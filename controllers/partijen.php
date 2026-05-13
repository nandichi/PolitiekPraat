<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/party_color_helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/PartyModel.php';

$partyModel = new PartyModel();
$parties = $partyModel->getAllParties();
$parties_json = json_encode($parties, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($parties_json === false) {
    $parties_json = '{}';
}

// Sorteer op huidige zetels
$partiesSorted = $parties;
uasort($partiesSorted, function ($a, $b) {
    return ((int) ($b['current_seats'] ?? 0)) - ((int) ($a['current_seats'] ?? 0));
});

// Top peilingen
$partiesByPolling = $parties;
uasort($partiesByPolling, function ($a, $b) {
    return ((int) ($b['polling']['seats'] ?? 0)) - ((int) ($a['polling']['seats'] ?? 0));
});

$totalSeats = array_sum(array_map(function ($p) {
    return (int) ($p['current_seats'] ?? 0);
}, $parties));

$totalPollingSeats = array_sum(array_map(function ($p) {
    return (int) ($p['polling']['seats'] ?? 0);
}, $parties));

$maxSeats = max(array_map(function ($p) {
    return (int) ($p['current_seats'] ?? 0);
}, $parties)) ?: 1;

$maxPolling = max(array_map(function ($p) {
    return (int) ($p['polling']['seats'] ?? 0);
}, $parties)) ?: 1;

$pageTitle = 'Nederlandse politieke partijen';
$pageDescription = 'Een rustig overzicht van alle Nederlandse politieke partijen, hun lijsttrekkers, zetels en standpunten.';
$data = ['title' => $pageTitle . ' - PolitiekPraat', 'description' => $pageDescription];

require_once BASE_PATH . '/views/partijen/index.php';
