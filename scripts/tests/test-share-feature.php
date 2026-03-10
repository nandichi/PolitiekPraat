<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

echo "<h1>Test Share Feature</h1>";

$stemwijzerController = new StemwijzerController();

// Test 1: Probeer de database structuur aan te maken/updaten
echo "<h2>Test 1: Database Structuur</h2>";
try {
    // Forceer de addShareIdColumnIfMissing functie
    $reflection = new ReflectionClass($stemwijzerController);
    $method = $reflection->getMethod('addShareIdColumnIfMissing');
    $method->setAccessible(true);
    $method->invoke($stemwijzerController);
    
    echo "✅ addShareIdColumnIfMissing succesvol uitgevoerd<br>";
} catch (Exception $e) {
    echo "❌ Fout bij database structuur update: " . $e->getMessage() . "<br>";
}

// Test 2: Probeer resultaten op te slaan
echo "<h2>Test 2: Resultaten Opslaan</h2>";
try {
    $sessionId = 'test_session_' . time();
    $answers = [0 => 'eens', 1 => 'neutraal', 2 => 'oneens'];
    $results = [
        'VVD' => ['score' => 4, 'total' => 6, 'agreement' => 67],
        'PVV' => ['score' => 2, 'total' => 6, 'agreement' => 33],
        'D66' => ['score' => 3, 'total' => 6, 'agreement' => 50]
    ];
    
    $shareId = $stemwijzerController->saveResults($sessionId, $answers, $results, null);
    
    if ($shareId) {
        echo "✅ Resultaten succesvol opgeslagen!<br>";
        echo "🔑 Share ID: " . htmlspecialchars($shareId) . "<br>";
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $shareUrl = $protocol . $host . '/resultaten/' . $shareId;
        echo "🔗 Share URL: <a href='$shareUrl' target='_blank'>$shareUrl</a><br>";
        
        // Test 3: Probeer resultaten op te halen
        echo "<h2>Test 3: Resultaten Ophalen</h2>";
        $savedResults = $stemwijzerController->getResultsByShareId($shareId);
        
        if ($savedResults) {
            echo "✅ Resultaten succesvol opgehaald!<br>";
            echo "📊 Antwoorden: " . count($savedResults->answers) . "<br>";
            echo "🎯 Resultaten: " . count($savedResults->results) . "<br>";
            echo "📅 Voltooid op: " . $savedResults->completed_at . "<br>";
            
            echo "<h3>Details:</h3>";
            echo "<pre>";
            echo "Antwoorden: " . json_encode($savedResults->answers, JSON_PRETTY_PRINT) . "\n";
            echo "Resultaten: " . json_encode($savedResults->results, JSON_PRETTY_PRINT) . "\n";
            echo "</pre>";
        } else {
            echo "❌ Kon resultaten niet ophalen<br>";
        }
        
    } else {
        echo "❌ Fout bij opslaan van resultaten<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Fout bij test: " . $e->getMessage() . "<br>";
}

// Test 4: Test de API endpoint
echo "<h2>Test 4: API Endpoint Test</h2>";
if (isset($shareId) && $shareId) {
    $apiUrl = '/api/stemwijzer.php?action=results&share_id=' . $shareId;
    echo "📡 API URL: <a href='$apiUrl' target='_blank'>$apiUrl</a><br>";
    echo "🔗 Share URL: <a href='/resultaten/$shareId' target='_blank'>/resultaten/$shareId</a><br>";
}

echo "<h2>Database Schema Info</h2>";
echo "Schema Type: " . $stemwijzerController->getSchemaType() . "<br>";

// Laat de database tabel structuur zien
try {
    $db = new Database();
    $db->query("SHOW TABLES LIKE 'stemwijzer_results'");
    if ($db->single()) {
        echo "✅ stemwijzer_results tabel bestaat<br>";
        
        $db->query("SHOW COLUMNS FROM stemwijzer_results");
        $columns = $db->resultSet();
        
        echo "<h3>Tabel Structuur:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Kolom</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column->Field) . "</td>";
            echo "<td>" . htmlspecialchars($column->Type) . "</td>";
            echo "<td>" . htmlspecialchars($column->Null) . "</td>";
            echo "<td>" . htmlspecialchars($column->Key) . "</td>";
            echo "<td>" . htmlspecialchars($column->Default ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check share_id kolom specifiek
        $hasShareId = false;
        foreach ($columns as $column) {
            if ($column->Field === 'share_id') {
                $hasShareId = true;
                break;
            }
        }
        
        if ($hasShareId) {
            echo "✅ share_id kolom bestaat<br>";
        } else {
            echo "❌ share_id kolom ontbreekt<br>";
        }
        
    } else {
        echo "❌ stemwijzer_results tabel bestaat niet<br>";
    }
} catch (Exception $e) {
    echo "❌ Fout bij database schema check: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/stemwijzer'>← Terug naar Stemwijzer</a>";
?> 