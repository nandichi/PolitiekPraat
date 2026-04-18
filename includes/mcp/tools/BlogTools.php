<?php
/**
 * MCP tools voor het blog-CMS van PolitiekPraat.
 *
 * - Read-only tools zijn publiek (mcp.read).
 * - Write-tools vereisen OAuth scope `mcp.write` + `blogs.write` en
 *   een ingelogde user.
 * - `blogs.admin` scope laat de agent blogs van andere auteurs
 *   beheren/verwijderen/publiceren.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use Database;
use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;
use PolitiekPraat\OAuth\Scopes;
use Throwable;

final class BlogTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            // ---------- READ ----------
            ToolBuilder::read(
                'list_blogs',
                'Lijst (gepubliceerde) blogs van PolitiekPraat, met filters op zoekterm, categorie, tag, auteur en datum.',
                [
                    'type' => 'object',
                    'properties' => [
                        'search'       => ['type' => 'string'],
                        'category_id'  => ['type' => 'integer'],
                        'category_slug'=> ['type' => 'string'],
                        'tag'          => ['type' => 'string'],
                        'author_id'    => ['type' => 'integer'],
                        'since'        => ['type' => 'string', 'description' => 'ISO-8601 datum (>=)'],
                        'until'        => ['type' => 'string', 'description' => 'ISO-8601 datum (<)'],
                        'limit'        => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset'       => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                        'order'        => ['type' => 'string', 'enum' => ['newest', 'oldest', 'popular', 'trending'], 'default' => 'newest'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_blogs']
            ),

            ToolBuilder::read(
                'get_blog',
                'Haal één blog op aan de hand van slug of id. Retourneert volledige content, categorie, tags, SEO-velden, poll en aantallen (views/likes/comments).',
                [
                    'type' => 'object',
                    'properties' => [
                        'slug' => ['type' => 'string', 'minLength' => 1],
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'include_comments' => ['type' => 'boolean', 'default' => false],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_blog']
            ),

            ToolBuilder::read(
                'list_blog_categories',
                'Lijst van alle beschikbare blog-categorieën met id, naam, slug en kleur.',
                [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                    'additionalProperties' => false,
                ],
                [self::class, 'list_blog_categories']
            ),

            ToolBuilder::read(
                'list_blog_tags',
                'Unieke tags die in bestaande blogs voorkomen, gesorteerd op frequentie.',
                [
                    'type' => 'object',
                    'properties' => [
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200, 'default' => 50],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_blog_tags']
            ),

            // ---------- WRITE (auteur) ----------
            ToolBuilder::write(
                'create_blog_draft',
                'Maak een nieuwe blog aan als draft (niet gepubliceerd). Retourneert id en slug. Gebruik daarna `publish_blog` of `schedule_blog` om hem live te zetten.',
                [
                    'type' => 'object',
                    'properties' => [
                        'title'           => ['type' => 'string', 'minLength' => 3, 'maxLength' => 255],
                        'content'         => ['type' => 'string', 'minLength' => 20, 'description' => 'Markdown-body'],
                        'summary'         => ['type' => 'string', 'maxLength' => 500, 'description' => 'Korte introductie (max ~200 tekens aanbevolen).'],
                        'excerpt'         => ['type' => 'string', 'maxLength' => 500],
                        'slug'            => ['type' => 'string', 'description' => 'Optioneel; auto-gegenereerd uit title.'],
                        'category_id'     => ['type' => 'integer', 'minimum' => 1],
                        'category_slug'   => ['type' => 'string'],
                        'tags'            => ['type' => 'array', 'items' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 50], 'maxItems' => 20],
                        'image_path'      => ['type' => 'string', 'description' => "Pad naar een eerder geüploade image (bv. 'uploads/blogs/images/foo.jpg'). Gebruik `upload_media_from_url` of `generate_blog_image` om er een te krijgen."],
                        'seo_title'       => ['type' => 'string', 'maxLength' => 255],
                        'seo_description' => ['type' => 'string', 'maxLength' => 320],
                        'meta_robots'     => ['type' => 'string', 'maxLength' => 100],
                        'canonical_url'   => ['type' => 'string', 'maxLength' => 500],
                    ],
                    'required' => ['title', 'content'],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'create_blog_draft']
            ),

            ToolBuilder::write(
                'update_blog',
                'Werk een bestaande blog bij. Alle velden zijn optioneel; alleen meegegeven velden worden gewijzigd. Controleert of de agent-user auteur is (of `blogs.admin` scope heeft).',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'              => ['type' => 'integer', 'minimum' => 1],
                        'slug'            => ['type' => 'string', 'minLength' => 1],
                        'title'           => ['type' => 'string', 'minLength' => 3, 'maxLength' => 255],
                        'content'         => ['type' => 'string', 'minLength' => 20],
                        'summary'         => ['type' => 'string', 'maxLength' => 500],
                        'excerpt'         => ['type' => 'string', 'maxLength' => 500],
                        'new_slug'        => ['type' => 'string', 'maxLength' => 255],
                        'category_id'     => ['type' => 'integer', 'minimum' => 1],
                        'tags'            => ['type' => 'array', 'items' => ['type' => 'string'], 'maxItems' => 20],
                        'image_path'      => ['type' => 'string'],
                        'seo_title'       => ['type' => 'string', 'maxLength' => 255],
                        'seo_description' => ['type' => 'string', 'maxLength' => 320],
                        'meta_robots'     => ['type' => 'string'],
                        'canonical_url'   => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'update_blog']
            ),

            ToolBuilder::write(
                'publish_blog',
                'Zet een blog (draft of scheduled) direct online: status=published, published_at=NOW().',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'slug' => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'publish_blog']
            ),

            ToolBuilder::write(
                'schedule_blog',
                'Plan een blog in om op een toekomstig moment gepubliceerd te worden (status=scheduled + scheduled_for). De cron `scripts/publish-scheduled-blogs.php` zet hem daarna automatisch live.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'            => ['type' => 'integer', 'minimum' => 1],
                        'slug'          => ['type' => 'string'],
                        'scheduled_for' => ['type' => 'string', 'description' => 'ISO-8601 datetime in de toekomst (Europe/Amsterdam).'],
                    ],
                    'required' => ['scheduled_for'],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'schedule_blog']
            ),

            ToolBuilder::write(
                'unpublish_blog',
                'Zet een gepubliceerde blog terug naar draft-status. De blog blijft bestaan maar is niet meer publiek zichtbaar.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'slug' => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'unpublish_blog']
            ),

            ToolBuilder::write(
                'delete_blog',
                'Verwijder een blog permanent. Alleen auteur of iemand met `blogs.admin` scope.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'slug' => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'delete_blog']
            ),

            ToolBuilder::write(
                'set_blog_category',
                'Wijzig de categorie van een blog.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'          => ['type' => 'integer', 'minimum' => 1],
                        'slug'        => ['type' => 'string'],
                        'category_id' => ['type' => 'integer', 'minimum' => 1],
                    ],
                    'required' => ['category_id'],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'set_blog_category']
            ),

            ToolBuilder::write(
                'set_blog_tags',
                'Overschrijf de tags van een blog (max 20).',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'slug' => ['type' => 'string'],
                        'tags' => ['type' => 'array', 'items' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 50], 'maxItems' => 20],
                    ],
                    'required' => ['tags'],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'set_blog_tags']
            ),

            ToolBuilder::write(
                'set_blog_featured_image',
                'Wijzig de featured image van een blog. Pad moet beginnen met `uploads/blogs/images/` of een externe https-URL zijn.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'         => ['type' => 'integer', 'minimum' => 1],
                        'slug'       => ['type' => 'string'],
                        'image_path' => ['type' => 'string', 'minLength' => 3],
                    ],
                    'required' => ['image_path'],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'set_blog_featured_image']
            ),

            ToolBuilder::write(
                'duplicate_blog',
                'Kopieer een bestaande blog naar een nieuwe draft (handig voor A/B varianten of her-publicatie).',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'    => ['type' => 'integer', 'minimum' => 1],
                        'slug'  => ['type' => 'string'],
                        'title' => ['type' => 'string', 'description' => 'Nieuwe titel; anders wordt "(kopie)" aan de oude titel geplakt.'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'duplicate_blog']
            ),

            ToolBuilder::write(
                'list_drafts',
                'Lijst van eigen drafts (niet gepubliceerde blogs). Admins zien alle drafts.',
                [
                    'type' => 'object',
                    'properties' => [
                        'limit'  => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset' => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'list_drafts']
            ),

            ToolBuilder::write(
                'list_scheduled_blogs',
                'Lijst van eigen ingeplande blogs. Admins zien alle scheduled blogs.',
                [
                    'type' => 'object',
                    'properties' => [
                        'limit'  => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset' => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE],
                [self::class, 'list_scheduled_blogs']
            ),

            ToolBuilder::write(
                'get_blog_analytics',
                'Analytics van een eigen blog: views, likes, aantal comments, evt. poll-stemmen. Admins kunnen analytics van elk blog opvragen.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'minimum' => 1],
                        'slug' => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::BLOGS_WRITE, Scopes::ANALYTICS_READ],
                [self::class, 'get_blog_analytics']
            ),
        ];
    }

    // ==========================================================
    //                         HANDLERS
    // ==========================================================

    // -------- READ --------

    public static function list_blogs(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));
        $order  = (string) ($args['order'] ?? 'newest');

        $where  = ["b.status = 'published'", "b.published_at <= NOW()"];
        $params = [];

        if (!empty($args['search'])) {
            $where[] = '(b.title LIKE :q OR b.content LIKE :q OR b.summary LIKE :q)';
            $params[':q'] = '%' . $args['search'] . '%';
        }
        if (!empty($args['category_id'])) {
            $where[] = 'b.category_id = :cat_id';
            $params[':cat_id'] = (int) $args['category_id'];
        }
        if (!empty($args['category_slug'])) {
            $where[] = 'c.slug = :cat_slug';
            $params[':cat_slug'] = (string) $args['category_slug'];
        }
        if (!empty($args['tag'])) {
            // JSON_CONTAINS voor JSON tag-array.
            $where[] = "JSON_CONTAINS(b.tags, JSON_QUOTE(:tag))";
            $params[':tag'] = (string) $args['tag'];
        }
        if (!empty($args['author_id'])) {
            $where[] = 'b.author_id = :aid';
            $params[':aid'] = (int) $args['author_id'];
        }
        if (!empty($args['since'])) {
            $where[] = 'b.published_at >= :since';
            $params[':since'] = (string) $args['since'];
        }
        if (!empty($args['until'])) {
            $where[] = 'b.published_at < :until';
            $params[':until'] = (string) $args['until'];
        }

        $orderBy = 'b.published_at DESC';
        if ($order === 'oldest')  $orderBy = 'b.published_at ASC';
        if ($order === 'popular') $orderBy = 'b.views DESC, b.published_at DESC';
        if ($order === 'trending') {
            // Eenvoudige trending: recente views + likes.
            $orderBy = '(b.views + b.likes * 5) / GREATEST(DATEDIFF(NOW(), b.published_at), 1) DESC';
        }

        $sql = "SELECT b.id, b.title, b.slug, b.summary, b.excerpt, b.image_path, b.published_at,
                       b.views, b.likes, b.reading_time, b.category_id, b.tags,
                       c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                       u.username AS author_name, u.id AS author_id
                FROM blogs b
                LEFT JOIN blog_categories c ON b.category_id = c.id
                JOIN users u ON b.author_id = u.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY {$orderBy}
                LIMIT {$limit} OFFSET {$offset}";

        try {
            $db->query($sql);
            foreach ($params as $k => $v) $db->bind($k, $v);
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) {
            throw new McpException(-32000, 'db_error: ' . $e->getMessage());
        }

        $countSql = "SELECT COUNT(*) AS total
                     FROM blogs b
                     LEFT JOIN blog_categories c ON b.category_id = c.id
                     WHERE " . implode(' AND ', $where);
        $db->query($countSql);
        foreach ($params as $k => $v) $db->bind($k, $v);
        $count = $db->single();
        $total = $count ? (int) $count->total : count($rows);

        return [
            'total'  => $total,
            'limit'  => $limit,
            'offset' => $offset,
            'blogs'  => array_map(static function ($r) {
                $a = (array) $r;
                if (isset($a['tags']) && is_string($a['tags']) && $a['tags'] !== '') {
                    $decoded = json_decode($a['tags'], true);
                    if (is_array($decoded)) $a['tags'] = $decoded;
                }
                return $a;
            }, $rows),
        ];
    }

    public static function get_blog(array $args, ?array $ctx): array
    {
        $db   = new Database();
        $row  = self::findBlog($db, $args, true); // alleen published voor publieke get
        if (!$row) throw new McpException(-32001, 'blog_not_found');

        $out = self::hydrateBlogRow($row);
        $out['url'] = self::blogUrl((string) $row->slug);

        if (!empty($args['include_comments'])) {
            $db->query("SELECT c.id, c.content, c.anonymous_name, c.created_at, u.username AS author_name
                        FROM comments c LEFT JOIN users u ON c.user_id = u.id
                        WHERE c.blog_id = :b ORDER BY c.created_at ASC LIMIT 100");
            $db->bind(':b', (int) $row->id);
            $comments = $db->resultSet() ?: [];
            $out['comments'] = array_map(static fn($c) => (array) $c, $comments);
        }

        // Poll
        $db->query("SELECT id, question, option_a, option_b, option_a_votes, option_b_votes, total_votes FROM blog_polls WHERE blog_id = :b LIMIT 1");
        $db->bind(':b', (int) $row->id);
        $poll = $db->single();
        if ($poll) $out['poll'] = (array) $poll;

        return $out;
    }

    public static function list_blog_categories(array $args, ?array $ctx): array
    {
        $db = new Database();
        try {
            $db->query("SELECT id, name, slug, color, icon FROM blog_categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
            $rows = $db->resultSet() ?: [];
            return ['categories' => array_map(static fn($r) => (array) $r, $rows)];
        } catch (Throwable $e) {
            return ['categories' => []];
        }
    }

    public static function list_blog_tags(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit = max(1, min(200, (int) ($args['limit'] ?? 50)));
        try {
            // Uitpakken JSON-arrays met JSON_TABLE indien beschikbaar; anders fallback PHP-side.
            $db->query("SELECT tags FROM blogs WHERE status='published' AND tags IS NOT NULL AND JSON_LENGTH(tags) > 0");
            $rows = $db->resultSet() ?: [];
            $counter = [];
            foreach ($rows as $r) {
                $tags = json_decode((string) $r->tags, true);
                if (!is_array($tags)) continue;
                foreach ($tags as $t) {
                    $t = strtolower(trim((string) $t));
                    if ($t === '') continue;
                    $counter[$t] = ($counter[$t] ?? 0) + 1;
                }
            }
            arsort($counter);
            $counter = array_slice($counter, 0, $limit, true);
            $list = [];
            foreach ($counter as $tag => $c) $list[] = ['tag' => $tag, 'count' => $c];
            return ['tags' => $list];
        } catch (Throwable $e) {
            return ['tags' => []];
        }
    }

    // -------- WRITE --------

    public static function create_blog_draft(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);

        $db    = new Database();
        $title = trim((string) $args['title']);
        $slug  = !empty($args['slug']) ? self::normalizeSlug((string) $args['slug']) : self::generateSlug($title);
        $slug  = self::uniqueSlug($db, $slug);

        $content = (string) $args['content'];
        $summary = (string) ($args['summary'] ?? self::makeSummary($content));
        $excerpt = isset($args['excerpt']) ? (string) $args['excerpt'] : null;

        $categoryId = isset($args['category_id']) ? (int) $args['category_id'] : null;
        if ($categoryId === null && !empty($args['category_slug'])) {
            $db->query("SELECT id FROM blog_categories WHERE slug = :s LIMIT 1");
            $db->bind(':s', (string) $args['category_slug']);
            $cat = $db->single();
            if ($cat) $categoryId = (int) $cat->id;
        }

        $tagsJson = null;
        if (!empty($args['tags']) && is_array($args['tags'])) {
            $tagsJson = json_encode(array_values(array_unique(array_map(static fn($t) => trim((string) $t), $args['tags']))));
        }

        $readingTime = self::calcReadingTime($content);
        $imagePath   = self::sanitizeImagePath($args['image_path'] ?? null);

        try {
            $db->query("INSERT INTO blogs
                (title, slug, content, summary, excerpt, image_path, author_id, category_id, tags,
                 seo_title, seo_description, meta_robots, canonical_url, reading_time,
                 status, published_at)
                VALUES
                (:title, :slug, :content, :summary, :excerpt, :image, :author, :cat, :tags,
                 :seo_t, :seo_d, :robots, :canon, :rt,
                 'draft', NULL)");
            $db->bind(':title',   $title);
            $db->bind(':slug',    $slug);
            $db->bind(':content', $content);
            $db->bind(':summary', $summary);
            $db->bind(':excerpt', $excerpt);
            $db->bind(':image',   $imagePath);
            $db->bind(':author',  $userId);
            $db->bind(':cat',     $categoryId);
            $db->bind(':tags',    $tagsJson);
            $db->bind(':seo_t',   $args['seo_title'] ?? null);
            $db->bind(':seo_d',   $args['seo_description'] ?? null);
            $db->bind(':robots',  $args['meta_robots'] ?? null);
            $db->bind(':canon',   $args['canonical_url'] ?? null);
            $db->bind(':rt',      $readingTime);
            $db->execute();
        } catch (Throwable $e) {
            throw new McpException(-32000, 'db_error: ' . $e->getMessage());
        }

        $id = (int) $db->lastInsertId();
        return [
            'ok'     => true,
            'id'     => $id,
            'slug'   => $slug,
            'status' => 'draft',
            'url'    => self::blogUrl($slug) . '?preview=1',
        ];
    }

    public static function update_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $updates = [];
        $params  = [':id' => (int) $blog->id];

        $map = [
            'title' => 'title', 'content' => 'content', 'summary' => 'summary', 'excerpt' => 'excerpt',
            'image_path' => 'image_path', 'seo_title' => 'seo_title', 'seo_description' => 'seo_description',
            'meta_robots' => 'meta_robots', 'canonical_url' => 'canonical_url', 'category_id' => 'category_id',
        ];
        foreach ($map as $argKey => $col) {
            if (array_key_exists($argKey, $args)) {
                if ($col === 'image_path') $args[$argKey] = self::sanitizeImagePath($args[$argKey]);
                $updates[] = "$col = :$col";
                $params[":$col"] = $args[$argKey];
            }
        }

        if (array_key_exists('tags', $args)) {
            $tagsJson = is_array($args['tags'])
                ? json_encode(array_values(array_unique(array_map(static fn($t) => trim((string) $t), $args['tags']))))
                : null;
            $updates[] = 'tags = :tags';
            $params[':tags'] = $tagsJson;
        }
        if (!empty($args['new_slug'])) {
            $newSlug = self::uniqueSlug($db, self::normalizeSlug((string) $args['new_slug']), (int) $blog->id);
            $updates[] = 'slug = :slug';
            $params[':slug'] = $newSlug;
        }
        if (array_key_exists('content', $args)) {
            $updates[] = 'reading_time = :rt';
            $params[':rt'] = self::calcReadingTime((string) $args['content']);
        }

        if (empty($updates)) {
            return ['ok' => true, 'id' => (int) $blog->id, 'changed' => 0];
        }

        $sql = 'UPDATE blogs SET ' . implode(', ', $updates) . ' WHERE id = :id';
        $db->query($sql);
        foreach ($params as $k => $v) $db->bind($k, $v);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'slug' => $params[':slug'] ?? $blog->slug, 'changed' => count($updates)];
    }

    public static function publish_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $db->query("UPDATE blogs SET status='published', published_at=NOW(), scheduled_for=NULL WHERE id = :id");
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'slug' => $blog->slug, 'status' => 'published', 'url' => self::blogUrl((string) $blog->slug)];
    }

    public static function schedule_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $when = strtotime((string) $args['scheduled_for']);
        if ($when === false || $when <= time()) {
            throw new McpException(-32602, 'scheduled_for moet een geldige datetime in de toekomst zijn');
        }
        $formatted = date('Y-m-d H:i:s', $when);

        $db->query("UPDATE blogs SET status='scheduled', scheduled_for = :when, published_at = :when WHERE id = :id");
        $db->bind(':when', $formatted);
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'slug' => $blog->slug, 'status' => 'scheduled', 'scheduled_for' => $formatted];
    }

    public static function unpublish_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $db->query("UPDATE blogs SET status='draft', scheduled_for=NULL WHERE id = :id");
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'slug' => $blog->slug, 'status' => 'draft'];
    }

    public static function delete_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        try {
            $db->beginTransaction();
            $db->query("DELETE FROM blog_poll_votes WHERE poll_id IN (SELECT id FROM blog_polls WHERE blog_id = :b)");
            $db->bind(':b', (int) $blog->id);
            $db->execute();
            $db->query("DELETE FROM blog_polls WHERE blog_id = :b");
            $db->bind(':b', (int) $blog->id);
            $db->execute();
            $db->query("DELETE FROM comments WHERE blog_id = :b");
            $db->bind(':b', (int) $blog->id);
            $db->execute();
            $db->query("DELETE FROM blogs WHERE id = :id");
            $db->bind(':id', (int) $blog->id);
            $db->execute();
            $db->commit();
        } catch (Throwable $e) {
            $db->rollback();
            throw new McpException(-32000, 'db_error: ' . $e->getMessage());
        }

        return ['ok' => true, 'id' => (int) $blog->id, 'deleted' => true];
    }

    public static function set_blog_category(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $db->query("SELECT id FROM blog_categories WHERE id = :c LIMIT 1");
        $db->bind(':c', (int) $args['category_id']);
        if (!$db->single()) throw new McpException(-32602, 'category_not_found');

        $db->query("UPDATE blogs SET category_id = :c WHERE id = :id");
        $db->bind(':c', (int) $args['category_id']);
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'category_id' => (int) $args['category_id']];
    }

    public static function set_blog_tags(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $tags = array_values(array_unique(array_map(static fn($t) => trim((string) $t), $args['tags'] ?? [])));
        $db->query("UPDATE blogs SET tags = :t WHERE id = :id");
        $db->bind(':t', json_encode($tags));
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'tags' => $tags];
    }

    public static function set_blog_featured_image(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');
        self::requireBlogOwnership($blog, $userId, $ctx);

        $img = self::sanitizeImagePath($args['image_path']);
        if (!$img) throw new McpException(-32602, 'invalid_image_path');

        $db->query("UPDATE blogs SET image_path = :i WHERE id = :id");
        $db->bind(':i', $img);
        $db->bind(':id', (int) $blog->id);
        $db->execute();

        return ['ok' => true, 'id' => (int) $blog->id, 'image_path' => $img];
    }

    public static function duplicate_blog(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $src    = self::findBlog($db, $args, false);
        if (!$src) throw new McpException(-32001, 'blog_not_found');

        $newTitle = trim((string) ($args['title'] ?? '')) ?: ($src->title . ' (kopie)');
        $slug     = self::uniqueSlug($db, self::generateSlug($newTitle));

        $db->query("INSERT INTO blogs
            (title, slug, content, summary, excerpt, image_path, author_id, category_id, tags,
             seo_title, seo_description, meta_robots, canonical_url, reading_time, status, published_at)
            SELECT :title, :slug, content, summary, excerpt, image_path, :author, category_id, tags,
                   seo_title, seo_description, meta_robots, canonical_url, reading_time, 'draft', NULL
            FROM blogs WHERE id = :id");
        $db->bind(':title', $newTitle);
        $db->bind(':slug', $slug);
        $db->bind(':author', $userId);
        $db->bind(':id', (int) $src->id);
        $db->execute();

        $newId = (int) $db->lastInsertId();
        return ['ok' => true, 'id' => $newId, 'slug' => $slug, 'status' => 'draft', 'source_id' => (int) $src->id];
    }

    public static function list_drafts(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $isAdmin = self::hasScope($ctx, Scopes::BLOGS_ADMIN);
        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));

        $db = new Database();
        $sql = "SELECT b.id, b.title, b.slug, b.summary, b.updated_at, b.author_id, u.username AS author_name
                FROM blogs b JOIN users u ON b.author_id = u.id
                WHERE b.status='draft'";
        if (!$isAdmin) $sql .= ' AND b.author_id = :uid';
        $sql .= " ORDER BY COALESCE(b.updated_at, b.created_at) DESC LIMIT {$limit} OFFSET {$offset}";

        $db->query($sql);
        if (!$isAdmin) $db->bind(':uid', $userId);
        $rows = $db->resultSet() ?: [];
        return ['drafts' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function list_scheduled_blogs(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $isAdmin = self::hasScope($ctx, Scopes::BLOGS_ADMIN);
        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));

        $db = new Database();
        $sql = "SELECT b.id, b.title, b.slug, b.scheduled_for, b.author_id, u.username AS author_name
                FROM blogs b JOIN users u ON b.author_id = u.id
                WHERE b.status='scheduled'";
        if (!$isAdmin) $sql .= ' AND b.author_id = :uid';
        $sql .= " ORDER BY b.scheduled_for ASC LIMIT {$limit} OFFSET {$offset}";

        $db->query($sql);
        if (!$isAdmin) $db->bind(':uid', $userId);
        $rows = $db->resultSet() ?: [];
        return ['scheduled' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_blog_analytics(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blog   = self::findBlog($db, $args, false);
        if (!$blog) throw new McpException(-32001, 'blog_not_found');

        if ((int) $blog->author_id !== $userId
            && !self::hasScope($ctx, Scopes::BLOGS_ADMIN)
            && !self::hasScope($ctx, Scopes::ANALYTICS_READ)) {
            throw new McpException(-32003, 'forbidden: niet je eigen blog');
        }

        $db->query("SELECT COUNT(*) AS c FROM comments WHERE blog_id = :b");
        $db->bind(':b', (int) $blog->id);
        $cmt = $db->single();

        $db->query("SELECT total_votes FROM blog_polls WHERE blog_id = :b LIMIT 1");
        $db->bind(':b', (int) $blog->id);
        $poll = $db->single();

        return [
            'id'              => (int) $blog->id,
            'slug'            => $blog->slug,
            'title'           => $blog->title,
            'status'          => $blog->status,
            'views'           => (int) ($blog->views ?? 0),
            'likes'           => (int) ($blog->likes ?? 0),
            'comments_count'  => $cmt ? (int) $cmt->c : 0,
            'poll_votes'      => $poll ? (int) $poll->total_votes : 0,
            'published_at'    => $blog->published_at,
            'updated_at'      => $blog->updated_at ?? null,
            'reading_time'    => $blog->reading_time ?? null,
        ];
    }

    // ==========================================================
    //                        HELPERS
    // ==========================================================

    private static function findBlog(Database $db, array $args, bool $publicOnly): ?object
    {
        $where = '';
        $bind  = [];
        if (!empty($args['id'])) {
            $where = 'id = :id';
            $bind[':id'] = (int) $args['id'];
        } elseif (!empty($args['slug'])) {
            $where = 'slug = :slug';
            $bind[':slug'] = (string) $args['slug'];
        } else {
            throw new McpException(-32602, 'Geef `id` of `slug`');
        }

        $sql = 'SELECT * FROM blogs WHERE ' . $where;
        if ($publicOnly) {
            $sql .= " AND status='published' AND published_at <= NOW()";
        }
        $sql .= ' LIMIT 1';
        $db->query($sql);
        foreach ($bind as $k => $v) $db->bind($k, $v);
        $row = $db->single();
        return $row ?: null;
    }

    private static function hydrateBlogRow(object $row): array
    {
        $out = (array) $row;
        if (isset($out['tags']) && is_string($out['tags']) && $out['tags'] !== '') {
            $decoded = json_decode($out['tags'], true);
            if (is_array($decoded)) $out['tags'] = $decoded;
        }
        return $out;
    }

    private static function requireUser(?array $ctx): int
    {
        $uid = $ctx['user_id'] ?? null;
        if (!$uid) throw new McpException(-32002, 'authentication_required');
        return (int) $uid;
    }

    private static function hasScope(?array $ctx, string $scope): bool
    {
        if (!$ctx) return false;
        return in_array($scope, $ctx['scopes'] ?? [], true);
    }

    private static function requireBlogOwnership(object $blog, int $userId, ?array $ctx): void
    {
        if ((int) $blog->author_id === $userId) return;
        if (self::hasScope($ctx, Scopes::BLOGS_ADMIN)) return;
        throw new McpException(-32003, 'forbidden: je bent niet de auteur van deze blog');
    }

    private static function generateSlug(string $title): string
    {
        $slug = strtolower($title);
        // Nederlandse karakters normaliseren.
        $slug = strtr($slug, [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c', 'ß' => 'ss',
        ]);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug !== '' ? $slug : 'blog-' . time();
    }

    private static function normalizeSlug(string $slug): string
    {
        return self::generateSlug($slug);
    }

    private static function uniqueSlug(Database $db, string $base, int $excludeId = 0): string
    {
        $slug = $base;
        $i = 2;
        while (true) {
            $db->query("SELECT id FROM blogs WHERE slug = :s" . ($excludeId ? ' AND id != :e' : '') . ' LIMIT 1');
            $db->bind(':s', $slug);
            if ($excludeId) $db->bind(':e', $excludeId);
            if (!$db->single()) return $slug;
            $slug = $base . '-' . $i;
            $i++;
            if ($i > 200) return $base . '-' . time();
        }
        return $slug;
    }

    private static function makeSummary(string $content): string
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text) ?: $text;
        return trim(mb_substr($text, 0, 200)) . (mb_strlen($text) > 200 ? '...' : '');
    }

    private static function calcReadingTime(string $content): int
    {
        $text = strip_tags($content);
        $words = str_word_count($text);
        return max(1, (int) ceil($words / 200));
    }

    private static function sanitizeImagePath(?string $path): ?string
    {
        if ($path === null || $path === '') return null;
        $path = trim($path);
        // Externe https-URL toestaan
        if (preg_match('/^https:\/\//i', $path)) return $path;
        // Interne paden moeten onder uploads/ of public/ vallen
        $path = ltrim($path, '/');
        if (preg_match('/^(uploads|public)\//', $path) && strpos($path, '..') === false) {
            return $path;
        }
        return null;
    }

    private static function blogUrl(string $slug): string
    {
        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : 'https://politiekpraat.nl';
        return $base . '/blog/' . $slug;
    }
}
