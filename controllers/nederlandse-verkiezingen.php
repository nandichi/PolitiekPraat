<?php
require_once BASE_PATH . '/includes/Database.php';

$fallback_partij_uitslagen_2025 = [
    ['partij' => 'D66', 'zetels' => 26, 'stemmen' => 1790634, 'percentage' => 16.94],
    ['partij' => 'PVV', 'zetels' => 26, 'stemmen' => 1760966, 'percentage' => 16.66],
    ['partij' => 'VVD', 'zetels' => 22, 'stemmen' => 1505829, 'percentage' => 14.24],
    ['partij' => 'GL-PvdA', 'zetels' => 20, 'stemmen' => 1352163, 'percentage' => 12.79],
    ['partij' => 'CDA', 'zetels' => 18, 'stemmen' => 1246874, 'percentage' => 11.79],
    ['partij' => 'JA21', 'zetels' => 9, 'stemmen' => 628517, 'percentage' => 5.95],
    ['partij' => 'Forum voor Democratie', 'zetels' => 7, 'stemmen' => 480393, 'percentage' => 4.54],
    ['partij' => 'BBB', 'zetels' => 4, 'stemmen' => 279916, 'percentage' => 2.65],
    ['partij' => 'DENK', 'zetels' => 3, 'stemmen' => 250368, 'percentage' => 2.37],
    ['partij' => 'ChristenUnie', 'zetels' => 3, 'stemmen' => 201361, 'percentage' => 1.90],
    ['partij' => 'SP', 'zetels' => 3, 'stemmen' => 199585, 'percentage' => 1.89],
    ['partij' => 'SGP', 'zetels' => 3, 'stemmen' => 238093, 'percentage' => 2.25],
    ['partij' => 'Partij voor de Dieren', 'zetels' => 3, 'stemmen' => 219371, 'percentage' => 2.08],
    ['partij' => '50PLUS', 'zetels' => 2, 'stemmen' => 151053, 'percentage' => 1.43],
    ['partij' => 'Volt', 'zetels' => 1, 'stemmen' => 116468, 'percentage' => 1.10],
];

$fallback_latest_verkiezing_2025 = [
    'id' => null,
    'jaar' => 2025,
    'partij_uitslagen' => json_encode($fallback_partij_uitslagen_2025, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    'coalitie_partijen' => json_encode(['D66', 'VVD', 'CDA'], JSON_UNESCAPED_UNICODE),
    'coalitie_zetels' => 66,
    'coalitie_type' => 'minderheid',
    'oppositie_partijen' => json_encode([
        'PVV',
        'GL-PvdA',
        'JA21',
        'Forum voor Democratie',
        'BBB',
        'DENK',
        'ChristenUnie',
        'SP',
        'SGP',
        'Partij voor de Dieren',
        '50PLUS',
        'Volt'
    ], JSON_UNESCAPED_UNICODE),
    'minister_president' => 'Rob Jetten',
    'minister_president_partij' => 'D66',
    'kabinet_naam' => 'Kabinet-Jetten',
    'kabinet_type' => 'centraal',
    'totaal_zetels' => 150,
    'totaal_stemmen' => 10640324,
    'opkomst_percentage' => 78.30,
    'kiesdrempel_percentage' => 0.67,
    'verkiezingsdata' => '2025-10-29',
    'kabinet_start_datum' => '2026-02-23',
    'formate_duur_dagen' => 93,
    'verkiezings_aanleiding' => 'vervroegd',
    'belangrijke_gebeurtenissen' => 'Vervroegde verkiezingen na de val van kabinet-Schoof (3 juni 2025); Kiesraad stelde de uitslag op 7 november 2025 vast.',
    'opvallende_feiten' => 'D66 en PVV delen de meeste zetels (26), waardoor D66 een minderheidskabinet met VVD en CDA (Kabinet-Jetten) vormde.',
    'nieuwe_partijen' => json_encode(['50PLUS'], JSON_UNESCAPED_UNICODE),
    'verdwenen_partijen' => json_encode(['Nieuw Sociaal Contract (NSC)'], JSON_UNESCAPED_UNICODE),
    'grootste_winnaar' => 'D66',
    'grootste_winnaar_aantal' => 17,
    'grootste_verliezer' => 'Nieuw Sociaal Contract (NSC)',
    'grootste_verliezer_aantal' => 20,
    'aantal_partijen_tk' => 15,
    'kiesdrempel_gehaald' => json_encode([
        'D66',
        'PVV',
        'VVD',
        'GL-PvdA',
        'CDA',
        'JA21',
        'Forum voor Democratie',
        'BBB',
        'DENK',
        'ChristenUnie',
        'SP',
        'SGP',
        'Partij voor de Dieren',
        '50PLUS',
        'Volt'
    ], JSON_UNESCAPED_UNICODE),
    'kiesdrempel_gemist' => json_encode([
        'Nieuw Sociaal Contract (NSC)',
        'Belang Van Nederland (BVNL)',
        'Vrede voor Dieren',
        'BIJ1',
        'LP (Libertaire Partij)',
        'Piratenpartij',
        'FNP',
        'Vrij Verbond',
        'DE LINIE',
        'NL PLAN',
        'ELLECT',
        'Partij voor de Rechtsstaat'
    ], JSON_UNESCAPED_UNICODE),
    'lijsttrekkers' => json_encode([
        ['partij' => 'D66', 'lijsttrekker' => 'Rob Jetten'],
        ['partij' => 'PVV', 'lijsttrekker' => 'Geert Wilders'],
        ['partij' => 'VVD', 'lijsttrekker' => 'Dilan Yeşilgöz'],
        ['partij' => 'GL-PvdA', 'lijsttrekker' => 'Frans Timmermans'],
        ['partij' => 'CDA', 'lijsttrekker' => 'Henri Bontenbal'],
        ['partij' => 'JA21', 'lijsttrekker' => 'Joost Eerdmans'],
        ['partij' => 'Forum voor Democratie', 'lijsttrekker' => 'Lidewij de Vos'],
        ['partij' => 'BBB', 'lijsttrekker' => 'Caroline van der Plas'],
        ['partij' => 'DENK', 'lijsttrekker' => 'Stephan van Baarle'],
        ['partij' => 'ChristenUnie', 'lijsttrekker' => 'Mirjam Bikker'],
        ['partij' => 'SP', 'lijsttrekker' => 'Jimmy Dijk'],
        ['partij' => 'SGP', 'lijsttrekker' => 'Chris Stoffer'],
        ['partij' => 'Partij voor de Dieren', 'lijsttrekker' => 'Esther Ouwehand'],
        ['partij' => '50PLUS', 'lijsttrekker' => 'Jan Struijs'],
        ['partij' => 'Volt', 'lijsttrekker' => 'Laurens Dassen']
    ], JSON_UNESCAPED_UNICODE),
    'tv_debatten' => json_encode([]),
    'verkiezingsuitslag_tijd' => null,
    'opkomst_verschil_vorige' => 0.55,
    'belangrijkste_themas' => json_encode([
        'Versneld verhogen van de AOW-leeftijd',
        'Versoberen van WW en WIA',
        'Krimp van het ambtenarenapparaat',
        'Hypotheekrenteaftrek blijft ongewijzigd',
        'Stikstofuitstoot landbouw 23-25% minder tegen 2030',
        'Defensiebudget richting 3,5% bbp',
        'Zorgkosten beperken met preventie, passende zorg en een hoger eigen risico',
        'Lelystad Airport open voor vakantievluchten'
    ], JSON_UNESCAPED_UNICODE),
    'beschrijving' => 'Vervroegde Tweede Kamerverkiezingen (29 oktober 2025) leverden D66 met Rob Jetten als grootste partij; Jetten leidde daarna het minderheidskabinet van D66, VVD en CDA.',
    'bronnen' => json_encode([
        'https://nl.wikipedia.org/wiki/Tweede_Kamerverkiezingen_2025',
        'https://www.kiesraad.nl/actueel/nieuws/2025/11/7/kiesraad-uitslag-tweede-kamerverkiezing-betrouwbaar',
        'https://nl.wikipedia.org/wiki/Kabinet-Jetten'
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    'opvallende_feiten' => 'D66 en PVV eindigden gelijk (26 zetels) en het Kabinet-Jetten draait op een minderheidscoalitie van D66, VVD en CDA.',
    'foto_url' => null
];

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Check if we're viewing ministers-presidenten page
    if (isset($_GET['actie']) && $_GET['actie'] === 'ministers-presidenten') {
        // Fetch all ministers-presidenten with their periods and achievements
        $presidentenQuery = "
            SELECT nmp.*, 
                   DATEDIFF(COALESCE(nmp.periode_eind, CURDATE()), nmp.periode_start) as dagen_in_functie,
                   YEAR(nmp.periode_start) as start_jaar,
                   YEAR(COALESCE(nmp.periode_eind, CURDATE())) as eind_jaar
            FROM nederlandse_ministers_presidenten nmp 
            ORDER BY nmp.periode_start DESC
        ";
        
        $presidentenStmt = $pdo->prepare($presidentenQuery);
        $presidentenStmt->execute();
        $allePresidenten = $presidentenStmt->fetchAll(PDO::FETCH_OBJ);
        
        // Group presidents by era and parse JSON data
        $presidentenPerEra = [];
        foreach ($allePresidenten as $president) {
            $startJaar = $president->start_jaar;
            
            // Parse JSON fields
            if (!empty($president->prestaties)) {
                $president->prestaties_parsed = json_decode($president->prestaties);
            }
            if (!empty($president->fun_facts)) {
                $president->fun_facts_parsed = json_decode($president->fun_facts);
            }
            if (!empty($president->kinderen)) {
                $president->kinderen_parsed = json_decode($president->kinderen);
            }
            if (!empty($president->kabinetten)) {
                $president->kabinetten_parsed = json_decode($president->kabinetten);
            }
            if (!empty($president->coalitiepartners)) {
                $president->coalitiepartners_parsed = json_decode($president->coalitiepartners);
            }
            if (!empty($president->wetgeving)) {
                $president->wetgeving_parsed = json_decode($president->wetgeving);
            }
            if (!empty($president->crises)) {
                $president->crises_parsed = json_decode($president->crises);
            }
            if (!empty($president->citaten)) {
                $president->citaten_parsed = json_decode($president->citaten);
            }
            if (!empty($president->ministersposten_voor_mp)) {
                $president->ministersposten_voor_mp_parsed = json_decode($president->ministersposten_voor_mp);
            }
            
            if ($startJaar >= 1848 && $startJaar <= 1917) {
                $era = 'Vroege Democratie (1848-1917)';
            } elseif ($startJaar >= 1918 && $startJaar <= 1945) {
                $era = 'Interbellum & WOII (1918-1945)';
            } elseif ($startJaar >= 1946 && $startJaar <= 1980) {
                $era = 'Wederopbouw & Verzuiling (1946-1980)';
            } elseif ($startJaar >= 1981 && $startJaar <= 2000) {
                $era = 'Ontzuiling & Modernisering (1981-2000)';
            } else {
                $era = 'Digitale Era & Polarisatie (2001-heden)';
            }
            
            $presidentenPerEra[$era][] = $president;
        }
        
        // Sorteer era's van nieuw naar oud
        $eraOrder = [
            'Digitale Era & Polarisatie (2001-heden)',
            'Ontzuiling & Modernisering (1981-2000)',
            'Wederopbouw & Verzuiling (1946-1980)',
            'Interbellum & WOII (1918-1945)',
            'Vroege Democratie (1848-1917)'
        ];
        
        $gesorteerdePresidentenPerEra = [];
        foreach ($eraOrder as $era) {
            if (isset($presidentenPerEra[$era])) {
                $gesorteerdePresidentenPerEra[$era] = $presidentenPerEra[$era];
            }
        }
        $presidentenPerEra = $gesorteerdePresidentenPerEra;
        
        // Fetch statistics about ministers-presidenten
        $presidentenStatistiekenQuery = "
            SELECT 
                COUNT(*) as totaal_presidenten,
                COUNT(DISTINCT partij) as aantal_partijen,
                AVG(DATEDIFF(COALESCE(periode_eind, CURDATE()), periode_start)) as gemiddelde_termijn_dagen,
                MIN(leeftijd_bij_aantreden) as jongste_leeftijd,
                MAX(leeftijd_bij_aantreden) as oudste_leeftijd,
                (SELECT COUNT(*) FROM nederlandse_ministers_presidenten WHERE partij LIKE '%VVD%') as vvd_presidenten,
                (SELECT COUNT(*) FROM nederlandse_ministers_presidenten WHERE partij LIKE '%PvdA%') as pvda_presidenten,
                (SELECT COUNT(*) FROM nederlandse_ministers_presidenten WHERE partij LIKE '%CDA%') as cda_presidenten,
                (SELECT COUNT(*) FROM nederlandse_ministers_presidenten WHERE is_huidig = 1) as huidig_aantal
            FROM nederlandse_ministers_presidenten
        ";
        
        $presidentenStatistiekenStmt = $pdo->prepare($presidentenStatistiekenQuery);
        $presidentenStatistiekenStmt->execute();
        $presidentenStatistieken = $presidentenStatistiekenStmt->fetch(PDO::FETCH_OBJ);
        
        // Include the ministers-presidenten view
        require_once BASE_PATH . '/views/nederlandse-verkiezingen/ministers-presidenten.php';
        return;
    }
    
    // Check if we're viewing a specific year
    if (isset($_GET['jaar'])) {
        $jaar = (int)$_GET['jaar'];
        
        // Fetch specific election details
        $verkiezingQuery = "
            SELECT nv.*, 
                   JSON_UNQUOTE(JSON_EXTRACT(nv.partij_uitslagen, '$')) as partij_uitslagen_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.coalitie_partijen, '$')) as coalitie_partijen_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.oppositie_partijen, '$')) as oppositie_partijen_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.belangrijkste_themas, '$')) as belangrijkste_themas_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.nieuwe_partijen, '$')) as nieuwe_partijen_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.verdwenen_partijen, '$')) as verdwenen_partijen_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.kiesdrempel_gehaald, '$')) as kiesdrempel_gehaald_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.kiesdrempel_gemist, '$')) as kiesdrempel_gemist_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.lijsttrekkers, '$')) as lijsttrekkers_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.tv_debatten, '$')) as tv_debatten_raw,
                   JSON_UNQUOTE(JSON_EXTRACT(nv.bronnen, '$')) as bronnen_raw
            FROM nederlandse_verkiezingen nv 
            WHERE nv.jaar = :jaar
        ";
        
        $verkiezingStmt = $pdo->prepare($verkiezingQuery);
        $verkiezingStmt->bindParam(':jaar', $jaar, PDO::PARAM_INT);
        $verkiezingStmt->execute();
        $verkiezing = $verkiezingStmt->fetch(PDO::FETCH_OBJ);
        
        if (!$verkiezing) {
            header('Location: ' . URLROOT . '/nederlandse-verkiezingen');
            exit();
        }
        
        // Parse JSON data and extract Nederlandse politieke info
        if (!empty($verkiezing->partij_uitslagen_raw)) {
            $verkiezing->partij_uitslagen = json_decode($verkiezing->partij_uitslagen_raw);
            
            // Extract largest and second party for display
            if (count($verkiezing->partij_uitslagen) >= 2) {
                $verkiezing->grootste_partij = $verkiezing->partij_uitslagen[0]->partij ?? null;
                $verkiezing->grootste_partij_zetels = $verkiezing->partij_uitslagen[0]->zetels ?? null;
                $verkiezing->grootste_partij_stemmen = $verkiezing->partij_uitslagen[0]->stemmen ?? null;
                $verkiezing->grootste_partij_percentage = $verkiezing->partij_uitslagen[0]->percentage ?? null;
                
                $verkiezing->tweede_partij = $verkiezing->partij_uitslagen[1]->partij ?? null;
                $verkiezing->tweede_partij_zetels = $verkiezing->partij_uitslagen[1]->zetels ?? null;
                $verkiezing->tweede_partij_stemmen = $verkiezing->partij_uitslagen[1]->stemmen ?? null;
                $verkiezing->tweede_partij_percentage = $verkiezing->partij_uitslagen[1]->percentage ?? null;
            }
        }
        
        if (!empty($verkiezing->coalitie_partijen_raw)) {
            $verkiezing->coalitie_partijen = json_decode($verkiezing->coalitie_partijen_raw);
        }
        if (!empty($verkiezing->oppositie_partijen_raw)) {
            $verkiezing->oppositie_partijen = json_decode($verkiezing->oppositie_partijen_raw);
        }
        if (!empty($verkiezing->belangrijkste_themas_raw)) {
            $verkiezing->belangrijkste_themas = json_decode($verkiezing->belangrijkste_themas_raw);
        }
        if (!empty($verkiezing->nieuwe_partijen_raw)) {
            $verkiezing->nieuwe_partijen = json_decode($verkiezing->nieuwe_partijen_raw);
        }
        if (!empty($verkiezing->verdwenen_partijen_raw)) {
            $verkiezing->verdwenen_partijen = json_decode($verkiezing->verdwenen_partijen_raw);
        }
        if (!empty($verkiezing->kiesdrempel_gehaald_raw)) {
            $verkiezing->kiesdrempel_gehaald = json_decode($verkiezing->kiesdrempel_gehaald_raw);
        }
        if (!empty($verkiezing->kiesdrempel_gemist_raw)) {
            $verkiezing->kiesdrempel_gemist = json_decode($verkiezing->kiesdrempel_gemist_raw);
        }
        if (!empty($verkiezing->lijsttrekkers_raw)) {
            $verkiezing->lijsttrekkers = json_decode($verkiezing->lijsttrekkers_raw);
        }
        if (!empty($verkiezing->tv_debatten_raw)) {
            $verkiezing->tv_debatten = json_decode($verkiezing->tv_debatten_raw);
        }
        if (!empty($verkiezing->bronnen_raw)) {
            $verkiezing->bronnen = json_decode($verkiezing->bronnen_raw);
        }
        
        // Fetch related elections (previous and next)
        $gerelateerdeVerkiezingen = ['vorige' => null, 'volgende' => null];
        
        // Previous election
        $vorigeQuery = "SELECT * FROM nederlandse_verkiezingen WHERE jaar < :jaar ORDER BY jaar DESC LIMIT 1";
        $vorigeStmt = $pdo->prepare($vorigeQuery);
        $vorigeStmt->bindParam(':jaar', $jaar, PDO::PARAM_INT);
        $vorigeStmt->execute();
        $gerelateerdeVerkiezingen['vorige'] = $vorigeStmt->fetch(PDO::FETCH_OBJ);
        
        // Next election
        $volgendeQuery = "SELECT * FROM nederlandse_verkiezingen WHERE jaar > :jaar ORDER BY jaar ASC LIMIT 1";
        $volgendeStmt = $pdo->prepare($volgendeQuery);
        $volgendeStmt->bindParam(':jaar', $jaar, PDO::PARAM_INT);
        $volgendeStmt->execute();
        $gerelateerdeVerkiezingen['volgende'] = $volgendeStmt->fetch(PDO::FETCH_OBJ);
        
        // Include the detail view
        require_once BASE_PATH . '/views/nederlandse-verkiezingen/detail.php';
        return;
    }
    
    // Fetch verkiezingen gegroepeerd per periode
    $verkiezingenPerPeriodeQuery = "
        SELECT 
            nv.*,
            CASE 
                WHEN nv.jaar BETWEEN 1918 AND 1945 THEN 'Interbellum & WOII (1918-1945)'
                WHEN nv.jaar BETWEEN 1946 AND 1980 THEN 'Wederopbouw & Verzuiling (1946-1980)'
                WHEN nv.jaar BETWEEN 1981 AND 2000 THEN 'Ontzuiling & Modernisering (1981-2000)'
                WHEN nv.jaar >= 2001 THEN 'Digitale Era & Polarisatie (2001-heden)'
                ELSE 'Vroege Democratie (1848-1917)'
            END as periode
        FROM nederlandse_verkiezingen nv 
        ORDER BY nv.jaar DESC
    ";
    
    $verkiezingenStmt = $pdo->prepare($verkiezingenPerPeriodeQuery);
    $verkiezingenStmt->execute();
    $verkiezingenData = $verkiezingenStmt->fetchAll(PDO::FETCH_OBJ);

    $hasLatestElection2025 = false;
    foreach ($verkiezingenData as $record) {
        if ((int) $record->jaar === 2025) {
            $hasLatestElection2025 = true;
            break;
        }
    }
    if (!$hasLatestElection2025) {
        array_unshift($verkiezingenData, (object) $fallback_latest_verkiezing_2025);
    }

    // Groepeer verkiezingen per periode en parse JSON data
    $verkiezingenPerPeriode = [];
    foreach ($verkiezingenData as $verkiezing) {
        // Parse JSON data voor partij uitslagen
        if (!empty($verkiezing->partij_uitslagen)) {
            $partijUitslagen = json_decode($verkiezing->partij_uitslagen);
            if ($partijUitslagen && count($partijUitslagen) > 0) {
                $verkiezing->grootste_partij = $partijUitslagen[0]->partij ?? null;
                $verkiezing->grootste_partij_zetels = $partijUitslagen[0]->zetels ?? null;
                $verkiezing->grootste_partij_stemmen = $partijUitslagen[0]->stemmen ?? null;
                $verkiezing->grootste_partij_percentage = $partijUitslagen[0]->percentage ?? null;
                $verkiezing->partij_uitslagen_parsed = $partijUitslagen;
            } else {
                // Zorg ervoor dat de properties bestaan, ook als de JSON leeg is
                $verkiezing->grootste_partij = null;
                $verkiezing->grootste_partij_zetels = null;
                $verkiezing->grootste_partij_stemmen = null;
                $verkiezing->grootste_partij_percentage = null;
                $verkiezing->partij_uitslagen_parsed = [];
            }
        } else {
            // Zorg ervoor dat de properties bestaan, ook als er geen partij_uitslagen zijn
            $verkiezing->grootste_partij = null;
            $verkiezing->grootste_partij_zetels = null;
            $verkiezing->grootste_partij_stemmen = null;
            $verkiezing->grootste_partij_percentage = null;
            $verkiezing->partij_uitslagen_parsed = [];
        }
        
        $verkiezingenPerPeriode[$verkiezing->periode][] = $verkiezing;
    }
    
    // Sorteer era's van nieuw naar oud
    $eraOrder = [
        'Digitale Era & Polarisatie (2001-heden)',
        'Ontzuiling & Modernisering (1981-2000)',
        'Wederopbouw & Verzuiling (1946-1980)',
        'Interbellum & WOII (1918-1945)',
        'Vroege Democratie (1848-1917)'
    ];
    
    $gesorteerdeVerkiezingenPerPeriode = [];
    foreach ($eraOrder as $era) {
        if (isset($verkiezingenPerPeriode[$era])) {
            $gesorteerdeVerkiezingenPerPeriode[$era] = $verkiezingenPerPeriode[$era];
        }
    }
    $verkiezingenPerPeriode = $gesorteerdeVerkiezingenPerPeriode;
    
    // Fetch algemene statistieken
    $statistiekenQuery = "
        SELECT 
            COUNT(*) as totaal_verkiezingen,
            COUNT(DISTINCT minister_president) as totaal_premiers,
            AVG(opkomst_percentage) as gemiddelde_opkomst,
            (SELECT COUNT(*) FROM nederlandse_verkiezingen WHERE minister_president_partij LIKE '%VVD%') as vvd_overwinningen,
            (SELECT COUNT(*) FROM nederlandse_verkiezingen WHERE minister_president_partij LIKE '%PvdA%') as pvda_overwinningen,
            (SELECT COUNT(*) FROM nederlandse_verkiezingen WHERE minister_president_partij LIKE '%CDA%') as cda_overwinningen,
            (SELECT COUNT(*) FROM nederlandse_verkiezingen WHERE minister_president_partij LIKE '%D66%') as d66_overwinningen,
            MAX(opkomst_percentage) as hoogste_opkomst,
            MIN(opkomst_percentage) as laagste_opkomst
        FROM nederlandse_verkiezingen
    ";
    
    $statistiekenStmt = $pdo->prepare($statistiekenQuery);
    $statistiekenStmt->execute();
    $statistieken = $statistiekenStmt->fetch(PDO::FETCH_OBJ);
    
    // Fetch key figures (ministers-presidenten) per periode
    $keyFiguresQuery = "
        SELECT 
            nmp.*,
            CASE 
                WHEN nmp.periode_start BETWEEN '1848-01-01' AND '1917-12-31' THEN 'vroege_democratie'
                WHEN nmp.periode_start BETWEEN '1918-01-01' AND '1945-12-31' THEN 'interbellum_woii'
                WHEN nmp.periode_start BETWEEN '1946-01-01' AND '1980-12-31' THEN 'wederopbouw_verzuiling'
                WHEN nmp.periode_start BETWEEN '1981-01-01' AND '2000-12-31' THEN 'ontzuiling_modernisering'
                WHEN nmp.periode_start >= '2001-01-01' THEN 'digitale_era'
                ELSE 'vroege_democratie'
            END as periode_categorie
        FROM nederlandse_ministers_presidenten nmp 
        WHERE nmp.foto_url IS NOT NULL AND nmp.foto_url != ''
        ORDER BY nmp.periode_start ASC
    ";
    
    $keyFiguresStmt = $pdo->prepare($keyFiguresQuery);
    $keyFiguresStmt->execute();
    $keyFiguresData = $keyFiguresStmt->fetchAll(PDO::FETCH_OBJ);
    
    // Groepeer key figures per periode
    $keyFiguresPresidenten = [];
    foreach ($keyFiguresData as $figure) {
        $keyFiguresPresidenten[$figure->periode_categorie][] = $figure;
    }
    
    // Limiteer tot 3 per periode voor de voorbeeldweergave
    foreach ($keyFiguresPresidenten as $periode => &$figures) {
        $figures = array_slice($figures, 0, 3);
    }
    
    // Fetch recente ministers-presidenten voor gallery preview
    $recentePremiers = "
        SELECT nmp.* 
        FROM nederlandse_ministers_presidenten nmp 
        WHERE nmp.foto_url IS NOT NULL AND nmp.foto_url != ''
        ORDER BY nmp.periode_start DESC 
        LIMIT 6
    ";
    
    $recentePremiersStmt = $pdo->prepare($recentePremiers);
    $recentePremiersStmt->execute();
    $recentePremiersData = $recentePremiersStmt->fetchAll(PDO::FETCH_OBJ);
    
} catch (PDOException $e) {
    error_log("Database error in nederlandse-verkiezingen: " . $e->getMessage());
    
    // Fallback data voor als database niet beschikbaar is
    $verkiezingenPerPeriode = [];
    $statistieken = (object)[
        'totaal_verkiezingen' => 25,
        'totaal_premiers' => 15,
        'gemiddelde_opkomst' => 78.5,
        'vvd_overwinningen' => 8,
        'pvda_overwinningen' => 6,
        'cda_overwinningen' => 5,
        'd66_overwinningen' => 2,
        'hoogste_opkomst' => 95.0,
        'laagste_opkomst' => 68.5,
        'meeste_zetels_ooit' => 54,
        'minste_zetels_winnaar' => 33
    ];
    $keyFiguresPresidenten = [];
    $recentePremiersData = [];
}

// Include the view
require_once BASE_PATH . '/views/nederlandse-verkiezingen/index.php';
?> 
