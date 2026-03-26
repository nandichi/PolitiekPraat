<?php

if (!function_exists('auth_ensure_csrf_token')) {
    function auth_ensure_csrf_token(): string {
        $session_token = $_SESSION['csrf_token'] ?? null;

        if (!is_string($session_token) || $session_token === '') {
            $session_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $session_token;
        }

        return $session_token;
    }
}

if (!function_exists('auth_require_csrf_token_from_post')) {
    function auth_require_csrf_token_from_post(): bool {
        $session_token = $_SESSION['csrf_token'] ?? null;
        $request_token = $_POST['csrf_token'] ?? null;

        if (!is_string($session_token) || $session_token === '' || !is_string($request_token) || $request_token === '') {
            http_response_code(403);
            return false;
        }

        if (!hash_equals($session_token, trim($request_token))) {
            http_response_code(403);
            return false;
        }

        return true;
    }
}
