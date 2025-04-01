# Profile Photo Fix Guide

This document provides instructions to fix the issue with profile photos not displaying correctly.

## The Problem

The profile photo URL `/uploads/profile_photos/profile_1_67eab55fcc478.jpeg` is returning a 404 error because:

1. There's confusion between where the files are stored and where they're being accessed from
2. The .htaccess file may not be properly configured to allow direct access to files

## Solution Steps

### 1. Fix Directory Structure

Ensure that profile photos are consistently stored in the correct location:

```php
// Create both directories to be safe
mkdir -p public/uploads/profile_photos
mkdir -p uploads/profile_photos

// Copy files from test_photos to public/uploads/profile_photos
cp public/test_photos/profile_1_*.* public/uploads/profile_photos/
```

### 2. Update .htaccess

Make sure your .htaccess file has these rules to allow direct access to static files:

```
# Allow direct access to files in specific directories
RewriteRule ^public/ - [L]
RewriteRule ^uploads/ - [L]
RewriteRule ^images/ - [L]
RewriteRule ^css/ - [L]
RewriteRule ^js/ - [L]
```

### 3. Fix Profile Photo Helper Function

The `getProfilePhotoUrl` function in both `includes/helpers.php` and `includes/functions.php` should be updated to check multiple possible locations for the profile photo:

```php
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
```

### 4. Fixed Upload Script

When uploading profile photos, ensure they are consistently saved to the public directory:

```php
// Create upload directory if it doesn't exist
$upload_dir = BASE_PATH . '/public/uploads/profile_photos/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
$filename = 'profile_' . $user_id . '_' . uniqid() . '.' . $file_extension;
$destination = $upload_dir . $filename;

// Move uploaded file
if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination)) {
    $profile_photo = 'uploads/profile_photos/' . $filename;
    // Rest of the code...
}
```

## Additional Troubleshooting

If the issue persists:

1. Check file permissions - make sure web server can read the files
2. Restart the web server to apply .htaccess changes
3. Clear browser cache
4. Make sure the URLROOT constant in config.php is set correctly
5. Check server logs for any additional errors 