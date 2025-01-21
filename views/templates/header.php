<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        secondary: '#e65100',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes gradientBg {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .gradient-animate {
            background: linear-gradient(270deg, #1a365d, #234876, #2d5a94);
            background-size: 200% 200%;
            animation: gradientBg 15s ease infinite;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <nav class="gradient-animate text-white shadow-lg relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-secondary rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-2xl font-bold tracking-tight group-hover:text-white transition-colors duration-200">
                            <?php echo SITENAME; ?>
                        </span>
                        <span class="block text-xs text-white/70 font-medium">
                            Jouw stem in het debat
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="<?php echo URLROOT; ?>" 
                       class="text-white/90 hover:text-white transition-colors duration-200 font-medium">
                        Home
                    </a>
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="text-white/90 hover:text-white transition-colors duration-200 font-medium">
                        Blogs
                    </a>
                    <a href="<?php echo URLROOT; ?>/forum" 
                       class="text-white/90 hover:text-white transition-colors duration-200 font-medium">
                        Forum
                    </a>
                    <a href="<?php echo URLROOT; ?>/contact" 
                       class="text-white/90 hover:text-white transition-colors duration-200 font-medium">
                        Contact
                    </a>
                </div>

                <!-- Desktop Auth Buttons -->
                <div class="hidden lg:flex items-center space-x-6">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 hidden group-hover:block animate-slide-down">
                                <?php if($_SESSION['is_admin']): ?>
                                    <a href="<?php echo URLROOT; ?>/admin" 
                                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Dashboard
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo URLROOT; ?>/profile" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profiel
                                </a>
                                <div class="border-t border-gray-100 my-2"></div>
                                <a href="<?php echo URLROOT; ?>/logout" 
                                   class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Uitloggen
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="text-white/90 hover:text-white transition-colors duration-200 font-medium">
                            Inloggen
                        </a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="bg-white text-primary px-6 py-2.5 rounded-lg font-semibold hover:bg-opacity-90 
                                  transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Registreren
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-white focus:outline-none" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="lg:hidden hidden" id="mobile-menu">
                <div class="py-4 space-y-3">
                    <a href="<?php echo URLROOT; ?>" class="block text-white/90 hover:text-white py-2">Home</a>
                    <a href="<?php echo URLROOT; ?>/blogs" class="block text-white/90 hover:text-white py-2">Blogs</a>
                    <a href="<?php echo URLROOT; ?>/forum" class="block text-white/90 hover:text-white py-2">Forum</a>
                    <a href="<?php echo URLROOT; ?>/contact" class="block text-white/90 hover:text-white py-2">Contact</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="border-t border-white/10 pt-3">
                            <p class="text-white/70 text-sm mb-2">Ingelogd als <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            <?php if($_SESSION['is_admin']): ?>
                                <a href="<?php echo URLROOT; ?>/admin" class="block text-white/90 hover:text-white py-2">Dashboard</a>
                            <?php endif; ?>
                            <a href="<?php echo URLROOT; ?>/profile" class="block text-white/90 hover:text-white py-2">Profiel</a>
                            <a href="<?php echo URLROOT; ?>/logout" class="block text-red-300 hover:text-red-200 py-2">Uitloggen</a>
                        </div>
                    <?php else: ?>
                        <div class="border-t border-white/10 pt-3 space-y-3">
                            <a href="<?php echo URLROOT; ?>/login" class="block text-white/90 hover:text-white py-2">Inloggen</a>
                            <a href="<?php echo URLROOT; ?>/register" 
                               class="block bg-white text-primary px-4 py-2 rounded-lg text-center font-semibold">
                                Registreren
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Toggle Script -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    <div class="flex-grow">
</body>
</html> 