<?php
declare(strict_types=1);

/**
 * Admin-editor voor de Midterms 2026 sectie.
 *
 * Lichtgewicht beheer voor:
 *   - Setup: migratie (tabellen aanmaken) + seed-import + status.
 *   - Races: ratings en details van Senaat-, Gouverneurs- en competitieve
 *     Huis-races bewerken.
 *   - Tijdlijn: gebeurtenissen toevoegen, bewerken en verwijderen.
 *
 * Beveiliging loopt via admin/_bootstrap.php -> _guard.php (alleen admins).
 */

require_once __DIR__ . '/_bootstrap.php';
require_once BASE_PATH . '/models/MidtermsModel.php';

$db = new Database();
$message = '';
$messageType = '';
$logOutput = '';

$ratingMeta = MidtermsModel::ratingMeta();
$ratingKeys = array_keys($ratingMeta);
$chamberLabels = ['senate' => 'Senaat', 'house' => 'Huis', 'governor' => 'Gouverneur'];
$categoryLabels = ['event' => 'Gebeurtenis', 'primary' => 'Voorverkiezing', 'deadline' => 'Deadline'];

/** Bestaat een tabel? */
$mtTableExists = static function (Database $db, string $table): bool {
    $db->query('SHOW TABLES LIKE :t');
    $db->bind(':t', $table);
    return (bool) $db->single();
};

/** Aantal rijen in een tabel (0 als de tabel niet bestaat). */
$mtCount = static function (Database $db, string $table): int {
    try {
        $db->query("SELECT COUNT(*) AS c FROM `{$table}`");
        $row = $db->single();
        return $row ? (int) $row->c : 0;
    } catch (Throwable $e) {
        return 0;
    }
};

/** Lege string -> null (voor optionele kolommen). */
$mtNullable = static function ($value): ?string {
    if ($value === null) {
        return null;
    }
    $value = trim((string) $value);
    return $value === '' ? null : $value;
};

// ---------------------------------------------------------------------------
// POST-afhandeling
// ---------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'run_migration':
                $migrationPath = BASE_PATH . '/database/migrations/create_midterms_tables.sql';
                $sql = is_readable($migrationPath) ? file_get_contents($migrationPath) : false;
                if ($sql === false) {
                    throw new RuntimeException('Migratiebestand niet leesbaar.');
                }
                $db->getConnection()->exec($sql);
                $message = 'Migratie uitgevoerd. De midterms-tabellen bestaan nu.';
                $messageType = 'success';
                break;

            case 'import_seed':
                ob_start();
                include BASE_PATH . '/scripts/seed_midterms.php';
                $logOutput = (string) ob_get_clean();
                if (stripos($logOutput, 'FOUT') !== false) {
                    $message = 'Seed-import meldde een fout, zie de uitvoer hieronder.';
                    $messageType = 'error';
                } else {
                    $message = 'Seed-data geimporteerd in de database.';
                    $messageType = 'success';
                }
                break;

            case 'save_race_rating':
                $raceId = (int) ($_POST['race_id'] ?? 0);
                $rating = (string) ($_POST['rating'] ?? 'tossup');
                if (!in_array($rating, $ratingKeys, true)) {
                    throw new RuntimeException('Ongeldige rating.');
                }
                $isCompetitive = isset($_POST['is_competitive']) ? 1 : 0;
                $db->query('UPDATE midterms_races SET rating = :rating, is_competitive = :comp WHERE id = :id');
                $db->bind(':rating', $rating);
                $db->bind(':comp', $isCompetitive);
                $db->bind(':id', $raceId);
                $db->execute();
                $message = 'Rating bijgewerkt.';
                $messageType = 'success';
                break;

            case 'save_race':
                $raceId = (int) ($_POST['race_id'] ?? 0);
                $chamber = (string) ($_POST['chamber'] ?? '');
                if (!isset($chamberLabels[$chamber])) {
                    throw new RuntimeException('Ongeldige kamer.');
                }
                $rating = (string) ($_POST['rating'] ?? 'tossup');
                if (!in_array($rating, $ratingKeys, true)) {
                    throw new RuntimeException('Ongeldige rating.');
                }
                $party = $mtNullable($_POST['incumbent_party'] ?? null);
                if ($party !== null && !in_array($party, ['D', 'R', 'I'], true)) {
                    throw new RuntimeException('Ongeldige partij.');
                }
                $stateCode = strtoupper(trim((string) ($_POST['state_code'] ?? '')));
                if (strlen($stateCode) !== 2) {
                    throw new RuntimeException('Staatcode moet 2 letters zijn.');
                }
                $district = $mtNullable($_POST['district'] ?? null);
                $fields = [
                    'chamber'         => $chamber,
                    'state_code'      => $stateCode,
                    'state_name'      => trim((string) ($_POST['state_name'] ?? '')),
                    'district'        => $district,
                    'incumbent_name'  => $mtNullable($_POST['incumbent_name'] ?? null),
                    'incumbent_party' => $party,
                    'is_open'         => isset($_POST['is_open']) ? 1 : 0,
                    'rating'          => $rating,
                    'is_competitive'  => isset($_POST['is_competitive']) ? 1 : 0,
                    'candidate_d'     => $mtNullable($_POST['candidate_d'] ?? null),
                    'candidate_r'     => $mtNullable($_POST['candidate_r'] ?? null),
                    'summary_nl'      => $mtNullable($_POST['summary_nl'] ?? null),
                    'source_url'      => $mtNullable($_POST['source_url'] ?? null),
                    'sort_order'      => (int) ($_POST['sort_order'] ?? 0),
                ];

                if ($raceId > 0) {
                    $set = implode(', ', array_map(static fn ($k) => "`{$k}` = :{$k}", array_keys($fields)));
                    $db->query("UPDATE midterms_races SET {$set} WHERE id = :__id");
                    foreach ($fields as $k => $v) {
                        $db->bind(":{$k}", $v);
                    }
                    $db->bind(':__id', $raceId);
                    $db->execute();
                    $message = 'Race bijgewerkt.';
                } else {
                    $cols = implode(', ', array_map(static fn ($k) => "`{$k}`", array_keys($fields)));
                    $ph = implode(', ', array_map(static fn ($k) => ":{$k}", array_keys($fields)));
                    $db->query("INSERT INTO midterms_races ({$cols}) VALUES ({$ph})");
                    foreach ($fields as $k => $v) {
                        $db->bind(":{$k}", $v);
                    }
                    $db->execute();
                    $message = 'Nieuwe race toegevoegd.';
                }
                $messageType = 'success';
                break;

            case 'delete_race':
                $raceId = (int) ($_POST['race_id'] ?? 0);
                $db->query('DELETE FROM midterms_races WHERE id = :id');
                $db->bind(':id', $raceId);
                $db->execute();
                $message = 'Race verwijderd.';
                $messageType = 'success';
                break;

            case 'save_event':
                $eventId = (int) ($_POST['event_id'] ?? 0);
                $eventDate = trim((string) ($_POST['event_date'] ?? ''));
                $title = trim((string) ($_POST['title_nl'] ?? ''));
                if ($eventDate === '' || $title === '') {
                    throw new RuntimeException('Datum en titel zijn verplicht.');
                }
                $fields = [
                    'event_date'     => $eventDate,
                    'title_nl'       => $title,
                    'description_nl' => $mtNullable($_POST['description_nl'] ?? null),
                    'category'       => (string) ($_POST['category'] ?? 'event'),
                    'state_code'     => $mtNullable($_POST['state_code'] ?? null),
                    'source_url'     => $mtNullable($_POST['source_url'] ?? null),
                    'is_published'   => isset($_POST['is_published']) ? 1 : 0,
                    'sort_order'     => (int) ($_POST['sort_order'] ?? 0),
                ];
                if ($eventId > 0) {
                    $set = implode(', ', array_map(static fn ($k) => "`{$k}` = :{$k}", array_keys($fields)));
                    $db->query("UPDATE midterms_timeline SET {$set} WHERE id = :__id");
                    foreach ($fields as $k => $v) {
                        $db->bind(":{$k}", $v);
                    }
                    $db->bind(':__id', $eventId);
                    $db->execute();
                    $message = 'Gebeurtenis bijgewerkt.';
                } else {
                    $cols = implode(', ', array_map(static fn ($k) => "`{$k}`", array_keys($fields)));
                    $ph = implode(', ', array_map(static fn ($k) => ":{$k}", array_keys($fields)));
                    $db->query("INSERT INTO midterms_timeline ({$cols}) VALUES ({$ph})");
                    foreach ($fields as $k => $v) {
                        $db->bind(":{$k}", $v);
                    }
                    $db->execute();
                    $message = 'Gebeurtenis toegevoegd.';
                }
                $messageType = 'success';
                break;

            case 'delete_event':
                $eventId = (int) ($_POST['event_id'] ?? 0);
                $db->query('DELETE FROM midterms_timeline WHERE id = :id');
                $db->bind(':id', $eventId);
                $db->execute();
                $message = 'Gebeurtenis verwijderd.';
                $messageType = 'success';
                break;
        }
    } catch (Throwable $e) {
        $message = 'Fout: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// ---------------------------------------------------------------------------
// Data laden voor weergave
// ---------------------------------------------------------------------------
$tablesReady = $mtTableExists($db, 'midterms_races')
    && $mtTableExists($db, 'midterms_timeline')
    && $mtTableExists($db, 'midterms_referenda');

$status = [
    'races'     => ['exists' => $mtTableExists($db, 'midterms_races'),     'count' => 0],
    'timeline'  => ['exists' => $mtTableExists($db, 'midterms_timeline'),  'count' => 0],
    'referenda' => ['exists' => $mtTableExists($db, 'midterms_referenda'), 'count' => 0],
    'odds'      => ['exists' => $mtTableExists($db, 'midterms_odds_cache'), 'count' => 0],
    'news'      => ['exists' => $mtTableExists($db, 'midterms_news_cache'), 'count' => 0],
];
foreach ($status as $key => $info) {
    $table = 'midterms_' . ($key === 'odds' ? 'odds_cache' : ($key === 'news' ? 'news_cache' : $key));
    if ($info['exists']) {
        $status[$key]['count'] = $mtCount($db, $table);
    }
}

$tab = $_GET['tab'] ?? 'setup';
if (!in_array($tab, ['setup', 'races', 'timeline'], true)) {
    $tab = 'setup';
}

$races = [];
$editRace = null;
$chamberFilter = $_GET['chamber'] ?? 'senate';
if (!in_array($chamberFilter, ['senate', 'house', 'governor', 'all'], true)) {
    $chamberFilter = 'senate';
}

if ($tab === 'races' && $tablesReady) {
    if (!empty($_GET['edit_race'])) {
        $db->query('SELECT * FROM midterms_races WHERE id = :id');
        $db->bind(':id', (int) $_GET['edit_race']);
        $editRace = $db->single() ?: null;
    }
    $sql = 'SELECT * FROM midterms_races';
    if ($chamberFilter !== 'all') {
        $sql .= ' WHERE chamber = :chamber';
    }
    $sql .= " ORDER BY is_competitive DESC,
              FIELD(rating,'tossup','lean_d','lean_r','likely_d','likely_r','safe_d','safe_r'),
              state_name ASC, district ASC";
    $db->query($sql);
    if ($chamberFilter !== 'all') {
        $db->bind(':chamber', $chamberFilter);
    }
    $races = $db->resultSet();
}

$events = [];
$editEvent = null;
if ($tab === 'timeline' && $tablesReady) {
    if (!empty($_GET['edit_event'])) {
        $db->query('SELECT * FROM midterms_timeline WHERE id = :id');
        $db->bind(':id', (int) $_GET['edit_event']);
        $editEvent = $db->single() ?: null;
    }
    $db->query('SELECT * FROM midterms_timeline ORDER BY event_date DESC, sort_order ASC');
    $events = $db->resultSet();
}

$usStates = MidtermsModel::usStates();

/** Tailwind-klasse voor een rating-badge. */
$ratingBadgeClass = static function (string $rating) use ($ratingMeta): string {
    $party = $ratingMeta[$rating]['party'] ?? null;
    if ($party === 'D') {
        return 'bg-blue-100 text-blue-800';
    }
    if ($party === 'R') {
        return 'bg-red-100 text-red-800';
    }
    return 'bg-yellow-100 text-yellow-800';
};

$adminPageTitle = 'Midterms 2026';
$adminPageDescription = 'Races, ratings en tijdlijn beheren';
$adminActiveNav = 'midterms';
require_once __DIR__ . '/partials/admin-header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<div class="max-w-7xl mx-auto">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Midterms 2026 beheer</h1>
            <p class="text-gray-600 text-sm">Beheer races, ratings en de tijdlijn voor de sectie <code>/midterms-2026</code>.</p>
        </div>
        <a href="<?= pp_e(pp_url('/midterms-2026')) ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <i class="fas fa-up-right-from-square"></i> Bekijk de pagina
        </a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (!$tablesReady): ?>
        <div class="mb-6 p-4 rounded-lg bg-yellow-50 text-yellow-800 border border-yellow-300">
            <i class="fas fa-triangle-exclamation mr-1"></i>
            De midterms-tabellen bestaan nog niet (of niet allemaal). Draai eerst de migratie en daarna de seed-import bij <strong>Setup</strong>.
            Tot die tijd toont de publieke pagina de seed-data uit het bestand.
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="flex gap-1 border-b border-gray-200 mb-6">
        <?php
        $tabs = ['setup' => 'Setup en status', 'races' => 'Races en ratings', 'timeline' => 'Tijdlijn'];
        foreach ($tabs as $key => $label):
            $isActive = $tab === $key;
        ?>
            <a href="?tab=<?= $key ?>"
               class="px-4 py-2 -mb-px border-b-2 text-sm font-medium transition-colors <?= $isActive ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-800' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($tab === 'setup'): ?>
        <!-- ================= SETUP ================= -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <?php
            $statusCards = [
                'races' => 'Races', 'timeline' => 'Tijdlijn', 'referenda' => 'Referenda',
                'odds' => 'Odds-cache', 'news' => 'Nieuws-cache',
            ];
            foreach ($statusCards as $key => $label):
            ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($label) ?></div>
                    <div class="mt-1 text-2xl font-bold text-gray-900"><?= (int) $status[$key]['count'] ?></div>
                    <div class="mt-1 text-xs <?= $status[$key]['exists'] ? 'text-green-600' : 'text-red-600' ?>">
                        <i class="fas <?= $status[$key]['exists'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                        <?= $status[$key]['exists'] ? 'tabel aanwezig' : 'tabel ontbreekt' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2"><i class="fas fa-database mr-2 text-blue-600"></i>1. Migratie</h2>
                <p class="text-gray-600 text-sm mb-4">Maakt de tabellen <code>midterms_races</code>, <code>midterms_timeline</code>, <code>midterms_referenda</code>, <code>midterms_odds_cache</code> en <code>midterms_news_cache</code> aan. Veilig om opnieuw te draaien (IF NOT EXISTS).</p>
                <form method="POST" onsubmit="this.querySelector('button').disabled=true;">
                    <input type="hidden" name="action" value="run_migration">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <i class="fas fa-play mr-1"></i> Migratie uitvoeren
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2"><i class="fas fa-seedling mr-2 text-green-600"></i>2. Seed-import</h2>
                <p class="text-gray-600 text-sm mb-4">Vult races (Senaat + Gouverneurs + competitieve Huis-districten), de tijdlijn en de referenda uit het seed-bestand. Idempotent: bestaande rijen worden bijgewerkt.</p>
                <form method="POST" onsubmit="this.querySelector('button').disabled=true;">
                    <input type="hidden" name="action" value="import_seed">
                    <button type="submit" <?= $tablesReady ? '' : 'disabled' ?>
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-file-import mr-1"></i> Seed-data importeren
                    </button>
                    <?php if (!$tablesReady): ?>
                        <p class="text-xs text-gray-500 mt-2">Draai eerst de migratie.</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if ($logOutput !== ''): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700"><i class="fas fa-terminal mr-2"></i>Uitvoer</h3>
                </div>
                <div class="p-4">
                    <pre class="text-xs text-gray-800 bg-gray-50 rounded p-3 overflow-x-auto"><?= htmlspecialchars($logOutput) ?></pre>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($tab === 'races'): ?>
        <!-- ================= RACES ================= -->
        <?php if (!$tablesReady): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                Draai eerst de migratie en seed-import bij <a href="?tab=setup" class="text-blue-600 underline">Setup</a>.
            </div>
        <?php else: ?>

            <?php if ($editRace !== null || (isset($_GET['new_race']))): ?>
                <?php
                $r = $editRace;
                $isNew = $editRace === null;
                ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <?= $isNew ? 'Nieuwe race toevoegen' : 'Race bewerken' ?>
                        </h2>
                        <a href="?tab=races&chamber=<?= pp_e($chamberFilter) ?>" class="text-sm text-gray-500 hover:text-gray-800"><i class="fas fa-xmark mr-1"></i>Sluiten</a>
                    </div>
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="hidden" name="action" value="save_race">
                        <input type="hidden" name="race_id" value="<?= $isNew ? 0 : (int) $r->id ?>">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kamer</label>
                            <select name="chamber" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <?php foreach ($chamberLabels as $ck => $cl): ?>
                                    <option value="<?= $ck ?>" <?= (!$isNew && $r->chamber === $ck) ? 'selected' : '' ?>><?= htmlspecialchars($cl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <select name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <?php foreach ($ratingMeta as $rk => $rm): ?>
                                    <option value="<?= $rk ?>" <?= (!$isNew && $r->rating === $rk) ? 'selected' : '' ?>><?= htmlspecialchars($rm['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Staatcode (2 letters)</label>
                            <input type="text" name="state_code" maxlength="2" required
                                   value="<?= $isNew ? '' : pp_e($r->state_code) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Staatnaam</label>
                            <input type="text" name="state_name"
                                   value="<?= $isNew ? '' : pp_e($r->state_name) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">District (leeg voor Senaat/Gouverneur)</label>
                            <input type="text" name="district" maxlength="8"
                                   value="<?= $isNew ? '' : pp_e((string) ($r->district ?? '')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zittende partij</label>
                            <select name="incumbent_party" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Onbekend</option>
                                <?php foreach (['D' => 'Democraat', 'R' => 'Republikein', 'I' => 'Onafhankelijk'] as $pk => $pl): ?>
                                    <option value="<?= $pk ?>" <?= (!$isNew && ($r->incumbent_party ?? '') === $pk) ? 'selected' : '' ?>><?= htmlspecialchars($pl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zittende naam</label>
                            <input type="text" name="incumbent_name"
                                   value="<?= $isNew ? '' : pp_e((string) ($r->incumbent_name ?? '')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sorteervolgorde</label>
                            <input type="number" name="sort_order"
                                   value="<?= $isNew ? '0' : (int) ($r->sort_order ?? 0) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kandidaat (D)</label>
                            <input type="text" name="candidate_d"
                                   value="<?= $isNew ? '' : pp_e((string) ($r->candidate_d ?? '')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kandidaat (R)</label>
                            <input type="text" name="candidate_r"
                                   value="<?= $isNew ? '' : pp_e((string) ($r->candidate_r ?? '')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Samenvatting (NL)</label>
                            <textarea name="summary_nl" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"><?= $isNew ? '' : pp_e((string) ($r->summary_nl ?? '')) ?></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bron-URL</label>
                            <input type="url" name="source_url"
                                   value="<?= $isNew ? '' : pp_e((string) ($r->source_url ?? '')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>

                        <div class="flex items-center gap-6 md:col-span-2">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_competitive" value="1" <?= (!$isNew && (int) $r->is_competitive === 1) ? 'checked' : '' ?>>
                                Competitief
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_open" value="1" <?= (!$isNew && (int) ($r->is_open ?? 0) === 1) ? 'checked' : '' ?>>
                                Open zetel (geen zittende)
                            </label>
                        </div>

                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <i class="fas fa-floppy-disk mr-1"></i> Opslaan
                            </button>
                            <a href="?tab=races&chamber=<?= pp_e($chamberFilter) ?>" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition-colors text-sm">Annuleren</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div class="flex gap-1">
                    <?php foreach (['senate' => 'Senaat', 'governor' => 'Gouverneurs', 'house' => 'Huis (competitief)', 'all' => 'Alles'] as $ck => $cl): ?>
                        <a href="?tab=races&chamber=<?= $ck ?>"
                           class="px-3 py-1.5 rounded-md text-sm <?= $chamberFilter === $ck ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                            <?= htmlspecialchars($cl) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <a href="?tab=races&chamber=<?= pp_e($chamberFilter) ?>&new_race=1"
                   class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-sm">
                    <i class="fas fa-plus"></i> Nieuwe race
                </a>
            </div>

            <?php if ($chamberFilter === 'house'): ?>
                <p class="text-xs text-gray-500 mb-3">Let op: voor het Huis staan alleen de competitieve districten in de database. De volledige baseline (alle 435) komt uit het seed-bestand en kleurt de kaart.</p>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Races (<?= count($races) ?>)</h3>
                </div>
                <?php if (empty($races)): ?>
                    <div class="p-8 text-center text-gray-500 text-sm">Geen races gevonden voor deze selectie.</div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 text-left">Kamer</th>
                                    <th class="px-4 py-2 text-left">Staat / district</th>
                                    <th class="px-4 py-2 text-left">Zittende</th>
                                    <th class="px-4 py-2 text-left">Rating &amp; competitief</th>
                                    <th class="px-4 py-2 text-right">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($races as $race): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($chamberLabels[$race->chamber] ?? $race->chamber) ?></td>
                                        <td class="px-4 py-2">
                                            <span class="font-medium text-gray-900"><?= pp_e($race->state_name) ?></span>
                                            <?php if (!empty($race->district)): ?>
                                                <span class="text-gray-500">- district <?= pp_e($race->district) ?></span>
                                            <?php endif; ?>
                                            <span class="text-gray-400">(<?= pp_e($race->state_code) ?>)</span>
                                        </td>
                                        <td class="px-4 py-2 text-gray-600">
                                            <?= pp_e((string) ($race->incumbent_name ?? '-')) ?>
                                            <?php if (!empty($race->incumbent_party)): ?>
                                                <span class="text-gray-400">(<?= pp_e($race->incumbent_party) ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-2">
                                            <form method="POST" class="flex items-center gap-2">
                                                <input type="hidden" name="action" value="save_race_rating">
                                                <input type="hidden" name="race_id" value="<?= (int) $race->id ?>">
                                                <select name="rating" class="px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <?php foreach ($ratingMeta as $rk => $rm): ?>
                                                        <option value="<?= $rk ?>" <?= $race->rating === $rk ? 'selected' : '' ?>><?= htmlspecialchars($rm['short']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label class="inline-flex items-center gap-1 text-xs text-gray-600">
                                                    <input type="checkbox" name="is_competitive" value="1" <?= (int) $race->is_competitive === 1 ? 'checked' : '' ?>>
                                                    comp.
                                                </label>
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs" title="Rating opslaan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-2 text-right whitespace-nowrap">
                                            <a href="?tab=races&chamber=<?= pp_e($chamberFilter) ?>&edit_race=<?= (int) $race->id ?>" class="text-gray-600 hover:text-blue-700 mr-3" title="Bewerken"><i class="fas fa-pen"></i></a>
                                            <form method="POST" class="inline" onsubmit="return confirm('Deze race verwijderen?');">
                                                <input type="hidden" name="action" value="delete_race">
                                                <input type="hidden" name="race_id" value="<?= (int) $race->id ?>">
                                                <button type="submit" class="text-gray-400 hover:text-red-600" title="Verwijderen"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: // tab === timeline ?>
        <!-- ================= TIJDLIJN ================= -->
        <?php if (!$tablesReady): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                Draai eerst de migratie en seed-import bij <a href="?tab=setup" class="text-blue-600 underline">Setup</a>.
            </div>
        <?php else: ?>
            <?php $ev = $editEvent; $isNewEvent = $editEvent === null; ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4"><?= $isNewEvent ? 'Nieuwe gebeurtenis' : 'Gebeurtenis bewerken' ?></h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="action" value="save_event">
                    <input type="hidden" name="event_id" value="<?= $isNewEvent ? 0 : (int) $ev->id ?>">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                        <input type="date" name="event_date" required
                               value="<?= $isNewEvent ? '' : pp_e((string) $ev->event_date) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categorie</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <?php foreach ($categoryLabels as $catKey => $catLabel): ?>
                                <option value="<?= $catKey ?>" <?= (!$isNewEvent && $ev->category === $catKey) ? 'selected' : '' ?>><?= htmlspecialchars($catLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titel (NL)</label>
                        <input type="text" name="title_nl" required
                               value="<?= $isNewEvent ? '' : pp_e((string) $ev->title_nl) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Omschrijving (NL)</label>
                        <textarea name="description_nl" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"><?= $isNewEvent ? '' : pp_e((string) ($ev->description_nl ?? '')) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Staatcode (optioneel)</label>
                        <input type="text" name="state_code" maxlength="2"
                               value="<?= $isNewEvent ? '' : pp_e((string) ($ev->state_code ?? '')) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sorteervolgorde</label>
                        <input type="number" name="sort_order"
                               value="<?= $isNewEvent ? '0' : (int) ($ev->sort_order ?? 0) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bron-URL (optioneel)</label>
                        <input type="url" name="source_url"
                               value="<?= $isNewEvent ? '' : pp_e((string) ($ev->source_url ?? '')) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_published" value="1" <?= ($isNewEvent || (int) $ev->is_published === 1) ? 'checked' : '' ?>>
                            Gepubliceerd
                        </label>
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            <i class="fas fa-floppy-disk mr-1"></i> <?= $isNewEvent ? 'Toevoegen' : 'Opslaan' ?>
                        </button>
                        <?php if (!$isNewEvent): ?>
                            <a href="?tab=timeline" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition-colors text-sm">Nieuw</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Gebeurtenissen (<?= count($events) ?>)</h3>
                </div>
                <?php if (empty($events)): ?>
                    <div class="p-8 text-center text-gray-500 text-sm">Nog geen gebeurtenissen.</div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 text-left">Datum</th>
                                    <th class="px-4 py-2 text-left">Titel</th>
                                    <th class="px-4 py-2 text-left">Categorie</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-right">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($events as $event): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-600"><?= pp_e(date('d-m-Y', strtotime((string) $event->event_date))) ?></td>
                                        <td class="px-4 py-2 font-medium text-gray-900"><?= pp_e((string) $event->title_nl) ?></td>
                                        <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($categoryLabels[$event->category] ?? $event->category) ?></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-0.5 rounded-full text-xs <?= (int) $event->is_published === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?>">
                                                <?= (int) $event->is_published === 1 ? 'Gepubliceerd' : 'Verborgen' ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right whitespace-nowrap">
                                            <a href="?tab=timeline&edit_event=<?= (int) $event->id ?>" class="text-gray-600 hover:text-blue-700 mr-3" title="Bewerken"><i class="fas fa-pen"></i></a>
                                            <form method="POST" class="inline" onsubmit="return confirm('Deze gebeurtenis verwijderen?');">
                                                <input type="hidden" name="action" value="delete_event">
                                                <input type="hidden" name="event_id" value="<?= (int) $event->id ?>">
                                                <button type="submit" class="text-gray-400 hover:text-red-600" title="Verwijderen"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
<?php require_once __DIR__ . '/partials/admin-footer.php';
