<body class="bg-gray-50 overflow-x-hidden">
    <!-- Background blobs disabled to improve performance and prevent flickering -->
    


    <!-- Main Navigation - Completely redesigned -->
    <nav class="relative z-50 sticky top-0">
        <!-- Blog Schedule Announcement Bar -->
        <div class="bg-gradient-to-r from-primary to-primary-dark text-white text-center py-1.5 px-4">
            <p class="text-xs sm:text-sm font-medium flex items-center justify-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                </span>
                <span class="hidden sm:inline">Elke zondag een nieuwe blog</span>
                <span class="sm:hidden">Zondag: nieuwe blog</span>
                <span class="text-white/50 mx-1">|</span>
                <span class="text-white/80">Doordeweeks extra bij actueel nieuws</span>
            </p>
        </div>
        
        <!-- Modern header with clean design -->
        <div class="bg-white shadow-lg border-b-2 border-primary/10">
            <!-- Top accent bar -->
            <div class="h-1 bg-gradient-to-r from-primary to-secondary"></div>
            
            <!-- Navigation content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 md:h-20">
                    <!-- New Logo Design - Text based with icon -->
                    <div class="flex items-center space-x-3">
                        <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-3 group">
                            <!-- Favicon Logo -->
                            <div class="relative">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden shadow-lg 
                                            transition-all duration-300 group-hover:shadow-xl 
                                            group-hover:scale-105 group-hover:rotate-3
                                            border-2 border-primary/20 group-hover:border-primary/40
                                            shadow-[0_0_15px_rgba(26,54,93,0.3)] group-hover:shadow-[0_0_25px_rgba(196,30,58,0.5)]">
                                    <img src="<?php echo URLROOT; ?>/favicon.jpeg" 
                                         alt="<?php echo SITENAME; ?> Logo" 
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                            
                            <!-- Brand text -->
                            <div class="flex flex-col">
                                <span class="text-lg md:text-2xl font-bold bg-gradient-to-r 
                                            from-primary via-secondary to-primary bg-clip-text text-transparent
                                            transition-all duration-300 group-hover:from-secondary 
                                            group-hover:to-primary tracking-tight">
                                    <?php echo SITENAME; ?>
                                </span>
                                <span class="text-xs md:text-sm text-gray-600 font-medium 
                                            transition-all duration-300 group-hover:text-primary
                                            hidden sm:block">
                                    Jouw politieke platform
                                </span>
                            </div>
                        </a>
                        
                        <!-- Beta Badge -->
                        <button onclick="openBetaModal()" 
                                class="beta-badge relative bg-gradient-to-r from-secondary to-primary text-white 
                                       text-xs font-bold px-2 py-1 rounded-full shadow-lg
                                       hover:from-primary hover:to-secondary transition-all duration-300
                                       hover:scale-105 hover:shadow-xl cursor-pointer
                                       animate-pulse hover:animate-none">
                            <span class="relative z-10">BETA</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-secondary/20 
                                        rounded-full blur-sm animate-pulse"></div>
                        </button>
                    </div>

                <!-- Desktop Navigation Links - Modern Dropdown Structure -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <?php 
                    $currentContext = getCurrentPageContext();
                    $isHome = ($currentContext['section'] === 'home' || empty($currentContext['section']));
                    ?>
                    <a href="<?php echo URLROOT; ?>/" 
                       class="nav-link px-3 lg:px-4 py-2 text-gray-700 font-medium rounded-lg <?php echo $isHome ? 'active' : ''; ?>">
                        <span>Home</span>
                    </a>

                    <!-- Verkiezingen Dropdown -->
                    <?php 
                    $isVerkiezingenActive = in_array($currentContext['section'], ['partijmeter', 'politiek-kompas', 'amerikaanse-verkiezingen', 'nederlandse-verkiezingen', 'resultaten']);
                    ?>
                    <div class="relative dropdown-trigger group">
                        <button class="nav-link px-3 lg:px-4 py-2 text-gray-700 font-medium rounded-lg flex items-center space-x-1 <?php echo $isVerkiezingenActive ? 'active' : ''; ?>">
                            <span>Verkiezingen</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="dropdown-content absolute left-0 w-72 bg-white rounded-xl shadow-xl py-2 px-1.5 border border-gray-100">
                            <a href="<?php echo URLROOT; ?>/partijmeter" 
                               class="flex items-center justify-between px-3 py-3 rounded-lg hover:bg-secondary/5 transition-all duration-300 group/item">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-secondary/20 transition-colors duration-300">
                                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">PartijMeter</div>
                                        <div class="text-xs text-gray-500">Ontdek jouw politieke match</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-secondary text-white rounded-full">2025</span>
                            </a>
                            
                            <div class="mx-2 my-1 border-t border-gray-100"></div>
                            
                            <a href="<?php echo URLROOT; ?>/politiek-kompas" 
                               class="flex items-center justify-between px-3 py-3 rounded-lg hover:bg-accent/5 transition-all duration-300 group/item">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-accent/20 transition-colors duration-300">
                                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Politiek Kompas</div>
                                        <div class="text-xs text-gray-500">Vergelijk partijstandpunten</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-accent text-white rounded-full">Nieuw</span>
                            </a>
                            
                            <div class="mx-2 my-1 border-t border-gray-100"></div>
                            
                            <!-- Amerikaanse Verkiezingen - Consistent met andere items -->
                            <a href="<?php echo URLROOT; ?>/amerikaanse-verkiezingen" 
                            class="flex items-center justify-between px-3 py-3 rounded-lg hover:bg-primary/5 transition-all duration-300 group/item">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-primary/20 transition-colors duration-300">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Amerikaanse Verkiezingen</div>
                                        <div class="text-xs text-gray-500">235 jaar democratie</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-primary text-white rounded-full">USA</span>
                            </a>
                            
                            <!-- Nederlandse Verkiezingen - Consistent met andere items -->
                            <a href="<?php echo URLROOT; ?>/nederlandse-verkiezingen" 
                            class="flex items-center justify-between px-3 py-3 rounded-lg hover:bg-orange-500/5 transition-all duration-300 group/item">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-500/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-orange-500/20 transition-colors duration-300">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Nederlandse Verkiezingen</div>
                                        <div class="text-xs text-gray-500">175 jaar democratie</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-orange-500 text-white rounded-full">NL</span>
                            </a>
                        </div>
                    </div>

                    <!-- Nieuws & Content Dropdown -->
                    <?php 
                    $isContentActive = in_array($currentContext['section'], ['blogs', 'nieuws']);
                    ?>
                    <div class="relative dropdown-trigger group">
                        <button class="nav-link px-3 lg:px-4 py-2 text-gray-700 font-medium rounded-lg flex items-center space-x-1 <?php echo $isContentActive ? 'active' : ''; ?>">
                            <span>Nieuws & Blogs</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="dropdown-content absolute left-0 w-60 bg-white rounded-xl shadow-xl py-2 px-1.5 border border-gray-100">
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-primary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-primary/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Politieke Blogs</div>
                                    <div class="text-xs text-gray-500">Expert analyses & meningen</div>
                                </div>
                            </a>
                            
                            <div class="mx-2 my-1 border-t border-gray-100"></div>
                            
                            <a href="<?php echo URLROOT; ?>/nieuws" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-secondary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-secondary/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m0 0v10a2 2 0 01-2 2h-5m-4 0V5a2 2 0 014 0v2M7 7h3m-3 4h3m-3 4h3"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Politiek Nieuws</div>
                                    <div class="text-xs text-gray-500">Laatste ontwikkelingen</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Politiek Dropdown -->
                    <?php 
                    $isPolitiekActive = in_array($currentContext['section'], ['partijen', 'themas', 'thema']);
                    ?>
                    <div class="relative dropdown-trigger group">
                        <button class="nav-link px-3 lg:px-4 py-2 text-gray-700 font-medium rounded-lg flex items-center space-x-1 <?php echo $isPolitiekActive ? 'active' : ''; ?>">
                            <span>Politiek</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="dropdown-content absolute left-0 w-56 bg-white rounded-xl shadow-xl py-2 px-1.5 border border-gray-100">
                            <a href="<?php echo URLROOT; ?>/partijen" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-primary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-primary/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Politieke Partijen</div>
                                    <div class="text-xs text-gray-500">Overzicht & standpunten</div>
                                </div>
                            </a>

                            
                            <div class="mx-2 my-1 border-t border-gray-100"></div>
                            
                            <a href="<?php echo URLROOT; ?>/themas" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-accent/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-accent/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Politieke Thema's</div>
                                    <div class="text-xs text-gray-500">Onderwerpen & analyses</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Info Dropdown -->
                    <?php 
                    $isInfoActive = in_array($currentContext['section'], ['over-mij', 'contact', 'forum']);
                    ?>
                    <div class="relative dropdown-trigger group">
                        <button class="nav-link px-3 lg:px-4 py-2 text-gray-700 font-medium rounded-lg flex items-center space-x-1 <?php echo $isInfoActive ? 'active' : ''; ?>">
                            <span>Info</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="dropdown-content absolute right-0 w-48 bg-white rounded-xl shadow-xl py-2 px-1.5 border border-gray-100">
                            <a href="<?php echo URLROOT; ?>/over-mij" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-primary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-primary/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Over Ons</div>
                                    <div class="text-xs text-gray-500">Missie & visie</div>
                                </div>
                            </a>
                            
                            <div class="mx-2 my-1 border-t border-gray-100"></div>
                            
                            <a href="<?php echo URLROOT; ?>/contact" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-secondary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:bg-secondary/20 transition-colors duration-300">
                                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Contact</div>
                                    <div class="text-xs text-gray-500">Neem contact op</div>
                                </div>
                            </a>
                            
                            <a href="<?php echo URLROOT; ?>/donatie" 
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-primary/5 transition-all duration-300 group/item">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl flex items-center justify-center mr-3 group-hover/item:from-primary/20 group-hover/item:to-secondary/20 transition-all duration-300">
                                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Doneer</div>
                                    <div class="text-xs text-gray-500">Steun PolitiekPraat</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Auth Buttons - Modern clean design -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-3 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-lg
                                         border border-gray-200 hover:border-primary/30
                                         transition-all duration-300 hover:shadow-md group-hover:scale-[1.02]">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary
                                         rounded-lg flex items-center justify-center
                                         shadow-sm transition-all duration-300
                                         group-hover:shadow-md group-hover:scale-110 overflow-hidden">
                                    <?php
                                    // Use dynamic profile photo with session refresh for ALL users
                                    $profilePhoto = function_exists('getProfilePhotoWithRefresh') 
                                        ? getProfilePhotoWithRefresh($_SESSION['username'] ?? '') 
                                        : getProfilePhotoUrl($_SESSION['profile_photo'] ?? '', $_SESSION['username'] ?? '');
                                    if ($profilePhoto['type'] === 'img'): 
                                    ?>
                                        <img src="<?php echo $profilePhoto['value']; ?>" 
                                             alt="Profile" class="w-full h-full object-cover rounded-lg">
                                    <?php else: ?>
                                        <span class="text-white font-bold text-sm">
                                            <?php echo $profilePhoto['value']; ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                </div>
                                <span class="font-medium text-gray-700 text-sm"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- User Dropdown -->
                            <div class="absolute right-0 mt-2 w-56 dropdown-content z-50" style="margin-top: -5px; padding-top: 15px;">
                                <div class="bg-white rounded-xl shadow-xl py-2.5 px-1.5 border border-gray-100 overflow-hidden">
                                    <!-- Subtle top accent -->
                                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary/30 via-secondary/30 to-primary/30"></div>
                                    
                                    <?php if($_SESSION['is_admin']): ?>
                                                                            <a href="<?php echo URLROOT; ?>/admin/stemwijzer-dashboard.php" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                             transition-all duration-300 hover:bg-gradient-to-r hover:from-primary/5 hover:to-primary/10 
                                             group/item relative overflow-hidden">
                                        <div class="w-9 h-9 bg-primary/5 rounded-lg flex items-center justify-center
                                                  transition-all duration-300 group-hover/item:scale-110 group-hover/item:bg-primary/15
                                                  group-hover/item:shadow-lg group-hover/item:shadow-primary/20">
                                            <svg class="w-5 h-5 text-primary transition-all duration-300 group-hover/item:scale-110" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 transition-all duration-300 group-hover/item:translate-x-1">
                                            <p class="text-sm font-medium text-gray-900 group-hover/item:text-primary transition-colors duration-300">Dashboard</p>
                                            <p class="text-xs text-gray-500 group-hover/item:text-primary/70 transition-colors duration-300">Beheer je website</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent 
                                                   opacity-0 transition-opacity duration-300 group-hover/item:opacity-100"></div>
                                    </a>
                                    <?php endif; ?>

                                    <a href="<?php echo URLROOT; ?>/blogs/manage" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                              transition-all duration-300 hover:bg-gradient-to-r hover:from-primary/5 hover:to-primary/10 
                                              group/item relative overflow-hidden">
                                        <div class="w-9 h-9 bg-primary/5 rounded-lg flex items-center justify-center
                                                  transition-all duration-300 group-hover/item:scale-110 group-hover/item:bg-primary/15
                                                  group-hover/item:shadow-lg group-hover/item:shadow-primary/20">
                                            <svg class="w-5 h-5 text-primary transition-all duration-300 group-hover/item:scale-110" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 transition-all duration-300 group-hover/item:translate-x-1">
                                            <p class="text-sm font-medium text-gray-900 group-hover/item:text-primary transition-colors duration-300">Mijn Blogs</p>
                                            <p class="text-xs text-gray-500 group-hover/item:text-primary/70 transition-colors duration-300">Beheer je blogs</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent 
                                                   opacity-0 transition-opacity duration-300 group-hover/item:opacity-100"></div>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/profile" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                              transition-all duration-300 hover:bg-gradient-to-r hover:from-secondary/5 hover:to-secondary/10 
                                              group/item relative overflow-hidden">
                                        <div class="w-9 h-9 bg-secondary/5 rounded-lg flex items-center justify-center
                                                  transition-all duration-300 group-hover/item:scale-110 group-hover/item:bg-secondary/15
                                                  group-hover/item:shadow-lg group-hover/item:shadow-secondary/20">
                                            <svg class="w-5 h-5 text-secondary transition-all duration-300 group-hover/item:scale-110" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 transition-all duration-300 group-hover/item:translate-x-1">
                                            <p class="text-sm font-medium text-gray-900 group-hover/item:text-secondary transition-colors duration-300">Profiel</p>
                                            <p class="text-xs text-gray-500 group-hover/item:text-secondary/70 transition-colors duration-300">Beheer je account</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/5 to-transparent 
                                                   opacity-0 transition-opacity duration-300 group-hover/item:opacity-100"></div>
                                    </a>
                                    <div class="mx-3 my-1.5 border-t border-gray-100"></div>

                                    <a href="<?php echo URLROOT; ?>/logout" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                             transition-all duration-300 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 
                                             group/item relative overflow-hidden">
                                        <div class="w-9 h-9 bg-red-500/5 rounded-lg flex items-center justify-center
                                                  transition-all duration-300 group-hover/item:scale-110 group-hover/item:bg-red-500/15
                                                  group-hover/item:shadow-lg group-hover/item:shadow-red-500/20">
                                            <svg class="w-5 h-5 text-red-500 transition-all duration-300 group-hover/item:scale-110" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 transition-all duration-300 group-hover/item:translate-x-1">
                                            <p class="text-sm font-medium text-red-600 group-hover/item:text-red-700 transition-colors duration-300">Uitloggen</p>
                                            <p class="text-xs text-red-400 group-hover/item:text-red-500 transition-colors duration-300">Tot ziens!</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-transparent 
                                                   opacity-0 transition-opacity duration-300 group-hover/item:opacity-100"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="text-gray-700 hover:text-primary transition-all duration-300 font-medium 
                                 py-2 px-4 rounded-lg border border-gray-200 hover:border-primary/30 hover:bg-gray-50">
                            <span>Inloggen</span>
                        </a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="bg-gradient-to-r from-primary to-secondary text-white px-4 py-2 rounded-lg 
                                 font-medium transition-all duration-300 hover:shadow-lg hover:scale-105
                                 relative overflow-hidden group"
                           style="display: none;">
                            <span class="relative z-10">Aanmelden</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary 
                                       opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button - Fixed styling -->
                <button class="md:hidden relative z-10 p-2 text-gray-700 hover:text-primary hover:bg-gray-100 rounded-lg border border-gray-200 
                            transition-all duration-300 hover:border-primary/30 group" 
                        id="mobile-menu-button" aria-label="Menu openen">
                    <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    