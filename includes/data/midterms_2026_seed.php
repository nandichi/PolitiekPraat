<?php
/**
 * Seed- en fallbackdata voor de Midterms 2026 sectie.
 *
 * Dit bestand is de enige bron van waarheid voor de v1-content. Het wordt
 * gebruikt door:
 *   1. scripts/seed_midterms.php  -> vult de database
 *   2. MidtermsModel              -> fallback als de DB leeg/onbereikbaar is
 *
 * Ratings: safe_d, likely_d, lean_d, tossup, lean_r, likely_r, safe_r
 * Partij-codes: D (Democraat), R (Republikein), I (onafhankelijk)
 *
 * NB: cijfers/odds worden live ververst via de Polymarket-cron; de waarden
 * hier zijn een momentopname (eind mei 2026) als terugval.
 *
 * De Senaat- en Gouverneursraces en de Huis-baseline (alle 435) worden
 * gegenereerd door scripts/midterms_generate_baseline.php en hier ingeladen.
 */

$mt_generated_races = (static function (): array {
    $f = __DIR__ . '/midterms_races_generated.php';
    $data = is_readable($f) ? require $f : [];
    return is_array($data) ? $data : [];
})();

$mt_house_baseline = (static function (): array {
    $f = __DIR__ . '/midterms_house_baseline.php';
    $data = is_readable($f) ? require $f : [];
    return is_array($data) ? $data : [];
})();

// Geselecteerde competitieve Huis-districten (highlights bovenop de baseline).
$mt_house_competitive = [
    [
        'chamber' => 'house', 'state_code' => 'CA', 'state_name' => 'Californië', 'district' => '13',
        'incumbent_name' => 'nog onbekend', 'incumbent_party' => 'D', 'is_open' => 0,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'nog onbekend', 'candidate_r' => 'nog onbekend',
        'summary_nl' => 'Een van de dichtst bevochten districten in de Central Valley van Californië.',
        'source_url' => 'https://www.270towin.com/2026-house-election/',
    ],
    [
        'chamber' => 'house', 'state_code' => 'NY', 'state_name' => 'New York', 'district' => '17',
        'incumbent_name' => 'nog onbekend', 'incumbent_party' => 'R', 'is_open' => 0,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'nog onbekend', 'candidate_r' => 'nog onbekend',
        'summary_nl' => 'Een voorstedelijk district ten noorden van New York City dat heen en weer pendelt tussen beide partijen.',
        'source_url' => 'https://www.270towin.com/2026-house-election/',
    ],
    [
        'chamber' => 'house', 'state_code' => 'NE', 'state_name' => 'Nebraska', 'district' => '2',
        'incumbent_name' => 'Don Bacon', 'incumbent_party' => 'R', 'is_open' => 0,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'nog onbekend', 'candidate_r' => 'nog onbekend',
        'summary_nl' => 'Het district rond Omaha staat bekend als een van de weinige districten die regelmatig van kleur wisselt.',
        'source_url' => 'https://www.270towin.com/2026-house-election/',
    ],
    [
        'chamber' => 'house', 'state_code' => 'ME', 'state_name' => 'Maine', 'district' => '2',
        'incumbent_name' => 'Jared Golden', 'incumbent_party' => 'D', 'is_open' => 0,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'nog onbekend', 'candidate_r' => 'nog onbekend',
        'summary_nl' => 'Het noordelijke district van Maine is een klassiek split-ticket district.',
        'source_url' => 'https://www.270towin.com/2026-house-election/',
    ],
];

return [

    // ----------------------------------------------------------------
    // Races: Senaat (35) + Gouverneurs (36) uit de generator, plus
    // geselecteerde competitieve Huis-districten.
    // ----------------------------------------------------------------
    'races' => array_merge(
        $mt_generated_races['senate'] ?? [],
        $mt_generated_races['governor'] ?? [],
        $mt_house_competitive
    ),

    // ----------------------------------------------------------------
    // Huis-baseline: huidige partij per district (alle 435).
    // Gegenereerd uit unitedstates/congress-legislators.
    // Formaat: "STAAT-DISTRICT" => "D"|"R"|"I"   (at-large => "AL")
    // ----------------------------------------------------------------
    'house_baseline' => $mt_house_baseline,

    // ----------------------------------------------------------------
    // Tijdlijn (voorverkiezingen + sleutelmomenten)
    // ----------------------------------------------------------------
    'timeline' => [
        [
            'event_date' => '2025-01-20', 'category' => 'event',
            'title_nl' => 'Aantreden Trump en Vance',
            'description_nl' => 'President Trump en vicepresident Vance treden aan. Daarmee begint feitelijk de aanloop naar de tussentijdse verkiezingen van 2026, die vaak als tussentijds rapportcijfer voor de zittende president gelden.',
            'source_url' => null,
        ],
        [
            'event_date' => '2025-01-20', 'category' => 'event',
            'title_nl' => 'Open Senaatszetels in Ohio en Florida',
            'description_nl' => 'Doordat J.D. Vance vicepresident werd en Marco Rubio een ministerspost kreeg, kwamen hun Senaatszetels vrij. Er werden tijdelijke opvolgers benoemd; in 2026 volgen speciale verkiezingen om de zetels definitief in te vullen.',
            'source_url' => null,
        ],
        [
            'event_date' => '2025-11-04', 'category' => 'event',
            'title_nl' => 'Off-year verkiezingen 2025: Democraten winnen Virginia en New Jersey',
            'description_nl' => 'Bij de verkiezingen van november 2025 wonnen de Democraten de gouverneursraces in Virginia (Abigail Spanberger) en New Jersey (Mikie Sherrill). Analisten lazen die uitslagen als een vroege graadmeter en een gunstig signaal voor de Democraten richting de midterms.',
            'source_url' => null,
        ],
        [
            'event_date' => '2026-02-15', 'category' => 'event',
            'title_nl' => 'Herindeling van kiesdistricten valt grotendeels in Republikeins voordeel uit',
            'description_nl' => 'In de aanloop naar 2026 hertekenden meerdere staten hun congresdistricten. Per saldo verbeterde die herindeling vooral de uitgangspositie van de Republikeinen voor het Huis van Afgevaardigden; volgens tellingen ging het om een handvol tot ruim tien zetels.',
            'source_url' => null,
        ],
        [
            'event_date' => '2026-03-03', 'category' => 'primary',
            'title_nl' => 'Start van het voorverkiezingsseizoen',
            'description_nl' => 'In het voorjaar van 2026 kozen de eerste staten hun kandidaten voor november. Daarmee begon het voorverkiezingsseizoen dat het deelnemersveld voor de midterms bepaalt.',
            'source_url' => null,
        ],
        [
            'event_date' => '2026-06-02', 'category' => 'primary',
            'title_nl' => 'Grote voorverkiezingsdag in onder meer Californië en Iowa',
            'description_nl' => 'Begin juni 2026 hielden meerdere staten, waaronder Californië en Iowa, hun voorverkiezingen. Zo kreeg het veld voor november verder vorm; in Iowa verschoof de Senaatsrace daardoor richting de Republikeinen.',
            'source_url' => null,
        ],
        [
            'event_date' => '2026-11-03', 'category' => 'deadline',
            'title_nl' => 'Election Day',
            'description_nl' => 'Op 3 november 2026 stemmen Amerikanen over het volledige Huis van Afgevaardigden, 35 Senaatszetels en 36 gouverneursposten, naast talloze referenda en lokale verkiezingen.',
            'source_url' => null,
        ],
    ],

    // ----------------------------------------------------------------
    // Referenda / ballot measures (uit o.a. Polymarket)
    // ----------------------------------------------------------------
    'referenda' => [
        [
            'state_code' => 'CA', 'state_name' => 'Californië', 'measure_code' => null,
            'title_nl' => 'Eenmalige vermogensbelasting voor miljardairs in Californië',
            'theme' => 'belastingen',
            'description_nl' => 'Californië stemt over een eenmalige vermogensbelasting voor miljardairs. Voorspellingsmarkten gaven de invoering eind mei een kleine kans.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Belasting wordt ingevoerd',
            'source_url' => 'https://polymarket.com/predictions/midterms',
        ],
        [
            'state_code' => 'MI', 'state_name' => 'Michigan', 'measure_code' => null,
            'title_nl' => 'Herschrijven van de grondwet van Michigan',
            'theme' => 'staatsinrichting',
            'description_nl' => 'Een voorstel om de grondwet van de staat Michigan te herzien.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Grondwet wordt herzien',
            'source_url' => 'https://polymarket.com/predictions/midterms',
        ],
        [
            'state_code' => 'MO', 'state_name' => 'Missouri', 'measure_code' => null,
            'title_nl' => 'Verbod op abortus en geslachtsbehandelingen voor minderjarigen in Missouri',
            'theme' => 'abortus',
            'description_nl' => 'Missouri stemt over een verbod gericht op abortus en medische geslachtsbehandelingen voor minderjarigen.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Verbod wordt aangenomen',
            'source_url' => 'https://polymarket.com/predictions/midterms',
        ],
        [
            'state_code' => 'NV', 'state_name' => 'Nevada', 'measure_code' => null,
            'title_nl' => 'Bescherming van abortusrechten in de grondwet van Nevada',
            'theme' => 'abortus',
            'description_nl' => 'Nevada stemt over het vastleggen van abortusbescherming in de staatsgrondwet.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Bescherming wordt vastgelegd',
            'source_url' => 'https://polymarket.com/predictions/midterms',
        ],
    ],

    // ----------------------------------------------------------------
    // Odds (Polymarket momentopname eind mei 2026). Wordt live ververst.
    // outcomes: lijst van {label_nl, price (0..1)}
    // ----------------------------------------------------------------
    'odds' => [
        'senate_control' => [
            'title_nl' => 'Wie controleert de Senaat na 2026?',
            'category' => 'control',
            'outcomes' => [
                ['label_nl' => 'Republikeinen', 'price' => 0.535],
                ['label_nl' => 'Democraten', 'price' => 0.465],
            ],
            'source_url' => 'https://polymarket.com/event/which-party-will-win-the-senate-in-2026',
        ],
        'house_control' => [
            'title_nl' => 'Wie controleert het Huis na 2026?',
            'category' => 'control',
            'outcomes' => [
                ['label_nl' => 'Democraten', 'price' => 0.81],
                ['label_nl' => 'Republikeinen', 'price' => 0.19],
            ],
            'source_url' => 'https://polymarket.com/event/which-party-will-win-the-house-in-2026',
        ],
        'rep_governors' => [
            'title_nl' => 'Hoeveel gouverneurs hebben de Republikeinen na 2026?',
            'category' => 'seats',
            'outcomes' => [],
            'source_url' => 'https://polymarket.com/event/how-many-republican-governors-after-the-2026-midterm-elections',
        ],
    ],

    // ----------------------------------------------------------------
    // Nieuws (fallback; live via Brave-cron)
    // ----------------------------------------------------------------
    'news' => [],

    // ----------------------------------------------------------------
    // Redactionele content (uitleg + hub-intro)
    // ----------------------------------------------------------------
    'content' => [
        'hub_intro' => 'Op 3 november 2026 gaan de Verenigde Staten naar de stembus voor de midterms: de tussentijdse verkiezingen halverwege de ambtstermijn van de president. Op deze pagina volg je de belangrijkste races, live voorspellingen en uitleg in het Nederlands.',
        'uitleg' => [
            [
                'titel' => 'Wat zijn de midterms?',
                'tekst' => 'De midterms zijn de verkiezingen die precies halverwege de vierjarige ambtstermijn van de Amerikaanse president plaatsvinden. Ze gelden vaak als een tussentijds rapportcijfer voor de zittende president en zijn partij.',
            ],
            [
                'titel' => 'Wat staat er op het spel?',
                'tekst' => 'Kiezers stemmen over het volledige Huis van Afgevaardigden (435 zetels), ongeveer een derde van de Senaat (in 2026 gaat het om 35 zetels) en een groot deel van de gouverneursposten (36 staten). Daarnaast zijn er talloze referenda en lokale verkiezingen.',
            ],
            [
                'titel' => 'Hoe werkt het Huis van Afgevaardigden?',
                'tekst' => 'Het Huis heeft 435 zetels, verdeeld over kiesdistricten. Elke twee jaar wordt het volledige Huis opnieuw gekozen. De partij met de meerderheid bepaalt de agenda en de voorzitter (de Speaker).',
            ],
            [
                'titel' => 'Hoe werkt de Senaat?',
                'tekst' => 'De Senaat telt 100 leden, twee per staat, die zes jaar zitten. Elke twee jaar staat ongeveer een derde van de zetels op de stembus. In 2026 gaat het om 35 zetels.',
            ],
            [
                'titel' => 'Wat zijn voorspellingsmarkten?',
                'tekst' => 'Op platforms als Polymarket kunnen mensen geld inzetten op de uitkomst van verkiezingen. De prijzen worden vaak gelezen als een kansinschatting. Het zijn geen peilingen en geen garanties, maar een momentopname van wat handelaren denken.',
            ],
        ],
        'disclaimer' => 'De voorspellingen en odds op deze pagina komen onder andere van voorspellingsmarkt Polymarket en van onafhankelijke prognosebureaus. Het zijn inschattingen, geen zekerheden en zeker geen stemadvies. Cijfers kunnen elk moment veranderen.',
    ],
];
