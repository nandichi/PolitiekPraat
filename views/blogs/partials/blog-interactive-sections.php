    <!-- Enhanced Interactive Actions Section -->
    <section class="relative py-12 sm:py-16 bg-gradient-to-br from-gray-50 via-white to-blue-50/30">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000000" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        </div>
        
        <div class="container mx-auto px-4 relative">
            <div class="max-w-6xl mx-auto">
                
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Interacteer met dit artikel
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Laat je stem horen en ontdek verschillende perspectieven op dit onderwerp
                    </p>
                </div>

                <!-- Enhanced Action Cards Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    
                    <!-- Enhanced Like Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-pink-500/5 to-red-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-bold text-gray-900" id="likeCountDisplay"><?php echo $blog->likes; ?></span>
                                    <span class="text-sm text-gray-500">likes</span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Vind je dit interessant?</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Laat anderen weten dat je dit artikel waardevol vindt door een like te geven
                            </p>
                            
                            <button id="likeButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Like deze blog">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Artikel leuk vinden</span>
                                <!-- Heart particles voor animatie -->
                                <div class="like-particles absolute inset-0 pointer-events-none">
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Bias Analysis Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-indigo-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-medium">
                                        AI-Powered
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Politieke Bias Analyse</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Laat AI de politieke oriëntatie en mogelijke bias in dit artikel analyseren
                            </p>
                            
                            <button id="biasButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Analyseer politieke bias">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Analyseer Bias</span>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Party Perspective Card -->
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden sm:col-span-2 lg:col-span-1">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 via-red-500/5 to-orange-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-medium">
                                        15 Partijen
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Partijleider Reacties</h3>
                            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                Ontdek hoe verschillende partijleiders op dit onderwerp zouden reageren
                            </p>
                            
                            <button id="partyPerspectiveButton" 
                                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    data-slug="<?php echo $blog->slug; ?>"
                                    aria-label="Bekijk partij perspectieven">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Bekijk Reacties</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Enhanced Share Section -->
                <div class="relative">
                    <!-- Decorative Divider -->
                    <div class="flex items-center justify-center mb-12">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        <div class="px-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    </div>

                    <!-- Share Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Deel dit artikel</h3>
                        <p class="text-gray-600">Verspreidt waardevolle politieke inzichten</p>
                    </div>

                    <!-- Enhanced Share Buttons -->
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        
                        <!-- Twitter/X Enhanced -->
                        <button onclick="window.open('https://twitter.com/intent/tweet?text=<?php echo urlencode($blog->title); ?>&url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')" 
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-sky-500 to-blue-500 text-white rounded-2xl hover:from-sky-600 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-sky-400 to-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Twitter</div>
                                    <div class="text-xs text-sky-100">Deel op X</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- LinkedIn Enhanced -->
                        <button onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">LinkedIn</div>
                                    <div class="text-xs text-blue-100">Professioneel</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Facebook Enhanced -->
                        <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-800 to-indigo-800 text-white rounded-2xl hover:from-blue-900 hover:to-indigo-900 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Facebook</div>
                                    <div class="text-xs text-blue-100">Sociale media</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- WhatsApp Enhanced -->
                        <button onclick="window.open('https://wa.me/?text=<?php echo urlencode($blog->title . ' - ' . URLROOT . '/blogs/' . $blog->slug); ?>', '_blank')"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">WhatsApp</div>
                                    <div class="text-xs text-green-100">Berichten</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Copy Link Enhanced -->
                        <button onclick="navigator.clipboard.writeText('<?php echo URLROOT . '/blogs/' . $blog->slug; ?>').then(() => showNotification('Link gekopieerd!', 'success'))"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-2xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-500 to-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Kopiëren</div>
                                    <div class="text-xs text-gray-100">Link delen</div>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Instagram Story Enhanced -->
                        <button id="instagramStoryBtn" 
                                onclick="shareToInstagramStory()"
                                class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-pink-500 via-purple-600 to-orange-500 text-white rounded-2xl hover:from-pink-600 hover:via-purple-700 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl overflow-hidden">
                            <!-- Animated Background Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-pink-400 via-purple-500 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            
                            <!-- Content -->
                            <div class="relative flex items-center gap-3">
                                <!-- Instagram Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <div class="hidden sm:block">
                                    <div class="font-semibold text-sm">Story</div>
                                    <div class="text-xs text-pink-100">Instagram</div>
                                </div>
                            </div>
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Comments Section -->
    <section class="py-16 sm:py-20 bg-white" style="display: none;">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h2 class="flex items-center justify-center text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        <span>Reacties</span>
                        <span class="inline-flex items-center bg-gradient-to-r from-primary to-secondary text-white text-lg px-3 py-1 rounded-full ml-3" id="commentCount">
                            <?php echo count($comments); ?>
                        </span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Deel je mening over dit artikel. Je kunt anoniem reageren zonder account aan te maken.
                    </p>
                </div>

                <!-- Comment Form -->
                <div class="bg-gradient-to-br from-gray-50 to-primary/5 rounded-2xl p-6 mb-12 border border-gray-200">
                    <form method="POST" action="<?php echo URLROOT; ?>/blogs/<?php echo $blog->slug; ?>#comments" class="space-y-6">
                        
                        <?php if ($comment_error): ?>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div class="text-red-800 text-sm">
                                        <?php echo htmlspecialchars($comment_error); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <!-- Anonymous Name Field -->
                            <div>
                                <label for="anonymous_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-5 h-5 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Jouw naam *
                                </label>
                                <input type="text" 
                                       name="anonymous_name" 
                                       id="anonymous_name" 
                                       placeholder="Bijv. Jan, Marie, Alex..."
                                       maxlength="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200"
                                       value="<?php echo isset($_POST['anonymous_name']) ? htmlspecialchars($_POST['anonymous_name']) : ''; ?>"
                                       required>
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Geen account nodig - reageer direct anoniem
                                </p>
                            </div>
                        <?php else: ?>
                            <!-- Logged in user info -->
                                                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold"><?php echo substr($_SESSION['username'], 0, 1); ?></span>
                                    </div>
                                    <div>
                                                                            <p class="text-sm font-medium text-primary">Reactie plaatsen als:</p>
                                    <p class="text-primary/70"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Comment Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Jouw reactie *
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="5"
                                      placeholder="Deel je mening over dit artikel. Houd het respectvol en constructief..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 resize-none"
                                      required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Minimaal 10 karakters</p>
                                <span class="text-sm text-gray-400" id="charCount">0/1000</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary-dark hover:to-secondary-dark transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Plaats Reactie
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div id="comments">
                    <?php if(empty($comments)): ?>
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nog geen reacties</h3>
                            <p class="text-gray-600 mb-6">Wees de eerste die reageert op dit artikel!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php 
                            $totalComments = count($comments);
                            $showLoadMore = $totalComments > 5;
                            
                            foreach($comments as $index => $comment): 
                                // Check if comment is liked by creator for special styling
                                $isLikedByCreator = $comment->is_liked_by_author;
                                
                                // Add class to hide comments after first 5
                                $hiddenClass = $index >= 5 ? 'hidden comment-hidden' : '';
                            ?>
                                <div class="<?php echo $isLikedByCreator ? 'bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border-2 border-red-300 shadow-xl ring-2 ring-red-200 ring-opacity-50' : 'bg-white border border-gray-200 shadow-sm'; ?> rounded-2xl overflow-hidden transition-all duration-500 <?php echo $isLikedByCreator ? 'transform hover:scale-[1.02] hover:shadow-2xl' : 'hover:shadow-md'; ?> <?php echo $hiddenClass; ?>">
                                    <!-- Comment Header -->
                                    <div class="px-6 py-4 <?php echo $isLikedByCreator ? 'bg-gradient-to-r from-red-100 via-pink-100 to-red-100 border-b border-red-200' : 'bg-gradient-to-r from-gray-50 to-primary/5 border-b border-gray-100'; ?>">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <!-- Avatar -->
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo $comment->author_type === 'anonymous' ? 'bg-gradient-to-r from-gray-400 to-gray-500' : 'bg-gradient-to-r from-primary to-secondary'; ?>">
                                                    <span class="text-white font-semibold text-sm">
                                                        <?php echo substr($comment->author_name, 0, 1); ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Author Info -->
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-900">
                                                            <?php echo htmlspecialchars($comment->author_name); ?>
                                                        </span>
                                                        <?php if($comment->author_type === 'anonymous'): ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                Gast
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Lid
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="text-sm text-gray-500">
                                                        <?php 
                                                        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
                                                        echo $formatter->format(strtotime($comment->created_at)); 
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Button -->
                                            <?php if(isset($_SESSION['user_id']) && $comment->author_type === 'registered' && 
                                                    ($_SESSION['user_id'] == $comment->user_id || $_SESSION['is_admin'])): ?>
                                                <form method="POST" 
                                                      action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                      class="inline">
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen?')"
                                                            title="Reactie verwijderen">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php elseif(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                                <form method="POST" 
                                                      action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                      class="inline">
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen? (Admin actie)')"
                                                            title="Reactie verwijderen (Admin)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Comment Content -->
                                    <div class="px-6 py-5">
                                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed mb-4">
                                            <?php echo nl2br(htmlspecialchars($comment->content)); ?>
                                        </div>
                                        
                                        <!-- Comment Actions -->
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <!-- Leuk Gevonden Door Auteur Badge -->
                                                <?php if ($comment->is_liked_by_author): ?>
                                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 border border-red-400 rounded-full shadow-lg">
                                                        <svg class="w-4 h-4 text-white animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                        </svg>
                                                        <span class="text-white text-sm font-bold">Leuk gevonden door auteur</span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Like Count (if > 0) -->
                                                <?php if ($comment->likes_count > 0): ?>
                                                    <span class="text-gray-500 text-sm">
                                                        <?php echo $comment->likes_count; ?> <?php echo $comment->likes_count == 1 ? 'like' : 'likes'; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Author Like Button -->
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $blog->author_id): ?>
                                                <button type="button" 
                                                        class="comment-like-btn inline-flex items-center gap-2 px-3 py-2 rounded-lg transition-all hover:bg-gray-50 <?php echo $comment->is_liked_by_author ? 'text-red-600' : 'text-gray-500 hover:text-red-600'; ?>"
                                                        data-comment-id="<?php echo $comment->id; ?>"
                                                        data-liked="<?php echo $comment->is_liked_by_author ? 'true' : 'false'; ?>"
                                                        title="<?php echo $comment->is_liked_by_author ? 'Reactie niet meer leuk vinden' : 'Reactie leuk vinden'; ?>">
                                                    <svg class="w-5 h-5 transition-all <?php echo $comment->is_liked_by_author ? 'scale-110' : ''; ?>" 
                                                         fill="<?php echo $comment->is_liked_by_author ? 'currentColor' : 'none'; ?>" 
                                                         stroke="currentColor" 
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium">
                                                        <?php echo $comment->is_liked_by_author ? 'Leuk gevonden' : 'Leuk vinden'; ?>
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if($showLoadMore): ?>
                                <!-- Load More Button -->
                                <div class="text-center pt-6" id="loadMoreContainer">
                                    <button 
                                        id="loadMoreComments" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary-dark hover:to-secondary-dark transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-primary"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        <span>Bekijk meer reacties</span>
                                        <span class="bg-white/20 px-2 py-1 rounded-full text-sm">
                                            <?php echo $totalComments - 5; ?>
                                        </span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Related Blogs Section -->
    <section class="relative py-20 sm:py-28 bg-gradient-to-br from-primary/5 via-white to-secondary/5 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl opacity-20 -translate-y-32 translate-x-32"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl opacity-20 translate-y-32 -translate-x-32"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 sm:mb-20">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-primary/10 via-primary/5 to-secondary/10 border border-primary/20 rounded-full text-sm sm:text-base font-bold text-primary mb-6 sm:mb-8 shadow-lg backdrop-blur-sm">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Meer Politiekpraat</span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-gray-900 mb-6 sm:mb-8 leading-tight">
                        <span class="bg-gradient-to-r from-primary via-primary-dark to-secondary bg-clip-text text-transparent">
                            Verdiep je kennis
                        </span>
                    </h2>

                    <!-- Subtitle -->
                    <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Ontdek meer waardevolle politieke inzichten en analyses die je helpen de complexe wereld van de politiek beter te begrijpen
                    </p>
                </div>

                <!-- Related Blogs Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                    <?php 
                    // Haal andere blogs op (maximaal 7 om er zeker van te zijn dat we 6 hebben na filtering)
                    $otherBlogs = (new BlogController())->getAll(7);
                    $count = 0;
                    foreach ($otherBlogs as $relatedBlog): 
                        if ($relatedBlog->slug !== $blog->slug && $count < 6): 
                            $count++;
                    ?>
                        <article class="group relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100/50 overflow-hidden hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-4 hover:scale-[1.02]">
                            <!-- Gradient Border Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 via-transparent to-secondary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"></div>
                            
                            <div class="relative z-10">
                                <!-- Image Container -->
                                <div class="relative overflow-hidden rounded-t-3xl">
                                    <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" class="block">
                                        <?php if ($relatedBlog->image_path): ?>
                                            <div class="relative h-48 sm:h-56 lg:h-64">
                                                <img src="<?php echo getBlogImageUrl($relatedBlog->image_path); ?>" 
                                                     alt="<?php echo htmlspecialchars($relatedBlog->title); ?>"
                                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                                <!-- Overlay Gradient -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <!-- Shine Effect -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 opacity-0 group-hover:opacity-100 transition-all duration-700 transform -translate-x-full group-hover:translate-x-full"></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="h-48 sm:h-56 lg:h-64 bg-gradient-to-br from-primary/15 via-primary/5 to-secondary/15 flex items-center justify-center relative overflow-hidden">
                                                <!-- Animated Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-0 left-0 w-32 h-32 bg-primary rounded-full blur-xl opacity-50 group-hover:scale-150 transition-transform duration-1000"></div>
                                                    <div class="absolute bottom-0 right-0 w-24 h-24 bg-secondary rounded-full blur-xl opacity-50 group-hover:scale-150 transition-transform duration-1000 delay-200"></div>
                                                </div>
                                                <svg class="w-16 sm:w-20 h-16 sm:h-20 text-primary/60 relative z-10 group-hover:text-primary transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </a>

                                    <!-- New Badge -->
                                    <?php 
                                    $published_time = strtotime($relatedBlog->published_at);
                                    $twelve_hours_ago = time() - (12 * 3600);
                                    if ($published_time > $twelve_hours_ago): 
                                    ?>
                                        <div class="absolute top-4 sm:top-6 right-4 sm:right-6 z-20">
                                            <div class="relative">
                                                <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-xs sm:text-sm font-bold shadow-xl border border-white/20">
                                                    <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                                    NIEUW
                                                </span>
                                                <!-- Glow Effect -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary rounded-full blur opacity-75 animate-pulse"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Content -->
                                <div class="p-6 sm:p-8">
                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="relative">
                                                <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-full overflow-hidden border-2 border-primary/20 shadow-md">
                                                    <?php
                                                    $relatedProfilePhotoData = getProfilePhotoUrl($relatedBlog->author_photo ?? null, $relatedBlog->author_name);
                                                    if ($relatedProfilePhotoData['type'] === 'img'): ?>
                                                        <img src="<?php echo htmlspecialchars($relatedProfilePhotoData['value']); ?>" 
                                                             alt="<?php echo htmlspecialchars($relatedBlog->author_name); ?>"
                                                             class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary text-white font-bold text-sm">
                                                            <?php echo htmlspecialchars($relatedProfilePhotoData['value']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-full blur opacity-50"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm sm:text-base font-semibold text-gray-700 truncate max-w-24 sm:max-w-32">
                                                    <?php echo htmlspecialchars($relatedBlog->author_name); ?>
                                                </p>
                                                <p class="text-xs sm:text-sm text-gray-500">
                                                    <?php 
                                                        $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
                                                        echo str_replace('.', '', $formatter->format(strtotime($relatedBlog->published_at))); 
                                                    ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Likes -->
                                        <div class="flex items-center space-x-1 px-3 py-1 bg-gray-50 rounded-full">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600">
                                                <?php echo (isset($relatedBlog->likes) && $relatedBlog->likes > 0) ? $relatedBlog->likes : '0'; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" class="block group/title">
                                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 line-clamp-2 group-hover:text-primary transition-colors duration-300 leading-tight">
                                            <?php echo htmlspecialchars($relatedBlog->title); ?>
                                        </h3>
                                    </a>
                                    
                                    <!-- Summary -->
                                    <p class="text-gray-600 text-sm sm:text-base line-clamp-3 mb-6 leading-relaxed">
                                        <?php echo htmlspecialchars($relatedBlog->summary); ?>
                                    </p>
                                    
                                    <!-- Read More Button -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="<?php echo URLROOT . '/blogs/' . $relatedBlog->slug; ?>" 
                                           class="group/btn inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary/10 to-secondary/10 hover:from-primary hover:to-secondary text-primary hover:text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                            <span class="text-sm sm:text-base">Lees artikel</span>
                                            <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>

                                        <!-- Reading Time -->
                                        <div class="flex items-center text-gray-400 text-xs sm:text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>3 min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-16 sm:mt-20">
                    <div class="inline-flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="group relative inline-flex items-center px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-primary via-primary-dark to-secondary text-white font-bold text-base sm:text-lg rounded-full shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:scale-105 overflow-hidden">
                            <!-- Animated Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary via-primary to-primary-dark opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 opacity-0 group-hover:opacity-100 transition-all duration-700 transform -translate-x-full group-hover:translate-x-full"></div>
                            
                            <span class="relative z-10 mr-3">Ontdek alle artikelen</span>
                            <svg class="relative z-10 w-5 sm:w-6 h-5 sm:h-6 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <div class="flex items-center text-gray-600 text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Wekelijks nieuwe inzichten</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Bias Analysis Modal - Uitgebreide versie -->
<div id="biasModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="hideBiasModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-start justify-center min-h-screen p-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full transform transition-all">
            <!-- Modal Header met thema kleuren -->
            <div id="biasModalHeader" class="bg-gradient-to-r from-primary-dark via-primary to-primary-dark px-5 py-4 rounded-t-2xl relative overflow-hidden">
                <!-- Animated background effect -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-1/4 w-24 h-24 bg-secondary rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 w-24 h-24 bg-primary-light rounded-full filter blur-3xl"></div>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/15 backdrop-blur rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Politieke Spectrum Analyse</h3>
                            <p class="text-blue-200 text-xs">Multi-dimensionale analyse</p>
                        </div>
                    </div>
                    <button id="closeBiasModal" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-5 bg-gray-50">
                <!-- Loading State -->
                <div id="biasLoading" class="text-center py-12">
                    <div class="relative inline-flex">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full"></div>
                        <div class="w-12 h-12 border-4 border-secondary rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                    </div>
                    <p class="text-gray-700 font-medium mt-4">Artikel wordt geanalyseerd...</p>
                    <p class="text-gray-500 text-sm mt-1">Dit kan 10-15 seconden duren</p>
                    <div class="flex justify-center gap-1.5 mt-3">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="biasError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h4 class="text-red-800 font-bold mb-1">Analyse mislukt</h4>
                        <p id="biasErrorMessage" class="text-red-600 text-sm"></p>
                    </div>
                </div>
                
                <!-- Results -->
                <div id="biasResults" class="hidden space-y-4">
                    
                    <!-- Overall Orientation Hero Card -->
                    <div id="overallOrientationCard" class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-dark rounded-xl p-5 text-white overflow-hidden">
                        <div class="absolute inset-0 opacity-15">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary rounded-full filter blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-primary-light rounded-full filter blur-3xl"></div>
                        </div>
                        <div class="relative">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-1">Politieke Orientatie</p>
                                    <h3 id="orientationBadge" class="text-2xl font-bold"></h3>
                            </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <p class="text-blue-200 text-xs uppercase tracking-wider">Zekerheid</p>
                                        <p class="text-xl font-bold"><span id="confidenceText">--</span>%</p>
                        </div>
                                    <div class="w-14 h-14 relative">
                                        <svg class="w-14 h-14 transform -rotate-90">
                                            <circle cx="28" cy="28" r="24" stroke="rgba(255,255,255,0.2)" stroke-width="4" fill="none"/>
                                            <circle id="confidenceCircle" cx="28" cy="28" r="24" stroke="url(#confidenceGradient)" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="151" stroke-dashoffset="151" class="transition-all duration-1000"/>
                                        </svg>
                                        <svg class="absolute top-0 left-0 w-0 h-0">
                                            <defs>
                                                <linearGradient id="confidenceGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#c41e3a"/>
                                                    <stop offset="100%" stop-color="#d63856"/>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p id="overallSummary" class="text-blue-100 mt-3 text-sm leading-relaxed"></p>
                    </div>
                </div>
                
                    <!-- Spectrum Breakdown -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            Politiek Spectrum (5 Assen)
                        </h4>
                        
                        <div class="space-y-4">
                            <!-- Economisch -->
                            <div class="spectrum-item" data-spectrum="economisch">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Economisch</span>
                                    <span id="economischLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-secondary via-gray-200 to-primary rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                </div>
                                    <div id="economischMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                            </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Links</span>
                                    <span>Rechts</span>
                        </div>
                                <p id="economischToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- Sociaal-cultureel -->
                            <div class="spectrum-item" data-spectrum="sociaal_cultureel">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Sociaal-cultureel</span>
                                    <span id="sociaal_cultureel_label" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-pink-400 via-gray-200 to-amber-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="sociaal_cultureel_marker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Progressief</span>
                                    <span>Conservatief</span>
                                </div>
                                <p id="sociaal_cultureel_toelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- EU/Internationaal -->
                            <div class="spectrum-item" data-spectrum="eu_internationaal">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">EU / Internationaal</span>
                                    <span id="eu_internationaal_label" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                            </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-blue-500 via-gray-200 to-orange-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                            </div>
                                    <div id="eu_internationaal_marker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                            </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Pro-EU</span>
                                    <span>Nationalistisch</span>
                        </div>
                                <p id="eu_internationaal_toelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                    </div>
                    
                            <!-- Klimaat -->
                            <div class="spectrum-item" data-spectrum="klimaat">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Klimaat</span>
                                    <span id="klimaatLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-green-500 via-gray-200 to-gray-500 rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="klimaatMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Groen</span>
                                    <span>Economie-eerst</span>
                                </div>
                                <p id="klimaatToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                            </div>
                            
                            <!-- Immigratie -->
                            <div class="spectrum-item" data-spectrum="immigratie">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-gray-700">Immigratie</span>
                                    <span id="immigratieLabel" class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"></span>
                                </div>
                                <div class="relative h-2.5 bg-gradient-to-r from-teal-400 via-gray-200 to-secondary rounded-full overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-0.5 h-full bg-gray-400/50"></div>
                                    </div>
                                    <div id="immigratieMarker" class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                                    <span>Open</span>
                                    <span>Restrictief</span>
                                </div>
                                <p id="immigratieToelichting" class="text-xs text-gray-500 mt-1 italic hidden"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Two Column Layout for Retoriek & Schrijfstijl -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Retorische Analyse -->
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                Retorische Analyse
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Toon</span>
                                    <span id="retoriekToon" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Framing</span>
                                    <span id="retoriekFraming" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Stijl</span>
                                    <span id="retoriekStijl" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="pt-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Objectiviteit</span>
                                        <span id="retoriekObjectiviteitValue" class="text-xs font-bold text-gray-800"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="retoriekObjectiviteitBar" class="h-full bg-gradient-to-r from-secondary to-secondary-light transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <p id="retoriekToelichting" class="text-xs text-gray-500 mt-2 italic hidden"></p>
                            </div>
                        </div>
                        
                        <!-- Schrijfstijl Analyse -->
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Schrijfstijl
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Feitelijk vs Mening</span>
                                        <span id="schrijfstijlFeitelijkValue" class="text-xs text-gray-500"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="schrijfstijlFeitelijkBar" class="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-700" style="width: 50%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                        <span>Feitelijk</span>
                                        <span>Mening</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Emotionele lading</span>
                                        <span id="schrijfstijlEmotieValue" class="text-xs text-gray-500"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="schrijfstijlEmotieBar" class="h-full bg-gradient-to-r from-gray-400 to-secondary transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Bronverwijzingen</span>
                                    <span id="schrijfstijlBronnen" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-xs text-gray-600">Argumentatie</span>
                                    <span id="schrijfstijlArgumentatie" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Partij Matching -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Partij Matching
                        </h4>
                        
                        <div class="space-y-3">
                            <!-- Best Match -->
                            <div class="bg-gradient-to-r from-primary/5 to-primary/10 rounded-lg p-3 border border-primary/20">
                                <p class="text-xs text-primary uppercase tracking-wider font-medium mb-0.5">Best passende partij</p>
                                <p id="partijBestMatch" class="text-base font-bold text-primary-dark"></p>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-3">
                                <!-- Zou onderschrijven -->
                                <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                    <p class="text-xs text-green-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Zou onderschrijven
                                    </p>
                                    <div id="partijOnderschrijven" class="flex flex-wrap gap-1.5"></div>
                                </div>
                                
                                <!-- Zou afwijzen -->
                                <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                                    <p class="text-xs text-red-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Zou afwijzen
                                    </p>
                                    <div id="partijAfwijzen" class="flex flex-wrap gap-1.5"></div>
                                </div>
                            </div>
                            
                            <p id="partijToelichting" class="text-xs text-gray-600 italic hidden"></p>
                        </div>
                    </div>
                    
                    <!-- Doelgroep & Kernpunten -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Doelgroep -->
                        <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-xl p-4 border border-primary/20">
                            <h4 class="text-sm font-bold text-primary-dark mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Doelgroep
                            </h4>
                            <div class="space-y-1">
                                <p id="doelgroepPrimair" class="text-primary-dark font-medium text-sm"></p>
                                <p id="doelgroepDemografisch" class="text-xs text-gray-600"></p>
                                <p id="doelgroepPolitiek" class="text-xs text-gray-500 italic"></p>
                            </div>
                        </div>
                        
                        <!-- Kernpunten -->
                        <div class="bg-gradient-to-br from-secondary/5 to-secondary/10 rounded-xl p-4 border border-secondary/20">
                            <h4 class="text-sm font-bold text-secondary-dark mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Kernpunten
                            </h4>
                            <ul id="kernpuntenList" class="space-y-1"></ul>
                        </div>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="bg-gray-100 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-gray-600">
                                <strong class="text-gray-700">Disclaimer:</strong> Deze analyse dient als indicatie. Politieke standpunten zijn complex - gebruik de resultaten als startpunt voor reflectie.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-white px-5 py-3 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between items-center">
                    <p class="text-xs text-gray-400">PolitiekPraat Analyse</p>
                    <div class="flex gap-2">
                        <button id="retryBiasAnalysis" class="px-3 py-1.5 text-secondary hover:text-secondary-dark hover:bg-secondary/5 rounded-lg transition-colors hidden text-sm font-medium">
                            Opnieuw
                        </button>
                        <button id="closeBiasModalFooter" class="px-4 py-1.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors text-sm font-medium">
                        Sluiten
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Party Perspective Modal - Uitgebreide versie -->
<div id="partyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="hidePartyModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-start justify-center min-h-screen p-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full transform transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-dark via-primary to-primary-dark px-5 py-4 rounded-t-2xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-1/4 w-24 h-24 bg-secondary rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 w-24 h-24 bg-primary-light rounded-full filter blur-3xl"></div>
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/15 backdrop-blur rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Partijleider Reacties</h3>
                            <p class="text-blue-200 text-xs">Kies een partijleider</p>
                        </div>
                    </div>
                    <button id="closePartyModal" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-5 bg-gray-50">
                <!-- Party Selection Grid -->
                <div id="partySelectionGrid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-4">
                    <?php foreach ($dbParties as $partyKey => $party): ?>
                    <button type="button" class="party-select-btn" data-party="<?php echo htmlspecialchars($partyKey); ?>">
                        <div class="p-3 border border-gray-200 rounded-xl hover:border-primary hover:bg-primary/5 transition-all cursor-pointer group text-center">
                            <img src="<?php echo htmlspecialchars($party['logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($partyKey); ?>" 
                                 class="w-12 h-12 mx-auto mb-1.5 object-contain"
                                 onerror="this.style.display='none'">
                            <h4 class="font-bold text-xs text-gray-900 group-hover:text-primary"><?php echo htmlspecialchars($partyKey); ?></h4>
                            <p class="text-xs text-gray-500 mt-0.5 leader-name truncate"><?php echo htmlspecialchars($party['leader']); ?></p>
                            <?php if (!empty($party['leader_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($party['leader_photo']); ?>" alt="<?php echo htmlspecialchars($party['leader']); ?>" class="leader-photo w-8 h-8 rounded-full mx-auto mt-1.5 object-cover border border-gray-200 hidden">
                            <?php endif; ?>
                        </div>
                    </button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Loading State -->
                <div id="partyLoading" class="hidden text-center py-12">
                    <div class="relative inline-flex">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full"></div>
                        <div class="w-12 h-12 border-4 border-secondary rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                    </div>
                    <p class="text-gray-700 font-medium mt-4">Reactie wordt gegenereerd...</p>
                    <p class="text-gray-500 text-sm mt-1">Dit kan 10-15 seconden duren</p>
                    <div class="flex justify-center gap-1.5 mt-3">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="partyError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h4 class="text-red-800 font-bold mb-1">Genereren mislukt</h4>
                        <p id="partyErrorMessage" class="text-red-600 text-sm"></p>
                    </div>
                </div>
                
                <!-- Results - Uitgebreide versie -->
                <div id="partyResults" class="hidden space-y-4">
                    
                    <!-- Leider Hero Card -->
                    <div id="leaderHeroCard" class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-dark rounded-xl p-5 text-white overflow-hidden">
                        <div class="absolute inset-0 opacity-15">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary rounded-full filter blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-primary-light rounded-full filter blur-3xl"></div>
                        </div>
                        <div class="relative flex items-center gap-4">
                            <img id="partyResultPhoto" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-white/30 shadow-lg">
                            <div class="flex-1">
                                <p id="partyResultName" class="text-xl font-bold"></p>
                                <p id="partyResultLeader" class="text-blue-200 text-sm"></p>
                            </div>
                            <img id="partyResultLogo" src="" alt="" class="w-12 h-12 object-contain opacity-80">
                        </div>
                    </div>
                    
                    <!-- Reactie Card -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Reactie
                        </h4>
                        <div class="space-y-3">
                            <p id="partyResultOpening" class="text-lg font-medium text-gray-900 italic border-l-4 border-secondary pl-3"></p>
                            <div id="partyResultContent" class="text-gray-700 text-sm leading-relaxed"></div>
                            <p id="partyResultAfsluiting" class="text-sm text-gray-600 font-medium border-l-4 border-primary pl-3"></p>
                        </div>
                    </div>
                    
                    <!-- Toon & Sentiment Analyse -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Toon Analyse
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Toon</span>
                                    <span id="partyToon" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Emotie</span>
                                    <span id="partyEmotie" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Sentiment</span>
                                        <span id="partySentimentValue" class="text-xs font-bold text-gray-800"></span>
                            </div>
                                    <div class="h-2 bg-gradient-to-r from-red-400 via-gray-200 to-green-400 rounded-full overflow-hidden relative">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-0.5 h-full bg-gray-400/50"></div>
                        </div>
                                        <div id="partySentimentMarker" class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white border-2 border-primary-dark rounded-full shadow transition-all duration-700" style="left: 50%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                        <span>Negatief</span>
                                        <span>Positief</span>
                                    </div>
                                </div>
                    </div>
                </div>
                
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Authenticiteit
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">Authenticiteit Score</span>
                                        <span id="partyAuthenticiteit" class="text-xs font-bold text-gray-800"></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="partyAuthenticiteitBar" class="h-full bg-gradient-to-r from-secondary to-secondary-light transition-all duration-700" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-xs text-gray-600">Retorische Stijl</span>
                                    <span id="partyRetoriek" class="text-xs font-medium text-gray-800 px-2 py-0.5 bg-gray-100 rounded"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Standpunten -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Standpunten
                        </h4>
                        
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Kernpunten</p>
                            <ul id="partyKernpunten" class="space-y-1"></ul>
                    </div>
                        
                        <div class="grid md:grid-cols-2 gap-3">
                            <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                <p class="text-xs text-green-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Eens met artikel
                                </p>
                                <ul id="partyEens" class="space-y-1"></ul>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                                <p class="text-xs text-red-700 uppercase tracking-wider font-medium mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Oneens met artikel
                                </p>
                                <ul id="partyOneens" class="space-y-1"></ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Partij Context -->
                    <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-xl p-4 border border-primary/20">
                        <h4 class="text-sm font-bold text-primary-dark mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Partij Context
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Relevante Beloftes</p>
                                <ul id="partyBeloftes" class="space-y-1"></ul>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Voorgestelde Oplossing</p>
                                <p id="partyOplossing" class="text-sm text-gray-700"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="bg-gray-100 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-gray-600">
                                <strong class="text-gray-700">Disclaimer:</strong> Dit is een AI-gegenereerde simulatie van hoe deze politicus zou kunnen reageren. Het is geen echte uitspraak.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-white px-5 py-3 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between items-center">
                    <button id="backToPartySelection" class="px-3 py-1.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors hidden text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Terug
                    </button>
                    <p class="text-xs text-gray-400">PolitiekPraat Analyse</p>
                    <button id="closePartyModalFooter" class="px-4 py-1.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors text-sm font-medium">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isAdmin()): ?>
<div class="fixed bottom-4 right-4 opacity-70 hover:opacity-100 z-50">
    <a href="?debug_photo=1" class="text-xs bg-gray-800 text-white py-1 px-2 rounded hover:bg-gray-700">
        Debug Photo
    </a>
</div>
<?php endif; ?>

<?php require_once 'views/templates/footer.php'; ?>
