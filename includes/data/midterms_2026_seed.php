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
 * hier zijn een momentopname (19 augustus 2026) als terugval.
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

// Geselecteerde Huis-districten. Kandidaten en status zijn gecontroleerd na
// de voorverkiezingen; de nationale ratingverdeling staat in de contextdata.
$mt_house_competitive = [
    [
        'chamber' => 'house', 'state_code' => 'CA', 'state_name' => 'Californië', 'district' => '13',
        'incumbent_name' => 'Adam Gray', 'incumbent_party' => 'D', 'is_open' => 0,
        'rating' => 'lean_d', 'is_competitive' => 1,
        'candidate_d' => 'Adam Gray', 'candidate_r' => 'Kevin Lincoln',
        'summary_nl' => 'Adam Gray won dit district in 2024 met minder dan tweehonderd stemmen. De nieuwe kaart is gunstiger voor Democraten, maar de Central Valley blijft competitief.',
        'source_url' => 'https://ballotpedia.org/California%27s_13th_Congressional_District_election,_2026',
    ],
    [
        'chamber' => 'house', 'state_code' => 'NY', 'state_name' => 'New York', 'district' => '17',
        'incumbent_name' => 'Mike Lawler', 'incumbent_party' => 'R', 'is_open' => 0,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'Cait Conley', 'candidate_r' => 'Mike Lawler',
        'summary_nl' => 'Legerveteraan Cait Conley neemt het op tegen de Republikeinse zittende Mike Lawler in een gelijk verdeeld district in de Hudson Valley.',
        'source_url' => 'https://ballotpedia.org/New_York%27s_17th_Congressional_District_election,_2026',
    ],
    [
        'chamber' => 'house', 'state_code' => 'NE', 'state_name' => 'Nebraska', 'district' => '2',
        'incumbent_name' => 'Don Bacon', 'incumbent_party' => 'R', 'is_open' => 1,
        'rating' => 'tossup', 'is_competitive' => 1,
        'candidate_d' => 'Denise Powell', 'candidate_r' => 'Brinker Harding',
        'summary_nl' => 'Door het vertrek van Don Bacon is het district rond Omaha open. Het gebied stemt op presidentsniveau licht Democratischer dan het land als geheel.',
        'source_url' => 'https://ballotpedia.org/Nebraska%27s_2nd_Congressional_District_election,_2026',
    ],
    [
        'chamber' => 'house', 'state_code' => 'ME', 'state_name' => 'Maine', 'district' => '2',
        'incumbent_name' => 'Jared Golden', 'incumbent_party' => 'D', 'is_open' => 1,
        'rating' => 'likely_r', 'is_competitive' => 1,
        'candidate_d' => 'Matt Dunlap', 'candidate_r' => 'Paul LePage',
        'summary_nl' => 'Jared Golden stelt zich niet herkiesbaar. Voormalig staatssecretaris Matt Dunlap neemt het op tegen oud-gouverneur Paul LePage in een district dat nationaal naar rechts opschoof.',
        'source_url' => 'https://ballotpedia.org/Maine%27s_2nd_Congressional_District_election,_2026',
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
            'title_nl' => 'Start van Trumps tweede termijn',
            'description_nl' => 'Donald Trump treedt aan als 47e president en JD Vance als vicepresident. De verkiezingen van 2026 worden het eerste landelijke oordeel halverwege deze tweede, niet-opeenvolgende termijn.',
            'source_url' => 'https://www.whitehouse.gov/administration/donald-j-trump/',
        ],
        [
            'event_date' => '2025-01-20', 'category' => 'event',
            'title_nl' => 'Speciale Senaatsverkiezingen in Ohio en Florida nodig',
            'description_nl' => 'Door het vertrek van JD Vance en Marco Rubio worden in 2026 naast de 33 reguliere Class II-races ook speciale Senaatsverkiezingen gehouden in Ohio en Florida.',
            'source_url' => 'https://ballotpedia.org/United_States_Senate_elections,_2026',
        ],
        [
            'event_date' => '2026-03-03', 'category' => 'primary',
            'title_nl' => 'Eerste grote voorverkiezingsdag',
            'description_nl' => 'Het voorverkiezingsseizoen begint in meerdere staten. Partijen kiezen hun kandidaten voor het Congres, gouverneurschappen en lokale functies.',
            'source_url' => 'https://www.fec.gov/introduction-campaign-finance/election-results-and-voting-information/',
        ],
        [
            'event_date' => '2026-06-02', 'category' => 'primary',
            'title_nl' => 'Voorverkiezingen in onder meer Californië en Iowa',
            'description_nl' => 'Een grote groep staten bepaalt het kandidatenveld. In Californië plaatsen de twee kandidaten met de meeste stemmen zich voor november, ongeacht partij.',
            'source_url' => 'https://www.fec.gov/introduction-campaign-finance/election-results-and-voting-information/',
        ],
        [
            'event_date' => '2026-06-23', 'category' => 'primary',
            'title_nl' => 'New York kiest kandidaten voor het Huis',
            'description_nl' => 'De voorverkiezingen in New York leggen onder meer de tweestrijd vast in het competitieve 17e district: Cait Conley tegen zittend afgevaardigde Mike Lawler.',
            'source_url' => 'https://ballotpedia.org/New_York%27s_17th_Congressional_District_election,_2026',
        ],
        [
            'event_date' => '2026-08-18', 'category' => 'primary',
            'title_nl' => 'Laatste grote primary night: Alaska, Florida en Wyoming',
            'description_nl' => 'Alaska, Florida en Wyoming stemmen. Vooral de Senaatsrace in Alaska krijgt landelijke aandacht, terwijl Florida voor het eerst met nieuwe congresgrenzen stemt.',
            'source_url' => 'https://abcnews.com/Politics/primaries-3-states-tuesday-narrow-field-key-senate/story?id=135721707',
        ],
        [
            'event_date' => '2026-11-03', 'category' => 'deadline',
            'title_nl' => 'Election Day',
            'description_nl' => 'Amerikanen kiezen alle 435 leden van het Huis, beslissen over 35 Senaatsraces en kiezen in 36 staten een gouverneur. De winnaars voor het Congres treden op 3 januari 2027 aan.',
            'source_url' => 'https://www.fec.gov/introduction-campaign-finance/election-results-and-voting-information/',
        ],
    ],

    // ----------------------------------------------------------------
    // Referenda / ballot measures (uit o.a. Polymarket)
    // ----------------------------------------------------------------
    'referenda' => [
        [
            'state_code' => 'CA', 'state_name' => 'Californië', 'measure_code' => 'Proposition 1',
            'title_nl' => 'Woning- en veteranenfonds van 11,25 miljard dollar',
            'theme' => 'wonen',
            'description_nl' => 'Proposition 1 vraagt toestemming voor staatsobligaties ter financiering van betaalbare woningen, woonprogramma’s en leningen voor veteranen.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Obligatielening wordt goedgekeurd',
            'election_date' => '2026-11-03',
            'source_url' => 'https://ballotpedia.org/California_Proposition_1,_Veteran_and_Housing_Assistance_Programs_Bond_Measure_(2026)',
        ],
        [
            'state_code' => 'MI', 'state_name' => 'Michigan', 'measure_code' => 'CCQ',
            'title_nl' => 'Moet Michigan een grondwettelijke conventie houden?',
            'theme' => 'staatsinrichting',
            'description_nl' => 'Kiezers beantwoorden de periodieke grondwettelijke vraag of de staat een conventie moet bijeenroepen om de grondwet te herzien.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Conventie wordt gehouden',
            'election_date' => '2026-11-03',
            'source_url' => 'https://ballotpedia.org/Michigan_Constitutional_Convention_Question_(2026)',
        ],
        [
            'state_code' => 'MO', 'state_name' => 'Missouri', 'measure_code' => 'Amendment 3',
            'title_nl' => 'Verbod op abortus en gendertransitieprocedures voor minderjarigen',
            'theme' => 'zorg',
            'description_nl' => 'Amendment 3 zou abortus in de meeste gevallen verbieden en medische gendertransitieprocedures voor mensen onder de achttien verbieden.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Amendement wordt aangenomen',
            'election_date' => '2026-11-03',
            'source_url' => 'https://ballotpedia.org/Missouri_Amendment_3,_Prohibit_Abortion_and_Gender_Transition_Procedures_for_Minors_Amendment_(2026)',
        ],
        [
            'state_code' => 'NV', 'state_name' => 'Nevada', 'measure_code' => 'Question 6',
            'title_nl' => 'Grondwettelijk recht op abortus vóór levensvatbaarheid',
            'theme' => 'abortus',
            'description_nl' => 'Question 6 zou vóór foetale levensvatbaarheid een recht op abortus vastleggen in de grondwet van Nevada.',
            'polymarket_slug' => null,
            'yes_label_nl' => 'Recht wordt vastgelegd',
            'election_date' => '2026-11-03',
            'source_url' => 'https://ballotpedia.org/Nevada_Question_6,_Right_to_Abortion_Initiative_(2026)',
        ],
    ],

    // ----------------------------------------------------------------
    // Odds (Polymarket momentopname 19 augustus 2026). Wordt live ververst.
    // outcomes: lijst van {label_nl, price (0..1)}
    // ----------------------------------------------------------------
    'odds' => [
        'senate_control' => [
            'title_nl' => 'Wie controleert de Senaat na 2026?',
            'category' => 'control',
            'outcomes' => [
                ['label_nl' => 'Democraten', 'price' => 0.505],
                ['label_nl' => 'Republikeinen', 'price' => 0.495],
            ],
            'source_url' => 'https://polymarket.com/event/which-party-will-win-the-senate-in-2026',
            'fetched_at' => '2026-08-19 19:00:00',
        ],
        'house_control' => [
            'title_nl' => 'Wie controleert het Huis na 2026?',
            'category' => 'control',
            'outcomes' => [
                ['label_nl' => 'Democraten', 'price' => 0.875],
                ['label_nl' => 'Republikeinen', 'price' => 0.125],
            ],
            'source_url' => 'https://polymarket.com/event/which-party-will-win-the-house-in-2026',
            'fetched_at' => '2026-08-19 19:00:00',
        ],
        'rep_governors' => [
            'title_nl' => 'Hoeveel gouverneurs hebben de Republikeinen na 2026?',
            'category' => 'seats',
            'outcomes' => [],
            'source_url' => 'https://polymarket.com/event/how-many-republican-governors-after-the-2026-midterm-elections',
            'fetched_at' => '2026-08-19 19:00:00',
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
