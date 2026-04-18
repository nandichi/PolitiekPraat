<?php
/**
 * OAuth 2.0 Protected Resource Metadata (RFC 9728).
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/error_bootstrap.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/oauth/OAuthServer.php';

use PolitiekPraat\OAuth\OAuthServer;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff');

try {
    $server = new OAuthServer(new Database());
    echo json_encode($server->protectedResourceMetadata(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    error_log('[oauth-protected-resource] ' . $e->getMessage());
    echo json_encode(['error' => 'server_error']);
}
