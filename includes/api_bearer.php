<?php
/**
 * Gemeenschappelijke helpers voor Bearer-authenticatie in de API.
 *
 * Accepteert zowel OAuth 2.0 access tokens (JWKS-verified, scope-aware)
 * als legacy intern-JWT (via JwtService) voor backwards compatibility.
 *
 * Bij een authenticatiefout wordt een RFC 9728 conforme WWW-Authenticate
 * header gezet die verwijst naar de Protected Resource Metadata.
 */

declare(strict_types=1);

require_once __DIR__ . '/oauth/OAuthServer.php';

if (!function_exists('api_bearer_extract')) {
    function api_bearer_extract(): ?string
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';
        if (!is_string($auth) || $auth === '') {
            return null;
        }
        if (stripos($auth, 'Bearer ') !== 0) {
            return null;
        }
        return trim(substr($auth, 7)) ?: null;
    }
}

if (!function_exists('api_bearer_resource_uri')) {
    function api_bearer_resource_uri(): string
    {
        $base = defined('URLROOT') ? (string) URLROOT : '';
        if ($base === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
            $base = $scheme . '://' . $host;
        }
        return rtrim($base, '/') . '/.well-known/oauth-protected-resource';
    }
}

if (!function_exists('api_bearer_challenge_header')) {
    function api_bearer_challenge_header(string $error = 'invalid_token', ?string $description = null, ?string $scopeNeeded = null): string
    {
        $resource = api_bearer_resource_uri();
        $parts = [
            'realm="politiekpraat"',
            'resource_metadata="' . $resource . '"',
            'error="' . $error . '"',
        ];
        if ($description !== null && $description !== '') {
            $parts[] = 'error_description="' . addslashes($description) . '"';
        }
        if ($scopeNeeded !== null && $scopeNeeded !== '') {
            $parts[] = 'scope="' . $scopeNeeded . '"';
        }
        return 'Bearer ' . implode(', ', $parts);
    }
}

if (!function_exists('api_bearer_send_challenge')) {
    function api_bearer_send_challenge(int $status, string $error, ?string $description = null, ?string $scopeNeeded = null): void
    {
        if (!headers_sent()) {
            header('WWW-Authenticate: ' . api_bearer_challenge_header($error, $description, $scopeNeeded));
        }
        if (function_exists('sendApiError')) {
            sendApiError($description ?? $error, $status);
        }
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'error'   => $description ?? $error,
            'timestamp' => date('c'),
        ]);
        exit;
    }
}

/**
 * Probeert het bearer token te verifiëren als OAuth access token.
 * Retourneert een normaliseerd context-array of null.
 */
if (!function_exists('api_bearer_verify_oauth')) {
    function api_bearer_verify_oauth(string $token): ?array
    {
        try {
            $server = \PolitiekPraat\OAuth\oauth_server();
            $payload = $server->verifyAccessToken($token);
            if ($payload === null) {
                return null;
            }
            $scopes = \PolitiekPraat\OAuth\Scopes::normalize((string) ($payload['scope'] ?? ''));
            return [
                'type'      => 'oauth',
                'payload'   => $payload,
                'user_id'   => isset($payload['user_id']) ? (int) $payload['user_id'] : null,
                'client_id' => $payload['client_id'] ?? null,
                'scopes'    => $scopes,
            ];
        } catch (Throwable $e) {
            error_log('[api_bearer_verify_oauth] ' . $e->getMessage());
            return null;
        }
    }
}

/**
 * Legacy fallback: verifieer als intern-JWT via JwtService.
 */
if (!function_exists('api_bearer_verify_legacy')) {
    function api_bearer_verify_legacy(string $token): ?array
    {
        if (!class_exists('JwtService')) {
            $path = __DIR__ . '/JwtService.php';
            if (is_readable($path)) {
                require_once $path;
            }
        }
        if (!class_exists('JwtService')) {
            return null;
        }
        try {
            $service = new JwtService();
            $payload = $service->verify($token);
            if (!$payload) {
                return null;
            }
            $payloadArr = is_array($payload) ? $payload : (array) $payload;
            return [
                'type'      => 'legacy',
                'payload'   => $payloadArr,
                'user_id'   => isset($payloadArr['user_id']) ? (int) $payloadArr['user_id'] : null,
                'client_id' => null,
                'scopes'    => \PolitiekPraat\OAuth\Scopes::supported(),
            ];
        } catch (Throwable $e) {
            return null;
        }
    }
}

/**
 * Verifieer het Bearer-token. Returns context-array of stuurt 401 challenge.
 */
if (!function_exists('api_bearer_require')) {
    function api_bearer_require(?string $requiredScope = null): array
    {
        $token = api_bearer_extract();
        if ($token === null) {
            api_bearer_send_challenge(401, 'invalid_request', 'Authorization header met Bearer token vereist.', $requiredScope);
        }

        $context = api_bearer_verify_oauth($token);
        if ($context === null) {
            $context = api_bearer_verify_legacy($token);
        }
        if ($context === null) {
            api_bearer_send_challenge(401, 'invalid_token', 'Access token is ongeldig of verlopen.', $requiredScope);
        }

        if ($requiredScope !== null && $requiredScope !== '' && $context['type'] === 'oauth') {
            if (!in_array($requiredScope, (array) $context['scopes'], true)) {
                api_bearer_send_challenge(403, 'insufficient_scope', 'Scope "' . $requiredScope . '" vereist.', $requiredScope);
            }
        }

        return $context;
    }
}

if (!function_exists('api_bearer_try')) {
    function api_bearer_try(): ?array
    {
        $token = api_bearer_extract();
        if ($token === null) {
            return null;
        }
        $ctx = api_bearer_verify_oauth($token);
        return $ctx ?: api_bearer_verify_legacy($token);
    }
}
