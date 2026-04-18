<?php
/**
 * OpenID Connect UserInfo endpoint (OIDC Core 1.0 §5.3).
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;
use PolitiekPraat\OAuth\Scopes;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

function oauth_userinfo_error(string $error, int $status = 401, ?string $description = null): void
{
    http_response_code($status);
    $scheme = 'Bearer error="' . $error . '"';
    if ($description !== null) {
        $scheme .= ' error_description="' . addslashes($description) . '"';
    }
    header('WWW-Authenticate: ' . $scheme);
    echo json_encode(['error' => $error, 'error_description' => $description], JSON_UNESCAPED_SLASHES);
    exit;
}

$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
if (stripos($auth, 'Bearer ') !== 0) {
    oauth_userinfo_error('invalid_token', 401, 'Bearer access token ontbreekt.');
}
$accessToken = trim(substr($auth, 7));

$server = new OAuthServer(new Database());
$payload = $server->verifyAccessToken($accessToken);
if ($payload === null) {
    oauth_userinfo_error('invalid_token', 401, 'Access token is ongeldig of verlopen.');
}

$scopes = Scopes::normalize((string) ($payload['scope'] ?? ''));
if (!in_array(Scopes::OPENID, $scopes, true)) {
    oauth_userinfo_error('insufficient_scope', 403, 'openid scope vereist.');
}

$userId = isset($payload['user_id']) ? (int) $payload['user_id'] : null;
if ($userId === null) {
    oauth_userinfo_error('invalid_token', 403, 'Token heeft geen user context.');
}

$user = $server->getUserById($userId);
if ($user === null) {
    oauth_userinfo_error('invalid_token', 401, 'Gebruiker niet gevonden.');
}

$claims = ['sub' => (string) $user['id']];
if (in_array(Scopes::PROFILE, $scopes, true)) {
    $claims['preferred_username'] = $user['username'] ?? null;
    $claims['name'] = $user['username'] ?? null;
    if (!empty($user['profile_photo'])) {
        $claims['picture'] = $user['profile_photo'];
    }
    if (!empty($user['created_at'])) {
        $claims['updated_at'] = strtotime((string) $user['created_at']);
    }
}
if (in_array(Scopes::EMAIL, $scopes, true)) {
    $claims['email'] = $user['email'] ?? null;
    $claims['email_verified'] = !empty($user['email']);
}

echo json_encode($claims, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
