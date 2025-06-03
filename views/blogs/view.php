<?php 
// Voeg dynamische meta tags toe voor deze specifieke blog
$pageTitle = htmlspecialchars($blog->title) . ' | PolitiekPraat';
$pageDescription = htmlspecialchars($blog->summary);
$pageImage = $blog->image_path ? URLROOT . '/' . $blog->image_path : URLROOT . '/public/img/og-image.jpg';

// Voeg deze variabelen toe aan $data voor de header
$data = [
    'title' => $pageTitle,
    'description' => $pageDescription,
    'image' => $pageImage
];

require_once 'views/templates/header.php'; ?>

<!-- Reading Progress Bar -->
<div id="reading-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-primary via-secondary to-accent z-50 transition-all duration-300 ease-out" style="width: 0%"></div>

<main class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/20 min-h-screen">
    <!-- Hero Section met Pure Tailwind -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-[400px] md:min-h-[600px] flex items-center">
        <!-- Decoratieve achtergrond -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-transparent to-red-900/20"></div>
        </div>
        
        <div class="relative z-10 w-full">
            <div class="container mx-auto px-3 sm:px-4 py-8 sm:py-16 lg:py-24">
                <div class="max-w-7xl mx-auto">
                    <!-- Breadcrumb -->
                    <nav class="mb-4 sm:mb-8" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm text-gray-300">
                            <li><a href="<?php echo URLROOT; ?>" class="hover:text-white transition-colors duration-200">Home</a></li>
                            <li><span class="text-gray-500">/</span></li>
                            <li><a href="<?php echo URLROOT; ?>/blogs" class="hover:text-white transition-colors duration-200">Blogs</a></li>
                            <li><span class="text-gray-500">/</span></li>
                            <li class="text-gray-400 truncate max-w-[120px] sm:max-w-xs"><?php echo htmlspecialchars($blog->title); ?></li>
                        </ol>
                    </nav>

                    <!-- Hero Grid -->
                    <div class="grid lg:grid-cols-5 gap-6 lg:gap-16 items-center">
                        <!-- Content Column -->
                        <div class="lg:col-span-3 space-y-4 sm:space-y-6 lg:space-y-10 text-center lg:text-left order-2 lg:order-1">
                            <!-- Badges -->
                            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2 sm:gap-3">
                                <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-blue-600/20 border border-blue-500/30 text-blue-200 font-medium text-xs sm:text-sm backdrop-blur-sm">
                                    <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Politieke Analyse
                                </span>
                                
                                <div class="inline-flex items-center text-gray-300 text-xs sm:text-sm bg-white/10 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full backdrop-blur-sm border border-white/20">
                                    <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="reading-minutes">5</span> min lezen
                                </div>
                            </div>
                            
                            <!-- Title & Summary -->
                            <div class="space-y-3 sm:space-y-4 lg:space-y-6">
                                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-white leading-tight tracking-tight px-2 sm:px-0">
                                    <?php echo htmlspecialchars($blog->title); ?>
                                </h1>
                                <p class="text-sm sm:text-base lg:text-xl text-gray-300 leading-relaxed max-w-3xl mx-auto lg:mx-0 px-2 sm:px-0">
                                    <?php echo htmlspecialchars($blog->summary); ?>
                                </p>
                            </div>

                            <!-- Author Info -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 lg:gap-6 justify-center lg:justify-start px-2 sm:px-0">
                                <div class="flex items-center justify-center lg:justify-start space-x-3 sm:space-x-4">
                                    <div class="relative">
                                        <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                             class="w-10 sm:w-12 lg:w-14 h-10 sm:h-12 lg:h-14 rounded-full border-2 border-white/30 shadow-xl">
                                        <div class="absolute -bottom-0.5 sm:-bottom-1 -right-0.5 sm:-right-1 w-3 sm:w-4 lg:w-5 h-3 sm:h-4 lg:h-5 bg-green-500 rounded-full border-2 border-slate-900"></div>
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <h3 class="text-white font-semibold text-sm sm:text-base lg:text-lg"><?php echo htmlspecialchars($blog->author_name); ?></h3>
                                        <p class="text-gray-300 text-xs sm:text-sm">Politiek Analist & Journalist</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-center space-x-3 sm:space-x-4 lg:space-x-6 text-gray-300 text-xs sm:text-sm">
                                    <div class="flex items-center space-x-1.5 sm:space-x-2 bg-white/5 px-2 sm:px-3 py-1 sm:py-1.5 lg:py-2 rounded-lg">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline"><?php echo date('d F Y', strtotime($blog->published_at)); ?></span>
                                        <span class="sm:hidden"><?php echo date('d M', strtotime($blog->published_at)); ?></span>
                                    </div>
                                    
                                    <button id="heroLikeButton" 
                                            class="hero-like-btn group flex items-center space-x-1.5 sm:space-x-2 bg-white/5 hover:bg-white/10 px-2 sm:px-3 py-1 sm:py-1.5 lg:py-2 rounded-lg transition-all duration-300 hover:scale-105"
                                            data-slug="<?php echo $blog->slug; ?>"
                                            aria-label="Like deze blog">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 transition-all duration-300 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span id="hero-like-count" class="group-hover:text-red-400 transition-colors text-xs sm:text-sm"><?php echo $blog->likes; ?> likes</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Image Column -->
                        <?php if ($blog->image_path): ?>
                        <div class="lg:col-span-2 order-1 lg:order-2">
                            <div class="relative group max-w-sm sm:max-w-md mx-auto lg:max-w-none">
                                <!-- Glow Effect -->
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-red-600 rounded-2xl blur-xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
                                
                                <!-- Main Image Container -->
                                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl">
                                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($blog->title); ?>"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    
                                    <!-- Image Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                
                                <!-- Decorative Elements -->
                                <div class="absolute -top-2 sm:-top-3 lg:-top-4 -right-2 sm:-right-3 lg:-right-4 w-4 sm:w-6 lg:w-8 h-4 sm:h-6 lg:h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full opacity-60 animate-pulse"></div>
                                <div class="absolute -bottom-2 sm:-bottom-3 lg:-bottom-4 -left-2 sm:-left-3 lg:-left-4 w-3 sm:w-4 lg:w-6 h-3 sm:h-4 lg:h-6 bg-gradient-to-br from-red-500 to-orange-500 rounded-full opacity-40"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <article class="relative">
        <div class="container mx-auto px-3 sm:px-4 py-8 sm:py-16">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                    
                    <!-- Video sectie (indien aanwezig) -->
                    <?php if ($blog->video_path || $blog->video_url): ?>
                    <div class="relative">
                        <?php if ($blog->video_path): ?>
                            <!-- Lokaal geüploade video -->
                            <div class="relative aspect-video bg-black">
                                <video controls class="w-full h-full rounded-t-2xl sm:rounded-t-3xl" poster="<?php echo $blog->image_path ? URLROOT . '/' . $blog->image_path : ''; ?>">
                                    <source src="<?php echo URLROOT . '/' . $blog->video_path; ?>" type="video/mp4">
                                    Je browser ondersteunt geen video weergave.
                                </video>
                            </div>
                        <?php elseif ($blog->video_url): ?>
                            <!-- Embedded video (YouTube/Vimeo) -->
                            <div class="relative aspect-video bg-black rounded-t-2xl sm:rounded-t-3xl overflow-hidden">
                                <?php
                                // YouTube URL omzetten naar embed URL
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $blog->video_url, $matches)) {
                                    $videoId = $matches[1];
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                }
                                // Vimeo URL omzetten naar embed URL
                                elseif (preg_match('/(?:vimeo\.com\/)([0-9]+)/', $blog->video_url, $matches)) {
                                    $videoId = $matches[1];
                                    $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                                }
                                ?>
                                <iframe src="<?php echo $embedUrl; ?>"
                                        class="absolute top-0 left-0 w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="p-4 sm:p-6 lg:p-8 xl:p-12">
                        <!-- Main Content -->
                        <div id="blog-content" class="prose prose-sm sm:prose-lg max-w-none">
                            <?php echo $blog->content; ?>
                        </div>
                    </div>

                    <!-- Social Actions & Share -->
                    <div class="px-4 sm:px-6 lg:px-8 xl:px-12 py-6 sm:py-8 bg-gradient-to-r from-gray-50 to-blue-50/50 border-t border-gray-100">
                        <div class="flex flex-col lg:flex-row items-center justify-between gap-4 sm:gap-6">
                            <!-- Like & Bookmark Actions -->
                            <div class="flex items-center gap-3 sm:gap-4">
                                <!-- Enhanced Like Button -->
                                <button id="likeButton" 
                                        class="group relative flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 bg-white rounded-full shadow-lg border-2 border-transparent hover:border-red-200 transition-all duration-300 transform hover:scale-105"
                                        data-slug="<?php echo $blog->slug; ?>"
                                        aria-label="Like deze blog">
                                    <div class="relative">
                                        <svg class="w-5 sm:w-6 h-5 sm:h-6 transition-all duration-300 group-hover:text-red-500" 
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" 
                                                  stroke-linejoin="round" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                        <!-- Heart particles voor animatie -->
                                        <div class="like-particles absolute inset-0 pointer-events-none">
                                            <div class="particle"></div>
                                            <div class="particle"></div>
                                            <div class="particle"></div>
                                            <div class="particle"></div>
                                            <div class="particle"></div>
                                            <div class="particle"></div>
                                        </div>
                                    </div>
                                    <span id="likeCount" class="font-semibold text-gray-700 group-hover:text-red-500 transition-colors text-sm sm:text-base"><?php echo $blog->likes; ?></span>
                                </button>

                                <!-- Bookmark Button -->
                                <button id="bookmarkButton" 
                                        class="group flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 bg-white rounded-full shadow-lg border-2 border-transparent hover:border-amber-200 transition-all duration-300 transform hover:scale-105"
                                        data-slug="<?php echo $blog->slug; ?>"
                                        aria-label="Bookmark deze blog">
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6 transition-all duration-300 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-700 group-hover:text-amber-500 transition-colors text-sm sm:text-base">Bewaren</span>
                                </button>
                            </div>

                            <!-- Share Options -->
                            <div class="flex items-center gap-2 sm:gap-3">
                                <span class="text-gray-600 font-medium mr-1 sm:mr-2 text-sm sm:text-base">Delen:</span>
                                
                                <button onclick="shareOnTwitter()" 
                                        class="p-2 sm:p-3 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors transform hover:scale-110 shadow-lg">
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </button>
                                
                                <button onclick="shareOnLinkedIn()" 
                                        class="p-2 sm:p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors transform hover:scale-110 shadow-lg">
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </button>
                                
                                <button onclick="shareOnFacebook()" 
                                        class="p-2 sm:p-3 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition-colors transform hover:scale-110 shadow-lg">
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </button>
                                
                                <button onclick="copyToClipboard()" 
                                        class="p-2 sm:p-3 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-colors transform hover:scale-110 shadow-lg">
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Enhanced Author Section -->
    <section class="py-12 sm:py-16 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="relative">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5"></div>
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23000" fill-opacity="0.02"%3E%3Cpath d="m0 40l40-40h-40z"/%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
                        
                        <div class="relative z-10 p-6 sm:p-8 lg:p-12">
                            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 sm:gap-8">
                                <!-- Author Avatar & Info -->
                                <div class="flex-shrink-0 text-center lg:text-left">
                                    <div class="relative inline-block">
                                        <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                             class="w-20 h-20 sm:w-24 sm:h-24 lg:w-32 lg:h-32 rounded-full border-4 border-white shadow-2xl">
                                        <div class="absolute -bottom-1 sm:-bottom-2 -right-1 sm:-right-2 w-6 sm:w-8 h-6 sm:h-8 bg-green-500 rounded-full border-2 sm:border-3 border-white flex items-center justify-center">
                                            <svg class="w-3 sm:w-4 h-3 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Author Details -->
                                <div class="flex-grow text-center lg:text-left">
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($blog->author_name); ?></h3>
                                    <p class="text-primary font-semibold mb-3 sm:mb-4 text-sm sm:text-base">Politiek Analist & Journalist</p>
                                    
                                    <p class="text-gray-600 leading-relaxed mb-4 sm:mb-6 max-w-2xl text-sm sm:text-base">
                                        Gespecialiseerd in Nederlandse politiek en maatschappelijke ontwikkelingen. Met meer dan 10 jaar ervaring in politieke analyse en journalistiek, brengt hij complexe politieke thema's op een begrijpelijke manier naar het publiek.
                                    </p>

                                    <!-- Author Stats -->
                                    <div class="flex flex-col sm:flex-row items-center lg:items-start gap-4 sm:gap-6 mb-4 sm:mb-6">
                                        <div class="flex items-center gap-4 sm:gap-6">
                                            <div class="text-center">
                                                <div class="text-xl sm:text-2xl font-bold text-primary">127</div>
                                                <div class="text-xs sm:text-sm text-gray-600">Artikelen</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl sm:text-2xl font-bold text-secondary">4.8K</div>
                                                <div class="text-xs sm:text-sm text-gray-600">Volgers</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl sm:text-2xl font-bold text-accent">25.6K</div>
                                                <div class="text-xs sm:text-sm text-gray-600">Weergaven</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Media Links -->
                                    <div class="flex items-center justify-center lg:justify-start gap-3 sm:gap-4">
                                        <a href="#" class="p-2 sm:p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all duration-300 transform hover:scale-110 shadow-lg">
                                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="p-2 sm:p-3 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-all duration-300 transform hover:scale-110 shadow-lg">
                                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                        </a>
                                        <a href="mailto:author@politiekpraat.nl" class="p-2 sm:p-3 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-all duration-300 transform hover:scale-110 shadow-lg">
                                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup Section -->
    <section class="py-12 sm:py-16 bg-gradient-to-r from-primary to-secondary">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-12 border border-white/20">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3 sm:mb-4">Blijf op de hoogte</h3>
                    <p class="text-base sm:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
                        Ontvang wekelijks de beste politieke analyses en updates direct in je inbox. Geen spam, alleen waardevolle content.
                    </p>
                    
                    <form id="newsletter-form" class="flex flex-col sm:flex-row gap-3 sm:gap-4 max-w-lg mx-auto">
                        <input type="email" 
                               placeholder="je@email.com" 
                               class="flex-grow px-4 sm:px-6 py-3 sm:py-4 rounded-full border-2 border-white/20 bg-white/10 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:border-white/50 transition-all duration-300 text-sm sm:text-base"
                               required>
                        <button type="submit" 
                                class="px-6 sm:px-8 py-3 sm:py-4 bg-white text-primary font-semibold rounded-full hover:bg-gray-50 transition-all duration-300 transform hover:scale-105 shadow-lg text-sm sm:text-base">
                            Aanmelden
                        </button>
                    </form>
                    
                    <p class="text-xs sm:text-sm text-white/70 mt-3 sm:mt-4">
                        Door je aan te melden ga je akkoord met onze <a href="#" class="underline hover:text-white">privacyverklaring</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Related Blogs Section -->
    <section class="py-16 sm:py-20 bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/20">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-12 sm:mb-16">
                    <div class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                        <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Gerelateerde Artikelen
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        Meer politieke inzichten
                    </h2>
                    <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto">
                        Verdiep je kennis met deze geselecteerde artikelen over actuele politieke ontwikkelingen
                    </p>
                </div>

                <!-- Related Blogs Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <?php 
                    // Haal andere blogs op (maximaal 7 om er zeker van te zijn dat we 6 hebben na filtering)
                    $otherBlogs = (new BlogController())->getAll(7);
                    $count = 0;
                    foreach ($otherBlogs as $relatedBlog): 
                        if ($relatedBlog->slug !== $blog->slug && $count < 6): 
                            $count++;
                    ?>
                        <article class="group bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            <div class="relative">
                                <a href="<?php echo URLROOT . '/blogs/view/' . $relatedBlog->slug; ?>" class="block">
                                    <?php if ($relatedBlog->image_path): ?>
                                        <div class="relative h-40 sm:h-48 overflow-hidden">
                                            <img src="<?php echo URLROOT . '/' . $relatedBlog->image_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($relatedBlog->title); ?>"
                                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="h-40 sm:h-48 bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                                            <svg class="w-12 sm:w-16 h-12 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <!-- New Badge -->
                                <?php 
                                $published_time = strtotime($relatedBlog->published_at);
                                $twelve_hours_ago = time() - (12 * 3600);
                                if ($published_time > $twelve_hours_ago): 
                                ?>
                                    <div class="absolute top-3 sm:top-4 right-3 sm:right-4 z-10">
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-xs font-bold shadow-lg">
                                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white rounded-full mr-1.5 sm:mr-2 animate-pulse"></span>
                                            NIEUW
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 sm:p-6">
                                <!-- Meta Info -->
                                <div class="flex items-center justify-between mb-3 sm:mb-4 text-xs sm:text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-5 sm:w-6 h-5 sm:h-6 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-semibold text-primary"><?php echo substr($relatedBlog->author_name, 0, 1); ?></span>
                                        </div>
                                        <span class="truncate"><?php echo htmlspecialchars($relatedBlog->author_name); ?></span>
                                    </div>
                                    <span><?php echo date('d M Y', strtotime($relatedBlog->published_at)); ?></span>
                                </div>

                                <!-- Title & Summary -->
                                <a href="<?php echo URLROOT . '/blogs/view/' . $relatedBlog->slug; ?>" class="block">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        <?php echo htmlspecialchars($relatedBlog->title); ?>
                                    </h3>
                                </a>
                                
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 mb-3 sm:mb-4">
                                    <?php echo htmlspecialchars($relatedBlog->summary); ?>
                                </p>
                                
                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-3 sm:pt-4 border-t border-gray-100">
                                    <a href="<?php echo URLROOT . '/blogs/view/' . $relatedBlog->slug; ?>" 
                                       class="inline-flex items-center text-primary font-medium text-xs sm:text-sm hover:text-primary-dark transition-colors">
                                        Lees verder
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                    
                                    <div class="flex items-center text-gray-400 text-xs">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span><?php echo (isset($relatedBlog->likes) && $relatedBlog->likes > 0) ? $relatedBlog->likes : '0'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <!-- View All Button -->
                <div class="text-center mt-8 sm:mt-12">
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-full hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm sm:text-base">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-4 4m4-4l-4-4"></path>
                        </svg>
                        Bekijk alle blogs
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php if (isAdmin()): ?>
<div class="fixed bottom-4 right-4 opacity-70 hover:opacity-100 z-50">
    <a href="?debug_photo=1" class="text-xs bg-gray-800 text-white py-1 px-2 rounded hover:bg-gray-700">
        Debug Photo
    </a>
</div>
<?php endif; ?>

<!-- Enhanced CSS Styles -->
<style>
/* Enhanced Typography and Reading Experience */
.prose {
    @apply text-gray-800 leading-relaxed;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.prose h2 {
    @apply text-lg md:text-2xl lg:text-3xl font-bold mt-6 md:mt-12 mb-3 md:mb-6 text-gray-900;
    position: relative;
    padding-bottom: 0.5rem;
}

.prose h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 3rem;
    height: 3px;
    background: linear-gradient(to right, theme('colors.primary'), theme('colors.secondary'));
    border-radius: 2px;
}

.prose h3 {
    @apply text-base md:text-xl lg:text-2xl font-semibold mt-4 md:mt-8 mb-2 md:mb-4 text-gray-900;
}

.prose h4 {
    @apply text-sm md:text-lg lg:text-xl font-semibold mt-3 md:mt-6 mb-1 md:mb-3 text-gray-900;
}

.prose p {
    @apply text-sm md:text-base lg:text-lg leading-relaxed mb-3 md:mb-6 text-gray-700;
}

.prose ul, .prose ol {
    @apply my-3 md:my-6 ml-3 md:ml-6 space-y-1 md:space-y-2;
}

.prose li {
    @apply text-sm md:text-base lg:text-lg leading-relaxed text-gray-700;
}

.prose blockquote {
    @apply border-l-4 border-primary/30 pl-4 md:pl-6 italic my-6 md:my-8 bg-gray-50 py-3 md:py-4 rounded-r-lg text-sm md:text-base;
}

.prose img {
    @apply rounded-xl my-6 md:my-8 w-full shadow-lg;
}

.prose a {
    @apply text-primary hover:text-primary-dark underline decoration-2 underline-offset-2 transition-colors duration-200;
}

.prose code {
    @apply bg-primary/10 text-primary rounded px-1.5 md:px-2 py-0.5 md:py-1 text-xs md:text-sm font-mono border;
}

.prose pre {
    @apply bg-gray-900 text-gray-100 rounded-xl p-3 md:p-6 overflow-x-auto my-6 md:my-8 shadow-lg text-xs md:text-sm;
}

.prose pre code {
    @apply bg-transparent text-inherit p-0 border-0;
}

/* Mobile-specific improvements */
@media (max-width: 640px) {
    .prose {
        font-size: 14px;
        line-height: 1.6;
    }
    
    .prose h1 {
        font-size: 1.5rem;
        line-height: 1.3;
        margin-bottom: 1rem;
    }
    
    .prose h2 {
        font-size: 1.25rem;
        line-height: 1.4;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    
    .prose h3 {
        font-size: 1.125rem;
        line-height: 1.4;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .prose h4 {
        font-size: 1rem;
        line-height: 1.4;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .prose p {
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .prose ul, .prose ol {
        margin: 1rem 0;
        margin-left: 1rem;
    }
    
    .prose li {
        font-size: 0.875rem;
        line-height: 1.6;
    }
    
    .prose blockquote {
        padding-left: 1rem;
        margin: 1.5rem 0;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        font-size: 0.875rem;
    }
    
    .prose img {
        margin: 1.5rem 0;
    }
    
    .prose pre {
        padding: 0.75rem;
        margin: 1.5rem 0;
        font-size: 0.75rem;
    }
    
    .prose code {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Enhanced Reading Progress Bar */
#reading-progress {
    background: linear-gradient(90deg, #1a365d 0%, #c41e3a 50%, #00796b 100%);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Enhanced Like Animation */
.like-particles .particle {
    @apply absolute w-1 h-1 bg-red-500 rounded-full opacity-0 pointer-events-none;
    transform-origin: center;
}

.liked .like-particles .particle {
    animation: particle-burst 0.8s ease-out forwards;
}

.liked .like-particles .particle:nth-child(1) { animation-delay: 0ms; --direction: 45deg; }
.liked .like-particles .particle:nth-child(2) { animation-delay: 100ms; --direction: 90deg; }
.liked .like-particles .particle:nth-child(3) { animation-delay: 200ms; --direction: 135deg; }
.liked .like-particles .particle:nth-child(4) { animation-delay: 300ms; --direction: 225deg; }
.liked .like-particles .particle:nth-child(5) { animation-delay: 400ms; --direction: 270deg; }
.liked .like-particles .particle:nth-child(6) { animation-delay: 500ms; --direction: 315deg; }

@keyframes particle-burst {
    0% {
        opacity: 1;
        transform: translate(0, 0) scale(1);
    }
    100% {
        opacity: 0;
        transform: translate(
            calc(cos(var(--direction)) * 30px),
            calc(sin(var(--direction)) * 30px)
        ) scale(0);
    }
}

/* Like Button States */
#likeButton.liked {
    @apply bg-red-50 border-red-300 text-red-600;
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
}

#likeButton.liked svg {
    fill: currentColor;
    animation: heartbeat 0.6s ease-in-out;
}

/* Hero Like Button States */
#heroLikeButton.liked {
    @apply bg-red-500/20 text-red-300;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

#heroLikeButton.liked svg {
    fill: currentColor;
    color: #fca5a5;
    animation: heartbeat 0.6s ease-in-out;
}

#heroLikeButton.liked span {
    color: #fca5a5;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.2); }
    50% { transform: scale(1); }
    75% { transform: scale(1.1); }
}

/* Bookmark Button States */
#bookmarkButton.bookmarked {
    @apply bg-amber-50 border-amber-300 text-amber-600;
}

#bookmarkButton.bookmarked svg {
    fill: currentColor;
}

/* Utility Classes */
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

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

/* Print styles */
@media print {
    #reading-progress,
    .social-actions,
    .related-blogs,
    .newsletter-signup {
        display: none !important;
    }
    
    .prose {
        @apply text-black;
    }
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reading Progress Bar
    function updateReadingProgress() {
        const article = document.getElementById('blog-content');
        if (!article) return;
        
        const articleHeight = article.offsetHeight;
        const articleTop = article.offsetTop;
        const scrollPosition = window.scrollY;
        const windowHeight = window.innerHeight;
        
        const progress = Math.max(0, Math.min(100, 
            ((scrollPosition + windowHeight - articleTop) / articleHeight) * 100
        ));
        
        document.getElementById('reading-progress').style.width = progress + '%';
    }
    
    // Reading Time Calculation
    function calculateReadingTime() {
        const content = document.getElementById('blog-content');
        if (!content) return;
        
        const text = content.textContent || content.innerText;
        const words = text.trim().split(/\s+/).length;
        const wordsPerMinute = 200;
        const minutes = Math.ceil(words / wordsPerMinute);
        
        const readingTimeElement = document.getElementById('reading-minutes');
        if (readingTimeElement) {
            readingTimeElement.textContent = minutes;
        }
    }
    
    // Like functionality
    const likeButton = document.getElementById('likeButton');
    const heroLikeButton = document.getElementById('heroLikeButton');
    const likeCount = document.getElementById('likeCount');
    const heroLikeCount = document.getElementById('hero-like-count');
    const slug = likeButton?.dataset.slug || heroLikeButton?.dataset.slug;
    let isProcessing = false;
    
    const likedBlogs = JSON.parse(localStorage.getItem('likedBlogs') || '{}');
    
    function updateLikeButtonState(isLiked) {
        if (likeButton) {
            if (isLiked) {
                likeButton.classList.add('liked');
            } else {
                likeButton.classList.remove('liked');
            }
        }
        
        if (heroLikeButton) {
            if (isLiked) {
                heroLikeButton.classList.add('liked');
            } else {
                heroLikeButton.classList.remove('liked');
            }
        }
    }
    
    async function handleLike(button) {
        if (isProcessing) return;
        
        const action = likedBlogs[slug] ? 'unlike' : 'like';
        isProcessing = true;
        
        // Disable both buttons
        if (likeButton) likeButton.disabled = true;
        if (heroLikeButton) heroLikeButton.disabled = true;
        
        try {
            const response = await fetch(`<?php echo URLROOT; ?>/blogs/like/${slug}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update both counters
                if (likeCount) likeCount.textContent = data.likes;
                if (heroLikeCount) heroLikeCount.textContent = data.likes + ' likes';
                
                if (action === 'like') {
                    likedBlogs[slug] = true;
                    updateLikeButtonState(true);
                } else {
                    delete likedBlogs[slug];
                    updateLikeButtonState(false);
                }
                
                localStorage.setItem('likedBlogs', JSON.stringify(likedBlogs));
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            isProcessing = false;
            if (likeButton) likeButton.disabled = false;
            if (heroLikeButton) heroLikeButton.disabled = false;
        }
    }
    
    // Initialize like state
    if (slug) {
        updateLikeButtonState(likedBlogs[slug]);
    }
    
    // Add event listeners to both like buttons
    likeButton?.addEventListener('click', () => handleLike(likeButton));
    heroLikeButton?.addEventListener('click', () => handleLike(heroLikeButton));
    
    // Bookmark functionality
    const bookmarkButton = document.getElementById('bookmarkButton');
    const bookmarkedBlogs = JSON.parse(localStorage.getItem('bookmarkedBlogs') || '{}');
    
    function updateBookmarkState(isBookmarked) {
        if (!bookmarkButton) return;
        
        if (isBookmarked) {
            bookmarkButton.classList.add('bookmarked');
        } else {
            bookmarkButton.classList.remove('bookmarked');
        }
    }
    
    // Initialize bookmark state
    if (slug) {
        updateBookmarkState(bookmarkedBlogs[slug]);
    }
    
    bookmarkButton?.addEventListener('click', function() {
        if (bookmarkedBlogs[slug]) {
            delete bookmarkedBlogs[slug];
            updateBookmarkState(false);
            showNotification('Bookmark verwijderd', 'info');
        } else {
            bookmarkedBlogs[slug] = {
                title: '<?php echo addslashes($blog->title); ?>',
                url: window.location.href,
                date: new Date().toISOString()
            };
            updateBookmarkState(true);
            showNotification('Blog opgeslagen in bookmarks', 'success');
        }
        
        localStorage.setItem('bookmarkedBlogs', JSON.stringify(bookmarkedBlogs));
    });
    
    // Newsletter Form
    const newsletterForm = document.getElementById('newsletter-form');
    newsletterForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        // Simulate newsletter signup
        showNotification('Bedankt voor je aanmelding! Je ontvangt een bevestigingsmail.', 'success');
        this.reset();
    });
    
    // Initialize all functions
    calculateReadingTime();
    
    // Add scroll listeners
    window.addEventListener('scroll', updateReadingProgress);
    window.addEventListener('resize', updateReadingProgress);
    
    // Initial progress update
    updateReadingProgress();
});

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.className += ` ${bgColors[type] || bgColors.info} text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 4000);
}

// Enhanced Share Functions
function shareOnTwitter() {
    const title = '<?php echo addslashes($blog->title); ?>';
    const url = window.location.href;
    const text = `${title} via @PolitiekPraat`;
    
    window.open(
        `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=400'
    );
}

function shareOnLinkedIn() {
    const title = '<?php echo addslashes($blog->title); ?>';
    const url = window.location.href;
    const summary = '<?php echo addslashes($blog->summary); ?>';
    
    window.open(
        `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}&title=${encodeURIComponent(title)}&summary=${encodeURIComponent(summary)}`,
        '_blank',
        'width=600,height=400'
    );
}

function shareOnFacebook() {
    const url = window.location.href;
    
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=400'
    );
}

async function copyToClipboard() {
    try {
        await navigator.clipboard.writeText(window.location.href);
        showNotification('Link gekopieerd naar klembord!', 'success');
    } catch (err) {
        console.error('Kon link niet kopiëren:', err);
        showNotification('Kon link niet kopiëren', 'error');
    }
}
</script>