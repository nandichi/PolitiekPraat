<?php
// Voorkom directe toegang tot dit bestand
if (!defined('URLROOT')) {
    exit;
}

// Laad PartyModel voor dynamische partij logo's
require_once __DIR__ . '/../../models/PartyModel.php';
?>

<!-- Link naar AOS (Animate On Scroll) library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<style>
/* Custom CSS voor over-mij pagina */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

.over-mij-hero {
    background: linear-gradient(135deg, #0f2a44 0%, #1a365d 50%, #c41e3a 100%);
    position: relative;
    overflow: hidden;
}



.profile-card {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.mission-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.skill-badge {
    background: linear-gradient(135deg, #1a365d 0%, #c41e3a 100%);
    color: white;
    transition: all 0.3s ease;
}

.skill-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(26, 54, 93, 0.3);
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, #1a365d 0%, #c41e3a 100%);
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.2);
}

.social-link {
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.social-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #1a365d 0%, #c41e3a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.animated-bg {
    background: linear-gradient(270deg, #1a365d, #c41e3a, #1a365d, #c41e3a);
    background-size: 800% 800%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.tech-item {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.tech-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}



@media (max-width: 768px) {
    .tech-grid { grid-template-columns: repeat(2, 1fr); }
    .over-mij-hero {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
    .party-float-container {
        animation-duration: 15s !important;
    }
}

@media (prefers-reduced-motion: reduce) {
    .party-float-container {
        animation: none !important;
    }
}
</style>



<main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Hero Section -->
    <section class="over-mij-hero min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Floating partij logos -->
        <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
            <?php
            // Haal partij logo's dynamisch op uit de database
            $partyModel = new PartyModel();
            $allDbParties = $partyModel->getAllParties();
            $partyLogos = [];
            $partyColors = [];
            foreach ($allDbParties as $key => $party) {
                $partyLogos[$key] = $party['logo'];
                $partyColors[$key] = $party['color'];
            }
            
            // Partijen configuratie met kleuren uit database - posities worden random gegenereerd
            $floatingPartijen = [
                ['naam' => 'VVD', 'kleur' => $partyColors['VVD'] ?? '#FF9900', 'logo' => $partyLogos['VVD'] ?? '', 'delay' => '0s', 'duration' => '20s', 'size' => 'large'],
                ['naam' => 'PVV', 'kleur' => $partyColors['PVV'] ?? '#0078D7', 'logo' => $partyLogos['PVV'] ?? '', 'delay' => '3s', 'duration' => '22s', 'size' => 'large'],
                ['naam' => 'GL-PvdA', 'kleur' => $partyColors['GL-PvdA'] ?? '#008800', 'logo' => $partyLogos['GL-PvdA'] ?? '', 'delay' => '6s', 'duration' => '24s', 'size' => 'large'],
                ['naam' => 'CDA', 'kleur' => $partyColors['CDA'] ?? '#1E8449', 'logo' => $partyLogos['CDA'] ?? '', 'delay' => '2s', 'duration' => '18s', 'size' => 'medium'],
                ['naam' => 'D66', 'kleur' => $partyColors['D66'] ?? '#00B13C', 'logo' => $partyLogos['D66'] ?? '', 'delay' => '4s', 'duration' => '26s', 'size' => 'medium'],
                ['naam' => 'SP', 'kleur' => $partyColors['SP'] ?? '#EE0000', 'logo' => $partyLogos['SP'] ?? '', 'delay' => '7s', 'duration' => '19s', 'size' => 'small'],
                ['naam' => 'PvdD', 'kleur' => $partyColors['PvdD'] ?? '#006400', 'logo' => $partyLogos['PvdD'] ?? '', 'delay' => '8s', 'duration' => '21s', 'size' => 'small'],
                ['naam' => 'Volt', 'kleur' => $partyColors['Volt'] ?? '#800080', 'logo' => $partyLogos['Volt'] ?? '', 'delay' => '10s', 'duration' => '25s', 'size' => 'small'],
                ['naam' => 'JA21', 'kleur' => $partyColors['JA21'] ?? '#4B0082', 'logo' => $partyLogos['JA21'] ?? '', 'delay' => '12s', 'duration' => '27s', 'size' => 'small'],
                ['naam' => 'SGP', 'kleur' => $partyColors['SGP'] ?? '#ff7f00', 'logo' => $partyLogos['SGP'] ?? '', 'delay' => '14s', 'duration' => '29s', 'size' => 'small']
            ];
            
            // Array om gebruikte posities bij te houden
            $usedPositions = [];
            
            // Functie om random posities te genereren die niet overlappen
            function generateRandomPosition($index, $total, &$usedPositions) {
                $attempts = 0;
                $maxAttempts = 50;
                
                do {
                    $attempts++;
                    
                    // Genereer random positie
                    $isLeft = (rand(0, 1) === 0);
                    
                    if ($isLeft) {
                        $position = [
                            'top' => rand(5, 90),
                            'left' => rand(2, 18),
                            'right' => null
                        ];
                    } else {
                        $position = [
                            'top' => rand(5, 90),
                            'left' => null,
                            'right' => rand(2, 18)
                        ];
                    }
                    
                    // Check overlap met bestaande posities
                    $hasOverlap = false;
                    foreach ($usedPositions as $used) {
                        $topDiff = abs($position['top'] - $used['top']);
                        
                        if ($isLeft && isset($used['left'])) {
                            $sideDiff = abs($position['left'] - $used['left']);
                            if ($topDiff < 15 && $sideDiff < 8) {
                                $hasOverlap = true;
                                break;
                            }
                        } elseif (!$isLeft && isset($used['right'])) {
                            $sideDiff = abs($position['right'] - $used['right']);
                            if ($topDiff < 15 && $sideDiff < 8) {
                                $hasOverlap = true;
                                break;
                            }
                        }
                    }
                    
                    // Vermijd centrale zone (waar content staat)
                    if ($position['top'] > 30 && $position['top'] < 70) {
                        if (($isLeft && $position['left'] > 15) || (!$isLeft && $position['right'] > 15)) {
                            $hasOverlap = true;
                        }
                    }
                    
                } while ($hasOverlap && $attempts < $maxAttempts);
                
                // Voeg positie toe aan gebruikte posities
                $usedPositions[] = $position;
                
                return $position;
            }
            
            foreach($floatingPartijen as $index => $partij):
                // Bepaal grootte op basis van size parameter
                $sizeClasses = match($partij['size']) {
                    'large' => 'w-16 h-16 sm:w-20 sm:h-20',
                    'medium' => 'w-12 h-12 sm:w-16 sm:h-16',
                    'small' => 'w-8 h-8 sm:w-12 sm:h-12',
                    default => 'w-12 h-12 sm:w-16 sm:h-16'
                };
                
                $logoSizeClasses = match($partij['size']) {
                    'large' => 'w-10 h-10 sm:w-14 sm:h-14',
                    'medium' => 'w-8 h-8 sm:w-10 sm:h-10',
                    'small' => 'w-6 h-6 sm:w-8 sm:h-8',
                    default => 'w-8 h-8 sm:w-10 sm:h-10'
                };
                
                // Genereer unieke random positie
                $position = generateRandomPosition($index, count($floatingPartijen), $usedPositions);
                $positionStyle = "top: {$position['top']}%;";
                if ($position['left'] !== null) {
                    $positionStyle .= " left: {$position['left']}%;";
                } else {
                    $positionStyle .= " right: {$position['right']}%;";
                }
            ?>
            <div class="absolute opacity-15 hover:opacity-30 transition-opacity duration-500 party-float-<?php echo $index; ?>" 
                 style="<?php echo $positionStyle; ?> animation: floating-<?php echo $index; ?> <?php echo $partij['duration']; ?> infinite ease-in-out; animation-delay: <?php echo $partij['delay']; ?>;">
                <!-- Partij logo container met mooie styling -->
                <div class="relative group cursor-pointer">
                    <!-- Multi-layer glow effect -->
                    <div class="absolute inset-0 <?php echo $sizeClasses; ?> rounded-3xl blur-xl transform scale-125 group-hover:scale-150 transition-transform duration-700 opacity-40"
                         style="background: linear-gradient(135deg, <?php echo $partij['kleur']; ?>, <?php echo $partij['kleur']; ?>80);"></div>
                    
                    <div class="absolute inset-0 <?php echo $sizeClasses; ?> rounded-3xl blur-md transform scale-110 group-hover:scale-125 transition-transform duration-500 opacity-60"
                         style="background: <?php echo $partij['kleur']; ?>;"></div>
                    
                    <!-- Main logo container -->
                    <div class="relative <?php echo $sizeClasses; ?> bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-700 overflow-hidden">
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent transform -skew-x-12 translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000"></div>
                        
                        <!-- Logo afbeelding -->
                        <div class="<?php echo $logoSizeClasses; ?> rounded-2xl overflow-hidden transform group-hover:scale-110 transition-transform duration-500 flex items-center justify-center relative z-10">
                            <img src="<?php echo $partij['logo']; ?>" 
                                 alt="<?php echo $partij['naam']; ?> logo"
                                 class="w-full h-full object-contain transition-all duration-500 group-hover:brightness-110"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 loading="lazy">
                            <!-- Fallback met gradient -->
                            <div class="hidden w-full h-full rounded-2xl font-black text-white items-center justify-center transform group-hover:scale-110 transition-transform duration-500 text-center"
                                 style="background: linear-gradient(135deg, <?php echo $partij['kleur']; ?>, <?php echo $partij['kleur']; ?>CC); text-shadow: 0 2px 4px rgba(0,0,0,0.4); font-size: <?php echo $partij['size'] === 'large' ? '14px' : ($partij['size'] === 'medium' ? '12px' : '10px'); ?>;">
                                <?php 
                                // Optimized fallback text
                                $afkorting = $partij['naam'];
                                if (strpos($afkorting, '/') !== false) {
                                    $delen = explode('/', $afkorting);
                                    echo substr($delen[0], 0, 3);
                                } else {
                                    echo substr($afkorting, 0, min(4, strlen($afkorting)));
                                }
                                ?>
                            </div>
                        </div>
                        
                        <!-- Animated border -->
                        <div class="absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background: linear-gradient(45deg, <?php echo $partij['kleur']; ?>, transparent, <?php echo $partij['kleur']; ?>); padding: 2px;">
                            <div class="w-full h-full rounded-3xl bg-white/95"></div>
                        </div>
                    </div>
                    
                    <!-- Floating particles -->
                    <div class="absolute -top-2 -right-2 w-3 h-3 rounded-full animate-ping opacity-60"
                         style="background: <?php echo $partij['kleur']; ?>; animation-delay: 0.5s;"></div>
                    <div class="absolute -bottom-2 -left-2 w-2 h-2 rounded-full animate-ping opacity-40"
                         style="background: <?php echo $partij['kleur']; ?>; animation-delay: 1s;"></div>
                    
                    <!-- Enhanced tooltip -->
                    <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-50">
                        <div class="relative">
                            <div class="absolute inset-0 bg-slate-900 rounded-lg blur-sm"></div>
                            <div class="relative bg-slate-900/95 backdrop-blur-sm text-white text-sm px-3 py-2 rounded-lg shadow-xl border border-slate-700 whitespace-nowrap">
                                <div class="font-bold"><?php echo $partij['naam']; ?></div>
                                <div class="text-xs text-slate-300">Nederlandse Politieke Partij</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CSS animaties voor partij logos -->
        <style>
        /* Floating animaties voor partij logos */
        <?php foreach($floatingPartijen as $index => $partij): ?>
        @keyframes floating-<?php echo $index; ?> {
            0% { 
                transform: translateX(0px) translateY(0px) rotate(0deg) scale(1); 
            }
            25% { 
                transform: translateX(<?php echo 15 + ($index * 3); ?>px) translateY(-<?php echo 20 + ($index * 2); ?>px) rotate(<?php echo 5 + ($index * 2); ?>deg) scale(1.05); 
            }
            50% { 
                transform: translateX(-<?php echo 10 + ($index * 3); ?>px) translateY(<?php echo 25 + ($index * 2); ?>px) rotate(-<?php echo 7 + ($index * 2); ?>deg) scale(0.95); 
            }
            75% { 
                transform: translateX(<?php echo 20 + ($index * 2); ?>px) translateY(<?php echo 15 + ($index * 3); ?>px) rotate(<?php echo 3 + ($index * 2); ?>deg) scale(1.02); 
            }
            100% { 
                transform: translateX(0px) translateY(0px) rotate(0deg) scale(1); 
            }
        }
        <?php endforeach; ?>
        
        /* Responsieve aanpassingen */
        @media (max-width: 768px) {
            .party-float-container {
                animation-duration: 15s !important;
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            .party-float-container {
                animation: none !important;
            }
        }
        </style>
        
        <!-- Hero Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    
                    <!-- Left Column: Profile Info -->
                    <div class="text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                        <!-- Profile Image -->
                        <div class="relative inline-block mb-8">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-red-600 rounded-full blur-lg opacity-30 animate-pulse"></div>
                            <div class="relative w-48 h-48 mx-auto rounded-full overflow-hidden border-4 border-white shadow-2xl">
                                <img src="<?= URLROOT ?>/public/images/naoufal-foto.jpg" 
                                     onerror="if(this.src !== '<?= URLROOT ?>/images/naoufal-foto.jpg') this.src='<?= URLROOT ?>/images/naoufal-foto.jpg'; else if(this.src !== '<?= URLROOT ?>/public/images/profiles/naoufal-foto.jpg') this.src='<?= URLROOT ?>/public/images/profiles/naoufal-foto.jpg';"
                                     alt="Foto van Naoufal Andichi" 
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            </div>
                            <!-- Online indicator -->
                            <div class="absolute bottom-4 right-4 w-6 h-6 bg-green-500 border-4 border-white rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Name and Title -->
                        <h1 class="text-5xl lg:text-7xl font-black text-white mb-4 leading-tight">
                            Naoufal Andichi
                        </h1>
                        <p class="text-2xl lg:text-3xl text-blue-100 mb-6 font-light">
                            Software Developer die dol is op politiek
                        </p>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3 mb-8">
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">🏛️ Liberaal</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">💻 Developer</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">📊 Analist</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">✍️ Blogger</span>
                        </div>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center lg:justify-start space-x-4">
                            <a href="https://www.linkedin.com/in/naoufalandichi/" 
                               target="_blank" rel="noopener noreferrer" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="mailto:naoufal@politiekpraat.nl" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            <a href="<?= URLROOT ?>/blogs" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-purple-600 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column: Quick Stats -->
                    <div class="space-y-8" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">150+</div>
                                <div class="text-gray-600 text-sm font-medium">Artikelen Geschreven</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">50k+</div>
                                <div class="text-gray-600 text-sm font-medium">Lezers Bereikt</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">3+</div>
                                <div class="text-gray-600 text-sm font-medium">Jaren Ervaring</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">20+</div>
                                <div class="text-gray-600 text-sm font-medium">Politieke Thema's</div>
                            </div>
                        </div>
                        
                        <!-- Mission Statement Card -->
                        <div class="mission-card p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold gradient-text mb-4">Wat ik doe</h3>
                            <p class="text-gray-700 leading-relaxed">
                                "Ik probeer politiek gewoon begrijpelijk uit te leggen. Geen ingewikkelde taal, gewoon helder en eerlijk."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-50 to-transparent rounded-full opacity-60"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-purple-50 to-transparent rounded-full opacity-60"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Over <span class="gradient-text">Mij</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-red-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- About Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    <!-- Personal Story -->
                    <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000">
                        <div class="bg-gradient-to-br from-white to-blue-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-blue-600 to-red-600 rounded-full mr-4"></span>
                                Mijn Verhaal
                            </h3>
                            
                            <div class="prose prose-lg text-gray-700 space-y-6">
                                <p class="leading-relaxed">
                                    Hey! Ik ben Naoufal, en ik ben een beetje een nerd als het gaat om twee dingen: programmeren en politiek. Misschien een rare combinatie, maar het werkt voor mij!
                                </p>
                                
                                <p class="leading-relaxed">
                                    Het begon allemaal met computers. Ik was dat kind dat urenlang kon klooien met code. Maar ergens onderweg raakte ik gefascineerd door hoe onze samenleving werkt en hoe beslissingen in Den Haag ons allemaal raken. Zo ontstond het idee voor PolitiekPraat.
                                </p>
                                
                                <p class="leading-relaxed">
                                    Ik ben liberaal ingesteld, geloof in vrijheid, eigen verantwoordelijkheid en dat onderwijs heel veel problemen kan oplossen. Maar bovenal hou ik van een goed debat en probeer ik dingen gewoon helder uit te leggen zonder politieke jargon.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Philosophy & Skills -->
                    <div class="space-y-8" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Philosophy Card -->
                        <div class="bg-gradient-to-br from-white to-purple-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-red-600 to-blue-600 rounded-full mr-4"></span>
                                Mijn Filosofie
                            </h3>
                            
                            <div class="space-y-4">
                                <blockquote class="text-lg italic text-gray-700 border-l-4 border-red-600 pl-6">
                                    "Politiek hoeft niet ingewikkeld te zijn als je het gewoon normaal uitlegt."
                                </blockquote>
                                
                                <p class="text-gray-700 leading-relaxed">
                                    Ik snap best dat politiek vaak saai of verwarrend overkomt. Daarom probeer ik het gewoon uit te leggen zoals ik het zelf zou willen horen, zonder gedoe en met wat humor waar mogelijk.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Skills & Expertise -->
                        <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-blue-600 to-red-600 rounded-full mr-4"></span>
                                Expertise
                            </h3>
                            
                            <div class="tech-grid">
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">🏛️</div>
                                    <div class="text-sm font-semibold text-gray-700">Politieke Analyse</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">📊</div>
                                    <div class="text-sm font-semibold text-gray-700">Data Visualisatie</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">✍️</div>
                                    <div class="text-sm font-semibold text-gray-700">Content Creatie</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">💻</div>
                                    <div class="text-sm font-semibold text-gray-700">Web Development</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">📱</div>
                                    <div class="text-sm font-semibold text-gray-700">UX Design</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">🎯</div>
                                    <div class="text-sm font-semibold text-gray-700">Strategy</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
        <!-- Animated background -->
        <div class="animated-bg absolute inset-0 opacity-5"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Mijn <span class="gradient-text">Visie</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-blue-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- Mission Content -->
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white/80 backdrop-blur-lg p-12 rounded-3xl shadow-2xl border border-white/20" data-aos="zoom-in" data-aos-duration="1000">
                        <!-- Quote decoration -->
                        <div class="text-6xl text-blue-600/20 mb-6">"</div>
                        
                        <div class="space-y-8 text-lg leading-relaxed text-gray-700">
                            <p class="text-xl lg:text-2xl font-light text-gray-800 mb-8">
                                Waarom PolitiekPraat? Omdat politieke thema’s vaak onnodig ingewikkeld en droog worden gebracht. Hier maak ik ze begrijpelijk, relevant en prettig om te lezen.
                            </p>
                            
                            <p>
                                Ik schrijf over onderwerpen als klimaat, onderwijs en economie. Niet omdat ik alle antwoorden heb, maar om complexe kwesties helder te duiden en inzichten te delen.
                            </p>
                            
                            <p>
                                Politiek raakt het dagelijks leven: van belasting en zorg tot de woningmarkt. Juist daarom verdient het een duidelijke, toegankelijke en nuchtere uitleg.
                            </p>
                            
                            <p class="text-xl font-semibold gradient-text">
                                Verken de artikelen, denk mee en deel je perspectief. Constructieve dialoog maakt het gesprek scherper én waardevoller.
                            </p>
                        </div>
                        
                        <!-- Quote end decoration -->
                        <div class="text-6xl text-blue-600/20 text-right">"</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Journey Timeline Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Mijn <span class="gradient-text">Reis</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-red-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- Timeline -->
                <div class="space-y-12">
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hoe het begon</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Tijdens mijn studie kreeg ik door dat politiek eigenlijk best belangrijk is, maar dat veel mensen er niet zo veel van snappen. Inclusief ikzelf toen nog.
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Eerste artikelen</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Ik begon dingen op te schrijven die ik interessant vond. Bleek dat andere mensen dat ook leuk vonden om te lezen. Wie had dat gedacht?
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">PolitiekPraat ontstaat</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Met wat programmeerskills en veel koffie bouwde ik deze site. Geen fancy agency, gewoon ik achter mijn laptop die iets cools wilde maken.
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Wat er komt</h3>
                        <p class="text-gray-600 leading-relaxed">
                                                            Meer artikelen, meer tools zoals de PartijMeter, en hopelijk steeds meer mensen die snappen dat politiek eigenlijk best interessant kan zijn.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

         <!-- Call to Action Section -->
    <section class="py-24 bg-gradient-to-br from-slate-900 via-blue-900 to-red-900 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <!-- Primary floating orbs -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-gradient-to-r from-blue-400/20 to-red-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gradient-to-l from-red-400/15 to-blue-400/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gradient-to-br from-blue-400/25 to-red-400/25 rounded-full blur-2xl animate-pulse" style="animation-delay: 4s;"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="20" cy="20" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <!-- Header Content -->
                <div class="mb-16" data-aos="fade-up" data-aos-duration="1000">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-lg border border-white/20 text-white/90 text-sm font-medium mb-8">
                        <svg class="w-4 h-4 mr-2 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-2-2V10a2 2 0 012-2h2m2-4h6a2 2 0 012 2v6a2 2 0 01-2 2h-6l-4 4V8a2 2 0 012-2z"/>
                        </svg>
                        Laten we Verbinden
                    </div>
                    
                    <h2 class="text-4xl lg:text-6xl font-black text-white mb-6 leading-tight">
                        Heb je een <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">Vraag</span>?
                    </h2>
                    
                    <p class="text-xl lg:text-2xl text-blue-100 leading-relaxed max-w-3xl mx-auto">
                        Vragen over iets politieks? Een idee voor een artikel? Of gewoon zin om te brainstormen? 
                        Stuur me gerust een berichtje!
                    </p>
                </div>
                
                <!-- Contact Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!-- Email Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="100">
                        <a href="mailto:naoufal@politiekpraat.nl" 
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Direct Contact</h3>
                                <p class="text-blue-200 text-sm mb-4">Voor vragen, feedback of samenwerking</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- LinkedIn Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="200">
                        <a href="https://www.linkedin.com/in/naoufalandichi/" 
                           target="_blank" rel="noopener noreferrer"
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Professioneel Netwerk</h3>
                                <p class="text-blue-200 text-sm mb-4">Verbind met me op LinkedIn</p>
                                <div class="text-blue-300 font-medium">Naoufal Andichi</div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Blog Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?= URLROOT ?>/blogs" 
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Mijn Analyses</h3>
                                <p class="text-blue-200 text-sm mb-4">Ontdek mijn politieke inzichten</p>
                                <div class="text-blue-300 font-medium">Bekijk Blogs</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Bottom CTA -->
                <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/20" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-left">
                            <h3 class="text-2xl font-bold text-white mb-2">Laten we politiek leuker maken</h3>
                            <p class="text-blue-200">Want het hoeft echt niet zo droog te zijn als het vaak lijkt.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?= URLROOT ?>/partijmeter" 
                               class="px-8 py-4 bg-gradient-to-r from-blue-600 to-red-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:to-red-700 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                Probeer de PartijMeter
                            </a>
                            <a href="<?= URLROOT ?>/blogs" 
                               class="px-8 py-4 bg-white/20 text-white font-bold rounded-2xl border border-white/30 hover:bg-white/30 transition-all duration-300 hover:scale-105">
                                Lees Meer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// AOS initialized in footer.php to avoid conflicts

// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Profile image hover effect
    const profileImg = document.querySelector('.over-mij-hero img');
    if (profileImg) {
        profileImg.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        profileImg.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }
    
    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>