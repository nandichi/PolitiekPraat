<?php
/**
 * MCP analytics-tools: agent kan stats over de site opvragen.
 * Vereist scope `analytics.read` (mits niet publiek), behalve get_site_stats
 * dat publieke totalen retourneert.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';

use Database;
use PolitiekPraat\MCP\ToolBuilder;
use PolitiekPraat\OAuth\Scopes;
use Throwable;

final class AnalyticsTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'get_site_stats',
                'Publieke site-statistieken: totaal aantal blogs, partijen, moties, stellingen, forum-topics en nieuws.',
                [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                    'additionalProperties' => false,
                ],
                [self::class, 'get_site_stats']
            ),

            ToolBuilder::read(
                'get_trending_blogs',
                'Top blogs op basis van views + likes in de afgelopen N dagen.',
                [
                    'type' => 'object',
                    'properties' => [
                        'days'  => ['type' => 'integer', 'minimum' => 1, 'maximum' => 90, 'default' => 7],
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 25, 'default' => 10],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_trending_blogs']
            ),

            ToolBuilder::write(
                'get_recent_activity',
                'Recente activity-feed: laatste comments, forum-replies, blogs en nieuwsartikelen. Vereist `analytics.read` scope.',
                [
                    'type' => 'object',
                    'properties' => [
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 20],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::ANALYTICS_READ],
                [self::class, 'get_recent_activity'],
                false // service-level, geen user nodig
            ),
        ];
    }

    public static function get_site_stats(array $args, ?array $ctx): array
    {
        $db = new Database();
        $stats = [
            'blogs'                 => self::count($db, "SELECT COUNT(*) AS c FROM blogs WHERE status='published' AND published_at <= NOW()"),
            'blog_drafts'           => self::count($db, "SELECT COUNT(*) AS c FROM blogs WHERE status='draft'"),
            'blog_scheduled'        => self::count($db, "SELECT COUNT(*) AS c FROM blogs WHERE status='scheduled'"),
            'blog_categories'       => self::count($db, 'SELECT COUNT(*) AS c FROM blog_categories WHERE is_active = 1'),
            'forum_topics'          => self::count($db, 'SELECT COUNT(*) AS c FROM forum_topics'),
            'forum_replies'         => self::count($db, 'SELECT COUNT(*) AS c FROM forum_replies'),
            'comments'              => self::count($db, 'SELECT COUNT(*) AS c FROM comments'),
            'partijen'              => self::count($db, 'SELECT COUNT(*) AS c FROM political_parties WHERE is_active = 1'),
            'stemwijzer_questions'  => self::count($db, 'SELECT COUNT(*) AS c FROM stemwijzer_questions WHERE is_active = 1'),
            'stemmentracker_moties' => self::count($db, 'SELECT COUNT(*) AS c FROM stemmentracker_moties WHERE is_active = 1'),
            'news_articles'         => self::count($db, 'SELECT COUNT(*) AS c FROM news_articles'),
            'newsletter_active'     => self::count($db, "SELECT COUNT(*) AS c FROM newsletter_subscribers WHERE status='active'"),
            'users'                 => self::count($db, 'SELECT COUNT(*) AS c FROM users'),
        ];
        return ['stats' => $stats, 'generated_at' => date('c')];
    }

    public static function get_trending_blogs(array $args, ?array $ctx): array
    {
        $db    = new Database();
        $days  = max(1, min(90, (int) ($args['days'] ?? 7)));
        $limit = max(1, min(25, (int) ($args['limit'] ?? 10)));

        try {
            $db->query("SELECT b.id, b.title, b.slug, b.summary, b.views, b.likes, b.published_at,
                               u.username AS author_name, c.name AS category_name,
                               (b.views + b.likes * 5) AS score
                        FROM blogs b
                        JOIN users u ON u.id = b.author_id
                        LEFT JOIN blog_categories c ON c.id = b.category_id
                        WHERE b.status='published' AND b.published_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
                        ORDER BY score DESC, b.published_at DESC
                        LIMIT {$limit}");
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) {
            return ['blogs' => [], 'error' => $e->getMessage()];
        }
        return ['days' => $days, 'blogs' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_recent_activity(array $args, ?array $ctx): array
    {
        $db    = new Database();
        $limit = max(1, min(50, (int) ($args['limit'] ?? 20)));

        $activities = [];

        try {
            $db->query("SELECT id, title, slug, published_at, 'blog_published' AS event
                        FROM blogs WHERE status='published'
                        ORDER BY published_at DESC LIMIT {$limit}");
            foreach ($db->resultSet() ?: [] as $r) {
                $activities[] = [
                    'event' => 'blog_published', 'id' => (int) $r->id,
                    'title' => $r->title, 'slug' => $r->slug, 'at' => $r->published_at,
                ];
            }
        } catch (Throwable $e) {}

        try {
            $db->query("SELECT c.id, c.content, c.created_at, b.title AS blog_title, b.slug AS blog_slug
                        FROM comments c JOIN blogs b ON b.id = c.blog_id
                        ORDER BY c.created_at DESC LIMIT {$limit}");
            foreach ($db->resultSet() ?: [] as $r) {
                $activities[] = [
                    'event' => 'comment_posted', 'id' => (int) $r->id,
                    'snippet' => mb_substr(strip_tags((string) $r->content), 0, 140),
                    'blog_title' => $r->blog_title, 'blog_slug' => $r->blog_slug, 'at' => $r->created_at,
                ];
            }
        } catch (Throwable $e) {}

        try {
            $db->query("SELECT id, title, created_at FROM forum_topics
                        ORDER BY created_at DESC LIMIT {$limit}");
            foreach ($db->resultSet() ?: [] as $r) {
                $activities[] = [
                    'event' => 'forum_topic_posted', 'id' => (int) $r->id,
                    'title' => $r->title, 'at' => $r->created_at,
                ];
            }
        } catch (Throwable $e) {}

        try {
            $db->query("SELECT id, title, source, published_at FROM news_articles
                        ORDER BY published_at DESC LIMIT {$limit}");
            foreach ($db->resultSet() ?: [] as $r) {
                $activities[] = [
                    'event' => 'news_article', 'id' => (int) $r->id,
                    'title' => $r->title, 'source' => $r->source, 'at' => $r->published_at,
                ];
            }
        } catch (Throwable $e) {}

        usort($activities, static fn($a, $b) => strcmp((string) ($b['at'] ?? ''), (string) ($a['at'] ?? '')));
        $activities = array_slice($activities, 0, $limit);

        return ['count' => count($activities), 'activities' => $activities];
    }

    private static function count(Database $db, string $sql): int
    {
        try {
            $db->query($sql);
            $r = $db->single();
            return $r ? (int) $r->c : 0;
        } catch (Throwable $e) {
            return 0;
        }
    }
}
