<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary overflow-hidden py-20">
        <!-- Decoratieve elementen -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block p-4 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <span class="text-6xl"><?php echo $thema['icon']; ?></span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6"><?php echo $thema['title']; ?></h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto"><?php echo $thema['long_description']; ?></p>
                
                <!-- Key Points -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
                    <?php foreach($thema['key_points'] as $point): ?>
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4">
                            <p class="text-white font-medium"><?php echo $point; ?></p>
                        </div>
                    <?php endforeach; ?>
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

    <!-- Standpunten Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Politieke Perspectieven
                        </span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600">Ontdek de verschillende politieke standpunten over <?php echo strtolower($thema['title']); ?></p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Links Perspectief -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-400/10 rounded-full blur-2xl transform translate-x-20 -translate-y-20"></div>
                        <div class="relative">
                            <h3 class="text-2xl font-bold text-blue-800 mb-6">Links Perspectief</h3>
                            <div class="space-y-6">
                                <?php foreach($linksePartijen as $partij => $standpunt): ?>
                                    <div class="bg-white rounded-xl p-6 shadow-lg">
                                        <h4 class="font-semibold text-blue-900 mb-2"><?php echo $partij; ?></h4>
                                        <p class="text-gray-600"><?php echo $standpunt; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Rechts Perspectief -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-red-400/10 rounded-full blur-2xl transform translate-x-20 -translate-y-20"></div>
                        <div class="relative">
                            <h3 class="text-2xl font-bold text-red-800 mb-6">Rechts Perspectief</h3>
                            <div class="space-y-6">
                                <?php foreach($rechtsePartijen as $partij => $standpunt): ?>
                                    <div class="bg-white rounded-xl p-6 shadow-lg">
                                        <h4 class="font-semibold text-red-900 mb-2"><?php echo $partij; ?></h4>
                                        <p class="text-gray-600"><?php echo $standpunt; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laatste Nieuws -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 relative">
                    <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-9xl text-gray-100 font-bold opacity-50 select-none">NIEUWS</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 relative">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Laatste Nieuws
                        </span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600">Het laatste nieuws over <?php echo strtolower($thema['title']); ?></p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($themaNews as $index => $news): ?>
                        <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                                data-aos="fade-up"
                                data-aos-delay="<?php echo $index * 100; ?>">
                            <?php if(isset($news['image'])): ?>
                                <div class="relative h-48 overflow-hidden">
                                    <img src="<?php echo $news['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($news['title']); ?>"
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-primary">
                                            <?php echo $news['source']; ?>
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        <?php echo date('d M Y', strtotime($news['publishedAt'])); ?>
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                    <?php echo $news['title']; ?>
                                </h3>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    <?php echo $news['description']; ?>
                                </p>

                                <div class="flex items-center justify-between">
                                    <a href="<?php echo $news['url']; ?>" 
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center text-primary font-medium group-hover:text-secondary transition-colors">
                                        <span class="relative">
                                            Lees artikel
                                            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                                        </span>
                                        <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>

                                    <div class="flex items-center space-x-4">
                                        <button class="text-gray-500 hover:text-primary transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                        <button class="text-gray-500 hover:text-primary transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if(empty($themaNews)): ?>
                    <div class="text-center py-12">
                        <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Geen nieuws gevonden</h3>
                        <p class="text-gray-600">Er zijn momenteel geen nieuwsartikelen beschikbaar over dit thema.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'views/templates/footer.php'; ?> 