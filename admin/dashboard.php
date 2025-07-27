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

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.blog-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.blog-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .mobile-stack {
        flex-direction: column;
        gap: 1rem;
    }
    
    .mobile-full {
        width: 100%;
    }
    
    .mobile-text-sm {
        font-size: 0.875rem;
    }
    
    .mobile-hidden {
        display: none;
    }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
                    <p class="text-blue-100 text-lg">Beheer blogs en content van PolitiekPraat</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center">
                        Stemwijzer Dashboard
                    </a>
                    <a href="../views/blogs/create.php" 
                       class="bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold text-center">
                        Nieuwe Blog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10 pb-12">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Blogs</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= count($blogs) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Views</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= array_sum(array_column($blogs, 'views')) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Likes</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= array_sum(array_column($blogs, 'likes')) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Recentste Blog</p>
                        <p class="text-sm md:text-base font-bold text-gray-800">
                            <?= !empty($blogs) ? formatDate($blogs[0]->published_at) : 'Geen blogs' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Management Section -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Blog Beheer</h2>
                        <p class="text-gray-600 text-sm">Alle blogs bekijken en beheren</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="../views/blogs/create.php" 
                           class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors text-center text-sm">
                            Nieuwe Blog Toevoegen
                        </a>
                        <a href="likes-beheer.php" 
                           class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition-colors text-center text-sm">
                            Likes Beheren
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <?php if (empty($blogs)): ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Geen blogs gevonden</h3>
                        <p class="text-gray-600 mb-4">Begin met het toevoegen van je eerste blog.</p>
                        <a href="../views/blogs/create.php" 
                           class="bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 transition-colors inline-block">
                            Eerste Blog Toevoegen
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Desktop Table View (hidden on mobile) -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Titel</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Auteur</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Datum</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Views</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Likes</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($blogs as $blog): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-4">
                                            <div class="font-semibold text-gray-800 hover:text-indigo-600 transition-colors">
                                                <a href="../views/blogs/view.php?id=<?= $blog->id ?>" class="hover:underline">
                                                    <?= htmlspecialchars($blog->title) ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600">
                                            <?= htmlspecialchars($blog->author_name) ?>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600">
                                            <?= formatDate($blog->published_at) ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                                                <?= number_format($blog->views) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-sm">
                                                <?= number_format($blog->likes) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex space-x-2">
                                                <a href="../views/blogs/edit.php?id=<?= $blog->id ?>" 
                                                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                                    Bewerken
                                                </a>
                                                <a href="../views/blogs/view.php?id=<?= $blog->id ?>" 
                                                   class="text-green-600 hover:text-green-800 font-medium text-sm">
                                                    Bekijken
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden space-y-4">
                        <?php foreach ($blogs as $blog): ?>
                            <div class="blog-card rounded-xl p-4 border border-gray-100">
                                <div class="flex flex-col space-y-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-800 mb-2 leading-tight">
                                            <a href="../views/blogs/view.php?id=<?= $blog->id ?>" class="hover:text-indigo-600 transition-colors">
                                                <?= htmlspecialchars($blog->title) ?>
                                            </a>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <?= htmlspecialchars($blog->author_name) ?>
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <?= formatDate($blog->published_at) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex space-x-4">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <?= number_format($blog->views) ?>
                                            </span>
                                            <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                                <?= number_format($blog->likes) ?>
                                            </span>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <a href="../views/blogs/edit.php?id=<?= $blog->id ?>" 
                                               class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-indigo-200 transition-colors">
                                                Bewerken
                                            </a>
                                            <a href="../views/blogs/view.php?id=<?= $blog->id ?>" 
                                               class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors">
                                                Bekijken
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../views/templates/footer.php'; ?> 