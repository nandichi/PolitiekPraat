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
            'peiling' => 29, 
            'vorige' => 30,
            'tkvorigepeiling' => 31,
            'tk2023' => 37
        ],
        'color' => '#0078D7'
    ],
    [
        'partij' => 'GL/PvdA',
        'zetels' => [
            'peiling' => 28, 
            'vorige' => 29,
            'tkvorigepeiling' => 30,
            'tk2023' => 25
        ],
        'color' => '#008800'
    ],
    [
        'partij' => 'CDA',
        'zetels' => [
            'peiling' => 21, 
            'vorige' => 21,
            'tkvorigepeiling' => 18,
            'tk2023' => 5
        ],
        'color' => '#1E8449'
    ],
    [
        'partij' => 'VVD',
        'zetels' => [
            'peiling' => 20, 
            'vorige' => 22,
            'tkvorigepeiling' => 25,
            'tk2023' => 24
        ],
        'color' => '#FF9900'
    ],
    [
        'partij' => 'JA21',
        'zetels' => [
            'peiling' => 10, 
            'vorige' => 8,
            'tkvorigepeiling' => 4,
            'tk2023' => 1
        ],
        'color' => '#4B0082'
    ],
    [
        'partij' => 'D66',
        'zetels' => [
            'peiling' => 9, 
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
            'tkvorigepeiling' => 7,
            'tk2023' => 5
        ],
        'color' => '#EE0000'
    ],
    [
        'partij' => 'BBB',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 3,
            'tkvorigepeiling' => 2,
            'tk2023' => 7
        ],
        'color' => '#7CFC00'
    ],
    [
        'partij' => 'FVD',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 4,
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
            'peiling' => 3, 
            'vorige' => 3,
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
        'partij' => 'NSC',
        'zetels' => [
            'peiling' => 0, 
            'vorige' => 0,
            'tkvorigepeiling' => 1,
            'tk2023' => 20
        ],
        'color' => '#4D7F78'
    ]
];

// Mogelijke coalities berekenen op basis van de bijgewerkte peilingdata van 5-7-2025
$mogelijkeCoalities = [
    [
        'naam' => 'Links-progressief',
        'partijen' => ['GL/PvdA', 'D66', 'SP', 'PvdDieren', 'Volt'],
        'zetels' => 28 + 9 + 7 + 4 + 3 // 51 zetels
    ],
    [
        'naam' => 'Rechts-conservatief',
        'partijen' => ['PVV', 'VVD', 'BBB', 'JA21', 'SGP', 'FVD'],
        'zetels' => 29 + 20 + 4 + 10 + 4 + 4 // 71 zetels
    ],
    [
        'naam' => 'Centrum-breed',
        'partijen' => ['GL/PvdA', 'VVD', 'CDA', 'D66', 'ChristenUnie'],
        'zetels' => 28 + 20 + 21 + 9 + 3 // 81 zetels
    ],
    [
        'naam' => 'Huidige coalitie',
        'partijen' => ['PVV', 'VVD', 'BBB', 'NSC'],
        'zetels' => 29 + 20 + 4 + 0 // 53 zetels
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

$latest_news = [
    [
        'orientation' => 'links',
        'source' => 'De Volkskrant',
        'bias' => 'centrum-links', 
        'publishedAt' => date('Y-m-d H:i:s'), // Vandaag 08:30
        'title' => 'VS bombarderen nucleaire faciliteiten Iran en storten zich in onvoorspelbaar conflict',
        'description' => 'De Verenigde Staten hebben nucleaire faciliteiten in Iran gebombardeerd, wat heeft geleid tot een zeer gespannen situatie. Dit militaire ingrijpen brengt grote onzekerheid met zich mee over mogelijke vergeldingsacties en de toekomstige stabiliteit in de regio.',
        'url' => 'https://www.volkskrant.nl/buitenland/vs-bombarderen-nucleaire-faciliteiten-iran-en-storten-zich-in-onvoorspelbaar-conflict~b4bd58ef/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'President die geen eindeloze oorlogen meer beloofde mengt zich in nieuwe oorlog met Iran',
        'description' => 'De Amerikaanse president die beloofde een einde te maken aan eindeloze oorlogen, raakt nu verwikkeld in een nieuw militair conflict met Iran. Dit roept vragen op over de geloofwaardigheid van eerdere beloftes en de toekomstige koers van het Amerikaanse buitenlandbeleid.',
        'url' => 'https://www.nrc.nl/nieuws/2025/06/22/president-die-geen-eindeloze-oorlogen-meer-beloofde-mengt-zich-in-nieuwe-oorlog-met-iran-a4897821'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'Gemengde reacties op controversiële uitspraken Saskia over migratie',
        'description' => 'De recente uitspraken van Saskia over het migratiebeleid leiden tot verhitte discussies. Sommigen prijzen haar directe aanpak, terwijl anderen haar standpunten te extreem vinden. De politieke spanning neemt toe.',
        'url' => 'https://www.trouw.nl/politiek/gemengde-reacties-op-controversiele-uitspraken-saskia-over-migratie~b7f31232/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Dagelijkse Standaard',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Amerika bombardeert Iraanse kerninstallaties en Israël wist dat dit zou gebeuren',
        'description' => 'De Verenigde Staten hebben Iraanse kerninstallaties gebombardeerd. Volgens bronnen was Israël vooraf op de hoogte van deze militaire actie. Dit markeert een significante escalatie in de spanningen tussen de VS en Iran.',
        'url' => 'https://www.dagelijksestandaard.nl/buitenland/amerika-bombardeert-iraanse-kerninstallaties-en-israel-wist-dat-dit-zou-gebeuren'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'De Telegraaf',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'LIVE - Iran: Aanval VS heeft eeuwigdurende gevolgen',
        'description' => 'Iran heeft gereageerd op de Amerikaanse bombardementen op nucleaire faciliteiten en waarschuwt voor "eeuwigdurende gevolgen". Volg hier live de laatste ontwikkelingen rondom het conflict tussen de VS en Iran.',
        'url' => 'https://www.telegraaf.nl/buitenland/live-iran-aanval-vs-heeft-eeuwigdurende-gevolgen/73099627.html'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Nieuw Rechts',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'VS bombardeert nucleaire installaties in Iran, Trump spreekt van overwinning',
        'description' => 'De Verenigde Staten hebben nucleaire installaties in Iran gebombardeerd. Voormalig president Trump noemt de actie een overwinning en zegt dat dit onder zijn leiding al eerder had moeten gebeuren.',
        'url' => 'https://nieuwrechts.nl/104891-vs-bombardeert-nucleaire-installaties-in-iran-trump-spreekt-van-overwinning'
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
    <!-- Hero Section - Volledig herbouwde professionele versie -->
    <section class="hero-section relative min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 overflow-hidden">
        
        <!-- Geavanceerde achtergrond met meerdere lagen -->
        <div class="absolute inset-0 z-0">
            <!-- Basis gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-dark/5 via-white to-secondary/5"></div>
            
            <!-- Animated mesh gradient -->
            <div class="absolute inset-0 opacity-40">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-secondary/10 animate-pulse"></div>
            </div>
            
            <!-- Geometric pattern overlay -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"80\" height=\"80\" viewBox=\"0 0 80 80\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Cpath d=\"M40 0L40 80M0 40L80 40\" stroke=\"%23334155\" stroke-width=\"1\"%3E%3C/path%3E%3Ccircle cx=\"40\" cy=\"40\" r=\"2\" fill=\"%23334155\"%3E%3C/circle%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            
            <!-- Glowing orbs -->
            <div class="absolute top-20 left-20 w-72 h-72 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-primary-light/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
        </div>
        
        <!-- Floating Partij Logo's - Verbeterd -->
        <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
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
            
            // Uitgebreide partijen configuratie
            $floatingPartijen = [
                ['naam' => 'VVD', 'kleur' => '#FF9900', 'logo' => $partyLogos['VVD'], 'positie' => 'top-24 left-8 sm:left-20', 'delay' => '0s', 'duration' => '20s', 'size' => 'large'],
                ['naam' => 'PVV', 'kleur' => '#0078D7', 'logo' => $partyLogos['PVV'], 'positie' => 'top-40 right-8 sm:right-24', 'delay' => '3s', 'duration' => '22s', 'size' => 'large'],
                ['naam' => 'GL-PvdA', 'kleur' => '#008800', 'logo' => $partyLogos['GL-PvdA'], 'positie' => 'top-1/2 left-12 sm:left-28', 'delay' => '6s', 'duration' => '24s', 'size' => 'medium'],
                ['naam' => 'CDA', 'kleur' => '#1E8449', 'logo' => $partyLogos['CDA'], 'positie' => 'bottom-40 right-12 sm:right-20', 'delay' => '2s', 'duration' => '18s', 'size' => 'medium'],
                ['naam' => 'D66', 'kleur' => '#00B13C', 'logo' => $partyLogos['D66'], 'positie' => 'bottom-56 left-16 sm:left-36', 'delay' => '4s', 'duration' => '26s', 'size' => 'medium'],
                ['naam' => 'SP', 'kleur' => '#EE0000', 'logo' => $partyLogos['SP'], 'positie' => 'top-56 right-16 sm:right-40', 'delay' => '7s', 'duration' => '19s', 'size' => 'small'],
                ['naam' => 'PvdD', 'kleur' => '#006400', 'logo' => $partyLogos['PvdD'], 'positie' => 'bottom-24 right-20 sm:right-32', 'delay' => '8s', 'duration' => '21s', 'size' => 'small'],
                ['naam' => 'Volt', 'kleur' => '#800080', 'logo' => $partyLogos['Volt'], 'positie' => 'top-72 left-10 sm:left-32', 'delay' => '10s', 'duration' => '25s', 'size' => 'small'],
                ['naam' => 'JA21', 'kleur' => '#4B0082', 'logo' => $partyLogos['JA21'], 'positie' => 'bottom-72 left-20 sm:left-48', 'delay' => '12s', 'duration' => '27s', 'size' => 'small'],
                ['naam' => 'SGP', 'kleur' => '#ff7f00', 'logo' => $partyLogos['SGP'], 'positie' => 'top-80 right-12 sm:right-28', 'delay' => '14s', 'duration' => '29s', 'size' => 'small'],
                ['naam' => 'FvD', 'kleur' => '#8B4513', 'logo' => $partyLogos['FvD'], 'positie' => 'bottom-16 right-24 sm:right-52', 'delay' => '16s', 'duration' => '31s', 'size' => 'small'],
                ['naam' => 'DENK', 'kleur' => '#00BFFF', 'logo' => $partyLogos['DENK'], 'positie' => 'top-96 left-24 sm:left-56', 'delay' => '18s', 'duration' => '33s', 'size' => 'small']
            ];
            
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
            ?>
            <div class="absolute <?php echo $partij['positie']; ?> opacity-15 hover:opacity-30 transition-opacity duration-500 party-float-<?php echo $index; ?>" 
                 style="animation: floating-<?php echo $index; ?> <?php echo $partij['duration']; ?> infinite ease-in-out; animation-delay: <?php echo $partij['delay']; ?>;">
                <!-- Partij logo container met verbeterde styling -->
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

        <!-- Geavanceerde CSS animaties -->
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
        
        /* Additionele animaties */
        @keyframes hero-pulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        
        @keyframes hero-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
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
        
        <!-- Main content container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="flex items-center justify-center min-h-screen py-20">
                
                <!-- Gecentraliseerde content met verbeterde styling -->
                <div class="text-center space-y-12 max-w-6xl mx-auto" data-aos="fade-up" data-aos-duration="1200">
                    <!-- Hoofdtitel met verbeterde effecten -->
                    <div class="space-y-6" data-aos="fade-up" data-aos-delay="400">
                        <h1 class="text-6xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black leading-tight tracking-tight">
                            <span class="block bg-gradient-to-r from-primary-dark via-primary to-secondary bg-clip-text text-transparent mb-4">
                                PolitiekPraat
                            </span>
                            <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent text-xl sm:text-2xl md:text-3xl lg:text-4xl font-light">
                                Jouw politieke kompas
                            </span>
                        </h1>
                    
                    <!-- Verbeterde subtitle -->
                    <div class="space-y-4" data-aos="fade-up" data-aos-delay="800">
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                            Ontdek de Nederlandse politiek via 
                            <span class="font-bold text-primary">blogs</span>, 
                            <span class="font-bold text-secondary">nieuws</span> en 
                            <span class="font-bold text-primary-dark">interactieve tools</span>
                        </p>
                        
                        <!-- Stats highlights -->
                        <div class="grid grid-cols-3 gap-4 sm:gap-6 max-w-2xl mx-auto mt-8" data-aos="fade-up" data-aos-delay="1000">
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl md:text-3xl font-bold text-primary">150+</div>
                                <div class="text-xs sm:text-sm text-slate-500 uppercase tracking-wide">Blogs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl md:text-3xl font-bold text-secondary">15</div>
                                <div class="text-xs sm:text-sm text-slate-500 uppercase tracking-wide">Partijen</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl md:text-3xl font-bold text-primary-dark">25</div>
                                <div class="text-xs sm:text-sm text-slate-500 uppercase tracking-wide">Stemwijzer vragen</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Geavanceerde CTA buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center pt-8" data-aos="fade-up" data-aos-delay="1200">
                        <!-- Primary CTA -->
                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="group relative inline-flex items-center justify-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-gradient-to-r from-primary-dark via-primary to-secondary text-white font-bold text-base sm:text-lg md:text-xl rounded-xl sm:rounded-2xl transition-all duration-500 transform hover:scale-105 hover:shadow-2xl hover:shadow-primary/25 overflow-hidden">
                            
                            <!-- Animated background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary via-primary to-primary-dark opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <span class="mr-2 sm:mr-3">Ontdek onze Blogs</span>
                                <div class="relative">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <!-- Arrow trail -->
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 absolute inset-0 transform translate-x-[-100%] opacity-0 group-hover:translate-x-8 group-hover:opacity-60 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Shimmer effect -->
                            <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                        </a>
                        
                        <!-- Secondary CTA -->
                        <a href="<?php echo URLROOT; ?>/stemwijzer" 
                           class="group relative inline-flex items-center justify-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-white/90 backdrop-blur-sm text-slate-900 font-bold text-base sm:text-lg md:text-xl rounded-xl sm:rounded-2xl border-2 border-slate-200 transition-all duration-500 transform hover:scale-105 hover:shadow-2xl hover:shadow-slate-900/10 hover:border-primary overflow-hidden">
                            
                            <!-- Animated background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-light/20 via-secondary-light/20 to-primary-light/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3 text-primary group-hover:text-primary-dark transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="mr-2 sm:mr-3 group-hover:text-primary-dark transition-colors duration-300">Start de Stemwijzer</span>
                                <div class="relative">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 transform transition-transform duration-500 group-hover:translate-x-2 text-primary group-hover:text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Shimmer effect -->
                            <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                        </a>
                    </div>
                    
                    <!-- Additional features hint -->
                    <div class="pt-8 text-center" data-aos="fade-up" data-aos-delay="1400">
                        <p class="text-slate-500 text-lg">
                            Ook beschikbaar: 
                            <a href="<?php echo URLROOT; ?>/nieuws" class="text-primary hover:text-primary-dark font-semibold transition-colors duration-300">Politiek Nieuws</a>, 
                            <a href="<?php echo URLROOT; ?>/partijen" class="text-secondary hover:text-secondary-dark font-semibold transition-colors duration-300">Partijoverzichten</a>, 
                            <a href="<?php echo URLROOT; ?>/programma-vergelijker" class="text-primary hover:text-primary-dark font-semibold transition-colors duration-300">Programma Vergelijker</a>
                        </p>
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
                            <span class="text-[60px] sm:text-[80px] md:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">BLOGS</span>
                        </div>
                        
                                                <!-- Main content -->
                        <div class="relative z-10 space-y-8">
                            <!-- Hoofdtitel -->
                            <div class="space-y-6">
                                                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Laatste</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Blogs
                                </span>
                            </h2>
                            
                                <!-- Decoratieve lijn systeem -->
                                <div class="flex items-center justify-center space-x-4 sm:space-x-6 mt-8">
                                    <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                    <div class="relative">
                                        <div class="w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                        <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                        <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                    </div>
                                    <div class="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-blue-600 via-red-600 to-blue-800"></div>
                                    <div class="relative">
                                        <div class="w-3 sm:w-4 h-3 sm:h-4 bg-red-600 rounded-full animate-pulse animation-delay-300"></div>
                                        <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-red-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                    </div>
                                    <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-red-600 via-red-500 to-transparent"></div>
                                </div>
                            </div>
                            
                            <!-- Subtitel -->
                            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
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
                                            <img src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
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

    <!-- Stemwijzer Call-to-Action Section -->
    <section class="py-24 bg-gradient-to-br from-blue-50 via-slate-50 to-red-50 relative overflow-hidden">
        <!-- Decoratieve achtergrond elementen -->
        <div class="absolute inset-0">
            <!-- Floating geometric shapes -->
            <div class="absolute top-16 left-8 w-40 h-40 bg-gradient-to-br from-blue-400/20 to-red-500/20 rounded-full blur-2xl animate-float"></div>
            <div class="absolute top-32 right-12 w-32 h-32 bg-gradient-to-br from-primary/15 to-secondary/15 rounded-full blur-xl animate-float-delayed"></div>
            <div class="absolute bottom-20 left-1/4 w-48 h-48 bg-gradient-to-br from-red-500/10 to-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-16 right-1/3 w-36 h-36 bg-gradient-to-br from-secondary/12 to-primary/12 rounded-full blur-2xl animate-bounce-slow"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgwLDAsMCwwLjAzKSIvPgo8L3N2Zz4=')] opacity-40"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Linker kolom: Content -->
                    <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-red-100 rounded-full border border-blue-200/50 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Verkiezingen 2025</span>
                        </div>
                        
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Ontdek jouw</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Politieke Match
                                </span>
                            </h2>
                            
                            <!-- Decoratieve lijn -->
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-blue-500 to-red-600"></div>
                                <div class="w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                <div class="w-16 sm:w-24 h-0.5 bg-gradient-to-r from-red-600 to-blue-600"></div>
                            </div>
                        </div>
                        
                        <!-- Verbeterde beschrijving -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 leading-relaxed">
                            Beantwoord <span class="font-semibold text-blue-600"><?php echo $totalQuestions ?? '25'; ?> actuele vragen</span> en zie direct welke partij het beste bij jouw standpunten past.
                        </p>
                        
                        <!-- Verbeterde Features - Gestructureerde lijst -->
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>Persoonlijke match-percentages</strong> met alle partijen</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>Korte uitleg</strong> bij elke vraag en waarom het belangrijk is</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>In 5-10 minuten klaar</strong> en meteen resultaat</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>100% anoniem</strong> - geen account nodig</span>
                            </div>
                        </div>
                        
                        <!-- Uitleg percentages -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="text-sm text-slate-600">
                                <strong class="text-blue-700">Match-percentage uitleg:</strong> Hoe hoger het percentage, hoe beter de partij bij jouw antwoorden past. 85% betekent dat je het bij 85% van de vragen eens bent met die partij.
                            </p>
                        </div>
                        
                        <!-- Verbeterde CTA Button -->
                        <div class="pt-6">
                            <div class="relative inline-block">
                                <!-- Glow effect -->
                                <div class="absolute -inset-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-2xl blur opacity-40 animate-pulse"></div>
                                
                                <!-- Main button -->
                                <a href="<?php echo URLROOT; ?>/stemwijzer" 
                                   class="relative inline-flex items-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-sm sm:text-base md:text-lg lg:text-xl rounded-xl sm:rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                                    
                                    <!-- Button content -->
                                    <div class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="mr-2 sm:mr-3">Start de Stemwijzer – Ontdek jouw match!</span>
                                        <div class="relative">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                            <!-- Arrow trail effect -->
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 absolute inset-0 transform translate-x-[-100%] opacity-0 group-hover:translate-x-8 group-hover:opacity-50 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Shimmer effect -->
                                    <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                                </a>
                            </div>
                            
                            <!-- Supporting text -->
                            <p class="mt-4 text-sm text-slate-500">
                                Gratis • <span class="font-semibold text-blue-600"><?php echo $totalQuestions ?? '25'; ?></span> vragen • <span class="font-semibold text-red-600">Alle Nederlandse partijen</span> opgenomen
                            </p>
                        </div>
                    </div>
                    
                    <!-- Rechter kolom: Visuele demonstratie -->
                    <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <!-- Premium preview card -->
                        <div class="relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 overflow-hidden transform rotate-3 hover:rotate-0 transition-transform duration-700">
                            <!-- Card header -->
                            <div class="bg-gradient-to-r from-primary to-secondary p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white">PolitiekPraat Stemwijzer</h3>
                                            <p class="text-indigo-100 text-sm">Verkiezingen 2025</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-white">85%</div>
                                        <div class="text-xs text-indigo-100">Match</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card content -->
                            <div class="p-6 space-y-6">
                                <!-- Vraag preview -->
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-500">Vraag 12 van <?php echo $totalQuestions ?? '25'; ?></span>
                                        <div class="text-xs text-slate-400">5 min. resterend</div>
                                    </div>
                                    
                                    <!-- Progress bar -->
                                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full animate-pulse" style="width: 48%"></div>
                                    </div>
                                    
                                    <!-- Vraag tekst -->
                                    <div class="bg-slate-50 rounded-xl p-4">
                                        <h4 class="text-lg font-semibold text-slate-900 mb-2">
                                            "Nederland moet een strenger asielbeleid voeren"
                                        </h4>
                                        <p class="text-sm text-slate-600">
                                            Wat vind jij van dit standpunt over het Nederlandse asielbeleid?
                                        </p>
                                    </div>
                                    
                                    <!-- Antwoord opties -->
                                    <div class="space-y-2">
                                        <button class="w-full p-3 bg-emerald-100 border border-emerald-300 rounded-xl text-left font-medium text-emerald-800 transition-all">
                                            ✓ Eens
                                        </button>
                                        <button class="w-full p-3 bg-slate-100 border border-slate-200 rounded-xl text-left text-slate-600 hover:bg-slate-200 transition-all">
                                            Neutraal
                                        </button>
                                        <button class="w-full p-3 bg-slate-100 border border-slate-200 rounded-xl text-left text-slate-600 hover:bg-slate-200 transition-all">
                                            Oneens
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Top matches preview -->
                                <div class="space-y-3">
                                    <h5 class="text-sm font-semibold text-slate-700">Jouw huidige top matches:</h5>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between p-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center text-white text-xs font-bold">1</div>
                                                <span class="text-sm font-medium text-slate-800">VVD</span>
                                            </div>
                                            <span class="text-sm font-bold text-blue-600">85%</span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 bg-gradient-to-r from-red-50 to-red-100 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 bg-red-600 rounded-md flex items-center justify-center text-white text-xs font-bold">2</div>
                                                <span class="text-sm font-medium text-slate-800">D66</span>
                                            </div>
                                            <span class="text-sm font-bold text-red-600">78%</span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center text-white text-xs font-bold">3</div>
                                                <span class="text-sm font-medium text-slate-800">CDA</span>
                                            </div>
                                            <span class="text-sm font-bold text-blue-600">72%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Floating elements -->
                            <div class="absolute -top-4 -right-4 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full animate-bounce"></div>
                            <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Decorative floating cards -->
                        <div class="absolute -top-8 -left-8 w-20 h-20 bg-gradient-to-br from-blue-400/20 to-primary/20 rounded-2xl backdrop-blur-sm rotate-12 animate-float opacity-60"></div>
                        <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-gradient-to-br from-red-400/20 to-secondary/20 rounded-2xl backdrop-blur-sm -rotate-12 animate-float opacity-60" style="animation-delay: -2s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programma Vergelijker Section - Consistent met Stemwijzer styling -->
    <section class="py-24 bg-gradient-to-br from-blue-50 via-slate-50 to-red-50 relative overflow-hidden">
        <!-- Decoratieve achtergrond elementen -->
        <div class="absolute inset-0">
            <!-- Floating geometric shapes -->
            <div class="absolute top-16 left-8 w-40 h-40 bg-gradient-to-br from-blue-400/20 to-red-500/20 rounded-full blur-2xl animate-float"></div>
            <div class="absolute top-32 right-12 w-32 h-32 bg-gradient-to-br from-primary/15 to-secondary/15 rounded-full blur-xl animate-float-delayed"></div>
            <div class="absolute bottom-20 left-1/4 w-48 h-48 bg-gradient-to-br from-red-500/10 to-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-16 right-1/3 w-36 h-36 bg-gradient-to-br from-secondary/12 to-primary/12 rounded-full blur-2xl animate-bounce-slow"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgwLDAsMCwwLjAzKSIvPgo8L3N2Zz4=')] opacity-40"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Linker kolom: Content -->
                    <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-red-100 rounded-full border border-blue-200/50 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Interactieve Tool</span>
                        </div>
                        
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Vergelijk</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Partijstandpunten
                                </span>
                            </h2>
                            
                            <!-- Decoratieve lijn -->
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-blue-500 to-red-600"></div>
                                <div class="w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                <div class="w-16 sm:w-24 h-0.5 bg-gradient-to-r from-red-600 to-blue-600"></div>
                            </div>
                        </div>
                        
                        <!-- Verbeterde beschrijving -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 leading-relaxed">
                            Bekijk de standpunten van <span class="font-semibold text-blue-600">15+ partijen</span> over 8 belangrijke thema's en maak een weloverwogen keuze.
                        </p>
                        
                        <!-- Verbeterde Features - Gestructureerde lijst -->
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>15+ partijen</strong> van groot tot klein</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>8 thema's</strong> klimaat, zorg, onderwijs, immigratie</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>Objectief en helder</strong> begrijpelijke uitleg zonder politieke kleur</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-600 rounded-full flex-shrink-0"></div>
                                <span class="text-sm sm:text-base md:text-lg text-slate-700"><strong>Interactief</strong> filter, vergelijk en zie direct de verschillen</span>
                            </div>
                        </div>
                        
                        <!-- Uitleg functionaliteit -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="text-sm text-slate-600">
                                <strong class="text-blue-700">Zo werkt het:</strong> Selecteer partijen en thema's, bekijk overeenkomsten en verschillen in één oogopslag. Real-time filtering toont direct hoeveel partijen overeenkomen of verschillen per standpunt.
                            </p>
                        </div>
                        
                        <!-- Verbeterde CTA Button -->
                        <div class="pt-6">
                            <div class="relative inline-block">
                                <!-- Glow effect -->
                                <div class="absolute -inset-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-2xl blur opacity-40 animate-pulse"></div>
                                
                                <!-- Main button -->
                                <a href="<?php echo URLROOT; ?>/programma-vergelijker" 
                                   class="relative inline-flex items-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-sm sm:text-base md:text-lg lg:text-xl rounded-xl sm:rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                                    
                                    <!-- Button content -->
                                    <div class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <span class="mr-2 sm:mr-3">Vergelijk nu partijen</span>
                                        <div class="relative">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                            <!-- Arrow trail effect -->
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 absolute inset-0 transform translate-x-[-100%] opacity-0 group-hover:translate-x-8 group-hover:opacity-50 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Shimmer effect -->
                                    <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                                </a>
                            </div>
                            
                            <!-- Supporting text -->
                            <p class="mt-4 text-sm text-slate-500">
                                Gratis • <span class="font-semibold text-blue-600">15+ partijen</span> • <span class="font-semibold text-red-600">8 belangrijke thema's</span> • Direct resultaat
                            </p>
                        </div>
                    </div>
                    
                    <!-- Rechter kolom: Visuele demonstratie -->
                    <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <!-- Premium preview card -->
                        <div class="relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 overflow-hidden transform rotate-3 hover:rotate-0 transition-transform duration-700">
                            <!-- Card header -->
                            <div class="bg-gradient-to-r from-primary to-secondary p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white">Programma Vergelijker</h3>
                                            <p class="text-indigo-100 text-sm">Thema: Klimaat & Energie</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-white">3</div>
                                        <div class="text-xs text-indigo-100">Partijen</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card content -->
                            <div class="p-6 space-y-6">
                                <!-- Partij selectie preview -->
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-500">Geselecteerde partijen</span>
                                        <div class="text-xs text-slate-400">3 van 15</div>
                                    </div>
                                    
                                    <!-- Partij pills -->
                                    <div class="flex flex-wrap gap-2">
                                        <div class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            VVD
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                            GL-PvdA
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                            D66
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Thema header -->
                                    <div class="bg-slate-50 rounded-xl p-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="text-2xl">🌍</div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-slate-900">Klimaat & Energie</h4>
                                                <p class="text-sm text-slate-600">Kernenergie standpunten</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Vergelijking resultaat -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-4 h-4 bg-blue-600 rounded-full"></div>
                                                <span class="text-sm font-medium text-slate-800">VVD: Voor kernenergie</span>
                                            </div>
                                            <span class="text-xs text-green-600 font-bold">✓ PRO</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-4 h-4 bg-green-600 rounded-full"></div>
                                                <span class="text-sm font-medium text-slate-800">GL-PvdA: Tegen kernenergie</span>
                                            </div>
                                            <span class="text-xs text-red-600 font-bold">✗ TEGEN</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-4 h-4 bg-purple-600 rounded-full"></div>
                                                <span class="text-sm font-medium text-slate-800">D66: Voorzichtig voor</span>
                                            </div>
                                            <span class="text-xs text-yellow-600 font-bold">~ NEUTRAAL</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Statistieken -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-3 text-center border border-green-200/50">
                                        <div class="text-xl font-bold text-green-700">2</div>
                                        <div class="text-xs font-medium text-green-600">Overeenkomsten</div>
                                    </div>
                                    <div class="bg-gradient-to-br from-red-50 to-orange-100 rounded-xl p-3 text-center border border-red-200/50">
                                        <div class="text-xl font-bold text-red-700">5</div>
                                        <div class="text-xs font-medium text-red-600">Verschillen</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Floating elements -->
                            <div class="absolute -top-4 -right-4 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full animate-bounce"></div>
                            <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Decorative floating cards -->
                        <div class="absolute -top-8 -left-8 w-20 h-20 bg-gradient-to-br from-blue-400/20 to-primary/20 rounded-2xl backdrop-blur-sm rotate-12 animate-float opacity-60"></div>
                        <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-gradient-to-br from-red-400/20 to-secondary/20 rounded-2xl backdrop-blur-sm -rotate-12 animate-float opacity-60" style="animation-delay: -2s;"></div>
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
                        <span class="text-[60px] sm:text-[80px] md:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">NIEUWS</span>
                    </div>
                    
                    <!-- Main content -->
                    <div class="relative z-10 space-y-8">
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                            <span class="block mb-2">Laatste</span>
                            <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                Politiek Nieuws
                            </span>
                        </h2>
                            
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-4 sm:space-x-6 mt-8">
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-3 sm:w-4 h-3 sm:h-4 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                        </div>
                        
                        <!-- Subtitel -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                            Vergelijk <span class="font-semibold text-blue-600">progressieve</span> en <span class="font-semibold text-red-600">conservatieve</span> perspectieven op de laatste ontwikkelingen
                        </p>
                        
                        <!-- Status indicator -->
                        <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="relative">
                                    <div class="w-2 sm:w-3 h-2 sm:h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-2 sm:w-3 h-2 sm:h-3 bg-green-500 rounded-full animate-ping opacity-50"></div>
                                </div>
                                <span class="text-xs sm:text-sm font-medium text-slate-600">Laatste update: vandaag om 08:30</span>
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
                        <span class="text-[60px] sm:text-[80px] md:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">POLLS</span>
                    </div>
                    
                    <!-- Main content -->
                    <div class="relative z-10 space-y-8">
                        <!-- Hoofdtitel -->
                        <div class="space-y-6">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Politieke</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Peilingen
                                </span>
                    </h2>
                            
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-4 sm:space-x-6 mt-8">
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-3 sm:w-4 h-3 sm:h-4 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                </div>
                
                        <!-- Subtitel -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                            De nieuwste <span class="font-semibold text-blue-600">zetelverdeling</span> volgens recente peilingen van <span class="font-semibold text-red-600">7 juni 2025</span>
                        </p>
                        
                        <!-- Status indicator -->
                        <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <svg class="w-3 sm:w-4 h-3 sm:h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium text-slate-600">Bron: Peil.nl / Maurice de Hond</span>
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
                                <span class="text-[50px] sm:text-[60px] md:text-[80px] lg:text-[100px] xl:text-[120px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">PARTIJEN</span>
                            </div>
                            
                            <!-- Main content -->
                            <div class="relative z-10 space-y-6">
                                <!-- Hoofdtitel -->
                                <div class="space-y-4">
                                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                                        <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Politieke Partijen
                                </span>
                            </h2>
                                    
                                    <!-- Decoratieve lijn systeem -->
                                    <div class="flex items-center justify-center space-x-3 sm:space-x-4 mt-6">
                                        <div class="w-8 sm:w-12 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                        <div class="relative">
                                            <div class="w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                            <div class="absolute inset-0 w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                        </div>
                                        <div class="w-16 sm:w-24 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                        <div class="relative">
                                            <div class="w-2 sm:w-3 h-2 sm:h-3 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                            <div class="absolute inset-0 w-2 sm:w-3 h-2 sm:h-3 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                        </div>
                                        <div class="w-8 sm:w-12 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                                    </div>
                                </div>
                                
                                <!-- Subtitel -->
                                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-light">
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
                        <span class="text-[50px] sm:text-[60px] md:text-[80px] lg:text-[100px] xl:text-[120px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">NEWS</span>
                </div>
                
                    <!-- Main content -->
                    <div class="relative z-10 space-y-6">
                        <!-- Hoofdtitel -->
                        <div class="space-y-4">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                                <span class="block mb-2">Mis nooit meer</span>
                                <span class="bg-gradient-to-r from-blue-600 via-red-600 to-blue-800 bg-clip-text text-transparent animate-gradient bg-size-200">
                                    Politiek Nieuws
                                </span>
                </h2>
                
                            <!-- Decoratieve lijn systeem -->
                            <div class="flex items-center justify-center space-x-3 sm:space-x-4 mt-6">
                                <div class="w-8 sm:w-12 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-blue-600"></div>
                                <div class="relative">
                                    <div class="w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-2 sm:w-3 h-2 sm:h-3 bg-blue-600 rounded-full animate-ping opacity-30"></div>
                                </div>
                                <div class="w-16 sm:w-24 h-0.5 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
                                <div class="relative">
                                    <div class="w-2 sm:w-3 h-2 sm:h-3 bg-indigo-600 rounded-full animate-pulse animation-delay-300"></div>
                                    <div class="absolute inset-0 w-2 sm:w-3 h-2 sm:h-3 bg-indigo-600 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                                </div>
                                <div class="w-8 sm:w-12 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-transparent"></div>
                            </div>
                        </div>
                        
                        <!-- Subtitel -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-light">
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

