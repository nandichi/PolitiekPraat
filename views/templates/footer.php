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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
                    <!-- Over PolitiekPraat Sectie -->
                    <div class="md:col-span-1">
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
                            <a href="https://www.instagram.com/politiekpraat/" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group" target="_blank" rel="noopener noreferrer">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="https://x.com/PolitiekPraatNL" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group" target="_blank" rel="noopener noreferrer">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/naoufalandichi/" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group" target="_blank" rel="noopener noreferrer">
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:scale-110 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.016 18.6h-2.472v-3.9c0-.923-.018-2.11-1.287-2.11-1.29 0-1.487 1.005-1.487 2.044v3.966H9.297V9.6h2.376v1.09h.033c.33-.627 1.14-1.29 2.347-1.29 2.51 0 2.97 1.653 2.97 3.803v5.397zM7.031 8.51a1.434 1.434 0 11.001-2.868 1.434 1.434 0 01-.001 2.868zm1.235 10.09H5.795V9.6h2.471v8.999z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigatie Links Sectie -->
                    <div>
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Navigatie
                        </h4>
                        <ul class="space-y-3">
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
                                <a href="<?php echo URLROOT; ?>/partijen" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Partijen
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/themas" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Thema's
                                </a>
                            </li>
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
                        </ul>
                    </div>

                    <!-- Verkiezingen Sectie -->
                    <div>
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Verkiezingen
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="<?php echo URLROOT; ?>/stemwijzer" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Stemwijzer
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/politiek-kompas" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Politiek Kompas
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/amerikaanse-verkiezingen" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Amerikaanse Verkiezingen
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/nederlandse-verkiezingen" 
                                   class="group flex items-center text-gray-600 hover:text-primary transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Nederlandse Verkiezingen
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info en Nieuwsbrief Sectie -->
                    <div>
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