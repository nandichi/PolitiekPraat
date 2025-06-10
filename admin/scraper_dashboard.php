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

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Scraper Dashboard - PolitiekPraat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1d4ed8',
                        secondary: '#dc2626'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">News Scraper Dashboard</h1>
        
        <!-- Database Statistieken -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Database Statistieken</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded">
                    <div class="text-2xl font-bold text-blue-600"><?= $stats['total_articles'] ?></div>
                    <div class="text-sm text-gray-600">Totaal Artikelen</div>
                </div>
                <div class="bg-green-50 p-4 rounded">
                    <div class="text-2xl font-bold text-green-600"><?= $stats['progressive_count'] ?></div>
                    <div class="text-sm text-gray-600">Progressief</div>
                </div>
                <div class="bg-red-50 p-4 rounded">
                    <div class="text-2xl font-bold text-red-600"><?= $stats['conservative_count'] ?></div>
                    <div class="text-sm text-gray-600">Conservatief</div>
                </div>
                <div class="bg-purple-50 p-4 rounded">
                    <div class="text-2xl font-bold text-purple-600"><?= $stats['source_count'] ?></div>
                    <div class="text-sm text-gray-600">Bronnen</div>
                </div>
            </div>
        </div>
        
        <!-- Scraper Controls -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Scraper Controls</h2>
            <div class="flex space-x-4">
                <button onclick="runScraper()" 
                        class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    🚀 Scraper Nu Uitvoeren
                </button>
                <button onclick="cleanupOld()" 
                        class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">
                    🧹 Oude Artikelen Opschonen (30+ dagen)
                </button>
            </div>
            
            <!-- Status Display -->
            <div id="status" class="mt-4 p-3 rounded" style="display: none;">
                <div id="status-content"></div>
            </div>
        </div>
        
        <!-- Scraping Statistieken per Bron -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Scraping Status per Bron</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-3">Nieuwsbron</th>
                            <th class="text-left p-3">Oriëntatie</th>
                            <th class="text-left p-3">Laatst Gescraped</th>
                            <th class="text-left p-3">Laatste Run Artikelen</th>
                            <th class="text-left p-3">RSS Feed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scrapingStats as $source => $sourceStats): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium"><?= htmlspecialchars($source) ?></td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    <?= $sourceStats['orientation'] === 'links' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($sourceStats['orientation']) ?>
                                </span>
                            </td>
                            <td class="p-3 text-sm">
                                <?= $sourceStats['last_scraped'] === 'Nog nooit' 
                                    ? '<span class="text-red-600">Nog nooit</span>' 
                                    : htmlspecialchars($sourceStats['last_scraped']) ?>
                            </td>
                            <td class="p-3"><?= $sourceStats['last_articles_found'] ?></td>
                            <td class="p-3">
                                <a href="<?= htmlspecialchars($sourceStats['rss_url']) ?>" 
                                   target="_blank" 
                                   class="text-blue-600 hover:underline text-sm">
                                    RSS Feed
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Cron Job Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Automatische Scraping</h2>
            <div class="bg-gray-50 p-4 rounded">
                <h3 class="font-medium mb-2">Cron Job Setup:</h3>
                <p class="text-sm text-gray-600 mb-2">
                    Om de scraper elke 30 minuten automatisch te laten draaien, voeg deze regel toe aan je crontab:
                </p>
                <code class="block bg-gray-800 text-green-400 p-2 rounded text-sm font-mono">
                    */30 * * * * /usr/bin/php <?= realpath(__DIR__ . '/../scripts/run_news_scraper.php') ?> >> <?= realpath(__DIR__ . '/../logs/news_scraper.log') ?> 2>&1
                </code>
                <p class="text-sm text-gray-600 mt-2">
                    Of gebruik het setup script: <code>bash scripts/setup_cron.sh</code>
                </p>
            </div>
        </div>
    </div>

    <script>
        function showStatus(message, type = 'info') {
            const status = document.getElementById('status');
            const content = document.getElementById('status-content');
            
            // Reset classes
            status.className = 'mt-4 p-3 rounded';
            
            // Add type-specific classes
            switch(type) {
                case 'success':
                    status.className += ' bg-green-100 text-green-800 border border-green-200';
                    break;
                case 'error':
                    status.className += ' bg-red-100 text-red-800 border border-red-200';
                    break;
                case 'loading':
                    status.className += ' bg-blue-100 text-blue-800 border border-blue-200';
                    break;
                default:
                    status.className += ' bg-gray-100 text-gray-800 border border-gray-200';
            }
            
            content.innerHTML = message;
            status.style.display = 'block';
        }

        function runScraper() {
            showStatus('🔄 Scraper wordt uitgevoerd...', 'loading');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=run_scraper'
            })
            .then(response => {
                // Controleer of response OK is
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Probeer JSON te parsen
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Onverwachte response:', text);
                        throw new Error('Server retourneerde geen geldige JSON. Mogelijk zijn er PHP errors opgetreden.');
                    }
                });
            })
            .then(data => {
                // Check of er een error flag is
                if (data.error) {
                    showStatus(`❌ Scraper fout: ${data.message}`, 'error');
                    return;
                }
                
                const message = `
                    ✅ Scraping voltooid!<br>
                    📄 Nieuwe artikelen: ${data.scraped_count || 0}<br>
                    ⚠️ Fouten: ${(data.errors || []).length}<br>
                    🕒 Tijd: ${data.timestamp || 'Onbekend'}
                    ${(data.errors && data.errors.length > 0) ? '<br><br>Fouten:<br>' + data.errors.join('<br>') : ''}
                `;
                showStatus(message, (data.errors && data.errors.length > 0) ? 'error' : 'success');
                
                // Reload page after 3 seconds to show updated stats
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
            .catch(error => {
                console.error('Scraper error:', error);
                showStatus('❌ Fout bij uitvoeren scraper: ' + error.message, 'error');
            });
        }

        function cleanupOld() {
            if (!confirm('Weet je zeker dat je artikelen ouder dan 30 dagen wilt verwijderen?')) {
                return;
            }
            
            showStatus('🧹 Oude artikelen worden verwijderd...', 'loading');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=cleanup_old&days=30'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Onverwachte response:', text);
                        throw new Error('Server retourneerde geen geldige JSON bij cleanup.');
                    }
                });
            })
            .then(data => {
                if (data.error) {
                    showStatus(`❌ Cleanup fout: ${data.message}`, 'error');
                    return;
                }
                
                const message = `✅ Cleanup voltooid! ${data.deleted_count || 0} oude artikelen verwijderd.`;
                showStatus(message, 'success');
                
                // Reload page after 2 seconds to show updated stats
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            })
            .catch(error => {
                console.error('Cleanup error:', error);
                showStatus('❌ Fout bij cleanup: ' + error.message, 'error');
            });
        }
    </script>
</body>
</html> 