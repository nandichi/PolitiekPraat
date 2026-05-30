<?php
/**
 * Genereert de feitelijke basisdata voor de Midterms 2026 sectie uit de
 * openbare dataset unitedstates/congress-legislators en een gedocumenteerde
 * rating-tabel (partijneiging per staat). Schrijft twee bestanden:
 *
 *   includes/data/midterms_house_baseline.php   (huidige partij per district, 435)
 *   includes/data/midterms_races_generated.php  (Senaat 35 + Gouverneurs 36)
 *
 * Zittende namen voor de Senaat komen uit de dataset; ratings zijn een
 * baseline op basis van de partijneiging van de staat en bekende
 * competitieve races. Ze zijn bedoeld als startpunt en kunnen via de
 * admin-editor worden verfijnd.
 *
 * Gebruik:
 *   curl -fsL https://unitedstates.github.io/congress-legislators/legislators-current.json -o /tmp/legislators.json
 *   php scripts/midterms_generate_baseline.php /tmp/legislators.json
 */

$src = $argv[1] ?? '/tmp/mtbuild/legislators.json';
if (!is_readable($src)) {
    fwrite(STDERR, "Bronbestand niet leesbaar: {$src}\n");
    exit(1);
}

$stateNames = [
    'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'Californië',
    'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
    'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
    'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
    'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
    'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
    'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
    'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
    'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
    'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming',
];
$states50 = array_keys($stateNames);
$partyMap = ['Democrat' => 'D', 'Republican' => 'R', 'Independent' => 'I'];

$data = json_decode(file_get_contents($src), true);
if (!is_array($data)) {
    fwrite(STDERR, "Kan JSON niet parsen\n");
    exit(1);
}

$baseline = [];
$senByState = [];

foreach ($data as $p) {
    $terms = $p['terms'] ?? [];
    if (!$terms) {
        continue;
    }
    $t = end($terms);
    $state = $t['state'] ?? '';
    $party = $partyMap[$t['party'] ?? ''] ?? 'I';
    $name = $p['name']['official_full'] ?? trim(($p['name']['first'] ?? '') . ' ' . ($p['name']['last'] ?? ''));

    if (($t['type'] ?? '') === 'rep') {
        if (!in_array($state, $states50, true)) {
            continue;
        }
        $dist = (int) ($t['district'] ?? 0);
        $key = $state . '-' . ($dist === 0 ? 'AL' : (string) $dist);
        $baseline[$key] = $party;
    } elseif (($t['type'] ?? '') === 'sen') {
        $senByState[$state][(int) ($t['class'] ?? 0)] = ['name' => $name, 'party' => $party];
    }
}
ksort($baseline);

// --- Schrijf de Huis-baseline ---
$out1 = __DIR__ . '/../includes/data/midterms_house_baseline.php';
$lines = ['<?php', '/**', ' * GEGENEREERD door scripts/midterms_generate_baseline.php', ' * Huidige partij per Huis-district, bron: unitedstates/congress-legislators.', ' * Formaat: "STAAT-DISTRICT" => "D"|"R"|"I"  (at-large => "AL").', ' */', 'return ['];
foreach ($baseline as $k => $v) {
    $lines[] = "    '" . $k . "' => '" . $v . "',";
}
$lines[] = '];';
file_put_contents($out1, implode("\n", $lines) . "\n");
$counts = array_count_values($baseline);

// --- Senaat 2026: Klasse 2 (regulier) + Ohio/Florida (speciaal) ---
// rating + competitief + open per staat (baseline op partijneiging en bekende races).
$senMeta = [
    'AK' => ['likely_r', 0, 0], 'AL' => ['safe_r', 0, 0], 'AR' => ['safe_r', 0, 0],
    'CO' => ['likely_d', 0, 0], 'DE' => ['safe_d', 0, 0], 'GA' => ['tossup', 1, 0],
    'IA' => ['likely_r', 0, 0], 'ID' => ['safe_r', 0, 0], 'IL' => ['safe_d', 0, 0],
    'KS' => ['safe_r', 0, 0], 'KY' => ['safe_r', 0, 0], 'LA' => ['likely_r', 0, 0],
    'MA' => ['safe_d', 0, 0], 'ME' => ['tossup', 1, 0], 'MI' => ['tossup', 1, 1],
    'MN' => ['likely_d', 0, 1], 'MS' => ['safe_r', 0, 0], 'MT' => ['safe_r', 0, 0],
    'NC' => ['tossup', 1, 1], 'NE' => ['safe_r', 0, 0], 'NH' => ['lean_d', 1, 1],
    'NJ' => ['safe_d', 0, 0], 'NM' => ['likely_d', 0, 0], 'OK' => ['safe_r', 0, 0],
    'OR' => ['safe_d', 0, 0], 'RI' => ['safe_d', 0, 0], 'SC' => ['safe_r', 0, 0],
    'SD' => ['safe_r', 0, 0], 'TN' => ['safe_r', 0, 0], 'TX' => ['likely_r', 1, 0],
    'VA' => ['likely_d', 0, 0], 'WV' => ['safe_r', 0, 0], 'WY' => ['safe_r', 0, 0],
];
$senCompSummary = [
    'GA' => 'Georgia is opnieuw een van de dichtst bevochten Senaatszetels; de uitslag kan de meerderheid bepalen.',
    'ME' => 'In Maine verdedigt een Republikein een zetel in een staat die op presidentsniveau Democratisch stemt.',
    'MI' => 'Michigan is een open zetel in een klassieke swing state en geldt als toss-up.',
    'NC' => 'North Carolina kleurt al jaren net rood, maar Democraten zien hier een serieuze kans.',
    'NH' => 'New Hampshire is een open zetel die licht Democratisch neigt, maar competitief blijft.',
    'TX' => 'Texas is normaal stevig Republikeins; een verdeelde voorverkiezing kan de race spannender maken.',
];

$senateRaces = [];
foreach ($senMeta as $code => $meta) {
    $inc = $senByState[$code][2] ?? ['name' => 'nog onbekend', 'party' => 'R'];
    $senateRaces[] = buildRace('senate', $code, $stateNames[$code], null, $inc, $meta, $senCompSummary[$code] ?? null);
}
// Speciale verkiezingen 2026: Ohio (zetel Vance) en Florida (zetel Rubio) -> klasse 3 zittende.
$ohio = $senByState['OH'][3] ?? ['name' => 'nog onbekend', 'party' => 'R'];
$florida = $senByState['FL'][3] ?? ['name' => 'nog onbekend', 'party' => 'R'];
$senateRaces[] = buildRace('senate', 'OH', 'Ohio', null, $ohio, ['lean_r', 1, 0], 'Speciale verkiezing voor de zetel die vrijkwam toen J.D. Vance vicepresident werd.');
$senateRaces[] = buildRace('senate', 'FL', 'Florida', null, $florida, ['likely_r', 0, 0], 'Speciale verkiezing voor de zetel die vrijkwam na het vertrek van Marco Rubio.');

// --- Gouverneurs 2026 (cohort 2022, 36 staten) ---
// [partij, zittende/'open zetel'/'nog onbekend', open, rating, competitief]
$govs = [
    'AL' => ['R', 'Kay Ivey', 0, 'safe_r', 0],
    'AK' => ['R', 'open zetel', 1, 'likely_r', 0],
    'AZ' => ['D', 'Katie Hobbs', 0, 'tossup', 1],
    'AR' => ['R', 'Sarah Huckabee Sanders', 0, 'safe_r', 0],
    'CA' => ['D', 'open zetel', 1, 'safe_d', 0],
    'CO' => ['D', 'open zetel', 1, 'likely_d', 0],
    'CT' => ['D', 'Ned Lamont', 0, 'likely_d', 0],
    'FL' => ['R', 'open zetel', 1, 'likely_r', 1],
    'GA' => ['R', 'open zetel', 1, 'tossup', 1],
    'HI' => ['D', 'Josh Green', 0, 'safe_d', 0],
    'ID' => ['R', 'Brad Little', 0, 'safe_r', 0],
    'IL' => ['D', 'JB Pritzker', 0, 'safe_d', 0],
    'IA' => ['R', 'open zetel', 1, 'likely_r', 0],
    'KS' => ['D', 'open zetel', 1, 'tossup', 1],
    'ME' => ['D', 'open zetel', 1, 'lean_d', 1],
    'MD' => ['D', 'Wes Moore', 0, 'safe_d', 0],
    'MA' => ['D', 'Maura Healey', 0, 'safe_d', 0],
    'MI' => ['D', 'open zetel', 1, 'tossup', 1],
    'MN' => ['D', 'Tim Walz', 0, 'likely_d', 0],
    'NE' => ['R', 'Jim Pillen', 0, 'safe_r', 0],
    'NV' => ['R', 'Joe Lombardo', 0, 'tossup', 1],
    'NH' => ['R', 'Kelly Ayotte', 0, 'lean_r', 1],
    'NM' => ['D', 'open zetel', 1, 'likely_d', 0],
    'NY' => ['D', 'Kathy Hochul', 0, 'likely_d', 0],
    'OH' => ['R', 'open zetel', 1, 'likely_r', 1],
    'OK' => ['R', 'open zetel', 1, 'safe_r', 0],
    'OR' => ['D', 'Tina Kotek', 0, 'likely_d', 0],
    'PA' => ['D', 'Josh Shapiro', 0, 'likely_d', 0],
    'RI' => ['D', 'Dan McKee', 0, 'likely_d', 0],
    'SC' => ['R', 'open zetel', 1, 'likely_r', 0],
    'SD' => ['R', 'nog onbekend', 0, 'safe_r', 0],
    'TN' => ['R', 'open zetel', 1, 'safe_r', 0],
    'TX' => ['R', 'Greg Abbott', 0, 'likely_r', 0],
    'VT' => ['R', 'Phil Scott', 0, 'safe_r', 0],
    'WI' => ['D', 'Tony Evers', 0, 'lean_d', 1],
    'WY' => ['R', 'Mark Gordon', 0, 'safe_r', 0],
];
$govCompSummary = [
    'AZ' => 'Arizona blijft een swing state; de gouverneursrace wordt op het scherp van de snede verwacht.',
    'GA' => 'Door termijnlimieten ontstaat een open race in een staat die de afgelopen jaren razend dicht stemde.',
    'KS' => 'Kansas kiest een nieuwe gouverneur in een Republikeins-leunende staat; de race geldt als open.',
    'ME' => 'Maine kiest een opvolger; de race neigt licht Democratisch maar blijft competitief.',
    'MI' => 'Michigan is een open gouverneursrace en een belangrijke graadmeter voor het Midwesten.',
    'NV' => 'Nevada is een notoire swing state waar de gouverneursrace traditioneel nipt wordt beslist.',
    'NH' => 'New Hampshire neigt licht Republikeins maar staat bekend om wisselende uitslagen.',
    'FL' => 'Florida kiest een opvolger; de staat is Republikeins geworden maar de open race trekt aandacht.',
    'OH' => 'Ohio kiest een nieuwe gouverneur; de staat is de afgelopen jaren naar rechts opgeschoven.',
    'WI' => 'Wisconsin is een van de dichtst verdeelde staten van het land.',
];
$govRaces = [];
foreach ($govs as $code => $g) {
    [$party, $inc, $open, $rating, $comp] = $g;
    $race = buildRace('governor', $code, $stateNames[$code], null,
        ['name' => $inc, 'party' => $party], [$rating, $comp, $open], $govCompSummary[$code] ?? null);
    $govRaces[] = $race;
}

// --- Schrijf de gegenereerde races ---
$out2 = __DIR__ . '/../includes/data/midterms_races_generated.php';
$php = "<?php\n/**\n * GEGENEREERD door scripts/midterms_generate_baseline.php\n"
    . " * Senaat (35) en Gouverneurs (36) voor 2026. Zittende namen uit\n"
    . " * unitedstates/congress-legislators; ratings zijn een baseline.\n */\nreturn "
    . var_export(['senate' => $senateRaces, 'governor' => $govRaces], true) . ";\n";
file_put_contents($out2, $php);

echo "Baseline: " . count($baseline) . " districten (D=" . ($counts['D'] ?? 0) . " R=" . ($counts['R'] ?? 0) . " I=" . ($counts['I'] ?? 0) . ") -> " . basename($out1) . "\n";
echo "Races: Senaat " . count($senateRaces) . ", Gouverneurs " . count($govRaces) . " -> " . basename($out2) . "\n";

/**
 * Bouw een race-record dat overeenkomt met het seed-schema.
 */
function buildRace(string $chamber, string $code, string $name, ?string $district, array $inc, array $meta, ?string $summary): array
{
    [$rating, $comp, $open] = $meta;
    return [
        'chamber' => $chamber,
        'state_code' => $code,
        'state_name' => $name,
        'district' => $district,
        'incumbent_name' => $inc['name'],
        'incumbent_party' => $inc['party'],
        'is_open' => (int) $open,
        'rating' => $rating,
        'is_competitive' => (int) $comp,
        'candidate_d' => null,
        'candidate_r' => null,
        'summary_nl' => $summary,
        'source_url' => $chamber === 'senate'
            ? 'https://www.270towin.com/2026-senate-election/'
            : 'https://www.270towin.com/2026-gubernatorial-election/',
    ];
}
