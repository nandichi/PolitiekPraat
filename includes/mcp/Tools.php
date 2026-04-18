<?php
/**
 * MCP tool-registry voor PolitiekPraat.
 *
 * Iedere tool heeft een naam, beschrijving, JSON-schema, required scope(s)
 * en een handler. De handler krijgt de gevalideerde arguments en een
 * auth-context (of null voor anonieme toegang).
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

require_once __DIR__ . '/../oauth/Scopes.php';

use Database;
use PolitiekPraat\OAuth\Scopes;
use Throwable;

class Tools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            self::readonly('list_blogs', 'Lijst politieke blogs. Optioneel filter met `search`.', [
                'type' => 'object',
                'properties' => [
                    'search' => ['type' => 'string'],
                    'limit'  => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10],
                    'offset' => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                ],
                'additionalProperties' => false,
            ], [self::class, 'list_blogs']),

            self::readonly('get_blog', 'Haal één blog op aan de hand van slug.', [
                'type' => 'object',
                'properties' => ['slug' => ['type' => 'string', 'minLength' => 1]],
                'required' => ['slug'],
                'additionalProperties' => false,
            ], [self::class, 'get_blog']),

            self::readonly('list_partijen', 'Lijst van Nederlandse politieke partijen.', [
                'type' => 'object',
                'properties' => new \stdClass(),
                'additionalProperties' => false,
            ], [self::class, 'list_partijen']),

            self::readonly('get_partij', 'Detail over een politieke partij.', [
                'type' => 'object',
                'properties' => ['slug' => ['type' => 'string', 'minLength' => 1]],
                'required' => ['slug'],
                'additionalProperties' => false,
            ], [self::class, 'get_partij']),

            self::readonly('list_themas', "Lijst politieke thema's.", [
                'type' => 'object',
                'properties' => new \stdClass(),
                'additionalProperties' => false,
            ], [self::class, 'list_themas']),

            self::readonly('get_thema', 'Detail over een thema.', [
                'type' => 'object',
                'properties' => ['slug' => ['type' => 'string', 'minLength' => 1]],
                'required' => ['slug'],
                'additionalProperties' => false,
            ], [self::class, 'get_thema']),

            self::readonly('list_nieuws', 'Recent politiek nieuws.', [
                'type' => 'object',
                'properties' => [
                    'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10],
                ],
                'additionalProperties' => false,
            ], [self::class, 'list_nieuws']),

            self::readonly('list_moties', 'Lijst moties uit de stemmentracker.', [
                'type' => 'object',
                'properties' => [
                    'limit'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10],
                    'onderwerp'  => ['type' => 'string'],
                ],
                'additionalProperties' => false,
            ], [self::class, 'list_moties']),

            self::readonly('get_partijmeter_stellingen', 'Haal de PartijMeter stellingen op.', [
                'type' => 'object',
                'properties' => new \stdClass(),
                'additionalProperties' => false,
            ], [self::class, 'get_partijmeter_stellingen']),

            // Write tools (scope mcp.write + ingelogde user)
            self::writeTool('post_comment', 'Plaats een reactie op een blog namens de ingelogde gebruiker.', [
                'type' => 'object',
                'properties' => [
                    'blog_slug' => ['type' => 'string', 'minLength' => 1],
                    'content'   => ['type' => 'string', 'minLength' => 2, 'maxLength' => 5000],
                ],
                'required' => ['blog_slug', 'content'],
                'additionalProperties' => false,
            ], [self::class, 'post_comment']),

            self::writeTool('post_forum_topic', 'Plaats een nieuw topic in het forum.', [
                'type' => 'object',
                'properties' => [
                    'title'   => ['type' => 'string', 'minLength' => 5, 'maxLength' => 255],
                    'content' => ['type' => 'string', 'minLength' => 10, 'maxLength' => 20000],
                ],
                'required' => ['title', 'content'],
                'additionalProperties' => false,
            ], [self::class, 'post_forum_topic']),

            self::writeTool('save_partijmeter_result', 'Sla antwoorden op de PartijMeter op en retourneer de share-ID.', [
                'type' => 'object',
                'properties' => [
                    'answers' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'question_id' => ['type' => 'integer'],
                                'answer'      => ['type' => 'string', 'enum' => ['eens', 'oneens', 'geen_mening']],
                                'weight'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 3],
                            ],
                            'required' => ['question_id', 'answer'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
                'required' => ['answers'],
                'additionalProperties' => false,
            ], [self::class, 'save_partijmeter_result']),
        ];
    }

    private static function readonly(string $name, string $description, array $schema, callable $handler): array
    {
        return [
            'name' => $name,
            'description' => $description,
            'inputSchema' => $schema,
            'scopes' => [Scopes::MCP_READ],
            'public' => true,
            'handler' => $handler,
        ];
    }

    private static function writeTool(string $name, string $description, array $schema, callable $handler): array
    {
        return [
            'name' => $name,
            'description' => $description,
            'inputSchema' => $schema,
            'scopes' => [Scopes::MCP_WRITE],
            'public' => false,
            'handler' => $handler,
        ];
    }

    // ----------- Handlers -----------------

    public static function list_blogs(array $args, ?array $ctx): array
    {
        $db = new Database();
        $search = trim((string) ($args['search'] ?? ''));
        $limit  = (int) ($args['limit'] ?? 10);
        $offset = (int) ($args['offset'] ?? 0);

        $sql = 'SELECT id, title, slug, summary, published_at FROM blogs';
        if ($search !== '') {
            $sql .= ' WHERE title LIKE :q OR content LIKE :q';
        }
        $sql .= ' ORDER BY published_at DESC LIMIT ' . max(1, min(50, $limit)) . ' OFFSET ' . max(0, $offset);

        $db->query($sql);
        if ($search !== '') {
            $db->bind(':q', '%' . $search . '%');
        }
        $rows = $db->resultSet() ?: [];
        return ['blogs' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_blog(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT id, title, slug, summary, content, published_at, views FROM blogs WHERE slug = :s LIMIT 1');
        $db->bind(':s', (string) $args['slug']);
        $row = $db->single();
        if (!$row) {
            throw new McpException(-32001, 'blog_not_found');
        }
        return (array) $row;
    }

    public static function list_partijen(array $args, ?array $ctx): array
    {
        $db = new Database();
        try {
            $db->query('SELECT id, name, slug FROM political_parties ORDER BY name');
            $rows = $db->resultSet() ?: [];
            return ['partijen' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['partijen' => [], 'error' => 'table_not_available'];
        }
    }

    public static function get_partij(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM political_parties WHERE slug = :s LIMIT 1');
        $db->bind(':s', (string) $args['slug']);
        $row = $db->single();
        if (!$row) {
            throw new McpException(-32001, 'partij_not_found');
        }
        return (array) $row;
    }

    public static function list_themas(array $args, ?array $ctx): array
    {
        $db = new Database();
        try {
            $db->query('SELECT id, name, slug FROM stemwijzer_themes ORDER BY name');
            $rows = $db->resultSet() ?: [];
            return ['themas' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['themas' => []];
        }
    }

    public static function get_thema(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM stemwijzer_themes WHERE slug = :s LIMIT 1');
        $db->bind(':s', (string) $args['slug']);
        $row = $db->single();
        if (!$row) {
            throw new McpException(-32001, 'thema_not_found');
        }
        return (array) $row;
    }

    public static function list_nieuws(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit = max(1, min(50, (int) ($args['limit'] ?? 10)));
        try {
            $db->query('SELECT id, title, url, source, published_at FROM news_articles ORDER BY published_at DESC LIMIT ' . $limit);
            $rows = $db->resultSet() ?: [];
            return ['items' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['items' => []];
        }
    }

    public static function list_moties(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit = max(1, min(50, (int) ($args['limit'] ?? 10)));
        $where = '';
        if (!empty($args['onderwerp'])) {
            $where = ' WHERE onderwerp LIKE :q';
        }
        try {
            $db->query('SELECT id, titel, onderwerp, datum FROM moties' . $where . ' ORDER BY datum DESC LIMIT ' . $limit);
            if ($where !== '') {
                $db->bind(':q', '%' . $args['onderwerp'] . '%');
            }
            $rows = $db->resultSet() ?: [];
            return ['moties' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['moties' => [], 'error' => 'table_not_available'];
        }
    }

    public static function get_partijmeter_stellingen(array $args, ?array $ctx): array
    {
        $db = new Database();
        try {
            $db->query('SELECT id, question, theme_id FROM stemwijzer_questions ORDER BY id');
            $rows = $db->resultSet() ?: [];
            return ['stellingen' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['stellingen' => []];
        }
    }

    public static function post_comment(array $args, ?array $ctx): array
    {
        $userId = $ctx['user_id'] ?? null;
        if ($userId === null) {
            throw new McpException(-32002, 'authentication_required');
        }

        $db = new Database();
        $db->query('SELECT id FROM blogs WHERE slug = :s LIMIT 1');
        $db->bind(':s', (string) $args['blog_slug']);
        $blog = $db->single();
        if (!$blog) {
            throw new McpException(-32001, 'blog_not_found');
        }

        $db->query('INSERT INTO comments (blog_id, user_id, content) VALUES (:b, :u, :c)');
        $db->bind(':b', (int) $blog->id);
        $db->bind(':u', (int) $userId);
        $db->bind(':c', (string) $args['content']);
        $db->execute();

        return ['ok' => true, 'comment_id' => (int) $db->lastInsertId()];
    }

    public static function post_forum_topic(array $args, ?array $ctx): array
    {
        $userId = $ctx['user_id'] ?? null;
        if ($userId === null) {
            throw new McpException(-32002, 'authentication_required');
        }

        $db = new Database();
        $db->query('INSERT INTO forum_topics (title, content, author_id) VALUES (:t, :c, :a)');
        $db->bind(':t', (string) $args['title']);
        $db->bind(':c', (string) $args['content']);
        $db->bind(':a', (int) $userId);
        $db->execute();

        return ['ok' => true, 'topic_id' => (int) $db->lastInsertId()];
    }

    public static function save_partijmeter_result(array $args, ?array $ctx): array
    {
        $db = new Database();
        $shareId = bin2hex(random_bytes(8));
        $userId = $ctx['user_id'] ?? null;

        try {
            $db->query('INSERT INTO stemwijzer_results (share_id, user_id, answers, created_at)
                        VALUES (:s, :u, :a, NOW())');
            $db->bind(':s', $shareId);
            $db->bind(':u', $userId);
            $db->bind(':a', json_encode($args['answers']));
            $db->execute();
        } catch (Throwable $e) {
            throw new McpException(-32003, 'storage_error: ' . $e->getMessage());
        }

        return ['ok' => true, 'share_id' => $shareId];
    }
}
