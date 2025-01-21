<?php
// Load environment variables
$env = parse_ini_file('../.env');

// Database configuratie
define('DB_HOST', getenv('DB_HOST') ?: $env['DB_HOST']);
define('DB_USER', getenv('DB_USER') ?: $env['DB_USER']);
define('DB_PASS', getenv('DB_PASS') ?: $env['DB_PASS']);
define('DB_NAME', getenv('DB_NAME') ?: $env['DB_NAME']);

// URL Root
define('URLROOT', getenv('SITE_URL') ?: $env['SITE_URL']);

// Site naam
define('SITENAME', 'PolitiekPraat');

// App versie
define('APPVERSION', '1.0.0');

// Start sessie
session_start(); 