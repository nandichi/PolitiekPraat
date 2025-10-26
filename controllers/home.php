<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalDataAPI.php';
require_once 'includes/PollAPI.php';
require_once 'includes/BlogController.php';

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
$blogController = new BlogController();

// Haal actuele politieke data op
$kamerStats = $politicalDataAPI->getKamerStatistieken();
$coalitieStatus = $politicalDataAPI->getCoalitieStatus();
$partijData = $politicalDataAPI->getPartijInformatie();

// Haal peilingen data op
$latestPolls = $pollAPI->getLatestPolls();
$historicalPolls = $pollAPI->getHistoricalPolls(3);

// Data voor zetelverdeling peiling 25-10-2025 (Ipsos I&O / Pauw & De Wit)
$peilingData = [
    [
        'partij' => 'PVV',
        'zetels' => [
            'peiling' => 26, 
            'vorige' => 29,
            'tkvorigepeiling' => 29,
            'tk2023' => 37
        ],
        'color' => '#0078D7'
    ],
    [
        'partij' => 'GL/PvdA',
        'zetels' => [
            'peiling' => 23, 
            'vorige' => 27,
            'tkvorigepeiling' => 22,
            'tk2023' => 25
        ],
        'color' => '#008800'
    ],
    [
        'partij' => 'D66',
        'zetels' => [
            'peiling' => 22, 
            'vorige' => 10,
            'tkvorigepeiling' => 18,
            'tk2023' => 9
        ],
        'color' => '#00B13C'
    ],
    [
        'partij' => 'CDA',
        'zetels' => [
            'peiling' => 20, 
            'vorige' => 21,
            'tkvorigepeiling' => 25,
            'tk2023' => 5
        ],
        'color' => '#1E8449'
    ],
    [
        'partij' => 'VVD',
        'zetels' => [
            'peiling' => 16, 
            'vorige' => 19,
            'tkvorigepeiling' => 14,
            'tk2023' => 24
        ],
        'color' => '#FF9900'
    ],
    [
        'partij' => 'JA21',
        'zetels' => [
            'peiling' => 12, 
            'vorige' => 8,
            'tkvorigepeiling' => 12,
            'tk2023' => 1
        ],
        'color' => '#4B0082'
    ],
    [
        'partij' => 'FVD',
        'zetels' => [
            'peiling' => 5, 
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#8B4513'
    ],
    [
        'partij' => 'PvdDieren',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 5,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#006400'
    ],
    [
        'partij' => 'BBB',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 5,
            'tkvorigepeiling' => 4,
            'tk2023' => 7
        ],
        'color' => '#7CFC00'
    ],
    [
        'partij' => 'SP',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 8,
            'tkvorigepeiling' => 4,
            'tk2023' => 5
        ],
        'color' => '#EE0000'
    ],
    [
        'partij' => 'DENK',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 4,
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#00BFFF'
    ],
    [
        'partij' => 'Volt',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 3,
            'tkvorigepeiling' => 3,
            'tk2023' => 2
        ],
        'color' => '#800080'
    ],
    [
        'partij' => 'SGP',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 4,
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#ff7f00'
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
        'partij' => '50PLUS',
        'zetels' => [
            'peiling' => 2, 
            'vorige' => 0,
            'tkvorigepeiling' => 2,
            'tk2023' => 0
        ],
        'color' => '#9C27B0'
    ],
    [
        'partij' => 'NSC',
        'zetels' => [
            'peiling' => 0, 
            'vorige' => 0,
            'tkvorigepeiling' => 0,
            'tk2023' => 20
        ],
        'color' => '#4D7F78'
    ]
];

// Mogelijke coalities berekenen op basis van de bijgewerkte peilingdata van 25-10-2025
$mogelijkeCoalities = [
    [
        'naam' => 'Links-progressief',
        'partijen' => ['GL/PvdA', 'D66', 'SP', 'PvdDieren', 'Volt'],
        'zetels' => 23 + 22 + 4 + 4 + 3 // 56 zetels
    ],
    [
        'naam' => 'Rechts-conservatief',
        'partijen' => ['PVV', 'VVD', 'BBB', 'JA21', 'SGP', 'FVD'],
        'zetels' => 26 + 16 + 4 + 12 + 3 + 5 // 66 zetels
    ],
    [
        'naam' => 'Centrum-breed',
        'partijen' => ['GL/PvdA', 'VVD', 'CDA', 'D66', 'ChristenUnie'],
        'zetels' => 23 + 16 + 20 + 22 + 3 // 84 zetels
    ],
    [
        'naam' => 'Huidige coalitie',
        'partijen' => ['PVV', 'VVD', 'BBB', 'NSC'],
        'zetels' => 26 + 16 + 4 + 0 // 46 zetels
    ]
];

// Sorteer coalities op aantal zetels (aflopend)
usort($mogelijkeCoalities, function($a, $b) {
    return $b['zetels'] - $a['zetels'];
});

// Haal de laatste 6 blogs op
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

// Haal Amerikaanse verkiezingen data op (laatste 4 verkiezingen)
$db->query("SELECT * FROM amerikaanse_verkiezingen ORDER BY jaar DESC LIMIT 4");
$amerikaanse_verkiezingen = $db->resultSet();

// Haal Nederlandse verkiezingen data op (laatste 4 verkiezingen)
$db->query("SELECT * FROM nederlandse_verkiezingen ORDER BY jaar DESC LIMIT 4");
$nederlandse_verkiezingen = $db->resultSet();

// Parse Nederlandse verkiezingen data
foreach ($nederlandse_verkiezingen as $verkiezing) {
    // Parse partij uitslagen voor grootste partij info
    if (!empty($verkiezing->partij_uitslagen)) {
        $partijUitslagen = json_decode($verkiezing->partij_uitslagen);
        if ($partijUitslagen && count($partijUitslagen) > 0) {
            $verkiezing->grootste_partij = $partijUitslagen[0]->partij ?? null;
            $verkiezing->grootste_partij_zetels = $partijUitslagen[0]->zetels ?? null;
            $verkiezing->grootste_partij_percentage = $partijUitslagen[0]->percentage ?? null;
        }
    }
}

// Haal de populairste blogs op voor de hero sectie
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo 
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
        'title' => 'Relschoppers in Den Haag beschadigden ook Binnenhof tijdens tocht door de stad',
        'description' => 'Tijdens de rellen in Den Haag hebben relschoppers ook schade aangericht aan het Binnenhof. De ongeregeldheden verspreidden zich door de hele stad en het politieke hart van Nederland werd niet gespaard tijdens de gewelddadige tocht.',
        'url' => 'https://www.volkskrant.nl/binnenland/relschoppers-in-den-haag-beschadigden-ook-binnenhof-tijdens-tocht-door-de-stad~b6bbfd7d/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'centrum-links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'Burgers hebben genoeg van al die meningen, zodat Den Haag maar afziet van al te verhit debat',
        'description' => 'Nederlandse burgers tonen steeds minder belangstelling voor verhitte politieke debatten. Politici in Den Haag worstelen met de vraag hoe zij nog kunnen doordringen tot een publiek dat moe is geworden van polarisatie en eindeloze meningsvorming.',
        'url' => 'https://www.nrc.nl/nieuws/2025/09/19/burgers-hebben-genoeg-van-al-die-meningen-zodat-den-haag-maar-afziet-van-al-te-verhit-debat-a4906809'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'centrum-links', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'De Kamer noemt antifa een terroristische organisatie, maar antifa is niet terroristisch en ook geen organisatie',
        'description' => 'De Tweede Kamer heeft een motie aangenomen waarin antifa wordt bestempeld als terroristische organisatie. Experts wijzen erop dat antifa echter geen georganiseerde structuur heeft en niet voldoet aan de definitie van terrorisme.',
        'url' => 'https://www.trouw.nl/politiek/de-kamer-noemt-antifa-een-terroristische-organisatie-maar-antifa-is-niet-terroristisch-en-ook-geen-organisatie~b8651a25/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Dagelijkse Standaard',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Timmermans misleidt: rellen Den Haag door hooligans, niet door extreemrechts',
        'description' => 'Frans Timmermans wijt de rellen in Den Haag ten onrechte aan extreemrechts, terwijl het hooligans betrof. Een analyse van de werkelijke oorzaken achter de ongeregeldheden en de politieke framing die daarop volgde.',
        'url' => 'https://www.dagelijksestandaard.nl/politiek/timmermans-misleidt-rellen-den-haag-door-hooligans-niet-door-extreemrechts'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'De Telegraaf',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'Rob Jetten over aanval op D66-kantoor: \'Deze mensen hebben zich laten ophitsen\'',
        'description' => 'D66-leider Rob Jetten reageert op de aanval op het partijkantoor. Hij stelt dat de daders zich hebben laten ophitsen door politieke retoriek en roept op tot meer respect in het politieke debat.',
        'url' => 'https://www.telegraaf.nl/politiek/rob-jetten-over-aanval-op-d66-kantoor-deze-mensen-hebben-zich-laten-ophitsen/91850069.html'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Nieuw Rechts',
        'bias' => 'rechts', 
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'Rapport onthult: EU gebruikt 250 miljoen voor academische propaganda',
        'description' => 'Een nieuw rapport toont aan dat de Europese Unie jaarlijks 250 miljoen euro besteedt aan wat critici academische propaganda noemen. Het geld wordt gebruikt om universiteiten en onderzoeksinstellingen te financieren die de EU-agenda ondersteunen.',
        'url' => 'https://nieuwrechts.nl/106542-rapport-onthult-eu-gebruikt-250-miljoen-voor-academische-propaganda'
    ]
];

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

// Haal de nieuwste blog post op voor de hero sectie
$latestBlog = $blogController->getAll(1); // Haal alleen de nieuwste blog op
$latestBlog = !empty($latestBlog) ? $latestBlog[0] : null;

?>
<!-- Link to external CSS file with cache busting -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/home.css?v=<?php echo filemtime(__DIR__ . '/../public/css/home.css'); ?>">
<?php
require_once 'views/templates/header.php';
?>

<main class="bg-gray-50 overflow-x-hidden">
    <!-- Hero Section - Moderne SaaS versie met nieuwste blog -->
    <section class="hero-section relative min-h-screen bg-gradient-to-br from-primary-dark via-primary to-secondary overflow-hidden">
        
        <!-- Moderne achtergrond met subtiele effecten -->
        <div class="absolute inset-0 z-0">
            <!-- Subtiele pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
            
            <!-- Ambient light effects -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            </div>
            
        <!-- Floating partij logos -->
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
            
            // Partijen configuratie met kleuren - posities worden random gegenereerd
            $floatingPartijen = [
                ['naam' => 'VVD', 'kleur' => '#FF9900', 'logo' => $partyLogos['VVD'], 'delay' => '0s', 'duration' => '20s', 'size' => 'large'],
                ['naam' => 'PVV', 'kleur' => '#0078D7', 'logo' => $partyLogos['PVV'], 'delay' => '3s', 'duration' => '22s', 'size' => 'large'],
                ['naam' => 'GL-PvdA', 'kleur' => '#008800', 'logo' => $partyLogos['GL-PvdA'], 'delay' => '6s', 'duration' => '24s', 'size' => 'large'],
                ['naam' => 'CDA', 'kleur' => '#1E8449', 'logo' => $partyLogos['CDA'], 'delay' => '2s', 'duration' => '18s', 'size' => 'medium'],
                ['naam' => 'D66', 'kleur' => '#00B13C', 'logo' => $partyLogos['D66'], 'delay' => '4s', 'duration' => '26s', 'size' => 'medium'],
                ['naam' => 'SP', 'kleur' => '#EE0000', 'logo' => $partyLogos['SP'], 'delay' => '7s', 'duration' => '19s', 'size' => 'small'],
                ['naam' => 'PvdD', 'kleur' => '#006400', 'logo' => $partyLogos['PvdD'], 'delay' => '8s', 'duration' => '21s', 'size' => 'small'],
                ['naam' => 'Volt', 'kleur' => '#800080', 'logo' => $partyLogos['Volt'], 'delay' => '10s', 'duration' => '25s', 'size' => 'small'],
                ['naam' => 'JA21', 'kleur' => '#4B0082', 'logo' => $partyLogos['JA21'], 'delay' => '12s', 'duration' => '27s', 'size' => 'small'],
                ['naam' => 'SGP', 'kleur' => '#ff7f00', 'logo' => $partyLogos['SGP'], 'delay' => '14s', 'duration' => '29s', 'size' => 'small']
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
        
        <!-- Main content container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="flex items-center justify-center min-h-screen py-20">
                
                <!-- Modern hero layout met nieuwste blog -->
                <div class="max-w-7xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-8 sm:gap-10 lg:gap-16 items-center">
                        
                        <!-- Links: Hoofdcontent -->
                        <div class="text-left space-y-8">
                            <!-- Header badge -->
                            <div class="hidden sm:inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                                <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                                <span class="text-white/90 text-sm font-medium">De nieuwste politieke insights</span>
                            </div>
                            
                            <!-- Hoofdtitel -->
                            <div class="space-y-6">
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight">
                                    <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent mb-2">
                                PolitiekPraat
                            </span>
                                    <span class="block text-white/95 text-2xl sm:text-3xl lg:text-4xl font-light">
                                Jouw politieke kompas
                            </span>
                        </h1>
                    
                                <p class="text-lg sm:text-xl text-white/80 leading-relaxed max-w-lg">
                                    Ontdek de Nederlandse politiek via diepgaande analyses, actueel nieuws en interactieve tools.
                                </p>
                            </div>
                            
                            <!-- Stats cards -->
                            <div class="grid grid-cols-3 gap-4 max-w-md">
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                                    <div class="text-2xl font-bold text-white">150+</div>
                                    <div class="text-sm text-white/70">Blogs</div>
                            </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                                    <div class="text-2xl font-bold text-white">15</div>
                                    <div class="text-sm text-white/70">Partijen</div>
                            </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                                    <div class="text-2xl font-bold text-white">25</div>
                                    <div class="text-sm text-white/70">Tools</div>
                        </div>
                    </div>
                    
                            <!-- CTA buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="<?php echo URLROOT; ?>/blogs" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary font-bold rounded-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                                    <span>Ontdek onze Blogs</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                                <a href="<?php echo URLROOT; ?>/partijmeter" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 hover:border-white/50 transition-all duration-300">
                                    Start PartijMeter
                                </a>
                                </div>
                            </div>
                            
                        <!-- Rechts: Nieuwste blog post -->
                        <div class="order-last lg:order-last">
                            <!-- Header voor blog sectie -->
                            <div class="text-center mb-6 lg:mb-8">
                                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-3">
                                    <div class="w-2 h-2 bg-secondary-light rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-white/90 text-sm font-medium">Nieuwste Artikel</span>
                                </div>
                            </div>
                            
                            <?php if ($latestBlog): ?>
                                <div class="relative bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border border-white/20 hover:shadow-3xl transition-all duration-500 transform hover:scale-105 
                                           max-w-sm mx-auto lg:max-w-none lg:mx-0 before:absolute before:inset-0 before:bg-gradient-to-br before:from-primary/5 before:via-transparent before:to-secondary/5 before:opacity-0 hover:before:opacity-100 before:transition-opacity before:duration-500">
                                    
                                    <!-- NIEUW badge -->
                                    <div class="absolute top-4 right-4 z-20">
                                        <div class="relative bg-gradient-to-r from-secondary to-primary text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary rounded-full animate-ping opacity-75"></div>
                                            <span class="relative">NIEUW</span>
                                        </div>
                                    </div>
                                    <!-- Blog afbeelding -->
                                    <?php if (!empty($latestBlog->image_path)): ?>
                                        <div class="aspect-video overflow-hidden relative">
                                            <img src="<?php echo URLROOT . '/' . $latestBlog->image_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($latestBlog->title); ?>"
                                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                            
                                            <!-- Gradient overlay voor betere tekst leesbaarheid -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Blog content -->
                                    <div class="p-4 sm:p-6 lg:p-8">
                                        <!-- Categorie badge -->
                                        <?php if (!empty($latestBlog->category_name)): ?>
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mb-4"
                                                 style="background-color: <?php echo $latestBlog->category_color ?? '#3B82F6'; ?>20; color: <?php echo $latestBlog->category_color ?? '#3B82F6'; ?>;">
                                                <?php if (!empty($latestBlog->category_icon)): ?>
                                                    <span class="mr-1"><?php echo $latestBlog->category_icon; ?></span>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($latestBlog->category_name); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Titel -->
                                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-3 lg:mb-4 leading-tight">
                                            <?php echo htmlspecialchars($latestBlog->title); ?>
                                        </h3>
                                        
                                        <!-- Samenvatting -->
                                        <p class="text-sm sm:text-base text-gray-600 mb-4 lg:mb-6 leading-relaxed">
                                            <?php echo htmlspecialchars(substr($latestBlog->summary ?? '', 0, 150)) . '...'; ?>
                                        </p>
                                        
                                        <!-- Meta info -->
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-6 pt-4 border-t border-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <!-- Datum -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span><?php echo date('d M Y', strtotime($latestBlog->published_at ?? date('Y-m-d'))); ?></span>
                                                </div>
                                                
                                                <!-- Auteur -->
                                                <?php if (!empty($latestBlog->author_name)): ?>
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        <span><?php echo htmlspecialchars($latestBlog->author_name); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Leestijd -->
                                            <div class="flex items-center bg-gray-50 rounded-full px-2 py-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="font-medium">5 min</span>
                                            </div>
                                        </div>

                            </div>
                            
                                        <!-- Lees meer button -->
                                        <a href="<?php echo URLROOT; ?>/blogs/<?php echo $latestBlog->slug; ?>" 
                                           class="group relative inline-flex items-center justify-center w-full px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-primary-dark via-primary to-secondary text-white text-sm sm:text-base font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 overflow-hidden">
                                            
                                            <!-- Shimmer effect -->
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                            
                                            <span class="relative z-10">Lees het volledige artikel</span>
                                            <svg class="relative z-10 w-4 h-4 sm:w-5 sm:h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>
                                </div>
                            </div>
                            <?php else: ?>
                                <!-- Fallback indien geen blog beschikbaar -->
                                <div class="relative bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-4 sm:p-6 lg:p-8 border border-white/20 max-w-sm mx-auto lg:max-w-none lg:mx-0 before:absolute before:inset-0 before:bg-gradient-to-br before:from-primary/5 before:via-transparent before:to-secondary/5 before:opacity-50">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nieuwe content komt eraan!</h3>
                                        <p class="text-gray-600 mb-6">We werken hard aan nieuwe politieke analyses en inzichten.</p>
                                        <a href="<?php echo URLROOT; ?>/blogs" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors duration-300">
                                            Bekijk alle blogs
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Verkiezingen Countdown Timer -->
    <section class="election-countdown-section py-20 md:py-28">
        <!-- Achtergrond effecten -->
        <div class="countdown-background-pattern"></div>
        <div class="countdown-glow"></div>
        
        <div class="countdown-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16 space-y-6">
                <div class="inline-flex items-center justify-center">
                    <div class="countdown-date-badge">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>29 Oktober 2025</span>
                    </div>
                </div>
                
                <h2 class="countdown-title">
                    Verkiezingen 2025
                </h2>
                
                <p class="countdown-subtitle">
                    Nog maar even tot de Nederlandse landelijke verkiezingen
                </p>
            </div>

            <!-- Countdown Grid -->
            <div class="countdown-grid mb-12">
                <div class="countdown-item">
                    <div class="text-center">
                        <span id="countdown-days" class="countdown-number">0</span>
                        <div class="countdown-label">Dagen</div>
                    </div>
                </div>
                
                <div class="countdown-item">
                    <div class="text-center">
                        <span id="countdown-hours" class="countdown-number">0</span>
                        <div class="countdown-label">Uren</div>
                    </div>
                </div>
                
                <div class="countdown-item">
                    <div class="text-center">
                        <span id="countdown-minutes" class="countdown-number">0</span>
                        <div class="countdown-label">Minuten</div>
                    </div>
                </div>
                
                <div class="countdown-item">
                    <div class="text-center">
                        <span id="countdown-seconds" class="countdown-number">0</span>
                        <div class="countdown-label">Seconden</div>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="text-center">
                <a href="<?php echo URLROOT; ?>/stemwijzer" class="countdown-cta">
                    <span>Bereid je voor</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
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

                                <a href="<?php echo URLROOT . '/blogs/' . $blog->slug; ?>" class="block relative h-full">
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
                                                        <?php
                                                        $profilePhotoData = getProfilePhotoUrl($blog->author_photo ?? null, $blog->author_name);
                                                        if ($profilePhotoData['type'] === 'img'): ?>
                                                            <img src="<?php echo htmlspecialchars($profilePhotoData['value']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                                 class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary text-white font-bold text-sm">
                                                                <?php echo htmlspecialchars($profilePhotoData['value']); ?>
                                                            </div>
                                                        <?php endif; ?>
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

    <!-- Amerikaanse Verkiezingen Section -->
    <section class="py-32 bg-gradient-to-br from-blue-900 via-red-900 to-blue-900 relative overflow-hidden">
        <!-- Amerikaanse achtergrond decoraties -->
        <div class="absolute inset-0">
            <!-- Stars pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDEwTDIzIDIwTDMwIDIwTDI0IDI2TDI3IDM2TDIwIDMwTDEzIDM2TDE2IDI2TDEwIDIwTDE3IDIwTDIwIDEwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIi8+PC9zdmc+')] opacity-20"></div>
            
            <!-- Red and blue stripes -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 via-white/20 to-blue-600 opacity-30"></div>
            <div class="absolute bottom-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 via-white/20 to-red-600 opacity-30"></div>
            
            <!-- Floating elements -->
            <div class="absolute top-20 left-10 w-32 h-32 bg-red-600/10 rounded-full blur-2xl animate-float"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-blue-600/10 rounded-full blur-xl animate-float-delayed"></div>
            <div class="absolute bottom-32 left-1/4 w-40 h-40 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Header sectie -->
            <div class="text-center mb-20 relative" data-aos="fade-up" data-aos-once="true">
                <!-- Achtergrond tekst -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                    <span class="text-[60px] sm:text-[80px] md:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-white/10 select-none tracking-wider">USA</span>
                </div>
                
                <!-- Main content -->
                <div class="relative z-10 space-y-8">
                    <!-- Hoofdtitel -->
                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-white leading-tight tracking-tight">
                        <span class="bg-gradient-to-r from-red-400 via-white to-blue-400 bg-clip-text text-transparent animate-gradient bg-size-200">
                            Amerikaanse Verkiezingen
                        </span>
                    </h2>
                    
                    <!-- Decoratieve lijn systeem -->
                    <div class="flex items-center justify-center space-x-4 sm:space-x-6 mt-8">
                        <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-transparent via-red-500 to-red-600"></div>
                        <div class="relative">
                            <div class="w-3 sm:w-4 h-3 sm:h-4 bg-white rounded-full animate-pulse"></div>
                            <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-white rounded-full animate-ping opacity-30"></div>
                        </div>
                        <div class="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-red-600 via-white to-blue-600"></div>
                        <div class="relative">
                            <div class="w-3 sm:w-4 h-3 sm:h-4 bg-blue-500 rounded-full animate-pulse animation-delay-300"></div>
                            <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-500 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                        </div>
                        <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-blue-600 via-blue-500 to-transparent"></div>
                    </div>
                    
                    <!-- Subtitel -->
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed font-light">
                        Ontdek de <span class="font-semibold text-red-300">geschiedenis</span> van Amerikaanse presidentsverkiezingen en hun <span class="font-semibold text-blue-300">impact</span> op de wereld
                    </p>
                </div>
            </div>

            <!-- Verkiezingen timeline -->
            <?php if (!empty($amerikaanse_verkiezingen)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="200">
                <?php foreach($amerikaanse_verkiezingen as $index => $verkiezing): ?>
                <div class="group relative bg-white/10 backdrop-blur-sm rounded-3xl overflow-hidden border border-white/20 hover:border-white/40 transition-all duration-700 hover:transform hover:-translate-y-2 hover:shadow-2xl" 
                     data-aos="fade-up" 
                     data-aos-delay="<?php echo 300 + ($index * 150); ?>">
                    
                    <!-- Top accent -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 via-white to-blue-500"></div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <!-- Winnaar foto en jaar -->
                        <div class="flex items-center space-x-4 mb-6">
                            <!-- Winnaar foto -->
                            <?php if (!empty($verkiezing->winnaar_foto_url)): ?>
                            <div class="relative">
                                <img src="<?php echo htmlspecialchars($verkiezing->winnaar_foto_url); ?>" 
                                     alt="<?php echo htmlspecialchars($verkiezing->winnaar); ?>"
                                     class="w-16 h-16 rounded-full object-cover border-4 border-white/30 shadow-xl">
                                <!-- Partij indicator -->
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 <?php echo $verkiezing->winnaar_partij == 'Republican' ? 'bg-red-500' : 'bg-blue-500'; ?> rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-bold"><?php echo $verkiezing->winnaar_partij == 'Republican' ? 'R' : 'D'; ?></span>
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Fallback badge als er geen foto is -->
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-600 to-blue-600 rounded-full shadow-xl">
                                <span class="text-white font-black text-lg"><?php echo substr($verkiezing->jaar, -2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Jaar badge -->
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full">
                                    <span class="text-white font-bold text-sm"><?php echo $verkiezing->jaar; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Winnaar info -->
                        <div class="space-y-4 mb-6">
                            <h3 class="text-xl font-bold text-white line-clamp-2">
                                <?php echo htmlspecialchars($verkiezing->winnaar); ?>
                            </h3>
                            
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 <?php echo $verkiezing->winnaar_partij == 'Republican' ? 'bg-red-500' : 'bg-blue-500'; ?> rounded-full"></div>
                                <span class="text-white/80 text-sm font-medium">
                                    <?php echo htmlspecialchars($verkiezing->winnaar_partij); ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Statistics -->
                        <div class="space-y-3">
                            <!-- Kiesmannen -->
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-sm">Kiesmannen</span>
                                <span class="text-white font-bold"><?php echo $verkiezing->winnaar_kiesmannen; ?>/<?php echo $verkiezing->totaal_kiesmannen; ?></span>
                            </div>
                            
                            <!-- Populaire stem percentage -->
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-sm">Populaire stem</span>
                                <span class="text-white font-bold"><?php echo number_format($verkiezing->winnaar_percentage_populair, 1); ?>%</span>
                            </div>
                            
                            <!-- Progress bar voor kiesmannen -->
                            <div class="mt-4">
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="<?php echo $verkiezing->winnaar_partij == 'Republican' ? 'bg-red-500' : 'bg-blue-500'; ?> h-2 rounded-full transition-all duration-1000" 
                                         style="width: <?php echo ($verkiezing->winnaar_kiesmannen / $verkiezing->totaal_kiesmannen) * 100; ?>%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-white/60 mt-1">
                                    <span>0</span>
                                    <span><?php echo $verkiezing->totaal_kiesmannen; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hover overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Placeholder als er geen data is -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 rounded-full mb-6">
                    <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-4">Verkiezingsdata wordt geladen</h3>
                <p class="text-white/70">Amerikaanse verkiezingsgegevens zijn momenteel niet beschikbaar.</p>
            </div>
            <?php endif; ?>

            <!-- CTA sectie -->
            <div class="text-center mt-20" data-aos="fade-up" data-aos-delay="600">
                <a href="<?php echo URLROOT; ?>/amerikaanse-verkiezingen" 
                   class="relative inline-flex items-center px-12 py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-lg rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                    
                    <!-- Button content -->
                    <div class="relative z-10 flex items-center">
                        <span class="mr-3">Ontdek alle verkiezingen</span>
                        <svg class="w-6 h-6 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                    
                    <!-- Shimmer effect -->
                    <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                </a>
                
                <!-- Supporting stats -->
                <?php if (!empty($amerikaanse_verkiezingen)): ?>
                <p class="mt-6 text-blue-200 text-sm">
                    <span class="font-semibold text-white"><?php echo count($amerikaanse_verkiezingen); ?></span> verkiezingen weergegeven • 
                    <span class="font-semibold text-white">Sinds 1789</span> beschikbaar
                </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

            <!-- PartijMeter Call-to-Action Section -->
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
                                <a href="<?php echo URLROOT; ?>/partijmeter" 
                                   class="relative inline-flex items-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-sm sm:text-base md:text-lg lg:text-xl rounded-xl sm:rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                                    
                                    <!-- Button content -->
                                    <div class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="mr-2 sm:mr-3">PartijMeter 2025 – Gratis Online Politieke Test!</span>
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
                                Gratis online stemtest • <span class="font-semibold text-blue-600"><?php echo $totalQuestions ?? '30'; ?></span> politieke thema's • <span class="font-semibold text-red-600">14 Nederlandse partijen</span> • Verkiezingen 2025
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
                                            <h3 class="text-lg font-bold text-white">PolitiekPraat PartijMeter</h3>
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

     <!-- Nederlandse Verkiezingen Section -->
    <section class="py-32 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 relative overflow-hidden">
        <!-- Nederlandse achtergrond decoraties -->
        <div class="absolute inset-0">
            <!-- Nederlandse kroon pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTUgMTZMMTIgMTJoNS41bC0uNS00aDRMMTEgMTJoNmwtMiA0em03LjUtOEwxMiA0bC41IDR6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+PC9zdmc+')] opacity-30"></div>
            
            <!-- Nederlandse accent stripes -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500/60 via-transparent to-blue-500/60 opacity-40"></div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500/60 via-transparent to-orange-500/60 opacity-40"></div>
            
            <!-- Floating elements met betere contrast -->
            <div class="absolute top-20 left-10 w-32 h-32 bg-orange-500/20 rounded-full blur-2xl animate-float"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-blue-500/20 rounded-full blur-xl animate-float-delayed"></div>
            <div class="absolute bottom-32 left-1/4 w-40 h-40 bg-slate-600/30 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Header sectie -->
            <div class="text-center mb-20 relative" data-aos="fade-up" data-aos-once="true">
                <!-- Achtergrond tekst -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                    <span class="text-[60px] sm:text-[80px] md:text-[100px] lg:text-[120px] xl:text-[160px] font-black text-white/10 select-none tracking-wider">NL</span>
                </div>
                
                <!-- Main content -->
                <div class="relative z-10 space-y-8">
                    <!-- Nederlandse kroon decoratie -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/20">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Hoofdtitel -->
                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black leading-tight tracking-tight">
                        <span class="bg-gradient-to-r from-orange-400 via-orange-300 to-blue-400 bg-clip-text text-transparent animate-gradient bg-size-200">
                            Nederlandse Verkiezingen
                        </span>
                    </h2>
                    
                    <!-- Decoratieve lijn systeem met Nederlandse kleuren -->
                    <div class="flex items-center justify-center space-x-4 sm:space-x-6 mt-8">
                        <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-transparent via-orange-400 to-orange-500"></div>
                        <div class="relative">
                            <div class="w-3 sm:w-4 h-3 sm:h-4 bg-orange-400 rounded-full animate-pulse"></div>
                            <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-orange-400 rounded-full animate-ping opacity-30"></div>
                        </div>
                        <div class="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-orange-500 via-slate-300 to-blue-500"></div>
                        <div class="relative">
                            <div class="w-3 sm:w-4 h-3 sm:h-4 bg-blue-400 rounded-full animate-pulse animation-delay-300"></div>
                            <div class="absolute inset-0 w-3 sm:w-4 h-3 sm:h-4 bg-blue-400 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                        </div>
                        <div class="w-12 sm:w-16 h-0.5 bg-gradient-to-r from-blue-500 via-blue-400 to-transparent"></div>
                    </div>
                    
                    <!-- Subtitel -->
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-200 max-w-4xl mx-auto leading-relaxed font-light">
                        Ontdek 175 jaar <span class="font-semibold text-orange-300">Nederlandse democratie</span> en hun <span class="font-semibold text-blue-300">invloed</span> op de samenleving
                    </p>
                </div>
            </div>

            <!-- Nederlandse verkiezingen timeline -->
            <?php if (!empty($nederlandse_verkiezingen)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="200">
                <?php foreach($nederlandse_verkiezingen as $index => $verkiezing): ?>
                <div class="group relative bg-slate-800/80 backdrop-blur-sm rounded-3xl overflow-hidden border border-slate-600/40 hover:border-orange-400/60 transition-all duration-700 hover:transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-500/20" 
                     data-aos="fade-up" 
                     data-aos-delay="<?php echo 300 + ($index * 150); ?>">
                    
                    <!-- Top accent met Nederlandse kleuren -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 via-orange-300 to-blue-500"></div>
                    
                    <!-- Nederlandse vlag corner -->
                    <div class="absolute top-3 right-3 w-8 h-6 rounded-sm opacity-40 group-hover:opacity-70 transition-opacity duration-300">
                        <div class="w-full h-2 bg-orange-500"></div>
                        <div class="w-full h-2 bg-slate-200"></div>
                        <div class="w-full h-2 bg-blue-600"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <!-- Jaar en grootste partij -->
                        <div class="flex items-center space-x-4 mb-6">
                            <!-- Jaar badge -->
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-blue-600 rounded-full shadow-xl">
                                <span class="text-white font-black text-lg"><?php echo substr($verkiezing->jaar, -2); ?></span>
                            </div>
                            
                            <!-- Verkiezing info -->
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 bg-slate-700/60 rounded-full border border-slate-600/40">
                                    <span class="text-slate-100 font-bold text-sm"><?php echo $verkiezing->jaar; ?></span>
                                </div>
                                <div class="text-xs text-orange-300 mt-1">Tweede Kamerverkiezing</div>
                            </div>
                        </div>
                        
                        <!-- Grootste partij info -->
                        <div class="space-y-4 mb-6">
                            <h3 class="text-xl font-bold text-slate-100 line-clamp-2">
                                <?php echo htmlspecialchars($verkiezing->grootste_partij ?? 'Onbekend'); ?>
                            </h3>
                            
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-orange-400 rounded-full"></div>
                                <span class="text-slate-300 text-sm font-medium">
                                    Grootste partij
                                </span>
                            </div>
                        </div>
                        
                        <!-- Statistics -->
                        <div class="space-y-3">
                            <!-- Zetels -->
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-sm">Zetels</span>
                                <span class="text-slate-100 font-bold"><?php echo $verkiezing->grootste_partij_zetels ?? 'N/A'; ?>/150</span>
                            </div>
                            
                            <!-- Percentage -->
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-sm">Stemmen</span>
                                <span class="text-slate-100 font-bold"><?php echo $verkiezing->grootste_partij_percentage ? number_format($verkiezing->grootste_partij_percentage, 1) . '%' : 'N/A'; ?></span>
                            </div>
                            
                            <!-- Minister-president -->
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-sm">Minister-president</span>
                                <span class="text-slate-100 font-bold text-xs"><?php echo htmlspecialchars($verkiezing->minister_president ?? 'N/A'); ?></span>
                            </div>
                            
                            <!-- Opkomst -->
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-sm">Opkomst</span>
                                <span class="text-slate-100 font-bold"><?php echo $verkiezing->opkomst_percentage ? number_format($verkiezing->opkomst_percentage, 1) . '%' : 'N/A'; ?></span>
                            </div>
                            
                            <!-- Progress bar voor zetels -->
                            <?php if ($verkiezing->grootste_partij_zetels): ?>
                            <div class="mt-4">
                                <div class="w-full bg-slate-700/60 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-2 rounded-full transition-all duration-1000" 
                                         style="width: <?php echo ($verkiezing->grootste_partij_zetels / 150) * 100; ?>%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-slate-400 mt-1">
                                    <span>0</span>
                                    <span>150</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Hover overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Placeholder als er geen data is -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-slate-700/60 rounded-full mb-6 border border-slate-600/40">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-100 mb-4">Nederlandse verkiezingsdata wordt geladen</h3>
                <p class="text-slate-400">Nederlandse verkiezingsgegevens zijn momenteel niet beschikbaar.</p>
            </div>
            <?php endif; ?>

            <!-- Nederlandse CTA sectie met twee buttons -->
            <div class="text-center mt-20" data-aos="fade-up" data-aos-delay="600">
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center items-center">
                    <!-- Nederlandse verkiezingen button -->
                    <a href="<?php echo URLROOT; ?>/nederlandse-verkiezingen" 
                       class="relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 via-orange-400 to-orange-500 text-white font-bold text-lg rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/25 group overflow-hidden">
                        
                        <!-- Nederlandse kroon icon -->
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 12h5.5l-.5-4h4L11 12h6l-2 4zm7.5-8L12 4l.5 4z"/>
                        </svg>
                        
                        <!-- Button content -->
                        <div class="relative z-10 flex items-center">
                            <span class="mr-3">Nederlandse verkiezingen</span>
                            <svg class="w-5 h-5 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                        
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                    </a>
                    
                    <!-- Ministers-presidenten button -->
                    <a href="<?php echo URLROOT; ?>/nederlandse-verkiezingen/ministers-presidenten" 
                       class="relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 text-white font-bold text-lg rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-blue-500/25 group overflow-hidden">
                        
                        <!-- Person icon -->
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        
                        <!-- Button content -->
                        <div class="relative z-10 flex items-center">
                            <span class="mr-3">Ministers-presidenten</span>
                            <svg class="w-5 h-5 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                        
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                    </a>
                </div>
                
                <!-- Supporting stats -->
                <?php if (!empty($nederlandse_verkiezingen)): ?>
                <p class="mt-6 text-slate-300 text-sm">
                    <span class="font-semibold text-slate-100"><?php echo count($nederlandse_verkiezingen); ?></span> verkiezingen weergegeven • 
                    <span class="font-semibold text-slate-100">Sinds 1848</span> beschikbaar
                </p>
                <?php endif; ?>
                
                <!-- Nederlandse decorative elements -->
                <div class="flex items-center justify-center mt-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-orange-400 to-transparent"></div>
                    <div class="mx-4 flex space-x-1">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

                <!-- Politiek Kompas Section - Consistent met PartijMeter styling -->
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
                                <a href="<?php echo URLROOT; ?>/politiek-kompas" 
                                   class="relative inline-flex items-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-6 bg-gradient-to-r from-primary via-secondary to-primary text-white font-bold text-sm sm:text-base md:text-lg lg:text-xl rounded-xl sm:rounded-2xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl group overflow-hidden">
                                    
                                    <!-- Button content -->
                                    <div class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <span class="mr-2 sm:mr-3">Ontdek je politieke kompas</span>
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
                                            <h3 class="text-lg font-bold text-white">Politiek Kompas</h3>
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
                                        <span class="hidden sm:inline">Peilingdatum: 26 oktober 2025</span>
                                            <span class="sm:hidden">26 oktober 2025</span>
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


