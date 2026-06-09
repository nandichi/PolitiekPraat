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

// MySQL matcht party_key hoofdletterongevoelig, dus een URL als /partijen/d66
// levert wel de juiste rij op, maar laat $partyKey lowercase. De redactionele
// profielen en kleuren gebruiken de canonieke schrijfwijze (D66, GL-PvdA,
// 50PLUS, FvD). Normaliseer daarom naar de exacte sleutel uit de databron.
if (!empty($party['party_key'])) {
    $partyKey = $party['party_key'];
}

$partyColor = getPartyColor($partyKey);

// Redactioneel profiel (versiebeheerd) en standpunt per thema.
$profileData = require BASE_PATH . '/includes/data/partijen_profiel.php';
$profile = $profileData[$partyKey] ?? null;

$partySlug = strtolower($partyKey);
$themas = require BASE_PATH . '/includes/data/themas.php';
$standpuntenAll = require BASE_PATH . '/includes/data/standpunten.php';

$partyThemePositions = [];
foreach ($themas as $themaSlug => $thema) {
    $tekst = $standpuntenAll[$themaSlug][$partySlug] ?? null;
    if (!$tekst) {
        continue;
    }
    $partyThemePositions[] = [
        'slug'     => $themaSlug,
        'title'    => $thema['title'] ?? ucfirst(str_replace('-', ' ', $themaSlug)),
        'icon'     => $thema['icon'] ?? 'circle',
        'category' => $thema['category'] ?? '',
        'tekst'    => $tekst,
    ];
}

$pageTitle = ($party['name'] ?? $partyKey) . ' - standpunten, leider en zetels';
$pageDescription = 'Uitgebreid profiel van ' . ($party['name'] ?? $partyKey)
    . ' op PolitiekPraat: geschiedenis, stroming, lijsttrekker, zetelhistorie, kritiek en standpunt per thema.';
$data = ['title' => $pageTitle . ' - PolitiekPraat', 'description' => $pageDescription];

require_once BASE_PATH . '/views/partijen/detail.php';
