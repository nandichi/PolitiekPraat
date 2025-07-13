<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../models/PartyModel.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Haal de partij key uit de URL
$partyKey = isset($_GET['party']) ? $_GET['party'] : 'PVV';

// Krijg partij informatie uit database
$partyModel = new PartyModel();
$party = $partyModel->getParty($partyKey);

// Als partij niet bestaat, redirect naar partijen overzicht
if (!$party) {
    header('Location: /partijen');
    exit;
}

$title = $party['name'] . " - " . $party['leader'];
$description = "Alles over " . $party['name'] . " en partijleider " . $party['leader'] . ". Bekijk standpunten, peilingen en meer.";

// Helper functie voor kleur
function getPartyColor($partyKey) {
    $colors = [
        'PVV' => '#003d82',
        'VVD' => '#ff6200',
        'GL-PvdA' => '#80bb3a',
        'NSC' => '#1e3a8a',
        'BBB' => '#7cb342',
        'D66' => '#009bdc',
        'SP' => '#ff1a1a',
        'PvdD' => '#006f3c',
        'CDA' => '#005f5f',
        'JA21' => '#001f3f',
        'SGP' => '#ff8c00',
        'FvD' => '#8b0000',
        'DENK' => '#00b8d4',
        'Volt' => '#502379',
        'CU' => '#006f4f'
    ];
    return $colors[$partyKey] ?? '#6b7280';
}

include_once BASE_PATH . '/views/templates/header.php';
?>

<main class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-20 overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Background decoration -->
        <div class="absolute top-20 left-10 w-80 h-80 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="container mx-auto px-4 relative">
            <div class="max-w-5xl mx-auto">
                <!-- Back button -->
                <div class="mb-8">
                    <a href="<?php echo URLROOT; ?>/partijen" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 hover:bg-white/20 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Terug naar partijen
                    </a>
                </div>
                
                <!-- Party info -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left column -->
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                            <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                            <span class="text-white/90 text-sm font-medium">Politieke partij</span>
                        </div>
                        
                        <h1 class="text-4xl lg:text-6xl font-black text-white mb-4 tracking-tight">
                            <?php echo htmlspecialchars($party['slug']); ?>
                        </h1>
                        
                        <p class="text-xl text-blue-100 mb-6 font-medium">
                            <?php echo htmlspecialchars($party['name']); ?>
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                <div class="text-2xl font-bold text-white"><?php echo $party['current_seats']; ?></div>
                                <div class="text-blue-200 text-sm">Huidige zetels</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                <div class="text-2xl font-bold text-white"><?php echo $party['polling']['seats']; ?></div>
                                <div class="text-blue-200 text-sm">Peilingen</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right column -->
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20">
                            <div class="text-center mb-6">
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl">
                                    <img src="<?php echo $party['leader_photo']; ?>" 
                                         alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                         class="w-full h-full object-cover">
                                </div>
                                <h2 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($party['leader']); ?></h2>
                                <p class="text-blue-200">Partijleider</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl overflow-hidden border-2 border-white/20 shadow-lg bg-white/10 flex items-center justify-center">
                                    <img src="<?php echo $party['logo']; ?>" 
                                         alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                         class="w-16 h-16 object-contain">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-6xl mx-auto">
            <!-- Description Section -->
            <section class="mb-16">
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                        Over <?php echo htmlspecialchars($party['name']); ?>
                    </h2>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        <?php echo htmlspecialchars($party['description']); ?>
                    </p>
                </div>
            </section>

            <!-- Leader Info Section -->
            <section class="mb-16">
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <div class="grid lg:grid-cols-3 gap-8 items-center">
                        <div class="text-center lg:text-left">
                            <div class="w-32 h-32 mx-auto lg:mx-0 mb-4 rounded-full overflow-hidden border-4 shadow-2xl" 
                                 style="border-color: <?php echo getPartyColor($party['slug']); ?>;">
                                <img src="<?php echo $party['leader_photo']; ?>" 
                                     alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                     class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($party['leader']); ?>
                            </h3>
                            <p class="text-gray-600">Partijleider <?php echo htmlspecialchars($party['name']); ?></p>
                        </div>
                        
                        <div class="lg:col-span-2">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                                <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                                Leiderschap
                            </h2>
                            <p class="text-gray-700 text-lg leading-relaxed">
                                <?php echo htmlspecialchars($party['leader_info']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Standpoints Section -->
            <section class="mb-16">
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                        <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                        Standpunten
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ($party['standpoints'] as $topic => $standpoint): ?>
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                                    <div class="w-2 h-2 rounded-full mr-3" style="background-color: <?php echo getPartyColor($party['slug']); ?>"></div>
                                    <?php echo htmlspecialchars($topic); ?>
                                </h3>
                                <p class="text-gray-700 leading-relaxed">
                                    <?php echo htmlspecialchars($standpoint); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Polling & Seats Section -->
            <section class="mb-16">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Current Seats -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                            Huidige Zetels
                        </h2>
                        <div class="text-center">
                            <div class="text-6xl font-black mb-4" style="color: <?php echo getPartyColor($party['slug']); ?>">
                                <?php echo $party['current_seats']; ?>
                            </div>
                            <p class="text-gray-600 text-lg">van 150 zetels in de Tweede Kamer</p>
                            <div class="mt-6">
                                <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000" 
                                         style="width: <?php echo ($party['current_seats'] / 150) * 100; ?>%; background-color: <?php echo getPartyColor($party['slug']); ?>">
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    <?php echo number_format(($party['current_seats'] / 150) * 100, 1); ?>% van alle zetels
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Polling -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                            Peilingen
                        </h2>
                        <div class="text-center">
                            <div class="text-6xl font-black mb-4" style="color: <?php echo getPartyColor($party['slug']); ?>">
                                <?php echo $party['polling']['seats']; ?>
                            </div>
                            <p class="text-gray-600 text-lg">verwachte zetels</p>
                            
                            <!-- Trend indicator -->
                            <div class="mt-6">
                                <?php 
                                $change = $party['polling']['change'];
                                $changeClass = $change > 0 ? 'text-green-600 bg-green-100' : ($change < 0 ? 'text-red-600 bg-red-100' : 'text-gray-600 bg-gray-100');
                                $changeIcon = $change > 0 ? '↗' : ($change < 0 ? '↘' : '→');
                                ?>
                                <div class="inline-flex items-center px-4 py-2 rounded-full <?php echo $changeClass; ?> font-bold">
                                    <span class="mr-2"><?php echo $changeIcon; ?></span>
                                    <span><?php echo $change > 0 ? '+' : ''; ?><?php echo $change; ?></span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    <?php echo $change > 0 ? 'Winst' : ($change < 0 ? 'Verlies' : 'Gelijk'); ?> ten opzichte van huidige zetels
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Perspectives Section -->
            <section class="mb-16">
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                        <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full mr-4"></div>
                        Politieke Perspectieven
                    </h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Left perspective -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                            <h3 class="text-xl font-bold text-blue-800 mb-4 flex items-center">
                                <div class="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                Links Perspectief
                            </h3>
                            <p class="text-blue-900 leading-relaxed">
                                <?php echo htmlspecialchars($party['perspectives']['left']); ?>
                            </p>
                        </div>
                        
                        <!-- Right perspective -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                            <h3 class="text-xl font-bold text-red-800 mb-4 flex items-center">
                                <div class="w-3 h-3 bg-red-600 rounded-full mr-3"></div>
                                Rechts Perspectief
                            </h3>
                            <p class="text-red-900 leading-relaxed">
                                <?php echo htmlspecialchars($party['perspectives']['right']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation -->
            <section class="text-center">
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Meer ontdekken</h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <a href="<?php echo URLROOT; ?>/partijen" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Alle Partijen
                        </a>
                        <a href="<?php echo URLROOT; ?>/stemwijzer" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m4-8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-6V9a2 2 0 012-2h2a2 2 0 012 2v2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Stemwijzer
                        </a>
                        <a href="<?php echo URLROOT; ?>/programma-vergelijker" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Vergelijker
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include_once BASE_PATH . '/views/templates/footer.php'; ?> 