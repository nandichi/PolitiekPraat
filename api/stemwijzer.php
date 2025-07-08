<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include necessary files
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/StemwijzerController.php';
require_once '../includes/ChatGPTAPI.php';

// Initialize controllers
$stemwijzerController = new StemwijzerController();
$chatGPTAPI = new ChatGPTAPI();

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
                    
                case 'results':
                    // Haal opgeslagen resultaten op via share_id
                    $shareId = $_GET['share_id'] ?? '';
                    
                    if (empty($shareId)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Share ID is required'
                        ]);
                        break;
                    }
                    
                    $results = $stemwijzerController->getResultsByShareId($shareId);
                    
                    if ($results) {
                        // Haal ook de stemwijzer data op voor het tonen van de resultaten
                        $stemwijzerData = $stemwijzerController->getStemwijzerData();
                        
                        echo json_encode([
                            'success' => true,
                            'results' => $results,
                            'stemwijzer_data' => $stemwijzerData,
                            'message' => 'Resultaten succesvol opgehaald'
                        ]);
                    } else {
                        http_response_code(404);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Results not found',
                            'message' => 'Geen resultaten gevonden voor deze link'
                        ]);
                    }
                    break;
                    
                case 'personality-analysis':
                    // Haal persoonlijkheidsanalyse op via share_id
                    $shareId = $_GET['share_id'] ?? '';
                    
                    if (empty($shareId)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Share ID is required'
                        ]);
                        break;
                    }
                    
                    // Haal resultaten op
                    $results = $stemwijzerController->getResultsByShareId($shareId);
                    
                    if ($results && isset($results->answers)) {
                        // Haal vragen op voor analyse
                        $stemwijzerData = $stemwijzerController->getStemwijzerData();
                        
                        // Bereken persoonlijkheidsanalyse
                        $personalityAnalysis = $stemwijzerController->analyzePoliticalPersonality($results->answers, $stemwijzerData['questions']);
                        
                        echo json_encode([
                            'success' => true,
                            'personality_analysis' => $personalityAnalysis,
                            'message' => 'Persoonlijkheidsanalyse succesvol berekend'
                        ]);
                    } else {
                        http_response_code(404);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Results not found',
                            'message' => 'Geen resultaten gevonden voor deze link'
                        ]);
                    }
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
                    
                case 'test-chatgpt':
                    // Test ChatGPT API verbinding
                    $testResult = $chatGPTAPI->testConnection();
                    
                    echo json_encode([
                        'success' => true,
                        'chatgpt_test' => $testResult,
                        'message' => 'ChatGPT API test uitgevoerd'
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
                    
                    $shareId = $stemwijzerController->saveResults($sessionId, $answers, $results, $userId);
                    
                    if ($shareId) {
                        // Genereer de link voor het delen van resultaten
                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                        $host = $_SERVER['HTTP_HOST'];
                        $shareUrl = $protocol . $host . '/resultaten/' . $shareId;
                        
                        echo json_encode([
                            'success' => true,
                            'message' => 'Resultaten succesvol opgeslagen',
                            'share_id' => $shareId,
                            'share_url' => $shareUrl,
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
                    
                case 'explain-question':
                    // ChatGPT uitleg voor een specifieke vraag
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Invalid JSON input'
                        ]);
                        break;
                    }
                    
                    $questionIndex = $input['questionIndex'] ?? null;
                    
                    if (is_null($questionIndex)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Question index is required'
                        ]);
                        break;
                    }
                    
                    // Haal vragen data op
                    $stemwijzerData = $stemwijzerController->getStemwijzerData();
                    
                    if (!isset($stemwijzerData['questions'][$questionIndex])) {
                        http_response_code(404);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Question not found'
                        ]);
                        break;
                    }
                    
                    $question = $stemwijzerData['questions'][$questionIndex];
                    
                    // Controleer of $question een object of array is
                    if (is_object($question)) {
                        $title = $question->title ?? '';
                        $context = $question->context ?? '';
                        $leftView = $question->left_view ?? '';
                        $rightView = $question->right_view ?? '';
                    } else {
                        $title = $question['title'] ?? '';
                        $context = $question['context'] ?? '';
                        $leftView = $question['left_view'] ?? '';
                        $rightView = $question['right_view'] ?? '';
                    }
                    
                    // Vraag ChatGPT om uitleg
                    $explanation = $chatGPTAPI->explainQuestion(
                        $title,
                        $context,
                        $leftView,
                        $rightView
                    );
                    
                    echo json_encode([
                        'success' => true,
                        'explanation' => $explanation,
                        'question_index' => $questionIndex,
                        'question_title' => $title
                    ]);
                    break;
                    
                case 'explain-match':
                    // ChatGPT uitleg waarom een partij bij iemand past
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Invalid JSON input'
                        ]);
                        break;
                    }
                    
                    $partyName = $input['partyName'] ?? null;
                    $userAnswers = $input['userAnswers'] ?? [];
                    $matchPercentage = $input['matchPercentage'] ?? 0;
                    
                    if (empty($partyName)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Party name is required'
                        ]);
                        break;
                    }
                    
                    if (empty($userAnswers)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'User answers are required'
                        ]);
                        break;
                    }
                    
                    // Haal vragen data op
                    $stemwijzerData = $stemwijzerController->getStemwijzerData();
                    
                    // Vraag ChatGPT om partij match uitleg
                    $explanation = $chatGPTAPI->explainPartyMatch(
                        $partyName,
                        $userAnswers,
                        $stemwijzerData['questions'],
                        $matchPercentage
                    );
                    
                    echo json_encode([
                        'success' => true,
                        'explanation' => $explanation,
                        'party_name' => $partyName,
                        'match_percentage' => $matchPercentage
                    ]);
                    break;
                    
                case 'political-advice':
                    // ChatGPT algemeen politiek advies
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    if (!$input) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Invalid JSON input'
                        ]);
                        break;
                    }
                    
                    $personalityAnalysis = $input['personalityAnalysis'] ?? null;
                    $topMatches = $input['topMatches'] ?? [];
                    
                    if (empty($personalityAnalysis) || empty($topMatches)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Personality analysis and top matches are required'
                        ]);
                        break;
                    }
                    
                    // Vraag ChatGPT om algemeen politiek advies
                    $advice = $chatGPTAPI->generatePoliticalAdvice($personalityAnalysis, $topMatches);
                    
                    echo json_encode([
                        'success' => true,
                        'advice' => $advice,
                        'personality_type' => $personalityAnalysis['political_profile']['type'] ?? 'Onbekend'
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