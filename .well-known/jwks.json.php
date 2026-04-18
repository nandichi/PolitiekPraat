<?php
/**
 * JSON Web Key Set (RFC 7517) van de OAuth authorization server.
 * Bevat alleen publieke sleutels.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/error_bootstrap.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;

header('Content-Type: application/jwk-set+json; charset=UTF-8');
header('Cache-Control: public, max-age=600');
header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff');

try {
    $server = new OAuthServer(new Database());
    $server->jwks()->ensureActiveKey();
    echo json_encode($server->jwks()->getJwkSet(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    error_log('[jwks.json] ' . $e->getMessage());
    echo json_encode(['keys' => []]);
}
