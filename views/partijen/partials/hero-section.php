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
