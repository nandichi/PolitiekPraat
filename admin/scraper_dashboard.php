<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../models/NewsModel.php';
require_once '../includes/NewsScraper.php';

// Eenvoudige authenticatie check (dit zou robuuster moeten in productie)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    // Voor development - in productie moet dit beveiligd worden
    $_SESSION['admin_logged_in'] = true;
}

// Initialiseer componenten
$db = new Database();
$newsModel = new NewsModel($db);
$scraper = new NewsScraper($newsModel);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Schakel alle output buffering in en onderdruk errors voor clean JSON
    ob_start();
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'run_scraper':
                // Onderdruk alle PHP output om JSON clean te houden
                ob_clean();
                $result = $scraper->scrapeAllSources();
                
                // Zorg dat er geen ongewenste output is
                if (ob_get_length()) {
                    ob_clean();
                }
                
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
                
            case 'cleanup_old':
                ob_clean();
                $days = intval($_POST['days'] ?? 30);
                $deleted = $scraper->cleanupOldArticles($days);
                
                if (ob_get_length()) {
                    ob_clean();
                }
                
                header('Content-Type: application/json');
                echo json_encode(['deleted_count' => $deleted]);
                exit;
        }
    } catch (Exception $e) {
        // Clean alle output buffer en stuur error response
        if (ob_get_length()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage(),
            'scraped_count' => 0,
            'errors' => [$e->getMessage()]
        ]);
        exit;
    }
    
    ob_end_clean();
}

// Haal statistieken op
$stats = $newsModel->getNewsStats();
$scrapingStats = $scraper->getScrapingStats();

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

.loading-spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #10b981;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.source-card {
    transition: all 0.3s ease;
}

.source-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/30 to-teal-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">News Scraper Dashboard</h1>
                    <p class="text-emerald-100 text-lg">Monitor en beheer automatische nieuws scraping</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="news-scraper-beheer.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center">
                        Scraper Beheer
                    </a>
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white text-emerald-600 px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-emerald-50 transition-all duration-300 font-semibold text-center">
                        Admin Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10 pb-12">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Artikelen</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['total_articles'] ?></p>
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
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['progressive_count'] ?></p>
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
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['conservative_count'] ?></p>
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
                        <p class="text-gray-600 text-sm font-medium">Bronnen</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $stats['source_count'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scraper Controls -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Scraper Controls</h2>
                <p class="text-gray-600 text-sm">Voer scraping acties handmatig uit</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <button onclick="runScraper()" 
                            class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Scraper Nu Uitvoeren
                    </button>
                    <button onclick="cleanupOld()" 
                            class="bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Oude Artikelen Opschonen (30+ dagen)
                    </button>
                </div>
                
                <!-- Status Display -->
                <div id="status" class="hidden p-4 rounded-xl border" style="display: none;">
                    <div id="status-content" class="flex items-center"></div>
                </div>
            </div>
        </div>
        
        <!-- Scraping Status per Bron -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Scraping Status per Bron</h2>
                <p class="text-gray-600 text-sm">Overzicht van alle nieuws bronnen</p>
            </div>
            
            <div class="p-6">
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Bron</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Politieke Oriëntatie</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Laatst Gescraped</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Laatste Run</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">RSS Feed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scrapingStats as $source => $sourceStats): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="font-semibold text-gray-800">
                                            <?= htmlspecialchars($source) ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium <?= $sourceStats['orientation'] === 'links' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $sourceStats['orientation'] === 'links' ? 'Progressief' : 'Conservatief' ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600">
                                        <?= $sourceStats['last_scraped'] ?>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                            <?= $sourceStats['last_articles_found'] ?> artikelen
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="<?= htmlspecialchars($sourceStats['rss_url']) ?>" 
                                           target="_blank" 
                                           class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                                            RSS Feed →
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    <?php foreach ($scrapingStats as $source => $sourceStats): ?>
                        <div class="source-card bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($source) ?></h3>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $sourceStats['orientation'] === 'links' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $sourceStats['orientation'] === 'links' ? 'Progressief' : 'Conservatief' ?>
                                    </span>
                                </div>
                                
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center justify-between">
                                        <span>Laatst gescraped:</span>
                                        <span class="font-medium"><?= $sourceStats['last_scraped'] ?></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Laatste run:</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                            <?= $sourceStats['last_articles_found'] ?> artikelen
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="pt-2 border-t border-gray-100">
                                    <a href="<?= htmlspecialchars($sourceStats['rss_url']) ?>" 
                                       target="_blank" 
                                       class="text-emerald-600 hover:text-emerald-800 text-sm font-medium flex items-center">
                                        RSS Feed
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Scraper Dashboard Functionaliteit
function runScraper() {
    showStatus('running', 'Scraper wordt uitgevoerd...');
    
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=run_scraper'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showStatus('error', `Fout: ${data.message}`);
        } else {
            showStatus('success', `Scraping voltooid! ${data.scraped_count} nieuwe artikelen gevonden.`);
            // Refresh pagina na 2 seconden voor updated stats
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        showStatus('error', `Network fout: ${error.message}`);
    });
}

function cleanupOld() {
    if (!confirm('Weet je zeker dat je artikelen ouder dan 30 dagen wilt verwijderen?')) {
        return;
    }
    
    showStatus('running', 'Oude artikelen worden verwijderd...');
    
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=cleanup_old&days=30'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showStatus('error', `Fout: ${data.message}`);
        } else {
            showStatus('success', `Cleanup voltooid! ${data.deleted_count} artikelen verwijderd.`);
            // Refresh pagina na 2 seconden voor updated stats
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        showStatus('error', `Network fout: ${error.message}`);
    });
}

function showStatus(type, message) {
    const statusDiv = document.getElementById('status');
    const statusContent = document.getElementById('status-content');
    
    let icon = '';
    let bgClass = '';
    let textClass = '';
    
    switch (type) {
        case 'running':
            icon = '<div class="loading-spinner mr-3"></div>';
            bgClass = 'bg-blue-50 border-blue-200';
            textClass = 'text-blue-800';
            break;
        case 'success':
            icon = '<svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            bgClass = 'bg-green-50 border-green-200';
            textClass = 'text-green-800';
            break;
        case 'error':
            icon = '<svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            bgClass = 'bg-red-50 border-red-200';
            textClass = 'text-red-800';
            break;
    }
    
    statusDiv.className = `p-4 rounded-xl border ${bgClass}`;
    statusContent.innerHTML = `${icon}<span class="font-medium ${textClass}">${message}</span>`;
    statusDiv.style.display = 'block';
    
    // Auto hide success/error messages na 5 seconden
    if (type !== 'running') {
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 5000);
    }
}

// Auto-refresh statistieken elke 30 seconden (alleen als pagina zichtbaar is)
setInterval(() => {
    if (!document.hidden) {
        // Refresh alleen de statistieken zonder volledige pagina reload
        console.log('Auto-refresh stats (kan geïmplementeerd worden via AJAX)');
    }
}, 30000);
</script>

<?php require_once '../views/templates/footer.php'; ?> 