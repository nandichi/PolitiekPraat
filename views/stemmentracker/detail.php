<?php
$pageTitle = htmlspecialchars($motie->title) . " - StemmenTracker";
$pageDescription = "Bekijk hoe alle politieke partijen hebben gestemd over: " . htmlspecialchars($motie->title);
$pageKeywords = "stemmentracker, " . htmlspecialchars($motie->onderwerp) . ", stemgedrag, tweede kamer, politieke partijen";

// Include header
include_once 'views/templates/header.php';

// Helper functie voor Nederlandse datum formatting
function formatDutchDate($date) {
    $months = [
        1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
        5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
        9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
    ];
    
    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $months[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$day $month $year";
}
?>

<style>
.glassmorphism {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-pattern {
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"%3E%3Cg opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="1.5" fill="white"/%3E%3Ccircle cx="0" cy="30" r="1" fill="white"/%3E%3Ccircle cx="60" cy="30" r="1" fill="white"/%3E%3Ccircle cx="30" cy="0" r="1" fill="white"/%3E%3Ccircle cx="30" cy="60" r="1" fill="white"/%3E%3C/g%3E%3C/svg%3E');
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
.stagger-4 { animation-delay: 0.4s; }
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-red-50">
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 hero-pattern opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- Header badge -->
                <div class="flex justify-center mb-8 fade-in-up stagger-1">
                    <div class="inline-flex items-center px-4 py-2 glassmorphism rounded-full">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">
                            <?php echo formatDutchDate($motie->datum_stemming); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12 fade-in-up stagger-2">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                        <?php echo htmlspecialchars($motie->title); ?>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($motie->description)); ?>
                    </p>
                </div>
                
                <!-- Metadata cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in-up stagger-3">
                    <div class="glassmorphism rounded-2xl p-6 text-center">
                        <div class="text-sm text-white/70 uppercase tracking-wider mb-2">Onderwerp</div>
                        <div class="text-lg font-bold text-white"><?php echo htmlspecialchars($motie->onderwerp); ?></div>
                    </div>
                    
                    <?php if ($motie->indiener): ?>
                    <div class="glassmorphism rounded-2xl p-6 text-center">
                        <div class="text-sm text-white/70 uppercase tracking-wider mb-2">Indiener</div>
                        <div class="text-lg font-bold text-white"><?php echo htmlspecialchars($motie->indiener); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="glassmorphism rounded-2xl p-6 text-center">
                        <div class="text-sm text-white/70 uppercase tracking-wider mb-2">Type</div>
                        <div class="text-lg font-bold text-white"><?php echo ucfirst($motie->stemming_type); ?></div>
                    </div>
                    
                    <?php if ($motie->uitslag): ?>
                    <div class="glassmorphism rounded-2xl p-6 text-center">
                        <div class="text-sm text-white/70 uppercase tracking-wider mb-2">Uitslag</div>
                        <div class="text-lg font-bold 
                            <?php 
                            echo $motie->uitslag === 'aangenomen' ? 'text-green-300' : 
                                 ($motie->uitslag === 'verworpen' ? 'text-red-300' : 'text-yellow-300'); 
                            ?>">
                            <?php echo ucfirst($motie->uitslag); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Themas en Extra Info -->
                <div class="flex flex-wrap justify-center gap-4 mb-8 fade-in-up stagger-4">
                    <!-- Themas -->
                    <?php if (!empty($motie_themas)): ?>
                        <?php foreach ($motie_themas as $thema): ?>
                            <span class="px-4 py-2 text-sm font-medium rounded-full glassmorphism text-white">
                                <?php echo htmlspecialchars($thema->name); ?>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Motie nummer -->
                    <?php if ($motie->motie_nummer): ?>
                        <span class="px-4 py-2 text-sm font-medium rounded-full glassmorphism text-white">
                            <?php echo htmlspecialchars($motie->motie_nummer); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Kamerstuk link -->
                <?php if ($motie->kamer_stuk_url): ?>
                <div class="text-center fade-in-up stagger-4">
                    <a href="<?php echo htmlspecialchars($motie->kamer_stuk_url); ?>" 
                       target="_blank" rel="noopener"
                       class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all duration-300 font-semibold border border-white/30">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Bekijk officiële kamerstuk
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Stemgedrag Section -->
    <section class="py-16 relative">
        <!-- Subtle background gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-white via-slate-50/50 to-white"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <!-- Section header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-primary via-primary-dark to-secondary bg-clip-text text-transparent mb-4">
                    Hoe stemden de partijen?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Bekijk het volledige stemgedrag van alle politieke partijen voor deze motie
                </p>
            </div>

            <?php if (empty($stemgedrag)): ?>
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-12 text-center border border-gray-100">
                        <div class="w-24 h-24 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-vote-yea text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Geen stemdata beschikbaar</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Voor deze motie is nog geen stemgedrag van politieke partijen vastgelegd in onze database.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Stemming overzicht -->
                <?php
                $voor_count = 0;
                $tegen_count = 0;
                $niet_gestemd_count = 0;
                $afwezig_count = 0;
                
                foreach ($stemgedrag as $stem) {
                    switch ($stem->vote) {
                        case 'voor': $voor_count++; break;
                        case 'tegen': $tegen_count++; break;
                        case 'niet_gestemd': $niet_gestemd_count++; break;
                        case 'afwezig': $afwezig_count++; break;
                    }
                }
                
                $total_votes = count($stemgedrag);
                ?>
                
                <!-- Overzicht stats -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-12 border border-gray-100">
                    <h3 class="text-2xl font-bold text-center mb-8 text-gray-800">Stemming Overzicht</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                        <div class="text-center group">
                            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 mb-4 transform group-hover:scale-105 transition-transform duration-300">
                                <div class="text-4xl font-black text-white mb-2"><?php echo $voor_count; ?></div>
                                <div class="text-green-100 font-medium">Voor</div>
                            </div>
                            <div class="text-lg font-bold text-gray-700">
                                <?php echo $total_votes > 0 ? round(($voor_count / $total_votes) * 100) : 0; ?>%
                            </div>
                        </div>
                        
                        <div class="text-center group">
                            <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-2xl p-6 mb-4 transform group-hover:scale-105 transition-transform duration-300">
                                <div class="text-4xl font-black text-white mb-2"><?php echo $tegen_count; ?></div>
                                <div class="text-red-100 font-medium">Tegen</div>
                            </div>
                            <div class="text-lg font-bold text-gray-700">
                                <?php echo $total_votes > 0 ? round(($tegen_count / $total_votes) * 100) : 0; ?>%
                            </div>
                        </div>
                        
                        <div class="text-center group">
                            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-6 mb-4 transform group-hover:scale-105 transition-transform duration-300">
                                <div class="text-4xl font-black text-white mb-2"><?php echo $niet_gestemd_count; ?></div>
                                <div class="text-yellow-100 font-medium">Niet gestemd</div>
                            </div>
                            <div class="text-lg font-bold text-gray-700">
                                <?php echo $total_votes > 0 ? round(($niet_gestemd_count / $total_votes) * 100) : 0; ?>%
                            </div>
                        </div>
                        
                        <div class="text-center group">
                            <div class="bg-gradient-to-br from-gray-400 to-gray-600 rounded-2xl p-6 mb-4 transform group-hover:scale-105 transition-transform duration-300">
                                <div class="text-4xl font-black text-white mb-2"><?php echo $afwezig_count; ?></div>
                                <div class="text-gray-100 font-medium">Afwezig</div>
                            </div>
                            <div class="text-lg font-bold text-gray-700">
                                <?php echo $total_votes > 0 ? round(($afwezig_count / $total_votes) * 100) : 0; ?>%
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Progress bar -->
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-6 shadow-inner overflow-hidden">
                            <div class="flex h-6 rounded-full">
                                <?php if ($voor_count > 0): ?>
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 transition-all duration-1000 ease-out" 
                                         style="width: <?php echo ($voor_count / $total_votes) * 100; ?>%"></div>
                                <?php endif; ?>
                                <?php if ($tegen_count > 0): ?>
                                    <div class="bg-gradient-to-r from-red-400 to-red-600 transition-all duration-1000 ease-out" 
                                         style="width: <?php echo ($tegen_count / $total_votes) * 100; ?>%"></div>
                                <?php endif; ?>
                                <?php if ($niet_gestemd_count > 0): ?>
                                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 transition-all duration-1000 ease-out" 
                                         style="width: <?php echo ($niet_gestemd_count / $total_votes) * 100; ?>%"></div>
                                <?php endif; ?>
                                <?php if ($afwezig_count > 0): ?>
                                    <div class="bg-gradient-to-r from-gray-400 to-gray-600 transition-all duration-1000 ease-out" 
                                         style="width: <?php echo ($afwezig_count / $total_votes) * 100; ?>%"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full animate-pulse"></div>
                    </div>
                </div>

                <!-- Partijen stemgedrag -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Voor -->
                    <?php if ($voor_count > 0): ?>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-green-100 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-thumbs-up text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Voor</h3>
                                <p class="text-green-600 font-semibold"><?php echo $voor_count; ?> partijen</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($stemgedrag as $stem): ?>
                                <?php if ($stem->vote === 'voor'): ?>
                                    <div class="flex items-center p-3 bg-green-50 rounded-xl border border-green-100 hover:bg-green-100 transition-colors">
                                        <?php if ($stem->logo_url): ?>
                                            <img src="<?php echo htmlspecialchars($stem->logo_url); ?>" 
                                                 alt="<?php echo htmlspecialchars($stem->party_name); ?>" 
                                                 class="w-10 h-10 rounded-lg mr-3 shadow-sm">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($stem->party_name); ?></div>
                                            <?php if ($stem->opmerking): ?>
                                                <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($stem->opmerking); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Tegen -->
                    <?php if ($tegen_count > 0): ?>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-red-100 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-thumbs-down text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Tegen</h3>
                                <p class="text-red-600 font-semibold"><?php echo $tegen_count; ?> partijen</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($stemgedrag as $stem): ?>
                                <?php if ($stem->vote === 'tegen'): ?>
                                    <div class="flex items-center p-3 bg-red-50 rounded-xl border border-red-100 hover:bg-red-100 transition-colors">
                                        <?php if ($stem->logo_url): ?>
                                            <img src="<?php echo htmlspecialchars($stem->logo_url); ?>" 
                                                 alt="<?php echo htmlspecialchars($stem->party_name); ?>" 
                                                 class="w-10 h-10 rounded-lg mr-3 shadow-sm">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($stem->party_name); ?></div>
                                            <?php if ($stem->opmerking): ?>
                                                <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($stem->opmerking); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Niet gestemd -->
                    <?php if ($niet_gestemd_count > 0): ?>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-yellow-100 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-minus text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Niet gestemd</h3>
                                <p class="text-orange-600 font-semibold"><?php echo $niet_gestemd_count; ?> partijen</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($stemgedrag as $stem): ?>
                                <?php if ($stem->vote === 'niet_gestemd'): ?>
                                    <div class="flex items-center p-3 bg-yellow-50 rounded-xl border border-yellow-100 hover:bg-yellow-100 transition-colors">
                                        <?php if ($stem->logo_url): ?>
                                            <img src="<?php echo htmlspecialchars($stem->logo_url); ?>" 
                                                 alt="<?php echo htmlspecialchars($stem->party_name); ?>" 
                                                 class="w-10 h-10 rounded-lg mr-3 shadow-sm">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($stem->party_name); ?></div>
                                            <?php if ($stem->opmerking): ?>
                                                <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($stem->opmerking); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Afwezig -->
                    <?php if ($afwezig_count > 0): ?>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-user-times text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Afwezig</h3>
                                <p class="text-gray-600 font-semibold"><?php echo $afwezig_count; ?> partijen</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <?php foreach ($stemgedrag as $stem): ?>
                                <?php if ($stem->vote === 'afwezig'): ?>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors">
                                        <?php if ($stem->logo_url): ?>
                                            <img src="<?php echo htmlspecialchars($stem->logo_url); ?>" 
                                                 alt="<?php echo htmlspecialchars($stem->party_name); ?>" 
                                                 class="w-10 h-10 rounded-lg mr-3 shadow-sm">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($stem->party_name); ?></div>
                                            <?php if ($stem->opmerking): ?>
                                                <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($stem->opmerking); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Terug naar overzicht Section -->
    <section class="py-12 bg-gradient-to-br from-primary-dark/5 via-primary/5 to-secondary/5">
        <div class="container mx-auto px-6 text-center">
            <a href="stemmentracker" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-primary-dark text-white rounded-2xl hover:from-primary-dark hover:to-primary transition-all duration-300 transform hover:scale-105 font-semibold text-lg shadow-xl">
                <i class="fas fa-arrow-left mr-3"></i>
                Terug naar StemmenTracker overzicht
            </a>
        </div>
    </section>
</main>

<?php include_once 'views/templates/footer.php'; ?>