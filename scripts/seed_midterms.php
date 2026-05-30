<?php
/**
 * Seed-importer voor de Midterms 2026 sectie.
 *
 * Vult de tabellen midterms_races, midterms_timeline en midterms_referenda met
 * de v1-content uit includes/data/midterms_2026_seed.php. De import is
 * idempotent: bestaande rijen worden bijgewerkt op basis van een logische
 * sleutel, nieuwe rijen worden toegevoegd. Zo kun je de import veilig opnieuw
 * draaien zonder dubbele rijen.
 *
 * Odds en nieuws worden bewust NIET geseed: die komen via de cron-scripts
 * (Polymarket / Brave). Zolang die tabellen leeg zijn valt het model terug op
 * de seed-momentopname.
 *
 * Gebruik (CLI):   php scripts/seed_midterms.php
 * Of include vanuit de admin; de uitvoer wordt dan met output buffering
 * opgevangen. Daarom gebruiken we hier `return` in plaats van `exit`.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

if (!function_exists('mt_seed_pdo_type')) {
    /** Bepaal het PDO-bindtype voor een waarde. */
    function mt_seed_pdo_type($value): int
    {
        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }
        if (is_int($value) || is_bool($value)) {
            return PDO::PARAM_INT;
        }
        return PDO::PARAM_STR;
    }
}

if (!function_exists('mt_seed_upsert')) {
    /**
     * Voeg een rij toe of werk een bestaande bij (NULL-veilige match).
     *
     * @param array<string,mixed> $fields    Kolom => waarde (volledige rij).
     * @param string[]            $matchKeys Kolommen die samen de logische sleutel vormen.
     */
    function mt_seed_upsert(PDO $pdo, string $table, array $fields, array $matchKeys, int &$inserted, int &$updated): void
    {
        $where = implode(' AND ', array_map(static fn ($k) => "`{$k}` <=> ?", $matchKeys));
        $sel = $pdo->prepare("SELECT id FROM `{$table}` WHERE {$where} LIMIT 1");
        $sel->execute(array_map(static fn ($k) => $fields[$k] ?? null, $matchKeys));
        $id = $sel->fetchColumn();

        if ($id !== false) {
            $set = implode(', ', array_map(static fn ($k) => "`{$k}` = :{$k}", array_keys($fields)));
            $stmt = $pdo->prepare("UPDATE `{$table}` SET {$set} WHERE id = :__id");
            foreach ($fields as $k => $v) {
                $stmt->bindValue(":{$k}", $v, mt_seed_pdo_type($v));
            }
            $stmt->bindValue(':__id', (int) $id, PDO::PARAM_INT);
            $stmt->execute();
            $updated++;
            return;
        }

        $cols = implode(', ', array_map(static fn ($k) => "`{$k}`", array_keys($fields)));
        $placeholders = implode(', ', array_map(static fn ($k) => ":{$k}", array_keys($fields)));
        $stmt = $pdo->prepare("INSERT INTO `{$table}` ({$cols}) VALUES ({$placeholders})");
        foreach ($fields as $k => $v) {
            $stmt->bindValue(":{$k}", $v, mt_seed_pdo_type($v));
        }
        $stmt->execute();
        $inserted++;
    }
}

echo "Midterms 2026 - seed-import\n";
echo "===========================\n\n";

$seedPath = __DIR__ . '/../includes/data/midterms_2026_seed.php';
if (!is_readable($seedPath)) {
    echo "FOUT: seed-bestand niet leesbaar: {$seedPath}\n";
    return;
}
$seed = require $seedPath;
if (!is_array($seed)) {
    echo "FOUT: seed-bestand gaf geen array terug.\n";
    return;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();
} catch (Throwable $e) {
    echo "FOUT: geen databaseverbinding: " . $e->getMessage() . "\n";
    return;
}

// Controleer of de tabellen bestaan; zo niet, eerst de migratie draaien.
foreach (['midterms_races', 'midterms_timeline', 'midterms_referenda'] as $table) {
    $check = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($table));
    if ($check === false || $check->fetchColumn() === false) {
        echo "FOUT: tabel {$table} bestaat niet. Draai eerst de migratie ";
        echo "(php scripts/run_midterms_migration.php).\n";
        return;
    }
}

try {
    // ----------------------------------------------------------------
    // Races (Senaat + Gouverneurs + competitieve Huis-districten)
    // ----------------------------------------------------------------
    $racesIns = 0;
    $racesUpd = 0;
    foreach (($seed['races'] ?? []) as $race) {
        $chamber = $race['chamber'] ?? null;
        $stateCode = $race['state_code'] ?? null;
        if ($chamber === null || $stateCode === null) {
            continue;
        }
        $candidatesJson = isset($race['candidates']) && is_array($race['candidates'])
            ? json_encode($race['candidates'], JSON_UNESCAPED_UNICODE)
            : null;

        $fields = [
            'chamber'         => (string) $chamber,
            'state_code'      => (string) $stateCode,
            'state_name'      => (string) ($race['state_name'] ?? ''),
            'district'        => $race['district'] ?? null,
            'incumbent_name'  => $race['incumbent_name'] ?? null,
            'incumbent_party' => $race['incumbent_party'] ?? null,
            'is_open'         => (int) ($race['is_open'] ?? 0),
            'rating'          => (string) ($race['rating'] ?? 'tossup'),
            'is_competitive'  => (int) ($race['is_competitive'] ?? 0),
            'candidate_d'     => $race['candidate_d'] ?? null,
            'candidate_r'     => $race['candidate_r'] ?? null,
            'candidates_json' => $candidatesJson,
            'summary_nl'      => $race['summary_nl'] ?? null,
            'source_url'      => $race['source_url'] ?? null,
            'sort_order'      => (int) ($race['sort_order'] ?? 0),
        ];
        mt_seed_upsert($pdo, 'midterms_races', $fields, ['chamber', 'state_code', 'district'], $racesIns, $racesUpd);
    }
    echo "Races:     {$racesIns} toegevoegd, {$racesUpd} bijgewerkt.\n";

    // ----------------------------------------------------------------
    // Tijdlijn
    // ----------------------------------------------------------------
    $tlIns = 0;
    $tlUpd = 0;
    foreach (($seed['timeline'] ?? []) as $i => $event) {
        if (empty($event['event_date']) || empty($event['title_nl'])) {
            continue;
        }
        $fields = [
            'event_date'     => (string) $event['event_date'],
            'title_nl'       => (string) $event['title_nl'],
            'description_nl' => $event['description_nl'] ?? null,
            'category'       => (string) ($event['category'] ?? 'event'),
            'state_code'     => $event['state_code'] ?? null,
            'source_url'     => $event['source_url'] ?? null,
            'is_published'   => (int) ($event['is_published'] ?? 1),
            'sort_order'     => (int) ($event['sort_order'] ?? $i),
        ];
        mt_seed_upsert($pdo, 'midterms_timeline', $fields, ['event_date', 'title_nl'], $tlIns, $tlUpd);
    }
    echo "Tijdlijn:  {$tlIns} toegevoegd, {$tlUpd} bijgewerkt.\n";

    // ----------------------------------------------------------------
    // Referenda
    // ----------------------------------------------------------------
    $refIns = 0;
    $refUpd = 0;
    foreach (($seed['referenda'] ?? []) as $i => $ref) {
        if (empty($ref['title_nl'])) {
            continue;
        }
        $fields = [
            'state_code'      => $ref['state_code'] ?? null,
            'state_name'      => $ref['state_name'] ?? null,
            'measure_code'    => $ref['measure_code'] ?? null,
            'title_nl'        => (string) $ref['title_nl'],
            'theme'           => $ref['theme'] ?? null,
            'description_nl'  => $ref['description_nl'] ?? null,
            'polymarket_slug' => $ref['polymarket_slug'] ?? null,
            'yes_label_nl'    => $ref['yes_label_nl'] ?? null,
            'election_date'   => $ref['election_date'] ?? null,
            'source_url'      => $ref['source_url'] ?? null,
            'is_published'    => (int) ($ref['is_published'] ?? 1),
            'sort_order'      => (int) ($ref['sort_order'] ?? $i),
        ];
        mt_seed_upsert($pdo, 'midterms_referenda', $fields, ['state_code', 'title_nl'], $refIns, $refUpd);
    }
    echo "Referenda: {$refIns} toegevoegd, {$refUpd} bijgewerkt.\n";

    echo "\nSeed-import voltooid.\n";
} catch (Throwable $e) {
    echo "FOUT bij seed-import: " . $e->getMessage() . "\n";
    return;
}
