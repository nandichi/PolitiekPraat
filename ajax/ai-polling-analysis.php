<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once BASE_PATH . '/includes/error_bootstrap.php';
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/ChatGPTAPI.php';
require_once BASE_PATH . '/includes/cors.php';

header('Content-Type: application/json');
apply_cors_policy(['POST', 'OPTIONS'], ['Content-Type']);

if (!function_exists('ai_polling_error')) {
    function ai_polling_error(string $public_message, int $status_code = 400, ?Throwable $exception = null): void
    {
        if ($exception !== null) {
            error_log(sprintf(
                '[ajax/ai-polling-analysis] %s in %s:%d',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ));
        }

        http_response_code($status_code);
        echo json_encode([
            'success' => false,
            'error' => $public_message,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ai_polling_error('Alleen POST requests toegestaan', 405);
}

try {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload) || !isset($payload['parties']) || !is_array($payload['parties'])) {
        ai_polling_error('Ongeldige input data');
    }

    $chatgpt = new ChatGPTAPI();
    $result = $chatgpt->analyzePollingData($payload['parties']);

    if (empty($result['success'])) {
        ai_polling_error('Analyse mislukt', 502);
    }

    echo json_encode([
        'success' => true,
        'content' => $result['content'],
        'timestamp' => date('Y-m-d H:i:s'),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    ai_polling_error('Interne serverfout', 500, $exception);
}
