    </div>
    <footer class="relative">
        <!-- Decoratieve top border met gradient -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-secondary to-accent"></div>
        
        <!-- Subtiele golven patroon als decoratie -->
        <div aria-hidden="true" class="absolute top-0 left-0 right-0 h-16 overflow-hidden">
            <svg class="absolute h-full w-full text-primary/5" preserveAspectRatio="none" viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg">
                <path d="M321.39 56.44c58-10.79 114.16-30.13 172-41.86 82.39-16.72 168.19-17.73 250.45-.39C823.78 31 906.67 72 985.66 92.83c70.05 18.48 146.53 26.09 214.34 3V0H0v27.35a600.21 600.21 0 00321.39 29.09z" fill="currentColor" />
            </svg>
        </div>

        <!-- Main Footer Content met verbeterde structuur -->
        <div class="bg-white text-gray-700">
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                    <!-- Over PolitiekPraat Sectie -->
                    <div class="md:col-span-4">
                        <div class="flex items-center space-x-3 mb-6 group">
                            <div class="relative">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-gray-200 transition-all duration-300">
                                    <!-- Logo met verbeterde styling -->
                                    <img src="<?php echo URLROOT; ?>/favicon.jpeg" 
                                         alt="PolitiekPraat Logo" 
                                         class="w-8 h-8 object-cover rounded-lg transition-all duration-300 group-hover:scale-110">
                                </div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-secondary rounded-full flex items-center justify-center shadow-lg animate-float">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" 
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" 
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold group-hover:text-primary transition-colors duration-300 text-gray-800">
                                    <?php echo SITENAME; ?>
                                </span>
                                <span class="text-sm text-gray-500">
                                    Samen bouwen aan democratie
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-8">
                            PolitiekPraat is het toonaangevende platform voor open en constructieve discussie over 
                            politieke onderwerpen in Nederland. Samen werken we aan een geïnformeerd politiek debat.
                        </p>

                        <!-- Social Media Links -->
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/naoufalandichi/" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group" target="_blank" rel="noopener noreferrer">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.016 18.6h-2.472v-3.9c0-.923-.018-2.11-1.287-2.11-1.29 0-1.487 1.005-1.487 2.044v3.966H9.297V9.6h2.376v1.09h.033c.33-.627 1.14-1.29 2.347-1.29 2.51 0 2.97 1.653 2.97 3.803v5.397zM7.031 8.51a1.434 1.434 0 11.001-2.868 1.434 1.434 0 01-.001 2.868zm1.235 10.09H5.795V9.6h2.471v8.999z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Verbeterde Navigatie Links Sectie -->
                    <div class="md:col-span-3">
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Navigatie
                        </h4>
                        <ul class="space-y-4">
                            <li>
                                <a href="<?php echo URLROOT; ?>/" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/blogs" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Blogs
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/nieuws" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Nieuws
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/stemwijzer" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Stemwijzer
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/partijen" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Partijen
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/politiek-kompas" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Vergelijker
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Extra Links Sectie -->
                    <div class="md:col-span-2">
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Meer
                        </h4>
                        <ul class="space-y-4">
                            <li>
                                <a href="<?php echo URLROOT; ?>/contact" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Contact
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/over-mij" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Over ons
                                </a>
                            </li>
                            <li>
                                <a href="#" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Privacybeleid
                                </a>
                            </li>
                            <li>
                                <a href="#" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Voorwaarden
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Verbeterde Contact Info en Nieuwsbrief Sectie -->
                    <div class="md:col-span-3">
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact
                        </h4>
                        <ul class="space-y-4">
                            <li class="flex items-center text-gray-600 hover:text-primary transition-colors duration-300 group">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-medium text-gray-800">E-mail</span>
                                    <span class="text-sm text-gray-500">info@politiekpraat.nl</span>
                                </div>
                            </li>
                            <li class="flex items-center text-gray-600 hover:text-primary transition-colors duration-300 group">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-medium text-gray-800">Telefoon</span>
                                    <span class="text-sm text-gray-500">+31 6 38107271</span>
                                </div>
                            </li>
                        </ul>

                        <!-- Nieuwsbrief Inschrijfformulier -->
                        <div class="mt-8">
                            <h5 class="text-md font-bold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                                Nieuwsbrief
                            </h5>
                            <p class="text-sm text-gray-500 mb-4">Blijf op de hoogte van het laatste nieuws en updates.</p>
                            
                            <form id="newsletterForm" class="space-y-3">
                                <div class="relative">
                                    <input type="email" name="email" required 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 placeholder-gray-400 
                                                focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300"
                                           placeholder="Jouw e-mailadres">
                                    <button type="submit" 
                                            class="absolute right-2 top-2 bg-primary text-white p-1.5 rounded-md 
                                                 hover:bg-primary/90 transition-all duration-300 transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button>
                                </div>
                                <div id="newsletterMessage" class="hidden">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright Sectie -->
        <div class="bg-gray-50 border-t border-gray-200">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                    <p>&copy; <?php echo date('Y'); ?> PolitiekPraat. Alle rechten voorbehouden.</p>
                    <div class="flex space-x-8 mt-4 md:mt-0">
                        <a href="<?php echo URLROOT; ?>/privacy-policy" class="hover:text-primary transition-colors duration-300">Privacy Policy</a>
                        <a href="<?php echo URLROOT; ?>/gebruiksvoorwaarden" class="hover:text-primary transition-colors duration-300">Gebruiksvoorwaarden</a>
                        <a href="<?php echo URLROOT; ?>/cookie-policy" class="hover:text-primary transition-colors duration-300">Cookie Policy</a>
                        <a href="<?php echo URLROOT; ?>/toegankelijkheid" class="hover:text-primary transition-colors duration-300">Toegankelijkheid</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <button id="scroll-to-top" 
            class="fixed bottom-8 right-8 w-12 h-12 bg-secondary text-white rounded-xl shadow-lg
                   transform transition-all duration-300 opacity-0 translate-y-10 hover:scale-110
                   flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
        // Scroll to top functionality
        const scrollToTopButton = document.getElementById('scroll-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopButton.classList.remove('opacity-0', 'translate-y-10');
            } else {
                scrollToTopButton.classList.add('opacity-0', 'translate-y-10');
            }
        });

        scrollToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Newsletter form handling
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[name="email"]').value;
            const messageDiv = document.getElementById('newsletterMessage');
            const messageContainer = messageDiv.querySelector('div');
            
            // Toon loading state
            messageDiv.classList.remove('hidden');
            messageContainer.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 text-white/80 transition-all duration-300';
            messageContainer.innerHTML = `
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Even geduld...</span>
            `;
            
            fetch('<?php echo URLROOT; ?>/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                messageDiv.classList.remove('hidden');
                if (data.status === 'success') {
                    messageContainer.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-secondary/20 text-white transition-all duration-300 transform animate-fade-in';
                    messageContainer.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${data.message}</span>
                    `;
                    this.reset();
                } else {
                    messageContainer.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/20 text-white transition-all duration-300';
                    messageContainer.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${data.message}</span>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.classList.remove('hidden');
                messageContainer.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/20 text-white transition-all duration-300';
                messageContainer.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Er is iets misgegaan. Probeer het later opnieuw.</span>
                `;
            });
        });
    </script>

    <?php if (isAdmin() && isset($_GET['debug_photo'])): ?>
    <div class="fixed bottom-0 right-0 bg-black/80 text-white p-4 rounded-tl-lg text-xs font-mono max-w-lg overflow-auto max-h-64">
        <h3 class="font-bold mb-2">Profile Photo Debug:</h3>
        <?php print_r($_SESSION['profile_photo_debug'] ?? 'No debug info available'); ?>
    </div>
    <?php endif; ?>

    <!-- Dynamic Scripts -->
    <?php if (basename($_SERVER['PHP_SELF']) == 'blogs.php' || strpos($_SERVER['REQUEST_URI'], '/blogs/') !== false): ?>
        <script src="<?php echo URLROOT; ?>/js/blog_likes.js"></script>
    <?php endif; ?>
    
    <!-- Newsletter JavaScript -->
    <script src="<?php echo URLROOT; ?>/js/newsletter.js"></script>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html> 