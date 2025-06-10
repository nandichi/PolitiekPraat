<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'models/NewsModel.php';

// Initialiseer database, NewsAPI en NewsModel
$db = new Database();
$newsAPI = new NewsAPI();
$newsModel = new NewsModel($db);

// Definieer de nieuwsbronnen per politieke oriëntatie
$news_sources = [
    'links' => [
        ['name' => 'De Volkskrant'],
        ['name' => 'NRC'],
        ['name' => 'Trouw'],
        ['name' => 'Socialisme.nu']
    ],
    'rechts' => [
        ['name' => 'NU.nl'],
        ['name' => 'Telegraaf'],
        ['name' => 'AD'],
        ['name' => 'FVD'],
        ['name' => 'De Dagelijkse Standaard'],
        ['name' => 'Nieuw Rechts']
    ]
];

// Haal de geselecteerde filter op (default is 'alle')
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'alle';

// Haal nieuws op uit database via NewsModel
$latest_news = $newsModel->getFilteredNews($filter);

// Als er geen data in database is, gebruik fallback hardcoded data
if (empty($latest_news)) {
    error_log("Geen nieuws gevonden in database, gebruik fallback data");
    
    // Fallback hardcoded data (verkort voor emergency gebruik)
    $fallback_news = [
        [
            'orientation' => 'links',
            'source' => 'De Volkskrant',
            'bias' => 'Progressief', 
            'publishedAt' => date('Y-m-d H:i:s'),
            'title' => 'Nieuwsdata wordt geladen uit database',
            'description' => 'De nieuwsartikelen worden nu opgehaald uit de database in plaats van hardcoded data.',
            'url' => '#'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'Telegraaf',
            'bias' => 'Conservatief',
            'publishedAt' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'title' => 'Database migratie voltooid',
            'description' => 'Het nieuwssysteem is succesvol gemigreerd naar een database-gebaseerde architectuur.',
            'url' => '#'
        ]
    ];
    
    // Filter fallback data indien nodig
switch($filter) {
    case 'progressief':
            $latest_news = array_filter($fallback_news, function($article) {
                return $article['orientation'] === 'links';
            });
        break;
    case 'conservatief':
            $latest_news = array_filter($fallback_news, function($article) {
                return $article['orientation'] === 'rechts';
            });
        break;
        default:
            $latest_news = $fallback_news;
    }
}

// Haal statistieken op via NewsModel
$stats = $newsModel->getNewsStats();

// Log aantallen artikelen
error_log("Aantal totale artikelen: " . $stats['total_articles']);
error_log("Aantal progressieve artikelen: " . $stats['progressive_count']);
error_log("Aantal conservatieve artikelen: " . $stats['conservative_count']);

// Leeg de cache als er een parameter is meegegeven (behouden voor compatibiliteit)
if (isset($_GET['clear_cache']) && $_GET['clear_cache'] === '1') {
    // Redirect naar dezelfde pagina zonder de cache parameter
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . (!empty($filter) && $filter !== 'alle' ? "?filter=$filter" : ""));
    exit;
}

// Voeg page title toe
$page_title = "Nieuws";

// Laad de header template
require_once 'views/templates/header.php';

// Laad de nieuwspagina view
require_once 'views/nieuws/index.php';
?> 
