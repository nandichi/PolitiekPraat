<?php
/**
 * MCP tools voor globale zoek over PolitiekPraat-content.
 *
 * `search_all` doorzoekt blogs, forum-topics, nieuws, partijen en moties
 * tegelijk en geeft een uniforme resultatenlijst.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';

use Database;
use PolitiekPraat\MCP\ToolBuilder;
use Throwable;

final class SearchTools
{
    private const KNOWN_TYPES = ['blog', 'forum', 'nieuws', 'partij', 'motie'];

    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'search_all',
                'Doorzoek blogs, forum, nieuws, partijen en moties tegelijk. Retourneert max `limit` resultaten per type, met url + snippet.',
                [
                    'type' => 'object',
                    'properties' => [
                        'query' => ['type' => 'string', 'minLength' => 2, 'maxLength' => 200],
                        'types' => [
                            'type' => 'array',
                            'items' => ['type' => 'string', 'enum' => self::KNOWN_TYPES],
                            'maxItems' => 5,
                            'description' => 'Beperk tot deze content-types. Default = alles.',
                        ],
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 25, 'default' => 5],
                    ],
                    'required' => ['query'],
                    'additionalProperties' => false,
                ],
                [self::class, 'search_all']
            ),
        ];
    }

    public static function search_all(array $args, ?array $ctx): array
    {
        $q     = (string) $args['query'];
        $types = !empty($args['types']) ? array_intersect((array) $args['types'], self::KNOWN_TYPES) : self::KNOWN_TYPES;
        $limit = max(1, min(25, (int) ($args['limit'] ?? 5)));
        $db    = new Database();
        $like  = '%' . $q . '%';

        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : 'https://politiekpraat.nl';
        $results = [];

        if (in_array('blog', $types, true)) {
            try {
                $db->query("SELECT id, title, slug, summary FROM blogs
                            WHERE status='published' AND published_at <= NOW()
                              AND (title LIKE :q OR summary LIKE :q OR content LIKE :q)
                            ORDER BY published_at DESC LIMIT {$limit}");
                $db->bind(':q', $like);
                foreach ($db->resultSet() ?: [] as $r) {
                    $results[] = [
                        'type'    => 'blog',
                        'id'      => (int) $r->id,
                        'title'   => $r->title,
                        'snippet' => self::trimSnippet((string) ($r->summary ?? '')),
                        'url'     => $base . '/blog/' . $r->slug,
                    ];
                }
            } catch (Throwable $e) { /* skip */ }
        }

        if (in_array('forum', $types, true)) {
            try {
                $db->query("SELECT id, title, content FROM forum_topics
                            WHERE title LIKE :q OR content LIKE :q
                            ORDER BY created_at DESC LIMIT {$limit}");
                $db->bind(':q', $like);
                foreach ($db->resultSet() ?: [] as $r) {
                    $results[] = [
                        'type'    => 'forum',
                        'id'      => (int) $r->id,
                        'title'   => $r->title,
                        'snippet' => self::trimSnippet((string) $r->content),
                        'url'     => $base . '/forum/topic/' . $r->id,
                    ];
                }
            } catch (Throwable $e) { /* skip */ }
        }

        if (in_array('nieuws', $types, true)) {
            try {
                $db->query("SELECT id, title, description, url, source FROM news_articles
                            WHERE title LIKE :q OR description LIKE :q
                            ORDER BY published_at DESC LIMIT {$limit}");
                $db->bind(':q', $like);
                foreach ($db->resultSet() ?: [] as $r) {
                    $results[] = [
                        'type'    => 'nieuws',
                        'id'      => (int) $r->id,
                        'title'   => $r->title,
                        'source'  => $r->source,
                        'snippet' => self::trimSnippet((string) ($r->description ?? '')),
                        'url'     => (string) $r->url,
                    ];
                }
            } catch (Throwable $e) { /* skip */ }
        }

        if (in_array('partij', $types, true)) {
            try {
                $db->query("SELECT id, party_key, name, leader FROM political_parties
                            WHERE is_active = 1 AND (name LIKE :q OR leader LIKE :q OR party_key LIKE :q)
                            ORDER BY current_seats DESC LIMIT {$limit}");
                $db->bind(':q', $like);
                foreach ($db->resultSet() ?: [] as $r) {
                    $results[] = [
                        'type'    => 'partij',
                        'id'      => (int) $r->id,
                        'title'   => $r->name,
                        'snippet' => 'Lijsttrekker: ' . ($r->leader ?? '-'),
                        'url'     => $base . '/partij/' . $r->party_key,
                    ];
                }
            } catch (Throwable $e) { /* skip */ }
        }

        if (in_array('motie', $types, true)) {
            try {
                $db->query("SELECT id, title, onderwerp, datum_stemming, uitslag FROM stemmentracker_moties
                            WHERE is_active = 1 AND (title LIKE :q OR onderwerp LIKE :q OR description LIKE :q)
                            ORDER BY datum_stemming DESC LIMIT {$limit}");
                $db->bind(':q', $like);
                foreach ($db->resultSet() ?: [] as $r) {
                    $results[] = [
                        'type'    => 'motie',
                        'id'      => (int) $r->id,
                        'title'   => $r->title,
                        'snippet' => 'Onderwerp: ' . ($r->onderwerp ?? '-') . ' (uitslag: ' . ($r->uitslag ?? '-') . ')',
                        'url'     => $base . '/stemmentracker?motie=' . (int) $r->id,
                    ];
                }
            } catch (Throwable $e) { /* skip */ }
        }

        return [
            'query'   => $q,
            'count'   => count($results),
            'results' => $results,
        ];
    }

    private static function trimSnippet(string $text, int $max = 220): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
        if ($text === '') return '';
        if (mb_strlen($text) <= $max) return $text;
        return mb_substr($text, 0, $max) . '...';
    }
}
