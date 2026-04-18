<?php
/**
 * MCP Server Card (SEP-1649) voor PolitiekPraat.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/error_bootstrap.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/mcp/Tools.php';

use PolitiekPraat\MCP\Tools;

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
$base   = defined('URLROOT') ? rtrim((string) URLROOT, '/') : ($scheme . '://' . $host);

$tools = array_map(static function (array $t) {
    return [
        'name'        => $t['name'],
        'description' => $t['description'],
        'inputSchema' => $t['inputSchema'],
        'scopes'      => $t['scopes'],
        'public'      => $t['public'],
    ];
}, Tools::catalog());

$card = [
    '$schema'     => 'https://modelcontextprotocol.io/schemas/server-card.v1.json',
    'serverInfo'  => [
        'name'    => 'politiekpraat',
        'title'   => 'PolitiekPraat MCP server',
        'version' => '1.0.0',
        'vendor'  => 'PolitiekPraat',
        'url'     => $base,
    ],
    'description' => 'MCP-server voor Nederlandse politieke informatie: blogs, partijen, thema\'s, moties en PartijMeter. Read-only tools zijn publiek, write-tools vereisen OAuth scope `mcp.write`.',
    'protocolVersion' => '2024-11-05',
    'transport'   => [
        'type' => 'http',
        'url'  => $base . '/mcp',
        'methods' => ['POST'],
        'contentType' => 'application/json',
    ],
    'capabilities' => [
        'tools'     => ['listChanged' => false],
        'resources' => new stdClass(),
        'prompts'   => new stdClass(),
        'logging'   => new stdClass(),
    ],
    'authentication' => [
        'type' => 'oauth2',
        'resource_metadata' => $base . '/.well-known/oauth-protected-resource',
        'authorization_servers' => [$base],
        'scopes_supported' => ['mcp.read', 'mcp.write'],
        'default_scopes'   => ['mcp.read'],
        'note' => 'Read-only tools kunnen anoniem worden aangeroepen. Write-tools vereisen een OAuth access token met scope `mcp.write` en een gebruikercontext.',
    ],
    'tools' => $tools,
    'documentation' => [
        'skills_index' => $base . '/.well-known/agent-skills/index.json',
        'api_catalog'  => $base . '/.well-known/api-catalog',
        'openapi'      => $base . '/.well-known/openapi.json',
    ],
    'contact' => [
        'name' => 'PolitiekPraat',
        'url'  => $base . '/contact',
    ],
    'license' => [
        'name' => 'Proprietary',
        'url'  => $base . '/gebruiksvoorwaarden',
    ],
];

echo json_encode($card, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
