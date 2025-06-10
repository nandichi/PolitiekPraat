<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Vul alle velden in';
    } else {
        $db = new Database();
        
        // Zoek gebruiker op email
        $db->query("SELECT * FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $user = $db->single();

        // Controleer of gebruiker bestaat en wachtwoord klopt
        if ($user && password_verify($password, $user->password)) {
            // Start sessie en sla gebruikersgegevens op
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['is_admin'] = $user->is_admin;
            $_SESSION['profile_photo'] = $user->profile_photo;
            
            // Redirect to dashboard
            header('Location: ' . URLROOT);
            exit;
        } else {
            $error = 'Ongeldige inloggegevens';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="container mx-auto px-4 py-16">
    <!-- Decoratieve golven aan de bovenkant -->
    <div aria-hidden="true" class="absolute left-0 right-0 top-[100px] overflow-hidden z-0">
        <svg class="absolute h-full w-full text-primary/5" preserveAspectRatio="none" viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg">
            <path d="M321.39 56.44c58-10.79 114.16-30.13 172-41.86 82.39-16.72 168.19-17.73 250.45-.39C823.78 31 906.67 72 985.66 92.83c70.05 18.48 146.53 26.09 214.34 3V0H0v27.35a600.21 600.21 0 00321.39 29.09z" fill="currentColor" />
        </svg>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-lg overflow-hidden relative z-10">
            <!-- Linker decoratieve sectie -->
            <div class="md:w-2/5 bg-gradient-to-br from-primary to-primary/80 p-8 text-white flex flex-col justify-between relative overflow-hidden">
                <!-- Decoratieve patronen -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 0 10 L 40 10 M 10 0 L 10 40" fill="none" stroke="white" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>
                
                <div class="relative">
                    <div class="flex items-center space-x-3 mb-6 group">
                        <div class="relative">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-white/20 transition-all duration-300">
                                <!-- Logo met verbeterde styling -->
                                <img src="<?php echo URLROOT; ?>/images/favicon-512x512.png" 
                                     alt="PolitiekPraat Logo" 
                                     class="w-8 h-8 object-contain transition-all duration-300 group-hover:scale-110">
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-secondary rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" 
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" 
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold group-hover:text-secondary transition-colors duration-300">
                                <?php echo SITENAME; ?>
                            </span>
                            <span class="text-sm text-white/70">
                                Samen bouwen aan democratie
                            </span>
                        </div>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-2">Welkom terug!</h2>
                    <p class="text-white/80 mb-6">
                        Log in om deel te nemen aan politieke discussies, je stem te laten horen en op de hoogte te blijven van het laatste nieuws.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block font-medium">Geïnformeerde discussies</span>
                                <span class="text-sm text-white/60">Neem deel aan het debat</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block font-medium">Laatste nieuws</span>
                                <span class="text-sm text-white/60">Blijf op de hoogte</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block font-medium">Laat je stem horen</span>
                                <span class="text-sm text-white/60">Deel je mening</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 text-sm text-white/70">
                    <p>© <?php echo date('Y'); ?> <?php echo SITENAME; ?>. Alle rechten voorbehouden.</p>
                </div>
            </div>
            
            <!-- Rechter formulier sectie -->
            <div class="md:w-3/5 p-8 md:p-12">
                <div class="flex justify-end mb-8">
                    <div class="flex space-x-4">
                        <a href="<?php echo URLROOT; ?>/" class="text-primary hover:text-secondary transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold mb-2 text-primary">Inloggen</h1>
                <p class="text-gray-600 mb-8">Vul je gegevens in om toegang te krijgen tot je account</p>
                
                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo URLROOT; ?>/login" class="space-y-6">
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">E-mailadres</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                   placeholder="jouw@email.nl"
                                   required>
                        </div>
                    </div>

                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Voer je wachtwoord in"
                                   required>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Onthoud mij op deze computer
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <div class="flex items-center justify-center">
                            <span>Inloggen</span>
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </button>
                </form>

            </div>
        </div>
        
        <!-- Decoratieve elementen onderaan -->
        <div class="flex justify-center mt-8">
            <div class="text-center max-w-xs">
                <div class="text-xs text-gray-500">
                    <p>Door in te loggen ga je akkoord met onze <a href="#" class="text-primary hover:underline">voorwaarden</a> en <a href="#" class="text-primary hover:underline">privacybeleid</a>.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 