<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="min-h-screen py-8 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decoratieve elementen -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-secondary/10 to-transparent"></div>
    <div class="absolute top-20 right-0 w-64 h-64 bg-primary/5 rounded-full filter blur-3xl opacity-70"></div>
    
    <div class="container mx-auto px-4 sm:px-6">
        <div class="max-w-xl mx-auto">
            <!-- Header Sectie -->
            <div class="text-center mb-8" data-aos="fade-down">
                <div class="inline-block mb-3 bg-primary/10 text-primary px-4 py-1 rounded-full text-sm font-semibold">
                    Statistieken Beheren
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 relative inline-block">
                    <span class="relative z-10">Likes Aanpassen</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-1"></div>
                </h1>
                <p class="text-base text-gray-600 max-w-md mx-auto">
                    Pas het aantal likes aan voor jouw blog
                </p>
            </div>

            <!-- Blog Informatie -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6" data-aos="fade-up">
                <!-- Decoratieve header -->
                <div class="bg-gradient-to-r from-primary to-secondary h-2"></div>
                
                <div class="p-4 sm:p-6">
                    <div class="flex items-start">
                        <?php if($blog->image_path): ?>
                            <div class="flex-shrink-0 h-16 w-16 sm:h-20 sm:w-20 mr-3 sm:mr-4">
                                <img class="h-16 w-16 sm:h-20 sm:w-20 rounded-lg object-cover shadow-md border border-gray-100" 
                                     src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
                                     alt="<?php echo htmlspecialchars($blog->title); ?>">
                            </div>
                        <?php else: ?>
                            <div class="flex-shrink-0 h-16 w-16 sm:h-20 sm:w-20 mr-3 sm:mr-4 bg-gray-100 rounded-lg flex items-center justify-center shadow-inner">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <div class="text-lg sm:text-xl font-semibold text-gray-900 mb-1">
                                <?php echo htmlspecialchars($blog->title); ?>
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 mb-2">
                                Gepubliceerd op <?php echo date('d-m-Y', strtotime($blog->published_at)); ?>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <?php echo $blog->likes; ?> likes
                                </span>
                            </div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->id; ?>" 
                           class="ml-2 sm:ml-4 flex-shrink-0 p-2 text-gray-500 hover:text-primary transition-colors rounded-full hover:bg-gray-100" 
                           title="Bekijk blog">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistieken Panel -->
            <div class="bg-white p-4 rounded-xl shadow-md border border-gray-100 mb-6" data-aos="fade-up" data-aos-delay="150">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-medium text-gray-600">Huidige Likes</div>
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900"><?php echo $blog->likes; ?></div>
            </div>

            <!-- Formulier -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-gradient-to-r from-primary to-secondary p-1">
                    <div class="bg-white rounded-t-xl p-4">
                        <h2 class="text-lg font-semibold text-gray-900">Likes Aanpassen</h2>
                        <p class="text-sm text-gray-500">Pas het aantal likes aan voor deze blog</p>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <form action="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>" method="POST" class="space-y-5">
                        <!-- Likes Input -->
                        <div class="relative">
                            <label for="likes" class="block text-sm font-medium text-gray-700 mb-2">Aantal Likes</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <input type="number" 
                                        name="likes" 
                                        id="likes" 
                                        min="0" 
                                        class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                        value="<?php echo $blog->likes; ?>">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <div class="text-sm text-gray-400">likes</div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                Vul het gewenste aantal likes in. Het aantal kan niet negatief zijn.
                            </p>
                        </div>
                        
                        <!-- Preview van statistieken -->
                        <div class="mt-4 p-3 sm:p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <h3 class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Voorbeeld van statistieken</h3>
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">
                                        <span id="preview-likes"><?php echo $blog->likes; ?></span> likes
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4 sm:justify-end pt-2">
                            <a href="<?php echo URLROOT; ?>/blogs/manage" 
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary w-full sm:w-auto">
                                <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Annuleren
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary w-full sm:w-auto">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Likes Bijwerken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll) - if your website uses it
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            offset: 80,
            once: true
        });
    }
    
    // Live preview van de statistieken
    const likesInput = document.getElementById('likes');
    const likesPreview = document.getElementById('preview-likes');
    
    if (likesInput && likesPreview) {
        likesInput.addEventListener('input', function() {
            likesPreview.textContent = this.value;
        });
    }
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 