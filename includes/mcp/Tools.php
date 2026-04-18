<?php
/**
 * MCP tool-registry voor PolitiekPraat.
 *
 * Aggregeert alle modulaire tool-classes in `/includes/mcp/tools/`.
 * Iedere module exposet een statische `catalog()` method die een lijst
 * tools retourneert opgebouwd via {@see ToolBuilder}.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

require_once __DIR__ . '/ToolBuilder.php';
require_once __DIR__ . '/McpException.php';
require_once __DIR__ . '/tools/BlogTools.php';
require_once __DIR__ . '/tools/MediaTools.php';
require_once __DIR__ . '/tools/PartijenTools.php';
require_once __DIR__ . '/tools/StemwijzerTools.php';
require_once __DIR__ . '/tools/StemmentrackerTools.php';
require_once __DIR__ . '/tools/NieuwsTools.php';
require_once __DIR__ . '/tools/CommunityTools.php';
require_once __DIR__ . '/tools/VerkiezingenTools.php';
require_once __DIR__ . '/tools/SearchTools.php';
require_once __DIR__ . '/tools/AnalyticsTools.php';

use PolitiekPraat\MCP\Tools\AnalyticsTools;
use PolitiekPraat\MCP\Tools\BlogTools;
use PolitiekPraat\MCP\Tools\CommunityTools;
use PolitiekPraat\MCP\Tools\MediaTools;
use PolitiekPraat\MCP\Tools\NieuwsTools;
use PolitiekPraat\MCP\Tools\PartijenTools;
use PolitiekPraat\MCP\Tools\SearchTools;
use PolitiekPraat\MCP\Tools\StemmentrackerTools;
use PolitiekPraat\MCP\Tools\StemwijzerTools;
use PolitiekPraat\MCP\Tools\VerkiezingenTools;

class Tools
{
    /** @var array<int, array<string, mixed>>|null */
    private static ?array $cache = null;

    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $tools = array_merge(
            BlogTools::catalog(),
            MediaTools::catalog(),
            PartijenTools::catalog(),
            StemwijzerTools::catalog(),
            StemmentrackerTools::catalog(),
            NieuwsTools::catalog(),
            CommunityTools::catalog(),
            VerkiezingenTools::catalog(),
            SearchTools::catalog(),
            AnalyticsTools::catalog(),
        );

        self::$cache = self::dedupeByName($tools);
        return self::$cache;
    }

    public static function find(string $name): ?array
    {
        foreach (self::catalog() as $t) {
            if ($t['name'] === $name) return $t;
        }
        return null;
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * Verwijdert dubbele tools (laatste registratie wint), en behoudt de
     * volgorde.
     *
     * @param array<int, array<string, mixed>> $tools
     * @return array<int, array<string, mixed>>
     */
    private static function dedupeByName(array $tools): array
    {
        $byName = [];
        foreach ($tools as $t) {
            $byName[$t['name']] = $t;
        }
        return array_values($byName);
    }
}
