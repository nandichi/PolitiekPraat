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
                        primary: '#1a365d',    // Donkerblauw - VVD/conservatief
                        secondary: '#c41e3a',  // Rood - PvdA/progressief
                        accent: '#00796b',     // Groen - GroenLinks/duurzaam
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .nav-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #234876 50%, #2d5a94 100%);
        }

        .menu-item {
            position: relative;
            overflow: hidden;
        }

        .menu-item::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #c41e3a;
            transform: translateX(-101%);
            transition: transform 0.3s ease;
        }

        .menu-item:hover::after {
            transform: translateX(0);
        }

        .glow-effect {
            position: relative;
        }

        .glow-effect::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #c41e3a, #00796b);
            border-radius: 0.5rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .glow-effect:hover::before {
            opacity: 1;
        }

        .scale-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .scale-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Announcement Bar -->
    <div class="bg-secondary text-white py-2 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 text-sm">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Laatste nieuws
                    </span>
                    <span class="hidden md:inline-block">|</span>
                    <span class="hidden md:flex items-center animate-pulse-slow">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        Nieuwe discussies toegevoegd
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-sm hover:text-white/90 transition-colors">Bekijk meer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="nav-gradient text-white shadow-lg relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-white/20 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                      d="M19 21v-6a2 2 0 00-2-2h-2v3h-3v-3h-2a2 2 0 00-2 2v6M3 21V5a2 2 0 012-2h14a2 2 0 012 2v16M9 7h.01M9 11h.01M15 7h.01M15 11h.01M12 7v5"/>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-secondary rounded-full flex items-center justify-center shadow-lg animate-float">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" 
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" 
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold tracking-tight group-hover:text-secondary transition-colors duration-300">
                            <?php echo SITENAME; ?>
                        </span>
                        <span class="text-xs text-white/70 font-medium">
                            Samen bouwen aan democratie
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="<?php echo URLROOT; ?>" 
                       class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                        Home
                    </a>
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                        Blogs
                    </a>
                    <a href="<?php echo URLROOT; ?>/forum" 
                       class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                        Forum
                    </a>
                    <a href="<?php echo URLROOT; ?>/contact" 
                       class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                        Contact
                    </a>
                </div>

                <!-- Desktop Auth Buttons -->
                <div class="hidden lg:flex items-center space-x-6">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 glass-effect px-4 py-2 rounded-xl hover:bg-white/20 transition-all duration-300">
                                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">
                                        <?php echo substr($_SESSION['username'], 0, 1); ?>
                                    </span>
                                </div>
                                <span class="font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <?php if($_SESSION['is_admin']): ?>
                                    <a href="<?php echo URLROOT; ?>/admin" 
                                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                                        <svg class="w-5 h-5 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Dashboard</span>
                                            <span class="block text-xs text-gray-500">Beheer je website</span>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo URLROOT; ?>/profile" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Profiel</span>
                                        <span class="block text-xs text-gray-500">Beheer je account</span>
                                    </div>
                                </a>
                                <div class="border-t border-gray-100 my-2"></div>
                                <a href="<?php echo URLROOT; ?>/logout" 
                                   class="flex items-center px-4 py-3 text-secondary hover:bg-red-50 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Uitloggen</span>
                                        <span class="block text-xs text-secondary/70">Tot ziens!</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                            Inloggen
                        </a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="glow-effect scale-hover bg-white text-primary px-6 py-2.5 rounded-lg font-semibold 
                                  transition-all duration-300 hover:text-secondary">
                            Word lid
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition-colors duration-300" 
                        id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="lg:hidden hidden glass-effect absolute top-full left-0 right-0 z-20 border-t border-white/10" 
                 id="mobile-menu">
                <div class="container mx-auto px-4 py-4 space-y-3">
                    <a href="<?php echo URLROOT; ?>" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                        Home
                    </a>
                    <a href="<?php echo URLROOT; ?>/blogs" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                        Blogs
                    </a>
                    <a href="<?php echo URLROOT; ?>/forum" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                        Forum
                    </a>
                    <a href="<?php echo URLROOT; ?>/contact" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                        Contact
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="border-t border-white/10 pt-3">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">
                                        <?php echo substr($_SESSION['username'], 0, 1); ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                    <p class="text-sm text-white/70">Actief lid</p>
                                </div>
                            </div>
                            <?php if($_SESSION['is_admin']): ?>
                                <a href="<?php echo URLROOT; ?>/admin" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                                    Dashboard
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo URLROOT; ?>/profile" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                                Profiel
                            </a>
                            <a href="<?php echo URLROOT; ?>/logout" class="block text-red-300 hover:text-red-200 py-2 transition-colors duration-300">
                                Uitloggen
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="border-t border-white/10 pt-3 space-y-3">
                            <a href="<?php echo URLROOT; ?>/login" class="block text-white/90 hover:text-white py-2 transition-colors duration-300">
                                Inloggen
                            </a>
                            <a href="<?php echo URLROOT; ?>/register" 
                               class="block bg-white text-primary px-4 py-2 rounded-lg text-center font-semibold 
                                      hover:bg-opacity-90 transition-colors duration-300">
                                Word lid
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
            mobileMenuButton.classList.toggle('bg-white/10');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.classList.remove('bg-white/10');
            }
        });
    </script>

    <div class="flex-grow">
</body>
</html> 