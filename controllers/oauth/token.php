<?php
/**
 * OAuth 2.0 token endpoint (RFC 6749 §3.2).
 * Ondersteunt grant types: authorization_code, refresh_token, client_credentials.
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;
use PolitiekPraat\OAuth\Scopes;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');
header('Pragma: no-cache');

function oauth_token_error(string $error, ?string $description = null, int $status = 400): void
{
    http_response_code($status);
    $out = ['error' => $error];
    if ($description !== null) {
        $out['error_description'] = $description;
    }
    echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    oauth_token_error('invalid_request', 'Alleen POST wordt ondersteund.', 405);
}

$server  = new OAuthServer(new Database());
$clients = $server->clients();

$clientId = $_POST['client_id'] ?? null;
$clientSecret = $_POST['client_secret'] ?? null;
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
if (stripos($authHeader, 'Basic ') === 0) {
    $decoded = base64_decode(substr($authHeader, 6), true);
    if ($decoded !== false && strpos($decoded, ':') !== false) {
        [$basicId, $basicSecret] = explode(':', $decoded, 2);
        $clientId     = $clientId ?: urldecode($basicId);
        $clientSecret = $clientSecret ?: urldecode($basicSecret);
    }
}

if ($clientId === null || $clientId === '') {
    oauth_token_error('invalid_client', 'client_id ontbreekt.', 401);
}

$client = $clients->findByClientId($clientId);
if ($client === null) {
    oauth_token_error('invalid_client', 'Onbekende client.', 401);
}

$grantType = $_POST['grant_type'] ?? '';
if (!$clients->clientHasGrant($client, $grantType)) {
    oauth_token_error('unauthorized_client', 'Grant type niet toegestaan voor deze client.');
}

if (!empty($client['is_public'])) {
    if ($client['token_endpoint_auth_method'] !== 'none') {
        oauth_token_error('invalid_client', 'Public client met verkeerde auth method.');
    }
} else {
    if ($clientSecret === null || $clientSecret === '' || !$clients->verifySecret($client, (string) $clientSecret)) {
        oauth_token_error('invalid_client', 'client_secret ongeldig.', 401);
    }
}

switch ($grantType) {
    case 'authorization_code':
        $code        = (string) ($_POST['code'] ?? '');
        $redirectUri = (string) ($_POST['redirect_uri'] ?? '');
        $verifier    = (string) ($_POST['code_verifier'] ?? '');

        if ($code === '') {
            oauth_token_error('invalid_request', 'code is verplicht.');
        }

        $record = $server->codes()->consume($code);
        if ($record === null) {
            oauth_token_error('invalid_grant', 'Authorization code ongeldig, gebruikt of verlopen.');
        }
        if ($record['client_id'] !== $client['client_id']) {
            oauth_token_error('invalid_grant', 'Client mismatch.');
        }
        if ($record['redirect_uri'] !== $redirectUri) {
            oauth_token_error('invalid_grant', 'redirect_uri mismatch.');
        }
        if (!empty($record['code_challenge'])) {
            if (!$server->validatePkce($verifier, $record['code_challenge'], $record['code_challenge_method'])) {
                oauth_token_error('invalid_grant', 'PKCE verificatie mislukt.');
            }
        }

        $user = $server->getUserById((int) $record['user_id']);
        if ($user === null) {
            oauth_token_error('invalid_grant', 'Gebruiker niet meer beschikbaar.');
        }

        $grantedScopes = Scopes::normalize((string) $record['scope']);
        $tokenData = $server->issueAccessToken($client, $user, $grantedScopes);

        $response = [
            'access_token' => $tokenData['access_token'],
            'token_type'   => $tokenData['token_type'],
            'expires_in'   => $tokenData['expires_in'],
            'scope'        => $tokenData['scope'],
        ];

        if (in_array(Scopes::OPENID, $grantedScopes, true)) {
            $response['id_token'] = $server->issueIdToken($client, $user, $grantedScopes, $record['nonce'] ?? null);
        }

        if (in_array(Scopes::OFFLINE_ACCESS, $grantedScopes, true) || $clients->clientHasGrant($client, 'refresh_token')) {
            $response['refresh_token'] = $server->issueRefreshToken($client, $user, $grantedScopes, $tokenData['jti']);
        }

        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;

    case 'refresh_token':
        $refresh = (string) ($_POST['refresh_token'] ?? '');
        if ($refresh === '') {
            oauth_token_error('invalid_request', 'refresh_token ontbreekt.');
        }
        $record = $server->tokens()->consumeRefreshToken($refresh);
        if ($record === null) {
            oauth_token_error('invalid_grant', 'Refresh token ongeldig of verlopen.');
        }
        if ($record['client_id'] !== $client['client_id']) {
            oauth_token_error('invalid_grant', 'Client mismatch.');
        }

        $requested = Scopes::normalize((string) ($_POST['scope'] ?? $record['scope']));
        $originalScopes = Scopes::normalize((string) $record['scope']);
        $granted = array_values(array_intersect($requested, $originalScopes));
        if (empty($granted)) {
            $granted = $originalScopes;
        }

        $user = $record['user_id'] !== null ? $server->getUserById((int) $record['user_id']) : null;

        $tokenData = $server->issueAccessToken($client, $user, $granted);
        $newRefresh = $server->issueRefreshToken($client, $user, $granted, $tokenData['jti']);

        echo json_encode([
            'access_token'  => $tokenData['access_token'],
            'token_type'    => $tokenData['token_type'],
            'expires_in'    => $tokenData['expires_in'],
            'scope'         => $tokenData['scope'],
            'refresh_token' => $newRefresh,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;

    case 'client_credentials':
        $requested = Scopes::normalize((string) ($_POST['scope'] ?? ''));
        $allowed = (array) $client['scopes'];
        $granted = array_values(array_intersect($requested, $allowed));
        if (empty($granted)) {
            $granted = array_values(array_intersect([Scopes::MCP_READ, Scopes::BLOGS_READ], $allowed));
        }

        $tokenData = $server->issueAccessToken($client, null, $granted);
        echo json_encode([
            'access_token' => $tokenData['access_token'],
            'token_type'   => $tokenData['token_type'],
            'expires_in'   => $tokenData['expires_in'],
            'scope'        => $tokenData['scope'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;

    default:
        oauth_token_error('unsupported_grant_type', 'Grant type wordt niet ondersteund.');
}
