<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/party_color_helpers.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Party information
require_once __DIR__ . '/../models/PartyModel.php';

// Krijg alle partijen uit de database
$partyModel = new PartyModel();
$parties = $partyModel->getAllParties();
$parties_json = json_encode($parties, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
if ($parties_json === false) {
    $parties_json = '{}';
}

// Include the header
$title = "Politieke Partijen Overzicht";
$description = "Een overzicht van alle Nederlandse politieke partijen, hun standpunten en lijsttrekkers";
include_once BASE_PATH . '/views/templates/header.php';
?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <script>window.__PP_PARTIES__ = <?php echo $parties_json; ?>;</script>
    <?php include BASE_PATH . '/views/partijen/partials/hero-section.php'; ?>
    

    <!-- AI Analyse Modal -->
    <div id="ai-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden transform scale-95 transition-transform duration-300" id="ai-modal-content">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/10 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">AI Peiling Analyse</h3>
                                <p class="text-blue-100 text-sm">Intelligente analyse van de Nederlandse politieke situatie</p>
                            </div>
                        </div>
                        <button id="close-ai-modal" class="p-2 hover:bg-white/10 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <!-- Loading State -->
                    <div id="ai-loading" class="flex flex-col items-center justify-center py-12">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                            <div class="absolute inset-0 w-16 h-16 border-4 border-transparent border-r-secondary rounded-full animate-spin animation-delay-75"></div>
                        </div>
                        <p class="mt-4 text-slate-600 font-medium">AI analyseert de peilingen...</p>
                        <p class="text-sm text-slate-500 mt-2">Dit kan even duren</p>
                    </div>

                    <!-- Content -->
                    <div id="ai-content" class="hidden prose prose-slate max-w-none">
                        <!-- AI gegenereerde content komt hier -->
                    </div>

                    <!-- Error State -->
                    <div id="ai-error" class="hidden text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2">Er ging iets fout</h4>
                        <p class="text-slate-600 mb-4">De AI-analyse kon niet worden geladen.</p>
                        <button id="retry-ai-analysis" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                            Opnieuw proberen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 max-w-7xl -mt-6 relative z-10">
        <!-- Enhanced Header & Controls Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
            <!-- Gradient Header -->
            <div class="bg-gradient-to-r from-primary via-secondary to-primary px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Title Section -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Alle Politieke Partijen</h2>
                            <p class="text-slate-300 text-sm">Ontdek de standpunten en visies van alle Nederlandse partijen</p>
                        </div>
                    </div>
                    <!-- Stats Overview -->
                    <div class="grid grid-cols-3 gap-4 lg:gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white"><?php echo count($parties); ?></div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Partijen</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">150</div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Zetels</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white"><?php 
                                $totalSeatsPolling = array_sum(array_column($parties, 'current_seats'));
                                echo $totalSeatsPolling;
                            ?></div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Bezet</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Controls -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Sort & Filter Controls -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <select id="sortOption" class="appearance-none bg-white border-2 border-gray-200 text-gray-700 rounded-xl px-4 py-2.5 pr-10 font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 cursor-pointer shadow-sm">
                                <option value="name">📝 Alfabetisch</option>
                                <option value="seats">🏛️ Huidige zetels</option>
                                <option value="polling">📊 Peilingen</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- View Toggle -->
                        <div class="bg-white rounded-xl p-1 border-2 border-gray-200 flex">
                            <button id="grid-view" class="px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Raster
                            </button>
                            <button id="list-view" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Lijst
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search & Info -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Zoek partij..." 
                                   class="pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 bg-white px-3 py-2 rounded-lg border">
                            <span id="party-counter"><?php echo count($parties); ?></span> partijen
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revolutionary Party Cards Grid -->
        <div id="parties-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
            <?php foreach ($parties as $partyKey => $party): ?>
                <article class="party-card group bg-gradient-to-br from-white to-gray-50 rounded-3xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 relative">
                    
                    <!-- Enhanced Party Header with Elegant Design -->
                    <header class="relative overflow-hidden">
                        <!-- Dynamic Color Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br opacity-5" 
                             style="background: linear-gradient(135deg, <?php echo getPartyColor($partyKey); ?>, <?php echo adjustColorBrightness(getPartyColor($partyKey), 40); ?>);">
                        </div>
                        
                        <!-- Decorative Pattern Overlay -->
                        <div class="absolute inset-0 opacity-5" 
                             style="background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23000000\" fill-opacity=\"0.4\"><circle cx=\"10\" cy=\"10\" r=\"1\"/><circle cx=\"30\" cy=\"30\" r=\"1\"/><circle cx=\"50\" cy=\"50\" r=\"1\"/></g></g></svg>');">
                        </div>
                        
                        <div class="relative p-6 pb-4">
                            <!-- Party Identity Section -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Premium Logo Container -->
                                    <div class="relative group-hover:scale-105 transition-transform duration-300">
                                        <div class="w-16 h-16 rounded-2xl bg-white shadow-lg border-2 border-gray-100 flex items-center justify-center overflow-hidden group-hover:shadow-xl transition-shadow duration-300">
                                            <img src="<?php echo htmlspecialchars($party['logo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                                 class="w-12 h-12 object-contain">
                                        </div>
                                        <!-- Elegant Color Dot -->
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-md" 
                                             style="background-color: <?php echo getPartyColor($partyKey); ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- Party Name & Abbreviation -->
                                    <div class="flex-1">
                                        <h2 class="text-xl font-black text-gray-900 mb-1 tracking-tight">
                                            <?php echo htmlspecialchars($partyKey); ?>
                                        </h2>
                                        <p class="text-sm text-gray-600 font-medium leading-tight">
                                            <?php echo htmlspecialchars($party['name']); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Current Seats Display -->
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white text-lg font-bold px-3 py-2 rounded-xl shadow-lg">
                                        <?php echo $party['current_seats']; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 font-medium">zetels</div>
                                </div>
                            </div>
                            
                            <!-- Sophisticated Polling Display -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Peilingen</div>
                                            <div class="flex items-baseline space-x-1">
                                                <span class="text-2xl font-bold" style="color: <?php echo getPartyColor($partyKey); ?>">
                                                    <?php echo $party['polling']['seats']; ?>
                                                </span>
                                                <span class="text-sm text-gray-400">van 150</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Trend Indicator -->
                                    <?php 
                                    $changeValue = $party['polling']['change'];
                                    $isPositive = $changeValue > 0;
                                    $isNegative = $changeValue < 0;
                                    
                                    if ($isPositive) {
                                        $trendClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $trendIcon = '📈';
                                    } elseif ($isNegative) {
                                        $trendClass = 'bg-red-100 text-red-700 border-red-200';
                                        $trendIcon = '📉';
                                    } else {
                                        $trendClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                        $trendIcon = '➡️';
                                    }
                                    ?>
                                    
                                    <div class="<?php echo $trendClass; ?> px-3 py-2 rounded-lg border font-bold text-sm flex items-center space-x-1">
                                        <span><?php echo $trendIcon; ?></span>
                                        <span><?php echo $changeValue !== 0 ? ($changeValue > 0 ? '+' : '') . $changeValue : '0'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    
                    <!-- Premium Content Section -->
                    <div class="p-6 pt-2 space-y-5">
                        
                        <!-- Elegant Leader Preview -->
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 shadow-md" 
                                     style="border-color: <?php echo getPartyColor($partyKey); ?>">
                                    <img src="<?php echo htmlspecialchars($party['leader_photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate">
                                    <?php echo htmlspecialchars($party['leader']); ?>
                                </p>
                                <p class="text-xs text-gray-600">Partijleider</p>
                            </div>
                        </div>
                        
                        <!-- Refined Description -->
                        <div class="space-y-3">
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-3">
                                <?php echo htmlspecialchars(mb_substr($party['description'], 0, 160)) . '...'; ?>
                            </p>
                        </div>
                        
                        <!-- Premium Standpoints Tags -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3">
                                Kernstandpunten
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php $standpointKeys = array_slice(array_keys($party['standpoints']), 0, 3); ?>
                                <?php foreach ($standpointKeys as $topic): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors duration-200" 
                                          style="background-color: <?php echo adjustColorOpacity(getPartyColor($partyKey), 0.1); ?>; 
                                                 border-color: <?php echo adjustColorOpacity(getPartyColor($partyKey), 0.3); ?>; 
                                                 color: <?php echo adjustColorBrightness(getPartyColor($partyKey), -40); ?>;">
                                        <?php echo htmlspecialchars($topic); ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($party['standpoints']) > 3): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        +<?php echo count($party['standpoints']) - 3; ?> meer
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sophisticated Action Footer -->
                    <footer class="p-6 pt-0">
                        <div class="w-full">
                            <!-- Primary Action Button -->
                            <a href="<?php echo URLROOT; ?>/partijen/<?php echo $partyKey; ?>" 
                               class="party-btn group relative overflow-hidden text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl block text-center w-full" 
                               style="background: linear-gradient(135deg, <?php echo getPartyColor($partyKey); ?>, <?php echo adjustColorBrightness(getPartyColor($partyKey), -20); ?>);">
                                <!-- Shimmer Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <span class="relative flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Bekijk partij</span>
                                </span>
                            </a>
                        </div>
                    </footer>
                    
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Enhanced Modals -->
<div id="party-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 id="party-modal-title" class="text-2xl font-bold text-gray-800"></h2>
            <button class="close-modal text-gray-500 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Party header information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
                    <img id="party-modal-logo" src="" alt="" class="w-32 h-32 object-contain mx-auto mb-3">
                    <p id="party-modal-abbr" class="text-2xl font-semibold text-gray-800 mb-1"></p>
                    <p id="party-modal-name" class="text-sm text-gray-500 mb-4"></p>
                    
                    <div class="flex items-center justify-center space-x-4 mb-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Zetels</p>
                            <p id="party-modal-seats" class="text-xl font-bold text-gray-800"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Peilingen</p>
                            <p id="party-modal-polling" class="text-xl font-bold text-gray-800"></p>
                        </div>
                    </div>
                    
                    <div id="party-modal-polling-trend" class="text-sm font-medium mt-2"></div>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Over de partij</h3>
                    <p id="party-modal-description" class="text-gray-600 mb-6"></p>
                    
                    <div class="flex items-center mb-4">
                        <img id="party-modal-leader-photo" src="" alt="" class="w-16 h-16 object-cover rounded-full mr-4 border-2 border-primary">
                        <div>
                            <p class="text-sm text-gray-500">Partijleider</p>
                            <p id="party-modal-leader" class="text-lg font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Party standpoints -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Standpunten</h3>
            <div id="party-modal-standpoints" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Standpoints will be filled by JavaScript -->
            </div>
        </div>
        
        <!-- Political Perspectives Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Politieke Perspectieven</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left perspective card -->
                <div class="perspective-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 shadow-sm relative overflow-hidden">
                    <!-- Decorative pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-10">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M-5.46392e-07 0L80 0L80 80L75 80C33.5786 80 -5.46392e-07 46.4214 -5.46392e-07 5L-5.46392e-07 0Z" fill="#2563EB"/>
                        </svg>
                    </div>
                    <div class="flex items-center mb-3 relative">
                        <div class="bg-blue-500 text-white text-xs uppercase font-bold tracking-wider py-1 px-3 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Links perspectief
                        </div>
                    </div>
                    <p id="party-modal-left-perspective" class="text-gray-700 relative"></p>
                </div>
                
                <!-- Right perspective card -->
                <div class="perspective-card bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-5 shadow-sm relative overflow-hidden">
                    <!-- Decorative pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-10">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M-5.46392e-07 0L80 0L80 80L75 80C33.5786 80 -5.46392e-07 46.4214 -5.46392e-07 5L-5.46392e-07 0Z" fill="#DC2626"/>
                        </svg>
                    </div>
                    <div class="flex items-center mb-3 relative">
                        <div class="bg-red-500 text-white text-xs uppercase font-bold tracking-wider py-1 px-3 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Rechts perspectief
                        </div>
                    </div>
                    <p id="party-modal-right-perspective" class="text-gray-700 relative"></p>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <button class="close-modal bg-gray-200 bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg">
                Sluiten
            </button>
        </div>
    </div>
</div>

<div id="leader-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 id="leader-modal-title" class="text-2xl font-bold text-gray-800"></h2>
            <button class="close-modal text-gray-500 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
                    <img id="leader-modal-photo" src="" alt="" class="w-40 h-40 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-lg">
                    <div class="flex items-center justify-center mb-3">
                        <img id="leader-modal-party-logo" src="" alt="" class="w-8 h-8 object-contain mr-2">
                        <p id="leader-modal-party-abbr" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    <p id="leader-modal-party-name" class="text-sm text-gray-500"></p>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Over de lijsttrekker</h3>
                <p id="leader-modal-info" class="text-gray-600"></p>
            </div>
        </div>
        
        <div class="text-center">
            <button class="close-modal bg-gray-200 bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg">
                Sluiten
            </button>
        </div>
    </div>
</div>

<!-- Partijen Overzicht Sectie - Vergelijkbaar met peilingen -->
    <section id="partijen" class="py-32 bg-gradient-to-b from-white via-slate-50 to-white relative overflow-hidden">
        <!-- Premium decoratieve achtergrond -->
        <div class="absolute inset-0">
            <!-- Animated gradient spheres -->
            <div class="absolute top-20 left-10 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-60 right-16 w-80 h-80 bg-gradient-to-br from-secondary/8 to-primary-light/8 rounded-full blur-2xl animate-float-delayed"></div>
            <div class="absolute bottom-32 left-1/3 w-72 h-72 bg-gradient-to-br from-primary-light/6 to-secondary-light/6 rounded-full blur-3xl animate-pulse-slow"></div>
            
            <!-- Geometric pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.04\"%3E%3Cpath d=\"M20 0L20 40M0 20L40 20\" stroke=\"%23334155\" stroke-width=\"1\"%3E%3C/path%3E%3Ccircle cx=\"20\" cy=\"20\" r=\"2\" fill=\"%23334155\"%3E%3C/circle%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
            
            <!-- Floating elements -->
            <div class="absolute top-40 right-1/4 w-4 h-4 bg-primary/20 rounded-full animate-bounce"></div>
            <div class="absolute bottom-1/3 left-1/4 w-6 h-6 bg-secondary/20 rounded-full animate-bounce animation-delay-75"></div>
            <div class="absolute top-2/3 right-1/3 w-3 h-3 bg-primary-light/20 rounded-full animate-bounce animation-delay-150"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Uniforme header sectie -->
            <div class="text-center mb-24 relative" data-aos="fade-up" data-aos-once="true">
                <!-- Achtergrond tekst -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                    <span class="text-[120px] sm:text-[160px] lg:text-[200px] xl:text-[280px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">PARTIJEN</span>
                </div>
                
                <!-- Main content -->
                <div class="relative z-10 space-y-8">
                    <!-- Hoofdtitel -->
                    <div class="space-y-6">
                        <h2 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-slate-900 leading-tight tracking-tight">
                            <span class="block mb-2">Partijen</span>
                            <span class="bg-gradient-to-r from-primary-dark via-primary to-secondary bg-clip-text text-transparent animate-gradient bg-size-200">
                                Peilingen
                            </span>
                        </h2>
                        
                        <!-- Decoratieve lijn systeem -->
                        <div class="flex items-center justify-center space-x-6 mt-8">
                            <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-primary to-secondary"></div>
                            <div class="relative">
                                <div class="w-4 h-4 bg-primary rounded-full animate-pulse"></div>
                                <div class="absolute inset-0 w-4 h-4 bg-primary rounded-full animate-ping opacity-30"></div>
                            </div>
                            <div class="w-32 h-0.5 bg-gradient-to-r from-secondary via-primary-light to-secondary-light"></div>
                            <div class="relative">
                                <div class="w-4 h-4 bg-secondary rounded-full animate-pulse animation-delay-300"></div>
                                <div class="absolute inset-0 w-4 h-4 bg-secondary rounded-full animate-ping opacity-30 animation-delay-300"></div>
                            </div>
                            <div class="w-16 h-0.5 bg-gradient-to-r from-secondary-light via-primary-light to-transparent"></div>
                        </div>
                    </div>
            
                    <!-- Subtitel -->
                    <p class="text-xl sm:text-2xl lg:text-3xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                        Overzicht van alle <span class="font-semibold text-primary">Nederlandse partijen</span> gesorteerd op <span class="font-semibold text-secondary">huidige zetels</span> en peilingen
                    </p>
                    
                    <!-- Status indicator en AI knop -->
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="inline-flex items-center px-6 py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-600">Actuele stand: Tweede Kamer 2023-2027</span>
                            </div>
                        </div>

                        <!-- AI Analyse Knop -->
                        <button id="ai-analysis-btn" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-white/20">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>AI Peiling Analyse</span>
                            <div class="ml-2 w-1 h-1 bg-white rounded-full animate-ping"></div>
                        </button>
                    </div>
                </div>
            </div>

            <?php
            // Sorteer partijen op huidige zetels (aflopend)
            $sortedPartiesCurrentSeats = $parties;
            uasort($sortedPartiesCurrentSeats, function($a, $b) {
                return $b['current_seats'] - $a['current_seats'];
            });

            // Sorteer partijen op peiling zetels (aflopend)
            $sortedPartiesPolling = $parties;
            uasort($sortedPartiesPolling, function($a, $b) {
                return $b['polling']['seats'] - $a['polling']['seats'];
            });
            ?>

            <!-- Partijen data -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-16 items-start">
                <!-- Linker kolom: Gecombineerde Zetelverdeling (spans 2 columns) -->
                <div class="xl:col-span-2 relative" data-aos="fade-right">
                    <div class="peiling-card group relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-500">
                        <!-- Animated background gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-white to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <!-- Card header -->
                        <div class="relative z-10 p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">Zetelverdeling</h3>
                                    <p class="text-slate-600 font-medium">Peilingen vs. Huidige zetels</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-slate-600">Live data</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gecombineerde partijen tabel -->
                            <div class="overflow-x-auto -mx-2">
                                <table class="w-full border-collapse text-sm">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/50">
                                            <th class="py-4 px-4 text-left font-bold text-slate-700 tracking-wide">Partij</th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Peiling</span>
                                                <span class="sm:hidden">Peiling</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Huidige zetels</span>
                                                <span class="sm:hidden">Huidig</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Verschil</span>
                                                <span class="sm:hidden">+/-</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Lijsttrekker</span>
                                                <span class="sm:hidden">Leider</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sortedPartiesCurrentSeats as $partyKey => $party): 
                                            $change = $party['polling']['seats'] - $party['current_seats'];
                                            $changeClass = $change > 0 ? 'peiling-change-badge bg-emerald-100 text-emerald-800 border-emerald-200' : 
                                                          ($change < 0 ? 'peiling-change-badge bg-red-100 text-red-800 border-red-200' : 
                                                          'peiling-change-badge bg-slate-100 text-slate-600 border-slate-200');
                                            $changeSymbol = $change > 0 ? '+' . $change : ($change < 0 ? $change : '0');
                                        ?>
                                        <tr class="peiling-table-row group border-b border-slate-100/50 hover:bg-gradient-to-r hover:from-primary/5 hover:via-white hover:to-secondary/5 transition-all duration-300">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center group">
                                                    <div class="peiling-party-indicator relative w-4 h-4 rounded-full mr-3 transition-transform duration-300 group-hover:scale-110" 
                                                         style="background-color: <?php echo getPartyColor($partyKey); ?>">
                                                        <div class="absolute inset-0 rounded-full animate-ping opacity-0 group-hover:opacity-30 transition-opacity duration-300" 
                                                             style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-900 group-hover:text-slate-800 transition-colors duration-300"><?php echo $partyKey; ?></span>
                                                        <br>
                                                        <span class="text-xs text-slate-500 hidden sm:inline"><?php echo $party['name']; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <div class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl text-sm shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 border border-blue-400/20">
                                                    <?php echo $party['polling']['seats']; ?>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <div class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-800 text-white font-bold rounded-xl text-sm shadow-md hover:shadow-lg hover:from-slate-800 hover:to-slate-900 transition-all duration-300 border border-slate-600/20">
                                                    <?php echo $party['current_seats']; ?>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <span class="<?php echo $changeClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border transition-all duration-300">
                                                    <?php echo $changeSymbol; ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <span class="text-sm font-medium text-slate-600"><?php echo explode(' ', $party['leader'])[0]; ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Footer informatie -->
                            <div class="mt-8 pt-6 border-t border-slate-200/50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-600">Peiling zetels</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-slate-900 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-600">Huidige zetels (2023)</span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-slate-500 font-medium">
                                        <span>Totaal: 150 zetels</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                    </div>
                </div>
                
                <!-- Rechter kolom: Grootste Verschuivingen -->
                <div class="relative" data-aos="fade-left">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Grootste Verschuivingen</h3>
                                    <p class="text-gray-600 text-sm">Ten opzichte van 2023</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-slate-600">Trends</span>
                                </div>
                            </div>
                            
                            <?php
                            // Sorteer partijen op absolute verandering (peiling vs huidig)
                            $sortedByChange = $parties;
                            uasort($sortedByChange, function($a, $b) {
                                $changeA = abs($a['polling']['seats'] - $a['current_seats']);
                                $changeB = abs($b['polling']['seats'] - $b['current_seats']);
                                return $changeB - $changeA;
                            });
                            $topChanges = array_slice($sortedByChange, 0, 8, true);
                            ?>
                            
                            <div class="space-y-3">
                                <?php foreach($topChanges as $partyKey => $party): 
                                    $change = $party['polling']['seats'] - $party['current_seats'];
                                    if ($change == 0) continue;
                                    
                                    $isPositive = $change > 0;
                                    $changeClass = $isPositive ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200';
                                    $changeIcon = $isPositive ? '↗' : '↘';
                                    $changeSymbol = $isPositive ? '+' . $change : $change;
                                ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                        <div>
                                            <span class="font-semibold text-gray-900 block"><?php echo $partyKey; ?></span>
                                            <span class="text-xs text-gray-500"><?php echo $party['current_seats']; ?> → <?php echo $party['polling']['seats']; ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="<?php echo $changeClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border">
                                            <?php echo $changeIcon; ?> <?php echo abs($change); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Stabiele partijen -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Stabiele Partijen</h4>
                                <div class="space-y-2">
                                    <?php 
                                    $stableParties = array_filter($parties, function($party) {
                                        return ($party['polling']['seats'] - $party['current_seats']) == 0;
                                    });
                                    foreach($stableParties as $partyKey => $party):
                                    ?>
                                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-2 h-2 rounded-full" style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                        <span class="text-sm font-medium text-gray-700"><?php echo $partyKey; ?></span>
                                        <span class="text-xs text-gray-500 ml-auto"><?php echo $party['current_seats']; ?> zetels</span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Footer informatie -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <span>Winst</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <span>Verlies</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Completely Rebuilt Modern Coalitiemaker -->
<div class="container mx-auto px-4 py-12 md:py-16 mb-16" id="coalitiemaker">
    <!-- Hero Header with Gradient -->
    <div class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary rounded-3xl shadow-2xl overflow-hidden mb-6 md:mb-8">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.05\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"2\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')]"></div>
        
        <div class="relative z-10 p-6 md:p-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6 mb-6">
                <div>
                    <div class="inline-flex items-center px-3 md:px-4 py-1.5 md:py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/25 mb-3 md:mb-4">
                            <div class="w-2 h-2 bg-secondary-light rounded-full mr-2 animate-pulse"></div>
                        <span class="text-white/95 text-xs md:text-sm font-semibold">Interactieve Coalitiemaker</span>
                        </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-3 tracking-tight">
                        Bouw Jouw Coalitie
                        </h2>
                    <p class="text-white/90 text-sm md:text-base lg:text-lg font-medium max-w-2xl">
                        Klik op partijen om je ideale coalitie samen te stellen en direct te zien of je een meerderheid behaalt
                        </p>
                    </div>
                
                <!-- Live Stats Summary -->
                <div class="flex md:flex-col gap-3 md:gap-2">
                    <div class="flex-1 md:flex-initial bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/25">
                        <div class="text-xs text-white/70 font-medium mb-1">Totale Zetels</div>
                        <div class="text-2xl md:text-3xl font-black text-white" id="header-seats">0</div>
                    </div>
                    <div class="flex-1 md:flex-initial bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/25">
                        <div class="text-xs text-white/70 font-medium mb-1">Partijen</div>
                        <div class="text-2xl md:text-3xl font-black text-white" id="header-parties">0</div>
                    </div>
                    </div>
                </div>
                
            <!-- View Tabs -->
            <div class="flex gap-2 bg-white/10 backdrop-blur-sm rounded-xl p-1.5 w-full sm:w-auto">
                <button id="coalition-current-tab" class="coalition-tab-btn active flex-1 sm:flex-initial px-4 md:px-6 py-2.5 md:py-3 rounded-lg font-semibold text-white text-sm md:text-base transition-all duration-300">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        <span>Huidige Zetels</span>
                        </span>
                    </button>
                <button id="coalition-polling-tab" class="coalition-tab-btn flex-1 sm:flex-initial px-4 md:px-6 py-2.5 md:py-3 rounded-lg font-semibold text-white/60 text-sm md:text-base transition-all duration-300">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        <span>Peilingen</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        <!-- Left Column: Party Selector -->
        <div class="lg:col-span-7 xl:col-span-8">
            <!-- Analytics Dashboard -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
                <!-- Seats Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-primary/10 p-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                            </svg>
                                </div>
                            </div>
                    <div class="text-2xl md:text-3xl font-black text-gray-900" id="dashboard-seats">0</div>
                    <div class="text-xs text-gray-500 font-medium">Zetels</div>
                        </div>
                
                <!-- Percentage Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-secondary/10 p-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary to-secondary-light rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                                </div>
                            </div>
                    <div class="text-2xl md:text-3xl font-black text-gray-900" id="dashboard-percentage">0%</div>
                    <div class="text-xs text-gray-500 font-medium">Percentage</div>
                </div>
                
                <!-- Parties Count Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-primary/10 p-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-light to-secondary rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                </div>
                    <div class="text-2xl md:text-3xl font-black text-gray-900" id="dashboard-party-count">0</div>
                    <div class="text-xs text-gray-500 font-medium">Partijen</div>
                            </div>
                            
                <!-- Status Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center" id="dashboard-status-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                            </div>
                        </div>
                    <div class="text-xs md:text-sm font-bold text-gray-900" id="dashboard-status-text">Geen coalitie</div>
                    <div class="text-xs text-gray-500 font-medium">Status</div>
                    </div>
                </div>
                
            <!-- Progress Bar Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-700">Meerderheid Tracker</span>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-black text-gray-900" id="progress-seats">0</span>
                        <span class="text-sm text-gray-500">/ 150</span>
                            </div>
                                        </div>
                
                <div class="relative w-full h-6 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                    <div id="main-progress-bar" class="h-full rounded-full shadow-sm transition-all duration-700 ease-out bg-gradient-to-r from-primary via-secondary to-primary-light relative overflow-hidden" style="width: 0%">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                                    </div>
                                    
                    <!-- Majority Indicator -->
                    <div class="absolute top-0 h-full w-0.5 bg-red-500" style="left: 50.67%">
                        <div class="absolute -top-1 -left-2 w-5 h-8 bg-red-500 rounded-sm shadow-lg flex items-center justify-center">
                            <span class="text-white text-xs font-bold">76</span>
                        </div>
                                        </div>
                                    </div>
                                    
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>Geen meerderheid</span>
                    <span class="font-bold text-red-600">Meerderheid</span>
                                    </div>
                                </div>
                                
            <!-- Party Grid -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                        <span>Selecteer Partijen</span>
                    </h3>
                    <div class="flex gap-2">
                        <button id="clear-all-btn" class="px-3 md:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs md:text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="hidden sm:inline">Wissen</span>
                        </button>
                        <button id="random-coalition-btn" class="px-3 md:px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg text-xs md:text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Random</span>
                        </button>
                    </div>
                                </div>
                                
                <div id="party-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                    <!-- Parties will be dynamically inserted here -->
                                </div>
                            </div>
                        </div>
                        
        <!-- Right Column: Visualizations -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-6">
            
            <!-- Hemicycle Visualization -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4">
                    <div class="w-2 h-2 bg-secondary rounded-full animate-pulse"></div>
                    <span>Tweede Kamer</span>
                                </h3>
                
                <div id="hemicycle-container" class="relative w-full" style="aspect-ratio: 2/1;">
                    <svg id="hemicycle-svg" class="w-full h-full" viewBox="0 0 400 200">
                        <!-- Hemicycle will be drawn here -->
                    </svg>
                                </div>
                                
                <div class="text-center mt-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold" id="hemicycle-selected">0</span> / <span class="font-semibold">150</span> zetels geselecteerd
                                </div>
                            </div>
                        </div>
                        
            <!-- Political Spectrum -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4">
                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                    <span>Politiek Spectrum</span>
                                </h3>
                
                <div class="relative mb-4">
                    <div class="h-6 bg-gradient-to-r from-red-500 via-purple-500 to-blue-500 rounded-full shadow-inner relative overflow-hidden">
                        <!-- Party indicators will be placed here -->
                        <div id="spectrum-indicators"></div>
                            </div>
                    
                    <div class="flex justify-between text-xs text-gray-600 mt-2 font-medium">
                        <span>Links</span>
                        <span>Centrum</span>
                        <span>Rechts</span>
                                    </div>
                                </div>
                
                <div id="spectrum-info" class="text-center">
                    <div class="text-sm text-gray-600">Selecteer partijen om het politieke spectrum te zien</div>
                            </div>
                        </div>
            
            <!-- Coalition Suggestions -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span>Coalitie Suggesties</span>
                </h3>
                
                <div id="suggestions-container" class="space-y-3">
                    <!-- Suggestions will be dynamically inserted -->
                    </div>
                </div>
            
        </div>
    </div>
</div>

<style>
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer {
    animation: shimmer 2s infinite;
}
.coalition-tab-btn.active {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
}
</style>

<!-- Premium Visual Tweede Kamer Representation -->
<div class="container mx-auto px-4 py-12 mb-16">
    <div class="bg-gradient-to-br from-white via-slate-50 to-gray-100 rounded-3xl shadow-2xl overflow-hidden border border-white/50 backdrop-blur-xl">
        
        <!-- Elegant Header -->
        <div class="relative bg-gradient-to-r from-primary-dark via-primary to-secondary p-8 overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"2\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-4xl font-black text-white mb-3 tracking-tight">
                            Zetelverdeling Tweede Kamer
                        </h2>
                        <p class="text-white/90 text-lg font-medium">
                            Live overzicht van de huidige politieke verhoudingen
                        </p>
                    </div>
                    <div class="hidden md:flex items-center space-x-3 bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white font-semibold">150 Zetels</span>
                    </div>
                </div>
                
                <!-- Modern Tabs -->
                <div class="flex space-x-2 bg-white/20 backdrop-blur-sm rounded-2xl p-2 w-fit">
                    <button id="current-tab" class="chamber-tab-btn px-6 py-3 rounded-xl font-semibold text-white bg-white/30 transition-all duration-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span>Huidige Zetels</span>
                        </span>
                    </button>
                    <button id="polling-tab" class="chamber-tab-btn px-6 py-3 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-white/20 transition-all duration-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            <span>Peilingen</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-8">
            <!-- Current Seats View -->
            <div id="current-view" class="chamber-view">
                <!-- Premium Chamber visualization -->
                <div class="relative flex justify-center mb-12">
                    <div class="chamber-container relative w-full max-w-5xl">
                        <!-- Modern Chamber Design -->
                        <div class="chamber-semicircle relative w-full h-80 md:h-96 bg-gradient-to-b from-slate-100 to-slate-200 rounded-t-full border-4 border-slate-300 shadow-2xl overflow-hidden">
                            <!-- Chamber Floor Pattern -->
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Ccircle cx=\"10\" cy=\"10\" r=\"1\" fill=\"%23475569\"/%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
                            
                            <!-- Seats Container -->
                            <div class="absolute inset-0 flex flex-col justify-end items-center p-6 md:p-10" id="current-seats-chamber">
                                <div class="text-slate-500 font-medium animate-pulse">Zetels worden geladen...</div>
                            </div>
                            
                            <!-- Parliament Speaker's Position -->
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-8 bg-slate-600 rounded-t-lg shadow-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Chamber Stats -->
                        <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-2xl shadow-xl border border-gray-200 px-6 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Coalitie</span>
                                </div>
                                <div class="w-px h-4 bg-gray-300"></div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Oppositie</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Legend with Logos -->
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Huidige Zetelverdeling</h3>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl px-4 py-2 border border-blue-200">
                            <span class="text-blue-800 font-bold text-lg">150 zetels</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <?php
                        // Sort parties by current seats (descending)
                        $seatsSorted = $parties;
                        uasort($seatsSorted, function($a, $b) {
                            return $b['current_seats'] - $a['current_seats'];
                        });
                        
                        foreach ($seatsSorted as $partyKey => $party) {
                            if ($party['current_seats'] > 0) {
                                $color = getPartyColor($partyKey);
                                echo '<div class="group relative bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-xl hover:border-gray-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1">';
                                
                                // Party logo and info
                                echo '<div class="flex items-center space-x-4 mb-3">';
                                echo '<div class="relative">';
                                echo '<div class="w-12 h-12 rounded-xl bg-white shadow-md border-2 border-gray-100 flex items-center justify-center overflow-hidden">';
                                echo '<img src="' . htmlspecialchars($party['logo']) . '" alt="' . htmlspecialchars($partyKey) . ' logo" class="w-10 h-10 object-contain">';
                                echo '</div>';
                                echo '<div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: ' . $color . '"></div>';
                                echo '</div>';
                                echo '<div class="flex-1 min-w-0">';
                                echo '<h4 class="font-bold text-gray-900 text-lg">' . htmlspecialchars($partyKey) . '</h4>';
                                echo '<p class="text-gray-600 text-sm truncate">' . htmlspecialchars($party['name']) . '</p>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Seats info
                                echo '<div class="flex items-center justify-between">';
                                echo '<div class="flex items-center space-x-2">';
                                echo '<span class="text-3xl font-black text-gray-900">' . $party['current_seats'] . '</span>';
                                echo '<span class="text-gray-500 font-medium">zetels</span>';
                                echo '</div>';
                                echo '<div class="text-right">';
                                $percentage = round(($party['current_seats'] / 150) * 100, 1);
                                echo '<div class="text-lg font-bold text-gray-700">' . $percentage . '%</div>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Progress bar
                                echo '<div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">';
                                echo '<div class="h-full transition-all duration-500 ease-out" style="background-color: ' . $color . '; width: ' . $percentage . '%"></div>';
                                echo '</div>';
                                
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Polling View (hidden by default) -->
            <div id="polling-view" class="chamber-view hidden">
                <!-- Premium Chamber visualization -->
                <div class="relative flex justify-center mb-12">
                    <div class="chamber-container relative w-full max-w-5xl">
                        <!-- Modern Chamber Design -->
                        <div class="chamber-semicircle relative w-full h-80 md:h-96 bg-gradient-to-b from-slate-100 to-slate-200 rounded-t-full border-4 border-slate-300 shadow-2xl overflow-hidden">
                            <!-- Chamber Floor Pattern -->
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Ccircle cx=\"10\" cy=\"10\" r=\"1\" fill=\"%23475569\"/%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
                            
                            <!-- Seats Container -->
                            <div class="absolute inset-0 flex flex-col justify-end items-center p-6 md:p-10" id="polling-seats-chamber">
                                <div class="text-slate-500 font-medium animate-pulse">Peiling zetels worden geladen...</div>
                            </div>
                            
                            <!-- Parliament Speaker's Position -->
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-8 bg-slate-600 rounded-t-lg shadow-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Chamber Stats for Polling -->
                        <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-2xl shadow-xl border border-gray-200 px-6 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse"></div>
                                    <span class="font-medium text-gray-700">Peilingen</span>
                                </div>
                                <div class="w-px h-4 bg-gray-300"></div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-700"><?php
                                        $totalPollingSeats = array_sum(array_map(function($party) {
                                            return $party['polling']['seats'];
                                        }, $parties));
                                        echo $totalPollingSeats;
                                    ?> zetels</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Legend with Logos for Polling -->
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Peilingen</h3>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl px-4 py-2 border border-purple-200">
                            <span class="text-purple-800 font-bold text-lg"><?php echo $totalPollingSeats; ?> zetels</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <?php
                        // Sort parties by polling seats (descending)
                        $pollingSorted = $parties;
                        uasort($pollingSorted, function($a, $b) {
                            return $b['polling']['seats'] - $a['polling']['seats'];
                        });
                        
                        foreach ($pollingSorted as $partyKey => $party) {
                            if ($party['polling']['seats'] > 0) {
                                $color = getPartyColor($partyKey);
                                $change = $party['polling']['seats'] - $party['current_seats'];
                                $changeClass = $change > 0 ? 'text-green-600 bg-green-50' : ($change < 0 ? 'text-red-600 bg-red-50' : 'text-gray-600 bg-gray-50');
                                $changeText = $change > 0 ? '+' . $change : ($change < 0 ? $change : '±0');
                                
                                echo '<div class="group relative bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-xl hover:border-gray-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1">';
                                
                                // Change indicator
                                if ($change != 0) {
                                    echo '<div class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-bold ' . $changeClass . '">';
                                    echo $changeText;
                                    echo '</div>';
                                }
                                
                                // Party logo and info
                                echo '<div class="flex items-center space-x-4 mb-3">';
                                echo '<div class="relative">';
                                echo '<div class="w-12 h-12 rounded-xl bg-white shadow-md border-2 border-gray-100 flex items-center justify-center overflow-hidden">';
                                echo '<img src="' . htmlspecialchars($party['logo']) . '" alt="' . htmlspecialchars($partyKey) . ' logo" class="w-10 h-10 object-contain">';
                                echo '</div>';
                                echo '<div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: ' . $color . '"></div>';
                                echo '</div>';
                                echo '<div class="flex-1 min-w-0">';
                                echo '<h4 class="font-bold text-gray-900 text-lg">' . htmlspecialchars($partyKey) . '</h4>';
                                echo '<p class="text-gray-600 text-sm truncate">' . htmlspecialchars($party['name']) . '</p>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Seats info with comparison
                                echo '<div class="space-y-3">';
                                echo '<div class="flex items-center justify-between">';
                                echo '<div class="flex items-center space-x-2">';
                                echo '<span class="text-3xl font-black text-gray-900">' . $party['polling']['seats'] . '</span>';
                                echo '<span class="text-gray-500 font-medium">zetels</span>';
                                echo '</div>';
                                echo '<div class="text-right">';
                                $percentage = round(($party['polling']['seats'] / 150) * 100, 1);
                                echo '<div class="text-lg font-bold text-gray-700">' . $percentage . '%</div>';
                                echo '</div>';
                                echo '</div>';
                                
                                // Progress bar
                                echo '<div class="h-2 bg-gray-200 rounded-full overflow-hidden">';
                                echo '<div class="h-full transition-all duration-500 ease-out" style="background-color: ' . $color . '; width: ' . $percentage . '%"></div>';
                                echo '</div>';
                                
                                // Comparison with current seats
                                echo '<div class="text-xs text-gray-500 flex justify-between">';
                                echo '<span>Huidig: ' . $party['current_seats'] . ' zetels</span>';
                                echo '<span class="' . ($change >= 0 ? 'text-green-600' : 'text-red-600') . ' font-medium">' . $changeText . '</span>';
                                echo '</div>';
                                echo '</div>';
                                
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Information note -->
                <div class="mt-8 flex justify-center">
                    <div class="max-w-lg bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-blue-800 font-medium mb-1">Peilingsinformatie</p>
                                <p class="text-blue-700 text-sm leading-relaxed">
                                    Hover over zetels in de kamer voor gedetailleerde partijinformatie. 
                                    Peilingen zijn gebaseerd op de laatste beschikbare data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
window.PP_CONFIG = Object.freeze({
    urlRoot: <?php echo json_encode(URLROOT, JSON_UNESCAPED_SLASHES); ?>
});
</script>
<script src="<?php echo URLROOT; ?>/js/partijen-page.js" defer></script>
<script src="<?php echo URLROOT; ?>/js/partijen-landing-effects.js" defer></script>

<style>
/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Enhanced Party Cards Styling */

/* Modern hover animations and transitions */
.party-card {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, box-shadow;
}

.party-card:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.party-card:active {
    transform: scale(0.98);
}
                    e.target.style.opacity = '0.6';
                    e.target.style.transform = 'rotate(3deg) scale(0.95)';
                    e.target.style.zIndex = '1000';
                }
            });
            
            document.addEventListener('dragend', (e) => {
                if (e.target.classList.contains('party-card') && !isMobile()) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'none';
                    e.target.style.zIndex = 'auto';
                }
            });
            
            const coalitionContainer = document.getElementById('selected-coalition');
            
            if (coalitionContainer) {
                // Desktop drag over coalition area
                coalitionContainer.addEventListener('dragover', (e) => {
                    if (!isMobile()) {
                        e.preventDefault();
                        coalitionContainer.classList.add('border-secondary', 'bg-secondary/5');
                        coalitionContainer.classList.remove('border-secondary/30');
                    }
                });
                
                coalitionContainer.addEventListener('dragleave', () => {
                    if (!isMobile()) {
                        coalitionContainer.classList.remove('border-secondary', 'bg-secondary/5');
                        coalitionContainer.classList.add('border-secondary/30');
                    }
                });
                
                coalitionContainer.addEventListener('drop', (e) => {
                    if (!isMobile()) {
                        e.preventDefault();
                        coalitionContainer.classList.remove('border-secondary', 'bg-secondary/5');
                        coalitionContainer.classList.add('border-secondary/30');
                        
                        const partyKey = e.dataTransfer.getData('text/plain');
                        if (partyKey && !this.coalition.includes(partyKey)) {
                            this.addToCoalition(partyKey);
                        }
                    }
                });
            }
            
            // Remove party from coalition when clicked
            document.addEventListener('click', (e) => {
                if (e.target.closest('.remove-party')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const card = e.target.closest('.party-card');
                    const partyKey = card.getAttribute('data-party');
                    this.removeFromCoalition(partyKey);
                }
            });
            
            // Mobile/tablet feedback for better UX
            if (isMobile()) {
                document.addEventListener('touchstart', (e) => {
                    const card = e.target.closest('.party-card');
                    if (card && card.closest('#available-parties')) {
                        card.style.transform = 'scale(0.98)';
                        card.style.transition = 'transform 0.1s ease';
                    }
                });
                
                document.addEventListener('touchend', (e) => {
                    const card = e.target.closest('.party-card');
                    if (card && card.closest('#available-parties')) {
                        setTimeout(() => {
                            card.style.transform = 'none';
                        }, 100);
                    }
                });
            }
        },
        
        updateAnalysis() {
            const totalSeats = this.calculateCoalitionSeats(this.coalition, this.currentView);
            const percentage = Math.round((totalSeats / 150) * 100);
            const hasMajority = totalSeats >= 76;
            
            // Update seats counter with animation
            this.animateCounter('coalition-seats', totalSeats);
            
            // Update progress bar
            const progressBar = document.getElementById('coalition-progress');
            if (progressBar) {
                progressBar.style.width = `${Math.min(100, (totalSeats / 150) * 100)}%`;
            }
            
            // Update status
            const statusEl = document.getElementById('coalition-status');
            if (statusEl) {
                if (totalSeats === 0) {
                    statusEl.textContent = 'Geen coalitie gevormd';
                    statusEl.className = 'text-center py-4 rounded-xl bg-gray-100 text-gray-600 font-semibold';
                } else if (hasMajority) {
                    statusEl.textContent = '🎉 Meerderheid behaald!';
                    statusEl.className = 'text-center py-4 rounded-xl bg-green-100 text-green-800 font-semibold';
                } else {
                    const needed = 76 - totalSeats;
                    statusEl.textContent = `⚠️ ${needed} zetels tekort`;
                    statusEl.className = 'text-center py-4 rounded-xl bg-yellow-100 text-yellow-800 font-semibold';
                }
            }
            
            // Update political spectrum
            this.updatePoliticalSpectrum();
            
            // Update coalition display
            this.updateCoalitionDisplay();
            
            // Update coalition stats
            const statsEl = document.getElementById('coalition-stats');
            if (statsEl) {
                if (totalSeats > 0) {
                    statsEl.classList.remove('hidden');
                    const partiesCountEl = document.getElementById('coalition-parties-count');
                    const percentageEl = document.getElementById('coalition-percentage');
                    if (partiesCountEl) partiesCountEl.textContent = this.coalition.length;
                    if (percentageEl) percentageEl.textContent = `${percentage}%`;
                } else {
                    statsEl.classList.add('hidden');
                }
            }
            
            // Update political spectrum
            this.updatePoliticalSpectrum();
            
            // Update coalition display
            this.updateCoalitionDisplay();
        },
        
        calculateCoalitionSeats(coalition, view) {
            return coalition.reduce((total, partyKey) => {
                const party = this.parties[partyKey];
                const seats = view === 'current' ? 
                    parseInt(party.current_seats) || 0 : 
                    parseInt(party.polling?.seats) || 0;
                return total + seats;
            }, 0);
        },
        
        clearCoalition() {
            this.coalition = [];
            this.updateAnalysis();
        },
        
        addToCoalition(partyKey) {
            if (this.coalition.includes(partyKey)) return;
            
            this.coalition.push(partyKey);
            this.updateCoalitionDisplay();
            this.updateAnalysis();
            this.removeFromAvailable(partyKey);
        },
        
        removeFromCoalition(partyKey) {
            this.coalition = this.coalition.filter(p => p !== partyKey);
            this.updateCoalitionDisplay();
            this.updateAnalysis();
            this.generateAvailableParties(); // Refresh available parties
        },
        
        updateCoalitionDisplay() {
            const container = document.getElementById('selected-coalition');
            if (!container) return;
            
            if (this.coalition.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                        <div class="bg-emerald-100 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium mb-2">Bouw je coalitie</p>
                        <p class="text-gray-400 text-sm">Sleep partijen hierheen om te beginnen</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = `<div class="space-y-3 p-4"></div>`;
            const coalitionList = container.querySelector('div');
            
            this.coalition.forEach(partyKey => {
                const party = this.parties[partyKey];
                const seats = this.currentView === 'current' ? 
                    parseInt(party.current_seats) || 0 : 
                    parseInt(party.polling?.seats) || 0;
                const card = this.createCoalitionPartyCard(partyKey, party, seats);
                coalitionList.appendChild(card);
            });
        },
        
        createCoalitionPartyCard(partyKey, party, seats) {
            const card = document.createElement('div');
            const color = this.getPartyColor(partyKey);
            
            card.className = 'party-card bg-white rounded-lg shadow-md border-l-4';
            card.style.borderLeftColor = color;
            card.setAttribute('data-party', partyKey);
            
            card.innerHTML = `
                <div class="p-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-white border" style="border-color: ${color}">
                            <img src="${party.logo}" alt="${party.name}" class="w-full h-full object-contain p-1">
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-900 text-sm">${partyKey}</h5>
                            <p class="text-xs text-gray-600">${seats} zetels</p>
                        </div>
                    </div>
                    <button class="remove-party text-red-500 p-1 rounded-full bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            return card;
        },
        
        updatePoliticalSpectrum() {
            const indicator = document.getElementById('coalition-spectrum-indicator');
            
            if (!indicator || this.coalition.length === 0) {
                if (indicator) indicator.style.display = 'none';
                return;
            }
            
            let totalPosition = 0;
            let totalSeats = 0;
            
            this.coalition.forEach(partyKey => {
                const seats = this.currentView === 'current' ? 
                    this.parties[partyKey].current_seats : 
                    this.parties[partyKey].polling.seats;
                totalPosition += this.partyPositions[partyKey] * seats;
                totalSeats += seats;
            });
            
            const avgPosition = totalPosition / totalSeats;
            indicator.style.left = `${avgPosition}%`;
            indicator.style.display = 'block';
        },
        
        removeFromAvailable(partyKey) {
            const partyCard = document.querySelector(`#available-parties .party-card[data-party="${partyKey}"]`);
            if (partyCard) {
                partyCard.remove();
            }
        },
        
        shuffleRandomCoalition() {
            this.clearCoalition();
            
            // Get available parties with seats
            const availableParties = Object.keys(this.parties).filter(key => {
                const seats = this.currentView === 'current' ? 
                    this.parties[key].current_seats : 
                    this.parties[key].polling.seats;
                return seats > 0;
            });
            
            // Shuffle and take 3-5 parties
            const shuffled = availableParties.sort(() => Math.random() - 0.5);
            const selectedParties = shuffled.slice(0, Math.floor(Math.random() * 3) + 3);
            
            selectedParties.forEach(party => this.addToCoalition(party));
        },
        
        applySuggestion(parties) {
            this.clearCoalition();
            parties.forEach(party => this.addToCoalition(party));
        },
        
        animateCounter(elementId, targetValue) {
            const element = document.getElementById(elementId);
            const startValue = parseInt(element.textContent) || 0;
            const duration = 500;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
                element.textContent = currentValue;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        addToCoalition(partyKey) {
            if (!this.coalition.includes(partyKey)) {
                this.coalition.push(partyKey);
                this.generateAvailableParties();
                this.updateAnalysis();
            }
        },
        
        removeFromCoalition(partyKey) {
            this.coalition = this.coalition.filter(key => key !== partyKey);
            this.generateAvailableParties();
            this.updateAnalysis();
        },
        
        clearCoalition() {
            this.coalition = [];
            this.generateAvailableParties();
            this.updateAnalysis();
        },
        
        shuffleRandomCoalition() {
            this.clearCoalition();
            
            // Generate a random coalition with majority
            const availableParties = Object.keys(this.parties);
            let attempts = 0;
            let totalSeats = 0;
            
            while (totalSeats < 76 && attempts < 20) {
                const randomParty = availableParties[Math.floor(Math.random() * availableParties.length)];
                
                if (!this.coalition.includes(randomParty)) {
                    const seats = this.currentView === 'current' ? 
                        this.parties[randomParty].current_seats : 
                        this.parties[randomParty].polling.seats;
                    
                    if (seats > 0) {
                        this.coalition.push(randomParty);
                        totalSeats += seats;
                    }
                }
                attempts++;
            }
            
            this.generateAvailableParties();
            this.updateAnalysis();
        },
        
        calculateCoalitionSeats(coalition, view) {
            return coalition.reduce((total, partyKey) => {
                const party = this.parties[partyKey];
                const seats = view === 'current' ? 
                    parseInt(party.current_seats) || 0 : 
                    parseInt(party.polling?.seats) || 0;
                return total + seats;
            }, 0);
        },
        
        updateCoalitionDisplay() {
            const container = document.getElementById('selected-coalition');
            if (!container) return;
            
            if (this.coalition.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                        <div class="bg-secondary/10 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium mb-2">Bouw je coalitie</p>
                        <p class="text-gray-400 text-sm">Sleep partijen hierheen om te beginnen</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = `<div class="space-y-3 p-4"></div>`;
            const coalitionList = container.querySelector('div');
            
            this.coalition.forEach((partyKey, index) => {
                const party = this.parties[partyKey];
                const seats = this.currentView === 'current' ? 
                    parseInt(party.current_seats) || 0 : 
                    parseInt(party.polling?.seats) || 0;
                const card = this.createCoalitionPartyCard(partyKey, party, seats);
                // Add staggered animation
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                coalitionList.appendChild(card);
                
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                }, index * 100);
            });
        },
        
        createCoalitionPartyCard(partyKey, party, seats) {
            const card = document.createElement('div');
            const color = this.getPartyColor(partyKey);
            
            card.className = 'party-card bg-white rounded-lg shadow-md border-l-4 hover:shadow-lg transition-all duration-200 touch-manipulation';
            card.style.borderLeftColor = color;
            card.setAttribute('data-party', partyKey);
            
            card.innerHTML = `
                <div class="p-2 lg:p-3 flex items-center justify-between">
                    <div class="flex items-center space-x-2 lg:space-x-3 flex-1 min-w-0">
                        <div class="w-8 lg:w-10 h-8 lg:h-10 rounded-lg overflow-hidden bg-white border flex-shrink-0" style="border-color: ${color}">
                            <img src="${party.logo}" alt="${party.name}" class="w-full h-full object-contain p-1">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="font-bold text-gray-900 text-sm lg:text-base truncate">${partyKey}</h5>
                            <p class="text-xs lg:text-sm text-gray-600">${seats} zetels</p>
                        </div>
                    </div>
                    <button class="remove-party text-red-500 p-2 lg:p-3 rounded-full bg-red-50 hover:bg-red-100 transition-colors duration-200 flex-shrink-0 touch-manipulation">
                        <svg class="w-3 lg:w-4 h-3 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            return card;
        },
        
        updatePoliticalSpectrum() {
            if (this.coalition.length === 0) {
                const indicator = document.getElementById('coalition-spectrum-indicator');
                const details = document.getElementById('coalition-spectrum-details');
                
                if (indicator) indicator.style.display = 'none';
                if (details) details.innerHTML = '<p class="text-center">Selecteer partijen om de gemiddelde politieke positie te zien</p>';
                return;
            }
            
            // Calculate average position
            const avgPosition = this.coalition.reduce((total, partyKey) => {
                return total + (this.partyPositions[partyKey] || 50);
            }, 0) / this.coalition.length;
            
            const indicator = document.getElementById('coalition-spectrum-indicator');
            const details = document.getElementById('coalition-spectrum-details');
            
            if (indicator) {
                indicator.style.display = 'block';
                indicator.style.left = `${avgPosition}%`;
            }
            
            if (details) {
                let description = '';
                if (avgPosition < 30) description = 'Links-georiënteerde coalitie';
                else if (avgPosition < 45) description = 'Centrum-links coalitie';
                else if (avgPosition < 55) description = 'Centrum coalitie';
                else if (avgPosition < 70) description = 'Centrum-rechts coalitie';
                else description = 'Rechts-georiënteerde coalitie';
                
                details.innerHTML = `<p class="text-center font-medium">${description}</p>`;
            }
        },
        
        generateCoalitionSuggestions() {
            const container = document.getElementById('coalition-suggestions');
            if (!container) return;
            
            // Generate realistic coalition options based on polling data (only showing coalitions with majority)
            const suggestions = [
                { name: 'Nationale Coalitie', parties: ['PVV', 'GL-PvdA', 'VVD', 'D66'], description: 'Vier grootste partijen samen' },
                { name: 'Centrum Coalitie', parties: ['GL-PvdA', 'VVD', 'D66', 'SP', 'BBB', 'DENK', 'PvdD', 'Volt'], description: 'Inclusieve brede samenwerking' },
                { name: 'Brede Coalitie', parties: ['GL-PvdA', 'VVD', 'D66', 'SP', 'BBB', 'JA21'], description: 'Centrum met pragmatische steun' },
                { name: 'Rechts Plus', parties: ['PVV', 'VVD', 'JA21', 'BBB', 'D66', 'SP'], description: 'Conservatief met brede steun' }
            ];
            
            container.innerHTML = '';
            
            suggestions.forEach((suggestion, index) => {
                // Altijd polling data gebruiken voor mogelijke coalities
                const totalSeats = suggestion.parties.reduce((total, partyKey) => {
                    const party = this.parties[partyKey];
                    if (!party) return total;
                    
                    const seats = parseInt(party.polling?.seats) || 0;
                    
                    return total + seats;
                }, 0);
                

                
                                 if (totalSeats >= 76) {
                     const suggestionCard = document.createElement('div');
                     suggestionCard.className = 'bg-white rounded-lg border border-gray-200 p-3 lg:p-4 hover:shadow-md transition-all duration-200 cursor-pointer touch-manipulation';
                     
                     suggestionCard.innerHTML = `
                          <div class="flex items-center justify-between mb-3">
                             <h4 class="font-semibold text-gray-900 text-sm lg:text-base flex-1 mr-2">${suggestion.name}</h4>
                             <span class="text-xs font-bold px-2 py-1 rounded-full flex-shrink-0 ${totalSeats >= 76 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                 ${totalSeats} zetels
                             </span>
                         </div>
                          <p class="text-xs lg:text-sm text-gray-600 mb-3">${suggestion.description}</p>
                          <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                              <span class="text-xs text-gray-500 font-medium flex-shrink-0">Partijen:</span>
                              <div class="flex flex-wrap gap-1 lg:gap-2">
                                  ${suggestion.parties.map(partyKey => {
                                      const party = this.parties[partyKey];
                                      if (!party) return '';
                                      return `
                                          <div class="relative group">
                                              <div class="w-7 lg:w-8 h-7 lg:h-8 rounded-lg overflow-hidden bg-white border border-gray-200 shadow-sm flex items-center justify-center">
                                                  <img src="${party.logo}" alt="${partyKey}" class="w-5 lg:w-6 h-5 lg:h-6 object-contain" title="${partyKey}">
                                              </div>
                                              <div class="absolute -bottom-1 -right-1 w-2 lg:w-2.5 h-2 lg:h-2.5 rounded-full border border-white shadow-sm" style="background-color: ${this.getPartyColor(partyKey)}"></div>
                                              <!-- Tooltip for desktop -->
                                              <div class="hidden lg:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                  ${partyKey}
                                              </div>
                                              <!-- Mobile label -->
                                              <div class="lg:hidden absolute -top-1 -left-1 bg-gray-800 text-white text-xs px-1 rounded opacity-0 group-active:opacity-100 transition-opacity duration-200 pointer-events-none">
                                                  ${partyKey}
                                              </div>
                                          </div>
                                      `;
                                  }).join('')}
                              </div>
                          </div>
                     `;
                    
                    suggestionCard.addEventListener('click', () => {
                        this.applySuggestion(suggestion.parties);
                    });
                    
                    container.appendChild(suggestionCard);
                }
            });
        },
        
        applySuggestion(parties) {
            this.clearCoalition();
            parties.forEach(party => this.addToCoalition(party));
        },
        
        animateCounter(elementId, targetValue) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const startValue = parseInt(element.textContent) || 0;
            const duration = 500;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
                element.textContent = currentValue;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        getPartyColor(partyKey) {
            const colors = {
                'PVV': '#0078D7', 'VVD': '#FF9900', 'NSC': '#4D7F78', 'BBB': '#006633',
                'GL-PvdA': '#008800', 'D66': '#00B13C', 'SP': '#EE0000', 'PvdD': '#007E3A',
                'CDA': '#1E8449', 'JA21': '#0066CC', 'SGP': '#FF6600', 'FvD': '#811E1E',
                'DENK': '#00b7b2', 'Volt': '#502379', 'CU': '#00AEEF'
            };
            return colors[partyKey] || '#A0A0A0';
        }
    };
    
    // Initialize the Modern Coalition Maker
    ModernCoalitionMaker.init();
    
    // Additional party functionality (existing code)
    const partyData = window.__PP_PARTIES__;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = '/public/images/profiles/placeholder-profile.svg';
            this.onerror = null;
        };
    });
    
    // Party buttons for modals
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            const party = partyData[partyKey];
            
            // Fill modal with party data
            document.getElementById('party-modal-title').textContent = party.name;
            document.getElementById('party-modal-logo').src = party.logo;
            document.getElementById('party-modal-logo').alt = `${party.name} logo`;
            document.getElementById('party-modal-abbr').textContent = partyKey;
            document.getElementById('party-modal-name').textContent = party.name;
            document.getElementById('party-modal-leader').textContent = party.leader;
            document.getElementById('party-modal-leader-photo').src = party.leader_photo;
            document.getElementById('party-modal-leader-photo').alt = party.leader;
            document.getElementById('party-modal-description').textContent = party.description;
            document.getElementById('party-modal-seats').textContent = parseInt(party.current_seats) || 0;
            document.getElementById('party-modal-polling').textContent = parseInt(party.polling?.seats) || 0;
            
            // Fill perspectives
            document.getElementById('party-modal-left-perspective').textContent = party.perspectives.left;
            document.getElementById('party-modal-right-perspective').textContent = party.perspectives.right;
            
            // Display polling trend
            const trendElement = document.getElementById('party-modal-polling-trend');
            const change = party.polling.change;
            const changeClass = change > 0 ? 'text-green-600' : (change < 0 ? 'text-red-600' : 'text-yellow-600');
            const changeIcon = change > 0 ? '↑' : (change < 0 ? '↓' : '→');
            const changeText = change > 0 ? `+${change}` : change;
            
            trendElement.className = `text-sm font-medium ${changeClass}`;
            trendElement.textContent = change !== 0 ? `Trend: ${changeIcon} ${changeText}` : 'Stabiel in peilingen';
            
            // Fill standpoints
            const standpointsContainer = document.getElementById('party-modal-standpoints');
            standpointsContainer.innerHTML = '';
            
            for (const [topic, standpoint] of Object.entries(party.standpoints)) {
                const standpointEl = document.createElement('div');
                standpointEl.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200';
                standpointEl.innerHTML = `
                    <h4 class="font-semibold text-gray-800 mb-2">${topic}</h4>
                    <p class="text-gray-600 text-sm">${standpoint}</p>
                `;
                standpointsContainer.appendChild(standpointEl);
            }
            
            // Show modal
            document.getElementById('party-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = '';
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const partyModal = document.getElementById('party-modal');
        const leaderModal = document.getElementById('leader-modal');
        
        if (event.target === partyModal) {
            partyModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (event.target === leaderModal) {
            leaderModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // AI Analysis functionality
    const aiBtn = document.getElementById('ai-analysis-btn');
    const aiModal = document.getElementById('ai-modal');
    const aiModalContent = document.getElementById('ai-modal-content');
    const closeAiModal = document.getElementById('close-ai-modal');
    const aiLoading = document.getElementById('ai-loading');
    const aiContent = document.getElementById('ai-content');
    const aiError = document.getElementById('ai-error');
    const retryBtn = document.getElementById('retry-ai-analysis');

    function openAiModal() {
        aiModal.classList.remove('hidden');
        setTimeout(() => {
            aiModal.classList.remove('opacity-0');
            aiModalContent.classList.remove('scale-95');
            aiModalContent.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
        
        // Start AI analysis
        performAiAnalysis();
    }

    function closeAiModalFunc() {
        aiModal.classList.add('opacity-0');
        aiModalContent.classList.remove('scale-100');
        aiModalContent.classList.add('scale-95');
        setTimeout(() => {
            aiModal.classList.add('hidden');
            // Reset states
            aiLoading.classList.remove('hidden');
            aiContent.classList.add('hidden');
            aiError.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function performAiAnalysis() {
        // Show loading state
        aiLoading.classList.remove('hidden');
        aiContent.classList.add('hidden');
        aiError.classList.add('hidden');

        // Gather polling data for AI analysis
        const pollingData = {
            parties: window.__PP_PARTIES__,
            date: new Date().toISOString().split('T')[0]
        };

        fetch('ajax/ai-polling-analysis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(pollingData)
        })
        .then(response => response.json())
        .then(data => {
            aiLoading.classList.add('hidden');
            
            if (data.success) {
                aiContent.innerHTML = formatAiResponse(data.content);
                aiContent.classList.remove('hidden');
            } else {
                showAiError();
            }
        })
        .catch(error => {
            console.error('AI Analysis error:', error);
            aiLoading.classList.add('hidden');
            showAiError();
        });
    }

    function showAiError() {
        aiError.classList.remove('hidden');
        aiContent.classList.add('hidden');
        aiLoading.classList.add('hidden');
    }

    function formatAiResponse(content) {
        // Format the AI response with nice styling
        const sections = content.split('\n\n');
        let formattedContent = '';
        
        sections.forEach(section => {
            if (section.trim()) {
                if (section.includes('**') || section.includes('#')) {
                    // Handle headers
                    section = section.replace(/\*\*(.*?)\*\*/g, '<h3 class="text-lg font-bold text-slate-900 mt-6 mb-3">$1</h3>');
                    section = section.replace(/### (.*)/g, '<h3 class="text-lg font-bold text-slate-900 mt-6 mb-3">$1</h3>');
                    section = section.replace(/## (.*)/g, '<h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">$1</h2>');
                } else {
                    // Regular paragraph
                    section = `<p class="text-slate-700 leading-relaxed mb-4">${section}</p>`;
                }
                formattedContent += section;
            }
        });
        
        return formattedContent;
    }

    // Event listeners
    aiBtn.addEventListener('click', openAiModal);
    closeAiModal.addEventListener('click', closeAiModalFunc);
    retryBtn.addEventListener('click', performAiAnalysis);

    // Close modal when clicking outside
    aiModal.addEventListener('click', function(e) {
        if (e.target === aiModal) {
            closeAiModalFunc();
        }
    });

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !aiModal.classList.contains('hidden')) {
            closeAiModalFunc();
        }
    });
});
</script>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/partijen-page-enhancements.css">

<?php include_once BASE_PATH . '/views/templates/footer.php'; ?>
</div>
</body>
</html>
