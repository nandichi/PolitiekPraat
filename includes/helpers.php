<?php
// Prevent multiple inclusion
if (!defined('HELPERS_INCLUDED')) {
    define('HELPERS_INCLUDED', true);

if (!function_exists('getProfilePhotoUrl')) {
    /**
     * Gets the profile photo URL or initial for a user
     * 
     * @param string|null $profilePhoto The profile photo path from database
     * @param string $username The username to get initial from if no photo
     * @return array ['type' => 'img'|'initial', 'value' => 'url'|'letter'] 
     */
    function getProfilePhotoUrl($profilePhoto, $username) {
        if (!empty($profilePhoto)) {
            // Check if the path starts with http:// or https://
            if (preg_match('/^https?:\/\//', $profilePhoto)) {
                return ['type' => 'img', 'value' => $profilePhoto];
            }
            
            // Check if path already has public/
            if (strpos($profilePhoto, 'public/') === 0) {
                $profilePhoto = substr($profilePhoto, 7); // Remove "public/"
            }
            
            // Check if the file exists in the public directory
            if (file_exists(BASE_PATH . '/public/' . $profilePhoto)) {
                return ['type' => 'img', 'value' => URLROOT . '/' . $profilePhoto];
            }
            
            // Try alternate paths
            $altPaths = [
                $profilePhoto,
                'uploads/profile_photos/' . basename($profilePhoto)
            ];
            
            foreach ($altPaths as $path) {
                if (file_exists(BASE_PATH . '/public/' . $path)) {
                    return ['type' => 'img', 'value' => URLROOT . '/' . $path];
                }
            }
        }
        
        // Return initial if no photo found
        $initial = !empty($username) ? strtoupper(substr($username, 0, 1)) : '?';
        return ['type' => 'initial', 'value' => $initial];
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is an admin
     * 
     * @return bool True if user is an admin, false otherwise
     */
    function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
}

if (!function_exists('getBlogImageUrl')) {
    /**
     * Gets the correct URL for a blog image
     * 
     * @param string|null $imagePath The image path from database
     * @return string|null The full URL to the image or null if no image
     */
    function getBlogImageUrl($imagePath) {
        if (empty($imagePath)) {
            return null;
        }
        
        // If path already starts with http:// or https://, return as is
        if (preg_match('/^https?:\/\//', $imagePath)) {
            return $imagePath;
        }
        
        // Remove any leading slash to normalize the path
        $imagePath = ltrim($imagePath, '/');
        
        // Check if the file exists
        if (file_exists(BASE_PATH . '/' . $imagePath)) {
            return URLROOT . '/' . $imagePath;
        }
        
        // If file doesn't exist, still return the expected path for backwards compatibility
        return URLROOT . '/' . $imagePath;
    }
}

if (!function_exists('getBlogVideoUrl')) {
    /**
     * Gets the correct URL for a blog video
     * 
     * @param string|null $videoPath The video path from database
     * @return string|null The full URL to the video or null if no video
     */
    function getBlogVideoUrl($videoPath) {
        if (empty($videoPath)) {
            return null;
        }
        
        // If path already starts with http:// or https://, return as is
        if (preg_match('/^https?:\/\//', $videoPath)) {
            return $videoPath;
        }
        
        // Remove any leading slash to normalize the path
        $videoPath = ltrim($videoPath, '/');
        
        // Check if the file exists
        if (file_exists(BASE_PATH . '/' . $videoPath)) {
            return URLROOT . '/' . $videoPath;
        }
        
        // If file doesn't exist, still return the expected path for backwards compatibility
        return URLROOT . '/' . $videoPath;
    }
}

if (!function_exists('getBlogAudioUrl')) {
    /**
     * Gets the correct URL for a blog audio file
     * 
     * @param string|null $audioPath The audio path from database
     * @return string|null The full URL to the audio or null if no audio
     */
    function getBlogAudioUrl($audioPath) {
        if (empty($audioPath)) {
            return null;
        }
        
        // If path already starts with http:// or https://, return as is
        if (preg_match('/^https?:\/\//', $audioPath)) {
            return $audioPath;
        }
        
        // Remove any leading slash to normalize the path
        $audioPath = ltrim($audioPath, '/');
        
        // Check if the file exists
        if (file_exists(BASE_PATH . '/' . $audioPath)) {
            return URLROOT . '/' . $audioPath;
        }
        
        // If file doesn't exist, still return the expected path for backwards compatibility
        return URLROOT . '/' . $audioPath;
    }
}

if (!function_exists('stripMarkdownForSocialMedia')) {
    /**
     * Strips markdown formatting from text for use in social media meta descriptions
     * 
     * @param string $text The text with markdown formatting
     * @param int $maxLength Maximum length of the cleaned text (default: 160)
     * @return string Clean text without markdown formatting
     */
    function stripMarkdownForSocialMedia($text, $maxLength = 160) {
        if (empty($text)) {
            return '';
        }
        
        // Remove markdown headers (# ## ### etc.)
        $text = preg_replace('/^#{1,6}\s+/m', '', $text);
        
        // Remove bold/italic formatting (**text**, *text*, __text__, _text_)
        $text = preg_replace('/(\*{1,2}|_{1,2})(.*?)\1/', '$2', $text);
        
        // Remove links [text](url) - keep just the text
        $text = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $text);
        
        // Remove images ![alt](url)
        $text = preg_replace('/!\[([^\]]*)\]\([^)]+\)/', '', $text);
        
        // Remove code blocks (```code```)
        $text = preg_replace('/```[^`]*```/', '', $text);
        
        // Remove inline code (`code`)
        $text = preg_replace('/`([^`]+)`/', '$1', $text);
        
        // Remove blockquotes (> text)
        $text = preg_replace('/^>\s+/m', '', $text);
        
        // Remove horizontal rules (--- or ***)
        $text = preg_replace('/^(-{3,}|\*{3,})$/m', '', $text);
        
        // Remove list markers (- * +)
        $text = preg_replace('/^[\s]*[-\*\+]\s+/m', '', $text);
        
        // Remove numbered list markers (1. 2. etc.)
        $text = preg_replace('/^\s*\d+\.\s+/m', '', $text);
        
        // Remove HTML tags if any
        $text = strip_tags($text);
        
        // Clean up extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Truncate to max length and add ellipsis if needed
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength - 3) . '...';
        }
        
        return $text;
    }
}

/**
 * Genereer breadcrumb navigatie gebaseerd op de huidige URL
 */
function generateBreadcrumbs() {
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestPath = parse_url($requestUri, PHP_URL_PATH);
    $pathSegments = explode('/', trim($requestPath, '/'));
    
    // Breadcrumb mapping voor Nederlandse labels
    $breadcrumbMap = [
        'blogs' => ['icon' => 'edit', 'label' => 'Blogs', 'description' => 'Politieke analyses en opinies'],
        'nieuws' => ['icon' => 'newspaper', 'label' => 'Nieuws', 'description' => 'Laatste politieke ontwikkelingen'],
        'partijen' => ['icon' => 'users', 'label' => 'Partijen', 'description' => 'Nederlandse politieke partijen'],
        'themas' => ['icon' => 'tag', 'label' => "Thema's", 'description' => 'Politieke onderwerpen'],
        'stemwijzer' => ['icon' => 'check-square', 'label' => 'Stemwijzer', 'description' => 'Ontdek jouw politieke match'],
        'politiek-kompas' => ['icon' => 'compass', 'label' => 'Politiek Kompas', 'description' => 'Vergelijk partijstandpunten'],
        'amerikaanse-verkiezingen' => ['icon' => 'flag', 'label' => 'Amerikaanse Verkiezingen', 'description' => '235 jaar democratie'],
        'nederlandse-verkiezingen' => ['icon' => 'home', 'label' => 'Nederlandse Verkiezingen', 'description' => '175 jaar democratie'],
        'over-mij' => ['icon' => 'user', 'label' => 'Over Ons', 'description' => 'Missie en visie'],
        'contact' => ['icon' => 'mail', 'label' => 'Contact', 'description' => 'Neem contact op'],
        'profile' => ['icon' => 'user-circle', 'label' => 'Profiel', 'description' => 'Jouw account'],
        'forum' => ['icon' => 'message-circle', 'label' => 'Forum', 'description' => 'Politieke discussies'],
        'resultaten' => ['icon' => 'bar-chart', 'label' => 'Resultaten', 'description' => 'Jouw stemwijzer resultaten'],
        'create' => ['icon' => 'plus', 'label' => 'Nieuw', 'description' => 'Maak nieuwe content'],
        'edit' => ['icon' => 'edit-2', 'label' => 'Bewerken', 'description' => 'Pas content aan'],
        'manage' => ['icon' => 'settings', 'label' => 'Beheren', 'description' => 'Beheer je content']
    ];
    
    $breadcrumbs = [];
    
    // Voeg altijd Home toe
    $breadcrumbs[] = [
        'url' => URLROOT . '/',
        'icon' => 'home',
        'label' => 'Home',
        'description' => 'Terug naar homepage',
        'active' => false
    ];
    
    // Bouw breadcrumbs op basis van URL segmenten
    $currentPath = '';
    foreach ($pathSegments as $index => $segment) {
        if (empty($segment)) continue;
        
        $currentPath .= '/' . $segment;
        $isActive = ($index === count($pathSegments) - 1);
        
        // Zoek mapping voor dit segment
        $segmentInfo = $breadcrumbMap[$segment] ?? [
            'icon' => 'file',
            'label' => ucfirst($segment),
            'description' => ucfirst($segment)
        ];
        
        // Speciale behandeling voor detail pagina's
        if ($index > 0 && is_numeric($segment)) {
            // Dit is waarschijnlijk een ID, skip het
            continue;
        }
        
        // Voor blog titels: probeer de echte titel op te halen als dit een blog detail pagina is
        $fullTitle = $segmentInfo['label'];
        $shortTitle = $segmentInfo['label'];
        
        if ($index > 0 && isset($pathSegments[$index-1]) && $pathSegments[$index-1] === 'blogs' && !is_numeric($segment)) {
            // Dit is een blog slug, probeer de titel op te halen
            $fullTitle = str_replace('-', ' ', $segment);
            $fullTitle = ucwords($fullTitle);
            
            // Responsieve titel inkorting
            $maxLength = 40; // Desktop
            if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/', $_SERVER['HTTP_USER_AGENT'])) {
                $maxLength = 25; // Mobile
            }
            
            if (strlen($fullTitle) > $maxLength) {
                $shortTitle = substr($fullTitle, 0, $maxLength - 3) . '...';
            } else {
                $shortTitle = $fullTitle;
            }
        }
        
        $breadcrumbs[] = [
            'url' => URLROOT . $currentPath,
            'icon' => $segmentInfo['icon'],
            'label' => $shortTitle,
            'fullLabel' => $fullTitle,
            'description' => $segmentInfo['description'],
            'active' => $isActive
        ];
    }
    
    return $breadcrumbs;
}

/**
 * Render breadcrumb HTML
 */
function renderBreadcrumbs($breadcrumbs = null) {
    if ($breadcrumbs === null) {
        $breadcrumbs = generateBreadcrumbs();
    }
    
    if (count($breadcrumbs) <= 1) {
        return ''; // Geen breadcrumbs tonen voor homepage
    }
    
    $html = '<nav aria-label="Breadcrumb" class="relative overflow-hidden sticky top-16 md:top-20 z-40 shadow-lg">';
    
    // Hoofdgradient achtergrond met primary en secondary
    $html .= '<div class="absolute inset-0 bg-gradient-to-r from-primary via-primary to-secondary"></div>';
    
    // Overlay gradient voor diepte
    $html .= '<div class="absolute inset-0 bg-gradient-to-br from-primary-dark/20 via-transparent to-secondary/30"></div>';
    
    // Decoratieve patterns
    $html .= '<div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px), radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 30px 30px, 40px 40px;"></div>';
    

    
    // Glow effects aan de randen
    $html .= '<div class="absolute top-0 left-0 w-32 h-full bg-gradient-to-r from-white/20 to-transparent"></div>';
    $html .= '<div class="absolute top-0 right-0 w-32 h-full bg-gradient-to-l from-white/15 to-transparent"></div>';
    
    // Border accenten
    $html .= '<div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-white/40 via-white/60 to-white/40"></div>';
    $html .= '<div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-white/20 via-white/40 to-white/20"></div>';
    
    $html .= '<div class="relative">';
    $html .= '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">';
    $html .= '<div class="flex items-center py-4 min-h-[4rem] space-x-1 relative overflow-x-auto scrollbar-hide">';
    
    foreach ($breadcrumbs as $index => $crumb) {
        $isLast = ($index === count($breadcrumbs) - 1);
        
        // Icon met aangepaste kleuren voor donkere achtergrond
        $iconClass = 'w-4 h-4 ' . ($crumb['active'] ? 'text-white' : 'text-white/80');
        $icon = getBreadcrumbIcon($crumb['icon'], $iconClass);
        
        if (!$isLast && !$crumb['active']) {
            // Clickable breadcrumb met glassmorphism effect
            $html .= '<a href="' . htmlspecialchars($crumb['url']) . '" ';
            $html .= 'class="flex items-center space-x-1.5 text-white/90 hover:text-white ';
            $html .= 'transition-all duration-300 group px-3 py-2 rounded-xl ';
            $html .= 'hover:bg-white/20 hover:backdrop-blur-sm hover:shadow-lg ';
            $html .= 'border border-white/10 hover:border-white/30 max-w-xs md:max-w-sm flex-shrink-0" ';
            $html .= 'title="' . htmlspecialchars($crumb['fullLabel'] ?? $crumb['label']) . '">';
            $html .= $icon;
            $html .= '<span class="text-sm font-medium group-hover:font-semibold drop-shadow-sm truncate">' . htmlspecialchars($crumb['label']) . '</span>';
            $html .= '</a>';
        } else {
            // Active/current breadcrumb met prominente styling
            $html .= '<div class="flex items-center space-x-1.5 text-white ';
            $html .= 'bg-white/25 backdrop-blur-sm px-3 py-2 rounded-xl ';
            $html .= 'border border-white/30 shadow-lg ring-1 ring-white/20 max-w-sm flex-shrink-0" ';
            $html .= 'title="' . htmlspecialchars($crumb['fullLabel'] ?? $crumb['label']) . '">';
            $html .= $icon;
            $html .= '<span class="text-sm font-bold drop-shadow-sm truncate">' . htmlspecialchars($crumb['label']) . '</span>';
            $html .= '</div>';
        }
        
        // Add separator (except for last item)
        if (!$isLast) {
            $html .= '<div class="flex items-center mx-2">';
            $html .= '<div class="w-1 h-1 bg-white/60 rounded-full"></div>';
            $html .= '<div class="w-1 h-1 bg-white/40 rounded-full ml-1"></div>';
            $html .= '<div class="w-1 h-1 bg-white/60 rounded-full ml-1"></div>';
            $html .= '</div>';
        }
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</nav>';
    
    return $html;
}

/**
 * Get SVG icon for breadcrumbs
 */
function getBreadcrumbIcon($iconName, $class = 'w-4 h-4') {
    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        'edit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>',
        'newspaper' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m0 0v10a2 2 0 01-2 2h-5m-4 0V5a2 2 0 014 0v2M7 7h3m-3 4h3m-3 4h3"/>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
        'tag' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
        'check-square' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
        'compass' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'flag' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 2H21l-3 6 3 6h-8.5l-1-2H5a2 2 0 00-2 2zm9-13.5V9"/>',
        'user' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
        'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        'user-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'message-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',
        'bar-chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',
        'edit-2' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'file' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
    ];
    
    $path = $icons[$iconName] ?? $icons['file'];
    
    return '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $path . '</svg>';
}

/**
 * Detecteer de huidige pagina voor actieve nav states
 */
function getCurrentPageContext() {
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestPath = parse_url($requestUri, PHP_URL_PATH);
    $pathSegments = explode('/', trim($requestPath, '/'));
    
    $context = [
        'section' => '',
        'subsection' => '',
        'action' => '',
        'id' => null
    ];
    
    if (empty($pathSegments[0])) {
        $context['section'] = 'home';
        return $context;
    }
    
    $context['section'] = $pathSegments[0];
    
    if (isset($pathSegments[1])) {
        if (is_numeric($pathSegments[1])) {
            $context['id'] = $pathSegments[1];
        } else {
            $context['subsection'] = $pathSegments[1];
        }
    }
    
    if (isset($pathSegments[2])) {
        $context['action'] = $pathSegments[2];
    }
    
    return $context;
}

/**
 * Quick Navigation Component voor snelle toegang tot hoofdfuncties
 */
function renderQuickNavigation() {
    $currentContext = getCurrentPageContext();
    
    // Quick action items gebaseerd op huidige context
    $quickActions = [
        [
            'icon' => 'check-square',
            'title' => 'Stemwijzer',
            'description' => 'Ontdek jouw match',
            'url' => URLROOT . '/stemwijzer',
            'color' => 'secondary',
            'highlight' => true
        ],
        [
            'icon' => 'compass',
            'title' => 'Politiek Kompas',
            'description' => 'Vergelijk partijen',
            'url' => URLROOT . '/politiek-kompas',
            'color' => 'accent',
            'highlight' => false
        ],
        [
            'icon' => 'users',
            'title' => 'Partijen',
            'description' => 'Alle Nederlandse partijen',
            'url' => URLROOT . '/partijen',
            'color' => 'primary',
            'highlight' => false
        ],
        [
            'icon' => 'newspaper',
            'title' => 'Nieuws',
            'description' => 'Laatste ontwikkelingen',
            'url' => URLROOT . '/nieuws',
            'color' => 'primary',
            'highlight' => false
        ]
    ];
    
    // Voeg context-specifieke acties toe
    if ($currentContext['section'] === 'blogs' && !$currentContext['subsection'] && isset($_SESSION['user_id'])) {
        array_unshift($quickActions, [
            'icon' => 'plus',
            'title' => 'Schrijf Blog',
            'description' => 'Deel jouw mening',
            'url' => URLROOT . '/blogs/create',
            'color' => 'green',
            'highlight' => true
        ]);
    }
    
    if (isset($_SESSION['user_id']) && $currentContext['section'] === 'profile') {
        array_unshift($quickActions, [
            'icon' => 'edit-2',
            'title' => 'Bewerk Profiel',
            'description' => 'Update je gegevens',
            'url' => URLROOT . '/profile/edit',
            'color' => 'blue',
            'highlight' => true
        ]);
    }
    
    $html = '<div class="fixed bottom-6 right-6 z-50 hidden lg:block" x-data="{ showQuickNav: false }">';
    
    // Trigger button
    $html .= '<button @click="showQuickNav = !showQuickNav" ';
    $html .= 'class="w-14 h-14 bg-gradient-to-r from-primary to-secondary rounded-full shadow-xl ';
    $html .= 'flex items-center justify-center text-white hover:shadow-2xl transition-all duration-300 ';
    $html .= 'hover:scale-110 group relative overflow-hidden" ';
    $html .= 'title="Snelle Navigatie">';
    
    // Button icon and effect
    $html .= '<div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 ';
    $html .= 'group-hover:opacity-100 transition-opacity duration-300"></div>';
    $html .= '<svg class="w-6 h-6 relative z-10 transition-transform duration-300" :class="showQuickNav ? \'rotate-45\' : \'\'" ';
    $html .= 'fill="none" stroke="currentColor" viewBox="0 0 24 24">';
    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
    $html .= '</svg>';
    $html .= '</button>';
    
    // Quick actions panel
    $html .= '<div x-show="showQuickNav" x-transition:enter="transition ease-out duration-300 transform" ';
    $html .= 'x-transition:enter-start="opacity-0 scale-95 translate-y-4" ';
    $html .= 'x-transition:enter-end="opacity-100 scale-100 translate-y-0" ';
    $html .= 'x-transition:leave="transition ease-in duration-200 transform" ';
    $html .= 'x-transition:leave-start="opacity-100 scale-100 translate-y-0" ';
    $html .= 'x-transition:leave-end="opacity-0 scale-95 translate-y-4" ';
    $html .= 'class="absolute bottom-16 right-0 bg-white rounded-2xl shadow-2xl border border-gray-100 ';
    $html .= 'p-4 w-72 backdrop-blur-lg" style="display: none;">';
    
    // Header
    $html .= '<div class="mb-4">';
    $html .= '<h3 class="text-lg font-bold text-gray-800 mb-1">Snelle Navigatie</h3>';
    $html .= '<p class="text-sm text-gray-500">Ga direct naar belangrijke functies</p>';
    $html .= '</div>';
    
    // Actions grid
    $html .= '<div class="space-y-2">';
    
    foreach ($quickActions as $action) {
        $colorClasses = [
            'primary' => 'bg-primary/10 text-primary hover:bg-primary/20 border-primary/20',
            'secondary' => 'bg-secondary/10 text-secondary hover:bg-secondary/20 border-secondary/20',
            'accent' => 'bg-orange-500/10 text-orange-600 hover:bg-orange-500/20 border-orange-500/20',
            'green' => 'bg-green-500/10 text-green-600 hover:bg-green-500/20 border-green-500/20',
            'blue' => 'bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 border-blue-500/20'
        ];
        
        $colorClass = $colorClasses[$action['color']] ?? $colorClasses['primary'];
        $highlightClass = $action['highlight'] ? 'ring-2 ring-' . $action['color'] . '/30' : '';
        
        $html .= '<a href="' . htmlspecialchars($action['url']) . '" ';
        $html .= 'class="flex items-center p-3 rounded-xl border transition-all duration-300 ';
        $html .= 'hover:shadow-md hover:scale-[1.02] group ' . $colorClass . ' ' . $highlightClass . '">';
        
        // Icon
        $html .= '<div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 ';
        $html .= 'transition-transform duration-300 group-hover:scale-110">';
        $html .= getBreadcrumbIcon($action['icon'], 'w-5 h-5');
        $html .= '</div>';
        
        // Content
        $html .= '<div class="flex-1">';
        $html .= '<div class="text-sm font-semibold">' . htmlspecialchars($action['title']) . '</div>';
        $html .= '<div class="text-xs opacity-70">' . htmlspecialchars($action['description']) . '</div>';
        $html .= '</div>';
        
        // Arrow
        $html .= '<svg class="w-4 h-4 opacity-50 transition-transform duration-300 group-hover:translate-x-1" ';
        $html .= 'fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
        $html .= '</svg>';
        
        $html .= '</a>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Page Context Indicator voor duidelijkheid over huidige locatie
 */
function renderPageContextIndicator() {
    $currentContext = getCurrentPageContext();
    
    // Skip voor homepage
    if ($currentContext['section'] === 'home' || empty($currentContext['section'])) {
        return '';
    }
    
    $contextMap = [
        'blogs' => ['label' => 'Blogs', 'color' => 'primary', 'description' => 'Politieke analyses en opinies'],
        'nieuws' => ['label' => 'Nieuws', 'color' => 'secondary', 'description' => 'Laatste politieke ontwikkelingen'],
        'partijen' => ['label' => 'Partijen', 'color' => 'primary', 'description' => 'Nederlandse politieke partijen'],
        'themas' => ['label' => 'Thema\'s', 'color' => 'accent', 'description' => 'Politieke onderwerpen'],
        'stemwijzer' => ['label' => 'Stemwijzer', 'color' => 'secondary', 'description' => 'Ontdek jouw politieke match'],
        'politiek-kompas' => ['label' => 'Politiek Kompas', 'color' => 'accent', 'description' => 'Vergelijk partijstandpunten'],
        'amerikaanse-verkiezingen' => ['label' => 'Amerikaanse Verkiezingen', 'color' => 'primary', 'description' => '235 jaar democratie'],
        'nederlandse-verkiezingen' => ['label' => 'Nederlandse Verkiezingen', 'color' => 'orange', 'description' => '175 jaar democratie'],
        'over-mij' => ['label' => 'Over Ons', 'color' => 'primary', 'description' => 'Missie en visie'],
        'contact' => ['label' => 'Contact', 'color' => 'secondary', 'description' => 'Neem contact op'],
        'profile' => ['label' => 'Profiel', 'color' => 'blue', 'description' => 'Jouw account'],
        'forum' => ['label' => 'Forum', 'color' => 'green', 'description' => 'Politieke discussies']
    ];
    
    $sectionInfo = $contextMap[$currentContext['section']] ?? null;
    
    if (!$sectionInfo) {
        return '';
    }
    
    // Only show on non-mobile
    $html = '<div class="hidden md:block bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">';
    $html .= '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">';
    $html .= '<div class="flex items-center justify-between">';
    
    // Left side - Context info
    $html .= '<div class="flex items-center space-x-3">';
    
    // Color indicator
    $colorClass = [
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary', 
        'accent' => 'bg-orange-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'orange' => 'bg-orange-500'
    ][$sectionInfo['color']] ?? 'bg-primary';
    
    $html .= '<div class="w-3 h-3 rounded-full ' . $colorClass . ' animate-pulse"></div>';
    $html .= '<div>';
    $html .= '<h2 class="text-sm font-semibold text-gray-800">' . htmlspecialchars($sectionInfo['label']) . '</h2>';
    $html .= '<p class="text-xs text-gray-500">' . htmlspecialchars($sectionInfo['description']) . '</p>';
    $html .= '</div>';
    $html .= '</div>';
    
    // Right side - Quick actions based on context
    $html .= '<div class="flex items-center space-x-2">';
    
    if ($currentContext['section'] === 'blogs' && isset($_SESSION['user_id'])) {
        $html .= '<a href="' . URLROOT . '/blogs/create" ';
        $html .= 'class="inline-flex items-center px-3 py-1.5 bg-primary/10 text-primary rounded-lg ';
        $html .= 'hover:bg-primary/20 transition-colors duration-200 text-sm font-medium">';
        $html .= '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
        $html .= '</svg>';
        $html .= 'Nieuwe Blog';
        $html .= '</a>';
    }
    
    if ($currentContext['section'] === 'partijen') {
        $html .= '<a href="' . URLROOT . '/stemwijzer" ';
        $html .= 'class="inline-flex items-center px-3 py-1.5 bg-secondary/10 text-secondary rounded-lg ';
        $html .= 'hover:bg-secondary/20 transition-colors duration-200 text-sm font-medium">';
        $html .= '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>';
        $html .= '</svg>';
        $html .= 'Doe Stemwijzer';
        $html .= '</a>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

// Additional helper functions can be added here

if (!function_exists('adjust_brightness')) {
    /**
     * Adjusts the brightness of a HEX color.
     *
     * @param string $hex The hex color code.
     * @param int $steps A value between -255 and 255 to adjust brightness.
     * @return string The new hex color code.
     */
    function adjust_brightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter.
        $steps = max(-255, min(255, $steps));

        // Normalize HEX
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2) . str_repeat(substr($hex,1,1), 2) . str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust brightness
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Convert back to hex
        }

        return $return;
    }
}

} // End of !defined('HELPERS_INCLUDED') 