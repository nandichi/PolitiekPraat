<?php
/**
 * Session Refresh Mechanism
 * 
 * Automatically synchronizes session data with database on each page load
 * to ensure user data (especially profile_photo) is always up-to-date
 */

if (!defined('SESSION_REFRESH_INCLUDED')) {
    define('SESSION_REFRESH_INCLUDED', true);

    /**
     * Refresh session data from database
     * Call this early in page execution to ensure fresh data
     */
    function refreshSessionFromDatabase() {
        // Only refresh if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        try {
            $db = new Database();
            
            // Get fresh user data from database
            $db->query("SELECT username, profile_photo, is_admin, bio FROM users WHERE id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $freshUser = $db->single();
            
            if (!$freshUser) {
                // User no longer exists - logout
                session_destroy();
                return false;
            }
            
            // Check if profile_photo has changed
            $oldPhoto = $_SESSION['profile_photo'] ?? null;
            $newPhoto = $freshUser->profile_photo;
            
            // Update session with fresh data
            $_SESSION['username'] = $freshUser->username;
            $_SESSION['profile_photo'] = $freshUser->profile_photo;
            $_SESSION['is_admin'] = $freshUser->is_admin;
            
            // Optional: Add bio to session if needed
            if (isset($freshUser->bio)) {
                $_SESSION['bio'] = $freshUser->bio;
            }
            
            // Log refresh for debugging (only if photo changed)
            if ($oldPhoto !== $newPhoto) {
                error_log("Session refreshed for user {$_SESSION['user_id']}: profile_photo changed from " . 
                         ($oldPhoto ?: '[NULL]') . " to " . ($newPhoto ?: '[NULL]'));
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Session refresh failed for user {$_SESSION['user_id']}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get profile photo with automatic session refresh fallback
     * This ensures we always get the latest profile photo even if session is stale
     */
    function getProfilePhotoWithRefresh($username = null) {
        // Use session data first
        $sessionPhoto = $_SESSION['profile_photo'] ?? null;
        $sessionUsername = $_SESSION['username'] ?? $username;
        
        if (function_exists('getProfilePhotoUrl')) {
            return getProfilePhotoUrl($sessionPhoto, $sessionUsername);
        }
        
        // Fallback if helper function is not available
        if (empty($sessionPhoto) && !empty($sessionUsername)) {
            return ['type' => 'initial', 'value' => strtoupper(substr($sessionUsername, 0, 1))];
        }
        
        return ['type' => 'initial', 'value' => '?'];
    }

    /**
     * Auto-refresh session on page load (call this early)
     */
    function autoRefreshSession() {
        // Only refresh once per page load to avoid multiple DB calls
        static $refreshed = false;
        
        if (!$refreshed && isset($_SESSION['user_id'])) {
            refreshSessionFromDatabase();
            $refreshed = true;
        }
    }
}
?>
