<?php
$db = new Database();

// Haal de laatste 6 blogs op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

require_once '../views/templates/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="bg-primary text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">
                Welkom bij PolitiekPraat
            </h1>
            <p class="text-xl md:text-2xl mb-8">
                Het platform voor open discussie over Nederlandse politiek
            </p>
            <a href="<?php echo URLROOT; ?>/blogs" 
               class="bg-secondary text-white px-8 py-3 rounded-lg hover:bg-opacity-90 transition">
                Bekijk onze blogs
            </a>
        </div>
    </section>

    <!-- Laatste Blogs Section -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8">Laatste Blogs</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($latest_blogs as $blog): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if($blog->image_path): ?>
                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo $blog->image_path; ?>" 
                             alt="<?php echo $blog->title; ?>"
                             class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">
                            <?php echo $blog->title; ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php echo substr($blog->summary, 0, 150) . '...'; ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                Door <?php echo $blog->author_name; ?>
                            </span>
                            <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                               class="text-secondary hover:underline">
                                Lees meer
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once '../views/templates/footer.php'; ?> 