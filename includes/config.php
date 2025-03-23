<?php
// Detect environment
$is_production = ($_SERVER['HTTP_HOST'] ?? 'localhost') === 'politiekpraat.nl';

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
    define('DB_PASS', 'Naoufal2004!');
    define('DB_NAME', 'politiek_db');
    define('URLROOT', 'http://localhost:8000');
}

// Site naam
define('SITENAME', 'PolitiekPraat');

// App versie
define('APPVERSION', '1.0.0');

// Start sessie
session_start();