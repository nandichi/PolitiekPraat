<?php
// Includes voor de database configuratie en eventuele andere benodigde bestanden
require_once '../includes/config.php';
require_once '../includes/Database.php';

// CORS headers toevoegen om API toegankelijk te maken
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Connect met de database via onze Database class
try {
    $database = new Database();
    $db = $database->getConnection();
} catch(Exception $e) {
    // Error bij database connectie
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connectie fout: ' . $e->getMessage()
    ]);
    exit;
}

// Response data structuur
$response = [
    'success' => true,
    'partyLogos' => [],
    'questions' => []
];

// 1. Alle partijen en hun logo's ophalen
try {
    $query = "SELECT party_id, party_name, party_logo FROM parties ORDER BY party_name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $parties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Maak een lookup array voor party_id -> party_name
    $partyIdToName = [];
    
    foreach ($parties as $party) {
        $partyIdToName[$party['party_id']] = $party['party_name'];
        $response['partyLogos'][$party['party_name']] = $party['party_logo'];
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fout bij ophalen partijen: ' . $e->getMessage()
    ]);
    exit;
}

// 2. Alle vragen ophalen
try {
    $query = "SELECT question_id, title, description, context, left_view, right_view 
              FROM questions 
              ORDER BY question_id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Voor elke vraag, haal de standpunten van alle partijen op
    foreach ($questions as $question) {
        $questionData = [
            'title' => $question['title'],
            'description' => $question['description'],
            'context' => $question['context'],
            'leftView' => $question['left_view'],
            'rightView' => $question['right_view'],
            'positions' => [],
            'explanations' => []
        ];
        
        // Haal standpunten en uitleg op voor deze vraag
        $positionsQuery = "SELECT party_id, stance, explanation 
                          FROM positions 
                          WHERE question_id = :question_id";
        $posStmt = $db->prepare($positionsQuery);
        $posStmt->bindParam(':question_id', $question['question_id']);
        $posStmt->execute();
        
        $positions = $posStmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($positions as $position) {
            $partyName = $partyIdToName[$position['party_id']];
            $questionData['positions'][$partyName] = $position['stance'];
            $questionData['explanations'][$partyName] = $position['explanation'];
        }
        
        $response['questions'][] = $questionData;
    }
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fout bij ophalen vragen: ' . $e->getMessage()
    ]);
    exit;
}

// Stuur de JSON response terug
echo json_encode($response); 