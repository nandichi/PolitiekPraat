<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Google tag (gtag.js) - Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XLQMYP3CDD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XLQMYP3CDD');
    </script>
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5550921434025979"
         crossorigin="anonymous"></script>
    
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
    <meta property="og:type" content="<?php echo isset($data['og_type']) ? $data['og_type'] : (isset($data['title']) ? 'article' : 'website'); ?>">
    <meta property="og:url" content="<?php echo isset($data['og_url']) ? htmlspecialchars($data['og_url']) : htmlspecialchars($canonicalUrl); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($metaImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta property="og:site_name" content="PolitiekPraat">
    <meta property="og:locale" content="nl_NL">
    
    <?php if (isset($data['article_author'])): ?>
    <!-- Article-specific meta tags -->
    <meta property="article:author" content="<?php echo htmlspecialchars($data['article_author']); ?>">
    <?php endif; ?>
    
    <?php if (isset($data['article_published_time'])): ?>
    <meta property="article:published_time" content="<?php echo htmlspecialchars($data['article_published_time']); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@PolitiekPraat">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($metaImage); ?>">
    <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($metaTitle); ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">

    <!-- Sitemap Link -->
    <link rel="sitemap" type="application/xml" href="<?php echo URLROOT; ?>/sitemap.xml">
    
    <!-- Provide translation alternates if available in the future -->
    <link rel="alternate" hreflang="nl-nl" href="<?php echo $canonicalUrl; ?>">
    
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="<?php echo URLROOT; ?>/public/css/output.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        'primary-dark': '#0f2a44',
                        'primary-light': '#2d4a6b',
                        secondary: '#c41e3a',
                        'secondary-dark': '#9e1829',
                        'secondary-light': '#d63856',
                        accent: '#F59E0B',
                        tertiary: '#F59E0B',
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
                            '0%': { transform: 'translateX(-150%)' },
                            '100%': { transform: 'translateX(150%)' },
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
        "url": "<?php echo $canonicalUrl; ?>",
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

    <?php if ($currentPage === 'stemwijzer'): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?php echo URLROOT; ?>/"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Gemeentelijke Stemwijzer Ede 2026",
                "item": "<?php echo URLROOT; ?>/stemwijzer"
            }
        ]
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Is dit een stemadvies?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Nee. Deze stemwijzer geeft hulp bij vergelijking van partijstandpunten voor de gemeenteraadsverkiezingen in Ede (2026)."
                }
            },
            {
                "@type": "Question",
                "name": "Voor welke verkiezing geldt deze stemwijzer?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "De huidige versie is expliciet bedoeld voor de gemeenteraadsverkiezingen 2026 in Ede."
                }
            },
            {
                "@type": "Question",
                "name": "Hoeveel stellingen en weging gebruikt de stemwijzer?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "De stemwijzer gebruikt 25 stellingen. Je kunt weging gebruiken om stellingen zwaarder mee te laten tellen in je vergelijking."
                }
            }
        ]
    }
    </script>
    <?php endif; ?>
    
    <?php if(isset($data['is_blog']) && $data['is_blog']): ?>
    <!-- Schema.org Structured Data for Blog Article -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo $canonicalUrl; ?>"
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
            "@id": "<?php echo $canonicalUrl; ?>"
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
    
    <!-- Accessibility & Privacy CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/accessibility.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/coffee-button.css">
    
    <!-- Accessibility Scripts -->
    <script src="<?php echo URLROOT; ?>/public/js/accessibility.js" defer></script>

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
        
        /* Navigation elements - premium enhanced */
        .nav-link {
            position: relative;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .nav-link span {
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }

        /* Premium hover background with subtle gradient */
        .nav-link::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 12px;
            background: linear-gradient(135deg, 
                rgba(26, 54, 93, 0.08) 0%, 
                rgba(196, 30, 58, 0.05) 50%,
                rgba(26, 54, 93, 0.08) 100%);
            opacity: 0;
            z-index: 0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            transform: scale(0.8);
        }

        /* Animated underline effect */
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #c41e3a, #1a365d);
            transform: translateX(-50%);
            transition: width 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            border-radius: 2px;
        }

        /* Subtle glow effect */
        .nav-link:hover::before {
            opacity: 1;
            transform: scale(1);
            box-shadow: 
                0 4px 20px rgba(26, 54, 93, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .nav-link:hover span {
            transform: translateY(-1px);
            color: #1a365d;
        }

        /* Active state styling */
        .nav-link.active::before {
            opacity: 1;
            transform: scale(1);
            background: linear-gradient(135deg, 
                rgba(26, 54, 93, 0.12) 0%, 
                rgba(196, 30, 58, 0.08) 50%,
                rgba(26, 54, 93, 0.12) 100%);
        }

        .nav-link.active::after {
            width: 80%;
        }

        .nav-link.active span {
            color: #1a365d;
            font-weight: 600;
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

        /* Scrollbar hide utility */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Enhanced dropdown styling with glassmorphism */
        .dropdown-content {
            transform-origin: top center;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.98);
            pointer-events: none;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            /* Verbeterde positioning - onder de button */
            top: calc(100% + 5px);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.98) 0%, 
                rgba(255, 255, 255, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: absolute;
            overflow: hidden;
            z-index: 9999;
        }
        
        .dropdown-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #1a365d, #c41e3a, #1a365d);
            background-size: 200% 100%;
            animation: gradient-flow 3s ease infinite;
        }
        
        .group:hover .dropdown-content,
        .dropdown-content:hover,
        .dropdown-content.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        
        /* Verbeterde dropdown hover states */
        .dropdown-trigger {
            position: relative;
        }
        
        .dropdown-trigger:hover .dropdown-content,
        .dropdown-trigger:focus-within .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        
        /* Anti-flicker hover gebied - boven de dropdown */
        .dropdown-content::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: transparent;
            z-index: 1;
        }
        
        /* Zorg ervoor dat content boven de transparante hover gebied blijft */
        .dropdown-content > * {
            position: relative;
            z-index: 2;
        }
        
        /* Verbeterde anti-flicker en smoother transitions */
        .dropdown-trigger {
            transition: all 0.2s ease;
        }
        
        .dropdown-trigger:hover {
            z-index: 10000;
        }
        
        /* Hover delay voor betere UX */
        .dropdown-content {
            transition-delay: 0ms;
        }
        
        .dropdown-trigger:not(:hover) .dropdown-content {
            transition-delay: 150ms;
        }
        
        /* Extra stabiliteit voor dropdown */
        .dropdown-content:hover {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) scale(1) !important;
            pointer-events: auto !important;
        }
        
        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
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
        
        /* Accessibility - Respect prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                will-change: auto !important;
            }
            
            .animate-pulse-subtle,
            .animate-ping,
            [class*="animate-"] {
                animation: none !important;
            }
            
            /* Disable transforms on hover for reduced motion */
            .group:hover *,
            .dropdown-trigger:hover *,
            .nav-link:hover {
                transform: none !important;
            }
        }

    </style>
</head>
