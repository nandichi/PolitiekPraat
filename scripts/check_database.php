<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';
require_once dirname(__DIR__) . '/includes/StemwijzerController.php';

echo "=== STEMWIJZER DATABASE DIAGNOSTIEK ===\n\n";

try {
    $db = new Database();
    echo "✓ Database connectie succesvol\n\n";
    
    // Test StemwijzerController
    try {
        $stemwijzerController = new StemwijzerController();
        echo "✓ StemwijzerController succesvol geladen\n";
        echo "Gedetecteerd schema type: " . $stemwijzerController->getSchemaType() . "\n\n";
        
        // Test getStemwijzerData method
        try {
            $stemwijzerData = $stemwijzerController->getStemwijzerData();
            echo "✓ getStemwijzerData() succesvol uitgevoerd\n";
            echo "Aantal questions geladen: " . count($stemwijzerData['questions']) . "\n";
            echo "Aantal parties geladen: " . count($stemwijzerData['parties']) . "\n";
            echo "Aantal party logos: " . count($stemwijzerData['partyLogos']) . "\n";
            echo "Schema type in data: " . ($stemwijzerData['schema_type'] ?? 'niet gevonden') . "\n\n";
        } catch (Exception $e) {
            echo "✗ FOUT in getStemwijzerData(): " . $e->getMessage() . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "✗ FOUT bij laden StemwijzerController: " . $e->getMessage() . "\n\n";
    }
    
    // Check tabellen bestaan
    echo "=== TABELLEN CHECK ===\n";
    $tables = ['stemwijzer_questions', 'stemwijzer_parties', 'stemwijzer_positions', 'stemwijzer_results'];
    foreach ($tables as $table) {
        try {
            $db->query("SHOW TABLES LIKE '$table'");
            $result = $db->single();
            if ($result) {
                echo "✓ Tabel $table bestaat\n";
            } else {
                echo "✗ Tabel $table bestaat NIET\n";
            }
        } catch (Exception $e) {
            echo "✗ Fout bij checken tabel $table: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // Check aantal records per tabel
    echo "=== RECORD AANTALLEN ===\n";
    foreach ($tables as $table) {
        try {
            $db->query("SELECT COUNT(*) as count FROM $table");
            $count = $db->single();
            echo "$table: " . $count->count . " records\n";
        } catch (Exception $e) {
            echo "$table: FOUT - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // Check of vragen actief zijn
    echo "=== ACTIEVE VRAGEN ===\n";
    try {
        $db->query('SELECT COUNT(*) as count FROM stemwijzer_questions WHERE is_active = 1');
        $activeCount = $db->single();
        echo "Aantal actieve vragen: " . $activeCount->count . "\n";
        
        $db->query('SELECT COUNT(*) as count FROM stemwijzer_questions WHERE is_active = 0');
        $inactiveCount = $db->single();
        echo "Aantal inactieve vragen: " . $inactiveCount->count . "\n\n";
    } catch (Exception $e) {
        echo "Fout bij checken actieve vragen: " . $e->getMessage() . "\n\n";
    }
    
    // Toon eerste 5 vragen als test
    echo "=== EERSTE 5 VRAGEN (TEST) ===\n";
    try {
        $db->query('SELECT id, title, order_number, is_active FROM stemwijzer_questions ORDER BY order_number LIMIT 5');
        $questions = $db->resultSet();
        
        if (empty($questions)) {
            echo "Geen vragen gevonden!\n";
        } else {
            foreach ($questions as $question) {
                $activeStatus = $question->is_active ? 'ACTIEF' : 'INACTIEF';
                echo "ID: {$question->id}, Order: {$question->order_number}, Status: {$activeStatus}, Title: {$question->title}\n";
            }
        }
    } catch (Exception $e) {
        echo "Fout bij ophalen vragen: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Check partijen
    echo "=== PARTIJEN CHECK ===\n";
    try {
        $db->query('SELECT id, name, short_name FROM stemwijzer_parties ORDER BY name LIMIT 5');
        $parties = $db->resultSet();
        
        if (empty($parties)) {
            echo "Geen partijen gevonden!\n";
        } else {
            echo "Eerste 5 partijen:\n";
            foreach ($parties as $party) {
                echo "ID: {$party->id}, Naam: {$party->name}, Kort: {$party->short_name}\n";
            }
        }
    } catch (Exception $e) {
        echo "Fout bij ophalen partijen: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Check posities voor eerste vraag
    echo "=== POSITIES CHECK (Eerste vraag) ===\n";
    try {
        $db->query('
            SELECT 
                sp.short_name as party_name,
                spos.position,
                spos.explanation
            FROM stemwijzer_positions spos
            JOIN stemwijzer_parties sp ON spos.party_id = sp.id
            WHERE spos.question_id = 1
            ORDER BY sp.name LIMIT 5
        ');
        $positions = $db->resultSet();
        
        if (empty($positions)) {
            echo "Geen posities gevonden voor vraag 1!\n";
        } else {
            echo "Posities voor vraag 1:\n";
            foreach ($positions as $position) {
                echo "{$position->party_name}: {$position->position}\n";
            }
        }
    } catch (Exception $e) {
        echo "Fout bij ophalen posities: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Test directe query zoals StemwijzerController gebruikt
    echo "=== DIRECTE QUERY TEST ===\n";
    try {
        $db->query("
            SELECT 
                id,
                title,
                description,
                context,
                left_view,
                right_view,
                order_number
            FROM stemwijzer_questions 
            WHERE is_active = 1 
            ORDER BY order_number ASC
            LIMIT 3
        ");
        
        $questions = $db->resultSet();
        echo "Directe query resultaat: " . count($questions) . " vragen gevonden\n";
        
        foreach ($questions as $question) {
            echo "- {$question->title} (Order: {$question->order_number})\n";
        }
        
    } catch (Exception $e) {
        echo "FOUT bij directe query: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    echo "=== DIAGNOSTIEK VOLTOOID ===\n";
    
} catch (Exception $e) {
    echo "✗ FATALE FOUT: Database connectie mislukt: " . $e->getMessage() . "\n";
    
    // Toon config info (zonder wachtwoorden)
    echo "\nConfig diagnostiek:\n";
    echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NIET GEDEFINIEERD') . "\n";
    echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NIET GEDEFINIEERD') . "\n";
    echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'NIET GEDEFINIEERD') . "\n";
    echo "DB_PASS: " . (defined('DB_PASS') ? '[GEDEFINIEERD]' : 'NIET GEDEFINIEERD') . "\n";
}
?> 