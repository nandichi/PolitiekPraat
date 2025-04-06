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
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

// Haal de populairste blogs op voor de hero sectie
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY views DESC, published_at DESC 
           LIMIT 4");
$featured_blogs = $db->resultSet();

// Berekenen van relatieve publicatiedatum voor blogs
foreach ($latest_blogs as $blog) {
    $blog->relative_date = getRelativeTime($blog->published_at);
}
foreach ($featured_blogs as $blog) {
    $blog->relative_date = getRelativeTime($blog->published_at);
}

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
    <!-- Hero Section - Volledig vernieuwde versie -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary pt-12 pb-16 overflow-hidden">
        <!-- Subtiele achtergrond patronen -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')]"></div>
        </div>
        
        <!-- Decoratieve accent lijn bovenaan -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary via-primary to-secondary"></div>
        
        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                <!-- Linker kolom: Welkomstekst en CTA -->
                <div class="w-full lg:w-1/2 text-white mb-10 lg:mb-0">
                    <div class="relative">
                        <!-- Logo icon in header stijl -->
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-white/10 
                                    backdrop-blur-sm shadow-lg transition-all duration-500 
                                    hover:bg-white/15 hover:scale-105 
                                    hover:shadow-secondary/10 hover:shadow-lg mb-6 relative">
                            
                            <!-- PolitiekPraat Logo -->
                            <img src="<?php echo URLROOT; ?>/images/favicon-512x512.png" 
                                 alt="PolitiekPraat Logo" 
                                 class="w-9 h-9 object-contain transition-all duration-500">
                            
                            <!-- Subtle glow effect -->
                            <div class="absolute inset-0 rounded-xl bg-white/5 opacity-0 blur-sm hover:opacity-100 
                                        transition-opacity duration-500"></div>
            </div>
            
                        <!-- Hoofdtitel met moderne styling -->
                        <h1 class="text-4xl sm:text-5xl lg:text-5xl font-bold mb-6 tracking-tight">
                            PolitiekPraat
                        </h1>
                        
                        <!-- Ondertitel met dynamische typewriter effect -->
                        <div class="text-lg sm:text-xl text-white/80 mb-8 max-w-lg leading-relaxed">
                            <span id="typewriter" class="inline-block"></span>
                            <span class="animate-pulse">|</span>
            </div>
            
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const typewriterElement = document.getElementById('typewriter');
                                const sentences = [
                                    "Ontdek hoe de Nederlandse politiek werkt en wat dit voor jou betekent.",
                                    "Volg de laatste ontwikkelingen in Den Haag op een toegankelijke manier.",
                                    "Blijf op de hoogte van belangrijke debatten en beslissingen in de Tweede Kamer.",
                                    "Leer meer over het Nederlandse democratische systeem en je rol daarin.",
                                    "Verdiep je in actuele politieke thema's die Nederland bezighouden.",
                                    "Ontdek hoe wetsvoorstellen tot stand komen en wat de gevolgen zijn.",
                                    "Krijg inzicht in de coalitievorming en politieke samenwerking in Nederland.",
                                    "Begrijp hoe de verkiezingen werken en waarom jouw stem belangrijk is.",
                                    "Verken de geschiedenis en toekomst van de Nederlandse politiek."
                                ];
                                
                                // Zet direct de eerste zin neer zodat er meteen iets zichtbaar is
                                typewriterElement.textContent = sentences[0];
                                
                                // Geen typewriter effect meer, gewoon de zinnen wisselen
                                let currentIndex = 0;
                                
                                function changeSentence() {
                                    currentIndex = (currentIndex + 1) % sentences.length;
                                    
                                    // Fade out effect
                                    typewriterElement.style.opacity = 0;
                                    
                                    setTimeout(function() {
                                        // Verander de tekst
                                        typewriterElement.textContent = sentences[currentIndex];
                                        
                                        // Fade in effect
                                        typewriterElement.style.opacity = 1;
                                    }, 500);
                                    
                                    setTimeout(changeSentence, 3000);
                                }
                                
                                // Voeg CSS toe voor de fade transitie
                                typewriterElement.style.transition = "opacity 0.5s ease";
                                typewriterElement.style.opacity = 1;
                                
                                // Start het wisselen van zinnen na een pauze
                                setTimeout(changeSentence, 3000);
                            });
                        </script>
                        
                        <!-- Call-to-action knop -->
                            <a href="<?php echo URLROOT; ?>/blogs" 
                           class="inline-flex items-center px-6 py-3 bg-white text-primary rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-300 group">
                                <span>Ontdek onze blogs</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                <!-- Rechter kolom: Blog slider - Smaller gemaakt -->
                <div class="w-full lg:w-5/12 mx-auto">
                    <div class="relative bg-white/5 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden">
                        <!-- Header met titel -->
                        <div class="flex items-center py-3 px-4 border-b border-white/10">
                            <div class="w-6 h-6 bg-[#c41e3a] rounded-full flex items-center justify-center mr-3">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                <h3 class="text-sm font-medium text-white uppercase tracking-wide">Uitgelichte Blogs</h3>
                                <p class="text-xs text-white/60">Actueel: <?php echo date('d-m-Y'); ?></p>
                            </div>
                        </div>
                        
                        <!-- Blog swiper container - Hoogte aangepast -->
                        <div class="hero-blog-swiper h-[320px] sm:h-[340px]">
                            <div class="swiper-wrapper">
                                <?php foreach($featured_blogs as $blog): ?>
                                <div class="swiper-slide">
                                    <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" class="group block h-full">
                                        <div class="relative h-full">
                                            <!-- Afbeelding met overlay -->
                                            <div class="relative h-36 sm:h-40">
                                                <?php if($blog->image_path): ?>
                                                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                         alt="<?php echo htmlspecialchars($blog->title); ?>" 
                                                         class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full bg-gradient-to-br from-primary/50 to-secondary/50 flex items-center justify-center">
                                                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M15 3v4m0 0v4m0-4h4m-4 0H7m15 5v5a2 2 0 01-2 2h-4"></path>
                                                        </svg>
                                            </div>
                                                <?php endif; ?>
                                                
                                                <!-- Category badge -->
                                                <div class="absolute top-3 left-3">
                                                    <span class="inline-flex items-center px-2 py-1 bg-[#c41e3a] text-white text-xs font-medium rounded-md shadow-sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Blog
                                                    </span>
                                                </div>
                                                </div>
                                                
                                            <!-- Content section -->
                                            <div class="p-4 bg-white/5">
                                                <h4 class="text-base font-semibold text-white mb-2 line-clamp-2 group-hover:text-[#c41e3a] transition-colors duration-300">
                                                    <?php echo htmlspecialchars($blog->title); ?>
                                                </h4>
                                                
                                                <p class="text-xs text-white/70 mb-3 line-clamp-2">
                                                    <?php echo htmlspecialchars($blog->summary); ?>
                                                </p>
                                                
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center mr-2">
                                                            <svg class="w-3 h-3 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        </div>
                                                        <span class="text-xs text-white/80"><?php echo htmlspecialchars($blog->author_name); ?></span>
                                                    </div>
                                                    
                                                    <span class="text-xs text-white/60">
                                                        <?php echo $blog->relative_date; ?>
                                                    </span>
                                                </div>
                                                
                                                <div class="mt-3 flex">
                                                    <span class="inline-flex items-center text-xs font-medium text-white hover:text-white/90 transition-colors duration-300">
                                                        Lees artikel
                                                        <svg class="w-3.5 h-3.5 ml-1.5 group-hover:translate-x-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                            </div>
                            
                            <!-- Navigatie controls -->
                            <div class="absolute top-1/2 -translate-y-1/2 z-10 flex justify-between w-full px-3">
                                <button class="hero-swiper-button-prev w-7 h-7 flex items-center justify-center rounded-full bg-black/30 text-white hover:bg-[#c41e3a] transition-colors duration-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        
                                <button class="hero-swiper-button-next w-7 h-7 flex items-center justify-center rounded-full bg-black/30 text-white hover:bg-[#c41e3a] transition-colors duration-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                            </div>
                            
                            <!-- Progress en paginering -->
                            <div class="absolute bottom-0 inset-x-0 h-8 px-4 z-10 flex items-center">
                                <div class="w-full">
                                    <!-- Paginering indicators -->
                                    <div class="hero-swiper-pagination flex justify-center"></div>
                                    
                                    <!-- Progress bar -->
                                    <div class="h-0.5 w-full bg-white/10 rounded-full overflow-hidden mt-1">
                                        <div class="swiper-progress-bar h-full bg-[#c41e3a]" style="width: 0%"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                        <!-- Footer leeg gelaten -->
                        <div class="px-4 py-2 flex justify-end">
                            <!-- Link verwijderd -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        <!-- Wave separator toegevoegd -->
        <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-0 transform z-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block h-8 w-full" style="transform: rotate(180deg)">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".15" fill="#FFFFFF"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".2" fill="#FFFFFF"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" opacity=".25" fill="#FFFFFF"></path>
            </svg>
        </div>
    </section>

    <script>
        // Hero blog swiper initialisatie
        document.addEventListener('DOMContentLoaded', function() {
            const heroSwiper = new Swiper('.hero-blog-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                grabCursor: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                speed: 800,
                loop: true,
                effect: 'slide',
                navigation: {
                    nextEl: '.hero-swiper-button-next',
                    prevEl: '.hero-swiper-button-prev',
                },
                pagination: {
                    el: '.hero-swiper-pagination',
                    clickable: true,
                    bulletClass: 'w-1.5 h-1.5 mx-1 rounded-full bg-white/30 transition-all duration-300 cursor-pointer',
                    bulletActiveClass: 'w-4 bg-[#c41e3a]',
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '"></span>';
                    },
                },
                on: {
                    init: function() {
                        updateProgressBar(this);
                    },
                    slideChange: function() {
                        updateProgressBar(this);
                    }
                }
            });
            
            // Update progress bar functie
            function updateProgressBar(swiper) {
                const progressBar = document.querySelector('.swiper-progress-bar');
                if (progressBar) {
                    const totalSlides = swiper.slides.length - 2; // Loop mode dupliceert 2 slides
                    const currentIndex = swiper.realIndex;
                    const percentage = (currentIndex / (totalSlides - 1)) * 100;
                    progressBar.style.width = percentage + '%';
                }
            }
        });
    </script>

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

    <!-- Partijen Highlight Section -->
    <section class="py-16 bg-gradient-to-br from-blue-50 to-white relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(0,0,0,0.03)\"%3E%3C/path%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Linker kolom: Interactieve visualisatie -->
                    <div class="relative" data-aos="fade-right">
                        <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-2xl blur opacity-25"></div>
                        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden group hover:shadow-2xl transition-all duration-500">
                            <div class="p-6">
                                <h3 class="text-2xl font-bold mb-4 text-gray-900">Zetelverdeling Tweede Kamer</h3>
                                
                                <!-- Pie chart implementatie voor zetelverdeling -->
                                <div class="relative w-full py-4">
                                    <?php
                                    $parties = [
                                        ['name' => 'PVV', 'color' => '#0078D7', 'seats' => 37],
                                        ['name' => 'GL-PvdA', 'color' => '#008800', 'seats' => 25],
                                        ['name' => 'VVD', 'color' => '#FF9900', 'seats' => 24],
                                        ['name' => 'NSC', 'color' => '#4D7F78', 'seats' => 20],
                                        ['name' => 'D66', 'color' => '#00B13C', 'seats' => 9],
                                        ['name' => 'CDA', 'color' => '#1E8449', 'seats' => 5],
                                        ['name' => 'SP', 'color' => '#EE0000', 'seats' => 5]
                                    ];
                                    // Sorteer partijen op aantal zetels (aflopend)
                                    usort($parties, function($a, $b) {
                                        return $b['seats'] - $a['seats'];
                                    });
                                    
                                    // Bereken totaal aantal zetels
                                    $totalSeats = array_sum(array_column($parties, 'seats'));
                                    
                                    // Bereken de percentages en bouw de conic-gradient op
                                    $gradientParts = [];
                                    $currentPercentage = 0;
                                    
                                    foreach($parties as $party) {
                                        $percentage = ($party['seats'] / $totalSeats) * 100;
                                        $endPercentage = $currentPercentage + $percentage;
                                        
                                        $gradientParts[] = $party['color'] . ' ' . 
                                                         round($currentPercentage, 1) . '% ' . 
                                                         round($endPercentage, 1) . '%';
                                        
                                        $currentPercentage = $endPercentage;
                                    }
                                    
                                    $conicGradient = implode(', ', $gradientParts);
                                    ?>
                                    
                                    <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-8">
                                        <!-- Pie chart -->
                                        <div class="relative">
                                            <div class="w-48 h-48 rounded-full shadow-lg" 
                                                 style="background: conic-gradient(<?php echo $conicGradient; ?>);">
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-16 h-16 bg-white rounded-full shadow-inner flex items-center justify-center text-sm font-medium">

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Legenda -->
                                        <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                                            <?php foreach($parties as $party): ?>
                                                <div class="flex items-center">
                                                    <div class="w-4 h-4 rounded-sm mr-2" style="background-color: <?php echo $party['color']; ?>"></div>
                                                    <div class="text-sm">
                                                        <span class="font-medium"><?php echo $party['name']; ?></span>
                                                        <span class="ml-1 text-gray-600">(<?php echo $party['seats']; ?>)</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center text-xs text-gray-500 mt-2">
                                        Totaal: <?php echo $totalSeats; ?> van 150 zetels in de Tweede Kamer
                                    </div>
                                </div>
                                
                                <div class="mt-6 text-right">
                                    <a href="<?php echo URLROOT; ?>/partijen" class="text-primary hover:text-secondary transition-colors text-sm font-medium">
                                        Bekijk alle partijen →
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Animated background effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>
                    
                    <!-- Left column: Content (now on right) -->
                    <div class="space-y-8" data-aos="fade-left">
                        <div class="space-y-6">
                            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    Politieke Partijen
                                </span>
                            </h2>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                Verken alle politieke partijen in Nederland en hun standpunten op één overzichtelijke plek. 
                                Leer meer over hun leiders, geschiedenis en waar ze voor staan in het huidige politieke landschap.
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Alle Partijen</h3>
                                    <p class="text-sm text-gray-500">Van groot tot klein</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Standpunten</h3>
                                    <p class="text-sm text-gray-500">Helder en duidelijk</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Partijleiders</h3>
                                    <p class="text-sm text-gray-500">Leer ze kennen</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Zetelverdeling</h3>
                                    <p class="text-sm text-gray-500">Actuele peilingen</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="<?php echo URLROOT; ?>/partijen" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-md">
                                <span>Bekijk alle partijen</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                                    Stemwijzer 2025
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



    </section>

    <!-- Newsletter Subscription Section -->
    <section class="py-16 px-4 md:px-8 bg-gradient-to-br from-primary/5 to-gray-50 rounded-3xl mx-4 mb-16 mt-16">
        <div class="max-w-4xl mx-auto text-center">
            <div class="animate-float-medium inline-block mb-6 bg-white p-4 rounded-2xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Blijf op de hoogte van het laatste nieuws</h2>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Schrijf je in voor onze nieuwsbrief en ontvang automatisch een bericht wanneer er een nieuwe blog wordt gepubliceerd.</p>
        
        <div class="max-w-md mx-auto">
            <form id="newsletterForm" class="flex flex-col sm:flex-row gap-3">
                <input type="email" name="email" id="newsletter-email" placeholder="Je e-mailadres" required 
                       class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary">
                <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg transition-colors hover:bg-primary/90 focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Inschrijven
                </button>
            </form>
            <div id="newsletterMessage" class="mt-4 text-center hidden"></div>
        </div>
    </div>
</section>

<!-- Vervolg van de pagina... -->

<!-- Swiper JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialisatie van de blog hero slider -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero blog slider
    const heroSwiper = new Swiper('.hero-blog-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 800,
        effect: 'slide',
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        loop: true,
        grabCursor: true,
        pagination: {
            el: '.hero-swiper-pagination',
            clickable: true,
            bulletClass: 'inline-block w-1.5 h-1.5 mx-1 cursor-pointer transition-all duration-200 bg-white/30 hover:bg-white/70 rounded-full',
            bulletActiveClass: 'w-4 bg-[#c41e3a]'
        },
        navigation: {
            nextEl: '.hero-swiper-button-next',
            prevEl: '.hero-swiper-button-prev'
        },
        on: {
            autoplayTimeLeft(s, time, progress) {
                const progressBar = document.querySelector('.swiper-progress-bar');
                if (progressBar) {
                    progressBar.style.width = (1 - progress) * 100 + '%';
                }
            }
        }
    });
});
</script>

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
