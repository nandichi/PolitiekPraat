<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include necessary files
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/StemwijzerController.php';

// Initialize controller
$stemwijzerController = new StemwijzerController();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? 'data';
            
            switch ($action) {
                case 'data':
                    // Haal alle stemwijzer data op
                    $data = $stemwijzerController->getStemwijzerData();
                    
                    // Debug informatie toevoegen
                    $debug = [
                        'schema_type' => $stemwijzerController->getSchemaType(),
                        'questions_count' => count($data['questions']),
                        'parties_count' => count($data['parties']),
                        'party_logos_count' => count($data['partyLogos']),
                        'has_positions' => !empty($data['questions']) && isset($data['questions'][0]->positions),
                        'has_explanations' => !empty($data['questions']) && isset($data['questions'][0]->explanations)
                    ];
                    
                    echo json_encode([
                        'success' => true,
                        'data' => $data,
                        'total_questions' => count($data['questions']),
                        'debug' => $debug
                    ]);
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
                        'error' => 'Invalid action parameter'
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
                        'error' => 'Invalid action for POST request'
                    ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
} 