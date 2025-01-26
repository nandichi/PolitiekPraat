<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';

// Initialiseer database en NewsAPI
$db = new Database();
$newsAPI = new NewsAPI();

// Definieer de nieuwsbronnen per politieke oriëntatie
$news_sources = [
    'links' => [
        ['name' => 'Trouw', 'url' => 'https://www.trouw.nl/rss.xml'],
        ['name' => 'NRC', 'url' => 'https://www.nrc.nl/rss/'],
        ['name' => 'De Volkskrant', 'url' => 'https://www.volkskrant.nl/voorpagina/rss.xml']
    ],
    'rechts' => [
        ['name' => 'WNL', 'url' => 'https://wnl.tv/feed/'],
        ['name' => 'Telegraaf', 'url' => 'https://www.telegraaf.nl/rss'],
        ['name' => 'AD', 'url' => 'https://www.ad.nl/rss.xml']
    ]
];

// Haal het laatste nieuws op van alle bronnen
$latest_news = [];

foreach ($news_sources as $orientation => $sources) {
    foreach ($sources as $source) {
        $news = $newsAPI->getLatestPoliticalNewsBySource($source['name']);
        foreach ($news as $article) {
            $article['orientation'] = $orientation;
            $article['bias'] = ($orientation === 'links') ? 'Progressief' : 'Conservatief';
            $latest_news[] = $article;
        }
    }
}

// Sorteer het nieuws op publicatiedatum (nieuwste eerst)
usort($latest_news, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// Laad de header template
require_once 'views/templates/header.php';

// Laad de nieuwspagina view
require_once 'views/nieuws/index.php';
?> 
