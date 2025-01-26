<main class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <!-- Hero Section met dynamische achtergrond -->
    <section class="relative bg-gradient-to-br from-blue-900 via-primary to-purple-900 py-20 overflow-hidden">
        <!-- Animated background pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-30 animate-pulse"></div>
        </div>

        <!-- Floating elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -left-4 top-1/4 w-24 h-24 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute -right-4 top-1/4 w-24 h-24 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute left-1/2 bottom-1/4 w-24 h-24 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 tracking-tight">
                    <span class="inline-block transform hover:scale-105 transition-transform duration-300">Politiek</span>
                    <span class="inline-block transform hover:scale-105 transition-transform duration-300 delay-100">Nieuws</span>
                </h1>
                <p class="text-xl text-gray-200 mb-8 leading-relaxed">
                    Ontdek het laatste politieke nieuws vanuit verschillende perspectieven,
                    <br class="hidden md:block">
                    zorgvuldig samengesteld voor een gebalanceerd overzicht.
                </p>
                
                <!-- Statistieken in hero sectie -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto mt-12">
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                        <div class="text-3xl font-bold text-white mb-1"><?php echo $stats['total_articles']; ?></div>
                        <div class="text-sm text-gray-200">Artikelen</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                        <div class="text-3xl font-bold text-white mb-1"><?php echo $stats['progressive_count']; ?></div>
                        <div class="text-sm text-gray-200">Progressief</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                        <div class="text-3xl font-bold text-white mb-1"><?php echo $stats['conservative_count']; ?></div>
                        <div class="text-sm text-gray-200">Conservatief</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                        <div class="text-3xl font-bold text-white mb-1"><?php 
                            $newest = new DateTime($stats['newest_article']);
                            $oldest = new DateTime($stats['oldest_article']);
                            $diff = $newest->diff($oldest);
                            echo $diff->h > 0 ? $diff->h : $diff->i;
                        ?></div>
                        <div class="text-sm text-gray-200"><?php echo $diff->h > 0 ? 'Uur' : 'Minuten'; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nieuws Content -->
    <section class="py-12 relative z-10">
        <div class="container mx-auto px-4">
            <!-- Filter knoppen -->
            <div class="flex flex-wrap gap-4 justify-center mb-12">
                <a href="?filter=alle" 
                   class="group relative px-8 py-3 rounded-full <?php echo $filter === 'alle' ? 'bg-blue-600 text-white' : 'bg-white hover:bg-blue-50'; ?> shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative z-10 flex items-center space-x-2">
                        <svg class="w-5 h-5 <?php echo $filter === 'alle' ? 'text-white' : 'text-blue-600'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="font-medium">Alle bronnen</span>
                        <span class="ml-1 text-sm opacity-75">(<?php echo $stats['total_articles']; ?>)</span>
                    </div>
                </a>
                <a href="?filter=progressief" 
                   class="group relative px-8 py-3 rounded-full <?php echo $filter === 'progressief' ? 'bg-blue-600 text-white' : 'bg-white hover:bg-blue-50'; ?> shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative z-10 flex items-center space-x-2">
                        <svg class="w-5 h-5 <?php echo $filter === 'progressief' ? 'text-white' : 'text-blue-600'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        <span class="font-medium">Progressief</span>
                        <span class="ml-1 text-sm opacity-75">(<?php echo $stats['progressive_count']; ?>)</span>
                    </div>
                </a>
                <a href="?filter=conservatief" 
                   class="group relative px-8 py-3 rounded-full <?php echo $filter === 'conservatief' ? 'bg-blue-600 text-white' : 'bg-white hover:bg-blue-50'; ?> shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative z-10 flex items-center space-x-2">
                        <svg class="w-5 h-5 <?php echo $filter === 'conservatief' ? 'text-white' : 'text-blue-600'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span class="font-medium">Conservatief</span>
                        <span class="ml-1 text-sm opacity-75">(<?php echo $stats['conservative_count']; ?>)</span>
                    </div>
                </a>
            </div>

            <!-- Nieuws artikelen grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($latest_news as $article): ?>
                    <article class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <?php if ($article['image']): ?>
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?php echo htmlspecialchars($article['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['title']); ?>"
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <!-- Bron en Bias Tags -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php 
                                    echo $article['bias'] === 'Progressief' 
                                        ? 'bg-blue-100 text-blue-800' 
                                        : 'bg-red-100 text-red-800'; 
                                ?>">
                                    <span class="w-2 h-2 rounded-full <?php 
                                        echo $article['bias'] === 'Progressief' 
                                            ? 'bg-blue-600' 
                                            : 'bg-red-600'; 
                                    ?> mr-2"></span>
                                    <?php echo htmlspecialchars($article['bias']); ?>
                                </span>
                                <span class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8m-2 12a2 2 0 01-2-2v-1"></path>
                                    </svg>
                                    <?php echo htmlspecialchars($article['source']); ?>
                                </span>
                            </div>

                            <!-- Artikel Titel -->
                            <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                   target="_blank" 
                                   class="hover:text-blue-600">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h2>

                            <!-- Artikel Beschrijving -->
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($article['description']); ?>
                            </p>

                            <!-- Footer met Datum en Lees Meer -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <time class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php 
                                        $date = new DateTime($article['publishedAt']);
                                        echo $date->format('d M H:i'); 
                                    ?>
                                </time>
                                <a href="<?php echo htmlspecialchars($article['url']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                    Lees meer
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Geen artikelen gevonden -->
            <?php if (empty($latest_news)): ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8m-2 12a2 2 0 01-2-2v-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Geen artikelen gevonden</h3>
                    <p class="text-gray-500">Er zijn momenteel geen artikelen beschikbaar voor deze selectie.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}

/* Voeg line-clamp classes toe */
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
</style>

<?php require_once 'views/templates/footer.php'; ?> 