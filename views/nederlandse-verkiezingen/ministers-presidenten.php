<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Modern Nederlandse Hero Section for Ministers-Presidenten -->
    <section class="relative min-h-[50vh] flex items-center justify-center overflow-hidden py-8 md:py-16">
        <!-- Background Image with Nederlandse Overlay -->
        <div class="absolute inset-0">
            <!-- Nederlandse Parliament (Binnenhof) Background -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Tweede_Kamer_der_Staten-Generaal.jpg/640px-Tweede_Kamer_der_Staten-Generaal.jpg');">
            </div>
            
            <!-- Dark Gradient Overlay for Better Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/95 via-slate-800/90 to-slate-900/95"></div>
            
            <!-- Nederlandse Vlag Tint for Dutch Theme -->
            <div class="absolute inset-0 bg-gradient-to-br from-orange-900/40 via-transparent to-blue-900/30"></div>
            
            <!-- Nederlandse Oranje Animated Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-orange-500/10 to-transparent animate-pulse" style="animation-duration: 5s;"></div>
        </div>
        
        <!-- Nederlandse Crown and Parliament Elements -->
        <div class="absolute top-20 left-10 w-3 h-3 bg-orange-500 rounded-full animate-ping opacity-75 hidden md:block"></div>
        <div class="absolute top-32 right-20 w-2 h-2 bg-blue-600 rounded-full animate-ping opacity-60 hidden md:block" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-1/4 w-2 h-2 bg-orange-400 rounded-full animate-ping opacity-40 hidden md:block" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-60 right-1/3 w-1.5 h-1.5 bg-white rounded-full animate-ping opacity-50 hidden md:block" style="animation-delay: 3s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                
                <!-- Nederlandse Kroon decoratie -->
                <div class="mb-6 flex justify-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/20">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Main Heading with Nederlandse styling -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 md:mb-6 tracking-tight leading-tight">
                    <span class="block">Nederlandse</span>
                    <span class="block bg-gradient-to-r from-orange-300 via-white to-blue-300 bg-clip-text text-transparent">
                        Ministers-presidenten
                    </span>
                </h1>
                
                <!-- Nederlandse subtitle -->
                <p class="text-base sm:text-lg md:text-xl text-slate-200 mb-3 md:mb-4 font-light leading-relaxed max-w-3xl mx-auto">
                    Van Thorbeckiaanse liberalen tot moderne Nederlandse coalitievorming
                </p>
                
                <!-- Nederlandse description -->
                <p class="text-sm sm:text-base text-slate-300 mb-6 leading-relaxed max-w-2xl mx-auto">
                    Ontdek de Nederlandse leiders die Nederland bestuurden door <?= isset($presidentenStatistieken) ? $presidentenStatistieken->totaal_presidenten : '15' ?> ministers-presidenten en 175 jaar democratische geschiedenis.
                </p>
                
                <!-- Nederlandse Quick Stats Row -->
                <?php if (isset($presidentenStatistieken)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-6 max-w-3xl mx-auto">
                    <div class="bg-orange-500/10 backdrop-blur-md rounded-xl p-4 border border-orange-400/30 hover:bg-orange-500/15 transition-all duration-300 group">
                        <div class="text-xl md:text-2xl font-black text-white mb-1 group-hover:scale-110 transition-transform duration-300">
                            <?= $presidentenStatistieken->totaal_presidenten ?>
                        </div>
                        <div class="text-orange-200 font-medium text-sm">Nederlandse Ministers-presidenten</div>
                    </div>
                    
                    <div class="bg-blue-500/10 backdrop-blur-md rounded-xl p-4 border border-blue-400/30 hover:bg-blue-500/15 transition-all duration-300 group">
                        <div class="text-xl md:text-2xl font-black text-white mb-1 group-hover:scale-110 transition-transform duration-300">
                            <?= $presidentenStatistieken->aantal_partijen ?>
                        </div>
                        <div class="text-blue-200 font-medium text-sm">Nederlandse partijen</div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300 group">
                        <div class="text-xl md:text-2xl font-black text-white mb-1 group-hover:scale-110 transition-transform duration-300">
                            <?= round($presidentenStatistieken->gemiddelde_termijn_dagen / 365, 1) ?>
                        </div>
                        <div class="text-slate-300 font-medium text-sm">Gemiddelde termijn (jaar)</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Ministers-presidenten by Era -->
    <section class="py-16 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <?php if (isset($presidentenPerEra) && !empty($presidentenPerEra)): ?>
                    <?php foreach ($presidentenPerEra as $era => $presidenten): ?>
                        <div class="mb-16">
                            <!-- Nederlandse Era Header -->
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 via-primary to-blue-600 rounded-full mb-4 border-2 border-orange-400/30 shadow-xl">
                                    <svg class="w-6 h-6 text-white mr-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                    </svg>
                                    <h3 class="text-2xl md:text-3xl font-bold text-white">
                                        <?= htmlspecialchars($era) ?>
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
                            
                            <!-- Presidents Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($presidenten as $president): ?>
                                    <div class="group h-full">
                                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border-2 border-orange-200/30 hover:border-orange-400/50 overflow-hidden h-full flex flex-col">
                                            
                                            <!-- President Header with Nederlandse styling -->
                                            <div class="bg-gradient-to-r from-orange-500 via-primary to-blue-600 p-6 text-center relative">
                                                <!-- President Number Badge -->
                                                <div class="absolute top-3 right-3 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm"><?= $president->minister_president_nummer ?></span>
                                                </div>
                                                
                                                <!-- President Photo -->
                                                <div class="w-20 h-20 mx-auto mb-4 relative">
                                                    <?php if (isset($president->foto_url) && !empty($president->foto_url)): ?>
                                                        <img src="<?= htmlspecialchars($president->foto_url) ?>" 
                                                             alt="<?= htmlspecialchars($president->naam) ?>"
                                                             class="w-full h-full rounded-full object-cover border-4 border-white/50 shadow-lg group-hover:scale-110 transition-transform duration-300"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="w-full h-full bg-white/20 rounded-full flex items-center justify-center absolute top-0 left-0" style="display: none;">
                                                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-white/20 rounded-full flex items-center justify-center border-4 border-white/50 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Name and Party -->
                                                <div class="text-lg font-bold text-white mb-1">
                                                    <?= htmlspecialchars($president->naam) ?>
                                                </div>
                                                <div class="text-sm text-blue-100">
                                                    <?= htmlspecialchars($president->partij) ?>
                                                </div>
                                            </div>
                                            
                                            <!-- President Details -->
                                            <div class="p-6 flex-1 flex flex-col">
                                                <!-- Term Period -->
                                                <div class="mb-4">
                                                    <div class="text-sm text-gray-600 mb-1">Termijn</div>
                                                    <div class="font-semibold text-gray-900">
                                                        <?= date('j F Y', strtotime($president->periode_start)) ?> - 
                                                        <?= $president->is_huidig ? 'heden' : date('j F Y', strtotime($president->periode_eind)) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?= round($president->dagen_in_functie / 365, 1) ?> jaar in functie
                                                    </div>
                                                </div>
                                                
                                                <!-- Key Details -->
                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <div class="text-xs text-gray-500">Leeftijd aantreden</div>
                                                        <div class="font-medium text-gray-900"><?= $president->leeftijd_bij_aantreden ?> jaar</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Geboorteplaats</div>
                                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($president->geboorteplaats) ?></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Biography Preview -->
                                                <div class="flex-1 mb-4">
                                                    <?php if (!empty($president->biografie)): ?>
                                                    <div class="text-sm text-gray-600 leading-relaxed h-12 overflow-hidden">
                                                        <?= htmlspecialchars(substr($president->biografie, 0, 120)) ?>...
                                                    </div>
                                                    <?php else: ?>
                                                    <div class="h-12"></div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Footer section - Status Badge and Button -->
                                                <div class="mt-auto">
                                                    <!-- Status Badge -->
                                                    <div class="mb-4 flex justify-center">
                                                        <?php if ($president->is_huidig): ?>
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                                </svg>
                                                                Huidig
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                                Voormalig MP
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- View Details Button -->
                                                    <div>
                                                        <button onclick="openPresidentModal(<?= htmlspecialchars(json_encode($president)) ?>)" 
                                                                class="w-full text-center py-2 px-3 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-300 border border-gray-200 group-hover:border-transparent cursor-pointer">
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
                            Er zijn momenteel geen ministers-presidenten gegevens beschikbaar.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Nederlandse Statistics Section -->
    <?php if (isset($presidentenStatistieken)): ?>
    <section class="py-16 md:py-20 bg-gradient-to-br from-orange-600 via-primary to-blue-700 relative overflow-hidden">
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
                        <span class="text-orange-200 font-semibold">Nederlandse premiers in cijfers</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                        Nederlandse ministers-presidenten in cijfers
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                        Statistieken en trends uit 175 jaar Nederlandse ministerspresident geschiedenis
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    <!-- Total Presidents with Nederlandse styling -->
                    <div class="bg-orange-500/15 backdrop-blur-md rounded-2xl p-6 border border-orange-400/30 text-center hover:bg-orange-500/20 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-orange-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                            </svg>
                        </div>
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">
                            <?= $presidentenStatistieken->totaal_presidenten ?>
                        </div>
                        <div class="text-orange-200 font-medium">Totaal Nederlandse presidenten</div>
                        <div class="text-orange-300 text-sm mt-1">Sinds 1848</div>
                    </div>
                    
                    <!-- VVD Presidents with Nederlandse accent -->
                    <div class="bg-blue-500/15 backdrop-blur-md rounded-2xl p-6 border border-blue-400/30 text-center hover:bg-blue-500/20 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-blue-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">
                            <?= $presidentenStatistieken->vvd_presidenten ?>
                        </div>
                        <div class="text-blue-200 font-medium">VVD Nederlandse premiers</div>
                        <div class="text-blue-300 text-sm mt-1">Liberale traditie</div>
                    </div>
                    
                    <!-- Youngest Age with Nederlandse accent -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center hover:bg-white/15 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-green-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">
                            <?= $presidentenStatistieken->jongste_leeftijd ?>
                        </div>
                        <div class="text-slate-200 font-medium">Jongste leeftijd</div>
                        <div class="text-slate-300 text-sm mt-1">Bij aantreden als Nederlandse MP</div>
                    </div>
                    
                    <!-- Average Term with Nederlandse accent -->
                    <div class="bg-orange-500/15 backdrop-blur-md rounded-2xl p-6 border border-orange-400/30 text-center hover:bg-orange-500/20 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-orange-500/30 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.5 6L12 10.5 8.5 8 12 5.5 15.5 8zM8.5 16L12 13.5 15.5 16 12 18.5 8.5 16z"/>
                            </svg>
                        </div>
                        <div class="text-3xl md:text-4xl font-black text-white mb-2">
                            <?= round($presidentenStatistieken->gemiddelde_termijn_dagen / 365, 1) ?>
                        </div>
                        <div class="text-orange-200 font-medium">Gemiddelde termijn</div>
                        <div class="text-orange-300 text-sm mt-1">Jaar als Nederlandse MP</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Minister-President Detail Modal -->
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
// JavaScript for Minister-President modal
function openPresidentModal(president) {
    const modal = document.getElementById('presidentModal');
    const content = document.getElementById('modalContent');
    
    // Parse JSON fields safely
    const prestaties = president.prestaties_parsed || [];
    const funFacts = president.fun_facts_parsed || [];
    const kinderen = president.kinderen_parsed || [];
    const kabinetten = president.kabinetten_parsed || [];
    const coalitiepartners = president.coalitiepartners_parsed || [];
    const wetgeving = president.wetgeving_parsed || [];
    const crises = president.crises_parsed || [];
    const citaten = president.citaten_parsed || [];
    const ministersposten = president.ministersposten_voor_mp_parsed || [];
    
    // Create modal content with Dutch styling
    content.innerHTML = `
        <div class="relative bg-white rounded-2xl overflow-hidden">
            <!-- Nederlandse Hero Header Section -->
            <div class="relative rounded-t-xl sm:rounded-t-2xl overflow-hidden">
                <!-- Background Pattern with Nederlandse vlag colors -->
                <div class="absolute inset-0 bg-gradient-to-br from-orange-600 via-white to-blue-600">
                    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.4"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                </div>
                
                <!-- Close button -->
                <button onclick="closePresidentModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 z-20 group border border-white/30">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Minister-President Header -->
                <div class="relative z-10 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-3 sm:space-y-0 sm:space-x-4 lg:space-x-6">
                        <!-- MP Photo -->
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
                            <!-- MP Badge -->
                            <div class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-orange-600">
                                <span class="text-orange-600 font-black text-xs sm:text-sm">${president.minister_president_nummer}</span>
                            </div>
                        </div>
                        
                        <!-- MP Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <div class="inline-flex items-center px-2 py-1 sm:px-3 bg-white/20 backdrop-blur-sm rounded-full text-black text-xs font-medium mb-2 sm:mb-3 border border-white/30">
                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                #${president.minister_president_nummer} Minister-president van Nederland
                            </div>
                            <h2 class="text-xl sm:text-2xl lg:text-4xl font-black text-black mb-1 sm:mb-2 leading-tight">${president.naam}</h2>
                            ${president.bijnaam ? `<div class="text-sm sm:text-lg lg:text-xl text-black italic mb-1 sm:mb-2">"${president.bijnaam}"</div>` : ''}
                            
                            <!-- MP Stats -->
                            <div class="flex flex-wrap justify-center sm:justify-start gap-2 sm:gap-3 mt-3 sm:mt-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 sm:px-3 sm:py-1 border border-white/30">
                                    <span class="text-black text-xs sm:text-sm font-medium">${president.partij}</span>
                                </div>
                                <div class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 sm:px-3 sm:py-1 border border-white/30">
                                    <span class="text-black text-xs sm:text-sm font-medium">${new Date(president.periode_start).getFullYear()} - ${president.periode_eind ? new Date(president.periode_eind).getFullYear() : 'heden'}</span>
                                </div>
                                <div class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 sm:px-3 sm:py-1 border border-white/30">
                                    <span class="text-black text-xs sm:text-sm font-medium">${Math.round(president.dagen_in_functie / 365 * 10) / 10} jaar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Sections -->
            <div class="p-4 sm:p-6 lg:p-8 space-y-6">
                
                <!-- Biography Section -->
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Biografie
                    </h3>
                    <p class="text-gray-700 leading-relaxed">${president.biografie || 'Geen biografie beschikbaar.'}</p>
                    
                    ${president.vroeg_leven ? `
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Vroege leven</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">${president.vroeg_leven}</p>
                        </div>
                    ` : ''}
                    
                    ${president.politieke_carriere ? `
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Politieke carrière</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">${president.politieke_carriere}</p>
                        </div>
                    ` : ''}
                </div>
                
                <!-- Fun Facts Section -->
                ${funFacts.length > 0 ? `
                    <div class="bg-orange-50 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Interessante weetjes
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${funFacts.map((fact, index) => `
                                <div class="bg-white rounded-xl p-4 border border-orange-200 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-orange-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-700 leading-relaxed text-sm">${fact}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Personal & Political Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="bg-blue-50 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                            </svg>
                            Persoonlijke informatie
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Geboorteplaats:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.geboorteplaats}</span>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span class="text-gray-600 text-sm">Leeftijd bij aantreden:</span>
                                    <span class="ml-auto font-medium text-gray-900">${president.leeftijd_bij_aantreden || 'N/A'} jaar</span>
                                </div>
                            </div>
                            ${president.onderwijs ? `
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Onderwijs:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.onderwijs}</span>
                                    </div>
                                </div>
                            ` : ''}
                            ${president.echtgenoot_echtgenote ? `
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Echtgenoot/Echtgenote:</span>
                                        <span class="ml-auto font-medium text-gray-900">${president.echtgenoot_echtgenote}</span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Political Info -->
                    <div class="bg-orange-50 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Politieke loopbaan
                        </h3>
                        <div class="space-y-3">
                            ${kabinetten.length > 0 ? `
                                <div class="bg-white rounded-lg p-3 border border-orange-200">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <div class="flex-1">
                                            <span class="text-gray-600 text-sm">Kabinetten:</span>
                                            <div class="mt-1">${kabinetten.map(kabinet => `<span class="inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">${kabinet}</span>`).join('')}</div>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                            ${coalitiepartners.length > 0 ? `
                                <div class="bg-white rounded-lg p-3 border border-orange-200">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63c-.23-.69-.82-1.31-1.67-1.31H16c-.8 0-1.54.37-2.01.99L11 12v7h2v5h2v-5h4v5h1z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <span class="text-gray-600 text-sm">Coalitiepartners:</span>
                                            <div class="mt-1">${coalitiepartners.map(partner => `<span class="inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">${partner}</span>`).join('')}</div>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
                
                <!-- Achievements and Legacy -->
                ${prestaties.length > 0 ? `
                    <div class="bg-green-50 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Belangrijkste prestaties
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            ${prestaties.map(prestatie => `
                                <div class="bg-white rounded-lg p-3 border border-green-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">${prestatie}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Famous Quotes -->
                ${citaten.length > 0 ? `
                    <div class="bg-purple-50 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 17h3l2-4V7h-6v6h3M6 17h3l2-4V7H5v6h3l-2 4z"/>
                            </svg>
                            Bekende uitspraken
                        </h3>
                        <div class="space-y-3">
                            ${citaten.map(citaat => `
                                <div class="bg-white rounded-lg p-4 border border-purple-200 border-l-4 border-l-purple-500">
                                    <p class="text-gray-700 italic">"${citaat}"</p>
                                    <p class="text-purple-600 text-sm font-medium mt-2">- ${president.naam}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
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