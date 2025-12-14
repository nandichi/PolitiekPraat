<?php
// Prevent multiple inclusion of config
if (!defined('CONFIG_INCLUDED')) {
    define('CONFIG_INCLUDED', true);

    // Detect environment
    // Check voor production: HTTP_HOST is politiekpraat.nl OF we draaien via CLI op de server
    $is_cli = (php_sapi_name() === 'cli' || php_sapi_name() === 'cli-server');
    $is_production_host = ($_SERVER['HTTP_HOST'] ?? '') === 'politiekpraat.nl';
    $is_production_server = $is_cli && strpos(__DIR__, '/home/naoufal/domains/politiekpraat.nl') !== false;
    $is_production = $is_production_host || $is_production_server;

    // Database configuratie
    if ($is_production) {
        // Production database settings
        define('DB_HOST', 'localhost');
        define('DB_USER', 'naoufal_politiekpraat_user');
        define('DB_PASS', 'Naoufal2004!');
        define('DB_NAME', 'naoufal_politiekpraat_db');
        define('URLROOT', 'https://politiekpraat.nl');
    } else {
        // Local development settings
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'naoufal_politiekpraat_db');
        define('URLROOT', 'http://localhost:8000');
    }

    // Site naam
    define('SITENAME', 'PolitiekPraat');

    // App versie
    define('APPVERSION', '1.0.0');

    // Set the base path
    if (!defined('BASE_PATH')) {
        define('BASE_PATH', dirname(__DIR__));
    }

    // Include Database class first (required by session refresh)
    require_once __DIR__ . '/Database.php';

    // Include helper functions only if the HELPERS_INCLUDED constant is not defined
    if (!defined('HELPERS_INCLUDED')) {
        require_once __DIR__ . '/helpers.php';
    }

    // Start sessie
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include session refresh mechanism (requires Database class)
    require_once __DIR__ . '/session_refresh.php';
    
    // Auto-refresh session on every page load to ensure fresh data
    autoRefreshSession();
}