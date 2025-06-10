<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../models/NewsModel.php';
require_once __DIR__ . '/../includes/NewsScraper.php';

echo "=== Force Fresh Scrape ===\n";
echo "Dit script reset de scrape cache en forceert nieuwe artikelen op te halen\n\n";

try {
    // Verwijder cache bestand om fresh scrape te forceren
    $cacheFile = __DIR__ . '/../cache/last_scraped.json';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
        echo "✓ Cache bestand verwijderd\n";
    } else {
        echo "ℹ Cache bestand bestond al niet\n";
    }
    
    // Initialiseer componenten
    $db = new Database();
    $newsModel = new NewsModel($db);
    $scraper = new NewsScraper($newsModel);
    
    echo "✓ Scraper componenten geïnitialiseerd\n";
    
    // Huidige database status
    $stats = $newsModel->getNewsStats();
    echo "Database status voor scraping: " . $stats['total_articles'] . " artikelen\n\n";
    
    // Voer fresh scraping uit
    echo "Start verse scraping van alle bronnen...\n";
    $result = $scraper->scrapeAllSources();
    
    // Resultaten
    echo "\n=== Scraping Resultaten ===\n";
    echo "Nieuwe artikelen toegevoegd: " . $result['scraped_count'] . "\n";
    echo "Fouten: " . count($result['errors']) . "\n";
    
    if (!empty($result['errors'])) {
        echo "\nFouten:\n";
        foreach ($result['errors'] as $error) {
            echo "- $error\n";
        }
    }
    
    // Nieuwe database status
    $newStats = $newsModel->getNewsStats();
    echo "\nDatabase status na scraping: " . $newStats['total_articles'] . " artikelen\n";
    echo "Artikelen toegevoegd deze run: " . ($newStats['total_articles'] - $stats['total_articles']) . "\n";
    
} catch (Exception $e) {
    echo "FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Fresh Scrape Voltooid ===\n";
?> 