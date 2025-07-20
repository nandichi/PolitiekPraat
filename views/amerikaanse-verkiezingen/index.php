<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <!-- Modern Responsive Hero Section with BBC Background -->
    <section class="relative min-h-[100svh] md:min-h-screen flex items-center justify-center overflow-hidden py-8 md:py-16">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <!-- BBC Election Data Visualization Background -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://ichef.bbci.co.uk/images/ic/640x360/p0jzn3xt.jpg');">
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
              </div>
        </div>
    </section>

    

    <!-- Enhanced American Democracy History Section -->
    <section class="py-20 md:py-28 bg-gradient-to-br from-primary-dark via-primary to-secondary relative overflow-hidden" id="verkiezingen-overzicht">
        <!-- Dynamic Background Elements -->
        <div class="absolute inset-0">
            <!-- Elegant White House silhouette -->
            <div class="absolute inset-0 opacity-[0.03]">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://media.architecturaldigest.com/photos/6559735fb796d428bef00d25/3:2/w_5568,h_3712,c_limit/GettyImages-1731443210.jpg');"></div>
            </div>
            
            <!-- Patriotic geometric patterns -->
            <div class="absolute top-0 left-0 w-96 h-96 opacity-10 -translate-x-48 -translate-y-48 rotate-45">
                <div class="w-full h-full bg-gradient-to-br from-red-400/20 to-blue-400/20 rounded-3xl backdrop-blur-3xl"></div>
            </div>
            <div class="absolute bottom-0 right-0 w-80 h-80 opacity-10 translate-x-40 translate-y-40 -rotate-45">
                <div class="w-full h-full bg-gradient-to-tl from-white/20 to-secondary-light/20 rounded-3xl backdrop-blur-3xl"></div>
            </div>
            
            <!-- Animated stars -->
            <div class="absolute top-20 left-20 w-2 h-2 bg-white rounded-full animate-ping opacity-40" style="animation-delay: 0s; animation-duration: 3s;"></div>
            <div class="absolute top-32 right-32 w-1.5 h-1.5 bg-secondary-light rounded-full animate-ping opacity-60" style="animation-delay: 1s; animation-duration: 4s;"></div>
            <div class="absolute bottom-40 left-1/4 w-1 h-1 bg-primary-light rounded-full animate-ping opacity-50" style="animation-delay: 2s; animation-duration: 5s;"></div>
            <div class="absolute bottom-60 right-1/3 w-2.5 h-2.5 bg-white rounded-full animate-ping opacity-30" style="animation-delay: 3s; animation-duration: 3.5s;"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Enhanced Header Section -->
                <div class="text-center mb-16 md:mb-20">

                    
                    <!-- Main title with advanced gradient -->
                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-8 leading-tight">
                        <span class="block text-white mb-2">Van de eerste</span>
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            democratische revolutie
                        </span>
                        <span class="block text-white">tot digitale campagnes</span>
                    </h2>
                    
                    <!-- Enhanced description -->
                    <div class="max-w-5xl mx-auto">
                        <p class="text-xl md:text-2xl text-blue-100 leading-relaxed mb-6 px-4">
                            Een ongeëvenaarde reis door meer dan twee eeuwen Amerikaanse democratie - van George Washington's 
                            unanieme overwinning tot de complexe digitale campagnes van vandaag.
                        </p>
                        <p class="text-lg text-blue-200 leading-relaxed px-4">
                            Elke verkiezing vertelt het verhaal van een groeiende natie, haar uitdagingen, triomfen en 
                            de voortdurende strijd om de democratische idealen te verwezenlijken.
                        </p>
                    </div>
                </div>

                <!-- Revolutionary Timeline Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Early Republic Era -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border border-white/20 shadow-2xl hover:shadow-white/10 transition-all duration-500 transform hover:-translate-y-4 hover:rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-600 rounded-full shadow-lg">
                                <span class="text-white font-bold text-sm">1789-1860</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Revolutionary icon -->
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-amber-400 via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-3 group-hover:rotate-6">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Vroege Republiek
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full border border-white/30">
                                        <svg class="w-4 h-4 text-amber-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="text-blue-100 text-sm font-semibold">De fundamenten</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    Van Washington's precedenten tot Lincoln's beproeving - de fundamentele democratische 
                                    instituties worden gevormd en getest door burgeroorlog en territoriale expansie.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                 <?php if (isset($keyFiguresPresidenten['vroege_republiek'])): ?>
                                     <?php foreach ($keyFiguresPresidenten['vroege_republiek'] as $president): ?>
                                         <div class="relative group/avatar">
                                             <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                 <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                     <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                          alt="<?= htmlspecialchars($president->naam) ?>"
                                                          class="w-full h-full object-cover"
                                                          onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                     <div class="w-full h-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php else: ?>
                                                     <div class="w-full h-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center text-xs font-bold text-white">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php endif; ?>
                                             </div>
                                             <!-- Tooltip on hover -->
                                             <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                 <?= htmlspecialchars($president->naam) ?>
                                             </div>
                                         </div>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <!-- Fallback initialen -->
                                     <div class="w-8 h-8 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">W</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">J</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">L</div>
                                 <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modern Era -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border border-white/20 shadow-2xl hover:shadow-white/10 transition-all duration-500 transform hover:-translate-y-4 hover:-rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-full shadow-lg">
                                <span class="text-white font-bold text-sm">1860-1960</span>
                            </div>
                            
                            <!-- Industrial icon -->
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 -rotate-3 group-hover:-rotate-6">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Industriële Era
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full border border-white/30">
                                        <svg class="w-4 h-4 text-blue-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 9.74s9-4.19 9-9.74V7l-10-5z"/>
                                        </svg>
                                        <span class="text-blue-100 text-sm font-semibold">Transformatie</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    Industrialisatie, wereldoorlogen en sociale revoluties transformeren Amerika. 
                                    Van isolationisme naar wereldleiderschap, van beperkt kiesrecht naar universele democratie.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                 <?php if (isset($keyFiguresPresidenten['industriele_era'])): ?>
                                     <?php foreach ($keyFiguresPresidenten['industriele_era'] as $president): ?>
                                         <div class="relative group/avatar">
                                             <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                 <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                     <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                          alt="<?= htmlspecialchars($president->naam) ?>"
                                                          class="w-full h-full object-cover"
                                                          onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                     <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php else: ?>
                                                     <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php endif; ?>
                                             </div>
                                             <!-- Tooltip on hover -->
                                             <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                 <?= htmlspecialchars($president->naam) ?>
                                             </div>
                                         </div>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <!-- Fallback initialen -->
                                     <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">T</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">F</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">T</div>
                                 <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Digital Age -->
                    <div class="group perspective-1000">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border border-white/20 shadow-2xl hover:shadow-white/10 transition-all duration-500 transform hover:-translate-y-4 hover:rotate-1 group-hover:bg-white/15 h-[500px] flex flex-col justify-between">
                            <!-- Period indicator -->
                            <div class="absolute -top-4 left-8 px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg">
                                <span class="text-white font-bold text-sm">1960-heden</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Digital icon -->
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 via-pink-600 to-red-600 rounded-2xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-2 group-hover:rotate-3">
                                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 00-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm6 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl font-black text-white mb-4 text-center">
                                    Digitale Revolutie
                                </h3>
                                
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full border border-white/30">
                                        <svg class="w-4 h-4 text-purple-300 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 3a9 9 0 009 9 9.75 9.75 0 01-6.74 9.75c-.8.19-1.1-.34-1.1-.78V18a3.25 3.25 0 00-.91-2.58c3-.33 6.2-1.48 6.2-6.69A5.31 5.31 0 0018 6.09a4.93 4.93 0 00-.13-3.64s-1.1-.35-3.6 1.35a12.4 12.4 0 00-6.55 0C5.22 2.1 4.12 2.45 4.12 2.45a4.93 4.93 0 00-.13 3.64 5.31 5.31 0 00-1.45 2.64c0 5.21 3.17 6.36 6.2 6.69a3.25 3.25 0 00-.91 2.58v3.65c0 .44-.3.97-1.1.78A9.75 9.75 0 013 12a9 9 0 019-9z"/>
                                        </svg>
                                        <span class="text-blue-100 text-sm font-semibold">Innovatie</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-blue-100 leading-relaxed text-center">
                                    TV-debatten revolutioneren campagnes, internet democratiseert informatie en sociale media 
                                    herdefiniëren politieke communicatie in het digitale tijdperk.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-2">
                                 <?php if (isset($keyFiguresPresidenten['digitale_revolutie'])): ?>
                                     <?php foreach ($keyFiguresPresidenten['digitale_revolutie'] as $president): ?>
                                         <div class="relative group/avatar">
                                             <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white/50 shadow-lg transition-all duration-300 group-hover/avatar:scale-125 group-hover/avatar:z-10">
                                                 <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                     <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                          alt="<?= htmlspecialchars($president->naam) ?>"
                                                          class="w-full h-full object-cover"
                                                          onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                     <div class="w-full h-full bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center text-xs font-bold text-white" style="display: none;">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php else: ?>
                                                     <div class="w-full h-full bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center text-xs font-bold text-white">
                                                         <?= substr($president->naam, 0, 1) ?>
                                                     </div>
                                                 <?php endif; ?>
                                             </div>
                                             <!-- Tooltip on hover -->
                                             <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 whitespace-nowrap z-20">
                                                 <?= htmlspecialchars($president->naam) ?>
                                             </div>
                                         </div>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <!-- Fallback initialen -->
                                     <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">K</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">O</div>
                                     <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">B</div>
                                 <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action with enhanced design -->
                <div class="text-center mt-16 md:mt-20">
                    <div class="inline-flex items-center space-x-6">
                        <div class="h-px w-16 bg-gradient-to-r from-transparent to-white/40"></div>
                        <div class="text-white/80 text-sm font-medium tracking-widest uppercase">
                            Ontdek de geschiedenis
                        </div>
                        <div class="h-px w-16 bg-gradient-to-l from-transparent to-white/40"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

      <!-- Presidents Gallery Preview Section -->
    <section class="py-16 md:py-20 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 relative overflow-hidden">
        <!-- Presidential backdrop -->
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Mount_Rushmore_detail_view_%28100MP%29.jpg/1200px-Mount_Rushmore_detail_view_%28100MP%29.jpg');"></div>
        </div>
        
        <!-- American presidential seal pattern -->
        <div class="absolute top-0 left-0 w-96 h-96 opacity-5 -translate-x-48 -translate-y-48">
            <svg viewBox="0 0 200 200" class="w-full h-full">
                <circle cx="100" cy="100" r="90" fill="none" stroke="currentColor" stroke-width="2"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="currentColor" stroke-width="1"/>
                <circle cx="100" cy="100" r="30" fill="none" stroke="currentColor" stroke-width="1"/>
            </svg>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-12 md:mb-16">
                    <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                        <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="text-white font-semibold">Presidents Gallery</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        Ontmoet de <span class="bg-gradient-to-r from-yellow-300 via-white to-yellow-300 bg-clip-text text-transparent">46 presidenten</span>
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 leading-relaxed max-w-4xl mx-auto">
                        Van George Washington, de vader van de natie, tot Joe Biden, de huidige president. 
                        Ontdek de leiders die Amerika vormden door meer dan twee eeuwen geschiedenis.
                    </p>
                </div>

                <!-- Featured Presidents Preview -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6 mb-12">
                    <!-- Washington -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Gilbert_Stuart_Williamstown_Portrait_of_George_Washington.jpg/256px-Gilbert_Stuart_Williamstown_Portrait_of_George_Washington.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">1</div>
                        </div>
                        <div class="text-white text-sm font-medium">G. Washington</div>
                        <div class="text-blue-200 text-xs">1789-1797</div>
                    </div>

                    <!-- Lincoln -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Abraham_Lincoln_O-77_matte_collodion_print.jpg/256px-Abraham_Lincoln_O-77_matte_collodion_print.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">16</div>
                        </div>
                        <div class="text-white text-sm font-medium">A. Lincoln</div>
                        <div class="text-blue-200 text-xs">1861-1865</div>
                    </div>

                    <!-- Roosevelt -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/FDR_1944_Color_Portrait.jpg/256px-FDR_1944_Color_Portrait.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">32</div>
                        </div>
                        <div class="text-white text-sm font-medium">F.D. Roosevelt</div>
                        <div class="text-blue-200 text-xs">1933-1945</div>
                    </div>

                    <!-- Kennedy -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/John_F._Kennedy%2C_White_House_color_photo_portrait.jpg/256px-John_F._Kennedy%2C_White_House_color_photo_portrait.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">35</div>
                        </div>
                        <div class="text-white text-sm font-medium">J.F. Kennedy</div>
                        <div class="text-blue-200 text-xs">1961-1963</div>
                    </div>

                    <!-- Obama -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/President_Barack_Obama.jpg/256px-President_Barack_Obama.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">44</div>
                        </div>
                        <div class="text-white text-sm font-medium">B. Obama</div>
                        <div class="text-blue-200 text-xs">2009-2017</div>
                    </div>

                    <!-- Biden -->
                    <div class="group text-center">
                        <div class="relative mb-3">
                            <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 p-1 group-hover:scale-110 transition-all duration-300">
                                <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Joe_Biden_presidential_portrait.jpg/256px-Joe_Biden_presidential_portrait.jpg');"></div>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-2 py-1 rounded-full">46</div>
                        </div>
                        <div class="text-white text-sm font-medium">J. Biden</div>
                        <div class="text-blue-200 text-xs">2021-heden</div>
                    </div>
                </div>

                <!-- Presidential Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="text-center bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">46</div>
                        <div class="text-blue-100 text-sm">Presidenten</div>
                        <div class="text-blue-200 text-xs mt-1">Washington - Biden</div>
                    </div>
                    <div class="text-center bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">235</div>
                        <div class="text-blue-100 text-sm">Jaar</div>
                        <div class="text-blue-200 text-xs mt-1">1789 - 2024</div>
                    </div>
                    <div class="text-center bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">4</div>
                        <div class="text-blue-100 text-sm">Vermoord</div>
                        <div class="text-blue-200 text-xs mt-1">Lincoln, Garfield, McKinley, Kennedy</div>
                    </div>
                    <div class="text-center bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">1</div>
                        <div class="text-blue-100 text-sm">Afgetreden</div>
                        <div class="text-blue-200 text-xs mt-1">Nixon (1974)</div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center">
                    <div class="mb-6">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">
                            Ontdek alle 46 presidenten
                        </h3>
                        <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                            Van hun achtergrond en prestaties tot hun impact op de Amerikaanse geschiedenis. 
                            Bekijk gedetailleerde profielen, biografieën en presidentiële prestaties.
                        </p>
                    </div>
                    
                    <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/presidenten" 
                       class="group inline-flex items-center justify-center px-8 md:px-12 py-4 md:py-5 bg-gradient-to-r from-yellow-500 via-yellow-400 to-amber-500 text-gray-900 font-bold text-lg rounded-full hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/25 transform hover:-translate-y-1 hover:scale-105">
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span>Bekijk alle presidenten</span>
                        <svg class="ml-3 w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    
                    <!-- Secondary actions -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center items-center">
                        <div class="inline-flex items-center text-blue-200 text-sm">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Uitgebreide biografieën
                        </div>
                        <div class="hidden sm:block w-1 h-1 bg-blue-300 rounded-full"></div>
                        <div class="inline-flex items-center text-blue-200 text-sm">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 18H8v-2h4v2zm0-4H8v-2h4v2zm0-4H8V8h4v4z"/>
                            </svg>
                            Presidentiële prestaties
                        </div>
                        <div class="hidden sm:block w-1 h-1 bg-blue-300 rounded-full"></div>
                        <div class="inline-flex items-center text-blue-200 text-sm">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            Historische context
                        </div>
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

        <!-- Revolutionary Electoral System Explanation Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-slate-50 via-white to-blue-50 relative overflow-hidden">
        <!-- Dynamic Background Elements -->
        <div class="absolute inset-0">
            <!-- Capitol building silhouette -->
            <div class="absolute inset-0 opacity-[0.02]">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/4/4f/US_Capitol_west_side.JPG');"></div>
            </div>
            
            <!-- Constitutional pattern elements -->
            <div class="absolute top-0 left-0 w-80 h-80 opacity-5 -translate-x-40 -translate-y-40 rotate-12">
                <div class="w-full h-full bg-gradient-to-br from-amber-400/30 to-red-400/30 rounded-full backdrop-blur-3xl"></div>
            </div>
            <div class="absolute bottom-0 right-0 w-96 h-96 opacity-5 translate-x-48 translate-y-48 -rotate-12">
                <div class="w-full h-full bg-gradient-to-tl from-blue-400/30 to-purple-400/30 rounded-full backdrop-blur-3xl"></div>
            </div>
            
            <!-- Floating democratic elements -->
            <div class="absolute top-32 left-1/4 w-2 h-2 bg-red-400 rounded-full animate-ping opacity-40" style="animation-delay: 0s; animation-duration: 4s;"></div>
            <div class="absolute top-56 right-1/4 w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping opacity-50" style="animation-delay: 1.5s; animation-duration: 5s;"></div>
            <div class="absolute bottom-32 left-1/3 w-1 h-1 bg-white rounded-full animate-ping opacity-30" style="animation-delay: 3s; animation-duration: 3.5s;"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Enhanced Header Section -->
                <div class="text-center mb-20 md:mb-24">
                    <!-- Revolutionary title with constitutional gradient -->
                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-10 leading-tight">
                        <span class="block text-gray-900 mb-2">Hoe kiest Amerika</span>
                        <span class="block bg-gradient-to-r from-primary via-secondary to-primary-light bg-clip-text text-transparent">
                            haar president?
                        </span>
                    </h2>
                    
                    <!-- Enhanced description with better typography -->
                    <div class="max-w-6xl mx-auto">
                        <p class="text-xl md:text-2xl text-gray-700 leading-relaxed mb-6 px-4 font-medium">
                            Een revolutionair systeem dat 235 jaar democratie heeft bevorderd - van de grondwettelijke kiesmannen tot moderne swing states.
                        </p>
                        <p class="text-lg text-gray-600 leading-relaxed px-4">
                            Ontdek hoe dit complexe maar geniale systeem ervoor zorgt dat elke stem telt en elke staat een stem heeft in de toekomst van de natie.
                        </p>
                    </div>
                </div>

                <!-- Revolutionary Electoral Process Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 mb-20">
                    <!-- Constitutional Foundation -->
                    <div class="group perspective-1000">
                         <div class="relative bg-gradient-to-br from-red-500/5 via-white to-red-500/10 backdrop-blur-xl rounded-3xl p-10 md:p-12 border border-red-200/50 shadow-2xl hover:shadow-red-100/50 transition-all duration-500 transform hover:-translate-y-6 hover:rotate-1 group-hover:bg-red-50/50 h-[600px] flex flex-col justify-between">
                            <!-- Step indicator -->
                            <div class="absolute -top-6 left-10 px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-xl">
                                <span class="text-white font-black text-lg">Stap 1</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Constitutional icon with animation -->
                                <div class="relative mb-8">
                                    <div class="w-24 h-24 bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-2 group-hover:rotate-3">
                                        <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 text-center">
                                    Kiesmannen Systeem
                                </h3>
                                
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center px-6 py-3 bg-red-500/10 rounded-full border border-red-300/50">
                                        <svg class="w-5 h-5 text-red-600 mr-3" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="text-red-700 font-bold text-sm">Grondwettelijk fundament</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-gray-700 leading-relaxed text-center text-lg">
                                    538 kiesmannen verdeeld over 50 staten plus Washington D.C. Een kandidaat moet 270 kiesmannen behalen om president te worden - de helft plus één.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-red-600">538</div>
                                        <div class="text-xs text-gray-600">Totaal</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-red-600">270</div>
                                        <div class="text-xs text-gray-600">Nodig</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-red-600">51</div>
                                        <div class="text-xs text-gray-600">Locaties</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Winner-Takes-All Strategy -->
                    <div class="group perspective-1000">
                         <div class="relative bg-gradient-to-br from-blue-500/5 via-white to-blue-500/10 backdrop-blur-xl rounded-3xl p-10 md:p-12 border border-blue-200/50 shadow-2xl hover:shadow-blue-100/50 transition-all duration-500 transform hover:-translate-y-6 hover:-rotate-1 group-hover:bg-blue-50/50 h-[600px] flex flex-col justify-between">
                            <!-- Step indicator -->
                            <div class="absolute -top-6 left-10 px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full shadow-xl">
                                <span class="text-white font-black text-lg">Stap 2</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Strategic icon with animation -->
                                <div class="relative mb-8">
                                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 -rotate-2 group-hover:-rotate-3">
                                        <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 text-center">
                                    Winner-Takes-All
                                </h3>
                                
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center px-6 py-3 bg-blue-500/10 rounded-full border border-blue-300/50">
                                        <svg class="w-5 h-5 text-blue-600 mr-3" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                        <span class="text-blue-700 font-bold text-sm">Strategische impact</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-gray-700 leading-relaxed text-center text-lg">
                                    In 48 staten krijgt de winnaar alle kiesmannen van die staat. Alleen Maine en Nebraska verdelen proportioneel, wat swing states cruciaal maakt.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-blue-600">48</div>
                                        <div class="text-xs text-gray-600">Alles-of-niets</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-blue-600">2</div>
                                        <div class="text-xs text-gray-600">Proportioneel</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-blue-600">~7</div>
                                        <div class="text-xs text-gray-600">Swing states</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Democratic Cycle -->
                    <div class="group perspective-1000">
                         <div class="relative bg-gradient-to-br from-purple-500/5 via-white to-purple-500/10 backdrop-blur-xl rounded-3xl p-10 md:p-12 border border-purple-200/50 shadow-2xl hover:shadow-purple-100/50 transition-all duration-500 transform hover:-translate-y-6 hover:rotate-1 group-hover:bg-purple-50/50 h-[600px] flex flex-col justify-between">
                            <!-- Step indicator -->
                            <div class="absolute -top-6 left-10 px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full shadow-xl">
                                <span class="text-white font-black text-lg">Stap 3</span>
                            </div>
                            
                            <!-- Header Section -->
                            <div class="flex-shrink-0">
                                <!-- Cyclical icon with animation -->
                                <div class="relative mb-8">
                                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-all duration-300 rotate-1 group-hover:rotate-2">
                                        <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2-7H5v2h14V4zM6 19h12v2H6v-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 text-center">
                                    Democratische Cyclus
                                </h3>
                                
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center px-6 py-3 bg-purple-500/10 rounded-full border border-purple-300/50">
                                        <svg class="w-5 h-5 text-purple-600 mr-3" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm3.5 6L12 10.5 8.5 8 12 5.5 15.5 8zM8.5 16L12 13.5 15.5 16 12 18.5 8.5 16z"/>
                                        </svg>
                                        <span class="text-purple-700 font-bold text-sm">Stabiele traditie</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="flex-grow flex items-center">
                                <p class="text-gray-700 leading-relaxed text-center text-lg">
                                    Elke vier jaar op de eerste dinsdag na de eerste maandag in november. Een 19e-eeuwse traditie die stabiliteit garandeert en voorspelbare machtsoverdrachten mogelijk maakt.
                                </p>
                            </div>
                            
                            <!-- Footer Section -->
                            <div class="flex-shrink-0">
                                <div class="flex justify-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-purple-600">59</div>
                                        <div class="text-xs text-gray-600">Verkiezingen</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-purple-600">4</div>
                                        <div class="text-xs text-gray-600">Jaar cyclus</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-black text-purple-600">235</div>
                                        <div class="text-xs text-gray-600">Jaar traditie</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Democratic Milestones Timeline -->
                <div class="relative">
                    <!-- Background decoration -->
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-white/50 to-secondary/5 rounded-3xl backdrop-blur-sm border border-primary/10"></div>
                    
                    <div class="relative bg-gradient-to-br from-slate-50/80 via-white/90 to-blue-50/80 backdrop-blur-xl rounded-3xl p-12 md:p-16 border border-white/50 shadow-2xl">
                        <!-- Enhanced timeline header -->
                        <div class="text-center mb-16">
                            <div class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-full mb-8 border border-primary/20">
                                <svg class="w-6 h-6 text-primary mr-3" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span class="text-primary font-bold text-lg">Democratische evolutie</span>
                            </div>
                            
                            <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                                Mijlpalen van de <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Amerikaanse democratie</span>
                            </h3>
                            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                Van beperkte stemrechten naar universeel kiesrecht - de groei van democratische participatie
                            </p>
                        </div>
                        
                        <!-- Enhanced milestone cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                            <!-- 1789 - Constitutional Foundation -->
                            <div class="group text-center">
                                <div class="relative bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 hover:rotate-1">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 18H8v-2h4v2zm0-4H8v-2h4v2zm0-4H8V8h4v4z"/>
                                        </svg>
                                    </div>
                                    <div class="pt-6">
                                        <div class="text-3xl font-black text-gray-900 mb-2">1789</div>
                                        <div class="text-lg font-bold text-amber-600 mb-3">Grondwet</div>
                                        <div class="text-sm text-gray-600">Eerste presidentsverkiezing - George Washington unaniem gekozen</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 1870 - Civil Rights -->
                            <div class="group text-center">
                                <div class="relative bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 hover:-rotate-1">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <div class="pt-6">
                                        <div class="text-3xl font-black text-gray-900 mb-2">1870</div>
                                        <div class="text-lg font-bold text-blue-600 mb-3">15e Amendement</div>
                                        <div class="text-sm text-gray-600">Stemrecht onafhankelijk van ras, kleur of vroegere slavernij</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 1920 - Women's Suffrage -->
                            <div class="group text-center">
                                <div class="relative bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 hover:rotate-1">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>
                                    <div class="pt-6">
                                        <div class="text-3xl font-black text-gray-900 mb-2">1920</div>
                                        <div class="text-lg font-bold text-purple-600 mb-3">19e Amendement</div>
                                        <div class="text-sm text-gray-600">Vrouwenkiesrecht - stemrecht voor alle Amerikaanse vrouwen</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 1965 - Voting Rights Act -->
                            <div class="group text-center">
                                <div class="relative bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 hover:-rotate-1">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="pt-6">
                                        <div class="text-3xl font-black text-gray-900 mb-2">1965</div>
                                        <div class="text-lg font-bold text-green-600 mb-3">Voting Rights Act</div>
                                        <div class="text-sm text-gray-600">Eliminatie van discriminatoire kiespraktijken</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced call to action -->
                        <div class="text-center mt-16">
                            <div class="inline-flex items-center space-x-8">
                                <div class="h-px w-20 bg-gradient-to-r from-transparent to-primary/40"></div>
                                <div class="text-gray-500 text-lg font-medium tracking-wide">
                                    Een voortdurende evolutie naar meer inclusieve democratie
                                </div>
                                <div class="h-px w-20 bg-gradient-to-l from-transparent to-primary/40"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 