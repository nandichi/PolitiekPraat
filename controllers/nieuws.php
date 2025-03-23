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
        ['name' => 'NU.nl'],
        ['name' => 'Telegraaf'],
        ['name' => 'AD']
    ]
];

// Log start van het ophalen van nieuws
error_log("Start ophalen nieuws van verschillende bronnen");

// Haal het laatste nieuws op van alle bronnen
$latest_news_by_orientation = [
    'links' => [],
    'rechts' => []
];

// Bepaal of de cache vernieuwd moet worden
$cache_lifetime = 15 * 60; // 15 minuten
$cache_file = 'cache/news.json'; // Aangepaste cache bestandsnaam
$use_cache = false;

// Controleer of de cache directory bestaat, zo niet, maak hem aan
if (!file_exists('cache')) {
    mkdir('cache', 0777, true);
}

// Controleer of de cache geldig is
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_lifetime)) {
    $cache_data = json_decode(file_get_contents($cache_file), true);
    if ($cache_data && isset($cache_data['latest_news_by_orientation'])) {
        $latest_news_by_orientation = $cache_data['latest_news_by_orientation'];
        $use_cache = true;
        error_log("Nieuws geladen uit cache");
    }
}

// Als de cache niet geldig is, haal dan vers nieuws op
if (!$use_cache) {
    // Verzamel eerst alle artikelen per oriëntatie
    foreach ($news_sources as $orientation => $sources) {
        foreach ($sources as $source) {
            error_log("Ophalen nieuws van: " . $source['name']);
            $news = $newsAPI->getLatestPoliticalNewsBySource($source['name']);
            error_log("Aantal artikelen opgehaald van " . $source['name'] . ": " . count($news));
            
            foreach ($news as $article) {
                // Alleen politieke artikelen toevoegen
                if (isset($article['isPolitical']) && $article['isPolitical'] === true) {
                    // Extra controle op sport termen in de titel
                    $sport_terms = ['sport', 'voetbal', 'tennis', 'hockey', 'schaatsen', 'wielrennen', 
                                  'olympisch', 'formule 1', 'f1', 'eredivisie', 'champions league'];
                    $is_sport = false;
                    
                    foreach ($sport_terms as $term) {
                        if (stripos($article['title'], $term) !== false || 
                            (isset($article['description']) && stripos($article['description'], $term) !== false)) {
                            $is_sport = true;
                            break;
                        }
                    }
                    
                    if (!$is_sport) {
                        $article['orientation'] = $orientation;
                        $article['bias'] = ($orientation === 'links') ? 'Progressief' : 'Conservatief';
                        $latest_news_by_orientation[$orientation][] = $article;
                    } else {
                        error_log("Sport artikel weggelaten: " . $article['title']);
                    }
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

    // Verwijder duplicaten op basis van titel
    foreach ($latest_news_by_orientation as $orientation => &$articles) {
        $unique_articles = [];
        $seen_titles = [];
        
        foreach ($articles as $article) {
            $normalized_title = strtolower(trim($article['title']));
            if (!in_array($normalized_title, $seen_titles)) {
                $seen_titles[] = $normalized_title;
                $unique_articles[] = $article;
            }
        }
        
        $articles = $unique_articles;
    }

    // Sla de resultaten op in de cache
    file_put_contents($cache_file, json_encode([
        'latest_news_by_orientation' => $latest_news_by_orientation,
        'timestamp' => time()
    ]));
    
    error_log("Nieuws opgeslagen in cache");
}

// Log aantallen artikelen
error_log("Aantal progressieve artikelen: " . count($latest_news_by_orientation['links']));
error_log("Aantal conservatieve artikelen: " . count($latest_news_by_orientation['rechts']));

// Bepaal het minimum aantal artikelen tussen links en rechts
$min_count = min(count($latest_news_by_orientation['links']), count($latest_news_by_orientation['rechts']));
$display_count = max(6, $min_count); // Minimaal 6 artikelen, of meer als beide oriëntaties genoeg hebben

// Filter de artikelen op basis van de geselecteerde optie
switch($filter) {
    case 'progressief':
        $latest_news = array_slice($latest_news_by_orientation['links'], 0, $display_count * 2);
        break;
    case 'conservatief':
        $latest_news = array_slice($latest_news_by_orientation['rechts'], 0, $display_count * 2);
        break;
    default: // 'alle'
        // Maak een gebalanceerde samenstelling van links en rechts nieuws
        $latest_news = [];
        for ($i = 0; $i < $min_count; $i++) {
            if (isset($latest_news_by_orientation['links'][$i])) {
                $latest_news[] = $latest_news_by_orientation['links'][$i];
            }
            if (isset($latest_news_by_orientation['rechts'][$i])) {
                $latest_news[] = $latest_news_by_orientation['rechts'][$i];
            }
        }
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
    'newest_article' => !empty($latest_news) ? reset($latest_news)['publishedAt'] : date('Y-m-d H:i:s'),
    'oldest_article' => !empty($latest_news) ? end($latest_news)['publishedAt'] : date('Y-m-d H:i:s', strtotime('-1 hour')),
    'source_count' => count(array_unique(array_column($latest_news, 'source')))
];

// Leeg de cache als er een parameter is meegegeven
if (isset($_GET['clear_cache']) && $_GET['clear_cache'] === '1') {
    if (file_exists($cache_file)) {
        unlink($cache_file);
        error_log("Cache geleegd op verzoek van gebruiker");
    }
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
