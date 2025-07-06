<?php
// Automatische news scraper cron job script
// Dit script kan worden uitgevoerd door een cron job om automatisch nieuws te scrapen

// Zet working directory naar project root voor correcte includes
$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);
chdir($projectRoot);

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';
require_once 'models/NewsModel.php';
require_once 'includes/NewsScraper.php';
require_once 'includes/mail_helper.php';

// Logging functie
function logMessage($message) {
    $logFile = 'logs/auto_news_scraper.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    echo $logEntry;
}

try {
    logMessage("Starting auto news scraper cron job");
    
    // Controleer of logs directory bestaat
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    // Laad automatische instellingen
    $autoSettingsFile = 'cache/auto_scraper_settings.json';
    if (!file_exists($autoSettingsFile)) {
        logMessage("Auto scraper settings file not found. Exiting.");
        exit;
    }
    
    $autoSettings = json_decode(file_get_contents($autoSettingsFile), true);
    
    // Controleer of automatische scraping is ingeschakeld
    if (!($autoSettings['enabled'] ?? false)) {
        logMessage("Auto scraping is disabled. Exiting.");
        exit;
    }
    
    // Controleer of het tijd is voor een nieuwe run
    $lastRun = $autoSettings['last_run'] ?? 0;
    $intervalSeconds = ($autoSettings['interval_minutes'] ?? 30) * 60;
    $now = time();
    
    if (($now - $lastRun) < $intervalSeconds) {
        $nextRun = $lastRun + $intervalSeconds;
        $remainingTime = $nextRun - $now;
        logMessage("Not time yet for next run. Next run in " . round($remainingTime / 60, 1) . " minutes");
        exit;
    }
    
    // Database verbinding en scraper initialiseren
    $db = new Database();
    $newsModel = new NewsModel($db);
    $scraper = new NewsScraper($newsModel);
    
    // Haal huidige statistieken op
    $statsBefore = $newsModel->getNewsStats();
    logMessage("Current database stats: " . ($statsBefore['total_articles'] ?? 0) . " total articles");
    
    // Voer scraping uit
    logMessage("Starting news scraping...");
    $result = $scraper->scrapeAllSources();
    
    $scrapedCount = $result['scraped_count'] ?? 0;
    $errors = $result['errors'] ?? [];
    
    logMessage("Scraping completed - Found $scrapedCount new articles");
    
    if (!empty($errors)) {
        logMessage("Scraping errors encountered:");
        foreach ($errors as $error) {
            logMessage("  - $error");
        }
    }
    
    // Log per-source statistics
    $scrapingStats = $scraper->getScrapingStats();
    logMessage("Source statistics:");
    foreach ($scrapingStats as $source => $sourceStats) {
        logMessage("  $source: " . ($sourceStats['last_articles_found'] ?? 0) . " articles, last scraped: " . ($sourceStats['last_scraped'] ?? 'never'));
    }
    
    // Automatische cleanup als ingeschakeld
    if ($autoSettings['auto_cleanup'] ?? false) {
        $cleanupDays = $autoSettings['cleanup_days'] ?? 30;
        
        // Voer cleanup uit elke 24 uur
        $lastCleanup = $autoSettings['last_cleanup'] ?? 0;
        if (($now - $lastCleanup) >= (24 * 3600)) {
            logMessage("Starting automatic cleanup of articles older than $cleanupDays days");
            $deletedCount = $scraper->cleanupOldArticles($cleanupDays);
            logMessage("Cleanup completed - Deleted $deletedCount old articles");
            
            // Update last cleanup tijd
            $autoSettings['last_cleanup'] = $now;
        }
    }
    
    // Update last run tijd
    $autoSettings['last_run'] = $now;
    file_put_contents($autoSettingsFile, json_encode($autoSettings));
    
    // Haal nieuwe statistieken op
    $statsAfter = $newsModel->getNewsStats();
    logMessage("Updated database stats: " . ($statsAfter['total_articles'] ?? 0) . " total articles");
    
    // Performance statistieken
    $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
    $executionTime = round(microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true)), 2);
    
    logMessage("Performance: {$memoryUsage}MB memory, {$executionTime}s execution time");
    logMessage("Auto news scraper completed successfully");
    
    // Email notificatie versturen
    $emailSummary = "News scraper succesvol uitgevoerd! {$scrapedCount} nieuwe artikelen gevonden.";
    $emailDetails = "Scraping Resultaten:\n";
    $emailDetails .= "- Nieuwe artikelen: {$scrapedCount}\n";
    $emailDetails .= "- Fouten: " . count($errors) . "\n";
    $emailDetails .= "- Memory gebruik: {$memoryUsage}MB\n";
    $emailDetails .= "- Uitvoeringstijd: {$executionTime}s\n";
    $emailDetails .= "- Database artikelen totaal: " . ($statsAfter['total_articles'] ?? 0) . "\n\n";
    
    if (!empty($errors)) {
        $emailDetails .= "Fouten tijdens scraping:\n";
        foreach ($errors as $error) {
            $emailDetails .= "- $error\n";
        }
        $emailDetails .= "\n";
    }
    
    // Voeg bron statistieken toe
    $emailDetails .= "Bron Statistieken:\n";
    foreach ($scrapingStats as $source => $sourceStats) {
        $emailDetails .= "- $source: " . ($sourceStats['last_articles_found'] ?? 0) . " artikelen\n";
    }
    
    // Bepaal email status
    $emailStatus = 'success';
    if (!empty($errors)) {
        $emailStatus = count($errors) > $scrapedCount ? 'error' : 'warning';
    }
    
    // Verstuur email
    $logFile = 'logs/auto_news_scraper.log';
    $emailSent = sendCronJobEmail(
        'Auto News Scraper',
        $emailStatus,
        $emailSummary,
        $emailDetails,
        $logFile
    );
    
    if ($emailSent) {
        logMessage("Email notificatie verstuurd naar admin");
    } else {
        logMessage("Waarschuwing: Email notificatie kon niet worden verstuurd");
    }
    
} catch (Exception $e) {
    logMessage("Error in auto news scraper: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    
    // Log naar main error log ook
    error_log("Auto News Scraper Error: " . $e->getMessage());
    
    // Verstuur error email
    $errorSummary = "Auto News Scraper FOUT! Het script is gestopt met een kritieke fout.";
    $errorDetails = "Fout: " . $e->getMessage() . "\n\n";
    $errorDetails .= "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    $errorDetails .= "Tijd: " . date('Y-m-d H:i:s') . "\n";
    $errorDetails .= "Server: " . gethostname() . "\n";
    
    $logFile = 'logs/auto_news_scraper.log';
    $emailSent = sendCronJobEmail(
        'Auto News Scraper - ERROR',
        'error',
        $errorSummary,
        $errorDetails,
        $logFile
    );
    
    if ($emailSent) {
        logMessage("Error email notificatie verstuurd naar admin");
    } else {
        logMessage("Kritiek: Error email kon niet worden verstuurd!");
    }
    
    exit(1);
}
?> 