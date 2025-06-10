<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

// Initialiseer database
$db = new Database();

// Maak eerst de tabel aan (als deze nog niet bestaat)
$createTableSQL = "
CREATE TABLE IF NOT EXISTS news_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    url VARCHAR(1000) NOT NULL,
    source VARCHAR(100) NOT NULL,
    bias VARCHAR(50) NOT NULL DEFAULT 'Neutraal',
    orientation VARCHAR(20) NOT NULL DEFAULT 'neutraal',
    published_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_source (source),
    INDEX idx_bias (bias),
    INDEX idx_orientation (orientation),
    INDEX idx_published_at (published_at)
)";

try {
    $db->directQuery($createTableSQL);
    echo "Tabel news_articles aangemaakt of bestaat al.\n";
} catch (Exception $e) {
    echo "Fout bij aanmaken tabel: " . $e->getMessage() . "\n";
    exit;
}

// De hardcoded nieuws data
$latest_news_by_orientation = [
    'links' => [
        [
            'orientation' => 'links',
            'source' => 'De Volkskrant',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s'), // Vandaag
            'title' => 'Monsterheffing op import iPhones in VS voorlopig van de baan, mogelijk wel een aparte \'sectorheffing\'',
            'description' => 'De vrijstelling van smartphones, computers en chips uit China van de hoogste Amerikaanse importheffing, is een voorlopige overwinning van bigtechbedrijven als Apple, Microsoft en Nvidia.',
            'url' => 'https://www.volkskrant.nl/economie/monsterheffing-op-import-iphones-in-vs-voorlopig-van-de-baan-mogelijk-wel-een-aparte-sectorheffing~bfdba99d/'
        ],
        [
            'orientation' => 'links',
            'source' => 'NRC',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-1 hours')), // 1 uur geleden
            'title' => 'Hoe de premier in één week twee tegengestelde opdrachten kreeg',
            'description' => 'Ambtenaren waarschuwen informeel voor het verval van de bestuurlijke kwaliteit onder deze coalitie. Een overzicht: onberekenbare partijleiders, eigengereide bewindslieden, bijzondere adviseurs.',
            'url' => 'https://www.nrc.nl/nieuws/2025/04/12/hoe-de-premier-in-een-week-twee-tegengestelde-opdrachten-kreeg-a4889643'
        ],
        [
            'orientation' => 'links',
            'source' => 'Joop (BNNVARA)',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-3 hours')), // 3 uur geleden
            'title' => 'Is het gezond verstand van Caroline van der Plas met het blote oog waarneembaar?',
            'description' => 'Caroline van der Plas staat erom bekend "uit te gaan van gezond verstand", maar in de praktijk betekent het vooral dat zij haar eigen gevoel boven wetenschappelijke kennis plaatst.',
            'url' => 'https://www.bnnvara.nl/joop/artikelen/is-het-gezond-verstand-van-caroline-van-der-plas-met-het-blote-oog-waarneembaar'
        ],
        [
            'orientation' => 'links',
            'source' => 'Het Parool',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-5 hours')), // 5 uur geleden
            'title' => 'Waarom Musk steeds meer een politiek blok aan Trumps been wordt: \'Ik heb Elon nergens meer voor nodig\'',
            'description' => 'De miljardair en X-eigenaar Elon Musk is een enthousiaste Trump-supporter en werkt voor de president, maar zijn onvoorspelbare gedrag en tweets zetten de relatie onder druk.',
            'url' => 'https://www.parool.nl/wereld/waarom-musk-steeds-meer-een-politiek-blok-aan-trumps-been-wordt-ik-heb-elon-nergens-meer-voor-nodig~be0d6e01/'
        ],
        [
            'orientation' => 'links',
            'source' => 'OneWorld',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-7 hours')), // 7 uur geleden
            'title' => 'Anouks transfobie schokkend? Wacht tot je politici hoort',
            'description' => 'De recente uitspraken van Anouk over transgender personen hebben voor ophef gezorgd, maar blijven ver achter bij de transfobie die te horen is bij sommige politici.',
            'url' => 'https://www.oneworld.nl/identiteit/anouks-transfobie-schokkend-wacht-tot-je-politici-hoort/'
        ],
        [
            'orientation' => 'links',
            'source' => 'Krapuul',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-9 hours')), // 9 uur geleden
            'title' => 'Israëlische leger (IDF) ontbindt peloton en ontslaat officieren na vernieling door reservisten van kamp op Westelijke Jordaanoever',
            'description' => 'Het Israëlische leger heeft een peloton ontbonden en meerdere officieren ontslagen nadat reservisten vernielingen hadden aangericht in een kamp op de Westelijke Jordaanoever.',
            'url' => 'https://krapuul.nl/israelische-leger-idf-ontbindt-peloton-en-ontslaat-officieren-na-vernieling-door-reservisten-van-kamp-op-westelijke-jordaanoever/'
        ],
        [
            'orientation' => 'links',
            'source' => 'Socialisme.nu',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-11 hours')), // 11 uur geleden
            'title' => 'PVV-kabinet laat huren explosief stijgen',
            'description' => 'Het PVV-kabinet laat de huren explosief stijgen door het afschaffen van de huurprijsbescherming en het verlagen van de huurtoeslag.',
            'url' => 'https://socialisme.nu/pvv-kabinet-laat-huren-explosief-stijgen/'
        ],
        [
            'orientation' => 'links',
            'source' => 'Trouw',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-14 hours')), // 14 uur geleden
            'title' => 'D66 pakt vrije rol in de oppositie en presenteert wilde plannen',
            'description' => 'D66 presenteert als oppositiepartij een reeks opvallende voorstellen, waaronder het afschaffen van de monarchie en het legaliseren van harddrugs. De partij kiest voor een vrije rol in de oppositie.',
            'url' => 'https://www.trouw.nl/politiek/d66-pakt-vrije-rol-in-de-oppositie-en-presenteert-wilde-plannen~bf16313b/'
        ]
    ],
    'rechts' => [
        [
            'orientation' => 'rechts',
            'source' => 'Telegraaf',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-5 hours')), // 5 uur geleden
            'title' => 'Poetin tart Trump met dodelijke raketaanval op Soemy vlak na gesprek met VS',
            'description' => 'De Russische raketaanval op Palmzondag in het centrum van Soemy heeft het leven gekost aan meer dan dertig Oekraïense burgers. Onder de doden zouden meerdere kinderen zijn.',
            'url' => 'https://www.telegraaf.nl/nieuws/1338036962/poetin-tart-trump-met-dodelijke-raketaanval-op-soemy-vlak-na-gesprek-met-vs-adviseur-zelenski-haalt-uit-naar-witte-huis'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'GeenStijl',
            'bias' => 'Conservatief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
            'title' => 'Vreemd bericht in Israëlische Joop: "Hamas heeft volgens IDF-officials opnieuw 40.000 strijders"',
            'description' => 'Hoogst opmerkelijk citaat afgelopen vrijdag in Haaretz: Israëlische defensiebronnen schatten dat Hamas opnieuw 40.000 strijders zou hebben, ondanks eerdere berichten over zware verliezen.',
            'url' => 'https://www.geenstijl.nl/5182598/vreemd-bericht-in-israelische-joop-hamas-heeft-volgens-idf-officials-opnieuw-40000-strijders'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'EW Magazine',
            'bias' => 'Conservatief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-7 hours')), // 7 uur geleden
            'title' => 'Druzen in Israël: vechten voor de Joodse staat, maar in de steek gelaten door Netanyahoe',
            'description' => 'De Druzische minderheid in Israël vecht traditiegetrouw mee in het Israëlische leger, maar voelt zich in de steek gelaten door de regering van Netanyahu.',
            'url' => 'https://www.ewmagazine.nl/buitenland/achtergrond/2025/04/druzen-israel-syrie-96507w/'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'Ongehoord Nederland',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
            'title' => 'Slechts kwart kiezers rechtse partijen vindt publieke omroep pluriform genoeg',
            'description' => 'Uit onderzoek blijkt dat slechts een kwart van de kiezers van rechtse partijen de publieke omroep pluriform genoeg vindt, terwijl de achterban van linkse partijen overwegend tevreden is.',
            'url' => 'https://ongehoordnederland.tv/244588/persberichten/slechts-kwart-kiezers-rechtse-partijen-vindt-publieke-omroep-pluriform-genoeg-achterban-linkse-partijen-wel-dik-tevreden/'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'Nieuw Rechts',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-9 hours')), // 9 uur geleden
            'title' => 'Kamer stemt voor keuzevrijheid bij woningverkoop: motie De Vos',
            'description' => 'De Tweede Kamer heeft een motie aangenomen die meer keuzevrijheid biedt bij woningverkoop. De motie van Kamerlid De Vos moet het voor huizenbezitters makkelijker maken zelf te bepalen aan wie ze hun woning verkopen.',
            'url' => 'https://nieuwrechts.nl/103699-kamer-stemt-voor-keuzevrijheid-bij-woningverkoop-motie-de-vos'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'TPO',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
            'title' => 'Wiersma krijgt tegenwind in Brussel bij pleidooi voor nieuwe mestuitzondering',
            'description' => 'Minister Wiersma krijgt in Brussel flinke tegenwind bij zijn pleidooi voor een nieuwe Nederlandse mestuitzondering. De Europese Commissie lijkt niet gevoelig voor de argumenten van de Nederlandse bewindsman.',
            'url' => 'https://tpo.nl/2025/04/11/wiersma-krijgt-tegenwind-in-brussel-bij-pleidooi-voor-nieuwe-mestuitzondering/'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'Wynia\'s Week',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-11 hours')), // 11 uur geleden
            'title' => 'Brussel besteedt miljarden euro\'s aan duistere lobby\'s voor klimaat- en asielbeleid',
            'description' => 'Volgens een onderzoek van de Europese Rekenkamer besteedt de EU miljarden euro\'s aan lobbyclubs die het klimaat- en migratiebeleid beïnvloeden, zonder voldoende transparantie en controle.',
            'url' => 'https://www.wyniasweek.nl/brussel-besteedt-miljarden-euros-aan-duistere-lobbys-voor-klimaat-en-asielbeleid-dankzij-de-europese-rekenkamer-weten-we-het-nu-zeker/'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'De Dagelijkse Standaard',
            'bias' => 'Conservatief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-13 hours')), // 13 uur geleden
            'title' => 'BNNVARA-gezicht Emma Wortelboer pronkt met salaris: "Gênant"',
            'description' => 'BNNVARA-presentatrice Emma Wortelboer heeft ophef veroorzaakt door in een interview openlijk te pronken met haar salaris, wat veel kritiek heeft opgeleverd op sociale media.',
            'url' => 'https://www.dagelijksestandaard.nl/media/bnnvara-gek-emma-wortelboer-pronkt-met-salaris-gnant'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'FVD',
            'bias' => 'Conservatief', 
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-18 hours')), // 18 uur geleden
            'title' => 'Extinction Rebellion dreigt met stinkbommen, FVD stelt Kamervragen',
            'description' => 'Forum voor Democratie heeft Kamervragen gesteld over de dreiging van Extinction Rebellion om stinkbommen te gebruiken bij demonstraties.',
            'url' => 'https://fvd.nl/nieuws/extinction-rebellion-dreigt-met-stinkbommen-fvd-stelt-kamervragen'
        ]
    ]
];

// Leeg eerst de tabel (voor herhaalde uitvoering)
try {
    $db->directQuery("DELETE FROM news_articles");
    echo "Bestaande artikelen verwijderd.\n";
} catch (Exception $e) {
    echo "Fout bij verwijderen bestaande data: " . $e->getMessage() . "\n";
}

// Voeg alle artikelen toe
$insertSQL = "INSERT INTO news_articles (title, description, url, source, bias, orientation, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)";

$insertCount = 0;
foreach ($latest_news_by_orientation as $orientation => $articles) {
    foreach ($articles as $article) {
        try {
            $db->query($insertSQL);
            $db->bind(1, $article['title']);
            $db->bind(2, $article['description']);
            $db->bind(3, $article['url']);
            $db->bind(4, $article['source']);
            $db->bind(5, $article['bias']);
            $db->bind(6, $article['orientation']);
            $db->bind(7, $article['publishedAt']);
            $db->execute();
            $insertCount++;
        } catch (Exception $e) {
            echo "Fout bij invoegen artikel: " . $e->getMessage() . "\n";
            echo "Artikel: " . $article['title'] . "\n";
        }
    }
}

echo "Succesvol $insertCount artikelen toegevoegd aan de database.\n";
echo "Script voltooid!\n";
?> 