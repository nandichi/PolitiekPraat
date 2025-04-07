<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log eventuele fouten naar een bestand
ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

// Includes voor de database configuratie en eventuele andere benodigde bestanden
require_once '../includes/config.php';
require_once '../includes/Database.php';

// CORS headers toevoegen om API toegankelijk te maken
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Initialiseer response array
$response = [
    'success' => true,
    'partyLogos' => [],
    'questions' => [],
    'debug' => [] // Debug informatie
];

// Connect met de database via onze Database class
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db === null) {
        throw new Exception("Database connectie is null");
    }
    
    $response['debug'][] = "Database connectie succesvol";
    
    // Controleer welk schema in gebruik is (oud of nieuw)
    $schemaType = 'oud'; // Standaard het oude schema aannemen
    
    try {
        // Controleer of we nieuwe kolommen hebben
        $stmt = $db->prepare("DESCRIBE parties");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $response['debug'][] = "Kolommen in de parties tabel: " . implode(", ", $columns);
        
        // Controleer of we 'id' of 'party_id' hebben
        if (in_array('id', $columns)) {
            $schemaType = 'nieuw';
            $response['debug'][] = "Nieuw schema gedetecteerd (id, name, logo_url)";
        } else {
            $response['debug'][] = "Oud schema gedetecteerd (party_id, party_name, party_logo)";
        }
    } catch (PDOException $e) {
        $response['debug'][] = "Kan tabelstructuur niet ophalen: " . $e->getMessage();
    }
    
    // Bepaal queries op basis van schema type
    if ($schemaType === 'nieuw') {
        // NIEUW SCHEMA: id, name, logo_url
        
        // Haal alle partijen en hun logo's op
        $partiesQuery = "SELECT id, name, logo_url FROM parties ORDER BY name";
        $partiesStmt = $db->prepare($partiesQuery);
        $partiesStmt->execute();
        
        $partyCount = $partiesStmt->rowCount();
        $response['debug'][] = "Aantal partijen gevonden: $partyCount";
        
        while ($party = $partiesStmt->fetch(PDO::FETCH_ASSOC)) {
            $response['partyLogos'][] = [
                'partyId' => $party['id'],
                'name' => $party['name'],
                'logoUrl' => $party['logo_url']
            ];
        }
        
        // Haal alle vragen op met hun stellingen en partijposities
        $questionsQuery = "SELECT id, title, description, context, left_view, right_view 
                          FROM questions ORDER BY id";
        $questionsStmt = $db->prepare($questionsQuery);
        $questionsStmt->execute();
        
        $questionCount = $questionsStmt->rowCount();
        $response['debug'][] = "Aantal vragen gevonden: $questionCount";
        
        while ($question = $questionsStmt->fetch(PDO::FETCH_ASSOC)) {
            $questionId = $question['id'];
            
            // Haal de posities op voor deze vraag
            $positionsQuery = "SELECT p.party_id, p.position, p.explanation, pa.name as party_name 
                              FROM positions p 
                              JOIN parties pa ON p.party_id = pa.id 
                              WHERE p.question_id = :question_id 
                              ORDER BY pa.name";
            $positionsStmt = $db->prepare($positionsQuery);
            $positionsStmt->bindParam(':question_id', $questionId);
            $positionsStmt->execute();
            
            $positionCount = $positionsStmt->rowCount();
            $response['debug'][] = "Aantal posities voor vraag $questionId: $positionCount";
            
            $positions = [];
            while ($position = $positionsStmt->fetch(PDO::FETCH_ASSOC)) {
                $positions[] = [
                    'partyId' => $position['party_id'],
                    'partyName' => $position['party_name'],
                    'position' => $position['position'],
                    'explanation' => $position['explanation']
                ];
            }
            
            $response['questions'][] = [
                'id' => $questionId,
                'title' => $question['title'],
                'description' => $question['description'],
                'context' => $question['context'],
                'leftView' => $question['left_view'],
                'rightView' => $question['right_view'],
                'positions' => $positions
            ];
        }
    } else {
        // OUD SCHEMA: party_id, party_name, party_logo
        
        // Haal alle partijen en hun logo's op
        $partiesQuery = "SELECT party_id, party_name, party_logo FROM parties ORDER BY party_name";
        $partiesStmt = $db->prepare($partiesQuery);
        $partiesStmt->execute();
        
        $partyCount = $partiesStmt->rowCount();
        $response['debug'][] = "Aantal partijen gevonden: $partyCount";
        
        while ($party = $partiesStmt->fetch(PDO::FETCH_ASSOC)) {
            $response['partyLogos'][] = [
                'partyId' => $party['party_id'],
                'name' => $party['party_name'],
                'logoUrl' => $party['party_logo']
            ];
        }
        
        // Haal alle vragen op met hun stellingen en partijposities
        $questionsQuery = "SELECT question_id, title, description, context, left_view, right_view 
                          FROM questions ORDER BY question_id";
        $questionsStmt = $db->prepare($questionsQuery);
        $questionsStmt->execute();
        
        $questionCount = $questionsStmt->rowCount();
        $response['debug'][] = "Aantal vragen gevonden: $questionCount";
        
        while ($question = $questionsStmt->fetch(PDO::FETCH_ASSOC)) {
            $questionId = $question['question_id'];
            
            // Haal de posities op voor deze vraag
            $positionsQuery = "SELECT p.party_id, p.stance as position, p.explanation, pa.party_name 
                              FROM positions p 
                              JOIN parties pa ON p.party_id = pa.party_id 
                              WHERE p.question_id = :question_id 
                              ORDER BY pa.party_name";
            $positionsStmt = $db->prepare($positionsQuery);
            $positionsStmt->bindParam(':question_id', $questionId);
            $positionsStmt->execute();
            
            $positionCount = $positionsStmt->rowCount();
            $response['debug'][] = "Aantal posities voor vraag $questionId: $positionCount";
            
            $positions = [];
            while ($position = $positionsStmt->fetch(PDO::FETCH_ASSOC)) {
                $positions[] = [
                    'partyId' => $position['party_id'],
                    'partyName' => $position['party_name'],
                    'position' => $position['position'],
                    'explanation' => $position['explanation']
                ];
            }
            
            $response['questions'][] = [
                'id' => $questionId,
                'title' => $question['title'],
                'description' => $question['description'],
                'context' => $question['context'],
                'leftView' => $question['left_view'],
                'rightView' => $question['right_view'],
                'positions' => $positions
            ];
        }
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['debug'][] = "Fout opgetreden: " . $e->getMessage();
    error_log("Stemwijzer API Error: " . $e->getMessage());
}

// Debug informatie alleen in development
// In productie deze regel verwijderen of aanpassen
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    // Debug informatie behouden
} else {
    // Verwijder debug informatie in productie
    unset($response['debug']);
}

// Stuur de JSON response terug
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 