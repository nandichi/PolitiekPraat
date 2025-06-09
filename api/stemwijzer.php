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
                        'statistics' => $stats,
                        'message' => 'Statistieken succesvol opgehaald'
                    ]);
                    break;
                    
                case 'test-save':
                    // Test de save functionaliteit
                    $testSessionId = 'api_test_' . time();
                    $testAnswers = [0 => 'eens', 1 => 'neutraal', 2 => 'oneens'];
                    $testResults = ['test' => ['score' => 2, 'total' => 6, 'agreement' => 33]];
                    
                    $saved = $stemwijzerController->saveResults($testSessionId, $testAnswers, $testResults, null);
                    
                    echo json_encode([
                        'success' => $saved,
                        'message' => $saved ? 'Test save successful' : 'Test save failed',
                        'test_data' => [
                            'sessionId' => $testSessionId,
                            'answers_count' => count($testAnswers),
                            'results_count' => count($testResults)
                        ]
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
                            'error' => 'Invalid JSON input',
                            'debug' => 'Geen geldige JSON data ontvangen'
                        ]);
                        break;
                    }
                    
                    // Valideer required fields
                    $sessionId = $input['sessionId'] ?? null;
                    $answers = $input['answers'] ?? [];
                    $results = $input['results'] ?? [];
                    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    
                    if (empty($sessionId)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Session ID is required',
                            'debug' => 'sessionId ontbreekt in request'
                        ]);
                        break;
                    }
                    
                    if (empty($answers)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Answers are required',
                            'debug' => 'Geen antwoorden ontvangen'
                        ]);
                        break;
                    }
                    
                    // Log de ontvangen data voor debugging
                    error_log("API: save-results - Ontvangen data: sessionId=$sessionId, antwoorden=" . count($answers) . ", userId=" . ($userId ?? 'null'));
                    
                    $saved = $stemwijzerController->saveResults($sessionId, $answers, $results, $userId);
                    
                    if ($saved) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Resultaten succesvol opgeslagen',
                            'debug' => [
                                'sessionId' => $sessionId,
                                'answersCount' => count($answers),
                                'userId' => $userId,
                                'timestamp' => date('Y-m-d H:i:s')
                            ]
                        ]);
                    } else {
                        http_response_code(500);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Failed to save results',
                            'message' => 'Er is een fout opgetreden bij het opslaan van de resultaten',
                            'debug' => [
                                'sessionId' => $sessionId,
                                'answersCount' => count($answers),
                                'schemaType' => $stemwijzerController->getSchemaType()
                            ]
                        ]);
                    }
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