<!-- Fixed Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuContent = document.getElementById('mobile-menu-content');
            const breakingNewsBanner = document.getElementById('breaking-news-banner');
            
            // Ensure elements exist
            if (!mobileMenuButton || !mobileMenuOverlay || !mobileMenuContent) {
                console.error('Mobile menu elements not found');
                return;
            }

            // Global function to close mobile menu
            window.closeMobileMenu = function() {
                document.body.classList.remove('overflow-hidden');
                mobileMenuOverlay.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
                mobileMenuOverlay.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                mobileMenuContent.classList.remove('translate-x-0');
                mobileMenuContent.classList.add('translate-x-full');
                
                // Show breaking news banner again
                if (breakingNewsBanner && breakingNewsBanner.style.display !== 'none') {
                    breakingNewsBanner.style.transform = 'translateY(0)';
                    breakingNewsBanner.style.visibility = 'visible';
                }
            };

            // Open mobile menu function
            function openMobileMenu() {
                document.body.classList.add('overflow-hidden');
                mobileMenuOverlay.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                mobileMenuOverlay.classList.add('opacity-100', 'visible', 'pointer-events-auto');
                mobileMenuContent.classList.remove('translate-x-full');
                mobileMenuContent.classList.add('translate-x-0');
                
                // Hide breaking news banner on mobile only
                if (breakingNewsBanner && window.innerWidth < 768) {
                    breakingNewsBanner.style.transform = 'translateY(-100%)';
                    breakingNewsBanner.style.visibility = 'hidden';
                }
            }

            // Toggle mobile menu
            mobileMenuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (mobileMenuOverlay.classList.contains('invisible')) {
                    openMobileMenu();
                } else {
                    closeMobileMenu();
                }
            });

            // Close menu when clicking backdrop
            mobileMenuOverlay.addEventListener('click', function(e) {
                if (e.target === mobileMenuOverlay) {
                    closeMobileMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenuOverlay.classList.contains('invisible')) {
                    closeMobileMenu();
                }
            });

            // Handle window resize to show/hide breaking news banner appropriately
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && breakingNewsBanner) {
                    // On desktop, always show the banner
                    breakingNewsBanner.style.transform = 'translateY(0)';
                    breakingNewsBanner.style.visibility = 'visible';
                } else if (window.innerWidth < 768 && !mobileMenuOverlay.classList.contains('invisible')) {
                    // On mobile with menu open, hide the banner
                    breakingNewsBanner.style.transform = 'translateY(-100%)';
                    breakingNewsBanner.style.visibility = 'hidden';
                }
            });



            // Handle desktop dropdowns with improved timing
            const dropdownGroups = document.querySelectorAll('.group');
            dropdownGroups.forEach(group => {
                const button = group.querySelector('button');
                const dropdown = group.querySelector('.dropdown-content');
                
                if (button && dropdown) {
                    let timeoutId;
                    
                    group.addEventListener('mouseenter', () => {
                        clearTimeout(timeoutId);
                        dropdown.classList.add('dropdown-active');
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateY(0) scale(1) rotateX(0deg)';
                        dropdown.style.pointerEvents = 'auto';
                    });
                    
                    group.addEventListener('mouseleave', () => {
                        timeoutId = setTimeout(() => {
                            dropdown.classList.remove('dropdown-active');
                            dropdown.style.opacity = '0';
                            dropdown.style.visibility = 'hidden';
                            dropdown.style.transform = 'translateY(-15px) scale(0.95) rotateX(-5deg)';
                            dropdown.style.pointerEvents = 'none';
                        }, 150);
                    });
                    
                    // Add keyboard support for accessibility
                    button.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            if (dropdown.classList.contains('dropdown-active')) {
                                dropdown.classList.remove('dropdown-active');
                                dropdown.style.opacity = '0';
                                dropdown.style.visibility = 'hidden';
                                dropdown.style.transform = 'translateY(-10px) scale(0.98)';
                                dropdown.style.pointerEvents = 'none';
                            } else {
                                dropdown.classList.add('dropdown-active');
                                dropdown.style.opacity = '1';
                                dropdown.style.visibility = 'visible';
                                dropdown.style.transform = 'translateY(0) scale(1)';
                                dropdown.style.pointerEvents = 'auto';
                            }
                        }
                    });
                }
            });
        });
    </script>

    <!-- Beta Modal JavaScript -->
    <script>
        function openBetaModal() {
            const modal = document.getElementById('betaModal');
            const panel = modal.querySelector('.relative');
            const overlay = modal.querySelector('.fixed');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset and animate
            panel.style.transform = 'scale(0.9)';
            panel.style.opacity = '0';
            overlay.style.opacity = '0';
            
            setTimeout(() => {
                panel.style.transform = 'scale(1)';
                panel.style.opacity = '1';
                overlay.style.opacity = '1';
            }, 10);
        }
        
        function closeBetaModal() {
            const modal = document.getElementById('betaModal');
            const panel = modal.querySelector('.relative');
            const overlay = modal.querySelector('.fixed');
            
            panel.style.transform = 'scale(0.9)';
            panel.style.opacity = '0';
            overlay.style.opacity = '0';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBetaModal();
            }
        });
        
        // Initialize modal animations
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('betaModal');
            if (modal) {
                const panel = modal.querySelector('.relative');
                const overlay = modal.querySelector('.fixed');
                
                if (panel) {
                    panel.style.transition = 'all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1)';
                }
                if (overlay) {
                    overlay.style.transition = 'opacity 0.3s ease-out';
                }
            }
        });
    </script>
