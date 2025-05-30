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

// Define keywords for each page
$metaKeywords = [
    'home' => 'politiek, nieuws, blogs, Nederland, politieke partijen, democratie, verkiezingen, overheid, Nederlandse politiek, Haags nieuws, Politiek Praat',
    'blogs' => 'politieke blogs, opinieartikelen, politieke analyses, Nederlandse politiek, politiek commentaar, verkiezingen, columnisten, Politiek Praat',
    'nieuws' => 'politiek nieuws, Nederlands nieuws, Haags nieuws, regering, oppositie, kabinet, ministeries, Tweede Kamer, Politiek Praat',
    'stemwijzer' => 'stemwijzer, politieke keuze, verkiezingen, stemhulp, kieskompas, partijkeuze, verkiezingen 2025, stemadvies, Politiek Praat',
    'forum' => 'politiek forum, politieke discussie, debat, meningen, Nederlandse politiek, politieke standpunten, actuele discussies, Politiek Praat',
    'contact' => 'contact, vragen, feedback, hulp, informatie, Politiek Praat, bereikbaarheid'
];

// Get current page from URL
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$metaDescription = $metaDescriptions[$currentPage] ?? $metaDescriptions['home'];
$metaKeyword = $metaKeywords[$currentPage] ?? $metaKeywords['home'];

// Controleer of we specifieke meta data hebben voor deze pagina (bijv. voor blogs)
$metaTitle = isset($data['title']) ? $data['title'] : (SITENAME . ' - Politiek voor iedereen');
$metaDescription = isset($data['description']) ? $data['description'] : $metaDescription;
$metaKeyword = isset($data['keywords']) ? $data['keywords'] : $metaKeyword;
$metaImage = isset($data['image']) ? $data['image'] : (URLROOT . '/public/img/og-image.jpg');
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeyword); ?>">
    
    <!-- Additional SEO meta tags -->
    <meta name="author" content="PolitiekPraat">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="language" content="Dutch">
    <meta name="revisit-after" content="7 days">
    <meta name="generator" content="PolitiekPraat CMS">
    <meta name="rating" content="general">
    <meta name="geo.region" content="NL">
    <meta name="geo.placename" content="Nederland">
    
    <!-- Improved SEO Performance -->
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="<?php echo URLROOT; ?>/images/favicon-512x512.png" as="image">
    
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

    <!-- Sitemap Link -->
    <link rel="sitemap" type="application/xml" href="<?php echo URLROOT; ?>/sitemap.xml">
    
    <!-- Provide translation alternates if available in the future -->
    <link rel="alternate" hreflang="nl-nl" href="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Browser configs -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?php echo SITENAME; ?>">
    <meta name="application-name" content="<?php echo SITENAME; ?>">
    <meta name="msapplication-TileColor" content="#1a365d">
    <meta name="theme-color" content="#1a365d">

    <meta name="google-site-verification" content="e72Qn95mvwZrvfw5CvXBKfeIv0vSqmo88Fw-oTJ5sgw" />
    <title><?php echo $metaTitle; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo URLROOT; ?>/images/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo URLROOT; ?>/images/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo URLROOT; ?>/images/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo URLROOT; ?>/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo URLROOT; ?>/images/android-chrome-192x192.png">
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/favicon.ico">
    <meta name="msapplication-TileImage" content="<?php echo URLROOT; ?>/images/favicon-144x144.png">
    <meta name="msapplication-TileColor" content="#1a365d">
    <meta name="theme-color" content="#1a365d">
    <link rel="manifest" href="<?php echo URLROOT; ?>/site.webmanifest">
    
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
    
    <!-- Schema.org Structured Data for Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php echo SITENAME; ?>",
        "url": "<?php echo URLROOT; ?>",
        "logo": "<?php echo URLROOT; ?>/images/favicon-512x512.png",
        "description": "<?php echo htmlspecialchars($metaDescriptions['home']); ?>",
        "sameAs": [
            "https://twitter.com/politiekpraat",
            "https://facebook.com/politiekpraat",
            "https://linkedin.com/company/politiekpraat"
        ]
    }
    </script>

    <!-- Schema.org Structured Data for WebSite -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php echo SITENAME; ?>",
        "url": "<?php echo URLROOT; ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "<?php echo URLROOT; ?>/zoeken?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- Schema.org Structured Data for WebPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "<?php echo htmlspecialchars($metaTitle); ?>",
        "description": "<?php echo htmlspecialchars($metaDescription); ?>",
        "url": "<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>",
        "publisher": {
            "@type": "Organization",
            "name": "<?php echo SITENAME; ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo URLROOT; ?>/images/favicon-512x512.png"
            }
        },
        "inLanguage": "nl-NL",
        "isPartOf": {
            "@type": "WebSite",
            "url": "<?php echo URLROOT; ?>"
        }
    }
    </script>
    
    <?php if(isset($data['is_blog']) && $data['is_blog']): ?>
    <!-- Schema.org Structured Data for Blog Article -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>"
        },
        "headline": "<?php echo htmlspecialchars($data['title']); ?>",
        "description": "<?php echo htmlspecialchars($data['description']); ?>",
        "image": "<?php echo htmlspecialchars($data['image']); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo htmlspecialchars($data['author'] ?? 'PolitiekPraat'); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?php echo SITENAME; ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo URLROOT; ?>/images/favicon-512x512.png"
            }
        },
        "datePublished": "<?php echo isset($data['created_at']) ? date('c', strtotime($data['created_at'])) : date('c'); ?>",
        "dateModified": "<?php echo isset($data['updated_at']) ? date('c', strtotime($data['updated_at'])) : date('c'); ?>"
    }
    </script>
    <?php endif; ?>
    
    <?php if(isset($data['is_news']) && $data['is_news']): ?>
    <!-- Schema.org Structured Data for News Article -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>"
        },
        "headline": "<?php echo htmlspecialchars($data['title']); ?>",
        "description": "<?php echo htmlspecialchars($data['description']); ?>",
        "image": "<?php echo htmlspecialchars($data['image']); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo htmlspecialchars($data['author'] ?? 'PolitiekPraat'); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?php echo SITENAME; ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo URLROOT; ?>/images/favicon-512x512.png"
            }
        },
        "datePublished": "<?php echo isset($data['created_at']) ? date('c', strtotime($data['created_at'])) : date('c'); ?>",
        "dateModified": "<?php echo isset($data['updated_at']) ? date('c', strtotime($data['updated_at'])) : date('c'); ?>"
    }
    </script>
    <?php endif; ?>
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5550921434025979" crossorigin="anonymous"></script>

    <style>
        [x-cloak] { 
            display: none !important; 
        }

        /* Mobile menu animation classes */
        .mobile-menu-enter {
            transform: translateX(100%);
        }
        .mobile-menu-enter-active {
            transform: translateX(0);
            transition: transform 0.3s ease-out;
        }
        .mobile-menu-exit {
            transform: translateX(0);
        }
        .mobile-menu-exit-active {
            transform: translateX(100%);
            transition: transform 0.3s ease-in;
        }

        /* Base effects - refined */
        .glass-effect {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        /* Modern navigation background */
        .nav-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #1e4178 50%, #254b8f 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        /* Enhanced fancy gradient background */
        .fancy-gradient {
            background: linear-gradient(135deg, #1a365d 0%, #1e4178 35%, #2a5495 65%, #c41e3a 100%);
            background-size: 250% 250%;
            animation: gradient-shift 18s ease infinite;
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
                rgba(255, 255, 255, 0.25) 50%, 
                rgba(255, 255, 255, 0) 100%);
            animation: shimmer 2.5s infinite;
        }
        
        /* Navigation elements - enhanced */
        .nav-link {
            position: relative;
            transition: all 0.35s cubic-bezier(0.25, 0.1, 0.25, 1);
        }

        .nav-link span {
            position: relative;
            z-index: 10;
        }

        .nav-link span::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #c41e3a, #ff4d6d);
            transform: scaleX(0);
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            transform-origin: right;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.15));
            opacity: 0;
            z-index: 0;
            transition: opacity 0.5s ease;
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            transform: translateX(-100%) skewX(-15deg);
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        }
        
        .magic-link:hover::before {
            transform: translateX(100%) skewX(-15deg);
        }

        /* Effects - modern and subtle */
        .glow-effect {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glow-effect::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #c41e3a, #3d5a80, #1a365d);
            background-size: 300% 300%;
            animation: gradient-shift 5s ease infinite;
            border-radius: 0.6rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .glow-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .glow-effect:hover::before {
            opacity: 1;
        }
        
        .active-glow {
            box-shadow: 0 0 15px rgba(196, 30, 58, 0.4);
            animation: pulse-glow 2.5s infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(196, 30, 58, 0.4); }
            50% { box-shadow: 0 0 25px rgba(196, 30, 58, 0.7); }
        }

        .scale-hover {
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1), 
                        box-shadow 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            will-change: transform, box-shadow;
        }

        .scale-hover:hover {
            transform: scale(1.03) translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .hover-lift {
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1), 
                       box-shadow 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            will-change: transform, box-shadow;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        
        .hover-expand {
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }
        
        .hover-expand:hover {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        /* Keyframe Animations - refined */
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
            50% { opacity: 0.7; }
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
            animation: shimmer 3s infinite;
        }

        /* Enhanced dropdown styling */
        .dropdown-content {
            transform-origin: top center;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.97);
            pointer-events: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            /* Add padding to create a hover gap */
            padding-top: 10px;
            margin-top: -10px;
        }
        
        .group:hover .dropdown-content,
        .dropdown-content.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        /* Modern header - significantly improved */
        .header-container {
            background: rgba(26, 54, 93, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-logo-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .header-logo-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Enhanced Sign up button */
        .signup-btn {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(250, 250, 250, 1) 100%);
            color: #1a365d;
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.15);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            transform: translateY(0);
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
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.2);
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

        /* New shadow-glow utilities for poll redesign */
        .shadow-glow {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.15), 
                        0 0 25px rgba(59, 130, 246, 0.1);
        }
        
        .shadow-glow-sm {
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.12), 
                        0 0 12px rgba(59, 130, 246, 0.08);
        }
        
        /* Enhanced animations for poll elements */
        @keyframes pulse-subtle {
            0%, 100% {
                opacity: 0.85;
            }
            50% {
                opacity: 1;
            }
        }
        
        .animate-pulse-subtle {
            animation: pulse-subtle 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
    </div>

    <!-- Main Navigation - Modernized -->
    <nav class="relative z-50 sticky top-0 header-container">
        <!-- Subtle top reflection line -->
        <div aria-hidden="true" class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        
        <!-- Animated gradient background -->
        <div class="absolute inset-0 fancy-gradient opacity-95"></div>
        
        <!-- Glassmorphism overlay -->
        <div class="absolute inset-0 backdrop-blur-md bg-primary/10"></div>
        
        <!-- Navigation content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo - Modern & Elegant -->
                <a href="<?php echo URLROOT; ?>" class="flex items-center space-x-3 group relative z-10">
                    <div class="relative overflow-hidden flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-xl 
                                bg-gradient-to-br from-primary to-primary/80 
                                backdrop-blur-sm shadow-lg transition-all duration-500 
                                group-hover:from-primary/90 group-hover:to-secondary/80 group-hover:scale-105 
                                group-hover:shadow-secondary/20 group-hover:shadow-xl border border-white/10 
                                group-hover:border-white/20">
                        
                        <!-- PolitiekPraat Logo met verbeterde effecten -->
                        <img src="<?php echo URLROOT; ?>/images/favicon-512x512.png" 
                             alt="PolitiekPraat Logo" 
                             class="w-8 h-8 md:w-9 md:h-9 object-contain transition-all duration-500
                                    group-hover:scale-110 group-hover:rotate-3 group-hover:brightness-110">
                        
                        <!-- Dynamic glow effect -->
                        <div class="absolute inset-0 rounded-xl bg-white/10 opacity-0 blur-md group-hover:opacity-80 
                                    transition-all duration-500 animate-pulse-slow"></div>
                        
                        <!-- Animated particles for decoration -->
                        <div class="absolute h-1 w-1 rounded-full bg-white/40 top-1 left-1 opacity-0 
                                    group-hover:opacity-100 group-hover:animate-ping"></div>
                        <div class="absolute h-1 w-1 rounded-full bg-white/40 bottom-2 right-1 opacity-0 
                                    group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.5s"></div>
                    </div>
                    
                    <!-- Text with enhanced styling and animations -->
                    <div class="flex flex-col">
                        <div class="relative overflow-hidden">
                            <span class="text-lg md:text-xl font-bold text-white tracking-tight relative 
                                         transition-all duration-500 group-hover:text-transparent 
                                         group-hover:bg-clip-text group-hover:bg-gradient-to-r 
                                         group-hover:from-white group-hover:to-white/80">
                                <?php echo SITENAME; ?>
                            </span>
                            <!-- Enhanced underline animation -->
                            <span class="absolute bottom-0 left-0 h-[2px] w-0 bg-gradient-to-r from-secondary via-secondary/80 to-white/70 
                                        group-hover:w-full transition-all duration-700 ease-out"></span>
                        </div>
                        <!-- Tagline with improved styling -->
                        <span class="text-xs md:text-sm text-white/70 font-medium transition-all duration-500 
                                    group-hover:text-white/90 italic">
                            <span class="hidden md:inline">Samen</span> bouwen aan democratie
                            <!-- Subtle dot indicator -->
                            <span class="inline-block w-1.5 h-1.5 ml-1 bg-secondary rounded-full 
                                        animate-pulse-slow"></span>
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links - Enhanced -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="<?php echo URLROOT; ?>/" 
                       class="nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium rounded-lg">
                        <span class="relative">Home</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium rounded-lg">
                        <span class="relative">Blogs</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/nieuws" 
                       class="nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium rounded-lg">
                        <span class="relative">Nieuws</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/stemwijzer" 
                       class="flex items-center nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium rounded-lg">
                        <span class="relative">Stemwijzer</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/partijen" 
                       class="flex items-center nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium rounded-lg">
                        <span class="relative">Partijen</span>
                    </a>

                    <!-- Forum Dropdown - Elegant implementation -->
                    <div class="relative group" style="display: none;">
                        <button class="nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium flex items-center rounded-lg">
                            <span class="relative">Forum</span>
                            <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute left-0 mt-2 w-56 dropdown-content z-50" style="margin-top: -5px; padding-top: 15px;">
                            <div class="p-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                <!-- Subtle top accent -->
                                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary/30 via-secondary/30 to-primary/30"></div>
                                
                                <a href="<?php echo URLROOT; ?>/forum" 
                                   class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200
                                          hover:bg-gray-50 group/item">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/5
                                              transition-transform duration-200 group-hover/item:scale-110">
                                        <svg class="w-5 h-5 text-primary transition-all duration-200 
                                                  group-hover/item:text-primary/90" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                       class="flex items-center px-3 py-2.5 mt-1 rounded-lg transition-all duration-200
                                              hover:bg-gray-50 group/item">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-secondary/5
                                                  transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-secondary transition-all duration-200" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Contact Dropdown - Elegant implementation -->
                    <div class="relative group">
                        <button class="nav-link px-2.5 lg:px-3 py-2 text-white/95 font-medium flex items-center rounded-lg">
                            <span class="relative">Contact</span>
                            <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute left-0 mt-2 w-56 dropdown-content z-50" style="margin-top: -5px; padding-top: 15px;">
                            <div class="p-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                <!-- Subtle top accent -->
                                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary/30 via-secondary/30 to-primary/30"></div>
                                
                                <a href="<?php echo URLROOT; ?>/contact" 
                                   class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200
                                          hover:bg-gray-50 group/item">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/5
                                              transition-transform duration-200 group-hover/item:scale-110">
                                        <svg class="w-5 h-5 text-primary transition-all duration-200" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                   class="flex items-center px-3 py-2.5 mt-1 rounded-lg transition-all duration-200
                                          hover:bg-gray-50 group/item">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-secondary/5
                                              transition-transform duration-200 group-hover/item:scale-110">
                                        <svg class="w-5 h-5 text-secondary transition-all duration-200" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Auth Buttons - Elegant & Modern -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 bg-white/10 px-3 py-2 rounded-lg
                                         border border-white/20 backdrop-blur-sm
                                         transition-all duration-300
                                         hover:bg-white/15 hover:border-secondary/30 hover:shadow-lg 
                                         hover:shadow-secondary/5 group-hover:scale-[1.02] group-hover:pr-4">
                                <div class="w-7 h-7 bg-gradient-to-br from-secondary to-primary/80
                                         rounded-lg flex items-center justify-center
                                         ring-1 ring-white/30 transition-all duration-300
                                         group-hover:ring-white/50 group-hover:scale-110 overflow-hidden">
                                    <?php
                                    if (isset($_SESSION['username']) && $_SESSION['username'] === 'Naoufal') {
                                        // Special profile photo for Naoufal
                                        $profilePhotoUrl = "https://media.licdn.com/dms/image/v2/D4E03AQFQkWCitMT1ug/profile-displayphoto-shrink_400_400/B4EZYuubOTHMAg-/0/1744540644719?e=1750291200&v=beta&t=Qs38y2l_-SWd_N2CcavekytGxrU06ixhojbHdDktfxM";
                                        ?>
                                        <img src="<?php echo $profilePhotoUrl; ?>" 
                                             alt="Profile" class="w-full h-full object-cover">
                                    <?php
                                    } else {
                                        // Normal profile photo handling for other users
                                        $profilePhoto = getProfilePhotoUrl($_SESSION['profile_photo'] ?? '', $_SESSION['username']);
                                        if ($profilePhoto['type'] === 'img'): 
                                        ?>
                                            <img src="<?php echo $profilePhoto['value']; ?>" 
                                                 alt="Profile" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-white font-bold text-sm">
                                                <?php echo $profilePhoto['value']; ?>
                                            </span>
                                        <?php endif;
                                    }
                                    ?>
                                </div>
                                <span class="font-medium text-white text-sm"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4 text-white transition-transform duration-300 group-hover:rotate-180" 
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
                                        <a href="<?php echo URLROOT; ?>/admin" 
                                           class="flex items-center px-3 py-2 rounded-lg
                                                 transition-all duration-200 hover:bg-gray-50 group/item">
                                            <div class="w-9 h-9 bg-primary/5 rounded-lg flex items-center justify-center
                                                      transition-transform duration-200 group-hover/item:scale-110">
                                                <svg class="w-5 h-5 text-primary transition-colors duration-200" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Dashboard</p>
                                                <p class="text-xs text-gray-500">Beheer je website</p>
                                            </div>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo URLROOT; ?>/blogs/manage" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                              transition-all duration-200 hover:bg-gray-50 group/item">
                                        <div class="w-9 h-9 bg-primary/5 rounded-lg flex items-center justify-center
                                                  transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-primary transition-colors duration-200" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Mijn Blogs</p>
                                            <p class="text-xs text-gray-500">Beheer je blogs</p>
                                        </div>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/profile" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                              transition-all duration-200 hover:bg-gray-50 group/item">
                                        <div class="w-9 h-9 bg-secondary/5 rounded-lg flex items-center justify-center
                                                  transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-secondary transition-colors duration-200" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Profiel</p>
                                            <p class="text-xs text-gray-500">Beheer je account</p>
                                        </div>
                                    </a>
                                    <div class="mx-3 my-1.5 border-t border-gray-100"></div>

                                    <a href="<?php echo URLROOT; ?>/logout" 
                                       class="flex items-center px-3 py-2 rounded-lg
                                             transition-all duration-200 hover:bg-red-50 group/item">
                                        <div class="w-9 h-9 bg-red-500/5 rounded-lg flex items-center justify-center
                                                  transition-transform duration-200 group-hover/item:scale-110">
                                            <svg class="w-5 h-5 text-red-500 transition-colors duration-200" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-red-600">Uitloggen</p>
                                            <p class="text-xs text-red-400">Tot ziens!</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="magic-link text-white/90 hover:text-white transition-all duration-300 font-medium 
                                 py-2 px-3.5 rounded-lg border border-white/10 hover:border-white/30 hover:bg-white/5">
                            <span>Inloggen</span>
                        </a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="signup-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 
                                 relative overflow-hidden group hover:-translate-y-1"
                           style="display: none;">
                            <span class="relative z-10">Aanmelden</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button - Elegant & Modern -->
                <button class="md:hidden relative z-50 p-1.5 text-white hover:bg-white/15 rounded-lg border border-white/20 
                            transition-all duration-300 hover:border-white/30 group" 
                        id="mobile-menu-button">
                    <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu - Enhanced -->
    <div class="md:hidden hidden fixed inset-0 z-40 glass-dark" id="mobile-menu-overlay">
        <!-- Backdrop with blur effect -->
        <div class="absolute inset-0 bg-primary/90 backdrop-blur-md"
             onclick="closeMobileMenu()"></div>
        
        <!-- Menu Content -->
        <div class="relative h-full w-full max-w-xs ml-auto bg-gradient-to-b from-primary to-primary/95 
                    shadow-2xl overflow-y-auto transform transition-all duration-500 ease-in-out">
            <!-- Mobile menu content -->
            <div class="p-5 space-y-5">
                <!-- Close Button -->
                <div class="flex justify-end">
                    <button class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg 
                                transition-all duration-300 group" 
                            onclick="closeMobileMenu()">
                        <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:rotate-90" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation Links - Enhanced and Modernized -->
                <nav class="space-y-3">
                    <a href="<?php echo URLROOT; ?>/" 
                       class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                              hover:bg-white/5 hover:pl-3 group">
                        <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-white/15 group-hover:scale-110">
                            <img src="<?php echo URLROOT; ?>/images/favicon-512x512.png" 
                                 alt="PolitiekPraat Logo" 
                                 class="w-7 h-7 object-contain">
                        </div>
                        <span class="font-medium">Home</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                              hover:bg-white/5 hover:pl-3 group">
                        <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-white/15 group-hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="font-medium">Blogs</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/nieuws" 
                       class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                              hover:bg-white/5 hover:pl-3 group">
                        <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-white/15 group-hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Nieuws</span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/stemwijzer" 
                       class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                              hover:bg-white/5 hover:pl-3 group">
                        <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-white/15 group-hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="font-medium flex items-center">
                            Stemwijzer
                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium 
                                        bg-secondary/20 border border-secondary/40 text-white rounded-full
                                        shadow-[0_0_8px_rgba(196,30,58,0.3)]">
                                Nieuw
                            </span>
                        </span>
                    </a>

                    <a href="<?php echo URLROOT; ?>/partijen" 
                       class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                              hover:bg-white/5 hover:pl-3 group">
                        <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                    group-hover:bg-white/15 group-hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <span class="font-medium">Partijen</span>
                    </a>

                    <!-- Forum Section - Improved -->
                    <div class="space-y-2" style="display: none;">
                        <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                     p-2 rounded-lg transition-all duration-300 hover:bg-white/5 group" 
                                onclick="toggleMobileSubmenu('forum-submenu')">
                            <div class="flex items-center">
                                <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-white/15 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Forum</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-300" 
                                 id="forum-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="hidden pl-10 space-y-2 border-l border-white/15" id="forum-submenu">
                            <a href="<?php echo URLROOT; ?>/forum" 
                               class="flex items-center text-white/80 hover:text-white p-2 rounded-lg transition-all duration-300 
                                      hover:bg-white/5 hover:pl-3">
                                <span class="font-medium">Alle discussies</span>
                            </a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo URLROOT; ?>/forum/create" 
                               class="flex items-center text-white/80 hover:text-white p-2 rounded-lg transition-all duration-300 
                                      hover:bg-white/5 hover:pl-3">
                                <span class="font-medium">Start discussie</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contact Section - Improved -->
                    <div class="space-y-2">
                        <button class="w-full flex items-center justify-between text-white/90 hover:text-white 
                                     p-2 rounded-lg transition-all duration-300 hover:bg-white/5 group" 
                                onclick="toggleMobileSubmenu('contact-submenu')">
                            <div class="flex items-center">
                                <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-white/15 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Contact</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-300" 
                                 id="contact-submenu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="hidden pl-10 space-y-2 border-l border-white/15" id="contact-submenu">
                            <a href="<?php echo URLROOT; ?>/contact" 
                               class="flex items-center text-white/80 hover:text-white p-2 rounded-lg transition-all duration-300 
                                      hover:bg-white/5 hover:pl-3">
                                <span class="font-medium">Contact opnemen</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/over-mij" 
                               class="flex items-center text-white/80 hover:text-white p-2 rounded-lg transition-all duration-300 
                                     hover:bg-white/5 hover:pl-3">
                                <span class="font-medium">Over ons</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Mobile Auth Section - Improved -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="pt-5 mt-5 border-t border-white/15">
                        <div class="flex items-center space-x-3 mb-4 bg-white/5 p-2.5 rounded-lg">
                            <div class="w-10 h-10 bg-gradient-to-br from-secondary to-primary/80 rounded-lg 
                                      flex items-center justify-center shadow-lg">
                                <span class="text-white text-lg font-bold">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                <p class="text-xs text-white/70">Actief lid</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php if($_SESSION['is_admin']): ?>
                                <a href="<?php echo URLROOT; ?>/admin" 
                                   class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                                          hover:bg-white/5 hover:pl-3 group">
                                    <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                                group-hover:bg-white/15 group-hover:scale-110">
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
                            <a href="<?php echo URLROOT; ?>/blogs/manage" 
                               class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                                      hover:bg-white/5 hover:pl-3 group">
                                <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-white/15 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Mijn Blogs</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/profile" 
                               class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                                      hover:bg-white/5 hover:pl-3 group">
                                <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-white/15 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Profiel</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/logout" 
                               class="flex items-center text-red-300 hover:text-red-200 p-2 rounded-lg transition-all duration-300 
                                     hover:bg-red-500/10 hover:pl-3 group">
                                <div class="mr-3 p-1.5 bg-red-500/10 rounded-lg transition-all duration-300 
                                            group-hover:bg-red-500/20 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Uitloggen</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="pt-5 mt-5 border-t border-white/15 space-y-4">
                        <a href="<?php echo URLROOT; ?>/login" 
                           class="flex items-center text-white/90 hover:text-white p-2 rounded-lg transition-all duration-300 
                                  hover:bg-white/5 hover:pl-3 group">
                            <div class="mr-3 p-1.5 bg-white/10 rounded-lg transition-all duration-300 
                                        group-hover:bg-white/15 group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="font-medium">Inloggen</span>
                        </a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="flex items-center justify-center px-4 py-2.5 bg-white text-primary font-medium 
                                  rounded-lg shadow-lg transition-all duration-300 
                                  hover:bg-secondary hover:text-white transform hover:scale-[1.02]
                                  active:scale-[0.98]"
                           style="display: none;">
                            <span>Aanmelden</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enhanced Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing mobile menu...');
            
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu-overlay');
            const mobileMenuContent = mobileMenu.querySelector('.max-w-xs');

            // Ensure mobile menu is properly initialized
            mobileMenuContent.classList.add('mobile-menu-enter');

            // Handle desktop dropdowns
            const dropdownGroups = document.querySelectorAll('.group');
            dropdownGroups.forEach(group => {
                const button = group.querySelector('button');
                const dropdown = group.querySelector('.dropdown-content');
                
                if (button && dropdown) {
                    let timeoutId;
                    
                    // Show dropdown on hover or focus
                    group.addEventListener('mouseenter', () => {
                        clearTimeout(timeoutId);
                        dropdown.classList.add('dropdown-active');
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateY(0) scale(1)';
                        dropdown.style.pointerEvents = 'auto';
                    });
                    
                    // Hide dropdown when mouse leaves the group
                    group.addEventListener('mouseleave', () => {
                        timeoutId = setTimeout(() => {
                            dropdown.classList.remove('dropdown-active');
                            dropdown.style.opacity = '0';
                            dropdown.style.visibility = 'hidden';
                            dropdown.style.transform = 'translateY(-10px) scale(0.97)';
                            dropdown.style.pointerEvents = 'none';
                        }, 100); // Small delay to prevent flickering
                    });
                    
                    // Ensure dropdown stays visible when focused
                    button.addEventListener('focus', () => {
                        clearTimeout(timeoutId);
                        dropdown.classList.add('dropdown-active');
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateY(0) scale(1)';
                        dropdown.style.pointerEvents = 'auto';
                    });
                    
                    // Handle keyboard navigation
                    button.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            dropdown.classList.remove('dropdown-active');
                            dropdown.style.opacity = '0';
                            dropdown.style.visibility = 'hidden';
                            dropdown.style.transform = 'translateY(-10px) scale(0.97)';
                            dropdown.style.pointerEvents = 'none';
                            button.blur();
                        } else if (e.key === 'ArrowDown' || e.key === 'Enter') {
                            e.preventDefault();
                            dropdown.classList.add('dropdown-active');
                            dropdown.style.opacity = '1';
                            dropdown.style.visibility = 'visible';
                            dropdown.style.transform = 'translateY(0) scale(1)';
                            dropdown.style.pointerEvents = 'auto';
                            
                            // Focus the first link in the dropdown
                            const firstLink = dropdown.querySelector('a');
                            if (firstLink) {
                                firstLink.focus();
                            }
                        }
                    });
                    
                    // Handle keyboard navigation within dropdown
                    const links = dropdown.querySelectorAll('a');
                    links.forEach((link, index) => {
                        link.addEventListener('keydown', (e) => {
                            if (e.key === 'Escape') {
                                dropdown.classList.remove('dropdown-active');
                                dropdown.style.opacity = '0';
                                dropdown.style.visibility = 'hidden';
                                dropdown.style.transform = 'translateY(-10px) scale(0.97)';
                                dropdown.style.pointerEvents = 'none';
                                button.focus();
                            } else if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                const nextLink = links[index + 1] || links[0];
                                if (nextLink) {
                                    nextLink.focus();
                                }
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                const prevLink = links[index - 1] || links[links.length - 1];
                                if (prevLink) {
                                    prevLink.focus();
                                }
                            }
                        });
                    });
                }
            });

            function toggleMobileMenu() {
                console.log('Toggle mobile menu');
                if (mobileMenu.classList.contains('hidden')) {
                    // Open menu with animations
                    mobileMenu.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    
                    // Animate menu sliding in
                    mobileMenuContent.classList.remove('mobile-menu-enter');
                    mobileMenuContent.classList.add('mobile-menu-enter-active');
                } else {
                    // Close menu with animations
                    mobileMenuContent.classList.remove('mobile-menu-enter-active');
                    mobileMenuContent.classList.add('mobile-menu-exit');
                    
                    requestAnimationFrame(() => {
                        mobileMenuContent.classList.remove('mobile-menu-exit');
                        mobileMenuContent.classList.add('mobile-menu-exit-active');
                    
                        // Hide completely after animation
                        setTimeout(() => {
                            mobileMenu.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                            mobileMenuContent.classList.remove('mobile-menu-exit-active');
                            mobileMenuContent.classList.add('mobile-menu-enter');
                        }, 300);
                    });
                }
            }

            function closeMobileMenu() {
                console.log('Close mobile menu');
                // Close menu with animations
                mobileMenuContent.classList.remove('mobile-menu-enter-active');
                mobileMenuContent.classList.add('mobile-menu-exit');
                
                requestAnimationFrame(() => {
                    mobileMenuContent.classList.remove('mobile-menu-exit');
                    mobileMenuContent.classList.add('mobile-menu-exit-active');
                
                    // Hide completely after animation
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        mobileMenuContent.classList.remove('mobile-menu-exit-active');
                        mobileMenuContent.classList.add('mobile-menu-enter');
                    }, 300);
                });
            }

            // Making the closeMobileMenu function globally available
            window.closeMobileMenu = closeMobileMenu;

            mobileMenuButton.addEventListener('click', toggleMobileMenu);

            // Close mobile menu when clicking outside
            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu) {
                    closeMobileMenu();
                }
            });

            // Mobile submenu toggle functionality with enhanced animations
            window.toggleMobileSubmenu = function(submenuId) {
                console.log('Toggle submenu:', submenuId);
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