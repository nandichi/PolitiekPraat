<?php
if (!isset($params['id'])) {
    header('Location: ' . URLROOT . '/forum');
    exit;
}

$db = new Database();

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

require_once '../views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <article class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="<?php echo URLROOT; ?>/forum" 
               class="text-primary hover:underline inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Terug naar Forum
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h1 class="text-3xl font-bold mb-4"><?php echo $topic->title; ?></h1>
            
            <div class="flex items-center text-gray-500 mb-6">
                <span>Door <?php echo $topic->author_name; ?></span>
                <span class="mx-2">•</span>
                <span><?php echo date('d-m-Y H:i', strtotime($topic->created_at)); ?></span>
                <span class="mx-2">•</span>
                <span><?php echo $topic->views; ?> views</span>
            </div>

            <div class="prose max-w-none">
                <?php echo nl2br($topic->content); ?>
            </div>
        </div>

        <!-- Replies Section -->
        <section id="replies" class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-8">Reacties (<?php echo count($replies); ?>)</h2>

            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" action="<?php echo URLROOT; ?>/forum/topic/<?php echo $topic->id; ?>#replies" 
                      class="mb-8">
                    <?php if ($reply_error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo $reply_error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 font-bold mb-2">Jouw reactie</label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="4"
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                                  required></textarea>
                    </div>

                    <button type="submit" 
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                        Plaats Reactie
                    </button>
                </form>
            <?php else: ?>
                <p class="mb-8 text-gray-600">
                    <a href="<?php echo URLROOT; ?>/login" class="text-primary hover:underline">Log in</a> 
                    om een reactie te plaatsen.
                </p>
            <?php endif; ?>

            <?php if(empty($replies)): ?>
                <p class="text-gray-600">Er zijn nog geen reacties.</p>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach($replies as $reply): ?>
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="font-bold"><?php echo $reply->author_name; ?></span>
                                    <span class="text-gray-500 text-sm ml-2">
                                        <?php echo date('d-m-Y H:i', strtotime($reply->created_at)); ?>
                                    </span>
                                </div>
                                <?php if(isset($_SESSION['user_id']) && 
                                        ($_SESSION['user_id'] == $reply->user_id || $_SESSION['is_admin'])): ?>
                                    <form method="POST" 
                                          action="<?php echo URLROOT; ?>/forum/reply/delete/<?php echo $reply->id; ?>"
                                          class="inline">
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800"
                                                onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen?')">
                                            Verwijderen
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="prose max-w-none">
                                <?php echo nl2br($reply->content); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </article>
</main>

<?php require_once '../views/templates/footer.php'; ?> 