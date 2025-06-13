<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-24 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- Header badge -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Live nieuwsupdates</span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                        Politiek
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Nieuws
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                        Een gebalanceerd overzicht van het laatste nieuws, objectief gepresenteerd vanuit diverse politieke hoeken
                    </p>
                </div>
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo $stats['total_articles']; ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Totaal artikelen</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo $stats['progressive_count']; ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Progressief</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo $stats['conservative_count']; ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Conservatief</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>

    <!-- Premium Filter Section -->
    <section class="relative z-10 pb-12" id="artikelen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Container -->
            <div class="max-w-4xl mx-auto mb-16" data-aos="fade-up" data-aos-delay="200">
                <!-- Filter Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">Filter Perspectief</h2>
                    <p class="text-slate-600 max-w-2xl mx-auto">Ontdek nieuws vanuit verschillende politieke perspectieven en vorm je eigen mening</p>
                </div>
                
                <!-- Premium Filter Buttons -->
                <div class="relative p-2 bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50">
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <!-- Alle Bronnen -->
                        <a href="?filter=alle#artikelen" 
                           class="group relative px-8 py-4 rounded-2xl text-sm font-bold transition-all duration-500 transform hover:scale-105 flex items-center overflow-hidden <?php echo $filter === 'alle' ? 'bg-gradient-to-r from-slate-700 to-slate-900 text-white shadow-lg' : 'bg-white/50 text-slate-700 hover:bg-white hover:shadow-md'; ?>">
                            <!-- Background animation -->
                            <?php if($filter !== 'alle'): ?>
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-700 to-slate-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center <?php echo $filter !== 'alle' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span>Alle Bronnen</span>
                                <?php if($filter === 'alle'): ?>
                                <div class="ml-2 w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- Progressief -->
                        <a href="?filter=progressief#artikelen" 
                           class="group relative px-8 py-4 rounded-2xl text-sm font-bold transition-all duration-500 transform hover:scale-105 flex items-center overflow-hidden <?php echo $filter === 'progressief' ? 'bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg' : 'bg-white/50 text-slate-700 hover:bg-white hover:shadow-md'; ?>">
                            <!-- Background animation -->
                            <?php if($filter !== 'progressief'): ?>
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center <?php echo $filter !== 'progressief' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <span>Progressief</span>
                                <?php if($filter === 'progressief'): ?>
                                <div class="ml-2 w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- Conservatief -->
                        <a href="?filter=conservatief#artikelen" 
                           class="group relative px-8 py-4 rounded-2xl text-sm font-bold transition-all duration-500 transform hover:scale-105 flex items-center overflow-hidden <?php echo $filter === 'conservatief' ? 'bg-gradient-to-r from-orange-600 to-red-700 text-white shadow-lg' : 'bg-white/50 text-slate-700 hover:bg-white hover:shadow-md'; ?>">
                            <!-- Background animation -->
                            <?php if($filter !== 'conservatief'): ?>
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center <?php echo $filter !== 'conservatief' ? 'group-hover:text-white' : ''; ?>">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <span>Conservatief</span>
                                <?php if($filter === 'conservatief'): ?>
                                <div class="ml-2 w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- Refresh Button -->
                        <a href="?clear_cache=1<?php echo !empty($filter) && $filter !== 'alle' ? "&filter=$filter" : ""; ?>#artikelen" 
                           class="group relative px-8 py-4 rounded-2xl text-sm font-bold transition-all duration-500 transform hover:scale-105 flex items-center bg-white/50 text-slate-700 hover:bg-white hover:shadow-md overflow-hidden">
                            <!-- Background animation -->
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex items-center group-hover:text-white">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-white transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <span>Vernieuwen</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Articles Section -->
    <section class="relative z-10 pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Debug info (tijdelijk) -->
            <?php if (isset($_GET['debug'])): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-8">
                    <strong>Debug Info:</strong><br>
                    - Huidige pagina: <?php echo $currentPage; ?><br>
                    - Totaal pagina's: <?php echo $totalPages; ?><br>
                    - Totaal artikelen: <?php echo $totalArticles; ?><br>
                    - Artikelen per pagina: <?php echo $articlesPerPage; ?><br>
                    - Artikelen gevonden: <?php echo count($latest_news); ?><br>
                    - Filter: <?php echo $filter; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($latest_news)): ?>
                                 <!-- Premium Articles Grid -->
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <?php foreach ($latest_news as $index => $article): ?>
                        <article class="group relative bg-white/90 backdrop-blur-sm rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-700 hover:-translate-y-3 border border-white/50" 
                                data-aos="fade-up" 
                                data-aos-delay="<?php echo $index * 100; ?>"
                                data-aos-duration="800"
                                data-aos-once="true">
                            
                            <!-- Hover effect overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-purple-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                            
                                                         <!-- Top accent line based on bias -->
                             <div class="absolute top-0 left-0 right-0 h-1 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-r from-blue-500 to-indigo-600' : 'bg-gradient-to-r from-red-500 to-orange-600'; ?> transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-700 ease-out"></div>
                            
                            <div class="relative z-10 p-8 h-full flex flex-col">
                                <!-- Header with bias and source -->
                                <div class="flex justify-between items-start mb-6">
                                    <div class="space-y-2">
                                                                                 <!-- Bias Badge -->
                                         <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider border <?php echo $article['bias'] === 'Progressief' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-red-100 text-red-800 border-red-200'; ?> shadow-sm">
                                             <div class="w-2 h-2 rounded-full mr-2 <?php echo $article['bias'] === 'Progressief' ? 'bg-blue-600' : 'bg-red-600'; ?> animate-pulse"></div>
                                            <?php echo htmlspecialchars($article['bias']); ?>
                                        </span>
                                        
                                                                                 <!-- Source -->
                                         <div class="flex items-center space-x-2">
                                             <div class="w-8 h-8 rounded-lg <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-br from-blue-500 to-indigo-600' : 'bg-gradient-to-br from-red-500 to-orange-600'; ?> flex items-center justify-center shadow-sm">
                                                <span class="text-white font-black text-xs">
                                                    <?php echo substr($article['source'], 0, 2); ?>
                                                </span>
                                            </div>
                                            <span class="text-sm font-medium text-slate-600"><?php echo htmlspecialchars($article['source']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Article Content -->
                                <div class="flex-grow space-y-4">
                                                                         <!-- Title -->
                                     <h3 class="text-xl lg:text-2xl font-bold text-slate-900 leading-tight <?php echo $article['bias'] === 'Progressief' ? 'group-hover:text-blue-700' : 'group-hover:text-red-700'; ?> transition-colors duration-300 line-clamp-3">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h3>
                                    
                                    <!-- Description -->
                                    <p class="text-slate-600 leading-relaxed line-clamp-4 group-hover:text-slate-700 transition-colors duration-300">
                                        <?php echo htmlspecialchars($article['description']); ?>
                                    </p>
                                </div>
                                
                                <!-- Footer -->
                                <div class="mt-8 pt-6 border-t border-slate-200/50 space-y-4">
                                    <!-- Published time -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2 text-sm text-slate-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <time>
                                                <?php 
                                                // Volgens de memory, alle datums moeten in Nederlands formaat
                                                setlocale(LC_TIME, 'nl_NL.UTF-8', 'Dutch');
                                                $date = new DateTime($article['publishedAt']);
                                                $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT, null, null, 'd MMM yyyy, HH:mm');
                                                echo $formatter->format($date);
                                                ?>
                                            </time>
                                        </div>
                                    </div>
                                    
                                                                         <!-- Read More Button -->
                                     <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        class="group/btn relative inline-flex items-center justify-center w-full px-6 py-3 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700' : 'bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700'; ?> text-white font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                                        
                                        <!-- Button content -->
                                        <span class="relative z-10 flex items-center">
                                            Lees volledig artikel
                                            <svg class="w-5 h-5 ml-2 transform transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </span>
                                        
                                        <!-- Shine effect -->
                                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                                    </a>
                                </div>
                            </div>
                            
                                                         <!-- Decorative corner elements -->
                             <div class="absolute top-0 right-0 w-16 h-16 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-br from-blue-500/10 to-indigo-500/10' : 'bg-gradient-to-br from-red-500/10 to-orange-500/10'; ?> transform rotate-45 translate-x-8 -translate-y-8 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                             <div class="absolute bottom-0 left-0 w-12 h-12 <?php echo $article['bias'] === 'Progressief' ? 'bg-gradient-to-tr from-indigo-500/10 to-blue-500/10' : 'bg-gradient-to-tr from-orange-500/10 to-red-500/10'; ?> transform rotate-45 -translate-x-6 translate-y-6 group-hover:-translate-x-4 group-hover:translate-y-4 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination Section -->
                <?php if (isset($_GET['debug'])): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-8">
                        <strong>Paginering Debug:</strong><br>
                        - $totalPages: <?php echo $totalPages; ?><br>
                        - Conditie ($totalPages > 1): <?php echo ($totalPages > 1) ? 'true' : 'false'; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($totalPages > 1): ?>
                <div class="text-center mt-20" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                    <div class="relative inline-block">
                        <!-- Multi-layered glow effect -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-700 rounded-2xl blur-xl opacity-20"></div>
                        
                        <!-- Main pagination container -->
                        <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-2xl border border-white/50">
                            <div class="space-y-6">
                                <!-- Pagination Info -->
                                <div class="text-center space-y-2">
                                    <h3 class="text-xl font-bold text-slate-900">
                                        Pagina <?php echo $currentPage; ?> van <?php echo $totalPages; ?>
                                    </h3>
                                    <p class="text-slate-600">
                                        Totaal <?php echo $totalArticles; ?> artikelen gevonden
                                    </p>
                                </div>
                                
                                <!-- Pagination Controls -->
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Vorige Pagina -->
                                    <?php if ($currentPage > 1): ?>
                                        <a href="?<?php 
                                            $params = [];
                                            if ($filter !== 'alle') $params[] = "filter=$filter";
                                            $params[] = "page=" . ($currentPage - 1);
                                            echo implode('&', $params);
                                        ?>#artikelen" 
                                           class="group relative flex items-center justify-center w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl transition-all duration-300 hover:scale-110 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <!-- Pagina Nummers -->
                                    <?php
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($totalPages, $currentPage + 2);
                                    
                                    // Eerste pagina
                                    if ($startPage > 1):
                                    ?>
                                        <a href="?<?php 
                                            $params = [];
                                            if ($filter !== 'alle') $params[] = "filter=$filter";
                                            $params[] = "page=1";
                                            echo implode('&', $params);
                                        ?>#artikelen" 
                                           class="flex items-center justify-center w-12 h-12 text-slate-700 bg-white/70 hover:bg-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-medium">
                                            1
                                        </a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="text-slate-400 font-bold">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <!-- Huidige range -->
                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="?<?php 
                                            $params = [];
                                            if ($filter !== 'alle') $params[] = "filter=$filter";
                                            $params[] = "page=$i";
                                            echo implode('&', $params);
                                        ?>#artikelen" 
                                           class="flex items-center justify-center w-12 h-12 <?php echo $i === $currentPage ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg' : 'text-slate-700 bg-white/70 hover:bg-white'; ?> rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-medium">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <!-- Laatste pagina -->
                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="text-slate-400 font-bold">...</span>
                                        <?php endif; ?>
                                        <a href="?<?php 
                                            $params = [];
                                            if ($filter !== 'alle') $params[] = "filter=$filter";
                                            $params[] = "page=$totalPages";
                                            echo implode('&', $params);
                                        ?>#artikelen" 
                                           class="flex items-center justify-center w-12 h-12 text-slate-700 bg-white/70 hover:bg-white rounded-xl transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg font-medium">
                                            <?php echo $totalPages; ?>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <!-- Volgende Pagina -->
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="?<?php 
                                            $params = [];
                                            if ($filter !== 'alle') $params[] = "filter=$filter";
                                            $params[] = "page=" . ($currentPage + 1);
                                            echo implode('&', $params);
                                        ?>#artikelen" 
                                           class="group relative flex items-center justify-center w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl transition-all duration-300 hover:scale-110 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Refresh Button -->
                                <div class="pt-4 border-t border-slate-200/50">
                                    <a href="?clear_cache=1<?php 
                                        $params = [];
                                        if ($filter !== 'alle') $params[] = "filter=$filter";
                                        if ($currentPage > 1) $params[] = "page=$currentPage";
                                        echo !empty($params) ? '&' . implode('&', $params) : '';
                                    ?>#artikelen" 
                                       class="group relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                                        
                                        <!-- Button content -->
                                        <div class="relative z-10 flex items-center">
                                            <svg class="w-5 h-5 mr-3 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            <span>Vernieuw artikelen</span>
                                        </div>
                                        
                                        <!-- Shimmer effect -->
                                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/30 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- No Articles State - Premium Design -->
                <div class="text-center py-24" data-aos="fade-up">
                    <!-- Background effect -->
                    <div class="relative max-w-2xl mx-auto">
                        <div class="absolute -inset-1 bg-gradient-to-r from-slate-200 to-slate-300 rounded-3xl blur opacity-25"></div>
                        <div class="relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-12">
                            <!-- Icon -->
                            <div class="relative mb-8">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full blur-xl opacity-20"></div>
                                <div class="relative w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="space-y-6">
                                <h3 class="text-3xl font-black text-slate-900">Geen Artikelen Gevonden</h3>
                                <p class="text-lg text-slate-600 max-w-md mx-auto leading-relaxed">
                                    Er zijn momenteel geen artikelen die aan je selectie voldoen. Probeer een andere filter of vernieuw de data.
                                </p>
                                
                                <!-- CTA Button -->
                                <div class="pt-6">
                                    <a href="?clear_cache=1" 
                                       class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-700 text-white font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <span class="relative flex items-center">
                                            <svg class="w-5 h-5 mr-3 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Data Vernieuwen
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Custom Animations CSS -->
<style>
/* Premium animations and effects */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-20px) rotate(3deg); }
    50% { transform: translateY(-10px) rotate(-2deg); }
    75% { transform: translateY(-15px) rotate(1deg); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-25px) rotate(-3deg); }
    50% { transform: translateY(-15px) rotate(2deg); }
    75% { transform: translateY(-20px) rotate(-1deg); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

@keyframes shimmer {
    0% { transform: translateY(-100%) skewY(12deg); }
    100% { transform: translateY(300%) skewY(12deg); }
}

@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
    animation-delay: -4s;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-shimmer {
    animation: shimmer 1.5s ease-out;
}

.animate-gradient {
    animation: gradient 3s ease infinite;
}

.bg-size-200 {
    background-size: 200% 200%;
}

.bg-pos-0 {
    background-position: 0% 50%;
}

.bg-pos-100 {
    background-position: 100% 50%;
}

.animation-delay-75 {
    animation-delay: 75ms;
}

.animation-delay-150 {
    animation-delay: 150ms;
}

.animation-delay-300 {
    animation-delay: 300ms;
}

/* Line clamp utilities */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:rotate-12 {
    transform: rotate(12deg);
}

/* Glass morphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Custom focus styles */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .text-\[160px\] {
        font-size: 100px;
    }
    .text-\[200px\] {
        font-size: 120px;
    }
    .text-\[250px\] {
        font-size: 140px;
    }
    .text-\[300px\] {
        font-size: 160px;
    }
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 