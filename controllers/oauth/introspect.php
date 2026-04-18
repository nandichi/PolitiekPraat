<?php
/**
 * OAuth 2.0 Token Introspection (RFC 7662).
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['active' => false]);
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
    echo json_encode(['active' => false]);
    exit;
}
$client = $clients->findByClientId($clientId);
if ($client === null) {
    http_response_code(401);
    echo json_encode(['active' => false]);
    exit;
}
if (empty($client['is_public'])) {
    if (!$clientSecret || !$clients->verifySecret($client, (string) $clientSecret)) {
        http_response_code(401);
        echo json_encode(['active' => false]);
        exit;
    }
}

$token = (string) ($_POST['token'] ?? '');
$payload = $server->verifyAccessToken($token);
if ($payload === null) {
    echo json_encode(['active' => false]);
    exit;
}

echo json_encode([
    'active'    => true,
    'scope'     => $payload['scope'] ?? '',
    'client_id' => $payload['client_id'] ?? null,
    'username'  => null,
    'token_type'=> 'Bearer',
    'exp'       => $payload['exp'] ?? null,
    'iat'       => $payload['iat'] ?? null,
    'nbf'       => $payload['nbf'] ?? null,
    'sub'       => $payload['sub'] ?? null,
    'aud'       => $payload['aud'] ?? null,
    'iss'       => $payload['iss'] ?? null,
    'jti'       => $payload['jti'] ?? null,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
