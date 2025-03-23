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

// Controleer of we specifieke meta data hebben voor deze pagina (bijv. voor blogs)
$metaTitle = isset($data['title']) ? $data['title'] : (SITENAME . ' - Politiek voor iedereen');
$metaDescription = isset($data['description']) ? $data['description'] : $metaDescription;
$metaImage = isset($data['image']) ? $data['image'] : (URLROOT . '/public/img/og-image.jpg');
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:type" content="<?php echo isset($data['title']) ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($metaImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($metaImage); ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">

    <meta name="google-site-verification" content="e72Qn95mvwZrvfw5CvXBKfeIv0vSqmo88Fw-oTJ5sgw" />
    <title><?php echo $metaTitle; ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMS41IiBkPSJNMyAyMWgxOE0zIDIxVjhsOS02IDkgNnYxM003IDIxVjExbTQgMTBWMTFtNiAxMFYxMW0tOC00aDQiIHN0cm9rZT0iIzFhMzY1ZCIvPjwvc3ZnPg==">
    <link rel="icon" type="image/png" href="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMS41IiBkPSJNMyAyMWgxOE0zIDIxVjhsOS02IDkgNnYxM003IDIxVjExbTQgMTBWMTFtNiAxMFYxMW0tOC00aDQiIHN0cm9rZT0iIzFhMzY1ZCIvPjwvc3ZnPg==">
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
                        tertiary: '#F59E0B',   // Oranje voor accent kleuren
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'marquee': 'marquee 25s linear infinite',
                        'spin-slow': 'spin 8s linear infinite',
                        'wave': 'wave 2.5s ease-in-out infinite',
                        'bounce-subtle': 'bounce-subtle 2s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
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
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-100%)' }
                        },
                        'bounce-subtle': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        wave: {
                            '0%': { transform: 'rotate(0deg)' },
                            '10%': { transform: 'rotate(14deg)' },
                            '20%': { transform: 'rotate(-8deg)' },
                            '30%': { transform: 'rotate(14deg)' },
                            '40%': { transform: 'rotate(-4deg)' },
                            '50%': { transform: 'rotate(10deg)' },
                            '60%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(0deg)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
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

        /* Base effects */
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .nav-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #234876 50%, #2d5a94 100%);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        .fancy-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #234876 40%, #2d5a94 60%, #c41e3a 100%);
            background-size: 200% 200%;
            animation: gradient-shift 15s ease infinite;
        }
        
        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer-effect::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(to right, 
                rgba(255, 255, 255, 0) 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                rgba(255, 255, 255, 0) 100%);
            animation: shimmer 2s infinite;
        }
        
        /* Navigation elements */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link span {
            position: relative;
            z-index: 10;
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

        .nav-link::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(196, 30, 58, 0), rgba(196, 30, 58, 0.2));
            opacity: 0;
            z-index: 0;
            transition: opacity 0.4s ease;
        }

        .nav-link:hover::before {
            opacity: 1;
        }

        .nav-link:hover span::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .magic-link {
            position: relative;
            overflow: hidden;
        }
        
        .magic-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%) skewX(-15deg);
            transition: transform 0.5s ease;
        }
        
        .magic-link:hover::before {
            transform: translateX(100%) skewX(-15deg);
        }

        /* Effects */
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

        .glow-effect:hover::before {
            opacity: 1;
        }
        
        .active-glow {
            box-shadow: 0 0 15px rgba(196, 30, 58, 0.5);
            animation: pulse-glow 2s infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(196, 30, 58, 0.5); }
            50% { box-shadow: 0 0 25px rgba(196, 30, 58, 0.8); }
        }

        .scale-hover {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        box-shadow 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .scale-hover:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .hover-expand {
            transition: all 0.3s ease;
        }
        
        .hover-expand:hover {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        /* Keyframe Animations */
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
        
        @keyframes shimmer {
            0% { transform: translateX(-150%); }
            100% { transform: translateX(150%); }
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
        
        .animate-shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .animate-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(to right, 
                rgba(255, 255, 255, 0) 0%, 
                rgba(255, 255, 255, 0.3) 50%, 
                rgba(255, 255, 255, 0) 100%);
            transform: translateX(-100%);
            animation: shimmer 2.5s infinite;
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

        /* Interactive elements */
        .signup-btn {
            position: relative;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            color: #1a365d;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.15);
            border: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
        }

        .signup-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(30deg) translateY(100%);
            transition: transform 0.7s;
            z-index: 1;
            pointer-events: none;
        }

        .signup-btn:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #c41e3a 0%, #d4293f 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(196, 30, 58, 0.25);
        }
        
        .signup-btn:hover::after {
            transform: rotate(30deg) translateY(-100%);
        }

        .signup-btn:active {
            transform: translateY(-1px);
        }
        
        .dropdown-content {
            transform-origin: top center;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            visibility: hidden;
            transform: scale(0.95) translateY(-10px);
            pointer-events: none;
        }
        
        .group:hover .dropdown-content,
        .dropdown-content.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1) translateY(0);
            pointer-events: auto;
        }
        
        /* Decorative elements */
        .particle {
            position: absolute;
            display: block;
            pointer-events: none;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.3);
        }
        
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.5;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen overflow-x-hidden">
    <!-- Background blobs for decoration -->
    <div aria-hidden="true" class="fixed inset-0 overflow-hidden z-0 pointer-events-none">
        <div class="blob bg-primary/20 w-[500px] h-[500px] -top-[250px] -left-[250px] animate-pulse-slow"></div>
        <div class="blob bg-secondary/10 w-[600px] h-[600px] top-[30%] -right-[300px] animate-pulse-slow" style="animation-delay: 1s;"></div>
        <div class="blob bg-accent/10 w-[400px] h-[400px] -bottom-[200px] left-[10%] animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="flex-grow relative z-10">
        <!-- Beta Notification Popup - Geoptimaliseerd -->
        <div x-data="{ showBetaNotice: localStorage.getItem('betaNoticeShown') === null }"
             x-show="showBetaNotice"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/75 backdrop-blur-sm"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div x-show="showBetaNotice" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="transform scale-95 opacity-0" 
                     x-transition:enter-end="transform scale-100 opacity-100"
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="transform scale-100 opacity-100" 
                     x-transition:leave-end="transform scale-95 opacity-0"
                     class="relative bg-white w-full max-w-lg p-6 rounded-2xl text-left shadow-2xl mx-auto border border-gray-200">
                    <div class="absolute top-0 left-0 w-full h-1 overflow-hidden">
                        <div class="animate-shimmer h-full bg-gradient-to-r from-transparent via-primary to-transparent"></div>
                    </div>
                    
                    <div class="flex items-center mb-4">
                        <div class="mr-4 flex-shrink-0 bg-gradient-to-br from-primary to-primary/80 rounded-xl p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                            Welkom bij de Beta versie
                        </h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Deze website bevindt zich momenteel in de beta fase. Wij werken hard aan nieuwe functionaliteiten en verbeteringen. Jouw feedback is zeer waardevol voor ons! Heb je suggesties of kom je problemen tegen? Laat het ons weten via de contactpagina.
                    </p>
                    <div class="flex gap-3">
                        <button type="button"
                                @click="localStorage.setItem('betaNoticeShown', 'true'); showBetaNotice = false"
                                class="flex-1 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium transform transition-all duration-300 hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/60 active:translate-y-0">
                            Doorgaan
                        </button>
                        <a href="<?php echo URLROOT; ?>/contact"
                           @click="localStorage.setItem('betaNoticeShown', 'true'); showBetaNotice = false"
                           class="flex-1 bg-white text-primary border border-primary/20 px-4 py-2.5 rounded-xl text-sm font-medium text-center transform transition-all duration-300 hover:bg-gray-50 hover:border-primary hover:shadow-lg hover:shadow-primary/10 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/60 active:translate-y-0">
                            Feedback
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="relative z-50 sticky top-0">
        <!-- Decorative particles -->
        <div aria-hidden="true" class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="particle w-2 h-2 left-[10%] top-[20%] animate-float" style="animation-delay: 0s;"></div>
            <div class="particle w-1.5 h-1.5 left-[30%] top-[60%] animate-float" style="animation-delay: 0.5s;"></div>
            <div class="particle w-1 h-1 left-[70%] top-[30%] animate-float" style="animation-delay: 1s;"></div>
            <div class="particle w-2 h-2 left-[85%] top-[70%] animate-float" style="animation-delay: 1.5s;"></div>
            <div class="particle w-1 h-1 left-[20%] top-[35%] animate-float" style="animation-delay: 2s;"></div>
            <div class="particle w-1.5 h-1.5 left-[60%] top-[15%] animate-float" style="animation-delay: 2.5s;"></div>
        </div>
    
        <!-- Animated gradient background -->
        <div class="absolute inset-0 fancy-gradient opacity-95"></div>
        
        <!-- Glassmorphism overlay -->
        <div class="absolute inset-0 backdrop-blur-md bg-white/5"></div>
        
        <!-- Decorative top border -->
        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-white/50 to-transparent"></div>

        <!-- Navigation content -->
        <div class="relative">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-4 group">
                        <div class="relative">
                            <!-- Enhanced logo background with 3D effect -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-white/30 via-white/20 to-transparent 
                                        flex items-center justify-center overflow-hidden
                                        border border-white/30 backdrop-blur-lg shadow-lg
                                        transform transition-all duration-500 ease-out
                                        group-hover:scale-110 group-hover:rotate-6 group-hover:border-secondary/60
                                        group-hover:shadow-secondary/20 group-hover:from-white/40">
                                
                                <!-- 3D shadow effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-black/10 to-transparent opacity-30 
                                            transform rotate-12 scale-90 translate-x-1 translate-y-1 group-hover:opacity-0 
                                            transition-all duration-500"></div>
                                
                                <!-- Animated highlight sweep -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent
                                            translate-x-[-100%] group-hover:translate-x-[150%] transition-transform duration-1000"></div>
                                
                                <!-- Logo icon with enhanced animations -->
                                <svg class="w-8 h-8 text-white relative z-10 transform transition-all duration-500 ease-out
                                            group-hover:scale-110 group-hover:text-secondary" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="1.5"
                                          d="M3 21h18M3 21V8l9-6 9 6v13M7 21V11m4 10V11m6 10V11m-8-4h4"/>
                                </svg>
                                
                                <!-- Sparkle effect elements -->
                                <div class="absolute top-1 right-2 w-1 h-1 bg-white rounded-full opacity-0 
                                            group-hover:opacity-80 transition-opacity duration-300 delay-150"></div>
                                <div class="absolute bottom-2 left-2 w-1.5 h-1.5 bg-white rounded-full opacity-0 
                                            group-hover:opacity-80 transition-opacity duration-300 delay-300"></div>
                            </div>
                            
                            <!-- Enhanced floating badge with glow effect -->
                            <div class="absolute -top-2 -right-2 w-7 h-7 bg-gradient-to-br from-secondary to-secondary/70
                                        rounded-full flex items-center justify-center overflow-hidden
                                        shadow-lg shadow-secondary/30 border border-white/20
                                        transform transition-all duration-500 ease-out
                                        group-hover:scale-125 group-hover:rotate-12 group-hover:shadow-secondary/50
                                        group-hover:border-white/30">
                                <!-- Animated pulsing glow -->
                                <div class="absolute inset-0 bg-secondary/60 animate-pulse-slow rounded-full 
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <!-- Animated shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent
                                            translate-x-[-100%] group-hover:translate-x-[200%] transition-transform duration-1000"></div>
                                
                                <svg class="w-4 h-4 text-white relative z-10 transform transition-transform duration-500
                                            group-hover:scale-110 group-hover:rotate-[-12deg]" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Enhanced text section with animated underline and glow -->
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold tracking-tight text-white relative
                                         transition-all duration-500 ease-out group-hover:text-white
                                         drop-shadow-md">
                                <?php echo SITENAME; ?>
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-secondary via-secondary/80 to-secondary/60
                                             transition-all duration-500 ease-out group-hover:w-full"></span>
                            </span>
                            <span class="text-sm text-white/80 font-medium tracking-wide
                                         transition-all duration-500 ease-out group-hover:text-white">
                                <span class="inline-block transform transition-transform group-hover:translate-x-1 duration-500">Samen</span> 
                                <span class="inline-block transform transition-transform group-hover:translate-x-0.5 duration-500 delay-75">bouwen</span> 
                                <span class="inline-block transform transition-transform group-hover:translate-x-0 duration-500 delay-150">aan</span> 
                                <span class="inline-block transform transition-transform group-hover:translate-x-0.5 duration-500 delay-200">democratie</span>
                            </span>
                        </div>
                    </a>

                    <!-- Desktop Navigation - Enhanced with animations -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <a href="<?php echo URLROOT; ?>/" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group rounded-lg">
                            <span class="relative">Home</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/blogs" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group rounded-lg">
                            <span class="relative">Blogs</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/nieuws" 
                           class="nav-link px-4 py-2 text-white/90 font-medium group rounded-lg">
                            <span class="relative">Nieuws</span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/stemwijzer" 
                           class="flex items-center nav-link px-4 py-2 text-white/90 font-medium group rounded-lg relative">
                            <span class="relative">Stemwijzer</span>
                            <div class="ml-2 inline-flex items-center px-2 py-0.5 text-[10px] font-medium 
                                      bg-gradient-to-r from-secondary/30 to-secondary/50
                                      border border-secondary/50 text-white rounded-full
                                      shadow-[0_0_10px_rgba(196,30,58,0.3)] animate-pulse-slow">
                                Nieuw
                            </div>
                        </a>

                        <!-- Forum Dropdown Button - Enhanced with better animations -->
                        <div class="relative group">
                            <button class="nav-link px-4 py-2 text-white/90 font-medium flex items-center rounded-lg">
                                <span class="relative">Forum</span>
                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div class="absolute left-0 mt-2 w-60 dropdown-content z-50">
                                <div class="p-3 bg-white rounded-xl shadow-2xl ring-1 ring-black/5 border border-gray-100">
                                    <!-- Subtle decorative accent at top -->
                                    <div class="absolute top-0 left-4 right-4 h-0.5 bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
                                    
                                    <a href="<?php echo URLROOT; ?>/forum" 
                                       class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/5
                                                   transition-transform duration-200 group-hover/item:scale-110 
                                                   group-hover/item:rotate-3 group-hover/item:bg-primary/10">
                                            <svg class="w-5 h-5 text-primary transition-all duration-200 
                                                        group-hover/item:text-primary/80" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800 transition-colors duration-200 
                                                      group-hover/item:text-primary">Alle discussies</p>
                                            <p class="text-xs text-gray-500">Bekijk het forum</p>
                                        </div>
                                    </a>

                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <a href="<?php echo URLROOT; ?>/forum/create" 
                                           class="flex items-center px-3 py-2.5 mt-1 rounded-lg transition-all duration-200
                                                  hover:bg-gray-50 group/item">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary/5
                                                       transition-transform duration-200 group-hover/item:scale-110
                                                       group-hover/item:rotate-3 group-hover/item:bg-secondary/10">
                                                <svg class="w-5 h-5 text-secondary transition-all duration-200 
                                                            group-hover/item:text-secondary/80" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-800 transition-colors duration-200 
                                                          group-hover/item:text-secondary">Start discussie</p>
                                                <p class="text-xs text-gray-500">Nieuw onderwerp</p>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Dropdown - Enhanced -->
                        <div class="relative group">
                            <button class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium py-2 flex items-center px-4 rounded-lg">
                                <span>Contact</span>
                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div class="absolute left-0 mt-2 w-60 dropdown-content z-50">
                                <div class="p-3 bg-white rounded-xl shadow-2xl ring-1 ring-black/5 border border-gray-100">
                                    <!-- Subtle decorative accent at top -->
                                    <div class="absolute top-0 left-4 right-4 h-0.5 bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
                                    
                                    <a href="<?php echo URLROOT; ?>/contact" 
                                       class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/5
                                                   transition-transform duration-200 group-hover/item:scale-110
                                                   group-hover/item:rotate-3 group-hover/item:bg-primary/10">
                                            <svg class="w-5 h-5 text-primary transition-all duration-200 
                                                        group-hover/item:text-primary/80" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800 transition-colors duration-200 
                                                      group-hover/item:text-primary">Contact</p>
                                            <p class="text-xs text-gray-500">Neem contact op</p>
                                        </div>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/over-mij" 
                                       class="flex items-center px-3 py-2.5 mt-1 rounded-lg transition-all duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary/5
                                                   transition-transform duration-200 group-hover/item:scale-110
                                                   group-hover/item:rotate-3 group-hover/item:bg-secondary/10">
                                            <svg class="w-5 h-5 text-secondary transition-all duration-200 
                                                        group-hover/item:text-secondary/80" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800 transition-colors duration-200 
                                                      group-hover/item:text-secondary">Over ons</p>
                                            <p class="text-xs text-gray-500">Leer ons kennen</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Auth Buttons - Enhanced with better animations and styling -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="relative group">
                                <button class="flex items-center space-x-3 bg-white/10 px-5 py-2.5 rounded-full
                                              border border-white/30 backdrop-blur-sm
                                              transform transition-all duration-300 ease-out
                                              hover:bg-white/15 hover:border-secondary/40 hover:scale-[1.02]
                                              hover:shadow-lg hover:shadow-secondary/10
                                              active:scale-[0.98] overflow-hidden">
                                    <!-- Animated highlight effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent
                                                translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-800"></div>
                                    
                                    <div class="w-8 h-8 bg-gradient-to-br from-secondary via-secondary/80 to-primary
                                               rounded-full flex items-center justify-center
                                               ring-2 ring-white/30 ring-offset-1 ring-offset-transparent
                                               shadow-inner transform transition-all duration-300
                                               group-hover:shadow-secondary/30 group-hover:scale-110
                                               group-hover:ring-white/50">
                                        <span class="text-white font-bold text-sm">
                                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                        </span>
                                    </div>
                                    <span class="font-medium text-white relative">
                                        <span class="relative z-10"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary/50 
                                                     transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                    <svg class="w-4 h-4 text-white transition-transform duration-300 group-hover:rotate-180" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Enhanced Dropdown Menu -->
                                <div class="absolute right-0 mt-3 w-64 dropdown-content z-50">
                                    <div class="bg-white rounded-2xl shadow-2xl py-3 px-1 border border-gray-100">
                                        <!-- Subtle decorative accent at top -->
                                        <div class="absolute top-0 left-4 right-4 h-0.5 bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
                                        
                                        <?php if($_SESSION['is_admin']): ?>
                                            <a href="<?php echo URLROOT; ?>/admin" 
                                               class="flex items-center px-4 py-3 rounded-xl
                                                      transition-all duration-200 hover:bg-gray-50 group/item">
                                                <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center
                                                          transform transition-transform duration-200 group-hover/item:scale-110 
                                                          group-hover/item:rotate-3 group-hover/item:bg-primary/10">
                                                    <svg class="w-5 h-5 text-primary transition-colors duration-200
                                                                group-hover/item:text-primary/80" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-900 transition-colors duration-200
                                                              group-hover/item:text-primary">Dashboard</p>
                                                    <p class="text-xs text-gray-500">Beheer je website</p>
                                                </div>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?php echo URLROOT; ?>/profile" 
                                           class="flex items-center px-4 py-3 rounded-xl
                                                  transition-all duration-200 hover:bg-gray-50 group/item">
                                            <div class="w-10 h-10 bg-secondary/5 rounded-lg flex items-center justify-center
                                                      transform transition-transform duration-200 group-hover/item:scale-110 
                                                      group-hover/item:rotate-3 group-hover/item:bg-secondary/10">
                                                <svg class="w-5 h-5 text-secondary transition-colors duration-200
                                                            group-hover/item:text-secondary/80" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900 transition-colors duration-200
                                                          group-hover/item:text-secondary">Profiel</p>
                                                <p class="text-xs text-gray-500">Beheer je account</p>
                                            </div>
                                        </a>

                                        <div class="mx-3 my-2 border-t border-gray-100"></div>

                                        <a href="<?php echo URLROOT; ?>/logout" 
                                           class="flex items-center px-4 py-3 rounded-xl
                                                  transition-all duration-200 hover:bg-red-50 group/item">
                                            <div class="w-10 h-10 bg-red-500/5 rounded-lg flex items-center justify-center
                                                      transform transition-transform duration-200 group-hover/item:scale-110 
                                                      group-hover/item:rotate-3 group-hover/item:bg-red-500/10">
                                                <svg class="w-5 h-5 text-red-500 transition-colors duration-200
                                                            group-hover/item:text-red-600" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-red-600 transition-colors duration-200
                                                          group-hover/item:text-red-700">Uitloggen</p>
                                                <p class="text-xs text-red-400">Tot ziens!</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/login" 
                               class="nav-link magic-link text-white/90 hover:text-white transition-all duration-300 font-medium py-2 px-5 rounded-lg">
                                <span>Inloggen</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/register" 
                               class="signup-btn px-8 py-3 rounded-xl font-semibold transition-all duration-300 relative overflow-hidden group">
                                <span class="relative z-10">Aanmelden</span>
                                <!-- Subtle hover animation -->
                                <span class="absolute inset-0 bg-gradient-to-r from-secondary/0 via-secondary/0 to-secondary/0 
                                            group-hover:from-secondary group-hover:via-secondary/90 group-hover:to-secondary
                                            transition-all duration-500 ease-out -z-0"></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Enhanced Mobile Menu Button -->
                    <button class="lg:hidden relative z-50 p-2.5 text-white hover:bg-white/15 rounded-xl border border-white/20 
                              transition-all duration-300 hover:border-white/40 hover:scale-105 active:scale-95 
                              hover:shadow-lg hover:shadow-white/5 group" 
                            id="mobile-menu-button">
                        <div class="relative">
                            <svg class="w-6 h-6 transform transition-transform duration-300 group-hover:rotate-90" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <!-- Subtle glow effect -->
                            <span class="absolute inset-0 rounded-full bg-white/20 blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        </div>
                    </button>
                </div>

                <!-- Enhanced Mobile Menu with better animations -->
                <div class="lg:hidden hidden fixed inset-0 z-40" id="mobile-menu-overlay">
                    <!-- Backdrop with blur effect -->
                    <div class="absolute inset-0 bg-primary/90 backdrop-blur-md"
                         onclick="closeMobileMenu()"></div>
                    
                    <!-- Menu Content -->
                    <div class="relative h-full w-full max-w-xs ml-auto bg-gradient-to-b from-primary via-primary/98 to-primary/95 
                               shadow-2xl overflow-y-auto transform transition-transform duration-500 translate-x-0">
                        <div class="p-6 space-y-6">
                            <!-- Close Button -->
                            <div class="flex justify-end">
                                <button class="p-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-xl 
                                              transition-all duration-300 hover:scale-105 active:scale-95 
                                              hover:shadow-lg hover:shadow-white/5 group" 
                                        onclick="closeMobileMenu()">
                                    <svg class="w-6 h-6 transform transition-transform duration-300 group-hover:rotate-90" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Enhanced Navigation Links -->
                            <nav class="space-y-5">
                                <a href="<?php echo URLROOT; ?>/" 
                                   class="flex items-center text-white/90 hover:text-white p-2.5 transition-all duration-300 
                                          rounded-xl hover:bg-white/5 hover:pl-4 group">
                                    <div class="mr-3 p-2 bg-white/10 rounded-lg transition-all duration-300 
                                                group-hover:bg-white/15 group-hover:scale-110 group-hover:rotate-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Home</span>
                                </a>

                                <!-- Media Section -->
                                <div class="space-y-3">
                                    <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                                 p-2.5 rounded-xl transition-all duration-300 hover:bg-white/5 hover:pl-4 group" 
                                            onclick="toggleMobileSubmenu('media-submenu')">
                                        <div class="flex items-center">
                                            <div class="mr-3 p-2 bg-white/10 rounded-lg transition-all duration-300 
                                                        group-hover:bg-white/15 group-hover:scale-110 group-hover:rotate-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <span class="font-medium">Media</span>
                                        </div>
                                        <svg class="w-4 h-4 transform transition-transform duration-300" 
                                             id="media-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="hidden pl-12 space-y-3 border-l border-white/10" id="media-submenu">
                                        <a href="<?php echo URLROOT; ?>/nieuws" 
                                           class="flex items-center text-white/80 hover:text-white p-2 rounded-lg 
                                                  transition-all duration-300 hover:bg-white/5 hover:pl-4">
                                            <span class="font-medium">Nieuws</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/blogs" 
                                           class="flex items-center text-white/80 hover:text-white p-2 rounded-lg 
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
                                    <div class="flex items-center space-x-4 mb-6 bg-white/5 p-3 rounded-xl">
                                        <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary/70 rounded-xl 
                                                  flex items-center justify-center shadow-lg relative overflow-hidden group">
                                            <!-- Animated background effect -->
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent
                                                      translate-x-[-100%] animate-shimmer"></div>
                                            <span class="text-white text-xl font-bold relative z-10">
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
                                               class="flex items-center text-white/90 hover:text-white p-2.5 rounded-xl 
                                                      transition-all duration-300 hover:bg-white/5 hover:pl-4 group">
                                                <div class="mr-3 p-2 bg-white/10 rounded-lg transition-all duration-300 
                                                            group-hover:bg-white/15 group-hover:scale-110 group-hover:rotate-3">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <span class="font-medium">Dashboard</span>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo URLROOT; ?>/profile" 
                                           class="flex items-center text-white/90 hover:text-white p-2.5 rounded-xl 
                                                  transition-all duration-300 hover:bg-white/5 hover:pl-4 group">
                                            <div class="mr-3 p-2 bg-white/10 rounded-lg transition-all duration-300 
                                                        group-hover:bg-white/15 group-hover:scale-110 group-hover:rotate-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-medium">Profiel</span>
                                                <span class="block text-xs text-white/60">Beheer je account</span>
                                            </div>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/logout" 
                                           class="flex items-center text-red-300 hover:text-red-200 p-2.5 rounded-xl 
                                                  transition-all duration-300 hover:bg-red-500/10 hover:pl-4 group">
                                            <div class="mr-3 p-2 bg-red-500/10 rounded-lg transition-all duration-300 
                                                        group-hover:bg-red-500/20 group-hover:scale-110 group-hover:rotate-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-medium">Uitloggen</span>
                                                <span class="block text-xs text-red-300/70">Tot ziens!</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="pt-6 border-t border-white/10 space-y-4">
                                    <a href="<?php echo URLROOT; ?>/login" 
                                       class="flex items-center text-white/90 hover:text-white p-2.5 rounded-xl 
                                              transition-all duration-300 hover:bg-white/5 hover:pl-4 group">
                                        <div class="mr-3 p-2 bg-white/10 rounded-lg transition-all duration-300 
                                                    group-hover:bg-white/15 group-hover:scale-110 group-hover:rotate-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium">Inloggen</span>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/register" 
                                       class="flex items-center justify-center px-6 py-3.5 bg-white text-primary font-semibold 
                                              rounded-xl shadow-lg transition-all duration-300 
                                              hover:bg-secondary hover:text-white transform hover:scale-[1.02]
                                              active:scale-[0.98] relative overflow-hidden group">
                                        <span class="relative z-10">Aanmelden</span>
                                        <!-- Animated highlight effect -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-secondary/20 to-transparent
                                                    translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Enhanced Mobile Menu Toggle Script -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu-overlay');
        const mobileMenuContent = mobileMenu.querySelector('.max-w-xs');

        function toggleMobileMenu() {
            if (mobileMenu.classList.contains('hidden')) {
                // Open menu with animations
                mobileMenu.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                // Animate menu sliding in
                setTimeout(() => {
                    mobileMenuContent.classList.add('translate-x-0');
                    mobileMenuContent.classList.remove('translate-x-full');
                }, 10);
            } else {
                // Close menu with animations
                mobileMenuContent.classList.remove('translate-x-0');
                mobileMenuContent.classList.add('translate-x-full');
                
                // Hide completely after animation
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }
        }

        function closeMobileMenu() {
            // Close menu with animations
            mobileMenuContent.classList.remove('translate-x-0');
            mobileMenuContent.classList.add('translate-x-full');
            
            // Hide completely after animation
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        mobileMenuButton.addEventListener('click', toggleMobileMenu);

        // Close mobile menu when clicking outside
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                closeMobileMenu();
            }
        });

        // Mobile submenu toggle functionality with enhanced animations
        function toggleMobileSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + '-icon');
            
            if (submenu.classList.contains('hidden')) {
                // First add the display style but keep it invisible for animation
                submenu.classList.remove('hidden');
                submenu.style.opacity = '0';
                submenu.style.maxHeight = '0';
                
                // Force a reflow to enable transition
                void submenu.offsetWidth;
                
                // Then animate in
                submenu.style.opacity = '1';
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            } else {
                // Animate out
                submenu.style.opacity = '0';
                submenu.style.maxHeight = '0';
                icon.classList.remove('rotate-180');
                
                // Hide completely after animation
                setTimeout(() => {
                    submenu.classList.add('hidden');
                    submenu.style.removeProperty('opacity');
                    submenu.style.removeProperty('max-height');
                }, 300);
            }
        }
        
        // Initialize AOS with enhanced settings
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize particle effects for decorative elements
            const particles = document.querySelectorAll('.particle');
            particles.forEach(particle => {
                // Make particles visible when JavaScript is enabled
                particle.style.display = 'block';
                
                // Randomize initial positions slightly
                const xOffset = Math.random() * 20 - 10; // -10px to +10px
                const yOffset = Math.random() * 20 - 10; // -10px to +10px
                particle.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
            
            // Initialize AOS animation library
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: false,
                mirror: true,
                anchorPlacement: 'top-bottom'
            });
        });
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