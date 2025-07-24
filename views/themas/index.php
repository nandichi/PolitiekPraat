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
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Politieke Thema's</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">Ontdek en discussieer mee over de belangrijkste politieke thema's in Nederland</p>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-auto" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="rgb(249 250 251)"/>
            </svg>
        </div>
    </section>

    <!-- Actuele Thema's Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Actuele Thema's
                        </span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600">De meest besproken politieke onderwerpen van dit moment</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($actueleThemas as $thema): ?>
                        <div class="group relative">
                            <!-- Card Background with Gradient Border -->
                            <div class="absolute inset-0 bg-gradient-to-br from-primary to-secondary rounded-2xl opacity-50 blur group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Main Card -->
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg transform transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl">
                                <!-- Icon Container -->
                                <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl flex items-center justify-center mb-6">
                                    <span class="text-4xl"><?php echo $thema['icon']; ?></span>
                                </div>

                                <!-- Content -->
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors mb-4">
                                    <?php echo $thema['title']; ?>
                                </h3>
                                <p class="text-gray-600 mb-6">
                                    <?php echo $thema['description']; ?>
                                </p>

                                <a href="<?php echo URLROOT; ?>/thema/<?php echo strtolower(str_replace(' ', '-', $thema['title'])); ?>" 
                                   class="inline-flex items-center text-primary font-semibold group-hover:text-secondary transition-colors">
                                    <span>Lees meer</span>
                                    <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Alle Thema's Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Alle Thema's
                        </span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-xl text-gray-600">Ontdek alle belangrijke politieke thema's</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($themas as $thema): ?>
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="p-6">
                                <!-- Icon & Title -->
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl flex items-center justify-center mr-4">
                                        <span class="text-2xl"><?php echo $thema['icon']; ?></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors">
                                        <?php echo $thema['title']; ?>
                                    </h3>
                                </div>

                                <!-- Description -->
                                <p class="text-gray-600 mb-6">
                                    <?php echo $thema['description']; ?>
                                </p>

                                <!-- Stats -->
                                <div class="flex items-center justify-between mb-6">
                                    <span class="inline-flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <?php echo $thema['stats']['discussions']; ?> discussies
                                    </span>
                                    <span class="inline-flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <?php echo $thema['stats']['followers']; ?> volgers
                                    </span>
                                </div>

                                <!-- Action Link -->
                                <a href="<?php echo URLROOT; ?>/thema/<?php echo $thema['slug']; ?>" 
                                   class="inline-flex items-center text-primary font-semibold hover:text-secondary transition-colors">
                                    <span>Bekijk thema</span>
                                    <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stemwijzer CTA Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Cpath d=\"M30 30m-20 0a20 20 0 1 1 40 0a20 20 0 1 1 -40 0\" stroke=\"white\" stroke-width=\"0.5\" fill=\"none\"/%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                    <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                    <span class="text-white/90 text-sm font-medium">Verkiezingen 2025</span>
                </div>

                <!-- Heading -->
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Ontdek welke partij bij 
                    <span class="bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                        jouw thema's past
                    </span>
                </h2>
                
                <!-- Description -->
                <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto mb-8 leading-relaxed">
                    Nu je de thema's kent, doe de gratis Stemwijzer 2025 en ontdek welke Nederlandse politieke partij het beste aansluit bij jouw standpunten over deze onderwerpen.
                </p>

                <!-- CTA Button -->
                <div class="relative inline-block">
                    <!-- Glow effect -->
                    <div class="absolute -inset-2 bg-gradient-to-r from-secondary-light via-white to-secondary-light rounded-2xl blur opacity-30 animate-pulse"></div>
                    
                    <!-- Main button -->
                    <a href="<?= URLROOT ?>/stemwijzer" 
                       class="relative inline-flex items-center justify-center px-8 md:px-12 py-4 md:py-6 bg-white text-primary font-bold text-lg md:text-xl rounded-2xl shadow-2xl hover:shadow-white/30 transform hover:scale-105 transition-all duration-300 group overflow-hidden">
                        
                        <!-- Button content -->
                        <div class="relative z-10 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="mr-3">Start de Stemwijzer 2025</span>
                            <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                        
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </a>
                </div>

                <!-- Supporting text -->
                <p class="mt-6 text-sm text-blue-200">
                    Gratis online stemtest • 30 politieke thema's • 14 Nederlandse partijen • Direct resultaat
                </p>
            </div>
        </div>
    </section>
</main>

<?php require_once 'views/templates/footer.php'; ?> 