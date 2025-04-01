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
            
            // Define potential paths to check
            $possiblePaths = [
                // Check in public directory (preferred location)
                BASE_PATH . '/public/' . $profilePhoto,
                // Check in root uploads directory (alternate location)
                BASE_PATH . '/' . $profilePhoto,
                // Check with just the filename in both locations
                BASE_PATH . '/public/uploads/profile_photos/' . basename($profilePhoto),
                BASE_PATH . '/uploads/profile_photos/' . basename($profilePhoto)
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
                    
                    return ['type' => 'img', 'value' => URLROOT . '/' . $urlPath];
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

// Additional helper functions can be added here

} // End of !defined('HELPERS_INCLUDED') 