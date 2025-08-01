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

                    <!-- Category Selection Section -->
                    <div class="mb-8" data-aos="fade-up" data-aos-delay="150">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Categorie selecteren
                        </label>
                        <div class="relative group">
                            <select name="category_id" 
                                    id="category_id" 
                                    class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 text-lg bg-gray-50/50 appearance-none cursor-pointer">
                                <option value="">Geen categorie (optioneel)</option>
                                <?php 
                                if (isset($categories) && !empty($categories)): 
                                    foreach ($categories as $category): 
                                ?>
                                    <option value="<?php echo $category->id; ?>" 
                                            <?php echo (isset($blog->category_id) && $blog->category_id == $category->id) ? 'selected' : ''; ?>
                                            data-color="<?php echo $category->color; ?>">
                                        <?php echo htmlspecialchars($category->name); ?>
                                    </option>
                                <?php 
                                    endforeach; 
                                endif; 
                                ?>
                            </select>
                            <!-- Custom dropdown arrow -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kies de categorie die het beste bij je blog past. Dit helpt lezers je artikel te vinden.
                        </p>
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
                                            <img src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
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

                    <!-- SoundCloud Audio URL Sectie -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="325">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 17.939h-1v-8.068c.308-.231.639-.429 1-.566v8.634zm3 0h1v-9.224c-.229.265-.443.548-.621.857l-.379-.184v8.551zm-2 0h1v-8.848c-.508-.079-.623-.05-1-.01v8.858zm-4 0h1v-6.891c-.024.184-.037.37-.037.557 0 .228.017.457.037.684v5.65zm13 0h1v-2.24c-.508.138-1.027.262-1.532.355l.532.025v1.86zm-3 0h1v-2.32c-.203.206-.398.422-.609.629l.609.177v1.514zm2 0h1v-2.174c-.993.21-1.927.364-2.811.455l.811-.039v1.758zm3-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557zm-14.32-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557z"/>
                            </svg>
                            SoundCloud Audio (Aanbevolen)
                        </label>
                        
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                            <div class="relative group mb-2">
                                <input type="url" 
                                       name="soundcloud_url" 
                                       id="soundcloud_url" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all duration-300 bg-gray-50/50"
                                       placeholder="https://soundcloud.com/user/track-name"
                                       value="<?php echo htmlspecialchars($blog->soundcloud_url ?? ''); ?>">
                                <div class="absolute inset-0 bg-orange-500/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                            </div>
                            <p class="text-sm text-gray-500">Plak een SoundCloud track of playlist link</p>
                            <div class="mt-2 text-xs text-gray-400 bg-orange-50 p-3 rounded-lg border border-orange-100">
                                <div class="space-y-2">
                                    <p><strong>SoundCloud instructies:</strong></p>
                                    <ol class="list-decimal list-inside space-y-1 text-xs">
                                        <li>Ga naar je SoundCloud track</li>
                                        <li>Klik op "Share" onder de track</li>
                                        <li>Kopieer de link en plak hier</li>
                                        <li>Zorg dat de track openbaar is</li>
                                    </ol>
                                    <p class="text-green-600 font-medium">✅ Voordeel: SoundCloud werkt betrouwbaar zonder CORS problemen en heeft professionele audio player</p>
                                </div>
                            </div>
                        </div>

                        <!-- SoundCloud URL Preview -->
                        <?php if(!empty($blog->soundcloud_url)): ?>
                        <div id="currentSoundcloudPreview" class="mt-4">
                            <div class="relative bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M7 17.939h-1v-8.068c.308-.231.639-.429 1-.566v8.634zm3 0h1v-9.224c-.229.265-.443.548-.621.857l-.379-.184v8.551zm-2 0h1v-8.848c-.508-.079-.623-.05-1-.01v8.858zm-4 0h1v-6.891c-.024.184-.037.37-.037.557 0 .228.017.457.037.684v5.65zm13 0h1v-2.24c-.508.138-1.027.262-1.532.355l.532.025v1.86zm-3 0h1v-2.32c-.203.206-.398.422-.609.629l.609.177v1.514zm2 0h1v-2.174c-.993.21-1.927.364-2.811.455l.811-.039v1.758zm3-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557zm-14.32-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557z"/>
                                        </svg>
                                        <h3 class="font-medium text-gray-900">Huidige SoundCloud audio</h3>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4">
                                    <div id="currentSoundcloudEmbedContainer" class="w-full">
                                        <!-- SoundCloud iframe wordt hier geladen -->
                                        <div class="text-center p-4">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto mb-2"></div>
                                            <p class="text-gray-600">SoundCloud track wordt geladen...</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-sm text-gray-600">
                                        <span id="currentSoundcloudInfo">Huidige SoundCloud track</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center text-orange-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 17.939h-1v-8.068c.308-.231.639-.429 1-.566v8.634zm3 0h1v-9.224c-.229.265-.443.548-.621.857l-.379-.184v8.551zm-2 0h1v-8.848c-.508-.079-.623-.05-1-.10v8.858zm-4 0h1v-6.891c-.024.184-.037.37-.037.557 0 .228.017.457.037.684v5.65zm13 0h1v-2.24c-.508.138-1.027.262-1.532.355l.532.025v1.86zm-3 0h1v-2.32c-.203.206-.398.422-.609.629l.609.177v1.514zm2 0h1v-2.174c-.993.21-1.927.364-2.811.455l.811-.039v1.758zm3-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557zm-14.32-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557z"/>
                                                </svg>
                                                SoundCloud verbonden
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div id="soundcloudPreview" class="hidden mt-6">
                            <div class="relative bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                <!-- Preview header -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M7 17.939h-1v-8.068c.308-.231.639-.429 1-.566v8.634zm3 0h1v-9.224c-.229.265-.443.548-.621.857l-.379-.184v8.551zm-2 0h1v-8.848c-.508-.079-.623-.50-1-.10v8.858zm-4 0h1v-6.891c-.024.184-.037.37-.037.557 0 .228.017.457.037.684v5.65zm13 0h1v-2.24c-.508.138-1.027.262-1.532.355l.532.025v1.86zm-3 0h1v-2.32c-.203.206-.398.422-.609.629l.609.177v1.514zm2 0h1v-2.174c-.993.21-1.927.364-2.811.455l.811-.039v1.758zm3-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557zm-14.32-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557z"/>
                                        </svg>
                                        <h3 class="font-medium text-gray-900">SoundCloud Preview</h3>
                                    </div>
                                    <button type="button" 
                                            onclick="removeSoundCloudUrl()" 
                                            class="group inline-flex items-center px-3 py-1 space-x-1 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="text-sm font-medium">Verwijderen</span>
                                    </button>
                                </div>
                                
                                <!-- SoundCloud embed container -->
                                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4">
                                    <div id="soundcloudEmbedContainer" class="w-full">
                                        <!-- SoundCloud iframe wordt hier geladen -->
                                    </div>
                                    <!-- SoundCloud info -->
                                    <div class="mt-3 flex items-center justify-between text-sm text-gray-600">
                                        <span id="soundcloudInfo">SoundCloud track wordt geladen...</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center text-orange-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 17.939h-1v-8.068c.308-.231.639-.429 1-.566v8.634zm3 0h1v-9.224c-.229.265-.443.548-.621.857l-.379-.184v8.551zm-2 0h1v-8.848c-.508-.079-.623-.50-1-.10v8.858zm-4 0h1v-6.891c-.024.184-.037.37-.037.557 0 .228.017.457.037.684v5.65zm13 0h1v-2.24c-.508.138-1.027.262-1.532.355l.532.025v1.86zm-3 0h1v-2.32c-.203.206-.398.422-.609.629l.609.177v1.514zm2 0h1v-2.174c-.993.21-1.927.364-2.811.455l.811-.039v1.758zm3-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557zm-14.32-2.557c-.024-.068-.053-.133-.063-.201l-.045-.171c-.065-.247-.077-.49-.077-.731 0-.242.012-.484.077-.731l.045-.171c.024-.09.054-.177.087-.265.069-.176.158-.343.261-.501.309-.473.765-.884 1.346-1.15.208-.095.406-.141.601-.141.195 0 .393.046.601.141.581.266 1.037.677 1.346 1.15.103.158.192.325.261.501.033.088.063.175.087.265l.045.171c.065.247.077.489.077.731 0 .241-.012.484-.077.731l-.045.171c-.01.068-.039.133-.063.201l-3 2.557z"/>
                                                </svg>
                                                SoundCloud verbonden
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Poll Sectie -->
                    <div class="mb-10" data-aos="fade-up" data-aos-delay="350">
                        <?php
                        // Check if blog already has a poll
                        $db = new Database();
                        $db->query("SELECT * FROM blog_polls WHERE blog_id = :blog_id");
                        $db->bind(':blog_id', $blog->id);
                        $existingPoll = $db->single();
                        ?>
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Poll toevoegen aan blog
                        </label>
                        
                        <!-- Poll Toggle Switch -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary/5 to-secondary/5 rounded-xl border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <div>
                                        <h3 class="font-medium text-gray-800">
                                            <?= $existingPoll ? 'Poll bewerken' : 'Poll toevoegen' ?>
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            <?= $existingPoll ? 'Pas de bestaande poll aan of verwijder deze' : 'Voeg een interactieve poll toe aan je blog' ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <?php if ($existingPoll): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                            Poll actief
                                        </span>
                                    <?php endif; ?>
                                    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               id="enablePoll" 
                                               class="sr-only peer" 
                                               <?= $existingPoll ? 'checked' : '' ?>
                                               onchange="togglePollSection()">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Poll Configuration Section -->
                        <div id="pollSection" class="<?= $existingPoll ? '' : 'hidden' ?> space-y-6 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                            <!-- Poll Question -->
                            <div>
                                <label for="pollQuestion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Wat is je stelling of vraag?
                                </label>
                                <div class="relative group">
                                    <textarea name="poll_question" 
                                              id="pollQuestion" 
                                              rows="3"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 bg-gray-50/50 resize-none"
                                              placeholder="Bijv. Wat vind jij van de nieuwe belastingplannen?"><?= $existingPoll ? htmlspecialchars($existingPoll->question) : '' ?></textarea>
                                    <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                </div>
                            </div>

                            <!-- Poll Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pollOptionA" class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center">
                                            <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">A</span>
                                            Eerste antwoordoptie
                                        </span>
                                    </label>
                                    <div class="relative group">
                                        <input type="text" 
                                               name="poll_option_a" 
                                               id="pollOptionA" 
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 bg-gray-50/50"
                                               placeholder="Bijv. Helemaal mee eens"
                                               value="<?= $existingPoll ? htmlspecialchars($existingPoll->option_a) : '' ?>">
                                        <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                    </div>
                                </div>

                                <div>
                                    <label for="pollOptionB" class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center">
                                            <span class="w-6 h-6 bg-secondary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">B</span>
                                            Tweede antwoordoptie
                                        </span>
                                    </label>
                                    <div class="relative group">
                                        <input type="text" 
                                               name="poll_option_b" 
                                               id="pollOptionB" 
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all duration-300 bg-gray-50/50"
                                               placeholder="Bijv. Helemaal niet eens"
                                               value="<?= $existingPoll ? htmlspecialchars($existingPoll->option_b) : '' ?>">
                                        <div class="absolute inset-0 bg-primary/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($existingPoll): ?>
                            <!-- Current Poll Stats -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Huidige resultaten
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($existingPoll->option_a) ?></span>
                                        <span class="text-sm text-gray-600">
                                            <?= $existingPoll->option_a_votes ?> stemmen
                                            (<?= $existingPoll->total_votes > 0 ? round(($existingPoll->option_a_votes / $existingPoll->total_votes) * 100, 1) : 0 ?>%)
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($existingPoll->option_b) ?></span>
                                        <span class="text-sm text-gray-600">
                                            <?= $existingPoll->option_b_votes ?> stemmen
                                            (<?= $existingPoll->total_votes > 0 ? round(($existingPoll->option_b_votes / $existingPoll->total_votes) * 100, 1) : 0 ?>%)
                                        </span>
                                    </div>
                                    <div class="pt-2 border-t border-gray-200">
                                        <span class="text-sm font-medium text-gray-800">
                                            Totaal: <?= $existingPoll->total_votes ?> stemmen
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        Poll aangemaakt op <?= date('d-m-Y H:i', strtotime($existingPoll->created_at)) ?>
                                    </span>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                               name="delete_poll" 
                                               id="deletePoll"
                                               class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                               onchange="confirmPollDeletion()">
                                        <span class="text-xs text-red-600">Poll verwijderen</span>
                                    </label>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Poll Preview -->
                            <div id="pollPreview" class="bg-gradient-to-br from-primary/5 via-white to-secondary/5 rounded-xl p-6 border border-gray-100">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Live preview
                                </h4>
                                <div class="bg-white rounded-lg p-4 shadow-sm border">
                                    <h5 id="previewQuestion" class="font-semibold text-gray-800 mb-4">
                                        <?= $existingPoll ? htmlspecialchars($existingPoll->question) : 'Je poll vraag verschijnt hier...' ?>
                                    </h5>
                                    <div class="space-y-3">
                                        <button type="button" class="w-full p-3 text-left bg-gradient-to-r from-primary/10 to-primary/5 hover:from-primary/20 hover:to-primary/10 rounded-lg border border-primary/20 transition-all">
                                            <span id="previewOptionA" class="font-medium text-gray-800">
                                                <?= $existingPoll ? htmlspecialchars($existingPoll->option_a) : 'Optie A verschijnt hier...' ?>
                                            </span>
                                        </button>
                                        <button type="button" class="w-full p-3 text-left bg-gradient-to-r from-secondary/10 to-secondary/5 hover:from-secondary/20 hover:to-secondary/10 rounded-lg border border-secondary/20 transition-all">
                                            <span id="previewOptionB" class="font-medium text-gray-800">
                                                <?= $existingPoll ? htmlspecialchars($existingPoll->option_b) : 'Optie B verschijnt hier...' ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Poll Tips -->
                            <div class="bg-primary/5 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-primary mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-900">Tips voor goede polls:</span> 
                                            Stel duidelijke, neutrale vragen. Zorg dat beide antwoordopties evenwichtig zijn. 
                                            Lezers kunnen maar één keer stemmen per browser.
                                        </p>
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

    // SoundCloud functionaliteit
    const soundcloudInput = document.getElementById('soundcloud_url');
    const soundcloudPreview = document.getElementById('soundcloudPreview');
    const soundcloudEmbedContainer = document.getElementById('soundcloudEmbedContainer');
    const soundcloudInfo = document.getElementById('soundcloudInfo');

    // SoundCloud URL validatie functie
    function validateSoundCloudUrl(url) {
        // Check verschillende SoundCloud URL formaten
        const patterns = [
            /^https:\/\/soundcloud\.com\/[^\/]+\/[^\/]+/,
            /^https:\/\/soundcloud\.com\/[^\/]+\/sets\/[^\/]+/,
            /^https:\/\/m\.soundcloud\.com\/[^\/]+\/[^\/]+/
        ];
        
        return patterns.some(pattern => pattern.test(url));
    }

    // SoundCloud oEmbed API functie
    async function getSoundCloudEmbed(url) {
        try {
            // Gebruik SoundCloud oEmbed API voor betrouwbare embeds
            const oembedUrl = `https://soundcloud.com/oembed?format=json&url=${encodeURIComponent(url)}&maxheight=166&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false`;
            
            const response = await fetch(oembedUrl);
            if (!response.ok) {
                throw new Error('SoundCloud API fout');
            }
            
            const data = await response.json();
            return {
                success: true,
                html: data.html,
                title: data.title,
                author: data.author_name
            };
        } catch (error) {
            console.error('SoundCloud embed fout:', error);
            // Fallback naar handmatige iframe constructie
            const trackId = url.split('/').pop();
            const embedUrl = `https://w.soundcloud.com/player/?url=${encodeURIComponent(url)}&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=true`;
            
            return {
                success: true,
                html: `<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="${embedUrl}"></iframe>`,
                title: 'SoundCloud Audio',
                author: 'Onbekende artiest'
            };
        }
    }

    // SoundCloud preview functie
    async function showSoundCloudPreview(url) {
        soundcloudPreview.classList.remove('hidden');
        soundcloudPreview.style.opacity = '0';
        soundcloudPreview.style.transform = 'translateY(10px)';
        
        // Toon loading state
        soundcloudInfo.textContent = 'SoundCloud track wordt geladen...';
        soundcloudEmbedContainer.innerHTML = '<div class="text-center p-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div></div>';
        
        try {
            const embedData = await getSoundCloudEmbed(url);
            
            if (embedData.success) {
                // Update container met SoundCloud embed
                soundcloudEmbedContainer.innerHTML = embedData.html;
                soundcloudInfo.textContent = `${embedData.title} - ${embedData.author}`;
            } else {
                throw new Error('Kan SoundCloud embed niet laden');
            }
        } catch (error) {
            console.error('SoundCloud preview fout:', error);
            soundcloudEmbedContainer.innerHTML = '<div class="text-red-600 text-center p-4">❌ Kan SoundCloud track niet laden</div>';
            soundcloudInfo.textContent = 'Fout bij laden van SoundCloud track';
        }
        
        // Animeer het verschijnen
        setTimeout(() => {
            soundcloudPreview.style.opacity = '1';
            soundcloudPreview.style.transform = 'translateY(0)';
        }, 50);
    }

    // SoundCloud URL verwijderen functie (globaal beschikbaar)
    window.removeSoundCloudUrl = function() {
        soundcloudInput.value = ''; // Reset URL input
        soundcloudPreview.classList.add('hidden'); // Verberg preview
        
        // Animatie voor het verwijderen
        soundcloudPreview.style.opacity = '0';
        soundcloudPreview.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            soundcloudPreview.style.opacity = '';
            soundcloudPreview.style.transform = '';
            soundcloudEmbedContainer.innerHTML = ''; // Reset embed container
        }, 300);
    }

    // SoundCloud URL validatie en preview
    if (soundcloudInput) {
        soundcloudInput.addEventListener('input', async function() {
            const url = this.value.trim();
            if (url) {
                if (validateSoundCloudUrl(url)) {
                    await showSoundCloudPreview(url);
                } else {
                    alert('Geen geldige SoundCloud link. Gebruik een link van soundcloud.com');
                    this.value = '';
                }
            }
        });
    }

    // Laad huidige SoundCloud track als die bestaat
    <?php if(!empty($blog->soundcloud_url)): ?>
    if (document.getElementById('currentSoundcloudPreview')) {
        // Laad huidige SoundCloud embed
        (async function() {
            try {
                const embedData = await getSoundCloudEmbed('<?php echo htmlspecialchars($blog->soundcloud_url); ?>');
                const container = document.getElementById('currentSoundcloudEmbedContainer');
                const info = document.getElementById('currentSoundcloudInfo');
                
                if (embedData.success && container) {
                    container.innerHTML = embedData.html;
                    if (info) {
                        info.textContent = `${embedData.title} - ${embedData.author}`;
                    }
                }
            } catch (error) {
                console.error('Fout bij laden huidige SoundCloud track:', error);
                const container = document.getElementById('currentSoundcloudEmbedContainer');
                if (container) {
                    container.innerHTML = '<div class="text-red-600 text-center p-4">❌ Kan huidige SoundCloud track niet laden</div>';
                }
            }
        })();
    }
    <?php endif; ?>

    // Poll functionaliteit
    setupPollFunctionality();
    
    // Category selection enhancement
    setupCategorySelection();
});

// Poll functionaliteit buiten DOMContentLoaded omdat het door onclick wordt aangeroepen
function togglePollSection() {
    const pollSection = document.getElementById('pollSection');
    const enablePollCheckbox = document.getElementById('enablePoll');
    
    if (enablePollCheckbox.checked) {
        pollSection.classList.remove('hidden');
        pollSection.style.opacity = '0';
        pollSection.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            pollSection.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            pollSection.style.opacity = '1';
            pollSection.style.transform = 'translateY(0)';
        }, 10);
    } else {
        pollSection.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        pollSection.style.opacity = '0';
        pollSection.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            pollSection.classList.add('hidden');
        }, 300);
    }
}

function confirmPollDeletion() {
    const deleteCheckbox = document.getElementById('deletePoll');
    const pollInputs = document.querySelectorAll('#pollSection input[type="text"], #pollSection textarea');
    
    if (deleteCheckbox.checked) {
        if (confirm('Weet je zeker dat je deze poll wilt verwijderen? Alle stemmen gaan verloren.')) {
            // Disable poll inputs om verwarring te voorkomen
            pollInputs.forEach(input => {
                input.disabled = true;
                input.style.opacity = '0.5';
            });
        } else {
            deleteCheckbox.checked = false;
        }
    } else {
        // Re-enable inputs
        pollInputs.forEach(input => {
            input.disabled = false;
            input.style.opacity = '1';
        });
    }
}

function setupPollFunctionality() {
    const pollQuestion = document.getElementById('pollQuestion');
    const pollOptionA = document.getElementById('pollOptionA');
    const pollOptionB = document.getElementById('pollOptionB');
    const previewQuestion = document.getElementById('previewQuestion');
    const previewOptionA = document.getElementById('previewOptionA');
    const previewOptionB = document.getElementById('previewOptionB');

    // Real-time preview updates
    function updatePollPreview() {
        const question = pollQuestion.value.trim();
        const optionA = pollOptionA.value.trim();
        const optionB = pollOptionB.value.trim();

        previewQuestion.textContent = question || 'Je poll vraag verschijnt hier...';
        previewOptionA.textContent = optionA || 'Optie A verschijnt hier...';
        previewOptionB.textContent = optionB || 'Optie B verschijnt hier...';
    }

    // Event listeners voor live preview
    if (pollQuestion) pollQuestion.addEventListener('input', updatePollPreview);
    if (pollOptionA) pollOptionA.addEventListener('input', updatePollPreview);
    if (pollOptionB) pollOptionB.addEventListener('input', updatePollPreview);
}

// Category selection functionality
function setupCategorySelection() {
    const categorySelect = document.getElementById('category_id');
    
    if (categorySelect) {
        // Add visual feedback for category selection
        categorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const categoryColor = selectedOption.getAttribute('data-color') || '#3B82F6';
            
            // Add subtle visual feedback
            if (selectedOption.value) {
                this.style.borderLeftColor = categoryColor;
                this.style.borderLeftWidth = '4px';
                
                // Optional: Show a small colored indicator
                let indicator = this.parentElement.querySelector('.category-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.className = 'category-indicator absolute top-4 left-4 w-3 h-3 rounded-full transition-all duration-300';
                    this.parentElement.appendChild(indicator);
                }
                indicator.style.backgroundColor = categoryColor;
                indicator.style.opacity = '1';
            } else {
                this.style.borderLeftColor = '';
                this.style.borderLeftWidth = '';
                
                const indicator = this.parentElement.querySelector('.category-indicator');
                if (indicator) {
                    indicator.style.opacity = '0';
                }
            }
        });
        
        // Trigger initial state
        categorySelect.dispatchEvent(new Event('change'));
    }
}
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 