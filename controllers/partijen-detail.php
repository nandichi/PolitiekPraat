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

// Helper function for lighter color variants
function lightenColor($hex, $percent) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = min(255, $r + ($percent / 100) * (255 - $r));
    $g = min(255, $g + ($percent / 100) * (255 - $g));
    $b = min(255, $b + ($percent / 100) * (255 - $b));
    
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

include_once BASE_PATH . '/views/templates/header.php';

$partyColor = getPartyColor($partyKey);
$partyColorLight = lightenColor($partyColor, 40);
$partyColorDark = lightenColor($partyColor, -20);
?>

<main class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-50">
    <!-- Dynamic Party Hero Section -->
    <section class="relative min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
        <!-- Dynamic Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, <?php echo $partyColor; ?> 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute top-10 left-10 w-72 h-72 rounded-full blur-3xl animate-pulse opacity-20" style="background: linear-gradient(45deg, <?php echo $partyColor; ?>, <?php echo $partyColorLight; ?>);"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full blur-3xl animate-pulse opacity-15" style="background: linear-gradient(-45deg, <?php echo $partyColorLight; ?>, <?php echo $partyColor; ?>); animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/3 w-64 h-64 rounded-full blur-2xl animate-pulse opacity-10" style="background: <?php echo $partyColor; ?>; animation-delay: 4s;"></div>
        
        <!-- Navigation Bar -->
        <nav class="relative z-20 p-6">
            <div class="container mx-auto">
                <a href="<?php echo URLROOT; ?>/partijen" 
                   class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-md rounded-full text-white/90 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/30 group">
                    <svg class="w-5 h-5 mr-3 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Terug naar alle partijen</span>
                </a>
            </div>
        </nav>
        
        <!-- Hero Content -->
        <div class="container mx-auto px-6 py-20 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center min-h-[60vh]">
                    <!-- Left Column - Party Information -->
                    <div class="text-center lg:text-left space-y-8">
                        
                        <!-- Party Name -->
                        <div>
                            <h1 class="text-5xl lg:text-7xl font-black text-white mb-6 tracking-tight leading-none">
                                <span class="block text-4xl lg:text-5xl text-white/70 font-medium mb-2"><?php echo strtoupper($partyKey); ?></span>
                                <span class="bg-gradient-to-r from-white via-white to-white/80 bg-clip-text text-transparent">
                                    <?php echo htmlspecialchars($party['name']); ?>
                                </span>
                            </h1>
                            
                            <p class="text-xl lg:text-2xl text-blue-100 font-light leading-relaxed max-w-2xl">
                                <?php echo htmlspecialchars($party['description']); ?>
                            </p>
                        </div>
                        
                        <!-- Key Stats -->
                        <div class="grid grid-cols-2 gap-6 max-w-md mx-auto lg:mx-0">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                                <div class="text-3xl font-black text-white mb-2"><?php echo $party['current_seats']; ?></div>
                                <div class="text-blue-200 text-sm font-medium uppercase tracking-wider">Huidige Zetels</div>
                                <div class="text-white/60 text-xs mt-1">Tweede Kamer</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                                <div class="text-3xl font-black text-white mb-2"><?php echo $party['polling']['seats']; ?></div>
                                <div class="text-blue-200 text-sm font-medium uppercase tracking-wider">Peilingen</div>
                                <?php if (isset($party['polling']['change'])): ?>
                                    <?php $change = $party['polling']['change']; ?>
                                    <div class="text-xs mt-1 <?php echo $change > 0 ? 'text-green-300' : ($change < 0 ? 'text-red-300' : 'text-white/60'); ?>">
                                        <?php echo $change > 0 ? '+' : ''; ?><?php echo $change; ?> trend
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-8">
                            <a href="#standpunten" 
                               class="group relative inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-r opacity-0 group-hover:opacity-10 transition-opacity duration-300" style="background: linear-gradient(45deg, <?php echo $partyColor; ?>, <?php echo $partyColorLight; ?>);"></div>
                                <svg class="w-5 h-5 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="relative z-10">Bekijk Standpunten</span>
                            </a>
                            
                            <a href="#leiderschap" 
                               class="group relative inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl border border-white/20 hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Ontmoet de Leider</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column - Visual Elements -->
                    <div class="relative">
                        <div class="relative z-10">
                            <!-- Leader Showcase -->
                            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl mb-8">
                                <div class="text-center">
                                    <div class="relative inline-block mb-6">
                                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/30 shadow-2xl mx-auto">
                                            <img src="<?php echo $party['leader_photo']; ?>" 
                                                 alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center shadow-xl" style="background-color: <?php echo $partyColor; ?>;">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <h2 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($party['leader']); ?></h2>
                                    <p class="text-blue-200 text-lg font-medium mb-4">Partijleider</p>
                                    
                                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                                        <p class="text-white/80 text-sm leading-relaxed">
                                            "<?php echo htmlspecialchars(substr($party['leader_info'], 0, 120)); ?>..."
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Party Logo Showcase -->
                            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 shadow-xl">
                                <div class="text-center">
                                    <div class="w-20 h-20 mx-auto rounded-2xl overflow-hidden border-2 border-white/20 shadow-lg bg-white/20 flex items-center justify-center mb-4">
                                        <img src="<?php echo $party['logo']; ?>" 
                                             alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                             class="w-16 h-16 object-contain">
                                    </div>
                                    <p class="text-white/80 text-sm font-medium">Officieel Partijlogo</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-20 h-20 rounded-full blur-xl opacity-30" style="background-color: <?php echo $partyColor; ?>; animation: float 6s ease-in-out infinite;"></div>
                        <div class="absolute bottom-10 left-10 w-16 h-16 rounded-full blur-lg opacity-25" style="background-color: <?php echo $partyColorLight; ?>; animation: float 8s ease-in-out infinite reverse;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div id="content" class="relative z-10 bg-white">
        <div class="container mx-auto px-6 py-20">
            <div class="max-w-7xl mx-auto space-y-20">
                
                <!-- Leadership Section -->
                <section id="leiderschap" class="scroll-mt-20">
                    <div class="grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-5">
                            <div class="relative">
                                <div class="aspect-square rounded-3xl overflow-hidden shadow-2xl border-4" style="border-color: <?php echo $partyColor; ?>;">
                                    <img src="<?php echo $party['leader_photo']; ?>" 
                                         alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-4 shadow-xl border border-gray-100">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border-2" style="border-color: <?php echo $partyColor; ?>;">
                                        <img src="<?php echo $party['logo']; ?>" 
                                             alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                             class="w-full h-full object-contain bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-7">
                            <div class="space-y-6">
                                <div>
                                    <div class="inline-flex items-center px-4 py-2 rounded-full border-2 mb-6" style="border-color: <?php echo $partyColor; ?>; background-color: <?php echo $partyColorLight; ?>20;">
                                        <div class="w-2 h-2 rounded-full mr-3" style="background-color: <?php echo $partyColor; ?>;"></div>
                                        <span class="font-semibold text-sm uppercase tracking-wider" style="color: <?php echo $partyColor; ?>;">Partijleiderschap</span>
                                    </div>
                                    
                                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6 leading-tight">
                                        <?php echo htmlspecialchars($party['leader']); ?>
                                    </h2>
                                    
                                    <p class="text-xl text-gray-600 font-medium mb-8">
                                        Partijleider van <?php echo htmlspecialchars($party['name']); ?>
                                    </p>
                                </div>
                                
                                <div class="prose prose-lg prose-gray max-w-none">
                                    <p class="text-gray-700 leading-relaxed text-lg">
                                        <?php echo htmlspecialchars($party['leader_info']); ?>
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-6 pt-6">
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <h3 class="font-bold text-gray-900 mb-2">Politieke Ervaring</h3>
                                        <p class="text-gray-600 text-sm">Leider sinds de oprichting van de partij</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <h3 class="font-bold text-gray-900 mb-2">Zetelvertegenwoordiging</h3>
                                        <p class="text-gray-600 text-sm"><?php echo $party['current_seats']; ?> zetels in de Tweede Kamer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Standpoints Section -->
                <section id="standpunten" class="scroll-mt-20">
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-100 relative overflow-hidden">
                        <!-- Background decoration -->
                        <div class="absolute top-0 right-0 w-40 h-40 rounded-full blur-3xl opacity-5" style="background-color: <?php echo $partyColor; ?>;"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-4 py-2 rounded-full border-2 mb-6" style="border-color: <?php echo $partyColor; ?>; background-color: <?php echo $partyColorLight; ?>20;">
                                    <div class="w-2 h-2 rounded-full mr-3" style="background-color: <?php echo $partyColor; ?>;"></div>
                                    <span class="font-semibold text-sm uppercase tracking-wider" style="color: <?php echo $partyColor; ?>;">Politieke Standpunten</span>
                                </div>
                                
                                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                                    Onze Standpunten
                                </h2>
                                
                                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                    Ontdek waar <?php echo htmlspecialchars($party['name']); ?> voor staat en hoe wij Nederland willen vormgeven
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php if (isset($party['standpoints']) && is_array($party['standpoints'])): ?>
                                    <?php foreach ($party['standpoints'] as $topic => $standpoint): ?>
                                        <div class="group relative bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                            <div class="absolute top-4 right-4 w-3 h-3 rounded-full" style="background-color: <?php echo $partyColor; ?>;"></div>
                                            
                                            <div class="mb-4">
                                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300" style="background-color: <?php echo $partyColorLight; ?>20;">
                                                    <svg class="w-6 h-6" style="color: <?php echo $partyColor; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                
                                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                                    <?php echo htmlspecialchars($topic); ?>
                                                </h3>
                                            </div>
                                            
                                            <p class="text-gray-700 leading-relaxed">
                                                <?php echo htmlspecialchars($standpoint); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-span-full text-center py-12">
                                        <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6" style="background-color: <?php echo $partyColorLight; ?>20;">
                                            <svg class="w-10 h-10" style="color: <?php echo $partyColor; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-lg">Standpunten worden binnenkort toegevoegd.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Performance Analytics Section -->
                <section class="scroll-mt-20">
                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Current Seats Card -->
                        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 relative overflow-hidden hover:shadow-2xl transition-all duration-300">
                            <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-2xl opacity-10" style="background-color: <?php echo $partyColor; ?>;"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full border-2" style="border-color: <?php echo $partyColor; ?>; background-color: <?php echo $partyColorLight; ?>20;">
                                        <div class="w-2 h-2 rounded-full mr-2" style="background-color: <?php echo $partyColor; ?>;"></div>
                                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: <?php echo $partyColor; ?>;">Tweede Kamer</span>
                                    </div>
                                    
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: <?php echo $partyColorLight; ?>20;">
                                        <svg class="w-6 h-6" style="color: <?php echo $partyColor; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Huidige Zetels</h3>
                                
                                <div class="text-center mb-6">
                                    <div class="text-6xl font-black mb-2" style="color: <?php echo $partyColor; ?>">
                                        <?php echo $party['current_seats']; ?>
                                    </div>
                                    <p class="text-gray-600 text-lg">van 150 zetels in de Tweede Kamer</p>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="bg-gray-100 rounded-full h-4 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out" 
                                             style="width: <?php echo ($party['current_seats'] / 150) * 100; ?>%; background-color: <?php echo $partyColor; ?>;">
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">0 zetels</span>
                                        <span class="font-bold" style="color: <?php echo $partyColor; ?>;">
                                            <?php echo number_format(($party['current_seats'] / 150) * 100, 1); ?>%
                                        </span>
                                        <span class="text-gray-500">150 zetels</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Polling Card -->
                        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 relative overflow-hidden hover:shadow-2xl transition-all duration-300">
                            <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full blur-2xl opacity-10" style="background-color: <?php echo $partyColor; ?>;"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full border-2" style="border-color: <?php echo $partyColor; ?>; background-color: <?php echo $partyColorLight; ?>20;">
                                        <div class="w-2 h-2 rounded-full mr-2" style="background-color: <?php echo $partyColor; ?>;"></div>
                                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: <?php echo $partyColor; ?>;">Peilingen</span>
                                    </div>
                                    
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: <?php echo $partyColorLight; ?>20;">
                                        <svg class="w-6 h-6" style="color: <?php echo $partyColor; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Verwachte Zetels</h3>
                                
                                <?php if (isset($party['polling']['seats'])): ?>
                                    <div class="text-center mb-6">
                                        <div class="text-6xl font-black mb-2" style="color: <?php echo $partyColor; ?>">
                                            <?php echo $party['polling']['seats']; ?>
                                        </div>
                                        <p class="text-gray-600 text-lg">verwachte zetels volgens peilingen</p>
                                    </div>
                                    
                                    <?php if (isset($party['polling']['change'])): ?>
                                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                                            <?php 
                                            $change = $party['polling']['change'];
                                            $changeClass = $change > 0 ? 'text-green-600 bg-green-100' : ($change < 0 ? 'text-red-600 bg-red-100' : 'text-gray-600 bg-gray-100');
                                            $changeIcon = $change > 0 ? '↗' : ($change < 0 ? '↘' : '→');
                                            $changeText = $change > 0 ? 'Winst' : ($change < 0 ? 'Verlies' : 'Stabiel');
                                            ?>
                                            <div class="inline-flex items-center px-4 py-2 rounded-full <?php echo $changeClass; ?> font-bold text-lg mb-2">
                                                <span class="mr-2 text-xl"><?php echo $changeIcon; ?></span>
                                                <span><?php echo $change > 0 ? '+' : ''; ?><?php echo $change; ?></span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                <?php echo $changeText; ?> ten opzichte van huidige zetels
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center text-gray-500 py-8">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <p class="text-lg">Geen peilingdata beschikbaar</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Political Perspectives Section -->
                <section class="scroll-mt-20">
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-100 relative overflow-hidden">
                        <div class="text-center mb-12">
                            <div class="inline-flex items-center px-4 py-2 rounded-full border-2 mb-6" style="border-color: <?php echo $partyColor; ?>; background-color: <?php echo $partyColorLight; ?>20;">
                                <div class="w-2 h-2 rounded-full mr-3" style="background-color: <?php echo $partyColor; ?>;"></div>
                                <span class="font-semibold text-sm uppercase tracking-wider" style="color: <?php echo $partyColor; ?>;">Politieke Analyse</span>
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                                Verschillende Perspectieven
                            </h2>
                            
                            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                Hoe wordt <?php echo htmlspecialchars($party['name']); ?> gezien vanuit verschillende politieke invalshoeken?
                            </p>
                        </div>

                        <div class="grid lg:grid-cols-2 gap-8">
                            <!-- Left Perspective -->
                            <div class="group relative">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border-2 border-blue-200 hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-blue-800">Links Perspectief</h3>
                                            <p class="text-blue-600 text-sm">Progressieve kijk op de partij</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white/70 rounded-xl p-6 border border-blue-100">
                                        <p class="text-blue-900 leading-relaxed text-lg">
                                            <?php echo htmlspecialchars($party['perspectives']['left'] ?? 'Geen perspectief beschikbaar.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Perspective -->
                            <div class="group relative">
                                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-8 border-2 border-red-200 hover:border-red-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-red-800">Rechts Perspectief</h3>
                                            <p class="text-red-600 text-sm">Conservatieve kijk op de partij</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white/70 rounded-xl p-6 border border-red-100">
                                        <p class="text-red-900 leading-relaxed text-lg">
                                            <?php echo htmlspecialchars($party['perspectives']['right'] ?? 'Geen perspectief beschikbaar.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- AI Analysis Section -->
                <section id="ai-analyse" class="scroll-mt-20">
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-3xl shadow-2xl p-8 lg:p-12 border border-purple-100 relative overflow-hidden">
                        <!-- Background decoration -->
                        <div class="absolute top-0 right-0 w-40 h-40 rounded-full blur-3xl opacity-5 bg-purple-500"></div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full blur-2xl opacity-5 bg-indigo-500"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-4 py-2 rounded-full border-2 border-purple-300 bg-purple-100 mb-6">
                                    <div class="w-2 h-2 rounded-full mr-3 bg-purple-600 animate-pulse"></div>
                                    <span class="font-semibold text-sm uppercase tracking-wider text-purple-700">PolitiekPraat Analyse</span>
                                </div>
                                
                                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                                    Diepgaande Analyse
                                </h2>
                                
                                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                    Krijg een uitgebreide, objectieve analyse van de voor- en nadelen van <?php echo htmlspecialchars($party['name']); ?> en <?php echo htmlspecialchars($party['leader']); ?>
                                </p>
                            </div>

                            <!-- Analysis Options -->
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                <!-- Party Analysis Card -->
                                <div class="bg-white rounded-2xl p-8 border border-purple-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                            Partij Analyse
                                        </h3>
                                        
                                        <p class="text-gray-600 mb-8 leading-relaxed">
                                            Krijg een objectieve beoordeling van de voor- en nadelen van <?php echo htmlspecialchars($party['name']); ?> als politieke partij.
                                        </p>
                                        
                                        <button id="analyze-party-btn" 
                                                data-party="<?php echo htmlspecialchars($partyKey); ?>"
                                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold rounded-2xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <span class="analyze-btn-text">Analyseer Partij</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Leader Analysis Card -->
                                <div class="bg-white rounded-2xl p-8 border border-purple-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                            Leider Analyse
                                        </h3>
                                        
                                        <p class="text-gray-600 mb-8 leading-relaxed">
                                            Ontdek de sterke en zwakke punten van <?php echo htmlspecialchars($party['leader']); ?> als politiek leider.
                                        </p>
                                        
                                        <button id="analyze-leader-btn" 
                                                data-party="<?php echo htmlspecialchars($partyKey); ?>"
                                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-2xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <span class="analyze-btn-text">Analyseer Leider</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Analysis Options -->
                            <div class="grid md:grid-cols-3 gap-6 mb-8">
                                <!-- Voter Profile Card -->
                                <div class="bg-white rounded-2xl p-6 border border-green-200 hover:border-green-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                                            Kiezer Profiel
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                            Wie stemt er op <?php echo htmlspecialchars($party['name']); ?>? Ontdek het typische kiezersprofiel.
                                        </p>
                                        
                                        <button id="analyze-voter-btn" 
                                                data-party="<?php echo htmlspecialchars($partyKey); ?>"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span class="analyze-btn-text">Analyseer Kiezers</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Timeline Card -->
                                <div class="bg-white rounded-2xl p-6 border border-orange-200 hover:border-orange-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                                            Politieke Geschiedenis
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                            Bekijk de belangrijkste momenten in de geschiedenis van <?php echo htmlspecialchars($party['name']); ?>.
                                        </p>
                                        
                                        <button id="analyze-timeline-btn" 
                                                data-party="<?php echo htmlspecialchars($partyKey); ?>"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m4-8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-6V9a2 2 0 012-2h2a2 2 0 012 2v2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span class="analyze-btn-text">Bekijk Timeline</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Q&A Card -->
                                <div class="bg-white rounded-2xl p-6 border border-blue-200 hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                                            Vraag & Antwoord
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                            Stel een specifieke vraag over <?php echo htmlspecialchars($party['name']); ?> en krijg een uitgebreid antwoord.
                                        </p>
                                        
                                        <button id="show-qa-btn" 
                                                data-party="<?php echo htmlspecialchars($partyKey); ?>"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            <span>Stel een Vraag</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Q&A Input Section (Initially Hidden) -->
                            <div id="qa-input-section" class="hidden mb-8">
                                <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Stel je vraag over <?php echo htmlspecialchars($party['name']); ?>
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="question-input" class="block text-sm font-medium text-gray-700 mb-2">
                                                Wat wil je weten over deze partij?
                                            </label>
                                            <textarea 
                                                id="question-input" 
                                                rows="3" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                                placeholder="Bijvoorbeeld: Wat is hun standpunt over klimaatverandering? Hoe denken ze over de economie?"
                                            ></textarea>
                                        </div>
                                        
                                        <div class="flex gap-3">
                                            <button 
                                                id="submit-question-btn"
                                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                                <span class="question-btn-text">Verstuur Vraag</span>
                                            </button>
                                            
                                            <button 
                                                id="cancel-question-btn"
                                                class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300">
                                                Annuleren
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Analysis Results Container -->
                            <div id="analysis-results" class="hidden">
                                <div class="bg-white rounded-2xl p-8 border border-purple-200 shadow-lg">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 id="analysis-title" class="text-2xl font-bold text-gray-900">
                                            Analyse Resultaten
                                        </h3>
                                        <button id="close-analysis" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Loading State -->
                                    <div id="analysis-loading" class="text-center py-12">
                                        <div class="inline-flex items-center px-6 py-3 bg-purple-100 rounded-full text-purple-700 font-medium">
                                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-purple-600 mr-3"></div>
                                            Analyse wordt geladen...
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div id="analysis-content" class="hidden">
                                        <div class="prose prose-lg max-w-none">
                                            <!-- Content will be inserted here by JavaScript -->
                                        </div>
                                    </div>
                                    
                                    <!-- Error State -->
                                    <div id="analysis-error" class="hidden text-center py-8">
                                        <div class="text-red-600 mb-4">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Analyse niet beschikbaar</h4>
                                        <p class="text-gray-600" id="analysis-error-message">Er is een fout opgetreden bij het genereren van de analyse.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Info Note -->
                            <div class="mt-8 text-center">
                                <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full border border-blue-200">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-blue-700 text-sm font-medium">Analyse is objectief en gebaseerd op publiek beschikbare informatie</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Navigation & Related Actions -->
                <section class="text-center scroll-mt-20">
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-100 relative overflow-hidden">
                        <!-- Background decoration -->
                        <div class="absolute top-0 left-0 w-40 h-40 rounded-full blur-3xl opacity-5" style="background-color: <?php echo $partyColor; ?>;"></div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 rounded-full blur-2xl opacity-5" style="background-color: <?php echo $partyColorLight; ?>;"></div>
                        
                        <div class="relative z-10">
                            <div class="max-w-3xl mx-auto mb-12">
                                <h2 class="text-4xl font-black text-gray-900 mb-6">
                                    Ontdek Meer
                                </h2>
                                <p class="text-xl text-gray-600">
                                    Verdiep je verder in de Nederlandse politiek en vergelijk verschillende partijen
                                </p>
                            </div>

                            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                                <a href="<?php echo URLROOT; ?>/partijen" 
                                   class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300" style="background-color: <?php echo $partyColorLight; ?>20;">
                                        <svg class="w-8 h-8" style="color: <?php echo $partyColor; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Alle Partijen</h3>
                                    <p class="text-gray-600 text-sm">Vergelijk alle Nederlandse politieke partijen</p>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/stemwijzer" 
                                   class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-orange-50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m4-8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-6V9a2 2 0 012-2h2a2 2 0 012 2v2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Stemwijzer</h3>
                                    <p class="text-gray-600 text-sm">Ontdek welke partij bij je past</p>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/programma-vergelijker" 
                                   class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-green-50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Vergelijker</h3>
                                    <p class="text-gray-600 text-sm">Vergelijk partijprogramma's</p>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/nieuws" 
                                   class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-2">Nieuws</h3>
                                    <p class="text-gray-600 text-sm">Politiek nieuws en actualiteiten</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</main>

<!-- Custom CSS for animations -->
<style>
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.scroll-mt-20 {
    scroll-margin-top: 5rem;
}

html {
    scroll-behavior: smooth;
}

/* Gradient text animation */
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.bg-clip-text {
    background-clip: text;
    -webkit-background-clip: text;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 PolitiekPraat Analysis functionality initialized');
    
    // Get elements
    const analyzePartyBtn = document.getElementById('analyze-party-btn');
    const analyzeLeaderBtn = document.getElementById('analyze-leader-btn');
    const analyzeVoterBtn = document.getElementById('analyze-voter-btn');
    const analyzeTimelineBtn = document.getElementById('analyze-timeline-btn');
    const showQaBtn = document.getElementById('show-qa-btn');
    const analysisResults = document.getElementById('analysis-results');
    const analysisTitle = document.getElementById('analysis-title');
    const analysisLoading = document.getElementById('analysis-loading');
    const analysisContent = document.getElementById('analysis-content');
    const analysisError = document.getElementById('analysis-error');
    const analysisErrorMessage = document.getElementById('analysis-error-message');
    const closeAnalysisBtn = document.getElementById('close-analysis');
    
    // Q&A elements
    const qaInputSection = document.getElementById('qa-input-section');
    const questionInput = document.getElementById('question-input');
    const submitQuestionBtn = document.getElementById('submit-question-btn');
    const cancelQuestionBtn = document.getElementById('cancel-question-btn');
    
    // Party analysis
    if (analyzePartyBtn) {
        analyzePartyBtn.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            performAnalysis('party', partyKey);
        });
    }
    
    // Leader analysis
    if (analyzeLeaderBtn) {
        analyzeLeaderBtn.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            performAnalysis('leader', partyKey);
        });
    }
    
    // Voter profile analysis
    if (analyzeVoterBtn) {
        analyzeVoterBtn.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            performAnalysis('voter_profile', partyKey);
        });
    }
    
    // Timeline analysis
    if (analyzeTimelineBtn) {
        analyzeTimelineBtn.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            performAnalysis('timeline', partyKey);
        });
    }
    
    // Show Q&A input
    if (showQaBtn) {
        showQaBtn.addEventListener('click', function() {
            toggleQAInput(true);
        });
    }
    
    // Submit question
    if (submitQuestionBtn) {
        submitQuestionBtn.addEventListener('click', function() {
            const question = questionInput.value.trim();
            if (question) {
                const partyKey = showQaBtn.getAttribute('data-party');
                performQuestionAnalysis(partyKey, question);
            } else {
                alert('Voer eerst een vraag in.');
            }
        });
    }
    
    // Cancel question
    if (cancelQuestionBtn) {
        cancelQuestionBtn.addEventListener('click', function() {
            toggleQAInput(false);
        });
    }
    
    // Enter key for question submission
    if (questionInput) {
        questionInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                submitQuestionBtn.click();
            }
        });
    }
    
    // Close analysis
    if (closeAnalysisBtn) {
        closeAnalysisBtn.addEventListener('click', function() {
            hideAnalysisResults();
        });
    }
    
    /**
     * Perform AI analysis
     */
    function performAnalysis(type, partyKey) {
        console.log(`📊 Starting ${type} analysis for ${partyKey}`);
        
        // Show results container
        showAnalysisResults();
        
        // Set title
        let title = '';
        switch(type) {
            case 'party': title = 'Partij Analyse'; break;
            case 'leader': title = 'Leider Analyse'; break;
            case 'voter_profile': title = 'Kiezer Profiel'; break;
            case 'timeline': title = 'Politieke Geschiedenis'; break;
            default: title = 'Analyse';
        }
        
        if (analysisTitle) {
            analysisTitle.textContent = `${title} Resultaten`;
        }
        
        // Show loading state
        showLoadingState();
        
        // Disable buttons
        setButtonStates(true);
        
        // Make API call
        fetch('<?php echo URLROOT; ?>/ajax/party-analysis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: type,
                partyKey: partyKey
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('📊 Analysis response:', data);
            
            if (data.success) {
                showAnalysisContent(data.content, type);
            } else {
                showAnalysisError(data.error || 'Er is een onbekende fout opgetreden.');
            }
        })
        .catch(error => {
            console.error('❌ Analysis error:', error);
            showAnalysisError('Verbindingsfout: Kon geen verbinding maken met de analyse service.');
        })
        .finally(() => {
            // Re-enable buttons
            setButtonStates(false);
        });
    }
    
    /**
     * Show analysis results container
     */
    function showAnalysisResults() {
        if (analysisResults) {
            analysisResults.classList.remove('hidden');
            // Smooth scroll to results
            analysisResults.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start',
                inline: 'nearest'
            });
        }
    }
    
    /**
     * Hide analysis results container
     */
    function hideAnalysisResults() {
        if (analysisResults) {
            analysisResults.classList.add('hidden');
        }
        
        // Reset states
        hideLoadingState();
        hideAnalysisContent();
        hideAnalysisError();
    }
    
    /**
     * Show loading state
     */
    function showLoadingState() {
        if (analysisLoading) analysisLoading.classList.remove('hidden');
        hideAnalysisContent();
        hideAnalysisError();
    }
    
    /**
     * Hide loading state
     */
    function hideLoadingState() {
        if (analysisLoading) analysisLoading.classList.add('hidden');
    }
    
    /**
     * Show analysis content
     */
    function showAnalysisContent(content, type) {
        hideLoadingState();
        hideAnalysisError();
        
        if (analysisContent) {
            // Format the content with proper styling
            const formattedContent = formatAnalysisContent(content);
            
            const contentDiv = analysisContent.querySelector('.prose');
            if (contentDiv) {
                contentDiv.innerHTML = formattedContent;
            }
            
            analysisContent.classList.remove('hidden');
        }
    }
    
    /**
     * Format analysis content with proper HTML styling
     */
    function formatAnalysisContent(content) {
        // Replace markdown-style headers with HTML
        let formatted = content
            .replace(/## (.*)/g, '<h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4 flex items-center"><span class="mr-3">$1</span></h2>')
            .replace(/### (.*)/g, '<h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">$1</h3>')
            .replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-gray-900">$1</strong>')
            .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');
        
        // Replace bullet points
        formatted = formatted.replace(/- (.*)/gm, '<li class="mb-2">$1</li>');
        
        // Wrap consecutive list items in ul tags
        formatted = formatted.replace(/(<li.*<\/li>\s*)+/g, function(match) {
            return '<ul class="list-disc list-inside space-y-2 ml-4 mb-4">' + match + '</ul>';
        });
        
        // Replace line breaks with paragraphs
        const paragraphs = formatted.split('\n\n').filter(p => p.trim());
        formatted = paragraphs.map(p => {
            if (!p.includes('<h') && !p.includes('<ul') && !p.includes('<li')) {
                return `<p class="mb-4 text-gray-700 leading-relaxed">${p.trim()}</p>`;
            }
            return p;
        }).join('');
        
        return formatted;
    }
    
    /**
     * Show analysis error
     */
    function showAnalysisError(errorMessage) {
        hideLoadingState();
        hideAnalysisContent();
        
        if (analysisError) {
            if (analysisErrorMessage) {
                analysisErrorMessage.textContent = errorMessage;
            }
            analysisError.classList.remove('hidden');
        }
    }
    
    /**
     * Hide analysis error
     */
    function hideAnalysisError() {
        if (analysisError) analysisError.classList.add('hidden');
    }
    
    /**
     * Hide analysis content
     */
    function hideAnalysisContent() {
        if (analysisContent) analysisContent.classList.add('hidden');
    }
    
    /**
     * Set button states (enabled/disabled)
     */
    function setButtonStates(disabled) {
        const buttons = [analyzePartyBtn, analyzeLeaderBtn, analyzeVoterBtn, analyzeTimelineBtn, showQaBtn, submitQuestionBtn];
        
        buttons.forEach(btn => {
            if (btn) {
                if (disabled) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    
                    // Update button text
                    const textSpan = btn.querySelector('.analyze-btn-text') || btn.querySelector('.question-btn-text');
                    if (textSpan) {
                        textSpan.textContent = 'Analyseren...';
                    }
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    // Restore button text
                    const textSpan = btn.querySelector('.analyze-btn-text') || btn.querySelector('.question-btn-text');
                    if (textSpan) {
                        if (btn.id === 'analyze-party-btn') {
                            textSpan.textContent = 'Analyseer Partij';
                        } else if (btn.id === 'analyze-leader-btn') {
                            textSpan.textContent = 'Analyseer Leider';
                        } else if (btn.id === 'analyze-voter-btn') {
                            textSpan.textContent = 'Analyseer Kiezers';
                        } else if (btn.id === 'analyze-timeline-btn') {
                            textSpan.textContent = 'Bekijk Timeline';
                        } else if (btn.id === 'submit-question-btn') {
                            textSpan.textContent = 'Verstuur Vraag';
                        }
                    }
                }
            }
        });
    }
    
    /**
     * Toggle Q&A input section
     */
    function toggleQAInput(show) {
        if (qaInputSection) {
            if (show) {
                qaInputSection.classList.remove('hidden');
                if (questionInput) {
                    questionInput.focus();
                }
                // Scroll to input
                qaInputSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center'
                });
            } else {
                qaInputSection.classList.add('hidden');
                if (questionInput) {
                    questionInput.value = '';
                }
            }
        }
    }
    
    /**
     * Perform question analysis
     */
    function performQuestionAnalysis(partyKey, question) {
        console.log(`❓ Starting Q&A analysis for ${partyKey}: ${question}`);
        
        // Hide Q&A input
        toggleQAInput(false);
        
        // Show results container
        showAnalysisResults();
        
        // Set title
        if (analysisTitle) {
            analysisTitle.textContent = 'Vraag & Antwoord Resultaten';
        }
        
        // Show loading state
        showLoadingState();
        
        // Disable buttons
        setButtonStates(true);
        
        // Make API call
        fetch('<?php echo URLROOT; ?>/ajax/party-analysis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'question',
                partyKey: partyKey,
                question: question
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('❓ Q&A response:', data);
            
            if (data.success) {
                // Add question to the response for display
                const contentWithQuestion = `## 🤔 **Jouw Vraag:**\n\n"${data.question}"\n\n---\n\n${data.content}`;
                showAnalysisContent(contentWithQuestion, 'question');
            } else {
                showAnalysisError(data.error || 'Er is een onbekende fout opgetreden.');
            }
        })
        .catch(error => {
            console.error('❌ Q&A error:', error);
            showAnalysisError('Verbindingsfout: Kon geen verbinding maken met de vraag & antwoord service.');
        })
        .finally(() => {
            // Re-enable buttons
            setButtonStates(false);
        });
    }
});
</script>

<?php include_once BASE_PATH . '/views/templates/footer.php'; ?> 