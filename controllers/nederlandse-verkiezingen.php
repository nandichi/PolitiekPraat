<?php
require_once BASE_PATH . '/includes/Database.php';

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
            ORDER BY nmp.periode_start ASC
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
                   JSON_UNQUOTE(JSON_EXTRACT(nv.nieuwe_partijen, '$')) as nieuwe_partijen_raw
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
        ORDER BY nv.jaar ASC
    ";
    
    $verkiezingenStmt = $pdo->prepare($verkiezingenPerPeriodeQuery);
    $verkiezingenStmt->execute();
    $verkiezingenData = $verkiezingenStmt->fetchAll(PDO::FETCH_OBJ);
    
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