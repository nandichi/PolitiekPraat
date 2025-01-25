<?php
$db = new Database();

// Haal alle discussies op
$db->query("SELECT forum_topics.*, 
                  users.username as author_name,
                  (SELECT COUNT(*) FROM forum_replies WHERE topic_id = forum_topics.id) as reply_count,
                  (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = forum_topics.id) as last_reply
           FROM forum_topics 
           JOIN users ON forum_topics.author_id = users.id 
           ORDER BY last_activity DESC");
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
                            <?php echo $topic->title; ?>
                        </a>
                        <p class="text-gray-600 text-sm mt-1">
                            <?php echo substr($topic->content, 0, 100) . '...'; ?>
                        </p>
                    </div>
                    <div class="col-span-2 text-center text-gray-600">
                        <?php echo $topic->author_name; ?>
                    </div>
                    <div class="col-span-2 text-center text-gray-600">
                        <?php echo $topic->reply_count; ?>
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
    <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 