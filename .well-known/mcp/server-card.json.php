<?php
/**
 * MCP Server Card (SEP-1649) voor PolitiekPraat.
 *
 * Geeft een complete catalog van tools, resources, resource-templates,
 * prompts, capabilities en authenticatie. Wordt door agents gebruikt om
 * de server te ontdekken.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/error_bootstrap.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/oauth/Scopes.php';
require_once __DIR__ . '/../../includes/mcp/Tools.php';
require_once __DIR__ . '/../../includes/mcp/Resources.php';
require_once __DIR__ . '/../../includes/mcp/Prompts.php';

use PolitiekPraat\MCP\Prompts;
use PolitiekPraat\MCP\Resources;
use PolitiekPraat\MCP\Tools;
use PolitiekPraat\OAuth\Scopes;

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
        'requireUser' => $t['require_user'] ?? !$t['public'],
    ];
}, Tools::catalog());

$card = [
    '$schema'         => 'https://modelcontextprotocol.io/schemas/server-card.v1.json',
    'serverInfo'      => [
        'name'    => 'politiekpraat',
        'title'   => 'PolitiekPraat MCP server',
        'version' => '1.1.0',
        'vendor'  => 'PolitiekPraat',
        'url'     => $base,
    ],
    'description' => "Volledige MCP-server voor PolitiekPraat: lezen en schrijven van blogs, comments, forum-topics, polls en nieuwsbrief-aanmeldingen, plus uitgebreide read-only tools voor partijen, stemwijzer-stellingen, stemmentracker-moties, Nederlandse en Amerikaanse verkiezingen, en globale zoek + analytics. Read-only tools en resources zijn publiek; schrijf-tools vereisen OAuth2 met scope `mcp.write` plus domain-specifieke scopes (bv. `blogs.write`, `media.write`).",
    'protocolVersion' => '2024-11-05',
    'transport'       => [
        'type'        => 'http',
        'url'         => $base . '/mcp',
        'methods'     => ['POST'],
        'contentType' => 'application/json',
    ],
    'capabilities' => [
        'tools'     => ['listChanged' => false],
        'resources' => ['subscribe' => false, 'listChanged' => false],
        'prompts'   => ['listChanged' => false],
        'logging'   => new stdClass(),
    ],
    'authentication' => [
        'type'                  => 'oauth2',
        'resource_metadata'     => $base . '/.well-known/oauth-protected-resource',
        'authorization_servers' => [$base],
        'scopes_supported'      => Scopes::supported(),
        'default_scopes'        => [Scopes::MCP_READ],
        'note'                  => 'Read-only tools en resources kunnen anoniem worden aangeroepen. Write-tools vereisen `mcp.write` plus de domain-scope (bv. `blogs.write`, `media.write`, `comments.write`, `forum.write`, `polls.write`, `newsletter.write`, `analytics.read`).',
    ],
    'tools'             => $tools,
    'resources'         => Resources::list(),
    'resourceTemplates' => Resources::templates(),
    'prompts'           => Prompts::list(),
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
