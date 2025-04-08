<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<main class="min-h-screen py-12 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decorative elements -->
    
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 relative inline-block">
                    <span class="relative z-10">Blog Bewerken</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-1"></div>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pas je blog aan met de markdown editor
                </p>
            </div>

            <!-- Main form -->
            <form action="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>" method="POST" enctype="multipart/form-data" 
                  class="bg-white rounded-2xl shadow-xl overflow-hidden">
                
                <!-- Decorative header -->
                <div class="bg-gradient-to-r from-primary to-secondary h-2"></div>

                <div class="p-8">
                    <!-- Title Section -->
                    <div class="mb-8">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titel van je blog
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-lg font-medium"
                               required
                               value="<?php echo htmlspecialchars($blog->title); ?>">
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Header afbeelding
                        </label>
                        
                        <?php if(!empty($blog->image_path)): ?>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 mb-2">Huidige afbeelding:</p>
                                <div class="relative rounded-xl overflow-hidden">
                                    <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($blog->title); ?>"
                                         class="w-full h-48 object-cover">
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="relative">
                            <div class="flex flex-col items-center gap-6 p-8 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <svg class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>

                                <div class="text-center space-y-4">
                                    <label for="image" class="inline-flex items-center px-6 py-3 bg-white border-2 border-primary/20 text-primary font-medium rounded-xl cursor-pointer hover:border-primary hover:bg-primary hover:text-white">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span>Nieuwe afbeelding kiezen</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    
                                    <p class="text-sm text-gray-500">
                                        PNG, JPG of GIF (max. 5MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video URL -->
                    <div class="mb-8">
                        <label for="video_url" class="block text-sm text-gray-600 mb-2">Video URL (YouTube, Vimeo)</label>
                        <input type="url" 
                               name="video_url" 
                               id="video_url" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="<?php echo htmlspecialchars($blog->video_url ?? ''); ?>">
                        <p class="mt-1 text-sm text-gray-500">Plak hier een YouTube of Vimeo video URL</p>
                    </div>

                    <!-- Content Editor Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Editor -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Blog content
                                <span class="text-primary">(Markdown ondersteund)</span>
                            </label>
                            <div class="h-[500px]">
                                <textarea name="content" 
                                          id="content" 
                                          class="w-full h-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono resize-none"
                                          required><?php echo htmlspecialchars($blog->content); ?></textarea>
                            </div>
                        </div>

                        <!-- Live Preview -->
                        <div>
                            <div class="sticky top-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Live preview</label>
                                <div id="preview" class="prose prose-lg max-w-none p-6 bg-white rounded-xl border-2 border-gray-200 h-[500px] overflow-y-auto">
                                    <em class="text-gray-500">Loading preview...</em>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Markdown Help Dropdown -->
                    <div class="mt-8">
                        <button type="button" 
                                onclick="toggleMarkdownHelp()"
                                class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl border border-gray-200 text-left hover:from-primary/20 hover:to-secondary/20">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Markdown Opmaak Hulp</h3>
                                    <p class="text-sm text-gray-600">Klik om voorbeelden te bekijken</p>
                                </div>
                            </div>
                            <svg id="markdownHelpIcon" class="w-5 h-5 text-gray-500 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Collapsible content -->
                        <div id="markdownHelp" class="hidden overflow-hidden">
                            <div class="p-6 bg-white border-x border-b border-gray-200 rounded-b-xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Headings -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Koppen</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono"># Tekst</code>
                                                <div class="mt-2 text-xl font-bold text-gray-900">Hoofdtitel</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono">## Tekst</code>
                                                <div class="mt-2 text-lg font-bold text-gray-900">Subtitel</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono">### Tekst</code>
                                                <div class="mt-2 text-base font-bold text-gray-900">Sub-subtitel</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Text Formatting -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Tekst Opmaak</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono">**vetgedrukt**</code>
                                                <div class="mt-2 font-bold text-gray-900">vetgedrukt</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono">*cursief*</code>
                                                <div class="mt-2 italic text-gray-900">cursief</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                                <code class="text-gray-600 font-mono">[link tekst](https://example.com)</code>
                                                <div class="mt-2 text-primary hover:underline">link tekst</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between space-x-4">
                        <a href="<?php echo URLROOT; ?>/blogs/manage" 
                           class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:border-gray-300 hover:text-gray-900">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            Annuleren
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 sm:px-8 sm:py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Wijzigingen Opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/marked@4.3.0/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.3/dist/purify.min.js"></script>
<script>
// Markdown help toggle function
function toggleMarkdownHelp() {
    const helpContent = document.getElementById('markdownHelp');
    const icon = document.getElementById('markdownHelpIcon');
    
    if (helpContent.classList.contains('hidden')) {
        helpContent.classList.remove('hidden');
        helpContent.style.maxHeight = helpContent.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    } else {
        helpContent.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => {
            helpContent.classList.add('hidden');
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('preview');

    // Configure marked
    marked.use({ 
        breaks: true,
        gfm: true,
        headerIds: false
    });

    // Update preview with debounce
    let previewTimeout = null;
    function updatePreview() {
        const content = contentTextarea.value;
        if (content.trim() === '') {
            previewDiv.innerHTML = '<em class="text-gray-500">Begin met typen om de preview te zien...</em>';
            return;
        }

        try {
            // Parse markdown to HTML
            const html = marked.parse(content);
            // Sanitize HTML
            const cleanHtml = DOMPurify.sanitize(html);
            // Update preview
            previewDiv.innerHTML = `<div class="prose prose-lg">${cleanHtml}</div>`;
        } catch (error) {
            console.error('Markdown parsing error:', error);
            previewDiv.innerHTML = '<em class="text-red-500">Er is een fout opgetreden bij het verwerken van de markdown.</em>';
        }
    }

    // Event listener for real-time preview with debounce
    contentTextarea.addEventListener('input', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 150);
    });

    // Initial preview
    updatePreview();
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 