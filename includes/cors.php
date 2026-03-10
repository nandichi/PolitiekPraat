<?php

if (!function_exists('get_allowed_cors_origins')) {
    function get_allowed_cors_origins(): array {
        $envOrigins = getenv('POLITIEKPRAAT_CORS_ORIGINS');

        if ($envOrigins !== false && trim($envOrigins) !== '') {
            $origins = array_filter(array_map('trim', explode(',', $envOrigins)));
            if (!empty($origins)) {
                return array_values(array_unique($origins));
            }
        }

        return [
            'https://politiekpraat.nl',
            'https://www.politiekpraat.nl',
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'http://localhost:8000',
            'http://127.0.0.1:8000'
        ];
    }
}

if (!function_exists('apply_cors_policy')) {
    function apply_cors_policy(array $methods, array $headers = ['Content-Type'], bool $reject_disallowed = true): void {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;
        $allowedOrigins = get_allowed_cors_origins();

        header('Vary: Origin');
        header('Access-Control-Allow-Methods: ' . implode(', ', $methods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $headers));

        if ($origin === null || $origin === '') {
            return;
        }

        if (in_array($origin, $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);

            if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
                http_response_code(204);
                exit;
            }

            return;
        }

        if ((($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') || $reject_disallowed) {
            http_response_code(403);
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'error' => 'Origin niet toegestaan'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
