<?php
// Make sure helper functions are included first
if (!defined('HELPERS_INCLUDED')) {
    require_once __DIR__ . '/helpers.php';
}

// Prevent multiple inclusions
if (!defined('FUNCTIONS_INCLUDED')) {
    define('FUNCTIONS_INCLUDED', true);

    if (!function_exists('redirect')) {
        function redirect($page) {
            header('location: ' . URLROOT . '/' . $page);
        }
    }

    if (!function_exists('isLoggedIn')) {
        function isLoggedIn() {
            return isset($_SESSION['user_id']);
        }
    }

    // Don't redeclare if it already exists in helpers.php
    if (!function_exists('isAdmin')) {
        function isAdmin() {
            return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
        }
    }

    if (!function_exists('sanitize')) {
        function sanitize($dirty) {
            return htmlspecialchars($dirty, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('generateSlug')) {
        function generateSlug($text) {
            // Vervang non-alphanumerieke karakters met een streepje
            $text = preg_replace('~[^\pL\d]+~u', '-', $text);
            // Translitereer
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            // Verwijder ongewenste karakters
            $text = preg_replace('~[^-\w]+~', '', $text);
            // Trim
            $text = trim($text, '-');
            // Verwijder duplicate streepjes
            $text = preg_replace('~-+~', '-', $text);
            // Lowercase
            $text = strtolower($text);
            
            return $text;
        }
    }

    if (!function_exists('formatDate')) {
        function formatDate($date) {
            return date('d-m-Y', strtotime($date));
        }
    }

    // Don't redeclare if it already exists in helpers.php
    if (!function_exists('getProfilePhotoUrl')) {
        /**
         * Get the URL for a profile photo with fallbacks
         * @param string|null $profile_photo The profile photo path from database
         * @param string $username The username (for initial fallback)
         * @return array Contains 'type' (img or initial) and 'value' (image URL or initial letter)
         */
        function getProfilePhotoUrl($profile_photo, $username = '') {
            $result = ['type' => 'initial', 'value' => ''];
            
            // Extract initial for fallback
            if (!empty($username)) {
                $result['value'] = strtoupper(substr($username, 0, 1));
            } else {
                $result['value'] = 'U';
            }
            
            // If no profile photo is set, return the initial
            if (empty($profile_photo)) {
                return $result;
            }
            
            // Define potential paths to check
            $possiblePaths = [
                // Check in public directory (preferred location)
                BASE_PATH . '/public/' . $profile_photo,
                // Check in root uploads directory (alternate location)
                BASE_PATH . '/' . $profile_photo,
                // Check with just the filename in both locations
                BASE_PATH . '/public/uploads/profile_photos/' . basename($profile_photo),
                BASE_PATH . '/uploads/profile_photos/' . basename($profile_photo)
            ];
            
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    // Convert the path to a URL
                    $urlPath = str_replace(BASE_PATH . '/public/', '', $path);
                    $urlPath = str_replace(BASE_PATH . '/', '', $urlPath);
                    
                    // Make sure the path starts from webroot
                    if (strpos($urlPath, 'public/') === 0) {
                        $urlPath = substr($urlPath, 7);
                    }
                    
                    $result['type'] = 'img';
                    $result['value'] = URLROOT . '/' . $urlPath;
                    break;
                }
            }
            
            return $result;
        }
    }
} // End of FUNCTIONS_INCLUDED check 