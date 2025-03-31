<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-primary to-secondary h-32 relative">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 opacity-10">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                            <path fill="#FFFFFF" d="M47.1,-51.2C59.5,-35.6,67.2,-17.8,66.2,-1C65.1,15.9,55.4,31.8,42.9,45.3C30.3,58.9,15.2,70,-1.4,71.7C-18,73.3,-36,65.4,-48.7,51.9C-61.5,38.5,-69,19.2,-70.3,-1.2C-71.5,-21.6,-66.5,-43.3,-53.4,-58.8C-40.3,-74.2,-20.2,-83.5,-0.9,-82.5C18.3,-81.5,36.6,-70.2,47.1,-51.2Z" transform="translate(100 100)" />
                        </svg>
                    </div>
                    
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
                <!-- Tab navigation -->
                <div class="flex mb-6 border-b">
                    <button type="button" class="px-4 py-2 text-primary font-medium border-b-2 border-primary focus:outline-none" id="tab-account">
                        Account Gegevens
                    </button>
                    <button type="button" class="px-4 py-2 text-gray-500 font-medium hover:text-primary focus:outline-none" id="tab-privacy">
                        Privacy Instellingen
                    </button>
                    <button type="button" class="px-4 py-2 text-gray-500 font-medium hover:text-primary focus:outline-none" id="tab-notifications">
                        Notificatie Voorkeuren
                    </button>
                </div>
                
                <form method="POST" class="space-y-6" id="profile-form" enctype="multipart/form-data">
                    <!-- Tab: Account Gegevens -->
                    <div class="tab-content" id="content-account">
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Profielfoto Upload -->
                            <div class="md:col-span-2 flex flex-col sm:flex-row items-start sm:items-center mb-4 pb-4 border-b border-gray-100">
                                <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary/80 rounded-xl flex items-center justify-center text-3xl font-bold text-white mr-6">
                                    <?php if (!empty($user['profile_photo'])): ?>
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($user['profile_photo']); ?>" 
                                             alt="Profielfoto" class="w-full h-full rounded-xl object-cover">
                                    <?php else: ?>
                                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="mb-2">
                                        <h3 class="text-gray-800 font-medium text-sm">Profielfoto</h3>
                                        <p class="text-gray-500 text-xs">Personaliseer je profiel met een profielfoto</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <label for="profile_photo_input" 
                                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm 
                                                     text-sm font-medium rounded-lg text-gray-700 bg-white 
                                                     hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                                     focus:ring-primary transition-colors duration-200 cursor-pointer">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Uploaden
                                        </label>
                                        <input type="file" id="profile_photo_input" name="profile_photo" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <button type="button" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm 
                                                      text-sm font-medium rounded-lg text-red-600 bg-red-50 
                                                      hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                                      focus:ring-red-500 transition-colors duration-200"
                                                id="remove_photo_button" 
                                                <?php echo empty($user['profile_photo']) ? 'disabled' : ''; ?>>
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Verwijderen
                                        </button>
                                        <input type="hidden" id="remove_photo" name="remove_photo" value="0">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Ondersteunde formaten: JPG, PNG, GIF, WEBP. Max 5MB.</p>
                                </div>
                            </div>

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
                                <p class="mt-1 text-xs text-gray-500">Deze naam is zichtbaar voor andere gebruikers</p>
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
                                <p class="mt-1 text-xs text-gray-500">Dit e-mailadres wordt niet publiek gedeeld</p>
                            </div>
                            
                            <!-- Bio -->
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Over mij <span class="text-gray-400 font-normal">(optioneel)</span>
                                </label>
                                <textarea id="bio" name="bio" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg
                                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                               transition-colors duration-200"
                                          placeholder="Vertel iets over jezelf..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                <p class="mt-1 text-xs text-gray-500">Een korte introductie die op je profielpagina wordt weergegeven</p>
                            </div>
                            
                            <!-- Wachtwoord wijzigen - Section Title -->
                            <div class="md:col-span-2 mt-4 pt-4 border-t border-gray-100">
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Wachtwoord wijzigen</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Laat deze velden leeg als je je wachtwoord niet wilt wijzigen.
                                </p>
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
                                        placeholder="Nieuw wachtwoord"
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
                                        placeholder="Bevestig nieuw wachtwoord"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg
                                                focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                                transition-colors duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Privacy Instellingen -->
                    <div class="tab-content hidden" id="content-privacy">
                        <div class="space-y-6">
                            <div class="pb-4 mb-4 border-b border-gray-100">
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Profiel zichtbaarheid</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Bepaal wie jouw profiel kan bekijken en wat er getoond wordt.
                                </p>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="profile_public" name="profile_public" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="profile_public" class="font-medium text-gray-700">Publiek profiel</label>
                                            <p class="text-gray-500">Iedereen kan je profiel bekijken</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="show_email" name="show_email"
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="show_email" class="font-medium text-gray-700">Toon e-mailadres</label>
                                            <p class="text-gray-500">Andere gebruikers kunnen je e-mailadres zien</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="show_activity" name="show_activity" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="show_activity" class="font-medium text-gray-700">Toon activiteit</label>
                                            <p class="text-gray-500">Toon je recente activiteit op je profielpagina</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Gegevens delen</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Controle over welke gegevens worden gedeeld voor analyse en verbetering.
                                </p>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="data_analytics" name="data_analytics" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="data_analytics" class="font-medium text-gray-700">Analysegegevens</label>
                                            <p class="text-gray-500">Deel gegevens over je gebruik van de site voor verbetering van onze diensten</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Notificatie Voorkeuren -->
                    <div class="tab-content hidden" id="content-notifications">
                        <div class="space-y-6">
                            <div class="pb-4 mb-4 border-b border-gray-100">
                                <h3 class="text-lg font-medium text-gray-800 mb-2">E-mail notificaties</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Stel in voor welke activiteiten je e-mails wilt ontvangen.
                                </p>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="notify_comments" name="notify_comments" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notify_comments" class="font-medium text-gray-700">Reacties</label>
                                            <p class="text-gray-500">Ontvang een e-mail wanneer iemand reageert op je blogs</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="notify_replies" name="notify_replies" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notify_replies" class="font-medium text-gray-700">Antwoorden</label>
                                            <p class="text-gray-500">Ontvang een e-mail wanneer iemand reageert op je forum berichten</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="notify_newsletter" name="notify_newsletter" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notify_newsletter" class="font-medium text-gray-700">Nieuwsbrief</label>
                                            <p class="text-gray-500">Ontvang onze wekelijkse nieuwsbrief met politiek nieuws en updates</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Website meldingen</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Bepaal welke meldingen je op de website wilt zien.
                                </p>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="notify_site_comments" name="notify_site_comments" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notify_site_comments" class="font-medium text-gray-700">Reactie meldingen</label>
                                            <p class="text-gray-500">Ontvang meldingen op de website voor nieuwe reacties</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="notify_site_likes" name="notify_site_likes" checked
                                                  class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notify_site_likes" class="font-medium text-gray-700">Like meldingen</label>
                                            <p class="text-gray-500">Ontvang meldingen op de website voor nieuwe likes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
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

<!-- Tab Switching Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab elements
        const tabAccount = document.getElementById('tab-account');
        const tabPrivacy = document.getElementById('tab-privacy');
        const tabNotifications = document.getElementById('tab-notifications');
        
        // Content elements
        const contentAccount = document.getElementById('content-account');
        const contentPrivacy = document.getElementById('content-privacy');
        const contentNotifications = document.getElementById('content-notifications');
        
        // Tab switching functions
        function switchToAccount() {
            // Update active tab styling
            tabAccount.classList.add('text-primary', 'border-b-2', 'border-primary');
            tabAccount.classList.remove('text-gray-500');
            
            tabPrivacy.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabPrivacy.classList.add('text-gray-500');
            
            tabNotifications.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabNotifications.classList.add('text-gray-500');
            
            // Show/hide content
            contentAccount.classList.remove('hidden');
            contentPrivacy.classList.add('hidden');
            contentNotifications.classList.add('hidden');
        }
        
        function switchToPrivacy() {
            // Update active tab styling
            tabAccount.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabAccount.classList.add('text-gray-500');
            
            tabPrivacy.classList.add('text-primary', 'border-b-2', 'border-primary');
            tabPrivacy.classList.remove('text-gray-500');
            
            tabNotifications.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabNotifications.classList.add('text-gray-500');
            
            // Show/hide content
            contentAccount.classList.add('hidden');
            contentPrivacy.classList.remove('hidden');
            contentNotifications.classList.add('hidden');
        }
        
        function switchToNotifications() {
            // Update active tab styling
            tabAccount.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabAccount.classList.add('text-gray-500');
            
            tabPrivacy.classList.remove('text-primary', 'border-b-2', 'border-primary');
            tabPrivacy.classList.add('text-gray-500');
            
            tabNotifications.classList.add('text-primary', 'border-b-2', 'border-primary');
            tabNotifications.classList.remove('text-gray-500');
            
            // Show/hide content
            contentAccount.classList.add('hidden');
            contentPrivacy.classList.add('hidden');
            contentNotifications.classList.remove('hidden');
        }
        
        // Event listeners
        tabAccount.addEventListener('click', switchToAccount);
        tabPrivacy.addEventListener('click', switchToPrivacy);
        tabNotifications.addEventListener('click', switchToNotifications);
        
        // Initialize with account tab active
        switchToAccount();
        
        // Form submission - include all fields from all tabs
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            // The form will submit all visible and hidden fields
        });
        
        // Profile Photo Handling
        const profilePhotoInput = document.getElementById('profile_photo_input');
        const removePhotoButton = document.getElementById('remove_photo_button');
        const removePhotoInput = document.getElementById('remove_photo');
        const profilePhotoPreview = document.querySelector('.w-20.h-20.rounded-xl');
        
        // Handle file selection
        profilePhotoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Alleen JPG, PNG, GIF of WEBP afbeeldingen zijn toegestaan.');
                    this.value = ''; // Clear the input
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('De afbeelding mag niet groter zijn dan 5MB.');
                    this.value = ''; // Clear the input
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update image preview
                    let imgElement = profilePhotoPreview.querySelector('img');
                    if (!imgElement) {
                        // Remove the text content (initial)
                        profilePhotoPreview.textContent = '';
                        // Create new image element
                        imgElement = document.createElement('img');
                        imgElement.classList.add('w-full', 'h-full', 'rounded-xl', 'object-cover');
                        imgElement.alt = 'Profielfoto';
                        profilePhotoPreview.appendChild(imgElement);
                    }
                    imgElement.src = e.target.result;
                    
                    // Enable remove button
                    removePhotoButton.disabled = false;
                    
                    // Reset remove flag (in case it was set)
                    removePhotoInput.value = '0';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Handle remove button click
        removePhotoButton.addEventListener('click', function() {
            // Reset the file input
            profilePhotoInput.value = '';
            
            // Set the remove flag
            removePhotoInput.value = '1';
            
            // Remove the image preview
            const imgElement = profilePhotoPreview.querySelector('img');
            if (imgElement) {
                profilePhotoPreview.removeChild(imgElement);
                // Add back the initial letter
                profilePhotoPreview.textContent = '<?php echo strtoupper(substr($user['username'], 0, 1)); ?>';
            }
            
            // Disable remove button
            this.disabled = true;
        });
    });
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?> 