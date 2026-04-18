<?php
/**
 * MCP tools voor de nieuwsfeed van PolitiekPraat (tabel `news_articles`).
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use Database;
use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;

final class NieuwsTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'list_nieuws',
                'Lijst van recent politiek nieuws uit aggregator, met filters op bron, bias, oriëntatie en zoekterm.',
                [
                    'type' => 'object',
                    'properties' => [
                        'search'      => ['type' => 'string'],
                        'source'      => ['type' => 'string'],
                        'bias'        => ['type' => 'string', 'description' => 'bv. links, rechts, centrum'],
                        'orientation' => ['type' => 'string'],
                        'since'       => ['type' => 'string', 'description' => 'ISO-8601 datum'],
                        'until'       => ['type' => 'string'],
                        'limit'       => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset'      => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_nieuws']
            ),

            ToolBuilder::read(
                'get_nieuws',
                'Detail van één nieuwsartikel.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'minimum' => 1],
                    ],
                    'required' => ['id'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_nieuws']
            ),

            ToolBuilder::read(
                'list_news_sources',
                'Unieke nieuwsbronnen met aantallen artikelen, bias en oriëntatie.',
                [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                    'additionalProperties' => false,
                ],
                [self::class, 'list_news_sources']
            ),
        ];
    }

    public static function list_nieuws(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));

        $where  = [];
        $params = [];
        if (!empty($args['search'])) {
            $where[] = '(title LIKE :q OR description LIKE :q)';
            $params[':q'] = '%' . $args['search'] . '%';
        }
        if (!empty($args['source'])) {
            $where[] = 'source = :s';
            $params[':s'] = (string) $args['source'];
        }
        if (!empty($args['bias'])) {
            $where[] = 'bias = :b';
            $params[':b'] = (string) $args['bias'];
        }
        if (!empty($args['orientation'])) {
            $where[] = 'orientation = :o';
            $params[':o'] = (string) $args['orientation'];
        }
        if (!empty($args['since'])) {
            $where[] = 'published_at >= :since';
            $params[':since'] = (string) $args['since'];
        }
        if (!empty($args['until'])) {
            $where[] = 'published_at < :until';
            $params[':until'] = (string) $args['until'];
        }

        $sql = 'SELECT id, title, description, url, source, bias, orientation, published_at
                FROM news_articles';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= " ORDER BY published_at DESC LIMIT {$limit} OFFSET {$offset}";

        $db->query($sql);
        foreach ($params as $k => $v) $db->bind($k, $v);
        $rows = $db->resultSet() ?: [];
        return ['items' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_nieuws(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM news_articles WHERE id = :i LIMIT 1');
        $db->bind(':i', (int) $args['id']);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'news_not_found');
        return (array) $row;
    }

    public static function list_news_sources(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT source, bias, orientation, COUNT(*) AS count
                    FROM news_articles
                    GROUP BY source, bias, orientation
                    ORDER BY count DESC');
        $rows = $db->resultSet() ?: [];
        return ['sources' => array_map(static fn($r) => (array) $r, $rows)];
    }
}
