<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <!-- Hero Section - Rebuilt to be elegant and professional -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-blue-600 py-20 overflow-hidden">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary to-blue-400"></div>
        
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Abstract wave pattern -->
            <svg class="absolute w-full h-56 -bottom-10 left-0 text-white/5" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path fill="currentColor" fill-opacity="1" d="M0,128L40,138.7C80,149,160,171,240,170.7C320,171,400,149,480,149.3C560,149,640,171,720,192C800,213,880,235,960,229.3C1040,224,1120,192,1200,165.3C1280,139,1360,117,1400,106.7L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
            </svg>
            
            <!-- Decorative circles -->
            <div class="absolute top-20 left-10 w-40 h-40 rounded-full bg-secondary/10 filter blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-blue-500/10 filter blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-20 h-20 rounded-full bg-secondary/20 filter blur-xl"></div>
            
            <!-- Dot pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-30"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Small decorative element above title -->
                <div class="inline-block mb-3">
                    <div class="flex items-center justify-center space-x-1">
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        <span class="block w-3 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    </div>
                </div>
                
                <!-- Title with gradient text -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-100 via-white to-secondary-light">
                        Politieke Blogs
                    </span>
                </h1>
                
                <!-- Subtitle with lighter weight -->
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed font-light">
                    Ontdek diepgaande analyses en persoonlijke inzichten over de Nederlandse politiek
                </p>
                
                <!-- Call to action button -->
                <div class="flex justify-center mb-6">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="px-6 py-3.5 bg-gradient-to-r from-secondary to-secondary-dark text-white font-medium rounded-lg shadow-lg hover:shadow-secondary/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Nieuwe Blog Schrijven</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts Grid -->
    <section class="py-12 relative z-10" id="blogs">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($blogs as $blog): ?>
                    <article class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <?php if ($blog->image_path): ?>
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                     alt="<?php echo htmlspecialchars($blog->title); ?>"
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        <?php endif; ?>

                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span class="flex items-center">
                                    <?php
                                    $profilePhoto = getProfilePhotoUrl($blog->profile_photo, $blog->author_name);
                                    if ($profilePhoto['type'] === 'img'): 
                                    ?>
                                        <img src="<?php echo $profilePhoto['value']; ?>" 
                                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                             class="w-8 h-8 rounded-full object-cover mr-2">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-2">
                                            <?php echo $profilePhoto['value']; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($blog->author_name); ?>
                                </span>
                                <span class="mx-3">•</span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                                </span>
                                <span class="mx-3">•</span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo $blog->reading_time; ?> min leestijd
                                </span>
                            </div>

                            <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">
                                <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>">
                                    <?php echo htmlspecialchars($blog->title); ?>
                                </a>
                            </h2>

                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo $blog->summary; ?>
                            </p>

                            <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>" 
                               class="inline-flex items-center text-primary font-medium group-hover:text-secondary transition-colors">
                                <span>Lees meer</span>
                                <svg class="w-5 h-5 ml-2 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if (empty($blogs)): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Nog geen blogs</h3>
                    <p class="text-gray-600">Wees de eerste die een blog schrijft!</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="inline-flex items-center px-6 py-3 mt-4 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nieuwe Blog Schrijven
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Scripts voor Markdown parsing -->
<script src="https://cdn.jsdelivr.net/npm/marked@4.3.0/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.3/dist/purify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configureer marked
    marked.use({ 
        breaks: true,
        gfm: true,
        headerIds: false
    });

    // Functie om markdown te parsen en te strippen van HTML tags
    function parseAndStripMarkdown(markdown, length = 150) {
        try {
            // Parse markdown naar HTML
            const html = marked.parse(markdown);
            // Sanitize de HTML
            const cleanHtml = DOMPurify.sanitize(html);
            // Strip HTML tags en limiteer lengte
            const text = cleanHtml.replace(/<[^>]*>/g, '');
            return text.length > length ? text.substring(0, length) + '...' : text;
        } catch (error) {
            console.error('Markdown parsing error:', error);
            return markdown;
        }
    }

    // Pas toe op alle blog samenvattingen
    document.querySelectorAll('.blog-summary').forEach(summary => {
        const markdown = summary.textContent;
        summary.textContent = parseAndStripMarkdown(markdown);
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 