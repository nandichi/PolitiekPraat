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
                
                <!-- Navigation breadcrumb -->
                <div class="mb-6">
                    <nav class="flex justify-center" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="<?= URLROOT ?>/amerikaanse-verkiezingen" class="inline-flex items-center text-sm font-medium text-blue-200 hover:text-white">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Amerikaanse Verkiezingen
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-white md:ml-2">Presidents Gallery</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                
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
                            
                            <!-- Presidents Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <?php foreach ($presidenten as $president): ?>
                                    <div class="group">
                                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-gray-100 overflow-hidden relative">
                                            
                                            <!-- Presidential number badge -->
                                            <div class="absolute top-3 left-3 w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center z-10">
                                                <span class="text-white font-black text-sm"><?= $president->president_nummer ?></span>
                                            </div>
                                            
                                            <!-- Party color indicator -->
                                            <div class="absolute top-3 right-3 w-4 h-4 rounded-full <?= $president->partij === 'Republican' ? 'bg-red-500' : ($president->partij === 'Democratic' ? 'bg-blue-500' : 'bg-gray-500') ?>"></div>
                                            
                                            <!-- President Photo -->
                                            <div class="relative h-64 bg-gradient-to-b from-slate-100 to-slate-200">
                                                <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                    <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                         alt="<?= htmlspecialchars($president->naam) ?>"
                                                         class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <!-- Fallback presidential icon -->
                                                    <div class="w-full h-full bg-gradient-to-b from-slate-200 to-slate-300 flex items-center justify-center" style="display: none;">
                                                        <svg class="w-16 h-16 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                        </svg>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-full h-full bg-gradient-to-b from-slate-200 to-slate-300 flex items-center justify-center">
                                                        <svg class="w-16 h-16 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- President Info -->
                                            <div class="p-6">
                                                <!-- Name and nickname -->
                                                <div class="text-center mb-4">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        <?= htmlspecialchars($president->naam) ?>
                                                    </h3>
                                                    <?php if (isset($president->bijnaam) && !empty($president->bijnaam)): ?>
                                                        <p class="text-sm text-gray-500 italic">"<?= htmlspecialchars($president->bijnaam) ?>"</p>
                                                    <?php endif; ?>
                                                    <div class="text-sm text-gray-600 mt-2">
                                                        <?= htmlspecialchars($president->partij) ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Term info -->
                                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                                    <div class="text-center">
                                                        <div class="text-sm font-medium text-gray-700">
                                                            <?= date('Y', strtotime($president->periode_start)) ?> - 
                                                            <?= $president->periode_eind ? date('Y', strtotime($president->periode_eind)) : 'heden' ?>
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <?= isset($president->jaren_in_functie) ? $president->jaren_in_functie : 'N/A' ?> jaar in functie
                                                        </div>
                                                        <?php if (isset($president->leeftijd_bij_aantreden)): ?>
                                                            <div class="text-xs text-gray-500">
                                                                Leeftijd bij aantreden: <?= $president->leeftijd_bij_aantreden ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Key achievements preview -->
                                                <?php if (isset($president->prestaties) && is_array($president->prestaties) && !empty($president->prestaties)): ?>
                                                    <div class="mb-4">
                                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Belangrijkste prestaties:</h4>
                                                        <ul class="text-xs text-gray-600 space-y-1">
                                                            <?php foreach (array_slice($president->prestaties, 0, 2) as $prestatie): ?>
                                                                <li class="flex items-start">
                                                                    <svg class="w-3 h-3 text-green-500 mr-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                                    </svg>
                                                                    <span><?= htmlspecialchars($prestatie) ?></span>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <?php if (count($president->prestaties) > 2): ?>
                                                                <li class="text-primary text-xs font-medium">
                                                                    +<?= count($president->prestaties) - 2 ?> meer...
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Fun fact preview -->
                                                <?php if (isset($president->fun_facts) && is_array($president->fun_facts) && !empty($president->fun_facts)): ?>
                                                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-blue-400 mr-2 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                            <div>
                                                                <h5 class="text-xs font-semibold text-blue-800 mb-1">Fun Fact:</h5>
                                                                <p class="text-xs text-blue-700"><?= htmlspecialchars($president->fun_facts[0]) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Biography preview -->
                                                <?php if (isset($president->biografie)): ?>
                                                    <div class="text-xs text-gray-600 leading-relaxed mb-4">
                                                        <?= htmlspecialchars(substr($president->biografie, 0, 120)) ?>
                                                        <?= strlen($president->biografie) > 120 ? '...' : '' ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- View Details Button -->
                                            <div class="px-6 pb-6">
                                                <button onclick="openPresidentModal(<?= htmlspecialchars(json_encode($president)) ?>)" 
                                                        class="w-full text-center py-3 px-4 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-300 border border-gray-200 group-hover:border-transparent cursor-pointer">
                                                    <span class="text-sm font-medium flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
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

<!-- President Detail Modal -->
<div id="presidentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div id="modalContent">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
// JavaScript for presidential modal
function openPresidentModal(president) {
    const modal = document.getElementById('presidentModal');
    const content = document.getElementById('modalContent');
    
    // Create modal content
    content.innerHTML = `
        <div class="relative">
            <!-- Close button -->
            <button onclick="closePresidentModal()" class="absolute top-4 right-4 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200 z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Header with photo -->
            <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white">
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white/20">
                        ${president.foto_url ? 
                            `<img src="${president.foto_url}" alt="${president.naam}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full bg-white/20 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>`
                        }
                    </div>
                    <div>
                        <div class="text-sm opacity-80">#${president.president_nummer} President van de Verenigde Staten</div>
                        <h2 class="text-3xl font-bold">${president.naam}</h2>
                        ${president.bijnaam ? `<div class="text-lg opacity-90">"${president.bijnaam}"</div>` : ''}
                        <div class="text-sm mt-2">${president.partij} • ${new Date(president.periode_start).getFullYear()} - ${president.periode_eind ? new Date(president.periode_eind).getFullYear() : 'heden'}</div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Biography -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Biografie</h3>
                    <p class="text-gray-700 leading-relaxed">${president.biografie}</p>
                </div>
                
                <!-- Achievements -->
                ${president.prestaties && president.prestaties.length > 0 ? `
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Belangrijkste Prestaties</h3>
                        <ul class="space-y-2">
                            ${president.prestaties.map(prestatie => `
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                    <span class="text-gray-700">${prestatie}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                ` : ''}
                
                <!-- Fun Facts -->
                ${president.fun_facts && president.fun_facts.length > 0 ? `
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Fun Facts</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            ${president.fun_facts.map(fact => `
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-3">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-400 mr-2 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="text-blue-700 text-sm">${fact}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Personal Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Persoonlijke Informatie</h3>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Geboren:</span> ${president.geboren_formatted || new Date(president.geboren).toLocaleDateString('nl-NL')}</div>
                            ${president.overleden ? `<div><span class="font-medium">Overleden:</span> ${president.overleden_formatted || new Date(president.overleden).toLocaleDateString('nl-NL')}</div>` : '<div><span class="font-medium">Status:</span> Nog levend</div>'}
                            <div><span class="font-medium">Geboorteplaats:</span> ${president.geboorteplaats}</div>
                            ${president.echtgenote ? `<div><span class="font-medium">Echtgenote:</span> ${president.echtgenote}</div>` : ''}
                            ${president.vice_president ? `<div><span class="font-medium">Vice-president:</span> ${president.vice_president}</div>` : ''}
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Presidentiële Informatie</h3>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Leeftijd bij aantreden:</span> ${president.leeftijd_bij_aantreden} jaar</div>
                            <div><span class="font-medium">Jaren in functie:</span> ${president.jaren_in_functie || 'N/A'} jaar</div>
                            ${president.familie_connecties ? `<div><span class="font-medium">Familie connecties:</span> ${president.familie_connecties}</div>` : ''}
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