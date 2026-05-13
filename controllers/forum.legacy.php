<?php
$db = new Database();

$per_page = 25;
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$offset = ($page - 1) * $per_page;

// Totaal aantal topics voor paginering
$db->query("SELECT COUNT(*) as total FROM forum_topics");
$total_topics_row = $db->single();
$total_topics = (int) ($total_topics_row->total ?? 0);
$total_pages = max(1, (int) ceil($total_topics / $per_page));

if ($page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $per_page;
}

// Haal topics op zonder gecorreleerde subqueries
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
?>

<main class="container mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Forum</h1>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo URLROOT; ?>/forum/create"
               class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                Nieuwe Discussie
            </a>
        <?php endif; ?>
    </div>

    <?php if(empty($topics)): ?>
        <div class="text-center py-12">
            <p class="text-gray-600 text-xl">Er zijn nog geen discussies gestart.</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <p class="mt-4">
                    <a href="<?php echo URLROOT; ?>/forum/create" class="text-primary hover:underline">
                        Start de eerste discussie!
                    </a>
                </p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="grid grid-cols-12 gap-4 p-4 bg-gray-50 font-bold text-gray-700">
                <div class="col-span-6">Onderwerp</div>
                <div class="col-span-2 text-center">Auteur</div>
                <div class="col-span-2 text-center">Reacties</div>
                <div class="col-span-2 text-center">Laatste Activiteit</div>
            </div>
            <?php foreach($topics as $topic): ?>
                <div class="grid grid-cols-12 gap-4 p-4 border-t">
                    <div class="col-span-6">
                        <a href="<?php echo URLROOT; ?>/forum/topic/<?php echo $topic->id; ?>"
                           class="text-primary hover:underline font-semibold">
                            <?php echo htmlspecialchars($topic->title, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                        <p class="text-gray-600 text-sm mt-1">
                            <?php echo htmlspecialchars(substr($topic->content, 0, 100), ENT_QUOTES, 'UTF-8') . '...'; ?>
                        </p>
                    </div>
                    <div class="col-span-2 text-center text-gray-600">
                        <?php echo htmlspecialchars($topic->author_name, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="col-span-2 text-center text-gray-600">
                        <?php echo (int) $topic->reply_count; ?>
                    </div>
                    <div class="col-span-2 text-center text-gray-600">
                        <?php
                        $last_activity = $topic->last_reply ?? $topic->created_at;
                        echo date('d-m-Y H:i', strtotime($last_activity));
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav class="mt-6 flex items-center justify-between" aria-label="Forum paginering">
                <a href="<?php echo URLROOT; ?>/forum?page=<?php echo max(1, $page - 1); ?>"
                   class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium <?php echo $page <= 1 ? 'pointer-events-none opacity-40' : 'hover:bg-gray-50'; ?>">
                    Vorige
                </a>

                <p class="text-sm text-gray-600">
                    Pagina <?php echo $page; ?> van <?php echo $total_pages; ?>
                </p>

                <a href="<?php echo URLROOT; ?>/forum?page=<?php echo min($total_pages, $page + 1); ?>"
                   class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium <?php echo $page >= $total_pages ? 'pointer-events-none opacity-40' : 'hover:bg-gray-50'; ?>">
                    Volgende
                </a>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
