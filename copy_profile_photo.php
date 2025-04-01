<?php
// Simple script to copy profile photo to the correct location
$source = __DIR__ . '/public/test_photos/profile_1_67eab55fcc478.jpeg';
$destination = __DIR__ . '/public/uploads/profile_photos/profile_1_67eab55fcc478.jpeg';

if (file_exists($source)) {
    // Create destination directory if it doesn't exist
    $dir = dirname($destination);
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Copy the file
    if (copy($source, $destination)) {
        echo "Successfully copied profile photo to: " . $destination . "\n";
    } else {
        echo "Failed to copy file.\n";
    }
} else {
    echo "Source file not found: " . $source . "\n";
} 