<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalDataAPI.php';
require_once 'includes/PollAPI.php';

// Helper functie om Markdown syntax te strippen
function stripMarkdown($text) {
    if (empty($text)) {
        return $text;
    }
    
    // Strip verschillende Markdown elementen
    $text = preg_replace('/^#{1,6}\s+/m', '', $text); // Headers (# ## ###)
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Bold **text**
    $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Italic *text*
    $text = preg_replace('/`(.*?)`/', '$1', $text); // Inline code `code`
    $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text); // Links [text](url)
    $text = preg_replace('/^\s*[-*+]\s+/m', '', $text); // Unordered lists
    $text = preg_replace('/^\s*\d+\.\s+/m', '', $text); // Ordered lists
    $text = preg_replace('/^>\s+/m', '', $text); // Blockquotes
    $text = preg_replace('/^\s*```.*?```\s*$/ms', '', $text); // Code blocks
    $text = preg_replace('/^\s*---+\s*$/m', '', $text); // Horizontal rules
    
    // Verwijder extra witruimte
    $text = preg_replace('/\n\s*\n/', "\n", $text);
    $text = trim($text);
    
    return $text;
}

// PERFORMANCE TODO: Implement server-side caching (e.g., Redis, Memcached) for database queries and API responses to significantly improve TTFB (Time To First Byte).
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

// Data voor zetelverdeling peiling 7-6-2025 (Peil.nl)
$peilingData = [
    [
        'partij' => 'PVV',
        'zetels' => [
            'peiling' => 31, 
            'vorige' => 31,
            'tkvorigepeiling' => 28,
            'tk2023' => 37
        ],
        'color' => '#0078D7'
    ],
    [
        'partij' => 'GL/PvdA',
        'zetels' => [
            'peiling' => 30, 
            'vorige' => 30,
            'tkvorigepeiling' => 29,
            'tk2023' => 25
        ],
        'color' => '#008800'
    ],
    [
        'partij' => 'VVD',
        'zetels' => [
            'peiling' => 24, 
            'vorige' => 25,
            'tkvorigepeiling' => 26,
            'tk2023' => 24
        ],
        'color' => '#FF9900'
    ],
    [
        'partij' => 'CDA',
        'zetels' => [
            'peiling' => 19, 
            'vorige' => 18,
            'tkvorigepeiling' => 19,
            'tk2023' => 5
        ],
        'color' => '#1E8449'
    ],
    [
        'partij' => 'D66',
        'zetels' => [
            'peiling' => 8, 
            'vorige' => 8,
            'tkvorigepeiling' => 8,
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
        'partij' => 'JA21',
        'zetels' => [
            'peiling' => 5, 
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 1
        ],
        'color' => '#4B0082'
    ],
    [
        'partij' => 'FVD',
        'zetels' => [
            'peiling' => 4, 
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
            'tkvorigepeiling' => 4,
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
        'partij' => 'Volt',
        'zetels' => [
            'peiling' => 4, 
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
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#4682B4'
    ],
    [
        'partij' => 'BBB',
        'zetels' => [
            'peiling' => 2, 
            'vorige' => 2,
            'tkvorigepeiling' => 3,
            'tk2023' => 7
        ],
        'color' => '#7CFC00'
    ],
    [
        'partij' => 'NSC',
        'zetels' => [
            'peiling' => 1, 
            'vorige' => 1,
            'tkvorigepeiling' => 1,
            'tk2023' => 20
        ],
        'color' => '#4D7F78'
    ]
];

// Mogelijke coalities berekenen op basis van de bijgewerkte peilingdata
$mogelijkeCoalities = [
    [
        'naam' => 'Links-progressief',
        'partijen' => ['GL/PvdA', 'D66', 'SP', 'PvdDieren', 'Volt'],
        'zetels' => 30 + 8 + 7 + 4 + 4 // 53 zetels
    ],
    [
        'naam' => 'Rechts-conservatief',
        'partijen' => ['PVV', 'VVD', 'BBB', 'JA21', 'SGP', 'FVD'],
        'zetels' => 31 + 24 + 2 + 5 + 4 + 4 // 70 zetels
    ],
    [
        'naam' => 'Centrum-breed',
        'partijen' => ['GL/PvdA', 'VVD', 'CDA', 'D66', 'ChristenUnie'],
        'zetels' => 30 + 24 + 19 + 8 + 3 // 84 zetels
    ],
    [
        'naam' => 'Huidige coalitie',
        'partijen' => ['PVV', 'VVD', 'BBB', 'NSC'],
        'zetels' => 31 + 24 + 2 + 1 // 58 zetels
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
    // Strip Markdown van titel en summary
    $blog->title = stripMarkdown($blog->title);
    $blog->summary = stripMarkdown($blog->summary);
}
foreach ($featured_blogs as $blog) {
    $blog->relative_date = getRelativeTime($blog->published_at);
    // Strip Markdown van titel en summary
    $blog->title = stripMarkdown($blog->title);
    $blog->summary = stripMarkdown($blog->summary);
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
// PERFORMANCE TODO: Overweeg API-calls asynchroon te maken (bv. met JavaScript na het laden van de pagina) of te cachen voor betere laadtijden.
$latest_news = [
    [
        'orientation' => 'links',
        'source' => 'Socialisme.nu',
        'bias' => 'links', 
        'publishedAt' => date('Y-m-d H:i:s'), // Vandaag 08:30
        'title' => 'Verzet tegen kabinet groeit, maar gevaar van extreemrechts ook',
        'description' => 'Het verzet tegen het kabinet groeit, maar tegelijkertijd neemt ook het gevaar van extreemrechts toe. Een analyse van de huidige politieke situatie en de groeiende polarisatie in Nederland.',
        'url' => 'https://socialisme.nu/verzet-tegen-kabinet-groeit-maar-gevaar-van-extreemrechts-ook/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'De meeste plannen van kabinet Schoof stuitten op juridische bezwaren en praktische problemen',
        'description' => 'Een analyse van de plannen van het kabinet Schoof laat zien dat veel voorstellen vastliepen op juridische obstakels en uitvoeringsproblemen. Dit heeft geleid tot vertraging en herziening van belangrijke beleidsvoornemens.',
        'url' => 'https://www.nrc.nl/nieuws/2025/06/03/de-meeste-plannen-van-kabinet-schoof-stuitten-op-juridische-bezwaren-en-praktische-problemen-a4895665'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'VVD wil rechts beleid blijven voeren, maar heeft de oppositie de komende tijd hard nodig',
        'description' => 'De VVD wil vasthouden aan een rechtse koers, maar zal voor het uitvoeren van beleid steun nodig hebben van de oppositie. Dit zorgt voor een complexe politieke situatie waarin compromissen onvermijdelijk zijn.',
        'url' => 'https://www.trouw.nl/politiek/vvd-wil-rechts-beleid-blijven-voeren-maar-heeft-de-oppositie-de-komende-tijd-hard-nodig~b7f31232/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'FVD',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Reactie FVD op val kabinet',
        'description' => 'Forum voor Democratie reageert op de val van het kabinet en roept op tot nieuwe verkiezingen om het vertrouwen in de politiek te herstellen.',
        'url' => 'https://fvd.nl/nieuws/reactie-fvd-op-val-kabinet'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'De Dagelijkse Standaard',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'Wilders krijgt hoon na breuk: "Bokito" en "verrader", maar blijft strijdbaar',
        'description' => 'Geert Wilders krijgt veel kritiek na het mislukken van de formatie, maar laat zich niet uit het veld slaan. De PVV-leider wordt onder andere uitgemaakt voor "Bokito" en "verrader".',
        'url' => 'https://www.dagelijksestandaard.nl/politiek/wilders-krijgt-hoon-na-breuk-bokito-en-verrader-maar-blijft-strijdbaar'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Nieuw Rechts', 
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'Premier Schoof meldt officiële val van kabinet: weg vrij voor nieuwe verkiezingen',
        'description' => 'Premier Schoof heeft zojuist de officiële val van het kabinet bekendgemaakt. Dit betekent dat er nieuwe verkiezingen zullen komen.',
        'url' => 'https://nieuwrechts.nl/104641-premier-schoof-meldt-officile-val-van-kabinet-weg-vrij-voor-nieuwe-verkiezingen'
    ]
];

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

?>
<!-- Link to external CSS file with cache busting -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/home.css?v=<?php echo filemtime(__DIR__ . '/../public/css/home.css'); ?>">
<?php
require_once 'views/templates/header.php';
?>

<main class="bg-gray-50 overflow-x-hidden">
    <!-- Hero Section - Volledig responsive versie -->
    <section class="new-hero-section bg-gray-50 font-sans">
        <div class="absolute inset-0 z-0 opacity-50">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%22100%22%20height%3D%22100%22%20viewBox%3D%220%200%20100%20100%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M11%2018c3.866%200%207-3.134%207-7s-3.134-7-7-7-7%203.134-7%207%203.134%207%207%207zm48%2025c3.866%200%207-3.134%207-7s-3.134-7-7-7-7%203.134-7%207%203.134%207%207%207zM%2073%2077c3.866%200%207-3.134%207-7s-3.134-7-7-7-7%203.134-7%207%203.134%207%207%207z%22%20fill%3D%22%23e0e7ff%22%2F%3E%3C%2Fsvg%3E')] bg-repeat"></div>
        </div>
        
        <!-- Floating Partij Logo's -->
        <div class="absolute inset-0 z-5 pointer-events-none overflow-hidden">
            <?php
            // Database van partij logo's
            $partyLogos = [
                'PVV' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
                'VVD' => 'https://logo.clearbit.com/vvd.nl',
                'NSC' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
                'BBB' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
                'GL-PvdA' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
                'D66' => 'https://logo.clearbit.com/d66.nl',
                'SP' => 'https://logo.clearbit.com/sp.nl',
                'PvdD' => 'https://logo.clearbit.com/partijvoordedieren.nl',
                'CDA' => 'https://logo.clearbit.com/cda.nl',
                'JA21' => 'https://logo.clearbit.com/ja21.nl',
                'SGP' => 'https://logo.clearbit.com/sgp.nl',
                'FvD' => 'https://logo.clearbit.com/fvd.nl',
                'DENK' => 'https://logo.clearbit.com/bewegingdenk.nl',
                'Volt' => 'https://logo.clearbit.com/voltnederland.org'
            ];
            
            // Selecteer de belangrijkste partijen voor de floating logos
            $floatingPartijen = [
                ['naam' => 'VVD', 'kleur' => '#FF9900', 'logo' => $partyLogos['VVD'], 'positie' => 'top-20 left-4 sm:left-16', 'delay' => '0s', 'duration' => '15s'],
                ['naam' => 'PVV', 'kleur' => '#0078D7', 'logo' => $partyLogos['PVV'], 'positie' => 'top-32 right-4 sm:right-20', 'delay' => '2s', 'duration' => '18s'],
                ['naam' => 'GL-PvdA', 'kleur' => '#008800', 'logo' => $partyLogos['GL-PvdA'], 'positie' => 'top-1/2 left-8 sm:left-24', 'delay' => '4s', 'duration' => '20s'],
                ['naam' => 'CDA', 'kleur' => '#1E8449', 'logo' => $partyLogos['CDA'], 'positie' => 'bottom-32 right-6 sm:right-16', 'delay' => '1s', 'duration' => '16s'],
                ['naam' => 'D66', 'kleur' => '#00B13C', 'logo' => $partyLogos['D66'], 'positie' => 'bottom-40 left-10 sm:left-32', 'delay' => '3s', 'duration' => '22s'],
                ['naam' => 'SP', 'kleur' => '#EE0000', 'logo' => $partyLogos['SP'], 'positie' => 'top-40 right-12 sm:right-36', 'delay' => '5s', 'duration' => '17s'],
                ['naam' => 'PvdD', 'kleur' => '#006400', 'logo' => $partyLogos['PvdD'], 'positie' => 'bottom-20 right-16 sm:right-28', 'delay' => '6s', 'duration' => '19s'],
                ['naam' => 'Volt', 'kleur' => '#800080', 'logo' => $partyLogos['Volt'], 'positie' => 'top-56 left-6 sm:left-28', 'delay' => '7s', 'duration' => '21s'],
                ['naam' => 'JA21', 'kleur' => '#000000', 'logo' => $partyLogos['JA21'], 'positie' => 'bottom-48 left-16 sm:left-44', 'delay' => '8s', 'duration' => '23s'],
                ['naam' => 'SGP', 'kleur' => '#000000', 'logo' => $partyLogos['SGP'], 'positie' => 'top-64 right-8 sm:right-24', 'delay' => '9s', 'duration' => '25s'],
                ['naam' => 'FvD', 'kleur' => '#000000', 'logo' => $partyLogos['FvD'], 'positie' => 'bottom-16 right-20 sm:right-48', 'delay' => '10s', 'duration' => '27s'],
                ['naam' => 'DENK', 'kleur' => '#000000', 'logo' => $partyLogos['DENK'], 'positie' => 'top-72 left-14 sm:left-52', 'delay' => '11s', 'duration' => '29s']
            ];
            
            foreach($floatingPartijen as $index => $partij):
            ?>
                         <div class="absolute <?php echo $partij['positie']; ?> opacity-20 party-float-<?php echo $index; ?>" 
                  style="animation: floating-<?php echo $index; ?> <?php echo $partij['duration']; ?> infinite linear; animation-delay: <?php echo $partij['delay']; ?>;">
                 <!-- Partij logo container -->
                 <div class="relative group">
                     <!-- Glow effect -->
                     <div class="absolute inset-0 w-12 h-12 sm:w-16 sm:h-16 rounded-2xl blur-md transform scale-110 group-hover:scale-125 transition-transform duration-500"
                          style="background: <?php echo $partij['kleur']; ?>; opacity: 0.3;"></div>
                     
                     <!-- Main logo -->
                     <div class="relative w-12 h-12 sm:w-16 sm:h-16 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/30 flex items-center justify-center transform group-hover:rotate-12 transition-all duration-500 overflow-hidden">
                                                 <!-- Logo afbeelding -->
                         <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg overflow-hidden transform group-hover:scale-110 transition-transform duration-300 flex items-center justify-center">
                            <img src="<?php echo $partij['logo']; ?>" 
                                 alt="<?php echo $partij['naam']; ?> logo"
                                 class="w-full h-full object-contain transition-all duration-300 group-hover:brightness-110"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 loading="lazy">
                            <!-- Fallback tekst als afbeelding niet laadt -->
                            <div class="hidden w-full h-full rounded-lg font-black text-xs text-white items-center justify-center transform group-hover:scale-110 transition-transform duration-300"
                                 style="background: linear-gradient(135deg, <?php echo $partij['kleur']; ?>, <?php echo $partij['kleur']; ?>88); text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                <?php 
                                // Fallback afkorting
                                $afkorting = $partij['naam'];
                                if (strpos($afkorting, '/') !== false) {
                                    $delen = explode('/', $afkorting);
                                    echo substr($delen[0], 0, 2);
                                } else {
                                    echo substr($afkorting, 0, min(3, strlen($afkorting)));
                                }
                                ?>
                            </div>
                        </div>
                        
                        <!-- Floating particles -->
                        <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full animate-ping opacity-40"
                             style="background: <?php echo $partij['kleur']; ?>;"></div>
                    </div>
                    
                    <!-- Tooltip -->
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <div class="bg-slate-900 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                            <?php echo $partij['naam']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CSS voor floating animaties -->
        <style>
        <?php foreach($floatingPartijen as $index => $partij): ?>
        @keyframes floating-<?php echo $index; ?> {
            0% { transform: translateX(0px) translateY(0px) rotate(0deg); }
            25% { transform: translateX(<?php echo 20 + ($index * 5); ?>px) translateY(-<?php echo 15 + ($index * 3); ?>px) rotate(<?php echo 2 + $index; ?>deg); }
            50% { transform: translateX(-<?php echo 15 + ($index * 4); ?>px) translateY(<?php echo 20 + ($index * 2); ?>px) rotate(-<?php echo 3 + $index; ?>deg); }
            75% { transform: translateX(<?php echo 25 + ($index * 3); ?>px) translateY(<?php echo 10 + ($index * 4); ?>px) rotate(<?php echo 1 + $index; ?>deg); }
            100% { transform: translateX(0px) translateY(0px) rotate(0deg); }
        }
        <?php endforeach; ?>
        
        @keyframes floating-extra {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }
        
        .animate-spin-slow {
            animation: spin 8s linear infinite;
        }
        
        .party-float-container {
            animation-play-state: running;
        }
        
        .party-float-container:hover {
            animation-play-state: paused;
        }
        </style>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center justify-center min-h-screen lg:min-h-[calc(100vh-80px)] py-24">
                
                <!-- Gecentraliseerde content -->
                <div class="text-center space-y-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold text-gray-900 leading-tight tracking-tight">
                        <span class="block">Politiek<span class="bg-gradient-to-r from-primary via-secondary to-primary bg-clip-text text-transparent">Praat</span></span>
                    </h1>
                    
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                        PolitiekPraat maakt complexe politieke onderwerpen begrijpelijk. Ontdek analyses, volg het laatste nieuws en vorm je eigen mening.
                    </p>
                    
                    <!-- Inline CSS voor kritieke button styling als fallback -->
                    <style>
                        .hero-btn-primary {
                            display: inline-flex;
                            align-items: center;
                            padding: 1rem 2rem;
                            background: linear-gradient(to right, #1a56db, #c41e3a);
                            color: white;
                            font-weight: 700;
                            font-size: 1.125rem;
                            border-radius: 0.75rem;
                            transition: all 0.3s ease;
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                            transform: scale(1);
                            position: relative;
                            overflow: hidden;
                            text-decoration: none;
                        }
                        .hero-btn-primary:hover {
                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                            transform: scale(1.05);
                        }
                        .hero-btn-secondary {
                            display: inline-flex;
                            align-items: center;
                            padding: 1rem 2rem;
                            background: white;
                            color: #111827;
                            font-weight: 700;
                            font-size: 1.125rem;
                            border-radius: 0.75rem;
                            border: 2px solid #e5e7eb;
                            transition: all 0.3s ease;
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                            transform: scale(1);
                            position: relative;
                            overflow: hidden;
                            text-decoration: none;
                        }
                        .hero-btn-secondary:hover {
                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                            transform: scale(1.05);
                            border-color: #1a56db;
                            color: #1a56db;
                        }
                        @media (max-width: 640px) {
                            .hero-btn-primary, .hero-btn-secondary {
                                padding: 0.875rem 1.5rem;
                                font-size: 1rem;
                            }
                        }
                    </style>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                        <a href="<?php echo URLROOT; ?>/blogs" class="new-hero-cta-primary hero-btn-primary group">
                            <span>Bekijk onze Blogs</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </a>
                        <a href="<?php echo URLROOT; ?>/stemwijzer" class="new-hero-cta-secondary hero-btn-secondary group">
                            <span>Start de Stemwijzer</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>

                <!-- Stats/Features - Toegevoegd voor meer visuele interesse -->
                <div class="grid grid-cols-3 gap-6 pt-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">150+</div>
                        <div class="text-sm text-gray-600">Artikelen</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">20+</div>
                        <div class="text-sm text-gray-600">Thema's</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">5k+</div>
                        <div class="text-sm text-gray-600">Lezers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- Laatste Nieuws & Blogs Sections -->
        <section class="py-24 bg-gradient-to-b from-gray-50 via-white to-gray-50 relative overflow-hidden">
            <!-- Decoratieve achtergrond elementen -->
            <div class="absolute inset-0">
                <!-- Floating geometric shapes -->
                <div class="absolute top-20 left-10 w-32 h-32 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-2xl animate-float"></div>
                <div class="absolute top-40 right-20 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full blur-xl animate-float-delayed"></div>
                <div class="absolute bottom-32 left-1/4 w-40 h-40 bg-gradient-to-br from-green-500/8 to-blue-500/8 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-1/3 w-28 h-28 bg-gradient-to-br from-yellow-500/8 to-red-500/8 rounded-full blur-2xl animate-bounce-slow"></div>
                
                <!-- Grid pattern overlay -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgwLDAsMCwwLjAzKSIvPgo8L3N2Zz4=')] opacity-40"></div>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <!-- Laatste Blogs -->
                <div class="mb-32 relative">
                    <!-- Uniforme header sectie -->
                    <div class="text-center mb-20 relative" data-aos="fade-up" data-aos-once="true">
                        <!-- Achtergrond tekst -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                            <span class="text-[120px] sm:text-[160px] lg:text-[200px] xl:text-[280px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">BLOGS</span>
                        </div>
                        
                                                <!-- Main content -->
                        <div class="relative z-10 space-y-8">
                            <!-- Hoofdtitel -->
                            <div class="space-y-6">
                                                            <h2 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Laatste</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Blogs
                                </span>
                            </h2>
                            
                                <!-- Decoratieve lijn systeem -->
                                <div class="flex items-center justify-center space-x-6 mt-8">
                                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                    <div class="relative">
                                        <div class="w-4 h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                        <div class="absolute inset-0 w-4 h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                        <div class="absolute inset-0 w-4 h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                    </div>
                                    <div class="w-32 h-0.5 bg-gradient-to-r from-blue-600 via-red-600 to-blue-800"></div>
                                    <div class="relative">
                                        <div class="w-4 h-4 bg-red-600 rounded-full animate-pulse animation-delay-300"></div>
                                        <div class="absolute inset-0 w-4 h-4 bg-red-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                    </div>
                                    <div class="w-16 h-0.5 bg-gradient-to-r from-red-600 via-red-500 to-transparent"></div>
                                </div>
                            </div>
                            
                            <!-- Subtitel -->
                            <p class="text-xl sm:text-2xl lg:text-3xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                                Ontdek mijn meest recente <span class="font-semibold text-blue-600">politieke analyses</span> en <span class="font-semibold text-red-600">diepgaande inzichten</span> over de Nederlandse politiek
                            </p>
                        </div>
                    </div>

                    <!-- Blogs Grid met premium design -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                        <?php foreach($latest_blogs as $index => $blog): ?>
                            <article class="group relative bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transform transition-all duration-700 hover:-translate-y-3 border border-gray-100/50 backdrop-blur-sm" 
                                    data-aos="fade-up" 
                                    data-aos-delay="<?php echo $index * 150; ?>"
                                    data-aos-duration="800"
                                    data-aos-easing="ease-out-cubic"
                                    data-aos-once="true">
                                
                                <!-- Hover effect overlay -->
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                                
                                <!-- Top accent line -->
                                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-secondary to-primary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-700 ease-out"></div>
                                
                                <?php 
                                // Check of de blog minder dan 12 uur oud is
                                $published_time = strtotime($blog->published_at);
                                $twelve_hours_ago = time() - (12 * 3600);
                                ?>
                                
                                <?php if ($published_time > $twelve_hours_ago): ?>
                                    <!-- Nieuw Badge voor recente blogs -->
                                    <div class="absolute top-6 right-6 z-30">
                                        <div class="relative">
                                            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-orange-500 rounded-full blur animate-pulse"></div>
                                            <div class="relative bg-gradient-to-r from-red-500 to-orange-500 text-white px-4 py-2 rounded-full shadow-lg transform rotate-3 group-hover:rotate-0 transition-transform duration-300">
                                                <div class="flex items-center space-x-2">
                                                    <div class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-full w-full bg-white"></span>
                                                    </div>
                                                    <span class="text-xs font-bold uppercase tracking-wide">NIEUW</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>" class="block relative h-full">
                                    <!-- Image sectie -->
                                    <div class="relative h-64 lg:h-72 overflow-hidden">
                                        <?php if ($blog->image_path): ?>
                                            <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($blog->title); ?>"
                                                 class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                                 loading="lazy" decoding="async">
                                        <?php else: ?>
                                            <!-- Fallback gradient -->
                                            <div class="w-full h-full bg-gradient-to-br from-primary/20 via-secondary/20 to-primary/30 flex items-center justify-center">
                                                <div class="text-center">
                                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                    <p class="text-gray-500 font-medium">Blog Artikel</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Gradient overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                        
                                        <!-- Category floating badge -->
                                        <div class="absolute top-6 left-6 z-20">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-white/20 backdrop-blur-md rounded-xl blur-sm"></div>
                                                <div class="relative bg-white/90 backdrop-blur-sm px-4 py-2 rounded-xl shadow-lg border border-white/20">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Politiek</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Reading time badge -->
                                        <div class="absolute bottom-6 right-6 z-20">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-black/20 backdrop-blur-md rounded-xl blur-sm"></div>
                                                <div class="relative bg-black/70 backdrop-blur-sm text-white px-3 py-2 rounded-xl shadow-lg">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium"><?php echo getRelativeTime($blog->published_at); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Content sectie -->
                                    <div class="p-8 min-h-[320px] flex flex-col">
                                        <!-- Author info -->
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <div class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-primary/20 group-hover:ring-primary/40 transition-all duration-300">
                                                        <img src="<?php echo URLROOT; ?>/public/images/naoufal-foto.jpg" 
                                                             onerror="if(this.src !== '<?php echo URLROOT; ?>/images/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/images/naoufal-foto.jpg'; else if(this.src !== '<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg';"
                                                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($blog->author_name); ?></p>
                                                    <p class="text-xs text-gray-500">Auteur</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4 leading-tight group-hover:text-primary transition-colors duration-300 line-clamp-2 flex-grow">
                                            <?php echo htmlspecialchars($blog->title); ?>
                                        </h3>

                                        <!-- Summary -->
                                        <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed text-sm lg:text-base flex-grow">
                                            <?php echo htmlspecialchars($blog->summary); ?>
                                        </p>

                                        <!-- Footer -->
                                        <div class="mt-auto space-y-4">
                                            <!-- Stats -->
                                            <div class="flex items-center justify-between text-sm text-gray-500">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span><?php echo (isset($blog->likes) && $blog->likes > 0) ? $blog->likes : '0'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="text-xs font-medium">
                                                    <?php 
                                                        $wordCount = str_word_count(strip_tags($blog->content ?? ''));
                                                        $readingTime = max(1, round($wordCount / 200)); // 200 woorden per minuut
                                                        echo $readingTime . ' min leestijd';
                                                    ?>
                                                </div>
                                            </div>
                                            
                                            <!-- CTA Button -->
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary rounded-xl blur opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                                <div class="relative bg-gradient-to-r from-primary to-secondary p-0.5 rounded-xl group-hover:shadow-lg transition-all duration-300">
                                                    <div class="bg-white rounded-[11px] px-6 py-3 group-hover:bg-transparent transition-all duration-300">
                                                        <div class="flex items-center justify-center space-x-2 group-hover:text-white transition-colors duration-300">
                                                            <span class="font-semibold text-gray-900 group-hover:text-white">Lees artikel</span>
                                                            <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1 text-gray-900 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Decoratieve hoek elementen -->
                                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 transform rotate-45 translate-x-8 -translate-y-8 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                                <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-secondary/10 to-primary/10 transform rotate-45 -translate-x-6 translate-y-6 group-hover:-translate-x-4 group-hover:translate-y-4 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Enhanced CTA Section -->
                    <div class="text-center mt-20" data-aos="zoom-in" data-aos-delay="400" data-aos-once="true">
                        <div class="relative inline-block">
                            <!-- Glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                            
                            <!-- Main button -->
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="relative inline-flex items-center px-12 py-5 bg-gradient-to-r from-primary via-secondary to-primary bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-bold text-lg rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                                
                                <!-- Button content -->
                                <div class="relative z-10 flex items-center">
                                    <span class="mr-3">Ontdek alle blogs</span>
                                    <div class="relative">
                                        <svg class="w-6 h-6 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                        <!-- Arrow trail effect -->
                                        <svg class="w-6 h-6 absolute inset-0 transform translate-x-[-100%] opacity-0 group-hover:translate-x-8 group-hover:opacity-50 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Shimmer effect -->
                                <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                            </a>
                            
                            <!-- Supporting text -->
                            <p class="mt-6 text-gray-600 text-sm">
                                <span class="font-semibold text-blue-600"><?php echo count($latest_blogs); ?></span> artikelen weergegeven • 
                                <span class="font-semibold text-red-600">150+</span> totaal beschikbaar
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Laatste Nieuws - Volledig herbouwde professionele sectie -->
        <section class="py-32 bg-gradient-to-b from-slate-50 via-white to-slate-50 relative overflow-hidden">
            <!-- Premium decoratieve achtergrond -->
            <div class="absolute inset-0">
                <!-- Animated gradient orbs -->
                <div class="absolute top-20 left-10 w-72 h-72 bg-gradient-to-br from-blue-400/20 to-indigo-600/20 rounded-full blur-3xl animate-float opacity-60"></div>
                <div class="absolute top-60 right-16 w-96 h-96 bg-gradient-to-br from-red-400/15 to-orange-500/15 rounded-full blur-3xl animate-float-delayed opacity-50"></div>
                <div class="absolute bottom-32 left-1/3 w-80 h-80 bg-gradient-to-br from-purple-400/10 to-pink-500/10 rounded-full blur-3xl animate-pulse-slow opacity-40"></div>
                
                <!-- Geometric patterns -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.05\"%3E%3Cpath d=\"M30 0L30 60M0 30L60 30\" stroke=\"%23334155\" stroke-width=\"1\"%3E%3C/path%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
                
                <!-- Subtle noise texture -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=\"0 0 256 256\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"noiseFilter\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.9\" numOctaves=\"1\" stitchTiles=\"stitch\"/%3E%3C/filter%3E%3Crect width=\"100%25\" height=\"100%25\" filter=\"url(%23noiseFilter)\" opacity=\"0.02\"/%3E%3C/svg%3E')]"></div>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Uniforme header sectie -->
                <div class="text-center mb-24 relative" data-aos="fade-up" data-aos-once="true">
                    <!-- Achtergrond tekst -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                        <span class="text-[120px] sm:text-[160px] lg:text-[200px] xl:text-[280px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">NIEUWS</span>
                    </div>
                    
                    <!-- Main content -->
                    <div class="relative z-10 space-y-8">
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                                                    <h2 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-slate-900 leading-tight tracking-tight">
                            <span class="block mb-2">Laatste</span>
                            <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                Politiek Nieuws
                            </span>
                        </h2>
                            
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-6 mt-8">
                                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-32 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-4 h-4 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-16 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                        </div>
                        
                        <!-- Subtitel -->
                        <p class="text-xl sm:text-2xl lg:text-3xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                            Vergelijk <span class="font-semibold text-blue-600">progressieve</span> en <span class="font-semibold text-red-600">conservatieve</span> perspectieven op de laatste ontwikkelingen
                        </p>
                        
                        <!-- Status indicator -->
                        <div class="inline-flex items-center px-6 py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-3 h-3 bg-green-500 rounded-full animate-ping opacity-50"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-600">Laatste update: vandaag om 08:30</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium nieuws grid -->
                <div class="relative">
                    <!-- Perspective divider -->
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-px h-full bg-gradient-to-b from-transparent via-slate-300 to-transparent hidden lg:block"></div>
                    
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-16 lg:gap-20 items-start">
                        <!-- Progressieve bronnen - Links -->
                        <div class="space-y-12" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                            <!-- Sectie header -->
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-25"></div>
                                <div class="relative bg-white rounded-2xl shadow-xl border border-blue-100/50 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-slate-900">Progressieve Media</h3>
                                                    <p class="text-sm text-blue-600 font-medium">Links-centrum perspectief</p>
                                                </div>
                                            </div>
                                            <div class="hidden sm:flex items-center space-x-2 text-blue-600">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                                <span class="text-xs font-medium">LIVE</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nieuws artikelen -->
                            <div class="space-y-8">
                                <?php
                                $links_news = array_filter($latest_news, function($news) {
                                    return $news['orientation'] === 'links';
                                });
                                foreach($links_news as $index => $news):
                                ?>
                                    <article class="group relative" 
                                            data-aos="fade-up" 
                                            data-aos-delay="<?php echo $index * 150; ?>"
                                            data-aos-duration="800"
                                            data-aos-once="true">
                                        
                                        <!-- Premium card design -->
                                        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-100/50 overflow-hidden transform transition-all duration-700 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10">
                                            <!-- Gradient accent -->
                                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-blue-500 to-indigo-600"></div>
                                            
                                            <!-- Hover overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                                            
                                            <div class="relative p-8">
                                                <!-- Header met bron info -->
                                                <div class="flex items-start justify-between mb-6">
                                                    <div class="flex items-center space-x-4">
                                                        <!-- Bron logo/avatar -->
                                                        <div class="relative">
                                                            <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                                                <span class="text-blue-700 font-black text-lg">
                                                                    <?php echo substr($news['source'], 0, 2); ?>
                                                                </span>
                                                            </div>
                                                            <!-- Online indicator -->
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-sm">
                                                                <div class="w-full h-full bg-green-500 rounded-full animate-pulse"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="space-y-1">
                                                            <p class="text-lg font-bold text-slate-900"><?php echo $news['source']; ?></p>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                                    <?php echo $news['bias']; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Timestamp -->
                                                    <div class="flex flex-col items-end">
                                                        <div class="inline-flex items-center px-4 py-2 bg-slate-50 rounded-xl border border-slate-200/50 shadow-sm">
                                                            <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-sm font-medium text-slate-600"><?php echo getRelativeTime($news['publishedAt']); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Artikel content -->
                                                <div class="space-y-6">
                                                    <h3 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors duration-300 line-clamp-3">
                                                        <?php echo $news['title']; ?>
                                                    </h3>
                                                    
                                                    <p class="text-slate-600 leading-relaxed text-lg line-clamp-4 group-hover:text-slate-700 transition-colors duration-300">
                                                        <?php echo $news['description']; ?>
                                                    </p>
                                                </div>
                                                
                                                <!-- Footer acties -->
                                                <div class="mt-8">
                                                    <!-- CTA Button -->
                                                    <a href="<?php echo $news['url']; ?>" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="group/btn relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/25 overflow-hidden">
                                                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-blue-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                                        <span class="relative flex items-center">
                                                            Lees volledig artikel
                                                            <svg class="w-5 h-5 ml-2 transform transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    
                                                    </div>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Conservatieve bronnen - Rechts -->
                        <div class="space-y-12" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                            <!-- Sectie header -->
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-red-500 to-orange-600 rounded-2xl blur opacity-25"></div>
                                <div class="relative bg-white rounded-2xl shadow-xl border border-red-100/50 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-8 py-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-slate-900">Conservatieve Media</h3>
                                                    <p class="text-sm text-red-600 font-medium">Rechts-centrum perspectief</p>
                                                </div>
                                            </div>
                                            <div class="hidden sm:flex items-center space-x-2 text-red-600">
                                                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                                <span class="text-xs font-medium">LIVE</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nieuws artikelen -->
                            <div class="space-y-8">
                                <?php
                                $rechts_news = array_filter($latest_news, function($news) {
                                    return $news['orientation'] === 'rechts';
                                });
                                foreach($rechts_news as $index => $news):
                                ?>
                                    <article class="group relative" 
                                            data-aos="fade-up" 
                                            data-aos-delay="<?php echo $index * 150; ?>"
                                            data-aos-duration="800"
                                            data-aos-once="true">
                                        
                                        <!-- Premium card design -->
                                        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-100/50 overflow-hidden transform transition-all duration-700 hover:-translate-y-2 hover:shadow-2xl hover:shadow-red-500/10">
                                            <!-- Gradient accent -->
                                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-400 via-red-500 to-orange-600"></div>
                                            
                                            <!-- Hover overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-orange-50/50 opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                                            
                                            <div class="relative p-8">
                                                <!-- Header met bron info -->
                                                <div class="flex items-start justify-between mb-6">
                                                    <div class="flex items-center space-x-4">
                                                        <!-- Bron logo/avatar -->
                                                        <div class="relative">
                                                            <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                                                <span class="text-red-700 font-black text-lg">
                                                                    <?php echo substr($news['source'], 0, 2); ?>
                                                                </span>
                                                            </div>
                                                            <!-- Online indicator -->
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-sm">
                                                                <div class="w-full h-full bg-green-500 rounded-full animate-pulse"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="space-y-1">
                                                            <p class="text-lg font-bold text-slate-900"><?php echo $news['source']; ?></p>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                                    <?php echo $news['bias']; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Timestamp -->
                                                    <div class="flex flex-col items-end">
                                                        <div class="inline-flex items-center px-4 py-2 bg-slate-50 rounded-xl border border-slate-200/50 shadow-sm">
                                                            <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-sm font-medium text-slate-600"><?php echo getRelativeTime($news['publishedAt']); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Artikel content -->
                                                <div class="space-y-6">
                                                    <h3 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight group-hover:text-red-600 transition-colors duration-300 line-clamp-3">
                                                        <?php echo $news['title']; ?>
                                                    </h3>
                                                    
                                                    <p class="text-slate-600 leading-relaxed text-lg line-clamp-4 group-hover:text-slate-700 transition-colors duration-300">
                                                        <?php echo $news['description']; ?>
                                                    </p>
                                                </div>
                                                
                                                <!-- Footer acties -->
                                                <div class="mt-8">
                                                    <!-- CTA Button -->
                                                    <a href="<?php echo $news['url']; ?>" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="group/btn relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-orange-600 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/25 overflow-hidden">
                                                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                                        <span class="relative flex items-center">
                                                            Lees volledig artikel
                                                            <svg class="w-5 h-5 ml-2 transform transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium CTA sectie -->
                <div class="text-center mt-24" data-aos="zoom-in" data-aos-delay="400" data-aos-once="true">
                    <div class="relative inline-block">
                        <!-- Multi-layered glow effect -->
                        <div class="absolute -inset-8 bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 rounded-3xl blur-2xl opacity-20 animate-pulse"></div>
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-500 via-red-500 to-blue-700 rounded-2xl blur-xl opacity-30"></div>
                        
                        <!-- Main button container -->
                        <div class="relative bg-white rounded-2xl p-8 shadow-2xl border border-slate-200/50 backdrop-blur-sm">
                            <div class="space-y-6">
                                <div class="space-y-4">
                                    <h3 class="text-3xl font-bold text-slate-900">Ontdek alle nieuwsperspectieven</h3>
                                    <p class="text-slate-600 max-w-2xl mx-auto">
                                        Krijg een compleet beeld van het politieke nieuws door verschillende bronnen en perspectieven te vergelijken
                                    </p>
                                </div>
                                
                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-6 py-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">12+</div>
                                        <div class="text-sm text-slate-500">Nieuwsbronnen</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-red-600">2</div>
                                        <div class="text-sm text-slate-500">Perspectieven</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-800">24/7</div>
                                        <div class="text-sm text-slate-500">Updates</div>
                                    </div>
                                </div>
                                
                                <!-- CTA Button -->
                                <a href="<?php echo URLROOT; ?>/nieuws" 
                                   class="group relative inline-flex items-center justify-center px-12 py-5 bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 text-white font-bold text-xl rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl overflow-hidden">
                                    
                                    <!-- Animated background -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-red-600 via-blue-600 to-red-800 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    
                                    <!-- Button content -->
                                    <div class="relative z-10 flex items-center">
                                        <span class="mr-4">Bekijk al het nieuws</span>
                                        <div class="relative">
                                            <svg class="w-7 h-7 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                            <!-- Arrow trail effect -->
                                            <svg class="w-7 h-7 absolute inset-0 transform translate-x-[-150%] opacity-0 group-hover:translate-x-10 group-hover:opacity-40 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Shimmer effect -->
                                    <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                                </a>
                                
                                <!-- Supporting text -->
                                <p class="text-sm text-slate-500">
                                    <span class="font-semibold text-blue-600"><?php echo count($latest_news); ?></span> artikelen weergegeven • 
                                    <span class="font-semibold text-red-600">Dagelijks</span> nieuwe updates • 
                                    <span class="font-semibold text-blue-800">Gratis</span> toegang
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Peiling Section - Volledig herbouwde professionele versie -->
        <section class="py-32 bg-gradient-to-b from-white via-slate-50 to-white relative overflow-hidden">
            <!-- Premium decoratieve achtergrond -->
            <div class="absolute inset-0">
                <!-- Animated gradient spheres -->
                <div class="absolute top-20 left-10 w-96 h-96 bg-gradient-to-br from-blue-500/10 to-indigo-600/10 rounded-full blur-3xl animate-float"></div>
                <div class="absolute top-60 right-16 w-80 h-80 bg-gradient-to-br from-purple-500/8 to-pink-500/8 rounded-full blur-2xl animate-float-delayed"></div>
                <div class="absolute bottom-32 left-1/3 w-72 h-72 bg-gradient-to-br from-green-500/6 to-blue-500/6 rounded-full blur-3xl animate-pulse-slow"></div>
                
                <!-- Geometric pattern overlay -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.04\"%3E%3Cpath d=\"M20 0L20 40M0 20L40 20\" stroke=\"%23334155\" stroke-width=\"1\"%3E%3C/path%3E%3Ccircle cx=\"20\" cy=\"20\" r=\"2\" fill=\"%23334155\"%3E%3C/circle%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
                
                <!-- Floating elements -->
                <div class="absolute top-40 right-1/4 w-4 h-4 bg-blue-500/20 rounded-full animate-bounce"></div>
                <div class="absolute bottom-1/3 left-1/4 w-6 h-6 bg-purple-500/20 rounded-full animate-bounce animation-delay-75"></div>
                <div class="absolute top-2/3 right-1/3 w-3 h-3 bg-indigo-500/20 rounded-full animate-bounce animation-delay-150"></div>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Uniforme header sectie -->
                <div class="text-center mb-24 relative" data-aos="fade-up" data-aos-once="true">
                    <!-- Achtergrond tekst -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                        <span class="text-[120px] sm:text-[160px] lg:text-[200px] xl:text-[280px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">POLLS</span>
                    </div>
                    
                    <!-- Main content -->
                    <div class="relative z-10 space-y-8">
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                            <h2 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Politieke</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Peilingen
                                </span>
                    </h2>
                            
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-6 mt-8">
                                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-32 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-4 h-4 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-16 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                </div>
                
                        <!-- Subtitel -->
                        <p class="text-xl sm:text-2xl lg:text-3xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                            De nieuwste <span class="font-semibold text-blue-600">zetelverdeling</span> volgens recente peilingen van <span class="font-semibold text-red-600">7 juni 2025</span>
                        </p>
                        
                        <!-- Status indicator -->
                        <div class="inline-flex items-center px-6 py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-600">Bron: Peil.nl / Maurice de Hond</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peiling data -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-16 items-start">
                    <!-- Linker kolom: Zetelverdeling Tabel -->
                    <div class="relative" data-aos="fade-right">
                        <!-- Premium card wrapper met glassmorphism -->
                        <div class="peiling-card group relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-500">
                            <!-- Animated background gradient -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-white to-purple-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                            
                            <!-- Card header -->
                            <div class="relative z-10 p-8">
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">Zetelverdeling</h3>
                                        <p class="text-slate-600 font-medium">Tweede Kamer der Staten-Generaal</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-slate-600">Live</span>
                                    </div>
                                </div>
                                
                                <!-- Premium tabel met verbeterde styling -->
                                <div class="overflow-x-auto -mx-2">
                                    <table class="w-full border-collapse text-sm">
                                        <thead>
                                            <tr class="bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/50">
                                                <th class="py-4 px-4 text-left font-bold text-slate-700 tracking-wide">Partijen</th>
                                                <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide whitespace-nowrap">
                                                    <span class="hidden sm:inline">07-06-25</span>
                                                    <span class="sm:hidden">Huidig</span>
                                                </th>
                                                <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide whitespace-nowrap">
                                                    <span class="hidden sm:inline">31-05-25</span>
                                                    <span class="sm:hidden">Vorig</span>
                                                </th>
                                                <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                    <span class="hidden sm:inline">Verschil</span>
                                                    <span class="sm:hidden">+/-</span>
                                                </th>
                                                <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">TK2023</th>
                                                <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">+/-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($peilingData as $index => $partij): 
                                                // Bereken veranderingen
                                                $verandering = $partij['zetels']['peiling'] - $partij['zetels']['tkvorigepeiling'];
                                                $veranderingTK = $partij['zetels']['peiling'] - $partij['zetels']['tk2023'];
                                                
                                                // Premium kleur classes
                                                $veranderingClass = $verandering > 0 ? 'peiling-change-badge bg-emerald-100 text-emerald-800 border-emerald-200' : 
                                                                   ($verandering < 0 ? 'peiling-change-badge bg-red-100 text-red-800 border-red-200' : 
                                                                   'peiling-change-badge bg-slate-100 text-slate-600 border-slate-200');
                                                
                                                $veranderingTKClass = $veranderingTK > 0 ? 'peiling-change-badge bg-emerald-100 text-emerald-800 border-emerald-200' : 
                                                                    ($veranderingTK < 0 ? 'peiling-change-badge bg-red-100 text-red-800 border-red-200' : 
                                                                    'peiling-change-badge bg-slate-100 text-slate-600 border-slate-200');
                                                
                                                // Symbolen
                                                $veranderingSymbol = $verandering > 0 ? '+' . $verandering : 
                                                                    ($verandering < 0 ? $verandering : '0');
                                                
                                                $veranderingTKSymbol = $veranderingTK > 0 ? '+' . $veranderingTK : 
                                                                      ($veranderingTK < 0 ? $veranderingTK : '0');
                                                                      
                                                // Korte naam voor mobiel
                                                $kortNaam = $partij['partij'];
                                                if (strpos($kortNaam, '/') !== false) {
                                                    $delen = explode('/', $kortNaam);
                                                    $kortNaam = $delen[0];
                                                } else if (strlen($kortNaam) > 10) {
                                                    $kortNaam = preg_replace('/[aeiou]/i', '', $kortNaam);
                                                    if (strlen($kortNaam) > 6) {
                                                        $kortNaam = substr($kortNaam, 0, 6);
                                                    }
                                                }
                                            ?>
                                            <tr class="peiling-table-row group border-b border-slate-100/50 hover:bg-gradient-to-r hover:from-blue-50/30 hover:via-white hover:to-purple-50/30 transition-all duration-300">
                                                <td class="py-4 px-4">
                                                    <div class="flex items-center group">
                                                        <div class="peiling-party-indicator relative w-4 h-4 rounded-full mr-3 transition-transform duration-300 group-hover:scale-110" 
                                                             style="background-color: <?php echo $partij['color']; ?>">
                                                            <div class="absolute inset-0 rounded-full animate-ping opacity-0 group-hover:opacity-30 transition-opacity duration-300" 
                                                                 style="background-color: <?php echo $partij['color']; ?>"></div>
                                                        </div>
                                                        <div>
                                                            <span class="hidden sm:inline font-bold text-slate-900 group-hover:text-slate-800 transition-colors duration-300"><?php echo $partij['partij']; ?></span>
                                                            <span class="sm:hidden font-bold text-slate-900 group-hover:text-slate-800 transition-colors duration-300"><?php echo $kortNaam; ?></span>
                                                        </div>
                            </div>
                                                </td>
                                                <td class="py-4 px-3 text-center">
                                                    <span class="peiling-seats-badge inline-flex items-center justify-center w-10 h-10 bg-slate-900 text-white font-bold rounded-full text-sm group-hover:bg-blue-600 transition-colors duration-300">
                                                        <?php echo $partij['zetels']['peiling']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-3 text-center">
                                                    <span class="font-semibold text-slate-600"><?php echo $partij['zetels']['tkvorigepeiling']; ?></span>
                                                </td>
                                                <td class="py-4 px-3 text-center">
                                                    <span class="<?php echo $veranderingClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border transition-all duration-300">
                                                        <?php echo $veranderingSymbol; ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-3 text-center">
                                                    <span class="font-semibold text-slate-600"><?php echo $partij['zetels']['tk2023']; ?></span>
                                                </td>
                                                <td class="py-4 px-3 text-center">
                                                    <span class="<?php echo $veranderingTKClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border transition-all duration-300">
                                                        <?php echo $veranderingTKSymbol; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                            </div>
                                
                                <!-- Footer informatie -->
                                <div class="mt-8 pt-6 border-t border-slate-200/50">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                                <span class="text-sm font-medium text-slate-600">Winst</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                                <span class="text-sm font-medium text-slate-600">Verlies</span>
                                            </div>
                                        </div>
                                        <div class="text-sm text-slate-500 font-medium">
                                        <span class="hidden sm:inline">Peilingdatum: 7 juni 2025</span>
                                            <span class="sm:hidden">7 jun 2025</span>
                            </div>
                        </div>
                    </div>
                        </div>
                            
                            <!-- Shimmer effect -->
                            <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                        </div>
                    </div>
                    
                    <!-- Rechter kolom: Visualisaties en Coalities -->
                    <div class="space-y-12" data-aos="fade-left">
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
                                        ['name' => 'PVV', 'color' => '#0078D7', 'seats' => 28],
                                        ['name' => 'GL-PvdA', 'color' => '#008800', 'seats' => 29],
                                        ['name' => 'VVD', 'color' => '#FF9900', 'seats' => 26],
                                        ['name' => 'NSC', 'color' => '#4D7F78', 'seats' => 1],
                                        ['name' => 'CDA', 'color' => '#1E8449', 'seats' => 19],
                                        ['name' => 'D66', 'color' => '#00B13C', 'seats' => 8],
                                        ['name' => 'SP', 'color' => '#EE0000', 'seats' => 8]
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
                        <!-- Uniforme header sectie -->
                        <div class="text-center mb-16 relative" data-aos="fade-up" data-aos-once="true">
                            <!-- Achtergrond tekst -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                                <span class="text-[80px] sm:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">PARTIJEN</span>
                            </div>
                            
                            <!-- Main content -->
                            <div class="relative z-10 space-y-6">
                                <!-- Hoofdtitel -->
                                <div class="space-y-4">
                                    <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                                        <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Politieke Partijen
                                </span>
                            </h2>
                                    
                                    <!-- Decoratieve lijn systeem -->
                                    <div class="flex items-center justify-center space-x-4 mt-6">
                                        <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                            <div class="absolute inset-0 w-3 h-3 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                        </div>
                                        <div class="w-24 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                            <div class="absolute inset-0 w-3 h-3 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                        </div>
                                        <div class="w-12 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                                    </div>
                                </div>
                                
                                <!-- Subtitel -->
                                <p class="text-lg sm:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-light">
                                    Verken alle <span class="font-semibold text-blue-600">politieke partijen</span> en hun <span class="font-semibold text-red-600">standpunten</span> op één overzichtelijke plek
                                </p>
                            </div>
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
                        <!-- Uniforme header sectie -->
                        <div class="text-center mb-16 relative" data-aos="fade-up" data-aos-once="true">
                            <!-- Achtergrond tekst -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                                <span class="text-[80px] sm:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">QUIZ</span>
                            </div>
                            
                            <!-- Main content -->
                            <div class="relative z-10 space-y-6">
                                <!-- Hoofdtitel -->
                                <div class="space-y-4">
                                    <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                                        <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Stemwijzer 2025
                                </span>
                            </h2>
                                    
                                    <!-- Decoratieve lijn systeem -->
                                    <div class="flex items-center justify-center space-x-4 mt-6">
                                        <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                            <div class="absolute inset-0 w-3 h-3 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                        </div>
                                        <div class="w-24 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                            <div class="absolute inset-0 w-3 h-3 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                        </div>
                                        <div class="w-12 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                                    </div>
                                </div>
                                
                                <!-- Subtitel -->
                                <p class="text-lg sm:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-light">
                                    Vind de partij die het beste bij jouw <span class="font-semibold text-blue-600">standpunten</span> past met onze <span class="font-semibold text-red-600">stemwijzer</span>
                                </p>
                            </div>
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

    <!-- Newsletter Subscription Section - Herbouwd -->
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Decoratieve elementen -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl opacity-70 transform -translate-x-1/4 -translate-y-1/4"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-secondary/5 rounded-full blur-3xl opacity-70 transform translate-x-1/4 translate-y-1/4"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Uniforme header sectie -->
                <div class="text-center mb-16 relative" data-aos="fade-up" data-aos-once="true">
                    <!-- Achtergrond tekst -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                        <span class="text-[80px] sm:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">NEWS</span>
                </div>
                
                    <!-- Main content -->
                    <div class="relative z-10 space-y-6">
                        <!-- Hoofdtitel -->
                        <div class="space-y-4">
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Mis nooit meer</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Politiek Nieuws
                                </span>
                </h2>
                
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-4 mt-6">
                                <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-3 h-3 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-24 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-3 h-3 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-3 h-3 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-12 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                        </div>
                        
                        <!-- Subtitel -->
                        <p class="text-lg sm:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-light">
                            Ontvang direct een melding bij nieuwe <span class="font-semibold text-blue-600">blogs</span> en <span class="font-semibold text-red-600">politieke updates</span>
                        </p>
                    </div>
                </div>
                
                <!-- Inschrijfformulier met verbeterde styling -->
                <div class="max-w-lg mx-auto" data-aos="fade-up" data-aos-delay="200">
                    <form id="newsletterForm" class="relative flex flex-col sm:flex-row gap-3 group">
                        <input type="email" name="email" id="newsletter-email" placeholder="jouw@emailadres.nl" required 
                               class="flex-1 px-6 py-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary shadow-sm text-base transition-all duration-300 focus:shadow-lg focus:scale-[1.02] placeholder-gray-400">
                        <button type="submit" 
                                class="px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl relative overflow-hidden group-focus-within:ring-4 group-focus-within:ring-primary/30 flex items-center justify-center text-base">
                            <span class="relative z-10 flex items-center">
                                Inschrijven
                                <svg class="w-5 h-5 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300 rounded-xl"></span>
                        </button>
                    </form>
                    <div id="newsletterMessage" class="mt-4 text-center text-sm hidden transition-all"></div> 
                </div>
                <p class="text-xs text-gray-500 mt-6" data-aos="fade-up" data-aos-delay="300">
                    We respecteren je privacy. Geen spam, alleen relevante updates.
                </p>
            </div>
        </div>
    </section>

    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Global JavaScript variables -->
    <script>
        window.URLROOT = '<?php echo URLROOT; ?>';
    </script>

    <!-- Laad het externe home.js script -->
    <script src="<?php echo URLROOT; ?>/public/js/home.js" defer></script>

<?php require_once 'views/templates/footer.php'; ?>

<?php
// Helper functie voor relatieve tijd
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
        // Formatteer de datum als 'dag Maand Jaar' (bijv. '15 Apr 2025')
        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, null, null, 'd MMM yyyy');
        return $formatter->format($timestamp);
    }
}

