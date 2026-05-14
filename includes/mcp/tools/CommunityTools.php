<?php
/**
 * MCP tools voor de community-features: comments, polls, newsletter.
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

final class CommunityTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            // ---------- READ ----------
            ToolBuilder::read(
                'list_comments_for_blog',
                'Lijst comments op een specifieke blog.',
                [
                    'type' => 'object',
                    'properties' => [
                        'blog_id'   => ['type' => 'integer', 'minimum' => 1],
                        'blog_slug' => ['type' => 'string'],
                        'limit'     => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200, 'default' => 50],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_comments_for_blog']
            ),

            ToolBuilder::read(
                'get_blog_poll',
                'Haal de poll van een blog op met tellingen.',
                [
                    'type' => 'object',
                    'properties' => [
                        'blog_id'   => ['type' => 'integer', 'minimum' => 1],
                        'blog_slug' => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_blog_poll']
            ),

            // ---------- WRITE: comments ----------
            ToolBuilder::write(
                'post_comment',
                'Plaats een reactie op een blog namens de ingelogde user.',
                [
                    'type' => 'object',
                    'properties' => [
                        'blog_slug' => ['type' => 'string'],
                        'blog_id'   => ['type' => 'integer', 'minimum' => 1],
                        'content'   => ['type' => 'string', 'minLength' => 2, 'maxLength' => 5000],
                    ],
                    'required' => ['content'],
                    'additionalProperties' => false,
                ],
                [Scopes::COMMENTS_WRITE],
                [self::class, 'post_comment']
            ),

            ToolBuilder::write(
                'update_comment',
                'Bewerk een eigen comment.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id'      => ['type' => 'integer', 'minimum' => 1],
                        'content' => ['type' => 'string', 'minLength' => 2, 'maxLength' => 5000],
                    ],
                    'required' => ['id', 'content'],
                    'additionalProperties' => false,
                ],
                [Scopes::COMMENTS_WRITE],
                [self::class, 'update_comment']
            ),

            ToolBuilder::write(
                'delete_comment',
                'Verwijder een eigen comment (of elke comment als je blogs.admin hebt).',
                [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'minimum' => 1],
                    ],
                    'required' => ['id'],
                    'additionalProperties' => false,
                ],
                [Scopes::COMMENTS_WRITE],
                [self::class, 'delete_comment']
            ),

            // ---------- WRITE: blog polls ----------
            ToolBuilder::write(
                'create_blog_poll',
                'Maak een A/B-poll aan bij een bestaande blog. Maximaal 1 poll per blog.',
                [
                    'type' => 'object',
                    'properties' => [
                        'blog_id'   => ['type' => 'integer', 'minimum' => 1],
                        'blog_slug' => ['type' => 'string'],
                        'question'  => ['type' => 'string', 'minLength' => 5, 'maxLength' => 500],
                        'option_a'  => ['type' => 'string', 'minLength' => 1, 'maxLength' => 200],
                        'option_b'  => ['type' => 'string', 'minLength' => 1, 'maxLength' => 200],
                    ],
                    'required' => ['question', 'option_a', 'option_b'],
                    'additionalProperties' => false,
                ],
                [Scopes::POLLS_WRITE, Scopes::BLOGS_WRITE],
                [self::class, 'create_blog_poll']
            ),

            ToolBuilder::write(
                'vote_blog_poll',
                'Stem op een blog-poll (A of B).',
                [
                    'type' => 'object',
                    'properties' => [
                        'poll_id' => ['type' => 'integer', 'minimum' => 1],
                        'choice'  => ['type' => 'string', 'enum' => ['A', 'B']],
                    ],
                    'required' => ['poll_id', 'choice'],
                    'additionalProperties' => false,
                ],
                [Scopes::POLLS_WRITE],
                [self::class, 'vote_blog_poll']
            ),

            // ---------- WRITE: newsletter ----------
            ToolBuilder::write(
                'subscribe_newsletter',
                'Meld een e-mailadres aan voor de PolitiekPraat-nieuwsbrief.',
                [
                    'type' => 'object',
                    'properties' => [
                        'email' => ['type' => 'string', 'minLength' => 5, 'maxLength' => 100],
                    ],
                    'required' => ['email'],
                    'additionalProperties' => false,
                ],
                [Scopes::NEWSLETTER_WRITE],
                [self::class, 'subscribe_newsletter']
            ),

            ToolBuilder::write(
                'unsubscribe_newsletter',
                'Zet een abonnee op status=unsubscribed.',
                [
                    'type' => 'object',
                    'properties' => [
                        'email' => ['type' => 'string', 'minLength' => 5, 'maxLength' => 100],
                    ],
                    'required' => ['email'],
                    'additionalProperties' => false,
                ],
                [Scopes::NEWSLETTER_WRITE],
                [self::class, 'unsubscribe_newsletter']
            ),
        ];
    }

    // ========================================================
    //                         HANDLERS
    // ========================================================

    public static function list_comments_for_blog(array $args, ?array $ctx): array
    {
        $db = new Database();
        $blogId = self::resolveBlogId($db, $args);
        if (!$blogId) throw new McpException(-32001, 'blog_not_found');

        $limit = max(1, min(200, (int) ($args['limit'] ?? 50)));
        $db->query("SELECT c.id, c.content, c.anonymous_name, c.is_liked_by_author, c.likes_count, c.created_at,
                           u.username AS author_name, u.id AS author_id
                    FROM comments c LEFT JOIN users u ON u.id = c.user_id
                    WHERE c.blog_id = :b ORDER BY c.created_at DESC LIMIT {$limit}");
        $db->bind(':b', $blogId);
        $rows = $db->resultSet() ?: [];
        return ['comments' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_blog_poll(array $args, ?array $ctx): array
    {
        $db = new Database();
        $blogId = self::resolveBlogId($db, $args);
        if (!$blogId) throw new McpException(-32001, 'blog_not_found');

        $db->query('SELECT * FROM blog_polls WHERE blog_id = :b LIMIT 1');
        $db->bind(':b', $blogId);
        $poll = $db->single();
        if (!$poll) return ['poll' => null];

        $out = (array) $poll;
        $total = max(1, (int) $poll->total_votes);
        $out['option_a_percentage'] = round((int) $poll->option_a_votes / $total * 100, 1);
        $out['option_b_percentage'] = round((int) $poll->option_b_votes / $total * 100, 1);
        return ['poll' => $out];
    }

    public static function post_comment(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blogId = self::resolveBlogId($db, $args);
        if (!$blogId) throw new McpException(-32001, 'blog_not_found');

        $db->query('INSERT INTO comments (blog_id, user_id, content) VALUES (:b, :u, :c)');
        $db->bind(':b', $blogId);
        $db->bind(':u', $userId);
        $db->bind(':c', (string) $args['content']);
        $db->execute();
        return ['ok' => true, 'comment_id' => (int) $db->lastInsertId()];
    }

    public static function update_comment(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $db->query('SELECT user_id FROM comments WHERE id = :i LIMIT 1');
        $db->bind(':i', (int) $args['id']);
        $c = $db->single();
        if (!$c) throw new McpException(-32001, 'comment_not_found');
        if ((int) $c->user_id !== $userId && !self::hasScope($ctx, Scopes::BLOGS_ADMIN)) {
            throw new McpException(-32003, 'forbidden');
        }

        $db->query('UPDATE comments SET content = :c WHERE id = :i');
        $db->bind(':c', (string) $args['content']);
        $db->bind(':i', (int) $args['id']);
        $db->execute();
        return ['ok' => true, 'id' => (int) $args['id']];
    }

    public static function delete_comment(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $db->query('SELECT user_id, blog_id FROM comments WHERE id = :i LIMIT 1');
        $db->bind(':i', (int) $args['id']);
        $c = $db->single();
        if (!$c) throw new McpException(-32001, 'comment_not_found');

        $isAdmin = self::hasScope($ctx, Scopes::BLOGS_ADMIN);
        if ((int) $c->user_id !== $userId && !$isAdmin) {
            // Ook blog-auteur mag verwijderen
            $db->query('SELECT author_id FROM blogs WHERE id = :b');
            $db->bind(':b', (int) $c->blog_id);
            $blog = $db->single();
            if (!$blog || (int) $blog->author_id !== $userId) {
                throw new McpException(-32003, 'forbidden');
            }
        }

        $db->query('DELETE FROM comments WHERE id = :i');
        $db->bind(':i', (int) $args['id']);
        $db->execute();
        return ['ok' => true, 'deleted' => (int) $args['id']];
    }

    public static function create_blog_poll(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $blogId = self::resolveBlogId($db, $args);
        if (!$blogId) throw new McpException(-32001, 'blog_not_found');

        $db->query('SELECT author_id FROM blogs WHERE id = :b');
        $db->bind(':b', $blogId);
        $b = $db->single();
        if ((int) ($b->author_id ?? 0) !== $userId && !self::hasScope($ctx, Scopes::BLOGS_ADMIN)) {
            throw new McpException(-32003, 'forbidden: je bent niet de auteur van deze blog');
        }

        $db->query('SELECT id FROM blog_polls WHERE blog_id = :b LIMIT 1');
        $db->bind(':b', $blogId);
        if ($db->single()) throw new McpException(-32009, 'poll_exists');

        $db->query('INSERT INTO blog_polls (blog_id, question, option_a, option_b) VALUES (:b, :q, :a, :ob)');
        $db->bind(':b', $blogId);
        $db->bind(':q', (string) $args['question']);
        $db->bind(':a', (string) $args['option_a']);
        $db->bind(':ob', (string) $args['option_b']);
        $db->execute();
        return ['ok' => true, 'poll_id' => (int) $db->lastInsertId()];
    }

    public static function vote_blog_poll(array $args, ?array $ctx): array
    {
        $userId = self::requireUser($ctx);
        $db     = new Database();
        $pollId = (int) $args['poll_id'];

        $db->query('SELECT id FROM blog_polls WHERE id = :p LIMIT 1');
        $db->bind(':p', $pollId);
        if (!$db->single()) throw new McpException(-32001, 'poll_not_found');

        $db->query('SELECT id FROM blog_poll_votes WHERE poll_id = :p AND user_id = :u LIMIT 1');
        $db->bind(':p', $pollId);
        $db->bind(':u', $userId);
        if ($db->single()) throw new McpException(-32009, 'already_voted');

        $choice = (string) $args['choice'];
        try {
            $db->beginTransaction();
            $db->query('INSERT INTO blog_poll_votes (poll_id, user_id, chosen_option) VALUES (:p, :u, :c)');
            $db->bind(':p', $pollId);
            $db->bind(':u', $userId);
            $db->bind(':c', $choice);
            $db->execute();
            $col = $choice === 'A' ? 'option_a_votes' : 'option_b_votes';
            $db->query("UPDATE blog_polls SET {$col} = {$col} + 1, total_votes = total_votes + 1 WHERE id = :p");
            $db->bind(':p', $pollId);
            $db->execute();
            $db->commit();
        } catch (Throwable $e) {
            $db->rollback();
            throw new McpException(-32000, 'db_error: ' . $e->getMessage());
        }

        return ['ok' => true, 'poll_id' => $pollId, 'choice' => $choice];
    }

    public static function subscribe_newsletter(array $args, ?array $ctx): array
    {
        self::requireUser($ctx); // agent moet auth'ed zijn
        $email = filter_var((string) $args['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) throw new McpException(-32602, 'invalid_email');

        $db = new Database();
        $db->query('SELECT id, status FROM newsletter_subscribers WHERE email = :e LIMIT 1');
        $db->bind(':e', $email);
        $existing = $db->single();

        if ($existing) {
            if ((string) $existing->status === 'active') {
                return ['ok' => true, 'already_subscribed' => true, 'id' => (int) $existing->id];
            }
            $db->query("UPDATE newsletter_subscribers SET status='active', subscribed_at=NOW() WHERE id = :i");
            $db->bind(':i', (int) $existing->id);
            $db->execute();
            return ['ok' => true, 'reactivated' => true, 'id' => (int) $existing->id];
        }

        $db->query("INSERT INTO newsletter_subscribers (email, status, subscribed_at) VALUES (:e, 'active', NOW())");
        $db->bind(':e', $email);
        $db->execute();
        return ['ok' => true, 'id' => (int) $db->lastInsertId()];
    }

    public static function unsubscribe_newsletter(array $args, ?array $ctx): array
    {
        self::requireUser($ctx);
        $email = filter_var((string) $args['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) throw new McpException(-32602, 'invalid_email');

        $db = new Database();
        $db->query("UPDATE newsletter_subscribers SET status='unsubscribed' WHERE email = :e");
        $db->bind(':e', $email);
        $db->execute();
        return ['ok' => true, 'email' => $email, 'status' => 'unsubscribed'];
    }

    // ========================================================
    //                         HELPERS
    // ========================================================

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

    private static function resolveBlogId(Database $db, array $args): ?int
    {
        if (!empty($args['blog_id'])) return (int) $args['blog_id'];
        if (!empty($args['blog_slug'])) {
            $db->query('SELECT id FROM blogs WHERE slug = :s LIMIT 1');
            $db->bind(':s', (string) $args['blog_slug']);
            $r = $db->single();
            return $r ? (int) $r->id : null;
        }
        return null;
    }
}
