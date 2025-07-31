<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Alleen POST requests toestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Alleen POST requests toegestaan']);
    exit;
}

require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/ChatGPTAPI.php';
require_once '../includes/StemwijzerController.php';

try {
    // Parse JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['action']) || !isset($input['data'])) {
        throw new Exception('Ongeldige request data');
    }
    
    $action = $input['action'];
    $data = $input['data'];
    
    // Valideer required data
    if (!isset($data['topParty']) || !isset($data['personalityAnalysis'])) {
        throw new Exception('Ontbrekende analyse data');
    }
    
    // Initialize ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    // Mock party data voor demo (in productie zou dit uit database komen)
    $partyData = [
        'description' => 'Nederlandse politieke partij',
        'leader' => 'Partijleider',
        'current_seats' => 15,
        'polling' => ['seats' => 18],
        'standpoints' => [
            'Immigratie' => 'Gematigde immigratiestandpunten',
            'Klimaat' => 'Groene transitie',
            'Zorg' => 'Toegankelijke zorg voor iedereen',
            'Energie' => 'Duurzame energie'
        ]
    ];
    
    $response = ['success' => false, 'content' => ''];
    
    switch ($action) {
        case 'party_match':
            $result = $chatGPT->explainPartyMatch(
                $data['topParty']['name'],
                $data['userAnswers'],
                [], // Questions - zou uit partijmeter data moeten komen
                $data['topParty']['agreement']
            );
            break;
            
        case 'political_advice':
            $result = $chatGPT->generatePoliticalAdvice(
                $data['personalityAnalysis'],
                $data['topMatches']
            );
            break;
            
        case 'party_pros_cons':
            $result = $chatGPT->analyzePartyProsAndCons(
                $data['topParty']['name'],
                $partyData
            );
            break;
            
        case 'voter_profile':
            $result = $chatGPT->generateVoterProfile(
                $data['topParty']['name'],
                $partyData
            );
            break;
            
        default:
            throw new Exception('Onbekende actie: ' . $action);
    }
    
    if ($result['success']) {
        // Format de content voor mooiere weergave
        $content = $result['content'];
        
        // Replace markdown-style headers met HTML
        $content = preg_replace('/^## (.+)$/m', '<h3 class="text-lg font-bold text-gray-800 mb-3 mt-6 first:mt-0">$1</h3>', $content);
        $content = preg_replace('/^### (.+)$/m', '<h4 class="text-base font-semibold text-gray-700 mb-2 mt-4">$1</h4>', $content);
        
        // Replace bullet points
        $content = preg_replace('/^- (.+)$/m', '<div class="flex items-start space-x-2 mb-2"><span class="text-indigo-500 mt-1">•</span><span>$1</span></div>', $content);
        
        // Replace bold text
        $content = preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-semibold text-gray-800">$1</strong>', $content);
        
        // Replace newlines with proper spacing
        $content = preg_replace('/\n\n/', '</p><p class="mb-4">', $content);
        $content = '<p class="mb-4">' . $content . '</p>';
        
        $response = [
            'success' => true,
            'content' => $content
        ];
    } else {
        $response = [
            'success' => false,
            'error' => $result['error'] ?? 'Onbekende fout bij AI analyse'
        ];
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);
?> 