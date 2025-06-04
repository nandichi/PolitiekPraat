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

// Initialize de stemwijzer controller
$stemwijzerController = new StemwijzerController();

// Haal stemwijzer data op uit de database
try {
    if ($debugMode) {
        echo "<!-- DEBUG: Probeer stemwijzer data te laden -->\n";
    }
    
    $stemwijzerData = $stemwijzerController->getStemwijzerData();
    $totalQuestions = count($stemwijzerData['questions']);
    
    if ($debugMode) {
        echo "<!-- DEBUG: Stemwijzer data succesvol geladen -->\n";
        echo "<!-- DEBUG: Aantal vragen: $totalQuestions -->\n";
        echo "<!-- DEBUG: Aantal partijen: " . count($stemwijzerData['parties']) . " -->\n";
        echo "<!-- DEBUG: Aantal party logos: " . count($stemwijzerData['partyLogos']) . " -->\n";
        
        if (empty($stemwijzerData['questions'])) {
            echo "<!-- DEBUG: WAARSCHUWING - Geen vragen gevonden! -->\n";
        }
    }
    
} catch (Exception $e) {
    if ($debugMode) {
        echo "<!-- DEBUG: FOUT bij laden stemwijzer data: " . $e->getMessage() . " -->\n";
        echo "<!-- DEBUG: Stack trace: " . $e->getTraceAsString() . " -->\n";
    }
    
    // Fallback naar hardcoded data als database faalt
    $stemwijzerData = ['questions' => [], 'parties' => [], 'partyLogos' => []];
    $totalQuestions = 0;
}

require_once 'views/templates/header.php';
?>

<!-- Custom Styles for Modern Stemwijzer -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
    0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); }
    50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.8); }
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

.progress-ring {
    transform: rotate(-90deg);
}

.progress-circle {
    transition: stroke-dashoffset 0.6s ease-in-out;
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Modern Hero Section -->
    <section class="relative min-h-[80vh] sm:min-h-[60vh] flex items-center justify-center overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <!-- Primary Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-blue-900 to-purple-900"></div>
            
            <!-- Animated Orbs -->
            <div class="absolute top-0 left-0 w-64 sm:w-96 h-64 sm:h-96 rounded-full bg-gradient-to-r from-blue-400/30 to-purple-400/30 blur-3xl floating-animation"></div>
            <div class="absolute bottom-0 right-0 w-48 sm:w-80 h-48 sm:h-80 rounded-full bg-gradient-to-r from-pink-400/20 to-blue-400/20 blur-3xl floating-animation" style="animation-delay: -3s;"></div>
            <div class="absolute top-1/2 left-1/2 w-32 sm:w-64 h-32 sm:h-64 rounded-full bg-gradient-to-r from-cyan-400/25 to-blue-400/25 blur-2xl floating-animation" style="animation-delay: -1.5s;"></div>
            
            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-4 sm:px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center px-3 sm:px-4 py-2 rounded-full glass-effect text-white/90 text-xs sm:text-sm font-medium mb-6 sm:mb-8 slide-in-bottom">
                    <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-2 text-red-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    🚨 Coalitie Gevallen - Nieuwe Verkiezingen 2025
                </div>
                
                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-4 sm:mb-6 leading-tight slide-in-bottom" style="animation-delay: 0.2s;">
                    Na de 
                    <span class="text-red-300">Politieke Crisis:</span>
                    <span class="block text-gradient bg-gradient-to-r from-blue-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">
                        Vind Jouw Stem
                    </span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-blue-100/80 mb-6 sm:mb-8 max-w-4xl mx-auto leading-relaxed font-light slide-in-bottom px-2 sm:px-0" style="animation-delay: 0.4s;">
                    De coalitie is gevallen en Nederland staat voor cruciale keuzes. <strong class="text-blue-200 font-semibold">Welke partij vertegenwoordigt echt jouw visie?</strong> 
                    Ontdek het met onze uitgebreide politieke match-test.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="container mx-auto px-6 -mt-8 relative z-10">
        <!-- Loading indicator -->
        <div id="loading-indicator" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-3xl p-8 text-center shadow-2xl">
                <div class="w-16 h-16 mx-auto mb-6">
                    <svg class="animate-spin w-full h-full text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stemwijzer App Container -->
        <div class="max-w-6xl mx-auto pb-20" x-data="stemwijzer()">
            
            <!-- Modern Progress Card -->
            <div x-show="screen !== 'start'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-y-8"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mb-8">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 relative overflow-hidden">
                    <!-- Decorative background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-indigo-200/20 to-purple-200/20 blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <!-- Progress Header -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-4">
                                <!-- Circular Progress -->
                                <div class="relative w-16 h-16">
                                    <svg class="w-full h-full progress-ring" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="16" fill="none" stroke="#e5e7eb" stroke-width="2"></circle>
                                        <circle cx="18" cy="18" r="16" fill="none" stroke="url(#progressGradient)" stroke-width="2" 
                                                stroke-linecap="round" class="progress-circle"
                                                :stroke-dasharray="100"
                                                :stroke-dashoffset="100 - (currentStep / totalSteps * 100)">
                                        </circle>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold text-indigo-600" x-text="currentStep + 1"></span>
                                    </div>
                                    <!-- Gradient Definition -->
                                    <defs>
                                        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#667eea"/>
                                            <stop offset="100%" stop-color="#764ba2"/>
                                        </linearGradient>
                                    </defs>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Vraag <span x-text="currentStep + 1"></span></h3>
                                    <p class="text-sm text-gray-500">van <span x-text="totalSteps"></span> stellingen</p>
                                </div>
                            </div>
                            
                            <!-- Progress Percentage -->
                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600" x-text="Math.round((currentStep / totalSteps) * 100) + '%'"></div>
                                <div class="text-xs text-gray-500">voltooid</div>
                            </div>
                        </div>
                        
                        <!-- Modern Progress Bar -->
                        <div class="relative">
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full transition-all duration-700 ease-out relative"
                                     :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                                    <!-- Shine effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                </div>
                            </div>
                            
                            <!-- Progress Milestones -->
                            <div class="flex justify-between mt-4">
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full bg-indigo-500 mb-1"></div>
                                    <span>Start</span>
                                </div>
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full" :class="currentStep >= totalSteps/2 ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                    <span>Halverwege</span>
                                </div>
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full" :class="currentStep >= totalSteps-1 ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                    <span>Einde</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ultra Modern Start Screen -->
            <div x-show="screen === 'start'" 
                 x-transition:enter="transition ease-out duration-800"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="relative">
                
                <!-- Main Start Card -->
                <div class="bg-white/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl shadow-2xl border border-white/50 p-6 sm:p-8 md:p-10 lg:p-12 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/30 via-purple-50/20 to-pink-50/30"></div>
                    <div class="absolute top-0 right-0 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 rounded-full bg-gradient-to-br from-indigo-200/20 to-purple-200/20 blur-3xl floating-animation"></div>
                    <div class="absolute bottom-0 left-0 w-24 sm:w-36 md:w-48 h-24 sm:h-36 md:h-48 rounded-full bg-gradient-to-tr from-pink-200/20 to-blue-200/20 blur-3xl floating-animation" style="animation-delay: -2s;"></div>
                    
                    <div class="relative z-10">
                        <!-- Header Section -->
                        <div class="text-center mb-8 sm:mb-10 md:mb-12">
                            <!-- Icon -->
                            <div class="inline-flex items-center justify-center w-16 sm:w-18 md:w-20 h-16 sm:h-18 md:h-20 rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/25 mb-4 sm:mb-5 md:mb-6">
                                <svg class="w-8 sm:w-9 md:w-10 h-8 sm:h-9 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3 sm:mb-4">
                                Welkom bij de 
                                <span class="block sm:inline text-gradient bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    Stemwijzer
                                </span>
                            </h2>
                            
                            <p class="text-base sm:text-lg md:text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto px-2 sm:px-0">
                                Ontdek welke partij het beste aansluit bij jouw politieke overtuigingen door onze uitgebreide vragenlijst in te vullen.
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-10 md:mb-12">
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-blue-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 sm:w-5.5 md:w-6 h-5 sm:h-5.5 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">Actuele Standpunten</h3>
                                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Gebaseerd op de nieuwste partijprogramma's en uitspraken van politieke leiders.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 sm:w-5.5 md:w-6 h-5 sm:h-5.5 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">100% Anoniem</h3>
                                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Je antwoorden worden niet opgeslagen en blijven volledig privé.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-purple-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 sm:w-5.5 md:w-6 h-5 sm:h-5.5 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">Gedetailleerde Analyse</h3>
                                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Krijg inzicht in waarom bepaalde partijen bij je passen.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-orange-50 to-red-50 border border-orange-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 sm:w-5.5 md:w-6 h-5 sm:h-5.5 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">Snel & Efficiënt</h3>
                                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Voltooi in ongeveer 10 minuten en krijg direct je resultaten.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Section -->
                        <div class="text-center">
                            <button @click="startQuiz()" 
                                    class="group relative w-full sm:w-auto px-8 sm:px-10 md:px-12 py-3 sm:py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-base sm:text-lg rounded-xl sm:rounded-2xl shadow-xl shadow-indigo-500/25 hover:shadow-2xl hover:shadow-indigo-500/40 transform hover:scale-105 transition-all duration-300 mb-4 sm:mb-6">
                                <div class="flex items-center justify-center space-x-2 sm:space-x-3">
                                    <span>Start de Stemwijzer</span>
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                                <!-- Shine effect -->
                                <div class="absolute inset-0 rounded-xl sm:rounded-2xl bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            </button>
                            
                            <p class="text-xs sm:text-sm text-gray-500 px-4 sm:px-0">
                                Geen account nodig • Volledig gratis • Direct resultaat
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ultra Modern Questions Screen -->
            <div x-show="screen === 'questions'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-y-8"
                 x-transition:enter-end="opacity-100 transform translate-y-0">

                <!-- Questions Layout Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
                    
                    <!-- Main Question Card (Left/Center) -->
                    <div class="xl:col-span-2">
                        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl shadow-2xl border border-white/50 p-4 sm:p-6 md:p-8 lg:p-10 relative overflow-hidden">
                            <!-- Decorative Background -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 via-indigo-50/20 to-purple-50/30"></div>
                            <div class="absolute top-0 right-0 w-24 sm:w-32 md:w-48 h-24 sm:h-32 md:h-48 rounded-full bg-gradient-to-br from-blue-200/10 to-purple-200/10 blur-3xl"></div>
                            
                            <div class="relative z-10">
                                <!-- Question Header -->
                                <div class="flex items-center justify-between mb-6 sm:mb-8">
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        <div class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full">
                                            <span class="text-xs sm:text-sm font-semibold text-indigo-700">
                                                Vraag <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Navigation Buttons -->
                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                        <button @click="previousQuestion()" 
                                                x-show="currentStep > 0"
                                                class="p-2 sm:p-3 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors group">
                                            <svg class="w-3 sm:w-4 h-3 sm:h-4 text-gray-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button @click="skipQuestion()"
                                                class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-xs sm:text-sm font-medium text-gray-600 transition-colors">
                                            Overslaan
                                        </button>
                                    </div>
                                </div>

                                <!-- Question Content -->
                                <div class="mb-8 sm:mb-10">
                                    <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 sm:mb-6 leading-tight" x-text="questions[currentStep].title"></h2>
                                    <p class="text-sm sm:text-base md:text-lg text-gray-600 leading-relaxed mb-4 sm:mb-6" x-text="questions[currentStep].description"></p>
                                    
                                    <!-- Explanation Toggle -->
                                    <button @click="showExplanation = !showExplanation"
                                            class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-50 hover:bg-blue-100 rounded-lg sm:rounded-xl text-blue-700 text-xs sm:text-sm font-medium transition-colors border border-blue-200/50">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Meer uitleg</span>
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 ml-1.5 sm:ml-2 transition-transform duration-200" :class="showExplanation ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <!-- Detailed Explanation Panel -->
                                    <div x-show="showExplanation" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mt-4 sm:mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl border border-blue-200/30 overflow-hidden">
                                        
                                        <div class="p-4 sm:p-6">
                                            <h3 class="text-base sm:text-lg font-semibold text-blue-900 mb-3 sm:mb-4">Context van deze stelling</h3>
                                            <p class="text-blue-800 leading-relaxed mb-4 sm:mb-6 text-sm sm:text-base" x-text="questions[currentStep].context"></p>
                                            
                                            <div class="grid grid-cols-1 gap-3 sm:gap-4">
                                                <div class="bg-emerald-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-emerald-200/50">
                                                    <h4 class="font-semibold text-emerald-800 mb-2 flex items-center text-sm sm:text-base">
                                                        <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>
                                                        Links
                                                    </h4>
                                                    <p class="text-emerald-700 text-xs sm:text-sm leading-relaxed" x-text="questions[currentStep].left_view"></p>
                                                </div>
                                                
                                                <div class="bg-red-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-red-200/50">
                                                    <h4 class="font-semibold text-red-800 mb-2 flex items-center text-sm sm:text-base">
                                                        <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                                        Rechts
                                                    </h4>
                                                    <p class="text-red-700 text-xs sm:text-sm leading-relaxed" x-text="questions[currentStep].right_view"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modern Answer Options -->
                                <div class="space-y-3 sm:space-y-4">
                                    <!-- Eens Option -->
                                    <button @click="answerQuestion('eens')"
                                            class="group w-full p-4 sm:p-5 md:p-6 bg-gradient-to-r from-emerald-50 to-green-50 hover:from-emerald-100 hover:to-green-100 border-2 border-emerald-200 hover:border-emerald-300 rounded-xl sm:rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-emerald-100 transform hover:-translate-y-1">
                                        <div class="flex items-center space-x-3 sm:space-x-4 md:space-x-5">
                                            <div class="w-10 sm:w-12 md:w-14 h-10 sm:h-12 md:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 sm:w-6 md:w-7 h-5 sm:h-6 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <div class="text-left flex-1">
                                                <h3 class="text-lg sm:text-xl font-bold text-emerald-700 mb-1">Eens</h3>
                                                <p class="text-emerald-600 text-xs sm:text-sm">Ik ben het eens met deze stelling</p>
                                            </div>
                                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-emerald-500 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Neutraal Option -->
                                    <button @click="answerQuestion('neutraal')"
                                            class="group w-full p-4 sm:p-5 md:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border-2 border-blue-200 hover:border-blue-300 rounded-xl sm:rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-blue-100 transform hover:-translate-y-1">
                                        <div class="flex items-center space-x-3 sm:space-x-4 md:space-x-5">
                                            <div class="w-10 sm:w-12 md:w-14 h-10 sm:h-12 md:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 sm:w-6 md:w-7 h-5 sm:h-6 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6"/>
                                                </svg>
                                            </div>
                                            <div class="text-left flex-1">
                                                <h3 class="text-lg sm:text-xl font-bold text-blue-700 mb-1">Neutraal</h3>
                                                <p class="text-blue-600 text-xs sm:text-sm">Ik heb geen uitgesproken mening hierover</p>
                                            </div>
                                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-blue-500 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Oneens Option -->
                                    <button @click="answerQuestion('oneens')"
                                            class="group w-full p-4 sm:p-5 md:p-6 bg-gradient-to-r from-red-50 to-pink-50 hover:from-red-100 hover:to-pink-100 border-2 border-red-200 hover:border-red-300 rounded-xl sm:rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-red-100 transform hover:-translate-y-1">
                                        <div class="flex items-center space-x-3 sm:space-x-4 md:space-x-5">
                                            <div class="w-10 sm:w-12 md:w-14 h-10 sm:h-12 md:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-lg shadow-red-500/25 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 sm:w-6 md:w-7 h-5 sm:h-6 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <div class="text-left flex-1">
                                                <h3 class="text-lg sm:text-xl font-bold text-red-700 mb-1">Oneens</h3>
                                                <p class="text-red-600 text-xs sm:text-sm">Ik ben het oneens met deze stelling</p>
                                            </div>
                                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-red-500 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar - Party Info & Progress -->
                    <div class="xl:col-span-1 space-y-4 sm:space-y-6">
                        <!-- Progress Stats Card -->
                        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl shadow-2xl border border-white/50 p-4 sm:p-6 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-50/30 to-pink-50/30"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center space-x-2 sm:space-x-3 mb-4 sm:mb-6">
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                        <svg class="w-4 sm:w-5 h-4 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Jouw Voortgang</h3>
                                </div>
                                
                                <div class="space-y-3 sm:space-y-4">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs sm:text-sm font-medium text-gray-600">Beantwoord</span>
                                            <span class="text-xs sm:text-sm font-bold text-purple-600">
                                                <span x-text="Object.keys(answers).length"></span>/<span x-text="totalSteps"></span>
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500"
                                                 :style="'width: ' + (Object.keys(answers).length / totalSteps * 100) + '%'"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-100">
                                        <div class="text-center">
                                            <div class="text-sm sm:text-lg font-bold text-emerald-600" x-text="countAnswerType('eens')"></div>
                                            <div class="text-xs text-gray-500">Eens</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-sm sm:text-lg font-bold text-blue-600" x-text="countAnswerType('neutraal')"></div>
                                            <div class="text-xs text-gray-500">Neutraal</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-sm sm:text-lg font-bold text-red-600" x-text="countAnswerType('oneens')"></div>
                                            <div class="text-xs text-gray-500">Oneens</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Party Positions Card -->
                        <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-6 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/30 to-blue-50/30"></div>
                            
                            <div class="relative z-10">
                                <!-- Collapsible Header -->
                                <button @click="showPartyPositions = !showPartyPositions" 
                                        class="w-full flex items-center justify-between p-0 bg-transparent border-none cursor-pointer group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">Partij Standpunten</h3>
                                    </div>
                                    
                                    <!-- Toggle Icon -->
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500" x-show="!showPartyPositions">Klik om te bekijken</span>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-all duration-200" 
                                             :class="showPartyPositions ? 'rotate-180' : ''" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>
                                
                                <!-- Collapsible Content -->
                                <div x-show="showPartyPositions" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                                     class="mt-6 space-y-4" 
                                     x-init="updatePartyGroups()">
                                    <!-- Eens partijen -->
                                    <div x-show="eensParties.length > 0">
                                        <h4 class="text-sm font-semibold text-emerald-700 mb-3 flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></div>
                                            Eens (<span x-text="eensParties.length"></span>)
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="party in eensParties.slice(0, 6)" :key="party">
                                                <div class="relative group">
                                                    <div class="w-10 h-10 rounded-lg bg-white border-2 border-emerald-200 hover:border-emerald-300 p-1 transition-colors cursor-pointer">
                                                        <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-md">
                                                    </div>
                                                    <!-- Tooltip -->
                                                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                                        <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap">
                                                            <span x-text="party"></span>
                                                        </div>
                                                        <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 mx-auto"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- Neutraal partijen -->
                                    <div x-show="neutraalParties.length > 0">
                                        <h4 class="text-sm font-semibold text-blue-700 mb-3 flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                            Neutraal (<span x-text="neutraalParties.length"></span>)
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="party in neutraalParties.slice(0, 6)" :key="party">
                                                <div class="relative group">
                                                    <div class="w-10 h-10 rounded-lg bg-white border-2 border-blue-200 hover:border-blue-300 p-1 transition-colors cursor-pointer">
                                                        <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-md">
                                                    </div>
                                                    <!-- Tooltip -->
                                                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                                        <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap">
                                                            <span x-text="party"></span>
                                                        </div>
                                                        <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 mx-auto"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- Oneens partijen -->
                                    <div x-show="oneensParties.length > 0">
                                        <h4 class="text-sm font-semibold text-red-700 mb-3 flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                            Oneens (<span x-text="oneensParties.length"></span>)
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="party in oneensParties.slice(0, 6)" :key="party">
                                                <div class="relative group">
                                                    <div class="w-10 h-10 rounded-lg bg-white border-2 border-red-200 hover:border-red-300 p-1 transition-colors cursor-pointer">
                                                        <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-md">
                                                    </div>
                                                    <!-- Tooltip -->
                                                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                                        <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap">
                                                            <span x-text="party"></span>
                                                        </div>
                                                        <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 mx-auto"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ultra Modern Results Screen -->
            <div x-show="screen === 'results'" 
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
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
                                        <img :src="finalResults[1]?.logo" :alt="finalResults[1]?.name" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                    
                                    <!-- Party Name -->
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="finalResults[1]?.name"></h3>
                                    
                                    <!-- Percentage -->
                                    <div class="text-4xl font-bold text-gray-600 mb-2" x-text="finalResults[1]?.agreement + '%'"></div>
                                    <div class="text-sm text-gray-500 mb-6">overeenkomst</div>
                                    
                                    <!-- Action Button -->
                                    <button @click="showPartyExplanation(finalResults[1])" 
                                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                                        Bekijk details
                                    </button>
                                </div>
                            </div>
                        </div>
                        
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
                                        <img :src="finalResults[0]?.logo" :alt="finalResults[0]?.name" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                    
                                    <!-- Party Name -->
                                    <h3 class="text-3xl font-bold text-gray-800 mb-3" x-text="finalResults[0]?.name"></h3>
                                    
                                    <!-- Percentage -->
                                    <div class="text-5xl font-bold text-yellow-600 mb-2" x-text="finalResults[0]?.agreement + '%'"></div>
                                    <div class="text-sm text-gray-600 mb-6">overeenkomst</div>
                                    
                                    <!-- Action Button -->
                                    <button @click="showPartyExplanation(finalResults[0])" 
                                            class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-yellow-500/25">
                                        Ontdek waarom
                                    </button>
                                </div>
                            </div>
                        </div>
                        
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
                                        <img :src="finalResults[2]?.logo" :alt="finalResults[2]?.name" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                    
                                    <!-- Party Name -->
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="finalResults[2]?.name"></h3>
                                    
                                    <!-- Percentage -->
                                    <div class="text-4xl font-bold text-orange-600 mb-2" x-text="finalResults[2]?.agreement + '%'"></div>
                                    <div class="text-sm text-gray-500 mb-6">overeenkomst</div>
                                    
                                    <!-- Action Button -->
                                    <button @click="showPartyExplanation(finalResults[2])" 
                                            class="px-6 py-3 bg-orange-100 hover:bg-orange-200 text-orange-800 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                                        Bekijk details
                                    </button>
                                </div>
                            </div>
                        </div>
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
                        <template x-for="(result, index) in finalResults" :key="index">
                            <div class="px-8 py-6 hover:bg-gray-50 transition-all duration-300 group cursor-pointer" @click="showPartyExplanation(result)">
                                <div class="flex items-center space-x-6">
                                    <!-- Rank -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg"
                                             :class="{
                                                'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white shadow-lg shadow-yellow-500/25': index === 0,
                                                'bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-lg shadow-gray-500/25': index === 1,
                                                'bg-gradient-to-br from-amber-600 to-orange-700 text-white shadow-lg shadow-orange-500/25': index === 2,
                                                'bg-gray-100 text-gray-600': index > 2
                                             }">
                                            <span x-text="index + 1"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Party Info -->
                                    <div class="flex items-center space-x-4 flex-1">
                                        <!-- Logo -->
                                        <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 p-2 shadow-sm group-hover:shadow-md transition-shadow">
                                            <img :src="result.logo" :alt="result.name" class="w-full h-full object-contain rounded-lg">
                                        </div>
                                        
                                        <!-- Name & Progress -->
                                        <div class="flex-1">
                                            <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition-colors" x-text="result.name"></h4>
                                            
                                            <!-- Progress Bar -->
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full transition-all duration-700 ease-out"
                                                         :class="{
                                                            'bg-gradient-to-r from-yellow-400 to-yellow-600': index === 0,
                                                            'bg-gradient-to-r from-gray-400 to-gray-600': index === 1,
                                                            'bg-gradient-to-r from-amber-600 to-orange-700': index === 2,
                                                            'bg-gradient-to-r from-indigo-500 to-purple-600': index > 2
                                                         }"
                                                         :style="'width: ' + result.agreement + '%'">
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-500 min-w-[4rem]" x-text="result.agreement + '%'"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Percentage & Arrow -->
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-gray-800" x-text="result.agreement + '%'"></div>
                                            <div class="text-sm text-gray-500">match</div>
                                        </div>
                                        
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Party Details Modal (Enhanced) -->
                <div x-show="showPartyDetails" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-400"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    
                    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden" @click.away="showPartyDetails = false">
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-100 sticky top-0 z-10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Party Logo -->
                                    <div class="w-16 h-16 rounded-2xl bg-white border border-gray-200 p-2 shadow-sm">
                                        <img :src="partyLogos[detailedParty?.name]" :alt="detailedParty?.name" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                    
                                    <!-- Party Info -->
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800" x-text="detailedParty?.name"></h3>
                                        <div class="flex items-center space-x-3 mt-1">
                                            <div class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full">
                                                <span class="text-sm font-semibold text-indigo-700" x-text="detailedParty?.agreement + '% overeenkomst'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Close Button -->
                                <button @click="showPartyDetails = false" 
                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors group">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Modal Content -->
                        <div class="p-8 overflow-y-auto max-h-[calc(90vh-120px)]">
                            <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Standpunten Vergelijking
                            </h4>
                            
                            <!-- Questions Comparison -->
                            <div class="space-y-6">
                                <template x-for="(question, index) in questions" :key="index">
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <!-- Question Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full text-sm font-bold" x-text="index + 1"></span>
                                                    <h5 class="text-lg font-semibold text-gray-800" x-text="question.title"></h5>
                                                </div>
                                                <p class="text-gray-600 text-sm" x-text="question.description"></p>
                                            </div>
                                            
                                            <!-- Match Status -->
                                            <div class="ml-4">
                                                <div class="px-3 py-1 rounded-full text-sm font-medium"
                                                     :class="{
                                                        'bg-green-100 text-green-800': answers[index] && answers[index] === question.positions[detailedParty?.name],
                                                        'bg-yellow-100 text-yellow-800': answers[index] && ((answers[index] === 'neutraal' && question.positions[detailedParty?.name] !== 'neutraal') || (answers[index] !== 'neutraal' && question.positions[detailedParty?.name] === 'neutraal')),
                                                        'bg-red-100 text-red-800': answers[index] && answers[index] !== question.positions[detailedParty?.name] && !(answers[index] === 'neutraal' || question.positions[detailedParty?.name] === 'neutraal'),
                                                        'bg-gray-100 text-gray-600': !answers[index]
                                                     }">
                                                    <template x-if="answers[index] && answers[index] === question.positions[detailedParty?.name]">
                                                        <span>✓ Match</span>
                                                    </template>
                                                    <template x-if="answers[index] && ((answers[index] === 'neutraal' && question.positions[detailedParty?.name] !== 'neutraal') || (answers[index] !== 'neutraal' && question.positions[detailedParty?.name] === 'neutraal'))">
                                                        <span>~ Gedeeltelijk</span>
                                                    </template>
                                                    <template x-if="answers[index] && answers[index] !== question.positions[detailedParty?.name] && !(answers[index] === 'neutraal' || question.positions[detailedParty?.name] === 'neutraal')">
                                                        <span>✗ Verschil</span>
                                                    </template>
                                                    <template x-if="!answers[index]">
                                                        <span>- Overgeslagen</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Positions Comparison -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Your Answer -->
                                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                                <h6 class="font-semibold text-gray-700 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Jouw antwoord
                                                </h6>
                                                <template x-if="answers[index]">
                                                    <div class="px-3 py-2 rounded-lg text-sm font-medium"
                                                         :class="{
                                                            'bg-emerald-100 text-emerald-800': answers[index] === 'eens',
                                                            'bg-blue-100 text-blue-800': answers[index] === 'neutraal',
                                                            'bg-red-100 text-red-800': answers[index] === 'oneens'
                                                         }"
                                                         x-text="answers[index]"></div>
                                                </template>
                                                <template x-if="!answers[index]">
                                                    <div class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Overgeslagen</div>
                                                </template>
                                            </div>
                                            
                                            <!-- Party Position -->
                                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                                <h6 class="font-semibold text-gray-700 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span x-text="detailedParty?.name"></span>
                                                </h6>
                                                <div class="px-3 py-2 rounded-lg text-sm font-medium"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-800': question.positions[detailedParty?.name] === 'eens',
                                                        'bg-blue-100 text-blue-800': question.positions[detailedParty?.name] === 'neutraal',
                                                        'bg-red-100 text-red-800': question.positions[detailedParty?.name] === 'oneens'
                                                     }"
                                                     x-text="question.positions[detailedParty?.name]"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Party Explanation -->
                                        <div class="mt-4 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                                            <h6 class="font-semibold text-indigo-800 mb-2">Toelichting:</h6>
                                            <p class="text-indigo-700 text-sm leading-relaxed" x-text="question.explanations[detailedParty?.name]"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button @click="resetQuiz()" 
                            class="group px-8 py-4 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-2xl font-semibold transition-all duration-300 hover:shadow-lg flex items-center space-x-3">
                        <svg class="w-5 h-5 group-hover:-rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Opnieuw doen</span>
                    </button>
                    
                    <button onclick="window.print()" 
                            class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-indigo-500/25 flex items-center space-x-3">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Resultaten afdrukken</span>
                    </button>
                    
                    <button @click="shareResults()" 
                            class="group px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-green-500/25 flex items-center space-x-3">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                        <span>Delen</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function stemwijzer() {
    return {
        screen: 'start',
        currentStep: 0,
        totalSteps: <?= $totalQuestions ?: 25 ?>,
        showExplanation: false,
        showPartyPositions: false,
        selectedParty: null,
        answers: {},
        eensParties: [],
        neutraalParties: [],
        oneensParties: [],
        
        results: {},
        finalResults: [],
        showPartyDetails: false,
        detailedParty: null,
        showingQuestion: null,
        
        // Data wordt nu uit de database geladen
        questions: <?= json_encode($stemwijzerData['questions'] ?? []) ?>,
        partyLogos: <?= json_encode($stemwijzerData['partyLogos'] ?? []) ?>,
        dataLoaded: <?= !empty($stemwijzerData['questions']) ? 'true' : 'false' ?>,
        
        // Loading state
        isLoading: false,
        
        init() {
            // Debug informatie loggen
            console.log('=== STEMWIJZER DEBUG INFO ===');
            console.log('Data loaded from PHP:', this.dataLoaded);
            console.log('Total questions from PHP:', this.totalSteps);
            console.log('Questions array length:', this.questions.length);
            console.log('Questions array:', this.questions);
            console.log('Party logos:', this.partyLogos);
            console.log('===============================');
            
            // Als er geen data uit de database komt, probeer dan de API
            if (!this.dataLoaded || this.questions.length === 0) {
                console.warn('Geen data uit database - probeer API');
                this.loadDataFromAPI();
            } else {
                // Update totalSteps gebaseerd op geladen data
                this.totalSteps = this.questions.length;
                console.log('Stemwijzer data geladen uit database:', this.questions.length, 'vragen');
                
                // Valideer dat alle vragen de juiste properties hebben
                this.validateQuestionsData();
                
                // Voeg een kleine vertraging toe voor animatie-effecten
                setTimeout(() => {
                    // Trigger any entrance animations here if needed
                }, 100);
            }
        },
        
        validateQuestionsData() {
            console.log('=== VALIDATING QUESTIONS DATA ===');
            const requiredProps = ['id', 'title', 'description', 'context', 'left_view', 'right_view', 'positions', 'explanations'];
            
            for (let i = 0; i < this.questions.length; i++) {
                const question = this.questions[i];
                
                for (const prop of requiredProps) {
                    if (!question.hasOwnProperty(prop) || question[prop] === undefined || question[prop] === null) {
                        console.warn(`Question ${i} missing property: ${prop}`);
                        
                        // Voeg default waarden toe voor ontbrekende properties
                        if (prop === 'positions') {
                            question[prop] = {};
                        } else if (prop === 'explanations') {
                            question[prop] = {};
                        } else {
                            question[prop] = `Ontbrekende data voor ${prop}`;
                        }
                    }
                }
                
                // Controleer of positions en explanations objecten zijn
                if (typeof question.positions !== 'object') {
                    console.warn(`Question ${i} positions is not an object:`, question.positions);
                    question.positions = {};
                }
                
                if (typeof question.explanations !== 'object') {
                    console.warn(`Question ${i} explanations is not an object:`, question.explanations);
                    question.explanations = {};
                }
            }
            
            console.log('=== VALIDATION COMPLETE ===');
        },
        
        async loadDataFromAPI() {
            console.log('=== API LOAD ATTEMPT ===');
            this.isLoading = true;
            document.getElementById('loading-indicator').style.display = 'flex';
            
            try {
                console.log('Fetching from: /api/stemwijzer.php?action=data');
                const response = await fetch('/api/stemwijzer.php?action=data');
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                const result = await response.json();
                console.log('API Response:', result);
                
                if (result.success && result.data) {
                    this.questions = result.data.questions;
                    this.partyLogos = result.data.partyLogos;
                    this.totalSteps = result.data.questions.length;
                    this.dataLoaded = true;
                    
                    console.log('Stemwijzer data succesvol geladen uit API:', result.data);
                } else {
                    console.error('API response not successful:', result);
                    throw new Error('API response was not successful');
                }
            } catch (error) {
                console.error('Fout bij laden van stemwijzer data:', error);
                
                // Fallback naar hardcoded data (alleen de eerste 2 vragen als voorbeeld)
                this.questions = this.getFallbackQuestions();
                this.partyLogos = this.getFallbackPartyLogos();
                this.totalSteps = this.questions.length;
                this.dataLoaded = true;
                
                // Show user-friendly error message
                this.showNotification('Er is een probleem met het laden van de vragen. Er wordt gebruik gemaakt van voorbeelddata.', 'warning');
            } finally {
                this.isLoading = false;
                document.getElementById('loading-indicator').style.display = 'none';
                console.log('=== API LOAD FINISHED ===');
            }
        },
        
        getFallbackQuestions() {
            return [
                {
                    title: "Asielbeleid",
                    description: "Nederland moet een strenger asielbeleid voeren met een asielstop en lagere immigratiecijfers.",
                    context: "Bij deze stelling gaat het erom hoe Nederland omgaat met mensen die asiel aanvragen.",
                    left_view: "Vinden dat Nederland humaan moet blijven en vluchtelingen moet opvangen.",
                    right_view: "Willen de instroom van asielzoekers beperken omdat zij vinden dat dit de druk op de samenleving verlaagt.",
                    positions: {
                        'PVV': 'eens',
                        'VVD': 'eens',
                        'NSC': 'eens',
                        'BBB': 'eens',
                        'GL-PvdA': 'oneens',
                        'D66': 'oneens',
                        'SP': 'neutraal',
                        'PvdD': 'oneens',
                        'CDA': 'neutraal',
                        'JA21': 'eens',
                        'SGP': 'eens',
                        'FvD': 'eens',
                        'DENK': 'oneens',
                        'Volt': 'oneens'
                    },
                    explanations: {
                        'PVV': "PVV steunt een strenger asielbeleid met een volledige asielstop.",
                        'VVD': "VVD pleit voor een strengere selectie van asielaanvragen.",
                        'NSC': "NSC benadrukt een doordacht asielbeleid.",
                        'BBB': "BBB ondersteunt een streng asielbeleid.",
                        'GL-PvdA': "GL-PvdA vindt dat humanitaire principes centraal moeten staan.",
                        'D66': "D66 wil een humaan maar gestructureerd asielbeleid.",
                        'SP': "SP vindt dat opvang en integratie belangrijk zijn.",
                        'PvdD': "PvdD wil een asielbeleid dat mensenrechten respecteert.",
                        'CDA': "CDA pleit voor een onderscheidend beleid.",
                        'JA21': "JA21 ondersteunt een restrictief asielbeleid.",
                        'SGP': "SGP wil een zeer restrictief asielbeleid.",
                        'FvD': "FvD wil asielaanvragen sterk beperken.",
                        'DENK': "DENK kiest voor een humaan asielbeleid.",
                        'Volt': "Volt staat voor een gemeenschappelijk Europees asielbeleid."
                    }
                }
            ];
        },
        
        getFallbackPartyLogos() {
            return {
                'PVV': 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
                'VVD': 'https://logo.clearbit.com/vvd.nl',
                'NSC': 'https://i.ibb.co/YT2fJZb4/nsc.png',
                'BBB': 'https://i.ibb.co/qMjw7jDV/bbb.png',
                'GL-PvdA': 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
                'D66': 'https://logo.clearbit.com/d66.nl',
                'SP': 'https://logo.clearbit.com/sp.nl',
                'PvdD': 'https://logo.clearbit.com/partijvoordedieren.nl',
                'CDA': 'https://logo.clearbit.com/cda.nl',
                'JA21': 'https://logo.clearbit.com/ja21.nl',
                'SGP': 'https://logo.clearbit.com/sgp.nl',
                'FvD': 'https://logo.clearbit.com/fvd.nl',
                'DENK': 'https://logo.clearbit.com/bewegingdenk.nl',
                'Volt': 'https://logo.clearbit.com/voltnederland.org'
            };
        },
        
        currentQuestion() {
            return this.questions[this.currentStep];
        },
        
        startQuiz() {
            if (!this.dataLoaded) {
                this.showNotification('De vragen worden nog geladen. Probeer het over een moment opnieuw.', 'warning');
                return;
            }
            
            this.screen = 'questions';
            this.currentStep = 0;
            this.showExplanation = false;
            this.updatePartyGroups();
        },
        
        updatePartyGroups() {
            if (!this.questions[this.currentStep]) {
                console.warn('updatePartyGroups: No question at step', this.currentStep);
                return;
            }
            
            const question = this.questions[this.currentStep];
            if (!question.positions || typeof question.positions !== 'object') {
                console.warn('updatePartyGroups: No positions for question', this.currentStep);
                this.eensParties = [];
                this.neutraalParties = [];
                this.oneensParties = [];
                return;
            }
            
            const positions = question.positions;
            this.eensParties = [];
            this.neutraalParties = [];
            this.oneensParties = [];
            
            Object.keys(positions).forEach(party => {
                const position = positions[party];
                
                if (position === 'eens') {
                    this.eensParties.push(party);
                } else if (position === 'neutraal') {
                    this.neutraalParties.push(party);
                } else if (position === 'oneens') {
                    this.oneensParties.push(party);
                }
            });
            
            console.log('Party groups updated:', {
                eens: this.eensParties.length,
                neutraal: this.neutraalParties.length,
                oneens: this.oneensParties.length
            });
        },
        
        countAnswerType(type) {
            return Object.values(this.answers).filter(answer => answer === type).length;
        },
        
        answerQuestion(answer) {
            this.answers[this.currentStep] = answer;
            
            // Add subtle haptic feedback if available
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
            
            // Progress to next question or show results
            if (this.currentStep < this.totalSteps - 1) {
                setTimeout(() => {
                    this.currentStep++;
                    this.showExplanation = false;
                    this.selectedParty = null;
                    this.updatePartyGroups();
                }, 300); // Small delay for smooth transition
            } else {
                setTimeout(() => {
                    this.calculateResults();
                    this.screen = 'results';
                    this.saveResultsToDatabase();
                }, 500);
            }
        },
        
        skipQuestion() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            } else {
                this.calculateResults();
                this.screen = 'results';
                this.saveResultsToDatabase();
            }
        },
        
        previousQuestion() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            }
        },
        
        calculateResults() {
            if (!this.questions.length) return;
            
            const parties = Object.keys(this.questions[0].positions);
            const results = {};
            
            parties.forEach(party => {
                results[party] = { score: 0, total: 0, agreement: 0 };
            });
            
            Object.keys(this.answers).forEach(questionIndex => {
                const question = this.questions[questionIndex];
                const userAnswer = this.answers[questionIndex];
                
                parties.forEach(party => {
                    const partyAnswer = question.positions[party];
                    
                    if (userAnswer === partyAnswer) {
                        results[party].score += 2;
                    } else if (
                        (userAnswer === 'neutraal' && (partyAnswer === 'eens' || partyAnswer === 'oneens')) ||
                        ((userAnswer === 'eens' || userAnswer === 'oneens') && partyAnswer === 'neutraal')
                    ) {
                        results[party].score += 1;
                    }
                    
                    results[party].total += 2;
                });
            });
            
            // Calculate percentages
            parties.forEach(party => {
                results[party].agreement = Math.round((results[party].score / results[party].total) * 100);
            });
            
            this.results = results;
            
            // Create sorted array for display
            this.finalResults = parties
                .map(party => ({
                    name: party,
                    agreement: results[party].agreement,
                    logo: this.partyLogos[party]
                }))
                .sort((a, b) => b.agreement - a.agreement);
        },
        
        async saveResultsToDatabase() {
            try {
                const sessionId = this.generateSessionId();
                const payload = {
                    sessionId: sessionId,
                    answers: this.answers,
                    results: this.results
                };
                
                const response = await fetch('/api/stemwijzer.php?action=save-results', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();
                if (result.success) {
                    console.log('Resultaten succesvol opgeslagen in database');
                } else {
                    console.warn('Kon resultaten niet opslaan in database:', result.error);
                }
            } catch (error) {
                console.error('Fout bij opslaan van resultaten:', error);
            }
        },
        
        generateSessionId() {
            return 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
        },
        
        showPartyExplanation(party) {
            this.detailedParty = party;
            this.showPartyDetails = true;
            
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        },
        
        closePartyDetails() {
            this.showPartyDetails = false;
            this.detailedParty = null;
            
            // Restore body scrolling
            document.body.style.overflow = '';
        },
        
        resetQuiz() {
            // Confirmation dialog
            if (confirm('Ben je zeker dat je opnieuw wilt beginnen? Je huidige voortgang gaat verloren.')) {
                this.screen = 'start';
                this.currentStep = 0;
                this.answers = {};
                this.results = {};
                this.finalResults = [];
                this.showExplanation = false;
                this.showPartyPositions = false;
                this.selectedParty = null;
                this.showPartyDetails = false;
                this.detailedParty = null;
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        shareResults() {
            const topThree = this.finalResults.slice(0, 3);
            const text = `Mijn Stemwijzer 2025 resultaten:\n\n` +
                `🥇 ${topThree[0]?.name}: ${topThree[0]?.agreement}%\n` +
                `🥈 ${topThree[1]?.name}: ${topThree[1]?.agreement}%\n` +
                `🥉 ${topThree[2]?.name}: ${topThree[2]?.agreement}%\n\n` +
                `Doe ook de test op: ${window.location.origin}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Mijn Stemwijzer 2025 Resultaten',
                    text: text,
                    url: window.location.href
                }).catch(err => {
                    console.log('Error sharing:', err);
                    this.fallbackShare(text);
                });
            } else {
                this.fallbackShare(text);
            }
        },
        
        fallbackShare(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showNotification('Resultaten gekopieerd naar klembord!', 'success');
                }).catch(() => {
                    this.showNotification('Kon resultaten niet kopiëren', 'error');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                
                try {
                    document.execCommand('copy');
                    this.showNotification('Resultaten gekopieerd naar klembord!', 'success');
                } catch (err) {
                    this.showNotification('Kon resultaten niet kopiëren', 'error');
                }
                
                document.body.removeChild(textArea);
            }
        },
        
        showNotification(message, type = 'info') {
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
        },
        
        // Keyboard navigation support
        handleKeydown(event) {
            if (this.screen === 'questions') {
                switch (event.key) {
                    case '1':
                        this.answerQuestion('eens');
                        break;
                    case '2':
                        this.answerQuestion('neutraal');
                        break;
                    case '3':
                        this.answerQuestion('oneens');
                        break;
                    case 'ArrowLeft':
                        event.preventDefault();
                        this.previousQuestion();
                        break;
                    case 'ArrowRight':
                    case ' ':
                        event.preventDefault();
                        this.skipQuestion();
                        break;
                    case 'Escape':
                        if (this.showExplanation) {
                            this.showExplanation = false;
                        }
                        break;
                }
            }
            
            if (this.showPartyDetails && event.key === 'Escape') {
                this.closePartyDetails();
            }
        }
    }
}

// Add keyboard event listener
document.addEventListener('keydown', (event) => {
    const stemwijzerInstance = window.stemwijzerInstance;
    if (stemwijzerInstance && stemwijzerInstance.handleKeydown) {
        stemwijzerInstance.handleKeydown(event);
    }
});

// Store instance globally for keyboard navigation
document.addEventListener('alpine:init', () => {
    window.stemwijzerInstance = stemwijzer();
});

// Add CSS for print styles
const printStyles = `
@media print {
    .no-print, nav, header, footer {
        display: none !important;
    }
    
    body {
        font-size: 12px;
        line-height: 1.4;
    }
    
    .print-break {
        page-break-before: always;
    }
    
    .shadow-xl, .shadow-2xl, .shadow-lg {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}
`;

// Add print styles to document
const styleSheet = document.createElement('style');
styleSheet.textContent = printStyles;
document.head.appendChild(styleSheet);
</script>

<?php require_once 'views/templates/footer.php'; ?> 