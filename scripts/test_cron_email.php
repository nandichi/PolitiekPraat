<?php
// Test script voor cron job email functionaliteit
// Gebruik dit om te testen of de email configuratie werkt

// Zet working directory naar project root voor correcte includes
$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);
chdir($projectRoot);

require_once 'includes/config.php';
require_once 'includes/mail_helper.php';

echo "🧪 Testing PolitiekPraat Cron Job Email Functionaliteit\n";
echo "========================================================\n\n";

// Test 1: Basis test email
echo "Test 1: Verzenden van basis test email...\n";
$testResult = sendTestCronEmail();

if ($testResult) {
    echo "✅ Test email succesvol verstuurd!\n";
} else {
    echo "❌ Test email kon niet worden verstuurd.\n";
}

echo "\n";

// Test 2: Success email
echo "Test 2: Verzenden van success email...\n";
$successResult = sendCronJobEmail(
    'Test Success Job',
    'success',
    'Dit is een test van een succesvolle cron job.',
    "Test details:\n- Alles werkt correct\n- Geen fouten gevonden\n- Performance: OK",
    ''
);

if ($successResult) {
    echo "✅ Success email succesvol verstuurd!\n";
} else {
    echo "❌ Success email kon niet worden verstuurd.\n";
}

echo "\n";

// Test 3: Warning email
echo "Test 3: Verzenden van warning email...\n";
$warningResult = sendCronJobEmail(
    'Test Warning Job',
    'warning',
    'Dit is een test van een cron job met waarschuwingen.',
    "Test details:\n- Job voltooid maar met waarschuwingen\n- Enkele bronnen niet beschikbaar\n- Performance: Traag",
    ''
);

if ($warningResult) {
    echo "✅ Warning email succesvol verstuurd!\n";
} else {
    echo "❌ Warning email kon niet worden verstuurd.\n";
}

echo "\n";

// Test 4: Error email
echo "Test 4: Verzenden van error email...\n";
$errorResult = sendCronJobEmail(
    'Test Error Job',
    'error',
    'Dit is een test van een gefaalde cron job.',
    "Test details:\n- FOUT: Database connectie mislukt\n- Stack trace: Test stack trace\n- Tijd: " . date('Y-m-d H:i:s'),
    ''
);

if ($errorResult) {
    echo "✅ Error email succesvol verstuurd!\n";
} else {
    echo "❌ Error email kon niet worden verstuurd.\n";
}

echo "\n";

// Test 5: Email met log bestand
echo "Test 5: Verzenden van email met log inhoud...\n";
$testLogFile = 'logs/test_log.txt';
file_put_contents($testLogFile, "[" . date('Y-m-d H:i:s') . "] Test log entry 1\n");
file_put_contents($testLogFile, "[" . date('Y-m-d H:i:s') . "] Test log entry 2\n", FILE_APPEND);
file_put_contents($testLogFile, "[" . date('Y-m-d H:i:s') . "] Test log entry 3\n", FILE_APPEND);

$logResult = sendCronJobEmail(
    'Test Log Job',
    'success',
    'Dit is een test van een cron job met log inhoud.',
    "Test details:\n- Log bestand toegevoegd\n- Recente entries worden getoond",
    $testLogFile
);

if ($logResult) {
    echo "✅ Log email succesvol verstuurd!\n";
} else {
    echo "❌ Log email kon niet worden verstuurd.\n";
}

// Cleanup test log
unlink($testLogFile);

echo "\n";

// Samenvatting
echo "==========================================\n";
echo "📊 Test Resultaten Samenvatting:\n";
echo "==========================================\n";
echo "Basis test email:    " . ($testResult ? "✅ Success" : "❌ Failed") . "\n";
echo "Success email:       " . ($successResult ? "✅ Success" : "❌ Failed") . "\n";
echo "Warning email:       " . ($warningResult ? "✅ Success" : "❌ Failed") . "\n";
echo "Error email:         " . ($errorResult ? "✅ Success" : "❌ Failed") . "\n";
echo "Log email:           " . ($logResult ? "✅ Success" : "❌ Failed") . "\n";

$totalTests = 5;
$passedTests = ($testResult ? 1 : 0) + ($successResult ? 1 : 0) + ($warningResult ? 1 : 0) + ($errorResult ? 1 : 0) + ($logResult ? 1 : 0);

echo "\nTotaal: $passedTests/$totalTests tests geslaagd\n";

if ($passedTests === $totalTests) {
    echo "🎉 Alle tests zijn geslaagd! Email functionaliteit werkt correct.\n";
    echo "Je zou nu 5 test emails moeten hebben ontvangen op: naoufal.exe14@gmail.com\n";
} else {
    echo "⚠️  Sommige tests zijn mislukt. Controleer je email configuratie.\n";
    echo "Controleer de volgende instellingen:\n";
    echo "- SMTP server instellingen in mail_config.php\n";
    echo "- PHPMailer installatie\n";
    echo "- Internet connectiviteit\n";
    echo "- Email server bereikbaarheid\n";
}

echo "\n";

// Toon configuratie info
echo "📧 Email Configuratie Info:\n";
echo "==========================================\n";
$mailConfig = include 'includes/mail_config.php';
echo "SMTP Host: " . $mailConfig['smtp_host'] . "\n";
echo "SMTP Port: " . $mailConfig['smtp_port'] . "\n";
echo "SMTP Username: " . $mailConfig['smtp_username'] . "\n";
echo "SMTP Security: " . $mailConfig['smtp_secure'] . "\n";
echo "From Email: " . $mailConfig['from_email'] . "\n";
echo "From Name: " . $mailConfig['from_name'] . "\n";
echo "Target Email: naoufal.exe14@gmail.com\n";

echo "\n✅ Email test completed!\n";
?> 