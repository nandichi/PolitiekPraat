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
require_once BASE_PATH . '/includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Meta informatie
$title = "Doneer aan PolitiekPraat";
$description = "Steun PolitiekPraat met een donatie. Help ons de website gratis, advertentievrij en onafhankelijk te houden. Elke bijdrage helpt ons verder ontwikkelen.";

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Modern Hero Section - Matching website styling -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 md:py-24 lg:py-32 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 md:w-64 md:h-64 lg:w-80 lg:h-80 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-16 left-8 w-20 h-20 md:w-32 md:h-32 bg-gradient-to-br from-primary/30 to-secondary/30 rounded-3xl rotate-45 animate-bounce hidden sm:block" style="animation-duration: 6s;"></div>
        <div class="absolute top-1/3 right-8 w-16 h-16 md:w-24 md:h-24 bg-gradient-to-tl from-secondary/25 to-primary/25 rounded-2xl rotate-12 animate-bounce hidden md:block" style="animation-duration: 8s; animation-delay: 2s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                
                <!-- Header badge -->
                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Onafhankelijk & advertentievrij</span>
                    </div>
                </div>
                
                <!-- Main Heading -->
                <div class="space-y-4 mb-8">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                        Steun
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            PolitiekPraat
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 font-medium">
                        Help PolitiekPraat onafhankelijke politieke informatie toegankelijk te houden
                    </p>
                </div>
                
                <!-- Description -->
                <p class="text-base md:text-lg text-blue-200 max-w-3xl mx-auto leading-relaxed mb-12">
                    Met jouw steun kunnen we PolitiekPraat gratis, advertentievrij en volledig onafhankelijk houden. 
                    Elke donatie helpt PolitiekPraat de vaste kosten te dekken en nieuwe tools te ontwikkelen.
                </p>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2">100%</div>
                        <div class="text-blue-200 text-sm md:text-base">Onafhankelijk</div>
                        <div class="text-blue-300 text-xs mt-1">Geen sponsors of partijen</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2">0€</div>
                        <div class="text-blue-200 text-sm md:text-base">Advertenties</div>
                        <div class="text-blue-300 text-xs mt-1">Altijd advertentievrij</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2">24/7</div>
                        <div class="text-blue-200 text-sm md:text-base">Toegankelijk</div>
                        <div class="text-blue-300 text-xs mt-1">Gratis voor iedereen</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Donation Content -->
    <section class="py-16 md:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                
                <!-- About Section -->
                <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-16 relative overflow-hidden">
                    <!-- Decorative accent -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-secondary to-primary"></div>
                    
                    <!-- Floating decoration -->
                    <div class="absolute top-8 right-8 w-24 h-24 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-2xl rotate-12 hidden lg:block"></div>
                    
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                    Waarom PolitiekPraat?
                                </span>
                            </h2>
                            
                            <div class="space-y-6 text-gray-700 text-lg leading-relaxed">
                                <p>
                                    PolitiekPraat is mijn eigen hobbyproject, ontstaan uit een liefde voor politiek en een drang om het debat toegankelijker te maken. Alles wat je op deze site vindt, van blogs tot tools als de PartijMeter, bouw, schrijf en onderhoud ik in mijn vrije tijd.
                                </p>
                                
                                <p>
                                    Ik doe dit puur uit passie, zonder inkomsten, samenwerkingen of sponsoring van partijen, bedrijven of instellingen. Je vindt hier dus geen verborgen agenda's, alleen mijn inzet voor onafhankelijke en eerlijke politieke informatie.
                                </p>
                                
                                <p class="font-semibold text-primary">
                                    Een donatie is voor mij geen verdienmodel, maar een steuntje in de rug om de vaste kosten te dekken en de site verder te ontwikkelen. Zo kan ik PolitiekPraat gratis, advertentievrij en onafhankelijk houden.
                                </p>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <!-- Central Icon -->
                            <div class="flex justify-center">
                                <div class="relative">
                                    <div class="w-32 h-32 md:w-40 md:h-40 bg-gradient-to-br from-primary to-secondary rounded-3xl flex items-center justify-center shadow-2xl animate-pulse">
                                        <svg class="w-16 h-16 md:w-20 md:h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Floating elements around the icon -->
                                    <div class="absolute -top-4 -left-4 w-8 h-8 bg-primary/20 rounded-xl rotate-45 animate-bounce" style="animation-delay: 0.5s;"></div>
                                    <div class="absolute -top-2 -right-6 w-6 h-6 bg-secondary/30 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
                                    <div class="absolute -bottom-6 -left-2 w-10 h-4 bg-primary/15 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
                                    <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-secondary/20 rounded-lg rotate-12 animate-bounce" style="animation-delay: 2s;"></div>
                                </div>
                            </div>
                            
                            <!-- Text below icon -->
                            <div class="text-center mt-8">
                                <p class="text-xl font-bold text-gray-900 mb-2">Gemaakt met passie</p>
                                <p class="text-gray-600">Voor een betere democratie</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donation Button Section -->
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl shadow-xl p-8 md:p-12 relative overflow-hidden">
                    <!-- Background pattern -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"20\" cy=\"20\" r=\"1\" fill=\"%23000\"/%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
                    
                    <div class="relative z-10 text-center">
                        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                Steun met een kop koffie
                            </span>
                        </h3>
                        
                        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                            Vind je het waardevol wat ik doe? Dan help je met een kop koffie enorm. Dankjewel voor je support!
                        </p>
                        
                        <!-- Buy Me a Coffee Button -->
                        <div class="flex justify-center">
                            <a href="https://buymeacoffee.com/politiekpraat" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="group relative inline-flex items-center justify-center px-8 py-4 md:px-12 md:py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-lg md:text-xl rounded-2xl shadow-2xl hover:shadow-primary/40 hover:-translate-y-2 transition-all duration-300 overflow-hidden transform hover:scale-105">
                                
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                
                                <!-- Coffee Icon -->
                                <svg class="w-6 h-6 md:w-8 md:h-8 mr-3 md:mr-4 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h20v-2H2v2zm4.15-8.65L7 14h10l.85-1.65c.2-.39.2-.86 0-1.24L17 8H7l-.85 3.11c-.2.38-.2.85 0 1.24zM6 7h12v-.5c0-.83-.67-1.5-1.5-1.5h-9C6.67 5 6 5.67 6 6.5V7z"/>
                                    <path d="M15.5 9.5c0 .83-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5.67-1.5 1.5-1.5 1.5.67 1.5 1.5zm-3 0c0 .83-.67 1.5-1.5 1.5S9.5 10.33 9.5 9.5 10.17 8 11 8s1.5.67 1.5 1.5z"/>
                                </svg>
                                
                                <span class="relative z-10">Doneer een kop koffie</span>
                                
                                <!-- Arrow -->
                                <svg class="w-5 h-5 md:w-6 md:h-6 ml-3 md:ml-4 relative z-10 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Additional info -->
                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Veilig via Buy Me a Coffee
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Geen accountregistratie vereist
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Impact Section -->
                <div class="mt-16 grid md:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Serverkosten</h4>
                        <p class="text-gray-600">Hosting, database en alle technische infrastructuur om de site online te houden</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Nieuwe Features</h4>
                        <p class="text-gray-600">Ontwikkeling van nieuwe tools zoals de PartijMeter en verbeteringen aan bestaande functies</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Meer Tijd</h4>
                        <p class="text-gray-600">Meer tijd om content te schrijven, nieuwsupdates te delen en de community te ondersteunen</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.float-animation {
    animation: float 6s ease-in-out infinite;
}

/* Enhanced hover effects */
.group:hover .group-hover\:translate-x-full {
    transform: translateX(100%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .animate-bounce {
        animation: none; /* Disable on mobile for performance */
    }
}

/* Pulsing animation for badges */
@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}
</style>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
