<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <!-- Modern Blog Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 md:py-24 lg:py-32 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Floating Geometric Shapes - Responsive -->
        <div class="absolute top-8 left-4 md:top-16 md:left-8 lg:top-20 lg:left-12 w-16 h-16 md:w-24 md:h-24 lg:w-32 lg:h-32 bg-gradient-to-br from-primary/30 to-secondary/30 rounded-3xl rotate-45 animate-bounce hidden sm:block" style="animation-duration: 6s; animation-delay: 0s;"></div>
        <div class="absolute top-1/3 right-4 md:right-8 lg:right-16 w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-gradient-to-tl from-secondary/25 to-primary/25 rounded-2xl rotate-12 animate-bounce hidden md:block" style="animation-duration: 8s; animation-delay: 2s;"></div>
        <div class="absolute bottom-16 left-1/4 w-8 h-8 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-full animate-bounce hidden lg:block" style="animation-duration: 7s; animation-delay: 4s;"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 md:w-64 md:h-64 lg:w-80 lg:h-80 bg-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    
                    <!-- Left Column - Main Content -->
                    <div class="text-center lg:text-left space-y-6 lg:space-y-8 order-1 lg:order-1">
                        
                        <!-- Main Heading -->
                        <div class="space-y-2 md:space-y-4">
                                                        <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 md:mb-6 tracking-tight leading-tight">
                        Politieke
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Blogs
                        </span>
                    </h1>
                            
                            <!-- Typing Animation Text -->
                            <div class="text-base md:text-lg lg:text-xl text-blue-100 font-medium min-h-[1.5em] md:min-h-[2em]">
                                <span id="typing-text" class="border-r-2 border-blue-300 animate-pulse"></span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-base md:text-lg lg:text-xl text-blue-100 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Ontdek diepgaande analyses en persoonlijke inzichten over de Nederlandse politiek. Waar ideeën vorm krijgen en meningen botsen.
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="<?php echo URLROOT; ?>/blogs/create" 
                                   class="group relative inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold rounded-2xl shadow-2xl hover:shadow-secondary/30 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                    <!-- Shine Effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <svg class="w-5 h-5 mr-2 md:mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <span class="relative z-10 text-sm md:text-base">Schrijf je verhaal</span>
                                </a>
                            <?php endif; ?>
                            
                            <a href="#blogs" 
                               class="group inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-white/10 backdrop-blur-lg text-white font-semibold rounded-2xl border border-white/20 hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 md:mr-3 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                <span class="text-sm md:text-base">Ontdek verhalen</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column - Featured Blog Spotlight -->
                    <div class="relative order-2 lg:order-2 mb-8 lg:mb-0">
                        <!-- Central Decorative Element -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full blur-3xl animate-pulse"></div>
                        </div>
                        
                        <!-- Featured Blog Card -->
                        <div class="relative max-w-lg mx-auto">
                            <!-- Featured Blog Container -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 md:p-8 border border-white/20 shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-1">
                                <!-- Header -->
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-light/80 to-secondary/80 rounded-2xl mb-4 shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Uitgelichte Blog</h3>
                                    <p class="text-blue-200 text-sm">Ontdek interessante politieke inzichten</p>
                                </div>
                                
                                <!-- Featured Blog Content -->
                                <div class="space-y-6">
                                    <?php if (!empty($blogs)): ?>
                                        <?php 
                                        // Selecteer een featured blog (bijvoorbeeld de nieuwste of een met veel likes)
                                        $featuredBlog = null;
                                        $maxLikes = 0;
                                        
                                        // Zoek blog met meeste likes, of neem de nieuwste
                                        foreach ($blogs as $blog) {
                                            if (($blog->likes ?? 0) > $maxLikes) {
                                                $maxLikes = $blog->likes ?? 0;
                                                $featuredBlog = $blog;
                                            }
                                        }
                                        
                                        // Als geen blog likes heeft, neem de nieuwste
                                        if (!$featuredBlog) {
                                            $featuredBlog = $blogs[0];
                                        }
                                        ?>
                                        
                                        <!-- Featured Blog Display -->
                                        <div class="text-center space-y-4">
                                            <!-- Blog Title -->
                                            <h4 class="text-white font-bold text-lg leading-tight">
                                                <?php echo htmlspecialchars($featuredBlog->title); ?>
                                            </h4>
                                            
                                            <!-- Blog Summary -->
                                            <p class="text-blue-200 text-sm leading-relaxed">
                                                <?php echo htmlspecialchars(substr($featuredBlog->summary, 0, 120)); ?>...
                                            </p>
                                            
                                            <!-- Blog Stats -->
                                            <div class="flex items-center justify-center space-x-4 text-white/80 text-xs">
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    <span><?php echo $featuredBlog->likes ?? 0; ?> likes</span>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span><?php echo date('d M', strtotime($featuredBlog->published_at)); ?></span>
                                                </div>
                                            </div>
                                            
                                            <!-- Read More Button -->
                                            <div class="pt-4">
                                                <a href="<?php echo URLROOT . '/blogs/' . $featuredBlog->slug; ?>" 
                                                   class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold rounded-2xl shadow-lg hover:shadow-secondary/30 hover:-translate-y-1 transition-all duration-300">
                                                    <span class="text-sm">Lees volledig artikel</span>
                                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        
                                    <?php else: ?>
                                        <!-- No Blogs Available -->
                                        <div class="text-center space-y-4">
                                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto">
                                                <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-white font-bold text-lg">Binnenkort beschikbaar</h4>
                                            <p class="text-blue-200 text-sm">
                                                De eerste blogs zijn onderweg. Kom snel terug voor interessante politieke analyses en inzichten.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Footer -->
                                    <div class="pt-6 border-t border-white/10">
                                        <div class="flex items-center justify-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-secondary-light/80 to-secondary/80 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-white font-semibold text-sm">Handmatig Geselecteerd</p>
                                                <p class="text-blue-200 text-xs">Zorgvuldig uitgekozen voor kwaliteit</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade transition -->
        <div class="absolute bottom-0 left-0 right-0 h-24 md:h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>
    
    <!-- Enhanced Scripts for AI Dashboard -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Typing Animation
        const typingElement = document.getElementById('typing-text');
        const texts = [
            'Waar meningen worden gevormd...',
            'Waar analyses diepgang krijgen...',
            'Waar politiek toegankelijk wordt...',
            'Waar ideeën tot leven komen...'
        ];
        
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;
        
        function typeText() {
            const currentText = texts[textIndex];
            
            if (isDeleting) {
                typingElement.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                typingSpeed = 50;
            } else {
                typingElement.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                typingSpeed = 100;
            }
            
            if (!isDeleting && charIndex === currentText.length) {
                typingSpeed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typingSpeed = 500;
            }
            
            setTimeout(typeText, typingSpeed);
        }
        
        typeText();
        
        // Add subtle hover effects to featured blog
        const featuredBlog = document.querySelector('.bg-white\\/10');
        if (featuredBlog) {
            featuredBlog.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            featuredBlog.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });
    
    // Add CSS for fade-in animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }
    `;
    document.head.appendChild(style);
    </script>

    <!-- Categorie Filter Sectie -->
    <section class="py-12 bg-gray-50/50 relative z-10">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                        Verken per Categorie
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500">
                        Vind de blogs die jou het meest interesseren.
                    </p>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-3 md:gap-4">
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="group inline-flex items-center px-4 py-2 rounded-full bg-white shadow-sm border border-gray-200 hover:bg-primary-light/20 hover:border-primary/30 transition-all duration-300 text-sm font-semibold text-gray-800 hover:text-primary transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h6a2 2 0 012 2v2"></path>
                        </svg>
                        <span>Alle artikelen</span>
                    </a>
                    
                    <?php 
                    $categoryController = new CategoryController();
                    $filterCategories = $categoryController->getBlogCountByCategory();
                    
                    foreach ($filterCategories as $category):
                        if ($category->blog_count > 0):
                    ?>
                        <a href="<?php echo URLROOT; ?>/blogs?category=<?php echo $category->slug; ?>" 
                           class="group relative inline-flex items-center px-4 py-2 rounded-full text-white text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 overflow-hidden"
                           style="background: linear-gradient(135deg, <?php echo htmlspecialchars($category->color); ?> 0%, <?php echo htmlspecialchars(adjust_brightness($category->color, -20)); ?> 100%);">
                            
                            <!-- Shine effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>

                            <span class="relative z-10 flex items-center">
                                <span class="w-2 h-2 bg-white/80 rounded-full mr-2.5"></span>
                                <?php echo htmlspecialchars($category->name); ?>
                                <span class="ml-2.5 bg-white/25 px-2 py-0.5 rounded-full text-xs font-bold">
                                    <?php echo $category->blog_count; ?>
                                </span>
                            </span>
                        </a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts Grid -->
    <section class="py-12 relative z-10" id="blogs">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($blogs as $blog): ?>
                    <article class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-gray-100 h-full">
                        <!-- Decoratieve hover accent lijn -->
                        <div class="absolute inset-0 top-auto h-1 bg-gradient-to-r from-primary to-secondary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 z-10"></div>
                        
                        <?php 
                        // Check of de blog minder dan 12 uur oud is
                        $published_time = strtotime($blog->published_at);
                        $twelve_hours_ago = time() - (12 * 3600); // 12 uur in seconden
                        
                        if ($published_time > $twelve_hours_ago): 
                        ?>
                            <!-- Nieuw Badge voor recent geplaatste blogs -->
                            <div class="absolute top-4 right-4 z-20">
                                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-sm font-bold shadow-lg">
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

                                                    <a href="<?php echo URLROOT . '/blogs/' . $blog->slug; ?>" class="block relative">
                            <?php if ($blog->image_path): ?>
                                <div class="relative h-52 overflow-hidden">
                                    <img src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
                                         alt="<?php echo htmlspecialchars($blog->title); ?>"
                                         class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            <?php else: ?>
                                <!-- Fallback voor blogs zonder afbeelding -->
                                <div class="h-40 bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="p-7">
                                <!-- Categorie badge (alleen als er een categorie is) -->
                                <?php if (isset($blog->category_name) && $blog->category_name): ?>
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" 
                                          style="background-color: <?php echo $blog->category_color ?? '#3B82F6'; ?>;">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 714-4z"></path>
                                        </svg>
                                        <?php echo htmlspecialchars($blog->category_name); ?>
                                    </span>
                                </div>
                                <?php endif; ?>

                                <!-- Auteur en datum info met verbeterd design -->
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center overflow-hidden">
                                            <?php
                                            $profilePhotoData = getProfilePhotoUrl($blog->author_photo ?? null, $blog->author_name);
                                            if ($profilePhotoData['type'] === 'img'): ?>
                                                <img src="<?php echo htmlspecialchars($profilePhotoData['value']); ?>" 
                                                     alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                     class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary text-white font-bold text-lg">
                                                    <?php echo htmlspecialchars($profilePhotoData['value']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($blog->author_name); ?></span>
                                    </div>
                                    
                                    <div class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                                    </div>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                    <?php echo htmlspecialchars($blog->title); ?>
                                </h2>

                                <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                    <?php echo htmlspecialchars($blog->summary); ?>
                                </p>

                                <!-- Actieknop met verbeterd ontwerp -->
                                <div class="flex items-center justify-between pt-2">
                                    <div class="inline-flex items-center px-4 py-2 bg-primary/5 hover:bg-primary/10 text-primary font-semibold rounded-lg transition-colors duration-300">
                                        <span>Lees meer</span>
                                        <svg class="w-4 h-4 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Likes indicator -->
                                    <div class="flex items-center text-gray-400 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span>
                                            <?php 
                                                // Toon het aantal likes uit de database
                                                echo (isset($blog->likes) && $blog->likes > 0) ? $blog->likes : '0';
                                                echo ' likes';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Decoratieve hoekelementen -->
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-10 -translate-y-10 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700"></div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if (empty($blogs)): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Nog geen blogs</h3>
                    <p class="text-gray-600">Wees de eerste die een blog schrijft!</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="inline-flex items-center px-6 py-3 mt-4 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nieuwe Blog Schrijven
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Scripts voor Markdown parsing -->
<script src="https://cdn.jsdelivr.net/npm/marked@4.3.0/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.3/dist/purify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configureer marked
    marked.use({ 
        breaks: true,
        gfm: true,
        headerIds: false
    });

    // Functie om markdown te parsen en te strippen van HTML tags
    function parseAndStripMarkdown(markdown, length = 150) {
        try {
            // Parse markdown naar HTML
            const html = marked.parse(markdown);
            // Sanitize de HTML
            const cleanHtml = DOMPurify.sanitize(html);
            // Strip HTML tags en limiteer lengte
            const text = cleanHtml.replace(/<[^>]*>/g, '');
            return text.length > length ? text.substring(0, length) + '...' : text;
        } catch (error) {
            console.error('Markdown parsing error:', error);
            return markdown;
        }
    }

    // Pas toe op alle blog samenvattingen
    document.querySelectorAll('.blog-summary').forEach(summary => {
        const markdown = summary.textContent;
        summary.textContent = parseAndStripMarkdown(markdown);
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 