<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/StemwijzerController.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$stemwijzerController = new StemwijzerController();

// Haal statistieken op
try {
    $db = new Database();
    
    // Tel aantal vragen
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_questions");
    $totalQuestions = $db->single()->count ?? 0;
    
    // Tel aantal partijen
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_parties");
    $totalParties = $db->single()->count ?? 0;
    
    // Tel aantal antwoorden/resultaten
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_results");
    $totalResults = $db->single()->count ?? 0;
    
    // Haal laatste 5 vragen op
    $db->query("SELECT id, title, order_number, is_active, created_at FROM stemwijzer_questions ORDER BY created_at DESC LIMIT 5");
    $recentQuestions = $db->resultSet() ?? [];
    
    // Haal alle partijen op
    $db->query("SELECT id, name, short_name, logo_url FROM stemwijzer_parties ORDER BY name ASC");
    $allParties = $db->resultSet() ?? [];
    
} catch (Exception $e) {
    $totalQuestions = 0;
    $totalParties = 0;
    $totalResults = 0;
    $recentQuestions = [];
    $allParties = [];
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
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Stemwijzer Beheer</h1>
                    <p class="text-blue-100 text-lg">Beheer vragen, partijen en bekijk statistieken</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-statistieken.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Statistieken
                    </a>
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Vragen Beheren
                    </a>
                                    <a href="stemwijzer-partij-beheer.php" 
                   class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                    Stemwijzer Partijen
                </a>
                <a href="political-parties-beheer.php" 
                   class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold">
                    Partijen Beheren
                </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Vragen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalQuestions ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Politieke Partijen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalParties ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Ingevulde Tests</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalResults ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Recent Questions -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Recente Vragen</h2>
                        <a href="stemwijzer-vraag-beheer.php" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Alle vragen →
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <?php if (empty($recentQuestions)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen vragen aangemaakt</p>
                            <a href="stemwijzer-vraag-toevoegen.php" class="inline-block mt-2 text-indigo-600 hover:text-indigo-800 font-medium">
                                Voeg eerste vraag toe
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentQuestions as $question): ?>
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($question->title) ?></h3>
                                        <div class="flex items-center space-x-3 text-sm text-gray-500">
                                            <span>Volgorde: <?= $question->order_number ?></span>
                                            <span class="px-2 py-1 rounded-full text-xs <?= $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= $question->is_active ? 'Actief' : 'Inactief' ?>
                                            </span>
                                            <span><?= formatDate($question->created_at) ?></span>
                                        </div>
                                    </div>
                                    <a href="stemwijzer-vraag-bewerken.php?id=<?= $question->id ?>" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Bewerken
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Parties Overview -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Politieke Partijen</h2>
                        <a href="stemwijzer-partij-beheer.php" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                            Alle partijen →
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <?php if (empty($allParties)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen partijen aangemaakt</p>
                            <a href="stemwijzer-partij-toevoegen.php" class="inline-block mt-2 text-emerald-600 hover:text-emerald-800 font-medium">
                                Voeg eerste partij toe
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-2 gap-4">
                            <?php foreach (array_slice($allParties, 0, 8) as $party): ?>
                                <div class="flex items-center space-x-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 p-1 flex-shrink-0">
                                        <?php if ($party->logo_url): ?>
                                            <img src="<?= htmlspecialchars($party->logo_url) ?>" 
                                                 alt="<?= htmlspecialchars($party->name) ?>" 
                                                 class="w-full h-full object-contain rounded-md">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-100 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-gray-800 text-sm truncate"><?= htmlspecialchars($party->name) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($party->short_name) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($allParties) > 8): ?>
                            <div class="mt-4 text-center">
                                <a href="stemwijzer-partij-beheer.php" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                                    + <?= count($allParties) - 8 ?> meer partijen
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Snelle Acties</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="stemwijzer-vraag-toevoegen.php" 
                   class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Nieuwe Vraag</h3>
                            <p class="text-sm text-gray-600">Voeg een vraag toe</p>
                        </div>
                    </div>
                </a>
                
                <a href="stemwijzer-partij-toevoegen.php" 
                   class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Nieuwe Partij</h3>
                            <p class="text-sm text-gray-600">Voeg een partij toe</p>
                        </div>
                    </div>
                </a>
                
                <a href="stemwijzer-standpunten-beheer.php" 
                   class="group p-6 bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Standpunten</h3>
                            <p class="text-sm text-gray-600">Beheer standpunten</p>
                        </div>
                    </div>
                </a>
                
                <a href="stemwijzer-statistieken.php" 
                   class="group p-6 bg-gradient-to-br from-orange-50 to-red-50 border border-orange-200 rounded-xl hover:from-orange-100 hover:to-red-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Statistieken</h3>
                            <p class="text-sm text-gray-600">Bekijk analytics</p>
                        </div>
                    </div>
                </a>
                
                <a href="../stemwijzer" target="_blank"
                   class="group p-6 bg-gradient-to-br from-teal-50 to-cyan-50 border border-teal-200 rounded-xl hover:from-teal-100 hover:to-cyan-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Preview</h3>
                            <p class="text-sm text-gray-600">Bekijk stemwijzer</p>
                        </div>
                    </div>
                </a>
                
                <a href="scraper_dashboard.php" 
                   class="group p-6 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl hover:from-indigo-100 hover:to-blue-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">News Scraper</h3>
                            <p class="text-sm text-gray-600">Beheer nieuwsscraper</p>
                        </div>
                    </div>
                </a>
                
                <a href="likes-beheer.php" 
                   class="group p-6 bg-gradient-to-br from-pink-50 to-red-50 border border-pink-200 rounded-xl hover:from-pink-100 hover:to-red-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Likes Beheer</h3>
                            <p class="text-sm text-gray-600">Beheer blog likes</p>
                        </div>
                    </div>
                </a>
                
                <a href="news-scraper-beheer.php" 
                   class="group p-6 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">News Scraper</h3>
                            <p class="text-sm text-gray-600">Auto nieuws scraping</p>
                        </div>
                    </div>
                </a>
                
                <a href="political-parties-beheer.php" 
                   class="group p-6 bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-200 rounded-xl hover:from-violet-100 hover:to-purple-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-violet-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Partijen Beheer</h3>
                            <p class="text-sm text-gray-600">Beheer politieke partijen</p>
                        </div>
                    </div>
                </a>
                
                <a href="presidenten-beheer.php" 
                   class="group p-6 bg-gradient-to-br from-red-50 to-blue-50 border border-red-200 rounded-xl hover:from-red-100 hover:to-blue-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m-4-4l4-4 4 4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">🇺🇸 Presidenten Beheer</h3>
                            <p class="text-sm text-gray-600">Amerikaanse presidenten database</p>
                        </div>
                    </div>
                </a>
                
                <a href="api-test.php" 
                   class="group p-6 bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200 rounded-xl hover:from-cyan-100 hover:to-blue-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-cyan-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">API Tester</h3>
                            <p class="text-sm text-gray-600">Test alle API endpoints</p>
                        </div>
                    </div>
                </a>
                
                <a href="test-cron-email.php" 
                   class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Test Cron Emails</h3>
                            <p class="text-sm text-gray-600">Test email notificaties</p>
                        </div>
                    </div>
                </a>
                
                <a href="../scripts/run_nederlandse_verkiezingen_migration.php" 
                   class="group p-6 bg-gradient-to-br from-orange-50 to-yellow-50 border border-orange-200 rounded-xl hover:from-orange-100 hover:to-yellow-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l3 3 3-3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">🇳🇱 Nederlandse Verkiezingen</h3>
                            <p class="text-sm text-gray-600">Setup Nederlandse verkiezingen database</p>
                        </div>
                    </div>
                </a>
                
                <a href="../scripts/run_anonymous_comments_migration.php" 
                   class="group p-6 bg-gradient-to-br from-slate-50 to-gray-50 border border-slate-200 rounded-xl hover:from-slate-100 hover:to-gray-100 transition-all duration-300 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-slate-500 to-gray-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">💬 Anonieme Comments</h3>
                            <p class="text-sm text-gray-600">Setup anonieme reacties database</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

<script>
// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters
    const counters = document.querySelectorAll('.text-3xl.font-bold');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 30;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 50);
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 