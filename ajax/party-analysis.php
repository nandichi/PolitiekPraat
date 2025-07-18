<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/ChatGPTAPI.php';
require_once BASE_PATH . '/models/PartyModel.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

// Validate required fields
if (!isset($input['type']) || !isset($input['partyKey'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields: type, partyKey']);
    exit;
}

$type = $input['type']; // 'party', 'leader', 'voter_profile', 'timeline', 'question'
$partyKey = $input['partyKey'];

try {
    // Get party data
    $partyModel = new PartyModel();
    $party = $partyModel->getParty($partyKey);
    
    if (!$party) {
        echo json_encode(['success' => false, 'error' => 'Party not found']);
        exit;
    }
    
    // Initialize ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    switch ($type) {
        case 'party':
            // Analyze party pros and cons
            $result = $chatGPT->analyzePartyProsAndCons($party['name'], $party);
            
            if (!$result['success']) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Analysis API error: ' . $result['error']
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'type' => 'party',
                'party_name' => $party['name'],
                'content' => $result['content']
            ]);
            break;
            
        case 'leader':
            // Analyze leader pros and cons
            $result = $chatGPT->analyzeLeaderProsAndCons(
                $party['leader'], 
                $party['name'], 
                $party['leader_info'], 
                $party
            );
            
            if (!$result['success']) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Analysis API error: ' . $result['error']
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'type' => 'leader',
                'leader_name' => $party['leader'],
                'party_name' => $party['name'],
                'content' => $result['content']
            ]);
            break;
            
        case 'voter_profile':
            // Generate voter profile
            $result = $chatGPT->generateVoterProfile($party['name'], $party);
            
            if (!$result['success']) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Analysis API error: ' . $result['error']
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'type' => 'voter_profile',
                'party_name' => $party['name'],
                'content' => $result['content']
            ]);
            break;
            
        case 'timeline':
            // Generate political timeline
            $result = $chatGPT->generatePoliticalTimeline($party['name'], $party);
            
            if (!$result['success']) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Analysis API error: ' . $result['error']
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'type' => 'timeline',
                'party_name' => $party['name'],
                'content' => $result['content']
            ]);
            break;
            
        case 'question':
            // Answer specific question
            if (!isset($input['question']) || empty(trim($input['question']))) {
                echo json_encode(['success' => false, 'error' => 'Question field is required for Q&A']);
                exit;
            }
            
            $question = trim($input['question']);
            $result = $chatGPT->answerPartyQuestion($question, $party['name'], $party);
            
            if (!$result['success']) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Analysis API error: ' . $result['error']
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'type' => 'question',
                'party_name' => $party['name'],
                'question' => $question,
                'content' => $result['content']
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid analysis type. Use: party, leader, voter_profile, timeline, or question']);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?> 