<?php
/**
 * API Catalog (RFC 9727) voor PolitiekPraat.
 *
 * Beschrijft de publieke REST API met linkset (RFC 9264) zodat AI-agents
 * automatisch service-desc (OpenAPI), service-doc, status en auth-info
 * kunnen ontdekken.
 *
 * Verwachte content-type: application/linkset+json
 */

declare(strict_types=1);

header('Content-Type: application/linkset+json; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
header('X-Content-Type-Options: nosniff');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
$base   = $scheme . '://' . $host;

$catalog = [
    'linkset' => [
        [
            'anchor' => $base . '/api',
            'service-desc' => [
                [
                    'href' => $base . '/.well-known/openapi.json',
                    'type' => 'application/vnd.oai.openapi+json;version=3.0',
                ],
            ],
            'service-doc' => [
                [
                    'href' => $base . '/api/',
                    'type' => 'text/html',
                    'title' => 'PolitiekPraat REST API documentatie',
                    'hreflang' => ['nl'],
                ],
            ],
            'status' => [
                [
                    'href' => $base . '/api/health',
                    'type' => 'application/json',
                ],
            ],
            'terms-of-service' => [
                [
                    'href' => $base . '/gebruiksvoorwaarden',
                    'type' => 'text/html',
                    'hreflang' => ['nl'],
                ],
            ],
            'privacy-policy' => [
                [
                    'href' => $base . '/privacy-policy',
                    'type' => 'text/html',
                    'hreflang' => ['nl'],
                ],
            ],
            'http://oauth.net/specs/discovery' => [
                [
                    'href' => $base . '/.well-known/oauth-protected-resource',
                    'type' => 'application/json',
                ],
            ],
            'https://modelcontextprotocol.io/rel/server-card' => [
                [
                    'href' => $base . '/.well-known/mcp/server-card.json',
                    'type' => 'application/json',
                    'title' => 'PolitiekPraat MCP server',
                ],
            ],
        ],
        [
            'anchor' => $base . '/mcp',
            'service-desc' => [
                [
                    'href' => $base . '/.well-known/mcp/server-card.json',
                    'type' => 'application/json',
                ],
            ],
            'status' => [
                [
                    'href' => $base . '/api/health',
                    'type' => 'application/json',
                ],
            ],
        ],
    ],
];

echo json_encode($catalog, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
