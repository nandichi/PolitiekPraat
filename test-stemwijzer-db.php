<?php
// Test script voor stemwijzer database
require_once 'includes/config.php';
require_once 'includes/Database.php';

echo "<h1>Stemwijzer Database Test</h1>\n";

// Test database connectie
echo "<h2>Database Connectie Test</h2>\n";
try {
    $db = new Database();
    echo "✅ Database connectie succesvol<br>\n";
    
    // Test welke omgeving
    $is_production = ($_SERVER['HTTP_HOST'] ?? 'localhost') === 'politiekpraat.nl';
    echo "Omgeving: " . ($is_production ? 'Production' : 'Development') . "<br>\n";
    echo "Database: " . DB_NAME . "<br>\n";
    echo "Host: " . DB_HOST . "<br>\n";
    
} catch (Exception $e) {
    echo "❌ Database connectie gefaald: " . $e->getMessage() . "<br>\n";
    exit;
}

// Test welke tabellen bestaan
echo "<h2>Bestaande Tabellen</h2>\n";
try {
    $db->query("SHOW TABLES");
    $tables = $db->resultSet();
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "📋 $tableName<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Kon tabellen niet ophalen: " . $e->getMessage() . "<br>\n";
}

// Test specifieke stemwijzer tabellen
echo "<h2>Stemwijzer Tabellen Test</h2>\n";

$stemwijzerTables = [
    'stemwijzer_questions',
    'stemwijzer_parties', 
    'stemwijzer_positions',
    'stemwijzer_results',
    'questions', // oude schema
    'parties',   // oude schema
    'positions'  // oude schema
];

foreach ($stemwijzerTables as $table) {
    try {
        $db->query("SHOW TABLES LIKE '$table'");
        $exists = $db->single();
        
        if ($exists) {
            echo "✅ Tabel '$table' bestaat<br>\n";
            
            // Tel records
            $db->query("SELECT COUNT(*) as count FROM $table");
            $count = $db->single();
            echo "&nbsp;&nbsp;&nbsp;→ $count->count records<br>\n";
            
            // Toon kolommen
            $db->query("SHOW COLUMNS FROM $table");
            $columns = $db->resultSet();
            echo "&nbsp;&nbsp;&nbsp;→ Kolommen: ";
            $columnNames = array_map(function($col) { return $col->Field; }, $columns);
            echo implode(', ', $columnNames) . "<br>\n";
            
        } else {
            echo "❌ Tabel '$table' bestaat niet<br>\n";
        }
    } catch (Exception $e) {
        echo "❌ Fout bij checken van tabel '$table': " . $e->getMessage() . "<br>\n";
    }
}

// Test StemwijzerController
echo "<h2>StemwijzerController Test</h2>\n";
try {
    require_once 'includes/StemwijzerController.php';
    $controller = new StemwijzerController();
    
    echo "✅ StemwijzerController geladen<br>\n";
    echo "Schema type: " . $controller->getSchemaType() . "<br>\n";
    
    // Test vragen ophalen
    $questions = $controller->getQuestions();
    echo "Aantal vragen: " . count($questions) . "<br>\n";
    
    // Test partijen ophalen
    $parties = $controller->getParties();
    echo "Aantal partijen: " . count($parties) . "<br>\n";
    
    // Test volledige data
    $data = $controller->getStemwijzerData();
    echo "Stemwijzer data succesvol opgehaald<br>\n";
    echo "Aantal vragen in data: " . count($data['questions']) . "<br>\n";
    echo "Aantal partijen in data: " . count($data['parties']) . "<br>\n";
    echo "Aantal party logos: " . count($data['partyLogos']) . "<br>\n";
    
} catch (Exception $e) {
    echo "❌ StemwijzerController fout: " . $e->getMessage() . "<br>\n";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>\n";
}

echo "<h2>Conclusie</h2>\n";
echo "Ga naar /test-stemwijzer-db.php om dit script uit te voeren<br>\n";
?> 