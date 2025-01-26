<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/favicon.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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

        /* Custom Animations */
        @keyframes bounce-x {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .animate-bounce-x {
            animation: bounce-x 1s infinite;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }

        /* Blog card hover effects */
        .blog-card-hover {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .blog-card-hover:hover {
            transform: translateY(-8px) scale(1.01);
        }

        /* Gradient text animation */
        .gradient-text {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--primary-color) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient 3s linear infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
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
    <nav class="relative z-50">
        <!-- Gradient background -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary/95 to-primary shadow-lg"></div>
        
        <!-- Glassmorphism effect -->
        <div class="absolute inset-0 backdrop-blur-md bg-white/5"></div>

        <!-- Navigation content -->
        <div class="relative">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-24">
                    <!-- Logo -->
                    <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-4 group">
                        <div class="relative">
                            <!-- Logo background with gradient -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 flex items-center justify-center 
                                      border border-white/10 backdrop-blur-sm transform transition-all duration-300 
                                      group-hover:scale-105 group-hover:rotate-3 group-hover:border-secondary/50">
                                <svg class="w-8 h-8 text-white transform transition-transform duration-300 group-hover:scale-110" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                          d="M19 21v-6a2 2 0 00-2-2h-2v3h-3v-3h-2a2 2 0 00-2 2v6M3 21V5a2 2 0 012-2h14a2 2 0 012 2v16M9 7h.01M9 11h.01M15 7h.01M15 11h.01M12 7v5"/>
                                </svg>
                            </div>
                            <!-- Floating badge -->
                            <div class="absolute -top-2 -right-2 w-7 h-7 bg-secondary rounded-full flex items-center justify-center 
                                      shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" 
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" 
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold tracking-tight text-white group-hover:text-secondary 
                                       transition-colors duration-300 relative">
                                <?php echo SITENAME; ?>
                                <!-- Underline effect -->
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 
                                           group-hover:w-full"></span>
                            </span>
                            <span class="text-sm text-white/70 font-medium tracking-wide">
                                Samen bouwen aan democratie
                            </span>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="<?php echo URLROOT; ?>/" 
                           class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                            Home
                        </a>

                        <!-- Nieuws & Blogs Dropdown -->
                        <div class="relative group">
                            <button class="menu-item text-white/90 hover:text-white transition-colors duration-300 font-medium py-2 flex items-center">
                                Media
                                <svg class="w-4 h-4 ml-1 transform transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <a href="<?php echo URLROOT; ?>/nieuws" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Nieuws</span>
                                        <span class="block text-xs text-gray-500">Laatste ontwikkelingen</span>
                                    </div>
                                </a>
                                <a href="<?php echo URLROOT; ?>/blogs" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Blogs</span>
                                        <span class="block text-xs text-gray-500">Politieke inzichten</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Forum Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-white/90 hover:text-white transition-all duration-300 
                                         font-medium py-2 group">
                                <span class="relative">
                                    Forum
                                    <span class="absolute inset-x-0 -bottom-0.5 h-0.5 bg-secondary transform scale-x-0 
                                               group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                                </span>
                                <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 opacity-0 invisible 
                                      group-hover:opacity-100 group-hover:visible transition-all duration-300 transform 
                                      origin-top scale-95 group-hover:scale-100">
                                <a href="<?php echo URLROOT; ?>/forum" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <svg class="w-5 h-5 mr-3 text-secondary transform transition-transform duration-300 
                                              group-hover/item:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Alle discussies
                                        </span>
                                        <span class="block text-xs text-gray-500">Bekijk het forum</span>
                                    </div>
                                </a>
                                <?php if(isset($_SESSION['user_id'])): ?>
                                <div class="px-4 py-2">
                                    <div class="border-t border-gray-100"></div>
                                </div>
                                <a href="<?php echo URLROOT; ?>/forum/create" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <svg class="w-5 h-5 mr-3 text-secondary transform transition-transform duration-300 
                                              group-hover/item:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Start discussie
                                        </span>
                                        <span class="block text-xs text-gray-500">Nieuw onderwerp</span>
                                    </div>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Thema's Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-white/90 hover:text-white transition-all duration-300 
                                         font-medium py-2 group">
                                <span class="relative">
                                    Thema's
                                    <span class="absolute inset-x-0 -bottom-0.5 h-0.5 bg-secondary transform scale-x-0 
                                               group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                                </span>
                                <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 opacity-0 invisible 
                                      group-hover:opacity-100 group-hover:visible transition-all duration-300 transform 
                                      origin-top scale-95 group-hover:scale-100">
                                <a href="<?php echo URLROOT; ?>/thema/economie" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg transform transition-transform 
                                               duration-300 group-hover/item:scale-110">💶</span>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Economie
                                        </span>
                                        <span class="block text-xs text-gray-500">Financiële zaken</span>
                                    </div>
                                </a>
                                <a href="<?php echo URLROOT; ?>/thema/zorg" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg transform transition-transform 
                                               duration-300 group-hover/item:scale-110">🏥</span>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Zorg
                                        </span>
                                        <span class="block text-xs text-gray-500">Gezondheidszorg</span>
                                    </div>
                                </a>
                                <a href="<?php echo URLROOT; ?>/thema/onderwijs" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg transform transition-transform 
                                               duration-300 group-hover/item:scale-110">📚</span>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Onderwijs
                                        </span>
                                        <span class="block text-xs text-gray-500">Educatie</span>
                                    </div>
                                </a>
                                <div class="px-4 py-2">
                                    <div class="border-t border-gray-100"></div>
                                </div>
                                <a href="<?php echo URLROOT; ?>/themas" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-300 
                                          group/item">
                                    <svg class="w-5 h-5 mr-3 text-secondary transform transition-transform duration-300 
                                              group-hover/item:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium group-hover/item:text-secondary transition-colors duration-300">
                                            Alle thema's
                                        </span>
                                        <span class="block text-xs text-gray-500">Bekijk overzicht</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <a href="<?php echo URLROOT; ?>/contact" 
                           class="relative text-white/90 hover:text-white transition-all duration-300 font-medium py-2 group">
                            <span class="relative z-10">Contact</span>
                            <span class="absolute inset-x-0 -bottom-0.5 h-0.5 bg-secondary transform scale-x-0 group-hover:scale-x-100 
                                       transition-transform duration-300 origin-left"></span>
                        </a>
                    </div>

                    <!-- Desktop Auth Buttons -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 glass-effect px-4 py-2 rounded-xl hover:bg-white/20 transition-all duration-300">
                                    <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">
                                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                        </span>
                                    </div>
                                    <span class="font-medium text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                    <svg class="w-4 h-4 text-white transition-transform duration-300 group-hover:rotate-180" 
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
                    <button class="lg:hidden relative z-50 p-2 text-white hover:bg-white/10 rounded-xl border border-white/10 
                              transition-all duration-300 hover:border-secondary/50" 
                            id="mobile-menu-button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div class="lg:hidden hidden fixed inset-0 z-40" id="mobile-menu-overlay">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-primary/80 backdrop-blur-sm"></div>
                    
                    <!-- Menu Content -->
                    <div class="relative h-full w-full max-w-sm ml-auto bg-gradient-to-b from-primary to-primary/95 
                               shadow-2xl overflow-y-auto">
                        <div class="p-6 space-y-6">
                            <!-- Close Button -->
                            <div class="flex justify-end">
                                <button class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition-colors duration-300" 
                                        onclick="closeMobileMenu()">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Navigation Links -->
                            <nav class="space-y-6">
                                <a href="<?php echo URLROOT; ?>/" 
                                   class="flex items-center text-white/90 hover:text-white py-2 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="font-medium">Home</span>
                                </a>

                                <!-- Media Section -->
                                <div class="space-y-3">
                                    <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                                 transition-colors duration-300" 
                                            onclick="toggleMobileSubmenu('media-submenu')">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="font-medium">Media</span>
                                        </div>
                                        <svg class="w-4 h-4 transform transition-transform duration-300" 
                                             id="media-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="hidden pl-8 space-y-3 border-l border-white/10" id="media-submenu">
                                        <a href="<?php echo URLROOT; ?>/nieuws" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Nieuws</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/blogs" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Blogs</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Forum Section -->
                                <div class="space-y-3">
                                    <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                                 transition-colors duration-300" 
                                            onclick="toggleMobileSubmenu('forum-submenu')">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                            </svg>
                                            <span class="font-medium">Forum</span>
                                        </div>
                                        <svg class="w-4 h-4 transform transition-transform duration-300" 
                                             id="forum-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="hidden pl-8 space-y-3 border-l border-white/10" id="forum-submenu">
                                        <a href="<?php echo URLROOT; ?>/forum" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Alle discussies</span>
                                        </a>
                                        <?php if(isset($_SESSION['user_id'])): ?>
                                        <a href="<?php echo URLROOT; ?>/forum/create" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Start discussie</span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Thema's Section -->
                                <div class="space-y-3">
                                    <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                                 transition-colors duration-300" 
                                            onclick="toggleMobileSubmenu('themas-submenu')">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                            <span class="font-medium">Thema's</span>
                                        </div>
                                        <svg class="w-4 h-4 transform transition-transform duration-300" 
                                             id="themas-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="hidden pl-8 space-y-3 border-l border-white/10" id="themas-submenu">
                                        <a href="<?php echo URLROOT; ?>/thema/economie" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">💶 Economie</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/thema/zorg" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">🏥 Zorg</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/thema/onderwijs" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">📚 Onderwijs</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/themas" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Alle thema's</span>
                                        </a>
                                    </div>
                                </div>

                                <a href="<?php echo URLROOT; ?>/contact" 
                                   class="flex items-center text-white/90 hover:text-white py-2 transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">Contact</span>
                                </a>
                            </nav>

                            <!-- Auth Section -->
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <div class="pt-6 border-t border-white/10">
                                    <div class="flex items-center space-x-4 mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary/80 rounded-xl 
                                                  flex items-center justify-center shadow-lg">
                                            <span class="text-white text-xl font-bold">
                                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                            <p class="text-sm text-white/70">Actief lid</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <?php if($_SESSION['is_admin']): ?>
                                            <a href="<?php echo URLROOT; ?>/admin" 
                                               class="flex items-center text-white/90 hover:text-white transition-colors duration-300">
                                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span class="font-medium">Dashboard</span>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo URLROOT; ?>/profile" 
                                           class="flex items-center text-white/90 hover:text-white transition-colors duration-300">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="font-medium">Profiel</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/logout" 
                                           class="flex items-center text-red-300 hover:text-red-200 transition-colors duration-300">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            <span class="font-medium">Uitloggen</span>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="pt-6 border-t border-white/10 space-y-4">
                                    <a href="<?php echo URLROOT; ?>/login" 
                                       class="flex items-center text-white/90 hover:text-white transition-colors duration-300">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        <span class="font-medium">Inloggen</span>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/register" 
                                       class="flex items-center justify-center px-6 py-3 bg-white text-primary font-semibold 
                                              rounded-xl shadow-lg hover:bg-white/95 transition-all duration-300 
                                              transform hover:scale-[1.02]">
                                        Word lid
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Toggle Script -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu-overlay');

        function toggleMobileMenu() {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function closeMobileMenu() {
            mobileMenu.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        mobileMenuButton.addEventListener('click', toggleMobileMenu);

        // Close mobile menu when clicking outside
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                closeMobileMenu();
            }
        });

        // Mobile submenu toggle functionality
        function toggleMobileSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + '-icon');
            
            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>

    <div class="flex-grow">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: false,
                mirror: true
            });
        });
    </script>
</body>
</html> 