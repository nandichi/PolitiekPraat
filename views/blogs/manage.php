<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<!-- Decoratieve achtergrond patronen -->
<div class="fixed inset-0 z-0 opacity-10">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239C92AC\" fill-opacity=\"0.15\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
</div>

<main class="min-h-screen py-8 sm:py-12 relative z-10 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Decoratieve elementen -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-secondary/10 to-transparent"></div>
    
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header Sectie -->
            <div class="text-center mb-8 sm:mb-12" data-aos="fade-down">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4 relative inline-block">
                    <span class="relative z-10">Mijn Blogs Beheren</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-1"></div>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Beheer, bewerk en verwijder je persoonlijke blogs
                </p>
            </div>

            <!-- Actieknoppen -->
            <div class="flex justify-center sm:justify-end mb-6">
                <a href="<?php echo URLROOT; ?>/blogs/create" 
                   class="inline-flex items-center px-4 sm:px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nieuwe Blog Schrijven
                </a>
            </div>

            <!-- Blog Tabel/Cards -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl" data-aos="fade-up">
                <!-- Decoratieve header -->
                <div class="bg-gradient-to-r from-primary to-secondary h-2"></div>
                
                <?php if(empty($blogs)): ?>
                    <div class="p-6 sm:p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Je hebt nog geen blogs geschreven</h3>
                        <p class="text-gray-600 mb-6">Deel je gedachten en inzichten over politieke onderwerpen die jou interesseren.</p>
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Eerste Blog Schrijven
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Desktop View (tabel) - Verborgen op mobiel -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Blog
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Datum
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Likes
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acties
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($blogs as $blog): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <?php if($blog->image_path): ?>
                                                    <div class="flex-shrink-0 h-16 w-16 mr-4">
                                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                                             src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                             alt="<?php echo htmlspecialchars($blog->title); ?>">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex-shrink-0 h-16 w-16 mr-4 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="ml-1">
                                                    <div class="text-base font-semibold text-gray-900 line-clamp-1 max-w-md">
                                                        <?php echo htmlspecialchars($blog->title); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500 line-clamp-2 max-w-md">
                                                        <?php echo htmlspecialchars($blog->summary); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('d-m-Y', strtotime($blog->published_at)); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('H:i', strtotime($blog->published_at)); ?> uur
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center">
                                                <div class="flex items-center px-3 py-1 bg-primary/5 rounded-lg">
                                                    <svg class="w-5 h-5 text-primary/70 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">
                                                        <?php echo $blog->likes; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                                                   class="group inline-flex items-center px-3 py-2 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Bekijken
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>"
                                                   class="group inline-flex items-center px-3 py-2 border border-primary/30 rounded-lg text-primary bg-white hover:bg-primary/5 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5 text-primary/70 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Bewerken
                                                </a>
                                                <?php /* Tijdelijk verborgen
                                                <a href="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>"
                                                   class="group inline-flex items-center px-3 py-2 border border-blue-300 rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    Likes Aanpassen
                                                </a>
                                                */ ?>
                                                <a href="<?php echo URLROOT; ?>/blogs/delete/<?php echo $blog->id; ?>"
                                                   onclick="return confirm('Weet je zeker dat je deze blog wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')"
                                                   class="group inline-flex items-center px-3 py-2 border border-red-300 rounded-lg text-red-600 bg-white hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Verwijderen
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobiele weergave (cards) -->
                    <div class="md:hidden">
                        <?php foreach($blogs as $blog): ?>
                            <div class="p-4 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-start">
                                    <?php if($blog->image_path): ?>
                                        <div class="flex-shrink-0 h-16 w-16 mr-3">
                                            <img class="h-16 w-16 rounded-lg object-cover" 
                                                 src="<?php echo URLROOT . '/' . $blog->image_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($blog->title); ?>">
                                        </div>
                                    <?php else: ?>
                                        <div class="flex-shrink-0 h-16 w-16 mr-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 line-clamp-1">
                                            <?php echo htmlspecialchars($blog->title); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500 line-clamp-2 mb-2">
                                            <?php echo htmlspecialchars($blog->summary); ?>
                                        </p>
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-xs text-gray-500">
                                                <?php echo date('d-m-Y', strtotime($blog->published_at)); ?> 
                                                <span class="mx-1">•</span>
                                                <?php echo date('H:i', strtotime($blog->published_at)); ?> uur
                                            </div>
                                            <div class="flex items-center px-2 py-1 bg-primary/5 rounded-md">
                                                <svg class="w-4 h-4 text-primary/70 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="text-xs font-medium text-gray-700">
                                                    <?php echo $blog->likes; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                                               class="group inline-flex items-center px-2 py-1 border border-gray-200 rounded-md text-xs text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                                <svg class="w-3 h-3 mr-1 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Bekijken
                                            </a>
                                            <a href="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>"
                                               class="group inline-flex items-center px-2 py-1 border border-primary/30 rounded-md text-xs text-primary bg-white hover:bg-primary/5 transition-colors">
                                                <svg class="w-3 h-3 mr-1 text-primary/70 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Bewerken
                                            </a>
                                            <?php /* Tijdelijk verborgen
                                            <a href="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>"
                                               class="group inline-flex items-center px-2 py-1 border border-blue-300 rounded-md text-xs text-blue-600 bg-white hover:bg-blue-50 transition-colors">
                                                <svg class="w-3 h-3 mr-1 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                Likes
                                            </a>
                                            */ ?>
                                            <a href="<?php echo URLROOT; ?>/blogs/delete/<?php echo $blog->id; ?>"
                                               onclick="return confirm('Weet je zeker dat je deze blog wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')"
                                               class="group inline-flex items-center px-2 py-1 border border-red-300 rounded-md text-xs text-red-600 bg-white hover:bg-red-50 transition-colors">
                                                <svg class="w-3 h-3 mr-1 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Verwijderen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($blogs) && $paginationData['totalPages'] > 1): ?>
                <!-- Pagination - Responsieve versie -->
                <div class="mt-8 flex justify-center" data-aos="fade-up" data-aos-delay="100">
                    <nav class="flex flex-wrap items-center justify-center bg-white px-3 sm:px-4 py-3 rounded-xl shadow-md w-full sm:w-auto">
                        <!-- Previous Page Button -->
                        <?php if ($paginationData['currentPage'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/blogs/manage?page=<?php echo $paginationData['currentPage'] - 1; ?>" 
                               class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 hover:text-primary m-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span class="hidden sm:inline">Vorige</span>
                            </a>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 text-sm font-medium text-gray-400 bg-white rounded-lg border border-gray-200 cursor-not-allowed m-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span class="hidden sm:inline">Vorige</span>
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers - Meer compact op mobiel -->
                        <div class="flex flex-wrap justify-center mx-1 sm:mx-4">
                            <span class="font-medium text-sm text-gray-700">
                                <?php 
                                    // Show page range - show fewer pages on mobile
                                    $startPage = max(1, $paginationData['currentPage'] - (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobi') !== false) ? 1 : 2));
                                    $endPage = min($paginationData['totalPages'], $paginationData['currentPage'] + (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobi') !== false) ? 1 : 2));
                                    
                                    // Always show first page if we're not starting at 1
                                    if ($startPage > 1) {
                                        echo '<a href="' . URLROOT . '/blogs/manage?page=1" class="px-2 sm:px-3 py-1 mx-0.5 sm:mx-1 rounded-md ' . (1 == $paginationData['currentPage'] ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-100') . '">1</a>';
                                        
                                        // Add ellipsis if needed
                                        if ($startPage > 2) {
                                            echo '<span class="mx-0.5 sm:mx-1 px-1 py-1">...</span>';
                                        }
                                    }
                                    
                                    // Page links
                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        echo '<a href="' . URLROOT . '/blogs/manage?page=' . $i . '" class="px-2 sm:px-3 py-1 mx-0.5 sm:mx-1 rounded-md ' . ($i == $paginationData['currentPage'] ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-100') . '">' . $i . '</a>';
                                    }
                                    
                                    // Always show last page if we're not ending at last page
                                    if ($endPage < $paginationData['totalPages']) {
                                        // Add ellipsis if needed
                                        if ($endPage < $paginationData['totalPages'] - 1) {
                                            echo '<span class="mx-0.5 sm:mx-1 px-1 py-1">...</span>';
                                        }
                                        
                                        echo '<a href="' . URLROOT . '/blogs/manage?page=' . $paginationData['totalPages'] . '" class="px-2 sm:px-3 py-1 mx-0.5 sm:mx-1 rounded-md ' . ($paginationData['totalPages'] == $paginationData['currentPage'] ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-100') . '">' . $paginationData['totalPages'] . '</a>';
                                    }
                                ?>
                            </span>
                        </div>

                        <!-- Next Page Button -->
                        <?php if ($paginationData['currentPage'] < $paginationData['totalPages']): ?>
                            <a href="<?php echo URLROOT; ?>/blogs/manage?page=<?php echo $paginationData['currentPage'] + 1; ?>" 
                               class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 hover:text-primary m-1">
                                <span class="hidden sm:inline">Volgende</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 text-sm font-medium text-gray-400 bg-white rounded-lg border border-gray-200 cursor-not-allowed m-1">
                                <span class="hidden sm:inline">Volgende</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </nav>
                </div>
                
                <!-- Blog Count - Responsief -->
                <div class="mt-4 text-center text-xs sm:text-sm text-gray-500">
                    <?php if (count($blogs) > 0): ?>
                        Toont <?php echo count($blogs); ?> van <?php echo $paginationData['totalBlogs']; ?> blogs (pagina <?php echo $paginationData['currentPage']; ?> van <?php echo $paginationData['totalPages']; ?>)
                    <?php else: ?>
                        Geen blogs gevonden.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll) - if your website uses it
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            offset: 100,
            once: true
        });
    }
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 