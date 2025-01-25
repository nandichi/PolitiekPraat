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

// Haal de laatste 5 blogs op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 5");
$latest_blogs = $db->resultSet();

// Haal het laatste nieuws op
$latest_news = array_slice($newsAPI->getLatestPoliticalNews(), 0, 5);

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
                                            Laatste update: <?php echo date('d M Y', strtotime($latestPolls['last_updated'])); ?>
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
                                            <?php echo $latestPolls['polls'][0]['bureau']; ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Partijen Grid -->
                                    <div class="space-y-2.5">
                                        <?php foreach($latestPolls['polls'][0]['parties'] as $party => $data): ?>
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

    <!-- Laatste Nieuws & Blogs Grid -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Laatste Nieuws -->
                <div class="h-full" data-aos="fade-right">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Laatste Politiek Nieuws</h2>
                        <p class="text-gray-600">Blijf op de hoogte van de laatste ontwikkelingen</p>
                    </div>
                    <div class="space-y-6 h-full">
                        <?php foreach($latest_news as $index => $news): 
                            $source_domains = [
                                'NOS' => 'nos.nl',
                                'NU.nl' => 'nu.nl',
                                'RTL Nieuws' => 'rtlnieuws.nl',
                                'AD' => 'ad.nl',
                                'Volkskrant' => 'volkskrant.nl',
                                'NRC' => 'nrc.nl',
                                'Trouw' => 'trouw.nl'
                            ];
                            $domain = isset($source_domains[$news['source']]) ? $source_domains[$news['source']] : parse_url($news['url'], PHP_URL_HOST);
                            $favicon_url = "https://www.google.com/s2/favicons?domain=" . $domain . "&sz=32";
                        ?>
                            <article class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:-translate-y-1 flex flex-col h-[220px]" 
                                     data-aos="fade-up" 
                                     data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="flex items-center mb-3">
                                    <div class="flex items-center flex-1">
                                        <img src="<?php echo $favicon_url; ?>" 
                                             alt="<?php echo htmlspecialchars($news['source']); ?> logo"
                                             class="w-6 h-6 object-contain mr-2">
                                        <span class="text-sm text-gray-500"><?php echo $news['source']; ?></span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($news['publishedAt'])); ?></span>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 hover:text-primary transition-colors">
                                    <?php echo $news['title']; ?>
                                </h3>
                                <p class="text-gray-600 mb-3 line-clamp-2 flex-grow">
                                    <?php echo $news['description']; ?>
                                </p>
                                <a href="<?php echo $news['url']; ?>" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center text-primary hover:text-secondary font-semibold mt-auto">
                                    Lees meer
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </article>
                        <?php endforeach; ?>
                        <div class="text-center mt-6">
                            <a href="<?php echo URLROOT; ?>/nieuws" 
                               class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all">
                                Bekijk al het nieuws
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laatste Blogs -->
                <div class="h-full" data-aos="fade-left">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Laatste Blogs</h2>
                        <p class="text-gray-600">Lees de meest recente politieke analyses</p>
                    </div>
                    <div class="space-y-6 h-full">
                        <?php foreach($latest_blogs as $index => $blog): ?>
                            <article class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1"
                                     data-aos="fade-up"
                                     data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="p-6 flex flex-col">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            <?php echo substr($blog->author_name, 0, 1); ?>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo $blog->author_name; ?></p>
                                            <p class="text-xs text-gray-500"><?php echo date('d M Y', strtotime($blog->published_at)); ?></p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-primary transition-colors">
                                        <?php echo $blog->title; ?>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-3 mb-4">
                                        <?php echo $blog->summary; ?>
                                    </p>
                                    <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                                       class="inline-flex items-center text-primary font-semibold hover:text-secondary transition-colors mt-auto">
                                        Lees meer
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                        <div class="text-center mt-6">
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all">
                                Bekijk alle blogs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Actuele Thema's Grid -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Actuele Thema's</h2>
                <p class="text-gray-600">De belangrijkste politieke onderwerpen van dit moment</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($actuele_themas as $index => $thema): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1"
                         data-aos="zoom-in"
                         data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="p-6">
                            <div class="text-4xl mb-4 animate-float"><?php echo $thema['icon']; ?></div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo $thema['title']; ?></h3>
                            <p class="text-gray-600"><?php echo $thema['description']; ?></p>
                            <a href="<?php echo URLROOT; ?>/themas/<?php echo strtolower(str_replace(' ', '-', $thema['title'])); ?>" 
                               class="inline-flex items-center mt-4 text-primary hover:text-secondary font-semibold">
                                Lees meer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Politieke Debatten -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Debatten</h2>
                <p class="text-gray-600">Volg de belangrijkste debatten in de Tweede Kamer</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach($debatten as $index => $debat): 
                    $is_upcoming = $debat['status'] === 'Aankomend';
                ?>
                    <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden"
                         data-aos="fade-up"
                         data-aos-delay="<?php echo $index * 150; ?>">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="<?php echo $is_upcoming ? 'bg-primary animate-pulse-slow' : 'bg-gray-500'; ?> text-white px-3 py-1 rounded-full text-sm">
                                    <?php echo $debat['status']; ?>
                                </span>
                                <span class="text-gray-500"><?php echo date('d M Y', strtotime($debat['datum'])); ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $debat['titel']; ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo $debat['beschrijving']; ?></p>
                            <a href="<?php echo URLROOT; ?>/debatten/<?php echo strtolower(str_replace(' ', '-', $debat['titel'])); ?>"
                               class="inline-flex items-center text-primary hover:text-secondary font-semibold">
                                Meer details
                                <svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Politieke Inzichten -->
    <section class="py-16 bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Inzichten</h2>
                <p class="text-gray-600">Ontdek de laatste trends en ontwikkelingen in de Nederlandse politiek</p>
            </div>
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Actuele Statistieken -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Actuele Statistieken</h3>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Kamervragen deze week</span>
                                    <span class="text-primary font-bold"><?php echo $kamerStats['kamervragen_deze_week']; ?></span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: <?php echo $kamerStats['kamervragen_percentage']; ?>%"></div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Moties ingediend</span>
                                    <span class="text-primary font-bold"><?php echo $kamerStats['moties_ingediend']; ?></span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: <?php echo $kamerStats['moties_percentage']; ?>%"></div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Wetsvoorstellen behandeld</span>
                                    <span class="text-primary font-bold"><?php echo $kamerStats['wetsvoorstellen_behandeld']; ?></span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: <?php echo $kamerStats['wetsvoorstellen_percentage']; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trending Onderwerpen -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Trending Onderwerpen</h3>
                            <div class="space-y-4">
                                <div class="flex items-center p-3 bg-primary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Klimaatbeleid</p>
                                        <p class="text-sm text-gray-600">247 discussies deze week</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-secondary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center text-secondary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Woningmarkt</p>
                                        <p class="text-sm text-gray-600">189 discussies deze week</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-primary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Migratie</p>
                                        <p class="text-sm text-gray-600">178 discussies deze week</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="<?php echo URLROOT; ?>/trends" 
                                   class="inline-flex items-center text-primary hover:text-secondary font-semibold">
                                    Bekijk alle trends
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Politieke Agenda -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Agenda</h2>
                <p class="text-gray-600">Belangrijke politieke gebeurtenissen en mijlpalen</p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6">
                    <?php foreach($agenda_items as $item): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($item['datum'])); ?></span>
                                        <h3 class="text-xl font-bold text-gray-900"><?php echo $item['titel']; ?></h3>
                                    </div>
                                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm">
                                        <?php echo $item['type']; ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <?php echo $item['locatie']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                            <a href="<?php echo URLROOT; ?>/auth/register" 
                               class="block text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                Registreer nu
                            </a>
                            <a href="<?php echo URLROOT; ?>/auth/login" 
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