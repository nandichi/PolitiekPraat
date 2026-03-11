<?php
require_once __DIR__ . '/../includes/error_bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

$apiDebugEnv = getenv('API_DEBUG');
$apiDebugEnabled = in_array(strtolower((string) $apiDebugEnv), ['1', 'true', 'yes', 'on'], true);
$nonProductionEnvs = ['local', 'development', 'dev', 'staging', 'test', 'testing'];
$isNonProduction = in_array(APP_ENV, $nonProductionEnvs, true);
$canAccessDiagnostics = $isNonProduction || APP_DEBUG || $apiDebugEnabled;

if (!$canAccessDiagnostics) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Forbidden',
        'timestamp' => date('c')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$debug = [];
$debug['php_version'] = PHP_VERSION;
$debug['base_path'] = dirname(__DIR__);
$debug['request_info'] = [
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
    'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
];

echo json_encode([
    'status' => 'debug_enabled',
    'timestamp' => date('Y-m-d H:i:s'),
    'debug_info' => $debug
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
