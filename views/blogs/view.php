<?php 
// Laad PartyModel voor dynamische partij logo's
require_once __DIR__ . '/../../models/PartyModel.php';

// Haal partij data dynamisch op uit de database
$partyModel = new PartyModel();
$dbParties = $partyModel->getAllParties();

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
                        
                        <!-- Instagram Story Enhanced -->
                        <button id="instagramStoryBtn" 
                                onclick="shareToInstagramStory()"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-pink-500 via-purple-600 to-orange-500 text-white rounded-2xl hover:from-pink-600 hover:via-purple-700 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Animated Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-pink-400 via-purple-500 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <!-- Instagram Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Story</div>
                                    <div class="text-xs text-pink-100">Instagram</div>
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

<!-- Bias Analysis Modal - Uitgebreide versie -->
<div id="biasModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="hideBiasModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-start justify-center min-h-screen p-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full transform transition-all">
            <!-- Modal Header met thema kleuren -->
            <div id="biasModalHeader" class="bg-gradient-to-r from-primary-dark via-primary to-primary-dark px-5 py-4 rounded-t-2xl relative overflow-hidden">
                <!-- Animated background effect -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-1/4 w-24 h-24 bg-secondary rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 w-24 h-24 bg-primary-light rounded-full filter blur-3xl"></div>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/15 backdrop-blur rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Politieke Spectrum Analyse</h3>
                            <p class="text-blue-200 text-xs">Multi-dimensionale analyse</p>
                        </div>
                    </div>
                    <button id="closeBiasModal" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-5 bg-gray-50">
                <!-- Loading State -->
                <div id="biasLoading" class="text-center py-12">
                    <div class="relative inline-flex">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full"></div>
                        <div class="w-12 h-12 border-4 border-secondary rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                    </div>
                    <p class="text-gray-700 font-medium mt-4">Artikel wordt geanalyseerd...</p>
                    <p class="text-gray-500 text-sm mt-1">Dit kan 10-15 seconden duren</p>
                    <div class="flex justify-center gap-1.5 mt-3">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="biasError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h4 class="text-red-800 font-bold mb-1">Analyse mislukt</h4>
                        <p id="biasErrorMessage" class="text-red-600 text-sm"></p>
                    </div>
                </div>
                
                <!-- Results -->
                <div id="biasResults" class="hidden space-y-4">
                    
                    <!-- Overall Orientation Hero Card -->
                    <div id="overallOrientationCard" class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-dark rounded-xl p-5 text-white overflow-hidden">
                        <div class="absolute inset-0 opacity-15">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary rounded-full filter blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-primary-light rounded-full filter blur-3xl"></div>
                        </div>
                        <div class="relative">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-1">Politieke Orientatie</p>
                                    <h3 id="orientationBadge" class="text-2xl font-bold"></h3>
                            </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <p class="text-blue-200 text-xs uppercase tracking-wider">Zekerheid</p>
                                        <p class="text-xl font-bold"><span id="confidenceText">--</span>%</p>
                        </div>
                                    <div class="w-14 h-14 relative">
                                        <svg class="w-14 h-14 transform -rotate-90">
                                            <circle cx="28" cy="28" r="24" stroke="rgba(255,255,255,0.2)" stroke-width="4" fill="none"/>
                                            <circle id="confidenceCircle" cx="28" cy="28" r="24" stroke="url(#confidenceGradient)" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="151" stroke-dashoffset="151" class="transition-all duration-1000"/>
                                        </svg>
                                        <svg class="absolute top-0 left-0 w-0 h-0">
                                            <defs>
                                                <linearGradient id="confidenceGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#c41e3a"/>
                                                    <stop offset="100%" stop-color="#d63856"/>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p id="overallSummary" class="text-blue-100 mt-3 text-sm leading-relaxed"></p>
                    </div>
                </div>
                
                    <!-- Spectrum Breakdown -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            Politiek Spectrum (5 Assen)
                        </h4>
                        
                        <div class="space-y-4">
                            <!-- Economisch -->
                            <div class="spectrum-item" data-spectrum="economisch">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Economisch</span>
                                    <span id="economischLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-secondary via-gray-200 to-primary rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                </div>
                                    <div id="economischMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                            </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Links</span>
                                    <span>Rechts</span>
                        </div>
                                <p id="economischToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- Sociaal-cultureel -->
                            <div class="spectrum-item" data-spectrum="sociaal_cultureel">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Sociaal-cultureel</span>
                                    <span id="sociaal_cultureel_label" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-pink-400 via-gray-200 to-amber-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="sociaal_cultureel_marker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Progressief</span>
                                    <span>Conservatief</span>
                                </div>
                                <p id="sociaal_cultureel_toelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- EU/Internationaal -->
                            <div class="spectrum-item" data-spectrum="eu_internationaal">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">EU / Internationaal</span>
                                    <span id="eu_internationaal_label" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                            </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-blue-500 via-gray-200 to-orange-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                            </div>
                                    <div id="eu_internationaal_marker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                            </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Pro-EU</span>
                                    <span>Nationalistisch</span>
                        </div>
                                <p id="eu_internationaal_toelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- Klimaat -->
                            <div class="spectrum-item" data-spectrum="klimaat">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Klimaat</span>
                                    <span id="klimaatLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-green-500 via-gray-200 to-gray-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="klimaatMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Groen</span>
                                    <span>Economie-eerst</span>
                                </div>
                                <p id="klimaatToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                            </div>
                            
                            <!-- Immigratie -->
                            <div class="spectrum-item" data-spectrum="immigratie">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Immigratie</span>
                                    <span id="immigratieLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-teal-400 via-gray-200 to-secondary rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="immigratieMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Open</span>
                                    <span>Restrictief</span>
                                </div>
                                <p id="immigratieToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Two Column Layout for Retoriek & Schrijfstijl -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Retorische Analyse -->
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                Retorische Analyse
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Toon</span>
                                    <span id="retoriekToon" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Framing</span>
                                    <span id="retoriekFraming" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Stijl</span>
                                    <span id="retoriekStijl" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="pt-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Objectiviteit</span>
                                        <span id="retoriekObjectiviteitValue" class="text-xs font-bold text-gray-800"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="retoriekObjectiviteitBar" class="h-full bg-gradient-to-r from-secondary to-secondary-light transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <p id="retoriekToelichting" class="text-xs text-gray-500 mt-2 italic hidden"></p>
                            </div>
                        </div>
                        
                        <!-- Schrijfstijl Analyse -->
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Schrijfstijl
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Feitelijk vs Mening</span>
                                        <span id="schrijfstijlFeitelijkValue" class="text-xs text-gray-500"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="schrijfstijlFeitelijkBar" class="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-700" style="width: 50%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                        <span>Feitelijk</span>
                                        <span>Mening</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Emotionele lading</span>
                                        <span id="schrijfstijlEmotieValue" class="text-xs text-gray-500"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="schrijfstijlEmotieBar" class="h-full bg-gradient-to-r from-gray-400 to-secondary transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Bronverwijzingen</span>
                                    <span id="schrijfstijlBronnen" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-xs text-gray-600">Argumentatie</span>
                                    <span id="schrijfstijlArgumentatie" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Partij Matching -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Partij Matching
                        </h4>
                        
                        <div class="space-y-3">
                            <!-- Best Match -->
                            <div class="bg-gradient-to-r from-primary/5 to-primary/10 rounded-lg p-3 border border-primary/20">
                                <p class="text-xs text-primary uppercase tracking-wider font-medium mb-0.5">Best passende partij</p>
                                <p id="partijBestMatch" class="text-base font-bold text-primary-dark"></p>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-3">
                                <!-- Zou onderschrijven -->
                                <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                    <p class="text-xs text-green-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Zou onderschrijven
                                    </p>
                                    <div id="partijOnderschrijven" class="flex flex-wrap gap-1.5"></div>
                                </div>
                                
                                <!-- Zou afwijzen -->
                                <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                                    <p class="text-xs text-red-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Zou afwijzen
                                    </p>
                                    <div id="partijAfwijzen" class="flex flex-wrap gap-1.5"></div>
                                </div>
                            </div>
                            
                            <p id="partijToelichting" class="text-xs text-gray-600 italic hidden"></p>
                        </div>
                    </div>
                    
                    <!-- Doelgroep & Kernpunten -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Doelgroep -->
                        <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-xl p-4 border border-primary/20">
                            <h4 class="text-sm font-bold text-primary-dark mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Doelgroep
                            </h4>
                            <div class="space-y-1">
                                <p id="doelgroepPrimair" class="text-primary-dark font-medium text-sm"></p>
                                <p id="doelgroepDemografisch" class="text-xs text-gray-600"></p>
                                <p id="doelgroepPolitiek" class="text-xs text-gray-500 italic"></p>
                            </div>
                        </div>
                        
                        <!-- Kernpunten -->
                        <div class="bg-gradient-to-br from-secondary/5 to-secondary/10 rounded-xl p-4 border border-secondary/20">
                            <h4 class="text-sm font-bold text-secondary-dark mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Kernpunten
                            </h4>
                            <ul id="kernpuntenList" class="space-y-1"></ul>
                        </div>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="bg-gray-100 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-gray-600">
                                <strong class="text-gray-700">Disclaimer:</strong> Deze analyse dient als indicatie. Politieke standpunten zijn complex - gebruik de resultaten als startpunt voor reflectie.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-white px-5 py-3 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between items-center">
                    <p class="text-xs text-gray-400">PolitiekPraat Analyse</p>
                    <div class="flex gap-2">
                        <button id="retryBiasAnalysis" class="px-3 py-1.5 text-secondary hover:text-secondary-dark hover:bg-secondary/5 rounded-lg transition-colors hidden text-sm font-medium">
                            Opnieuw
                        </button>
                        <button id="closeBiasModalFooter" class="px-4 py-1.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors text-sm font-medium">
                        Sluiten
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Party Perspective Modal - Uitgebreide versie -->
<div id="partyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="hidePartyModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-start justify-center min-h-screen p-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full transform transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-dark via-primary to-primary-dark px-5 py-4 rounded-t-2xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-1/4 w-24 h-24 bg-secondary rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 w-24 h-24 bg-primary-light rounded-full filter blur-3xl"></div>
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/15 backdrop-blur rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Partijleider Reacties</h3>
                            <p class="text-blue-200 text-xs">Kies een partijleider</p>
                        </div>
                    </div>
                    <button id="closePartyModal" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-5 bg-gray-50">
                <!-- Party Selection Grid -->
                <div id="partySelectionGrid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-4">
                    <?php foreach ($dbParties as $partyKey => $party): ?>
                    <button type="button" class="party-select-btn" data-party="<?php echo htmlspecialchars($partyKey); ?>">
                        <div class="p-3 border border-gray-200 rounded-xl hover:border-primary hover:bg-primary/5 transition-all cursor-pointer group text-center">
                            <img src="<?php echo htmlspecialchars($party['logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($partyKey); ?>" 
                                 class="w-12 h-12 mx-auto mb-1.5 object-contain"
                                 onerror="this.style.display='none'">
                            <h4 class="font-bold text-xs text-gray-900 group-hover:text-primary"><?php echo htmlspecialchars($partyKey); ?></h4>
                            <p class="text-xs text-gray-500 mt-0.5 leader-name truncate"><?php echo htmlspecialchars($party['leader']); ?></p>
                            <?php if (!empty($party['leader_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($party['leader_photo']); ?>" alt="<?php echo htmlspecialchars($party['leader']); ?>" class="leader-photo w-8 h-8 rounded-full mx-auto mt-1.5 object-cover border border-gray-200 hidden">
                            <?php endif; ?>
                        </div>
                    </button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Loading State -->
                <div id="partyLoading" class="hidden text-center py-12">
                    <div class="relative inline-flex">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full"></div>
                        <div class="w-12 h-12 border-4 border-secondary rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                    </div>
                    <p class="text-gray-700 font-medium mt-4">Reactie wordt gegenereerd...</p>
                    <p class="text-gray-500 text-sm mt-1">Dit kan 10-15 seconden duren</p>
                    <div class="flex justify-center gap-1.5 mt-3">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="partyError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h4 class="text-red-800 font-bold mb-1">Genereren mislukt</h4>
                        <p id="partyErrorMessage" class="text-red-600 text-sm"></p>
                    </div>
                </div>
                
                <!-- Results - Uitgebreide versie -->
                <div id="partyResults" class="hidden space-y-4">
                    
                    <!-- Leider Hero Card -->
                    <div id="leaderHeroCard" class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-dark rounded-xl p-5 text-white overflow-hidden">
                        <div class="absolute inset-0 opacity-15">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary rounded-full filter blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-primary-light rounded-full filter blur-3xl"></div>
                        </div>
                        <div class="relative flex items-center gap-4">
                            <img id="partyResultPhoto" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-white/30 shadow-lg">
                            <div class="flex-1">
                                <p id="partyResultName" class="text-xl font-bold"></p>
                                <p id="partyResultLeader" class="text-blue-200 text-sm"></p>
                            </div>
                            <img id="partyResultLogo" src="" alt="" class="w-12 h-12 object-contain opacity-80">
                        </div>
                    </div>
                    
                    <!-- Reactie Card -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Reactie
                        </h4>
                        <div class="space-y-3">
                            <p id="partyResultOpening" class="text-lg font-medium text-gray-900 italic border-l-4 border-secondary pl-3"></p>
                            <div id="partyResultContent" class="text-gray-700 text-sm leading-relaxed"></div>
                            <p id="partyResultAfsluiting" class="text-sm text-gray-600 font-medium border-l-4 border-primary pl-3"></p>
                        </div>
                    </div>
                    
                    <!-- Toon & Sentiment Analyse -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Toon Analyse
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Toon</span>
                                    <span id="partyToon" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Emotie</span>
                                    <span id="partyEmotie" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Sentiment</span>
                                        <span id="partySentimentValue" class="text-xs font-bold text-gray-800"></span>
                            </div>
                                    <div class="h-2 bg-gradient-to-r from-red-400 via-gray-200 to-green-400 rounded-full overflow-hidden relative">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-0.5 h-full bg-gray-400/50"></div>
                        </div>
                                        <div id="partySentimentMarker" class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                        <span>Negatief</span>
                                        <span>Positief</span>
                                    </div>
                                </div>
                    </div>
                </div>
                
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Authenticiteit
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Authenticiteit Score</span>
                                        <span id="partyAuthenticiteit" class="text-xs font-bold text-gray-800"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="partyAuthenticiteitBar" class="h-full bg-gradient-to-r from-secondary to-secondary-light transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-xs text-gray-600">Retorische Stijl</span>
                                    <span id="partyRetoriek" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Standpunten -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Standpunten
                        </h4>
                        
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Kernpunten</p>
                            <ul id="partyKernpunten" class="space-y-1"></ul>
                    </div>
                        
                        <div class="grid md:grid-cols-2 gap-3">
                            <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                <p class="text-xs text-green-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Eens met artikel
                                </p>
                                <ul id="partyEens" class="space-y-1"></ul>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                                <p class="text-xs text-red-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Oneens met artikel
                                </p>
                                <ul id="partyOneens" class="space-y-1"></ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Partij Context -->
                    <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-xl p-4 border border-primary/20">
                        <h4 class="text-sm font-bold text-primary-dark mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Partij Context
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Relevante Beloftes</p>
                                <ul id="partyBeloftes" class="space-y-1"></ul>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Voorgestelde Oplossing</p>
                                <p id="partyOplossing" class="text-sm text-gray-700"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="bg-gray-100 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-gray-600">
                                <strong class="text-gray-700">Disclaimer:</strong> Dit is een AI-gegenereerde simulatie van hoe deze politicus zou kunnen reageren. Het is geen echte uitspraak.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-white px-5 py-3 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between items-center">
                    <button id="backToPartySelection" class="px-3 py-1.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors hidden text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Terug
                    </button>
                    <p class="text-xs text-gray-400">PolitiekPraat Analyse</p>
                    <button id="closePartyModalFooter" class="px-4 py-1.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors text-sm font-medium">
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

// Reading Progress Bar - optimized with throttling
function initializeReadingProgress() {
    let ticking = false;
    let lastUpdateTime = 0;
    const updateThrottle = 16; // ~60fps
    
    function updateReadingProgress() {
        const now = Date.now();
        if (now - lastUpdateTime < updateThrottle) {
            ticking = false;
            return;
        }
        lastUpdateTime = now;
        
        const article = document.getElementById('blog-content');
        if (!article) {
            ticking = false;
            return;
        }
        
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
        
        ticking = false;
    }
    
    function requestUpdate() {
        if (!ticking) {
            requestAnimationFrame(updateReadingProgress);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate, { passive: true });
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
    
    // === OVERALL ORIENTATION ===
    const orientationBadge = document.getElementById('orientationBadge');
    if (orientationBadge && analysis.overall?.orientatie) {
        orientationBadge.textContent = analysis.overall.orientatie;
    }
    
    // Set confidence with animated circle
    const confidenceText = document.getElementById('confidenceText');
    const confidenceCircle = document.getElementById('confidenceCircle');
    if (confidenceText && analysis.overall?.confidence) {
        const confidence = analysis.overall.confidence;
        confidenceText.textContent = confidence;
        
        // Animate the SVG circle (r=24, circumference = 2 * PI * 24 = 150.8)
        if (confidenceCircle) {
            const circumference = 2 * Math.PI * 24;
            const offset = circumference - (confidence / 100) * circumference;
            setTimeout(() => {
                confidenceCircle.style.strokeDashoffset = offset;
            }, 100);
        }
    }
    
    // Set overall summary
    const overallSummary = document.getElementById('overallSummary');
    if (overallSummary && analysis.overall?.samenvatting) {
        overallSummary.textContent = analysis.overall.samenvatting;
    }
    
    // === SPECTRUM BREAKDOWN ===
    if (analysis.spectrum) {
        // Economisch
        setSpectrumMarker('economisch', analysis.spectrum.economisch);
        // Sociaal-cultureel
        setSpectrumMarker('sociaal_cultureel', analysis.spectrum.sociaal_cultureel);
        // EU/Internationaal
        setSpectrumMarker('eu_internationaal', analysis.spectrum.eu_internationaal);
        // Klimaat
        setSpectrumMarker('klimaat', analysis.spectrum.klimaat);
        // Immigratie
        setSpectrumMarker('immigratie', analysis.spectrum.immigratie);
    }
    
    // === RETORISCHE ANALYSE ===
    if (analysis.retoriek) {
        setTextContent('retoriekToon', capitalizeFirst(analysis.retoriek.toon || '-'));
        setTextContent('retoriekFraming', capitalizeFirst(analysis.retoriek.framing || '-'));
        setTextContent('retoriekStijl', capitalizeFirst(analysis.retoriek.stijl || '-'));
        
        const objectiviteit = analysis.retoriek.objectiviteit || 0;
        setTextContent('retoriekObjectiviteitValue', objectiviteit + '%');
        const objBar = document.getElementById('retoriekObjectiviteitBar');
        if (objBar) {
            setTimeout(() => { objBar.style.width = objectiviteit + '%'; }, 100);
        }
        
        setTextContent('retoriekToelichting', analysis.retoriek.toelichting || '');
    }
    
    // === SCHRIJFSTIJL ===
    if (analysis.schrijfstijl) {
        const feitelijk = analysis.schrijfstijl.feitelijk_vs_mening || 50;
        setTextContent('schrijfstijlFeitelijkValue', feitelijk + '% mening');
        const feitelijkBar = document.getElementById('schrijfstijlFeitelijkBar');
        if (feitelijkBar) {
            setTimeout(() => { feitelijkBar.style.width = feitelijk + '%'; }, 100);
        }
        
        const emotie = analysis.schrijfstijl.emotionele_lading || 0;
        setTextContent('schrijfstijlEmotieValue', emotie + '%');
        const emotieBar = document.getElementById('schrijfstijlEmotieBar');
        if (emotieBar) {
            setTimeout(() => { emotieBar.style.width = emotie + '%'; }, 100);
        }
        
        setTextContent('schrijfstijlBronnen', capitalizeFirst(analysis.schrijfstijl.bronverwijzingen || '-'));
        setTextContent('schrijfstijlArgumentatie', capitalizeFirst(analysis.schrijfstijl.argumentatie_balans || '-'));
    }
    
    // === PARTIJ MATCHING ===
    if (analysis.partij_match) {
        setTextContent('partijBestMatch', analysis.partij_match.best_match || '-');
        
        // Partijen die zouden onderschrijven
        const onderschrijvenContainer = document.getElementById('partijOnderschrijven');
        if (onderschrijvenContainer && analysis.partij_match.zou_onderschrijven) {
            onderschrijvenContainer.innerHTML = '';
            analysis.partij_match.zou_onderschrijven.forEach(partij => {
                const badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-medium';
                badge.textContent = partij;
                onderschrijvenContainer.appendChild(badge);
            });
        }
        
        // Partijen die zouden afwijzen
        const afwijzenContainer = document.getElementById('partijAfwijzen');
        if (afwijzenContainer && analysis.partij_match.zou_afwijzen) {
            afwijzenContainer.innerHTML = '';
            analysis.partij_match.zou_afwijzen.forEach(partij => {
                const badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-medium';
                badge.textContent = partij;
                afwijzenContainer.appendChild(badge);
            });
        }
        
        setTextContent('partijToelichting', analysis.partij_match.toelichting || '');
    }
    
    // === DOELGROEP ===
    if (analysis.doelgroep) {
        setTextContent('doelgroepPrimair', analysis.doelgroep.primair || '-');
        setTextContent('doelgroepDemografisch', analysis.doelgroep.demografisch || '');
        setTextContent('doelgroepPolitiek', analysis.doelgroep.politiek_profiel || '');
    }
    
    // === KERNPUNTEN ===
    const kernpuntenList = document.getElementById('kernpuntenList');
    if (kernpuntenList && analysis.kernpunten) {
        kernpuntenList.innerHTML = '';
        analysis.kernpunten.forEach(punt => {
            const li = document.createElement('li');
            li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
            li.innerHTML = `
                <svg class="w-3 h-3 text-secondary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>${punt}</span>
            `;
            kernpuntenList.appendChild(li);
        });
    }
}

function setSpectrumMarker(spectrumKey, data) {
    if (!data) return;
    
    // Map spectrum keys to element IDs
    const idMappings = {
        'economisch': { marker: 'economischMarker', label: 'economischLabel', toelichting: 'economischToelichting' },
        'sociaal_cultureel': { marker: 'sociaal_cultureel_marker', label: 'sociaal_cultureel_label', toelichting: 'sociaal_cultureel_toelichting' },
        'eu_internationaal': { marker: 'eu_internationaal_marker', label: 'eu_internationaal_label', toelichting: 'eu_internationaal_toelichting' },
        'klimaat': { marker: 'klimaatMarker', label: 'klimaatLabel', toelichting: 'klimaatToelichting' },
        'immigratie': { marker: 'immigratieMarker', label: 'immigratieLabel', toelichting: 'immigratieToelichting' }
    };
    
    const ids = idMappings[spectrumKey];
    if (!ids) return;
    
    const marker = document.getElementById(ids.marker);
    const label = document.getElementById(ids.label);
    const toelichting = document.getElementById(ids.toelichting);
    
    if (marker && typeof data.score === 'number') {
        // Convert score (-100 to +100) to percentage (0 to 100)
        const percentage = ((data.score + 100) / 200) * 100;
        setTimeout(() => {
            marker.style.left = `calc(${percentage}% - 10px)`;
        }, 100);
    }
    
    if (label && data.label) {
        label.textContent = data.label;
    }
    
    if (toelichting && data.toelichting) {
        toelichting.textContent = data.toelichting;
    }
}

function setTextContent(elementId, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
    }
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
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
    console.log('Showing party results for:', partyKey, data);
    
    // Dynamisch geladen partij data uit database
    const partyData = <?php 
        $jsPartyData = [];
        foreach ($dbParties as $key => $party) {
            $jsPartyData[$key] = [
                'name' => $party['name'],
                'leader' => $party['leader'],
                'logo' => $party['logo'],
                'leaderPhoto' => $party['leader_photo'] ?? ''
            ];
        }
        echo json_encode($jsPartyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?>;
    
    const party = partyData[partyKey];
    if (!party) return;
    
    // Check if we have structured data or legacy plain text
    const analysis = data.analysis || data;
    const isStructured = analysis.reactie && analysis.analyse;
    
    // Show results container
    document.getElementById('partyResults')?.classList.remove('hidden');
    document.getElementById('backToPartySelection')?.classList.remove('hidden');
    
    // === LEIDER HERO CARD ===
    const photo = document.getElementById('partyResultPhoto');
    if (photo) {
        photo.src = party.leaderPhoto || party.logo;
        photo.alt = party.leader;
    }
    
    const logo = document.getElementById('partyResultLogo');
    if (logo) {
        logo.src = party.logo;
        logo.alt = partyKey;
    }
    
    const name = document.getElementById('partyResultName');
    if (name) {
        name.textContent = party.leader;
    }
    
    const leader = document.getElementById('partyResultLeader');
    if (leader) {
        leader.textContent = `Partijleider ${party.name}`;
    }
    
    if (isStructured) {
        // === REACTIE ===
        setTextContent('partyResultOpening', analysis.reactie?.opening || '');
        setTextContent('partyResultContent', analysis.reactie?.hoofdtekst || '');
        setTextContent('partyResultAfsluiting', analysis.reactie?.afsluiting || '');
        
        // === TOON ANALYSE ===
        setTextContent('partyToon', capitalizeFirst(analysis.analyse?.toon || '-'));
        setTextContent('partyEmotie', capitalizeFirst(analysis.analyse?.emotie || '-'));
        
        const sentiment = analysis.analyse?.sentiment || 0;
        setTextContent('partySentimentValue', (sentiment >= 0 ? '+' : '') + sentiment);
        const sentimentMarker = document.getElementById('partySentimentMarker');
        if (sentimentMarker) {
            const percentage = ((sentiment + 100) / 200) * 100;
            setTimeout(() => {
                sentimentMarker.style.left = `calc(${percentage}% - 6px)`;
            }, 100);
        }
        
        // === AUTHENTICITEIT ===
        const authenticiteit = analysis.meta?.authenticiteit_score || 0;
        setTextContent('partyAuthenticiteit', authenticiteit + '%');
        const authBar = document.getElementById('partyAuthenticiteitBar');
        if (authBar) {
            setTimeout(() => { authBar.style.width = authenticiteit + '%'; }, 100);
        }
        setTextContent('partyRetoriek', capitalizeFirst(analysis.meta?.retorische_stijl || '-'));
        
        // === STANDPUNTEN ===
        const kernpuntenList = document.getElementById('partyKernpunten');
        if (kernpuntenList && analysis.standpunten?.kernpunten) {
            kernpuntenList.innerHTML = '';
            analysis.standpunten.kernpunten.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
                li.innerHTML = `
                    <svg class="w-3 h-3 text-secondary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span>${punt}</span>
                `;
                kernpuntenList.appendChild(li);
            });
        }
        
        // Eens met artikel
        const eensList = document.getElementById('partyEens');
        if (eensList && analysis.standpunten?.eens_met_artikel) {
            eensList.innerHTML = '';
            analysis.standpunten.eens_met_artikel.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'text-xs text-green-800';
                li.textContent = punt;
                eensList.appendChild(li);
            });
        }
        
        // Oneens met artikel
        const oneensList = document.getElementById('partyOneens');
        if (oneensList && analysis.standpunten?.oneens_met_artikel) {
            oneensList.innerHTML = '';
            analysis.standpunten.oneens_met_artikel.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'text-xs text-red-800';
                li.textContent = punt;
                oneensList.appendChild(li);
            });
        }
        
        // === PARTIJ CONTEXT ===
        const beloftesList = document.getElementById('partyBeloftes');
        if (beloftesList && analysis.partij_context?.relevante_beloftes) {
            beloftesList.innerHTML = '';
            analysis.partij_context.relevante_beloftes.forEach(belofte => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
                li.innerHTML = `
                    <svg class="w-3 h-3 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>${belofte}</span>
                `;
                beloftesList.appendChild(li);
            });
        }
        
        setTextContent('partyOplossing', analysis.partij_context?.voorgestelde_oplossing || '');
        
    } else {
        // Fallback for legacy plain text response
        const content = data.content || '';
        setTextContent('partyResultOpening', '');
        setTextContent('partyResultContent', content);
        setTextContent('partyResultAfsluiting', '');
        
        // Hide structured elements
        ['partyToon', 'partyEmotie', 'partyAuthenticiteit', 'partyRetoriek'].forEach(id => {
            setTextContent(id, '-');
        });
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

// ============================================
// Instagram Story Generator Class
// ============================================
class InstagramStoryGenerator {
    constructor(options) {
        this.title = options.title || 'Blog';
        this.author = options.author || 'PolitiekPraat';
        this.date = options.date || '';
        this.readingTime = options.readingTime || '5 min';
        this.blogUrl = options.blogUrl || window.location.href;
        this.imageUrl = options.imageUrl || null;
        
        // Instagram Story dimensions (9:16 aspect ratio)
        this.width = 1080;
        this.height = 1920;
        
        // PolitiekPraat colors
        this.colors = {
            primaryDark: '#0f2a44',
            primary: '#1a365d',
            primaryLight: '#2d4a6b',
            secondary: '#c41e3a',
            secondaryLight: '#d63856',
            accent: '#F59E0B'
        };
    }
    
    async generateStory() {
        const canvas = document.createElement('canvas');
        canvas.width = this.width;
        canvas.height = this.height;
        const ctx = canvas.getContext('2d');
        
        // Draw background (with image if available)
        await this.drawBackground(ctx);
        
        // Draw content
        this.drawContent(ctx);
        
        // Draw branding
        this.drawBranding(ctx);
        
        return canvas;
    }
    
    async drawBackground(ctx) {
        // Try to load and draw the blog image first
        if (this.imageUrl) {
            try {
                const img = await this.loadImage(this.imageUrl);
                
                // Calculate cover dimensions (fill entire canvas)
                const imgRatio = img.width / img.height;
                const canvasRatio = this.width / this.height;
                
                let drawWidth, drawHeight, drawX, drawY;
                
                if (imgRatio > canvasRatio) {
                    // Image is wider - fit height, crop width
                    drawHeight = this.height;
                    drawWidth = img.width * (this.height / img.height);
                    drawX = (this.width - drawWidth) / 2;
                    drawY = 0;
                } else {
                    // Image is taller - fit width, crop height
                    drawWidth = this.width;
                    drawHeight = img.height * (this.width / img.width);
                    drawX = 0;
                    drawY = (this.height - drawHeight) / 2;
                }
                
                // Draw the image
                ctx.drawImage(img, drawX, drawY, drawWidth, drawHeight);
                
                // Add dark overlay gradient for text readability
                const overlay = ctx.createLinearGradient(0, 0, 0, this.height);
                overlay.addColorStop(0, 'rgba(15, 42, 68, 0.85)');
                overlay.addColorStop(0.3, 'rgba(15, 42, 68, 0.7)');
                overlay.addColorStop(0.5, 'rgba(15, 42, 68, 0.6)');
                overlay.addColorStop(0.7, 'rgba(15, 42, 68, 0.7)');
                overlay.addColorStop(1, 'rgba(15, 42, 68, 0.9)');
                ctx.fillStyle = overlay;
                ctx.fillRect(0, 0, this.width, this.height);
                
                // Add colored accent overlay
                const accentOverlay = ctx.createLinearGradient(0, this.height * 0.6, 0, this.height);
                accentOverlay.addColorStop(0, 'transparent');
                accentOverlay.addColorStop(1, 'rgba(196, 30, 58, 0.3)');
                ctx.fillStyle = accentOverlay;
                ctx.fillRect(0, 0, this.width, this.height);
                
            } catch (e) {
                console.log('Could not load blog image, using gradient background');
                this.drawGradientBackground(ctx);
            }
        } else {
            this.drawGradientBackground(ctx);
        }
        
        // Add subtle decorative elements
        this.drawDecorations(ctx);
    }
    
    loadImage(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Failed to load image'));
            img.src = url;
        });
    }
    
    drawGradientBackground(ctx) {
        // Create diagonal gradient
        const gradient = ctx.createLinearGradient(0, 0, this.width, this.height);
        gradient.addColorStop(0, this.colors.primaryDark);
        gradient.addColorStop(0.4, this.colors.primary);
        gradient.addColorStop(0.7, this.colors.primaryLight);
        gradient.addColorStop(1, this.colors.secondary);
        
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, this.width, this.height);
    }
    
    drawDecorations(ctx) {
        // Top ambient glow
        const topGlow = ctx.createRadialGradient(
            this.width * 0.3, 150, 0,
            this.width * 0.3, 150, 350
        );
        topGlow.addColorStop(0, 'rgba(196, 30, 58, 0.2)');
        topGlow.addColorStop(1, 'transparent');
        ctx.fillStyle = topGlow;
        ctx.fillRect(0, 0, this.width, 500);
        
        // Bottom ambient glow
        const bottomGlow = ctx.createRadialGradient(
            this.width * 0.7, this.height - 200, 0,
            this.width * 0.7, this.height - 200, 400
        );
        bottomGlow.addColorStop(0, 'rgba(26, 54, 93, 0.25)');
        bottomGlow.addColorStop(1, 'transparent');
        ctx.fillStyle = bottomGlow;
        ctx.fillRect(0, this.height - 600, this.width, 600);
        
        // Decorative circles (subtle)
        ctx.globalAlpha = 0.06;
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2;
        
        ctx.beginPath();
        ctx.arc(this.width - 80, 120, 150, 0, Math.PI * 2);
        ctx.stroke();
        
        ctx.beginPath();
        ctx.arc(80, this.height - 350, 100, 0, Math.PI * 2);
        ctx.stroke();
        
        ctx.globalAlpha = 1;
    }
    
    drawContent(ctx) {
        const centerX = this.width / 2;
        const padding = 60;
        const contentWidth = this.width - (padding * 2);
        
        // ===== TOP SECTION =====
        // "POLITIEK ARTIKEL" badge
        const badgeY = 180;
        ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
        this.roundRect(ctx, centerX - 130, badgeY - 22, 260, 44, 22);
        ctx.fill();
        
        ctx.font = 'bold 22px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.fillText('POLITIEK ARTIKEL', centerX, badgeY + 7);
        
        // ===== TITLE SECTION =====
        // Calculate title with proper sizing
        const titleFontSize = this.calculateTitleFontSize(ctx, this.title, contentWidth);
        const titleLines = this.wrapText(ctx, this.title, contentWidth - 40, titleFontSize);
        const lineHeight = titleFontSize * 1.2;
        const titleBlockHeight = (titleLines.length * lineHeight) + 60;
        
        // Position title card in upper-middle area
        const titleCardY = 280;
        
        // Title background card
        ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
        this.roundRect(ctx, padding, titleCardY, contentWidth, titleBlockHeight, 24);
        ctx.fill();
        
        // Border accent
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
        ctx.lineWidth = 2;
        this.roundRect(ctx, padding, titleCardY, contentWidth, titleBlockHeight, 24);
        ctx.stroke();
        
        // Draw title text
        ctx.font = `bold ${titleFontSize}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        
        const titleStartY = titleCardY + 30 + titleFontSize;
        titleLines.forEach((line, index) => {
            ctx.fillText(line, centerX, titleStartY + (index * lineHeight));
        });
        
        // ===== META INFO SECTION =====
        const metaY = titleCardY + titleBlockHeight + 50;
        
        // Author
        ctx.font = '600 32px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
        ctx.fillText(`Door ${this.author}`, centerX, metaY);
        
        // Date and reading time
        ctx.font = '400 26px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
        ctx.fillText(`${this.date}  |  ${this.readingTime} leestijd`, centerX, metaY + 45);
        
        // Decorative separator with diamond
        const sepY = metaY + 100;
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(centerX - 80, sepY);
        ctx.lineTo(centerX + 80, sepY);
        ctx.stroke();
        
        // Diamond accent
        ctx.fillStyle = this.colors.secondaryLight;
        ctx.save();
        ctx.translate(centerX, sepY);
        ctx.rotate(Math.PI / 4);
        ctx.fillRect(-7, -7, 14, 14);
        ctx.restore();
    }
    
    calculateTitleFontSize(ctx, text, maxWidth) {
        // Start with ideal size and reduce if needed
        const sizes = [56, 52, 48, 44, 40, 36];
        
        for (const size of sizes) {
            ctx.font = `bold ${size}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
            const lines = this.wrapText(ctx, text, maxWidth - 40, size);
            
            // Max 4 lines with reasonable total height
            if (lines.length <= 4) {
                return size;
            }
        }
        
        return 36; // Minimum size
    }
    
    drawBranding(ctx) {
        const centerX = this.width / 2;
        const brandingY = this.height - 320;
        const padding = 60;
        const contentWidth = this.width - (padding * 2);
        
        // CTA background card
        ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
        this.roundRect(ctx, padding + 20, brandingY - 30, contentWidth - 40, 170, 20);
        ctx.fill();
        
        // "Lees het volledige artikel op" text
        ctx.font = '400 28px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.textAlign = 'center';
        ctx.fillText('Lees het volledige artikel op', centerX, brandingY + 20);
        
        // PolitiekPraat.nl - prominent
        ctx.font = 'bold 48px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.fillText('POLITIEKPRAAT.NL', centerX, brandingY + 85);
        
        // Swipe up indicator
        const arrowY = this.height - 90;
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        
        // Arrow up
        ctx.beginPath();
        ctx.moveTo(centerX - 18, arrowY + 8);
        ctx.lineTo(centerX, arrowY - 8);
        ctx.lineTo(centerX + 18, arrowY + 8);
        ctx.stroke();
        
        // "Veeg omhoog" text
        ctx.font = '400 22px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.45)';
        ctx.fillText('Veeg omhoog voor meer', centerX, arrowY + 45);
    }
    
    wrapText(ctx, text, maxWidth, fontSize) {
        ctx.font = `bold ${fontSize}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
        const words = text.split(' ');
        const lines = [];
        let currentLine = '';
        
        for (const word of words) {
            const testLine = currentLine ? `${currentLine} ${word}` : word;
            const metrics = ctx.measureText(testLine);
            
            if (metrics.width > maxWidth && currentLine) {
                lines.push(currentLine);
                currentLine = word;
            } else {
                currentLine = testLine;
            }
        }
        
        if (currentLine) {
            lines.push(currentLine);
        }
        
        // Limit to 4 lines max, truncate if needed
        if (lines.length > 4) {
            lines.length = 4;
            const lastLine = lines[3];
            // Truncate last line properly
            while (ctx.measureText(lastLine + '...').width > maxWidth && lastLine.length > 0) {
                lines[3] = lastLine.substring(0, lastLine.length - 1);
            }
            lines[3] = lines[3].trim() + '...';
        }
        
        return lines;
    }
    
    roundRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }
    
    async toBlob() {
        const canvas = await this.generateStory();
        return new Promise((resolve) => {
            canvas.toBlob(resolve, 'image/png', 1.0);
        });
    }
    
    async toDataUrl() {
        const canvas = await this.generateStory();
        return canvas.toDataURL('image/png');
    }
}

// ============================================
// Instagram Story Share Functions
// ============================================
async function shareToInstagramStory() {
    const shareButton = document.getElementById('instagramStoryBtn');
    const originalContent = shareButton.innerHTML;
    
    // Show loading state
    shareButton.disabled = true;
    shareButton.innerHTML = `
        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex items-center gap-3">
            <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="hidden sm:block text-sm font-semibold">Genereren...</span>
        </div>
    `;
    
    try {
        // Get blog data from PHP
        const blogData = {
            title: <?php echo json_encode($blog->title); ?>,
            author: <?php echo json_encode($blog->author_name); ?>,
            date: <?php echo json_encode((new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($blog->published_at))); ?>,
            readingTime: document.getElementById('reading-minutes')?.textContent + ' min' || '5 min',
            blogUrl: window.location.href,
            imageUrl: <?php echo json_encode($blog->image_path ? getBlogImageUrl($blog->image_path) : null); ?>
        };
        
        // Generate the story image
        const generator = new InstagramStoryGenerator(blogData);
        const blob = await generator.toBlob();
        const file = new File([blob], 'politiekpraat-story.png', { type: 'image/png' });
        
        // Check if Web Share API with files is supported (mobile)
        if (navigator.canShare && navigator.canShare({ files: [file] })) {
            await navigator.share({
                files: [file],
                title: blogData.title,
                text: `Lees "${blogData.title}" op PolitiekPraat.nl`,
                url: blogData.blogUrl
            });
            showNotification('Story succesvol gedeeld!', 'success');
        } else {
            // Fallback: download the image
            downloadStoryImage(blob, blogData.title);
        }
    } catch (error) {
        if (error.name !== 'AbortError') {
            console.error('Error sharing story:', error);
            showNotification('Er ging iets mis bij het genereren van de story', 'error');
        }
    } finally {
        // Restore button state
        shareButton.disabled = false;
        shareButton.innerHTML = originalContent;
    }
}

function downloadStoryImage(blob, title) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `politiekpraat-${title.toLowerCase().replace(/[^a-z0-9]+/g, '-').substring(0, 50)}.png`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('Story afbeelding gedownload! Upload deze naar je Instagram Story.', 'success');
}

// Preview function for testing
async function previewInstagramStory() {
    const blogData = {
        title: <?php echo json_encode($blog->title); ?>,
        author: <?php echo json_encode($blog->author_name); ?>,
        date: <?php echo json_encode((new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($blog->published_at))); ?>,
        readingTime: document.getElementById('reading-minutes')?.textContent + ' min' || '5 min',
        blogUrl: window.location.href,
        imageUrl: <?php echo json_encode($blog->image_path ? getBlogImageUrl($blog->image_path) : null); ?>
    };
    
    const generator = new InstagramStoryGenerator(blogData);
    const dataUrl = await generator.toDataUrl();
    
    // Open preview in new window
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Story Preview - ${blogData.title}</title>
            <style>
                body { 
                    margin: 0; 
                    display: flex; 
                    justify-content: center; 
                    align-items: center; 
                    min-height: 100vh; 
                    background: #1a1a1a;
                }
                img { 
                    max-height: 90vh; 
                    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
                    border-radius: 20px;
                }
            </style>
        </head>
        <body>
            <img src="${dataUrl}" alt="Instagram Story Preview">
        </body>
        </html>
    `);
}

console.log('Blog view script fully loaded and initialized');
</script>