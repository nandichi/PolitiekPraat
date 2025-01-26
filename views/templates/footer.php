    </div>
    <footer class="relative mt-auto">
        <!-- Decorative Top Border -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-secondary to-accent"></div>
        
        <!-- Newsletter Section with Particles Background -->
        <div class="relative bg-gradient-to-br from-primary to-primary/95 text-white overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.1)_1px,transparent_1px)] bg-[length:20px_20px]"></div>
            </div>
            
            <div class="relative container mx-auto px-4 py-16">
                <div class="max-w-4xl mx-auto text-center">
                    <h3 class="text-3xl md:text-4xl font-bold mb-6 animate-fade-in">
                        Blijf op de hoogte van het laatste nieuws
                    </h3>
                    <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
                        Ontvang wekelijks de beste politieke inzichten, analyses en community updates direct in je inbox.
                    </p>
                    <form id="newsletterForm" class="flex flex-col sm:flex-row gap-4 justify-center max-w-lg mx-auto">
                        <div class="relative flex-grow">
                            <input type="email" 
                                   name="email"
                                   placeholder="Je e-mailadres" 
                                   class="w-full bg-white/10 text-white placeholder-white/60 px-6 py-3 rounded-xl
                                          focus:outline-none focus:ring-2 focus:ring-secondary border border-white/20
                                          transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary/20 to-accent/20 rounded-xl
                                      opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                        <button type="submit" 
                                class="bg-secondary text-white px-8 py-3 rounded-xl font-semibold
                                       hover:bg-accent transition-all duration-300 transform hover:scale-105
                                       focus:outline-none focus:ring-2 focus:ring-white/50 shadow-lg">
                            Aanmelden
                        </button>
                    </form>
                    <div id="newsletterMessage" class="mt-6 text-center hidden">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="bg-primary/95 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                    <!-- About Section -->
                    <div class="md:col-span-5">
                        <div class="flex items-center space-x-3 mb-6 group">
                            <div class="relative">
                                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-white/20 transition-all duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                              d="M19 21v-6a2 2 0 00-2-2h-2v3h-3v-3h-2a2 2 0 00-2 2v6M3 21V5a2 2 0 012-2h14a2 2 0 012 2v16M9 7h.01M9 11h.01M15 7h.01M15 11h.01M12 7v5"/>
                                    </svg>
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
                                <span class="text-2xl font-bold group-hover:text-secondary transition-colors duration-300">
                                    <?php echo SITENAME; ?>
                                </span>
                                <span class="text-sm text-white/70">
                                    Samen bouwen aan democratie
                                </span>
                            </div>
                        </div>
                        <p class="text-white/80 leading-relaxed mb-8">
                            PolitiekPraat is het toonaangevende platform voor open en constructieve discussie over 
                            politieke onderwerpen in Nederland. Wij faciliteren een respectvolle dialoog en 
                            stimuleren kritisch denken over actuele politieke thema's.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.016 18.6h-2.472v-3.9c0-.923-.018-2.11-1.287-2.11-1.29 0-1.487 1.005-1.487 2.044v3.966H9.297V9.6h2.376v1.09h.033c.33-.627 1.14-1.29 2.347-1.29 2.51 0 2.97 1.653 2.97 3.803v5.397zM7.031 8.51a1.434 1.434 0 11.001-2.868 1.434 1.434 0 01-.001 2.868zm1.235 10.09H5.795V9.6h2.471v8.999z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
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
                                <a href="<?php echo URLROOT; ?>/blogs" 
                                   class="group flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Blogs
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/forum" 
                                   class="group flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Forum
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URLROOT; ?>/contact" 
                                   class="group flex items-center text-white/80 hover:text-white transition-colors duration-300">
                                    <span class="w-2 h-2 bg-secondary rounded-full mr-3 transform scale-0 group-hover:scale-100 transition-transform duration-300"></span>
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="md:col-span-4">
                        <h4 class="text-lg font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact
                        </h4>
                        <ul class="space-y-4">
                            <li class="flex items-center text-white/80 hover:text-white transition-colors duration-300 group">
                                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-medium">E-mail</span>
                                    <span class="text-sm text-white/60">info@politiekpraat.nl</span>
                                </div>
                            </li>
                            <li class="flex items-center text-white/80 hover:text-white transition-colors duration-300 group">
                                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-medium">Telefoon</span>
                                    <span class="text-sm text-white/60">+31 6 38107271</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-primary/90 border-t border-white/10">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center text-white/60 text-sm">
                <p>&copy; <?php echo date('Y'); ?> PolitiekPraat. Alle rechten voorbehouden.</p>
                    <div class="flex space-x-8 mt-4 md:mt-0">
                        <a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors duration-300">Gebruiksvoorwaarden</a>
                        <a href="#" class="hover:text-white transition-colors duration-300">Cookie Policy</a>
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
</body>
</html> 