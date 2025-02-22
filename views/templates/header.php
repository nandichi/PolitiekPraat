<?php
// Dynamic meta descriptions based on current page
$metaDescriptions = [
    'home' => 'Ontdek het laatste politieke nieuws, blogs en analyses op Politiekpraat. Blijf op de hoogte van de Nederlandse politiek en neem deel aan het debat.',
    'blogs' => 'Lees en deel politieke blogs over actuele thema\'s. Van ervaren schrijvers tot nieuwe stemmen in het politieke debat.',
    'nieuws' => 'Het laatste politieke nieuws uit betrouwbare bronnen, zowel progressief als conservatief. Blijf geïnformeerd over de Nederlandse politiek.',
    'stemwijzer' => 'Doe de stemwijzer 2025 en ontdek welke partij het beste bij jouw standpunten past. Objectief en onafhankelijk advies.',
    'forum' => 'Discussieer mee over politieke onderwerpen in ons forum. Deel je mening en ga in gesprek met anderen over de Nederlandse politiek.',
    'contact' => 'Neem contact op met PolitiekPlatform. We staan klaar om je vragen te beantwoorden en feedback te ontvangen.'
];

// Get current page from URL
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$metaDescription = $metaDescriptions[$currentPage] ?? $metaDescriptions['home'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($data['title']) ? htmlspecialchars($data['title']) : 'PolitiekPlatform - Politiek voor iedereen'; ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:image" content="<?php echo URLROOT; ?>/public/img/og-image.jpg">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($data['title']) ? htmlspecialchars($data['title']) : 'PolitiekPlatform - Politiek voor iedereen'; ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="twitter:image" content="<?php echo URLROOT; ?>/public/img/twitter-card.jpg">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">

    <meta name="google-site-verification" content="e72Qn95mvwZrvfw5CvXBKfeIv0vSqmo88Fw-oTJ5sgw" />
    <title><?php echo SITENAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/favicon.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    typography: {
                        DEFAULT: {
                            css: {
                                color: '#374151',
                                maxWidth: 'none',
                                h1: {
                                    color: '#1a365d',
                                },
                                h2: {
                                    color: '#1a365d',
                                },
                                h3: {
                                    color: '#1a365d',
                                },
                                strong: {
                                    color: '#1a365d',
                                },
                                a: {
                                    color: '#c41e3a',
                                    '&:hover': {
                                        color: '#1a365d',
                                    },
                                },
                                code: {
                                    color: '#1a365d',
                                    backgroundColor: '#f3f4f6',
                                    padding: '0.2em 0.4em',
                                    borderRadius: '0.25rem',
                                },
                                pre: {
                                    backgroundColor: '#f3f4f6',
                                    code: {
                                        backgroundColor: 'transparent',
                                        padding: '0',
                                    },
                                },
                            },
                        },
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { 
            display: none !important; 
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #234876 50%, #2d5a94 100%);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link span {
            position: relative;
        }

        .nav-link span::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #c41e3a, #ff4d6d);
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.45, 0.05, 0.55, 0.95);
            transform-origin: right;
        }

        .nav-link:hover span::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .glow-effect {
            position: relative;
            overflow: hidden;
        }

        .glow-effect::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #c41e3a, #00796b, #1a365d);
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
            border-radius: 0.5rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glow-effect:hover::before {
            opacity: 1;
        }

        .scale-hover {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        box-shadow 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .scale-hover:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        @keyframes bounce-x {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(8px); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .animate-bounce-x {
            animation: bounce-x 1.2s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        }

        .animate-float {
            animation: float 4s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 2.5s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        }

        .blog-card-hover {
            transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .blog-card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .gradient-text {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--primary-color) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient 4s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .signup-btn {
            position: relative;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            color: #1a365d;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.15);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .signup-btn:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #c41e3a 0%, #d4293f 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(196, 30, 58, 0.25);
        }

        .signup-btn:active {
            transform: translateY(-1px);
        }

        .particle {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Beta Notification Popup -->
    <div x-data="{ 
            showBetaNotice: false
        }" 
         x-init="showBetaNotice = localStorage.getItem('betaNoticeShown') === null"
         x-cloak
         x-show="showBetaNotice" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Welkom bij de Beta versie
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Deze website bevindt zich momenteel in de beta fase. Wij werken hard aan nieuwe functionaliteiten en verbeteringen. Jouw feedback is zeer waardevol voor ons! Heb je suggesties of kom je problemen tegen? Laat het ons weten via de contactpagina.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 flex gap-3">
                    <button type="button"
                            @click="localStorage.setItem('betaNoticeShown', 'true'); showBetaNotice = false"
                            class="flex-1 rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm transition duration-150 ease-in-out inline-flex items-center justify-center">
                        Doorgaan
                    </button>
                    <a href="<?php echo URLROOT; ?>/contact"
                       @click="localStorage.setItem('betaNoticeShown', 'true'); showBetaNotice = false"
                       class="flex-1 rounded-md border border-primary shadow-sm px-4 py-2 bg-white text-base font-medium text-primary hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm transition duration-150 ease-in-out inline-flex items-center justify-center">
                        Feedback
                    </a>
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
                            <!-- Logo background with enhanced gradient -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-white/20 via-white/10 to-white/5 
                                        flex items-center justify-center overflow-hidden
                                        border border-white/20 backdrop-blur-lg shadow-lg
                                        transform transition-all duration-500 ease-out
                                        group-hover:scale-110 group-hover:rotate-6 group-hover:border-secondary/50
                                        group-hover:shadow-secondary/20 group-hover:from-white/30">
                                
                                <!-- Animated background effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent
                                            translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                
                                <!-- Logo icon with enhanced animation -->
                                <svg class="w-8 h-8 text-white relative z-10 transform transition-all duration-500 ease-out
                                            group-hover:scale-110 group-hover:text-secondary/90" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                          d="M19 21v-6a2 2 0 00-2-2h-2v3h-3v-3h-2a2 2 0 00-2 2v6M3 21V5a2 2 0 012-2h14a2 2 0 012 2v16M9 7h.01M9 11h.01M15 7h.01M15 11h.01M12 7v5"/>
                                </svg>
                            </div>
                            
                            <!-- Enhanced floating badge -->
                            <div class="absolute -top-2 -right-2 w-7 h-7 bg-gradient-to-br from-secondary to-secondary/80
                                        rounded-full flex items-center justify-center overflow-hidden
                                        shadow-lg shadow-secondary/30 border border-white/10
                                        transform transition-all duration-500 ease-out
                                        group-hover:scale-125 group-hover:rotate-12 group-hover:shadow-secondary/40">
                                <!-- Animated shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent
                                            translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                
                                <svg class="w-4 h-4 text-white relative z-10 transform transition-transform duration-500
                                            group-hover:scale-110 group-hover:rotate-[-12deg]" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" 
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" 
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Enhanced text section -->
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold tracking-tight text-white relative
                                         transition-all duration-500 ease-out group-hover:text-secondary/90">
                                <?php echo SITENAME; ?>
                                <!-- Enhanced underline effect -->
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-secondary via-secondary/80 to-secondary/60
                                             transition-all duration-500 ease-out group-hover:w-full"></span>
                            </span>
                            <span class="text-sm text-white/70 font-medium tracking-wide
                                         transition-all duration-500 ease-out group-hover:text-white/90">
                                Samen bouwen aan democratie
                            </span>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <a href="<?php echo URLROOT; ?>/" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group">
                            <span class="relative">Home</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group">
                            <span class="relative">Blogs</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/nieuws" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group">
                            <span class="relative">Nieuws</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/stemwijzer" 
                           class="flex items-center nav-link px-4 py-2 text-white/90 font-medium group">
                            <span class="relative">Stemwijzer</span>
                            <div class="ml-2 inline-flex items-center px-2 py-0.5 text-[10px] font-medium 
                                     bg-gradient-to-r from-secondary/20 to-secondary/40
                                     border border-secondary/50 text-white rounded-full
                                     shadow-[0_0_10px_rgba(196,30,58,0.3)]">
                                Nieuw
                            </div>
                        </a>

                        <!-- Forum Dropdown Button -->
                        <div class="relative group">
                            <button class="nav-link px-4 py-2 text-white/90 font-medium flex items-center">
                                <span class="relative">Forum</span>
                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-500 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div class="absolute left-0 mt-1 w-56 opacity-0 invisible translate-y-2
                                        transition-all duration-300 ease-out group-hover:opacity-100 
                                        group-hover:visible group-hover:translate-y-0">
                                <div class="p-2 bg-white rounded-xl shadow-xl ring-1 ring-black/5">
                                    <a href="<?php echo URLROOT; ?>/forum" 
                                       class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/5
                                                   transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">Alle discussies</p>
                                            <p class="text-xs text-gray-500">Bekijk het forum</p>
                                        </div>
                                    </a>

                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <a href="<?php echo URLROOT; ?>/forum/create" 
                                           class="flex items-center px-3 py-2 mt-1 rounded-lg transition-colors duration-200
                                                  hover:bg-gray-50 group/item">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary/5
                                                       transition-transform duration-200 group-hover/item:scale-110">
                                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800">Start discussie</p>
                                                <p class="text-xs text-gray-500">Nieuw onderwerp</p>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Dropdown -->
                        <div class="relative group">
                            <button class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium py-2 flex items-center">
                                <span>Contact</span>
                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div class="absolute left-0 mt-1 w-48 opacity-0 invisible translate-y-2
                                        transition-all duration-300 ease-out group-hover:opacity-100 
                                        group-hover:visible group-hover:translate-y-0">
                                <div class="p-2 bg-white rounded-xl shadow-xl ring-1 ring-black/5">
                                    <a href="<?php echo URLROOT; ?>/contact" 
                                       class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/5
                                                   transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">Contact</p>
                                            <p class="text-xs text-gray-500">Neem contact op</p>
                                        </div>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/over-mij" 
                                       class="flex items-center px-3 py-2 mt-1 rounded-lg transition-colors duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary/5
                                                   transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">Over ons</p>
                                            <p class="text-xs text-gray-500">Leer ons kennen</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Auth Buttons -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="relative group">
                                <button class="flex items-center space-x-3 bg-white/10 px-5 py-2.5 rounded-full
                                              border-2 border-white/20 backdrop-blur-sm
                                              transform transition-all duration-300 ease-out
                                              hover:bg-secondary/20 hover:border-secondary/40 hover:scale-[1.02]
                                              active:scale-[0.98]">
                                    <div class="w-8 h-8 bg-gradient-to-tl from-secondary to-primary
                                               rounded-full flex items-center justify-center
                                               ring-2 ring-white/30 ring-offset-1 ring-offset-transparent
                                               shadow-inner transform transition-all duration-300
                                               group-hover:shadow-secondary/30 group-hover:scale-110">
                                        <span class="text-white font-bold text-lg">
                                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                        </span>
                                    </div>
                                    <span class="font-medium text-white">
                                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    </span>
                                    <svg class="w-4 h-4 text-white transition-transform duration-300 group-hover:rotate-180" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl py-3 px-1
                                            opacity-0 invisible transform scale-95 -translate-y-3
                                            transition-all duration-200 ease-out origin-top
                                            group-hover:opacity-100 group-hover:visible 
                                            group-hover:scale-100 group-hover:translate-y-0">
                                    
                                    <?php if($_SESSION['is_admin']): ?>
                                        <a href="<?php echo URLROOT; ?>/admin" 
                                           class="flex items-center px-4 py-3 rounded-xl
                                                  transition-colors duration-200 hover:bg-gray-50 group/item">
                                            <div class="w-9 h-9 bg-primary/10 rounded-lg flex items-center justify-center
                                                      transform transition-transform duration-200 group-hover/item:scale-110 
                                                      group-hover/item:rotate-3">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">Dashboard</p>
                                                <p class="text-xs text-gray-500">Beheer je website</p>
                                            </div>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo URLROOT; ?>/profile" 
                                       class="flex items-center px-4 py-3 rounded-xl
                                              transition-colors duration-200 hover:bg-gray-50 group/item">
                                        <div class="w-9 h-9 bg-secondary/10 rounded-lg flex items-center justify-center
                                                  transform transition-transform duration-200 group-hover/item:scale-110 
                                                  group-hover/item:rotate-3">
                                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Profiel</p>
                                            <p class="text-xs text-gray-500">Beheer je account</p>
                                        </div>
                                    </a>

                                    <div class="mx-3 my-2 border-t border-gray-100"></div>

                                    <a href="<?php echo URLROOT; ?>/logout" 
                                       class="flex items-center px-4 py-3 rounded-xl
                                              transition-colors duration-200 hover:bg-red-50 group/item">
                                        <div class="w-9 h-9 bg-red-500/10 rounded-lg flex items-center justify-center
                                                  transform transition-transform duration-200 group-hover/item:scale-110 
                                                  group-hover/item:rotate-3">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-red-600">Uitloggen</p>
                                            <p class="text-xs text-red-400">Tot ziens!</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/login" 
                               class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                                <span>Inloggen</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/register" 
                               class="signup-btn px-8 py-3 rounded-xl font-semibold">
                                Aanmelden
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

                                <a href="<?php echo URLROOT; ?>/stemwijzer" 
                                   class="flex items-center text-white/90 hover:text-white py-2 transition-colors duration-300">
                                    <span class="font-medium flex items-center">
                                        Stemwijzer
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 text-[10px] font-medium 
                                                     bg-gradient-to-r from-secondary/20 to-secondary/40
                                                     border border-secondary/50 text-white rounded-full
                                                     shadow-[0_0_10px_rgba(196,30,58,0.3)]">
                                            Nieuw
                                        </span>
                                    </span>
                                </a>

                                <!-- Contact Dropdown -->
                                <div class="space-y-3">
                                    <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                                 transition-colors duration-300" 
                                            onclick="toggleMobileSubmenu('contact-submenu')">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium">Contact</span>
                                        </div>
                                        <svg class="w-4 h-4 transform transition-transform duration-300" 
                                             id="contact-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="hidden pl-8 space-y-3 border-l border-white/10" id="contact-submenu">
                                        <a href="<?php echo URLROOT; ?>/contact" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Contact opnemen</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/over-mij" 
                                           class="flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                            <span class="font-medium">Over ons</span>
                                        </a>
                                    </div>
                                </div>
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
                                            <div>
                                                <span class="font-medium">Profiel</span>
                                                <span class="block text-xs text-gray-500">Beheer je account</span>
                                            </div>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/logout" 
                                           class="flex items-center text-red-300 hover:text-red-200 transition-colors duration-300">
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
                                        Aanmelden
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