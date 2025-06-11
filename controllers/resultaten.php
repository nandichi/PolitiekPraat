<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

// Debug mode - zet op true voor live debugging
$debugMode = false;

if ($debugMode) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Haal share_id uit de URL
$shareId = $_GET['id'] ?? '';

if (empty($shareId)) {
    // Redirect naar stemwijzer als geen share_id
    header('Location: /stemwijzer');
    exit;
}

// Initialize de stemwijzer controller
$stemwijzerController = new StemwijzerController();

// Probeer de resultaten op te halen
$savedResults = null;
$stemwijzerData = null;
$errorMessage = '';

try {
    $savedResults = $stemwijzerController->getResultsByShareId($shareId);
    
    if ($savedResults) {
        // Haal ook de stemwijzer data op voor het tonen van details
        $stemwijzerData = $stemwijzerController->getStemwijzerData();
        
        if ($debugMode) {
            echo "<!-- DEBUG: Resultaten succesvol geladen voor share_id: $shareId -->\n";
            echo "<!-- DEBUG: Antwoorden: " . count($savedResults->answers) . " -->\n";
            echo "<!-- DEBUG: Resultaten: " . count($savedResults->results) . " -->\n";
        }
    } else {
        $errorMessage = 'Deze link is ongeldig of de resultaten zijn niet meer beschikbaar.';
        if ($debugMode) {
            echo "<!-- DEBUG: Geen resultaten gevonden voor share_id: $shareId -->\n";
        }
    }
    
} catch (Exception $e) {
    $errorMessage = 'Er is een fout opgetreden bij het laden van de resultaten.';
    if ($debugMode) {
        echo "<!-- DEBUG: FOUT bij laden resultaten: " . $e->getMessage() . " -->\n";
    }
}

require_once 'views/templates/header.php';
?>

<!-- Custom Styles for Results Page -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --primary-gradient: linear-gradient(135deg, #1a56db 0%, #c41e3a 100%);
    --secondary-gradient: linear-gradient(135deg, #dc2626 0%, #1e40af 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warm-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
}

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.text-gradient {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.floating-animation {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.pulse-glow {
    animation: pulse-glow 2s infinite;
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(26, 86, 219, 0.4); }
    50% { box-shadow: 0 0 40px rgba(26, 86, 219, 0.8); }
}

.slide-in-bottom {
    animation: slide-in-bottom 0.8s ease-out;
}

@keyframes slide-in-bottom {
    0% { transform: translateY(100px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .shadow-xl, .shadow-2xl, .shadow-lg {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    body {
        font-size: 12px;
        line-height: 1.4;
    }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-red-50">
    
    <!-- Modern Hero Section -->
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <!-- Primary Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-slate-900 to-red-900"></div>
            
            <!-- Animated Orbs -->
            <div class="absolute top-0 left-0 w-64 sm:w-96 h-64 sm:h-96 rounded-full bg-gradient-to-r from-blue-400/30 to-red-400/30 blur-3xl floating-animation"></div>
            <div class="absolute bottom-0 right-0 w-48 sm:w-80 h-48 sm:h-80 rounded-full bg-gradient-to-r from-red-400/20 to-blue-400/20 blur-3xl floating-animation" style="animation-delay: -3s;"></div>
            <div class="absolute top-1/2 left-1/2 w-32 sm:w-64 h-32 sm:h-64 rounded-full bg-gradient-to-r from-blue-400/25 to-red-400/25 blur-2xl floating-animation" style="animation-delay: -1.5s;"></div>
            
            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-4 sm:px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <?php if ($savedResults): ?>
                <!-- Success State -->
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-white/90 text-sm font-medium mb-6 slide-in-bottom">
                    <svg class="w-4 h-4 mr-2 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Opgeslagen resultaten - <?= date('d-m-Y H:i', strtotime($savedResults->completed_at)) ?>
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-6 leading-tight slide-in-bottom" style="animation-delay: 0.2s;">
                    <span class="block text-gradient bg-gradient-to-r from-green-300 via-blue-300 to-green-300 bg-clip-text text-transparent">
                        Jouw Stemwijzer
                    </span>
                    <span class="text-white">Resultaten</span>
                </h1>
                
                <p class="text-lg md:text-xl text-blue-100/80 mb-8 max-w-3xl mx-auto leading-relaxed font-light slide-in-bottom" style="animation-delay: 0.4s;">
                    Bekijk hier de resultaten van jouw stemwijzer test. Deze pagina kan je <strong class="text-blue-200 font-semibold">bookmarken of delen</strong> 
                    om je resultaten later opnieuw te bekijken.
                </p>
                
                <?php else: ?>
                <!-- Error State -->
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-effect text-white/90 text-sm font-medium mb-6 slide-in-bottom">
                    <svg class="w-4 h-4 mr-2 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    Resultaten niet gevonden
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-6 leading-tight slide-in-bottom" style="animation-delay: 0.2s;">
                    <span class="text-white">Oeps!</span>
                    <span class="block text-gradient bg-gradient-to-r from-red-300 via-orange-300 to-red-300 bg-clip-text text-transparent">
                        Link Niet Gevonden
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-blue-100/80 mb-8 max-w-3xl mx-auto leading-relaxed font-light slide-in-bottom" style="animation-delay: 0.4s;">
                    <?= htmlspecialchars($errorMessage) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="container mx-auto px-6 -mt-8 relative z-10">
        
        <?php if ($savedResults && $stemwijzerData): ?>
        
        <?php
        // Bereken de finale resultaten opnieuw voor display
        $parties = array_keys($stemwijzerData['parties']);
        $finalResults = [];
        
        foreach ($savedResults->results as $partyName => $result) {
            $finalResults[] = [
                'name' => $partyName,
                'agreement' => $result['agreement'] ?? 0,
                'logo' => $stemwijzerData['partyLogos'][$partyName] ?? ''
            ];
        }
        
        // Sorteer op agreement percentage
        usort($finalResults, function($a, $b) {
            return $b['agreement'] - $a['agreement'];
        });
        
        // Genereer persoonlijkheidsanalyse
        $personalityAnalysis = $stemwijzerController->analyzePoliticalPersonality($savedResults->answers, $stemwijzerData['questions']);
        ?>
        
        <!-- Politieke Persoonlijkheidsanalyse Sectie -->
        <div class="max-w-6xl mx-auto pb-12">
            <!-- Persoonlijkheidsanalyse Hero -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg shadow-purple-500/25 mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Jouw Politieke
                    <span class="text-gradient bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Persoonlijkheid
                    </span>
                </h2>
                
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-8">
                    Op basis van jouw antwoorden hebben we een uniek profiel samengesteld dat jouw politieke voorkeur en persoonlijkheid beschrijft.
                </p>
            </div>

            <!-- Hoofdprofiel Card -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden mb-12">
                <?php
                $profileColorClass = 'bg-gradient-to-r ' . $personalityAnalysis['political_profile']['color'];
                ?>
                <div class="<?= $profileColorClass ?> p-8 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/10 blur-3xl transform translate-x-32 -translate-y-32"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-3xl font-bold mb-2"><?= htmlspecialchars($personalityAnalysis['political_profile']['type']) ?></h3>
                                <p class="text-white/90 text-lg leading-relaxed"><?= htmlspecialchars($personalityAnalysis['political_profile']['description']) ?></p>
                            </div>
                            <div class="text-6xl opacity-20">🗳️</div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold"><?= round($personalityAnalysis['left_right_percentage']) ?>%</div>
                                <div class="text-sm opacity-90">Rechts</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold"><?= round($personalityAnalysis['progressive_percentage']) ?>%</div>
                                <div class="text-sm opacity-90">Progressief</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold"><?= round($personalityAnalysis['authoritarian_percentage']) ?>%</div>
                                <div class="text-sm opacity-90">Autoritair</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold"><?= round($personalityAnalysis['eu_pro_percentage']) ?>%</div>
                                <div class="text-sm opacity-90">Pro-EU</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Politiek Kompas -->
                <div class="p-8">
                    <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jouw Positie op het Politieke Kompas</h4>
                    
                    <div class="max-w-md mx-auto mb-8">
                        <div class="relative w-80 h-80 mx-auto">
                            <!-- Kompas achtergrond -->
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full border-2 border-gray-300"></div>
                            
                            <!-- Kwadranten -->
                            <div class="absolute inset-0 grid grid-cols-2 grid-rows-2 rounded-full overflow-hidden">
                                <div class="bg-green-200/30 border-r border-b border-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-green-700 text-center">Links<br/>Liberaal</span>
                                </div>
                                <div class="bg-blue-200/30 border-b border-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-700 text-center">Rechts<br/>Liberaal</span>
                                </div>
                                <div class="bg-red-200/30 border-r border-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-red-700 text-center">Links<br/>Autoritair</span>
                                </div>
                                <div class="bg-indigo-200/30 flex items-center justify-center">
                                    <span class="text-xs font-medium text-indigo-700 text-center">Rechts<br/>Autoritair</span>
                                </div>
                            </div>
                            
                            <!-- Assen labels -->
                            <div class="absolute top-2 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600">Liberaal</div>
                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600">Autoritair</div>
                            <div class="absolute left-2 top-1/2 transform -translate-y-1/2 text-xs font-medium text-gray-600 -rotate-90">Links</div>
                            <div class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs font-medium text-gray-600 rotate-90">Rechts</div>
                            
                            <!-- Jouw positie -->
                            <?php 
                            $x = ($personalityAnalysis['compass_position']['x'] + 50) * 2.8; // Schaal naar pixels
                            $y = (-$personalityAnalysis['compass_position']['y'] + 50) * 2.8; // Inverteer Y-as
                            $quadrantColor = $personalityAnalysis['compass_position']['quadrant']['color'];
                            $dotColorClass = 'bg-' . $quadrantColor . '-500';
                            ?>
                            <div class="absolute w-4 h-4 <?= $dotColorClass ?> rounded-full border-2 border-white shadow-lg transform -translate-x-2 -translate-y-2"
                                 style="left: <?= $x ?>px; top: <?= $y ?>px;">
                            </div>
                            
                            <!-- Centrum punt -->
                            <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-gray-400 rounded-full transform -translate-x-1 -translate-y-1"></div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <?php
                        $badgeColorClass = 'bg-' . $quadrantColor . '-100 text-' . $quadrantColor . '-800';
                        $dotBadgeColorClass = 'bg-' . $quadrantColor . '-500';
                        ?>
                        <div class="inline-flex items-center px-4 py-2 <?= $badgeColorClass ?> rounded-full font-semibold">
                            <div class="w-3 h-3 <?= $dotBadgeColorClass ?> rounded-full mr-2"></div>
                            <?= htmlspecialchars($personalityAnalysis['compass_position']['quadrant']['name']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Persoonlijkheidskenmerken -->
            <?php if (!empty($personalityAnalysis['personality_traits'])): ?>
            <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 mb-12">
                <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jouw Politieke Kenmerken</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($personalityAnalysis['personality_traits'] as $trait): ?>
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 text-center hover:shadow-lg transition-shadow">
                        <div class="text-4xl mb-4"><?= $trait['icon'] ?></div>
                        <h5 class="text-lg font-bold text-gray-800 mb-2"><?= htmlspecialchars($trait['name']) ?></h5>
                        <p class="text-gray-600 text-sm leading-relaxed"><?= htmlspecialchars($trait['description']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Politieke Assen Analyse -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 mb-16">
                <h4 class="text-2xl font-bold text-gray-800 mb-8 text-center">Gedetailleerde Politieke Analyse</h4>
                
                <div class="space-y-8">
                    <!-- Links-Rechts As -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-600">Economisch Links</span>
                            <span class="text-sm font-medium text-gray-600">Economisch Rechts</span>
                        </div>
                        <div class="relative h-4 bg-gradient-to-r from-red-200 via-gray-200 to-blue-200 rounded-full">
                            <div class="absolute top-0 h-full bg-gradient-to-r from-red-500 to-blue-500 rounded-full transition-all duration-700"
                                 style="width: <?= round($personalityAnalysis['left_right_percentage']) ?>%; opacity: 0.8;"></div>
                            <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                 style="left: calc(<?= round($personalityAnalysis['left_right_percentage']) ?>% - 6px);"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-lg font-bold text-gray-700"><?= round($personalityAnalysis['left_right_percentage']) ?>% Rechts georiënteerd</span>
                        </div>
                    </div>

                    <!-- Progressief-Conservatief As -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-600">Conservatief</span>
                            <span class="text-sm font-medium text-gray-600">Progressief</span>
                        </div>
                        <div class="relative h-4 bg-gradient-to-r from-orange-200 via-gray-200 to-green-200 rounded-full">
                            <div class="absolute top-0 h-full bg-gradient-to-r from-orange-500 to-green-500 rounded-full transition-all duration-700"
                                 style="width: <?= round($personalityAnalysis['progressive_percentage']) ?>%; opacity: 0.8;"></div>
                            <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                 style="left: calc(<?= round($personalityAnalysis['progressive_percentage']) ?>% - 6px);"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-lg font-bold text-gray-700"><?= round($personalityAnalysis['progressive_percentage']) ?>% Progressief</span>
                        </div>
                    </div>

                    <!-- Autoritair-Libertair As -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-600">Libertair</span>
                            <span class="text-sm font-medium text-gray-600">Autoritair</span>
                        </div>
                        <div class="relative h-4 bg-gradient-to-r from-purple-200 via-gray-200 to-red-200 rounded-full">
                            <div class="absolute top-0 h-full bg-gradient-to-r from-purple-500 to-red-500 rounded-full transition-all duration-700"
                                 style="width: <?= round($personalityAnalysis['authoritarian_percentage']) ?>%; opacity: 0.8;"></div>
                            <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                 style="left: calc(<?= round($personalityAnalysis['authoritarian_percentage']) ?>% - 6px);"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-lg font-bold text-gray-700"><?= round($personalityAnalysis['authoritarian_percentage']) ?>% Autoritair</span>
                        </div>
                    </div>

                    <!-- EU As -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-600">EU Skeptisch</span>
                            <span class="text-sm font-medium text-gray-600">Pro-EU</span>
                        </div>
                        <div class="relative h-4 bg-gradient-to-r from-yellow-200 via-gray-200 to-blue-200 rounded-full">
                            <div class="absolute top-0 h-full bg-gradient-to-r from-yellow-500 to-blue-500 rounded-full transition-all duration-700"
                                 style="width: <?= round($personalityAnalysis['eu_pro_percentage']) ?>%; opacity: 0.8;"></div>
                            <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                 style="left: calc(<?= round($personalityAnalysis['eu_pro_percentage']) ?>% - 6px);"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-lg font-bold text-gray-700"><?= round($personalityAnalysis['eu_pro_percentage']) ?>% Pro-EU</span>
                        </div>
                    </div>
                </div>

                <!-- Statistieken -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div class="bg-blue-50 rounded-xl p-4">
                            <div class="text-2xl font-bold text-blue-600"><?= $personalityAnalysis['total_answered'] ?></div>
                            <div class="text-sm text-blue-700">Vragen beantwoord</div>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-4">
                            <div class="text-2xl font-bold text-purple-600"><?= htmlspecialchars($personalityAnalysis['political_profile']['type']) ?></div>
                            <div class="text-sm text-purple-700">Politiek Type</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Display - Reuse styling from stemwijzer.php -->
        <div class="max-w-6xl mx-auto pb-20">
            
            <!-- Results Hero Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/25 mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Jouw Politieke
                    <span class="text-gradient bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                        Matches
                    </span>
                </h2>
                
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Op basis van jouw antwoorden hebben we de partijen gerangschikt die het beste bij jouw politieke voorkeuren passen.
                </p>
            </div>

            <!-- Top 3 Podium -->
            <div class="relative mb-16">
                <!-- Podium Background -->
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-4xl h-32 bg-gradient-to-r from-yellow-100 via-yellow-50 to-yellow-100 rounded-3xl opacity-30"></div>
                
                <div class="relative grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <?php if (count($finalResults) >= 2): ?>
                    <!-- 2nd Place -->
                    <div class="relative md:order-1 transform md:translate-y-8">
                        <!-- Position Badge -->
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-white font-bold text-lg">2</span>
                            </div>
                        </div>
                        
                        <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 pt-10 text-center relative overflow-hidden card-hover">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-50/30 to-slate-50/30"></div>
                            
                            <div class="relative z-10">
                                <!-- Party Logo -->
                                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-white shadow-lg border border-gray-200 p-2">
                                    <img src="<?= htmlspecialchars($finalResults[1]['logo']) ?>" alt="<?= htmlspecialchars($finalResults[1]['name']) ?>" class="w-full h-full object-contain rounded-xl">
                                </div>
                                
                                <!-- Party Name -->
                                <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($finalResults[1]['name']) ?></h3>
                                
                                <!-- Percentage -->
                                <div class="text-4xl font-bold text-gray-600 mb-2"><?= $finalResults[1]['agreement'] ?>%</div>
                                <div class="text-sm text-gray-500 mb-6">overeenkomst</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (count($finalResults) >= 1): ?>
                    <!-- 1st Place (Winner) -->
                    <div class="relative md:order-2 transform md:-translate-y-4">
                        <!-- Winner Crown -->
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Winner Sparkles -->
                        <div class="absolute -top-4 -left-4 w-3 h-3 bg-yellow-400 rounded-full animate-ping"></div>
                        <div class="absolute -top-2 -right-6 w-2 h-2 bg-yellow-500 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
                        <div class="absolute top-8 -left-6 w-2 h-2 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 1s;"></div>
                        
                        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-3xl shadow-2xl border-2 border-yellow-200 p-8 pt-12 text-center relative overflow-hidden transform hover:scale-105 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-100/20 to-amber-100/20"></div>
                            
                            <div class="relative z-10">
                                <!-- Winner Badge -->
                                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-full text-sm font-bold mb-4 shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                                    </svg>
                                    Beste Match!
                                </div>
                                
                                <!-- Party Logo -->
                                <div class="w-24 h-24 mx-auto mb-4 rounded-2xl bg-white shadow-xl border-2 border-yellow-200 p-2">
                                    <img src="<?= htmlspecialchars($finalResults[0]['logo']) ?>" alt="<?= htmlspecialchars($finalResults[0]['name']) ?>" class="w-full h-full object-contain rounded-xl">
                                </div>
                                
                                <!-- Party Name -->
                                <h3 class="text-3xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($finalResults[0]['name']) ?></h3>
                                
                                <!-- Percentage -->
                                <div class="text-5xl font-bold text-yellow-600 mb-2"><?= $finalResults[0]['agreement'] ?>%</div>
                                <div class="text-sm text-gray-600 mb-6">overeenkomst</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (count($finalResults) >= 3): ?>
                    <!-- 3rd Place -->
                    <div class="relative md:order-3 transform md:translate-y-12">
                        <!-- Position Badge -->
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-600 to-orange-700 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-white font-bold text-lg">3</span>
                            </div>
                        </div>
                        
                        <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 pt-10 text-center relative overflow-hidden card-hover">
                            <div class="absolute inset-0 bg-gradient-to-br from-orange-50/30 to-amber-50/30"></div>
                            
                            <div class="relative z-10">
                                <!-- Party Logo -->
                                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-white shadow-lg border border-gray-200 p-2">
                                    <img src="<?= htmlspecialchars($finalResults[2]['logo']) ?>" alt="<?= htmlspecialchars($finalResults[2]['name']) ?>" class="w-full h-full object-contain rounded-xl">
                                </div>
                                
                                <!-- Party Name -->
                                <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($finalResults[2]['name']) ?></h3>
                                
                                <!-- Percentage -->
                                <div class="text-4xl font-bold text-orange-600 mb-2"><?= $finalResults[2]['agreement'] ?>%</div>
                                <div class="text-sm text-gray-500 mb-6">overeenkomst</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Complete Results List -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden mb-12">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Volledige Ranglijst</h3>
                    </div>
                </div>
                
                <!-- Results List -->
                <div class="divide-y divide-gray-100">
                    <?php foreach ($finalResults as $index => $result): ?>
                    <div class="px-8 py-6 hover:bg-gray-50 transition-all duration-300">
                        <div class="flex items-center space-x-6">
                            <!-- Rank -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg
                                     <?php 
                                        if ($index === 0) echo 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white shadow-lg shadow-yellow-500/25';
                                        elseif ($index === 1) echo 'bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-lg shadow-gray-500/25';
                                        elseif ($index === 2) echo 'bg-gradient-to-br from-amber-600 to-orange-700 text-white shadow-lg shadow-orange-500/25';
                                        else echo 'bg-gray-100 text-gray-600';
                                     ?>">
                                    <span><?= $index + 1 ?></span>
                                </div>
                            </div>
                            
                            <!-- Party Info -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Logo -->
                                <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 p-2 shadow-sm">
                                    <img src="<?= htmlspecialchars($result['logo']) ?>" alt="<?= htmlspecialchars($result['name']) ?>" class="w-full h-full object-contain rounded-lg">
                                </div>
                                
                                <!-- Name & Progress -->
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($result['name']) ?></h4>
                                    
                                    <!-- Progress Bar -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-700 ease-out
                                                 <?php 
                                                    if ($index === 0) echo 'bg-gradient-to-r from-yellow-400 to-yellow-600';
                                                    elseif ($index === 1) echo 'bg-gradient-to-r from-gray-400 to-gray-600';
                                                    elseif ($index === 2) echo 'bg-gradient-to-r from-amber-600 to-orange-700';
                                                    else echo 'bg-gradient-to-r from-indigo-500 to-purple-600';
                                                 ?>"
                                                 style="width: <?= $result['agreement'] ?>%">
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500 min-w-[4rem]"><?= $result['agreement'] ?>%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Percentage -->
                            <div class="text-right">
                                <div class="text-3xl font-bold text-gray-800"><?= $result['agreement'] ?>%</div>
                                <div class="text-sm text-gray-500">match</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Share and Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 no-print">
                <button onclick="window.print()" 
                        class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-indigo-500/25 flex items-center space-x-3">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Resultaten afdrukken</span>
                </button>
                
                <button onclick="shareResults()" 
                        class="group px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-green-500/25 flex items-center space-x-3">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    <span>Link delen</span>
                </button>
                
                <a href="/stemwijzer" 
                   class="group px-8 py-4 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-2xl font-semibold transition-all duration-300 hover:shadow-lg flex items-center space-x-3">
                    <svg class="w-5 h-5 group-hover:-rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Nieuwe test doen</span>
                </a>
            </div>

        </div>
        
        <?php else: ?>
        
        <!-- Error State Content -->
        <div class="max-w-4xl mx-auto pb-20">
            <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 md:p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-red-50/30 to-orange-50/30"></div>
                
                <div class="relative z-10">
                    <!-- Error Icon -->
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-red-500 to-orange-600 shadow-lg shadow-red-500/25 mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Resultaten niet gevonden</h2>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                        <?= htmlspecialchars($errorMessage) ?>
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="/stemwijzer" 
                           class="group px-8 py-4 bg-gradient-to-r from-blue-600 to-red-600 hover:from-blue-700 hover:to-red-700 text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-blue-500/25 flex items-center space-x-3">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            <span>Doe de Stemwijzer test</span>
                        </a>
                        
                        <a href="/" 
                           class="group px-8 py-4 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-2xl font-semibold transition-all duration-300 hover:shadow-lg flex items-center space-x-3">
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>
                            <span>Terug naar home</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</main>

<script>
function shareResults() {
    const url = window.location.href;
    const title = 'Mijn Stemwijzer 2025 Resultaten';
    const text = 'Bekijk mijn politieke matches van de Stemwijzer 2025!';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        }).catch(err => {
            console.log('Error sharing:', err);
            fallbackShare(url);
        });
    } else {
        fallbackShare(url);
    }
}

function fallbackShare(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link gekopieerd naar klembord!', 'success');
        }).catch(() => {
            showNotification('Kon link niet kopiëren', 'error');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.select();
        
        try {
            document.execCommand('copy');
            showNotification('Link gekopieerd naar klembord!', 'success');
        } catch (err) {
            showNotification('Kon link niet kopiëren', 'error');
        }
        
        document.body.removeChild(textArea);
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full`;
    
    // Style based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-500', 'text-white');
            break;
        default:
            notification.classList.add('bg-blue-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>

<?php require_once 'views/templates/footer.php'; ?> 