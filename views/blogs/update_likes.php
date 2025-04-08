<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="min-h-screen py-12 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decoratieve elementen -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-secondary/10 to-transparent"></div>
    
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header Sectie -->
            <div class="text-center mb-12" data-aos="fade-down">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 relative inline-block">
                    <span class="relative z-10">Likes Aanpassen</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-1"></div>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pas het aantal likes aan voor jouw blog
                </p>
            </div>

            <!-- Blog Informatie -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8" data-aos="fade-up">
                <!-- Decoratieve header -->
                <div class="bg-gradient-to-r from-primary to-secondary h-2"></div>
                
                <div class="p-6">
                    <div class="flex items-center">
                        <?php if($blog->image_path): ?>
                            <div class="flex-shrink-0 h-16 w-16 mr-4">
                                <img class="h-16 w-16 rounded-lg object-cover" 
                                     src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                     alt="<?php echo htmlspecialchars($blog->title); ?>">
                            </div>
                        <?php else: ?>
                            <div class="flex-shrink-0 h-16 w-16 mr-4 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="ml-1">
                            <div class="text-xl font-semibold text-gray-900">
                                <?php echo htmlspecialchars($blog->title); ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                Gepubliceerd op <?php echo date('d-m-Y', strtotime($blog->published_at)); ?> om <?php echo date('H:i', strtotime($blog->published_at)); ?> uur
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulier -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="p-8">
                    <form action="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>" method="POST" class="space-y-6">
                        <div>
                            <label for="likes" class="block text-sm font-medium text-gray-700 mb-2">Aantal Likes</label>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/10">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="number" 
                                       name="likes" 
                                       id="likes" 
                                       min="0" 
                                       class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md py-3"
                                       value="<?php echo $blog->likes; ?>">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Vul het gewenste aantal likes in. Het aantal kan niet negatief zijn.
                            </p>
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <a href="<?php echo URLROOT; ?>/blogs/manage" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Annuleren
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
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
            offset: 100,
            once: true
        });
    }
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 