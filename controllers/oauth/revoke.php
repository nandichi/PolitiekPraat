<?php
/**
 * OAuth 2.0 Token Revocation (RFC 7009).
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'invalid_request']);
    exit;
}

$server  = new OAuthServer(new Database());
$clients = $server->clients();

$clientId = $_POST['client_id'] ?? null;
$clientSecret = $_POST['client_secret'] ?? null;
$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (stripos($auth, 'Basic ') === 0) {
    $decoded = base64_decode(substr($auth, 6), true);
    if ($decoded !== false && strpos($decoded, ':') !== false) {
        [$bid, $bsec] = explode(':', $decoded, 2);
        $clientId     = $clientId ?: urldecode($bid);
        $clientSecret = $clientSecret ?: urldecode($bsec);
    }
}

if (!$clientId) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid_client']);
    exit;
}

$client = $clients->findByClientId($clientId);
if ($client === null) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid_client']);
    exit;
}
if (empty($client['is_public'])) {
    if (!$clientSecret || !$clients->verifySecret($client, (string) $clientSecret)) {
        http_response_code(401);
        echo json_encode(['error' => 'invalid_client']);
        exit;
    }
}

$token = (string) ($_POST['token'] ?? '');
$hint  = (string) ($_POST['token_type_hint'] ?? '');

if ($hint === 'refresh_token' || $hint === '') {
    $server->tokens()->revokeRefreshToken($token);
}
if ($hint === 'access_token' || $hint === '') {
    $payload = $server->verifyAccessToken($token);
    if ($payload !== null && !empty($payload['jti'])) {
        $server->tokens()->revokeAccessToken((string) $payload['jti']);
    }
}

http_response_code(200);
echo '{}';
