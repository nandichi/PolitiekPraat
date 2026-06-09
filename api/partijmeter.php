<?php
/**
 * PartijMeter API - opslaan en ophalen van (deelbare) resultaten.
 *
 * POST  /api/partijmeter.php           body: { answers, weights, results }
 *       -> { success, shareId, shareUrl }
 * GET   /api/partijmeter.php?share_id= -> { success, result }
 */

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');
apply_cors_policy(['GET', 'POST', 'OPTIONS'], ['Content-Type']);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/PartijMeterController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new PartijMeterController();
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Bouw de absolute deel-URL voor een share-id.
 */
function partijmeter_share_url(string $shareId): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
    return $protocol . $host . '/partijmeter/resultaat/' . $shareId;
}

try {
    switch ($method) {
        case 'GET':
            $shareId = trim((string) ($_GET['share_id'] ?? ''));
            if ($shareId === '' || !preg_match('/^[a-zA-Z0-9]+$/', $shareId)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Ongeldig share-id']);
                break;
            }
            $result = $controller->getResultByShareId($shareId);
            if (!$result) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Resultaat niet gevonden']);
                break;
            }
            echo json_encode([
                'success' => true,
                'result' => $result,
                'shareUrl' => partijmeter_share_url($shareId),
            ]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!is_array($input)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Ongeldige JSON-invoer']);
                break;
            }

            $answers = $input['answers'] ?? [];
            $weights = $input['weights'] ?? [];
            $results = $input['results'] ?? [];

            if (empty($answers) || !is_array($answers)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Geen antwoorden ontvangen']);
                break;
            }

            // Defensief: voorkom oneindig grote payloads.
            if (count($answers) > 200) {
                http_response_code(413);
                echo json_encode(['success' => false, 'error' => 'Payload te groot']);
                break;
            }

            $shareId = $controller->saveResult($answers, is_array($weights) ? $weights : [], is_array($results) ? $results : []);

            if ($shareId) {
                echo json_encode([
                    'success' => true,
                    'shareId' => $shareId,
                    'shareUrl' => partijmeter_share_url($shareId),
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Opslaan mislukt']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Methode niet toegestaan']);
    }
} catch (Throwable $e) {
    error_log('[PartijMeter API] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Serverfout']);
}
