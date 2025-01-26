<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<!-- Decoratieve achtergrond patronen -->
<div class="fixed inset-0 z-0 opacity-10">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239C92AC\" fill-opacity=\"0.15\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
</div>

<main class="min-h-screen py-12 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decoratieve elementen -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-secondary/10 to-transparent"></div>
    
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header Sectie -->
            <div class="text-center mb-12" data-aos="fade-down">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 relative inline-block">
                    <span class="relative z-10">Nieuwe Blog Schrijven</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-1"></div>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Deel jouw politieke inzichten en draag bij aan het maatschappelijke debat
                </p>
            </div>

            <!-- Hoofdformulier -->
            <form action="<?php echo URLROOT; ?>/blogs/create" method="POST" enctype="multipart/form-data" 
                  class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                
                <!-- Decoratieve header -->
                <div class="bg-gradient-to-r from-primary to-secondary h-2"></div>

                <div class="p-8">
                    <!-- Titel Sectie -->
                    <div class="mb-8" data-aos="fade-up" data-aos-delay="100">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titel van je blog
                        </label>
                        <div class="relative group">
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all duration-300 text-lg font-medium"
                                   required
                                   placeholder="Een pakkende titel voor je blog...">
                            <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- Afbeelding Upload Sectie -->
                    <div class="mb-8" data-aos="fade-up" data-aos-delay="200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Header afbeelding
                        </label>
                        <div class="relative group">
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl transition-all duration-300 group-hover:border-primary/50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors duration-300" 
                                         stroke="currentColor" 
                                         fill="none" 
                                         viewBox="0 0 48 48" 
                                         aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                              stroke-width="2" 
                                              stroke-linecap="round" 
                                              stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-secondary focus-within:outline-none">
                                            <span>Upload een afbeelding</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">of sleep deze hierheen</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF tot 5MB</p>
                                </div>
                            </div>
                            <!-- Preview container -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <img src="" alt="Preview" class="max-h-48 rounded-lg mx-auto">
                            </div>
                        </div>
                    </div>

                    <!-- Content Editor Sectie -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="300">
                        <!-- Editor -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Blog content
                                <span class="text-primary">(Markdown ondersteund)</span>
                            </label>
                            <div class="relative group h-[500px]">
                                <textarea name="content" 
                                          id="content" 
                                          class="w-full h-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all duration-300 font-mono resize-none"
                                          required
                                          placeholder="Begin hier met schrijven..."></textarea>
                                <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                            </div>
                        </div>

                        <!-- Live Preview -->
                        <div>
                            <div class="sticky top-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Live preview</label>
                                <div id="preview" class="prose prose-lg max-w-none p-6 bg-white rounded-xl border-2 border-gray-200 h-[500px] overflow-y-auto">
                                    <em class="text-gray-500">Begin met typen om de preview te zien...</em>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Markdown Hulp Dropdown -->
                    <div class="mt-8" data-aos="fade-up" data-aos-delay="400">
                        <button type="button" 
                                onclick="toggleMarkdownHelp()"
                                class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl border border-gray-200 text-left transition-all duration-300 hover:from-primary/20 hover:to-secondary/20">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Markdown Opmaak Hulp</h3>
                                    <p class="text-sm text-gray-600">Klik om voorbeelden te bekijken</p>
                                </div>
                            </div>
                            <svg id="markdownHelpIcon" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Uitklapbare content -->
                        <div id="markdownHelp" class="hidden overflow-hidden transition-all duration-500 ease-in-out">
                            <div class="p-6 bg-white border-x border-b border-gray-200 rounded-b-xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Koppen -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Koppen</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono"># Tekst</code>
                                                <div class="mt-2 text-xl font-bold text-gray-900">Hoofdtitel</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">## Tekst</code>
                                                <div class="mt-2 text-lg font-bold text-gray-900">Subtitel</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">### Tekst</code>
                                                <div class="mt-2 text-base font-bold text-gray-900">Sub-subtitel</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tekst Opmaak -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Tekst Opmaak</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">**Tekst**</code>
                                                <div class="mt-2 font-bold text-gray-900">Vetgedrukt</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">*Tekst*</code>
                                                <div class="mt-2 italic text-gray-900">Schuingedrukt</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lijsten -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Lijsten</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">- Item<br>- Nog een item</code>
                                                <div class="mt-2 text-gray-900">
                                                    <ul class="list-disc list-inside">
                                                        <li>Item</li>
                                                        <li>Nog een item</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">1. Item<br>2. Nog een item</code>
                                                <div class="mt-2 text-gray-900">
                                                    <ol class="list-decimal list-inside">
                                                        <li>Item</li>
                                                        <li>Nog een item</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Links & Afbeeldingen -->
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-medium text-primary mb-3">Links & Afbeeldingen</h4>
                                        <div class="space-y-3">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">[Tekst](url)</code>
                                                <div class="mt-2">
                                                    <a href="#" class="text-primary hover:underline">Link tekst</a>
                                                </div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-primary/30 transition-colors duration-300">
                                                <code class="text-gray-600 font-mono">![Alt tekst](url)</code>
                                                <div class="mt-2 text-gray-600 flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Afbeelding invoegen
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actie Knoppen -->
                    <div class="mt-8 flex items-center justify-between space-x-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:border-gray-300 hover:text-gray-900 transition-colors duration-300 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            Annuleren
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 sm:px-8 sm:py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Blog Publiceren
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
// Markdown help toggle functie (globaal beschikbaar)
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
        }, 500);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('preview');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    // Configureer marked met aangepaste renderer
    const renderer = {
        heading(text, level) {
            const sizes = {
                1: 'text-4xl font-bold mb-6',
                2: 'text-3xl font-bold mb-4',
                3: 'text-2xl font-bold mb-3'
            };
            return `<h${level} class="${sizes[level]}">${text}</h${level}>`;
        },
        paragraph(text) {
            return `<p class="mb-6">${text}</p>`;
        },
        list(body, ordered) {
            const type = ordered ? 'ol' : 'ul';
            const className = ordered ? 'list-decimal' : 'list-disc';
            return `<${type} class="${className} ml-4 mb-4 space-y-2">${body}</${type}>`;
        },
        listitem(text) {
            return `<li class="ml-4">${text}</li>`;
        },
        link(href, title, text) {
            return `<a href="${href}" class="text-primary hover:underline" target="_blank">${text}</a>`;
        },
        image(href, title, text) {
            return `<img src="${href}" alt="${text}" title="${title || ''}" class="max-w-full rounded-lg my-4">`;
        },
        strong(text) {
            return `<strong class="font-bold">${text}</strong>`;
        },
        em(text) {
            return `<em class="italic">${text}</em>`;
        }
    };

    // Configureer marked
    marked.use({ 
        renderer,
        breaks: true,
        gfm: true,
        headerIds: false
    });

    // Update preview functie
    function updatePreview() {
        const content = contentTextarea.value;
        if (content.trim() === '') {
            previewDiv.innerHTML = '<em class="text-gray-500">Begin met typen om de preview te zien...</em>';
            return;
        }

        try {
            // Parse markdown naar HTML
            const html = marked.parse(content);
            // Sanitize de HTML
            const cleanHtml = DOMPurify.sanitize(html);
            // Update de preview
            previewDiv.innerHTML = `<div class="prose prose-lg">${cleanHtml}</div>`;
        } catch (error) {
            console.error('Markdown parsing error:', error);
            previewDiv.innerHTML = '<em class="text-red-500">Er is een fout opgetreden bij het verwerken van de markdown.</em>';
        }
    }

    // Event listeners voor real-time preview
    let timeout = null;
    contentTextarea.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(updatePreview, 150);
    });

    // Trigger initial preview
    updatePreview();

    // Afbeelding preview
    imageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.classList.remove('hidden');
                imagePreview.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Drag and drop functionaliteit
    const dropZone = imageInput.closest('div');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-primary');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-primary');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        imageInput.files = files;
        
        if (files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.classList.remove('hidden');
                imagePreview.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(files[0]);
        }
    }
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 