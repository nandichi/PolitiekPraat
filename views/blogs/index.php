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
                    <article class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-gray-100 h-full">
                        <!-- Decoratieve hover accent lijn -->
                        <div class="absolute inset-0 top-auto h-1 bg-gradient-to-r from-primary to-secondary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 z-10"></div>
                        
                        <?php 
                        // Check of de blog minder dan 12 uur oud is
                        $published_time = strtotime($blog->published_at);
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

                        <a href="<?php echo URLROOT . '/blogs/view/' . $blog->slug; ?>" class="block relative">
                            <?php if ($blog->image_path): ?>
                                <div class="relative h-52 overflow-hidden">
                                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($blog->title); ?>"
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

                            <div class="p-7">
                                <!-- Auteur en datum info met verbeterd design -->
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center overflow-hidden">
                                            <img src="<?php echo URLROOT; ?>/public/images/naoufal-foto.jpg" 
                                                 onerror="if(this.src !== '<?php echo URLROOT; ?>/images/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/images/naoufal-foto.jpg'; else if(this.src !== '<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg') this.src='<?php echo URLROOT; ?>/public/images/profiles/naoufal-foto.jpg';"
                                                 alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($blog->author_name); ?></span>
                                    </div>
                                    
                                    <div class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                                    </div>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                    <?php echo htmlspecialchars($blog->title); ?>
                                </h2>

                                <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                    <?php echo htmlspecialchars($blog->summary); ?>
                                </p>

                                <!-- Actieknop met verbeterd ontwerp -->
                                <div class="flex items-center justify-between pt-2">
                                    <div class="inline-flex items-center px-4 py-2 bg-primary/5 hover:bg-primary/10 text-primary font-semibold rounded-lg transition-colors duration-300">
                                        <span>Lees meer</span>
                                        <svg class="w-4 h-4 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Likes indicator -->
                                    <div class="flex items-center text-gray-400 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span>
                                            <?php 
                                                // Toon het aantal likes uit de database
                                                echo (isset($blog->likes) && $blog->likes > 0) ? $blog->likes : '0';
                                                echo ' likes';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Decoratieve hoekelementen -->
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-primary/5 to-secondary/5 transform rotate-45 translate-x-10 -translate-y-10 group-hover:translate-x-6 group-hover:-translate-y-6 transition-transform duration-700"></div>
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