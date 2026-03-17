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
        
        <div class="relative z-10 container mx-auto px-4 sm:px-6">
            <div class="max-w-4xl mx-auto flex flex-col items-center justify-end min-h-[60vh] text-center pb-8 sm:pb-12 sm:justify-center sm:min-h-[50vh] sm:py-16">
                <!-- Responsive Badges -->
                <div class="flex items-center justify-center sm:justify-start gap-2 sm:gap-3 mb-6 sm:mb-8 flex-wrap">
                    <?php if (isset($blog->category_name) && $blog->category_name): ?>
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-white font-semibold text-xs sm:text-sm backdrop-blur-sm border border-white/30 shadow-lg transform transition-all duration-300 hover:scale-105"
                         style="background: linear-gradient(135deg, <?php echo htmlspecialchars($blog->category_color ?? '#1a56db'); ?>, <?php echo htmlspecialchars(adjust_brightness($blog->category_color ?? '#1a56db', -20)); ?>);">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <?php echo htmlspecialchars($blog->category_name); ?>
                    </span>
                    <?php endif; ?>
                    
                    <div class="inline-flex items-center px-3 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold text-xs sm:text-sm shadow-lg transform transition-all duration-300 hover:scale-105">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="reading-minutes">5</span> min lezen
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-4 sm:mb-8 [text-shadow:_0_2px_4px_rgb(0_0_0_/_50%)]">
                    <?php echo htmlspecialchars($blog->title); ?>
                </h1>

                <!-- Author Info & Actions -->
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:gap-6">
                    <div class="flex flex-col items-center text-center sm:flex-row sm:space-x-4">
                        <?php
                        $profilePhotoData = getProfilePhotoUrl($blog->author_photo ?? null, $blog->author_name);
                        if ($profilePhotoData['type'] === 'img'): ?>
                            <img src="<?php echo htmlspecialchars($profilePhotoData['value']); ?>" 
                                 alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                 class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-white/30 shadow-lg sm:shadow-xl object-cover mb-2 sm:mb-0">
                        <?php else: ?>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-white/30 shadow-lg sm:shadow-xl flex items-center justify-center bg-gradient-to-br from-primary to-secondary text-white font-bold text-lg mb-2 sm:mb-0">
                                <?php echo htmlspecialchars($profilePhotoData['value']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="sm:text-left">
                            <h3 class="text-white font-semibold text-base sm:text-lg"><?php echo htmlspecialchars($blog->author_name); ?></h3>
                            <p class="text-gray-300 text-xs sm:text-sm"><?php 
                                $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                                echo $formatter->format(strtotime($blog->published_at)); 
                            ?></p>
                        </div>
                    </div>
                    
                    <button id="heroLikeButton" 
                            class="hero-like-btn group flex items-center space-x-2 bg-black/20 hover:bg-black/40 sm:bg-white/5 sm:hover:bg-white/10 px-5 py-2 sm:px-4 rounded-full transition-all duration-300 hover:scale-105 border border-white/20"
                            data-slug="<?php echo $blog->slug; ?>"
                            aria-label="Like deze blog">
                        <svg class="w-5 h-5 text-red-300 sm:text-white transition-all duration-300 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="hero-like-count" class="text-red-300 sm:text-white group-hover:text-red-400 transition-colors text-sm font-semibold"><?php echo $blog->likes; ?> likes</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <article class="relative">
        <div class="container mx-auto px-3 sm:px-4 py-8 sm:py-16">
            <div class="max-w-4xl mx-auto">
                <!-- Enhanced Article Card -->
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden backdrop-blur-sm">
                    
                    <!-- Video sectie (indien aanwezig) -->
                    <?php if ($blog->video_path || $blog->video_url): ?>
                    <div class="relative group">
                        <?php if ($blog->video_path): ?>
                            <!-- Lokaal geüploade video -->
                            <div class="relative aspect-video bg-gradient-to-br from-gray-900 to-gray-800 rounded-t-3xl overflow-hidden">
                                <video controls class="w-full h-full object-cover" poster="<?php echo $blog->image_path ? getBlogImageUrl($blog->image_path) : ''; ?>">
                                    <source src="<?php echo getBlogVideoUrl($blog->video_path); ?>" type="video/mp4">
                                    Je browser ondersteunt geen video weergave.
                                </video>
                                <!-- Video overlay controls -->
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full p-4 transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($blog->video_url): ?>
                            <!-- Embedded video (YouTube/Vimeo) -->
                            <div class="relative aspect-video bg-gradient-to-br from-gray-900 to-gray-800 rounded-t-3xl overflow-hidden">
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

                    <!-- Enhanced Podcast sectie (indien aanwezig) -->
                    <?php if (!empty($blog->soundcloud_url)): ?>
                    <div class="relative overflow-hidden">
                        <!-- Audio Header -->
                        <div class="bg-gradient-to-r from-primary-dark via-primary to-secondary p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white mb-1">🎙️ AI Podcast Editie</h3>
                                        <p class="text-white/90 text-sm">Luister naar de AI-gegenereerde podcast versie</p>
                                    </div>
                                </div>
                                <div class="hidden sm:flex items-center gap-2">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 text-white text-sm font-medium backdrop-blur-sm border border-white/30">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                        AI Podcast
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced SoundCloud Player -->
                        <div class="bg-gradient-to-b from-gray-50 to-white p-6">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                                <?php
                                // Enhanced SoundCloud embed URL
                                $soundcloudEnhancedUrl = 'https://w.soundcloud.com/player/?url=' . urlencode($blog->soundcloud_url) . '&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true';
                                ?>
                                
                                <iframe class="w-full border-0 soundcloud-iframe" 
                                        height="166" 
                                        scrolling="no" 
                                        frameborder="no" 
                                        allow="autoplay" 
                                        src="<?php echo htmlspecialchars($soundcloudEnhancedUrl); ?>">
                                </iframe>
                            </div>
                        </div>
                    </div>
                    
                    <?php elseif (!empty($blog->audio_path) || !empty($blog->audio_url)): ?>
                    <!-- Podcast Audio Section -->
                    <div class="relative">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-primary-light opacity-[0.03] pointer-events-none"></div>
                        
                        <!-- Audio Player Container -->
                        <div class="relative p-4 sm:p-6 lg:p-8">
                            <div class="max-w-2xl mx-auto">
                                <div class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-light rounded-2xl sm:rounded-3xl shadow-xl">
                                    <!-- Decorative Elements Wrapper with overflow-hidden -->
                                    <div class="absolute inset-0 overflow-hidden rounded-2xl sm:rounded-3xl pointer-events-none">
                                        <div class="absolute top-0 right-0 w-32 sm:w-48 h-32 sm:h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                        <div class="absolute bottom-0 left-0 w-24 sm:w-32 h-24 sm:h-32 bg-secondary/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="relative z-10 p-5 sm:p-6 lg:p-8" style="overflow: visible;">
                                        <!-- Header Row -->
                                        <div class="flex items-start sm:items-center justify-between gap-3 mb-5 sm:mb-6">
                                            <div class="flex items-center gap-3 sm:gap-4">
                                                <!-- Icon with pulse animation -->
                                                <div class="relative flex-shrink-0">
                                                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-xl sm:rounded-2xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                                                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="absolute -top-0.5 -right-0.5 w-3 h-3 sm:w-3.5 sm:h-3.5 bg-secondary rounded-full border-2 border-white animate-pulse"></div>
                                                </div>
                                                
                                                <div>
                                                    <h3 class="text-base sm:text-lg font-bold text-white leading-tight">Podcast versie</h3>
                                                    <p class="text-white/70 text-xs sm:text-sm mt-0.5">Luister naar dit artikel</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Info tooltip -->
                                            <div class="relative group flex-shrink-0">
                                                <button type="button" class="w-7 h-7 sm:w-8 sm:h-8 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110" aria-label="Meer informatie">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                                <div class="absolute right-0 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 top-full mt-2 w-64 sm:w-80 max-w-[calc(100vw-2rem)] p-3 sm:p-4 bg-gray-900/95 backdrop-blur-sm text-white text-xs sm:text-sm rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 whitespace-normal break-words">
                                                    <p class="leading-relaxed">Deze podcast is automatisch gegenereerd op basis van de blogtekst door PolitiekPraat.</p>
                                                    <div class="absolute -top-1.5 right-3 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 w-3 h-3 bg-gray-900/95 transform rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Sound Wave Visualization -->
                                        <div class="flex items-center justify-center gap-0.5 sm:gap-1 h-8 sm:h-10 mb-5 sm:mb-6" aria-hidden="true">
                                            <?php for($i = 0; $i < 20; $i++): ?>
                                            <div class="w-1 sm:w-1.5 bg-white/30 rounded-full transition-all duration-300" style="height: <?php echo rand(20, 100); ?>%; animation: soundwave <?php echo 0.5 + ($i * 0.05); ?>s ease-in-out infinite alternate;"></div>
                                            <?php endfor; ?>
                                        </div>
                                        
                                        <!-- Audio Player -->
                                        <div class="space-y-4">
                                            <?php if (!empty($blog->audio_url)): ?>
                                                <?php
                                                $fileId = '';
                                                if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $blog->audio_url, $matches)) {
                                                    $fileId = $matches[1];
                                                } elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $blog->audio_url, $matches)) {
                                                    $fileId = $matches[1];
                                                }
                                                ?>
                                                
                                                <?php if ($fileId): ?>
                                                    <div id="googleDrivePodcastLoader" class="text-center">
                                                        <button onclick="loadGoogleDrivePodcast('<?php echo $fileId; ?>')" class="group inline-flex items-center gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white text-primary font-semibold rounded-xl sm:rounded-2xl hover:bg-white/90 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-[1.02]">
                                                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M8 5v14l11-7z"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm sm:text-base">Podcast afspelen</span>
                                                        </button>
                                                    </div>
                                                    
                                                    <audio id="googleDrivePodcast" controls class="w-full rounded-xl" preload="none" style="display: none;">
                                                        <source src="https://docs.google.com/uc?export=download&id=<?php echo $fileId; ?>" type="audio/mpeg">
                                                    </audio>
                                                <?php endif; ?>
                                                
                                            <?php elseif (!empty($blog->audio_path)): ?>
                                                <div class="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-3 sm:p-4">
                                                    <audio controls class="w-full rounded-lg" preload="metadata" style="filter: invert(1) hue-rotate(180deg);">
                                                        <source src="<?php echo URLROOT . '/' . $blog->audio_path; ?>" type="audio/mpeg">
                                                        Je browser ondersteunt geen audio weergave.
                                                    </audio>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Duration indicator -->
                                        <div class="flex items-center justify-center gap-2 mt-4 sm:mt-5 text-white/60 text-xs sm:text-sm">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Gegenereerde audio versie van dit artikel</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        @keyframes soundwave {
                            0% { height: 20%; }
                            100% { height: 80%; }
                        }
                    </style>
                    <?php endif; ?>

                    <!-- Enhanced Content -->
                    <div class="relative">
                        <!-- Content Header -->
                        <div class="px-6 sm:px-8 lg:px-12 pt-8 pb-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900">Artikel</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span><span id="reading-minutes-content">5</span> min lezen</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Main Content -->
                        <div class="px-6 sm:px-8 lg:px-12 xl:px-16 py-8">
                            <div id="blog-content" class="prose prose-lg max-w-none">
                                <?php echo $blog->content; ?>
                            </div>

                                        <!-- Poll sectie (indien aanwezig) -->
            <?php if ($blog->poll): ?>
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="max-w-2xl mx-auto">
                    <!-- Poll Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Peiling
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                            <?php echo htmlspecialchars($blog->poll->question); ?>
                        </h3>
                        
                        <?php if ($blog->poll->total_votes > 0): ?>
                            <p class="text-gray-600">
                                <span class="font-semibold text-primary"><?php echo $blog->poll->total_votes; ?></span>
                                <?php echo $blog->poll->total_votes === 1 ? 'persoon heeft gestemd' : 'mensen hebben gestemd'; ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Poll Container -->
                    <div id="pollContainer" data-poll-id="<?php echo $blog->poll->id; ?>">
                        <?php if ($blog->poll->user_has_voted): ?>
                            <!-- Resultaten -->
                            <div class="space-y-4 mb-6">
                                <!-- Optie A -->
                                <div class="relative group">
                                    <div class="bg-white border-2 <?php echo $blog->poll->user_choice === 'A' ? 'border-blue-500' : 'border-gray-200'; ?> rounded-xl overflow-hidden">
                                        <!-- Progress bar -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-blue-100 opacity-60 transition-all duration-1000 ease-out" 
                                             style="width: <?php echo $blog->poll->option_a_percentage; ?>%"></div>
                                        
                                        <div class="relative p-5">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                                    <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                                        A
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-gray-900 text-base break-words">
                                                            <?php echo htmlspecialchars($blog->poll->option_a); ?>
                                                        </p>
                                                        <?php if ($blog->poll->user_choice === 'A'): ?>
                                                            <p class="text-sm text-blue-600 font-medium mt-1">
                                                                ✓ Jouw keuze
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-4">
                                                    <div class="text-2xl font-bold text-blue-600">
                                                        <?php echo $blog->poll->option_a_percentage; ?>%
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $blog->poll->option_a_votes; ?> <?php echo $blog->poll->option_a_votes === 1 ? 'stem' : 'stemmen'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Optie B -->
                                <div class="relative group">
                                    <div class="bg-white border-2 <?php echo $blog->poll->user_choice === 'B' ? 'border-red-500' : 'border-gray-200'; ?> rounded-xl overflow-hidden">
                                        <!-- Progress bar -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-red-100 opacity-60 transition-all duration-1000 ease-out" 
                                             style="width: <?php echo $blog->poll->option_b_percentage; ?>%"></div>
                                        
                                        <div class="relative p-5">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                                    <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                                        B
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-gray-900 text-base break-words">
                                                            <?php echo htmlspecialchars($blog->poll->option_b); ?>
                                                        </p>
                                                        <?php if ($blog->poll->user_choice === 'B'): ?>
                                                            <p class="text-sm text-red-600 font-medium mt-1">
                                                                ✓ Jouw keuze
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-4">
                                                    <div class="text-2xl font-bold text-red-600">
                                                        <?php echo $blog->poll->option_b_percentage; ?>%
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $blog->poll->option_b_votes; ?> <?php echo $blog->poll->option_b_votes === 1 ? 'stem' : 'stemmen'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bedank bericht -->
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center gap-3 text-green-800">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="font-medium">Bedankt voor je stem! Je hebt bijgedragen aan het politieke debat.</p>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- Stem opties -->
                            <div class="space-y-3 mb-6">
                                <!-- Optie A -->
                                <button onclick="votePoll('A')" 
                                        class="poll-option w-full group bg-white hover:bg-blue-50 border-2 border-gray-200 hover:border-blue-500 rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-500 group-hover:bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg transition-colors flex-shrink-0">
                                            A
                                        </div>
                                        <div class="flex-1 text-left min-w-0">
                                            <p class="font-medium text-gray-900 text-base break-words">
                                                <?php echo htmlspecialchars($blog->poll->option_a); ?>
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Optie B -->
                                <button onclick="votePoll('B')" 
                                        class="poll-option w-full group bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-500 rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-red-500 group-hover:bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg transition-colors flex-shrink-0">
                                            B
                                        </div>
                                        <div class="flex-1 text-left min-w-0">
                                            <p class="font-medium text-gray-900 text-base break-words">
                                                <?php echo htmlspecialchars($blog->poll->option_b); ?>
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Info bericht -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex gap-3 text-blue-800">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-medium mb-1">Jouw stem telt mee!</p>
                                        <p>Je kunt één keer stemmen. Na het stemmen zie je de resultaten.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

