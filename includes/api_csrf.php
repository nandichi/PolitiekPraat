<?php

if (!function_exists('api_get_raw_input')) {
    function api_get_raw_input(): string {
        if (!array_key_exists('api_raw_input', $GLOBALS)) {
            $rawInput = file_get_contents('php://input');
            $GLOBALS['api_raw_input'] = is_string($rawInput) ? $rawInput : '';
        }

        return $GLOBALS['api_raw_input'];
    }
}

if (!function_exists('api_get_request_json')) {
    function api_get_request_json(): array {
        if (!array_key_exists('api_request_json', $GLOBALS)) {
            $rawInput = api_get_raw_input();
            $decoded = json_decode($rawInput, true);
            $GLOBALS['api_request_json'] = is_array($decoded) ? $decoded : [];
        }

        return $GLOBALS['api_request_json'];
    }
}

if (!function_exists('api_get_csrf_token_from_request')) {
    function api_get_csrf_token_from_request(): ?string {
        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_SERVER['HTTP_X_XSRF_TOKEN'] ?? null;
        if (is_string($headerToken) && $headerToken !== '') {
            return trim($headerToken);
        }

        if (isset($_POST['csrf_token']) && is_string($_POST['csrf_token']) && $_POST['csrf_token'] !== '') {
            return trim($_POST['csrf_token']);
        }

        $jsonBody = api_get_request_json();
        if (isset($jsonBody['csrf_token']) && is_string($jsonBody['csrf_token']) && $jsonBody['csrf_token'] !== '') {
            return trim($jsonBody['csrf_token']);
        }

        return null;
    }
}

if (!function_exists('api_get_allowed_same_site_origins')) {
    function api_get_allowed_same_site_origins(): array {
        $allowed = [];

        if (defined('URLROOT') && is_string(URLROOT) && URLROOT !== '') {
            $allowed[] = rtrim(URLROOT, '/');
        }

        if (isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $allowed[] = $scheme . '://' . $_SERVER['HTTP_HOST'];
            $allowed[] = 'https://' . $_SERVER['HTTP_HOST'];
            $allowed[] = 'http://' . $_SERVER['HTTP_HOST'];
        }

        if (function_exists('get_allowed_cors_origins')) {
            foreach (get_allowed_cors_origins() as $origin) {
                if (is_string($origin) && $origin !== '') {
                    $allowed[] = rtrim($origin, '/');
                }
            }
        }

        $allowed = array_values(array_unique(array_filter($allowed)));
        return $allowed;
    }
}

if (!function_exists('api_is_same_site_request')) {
    function api_is_same_site_request(): bool {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;
        $referer = $_SERVER['HTTP_REFERER'] ?? null;

        if ((!is_string($origin) || trim($origin) === '') && (!is_string($referer) || trim($referer) === '')) {
            return false;
        }

        $allowedOrigins = api_get_allowed_same_site_origins();
        if (empty($allowedOrigins)) {
            return false;
        }

        if (is_string($origin) && trim($origin) !== '') {
            $normalizedOrigin = rtrim(trim($origin), '/');
            return in_array($normalizedOrigin, $allowedOrigins, true);
        }

        $refererHost = parse_url((string) $referer, PHP_URL_HOST);
        if (!is_string($refererHost) || $refererHost === '') {
            return false;
        }

        foreach ($allowedOrigins as $allowedOrigin) {
            $allowedHost = parse_url($allowedOrigin, PHP_URL_HOST);
            if (is_string($allowedHost) && strcasecmp($allowedHost, $refererHost) === 0) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('api_require_admin_csrf')) {
    function api_require_admin_csrf(): bool {
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        if (!is_string($sessionToken) || $sessionToken === '') {
            sendApiError('CSRF sessietoken ontbreekt', 403);
            return false;
        }

        $requestToken = api_get_csrf_token_from_request();
        if (!is_string($requestToken) || $requestToken === '' || !hash_equals($sessionToken, $requestToken)) {
            sendApiError('Ongeldige CSRF token', 403);
            return false;
        }

        if (!api_is_same_site_request()) {
            sendApiError('Ongeldige origin voor mutatie-request', 403);
            return false;
        }

        return true;
    }
}
