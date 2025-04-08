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

// Data voor zetelverdeling peiling 4-4-2025
$peilingData = [
    [
        'partij' => 'PVV',
        'zetels' => [
            'peiling' => 29,
            'vorige' => 29,
            'tkvorigepeiling' => 32,
            'tk2023' => 37
        ],
        'color' => '#0078D7'
    ],
    [
        'partij' => 'GroenLinks/PvdA',
        'zetels' => [
            'peiling' => 29,
            'vorige' => 28,
            'tkvorigepeiling' => 24,
            'tk2023' => 25
        ],
        'color' => '#008800'
    ],
    [
        'partij' => 'VVD',
        'zetels' => [
            'peiling' => 25,
            'vorige' => 26,
            'tkvorigepeiling' => 21,
            'tk2023' => 24
        ],
        'color' => '#FF9900'
    ],
    [
        'partij' => 'CDA',
        'zetels' => [
            'peiling' => 19,
            'vorige' => 18,
            'tkvorigepeiling' => 17,
            'tk2023' => 5
        ],
        'color' => '#1E8449'
    ],
    [
        'partij' => 'D66',
        'zetels' => [
            'peiling' => 9,
            'vorige' => 9,
            'tkvorigepeiling' => 12,
            'tk2023' => 9
        ],
        'color' => '#00B13C'
    ],
    [
        'partij' => 'SP',
        'zetels' => [
            'peiling' => 7,
            'vorige' => 7,
            'tkvorigepeiling' => 8,
            'tk2023' => 5
        ],
        'color' => '#EE0000'
    ],
    [
        'partij' => 'FVD',
        'zetels' => [
            'peiling' => 5,
            'vorige' => 5,
            'tkvorigepeiling' => 5,
            'tk2023' => 3
        ],
        'color' => '#8B4513'
    ],
    [
        'partij' => 'PvdDieren',
        'zetels' => [
            'peiling' => 4,
            'vorige' => 4,
            'tkvorigepeiling' => 5,
            'tk2023' => 3
        ],
        'color' => '#006400'
    ],
    [
        'partij' => 'SGP',
        'zetels' => [
            'peiling' => 4,
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#ff7f00'
    ],
    [
        'partij' => 'DENK',
        'zetels' => [
            'peiling' => 4,
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#00BFFF'
    ],
    [
        'partij' => 'JA21',
        'zetels' => [
            'peiling' => 4,
            'vorige' => 4,
            'tkvorigepeiling' => 3,
            'tk2023' => 1
        ],
        'color' => '#4B0082'
    ],
    [
        'partij' => 'Volt',
        'zetels' => [
            'peiling' => 3,
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 2
        ],
        'color' => '#800080'
    ],
    [
        'partij' => 'ChristenUnie',
        'zetels' => [
            'peiling' => 3,
            'vorige' => 3,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#4682B4'
    ],
    [
        'partij' => 'BBB',
        'zetels' => [
            'peiling' => 3,
            'vorige' => 3,
            'tkvorigepeiling' => 4,
            'tk2023' => 7
        ],
        'color' => '#7CFC00'
    ],
    [
        'partij' => 'Nieuw Soc.Contr.',
        'zetels' => [
            'peiling' => 2,
            'vorige' => 2,
            'tkvorigepeiling' => 3,
            'tk2023' => 20
        ],
        'color' => '#4D7F78'
    ]
];

// Mogelijke coalities berekenen op basis van de peilingdata
$mogelijkeCoalities = [
    [
        'naam' => 'Links-progressief',
        'partijen' => ['GroenLinks/PvdA', 'D66', 'SP', 'PvdDieren', 'Volt'],
        'zetels' => 29 + 9 + 7 + 4 + 3
    ],
    [
        'naam' => 'Rechts-conservatief',
        'partijen' => ['PVV', 'VVD', 'BBB', 'JA21', 'SGP', 'FVD'],
        'zetels' => 29 + 25 + 3 + 4 + 4 + 5
    ],
    [
        'naam' => 'Centrum-breed',
        'partijen' => ['GroenLinks/PvdA', 'VVD', 'CDA', 'D66', 'ChristenUnie'],
        'zetels' => 29 + 25 + 19 + 9 + 3
    ],
    [
        'naam' => 'Huidige coalitie',
        'partijen' => ['PVV', 'VVD', 'BBB', 'Nieuw Soc.Contr.'],
        'zetels' => 29 + 25 + 3 + 2
    ]
];

// Sorteer coalities op aantal zetels (aflopend)
usort($mogelijkeCoalities, function($a, $b) {
    return $b['zetels'] - $a['zetels'];
});

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
        'title' => 'Palestijn in Gaza: "Israël blijft ons bombarderen"',
        'description' => 'Een Palestijnse inwoner van Gaza vertelt over de aanhoudende bombardementen door Israël en de humanitaire crisis die hieruit voortvloeit.',
        'url' => 'https://socialisme.nu/palestijn-in-gaza-israel-blijft-ons-bombarderen/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'centrum-links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'Tweede Kamer wil dat vuurwerkverbod niet al deze jaarwisseling van kracht wordt',
        'description' => 'Een meerderheid in de Tweede Kamer wil dat het landelijke vuurwerkverbod niet al deze jaarwisseling van kracht wordt. De Kamer vindt dat er meer tijd nodig is voor een goede voorbereiding.',
        'url' => 'https://www.nrc.nl/nieuws/2025/04/08/tweede-kamer-wil-dat-vuurwerkverbod-niet-al-deze-jaarwisseling-van-kracht-wordt-a4889224'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'centrum-links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'VS bouwen militaire macht op rond Iran, Trump wil zo deal afdwingen',
        'description' => 'De Verenigde Staten bouwen hun militaire aanwezigheid op in de regio rond Iran. President Trump hoopt hiermee Iran te dwingen tot nieuwe onderhandelingen.',
        'url' => 'https://www.trouw.nl/buitenland/vs-bouwen-militaire-macht-op-rond-iran-trump-wil-zo-deal-afdwingen~b5084af8/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'FVD',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Kamervragen Baudet over diversiteitsquota bij overheid: "Dit beleid is juist discriminerend"',
        'description' => 'Thierry Baudet heeft Kamervragen gesteld over het diversiteitsbeleid bij de overheid. Hij stelt dat quota en voorkeursbeleid juist discriminerend werken.',
        'url' => 'https://fvd.nl/nieuws/kamervragen-baudet-over-diversiteitsquota-bij-overheid-dit-beleid-is-juist-discriminerend'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'De Dagelijkse Standaard',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'Knettergek: CDAer Berendsen wil EU-burgers censureren om Trump te pesten',
        'description' => 'CDA-Europarlementariër Tom Berendsen wil dat de EU maatregelen neemt om de verspreiding van desinformatie door Trump-aanhangers tegen te gaan.',
        'url' => 'https://www.dagelijksestandaard.nl/politiek/knettergek-cdaer-berendsen-wil-eu-burgers-censureren-om-trump-te-pesten'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Nieuw Rechts',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'Coenradie zet zich af tegen eigen partij: "Ze is flink aan het schoppen"',
        'description' => 'Voormalig VVD-Kamerlid Coenradie uit kritiek op haar eigen partij. Ze vindt dat de partij te veel afstand neemt van haar traditionele standpunten.',
        'url' => 'https://nieuwrechts.nl/103648-coenradie-zet-zich-af-tegen-eigen-partij-ze-is-flink-aan-het-schoppen'
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
    
    /* Glow effect voor de actieve slide */
    .swiper-slide-active-custom {
        transform: scale(1.02);
        transition: all 0.5s ease;
        animation: subtle-pulse 3s infinite alternate;
    }
    
    @keyframes subtle-pulse {
        0% {
            box-shadow: 0 0 0 rgba(26, 86, 219, 0);
        }
        100% {
            box-shadow: 0 0 25px rgba(26, 86, 219, 0.3);
        }
    }
    
    /* Glassmorphism effect verbetering */
    .hero-blog-swiper .swiper-slide a {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .hero-blog-swiper .swiper-slide a:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-3px);
    }
    
    /* Verbetering voor bullets paginering */
    .hero-swiper-pagination .inline-block {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    /* Animatie voor de progress bar */
    .swiper-progress-bar {
        transition: width 0.1s linear;
    }
    
    /* Verbeterde hover effect voor navigatie knoppen */
    .hero-swiper-button-next:hover,
    .hero-swiper-button-prev:hover {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(26, 86, 219, 0.4);
    }

    /* Verbeterde hero styling */
    .hero-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #121928 0%, #1a56db 100%);
        padding: 4rem 0 6rem;
        isolation: isolate;
    }
    
    /* Moderne decoratieve elementen */
    .hero-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.07;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='white' fill-opacity='1' fill-rule='evenodd'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
    }
    
    .hero-accent {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1a56db, #7e3af2, #1a56db);
        animation: gradientShift 8s ease infinite;
        background-size: 200% 200%;
    }

    /* Moderne floating shapes */
    .hero-shape {
        position: absolute;
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        filter: blur(40px);
        z-index: 0;
        animation: shapeFloat 15s ease-in-out infinite alternate;
        opacity: 0.12;
    }
    
    .hero-shape-1 {
        background: #1a56db;
        width: 300px;
        height: 300px;
        top: -100px;
        right: 5%;
    }
    
    .hero-shape-2 {
        background: #7e3af2;
        width: 200px;
        height: 200px;
        bottom: -50px;
        left: 10%;
        animation-delay: 2s;
    }
    
    .hero-shape-3 {
        background: #1a56db;
        width: 150px;
        height: 150px;
        bottom: 20%;
        right: 15%;
        animation-delay: 4s;
    }

    /* Blog slider styling */
    .hero-blog-card {
        backdrop-filter: blur(10px);
        background: rgba(17, 24, 39, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hero-blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
    }
    
    .hero-blog-card-header {
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        backdrop-filter: blur(5px);
    }
    
    .hero-blog-card-icon {
        background: linear-gradient(135deg, rgba(26, 86, 219, 0.2), rgba(126, 58, 242, 0.2));
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Typewriter text effect */
    #typewriter {
        display: inline-block;
        transition: opacity 0.5s ease;
        position: relative;
        padding-right: 0.1em;
    }
    
    #typewriter::after {
        content: '';
        position: absolute;
        right: -0.1em;
        animation: blink 1s step-end infinite;
    }
    
    /* Swiper controls styling */
    .hero-swiper-pagination {
        display: flex;
        gap: 0.25rem;
    }
    
    .hero-swiper-pagination .swiper-pagination-bullet {
        width: 2rem;
        height: 0.25rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.3);
        opacity: 1;
        transition: all 0.3s ease;
    }
    
    .hero-swiper-pagination .swiper-pagination-bullet-active {
        background: linear-gradient(90deg, #1a56db, #7e3af2);
        width: 3rem;
    }
    
    .hero-swiper-button-next,
    .hero-swiper-button-prev {
        width: 3rem;
        height: 3rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .hero-swiper-button-next:hover,
    .hero-swiper-button-prev:hover {
        background: rgba(26, 86, 219, 0.3);
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    
    .hero-swiper-progress-bar {
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        width: 100%;
    }
    
    .hero-swiper-progress-bar .progress {
        height: 100%;
        background: linear-gradient(90deg, #1a56db, #7e3af2);
        width: 0;
        transition: width 0.3s ease;
    }
    
    /* CTA button styling */
    .hero-cta-button {
        background: white;
        color: #1a56db;
        border-radius: 0.75rem;
        font-weight: 600;
        padding: 0.875rem 1.75rem;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .hero-cta-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: linear-gradient(90deg, #1a56db, #7e3af2);
        transition: width 0.3s ease;
        z-index: -1;
    }
    
    .hero-cta-button:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .hero-cta-button:hover::before {
        width: 100%;
    }
    
    .hero-cta-button:hover svg {
        transform: translateX(4px);
        color: white;
    }
    
    .hero-cta-button svg {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    
    /* Animations */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    @keyframes shapeFloat {
        0% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0); }
    }
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
</style>

<main class="bg-gray-50 overflow-x-hidden">
    <!-- Hero Section - Volledig vernieuwde moderne versie -->
    <section class="hero-section">
        <!-- Decoratieve elementen -->
        <div class="hero-pattern"></div>
        <div class="hero-accent"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
        
        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
                <!-- Linker kolom: Welkomstekst en CTA -->
                <div class="w-full lg:w-1/2 text-white mb-10 lg:mb-0">
                    <div class="relative">
                        <!-- Hoofdtitel met moderne styling -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-white/90 to-white relative">
                                PolitiekPraat
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-white/30 rounded-full"></span>
                            </span>
                        </h1>
                        
                        <!-- Ondertitel met dynamische typewriter effect -->
                        <div class="text-lg sm:text-xl text-white/90 mb-8 max-w-lg leading-relaxed">
                            <span id="typewriter" class="inline-block"></span>
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
                        
                        <!-- Call-to-action knop met verbeterde styling -->
                        <a href="<?php echo URLROOT; ?>/blogs" class="hero-cta-button">
                            <span>Ontdek onze blogs</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Rechter kolom: Blog slider - Vernieuwde elegante versie -->
                <div class="w-full lg:w-5/12 mx-auto">
                    <div class="hero-blog-card">
                        <!-- Header met titel - Vernieuwd design -->
                        <div class="hero-blog-card-header relative flex items-center justify-between py-4 px-6">
                            <div class="flex items-center">
                                <div class="flex h-10 w-10 hero-blog-card-icon p-0.5 overflow-hidden mr-3">
                                    <div class="w-full h-full bg-gray-800/80 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-white tracking-wide">Uitgelichte Blogs</h3>
                                    <p class="text-xs text-white/60 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Actueel: <?php echo date('d-m-Y'); ?>
                                    </p>
                                </div>
                            </div>
                        </div><!-- Ondertitel met dynamische typewriter effect -->
                        
                        <!-- Blog swiper container - Verbeterde stijl en layout -->
                        <div class="hero-blog-swiper h-[360px] sm:h-[400px]">
                            <div class="swiper-wrapper">
                                <?php foreach($featured_blogs as $blog): ?>
                                <div class="swiper-slide p-4">
                                    <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                                       class="group block h-full bg-black/20 hover:bg-white/10 rounded-xl p-1 border border-white/5 hover:border-white/20 transition-all duration-300 overflow-hidden">
                                        <div class="relative h-full rounded-lg overflow-hidden flex flex-col">
                                            <!-- Afbeelding met overlay -->
                                            <div class="relative h-40 sm:h-44 rounded-t-lg overflow-hidden">
                                                <?php if($blog->image_path): ?>
                                                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                         alt="<?php echo htmlspecialchars($blog->title); ?>" 
                                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60"></div>
                                                <?php else: ?>
                                                    <div class="w-full h-full bg-gradient-to-br from-primary/70 to-secondary/70 flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M15 3v4m0 0v4m0-4h4m-4 0H7m15 5v5a2 2 0 01-2 2h-4"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60"></div>
                                                <?php endif; ?>
                                                
                                                <!-- Category badge - Eleganter -->
                                                <div class="absolute top-3 left-3 z-10">
                                                    <span class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-full shadow-lg backdrop-blur-sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Blog
                                                    </span>
                                                </div>
                                                
                                                <!-- Datum badge - Toegevoegd -->
                                                <div class="absolute top-3 right-3 z-10">
                                                    <span class="inline-flex items-center px-2 py-1 bg-black/50 text-white text-xs rounded-md backdrop-blur-sm border border-white/10">
                                                        <?php echo $blog->relative_date; ?>
                                                    </span>
                                                </div>
                                            </div>
                                                
                                            <!-- Content section - Verbeterd -->
                                            <div class="flex-grow p-4 flex flex-col justify-between bg-gradient-to-b from-gray-800/30 to-gray-900/30 rounded-b-lg">
                                                <div>
                                                    <h4 class="text-base font-semibold text-white mb-2 line-clamp-2 group-hover:text-white transition-colors duration-300">
                                                        <?php echo htmlspecialchars($blog->title); ?>
                                                    </h4>
                                                    
                                                    <p class="text-xs text-white/70 mb-3 line-clamp-2">
                                                        <?php echo htmlspecialchars($blog->summary); ?>
                                                    </p>
                                                </div>
                                                
                                                <div class="flex items-center justify-between mt-auto pt-3 border-t border-white/10">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center overflow-hidden">
                                                            <img src="https://media.licdn.com/dms/image/v2/D4E03AQH41qDaDSt19A/profile-displayphoto-shrink_800_800/B4EZWO8j20HgAg-/0/1741859995715?e=1749081600&v=beta&t=RsXjUDf5AAjzVEK7lMDS2InjE18FehpMDQB7JxICP64" 
                                                                 alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                                 class="w-full h-full object-cover">
                                                            </div>
                                                        <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($blog->author_name); ?></span>
                                                    </div>
                                                    
                                                    <span class="inline-flex items-center text-xs font-medium text-white group-hover:text-white transition-colors duration-300">
                                                        Lees meer
                                                        <svg class="w-3.5 h-3.5 ml-1.5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            
                        <!-- Navigatie knoppen voor Swiper -->
                        <div class="absolute top-1/2 -left-12 transform -translate-y-1/2 z-10 hero-swiper-button-prev opacity-0 lg:opacity-100 pointer-events-none lg:pointer-events-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="absolute top-1/2 -right-12 transform -translate-y-1/2 z-10 hero-swiper-button-next opacity-0 lg:opacity-100 pointer-events-none lg:pointer-events-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        
                        <!-- Footer met paginering en progressie -->
                        <div class="px-6 py-4 hero-blog-card-header">
                            <div class="flex items-center justify-between">
                                <div class="hero-swiper-progress-bar">
                                    <div class="progress" id="hero-swiper-progressbar"></div>
                                </div>
                                
                                <a href="<?php echo URLROOT; ?>/blogs" class="text-xs text-white/70 hover:text-white flex items-center transition-colors duration-300 ml-6">
                                    Alle blogs
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hero Blog Swiper
            const heroBlogSwiper = new Swiper('.hero-blog-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                grabCursor: true,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                navigation: {
                    nextEl: '.hero-swiper-button-next',
                    prevEl: '.hero-swiper-button-prev',
                },
                on: {
                    slideChange: function (swiper) {
                        // Update progress bar
                        const progress = ((swiper.realIndex + 1) / swiper.slides.length) * 100;
                        document.getElementById('hero-swiper-progressbar').style.width = progress + '%';
                    },
                    init: function(swiper) {
                        // Set initial progress
                        document.getElementById('hero-swiper-progressbar').style.width = (1 / swiper.slides.length) * 100 + '%';
                    }
                }
            });
        });
    </script>

    <!-- Peiling Section met opvallend design -->
    <section class="py-16 bg-white relative overflow-hidden -mt-1">
        <!-- Decoratieve elementen -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative">
            <div class="text-center mb-16 relative" data-aos="fade-up">
                <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">PEILINGEN</span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Actuele Peilingen</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Zetelverdeling volgens de recentste peiling van 4 april 2025</p>
                <p class="text-sm text-gray-500 mt-2">Bron: Peil.nl / Maurice de Hond</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Linker kolom: Zetelverdeling Tabel -->
                <div class="relative" data-aos="fade-right">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <h3 class="text-xl sm:text-2xl font-bold mb-4 text-gray-900">Zetelverdeling Tweede Kamer</h3>
                            
                            <!-- Verbeterde tabel met zetelverdelingen, responsief voor mobiel -->
                            <div class="overflow-visible">
                                <table class="w-full border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="py-2 px-1 sm:px-3 text-left font-semibold text-gray-700">Partijen</th>
                                            <th class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-700 whitespace-nowrap">
                                                <span class="hidden sm:inline">04-04-25</span>
                                                <span class="sm:hidden">4 Apr</span>
                                            </th>
                                            <th class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-700 whitespace-nowrap">
                                                <span class="hidden sm:inline">22-02-25</span>
                                                <span class="sm:hidden">22 Feb</span>
                                            </th>
                                            <th class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-700">
                                                <span class="hidden sm:inline">Verschil</span>
                                                <span class="sm:hidden">+/-</span>
                                            </th>
                                            <th class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-700">TK2023</th>
                                            <th class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-700">+/-</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($peilingData as $partij): 
                                            // Bereken veranderingen (nu direct vergeleken met tkvorigepeiling)
                                            $verandering = $partij['zetels']['peiling'] - $partij['zetels']['tkvorigepeiling'];
                                            $veranderingTK = $partij['zetels']['peiling'] - $partij['zetels']['tk2023'];
                                            
                                            // Bepaal de kleur voor de veranderingen
                                            $veranderingClass = $verandering > 0 ? 'bg-green-100 text-green-800' : 
                                                               ($verandering < 0 ? 'bg-red-100 text-red-800' : 
                                                               'bg-gray-100 text-gray-600');
                                            
                                            $veranderingTKClass = $veranderingTK > 0 ? 'bg-green-100 text-green-800' : 
                                                                ($veranderingTK < 0 ? 'bg-red-100 text-red-800' : 
                                                                'bg-gray-100 text-gray-600');
                                            
                                            // Teken veranderingssymbolen
                                            $veranderingSymbol = $verandering > 0 ? '+' . $verandering : 
                                                                ($verandering < 0 ? $verandering : '0');
                                            
                                            $veranderingTKSymbol = $veranderingTK > 0 ? '+' . $veranderingTK : 
                                                                  ($veranderingTK < 0 ? $veranderingTK : '0');
                                                                  
                                            // Maak een verkorte versie van de partijnaam voor mobiel
                                            $kortNaam = $partij['partij'];
                                            if (strpos($kortNaam, '/') !== false) {
                                                $delen = explode('/', $kortNaam);
                                                $kortNaam = $delen[0];
                                            } else if (strlen($kortNaam) > 10) {
                                                // Afkorten voor lange namen
                                                $kortNaam = preg_replace('/[aeiou]/i', '', $kortNaam); // Verwijder klinkers
                                                if (strlen($kortNaam) > 6) {
                                                    $kortNaam = substr($kortNaam, 0, 6);
                                                }
                                            }
                                        ?>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                            <td class="py-2 px-1 sm:px-3">
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full mr-1 sm:mr-2" style="background-color: <?php echo $partij['color']; ?>"></div>
                                                    <span class="hidden sm:inline font-medium text-gray-900"><?php echo $partij['partij']; ?></span>
                                                    <span class="sm:hidden font-medium text-gray-900"><?php echo $kortNaam; ?></span>
                                                </div>
                                            </td>
                                            <td class="py-2 px-1 sm:px-3 text-center font-semibold text-gray-900"><?php echo $partij['zetels']['peiling']; ?></td>
                                            <td class="py-2 px-1 sm:px-3 text-center text-gray-700"><?php echo $partij['zetels']['tkvorigepeiling']; ?></td>
                                            <td class="py-2 px-1 sm:px-3 text-center">
                                                <span class="inline-block px-1 sm:px-2 py-0.5 sm:py-1 rounded-full <?php echo $veranderingClass; ?>">
                                                    <?php echo $veranderingSymbol; ?>
                                                </span>
                                            </td>
                                            <td class="py-2 px-1 sm:px-3 text-center text-gray-700"><?php echo $partij['zetels']['tk2023']; ?></td>
                                            <td class="py-2 px-1 sm:px-3 text-center">
                                                <span class="inline-block px-1 sm:px-2 py-0.5 sm:py-1 rounded-full <?php echo $veranderingTKClass; ?>">
                                                    <?php echo $veranderingTKSymbol; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 text-xs text-gray-500 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="mb-2 sm:mb-0">
                                    <span class="hidden sm:inline">Peilingdatum: 4 april 2025</span>
                                    <span class="sm:hidden">Peiling: 4 apr 2025</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-green-100 rounded-full mr-1"></span>
                                    <span class="mr-3">Winst</span>
                                    <span class="inline-block w-3 h-3 bg-red-100 rounded-full mr-1"></span>
                                    <span>Verlies</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rechter kolom: Visualisaties en Coalities -->
                <div class="space-y-8" data-aos="fade-left">
                    <!-- Donut Chart Visualisatie -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <h3 class="text-xl sm:text-2xl font-bold mb-6 text-gray-900">Zetelverdeling Visualisatie</h3>
                            
                            <div class="flex justify-center mb-8">
                                <!-- SVG Donut Chart -->
                                <div class="relative w-56 sm:w-64 h-56 sm:h-64">
                                    <?php
                                    $totaalZetels = 150; // Totaal aantal zetels in de Tweede Kamer
                                    $cumulatiefPercentage = 0;
                                    $donutSegmenten = [];
                                    
                                    // Bereken het aandeel per partij en maak donutsegmenten
                                    foreach($peilingData as $partij) {
                                        $percentage = ($partij['zetels']['peiling'] / $totaalZetels) * 100;
                                        $startAngle = $cumulatiefPercentage * 3.6; // 3.6 graden per 1%
                                        $endAngle = ($cumulatiefPercentage + $percentage) * 3.6;
                                        
                                        // Bereken de coördinaten voor het pad
                                        $x1 = 32 + 28 * cos(deg2rad($startAngle));
                                        $y1 = 32 + 28 * sin(deg2rad($startAngle));
                                        $x2 = 32 + 28 * cos(deg2rad($endAngle));
                                        $y2 = 32 + 28 * sin(deg2rad($endAngle));
                                        
                                        // Bepaal of het pad een grote boog moet zijn (voor segmenten > 180 graden)
                                        $largeArcFlag = $percentage > 50 ? 1 : 0;
                                        
                                        // Formatteer het SVG path
                                        $path = "M 32 32 L $x1 $y1 A 28 28 0 $largeArcFlag 1 $x2 $y2 Z";
                                        
                                        $donutSegmenten[] = [
                                            'path' => $path,
                                            'color' => $partij['color'],
                                            'partij' => $partij['partij'],
                                            'zetels' => $partij['zetels']['peiling']
                                        ];
                                        
                                        $cumulatiefPercentage += $percentage;
                                    }
                                    ?>
                                    
                                    <svg width="100%" height="100%" viewBox="0 0 64 64">
                                        <?php 
                                        // Teken eerst een cirkelvormige achtergrond
                                        echo '<circle cx="32" cy="32" r="28" fill="white" stroke="#e5e7eb" stroke-width="1" />';
                                        
                                        // Teken alle segmenten
                                        foreach($donutSegmenten as $segment) {
                                            echo '<path d="' . $segment['path'] . '" fill="' . $segment['color'] . '" stroke="white" stroke-width="0.5" class="transition-all duration-300 hover:opacity-80 cursor-pointer" />';
                                        }
                                        
                                        // Teken een witte cirkel in het midden om er een donut van te maken
                                        echo '<circle cx="32" cy="32" r="18" fill="white" />';
                                        ?>
                                        
                                        <!-- Middentekst met totaal aantal zetels -->
                                        <text x="32" y="30" text-anchor="middle" font-size="6" font-weight="bold" fill="#1a365d">Totaal</text>
                                        <text x="32" y="38" text-anchor="middle" font-size="8" font-weight="bold" fill="#1a365d">150</text>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Legenda in grid format -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2">
                                <?php
                                // Toon alleen de top 9 partijen in de legenda
                                $topPartijen = array_slice($peilingData, 0, 9);
                                foreach($topPartijen as $partij):
                                ?>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo $partij['color']; ?>"></div>
                                    <span class="text-xs text-gray-700">
                                        <?php echo $partij['partij']; ?> (<?php echo $partij['zetels']['peiling']; ?>)
                                    </span>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php if(count($peilingData) > 9): ?>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2 bg-gray-300"></div>
                                    <span class="text-xs text-gray-700">
                                        Overige (<?php 
                                        $overige = 0;
                                        for($i = 9; $i < count($peilingData); $i++) {
                                            $overige += $peilingData[$i]['zetels']['peiling'];
                                        }
                                        echo $overige;
                                        ?>)
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mogelijke Coalities -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <h3 class="text-xl sm:text-2xl font-bold mb-6 text-gray-900">Mogelijke Coalities</h3>
                            
                            <div class="space-y-6">
                                <?php foreach($mogelijkeCoalities as $index => $coalitie): 
                                    // Bepaal of het een meerderheid is
                                    $isMeerderheid = $coalitie['zetels'] >= 76;
                                    $meerderheidClass = $isMeerderheid ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200';
                                    $progressClass = $isMeerderheid ? 'bg-green-500' : 'bg-gray-400';
                                    $percentage = ($coalitie['zetels'] / 150) * 100;
                                ?>
                                <div class="border <?php echo $meerderheidClass; ?> rounded-xl p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2 sm:gap-0">
                                        <h4 class="font-semibold text-gray-900"><?php echo $coalitie['naam']; ?></h4>
                                        <div class="flex items-center">
                                            <span class="font-semibold text-gray-900"><?php echo $coalitie['zetels']; ?></span>
                                            <span class="text-xs text-gray-500 ml-1">zetels</span>
                                            <?php if($isMeerderheid): ?>
                                            <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Meerderheid</span>
                                            <?php else: ?>
                                            <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Geen meerd.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Voortgangsbalk voor aantal zetels -->
                                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden mb-3">
                                        <div class="h-full <?php echo $progressClass; ?> rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    
                                    <!-- Partijen in coalitie -->
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach($coalitie['partijen'] as $partijNaam): 
                                            // Zoek de kleur van de partij
                                            $partijKleur = '#888888'; // Standaardkleur
                                            foreach($peilingData as $partij) {
                                                if($partij['partij'] === $partijNaam) {
                                                    $partijKleur = $partij['color'];
                                                    break;
                                                }
                                            }
                                            
                                            // Maak verkorte naam voor mobiel
                                            $kortPartyNaam = $partijNaam;
                                            if (strpos($kortPartyNaam, '/') !== false) {
                                                $delen = explode('/', $kortPartyNaam);
                                                $kortPartyNaam = $delen[0];
                                            } else if (strlen($kortPartyNaam) > 8) {
                                                $kortPartyNaam = preg_replace('/[aeiou]/i', '', $kortPartyNaam);
                                                if (strlen($kortPartyNaam) > 6) {
                                                    $kortPartyNaam = substr($kortPartyNaam, 0, 6);
                                                }
                                            }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium" 
                                              style="background-color: <?php echo $partijKleur; ?>20; color: <?php echo $partijKleur; ?>;">
                                            <span class="w-2 h-2 rounded-full mr-1" style="background-color: <?php echo $partijKleur; ?>;"></span>
                                            <span class="hidden sm:inline"><?php echo $partijNaam; ?></span>
                                            <span class="sm:hidden"><?php echo $kortPartyNaam; ?></span>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Uitgelichte Nieuwste Blog Section -->
    <section class="py-16 bg-white relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50 via-white to-gray-50"></div>
        <div class="absolute top-40 right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-20 w-72 h-72 bg-secondary/5 rounded-full blur-3xl"></div>
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
            <div class="mb-20 relative">
                <!-- Decoratieve elementen -->
                <div class="absolute -top-20 left-0 w-120 h-120 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-120 h-120 bg-secondary/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-green-400/5 rounded-full blur-2xl"></div>
                <div class="absolute bottom-1/2 right-1/3 w-48 h-48 bg-yellow-400/5 rounded-full blur-xl"></div>

                <div class="text-center mb-20 relative" data-aos="fade-up" data-aos-once="true">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-60 select-none">BLOGS</span>
                    <h2 class="text-5xl font-extrabold text-gray-900 mb-5 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Laatste Blogs</span>
                    </h2>
                    <div class="w-32 h-1.5 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-6"></div>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">Ontdek mijn meest recente politieke analyses en inzichten</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <?php foreach($latest_blogs as $index => $blog): ?>
                        <article class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-gray-100" 
                                data-aos="fade-up" 
                                data-aos-delay="<?php echo $index * 100; ?>"
                                data-aos-duration="800"
                                data-aos-easing="ease-out-cubic"
                                data-aos-once="true">
                            <!-- Decoratieve hover accent lijn -->
                            <div class="absolute inset-0 top-auto h-1 bg-gradient-to-r from-primary to-secondary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 z-10"></div>
                            
                            <?php 
                            // Check of de blog minder dan 12 uur oud is
                            $published_time = strtotime($blog->published_at);
                            $twelve_hours_ago = time() - (12 * 3600); // 12 uur in seconden
                            
                            if ($published_time > $twelve_hours_ago): 
                            ?>
                                <!-- Nieuw Badge voor recent geplaatste blogs -->
                                <div class="absolute top-4 right-4 z-20">
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-sm font-bold shadow-lg">
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
                                    <div class="relative h-52 overflow-hidden">
                                        <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                             alt="<?php echo htmlspecialchars($blog->title); ?>"
                                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                <?php else: ?>
                                    <!-- Fallback voor blogs zonder afbeelding -->
                                    <div class="h-40 bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <div class="p-7">
                                    <!-- Auteur en datum info met verbeterd design -->
                                    <div class="flex items-center justify-between mb-5">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center overflow-hidden">
                                                <img src="https://media.licdn.com/dms/image/v2/D4E03AQH41qDaDSt19A/profile-displayphoto-shrink_800_800/B4EZWO8j20HgAg-/0/1741859995715?e=1749081600&v=beta&t=RsXjUDf5AAjzVEK7lMDS2InjE18FehpMDQB7JxICP64" 
                                                     alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                     class="w-full h-full object-cover">
                                                </div>
                                            <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($blog->author_name); ?></span>
                                        </div>
                                        
                                        <div class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo getRelativeTime($blog->published_at); ?>
                                        </div>
                                    </div>

                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        <?php echo htmlspecialchars($blog->title); ?>
                                    </h2>

                                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                        <?php echo htmlspecialchars($blog->summary); ?>
                                    </p>

                                    <!-- Actieknop met verbeterd ontwerp -->
                                    <div class="flex items-center justify-between pt-2">
                                        <div class="inline-flex items-center px-4 py-2 bg-primary/5 hover:bg-primary/10 text-primary font-semibold rounded-lg transition-colors duration-300">
                                            <span>Lees meer</span>
                                            <svg class="w-4 h-4 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Leestijd indicatie vervangen door likes -->
                                        <div class="flex items-center text-gray-400 text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span>
                                                <?php 
                                                    // Toon het aantal likes uit de database
                                                    echo (isset($blog->likes) && $blog->likes > 0) ? $blog->likes : '0';
                                                    echo ' likes';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Decoratieve hoekelementen -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-10 -translate-y-10 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700"></div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- CTA Button met verbeterde styling -->
                <div class="text-center mt-20" data-aos="zoom-in" data-aos-delay="300" data-aos-once="true">
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-xl hover:opacity-95 transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl group relative overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            <span class="mr-1">Bekijk alle blogs</span>
                            <svg class="w-5 h-5 ml-2 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                            <span class="absolute inset-0 -z-10 bg-white opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Laatste Nieuws -->
            <div class="relative py-24" data-aos="fade-up" data-aos-once="true">
                <!-- Decoratieve elementen -->
                <div class="absolute -top-20 right-0 w-120 h-120 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-0 w-120 h-120 bg-secondary/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-blue-400/5 rounded-full blur-2xl"></div>
                <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-red-400/5 rounded-full blur-xl"></div>

                <div class="text-center mb-20 relative" data-aos="fade-up" data-aos-once="true">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-60 select-none">NIEUWS</span>
                    <h2 class="text-5xl font-extrabold text-gray-900 mb-5 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Laatste Politiek Nieuws</span>
                    </h2>
                    <div class="w-32 h-1.5 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-6"></div>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">Blijf op de hoogte van de laatste ontwikkelingen in de Nederlandse politiek door beide perspectieven te vergelijken</p>
                </div>

                <!-- Laatste Nieuws Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 relative">
                    <!-- Links georiënteerde bronnen -->
                    <div class="space-y-10" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                        <div class="flex items-center justify-center lg:justify-start">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-5 py-3 rounded-xl shadow-md inline-flex items-center">
                                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                                <h3 class="text-xl font-bold text-white">Progressieve Media</h3>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <?php
                            $links_news = array_filter($latest_news, function($news) {
                                return $news['orientation'] === 'links';
                            });
                            foreach($links_news as $index => $news):
                            ?>
                                <article class="group bg-white border border-blue-100 rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl relative"
                                        data-aos="fade-up" 
                                        data-aos-delay="<?php echo $index * 100; ?>"
                                        data-aos-duration="800"
                                        data-aos-once="true">
                                    <!-- Accent border -->
                                    <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-blue-600 transform origin-left scale-y-0 group-hover:scale-y-100 transition-transform duration-300"></div>
                                    
                                    <div class="relative p-7">
                                        <!-- News Source & Date -->
                                        <div class="flex items-center justify-between mb-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center overflow-hidden group-hover:bg-blue-200 transition-all duration-300">
                                                        <span class="text-blue-600 font-bold text-lg"><?php echo substr($news['source'], 0, 2); ?></span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900"><?php echo $news['source']; ?></p>
                                                    <span class="text-xs px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full font-medium"><?php echo $news['bias']; ?></span>
                                                </div>
                                            </div>
                                            <div class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium">
                                                <?php echo getRelativeTime($news['publishedAt']); ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Rest van de artikel content -->
                                        <div class="space-y-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                                <?php echo $news['title']; ?>
                                            </h3>
                                            <p class="text-gray-600 line-clamp-3 leading-relaxed">
                                                <?php echo $news['description']; ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Article Footer -->
                                        <div class="mt-7 flex items-center justify-between">
                                            <a href="<?php echo $news['url']; ?>" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold rounded-lg transition-colors duration-300">
                                                <span>Lees artikel</span>
                                                <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                            
                                            <div class="flex items-center text-gray-400 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Progressief perspectief</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Rechts georiënteerde bronnen -->
                    <div class="space-y-10" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <div class="flex items-center justify-center lg:justify-start">
                            <div class="bg-gradient-to-r from-red-500 to-red-700 px-5 py-3 rounded-xl shadow-md inline-flex items-center">
                                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <h3 class="text-xl font-bold text-white">Conservatieve Media</h3>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <?php
                            $rechts_news = array_filter($latest_news, function($news) {
                                return $news['orientation'] === 'rechts';
                            });
                            foreach($rechts_news as $index => $news):
                            ?>
                                <article class="group bg-white border border-red-100 rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl relative"
                                        data-aos="fade-up" 
                                        data-aos-delay="<?php echo $index * 100; ?>"
                                        data-aos-duration="800"
                                        data-aos-once="true">
                                    <!-- Accent border -->
                                    <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-red-400 to-red-600 transform origin-right scale-y-0 group-hover:scale-y-100 transition-transform duration-300"></div>
                                    
                                    <div class="relative p-7">
                                        <!-- News Source & Date -->
                                        <div class="flex items-center justify-between mb-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center overflow-hidden group-hover:bg-red-200 transition-all duration-300">
                                                        <span class="text-red-600 font-bold text-lg"><?php echo substr($news['source'], 0, 2); ?></span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900"><?php echo $news['source']; ?></p>
                                                    <span class="text-xs px-2.5 py-1 bg-red-100 text-red-700 rounded-full font-medium"><?php echo $news['bias']; ?></span>
                                                </div>
                                            </div>
                                            <div class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium">
                                                <?php echo getRelativeTime($news['publishedAt']); ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Rest van de artikel content -->
                                        <div class="space-y-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2">
                                                <?php echo $news['title']; ?>
                                            </h3>
                                            <p class="text-gray-600 line-clamp-3 leading-relaxed">
                                                <?php echo $news['description']; ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Article Footer -->
                                        <div class="mt-7 flex items-center justify-between">
                                            <a href="<?php echo $news['url']; ?>" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg transition-colors duration-300">
                                                <span>Lees artikel</span>
                                                <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                            
                                            <div class="flex items-center text-gray-400 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Conservatief perspectief</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- CTA Button met verbeterde styling -->
                <div class="text-center mt-20">
                    <a href="<?php echo URLROOT; ?>/nieuws" 
                       class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-xl hover:opacity-95 transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl group relative overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            <span class="mr-1">Bekijk al het nieuws</span>
                            <svg class="w-5 h-5 ml-2 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                            <span class="absolute inset-0 -z-10 bg-white opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Actuele Thema's Grid -->
    <section class="py-24 relative overflow-hidden">
        <!-- Decoratieve elementen -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50 via-white to-gray-50"></div>
        <div class="absolute -top-40 -left-40 w-120 h-120 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-120 h-120 bg-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/3 w-64 h-64 bg-purple-400/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-1/3 right-1/3 w-48 h-48 bg-yellow-400/5 rounded-full blur-xl"></div>

        <div class="container mx-auto px-4 relative">
            <div class="text-center mb-20 relative" data-aos="fade-up">
                <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-60 select-none">THEMA'S</span>
                <h2 class="text-5xl font-extrabold text-gray-900 mb-5 relative">
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Actuele Thema's</span>
                </h2>
                <div class="w-32 h-1.5 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-6"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">Ontdek de belangrijkste politieke onderwerpen die Nederland bezighouden</p>
            </div>

            <!-- Thema's grid met verbeterd design -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php foreach($actuele_themas as $index => $thema): ?>
                    <div class="group relative" 
                         data-aos="zoom-in" 
                         data-aos-delay="<?php echo $index * 100; ?>"
                         data-aos-duration="800"
                         data-aos-once="true">
                        <!-- Card Background met subtiele schaduw & glow -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-2xl opacity-0 blur-xl group-hover:opacity-70 transition-all duration-500 transform group-hover:scale-105"></div>
                        
                        <!-- Hoofdkaart met verbeterd design -->
                        <div class="relative bg-white rounded-2xl shadow-lg transform transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl overflow-hidden border border-gray-100 h-full">
                            <!-- Decoratieve hover accent lijn -->
                            <div class="absolute inset-0 top-auto h-1 bg-gradient-to-r from-primary to-secondary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                            
                            <!-- Decoratief patroon -->
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(0,0,0,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>

                            <!-- Inhoud container met verbeterde padding -->
                            <div class="relative p-8">
                                <!-- Icon container met verbeterd ontwerp -->
                                <div class="mb-6 flex items-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl flex items-center justify-center transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-md group-hover:from-primary/20 group-hover:to-secondary/20">
                                        <span class="text-4xl"><?php echo $thema['icon']; ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
                                            Politiek thema
                                        </div>
                                    </div>
                                </div>

                                <!-- Content met verbeterde typografie en spacing -->
                                <div class="space-y-5">
                                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary transition-colors duration-300">
                                        <?php echo $thema['title']; ?>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-3 leading-relaxed">
                                        <?php echo $thema['description']; ?>
                                    </p>

                                    <!-- Stats of relevante info -->
                                    <div class="flex items-center space-x-6 pt-2">
                                        <div class="flex items-center text-gray-500 text-sm">
                                            <svg class="w-4 h-4 mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Recent geüpdatet</span>
                                        </div>
                                        <div class="flex items-center text-gray-500 text-sm">
                                            <svg class="w-4 h-4 mr-1 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <span>Actueel</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Actieknop met verbeterd ontwerp -->
                                    <div class="pt-2">
                                        <a href="<?php echo URLROOT; ?>/thema/<?php echo strtolower(str_replace(' ', '-', $thema['title'])); ?>" 
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary/5 to-secondary/5 hover:from-primary/10 hover:to-secondary/10 text-primary font-semibold rounded-lg transition-all duration-300 mt-2 group-hover:shadow-sm">
                                            <span>Ontdek meer</span>
                                            <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Decoratieve hoek elementen -->
                                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-12 -translate-y-12 group-hover:translate-x-8 group-hover:-translate-y-8 transition-transform duration-700"></div>
                                <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-primary/5 to-secondary/5 transform rotate-45 -translate-x-8 translate-y-8 group-hover:-translate-x-6 group-hover:translate-y-6 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTA Button met verbeterde styling -->
            <div class="text-center mt-20" data-aos="fade-up">
                <a href="<?php echo URLROOT; ?>/themas" 
                   class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-xl hover:opacity-95 transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl group relative overflow-hidden">
                    <span class="relative z-10 flex items-center">
                        <span class="mr-1">Bekijk alle thema's</span>
                        <svg class="w-5 h-5 ml-2 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                        <span class="absolute inset-0 -z-10 bg-white opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                    </span>
                </a>
            </div>
        </div>
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
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
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
            bulletClass: 'inline-block w-2 h-2 mx-1 cursor-pointer transition-all duration-300 bg-white/30 hover:bg-white/70 rounded-full',
            bulletActiveClass: 'w-6 bg-gradient-to-r from-primary to-secondary'
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
            },
            init: function() {
                // Voeg extra klasse toe aan de actieve slide
                this.slides.forEach((slide) => {
                    slide.classList.remove('swiper-slide-active-custom');
                });
                this.slides[this.activeIndex].classList.add('swiper-slide-active-custom');
            },
            slideChange: function() {
                // Update de actieve slide klasse
                this.slides.forEach((slide) => {
                    slide.classList.remove('swiper-slide-active-custom');
                });
                this.slides[this.activeIndex].classList.add('swiper-slide-active-custom');
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
