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
        $result = ['type' => 'initial', 'value' => ''];
        
        // Extract initial for fallback
        if (!empty($username)) {
            $result['value'] = strtoupper(substr($username, 0, 1));
        } else {
            $result['value'] = '?';
        }
        
        // If no profile photo is set, return the initial
        if (empty($profilePhoto)) {
            return $result;
        }
        
        // Check if the path starts with http:// or https://
        if (preg_match('/^https?:\/\//', $profilePhoto)) {
            return ['type' => 'img', 'value' => $profilePhoto];
        }
        
        // Try different path combinations
        $possible_paths = [
            $profilePhoto,
            'uploads/profile_photos/' . basename($profilePhoto),
            'public/' . $profilePhoto
        ];
        
        // If path already has public/, add a version without it
        if (strpos($profilePhoto, 'public/') === 0) {
            $possible_paths[] = substr($profilePhoto, 7); // Remove "public/"
        } else {
            $possible_paths[] = 'public/uploads/profile_photos/' . basename($profilePhoto);
        }
        
        foreach ($possible_paths as $path) {
            $full_path = BASE_PATH . '/' . $path;
            if (file_exists($full_path)) {
                $result['type'] = 'img';
                // Make sure we're not duplicating public/ in the URL
                $web_path = $path;
                if (strpos($path, 'public/') === 0) {
                    $web_path = substr($path, 7); // Remove "public/" for URL
                }
                $result['value'] = URLROOT . '/' . $web_path;
                break;
            }
        }
        
        return $result;
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