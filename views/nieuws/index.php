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
                                <span class="text-white/90 text-sm font-medium">Live nieuwsupdates</span>
                            </div>
                        </div>
                        
                        <!-- Main Heading -->
                        <div class="space-y-2 md:space-y-4">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 md:mb-6 tracking-tight leading-tight">
                                Politiek
                                <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                                    Nieuws
                                </span>
                            </h1>
                            
                            <!-- Typing Animation Text -->
                            <div class="text-base md:text-lg lg:text-xl text-blue-100 font-medium min-h-[1.5em] md:min-h-[2em]">
                                <span id="typing-text" class="border-r-2 border-blue-300 animate-pulse"></span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-base md:text-lg lg:text-xl text-blue-100 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Een gebalanceerd overzicht van het laatste nieuws, objectief gepresenteerd vanuit diverse politieke hoeken
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                            <a href="#artikelen" 
                               class="group relative inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold rounded-2xl shadow-2xl hover:shadow-secondary/30 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <svg class="w-5 h-5 mr-2 md:mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                                <span class="relative z-10 text-sm md:text-base">Bekijk nieuws</span>
                            </a>
                            
                            <a href="?clear_cache=1#artikelen" 
                               class="group inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-white/10 backdrop-blur-lg text-white font-semibold rounded-2xl border border-white/20 hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 md:mr-3 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span class="text-sm md:text-base">Vernieuw data</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column - Live News Dashboard -->
                    <div class="relative order-2 lg:order-2 mb-8 lg:mb-0">
                        <!-- Central Decorative Element -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full blur-3xl animate-pulse"></div>
                        </div>
                        
                        <!-- Live News Dashboard -->
                        <div class="relative max-w-lg mx-auto">
                            <!-- Dashboard Container -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 md:p-8 border border-white/20 shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-1">
                                <!-- Header -->
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-light/80 to-secondary/80 rounded-2xl mb-4 shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Live Nieuws Dashboard</h3>
                                    <p class="text-blue-200 text-sm">Real-time politieke updates</p>
                                </div>
                                
                                <!-- Dashboard Stats -->
                                <div class="space-y-4 mb-6">
                                    <!-- Total Articles -->
                                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-white font-semibold text-sm">Totaal artikelen</p>
                                                    <p class="text-blue-200 text-xs">Alle beschikbare bronnen</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-white" id="total-counter"><?php echo $stats['total_articles']; ?></p>
                                                <p class="text-xs text-blue-300">+<span id="total-increment">0</span> vandaag</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progressive Articles -->
                                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-white font-semibold text-sm">Progressief</p>
                                                    <p class="text-blue-200 text-xs">Vooruitstrevende berichtgeving</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-white" id="progressive-counter"><?php echo $stats['progressive_count']; ?></p>
                                                <p class="text-xs text-emerald-300">+<span id="progressive-increment">0</span> recent</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Conservative Articles -->
                                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-red-500/20 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-white font-semibold text-sm">Conservatief</p>
                                                    <p class="text-blue-200 text-xs">Traditionele berichtgeving</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-white" id="conservative-counter"><?php echo $stats['conservative_count']; ?></p>
                                                <p class="text-xs text-red-300">+<span id="conservative-increment">0</span> recent</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Live Activity Feed -->
                                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-white font-bold text-sm">Live Activiteit</h4>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="text-green-400 text-xs font-medium">LIVE</span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2" id="live-activity">
                                        <div class="flex items-center space-x-3 text-xs">
                                            <div class="w-1 h-1 bg-blue-400 rounded-full animate-pulse"></div>
                                            <span class="text-blue-200">Nieuwe artikelen worden geladen...</span>
                                        </div>
                                        <div class="flex items-center space-x-3 text-xs">
                                            <div class="w-1 h-1 bg-emerald-400 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                                            <span class="text-blue-200">Progressieve bronnen bijgewerkt</span>
                                        </div>
                                        <div class="flex items-center space-x-3 text-xs">
                                            <div class="w-1 h-1 bg-red-400 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
                                            <span class="text-blue-200">Conservatieve analyses toegevoegd</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="pt-6 border-t border-white/10">
                                    <div class="flex items-center justify-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-secondary-light/80 to-secondary/80 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-white font-semibold text-sm">Real-time Updates</p>
                                            <p class="text-blue-200 text-xs">Automatisch bijgewerkt elke minuut</p>
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
    
    <!-- Enhanced Scripts for News Dashboard -->
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
            'Waar nieuws vorm krijgt...',
            'Waar perspectieven botsen...',
            'Waar objectiviteit centraal staat...',
            'Waar waarheid boven alles gaat...'
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
        
        // Live Dashboard Animation
        const liveActivity = document.getElementById('live-activity');
        const activities = [
            { text: 'Nieuwe artikelen scannen...', color: 'blue' },
            { text: 'Progressieve bronnen bijgewerkt', color: 'emerald' },
            { text: 'Conservatieve analyses toegevoegd', color: 'red' },
            { text: 'Trending topics geüpdatet', color: 'purple' },
            { text: 'Nieuwsdata gesynchroniseerd', color: 'blue' },
            { text: 'Bias-analyse voltooid', color: 'yellow' }
        ];
        
        let activityIndex = 0;
        
        function updateLiveActivity() {
            const activity = activities[activityIndex];
            const newActivity = document.createElement('div');
            newActivity.className = 'flex items-center space-x-3 text-xs';
            newActivity.innerHTML = `
                <div class="w-1 h-1 bg-${activity.color}-400 rounded-full animate-pulse"></div>
                <span class="text-blue-200">${activity.text}</span>
            `;
            
            liveActivity.appendChild(newActivity);
            
            // Keep only last 3 activities
            if (liveActivity.children.length > 3) {
                liveActivity.removeChild(liveActivity.firstChild);
            }
            
            activityIndex = (activityIndex + 1) % activities.length;
        }
        
        // Update activity every 3 seconds
        setInterval(updateLiveActivity, 3000);
        
        // Animate counters
        function animateCounter(elementId, increment) {
            const element = document.getElementById(elementId);
            if (element) {
                const current = parseInt(element.textContent);
                const random = Math.floor(Math.random() * 3) + 1;
                element.textContent = current + random;
                
                // Update increment
                const incrementElement = document.getElementById(increment);
                if (incrementElement) {
                    const currentIncrement = parseInt(incrementElement.textContent);
                    incrementElement.textContent = currentIncrement + random;
                }
            }
        }
        
        // Simulate counter updates every 30 seconds
        setInterval(() => {
            if (Math.random() > 0.7) { // 30% chance
                animateCounter('total-counter', 'total-increment');
            }
            if (Math.random() > 0.8) { // 20% chance
                animateCounter('progressive-counter', 'progressive-increment');
            }
            if (Math.random() > 0.8) { // 20% chance
                animateCounter('conservative-counter', 'conservative-increment');
            }
        }, 30000);
    });
    </script>

    <!-- Modern Filter Section -->
    <section class="relative z-10 -mt-20 pb-16" id="artikelen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Container -->
            <div class="max-w-5xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                <!-- Filter Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center px-6 py-3 bg-white/90 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-lg mb-6">
                        <div class="w-2 h-2 bg-primary rounded-full animate-pulse mr-3"></div>
                        <span class="text-slate-700 font-semibold text-sm">Nieuws Perspectief</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4">Bekijk vanuit elk perspectief</h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">Vorm je eigen mening door nieuws te bekijken vanuit verschillende politieke invalshoeken</p>
                </div>
                
                <!-- Modern Filter Grid -->
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/70 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Alle Bronnen - Neutrale Blauw -->
                        <a href="?filter=alle#artikelen" 
                           class="group relative p-6 rounded-2xl transition-all duration-500 transform hover:scale-105 hover:-translate-y-2 overflow-hidden <?php echo $filter === 'alle' ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-xl' : 'bg-slate-50 hover:bg-white text-slate-700 hover:shadow-lg'; ?>">
                            
                            <!-- Hover background -->
                            <?php if($filter !== 'alle'): ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 text-center <?php echo $filter !== 'alle' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-lg mb-2">Alle Bronnen</h3>
                                <p class="text-sm opacity-80">Objectief overzicht</p>
                                <?php if($filter === 'alle'): ?>
                                <div class="absolute top-3 right-3 w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-400/20 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 bg-blue-500/20 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                        </a>

                        <!-- Progressief - Groen -->
                        <a href="?filter=progressief#artikelen" 
                           class="group relative p-6 rounded-2xl transition-all duration-500 transform hover:scale-105 hover:-translate-y-2 overflow-hidden <?php echo $filter === 'progressief' ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-xl' : 'bg-slate-50 hover:bg-white text-slate-700 hover:shadow-lg'; ?>">
                            
                            <!-- Hover background -->
                            <?php if($filter !== 'progressief'): ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 text-center <?php echo $filter !== 'progressief' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-lg mb-2">Progressief</h3>
                                <p class="text-sm opacity-80">Vooruitstrevend</p>
                                <?php if($filter === 'progressief'): ?>
                                <div class="absolute top-3 right-3 w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-400/20 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 bg-green-500/20 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                        </a>

                        <!-- Conservatief - Rood -->
                        <a href="?filter=conservatief#artikelen" 
                           class="group relative p-6 rounded-2xl transition-all duration-500 transform hover:scale-105 hover:-translate-y-2 overflow-hidden <?php echo $filter === 'conservatief' ? 'bg-gradient-to-br from-red-500 to-red-600 text-white shadow-xl' : 'bg-slate-50 hover:bg-white text-slate-700 hover:shadow-lg'; ?>">
                            
                            <!-- Hover background -->
                            <?php if($filter !== 'conservatief'): ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 text-center <?php echo $filter !== 'conservatief' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-lg mb-2">Conservatief</h3>
                                <p class="text-sm opacity-80">Behoudend</p>
                                <?php if($filter === 'conservatief'): ?>
                                <div class="absolute top-3 right-3 w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-400/20 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 bg-red-500/20 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                        </a>

                        <!-- Vernieuwen -->
                        <a href="?clear_cache=1<?php echo !empty($filter) && $filter !== 'alle' ? "&filter=$filter" : ""; ?>#artikelen" 
                           class="group relative p-6 rounded-2xl transition-all duration-500 transform hover:scale-105 hover:-translate-y-2 bg-slate-50 hover:bg-white text-slate-700 hover:shadow-lg overflow-hidden">
                            
                            <!-- Hover background -->
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            
                            <!-- Content -->
                            <div class="relative z-10 text-center group-hover:text-white">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-lg mb-2">Vernieuwen</h3>
                                <p class="text-sm opacity-80">Actualiseren</p>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-400/20 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 bg-indigo-500/20 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                        </a>
                    </div>
                    
                    <!-- Separator -->
                    <div class="my-6 border-t border-slate-200"></div>
                    
                    <!-- Source Filter Buttons -->
                    <div>
                        <p class="text-sm text-slate-500 text-center mb-4 font-medium">Of filter per nieuwsbron:</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            <?php
                            // Progressieve bronnen (groen)
                            $progressiefSources = [
                                'De Volkskrant' => 'VK',
                                'NRC' => 'NRC',
                                'Trouw' => 'TR'
                            ];
                            foreach ($progressiefSources as $sourceName => $shortName): 
                                $isActive = $filter === $sourceName;
                            ?>
                                <a href="?filter=<?= urlencode($sourceName) ?>#artikelen" 
                                   class="group relative px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 <?= $isActive ? 'bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'; ?>">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full <?= $isActive ? 'bg-white' : 'bg-emerald-500'; ?>"></span>
                                        <?= htmlspecialchars($sourceName) ?>
                                    </span>
                                    <?php if($isActive): ?>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full animate-pulse shadow"></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                            
                            <?php
                            // Conservatieve bronnen (rood)
                            $conservatiefSources = [
                                'AD' => 'AD',
                                'NU.nl' => 'NU',
                                'RTL Nieuws' => 'RTL'
                            ];
                            foreach ($conservatiefSources as $sourceName => $shortName): 
                                $isActive = $filter === $sourceName;
                            ?>
                                <a href="?filter=<?= urlencode($sourceName) ?>#artikelen" 
                                   class="group relative px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 <?= $isActive ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200'; ?>">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full <?= $isActive ? 'bg-white' : 'bg-red-500'; ?>"></span>
                                        <?= htmlspecialchars($sourceName) ?>
                                    </span>
                                    <?php if($isActive): ?>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full animate-pulse shadow"></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Articles Section -->
    <section class="relative z-10 pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Debug info (tijdelijk) -->
            <?php if (isset($_GET['debug'])): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-8">
                    <strong>Debug Info:</strong><br>
                    - Huidige pagina: <?php echo $currentPage; ?><br>
                    - Totaal pagina's: <?php echo $totalPages; ?><br>
                    - Totaal artikelen: <?php echo $totalArticles; ?><br>
                    - Artikelen per pagina: <?php echo $articlesPerPage; ?><br>
                    - Artikelen gevonden: <?php echo count($latest_news); ?><br>
                    - Filter: <?php echo $filter; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($latest_news)): ?>
                <!-- Modern Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                    <?php foreach ($latest_news as $index => $article): ?>
                        <article class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100" 
                                data-aos="fade-up" 
                                data-aos-delay="<?php echo $index * 100; ?>"
                                data-aos-duration="600"
                                data-aos-once="true">
                            
                            <!-- Kleuraccent bovenaan gebaseerd op bias -->
                            <div class="absolute top-0 left-0 right-0 h-2 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-r from-emerald-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'; ?>"></div>
                            
                            <!-- Hover overlay effect -->
                            <div class="absolute inset-0 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-br from-emerald-50/40 to-green-50/40' : 'bg-gradient-to-br from-red-50/40 to-red-50/40'; ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <div class="relative z-10 p-8 h-full flex flex-col">
                                <!-- Header met bias badge en bron -->
                                <div class="mb-6">
                                    <!-- Bias Badge met juiste kleuren -->
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider <?php echo $article['bias'] === 'Progressief' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200'; ?> shadow-sm">
                                            <div class="w-2 h-2 rounded-full mr-2 <?php echo $article['bias'] === 'Progressief' ? 'bg-emerald-600' : 'bg-red-600'; ?> animate-pulse"></div>
                                            <?php echo htmlspecialchars($article['bias']); ?>
                                        </span>
                                        
                                        <!-- Bookmark icon -->
                                        <div class="w-10 h-10 rounded-xl <?php echo $article['bias'] === 'Progressief' ? 'bg-emerald-50 group-hover:bg-emerald-100' : 'bg-red-50 group-hover:bg-red-100'; ?> flex items-center justify-center transition-colors duration-300">
                                            <svg class="w-5 h-5 <?php echo $article['bias'] === 'Progressief' ? 'text-emerald-600' : 'text-red-600'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Bron informatie -->
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-br from-emerald-500 to-green-600' : 'bg-gradient-to-br from-red-500 to-red-600'; ?> flex items-center justify-center shadow-md">
                                            <span class="text-white font-black text-sm">
                                                <?php echo strtoupper(substr($article['source'], 0, 2)); ?>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($article['source']); ?></div>
                                            <div class="text-xs text-slate-500">
                                                <?php 
                                                // Nederlandse datum formatting volgens memory
                                                $date = new DateTime($article['publishedAt']);
                                                $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT, null, null, 'd MMM yyyy, HH:mm');
                                                echo $formatter->format($date);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Artikel content -->
                                <div class="flex-grow space-y-4">
                                    <!-- Titel -->
                                    <h3 class="text-xl lg:text-2xl font-black text-slate-900 leading-tight line-clamp-3 <?php echo $article['bias'] === 'Progressief' ? 'group-hover:text-emerald-700' : 'group-hover:text-red-700'; ?> transition-colors duration-300">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h3>
                                    
                                    <!-- Beschrijving -->
                                    <p class="text-slate-600 leading-relaxed line-clamp-4 group-hover:text-slate-700 transition-colors duration-300">
                                        <?php echo htmlspecialchars($article['description']); ?>
                                    </p>
                                </div>
                                
                                <!-- Footer met CTA -->
                                <div class="mt-8 pt-6 border-t border-slate-200/60">
                                    <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="group/btn relative inline-flex items-center justify-center w-full px-6 py-4 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700' : 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700'; ?> text-white font-bold rounded-2xl transition-all duration-300 hover:scale-[1.02] shadow-lg hover:shadow-xl overflow-hidden">
                                        
                                        <!-- Button inhoud -->
                                        <span class="relative z-10 flex items-center">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            Lees volledig artikel
                                            <svg class="w-5 h-5 ml-3 transform transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </span>
                                        
                                        <!-- Glans effect -->
                                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 transition-transform duration-1000 group-hover/btn:translate-y-full"></div>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Decoratieve hoekelementen -->
                            <div class="absolute -top-1 -right-1 w-12 h-12 <?php echo $article['bias'] === 'Progressief' ? 'bg-emerald-500/10' : 'bg-red-500/10'; ?> rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="absolute -bottom-1 -left-1 w-8 h-8 <?php echo $article['bias'] === 'Progressief' ? 'bg-green-500/10' : 'bg-red-500/10'; ?> rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination Section -->
                <?php if (isset($_GET['debug'])): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-8">
                        <strong>Paginering Debug:</strong><br>
                        - $totalPages: <?php echo $totalPages; ?><br>
                        - Conditie ($totalPages > 1): <?php echo ($totalPages > 1) ? 'true' : 'false'; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($totalPages > 1): ?>
                <!-- Modern Pagination -->
                <div class="text-center mt-24" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                    <div class="max-w-4xl mx-auto">
                        <!-- Pagination Header -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">
                                Pagina <?php echo $currentPage; ?> van <?php echo $totalPages; ?>
                            </h3>
                            <p class="text-slate-600">
                                <?php echo $totalArticles; ?> artikelen gevonden in totaal
                            </p>
                        </div>
                        
                        <!-- Main Pagination Container -->
                        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
                            <div class="flex items-center justify-center flex-wrap gap-3">
                                
                                <!-- Vorige Pagina -->
                                <?php if ($currentPage > 1): ?>
                                    <a href="?<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        $params[] = "page=" . ($currentPage - 1);
                                        echo implode('&', $params);
                                    ?>#artikelen" 
                                       class="group flex items-center px-6 py-3 bg-slate-100 hover:bg-primary text-slate-700 hover:text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-semibold">
                                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Vorige
                                    </a>
                                <?php endif; ?>
                                
                                <!-- Pagina Nummers -->
                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                                
                                // Eerste pagina
                                if ($startPage > 1):
                                ?>
                                    <a href="?<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        $params[] = "page=1";
                                        echo implode('&', $params);
                                    ?>#artikelen" 
                                       class="flex items-center justify-center w-12 h-12 text-slate-700 bg-slate-100 hover:bg-primary hover:text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-semibold">
                                        1
                                    </a>
                                    <?php if ($startPage > 2): ?>
                                        <span class="text-slate-400 font-bold px-2">...</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <!-- Huidige range -->
                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        $params[] = "page=$i";
                                        echo implode('&', $params);
                                    ?>#artikelen" 
                                       class="flex items-center justify-center w-12 h-12 <?php echo $i === $currentPage ? 'bg-gradient-to-br from-primary to-primary-dark text-white shadow-lg' : 'text-slate-700 bg-slate-100 hover:bg-primary hover:text-white'; ?> rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-semibold">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <!-- Laatste pagina -->
                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <span class="text-slate-400 font-bold px-2">...</span>
                                    <?php endif; ?>
                                    <a href="?<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        $params[] = "page=$totalPages";
                                        echo implode('&', $params);
                                    ?>#artikelen" 
                                       class="flex items-center justify-center w-12 h-12 text-slate-700 bg-slate-100 hover:bg-primary hover:text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-semibold">
                                        <?php echo $totalPages; ?>
                                    </a>
                                <?php endif; ?>
                                
                                <!-- Volgende Pagina -->
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        $params[] = "page=" . ($currentPage + 1);
                                        echo implode('&', $params);
                                    ?>#artikelen" 
                                       class="group flex items-center px-6 py-3 bg-slate-100 hover:bg-primary text-slate-700 hover:text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-semibold">
                                        Volgende
                                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="mt-8 pt-6 border-t border-slate-200 flex justify-center">
                                <a href="?clear_cache=1<?php 
                                    $params = [];
                                    if ($filter !== 'alle') $params[] = "filter=$filter";
                                    if ($currentPage > 1) $params[] = "page=$currentPage";
                                    echo !empty($params) ? '&' . implode('&', $params) : '';
                                ?>#artikelen" 
                                   class="group inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                                    
                                    <svg class="w-5 h-5 mr-3 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Vernieuw artikelen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- Modern Empty State -->
                <div class="text-center py-32" data-aos="fade-up">
                    <div class="max-w-2xl mx-auto">
                        <!-- Icon Container -->
                        <div class="relative mb-12">
                            <div class="w-32 h-32 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                                <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <!-- Decoratieve elementen -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-slate-200/50 rounded-full"></div>
                            <div class="absolute -bottom-3 -left-3 w-6 h-6 bg-slate-300/50 rounded-full"></div>
                        </div>
                        
                        <!-- Content -->
                        <div class="space-y-6">
                            <h3 class="text-4xl font-black text-slate-900">Geen Artikelen Gevonden</h3>
                            <p class="text-xl text-slate-600 max-w-lg mx-auto leading-relaxed">
                                Er zijn momenteel geen artikelen beschikbaar voor de geselecteerde filter. Probeer een andere selectie of vernieuw de data.
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8">
                                <!-- Terug naar alle artikelen -->
                                <a href="?filter=alle#artikelen" 
                                   class="inline-flex items-center px-8 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Bekijk alle artikelen
                                </a>
                                
                                <!-- Vernieuw data -->
                                <a href="?clear_cache=1" 
                                   class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-3 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Data vernieuwen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Modern Nieuws Styling -->
<style>
/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Utility classes voor line clamping */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth hover animaties */
.hover\:scale-\[1\.02\]:hover {
    transform: scale(1.02);
}

/* Responsive aanpassingen */
@media (max-width: 640px) {
    .grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-4 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .text-4xl {
        font-size: 2rem;
    }
    
    .text-xl {
        font-size: 1.125rem;
    }
}

/* Focus states voor toegankelijkheid */
.focus\:ring-2:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    outline: none;
}

/* Print styling */
@media print {
    .shadow-lg, .shadow-xl, .shadow-2xl {
        box-shadow: none !important;
    }
    
    .bg-gradient-to-r, .bg-gradient-to-br {
        background: #374151 !important;
        color: white !important;
    }
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 