<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<!-- Enhanced Background with Modern Patterns -->
<div class="fixed inset-0 z-0">
    <!-- Primary gradient background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-secondary opacity-5"></div>
    
    <!-- Decorative mesh pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23818CF8\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M20 20c0-11.046-8.954-20-20-20v20h20zM0 20v20h20c0-11.046-8.954-20-20-20z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
    </div>
    
    <!-- Floating orbs for ambient lighting -->
    <div class="absolute top-20 left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-secondary/10 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-primary-light/5 rounded-full blur-3xl opacity-50"></div>
</div>

<main class="min-h-screen relative z-10">
    <!-- Content Section -->
    <section class="py-12 sm:py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                
                <!-- Action Buttons Section -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4" data-aos="fade-up">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Blog Overzicht</h2>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?php echo URLROOT; ?>/blogs/create" 
                           class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-2xl hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="relative z-10">Nieuwe Blog Schrijven</span>
                        </a>
                        
                        <button class="group inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-semibold rounded-2xl hover:border-primary hover:text-primary transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter & Zoek
                        </button>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="bg-white rounded-3xl shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <!-- Decorative header -->
                    <div class="h-2 bg-gradient-to-r from-primary-dark via-primary to-secondary"></div>
                    
                    <?php if(empty($blogs)): ?>
                        <!-- Empty State -->
                        <div class="p-8 sm:p-16 text-center">
                            <div class="relative inline-block mb-8">
                                <div class="w-32 h-32 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center mx-auto">
                                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                </div>
                                <!-- Decorative elements -->
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-secondary rounded-full"></div>
                                <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-primary rounded-full"></div>
                            </div>
                            
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Je hebt nog geen blogs geschreven</h3>
                            <p class="text-gray-600 mb-8 text-lg max-w-md mx-auto leading-relaxed">
                                Deel je gedachten en inzichten over politieke onderwerpen die jou interesseren en bereik een groter publiek.
                            </p>
                            
                            <a href="<?php echo URLROOT; ?>/blogs/create" 
                               class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-2xl hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 transform hover:scale-105">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Eerste Blog Schrijven
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Desktop Table View -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Blog Informatie
                                        </th>
                                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Publicatiedatum
                                        </th>
                                        <th scope="col" class="px-8 py-5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Engagement
                                        </th>
                                        <th scope="col" class="px-8 py-5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach($blogs as $index => $blog): ?>
                                        <tr class="group hover:bg-gradient-to-r hover:from-primary/2 hover:to-secondary/2 transition-all duration-300">
                                            <td class="px-8 py-6">
                                                <div class="flex items-center">
                                                    <div class="relative">
                                                        <?php if($blog->image_path): ?>
                                                            <div class="flex-shrink-0 h-20 w-20 mr-6 relative">
                                                                <img class="h-20 w-20 rounded-2xl object-cover shadow-lg ring-2 ring-white" 
                                                                     src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
                                                                     alt="<?php echo htmlspecialchars($blog->title); ?>">
                                                                <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/20 to-transparent"></div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="flex-shrink-0 h-20 w-20 mr-6 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl flex items-center justify-center ring-2 ring-white shadow-lg">
                                                                <svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-lg font-bold text-gray-900 line-clamp-1 group-hover:text-primary transition-colors">
                                                            <?php echo htmlspecialchars($blog->title); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-600 line-clamp-2 mt-1 leading-relaxed">
                                                            <?php echo htmlspecialchars($blog->summary); ?>
                                                        </div>
                                                        <div class="flex items-center mt-2 space-x-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                                Blog #<?php echo $blog->id; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        <?php echo date('d-m-Y', strtotime($blog->published_at)); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo date('H:i', strtotime($blog->published_at)); ?> uur
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6">
                                                <div class="flex justify-center">
                                                    <div class="flex items-center px-4 py-2 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-2xl border border-primary/20">
                                                        <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span class="text-sm font-bold text-gray-900">
                                                            <?php echo $blog->likes; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="<?php echo URLROOT; ?>/blogs/<?php echo $blog->slug; ?>" 
                                                       class="group/btn inline-flex items-center px-3 py-2 border-2 border-gray-200 rounded-xl text-gray-600 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Bekijken
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>"
                                                       class="group/btn inline-flex items-center px-3 py-2 border-2 border-primary/30 rounded-xl text-primary bg-white hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Bewerken
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>"
                                                       class="group/btn inline-flex items-center px-3 py-2 border-2 border-blue-300 rounded-xl text-blue-600 bg-white hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        Likes
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/blogs/delete/<?php echo $blog->id; ?>"
                                                       onclick="return confirm('Weet je zeker dat je deze blog wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')"
                                                       class="group/btn inline-flex items-center px-3 py-2 border-2 border-red-300 rounded-xl text-red-600 bg-white hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        
                        <!-- Mobile/Tablet Card View -->
                        <div class="lg:hidden divide-y divide-gray-100">
                            <?php foreach($blogs as $blog): ?>
                                <div class="p-6 hover:bg-gradient-to-r hover:from-primary/2 hover:to-secondary/2 transition-all duration-300">
                                    <div class="flex items-start space-x-4">
                                        <!-- Image -->
                                        <div class="flex-shrink-0">
                                            <?php if($blog->image_path): ?>
                                                <div class="h-20 w-20 relative">
                                                    <img class="h-20 w-20 rounded-2xl object-cover shadow-lg ring-2 ring-white" 
                                                         src="<?php echo getBlogImageUrl($blog->image_path); ?>" 
                                                         alt="<?php echo htmlspecialchars($blog->title); ?>">
                                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/20 to-transparent"></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="h-20 w-20 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl flex items-center justify-center ring-2 ring-white shadow-lg">
                                                    <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a2 2 0 012.8 0L16 17m-2-2l1.6-1.6a2 2 0 012.8 0L20 15m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1 mb-1">
                                                <?php echo htmlspecialchars($blog->title); ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-3 leading-relaxed">
                                                <?php echo htmlspecialchars($blog->summary); ?>
                                            </p>
                                            
                                            <!-- Meta Info -->
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center space-x-4">
                                                    <div class="text-xs text-gray-500">
                                                        <?php echo date('d-m-Y', strtotime($blog->published_at)); ?> 
                                                        <span class="mx-1">•</span>
                                                        <?php echo date('H:i', strtotime($blog->published_at)); ?>
                                                    </div>
                                                    <div class="flex items-center px-2 py-1 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg border border-primary/20">
                                                        <svg class="w-3 h-3 text-primary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span class="text-xs font-semibold text-gray-900">
                                                            <?php echo $blog->likes; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex flex-wrap gap-2">
                                                <a href="<?php echo URLROOT; ?>/blogs/<?php echo $blog->slug; ?>" 
                                                   class="inline-flex items-center px-3 py-1.5 border-2 border-gray-200 rounded-xl text-xs text-gray-600 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Bekijken
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/blogs/edit/<?php echo $blog->id; ?>"
                                                   class="inline-flex items-center px-3 py-1.5 border-2 border-primary/30 rounded-xl text-xs text-primary bg-white hover:bg-primary hover:text-white transition-all duration-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Bewerken
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/blogs/updateLikes/<?php echo $blog->id; ?>"
                                                   class="md:inline-flex hidden items-center px-3 py-1.5 border-2 border-blue-300 rounded-xl text-xs text-blue-600 bg-white hover:bg-blue-600 hover:text-white transition-all duration-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    Likes
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/blogs/delete/<?php echo $blog->id; ?>"
                                                   onclick="return confirm('Weet je zeker dat je deze blog wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')"
                                                   class="inline-flex items-center px-3 py-1.5 border-2 border-red-300 rounded-xl text-xs text-red-600 bg-white hover:bg-red-600 hover:text-white transition-all duration-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <!-- Enhanced Pagination -->
                    <div class="mt-12 flex justify-center" data-aos="fade-up" data-aos-delay="200">
                        <nav class="relative">
                            <!-- Background decoration -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-primary/5 rounded-2xl blur-xl"></div>
                            
                            <div class="relative flex flex-wrap items-center justify-center bg-white/80 backdrop-blur-sm px-4 sm:px-6 py-4 rounded-2xl shadow-lg border border-gray-200/50">
                                
                                <!-- Previous Page Button -->
                                <?php if ($paginationData['currentPage'] > 1): ?>
                                    <a href="<?php echo URLROOT; ?>/blogs/manage?page=<?php echo $paginationData['currentPage'] - 1; ?>" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white rounded-xl border-2 border-gray-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 m-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Vorige</span>
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-xl border-2 border-gray-200 cursor-not-allowed m-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Vorige</span>
                                    </span>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <div class="flex flex-wrap justify-center mx-2 sm:mx-6">
                                    <?php 
                                        $startPage = max(1, $paginationData['currentPage'] - 2);
                                        $endPage = min($paginationData['totalPages'], $paginationData['currentPage'] + 2);
                                        
                                        // Always show first page if we're not starting at 1
                                        if ($startPage > 1) {
                                            echo '<a href="' . URLROOT . '/blogs/manage?page=1" class="inline-flex items-center justify-center w-10 h-10 mx-1 text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-110 ' . (1 == $paginationData['currentPage'] ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-gray-700 hover:bg-primary/10 hover:text-primary') . '">1</a>';
                                            
                                            // Add ellipsis if needed
                                            if ($startPage > 2) {
                                                echo '<span class="inline-flex items-center justify-center w-10 h-10 mx-1 text-gray-400">...</span>';
                                            }
                                        }
                                        
                                        // Page links
                                        for ($i = $startPage; $i <= $endPage; $i++) {
                                            echo '<a href="' . URLROOT . '/blogs/manage?page=' . $i . '" class="inline-flex items-center justify-center w-10 h-10 mx-1 text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-110 ' . ($i == $paginationData['currentPage'] ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-gray-700 hover:bg-primary/10 hover:text-primary') . '">' . $i . '</a>';
                                        }
                                        
                                        // Always show last page if we're not ending at last page
                                        if ($endPage < $paginationData['totalPages']) {
                                            // Add ellipsis if needed
                                            if ($endPage < $paginationData['totalPages'] - 1) {
                                                echo '<span class="inline-flex items-center justify-center w-10 h-10 mx-1 text-gray-400">...</span>';
                                            }
                                            
                                            echo '<a href="' . URLROOT . '/blogs/manage?page=' . $paginationData['totalPages'] . '" class="inline-flex items-center justify-center w-10 h-10 mx-1 text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-110 ' . ($paginationData['totalPages'] == $paginationData['currentPage'] ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-gray-700 hover:bg-primary/10 hover:text-primary') . '">' . $paginationData['totalPages'] . '</a>';
                                        }
                                    ?>
                                </div>

                                <!-- Next Page Button -->
                                <?php if ($paginationData['currentPage'] < $paginationData['totalPages']): ?>
                                    <a href="<?php echo URLROOT; ?>/blogs/manage?page=<?php echo $paginationData['currentPage'] + 1; ?>" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white rounded-xl border-2 border-gray-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 m-1">
                                        <span class="hidden sm:inline">Volgende</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-xl border-2 border-gray-200 cursor-not-allowed m-1">
                                        <span class="hidden sm:inline">Volgende</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </nav>
                    </div>
                    
                    <!-- Enhanced Blog Statistics -->
                    <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50">
                            <div class="flex items-center space-x-6 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-900"><?php echo count($blogs); ?></span> van 
                                        <span class="font-semibold text-gray-900"><?php echo $paginationData['totalBlogs']; ?></span> blogs
                                    </span>
                                </div>
                                <div class="w-px h-4 bg-gray-300"></div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-secondary rounded-full"></div>
                                    <span class="text-gray-600">
                                        Pagina <span class="font-semibold text-gray-900"><?php echo $paginationData['currentPage']; ?></span> van 
                                        <span class="font-semibold text-gray-900"><?php echo $paginationData['totalPages']; ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- Enhanced JavaScript with Modern Interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            offset: 100,
            once: true,
            easing: 'ease-out-cubic'
        });
    }
    
    // Add smooth hover effects for cards
    const blogRows = document.querySelectorAll('tr.group, .lg\\:hidden > div');
    blogRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('a[class*="bg-gradient"], button[class*="bg-gradient"]');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add floating animation to decorative elements
    const floatingElements = document.querySelectorAll('.absolute.top-10, .absolute.bottom-20');
    floatingElements.forEach((element, index) => {
        element.style.animation = `float ${3 + index * 0.5}s ease-in-out infinite`;
    });
    
    // Add parallax effect to background elements
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.absolute.top-20, .absolute.bottom-20');
        
        parallaxElements.forEach((element, index) => {
            const speed = 0.5 + index * 0.1;
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
    
    // Enhanced loading states for action buttons
    const actionButtons = document.querySelectorAll('a[href*="/blogs/"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.href.includes('/delete/')) return; // Skip delete buttons
            
            const originalText = this.innerHTML;
            this.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Laden...
            `;
            
            // Reset after 3 seconds if page hasn't changed
            setTimeout(() => {
                if (document.contains(this)) {
                    this.innerHTML = originalText;
                }
            }, 3000);
        });
    });
});

// CSS for ripple effect and floating animation
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    .backdrop-blur {
        backdrop-filter: blur(8px);
    }
`;
document.head.appendChild(style);
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 