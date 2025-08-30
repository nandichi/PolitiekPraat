<?php 
// Voeg dynamische meta tags toe voor deze specifieke blog
$pageTitle = htmlspecialchars($blog->title) . ' | PolitiekPraat';
$pageDescription = htmlspecialchars(stripMarkdownForSocialMedia($blog->summary, 160));

// Zorg ervoor dat de afbeelding URL altijd absoluut is voor social media sharing
if ($blog->image_path) {
    // getBlogImageUrl() retourneert al een absolute URL
    $pageImage = getBlogImageUrl($blog->image_path);
} else {
    // Fallback naar default metadata foto
    $pageImage = rtrim(URLROOT, '/') . '/metadata-foto.png';
}

// Voeg deze variabelen toe aan $data voor de header
$data = [
    'title' => $pageTitle,
    'description' => $pageDescription,
    'image' => $pageImage,
    // Voeg extra meta data toe voor betere social sharing
    'og_type' => 'article',
    'og_url' => rtrim(URLROOT, '/') . '/blogs/' . $blog->slug,
    'article_author' => $blog->author_name,
    'article_published_time' => date('c', strtotime($blog->published_at))
];

// Haal comments op (zowel van ingelogde als anonieme gebruikers) met like info
$db = new Database();
$db->query("SELECT comments.*, 
           COALESCE(users.username, comments.anonymous_name) as author_name,
           CASE 
               WHEN comments.user_id IS NOT NULL THEN 'registered'
               ELSE 'anonymous'
           END as author_type,
           comments.is_liked_by_author,
           comments.likes_count
           FROM comments 
           LEFT JOIN users ON comments.user_id = users.id 
           WHERE comments.blog_id = :blog_id 
           ORDER BY comments.created_at DESC");
$db->bind(':blog_id', $blog->id);
$comments = $db->resultSet();

// Comment toevoegen (zowel voor ingelogde als anonieme gebruikers)
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $anonymous_name = isset($_POST['anonymous_name']) ? filter_input(INPUT_POST, 'anonymous_name', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    
    if (empty($content)) {
        $comment_error = 'Vul een reactie in';
    } elseif (!isset($_SESSION['user_id']) && empty($anonymous_name)) {
        $comment_error = 'Vul je naam in';
    } elseif (!isset($_SESSION['user_id']) && strlen($anonymous_name) > 100) {
        $comment_error = 'Naam mag maximaal 100 karakters zijn';
    } elseif (strlen($content) < 10) {
        $comment_error = 'Reactie moet minimaal 10 karakters zijn';
    } elseif (strlen($content) > 1000) {
        $comment_error = 'Reactie mag maximaal 1000 karakters zijn';
    } else {
        if (isset($_SESSION['user_id'])) {
            // Ingelogde gebruiker
            $db->query("INSERT INTO comments (blog_id, user_id, content) VALUES (:blog_id, :user_id, :content)");
            $db->bind(':blog_id', $blog->id);
            $db->bind(':user_id', $_SESSION['user_id']);
            $db->bind(':content', $content);
        } else {
            // Anonieme gebruiker
            $db->query("INSERT INTO comments (blog_id, anonymous_name, content) VALUES (:blog_id, :anonymous_name, :content)");
            $db->bind(':blog_id', $blog->id);
            $db->bind(':anonymous_name', $anonymous_name);
            $db->bind(':content', $content);
        }
        
        if ($db->execute()) {
            header('Location: ' . URLROOT . '/blogs/' . $blog->slug . '#comments');
            exit;
        } else {
            $comment_error = 'Er is iets misgegaan bij het plaatsen van je reactie';
        }
    }
}

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
                    <!-- Enhanced Fallback Audio Section -->
                    <div class="relative overflow-hidden">
                        <!-- Audio Header -->
                        <div class="bg-gradient-to-r from-primary-dark via-primary to-secondary p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white mb-1">🎵 AI Audio Versie</h3>
                                        <p class="text-white/90 text-sm">Luister naar de AI-gegenereerde podcast versie</p>
                                    </div>
                                </div>
                                <div class="hidden sm:flex items-center gap-2">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 text-white text-sm font-medium backdrop-blur-sm border border-white/30">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                        AI AUDIO
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Audio Player -->
                        <div class="bg-gradient-to-b from-gray-50 to-white p-6">
                            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
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
                                        <div class="text-center space-y-4">
                                            <div class="flex items-center justify-center gap-3">
                                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h12a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900">AI Podcast Audio</h4>
                                                    <p class="text-sm text-gray-600">Klik om de AI-gegenereerde podcast af te spelen</p>
                                                </div>
                                            </div>
                                            
                                            <button onclick="loadGoogleDrivePodcast('<?php echo $fileId; ?>')" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h12a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                                </svg>
                                                AI Podcast Afspelen
                                            </button>
                                            
                                            <audio id="googleDrivePodcast" controls class="w-full mt-4 rounded-lg shadow-md" preload="none" style="display: none;">
                                                <source src="https://docs.google.com/uc?export=download&id=<?php echo $fileId; ?>" type="audio/mpeg">
                                            </audio>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php elseif (!empty($blog->audio_path)): ?>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">AI Audio Podcast</h4>
                                                <p class="text-sm text-gray-600">Luister naar de AI-gegenereerde podcast versie</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <audio controls class="w-full rounded-lg" preload="metadata">
                                                <source src="<?php echo getBlogAudioUrl($blog->audio_path); ?>" type="audio/mpeg">
                                                Je browser ondersteunt geen audio weergave.
                                            </audio>
                                        </div>
                                        
                                        <!-- Audio Controls -->
                                        <div class="flex items-center justify-center gap-3 pt-4">
                                            <button onclick="changePlaybackSpeed()" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span class="text-sm font-medium">Snelheid</span>
                                            </button>
                                            <button onclick="downloadAudio()" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-full hover:from-green-600 hover:to-teal-700 transition-all transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-sm font-medium">Download</span>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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

    <!-- Enhanced Interactive Actions Section -->
    <section class="relative py-12 sm:py-16 bg-gradient-to-br from-gray-50 via-white to-blue-50/30">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000000" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        </div>
        
        <div class="container mx-auto px-4 relative">
            <div class="max-w-6xl mx-auto">
                
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Interacteer met dit artikel
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Laat je stem horen en ontdek verschillende perspectieven op dit onderwerp
                    </p>
                </div>

                <!-- Enhanced Action Cards Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    
                    <!-- Enhanced Like Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-pink-500/5 to-red-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-bold text-gray-900" id="likeCountDisplay"><?php echo $blog->likes; ?></span>
                                    <span class="text-sm text-gray-500">likes</span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Vind je dit interessant?</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Laat anderen weten dat je dit artikel waardevol vindt door een like te geven
                            </p>
                            
                            <button id="likeButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Like deze blog">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Artikel leuk vinden</span>
                                <!-- Heart particles voor animatie -->
                                <div class="like-particles absolute inset-0 pointer-events-none">
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Bias Analysis Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-indigo-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-medium">
                                        AI-Powered
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Politieke Bias Analyse</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Laat AI de politieke oriëntatie en mogelijke bias in dit artikel analyseren
                            </p>
                            
                            <button id="biasButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Analyseer politieke bias">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Analyseer Bias</span>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Party Perspective Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden sm:col-span-2 lg:col-span-1">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 via-red-500/5 to-orange-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-medium">
                                        15 Partijen
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Partijleider Reacties</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Ontdek hoe verschillende partijleiders op dit onderwerp zouden reageren
                            </p>
                            
                            <button id="partyPerspectiveButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Bekijk partij perspectieven">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Bekijk Reacties</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Enhanced Share Section -->
                <div class="relative">
                    <!-- Decorative Divider -->
                    <div class="flex items-center justify-center mb-12">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        <div class="px-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    </div>

                    <!-- Share Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Deel dit artikel</h3>
                        <p class="text-gray-600">Verspreidt waardevolle politieke inzichten</p>
                    </div>

                    <!-- Enhanced Share Buttons -->
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        
                        <!-- Twitter/X Enhanced -->
                        <button onclick="window.open('https://twitter.com/intent/tweet?text=<?php echo urlencode($blog->title); ?>&url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')" 
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-sky-500 to-blue-500 text-white rounded-2xl hover:from-sky-600 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-sky-400 to-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Twitter</div>
                                    <div class="text-xs text-sky-100">Deel op X</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- LinkedIn Enhanced -->
                        <button onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">LinkedIn</div>
                                    <div class="text-xs text-blue-100">Professioneel</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Facebook Enhanced -->
                        <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-800 to-indigo-800 text-white rounded-2xl hover:from-blue-900 hover:to-indigo-900 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Facebook</div>
                                    <div class="text-xs text-blue-100">Sociale media</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- WhatsApp Enhanced -->
                        <button onclick="window.open('https://wa.me/?text=<?php echo urlencode($blog->title . ' - ' . URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">WhatsApp</div>
                                    <div class="text-xs text-green-100">Berichten</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Copy Link Enhanced -->
                        <button onclick="navigator.clipboard.writeText('<?php echo URLROOT . '/blogs/' . $blog->slug; ?>').then(() => showNotification('Link gekopieerd!', 'success'))"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-2xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-500 to-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Kopiëren</div>
                                    <div class="text-xs text-gray-100">Link delen</div>
                                </div>
                            </div>
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Comments Section -->
    <section class="py-16 sm:py-20 bg-white" style="display: none;">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h2 class="flex items-center justify-center text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        <span>Reacties</span>
                        <span class="inline-flex items-center bg-gradient-to-r from-primary to-secondary text-white text-lg px-3 py-1 rounded-full ml-3" id="commentCount">
                            <?php echo count($comments); ?>
                        </span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Deel je mening over dit artikel. Je kunt anoniem reageren zonder account aan te maken.
                    </p>
                </div>

                <!-- Comment Form -->
                <div class="bg-gradient-to-br from-gray-50 to-primary/5 rounded-2xl p-6 mb-12 border border-gray-200">
                    <form method="POST" action="<?php echo URLROOT; ?>/blogs/<?php echo $blog->slug; ?>#comments" class="space-y-6">
                        
                        <?php if ($comment_error): ?>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div class="text-red-800 text-sm">
                                        <?php echo htmlspecialchars($comment_error); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <!-- Anonymous Name Field -->
                            <div>
                                <label for="anonymous_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-5 h-5 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Jouw naam *
                                </label>
                                <input type="text" 
                                       name="anonymous_name" 
                                       id="anonymous_name" 
                                       placeholder="Bijv. Jan, Marie, Alex..."
                                       maxlength="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200"
                                       value="<?php echo isset($_POST['anonymous_name']) ? htmlspecialchars($_POST['anonymous_name']) : ''; ?>"
                                       required>
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Geen account nodig - reageer direct anoniem
                                </p>
                            </div>
                        <?php else: ?>
                            <!-- Logged in user info -->
                                                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold"><?php echo substr($_SESSION['username'], 0, 1); ?></span>
                                    </div>
                                    <div>
                                                                            <p class="text-sm font-medium text-primary">Reactie plaatsen als:</p>
                                    <p class="text-primary/70"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Comment Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Jouw reactie *
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="5"
                                      placeholder="Deel je mening over dit artikel. Houd het respectvol en constructief..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 resize-none"
                                      required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Minimaal 10 karakters</p>
                                <span class="text-sm text-gray-400" id="charCount">0/1000</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary-dark hover:to-secondary-dark transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Plaats Reactie
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div id="comments">
                    <?php if(empty($comments)): ?>
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nog geen reacties</h3>
                            <p class="text-gray-600 mb-6">Wees de eerste die reageert op dit artikel!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php 
                            $totalComments = count($comments);
                            $showLoadMore = $totalComments > 5;
                            
                            foreach($comments as $index => $comment): 
                                // Check if comment is liked by creator for special styling
                                $isLikedByCreator = $comment->is_liked_by_author;
                                
                                // Add class to hide comments after first 5
                                $hiddenClass = $index >= 5 ? 'hidden comment-hidden' : '';
                            ?>
                                <div class="<?php echo $isLikedByCreator ? 'bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border-2 border-red-300 shadow-xl ring-2 ring-red-200 ring-opacity-50' : 'bg-white border border-gray-200 shadow-sm'; ?> rounded-2xl overflow-hidden transition-all duration-500 <?php echo $isLikedByCreator ? 'transform hover:scale-[1.02] hover:shadow-2xl' : 'hover:shadow-md'; ?> <?php echo $hiddenClass; ?>">
                                    <!-- Comment Header -->
                                    <div class="px-6 py-4 <?php echo $isLikedByCreator ? 'bg-gradient-to-r from-red-100 via-pink-100 to-red-100 border-b border-red-200' : 'bg-gradient-to-r from-gray-50 to-primary/5 border-b border-gray-100'; ?>">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <!-- Avatar -->
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo $comment->author_type === 'anonymous' ? 'bg-gradient-to-r from-gray-400 to-gray-500' : 'bg-gradient-to-r from-primary to-secondary'; ?>">
                                                    <span class="text-white font-semibold text-sm">
                                                        <?php echo substr($comment->author_name, 0, 1); ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Author Info -->
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-900">
                                                            <?php echo htmlspecialchars($comment->author_name); ?>
                                                        </span>
                                                        <?php if($comment->author_type === 'anonymous'): ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                Gast
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Lid
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="text-sm text-gray-500">
                                                        <?php 
                                                        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
                                                        echo $formatter->format(strtotime($comment->created_at)); 
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Button -->
                                            <?php if(isset($_SESSION['user_id']) && $comment->author_type === 'registered' && 
                                                    ($_SESSION['user_id'] == $comment->user_id || $_SESSION['is_admin'])): ?>
                                                <form method="POST" 
                                                      action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                      class="inline">
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen?')"
                                                            title="Reactie verwijderen">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php elseif(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                                <form method="POST" 
                                                      action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                      class="inline">
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen? (Admin actie)')"
                                                            title="Reactie verwijderen (Admin)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Comment Content -->
                                    <div class="px-6 py-5">
                                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed mb-4">
                                            <?php echo nl2br(htmlspecialchars($comment->content)); ?>
                                        </div>
                                        
                                        <!-- Comment Actions -->
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <!-- Leuk Gevonden Door Auteur Badge -->
                                                <?php if ($comment->is_liked_by_author): ?>
                                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 border border-red-400 rounded-full shadow-lg">
                                                        <svg class="w-4 h-4 text-white animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                        </svg>
                                                        <span class="text-white text-sm font-bold">Leuk gevonden door auteur</span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Like Count (if > 0) -->
                                                <?php if ($comment->likes_count > 0): ?>
                                                    <span class="text-gray-500 text-sm">
                                                        <?php echo $comment->likes_count; ?> <?php echo $comment->likes_count == 1 ? 'like' : 'likes'; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Author Like Button -->
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $blog->author_id): ?>
                                                <button type="button" 
                                                        class="comment-like-btn inline-flex items-center gap-2 px-3 py-2 rounded-lg transition-all hover:bg-gray-50 <?php echo $comment->is_liked_by_author ? 'text-red-600' : 'text-gray-500 hover:text-red-600'; ?>"
                                                        data-comment-id="<?php echo $comment->id; ?>"
                                                        data-liked="<?php echo $comment->is_liked_by_author ? 'true' : 'false'; ?>"
                                                        title="<?php echo $comment->is_liked_by_author ? 'Reactie niet meer leuk vinden' : 'Reactie leuk vinden'; ?>">
                                                    <svg class="w-5 h-5 transition-all <?php echo $comment->is_liked_by_author ? 'scale-110' : ''; ?>" 
                                                         fill="<?php echo $comment->is_liked_by_author ? 'currentColor' : 'none'; ?>" 
                                                         stroke="currentColor" 
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium">
                                                        <?php echo $comment->is_liked_by_author ? 'Leuk gevonden' : 'Leuk vinden'; ?>
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if($showLoadMore): ?>
                                <!-- Load More Button -->
                                <div class="text-center pt-6" id="loadMoreContainer">
                                    <button 
                                        id="loadMoreComments" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary-dark hover:to-secondary-dark transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-primary"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        <span>Bekijk meer reacties</span>
                                        <span class="bg-white/20 px-2 py-1 rounded-full text-sm">
                                            <?php echo $totalComments - 5; ?>
                                        </span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Related Blogs Section -->
    <section class="relative py-20 sm:py-28 bg-gradient-to-br from-primary/5 via-white to-secondary/5 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl opacity-20 -translate-y-32 translate-x-32"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl opacity-20 translate-y-32 -translate-x-32"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 sm:mb-20">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-primary/10 via-primary/5 to-secondary/10 border border-primary/20 rounded-full text-sm sm:text-base font-bold text-primary mb-6 sm:mb-8 shadow-lg backdrop-blur-sm">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Meer Politiekpraat</span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-gray-900 mb-6 sm:mb-8 leading-tight">
                        <span class="bg-gradient-to-r from-primary via-primary-dark to-secondary bg-clip-text text-transparent">
                            Verdiep je kennis
                        </span>
                    </h2>

                    <!-- Subtitle -->
                    <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Ontdek meer waardevolle politieke inzichten en analyses die je helpen de complexe wereld van de politiek beter te begrijpen
                    </p>
                </div>

                <!-- Related Blogs Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                    <?php 
                    // Haal andere blogs op (maximaal 7 om er zeker van te zijn dat we 6 hebben na filtering)
                    $otherBlogs = (new BlogController())->getAll(7);
                    $count = 0;
                    foreach ($otherBlogs as $relatedBlog): 
                        if ($relatedBlog->slug !== $blog->slug && $count < 6): 
                            $count++;
                    ?>
                        <article class="group relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100/50 overflow-hidden hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-4 hover:scale-[1.02]">
                            <!-- Gradient Border Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 via-transparent to-secondary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"></div>
                            
                            <div class="relative z-10">
                                <!-- Image Container -->
                                <div class="relative overflow-hidden rounded-t-3xl">
                                    <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" class="block">
                                        <?php if ($relatedBlog->image_path): ?>
                                            <div class="relative h-48 sm:h-56 lg:h-64">
                                                <img src="<?php echo getBlogImageUrl($relatedBlog->image_path); ?>" 
                                                     alt="<?php echo htmlspecialchars($relatedBlog->title); ?>"
                                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                                <!-- Overlay Gradient -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <!-- Shine Effect -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 opacity-0 group-hover:opacity-100 transition-all duration-700 transform -translate-x-full group-hover:translate-x-full"></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="h-48 sm:h-56 lg:h-64 bg-gradient-to-br from-primary/15 via-primary/5 to-secondary/15 flex items-center justify-center relative overflow-hidden">
                                                <!-- Animated Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-0 left-0 w-32 h-32 bg-primary rounded-full blur-xl opacity-50 group-hover:scale-150 transition-transform duration-1000"></div>
                                                    <div class="absolute bottom-0 right-0 w-24 h-24 bg-secondary rounded-full blur-xl opacity-50 group-hover:scale-150 transition-transform duration-1000 delay-200"></div>
                                                </div>
                                                <svg class="w-16 sm:w-20 h-16 sm:h-20 text-primary/60 relative z-10 group-hover:text-primary transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div class="absolute top-4 sm:top-6 right-4 sm:right-6 z-20">
                                            <div class="relative">
                                                <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-xs sm:text-sm font-bold shadow-xl border border-white/20">
                                                    <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                                    NIEUW
                                                </span>
                                                <!-- Glow Effect -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary rounded-full blur opacity-75 animate-pulse"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Content -->
                                <div class="p-6 sm:p-8">
                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="relative">
                                                <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-full overflow-hidden border-2 border-primary/20 shadow-md">
                                                    <?php
                                                    $relatedProfilePhotoData = getProfilePhotoUrl($relatedBlog->author_photo ?? null, $relatedBlog->author_name);
                                                    if ($relatedProfilePhotoData['type'] === 'img'): ?>
                                                        <img src="<?php echo htmlspecialchars($relatedProfilePhotoData['value']); ?>" 
                                                             alt="<?php echo htmlspecialchars($relatedBlog->author_name); ?>"
                                                             class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary text-white font-bold text-sm">
                                                            <?php echo htmlspecialchars($relatedProfilePhotoData['value']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-full blur opacity-50"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm sm:text-base font-semibold text-gray-700 truncate max-w-24 sm:max-w-32">
                                                    <?php echo htmlspecialchars($relatedBlog->author_name); ?>
                                                </p>
                                                <p class="text-xs sm:text-sm text-gray-500">
                                                    <?php 
                                                        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
                                                        echo str_replace('.', '', $formatter->format(strtotime($relatedBlog->published_at))); 
                                                    ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Likes -->
                                        <div class="flex items-center space-x-1 px-3 py-1 bg-gray-50 rounded-full">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600">
                                                <?php echo (isset($relatedBlog->likes) && $relatedBlog->likes > 0) ? $relatedBlog->likes : '0'; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" class="block group/title">
                                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 line-clamp-2 group-hover:text-primary transition-colors duration-300 leading-tight">
                                            <?php echo htmlspecialchars($relatedBlog->title); ?>
                                        </h3>
                                    </a>
                                    
                                    <!-- Summary -->
                                    <p class="text-gray-600 text-sm sm:text-base line-clamp-3 mb-6 leading-relaxed">
                                        <?php echo htmlspecialchars($relatedBlog->summary); ?>
                                    </p>
                                    
                                    <!-- Read More Button -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" 
                                           class="group/btn inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary/10 to-secondary/10 hover:from-primary hover:to-secondary text-primary hover:text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                            <span class="text-sm sm:text-base">Lees artikel</span>
                                            <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>

                                        <!-- Reading Time -->
                                        <div class="flex items-center text-gray-400 text-xs sm:text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>3 min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-16 sm:mt-20">
                    <div class="inline-flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="group relative inline-flex items-center px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-primary via-primary-dark to-secondary text-white font-bold text-base sm:text-lg rounded-full shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:scale-105 overflow-hidden">
                            <!-- Animated Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary via-primary to-primary-dark opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 opacity-0 group-hover:opacity-100 transition-all duration-700 transform -translate-x-full group-hover:translate-x-full"></div>
                            
                            <span class="relative z-10 mr-3">Ontdek alle artikelen</span>
                            <svg class="relative z-10 w-5 sm:w-6 h-5 sm:h-6 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <div class="flex items-center text-gray-600 text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Wekelijks nieuwe inzichten</span>
                        </div>
                    </div>
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

/* Enhanced Newsletter Section Animations */
@keyframes float-slow {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

@keyframes float-medium {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-15px) rotate(120deg);
    }
}

@keyframes float-fast {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-10px) rotate(90deg);
    }
}

.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}

.animate-float-medium {
    animation: float-medium 4s ease-in-out infinite;
}

.animate-float-fast {
    animation: float-fast 3s ease-in-out infinite;
}

/* Enhanced Audio Player Styling */
.soundcloud-iframe {
    border-radius: 0.75rem;
    overflow: hidden;
}

/* Enhanced Form Styling */
.newsletter-form input:focus {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.newsletter-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Enhanced Social Share Button Animations */
.share-button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.share-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.share-button:hover::before {
    left: 100%;
}

.share-button:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

/* Enhanced Interactive Card Hover Effects */
.interactive-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
}

.interactive-card:hover {
    transform: translateY(-8px) rotateX(5deg) rotateY(5deg);
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
}

.interactive-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    border-radius: inherit;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.interactive-card:hover::before {
    opacity: 1;
}

/* Enhanced Modal Animations */
.modal-enter {
    animation: modalEnter 0.3s ease-out forwards;
}

.modal-exit {
    animation: modalExit 0.3s ease-in forwards;
}

@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes modalExit {
    from {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    to {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
}

/* Enhanced Loading Animations */
.loading-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.loading-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Enhanced Typography Gradient Effects */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Enhanced Audio Controls */
.audio-controls button {
    transition: all 0.3s ease;
}

.audio-controls button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
        height: 120px !important;
    }
}

/* Enhanced Background Patterns */
.bg-pattern-dots {
    background-image: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

.bg-pattern-grid {
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Enhanced Newsletter Success State */
.newsletter-success {
    animation: slideInFromBottom 0.5s ease-out forwards;
}

@keyframes slideInFromBottom {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
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
// Global variables
let isLikeProcessing = false;
let likedBlogs = {};
let currentBlogSlug = '<?php echo $blog->slug; ?>';

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Blog view script loaded');
    
    // Initialize all components
    initializeReadingProgress();
    initializeReadingTime();
    initializeLikeSystem();
    initializeBiasAnalysis();
    initializePartyPerspective();
    initializeAudioFeatures();
    
    // Load liked blogs from localStorage
    likedBlogs = JSON.parse(localStorage.getItem('likedBlogs') || '{}');
    updateLikeButtonStates();
});

// Reading Progress Bar
function initializeReadingProgress() {
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
        
        const progressBar = document.getElementById('reading-progress');
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
    }
    
    window.addEventListener('scroll', updateReadingProgress);
    window.addEventListener('resize', updateReadingProgress);
    updateReadingProgress();
}

// Reading Time Calculation
function initializeReadingTime() {
    const content = document.getElementById('blog-content');
    if (!content) return;
    
    const text = content.textContent || content.innerText;
    const words = text.trim().split(/\s+/).length;
    const wordsPerMinute = 200;
    const minutes = Math.ceil(words / wordsPerMinute);
    
    const readingTimeElements = document.querySelectorAll('#reading-minutes, #reading-minutes-content');
    readingTimeElements.forEach(element => {
        if (element) {
            element.textContent = minutes;
        }
    });
}

// Like System
function initializeLikeSystem() {
    const likeButton = document.getElementById('likeButton');
    const heroLikeButton = document.getElementById('heroLikeButton');
    
    if (likeButton) {
        likeButton.addEventListener('click', handleLikeClick);
        console.log('Like button event listener added');
    }
    
    if (heroLikeButton) {
        heroLikeButton.addEventListener('click', handleLikeClick);
        console.log('Hero like button event listener added');
    }
}

async function handleLikeClick(event) {
    if (isLikeProcessing) {
        console.log('Like already processing, ignoring click');
        return;
    }
    
    console.log('Like button clicked');
    
    const button = event.currentTarget;
    const slug = button.getAttribute('data-slug') || currentBlogSlug;
    
    console.log('Current blog slug:', currentBlogSlug);
    console.log('Button slug:', slug);
    console.log('LikedBlogs state:', likedBlogs);
    
    if (!slug) {
        console.error('No slug found for like action');
        showNotification('Er ging iets mis. Probeer het opnieuw.', 'error');
        return;
    }
    
    const action = likedBlogs[slug] ? 'unlike' : 'like';
    isLikeProcessing = true;
    
    // Visual feedback
    button.style.transform = 'scale(0.95)';
    
    // Disable buttons
    const allLikeButtons = document.querySelectorAll('#likeButton, #heroLikeButton');
    allLikeButtons.forEach(btn => btn.disabled = true);
    
    try {
        console.log(`Performing ${action} action for slug: ${slug}`);
        
        // Create endpoint URL
        const likeEndpoint = `<?php echo URLROOT; ?>/views/blogs/update_likes.php`;
        console.log('Like endpoint URL:', likeEndpoint);
        
        const formData = new FormData();
        formData.append('slug', slug);
        formData.append('action', action);
        
        console.log('Sending FormData:', {
            slug: formData.get('slug'),
            action: formData.get('action')
        });
        
        const response = await fetch(likeEndpoint, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error text:', errorText);
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }
        
        const responseText = await response.text();
        console.log('Raw response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            console.error('Response was not valid JSON:', responseText);
            throw new Error('Server response was not valid JSON');
        }
        
        console.log('Parsed response data:', data);
        
        if (data.success) {
            // Update like counts
            updateLikeCounts(data.likes);
            
            // Update local storage
            if (action === 'like') {
                likedBlogs[slug] = true;
                showNotification('Artikel geliked! ❤️', 'success');
                createConfetti(button);
            } else {
                delete likedBlogs[slug];
                showNotification('Like verwijderd', 'info');
            }
            
            localStorage.setItem('likedBlogs', JSON.stringify(likedBlogs));
            updateLikeButtonStates();
            
        } else {
            throw new Error(data.error || 'Onbekende fout');
        }
        
    } catch (error) {
        console.error('Like error details:', error);
        showNotification('Er ging iets mis bij het liken. Probeer het opnieuw.', 'error');
    } finally {
        isLikeProcessing = false;
        button.style.transform = 'scale(1)';
        allLikeButtons.forEach(btn => btn.disabled = false);
    }
}

function updateLikeCounts(newCount) {
    // Update all like count displays
    const likeCountDisplay = document.getElementById('likeCountDisplay');
    const heroLikeCount = document.getElementById('hero-like-count');
    
    if (likeCountDisplay) {
        likeCountDisplay.style.transform = 'scale(1.2)';
        setTimeout(() => {
            likeCountDisplay.textContent = newCount;
            likeCountDisplay.style.transform = 'scale(1)';
        }, 150);
    }
    
    if (heroLikeCount) {
        heroLikeCount.style.transform = 'scale(1.1)';
        setTimeout(() => {
            heroLikeCount.textContent = newCount + ' likes';
            heroLikeCount.style.transform = 'scale(1)';
        }, 150);
    }
}

function updateLikeButtonStates() {
    const isLiked = likedBlogs[currentBlogSlug] || false;
    const likeButton = document.getElementById('likeButton');
    const heroLikeButton = document.getElementById('heroLikeButton');
    
    if (likeButton) {
        if (isLiked) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }
    }
    
    if (heroLikeButton) {
        const svg = heroLikeButton.querySelector('svg');
        if (isLiked) {
            heroLikeButton.classList.add('liked');
            if (svg) svg.setAttribute('fill', 'currentColor');
        } else {
            heroLikeButton.classList.remove('liked');
            if (svg) svg.setAttribute('fill', 'none');
        }
    }
}

function createConfetti(button) {
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7'];
    const buttonRect = button.getBoundingClientRect();
    
    for (let i = 0; i < 20; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.left = (buttonRect.left + buttonRect.width / 2) + 'px';
            confetti.style.top = (buttonRect.top + buttonRect.height / 2) + 'px';
            confetti.style.width = '6px';
            confetti.style.height = '6px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.borderRadius = '50%';
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            
            document.body.appendChild(confetti);
            
            const angle = (Math.PI * 2 * i) / 20;
            const velocity = 2 + Math.random() * 2;
            const gravity = 0.1;
            let vx = Math.cos(angle) * velocity;
            let vy = Math.sin(angle) * velocity;
            let x = 0;
            let y = 0;
            
            const animate = () => {
                x += vx;
                y += vy;
                vy += gravity;
                
                confetti.style.transform = `translate(${x}px, ${y}px) rotate(${x}deg)`;
                confetti.style.opacity = Math.max(0, 1 - Math.abs(y) / 200);
                
                if (confetti.style.opacity > 0) {
                    requestAnimationFrame(animate);
                } else {
                    confetti.remove();
                }
            };
            
            requestAnimationFrame(animate);
        }, i * 30);
    }
}

// Bias Analysis System
function initializeBiasAnalysis() {
    const biasButton = document.getElementById('biasButton');
    const biasModal = document.getElementById('biasModal');
    const closeBiasModal = document.getElementById('closeBiasModal');
    const closeBiasModalFooter = document.getElementById('closeBiasModalFooter');
    const retryBiasAnalysis = document.getElementById('retryBiasAnalysis');
    
    if (biasButton) {
        biasButton.addEventListener('click', function() {
            console.log('Bias button clicked');
            const slug = this.getAttribute('data-slug') || currentBlogSlug;
            if (slug) {
                showBiasModal();
                performBiasAnalysis(slug);
            }
        });
    }
    
    if (closeBiasModal) {
        closeBiasModal.addEventListener('click', hideBiasModal);
    }
    
    if (closeBiasModalFooter) {
        closeBiasModalFooter.addEventListener('click', hideBiasModal);
    }
    
    if (retryBiasAnalysis) {
        retryBiasAnalysis.addEventListener('click', function() {
            const slug = biasButton?.getAttribute('data-slug') || currentBlogSlug;
            if (slug) {
                performBiasAnalysis(slug);
            }
        });
    }
    
    // Close modal on background click
    if (biasModal) {
        biasModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideBiasModal();
            }
        });
    }
}

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
    console.log('Starting bias analysis for slug:', slug);
    
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
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Bias analysis response:', data);
        
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
        showBiasError('Netwerk fout: Kon geen verbinding maken met de server. Controleer je internetverbinding en probeer het opnieuw.');
    }
}

function showBiasResults(analysis) {
    console.log('Showing bias results:', analysis);
    
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
        setIndicator('economicIndicator', analysis.indicators.economic);
        setIndicator('socialIndicator', analysis.indicators.social);
        setIndicator('immigrationIndicator', analysis.indicators.immigration);
    }
    
    // Set summary
    const summaryText = document.getElementById('summaryText');
    if (summaryText && analysis.summary) {
        summaryText.textContent = analysis.summary;
    }
}

function setIndicator(elementId, value) {
    const element = document.getElementById(elementId);
    if (element && value) {
        const normalized = value.toLowerCase();
        element.textContent = getOrientationLabel(normalized);
        element.className = `px-3 py-1 rounded-full text-sm font-medium ${getOrientationColors(normalized)}`;
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

// Party Perspective System  
function initializePartyPerspective() {
    const partyPerspectiveButton = document.getElementById('partyPerspectiveButton');
    const partyModal = document.getElementById('partyModal');
    const closePartyModal = document.getElementById('closePartyModal');
    const closePartyModalFooter = document.getElementById('closePartyModalFooter');
    const backToPartySelection = document.getElementById('backToPartySelection');
    
    if (partyPerspectiveButton) {
        partyPerspectiveButton.addEventListener('click', function() {
            console.log('Party perspective button clicked');
            showPartyModal();
        });
    }
    
    if (closePartyModal) {
        closePartyModal.addEventListener('click', hidePartyModal);
    }
    
    if (closePartyModalFooter) {
        closePartyModalFooter.addEventListener('click', hidePartyModal);
    }
    
    if (backToPartySelection) {
        backToPartySelection.addEventListener('click', showPartySelection);
    }
    
    // Background click to close
    if (partyModal) {
        partyModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hidePartyModal();
            }
        });
    }
    
    // Party selection buttons
    document.querySelectorAll('.party-select-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const party = this.getAttribute('data-party');
            if (party) {
                console.log('Party selected:', party);
                performPartyAnalysis(party);
            }
        });
    });
}

function showPartyModal() {
    const modal = document.getElementById('partyModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        showPartySelection();
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

async function performPartyAnalysis(party) {
    console.log('Starting party analysis for:', party);
    
    try {
        // Hide selection, show loading
        document.getElementById('partySelectionGrid')?.classList.add('hidden');
        document.getElementById('partyLoading')?.classList.remove('hidden');
        document.getElementById('partyError')?.classList.add('hidden');
        document.getElementById('partyResults')?.classList.add('hidden');
        
        const slug = currentBlogSlug;
        if (!slug) return;
        
        const formData = new FormData();
        formData.append('slug', slug);
        formData.append('party', party);
        formData.append('type', 'leader');
        
        const response = await fetch('<?php echo URLROOT; ?>/controllers/blogs/party-perspective.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Party analysis response:', data);
        
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
        showPartyError('Netwerk fout: Kon geen verbinding maken met de server. Controleer je internetverbinding en probeer het opnieuw.');
    }
}

function showPartyResults(data, partyKey) {
    console.log('Showing party results for:', partyKey);
    
    const partyData = {
        'PVV': { name: 'Partij voor de Vrijheid', leader: 'Geert Wilders', logo: 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg', leaderPhoto: '/partijleiders/geert.jpg' },
        'VVD': { name: 'Volkspartij voor Vrijheid en Democratie', leader: 'Dilan Yeşilgöz-Zegerius', logo: 'https://logo.clearbit.com/vvd.nl', leaderPhoto: '/partijleiders/dilan.jpg' },
        'GL-PvdA': { name: 'GroenLinks-PvdA', leader: 'Frans Timmermans', logo: 'https://i.ibb.co/67hkc5Hv/gl-pvda.png', leaderPhoto: '/partijleiders/frans.jpg' },
        'NSC': { name: 'Nieuw Sociaal Contract', leader: 'Nicolien van Vroonhoven', logo: 'https://i.ibb.co/YT2fJZb4/nsc.png', leaderPhoto: 'https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg' },
        'BBB': { name: 'BoerBurgerBeweging', leader: 'Caroline van der Plas', logo: 'https://i.ibb.co/qMjw7jDV/bbb.png', leaderPhoto: '/partijleiders/plas.jpg' },
        'D66': { name: 'Democraten 66', leader: 'Rob Jetten', logo: 'https://logo.clearbit.com/d66.nl', leaderPhoto: '/partijleiders/rob.jpg' },
        'SP': { name: 'Socialistische Partij', leader: 'Jimmy Dijk', logo: 'https://logo.clearbit.com/sp.nl', leaderPhoto: '/partijleiders/jimmy.jpg' },
        'PvdD': { name: 'Partij voor de Dieren', leader: 'Esther Ouwehand', logo: 'https://logo.clearbit.com/partijvoordedieren.nl', leaderPhoto: '/partijleiders/esther.jpg' },
        'CDA': { name: 'Christen-Democratisch Appèl', leader: 'Henri Bontenbal', logo: 'https://logo.clearbit.com/cda.nl', leaderPhoto: '/partijleiders/Henri.jpg' },
        'JA21': { name: 'Juiste Antwoord 2021', leader: 'Joost Eerdmans', logo: 'https://logo.clearbit.com/ja21.nl', leaderPhoto: '/partijleiders/joost.jpg' },
        'SGP': { name: 'Staatkundig Gereformeerde Partij', leader: 'Chris Stoffer', logo: 'https://logo.clearbit.com/sgp.nl', leaderPhoto: '/partijleiders/Chris.jpg' },
        'FvD': { name: 'Forum voor Democratie', leader: 'Thierry Baudet', logo: 'https://logo.clearbit.com/fvd.nl', leaderPhoto: '/partijleiders/thierry.jpg' },
        'DENK': { name: 'DENK', leader: 'Stephan van Baarle', logo: 'https://logo.clearbit.com/bewegingdenk.nl', leaderPhoto: '/partijleiders/baarle.jpg' },
        'Volt': { name: 'Volt Nederland', leader: 'Laurens Dassen', logo: 'https://logo.clearbit.com/voltnederland.org', leaderPhoto: '/partijleiders/dassen.jpg' },
        'CU': { name: 'ChristenUnie', leader: 'Mirjam Bikker', logo: 'https://logo.clearbit.com/christenunie.nl', leaderPhoto: 'https://i.ibb.co/wh3wwQ66/Bikker.jpg' }
    };
    
    const party = partyData[partyKey];
    if (!party) return;
    
    // Show results container
    document.getElementById('partyResults')?.classList.remove('hidden');
    document.getElementById('backToPartySelection')?.classList.remove('hidden');
    
    // Set party info - show leader photo for leader mode
    const logo = document.getElementById('partyResultLogo');
    if (logo) {
        logo.src = party.leaderPhoto;
        logo.alt = party.leader;
        logo.className = 'w-20 h-20 object-cover rounded-full border-2 border-gray-300';
    }
    
    const name = document.getElementById('partyResultName');
    if (name) {
        name.textContent = party.leader;
    }
    
    const leader = document.getElementById('partyResultLeader');
    if (leader) {
        leader.textContent = `Partijleider ${party.name}`;
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

// Audio Features
function initializeAudioFeatures() {
    // Initialize any audio-related features
    <?php if (!empty($blog->audio_path) || !empty($blog->audio_url)): ?>
    console.log('Audio features initialized');
    <?php endif; ?>
}

// Notification System
function showNotification(message, type = 'info') {
    console.log(`Notification: ${message} (${type})`);
    
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
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Utility functions for share buttons
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
    const url = window.location.href;
    window.open(
        `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
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

// Character count for comment textarea
const contentTextarea = document.getElementById('content');
const charCount = document.getElementById('charCount');

if (contentTextarea && charCount) {
    function updateCharCount() {
        const length = contentTextarea.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 1000) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    }
    
    contentTextarea.addEventListener('input', updateCharCount);
    updateCharCount();
}

// Comment like functionality
function initializeCommentLikes() {
    const likeButtons = document.querySelectorAll('.comment-like-btn');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', handleCommentLike);
    });
}

async function handleCommentLike(event) {
    const button = event.currentTarget;
    const commentId = button.getAttribute('data-comment-id');
    const isLiked = button.getAttribute('data-liked') === 'true';
    const action = isLiked ? 'unlike' : 'like';
    
    // Disable button during request
    button.disabled = true;
    
    try {
        const response = await fetch('<?php echo URLROOT; ?>/ajax/comment-like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                comment_id: parseInt(commentId),
                action: action
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update button state
            const isNowLiked = data.is_liked;
            button.setAttribute('data-liked', isNowLiked.toString());
            
            const svg = button.querySelector('svg');
            const span = button.querySelector('span');
            
            if (isNowLiked) {
                // Liked state
                button.classList.remove('text-gray-500', 'hover:text-red-600');
                button.classList.add('text-red-600');
                svg.setAttribute('fill', 'currentColor');
                svg.classList.add('scale-110');
                span.textContent = 'Leuk gevonden';
                button.title = 'Reactie niet meer leuk vinden';
                
                // Add liked badge if not exists and update comment styling
                const commentContainer = button.closest('.rounded-2xl');
                const commentHeader = commentContainer.querySelector('.px-6.py-4');
                const actionsDiv = button.closest('.flex');
                const badgeContainer = actionsDiv.querySelector('.flex.items-center.gap-3');
                
                // Update comment container styling
                commentContainer.className = 'bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border-2 border-red-300 shadow-xl ring-2 ring-red-200 ring-opacity-50 rounded-2xl overflow-hidden transition-all duration-500 transform hover:scale-[1.02] hover:shadow-2xl';
                
                // Update header styling
                commentHeader.className = 'px-6 py-4 bg-gradient-to-r from-red-100 via-pink-100 to-red-100 border-b border-red-200';
                
                if (!badgeContainer.querySelector('.bg-gradient-to-r.from-red-500')) {
                    const badge = document.createElement('div');
                    badge.className = 'inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 border border-red-400 rounded-full shadow-lg creator-liked-badge';
                    badge.innerHTML = `
                        <svg class="w-4 h-4 text-white animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                                                 <span class="text-white text-sm font-bold">Leuk gevonden door auteur</span>
                    `;
                    badgeContainer.appendChild(badge);
                }
                
                // Add like animation and special effects
                createLikeAnimation(button);
                
                // Add sparkle effect to the comment
                createSparkleEffect(commentContainer);
                
            } else {
                // Unliked state
                button.classList.remove('text-red-600');
                button.classList.add('text-gray-500', 'hover:text-red-600');
                svg.setAttribute('fill', 'none');
                svg.classList.remove('scale-110');
                span.textContent = 'Leuk vinden';
                button.title = 'Reactie leuk vinden';
                
                // Reset comment container styling
                const commentContainer = button.closest('.rounded-2xl');
                const commentHeader = commentContainer.querySelector('.px-6.py-4');
                commentContainer.className = 'bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-md';
                commentHeader.className = 'px-6 py-4 bg-gradient-to-r from-gray-50 to-primary/5 border-b border-gray-100';
                
                // Remove liked badge
                const actionsDiv = button.closest('.flex');
                const badgeContainer = actionsDiv.querySelector('.flex.items-center.gap-3');
                const badge = badgeContainer.querySelector('.creator-liked-badge');
                if (badge) {
                    badge.remove();
                }
            }
            
            // Show success message
            const message = isNowLiked ? 'Reactie gemarkeerd als leuk gevonden!' : 'Reactie niet meer leuk gevonden';
            showNotification(message, 'success');
            
        } else {
            throw new Error(data.error || 'Unknown error');
        }
        
    } catch (error) {
        console.error('Comment like error:', error);
        showNotification('Er ging iets mis bij het liken van de reactie', 'error');
    } finally {
        button.disabled = false;
    }
}

function createLikeAnimation(button) {
    // Create floating hearts animation
    const hearts = ['❤️', '💖', '💕', '💗', '💘', '💝'];
    
    for (let i = 0; i < 8; i++) {
        setTimeout(() => {
            const heart = document.createElement('div');
            heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
            heart.className = 'absolute pointer-events-none text-lg';
            heart.style.left = '50%';
            heart.style.top = '50%';
            heart.style.transform = 'translate(-50%, -50%)';
            heart.style.zIndex = '9999';
            
            button.appendChild(heart);
            
            // Animate heart
            const angle = (Math.PI * 2 * i) / 8;
            const distance = 40 + Math.random() * 30;
            const duration = 1200 + Math.random() * 600;
            
            heart.animate([
                {
                    transform: 'translate(-50%, -50%) scale(0) rotate(0deg)',
                    opacity: 1
                },
                {
                    transform: `translate(${Math.cos(angle) * distance - 50}%, ${Math.sin(angle) * distance - 50}%) scale(1.2) rotate(360deg)`,
                    opacity: 0
                }
            ], {
                duration: duration,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            }).onfinish = () => heart.remove();
            
        }, i * 80);
    }
}

function createSparkleEffect(commentContainer) {
    // Create sparkle effects around the comment
    const sparkles = ['✨', '💫', '🌟', '⭐'];
    
    for (let i = 0; i < 12; i++) {
        setTimeout(() => {
            const sparkle = document.createElement('div');
            sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
            sparkle.className = 'absolute pointer-events-none text-sm';
            sparkle.style.zIndex = '9998';
            
            // Random position around the comment
            const rect = commentContainer.getBoundingClientRect();
            const x = Math.random() * rect.width;
            const y = Math.random() * rect.height;
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            
            commentContainer.appendChild(sparkle);
            
            // Animate sparkle
            const moveX = (Math.random() - 0.5) * 60;
            const moveY = (Math.random() - 0.5) * 60;
            const duration = 1500 + Math.random() * 1000;
            
            sparkle.animate([
                {
                    transform: 'scale(0) rotate(0deg)',
                    opacity: 1
                },
                {
                    transform: `translate(${moveX}px, ${moveY}px) scale(1.5) rotate(180deg)`,
                    opacity: 0.7
                },
                {
                    transform: `translate(${moveX * 2}px, ${moveY * 2}px) scale(0) rotate(360deg)`,
                    opacity: 0
                }
            ], {
                duration: duration,
                easing: 'ease-out'
            }).onfinish = () => sparkle.remove();
            
        }, i * 120);
    }
}

// Load More Comments Functionality
function initializeLoadMoreComments() {
    const loadMoreBtn = document.getElementById('loadMoreComments');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Find all hidden comments
            const hiddenComments = document.querySelectorAll('.comment-hidden');
            
            // Show all hidden comments with animation
            hiddenComments.forEach((comment, index) => {
                setTimeout(() => {
                    comment.classList.remove('hidden', 'comment-hidden');
                    comment.style.opacity = '0';
                    comment.style.transform = 'translateY(20px)';
                    
                    // Animate in
                    setTimeout(() => {
                        comment.style.transition = 'all 0.5s ease-out';
                        comment.style.opacity = '1';
                        comment.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100); // Stagger animation
            });
            
            // Hide the load more button with animation
            loadMoreContainer.style.transition = 'all 0.3s ease-out';
            loadMoreContainer.style.opacity = '0';
            loadMoreContainer.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                loadMoreContainer.style.display = 'none';
            }, 300);
        });
    }
}

// Poll functionaliteit
function votePoll(choice) {
    const pollContainer = document.getElementById('pollContainer');
    const pollId = pollContainer.getAttribute('data-poll-id');
    
    if (!pollId) {
        showNotification('Poll ID niet gevonden', 'error');
        return;
    }
    
    // Disable poll buttons tijdens stemmen
    const pollButtons = document.querySelectorAll('.poll-option');
    pollButtons.forEach(button => {
        button.disabled = true;
        button.style.opacity = '0.5';
    });
    
    // Toon loading state
    showNotification('Stem wordt verwerkt...', 'info');
    
    // Verstuur AJAX request
    const baseUrl = '<?php echo URLROOT; ?>';
    fetch(baseUrl + '/ajax/poll-vote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `poll_id=${pollId}&choice=${choice}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toon succes bericht
            showNotification(data.message, 'success');
            
            // Update poll weergave met resultaten
            updatePollResults(data.poll, choice);
        } else {
            // Toon foutmelding
            showNotification(data.message, 'error');
            
            // Re-enable buttons bij fout
            pollButtons.forEach(button => {
                button.disabled = false;
                button.style.opacity = '1';
            });
        }
    })
    .catch(error => {
        console.error('Poll voting error:', error);
        showNotification('Er is een fout opgetreden bij het stemmen', 'error');
        
        // Re-enable buttons bij fout
        pollButtons.forEach(button => {
            button.disabled = false;
            button.style.opacity = '1';
        });
    });
}

function updatePollResults(poll, userChoice) {
    const pollContainer = document.getElementById('pollContainer');
    
    // Bereken percentages
    const totalVotes = parseInt(poll.option_a_votes) + parseInt(poll.option_b_votes);
    const optionAPercentage = totalVotes > 0 ? Math.round((poll.option_a_votes / totalVotes) * 100 * 10) / 10 : 0;
    const optionBPercentage = totalVotes > 0 ? Math.round((poll.option_b_votes / totalVotes) * 100 * 10) / 10 : 0;
    
    // Update HTML met nieuwe clean design
    pollContainer.innerHTML = `
        <div class="space-y-4 mb-6">
            <!-- Optie A -->
            <div class="relative group">
                <div class="bg-white border-2 ${userChoice === 'A' ? 'border-blue-500' : 'border-gray-200'} rounded-xl overflow-hidden">
                    <!-- Progress bar -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-blue-100 opacity-60 transition-all duration-1000 ease-out" style="width: ${optionAPercentage}%"></div>
                    
                    <div class="relative p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    A
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-base break-words">
                                        ${poll.option_a}
                                    </p>
                                    ${userChoice === 'A' ? `
                                        <p class="text-sm text-blue-600 font-medium mt-1">
                                            ✓ Jouw keuze
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <div class="text-2xl font-bold text-blue-600">
                                    ${optionAPercentage}%
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${poll.option_a_votes} ${poll.option_a_votes === 1 ? 'stem' : 'stemmen'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optie B -->
            <div class="relative group">
                <div class="bg-white border-2 ${userChoice === 'B' ? 'border-red-500' : 'border-gray-200'} rounded-xl overflow-hidden">
                    <!-- Progress bar -->
                    <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-red-100 opacity-60 transition-all duration-1000 ease-out" style="width: ${optionBPercentage}%"></div>
                    
                    <div class="relative p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    B
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-base break-words">
                                        ${poll.option_b}
                                    </p>
                                    ${userChoice === 'B' ? `
                                        <p class="text-sm text-red-600 font-medium mt-1">
                                            ✓ Jouw keuze
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <div class="text-2xl font-bold text-red-600">
                                    ${optionBPercentage}%
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${poll.option_b_votes} ${poll.option_b_votes === 1 ? 'stem' : 'stemmen'}
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
    `;
    
    // Update totaal aantal stemmen in header
    const totalVotesElements = document.querySelectorAll('.font-semibold.text-primary');
    totalVotesElements.forEach(element => {
        if (element.textContent && !isNaN(parseInt(element.textContent))) {
            element.textContent = totalVotes;
            
            // Update bijbehorende tekst
            const nextElement = element.nextSibling;
            if (nextElement && nextElement.textContent) {
                nextElement.textContent = totalVotes === 1 ? ' persoon heeft gestemd' : ' mensen hebben gestemd';
            }
        }
    });
}

// Initialize comment likes when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCommentLikes();
    initializeLoadMoreComments();
});

console.log('Blog view script fully loaded and initialized');
</script>