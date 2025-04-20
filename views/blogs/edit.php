<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<!-- Stijlvolle achtergrond met subtiel patroon -->
<div class="fixed inset-0 z-0 opacity-10">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239C92AC\" fill-opacity=\"0.15\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
</div>

<main class="min-h-screen py-8 md:py-16 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decoratieve achtergrond elementen -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-secondary/10 to-transparent"></div>
    
    <div class="container mx-auto px-4 sm:px-6">
        <div class="max-w-5xl mx-auto">
            <!-- Header met moderne styling -->
            <div class="text-center mb-10" data-aos="fade-down">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 relative inline-block tracking-tight">
                    <span class="relative z-10">Blog Bewerken</span>
                    <div class="absolute -bottom-3 left-0 w-full h-3 bg-primary/20 -rotate-1 transform origin-left scale-110"></div>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Pas je blog aan met de geavanceerde editor
                </p>
            </div>

            <!-- Hoofdformulier met verbeterde styling -->
            <form action="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>" method="POST" enctype="multipart/form-data" 
                  class="bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl relative">
                
                <!-- Decoratieve header bar met gradient -->
                <div class="bg-gradient-to-r from-primary via-primary/90 to-secondary h-2"></div>

                <!-- Subtiele strepen patroon voor decoratie -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-70 rounded-bl-full"></div>
                
                <div class="p-6 sm:p-10 relative">
                    <!-- Title Section -->
                    <div class="mb-8" data-aos="fade-up" data-aos-delay="100">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Titel van je blog
                        </label>
                        <div class="relative group">
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 text-lg font-medium bg-gray-50/50"
                                   required
                                   value="<?php echo htmlspecialchars($blog->title); ?>">
                            <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- Image Upload Section - verbeterd ontwerp -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="200">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Header afbeelding
                        </label>
                        
                        <?php if(!empty($blog->image_path)): ?>
                            <div class="mb-6">
                                <div class="relative bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <h3 class="font-medium text-gray-900">Huidige afbeelding</h3>
                                        </div>
                                    </div>
                                    <div class="relative rounded-xl overflow-hidden bg-white shadow-md">
                                        <div class="aspect-[16/9] overflow-hidden">
                                            <img src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($blog->title); ?>"
                                                 class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="relative">
                            <!-- Upload zone met verbeterd design -->
                            <div class="group/upload relative overflow-hidden">
                                <div class="relative flex flex-col items-center gap-6 p-8 bg-gradient-to-br from-white to-gray-50 rounded-2xl border-2 border-dashed border-gray-200 transition-all duration-500 ease-out hover:border-primary hover:from-primary/[0.02] hover:to-secondary/[0.02]">
                                    <!-- Decoratieve elementen -->
                                    <div class="absolute -left-4 -top-4 w-32 h-32 bg-primary/5 rounded-full mix-blend-multiply filter blur-xl opacity-0 group-hover/upload:opacity-70 transition-all duration-700 group-hover/upload:translate-x-4 group-hover/upload:translate-y-4"></div>
                                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-secondary/5 rounded-full mix-blend-multiply filter blur-xl opacity-0 group-hover/upload:opacity-70 transition-all duration-700 group-hover/upload:translate-x-4 group-hover/upload:translate-y-4"></div>

                                    <!-- Upload icoon container met verbeterde animaties -->
                                    <div class="relative z-10 group/icon">
                                        <div class="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm transition-all duration-500 group-hover/upload:bg-white group-hover/upload:shadow-lg group-hover/upload:scale-110">
                                            <svg class="w-12 h-12 text-gray-400 transition-colors duration-300 group-hover/upload:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path class="transition-all duration-500" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Upload tekst en knop met verbeterd ontwerp -->
                                    <div class="relative z-10 text-center space-y-4">
                                        <label for="image" class="group/button inline-flex items-center px-6 py-3 bg-white border-2 border-primary/30 text-primary font-medium rounded-xl cursor-pointer shadow-sm transition-all duration-300 hover:border-primary hover:bg-primary hover:text-white hover:shadow-md">
                                            <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover/button:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span>Nieuwe afbeelding kiezen</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        
                                        <div class="flex flex-col items-center space-y-2">
                                            <p class="text-sm text-gray-500">
                                                Sleep je afbeelding hierheen of gebruik de knop hierboven
                                            </p>
                                            <span class="inline-flex items-center px-3 py-1 space-x-1 bg-gray-50 rounded-full text-xs text-gray-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>PNG, JPG of GIF (max. 5MB)</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video URL Section -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="250">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Video toevoegen
                        </label>
                        
                        <!-- Video URL kaart -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-primary mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <h3 class="font-medium text-gray-900">Video URL</h3>
                            </div>
                            
                            <div class="relative group mb-2">
                                <input type="url" 
                                       name="video_url" 
                                       id="video_url" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 bg-gray-50/50"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       value="<?php echo htmlspecialchars($blog->video_url ?? ''); ?>">
                                <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                            </div>
                            <p class="text-sm text-gray-500">Plak een YouTube of Vimeo video URL</p>
                        </div>
                        
                        <!-- Video Preview if URL exists -->
                        <?php if(!empty($blog->video_url)): ?>
                        <div id="currentVideoPreview" class="mt-4">
                            <div class="relative bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 class="font-medium text-gray-900">Huidige video</h3>
                                    </div>
                                </div>
                                
                                <div class="relative rounded-xl overflow-hidden bg-black">
                                    <div class="aspect-video">
                                        <?php
                                        $videoUrl = $blog->video_url;
                                        $embedUrl = '';
                                        
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $youtubeMatches)) {
                                            $embedUrl = "https://www.youtube.com/embed/{$youtubeMatches[1]}";
                                        } elseif (preg_match('/(?:vimeo\.com\/)([0-9]+)/', $videoUrl, $vimeoMatches)) {
                                            $embedUrl = "https://player.vimeo.com/video/{$vimeoMatches[1]}";
                                        }
                                        
                                        if (!empty($embedUrl)):
                                        ?>
                                            <iframe src="<?php echo $embedUrl; ?>" 
                                                    class="w-full aspect-video" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen></iframe>
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                                <p>Video kan niet worden weergegeven</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Content Editor Sectie - volledig herontworpen met markdown knoppen -->
                    <div class="mt-10" data-aos="fade-up" data-aos-delay="300">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-1 flex items-center">
                                <svg class="w-5 h-5 text-primary mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Blog content
                            </h3>
                            <p class="text-sm text-gray-600">Bewerk je artikeltekst met ondersteuning voor opmaak</p>
                        </div>

                        <!-- Moderne Editor met Markdown Knoppen -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <!-- Markdown toolbar -->
                            <div class="flex flex-wrap items-center gap-1 p-2 border-b border-gray-100 bg-gray-50/70">
                                <div class="flex items-center divide-x divide-gray-200">
                                    <!-- Koppen -->
                                    <div class="pr-1">
                                        <button type="button" onclick="insertMarkdown('heading')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Koptekst">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Tekstopmaak -->
                                    <div class="px-1 flex items-center gap-1">
                                        <button type="button" onclick="insertMarkdown('bold')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Vetgedrukt">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11h4a4 4 0 100-8H8v8zm0 0v8h5a4 4 0 000-8H8z"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('italic')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Cursief">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.5 9l-3 9m0 0l-3-9m3 9v-9m0 0H9m6 0h3"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('quote')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Citaat">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Lijsten -->
                                    <div class="px-1 flex items-center gap-1">
                                        <button type="button" onclick="insertMarkdown('bullet')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Opsomming">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('number')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Genummerde lijst">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Links en media -->
                                    <div class="pl-1 flex items-center gap-1">
                                        <button type="button" onclick="insertMarkdown('link')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Link invoegen">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('image')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Afbeelding invoegen">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('code')" class="p-1.5 text-gray-600 hover:text-primary hover:bg-gray-100 rounded transition-colors" title="Code blok">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Editor en Preview Tabs -->
                            <div class="flex border-b border-gray-100">
                                <button type="button" 
                                        id="editorTabBtn" 
                                        onclick="switchToEditor()" 
                                        class="px-4 py-2 font-medium text-primary border-b-2 border-primary">
                                    Bewerken
                                </button>
                                <button type="button" 
                                        id="previewTabBtn" 
                                        onclick="switchToPreview()" 
                                        class="px-4 py-2 font-medium text-gray-500 hover:text-gray-700">
                                    Preview
                                </button>
                            </div>
                            
                            <!-- Editor Container -->
                            <div id="editorContainer" class="relative">
                                <textarea name="content" 
                                          id="content" 
                                          class="w-full h-[500px] px-4 py-3 border-0 outline-none transition-all duration-300 font-mono resize-none focus:ring-0"
                                          required><?php echo htmlspecialchars($blog->content); ?></textarea>
                            </div>
                            
                            <!-- Preview Container -->
                            <div id="previewContainer" class="hidden h-[500px] overflow-y-auto p-6">
                                <div id="preview" class="prose prose-lg max-w-none">
                                    <em class="text-gray-500">Begin met typen om de preview te zien...</em>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Handige tips -->
                        <div class="mt-4 p-4 bg-primary/5 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-primary mt-0.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-gray-900">Tip:</span> 
                                        Gebruik de knoppen hierboven om markdown opmaak toe te voegen. Schakel naar de preview-tab om te zien hoe je artikel eruit zal zien.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actie Knoppen met verbeterd design -->
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="<?php echo URLROOT; ?>/blogs/manage" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 border-2 border-gray-200 rounded-xl text-gray-600 bg-white hover:border-gray-300 hover:text-gray-900 transition-colors duration-300 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            Annuleren
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('preview');
    const editorContainer = document.getElementById('editorContainer');
    const previewContainer = document.getElementById('previewContainer');
    const editorTabBtn = document.getElementById('editorTabBtn');
    const previewTabBtn = document.getElementById('previewTabBtn');

    // Tabfunctionaliteit voor editor/preview
    window.switchToEditor = function() {
        editorContainer.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        editorTabBtn.classList.add('text-primary', 'border-b-2', 'border-primary');
        editorTabBtn.classList.remove('text-gray-500');
        previewTabBtn.classList.remove('text-primary', 'border-b-2', 'border-primary');
        previewTabBtn.classList.add('text-gray-500');
    };

    window.switchToPreview = function() {
        updatePreview(); // Zorg dat preview up-to-date is
        editorContainer.classList.add('hidden');
        previewContainer.classList.remove('hidden');
        previewTabBtn.classList.add('text-primary', 'border-b-2', 'border-primary');
        previewTabBtn.classList.remove('text-gray-500');
        editorTabBtn.classList.remove('text-primary', 'border-b-2', 'border-primary');
        editorTabBtn.classList.add('text-gray-500');
    };

    // Markdown invoegfunctionaliteit
    window.insertMarkdown = function(type) {
        // Huidige cursor positie opslaan
        const start = contentTextarea.selectionStart;
        const end = contentTextarea.selectionEnd;
        const selectedText = contentTextarea.value.substring(start, end);
        
        let markdownText = '';
        
        switch(type) {
            case 'heading':
                markdownText = `## ${selectedText || 'Koptekst'}`;
                break;
            case 'bold':
                markdownText = `**${selectedText || 'vetgedrukte tekst'}**`;
                break;
            case 'italic':
                markdownText = `*${selectedText || 'cursieve tekst'}*`;
                break;
            case 'quote':
                markdownText = `> ${selectedText || 'citaat tekst'}`;
                break;
            case 'bullet':
                markdownText = `- ${selectedText || 'lijstitem'}`;
                break;
            case 'number':
                markdownText = `1. ${selectedText || 'genummerd item'}`;
                break;
            case 'link':
                markdownText = `[${selectedText || 'link tekst'}](url)`;
                break;
            case 'image':
                markdownText = `![${selectedText || 'alt tekst'}](afbeelding-url)`;
                break;
            case 'code':
                markdownText = `\`\`\`\n${selectedText || 'code hier'}\n\`\`\``;
                break;
            default:
                return;
        }
        
        // Nieuwe content samenstellen
        const newContent = contentTextarea.value.substring(0, start) + markdownText + contentTextarea.value.substring(end);
        contentTextarea.value = newContent;
        
        // Focus terugzetten en preview updaten
        contentTextarea.focus();
        contentTextarea.selectionStart = start;
        contentTextarea.selectionEnd = start + markdownText.length;
        
        // Preview bijwerken
        updatePreview();
    };

    // Configure marked with custom renderer
    const renderer = {
        heading(text, level) {
            const sizes = {
                1: 'text-4xl font-bold mb-6',
                2: 'text-3xl font-bold mb-4',
                3: 'text-2xl font-bold mb-3'
            };
            return `<h${level} class="${sizes[level] || 'text-xl font-bold mb-2'}">${text}</h${level}>`;
        },
        paragraph(text) {
            return `<p class="mb-6 text-gray-700 leading-relaxed">${text}</p>`;
        },
        list(body, ordered) {
            const type = ordered ? 'ol' : 'ul';
            const className = ordered ? 'list-decimal' : 'list-disc';
            return `<${type} class="${className} ml-4 mb-6 space-y-2 text-gray-700">${body}</${type}>`;
        },
        listitem(text) {
            return `<li class="ml-4">${text}</li>`;
        },
        link(href, title, text) {
            return `<a href="${href}" class="text-primary hover:underline" target="_blank" rel="noopener noreferrer">${text}</a>`;
        },
        image(href, title, text) {
            return `<img src="${href}" alt="${text}" title="${title || ''}" class="max-w-full rounded-lg my-6 shadow-lg">`;
        },
        strong(text) {
            return `<strong class="font-bold text-gray-900">${text}</strong>`;
        },
        em(text) {
            return `<em class="italic text-gray-800">${text}</em>`;
        },
        blockquote(text) {
            return `<blockquote class="border-l-4 border-primary/30 pl-4 py-2 mb-6 text-gray-700 italic">${text}</blockquote>`;
        },
        code(text, language) {
            return `<pre class="bg-gray-50 rounded-lg p-4 mb-6 overflow-x-auto"><code class="text-sm font-mono text-gray-800">${text}</code></pre>`;
        },
        hr() {
            return '<hr class="my-8 border-t-2 border-gray-100">';
        }
    };

    // Configureer marked
    marked.use({ 
        renderer,
        breaks: true,
        gfm: true,
        headerIds: false
    });

    // Update preview functie met debounce
    let previewTimeout = null;
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
            // Update de preview met fade effect
            previewDiv.style.opacity = '0';
            setTimeout(() => {
                previewDiv.innerHTML = cleanHtml;
                previewDiv.style.opacity = '1';
            }, 150);
        } catch (error) {
            console.error('Markdown parsing error:', error);
            previewDiv.innerHTML = '<em class="text-red-500">Er is een fout opgetreden bij het verwerken van de markdown.</em>';
        }
    }

    // Event listener voor real-time preview met debounce
    contentTextarea.addEventListener('input', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 150);
    });

    // Initial preview
    updatePreview();
    
    // Voeg animaties toe
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('shadow-sm');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('shadow-sm');
        });
    });
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 