<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';

// Initialiseer database en NewsAPI
$db = new Database();
$newsAPI = new NewsAPI();

// Haal de geselecteerde filter op (default is 'alle')
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'alle';

// Definieer de nieuwsbronnen per politieke oriëntatie
$news_sources = [
    'links' => [
        ['name' => 'De Volkskrant'],
        ['name' => 'NRC'],
        ['name' => 'Trouw']
    ],
    'rechts' => [
        ['name' => 'WNL'],
        ['name' => 'Telegraaf'],
        ['name' => 'AD']
    ]
];

// Haal het laatste nieuws op van alle bronnen
$latest_news_by_orientation = [
    'links' => [],
    'rechts' => []
];

// Verzamel eerst alle artikelen per oriëntatie
foreach ($news_sources as $orientation => $sources) {
    foreach ($sources as $source) {
        $news = $newsAPI->getLatestPoliticalNewsBySource($source['name']);
        foreach ($news as $article) {
            if ($article['isPolitical']) {
                $article['orientation'] = $orientation;
                $article['bias'] = ($orientation === 'links') ? 'Progressief' : 'Conservatief';
                $latest_news_by_orientation[$orientation][] = $article;
            }
        }
    }
}

// Sorteer beide arrays op publicatiedatum
foreach ($latest_news_by_orientation as &$articles) {
    usort($articles, function($a, $b) {
        return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
    });
}

// Bepaal het minimum aantal artikelen tussen links en rechts
$min_count = min(count($latest_news_by_orientation['links']), count($latest_news_by_orientation['rechts']));

// Filter de artikelen op basis van de geselecteerde optie
switch($filter) {
    case 'progressief':
        $latest_news = array_slice($latest_news_by_orientation['links'], 0, $min_count * 2);
        break;
    case 'conservatief':
        $latest_news = array_slice($latest_news_by_orientation['rechts'], 0, $min_count * 2);
        break;
    default: // 'alle'
        $latest_news = array_merge(
            array_slice($latest_news_by_orientation['links'], 0, $min_count),
            array_slice($latest_news_by_orientation['rechts'], 0, $min_count)
        );
}

// Sorteer het gecombineerde nieuws op publicatiedatum
usort($latest_news, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// Voeg wat interessante statistieken toe
$stats = [
    'total_articles' => count($latest_news),
    'progressive_count' => count($latest_news_by_orientation['links']),
    'conservative_count' => count($latest_news_by_orientation['rechts']),
    'newest_article' => reset($latest_news)['publishedAt'],
    'oldest_article' => end($latest_news)['publishedAt']
];

// Laad de header template
require_once 'views/templates/header.php';

// Laad de nieuwspagina view
require_once 'views/nieuws/index.php';
?> 
