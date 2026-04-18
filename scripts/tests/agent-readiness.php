<?php
/**
 * Smoke-tests voor agent-readiness endpoints.
 *
 * Controleert dat elk .well-known endpoint, de health check, de OAuth
 * discovery URLs, de JWKS en de MCP endpoint bereikbaar zijn en correcte
 * Content-Type + JSON-structuur geven.
 *
 * Gebruik:
 *   php scripts/tests/agent-readiness.php                   # lokaal (http://localhost)
 *   php scripts/tests/agent-readiness.php https://politiekpraat.nl
 *
 * Exit code 0 bij succes, 1 bij één of meer fouten.
 */

declare(strict_types=1);

$base = $argv[1] ?? 'http://localhost';
$base = rtrim($base, '/');

$passed = 0;
$failed = 0;
$errors = [];

function http_get(string $url, array $extraHeaders = []): array
{
    $ch = curl_init($url);
    $headers = ['Accept: application/json'];
    foreach ($extraHeaders as $k => $v) {
        $headers[] = $k . ': ' . $v;
    }
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_USERAGENT      => 'agent-readiness-smoketest/1.0',
    ]);
    $raw = curl_exec($ch);
    $hdrSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $status  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerStr = substr((string) $raw, 0, $hdrSize);
    $body = substr((string) $raw, $hdrSize);
    curl_close($ch);
    return ['status' => (int) $status, 'headers' => $headerStr, 'body' => $body];
}

function http_post(string $url, string $body, array $extraHeaders = []): array
{
    $ch = curl_init($url);
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    foreach ($extraHeaders as $k => $v) {
        $headers[] = $k . ': ' . $v;
    }
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => $headers,
    ]);
    $raw = curl_exec($ch);
    $hdrSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $status  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerStr = substr((string) $raw, 0, $hdrSize);
    $respBody = substr((string) $raw, $hdrSize);
    curl_close($ch);
    return ['status' => (int) $status, 'headers' => $headerStr, 'body' => $respBody];
}

function expect(bool $ok, string $label, string $detail = ''): void
{
    global $passed, $failed, $errors;
    if ($ok) {
        $passed++;
        fwrite(STDOUT, "  PASS  $label\n");
    } else {
        $failed++;
        $errors[] = $label . ($detail !== '' ? ': ' . $detail : '');
        fwrite(STDOUT, "  FAIL  $label" . ($detail !== '' ? " -- $detail" : '') . "\n");
    }
}

echo "Agent-readiness smoketests voor {$base}\n\n";

echo "[1] Link headers op /\n";
$r = http_get($base . '/');
expect($r['status'] >= 200 && $r['status'] < 400, 'homepage bereikbaar', (string) $r['status']);
expect(stripos($r['headers'], "\nLink:") !== false || stripos($r['headers'], "\nlink:") !== false, 'Link header aanwezig');

echo "\n[2] robots.txt Content-Signal\n";
$r = http_get($base . '/robots.txt');
expect($r['status'] === 200, 'robots.txt bereikbaar');
expect(stripos($r['body'], 'Content-Signal') !== false, 'Content-Signal directive aanwezig');

echo "\n[3] .well-known/api-catalog (linkset+json)\n";
$r = http_get($base . '/.well-known/api-catalog');
expect($r['status'] === 200, 'status 200');
expect(stripos($r['headers'], 'application/linkset+json') !== false, 'linkset+json content-type');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['linkset']), 'JSON met linkset key');

echo "\n[4] .well-known/openapi.json\n";
$r = http_get($base . '/.well-known/openapi.json');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && !empty($json['openapi']), 'geldig OpenAPI document');

echo "\n[5] /api/health\n";
$r = http_get($base . '/api/health');
expect($r['status'] === 200 || $r['status'] === 503, 'status 200 of 503');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['status']), 'status-veld aanwezig');

echo "\n[6] Markdown negotiation (Accept: text/markdown)\n";
$r = http_get($base . '/', ['Accept' => 'text/markdown']);
expect($r['status'] >= 200 && $r['status'] < 400, 'HTTP 2xx');
expect(stripos($r['headers'], 'text/markdown') !== false, 'Content-Type text/markdown');

echo "\n[7] .well-known/agent-skills/index.json\n";
$r = http_get($base . '/.well-known/agent-skills/index.json');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['skills']), 'skills array aanwezig');

echo "\n[8] .well-known/openid-configuration\n";
$r = http_get($base . '/.well-known/openid-configuration');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['issuer'], $json['jwks_uri']), 'OIDC metadata compleet');

echo "\n[9] .well-known/oauth-authorization-server\n";
$r = http_get($base . '/.well-known/oauth-authorization-server');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['token_endpoint']), 'AS metadata bevat token_endpoint');

echo "\n[10] .well-known/oauth-protected-resource\n";
$r = http_get($base . '/.well-known/oauth-protected-resource');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['resource'], $json['scopes_supported']), 'resource metadata compleet');

echo "\n[11] .well-known/jwks.json\n";
$r = http_get($base . '/.well-known/jwks.json');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['keys']), 'JWK set bevat keys');

echo "\n[12] API 401 -> WWW-Authenticate resource_metadata\n";
$r = http_get($base . '/api/user/profile');
expect($r['status'] === 401 || $r['status'] === 403 || $r['status'] === 400, 'unauth status');
expect(stripos($r['headers'], 'resource_metadata=') !== false || stripos($r['headers'], 'WWW-Authenticate') !== false,
    'WWW-Authenticate header verwijst naar protected resource metadata');

echo "\n[13] MCP ping (JSON-RPC)\n";
$r = http_post($base . '/mcp', json_encode(['jsonrpc' => '2.0', 'id' => 1, 'method' => 'ping']));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']) && ($json['id'] ?? null) === 1, 'ping result aanwezig');

echo "\n[14] MCP tools/list\n";
$r = http_post($base . '/mcp', json_encode(['jsonrpc' => '2.0', 'id' => 2, 'method' => 'tools/list']));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['tools']) && is_array($json['result']['tools']), 'tools array aanwezig');

echo "\n[15] .well-known/mcp/server-card.json\n";
$r = http_get($base . '/.well-known/mcp/server-card.json');
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['serverInfo'], $json['transport']), 'server card compleet');
expect(is_array($json) && !empty($json['resources']), 'server card heeft resources');
expect(is_array($json) && !empty($json['resourceTemplates']), 'server card heeft resourceTemplates');
expect(is_array($json) && !empty($json['prompts']), 'server card heeft prompts');

echo "\n[16] MCP resources/list\n";
$r = http_post($base . '/mcp', json_encode(['jsonrpc' => '2.0', 'id' => 16, 'method' => 'resources/list']));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['resources']) && is_array($json['result']['resources']),
    'resources array aanwezig');

echo "\n[17] MCP resources/templates/list\n";
$r = http_post($base . '/mcp', json_encode(['jsonrpc' => '2.0', 'id' => 17, 'method' => 'resources/templates/list']));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['resourceTemplates'])
    && is_array($json['result']['resourceTemplates']),
    'resourceTemplates array aanwezig');

echo "\n[18] MCP prompts/list\n";
$r = http_post($base . '/mcp', json_encode(['jsonrpc' => '2.0', 'id' => 18, 'method' => 'prompts/list']));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['prompts']) && is_array($json['result']['prompts']),
    'prompts array aanwezig');

echo "\n[19] MCP tools/call list_blogs (publieke read-tool)\n";
$r = http_post($base . '/mcp', json_encode([
    'jsonrpc' => '2.0',
    'id'      => 19,
    'method'  => 'tools/call',
    'params'  => ['name' => 'list_blogs', 'arguments' => ['limit' => 3]],
]));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['structuredContent']), 'structuredContent aanwezig');

echo "\n[20] MCP tools/call list_partijen\n";
$r = http_post($base . '/mcp', json_encode([
    'jsonrpc' => '2.0',
    'id'      => 20,
    'method'  => 'tools/call',
    'params'  => ['name' => 'list_partijen', 'arguments' => new stdClass()],
]));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['structuredContent']['partijen']),
    'partijen array aanwezig');

echo "\n[21] MCP tools/call list_nieuws\n";
$r = http_post($base . '/mcp', json_encode([
    'jsonrpc' => '2.0',
    'id'      => 21,
    'method'  => 'tools/call',
    'params'  => ['name' => 'list_nieuws', 'arguments' => ['limit' => 3]],
]));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['structuredContent']), 'nieuws response structuredContent');

echo "\n[22] MCP tools/call get_site_stats (publiek)\n";
$r = http_post($base . '/mcp', json_encode([
    'jsonrpc' => '2.0',
    'id'      => 22,
    'method'  => 'tools/call',
    'params'  => ['name' => 'get_site_stats', 'arguments' => new stdClass()],
]));
expect($r['status'] === 200, 'status 200');
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['result']['structuredContent']),
    'site_stats response structuredContent');

echo "\n[23] MCP write-tool zonder auth moet falen (create_blog_draft)\n";
$r = http_post($base . '/mcp', json_encode([
    'jsonrpc' => '2.0',
    'id'      => 23,
    'method'  => 'tools/call',
    'params'  => ['name' => 'create_blog_draft', 'arguments' => ['title' => 'x', 'content' => 'y']],
]));
$json = json_decode($r['body'], true);
expect(is_array($json) && isset($json['error']),
    'error response zonder OAuth',
    isset($json['error']['message']) ? (string) $json['error']['message'] : '');

echo "\n---------- Resultaat ----------\n";
echo "Passed: {$passed}\n";
echo "Failed: {$failed}\n";
if ($failed > 0) {
    echo "Fouten:\n";
    foreach ($errors as $e) {
        echo " - $e\n";
    }
    exit(1);
}
exit(0);
