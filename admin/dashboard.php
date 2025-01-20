<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/BlogController.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$blogController = new BlogController();
$blogs = $blogController->getAll();

require_once '../views/templates/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <a href="create-blog.php" 
           class="bg-secondary text-white px-4 py-2 rounded hover:bg-opacity-90">
            Nieuwe Blog
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Titel
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Auteur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Datum
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Views
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acties
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo $blog->title; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo $blog->author_name; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo formatDate($blog->published_at); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo $blog->views; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="edit-blog.php?id=<?php echo $blog->id; ?>" 
                               class="text-primary hover:text-opacity-90 mr-3">
                                Bewerken
                            </a>
                            <a href="delete-blog.php?id=<?php echo $blog->id; ?>" 
                               class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Weet je zeker dat je deze blog wilt verwijderen?')">
                                Verwijderen
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../views/templates/footer.php'; ?> 