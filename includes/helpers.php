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



// Additional helper functions can be added here

} // End of !defined('HELPERS_INCLUDED') 