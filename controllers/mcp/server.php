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
 *   - ping
 *   - tools/list
 *   - tools/call
 *   - resources/list
 *   - resources/templates/list
 *   - resources/read
 *   - prompts/list
 *   - prompts/get
 *
 * Authenticatie:
 *   - Read-only tools en resources zijn publiek (mcp.read is impliciet).
 *   - Write-tools vereisen een OAuth access token met scope `mcp.write`
 *     en (meestal) een user context. Tools met `require_user=false` mogen
 *     ook met client_credentials worden aangeroepen.
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
require_once BASE_PATH . '/includes/mcp/Resources.php';
require_once BASE_PATH . '/includes/mcp/Prompts.php';

use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\Prompts;
use PolitiekPraat\MCP\Resources;
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
    mcp_send([
        'name'      => 'politiekpraat',
        'version'   => '1.1.0',
        'transport' => 'http',
        'protocol'  => 'mcp/2024-11-05',
        'docs'      => 'https://politiekpraat.nl/.well-known/mcp/server-card.json',
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
        mcp_result($id, [
            'protocolVersion' => '2024-11-05',
            'serverInfo'      => [
                'name'    => 'politiekpraat',
                'version' => '1.1.0',
            ],
            'capabilities'    => [
                'tools'     => ['listChanged' => false],
                'resources' => ['subscribe' => false, 'listChanged' => false],
                'prompts'   => ['listChanged' => false],
                'logging'   => new stdClass(),
            ],
            'instructions' => 'PolitiekPraat MCP-server. Read-only tools en resources zijn publiek. Schrijf-tools vereisen OAuth access token met scope `mcp.write` + relevante domain scopes (bv. `blogs.write`, `media.write`). Volg de prompt `write_political_blog` voor een autonome blog-workflow.',
        ]);
        break;

    case 'ping':
        mcp_result($id, new stdClass());
        break;

    case 'tools/list': {
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
    }

    case 'tools/call': {
        $name = $params['name'] ?? '';
        $args = is_array($params['arguments'] ?? null) ? $params['arguments'] : [];

        $found = Tools::find((string) $name);
        if ($found === null) {
            mcp_error($id, -32601, 'Onbekende tool: ' . $name);
        }

        $needsAuth = !$found['public'];
        $requireUser = $found['require_user'] ?? true;

        if ($needsAuth) {
            if ($authContext === null) {
                mcp_auth_challenge(401, 'invalid_token', 'Access token vereist voor deze tool.', Scopes::MCP_WRITE);
            }
            foreach ($found['scopes'] as $scope) {
                if (!in_array($scope, $authScopes, true)) {
                    mcp_auth_challenge(403, 'insufficient_scope', 'Scope ' . $scope . ' vereist.', $scope);
                }
            }
            if ($requireUser && ($authContext['user_id'] ?? null) === null) {
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
    }

    case 'resources/list':
        mcp_result($id, ['resources' => Resources::list()]);
        break;

    case 'resources/templates/list':
        mcp_result($id, ['resourceTemplates' => Resources::templates()]);
        break;

    case 'resources/read': {
        $uri = (string) ($params['uri'] ?? '');
        if ($uri === '') {
            mcp_error($id, -32602, 'uri_required');
        }
        try {
            $contents = Resources::read($uri);
            mcp_result($id, ['contents' => $contents]);
        } catch (McpException $e) {
            mcp_error($id, $e->getCode(), $e->getMessage(), $e->toJsonRpc()['data'] ?? null);
        } catch (Throwable $e) {
            error_log('[mcp] resource ' . $uri . ' error: ' . $e->getMessage());
            mcp_error($id, -32000, 'Interne fout bij resources/read.');
        }
        break;
    }

    case 'prompts/list':
        mcp_result($id, ['prompts' => Prompts::list()]);
        break;

    case 'prompts/get': {
        $name = (string) ($params['name'] ?? '');
        $args = is_array($params['arguments'] ?? null) ? $params['arguments'] : [];
        if ($name === '') {
            mcp_error($id, -32602, 'name_required');
        }
        try {
            mcp_result($id, Prompts::get($name, $args));
        } catch (McpException $e) {
            mcp_error($id, $e->getCode(), $e->getMessage(), $e->toJsonRpc()['data'] ?? null);
        } catch (Throwable $e) {
            error_log('[mcp] prompt ' . $name . ' error: ' . $e->getMessage());
            mcp_error($id, -32000, 'Interne fout bij prompts/get.');
        }
        break;
    }

    case 'notifications/initialized':
    case 'notifications/cancelled':
        http_response_code(204);
        exit;

    default:
        mcp_error($id, -32601, 'Onbekende method: ' . $method);
}
