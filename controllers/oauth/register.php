<?php
/**
 * OAuth 2.0 Dynamic Client Registration (RFC 7591).
 *
 * Accepteert JSON body met client metadata. Registratie is open maar
 * rate-limited; voor enterprise kan een initial_access_token worden vereist
 * via env POLITIEKPRAAT_OAUTH_REGISTRATION_TOKEN.
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';
require_once BASE_PATH . '/includes/rate_limiter.php';

use PolitiekPraat\OAuth\OAuthServer;
use PolitiekPraat\OAuth\Scopes;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

function oauth_register_error(string $error, int $status = 400, ?string $description = null): void
{
    http_response_code($status);
    echo json_encode([
        'error' => $error,
        'error_description' => $description,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    oauth_register_error('invalid_request', 405, 'Alleen POST is toegestaan.');
}

if (function_exists('enforce_api_rate_limit')) {
    try {
        enforce_api_rate_limit();
    } catch (Throwable $e) { /* already handled */ }
}

$requiredInitialToken = getenv('POLITIEKPRAAT_OAUTH_REGISTRATION_TOKEN');
if (is_string($requiredInitialToken) && trim($requiredInitialToken) !== '') {
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (stripos($auth, 'Bearer ') !== 0 || !hash_equals(trim($requiredInitialToken), trim(substr($auth, 7)))) {
        oauth_register_error('invalid_token', 401, 'Initial access token vereist.');
    }
}

$body = file_get_contents('php://input') ?: '';
$input = json_decode($body, true);
if (!is_array($input)) {
    oauth_register_error('invalid_client_metadata', 400, 'Body moet JSON-object zijn.');
}

$name = trim((string) ($input['client_name'] ?? ''));
if ($name === '') {
    oauth_register_error('invalid_client_metadata', 400, 'client_name is verplicht.');
}

$redirects = $input['redirect_uris'] ?? [];
if (!is_array($redirects) || empty($redirects)) {
    oauth_register_error('invalid_redirect_uri', 400, 'redirect_uris is verplicht.');
}
// Accept http(s) URLs plus private-use / custom URI schemes for native apps
// (RFC 8252, bv. cursor://, com.example.app://, app.bundle.id:/callback).
// Gevaarlijke schemes worden expliciet geweigerd.
$forbiddenSchemes = ['javascript', 'data', 'vbscript', 'file', 'about'];
foreach ($redirects as $uri) {
    if (!is_string($uri) || $uri === '') {
        oauth_register_error('invalid_redirect_uri', 400, 'redirect_uris moet een lijst met strings zijn.');
    }
    if (!preg_match('#^([a-z][a-z0-9+\-.]{0,31}):#i', $uri, $m)) {
        oauth_register_error('invalid_redirect_uri', 400, 'redirect_uri heeft geen geldige scheme: ' . $uri);
    }
    $scheme = strtolower($m[1]);
    if (in_array($scheme, $forbiddenSchemes, true)) {
        oauth_register_error('invalid_redirect_uri', 400, 'redirect_uri scheme niet toegestaan: ' . $scheme);
    }
}

$grantTypes    = is_array($input['grant_types'] ?? null) ? $input['grant_types'] : ['authorization_code'];
$responseTypes = is_array($input['response_types'] ?? null) ? $input['response_types'] : ['code'];
$scopeString   = (string) ($input['scope'] ?? 'openid profile email');
$scopes        = Scopes::normalize($scopeString);
if (empty($scopes)) {
    $scopes = [Scopes::OPENID, Scopes::PROFILE];
}

$authMethod = (string) ($input['token_endpoint_auth_method'] ?? 'client_secret_basic');
$allowedAuth = ['client_secret_basic', 'client_secret_post', 'none'];
if (!in_array($authMethod, $allowedAuth, true)) {
    oauth_register_error('invalid_client_metadata', 400, 'token_endpoint_auth_method niet ondersteund.');
}
$isPublic = ($authMethod === 'none');

$server = new OAuthServer(new Database());
$clients = $server->clients();

try {
    $client = $clients->create([
        'client_name'                => $name,
        'client_uri'                 => $input['client_uri'] ?? null,
        'logo_uri'                   => $input['logo_uri'] ?? null,
        'tos_uri'                    => $input['tos_uri'] ?? null,
        'policy_uri'                 => $input['policy_uri'] ?? null,
        'redirect_uris'              => $redirects,
        'grant_types'                => $grantTypes,
        'response_types'             => $responseTypes,
        'scopes'                     => $scopes,
        'token_endpoint_auth_method' => $authMethod,
        'is_public'                  => $isPublic,
        'issue_registration_token'   => true,
        'registration_client_uri'    => $server->endpoint('/oauth/register/' . '__cid__'),
    ]);
} catch (Throwable $e) {
    error_log('[oauth/register] ' . $e->getMessage());
    oauth_register_error('server_error', 500, 'Registratie mislukt.');
}

http_response_code(201);
$response = [
    'client_id'                     => $client['client_id'],
    'client_name'                   => $client['client_name'],
    'redirect_uris'                 => $client['redirect_uris'],
    'grant_types'                   => $client['grant_types'],
    'response_types'                => $client['response_types'],
    'scope'                         => Scopes::format((array) $client['scopes']),
    'token_endpoint_auth_method'    => $client['token_endpoint_auth_method'],
    'client_id_issued_at'           => time(),
];

if (!empty($client['__plain_client_secret'])) {
    $response['client_secret'] = $client['__plain_client_secret'];
    $response['client_secret_expires_at'] = 0;
}
if (!empty($client['__registration_access_token'])) {
    $response['registration_access_token'] = $client['__registration_access_token'];
    $response['registration_client_uri'] = $server->endpoint('/oauth/register/' . $client['client_id']);
}

echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
