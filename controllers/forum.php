<?php
$db = new Database();

$per_page = 25;
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$offset = ($page - 1) * $per_page;

$db->query("SELECT COUNT(*) as total FROM forum_topics");
$total_topics_row = $db->single();
$total_topics = (int) ($total_topics_row->total ?? 0);
$total_pages = max(1, (int) ceil($total_topics / $per_page));

if ($page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $per_page;
}

$db->query("SELECT forum_topics.*,
                  users.username as author_name,
                  COALESCE(reply_stats.reply_count, 0) as reply_count,
                  reply_stats.last_reply
           FROM forum_topics
           JOIN users ON forum_topics.author_id = users.id
           LEFT JOIN (
               SELECT topic_id,
                      COUNT(*) as reply_count,
                      MAX(created_at) as last_reply
               FROM forum_replies
               GROUP BY topic_id
           ) as reply_stats ON reply_stats.topic_id = forum_topics.id
           ORDER BY forum_topics.last_activity DESC
           LIMIT :limit OFFSET :offset");
$db->bind(':limit', $per_page);
$db->bind(':offset', $offset);
$topics = $db->resultSet();

require_once BASE_PATH . '/views/templates/header.php';

if (!function_exists('pp_forum_relative_time')) {
    function pp_forum_relative_time(string $datetime): string {
        $ts = strtotime($datetime);
        if (!$ts) return '';
        $diff = time() - $ts;
        if ($diff < 60) return 'zojuist';
        if ($diff < 3600) return floor($diff / 60) . ' min geleden';
        if ($diff < 86400) return floor($diff / 3600) . ' uur geleden';
        if ($diff < 604800) return floor($diff / 86400) . ' dagen geleden';
        return date('d M Y', $ts);
    }
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Forum',
    'title'   => 'Discussies van de community',
    'lead'    => 'Praat mee over politieke onderwerpen die jou bezighouden. Respectvol, onderbouwd en met ruimte voor verschillende perspectieven.',
]) ?>

<section class="pp-container pp-container--wide py-10 md:py-12">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8 border-b border-[color:var(--color-keyline)] pb-5">
        <div>
            <div class="eyebrow mb-1">Overzicht</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] leading-tight"><?= (int) $total_topics ?> <?= $total_topics === 1 ? 'discussie' : 'discussies' ?></h2>
        </div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?= pp_e(URLROOT) ?>/forum/create" class="btn btn--primary">
                <?= pp_icon('plus', 14) ?>
                Nieuwe discussie
            </a>
        <?php else: ?>
            <a href="<?= pp_e(URLROOT) ?>/login" class="btn btn--ghost">
                <?= pp_icon('log-in', 14) ?>
                Log in om mee te doen
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($topics)): ?>
        <div class="keyline-card p-10 md:p-14 text-center">
            <div class="text-[color:var(--color-ink-faint)] mb-4 flex justify-center"><?= pp_icon('message-square', 40) ?></div>
            <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-2">Nog geen discussies</h3>
            <p class="text-[color:var(--color-ink-muted)] mb-6 max-w-md mx-auto">
                Er is nog niemand een gesprek begonnen. Wil jij de eerste zijn?
            </p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= pp_e(URLROOT) ?>/forum/create" class="btn btn--primary">
                    <?= pp_icon('plus', 14) ?>
                    Start de eerste discussie
                </a>
            <?php else: ?>
                <a href="<?= pp_e(URLROOT) ?>/login" class="btn btn--primary">
                    <?= pp_icon('log-in', 14) ?>
                    Inloggen
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <ul class="border-t border-[color:var(--color-keyline)]">
            <?php foreach ($topics as $topic): ?>
                <?php
                $lastActivity = $topic->last_reply ?? $topic->created_at;
                $snippet = trim((string) $topic->content);
                if (function_exists('mb_strimwidth')) {
                    $snippet = mb_strimwidth(strip_tags($snippet), 0, 180, '...');
                } else {
                    $snippet = substr(strip_tags($snippet), 0, 180) . (strlen($snippet) > 180 ? '...' : '');
                }
                ?>
                <li class="border-b border-[color:var(--color-keyline)] py-5 md:py-6 group">
                    <a href="<?= pp_e(URLROOT) ?>/forum/topic/<?= (int) $topic->id ?>" class="block">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                            <div class="md:col-span-8">
                                <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-1.5 leading-snug group-hover:text-[color:var(--color-hague)] transition-colors">
                                    <?= pp_e($topic->title) ?>
                                </h3>
                                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-2"><?= pp_e($snippet) ?></p>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-[color:var(--color-ink-faint)]">
                                    <span class="inline-flex items-center gap-1.5"><?= pp_icon('user', 12) ?><?= pp_e($topic->author_name) ?></span>
                                    <span class="inline-flex items-center gap-1.5"><?= pp_icon('calendar', 12) ?><?= pp_e(date('d M Y', strtotime($topic->created_at))) ?></span>
                                </div>
                            </div>
                            <div class="md:col-span-2 md:text-center">
                                <div class="font-display text-2xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $topic->reply_count ?></div>
                                <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]"><?= $topic->reply_count == 1 ? 'reactie' : 'reacties' ?></div>
                            </div>
                            <div class="md:col-span-2 md:text-right">
                                <div class="text-sm text-[color:var(--color-ink)]"><?= pp_e(pp_forum_relative_time($lastActivity)) ?></div>
                                <div class="text-xs text-[color:var(--color-ink-faint)]"><?= pp_e(date('H:i', strtotime($lastActivity))) ?></div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($total_pages > 1): ?>
            <nav class="pagination mt-10" aria-label="Forum paginering">
                <a href="<?= pp_e(URLROOT) ?>/forum?page=<?= max(1, $page - 1) ?>"
                   class="pagination__link <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">
                    <?= pp_icon('chevron-left', 14) ?> Vorige
                </a>
                <span class="pagination__info">Pagina <?= (int) $page ?> van <?= (int) $total_pages ?></span>
                <a href="<?= pp_e(URLROOT) ?>/forum?page=<?= min($total_pages, $page + 1) ?>"
                   class="pagination__link <?= $page >= $total_pages ? 'pointer-events-none opacity-40' : '' ?>">
                    Volgende <?= pp_icon('chevron-right', 14) ?>
                </a>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
