<?php
// Tijdelijke router voor de PHP built-in server (lokale preview).
// Serveert bestaande statische bestanden direct, routeert de rest naar index.php.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}
require __DIR__ . '/index.php';
