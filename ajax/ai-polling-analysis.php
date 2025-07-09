<?php
// Error reporting voor development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/ChatGPTAPI.php';

// Headers voor JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Controleer of het een POST request is
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Alleen POST requests toegestaan'
    ]);
    exit;
}

try {
    // Lees de input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['parties'])) {
        throw new Exception('Ongeldige input data');
    }
    
    // Initialiseer ChatGPT API
    $chatgpt = new ChatGPTAPI();
    
    // Voer de peiling analyse uit
    $result = $chatgpt->analyzePollingData($data['parties']);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'content' => $result['content'],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        throw new Exception($result['error'] ?? 'AI analyse mislukt');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?> 