<?php
/**
 * This script moves all profile photos from the test_photos directory to the uploads/profile_photos directory
 */

// Define paths
$sourceDir = __DIR__ . '/public/test_photos/';
$destDir = __DIR__ . '/public/uploads/profile_photos/';

// Create destination directory if it doesn't exist
if (!file_exists($destDir)) {
    mkdir($destDir, 0755, true);
    echo "Created directory: " . $destDir . "\n";
}

// Get all image files in the source directory
if (is_dir($sourceDir)) {
    $files = scandir($sourceDir);
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $count = 0;
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        // Check if it's an image file
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($extension, $imageExtensions)) {
            $sourcePath = $sourceDir . $file;
            $destPath = $destDir . $file;
            
            // Only copy if source exists and is not already in destination
            if (file_exists($sourcePath) && !file_exists($destPath)) {
                if (copy($sourcePath, $destPath)) {
                    echo "Copied: " . $file . "\n";
                    $count++;
                } else {
                    echo "Failed to copy: " . $file . "\n";
                }
            } elseif (file_exists($destPath)) {
                echo "File already exists in destination: " . $file . "\n";
                $count++;
            }
        }
    }
    
    echo "\nMoved " . $count . " profile photos to the uploads/profile_photos directory.\n";
} else {
    echo "Source directory does not exist: " . $sourceDir . "\n";
} 