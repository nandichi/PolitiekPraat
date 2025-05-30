<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Error logging voor debugging
error_reporting(E_ALL);
ini_set('log_errors', 1);

// Include necessary files
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/StemwijzerController.php';

try {
    // Initialize controller
    $stemwijzerController = new StemwijzerController();

    // Get the request method
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? 'data';
            
            switch ($action) {
                case 'data':
                    // Haal alle stemwijzer data op
                    $data = $stemwijzerController->getStemwijzerData();
                    
                    // Debug info toevoegen
                    $response = [
                        'success' => true,
                        'data' => $data,
                        'total_questions' => count($data['questions']),
                        'debug' => [
                            'schema_type' => $data['schema_type'] ?? 'unknown',
                            'questions_count' => count($data['questions']),
                            'parties_count' => count($data['parties']),
                            'logos_count' => count($data['partyLogos'])
                        ]
                    ];
                    
                    echo json_encode($response);
                    break;
                    
                case 'questions':
                    // Haal alleen vragen op
                    $questions = $stemwijzerController->getQuestions();
                    echo json_encode([
                        'success' => true,
                        'questions' => $questions,
                        'count' => count($questions)
                    ]);
                    break;
                    
                case 'parties':
                    // Haal alleen partijen op
                    $parties = $stemwijzerController->getParties();
                    echo json_encode([
                        'success' => true,
                        'parties' => $parties,
                        'count' => count($parties)
                    ]);
                    break;
                    
                case 'stats':
                    // Haal statistieken op
                    $stats = $stemwijzerController->getStatistics();
                    echo json_encode([
                        'success' => true,
                        'statistics' => $stats
                    ]);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action parameter: ' . $action
                    ]);
            }
            break;
            
        case 'POST':
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'save-results':
                    // Sla resultaten op
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Invalid JSON input'
                        ]);
                        break;
                    }
                    
                    $sessionId = $input['sessionId'] ?? session_id();
                    $answers = $input['answers'] ?? [];
                    $results = $input['results'] ?? [];
                    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    
                    $saved = $stemwijzerController->saveResults($sessionId, $answers, $results, $userId);
                    
                    echo json_encode([
                        'success' => $saved,
                        'message' => $saved ? 'Results saved successfully' : 'Failed to save results'
                    ]);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action for POST request: ' . $action
                    ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed: ' . $method
            ]);
    }
    
} catch (Exception $e) {
    // Log the error for debugging
    error_log('Stemwijzer API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
} 