<?php require_once 'views/templates/header.php'; ?>

<main class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-primary overflow-hidden py-20">
        <!-- Decoratieve elementen -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Politieke Blogs</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Ontdek diepgaande analyses en persoonlijke inzichten over de Nederlandse politiek
                </p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mt-8">
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="inline-flex items-center px-6 py-3 bg-white text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nieuwe Blog Schrijven
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Blog Posts Grid -->
    <section class="py-12">
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
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php echo htmlspecialchars($blog->author_name); ?>
                                </span>
                                <span class="mx-3">•</span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo date('d M Y', strtotime($blog->published_at)); ?>
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