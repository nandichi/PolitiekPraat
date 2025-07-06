<?php
// Test script om te verifiëren dat cronjob scripts correct werken met absolute paden
// Dit script test of alle includes kunnen worden geladen zonder padfouten

echo "🧪 Testing PolitiekPraat Cron Job Path Resolution\n";
echo "====================================================\n\n";

// Test functie om een script te testen
function testScript($scriptPath, $scriptName) {
    echo "Test: $scriptName\n";
    echo "Script pad: $scriptPath\n";
    
    if (!file_exists($scriptPath)) {
        echo "❌ Script bestand niet gevonden!\n\n";
        return false;
    }
    
    // Test of het script kan worden geladen zonder fatale fouten
    $output = [];
    $returnCode = 0;
    
    // Voer het script uit in test mode
    exec("php -l $scriptPath 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Script syntax is correct\n";
        
        // Test of includes kunnen worden geladen
        ob_start();
        $oldErrorReporting = error_reporting(E_ERROR | E_PARSE);
        
        try {
            // Probeer het script te includen om te testen of alle includes werken
            $testOutput = shell_exec("php -r 'error_reporting(E_ERROR | E_PARSE); include \"$scriptPath\"; echo \"INCLUDES_OK\";' 2>&1");
            
            if (strpos($testOutput, 'INCLUDES_OK') !== false || strpos($testOutput, 'Exit') !== false) {
                echo "✅ Alle includes kunnen worden geladen\n";
                $success = true;
            } else {
                echo "❌ Include probleem gedetecteerd:\n";
                echo "   " . trim($testOutput) . "\n";
                $success = false;
            }
        } catch (Exception $e) {
            echo "❌ Script uitvoering fout: " . $e->getMessage() . "\n";
            $success = false;
        }
        
        error_reporting($oldErrorReporting);
        ob_end_clean();
        
    } else {
        echo "❌ Script syntax fout:\n";
        foreach ($output as $line) {
            echo "   $line\n";
        }
        $success = false;
    }
    
    echo "\n";
    return $success;
}

// Test alle cronjob scripts
$scripts = [
    'auto_news_scraper.php' => 'Auto News Scraper',
    'auto_likes_cron.php' => 'Auto Likes Cron',
    'test_cron_email.php' => 'Test Cron Email',
    'run_news_scraper.php' => 'Run News Scraper'
];

$testResults = [];
$scriptsDir = dirname(__FILE__);

foreach ($scripts as $scriptFile => $scriptName) {
    $scriptPath = $scriptsDir . '/' . $scriptFile;
    $testResults[$scriptName] = testScript($scriptPath, $scriptName);
}

// Toon samenvatting
echo "===============================================\n";
echo "📊 Test Resultaten Samenvatting:\n";
echo "===============================================\n";

$totalTests = count($testResults);
$passedTests = 0;

foreach ($testResults as $scriptName => $result) {
    echo sprintf("%-20s: %s\n", $scriptName, $result ? "✅ Geslaagd" : "❌ Mislukt");
    if ($result) $passedTests++;
}

echo "\nTotaal: $passedTests/$totalTests tests geslaagd\n";

if ($passedTests === $totalTests) {
    echo "\n🎉 Alle cronjob scripts zijn correct geconfigureerd!\n";
    echo "De padproblemen zijn opgelost en de scripts kunnen nu worden uitgevoerd door cronjobs.\n";
    echo "\n📋 Volgende stappen:\n";
    echo "1. Test een van de scripts handmatig vanaf de command line\n";
    echo "2. Configureer je cronjobs om de scripts uit te voeren\n";
    echo "3. Controleer de log bestanden voor succesvolle uitvoering\n";
} else {
    echo "\n⚠️  Sommige scripts hebben nog steeds problemen.\n";
    echo "Controleer de foutmeldingen hierboven en los deze op voordat je de cronjobs configureert.\n";
}

echo "\n✅ Path resolution test completed!\n";
?> 