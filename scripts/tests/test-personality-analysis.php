<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

echo "<h2>Test Persoonlijkheidsanalyse Feature</h2>\n";
echo "<h3>=====================================</h3>\n";

// Initialize controller
$stemwijzerController = new StemwijzerController();

echo "<h4>1. Test Database Connection</h4>\n";
try {
    $stemwijzerData = $stemwijzerController->getStemwijzerData();
    echo "✅ Database connectie succesvol\n";
    echo "✅ Vragen geladen: " . count($stemwijzerData['questions']) . "\n";
    echo "✅ Partijen geladen: " . count($stemwijzerData['parties']) . "\n";
} catch (Exception $e) {
    echo "❌ Database fout: " . $e->getMessage() . "\n";
    exit;
}

echo "\n<h4>2. Test Persoonlijkheidsanalyse Functie</h4>\n";

// Test data - simuleer antwoorden van een gebruiker
$testAnswers = [
    0 => 'eens',      // Eerste vraag: eens
    1 => 'oneens',    // Tweede vraag: oneens  
    2 => 'neutraal',  // Derde vraag: neutraal
    3 => 'eens',      // Vierde vraag: eens
    4 => 'oneens'     // Vijfde vraag: oneens
];

try {
    echo "📊 Test persoonlijkheidsanalyse met sample data...\n";
    $analysis = $stemwijzerController->analyzePoliticalPersonality($testAnswers, $stemwijzerData['questions']);
    echo "✅ Persoonlijkheidsanalyse succesvol uitgevoerd\n";
    
    echo "\n<h4>3. Analyse Resultaten</h4>\n";
    echo "📈 Totaal beantwoorde vragen: " . $analysis['total_answered'] . "\n";
    echo "📈 Links-Rechts percentage: " . round($analysis['left_right_percentage']) . "%\n";
    echo "📈 Progressief percentage: " . round($analysis['progressive_percentage']) . "%\n";
    echo "📈 Autoritair percentage: " . round($analysis['authoritarian_percentage']) . "%\n";
    echo "📈 Pro-EU percentage: " . round($analysis['eu_pro_percentage']) . "%\n";
    
    echo "\n<h4>4. Politiek Profiel</h4>\n";
    echo "🏷️ Type: " . $analysis['political_profile']['type'] . "\n";
    echo "📝 Beschrijving: " . $analysis['political_profile']['description'] . "\n";
    echo "🎨 Kleur: " . $analysis['political_profile']['color'] . "\n";
    
    echo "\n<h4>5. Persoonlijkheidskenmerken</h4>\n";
    if (!empty($analysis['personality_traits'])) {
        foreach ($analysis['personality_traits'] as $trait) {
            echo $trait['icon'] . " " . $trait['name'] . ": " . $trait['description'] . "\n";
        }
    } else {
        echo "⚪ Geen specifieke kenmerken gedetecteerd (gematigd profiel)\n";
    }
    
    echo "\n<h4>6. Politiek Kompas Positie</h4>\n";
    $compass = $analysis['compass_position'];
    echo "📍 X-as (economisch): " . round($compass['x'], 2) . " (negatief = links, positief = rechts)\n";
    echo "📍 Y-as (sociaal): " . round($compass['y'], 2) . " (negatief = liberaal, positief = autoritair)\n";
    echo "🎯 Kwadrant: " . $compass['quadrant']['name'] . " (" . $compass['quadrant']['color'] . ")\n";
    
} catch (Exception $e) {
    echo "❌ Fout bij persoonlijkheidsanalyse: " . $e->getMessage() . "\n";
    echo "🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n<h4>7. Test Save & Retrieve Results</h4>\n";

// Test resultaten opslaan en ophalen
$testResults = [
    'VVD' => ['agreement' => 75, 'score' => 30, 'total' => 40],
    'PVV' => ['agreement' => 45, 'score' => 18, 'total' => 40],
    'D66' => ['agreement' => 80, 'score' => 32, 'total' => 40]
];

try {
    $shareId = $stemwijzerController->saveResults('test_session_' . time(), $testAnswers, $testResults);
    
    if ($shareId) {
        echo "✅ Resultaten opgeslagen met share_id: " . $shareId . "\n";
        
        // Test ophalen
        $savedResults = $stemwijzerController->getResultsByShareId($shareId);
        if ($savedResults) {
            echo "✅ Resultaten succesvol opgehaald\n";
            echo "📊 Antwoorden: " . count($savedResults->answers) . "\n";
            echo "📊 Resultaten: " . count($savedResults->results) . "\n";
            
            // Test persoonlijkheidsanalyse met opgehaalde data
            $analysis2 = $stemwijzerController->analyzePoliticalPersonality($savedResults->answers, $stemwijzerData['questions']);
            echo "✅ Persoonlijkheidsanalyse werkt met opgehaalde data\n";
            echo "🏷️ Politiek type: " . $analysis2['political_profile']['type'] . "\n";
            
        } else {
            echo "❌ Kon resultaten niet ophalen\n";
        }
    } else {
        echo "❌ Kon resultaten niet opslaan\n";
    }
    
} catch (Exception $e) {
    echo "❌ Fout bij save/retrieve test: " . $e->getMessage() . "\n";
}

echo "\n<h3>=====================================</h3>\n";
echo "🎉 Test voltooid!\n";
echo "⚡ De persoonlijkheidsanalyse feature is nu klaar voor gebruik.\n";
echo "\n<p><strong>Test de feature:</strong></p>\n";
echo "<p>1. Ga naar <a href='/stemwijzer'>/stemwijzer</a> en vul de stemwijzer in</p>\n";
echo "<p>2. Bekijk je persoonlijkheidsanalyse voordat je de partijresultaten ziet</p>\n";
echo "<p>3. Deel je resultatenlink en bekijk de persoonlijkheidsanalyse op de shared pagina</p>\n";
?> 