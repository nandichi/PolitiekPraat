<?php
/**
 * OAuth 2.0 authorization endpoint (RFC 6749 §4.1.1).
 * Ondersteunt authorization_code grant met PKCE (S256).
 *
 * Input (query):
 *   response_type=code
 *   client_id, redirect_uri, scope, state, code_challenge, code_challenge_method
 *   nonce (optioneel, OIDC)
 */

declare(strict_types=1);

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';
require_once BASE_PATH . '/includes/auth_csrf.php';

use PolitiekPraat\OAuth\OAuthServer;
use PolitiekPraat\OAuth\Scopes;

function oauth_send_error_redirect(string $redirectUri, string $error, ?string $state = null, ?string $description = null): void
{
    $qs = ['error' => $error];
    if ($state !== null) $qs['state'] = $state;
    if ($description !== null) $qs['error_description'] = $description;
    $separator = strpos($redirectUri, '?') === false ? '?' : '&';
    header('Location: ' . $redirectUri . $separator . http_build_query($qs));
    exit;
}

function oauth_send_error_page(string $error, string $description = ''): void
{
    http_response_code(400);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html lang="nl"><head><meta charset="UTF-8"><title>OAuth fout</title></head><body>';
    echo '<h1>OAuth autorisatie mislukt</h1>';
    echo '<p><strong>' . htmlspecialchars($error) . '</strong></p>';
    if ($description !== '') {
        echo '<p>' . htmlspecialchars($description) . '</p>';
    }
    echo '</body></html>';
    exit;
}

$server  = new OAuthServer(new Database());
$clients = $server->clients();

$responseType      = $_GET['response_type'] ?? '';
$clientId          = $_GET['client_id'] ?? '';
$redirectUri       = $_GET['redirect_uri'] ?? '';
$scopeRaw          = $_GET['scope'] ?? '';
$state             = $_GET['state'] ?? null;
$codeChallenge     = $_GET['code_challenge'] ?? null;
$codeChallengeMethod = $_GET['code_challenge_method'] ?? null;
$nonce             = $_GET['nonce'] ?? null;
$prompt            = $_GET['prompt'] ?? '';

if ($clientId === '' || $redirectUri === '') {
    oauth_send_error_page('invalid_request', 'client_id en redirect_uri zijn verplicht.');
}

$client = $clients->findByClientId($clientId);
if ($client === null) {
    oauth_send_error_page('invalid_client', 'Onbekende client.');
}

if (!$clients->clientHasRedirect($client, $redirectUri)) {
    oauth_send_error_page('invalid_request', 'redirect_uri is niet geregistreerd voor deze client.');
}

if ($responseType !== 'code') {
    oauth_send_error_redirect($redirectUri, 'unsupported_response_type', $state, 'Alleen response_type=code wordt ondersteund.');
}

if (!$clients->clientHasGrant($client, 'authorization_code')) {
    oauth_send_error_redirect($redirectUri, 'unauthorized_client', $state, 'Client mag de authorization_code flow niet gebruiken.');
}

if (!empty($client['is_public'])) {
    if ($codeChallenge === null || $codeChallengeMethod === null) {
        oauth_send_error_redirect($redirectUri, 'invalid_request', $state, 'PKCE (code_challenge + code_challenge_method=S256) is verplicht voor public clients.');
    }
}
if ($codeChallenge !== null && strtoupper((string) $codeChallengeMethod) !== 'S256') {
    oauth_send_error_redirect($redirectUri, 'invalid_request', $state, 'code_challenge_method moet S256 zijn.');
}

$requested = Scopes::normalize((string) $scopeRaw);
if (empty($requested)) {
    $requested = [Scopes::OPENID, Scopes::PROFILE];
}
$allowedByClient = (array) $client['scopes'];
$granted = array_values(array_intersect($requested, $allowedByClient));
if (empty($granted)) {
    oauth_send_error_redirect($redirectUri, 'invalid_scope', $state, 'Gevraagde scope is niet toegestaan voor deze client.');
}

if (!isLoggedIn()) {
    $_SESSION['oauth_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
    header('Location: ' . URLROOT . '/login');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$db = $server->db();
$db->query('SELECT id, username, email, is_admin FROM users WHERE id = :id LIMIT 1');
$db->bind(':id', $userId);
$userRow = $db->single();
if (!$userRow) {
    header('Location: ' . URLROOT . '/login');
    exit;
}
$user = (array) $userRow;

$db->query('SELECT scope, revoked_at FROM oauth_consents WHERE user_id = :u AND client_id = :c LIMIT 1');
$db->bind(':u', $userId);
$db->bind(':c', $client['client_id']);
$consentRow = $db->single();
$existingScopes = [];
if ($consentRow && empty($consentRow->revoked_at)) {
    $existingScopes = Scopes::normalize((string) ($consentRow->scope ?? ''));
}
$needsConsent = !empty(array_diff($granted, $existingScopes));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!auth_require_csrf_token_from_post()) {
        oauth_send_error_page('invalid_request', 'Ongeldige CSRF-token.');
    }
    $decision = $_POST['decision'] ?? 'deny';
    if ($decision !== 'allow') {
        oauth_send_error_redirect($redirectUri, 'access_denied', $state, 'Gebruiker heeft de autorisatie geweigerd.');
    }

    $db->query('INSERT INTO oauth_consents (user_id, client_id, scope) VALUES (:u, :c, :s)
                ON DUPLICATE KEY UPDATE scope = VALUES(scope), revoked_at = NULL, granted_at = CURRENT_TIMESTAMP');
    $db->bind(':u', $userId);
    $db->bind(':c', $client['client_id']);
    $db->bind(':s', Scopes::format($granted));
    $db->execute();

    $code = $server->codes()->create([
        'client_id'             => $client['client_id'],
        'user_id'               => $userId,
        'redirect_uri'          => $redirectUri,
        'scope'                 => Scopes::format($granted),
        'code_challenge'        => $codeChallenge,
        'code_challenge_method' => $codeChallengeMethod,
        'nonce'                 => $nonce,
        'state'                 => $state,
    ]);

    $qs = ['code' => $code];
    if ($state !== null) {
        $qs['state'] = $state;
    }
    $separator = strpos($redirectUri, '?') === false ? '?' : '&';
    header('Location: ' . $redirectUri . $separator . http_build_query($qs));
    exit;
}

if (!$needsConsent && $prompt !== 'consent') {
    $code = $server->codes()->create([
        'client_id'             => $client['client_id'],
        'user_id'               => $userId,
        'redirect_uri'          => $redirectUri,
        'scope'                 => Scopes::format($granted),
        'code_challenge'        => $codeChallenge,
        'code_challenge_method' => $codeChallengeMethod,
        'nonce'                 => $nonce,
        'state'                 => $state,
    ]);
    $qs = ['code' => $code];
    if ($state !== null) $qs['state'] = $state;
    $separator = strpos($redirectUri, '?') === false ? '?' : '&';
    header('Location: ' . $redirectUri . $separator . http_build_query($qs));
    exit;
}

$csrfToken = auth_ensure_csrf_token();
$scopeDefs = Scopes::definitions();

require_once BASE_PATH . '/views/oauth/consent.php';
