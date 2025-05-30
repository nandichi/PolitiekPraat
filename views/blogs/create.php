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
                    <span class="relative z-10">Nieuwe Blog Schrijven</span>
                    <div class="absolute -bottom-3 left-0 w-full h-3 bg-primary/20 -rotate-1 transform origin-left scale-110"></div>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Deel jouw politieke inzichten en draag bij aan het maatschappelijke debat
                </p>
            </div>

            <!-- Hoofdformulier met verbeterde styling -->
            <form action="<?php echo URLROOT; ?>/blogs/create" method="POST" enctype="multipart/form-data" 
                  class="bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl relative">
                
                <!-- Decoratieve header bar met gradient -->
                <div class="bg-gradient-to-r from-primary via-primary/90 to-secondary h-2"></div>

                <!-- Subtiele strepen patroon voor decoratie -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-70 rounded-bl-full"></div>
                
                <div class="p-6 sm:p-10 relative">
                    <!-- Titel Sectie -->
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
                                   placeholder="Een pakkende titel voor je blog...">
                            <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- Afbeelding Upload Sectie - verbeterd ontwerp -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="200">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Header afbeelding
                        </label>
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
                                            <span>Afbeelding kiezen</span>
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

                                <!-- Preview container met verbeterde styling -->
                                <div id="imagePreview" class="hidden mt-6">
                                    <div class="relative bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                        <!-- Preview header -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <h3 class="font-medium text-gray-900">Header afbeelding preview</h3>
                                            </div>
                                            <button type="button" 
                                                    onclick="removeImage()" 
                                                    class="group inline-flex items-center px-3 py-1 space-x-1 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="text-sm font-medium">Verwijderen</span>
                                            </button>
                                        </div>

                                        <!-- Preview image container met verbeterde styling -->
                                        <div class="relative rounded-xl overflow-hidden bg-white shadow-md">
                                            <div class="aspect-[16/9] overflow-hidden">
                                                <img src="" alt="Preview" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                                            </div>
                                            <!-- Image info -->
                                            <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                                                <p class="text-white text-sm" id="imageInfo">Afbeelding info laden...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Upload Sectie -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="250">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Video toevoegen
                        </label>
                        
                        <!-- Video opties kaartweergave -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Video URL input kaart -->
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
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                </div>
                                <p class="text-sm text-gray-500">Plak een YouTube of Vimeo video URL</p>
                            </div>

                            <!-- Video bestand upload kaart -->
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-primary mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <h3 class="font-medium text-gray-900">Video bestand</h3>
                                </div>
                                
                                <div class="relative group/upload">
                                    <div class="relative flex flex-col items-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 transition-all duration-300 hover:bg-gray-50/70 hover:border-primary/30">
                                        <label for="video" class="w-full group/button flex flex-col items-center justify-center cursor-pointer py-3">
                                            <svg class="w-8 h-8 text-gray-400 mb-2 transition-colors duration-300 group-hover/button:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 group-hover/button:text-primary transition-colors">Klik of sleep om video te uploaden</span>
                                            <input id="video" name="video" type="file" class="sr-only" accept="video/*">
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">MP4, WebM of OGG (max. 100MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video Preview -->
                        <div id="videoPreview" class="hidden mt-6">
                            <div class="relative bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                <!-- Preview header -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 class="font-medium text-gray-900">Video preview</h3>
                                    </div>
                                    <button type="button" 
                                            onclick="removeVideo()" 
                                            class="group inline-flex items-center px-3 py-1 space-x-1 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="text-sm font-medium">Verwijderen</span>
                                    </button>
                                </div>

                                <!-- Video player container -->
                                <div class="relative rounded-xl overflow-hidden bg-black">
                                    <div class="aspect-video">
                                        <video id="videoPlayer" controls class="w-full h-full">
                                            <source src="" type="video/mp4">
                                            Je browser ondersteunt geen video weergave.
                                        </video>
                                    </div>
                                    <!-- Video info -->
                                    <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                                        <p class="text-white text-sm" id="videoInfo">Video info laden...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <p class="text-sm text-gray-600">Schrijf hier je artikeltekst met ondersteuning voor opmaak</p>
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
                                          required
                                          placeholder="Begin hier met schrijven... Gebruik de knoppen hierboven voor opmaak."></textarea>
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

                    <!-- Actie Knoppen met verbeterd design -->
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="<?php echo URLROOT; ?>/blogs" 
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
    const imageInfoText = document.getElementById('imageInfo');
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

    // Configureer marked met aangepaste renderer
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

    // Trigger initial preview
    updatePreview();

    // Functie om bestandsgrootte te formatteren
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Functie om afbeelding te verwijderen
    window.removeImage = function() {
        imageInput.value = ''; // Reset input
        imagePreview.classList.add('hidden'); // Verberg preview
        
        // Animatie voor het verwijderen
        imagePreview.style.opacity = '0';
        imagePreview.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            imagePreview.style.opacity = '';
            imagePreview.style.transform = '';
        }, 300);
    }

    // Update afbeelding preview met extra informatie
    function updateImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Toon de preview container met animatie
            imagePreview.classList.remove('hidden');
            imagePreview.style.opacity = '0';
            imagePreview.style.transform = 'translateY(10px)';
            
            // Update de afbeelding
            const img = imagePreview.querySelector('img');
            img.src = e.target.result;
            
            // Update bestandsinformatie
            const fileInfo = `${file.name} (${formatFileSize(file.size)})`;
            imageInfoText.textContent = fileInfo;
            
            // Animeer het verschijnen
            setTimeout(() => {
                imagePreview.style.opacity = '1';
                imagePreview.style.transform = 'translateY(0)';
            }, 50);
        }
        reader.readAsDataURL(file);
    }

    // Event listeners voor afbeelding upload
    imageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            updateImagePreview(e.target.files[0]);
        }
    });

    // Drag and drop functionaliteit
    const dropZone = document.querySelector('.group/upload');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
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

        if (files && files[0]) {
            imageInput.files = files; // Update de input files
            updateImagePreview(files[0]);
        }
    }

    // Video functionaliteit
    const videoInput = document.getElementById('video');
    const videoPreview = document.getElementById('videoPreview');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoInfoText = document.getElementById('videoInfo');
    const videoUrlInput = document.getElementById('video_url');

    // Video URL validatie en preview
    videoUrlInput.addEventListener('input', function() {
        const url = this.value;
        if (url) {
            // YouTube URL validatie en omzetting
            const youtubeMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
            if (youtubeMatch) {
                const videoId = youtubeMatch[1];
                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                showVideoUrlPreview(embedUrl, 'youtube');
                return;
            }

            // Vimeo URL validatie en omzetting
            const vimeoMatch = url.match(/(?:vimeo\.com\/)([0-9]+)/);
            if (vimeoMatch) {
                const videoId = vimeoMatch[1];
                const embedUrl = `https://player.vimeo.com/video/${videoId}`;
                showVideoUrlPreview(embedUrl, 'vimeo');
                return;
            }

            // Ongeldige URL
            alert('Voer een geldige YouTube of Vimeo URL in');
            this.value = '';
        }
    });

    function showVideoUrlPreview(embedUrl, platform) {
        videoPreview.classList.remove('hidden');
        const iframe = document.createElement('iframe');
        iframe.src = embedUrl;
        iframe.className = 'w-full aspect-video';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;

        const videoContainer = videoPreview.querySelector('.aspect-video');
        videoContainer.innerHTML = '';
        videoContainer.appendChild(iframe);

        videoInfoText.textContent = `${platform.charAt(0).toUpperCase() + platform.slice(1)} video`;
    }

    // Video bestand upload en preview
    function updateVideoPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            videoPreview.classList.remove('hidden');
            videoPreview.style.opacity = '0';
            videoPreview.style.transform = 'translateY(10px)';
            
            // Update video player
            videoPlayer.src = e.target.result;
            
            // Update bestandsinformatie
            const fileInfo = `${file.name} (${formatFileSize(file.size)})`;
            videoInfoText.textContent = fileInfo;
            
            // Animeer het verschijnen
            setTimeout(() => {
                videoPreview.style.opacity = '1';
                videoPreview.style.transform = 'translateY(0)';
            }, 50);
        }
        reader.readAsDataURL(file);
    }

    // Video verwijderen functie
    window.removeVideo = function() {
        videoInput.value = ''; // Reset input
        videoUrlInput.value = ''; // Reset URL input
        videoPreview.classList.add('hidden'); // Verberg preview
        
        // Animatie voor het verwijderen
        videoPreview.style.opacity = '0';
        videoPreview.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            videoPreview.style.opacity = '';
            videoPreview.style.transform = '';
            videoPlayer.src = ''; // Reset video player
        }, 300);
    }

    // Event listeners voor video bestand upload
    videoInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            // Check bestandsgrootte (max 100MB)
            if (file.size > 100 * 1024 * 1024) {
                alert('Video mag niet groter zijn dan 100MB');
                this.value = '';
                return;
            }
            updateVideoPreview(file);
            // Reset URL input als er een bestand is gekozen
            videoUrlInput.value = '';
        }
    });

    // Voorkom dat beide video opties tegelijk worden gebruikt
    videoUrlInput.addEventListener('input', function() {
        if (this.value) {
            videoInput.value = ''; // Reset bestand input
            if (!videoPreview.classList.contains('hidden')) {
                removeVideo();
            }
        }
    });

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