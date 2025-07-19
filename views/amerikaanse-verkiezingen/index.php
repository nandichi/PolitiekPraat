<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <!-- Modern Responsive Hero Section with BBC Background -->
    <section class="relative min-h-[100svh] md:min-h-screen flex items-center justify-center overflow-hidden py-8 md:py-16">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <!-- BBC Election Data Visualization Background -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://ichef.bbci.co.uk/ace/standard/976/cpsprodpb/9802/production/_133541983_fbg2ani_.png');">
            </div>
            
            <!-- Dark Gradient Overlay for Better Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/95 via-slate-800/90 to-slate-900/95"></div>
            
            <!-- Additional Blue Tint for American Theme -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/30 via-transparent to-red-900/20"></div>
            
            <!-- Subtle Animated Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse" style="animation-duration: 4s;"></div>
        </div>
        
        <!-- Floating Elements for Visual Interest - Hidden on mobile -->
        <div class="absolute top-20 left-10 w-2 h-2 bg-blue-400 rounded-full animate-ping opacity-75 hidden md:block"></div>
        <div class="absolute top-32 right-20 w-1 h-1 bg-red-400 rounded-full animate-ping opacity-60 hidden md:block" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-1/4 w-1.5 h-1.5 bg-white rounded-full animate-ping opacity-40 hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-60 right-1/3 w-1 h-1 bg-blue-300 rounded-full animate-ping opacity-50 hidden md:block" style="animation-delay: 3s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                
                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl xl:text-9xl font-black text-white mb-4 md:mb-8 tracking-tight leading-tight px-2">
                    <span class="block">Amerikaanse</span>
                    <span class="block bg-gradient-to-r from-red-300 via-white to-blue-300 bg-clip-text text-transparent">
                        Verkiezingen
                    </span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-slate-200 mb-3 md:mb-4 font-light leading-relaxed max-w-4xl mx-auto px-4">
                    De complete geschiedenis van de Amerikaanse democratie
                </p>
                
                <!-- Description -->
                <p class="text-sm sm:text-base md:text-lg text-slate-300 mb-6 md:mb-12 leading-relaxed max-w-3xl mx-auto px-4">
                    Van George Washington's eerste overwinning in 1789 tot de moderne digitale campagnes van vandaag. 
                    Ontdek 235 jaar democratische geschiedenis, presidentiële races en politieke mijlpalen die Amerika vormden.
                </p>
                
                <!-- Statistics Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-6 lg:gap-8 mb-6 md:mb-12 max-w-4xl mx-auto px-4">
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            <?= isset($statistieken) ? $statistieken->totaal_verkiezingen : '32' ?>
                        </div>
                        <div class="text-slate-300 font-medium text-sm md:text-base">Verkiezingen</div>
                        <div class="text-xs text-slate-400 mt-1">Sinds 1789</div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            46
                        </div>
                        <div class="text-slate-300 font-medium text-sm md:text-base">Presidenten</div>
                        <div class="text-xs text-slate-400 mt-1">Washington - Biden</div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            235+
                        </div>
                        <div class="text-slate-300 font-medium text-sm md:text-base">Jaar</div>
                        <div class="text-xs text-slate-400 mt-1">Democratie</div>
                    </div>
                </div>
                
                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center items-center px-4">
                    <a href="#verkiezingen-overzicht" 
                       class="w-full sm:w-auto group inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-blue-600 to-red-600 text-white font-bold rounded-full hover:from-blue-700 hover:to-red-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 hover:scale-105">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        <span class="text-sm md:text-base">Verken alle verkiezingen</span>
                        <svg class="ml-2 md:ml-3 w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    
                    <a href="#statistieken" 
                       class="w-full sm:w-auto group inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-full border border-white/20 hover:bg-white/20 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm md:text-base">Bekijk statistieken</span>
                    </a>
                </div>
              </div>
        </div>
    </section>

    <!-- American Landmarks Introduction Section -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-white to-slate-50 relative" id="verkiezingen-overzicht">
        <!-- White House Background -->
        <div class="absolute inset-0 opacity-5">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://media.architecturaldigest.com/photos/6559735fb796d428bef00d25/3:2/w_5568,h_3712,c_limit/GettyImages-1731443210.jpg');"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12 md:mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-100 to-blue-100 rounded-full mb-6">
                        <svg class="w-6 h-6 text-primary mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3l1.09 2.09L15 6l-1.91 1.09L12 9l-1.09-1.91L9 6l1.91-1.09L12 3zm-6 6l.69 1.31L8 11l-1.31.69L6 13l-.69-1.31L4 11l1.31-.69L6 9zm12 0l.69 1.31L20 11l-1.31.69L18 13l-.69-1.31L16 11l1.31-.69L18 9zm-6 6l.69 1.31L14 17l-1.31.69L12 19l-.69-1.31L10 17l1.31-.69L12 15z"/>
                        </svg>
                        <span class="text-primary font-semibold">De geschiedenis van de Amerikaanse democratie</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Van de eerste stem tot de modernste campagnes
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-4xl mx-auto">
                        Vanaf George Washington's unanieme overwinning in 1789 tot de digitale campagnes van vandaag. 
                        Elke verkiezing weerspiegelt de groei, uitdagingen en vooruitgang van de Amerikaanse natie en haar democratische waarden.
                    </p>
                </div>

                <!-- American Timeline Preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="text-center bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Vroege Republiek</h3>
                        <p class="text-gray-600 text-sm">1789-1860</p>
                        <p class="text-gray-500 text-sm mt-2">Van Washington tot Lincoln - de fundamenten van de democratie</p>
                    </div>
                    
                    <div class="text-center bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 14l5-5 5 5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Moderne Era</h3>
                        <p class="text-gray-600 text-sm">1860-1960</p>
                        <p class="text-gray-500 text-sm mt-2">Industrialisatie, wereldoorlogen en sociale verandering</p>
                    </div>
                    
                    <div class="text-center bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Digitale Tijd</h3>
                        <p class="text-gray-600 text-sm">1960-heden</p>
                        <p class="text-gray-500 text-sm mt-2">TV-debatten, internet campagnes en sociale media</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Elections Overview by Period with American styling -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-slate-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <?php if (isset($verkiezingenPerPeriode) && !empty($verkiezingenPerPeriode)): ?>
                    <?php foreach ($verkiezingenPerPeriode as $periode => $verkiezingen): ?>
                        <div class="mb-16">
                            <!-- Period Header with American flair -->
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary rounded-full mb-4">
                                    <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 18H8v-2h4v2zm0-4H8v-2h4v2zm0-4H8V8h4v4z"/>
                                    </svg>
                                    <h3 class="text-2xl md:text-3xl font-bold text-white">
                                        <?= htmlspecialchars($periode) ?>
                                    </h3>
                                </div>
                                
                                <!-- American decorative border -->
                                <div class="flex items-center justify-center">
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-red-300 to-transparent"></div>
                                    <div class="mx-4 flex space-x-1">
                                        <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-blue-300 to-transparent"></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <?php foreach ($verkiezingen as $verkiezing): ?>
                                    <div class="group">
                                        <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/<?= $verkiezing->jaar ?>" 
                                           class="block bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-gray-100 overflow-hidden relative">
                                            
                                            <!-- American flag corner -->
                                            <div class="absolute top-3 right-3 w-8 h-6 bg-gradient-to-r from-red-500 via-white to-blue-500 rounded-sm opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                                            
                                            <!-- Election Year Header with American styling -->
                                            <div class="bg-gradient-to-r from-red-600 via-white to-blue-600 p-1">
                                                <div class="bg-gradient-to-r from-primary to-secondary p-4 text-center">
                                                    <div class="text-2xl font-black text-white mb-1">
                                                        <?= $verkiezing->jaar ?>
                                                    </div>
                                                    <div class="text-sm text-blue-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                                        </svg>
                                                        Presidentsverkiezing
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Winner Info with presidential styling and photo -->
                                            <div class="p-6">
                                                <div class="text-center mb-4">
                                                    <!-- Winner Photo -->
                                                    <?php if (isset($verkiezing->winnaar_foto_url) && !empty($verkiezing->winnaar_foto_url)): ?>
                                                        <div class="w-20 h-20 mx-auto mb-4 relative">
                                                            <img src="<?= htmlspecialchars($verkiezing->winnaar_foto_url) ?>" 
                                                                 alt="<?= htmlspecialchars($verkiezing->winnaar) ?>"
                                                                 class="w-full h-full rounded-full object-cover border-4 border-gradient-to-r from-amber-400 to-yellow-500 shadow-lg group-hover:scale-110 transition-transform duration-300"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <!-- Fallback presidential icon -->
                                                            <div class="w-full h-full bg-gradient-to-r from-amber-400 to-yellow-500 rounded-full flex items-center justify-center absolute top-0 left-0" style="display: none;">
                                                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-20 h-20 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="text-lg font-bold text-gray-900 mb-1">
                                                        <?= htmlspecialchars($verkiezing->winnaar) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mb-3">
                                                        <?= htmlspecialchars($verkiezing->winnaar_partij) ?>
                                                    </div>
                                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                        </svg>
                                                        <?= $verkiezing->winnaar_kiesmannen ?> kiesmannen
                                                    </div>
                                                </div>
                                                
                                                <!-- Quick Stats with American icons -->
                                                <div class="space-y-2 text-center text-sm text-gray-500">
                                                    <div class="flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                        <?= number_format($verkiezing->winnaar_percentage_populair, 1) ?>% van de stemmen
                                                    </div>
                                                    <?php if (isset($verkiezing->opkomst_percentage) && $verkiezing->opkomst_percentage): ?>
                                                        <div class="flex items-center justify-center">
                                                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.99 2.99 0 0018.05 7H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1v-5h0zM12.5 11.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5S11 9.17 11 10s.67 1.5 1.5 1.5zM5.5 6c1.11 0 2-.89 2-2s-.89-2-2-2-2 .89-2 2 .89 2 2 2zm1.5 2h-4c-.83 0-1.5.67-1.5 1.5S2.17 11 3 11h1v8h2v-8h1c.83 0 1.5-.67 1.5-1.5S7.83 8 7 8z"/>
                                                            </svg>
                                                            <?= number_format($verkiezing->opkomst_percentage, 1) ?>% opkomst
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Opponent Info (if photo is available) -->
                                                <?php if (isset($verkiezing->verliezer_foto_url) && !empty($verkiezing->verliezer_foto_url) && isset($verkiezing->verliezer)): ?>
                                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                                        <div class="flex items-center justify-center space-x-3">
                                                            <div class="text-center">
                                                                <div class="w-10 h-10 mx-auto mb-1">
                                                                    <img src="<?= htmlspecialchars($verkiezing->verliezer_foto_url) ?>" 
                                                                         alt="<?= htmlspecialchars($verkiezing->verliezer) ?>"
                                                                         class="w-full h-full rounded-full object-cover border-2 border-gray-300 opacity-60"
                                                                         onerror="this.style.display='none';">
                                                                </div>
                                                                <div class="text-xs text-gray-500"><?= htmlspecialchars($verkiezing->verliezer) ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- View Details Button with American styling -->
                                            <div class="px-6 pb-6">
                                                <div class="w-full text-center py-3 px-4 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-300 border border-gray-200 group-hover:border-transparent">
                                                    <span class="text-sm font-medium flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                                        </svg>
                                                        Bekijk details
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3l1.09 2.09L15 6l-1.91 1.09L12 9l-1.09-1.91L9 6l1.91-1.09L12 3zm-6 6l.69 1.31L8 11l-1.31.69L6 13l-.69-1.31L4 11l1.31-.69L6 9zm12 0l.69 1.31L20 11l-1.31.69L18 13l-.69-1.31L16 11l1.31-.69L18 9zm-6 6l.69 1.31L14 17l-1.31.69L12 19l-.69-1.31L10 17l1.31-.69L12 15z"/>
                            </svg>
                        </div>
                        <div class="text-gray-500 text-lg">
                            Er zijn momenteel geen verkiezingsgegevens beschikbaar.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Enhanced Statistics Section with Capitol building background -->
    <section class="py-16 md:py-20 bg-gradient-to-br from-primary to-secondary relative overflow-hidden" id="statistieken">
        <!-- Capitol building silhouette -->
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/4/4f/US_Capitol_west_side.JPG');"></div>
        </div>
        
        <!-- American flag pattern -->
        <div class="absolute top-0 right-0 w-64 h-64 opacity-5">
            <svg viewBox="0 0 300 200" class="w-full h-full">
                <rect width="300" height="200" fill="url(#flag-pattern)"/>
                <defs>
                    <pattern id="flag-pattern" x="0" y="0" width="60" height="40" patternUnits="userSpaceOnUse">
                        <rect width="60" height="40" fill="#B91C1C"/>
                        <rect y="20" width="60" height="20" fill="white"/>
                    </pattern>
                </defs>
            </svg>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12 md:mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                        <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-white font-semibold">Verkiezingsstatistieken</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                        235 jaar democratische geschiedenis
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                        Ontdek fascinerende cijfers en trends uit meer dan twee eeuwen Amerikaanse presidentsverkiezingen - 
                        van de kleinste staat tot de grootste campagnes, van de laagste tot de hoogste opkomsten.
                    </p>
                </div>
                
                <?php if (isset($statistieken)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-12">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= $statistieken->totaal_verkiezingen ?>
                            </div>
                            <div class="text-blue-100 font-medium">Totaal verkiezingen</div>
                            <div class="text-blue-200 text-sm mt-1">Sinds 1789</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-red-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= $statistieken->republican_overwinningen ?>
                            </div>
                            <div class="text-blue-100 font-medium">Republican overwinningen</div>
                            <div class="text-blue-200 text-sm mt-1">Grand Old Party</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-blue-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= $statistieken->democratic_overwinningen ?>
                            </div>
                            <div class="text-blue-100 font-medium">Democratic overwinningen</div>
                            <div class="text-blue-200 text-sm mt-1">Democratic Party</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-green-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.99 2.99 0 0018.05 7H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1v-5h0zM12.5 11.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5S11 9.17 11 10s.67 1.5 1.5 1.5zM5.5 6c1.11 0 2-.89 2-2s-.89-2-2-2-2 .89-2 2 .89 2 2 2zm1.5 2h-4c-.83 0-1.5.67-1.5 1.5S2.17 11 3 11h1v8h2v-8h1c.83 0 1.5-.67 1.5-1.5S7.83 8 7 8z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= number_format($statistieken->gemiddelde_opkomst, 1) ?>%
                            </div>
                            <div class="text-blue-100 font-medium">Gemiddelde opkomst</div>
                            <div class="text-blue-200 text-sm mt-1">Democratische participatie</div>
                        </div>
                    </div>
                    
                    <!-- Additional American-themed stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 text-center border border-white/10">
                            <div class="text-2xl font-bold text-white mb-1">538</div>
                            <div class="text-blue-100 text-sm">Totaal kiesmannen</div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 text-center border border-white/10">
                            <div class="text-2xl font-bold text-white mb-1">270</div>
                            <div class="text-blue-100 text-sm">Nodig om te winnen</div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 text-center border border-white/10">
                            <div class="text-2xl font-bold text-white mb-1">50</div>
                            <div class="text-blue-100 text-sm">Staten + D.C.</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Enhanced Educational Section with American elements -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-white to-slate-50 relative">
        <!-- Statue of Liberty silhouette -->
        <div class="absolute top-8 right-8 opacity-5 hidden lg:block">
            <svg width="120" height="200" viewBox="0 0 120 200" fill="currentColor" class="text-gray-300">
                <path d="M60 10c-5 0-10 5-10 10v20c0 2 1 4 3 5l-3 15c0 3 2 5 5 5h10c3 0 5-2 5-5l-3-15c2-1 3-3 3-5V20c0-5-5-10-10-10zm0 40l-15 5v120c0 8 6 15 15 15s15-7 15-15V55l-15-5z"/>
            </svg>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Main Educational Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-100 to-blue-100 rounded-full mb-6">
                        <svg class="w-6 h-6 text-primary mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3l1.09 2.09L15 6l-1.91 1.09L12 9l-1.09-1.91L9 6l1.91-1.09L12 3zm-6 6l.69 1.31L8 11l-1.31.69L6 13l-.69-1.31L4 11l1.31-.69L6 9zm12 0l.69 1.31L20 11l-1.31.69L18 13l-.69-1.31L16 11l1.31-.69L18 9zm-6 6l.69 1.31L14 17l-1.31.69L12 19l-.69-1.31L10 17l1.31-.69L12 15z"/>
                        </svg>
                        <span class="text-primary font-semibold">Het Amerikaanse verkiezingssysteem</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        Hoe kiest Amerika haar president?
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                        Een uniek systeem dat al meer dan 235 jaar stand houdt - van kiesmannen tot swing states, 
                        ontdek hoe de Amerikaanse democratie werkt en waarom elke stem telt in dit complexe maar fascinerende proces.
                    </p>
                </div>
                
                <!-- Electoral Process Steps -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="text-center group">
                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">1</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kiesmannen systeem</h3>
                        <p class="text-gray-600 leading-relaxed">
                            538 kiesmannen verdeeld over 50 staten en Washington D.C. Een kandidaat heeft er 270 nodig om president te worden.
                        </p>
                        <div class="mt-4 inline-flex items-center text-sm text-primary font-medium">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Gebaseerd op Congres representatie
                        </div>
                    </div>
                    
                    <div class="text-center group">
                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">2</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Winner-takes-all</h3>
                        <p class="text-gray-600 leading-relaxed">
                            In 48 staten krijgt de winnaar alle kiesmannen van die staat. Alleen Maine en Nebraska verdelen proportioneel.
                        </p>
                        <div class="mt-4 inline-flex items-center text-sm text-primary font-medium">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            Maakt swing states cruciaal
                        </div>
                    </div>
                    
                    <div class="text-center group">
                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2-7H5v2h14V4zM6 19h12v2H6v-2z"/>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">3</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Elke 4 jaar</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Verkiezingen op de eerste dinsdag na de eerste maandag in november. Een traditie sinds de 19e eeuw.
                        </p>
                        <div class="mt-4 inline-flex items-center text-sm text-primary font-medium">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm3.5 6L12 10.5 8.5 8 12 5.5 15.5 8zM8.5 16L12 13.5 15.5 16 12 18.5 8.5 16z"/>
                            </svg>
                            Stabiele democratische cyclus
                        </div>
                    </div>
                </div>

                <!-- American Democracy Timeline -->
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-3xl p-8 md:p-12">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                            Mijlpalen van de Amerikaanse democratie
                        </h3>
                        <p class="text-gray-600">Van beperkte stemrechten tot universeel kiesrecht</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="mb-3">
                                <svg class="w-8 h-8 text-amber-600 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 18H8v-2h4v2zm0-4H8v-2h4v2zm0-4H8V8h4v4z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-gray-900">1789</div>
                            <div class="text-sm text-gray-600">Eerste verkiezing</div>
                        </div>
                        <div class="text-center bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="mb-3">
                                <svg class="w-8 h-8 text-blue-600 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-gray-900">1870</div>
                            <div class="text-sm text-gray-600">15e amendement</div>
                        </div>
                        <div class="text-center bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="mb-3">
                                <svg class="w-8 h-8 text-purple-600 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-gray-900">1920</div>
                            <div class="text-sm text-gray-600">Vrouwenkiesrecht</div>
                        </div>
                        <div class="text-center bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="mb-3">
                                <svg class="w-8 h-8 text-green-600 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-gray-900">1965</div>
                            <div class="text-sm text-gray-600">Voting Rights Act</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 