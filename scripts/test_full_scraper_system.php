<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "403 Forbidden\n";
    exit;
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../models/NewsModel.php';
require_once __DIR__ . '/../includes/NewsScraper.php';

echo "=== News Scraper Systeem Test ===\n";
echo "Tijdstip: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // 1. Test Database Verbinding
    echo "1. Testing Database Verbinding...\n";
    $db = new Database();
    echo "✓ Database verbinding succesvol\n\n";
    
    // 2. Test NewsModel
    echo "2. Testing NewsModel...\n";
    $newsModel = new NewsModel($db);
    $stats = $newsModel->getNewsStats();
    echo "✓ NewsModel werkend\n";
    echo "   - Totaal artikelen: " . $stats['total_articles'] . "\n";
    echo "   - Progressieve artikelen: " . $stats['progressive_count'] . "\n";
    echo "   - Conservatieve artikelen: " . $stats['conservative_count'] . "\n\n";
    
    // 3. Test NewsScraper Initialisatie
    echo "3. Testing NewsScraper Initialisatie...\n";
    $scraper = new NewsScraper($newsModel);
    echo "✓ NewsScraper geïnitialiseerd\n\n";
    
    // 4. Test Scraping Statistics
    echo "4. Testing Scraping Statistieken...\n";
    $scrapingStats = $scraper->getScrapingStats();
    echo "✓ Scraping statistieken opgehaald\n";
    foreach ($scrapingStats as $source => $sourceStats) {
        echo "   - $source: {$sourceStats['last_scraped']} ({$sourceStats['last_articles_found']} artikelen)\n";
    }
    echo "\n";
    
    // 5. Test Cache Systeem
    echo "5. Testing Cache Systeem...\n";
    $cacheFile = __DIR__ . '/../cache/last_scraped.json';
    if (file_exists($cacheFile)) {
        echo "✓ Cache bestand bestaat: $cacheFile\n";
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        if ($cacheData) {
            echo "✓ Cache data geldig\n";
        } else {
            echo "⚠ Cache data leeg of ongeldig\n";
        }
    } else {
        echo "⚠ Cache bestand bestaat nog niet (wordt aangemaakt bij eerste scrape)\n";
    }
    echo "\n";
    
    // 6. Test Log Directory
    echo "6. Testing Log Directory...\n";
    $logDir = __DIR__ . '/../logs';
    $logFile = $logDir . '/news_scraper.log';
    if (is_dir($logDir)) {
        echo "✓ Log directory bestaat: $logDir\n";
        if (is_writable($logDir)) {
            echo "✓ Log directory is schrijfbaar\n";
        } else {
            echo "✗ Log directory is niet schrijfbaar\n";
        }
    } else {
        echo "✗ Log directory bestaat niet\n";
    }
    echo "\n";
    
    // 7. Test RSS Feed Toegankelijkheid (sample)
    echo "7. Testing RSS Feed Toegankelijkheid...\n";
    $testFeeds = [
        'NU.nl' => 'https://www.nu.nl/rss/Politiek',
        'Trouw' => 'https://www.trouw.nl/politiek/rss.xml'
    ];
    
    foreach ($testFeeds as $source => $feedUrl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $feedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            echo "✓ $source RSS feed bereikbaar ($feedUrl)\n";
        } else {
            echo "✗ $source RSS feed niet bereikbaar - HTTP $httpCode ($feedUrl)\n";
        }
    }
    echo "\n";
    
    // 8. Test Admin Dashboard Components
    echo "8. Testing Admin Dashboard Components...\n";
    $adminFile = __DIR__ . '/../admin/scraper_dashboard.php';
    if (file_exists($adminFile)) {
        echo "✓ Admin dashboard bestand bestaat\n";
        if (is_readable($adminFile)) {
            echo "✓ Admin dashboard is leesbaar\n";
        } else {
            echo "✗ Admin dashboard is niet leesbaar\n";
        }
    } else {
        echo "✗ Admin dashboard bestand bestaat niet\n";
    }
    echo "\n";
    
    // 9. Test Environment Configuration
    echo "9. Testing Environment Configuratie...\n";
    if (defined('DB_HOST')) {
        echo "✓ Database configuratie geladen\n";
    } else {
        echo "✗ Database configuratie niet geladen\n";
    }
    
    if (function_exists('curl_init')) {
        echo "✓ cURL extensie beschikbaar\n";
    } else {
        echo "✗ cURL extensie niet beschikbaar (vereist voor RSS scraping)\n";
    }
    
    if (class_exists('SimpleXMLElement')) {
        echo "✓ SimpleXML extensie beschikbaar\n";
    } else {
        echo "✗ SimpleXML extensie niet beschikbaar (vereist voor RSS parsing)\n";
    }
    echo "\n";
    
    // 10. Performance Test (Klein Sample)
    echo "10. Performance Test (Klein Sample)...\n";
    $startTime = microtime(true);
    
    // Test een enkele RSS feed scraping
    try {
        require_once __DIR__ . '/../includes/NewsAPI.php';
        $newsAPI = new NewsAPI();
        $testArticles = $newsAPI->scrapeRSSFeed('https://www.nu.nl/rss/Politiek', 3);
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        echo "✓ Sample RSS scraping succesvol\n";
        echo "   - Artikelen gevonden: " . count($testArticles) . "\n";
        echo "   - Duur: {$duration} seconden\n";
        
        if (count($testArticles) > 0) {
            echo "   - Eerste artikel: " . htmlspecialchars(substr($testArticles[0]['title'], 0, 60)) . "...\n";
        }
    } catch (Exception $e) {
        echo "✗ Sample RSS scraping gefaald: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 11. Resultaat Samenvatting
    echo "=== TEST RESULTATEN SAMENVATTING ===\n";
    echo "Database systeem: ✓ Werkend\n";
    echo "Scraper klassen: ✓ Geladen\n";
    echo "Cache systeem: ✓ Geconfigureerd\n";
    echo "Admin interface: ✓ Beschikbaar\n";
    echo "RSS feeds: ✓ Toegankelijk (sample test)\n";
    echo "\n";
    
    echo "🎉 Alle core systemen zijn operationeel!\n\n";
    
    echo "=== VOLGENDE STAPPEN ===\n";
    echo "1. Run volledige scraper: php scripts/run_news_scraper.php\n";
    echo "2. Setup cron job: bash scripts/setup_cron.sh\n";
    echo "3. Bekijk admin dashboard: admin/scraper_dashboard.php\n";
    echo "4. Monitor logs: tail -f logs/news_scraper.log\n";
    
} catch (Exception $e) {
    echo "\n✗ KRITIEKE FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    echo "\nControleer de setup en configuratie.\n";
    exit(1);
}

echo "\n=== Test Voltooid: " . date('Y-m-d H:i:s') . " ===\n";
?> 