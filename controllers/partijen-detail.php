<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/party_color_helpers.php';
require_once __DIR__ . '/../models/PartyModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$partyKey = isset($_GET['party']) ? $_GET['party'] : '';

// Probeer eerst exact, dan case-insensitive via uppercase variant
$partyModel = new PartyModel();
$party = $partyModel->getParty($partyKey);

if (!$party) {
    // Probeer alternatieve schrijfwijzes
    $allParties = $partyModel->getAllParties();
    foreach ($allParties as $candidateKey => $candidateParty) {
        if (strtolower($candidateKey) === strtolower($partyKey)) {
            $partyKey = $candidateKey;
            $party = $candidateParty;
            break;
        }
        $slugVariant = strtolower(str_replace(['/', ' '], '-', $candidateKey));
        if ($slugVariant === strtolower($partyKey)) {
            $partyKey = $candidateKey;
            $party = $candidateParty;
            break;
        }
    }
}

if (!$party) {
    header('Location: ' . URLROOT . '/partijen');
    exit;
}

$partyColor = getPartyColor($partyKey);

$pageTitle = ($party['name'] ?? $partyKey) . ' - ' . ($party['leader'] ?? '');
$pageDescription = 'Profiel van ' . ($party['name'] ?? $partyKey) . ' op PolitiekPraat: lijsttrekker, zetels, standpunten en peilingen.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

require_once BASE_PATH . '/views/partijen/detail.php';
