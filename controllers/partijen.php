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

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Party information
require_once __DIR__ . '/../models/PartyModel.php';

// Krijg alle partijen uit de database
$partyModel = new PartyModel();
$parties = $partyModel->getAllParties();

// Include the header
$title = "Politieke Partijen Overzicht";
$description = "Een overzicht van alle Nederlandse politieke partijen, hun standpunten en lijsttrekkers";
include_once BASE_PATH . '/views/templates/header.php';
?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 md:py-24 lg:py-32 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Floating Geometric Shapes - Responsive -->
        <div class="absolute top-8 left-4 md:top-16 md:left-8 lg:top-20 lg:left-12 w-16 h-16 md:w-24 md:h-24 lg:w-32 lg:h-32 bg-gradient-to-br from-primary/30 to-secondary/30 rounded-3xl rotate-45 animate-bounce hidden sm:block" style="animation-duration: 6s; animation-delay: 0s;"></div>
        <div class="absolute top-1/3 right-4 md:right-8 lg:right-16 w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-gradient-to-tl from-secondary/25 to-primary/25 rounded-2xl rotate-12 animate-bounce hidden md:block" style="animation-duration: 8s; animation-delay: 2s;"></div>
        <div class="absolute bottom-16 left-1/4 w-8 h-8 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-full animate-bounce hidden lg:block" style="animation-duration: 7s; animation-delay: 4s;"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 md:w-64 md:h-64 lg:w-80 lg:h-80 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    
                    <!-- Left Column - Main Content -->
                    <div class="text-center lg:text-left space-y-6 lg:space-y-8 order-1 lg:order-1">
                        
                        <!-- Header badge -->
                        <div class="flex justify-center lg:justify-start mb-6">
                            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                                <span class="text-white/90 text-sm font-medium">Live politieke data</span>
                            </div>
                        </div>
                        
                        <!-- Main Heading -->
                        <div class="space-y-2 md:space-y-4">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 md:mb-6 tracking-tight leading-tight">
                                Nederlandse
                                <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                                    Politieke Partijen
                                </span>
                            </h1>
                            
                            <!-- Typing Animation Text -->
                            <div class="text-base md:text-lg lg:text-xl text-blue-100 font-medium min-h-[1.5em] md:min-h-[2em]">
                                <span id="typing-text" class="border-r-2 border-blue-300 animate-pulse"></span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-base md:text-lg lg:text-xl text-blue-100 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Ontdek alle standpunten, lijsttrekkers en actuele peilingen van alle Nederlandse politieke partijen
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                            <a href="<?= URLROOT ?>/stemwijzer" 
                               class="group relative inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold rounded-2xl shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <svg class="w-5 h-5 mr-2 md:mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span class="relative z-10 text-sm md:text-base">Stemwijzer 2025</span>
                            </a>
                            
                            <a href="#partijen" 
                               class="group inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-white/10 backdrop-blur-lg text-white font-semibold rounded-2xl border border-white/20 hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 md:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm md:text-base">Bekijk partijen</span>
                            </a>
                            
                            <a href="#coalitiemaker" 
                               class="group inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-white/10 backdrop-blur-lg text-white font-semibold rounded-2xl border border-white/20 hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 md:mr-3 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm md:text-base">Coalitiemaker</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column - Political Leaders Showcase -->
                    <div class="relative order-2 lg:order-2 mb-8 lg:mb-0">
                        <!-- Central Decorative Element -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full blur-3xl animate-pulse"></div>
                        </div>
                        
                        <!-- Political Leaders Dashboard -->
                        <div class="relative max-w-lg mx-auto">
                            <!-- Dashboard Container -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 md:p-8 border border-white/20 shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-1">
                                <!-- Header -->
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-light/80 to-secondary/80 rounded-2xl mb-4 shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Politieke Leiders</h3>
                                    <p class="text-blue-200 text-sm">Ontmoet de gezichten van de politiek</p>
                                </div>
                                
                                <!-- Featured Leaders Carousel -->
                                <div class="space-y-4 mb-6">
                                    <div id="leaders-carousel" class="relative">
                                        <!-- Leader Cards -->
                                        <?php 
                                        $featuredLeaders = ['PVV', 'VVD', 'GL-PvdA', 'NSC', 'BBB'];
                                        foreach ($featuredLeaders as $index => $partyKey): 
                                            $party = $parties[$partyKey];
                                            $isFirst = $index === 0;
                                        ?>
                                        <div class="leader-card <?php echo $isFirst ? 'active' : 'hidden'; ?> bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10 transition-all duration-500">
                                            <div class="flex items-center space-x-4">
                                                <div class="relative">
                                                    <div class="w-16 h-16 rounded-full overflow-hidden border-3 border-white/20 shadow-lg">
                                                        <img src="<?php echo $party['leader_photo']; ?>" 
                                                             alt="<?php echo $party['leader']; ?>" 
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-sm" 
                                                         style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-white font-bold text-sm truncate"><?php echo $party['leader']; ?></h4>
                                                    <p class="text-blue-200 text-xs mb-1"><?php echo $partyKey; ?> - <?php echo $party['current_seats']; ?> zetels</p>
                                                    <div class="w-full bg-white/10 rounded-full h-1.5">
                                                        <div class="h-1.5 rounded-full transition-all duration-1000" 
                                                             style="width: <?php echo ($party['current_seats'] / 37) * 100; ?>%; background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Carousel Navigation -->
                                    <div class="flex justify-center space-x-2 mt-4">
                                        <?php foreach ($featuredLeaders as $index => $partyKey): ?>
                                        <button class="carousel-dot w-2 h-2 rounded-full transition-all duration-300 <?php echo $index === 0 ? 'bg-white' : 'bg-white/30'; ?>" 
                                                data-index="<?php echo $index; ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <!-- Quick Stats -->
                                <div class="grid grid-cols-3 gap-3 mb-6">
                                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-white"><?php echo count($parties); ?></div>
                                        <div class="text-xs text-blue-200">Partijen</div>
                                    </div>
                                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-white">150</div>
                                        <div class="text-xs text-blue-200">Zetels</div>
                                    </div>
                                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-white">2025</div>
                                        <div class="text-xs text-blue-200">Verkiezingen</div>
                                    </div>
                                </div>
                                
                                <!-- Zetel Verdeling -->
                                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-white font-bold text-sm">Grootste Partijen</h4>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                            <span class="text-blue-400 text-xs font-medium">ACTUEEL</span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3" id="seat-distribution">
                                        <!-- PVV -->
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 rounded-full" style="background-color: #1e3a8a;"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-white text-xs font-medium">PVV</span>
                                                    <span class="text-blue-200 text-xs">37 zetels</span>
                                                </div>
                                                <div class="w-full bg-white/10 rounded-full h-1">
                                                    <div class="h-1 rounded-full transition-all duration-1000" 
                                                         style="width: 24.7%; background-color: #1e3a8a;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- VVD -->
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 rounded-full" style="background-color: #1e40af;"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-white text-xs font-medium">VVD</span>
                                                    <span class="text-blue-200 text-xs">24 zetels</span>
                                                </div>
                                                <div class="w-full bg-white/10 rounded-full h-1">
                                                    <div class="h-1 rounded-full transition-all duration-1000" 
                                                         style="width: 16%; background-color: #1e40af;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- GL-PvdA -->
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 rounded-full" style="background-color: #059669;"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-white text-xs font-medium">GL-PvdA</span>
                                                    <span class="text-blue-200 text-xs">25 zetels</span>
                                                </div>
                                                <div class="w-full bg-white/10 rounded-full h-1">
                                                    <div class="h-1 rounded-full transition-all duration-1000" 
                                                         style="width: 16.7%; background-color: #059669;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- NSC -->
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 rounded-full" style="background-color: #7c3aed;"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-white text-xs font-medium">NSC</span>
                                                    <span class="text-blue-200 text-xs">20 zetels</span>
                                                </div>
                                                <div class="w-full bg-white/10 rounded-full h-1">
                                                    <div class="h-1 rounded-full transition-all duration-1000" 
                                                         style="width: 13.3%; background-color: #7c3aed;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="pt-6 border-t border-white/10">
                                    <div class="flex items-center justify-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-secondary-light/80 to-secondary/80 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-white font-semibold text-sm">Live Updates</p>
                                            <p class="text-blue-200 text-xs">Realtime politieke data</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade transition -->
        <div class="absolute bottom-0 left-0 right-0 h-24 md:h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>
    
    <!-- Enhanced Scripts for Political Dashboard -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Typing Animation
        const typingElement = document.getElementById('typing-text');
        const texts = [
            'Waar democratie vorm krijgt...',
            'Waar standpunten botsen...',
            'Waar leiders inspireren...',
            'Waar jouw stem telt...'
        ];
        
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;
        
        function typeText() {
            const currentText = texts[textIndex];
            
            if (isDeleting) {
                typingElement.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                typingSpeed = 50;
            } else {
                typingElement.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                typingSpeed = 100;
            }
            
            if (!isDeleting && charIndex === currentText.length) {
                typingSpeed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typingSpeed = 500;
            }
            
            setTimeout(typeText, typingSpeed);
        }
        
        typeText();
        
        // Leaders Carousel
        const leaderCards = document.querySelectorAll('.leader-card');
        const carouselDots = document.querySelectorAll('.carousel-dot');
        let currentLeader = 0;
        
        function showLeader(index) {
            leaderCards.forEach((card, i) => {
                if (i === index) {
                    card.classList.remove('hidden');
                    card.classList.add('active');
                } else {
                    card.classList.add('hidden');
                    card.classList.remove('active');
                }
            });
            
            carouselDots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('bg-white/30');
                    dot.classList.add('bg-white');
                } else {
                    dot.classList.add('bg-white/30');
                    dot.classList.remove('bg-white');
                }
            });
        }
        
        // Auto-rotate carousel
        setInterval(() => {
            currentLeader = (currentLeader + 1) % leaderCards.length;
            showLeader(currentLeader);
        }, 4000);
        
        // Manual carousel control
        carouselDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentLeader = index;
                showLeader(currentLeader);
            });
        });
        
        // Seat Distribution Animation
        const seatBars = document.querySelectorAll('#seat-distribution .h-1');
        
        // Animate progress bars on load
        setTimeout(() => {
            seatBars.forEach((bar, index) => {
                bar.style.transform = 'scaleX(0)';
                bar.style.transformOrigin = 'left';
                setTimeout(() => {
                    bar.style.transform = 'scaleX(1)';
                }, index * 200);
            });
        }, 1000);
        
        // Subtle pulse animation for the bars
        setInterval(() => {
            seatBars.forEach((bar, index) => {
                setTimeout(() => {
                    bar.style.transform = 'scaleX(1.02)';
                    setTimeout(() => {
                        bar.style.transform = 'scaleX(1)';
                    }, 200);
                }, index * 100);
            });
        }, 8000); // Every 8 seconds
    });
    </script>

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
document.addEventListener('DOMContentLoaded', function() {
    // Party data from PHP
    const partyData = <?php echo json_encode($parties); ?>;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            // Replace with a fallback image if loading fails
            this.src = 'https://i.ibb.co/kXL6rQ8/placeholder-profile.jpg';
            this.onerror = null; // Prevent infinite loop
        };
    });
    
    // Party buttons - navigate to detail page
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            window.location.href = `<?php echo URLROOT; ?>/partijen/${partyKey}`;
        });
    });
    

    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
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
    
    // Enhanced Sorting functionality
    document.getElementById('sortOption').addEventListener('change', function() {
        const sortMethod = this.value;
        const partyCards = Array.from(document.querySelectorAll('.party-card'));
        const partyGrid = document.getElementById('parties-grid');
        
        // Add loading animation
        partyGrid.style.opacity = '0.5';
        partyGrid.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
            // Sort the cards
            partyCards.sort((a, b) => {
                const aPartyKey = a.querySelector('.party-btn').getAttribute('data-party');
                const bPartyKey = b.querySelector('.party-btn').getAttribute('data-party');
                
                if (sortMethod === 'name') {
                    return aPartyKey.localeCompare(bPartyKey);
                } else if (sortMethod === 'seats') {
                    return partyData[bPartyKey].current_seats - partyData[aPartyKey].current_seats;
                } else if (sortMethod === 'polling') {
                    return partyData[bPartyKey].polling.seats - partyData[aPartyKey].polling.seats;
                }
                
                return 0;
            });
            
            // Remove existing cards
            partyCards.forEach(card => card.remove());
            
            // Add the sorted cards back with staggered animation
            partyCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                partyGrid.appendChild(card);
                
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            // Restore grid
            partyGrid.style.transition = 'all 0.3s ease';
            partyGrid.style.opacity = '1';
            partyGrid.style.transform = 'scale(1)';
        }, 150);
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const partyCounter = document.getElementById('party-counter');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const partyCards = document.querySelectorAll('.party-card');
            let visibleCount = 0;
            
            partyCards.forEach(card => {
                const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
                const party = partyData[partyKey];
                const searchText = `${partyKey} ${party.name} ${party.leader}`.toLowerCase();
                
                if (searchText.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update counter
            partyCounter.textContent = visibleCount;
        });
    }
    
    // View toggle functionality (Grid/List)
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const partiesGrid = document.getElementById('parties-grid');
    
    if (gridViewBtn && listViewBtn) {
        listViewBtn.addEventListener('click', function() {
            // Switch to list view
            partiesGrid.className = 'space-y-3 mb-12';
            
            // Update button states
            gridViewBtn.classList.remove('bg-primary', 'text-white');
            gridViewBtn.classList.add('text-gray-600');
            listViewBtn.classList.add('bg-primary', 'text-white');
            listViewBtn.classList.remove('text-gray-600');
            
            // Transform cards for list view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToListView(card);
            });
        });
        
        gridViewBtn.addEventListener('click', function() {
            // Switch to grid view
            partiesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12';
            
            // Update button states
            listViewBtn.classList.remove('bg-primary', 'text-white');
            listViewBtn.classList.add('text-gray-600');
            gridViewBtn.classList.add('bg-primary', 'text-white');
            gridViewBtn.classList.remove('text-gray-600');
            
            // Transform cards back to grid view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToGridView(card);
            });
        });
    }
    
    // Enhanced list view transformation
    function transformToListView(card) {
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        const color = getPartyColor(partyKey);
        const changeValue = party.polling.change;
        
                 // Create horizontal list layout with better alignment
         card.innerHTML = `
             <div class="flex items-center p-6 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                 <!-- Left Section: Party Identity (Fixed width) -->
                 <div class="flex items-center space-x-4 w-80">
                     <!-- Logo & Party Info -->
                     <div class="flex items-center space-x-4">
                         <div class="relative flex-shrink-0">
                             <div class="w-14 h-14 rounded-xl bg-white shadow-md border-2 border-gray-100 flex items-center justify-center overflow-hidden">
                                 <img src="${party.logo}" alt="${party.name} logo" class="w-10 h-10 object-contain">
                             </div>
                             <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: ${color}"></div>
                         </div>
                         <div class="min-w-0">
                             <h3 class="text-lg font-bold text-gray-900">${partyKey}</h3>
                         </div>
                     </div>
                     
                     <!-- Leader Info -->
                     <div class="flex items-center space-x-3 pl-6 border-l border-gray-200">
                         <div class="w-10 h-10 rounded-full overflow-hidden border-2 shadow-sm flex-shrink-0" style="border-color: ${color}">
                             <img src="${party.leader_photo}" alt="${party.leader}" class="w-full h-full object-cover">
                         </div>
                         <div class="min-w-0">
                             <p class="text-sm font-semibold text-gray-800 truncate">${party.leader}</p>
                             <p class="text-xs text-gray-500">Partijleider</p>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Center Section: Key Stats (Fixed widths for alignment) -->
                 <div class="flex items-center space-x-12 flex-1 justify-center">
                     <!-- Current Seats -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold text-gray-900">${party.current_seats}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Huidige zetels</div>
                     </div>
                     
                     <!-- Polling -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold" style="color: ${color}">${parseInt(party.polling?.seats) || 0}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Peilingen</div>
                     </div>
                     
                     <!-- Trend -->
                     <div class="text-center w-28">
                         <div class="flex items-center justify-center mb-1">
                             ${changeValue > 0 ? 
                                 `<div class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-200 text-sm font-bold">📈 +${changeValue}</div>` :
                                 changeValue < 0 ? 
                                 `<div class="bg-red-100 text-red-700 px-2 py-1 rounded-lg border border-red-200 text-sm font-bold">📉 ${changeValue}</div>` :
                                 `<div class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg border border-blue-200 text-sm font-bold">➡️ 0</div>`
                             }
                         </div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Trend</div>
                     </div>
                 </div>
                 
                 <!-- Right Section: Actions -->
                 <div class="flex items-center justify-end">
                     <button class="party-btn bg-gradient-to-r text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300" 
                             style="background: linear-gradient(135deg, ${color}, ${adjustColorBrightness(color, -20)});"
                             data-party="${partyKey}">
                         <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                         </svg>
                         Bekijk partij
                     </button>
                 </div>
             </div>
         `;
        
        // Re-attach event listeners for the new buttons
        attachButtonListeners(card);
    }
    
    // Restore grid view
    function transformToGridView(card) {
        // Get the original party key to rebuild the card
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        
        // Restore original card HTML (this would need the original template)
        location.reload(); // Simple solution - reload to restore original cards
    }
    
    // Helper function to attach event listeners to new buttons
    function attachButtonListeners(card) {
        const partyBtn = card.querySelector('.party-btn');
        
        if (partyBtn) {
            partyBtn.addEventListener('click', function() {
                // Navigate to party detail page instead of showing modal
                const partyKey = this.getAttribute('data-party');
                window.location.href = `<?php echo URLROOT; ?>/partijen/${partyKey}`;
            });
        }
    }
    
    // Enhanced tab switching for chamber views
    const currentTab = document.getElementById('current-tab');
    const pollingTab = document.getElementById('polling-tab');
    const currentView = document.getElementById('current-view');
    const pollingView = document.getElementById('polling-view');
    
    if (currentTab && pollingTab && currentView && pollingView) {
        currentTab.addEventListener('click', function() {
            // Update current tab styling
            currentTab.classList.add('bg-white/30');
            currentTab.classList.remove('bg-white/20');
            
            // Update polling tab styling
            pollingTab.classList.remove('bg-white/30');
            pollingTab.classList.add('bg-white/20', 'text-white/70');
            pollingTab.classList.remove('text-white');
            
            // Switch views with animation
            pollingView.classList.add('hidden');
            currentView.classList.remove('hidden');
            
            // Trigger chamber visualization for current seats
            setTimeout(() => {
                createChamberVisualization();
            }, 100);
        });
        
        pollingTab.addEventListener('click', function() {
            // Update polling tab styling
            pollingTab.classList.add('bg-white/30', 'text-white');
            pollingTab.classList.remove('bg-white/20', 'text-white/70');
            
            // Update current tab styling
            currentTab.classList.remove('bg-white/30');
            currentTab.classList.add('bg-white/20', 'text-white/70');
            currentTab.classList.remove('text-white');
            
            // Switch views with animation
            currentView.classList.add('hidden');
            pollingView.classList.remove('hidden');
            
            // Trigger chamber visualization for polling seats
            setTimeout(() => {
                createChamberVisualization();
            }, 100);
        });
    }
    
    // Create premium chamber visualization with enhanced animations
    function createChamberVisualization() {
        const currentSeatsContainer = document.getElementById('current-seats-chamber');
        const pollingSeatsContainer = document.getElementById('polling-seats-chamber');
        
        if (!currentSeatsContainer || !pollingSeatsContainer) return;
        
        // Clear containers with fade effect
        fadeOutAndClear(currentSeatsContainer);
        fadeOutAndClear(pollingSeatsContainer);
        
        // Define realistic Dutch Parliament layout (semicircle arrangement)
        const chamberLayout = [
            8,   // Back row
            12,  // 
            16,  // 
            20,  // 
            24,  // 
            28,  // 
            32,  // Front center
            10   // Speaker area
        ];
        
        // Create seats for both views with staggered animation
        setTimeout(() => {
            createPremiumChamberSeats(currentSeatsContainer, chamberLayout, 'current');
            createPremiumChamberSeats(pollingSeatsContainer, chamberLayout, 'polling');
        }, 300);
    }
    
    function fadeOutAndClear(container) {
        container.style.transition = 'opacity 0.3s ease';
        container.style.opacity = '0';
        setTimeout(() => {
            container.innerHTML = '<div class="text-slate-500 font-medium animate-pulse">Zetels worden geladen...</div>';
            container.style.opacity = '1';
        }, 300);
    }
    
    function createPremiumChamberSeats(container, layout, type) {
        let seatCount = 0;
        const totalSeats = 150;
        let partySeats = [];
        
        // Collect all parties with their seats (current or polling)
        for (const [partyKey, party] of Object.entries(partyData)) {
            const seatNum = type === 'current' ? 
                parseInt(party.current_seats) || 0 : 
                parseInt(party.polling?.seats) || 0;
            if (seatNum > 0) {
                partySeats.push({
                    party: partyKey,
                    count: seatNum,
                    color: getPartyColor(partyKey),
                    partyData: party
                });
            }
        }
        
        // Sort by seat count for better visual distribution
        partySeats.sort((a, b) => b.count - a.count);
        
        // Create mixed array for realistic distribution
        let allSeats = [];
        partySeats.forEach(partySeat => {
            for (let i = 0; i < partySeat.count; i++) {
                allSeats.push({
                    party: partySeat.party,
                    color: partySeat.color,
                    partyData: partySeat.partyData
                });
            }
        });
        
        // Smart shuffle for realistic clustering
        allSeats = smartShuffle(allSeats);
        
        // Add empty seats if needed
        while (allSeats.length < totalSeats) {
            allSeats.push({ party: 'empty', color: '#E5E7EB', isEmpty: true });
        }
        
        // Clear loading message
        container.innerHTML = '';
        
        // Create rows of seats with enhanced styling
        layout.forEach((seatsInRow, rowIndex) => {
            const row = document.createElement('div');
            row.className = 'flex justify-center items-center mb-2 gap-1.5 md:gap-2';
            row.style.marginBottom = `${4 + rowIndex * 2}px`;
            
            for (let i = 0; i < seatsInRow && seatCount < totalSeats; i++) {
                const seatData = allSeats[seatCount];
                const seat = createPremiumSeat(seatData, type, seatCount);
                
                // Add staggered animation
                seat.style.animationDelay = `${seatCount * 8}ms`;
                
                row.appendChild(seat);
                seatCount++;
            }
            
            container.appendChild(row);
        });
    }
    
    function smartShuffle(array) {
        // Keep some clustering while still shuffling
        const result = [...array];
        for (let i = result.length - 1; i > 0; i--) {
            // Bias towards nearby positions for more realistic clustering
            const maxDistance = Math.min(5, i);
            const j = Math.max(0, i - Math.floor(Math.random() * maxDistance));
            [result[i], result[j]] = [result[j], result[i]];
        }
        return result;
    }
    
    function createPremiumSeat(seatData, type, index) {
        const seat = document.createElement('div');
        seat.className = 'premium-seat w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 rounded-full border-2 border-white shadow-lg cursor-pointer relative transform hover:scale-125 transition-all duration-300 opacity-0';
        
        // Add entrance animation
        seat.style.animation = 'seatFadeIn 0.6s ease-out forwards';
        
        if (seatData.isEmpty) {
            seat.style.backgroundColor = seatData.color;
            seat.style.opacity = '0.3';
            seat.style.cursor = 'default';
        } else {
            seat.style.backgroundColor = seatData.color;
            seat.style.boxShadow = `0 4px 12px ${seatData.color}40, 0 2px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.3)`;
            
            // Enhanced tooltip
            const tooltip = createPremiumTooltip(seatData, type);
            seat.appendChild(tooltip);
            
            // Premium interactions
            seat.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.5) translateY(-3px)';
                this.style.zIndex = '100';
                this.style.boxShadow = `0 8px 25px ${seatData.color}60, 0 4px 12px rgba(0,0,0,0.15)`;
                
                const tooltip = this.querySelector('.premium-tooltip');
                if (tooltip) {
                    tooltip.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    tooltip.classList.add('opacity-100', 'scale-100');
                }
            });
            
            seat.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0px)';
                this.style.zIndex = '10';
                this.style.boxShadow = `0 4px 12px ${seatData.color}40, 0 2px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.3)`;
                
                const tooltip = this.querySelector('.premium-tooltip');
                if (tooltip) {
                    tooltip.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    tooltip.classList.remove('opacity-100', 'scale-100');
                }
            });
            
            // Click to view party details
            seat.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `<?php echo URLROOT; ?>/partijen/${seatData.party}`;
            });
        }
        
        seat.dataset.party = seatData.party;
        return seat;
    }
    
    function createPremiumTooltip(seatData, type) {
        const tooltip = document.createElement('div');
        tooltip.className = 'premium-tooltip absolute bottom-full left-1/2 transform -translate-x-1/2 mb-4 bg-black/95 backdrop-blur-sm text-white px-4 py-3 rounded-xl text-sm whitespace-nowrap opacity-0 scale-95 transition-all duration-200 pointer-events-none z-50 shadow-2xl border border-white/20';
        
        const seats = type === 'current' ? seatData.partyData.current_seats : seatData.partyData.polling.seats;
        const percentage = ((seats / 150) * 100).toFixed(1);
        
        tooltip.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden bg-white/20 p-1 flex-shrink-0">
                    <img src="${seatData.partyData.logo}" alt="${seatData.party}" class="w-full h-full object-contain" onerror="this.style.display='none'">
                </div>
                <div>
                    <div class="font-bold text-white">${seatData.party}</div>
                    <div class="text-xs text-gray-300 truncate max-w-32">${seatData.partyData.name}</div>
                    <div class="text-xs mt-1">
                        <span class="text-blue-300 font-medium">${seats} zetels</span>
                        <span class="text-gray-400"> • ${percentage}%</span>
                    </div>
                </div>
            </div>
            <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-black/95"></div>
        `;
        
        return tooltip;
    }
    
    // Initialize chamber visualization
    setTimeout(() => {
        createChamberVisualization();
    }, 500);
    
    // REMOVED: highlightPartySeats and resetHighlights functions
    // These functions are no longer needed since we removed the hover highlighting functionality
        

    
    // Assign colors to parties
    function getPartyColor(partyKey) {
        const partyColors = {
            'PVV': '#0078D7', 
            'VVD': '#FF9900',
            'NSC': '#4D7F78',
            'BBB': '#006633',
            'GL-PvdA': '#008800',
            'D66': '#00B13C',
            'SP': '#EE0000',
            'PvdD': '#007E3A',
            'CDA': '#1E8449',
            'JA21': '#0066CC',
            'SGP': '#FF6600', 
            'FvD': '#811E1E',
            'DENK': '#00b7b2',
            'Volt': '#502379',
            'CU': '#00AEEF'
        };
        
        return partyColors[partyKey] || '#A0A0A0';
    }
    
    // Helper function to adjust color brightness for JavaScript
    function adjustColorBrightness(hex, steps) {
        // Remove the # if present
        hex = hex.replace('#', '');
        
        // Parse r, g, b values
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        
        // Adjust brightness
        const newR = Math.max(0, Math.min(255, r + steps));
        const newG = Math.max(0, Math.min(255, g + steps));
        const newB = Math.max(0, Math.min(255, b + steps));
        
        // Convert back to hex
        const toHex = (n) => {
            const hex = n.toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };
        
        return '#' + toHex(newR) + toHex(newG) + toHex(newB);
    }
});

// Helper function for PHP to use the same color mapping
<?php
function getPartyColor($partyKey) {
    $partyColors = [
        'PVV' => '#0078D7',
        'VVD' => '#FF9900',
        'NSC' => '#4D7F78',
        'BBB' => '#95c119',
        'GL-PvdA' => '#008800',
        'D66' => '#00B13C',
        'SP' => '#EE0000',
        'PvdD' => '#007E3A',
        'CDA' => '#1E8449',
        'JA21' => '#0066CC',
        'SGP' => '#FF6600',
        'FvD' => '#811E1E',
        'DENK' => '#00b7b2',
        'Volt' => '#502379',
        'CU' => '#00AEEF'
    ];
    
    return isset($partyColors[$partyKey]) ? $partyColors[$partyKey] : '#A0A0A0';
}

// New helper function to adjust color opacity for standpoint badges
function adjustColorOpacity($hex, $opacity) {
    // Convert hex to rgb
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "rgba($r, $g, $b, $opacity)";
}

// New helper function to adjust color brightness
function adjustColorBrightness($hex, $steps) {
    // Convert hex to rgb
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    
    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    // Convert back to hex
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
?>

// Completely Rebuilt Modern Coalition Maker - Tap/Click Only
document.addEventListener('DOMContentLoaded', function() {
    const NewCoalitionMaker = {
        parties: <?php echo json_encode($parties); ?>,
        currentView: 'current',
        selectedParties: new Set(),
        
        // Political spectrum positions (0 = far left, 100 = far right)
        partyPositions: {
            'PVV': 85, 'VVD': 70, 'NSC': 45, 'BBB': 60, 'GL-PvdA': 15,
            'D66': 30, 'SP': 10, 'PvdD': 20, 'CDA': 50, 'JA21': 80,
            'SGP': 75, 'FvD': 90, 'DENK': 25, 'Volt': 35, 'CU': 40
        },
        
        partyColors: {
            'PVV': '#0078D7', 'VVD': '#FF9900', 'NSC': '#4D7F78', 'BBB': '#006633',
            'GL-PvdA': '#008800', 'D66': '#00B13C', 'SP': '#EE0000', 'PvdD': '#007E3A',
            'CDA': '#1E8449', 'JA21': '#0066CC', 'SGP': '#FF6600', 'FvD': '#811E1E',
            'DENK': '#00b7b2', 'Volt': '#502379', 'CU': '#00AEEF'
        },
        
        init() {
            this.setupEventListeners();
            this.renderPartyGrid();
            this.renderHemicycle();
            this.updateAllMetrics();
            this.generateSuggestions();
        },
        
        setupEventListeners() {
            // Tab switching
            document.getElementById('coalition-current-tab')?.addEventListener('click', () => this.switchView('current'));
            document.getElementById('coalition-polling-tab')?.addEventListener('click', () => this.switchView('polling'));
            
            // Action buttons
            document.getElementById('clear-all-btn')?.addEventListener('click', () => this.clearAll());
            document.getElementById('random-coalition-btn')?.addEventListener('click', () => this.randomCoalition());
        },
        
        switchView(view) {
            this.currentView = view;
            
            // Update tabs
            const currentTab = document.getElementById('coalition-current-tab');
            const pollingTab = document.getElementById('coalition-polling-tab');
            
            if (view === 'current') {
                currentTab?.classList.add('active');
                currentTab?.classList.remove('text-white/60');
                pollingTab?.classList.remove('active');
                pollingTab?.classList.add('text-white/60');
            } else {
                pollingTab?.classList.add('active');
                pollingTab?.classList.remove('text-white/60');
                currentTab?.classList.remove('active');
                currentTab?.classList.add('text-white/60');
            }
            
            this.renderPartyGrid();
            this.updateAllMetrics();
        },
        
        renderPartyGrid() {
            const container = document.getElementById('party-grid');
            if (!container) return;
            
            container.innerHTML = '';
            
            // Sort parties by seats
            const sorted = Object.entries(this.parties).sort((a, b) => {
                const seatsA = this.getSeats(a[0]);
                const seatsB = this.getSeats(b[0]);
                return seatsB - seatsA;
            });
            
            sorted.forEach(([key, party]) => {
                const seats = this.getSeats(key);
                if (seats > 0) {
                    const card = this.createPartyCard(key, party, seats);
                    container.appendChild(card);
                }
            });
        },
        
        createPartyCard(key, party, seats) {
            const card = document.createElement('div');
            const isSelected = this.selectedParties.has(key);
            const color = this.partyColors[key] || '#A0A0A0';
            
            card.className = `party-card cursor-pointer rounded-xl p-3 md:p-4 transition-all duration-300 border-2 ${
                isSelected 
                    ? 'bg-gradient-to-br from-primary/10 to-secondary/10 border-primary shadow-lg scale-105' 
                    : 'bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 hover:shadow-md'
            }`;
            
            card.innerHTML = `
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden bg-white border border-gray-200 flex-shrink-0 flex items-center justify-center shadow-sm">
                            <img src="${party.logo}" alt="${key}" class="w-8 h-8 md:w-10 md:h-10 object-contain p-1">
                            </div>
                            <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-sm md:text-base truncate">${key}</h4>
                                <p class="text-xs text-gray-600 truncate hidden sm:block">${party.name}</p>
                            </div>
                        </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                            <span class="text-xs font-semibold text-gray-700">${seats} zetels</span>
                            </div>
                        ${isSelected ? `
                            <div class="flex items-center gap-1 text-primary">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                            ` : ''}
                        </div>
                    </div>
            `;
            
            card.addEventListener('click', () => this.toggleParty(key));
            return card;
        },
        
        toggleParty(key) {
            if (this.selectedParties.has(key)) {
                this.selectedParties.delete(key);
            } else {
                this.selectedParties.add(key);
            }
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        clearAll() {
            this.selectedParties.clear();
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        randomCoalition() {
            this.selectedParties.clear();
            const parties = Object.keys(this.parties).filter(k => this.getSeats(k) > 0);
            let totalSeats = 0;
            
            // Shuffle and select until we have a majority
            const shuffled = parties.sort(() => Math.random() - 0.5);
            for (const party of shuffled) {
                if (totalSeats >= 76) break;
                this.selectedParties.add(party);
                totalSeats += this.getSeats(party);
                if (this.selectedParties.size >= 6) break; // Max 6 parties
            }
            
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        getSeats(key) {
            const party = this.parties[key];
            return this.currentView === 'current' 
                ? parseInt(party.current_seats) || 0 
                : parseInt(party.polling?.seats) || 0;
        },
        
        getTotalSeats() {
            let total = 0;
            this.selectedParties.forEach(key => {
                total += this.getSeats(key);
            });
            return total;
        },
        
        updateAllMetrics() {
            const totalSeats = this.getTotalSeats();
            const percentage = Math.round((totalSeats / 150) * 100);
            const partyCount = this.selectedParties.size;
            const hasMajority = totalSeats >= 76;
            
            // Update all displays
            this.animateNumber('header-seats', totalSeats);
            this.animateNumber('header-parties', partyCount);
            this.animateNumber('dashboard-seats', totalSeats);
            this.animateNumber('progress-seats', totalSeats);
            this.animateNumber('dashboard-party-count', partyCount);
            this.animateNumber('hemicycle-selected', totalSeats);
            
            document.getElementById('dashboard-percentage').textContent = percentage + '%';
            
            // Update progress bar
            const progressBar = document.getElementById('main-progress-bar');
            if (progressBar) {
                progressBar.style.width = Math.min(100, (totalSeats / 150) * 100) + '%';
            }
            
            // Update status
            const statusIcon = document.getElementById('dashboard-status-icon');
            const statusText = document.getElementById('dashboard-status-text');
            
            if (totalSeats === 0) {
                statusText.textContent = 'Geen coalitie';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center';
            } else if (hasMajority) {
                statusText.textContent = 'Meerderheid!';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center';
            } else {
                statusText.textContent = (76 - totalSeats) + ' tekort';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center';
            }
        },
        
        animateNumber(elementId, targetValue) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const startValue = parseInt(element.textContent) || 0;
            const duration = 500;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(startValue + (targetValue - startValue) * easeOut);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        renderHemicycle() {
            const svg = document.getElementById('hemicycle-svg');
            if (!svg) return;
            
            svg.innerHTML = '';
            
            // Configuration
            const centerX = 200;
            const centerY = 180;
            const rows = 5;
            const seatsPerRow = [20, 25, 30, 35, 40];
            const startRadius = 60;
            const radiusIncrement = 20;
            
            let seatIndex = 0;
            const selectedKeys = Array.from(this.selectedParties);
            let currentPartyIndex = 0;
            let seatsForCurrentParty = selectedKeys.length > 0 ? this.getSeats(selectedKeys[0]) : 0;
            
            // Draw seats in hemicycle
            for (let row = 0; row < rows; row++) {
                const radius = startRadius + (row * radiusIncrement);
                const numSeats = seatsPerRow[row];
                const startAngle = Math.PI;
                const endAngle = 0;
                const angleStep = (startAngle - endAngle) / (numSeats - 1);
                
                for (let i = 0; i < numSeats; i++) {
                    const angle = startAngle - (i * angleStep);
                    const x = centerX + radius * Math.cos(angle);
                    const y = centerY - radius * Math.sin(angle);
                    
                    let color = '#E5E7EB'; // gray-200 for empty seats
                    
                    // Assign color if seat is part of selected coalition
                    if (seatIndex < this.getTotalSeats()) {
                        if (seatsForCurrentParty > 0) {
                            color = this.partyColors[selectedKeys[currentPartyIndex]] || '#A0A0A0';
                            seatsForCurrentParty--;
                            
                            if (seatsForCurrentParty === 0 && currentPartyIndex < selectedKeys.length - 1) {
                                currentPartyIndex++;
                                seatsForCurrentParty = this.getSeats(selectedKeys[currentPartyIndex]);
                            }
                        }
                    }
                    
                    // Create seat circle
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', x);
                    circle.setAttribute('cy', y);
                    circle.setAttribute('r', '3.5');
                    circle.setAttribute('fill', color);
                    circle.setAttribute('stroke', '#fff');
                    circle.setAttribute('stroke-width', '0.5');
                    
                    svg.appendChild(circle);
                    seatIndex++;
                }
            }
        },
        
        updateSpectrum() {
            const container = document.getElementById('spectrum-indicators');
            const info = document.getElementById('spectrum-info');
            
            if (!container || !info) return;
            
            container.innerHTML = '';
            
            if (this.selectedParties.size === 0) {
                info.innerHTML = '<div class="text-sm text-gray-600">Selecteer partijen om het politieke spectrum te zien</div>';
                return;
            }
            
            // Calculate weighted average
            let totalPosition = 0;
            let totalSeats = 0;
            
            this.selectedParties.forEach(key => {
                const position = this.partyPositions[key] || 50;
                const seats = this.getSeats(key);
                totalPosition += position * seats;
                totalSeats += seats;
            });
            
            const avgPosition = totalPosition / totalSeats;
            
            // Place indicators for each party
            this.selectedParties.forEach(key => {
                const position = this.partyPositions[key] || 50;
                const color = this.partyColors[key] || '#A0A0A0';
                
                const indicator = document.createElement('div');
                indicator.className = 'absolute w-3 h-8 -top-1 transform -translate-x-1/2 transition-all duration-500';
                indicator.style.left = position + '%';
                indicator.innerHTML = `
                    <div class="w-full h-full rounded-full shadow-lg border-2 border-white" style="background-color: ${color}"></div>
                `;
                
                container.appendChild(indicator);
            });
            
            // Describe political orientation
            let description = '';
            if (avgPosition < 25) description = 'Sterk links-georiënteerde coalitie';
            else if (avgPosition < 40) description = 'Links-georiënteerde coalitie';
            else if (avgPosition < 48) description = 'Centrum-links coalitie';
            else if (avgPosition < 53) description = 'Centrum coalitie';
            else if (avgPosition < 65) description = 'Centrum-rechts coalitie';
            else if (avgPosition < 80) description = 'Rechts-georiënteerde coalitie';
            else description = 'Sterk rechts-georiënteerde coalitie';
            
            info.innerHTML = `<div class="text-sm font-semibold text-gray-900">${description}</div>`;
        },
        
        generateSuggestions() {
            const container = document.getElementById('suggestions-container');
            if (!container) return;
            
            const suggestions = [
                { name: 'Grote Coalitie', parties: ['PVV', 'VVD', 'GL-PvdA', 'NSC'], description: 'Vier grootste partijen' },
                { name: 'Paars-Plus', parties: ['VVD', 'D66', 'GL-PvdA', 'Volt'], description: 'Progressief-liberaal' },
                { name: 'Nationaal Compromis', parties: ['VVD', 'NSC', 'GL-PvdA', 'CDA', 'D66'], description: 'Breed centrum' },
                { name: 'Rechts Blok', parties: ['PVV', 'VVD', 'NSC', 'BBB', 'JA21'], description: 'Conservatief-liberaal' }
            ];
            
            container.innerHTML = suggestions.map(sug => {
                // Calculate seats for polling view
                const totalSeats = sug.parties.reduce((sum, key) => {
                    const party = this.parties[key];
                    return sum + (parseInt(party?.polling?.seats) || 0);
                }, 0);
                
                if (totalSeats < 76) return ''; // Only show coalitions with majority
                
                return `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200 hover:border-primary hover:shadow-md transition-all cursor-pointer"
                         onclick="NewCoalitionMaker.applySuggestion(${JSON.stringify(sug.parties).replace(/"/g, '&quot;')})">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-bold text-gray-900 text-sm">${sug.name}</h4>
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-primary/10 text-primary">${totalSeats} zetels</span>
                            </div>
                        <p class="text-xs text-gray-600 mb-3">${sug.description}</p>
                        <div class="flex flex-wrap gap-2">
                            ${sug.parties.map(key => {
                                const party = this.parties[key];
                                if (!party) return '';
                                const color = this.partyColors[key] || '#A0A0A0';
                                return `
                                    <div class="flex items-center gap-1 bg-white rounded-lg px-2 py-1 border border-gray-200">
                                        <div class="w-2 h-2 rounded-full" style="background-color: ${color}"></div>
                                        <span class="text-xs font-semibold">${key}</span>
                        </div>
                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }).filter(html => html !== '').join('');
        },
        
        applySuggestion(partyKeys) {
            this.selectedParties.clear();
            partyKeys.forEach(key => {
                if (this.parties[key] && this.getSeats(key) > 0) {
                    this.selectedParties.add(key);
                }
            });
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        }
    };
    
    // Initialize
    NewCoalitionMaker.init();
    window.NewCoalitionMaker = NewCoalitionMaker;
    
    // Additional party functionality (existing code)
    const partyData = <?php echo json_encode($parties); ?>;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = 'https://i.ibb.co/kXL6rQ8/placeholder-profile.jpg';
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
            parties: <?php echo json_encode($parties); ?>,
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
            console.error('AI Analysis Error:', error);
            showAiError();
        });
    }
    
    function showAiError() {
        aiLoading.classList.add('hidden');
        aiError.classList.remove('hidden');
    }
    
    function formatAiResponse(content) {
        // Format AI response with proper styling
        const sections = content.split('\n\n');
        return sections.map(section => {
            if (section.startsWith('#')) {
                const title = section.replace(/^#+\s*/, '');
                return `<h3 class="text-xl font-bold text-gray-900 mb-3 mt-6 first:mt-0">${title}</h3>`;
            } else if (section.startsWith('-')) {
                const items = section.split('\n').filter(line => line.trim());
                const listItems = items.map(item => `<li>${item.replace(/^-\s*/, '')}</li>`).join('');
                return `<ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">${listItems}</ul>`;
            } else {
                return `<p class="text-gray-700 leading-relaxed mb-4">${section}</p>`;
            }
        }).join('');
    }
    
    // Event listeners for AI modal
    if (aiBtn) {
        aiBtn.addEventListener('click', openAiModal);
    }
    
    if (closeAiModal) {
        closeAiModal.addEventListener('click', closeAiModalFunc);
    }
    
    if (retryBtn) {
        retryBtn.addEventListener('click', performAiAnalysis);
    }
    
    // Close AI modal when clicking outside
    if (aiModal) {
        aiModal.addEventListener('click', function(e) {
            if (e.target === aiModal) {
                closeAiModalFunc();
            }
        });
    }
    
    // Close AI modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !aiModal.classList.contains('hidden')) {
            closeAiModalFunc();
        }
    });
});
</script>

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
    const partyData = <?php echo json_encode($parties); ?>;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = 'https://i.ibb.co/kXL6rQ8/placeholder-profile.jpg';
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
            parties: <?php echo json_encode($parties); ?>,
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

<style>
/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Enhanced Party Cards Styling */

/* Modern hover animations and transitions */
.party-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, box-shadow;
}

.party-card:hover {
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Enhanced button shimmer effects */
.party-btn:hover {
    animation: buttonPulse 0.6s ease;
}

@keyframes buttonPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Fade in animation for search results */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced focus states */
input:focus, select:focus, button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Premium gradient backgrounds */
.bg-premium-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Advanced backdrop blur effects */
.backdrop-blur-premium {
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
}

/* Enhanced scrollbar styling */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

/* Text truncation utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced Party Card Animation */
.party-card {
    position: relative;
    backface-visibility: hidden;
}

.party-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    opacity: 0;
    pointer-events: none;
    z-index: 1;
}

.party-card:active {
    
}

/* Drag and Drop Visual Feedback */
.party-card[draggable="true"]:active {
    opacity: 0.8;
    z-index: 1000;
}

/* Coalition Drop Zone Enhanced Styling */
#selected-coalition {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
}

#selected-coalition.bg-emerald-50 {
    background-color: rgba(16, 185, 129, 0.1);
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3), inset 0 0 20px rgba(16, 185, 129, 0.1);
}

/* Progress Bar Enhanced Animation */
#coalition-progress {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    background-size: 200% 100%;
    position: relative;
    overflow: hidden;
}

/* Chamber Visualization Styling */
.chamber {
    width: 100%;
    height: 300px;
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 50% 50% 0 0;
    overflow: hidden;
    border: 3px solid #cbd5e1;
    box-shadow: inset 0 4px 6px rgba(0, 0, 0, 0.1);
}

.chamber-grid {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    padding: 20px;
}

.chamber-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 4px;
    gap: 3px;
}

.chamber-seat {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.8);
    cursor: pointer;
    position: relative;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.chamber-seat.highlight {
    border: 2px solid #fbbf24;
    box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.3), 0 4px 12px rgba(0, 0, 0, 0.4);
    z-index: 20;
}

.chamber-seat.dim {
    opacity: 0.3;
}

.seat-tooltip {
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    z-index: 100;
}

.seat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
}

.chamber-seat.active .seat-tooltip {
    opacity: 1;
}

/* Responsive adjustments for chamber */
@media (max-width: 768px) {
    .chamber {
        height: 200px;
    }
    
    .chamber-seat {
        width: 8px;
        height: 8px;
    }
    
    .chamber-row {
        gap: 2px;
        margin-bottom: 2px;
    }
    
    .chamber-grid {
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .chamber {
        height: 150px;
    }
    
    .chamber-seat {
        width: 6px;
        height: 6px;
    }
    
    .chamber-row {
        gap: 1px;
        margin-bottom: 1px;
    }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Modern Coalitiemaker CSS */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.bg-shimmer {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

/* Tab styling voor coalitiemaker */
.coalition-tab-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.coalition-tab-btn.active {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(8px);
    color: white;
}

.coalition-tab-btn:not(.active) {
    color: rgba(255, 255, 255, 0.7);
}

.coalition-tab-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.coalition-tab-btn:hover::before {
    left: 100%;
}

/* Party card hover effects */
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

/* Drag feedback */
.party-card[draggable="true"]:active {
    opacity: 0.8;
    z-index: 1000;
    cursor: grabbing;
}

/* Coalition drop zone enhanced styling */
#selected-coalition {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(var(--secondary-rgb), 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(var(--secondary-rgb), 0.03) 0%, transparent 50%);
    transition: all 0.3s ease;
}

#selected-coalition.border-secondary {
    box-shadow: 0 0 0 2px rgba(var(--secondary-rgb), 0.3), inset 0 0 20px rgba(var(--secondary-rgb), 0.1);
}

/* Progress bar enhanced animation */
#coalition-progress {
    background: linear-gradient(90deg, var(--primary), var(--secondary), var(--secondary-light));
    background-size: 200% 100%;
    position: relative;
    overflow: hidden;
    animation: shimmer 4s ease-in-out infinite;
}

/* Smooth animation for counters */
@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

#coalition-seats, #available-count {
    animation: countUp 0.3s ease-out;
}

/* Remove button hover effect */
.remove-party {
    transition: all 0.2s ease;
    transform: scale(0.9);
}

.remove-party:hover {
    transform: scale(1);
    background-color: rgba(239, 68, 68, 0.2);
}

/* Spectrum indicator smooth movement */
#coalition-spectrum-indicator {
    transition: left 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Status badge animations */
#coalition-status {
    transition: all 0.4s ease;
}

/* Suggestion cards hover */
#coalition-suggestions .cursor-pointer:hover {
    transform: translateY(-1px);
}

/* Enhanced scrollbar styling */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: rgba(var(--primary-rgb), 0.3) rgba(var(--primary-rgb), 0.1);
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: rgba(var(--primary-rgb), 0.1);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgba(var(--primary-rgb), 0.3);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgba(var(--primary-rgb), 0.5);
}

/* Fade in animation voor party cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.party-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Glow effect voor active elements */
.glow-primary {
    box-shadow: 0 0 20px rgba(var(--primary-rgb), 0.3);
}

.glow-secondary {
    box-shadow: 0 0 20px rgba(var(--secondary-rgb), 0.3);
}

/* Mobile responsive improvements */
@media (max-width: 1023px) {
    .party-card {
        cursor: pointer !important;
    }
    
    .party-card:active {
        transform: scale(0.95);
        background-color: rgba(var(--secondary-rgb), 0.05);
    }
    
    /* Disable drag on mobile */
    .party-card[draggable="true"] {
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
    }
}

/* Touch-friendly button sizing */
@media (max-width: 768px) {
    .coalition-tab-btn {
        min-height: 44px; /* iOS recommended touch target */
        touch-action: manipulation;
    }
    
    #clear-coalition,
    #shuffle-coalition {
        min-height: 44px;
        touch-action: manipulation;
    }
    
    .remove-party {
        min-width: 44px;
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Improved scrolling on mobile */
@media (max-width: 768px) {
    #available-parties,
    #selected-coalition,
    #coalition-suggestions {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }
}

/* Better text sizing on small screens */
@media (max-width: 640px) {
    .party-card h4 {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    .party-card p,
    .party-card span {
        font-size: 0.75rem;
        line-height: 1rem;
    }
}

/* Landscape mobile optimization */
@media (max-width: 896px) and (orientation: landscape) {
    .coalitiemaker-header {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .coalitiemaker-content {
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
}

.chamber-semicircle {
    border-radius: 50% 50% 0 0;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
}

.chamber-seat-tooltip {
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
}

.chamber-seat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
}

.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

/* Premium Chamber Seat Animations */
@keyframes seatFadeIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(20px);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1) translateY(-5px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.premium-seat {
    animation: seatFadeIn 0.6s ease-out forwards;
}

/* Enhanced Chamber Design */
.chamber-semicircle {
    border-radius: 50% 50% 0 0;
    background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 50%, #cbd5e1 100%);
    position: relative;
}

.chamber-semicircle::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent 48%, rgba(255,255,255,0.1) 49%, rgba(255,255,255,0.1) 51%, transparent 52%);
    border-radius: inherit;
    pointer-events: none;
}

/* Premium Tooltip Styling */
.premium-tooltip {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* Enhanced Tab Styling */
.chamber-tab-btn {
    position: relative;
    overflow: hidden;
}

.chamber-tab-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.chamber-tab-btn:hover::before {
    left: 100%;
}

/* Floating Animation for Decorative Elements */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-5px) rotate(1deg); }
    66% { transform: translateY(-8px) rotate(-1deg); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}
</style>

<?php include_once BASE_PATH . '/views/templates/footer.php'; ?>
</div>
</body>
</html>