<?php
// Simple debug script voor API troubleshooting
header('Content-Type: application/json; charset=UTF-8');

$debug = [];

// Check PHP version
$debug['php_version'] = PHP_VERSION;

// Check if we can include files
$basePath = dirname(__DIR__);
$debug['base_path'] = $basePath;

// Test config file
$configPath = $basePath . '/includes/config.php';
$debug['config_exists'] = file_exists($configPath);
if (file_exists($configPath)) {
    try {
        require_once $configPath;
        $debug['config_loaded'] = true;
        $debug['constants'] = [
            'URLROOT' => defined('URLROOT') ? URLROOT : 'NOT DEFINED',
            'DB_HOST' => defined('DB_HOST') ? 'DEFINED' : 'NOT DEFINED',
            'DB_NAME' => defined('DB_NAME') ? DB_NAME : 'NOT DEFINED'
        ];
    } catch (Exception $e) {
        $debug['config_error'] = $e->getMessage();
    }
}

// Test database file
$dbPath = $basePath . '/includes/Database.php';
$debug['database_file_exists'] = file_exists($dbPath);

if (file_exists($dbPath)) {
    try {
        require_once $dbPath;
        $debug['database_class_exists'] = class_exists('Database');
        
        if (class_exists('Database')) {
            try {
                $db = new Database();
                $debug['database_connection'] = 'SUCCESS';
            } catch (Exception $e) {
                $debug['database_connection'] = 'FAILED: ' . $e->getMessage();
            }
        }
    } catch (Exception $e) {
        $debug['database_load_error'] = $e->getMessage();
    }
}

// Check permissions
$debug['permissions'] = [
    'api_directory_readable' => is_readable(__DIR__),
    'api_directory_writable' => is_writable(__DIR__),
    'includes_directory_readable' => is_readable($basePath . '/includes'),
    'includes_directory_exists' => file_exists($basePath . '/includes')
];

// Check request info
$debug['request_info'] = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'host' => $_SERVER['HTTP_HOST'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown'
];

// File listing
$debug['api_files'] = [];
if (is_readable(__DIR__)) {
    $files = scandir(__DIR__);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $debug['api_files'][] = $file;
        }
    }
}

echo json_encode([
    'status' => 'debug_complete',
    'timestamp' => date('Y-m-d H:i:s'),
    'debug_info' => $debug
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?> 