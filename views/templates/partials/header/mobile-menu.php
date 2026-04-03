<!-- Mobile Menu - Fixed overlay and animations -->
    <div class="md:hidden fixed inset-0 z-[60] transition-all duration-300 opacity-0 invisible pointer-events-none" 
         id="mobile-menu-overlay">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-md transition-opacity duration-300"
             onclick="closeMobileMenu()"></div>
        
        <!-- Menu Content - Fixed positioning and animations -->
        <div class="absolute right-0 top-0 h-full w-full max-w-xs bg-white 
                    shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300 ease-out
                    border-l border-gray-200"
             id="mobile-menu-content">
            
            <!-- Mobile menu content -->
            <div class="p-4 space-y-4">
                <!-- Close Button -->
                <div class="flex justify-end">
                    <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg 
                                transition-all duration-300 group" 
                            onclick="closeMobileMenu()" aria-label="Menu sluiten">
                        <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:rotate-90" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation Links - Verbeterde gegroepeerde structuur -->
                <nav class="space-y-1">
                    <!-- Home - altijd bovenaan -->
                    <a href="<?php echo URLROOT; ?>/" 
                       class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                              hover:bg-primary/5 group">
                        <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-primary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="font-medium">Home</span>
                    </a>

                    <!-- Sectie Header: Verkiezingen & Politiek -->
                    <div class="px-3 py-2">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-secondary rounded-full mr-2"></div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Verkiezingen & Politiek</h3>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/partijmeter" 
                       class="flex items-center text-gray-700 hover:text-secondary p-3 rounded-lg transition-all duration-300 
                              hover:bg-secondary/5 group">
                        <div class="mr-3 p-2 bg-secondary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-secondary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="font-medium flex items-center">
                            PartijMeter
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium 
                                        bg-secondary text-white rounded-full">
                                2025
                            </span>
                        </span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/politiek-kompas" 
                       class="flex items-center text-gray-700 hover:text-accent p-3 rounded-lg transition-all duration-300 
                              hover:bg-orange-500/5 group">
                        <div class="mr-3 p-2 bg-orange-500/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-orange-500/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="font-medium flex items-center">
                            Politiek Kompas
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium 
                                        bg-orange-500 text-white rounded-full">
                                Nieuw
                            </span>
                        </span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/partijen" 
                       class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                              hover:bg-primary/5 group">
                        <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-primary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <span class="font-medium">Partijen</span>
                    </a>


                    <a href="<?php echo URLROOT; ?>/stemwijzer" 
                       class="flex items-center text-gray-700 hover:text-primary-light p-3 rounded-lg transition-all duration-300 
                              hover:bg-primary-light/5 group">
                        <div class="mr-3 p-2 bg-primary-light/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-primary-light/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">StemWijzer</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/themas" 
                       class="flex items-center text-gray-700 hover:text-accent p-3 rounded-lg transition-all duration-300 
                              hover:bg-orange-500/5 group">
                        <div class="mr-3 p-2 bg-orange-500/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-orange-500/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Thema's</span>
                    </a>

                    <!-- Sectie Header: Nieuws & Content -->
                    <div class="px-3 py-2 mt-4">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Nieuws & Content</h3>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                              hover:bg-primary/5 group">
                        <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-primary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Blogs</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/nieuws" 
                       class="flex items-center text-gray-700 hover:text-secondary p-3 rounded-lg transition-all duration-300 
                              hover:bg-secondary/5 group">
                        <div class="mr-3 p-2 bg-secondary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-secondary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m0 0v10a2 2 0 01-2 2h-5m-4 0V5a2 2 0 014 0v2M7 7h3m-3 4h3m-3 4h3"/>
                            </svg>
                        </div>
                        <span class="font-medium">Nieuws</span>
                    </a>

                    <!-- Sectie Header: Verkiezingsgeschiedenis -->
                    <div class="px-3 py-2 mt-4">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Verkiezingsgeschiedenis</h3>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/amerikaanse-verkiezingen" 
                       class="flex items-center text-gray-700 hover:text-blue-600 p-3 rounded-lg transition-all duration-300 
                              hover:bg-blue-500/5 group">
                        <div class="mr-3 p-2 bg-blue-500/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-blue-500/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <span class="font-medium flex items-center text-sm">
                            Amerikaanse Verkiezingen
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium 
                                        bg-blue-500 text-white rounded-full">
                                USA
                            </span>
                        </span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/nederlandse-verkiezingen" 
                       class="flex items-center text-gray-700 hover:text-orange-600 p-3 rounded-lg transition-all duration-300 
                              hover:bg-orange-500/5 group">
                        <div class="mr-3 p-2 bg-orange-500/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-orange-500/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="font-medium flex items-center text-sm">
                            Nederlandse Verkiezingen
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium 
                                        bg-orange-500 text-white rounded-full">
                                NL
                            </span>
                        </span>
                    </a>

                    <!-- Sectie Header: Informatie -->
                    <div class="px-3 py-2 mt-4">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Informatie</h3>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/over-mij" 
                       class="flex items-center text-gray-700 hover:text-green-600 p-3 rounded-lg transition-all duration-300 
                              hover:bg-green-500/5 group">
                        <div class="mr-3 p-2 bg-green-500/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-green-500/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Over ons</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/contact" 
                       class="flex items-center text-gray-700 hover:text-secondary p-3 rounded-lg transition-all duration-300 
                              hover:bg-secondary/5 group">
                        <div class="mr-3 p-2 bg-secondary/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-secondary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Contact</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/donatie" 
                       class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                              hover:bg-primary/5 group">
                        <div class="mr-3 p-2 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-lg transition-all duration-300 
                                    group-hover:from-primary/20 group-hover:to-secondary/20 group-hover:scale-110">
                            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Doneer</span>
                    </a>
                </nav>

                <!-- Mobile Auth Section - Simplified -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-3 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <div class="w-10 h-10 bg-gradient-to-br from-secondary to-primary/80 rounded-lg 
                                      flex items-center justify-center shadow-lg">
                                <?php
                                // Use dynamic profile photo with session refresh for ALL users (mobile)
                                $mobileProfilePhoto = function_exists('getProfilePhotoWithRefresh') 
                                    ? getProfilePhotoWithRefresh($_SESSION['username'] ?? '') 
                                    : getProfilePhotoUrl($_SESSION['profile_photo'] ?? '', $_SESSION['username'] ?? '');
                                if ($mobileProfilePhoto['type'] === 'img'): 
                                ?>
                                    <img src="<?php echo $mobileProfilePhoto['value']; ?>" 
                                         alt="Profile" class="w-full h-full object-cover rounded-lg">
                                <?php else: ?>
                                    <span class="text-white font-bold text-lg">
                                        <?php echo $mobileProfilePhoto['value']; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-gray-800 font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                <p class="text-xs text-gray-500">Actief lid</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <?php if($_SESSION['is_admin']): ?>
                                <a href="<?php echo URLROOT; ?>/admin" 
                                   class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                                          hover:bg-primary/5 group">
                                    <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                                group-hover:bg-primary/20 group-hover:scale-110">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo URLROOT; ?>/blogs/manage" 
                               class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                                      hover:bg-primary/5 group">
                                <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-primary/20 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Mijn Blogs</span>
                            </a>
                            
                            <a href="<?php echo URLROOT; ?>/profile" 
                               class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                                      hover:bg-primary/5 group">
                                <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-primary/20 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Profiel</span>
                            </a>
                            
                            <a href="<?php echo URLROOT; ?>/logout" 
                               class="flex items-center text-red-600 hover:text-red-700 p-3 rounded-lg transition-all duration-300 
                                     hover:bg-red-50 group">
                                <div class="mr-3 p-2 bg-red-50 rounded-lg transition-all duration-300 
                                            group-hover:bg-red-100 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Uitloggen</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="pt-4 mt-4 border-t border-gray-200 space-y-3">
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="flex items-center text-gray-700 hover:text-primary p-3 rounded-lg transition-all duration-300 
                                  hover:bg-primary/5 group">
                            <div class="mr-3 p-2 bg-primary/10 rounded-lg transition-all duration-300 
                                        group-hover:bg-primary/20 group-hover:scale-110">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="font-medium">Inloggen</span>
                        </a>
                        
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-primary to-secondary text-white font-medium 
                                  rounded-lg shadow-lg transition-all duration-300 
                                  hover:shadow-xl transform hover:scale-[1.02]
                                  active:scale-[0.98]"
                           style="display: none;">
                            <span>Aanmelden</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



    