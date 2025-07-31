<?php require_once 'views/templates/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
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
                        <span class="text-white/90 text-sm font-medium">Politiek kompas tool</span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                        Politiek
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Kompas
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                        Vergelijk standpunten van Nederlandse politieke partijen
                    </p>
                </div>
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo count($data['parties']); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Partijen</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo count($data['themes']); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Thema's</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">150</div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Zetels</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>

    <!-- Main Comparison Tool -->
    <section class="relative -mt-12 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Glassmorphism Container -->
            <div class="relative bg-white/70 backdrop-blur-xl rounded-4xl shadow-2xl border border-white/20 overflow-hidden">
                <!-- Background Effects -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-secondary/5"></div>
                <div class="absolute top-0 left-1/4 w-96 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-24 bg-secondary/10 rounded-full blur-3xl"></div>
                
                <!-- Progress Indicator -->
                <div class="relative z-10 px-6 sm:px-8 lg:px-10 pt-8 pb-6">
                    <div class="flex items-center justify-center mb-8">
                        <div class="flex items-center space-x-4">
                            <!-- Step 1: Partijen -->
                            <div class="flex items-center">
                                <div id="step-1-indicator" class="w-10 h-10 rounded-2xl border-2 border-primary/30 bg-primary/10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="hidden sm:block ml-3 text-sm font-medium text-slate-600">Partijen</span>
                            </div>
                            
                            <div class="w-12 h-px bg-gradient-to-r from-primary/30 to-secondary/30"></div>
                            
                            <!-- Step 2: Thema's -->
                            <div class="flex items-center">
                                <div id="step-2-indicator" class="w-10 h-10 rounded-2xl border-2 border-secondary/30 bg-secondary/10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <span class="hidden sm:block ml-3 text-sm font-medium text-slate-600">Thema's</span>
                            </div>
                            
                            <div class="w-12 h-px bg-gradient-to-r from-secondary/30 to-primary/30"></div>
                            
                            <!-- Step 3: Vergelijken -->
                            <div class="flex items-center">
                                <div id="step-3-indicator" class="w-10 h-10 rounded-2xl border-2 border-slate-300 bg-slate-50 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <span class="hidden sm:block ml-3 text-sm font-medium text-slate-600">Vergelijk</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selection Interface -->
                <div class="relative z-10 px-6 sm:px-8 lg:px-10 pb-8">
                    
                    <!-- Selection Summary Bar -->
                    <div class="mb-8 bg-white/50 backdrop-blur-sm rounded-3xl p-6 border border-white/30">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Selected Parties Display -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-slate-800 flex items-center">
                                        <span class="w-3 h-3 bg-primary rounded-full mr-2"></span>
                                        Geselecteerde Partijen
                                        <span id="party-count" class="ml-2 px-2 py-1 bg-primary/10 text-primary text-sm rounded-full font-medium">0</span>
                                    </h4>
                                    <button id="clear-parties" class="text-sm text-slate-500 hover:text-slate-700 transition-colors hidden">
                                        Wissen
                                    </button>
                                </div>
                                <div id="selected-parties-display" class="flex flex-wrap gap-2 min-h-[2.5rem] items-center">
                                    <span class="text-sm text-slate-500 italic">Geen partijen geselecteerd</span>
                                </div>
                            </div>
                            
                            <!-- Selected Themes Display -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-slate-800 flex items-center">
                                        <span class="w-3 h-3 bg-secondary rounded-full mr-2"></span>
                                        Geselecteerde Thema's
                                        <span id="theme-count" class="ml-2 px-2 py-1 bg-secondary/10 text-secondary text-sm rounded-full font-medium">0</span>
                                    </h4>
                                    <button id="clear-themes" class="text-sm text-slate-500 hover:text-slate-700 transition-colors hidden">
                                        Wissen
                                    </button>
                                </div>
                                <div id="selected-themes-display" class="flex flex-wrap gap-2 min-h-[2.5rem] items-center">
                                    <span class="text-sm text-slate-500 italic">Geen thema's geselecteerd</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Selection Grid -->
                    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
                        
                        <!-- Party Selection -->
                        <div class="space-y-6">
                            <div class="text-center lg:text-left">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-dark rounded-3xl mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">Kies Partijen</h3>
                                <p class="text-slate-600">Selecteer minimaal 2 partijen om te vergelijken</p>
                            </div>
                            
                            <!-- Party Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="party-selector">
                                <?php foreach($data['parties'] as $key => $party): ?>
                                    <div class="party-card group relative bg-white/70 backdrop-blur-sm rounded-2xl border-2 border-slate-200/50 
                                               hover:border-primary/50 hover:shadow-lg cursor-pointer transition-all duration-300 
                                               hover:scale-[1.02] hover:-translate-y-1" 
                                         data-party="<?php echo $key; ?>" 
                                         data-color="<?php echo $party['color']; ?>"
                                         data-name="<?php echo $party['name']; ?>"
                                         data-logo="<?php echo $party['logo']; ?>"
                                         data-seats="<?php echo $party['current_seats']; ?>">
                                        
                                        <!-- Selection Status -->
                                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full border-2 border-white 
                                                   opacity-0 scale-0 transition-all duration-300 flex items-center justify-center party-check">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        
                                        <div class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <img src="<?php echo $party['logo']; ?>" 
                                                         alt="<?php echo $party['name']; ?>" 
                                                         class="w-12 h-12 rounded-xl object-cover border-2 border-slate-200 
                                                               group-hover:border-primary/30 transition-all duration-300"
                                                         onerror="this.style.display='none'">
                                                    <div class="absolute inset-0 bg-primary/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-bold text-slate-800 group-hover:text-primary 
                                                               transition-colors duration-300 text-sm sm:text-base truncate">
                                                        <?php echo $key; ?>
                                                    </div>
                                                    <div class="text-xs text-slate-500 flex items-center">
                                                        <span class="w-2 h-2 rounded-full mr-1" style="background-color: <?php echo $party['color']; ?>"></span>
                                                        <?php echo $party['current_seats']; ?> zetels
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hover Effect -->
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-primary-dark/5 
                                                   opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Theme Selection -->
                        <div class="space-y-6">
                            <div class="text-center lg:text-left">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-secondary to-secondary-light rounded-3xl mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                    <div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">Kies Thema's</h3>
                                        <p class="text-slate-600">Selecteer de onderwerpen voor vergelijking</p>
                                    </div>
                                    <button id="select-all-themes" 
                                            class="group inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-secondary to-secondary-light 
                                                  text-white font-medium rounded-xl shadow-lg hover:shadow-xl 
                                                  transform hover:scale-105 active:scale-95 transition-all duration-300 
                                                  text-sm whitespace-nowrap">
                                        <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-300" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span id="select-all-text">Alle selecteren</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Theme Grid -->
                            <div class="space-y-3" id="theme-selector">
                                <?php foreach($data['themes'] as $key => $theme): ?>
                                    <div class="theme-card group relative bg-white/70 backdrop-blur-sm rounded-2xl border-2 border-slate-200/50 
                                               hover:border-secondary/50 hover:shadow-lg cursor-pointer transition-all duration-300 
                                               hover:scale-[1.02] hover:-translate-y-1" 
                                         data-theme="<?php echo $key; ?>"
                                         data-title="<?php echo $theme['title']; ?>"
                                         data-description="<?php echo $theme['description']; ?>">
                                        
                                        <!-- Selection Status -->
                                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-secondary rounded-full border-2 border-white 
                                                   opacity-0 scale-0 transition-all duration-300 flex items-center justify-center theme-check">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        
                                        <div class="p-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="relative">
                                                    <!-- Icon container with gradient background -->
                                                    <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl 
                                                               flex items-center justify-center shadow-lg group-hover:shadow-xl
                                                               group-hover:from-secondary/20 group-hover:to-secondary/10
                                                               transition-all duration-300 group-hover:scale-110">
                                                        <div class="text-slate-600 group-hover:text-secondary transition-colors duration-300">
                                                            <?php echo $theme['icon']; ?>
                                                        </div>
                                                    </div>
                                                    <!-- Subtle glow effect on hover -->
                                                    <div class="absolute inset-0 rounded-2xl bg-secondary/20 opacity-0 blur-xl
                                                               group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-bold text-slate-800 group-hover:text-secondary 
                                                               transition-colors duration-300 text-base sm:text-lg mb-1">
                                                        <?php echo $theme['title']; ?>
                                                    </div>
                                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                                        <?php echo $theme['description']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hover Effect -->
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-secondary/5 to-secondary-light/5 
                                                   opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Compare Action -->
                    <div class="mt-12 text-center">
                        <div class="inline-flex flex-col items-center space-y-4">
                            <!-- Ready Indicator -->
                            <div id="ready-indicator" class="hidden animate-bounce">
                                <div class="w-3 h-3 bg-gradient-to-r from-primary to-secondary rounded-full"></div>
                            </div>
                            
                            <!-- Compare Button -->
                            <button id="compare-btn" 
                                    class="group relative overflow-hidden px-10 py-5 bg-gradient-to-r from-primary via-secondary to-primary 
                                          text-white font-black rounded-3xl shadow-2xl hover:shadow-3xl 
                                          transform hover:scale-105 active:scale-95 transition-all duration-500 
                                          disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none
                                          disabled:hover:shadow-2xl text-lg">
                                
                                <!-- Animated Background -->
                                <div class="absolute inset-0 bg-gradient-to-r from-primary-dark via-primary to-secondary-light opacity-0 
                                           group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <!-- Button Content -->
                                <div class="relative z-10 flex items-center">
                                    <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-300" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span id="button-text">Vergelijk Standpunten</span>
                                </div>
                                
                                <!-- Shimmer Effect -->
                                <div class="absolute inset-0 -top-1 -left-1 bg-gradient-to-r from-transparent via-white/25 to-transparent 
                                           transform -skew-x-12 translate-x-[-100%] group-hover:translate-x-[200%] transition-transform duration-1000"></div>
                            </button>
                            
                            <!-- Help Text -->
                            <div id="help-text" class="text-sm text-slate-500 max-w-md">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Selecteer minimaal 2 partijen en 1 thema om te beginnen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparison Results -->
                <div id="comparison-results" class="hidden relative z-10">
                    <div class="border-t border-white/20 bg-gradient-to-br from-slate-50/80 to-blue-50/40 backdrop-blur-sm p-6 sm:p-8 lg:p-10">
                        <div id="results-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Compare Section -->
    <section class="py-20 bg-gradient-to-br from-slate-100 to-blue-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-800 mb-6">
                    Snelle Vergelijkingen
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Populaire vergelijkingen om snel inzicht te krijgen in belangrijke verschillen
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Grootste Partijen -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02]">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Grootste Partijen</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Vergelijk de standpunten van PVV, GL-PvdA en VVD op kernthema's
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Immigratie</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Klimaat</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Zorg</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 
                                      text-blue-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="PVV,GL-PvdA,VVD" 
                                data-themes="Immigratie,Klimaat,Zorg">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Links vs Rechts -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02]">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 via-purple-500 to-green-500 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Links vs Rechts</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Ontdek de verschillen tussen linkse en rechtse partijen
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Economie</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Zorg</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Onderwijs</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 
                                      text-purple-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="SP,GL-PvdA,VVD,PVV" 
                                data-themes="Economie,Zorg,Onderwijs">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Klimaat & Duurzaamheid -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02] md:col-span-2 xl:col-span-1">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Klimaat & Energie</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Vergelijk alle partijen op duurzaamheid en energietransitie
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Klimaat</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Energie</span>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Duurzaamheid</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 
                                      text-green-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="GL-PvdA,D66,PvdD,VVD,PVV" 
                                data-themes="Klimaat,Energie,Duurzaamheid">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Modern Glassmorphism and Card Styles */
.rounded-4xl {
    border-radius: 2rem;
}

/* Selection Cards */
.party-card.selected {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(var(--primary), 0.05) 0%, rgba(var(--primary-dark), 0.08) 100%);
    transform: scale(1.02) translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(var(--primary), 0.3);
}

.theme-card.selected {
    border-color: var(--secondary);
    background: linear-gradient(135deg, rgba(var(--secondary), 0.05) 0%, rgba(var(--secondary-light), 0.08) 100%);
    transform: scale(1.02) translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(var(--secondary), 0.3);
}

.party-card.selected .party-check,
.theme-card.selected .theme-check {
    opacity: 1;
    scale: 1;
}

/* Selection Chips/Tags */
.selection-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #475569;
    box-shadow: 0 4px 8px -2px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: slideInScale 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.selection-chip:hover {
    transform: translateY(-1px) scale(1.02);
    box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.15);
}

.selection-chip.party-chip {
    background: linear-gradient(135deg, rgba(var(--primary), 0.15) 0%, rgba(var(--primary), 0.05) 100%);
    border-color: rgba(var(--primary), 0.3);
    color: var(--primary-dark);
}

.selection-chip.theme-chip {
    background: linear-gradient(135deg, rgba(var(--secondary), 0.15) 0%, rgba(var(--secondary), 0.05) 100%);
    border-color: rgba(var(--secondary), 0.3);
    color: var(--secondary-dark);
}

.selection-chip .chip-logo {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 0.375rem;
    margin-right: 0.5rem;
    object-fit: cover;
}

.selection-chip .chip-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%);
    padding: 0.125rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.selection-chip .chip-icon svg {
    width: 100%;
    height: 100%;
    filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.1));
}

.selection-chip.party-chip .chip-icon {
    background: linear-gradient(135deg, rgba(var(--primary), 0.2) 0%, rgba(var(--primary), 0.1) 100%);
}

.selection-chip.theme-chip .chip-icon {
    background: linear-gradient(135deg, rgba(var(--secondary), 0.2) 0%, rgba(var(--secondary), 0.1) 100%);
}

.selection-chip .chip-remove {
    margin-left: 0.5rem;
    padding: 0.125rem;
    border-radius: 0.25rem;
    color: #64748b;
    transition: all 0.2s ease;
    cursor: pointer;
}

.selection-chip .chip-remove:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #ef4444;
}

/* Progress Indicators */
.step-indicator.active {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-color: var(--primary);
    box-shadow: 0 4px 12px -4px rgba(var(--primary), 0.4);
}

.step-indicator.active svg {
    color: white;
}

.step-indicator.theme.active {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
    border-color: var(--secondary);
    box-shadow: 0 4px 12px -4px rgba(var(--secondary), 0.4);
}

.step-indicator.completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    box-shadow: 0 4px 12px -4px rgba(16, 185, 129, 0.4);
}

.step-indicator.completed svg {
    color: white;
}

/* Enhanced Button Animations */
.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

#compare-btn:disabled {
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    cursor: not-allowed;
    transform: none !important;
}

#compare-btn:not(:disabled):hover .shimmer-effect {
    animation: shimmer 1.5s ease-in-out;
}

/* Animations */
@keyframes slideInScale {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%) skewX(-12deg);
    }
    100% {
        transform: translateX(200%) skewX(-12deg);
    }
}

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

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 8px rgba(var(--primary), 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(var(--primary), 0.6);
    }
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced loading states */
.loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.loading-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    background-size: 200% 100%;
    animation: shimmer-loading 1.5s infinite;
}

@keyframes shimmer-loading {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Slide animations for results */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px) scaleY(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scaleY(1);
    }
}

@keyframes slideUp {
    from {
        opacity: 1;
        transform: translateY(0) scaleY(1);
    }
    to {
        opacity: 0;
        transform: translateY(-10px) scaleY(0.95);
    }
}

/* Details container styling */
.details-container {
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.details-container.expanding {
    animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.details-container.collapsing {
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Feasibility badges */
.feasibility-badge {
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.feasibility-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.feasibility-haalbaar {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #10b981;
}

.feasibility-gedeeltelijk {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #f59e0b;
}

.feasibility-moeilijk {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #ef4444;
}

/* Enhanced responsive design */
@media (max-width: 640px) {
    .text-4xl { font-size: 2.5rem; }
    .text-5xl { font-size: 3rem; }
    .text-6xl { font-size: 3.5rem; }
    
    .selection-chip {
        font-size: 0.75rem;
        padding: 0.375rem 0.625rem;
    }
    
    .selection-chip .chip-logo {
        width: 1rem;
        height: 1rem;
    }
}

/* Smooth staggered animations */
.party-card, .theme-card {
    animation: fadeInUp 0.6s ease-out backwards;
}

.party-card:nth-child(1) { animation-delay: 0.05s; }
.party-card:nth-child(2) { animation-delay: 0.1s; }
.party-card:nth-child(3) { animation-delay: 0.15s; }
.party-card:nth-child(4) { animation-delay: 0.2s; }
.party-card:nth-child(5) { animation-delay: 0.25s; }
.party-card:nth-child(6) { animation-delay: 0.3s; }

.theme-card:nth-child(1) { animation-delay: 0.1s; }
.theme-card:nth-child(2) { animation-delay: 0.15s; }
.theme-card:nth-child(3) { animation-delay: 0.2s; }
.theme-card:nth-child(4) { animation-delay: 0.25s; }
.theme-card:nth-child(5) { animation-delay: 0.3s; }
.theme-card:nth-child(6) { animation-delay: 0.35s; }

/* Enhanced SVG icon styling */
.theme-card svg {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.theme-card:hover svg {
    transform: scale(1.05);
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

/* Icon container hover effects */
.theme-card .relative:hover {
    animation: subtle-pulse 2s ease-in-out infinite;
}

@keyframes subtle-pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

/* Enhanced icon glow on selection */
.theme-card.selected svg {
    filter: drop-shadow(0 0 8px rgba(var(--secondary), 0.4));
}

/* Focus and accessibility improvements */
.party-card:focus-visible,
.theme-card:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

.selection-chip:focus-visible {
    outline: 2px solid var(--secondary);
    outline-offset: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const parties = <?php echo json_encode($data['parties']); ?>;
    const themes = <?php echo json_encode($data['themes']); ?>;
    
    // UI Elements
    const compareBtn = document.getElementById('compare-btn');
    const resultsDiv = document.getElementById('comparison-results');
    const resultsContent = document.getElementById('results-content');
    const buttonText = document.getElementById('button-text');
    const helpText = document.getElementById('help-text');
    const readyIndicator = document.getElementById('ready-indicator');
    
    // Selection displays
    const partyCountEl = document.getElementById('party-count');
    const themeCountEl = document.getElementById('theme-count');
    const selectedPartiesDisplay = document.getElementById('selected-parties-display');
    const selectedThemesDisplay = document.getElementById('selected-themes-display');
    const clearPartiesBtn = document.getElementById('clear-parties');
    const clearThemesBtn = document.getElementById('clear-themes');
    
    // Progress indicators
    const step1Indicator = document.getElementById('step-1-indicator');
    const step2Indicator = document.getElementById('step-2-indicator');
    const step3Indicator = document.getElementById('step-3-indicator');
    
    // Selection state
    let selectedParties = new Set();
    let selectedThemes = new Set();
    
    // Card selection handlers
    function initializeSelectionHandlers() {
        // Party card selection
        document.querySelectorAll('.party-card').forEach(card => {
            card.addEventListener('click', function() {
                const partyKey = this.dataset.party;
                
                if (selectedParties.has(partyKey)) {
                    selectedParties.delete(partyKey);
                    this.classList.remove('selected');
                } else {
                    selectedParties.add(partyKey);
                    this.classList.add('selected');
                }
                
                updateUI();
            });
            
            // Keyboard support
            card.setAttribute('tabindex', '0');
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        // Theme card selection
        document.querySelectorAll('.theme-card').forEach(card => {
            card.addEventListener('click', function() {
                const themeKey = this.dataset.theme;
                
                if (selectedThemes.has(themeKey)) {
                    selectedThemes.delete(themeKey);
                    this.classList.remove('selected');
                } else {
                    selectedThemes.add(themeKey);
                    this.classList.add('selected');
                }
                
                updateUI();
            });
            
            // Keyboard support
            card.setAttribute('tabindex', '0');
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    }
    
    // Create selection chip
    function createSelectionChip(type, key, data) {
        const chip = document.createElement('div');
        chip.className = `selection-chip ${type}-chip`;
        chip.dataset.key = key;
        
        if (type === 'party') {
            chip.innerHTML = `
                <img src="${data.logo}" alt="${data.name}" class="chip-logo" onerror="this.style.display='none'">
                <span>${key}</span>
                <svg class="chip-remove w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            `;
        } else {
            chip.innerHTML = `
                <span class="chip-icon w-4 h-4 inline-flex items-center justify-center">${themes[key].icon}</span>
                <span>${data.title}</span>
                <svg class="chip-remove w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            `;
        }
        
        // Add remove functionality
        chip.querySelector('.chip-remove').addEventListener('click', function(e) {
            e.stopPropagation();
            
            if (type === 'party') {
                selectedParties.delete(key);
                document.querySelector(`[data-party="${key}"]`).classList.remove('selected');
            } else {
                selectedThemes.delete(key);
                document.querySelector(`[data-theme="${key}"]`).classList.remove('selected');
            }
            
            updateUI();
        });
        
        return chip;
    }
    
    // Update UI based on current selection
    function updateUI() {
        updateSelectionDisplay();
        updateProgressIndicators();
        updateCompareButton();
        updateHelpText();
        updateClearButtons();
    }
    
    // Update selection display chips
    function updateSelectionDisplay() {
        // Update party count and display
        partyCountEl.textContent = selectedParties.size;
        selectedPartiesDisplay.innerHTML = '';
        
        if (selectedParties.size === 0) {
            selectedPartiesDisplay.innerHTML = '<span class="text-sm text-slate-500 italic">Geen partijen geselecteerd</span>';
        } else {
            selectedParties.forEach(partyKey => {
                const chip = createSelectionChip('party', partyKey, parties[partyKey]);
                selectedPartiesDisplay.appendChild(chip);
            });
        }
        
        // Update theme count and display
        themeCountEl.textContent = selectedThemes.size;
        selectedThemesDisplay.innerHTML = '';
        
        if (selectedThemes.size === 0) {
            selectedThemesDisplay.innerHTML = '<span class="text-sm text-slate-500 italic">Geen thema\'s geselecteerd</span>';
        } else {
            selectedThemes.forEach(themeKey => {
                const chip = createSelectionChip('theme', themeKey, themes[themeKey]);
                selectedThemesDisplay.appendChild(chip);
            });
        }
    }
    
    // Update progress indicators
    function updateProgressIndicators() {
        // Step 1: Partijen
        if (selectedParties.size >= 2) {
            step1Indicator.classList.add('completed');
            step1Indicator.classList.remove('active');
        } else if (selectedParties.size > 0) {
            step1Indicator.classList.add('active');
            step1Indicator.classList.remove('completed');
        } else {
            step1Indicator.classList.remove('active', 'completed');
        }
        
        // Step 2: Thema's
        if (selectedThemes.size >= 1) {
            step2Indicator.classList.add('completed', 'theme');
            step2Indicator.classList.remove('active');
        } else {
            step2Indicator.classList.remove('completed');
            step2Indicator.classList.add('active', 'theme');
        }
        
        // Step 3: Vergelijken
        const canCompare = selectedParties.size >= 2 && selectedThemes.size >= 1;
        if (canCompare) {
            step3Indicator.classList.add('completed');
            step3Indicator.classList.remove('active');
            readyIndicator.classList.remove('hidden');
        } else {
            step3Indicator.classList.remove('completed');
            readyIndicator.classList.add('hidden');
        }
    }
    
    // Update compare button
    function updateCompareButton() {
        const canCompare = selectedParties.size >= 2 && selectedThemes.size >= 1;
        compareBtn.disabled = !canCompare;
        
        if (canCompare) {
            buttonText.textContent = `Vergelijk ${selectedParties.size} partijen op ${selectedThemes.size} thema${selectedThemes.size === 1 ? '' : 's'}`;
        } else {
            buttonText.textContent = 'Vergelijk Standpunten';
        }
    }
    
    // Update help text
    function updateHelpText() {
        const partiesNeeded = Math.max(0, 2 - selectedParties.size);
        const themesNeeded = Math.max(0, 1 - selectedThemes.size);
        
        let message = '';
        if (partiesNeeded > 0 && themesNeeded > 0) {
            message = `Selecteer nog ${partiesNeeded} partij${partiesNeeded === 1 ? '' : 'en'} en ${themesNeeded} thema om te beginnen`;
        } else if (partiesNeeded > 0) {
            message = `Selecteer nog ${partiesNeeded} partij${partiesNeeded === 1 ? '' : 'en'} om te beginnen`;
        } else if (themesNeeded > 0) {
            message = `Selecteer nog ${themesNeeded} thema om te beginnen`;
        } else {
            message = 'Alles klaar! Klik op de knop om te vergelijken';
        }
        
        const helpSpan = helpText.querySelector('span');
        if (helpSpan) {
            helpSpan.textContent = message;
        }
    }
    
    // Update clear buttons visibility
    function updateClearButtons() {
        clearPartiesBtn.classList.toggle('hidden', selectedParties.size === 0);
        clearThemesBtn.classList.toggle('hidden', selectedThemes.size === 0);
    }
    
    // Clear button handlers
    clearPartiesBtn.addEventListener('click', function() {
        selectedParties.clear();
        document.querySelectorAll('.party-card.selected').forEach(card => {
            card.classList.remove('selected');
        });
        updateUI();
    });
    
    clearThemesBtn.addEventListener('click', function() {
        selectedThemes.clear();
        document.querySelectorAll('.theme-card.selected').forEach(card => {
            card.classList.remove('selected');
        });
        updateUI();
    });
    
    // Select all themes functionality
    const selectAllThemesBtn = document.getElementById('select-all-themes');
    const selectAllText = document.getElementById('select-all-text');
    
    selectAllThemesBtn.addEventListener('click', function() {
        const totalThemes = Object.keys(themes).length;
        const allSelected = selectedThemes.size === totalThemes;
        
        if (allSelected) {
            // Deselect all
            selectedThemes.clear();
            document.querySelectorAll('.theme-card.selected').forEach(card => {
                card.classList.remove('selected');
            });
            selectAllText.textContent = 'Alle selecteren';
        } else {
            // Select all
            Object.keys(themes).forEach(themeKey => {
                selectedThemes.add(themeKey);
                document.querySelector(`[data-theme="${themeKey}"]`).classList.add('selected');
            });
            selectAllText.textContent = 'Alle deselecteren';
        }
        
        updateUI();
    });
    
    // Update select all button text
    function updateSelectAllButton() {
        const totalThemes = Object.keys(themes).length;
        const allSelected = selectedThemes.size === totalThemes;
        selectAllText.textContent = allSelected ? 'Alle deselecteren' : 'Alle selecteren';
    }
    
    // Quick compare functionality
    document.querySelectorAll('.quick-compare').forEach(button => {
        button.addEventListener('click', function() {
            const quickParties = this.dataset.parties.split(',');
            const quickThemes = this.dataset.themes.split(',');
            
            // Clear current selections
            selectedParties.clear();
            selectedThemes.clear();
            document.querySelectorAll('.party-card.selected, .theme-card.selected').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Set new selections
            quickParties.forEach(party => {
                if (parties[party]) {
                    selectedParties.add(party);
                    document.querySelector(`[data-party="${party}"]`)?.classList.add('selected');
                }
            });
            
            quickThemes.forEach(theme => {
                if (themes[theme]) {
                    selectedThemes.add(theme);
                    document.querySelector(`[data-theme="${theme}"]`)?.classList.add('selected');
                }
            });
            
            updateUI();
            
            // Trigger comparison
            setTimeout(() => {
                performComparison();
                
                // Smooth scroll to comparison tool
                document.querySelector('#comparison-results').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
            }, 300);
        });
    });
    
    // Perform comparison
    function performComparison() {
        if (selectedParties.size < 2 || selectedThemes.size < 1) {
            return;
        }
        
        // Show loading state
        buttonText.textContent = 'Vergelijking laden...';
        compareBtn.disabled = true;
        compareBtn.classList.add('loading-shimmer');
        
        setTimeout(() => {
            let html = '<div class="space-y-8">';
            
            const selectedThemesList = Array.from(selectedThemes);
            const selectedPartiesList = Array.from(selectedParties);
            
            selectedThemesList.forEach((themeKey, themeIndex) => {
                const theme = themes[themeKey];
                html += `
                    <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-3xl p-6 sm:p-8 border border-slate-200/50 
                             shadow-lg hover:shadow-xl transition-all duration-300" 
                         style="animation: fadeInUp 0.6s ease-out backwards; animation-delay: ${themeIndex * 100}ms">
                        <div class="flex flex-col sm:flex-row sm:items-center mb-8 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-4">
                                <div class="relative w-20 h-20 bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 rounded-3xl 
                                           flex items-center justify-center shadow-2xl border border-white/20">
                                    <div class="w-10 h-10 text-white filter drop-shadow-lg">${theme.icon}</div>
                                    <!-- Subtle inner glow -->
                                    <div class="absolute inset-1 rounded-2xl bg-white/10 blur-sm"></div>
                                </div>
                                <div>
                                    <h3 class="text-2xl sm:text-3xl font-bold text-slate-800">${theme.title}</h3>
                                    <p class="text-slate-600 text-sm sm:text-base">${theme.description}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid gap-4 lg:gap-6">
                `;
                
                selectedPartiesList.forEach((partyKey, partyIndex) => {
                    const party = parties[partyKey];
                    const standpoint = party.standpoints[themeKey];
                    
                    // Check if standpoint is in new format (object) or old format (string)
                    const isNewFormat = standpoint && typeof standpoint === 'object';
                    const summary = isNewFormat ? standpoint.summary : (standpoint || 'Geen specifiek standpunt bekend voor dit thema.');
                    const details = isNewFormat ? standpoint.details : null;
                    const feasibility = isNewFormat ? standpoint.feasibility : null;
                    
                    const uniqueId = `${themeKey}_${partyKey}_${Date.now()}_${Math.random()}`.replace(/[^a-zA-Z0-9]/g, '');
                    
                    html += `
                        <div class="bg-white rounded-2xl p-4 sm:p-6 border-l-4 shadow-md hover:shadow-lg 
                                   transition-all duration-300 transform hover:scale-[1.01]" 
                             style="border-left-color: ${party.color}; animation: fadeInUp 0.6s ease-out backwards; animation-delay: ${(themeIndex * selectedPartiesList.length + partyIndex) * 50}ms">
                            <div class="flex flex-col sm:flex-row sm:items-start space-y-3 sm:space-y-0 sm:space-x-4">
                                <img src="${party.logo}" alt="${party.name}" 
                                     class="w-14 h-14 rounded-xl object-cover border-2 border-slate-200 flex-shrink-0 mx-auto sm:mx-0"
                                     onerror="this.style.display='none'">
                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-lg">${party.name}</h4>
                                            <div class="flex items-center justify-center sm:justify-start space-x-2 text-sm text-slate-500 mt-1">
                                                <span>${party.current_seats} zetels</span>
                                                <span class="w-2 h-2 rounded-full" style="background-color: ${party.color}"></span>
                                                ${feasibility ? `<span class="px-2 py-1 rounded-full text-xs font-medium ${getFeasibilityColor(feasibility.score)}">${feasibility.score}</span>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prose prose-sm max-w-none">
                                        <p class="text-slate-700 leading-relaxed text-sm sm:text-base">${summary}</p>
                                        
                                        ${details ? `
                                            <div id="details_${uniqueId}" class="details-container hidden mt-4 border-t border-slate-200 pt-4">
                                                <div class="space-y-4">
                                                    <div>
                                                        <h5 class="font-semibold text-slate-800 mb-2 flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Gedetailleerde plannen
                                                        </h5>
                                                        <p class="text-slate-600 text-sm leading-relaxed">${details}</p>
                                                    </div>
                                                    
                                                    ${feasibility ? `
                                                        <div class="bg-slate-50 rounded-xl p-4">
                                                            <h5 class="font-semibold text-slate-800 mb-3 flex items-center">
                                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Haalbaarheidsanalyse
                                                            </h5>
                                                            <div class="grid sm:grid-cols-2 gap-3 text-sm">
                                                                <div>
                                                                    <span class="font-medium text-slate-700">Realiseerbaarheid:</span>
                                                                    <p class="text-slate-600 mt-1">${feasibility.explanation}</p>
                                                                </div>
                                                                <div>
                                                                    <span class="font-medium text-slate-700">Kosten/Baten:</span>
                                                                    <p class="text-slate-600 mt-1">${feasibility.costs}</p>
                                                                </div>
                                                                <div class="sm:col-span-2">
                                                                    <span class="font-medium text-slate-700">Tijdslijn:</span>
                                                                    <p class="text-slate-600 mt-1">${feasibility.timeline}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ` : ''}
                                                </div>
                                            </div>
                                            
                                            <button onclick="toggleDetails('${uniqueId}')" 
                                                    class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 
                                                           hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                                <span id="toggle_text_${uniqueId}">Lees meer</span>
                                                <svg id="toggle_icon_${uniqueId}" class="w-4 h-4 ml-1 transform transition-transform duration-200" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            resultsContent.innerHTML = html;
            resultsDiv.classList.remove('hidden');
            
            // Reset button
            compareBtn.classList.remove('loading-shimmer');
            updateCompareButton();
            
            // Smooth scroll to results
            setTimeout(() => {
                resultsDiv.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
            }, 100);
            
        }, 500);
    }
    
    // Compare button click
    compareBtn.addEventListener('click', performComparison);
    
    // Initialize
    initializeSelectionHandlers();
    updateUI();
    updateSelectAllButton();
    
    // Helper functions
    window.getFeasibilityColor = function(score) {
        switch(score.toLowerCase()) {
            case 'haalbaar': 
                return 'feasibility-haalbaar feasibility-badge';
            case 'gedeeltelijk': 
                return 'feasibility-gedeeltelijk feasibility-badge';
            case 'moeilijk': 
                return 'feasibility-moeilijk feasibility-badge';
            default: 
                return 'bg-gray-100 text-gray-800 feasibility-badge';
        }
    };
    
    window.toggleDetails = function(uniqueId) {
        const detailsDiv = document.getElementById(`details_${uniqueId}`);
        const toggleText = document.getElementById(`toggle_text_${uniqueId}`);
        const toggleIcon = document.getElementById(`toggle_icon_${uniqueId}`);
        
        // Prevent multiple rapid clicks
        if (detailsDiv.dataset.animating === 'true') {
            return;
        }
        
        detailsDiv.dataset.animating = 'true';
        
        if (detailsDiv.classList.contains('hidden')) {
            // Expanding
            detailsDiv.classList.remove('hidden');
            detailsDiv.classList.remove('collapsing');
            detailsDiv.classList.add('expanding');
            
            // Force reflow to ensure the element is visible before animation
            detailsDiv.offsetHeight;
            
            toggleText.textContent = 'Lees minder';
            toggleIcon.style.transform = 'rotate(180deg)';
            
            // Clean up after animation
            setTimeout(() => {
                detailsDiv.classList.remove('expanding');
                detailsDiv.dataset.animating = 'false';
            }, 300);
            
        } else {
            // Collapsing
            detailsDiv.classList.remove('expanding');
            detailsDiv.classList.add('collapsing');
            
            toggleText.textContent = 'Lees meer';
            toggleIcon.style.transform = 'rotate(0deg)';
            
            // Hide after animation completes
            setTimeout(() => {
                detailsDiv.classList.add('hidden');
                detailsDiv.classList.remove('collapsing');
                detailsDiv.dataset.animating = 'false';
            }, 300);
        }
    };
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 