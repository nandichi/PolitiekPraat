<?php
/**
 * MCP (Model Context Protocol) server endpoint voor PolitiekPraat.
 *
 * Ondersteunt JSON-RPC 2.0 over HTTP POST. De transport volgt het
 * "streamable-http" profiel van de MCP-spec maar zonder SSE (single
 * request -> single response). Ideaal voor PHP-Apache runtimes.
 *
 * Ondersteunde methods:
 *   - initialize
 *   - tools/list
 *   - tools/call
 *   - ping
 *
 * Authenticatie:
 *   - Read-only tools zijn publiek (mcp.read is impliciet).
 *   - Write-tools vereisen een OAuth access token met scope `mcp.write`
 *     en een user context.
 */

declare(strict_types=1);

header_remove('X-Powered-By');
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, Mcp-Session-Id');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header('Access-Control-Expose-Headers: WWW-Authenticate, Mcp-Session-Id');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once BASE_PATH . '/includes/oauth/OAuthServer.php';
require_once BASE_PATH . '/includes/api_bearer.php';
require_once BASE_PATH . '/includes/mcp/McpException.php';
require_once BASE_PATH . '/includes/mcp/SchemaValidator.php';
require_once BASE_PATH . '/includes/mcp/Tools.php';

use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\SchemaValidator;
use PolitiekPraat\MCP\Tools;
use PolitiekPraat\OAuth\Scopes;

function mcp_send(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function mcp_error($id, int $code, string $message, $data = null, int $httpStatus = 200): void
{
    $err = ['code' => $code, 'message' => $message];
    if ($data !== null) {
        $err['data'] = $data;
    }
    mcp_send(['jsonrpc' => '2.0', 'id' => $id, 'error' => $err], $httpStatus);
}

function mcp_result($id, $result): void
{
    mcp_send(['jsonrpc' => '2.0', 'id' => $id, 'result' => $result]);
}

function mcp_auth_challenge(int $status, string $error, string $description, ?string $scopeNeeded = null): void
{
    if (!headers_sent()) {
        header('WWW-Authenticate: ' . api_bearer_challenge_header($error, $description, $scopeNeeded));
    }
    mcp_error(null, $status === 401 ? -32001 : -32002, $description, ['error' => $error], $status);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
    // Handige discovery response bij GET (niet strict MCP, maar hulpvaardig).
    mcp_send([
        'name' => 'politiekpraat',
        'version' => '1.0.0',
        'transport' => 'http',
        'protocol' => 'mcp/2024-11-05',
        'docs' => 'https://politiekpraat.nl/.well-known/mcp/server-card.json',
    ]);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    mcp_error(null, -32600, 'Alleen POST wordt ondersteund.', null, 405);
}

$raw = file_get_contents('php://input') ?: '';
$req = json_decode($raw, true);

if (!is_array($req) || !isset($req['jsonrpc']) || $req['jsonrpc'] !== '2.0' || !isset($req['method'])) {
    mcp_error(null, -32700, 'Ongeldig JSON-RPC request.', null, 400);
}

$id     = $req['id'] ?? null;
$method = (string) $req['method'];
$params = is_array($req['params'] ?? null) ? $req['params'] : [];

$authContext = api_bearer_try();
$authScopes  = $authContext['scopes'] ?? [];

switch ($method) {
    case 'initialize':
        $clientInfo = $params['clientInfo'] ?? [];
        mcp_result($id, [
            'protocolVersion' => '2024-11-05',
            'serverInfo'      => [
                'name'    => 'politiekpraat',
                'version' => '1.0.0',
            ],
            'capabilities'    => [
                'tools'     => ['listChanged' => false],
                'logging'   => new stdClass(),
                'resources' => new stdClass(),
                'prompts'   => new stdClass(),
            ],
            'instructions' => 'PolitiekPraat MCP-server. Read-only tools zijn publiek. Schrijf-tools vereisen OAuth access token met scope `mcp.write`.',
        ]);
        break;

    case 'ping':
        mcp_result($id, new stdClass());
        break;

    case 'tools/list':
        $catalog = Tools::catalog();
        $list = array_map(static function (array $t) {
            return [
                'name'        => $t['name'],
                'description' => $t['description'],
                'inputSchema' => $t['inputSchema'],
            ];
        }, $catalog);
        mcp_result($id, ['tools' => $list]);
        break;

    case 'tools/call':
        $name = $params['name'] ?? '';
        $args = is_array($params['arguments'] ?? null) ? $params['arguments'] : [];

        $catalog = Tools::catalog();
        $found = null;
        foreach ($catalog as $t) {
            if ($t['name'] === $name) {
                $found = $t;
                break;
            }
        }
        if ($found === null) {
            mcp_error($id, -32601, 'Onbekende tool: ' . $name);
        }

        $needsAuth = !$found['public'];
        if ($needsAuth) {
            if ($authContext === null) {
                mcp_auth_challenge(401, 'invalid_token', 'Access token vereist voor deze tool.', Scopes::MCP_WRITE);
            }
            foreach ($found['scopes'] as $scope) {
                if (!in_array($scope, $authScopes, true)) {
                    mcp_auth_challenge(403, 'insufficient_scope', 'Scope ' . $scope . ' vereist.', $scope);
                }
            }
            if ($authContext['user_id'] === null) {
                mcp_error($id, -32002, 'Deze tool vereist een gebruikercontext (login).');
            }
        }

        $errors = SchemaValidator::validate($args, $found['inputSchema']);
        if (!empty($errors)) {
            mcp_error($id, -32602, 'Ongeldige arguments', ['errors' => $errors]);
        }

        try {
            $result = ($found['handler'])($args, $authContext);
            mcp_result($id, [
                'content' => [[
                    'type' => 'text',
                    'text' => json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                ]],
                'structuredContent' => $result,
                'isError' => false,
            ]);
        } catch (McpException $e) {
            mcp_error($id, $e->getCode(), $e->getMessage(), $e->toJsonRpc()['data'] ?? null);
        } catch (Throwable $e) {
            error_log('[mcp] tool ' . $name . ' error: ' . $e->getMessage());
            mcp_error($id, -32000, 'Interne fout tijdens tool-aanroep.');
        }
        break;

    case 'notifications/initialized':
    case 'notifications/cancelled':
        http_response_code(204);
        exit;

    default:
        mcp_error($id, -32601, 'Onbekende method: ' . $method);
}
