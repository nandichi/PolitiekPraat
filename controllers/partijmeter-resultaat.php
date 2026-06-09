<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/PartijMeterController.php';

$shareId = trim((string) ($_GET['id'] ?? ''));

if ($shareId === '' || !preg_match('/^[a-zA-Z0-9]+$/', $shareId)) {
    http_response_code(404);
    require 'controllers/404.php';
    return;
}

$partijMeter = new PartijMeterController();
$stored = $partijMeter->getResultByShareId($shareId);

if (!$stored) {
    http_response_code(404);
    require 'controllers/404.php';
    return;
}

try {
    $payload = $partijMeter->getData();
} catch (Throwable $e) {
    error_log('PartijMeter resultaat data load error: ' . $e->getMessage());
    $payload = ['meta' => ['totalQuestions' => 0, 'totalParties' => 0, 'disclaimer' => '', 'peildatum' => ''], 'questions' => [], 'parties' => []];
}

// Gedeeld resultaat doorgeven aan de view (client herberekent de uitslag).
$sharedResult = [
    'answers' => $stored['answers'] ?? [],
    'weights' => $stored['weights'] ?? [],
    'results' => $stored['results'] ?? [],
];

// Dynamische meta op basis van de beste match (voor mooie deel-previews).
$topName = null;
$topAgreement = null;
$topImage = null;
$ranking = $stored['results']['ranking'] ?? [];
if (!empty($ranking) && isset($ranking[0]['key'])) {
    $topKey = $ranking[0]['key'];
    $topAgreement = isset($ranking[0]['agreement']) ? (int) $ranking[0]['agreement'] : null;
    foreach ($payload['parties'] as $p) {
        if ($p['key'] === $topKey) {
            $topName = $p['name'];
            $img = $p['leaderPhoto'] ?? ($p['logo'] ?? null);
            if ($img) {
                $topImage = preg_match('#^https?://#i', $img) ? $img : rtrim(URLROOT, '/') . '/' . ltrim($img, '/');
            }
            break;
        }
    }
}

if ($topName !== null && $topAgreement !== null) {
    $pageTitle = 'Mijn PartijMeter resultaat: ' . $topName . ' (' . $topAgreement . '% match)';
    $pageDescription = 'Ik heb ' . $topAgreement . '% overeenkomst met ' . $topName . ' in de PartijMeter 2026. Doe ook de test en ontdek welke partij het beste bij jouw standpunten past.';
} else {
    $pageTitle = 'Mijn PartijMeter resultaat - PolitiekPraat';
    $pageDescription = 'Bekijk dit gedeelde PartijMeter-resultaat en doe zelf de test om te ontdekken welke partij bij jou past.';
}

$data = ['title' => $pageTitle, 'description' => $pageDescription];
if ($topImage) {
    $data['image'] = $topImage;
}

require_once 'views/partijmeter/index.php';
