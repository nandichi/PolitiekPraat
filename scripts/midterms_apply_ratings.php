<?php
/**
 * Werkt de ratings, competitief-vlaggen en bron-URL's van de gegenereerde
 * Senaat- en Gouverneursraces bij naar de consensus van de forecasters
 * (Cook Political Report / 270toWin / Ballotpedia, stand juni 2026).
 *
 * Past includes/data/midterms_races_generated.php aan en behoudt de zittende
 * namen (die komen uit unitedstates/congress-legislators). Alleen rating,
 * is_competitive en source_url worden aangeraakt. Idempotent: opnieuw draaien
 * geeft hetzelfde resultaat.
 *
 * Belangrijke correctie: de bron-URL voor gouverneurs was
 * /2026-gubernatorial-election/ (404). De juiste 270toWin-URL is
 * /2026-governor-election/.
 *
 * Gebruik: php scripts/midterms_apply_ratings.php
 */

$file = __DIR__ . '/../includes/data/midterms_races_generated.php';
if (!is_readable($file)) {
    fwrite(STDERR, "Bestand niet leesbaar: {$file}\n");
    exit(1);
}
$data = require $file;
if (!is_array($data) || !isset($data['senate'], $data['governor'])) {
    fwrite(STDERR, "Onverwacht formaat in {$file}\n");
    exit(1);
}

$SENATE_SOURCE = 'https://www.270towin.com/2026-senate-election/';
$GOV_SOURCE = 'https://www.270towin.com/2026-governor-election/';

// [rating, is_competitive] per staat. Stand: Cook/270toWin, juni 2026.
$senateOverrides = [
    'AK' => ['lean_r', 1],   // Sullivan (R), Cook: Lean R
    'CO' => ['safe_d', 0],   // Cook: Solid D
    'GA' => ['lean_d', 1],   // Ossoff (D), Cook verschoof apr 2026 van Toss-up naar Lean D
    'IA' => ['lean_r', 1],   // verschoof juni 2026 van Likely naar Lean R
    'NC' => ['lean_d', 1],   // open zetel Tillis, Cook verschoof apr 2026 van Toss-up naar Lean D (Cooper vs Whatley)
    'TX' => ['lean_r', 1],   // Cornyn (R), Cook: Lean R
    'VA' => ['safe_d', 0],   // Warner (D), Cook: Solid D
    'OH' => ['tossup', 1],   // speciale verkiezing zetel Vance, Cook: Toss-up
];

$govOverrides = [
    'WI' => ['tossup', 1],   // open zetel, toss-up
    'FL' => ['lean_r', 1],   // open zetel, Lean R
    'GA' => ['lean_r', 1],   // open zetel, Lean R
    'OH' => ['lean_r', 1],   // open zetel, Lean R
    'KS' => ['lean_r', 1],   // open zetel (D verlaat), Lean R
    'CO' => ['lean_d', 1],   // open zetel, Lean D
    'CT' => ['lean_d', 1],   // Lean D
    'NM' => ['lean_d', 1],   // open zetel, Lean D
    'OR' => ['lean_d', 1],   // Kotek (D) verdedigt, Lean D
];

$changes = 0;
foreach ($data['senate'] as &$race) {
    $race['source_url'] = $SENATE_SOURCE;
    $code = $race['state_code'] ?? '';
    if (isset($senateOverrides[$code])) {
        [$rating, $comp] = $senateOverrides[$code];
        if (($race['rating'] ?? null) !== $rating || (int) ($race['is_competitive'] ?? 0) !== $comp) {
            $changes++;
        }
        $race['rating'] = $rating;
        $race['is_competitive'] = $comp;
    }
}
unset($race);

foreach ($data['governor'] as &$race) {
    $race['source_url'] = $GOV_SOURCE; // 404-fix
    $code = $race['state_code'] ?? '';
    if (isset($govOverrides[$code])) {
        [$rating, $comp] = $govOverrides[$code];
        if (($race['rating'] ?? null) !== $rating || (int) ($race['is_competitive'] ?? 0) !== $comp) {
            $changes++;
        }
        $race['rating'] = $rating;
        $race['is_competitive'] = $comp;
    }
}
unset($race);

$php = "<?php\n/**\n * GEGENEREERD door scripts/midterms_generate_baseline.php\n"
    . " * Ratings/bronnen bijgewerkt door scripts/midterms_apply_ratings.php\n"
    . " * (consensus Cook Political Report / 270toWin / Ballotpedia, juni 2026).\n"
    . " * Senaat (35) en Gouverneurs (36) voor 2026. Zittende namen uit\n"
    . " * unitedstates/congress-legislators.\n */\nreturn "
    . var_export($data, true) . ";\n";
file_put_contents($file, $php);

echo "Klaar: ratings bijgewerkt ({$changes} gewijzigd), bron-URL's gezet.\n";
echo "Senaat: " . count($data['senate']) . " races, Gouverneurs: " . count($data['governor']) . " races.\n";
