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

// Structured Data voor SEO
$structuredData = [
    "@context" => "https://schema.org",
    "@type" => "WebApplication",
    "name" => "Stemwijzer 2025 Nederland",
    "description" => "Gratis online stemtest voor Nederlandse verkiezingen 2025. Ontdek welke politieke partij het beste bij jouw standpunten past.",
    "url" => URLROOT . "/stemwijzer",
    "applicationCategory" => "EducationalApplication",
    "operatingSystem" => "Web",
    "offers" => [
        "@type" => "Offer",
        "price" => "0",
        "priceCurrency" => "EUR"
    ],
    "provider" => [
        "@type" => "Organization",
        "name" => "PolitiekPraat",
        "url" => URLROOT
    ],
    "potentialAction" => [
        "@type" => "UseAction",
        "target" => URLROOT . "/stemwijzer",
        "name" => "Start Stemwijzer Test"
    ]
];

$faqStructuredData = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "Hoe betrouwbaar is de stemwijzer test?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Onze stemwijzer gebruikt officiële partijstandpunten uit verkiezingsprogramma's en recente uitspraken. Alle 14 Nederlandse politieke partijen zijn vertegenwoordigd met actuele standpunten over 30 thema's."
            ]
        ],
        [
            "@type" => "Question", 
            "name" => "Hoe lang duurt de stemwijzer test?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "De complete stemwijzer test duurt ongeveer 5-7 minuten. Je beantwoordt 30 stellingen over actuele politieke thema's en krijgt direct je persoonlijke resultaten met partij-matches."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Welke partijen zitten in de stemwijzer?", 
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Alle 14 grote Nederlandse politieke partijen: VVD, PVV, NSC, BBB, GL-PvdA, D66, SP, PvdD, CDA, JA21, SGP, FvD, DENK en Volt. Updated voor de verkiezingen 2025."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Is mijn stemwijzer data veilig?",
            "acceptedAnswer" => [
                "@type" => "Answer", 
                "text" => "Ja, je privacy is gegarandeerd. We slaan geen persoonlijke gegevens op en je antwoorden zijn volledig anoniem. Alleen jij ziet je resultaten, tenzij je ervoor kiest deze te delen."
            ]
        ]
    ]
];

$howToStructuredData = [
    "@context" => "https://schema.org",
    "@type" => "HowTo",
    "name" => "Hoe doe je de Stemwijzer 2025 test",
    "description" => "Stap-voor-stap uitleg hoe je de Nederlandse Stemwijzer 2025 test doet om jouw politieke match te vinden",
    "totalTime" => "PT7M",
    "supply" => [
        [
            "@type" => "HowToSupply",
            "name" => "Computer of smartphone met internetverbinding"
        ]
    ],
    "step" => [
        [
            "@type" => "HowToStep",
            "name" => "Beantwoord Stellingen",
            "text" => "Ga door 30 actuele politieke stellingen over thema's als klimaat, zorg, economie en immigratie. Kies uit 'eens', 'oneens' of 'neutraal'."
        ],
        [
            "@type" => "HowToStep", 
            "name" => "Vergelijk Standpunten",
            "text" => "Onze algoritme vergelijkt jouw antwoorden met de officiële standpunten van alle 14 Nederlandse politieke partijen uit hun verkiezingsprogramma's."
        ],
        [
            "@type" => "HowToStep",
            "name" => "Ontvang Resultaten", 
            "text" => "Krijg een overzicht van je politieke profiel, persoonlijkheidsanalyse en zie welke partijen het beste bij jouw standpunten passen."
        ]
    ]
];
?>

<!-- Structured Data voor SEO -->
<script type="application/ld+json">
<?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<script type="application/ld+json">
<?= json_encode($faqStructuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<script type="application/ld+json">
<?= json_encode($howToStructuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<!-- Custom Styles for Modern Stemwijzer -->
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

.progress-ring {
    transform: rotate(-90deg);
}

.progress-circle {
    transition: stroke-dashoffset 0.6s ease-in-out;
}
</style>



<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-red-50">
    
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-24 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- Header badge -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Verkiezingen 2025 - Jouw stem telt</span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                        Stemwijzer 2025
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Nederland
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                        Gratis online stemtest - Ontdek binnen 5 minuten welke Nederlandse politieke partij het beste bij jouw standpunten past
                    </p>
                </div>
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo $totalQuestions; ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Stellingen</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo count($stemwijzerData['parties']); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Partijen</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">2025</div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Verkiezingen</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>

    <!-- Main Content Container -->
    <div class="container mx-auto px-6 -mt-8 relative z-10">
        <!-- Loading indicator -->
        <div id="loading-indicator" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-3xl p-8 text-center shadow-2xl">
                <div class="w-16 h-16 mx-auto mb-6">
                    <svg class="animate-spin w-full h-full text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stemwijzer App Container -->
        <div id="stemwijzer-app" class="max-w-6xl mx-auto pb-20" x-data="stemwijzer()">
            
            <!-- Modern Progress Card -->
            <div x-show="screen !== 'start'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-y-8"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mb-8">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 relative overflow-hidden">
                    <!-- Decorative background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-red-50/50"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-blue-200/20 to-red-200/20 blur-3xl"></div>
                    
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
                                        <span class="text-sm font-bold text-blue-600" x-text="currentStep + 1"></span>
                                    </div>
                                    <!-- Gradient Definition -->
                                    <defs>
                                        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#1a56db"/>
                                            <stop offset="100%" stop-color="#c41e3a"/>
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
                                <div class="text-2xl font-bold text-blue-600" x-text="Math.round((currentStep / totalSteps) * 100) + '%'"></div>
                                <div class="text-xs text-gray-500">voltooid</div>
                            </div>
                        </div>
                        
                        <!-- Modern Progress Bar -->
                        <div class="relative">
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-blue-500 via-red-500 to-blue-600 rounded-full transition-all duration-700 ease-out relative"
                                     :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                                    <!-- Shine effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                </div>
                            </div>
                            
                            <!-- Progress Milestones -->
                            <div class="flex justify-between mt-4">
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mb-1"></div>
                                    <span>Start</span>
                                </div>
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full" :class="currentStep >= totalSteps/2 ? 'bg-blue-500' : 'bg-gray-300'"></div>
                                    <span>Halverwege</span>
                                </div>
                                <div class="text-xs text-gray-400 flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full" :class="currentStep >= totalSteps-1 ? 'bg-blue-500' : 'bg-gray-300'"></div>
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
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 via-red-50/20 to-blue-50/30"></div>
                    <div class="absolute top-0 right-0 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 rounded-full bg-gradient-to-br from-blue-200/20 to-red-200/20 blur-3xl floating-animation"></div>
                    <div class="absolute bottom-0 left-0 w-24 sm:w-36 md:w-48 h-24 sm:h-36 md:h-48 rounded-full bg-gradient-to-tr from-red-200/20 to-blue-200/20 blur-3xl floating-animation" style="animation-delay: -2s;"></div>
                    
                    <div class="relative z-10">
                        <!-- Header Section -->
                        <div class="text-center mb-8 sm:mb-10 md:mb-12">
                            <!-- Icon -->
                            <div class="inline-flex items-center justify-center w-16 sm:w-18 md:w-20 h-16 sm:h-18 md:h-20 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-red-600 shadow-lg shadow-blue-500/25 mb-4 sm:mb-5 md:mb-6">
                                <svg class="w-8 sm:w-9 md:w-10 h-8 sm:h-9 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3 sm:mb-4">
                                Welkom bij de gratis
                                <span class="block sm:inline text-gradient bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    Online Stemtest Nederland
                                </span>
                            </h2>
                            
                            <p class="text-base sm:text-lg md:text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto px-2 sm:px-0">
                                Vergelijk alle Nederlandse politieke partijen en ontdek welke partij het beste aansluit bij jouw politieke overtuigingen door onze uitgebreide stemtest voor de verkiezingen 2025 in te vullen.
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-10 md:mb-12">
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-50 to-red-50 border border-blue-100/50 card-hover">
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
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-red-50 to-blue-50 border border-red-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
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
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-50 to-red-50 border border-blue-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-blue-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
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
                            
                            <div class="group p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-br from-red-50 to-blue-50 border border-red-100/50 card-hover">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="w-10 sm:w-11 md:w-12 h-10 sm:h-11 md:h-12 rounded-lg sm:rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
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
                                    class="group relative w-full sm:w-auto px-8 sm:px-10 md:px-12 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-red-600 text-white font-semibold text-base sm:text-lg rounded-xl sm:rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-2xl hover:shadow-blue-500/40 transform hover:scale-105 transition-all duration-300 mb-4 sm:mb-6">
                                <div class="flex items-center justify-center space-x-2 sm:space-x-3">
                                    <span>Start de Gratis Stemtest 2025</span>
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
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 via-red-50/20 to-blue-50/30"></div>
                            <div class="absolute top-0 right-0 w-24 sm:w-32 md:w-48 h-24 sm:h-32 md:h-48 rounded-full bg-gradient-to-br from-blue-200/10 to-red-200/10 blur-3xl"></div>
                            
                            <div class="relative z-10">
                                <!-- Question Header -->
                                <div class="flex items-center justify-between mb-6 sm:mb-8">
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        <div class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-blue-100 to-red-100 rounded-full">
                                            <span class="text-xs sm:text-sm font-semibold text-blue-700">
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
                                    
                                    <!-- Explanation Toggles -->
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        <button @click="showExplanation = !showExplanation"
                                                class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-50 hover:bg-blue-100 rounded-lg sm:rounded-xl text-blue-700 text-xs sm:text-sm font-medium transition-colors border border-blue-200/50">
                                            <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Basis uitleg</span>
                                            <svg class="w-3 sm:w-4 h-3 sm:h-4 ml-1.5 sm:ml-2 transition-transform duration-200" :class="showExplanation ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <button @click="loadAIExplanation()" 
                                                :disabled="loadingAIExplanation"
                                                class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-lg sm:rounded-xl text-purple-700 text-xs sm:text-sm font-medium transition-colors border border-purple-200/50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg x-show="!loadingAIExplanation" class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            <svg x-show="loadingAIExplanation" class="animate-spin w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span x-text="loadingAIExplanation ? 'AI denkt na...' : 'AI Uitleg'"></span>
                                        </button>
                                    </div>

                                    <!-- Detailed Explanation Panel -->
                                    <div x-show="showExplanation" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mt-4 sm:mt-6 bg-gradient-to-br from-blue-50 to-red-50 rounded-xl sm:rounded-2xl border border-blue-200/30 overflow-hidden">
                                        
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
                                    
                                    <!-- AI Explanation Panel -->
                                    <div x-show="showAIExplanation && aiExplanationContent" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mt-4 sm:mt-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl border border-purple-200/30 overflow-hidden">
                                        
                                        <div class="p-4 sm:p-6">
                                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                                <h3 class="text-base sm:text-lg font-semibold text-purple-900 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                                    </svg>
                                                    AI Expert Uitleg
                                                </h3>
                                                <button @click="showAIExplanation = false" 
                                                        class="p-2 rounded-full hover:bg-purple-100 transition-colors">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="prose prose-purple max-w-none">
                                                <p class="text-purple-800 leading-relaxed mb-0 text-sm sm:text-base whitespace-pre-line" x-text="aiExplanationContent"></p>
                                            </div>
                                            
                                            <div class="mt-4 pt-4 border-t border-purple-200/50">
                                                <div class="flex items-center text-xs text-purple-600">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Deze uitleg is gegenereerd door AI en is bedoeld als aanvullende informatie
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
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-red-50/30"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center space-x-2 sm:space-x-3 mb-4 sm:mb-6">
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-500 to-red-600 flex items-center justify-center">
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
                                            <span class="text-xs sm:text-sm font-bold text-blue-600">
                                                <span x-text="Object.keys(answers).length"></span>/<span x-text="totalSteps"></span>
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-red-500 rounded-full transition-all duration-500"
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
                        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/20 to-red-50/20"></div>
                            
                            <div class="relative z-10">
                                <!-- Clean Header -->
                                <div class="p-4 border-b border-gray-100/80">
                                    <button @click="showPartyPositions = !showPartyPositions" 
                                            class="w-full flex items-center justify-between group">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-red-600 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-left">
                                                    Partij Standpunten
                                                </h3>
                                                <p class="text-xs text-gray-500 mt-0.5" x-show="!showPartyPositions">
                                                    Bekijk hoe partijen denken over deze stelling
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Clean Toggle Icon -->
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-all duration-200" 
                                                 :class="showPartyPositions ? 'rotate-180' : ''" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                                
                                <!-- Improved Collapsible Content -->
                                <div x-show="showPartyPositions" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                                     class="p-4 space-y-4" 
                                     x-init="updatePartyGroups()">
                                     
                                    <!-- Compact Party Groups -->
                                    <div class="space-y-3">
                                        <!-- Eens partijen -->
                                        <div x-show="eensParties.length > 0" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                                <span class="text-sm font-medium text-emerald-700">Eens</span>
                                                <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full" x-text="eensParties.length"></span>
                                            </div>
                                                                                         <div class="flex flex-wrap gap-2 ml-4">
                                                 <template x-for="party in eensParties.slice(0, 8)" :key="party">
                                                     <div class="relative group">
                                                         <div class="w-10 h-10 rounded-md bg-white border border-emerald-200 hover:border-emerald-400 p-1 transition-all duration-200 hover:scale-110">
                                                             <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-sm">
                                                         </div>
                                                         <!-- Enhanced Tooltip -->
                                                         <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-30 pointer-events-none">
                                                             <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap shadow-lg">
                                                                 <span x-text="party"></span>
                                                             </div>
                                                             <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[6px] border-transparent border-t-gray-900 mx-auto"></div>
                                                         </div>
                                                     </div>
                                                 </template>
                                                 <div x-show="eensParties.length > 8" class="flex items-center justify-center w-10 h-10 rounded-md bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-600">
                                                     +<span x-text="eensParties.length - 8"></span>
                                                 </div>
                                             </div>
                                        </div>
                                        
                                        <!-- Neutraal partijen -->
                                        <div x-show="neutraalParties.length > 0" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                                                <span class="text-sm font-medium text-blue-700">Neutraal</span>
                                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full" x-text="neutraalParties.length"></span>
                                            </div>
                                                                                         <div class="flex flex-wrap gap-2 ml-4">
                                                 <template x-for="party in neutraalParties.slice(0, 8)" :key="party">
                                                     <div class="relative group">
                                                         <div class="w-10 h-10 rounded-md bg-white border border-blue-200 hover:border-blue-400 p-1 transition-all duration-200 hover:scale-110">
                                                             <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-sm">
                                                         </div>
                                                         <!-- Enhanced Tooltip -->
                                                         <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-30 pointer-events-none">
                                                             <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap shadow-lg">
                                                                 <span x-text="party"></span>
                                                             </div>
                                                             <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[6px] border-transparent border-t-gray-900 mx-auto"></div>
                                                         </div>
                                                     </div>
                                                 </template>
                                                 <div x-show="neutraalParties.length > 8" class="flex items-center justify-center w-10 h-10 rounded-md bg-blue-50 border border-blue-200 text-xs font-medium text-blue-600">
                                                     +<span x-text="neutraalParties.length - 8"></span>
                                                 </div>
                                             </div>
                                        </div>
                                        
                                        <!-- Oneens partijen -->
                                        <div x-show="oneensParties.length > 0" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                                <span class="text-sm font-medium text-red-700">Oneens</span>
                                                <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded-full" x-text="oneensParties.length"></span>
                                            </div>
                                                                                         <div class="flex flex-wrap gap-2 ml-4">
                                                 <template x-for="party in oneensParties.slice(0, 8)" :key="party">
                                                     <div class="relative group">
                                                         <div class="w-10 h-10 rounded-md bg-white border border-red-200 hover:border-red-400 p-1 transition-all duration-200 hover:scale-110">
                                                             <img :src="partyLogos[party]" :alt="party" class="w-full h-full object-contain rounded-sm">
                                                         </div>
                                                         <!-- Enhanced Tooltip -->
                                                         <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-30 pointer-events-none">
                                                             <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap shadow-lg">
                                                                 <span x-text="party"></span>
                                                             </div>
                                                             <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[6px] border-transparent border-t-gray-900 mx-auto"></div>
                                                         </div>
                                                     </div>
                                                 </template>
                                                 <div x-show="oneensParties.length > 8" class="flex items-center justify-center w-10 h-10 rounded-md bg-red-50 border border-red-200 text-xs font-medium text-red-600">
                                                     +<span x-text="oneensParties.length - 8"></span>
                                                 </div>
                                             </div>
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
                
                <!-- Jouw Politieke Persoonlijkheid sectie - tijdelijk verborgen -->
                <div style="display: none;">
                    <!-- Persoonlijkheidsanalyse Hero -->
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg shadow-purple-500/25 mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 flex items-center justify-center gap-3">
                            <span>Jouw Politieke
                                <span class="text-gradient bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                    Persoonlijkheid
                                </span>
                            </span>
                            <div class="relative group">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center cursor-help shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 px-4 py-3 bg-gray-900 text-white text-sm rounded-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 w-80 z-50 shadow-2xl">
                                    <div class="space-y-3">
                                        <p class="font-semibold text-purple-300">Hoe wordt dit berekend?</p>
                                        <div class="space-y-2 text-xs leading-relaxed">
                                            <p><strong>Categorisatie:</strong> Vragen worden automatisch ingedeeld in economische, sociale, progressieve, autoritaire en EU-gerelateerde onderwerpen op basis van kernwoorden.</p>
                                            <p><strong>Scoring:</strong> Per categorie krijg je een score van 0-100% gebaseerd op je antwoorden (eens = +1, oneens = -1, neutraal = 0).</p>
                                            <p><strong>Profiel:</strong> Je politieke type wordt bepaald door je economische (links-rechts) en progressieve scores te combineren.</p>
                                            <p><strong>Kompas:</strong> Je positie gebruikt economische en sociale scores voor de X/Y-as.</p>
                                        </div>
                                    </div>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                        </h2>
                        
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-8">
                            Op basis van jouw antwoorden hebben we een uniek profiel samengesteld dat jouw politieke voorkeur en persoonlijkheid beschrijft.
                        </p>
                    </div>

                    <!-- Hoofdprofiel Card -->
                    <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden mb-12">
                        <div class="bg-gradient-to-r p-8 text-white relative overflow-hidden" :class="personalityAnalysis.political_profile.color">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>
                            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/10 blur-3xl transform translate-x-32 -translate-y-32"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-3xl font-bold mb-2" x-text="personalityAnalysis.political_profile.type"></h3>
                                        <p class="text-white/90 text-lg leading-relaxed" x-text="personalityAnalysis.political_profile.description"></p>
                                    </div>
                                    <div class="text-6xl opacity-20">🗳️</div>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white/20 rounded-xl p-4 text-center">
                                        <div class="text-2xl font-bold" x-text="Math.round(personalityAnalysis.left_right_percentage) + '%'"></div>
                                        <div class="text-sm opacity-90">Rechts</div>
                                    </div>
                                    <div class="bg-white/20 rounded-xl p-4 text-center">
                                        <div class="text-2xl font-bold" x-text="Math.round(personalityAnalysis.progressive_percentage) + '%'"></div>
                                        <div class="text-sm opacity-90">Progressief</div>
                                    </div>
                                    <div class="bg-white/20 rounded-xl p-4 text-center">
                                        <div class="text-2xl font-bold" x-text="Math.round(personalityAnalysis.authoritarian_percentage) + '%'"></div>
                                        <div class="text-sm opacity-90">Autoritair</div>
                                    </div>
                                    <div class="bg-white/20 rounded-xl p-4 text-center">
                                        <div class="text-2xl font-bold" x-text="Math.round(personalityAnalysis.eu_pro_percentage) + '%'"></div>
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
                                    <div class="absolute w-4 h-4 rounded-full border-2 border-white shadow-lg transform -translate-x-2 -translate-y-2"
                                         :class="'bg-' + personalityAnalysis.compass_position.quadrant.color + '-500'"
                                         :style="'left: ' + ((personalityAnalysis.compass_position.x + 50) * 2.8) + 'px; top: ' + ((-personalityAnalysis.compass_position.y + 50) * 2.8) + 'px;'">
                                    </div>
                                    
                                    <!-- Centrum punt -->
                                    <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-gray-400 rounded-full transform -translate-x-1 -translate-y-1"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-full font-semibold"
                                     :class="'bg-' + personalityAnalysis.compass_position.quadrant.color + '-100 text-' + personalityAnalysis.compass_position.quadrant.color + '-800'">
                                    <div class="w-3 h-3 rounded-full mr-2"
                                         :class="'bg-' + personalityAnalysis.compass_position.quadrant.color + '-500'"></div>
                                    <span x-text="personalityAnalysis.compass_position.quadrant.name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Persoonlijkheidskenmerken -->
                    <div x-show="personalityAnalysis.personality_traits.length > 0" class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-8 mb-12">
                        <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jouw Politieke Kenmerken</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="trait in personalityAnalysis.personality_traits" :key="trait.name">
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 text-center hover:shadow-lg transition-shadow">
                                    <div class="text-4xl mb-4" x-text="trait.icon"></div>
                                    <h5 class="text-lg font-bold text-gray-800 mb-2" x-text="trait.name"></h5>
                                    <p class="text-gray-600 text-sm leading-relaxed" x-text="trait.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>

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
                                         :style="'width: ' + Math.round(personalityAnalysis.left_right_percentage) + '%; opacity: 0.8;'"></div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                         :style="'left: calc(' + Math.round(personalityAnalysis.left_right_percentage) + '% - 6px);'"></div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-lg font-bold text-gray-700"><span x-text="Math.round(personalityAnalysis.left_right_percentage)"></span>% Rechts georiënteerd</span>
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
                                         :style="'width: ' + Math.round(personalityAnalysis.progressive_percentage) + '%; opacity: 0.8;'"></div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                         :style="'left: calc(' + Math.round(personalityAnalysis.progressive_percentage) + '% - 6px);'"></div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-lg font-bold text-gray-700"><span x-text="Math.round(personalityAnalysis.progressive_percentage)"></span>% Progressief</span>
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
                                         :style="'width: ' + Math.round(personalityAnalysis.authoritarian_percentage) + '%; opacity: 0.8;'"></div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                         :style="'left: calc(' + Math.round(personalityAnalysis.authoritarian_percentage) + '% - 6px);'"></div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-lg font-bold text-gray-700"><span x-text="Math.round(personalityAnalysis.authoritarian_percentage)"></span>% Autoritair</span>
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
                                         :style="'width: ' + Math.round(personalityAnalysis.eu_pro_percentage) + '%; opacity: 0.8;'"></div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white border-2 border-gray-400 rounded-full shadow"
                                         :style="'left: calc(' + Math.round(personalityAnalysis.eu_pro_percentage) + '% - 6px);'"></div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-lg font-bold text-gray-700"><span x-text="Math.round(personalityAnalysis.eu_pro_percentage)"></span>% Pro-EU</span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistieken -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-6 text-center">
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <div class="text-2xl font-bold text-blue-600" x-text="personalityAnalysis.total_answered"></div>
                                    <div class="text-sm text-blue-700">Vragen beantwoord</div>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-4">
                                    <div class="text-2xl font-bold text-purple-600" x-text="personalityAnalysis.political_profile.type"></div>
                                    <div class="text-sm text-purple-700">Politiek Type</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col gap-2 items-center justify-center">
                                        <button @click="showPartyExplanation(finalResults[1])" 
                                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                                            Bekijk details
                                        </button>
                                    </div>
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
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col gap-3 items-center justify-center">
                                        <button @click="showPartyExplanation(finalResults[0])" 
                                                class="w-full max-w-xs px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-yellow-500/25">
                                            Bekijk details
                                        </button>
                                    </div>
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
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col gap-2 items-center justify-center">
                                        <button @click="showPartyExplanation(finalResults[2])" 
                                                class="px-6 py-3 bg-orange-100 hover:bg-orange-200 text-orange-800 rounded-xl font-medium transition-all duration-300 hover:shadow-lg">
                                            Bekijk details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complete Results List -->
                <div class="bg-white/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl shadow-2xl border border-white/50 overflow-hidden mb-12">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-gray-100">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Volledige Ranglijst</h3>
                        </div>
                    </div>
                    
                    <!-- Results List -->
                    <div class="divide-y divide-gray-100">
                        <template x-for="(result, index) in finalResults" :key="index">
                            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 hover:bg-gray-50 transition-all duration-300 group cursor-pointer" @click="showPartyExplanation(result)">
                                <div class="flex items-center space-x-3 sm:space-x-4 lg:space-x-6">
                                    <!-- Rank -->
                                    <div class="flex-shrink-0">
                                        <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center font-bold text-sm sm:text-lg"
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
                                    <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                                        <!-- Logo -->
                                        <div class="w-10 sm:w-12 lg:w-14 h-10 sm:h-12 lg:h-14 rounded-lg sm:rounded-xl bg-white border border-gray-200 p-1 sm:p-2 shadow-sm group-hover:shadow-md transition-shadow flex-shrink-0">
                                            <img :src="result.logo" :alt="result.name" class="w-full h-full object-contain rounded-sm sm:rounded-lg">
                                        </div>
                                        
                                        <!-- Name & Progress -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 mb-1 sm:mb-2 group-hover:text-indigo-600 transition-colors truncate" x-text="result.name"></h4>
                                            
                                            <!-- Progress Bar -->
                                            <div class="flex items-center space-x-2 sm:space-x-3">
                                                <div class="flex-1 h-2 sm:h-3 bg-gray-100 rounded-full overflow-hidden">
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
                                                <div class="text-xs sm:text-sm text-gray-500 min-w-[2.5rem] sm:min-w-[4rem]" x-text="result.agreement + '%'"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Percentage & Arrow -->
                                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                                        <div class="text-right hidden sm:block">
                                            <div class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-800" x-text="result.agreement + '%'"></div>
                                            <div class="text-xs sm:text-sm text-gray-500">match</div>
                                        </div>
                                        
                                        <svg class="w-4 sm:w-5 lg:w-6 h-4 sm:h-5 lg:h-6 text-gray-400 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    
                    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden" @click.away="closePartyDetails()">
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
                                <button @click="closePartyDetails()" 
                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors group">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Modal Content -->
                        <div class="p-8 overflow-y-auto max-h-[calc(90vh-120px)]">
                            <!-- AI Explanation Section -->
                            <div x-show="detailedParty?.aiExplanation" class="mb-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/30 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        <h4 class="text-xl font-bold text-purple-900">AI Expert Analyse: Waarom Past Dit Bij Jou?</h4>
                                    </div>
                                    <div class="prose prose-purple max-w-none">
                                        <p class="text-purple-800 leading-relaxed whitespace-pre-line" x-text="detailedParty?.aiExplanation"></p>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-purple-200/50">
                                        <div class="flex items-center text-sm text-purple-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Deze analyse is gegenereerd door AI op basis van jouw antwoorden
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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

                <!-- AI Political Advice Section -->
                <div id="ai-advice-section" class="mb-12 bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl border border-purple-200/50 p-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-100/20 to-pink-100/20"></div>
                    
                    <div class="relative z-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg shadow-purple-500/25 mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Persoonlijk AI Stemadvies</h3>
                        <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                            Krijg persoonlijk stemadvies van onze AI expert, gebaseerd op jouw unieke politieke profiel.
                        </p>
                        
                        <!-- AI Advice Content -->
                        <div x-show="aiAdviceContent" class="bg-white rounded-2xl border border-purple-200 p-6 mb-6 max-w-4xl mx-auto text-left">
                            <div class="prose prose-purple max-w-none">
                                <p class="text-gray-800 leading-relaxed whitespace-pre-line" x-text="aiAdviceContent"></p>
                            </div>
                        </div>
                        
                        <!-- Load AI Advice Button -->
                        <div x-show="!aiAdviceContent">
                            <button @click="loadPoliticalAdvice()" 
                                    :disabled="loadingAIAdvice"
                                    class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-xl shadow-lg shadow-purple-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg x-show="!loadingAIAdvice" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <svg x-show="loadingAIAdvice" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="loadingAIAdvice ? 'AI expert analyseert jouw profiel...' : 'Krijg Persoonlijk AI Stemadvies'"></span>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Disclaimer -->
                        <div class="mt-6 text-sm text-purple-600">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dit advies is gegenereerd door AI en is bedoeld als aanvullende informatie bij je stemkeuze
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Link Section (Only show if we have a share URL) -->
                <div x-show="shareUrl" class="mb-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl border border-blue-200/50 p-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100/20 to-indigo-100/20"></div>
                    
                    <div class="relative z-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/25 mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Jouw Persoonlijke Resultatenlink</h3>
                        <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                            Bewaar deze link om je resultaten later opnieuw te bekijken, of deel hem met vrienden en familie.
                        </p>
                        
                        <!-- Share URL Display -->
                        <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 max-w-3xl mx-auto">
                            <div class="flex items-center space-x-3">
                                <div class="flex-1 text-left">
                                    <div class="text-sm text-gray-500 mb-1">Jouw resultatenlink:</div>
                                    <div class="text-blue-600 font-mono text-sm break-all" x-text="shareUrl"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions for Share Link -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <button @click="copyShareUrl()" 
                                    class="group px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Link kopiëren</span>
                            </button>
                            
                            <button @click="openShareUrl()" 
                                    class="group px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Bekijk link</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

    <!-- SEO Content Sections -->
    <section class="bg-white py-16 relative">
        <div class="container mx-auto px-6">
                         <!-- FAQ Section -->
             <div class="max-w-5xl mx-auto mb-16">
                 <!-- Header with decorative elements -->
                 <div class="relative text-center mb-16">
                     <!-- Background decoration -->
                     <div class="absolute inset-0 flex items-center justify-center opacity-5">
                         <svg class="w-64 h-64 text-primary" fill="currentColor" viewBox="0 0 24 24">
                             <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                         </svg>
                     </div>
                     
                     <!-- Badge -->
                     <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-full border border-primary/20 mb-6">
                         <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                         </svg>
                         <span class="text-primary font-semibold text-sm">Veel Gesteld</span>
                     </div>

                     <!-- Main heading -->
                     <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-800 mb-6 leading-tight">
                         Alles over de
                         <span class="block bg-gradient-to-r from-primary via-secondary to-primary bg-clip-text text-transparent">
                             Stemwijzer 2025
                         </span>
                     </h2>
                     
                     <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                         Ontdek de antwoorden op de meest gestelde vragen over onze gratis online stemtest
                     </p>
                 </div>

                 <!-- FAQ Accordion -->
                 <div class="space-y-4" x-data="{ openFaq: 1 }">
                     <!-- FAQ Item 1 -->
                     <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                         <button @click="openFaq = openFaq === 1 ? null : 1" 
                                 class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all duration-300"
                                 :class="openFaq === 1 ? 'bg-gradient-to-r from-primary/5 to-secondary/5' : 'hover:bg-gray-50'">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center space-x-4 flex-1">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                         </svg>
                                     </div>
                                     <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">
                                         Hoe betrouwbaar is de stemwijzer test?
                                     </h3>
                                 </div>
                                 <div class="flex-shrink-0 ml-4">
                                     <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                                          :class="openFaq === 1 ? 'rotate-180' : ''" 
                                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                     </svg>
                                 </div>
                             </div>
                         </button>
                         <div x-show="openFaq === 1" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 max-h-0"
                              x-transition:enter-end="opacity-100 max-h-96"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 max-h-96"
                              x-transition:leave-end="opacity-0 max-h-0"
                              class="overflow-hidden">
                             <div class="px-6 md:px-8 pt-4 pb-6 md:pt-6 md:pb-8">
                                 <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                     Onze stemwijzer gebruikt officiële partijstandpunten uit verkiezingsprogramma's en recente uitspraken. 
                                     Alle 14 Nederlandse politieke partijen zijn vertegenwoordigd met actuele standpunten over 30 thema's. 
                                     De data wordt regelmatig geüpdatet om de meest actuele politieke posities weer te geven.
                                 </p>
                             </div>
                         </div>
                     </div>

                     <!-- FAQ Item 2 -->
                     <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                         <button @click="openFaq = openFaq === 2 ? null : 2" 
                                 class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all duration-300"
                                 :class="openFaq === 2 ? 'bg-gradient-to-r from-primary/5 to-secondary/5' : 'hover:bg-gray-50'">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center space-x-4 flex-1">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-secondary to-primary flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                         </svg>
                                     </div>
                                     <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">
                                         Hoe lang duurt de stemwijzer test?
                                     </h3>
                                 </div>
                                 <div class="flex-shrink-0 ml-4">
                                     <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                                          :class="openFaq === 2 ? 'rotate-180' : ''" 
                                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                     </svg>
                                 </div>
                             </div>
                         </button>
                         <div x-show="openFaq === 2" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 max-h-0"
                              x-transition:enter-end="opacity-100 max-h-96"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 max-h-96"
                              x-transition:leave-end="opacity-0 max-h-0"
                              class="overflow-hidden">
                             <div class="px-6 md:px-8 pt-4 pb-6 md:pt-6 md:pb-8">
                                 <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                     De complete stemwijzer test duurt ongeveer 5-7 minuten. Je beantwoordt 30 stellingen over actuele 
                                     politieke thema's en krijgt direct je persoonlijke resultaten met partij-matches. Je kunt de test ook 
                                     pauzeren en later hervatten.
                                 </p>
                             </div>
                         </div>
                     </div>

                     <!-- FAQ Item 3 -->
                     <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                         <button @click="openFaq = openFaq === 3 ? null : 3" 
                                 class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all duration-300"
                                 :class="openFaq === 3 ? 'bg-gradient-to-r from-primary/5 to-secondary/5' : 'hover:bg-gray-50'">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center space-x-4 flex-1">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                         </svg>
                                     </div>
                                     <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">
                                         Welke partijen zitten in de stemwijzer?
                                     </h3>
                                 </div>
                                 <div class="flex-shrink-0 ml-4">
                                     <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                                          :class="openFaq === 3 ? 'rotate-180' : ''" 
                                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                     </svg>
                                 </div>
                             </div>
                         </button>
                         <div x-show="openFaq === 3" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 max-h-0"
                              x-transition:enter-end="opacity-100 max-h-96"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 max-h-96"
                              x-transition:leave-end="opacity-0 max-h-0"
                              class="overflow-hidden">
                             <div class="px-6 md:px-8 pt-4 pb-6 md:pt-6 md:pb-8">
                                 <p class="text-gray-600 leading-relaxed text-base md:text-lg mb-6">
                                     Alle 14 grote Nederlandse politieke partijen zijn opgenomen in de Stemwijzer 2025:
                                 </p>
                                 <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                                     <?php 
                                     $parties = ['VVD', 'PVV', 'NSC', 'BBB', 'GL-PvdA', 'D66', 'SP', 'PvdD', 'CDA', 'JA21', 'SGP', 'FvD', 'DENK', 'Volt'];
                                     foreach($parties as $party): ?>
                                     <div class="bg-gray-50 rounded-lg px-3 py-2 border border-gray-200 text-center">
                                         <span class="text-sm font-medium text-gray-700"><?= $party ?></span>
                                     </div>
                                     <?php endforeach; ?>
                                 </div>
                                 <p class="text-gray-500 text-sm">
                                     Alle standpunten zijn geüpdatet voor de verkiezingen 2025
                                 </p>
                             </div>
                         </div>
                     </div>

                     <!-- FAQ Item 4 -->
                     <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                         <button @click="openFaq = openFaq === 4 ? null : 4" 
                                 class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all duration-300"
                                 :class="openFaq === 4 ? 'bg-gradient-to-r from-primary/5 to-secondary/5' : 'hover:bg-gray-50'">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center space-x-4 flex-1">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-secondary to-primary flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                         </svg>
                                     </div>
                                     <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">
                                         Is mijn stemwijzer data veilig?
                                     </h3>
                                 </div>
                                 <div class="flex-shrink-0 ml-4">
                                     <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                                          :class="openFaq === 4 ? 'rotate-180' : ''" 
                                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                     </svg>
                                 </div>
                             </div>
                         </button>
                         <div x-show="openFaq === 4" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 max-h-0"
                              x-transition:enter-end="opacity-100 max-h-96"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 max-h-96"
                              x-transition:leave-end="opacity-0 max-h-0"
                              class="overflow-hidden">
                             <div class="px-6 md:px-8 pt-4 pb-6 md:pt-6 md:pb-8">
                                 <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                     Ja, je privacy is volledig gegarandeerd. We slaan geen persoonlijke gegevens op en je antwoorden 
                                     zijn volledig anoniem. Alleen jij ziet je resultaten, tenzij je ervoor kiest deze te delen via een 
                                     unieke link. We gebruiken moderne encryptie en volgen alle Nederlandse privacywetgeving (AVG/GDPR).
                                 </p>
                             </div>
                         </div>
                     </div>

                     <!-- FAQ Item 5 - Bonus -->
                     <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                         <button @click="openFaq = openFaq === 5 ? null : 5" 
                                 class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all duration-300"
                                 :class="openFaq === 5 ? 'bg-gradient-to-r from-primary/5 to-secondary/5' : 'hover:bg-gray-50'">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center space-x-4 flex-1">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                         </svg>
                                     </div>
                                     <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">
                                         Wat krijg ik als resultaat?
                                     </h3>
                                 </div>
                                 <div class="flex-shrink-0 ml-4">
                                     <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                                          :class="openFaq === 5 ? 'rotate-180' : ''" 
                                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                     </svg>
                                 </div>
                             </div>
                         </button>
                         <div x-show="openFaq === 5" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 max-h-0"
                              x-transition:enter-end="opacity-100 max-h-96"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 max-h-96"
                              x-transition:leave-end="opacity-0 max-h-0"
                              class="overflow-hidden">
                             <div class="px-6 md:px-8 pt-4 pb-6 md:pt-6 md:pb-8">
                                 <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                     Je krijgt een uitgebreid overzicht met je partij-matches (in percentages), een politiek kompas dat 
                                     je positie weergeeft, een persoonlijkheidsanalyse van je politieke voorkeuren, en gedetailleerde 
                                     uitleg waarom bepaalde partijen wel of niet bij je passen. Je kunt je resultaten ook delen of 
                                     vergelijken met vrienden.
                                 </p>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- CTA after FAQ -->
                 <div class="text-center mt-12 p-8 bg-gradient-to-r from-primary/5 via-secondary/5 to-primary/5 rounded-2xl border border-primary/10">
                     <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">
                         Nog vragen? Start gewoon de test!
                     </h3>
                     <p class="text-gray-600 mb-6">
                         De beste manier om de stemwijzer te begrijpen is door hem uit te proberen
                     </p>
                     <a href="#stemwijzer-app" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                         </svg>
                         Ga naar de stemwijzer
                     </a>
                 </div>
             </div>

            <!-- How It Works Section - Ultra Modern Design -->
            <div class="max-w-7xl mx-auto mb-20">
                <!-- Section Header with Enhanced Design -->
                <div class="relative text-center mb-16">
                    <!-- Background decorative elements -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-5">
                        <svg class="w-80 h-80 text-primary" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>

                    <!-- Badge with animation -->
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 
                               rounded-full border border-primary/20 mb-8 backdrop-blur-sm">
                        <div class="w-3 h-3 bg-primary rounded-full mr-3 animate-pulse"></div>
                        <span class="text-primary font-semibold text-sm tracking-wide uppercase">Zo Simpel Werkt Het</span>
                    </div>

                    <!-- Main heading with enhanced typography -->
                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-800 mb-6 leading-tight tracking-tight">
                        <span class="block mb-2">Hoe werkt de</span>
                        <span class="bg-gradient-to-r from-primary via-secondary to-primary-light bg-clip-text text-transparent">
                            Online Stemtest?
                        </span>
                    </h2>
                    
                    <!-- Enhanced subtitle -->
                    <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-light">
                        In slechts <span class="font-semibold text-primary">3 eenvoudige stappen</span> naar jouw 
                        <span class="font-semibold text-secondary">perfecte politieke match</span>
                    </p>

                    <!-- Decorative line -->
                    <div class="flex items-center justify-center mt-8 space-x-4">
                        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary"></div>
                        <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                        <div class="w-24 h-0.5 bg-gradient-to-r from-primary via-secondary to-primary"></div>
                        <div class="w-3 h-3 bg-secondary rounded-full animate-pulse animation-delay-500"></div>
                        <div class="w-16 h-0.5 bg-gradient-to-r from-secondary to-transparent"></div>
                    </div>
                </div>

                <!-- Steps Grid with Glassmorphism Cards -->
                <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Step 1 - Enhanced Interactive Card -->
                    <div class="group relative">
                        <!-- Animated background glow -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-indigo-600/20 rounded-3xl 
                                   opacity-0 group-hover:opacity-100 transition-all duration-500 blur-xl"></div>
                        
                        <!-- Main card -->
                        <div class="relative bg-white/80 backdrop-blur-xl border border-white/30 rounded-3xl p-8 md:p-10 
                                   shadow-2xl shadow-blue-500/10 transform transition-all duration-500 
                                   group-hover:-translate-y-2 group-hover:shadow-3xl group-hover:shadow-blue-500/20">
                            
                            <!-- Icon container with advanced design -->
                            <div class="relative mb-8">
                                <!-- Outer glow ring -->
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl 
                                           opacity-20 group-hover:opacity-40 transition-all duration-500 scale-110"></div>
                                
                                <!-- Main icon container -->
                                <div class="relative w-20 h-20 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 
                                           rounded-3xl flex items-center justify-center shadow-xl group-hover:scale-110 
                                           transition-all duration-500">
                                    <!-- Question/Survey icon -->
                                    <svg class="w-10 h-10 text-white transform group-hover:rotate-12 transition-transform duration-500" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                
                                <!-- Step number badge -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 
                                           rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">1</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 group-hover:text-blue-600 
                                          transition-colors duration-300">
                                    Beantwoord Stellingen
                                </h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                    Doorloop <span class="font-semibold text-blue-600">30 actuele politieke stellingen</span> 
                                    over belangrijke thema's zoals klimaat, zorg, economie en immigratie. 
                                    Kies simpelweg uit <span class="font-semibold">'eens'</span>, 
                                    <span class="font-semibold">'oneens'</span> of <span class="font-semibold">'neutraal'</span>.
                                </p>
                                
                                <!-- Feature highlights -->
                                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                        5-7 minuten
                                    </span>
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                        30 vragen
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 - Enhanced Interactive Card -->
                    <div class="group relative">
                        <!-- Animated background glow -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-pink-600/20 rounded-3xl 
                                   opacity-0 group-hover:opacity-100 transition-all duration-500 blur-xl"></div>
                        
                        <!-- Main card -->
                        <div class="relative bg-white/80 backdrop-blur-xl border border-white/30 rounded-3xl p-8 md:p-10 
                                   shadow-2xl shadow-purple-500/10 transform transition-all duration-500 
                                   group-hover:-translate-y-2 group-hover:shadow-3xl group-hover:shadow-purple-500/20">
                            
                            <!-- Icon container -->
                            <div class="relative mb-8">
                                <!-- Outer glow ring -->
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl 
                                           opacity-20 group-hover:opacity-40 transition-all duration-500 scale-110"></div>
                                
                                <!-- Main icon container -->
                                <div class="relative w-20 h-20 mx-auto bg-gradient-to-br from-purple-500 to-pink-600 
                                           rounded-3xl flex items-center justify-center shadow-xl group-hover:scale-110 
                                           transition-all duration-500">
                                    <!-- Analysis/Compare icon -->
                                    <svg class="w-10 h-10 text-white transform group-hover:rotate-12 transition-transform duration-500" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                
                                <!-- Step number badge -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 
                                           rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">2</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 group-hover:text-purple-600 
                                          transition-colors duration-300">
                                    Intelligente Analyse
                                </h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                    Ons geavanceerde algoritme vergelijkt jouw antwoorden met de 
                                    <span class="font-semibold text-purple-600">officiële standpunten</span> van alle 
                                    <span class="font-semibold">14 Nederlandse politieke partijen</span> uit hun 
                                    verkiezingsprogramma's en recente uitspraken.
                                </p>
                                
                                <!-- Feature highlights -->
                                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                                        14 partijen
                                    </span>
                                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                                        Realtime
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 - Enhanced Interactive Card -->
                    <div class="group relative">
                        <!-- Animated background glow -->
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-600/20 rounded-3xl 
                                   opacity-0 group-hover:opacity-100 transition-all duration-500 blur-xl"></div>
                        
                        <!-- Main card -->
                        <div class="relative bg-white/80 backdrop-blur-xl border border-white/30 rounded-3xl p-8 md:p-10 
                                   shadow-2xl shadow-emerald-500/10 transform transition-all duration-500 
                                   group-hover:-translate-y-2 group-hover:shadow-3xl group-hover:shadow-emerald-500/20">
                            
                            <!-- Icon container -->
                            <div class="relative mb-8">
                                <!-- Outer glow ring -->
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl 
                                           opacity-20 group-hover:opacity-40 transition-all duration-500 scale-110"></div>
                                
                                <!-- Main icon container -->
                                <div class="relative w-20 h-20 mx-auto bg-gradient-to-br from-emerald-500 to-teal-600 
                                           rounded-3xl flex items-center justify-center shadow-xl group-hover:scale-110 
                                           transition-all duration-500">
                                    <!-- Results/Analytics icon -->
                                    <svg class="w-10 h-10 text-white transform group-hover:rotate-12 transition-transform duration-500" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                
                                <!-- Step number badge -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 
                                           rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">3</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 group-hover:text-emerald-600 
                                          transition-colors duration-300">
                                    Persoonlijke Resultaten
                                </h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">
                                    Ontvang direct een <span class="font-semibold text-emerald-600">uitgebreid overzicht</span> 
                                    van je politieke profiel, persoonlijkheidsanalyse en zie precies welke partijen 
                                    het beste aansluiten bij jouw <span class="font-semibold">unieke standpunten</span>.
                                </p>
                                
                                <!-- Feature highlights -->
                                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">
                                        Direct resultaat
                                    </span>
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">
                                        Deelbaar
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom call-to-action with enhanced design -->
                <div class="text-center mt-16">
                    <div class="inline-flex items-center space-x-3 px-8 py-4 bg-gradient-to-r from-primary/10 to-secondary/10 
                               rounded-2xl border border-primary/20 backdrop-blur-sm">
                        <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                        <span class="text-gray-700 font-medium">Klaar om je politieke match te ontdekken?</span>
                        <div class="w-3 h-3 bg-secondary rounded-full animate-pulse animation-delay-500"></div>
                    </div>
                </div>
            </div>

            <!-- Political Themes Preview - Completely Redesigned -->
            <div class="max-w-7xl mx-auto">
                <!-- Enhanced Section Header -->
                <div class="relative text-center mb-16">
                    <!-- Background decorative elements -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-5">
                        <svg class="w-96 h-96 text-secondary" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>

                    <!-- Badge -->
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-secondary/10 via-primary/10 to-secondary/10 
                               rounded-full border border-secondary/20 mb-8 backdrop-blur-sm">
                        <div class="w-3 h-3 bg-secondary rounded-full mr-3 animate-pulse"></div>
                        <span class="text-secondary font-semibold text-sm tracking-wide uppercase">Alle Belangrijke Onderwerpen</span>
                    </div>

                    <!-- Main heading -->
                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-800 mb-6 leading-tight tracking-tight">
                        <span class="block mb-2">30 Actuele</span>
                        <span class="bg-gradient-to-r from-secondary via-primary to-secondary-light bg-clip-text text-transparent">
                            Politieke Thema's
                        </span>
                    </h2>
                    
                    <!-- Enhanced subtitle -->
                    <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed font-light">
                        Vergelijk <span class="font-semibold text-secondary">partijstandpunten</span> op alle belangrijke onderwerpen 
                        van <span class="font-semibold text-primary">verkiezingen 2025</span>
                    </p>

                    <!-- Decorative line -->
                    <div class="flex items-center justify-center mt-8 space-x-4">
                        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-secondary"></div>
                        <div class="w-3 h-3 bg-secondary rounded-full animate-pulse"></div>
                        <div class="w-24 h-0.5 bg-gradient-to-r from-secondary via-primary to-secondary"></div>
                        <div class="w-3 h-3 bg-primary rounded-full animate-pulse animation-delay-500"></div>
                        <div class="w-16 h-0.5 bg-gradient-to-r from-primary to-transparent"></div>
                    </div>
                </div>

                <?php 
                $themeCategories = [
                    'Sociale & Zorg' => [
                        'color' => 'from-emerald-500 to-teal-600',
                        'bgColor' => 'from-emerald-50 to-teal-50',
                        'textColor' => 'text-emerald-700',
                        'borderColor' => 'border-emerald-200',
                        'hoverColor' => 'hover:border-emerald-400',
                        'icon' => '🏥',
                        'themes' => ['Gezondheidszorg', 'Sociale Zekerheid', 'Zorg voor Ouderen', 'Kinderopvang', 'Pensioenen']
                    ],
                    'Economie & Werk' => [
                        'color' => 'from-blue-500 to-indigo-600',
                        'bgColor' => 'from-blue-50 to-indigo-50',
                        'textColor' => 'text-blue-700',
                        'borderColor' => 'border-blue-200',
                        'hoverColor' => 'hover:border-blue-400',
                        'icon' => '💼',
                        'themes' => ['Economie & Werk', 'Belastingen', 'Startups & Innovatie', 'Internationale Handel']
                    ],
                    'Milieu & Klimaat' => [
                        'color' => 'from-green-500 to-emerald-600',
                        'bgColor' => 'from-green-50 to-emerald-50',
                        'textColor' => 'text-green-700',
                        'borderColor' => 'border-green-200',
                        'hoverColor' => 'hover:border-green-400',
                        'icon' => '🌍',
                        'themes' => ['Klimaat & Energie', 'Milieu & Natuur', 'Duurzaamheid', 'Landbouw']
                    ],
                    'Samenleving & Veiligheid' => [
                        'color' => 'from-red-500 to-pink-600',
                        'bgColor' => 'from-red-50 to-pink-50',
                        'textColor' => 'text-red-700',
                        'borderColor' => 'border-red-200',
                        'hoverColor' => 'hover:border-red-400',
                        'icon' => '🛡️',
                        'themes' => ['Veiligheid', 'Immigratie & Integratie', 'Justitie', 'Defensie', 'Discriminatie']
                    ],
                    'Wonen & Infrastructuur' => [
                        'color' => 'from-orange-500 to-amber-600',
                        'bgColor' => 'from-orange-50 to-amber-50',
                        'textColor' => 'text-orange-700',
                        'borderColor' => 'border-orange-200',
                        'hoverColor' => 'hover:border-orange-400',
                        'icon' => '🏠',
                        'themes' => ['Wonen', 'Verkeer & Transport', 'Infrastructuur', 'Lokaal Bestuur']
                    ],
                    'Digitaal & Maatschappij' => [
                        'color' => 'from-purple-500 to-violet-600',
                        'bgColor' => 'from-purple-50 to-violet-50',
                        'textColor' => 'text-purple-700',
                        'borderColor' => 'border-purple-200',
                        'hoverColor' => 'hover:border-purple-400',
                        'icon' => '📱',
                        'themes' => ['Digitalisering', 'Privacy & Data', 'Media', 'Onderwijs', 'Cultuur', 'Sport & Recreatie', 'Europa & EU', 'Mensenrechten']
                    ]
                ];
                ?>

                <!-- Theme Categories Grid -->
                <div class="space-y-12">
                    <?php foreach ($themeCategories as $categoryName => $categoryData): ?>
                    <div class="group">
                        <!-- Category Header -->
                        <div class="flex items-center justify-center mb-8">
                            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r <?= $categoryData['bgColor'] ?> 
                                       rounded-2xl border <?= $categoryData['borderColor'] ?> shadow-sm">
                                <span class="text-2xl mr-3"><?= $categoryData['icon'] ?></span>
                                <span class="font-bold <?= $categoryData['textColor'] ?> text-lg"><?= $categoryName ?></span>
                                <div class="ml-3 px-2 py-1 bg-white/60 rounded-full">
                                    <span class="text-xs font-medium <?= $categoryData['textColor'] ?>"><?= count($categoryData['themes']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Theme Cards Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <?php foreach ($categoryData['themes'] as $theme): ?>
                            <div class="group/card relative">
                                <!-- Hover glow effect -->
                                <div class="absolute inset-0 bg-gradient-to-br <?= $categoryData['color'] ?> rounded-2xl 
                                           opacity-0 group-hover/card:opacity-20 transition-all duration-500 blur-lg"></div>
                                
                                <!-- Main card -->
                                <div class="relative bg-white/90 backdrop-blur-sm border <?= $categoryData['borderColor'] ?> 
                                           rounded-2xl p-4 text-center shadow-lg <?= $categoryData['hoverColor'] ?> 
                                           hover:shadow-xl transform transition-all duration-300 
                                           group-hover/card:-translate-y-1 group-hover/card:scale-105">
                                    
                                    <!-- Theme icon (first letter styled) -->
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br <?= $categoryData['color'] ?> 
                                               rounded-xl flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-lg"><?= substr($theme, 0, 1) ?></span>
                                    </div>

                                    <!-- Theme name -->
                                    <span class="text-sm font-semibold text-gray-700 group-hover/card:<?= $categoryData['textColor'] ?> 
                                                transition-colors duration-300 leading-tight block">
                                        <?= $theme ?>
                                    </span>

                                    <!-- Hover indicator -->
                                    <div class="absolute top-2 right-2 w-2 h-2 bg-gradient-to-br <?= $categoryData['color'] ?> 
                                               rounded-full opacity-0 group-hover/card:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Enhanced Bottom Section -->
                <div class="mt-16 text-center">
                    <!-- Stats Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto mb-8">
                        <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl p-6 border border-primary/20">
                            <div class="text-3xl font-black text-primary mb-2">30</div>
                            <div class="text-sm font-medium text-gray-600">Actuele Thema's</div>
                        </div>
                        <div class="bg-gradient-to-br from-secondary/10 to-primary/10 rounded-2xl p-6 border border-secondary/20">
                            <div class="text-3xl font-black text-secondary mb-2">14</div>
                            <div class="text-sm font-medium text-gray-600">Politieke Partijen</div>
                        </div>
                        <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl p-6 border border-primary/20">
                            <div class="text-3xl font-black text-primary mb-2">2025</div>
                            <div class="text-sm font-medium text-gray-600">Verkiezingen</div>
                        </div>
                    </div>

                    <!-- Data source info -->
                    <div class="inline-flex items-center space-x-3 px-8 py-4 bg-gradient-to-r from-gray-50 to-gray-100 
                               rounded-2xl border border-gray-200 shadow-sm">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600 font-medium">
                            Alle thema's zijn gebaseerd op de <span class="font-semibold">verkiezingsprogramma's 2025</span> 
                            en <span class="font-semibold">recente partijstandpunten</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
function stemwijzer() {
    return {
        screen: 'start',
        currentStep: 0,
        totalSteps: <?= $totalQuestions ?: 25 ?>,
        showExplanation: false,
        showPartyPositions: false,
        selectedParty: null,
        
        // ChatGPT functionaliteit
        showAIExplanation: false,
        aiExplanationContent: '',
        loadingAIExplanation: false,
        aiAdviceContent: '',
        loadingAIAdvice: false,
        answers: {},
        eensParties: [],
        neutraalParties: [],
        oneensParties: [],
        
        results: {},
        finalResults: [],
        personalityAnalysis: {
            political_profile: {
                type: '',
                description: '',
                color: 'from-gray-500 to-slate-600'
            },
            personality_traits: [],
            compass_position: {
                x: 0,
                y: 0,
                quadrant: {
                    name: 'Centraal',
                    color: 'gray'
                }
            },
            left_right_percentage: 50,
            progressive_percentage: 50,
            authoritarian_percentage: 50,
            eu_pro_percentage: 50
        },
        showPartyDetails: false,
        detailedParty: null,
        showingQuestion: null,
        shareUrl: '',
        shareId: '',
        
        // Data wordt nu uit de database geladen
        questions: <?= json_encode($stemwijzerData['questions'] ?? [], JSON_INVALID_UTF8_SUBSTITUTE) ?>,
        partyLogos: <?= json_encode($stemwijzerData['partyLogos'] ?? [], JSON_INVALID_UTF8_SUBSTITUTE) ?>,
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
                
            // Calculate personality analysis
            this.calculatePersonalityAnalysis();
        },

        calculatePersonalityAnalysis() {
            const analysis = {
                progressive_conservative_score: 0,
                authoritarian_libertarian_score: 0,
                eu_skeptic_pro_score: 0,
                economic_left_right: 0,
                social_liberal_conservative: 0,
                total_answered: Object.keys(this.answers).length
            };

            // Counters voor elke categorie om juiste percentages te berekenen
            const categoryCounters = {
                economic_questions: 0,
                social_questions: 0,
                eu_questions: 0,
                progressive_questions: 0,
                authoritarian_questions: 0
            };

            // Categorieën van vragen voor verschillende assen (uitgebreid)
            const economicKeywords = ['belasting', 'uitkering', 'economie', 'subsidie', 'markt', 'inkomen', 'pensioen', 'loon', 'werk', 'baan', 'onderneming', 'bedrijf'];
            const socialKeywords = ['asiel', 'immigratie', 'integratie', 'criminaliteit', 'veiligheid', 'identiteit', 'cultuur', 'traditie'];
            const euKeywords = ['europa', 'eu', 'europese', 'brexit', 'soevereiniteit', 'brussel'];
            const progressiveKeywords = ['klimaat', 'milieu', 'duurzaam', 'innovatie', 'technologie', 'energie', 'natuur'];
            const authoritarianKeywords = ['veiligheid', 'privacy', 'surveillance', 'politie', 'straf', 'orde', 'autoriteit'];

            Object.keys(this.answers).forEach(questionIndex => {
                const question = this.questions[questionIndex];
                if (!question) return;
                
                const answer = this.answers[questionIndex];
                const questionText = ((question.title || '') + ' ' + (question.description || '')).toLowerCase();
                
                // Bepaal de waarde van het antwoord
                let answerValue = 0;
                if (answer === 'eens') answerValue = 1;
                else if (answer === 'oneens') answerValue = -1;

                // Check voor verschillende categorieën
                if (this.containsKeywords(questionText, economicKeywords)) {
                    analysis.economic_left_right += answerValue;
                    categoryCounters.economic_questions++;
                }
                if (this.containsKeywords(questionText, socialKeywords)) {
                    analysis.social_liberal_conservative += answerValue;
                    categoryCounters.social_questions++;
                }
                if (this.containsKeywords(questionText, euKeywords)) {
                    analysis.eu_skeptic_pro_score += answerValue;
                    categoryCounters.eu_questions++;
                }
                if (this.containsKeywords(questionText, progressiveKeywords)) {
                    analysis.progressive_conservative_score += answerValue;
                    categoryCounters.progressive_questions++;
                }
                if (this.containsKeywords(questionText, authoritarianKeywords)) {
                    analysis.authoritarian_libertarian_score += answerValue;
                    categoryCounters.authoritarian_questions++;
                }
            });

            // Normaliseer scores naar percentages gebaseerd op het juiste aantal vragen per categorie
            analysis.economic_right_percentage = categoryCounters.economic_questions > 0 ? 
                ((analysis.economic_left_right / categoryCounters.economic_questions) + 1) * 50 : 50;
                
            analysis.social_conservative_percentage = categoryCounters.social_questions > 0 ? 
                ((analysis.social_liberal_conservative / categoryCounters.social_questions) + 1) * 50 : 50;
                
            analysis.progressive_percentage = categoryCounters.progressive_questions > 0 ? 
                ((analysis.progressive_conservative_score / categoryCounters.progressive_questions) + 1) * 50 : 50;
                
            analysis.authoritarian_percentage = categoryCounters.authoritarian_questions > 0 ? 
                ((analysis.authoritarian_libertarian_score / categoryCounters.authoritarian_questions) + 1) * 50 : 50;
                
            analysis.eu_pro_percentage = categoryCounters.eu_questions > 0 ? 
                ((analysis.eu_skeptic_pro_score / categoryCounters.eu_questions) + 1) * 50 : 50;

            // Voor backwards compatibility - gebruik economische score als basis voor algemene left_right
            analysis.left_right_percentage = analysis.economic_right_percentage;

            // Bepaal politiek profiel
            analysis.political_profile = this.determinePoliticalProfile(analysis);
            analysis.personality_traits = this.determinePoliticalTraits(analysis);
            analysis.compass_position = this.determineCompassPosition(analysis);

            this.personalityAnalysis = analysis;
        },

        containsKeywords(text, keywords) {
            return keywords.some(keyword => text.includes(keyword));
        },

        determinePoliticalProfile(analysis) {
            const leftRight = analysis.economic_right_percentage || 50;
            const progressive = analysis.progressive_percentage || 50;
            
            if (leftRight < 35 && progressive > 65) {
                return {
                    type: 'Progressief Links',
                    description: 'Je hebt vooruitstrevende ideeën en gelooft in sociale gelijkheid en verandering.',
                    color: 'from-green-500 to-blue-500'
                };
            } else if (leftRight < 35 && progressive < 35) {
                return {
                    type: 'Traditioneel Links',
                    description: 'Je combineert linkse economische ideeën met meer traditionele sociale waarden.',
                    color: 'from-red-500 to-orange-500'
                };
            } else if (leftRight > 65 && progressive > 65) {
                return {
                    type: 'Progressief Rechts',
                    description: 'Je bent economisch liberaal maar sociaal vooruitstrevend.',
                    color: 'from-blue-500 to-purple-500'
                };
            } else if (leftRight > 65 && progressive < 35) {
                return {
                    type: 'Conservatief Rechts',
                    description: 'Je hebt traditionele waarden en gelooft in vrije markteconomie.',
                    color: 'from-blue-600 to-indigo-600'
                };
            } else {
                return {
                    type: 'Politiek Centraal',
                    description: 'Je hebt een gematigde politieke houding met elementen van verschillende kanten.',
                    color: 'from-gray-500 to-slate-600'
                };
            }
        },

        determinePoliticalTraits(analysis) {
            const traits = [];
            
            const leftRight = analysis.economic_right_percentage || 50;
            const progressive = analysis.progressive_percentage || 50;
            const authoritarian = analysis.authoritarian_percentage || 50;
            const euPro = analysis.eu_pro_percentage || 50;

            if (leftRight < 30) {
                traits.push({name: 'Sterk Sociaal Bewust', icon: '❤️', description: 'Gelijkheid en solidariteit staan centraal'});
            } else if (leftRight > 70) {
                traits.push({name: 'Economisch Liberal', icon: '💼', description: 'Gelooft in vrije markt en ondernemerschap'});
            }

            if (progressive > 70) {
                traits.push({name: 'Vooruitstrevend', icon: '🚀', description: 'Omarmt verandering en innovatie'});
            } else if (progressive < 30) {
                traits.push({name: 'Traditioneel', icon: '🏛️', description: 'Waardeert bewezen systemen en tradities'});
            }

            if (authoritarian > 70) {
                traits.push({name: 'Veiligheid Georiënteerd', icon: '🛡️', description: 'Prioriteit aan orde en veiligheid'});
            } else if (authoritarian < 30) {
                traits.push({name: 'Vrijheidsliefhebber', icon: '🕊️', description: 'Individualiteit en persoonlijke vrijheid belangrijk'});
            }

            if (euPro > 70) {
                traits.push({name: 'Europees Minded', icon: '🇪🇺', description: 'Steunt Europese samenwerking'});
            } else if (euPro < 30) {
                traits.push({name: 'Soevereiniteitsvoorkeur', icon: '🏴', description: 'Nationale autonomie is belangrijk'});
            }

            if (Math.abs(leftRight - 50) < 15 && Math.abs(progressive - 50) < 15) {
                traits.push({name: 'Pragmatisch', icon: '⚖️', description: 'Zoekt balans tussen verschillende standpunten'});
            }

            return traits;
        },

        determineCompassPosition(analysis) {
            const economic = (analysis.economic_right_percentage || 50) - 50;
            const social = (analysis.social_conservative_percentage || 50) - 50;
            
            return {
                x: economic,
                y: social,
                quadrant: this.getQuadrant(economic, social)
            };
        },

        getQuadrant(x, y) {
            if (x > 0 && y < 0) {
                return {name: 'Rechts-Liberaal', color: 'blue'};
            } else if (x > 0 && y > 0) {
                return {name: 'Rechts-Autoritair', color: 'indigo'};
            } else if (x < 0 && y > 0) {
                return {name: 'Links-Autoritair', color: 'red'};
            } else {
                return {name: 'Links-Liberaal', color: 'green'};
            }
        },
        
        async saveResultsToDatabase() {
            try {
                const sessionId = this.generateSessionId();
                const payload = {
                    sessionId: sessionId,
                    answers: this.answers,
                    results: this.results
                };
                
                console.log('Probeer resultaten op te slaan:', {
                    sessionId: sessionId,
                    answersCount: Object.keys(this.answers).length,
                    resultsCount: Object.keys(this.results).length
                });
                
                const response = await fetch('/api/stemwijzer.php?action=save-results', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();
                console.log('API Response:', result);
                
                if (result.success) {
                    console.log('✅ Resultaten opgeslagen!');
                    console.log('Debug info:', result.debug);
                    
                    // Bewaar de share URL voor later gebruik
                    this.shareUrl = result.share_url;
                    this.shareId = result.share_id;
                    
                    // Haal uitgebreide persoonlijkheidsanalyse op van de backend
                    await this.loadPersonalityAnalysisFromBackend(result.share_id);
                    
                    console.log('🔗 Share URL:', this.shareUrl);
                } else {
                    console.warn('❌ Fout bij opslaan:', result.error);
                    console.warn('Debug info:', result.debug);  
                }
            } catch (error) {
                console.error('❌ Netwerk fout bij opslaan van resultaten:', error);
            }
        },
        
        async loadPersonalityAnalysisFromBackend(shareId) {
            try {
                console.log('🧠 Laden persoonlijkheidsanalyse van backend...');
                
                const response = await fetch(`/api/stemwijzer.php?action=personality-analysis&share_id=${shareId}`);
                const result = await response.json();
                
                if (result.success && result.personality_analysis) {
                    console.log('✅ Persoonlijkheidsanalyse geladen van backend:', result.personality_analysis);
                    this.personalityAnalysis = result.personality_analysis;
                } else {
                    console.warn('❌ Kon persoonlijkheidsanalyse niet laden van backend, gebruik client-side versie');
                    // Fallback naar client-side berekening
                    this.calculatePersonalityAnalysis();
                }
            } catch (error) {
                console.error('❌ Fout bij laden persoonlijkheidsanalyse van backend:', error);
                // Fallback naar client-side berekening
                this.calculatePersonalityAnalysis();
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
            
            // Restore body scrolling (handle both inline styles and CSS classes)
            document.body.style.overflow = '';
            document.body.classList.remove('overflow-hidden');
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

        copyShareUrl() {
            if (!this.shareUrl) {
                this.showNotification('Geen share link beschikbaar', 'error');
                return;
            }
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(this.shareUrl).then(() => {
                    this.showNotification('Share link gekopieerd naar klembord!', 'success');
                }).catch(() => {
                    this.fallbackCopyShareUrl();
                });
            } else {
                this.fallbackCopyShareUrl();
            }
        },

        fallbackCopyShareUrl() {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = this.shareUrl;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            
            try {
                document.execCommand('copy');
                this.showNotification('Share link gekopieerd naar klembord!', 'success');
            } catch (err) {
                this.showNotification('Kon share link niet kopiëren', 'error');
            }
            
            document.body.removeChild(textArea);
        },

        shareResultsViaLink() {
            if (!this.shareUrl) {
                this.showNotification('Geen share link beschikbaar', 'error');
                return;
            }
            
            const topThree = this.finalResults.slice(0, 3);
            const text = `Bekijk mijn Stemwijzer 2025 resultaten:\n\n` +
                `🥇 ${topThree[0]?.name}: ${topThree[0]?.agreement}%\n` +
                `🥈 ${topThree[1]?.name}: ${topThree[1]?.agreement}%\n` +
                `🥉 ${topThree[2]?.name}: ${topThree[2]?.agreement}%\n\n` +
                `Link: ${this.shareUrl}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Mijn Stemwijzer 2025 Resultaten',
                    text: text,
                    url: this.shareUrl
                }).catch(err => {
                    console.log('Error sharing:', err);
                    this.fallbackShare(text);
                });
            } else {
                this.fallbackShare(text);
            }
        },

        openShareUrl() {
            if (!this.shareUrl) {
                this.showNotification('Geen share link beschikbaar', 'error');
                return;
            }
            
            window.open(this.shareUrl, '_blank');
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
        
        // ChatGPT functionaliteit
        async loadAIExplanation() {
            if (this.loadingAIExplanation) return;
            
            this.loadingAIExplanation = true;
            this.aiExplanationContent = '';
            
            try {
                const response = await fetch('/api/stemwijzer.php?action=explain-question', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        questionIndex: this.currentStep
                    })
                });
                
                const result = await response.json();
                
                if (result.success && result.explanation.success) {
                    this.aiExplanationContent = result.explanation.content;
                    this.showAIExplanation = true;
                } else {
                    this.showNotification('Kon AI uitleg niet laden: ' + (result.explanation.error || result.error), 'error');
                }
            } catch (error) {
                console.error('Fout bij laden AI uitleg:', error);
                this.showNotification('Er ging iets mis bij het laden van de AI uitleg', 'error');
            } finally {
                this.loadingAIExplanation = false;
            }
        },
        
        async loadPartyMatchExplanation(party) {
            if (!party || this.loadingAIExplanation) return;
            
            this.loadingAIExplanation = true;
            
            try {
                const response = await fetch('/api/stemwijzer.php?action=explain-match', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        partyName: party.name,
                        userAnswers: this.answers,
                        matchPercentage: party.agreement
                    })
                });
                
                const result = await response.json();
                
                if (result.success && result.explanation.success) {
                    // Voeg AI uitleg toe aan de partij details
                    party.aiExplanation = result.explanation.content;
                    // Update ook de detailedParty als deze gelijk is aan de huidige party
                    if (this.detailedParty && this.detailedParty.name === party.name) {
                        this.detailedParty.aiExplanation = result.explanation.content;
                    }
                } else {
                    this.showNotification('Kon partij uitleg niet laden: ' + (result.explanation.error || result.error), 'error');
                }
            } catch (error) {
                console.error('Fout bij laden partij uitleg:', error);
                this.showNotification('Er ging iets mis bij het laden van de partij uitleg', 'error');
            } finally {
                this.loadingAIExplanation = false;
            }
        },
        
        async loadPoliticalAdvice() {
            if (this.loadingAIAdvice) return;
            
            this.loadingAIAdvice = true;
            this.aiAdviceContent = '';
            
            try {
                const response = await fetch('/api/stemwijzer.php?action=political-advice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        topMatches: this.finalResults.slice(0, 3),
                        userAnswers: this.answers
                    })
                });
                
                const result = await response.json();
                
                if (result.success && result.advice.success) {
                    this.aiAdviceContent = result.advice.content;
                    
                    // Auto-scroll naar AI advice sectie nadat content is geladen
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const aiAdviceSection = document.getElementById('ai-advice-section');
                            if (aiAdviceSection) {
                                aiAdviceSection.scrollIntoView({ 
                                    behavior: 'smooth', 
                                    block: 'center',
                                    inline: 'nearest'
                                });
                                
                                // Voeg een subtiele highlight effect toe
                                aiAdviceSection.classList.add('ring-4', 'ring-purple-200', 'ring-opacity-50');
                                setTimeout(() => {
                                    aiAdviceSection.classList.remove('ring-4', 'ring-purple-200', 'ring-opacity-50');
                                }, 2000);
                            }
                        }, 300); // Kleine vertraging zodat de content zichtbaar is
                    });
                } else {
                    this.showNotification('Kon AI advies niet laden: ' + (result.advice.error || result.error), 'error');
                }
            } catch (error) {
                console.error('Fout bij laden AI advies:', error);
                this.showNotification('Er ging iets mis bij het laden van het AI advies', 'error');
            } finally {
                this.loadingAIAdvice = false;
            }
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