<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

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