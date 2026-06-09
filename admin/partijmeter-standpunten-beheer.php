<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../models/PartyModel.php';

$db = new Database();

// ---------------------------------------------------------------- POST acties
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_positions') {
    $questionId = (int) ($_POST['question_id'] ?? 0);
    $positions = $_POST['position'] ?? [];
    $explanations = $_POST['explanation'] ?? [];
    $sources = $_POST['source_url'] ?? [];
    $allowed = ['eens', 'neutraal', 'oneens'];

    try {
        foreach ($positions as $partyKey => $pos) {
            $partyKey = (string) $partyKey;
            $pos = (string) $pos;
            $expl = trim((string) ($explanations[$partyKey] ?? ''));
            $src = trim((string) ($sources[$partyKey] ?? ''));

            if (!in_array($pos, $allowed, true)) {
                // Leeg/onbekend: bestaand standpunt wissen
                $db->query('DELETE FROM partijmeter_positions WHERE question_id = :qid AND party_key = :pkey');
                $db->bind(':qid', $questionId);
                $db->bind(':pkey', $partyKey);
                $db->execute();
                continue;
            }

            $db->query('INSERT INTO partijmeter_positions (question_id, party_key, position, explanation, source_url)
                        VALUES (:qid, :pkey, :pos, :expl, :src)
                        ON DUPLICATE KEY UPDATE position = :pos2, explanation = :expl2, source_url = :src2, updated_at = NOW()');
            $db->bind(':qid', $questionId);
            $db->bind(':pkey', $partyKey);
            $db->bind(':pos', $pos);
            $db->bind(':expl', $expl !== '' ? $expl : null);
            $db->bind(':src', $src !== '' ? $src : null);
            $db->bind(':pos2', $pos);
            $db->bind(':expl2', $expl !== '' ? $expl : null);
            $db->bind(':src2', $src !== '' ? $src : null);
            $db->execute();
        }
        header('Location: partijmeter-standpunten-beheer.php?question_id=' . $questionId . '&msg=saved');
        exit;
    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}

$adminPageTitle = 'PartijMeter standpunten';
$adminPageDescription = 'Standpunten per stelling per partij, inclusief bron';
$adminActiveNav = 'partijmeter-standpunten';
require_once __DIR__ . '/partials/admin-header.php';

// Stellingen ophalen
try {
    $db->query('SELECT id, theme, title, order_number FROM partijmeter_questions ORDER BY order_number ASC, id ASC');
    $questions = $db->resultSet() ?: [];
} catch (Throwable $e) {
    $questions = [];
}

// Geselecteerde stelling
$selectedId = (int) ($_GET['question_id'] ?? 0);
if ($selectedId === 0 && !empty($questions)) {
    $selectedId = (int) $questions[0]->id;
}
$selectedIndex = -1;
$selectedQuestion = null;
foreach ($questions as $i => $q) {
    if ((int) $q->id === $selectedId) {
        $selectedIndex = $i;
        $selectedQuestion = $q;
        break;
    }
}

// Partijen (kolommen)
try {
    $parties = (new PartyModel())->getAllParties();
} catch (Throwable $e) {
    $parties = [];
}

// Bestaande standpunten voor de geselecteerde stelling
$existing = [];
if ($selectedQuestion) {
    try {
        $db->query('SELECT party_key, position, explanation, source_url FROM partijmeter_positions WHERE question_id = :qid');
        $db->bind(':qid', $selectedId);
        foreach (($db->resultSet() ?: []) as $row) {
            $existing[$row->party_key] = $row;
        }
    } catch (Throwable $e) {
        $existing = [];
    }
}

$filledCount = 0;
foreach ($parties as $key => $p) {
    if (isset($existing[$key])) {
        $filledCount++;
    }
}

$prevId = $selectedIndex > 0 ? (int) $questions[$selectedIndex - 1]->id : null;
$nextId = ($selectedIndex >= 0 && $selectedIndex < count($questions) - 1) ? (int) $questions[$selectedIndex + 1]->id : null;

$msg = $_GET['msg'] ?? '';
?>

<style>
.pm-seg { position: relative; display: block; }
.pm-seg input { position: absolute; opacity: 0; width: 0; height: 0; }
.pm-seg span {
    display: block; padding: 0.5rem 0.25rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;
    text-align: center; font-size: 0.8rem; font-weight: 600; color: #6b7280; cursor: pointer; transition: all .15s;
}
.pm-seg:hover span { border-color: #cbd5e1; }
.pm-seg--eens input:checked + span { background: #dcfce7; border-color: #16a34a; color: #166534; }
.pm-seg--neutraal input:checked + span { background: #dbeafe; border-color: #2563eb; color: #1e40af; }
.pm-seg--oneens input:checked + span { background: #fee2e2; border-color: #dc2626; color: #991b1b; }
.pm-seg--leeg input:checked + span { background: #f3f4f6; border-color: #9ca3af; color: #374151; }
</style>

<div class="admin-content__inner max-w-5xl">

    <?php if (!empty($errorMessage)): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-200 text-red-800"><?= pp_e($errorMessage) ?></div>
    <?php elseif ($msg === 'saved'): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-200 text-green-800">Standpunten opgeslagen.</div>
    <?php endif; ?>

    <?php if (empty($questions)): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <p class="text-gray-600 mb-4">Er zijn nog geen stellingen. Voeg eerst stellingen toe of draai de seed.</p>
            <a href="partijmeter-vragen-beheer.php" class="inline-block px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium">Naar stellingen</a>
        </div>
    <?php elseif (empty($parties)): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-600">Geen actieve partijen gevonden in political_parties.</div>
    <?php else: ?>

        <!-- Stelling-kiezer -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
            <div class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stelling</label>
                    <select onchange="location.href='partijmeter-standpunten-beheer.php?question_id=' + this.value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <?php foreach ($questions as $i => $q): ?>
                            <option value="<?= (int) $q->id ?>" <?= (int) $q->id === $selectedId ? 'selected' : '' ?>>
                                <?= (int) $q->order_number ?>. <?= pp_e(mb_strimwidth($q->title, 0, 80, '...')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <?php if ($prevId !== null): ?>
                        <a href="partijmeter-standpunten-beheer.php?question_id=<?= $prevId ?>" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Vorige</a>
                    <?php endif; ?>
                    <span class="text-sm text-gray-500 whitespace-nowrap">Stelling <?= $selectedIndex + 1 ?> / <?= count($questions) ?></span>
                    <?php if ($nextId !== null): ?>
                        <a href="partijmeter-standpunten-beheer.php?question_id=<?= $nextId ?>" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Volgende</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($selectedQuestion): ?>
                <div class="mt-4 p-4 rounded-lg bg-gray-50 border border-gray-100">
                    <div class="flex items-center justify-between gap-3">
                        <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 text-xs font-medium"><?= pp_e($selectedQuestion->theme) ?></span>
                        <span class="text-xs text-gray-500"><?= $filledCount ?>/<?= count($parties) ?> ingevuld</span>
                    </div>
                    <p class="mt-2 text-gray-800 font-medium"><?= pp_e($selectedQuestion->title) ?></p>
                    <a href="partijmeter-vragen-beheer.php?edit=<?= (int) $selectedQuestion->id ?>" class="text-indigo-600 text-xs font-medium">Stelling bewerken</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Standpunten-formulier -->
        <form method="POST">
            <input type="hidden" name="action" value="save_positions">
            <input type="hidden" name="question_id" value="<?= (int) $selectedId ?>">

            <div class="space-y-4">
                <?php foreach ($parties as $key => $party):
                    $cur = $existing[$key] ?? null;
                    $curPos = $cur->position ?? '';
                    $curExpl = $cur->explanation ?? '';
                    $curSrc = $cur->source_url ?? '';
                    $logo = $party['logo'] ?? '';
                ?>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <?php if ($logo): ?>
                                <img src="<?= pp_e($logo) ?>" alt="<?= pp_e($party['name']) ?>" class="w-8 h-8 object-contain rounded">
                            <?php else: ?>
                                <span class="w-8 h-8 rounded flex items-center justify-center text-white text-xs font-bold" style="background: <?= pp_e($party['color'] ?: '#64748b') ?>"><?= pp_e(mb_substr($party['name'], 0, 2)) ?></span>
                            <?php endif; ?>
                            <span class="font-semibold text-gray-800"><?= pp_e($party['name']) ?></span>
                        </div>

                        <div class="grid grid-cols-4 gap-2 mb-3">
                            <?php
                            $opts = [
                                'eens' => ['Eens', 'pm-seg--eens'],
                                'neutraal' => ['Neutraal', 'pm-seg--neutraal'],
                                'oneens' => ['Oneens', 'pm-seg--oneens'],
                                '' => ['Leeg', 'pm-seg--leeg'],
                            ];
                            foreach ($opts as $val => [$label, $cls]): ?>
                                <label class="pm-seg <?= $cls ?>">
                                    <input type="radio" name="position[<?= pp_e($key) ?>]" value="<?= pp_e((string) $val) ?>" <?= (string) $curPos === (string) $val ? 'checked' : '' ?>>
                                    <span><?= pp_e($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Toelichting</label>
                                <textarea name="explanation[<?= pp_e($key) ?>]" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= pp_e((string) $curExpl) ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Bron (URL)</label>
                                <input type="url" name="source_url[<?= pp_e($key) ?>]" value="<?= pp_e((string) $curSrc) ?>" placeholder="https://..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sticky bottom-0 mt-6 py-4 bg-linear-to-t from-white via-white to-transparent">
                <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Standpunten opslaan</button>
            </div>
        </form>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/admin-footer.php'; ?>
