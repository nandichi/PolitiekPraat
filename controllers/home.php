<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalDataAPI.php';
require_once 'includes/PollAPI.php';

$db = new Database();
$newsAPI = new NewsAPI();
$openDataAPI = new OpenDataAPI();
$politicalDataAPI = new PoliticalDataAPI();
$pollAPI = new PollAPI();

// Haal actuele politieke data op
$kamerStats = $politicalDataAPI->getKamerStatistieken();
$coalitieStatus = $politicalDataAPI->getCoalitieStatus();
$partijData = $politicalDataAPI->getPartijInformatie();

// Haal peilingen data op
$latestPolls = $pollAPI->getLatestPolls();
$historicalPolls = $pollAPI->getHistoricalPolls(3);

// Haal de laatste 6 blogs op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

// Haal het laatste nieuws op
$news_sources = [
    'links' => [
        ['name' => 'De Volkskrant', 'bias' => 'links', 'url' => 'https://www.volkskrant.nl/voorpagina/rss.xml'],
        ['name' => 'Trouw', 'bias' => 'centrum-links', 'url' => 'https://www.trouw.nl/rss.xml'],
        ['name' => 'NRC', 'bias' => 'centrum-links', 'url' => 'https://www.nrc.nl/rss/']
    ],
    'rechts' => [
        ['name' => 'Telegraaf', 'bias' => 'rechts', 'url' => 'https://www.telegraaf.nl/rss'],
        ['name' => 'Reformatorisch Dagblad', 'bias' => 'rechts', 'url' => 'https://rd.nl/rss'],
        ['name' => 'AD', 'bias' => 'centrum-rechts', 'url' => 'https://www.ad.nl/rss.xml']
    ]
];

// Voorbeeldnieuws (vervangt de API-call voor demonstratiedoeleinden)
$latest_news = [
    [
        'orientation' => 'links',
        'source' => 'Socialisme.nu',
        'bias' => 'links',
        'publishedAt' => date('Y-m-d H:i:s'), // Vandaag 08:30
        'title' => 'Hoe de PVV Henk en Ingrid wil kaalplukken',
        'description' => 'De PVV presenteert zich als de partij van "gewone mensen" zoals Henk en Ingrid. Maar uit analyse van het verkiezingsprogramma blijkt dat de partij juist deze groep het hardst raakt met bezuinigingen op sociale voorzieningen.',
        'url' => 'https://socialisme.nu/hoe-de-pvv-henk-en-ingrid-wil-kaalplukken/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'centrum-links',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'Duitsland Blog 23 februari: Laatste ontwikkelingen in de Duitse politiek',
        'description' => 'Volg de laatste ontwikkelingen in de Duitse politiek, met updates over de coalitievorming, verkiezingen en belangrijke debatten.',
        'url' => 'https://www.nrc.nl/nieuws/2025/02/23/duitslandblog-23-februari-a4884064'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'centrum-links',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'Hoe de bromance tussen Trump en Poetin de wereld verandert (en heeft de NAVO haar langste tijd gehad?)',
        'description' => 'De relatie tussen Donald Trump en Vladimir Poetin lijkt steeds hechter te worden. Wat betekent dit voor de internationale verhoudingen en de toekomst van de NAVO?',
        'url' => 'https://www.trouw.nl/buitenland/hoe-de-bromance-tussen-trump-en-poetin-de-wereld-verandert-en-heeft-de-navo-haar-langste-tijd-gehad~bc9c11ae/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'FVD',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Vredesplan Trump-Poetin: Wat doet de EU? Ralf Dekker in Tweede Kamerdebat over Oekraïne-conflict',
        'description' => 'Tijdens het Tweede Kamerdebat over het Oekraïne-conflict besprak Ralf Dekker het vredesplan van Trump en Poetin en de rol van de EU hierin. FVD pleit voor een diplomatieke oplossing.',
        'url' => 'https://fvd.nl/nieuws/vredesplan-trump-poetin-wat-doet-de-eu-ralf-dekker-in-tweede-kamerdebat-over-oekraine-conflict'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'De Dagelijkse Standaard',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'Peilingen: Vertrouwen in kabinet keldert, PVV nog altijd aan kop als grootste partij',
        'description' => 'Uit recente peilingen blijkt dat het vertrouwen in het kabinet verder is gedaald. De PVV van Geert Wilders blijft de grootste partij in de peilingen, terwijl andere partijen terrein verliezen.',
        'url' => 'https://www.dagelijksestandaard.nl/politiek/peilingen-vertrouwen-in-kabinet-keldert-pvv-nog-altijd-aan-kop-als-grootste-partij'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'BNR',
        'bias' => 'centrum-rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'Timmermans gaat tijdens debat proberen de coalitie uit elkaar te spelen',
        'description' => 'GroenLinks-PvdA-leider Frans Timmermans gaat tijdens het debat over de regeringsverklaring proberen de coalitie uit elkaar te spelen. Dat zegt politiek commentator Laurens Boven.',
        'url' => 'https://www.bnr.nl/nieuws/nieuws-politiek/10567499/timmermans-gaat-tijdens-debat-proberen-de-coalitie-uit-elkaar-te-spelen'
    ]
];

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

require_once 'views/templates/header.php';
?>

<!-- Add custom animations for floating elements -->
<style>
    @keyframes float-slow {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    @keyframes float-medium {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(-2deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    @keyframes float-fast {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-7px) rotate(1deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    @keyframes float-particle {
        0% { transform: translateY(0) translateX(0); }
        100% { transform: translateY(10px) translateX(10px); }
    }
    
    .animate-float-slow {
        animation: float-slow 6s ease-in-out infinite;
    }
    
    .animate-float-medium {
        animation: float-medium 4s ease-in-out infinite;
    }
    
    .animate-float-fast {
        animation: float-fast 3s ease-in-out infinite;
    }
    
    @keyframes slide {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(500%); }
    }
    
    .animate-slide {
        animation: slide 4s ease-in-out infinite;
    }
</style>

<main class="bg-gray-50 overflow-x-hidden">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary overflow-hidden">
        <!-- Decorative top bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-secondary via-primary to-secondary"></div>
        <div class="absolute top-0 left-0 h-1.5 w-1/3 bg-white opacity-40">
            <div class="absolute top-0 left-0 h-full w-20 bg-white animate-slide"></div>
        </div>
        
        <!-- Animated background pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>
        
        <!-- Spotlight effect -->
        <div class="absolute inset-0 pointer-events-none"
             x-data="{ mouseX: 0, mouseY: 0 }"
             @mousemove="mouseX = $event.clientX; mouseY = $event.clientY"
             >
            <div class="absolute -top-[30%] -left-[30%] w-[130%] h-[130%] bg-gradient-radial from-primary/20 to-transparent opacity-50"
                 :style="`transform: translate(${mouseX/50}px, ${mouseY/50}px);`"></div>
        </div>
        
        <!-- Animated floating elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Political icons and symbols -->
            <div class="absolute top-[15%] left-[12%] w-12 h-12 bg-white/10 rounded-full flex items-center justify-center animate-float-medium">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            
            <div class="absolute top-[30%] right-[22%] w-14 h-14 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center animate-float-slow">
                <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l9-4 9 4v6a11 11 0 01-9 11 11 11 0 01-9-11V6z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"></path>
                </svg>
            </div>
            
            <div class="absolute bottom-[25%] left-[28%] w-10 h-10 bg-secondary/20 rounded-lg flex items-center justify-center animate-float-fast">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 16h6"></path>
                </svg>
            </div>
            
            <div class="absolute bottom-[35%] right-[35%] w-10 h-10 bg-primary/20 backdrop-blur-sm rounded-full flex items-center justify-center animate-float-medium">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
            </div>
            
            <div class="absolute top-[45%] left-[38%] w-9 h-9 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center animate-float-slow">
                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>

            <!-- Existing floating circles -->
            <div class="absolute top-1/4 left-[10%] w-12 h-12 rounded-full bg-blue-400/20 animate-float-slow"></div>
            <div class="absolute bottom-1/3 right-[15%] w-20 h-20 rounded-full bg-primary/20 animate-float-medium"></div>
            <div class="absolute top-1/3 right-[30%] w-8 h-8 rounded-full bg-indigo-500/20 animate-float-fast"></div>
            
            <!-- Floating shapes -->
            <div class="absolute top-1/2 left-[25%] w-16 h-16 bg-secondary/20 rounded-lg rotate-45 animate-float-medium"></div>
            <div class="absolute bottom-1/4 left-[40%] w-10 h-10 border-2 border-white/10 rounded-md rotate-12 animate-float-slow"></div>
            
            <!-- Dutch flag element -->
            <div class="absolute top-[60%] right-[18%] w-16 h-10 overflow-hidden rounded-md animate-float-slow">
                <div class="absolute inset-0 flex flex-col">
                    <div class="h-1/3 bg-[#AE1C28]"></div>
                    <div class="h-1/3 bg-white"></div>
                    <div class="h-1/3 bg-[#21468B]"></div>
                </div>
            </div>
            
            <!-- EU stars element -->
            <div class="absolute bottom-[15%] right-[28%] w-12 h-12 bg-[#003399]/30 rounded-full flex items-center justify-center animate-float-medium">
                <div class="relative w-8 h-8">
                    <?php for ($i = 0; $i < 12; $i++): 
                        $angle = $i * 30;
                        $radians = $angle * M_PI / 180;
                        $x = 4 + 3.5 * cos($radians);
                        $y = 4 + 3.5 * sin($radians);
                    ?>
                    <div class="absolute w-1 h-1 bg-[#FFCC00] rounded-full" style="left: <?php echo $x; ?>px; top: <?php echo $y; ?>px;"></div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Ballot icon -->
            <div class="absolute top-[70%] left-[15%] w-11 h-11 bg-secondary/20 backdrop-blur-sm rounded-lg flex items-center justify-center rotate-12 animate-float-medium">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            
            <!-- Scales of justice -->
            <div class="absolute bottom-[20%] left-[20%] w-12 h-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center animate-float-slow">
                <svg class="w-7 h-7 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                </svg>
            </div>
            
            <!-- Speech bubble / debate -->
            <div class="absolute top-[20%] right-[10%] w-10 h-10 bg-primary/20 backdrop-blur-sm rounded-lg flex items-center justify-center animate-float-fast">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            
            <!-- Parliament/seats icon -->
            <div class="absolute bottom-[40%] left-[8%] w-12 h-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center animate-float-medium">
                <svg class="w-7 h-7 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            
            <!-- Particle container with animated dots -->
            <div class="absolute inset-0" 
                 x-data="{
                     particles: Array.from({length: 20}, () => ({
                         x: Math.random() * 100,
                         y: Math.random() * 100,
                         size: Math.random() * 1.5 + 0.5,
                         speed: Math.random() * 1 + 0.5,
                         opacity: Math.random() * 0.5 + 0.2
                     }))
                 }">
                <template x-for="(particle, index) in particles" :key="index">
                    <div class="absolute rounded-full bg-white"
                         :style="`
                            left: ${particle.x}%; 
                            top: ${particle.y}%; 
                            width: ${particle.size}px; 
                            height: ${particle.size}px; 
                            opacity: ${particle.opacity};
                            animation: float-particle ${particle.speed + 3}s infinite alternate ease-in-out;
                         `"></div>
                </template>
            </div>
        </div>
        
        <div class="container mx-auto px-4 py-12 md:py-16 lg:py-20 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                    <!-- Left Column: Main Content -->
                    <div class="text-white space-y-6 md:space-y-8" data-aos="fade-right">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
                                Politiek voor iedereen
                            </span>
                        </h1>
                        <div class="h-16 sm:h-20 md:h-24 flex items-center">
                            <p class="text-lg sm:text-xl md:text-2xl text-gray-300 leading-relaxed"
                               x-data="{
                                   texts: [
                                       'Ontdek hoe de Nederlandse politiek werkt en wat dit voor jou betekent.',
                                       'Praat mee over actuele onderwerpen die Nederland bezighouden.',
                                       'Deel jouw mening en lees wat anderen ervan vinden.',
                                       'Blijf op de hoogte van de laatste politieke ontwikkelingen.',
                                       'Leer meer over verkiezingen en hoe jouw stem verschil maakt.'
                                   ],
                                   currentText: '',
                                   currentIndex: 0,
                                   isDeleting: false,
                                   typeSpeed: 50,
                                   deleteSpeed: 30,
                                   pauseSpeed: 2000
                               }"
                               x-init="$nextTick(() => {
                                   function type() {
                                       const current = texts[currentIndex];
                                       
                                       if (!isDeleting) {
                                           currentText = current.substring(0, currentText.length + 1);
                                           
                                           if (currentText === current) {
                                               isDeleting = true;
                                               setTimeout(type, pauseSpeed);
                                               return;
                                           }
                                           
                                           setTimeout(type, typeSpeed);
                                       } else {
                                           currentText = current.substring(0, currentText.length - 1);
                                           
                                           if (currentText === '') {
                                               isDeleting = false;
                                               currentIndex = (currentIndex + 1) % texts.length;
                                           }
                                           
                                           setTimeout(type, deleteSpeed);
                                       }
                                   }
                                   
                                   type();
                               })"
                               x-text="currentText">
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="inline-flex items-center justify-center bg-white text-primary px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-lg group">
                                <span>Ontdek onze blogs</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Live Peilingen -->
                    <div class="relative w-full" data-aos="fade-left">
                        <!-- Achtergrond met kleuren die passen bij de website -->
                        <div class="absolute inset-0 bg-[#1a365d] rounded-xl shadow-xl"></div>
                        
                        <!-- Decoratieve accenten - verbeterde header met gradient -->
                        <div class="absolute top-0 left-0 right-0 h-12 sm:h-16 bg-gradient-to-r from-[#1a365d] to-[#2a4a7c] rounded-t-xl flex items-center justify-between px-3 sm:px-5 overflow-hidden">
                            <!-- Rode accent border bovenaan -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-[#c41e3a]"></div>
                            
                            <!-- Decoratieve elementen -->
                            <div class="absolute -right-16 -top-16 w-32 h-32 bg-[#c41e3a]/10 rounded-full blur-xl"></div>
                            
                            <!-- Linker deel met titel -->
                            <div class="flex items-center space-x-2 sm:space-x-3 relative z-10">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-[#c41e3a] rounded-full flex items-center justify-center shadow-md">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base md:text-lg font-bold text-white tracking-wide uppercase">ACTUELE PEILINGEN</h3>
                                    <p class="hidden sm:block text-[10px] text-white/60">Laatste update: vandaag</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Main Content - aangepast met meer padding-top voor de header -->
                        <div class="relative p-3 sm:p-5 pt-16 sm:pt-20 rounded-xl overflow-hidden">
                            <!-- Info regel - datum verwijderd -->
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="flex items-center text-xs text-white/70">
                                    <span class="text-[10px] sm:text-xs">Tweede Kamer</span>
                                    <span class="inline-block mx-1 sm:mx-2">•</span>
                                    <span class="flex items-center text-[10px] sm:text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#c41e3a] animate-pulse mr-1"></span>
                                        <span>Live data</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Interactieve visualisatie van de peilingen -->
                            <div x-data="{ activeView: 'seats' }" class="relative">
                                <!-- Weergave selector -->
                                <div class="flex mb-2 sm:mb-3">
                                    <button @click="activeView = 'seats'" 
                                            :class="{'bg-[#c41e3a] text-white': activeView === 'seats', 'bg-white/10 text-white hover:bg-white/20': activeView !== 'seats'}" 
                                            class="text-[10px] sm:text-xs py-1 px-2 sm:px-3 rounded-l-md transition-colors duration-200 focus:outline-none font-semibold">
                                        Zetels
                                    </button>
                                    <button @click="activeView = 'percentage'" 
                                            :class="{'bg-[#c41e3a] text-white': activeView === 'percentage', 'bg-white/10 text-white hover:bg-white/20': activeView !== 'percentage'}" 
                                            class="text-[10px] sm:text-xs py-1 px-2 sm:px-3 rounded-r-md transition-colors duration-200 focus:outline-none font-semibold">
                                        Percentages
                                    </button>
                                </div>

                                <!-- Partijen ranking met compacte visualisatie -->
                                <div>
                                    <?php 
                                    $topParties = [
                                        'pvv' => ['seats' => 30, 'percentage' => 20.0, 'color' => '#0D47A1', 'change' => -4],
                                        'gl-pvda' => ['seats' => 27, 'percentage' => 18.0, 'color' => '#7B1FA2', 'change' => 3],
                                        'vvd' => ['seats' => 25, 'percentage' => 16.7, 'color' => '#FF6F00', 'change' => 5],
                                        'd66' => ['seats' => 11, 'percentage' => 7.3, 'color' => '#00796B', 'change' => 0],
                                        'cda' => ['seats' => 18, 'percentage' => 12.0, 'color' => '#2E7D32', 'change' => 2],
                                        'sp' => ['seats' => 6, 'percentage' => 4.0, 'color' => '#C62828', 'change' => -2],
                                        'pvdd' => ['seats' => 7, 'percentage' => 4.7, 'color' => '#388E3C', 'change' => -1],
                                        'ja21' => ['seats' => 4, 'percentage' => 2.7, 'color' => '#EF6C00', 'change' => 1]
                                    ];
                                    
                                    foreach($topParties as $party => $data): 
                                        $changeClass = $data['change'] > 0 ? 'text-green-400' : ($data['change'] < 0 ? 'text-red-400' : 'text-yellow-400');
                                        $changeIndicator = $data['change'] > 0 ? '+' . $data['change'] : $data['change'];
                                    ?>
                                        <div class="mb-1 sm:mb-1.5 last:mb-0 relative">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center w-16 sm:w-24">
                                                    <div class="h-2 w-2 mr-1 sm:mr-1.5 rounded-full" style="background-color: <?php echo $data['color']; ?>"></div>
                                                    <span class="text-[10px] sm:text-xs font-semibold text-white uppercase"><?php echo $party; ?></span>
                                                </div>
                                                <div class="relative h-1.5 sm:h-2 bg-white/10 rounded-full overflow-hidden flex-grow mx-1 sm:mx-2">
                                                    <div x-show="activeView === 'seats'" class="absolute inset-y-0 left-0 rounded-full" 
                                                         style="width: <?php echo ($data['seats'] / 150) * 100; ?>%; background-color: <?php echo $data['color']; ?>"></div>
                                                    <div x-show="activeView === 'percentage'" class="absolute inset-y-0 left-0 rounded-full" 
                                                         style="width: <?php echo $data['percentage']; ?>%; background-color: <?php echo $data['color']; ?>"></div>
                                                </div>
                                                <div class="flex items-center w-12 sm:w-16 justify-end">
                                                    <span x-show="activeView === 'seats'" class="text-[10px] sm:text-xs font-bold text-white"><?php echo $data['seats']; ?></span>
                                                    <span x-show="activeView === 'percentage'" class="text-[10px] sm:text-xs font-bold text-white"><?php echo $data['percentage']; ?>%</span>
                                                    <span class="<?php echo $changeClass; ?> text-[8px] sm:text-[10px] ml-1 sm:ml-1.5"><?php echo $changeIndicator; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <!-- Resterende partijen samenvatting -->
                                    <div class="mt-1 pt-1 border-t border-white/10">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center w-16 sm:w-24">
                                                <span class="text-[10px] sm:text-xs text-white/80">Overige partijen</span>
                                            </div>
                                            <div class="relative h-1.5 sm:h-2 bg-white/10 rounded-full overflow-hidden flex-grow mx-1 sm:mx-2">
                                                <div x-show="activeView === 'seats'" class="absolute inset-y-0 left-0 bg-white/20 rounded-full" style="width: <?php echo (22 / 150) * 100; ?>%"></div>
                                                <div x-show="activeView === 'percentage'" class="absolute inset-y-0 left-0 bg-white/20 rounded-full" style="width: 14.6%"></div>
                                            </div>
                                            <div class="flex items-center w-12 sm:w-16 justify-end">
                                                <span x-show="activeView === 'seats'" class="text-[10px] sm:text-xs font-bold text-white">22</span>
                                                <span x-show="activeView === 'percentage'" class="text-[10px] sm:text-xs font-bold text-white">14.6%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ontwikkeling in 90 dagen -->
                                <div class="mt-3 sm:mt-4 pt-2 sm:pt-3 border-t border-white/10">
                                    <h4 class="text-[10px] sm:text-xs font-semibold text-white mb-1 sm:mb-2">Ontwikkeling in 90 dagen</h4>
                                    <div class="relative h-14 sm:h-20 w-full">
                                        <div class="absolute inset-0">
                                            <!-- Verbeterde lijngrafiek met dikkere lijnen -->
                                            <svg class="w-full h-full" viewBox="0 0 400 100" preserveAspectRatio="none">
                                                <!-- Horizontale gridlijnen -->
                                                <line x1="0" y1="25" x2="400" y2="25" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
                                                <line x1="0" y1="50" x2="400" y2="50" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
                                                <line x1="0" y1="75" x2="400" y2="75" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
                                                
                                                <!-- Verticale gridlijnen voor beter referentiepunt -->
                                                <line x1="100" y1="0" x2="100" y2="100" stroke="rgba(255,255,255,0.05)" stroke-width="1" />
                                                <line x1="200" y1="0" x2="200" y2="100" stroke="rgba(255,255,255,0.05)" stroke-width="1" />
                                                <line x1="300" y1="0" x2="300" y2="100" stroke="rgba(255,255,255,0.05)" stroke-width="1" />
                                                
                                                <!-- PVV lijn - dikker en beter zichtbaar -->
                                                <path d="M0,40 L50,45 L100,30 L150,45 L200,60 L250,40 L300,35 L350,30 L400,60" 
                                                      fill="none" stroke="#0D47A1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                                
                                                <!-- GL-PvdA lijn - dikker en beter zichtbaar -->
                                                <path d="M0,60 L50,55 L100,55 L150,50 L200,45 L250,40 L300,40 L350,30 L400,25" 
                                                      fill="none" stroke="#7B1FA2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                                
                                                <!-- VVD lijn - dikker en beter zichtbaar -->
                                                <path d="M0,50 L50,55 L100,60 L150,55 L200,50 L250,50 L300,50 L350,40 L400,30" 
                                                      fill="none" stroke="#FF6F00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Legenda met duidelijkere markers -->
                                        <div class="absolute bottom-0 left-0 right-0 flex justify-between sm:justify-start sm:space-x-4">
                                            <div class="flex items-center">
                                                <div class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-[#0D47A1] mr-1"></div>
                                                <span class="text-[8px] sm:text-[10px] text-white/80">PVV</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-[#7B1FA2] mr-1"></div>
                                                <span class="text-[8px] sm:text-[10px] text-white/80">GL-PvdA</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-[#FF6F00] mr-1"></div>
                                                <span class="text-[8px] sm:text-[10px] text-white/80">VVD</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Tijdsaanduiding -->
                                        <div class="absolute top-1 left-0 right-0 flex justify-between">
                                            <span class="text-[7px] sm:text-[8px] text-white/40">90 dagen</span>
                                            <span class="text-[7px] sm:text-[8px] text-white/40">Heden</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Footer met informatie en links -->
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-[8px] sm:text-[10px] text-white/50">Peilingwijzer • Ipsos I&O Research</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave Separator -->
        <div class="absolute bottom-[-20px] left-0 right-0 overflow-hidden z-0">
            <svg class="w-full h-[80px]" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0 120L40 114C80 108 160 96 240 90C320 84 400 84 480 78C560 72 640 60 720 54C800 48 880 48 960 54C1040 60 1120 72 1200 78C1280 84 1360 84 1400 84L1440 84V120H1400C1360 120 1280 120 1200 120C1120 120 1040 120 960 120C880 120 800 120 720 120C640 120 560 120 480 120C400 120 320 120 240 120C160 120 80 120 40 120H0Z" fill="white"/>
                <path d="M0 120L40 102C80 84 160 48 240 36C320 24 400 36 480 48C560 60 640 72 720 78C800 84 880 84 960 78C1040 72 1120 60 1200 48C1280 36 1360 24 1400 18L1440 12V120H1400C1360 120 1280 120 1200 120C1120 120 1040 120 960 120C880 120 800 120 720 120C640 120 560 120 480 120C400 120 320 120 240 120C160 120 80 120 40 120H0Z" fill="white" fill-opacity="0.5"/>
            </svg>
        </div>
    </section>

    <!-- Uitgelichte Nieuwste Blog Section -->
    <section class="py-16 bg-white relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50 via-white to-gray-50"></div>
        <div class="absolute top-40 right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-20 w-72 h-72 bg-secondary/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative">
            <!-- Section Header with "NIEUWSTE BLOG" text -->
            <div class="text-center mb-12 relative" data-aos="fade-up">
                <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">SPOTLIGHT</span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Net gepubliceerd</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ontdek onze nieuwste politieke analyse</p>
            </div>

            <?php 
            // Haal de allernieuwste blog op (eerste item uit de $latest_blogs array)
            if (!empty($latest_blogs) && isset($latest_blogs[0])): 
                $newest_blog = $latest_blogs[0];
                // Check of de blog minder dan 24 uur oud is
                $published_time = strtotime($newest_blog->published_at);
                $twenty_four_hours_ago = time() - (24 * 3600);
                $is_very_new = $published_time > $twenty_four_hours_ago;
            ?>
            <div class="max-w-7xl mx-auto" data-aos="zoom-in">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden group relative transform transition duration-500 hover:shadow-2xl">
                    <!-- Gradient border effect on hover -->
                    <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl -m-0.5 z-0"></div>
                    
                    <div class="relative z-10 flex flex-col lg:flex-row">
                        <!-- Image Column -->
                        <div class="lg:w-1/2 relative overflow-hidden">
                            <?php if ($newest_blog->image_path): ?>
                                <div class="relative h-64 lg:h-full">
                                    <img src="<?php echo URLROOT . '/' . $newest_blog->image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($newest_blog->title); ?>"
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                    
                                    <!-- Stronger gradient overlay to ensure text readability -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/30"></div>
                                    
                                    <?php if ($is_very_new): ?>
                                        <!-- "NIEUW" badge -->
                                        <div class="absolute top-4 left-4 z-20">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white text-primary text-sm font-semibold shadow-lg">
                                                <span class="relative flex h-2 w-2 mr-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                                                </span>
                                                NIEUW
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="h-64 lg:h-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content Column -->
                        <div class="lg:w-1/2 p-6 lg:p-10 flex flex-col justify-between bg-white">
                            <div>
                                <!-- Author info and publish date -->
                                <div class="flex items-center text-sm text-gray-600 mb-6">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="7" r="4" stroke-width="2"/>
                                            <path stroke-width="2" d="M5 21v-2a7 7 0 0114 0v2"/>
                                        </svg>
                                        <?php echo htmlspecialchars($newest_blog->author_name); ?>
                                    </span>
                                    <span class="mx-3">•</span>
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo getRelativeTime($newest_blog->published_at); ?>
                                    </span>
                                </div>
                                
                                <!-- Blog title with solid color and no hover effect on text -->
                                <h2 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">
                                    <?php echo htmlspecialchars($newest_blog->title); ?>
                                </h2>
                                
                                <!-- Blog summary with better contrast -->
                                <p class="text-gray-700 mb-6 text-lg leading-relaxed">
                                    <?php echo htmlspecialchars($newest_blog->summary); ?>
                                </p>
                            </div>
                            
                            <!-- Action button -->
                            <div>
                                <a href="<?php echo URLROOT . '/blogs/view/' . $newest_blog->slug; ?>" 
                                   class="inline-flex items-center px-6 py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-all transform group-hover:scale-105 shadow-md">
                                    <span>Lees het volledige artikel</span>
                                    <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Nieuwe Stemwijzer Highlight Section -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <!-- Decoratieve elementen -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(0,0,0,0.03)\"%3E%3C/path%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative">

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Linker kolom: Content -->
                    <div class="space-y-8" data-aos="fade-right">
                        <div class="space-y-6">
                            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                    Ontdek de Stemwijzer 2025
                                </span>
                            </h2>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                Vind de partij die het beste bij jouw standpunten past met onze gloednieuwe stemwijzer. 
                                Beantwoord 25 belangrijke stellingen en krijg direct inzicht in jouw politieke voorkeuren.
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 transform transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Snel & Eenvoudig</h3>
                                    <p class="text-sm text-gray-500">Slechts 10-15 minuten</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center flex-shrink-0 transform transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">100% Anoniem</h3>
                                    <p class="text-sm text-gray-500">Privacy gewaarborgd</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 transform transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Direct Resultaat</h3>
                                    <p class="text-sm text-gray-500">Meteen inzicht</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 transform transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Actueel</h3>
                                    <p class="text-sm text-gray-500">Laatste standpunten</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-4">
                            <a href="<?php echo URLROOT; ?>/stemwijzer" 
                               class="group relative inline-flex items-center justify-center px-8 py-4 overflow-hidden bg-gradient-to-r from-primary to-secondary rounded-xl transition-all duration-500 hover:scale-105 hover:shadow-xl shadow-lg">
                                <div class="absolute inset-0 w-0 bg-gradient-to-r from-secondary to-primary transition-all duration-500 ease-out group-hover:w-full"></div>
                                <div class="relative flex items-center justify-center text-white font-semibold">
                                    <span class="mr-3">Start de Stemwijzer</span>
                                    <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Rechter kolom: Interactieve Illustratie -->
                    <div class="relative lg:pl-12" data-aos="fade-left">
                        <div class="relative">
                            <!-- Decoratieve achtergrond -->
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-3xl transform rotate-3"></div>
                            
                            <!-- Main content card -->
                            <div class="relative bg-white rounded-2xl shadow-xl p-8 transform -rotate-3 hover:rotate-0 transition-all duration-500">
                                <!-- Card header -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Stemwijzer 2025</h3>
                                            <p class="text-sm text-gray-500">25 belangrijke stellingen</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                        Live
                                    </span>
                                </div>

                                <!-- Example question -->
                                <div class="space-y-6">
                                    <div class="p-4 bg-gray-50 rounded-xl">
                                        <p class="text-sm font-medium text-gray-900 mb-2">Voorbeeldstelling:</p>
                                        <p class="text-sm text-gray-600">"Nederland moet meer investeren in duurzame energie."</p>
                                    </div>

                                    <!-- Answer options -->
                                    <div class="grid grid-cols-1 gap-3">
                                        <button class="w-full px-4 py-3 text-sm font-medium text-left text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary/5 hover:border-primary/20 hover:text-primary transition-all duration-200">
                                            Eens
                                        </button>
                                        <button class="w-full px-4 py-3 text-sm font-medium text-left text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary/5 hover:border-primary/20 hover:text-primary transition-all duration-200">
                                            Neutraal
                                        </button>
                                        <button class="w-full px-4 py-3 text-sm font-medium text-left text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary/5 hover:border-primary/20 hover:text-primary transition-all duration-200">
                                            Oneens
                                        </button>
                                    </div>
                                </div>

                                <!-- Progress indicator -->
                                <div class="mt-6">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                        <span>Voortgang</span>
                                        <span>0/25 vragen</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="w-0 h-full bg-gradient-to-r from-primary to-secondary rounded-full"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Decorative elements -->
                            <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full blur-2xl"></div>
                            <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-secondary/20 to-primary/20 rounded-full blur-2xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laatste Nieuws & Blogs Sections -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <!-- Laatste Blogs -->
            <div class="mb-20">
                <div class="text-center mb-16 relative" data-aos="fade-up" data-aos-once="true">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">BLOGS</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Laatste Blogs</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ontdek de meest recente politieke analyses en inzichten van onze community</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($latest_blogs as $index => $blog): ?>
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 relative" 
                                data-aos="fade-up" 
                                data-aos-delay="<?php echo $index * 100; ?>"
                                data-aos-duration="800"
                                data-aos-easing="ease-out-cubic"
                                data-aos-once="true">
                            <?php 
                            // Check of de blog minder dan 12 uur oud is
                            $published_time = strtotime($blog->published_at);
                            $twelve_hours_ago = time() - (12 * 3600); // 12 uur in seconden
                            
                            if ($published_time > $twelve_hours_ago): 
                            ?>
                                <!-- Nieuw Badge voor recent geplaatste blogs -->
                                <div class="absolute top-4 right-4 z-20">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold shadow-lg">
                                        <span class="relative flex h-2 w-2 mr-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                        </span>
                                        NIEUW
                                    </div>
                                </div>
                                <!-- Extra highlight effect voor nieuwe blogs -->
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 pointer-events-none"></div>
                            <?php endif; ?>

                            <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>" class="block relative">
                                <?php if ($blog->image_path): ?>
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                             alt="<?php echo htmlspecialchars($blog->title); ?>"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                <?php endif; ?>

                                <div class="p-6">
                                    <div class="flex items-center text-sm text-gray-500 mb-4">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="7" r="4" stroke-width="2"/>
                                                <path stroke-width="2" d="M5 21v-2a7 7 0 0114 0v2"/>
                                            </svg>
                                            <?php echo htmlspecialchars($blog->author_name); ?>
                                        </span>
                                        <span class="mx-3">-</span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo getRelativeTime($blog->published_at); ?>
                                        </span>
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">
                                        <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>">
                                            <?php echo htmlspecialchars($blog->title); ?>
                                        </a>
                                    </h2>

                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        <?php echo htmlspecialchars($blog->summary); ?>
                                    </p>

                                    <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>" 
                                       class="inline-flex items-center text-primary font-medium group-hover:text-secondary transition-colors">
                                        <span>Lees meer</span>
                                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- CTA Button -->
                <div class="text-center mt-16" data-aos="zoom-in" data-aos-delay="300" data-aos-once="true">
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span>Bekijk alle blogs</span>
                        <svg class="w-5 h-5 ml-2 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Laatste Nieuws -->
            <div class="relative pt-16" data-aos="fade-up" data-aos-once="true">
                <!-- Decoratieve elementen -->
                <div class="absolute -top-10 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>

                <div class="text-center mb-16 relative" data-aos="fade-up" data-aos-once="true">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">NIEUWS</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Laatste Politiek Nieuws</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Blijf op de hoogte van de laatste ontwikkelingen in de Nederlandse politiek</p>
                </div>

                <!-- Laatste Nieuws Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative">
                    <!-- Links georiënteerde bronnen -->
                    <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            Progressieve Media
                        </h3>
                        <?php
                        $links_news = array_filter($latest_news, function($news) {
                            return $news['orientation'] === 'links';
                        });
                        foreach($links_news as $index => $news):
                        ?>
                            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                                     data-aos="fade-up" 
                                     data-aos-delay="<?php echo $index * 100; ?>"
                                     data-aos-duration="800"
                                     data-aos-once="true">
                                <div class="relative p-6">
                                    <!-- News Source & Date -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="relative">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform duration-300">
                                                    <span class="text-blue-600 font-semibold"><?php echo substr($news['source'], 0, 2); ?></span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900"><?php echo $news['source']; ?></p>
                                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded-full"><?php echo $news['bias']; ?></span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo getRelativeTime($news['publishedAt']); ?>
                                        </div>
                                    </div>
                                    <!-- Rest van de artikel content -->
                                    <div class="space-y-3">
                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">
                                            <?php echo $news['title']; ?>
                                        </h3>
                                        <p class="text-gray-600 line-clamp-2">
                                            <?php echo $news['description']; ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Article Footer -->
                                    <div class="mt-6 flex items-center justify-between">
                                        <a href="<?php echo $news['url']; ?>" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center text-blue-600 font-semibold group-hover:text-blue-700 transition-colors">
                                            <span>Lees artikel</span>
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Rechts georiënteerde bronnen -->
                    <div class="space-y-8" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Conservatieve Media
                        </h3>
                        <?php
                        $rechts_news = array_filter($latest_news, function($news) {
                            return $news['orientation'] === 'rechts';
                        });
                        foreach($rechts_news as $index => $news):
                        ?>
                            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                                     data-aos="fade-up" 
                                     data-aos-delay="<?php echo $index * 100; ?>"
                                     data-aos-duration="800"
                                     data-aos-once="true">
                                <div class="relative p-6">
                                    <!-- News Source & Date -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="relative">
                                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform duration-300">
                                                    <span class="text-red-600 font-semibold"><?php echo substr($news['source'], 0, 2); ?></span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900"><?php echo $news['source']; ?></p>
                                                <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full"><?php echo $news['bias']; ?></span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo getRelativeTime($news['publishedAt']); ?>
                                        </div>
                                    </div>
                                    <!-- Rest van de artikel content -->
                                    <div class="space-y-3">
                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">
                                            <?php echo $news['title']; ?>
                                        </h3>
                                        <p class="text-gray-600 line-clamp-2">
                                            <?php echo $news['description']; ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Article Footer -->
                                    <div class="mt-6 flex items-center justify-between">
                                        <a href="<?php echo $news['url']; ?>" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center text-red-600 font-semibold group-hover:text-red-700 transition-colors">
                                            <span>Lees artikel</span>
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center mt-16">
                    <a href="<?php echo URLROOT; ?>/nieuws" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl group">
                        <span>Bekijk al het nieuws</span>
                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Actuele Thema's Grid -->
    <section class="py-16 relative overflow-hidden">
        <!-- Decoratieve elementen -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50 via-white to-gray-50"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative">
            <div class="text-center mb-16 relative" data-aos="fade-up">
                <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">THEMA'S</span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Actuele Thema's</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ontdek de belangrijkste politieke onderwerpen van dit moment</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($actuele_themas as $index => $thema): ?>
                    <div class="group relative" data-aos="zoom-in" data-aos-delay="<?php echo $index * 100; ?>">
                        <!-- Card Background with Gradient Border -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary to-secondary rounded-2xl opacity-50 blur group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-white rounded-2xl p-6 shadow-lg transform transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl overflow-hidden">
                            <!-- Decorative Pattern -->
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(0,0,0,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-10"></div>

                            <!-- Icon Container -->
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl flex items-center justify-center mb-6 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                    <span class="text-4xl"><?php echo $thema['icon']; ?></span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="relative space-y-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors">
                                    <?php echo $thema['title']; ?>
                                </h3>
                                <p class="text-gray-600 line-clamp-3">
                                    <?php echo $thema['description']; ?>
                                </p>

                                <!-- Stats & Tags -->
                                <div class="flex flex-wrap gap-2 pt-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        <?php echo rand(10, 99); ?> discussies
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-secondary/10 text-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7 1.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <?php echo rand(100, 999); ?> volgers
                                    </span>
                                </div>

                                <!-- Action Link -->
                                <a href="<?php echo URLROOT; ?>/thema/<?php echo strtolower(str_replace(' ', '-', $thema['title'])); ?>" 
                                   class="inline-flex items-center mt-6 text-primary font-semibold group-hover:text-secondary transition-colors">
                                    <span class="relative">
                                        Ontdek meer
                                        <div class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                                    </span>
                                    <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Decorative Corner -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-10 -translate-y-10 group-hover:translate-x-8 group-hover:-translate-y-8 transition-transform duration-500"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-16" data-aos="fade-up">
                <a href="<?php echo URLROOT; ?>/themas" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl group">
                    <span>Bekijk alle thema's</span>
                    <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="py-16 bg-gradient-to-br from-primary/10 to-secondary/10">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 md:p-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Doe mee aan het debat
                        </h2>
                        <p class="text-gray-600 mb-6">
                            Word lid van onze community en deel jouw perspectief op de Nederlandse politiek. 
                            Start discussies, schrijf blogs en draag bij aan het politieke debat.
                        </p>
                        <div class="space-y-4">
                            <a href="<?php echo URLROOT; ?>/register" 
                               class="block text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                Registreer nu
                            </a>
                            <a href="<?php echo URLROOT; ?>/login" 
                               class="block text-center text-primary hover:text-secondary transition-colors">
                                Al een account? Log in
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 bg-gradient-to-br from-primary to-secondary p-8 md:p-12 text-white">
                        <h3 class="text-2xl font-bold mb-6">Waarom meedoen?</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Deel je politieke inzichten
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Neem deel aan discussies
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Schrijf en publiceer blogs
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verbind met gelijkgestemden
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-auto" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                
            </svg>
        </div>
    </section>
</main>

<?php require_once 'views/templates/footer.php'; ?>

<?php
// Helper functie voor relatieve tijd (voeg deze toe bovenaan het bestand na de requires)
function getRelativeTime($date) {
    $timestamp = strtotime($date);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return 'zojuist';
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' ' . ($minutes == 1 ? 'minuut' : 'minuten') . ' geleden';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' ' . ($hours == 1 ? 'uur' : 'uur') . ' geleden';
    } else {
        return date('d M Y', $timestamp);
    }
} 