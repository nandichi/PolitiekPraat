<?php
// Configuratie voor het beheren van automatische news scraping
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../models/NewsModel.php';
require_once '../includes/NewsScraper.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

// Database verbinding
$db = new Database();
$newsModel = new NewsModel($db);
$scraper = new NewsScraper($newsModel);

// Verwerk formulier submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'manual_scrape':
                // Handmatige scraping uitvoeren
                try {
                    $result = $scraper->scrapeAllSources();
                    $successMessage = "Scraping voltooid! {$result['scraped_count']} nieuwe artikelen gevonden.";
                    if (!empty($result['errors'])) {
                        $errorMessage = "Scraping voltooid met fouten: " . implode(', ', $result['errors']);
                    }
                } catch (Exception $e) {
                    $errorMessage = "Fout bij scraping: " . $e->getMessage();
                }
                break;
                
            case 'cleanup_old':
                // Oude artikelen opruimen
                $days = (int)$_POST['cleanup_days'] ?? 30;
                try {
                    $deletedCount = $scraper->cleanupOldArticles($days);
                    $successMessage = "Cleanup voltooid! {$deletedCount} oude artikelen verwijderd.";
                } catch (Exception $e) {
                    $errorMessage = "Fout bij cleanup: " . $e->getMessage();
                }
                break;
                
            case 'save_auto_settings':
                // Sla automatische scraping instellingen op
                $autoEnabled = isset($_POST['auto_enabled']) ? 1 : 0;
                $autoInterval = (int)$_POST['auto_interval'];
                $autoCleanup = isset($_POST['auto_cleanup']) ? 1 : 0;
                $cleanupDays = (int)$_POST['cleanup_days'];
                
                $autoSettings = [
                    'enabled' => $autoEnabled,
                    'interval_minutes' => $autoInterval,
                    'auto_cleanup' => $autoCleanup,
                    'cleanup_days' => $cleanupDays,
                    'last_run' => time(),
                    'last_manual_run' => time()
                ];
                
                file_put_contents('../cache/auto_scraper_settings.json', json_encode($autoSettings));
                $successMessage = "Automatische scraping instellingen opgeslagen";
                break;
                
            case 'test_sources':
                // Test alle RSS feeds
                $testResults = [];
                $scrapingStats = $scraper->getScrapingStats();
                
                foreach ($scrapingStats as $source => $sourceStats) {
                    try {
                        $rss = simplexml_load_string(file_get_contents($sourceStats['rss_url']));
                        if ($rss !== false) {
                            $itemCount = count($rss->channel->item);
                            $testResults[$source] = [
                                'status' => 'success',
                                'items' => $itemCount,
                                'message' => "RSS feed werkt - {$itemCount} items gevonden"
                            ];
                        } else {
                            $testResults[$source] = [
                                'status' => 'error',
                                'message' => 'RSS feed kan niet worden gelezen'
                            ];
                        }
                    } catch (Exception $e) {
                        $testResults[$source] = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                }
                
                $testMessage = "RSS feeds getest";
                break;
        }
    }
}

// Haal statistieken op
$stats = $newsModel->getNewsStats();
$scrapingStats = $scraper->getScrapingStats();

// Laad automatische instellingen
$autoSettingsFile = '../cache/auto_scraper_settings.json';
$autoSettings = [];
if (file_exists($autoSettingsFile)) {
    $autoSettings = json_decode(file_get_contents($autoSettingsFile), true);
}

// Haal logs op
$logFile = '../logs/news_scraper.log';
$recentLogs = [];
if (file_exists($logFile)) {
    $logs = array_slice(file($logFile), -20); // Laatste 20 regels
    $recentLogs = array_reverse($logs);
}

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #0f766e 0%, #059669 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.scraper-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.log-entry {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    line-height: 1.4;
}

.source-status {
    transition: all 0.3s ease;
}

.source-status:hover {
    transform: translateX(5px);
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/30 to-teal-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">News Scraper Beheer</h1>
                    <p class="text-emerald-100 text-lg">Automatische nieuws scraping en monitoring</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Terug naar Dashboard
                    </a>
                    <a href="scraper_dashboard.php" 
                       class="bg-white text-emerald-600 px-6 py-3 rounded-xl hover:bg-emerald-50 transition-all duration-300 font-semibold">
                        Scraper Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Succes/Error Messages -->
        <?php if (isset($successMessage)): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800"><?= $successMessage ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800"><?= $errorMessage ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($testMessage)): ?>
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800"><?= $testMessage ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Artikelen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total_articles'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Progressief</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['progressive_count'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Conservatief</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['conservative_count'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover scraper-pulse">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Nieuwsbronnen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['source_count'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Handmatige Scraper Controls -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Handmatige Scraper Controls</h2>
                    <p class="text-gray-600 text-sm">Voer scraping handmatig uit</p>
                </div>
                
                <div class="p-6 space-y-4">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="manual_scrape">
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Scraper Nu Uitvoeren
                        </button>
                    </form>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="cleanup_old">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cleanup Oude Artikelen</label>
                            <select name="cleanup_days" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="7">7 dagen</option>
                                <option value="14">14 dagen</option>
                                <option value="30" selected>30 dagen</option>
                                <option value="60">60 dagen</option>
                                <option value="90">90 dagen</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Oude Artikelen Opruimen
                        </button>
                    </form>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="test_sources">
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Test RSS Feeds
                        </button>
                    </form>
                </div>
            </div>

            <!-- Automatische Instellingen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Automatische Scraping</h2>
                    <p class="text-gray-600 text-sm">Configureer automatische scraping</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="save_auto_settings">
                        
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="auto_enabled" id="auto_enabled" 
                                   <?= ($autoSettings['enabled'] ?? false) ? 'checked' : '' ?>
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="auto_enabled" class="text-sm font-medium text-gray-700">
                                Automatische scraping inschakelen
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scraping Interval</label>
                            <select name="auto_interval" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="15" <?= ($autoSettings['interval_minutes'] ?? 30) == 15 ? 'selected' : '' ?>>15 minuten</option>
                                <option value="30" <?= ($autoSettings['interval_minutes'] ?? 30) == 30 ? 'selected' : '' ?>>30 minuten</option>
                                <option value="60" <?= ($autoSettings['interval_minutes'] ?? 30) == 60 ? 'selected' : '' ?>>1 uur</option>
                                <option value="120" <?= ($autoSettings['interval_minutes'] ?? 30) == 120 ? 'selected' : '' ?>>2 uur</option>
                                <option value="240" <?= ($autoSettings['interval_minutes'] ?? 30) == 240 ? 'selected' : '' ?>>4 uur</option>
                                <option value="480" <?= ($autoSettings['interval_minutes'] ?? 30) == 480 ? 'selected' : '' ?>>8 uur</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="auto_cleanup" id="auto_cleanup" 
                                   <?= ($autoSettings['auto_cleanup'] ?? false) ? 'checked' : '' ?>
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="auto_cleanup" class="text-sm font-medium text-gray-700">
                                Automatische cleanup inschakelen
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cleanup na (dagen)</label>
                            <select name="cleanup_days" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="7" <?= ($autoSettings['cleanup_days'] ?? 30) == 7 ? 'selected' : '' ?>>7 dagen</option>
                                <option value="14" <?= ($autoSettings['cleanup_days'] ?? 30) == 14 ? 'selected' : '' ?>>14 dagen</option>
                                <option value="30" <?= ($autoSettings['cleanup_days'] ?? 30) == 30 ? 'selected' : '' ?>>30 dagen</option>
                                <option value="60" <?= ($autoSettings['cleanup_days'] ?? 30) == 60 ? 'selected' : '' ?>>60 dagen</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Instellingen Opslaan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RSS Sources Status -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-violet-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">RSS Bronnen Status</h2>
                <p class="text-gray-600 text-sm">Overzicht van alle nieuwsbronnen</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($scrapingStats as $source => $sourceStats): ?>
                        <div class="source-status p-4 border border-gray-200 rounded-xl hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($source) ?></h3>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    <?= $sourceStats['orientation'] === 'links' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($sourceStats['orientation']) ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                <strong>Laatst gescraped:</strong> <?= $sourceStats['last_scraped'] ?>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                <strong>Laatste run:</strong> <?= $sourceStats['last_articles_found'] ?> artikelen
                            </div>
                            <div class="flex items-center justify-between">
                                <a href="<?= htmlspecialchars($sourceStats['rss_url']) ?>" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    RSS Feed →
                                </a>
                                <?php if (isset($testResults[$source])): ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        <?= $testResults[$source]['status'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $testResults[$source]['status'] === 'success' ? 'Test OK' : 'Test FOUT' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Recente Logs</h2>
                <p class="text-gray-600 text-sm">Laatste 20 log entries</p>
            </div>
            
            <div class="p-6">
                <?php if (empty($recentLogs)): ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-600">Geen logs gevonden</p>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-900 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="log-entry text-green-400 mb-1"><?= htmlspecialchars(trim($log)) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Cron Job Info -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Cron Job Setup</h2>
                <p class="text-gray-600 text-sm">Automatische uitvoering instellen</p>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Automatische News Scraping</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            Voeg deze regel toe aan je crontab voor automatische uitvoering:
                        </p>
                        <div class="bg-gray-800 text-green-400 p-3 rounded-lg font-mono text-sm overflow-x-auto">
                            */<?= $autoSettings['interval_minutes'] ?? 30 ?> * * * * /usr/bin/php <?= realpath(__DIR__ . '/../scripts/auto_news_scraper.php') ?> >> <?= realpath(__DIR__ . '/../logs/auto_news_scraper.log') ?> 2>&1
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Handmatige Test</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            Test het automatische script handmatig:
                        </p>
                        <div class="bg-gray-800 text-green-400 p-3 rounded-lg font-mono text-sm overflow-x-auto">
                            /usr/bin/php <?= realpath(__DIR__ . '/../scripts/auto_news_scraper.php') ?>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Setup Script</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            Gebruik het setup script voor automatische configuratie:
                        </p>
                        <div class="bg-gray-800 text-green-400 p-3 rounded-lg font-mono text-sm overflow-x-auto">
                            bash <?= realpath(__DIR__ . '/../scripts/setup_news_cron.sh') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// News Scraper Beheer Functionaliteit
document.addEventListener('DOMContentLoaded', function() {
    const autoEnabled = document.getElementById('auto_enabled');
    const autoCleanup = document.getElementById('auto_cleanup');
    
    // Toon waarschuwing bij automatische instellingen
    if (autoEnabled) {
        autoEnabled.addEventListener('change', function() {
            if (this.checked) {
                alert('Let op: Deze functionaliteit vereist een cron job voor automatische uitvoering. Zie de instructies onderaan de pagina.');
            }
        });
    }
    
    // Automatische log refresh
    setInterval(function() {
        // Alleen refreshen als de gebruiker niet actief is
        if (document.hidden) {
            return;
        }
        
        // Zou hier een AJAX call kunnen maken om logs te refreshen
        console.log('Auto-refresh logs (zou geïmplementeerd kunnen worden)');
    }, 30000); // 30 seconden
    
    // Smooth scroll voor lange logs
    const logContainer = document.querySelector('.log-entry');
    if (logContainer) {
        logContainer.scrollTop = logContainer.scrollHeight;
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 