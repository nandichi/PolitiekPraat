<?php
if (!isset($params['slug'])) {
    header('Location: ' . URLROOT . '/blogs');
    exit;
}

$db = new Database();

// Haal blog op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           WHERE blogs.slug = :slug");
$db->bind(':slug', $params['slug']);
$blog = $db->single();

if (!$blog) {
    header('Location: ' . URLROOT . '/404');
    exit;
}

// Update views
$db->query("UPDATE blogs SET views = views + 1 WHERE id = :id");
$db->bind(':id', $blog->id);
$db->execute();

// Haal comments op
$db->query("SELECT comments.*, users.username as author_name 
           FROM comments 
           JOIN users ON comments.user_id = users.id 
           WHERE comments.blog_id = :blog_id 
           ORDER BY comments.created_at DESC");
$db->bind(':blog_id', $blog->id);
$comments = $db->resultSet();

// Comment toevoegen
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (empty($content)) {
        $comment_error = 'Vul een reactie in';
    } else {
        $db->query("INSERT INTO comments (blog_id, user_id, content) VALUES (:blog_id, :user_id, :content)");
        $db->bind(':blog_id', $blog->id);
        $db->bind(':user_id', $_SESSION['user_id']);
        $db->bind(':content', $content);
        
        if ($db->execute()) {
            header('Location: ' . URLROOT . '/blogs/view/' . $blog->slug . '#comments');
            exit;
        } else {
            $comment_error = 'Er is iets misgegaan bij het plaatsen van je reactie';
        }
    }
}

require_once '../views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <article class="max-w-4xl mx-auto">
        <?php if($blog->image_path): ?>
            <img src="<?php echo URLROOT; ?>/public/images/<?php echo $blog->image_path; ?>" 
                 alt="<?php echo $blog->title; ?>"
                 class="w-full h-96 object-cover rounded-lg mb-8">
        <?php endif; ?>

        <h1 class="text-4xl font-bold mb-4"><?php echo $blog->title; ?></h1>
        
        <div class="flex items-center text-gray-500 mb-8">
            <span>Door <?php echo $blog->author_name; ?></span>
            <span class="mx-2">•</span>
            <span><?php echo date('d-m-Y', strtotime($blog->published_at)); ?></span>
            <span class="mx-2">•</span>
            <span><?php echo $blog->views; ?> views</span>
        </div>

        <div class="prose max-w-none mb-12">
            <?php echo nl2br($blog->content); ?>
        </div>

        <!-- Comments Section -->
        <section id="comments" class="mt-12 pt-12 border-t">
            <h2 class="text-2xl font-bold mb-8">Reacties (<?php echo count($comments); ?>)</h2>

            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" action="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>#comments" 
                      class="mb-8">
                    <?php if ($comment_error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo $comment_error; ?>
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

            <?php if(empty($comments)): ?>
                <p class="text-gray-600">Er zijn nog geen reacties.</p>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach($comments as $comment): ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="font-bold"><?php echo $comment->author_name; ?></span>
                                    <span class="text-gray-500 text-sm ml-2">
                                        <?php echo date('d-m-Y H:i', strtotime($comment->created_at)); ?>
                                    </span>
                                </div>
                                <?php if(isset($_SESSION['user_id']) && 
                                        ($_SESSION['user_id'] == $comment->user_id || $_SESSION['is_admin'])): ?>
                                    <form method="POST" 
                                          action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                          class="inline">
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800"
                                                onclick="return confirm('Weet je zeker dat je deze reactie wilt verwijderen?')">
                                            Verwijderen
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="text-gray-700"><?php echo nl2br($comment->content); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </article>
</main>

<?php require_once '../views/templates/footer.php'; ?> 