<?php
$pageTitle = "StemmenTracker - Hoe stemden de partijen?";
$pageDescription = "Bekijk hoe politieke partijen hebben gestemd over moties in de Tweede Kamer. Check het stemgedrag van alle partijen op belangrijke onderwerpen.";
$pageKeywords = "stemmentracker, stemgedrag, tweede kamer, moties, politieke partijen, nederland";

// Include header
include_once 'views/templates/header.php';
?>

<style>
/* StemmenTracker Custom Styles */
.hero-pattern {
    background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"%3E%3Cg opacity="0.03"%3E%3Cpath d="M30 5L35 20H50L38.75 30L42.5 45L30 37.5L17.5 45L21.25 30L10 20H25L30 5Z" fill="white"/%3E%3C/g%3E%3C/svg%3E');
}

.glassmorphism {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-hover {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.filter-glass {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.motie-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
}

.motie-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
    background: rgba(255, 255, 255, 1);
}

.slide-in-bottom {
    animation: slide-in-bottom 0.8s ease-out;
}

@keyframes slide-in-bottom {
    0% { transform: translateY(100px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

.fade-in-up {
    animation: fade-in-up 1s ease-out;
}

@keyframes fade-in-up {
    0% { transform: translateY(30px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-red-50">
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-24 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 hero-pattern opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- Header badge -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex items-center px-4 py-2 glassmorphism rounded-full">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Actuele stemdata uit de Tweede Kamer</span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                        StemmenTracker
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Nederland
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed mb-6">
                        Ontdek hoe politieke partijen werkelijk hebben gestemd over moties in de Tweede Kamer
                    </p>
                    
                    <p class="text-lg text-blue-200 max-w-4xl mx-auto">
                        In tegenstelling tot de StemWijzer, die plannen van partijen toont, laat de StemmenTracker zien 
                        hoe partijen daadwerkelijk hebben gestemd in de Tweede Kamer.
                    </p>
                </div>
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="glassmorphism rounded-2xl p-6 slide-in-bottom stagger-1">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo number_format($statistieken['total_moties']); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Moties</div>
                        </div>
                    </div>
                    
                    <div class="glassmorphism rounded-2xl p-6 slide-in-bottom stagger-2">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo $statistieken['recent_count']; ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Recent</div>
                        </div>
                    </div>
                    
                    <div class="glassmorphism rounded-2xl p-6 slide-in-bottom stagger-3">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo count($statistieken['top_themas']); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Thema's</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>

    <!-- Intro Section -->
    <div class="container mx-auto px-6 -mt-8 relative z-10">
        <div class="max-w-6xl mx-auto">
            <!-- Info Card -->
            <div class="filter-glass rounded-3xl shadow-2xl p-8 mb-12 fade-in-up">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Beslissende Momenten in de Politiek</h2>
                    <div class="max-w-4xl mx-auto">
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            Hier vind je de moties die Nederland echt bezig houden. Van controversiële onderwerpen die 
                            de gemoederen verhitten tot cruciale beslissingen die onze toekomst vormgeven. Deze stemresultaten 
                            laten zien waar onze volksvertegenwoordigers werkelijk voor staan wanneer het erop aankomt.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <div class="bg-white/60 rounded-2xl p-6 border border-white/40">
                                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-fire text-primary text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Hete Onderwerpen</h3>
                                <p class="text-gray-600 text-sm">Moties die discussie en debat oproepen in de maatschappij</p>
                            </div>
                            
                            <div class="bg-white/60 rounded-2xl p-6 border border-white/40">
                                <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-landmark text-secondary text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Belangrijke Besluiten</h3>
                                <p class="text-gray-600 text-sm">Stemresultaten die impact hebben op het dagelijks leven</p>
                            </div>
                            
                            <div class="bg-white/60 rounded-2xl p-6 border border-white/40">
                                <div class="w-12 h-12 bg-primary-light/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-primary-light text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Maatschappelijke Thema's</h3>
                                <p class="text-gray-600 text-sm">Onderwerpen waar Nederland over spreekt en nadenkt</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 p-6 bg-gradient-to-r from-primary/5 to-secondary/5 rounded-2xl border border-primary/10">
                            <p class="text-gray-700 font-medium">
                                💡 <strong>Transparantie in actie:</strong> Zie niet alleen wat partijen beloven, maar hoe ze daadwerkelijk 
                                stemmen wanneer het erop aankomt. Elke motie vertelt een verhaal over de richting van ons land.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Moties List -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <!-- Results Header -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Gevonden Moties
                    </h2>
                    <p class="text-lg text-gray-600">
                        <span class="inline-flex items-center px-4 py-2 bg-primary/10 text-primary rounded-full font-semibold">
                            <?php echo count($moties); ?> resultaten
                        </span>
                    </p>
                </div>

                <?php if (empty($moties)): ?>
                    <!-- Empty State -->
                    <div class="filter-glass rounded-3xl shadow-2xl p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-search text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">Geen moties gevonden</h3>
                        <p class="text-gray-500 mb-8">Probeer andere filters of zoektermen om relevante moties te vinden</p>
                        <a href="stemmentracker" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-all duration-300">
                            <i class="fas fa-refresh mr-2"></i>Opnieuw zoeken
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Moties Grid -->
                    <div class="space-y-6">
                        <?php foreach ($moties as $motie): ?>
                            <div class="motie-card rounded-3xl shadow-xl p-8 card-hover">
                                <!-- Header with Date and Tags -->
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-600">
                                                <?php echo date('d F Y', strtotime($motie->datum_stemming)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Tags Container -->
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Themas -->
                                        <?php if ($motie->themas): ?>
                                            <?php 
                                            $thema_names = explode(', ', $motie->themas);
                                            $thema_colors = explode(',', $motie->thema_colors ?: '');
                                            ?>
                                            <?php foreach ($thema_names as $index => $thema_name): ?>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full" 
                                                      style="background-color: <?php echo htmlspecialchars($thema_colors[$index] ?? '#3B82F6'); ?>15; color: <?php echo htmlspecialchars($thema_colors[$index] ?? '#3B82F6'); ?>; border: 1px solid <?php echo htmlspecialchars($thema_colors[$index] ?? '#3B82F6'); ?>30;">
                                                    <?php echo htmlspecialchars(trim($thema_name)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        
                                        <!-- Uitslag -->
                                        <?php if ($motie->uitslag): ?>
                                            <span class="px-3 py-1 text-xs font-bold rounded-full flex items-center gap-1
                                                <?php 
                                                echo $motie->uitslag === 'aangenomen' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                                     ($motie->uitslag === 'verworpen' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200'); 
                                                ?>">
                                                <i class="fas fa-<?php echo $motie->uitslag === 'aangenomen' ? 'check' : ($motie->uitslag === 'verworpen' ? 'times' : 'pause'); ?>"></i>
                                                <?php echo ucfirst($motie->uitslag); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Main Content -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Motie Details -->
                                    <div class="lg:col-span-2">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4 leading-tight">
                                            <a href="stemmentracker?action=detail&id=<?php echo $motie->id; ?>" 
                                               class="hover:text-primary transition-colors duration-300">
                                                <?php echo htmlspecialchars($motie->title); ?>
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-600 text-lg leading-relaxed mb-6">
                                            <?php echo htmlspecialchars(substr($motie->description, 0, 250)) . (strlen($motie->description) > 250 ? '...' : ''); ?>
                                        </p>
                                        
                                        <!-- Meta Information -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-folder text-primary"></i>
                                                <span class="text-gray-500">Onderwerp:</span>
                                                <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($motie->onderwerp); ?></span>
                                            </div>
                                            
                                            <?php if ($motie->indiener): ?>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-user text-primary"></i>
                                                <span class="text-gray-500">Indiener:</span>
                                                <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($motie->indiener); ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($motie->motie_nummer): ?>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-hashtag text-primary"></i>
                                                <span class="text-gray-500">Motie:</span>
                                                <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($motie->motie_nummer); ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Voting Results -->
                                    <div class="lg:col-span-1">
                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                                            <?php if ($motie->vote_count > 0): ?>
                                                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                                    <i class="fas fa-poll text-primary"></i>
                                                    Stemresultaat
                                                </h4>
                                                
                                                <!-- Vote Counts -->
                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                    <div class="text-center p-3 bg-green-50 rounded-xl border border-green-200">
                                                        <div class="text-2xl font-bold text-green-600"><?php echo $motie->voor_count; ?></div>
                                                        <div class="text-xs text-green-700 font-medium">VOOR</div>
                                                    </div>
                                                    <div class="text-center p-3 bg-red-50 rounded-xl border border-red-200">
                                                        <div class="text-2xl font-bold text-red-600"><?php echo $motie->tegen_count; ?></div>
                                                        <div class="text-xs text-red-700 font-medium">TEGEN</div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Progress Bar -->
                                                <div class="mb-4">
                                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                        <div class="flex h-full">
                                                            <?php if ($motie->voor_count > 0): ?>
                                                                <div class="bg-green-500" style="width: <?php echo ($motie->voor_count / $motie->vote_count) * 100; ?>%"></div>
                                                            <?php endif; ?>
                                                            <?php if ($motie->tegen_count > 0): ?>
                                                                <div class="bg-red-500" style="width: <?php echo ($motie->tegen_count / $motie->vote_count) * 100; ?>%"></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-sm text-gray-600 text-center mb-4">
                                                    <?php echo $motie->vote_count; ?> partijen hebben gestemd
                                                </p>
                                            <?php else: ?>
                                                <div class="text-center py-4">
                                                    <i class="fas fa-exclamation-circle text-2xl text-gray-400 mb-2"></i>
                                                    <p class="text-sm text-gray-500">Geen stemdata beschikbaar</p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Action Button -->
                                            <a href="stemmentracker?action=detail&id=<?php echo $motie->id; ?>" 
                                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-xl hover:from-primary-dark hover:to-primary transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                <i class="fas fa-eye mr-2"></i>Bekijk Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Statistieken Section -->
    <?php if (!empty($statistieken['top_themas'])): ?>
    <section class="py-16 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Meest Actieve Thema's
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Ontdek welke onderwerpen het meest besproken worden in de Tweede Kamer
                    </p>
                </div>
                
                <!-- Themas Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <?php foreach ($statistieken['top_themas'] as $index => $thema): ?>
                        <div class="filter-glass rounded-2xl p-6 text-center card-hover group fade-in-up" style="animation-delay: <?php echo $index * 0.1; ?>s">
                            <!-- Icon/Color -->
                            <div class="relative mb-4">
                                <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300" 
                                     style="background: linear-gradient(135deg, <?php echo htmlspecialchars($thema->color); ?>20, <?php echo htmlspecialchars($thema->color); ?>10); border: 2px solid <?php echo htmlspecialchars($thema->color); ?>30;">
                                    <div class="w-8 h-8 rounded-full" style="background-color: <?php echo htmlspecialchars($thema->color); ?>"></div>
                                </div>
                                <!-- Badge -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg">
                                    <?php echo $index + 1; ?>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="font-bold text-gray-900 mb-2 text-lg group-hover:text-primary transition-colors duration-300">
                                <?php echo htmlspecialchars($thema->name); ?>
                            </h3>
                            <p class="text-sm text-gray-500 mb-3">
                                <span class="font-semibold text-primary"><?php echo $thema->count; ?></span> moties
                            </p>
                            
                            <!-- Progress indicator -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 group-hover:scale-105" 
                                     style="background-color: <?php echo htmlspecialchars($thema->color); ?>; width: <?php echo ($thema->count / $statistieken['top_themas'][0]->count) * 100; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Call to Action -->
                <div class="text-center mt-12">
                    <p class="text-gray-600 mb-6">Wil je meer zien over een specifiek thema?</p>
                    <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                       class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary-dark hover:to-primary transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-filter mr-2"></i>
                        Filter op Thema
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php include_once 'views/templates/footer.php'; ?>