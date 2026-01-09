<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Hero Section for Nederlandse Election Detail -->
    <section class="relative bg-gradient-to-br from-orange-600 via-primary to-blue-700 py-16 md:py-24 overflow-hidden">
        <!-- Background elements with Nederlandse touches -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Nederlandse vlag ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-80 md:h-80 bg-orange-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 md:w-64 md:h-64 bg-blue-400/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                
                <!-- Nederlandse Kroon decoratie -->
                <div class="mb-6 flex justify-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/20">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Main Title with Nederlandse gradient -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-12 tracking-tight">
                    Nederlandse Tweede Kamerverkiezing
                    <span class="block bg-gradient-to-r from-orange-200 via-white to-blue-200 bg-clip-text text-transparent">
                        <?= $verkiezing->jaar ?>
                    </span>
                </h1>
                
                <!-- Political Battle Section with Nederlandse styling -->
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <!-- Largest Party Card with Nederlandse accent -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-400/20 to-orange-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border-2 border-orange-400/40 hover:border-orange-400/60 transition-all duration-300 shadow-2xl">
                                <!-- Winner Crown with Nederlandse colors -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-2 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg border border-orange-400/30 whitespace-nowrap">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                        </svg>
                                        <span class="hidden sm:inline">GROOTSTE NEDERLANDSE PARTIJ</span>
                                        <span class="sm:hidden">GROOTSTE PARTIJ</span>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2 break-words leading-tight">
                                        <?= htmlspecialchars($verkiezing->grootste_partij ?? 'Onbekend') ?>
                                    </h3>
                                    <div class="text-lg text-orange-200 mb-6 font-semibold">
                                        Grootste partij
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-orange-500/20 rounded-xl p-3 border border-orange-400/30">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->grootste_partij_zetels ?? 'Onbekend' ?></div>
                                            <div class="text-orange-200 text-sm">Zetels</div>
                                        </div>
                                        <div class="bg-orange-500/20 rounded-xl p-3 border border-orange-400/30">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->grootste_partij_percentage ? number_format($verkiezing->grootste_partij_percentage, 1) . '%' : 'Onbekend' ?></div>
                                            <div class="text-orange-200 text-sm">Stemmen</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- VS Symbol with Nederlandse colors -->
                        <div class="hidden lg:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                            <div class="bg-white/20 backdrop-blur-md rounded-full w-16 h-16 flex items-center justify-center border-2 border-orange-400/30">
                                <span class="text-white font-black text-xl">VS</span>
                            </div>
                        </div>
                        
                        <!-- Second Party Card with Nederlandse accent -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400/20 to-blue-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border-2 border-blue-400/40 hover:border-blue-400/60 transition-all duration-300 shadow-2xl">
                                <!-- Runner-up Badge with Nederlandse styling -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-2 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg border border-blue-400/30 whitespace-nowrap">
                                        <span class="hidden sm:inline">TWEEDE NEDERLANDSE PARTIJ</span>
                                        <span class="sm:hidden">TWEEDE PARTIJ</span>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2 break-words leading-tight">
                                        <?= htmlspecialchars($verkiezing->tweede_partij ?? 'Onbekend') ?>
                                    </h3>
                                    <div class="text-lg text-blue-200 mb-6 font-semibold">
                                        Tweede partij
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-blue-500/20 rounded-xl p-3 border border-blue-400/30">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->tweede_partij_zetels ?? 'Onbekend' ?></div>
                                            <div class="text-blue-200 text-sm">Zetels</div>
                                        </div>
                                        <div class="bg-blue-500/20 rounded-xl p-3 border border-blue-400/30">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->tweede_partij_percentage ? number_format($verkiezing->tweede_partij_percentage, 1) . '%' : 'Onbekend' ?></div>
                                            <div class="text-blue-200 text-sm">Stemmen</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Minister-president Section with Nederlandse styling -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border-2 border-orange-400/30 max-w-4xl mx-auto mb-8">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-white mb-4">
                                <svg class="w-6 h-6 inline mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                                </svg>
                                Nederlandse Minister-president
                            </h3>
                            <div class="text-3xl font-black text-white mb-2 break-words leading-tight">
                                <?= htmlspecialchars($verkiezing->minister_president) ?>
                            </div>
                            <div class="text-lg text-orange-200 mb-4 break-words">
                                <?= htmlspecialchars($verkiezing->minister_president_partij) ?>
                            </div>
                            <?php if (isset($verkiezing->kabinet_naam) && !empty($verkiezing->kabinet_naam)): ?>
                                <div class="text-blue-200 font-semibold">
                                    Kabinet <?= htmlspecialchars($verkiezing->kabinet_naam) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Election Overview Stats with Nederlandse accents -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border-2 border-white/20 max-w-4xl mx-auto mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <div class="bg-orange-500/20 rounded-xl p-4 border border-orange-400/30">
                                <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $verkiezing->totaal_zetels ?? 150 ?></div>
                                <div class="text-orange-200 text-sm font-medium">Totaal Zetels</div>
                            </div>
                            <div class="bg-blue-500/20 rounded-xl p-4 border border-blue-400/30">
                                <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $verkiezing->opkomst_percentage ? number_format($verkiezing->opkomst_percentage, 1) . '%' : 'Onbekend' ?></div>
                                <div class="text-blue-200 text-sm font-medium">Nederlandse Opkomst</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 border border-white/30">
                                <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $verkiezing->totaal_stemmen ? number_format($verkiezing->totaal_stemmen) : 'Onbekend' ?></div>
                                <div class="text-slate-200 text-sm font-medium">Totaal Stemmen</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Election Context Information -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border-2 border-white/20 max-w-4xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                            <?php if (isset($verkiezing->verkiezings_aanleiding) && !empty($verkiezing->verkiezings_aanleiding)): ?>
                                <div class="bg-red-500/20 rounded-xl p-4 border border-red-400/30">
                                    <div class="text-lg font-bold text-white mb-1"><?= ucfirst($verkiezing->verkiezings_aanleiding) ?></div>
                                    <div class="text-red-200 text-sm font-medium">Verkiezingsreden</div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($verkiezing->kabinet_type) && !empty($verkiezing->kabinet_type)): ?>
                                <div class="bg-purple-500/20 rounded-xl p-4 border border-purple-400/30">
                                    <div class="text-lg font-bold text-white mb-1"><?= ucfirst($verkiezing->kabinet_type) ?></div>
                                    <div class="text-purple-200 text-sm font-medium">Kabinet Type</div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($verkiezing->formatie_duur_dagen) && !empty($verkiezing->formatie_duur_dagen)): ?>
                                <div class="bg-yellow-500/20 rounded-xl p-4 border border-yellow-400/30">
                                    <div class="text-lg font-bold text-white mb-1"><?= $verkiezing->formatie_duur_dagen ?> dagen</div>
                                    <div class="text-yellow-200 text-sm font-medium">Formatie Duur</div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($verkiezing->aantal_partijen_tk) && !empty($verkiezing->aantal_partijen_tk)): ?>
                                <div class="bg-green-500/20 rounded-xl p-4 border border-green-400/30">
                                    <div class="text-lg font-bold text-white mb-1"><?= $verkiezing->aantal_partijen_tk ?></div>
                                    <div class="text-green-200 text-sm font-medium">Partijen in TK</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kabinet Photo Section -->
    <?php if (isset($verkiezing->foto_url) && !empty($verkiezing->foto_url)): ?>
        <section class="py-16 md:py-20 bg-gradient-to-b from-white to-slate-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Kabinet <?= htmlspecialchars($verkiezing->kabinet_naam ?? $verkiezing->jaar) ?>
                        </h2>
                        <p class="text-lg text-gray-600">
                            Het kabinet onder leiding van <?= htmlspecialchars($verkiezing->minister_president) ?>
                        </p>
                    </div>
                    
                    <div class="relative group">
                        <!-- Glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-3xl blur-2xl opacity-50 group-hover:opacity-70 transition-all duration-500"></div>
                        
                        <!-- Photo container -->
                        <div class="relative bg-white rounded-3xl p-4 md:p-6 shadow-2xl border border-gray-100 overflow-hidden">
                            <div class="aspect-video w-full overflow-hidden rounded-2xl">
                                <img src="<?= htmlspecialchars($verkiezing->foto_url) ?>" 
                                     alt="Kabinet <?= htmlspecialchars($verkiezing->kabinet_naam ?? $verkiezing->jaar) ?> - <?= htmlspecialchars($verkiezing->minister_president) ?>" 
                                     class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-700">
                            </div>
                            
                            <!-- Photo caption -->
                            <div class="mt-4 text-center">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Kabinet <?= htmlspecialchars($verkiezing->kabinet_naam ?? $verkiezing->jaar) ?>
                                </h3>
                                <p class="text-gray-600">
                                    Minister-president: <?= htmlspecialchars($verkiezing->minister_president) ?> (<?= htmlspecialchars($verkiezing->minister_president_partij) ?>)
                                </p>
                                
                                <?php if (isset($verkiezing->coalitie_partijen) && !empty($verkiezing->coalitie_partijen)): ?>
                                    <div class="mt-3">
                                        <div class="text-sm text-gray-500 mb-2">Coalitie:</div>
                                        <div class="flex flex-wrap justify-center gap-2">
                                            <?php foreach ($verkiezing->coalitie_partijen as $partij): ?>
                                                <span class="inline-block px-3 py-1 text-xs font-semibold text-primary bg-primary/10 rounded-full border border-primary/20">
                                                    <?= htmlspecialchars($partij) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($verkiezing->kabinet_start_datum) && !empty($verkiezing->kabinet_start_datum)): ?>
                                    <div class="mt-3 text-sm text-gray-500">
                                        <?php 
                                        $startDatum = date('j F Y', strtotime($verkiezing->kabinet_start_datum));
                                        $eindDatum = isset($verkiezing->kabinet_eind_datum) && !empty($verkiezing->kabinet_eind_datum) 
                                            ? date('j F Y', strtotime($verkiezing->kabinet_eind_datum)) 
                                            : 'heden';
                                        ?>
                                        Periode: <?= $startDatum ?> - <?= $eindDatum ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Winners and Losers Section -->
    <?php if ((isset($verkiezing->grootste_winnaar) && !empty($verkiezing->grootste_winnaar)) || (isset($verkiezing->grootste_verliezer) && !empty($verkiezing->grootste_verliezer))): ?>
        <section class="py-16 md:py-20 bg-gradient-to-br from-slate-50 to-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Verkiezingsdynamiek
                        </h2>
                        <p class="text-lg text-gray-600">Grootste winnaars en verliezers van deze verkiezing</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <?php if (isset($verkiezing->grootste_winnaar) && !empty($verkiezing->grootste_winnaar)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-200 to-emerald-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                                <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl p-6 md:p-8 border-2 border-green-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg flex items-center whitespace-nowrap">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                            <span class="hidden sm:inline">GROOTSTE WINNAAR</span>
                                            <span class="sm:hidden">WINNAAR</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6 text-center">
                                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 break-words leading-tight">
                                            <?= htmlspecialchars($verkiezing->grootste_winnaar) ?>
                                        </h3>
                                        <div class="text-lg text-green-700 mb-6 font-semibold">
                                            Zetelwinst
                                        </div>
                                        
                                        <div class="bg-white/80 rounded-xl p-6 border border-green-200">
                                            <div class="text-4xl font-bold text-green-600 mb-2">
                                                +<?= $verkiezing->grootste_winnaar_aantal ?>
                                            </div>
                                            <div class="text-sm text-gray-600 font-medium">Extra zetels gewonnen</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($verkiezing->grootste_verliezer) && !empty($verkiezing->grootste_verliezer)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-red-200 to-pink-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                                <div class="relative bg-gradient-to-br from-red-50 to-pink-50 rounded-3xl p-6 md:p-8 border-2 border-red-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                        <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg flex items-center whitespace-nowrap">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            </svg>
                                            <span class="hidden sm:inline">GROOTSTE VERLIEZER</span>
                                            <span class="sm:hidden">VERLIEZER</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6 text-center">
                                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 break-words leading-tight">
                                            <?= htmlspecialchars($verkiezing->grootste_verliezer) ?>
                                        </h3>
                                        <div class="text-lg text-red-700 mb-6 font-semibold">
                                            Zetelverlies
                                        </div>
                                        
                                        <div class="bg-white/80 rounded-xl p-6 border border-red-200">
                                            <div class="text-4xl font-bold text-red-600 mb-2">
                                                <?= $verkiezing->grootste_verliezer_aantal ?>
                                            </div>
                                            <div class="text-sm text-gray-600 font-medium">Zetels verloren</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Election Results Section -->
    <section class="py-16 md:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Verkiezingsuitslag
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <!-- Largest Party Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-200 to-orange-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                        <div class="relative bg-gradient-to-br from-orange-50 to-orange-50 rounded-3xl p-6 md:p-8 border-2 border-orange-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                            <!-- Winner Badge -->
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg whitespace-nowrap">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">GROOTSTE PARTIJ</span>
                                    <span class="sm:hidden">WINNAAR</span>
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 break-words leading-tight">
                                        <?= htmlspecialchars($verkiezing->grootste_partij ?? 'Onbekend') ?>
                                    </h3>
                                    <div class="text-lg text-orange-700 mb-6 font-semibold">
                                        Grootste partij
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-orange-200">
                                            <div class="text-2xl md:text-3xl font-bold text-orange-600"><?= $verkiezing->grootste_partij_zetels ?? 'Onbekend' ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Zetels</div>
                                        </div>
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-orange-200">
                                            <div class="text-2xl md:text-3xl font-bold text-orange-600"><?= $verkiezing->grootste_partij_stemmen ? number_format($verkiezing->grootste_partij_stemmen) : 'Onbekend' ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Stemmen</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-orange-100 rounded-xl p-4">
                                        <div class="text-xl font-bold text-orange-700">
                                            <?= $verkiezing->grootste_partij_percentage ? number_format($verkiezing->grootste_partij_percentage, 1) . '% van de stemmen' : 'Percentage onbekend' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Party Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-200 to-blue-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                        <div class="relative bg-gradient-to-br from-blue-50 to-blue-50 rounded-3xl p-6 md:p-8 border-2 border-blue-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                            <!-- Runner-up Badge -->
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg whitespace-nowrap">
                                    <span class="hidden sm:inline">TWEEDE PARTIJ</span>
                                    <span class="sm:hidden">TWEEDE</span>
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 break-words leading-tight">
                                        <?= htmlspecialchars($verkiezing->tweede_partij ?? 'Onbekend') ?>
                                    </h3>
                                    <div class="text-lg text-blue-700 mb-6 font-semibold">
                                        Tweede partij
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-blue-200">
                                            <div class="text-2xl md:text-3xl font-bold text-blue-600"><?= $verkiezing->tweede_partij_zetels ?? 'Onbekend' ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Zetels</div>
                                        </div>
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-blue-200">
                                            <div class="text-2xl md:text-3xl font-bold text-blue-600"><?= $verkiezing->tweede_partij_stemmen ? number_format($verkiezing->tweede_partij_stemmen) : 'Onbekend' ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Stemmen</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-blue-100 rounded-xl p-4">
                                        <div class="text-xl font-bold text-blue-700">
                                            <?= $verkiezing->tweede_partij_percentage ? number_format($verkiezing->tweede_partij_percentage, 1) . '% van de stemmen' : 'Percentage onbekend' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Coalition Information -->
                <?php if (isset($verkiezing->coalitie_partijen) && !empty($verkiezing->coalitie_partijen)): ?>
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 mb-8">
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">
                                Coalitie & Oppositie
                            </h3>
                            <p class="text-gray-600">Regering en oppositie na de verkiezingen</p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Coalition -->
                            <div class="space-y-4">
                                <div class="text-center">
                                    <h4 class="text-xl font-bold text-gray-800 mb-1 flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Coalitie
                                    </h4>
                                    <p class="text-gray-600 text-sm">
                                        <?= isset($verkiezing->coalitie_zetels) ? $verkiezing->coalitie_zetels : 'Onbekend' ?> zetels
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <?php foreach ($verkiezing->coalitie_partijen as $partij): ?>
                                        <div class="flex items-center justify-center bg-green-100 rounded-lg px-3 py-2 min-h-[3rem]">
                                            <span class="text-green-700 font-semibold text-sm text-center break-words leading-tight">
                                                <?= htmlspecialchars($partij) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Opposition -->
                            <?php if (isset($verkiezing->oppositie_partijen) && !empty($verkiezing->oppositie_partijen)): ?>
                                <div class="space-y-4">
                                    <div class="text-center">
                                        <h4 class="text-xl font-bold text-gray-800 mb-1 flex items-center justify-center">
                                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                            </svg>
                                            Oppositie
                                        </h4>
                                        <p class="text-gray-600 text-sm">
                                            <?= (150 - (isset($verkiezing->coalitie_zetels) ? $verkiezing->coalitie_zetels : 0)) ?> zetels
                                        </p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <?php foreach ($verkiezing->oppositie_partijen as $partij): ?>
                                            <div class="flex items-center justify-center bg-red-100 rounded-lg px-3 py-2 min-h-[3rem]">
                                                <span class="text-red-700 font-semibold text-sm text-center break-words leading-tight">
                                                    <?= htmlspecialchars($partij) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Nederlandse Tweede Kamer Samenstelling -->
                <div class="bg-gradient-to-br from-slate-50 to-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">
                            Tweede Kamer Samenstelling
                        </h3>
                        <p class="text-gray-600">Alle <?= $verkiezing->aantal_partijen_tk ?? count($verkiezing->partij_uitslagen ?? []) ?> partijen die zetels behaalden</p>
                        <div class="inline-flex items-center mt-2 px-4 py-2 bg-primary/10 rounded-full border border-primary/20">
                            <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-primary font-semibold text-sm">Kiesdrempel: <?= $verkiezing->kiesdrempel_percentage ?? 0.67 ?>%</span>
                        </div>
                    </div>
                    
                    <?php if (isset($verkiezing->partij_uitslagen) && !empty($verkiezing->partij_uitslagen)): ?>
                        <!-- Partij resultaten grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                            <?php 
                            $colors = [
                                'bg-orange-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-red-500',
                                'bg-yellow-500', 'bg-indigo-500', 'bg-pink-500', 'bg-gray-500', 'bg-teal-500',
                                'bg-cyan-500', 'bg-lime-500', 'bg-amber-500', 'bg-emerald-500', 'bg-violet-500'
                            ];
                            $lightColors = [
                                'bg-orange-100', 'bg-blue-100', 'bg-green-100', 'bg-purple-100', 'bg-red-100',
                                'bg-yellow-100', 'bg-indigo-100', 'bg-pink-100', 'bg-gray-100', 'bg-teal-100',
                                'bg-cyan-100', 'bg-lime-100', 'bg-amber-100', 'bg-emerald-100', 'bg-violet-100'
                            ];
                            $textColors = [
                                'text-orange-700', 'text-blue-700', 'text-green-700', 'text-purple-700', 'text-red-700',
                                'text-yellow-700', 'text-indigo-700', 'text-pink-700', 'text-gray-700', 'text-teal-700',
                                'text-cyan-700', 'text-lime-700', 'text-amber-700', 'text-emerald-700', 'text-violet-700'
                            ];
                            ?>
                            
                            <?php foreach ($verkiezing->partij_uitslagen as $index => $partij): ?>
                                <?php 
                                $colorIndex = $index % count($colors);
                                $isCoalition = isset($verkiezing->coalitie_partijen) && in_array($partij->partij, $verkiezing->coalitie_partijen);
                                ?>
                                <div class="relative group">
                                    <div class="bg-white rounded-xl p-4 border-2 <?= $isCoalition ? 'border-green-300 bg-green-50/50' : 'border-gray-200' ?> hover:shadow-lg transition-all duration-300">
                                        <!-- Coalition indicator -->
                                        <?php if ($isCoalition): ?>
                                            <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                COALITIE
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Ranking badge -->
                                        <div class="absolute -top-3 -left-3 w-8 h-8 <?= $colors[$colorIndex] ?> rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            <?= $index + 1 ?>
                                        </div>
                                        
                                        <div class="text-center pt-2">
                                            <h4 class="text-lg font-bold text-gray-900 mb-1 break-words leading-tight"><?= htmlspecialchars($partij->partij) ?></h4>
                                            
                                            <div class="grid grid-cols-2 gap-3 mb-3">
                                                <div class="<?= $lightColors[$colorIndex] ?> rounded-lg p-2">
                                                    <div class="text-xl font-bold <?= $textColors[$colorIndex] ?>"><?= $partij->zetels ?></div>
                                                    <div class="text-xs text-gray-600">Zetels</div>
                                                </div>
                                                <div class="<?= $lightColors[$colorIndex] ?> rounded-lg p-2">
                                                    <div class="text-xl font-bold <?= $textColors[$colorIndex] ?>"><?= isset($partij->percentage) && $partij->percentage ? number_format($partij->percentage, 1) . '%' : 'Onbekend' ?></div>
                                                    <div class="text-xs text-gray-600">Stemmen</div>
                                                </div>
                                            </div>
                                            
                                            <div class="text-xs text-gray-500">
                                                <?= isset($partij->stemmen) && $partij->stemmen ? number_format($partij->stemmen) . ' stemmen' : 'Onbekend aantal stemmen' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Tweede Kamer verdeling visualisatie -->
                        <div class="bg-white rounded-2xl p-6 border border-gray-200">
                            <h4 class="text-xl font-bold text-gray-800 mb-4 text-center">
                                <svg class="w-6 h-6 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Zetelverdeling Visualisatie
                            </h4>
                            
                            <!-- Progress bar style visualization -->
                            <div class="space-y-2">
                                <div class="h-6 bg-gray-200 rounded-full overflow-hidden shadow-inner flex">
                                    <?php foreach ($verkiezing->partij_uitslagen as $index => $partij): ?>
                                        <?php 
                                        $colorIndex = $index % count($colors);
                                        $widthPercentage = ($partij->zetels / ($verkiezing->totaal_zetels ?? 150)) * 100;
                                        ?>
                                        <div class="<?= $colors[$colorIndex] ?> flex items-center justify-center text-white text-xs font-bold" 
                                             style="width: <?= $widthPercentage ?>%"
                                             title="<?= htmlspecialchars($partij->partij) ?>: <?= $partij->zetels ?> zetels">
                                            <?php if ($widthPercentage > 5): ?>
                                                <?= $partij->zetels ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Legend -->
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 text-xs">
                                    <?php foreach ($verkiezing->partij_uitslagen as $index => $partij): ?>
                                        <?php $colorIndex = $index % count($colors); ?>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 <?= $colors[$colorIndex] ?> rounded-full mr-2 flex-shrink-0"></div>
                                            <span class="text-gray-700 break-words"><?= htmlspecialchars($partij->partij) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Majority line -->
                            <div class="text-center mt-4 p-3 bg-green-50 rounded-xl border border-green-200">
                                <div class="text-green-800 font-bold text-sm">
                                    Benodigd voor meerderheid: 76 zetels | 
                                    Coalitie heeft: <?= $verkiezing->coalitie_zetels ?? 'Onbekend' ?> zetels
                                    <?php if (isset($verkiezing->coalitie_zetels) && $verkiezing->coalitie_zetels >= 76): ?>
                                        ✓ <span class="text-green-600">Meerderheid</span>
                                    <?php elseif (isset($verkiezing->coalitie_zetels)): ?>
                                        ✗ <span class="text-red-600">Minderheid</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="text-gray-500">Gedetailleerde verkiezingsgegevens niet beschikbaar</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Information Section -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-slate-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Verkiezingsdetails
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Ontdek de belangrijkste feiten, data en gebeurtenissen van deze Tweede Kamerverkiezing
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <!-- Left Column -->
                    <div class="space-y-8">
                        <!-- Key Dates -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900">Belangrijke Data</h3>
                                </div>
                                
                                <div class="space-y-6">
                                    <?php if (isset($verkiezing->verkiezingsdata) && !empty($verkiezing->verkiezingsdata)): ?>
                                        <div class="flex items-center p-4 bg-gradient-to-r from-primary/5 to-primary/10 rounded-xl">
                                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3m0 2V5m0 0V3m0 2V5"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">Verkiezingsdag</div>
                                                <div class="text-primary font-semibold"><?= date('j F Y', strtotime($verkiezing->verkiezingsdata)) ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($verkiezing->kabinet_start_datum) && !empty($verkiezing->kabinet_start_datum)): ?>
                                        <div class="flex items-center p-4 bg-gradient-to-r from-secondary/5 to-secondary/10 rounded-xl">
                                            <div class="w-10 h-10 bg-secondary/20 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-2a1 1 0 011-1h1a1 1 0 011 1v2M7 16h6M9 12h1"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">Kabinetsstart</div>
                                                <div class="text-secondary font-semibold"><?= date('j F Y', strtotime($verkiezing->kabinet_start_datum)) ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($verkiezing->kabinet_eind_datum) && !empty($verkiezing->kabinet_eind_datum)): ?>
                                        <div class="flex items-center p-4 bg-gradient-to-r from-red-500/5 to-red-500/10 rounded-xl">
                                            <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">Kabinet einde</div>
                                                <div class="text-red-500 font-semibold"><?= date('j F Y', strtotime($verkiezing->kabinet_eind_datum)) ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Key Themes -->
                        <?php if (isset($verkiezing->belangrijkste_themas) && !empty($verkiezing->belangrijkste_themas)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-100/50 to-indigo-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Belangrijkste Thema's</h3>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <?php foreach ($verkiezing->belangrijkste_themas as $index => $thema): ?>
                                            <div class="group/tag">
                                                <span class="inline-flex items-center w-full px-4 py-3 rounded-xl text-sm font-semibold bg-gradient-to-r from-primary/10 to-secondary/10 text-primary border border-primary/20 hover:border-primary/40 transition-all duration-200 hover:shadow-md">
                                                    <span class="w-2 h-2 bg-primary rounded-full mr-3"></span>
                                                    <?= htmlspecialchars($thema) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Nieuwe en Verdwenen Partijen -->
                        <?php if ((isset($verkiezing->nieuwe_partijen) && !empty($verkiezing->nieuwe_partijen)) || (isset($verkiezing->verdwenen_partijen) && !empty($verkiezing->verdwenen_partijen))): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-100/50 to-pink-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Partij Veranderingen</h3>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <?php if (isset($verkiezing->nieuwe_partijen) && !empty($verkiezing->nieuwe_partijen)): ?>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Nieuwe Partijen in de Tweede Kamer
                                                </h4>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <?php foreach ($verkiezing->nieuwe_partijen as $partij): ?>
                                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-green-100 text-green-800 border border-green-200 break-words">
                                                            <span class="w-2 h-2 bg-green-600 rounded-full mr-2 flex-shrink-0"></span>
                                                            <span class="break-words"><?= htmlspecialchars($partij) ?></span>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($verkiezing->verdwenen_partijen) && !empty($verkiezing->verdwenen_partijen)): ?>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Verdwenen uit de Tweede Kamer
                                                </h4>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <?php foreach ($verkiezing->verdwenen_partijen as $partij): ?>
                                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-red-100 text-red-800 border border-red-200 break-words">
                                                            <span class="w-2 h-2 bg-red-600 rounded-full mr-2 flex-shrink-0"></span>
                                                            <span class="break-words"><?= htmlspecialchars($partij) ?></span>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Key Events -->
                        <?php if (isset($verkiezing->belangrijke_gebeurtenissen) && !empty($verkiezing->belangrijke_gebeurtenissen)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-100/50 to-emerald-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Belangrijke Gebeurtenissen</h3>
                                    </div>
                                    
                                    <div class="prose prose-lg max-w-none">
                                        <p class="text-gray-700 leading-relaxed text-lg">
                                            <?= nl2br(htmlspecialchars($verkiezing->belangrijke_gebeurtenissen)) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Notable Facts -->
                        <?php if (isset($verkiezing->opvallende_feiten) && !empty($verkiezing->opvallende_feiten)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-yellow-100/50 to-orange-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Opvallende Feiten</h3>
                                    </div>
                                    
                                    <div class="prose prose-lg max-w-none">
                                        <p class="text-gray-700 leading-relaxed text-lg">
                                            <?= nl2br(htmlspecialchars($verkiezing->opvallende_feiten)) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Lijsttrekkers -->
                        <?php if (isset($verkiezing->lijsttrekkers) && !empty($verkiezing->lijsttrekkers)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-100/50 to-purple-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Lijsttrekkers</h3>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <?php foreach ($verkiezing->lijsttrekkers as $lijsttrekker): ?>
                                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200">
                                                <div class="text-center">
                                                    <div class="font-semibold text-gray-900 break-words"><?= htmlspecialchars($lijsttrekker->naam ?? $lijsttrekker) ?></div>
                                                    <?php if (isset($lijsttrekker->partij)): ?>
                                                        <div class="text-sm text-indigo-600 font-medium break-words"><?= htmlspecialchars($lijsttrekker->partij) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- TV Debatten -->
                        <?php if (isset($verkiezing->tv_debatten) && !empty($verkiezing->tv_debatten)): ?>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-red-100/50 to-pink-100/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">TV Debatten</h3>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <?php 
                                        // Handle both array and string formats
                                        $debatten = [];
                                        if (is_array($verkiezing->tv_debatten)) {
                                            $debatten = $verkiezing->tv_debatten;
                                        } elseif (is_string($verkiezing->tv_debatten)) {
                                            // If it's a string, treat it as a single debat
                                            $debatten = [(object)['naam' => $verkiezing->tv_debatten]];
                                        }
                                        
                                        // Function to detect YouTube URL
                                        function extractYouTubeUrl($text) {
                                            if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $text, $matches)) {
                                                return 'https://www.youtube.com/watch?v=' . $matches[1];
                                            }
                                            return null;
                                        }
                                        
                                        function isOnlyYouTubeUrl($text) {
                                            $text = trim($text);
                                            return preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+$/', $text);
                                        }
                                        ?>
                                        
                                        <?php foreach ($debatten as $debat): ?>
                                            <?php 
                                            $debatText = $debat->naam ?? $debat;
                                            $youtubeUrl = null;
                                            $displayName = $debatText;
                                            
                                            // Check if this debat has a youtube_url property
                                            if (isset($debat->youtube_url) && !empty($debat->youtube_url)) {
                                                $youtubeUrl = $debat->youtube_url;
                                                // If we have a separate youtube_url field, keep the original name
                                            } else {
                                                // Try to extract YouTube URL from the text
                                                $youtubeUrl = extractYouTubeUrl($debatText);
                                                
                                                // If we found a YouTube URL in the text, use generic name
                                                if ($youtubeUrl) {
                                                    $displayName = "TV Verkiezingsdebat";
                                                }
                                            }
                                            ?>
                                            
                                            <?php if ($youtubeUrl): ?>
                                                <!-- Only show this debat if it has a proper YouTube URL -->
                                                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                                                    <!-- YouTube Video Card -->
                                                    <div class="space-y-3">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <div class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($displayName) ?></div>
                                                                <?php if (isset($debat->datum) && !empty($debat->datum)): ?>
                                                                    <div class="text-sm text-red-600 mb-1"><?= htmlspecialchars($debat->datum) ?></div>
                                                                <?php endif; ?>
                                                                <?php if (isset($debat->omroep) && !empty($debat->omroep)): ?>
                                                                    <div class="text-sm text-gray-600"><?= htmlspecialchars($debat->omroep) ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- YouTube Video Player -->
                                                        <div class="space-y-3">
                                                            <?php 
                                                            // Extract video ID for player
                                                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches);
                                                            $videoId = $matches[1] ?? '';
                                                            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                                                            $uniqueId = 'video_' . $videoId . '_' . uniqid();
                                                            ?>
                                                            
                                                            <!-- Video Preview (shows initially) -->
                                                            <div id="<?= $uniqueId ?>_preview" class="relative group cursor-pointer" onclick="loadYouTubePlayer('<?= $videoId ?>', '<?= $uniqueId ?>')">
                                                                <div class="aspect-video bg-gray-900 rounded-xl overflow-hidden relative">
                                                                    <img src="<?= $thumbnailUrl ?>" 
                                                                         alt="YouTube Video Thumbnail" 
                                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjkwIiB2aWV3Qm94PSIwIDAgMTIwIDkwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iMTIwIiBoZWlnaHQ9IjkwIiBmaWxsPSIjMzMzMzMzIi8+CjxwYXRoIGQ9Ik00OCAzNkw3MiA0NUw0OCA1NFYzNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo='">
                                                                    
                                                                    <!-- Play Button Overlay -->
                                                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors duration-300">
                                                                        <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                                                                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                                                <path d="M8 5v14l11-7z"/>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- YouTube Logo -->
                                                                    <div class="absolute top-3 right-3 bg-red-600 rounded px-2 py-1">
                                                                        <svg class="w-6 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                                        </svg>
                                                                    </div>
                                                                    
                                                                    <!-- Play Tekst -->
                                                                    <div class="absolute bottom-4 left-4 bg-black/70 text-white px-3 py-1 rounded-lg text-sm font-medium">
                                                                        Klik om af te spelen
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- YouTube Player (hidden initially) -->
                                                            <div id="<?= $uniqueId ?>_player" class="hidden">
                                                                <div class="aspect-video bg-gray-900 rounded-xl overflow-hidden relative">
                                                                    <iframe id="<?= $uniqueId ?>_iframe"
                                                                            class="w-full h-full"
                                                                            src=""
                                                                            title="YouTube video player"
                                                                            frameborder="0"
                                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                                            referrerpolicy="strict-origin-when-cross-origin"
                                                                            allowfullscreen>
                                                                    </iframe>
                                                                </div>
                                                                
                                                                <!-- Player Controls -->
                                                                <div class="flex items-center justify-between mt-3 px-2">
                                                                    <div class="flex items-center space-x-3">
                                                                        <button onclick="toggleVideo('<?= $uniqueId ?>')" 
                                                                                class="flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors duration-200">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                                                            </svg>
                                                                            Stop
                                                                        </button>
                                                                        

                                                                    </div>
                                                                    
                                                                    <a href="<?= htmlspecialchars($youtubeUrl) ?>" 
                                                                       target="_blank" 
                                                                       rel="noopener noreferrer"
                                                                       class="flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors duration-200">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                        </svg>
                                                                        YouTube
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (isset($debat->beschrijving) && !empty($debat->beschrijving)): ?>
                                                        <div class="mt-2 text-sm text-gray-600">
                                                            <?= htmlspecialchars($debat->beschrijving) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <!-- Regular Debat Card (no YouTube URL found) -->
                                                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($displayName) ?></div>
                                                            <?php if (isset($debat->datum) && !empty($debat->datum)): ?>
                                                                <div class="text-sm text-red-600 mb-1"><?= htmlspecialchars($debat->datum) ?></div>
                                                            <?php endif; ?>
                                                            <?php if (isset($debat->omroep) && !empty($debat->omroep)): ?>
                                                                <div class="text-sm text-gray-600"><?= htmlspecialchars($debat->omroep) ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (isset($debat->beschrijving) && !empty($debat->beschrijving)): ?>
                                                        <div class="mt-2 text-sm text-gray-600">
                                                            <?= htmlspecialchars($debat->beschrijving) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Description -->
                <?php if (isset($verkiezing->beschrijving) && !empty($verkiezing->beschrijving)): ?>
                    <div class="mt-16">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-3xl blur-2xl opacity-50 group-hover:opacity-70 transition-all duration-500"></div>
                            <div class="relative bg-gradient-to-br from-primary/5 via-white to-secondary/5 rounded-3xl p-8 md:p-12 border border-primary/20 shadow-2xl">
                                <div class="text-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Over deze verkiezing</h3>
                                    <p class="text-lg text-gray-600">Een uitgebreide blik op de verkiezing van <?= $verkiezing->jaar ?></p>
                                </div>
                                
                                <div class="max-w-4xl mx-auto">
                                    <div class="prose prose-xl max-w-none">
                                        <div class="text-gray-700 text-xl leading-relaxed font-medium bg-white/50 rounded-2xl p-6 md:p-8 border border-gray-100">
                                            <?= nl2br(htmlspecialchars($verkiezing->beschrijving)) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Bronnen -->
                <?php if (isset($verkiezing->bronnen) && !empty($verkiezing->bronnen)): ?>
                    <div class="mt-16">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-200/50 to-gray-300/50 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 max-w-4xl mx-auto">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900">Bronnen & Referenties</h3>
                                </div>
                                
                                <div class="space-y-3">
                                    <?php foreach ($verkiezing->bronnen as $bron): ?>
                                        <div class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4 text-gray-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                            <?php if (is_object($bron) && isset($bron->naam)): ?>
                                                <div>
                                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($bron->naam) ?></div>
                                                    <?php if (isset($bron->url)): ?>
                                                        <a href="<?= htmlspecialchars($bron->url) ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                                            <?= htmlspecialchars($bron->url) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-800 font-medium"><?= htmlspecialchars($bron) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Navigation Section -->
    <?php if (isset($gerelateerdeVerkiezingen) && ($gerelateerdeVerkiezingen['vorige'] || $gerelateerdeVerkiezingen['volgende'])): ?>
        <section class="py-20 bg-gradient-to-br from-primary via-primary-dark to-secondary relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.05\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">
                            Andere Verkiezingen
                        </h3>
                        <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                            Ontdek andere Tweede Kamerverkiezingen en vergelijk de resultaten door de Nederlandse democratische geschiedenis
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <?php if ($gerelateerdeVerkiezingen['vorige']): ?>
                            <a href="<?= URLROOT ?>/nederlandse-verkiezingen/<?= $gerelateerdeVerkiezingen['vorige']->jaar ?>" 
                               class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-all duration-300"></div>
                                <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border border-white/30 hover:border-white/50 transition-all duration-300 shadow-2xl">
                                    <!-- Direction indicator -->
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-3">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="text-blue-200 text-sm font-semibold mb-3 uppercase tracking-wider">Vorige verkiezing</div>
                                        <div class="text-3xl md:text-4xl font-black text-white mb-4">
                                            <?= $gerelateerdeVerkiezingen['vorige']->jaar ?>
                                        </div>
                                        
                                        <div class="text-xl font-bold text-white mb-2 break-words">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->grootste_partij ?? 'Onbekend') ?>
                                        </div>
                                        <div class="text-blue-200 mb-2">
                                            Grootste partij
                                        </div>
                                        <div class="text-sm text-blue-100 break-words">
                                            MP: <?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->minister_president) ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($gerelateerdeVerkiezingen['volgende']): ?>
                            <a href="<?= URLROOT ?>/nederlandse-verkiezingen/<?= $gerelateerdeVerkiezingen['volgende']->jaar ?>" 
                               class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-all duration-300"></div>
                                <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border border-white/30 hover:border-white/50 transition-all duration-300 shadow-2xl">
                                    <!-- Direction indicator -->
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-3">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="text-blue-200 text-sm font-semibold mb-3 uppercase tracking-wider">Volgende verkiezing</div>
                                        <div class="text-3xl md:text-4xl font-black text-white mb-4">
                                            <?= $gerelateerdeVerkiezingen['volgende']->jaar ?>
                                        </div>
                                        
                                        <div class="text-xl font-bold text-white mb-2 break-words">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->grootste_partij ?? 'Onbekend') ?>
                                        </div>
                                        <div class="text-blue-200 mb-2">
                                            Grootste partij
                                        </div>
                                        <div class="text-sm text-blue-100 break-words">
                                            MP: <?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->minister_president) ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Back to overview button -->
                    <div class="text-center">
                        <a href="<?= URLROOT ?>/nederlandse-verkiezingen" 
                           class="group inline-flex items-center px-8 py-4 bg-white text-primary font-bold rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl">
                            <svg class="mr-3 w-6 h-6 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-lg">Alle verkiezingen bekijken</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<script>
// YouTube Player Functions
function loadYouTubePlayer(videoId, uniqueId) {
    const preview = document.getElementById(uniqueId + '_preview');
    const player = document.getElementById(uniqueId + '_player');
    const iframe = document.getElementById(uniqueId + '_iframe');
    
    // Hide preview and show player
    preview.classList.add('hidden');
    player.classList.remove('hidden');
    
    // Set the iframe source to start playing
    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&fs=1`;
    
    // Smooth scroll to video if it's not fully visible
    setTimeout(() => {
        player.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }, 200);
}

function toggleVideo(uniqueId) {
    const preview = document.getElementById(uniqueId + '_preview');
    const player = document.getElementById(uniqueId + '_player');
    const iframe = document.getElementById(uniqueId + '_iframe');
    
    // Stop the video by clearing the src
    iframe.src = '';
    
    // Show preview and hide player
    player.classList.add('hidden');
    preview.classList.remove('hidden');
    
    // Smooth scroll back to thumbnail
    setTimeout(() => {
        preview.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }, 200);
}

// Auto-stop videos when scrolling away (performance optimization)
let lastScrollTime = 0;
window.addEventListener('scroll', function() {
    const now = Date.now();
    if (now - lastScrollTime > 1000) { // Throttle to every second
        lastScrollTime = now;
        
        const players = document.querySelectorAll('[id$="_player"]:not(.hidden)');
        players.forEach(player => {
            const rect = player.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            // If video is completely out of view, optionally pause it
            if (!isVisible && (rect.bottom < -200 || rect.top > window.innerHeight + 200)) {
                // Video is far out of view - could pause here if desired
                // For now, we'll keep it playing to avoid interrupting user experience
            }
        });
    }
});

// Handle page unload to clean up
window.addEventListener('beforeunload', function() {
    // Clear all iframe sources to stop videos
    const iframes = document.querySelectorAll('[id$="_iframe"]');
    iframes.forEach(iframe => {
        iframe.src = '';
    });
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 