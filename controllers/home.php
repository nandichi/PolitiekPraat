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
        'source' => 'De Volkskrant',
        'bias' => 'links',
        'publishedAt' => date('Y-m-d H:i:s'), // Vandaag 08:30
        'title' => 'De Europese ‘Patriotten’ van Orbán, Le Pen en Wilders stralen vooral op de familiefoto’s veel gebundelde kracht uit"',
        'description' => 'De ‘Patriotten voor Europa’ komen dit weekeinde bij elkaar in Madrid. Onder anderen Geert Wilders en Marine Le Pen zullen spreken op dit partijcongres voor radicaal-rechts. De nieuwe Europese familie werd vorig jaar opgericht om Europa ‘van binnenuit te slopen’, al komt daar nog maar weinig van terecht.',
        'url' => 'https://www.volkskrant.nl/buitenland/de-europese-patriotten-van-orban-le-pen-en-wilders-stralen-vooral-op-de-familiefoto-s-veel-gebundelde-kracht-uit~bf074d80/'
    ],
    [
        'orientation' => 'links',
        'source' => 'NRC',
        'bias' => 'centrum-links',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')), // 2 uur geleden
        'title' => 'Tweede Kamer moet de 1,3 miljard om btw-verhoging op boeken, sport, cultuur en media te voorkomen zelf vinden van het kabinet',
        'description' => 'Staatssecretaris Van Oostenbruggen (Fiscaliteit) presenteert drie alternatieven om de voorgenomen btw-verhoging terug te draaien. De Tweede Kamer is nu aan zet.',
        'url' => 'https://www.nrc.nl/nieuws/2025/02/07/tweede-kamer-moet-de-13-miljard-om-btw-verhoging-op-boeken-sport-cultuur-en-media-te-voorkomen-zelf-vinden-van-het-kabinet-a4882409'
    ],
    [
        'orientation' => 'links',
        'source' => 'Trouw',
        'bias' => 'centrum-links',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-4 hours')), // 4 uur geleden
        'title' => 'Kan een nieuwe linkse partij de gematigde kiezer terugwinnen?',
        'description' => 'De PvdA en GroenLinks hebben een nieuwe linkse partij nodig, zeggen politicollega’s. Maar deze partij moet ook de gematigde kiezer terugwinnen, zeggen andere politicollega’s.',
        'url' => 'https://www.trouw.nl/politiek/kan-een-nieuwe-linkse-partij-de-gematigde-kiezer-terugwinnen~bb43f70b/'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Telegraaf',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-6 hours')), // 6 uur geleden
        'title' => 'Trump pakt Biden toegang tot gevoelige informatie af: "Joe, je bent ontslagen"',
        'description' => 'De Amerikaanse president heeft zijn voormalige vicepresident, Biden, toegang tot gevoelige informatie afgewezen. "Joe, je bent ontslagen", schreef Trump op Twitter.',
        'url' => 'https://www.telegraaf.nl/nieuws/16012582/live-trump-pakt-biden-toegang-tot-gevoelige-informatie-af-joe-je-bent-ontslagen'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'AD',
        'bias' => 'centrum-rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-8 hours')), // 8 uur geleden
        'title' => 'Trump valt ICC aan met sancties: "Risico bestaat dat Strafhof straks niet meer kan functioneren"',
        'description' => 'Een aanval op de internationale rechtsorde. Dat zeggen vooraanstaande juristen en politici over het besluit van de Amerikaanse president Trump om sancties in te stellen tegen medewerkers van het Internationaal Strafhof (ICC) in Den Haag. Wat zijn de gevolgen van Trumps besluit? ,Straffeloosheid ligt op de loer. ',
        'url' => 'https://www.ad.nl/wonen'
    ],
    [
        'orientation' => 'rechts',
        'source' => 'Reformatorisch Dagblad',
        'bias' => 'rechts',
        'publishedAt' => date('Y-m-d H:i:s', strtotime('-10 hours')), // 10 uur geleden
        'title' => 'Premier Schoof en Faber (Asiel) niet eenduidig over uit te brengen advies Raad van State',
        'description' => 'https://www.rd.nl/artikel/1095099-premier-schoof-en-faber-asiel-niet-eenduidig-over-uit-te-brengen-advies-raad-van-state',
        'url' => 'https://www.rd.nl/artikel/1095099-premier-schoof-en-faber-asiel-niet-eenduidig-over-uit-te-brengen-advies-raad-van-state'
    ]
];

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

require_once 'views/templates/header.php';
?>

<main class="bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary overflow-hidden">
        <!-- Animated background pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>
        
        <div class="container mx-auto px-4 py-20 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Column: Main Content -->
                    <div class="text-white space-y-8" data-aos="fade-right">
                        <h1 class="text-5xl md:text-7xl font-bold leading-tight">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
                                Politiek voor iedereen
                            </span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-300 leading-relaxed">
                            Ontdek, discussieer en draag bij aan het politieke debat in Nederland. 
                            Jouw stem telt in de democratische dialoog.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="inline-flex items-center justify-center bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-lg group">
                                <span>Ontdek onze blogs</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            <a href="<?php echo URLROOT; ?>/forum" 
                               class="inline-flex items-center justify-center bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-all group">
                                <span>Ga naar het forum</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Live Peilingen -->
                    <div class="relative max-w-2xl mx-auto lg:max-w-none" data-aos="fade-left">
                        <!-- Glassmorphism Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-800/90 via-slate-900/90 to-slate-800/90 rounded-xl backdrop-blur-md"></div>
                        
                        <!-- Main Content -->
                        <div class="relative bg-white/5 p-4 sm:p-6 rounded-xl border border-white/10">
                            <!-- Dashboard Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-500/10 rounded-lg mr-3 ring-1 ring-blue-500/20">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white tracking-tight">Live Peilingen</h3>
                                        <p class="text-xs text-blue-300/80">
                                            Laatste update: <?php echo date('d M Y'); ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 ring-1 ring-green-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5 animate-pulse"></span>
                                    Live
                                </span>
                            </div>

                            <!-- Latest Polls Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                                <!-- Zetelverdeling Card -->
                                <div class="bg-gradient-to-br from-white/[0.04] to-white/[0.02] rounded-lg p-4 border border-white/10 hover:border-blue-500/20 transition-colors shadow-lg shadow-black/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-medium text-white flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Laatste Peiling
                                        </h4>
                                        <span class="text-xs text-blue-300/80 font-medium px-2 py-0.5 rounded-full bg-blue-500/5 ring-1 ring-blue-500/20">
                                            I&O Research
                                        </span>
                                    </div>
                                    
                                    <!-- Partijen Accordion -->
                                    <div class="space-y-4">
                                        <!-- Grote Partijen (Altijd zichtbaar) -->
                                        <?php 
                                        $topParties = [
                                            'pvv' => ['seats' => 39, 'percentage' => 26.0],
                                            'gl-pvda' => ['seats' => 25, 'percentage' => 16.7],
                                            'vvd' => ['seats' => 23, 'percentage' => 15.3],
                                            'd66' => ['seats' => 12, 'percentage' => 8.0]
                                        ];
                                        
                                        foreach($topParties as $party => $data): 
                                        ?>
                                            <div class="group">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div class="flex items-center">
                                                        <span class="text-sm text-white/90 font-medium"><?php echo strtoupper($party); ?></span>
                                                        <?php if(isset($latestPolls['trends'][$party])): ?>
                                                            <?php if($latestPolls['trends'][$party]['trend'] === 'up'): ?>
                                                                <div class="ml-1.5 px-1.5 py-0.5 rounded bg-green-500/10">
                                                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                                    </svg>
                                                                </div>
                                                            <?php elseif($latestPolls['trends'][$party]['trend'] === 'down'): ?>
                                                                <div class="ml-1.5 px-1.5 py-0.5 rounded bg-red-500/10">
                                                                    <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                                                    </svg>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-blue-300/80"><?php echo $data['percentage']; ?>%</span>
                                                        <span class="text-sm text-white font-bold bg-white/5 px-1.5 py-0.5 rounded">
                                                            <?php echo $data['seats']; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden ring-1 ring-white/10">
                                                    <div class="bg-gradient-to-r from-blue-500 to-blue-400 h-full rounded-full transition-all transform origin-left scale-x-100 group-hover:scale-x-105" 
                                                         style="width: <?php echo ($data['seats'] / 150) * 100; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Overige Partijen (Uitklapbaar) -->
                                        <div x-data="{ open: false }" class="mt-4">
                                            <button @click="open = !open" 
                                                    class="w-full flex items-center justify-between p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                                                <span class="text-sm text-white/90 font-medium">Toon overige partijen</span>
                                                <svg class="w-4 h-4 text-white/70 transform transition-transform" 
                                                     :class="{ 'rotate-180': open }"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                 class="mt-2 space-y-2">
                                                <?php 
                                                $otherParties = [
                                                    'bbb' => ['seats' => 6, 'percentage' => 4.0],
                                                    'cda' => ['seats' => 13, 'percentage' => 8.7],
                                                    'sp' => ['seats' => 7, 'percentage' => 4.7],
                                                    'denk' => ['seats' => 3, 'percentage' => 2.0],
                                                    'pvdd' => ['seats' => 6, 'percentage' => 4.0],
                                                    'fvd' => ['seats' => 3, 'percentage' => 2.0],
                                                    'sgp' => ['seats' => 3, 'percentage' => 2.0],
                                                    'cu' => ['seats' => 4, 'percentage' => 2.7],
                                                    'volt' => ['seats' => 3, 'percentage' => 2.0],
                                                    'ja21' => ['seats' => 1, 'percentage' => 0.7],
                                                    'nsc' => ['seats' => 2, 'percentage' => 1.3]
                                                ];
                                                
                                                foreach($otherParties as $party => $data): 
                                                ?>
                                                    <div class="group">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <div class="flex items-center">
                                                                <span class="text-sm text-white/90 font-medium"><?php echo strtoupper($party); ?></span>
                                                                <?php if(isset($latestPolls['trends'][$party])): ?>
                                                                    <?php if($latestPolls['trends'][$party]['trend'] === 'up'): ?>
                                                                        <div class="ml-1.5 px-1.5 py-0.5 rounded bg-green-500/10">
                                                                            <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                                            </svg>
                                                                        </div>
                                                                    <?php elseif($latestPolls['trends'][$party]['trend'] === 'down'): ?>
                                                                        <div class="ml-1.5 px-1.5 py-0.5 rounded bg-red-500/10">
                                                                            <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                                                            </svg>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-xs text-blue-300/80"><?php echo $data['percentage']; ?>%</span>
                                                                <span class="text-sm text-white font-bold bg-white/5 px-1.5 py-0.5 rounded">
                                                                    <?php echo $data['seats']; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden ring-1 ring-white/10">
                                                            <div class="bg-gradient-to-r from-blue-500 to-blue-400 h-full rounded-full transition-all transform origin-left scale-x-100 group-hover:scale-x-105" 
                                                                 style="width: <?php echo ($data['seats'] / 150) * 100; ?>%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trend Analysis -->
                                <div class="bg-gradient-to-br from-white/[0.04] to-white/[0.02] rounded-lg p-4 border border-white/10 hover:border-purple-500/20 transition-colors shadow-lg shadow-black/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-medium text-white flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            Trend Analyse
                                        </h4>
                                        <span class="text-xs text-purple-300/80 font-medium px-2 py-0.5 rounded-full bg-purple-500/5 ring-1 ring-purple-500/20">
                                            3 mnd
                                        </span>
                                    </div>
                                    
                                    <!-- Trend Cards -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <?php foreach($latestPolls['trends'] as $party => $trend): ?>
                                            <div class="bg-white/5 p-2 rounded-lg border border-white/10 hover:bg-white/[0.02] transition-colors">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs text-white/90 font-medium"><?php echo strtoupper($party); ?></span>
                                                    <div class="flex items-center">
                                                        <?php if($trend['trend'] === 'up'): ?>
                                                            <div class="flex items-center space-x-1 px-1.5 py-0.5 rounded bg-green-500/10">
                                                                <span class="text-xs text-green-400 font-medium">+<?php echo $trend['change']; ?></span>
                                                                <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                                </svg>
                                                            </div>
                                                        <?php elseif($trend['trend'] === 'down'): ?>
                                                            <div class="flex items-center space-x-1 px-1.5 py-0.5 rounded bg-red-500/10">
                                                                <span class="text-xs text-red-400 font-medium"><?php echo $trend['change']; ?></span>
                                                                <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                                                </svg>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="flex items-center space-x-1 px-1.5 py-0.5 rounded bg-yellow-500/10">
                                                                <span class="text-xs text-yellow-400 font-medium">0</span>
                                                                <svg class="w-3 h-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-white/5 text-center text-white/60">
                                                    <?php 
                                                    $trendText = $trend['trend'] === 'up' ? 'Stijgend' : ($trend['trend'] === 'down' ? 'Dalend' : 'Stabiel');
                                                    echo $trendText . ' trend';
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Poll Bureaus -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <?php foreach($latestPolls['polls'] as $poll): ?>
                                    <div class="bg-gradient-to-br from-white/[0.04] to-white/[0.02] rounded-lg p-3 border border-white/10 hover:border-indigo-500/20 transition-all duration-300 shadow-lg shadow-black/10">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-white font-medium"><?php echo $poll['bureau']; ?></span>
                                            <span class="text-[10px] text-indigo-300/80 font-medium px-1.5 py-0.5 rounded-full bg-indigo-500/5 ring-1 ring-indigo-500/20">
                                                <?php echo date('d M', strtotime($poll['date'])); ?>
                                            </span>
                                        </div>
                                        <div class="space-y-1.5">
                                            <?php 
                                            $topParties = array_slice($poll['parties'], 0, 3);
                                            foreach($topParties as $party => $data): 
                                            ?>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-white/80"><?php echo strtoupper($party); ?></span>
                                                    <span class="text-xs text-white font-medium bg-white/5 px-1.5 py-0.5 rounded">
                                                        <?php echo $data['seats']; ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Decorative Elements -->
                            <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-500/20 rounded-full blur-2xl"></div>
                            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-purple-500/20 rounded-full blur-2xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-auto" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="rgb(249 250 251)"/>
            </svg>
        </div>
    </section>

    <!-- Nieuwe Stemwijzer Highlight Section -->
    <section class="py-16 bg-gray-50">
        <!-- Decoratieve achtergrond elementen -->
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-primary/5 to-transparent"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-secondary/10 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative">
            <!-- NEW badge -->
            <div class="flex justify-center mb-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary/20 to-secondary/20 text-primary border border-primary/20 shadow-lg shadow-primary/10 transform hover:scale-105 transition-all duration-300 group">
                    <div class="flex space-x-2 items-center">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        <span class="text-sm font-bold tracking-wider group-hover:text-secondary transition-colors duration-300">NIEUW</span>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                <div class="relative p-8 md:p-12">
                    <!-- Decoratieve pattern -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(0,0,0,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-5"></div>

                    <div class="relative flex flex-col md:flex-row items-center gap-8">
                        <!-- Linker kolom met tekst -->
                        <div class="md:w-2/3 space-y-6">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                    Ontdek de Stemwijzer 2025
                                </span>
                            </h2>
                            <p class="text-lg text-gray-600 leading-relaxed">
                                Vind de partij die het beste bij jouw standpunten past met onze gloednieuwe stemwijzer. 
                                Beantwoord 25 belangrijke stellingen en krijg direct inzicht in jouw politieke voorkeuren.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Slechts 5-10 minuten</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>100% anoniem</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span>Direct resultaat</span>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/stemwijzer" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl 
                                      hover:opacity-90 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl group">
                                <span>Start de Stemwijzer</span>
                                <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Rechter kolom met illustratie -->
                        <div class="md:w-1/3 flex justify-center">
                            <div class="w-48 h-48 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center">
                                <svg class="w-24 h-24 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
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
                <div class="text-center mb-16 relative">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">BLOGS</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Laatste Blogs</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ontdek de meest recente politieke analyses en inzichten van onze community</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($latest_blogs as $index => $blog): ?>
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 relative">
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
                <div class="text-center mt-16">
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
            <div class="relative pt-16" data-aos="fade-up">
                <!-- Decoratieve elementen -->
                <div class="absolute -top-10 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>

                <div class="text-center mb-16 relative">
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
                    <div class="space-y-8">
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
                        foreach($links_news as $news):
                        ?>
                            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
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
                    <div class="space-y-8">
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
                        foreach($rechts_news as $news):
                        ?>
                            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
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