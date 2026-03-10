<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/ChatGPTAPI.php';

// Test ChatGPT API
$chatGPT = new ChatGPTAPI();

echo "<h1>ChatGPT API Test voor PolitiekPraat Stemwijzer</h1>";

echo "<h2>1. API Verbinding Test</h2>";
$connectionTest = $chatGPT->testConnection();
if ($connectionTest['success']) {
    echo "<p style='color: green;'>✅ API verbinding succesvol!</p>";
    echo "<p><strong>Response:</strong> " . htmlspecialchars($connectionTest['content']) . "</p>";
} else {
    echo "<p style='color: red;'>❌ API verbinding mislukt!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($connectionTest['error']) . "</p>";
}

echo "<h2>2. Vraag Uitleg Test</h2>";
$questionExplanation = $chatGPT->explainQuestion(
    "Nederland moet een strenger asielbeleid voeren",
    "Het gaat over hoe Nederland omgaat met asielzoekers",
    "Vinden dat Nederland humaan moet blijven",
    "Willen de instroom beperken"
);

if ($questionExplanation['success']) {
    echo "<p style='color: green;'>✅ Vraag uitleg succesvol!</p>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<strong>AI Uitleg:</strong><br>";
    echo nl2br(htmlspecialchars($questionExplanation['content']));
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Vraag uitleg mislukt!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($questionExplanation['error']) . "</p>";
}

echo "<h2>3. Partij Match Test</h2>";
$testAnswers = [
    0 => 'eens',
    1 => 'neutraal', 
    2 => 'oneens'
];

$testQuestions = [
    ['title' => 'Asielbeleid'],
    ['title' => 'Klimaatbeleid'],
    ['title' => 'Economisch beleid']
];

$partyMatch = $chatGPT->explainPartyMatch('VVD', $testAnswers, $testQuestions, 75);

if ($partyMatch['success']) {
    echo "<p style='color: green;'>✅ Partij match uitleg succesvol!</p>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<strong>Waarom VVD bij jou past (75% match):</strong><br>";
    echo nl2br(htmlspecialchars($partyMatch['content']));
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Partij match uitleg mislukt!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($partyMatch['error']) . "</p>";
}

echo "<h2>4. Politiek Advies Test</h2>";
$testPersonality = [
    'political_profile' => [
        'type' => 'Progressief Rechts',
        'description' => 'Je bent economisch liberaal maar sociaal vooruitstrevend.'
    ],
    'left_right_percentage' => 70,
    'progressive_percentage' => 65,
    'authoritarian_percentage' => 40,
    'eu_pro_percentage' => 80
];

$testTopMatches = [
    ['name' => 'VVD', 'agreement' => 75],
    ['name' => 'D66', 'agreement' => 72],
    ['name' => 'Volt', 'agreement' => 68]
];

$politicalAdvice = $chatGPT->generatePoliticalAdvice($testPersonality, $testTopMatches);

if ($politicalAdvice['success']) {
    echo "<p style='color: green;'>✅ Politiek advies succesvol!</p>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<strong>Persoonlijk Stemadvies:</strong><br>";
    echo nl2br(htmlspecialchars($politicalAdvice['content']));
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Politiek advies mislukt!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($politicalAdvice['error']) . "</p>";
}

echo "<h2>API Test Samenvatting</h2>";
$totalTests = 4;
$passedTests = 0;

if ($connectionTest['success']) $passedTests++;
if ($questionExplanation['success']) $passedTests++;
if ($partyMatch['success']) $passedTests++;
if ($politicalAdvice['success']) $passedTests++;

echo "<p><strong>Resultaat:</strong> $passedTests/$totalTests tests geslaagd</p>";

if ($passedTests === $totalTests) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Alle ChatGPT API functies werken correct!</p>";
    echo "<p>De stemwijzer is nu klaar voor AI-aangedreven uitleg en advies.</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ Sommige functies werken niet correct. Controleer de API key en netwerk verbinding.</p>";
}

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2 {
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}

.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
}

.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
}
</style> 