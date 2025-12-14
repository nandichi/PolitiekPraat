<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/StemwijzerController.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$partijmeterController = new StemwijzerController();

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
    
    // StemmenTracker statistieken
    $db->query("SELECT COUNT(*) as count FROM stemmentracker_moties");
    $totalMoties = $db->single()->count ?? 0;
    
    $db->query("SELECT COUNT(*) as count FROM stemmentracker_votes");
    $totalVotes = $db->single()->count ?? 0;
    
    // Haal recente moties op
    $db->query("SELECT id, title, datum_stemming, onderwerp, uitslag FROM stemmentracker_moties ORDER BY created_at DESC LIMIT 5");
    $recentMoties = $db->resultSet() ?? [];
    
} catch (Exception $e) {
    $totalQuestions = 0;
    $totalParties = 0;
    $totalResults = 0;
    $recentQuestions = [];
    $allParties = [];
    $totalMoties = 0;
    $totalVotes = 0;
    $recentMoties = [];
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
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">PartijMeter Beheer</h1>
                    <p class="text-blue-100 text-base md:text-lg">Beheer vragen, partijen en bekijk statistieken</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:flex-wrap lg:flex-nowrap gap-3">
                    <a href="stemwijzer-statistieken.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center text-sm md:text-base">
                        Statistieken
                    </a>
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center text-sm md:text-base">
                        Vragen Beheren
                    </a>
                    <a href="stemwijzer-partij-beheer.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center text-sm md:text-base">
                        PartijMeter Partijen
                    </a>
                    <a href="political-parties-beheer.php" 
                       class="bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold text-center text-sm md:text-base">
                        Partijen Beheren
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
            
            <!-- StemmenTracker Stats Card -->
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary-dark rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2H9m0 10V9a2 2 0 012-2h2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">StemmenTracker</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalMoties ?></p>
                        <p class="text-xs text-gray-500"><?= $totalVotes ?> stemmen</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
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

            <!-- Recent StemmenTracker Moties -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-secondary-light/20 to-secondary/20 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Recente Moties</h2>
                        <a href="stemmentracker-motie-beheer.php" class="text-secondary hover:text-secondary-dark text-sm font-medium">
                            Alle moties →
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <?php if (empty($recentMoties)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen moties aangemaakt</p>
                            <a href="stemmentracker-motie-toevoegen.php" class="inline-block mt-2 text-secondary hover:text-secondary-dark font-medium">
                                Voeg eerste motie toe
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentMoties as $motie): ?>
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($motie->title) ?></h3>
                                        <div class="flex items-center space-x-3 text-sm text-gray-500">
                                            <span><?= date('d-m-Y', strtotime($motie->datum_stemming)) ?></span>
                                            <span><?= htmlspecialchars($motie->onderwerp) ?></span>
                                            <?php if ($motie->uitslag): ?>
                                                <span class="px-2 py-1 rounded-full text-xs <?= $motie->uitslag === 'aangenomen' ? 'bg-green-100 text-green-800' : ($motie->uitslag === 'verworpen' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                                    <?= ucfirst($motie->uitslag) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="stemmentracker-stemgedrag-beheer.php?motie_id=<?= $motie->id ?>" 
                                       class="text-secondary hover:text-secondary-dark font-medium text-sm">
                                        Stemgedrag
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Snelle Acties</h2>
            
            <!-- Stemwijzer Beheer -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    PartijMeter Beheer
                </h3>
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
                    
                    <a href="../partijmeter" target="_blank"
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
                                <p class="text-sm text-gray-600">Bekijk PartijMeter</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- StemmenTracker Beheer -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-secondary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2H9m0 10V9a2 2 0 012-2h2"/>
                    </svg>
                    StemmenTracker Beheer
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <a href="#" onclick="createStemmenTrackerTables()" 
                       class="group p-6 bg-gradient-to-br from-secondary-light/10 to-secondary/10 border border-secondary-light/30 rounded-xl hover:from-secondary-light/20 hover:to-secondary/20 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">🗃️ Setup Database</h3>
                                <p class="text-sm text-gray-600">Installeer StemmenTracker tabellen</p>
                            </div>
                        </div>
                    </a>

                    <a href="stemmentracker-motie-beheer.php" 
                       class="group p-6 bg-gradient-to-br from-primary/10 to-primary-dark/10 border border-primary/30 rounded-xl hover:from-primary/20 hover:to-primary-dark/20 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">📋 Moties Beheer</h3>
                                <p class="text-sm text-gray-600">Beheer alle moties</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="stemmentracker-motie-toevoegen.php" 
                       class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">➕ Nieuwe Motie</h3>
                                <p class="text-sm text-gray-600">Voeg motie toe</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="stemmentracker-stemgedrag-beheer.php" 
                       class="group p-6 bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-200 rounded-xl hover:from-purple-100 hover:to-violet-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2H9m0 10V9a2 2 0 012-2h2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">🗳️ Stemgedrag</h3>
                                <p class="text-sm text-gray-600">Beheer partij stemmen</p>
                            </div>
                        </div>
                    </a>

                    <a href="../stemmentracker" target="_blank"
                       class="group p-6 bg-gradient-to-br from-teal-50 to-cyan-50 border border-teal-200 rounded-xl hover:from-teal-100 hover:to-cyan-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">👁️ Preview</h3>
                                <p class="text-sm text-gray-600">Bekijk StemmenTracker</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Politiek & Data Beheer -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-violet-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Politiek & Data Beheer
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
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
                    
                    <a href="#" onclick="runPeilingenUpdate()" 
                       class="group p-6 bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl hover:from-yellow-100 hover:to-amber-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">📊 Update Peilingen</h3>
                                <p class="text-sm text-gray-600">Bijwerken partij peilingen</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="polls-beheer.php" 
                       class="group p-6 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl hover:from-amber-100 hover:to-orange-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">📊 Polls Beheer</h3>
                                <p class="text-sm text-gray-600">Beheer blog poll stemcijfers</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Frontend & Content Beheer -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Frontend & Content Beheer
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <a href="auto-likes-beheer.php" 
                       class="group p-6 bg-gradient-to-br from-pink-50 to-red-50 border border-pink-200 rounded-xl hover:from-pink-100 hover:to-red-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Auto Likes Beheer</h3>
                                <p class="text-sm text-gray-600">Beheer automatische likes</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" onclick="runAutoLikesMigration()" 
                       class="group p-6 bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200 rounded-xl hover:from-rose-100 hover:to-pink-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Auto Likes Migratie</h3>
                                <p class="text-sm text-gray-600">Installeer database tabellen</p>
                            </div>
                        </div>
                    </a>

                    <a href="../scripts/fix_live_blog_categories.php" 
                       class="group p-6 bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl hover:from-indigo-100 hover:to-purple-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">🏷️ Fix Live Blog Categorieën</h3>
                                <p class="text-sm text-gray-600">Directe database migration</p>
                            </div>
                        </div>
                    </a>

                    <a href="test-comments-beheer.php" 
                       class="group p-6 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">🎭 Test Reacties</h3>
                                <p class="text-sm text-gray-600">Beheer realistische test reacties</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" onclick="createPollTables()" 
                       class="group p-6 bg-gradient-to-br from-lime-50 to-green-50 border border-lime-200 rounded-xl hover:from-lime-100 hover:to-green-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-lime-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">🗃️ Setup Poll Tables</h3>
                                <p class="text-sm text-gray-600">Installeer poll database tabellen</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Backend & Automation -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    Backend & Automation
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
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
                </div>
            </div>
                
                <!-- Tijdelijk verborgen scripts -->
                <div style="display: none;">
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
                    
                    <a href="../scripts/run_comment_likes_migration.php" 
                       class="group p-6 bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200 rounded-xl hover:from-rose-100 hover:to-pink-100 transition-all duration-300 card-hover">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-rose-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">❤️ Comment Likes</h3>
                                <p class="text-sm text-gray-600">Setup "liked by creator" functie</p>
                            </div>
                        </div>
                    </a>
                
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Peilingen update functie
function runPeilingenUpdate() {
    if (confirm('Weet je zeker dat je de partij peilingen wilt bijwerken met de laatste data?')) {
        // Toon loading indicator
        const button = event.target.closest('a');
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Updating...</h3>
                    <p class="text-sm text-gray-600">Even geduld</p>
                </div>
            </div>
        `;
        
        // Maak AJAX request
        fetch('../scripts/update_peilingen.php')
            .then(response => response.text())
            .then(data => {
                button.innerHTML = originalContent;
                if (data.includes('succesvol') || data.includes('Update voltooid')) {
                    alert('Peilingen succesvol bijgewerkt!\\n\\n' + data);
                    // Refresh de pagina om nieuwe data te tonen
                    window.location.reload();
                } else {
                    alert('Er is een fout opgetreden:\\n\\n' + data);
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                alert('Network error: ' + error.message);
            });
    }
}

// Poll tables setup functie
function createPollTables() {
    if (confirm('Weet je zeker dat je de poll database tabellen wilt installeren/bijwerken?')) {
        // Toon loading indicator
        const button = event.target.closest('a');
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-lime-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Installeren...</h3>
                    <p class="text-sm text-gray-600">Even geduld</p>
                </div>
            </div>
        `;
        
        // Maak AJAX request
        fetch('../scripts/create_poll_tables.php')
            .then(response => response.text())
            .then(data => {
                button.innerHTML = originalContent;
                if (data.includes('success') || data.includes('Tabellen aangemaakt')) {
                    alert('Poll tabellen succesvol geïnstalleerd!\\n\\n' + data);
                } else {
                    alert('Er is een fout opgetreden:\\n\\n' + data);
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                alert('Network error: ' + error.message);
            });
    }
}

// StemmenTracker tables setup functie
function createStemmenTrackerTables() {
    if (confirm('Weet je zeker dat je de StemmenTracker database tabellen wilt installeren/bijwerken?\\n\\nDit zal de volgende tabellen aanmaken:\\n- stemmentracker_moties\\n- stemmentracker_votes\\n- stemmentracker_themas\\n- stemmentracker_motie_themas')) {
        // Toon loading indicator
        const button = event.target.closest('a');
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Installeren...</h3>
                    <p class="text-sm text-gray-600">Database tabellen aanmaken</p>
                </div>
            </div>
        `;
        
        // Maak AJAX request
        fetch('../scripts/create_stemmentracker_tables.php')
            .then(response => response.text())
            .then(data => {
                button.innerHTML = originalContent;
                if (data.includes('✓') || data.includes('succesvol') || data.includes('Created table')) {
                    alert('StemmenTracker database tabellen succesvol geïnstalleerd!\\n\\n' + data.replace(/✓/g, '✅'));
                    // Refresh de pagina om nieuwe data te tonen
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert('Er is een fout opgetreden bij het installeren van de tabellen:\\n\\n' + data);
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                alert('Network error bij het installeren van StemmenTracker tabellen: ' + error.message);
            });
    }
}

// Auto Likes migratie functie
function runAutoLikesMigration() {
    if (confirm('Weet je zeker dat je de Auto Likes database tabellen wilt installeren?\n\nDit zal de volgende tabellen aanmaken:\n- auto_likes_config\n- auto_likes_log\n- auto_likes_settings')) {
        // Toon loading indicator
        const button = event.target.closest('a');
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Installeren...</h3>
                    <p class="text-sm text-gray-600">Database tabellen aanmaken</p>
                </div>
            </div>
        `;
        
        // Maak AJAX request
        fetch('../scripts/create_auto_likes_tables.php')
            .then(response => response.text())
            .then(data => {
                button.innerHTML = originalContent;
                if (data.includes('voltooid') || data.includes('aangemaakt') || data.includes('Migratie')) {
                    alert('Auto Likes database tabellen succesvol geinstalleerd!\n\n' + data);
                    // Refresh de pagina
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert('Er is een fout opgetreden:\n\n' + data);
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                alert('Network error: ' + error.message);
            });
    }
}

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