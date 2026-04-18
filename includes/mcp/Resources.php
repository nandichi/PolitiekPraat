<?php
/**
 * MCP Resources voor PolitiekPraat.
 *
 * Resources zijn read-only data-objecten die agents direct kunnen ophalen
 * via een URI (bv. `blog://klimaat-2026`). De server presenteert ze in
 * `resources/list` en serveert hun inhoud in `resources/read`.
 *
 * Naast statische resources (`schema://*`) ondersteunen we ook
 * "templated" resources die dynamisch worden opgehaald uit de database
 * via een URI-pattern.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

require_once __DIR__ . '/McpException.php';

use Database;
use Throwable;

final class Resources
{
    /**
     * Lijst van vaste resources die altijd zichtbaar zijn in resources/list.
     * Templated resources verschijnen als hint via `resourceTemplates` in
     * de server-card / capabilities, en worden direct gelezen via URI.
     *
     * @return array<int, array<string,mixed>>
     */
    public static function list(): array
    {
        return [
            [
                'uri'         => 'schema://blogs',
                'name'        => 'Blogs DB schema',
                'description' => 'JSON-beschrijving van de relevante kolommen op de tabel `blogs` voor het schrijven en publiceren van blogs.',
                'mimeType'    => 'application/json',
            ],
            [
                'uri'         => 'schema://political_parties',
                'name'        => 'Political parties DB schema',
                'description' => 'JSON-beschrijving van de tabel `political_parties` (kolommen + voorbeeld).',
                'mimeType'    => 'application/json',
            ],
            [
                'uri'         => 'schema://stemmentracker',
                'name'        => 'Stemmentracker DB schema',
                'description' => 'JSON-beschrijving van de Stemmentracker-tabellen.',
                'mimeType'    => 'application/json',
            ],
            [
                'uri'         => 'guide://blog-writing',
                'name'        => 'Gids: blog schrijven via MCP',
                'description' => 'Stap-voor-stap workflow om autonoom een blog te schrijven, illustreren en publiceren via de MCP-tools.',
                'mimeType'    => 'text/markdown',
            ],
        ];
    }

    /**
     * URI-templates die agents helpen dynamische resources te ontdekken.
     * Volgt RFC 6570 (level-1 substitution: {var}).
     *
     * @return array<int, array<string,mixed>>
     */
    public static function templates(): array
    {
        return [
            ['uriTemplate' => 'blog://{slug}',     'name' => 'Blog by slug',          'mimeType' => 'application/json'],
            ['uriTemplate' => 'partij://{key}',    'name' => 'Partij by party_key',   'mimeType' => 'application/json'],
            ['uriTemplate' => 'motie://{id}',      'name' => 'Motie by id',           'mimeType' => 'application/json'],
            ['uriTemplate' => 'stelling://{id}',   'name' => 'PartijMeter-stelling',  'mimeType' => 'application/json'],
            ['uriTemplate' => 'thema://{id}',      'name' => 'Stemmentracker-thema',  'mimeType' => 'application/json'],
            ['uriTemplate' => 'nieuws://{id}',     'name' => 'Nieuwsartikel by id',   'mimeType' => 'application/json'],
        ];
    }

    /**
     * Leest één resource. Retourneert een MCP `resources/read` payload met
     * `contents = [{uri, mimeType, text|blob}]`.
     *
     * @return array<int, array<string,mixed>>
     */
    public static function read(string $uri): array
    {
        if (!preg_match('#^([a-z]+)://(.+)$#i', $uri, $m)) {
            throw new McpException(-32602, 'invalid_resource_uri');
        }
        $scheme = strtolower($m[1]);
        $path   = $m[2];

        switch ($scheme) {
            case 'schema': return self::readSchema($uri, $path);
            case 'guide':  return self::readGuide($uri, $path);
            case 'blog':   return self::readBlog($uri, $path);
            case 'partij': return self::readPartij($uri, $path);
            case 'motie':  return self::readMotie($uri, $path);
            case 'stelling':return self::readStelling($uri, $path);
            case 'thema':  return self::readThema($uri, $path);
            case 'nieuws': return self::readNieuws($uri, $path);
        }
        throw new McpException(-32601, 'unknown_resource_scheme: ' . $scheme);
    }

    // ------------------ READERS ------------------

    /** @return array<int, array<string,mixed>> */
    private static function readSchema(string $uri, string $name): array
    {
        $schemas = [
            'blogs' => [
                'table'   => 'blogs',
                'columns' => [
                    'id', 'title', 'slug', 'content', 'summary', 'excerpt', 'image_path',
                    'category_id', 'author_id', 'tags (JSON array)',
                    'status (draft|published|scheduled)', 'scheduled_for', 'published_at',
                    'updated_at', 'created_at', 'views', 'likes', 'reading_time',
                    'seo_title', 'seo_description', 'meta_robots', 'canonical_url',
                ],
                'notes' => [
                    'Filter publieke views altijd op status="published" AND published_at <= NOW().',
                    'Tags zijn JSON-array van strings.',
                    'Slug moet uniek zijn — gebruik `create_blog_draft` om automatisch te uniceren.',
                ],
            ],
            'political_parties' => [
                'table'   => 'political_parties',
                'columns' => [
                    'id', 'party_key (slug-achtig)', 'name', 'leader', 'logo', 'leader_photo',
                    'color', 'current_seats', 'tk2023_seats', 'is_active',
                    'standpoints (JSON)', 'polling (JSON)', 'perspectives (JSON)', 'leader_info',
                ],
            ],
            'stemmentracker' => [
                'tables' => [
                    'stemmentracker_moties (Tweede-Kamer moties)',
                    'stemmentracker_themas (themas)',
                    'stemmentracker_motie_themas (m2m)',
                    'stemmentracker_votes (party_id + vote: voor/tegen/...)',
                ],
            ],
        ];
        if (!isset($schemas[$name])) throw new McpException(-32001, 'schema_not_found');

        return [[
            'uri'      => $uri,
            'mimeType' => 'application/json',
            'text'     => json_encode($schemas[$name], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readGuide(string $uri, string $name): array
    {
        $guides = [
            'blog-writing' => <<<MD
# Blog schrijven via MCP

Workflow voor een autonome agent:

1. Inspireer je via `list_nieuws` of `search_all` voor actuele onderwerpen.
2. Optioneel: `get_partij` of `get_partij_stemgedrag` voor onderbouwing.
3. (Optioneel) `generate_blog_image` of `upload_media_from_url` voor een
   featured image. Onthoud het `image_path` voor stap 4.
4. `create_blog_draft` met `title`, `content` (markdown), `summary`,
   `category_slug`, `tags`, en optioneel `image_path`, `seo_title`,
   `seo_description`. Retourneert `id` en `slug`.
5. Reviewen: `get_blog` met de slug (let op: voor drafts via `?preview=1`).
6. Publiceren met `publish_blog` of inplannen met `schedule_blog`.
7. Achteraf: `get_blog_analytics` voor views/likes/comments.

## Best practices
- Schrijf neutraal en feitelijk; baseer claims op moties/standpunten.
- Voeg interne links toe naar relevante partijen / moties / blogs.
- Reading time wordt automatisch berekend, summary ook (max 200 tekens).
MD,
        ];
        if (!isset($guides[$name])) throw new McpException(-32001, 'guide_not_found');
        return [[
            'uri'      => $uri,
            'mimeType' => 'text/markdown',
            'text'     => $guides[$name],
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readBlog(string $uri, string $slug): array
    {
        $db = new Database();
        $db->query("SELECT id, title, slug, summary, content, image_path, published_at, views, likes,
                           category_id, tags, reading_time, seo_title, seo_description
                    FROM blogs WHERE slug = :s AND status='published' AND published_at <= NOW() LIMIT 1");
        $db->bind(':s', $slug);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'blog_not_found');
        return [[
            'uri' => $uri,
            'mimeType' => 'application/json',
            'text' => json_encode((array) $row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readPartij(string $uri, string $key): array
    {
        $db = new Database();
        $db->query('SELECT * FROM political_parties WHERE party_key = :k LIMIT 1');
        $db->bind(':k', $key);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'partij_not_found');
        $out = (array) $row;
        foreach (['standpoints', 'polling', 'perspectives'] as $c) {
            if (isset($out[$c]) && is_string($out[$c]) && $out[$c] !== '') {
                $dec = json_decode($out[$c], true);
                if (is_array($dec)) $out[$c] = $dec;
            }
        }
        return [[
            'uri' => $uri, 'mimeType' => 'application/json',
            'text' => json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readMotie(string $uri, string $idStr): array
    {
        $db = new Database();
        $id = (int) $idStr;
        if ($id <= 0) throw new McpException(-32602, 'invalid_id');
        $db->query('SELECT * FROM stemmentracker_moties WHERE id = :i LIMIT 1');
        $db->bind(':i', $id);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'motie_not_found');
        return [[
            'uri' => $uri, 'mimeType' => 'application/json',
            'text' => json_encode((array) $row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readStelling(string $uri, string $idStr): array
    {
        $db = new Database();
        $id = (int) $idStr;
        if ($id <= 0) throw new McpException(-32602, 'invalid_id');
        $db->query('SELECT * FROM stemwijzer_questions WHERE id = :i LIMIT 1');
        $db->bind(':i', $id);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'stelling_not_found');
        return [[
            'uri' => $uri, 'mimeType' => 'application/json',
            'text' => json_encode((array) $row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readThema(string $uri, string $idStr): array
    {
        $db = new Database();
        $id = (int) $idStr;
        if ($id <= 0) throw new McpException(-32602, 'invalid_id');
        $db->query('SELECT * FROM stemmentracker_themas WHERE id = :i LIMIT 1');
        $db->bind(':i', $id);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'thema_not_found');
        return [[
            'uri' => $uri, 'mimeType' => 'application/json',
            'text' => json_encode((array) $row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }

    /** @return array<int, array<string,mixed>> */
    private static function readNieuws(string $uri, string $idStr): array
    {
        $db = new Database();
        $id = (int) $idStr;
        if ($id <= 0) throw new McpException(-32602, 'invalid_id');
        $db->query('SELECT * FROM news_articles WHERE id = :i LIMIT 1');
        $db->bind(':i', $id);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'news_not_found');
        return [[
            'uri' => $uri, 'mimeType' => 'application/json',
            'text' => json_encode((array) $row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]];
    }
}
