<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "🇺🇸 Amerikaanse Presidenten Data Fix Script\n";
echo "===========================================\n";
echo "Voegt ontbrekende presidenten toe (Trump #45 en #47)\n\n";

try {
    $db = new Database();
    echo "✅ Database connectie succesvol\n\n";
    
    // Controleer eerst hoeveel presidenten er zijn
    $db->query("SELECT COUNT(*) as count FROM amerikaanse_presidenten");
    $result = $db->single();
    echo "Huidige aantal presidenten in database: " . $result->count . "\n";
    
    // Controleer of Trump er al in staat
    $db->query("SELECT * FROM amerikaanse_presidenten WHERE president_nummer = 45");
    $trump = $db->single();
    
    if ($trump) {
        echo "✅ Donald Trump staat al in de database\n";
        echo "President #45: " . $trump->naam . "\n\n";
    } else {
        echo "❌ Donald Trump ontbreekt - voegen toe...\n\n";
        
        // Voeg Donald Trump toe met UTF-8 safe data
        $trump_data = [
            'president_nummer' => 45,
            'naam' => 'Donald Trump',
            'volledige_naam' => 'Donald John Trump',
            'bijnaam' => 'The Donald',
            'partij' => 'Republican',
            'periode_start' => '2017-01-20',
            'periode_eind' => '2021-01-20',
            'is_huidig' => false,
            'geboren' => '1946-06-14',
            'overleden' => null,
            'geboorteplaats' => 'Queens, New York City, New York',
            'vice_president' => 'Mike Pence',
            'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/56/Donald_Trump_official_portrait.jpg',
            'biografie' => 'Donald Trump was de 45e president van de Verenigde Staten en de eerste president zonder voorafgaande militaire of overheidsdienstervaring. Voor zijn presidentschap was hij een zakenman en tv-persoonlijkheid.',
            'vroeg_leven' => 'Geboren in een welvarende familie in Queens, New York. Studeerde aan Wharton School van de University of Pennsylvania.',
            'politieke_carriere' => 'Geen voorafgaande politieke ervaring voor zijn presidentschap. Won de presidentsverkiezing van 2016.',
            'prestaties' => json_encode([
                'Tax Cuts and Jobs Act van 2017',
                'Deregulering van diverse sectoren',
                'Aanstelling van drie Hooggerechtshofrechters',
                'Abraham Accords in het Midden-Oosten',
                'Operation Warp Speed voor COVID-19 vaccins'
            ]),
            'fun_facts' => json_encode([
                'Eerste president die geboren werd in Queens, New York',
                'Oudste president ooit ingehuldigd (70 jaar)',
                'Had een ster op de Hollywood Walk of Fame',
                'Schreef meerdere boeken waaronder "The Art of the Deal"',
                'Presenteerde reality-TV show "The Apprentice"'
            ]),
            'echtgenote' => 'Melania Trump (3e echtgenote)',
            'kinderen' => json_encode(['Donald Jr.', 'Ivanka', 'Eric', 'Tiffany', 'Barron']),
            'familie_connecties' => 'Geen directe presidentiële familie.',
            'lengte_cm' => 190,
            'gewicht_kg' => 110,
            'verkiezingsjaren' => json_encode([2016]),
            'leeftijd_bij_aantreden' => 70,
            'belangrijkste_gebeurtenissen' => 'COVID-19 pandemie, handeloorlog met China, aanval op het Capitool, twee impeachments',
            'bekende_speeches' => 'Inaugurele speech 2017, State of the Union speeches',
            'wetgeving' => json_encode(['Tax Cuts and Jobs Act', 'First Step Act']),
            'oorlogen' => json_encode(['Ongoing conflicts in Afghanistan, Iraq, Syria']),
            'economische_situatie' => 'Sterke economie tot COVID-19, lage werkloosheid, handelsoorlog met China',
            'carrierre_voor_president' => 'Vastgoedontwikkelaar, zakenman, tv-persoonlijkheid, auteur',
            'carrierre_na_president' => 'Terug naar zakenleven, politieke activiteiten',
            'doodsoorzaak' => null,
            'begrafenisplaats' => null,
            'historische_waardering' => 'Controversieel en gepolariseerd, deelde het land, unieke presidentiële stijl',
            'controverses' => 'Twee impeachments, Capitool bestorming, verschillende rechtszaken, controversiële uitspraken',
            'citaten' => json_encode([
                '"Make America Great Again"',
                '"You\'re fired!"',
                '"The art of the deal"'
            ]),
            'monumenten_ter_ere' => 'Geen officiële monumenten (nog levend)'
        ];
        
        $sql = "INSERT INTO amerikaanse_presidenten (
            president_nummer, naam, volledige_naam, bijnaam, partij, periode_start, periode_eind,
            is_huidig, geboren, overleden, geboorteplaats, vice_president, foto_url, biografie,
            vroeg_leven, politieke_carriere, prestaties, fun_facts, echtgenote, kinderen,
            familie_connecties, lengte_cm, gewicht_kg, verkiezingsjaren, leeftijd_bij_aantreden,
            belangrijkste_gebeurtenissen, bekende_speeches, wetgeving, oorlogen, economische_situatie,
            carrierre_voor_president, carrierre_na_president, doodsoorzaak, begrafenisplaats,
            historische_waardering, controverses, citaten, monumenten_ter_ere
        ) VALUES (
            :president_nummer, :naam, :volledige_naam, :bijnaam, :partij, :periode_start, :periode_eind,
            :is_huidig, :geboren, :overleden, :geboorteplaats, :vice_president, :foto_url, :biografie,
            :vroeg_leven, :politieke_carriere, :prestaties, :fun_facts, :echtgenote, :kinderen,
            :familie_connecties, :lengte_cm, :gewicht_kg, :verkiezingsjaren, :leeftijd_bij_aantreden,
            :belangrijkste_gebeurtenissen, :bekende_speeches, :wetgeving, :oorlogen, :economische_situatie,
            :carrierre_voor_president, :carrierre_na_president, :doodsoorzaak, :begrafenisplaats,
            :historische_waardering, :controverses, :citaten, :monumenten_ter_ere
        )";
        
        $db->query($sql);
        
        // Bind alle parameters
        foreach ($trump_data as $key => $value) {
            $db->bind(':' . $key, $value);
        }
        
        if ($db->execute()) {
            echo "✅ Donald Trump succesvol toegevoegd!\n";
        } else {
            echo "❌ Fout bij toevoegen Donald Trump\n";
        }
    }
    
    // Controleer Biden
    $db->query("SELECT * FROM amerikaanse_presidenten WHERE president_nummer = 46");
    $biden = $db->single();
    
    if ($biden) {
        echo "✅ Joe Biden staat in de database\n";
        echo "President #46: " . $biden->naam . "\n\n";
    } else {
        echo "❌ Joe Biden ontbreekt in de database\n";
    }
    
    // Controleer of Trump als 47e president er al in staat
    $db->query("SELECT * FROM amerikaanse_presidenten WHERE president_nummer = 47");
    $trump47 = $db->single();
    
    if ($trump47) {
        echo "✅ Donald Trump (47e president) staat al in de database\n";
        echo "President #47: " . $trump47->naam . "\n\n";
    } else {
        echo "❌ Donald Trump (47e president) ontbreekt - voegen toe...\n\n";
        
        // Voeg Donald Trump toe als 47e president (tweede termijn)
        $trump47_data = [
            'president_nummer' => 47,
            'naam' => 'Donald Trump',
            'volledige_naam' => 'Donald John Trump',
            'bijnaam' => 'The Donald',
            'partij' => 'Republican',
            'periode_start' => '2025-01-20',
            'periode_eind' => '2029-01-20',
            'is_huidig' => true,
            'geboren' => '1946-06-14',
            'overleden' => null,
            'geboorteplaats' => 'Queens, New York City, New York',
            'vice_president' => 'JD Vance',
            'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/56/Donald_Trump_official_portrait.jpg',
            'biografie' => 'Donald Trump is de 47e president van de Verenigde Staten, de eerste president in meer dan 130 jaar die terugkeert na een onderbreking. Hij diende eerder als 45e president (2017-2021) en won de verkiezing van 2024.',
            'vroeg_leven' => 'Geboren in een welvarende familie in Queens, New York. Studeerde aan Wharton School van de University of Pennsylvania.',
            'politieke_carriere' => 'Diende als 45e president (2017-2021), verloor de verkiezing van 2020, won de verkiezing van 2024 en keerde terug als 47e president.',
            'prestaties' => json_encode([
                'Eerste president sinds Grover Cleveland die terugkeert na onderbreking',
                'Oudste president ooit ingehuldigd (78 jaar bij tweede inauguratie)',
                'Won verkiezing 2024 ondanks juridische uitdagingen',
                'Bouwde sterke politieke beweging op na 2020',
                'Veranderde het Republikeinse partijlandschap'
            ]),
            'fun_facts' => json_encode([
                'Eerste president om niet-opeenvolgende termijnen te dienen sinds Grover Cleveland (1885-1889, 1893-1897)',
                'Oudste president ooit ingehuldigd voor tweede termijn',
                'Eerste president die veroordeeld werd en toch terugkwam',
                'Eerste president met eigen social media platform (Truth Social)',
                'Eerste president die impeached werd en toch terugkeerde'
            ]),
            'echtgenote' => 'Melania Trump',
            'kinderen' => json_encode(['Donald Jr.', 'Ivanka', 'Eric', 'Tiffany', 'Barron']),
            'familie_connecties' => 'Geen directe presidentiële familie.',
            'lengte_cm' => 190,
            'gewicht_kg' => 110,
            'verkiezingsjaren' => json_encode([2016, 2024]),
            'leeftijd_bij_aantreden' => 78,
            'belangrijkste_gebeurtenissen' => 'Historische terugkeer na 4 jaar, verkiezingsoverwinning 2024, juridische uitdagingen overwonnen',
            'bekende_speeches' => 'Inaugurele speech 2025, verkiezingscampagne speeches 2024',
            'wetgeving' => json_encode(['Nog te bepalen - net begonnen aan tweede termijn']),
            'oorlogen' => json_encode(['Nog te bepalen']),
            'economische_situatie' => 'Net begonnen aan tweede termijn - beleid nog in ontwikkeling',
            'carrierre_voor_president' => 'Vastgoedontwikkelaar, zakenman, tv-persoonlijkheid, 45e president',
            'carrierre_na_president' => 'Politieke activiteiten, verkiezingscampagne 2024, terugkeer als president',
            'doodsoorzaak' => null,
            'begrafenisplaats' => null,
            'historische_waardering' => 'Uniek in de Amerikaanse geschiedenis - eerste president sinds Cleveland met niet-opeenvolgende termijnen',
            'controverses' => 'Juridische uitdagingen, verkiezingsclaims 2020, Capitool gebeurtenissen, diverse rechtszaken',
            'citaten' => json_encode([
                '"I\'m back!"',
                '"Make America Great Again"',
                '"The greatest comeback in political history"'
            ]),
            'monumenten_ter_ere' => 'Geen officiële monumenten (nog levend en in functie)'
        ];
        
        $sql = "INSERT INTO amerikaanse_presidenten (
            president_nummer, naam, volledige_naam, bijnaam, partij, periode_start, periode_eind,
            is_huidig, geboren, overleden, geboorteplaats, vice_president, foto_url, biografie,
            vroeg_leven, politieke_carriere, prestaties, fun_facts, echtgenote, kinderen,
            familie_connecties, lengte_cm, gewicht_kg, verkiezingsjaren, leeftijd_bij_aantreden,
            belangrijkste_gebeurtenissen, bekende_speeches, wetgeving, oorlogen, economische_situatie,
            carrierre_voor_president, carrierre_na_president, doodsoorzaak, begrafenisplaats,
            historische_waardering, controverses, citaten, monumenten_ter_ere
        ) VALUES (
            :president_nummer, :naam, :volledige_naam, :bijnaam, :partij, :periode_start, :periode_eind,
            :is_huidig, :geboren, :overleden, :geboorteplaats, :vice_president, :foto_url, :biografie,
            :vroeg_leven, :politieke_carriere, :prestaties, :fun_facts, :echtgenote, :kinderen,
            :familie_connecties, :lengte_cm, :gewicht_kg, :verkiezingsjaren, :leeftijd_bij_aantreden,
            :belangrijkste_gebeurtenissen, :bekende_speeches, :wetgeving, :oorlogen, :economische_situatie,
            :carrierre_voor_president, :carrierre_na_president, :doodsoorzaak, :begrafenisplaats,
            :historische_waardering, :controverses, :citaten, :monumenten_ter_ere
        )";
        
        $db->query($sql);
        
        // Bind alle parameters voor Trump 47
        foreach ($trump47_data as $key => $value) {
            $db->bind(':' . $key, $value);
        }
        
        if ($db->execute()) {
            echo "✅ Donald Trump (47e president) succesvol toegevoegd!\n";
            
            // Update Biden's is_huidig status naar false
            $db->query("UPDATE amerikaanse_presidenten SET is_huidig = FALSE WHERE president_nummer = 46");
            $db->execute();
            echo "✅ Joe Biden's status bijgewerkt naar voormalig president\n";
        } else {
            echo "❌ Fout bij toevoegen Donald Trump (47e president)\n";
        }
    }
    
    // Toon eindresultaat
    $db->query("SELECT COUNT(*) as count FROM amerikaanse_presidenten");
    $final_result = $db->single();
    echo "Eindresultaat: " . $final_result->count . " presidenten in de database\n";
    
    if ($final_result->count == 47) {
        echo "🎉 Alle 47 presidenten zijn succesvol toegevoegd!\n";
        echo "Including Donald Trump's historic return as the 47th president!\n";
    } elseif ($final_result->count == 46) {
        echo "✅ Alle 46 historische presidenten toegevoegd. Trump 47 nog niet toegevoegd.\n";
    } else {
        echo "⚠️  Verwacht aantal: 47 presidenten, huidige aantal: " . $final_result->count . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Fout: " . $e->getMessage() . "\n";
} 