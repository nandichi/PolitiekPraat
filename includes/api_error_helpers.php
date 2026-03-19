<?php

if (!function_exists('api_get_request_id')) {
    function api_get_request_id(): string {
        static $requestId = null;

        if ($requestId !== null) {
            return $requestId;
        }

        $incoming = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';
        if (is_string($incoming) && preg_match('/^[a-zA-Z0-9._-]{8,100}$/', $incoming)) {
            $requestId = $incoming;
            return $requestId;
        }

        try {
            $requestId = bin2hex(random_bytes(8));
        } catch (Exception $e) {
            $requestId = uniqid('req_', true);
        }

        return $requestId;
    }
}

if (!function_exists('api_can_show_diagnostics')) {
    function api_can_show_diagnostics(): bool {
        return function_exists('can_access_diagnostics') ? can_access_diagnostics() : false;
    }
}

if (!function_exists('api_public_error_message')) {
    function api_public_error_message($message, int $statusCode): string {
        if ($statusCode >= 500 && !api_can_show_diagnostics()) {
            return 'Interne serverfout';
        }

        return (string) $message;
    }
}

if (!function_exists('api_log_error_details')) {
    function api_log_error_details(string $publicMessage, int $statusCode, $debug = null): void {
        if ($statusCode < 500) {
            return;
        }

        $requestId = api_get_request_id();
        $context = [
            'request_id' => $requestId,
            'status' => $statusCode,
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'public_error' => $publicMessage,
        ];

        if ($debug !== null) {
            $context['debug'] = $debug;
        }

        error_log('[API ERROR] ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}

if (!function_exists('api_build_error_response')) {
    function api_build_error_response($message, int $statusCode = 500, $debug = null): array {
        $publicMessage = api_public_error_message($message, $statusCode);
        api_log_error_details((string) $message, $statusCode, $debug);

        $response = [
            'success' => false,
            'error' => $publicMessage,
            'timestamp' => date('c'),
            'request_id' => api_get_request_id(),
        ];

        if ($debug !== null && api_can_show_diagnostics()) {
            $response['debug'] = $debug;
        }

        return $response;
    }
}
