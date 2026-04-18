<?php
/**
 * Agent Discovery helper
 *
 * Zet Link response-headers (RFC 8288) zodat AI-agents bij een reguliere
 * HTML/Markdown response alle relevante .well-known endpoints kunnen
 * ontdekken. Wordt aangeroepen vanuit index.php voorafgaand aan routing.
 *
 * Relevante specs:
 *  - RFC 8288 (Web Linking / Link header)
 *  - RFC 9727 (API Catalog)
 *  - RFC 9728 (OAuth Protected Resource Metadata)
 *  - https://agentskills.io/
 *  - https://modelcontextprotocol.io/
 */

if (!function_exists('agent_discovery_should_emit_links')) {
    /**
     * Bepaalt of voor het huidige request Link-headers moeten worden gezet.
     * We slaan paden over die zelf JSON/API zijn, omdat daar al content-
     * specifieke headers (zoals WWW-Authenticate) gezet worden.
     */
    function agent_discovery_should_emit_links(): bool {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = '/' . ltrim($path, '/');

        $skipPrefixes = [
            '/api/',
            '/ajax/',
            '/.well-known/',
            '/admin/ajax',
        ];

        foreach ($skipPrefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                return false;
            }
        }

        if ($path === '/api') {
            return false;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return false;
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (!in_array($method, ['GET', 'HEAD'], true)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('agent_discovery_build_canonical_url')) {
    function agent_discovery_build_canonical_url(string $path): string {
        $base = defined('URLROOT') ? (string) URLROOT : '';
        if ($base === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
            $base = $scheme . '://' . $host;
        }

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('agent_discovery_emit_link_headers')) {
    /**
     * Emit Link-headers voor agent-discovery.
     *
     * We zetten alle headers met een enkele komma-gescheiden Link header.
     * RFC 8288 staat expliciet meerdere Link-veldwaarden toe, zowel als
     * losse headers als komma-gescheiden binnen één header.
     */
    function agent_discovery_emit_link_headers(): void {
        if (headers_sent()) {
            return;
        }

        if (!agent_discovery_should_emit_links()) {
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $canonical = agent_discovery_build_canonical_url($path);

        $links = [
            '</.well-known/api-catalog>; rel="api-catalog"; type="application/linkset+json"',
            '</api>; rel="service-doc"; type="text/html"',
            '</.well-known/agent-skills/index.json>; rel="https://agentskills.io/rel/index"; type="application/json"',
            '</.well-known/mcp/server-card.json>; rel="https://modelcontextprotocol.io/rel/server-card"; type="application/json"',
            '</.well-known/oauth-authorization-server>; rel="oauth-authorization-server"',
            '</.well-known/openid-configuration>; rel="http://openid.net/specs/connect/1.0/issuer"',
            '</.well-known/oauth-protected-resource>; rel="https://www.rfc-editor.org/rfc/rfc9728"',
            '<' . $canonical . '>; rel="alternate"; type="text/markdown"',
        ];

        header('Link: ' . implode(', ', $links), false);

        $existingVary = '';
        foreach (headers_list() as $existingHeader) {
            if (stripos($existingHeader, 'Vary:') === 0) {
                $existingVary = trim(substr($existingHeader, 5));
                break;
            }
        }

        $varyTokens = array_filter(array_map('trim', explode(',', $existingVary)));
        if (!in_array('Accept', $varyTokens, true)) {
            $varyTokens[] = 'Accept';
        }
        header('Vary: ' . implode(', ', $varyTokens));
    }
}
