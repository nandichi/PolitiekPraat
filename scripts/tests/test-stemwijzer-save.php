<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

echo "=== STEMWIJZER RESULTATEN OPSLAG TEST ===\n\n";

try {
    // Test database connectie
    $db = new Database();
    echo "✅ Database connectie succesvol\n";
    
    // Test StemwijzerController
    $controller = new StemwijzerController();
    echo "✅ StemwijzerController geladen\n";
    echo "Schema type: " . $controller->getSchemaType() . "\n\n";
    
    // Controleer of stemwijzer_results tabel bestaat
    $db->query("SHOW TABLES LIKE 'stemwijzer_results'");
    $tableExists = $db->single();
    
    if ($tableExists) {
        echo "✅ Tabel 'stemwijzer_results' bestaat\n";
        
        // Toon tabel structuur
        $db->query("DESCRIBE stemwijzer_results");
        $columns = $db->resultSet();
        echo "Kolommen:\n";
        foreach ($columns as $column) {
            echo "  - {$column->Field} ({$column->Type})\n";
        }
        echo "\n";
        
        // Tel bestaande records
        $db->query("SELECT COUNT(*) as count FROM stemwijzer_results");
        $count = $db->single();
        echo "Aantal bestaande records: {$count->count}\n\n";
        
    } else {
        echo "❌ Tabel 'stemwijzer_results' bestaat niet\n";
        echo "De tabel zal automatisch worden aangemaakt bij de eerste opslag\n\n";
    }
    
    // Test het opslaan van resultaten
    echo "=== TEST RESULTATEN OPSLAAN ===\n";
    
    $testSessionId = 'test_session_' . time();
    $testAnswers = [
        0 => 'eens',
        1 => 'oneens', 
        2 => 'neutraal',
        3 => 'eens'
    ];
    $testResults = [
        'PVV' => ['score' => 4, 'total' => 8, 'agreement' => 50],
        'VVD' => ['score' => 6, 'total' => 8, 'agreement' => 75],
        'D66' => ['score' => 2, 'total' => 8, 'agreement' => 25]
    ];
    
    echo "Test data:\n";
    echo "- Session ID: $testSessionId\n";
    echo "- Antwoorden: " . count($testAnswers) . " stuks\n";
    echo "- Resultaten: " . count($testResults) . " partijen\n\n";
    
    $saved = $controller->saveResults($testSessionId, $testAnswers, $testResults, null);
    
    if ($saved) {
        echo "✅ Test resultaten succesvol opgeslagen!\n";
        
        // Controleer of het record daadwerkelijk is opgeslagen
        $db->query("SELECT * FROM stemwijzer_results WHERE session_id = :session_id ORDER BY id DESC LIMIT 1");
        $db->bind(':session_id', $testSessionId);
        $savedRecord = $db->single();
        
        if ($savedRecord) {
            echo "✅ Record gevonden in database:\n";
            echo "  - ID: {$savedRecord->id}\n";
            echo "  - Session ID: {$savedRecord->session_id}\n";
            echo "  - Completed at: {$savedRecord->completed_at}\n";
            echo "  - IP Address: {$savedRecord->ip_address}\n";
            
            $savedAnswers = json_decode($savedRecord->answers, true);
            $savedResults = json_decode($savedRecord->results, true);
            
            echo "  - Antwoorden in database: " . count($savedAnswers) . " stuks\n";
            echo "  - Resultaten in database: " . count($savedResults) . " partijen\n";
            
            // Cleanup - verwijder test record
            $db->query("DELETE FROM stemwijzer_results WHERE id = :id");
            $db->bind(':id', $savedRecord->id);
            if ($db->execute()) {
                echo "✅ Test record opgeruimd\n";
            }
        } else {
            echo "❌ Record niet gevonden in database ondanks success\n";
        }
        
    } else {
        echo "❌ Test resultaten NIET opgeslagen\n";
        echo "Controleer de error logs voor meer details\n";
    }
    
    echo "\n=== TEST STATISTIEKEN ===\n";
    $stats = $controller->getStatistics();
    echo "Statistieken:\n";
    foreach ($stats as $key => $value) {
        echo "  - $key: $value\n";
    }
    
} catch (Exception $e) {
    echo "❌ FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST VOLTOOID ===\n";
echo "Controleer de error logs voor eventuele waarschuwingen of fouten\n";
?> 