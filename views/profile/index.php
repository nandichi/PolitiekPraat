<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <?php if (!empty($success_message)): ?>
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded animate-fade-in" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <div class="max-w-4xl mx-auto">
            <!-- Profile Header -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-primary to-secondary h-32 relative">
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center text-3xl font-bold text-primary">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
                <div class="pt-16 pb-8 px-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p class="text-gray-600">Lid sinds <?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Persoonlijke Informatie -->
                <div class="bg-white rounded-xl shadow-lg p-6 scale-hover">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">Persoonlijke Informatie</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Gebruikersnaam</span>
                            <span class="text-gray-800 font-medium"><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">E-mailadres</span>
                            <span class="text-gray-800 font-medium"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Account Statistieken -->
                <div class="bg-white rounded-xl shadow-lg p-6 scale-hover">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">Account Statistieken</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <span class="block text-2xl font-bold text-primary">0</span>
                            <span class="text-sm text-gray-600">Blogs</span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <span class="block text-2xl font-bold text-secondary">0</span>
                            <span class="text-sm text-gray-600">Forum posts</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acties -->
            <div class="mt-6 flex justify-end">
                <a href="/profile/edit" 
                   class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-xl font-semibold 
                          shadow-lg hover:bg-primary/90 transition-all duration-300 scale-hover">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Profiel Bewerken
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?> 