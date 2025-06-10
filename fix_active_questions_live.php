<?php
/**
 * Script om alle vragen actief te maken op de live server
 * Upload dit naar de live server en voer het uit via browser
 */

// Alleen uitvoeren via browser voor veiligheid
if (php_sapi_name() === 'cli') {
    die("Dit script kan alleen via browser worden uitgevoerd.\n");
}

echo "<h1>Stemwijzer Vragen Activatie Script</h1>";
echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";

try {
    require_once 'includes/config.php';
    require_once 'includes/Database.php';
    
    $db = new Database();
    echo "<p>✅ Database connectie succesvol</p>";
    
    // Check huidige status
    $db->query('SELECT COUNT(*) as total FROM stemwijzer_questions');
    $total = $db->single();
    
    $db->query('SELECT COUNT(*) as active FROM stemwijzer_questions WHERE is_active = 1');
    $active = $db->single();
    
    echo "<h2>Huidige Status</h2>";
    echo "<p>Totaal aantal vragen: <strong>" . $total->total . "</strong></p>";
    echo "<p>Actieve vragen: <strong>" . $active->active . "</strong></p>";
    
    if ($active->active < $total->total) {
        echo "<h2>Vragen Activeren</h2>";
        echo "<p>🔄 Alle vragen worden geactiveerd...</p>";
        
        $db->query('UPDATE stemwijzer_questions SET is_active = 1 WHERE is_active != 1');
        $updated = $db->execute();
        
        if ($updated) {
            // Check opnieuw
            $db->query('SELECT COUNT(*) as active FROM stemwijzer_questions WHERE is_active = 1');
            $newActive = $db->single();
            
            echo "<p>✅ Update succesvol! Actieve vragen na update: <strong>" . $newActive->active . "</strong></p>";
        } else {
            echo "<p>❌ Update gefaald</p>";
        }
    } else {
        echo "<p>✅ Alle vragen zijn al actief - geen actie nodig</p>";
    }
    
    // Test StemwijzerController
    echo "<h2>StemwijzerController Test</h2>";
    require_once 'includes/StemwijzerController.php';
    $controller = new StemwijzerController();
    
    $data = $controller->getStemwijzerData();
    echo "<p>Schema type: " . $controller->getSchemaType() . "</p>";
    echo "<p>Vragen geladen: " . count($data['questions']) . "</p>";
    echo "<p>Partijen geladen: " . count($data['parties']) . "</p>";
    echo "<p>Party logos: " . count($data['partyLogos']) . "</p>";
    
    if (count($data['questions']) > 0) {
        echo "<p>✅ Stemwijzer data is succesvol geladen!</p>";
        echo "<p><strong>De stemwijzer zou nu moeten werken.</strong></p>";
    } else {
        echo "<p>❌ Er zijn nog steeds geen vragen geladen. Er is een ander probleem.</p>";
    }
    
    echo "<h2>Directe API Test</h2>";
    echo "<p>Test de API direct: <a href='/api/stemwijzer.php?action=data' target='_blank'>/api/stemwijzer.php?action=data</a></p>";
    echo "<p>Debug API: <a href='/api/stemwijzer.php?action=debug' target='_blank'>/api/stemwijzer.php?action=debug</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>FOUT:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><em>Dit script kan veilig meerdere keren worden uitgevoerd.</em></p>";
echo "<p><strong>Vervolgstappen:</strong></p>";
echo "<ol>";
echo "<li>Ga naar de stemwijzer pagina en controleer of het werkt</li>";
echo "<li>Open browser console (F12) voor debug informatie</li>";
echo "<li>Test de API endpoints hierboven</li>";
echo "</ol>";
?> 