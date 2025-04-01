<main class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <!-- Hero Section - Rebuilt to be elegant and professional -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-blue-600 py-20 overflow-hidden">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary to-blue-400"></div>
        
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Abstract wave pattern -->
            <svg class="absolute w-full h-56 -bottom-10 left-0 text-white/5" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path fill="currentColor" fill-opacity="1" d="M0,128L40,138.7C80,149,160,171,240,170.7C320,171,400,149,480,149.3C560,149,640,171,720,192C800,213,880,235,960,229.3C1040,224,1120,192,1200,165.3C1280,139,1360,117,1400,106.7L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
            </svg>
            
            <!-- Decorative circles -->
            <div class="absolute top-20 left-10 w-40 h-40 rounded-full bg-secondary/10 filter blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-blue-500/10 filter blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-20 h-20 rounded-full bg-secondary/20 filter blur-xl"></div>
            
            <!-- Dot pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-30"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Small decorative element above title -->
                <div class="inline-block mb-3">
                    <div class="flex items-center justify-center space-x-1">
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        <span class="block w-3 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    </div>
                </div>
                
                <!-- Title with gradient text -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-100 via-white to-secondary-light">
                        Nieuws uit alle perspectieven
                    </span>
                </h1>
                
                <!-- Subtitle with lighter weight -->
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed font-light">
                    Blijf geïnformeerd met een gebalanceerde selectie van nieuwsartikelen uit zowel progressieve als conservatieve bronnen
                </p>
                
                <!-- Call to action button -->
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <a href="#artikelen" 
                       class="px-6 py-3.5 bg-gradient-to-r from-secondary to-secondary-dark text-white font-medium rounded-lg shadow-lg hover:shadow-secondary/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                        <span>Verken artikelen</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <a href="?clear_cache=1" 
                       class="px-6 py-3.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-lg border border-white/20 hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Vernieuwen</span>
                    </a>
                </div>
                
                <!-- Statistics cards with updated styling -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo $stats['total_articles']; ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Artikelen</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo isset($stats['source_count']) ? $stats['source_count'] : count(array_unique(array_column($latest_news, 'source'))); ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Bronnen</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo $stats['progressive_count']; ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Progressief</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo $stats['conservative_count']; ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Conservatief</div>
                    </div>
                </div>
                
                <!-- News source badges with updated styling -->
                <div class="flex flex-wrap justify-center gap-2 mt-6">
                    <?php foreach (array_merge($news_sources['links'], $news_sources['rechts']) as $source): ?>
                        <span class="px-3 py-1 bg-white/10 text-xs text-white font-medium rounded-full border border-white/10">
                            <?php echo htmlspecialchars($source['name']); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Nieuws Content -->
    <section class="py-12 relative z-10" id="artikelen">
        <div class="container mx-auto px-4">
            <!-- Filter knoppen - redesigned for cleaner look -->
            <div class="flex flex-wrap gap-3 justify-center mb-8">
                <a href="?filter=alle" 
                   class="px-5 py-2.5 rounded-lg <?php echo $filter === 'alle' ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'; ?> shadow-sm transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 <?php echo $filter === 'alle' ? 'text-white' : 'text-primary'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="font-medium">Alle bronnen</span>
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 <?php echo $filter === 'alle' ? 'bg-blue-700/20 text-white' : 'text-gray-600'; ?>"><?php echo $stats['total_articles']; ?></span>
                </a>
                <a href="?filter=progressief" 
                   class="px-5 py-2.5 rounded-lg <?php echo $filter === 'progressief' ? 'bg-secondary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'; ?> shadow-sm transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 <?php echo $filter === 'progressief' ? 'text-white' : 'text-secondary'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    <span class="font-medium">Progressief</span>
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 <?php echo $filter === 'progressief' ? 'bg-red-700/20 text-white' : 'text-gray-600'; ?>"><?php echo $stats['progressive_count']; ?></span>
                </a>
                <a href="?filter=conservatief" 
                   class="px-5 py-2.5 rounded-lg <?php echo $filter === 'conservatief' ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'; ?> shadow-sm transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 <?php echo $filter === 'conservatief' ? 'text-white' : 'text-primary'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    <span class="font-medium">Conservatief</span>
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 <?php echo $filter === 'conservatief' ? 'bg-blue-700/20 text-white' : 'text-gray-600'; ?>"><?php echo $stats['conservative_count']; ?></span>
                </a>
                <a href="?clear_cache=1<?php echo !empty($filter) && $filter !== 'alle' ? "&filter=$filter" : ""; ?>" 
                   class="px-5 py-2.5 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="font-medium">Vernieuwen</span>
                </a>
            </div>

            <!-- Nieuws artikelen grid - updated for more professional look -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($latest_news as $article): ?>
                    <article class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full transform hover:-translate-y-1">
                        <?php if ($article['image']): ?>
                            <div class="relative h-48 overflow-hidden">
                                <a href="<?php echo htmlspecialchars($article['url']); ?>" target="_blank" class="block h-full">
                                    <img src="<?php echo htmlspecialchars($article['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($article['title']); ?>"
                                         class="w-full h-full object-cover transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                </a>
                                <!-- Source badge on image -->
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1.5 bg-white/90 backdrop-blur-sm text-xs font-semibold rounded-lg shadow-md">
                                        <?php echo htmlspecialchars($article['source']); ?>
                                    </span>
                                </div>
                                <!-- Title on image for better visual hierarchy -->
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h2 class="text-lg font-bold text-white mb-1 line-clamp-2 leading-snug">
                                        <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                           target="_blank" 
                                           class="hover:text-blue-100 transition-colors">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-5 flex-grow flex flex-col">
                            <?php if (!$article['image']): ?>
                                <!-- Artikel Titel - improved typography (only show if no image) -->
                                <h2 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 leading-snug">
                                    <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                       target="_blank" 
                                       class="hover:text-primary transition-colors">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </a>
                                </h2>
                            <?php endif; ?>

                            <!-- Bias Tag - simplified design -->
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium <?php 
                                    echo $article['bias'] === 'Progressief' 
                                        ? 'bg-secondary/10 text-secondary' 
                                        : 'bg-primary/10 text-primary'; 
                                ?>">
                                    <?php echo htmlspecialchars($article['bias']); ?>
                                </span>
                            </div>

                            <!-- Artikel Beschrijving - improved readability -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed flex-grow">
                                <?php echo htmlspecialchars($article['description']); ?>
                            </p>

                            <!-- Footer met Datum en Lees Meer - cleaner design -->
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                <time class="text-xs text-gray-500 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php 
                                        $date = new DateTime($article['publishedAt']);
                                        echo $date->format('d M H:i'); 
                                    ?>
                                </time>
                                <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center text-xs font-medium text-primary hover:text-primary-dark transition-colors">
                                    Lees meer
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Geen artikelen gevonden - improved empty state -->
            <?php if (empty($latest_news)): ?>
                <div class="text-center py-16 bg-gray-50 rounded-xl border border-gray-200 max-w-xl mx-auto">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8m-2 12a2 2 0 01-2-2v-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Geen artikelen gevonden</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">Er zijn momenteel geen artikelen beschikbaar voor deze selectie. Probeer een andere filter of kom later terug.</p>
                    <div class="mt-6">
                        <a href="?clear_cache=1" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg shadow-sm hover:bg-primary-dark transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Data vernieuwen
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
/* Essential utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Subtle hover effects */
article:hover img {
    transform: scale(1.03);
}

/* Smooth transitions */
a, button {
    transition: all 0.2s ease-in-out;
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 