<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Modern Responsive Hero Section with Dutch Parliament Background -->
    <section class="relative min-h-[100svh] md:min-h-screen flex items-center justify-center overflow-hidden py-8 md:py-16">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <!-- Dutch Parliament (Binnenhof) Background -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Den_Haag_Binnenhof_Hofvijver.jpg/640px-Den_Haag_Binnenhof_Hofvijver.jpg');">
            </div>
            
            <!-- Dark Gradient Overlay for Better Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/95 via-slate-800/90 to-slate-900/95"></div>
            
            <!-- Dutch Flag Tint for Nederlandse Theme -->
            <div class="absolute inset-0 bg-gradient-to-br from-orange-900/40 via-transparent to-blue-900/30"></div>
            
            <!-- Oranje Animated Overlay - Nederlandse accent -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-orange-500/10 to-transparent animate-pulse" style="animation-duration: 5s;"></div>
        </div>
        
        <!-- Nederlandse Crown and Lion Elements - Hidden on mobile -->
        <div class="absolute top-20 left-10 w-3 h-3 bg-orange-500 rounded-full animate-ping opacity-75 hidden md:block"></div>
        <div class="absolute top-32 right-20 w-2 h-2 bg-blue-600 rounded-full animate-ping opacity-60 hidden md:block" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-1/4 w-2 h-2 bg-orange-400 rounded-full animate-ping opacity-40 hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-60 right-1/3 w-1.5 h-1.5 bg-white rounded-full animate-ping opacity-50 hidden md:block" style="animation-delay: 3s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                
                <!-- Nederlandse Kroon Symbol -->
                <div class="mb-6 flex justify-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/20">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Main Heading with Nederlandse styling -->
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl xl:text-9xl font-black text-white mb-4 md:mb-8 tracking-tight leading-tight px-2">
                    <span class="block">Nederlandse</span>
                    <span class="block bg-gradient-to-r from-orange-300 via-white to-blue-300 bg-clip-text text-transparent">
                        Verkiezingen
                    </span>
                </h1>
                
                <!-- Nederlandse subtitle -->
                <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-slate-200 mb-3 md:mb-4 font-light leading-relaxed max-w-4xl mx-auto px-4">
                    175 jaar Nederlandse democratie en parlementaire geschiedenis
                </p>
                
                <!-- Description with Nederlandse context -->
                <p class="text-sm sm:text-base md:text-lg text-slate-300 mb-6 md:mb-12 leading-relaxed max-w-3xl mx-auto px-4">
                    Van Thorbeckiaanse grondwet van 1848 tot de moderne coalitiekabinetten van vandaag. 
                    Ontdek hoe Nederland werd uitgebouwd tot een van 's werelds meest stabiele democratieën.
                </p>
                
                <!-- Statistics Row with Nederlandse elements -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-6 lg:gap-8 mb-6 md:mb-12 max-w-4xl mx-auto px-4">
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-orange-500/20 hover:bg-orange-500/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            <?= isset($statistieken) ? $statistieken->totaal_verkiezingen : '25' ?>
                        </div>
                        <div class="text-orange-200 font-medium text-sm md:text-base">Verkiezingen</div>
                        <div class="text-xs text-orange-300 mt-1">Tweede Kamer</div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-blue-500/20 hover:bg-blue-500/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            <?= isset($statistieken) ? $statistieken->totaal_premiers : '15' ?>
                        </div>
                        <div class="text-blue-200 font-medium text-sm md:text-base">Ministers-presidenten</div>
                        <div class="text-xs text-blue-300 mt-1">Sinds 1848</div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/20 hover:bg-white/10 transition-all duration-300 group">
                        <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                            175+
                        </div>
                        <div class="text-slate-300 font-medium text-sm md:text-base">Jaar</div>
                        <div class="text-xs text-slate-400 mt-1">Nederlandse Democratie</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Dutch Democracy History Section -->
    <section class="py-20 md:py-28 bg-gradient-to-br from-primary-dark via-primary to-secondary relative overflow-hidden" id="verkiezingen-overzicht">
        <!-- Dynamic Background Elements with Nederlandse touches -->
        <div class="absolute inset-0">
            <!-- Dutch Parliament silhouette -->
            <div class="absolute inset-0 opacity-[0.03]">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Tweede_Kamer_der_Staten-Generaal.jpg/640px-Tweede_Kamer_der_Staten-Generaal.jpg');"></div>
            </div>
            
            <!-- Nederlandse geometric patterns with flag colors -->
            <div class="absolute top-0 left-0 w-96 h-96 opacity-10 -translate-x-48 -translate-y-48 rotate-45">
                <div class="w-full h-full bg-gradient-to-br from-orange-500/30 to-blue-500/20 rounded-3xl backdrop-blur-3xl"></div>
            </div>
            <div class="absolute bottom-0 right-0 w-80 h-80 opacity-10 translate-x-40 translate-y-40 -rotate-45">
                <div class="w-full h-full bg-gradient-to-tl from-orange-400/20 to-white/15 rounded-3xl backdrop-blur-3xl"></div>
            </div>
            
            <!-- Nederlandse vlag kleuren animated elements -->
            <div class="absolute top-20 left-20 w-3 h-3 bg-orange-500 rounded-full animate-ping opacity-40" style="animation-delay: 0s; animation-duration: 3s;"></div>
            <div class="absolute top-32 right-32 w-2 h-2 bg-white rounded-full animate-ping opacity-60" style="animation-delay: 1s; animation-duration: 4s;"></div>
            <div class="absolute bottom-40 left-1/4 w-2 h-2 bg-blue-600 rounded-full animate-ping opacity-50" style="animation-delay: 2s; animation-duration: 5s;"></div>
            <div class="absolute bottom-60 right-1/3 w-3 h-3 bg-orange-400 rounded-full animate-ping opacity-30" style="animation-delay: 3s; animation-duration: 3.5s;"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Enhanced Header Section with Nederlandse styling -->
                <div class="text-center mb-16 md:mb-20">
                    <!-- Nederlandse kroon decoratie -->
                    <div class="flex justify-center mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center border-2 border-white/30">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                </svg>
                            </div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                        </div>
                    </div>
                    
                    <!-- Main title with Nederlandse gradient -->
                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-8 leading-tight">
                        <span class="block text-white mb-2">Van grondwettelijke</span>
                        <span class="block bg-gradient-to-r from-orange-300 via-white to-blue-300 bg-clip-text text-transparent">
                            monarchie tot moderne
                        </span>
                        <span class="block text-white">parlementaire democratie</span>
                    </h2>
                    
                    <!-- Enhanced description with Nederlandse context -->
                    <div class="max-w-5xl mx-auto">
                        <p class="text-xl md:text-2xl text-blue-100 leading-relaxed mb-6 px-4">
                            Een unieke reis door Nederlandse democratische geschiedenis - van Thorbeckiaanse liberalisering 
                            tot moderne coalitievorming en digitale politiek.
                        </p>
                        <p class="text-lg text-blue-200 leading-relaxed px-4">
                            Elke verkiezing weerspiegelt de Nederlandse zoektocht naar consensus, verdraagzaamheid en 
                            bestuurlijke stabiliteit in een gepolariseerde wereld.
                        </p>
                    </div>
                </div>

                <!-- Revolutionary Timeline Cards with Nederlandse theming -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Constitutional Period -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border-2 border-orange-500/30 shadow-2xl hover:shadow-orange-500/20 transition-all duration-500 transform hover:-translate-y-4 hover:rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator with Nederlandse kleuren -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg border border-white/20">
                                <span class="text-white font-bold text-sm">1848-1917</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Constitutional icon with Nederlandse styling -->
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-3 group-hover:rotate-6 border-2 border-white/20">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 18H8v-2h4v2zm0-4H8v-2h4v2zm0-4H8V8h4v4z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Grondwettelijke Era
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-orange-500/20 rounded-full border border-orange-400/30">
                                        <svg class="w-4 h-4 text-orange-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="text-orange-200 text-sm font-semibold">Liberale basis</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    Thorbeckiaanse grondwet legt basis voor moderne Nederlandse democratie. Van monarchale macht naar 
                                    ministeriële verantwoordelijkheid en uitbreiding van het kiesrecht.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                    <?php if (isset($keyFiguresPresidenten['vroege_democratie'])): ?>
                                        <?php foreach ($keyFiguresPresidenten['vroege_democratie'] as $premier): ?>
                                            <div class="relative group/avatar">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-orange-400/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                    <?php if (isset($premier->foto_url) && !empty($premier->foto_url)): ?>
                                                        <img src="<?= htmlspecialchars($premier->foto_url) ?>" 
                                                             alt="<?= htmlspecialchars($premier->naam) ?>"
                                                             class="w-full h-full object-cover"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="w-full h-full bg-gradient-to-r from-orange-400 to-orange-500 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-gradient-to-r from-orange-400 to-orange-500 flex items-center justify-center text-xs font-bold text-white">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- Tooltip on hover -->
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                    <?= htmlspecialchars($premier->naam) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Fallback initialen with Nederlandse kleuren -->
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">T</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">K</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">C</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pillarization Era with Nederlandse styling -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border-2 border-blue-500/30 shadow-2xl hover:shadow-blue-500/20 transition-all duration-500 transform hover:-translate-y-4 hover:-rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full shadow-lg border border-white/20">
                                <span class="text-white font-bold text-sm">1918-1980</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 -rotate-3 group-hover:-rotate-6 border-2 border-white/20">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 9.74s9-4.19 9-9.74V7l-10-5z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Nederlandse Verzuiling
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-blue-500/20 rounded-full border border-blue-400/30">
                                        <svg class="w-4 h-4 text-blue-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                                        </svg>
                                        <span class="text-blue-200 text-sm font-semibold">Sociale cohesie</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    Katholieke, protestantse, socialistische en liberale zuilen domineren de Nederlandse politiek. 
                                    Pacificatie, wederopbouw en welvaartsstaat worden geboren.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                    <?php if (isset($keyFiguresPresidenten['wederopbouw_verzuiling'])): ?>
                                        <?php foreach ($keyFiguresPresidenten['wederopbouw_verzuiling'] as $premier): ?>
                                            <div class="relative group/avatar">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-blue-400/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                    <?php if (isset($premier->foto_url) && !empty($premier->foto_url)): ?>
                                                        <img src="<?= htmlspecialchars($premier->foto_url) ?>" 
                                                             alt="<?= htmlspecialchars($premier->naam) ?>"
                                                             class="w-full h-full object-cover"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- Tooltip on hover -->
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                    <?= htmlspecialchars($premier->naam) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Fallback initialen -->
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">D</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">C</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">L</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modern Era with Nederlandse accent -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border-2 border-orange-400/30 shadow-2xl hover:shadow-orange-400/20 transition-all duration-500 transform hover:-translate-y-4 hover:rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg border border-white/20">
                                <span class="text-white font-bold text-sm">1980-heden</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Digital icon with Nederlandse styling -->
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-2 group-hover:rotate-3 border-2 border-white/20">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Ontzuiling & Digitalisering
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-orange-500/20 rounded-full border border-orange-400/30">
                                        <svg class="w-4 h-4 text-orange-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <span class="text-orange-200 text-sm font-semibold">Nederlandse Vernieuwing</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    Einde van verzuiling, opkomst nieuwe partijen en sociale media revolutioneren Nederlandse campagnes. 
                                    Van paarse kabinetten tot moderne coalitievorming.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                    <?php if (isset($keyFiguresPresidenten['digitale_era'])): ?>
                                        <?php foreach ($keyFiguresPresidenten['digitale_era'] as $premier): ?>
                                            <div class="relative group/avatar">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-orange-400/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                    <?php if (isset($premier->foto_url) && !empty($premier->foto_url)): ?>
                                                        <img src="<?= htmlspecialchars($premier->foto_url) ?>" 
                                                             alt="<?= htmlspecialchars($premier->naam) ?>"
                                                             class="w-full h-full object-cover"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="w-full h-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-xs font-bold text-white">
                                                            <?= substr($premier->naam, 0, 1) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- Tooltip on hover -->
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                    <?= htmlspecialchars($premier->naam) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Fallback initialen -->
                                        <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">K</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">B</div>
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg border border-white/20">R</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action with enhanced Nederlandse design -->
                <div class="text-center mt-16 md:mt-20">
                    <div class="inline-flex items-center space-x-6">
                        <div class="h-px w-16 bg-gradient-to-r from-transparent to-orange-400/60"></div>
                        <div class="text-orange-200 text-sm font-medium tracking-widest uppercase">
                            Ontdek de Nederlandse geschiedenis
                        </div>
                        <div class="h-px w-16 bg-gradient-to-l from-transparent to-blue-400/60"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nederlandse Prime Ministers Gallery Preview Section -->
    <section class="py-16 md:py-20 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 relative overflow-hidden">
        <!-- Dutch Parliament backdrop with Nederlandse elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Den_Haag_Binnenhof_Hofvijver.jpg/640px-Den_Haag_Binnenhof_Hofvijver.jpg');"></div>
        </div>
        
        <!-- Nederlandse vlag kleuren overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-orange-900/20 via-transparent to-blue-900/20"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header with Nederlandse styling -->
                <div class="text-center mb-12 md:mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-orange-500/20 backdrop-blur-sm rounded-full mb-6 border border-orange-400/30">
                        <svg class="w-6 h-6 text-orange-300 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="text-orange-200 font-semibold">Nederlandse Ministers-presidenten</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        De <span class="bg-gradient-to-r from-orange-300 via-white to-blue-300 bg-clip-text text-transparent">Nederlandse premiers</span>
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 leading-relaxed max-w-4xl mx-auto">
                        Van Thorbeckiaanse liberalen tot moderne coalitievorming. 
                        Ontdek de leiders die Nederland bestuurden door meer dan anderhalve eeuw democratie.
                    </p>
                </div>

                <!-- Featured Nederlandse Prime Ministers Preview -->
                <?php if (!empty($recentePremiersData)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 mb-12">
                    <?php foreach (array_slice($recentePremiersData, 0, 6) as $index => $premier): ?>
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-orange-500 to-orange-600 p-1 group-hover:scale-110 transition-all duration-300 border-2 border-white/20">
                                <?php if (isset($premier->foto_url) && !empty($premier->foto_url)): ?>
                                    <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('<?= htmlspecialchars($premier->foto_url) ?>');"></div>
                                <?php else: ?>
                                    <div class="w-full h-full rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg">
                                        <?= substr($premier->naam, 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg border border-orange-400/30"><?= $premier->minister_president_nummer ?></div>
                        </div>
                        <div class="text-white text-sm font-medium"><?= htmlspecialchars($premier->naam) ?></div>
                        <div class="text-orange-200 text-xs"><?= htmlspecialchars($premier->partij) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <!-- Fallback voorbeelden -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 mb-12">
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-orange-400 to-orange-600 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg">R</div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">50</div>
                        </div>
                        <div class="text-white text-sm font-medium">M. Rutte</div>
                        <div class="text-blue-200 text-xs">VVD</div>
                    </div>
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-orange-400 to-orange-600 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg">B</div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">49</div>
                        </div>
                        <div class="text-white text-sm font-medium">J.P. Balkenende</div>
                        <div class="text-blue-200 text-xs">CDA</div>
                    </div>
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-orange-400 to-orange-600 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg">K</div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">48</div>
                        </div>
                        <div class="text-white text-sm font-medium">W. Kok</div>
                        <div class="text-blue-200 text-xs">PvdA</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Nederlandse Political Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="text-center bg-orange-500/10 backdrop-blur-md rounded-xl p-6 border border-orange-400/30 hover:bg-orange-500/15 transition-all duration-300">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2"><?= isset($statistieken) ? $statistieken->totaal_premiers : '15' ?></div>
                        <div class="text-orange-200 text-sm">Nederlandse Ministers-presidenten</div>
                        <div class="text-orange-300 text-xs mt-1">Sinds 1848</div>
                    </div>
                    <div class="text-center bg-blue-500/10 backdrop-blur-md rounded-xl p-6 border border-blue-400/30 hover:bg-blue-500/15 transition-all duration-300">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">175</div>
                        <div class="text-blue-200 text-sm">Jaar</div>
                        <div class="text-blue-300 text-xs mt-1">Nederlandse Democratie</div>
                    </div>
                    <div class="text-center bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">150</div>
                        <div class="text-slate-200 text-sm">Tweede Kamerzetels</div>
                        <div class="text-slate-300 text-xs mt-1">Sinds 1956</div>
                    </div>
                    <div class="text-center bg-orange-500/10 backdrop-blur-md rounded-xl p-6 border border-orange-400/30 hover:bg-orange-500/15 transition-all duration-300">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">4</div>
                        <div class="text-orange-200 text-sm">Jaar</div>
                        <div class="text-orange-300 text-xs mt-1">Verkiezingscyclus</div>
                    </div>
                </div>

                <!-- Nederlandse Call to Action -->
                <div class="text-center">
                    <div class="mb-6">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">
                            Ontdek alle Nederlandse ministers-presidenten
                        </h3>
                        <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                            Van Thorbeckiaanse liberale pioniers tot moderne coalitievorming. 
                            Bekijk gedetailleerde profielen en prestaties van Nederlandse premiers.
                        </p>
                    </div>
                    
                    <a href="<?= URLROOT ?>/nederlandse-verkiezingen/ministers-presidenten" 
                       class="group inline-flex items-center justify-center px-8 md:px-12 py-4 md:py-5 bg-gradient-to-r from-orange-500 via-orange-400 to-orange-500 text-white font-bold text-lg rounded-full hover:from-orange-400 hover:to-orange-400 transition-all duration-300 shadow-2xl hover:shadow-orange-500/25 transform hover:-translate-y-1 hover:scale-105 border-2 border-orange-400/30">
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                        <span>Bekijk alle Nederlandse ministers-presidenten</span>
                        <svg class="ml-3 w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Nederlandse Elections Overview by Period -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-slate-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <?php if (isset($verkiezingenPerPeriode) && !empty($verkiezingenPerPeriode)): ?>
                    <?php foreach ($verkiezingenPerPeriode as $periode => $verkiezingen): ?>
                        <div class="mb-16">
                            <!-- Period Header with Nederlandse flair -->
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 via-primary to-blue-600 rounded-full mb-4 border-2 border-orange-400/30 shadow-xl">
                                    <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                    </svg>
                                    <h3 class="text-2xl md:text-3xl font-bold text-white">
                                        <?= htmlspecialchars($periode) ?>
                                    </h3>
                                </div>
                                
                                <!-- Nederlandse decorative border with flag colors -->
                                <div class="flex items-center justify-center">
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-orange-400 to-transparent"></div>
                                    <div class="mx-4 flex space-x-1">
                                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                        <div class="w-3 h-3 bg-white border border-orange-400 rounded-full"></div>
                                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent"></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <?php foreach ($verkiezingen as $verkiezing): ?>
                                    <div class="group">
                                        <a href="<?= URLROOT ?>/nederlandse-verkiezingen/<?= $verkiezing->jaar ?>" 
                                           class="block bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border-2 border-orange-200/30 hover:border-orange-400/50 overflow-hidden relative">
                                            
                                            <!-- Nederlandse vlag corner -->
                                            <div class="absolute top-3 right-3 w-8 h-6 rounded-sm opacity-30 group-hover:opacity-60 transition-opacity duration-300">
                                                <div class="w-full h-2 bg-orange-500"></div>
                                                <div class="w-full h-2 bg-white"></div>
                                                <div class="w-full h-2 bg-blue-600"></div>
                                            </div>
                                            
                                            <!-- Election Year Header with Nederlandse styling -->
                                            <div class="bg-gradient-to-r from-orange-500 via-orange-400 to-blue-600 p-1">
                                                <div class="bg-gradient-to-r from-orange-500 to-blue-600 p-4 text-center">
                                                    <div class="text-2xl font-black text-white mb-1">
                                                        <?= $verkiezing->jaar ?>
                                                    </div>
                                                    <div class="text-sm text-orange-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                                        </svg>
                                                        Nederlandse Tweede Kamerverkiezing
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Winner Info -->
                                            <div class="p-6">
                                                <div class="text-center mb-4">
                                                    <div class="text-lg font-bold text-gray-900 mb-1">
                                                        <?= htmlspecialchars($verkiezing->grootste_partij ?? 'Onbekend') ?>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mb-3">
                                                        Grootste partij
                                                    </div>
                                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                        </svg>
                                                        <?= $verkiezing->grootste_partij_zetels ?? 'Onbekend' ?> zetels
                                                    </div>
                                                </div>
                                                
                                                <!-- Minister-president info -->
                                                <div class="text-center mb-4 p-3 bg-gray-50 rounded-lg">
                                                    <div class="text-sm text-gray-600 mb-1">Minister-president</div>
                                                    <div class="font-semibold text-gray-900"><?= htmlspecialchars($verkiezing->minister_president) ?></div>
                                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($verkiezing->minister_president_partij) ?></div>
                                                </div>
                                                
                                                <!-- Quick Stats -->
                                                <div class="space-y-2 text-center text-sm text-gray-500">
                                                    <div class="flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                        <?= $verkiezing->grootste_partij_percentage ? number_format($verkiezing->grootste_partij_percentage, 1) . '% van de stemmen' : 'Percentage onbekend' ?>
                                                    </div>
                                                    <div class="flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.99 2.99 0 0018.05 7H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1v-5h0zM12.5 11.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5S11 9.17 11 10s.67 1.5 1.5 1.5zM5.5 6c1.11 0 2-.89 2-2s-.89-2-2-2-2 .89-2 2 .89 2 2 2zm1.5 2h-4c-.83 0-1.5.67-1.5 1.5S2.17 11 3 11h1v8h2v-8h1c.83 0 1.5-.67 1.5-1.5S7.83 8 7 8z"/>
                                                        </svg>
                                                        <?= number_format($verkiezing->opkomst_percentage, 1) ?>% opkomst
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- View Details Button -->
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

    <!-- Enhanced Nederlandse Statistics Section -->
    <section class="py-16 md:py-20 bg-gradient-to-br from-orange-600 via-primary to-blue-700 relative overflow-hidden" id="statistieken">
        <!-- Nederlandse Parliament silhouette -->
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Den_Haag_Binnenhof_Hofvijver.jpg/640px-Den_Haag_Binnenhof_Hofvijver.jpg');"></div>
        </div>
        
        <!-- Nederlandse vlag elements -->
        <div class="absolute top-10 left-10 w-4 h-4 bg-orange-500 rounded-full animate-pulse opacity-30"></div>
        <div class="absolute top-20 right-20 w-3 h-3 bg-white rounded-full animate-pulse opacity-40" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-20 w-3 h-3 bg-blue-600 rounded-full animate-pulse opacity-30" style="animation-delay: 2s;"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12 md:mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-orange-500/30 backdrop-blur-sm rounded-full mb-6 border border-orange-400/40">
                        <svg class="w-6 h-6 text-orange-200 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                        <span class="text-orange-200 font-semibold">Nederlandse politiek in cijfers</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                        175 jaar Nederlandse parlementaire democratie
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                        Van census kiesrecht tot universeel stemrecht, van twee grote blokken tot 
                        versnipperde coalitievorming - de evolutie van Nederlandse democratie in cijfers.
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
                            <div class="text-blue-200 text-sm mt-1">Tweede Kamer</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-orange-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= $statistieken->vvd_overwinningen ?>
                            </div>
                            <div class="text-blue-100 font-medium">VVD premiers</div>
                            <div class="text-blue-200 text-sm mt-1">Liberale traditie</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-red-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="text-3xl md:text-4xl font-black text-white mb-2">
                                <?= $statistieken->pvda_overwinningen ?>
                            </div>
                            <div class="text-blue-100 font-medium">PvdA premiers</div>
                            <div class="text-blue-200 text-sm mt-1">Sociaal-democratie</div>
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
                    
                    <!-- Additional Nederlandse-themed stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-orange-500/15 backdrop-blur-sm rounded-xl p-6 text-center border border-orange-400/30 hover:bg-orange-500/20 transition-all duration-300">
                            <div class="text-2xl font-bold text-white mb-1">150</div>
                            <div class="text-orange-200 text-sm">Tweede Kamerzetels</div>
                        </div>
                        <div class="bg-blue-500/15 backdrop-blur-sm rounded-xl p-6 text-center border border-blue-400/30 hover:bg-blue-500/20 transition-all duration-300">
                            <div class="text-2xl font-bold text-white mb-1">76</div>
                            <div class="text-blue-200 text-sm">Nodig voor meerderheid</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20 hover:bg-white/15 transition-all duration-300">
                            <div class="text-2xl font-bold text-white mb-1">0.67%</div>
                            <div class="text-slate-200 text-sm">Nederlandse Kiesdrempel</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 
