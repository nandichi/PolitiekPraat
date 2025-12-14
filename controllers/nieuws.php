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

// Paginering instellingen
$articlesPerPage = 9;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Haal nieuws op uit database via NewsModel met paginering
$latest_news = $newsModel->getFilteredNewsPaginated($filter, $currentPage, $articlesPerPage);

// Haal totaal aantal artikelen op voor paginering
$totalArticles = $newsModel->getTotalCount($filter);
$totalPages = ceil($totalArticles / $articlesPerPage);

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
    
    // Set fallback paginering data
    $totalArticles = count($latest_news);
    $totalPages = 1;
}

// Haal statistieken op via NewsModel
$stats = $newsModel->getNewsStats();

// Log aantallen artikelen
error_log("Aantal totale artikelen: " . $stats['total_articles']);
error_log("Aantal progressieve artikelen: " . $stats['progressive_count']);
error_log("Aantal conservatieve artikelen: " . $stats['conservative_count']);
error_log("Huidige pagina: $currentPage van $totalPages");
error_log("Aantal artikelen op huidige pagina: " . count($latest_news));
error_log("TotalArticles voor paginering: " . $totalArticles);

// Leeg de cache als er een parameter is meegegeven (behouden voor compatibiliteit)
if (isset($_GET['clear_cache']) && $_GET['clear_cache'] === '1') {
    // Redirect naar dezelfde pagina zonder de cache parameter
    $redirectUrl = strtok($_SERVER["REQUEST_URI"], '?');
    $params = [];
    if (!empty($filter) && $filter !== 'alle') {
        $params[] = "filter=$filter";
    }
    if ($currentPage > 1) {
        $params[] = "page=$currentPage";
    }
    
    $redirectUrl .= !empty($params) ? '?' . implode('&', $params) : '';
    header("Location: $redirectUrl");
    exit;
}

// Voeg page title toe
$page_title = "Nieuws";

// Laad de header template
require_once 'views/templates/header.php';

// Laad de nieuwspagina view
require_once 'views/nieuws/index.php';
?> 
