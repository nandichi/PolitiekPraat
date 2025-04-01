<?php
// Simple script to check if profile photo is accessible
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/helpers.php';

// Test the different paths in getProfilePhotoUrl function
$profilePhotoPath = 'uploads/profile_photos/profile_1_67eab55fcc478.jpeg';
$result = getProfilePhotoUrl($profilePhotoPath, 'user');

echo "Profile photo test results:\n";
echo "Type: " . $result['type'] . "\n";
echo "Value: " . $result['value'] . "\n";

// Check if the file exists in various locations
$pathsToCheck = [
    __DIR__ . '/public/' . $profilePhotoPath,
    __DIR__ . '/' . $profilePhotoPath,
    __DIR__ . '/public/uploads/profile_photos/' . basename($profilePhotoPath),
    __DIR__ . '/uploads/profile_photos/' . basename($profilePhotoPath)
];

echo "\nChecking file existence:\n";
foreach ($pathsToCheck as $path) {
    echo $path . ": " . (file_exists($path) ? "EXISTS" : "NOT FOUND") . "\n";
}

// Check if the URL is accessible
$url = $result['value'];
echo "\nTrying to access the URL: " . $url . "\n";

$headers = @get_headers($url);
if ($headers && strpos($headers[0], '200') !== false) {
    echo "URL is accessible (200 OK)\n";
} else {
    echo "URL is NOT accessible\n";
}

// Output current configuration
echo "\nConfiguration:\n";
echo "BASE_PATH: " . BASE_PATH . "\n";
echo "URLROOT: " . URLROOT . "\n"; 