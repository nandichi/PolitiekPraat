<?php
// Debug script voor stemwijzer API
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

header('Content-Type: application/json');

try {
    $controller = new StemwijzerController();
    
    echo "=== STEMWIJZER DEBUG API ===\n";
    echo "Schema type: " . $controller->getSchemaType() . "\n";
    
    // Test vragen
    $questions = $controller->getQuestions();
    echo "Questions found: " . count($questions) . "\n";
    
    if (!empty($questions)) {
        echo "First question: " . json_encode($questions[0], JSON_PRETTY_PRINT) . "\n";
    }
    
    // Test partijen
    $parties = $controller->getParties();
    echo "Parties found: " . count($parties) . "\n";
    
    if (!empty($parties)) {
        echo "First party: " . json_encode($parties[0], JSON_PRETTY_PRINT) . "\n";
    }
    
    // Test posities voor vraag 1
    if (!empty($questions)) {
        $firstQuestionId = $questions[0]->id;
        $positions = $controller->getPositionsForQuestion($firstQuestionId);
        echo "Positions for question {$firstQuestionId}: " . count($positions['positions']) . "\n";
        echo "Sample positions: " . json_encode(array_slice($positions['positions'], 0, 3, true), JSON_PRETTY_PRINT) . "\n";
    }
    
    // Test volledige data
    $data = $controller->getStemwijzerData();
    echo "Full data questions: " . count($data['questions']) . "\n";
    echo "Full data parties: " . count($data['parties']) . "\n";
    echo "Full data party logos: " . count($data['partyLogos']) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 