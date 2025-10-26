<?php
// Politiek Gesprek - AI Stemwijzer Chatbot Controller

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

// Check of gebruiker is ingelogd (optioneel)
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

// Haal actieve partijen op voor referentie
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("
    SELECT id, name, short_name, logo_url
    FROM stemwijzer_parties
    WHERE is_active = 1
    ORDER BY name
");
$stmt->execute();
$parties = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check of gebruiker een actieve sessie heeft
$activeSession = null;
if ($userId) {
    $stmt = $conn->prepare("
        SELECT session_id, current_question_index, started_at
        FROM politiek_gesprek_sessions
        WHERE user_id = ? AND completed_at IS NULL
        ORDER BY started_at DESC
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $activeSession = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Set page metadata
$pageTitle = "Politiek Gesprek - Ontdek jouw politieke match";
$pageDescription = "Voer een diepgaand gesprek met onze AI over Nederlandse politiek en ontdek welke partij het beste bij jou past. 20 intelligente vragen die zich aanpassen aan jouw antwoorden.";
$pageKeywords = "stemwijzer, politiek, ai chatbot, verkiezingen, partijen, nederland, stemadvies";

// Load the view
require_once __DIR__ . '/../views/politiek-gesprek.php';

