<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-primary to-secondary h-32 relative">
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center text-3xl font-bold text-primary">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
                <div class="pt-16 pb-8 px-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Profiel Bewerken</h1>
                    <p class="text-gray-600">Pas je profielgegevens aan</p>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded animate-fade-in" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded animate-fade-in" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" class="space-y-6">
                    <!-- Gebruikersnaam -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Gebruikersnaam
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($user['username']); ?>" required
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                          transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-mailadres
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                          transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Wachtwoord -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nieuw Wachtwoord
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" id="new_password" name="new_password"
                                   placeholder="Laat leeg om wachtwoord niet te wijzigen"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                          transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Bevestig Wachtwoord -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Bevestig Nieuw Wachtwoord
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                          transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="/profile" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white 
                                  hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Annuleren
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white 
                                       bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary 
                                       transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Opslaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?> 