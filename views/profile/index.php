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
                    <!-- Add decorative elements -->
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
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($user['username']); ?></h1>
                            <p class="text-gray-600">Lid sinds <?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Actief lid
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Kolom 1: Persoonlijke Informatie -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-6 scale-hover mb-6">
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
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Account leeftijd</span>
                                <span class="text-gray-800 font-medium">
                                    <?php 
                                        $created = new DateTime($user['created_at']);
                                        $now = new DateTime();
                                        $interval = $created->diff($now);
                                        echo $interval->y > 0 ? $interval->y . ' jaar' : $interval->m . ' maanden';
                                    ?>
                                </span>
                            </div>
                            <?php if (!empty($user['bio'])): ?>
                            <div class="flex flex-col mt-4 pt-4 border-t border-gray-100">
                                <span class="text-sm text-gray-500 mb-1">Over mij</span>
                                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Privacy Instellingen -->
                    <div class="bg-white rounded-xl shadow-lg p-6 scale-hover">
                        <div class="flex items-center mb-6">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-800">Privacy Instellingen</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-800">Account zichtbaarheid</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Openbaar</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-800">Email notificaties</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Aan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Account Statistieken & Activiteit -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Account Statistieken - Uitgebreid -->
                    <div class="bg-white rounded-xl shadow-lg p-6 scale-hover">
                        <div class="flex items-center mb-6">
                            <svg class="w-6 h-6 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-800">Account Statistieken</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <span class="block text-2xl font-bold text-primary"><?php echo isset($stats['blogs']) ? $stats['blogs'] : 0; ?></span>
                                <span class="text-sm text-gray-600">Blogs</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <span class="block text-2xl font-bold text-accent"><?php echo isset($stats['comments']) ? $stats['comments'] : 0; ?></span>
                                <span class="text-sm text-gray-600">Reacties</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <span class="block text-2xl font-bold text-tertiary"><?php echo isset($stats['likes_received']) ? $stats['likes_received'] : 0; ?></span>
                                <span class="text-sm text-gray-600">Likes ontvangen</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="font-medium text-gray-700 mb-3">Activiteit Overzicht</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-primary bg-primary/10">
                                                Activiteit niveau
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-primary">
                                                <?php echo isset($stats['activity_level']) ? $stats['activity_level'] : '30%'; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-primary/10">
                                        <div style="width:<?php echo isset($stats['activity_level']) ? $stats['activity_level'] : '30%'; ?>" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recente Activiteit -->
                    <div class="bg-white rounded-xl shadow-lg p-6 scale-hover">
                        <div class="flex items-center mb-6">
                            <svg class="w-6 h-6 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-800">Recente Activiteit</h2>
                        </div>
                        
                        <?php if (!empty($recent_activity)): ?>
                            <div class="space-y-4">
                                <?php foreach ($recent_activity as $activity): ?>
                                    <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center mr-4">
                                            <?php if ($activity['type'] == 'blog'): ?>
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            <?php elseif ($activity['type'] == 'comment'): ?>
                                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-800 truncate">
                                                <?php echo htmlspecialchars($activity['title']); ?>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?php echo htmlspecialchars($activity['description']); ?>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-2">
                                                <?php echo htmlspecialchars($activity['date']); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-50 rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Geen recente activiteit</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Je hebt nog geen blogs of reacties geplaatst.
                                </p>
                                <div class="mt-6">
                                    <a href="<?php echo URLROOT; ?>/blogs/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Schrijf een blog
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
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