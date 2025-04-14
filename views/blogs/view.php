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

<main class="bg-gray-50 min-h-screen py-6 sm:py-12">
    <article class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg sm:rounded-2xl shadow-md sm:shadow-lg overflow-hidden">
            <?php if ($blog->image_path): ?>
                <div class="relative h-64 sm:h-72 md:h-96 w-full">
                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                         alt="<?php echo htmlspecialchars($blog->title); ?>"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 md:p-8">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 leading-tight"><?php echo htmlspecialchars($blog->title); ?></h1>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 text-white/90 text-sm sm:text-base">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden bg-white/10 backdrop-blur-sm mr-3">
                                    <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                                         alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="font-medium"><?php echo htmlspecialchars($blog->author_name); ?></span>
                            </div>
                            
                            <div class="flex items-center gap-4 sm:gap-6">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                                </span>
                                
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span id="headerLikeCount"><?php echo $blog->likes; ?> likes</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-4 sm:p-6 md:p-8">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-3 leading-tight"><?php echo htmlspecialchars($blog->title); ?></h1>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 text-gray-600 text-sm sm:text-base">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden mr-3 bg-gradient-to-br from-primary/20 to-secondary/20">
                                <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                                     alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                     class="w-full h-full object-cover">
                            </div>
                            <span class="font-bold"><?php echo htmlspecialchars($blog->author_name); ?></span>
                        </div>
                        
                        <div class="flex items-center gap-4 sm:gap-6">
                            <span class="flex items-center bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                            </span>
                            
                            <span class="flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span id="headerLikeCount"><?php echo $blog->likes; ?> likes</span>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Content sectie aanpassen -->
            <div class="p-4 sm:p-6 md:p-8">
                <?php if ($blog->video_path || $blog->video_url): ?>
                    <div class="mb-8">
                        <?php if ($blog->video_path): ?>
                            <!-- Lokaal geüploade video -->
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-black">
                                <video controls class="w-full h-full">
                                    <source src="<?php echo URLROOT . '/' . $blog->video_path; ?>" type="video/mp4">
                                    Je browser ondersteunt geen video weergave.
                                </video>
                            </div>
                        <?php elseif ($blog->video_url): ?>
                            <!-- Embedded video (YouTube/Vimeo) -->
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-black">
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

                <div class="prose prose-sm sm:prose lg:prose-lg max-w-none">
                    <?php echo $blog->content; ?>
                </div>
            </div>

            <!-- Sociale Media Delen + Likes -->
            <div class="p-4 sm:p-6 md:p-8 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <!-- Like Button -->
                        <button id="likeButton" 
                                class="group flex-1 sm:flex-initial inline-flex items-center justify-center px-4 sm:px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-300 bg-gray-50 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-slug="<?php echo $blog->slug; ?>"
                                aria-label="Like deze blog">
                            <div class="relative">
                                <div class="relative">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-all duration-300" 
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path class="transform origin-center transition-all duration-300" 
                                              stroke-linecap="round" 
                                              stroke-linejoin="round" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <div class="like-particles hidden">
                                        <i></i><i></i><i></i><i></i><i></i><i></i>
                                    </div>
                                </div>
                            </div>
                            <span id="likeCount" class="ml-2 font-semibold min-w-[1.5rem]"><?php echo $blog->likes; ?></span>
                        </button>

                        <!-- Share Button -->
                        <button onclick="shareBlog()" 
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 sm:px-6 py-2.5 rounded-lg text-sm font-medium bg-gray-50 text-gray-600 hover:bg-gray-100 transition-all duration-300">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span>Delen</span>
                        </button>
                    </div>

                    <!-- Back Button -->
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 sm:px-6 py-2.5 rounded-lg text-sm font-medium bg-primary/5 text-primary hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Terug naar blogs</span>
                    </a>
                </div>
            </div>

            <!-- Auteur informatie toevoegen -->
            <div class="p-4 sm:p-6 md:p-8 bg-gray-50 border-t border-gray-100">
                <div class="flex items-start sm:items-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center overflow-hidden mr-4 bg-gradient-to-br from-primary/20 to-secondary/20">
                        <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($blog->author_name); ?></h4>
                        <p class="text-gray-600 text-sm mb-2">Politiek Analist</p>
                        <p class="text-gray-500 text-sm">
                            Deelt inzichten over politieke ontwikkelingen in Nederland en Europa. Volg voor meer analyses en updates over actuele politieke thema's.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gerelateerde Blogs Carousel -->
        <div class="max-w-4xl mx-auto mt-16 mb-8">
            <div class="flex items-center justify-between mb-12 px-4">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-primary mb-2">Blijf op de hoogte</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 relative">
                        Ontdek meer interessante blogs
                        <span class="absolute -bottom-3 left-0 w-20 h-1 bg-primary rounded-full"></span>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <button class="swiper-button-prev-custom p-2.5 rounded-full bg-white shadow-md hover:shadow-lg transition-all duration-300 text-primary hover:text-primary-dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="swiper-button-next-custom p-2.5 rounded-full bg-white shadow-md hover:shadow-lg transition-all duration-300 text-primary hover:text-primary-dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Swiper Container -->
            <div class="swiper blogsSwiper">
                <div class="swiper-wrapper">
                    <?php 
                    // Haal andere blogs op (maximaal 10)
                    $otherBlogs = (new BlogController())->getAll(10);
                    foreach ($otherBlogs as $relatedBlog): 
                        if ($relatedBlog->slug !== $blog->slug): // Skip huidige blog
                    ?>
                        <div class="swiper-slide px-2 pb-8">
                            <article class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-gray-100 h-full">
                                <!-- Decoratieve hover accent lijn -->
                                <div class="absolute inset-0 top-auto h-1 bg-gradient-to-r from-primary to-secondary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 z-10"></div>
                                
                                <?php 
                                // Check of de blog minder dan 12 uur oud is
                                $published_time = strtotime($relatedBlog->published_at);
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

                                <a href="<?php echo URLROOT . '/blogs/view/' . $relatedBlog->slug; ?>" class="block relative">
                                    <?php if ($relatedBlog->image_path): ?>
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="<?php echo URLROOT . '/' . $relatedBlog->image_path; ?>" 
                                             alt="<?php echo htmlspecialchars($relatedBlog->title); ?>"
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

                                    <div class="p-6">
                                        <!-- Auteur en datum info met verbeterd design -->
                                        <div class="flex items-center justify-between mb-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center overflow-hidden">
                                                    <img src="https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM" 
                                                         alt="<?php echo htmlspecialchars($relatedBlog->author_name); ?>"
                                                         class="w-full h-full object-cover">
                                                </div>
                                                <span class="text-xs font-bold text-gray-800"><?php echo htmlspecialchars($relatedBlog->author_name); ?></span>
                                            </div>
                                            
                                            <div class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-medium flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <?php echo date('d M Y', strtotime($relatedBlog->published_at)); ?>
                                            </div>
                                        </div>

                                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                            <?php echo htmlspecialchars($relatedBlog->title); ?>
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                                            <?php echo htmlspecialchars($relatedBlog->summary); ?>
                                        </p>
                                        
                                        <div class="flex items-center justify-between pt-2">
                                            <div class="inline-flex items-center text-primary font-medium text-sm">
                                                <span>Lees meer</span>
                                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </div>
                                            
                                            <!-- Like indicator -->
                                            <div class="flex items-center text-gray-400 text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span><?php echo (isset($relatedBlog->likes) && $relatedBlog->likes > 0) ? $relatedBlog->likes : '0'; ?> likes</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Decoratieve hoekelementen -->
                                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-8 -translate-y-8 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700"></div>
                            </article>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
        </div>
    </article>
</main>

<?php if (isAdmin()): ?>
<div class="fixed bottom-4 right-4 opacity-70 hover:opacity-100 z-50">
    <a href="?debug_photo=1" class="text-xs bg-gray-800 text-white py-1 px-2 rounded hover:bg-gray-700">
        Debug Photo
    </a>
</div>
<?php endif; ?>

<!-- Swiper JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Swiper Initialisatie -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.blogsSwiper', {
        slidesPerView: 1.2,
        centeredSlides: true,
        spaceBetween: 16,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                centeredSlides: false,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                centeredSlides: false,
                spaceBetween: 24,
            },
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
});
</script>

<!-- Share Script -->
<script>
async function shareBlog() {
    const title = '<?php echo htmlspecialchars($blog->title); ?>';
    const url = window.location.href;
    const text = 'Lees deze interessante blog op PolitiekPraat: <?php echo htmlspecialchars($blog->summary); ?>';

    if (navigator.share) {
        try {
            await navigator.share({
                title: title,
                text: text,
                url: url
            });
        } catch (err) {
            console.log('Error bij delen:', err);
        }
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link gekopieerd naar klembord!');
        }).catch(err => {
            console.error('Kon link niet kopiëren:', err);
        });
    }
}
</script>

<!-- Update de CSS voor de like animaties -->
<style>
/* Update bestaande stijlen */
.prose {
    @apply text-gray-800;
}

.prose h2 {
    @apply text-xl sm:text-2xl font-semibold mt-8 mb-4;
}

.prose h3 {
    @apply text-lg sm:text-xl font-semibold mt-6 mb-3;
}

.prose p {
    @apply text-base sm:text-lg leading-relaxed mb-4;
}

.prose ul, .prose ol {
    @apply my-4 ml-4 space-y-2;
}

.prose li {
    @apply text-base sm:text-lg leading-relaxed;
}

.prose img {
    @apply rounded-lg my-6 w-full;
}

.prose blockquote {
    @apply border-l-4 border-gray-200 pl-4 italic my-6;
}

.prose a {
    @apply text-primary hover:text-primary-dark underline transition-colors duration-200;
}

.prose code {
    @apply bg-gray-100 rounded px-1.5 py-0.5 text-sm font-mono;
}

.prose pre {
    @apply bg-gray-900 text-gray-100 rounded-lg p-4 overflow-x-auto my-6;
}

.prose pre code {
    @apply bg-transparent text-inherit p-0;
}

/* Like animatie stijlen behouden en verbeteren */
.like-particles {
    @apply absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full pointer-events-none;
}

.like-particles i {
    @apply absolute block w-1 h-1 bg-red-500 rounded-full opacity-0;
}

@keyframes particle-animation {
    0% {
        transform: translate(0, 0) scale(1);
        opacity: 0;
    }
    25% {
        opacity: 1;
    }
    100% {
        transform: translate(var(--tx), var(--ty)) scale(0);
        opacity: 0;
    }
}

@keyframes heart-beat {
    0% { transform: scale(1); }
    25% { transform: scale(1.2); }
    50% { transform: scale(1); }
    75% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.liked .like-particles {
    @apply block;
}

.liked .like-particles i {
    animation: particle-animation 0.8s ease-out forwards;
}

#likeButton.liked {
    @apply bg-red-50 text-red-500 border-red-200;
}

#likeButton svg {
    @apply transition-all duration-300;
}

#likeButton.liked svg {
    @apply text-red-500 scale-110;
    fill: currentColor;
}

#likeButton:hover:not(.liked) svg {
    @apply text-red-400 scale-105;
}

/* Verbeterde button hover states */
button, a {
    @apply transform transition-all duration-200 hover:shadow-md active:scale-95;
}

/* Verbeterde focus states voor toegankelijkheid */
button:focus, a:focus {
    @apply outline-none ring-2 ring-offset-2 ring-primary/50;
}

/* Swiper styling */
.blogsSwiper {
    @apply pb-8;
    margin: 0 -1rem;
}

@media (min-width: 640px) {
    .blogsSwiper {
        margin: 0;
    }
}

.swiper-slide {
    height: auto !important;
    @apply transition-all duration-300;
}

.swiper-slide:not(.swiper-slide-active) {
    @apply opacity-60;
}

@media (min-width: 640px) {
    .swiper-slide:not(.swiper-slide-active) {
        @apply opacity-100;
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Verbeterde hover effecten voor kaarten */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

/* Verbeterde schaduw effecten */
.shadow-sm {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* Verbeterde navigatie knoppen */
.swiper-button-prev-custom,
.swiper-button-next-custom {
    @apply transition-all duration-300 ease-out shadow-[0_2px_8px_rgba(0,0,0,0.08)];
}

.swiper-button-prev-custom:hover,
.swiper-button-next-custom:hover {
    @apply transform scale-110 bg-primary text-white shadow-[0_4px_12px_rgba(0,0,0,0.12)];
}

.swiper-button-prev-custom:active,
.swiper-button-next-custom:active {
    @apply transform scale-95;
}

/* Sectie titel styling */
h2.relative span.absolute {
    @apply transition-all duration-300;
}

h2.relative:hover span.absolute {
    @apply w-32;
}

@media (prefers-reduced-motion: reduce) {
    h2.relative:hover span.absolute {
        @apply w-20;
    }
}
</style>

<!-- Update het JavaScript voor betere animaties -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButton = document.getElementById('likeButton');
    const likeCount = document.getElementById('likeCount');
    const slug = likeButton.dataset.slug;
    let isProcessing = false;
    
    const likedBlogs = JSON.parse(localStorage.getItem('likedBlogs') || '{}');
    
    // Update initiële status
    updateLikeButtonState(likedBlogs[slug]);
    
    function updateLikeButtonState(isLiked) {
        if (isLiked) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }
    }
    
    function animateLike() {
        likeButton.classList.add('liked');
        const heart = likeButton.querySelector('svg');
        heart.style.animation = 'heart-beat 0.8s ease-in-out';
        setTimeout(() => {
            heart.style.animation = '';
        }, 800);
    }
    
    likeButton.addEventListener('click', async function() {
        if (isProcessing) return;
        
        const action = likedBlogs[slug] ? 'unlike' : 'like';
        isProcessing = true;
        likeButton.disabled = true;
        
        if (action === 'like') {
            animateLike();
        }
        
        try {
            const response = await fetch(`<?php echo URLROOT; ?>/blogs/like/${slug}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action })
            });
            
            const data = await response.json();
            
            if (data.success) {
                likeCount.textContent = data.likes;
                
                if (action === 'like') {
                    likedBlogs[slug] = true;
                } else {
                    delete likedBlogs[slug];
                }
                
                updateLikeButtonState(likedBlogs[slug]);
                localStorage.setItem('likedBlogs', JSON.stringify(likedBlogs));
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            isProcessing = false;
            likeButton.disabled = false;
        }
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 