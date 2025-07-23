<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../vendor/erusev/parsedown/Parsedown.php';
require_once __DIR__ . '/../includes/BlogController.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if required data is provided
if (!isset($_POST['poll_id']) || !isset($_POST['choice'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ontbrekende gegevens']);
    exit;
}

$pollId = filter_var($_POST['poll_id'], FILTER_VALIDATE_INT);
$choice = trim($_POST['choice']);

// Validate input
if (!$pollId || !in_array($choice, ['A', 'B'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldige gegevens']);
    exit;
}

try {
    // Create BlogController instance
    $blogController = new BlogController();
    
    // Vote on poll
    $result = $blogController->votePoll($pollId, $choice);
    
    // Set appropriate HTTP status code
    if (!$result['success']) {
        http_response_code(400);
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($result);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Er is een serverfout opgetreden: ' . $e->getMessage()
    ]);
}
?> 