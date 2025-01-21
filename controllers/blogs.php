<?php
$db = new Database();

// Haal alle blogs op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC");
$blogs = $db->resultSet();

require_once '../views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Blogs</h1>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo URLROOT; ?>/blogs/create" 
               class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                Nieuwe Blog
            </a>
        <?php endif; ?>
    </div>

    <?php if(empty($blogs)): ?>
        <div class="text-center py-12">
            <p class="text-gray-600 text-xl">Er zijn nog geen blogs geplaatst.</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <p class="mt-4">
                    <a href="<?php echo URLROOT; ?>/blogs/create" class="text-primary hover:underline">
                        Wees de eerste die een blog schrijft!
                    </a>
                </p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($blogs as $blog): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if($blog->image_path): ?>
                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo $blog->image_path; ?>" 
                             alt="<?php echo $blog->title; ?>"
                             class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2">
                            <?php echo $blog->title; ?>
                        </h2>
                        <p class="text-gray-600 mb-4">
                            <?php echo substr($blog->summary, 0, 150) . '...'; ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <span>Door <?php echo $blog->author_name; ?></span>
                                <br>
                                <span><?php echo date('d-m-Y', strtotime($blog->published_at)); ?></span>
                            </div>
                            <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                               class="text-secondary hover:underline">
                                Lees meer
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../views/templates/footer.php'; ?> 