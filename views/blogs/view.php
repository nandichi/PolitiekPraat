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

            <!-- Blog Content met Markdown -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none">
                    <?php 
                    // Converteer de Markdown naar HTML en geef het weer
                    echo $blog->content; 
                    ?>
                </div>
            </div>

            <!-- Sociale Media Delen -->
            <div class="p-8 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <button onclick="shareBlog()" class="flex items-center text-gray-600 hover:text-primary transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        <span>Delen</span>
                    </button>
                    <a href="<?php echo URLROOT; ?>/blogs" class="flex items-center text-gray-600 hover:text-primary transition-colors">
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

<?php require_once 'views/templates/footer.php'; ?> 