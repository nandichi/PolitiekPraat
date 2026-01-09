<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Hero Section for Election Detail -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 md:py-24 overflow-hidden">
        <!-- Background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-80 md:h-80 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 md:w-64 md:h-64 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                
                <!-- Main Title -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-12 tracking-tight">
                    Verkiezing van
                    <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                        <?= $verkiezing->jaar ?>
                    </span>
                </h1>
                
                <!-- Presidential Battle Section -->
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <!-- Winner Card -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-400/20 to-emerald-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border border-white/30 hover:border-white/50 transition-all duration-300 shadow-2xl">
                                <!-- Winner Crown -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        WINNAAR
                                    </div>
                                </div>
                                
                                <!-- Photo -->
                                <?php if (isset($verkiezing->winnaar_foto_url) && !empty($verkiezing->winnaar_foto_url)): ?>
                                    <div class="mb-6">
                                        <div class="w-32 h-32 md:w-40 md:h-40 mx-auto rounded-full border-4 border-white/50 shadow-2xl overflow-hidden bg-white/20">
                                            <img src="<?= htmlspecialchars($verkiezing->winnaar_foto_url) ?>" 
                                                 alt="<?= htmlspecialchars($verkiezing->winnaar) ?>" 
                                                 class="w-full h-full object-cover" 
                                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-2xl\'><?= substr($verkiezing->winnaar, 0, 2) ?></div>'">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2">
                                        <?= htmlspecialchars($verkiezing->winnaar) ?>
                                    </h3>
                                    <div class="text-lg text-green-200 mb-6 font-semibold">
                                        <?= htmlspecialchars($verkiezing->winnaar_partij) ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white/10 rounded-xl p-3">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->winnaar_kiesmannen ?></div>
                                            <div class="text-green-200 text-sm">Kiesmannen</div>
                                        </div>
                                        <div class="bg-white/10 rounded-xl p-3">
                                            <div class="text-2xl font-bold text-white"><?= number_format($verkiezing->winnaar_percentage_populair, 1) ?>%</div>
                                            <div class="text-green-200 text-sm">Populair</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- VS Symbol -->
                        <div class="hidden lg:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                            <div class="bg-white/20 backdrop-blur-md rounded-full w-16 h-16 flex items-center justify-center border border-white/30">
                                <span class="text-white font-black text-xl">VS</span>
                            </div>
                        </div>
                        
                        <!-- Loser Card -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-400/20 to-rose-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative bg-white/15 backdrop-blur-md rounded-3xl p-8 border border-white/30 hover:border-white/50 transition-all duration-300 shadow-2xl">
                                <!-- Runner-up Badge -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                    <div class="bg-gradient-to-r from-gray-400 to-gray-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                        TWEEDE PLAATS
                                    </div>
                                </div>
                                
                                <!-- Photo -->
                                <?php if (isset($verkiezing->verliezer_foto_url) && !empty($verkiezing->verliezer_foto_url)): ?>
                                    <div class="mb-6">
                                        <div class="w-32 h-32 md:w-40 md:h-40 mx-auto rounded-full border-4 border-white/50 shadow-2xl overflow-hidden bg-white/20">
                                            <img src="<?= htmlspecialchars($verkiezing->verliezer_foto_url) ?>" 
                                                 alt="<?= htmlspecialchars($verkiezing->verliezer) ?>" 
                                                 class="w-full h-full object-cover" 
                                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white font-bold text-2xl\'><?= substr($verkiezing->verliezer, 0, 2) ?></div>'">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2">
                                        <?= htmlspecialchars($verkiezing->verliezer) ?>
                                    </h3>
                                    <div class="text-lg text-red-200 mb-6 font-semibold">
                                        <?= htmlspecialchars($verkiezing->verliezer_partij) ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white/10 rounded-xl p-3">
                                            <div class="text-2xl font-bold text-white"><?= $verkiezing->verliezer_kiesmannen ?></div>
                                            <div class="text-red-200 text-sm">Kiesmannen</div>
                                        </div>
                                        <div class="bg-white/10 rounded-xl p-3">
                                            <div class="text-2xl font-bold text-white"><?= number_format($verkiezing->verliezer_percentage_populair, 1) ?>%</div>
                                            <div class="text-red-200 text-sm">Populair</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Election Overview Stats -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border border-white/20 max-w-4xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <div class="bg-white/10 rounded-xl p-4">
                                <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $verkiezing->totaal_kiesmannen ?? 538 ?></div>
                                <div class="text-blue-100 text-sm font-medium">Totaal Kiesmannen</div>
                            </div>
                            <?php if (isset($verkiezing->opkomst_percentage) && $verkiezing->opkomst_percentage): ?>
                                <div class="bg-white/10 rounded-xl p-4">
                                    <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= number_format($verkiezing->opkomst_percentage, 1) ?>%</div>
                                    <div class="text-blue-100 text-sm font-medium">Opkomstpercentage</div>
                                </div>
                            <?php endif; ?>
                            <div class="bg-white/10 rounded-xl p-4">
                                <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= number_format($verkiezing->winnaar_stemmen_populair + $verkiezing->verliezer_stemmen_populair) ?></div>
                                <div class="text-blue-100 text-sm font-medium">Totaal Stemmen</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Election Results Section -->
    <section class="py-16 md:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Verkiezingsuitslag
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <!-- Winner Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-200 to-emerald-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                        <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl p-6 md:p-8 border-2 border-green-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                            <!-- Winner Badge -->
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    WINNAAR
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <!-- Photo -->
                                <?php if (isset($verkiezing->winnaar_foto_url) && !empty($verkiezing->winnaar_foto_url)): ?>
                                    <div class="mb-6">
                                        <div class="w-28 h-28 md:w-32 md:h-32 mx-auto rounded-full border-4 border-green-300 shadow-xl overflow-hidden bg-green-100">
                                            <img src="<?= htmlspecialchars($verkiezing->winnaar_foto_url) ?>" 
                                                 alt="<?= htmlspecialchars($verkiezing->winnaar) ?>" 
                                                 class="w-full h-full object-cover" 
                                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xl\'><?= substr($verkiezing->winnaar, 0, 2) ?></div>'">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                        <?= htmlspecialchars($verkiezing->winnaar) ?>
                                    </h3>
                                    <div class="text-lg text-green-700 mb-6 font-semibold">
                                        <?= htmlspecialchars($verkiezing->winnaar_partij) ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-green-200">
                                            <div class="text-2xl md:text-3xl font-bold text-green-600"><?= $verkiezing->winnaar_kiesmannen ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Kiesmannen</div>
                                        </div>
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-green-200">
                                            <div class="text-2xl md:text-3xl font-bold text-green-600"><?= number_format($verkiezing->winnaar_stemmen_populair) ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Populaire stemmen</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-green-100 rounded-xl p-4">
                                        <div class="text-xl font-bold text-green-700">
                                            <?= number_format($verkiezing->winnaar_percentage_populair, 1) ?>% van de stemmen
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Runner-up Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-200 to-rose-300 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
                        <div class="relative bg-gradient-to-br from-red-50 to-rose-50 rounded-3xl p-6 md:p-8 border-2 border-red-200 shadow-xl hover:shadow-2xl transition-all duration-300">
                            <!-- Runner-up Badge -->
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                                    TWEEDE PLAATS
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <!-- Photo -->
                                <?php if (isset($verkiezing->verliezer_foto_url) && !empty($verkiezing->verliezer_foto_url)): ?>
                                    <div class="mb-6">
                                        <div class="w-28 h-28 md:w-32 md:h-32 mx-auto rounded-full border-4 border-red-300 shadow-xl overflow-hidden bg-red-100">
                                            <img src="<?= htmlspecialchars($verkiezing->verliezer_foto_url) ?>" 
                                                 alt="<?= htmlspecialchars($verkiezing->verliezer) ?>" 
                                                 class="w-full h-full object-cover" 
                                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white font-bold text-xl\'><?= substr($verkiezing->verliezer, 0, 2) ?></div>'">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                        <?= htmlspecialchars($verkiezing->verliezer) ?>
                                    </h3>
                                    <div class="text-lg text-red-700 mb-6 font-semibold">
                                        <?= htmlspecialchars($verkiezing->verliezer_partij) ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-red-200">
                                            <div class="text-2xl md:text-3xl font-bold text-red-600"><?= $verkiezing->verliezer_kiesmannen ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Kiesmannen</div>
                                        </div>
                                        <div class="bg-white/80 rounded-xl p-4 text-center border border-red-200">
                                            <div class="text-2xl md:text-3xl font-bold text-red-600"><?= number_format($verkiezing->verliezer_stemmen_populair) ?></div>
                                            <div class="text-sm text-gray-600 font-medium">Populaire stemmen</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-red-100 rounded-xl p-4">
                                        <div class="text-xl font-bold text-red-700">
                                            <?= number_format($verkiezing->verliezer_percentage_populair, 1) ?>% van de stemmen
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Electoral College vs Popular Vote -->
                <div class="bg-gradient-to-br from-slate-50 to-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">
                            Vergelijking Stemmen
                        </h3>
                        <p class="text-gray-600">Kiesmannen versus Populaire Stemmen</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Electoral College Chart -->
                        <div class="space-y-4">
                            <div class="text-center">
                                <h4 class="text-xl font-bold text-gray-800 mb-1">
                                    <svg class="w-6 h-6 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-2a1 1 0 011-1h1a1 1 0 011 1v2M7 16h6M9 12h1"></path>
                                    </svg>
                                    Kiesmannen
                                </h4>
                                <p class="text-gray-600 text-xs"><?= $verkiezing->totaal_kiesmannen ?? 538 ?> totaal beschikbaar</p>
                            </div>
                            
                            <?php 
                            $total_electoral = $verkiezing->totaal_kiesmannen ?? 538;
                            $winner_percentage_electoral = ($verkiezing->winnaar_kiesmannen / $total_electoral) * 100;
                            $loser_percentage_electoral = ($verkiezing->verliezer_kiesmannen / $total_electoral) * 100;
                            ?>
                            
                            <!-- Chart -->
                            <div class="space-y-3">
                                <div class="h-8 bg-gray-200 rounded-full overflow-hidden shadow-inner relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                    <div class="h-full bg-gradient-to-r from-green-400 to-green-600 float-left shadow-lg relative z-10" style="width: <?= $winner_percentage_electoral ?>%"></div>
                                    <div class="h-full bg-gradient-to-r from-red-400 to-red-600 float-left shadow-lg relative z-10" style="width: <?= $loser_percentage_electoral ?>%"></div>
                                </div>
                                
                                <!-- Labels in grid -->
                                <div class="grid grid-cols-1 gap-2">
                                    <div class="flex items-center justify-center bg-green-100 rounded-lg px-3 py-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                                        <span class="text-green-700 font-semibold text-xs text-center">
                                            <?= htmlspecialchars($verkiezing->winnaar) ?>: <?= $verkiezing->winnaar_kiesmannen ?> (<?= number_format($winner_percentage_electoral, 1) ?>%)
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-center bg-red-100 rounded-lg px-3 py-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2 flex-shrink-0"></div>
                                        <span class="text-red-700 font-semibold text-xs text-center">
                                            <?= htmlspecialchars($verkiezing->verliezer) ?>: <?= $verkiezing->verliezer_kiesmannen ?> (<?= number_format($loser_percentage_electoral, 1) ?>%)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Winner indicator -->
                            <div class="text-center p-3 bg-green-50 rounded-xl border border-green-200">
                                <div class="text-green-800 font-bold text-sm">
                                    Benodigd voor winst: <?= ceil(($verkiezing->totaal_kiesmannen ?? 538) / 2) ?> kiesmannen
                                </div>
                            </div>
                        </div>
                        
                        <!-- Popular Vote Chart -->
                        <div class="space-y-4">
                            <div class="text-center">
                                <h4 class="text-xl font-bold text-gray-800 mb-1">
                                    <svg class="w-6 h-6 inline mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Populaire Stemmen
                                </h4>
                                <p class="text-gray-600 text-xs"><?= number_format($verkiezing->winnaar_stemmen_populair + $verkiezing->verliezer_stemmen_populair) ?> totaal uitgebracht</p>
                            </div>
                            
                            <!-- Chart -->
                            <div class="space-y-3">
                                <div class="h-8 bg-gray-200 rounded-full overflow-hidden shadow-inner relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                    <div class="h-full bg-gradient-to-r from-green-400 to-green-600 float-left shadow-lg relative z-10" style="width: <?= $verkiezing->winnaar_percentage_populair ?>%"></div>
                                    <div class="h-full bg-gradient-to-r from-red-400 to-red-600 float-left shadow-lg relative z-10" style="width: <?= $verkiezing->verliezer_percentage_populair ?>%"></div>
                                </div>
                                
                                <!-- Labels in grid -->
                                <div class="grid grid-cols-1 gap-2">
                                    <div class="flex items-center justify-center bg-green-100 rounded-lg px-3 py-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                                        <span class="text-green-700 font-semibold text-xs text-center">
                                            <?= htmlspecialchars($verkiezing->winnaar) ?>: <?= number_format($verkiezing->winnaar_stemmen_populair) ?> (<?= number_format($verkiezing->winnaar_percentage_populair, 1) ?>%)
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-center bg-red-100 rounded-lg px-3 py-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2 flex-shrink-0"></div>
                                        <span class="text-red-700 font-semibold text-xs text-center">
                                            <?= htmlspecialchars($verkiezing->verliezer) ?>: <?= number_format($verkiezing->verliezer_stemmen_populair) ?> (<?= number_format($verkiezing->verliezer_percentage_populair, 1) ?>%)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Margin indicator -->
                            <?php 
                            $margin = abs($verkiezing->winnaar_stemmen_populair - $verkiezing->verliezer_stemmen_populair);
                            $margin_percentage = abs($verkiezing->winnaar_percentage_populair - $verkiezing->verliezer_percentage_populair);
                            ?>
                            <div class="text-center p-3 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="text-blue-800 font-bold text-sm">
                                    Verschil: <?= number_format($margin) ?> stemmen (<?= number_format($margin_percentage, 1) ?>%)
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comparison Note -->
                    <?php if ($verkiezing->winnaar_percentage_populair < 50): ?>
                        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span class="text-yellow-800 font-semibold">
                                    Let op: De winnaar behaalde geen meerderheid van de populaire stemmen (onder de 50%).
                                </span>
                            </div>
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
                        Ontdek de belangrijkste feiten, data en gebeurtenissen van deze presidentsverkiezing
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
                                    <?php if (isset($verkiezing->verkiezingsdata_formatted)): ?>
                                        <div class="flex items-center p-4 bg-gradient-to-r from-primary/5 to-primary/10 rounded-xl">
                                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3m0 2V5m0 0V3m0 2V5"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">Verkiezingsdag</div>
                                                <div class="text-primary font-semibold"><?= $verkiezing->verkiezingsdata_formatted ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($verkiezing->inhuldiging_data_formatted)): ?>
                                        <div class="flex items-center p-4 bg-gradient-to-r from-secondary/5 to-secondary/10 rounded-xl">
                                            <div class="w-10 h-10 bg-secondary/20 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-2a1 1 0 011-1h1a1 1 0 011 1v2M7 16h6M9 12h1"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">Inhuldiging</div>
                                                <div class="text-secondary font-semibold"><?= $verkiezing->inhuldiging_data_formatted ?></div>
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
                            Ontdek andere presidentiële verkiezingen en vergelijk de resultaten door de geschiedenis heen
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <?php if ($gerelateerdeVerkiezingen['vorige']): ?>
                            <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/<?= $gerelateerdeVerkiezingen['vorige']->jaar ?>" 
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
                                        
                                        <!-- Winner info with photo if available -->
                                        <?php if (isset($gerelateerdeVerkiezingen['vorige']->winnaar_foto_url) && !empty($gerelateerdeVerkiezingen['vorige']->winnaar_foto_url)): ?>
                                            <div class="mb-4">
                                                <div class="w-16 h-16 mx-auto rounded-full border-2 border-white/50 shadow-lg overflow-hidden bg-white/20">
                                                    <img src="<?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->winnaar_foto_url) ?>" 
                                                         alt="<?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->winnaar) ?>" 
                                                         class="w-full h-full object-cover" 
                                                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm\'><?= substr($gerelateerdeVerkiezingen['vorige']->winnaar, 0, 2) ?></div>'">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="text-xl font-bold text-white mb-2">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->winnaar) ?>
                                        </div>
                                        <div class="text-blue-200">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['vorige']->winnaar_partij) ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($gerelateerdeVerkiezingen['volgende']): ?>
                            <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/<?= $gerelateerdeVerkiezingen['volgende']->jaar ?>" 
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
                                        
                                        <!-- Winner info with photo if available -->
                                        <?php if (isset($gerelateerdeVerkiezingen['volgende']->winnaar_foto_url) && !empty($gerelateerdeVerkiezingen['volgende']->winnaar_foto_url)): ?>
                                            <div class="mb-4">
                                                <div class="w-16 h-16 mx-auto rounded-full border-2 border-white/50 shadow-lg overflow-hidden bg-white/20">
                                                    <img src="<?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->winnaar_foto_url) ?>" 
                                                         alt="<?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->winnaar) ?>" 
                                                         class="w-full h-full object-cover" 
                                                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm\'><?= substr($gerelateerdeVerkiezingen['volgende']->winnaar, 0, 2) ?></div>'">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="text-xl font-bold text-white mb-2">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->winnaar) ?>
                                        </div>
                                        <div class="text-blue-200">
                                            <?= htmlspecialchars($gerelateerdeVerkiezingen['volgende']->winnaar_partij) ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Back to overview button -->
                    <div class="text-center">
                        <a href="<?= URLROOT ?>/amerikaanse-verkiezingen" 
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

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 