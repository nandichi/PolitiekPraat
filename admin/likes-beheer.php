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
                
            case 'bulk_set_likes':
                // Bulk likes update voor geselecteerde blogs
                if (isset($_POST['selected_blogs']) && is_array($_POST['selected_blogs'])) {
                    $updatedCount = 0;
                    $totalLikesSet = 0;
                    
                    foreach ($_POST['selected_blogs'] as $blogId) {
                        $blogId = (int)$blogId;
                        $likesKey = "likes_" . $blogId;
                        
                        if (isset($_POST[$likesKey])) {
                            $likesAmount = max(0, (int)$_POST[$likesKey]);
                            
                            $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
                            $db->bind(':likes', $likesAmount);
                            $db->bind(':id', $blogId);
                            
                            if ($db->execute()) {
                                $updatedCount++;
                                $totalLikesSet += $likesAmount;
                            }
                        }
                    }
                    
                    $successMessage = "Likes succesvol bijgewerkt voor {$updatedCount} blogs";
                } else {
                    $errorMessage = "Geen blogs geselecteerd voor bulk update";
                }
                break;
                
            case 'single_blog_update':
                // Individuele blog likes update via AJAX
                if (isset($_POST['blog_id']) && isset($_POST['likes_amount'])) {
                    $blogId = (int)$_POST['blog_id'];
                    $likesAmount = max(0, (int)$_POST['likes_amount']);
                    
                    $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
                    $db->bind(':likes', $likesAmount);
                    $db->bind(':id', $blogId);
                    
                    if ($db->execute()) {
                        if (isset($_POST['ajax'])) {
                            echo json_encode(['success' => true, 'message' => 'Likes bijgewerkt']);
                            exit;
                        } else {
                            $successMessage = "Likes succesvol ingesteld op {$likesAmount}";
                        }
                    } else {
                        if (isset($_POST['ajax'])) {
                            echo json_encode(['success' => false, 'message' => 'Fout bij bijwerken']);
                            exit;
                        } else {
                            $errorMessage = "Er ging iets mis bij het instellen van likes";
                        }
                    }
                }
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

.blog-item {
    transition: all 0.3s ease;
}

.blog-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.blog-checkbox:checked + .blog-item {
    background-color: #f3f4f6;
    border-color: #8b5cf6;
}

.likes-input {
    transition: all 0.3s ease;
}

.likes-input:focus {
    transform: scale(1.05);
}

.selected-highlight {
    background-color: #f3f4f6;
    border-color: #8b5cf6;
}

/* Animaties voor feedback */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.bounce-animation {
    animation: bounce 0.6s ease-in-out;
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

        <!-- Bulk Likes Editor -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Bulk Likes Editor</h2>
                        <p class="text-gray-600 text-sm">Bewerk meerdere blogs tegelijk</p>
                    </div>
                    <div class="flex space-x-2">
                        <button id="selectAll" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors text-sm">
                            Alles Selecteren
                        </button>
                        <button id="bulkUpdate" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors text-sm">
                            Bulk Update
                        </button>
                    </div>
                </div>
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
                    <form id="bulkLikesForm" method="POST">
                        <input type="hidden" name="action" value="bulk_set_likes">
                        <div class="space-y-3">
                            <?php foreach ($allBlogs as $blog): ?>
                                <div class="blog-item flex items-center p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <input type="checkbox" name="selected_blogs[]" value="<?= $blog->id ?>" 
                                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded blog-checkbox">
                                        
                                        <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-800 mb-1 truncate"><?= htmlspecialchars($blog->title) ?></h3>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span><?= formatDate($blog->published_at) ?></span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    Huidige: <?= $blog->likes ?> likes
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center space-x-2">
                                            <label class="text-sm text-gray-600">Nieuwe likes:</label>
                                            <input type="number" name="likes_<?= $blog->id ?>" value="<?= $blog->likes ?>" 
                                                   min="0" max="99999" 
                                                   class="w-20 border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 likes-input">
                                        </div>
                                        
                                        <div class="flex space-x-1">
                                            <button type="button" onclick="adjustLikes(<?= $blog->id ?>, -1)" 
                                                    class="w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="adjustLikes(<?= $blog->id ?>, 1)" 
                                                    class="w-8 h-8 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <button type="button" onclick="updateSingleBlog(<?= $blog->id ?>)" 
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors text-sm whitespace-nowrap">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Bulk Actions -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600">Geselecteerde blogs:</span>
                                    <span id="selectedCount" class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">0</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <label class="text-sm text-gray-600">Stel alle geselecteerde in op:</label>
                                        <input type="number" id="bulkLikesValue" min="0" max="99999" value="0" 
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <button type="button" onclick="applyBulkLikes()" 
                                            class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors text-sm">
                                        Toepassen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Bulk Likes Editor Functionaliteit
document.addEventListener('DOMContentLoaded', function() {
    const autoEnabled = document.getElementById('auto_enabled');
    const likesCounters = document.querySelectorAll('.text-3xl.font-bold');
    const selectAllBtn = document.getElementById('selectAll');
    const bulkUpdateBtn = document.getElementById('bulkUpdate');
    const bulkForm = document.getElementById('bulkLikesForm');
    const selectedCountSpan = document.getElementById('selectedCount');
    const blogCheckboxes = document.querySelectorAll('.blog-checkbox');
    
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
    
    // Update selected count
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.blog-checkbox:checked').length;
        selectedCountSpan.textContent = selectedCount;
    }
    
    // Select all functionality
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allSelected = document.querySelectorAll('.blog-checkbox:checked').length === blogCheckboxes.length;
            
            blogCheckboxes.forEach(checkbox => {
                checkbox.checked = !allSelected;
            });
            
            updateSelectedCount();
            selectAllBtn.textContent = allSelected ? 'Alles Selecteren' : 'Alles Deselecteren';
        });
    }
    
    // Individual checkbox change
    blogCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            
            // Visual feedback for selection
            const blogItem = this.closest('.blog-item');
            if (this.checked) {
                blogItem.classList.add('selected-highlight');
            } else {
                blogItem.classList.remove('selected-highlight');
            }
        });
    });
    
    // Bulk update submit
    if (bulkUpdateBtn) {
        bulkUpdateBtn.addEventListener('click', function() {
            const selectedBlogs = document.querySelectorAll('.blog-checkbox:checked');
            
            if (selectedBlogs.length === 0) {
                alert('Selecteer eerst enkele blogs om bij te werken.');
                return;
            }
            
            if (confirm(`Weet je zeker dat je ${selectedBlogs.length} blogs wilt bijwerken?`)) {
                bulkForm.submit();
            }
        });
    }
    
    // Initial count update
    updateSelectedCount();
});

// Likes aanpassen functie
function adjustLikes(blogId, adjustment) {
    const input = document.querySelector(`input[name="likes_${blogId}"]`);
    if (input) {
        const currentValue = parseInt(input.value) || 0;
        const newValue = Math.max(0, currentValue + adjustment);
        input.value = newValue;
        
        // Visual feedback
        input.style.backgroundColor = adjustment > 0 ? '#dcfce7' : '#fef2f2';
        setTimeout(() => {
            input.style.backgroundColor = '';
        }, 300);
    }
}

// Bulk likes toepassen
function applyBulkLikes() {
    const bulkValue = document.getElementById('bulkLikesValue').value;
    const selectedCheckboxes = document.querySelectorAll('.blog-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Selecteer eerst enkele blogs.');
        return;
    }
    
    if (confirm(`Weet je zeker dat je ${selectedCheckboxes.length} blogs wilt instellen op ${bulkValue} likes?`)) {
        selectedCheckboxes.forEach(checkbox => {
            const blogId = checkbox.value;
            const input = document.querySelector(`input[name="likes_${blogId}"]`);
            if (input) {
                input.value = bulkValue;
                
                // Visual feedback
                input.style.backgroundColor = '#ddd6fe';
                setTimeout(() => {
                    input.style.backgroundColor = '';
                }, 500);
            }
        });
    }
}

// Individuele blog update via AJAX
function updateSingleBlog(blogId) {
    const input = document.querySelector(`input[name="likes_${blogId}"]`);
    if (!input) return;
    
    const likesAmount = input.value;
    
    // Visual feedback
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = '...';
    button.disabled = true;
    
    // AJAX request
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=single_blog_update&blog_id=${blogId}&likes_amount=${likesAmount}&ajax=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success feedback
            button.textContent = '✓';
            button.style.backgroundColor = '#22c55e';
            input.style.backgroundColor = '#dcfce7';
            
            // Update stats if visible
            const statsElements = document.querySelectorAll('.text-3xl.font-bold');
            // Hier zou je de stats kunnen updaten
            
            setTimeout(() => {
                button.textContent = originalText;
                button.style.backgroundColor = '';
                input.style.backgroundColor = '';
            }, 1500);
        } else {
            // Error feedback
            button.textContent = '✗';
            button.style.backgroundColor = '#ef4444';
            input.style.backgroundColor = '#fef2f2';
            alert('Fout bij bijwerken: ' + data.message);
            
            setTimeout(() => {
                button.textContent = originalText;
                button.style.backgroundColor = '';
                input.style.backgroundColor = '';
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Er ging iets mis bij het bijwerken.');
        button.textContent = originalText;
        button.style.backgroundColor = '';
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + A voor select all
    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
        e.preventDefault();
        const selectAllBtn = document.getElementById('selectAll');
        if (selectAllBtn) {
            selectAllBtn.click();
        }
    }
    
    // Ctrl/Cmd + Enter voor bulk update
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const bulkUpdateBtn = document.getElementById('bulkUpdate');
        if (bulkUpdateBtn) {
            bulkUpdateBtn.click();
        }
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 