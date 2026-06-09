<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/PartijMeterController.php';

$partijMeter = new PartijMeterController();

try {
    $payload = $partijMeter->getData();
} catch (Throwable $e) {
    error_log('PartijMeter data load error: ' . $e->getMessage());
    $payload = [
        'meta' => ['totalQuestions' => 0, 'totalParties' => 0, 'disclaimer' => '', 'peildatum' => ''],
        'questions' => [],
        'parties' => [],
    ];
}

// Wordt door de gedeelde-resultatenpagina hergebruikt: bij een meegegeven
// $sharedResult toont de view direct het opgeslagen resultaat.
$sharedResult = $sharedResult ?? null;

$totalQuestions = (int) ($payload['meta']['totalQuestions'] ?? 0);
$totalParties = (int) ($payload['meta']['totalParties'] ?? 0);

$pageTitle = 'PartijMeter 2026 - Welke partij past bij jou?';
$pageDescription = 'Doe de PartijMeter 2026 en ontdek met ' . $totalQuestions . ' actuele stellingen welke van de ' . $totalParties . ' partijen in de Tweede Kamer het beste bij jouw standpunten past. Gratis, anoniem en in een paar minuten.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

require_once 'views/partijmeter/index.php';
