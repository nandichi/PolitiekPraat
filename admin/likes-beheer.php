<?php
// Configuratie voor het beheren van likes met onregelmatige updates
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

// Database verbinding
$db = new Database();

// Verwerk formulier submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_random_likes':
                // Voeg willekeurige likes toe aan alle blogs
                $minLikes = (int)$_POST['min_likes'] ?? 1;
                $maxLikes = (int)$_POST['max_likes'] ?? 10;
                $blogCount = (int)$_POST['blog_count'] ?? 0;
                
                // Haal blogs op om likes aan toe te voegen
                $db->query("SELECT id, title, likes FROM blogs ORDER BY published_at DESC");
                $blogs = $db->resultSet();
                
                if ($blogCount > 0) {
                    // Selecteer willekeurig aantal blogs
                    $selectedBlogs = array_slice($blogs, 0, $blogCount);
                } else {
                    $selectedBlogs = $blogs;
                }
                
                $updatedCount = 0;
                foreach ($selectedBlogs as $blog) {
                    $randomLikes = rand($minLikes, $maxLikes);
                    $newLikes = $blog->likes + $randomLikes;
                    
                    $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
                    $db->bind(':likes', $newLikes);
                    $db->bind(':id', $blog->id);
                    
                    if ($db->execute()) {
                        $updatedCount++;
                    }
                }
                
                $successMessage = "Likes succesvol toegevoegd aan {$updatedCount} blogs";
                break;
                
            case 'set_blog_likes':
                // Stel likes in voor specifieke blog
                $blogId = (int)$_POST['blog_id'];
                $likesAmount = (int)$_POST['likes_amount'];
                
                $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
                $db->bind(':likes', $likesAmount);
                $db->bind(':id', $blogId);
                
                if ($db->execute()) {
                    $successMessage = "Likes succesvol ingesteld op {$likesAmount}";
                } else {
                    $errorMessage = "Er ging iets mis bij het instellen van likes";
                }
                break;
                
            case 'save_auto_settings':
                // Sla automatische instellingen op (zou in database kunnen worden opgeslagen)
                $autoEnabled = isset($_POST['auto_enabled']) ? 1 : 0;
                $autoMinLikes = (int)$_POST['auto_min_likes'];
                $autoMaxLikes = (int)$_POST['auto_max_likes'];
                $autoInterval = (int)$_POST['auto_interval'];
                
                // Voor nu opslaan in een bestand - in productie zou dit in database moeten
                $autoSettings = [
                    'enabled' => $autoEnabled,
                    'min_likes' => $autoMinLikes,
                    'max_likes' => $autoMaxLikes,
                    'interval_hours' => $autoInterval,
                    'last_run' => time()
                ];
                
                file_put_contents('../cache/auto_likes_settings.json', json_encode($autoSettings));
                $successMessage = "Automatische instellingen opgeslagen";
                break;
        }
    }
}

// Haal alle blogs op
$db->query("SELECT id, title, likes, published_at FROM blogs ORDER BY published_at DESC");
$allBlogs = $db->resultSet() ?? [];

// Haal statistieken op
$db->query("SELECT COUNT(*) as total_blogs, SUM(likes) as total_likes FROM blogs");
$stats = $db->single();

// Laad automatische instellingen
$autoSettingsFile = '../cache/auto_likes_settings.json';
$autoSettings = [];
if (file_exists($autoSettingsFile)) {
    $autoSettings = json_decode(file_get_contents($autoSettingsFile), true);
}

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

.likes-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Likes Beheer</h1>
                    <p class="text-blue-100 text-lg">Beheer blog likes en automatische updates</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Terug naar Dashboard
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
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Blogs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats->total_blogs ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover likes-pulse">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Likes</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats->total_likes ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Random Likes Toevoegen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Willekeurige Likes Toevoegen</h2>
                    <p class="text-gray-600 text-sm">Voeg onregelmatige likes toe aan blogs</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_random_likes">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Likes</label>
                                <input type="number" name="min_likes" value="1" min="1" max="50" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Likes</label>
                                <input type="number" name="max_likes" value="10" min="1" max="100" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aantal Blogs (0 = alle)</label>
                            <input type="number" name="blog_count" value="0" min="0" max="<?= count($allBlogs) ?>" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <p class="text-xs text-gray-500 mt-1">Laat leeg of 0 voor alle blogs</p>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Likes Toevoegen
                        </button>
                    </form>
                </div>
            </div>

            <!-- Automatische Instellingen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Automatische Instellingen</h2>
                    <p class="text-gray-600 text-sm">Configureer automatische likes updates</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="save_auto_settings">
                        
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="auto_enabled" id="auto_enabled" 
                                   <?= ($autoSettings['enabled'] ?? false) ? 'checked' : '' ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="auto_enabled" class="text-sm font-medium text-gray-700">
                                Automatische likes inschakelen
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Likes</label>
                                <input type="number" name="auto_min_likes" value="<?= $autoSettings['min_likes'] ?? 1 ?>" min="1" max="10" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Likes</label>
                                <input type="number" name="auto_max_likes" value="<?= $autoSettings['max_likes'] ?? 5 ?>" min="1" max="20" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Interval (uren)</label>
                            <select name="auto_interval" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" <?= ($autoSettings['interval_hours'] ?? 6) == 1 ? 'selected' : '' ?>>1 uur</option>
                                <option value="2" <?= ($autoSettings['interval_hours'] ?? 6) == 2 ? 'selected' : '' ?>>2 uur</option>
                                <option value="4" <?= ($autoSettings['interval_hours'] ?? 6) == 4 ? 'selected' : '' ?>>4 uur</option>
                                <option value="6" <?= ($autoSettings['interval_hours'] ?? 6) == 6 ? 'selected' : '' ?>>6 uur</option>
                                <option value="12" <?= ($autoSettings['interval_hours'] ?? 6) == 12 ? 'selected' : '' ?>>12 uur</option>
                                <option value="24" <?= ($autoSettings['interval_hours'] ?? 6) == 24 ? 'selected' : '' ?>>24 uur</option>
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

        <!-- Blogs Overzicht -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Blogs Overzicht</h2>
                <p class="text-gray-600 text-sm">Beheer likes per blog</p>
            </div>
            
            <div class="p-6">
                <?php if (empty($allBlogs)): ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-600">Geen blogs gevonden</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($allBlogs as $blog): ?>
                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($blog->title) ?></h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><?= formatDate($blog->published_at) ?></span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            <?= $blog->likes ?> likes
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form method="POST" class="flex items-center space-x-2">
                                        <input type="hidden" name="action" value="set_blog_likes">
                                        <input type="hidden" name="blog_id" value="<?= $blog->id ?>">
                                        <input type="number" name="likes_amount" value="<?= $blog->likes ?>" min="0" max="9999" 
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <button type="submit" 
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition-colors text-sm">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Automatische likes simulator (alleen visueel)
document.addEventListener('DOMContentLoaded', function() {
    const autoEnabled = document.getElementById('auto_enabled');
    const likesCounters = document.querySelectorAll('.text-3xl.font-bold');
    
    // Simulate random likes updates (alleen visueel voor demonstratie)
    function simulateRandomLikes() {
        if (autoEnabled && autoEnabled.checked) {
            // Voeg willekeurige visuele effecten toe
            const pulseElements = document.querySelectorAll('.likes-pulse');
            pulseElements.forEach(element => {
                element.style.animation = 'pulse 0.5s ease-in-out';
                setTimeout(() => {
                    element.style.animation = 'pulse 2s infinite';
                }, 500);
            });
        }
    }
    
    // Simuleer elke 30 seconden (alleen voor demonstratie)
    setInterval(simulateRandomLikes, 30000);
    
    // Toon waarschuwing bij automatische instellingen
    if (autoEnabled) {
        autoEnabled.addEventListener('change', function() {
            if (this.checked) {
                alert('Let op: Deze functionaliteit vereist een cron job of scheduler voor automatische uitvoering.');
            }
        });
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 