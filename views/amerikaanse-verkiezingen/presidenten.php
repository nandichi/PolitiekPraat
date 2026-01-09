<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Presidential Hero Section -->
    <section class="relative min-h-[100svh] md:min-h-screen flex items-center justify-center overflow-hidden py-8 md:py-16">
        <!-- Mount Rushmore Background -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/f/f3/Dean_Franklin_-_06.04.03_Mount_Rushmore_Monument_%28by-sa%29-3_new.jpg');">
            </div>
            
            <!-- Overlay for readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/95 via-slate-800/90 to-slate-900/95"></div>
            
            <!-- Presidential blue tint -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 via-transparent to-blue-800/30"></div>
        </div>
        
        <!-- Floating presidential elements -->
        <div class="absolute top-20 left-10 w-3 h-3 bg-blue-300 rounded-full animate-ping opacity-75 hidden md:block"></div>
        <div class="absolute top-32 right-20 w-2 h-2 bg-red-300 rounded-full animate-ping opacity-60 hidden md:block" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-1/4 w-2 h-2 bg-white rounded-full animate-ping opacity-40 hidden md:block" style="animation-delay: 2s;"></div>
        
        <!-- Main Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                
                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl xl:text-9xl font-black text-white mb-4 md:mb-8 tracking-tight leading-tight px-2">
                    <span class="block">Presidents</span>
                    <span class="block bg-gradient-to-r from-red-300 via-white to-blue-300 bg-clip-text text-transparent">
                        Gallery
                    </span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-slate-200 mb-3 md:mb-4 font-light leading-relaxed max-w-4xl mx-auto px-4">
                    Alle 46 presidenten van de Verenigde Staten
                </p>
                
                <!-- Description -->
                <p class="text-sm sm:text-base md:text-lg text-slate-300 mb-6 md:mb-12 leading-relaxed max-w-3xl mx-auto px-4">
                    Van George Washington tot Joe Biden - ontdek de leiders die Amerika vormden, hun prestaties, 
                    familie dynastieën en fascinerende verhalen uit 235 jaar presidentschap.
                </p>
                
                <!-- Presidential Statistics -->
                <?php if (isset($presidentenStatistieken)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 md:gap-6 lg:gap-8 mb-6 md:mb-12 max-w-5xl mx-auto px-4">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                            <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                                <?= $presidentenStatistieken->totaal_presidenten ?>
                            </div>
                            <div class="text-slate-300 font-medium text-sm md:text-base">Presidenten</div>
                            <div class="text-xs text-slate-400 mt-1">1789 - heden</div>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                            <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                                <?= round($presidentenStatistieken->gemiddelde_leeftijd) ?>
                            </div>
                            <div class="text-slate-300 font-medium text-sm md:text-base">Gem. leeftijd</div>
                            <div class="text-xs text-slate-400 mt-1">Bij aantreden</div>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                            <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                                <?= $presidentenStatistieken->nog_levend ?>
                            </div>
                            <div class="text-slate-300 font-medium text-sm md:text-base">Nog levend</div>
                            <div class="text-xs text-slate-400 mt-1">Ex-presidenten</div>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl md:rounded-2xl p-4 md:p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                            <div class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-1 md:mb-2 group-hover:scale-110 transition-transform duration-300">
                                <?= round($presidentenStatistieken->gemiddelde_termijn_jaren, 1) ?>
                            </div>
                            <div class="text-slate-300 font-medium text-sm md:text-base">Gem. termijn</div>
                            <div class="text-xs text-slate-400 mt-1">Jaren in functie</div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center items-center px-4">
                    <a href="#presidenten-overzicht" 
                       class="w-full sm:w-auto group inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-blue-600 to-red-600 text-white font-bold rounded-full hover:from-blue-700 hover:to-red-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 hover:scale-105">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="text-sm md:text-base">Verken alle presidenten</span>
                    </a>
                    
                    <a href="#familie-dynastieen" 
                       class="w-full sm:w-auto group inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-full border border-white/20 hover:bg-white/20 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                        </svg>
                        <span class="text-sm md:text-base">Familie dynastieën</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Presidents Overview by Era -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-white to-slate-50" id="presidenten-overzicht">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <?php if (isset($presidentenPerPeriode) && !empty($presidentenPerPeriode)): ?>
                    <?php foreach ($presidentenPerPeriode as $periode => $presidenten): ?>
                        <div class="mb-16">
                            <!-- Period Header -->
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary rounded-full mb-4">
                                    <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <h3 class="text-2xl md:text-3xl font-bold text-white">
                                        <?= htmlspecialchars($periode) ?>
                                    </h3>
                                </div>
                                
                                <!-- Presidential decorative border -->
                                <div class="flex items-center justify-center">
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-blue-300 to-transparent"></div>
                                    <div class="mx-4 flex space-x-1">
                                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-red-300 to-transparent"></div>
                                </div>
                            </div>
                            
                            <!-- Presidents Grid - Symmetrische Layout -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6">
                                <?php 
                                // Sorteer presidenten van nieuwste naar oudste
                                $gesorteerdePresidenten = $presidenten;
                                if (is_array($gesorteerdePresidenten)) {
                                    usort($gesorteerdePresidenten, function($a, $b) {
                                        return $b->president_nummer - $a->president_nummer;
                                    });
                                }
                                ?>
                                <?php foreach ($gesorteerdePresidenten as $president): ?>
                                    <div class="group h-full">
                                        <!-- Card Container met gefixeerde hoogte voor symmetrie -->
                                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden relative h-full flex flex-col">
                                            
                                            <!-- Presidential number badge -->
                                            <div class="absolute top-3 left-3 w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center z-10 shadow-md">
                                                <span class="text-white font-black text-xs"><?= $president->president_nummer ?></span>
                                            </div>
                                            
                                            <!-- Party color indicator -->
                                            <div class="absolute top-3 right-3 w-3 h-3 rounded-full shadow-md <?= $president->partij === 'Republican' ? 'bg-red-500' : ($president->partij === 'Democratic' ? 'bg-blue-500' : 'bg-gray-500') ?>"></div>
                                            
                                            <!-- President Photo - Consistente aspect ratio -->
                                            <div class="relative w-full aspect-[4/5] bg-gradient-to-b from-slate-100 to-slate-200 flex-shrink-0 overflow-hidden rounded-t-xl">
                                                <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                    <!-- Main photo container met letterboxing effect -->
                                                    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 to-slate-100"></div>
                                                    <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                         alt="<?= htmlspecialchars($president->naam) ?>"
                                                         class="w-full h-full object-contain group-hover:scale-110 transition-all duration-500 ease-out relative z-10"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <!-- Fallback presidential icon -->
                                                    <div class="w-full h-full bg-gradient-to-b from-slate-200 to-slate-300 flex items-center justify-center absolute inset-0 z-10" style="display: none;">
                                                        <div class="text-center">
                                                            <svg class="w-16 h-16 text-slate-400 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                            <div class="text-xs text-slate-500 font-medium">Geen foto</div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-full h-full bg-gradient-to-b from-slate-200 to-slate-300 flex items-center justify-center">
                                                        <div class="text-center">
                                                            <svg class="w-16 h-16 text-slate-400 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                            <div class="text-xs text-slate-500 font-medium">Geen foto</div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Subtle vignette effect voor betere badge leesbaarheid -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-black/10 pointer-events-none"></div>
                                            </div>
                                            
                                            <!-- President Content - Flexibele content area -->
                                            <div class="p-4 flex-1 flex flex-col">
                                                <!-- Name and basic info - Gefixeerde hoogte -->
                                                <div class="text-center mb-3 h-20 flex flex-col justify-center">
                                                    <h3 class="text-base font-bold text-gray-900 mb-1 leading-tight">
                                                        <?= htmlspecialchars($president->naam) ?>
                                                    </h3>
                                                    <?php if (isset($president->bijnaam) && !empty($president->bijnaam)): ?>
                                                        <p class="text-xs text-gray-500 italic line-clamp-1">"<?= htmlspecialchars($president->bijnaam) ?>"</p>
                                                    <?php endif; ?>
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        <?= htmlspecialchars($president->partij) ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Term info - Compacte weergave -->
                                                <div class="bg-gray-50 rounded-lg p-2 mb-3 flex-shrink-0">
                                                    <div class="text-center">
                                                        <div class="text-sm font-medium text-gray-700">
                                                            <?= date('Y', strtotime($president->periode_start)) ?> - 
                                                            <?= $president->periode_eind ? date('Y', strtotime($president->periode_eind)) : 'heden' ?>
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <?= isset($president->jaren_in_functie) ? $president->jaren_in_functie : 'N/A' ?> jaar
                                                            <?php if (isset($president->leeftijd_bij_aantreden)): ?>
                                                                • <?= $president->leeftijd_bij_aantreden ?> jaar oud
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Content preview - Uniforme hoogte -->
                                                <div class="flex-1 mb-3 min-h-[80px] flex flex-col justify-start">
                                                    <!-- Key achievement of fun fact -->
                                                    <?php 
                                                    $hasAchievements = isset($president->prestaties) && is_array($president->prestaties) && !empty($president->prestaties);
                                                    $hasFunFacts = isset($president->fun_facts) && is_array($president->fun_facts) && !empty($president->fun_facts);
                                                    ?>
                                                    
                                                    <?php if ($hasAchievements): ?>
                                                        <div class="bg-green-50 border-l-3 border-green-400 p-2 rounded">
                                                            <div class="flex items-start">
                                                                <svg class="w-3 h-3 text-green-500 mr-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                                </svg>
                                                                <div>
                                                                    <div class="text-xs font-semibold text-green-800 mb-1">Prestatie:</div>
                                                                    <p class="text-xs text-green-700 line-clamp-2">
                                                                        <?= htmlspecialchars(substr($president->prestaties[0], 0, 80)) ?>
                                                                        <?= strlen($president->prestaties[0]) > 80 ? '...' : '' ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php elseif ($hasFunFacts): ?>
                                                        <div class="bg-blue-50 border-l-3 border-blue-400 p-2 rounded">
                                                            <div class="flex items-start">
                                                                <svg class="w-3 h-3 text-blue-400 mr-1 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <div class="text-xs font-semibold text-blue-800 mb-1">Fun Fact:</div>
                                                                    <p class="text-xs text-blue-700 line-clamp-2">
                                                                        <?= htmlspecialchars(substr($president->fun_facts[0], 0, 80)) ?>
                                                                        <?= strlen($president->fun_facts[0]) > 80 ? '...' : '' ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php elseif (isset($president->biografie)): ?>
                                                        <div class="bg-gray-50 p-2 rounded">
                                                            <p class="text-xs text-gray-600 line-clamp-3">
                                                                <?= htmlspecialchars(substr($president->biografie, 0, 120)) ?>
                                                                <?= strlen($president->biografie) > 120 ? '...' : '' ?>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Stats badges -->
                                                <div class="flex justify-center space-x-1 mb-3">
                                                    <?php if ($hasAchievements): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                            </svg>
                                                            <?= count($president->prestaties) ?> prestaties
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($hasFunFacts): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                            <?= count($president->fun_facts) ?> facts
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- View Details Button - Altijd onderaan -->
                                            <div class="px-4 pb-4 mt-auto">
                                                <button onclick="openPresidentModal(<?= htmlspecialchars(json_encode($president)) ?>)" 
                                                        class="w-full text-center py-2 px-3 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-300 border border-gray-200 group-hover:border-transparent cursor-pointer">
                                                    <span class="text-xs font-medium flex items-center justify-center">
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                        </svg>
                                                        Bekijk details
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div class="text-gray-500 text-lg">
                            Er zijn momenteel geen presidenten gegevens beschikbaar.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Presidential Dynasties Section -->
    <?php if (isset($familieDynastieen) && !empty($familieDynastieen)): ?>
        <section class="py-16 md:py-20 bg-gradient-to-br from-primary to-secondary relative overflow-hidden" id="familie-dynastieen">
            <!-- White House silhouette background -->
            <div class="absolute inset-0 opacity-10">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://media.architecturaldigest.com/photos/6559735fb796d428bef00d25/3:2/w_5568,h_3712,c_limit/GettyImages-1731443210.jpg');"></div>
            </div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-12 md:mb-16">
                        <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                            <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                            </svg>
                            <span class="text-white font-semibold">Presidentiële Familie Dynastieën</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                            Macht loopt in de familie
                        </h2>
                        <p class="text-lg md:text-xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                            Verschillende families hebben meerdere presidenten voortgebracht, van vader-zoon combinaties 
                            tot neef-oom verhoudingen. Ontdek deze politieke dynastieën die generaties omspannen.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php foreach ($familieDynastieen as $familieNaam => $familiePresidenten): ?>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <h3 class="text-2xl font-bold text-white mb-4"><?= htmlspecialchars($familieNaam) ?></h3>
                                <div class="space-y-4">
                                    <?php foreach ($familiePresidenten as $president): ?>
                                        <div class="flex items-center space-x-4 bg-white/5 rounded-lg p-3">
                                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold"><?= $president->president_nummer ?></span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-white font-semibold"><?= htmlspecialchars($president->naam) ?></div>
                                                <div class="text-blue-100 text-sm"><?= htmlspecialchars($president->familie_connecties) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<!-- President Detail Modal - Verbeterde Styling -->
<div id="presidentModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-1 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-6xl w-full max-h-[98vh] sm:max-h-[95vh] overflow-hidden animate-fadeIn">
        <div id="modalContent" class="overflow-y-auto max-h-[98vh] sm:max-h-[95vh]">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// JavaScript for presidential modal
function openPresidentModal(president) {
    const modal = document.getElementById('presidentModal');
    const content = document.getElementById('modalContent');
    
    // Create modal content
    content.innerHTML = `
        <div class="relative bg-white rounded-2xl overflow-hidden">
            <!-- Hero Header Section -->
            <div class="relative rounded-t-xl sm:rounded-t-2xl overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-red-600">
                    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.4"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                </div>
                
                <!-- Close button - Verbeterd design -->
                <button onclick="closePresidentModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 z-20 group border border-white/30">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Presidential Header -->
                <div class="relative z-10 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-3 sm:space-y-0 sm:space-x-4 lg:space-x-6">
                        <!-- Presidential Photo -->
                        <div class="relative">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-32 lg:h-32 rounded-full overflow-hidden border-3 sm:border-4 border-white/30 shadow-2xl bg-white/10 backdrop-blur-sm">
                                ${president.foto_url ? 
                                    `<img src="${president.foto_url}" alt="${president.naam}" class="w-full h-full object-cover">` :
                                    `<div class="w-full h-full bg-white/20 flex items-center justify-center">
                                        <svg class="w-8 h-8 sm:w-10 sm:w-10 lg:w-12 lg:h-12 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>`
                                }
                            </div>
                            <!-- Presidential Badge -->
                            <div class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-blue-600">
                                <span class="text-blue-600 font-black text-xs sm:text-sm">${president.president_nummer}</span>
                            </div>
                        </div>
                        
                        <!-- Presidential Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <div class="inline-flex items-center px-2 py-1 sm:px-3 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs font-medium mb-2 sm:mb-3 border border-white/30">
                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                #${president.president_nummer} President van de Verenigde Staten
                            </div>
                            <h2 class="text-xl sm:text-2xl lg:text-4xl font-black text-white mb-1 sm:mb-2 leading-tight">${president.naam}</h2>
                            ${president.bijnaam ? `<div class="text-sm sm:text-lg lg:text-xl text-blue-100 italic mb-1 sm:mb-2">"${president.bijnaam}"</div>` : ''}
                            
                            <!-- President Stats -->
                            <div class="flex flex-wrap justify-center sm:justify-start gap-1 sm:gap-2 mt-2 sm:mt-4">
                                <span class="inline-flex items-center px-2 py-1 sm:px-3 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs sm:text-sm font-medium border border-white/30">
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full mr-1 sm:mr-2 ${president.partij === 'Republican' ? 'bg-red-300' : president.partij === 'Democratic' ? 'bg-blue-300' : 'bg-gray-300'}"></div>
                                    ${president.partij}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 sm:px-3 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs sm:text-sm font-medium border border-white/30">
                                    <svg class="w-2 h-2 sm:w-3 sm:h-3 mr-1 sm:mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    ${new Date(president.periode_start).getFullYear()} - ${president.periode_eind ? new Date(president.periode_eind).getFullYear() : 'heden'}
                                </span>
                                ${president.leeftijd_bij_aantreden ? `
                                    <span class="inline-flex items-center px-2 py-1 sm:px-3 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs sm:text-sm font-medium border border-white/30">
                                        <svg class="w-2 h-2 sm:w-3 sm:h-3 mr-1 sm:mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2.5-7H18V2h-2v2H8V2H6v2H4.5C3.67 4 3 4.67 3 5.5v13C3 19.33 3.67 20 4.5 20h15c.83 0 1.5-.67 1.5-1.5v-13C21 4.67 20.33 4 19.5 4z"/>
                                        </svg>
                                        ${president.leeftijd_bij_aantreden} jaar oud
                                    </span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Wave -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-4 sm:h-6 lg:h-8">
                        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#ffffff"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Content Sections -->
            <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 lg:space-y-8">
                <!-- Biography Section -->
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-100">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center mr-2 sm:mr-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Biografie</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base">${president.biografie}</p>
                </div>
                
                <!-- Achievements Section -->
                ${president.prestaties && president.prestaties.length > 0 ? `
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Belangrijkste Prestaties</h3>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">${president.prestaties.length} prestaties</span>
                        </div>
                        <div class="grid gap-3">
                            ${president.prestaties.map((prestatie, index) => `
                                <div class="bg-white rounded-xl p-4 border border-green-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <span class="text-green-600 font-bold text-xs">${index + 1}</span>
                                        </div>
                                        <span class="text-gray-800 font-medium leading-relaxed">${prestatie}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Fun Facts Section -->
                ${president.fun_facts && president.fun_facts.length > 0 ? `
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Interessante Feiten</h3>
                            <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">${president.fun_facts.length} feiten</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${president.fun_facts.map((fact, index) => `
                                <div class="bg-white rounded-xl p-4 border border-blue-200 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-700 leading-relaxed">${fact}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Personal & Presidential Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Persoonlijke Informatie</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12h-12v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Geboren:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.geboren_formatted || new Date(president.geboren).toLocaleDateString('nl-NL')}</span>
                                </div>
                            </div>
                            ${president.overleden ? `
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-purple-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Overleden:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.overleden_formatted || new Date(president.overleden).toLocaleDateString('nl-NL')}</span>
                                    </div>
                                </div>
                            ` : `
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Status:</span>
                                        <span class="ml-auto font-medium text-green-700">Nog levend</span>
                                    </div>
                                </div>
                            `}
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Geboorteplaats:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.geboorteplaats}</span>
                                </div>
                            </div>
                            ${president.echtgenote ? `
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-purple-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Echtgenote:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.echtgenote}</span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Presidential Information Card -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Presidentiële Informatie</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 border border-amber-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2.5-7H18V2h-2v2H8V2H6v2H4.5C3.67 4 3 4.67 3 5.5v13C3 19.33 3.67 20 4.5 20h15c.83 0 1.5-.67 1.5-1.5v-13C21 4.67 20.33 4 19.5 4z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Leeftijd bij aantreden:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.leeftijd_bij_aantreden} jaar</span>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-amber-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Jaren in functie:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.jaren_in_functie || 'N/A'} jaar</span>
                                </div>
                            </div>
                            ${president.vice_president ? `
                                <div class="bg-white rounded-lg p-3 border border-amber-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-amber-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Vice-president:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.vice_president}</span>
                                    </div>
                                </div>
                            ` : ''}
                            ${president.familie_connecties ? `
                                <div class="bg-white rounded-lg p-3 border border-amber-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-amber-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Familie connecties:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.familie_connecties}</span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePresidentModal() {
    const modal = document.getElementById('presidentModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('presidentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePresidentModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePresidentModal();
    }
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 