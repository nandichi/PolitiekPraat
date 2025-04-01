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

// Additional helper functions can be added here

} // End of !defined('HELPERS_INCLUDED') 