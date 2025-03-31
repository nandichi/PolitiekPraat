<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <!-- Hero Section met cleaner design -->
    <section class="relative bg-gradient-to-r from-indigo-600 via-blue-700 to-blue-800 py-20 overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <svg class="absolute left-0 top-0 h-full w-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgba(255, 255, 255, 0.05)" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <div class="absolute right-0 -bottom-20 opacity-20">
                <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.1)" d="M47.1,-68.1C59.4,-62.5,67.2,-46.6,71.2,-30.9C75.1,-15.2,75.2,0.4,72.6,16.3C70,32.2,64.8,48.5,53.5,58.1C42.3,67.7,25.2,70.5,8.5,72.2C-8.2,73.9,-24.6,74.5,-39.8,69.2C-55,63.9,-69,52.6,-76.6,37.5C-84.3,22.4,-85.7,3.4,-81.4,-13.7C-77.2,-30.7,-67.4,-45.8,-54.4,-52.4C-41.4,-59,-25.9,-57.1,-10.3,-58.7C5.3,-60.2,34.8,-73.7,47.1,-68.1Z" transform="translate(100 100)" />
                </svg>
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-tight leading-tight">
                    Politieke Blogs
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Ontdek diepgaande analyses en persoonlijke inzichten over de Nederlandse politiek
                </p>
                
                <!-- Call to action buttons -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="px-6 py-3 bg-white text-blue-700 font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Nieuwe Blog Schrijven</span>
                        </a>
                    <?php endif; ?>
                    <a href="#blogs" class="px-6 py-3 bg-blue-900 bg-opacity-50 text-white font-medium rounded-lg shadow-lg hover:bg-opacity-70 transition-all duration-200 backdrop-blur-sm flex items-center">
                        <span>Verken blogs</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                </div>
                
                <?php if (!empty($blogs)): ?>
                <!-- Blog statistieken in modern cards layout -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo count($blogs); ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Blogs</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2"><?php echo count(array_unique(array_column((array) $blogs, 'author_id'))); ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Auteurs</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-lg">
                        <?php 
                            $total_reading_time = array_sum(array_column((array) $blogs, 'reading_time')); 
                        ?>
                        <div class="text-4xl font-bold text-white mb-2"><?php echo $total_reading_time; ?></div>
                        <div class="text-sm text-blue-100 font-medium uppercase tracking-wide">Min. Leestijd</div>
                    </div>
                </div>
                <?php endif; ?>
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
                                    <?php if (!empty($blog->profile_photo)): ?>
                                        <img src="<?php echo URLROOT . '/' . $blog->profile_photo; ?>" 
                                             alt="<?php echo htmlspecialchars($blog->author_name); ?>"
                                             class="w-8 h-8 rounded-full object-cover mr-2">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-2">
                                            <?php echo substr($blog->author_name, 0, 1); ?>
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