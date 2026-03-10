<?php
/**
 * Centrale foutafhandeling voor PolitiekPraat.
 *
 * Standaard draait productie veilig (geen error output naar browser).
 * Zet APP_ENV=development of APP_DEBUG=true om debug-output lokaal toe te staan.
 */

if (!defined('APP_ENV')) {
    $app_env = getenv('APP_ENV') ?: 'production';
    define('APP_ENV', strtolower((string) $app_env));
}

if (!defined('APP_DEBUG')) {
    $debug_value = getenv('APP_DEBUG');
    $is_debug = in_array(strtolower((string) $debug_value), ['1', 'true', 'yes', 'on'], true);
    define('APP_DEBUG', APP_ENV !== 'production' && $is_debug);
}

$logs_dir = dirname(__DIR__) . '/logs';
if (!is_dir($logs_dir)) {
    @mkdir($logs_dir, 0755, true);
}

ini_set('log_errors', '1');
ini_set('error_log', $logs_dir . '/php-error.log');

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

if (!function_exists('pp_is_api_request')) {
    function pp_is_api_request(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/api/') === 0 || strpos($uri, '/api') === 0;
    }
}

if (!function_exists('pp_render_error_response')) {
    function pp_render_error_response(string $message, int $status_code = 500): void
    {
        if (!headers_sent()) {
            http_response_code($status_code);
        }

        if (pp_is_api_request()) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=UTF-8');
            }

            echo json_encode([
                'success' => false,
                'error' => $message,
                'timestamp' => date('c'),
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo APP_DEBUG
            ? '<h1>Applicatiefout</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
            : '<h1>Er ging iets mis</h1><p>Probeer het later opnieuw.</p>';
    }
}

set_exception_handler(static function (Throwable $exception): void {
    error_log(sprintf(
        '[Uncaught Exception] %s in %s:%d\n%s',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    ));

    $message = APP_DEBUG ? $exception->getMessage() : 'Interne serverfout';
    pp_render_error_response($message, 500);
    exit;
});

register_shutdown_function(static function (): void {
    $last_error = error_get_last();
    if ($last_error === null) {
        return;
    }

    $fatal_types = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if (!in_array($last_error['type'], $fatal_types, true)) {
        return;
    }

    error_log(sprintf(
        '[Fatal Error] %s in %s:%d',
        $last_error['message'] ?? 'Unknown fatal error',
        $last_error['file'] ?? 'unknown',
        $last_error['line'] ?? 0
    ));

    if (!headers_sent()) {
        http_response_code(500);
    }

    if (!APP_DEBUG) {
        pp_render_error_response('Interne serverfout', 500);
    }
});
