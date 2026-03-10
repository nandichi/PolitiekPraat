<?php
require_once 'includes/ChatGPTAPI.php';

echo "=== OpenAI API Configuratie Test ===\n\n";

try {
    // Test de ChatGPT API instantie
    echo "1. ChatGPT API instantie maken...\n";
    $chatGPT = new ChatGPTAPI();
    echo "   ✓ API instantie succesvol aangemaakt\n\n";
    
    // Test de verbinding
    echo "2. API verbinding testen...\n";
    $result = $chatGPT->testConnection();
    
    if ($result['success']) {
        echo "   ✓ API verbinding succesvol!\n";
        echo "   Antwoord: " . $result['content'] . "\n\n";
    } else {
        echo "   ✗ API verbinding gefaald\n";
        echo "   Fout: " . $result['error'] . "\n\n";
        if (isset($result['response'])) {
            echo "   Response: " . $result['response'] . "\n\n";
        }
    }
    
    echo "=== Test Voltooid ===\n";
    
} catch (Exception $e) {
    echo "   ✗ Fout bij instantie maken: " . $e->getMessage() . "\n\n";
    echo "Controleer of de API key correct is geconfigureerd:\n";
    echo "- .env bestand met OPENAI_API_KEY\n";
    echo "- Environment variabele OPENAI_API_KEY\n";
    echo "- config/api_keys.php bestand\n\n";
    echo "Zie OPENAI_API_SETUP.md voor gedetailleerde instructies.\n";
}
?> 