<?php
/**
 * Script om Amerikaanse verkiezingen database tabel aan te maken en te vullen
 * Inclusief velden voor foto URLs.
 *
 * Voer uit met: php scripts/setup_amerikaanse_verkiezingen.php
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

try {
    $db = new Database();
    echo "Database verbinding succesvol!\n";

    // Stap 1: Maak de tabel aan
    echo "\n=== Stap 1: Database tabel aanmaken ===\n";
    
    // De tabel wordt uitgebreid met winnaar_foto_url en verliezer_foto_url
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS amerikaanse_verkiezingen (
        id INT AUTO_INCREMENT PRIMARY KEY,
        jaar INT NOT NULL UNIQUE,
        winnaar VARCHAR(255) NOT NULL,
        winnaar_partij VARCHAR(100) NOT NULL,
        winnaar_kiesmannen INT NOT NULL,
        verliezer VARCHAR(255) NOT NULL,
        verliezer_partij VARCHAR(100) NOT NULL,
        verliezer_kiesmannen INT NOT NULL,
        winnaar_stemmen_populair BIGINT NOT NULL,
        verliezer_stemmen_populair BIGINT NOT NULL,
        winnaar_percentage_populair DECIMAL(5,2) NOT NULL,
        verliezer_percentage_populair DECIMAL(5,2) NOT NULL,
        totaal_kiesmannen INT NOT NULL,
        opkomst_percentage DECIMAL(5,2),
        belangrijkste_themas JSON,
        belangrijke_gebeurtenissen TEXT,
        opvallende_feiten TEXT,
        verkiezingsdata DATE,
        inhuldiging_data DATE,
        bronnen JSON,
        extra_kandidaten JSON,
        beschrijving TEXT,
        winnaar_foto_url VARCHAR(2083),
        verliezer_foto_url VARCHAR(2083),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_jaar (jaar),
        INDEX idx_winnaar_partij (winnaar_partij),
        INDEX idx_verkiezingsdata (verkiezingsdata)
    )";
    
    $db->query($createTableSQL);
    $db->execute();
    echo "✓ Tabel 'amerikaanse_verkiezingen' succesvol aangemaakt/bijgewerkt met fotovelden\n";

    // Stap 2: Controleer of er al data in de tabel staat
    echo "\n=== Stap 2: Data controle ===\n";
    
    $db->query("SELECT COUNT(*) as count FROM amerikaanse_verkiezingen");
    $result = $db->single();
    $existing_count = $result->count;
    
    echo "Bestaande verkiezingen in database: $existing_count\n";
    
    if ($existing_count > 0) {
        echo "Er staan al verkiezingen in de database. Wil je deze overschrijven? (y/N): ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if (trim($line) !== 'y' && trim($line) !== 'Y') {
            echo "Script gestopt. Geen data toegevoegd.\n";
            exit(0);
        }
        
        // Verwijder bestaande data
        $db->query("TRUNCATE TABLE amerikaanse_verkiezingen");
        $db->execute();
        echo "✓ Bestaande data verwijderd\n";
    }

    // Stap 3: Voeg de data toe
    echo "\n=== Stap 3: Data toevoegen (1900-2024) ===\n";
    
    // Data van 2024 tot 1900, inclusief placeholder URLs voor foto's
    // LET OP: Vervang de 'placehold.co' URLs met echte URLs naar de foto's.
    $verkiezingenData = [
        [
            'jaar' => 2024, 'winnaar' => 'Donald Trump', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 312, 'verliezer' => 'Kamala Harris', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 226,
            'winnaar_stemmen_populair' => 74223975, 'verliezer_stemmen_populair' => 70329765, 'winnaar_percentage_populair' => 50.0, 'verliezer_percentage_populair' => 47.4, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 64.5,
            'verkiezingsdata' => '2024-11-05', 'inhuldiging_data' => '2025-01-20', 'belangrijkste_themas' => ['Economie', 'Immigratie', 'Democratie', 'Abortus'],
            'belangrijke_gebeurtenissen' => 'Trump\'s comeback na zijn verlies in 2020 en verschillende juridische zaken. Harris nam het over van Biden die zich terugtrok uit de race.',
            'opvallende_feiten' => 'Trump wordt de eerste president sinds Grover Cleveland (1892) die niet-opeenvolgende termijnen dient. Hij is ook de oudste president ooit bij inhuldiging.',
            'beschrijving' => 'De verkiezing van 2024 was in eerste instantie een rematch tussen Trump en Biden, maar Harris nam het over na Biden\'s terugtrekking uit de race. Trump won zowel de kiesmannen als de populaire stemmen.',
            'bronnen' => ['CNN Election Results', 'Associated Press', 'Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'Robert F. Kennedy Jr.', 'partij' => 'Independent', 'percentage' => 0.5], ['naam' => 'Jill Stein', 'partij' => 'Green', 'percentage' => 0.4]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg/960px-Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Kamala_Harris_Vice_Presidential_Portrait.jpg/1200px-Kamala_Harris_Vice_Presidential_Portrait.jpg'
        ],
        [
            'jaar' => 2020, 'winnaar' => 'Joe Biden', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 306, 'verliezer' => 'Donald Trump', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 232,
            'winnaar_stemmen_populair' => 81283501, 'verliezer_stemmen_populair' => 74223975, 'winnaar_percentage_populair' => 51.3, 'verliezer_percentage_populair' => 46.8, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 66.6,
            'verkiezingsdata' => '2020-11-03', 'inhuldiging_data' => '2021-01-20', 'belangrijkste_themas' => ['COVID-19', 'Economie', 'Rassengelijkheid', 'Klimaat'],
            'belangrijke_gebeurtenissen' => 'Verkiezing tijdens COVID-19 pandemie met record aantal poststemmen. Trump betwistte de uitslag en riep op tot een bestorming van het Capitool op 6 januari 2021.',
            'opvallende_feiten' => 'Hoogste opkomst in meer dan een eeuw. Biden kreeg de meeste stemmen ooit in een Amerikaanse verkiezing. Trump weigerde nederlaag te erkennen.',
            'beschrijving' => 'De verkiezing werd overschaduwd door de COVID-19 pandemie en resulteerde in de hoogste opkomst sinds 1900. Biden werd de oudste president ooit bij inhuldiging.',
            'bronnen' => ['Reuters Election Results', 'New York Times', 'Ballotpedia'], 'extra_kandidaten' => [['naam' => 'Jo Jorgensen', 'partij' => 'Libertarian', 'percentage' => 1.2], ['naam' => 'Howie Hawkins', 'partij' => 'Green', 'percentage' => 0.3]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Joe_Biden_presidential_portrait.jpg/960px-Joe_Biden_presidential_portrait.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg/960px-Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg'
        ],
        [
            'jaar' => 2016, 'winnaar' => 'Donald Trump', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 304, 'verliezer' => 'Hillary Clinton', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 227,
            'winnaar_stemmen_populair' => 62984828, 'verliezer_stemmen_populair' => 65853514, 'winnaar_percentage_populair' => 46.1, 'verliezer_percentage_populair' => 48.2, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 55.7,
            'verkiezingsdata' => '2016-11-08', 'inhuldiging_data' => '2017-01-20', 'belangrijkste_themas' => ['Handel', 'Immigratie', 'Healthcare', 'E-mails'],
            'belangrijke_gebeurtenissen' => 'Clinton won de populaire stemmen maar verloor kiesmannen. FBI-onderzoek naar Clinton\'s e-mails speelde een rol.',
            'opvallende_feiten' => 'Trump had geen politieke ervaring. Clinton zou de eerste vrouwelijke president zijn geweest. Grootste electorale verrassing in moderne geschiedenis.',
            'beschrijving' => 'Een verrassende overwinning voor Trump tegen de verwachting van de meeste peilingen in. Het toonde de kracht van het kiesmansysteem versus populaire stemmen.',
            'bronnen' => ['Washington Post', 'Politico', 'ABC News'], 'extra_kandidaten' => [['naam' => 'Gary Johnson', 'partij' => 'Libertarian', 'percentage' => 3.3], ['naam' => 'Jill Stein', 'partij' => 'Green', 'percentage' => 1.1]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Donald_Trump_official_portrait.jpg/480px-Donald_Trump_official_portrait.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Hillary_Clinton_official_Secretary_of_State_portrait_crop.jpg/480px-Hillary_Clinton_official_Secretary_of_State_portrait_crop.jpg'
        ],
        [
                'jaar' => 2012, 'winnaar' => 'Barack Obama', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 332, 'verliezer' => 'Mitt Romney', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 206,
                'winnaar_stemmen_populair' => 65915795, 'verliezer_stemmen_populair' => 60933504, 'winnaar_percentage_populair' => 51.1, 'verliezer_percentage_populair' => 47.2, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 54.9,
                'verkiezingsdata' => '2012-11-06', 'inhuldiging_data' => '2013-01-20', 'belangrijkste_themas' => ['Economie', 'Healthcare', 'Buitenlands beleid'],
                'belangrijke_gebeurtenissen' => 'Obama\'s herverkiezing na de financiële crisis. Romney was succesvolle zakenman en voormalig gouverneur van Massachusetts.',
                'opvallende_feiten' => 'Obamacare was een belangrijk campagne-onderwerp. Obama won ondanks hoge werkloosheid.',
                'beschrijving' => 'Obama won zijn herverkiezing ondanks economische uitdagingen na de crisis van 2008. Romney was een sterke uitdager met zakelijke ervaring.',
                'bronnen' => ['USA Today', 'The Guardian', 'Pew Research'], 'extra_kandidaten' => [['naam' => 'Gary Johnson', 'partij' => 'Libertarian', 'percentage' => 1.0], ['naam' => 'Jill Stein', 'partij' => 'Green', 'percentage' => 0.4]],
                'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/President_Barack_Obama.jpg/960px-President_Barack_Obama.jpg',
                'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Mitt_Romney_official_US_Senate_portrait.jpg'
            ],
        [
            'jaar' => 2008, 'winnaar' => 'Barack Obama', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 365, 'verliezer' => 'John McCain', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 173,
            'winnaar_stemmen_populair' => 69498516, 'verliezer_stemmen_populair' => 59948323, 'winnaar_percentage_populair' => 52.9, 'verliezer_percentage_populair' => 45.7, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 58.2,
            'verkiezingsdata' => '2008-11-04', 'inhuldiging_data' => '2009-01-20', 'belangrijkste_themas' => ['Financiële crisis', 'Irak-oorlog', 'Healthcare', 'Change'],
            'belangrijke_gebeurtenissen' => 'Obama werd de eerste Afro-Amerikaanse president. De verkiezing vond plaats tijdens de financiële crisis van 2008.',
            'opvallende_feiten' => 'Obama\'s slogan "Yes We Can" werd iconisch. Sarah Palin was McCain\'s running mate. Sociale media speelde voor het eerst een grote rol.',
            'beschrijving' => 'Obama\'s historische overwinning tijdens een van de ergste financiële crises sinds de Grote Depressie. Zijn boodschap van "change" resoneerde sterk.',
            'bronnen' => ['NBC News', 'CBS News', 'Time Magazine'], 'extra_kandidaten' => [['naam' => 'Ralph Nader', 'partij' => 'Independent', 'percentage' => 0.6], ['naam' => 'Bob Barr', 'partij' => 'Libertarian', 'percentage' => 0.4]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/President_Barack_Obama.jpg/960px-President_Barack_Obama.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/e1/John_McCain_official_portrait_2009.jpg'
        ],
        [
            'jaar' => 2004, 'winnaar' => 'George W. Bush', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 286, 'verliezer' => 'John Kerry', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 251,
            'winnaar_stemmen_populair' => 62040610, 'verliezer_stemmen_populair' => 59028444, 'winnaar_percentage_populair' => 50.7, 'verliezer_percentage_populair' => 48.3, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 60.1,
            'verkiezingsdata' => '2004-11-02', 'inhuldiging_data' => '2005-01-20', 'belangrijkste_themas' => ['Irak-oorlog', 'Terrorisme', 'Economie', 'Morele waarden'],
            'belangrijke_gebeurtenissen' => 'Bush\'s herverkiezing tijdens de Irak-oorlog. Kerry was Vietnamveteraan en senator.',
            'opvallende_feiten' => 'Laatste keer dat een Republikein de populaire stemmen won tot 2024. Ohio was de beslissende staat.',
            'beschrijving' => 'Bush won herverkiezing ondanks controverses over de Irak-oorlog. Kerry kon niet genoeg kiezers overtuigen van verandering.',
            'bronnen' => ['Fox News', 'CNN', 'Los Angeles Times'], 'extra_kandidaten' => [['naam' => 'Ralph Nader', 'partij' => 'Independent', 'percentage' => 0.4], ['naam' => 'Michael Badnarik', 'partij' => 'Libertarian', 'percentage' => 0.3]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/George-W-Bush.jpeg/1200px-George-W-Bush.jpeg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/66/Official_portrait_of_Secretary_of_State_John_Kerry.jpg'
        ],
        [
            'jaar' => 2000, 'winnaar' => 'George W. Bush', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 271, 'verliezer' => 'Al Gore', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 266,
            'winnaar_stemmen_populair' => 50456002, 'verliezer_stemmen_populair' => 50999897, 'winnaar_percentage_populair' => 47.9, 'verliezer_percentage_populair' => 48.4, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 54.2,
            'verkiezingsdata' => '2000-11-07', 'inhuldiging_data' => '2001-01-20', 'belangrijkste_themas' => ['Economie', 'Belastingen', 'Sociale zekerheid'],
            'belangrijke_gebeurtenissen' => 'Extreem nipte verkiezing, beslist door de staat Florida. Na weken van hertellingen en rechtszaken stopte het Hooggerechtshof de hertelling in de zaak Bush v. Gore.',
            'opvallende_feiten' => 'Een van de vijf verkiezingen waarbij de winnaar de populaire stemmen verloor. De uitslag was pas op 12 december definitief.',
            'beschrijving' => 'Een van de meest controversiële verkiezingen in de Amerikaanse geschiedenis, waarbij de winnaar werd bepaald door slechts 537 stemmen in Florida en een beslissing van het Hooggerechtshof.',
            'bronnen' => ['Federal Election Commission', '270toWin'], 'extra_kandidaten' => [['naam' => 'Ralph Nader', 'partij' => 'Green', 'percentage' => 2.7]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/George-W-Bush.jpeg/1200px-George-W-Bush.jpeg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Al_Gore%2C_Vice_President_of_the_United_States%2C_official_portrait_1994.jpg'
        ],
        [
            'jaar' => 1996, 'winnaar' => 'Bill Clinton', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 379, 'verliezer' => 'Bob Dole', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 159,
            'winnaar_stemmen_populair' => 47401185, 'verliezer_stemmen_populair' => 39197729, 'winnaar_percentage_populair' => 49.2, 'verliezer_percentage_populair' => 40.7, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 49.0,
            'verkiezingsdata' => '1996-11-05', 'inhuldiging_data' => '1997-01-20', 'belangrijkste_themas' => ['Economie', 'Begrotingstekort', 'Zorgverzekering'],
            'belangrijke_gebeurtenissen' => 'Clinton werd herkozen tijdens een periode van economische voorspoed. De Republikeinen hadden in 1994 de controle over het Congres overgenomen.',
            'opvallende_feiten' => 'Ross Perot deed opnieuw mee als kandidaat voor de Reform Party en kreeg 8.4% van de stemmen, maar geen kiesmannen.',
            'beschrijving' => 'Clinton won comfortabel zijn herverkiezing dankzij een sterke economie, en werd de eerste Democraat sinds FDR die voor een tweede termijn werd gekozen.',
            'bronnen' => ['Federal Election Commission', 'History.com'], 'extra_kandidaten' => [['naam' => 'Ross Perot', 'partij' => 'Reform', 'percentage' => 8.4]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Bill_Clinton.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Ks_1996_dole.jpg/1200px-Ks_1996_dole.jpg'
        ],
        [
            'jaar' => 1992, 'winnaar' => 'Bill Clinton', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 370, 'verliezer' => 'George H. W. Bush', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 168,
            'winnaar_stemmen_populair' => 44909806, 'verliezer_stemmen_populair' => 39104550, 'winnaar_percentage_populair' => 43.0, 'verliezer_percentage_populair' => 37.4, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 55.2,
            'verkiezingsdata' => '1992-11-03', 'inhuldiging_data' => '1993-01-20', 'belangrijkste_themas' => ['Economie', 'Recessie', 'Werkloosheid'],
            'belangrijke_gebeurtenissen' => 'De onafhankelijke kandidaat Ross Perot speelde een grote rol en behaalde bijna 19% van de populaire stemmen, wat mogelijk stemmen wegnam bij zittend president Bush.',
            'opvallende_feiten' => 'Clinton\'s campagne had de beroemde interne slogan "It\'s the economy, stupid". Perot\'s 18.9% is het hoogste percentage voor een derde partij sinds 1912.',
            'beschrijving' => 'Een economische recessie en de sterke kandidatuur van Ross Perot leidden tot de nederlaag van de zittende president George H. W. Bush.',
            'bronnen' => ['Federal Election Commission', 'Britannica'], 'extra_kandidaten' => [['naam' => 'Ross Perot', 'partij' => 'Independent', 'percentage' => 18.9]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Bill_Clinton.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/George_H._W._Bush_presidential_portrait_%28cropped%29.jpg'
        ],
        [
            'jaar' => 1988, 'winnaar' => 'George H. W. Bush', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 426, 'verliezer' => 'Michael Dukakis', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 111,
            'winnaar_stemmen_populair' => 48886597, 'verliezer_stemmen_populair' => 41809476, 'winnaar_percentage_populair' => 53.4, 'verliezer_percentage_populair' => 45.6, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 50.2,
            'verkiezingsdata' => '1988-11-08', 'inhuldiging_data' => '1989-01-20', 'belangrijkste_themas' => ['Economie', 'Criminaliteit', 'Buitenlands beleid'],
            'belangrijke_gebeurtenissen' => 'Bush, de zittende vicepresident, voerde een succesvolle campagne die Dukakis als te liberaal afschilderde. De "Willie Horton" advertentie was zeer controversieel.',
            'opvallende_feiten' => 'Bush\'s belofte "Read my lips: no new taxes" zou hem later politiek achtervolgen. Dit was de derde opeenvolgende Republikeinse overwinning.',
            'beschrijving' => 'Bush profiteerde van de populariteit van de Reagan-jaren en won een overtuigende overwinning, hoewel de Democraten de controle over het Congres behielden.',
            'bronnen' => ['Federal Election Commission', 'Miller Center'], 'extra_kandidaten' => [['naam' => 'Lloyd Bentsen', 'partij' => 'Democratic', 'percentage' => 0.0]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/George_H._W._Bush_presidential_portrait_%28cropped%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/06/Michael_Dukakis_1988_DNC_%28cropped%29.jpg'
        ],
        [
            'jaar' => 1984, 'winnaar' => 'Ronald Reagan', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 525, 'verliezer' => 'Walter Mondale', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 13,
            'winnaar_stemmen_populair' => 54455472, 'verliezer_stemmen_populair' => 37577352, 'winnaar_percentage_populair' => 58.8, 'verliezer_percentage_populair' => 40.6, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 53.3,
            'verkiezingsdata' => '1984-11-06', 'inhuldiging_data' => '1985-01-21', 'belangrijkste_themas' => ['Economisch herstel', 'Koude Oorlog', 'Leiderschap'],
            'belangrijke_gebeurtenissen' => 'Reagan\'s "Morning in America" campagne benadrukte de economische opleving. Mondale\'s running mate, Geraldine Ferraro, was de eerste vrouwelijke VP-kandidaat van een grote partij.',
            'opvallende_feiten' => 'Een van de grootste "landslide" overwinningen in de Amerikaanse geschiedenis. Mondale won alleen zijn thuisstaat Minnesota en het District of Columbia.',
            'beschrijving' => 'Dankzij een bloeiende economie en zijn persoonlijke populariteit behaalde Reagan een verpletterende herverkiezingsoverwinning.',
            'bronnen' => ['Federal Election Commission', 'The American Presidency Project'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://hips.hearstapps.com/hmg-prod/images/gettyimages-113494477.jpg?crop=1xw:1.0xh;center,top&resize=640:*',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Waltermondaleasdiplomat.jpg/1200px-Waltermondaleasdiplomat.jpg'
        ],
        [
            'jaar' => 1980, 'winnaar' => 'Ronald Reagan', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 489, 'verliezer' => 'Jimmy Carter', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 49,
            'winnaar_stemmen_populair' => 43903230, 'verliezer_stemmen_populair' => 35480115, 'winnaar_percentage_populair' => 50.7, 'verliezer_percentage_populair' => 41.0, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 52.6,
            'verkiezingsdata' => '1980-11-04', 'inhuldiging_data' => '1981-01-20', 'belangrijkste_themas' => ['Economie (Stagflatie)', 'Iraanse gijzelingscrisis', 'Overheidsingrijpen'],
            'belangrijke_gebeurtenissen' => 'De aanhoudende gijzeling van Amerikanen in Iran en een zwakke economie (hoge inflatie en werkloosheid) ondermijnden het presidentschap van Carter.',
            'opvallende_feiten' => 'De Republikeinse kandidaat John Anderson deed mee als onafhankelijke en kreeg 6.6% van de stemmen. Reagan\'s vraag in het debat, "Are you better off than you were four years ago?", was zeer effectief.',
            'beschrijving' => 'Reagan\'s optimistische, conservatieve boodschap resoneerde met kiezers die ontevreden waren over de economie en het buitenlands beleid, wat leidde tot een duidelijke overwinning.',
            'bronnen' => ['Federal Election Commission', 'JFK Library'], 'extra_kandidaten' => [['naam' => 'John B. Anderson', 'partij' => 'Independent', 'percentage' => 6.6]],
            'winnaar_foto_url' => 'https://hips.hearstapps.com/hmg-prod/images/gettyimages-113494477.jpg?crop=1xw:1.0xh;center,top&resize=640:*',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/JimmyCarterPortrait2.jpg'
        ],
        [
            'jaar' => 1976, 'winnaar' => 'Jimmy Carter', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 297, 'verliezer' => 'Gerald Ford', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 240,
            'winnaar_stemmen_populair' => 40831881, 'verliezer_stemmen_populair' => 39148634, 'winnaar_percentage_populair' => 50.1, 'verliezer_percentage_populair' => 48.0, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 53.6,
            'verkiezingsdata' => '1976-11-02', 'inhuldiging_data' => '1977-01-20', 'belangrijkste_themas' => ['Watergate', 'Economie', 'Vertrouwen in de overheid'],
            'belangrijke_gebeurtenissen' => 'De eerste verkiezing na het Watergate-schandaal. Ford\'s gratieverlening aan Nixon was een belangrijk en controversieel onderwerp.',
            'opvallende_feiten' => 'Carter, een voormalig gouverneur van Georgia, was een relatieve buitenstaander in Washington. Ford was de enige president die nooit was gekozen als president of vicepresident.',
            'beschrijving' => 'Carter positioneerde zichzelf als een eerlijke buitenstaander en wist te profiteren van de publieke afkeer van de Washington-politiek na Watergate.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/JimmyCarterPortrait2.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Gerald_Ford_presidential_portrait.jpg/1200px-Gerald_Ford_presidential_portrait.jpg'
        ],
        [
            'jaar' => 1972, 'winnaar' => 'Richard Nixon', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 520, 'verliezer' => 'George McGovern', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 17,
            'winnaar_stemmen_populair' => 47168710, 'verliezer_stemmen_populair' => 29173222, 'winnaar_percentage_populair' => 60.7, 'verliezer_percentage_populair' => 37.5, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 55.1,
            'verkiezingsdata' => '1972-11-07', 'inhuldiging_data' => '1973-01-20', 'belangrijkste_themas' => ['Vietnamoorlog', 'Economie', 'Law and order'],
            'belangrijke_gebeurtenissen' => 'De inbraak in het Watergate-complex vond plaats in juni 1972, maar de volledige omvang van het schandaal werd pas na de verkiezingen duidelijk.',
            'opvallende_feiten' => 'Een van de grootste "landslide" overwinningen. McGovern won alleen Massachusetts en het District of Columbia. Nixon won de steun van veel conservatieve Democraten.',
            'beschrijving' => 'Nixon behaalde een verpletterende overwinning op de als extreem-links beschouwde McGovern, met een campagne gericht op de "stille meerderheid".',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ec/Richard_Nixon_presidential_portrait.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a3/George_McGovern_%28D-SD%29_%283x4-1%29.jpg'
        ],
        [
            'jaar' => 1968, 'winnaar' => 'Richard Nixon', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 301, 'verliezer' => 'Hubert Humphrey', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 191,
            'winnaar_stemmen_populair' => 31783783, 'verliezer_stemmen_populair' => 31271839, 'winnaar_percentage_populair' => 43.4, 'verliezer_percentage_populair' => 42.7, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 60.8,
            'verkiezingsdata' => '1968-11-05', 'inhuldiging_data' => '1969-01-20', 'belangrijkste_themas' => ['Vietnamoorlog', 'Law and order', 'Burgerrechten'],
            'belangrijke_gebeurtenissen' => 'Een tumultueus jaar met de moorden op Martin Luther King Jr. en Robert F. Kennedy, en hevige protesten tegen de Vietnamoorlog.',
            'opvallende_feiten' => 'De onafhankelijke kandidaat George Wallace, die voor segregatie was, won 5 staten in het Zuiden en kreeg 13.5% van de stemmen.',
            'beschrijving' => 'In een zeer verdeeld land won Nixon nipt van Humphrey met een belofte om de orde te herstellen en een eervol einde te maken aan de Vietnamoorlog.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'George Wallace', 'partij' => 'American Independent', 'percentage' => 13.5]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ec/Richard_Nixon_presidential_portrait.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/23/Hubert_Humphrey_vice_presidential_portrait.jpg'
        ],
        [
            'jaar' => 1964, 'winnaar' => 'Lyndon B. Johnson', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 486, 'verliezer' => 'Barry Goldwater', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 52,
            'winnaar_stemmen_populair' => 43127041, 'verliezer_stemmen_populair' => 27175754, 'winnaar_percentage_populair' => 61.1, 'verliezer_percentage_populair' => 38.5, 'totaal_kiesmannen' => 538, 'opkomst_percentage' => 61.9,
            'verkiezingsdata' => '1964-11-03', 'inhuldiging_data' => '1965-01-20', 'belangrijkste_themas' => ['Burgerrechten', 'Armoedebestrijding', 'Vietnam'],
            'belangrijke_gebeurtenissen' => 'Johnson, die president werd na de moord op Kennedy, lanceerde zijn "Great Society" programma. Goldwater werd gezien als een extreem-rechtse kandidaat.',
            'opvallende_feiten' => 'Johnson won met het grootste percentage van de populaire stemmen in de geschiedenis. De "Daisy" advertentie, die speelde op de angst voor een kernoorlog, was zeer invloedrijk.',
            'beschrijving' => 'Johnson behaalde een enorme overwinning, deels uit sympathie na de dood van JFK en deels omdat Goldwater als te extremistisch werd beschouwd door veel kiezers.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Lyndon_B._Johnson%2C_photo_portrait%2C_color_%283x4_cropped%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/19/Senator_Goldwater_1960.jpg'
        ],
        [
            'jaar' => 1960, 'winnaar' => 'John F. Kennedy', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 303, 'verliezer' => 'Richard Nixon', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 219,
            'winnaar_stemmen_populair' => 34220984, 'verliezer_stemmen_populair' => 34108157, 'winnaar_percentage_populair' => 49.7, 'verliezer_percentage_populair' => 49.5, 'totaal_kiesmannen' => 537, 'opkomst_percentage' => 62.8,
            'verkiezingsdata' => '1960-11-08', 'inhuldiging_data' => '1961-01-20', 'belangrijkste_themas' => ['Koude Oorlog', 'Economie', 'Ervaring'],
            'belangrijke_gebeurtenissen' => 'De eerste presidentiële debatten op televisie, die een grote rol speelden. Kennedy\'s jeugd en charisma werden afgezet tegen Nixon\'s ervaring.',
            'opvallende_feiten' => 'Een van de nipste verkiezingen in de geschiedenis. Kennedy was de eerste katholieke president. Er waren beschuldigingen van fraude in Illinois en Texas.',
            'beschrijving' => 'De televisiedebatten gaven Kennedy een voorsprong op de meer ervaren Nixon. Zijn jeugdige uitstraling en boodschap van een "New Frontier" spraken veel kiezers aan.',
            'bronnen' => ['Federal Election Commission', 'JFK Library'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://m.media-amazon.com/images/M/MV5BZGQ4NzZlNTktMWZlYS00N2NjLWJhZWYtZjZjMTMxYWQxMjcwXkEyXkFqcGc@._V1_.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ec/Richard_Nixon_presidential_portrait.jpg'
        ],
        [
            'jaar' => 1956, 'winnaar' => 'Dwight D. Eisenhower', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 457, 'verliezer' => 'Adlai Stevenson', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 73,
            'winnaar_stemmen_populair' => 35579180, 'verliezer_stemmen_populair' => 26028028, 'winnaar_percentage_populair' => 57.4, 'verliezer_percentage_populair' => 42.0, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 59.3,
            'verkiezingsdata' => '1956-11-06', 'inhuldiging_data' => '1957-01-21', 'belangrijkste_themas' => ['Vrede', 'Voorspoed', 'Koude Oorlog'],
            'belangrijke_gebeurtenissen' => 'De Suezcrisis en de Hongaarse Opstand vonden plaats kort voor de verkiezingen, wat de focus legde op buitenlands beleid en Eisenhower\'s leiderschap.',
            'opvallende_feiten' => 'Een herhaling van de verkiezing van 1952, met een nog grotere overwinning voor Eisenhower. Hij was populair vanwege de economische welvaart en het einde van de Koreaanse oorlog.',
            'beschrijving' => 'Eisenhower werd comfortabel herkozen op een platform van "Peace and Prosperity", en werd gezien als een stabiele leider in een onrustige wereld.',
            'bronnen' => ['Federal Election Commission', 'Eisenhower Library'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Dwight_D._Eisenhower%2C_official_photo_portrait%2C_May_29%2C_1959.jpg/330px-Dwight_D._Eisenhower%2C_official_photo_portrait%2C_May_29%2C_1959.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Portrait_of_Ambassador_Adlai_E._Stevenson_II_%283x4_crop%29.jpg/330px-Portrait_of_Ambassador_Adlai_E._Stevenson_II_%283x4_crop%29.jpg'
        ],
        [
            'jaar' => 1952, 'winnaar' => 'Dwight D. Eisenhower', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 442, 'verliezer' => 'Adlai Stevenson', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 89,
            'winnaar_stemmen_populair' => 34075529, 'verliezer_stemmen_populair' => 27375090, 'winnaar_percentage_populair' => 55.2, 'verliezer_percentage_populair' => 44.3, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 61.6,
            'verkiezingsdata' => '1952-11-04', 'inhuldiging_data' => '1953-01-20', 'belangrijkste_themas' => ['Koreaanse Oorlog', 'Communisme', 'Corruptie'],
            'belangrijke_gebeurtenissen' => 'De slogan "I Like Ike" was enorm populair. Eisenhower, een oorlogsheld uit WOII, beloofde naar Korea te gaan om de oorlog te beëindigen.',
            'opvallende_feiten' => 'Dit was de eerste keer in 20 jaar dat een Republikein het Witte Huis won. Eisenhower\'s running mate Richard Nixon hield de beroemde "Checkers" toespraak om zijn naam te zuiveren.',
            'beschrijving' => 'Ontevredenheid over de Koreaanse Oorlog en beschuldigingen van corruptie tegen de Truman-regering leidden tot een grote overwinning voor de immens populaire generaal Eisenhower.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Dwight_D._Eisenhower%2C_official_photo_portrait%2C_May_29%2C_1959.jpg/330px-Dwight_D._Eisenhower%2C_official_photo_portrait%2C_May_29%2C_1959.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Portrait_of_Ambassador_Adlai_E._Stevenson_II_%283x4_crop%29.jpg/330px-Portrait_of_Ambassador_Adlai_E._Stevenson_II_%283x4_crop%29.jpg'
        ],
        [
            'jaar' => 1948, 'winnaar' => 'Harry S. Truman', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 303, 'verliezer' => 'Thomas E. Dewey', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 189,
            'winnaar_stemmen_populair' => 24179347, 'verliezer_stemmen_populair' => 21991291, 'winnaar_percentage_populair' => 49.6, 'verliezer_percentage_populair' => 45.1, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 51.1,
            'verkiezingsdata' => '1948-11-02', 'inhuldiging_data' => '1949-01-20', 'belangrijkste_themas' => ['Koude Oorlog', 'Burgerrechten', 'Economie'],
            'belangrijke_gebeurtenissen' => 'De Democratische partij was verdeeld, met afsplitsingen van de "Dixiecrats" (pro-segregatie) en de Progressieve Partij.',
            'opvallende_feiten' => 'Beschouwd als een van de grootste politieke verrassingen in de Amerikaanse geschiedenis. De Chicago Daily Tribune drukte de beroemde foute krantenkop "DEWEY DEFEATS TRUMAN".',
            'beschrijving' => 'Tegen alle verwachtingen in won Truman met een energieke "whistle-stop" campagne, waarbij hij het "do-nothing" Republikeinse Congres aanviel.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'Strom Thurmond', 'partij' => 'States\' Rights', 'percentage' => 2.4], ['naam' => 'Henry A. Wallace', 'partij' => 'Progressive', 'percentage' => 2.4]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0b/TRUMAN_58-766-06_%28cropped%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Thomas_Dewey_in_1944.jpg/1200px-Thomas_Dewey_in_1944.jpg'
        ],
        [
            'jaar' => 1944, 'winnaar' => 'Franklin D. Roosevelt', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 432, 'verliezer' => 'Thomas E. Dewey', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 99,
            'winnaar_stemmen_populair' => 25612916, 'verliezer_stemmen_populair' => 22017929, 'winnaar_percentage_populair' => 53.4, 'verliezer_percentage_populair' => 45.9, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 56.0,
            'verkiezingsdata' => '1944-11-07', 'inhuldiging_data' => '1945-01-20', 'belangrijkste_themas' => ['Tweede Wereldoorlog', 'Leiderschap in oorlogstijd'],
            'belangrijke_gebeurtenissen' => 'De verkiezing vond plaats tijdens de Tweede Wereldoorlog, met de geallieerde invasie van Normandië (D-Day) die in juni had plaatsgevonden.',
            'opvallende_feiten' => 'Roosevelt werd gekozen voor een ongekende vierde termijn. Zijn gezondheid was al slecht, en hij stierf slechts enkele maanden na zijn inhuldiging in april 1945.',
            'beschrijving' => 'Kiezers wilden geen leider wisselen tijdens de oorlog, wat leidde tot een comfortabele overwinning voor Roosevelt, ondanks zorgen over zijn gezondheid en lange ambtstermijn.',
            'bronnen' => ['Federal Election Commission', 'FDR Library'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/67/FDR_1944_Color_Portrait_%28cropped%29%28b%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Thomas_Dewey_in_1944.jpg/1200px-Thomas_Dewey_in_1944.jpg'
        ],
        [
            'jaar' => 1940, 'winnaar' => 'Franklin D. Roosevelt', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 449, 'verliezer' => 'Wendell Willkie', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 82,
            'winnaar_stemmen_populair' => 27313945, 'verliezer_stemmen_populair' => 22347744, 'winnaar_percentage_populair' => 54.7, 'verliezer_percentage_populair' => 44.8, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 62.4,
            'verkiezingsdata' => '1940-11-05', 'inhuldiging_data' => '1941-01-20', 'belangrijkste_themas' => ['Dreiging van WOII', 'New Deal', 'Derde termijn'],
            'belangrijke_gebeurtenissen' => 'De oorlog in Europa (val van Frankrijk, Slag om Engeland) domineerde de verkiezing. Roosevelt beloofde Amerikaanse jongens niet naar een buitenlandse oorlog te sturen.',
            'opvallende_feiten' => 'Roosevelt doorbrak de traditie van maximaal twee termijnen, die door George Washington was ingesteld. Willkie was een zakenman die nog nooit een politiek ambt had bekleed.',
            'beschrijving' => 'De groeiende dreiging van de Tweede Wereldoorlog overtuigde veel Amerikanen ervan dat het geen tijd was om van leider te wisselen, wat FDR een historische derde termijn opleverde.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/67/FDR_1944_Color_Portrait_%28cropped%29%28b%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Portrait_of_Wendell_Lewis_Willkie_Sitting_by_Wall_%28cropped%29.jpg/640px-Portrait_of_Wendell_Lewis_Willkie_Sitting_by_Wall_%28cropped%29.jpg'
        ],
        [
            'jaar' => 1936, 'winnaar' => 'Franklin D. Roosevelt', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 523, 'verliezer' => 'Alf Landon', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 8,
            'winnaar_stemmen_populair' => 27752648, 'verliezer_stemmen_populair' => 16681862, 'winnaar_percentage_populair' => 60.8, 'verliezer_percentage_populair' => 36.5, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 56.9,
            'verkiezingsdata' => '1936-11-03', 'inhuldiging_data' => '1937-01-20', 'belangrijkste_themas' => ['New Deal', 'Economisch herstel'],
            'belangrijke_gebeurtenissen' => 'De verkiezing was een referendum over Roosevelt\'s New Deal-beleid. De economie begon tekenen van herstel te vertonen.',
            'opvallende_feiten' => 'Een van de grootste "landslide" overwinningen. Landon won alleen de staten Maine en Vermont. Dit was de eerste verkiezing met de inhuldiging op 20 januari (voorheen 4 maart).',
            'beschrijving' => 'De populariteit van de New Deal-programma\'s gaf Roosevelt een verpletterende overwinning en consolideerde de "New Deal Coalition" die de Democratische partij decennialang zou domineren.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/67/FDR_1944_Color_Portrait_%28cropped%29%28b%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/db/LandonPortr.jpg'
        ],
        [
            'jaar' => 1932, 'winnaar' => 'Franklin D. Roosevelt', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 472, 'verliezer' => 'Herbert Hoover', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 59,
            'winnaar_stemmen_populair' => 22821277, 'verliezer_stemmen_populair' => 15761254, 'winnaar_percentage_populair' => 57.4, 'verliezer_percentage_populair' => 39.7, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 52.6,
            'verkiezingsdata' => '1932-11-08', 'inhuldiging_data' => '1933-03-04', 'belangrijkste_themas' => ['Grote Depressie', 'Werkloosheid', 'Rol van de overheid'],
            'belangrijke_gebeurtenissen' => 'De verkiezing vond plaats op het dieptepunt van de Grote Depressie, met een werkloosheid van bijna 25%.',
            'opvallende_feiten' => 'Roosevelt beloofde een "New Deal" voor het Amerikaanse volk. De overwinning markeerde een grote politieke verschuiving naar de Democratische partij.',
            'beschrijving' => 'Zittend president Hoover werd verantwoordelijk gehouden voor de Grote Depressie. Roosevelt\'s optimisme en belofte van actie leidden tot een enorme overwinning.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/67/FDR_1944_Color_Portrait_%28cropped%29%28b%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/57/President_Hoover_portrait.jpg'
        ],
        [
            'jaar' => 1928, 'winnaar' => 'Herbert Hoover', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 444, 'verliezer' => 'Al Smith', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 87,
            'winnaar_stemmen_populair' => 21427123, 'verliezer_stemmen_populair' => 15015464, 'winnaar_percentage_populair' => 58.2, 'verliezer_percentage_populair' => 40.8, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 56.9,
            'verkiezingsdata' => '1928-11-06', 'inhuldiging_data' => '1929-03-04', 'belangrijkste_themas' => ['Economische voorspoed', 'Drooglegging (Prohibition)', 'Religie'],
            'belangrijke_gebeurtenissen' => 'De "Roaring Twenties" waren op hun hoogtepunt. Hoover beloofde "een kip in elke pan en een auto in elke garage".',
            'opvallende_feiten' => 'Al Smith was de eerste katholieke presidentskandidaat van een grote partij, wat op veel anti-katholiek sentiment stuitte. De beurskrach van 1929 vond plaats 8 maanden na Hoover\'s inhuldiging.',
            'beschrijving' => 'Hoover profiteerde van de economische voorspoed van de jaren \'20 en won overtuigend van Smith, wiens katholicisme en standpunt tegen de drooglegging hem stemmen kostten.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/57/President_Hoover_portrait.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/AlfredSmith.jpg/960px-AlfredSmith.jpg'
        ],
        [
            'jaar' => 1924, 'winnaar' => 'Calvin Coolidge', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 382, 'verliezer' => 'John W. Davis', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 136,
            'winnaar_stemmen_populair' => 15723789, 'verliezer_stemmen_populair' => 8386242, 'winnaar_percentage_populair' => 54.0, 'verliezer_percentage_populair' => 28.8, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 48.9,
            'verkiezingsdata' => '1924-11-04', 'inhuldiging_data' => '1925-03-04', 'belangrijkste_themas' => ['Economie', 'Beperkte overheid'],
            'belangrijke_gebeurtenissen' => 'Coolidge, die president werd na de dood van Harding, had een imago van fatsoen en rust na de schandalen van de vorige regering.',
            'opvallende_feiten' => 'De Progressieve kandidaat Robert M. La Follette kreeg een indrukwekkende 16.6% van de stemmen en won zijn thuisstaat Wisconsin.',
            'beschrijving' => 'Onder de slogan "Keep Cool with Coolidge" won de zittende president gemakkelijk dankzij de bloeiende economie en zijn stille, conservatieve stijl.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'Robert M. La Follette', 'partij' => 'Progressive', 'percentage' => 16.6]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/49/President_Calvin_Coolidge%2C_1924_portrait_photograph_%283x4_cropped%29.jpeg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/DAVIS%2C_JOHN_W._HONORABLE_LCCN2016862563_%28cropped%29.jpg/1200px-DAVIS%2C_JOHN_W._HONORABLE_LCCN2016862563_%28cropped%29.jpg'
        ],
        [
            'jaar' => 1920, 'winnaar' => 'Warren G. Harding', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 404, 'verliezer' => 'James M. Cox', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 127,
            'winnaar_stemmen_populair' => 16144093, 'verliezer_stemmen_populair' => 9139661, 'winnaar_percentage_populair' => 60.3, 'verliezer_percentage_populair' => 34.1, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 49.2,
            'verkiezingsdata' => '1920-11-02', 'inhuldiging_data' => '1921-03-04', 'belangrijkste_themas' => ['Volkenbond', 'Normaliteit na WOI'],
            'belangrijke_gebeurtenissen' => 'De eerste verkiezing waarin vrouwen in alle staten mochten stemmen na de ratificatie van het 19e amendement.',
            'opvallende_feiten' => 'Harding voerde campagne met de belofte van een "Return to Normalcy" (terugkeer naar normaal) na de Eerste Wereldoorlog. De socialistische kandidaat Eugene V. Debs voerde campagne vanuit de gevangenis en kreeg bijna een miljoen stemmen.',
            'beschrijving' => 'Het Amerikaanse volk was de internationale betrokkenheid onder Wilson beu en koos massaal voor Harding\'s boodschap van isolationisme en binnenlandse focus.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'Eugene V. Debs', 'partij' => 'Socialist', 'percentage' => 3.4]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Warren_G_Harding-Harris_%26_Ewing.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8e/James_M._Cox_1920.jpg'
        ],
        [
            'jaar' => 1916, 'winnaar' => 'Woodrow Wilson', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 277, 'verliezer' => 'Charles Evans Hughes', 'verliezer_partij' => 'Republican', 'verliezer_kiesmannen' => 254,
            'winnaar_stemmen_populair' => 9126868, 'verliezer_stemmen_populair' => 8548728, 'winnaar_percentage_populair' => 49.2, 'verliezer_percentage_populair' => 46.1, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 61.6,
            'verkiezingsdata' => '1916-11-07', 'inhuldiging_data' => '1917-03-05', 'belangrijkste_themas' => ['Eerste Wereldoorlog', 'Neutraliteit'],
            'belangrijke_gebeurtenissen' => 'De verkiezing werd overschaduwd door de Eerste Wereldoorlog die in Europa woedde.',
            'opvallende_feiten' => 'Wilson won een nipte herverkiezing met de slogan "He Kept Us Out of War". Ironisch genoeg traden de VS vijf maanden na zijn inhuldiging toe tot de oorlog.',
            'beschrijving' => 'De verkiezing was extreem nipt en de uitslag hing af van de staat Californië. Wilson\'s belofte van neutraliteit was de sleutel tot zijn overwinning.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/cd/President_Woodrow_Wilson_by_Harris_%26_Ewing%2C_1914-crop_%282%29.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Charles_Evans_Hughes_cph.3b15401.jpg'
        ],
        [
            'jaar' => 1912, 'winnaar' => 'Woodrow Wilson', 'winnaar_partij' => 'Democratic', 'winnaar_kiesmannen' => 435, 'verliezer' => 'Theodore Roosevelt', 'verliezer_partij' => 'Progressive', 'verliezer_kiesmannen' => 88,
            'winnaar_stemmen_populair' => 6296284, 'verliezer_stemmen_populair' => 4122721, 'winnaar_percentage_populair' => 41.8, 'verliezer_percentage_populair' => 27.4, 'totaal_kiesmannen' => 531, 'opkomst_percentage' => 58.8,
            'verkiezingsdata' => '1912-11-05', 'inhuldiging_data' => '1913-03-04', 'belangrijkste_themas' => ['Trusts', 'Hervormingen', 'Tarieven'],
            'belangrijke_gebeurtenissen' => 'De Republikeinse partij was gescheurd tussen de zittende president William Howard Taft en de voormalige president Theodore Roosevelt.',
            'opvallende_feiten' => 'Roosevelt deed mee als kandidaat voor de Progressive ("Bull Moose") Party, waardoor de Republikeinse stemmen werden verdeeld en de Democraat Wilson kon winnen. Roosevelt overleefde een moordaanslag tijdens de campagne.',
            'beschrijving' => 'Door de splitsing in de Republikeinse partij won Wilson gemakkelijk het kiescollege, ook al had hij slechts 41.8% van de populaire stemmen.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [['naam' => 'William Howard Taft', 'partij' => 'Republican', 'percentage' => 23.2]],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/cd/President_Woodrow_Wilson_by_Harris_%26_Ewing%2C_1914-crop_%282%29.jpg',
            'verliezer_foto_url' => 'https://bidenwhitehouse.archives.gov/wp-content/uploads/2021/01/26_theodore_roosevelt.jpg'
        ],
        [
            'jaar' => 1908, 'winnaar' => 'William Howard Taft', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 321, 'verliezer' => 'William Jennings Bryan', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 162,
            'winnaar_stemmen_populair' => 7678335, 'verliezer_stemmen_populair' => 6408979, 'winnaar_percentage_populair' => 51.6, 'verliezer_percentage_populair' => 43.0, 'totaal_kiesmannen' => 483, 'opkomst_percentage' => 65.4,
            'verkiezingsdata' => '1908-11-03', 'inhuldiging_data' => '1909-03-04', 'belangrijkste_themas' => ['Voortzetting Roosevelt-beleid', 'Trusts'],
            'belangrijke_gebeurtenissen' => 'Taft was de door Theodore Roosevelt persoonlijk uitgekozen opvolger.',
            'opvallende_feiten' => 'Dit was de derde en laatste keer dat William Jennings Bryan de Democratische presidentskandidaat was; hij verloor alle drie de keren.',
            'beschrijving' => 'Taft won door te beloven het populaire beleid van zijn voorganger, Theodore Roosevelt, voort te zetten.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a1/Cabinet_card_of_William_Howard_Taft_by_Pach_Brothers_-_Cropped_to_image.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Portrait_of_Secretary_William_Jennings_Bryan_of_Nebraska%2C_1913.jpeg'
        ],
        [
            'jaar' => 1904, 'winnaar' => 'Theodore Roosevelt', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 336, 'verliezer' => 'Alton B. Parker', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 140,
            'winnaar_stemmen_populair' => 7630457, 'verliezer_stemmen_populair' => 5083880, 'winnaar_percentage_populair' => 56.4, 'verliezer_percentage_populair' => 37.6, 'totaal_kiesmannen' => 476, 'opkomst_percentage' => 65.2,
            'verkiezingsdata' => '1904-11-08', 'inhuldiging_data' => '1905-03-04', 'belangrijkste_themas' => ['Regulering van trusts', 'Buitenlands beleid'],
            'belangrijke_gebeurtenissen' => 'Roosevelt, die president werd na de moord op McKinley in 1901, voerde campagne op zijn "Square Deal" beleid.',
            'opvallende_feiten' => 'Roosevelt was enorm populair en won met een grote marge. Hij was de eerste vicepresident die na de dood van een president werd herkozen op eigen kracht.',
            'beschrijving' => 'Roosevelt\'s energieke leiderschap en progressieve hervormingen waren populair bij het volk, wat leidde tot een gemakkelijke overwinning.',
            'bronnen' => ['Federal Election Commission'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://bidenwhitehouse.archives.gov/wp-content/uploads/2021/01/26_theodore_roosevelt.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/47/AltonBParker.png'
        ],
        [
            'jaar' => 1900, 'winnaar' => 'William McKinley', 'winnaar_partij' => 'Republican', 'winnaar_kiesmannen' => 292, 'verliezer' => 'William Jennings Bryan', 'verliezer_partij' => 'Democratic', 'verliezer_kiesmannen' => 155,
            'winnaar_stemmen_populair' => 7228864, 'verliezer_stemmen_populair' => 6370932, 'winnaar_percentage_populair' => 51.6, 'verliezer_percentage_populair' => 45.5, 'totaal_kiesmannen' => 447, 'opkomst_percentage' => 73.2,
            'verkiezingsdata' => '1900-11-06', 'inhuldiging_data' => '1901-03-04', 'belangrijkste_themas' => ['Imperialisme', 'Goudstandaard', 'Economie'],
            'belangrijke_gebeurtenissen' => 'Een herhaling van de verkiezing van 1896. De Spaanse-Amerikaanse Oorlog van 1898 maakte imperialisme een belangrijk thema.',
            'opvallende_feiten' => 'McKinley werd zes maanden na zijn tweede inhuldiging vermoord, waardoor vicepresident Theodore Roosevelt president werd.',
            'beschrijving' => 'De verkiezing was een referendum over het beleid van McKinley, inclusief de goudstandaard en de recente territoriale uitbreidingen. Economische voorspoed hielp hem aan de overwinning.',
            'bronnen' => ['Federal Election Commission', 'National Archives'], 'extra_kandidaten' => [],
            'winnaar_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/6d/Mckinley.jpg',
            'verliezer_foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Portrait_of_Secretary_William_Jennings_Bryan_of_Nebraska%2C_1913.jpeg'
        ],
    ];

    // De INSERT query is uitgebreid met de nieuwe fotovelden
    $insertSQL = "INSERT INTO amerikaanse_verkiezingen (
        jaar, winnaar, winnaar_partij, winnaar_kiesmannen, verliezer, verliezer_partij, 
        verliezer_kiesmannen, winnaar_stemmen_populair, verliezer_stemmen_populair,
        winnaar_percentage_populair, verliezer_percentage_populair, totaal_kiesmannen, opkomst_percentage,
        belangrijkste_themas, belangrijke_gebeurtenissen, opvallende_feiten,
        verkiezingsdata, inhuldiging_data, bronnen, extra_kandidaten, beschrijving,
        winnaar_foto_url, verliezer_foto_url
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $successCount = 0;
    foreach ($verkiezingenData as $verkiezing) {
        try {
            $db->query($insertSQL);
            // Bind alle 23 parameters
            $db->bind(1, $verkiezing['jaar']);
            $db->bind(2, $verkiezing['winnaar']);
            $db->bind(3, $verkiezing['winnaar_partij']);
            $db->bind(4, $verkiezing['winnaar_kiesmannen']);
            $db->bind(5, $verkiezing['verliezer']);
            $db->bind(6, $verkiezing['verliezer_partij']);
            $db->bind(7, $verkiezing['verliezer_kiesmannen']);
            $db->bind(8, $verkiezing['winnaar_stemmen_populair']);
            $db->bind(9, $verkiezing['verliezer_stemmen_populair']);
            $db->bind(10, $verkiezing['winnaar_percentage_populair']);
            $db->bind(11, $verkiezing['verliezer_percentage_populair']);
            $db->bind(12, $verkiezing['totaal_kiesmannen']);
            $db->bind(13, $verkiezing['opkomst_percentage']);
            $db->bind(14, json_encode($verkiezing['belangrijkste_themas']));
            $db->bind(15, $verkiezing['belangrijke_gebeurtenissen']);
            $db->bind(16, $verkiezing['opvallende_feiten']);
            $db->bind(17, $verkiezing['verkiezingsdata']);
            $db->bind(18, $verkiezing['inhuldiging_data']);
            $db->bind(19, json_encode($verkiezing['bronnen']));
            $db->bind(20, json_encode($verkiezing['extra_kandidaten']));
            $db->bind(21, $verkiezing['beschrijving']);
            $db->bind(22, $verkiezing['winnaar_foto_url']);
            $db->bind(23, $verkiezing['verliezer_foto_url']);
            
            if ($db->execute()) {
                echo "✓ Verkiezing {$verkiezing['jaar']} toegevoegd\n";
                $successCount++;
            } else {
                echo "✗ Fout bij toevoegen verkiezing {$verkiezing['jaar']}\n";
            }
        } catch (Exception $e) {
            echo "✗ Fout bij verkiezing {$verkiezing['jaar']}: " . $e->getMessage() . "\n";
        }
    }

    // Stap 4: Controleer resultaat
    echo "\n=== Stap 4: Resultaat ===\n";
    
    $db->query("SELECT COUNT(*) as count FROM amerikaanse_verkiezingen");
    $result = $db->single();
    $total_count = $result->count;
    
    echo "✓ Script voltooid!\n";
    echo "✓ $successCount van " . count($verkiezingenData) . " verkiezingen succesvol toegevoegd\n";
    echo "✓ Totaal verkiezingen in database: $total_count\n";
    
    // Toon overzicht
    echo "\n=== Overzicht verkiezingen ===\n";
    $db->query("SELECT jaar, winnaar, winnaar_partij FROM amerikaanse_verkiezingen ORDER BY jaar DESC");
    $verkiezingen = $db->resultSet();
    
    foreach ($verkiezingen as $verkiezing) {
        echo "- {$verkiezing->jaar}: {$verkiezing->winnaar} ({$verkiezing->winnaar_partij})\n";
    }
    
    echo "\n✅ Setup voltooid! Je kunt nu de applicatie gebruiken.\n";

} catch (Exception $e) {
    echo "❌ FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
