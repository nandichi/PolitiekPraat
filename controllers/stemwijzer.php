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
        echo "<!-- DEBUG: Schema type: " . $stemwijzerController->getSchemaType() . " -->\n";
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

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen mb-20">
    <!-- Hero Section - Elegant and professional design -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-blue-600 py-20 overflow-hidden">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary to-blue-400"></div>
        
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Abstract wave pattern -->
            <svg class="absolute w-full h-56 -bottom-10 left-0 text-white/5" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path fill="currentColor" fill-opacity="1" d="M0,128L40,138.7C80,149,160,171,240,170.7C320,171,400,149,480,149.3C560,149,640,171,720,192C800,213,880,235,960,229.3C1040,224,1120,192,1200,165.3C1280,139,1360,117,1400,106.7L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
            </svg>
            
            <!-- Decorative circles -->
            <div class="absolute top-20 left-10 w-40 h-40 rounded-full bg-secondary/10 filter blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-blue-500/10 filter blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-20 h-20 rounded-full bg-secondary/20 filter blur-xl"></div>
            
            <!-- Dot pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-30"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Small decorative element above title -->
                <div class="inline-block mb-3">
                    <div class="flex items-center justify-center space-x-1">
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        <span class="block w-3 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    </div>
                </div>
                
                <!-- Title with gradient text -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-100 via-white to-secondary-light">
                        Stemwijzer 2025
                    </span>
                </h1>
                
                <!-- Subtitle with lighter weight -->
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed font-light">
                    Ontdek welke partij het beste bij jouw standpunten past met onze uitgebreide analyse
                    <?php if ($totalQuestions > 0): ?>
                        • <?= $totalQuestions ?> stellingen
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </section>
    
    <div class="container mx-auto px-4 max-w-7xl -mt-6 relative z-10">
        <!-- Loading indicator voor als data nog geladen wordt -->
        <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-xl p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                <p class="text-gray-700">Vragen laden...</p>
            </div>
        </div>

        <!-- Stemwijzer App -->
        <div class="max-w-4xl mx-auto" x-data="stemwijzer()">
            <!-- Progress Bar -->
            <div class="mb-8 bg-white rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-2xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-secondary/5 to-primary/5 rounded-full blur-2xl -z-10 transform -translate-x-1/3 translate-y-1/3"></div>
                
                <!-- Header met vraagnummer en tijd -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-5">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary/10 to-primary/5 
                                       flex items-center justify-center">
                                <span class="text-xl font-semibold text-primary" x-text="currentStep + 1"></span>
                                <div class="absolute inset-0 rounded-full border-2 border-primary/20 
                                           animate-pulse-subtle"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-medium text-gray-900">Vraag</span>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-500">
                                    <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span> stellingen
                                </span>
                                <span class="ml-2 px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded-full">
                                    <span x-text="Math.round((currentStep / totalSteps) * 100)"></span>% voltooid
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                    </div>
                </div>
                
                <!-- Progress track -->
                <div class="relative mb-6">
                    <!-- Background track -->
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden backdrop-blur-sm shadow-inner">
                        <!-- Progress bar -->
                        <div class="h-full bg-gradient-to-r from-primary via-primary/90 to-secondary
                                    transition-all duration-500 ease-out-cubic relative"
                             :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 flex">
                                <div class="w-1/2 bg-gradient-to-r from-transparent to-white/40"></div>
                                <div class="w-1/2 bg-gradient-to-l from-transparent to-white/40"></div>
                            </div>
                            
                            <!-- Progress indicator -->
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-5 h-5 
                                       bg-white rounded-full shadow-lg shadow-primary/30 border-2 border-primary/30
                                       animate-pulse-slow z-10"></div>
                        </div>
                    </div>

                    <!-- Step markers with segments -->
                    <div class="absolute top-1/2 -translate-y-1/2 w-full flex justify-between px-[1px]">
                        <template x-for="(_, index) in Array.from({length: 5})" :key="index">
                            <div class="relative group">
                                <div class="w-1 h-3.5 rounded-sm transition-all duration-300"
                                     :class="currentStep >= Math.floor(index * (totalSteps / 4)) ? 'bg-primary/70' : 'bg-gray-200'">
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full mb-2 -translate-x-1/2 left-1/2
                                           opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                    <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap shadow-lg">
                                        <span x-text="Math.round((index / 4) * 100)"></span>% voltooid
                                    </div>
                                    <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 mx-auto"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Labels with icons -->
                <div class="flex justify-between px-1 text-xs font-medium">
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Start</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Halverwege</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Einde</span>
                    </div>
                </div>
            </div>

            <!-- Voeg deze custom animaties toe aan je bestaande style sectie -->
            <style>
            @keyframes pulse-subtle {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            @keyframes pulse-slow {
                0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
                50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes ease-out-cubic {
                0% { transition-timing-function: cubic-bezier(0.33, 1, 0.68, 1); }
                100% { transition-timing-function: cubic-bezier(0.33, 1, 0.68, 1); }
            }

            .shadow-glow {
                box-shadow: 0 0 12px 3px rgba(255, 255, 255, 0.8);
            }

            .animate-pulse-subtle {
                animation: pulse-subtle 3s ease-in-out infinite;
            }

            .animate-pulse-slow {
                animation: pulse-slow 2.5s ease-in-out infinite;
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            </style>

            <!-- Start Screen -->
            <div x-show="screen === 'start'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="bg-white rounded-2xl shadow-xl p-10 relative overflow-hidden mb-20">
                <!-- Decoratieve achtergrond elementen -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl -z-10 transform translate-x-1/3 -translate-y-1/3 animate-float"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-secondary/10 to-primary/10 rounded-full blur-3xl -z-10 transform -translate-x-1/3 translate-y-1/3 animate-float" style="animation-delay: 2s;"></div>

                <!-- Header met icon -->
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-5 mb-8">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-lg shadow-primary/20 mx-auto md:mx-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Welkom bij de Stemwijzer</h2>
                        <div class="flex items-center justify-center md:justify-start mt-2">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-medium text-gray-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                ±10 minuten
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-6">
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Deze stemwijzer helpt je om te ontdekken welke partij het beste bij jouw politieke voorkeuren past. 
                        Je krijgt een aantal stellingen te zien waarop je kunt aangeven in hoeverre je het ermee eens bent.
                    </p>

                    <!-- Features/voordelen -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 py-6">
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Gebaseerd op actuele partijstandpunten</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Volledig anoniem en privacy-vriendelijk</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Gedetailleerde resultaten</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Slechts 25 belangrijke stellingen</span>
                        </div>
                    </div>
                </div>

                <!-- Start button -->
                <button @click="startQuiz()" 
                        class="w-full mt-10 bg-gradient-to-r from-primary to-secondary text-white font-semibold 
                               py-4 px-6 rounded-xl shadow-lg shadow-primary/20
                               hover:shadow-xl hover:shadow-primary/30
                               transform transition-all duration-300 hover:scale-[1.02]
                               focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2">
                    <div class="flex items-center justify-center space-x-3">
                        <span class="text-lg">Start de Stemwijzer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </button>
            </div>

            <!-- Questions Screen -->
            <div x-show="screen === 'questions'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="max-w-[1400px] mx-auto">

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Question & Answers -->
                    <div class="lg:col-span-7 bg-white rounded-2xl shadow-xl p-8">
                        <!-- Question Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-5">
                                <span class="px-4 py-1.5 bg-gray-100 rounded-full text-xs font-medium text-gray-600 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Vraag <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span>
                                </span>
                                <div class="flex items-center space-x-3 text-xs text-gray-500">
                                    <button @click="previousQuestion()" 
                                            x-show="currentStep > 0"
                                            class="flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="hidden sm:inline font-medium">Vorige</span>
                                    </button>
                                    <button @click="skipQuestion()"
                                            class="flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                        <span class="hidden sm:inline font-medium">Overslaan</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4" x-text="questions[currentStep].title"></h2>
                            <p class="text-lg text-gray-600 leading-relaxed" x-text="questions[currentStep].description"></p>
                            
                            <!-- Nieuwe uitleg knop -->
                            <button @click="showExplanation = !showExplanation"
                                    class="mt-5 px-4 py-2 bg-gray-50 text-sm text-gray-700 hover:bg-gray-100 rounded-full flex items-center space-x-2 transition-colors border border-gray-200">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Uitleg over deze stelling</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="showExplanation ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Uitleg panel -->
                            <div x-show="showExplanation" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-6 rounded-xl overflow-hidden border border-gray-100">
                                <div class="bg-blue-50 px-5 py-3 border-b border-blue-100">
                                    <h3 class="font-semibold text-blue-900">Uitleg bij deze stelling</h3>
                                </div>
                                <div x-text="questions[currentStep].context" 
                                     class="text-gray-700 p-5 bg-white leading-relaxed">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-t border-gray-100">
                                    <div class="bg-blue-50 p-5 border-r border-blue-100">
                                        <h4 class="font-medium text-blue-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                            Linkse partijen vinden:
                                        </h4>
                                        <p x-text="questions[currentStep].leftView" 
                                           class="text-blue-800 leading-relaxed"></p>
                                    </div>
                                    <div class="bg-red-50 p-5">
                                        <h4 class="font-medium text-red-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                            </svg>
                                            Rechtse partijen vinden:
                                        </h4>
                                        <p x-text="questions[currentStep].rightView" 
                                           class="text-red-800 leading-relaxed"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Options -->
                        <div class="grid grid-cols-1 gap-5 mt-8">
                            <!-- Eens button -->
                            <button @click="answerQuestion('eens')"
                                    class="relative bg-gradient-to-r from-emerald-50 to-white border-2 border-emerald-500 rounded-xl p-6 
                                           transition-all duration-300 hover:shadow-lg hover:shadow-emerald-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-emerald-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-emerald-700">Eens</h3>
                                        <p class="text-sm text-emerald-600">Ik ben het eens met deze stelling</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Neutraal button -->
                            <button @click="answerQuestion('neutraal')"
                                    class="relative bg-gradient-to-r from-blue-50 to-white border-2 border-blue-400 rounded-xl p-6
                                          transition-all duration-300 hover:shadow-lg hover:shadow-blue-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-blue-400 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-blue-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-blue-700">Neutraal</h3>
                                        <p class="text-sm text-blue-600">Ik sta hier neutraal tegenover</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Oneens button -->
                            <button @click="answerQuestion('oneens')"
                                    class="relative bg-gradient-to-r from-red-50 to-white border-2 border-red-500 rounded-xl p-6
                                          transition-all duration-300 hover:shadow-lg hover:shadow-red-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-red-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-red-700">Oneens</h3>
                                        <p class="text-sm text-red-600">Ik ben het oneens met deze stelling</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Skip button (smaller) -->
                            <button @click="skipQuestion()"
                                    class="mt-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 transition-colors rounded-xl mx-auto flex items-center space-x-2 text-gray-600">
                                <span class="text-sm font-medium">Deze vraag overslaan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Party Information -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Informatie over de vraag -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 relative overflow-hidden">
                            <!-- Decorative elements -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Partijstandpunten
                            </h3>
                            
                            <p class="text-gray-600 mb-6">
                                Bekijk hoe de belangrijkste politieke partijen staan tegenover deze stelling:
                            </p>
                            
                            <div class="space-y-4">
                                <!-- Eens groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-emerald-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Eens met deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2" x-init="updatePartyGroups()">
                                        <template x-for="(partido, index) in $data.eensParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-emerald-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Neutraal groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                        </svg>
                                        Neutraal tegenover deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(partido, index) in $data.neutraalParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-blue-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Oneens groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Oneens met deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(partido, index) in $data.oneensParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-red-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Partij uitleg -->
                        <div x-show="selectedParty !== null" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="bg-white rounded-2xl shadow-xl p-8">
                            <div class="flex items-start space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-xl bg-white p-1 border border-gray-200 flex-shrink-0">
                                    <img :src="$data.partyLogos[selectedParty]" :alt="selectedParty" class="w-full h-full object-contain rounded-lg">
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900" x-text="selectedParty"></h3>
                                    <div class="mt-1 flex items-center">
                                        <div class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                                             :class="{
                                                'bg-emerald-100 text-emerald-800': questions[currentStep].positions[selectedParty] === 'eens',
                                                'bg-blue-100 text-blue-800': questions[currentStep].positions[selectedParty] === 'neutraal',
                                                'bg-red-100 text-red-800': questions[currentStep].positions[selectedParty] === 'oneens'
                                             }">
                                            <span x-text="questions[currentStep].positions[selectedParty]"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100" 
                               x-text="questions[currentStep].explanations[selectedParty]"></p>
                               
                            <button @click="selectedParty = null" 
                                    class="mt-4 text-sm text-gray-500 hover:text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Sluiten
                            </button>
                        </div>
                        
                        <!-- Voortgang box -->
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Jouw voortgang
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-2 text-sm">
                                        <span class="font-medium text-gray-700">Beantwoorde vragen</span>
                                        <span class="text-primary font-semibold">
                                            <span x-text="Object.keys(answers).length"></span>/<span x-text="totalSteps"></span>
                                        </span>
                                    </div>
                                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500 ease-out"
                                             :style="'width: ' + (Object.keys(answers).length / totalSteps * 100) + '%'"></div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Eens</span>
                                        <span class="font-medium text-emerald-600" x-text="countAnswerType('eens')"></span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Neutraal</span>
                                        <span class="font-medium text-blue-600" x-text="countAnswerType('neutraal')"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Oneens</span>
                                        <span class="font-medium text-red-600" x-text="countAnswerType('oneens')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Screen -->
            <div x-show="screen === 'results'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="max-w-5xl mx-auto">
                
                <!-- Hero Results -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
                    <!-- Decoratieve achtergrond elementen -->
                    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-secondary/10 to-primary/10 rounded-full blur-3xl -z-10 transform -translate-x-1/3 translate-y-1/3"></div>
                    
                    <div class="text-center mb-8">
                        <div class="inline-block p-3 bg-primary/10 rounded-full mb-4">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Jouw resultaten</h2>
                        <p class="text-gray-600 max-w-3xl mx-auto">
                            Gebaseerd op je antwoorden hebben we berekend welke partijen het beste bij jouw standpunten passen.
                            Hoe hoger het percentage, hoe meer jullie standpunten overeenkomen.
                        </p>
                    </div>
                    
                    <!-- Top 3 Results -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-20">
                        <!-- #2 Result -->
                        <div class="relative order-2 md:order-1 mt-6 md:mt-10">
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-xl font-bold text-gray-700">2</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 pt-8 text-center h-full relative overflow-hidden">
                                <div class="mb-3 w-20 h-20 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[1]?.logo" :alt="finalResults[1]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="finalResults[1]?.name"></h3>
                                <div class="text-3xl font-bold text-primary" x-text="finalResults[1]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-500 mb-4">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[1])" 
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition-colors">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                        
                        <!-- #1 Result (Larger) -->
                        <div class="relative order-1 md:order-2 transform md:-translate-y-4">
                            <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="bg-gradient-to-b from-primary/5 to-primary/10 rounded-xl border border-primary/20 p-8 text-center h-full relative overflow-hidden shadow-xl">
                                <div class="mb-4 w-24 h-24 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[0]?.logo" :alt="finalResults[0]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-1" x-text="finalResults[0]?.name"></h3>
                                <div class="text-4xl font-bold text-primary mb-1" x-text="finalResults[0]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-600 mb-5">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[0])" 
                                        class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-medium transition-colors shadow-md shadow-primary/20">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                        
                        <!-- #3 Result -->
                        <div class="relative order-3 mt-6 md:mt-10">
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-xl font-bold text-gray-700">3</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 pt-8 text-center h-full relative overflow-hidden">
                                <div class="mb-3 w-20 h-20 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[2]?.logo" :alt="finalResults[2]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="finalResults[2]?.name"></h3>
                                <div class="text-3xl font-bold text-primary" x-text="finalResults[2]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-500 mb-4">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[2])" 
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition-colors">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- All Results Table -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-md">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Alle resultaten</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <template x-for="(result, index) in finalResults.slice(0, 10)" :key="index">
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-white p-1 border border-gray-200 shadow-sm">
                                                <img :src="result.logo" :alt="result.name" class="w-full h-full object-contain rounded-md">
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900" x-text="result.name"></h4>
                                                <div class="flex items-center mt-1">
                                                    <div class="text-xs text-gray-500 mr-2" x-text="'#' + (index + 1)"></div>
                                                    <div class="h-1.5 w-24 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-primary rounded-full" :style="'width: ' + result.agreement + '%'"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-xl font-bold text-gray-900" x-text="result.agreement + '%'"></div>
                                            <button @click="showPartyExplanation(result)" 
                                                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <!-- Party Details Modal -->
                <div x-show="showPartyDetails" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                    
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.away="showPartyDetails = false">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100 sticky top-0 bg-white z-10">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-xl bg-white p-1 border border-gray-200 shadow-sm">
                                        <img :src="$data.partyLogos[detailedParty?.name]" :alt="detailedParty?.name" class="w-full h-full object-contain rounded-md">
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900" x-text="detailedParty?.name"></h3>
                                        <div class="flex items-center mt-1">
                                            <div class="px-3 py-1 bg-primary/10 rounded-full text-sm font-medium text-primary">
                                                <span x-text="detailedParty?.agreement + '% overeenkomst'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="showPartyDetails = false" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Standpunten per stelling</h4>
                            
                            <div class="space-y-6">
                                <template x-for="(question, index) in questions" :key="index">
                                    <div class="p-4 border border-gray-100 rounded-xl hover:border-gray-200 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-block w-6 h-6 text-xs flex items-center justify-center bg-gray-100 text-gray-700 rounded-full font-medium" x-text="index + 1"></span>
                                                    <h5 class="font-semibold text-gray-900" x-text="question.title"></h5>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3" x-text="question.description"></p>
                                            </div>
                                            
                                            <div class="px-3 py-1 rounded-full text-sm font-medium"
                                                 :class="{
                                                    'bg-emerald-100 text-emerald-800': question.positions[detailedParty?.name] === 'eens',
                                                    'bg-blue-100 text-blue-800': question.positions[detailedParty?.name] === 'neutraal',
                                                    'bg-red-100 text-red-800': question.positions[detailedParty?.name] === 'oneens'
                                                 }">
                                                <span x-text="question.positions[detailedParty?.name]"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-700"
                                             x-text="question.explanations[detailedParty?.name]"></div>
                                                 
                                        <div class="mt-2 text-xs text-gray-500 flex items-center">
                                            <template x-if="answers[index]">
                                                <div class="flex items-center space-x-1">
                                                    <span>Jouw antwoord:</span>
                                                    <span class="font-medium px-2 py-0.5 rounded-full"
                                                          :class="{
                                                            'bg-emerald-100 text-emerald-800': answers[index] === 'eens',
                                                            'bg-blue-100 text-blue-800': answers[index] === 'neutraal',
                                                            'bg-red-100 text-red-800': answers[index] === 'oneens'
                                                          }"
                                                          x-text="answers[index]"></span>
                                                </div>
                                            </template>
                                            <template x-if="!answers[index]">
                                                <span>Je hebt deze vraag overgeslagen</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button @click="resetQuiz()" 
                            class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Opnieuw beginnen</span>
                    </button>
                    
                    <button onclick="window.print()" 
                            class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Resultaten afdrukken</span>
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
                
                alert('Er is een probleem met het laden van de vragen. Er wordt gebruik gemaakt van voorbeelddata.');
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
                alert('De vragen worden nog geladen. Probeer het over een moment opnieuw.');
                return;
            }
            
            this.screen = 'questions';
            this.currentStep = 0;
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
            
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            } else {
                this.calculateResults();
                this.screen = 'results';
                
                // Sla resultaten op in de database
                this.saveResultsToDatabase();
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
                
                // Sla resultaten op in de database
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
        },
        
        resetQuiz() {
            this.screen = 'start';
            this.currentStep = 0;
            this.answers = {};
            this.results = {};
            this.finalResults = [];
            this.showExplanation = false;
            this.selectedParty = null;
        },
        
        shareResults() {
            const text = `Mijn Stemwijzer resultaten:\n${
                this.finalResults.slice(0, 3)
                    .map(r => `${r.name}: ${r.agreement}%`)
                    .join('\n')
            }`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Mijn Stemwijzer Resultaten',
                    text: text,
                    url: window.location.href
                });
            } else {
                // Fallback: kopieer naar klembord
                navigator.clipboard.writeText(text)
                    .then(() => alert('Resultaten gekopieerd naar klembord!'));
            }
        },
        
        saveResults() {
            const results = {
                timestamp: new Date().toISOString(),
                matches: this.finalResults,
                answers: this.answers,
                results: this.results
            };

            // Sla ook op in localStorage voor offline gebruik
            const savedResults = JSON.parse(localStorage.getItem('stemwijzerResults') || '[]');
            savedResults.push(results);
            localStorage.setItem('stemwijzerResults', JSON.stringify(savedResults));

            alert('Je resultaten zijn opgeslagen! Je kunt ze later terugvinden.');
        }
    }
}
</script>

<?php require_once 'views/templates/footer.php'; ?> 