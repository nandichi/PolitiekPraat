<?php require_once 'views/templates/header.php'; ?>

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
                        <div class="flex items-center text-white/90 text-sm sm:text-base">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <?php echo htmlspecialchars($blog->author_name); ?>
                            </span>
                            <span class="mx-2 sm:mx-4">•</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-4 sm:p-6 md:p-8">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-3 leading-tight"><?php echo htmlspecialchars($blog->title); ?></h1>
                    <div class="flex items-center text-gray-600 text-sm sm:text-base">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <?php echo htmlspecialchars($blog->author_name); ?>
                        </span>
                        <span class="mx-2 sm:mx-4">•</span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Content sectie aanpassen -->
            <div class="p-4 sm:p-6 md:p-8">
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
        </div>
    </article>
</main>

<!-- Share Script -->
<script>
async function shareBlog() {
    const title = '<?php echo htmlspecialchars($blog->title); ?>';
    const url = window.location.href;
    const text = 'Lees deze interessante blog op PolitiekPraat: ';

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