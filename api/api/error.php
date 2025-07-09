<?php
// Include common API configuration for consistency
require_once 'config.php';

$errorCode = http_response_code();
$errorMessage = '';

switch ($errorCode) {
    case 404:
        $errorMessage = 'API endpoint niet gevonden';
        break;
    case 500:
        $errorMessage = 'Interne server fout';
        break;
    default:
        $errorMessage = 'Onbekende fout opgetreden';
        break;
}

echo json_encode([
    'success' => false,
    'error' => $errorMessage,
    'code' => $errorCode
]);
?> 