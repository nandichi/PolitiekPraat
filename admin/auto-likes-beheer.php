<?php
/**
 * Auto Likes Beheer Dashboard
 * Beheer van automatische likes simulatie per blog
 */
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$db = new Database();

// Controleer of de benodigde tabellen bestaan
$db->query("SHOW TABLES LIKE 'auto_likes_settings'");
$tableExists = $db->single();

if (!$tableExists) {
    require_once '../views/templates/header.php';
    ?>
    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-red-200">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Database Migratie Vereist</h1>
                        <p class="text-gray-600">De benodigde tabellen bestaan nog niet</p>
                    </div>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <p class="text-amber-800 mb-2">Voer de volgende opdracht uit op de server om de tabellen aan te maken:</p>
                    <code class="block bg-gray-800 text-green-400 p-3 rounded-lg text-sm overflow-x-auto">
                        php <?= dirname(dirname(__FILE__)) ?>/scripts/create_auto_likes_tables.php
                    </code>
                </div>
                
                <div class="flex space-x-4">
                    <a href="dashboard.php" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                        Terug naar Dashboard
                    </a>
                    <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 transition-colors">
                        Opnieuw Proberen
                    </a>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once '../views/templates/footer.php';
    exit;
}

// Helper functie om instelling op te halen
function getSetting($db, $key, $default = null) {
    $db->query("SELECT setting_value FROM auto_likes_settings WHERE setting_key = :key");
    $db->bind(':key', $key);
    $result = $db->single();
    return $result ? $result->setting_value : $default;
}

// Helper functie om instelling bij te werken
function updateSetting($db, $key, $value) {
    $db->query("INSERT INTO auto_likes_settings (setting_key, setting_value) VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value2");
    $db->bind(':key', $key);
    $db->bind(':value', $value);
    $db->bind(':value2', $value);
    return $db->execute();
}

// Verwerk formulier submissions
$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'save_global_settings':
            $globalEnabled = isset($_POST['global_enabled']) ? '1' : '0';
            $defaultMinLikes = max(1, (int)($_POST['default_min_likes'] ?? 1));
            $defaultMaxLikes = max($defaultMinLikes, (int)($_POST['default_max_likes'] ?? 5));
            $likeChanceMin = max(1, min(100, (int)($_POST['like_chance_min'] ?? 30)));
            $likeChanceMax = max($likeChanceMin, min(100, (int)($_POST['like_chance_max'] ?? 70)));
            
            updateSetting($db, 'global_enabled', $globalEnabled);
            updateSetting($db, 'default_min_likes', $defaultMinLikes);
            updateSetting($db, 'default_max_likes', $defaultMaxLikes);
            updateSetting($db, 'like_chance_min', $likeChanceMin);
            updateSetting($db, 'like_chance_max', $likeChanceMax);
            
            $successMessage = "Globale instellingen opgeslagen";
            break;
            
        case 'toggle_blog':
            $blogId = (int)($_POST['blog_id'] ?? 0);
            $enabled = isset($_POST['enabled']) ? 1 : 0;
            
            if ($blogId > 0) {
                // Insert of update config
                $db->query("INSERT INTO auto_likes_config (blog_id, enabled) VALUES (:blog_id, :enabled)
                            ON DUPLICATE KEY UPDATE enabled = :enabled2");
                $db->bind(':blog_id', $blogId);
                $db->bind(':enabled', $enabled);
                $db->bind(':enabled2', $enabled);
                
                if ($db->execute()) {
                    $successMessage = "Blog configuratie bijgewerkt";
                } else {
                    $errorMessage = "Fout bij bijwerken configuratie";
                }
            }
            break;
            
        case 'update_blog_config':
            $blogId = (int)($_POST['blog_id'] ?? 0);
            $enabled = isset($_POST['enabled']) ? 1 : 0;
            $minLikes = max(1, (int)($_POST['min_likes'] ?? 1));
            $maxLikes = max($minLikes, (int)($_POST['max_likes'] ?? 5));
            
            if ($blogId > 0) {
                $db->query("INSERT INTO auto_likes_config (blog_id, enabled, min_likes_per_run, max_likes_per_run) 
                            VALUES (:blog_id, :enabled, :min_likes, :max_likes)
                            ON DUPLICATE KEY UPDATE enabled = :enabled2, min_likes_per_run = :min_likes2, max_likes_per_run = :max_likes2");
                $db->bind(':blog_id', $blogId);
                $db->bind(':enabled', $enabled);
                $db->bind(':enabled2', $enabled);
                $db->bind(':min_likes', $minLikes);
                $db->bind(':min_likes2', $minLikes);
                $db->bind(':max_likes', $maxLikes);
                $db->bind(':max_likes2', $maxLikes);
                
                if ($db->execute()) {
                    $successMessage = "Blog configuratie opgeslagen";
                } else {
                    $errorMessage = "Fout bij opslaan configuratie";
                }
            }
            break;
            
        case 'bulk_enable':
            $db->query("UPDATE auto_likes_config SET enabled = 1");
            $db->execute();
            
            // Voeg ook blogs toe die nog geen config hebben
            $db->query("INSERT IGNORE INTO auto_likes_config (blog_id, enabled) SELECT id, 1 FROM blogs");
            $db->execute();
            
            $successMessage = "Alle blogs ingeschakeld voor auto likes";
            break;
            
        case 'bulk_disable':
            $db->query("UPDATE auto_likes_config SET enabled = 0");
            $db->execute();
            $successMessage = "Alle blogs uitgeschakeld voor auto likes";
            break;
            
        case 'clear_logs':
            $db->query("DELETE FROM auto_likes_log");
            $db->execute();
            $successMessage = "Alle logs gewist";
            break;
    }
}

// AJAX toggle request
if (isset($_GET['ajax']) && $_GET['ajax'] === 'toggle') {
    header('Content-Type: application/json');
    
    $blogId = (int)($_GET['blog_id'] ?? 0);
    $enabled = (int)($_GET['enabled'] ?? 0);
    
    if ($blogId > 0) {
        $db->query("INSERT INTO auto_likes_config (blog_id, enabled) VALUES (:blog_id, :enabled)
                    ON DUPLICATE KEY UPDATE enabled = :enabled2");
        $db->bind(':blog_id', $blogId);
        $db->bind(':enabled', $enabled);
        $db->bind(':enabled2', $enabled);
        
        if ($db->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status bijgewerkt']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Fout bij bijwerken']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ongeldig blog ID']);
    }
    exit;
}

// Haal globale instellingen op
$globalEnabled = getSetting($db, 'global_enabled', '0');
$defaultMinLikes = getSetting($db, 'default_min_likes', '1');
$defaultMaxLikes = getSetting($db, 'default_max_likes', '5');
$likeChanceMin = getSetting($db, 'like_chance_min', '30');
$likeChanceMax = getSetting($db, 'like_chance_max', '70');
$lastRun = getSetting($db, 'last_run', '0');

// Haal alle blogs op met hun configuratie
$db->query("
    SELECT 
        b.id,
        b.title,
        b.likes,
        b.published_at,
        COALESCE(alc.enabled, 1) as auto_enabled,
        COALESCE(alc.min_likes_per_run, :default_min) as min_likes,
        COALESCE(alc.max_likes_per_run, :default_max) as max_likes
    FROM blogs b
    LEFT JOIN auto_likes_config alc ON b.id = alc.blog_id
    ORDER BY b.published_at DESC
");
$db->bind(':default_min', $defaultMinLikes);
$db->bind(':default_max', $defaultMaxLikes);
$blogs = $db->resultSet() ?? [];

// Haal statistieken op
$db->query("SELECT COUNT(*) as total_blogs, SUM(likes) as total_likes FROM blogs");
$blogStats = $db->single();

$db->query("SELECT COUNT(*) as enabled_count FROM auto_likes_config WHERE enabled = 1");
$enabledStats = $db->single();

$db->query("SELECT SUM(likes_added) as total_auto_likes FROM auto_likes_log");
$autoLikesStats = $db->single();

// Haal recente logs op
$db->query("
    SELECT all2.*, b.title as blog_title
    FROM auto_likes_log all2
    JOIN blogs b ON all2.blog_id = b.id
    ORDER BY all2.run_time DESC
    LIMIT 50
");
$recentLogs = $db->resultSet() ?? [];

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.toggle-switch {
    position: relative;
    width: 48px;
    height: 24px;
    background-color: #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.toggle-switch.active {
    background-color: #10b981;
}

.toggle-switch::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.toggle-switch.active::after {
    transform: translateX(24px);
}

.blog-row {
    transition: all 0.2s ease;
}

.blog-row:hover {
    background-color: #f9fafb;
}

.blog-row.disabled {
    opacity: 0.6;
}

.pulse-dot {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Auto Likes Beheer</h1>
                    <p class="text-blue-100 text-lg">Beheer automatische likes simulatie per blog</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="runCronNow()" id="runCronBtn"
                       class="bg-green-500 text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-green-600 transition-all duration-300 font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Nu Uitvoeren
                    </button>
                    <a href="dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center">
                        Terug naar Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10 pb-12">
        
        <!-- Succes/Error Messages -->
        <?php if ($successMessage): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($successMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800"><?= htmlspecialchars($errorMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card rounded-2xl p-5 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Totaal Blogs</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $blogStats->total_blogs ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Ingeschakeld</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $enabledStats->enabled_count ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Auto Likes</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $autoLikesStats->total_auto_likes ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Laatste Run</p>
                        <p class="text-sm font-bold text-gray-800">
                            <?= $lastRun > 0 ? date('d-m H:i', (int)$lastRun) : 'Nooit' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- Globale Instellingen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Globale Instellingen</h2>
                </div>
                
                <form method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="action" value="save_global_settings">
                    
                    <!-- Global Toggle -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Systeem Actief</p>
                            <p class="text-sm text-gray-500">Schakel alle auto likes in/uit</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="global_enabled" class="sr-only peer" <?= $globalEnabled === '1' ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <!-- Likes Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Standaard Likes per Run</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-gray-500">Min</label>
                                <input type="number" name="default_min_likes" value="<?= htmlspecialchars($defaultMinLikes) ?>" 
                                       min="1" max="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Max</label>
                                <input type="number" name="default_max_likes" value="<?= htmlspecialchars($defaultMaxLikes) ?>" 
                                       min="1" max="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Like Chance -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kans op Likes (%)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-gray-500">Min %</label>
                                <input type="number" name="like_chance_min" value="<?= htmlspecialchars($likeChanceMin) ?>" 
                                       min="1" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Max %</label>
                                <input type="number" name="like_chance_max" value="<?= htmlspecialchars($likeChanceMax) ?>" 
                                       min="1" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Willekeurige kans per blog per run</p>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
                        Instellingen Opslaan
                    </button>
                </form>
            </div>
            
            <!-- Bulk Acties -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Bulk Acties</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="bulk_enable">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 mb-3">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Alle Blogs Inschakelen
                        </button>
                    </form>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="bulk_disable">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 mb-3">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Alle Blogs Uitschakelen
                        </button>
                    </form>
                    
                    <form method="POST" onsubmit="return confirm('Weet je zeker dat je alle logs wilt wissen?')">
                        <input type="hidden" name="action" value="clear_logs">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-red-600 hover:to-rose-700 transition-all duration-300">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Alle Logs Wissen
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Recente Logs -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Recente Activiteit</h2>
                </div>
                
                <div class="p-4 max-h-80 overflow-y-auto">
                    <?php if (empty($recentLogs)): ?>
                        <p class="text-gray-500 text-center py-8">Nog geen activiteit gelogd</p>
                    <?php else: ?>
                        <div class="space-y-2">
                            <?php foreach (array_slice($recentLogs, 0, 15) as $log): ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 truncate"><?= htmlspecialchars(mb_substr($log->blog_title, 0, 25)) ?>...</p>
                                        <p class="text-xs text-gray-500"><?= date('d-m H:i', strtotime($log->run_time)) ?></p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                        +<?= $log->likes_added ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Blog Lijst -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Blog Configuratie</h2>
                        <p class="text-sm text-gray-600">Schakel auto likes per blog in of uit</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500"><?= count($blogs) ?> blogs</span>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                <?php if (empty($blogs)): ?>
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">Geen blogs gevonden</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($blogs as $blog): ?>
                        <div class="blog-row flex items-center justify-between p-4 <?= $blog->auto_enabled ? '' : 'disabled' ?>" data-blog-id="<?= $blog->id ?>">
                            <div class="flex items-center space-x-4 flex-1 min-w-0">
                                <div class="toggle-switch <?= $blog->auto_enabled ? 'active' : '' ?>" 
                                     onclick="toggleBlog(<?= $blog->id ?>, <?= $blog->auto_enabled ? 0 : 1 ?>)">
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-800 truncate"><?= htmlspecialchars($blog->title) ?></h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><?= date('d-m-Y', strtotime($blog->published_at)) ?></span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            <?= $blog->likes ?> likes
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-500">
                                    <span class="px-2 py-1 bg-gray-100 rounded"><?= $blog->min_likes ?>-<?= $blog->max_likes ?> per run</span>
                                </div>
                                
                                <button onclick="openConfigModal(<?= $blog->id ?>, '<?= htmlspecialchars(addslashes($blog->title)) ?>', <?= $blog->auto_enabled ?>, <?= $blog->min_likes ?>, <?= $blog->max_likes ?>)" 
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Config Modal -->
<div id="configModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Blog Configuratie</h3>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="update_blog_config">
            <input type="hidden" name="blog_id" id="modalBlogId">
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <p class="font-medium text-gray-800">Auto Likes Actief</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="enabled" id="modalEnabled" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Likes per Run</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-500">Min</label>
                        <input type="number" name="min_likes" id="modalMinLikes" min="1" max="20" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Max</label>
                        <input type="number" name="max_likes" id="modalMaxLikes" min="1" max="50" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeConfigModal()" 
                        class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-200 transition-all duration-300">
                    Annuleren
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
                    Opslaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleBlog(blogId, enabled) {
    const row = document.querySelector(`[data-blog-id="${blogId}"]`);
    const toggle = row.querySelector('.toggle-switch');
    
    // Optimistic UI update
    toggle.classList.toggle('active');
    row.classList.toggle('disabled');
    
    fetch(`?ajax=toggle&blog_id=${blogId}&enabled=${enabled}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // Revert on failure
                toggle.classList.toggle('active');
                row.classList.toggle('disabled');
                alert('Fout: ' + data.message);
            }
        })
        .catch(error => {
            // Revert on error
            toggle.classList.toggle('active');
            row.classList.toggle('disabled');
            console.error('Error:', error);
        });
}

function openConfigModal(blogId, title, enabled, minLikes, maxLikes) {
    document.getElementById('modalBlogId').value = blogId;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalEnabled').checked = enabled === 1;
    document.getElementById('modalMinLikes').value = minLikes;
    document.getElementById('modalMaxLikes').value = maxLikes;
    document.getElementById('configModal').classList.remove('hidden');
}

function closeConfigModal() {
    document.getElementById('configModal').classList.add('hidden');
}

// Close modal on backdrop click
document.getElementById('configModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfigModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfigModal();
    }
});

// Run cron job now
function runCronNow() {
    const button = document.getElementById('runCronBtn');
    const originalContent = button.innerHTML;
    
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = `
        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Bezig...
    `;
    
    fetch('../scripts/auto_likes_cron.php?run=1')
        .then(response => response.text())
        .then(data => {
            button.disabled = false;
            button.innerHTML = originalContent;
            
            // Parse de output
            const lines = data.split('\n').filter(line => line.trim());
            const summary = lines.slice(-4).join('\n');
            
            if (data.includes('voltooid') || data.includes('Samenvatting')) {
                alert('Auto Likes succesvol uitgevoerd!\n\n' + summary);
                // Refresh de pagina om nieuwe stats te tonen
                setTimeout(() => window.location.reload(), 500);
            } else if (data.includes('uitgeschakeld')) {
                alert('Auto Likes is uitgeschakeld. Schakel het eerst in via de globale instellingen.');
            } else if (data.includes('FOUT')) {
                alert('Er is een fout opgetreden:\n\n' + data);
            } else {
                alert('Resultaat:\n\n' + data);
            }
        })
        .catch(error => {
            button.disabled = false;
            button.innerHTML = originalContent;
            alert('Er is een fout opgetreden: ' + error.message);
        });
}
</script>

<?php require_once '../views/templates/footer.php'; ?>
