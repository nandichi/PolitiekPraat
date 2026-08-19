<?php
/**
 * Werkt de ratings, competitief-vlaggen en bron-URL's van de gegenereerde
 * Senaat- en Gouverneursraces bij naar de consensus van de forecasters
 * (Cook Political Report, gecontroleerd 19 augustus 2026).
 *
 * Past includes/data/midterms_races_generated.php aan en behoudt de zittende
 * namen (die komen uit unitedstates/congress-legislators). Daarnaast worden
 * inmiddels bevestigde kandidaten en open-zetelstatussen van de competitieve
 * races bijgewerkt. Idempotent: opnieuw draaien
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

$SENATE_SOURCE = 'https://www.cookpolitical.com/ratings/senate-race-ratings';
$GOV_SOURCE = 'https://www.270towin.com/2026-governor-election/';

// Volledige Cook-verdeling per staat, gecontroleerd op 19 augustus 2026.
// Totaal: 9 Solid D, 1 Likely D, 3 Lean D, 4 Toss Up,
// 2 Lean R, 2 Likely R en 14 Solid R = 35 races.
$senateOverrides = [
    'AL' => ['safe_r', 0], 'AK' => ['tossup', 1], 'AR' => ['safe_r', 0],
    'CO' => ['safe_d', 0], 'DE' => ['safe_d', 0], 'FL' => ['safe_r', 0],
    'GA' => ['lean_d', 1], 'ID' => ['safe_r', 0], 'IL' => ['safe_d', 0],
    'IA' => ['lean_r', 1], 'KS' => ['likely_r', 0], 'KY' => ['safe_r', 0],
    'LA' => ['safe_r', 0], 'ME' => ['tossup', 1], 'MA' => ['safe_d', 0],
    'MI' => ['tossup', 1], 'MN' => ['likely_d', 0], 'MS' => ['safe_r', 0],
    'MT' => ['safe_r', 0], 'NE' => ['likely_r', 0], 'NH' => ['lean_d', 1],
    'NJ' => ['safe_d', 0], 'NM' => ['safe_d', 0], 'NC' => ['lean_d', 1],
    'OH' => ['tossup', 1], 'OK' => ['safe_r', 0], 'OR' => ['safe_d', 0],
    'RI' => ['safe_d', 0], 'SC' => ['safe_r', 0], 'SD' => ['safe_r', 0],
    'TN' => ['safe_r', 0], 'TX' => ['lean_r', 1], 'VA' => ['safe_d', 0],
    'WV' => ['safe_r', 0], 'WY' => ['safe_r', 0],
];

// Bevestigde kandidaten en open-zetelstatus, gecontroleerd op 19 augustus.
// New Hampshire ontbreekt bewust: de primary is daar pas op 8 september.
$senateDetails = [
    'AK' => ['Mary Peltola', 'Dan Sullivan', 0, 'Mary Peltola en zittend senator Dan Sullivan bereikten na de open primary de verkiezing in november.'],
    'GA' => ['Jon Ossoff', 'Mike Collins', 0, 'Zittend Democratisch senator Jon Ossoff neemt het op tegen Republikeins afgevaardigde Mike Collins.'],
    'IA' => ['Josh Turek', 'Ashley Hinson', 1, 'Na het vertrek van Joni Ernst strijden Josh Turek en Ashley Hinson om deze open zetel.'],
    'ME' => ['Troy Jackson', 'Susan Collins', 0, 'Troy Jackson werd na de terugtrekking van de oorspronkelijke Democratische winnaar de tegenstander van Susan Collins.'],
    'MI' => ['Abdul El-Sayed', 'Mike Rogers', 1, 'Abdul El-Sayed en Mike Rogers strijden om de open zetel van Gary Peters.'],
    'NC' => ['Roy Cooper', 'Michael Whatley', 1, 'Oud-gouverneur Roy Cooper en voormalig RNC-voorzitter Michael Whatley strijden om de open zetel.'],
    'OH' => ['Sherrod Brown', 'Jon Husted', 0, 'Voormalig senator Sherrod Brown probeert de speciale verkiezing te winnen van zittend senator Jon Husted.'],
    'TX' => ['James Talarico', 'Ken Paxton', 1, 'James Talarico en Ken Paxton strijden om de zetel waarvoor de zittende senator niet terugkeert.'],
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
    if (isset($senateDetails[$code])) {
        [$candidateD, $candidateR, $isOpen, $summary] = $senateDetails[$code];
        $race['candidate_d'] = $candidateD;
        $race['candidate_r'] = $candidateR;
        $race['is_open'] = $isOpen;
        $race['summary_nl'] = $summary;
    } elseif ($code === 'NH') {
        $race['is_open'] = 1;
        $race['summary_nl'] = 'De zetel van Jeanne Shaheen is open; de Democratische en Republikeinse voorverkiezingen volgen op 8 september.';
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
    . " * (Cook Political Report, gecontroleerd 19 augustus 2026).\n"
    . " * Senaat (35) en Gouverneurs (36) voor 2026. Zittende namen uit\n"
    . " * unitedstates/congress-legislators.\n */\nreturn "
    . var_export($data, true) . ";\n";
file_put_contents($file, $php);

echo "Klaar: ratings bijgewerkt ({$changes} gewijzigd), bron-URL's gezet.\n";
echo "Senaat: " . count($data['senate']) . " races, Gouverneurs: " . count($data['governor']) . " races.\n";
