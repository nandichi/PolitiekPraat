<?php
if (!isset($params['id'])) {
    header('Location: ' . URLROOT . '/forum');
    exit;
}

$db = new Database();

if (!function_exists('forum_escape')) {
    function forum_escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('forum_escape_with_breaks')) {
    function forum_escape_with_breaks(string $value): string
    {
        return nl2br(forum_escape($value));
    }
}

// Haal topic op
$db->query("SELECT forum_topics.*, users.username as author_name 
           FROM forum_topics 
           JOIN users ON forum_topics.author_id = users.id 
           WHERE forum_topics.id = :id");
$db->bind(':id', $params['id']);
$topic = $db->single();

if (!$topic) {
    header('Location: ' . URLROOT . '/404');
    exit;
}

// Update views
$db->query("UPDATE forum_topics SET views = views + 1 WHERE id = :id");
$db->bind(':id', $topic->id);
$db->execute();

// Haal reacties op
$db->query("SELECT forum_replies.*, users.username as author_name 
           FROM forum_replies 
           JOIN users ON forum_replies.user_id = users.id 
           WHERE forum_replies.topic_id = :topic_id 
           ORDER BY forum_replies.created_at ASC");
$db->bind(':topic_id', $topic->id);
$replies = $db->resultSet();

// Reactie toevoegen
$reply_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (empty($content)) {
        $reply_error = 'Vul een reactie in';
    } else {
        $db->query("INSERT INTO forum_replies (topic_id, user_id, content) VALUES (:topic_id, :user_id, :content)");
        $db->bind(':topic_id', $topic->id);
        $db->bind(':user_id', $_SESSION['user_id']);
        $db->bind(':content', $content);
        
        if ($db->execute()) {
            // Update last_activity van het topic
            $db->query("UPDATE forum_topics SET last_activity = CURRENT_TIMESTAMP WHERE id = :id");
            $db->bind(':id', $topic->id);
            $db->execute();
            
            header('Location: ' . URLROOT . '/forum/topic/' . $topic->id . '#replies');
            exit;
        } else {
            $reply_error = 'Er is iets misgegaan bij het plaatsen van je reactie';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-10 md:py-14">
    <div class="mb-6">
        <a href="<?= pp_e(URLROOT) ?>/forum" class="inline-flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-hague)] transition-colors">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar forum
        </a>
    </div>

    <article class="keyline-card p-6 md:p-10 mb-8">
        <div class="eyebrow mb-3">Discussie</div>
        <h1 class="font-display text-display-xl text-[color:var(--color-ink)] mb-5 leading-[1.15]"><?= forum_escape((string) $topic->title) ?></h1>
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-[color:var(--color-ink-faint)] mb-6 pb-6 border-b border-[color:var(--color-keyline)]">
            <span class="inline-flex items-center gap-1.5"><?= pp_icon('user', 12) ?><?= forum_escape((string) $topic->author_name) ?></span>
            <span class="inline-flex items-center gap-1.5"><?= pp_icon('calendar', 12) ?><?= pp_e(date('d M Y, H:i', strtotime($topic->created_at))) ?></span>
            <span class="inline-flex items-center gap-1.5"><?= pp_icon('eye', 12) ?><?= (int) $topic->views ?> weergaven</span>
        </div>
        <div class="prose-editorial max-w-none">
            <?= forum_escape_with_breaks((string) $topic->content) ?>
        </div>
    </article>

    <section id="replies" aria-labelledby="replies-heading">
        <div class="flex items-end justify-between mb-6 pb-4 border-b border-[color:var(--color-keyline)]">
            <h2 id="replies-heading" class="font-display text-display-lg text-[color:var(--color-ink)] leading-tight">Reacties (<?= count($replies) ?>)</h2>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="<?= pp_e(URLROOT) ?>/forum/topic/<?= (int) $topic->id ?>#replies" class="keyline-card p-6 md:p-8 mb-8">
                <?php if ($reply_error): ?>
                    <div class="border-l-4 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] p-4 mb-5 rounded-r text-sm">
                        <?= forum_escape($reply_error) ?>
                    </div>
                <?php endif; ?>
                <div class="field mb-4">
                    <label for="content" class="field__label">Jouw reactie</label>
                    <textarea name="content" id="content" rows="5" required class="textarea" placeholder="Schrijf een onderbouwde, respectvolle reactie..."></textarea>
                </div>
                <button type="submit" class="btn btn--primary">
                    <?= pp_icon('send', 14) ?>
                    Plaats reactie
                </button>
            </form>
        <?php else: ?>
            <div class="keyline-card p-6 mb-8 flex items-center justify-between gap-4 flex-wrap">
                <p class="text-sm text-[color:var(--color-ink-muted)]">Log in om een reactie te plaatsen.</p>
                <a href="<?= pp_e(URLROOT) ?>/login" class="btn btn--ghost">
                    <?= pp_icon('log-in', 14) ?>
                    Inloggen
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($replies)): ?>
            <div class="text-center py-10 text-[color:var(--color-ink-muted)]">
                <div class="text-[color:var(--color-ink-faint)] mb-3 flex justify-center"><?= pp_icon('message-circle', 32) ?></div>
                <p>Er zijn nog geen reacties. Wees de eerste die reageert.</p>
            </div>
        <?php else: ?>
            <ul class="space-y-0 border-t border-[color:var(--color-keyline)]">
                <?php foreach ($replies as $reply): ?>
                    <li class="border-b border-[color:var(--color-keyline)] py-6">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="font-display text-[color:var(--color-ink)] font-medium"><?= forum_escape((string) $reply->author_name) ?></span>
                                <span class="text-[color:var(--color-ink-faint)]"><?= pp_e(date('d M Y, H:i', strtotime($reply->created_at))) ?></span>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $reply->user_id || !empty($_SESSION['is_admin']))): ?>
                                <form method="POST" action="<?= pp_e(URLROOT) ?>/forum/reply/delete/<?= (int) $reply->id ?>" class="inline">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs text-[color:var(--color-terracotta)] hover:underline underline-offset-2"
                                            onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen?')">
                                        <?= pp_icon('trash-2', 12) ?>
                                        Verwijderen
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="prose-editorial max-w-none text-sm">
                            <?= forum_escape_with_breaks((string) $reply->content) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 