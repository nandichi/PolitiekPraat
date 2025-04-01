<?php
/**
 * Comprehensive fix for profile photo issues
 * This script performs multiple fixes:
 * 1. Ensures correct directories exist
 * 2. Copies photos between locations as needed
 * 3. Tests the getProfilePhotoUrl function
 * 4. Generates a test image if needed
 */

// Load config and helpers
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/helpers.php';

echo "=== Profile Photo Fix Utility ===\n\n";

// Check and create directories
$dirs = [
    __DIR__ . '/public/uploads/profile_photos',
    __DIR__ . '/uploads/profile_photos',
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created directory: $dir\n";
        } else {
            echo "✗ Failed to create directory: $dir\n";
        }
    } else {
        echo "✓ Directory exists: $dir\n";
    }
}

// Test photo path
$testPhoto = 'profile_1_67eab55fcc478.jpeg';
$publicPath = __DIR__ . '/public/uploads/profile_photos/' . $testPhoto;
$rootPath = __DIR__ . '/uploads/profile_photos/' . $testPhoto;
$testPath = __DIR__ . '/public/test_photos/' . $testPhoto;

// Create test profile photo if needed
if (!file_exists($publicPath) && !file_exists($rootPath) && !file_exists($testPath)) {
    echo "\nGenerating test profile photo...\n";
    
    // Create a simple test image (100x100 red square)
    $image = imagecreatetruecolor(100, 100);
    $red = imagecolorallocate($image, 255, 0, 0);
    imagefill($image, 0, 0, $red);
    
    if (!file_exists(__DIR__ . '/public/test_photos')) {
        mkdir(__DIR__ . '/public/test_photos', 0755, true);
    }
    
    if (imagejpeg($image, $testPath)) {
        echo "✓ Created test photo: $testPath\n";
    } else {
        echo "✗ Failed to create test photo\n";
    }
    
    imagedestroy($image);
}

// Copy photos between directories
echo "\nSynchronizing photos between directories...\n";
if (file_exists($testPath) && !file_exists($publicPath)) {
    if (copy($testPath, $publicPath)) {
        echo "✓ Copied test photo to public path\n";
    } else {
        echo "✗ Failed to copy test photo to public path\n";
    }
}

if (file_exists($publicPath) && !file_exists($rootPath)) {
    if (copy($publicPath, $rootPath)) {
        echo "✓ Copied photo to root uploads path\n";
    } else {
        echo "✗ Failed to copy photo to root uploads path\n";
    }
}

// Test the getProfilePhotoUrl function
echo "\nTesting getProfilePhotoUrl function...\n";
$paths = [
    'uploads/profile_photos/' . $testPhoto,
    'public/uploads/profile_photos/' . $testPhoto,
    $testPhoto
];

foreach ($paths as $path) {
    $result = getProfilePhotoUrl($path, 'user');
    echo "Path: $path\n";
    echo "  Type: " . $result['type'] . "\n";
    echo "  Value: " . $result['value'] . "\n";
}

// Check .htaccess
echo "\nChecking .htaccess configuration...\n";
$htaccess = file_get_contents(__DIR__ . '/.htaccess');
$rules = [
    'RewriteRule ^uploads/ - [L]',
    'RewriteRule ^public/ - [L]'
];

$allRulesFound = true;
foreach ($rules as $rule) {
    if (strpos($htaccess, $rule) !== false) {
        echo "✓ Rule found: $rule\n";
    } else {
        echo "✗ Rule not found: $rule\n";
        $allRulesFound = false;
    }
}

if (!$allRulesFound) {
    echo "\nYour .htaccess file should contain these rules:\n";
    foreach ($rules as $rule) {
        echo "$rule\n";
    }
}

echo "\n=== Fix Complete ===\n";
echo "If profile photos are still not displaying:\n";
echo "1. Make sure your web server has read permissions for the profile_photos directories\n";
echo "2. Check that the URL in getProfilePhotoUrl matches your actual server configuration\n";
echo "3. Restart your web server to apply .htaccess changes\n"; 