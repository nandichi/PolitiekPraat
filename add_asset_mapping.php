<?php
/**
 * This script adds a symbolic link from the uploads directory to the public/uploads directory
 * for better asset mapping (if needed on the server)
 */

// Define paths
$sourcePath = __DIR__ . '/uploads/profile_photos';
$targetPath = __DIR__ . '/public/uploads/profile_photos';

echo "Setting up proper asset mapping for profile photos...\n";

// Create target directory if it doesn't exist
if (!file_exists($targetPath)) {
    if (mkdir($targetPath, 0755, true)) {
        echo "Created directory: $targetPath\n";
    } else {
        echo "Failed to create directory: $targetPath\n";
        exit(1);
    }
}

// Create source directory if it doesn't exist
if (!file_exists($sourcePath)) {
    if (mkdir($sourcePath, 0755, true)) {
        echo "Created directory: $sourcePath\n";
    } else {
        echo "Failed to create directory: $sourcePath\n";
        exit(1);
    }
}

// Copy all files from uploads/profile_photos to public/uploads/profile_photos (if they exist)
if (is_dir($sourcePath)) {
    $files = scandir($sourcePath);
    $count = 0;
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $sourceFile = $sourcePath . '/' . $file;
        $targetFile = $targetPath . '/' . $file;
        
        if (file_exists($sourceFile) && !file_exists($targetFile)) {
            if (copy($sourceFile, $targetFile)) {
                echo "Copied: $file\n";
                $count++;
            } else {
                echo "Failed to copy: $file\n";
            }
        }
    }
    
    echo "Copied $count files from $sourcePath to $targetPath\n";
}

echo "Profile photo asset mapping complete!\n";

// Add note about .htaccess
echo "\nNOTE: Make sure your .htaccess file allows direct access to files in the 'uploads' directory.\n";
echo "The following rules should be in your .htaccess file:\n\n";
echo "RewriteRule ^uploads/ - [L]\n";
echo "RewriteRule ^public/ - [L]\n"; 