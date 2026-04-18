<?php
/**
 * Helper voor het opbouwen van MCP tool-definities.
 *
 * Elke tool heeft: naam, omschrijving, inputSchema, scopes, public-flag
 * en een handler. `ToolBuilder::read()` maakt een publieke read-tool
 * (scope `mcp.read`, geen auth nodig). `ToolBuilder::write()` maakt een
 * write-tool die een OAuth access token met minstens scope `mcp.write`
 * vereist + een user-context.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

require_once __DIR__ . '/../oauth/Scopes.php';

use PolitiekPraat\OAuth\Scopes;

final class ToolBuilder
{
    /**
     * Publieke read-only tool. Iedereen (ook anonieme clients) mag
     * hem aanroepen.
     *
     * @param callable(array,?array):array $handler
     */
    public static function read(string $name, string $description, array $schema, callable $handler, array $extraScopes = []): array
    {
        return [
            'name'        => $name,
            'description' => $description,
            'inputSchema' => $schema,
            'scopes'      => array_values(array_unique(array_merge([Scopes::MCP_READ], $extraScopes))),
            'public'      => true,
            'handler'     => $handler,
        ];
    }

    /**
     * Write-tool. Vereist een geldig OAuth access token met scope
     * `mcp.write` én extra domain-specifieke scopes (bv. `blogs.write`).
     *
     * @param string[] $domainScopes
     * @param callable(array,?array):array $handler
     */
    public static function write(string $name, string $description, array $schema, array $domainScopes, callable $handler, bool $requireUser = true): array
    {
        return [
            'name'         => $name,
            'description'  => $description,
            'inputSchema'  => $schema,
            'scopes'       => array_values(array_unique(array_merge([Scopes::MCP_WRITE], $domainScopes))),
            'public'       => false,
            'handler'      => $handler,
            'require_user' => $requireUser,
        ];
    }

    /**
     * Service-level write tool (client_credentials zonder user). Handig
     * voor machine-to-machine scripts die wél auth'ed zijn maar geen
     * mens erachter hebben.
     *
     * @param string[] $domainScopes
     * @param callable(array,?array):array $handler
     */
    public static function service(string $name, string $description, array $schema, array $domainScopes, callable $handler): array
    {
        return self::write($name, $description, $schema, $domainScopes, $handler, false);
    }
}
