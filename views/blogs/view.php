<?php 
// Voeg dynamische meta tags toe voor deze specifieke blog
$pageTitle = htmlspecialchars($blog->title) . ' | PolitiekPraat';
$pageDescription = htmlspecialchars($blog->summary);
$pageImage = $blog->image_path ? getBlogImageUrl($blog->image_path) : URLROOT . '/public/img/og-image.jpg';

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
    <!-- Professionele Hero Section -->
    <section class="relative text-white <?php echo $blog->image_path ? 'bg-cover bg-center' : 'bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900'; ?>" style="<?php echo $blog->image_path ? 'background-image: url(\'' . getBlogImageUrl($blog->image_path) . '\');' : ''; ?>">
        <!-- Overlay -->
        <div class="absolute inset-0 <?php echo $blog->image_path ? 'bg-black/60' : ''; ?>">
            <?php if (!$blog->image_path): ?>
                <div class="absolute inset-0 opacity-30 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-transparent to-red-900/20"></div>
            <?php endif; ?>
        </div>
        
        <div class="relative z-10 container mx-auto px-4">
            <div class="max-w-4xl mx-auto flex flex-col items-center justify-center min-h-[50vh] md:min-h-[60vh] py-16 text-center">
                
                <!-- Breadcrumb -->
                <nav class="w-full mb-6" aria-label="Breadcrumb">
                    <ol class="flex justify-center items-center space-x-2 text-sm text-gray-300">
                        <li><a href="<?php echo URLROOT; ?>" class="hover:text-white transition-colors duration-200">Home</a></li>
                        <li><span class="text-gray-500">/</span></li>
                        <li><a href="<?php echo URLROOT; ?>/blogs" class="hover:text-white transition-colors duration-200">Blogs</a></li>
                        <li><span class="text-gray-500">/</span></li>
                        <li class="text-gray-400 truncate max-w-xs"><?php echo htmlspecialchars($blog->title); ?></li>
                    </ol>
                </nav>

                <!-- Badges -->
                <div class="flex items-center gap-3 mb-6">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-600/20 border border-blue-500/30 text-blue-200 font-medium text-sm backdrop-blur-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Politieke Analyse
                    </span>
                    
                    <div class="inline-flex items-center text-gray-300 text-sm bg-white/10 px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="reading-minutes">5</span> min lezen
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-8 [text-shadow:_0_2px_4px_rgb(0_0_0_/_50%)]">
                    <?php echo htmlspecialchars($blog->title); ?>
                </h1>

                <!-- Author Info & Actions -->
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="flex items-center space-x-4">
                        <img src="<?php echo URLROOT; ?>/public/images/naoufal-foto.jpg" 
                             onerror="if(this.src !== '<?php echo URLROOT; ?>/images/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/images/naoufal-foto.jpg'; else if(this.src !== '<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg';"
                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                             class="w-14 h-14 rounded-full border-2 border-white/30 shadow-xl object-cover">
                        <div class="text-left">
                            <h3 class="text-white font-semibold text-lg"><?php echo htmlspecialchars($blog->author_name); ?></h3>
                            <p class="text-gray-300 text-sm"><?php 
                                $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                                echo $formatter->format(strtotime($blog->published_at)); 
                            ?></p>
                        </div>
                    </div>
                    
                    <button id="heroLikeButton" 
                            class="hero-like-btn group flex items-center space-x-2 bg-white/5 hover:bg-white/10 px-4 py-2 rounded-full transition-all duration-300 hover:scale-105 border border-white/20"
                            data-slug="<?php echo $blog->slug; ?>"
                            aria-label="Like deze blog">
                        <svg class="w-5 h-5 transition-all duration-300 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="hero-like-count" class="group-hover:text-red-400 transition-colors text-sm font-semibold"><?php echo $blog->likes; ?> likes</span>
                    </button>
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
                                <video controls class="w-full h-full rounded-t-2xl sm:rounded-t-3xl" poster="<?php echo $blog->image_path ? getBlogImageUrl($blog->image_path) : ''; ?>">
                                    <source src="<?php echo getBlogVideoUrl($blog->video_path); ?>" type="video/mp4">
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

                    <!-- Podcast sectie (indien aanwezig) -->
                    <?php if (!empty($blog->soundcloud_url)): ?>
                    <div class="border-b border-gray-100">
                        <div class="p-3 sm:p-4 bg-gradient-to-r from-orange-50/50 to-red-50/50">
                            <!-- Header met NIEUW badge -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-500 to-orange-500 text-white">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span>
                                        NIEUW
                                    </span>
                                    <p class="text-sm font-medium text-gray-900">Luister ook naar de podcast</p>
                                </div>
                                
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                            
                            <!-- Compacte SoundCloud Player -->
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                                <?php
                                // Compacte SoundCloud embed URL
                                $soundcloudCompactUrl = 'https://w.soundcloud.com/player/?url=' . urlencode($blog->soundcloud_url) . '&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false&visual=false';
                                ?>
                                
                                <iframe class="w-full border-0 soundcloud-iframe" 
                                        height="120" 
                                        scrolling="no" 
                                        frameborder="no" 
                                        allow="autoplay" 
                                        src="<?php echo htmlspecialchars($soundcloudCompactUrl); ?>">
                                </iframe>
                            </div>
                        </div>
                    </div>
                    
                    <?php elseif (!empty($blog->audio_path) || !empty($blog->audio_url)): ?>
                    <!-- Fallback voor andere audio types -->
                    <div class="border-b border-gray-100">
                        <div class="p-3 sm:p-4 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500 to-indigo-500 text-white">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span>
                                        AUDIO
                                    </span>
                                    <p class="text-sm font-medium text-gray-900">Luister ook naar de podcast</p>
                                </div>
                                
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                <?php if (!empty($blog->audio_url)): ?>
                                    <?php
                                    // Extracteer file ID uit Google Drive URL
                                    $fileId = '';
                                    if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $blog->audio_url, $matches)) {
                                        $fileId = $matches[1];
                                    } elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $blog->audio_url, $matches)) {
                                        $fileId = $matches[1];
                                    }
                                    ?>
                                    
                                    <?php if ($fileId): ?>
                                        <div class="text-center">
                                            <button onclick="loadGoogleDrivePodcast('<?php echo $fileId; ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-sm text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h12a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                                </svg>
                                                Podcast afspelen
                                            </button>
                                            <audio id="googleDrivePodcast" controls class="w-full mt-3" preload="none" style="display: none;">
                                                <source src="https://docs.google.com/uc?export=download&id=<?php echo $fileId; ?>" type="audio/mpeg">
                                            </audio>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php elseif (!empty($blog->audio_path)): ?>
                                    <audio controls class="w-full" preload="metadata">
                                        <source src="<?php echo URLROOT . '/' . $blog->audio_path; ?>" type="audio/mpeg">
                                        Je browser ondersteunt geen audio weergave.
                                    </audio>
                                <?php endif; ?>
                            </div>
                        </div>
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
                    <div class="px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-10 bg-gradient-to-br from-slate-50 via-blue-50/80 to-indigo-50/60 border-t border-gray-200/50">
                        <div class="max-w-4xl mx-auto">
                            <!-- Action Buttons Row -->
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-8 mb-8">
                                <!-- Enhanced Like Button -->
                                <button id="likeButton" 
                                        class="group relative flex items-center gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 hover:border-red-200/60 transition-all duration-500 transform hover:scale-105 hover:shadow-xl"
                                        data-slug="<?php echo $blog->slug; ?>"
                                        aria-label="Like deze blog">
                                    <div class="relative">
                                        <svg class="w-6 h-6 transition-all duration-300 group-hover:text-red-500" 
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
                                    <div class="flex flex-col items-start">
                                        <span id="likeCount" class="font-bold text-lg text-gray-800 group-hover:text-red-500 transition-colors"><?php echo $blog->likes; ?></span>
                                    </div>
                                </button>

                                <!-- Politieke Bias Detector Button -->
                                <button id="biasButton" 
                                        class="group relative flex items-center gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 backdrop-blur-sm rounded-2xl shadow-lg border border-purple-200/50 hover:border-purple-300/60 transition-all duration-500 transform hover:scale-105 hover:shadow-xl"
                                        data-slug="<?php echo $blog->slug; ?>"
                                        aria-label="Analyseer politieke bias">
                                    <div class="relative">
                                        <svg class="w-6 h-6 text-purple-600 transition-all duration-300 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="font-bold text-lg text-purple-700 group-hover:text-purple-800 transition-colors">Politieke Bias</span>
                                    </div>
                                </button>

                                <!-- Party Perspective Button -->
                                <button id="partyPerspectiveButton" 
                                        class="group relative flex items-center gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-orange-500/10 to-red-500/10 backdrop-blur-sm rounded-2xl shadow-lg border border-orange-200/50 hover:border-orange-300/60 transition-all duration-500 transform hover:scale-105 hover:shadow-xl"
                                        data-slug="<?php echo $blog->slug; ?>"
                                        aria-label="Bekijk partij perspectieven">
                                    <div class="relative">
                                        <svg class="w-6 h-6 text-orange-600 transition-all duration-300 group-hover:text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="font-bold text-lg text-orange-700 group-hover:text-orange-800 transition-colors">Leider Reacties</span>
                                    </div>
                                </button>
                            </div>

                            <!-- Divider -->
                            <div class="relative mb-8">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300/50"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="bg-gradient-to-br from-slate-50 via-blue-50/80 to-indigo-50/60 px-4 text-gray-500 font-medium">Deel dit artikel</span>
                                </div>
                            </div>

                            <!-- Share Options -->
                            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                                <!-- Twitter/X share button -->
                                <button onclick="window.open('https://twitter.com/intent/tweet?text=<?php echo urlencode($blog->title); ?>&url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')" 
                                        class="group flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-sky-500 to-blue-500 text-white rounded-xl hover:from-sky-600 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    <span class="hidden sm:inline font-medium">Twitter</span>
                                </button>
                                
                                <!-- LinkedIn share button -->
                                <button onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                        class="group flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    <span class="hidden sm:inline font-medium">LinkedIn</span>
                                </button>
                                
                                <!-- Facebook share button -->
                                <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                        class="group flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-800 to-indigo-800 text-white rounded-xl hover:from-blue-900 hover:to-indigo-900 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span class="hidden sm:inline font-medium">Facebook</span>
                                </button>
                                
                                <!-- Copy link button -->
                                <button onclick="navigator.clipboard.writeText('<?php echo URLROOT . '/blogs/' . $blog->slug; ?>').then(() => showNotification('Link gekopieerd!', 'success'))"
                                        class="group flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline font-medium">Kopiëren</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>



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
                                            <img src="<?php echo getBlogImageUrl($relatedBlog->image_path); ?>" 
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
                                    <span><?php 
                                        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
                                        echo str_replace('.', '', $formatter->format(strtotime($relatedBlog->published_at))); 
                                    ?></span>
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

<!-- Bias Analysis Modal -->
<div id="biasModal" class="fixed inset-0 z-50 hidden">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Politieke Bias Analyse</h3>
                            <p class="text-purple-100 text-sm">AI-gedreven analyse van politieke orientatie</p>
                        </div>
                    </div>
                    <button id="closeBiasModal" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <!-- Loading State -->
                <div id="biasLoading" class="text-center py-8">
                    <div class="inline-flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                        <span class="text-gray-600 font-medium">Artikel wordt geanalyseerd...</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-2">Dit kan een paar seconden duren</p>
                </div>
                
                <!-- Error State -->
                <div id="biasError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <h4 class="text-red-800 font-medium mb-1">Analyse mislukt</h4>
                                <p id="biasErrorMessage" class="text-red-700 text-sm"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Results -->
                <div id="biasResults" class="hidden space-y-6">
                    <!-- Overall Orientation -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Politieke Orientatie</h4>
                        <div class="flex items-center space-x-4">
                            <div id="orientationBadge" class="px-4 py-2 rounded-full font-bold text-lg"></div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-600 mb-1">Zekerheid</div>
                                <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div id="confidenceBar" class="h-full bg-gradient-to-r from-purple-500 to-blue-500 transition-all duration-500"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span id="confidenceText">--</span>% zeker
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reasoning -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Analyse Uitleg</h4>
                        <p id="reasoningText" class="text-gray-700 leading-relaxed"></p>
                    </div>
                    
                    <!-- Detailed Indicators -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Gedetailleerde Indicatoren</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 mb-2">Economisch</div>
                                <div id="economicIndicator" class="px-3 py-1 rounded-full text-sm font-medium"></div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600 mb-2">Sociaal</div>
                                <div id="socialIndicator" class="px-3 py-1 rounded-full text-sm font-medium"></div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600 mb-2">Immigratie</div>
                                <div id="immigrationIndicator" class="px-3 py-1 rounded-full text-sm font-medium"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Samenvatting</h4>
                        <p id="summaryText" class="text-gray-700 leading-relaxed"></p>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <strong>Disclaimer:</strong> Deze analyse is gebaseerd op AI en dient als indicatie. Politieke standpunten zijn complex en deze tool geeft een vereenvoudigde weergave.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <button id="closeBiasModalFooter" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Sluiten
                    </button>
                    <button id="retryBiasAnalysis" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors hidden">
                        Opnieuw proberen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Party Perspective Modal -->
<div id="partyModal" class="fixed inset-0 z-50 hidden">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Leider Reacties</h3>
                            <p class="text-orange-100 text-sm">Kies een partijleider voor hun AI-gegenereerde reactie</p>
                        </div>
                    </div>
                    <button id="closePartyModal" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <!-- Party Selection Grid -->
                <div id="partySelectionGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                    <!-- PVV -->
                    <button type="button" class="party-select-btn" data-party="PVV">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-orange-300 hover:bg-orange-50 transition-all cursor-pointer group">
                            <img src="https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg" 
                                 alt="PVV" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-orange-700">PVV</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Geert Wilders</p>
                            <img src="/partijleiders/geert.jpg" alt="Geert Wilders" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- VVD -->
                    <button type="button" class="party-select-btn" data-party="VVD">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/vvd.nl" 
                                 alt="VVD" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-blue-700">VVD</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Dilan Yeşilgöz</p>
                            <img src="/partijleiders/dilan.jpg" alt="Dilan Yeşilgöz" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- GL-PvdA -->
                    <button type="button" class="party-select-btn" data-party="GL-PvdA">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all cursor-pointer group">
                            <img src="https://i.ibb.co/67hkc5Hv/gl-pvda.png" 
                                 alt="GL-PvdA" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-green-700">GL-PvdA</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Frans Timmermans</p>
                            <img src="/partijleiders/frans.jpg" alt="Frans Timmermans" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- NSC -->
                    <button type="button" class="party-select-btn" data-party="NSC">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-purple-300 hover:bg-purple-50 transition-all cursor-pointer group">
                            <img src="https://i.ibb.co/YT2fJZb4/nsc.png" 
                                 alt="NSC" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-purple-700">NSC</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Nicolien van Vroonhoven</p>
                            <img src="https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg" alt="Nicolien van Vroonhoven" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- BBB -->
                    <button type="button" class="party-select-btn" data-party="BBB">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-yellow-300 hover:bg-yellow-50 transition-all cursor-pointer group">
                            <img src="https://i.ibb.co/qMjw7jDV/bbb.png" 
                                 alt="BBB" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-yellow-700">BBB</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Caroline van der Plas</p>
                            <img src="/partijleiders/plas.jpg" alt="Caroline van der Plas" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- D66 -->
                    <button type="button" class="party-select-btn" data-party="D66">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-teal-300 hover:bg-teal-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/d66.nl" 
                                 alt="D66" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-teal-700">D66</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Rob Jetten</p>
                            <img src="/partijleiders/rob.jpg" alt="Rob Jetten" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- SP -->
                    <button type="button" class="party-select-btn" data-party="SP">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/sp.nl" 
                                 alt="SP" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-red-700">SP</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Jimmy Dijk</p>
                            <img src="/partijleiders/jimmy.jpg" alt="Jimmy Dijk" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- PvdD -->
                    <button type="button" class="party-select-btn" data-party="PvdD">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-lime-300 hover:bg-lime-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/partijvoordedieren.nl" 
                                 alt="PvdD" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-lime-700">PvdD</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Esther Ouwehand</p>
                            <img src="/partijleiders/esther.jpg" alt="Esther Ouwehand" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- CDA -->
                    <button type="button" class="party-select-btn" data-party="CDA">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-sky-300 hover:bg-sky-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/cda.nl" 
                                 alt="CDA" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-sky-700">CDA</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Henri Bontenbal</p>
                            <img src="/partijleiders/Henri.jpg" alt="Henri Bontenbal" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- JA21 -->
                    <button type="button" class="party-select-btn" data-party="JA21">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/ja21.nl" 
                                 alt="JA21" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-indigo-700">JA21</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Joost Eerdmans</p>
                            <img src="/partijleiders/joost.jpg" alt="Joost Eerdmans" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- SGP -->
                    <button type="button" class="party-select-btn" data-party="SGP">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-gray-400 hover:bg-gray-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/sgp.nl" 
                                 alt="SGP" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-gray-700">SGP</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Chris Stoffer</p>
                            <img src="/partijleiders/Chris.jpg" alt="Chris Stoffer" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- FvD -->
                    <button type="button" class="party-select-btn" data-party="FvD">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-amber-300 hover:bg-amber-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/fvd.nl" 
                                 alt="FvD" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-amber-700">FvD</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Thierry Baudet</p>
                            <img src="/partijleiders/thierry.jpg" alt="Thierry Baudet" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- DENK -->
                    <button type="button" class="party-select-btn" data-party="DENK">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-cyan-300 hover:bg-cyan-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/bewegingdenk.nl" 
                                 alt="DENK" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-cyan-700">DENK</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Stephan van Baarle</p>
                            <img src="/partijleiders/baarle.jpg" alt="Stephan van Baarle" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- Volt -->
                    <button type="button" class="party-select-btn" data-party="Volt">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-violet-300 hover:bg-violet-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/voltnederland.org" 
                                 alt="Volt" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-violet-700">Volt</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Laurens Dassen</p>
                            <img src="/partijleiders/dassen.jpg" alt="Laurens Dassen" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                    
                    <!-- CU -->
                    <button type="button" class="party-select-btn" data-party="CU">
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-emerald-300 hover:bg-emerald-50 transition-all cursor-pointer group">
                            <img src="https://logo.clearbit.com/christenunie.nl" 
                                 alt="CU" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                            <h4 class="font-bold text-sm text-gray-900 group-hover:text-emerald-700">CU</h4>
                            <p class="text-xs text-gray-600 mt-1 leader-name">Mirjam Bikker</p>
                            <img src="https://i.ibb.co/wh3wwQ66/Bikker.jpg" alt="Mirjam Bikker" class="leader-photo w-12 h-12 rounded-full mx-auto mt-2 object-cover border-2 border-gray-200 hidden">
                        </div>
                    </button>
                </div>
                
                <!-- Loading State -->
                <div id="partyLoading" class="hidden text-center py-8">
                    <div class="inline-flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                        <span class="text-gray-600 font-medium">Perspectief wordt gegenereerd...</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-2">Dit kan een paar seconden duren</p>
                </div>
                
                <!-- Error State -->
                <div id="partyError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <h4 class="text-red-800 font-medium mb-1">Genereren mislukt</h4>
                                <p id="partyErrorMessage" class="text-red-700 text-sm"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Results -->
                <div id="partyResults" class="hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-orange-50 rounded-xl p-6 mb-6">
                        <div class="flex items-start space-x-4">
                            <img id="partyResultLogo" src="" alt="" class="w-20 h-20 object-contain">
                            <div class="flex-1">
                                <h4 id="partyResultName" class="text-xl font-bold text-gray-900 mb-1"></h4>
                                <p id="partyResultLeader" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="prose prose-lg max-w-none">
                        <div id="partyResultContent" class="bg-white border border-gray-200 rounded-xl p-6"></div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <button id="backToPartySelection" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors hidden">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Terug naar selectie
                    </button>
                    <div class="flex-1"></div>
                    <button id="closePartyModalFooter" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isAdmin()): ?>
<div class="fixed bottom-4 right-4 opacity-70 hover:opacity-100 z-50">
    <a href="?debug_photo=1" class="text-xs bg-gray-800 text-white py-1 px-2 rounded hover:bg-gray-700">
        Debug Photo
    </a>
</div>
<?php endif; ?>

<?php require_once 'views/templates/footer.php'; ?>

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

/* Mobile SoundCloud Player Responsive */
@media (max-width: 640px) {
    .soundcloud-iframe {
        height: 100px !important;
    }
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
    
    // Bias Analysis Functionality
    const biasButton = document.getElementById('biasButton');
    const biasModal = document.getElementById('biasModal');
    const closeBiasModal = document.getElementById('closeBiasModal');
    const closeBiasModalFooter = document.getElementById('closeBiasModalFooter');
    const retryBiasAnalysis = document.getElementById('retryBiasAnalysis');
    
    // Bias analysis event listeners
    biasButton?.addEventListener('click', function() {
        const slug = this.getAttribute('data-slug');
        if (slug) {
            showBiasModal();
            performBiasAnalysis(slug);
        }
    });
    
    closeBiasModal?.addEventListener('click', hideBiasModal);
    closeBiasModalFooter?.addEventListener('click', hideBiasModal);
    retryBiasAnalysis?.addEventListener('click', function() {
        const slug = biasButton?.getAttribute('data-slug');
        if (slug) {
            performBiasAnalysis(slug);
        }
    });
    
         // Close modal on background click
     biasModal?.addEventListener('click', function(e) {
         if (e.target === this) {
             hideBiasModal();
         }
     });
     
     // Party Perspective Functionality
     const partyPerspectiveButton = document.getElementById('partyPerspectiveButton');
     const partyModal = document.getElementById('partyModal');
     const closePartyModal = document.getElementById('closePartyModal');
     const closePartyModalFooter = document.getElementById('closePartyModalFooter');
     const backToPartySelection = document.getElementById('backToPartySelection');
     let currentMode = 'leader'; // Always leader mode
     
     // Party data with logos
     const partyData = {
         'PVV': {
             name: 'Partij voor de Vrijheid',
             leader: 'Geert Wilders',
             logo: 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
             leaderPhoto: '/partijleiders/geert.jpg'
         },
         'VVD': {
             name: 'Volkspartij voor Vrijheid en Democratie',
             leader: 'Dilan Yeşilgöz-Zegerius',
             logo: 'https://logo.clearbit.com/vvd.nl',
             leaderPhoto: '/partijleiders/dilan.jpg'
         },
         'GL-PvdA': {
             name: 'GroenLinks-PvdA',
             leader: 'Frans Timmermans',
             logo: 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
             leaderPhoto: '/partijleiders/frans.jpg'
         },
         'NSC': {
             name: 'Nieuw Sociaal Contract',
             leader: 'Nicolien van Vroonhoven',
             logo: 'https://i.ibb.co/YT2fJZb4/nsc.png',
             leaderPhoto: 'https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg'
         },
         'BBB': {
             name: 'BoerBurgerBeweging',
             leader: 'Caroline van der Plas',
             logo: 'https://i.ibb.co/qMjw7jDV/bbb.png',
             leaderPhoto: '/partijleiders/plas.jpg'
         },
         'D66': {
             name: 'Democraten 66',
             leader: 'Rob Jetten',
             logo: 'https://logo.clearbit.com/d66.nl',
             leaderPhoto: '/partijleiders/rob.jpg'
         },
         'SP': {
             name: 'Socialistische Partij',
             leader: 'Jimmy Dijk',
             logo: 'https://logo.clearbit.com/sp.nl',
             leaderPhoto: '/partijleiders/jimmy.jpg'
         },
         'PvdD': {
             name: 'Partij voor de Dieren',
             leader: 'Esther Ouwehand',
             logo: 'https://logo.clearbit.com/partijvoordedieren.nl',
             leaderPhoto: '/partijleiders/esther.jpg'
         },
         'CDA': {
             name: 'Christen-Democratisch Appèl',
             leader: 'Henri Bontenbal',
             logo: 'https://logo.clearbit.com/cda.nl',
             leaderPhoto: '/partijleiders/Henri.jpg'
         },
         'JA21': {
             name: 'Juiste Antwoord 2021',
             leader: 'Joost Eerdmans',
             logo: 'https://logo.clearbit.com/ja21.nl',
             leaderPhoto: '/partijleiders/joost.jpg'
         },
         'SGP': {
             name: 'Staatkundig Gereformeerde Partij',
             leader: 'Chris Stoffer',
             logo: 'https://logo.clearbit.com/sgp.nl',
             leaderPhoto: '/partijleiders/Chris.jpg'
         },
         'FvD': {
             name: 'Forum voor Democratie',
             leader: 'Thierry Baudet',
             logo: 'https://logo.clearbit.com/fvd.nl',
             leaderPhoto: '/partijleiders/thierry.jpg'
         },
         'DENK': {
             name: 'DENK',
             leader: 'Stephan van Baarle',
             logo: 'https://logo.clearbit.com/bewegingdenk.nl',
             leaderPhoto: '/partijleiders/baarle.jpg'
         },
         'Volt': {
             name: 'Volt Nederland',
             leader: 'Laurens Dassen',
             logo: 'https://logo.clearbit.com/voltnederland.org',
             leaderPhoto: '/partijleiders/dassen.jpg'
         },
         'CU': {
             name: 'ChristenUnie',
             leader: 'Mirjam Bikker',
             logo: 'https://logo.clearbit.com/christenunie.nl',
             leaderPhoto: 'https://i.ibb.co/wh3wwQ66/Bikker.jpg'
         }
     };
     
     // Open party modal
     partyPerspectiveButton?.addEventListener('click', function() {
         showPartyModal();
     });
     
     // Close party modal
     closePartyModal?.addEventListener('click', hidePartyModal);
     closePartyModalFooter?.addEventListener('click', hidePartyModal);
     
     // Background click to close
     partyModal?.addEventListener('click', function(e) {
         if (e.target === this) {
             hidePartyModal();
         }
     });
     
     // Mode toggle removed - always leader mode
     
     // Back button
     backToPartySelection?.addEventListener('click', function() {
         showPartySelection();
     });
     
     // Party selection
     document.querySelectorAll('.party-select-btn').forEach(btn => {
         btn.addEventListener('click', function() {
             const party = this.getAttribute('data-party');
             if (party) {
                 performPartyAnalysis(party, currentMode);
             }
         });
     });
     
     function updateModeText() {
         const leaderNames = document.querySelectorAll('.leader-name');
         const leaderPhotos = document.querySelectorAll('.leader-photo');
         
         // Always show leader mode
         leaderNames.forEach(el => el.style.display = 'block');
         leaderPhotos.forEach(el => el.classList.remove('hidden'));
     }
     
     function showPartyModal() {
         const modal = document.getElementById('partyModal');
         if (modal) {
             modal.classList.remove('hidden');
             document.body.style.overflow = 'hidden';
             showPartySelection();
             // Ensure leader mode is shown
             updateModeText();
         }
     }
     
     function hidePartyModal() {
         const modal = document.getElementById('partyModal');
         if (modal) {
             modal.classList.add('hidden');
             document.body.style.overflow = '';
         }
     }
     
     function showPartySelection() {
         document.getElementById('partySelectionGrid')?.classList.remove('hidden');
         document.getElementById('partyLoading')?.classList.add('hidden');
         document.getElementById('partyError')?.classList.add('hidden');
         document.getElementById('partyResults')?.classList.add('hidden');
         document.getElementById('backToPartySelection')?.classList.add('hidden');
     }
     
     async function performPartyAnalysis(party, type) {
         try {
             // Hide selection, show loading
             document.getElementById('partySelectionGrid')?.classList.add('hidden');
             document.getElementById('partyLoading')?.classList.remove('hidden');
             document.getElementById('partyError')?.classList.add('hidden');
             document.getElementById('partyResults')?.classList.add('hidden');
             
             const slug = partyPerspectiveButton?.getAttribute('data-slug');
             if (!slug) return;
             
             const formData = new FormData();
             formData.append('slug', slug);
             formData.append('party', party);
             formData.append('type', type);
             
             const response = await fetch('<?php echo URLROOT; ?>/controllers/blogs/party-perspective.php', {
                 method: 'POST',
                 headers: {
                     'X-Requested-With': 'XMLHttpRequest'
                 },
                 body: formData
             });
             
             const data = await response.json();
             
             // Hide loading
             document.getElementById('partyLoading')?.classList.add('hidden');
             
             if (data.success) {
                 showPartyResults(data, party);
             } else {
                 showPartyError(data.error || 'Onbekende fout bij het genereren');
             }
             
         } catch (error) {
             console.error('Party analysis error:', error);
             document.getElementById('partyLoading')?.classList.add('hidden');
             showPartyError('Netwerk fout: Kon geen verbinding maken met de server');
         }
     }
     
     function showPartyResults(data, partyKey) {
         const party = partyData[partyKey];
         if (!party) return;
         
         // Show results container
         document.getElementById('partyResults')?.classList.remove('hidden');
         document.getElementById('backToPartySelection')?.classList.remove('hidden');
         
         // Set party info - show leader photo for leader mode, party logo for party mode
         const logo = document.getElementById('partyResultLogo');
         if (logo) {
             if (data.type === 'leader' && party.leaderPhoto) {
                 logo.src = party.leaderPhoto;
                 logo.alt = party.leader;
                 logo.className = 'w-20 h-20 object-cover rounded-full border-2 border-gray-300';
             } else {
                 logo.src = party.logo;
                 logo.alt = party.name;
                 logo.className = 'w-20 h-20 object-contain';
             }
         }
         
         const name = document.getElementById('partyResultName');
         if (name) {
             name.textContent = data.type === 'party' ? party.name : party.leader;
         }
         
         const leader = document.getElementById('partyResultLeader');
         if (leader) {
             leader.textContent = data.type === 'party' ? 'Partijstandpunt' : `Partijleider ${party.name}`;
         }
         
         // Set content
         const content = document.getElementById('partyResultContent');
         if (content) {
             content.innerHTML = `<div class="text-gray-700 leading-relaxed whitespace-pre-wrap">${data.content}</div>`;
         }
     }
     
     function showPartyError(errorMessage) {
         document.getElementById('partyError')?.classList.remove('hidden');
         document.getElementById('backToPartySelection')?.classList.remove('hidden');
         
         const errorMessageElement = document.getElementById('partyErrorMessage');
         if (errorMessageElement) {
             errorMessageElement.textContent = errorMessage;
         }
     }
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

// Bias Analysis Functions
function showBiasModal() {
    const modal = document.getElementById('biasModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset modal state
        document.getElementById('biasLoading')?.classList.remove('hidden');
        document.getElementById('biasError')?.classList.add('hidden');
        document.getElementById('biasResults')?.classList.add('hidden');
        document.getElementById('retryBiasAnalysis')?.classList.add('hidden');
    }
}

function hideBiasModal() {
    const modal = document.getElementById('biasModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

async function performBiasAnalysis(slug) {
    try {
        // Show loading state
        document.getElementById('biasLoading')?.classList.remove('hidden');
        document.getElementById('biasError')?.classList.add('hidden');
        document.getElementById('biasResults')?.classList.add('hidden');
        document.getElementById('retryBiasAnalysis')?.classList.add('hidden');
        
        const formData = new FormData();
        formData.append('slug', slug);
        
        const response = await fetch('<?php echo URLROOT; ?>/controllers/blogs/analyze-bias.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        // Hide loading
        document.getElementById('biasLoading')?.classList.add('hidden');
        
        if (data.success && data.analysis) {
            showBiasResults(data.analysis);
        } else {
            showBiasError(data.error || 'Onbekende fout bij de analyse');
        }
        
    } catch (error) {
        console.error('Bias analysis error:', error);
        document.getElementById('biasLoading')?.classList.add('hidden');
        showBiasError('Netwerk fout: Kon geen verbinding maken met de server');
    }
}

function showBiasResults(analysis) {
    // Show results container
    document.getElementById('biasResults')?.classList.remove('hidden');
    
    // Set orientation badge
    const orientationBadge = document.getElementById('orientationBadge');
    if (orientationBadge && analysis.orientation) {
        const orientation = analysis.orientation.toLowerCase();
        orientationBadge.textContent = getOrientationLabel(orientation);
        orientationBadge.className = `px-4 py-2 rounded-full font-bold text-lg ${getOrientationColors(orientation)}`;
    }
    
    // Set confidence
    const confidenceBar = document.getElementById('confidenceBar');
    const confidenceText = document.getElementById('confidenceText');
    if (confidenceBar && confidenceText && analysis.confidence) {
        confidenceBar.style.width = analysis.confidence + '%';
        confidenceText.textContent = analysis.confidence;
    }
    
    // Set reasoning
    const reasoningText = document.getElementById('reasoningText');
    if (reasoningText && analysis.reasoning) {
        reasoningText.textContent = analysis.reasoning;
    }
    
    // Set indicators
    if (analysis.indicators) {
        const economicIndicator = document.getElementById('economicIndicator');
        if (economicIndicator && analysis.indicators.economic) {
            const economic = analysis.indicators.economic.toLowerCase();
            economicIndicator.textContent = getOrientationLabel(economic);
            economicIndicator.className = `px-3 py-1 rounded-full text-sm font-medium ${getOrientationColors(economic)}`;
        }
        
        const socialIndicator = document.getElementById('socialIndicator');
        if (socialIndicator && analysis.indicators.social) {
            const social = analysis.indicators.social.toLowerCase();
            socialIndicator.textContent = getOrientationLabel(social);
            socialIndicator.className = `px-3 py-1 rounded-full text-sm font-medium ${getOrientationColors(social)}`;
        }
        
        const immigrationIndicator = document.getElementById('immigrationIndicator');
        if (immigrationIndicator && analysis.indicators.immigration) {
            const immigration = analysis.indicators.immigration.toLowerCase();
            immigrationIndicator.textContent = getOrientationLabel(immigration);
            immigrationIndicator.className = `px-3 py-1 rounded-full text-sm font-medium ${getOrientationColors(immigration)}`;
        }
    }
    
    // Set summary
    const summaryText = document.getElementById('summaryText');
    if (summaryText && analysis.summary) {
        summaryText.textContent = analysis.summary;
    }
}

function showBiasError(errorMessage) {
    document.getElementById('biasError')?.classList.remove('hidden');
    document.getElementById('retryBiasAnalysis')?.classList.remove('hidden');
    
    const errorMessageElement = document.getElementById('biasErrorMessage');
    if (errorMessageElement) {
        errorMessageElement.textContent = errorMessage;
    }
}

function getOrientationLabel(orientation) {
    const labels = {
        'links': 'Links',
        'rechts': 'Rechts', 
        'centrum': 'Centrum',
        'neutraal': 'Neutraal'
    };
    return labels[orientation] || orientation;
}

function getOrientationColors(orientation) {
    const colors = {
        'links': 'bg-red-100 text-red-800 border border-red-200',
        'rechts': 'bg-blue-100 text-blue-800 border border-blue-200',
        'centrum': 'bg-purple-100 text-purple-800 border border-purple-200',
        'neutraal': 'bg-gray-100 text-gray-800 border border-gray-200'
    };
    return colors[orientation] || 'bg-gray-100 text-gray-800 border border-gray-200';
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

// Audio Player Functionality
<?php if (!empty($blog->audio_path) || !empty($blog->audio_url)): ?>
let currentSpeed = 1;
const speeds = [0.5, 0.75, 1, 1.25, 1.5, 2];

// Google Drive Audio Loading Functionaliteit
function loadGoogleDriveAudio(fileId) {
    const audioElement = document.getElementById('googleDriveAudio');
    const previewElement = document.getElementById('googleDrivePreview');
    
    if (!audioElement || !previewElement) return;
    
    // Toon loading state
    previewElement.innerHTML = `
        <div class="flex items-center justify-center p-6 bg-gray-50 rounded-lg">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-3"></div>
                <p class="text-gray-600">Audio wordt geladen...</p>
            </div>
        </div>
    `;
    
    // Probeer verschillende Google Drive URL formaten
    const urls = [
        `https://docs.google.com/uc?export=download&id=${fileId}`,
        `https://drive.google.com/uc?export=download&id=${fileId}`,
        `https://www.googleapis.com/drive/v3/files/${fileId}?alt=media&key=YOUR_API_KEY`
    ];
    
    let urlIndex = 0;
    
    function tryNextUrl() {
        if (urlIndex >= urls.length) {
            // Als alle URLs falen, toon alternatieve opties
            showGoogleDriveAlternatives(fileId);
            return;
        }
        
        const currentUrl = urls[urlIndex];
        audioElement.src = currentUrl;
        
        // Event listeners voor deze poging
        const onCanPlay = () => {
            // Audio succesvol geladen
            audioElement.style.display = 'block';
            previewElement.style.display = 'none';
            cleanup();
            showNotification('Google Drive audio succesvol geladen!', 'success');
        };
        
        const onError = () => {
            urlIndex++;
            cleanup();
            tryNextUrl();
        };
        
        const cleanup = () => {
            audioElement.removeEventListener('canplay', onCanPlay);
            audioElement.removeEventListener('error', onError);
        };
        
        audioElement.addEventListener('canplay', onCanPlay);
        audioElement.addEventListener('error', onError);
        
        // Timeout na 5 seconden
        setTimeout(() => {
            if (audioElement.style.display === 'none') {
                urlIndex++;
                cleanup();
                tryNextUrl();
            }
        }, 5000);
    }
    
    tryNextUrl();
}

function showGoogleDriveAlternatives(fileId) {
    const previewElement = document.getElementById('googleDrivePreview');
    if (!previewElement) return;
    
    previewElement.innerHTML = `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-yellow-800 font-medium mb-2">Audio kan niet direct worden afgespeeld</h3>
                    <p class="text-yellow-700 text-sm mb-4">
                        Google Drive heeft beperkingen voor directe audio streaming. Gebruik een van de onderstaande opties:
                    </p>
                    <div class="space-y-3">
                        <a href="https://drive.google.com/file/d/${fileId}/view" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Luister in Google Drive
                        </a>
                        <button onclick="downloadGoogleDriveAudio('${fileId}')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors ml-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Audio
                        </button>
                    </div>
                    <div class="mt-4 p-3 bg-white border border-yellow-200 rounded">
                        <p class="text-xs text-gray-600">
                            <strong>Tip voor de auteur:</strong> Voor betere compatibiliteit, upload audio bestanden liever direct naar de website in plaats van Google Drive links te gebruiken.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function downloadGoogleDriveAudio(fileId) {
    const downloadUrl = `https://drive.google.com/uc?export=download&id=${fileId}`;
    window.open(downloadUrl, '_blank');
    showNotification('Audio download gestart via Google Drive', 'success');
}

function changePlaybackSpeed() {
    const audioPlayer = document.querySelector('audio');
    const speedButton = document.getElementById('speedButton');
    const speedText = document.getElementById('speedText');
    
    if (!audioPlayer) return;
    
    // Find current speed index and move to next
    const currentIndex = speeds.indexOf(currentSpeed);
    const nextIndex = (currentIndex + 1) % speeds.length;
    currentSpeed = speeds[nextIndex];
    
    // Update audio playback rate
    audioPlayer.playbackRate = currentSpeed;
    
    // Update button text
    speedText.textContent = currentSpeed + 'x';
    
    // Visual feedback
    speedButton.classList.add('bg-primary', 'text-white');
    setTimeout(() => {
        speedButton.classList.remove('bg-primary', 'text-white');
    }, 200);
    
    showNotification(`Afspeelsnelheid aangepast naar ${currentSpeed}x`, 'info');
}

function downloadAudio() {
    <?php if (!empty($blog->soundcloud_url)): ?>
        // Voor SoundCloud audio - open in nieuwe tab
        window.open('<?php echo htmlspecialchars($blog->soundcloud_url); ?>', '_blank');
        showNotification('SoundCloud audio geopend in nieuwe tab', 'success');
    <?php elseif (!empty($blog->audio_url)): ?>
        // Voor Google Drive audio
        const audioUrl = '<?php echo htmlspecialchars($audioSrc ?? $blog->audio_url); ?>';
        const filename = 'audio-<?php echo $blog->slug; ?>.mp3';
        
        // Open Google Drive download in nieuwe tab
        window.open(audioUrl, '_blank');
        showNotification('Audio download gestart via Google Drive', 'success');
    <?php elseif (!empty($blog->audio_path)): ?>
        // Voor lokaal geüploade audio
        const audioPath = '<?php echo URLROOT . "/" . $blog->audio_path; ?>';
        const filename = 'audio-<?php echo $blog->slug; ?>.mp3';
        
        // Create download link
        const link = document.createElement('a');
        link.href = audioPath;
        link.download = filename;
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('Audio download gestart', 'success');
    <?php else: ?>
        showNotification('Geen audio beschikbaar voor download', 'error');
    <?php endif; ?>
}

// Initialize audio player when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const audioPlayer = document.querySelector('audio');
    
    if (audioPlayer) {
        // Add loading indicator
        audioPlayer.addEventListener('loadstart', function() {
            showNotification('Audio wordt geladen...', 'info');
        });
        
        // Audio loaded successfully
        audioPlayer.addEventListener('canplaythrough', function() {
            console.log('Audio gereed voor afspelen');
        });
        
        // Error handling
        audioPlayer.addEventListener('error', function(e) {
            console.error('Audio load error:', e);
            showNotification('Er is een probleem met het laden van de audio', 'error');
        });
        
        // Time update for progress tracking
        audioPlayer.addEventListener('timeupdate', function() {
            // Can be used for additional progress tracking if needed
        });
        
        // Audio ended
        audioPlayer.addEventListener('ended', function() {
            showNotification('Audio afspelen voltooid', 'success');
        });
    }
});
<?php endif; ?>
</script>