<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gray-50 min-h-screen py-12">
    <article class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
            <?php if ($blog->image_path): ?>
                <div class="relative h-96 w-full">
                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                         alt="<?php echo htmlspecialchars($blog->title); ?>"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?php echo htmlspecialchars($blog->title); ?></h1>
                        <div class="flex items-center text-white/80">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <?php echo htmlspecialchars($blog->author_name); ?>
                            </span>
                            <span class="mx-4">•</span>
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($blog->title); ?></h1>
                    <div class="flex items-center text-gray-600">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <?php echo htmlspecialchars($blog->author_name); ?>
                        </span>
                        <span class="mx-4">•</span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Content sectie aanpassen -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none">
                    <?php echo $blog->content; ?>
                </div>
            </div>

            <!-- Sociale Media Delen + Likes -->
            <div class="p-8 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-6 w-full sm:w-auto">
                        <!-- Like Button -->
                        <button id="likeButton" 
                                class="group flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-medium transition-all duration-300 bg-gray-50 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-slug="<?php echo $blog->slug; ?>"
                                aria-label="Like deze blog">
                            <div class="relative">
                                <!-- Hartje animatie container -->
                                <div class="relative">
                                    <svg class="w-6 h-6 transition-all duration-300" 
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
                                    <!-- Kleine hartjes animatie -->
                                    <div class="like-particles hidden">
                                        <i></i><i></i><i></i><i></i><i></i><i></i>
                                    </div>
                                </div>
                            </div>
                            <span id="likeCount" class="ml-2 font-semibold min-w-[1.5rem]"><?php echo $blog->likes; ?></span>
                        </button>

                        <!-- Share Button -->
                        <button onclick="shareBlog()" 
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-medium bg-gray-50 text-gray-600 hover:bg-gray-100 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span>Delen</span>
                        </button>
                    </div>

                    <!-- Back Button -->
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-medium bg-primary/5 text-primary hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
.like-particles {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.like-particles i {
    position: absolute;
    display: block;
    width: 5px;
    height: 5px;
    background: rgb(239, 68, 68); /* red-500 */
    border-radius: 50%;
    opacity: 0;
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
    0% {
        transform: scale(1);
    }
    25% {
        transform: scale(1.3);
    }
    50% {
        transform: scale(1);
    }
    75% {
        transform: scale(1.3);
    }
    100% {
        transform: scale(1);
    }
}

.liked .like-particles {
    display: block;
}

.liked .like-particles i {
    animation: particle-animation 0.8s ease-out forwards;
}

.liked .like-particles i:nth-child(1) { --tx: -15px; --ty: -15px; animation-delay: 0s; }
.liked .like-particles i:nth-child(2) { --tx: 15px; --ty: -15px; animation-delay: 0.1s; }
.liked .like-particles i:nth-child(3) { --tx: 15px; --ty: 0px; animation-delay: 0.15s; }
.liked .like-particles i:nth-child(4) { --tx: -15px; --ty: 0px; animation-delay: 0.2s; }
.liked .like-particles i:nth-child(5) { --tx: -15px; --ty: 15px; animation-delay: 0.25s; }
.liked .like-particles i:nth-child(6) { --tx: 15px; --ty: 15px; animation-delay: 0.3s; }

/* Voeg deze classes toe voor de like button states */
#likeButton.liked {
    @apply bg-red-50 text-red-500;
}

#likeButton svg {
    @apply transition-colors duration-300;
}

#likeButton.liked svg {
    @apply text-red-500;
    fill: currentColor;
}

#likeButton:hover:not(.liked) svg {
    @apply text-red-400;
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