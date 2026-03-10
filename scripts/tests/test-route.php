<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Route</h1>";
echo "<p>Je bezoekt: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Server software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Test bestandsrechten
echo "<h2>Bestandsrechten Test</h2>";
$current_dir = __DIR__;
echo "<p>Huidige map: " . $current_dir . "</p>";
echo "<p>Leesbaar: " . (is_readable($current_dir) ? 'Ja' : 'Nee') . "</p>";
echo "<p>Schrijfbaar: " . (is_writable($current_dir) ? 'Ja' : 'Nee') . "</p>";

// Test includes map
$includes_dir = __DIR__ . '/includes';
echo "<h2>Includes Map Test</h2>";
echo "<p>Includes map bestaat: " . (file_exists($includes_dir) ? 'Ja' : 'Nee') . "</p>";
if (file_exists($includes_dir)) {
    echo "<p>Includes map leesbaar: " . (is_readable($includes_dir) ? 'Ja' : 'Nee') . "</p>";
}
?> 