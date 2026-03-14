<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../models/NewsModel.php';
require_once __DIR__ . '/../includes/NewsScraper.php';

// Log start
echo "=== News Scraper Start: " . date('Y-m-d H:i:s') . " ===\n";

try {
    // Initialiseer componenten
    $db = new Database();
    $newsModel = new NewsModel($db);
    $scraper = new NewsScraper($newsModel);
    
    // Check database connectie
    $stats = $newsModel->getNewsStats();
    echo "Database status: " . $stats['total_articles'] . " artikelen in database\n";
    
    // Voer scraping uit
    $result = $scraper->scrapeAllSources();
    
    // Log resultaten
    echo "\n=== Scraping Resultaten ===\n";
    echo "Nieuwe artikelen toegevoegd: " . $result['scraped_count'] . "\n";
    echo "Fouten: " . count($result['errors']) . "\n";
    
    if (!empty($result['errors'])) {
        echo "\nFouten tijdens scraping:\n";
        foreach ($result['errors'] as $error) {
            echo "- $error\n";
        }
    }
    
    // Toon scraping statistieken
    echo "\n=== Bron Statistieken ===\n";
    $scrapingStats = $scraper->getScrapingStats();
    foreach ($scrapingStats as $source => $sourceStats) {
        echo "$source:\n";
        echo "  Laatst gescraped: " . $sourceStats['last_scraped'] . "\n";
        echo "  Laatste run artikelen: " . $sourceStats['last_articles_found'] . "\n";
        echo "  RSS primair: " . ($sourceStats['rss_url'] ?? '-') . "\n";
        if (!empty($sourceStats['rss_urls']) && is_array($sourceStats['rss_urls'])) {
            echo "  RSS fallback(s): " . implode(' | ', $sourceStats['rss_urls']) . "\n";
        }
        echo "\n";
    }

    // Compacte health check: genoeg recente artikelen per perspectief?
    echo "=== Health Check (48h) ===\n";
    $health = $scraper->getHealthReport(48);
    echo "Links recent (48h): " . ($health['orientation']['links'] ?? 0) . "\n";
    echo "Rechts recent (48h): " . ($health['orientation']['rechts'] ?? 0) . "\n";

    $unhealthySources = [];
    foreach (($health['sources'] ?? []) as $sourceName => $sourceHealth) {
        if (empty($sourceHealth['is_healthy'])) {
            $unhealthySources[] = $sourceName;
        }
    }

    if (!empty($unhealthySources)) {
        echo "Waarschuwing: geen recente artikelen in 48h voor: " . implode(', ', $unhealthySources) . "\n";
    }
    
    // Cleanup oude artikelen (elke dag om 6:00 AM)
    $currentHour = date('H');
    if ($currentHour == '06') {
        echo "=== Cleanup Oude Artikelen ===\n";
        $deletedCount = $scraper->cleanupOldArticles(30);
        echo "Oude artikelen verwijderd: $deletedCount\n";
    }
    
    // Update laatste database stats
    $newStats = $newsModel->getNewsStats();
    echo "\n=== Database Status Na Scraping ===\n";
    echo "Totaal artikelen: " . $newStats['total_articles'] . "\n";
    echo "Progressieve artikelen: " . $newStats['progressive_count'] . "\n";
    echo "Conservatieve artikelen: " . $newStats['conservative_count'] . "\n";
    echo "Nieuwste artikel: " . $newStats['newest_article'] . "\n";
    
} catch (Exception $e) {
    echo "KRITIEKE FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    
    // Log naar error log
    error_log("News Scraper Fout: " . $e->getMessage());
    
    exit(1);
}

echo "\n=== News Scraper Voltooid: " . date('Y-m-d H:i:s') . " ===\n";
?> 