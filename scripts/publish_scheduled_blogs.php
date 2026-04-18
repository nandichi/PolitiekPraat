<?php
/**
 * Publish Scheduled Blogs
 *
 * Flip blogs met status='scheduled' en scheduled_for <= NOW()
 * naar status='published'. published_at wordt gesynchroniseerd met
 * scheduled_for zodat de sort in publieke lijsten klopt.
 *
 * Aanbevolen cron: elke minuut.
 *   * * * * * /usr/bin/php /pad/naar/scripts/publish_scheduled_blogs.php >> /pad/naar/logs/publish_scheduled.log 2>&1
 */

declare(strict_types=1);

$scriptDir   = dirname(__FILE__);
$projectRoot = dirname($scriptDir);

require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/Database.php';

function log_line(string $msg): void
{
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
}

try {
    $db = new Database();

    $db->query("
        SELECT id, title, slug, scheduled_for
        FROM blogs
        WHERE status = 'scheduled'
          AND scheduled_for IS NOT NULL
          AND scheduled_for <= NOW()
        ORDER BY scheduled_for ASC
        LIMIT 100
    ");
    $due = $db->resultSet();

    $total = is_array($due) ? count($due) : 0;
    if ($total === 0) {
        log_line('Geen scheduled blogs te publiceren.');
        exit(0);
    }

    log_line("Publicatie-ronde: {$total} blog(s) klaar om live te gaan.");

    $published = 0;
    foreach ($due as $blog) {
        $id      = (int) ($blog->id ?? 0);
        $title   = (string) ($blog->title ?? '');
        $slug    = (string) ($blog->slug ?? '');
        $schedAt = (string) ($blog->scheduled_for ?? '');

        if ($id <= 0) {
            continue;
        }

        try {
            $db->query("
                UPDATE blogs
                SET status = 'published',
                    published_at = COALESCE(scheduled_for, NOW()),
                    scheduled_for = NULL
                WHERE id = :id
                  AND status = 'scheduled'
            ");
            $db->bind(':id', $id);
            $db->execute();
            $published++;
            log_line("OK  gepubliceerd id={$id} slug={$slug} (scheduled_for={$schedAt})");
        } catch (Throwable $e) {
            log_line("ERR id={$id}: " . $e->getMessage());
        }
    }

    log_line("Klaar: {$published}/{$total} blogs gepubliceerd.");
    exit(0);
} catch (Throwable $e) {
    log_line('FATAL: ' . $e->getMessage());
    exit(1);
}
