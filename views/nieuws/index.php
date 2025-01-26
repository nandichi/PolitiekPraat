<main class="bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary py-16 overflow-hidden">
        <!-- Animated background pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Politiek Nieuws
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Een gebalanceerd overzicht van het laatste politieke nieuws uit verschillende perspectieven
                </p>
            </div>
        </div>
    </section>

    <!-- Nieuws Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Filters -->
            <div class="mb-12 flex flex-wrap gap-4 justify-center">
                <button class="px-6 py-2 rounded-full bg-blue-100 text-blue-600 font-medium hover:bg-blue-200 transition-colors active">
                    Alle bronnen
                </button>
                <button class="px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition-colors">
                    Progressief
                </button>
                <button class="px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition-colors">
                    Conservatief
                </button>
            </div>

            <!-- Nieuws Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Links georiënteerde bronnen -->
                <div class="space-y-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        Progressieve Media
                    </h2>
                    
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
                                        <?php echo date('d M Y', strtotime($news['publishedAt'])); ?>
                                    </div>
                                </div>

                                <!-- Article Content -->
                                <div class="space-y-3">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">
                                        <?php echo $news['title']; ?>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-3">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Conservatieve Media
                    </h2>

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
                                        <?php echo date('d M Y', strtotime($news['publishedAt'])); ?>
                                    </div>
                                </div>

                                <!-- Article Content -->
                                <div class="space-y-3">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">
                                        <?php echo $news['title']; ?>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-3">
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

            <!-- Pagination -->
            <div class="flex justify-center items-center space-x-2">
                <button class="w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-gray-600 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="w-10 h-10 bg-primary text-white rounded-lg shadow flex items-center justify-center">1</button>
                <button class="w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-gray-600 hover:bg-gray-50">2</button>
                <button class="w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-gray-600 hover:bg-gray-50">3</button>
                <button class="w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-gray-600 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
</main>

<?php require_once 'views/templates/footer.php'; ?> 