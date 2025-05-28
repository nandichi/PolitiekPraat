document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }

    // Enhanced Typewriter effect
    const typewriterElement = document.getElementById('typewriter');
    if (typewriterElement) {
        const sentences = [
            "Ontdek hoe de Nederlandse politiek werkt en wat dit voor jou betekent.",
            "Volg de laatste ontwikkelingen in Den Haag op een toegankelijke manier.",
            "Blijf op de hoogte van belangrijke debatten en beslissingen in de Tweede Kamer.",
            "Leer meer over het Nederlandse democratische systeem en je rol daarin.",
            "Verdiep je in actuele politieke thema's die Nederland bezighouden.",
            "Ontdek hoe wetsvoorstellen tot stand komen en wat de gevolgen zijn.",
            "Krijg inzicht in de coalitievorming en politieke samenwerking in Nederland.",
            "Begrijp hoe de verkiezingen werken en waarom jouw stem belangrijk is.",
            "Verken de geschiedenis en toekomst van de Nederlandse politiek."
        ];
        
        let currentIndex = 0;
        let isTyping = false;
        
        // Set initial text
        typewriterElement.textContent = sentences[currentIndex];
        typewriterElement.style.opacity = 1;

        function typeWriter(text, element, callback) {
            if (isTyping) return;
            isTyping = true;
            
            let i = 0;
            element.textContent = '';
            element.style.opacity = 1;
            
            function type() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++;
                    setTimeout(type, 50); // Typing speed
                } else {
                    isTyping = false;
                    if (callback) callback();
                }
            }
            type();
        }

        function changeSentence() {
            if (isTyping) return;
            
            // Fade out
            typewriterElement.style.opacity = 0;
            
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % sentences.length;
                typeWriter(sentences[currentIndex], typewriterElement);
            }, 600);
        }
        
        // Start the sentence changing loop
        setTimeout(() => {
            setInterval(changeSentence, 5000); // Change every 5 seconds
        }, 4000); // Initial delay
    }

    // Enhanced Hero blog slider with improved settings
    const heroSwiperContainer = document.querySelector('.hero-blog-swiper');
    if (heroSwiperContainer) {
        const heroSwiper = new Swiper(heroSwiperContainer, { 
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 1000,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 6000,
                disableOnInteraction: false, 
                pauseOnMouseEnter: true
            },
            loop: true,
            grabCursor: true,
            pagination: {
                el: '.blog-pagination',
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active'
            },
            navigation: {
                nextEl: '.blog-nav-next',
                prevEl: '.blog-nav-prev'
            },
            // Enhanced transition effects
            on: {
                init: function() {
                    // Add custom slide animations
                    this.slides.forEach((slide, index) => {
                        if (index === this.activeIndex) {
                            slide.classList.add('swiper-slide-active-custom');
                        }
                    });
                },
                slideChangeTransitionStart: function() {
                    // Remove active class from all slides
                    this.slides.forEach(slide => {
                        slide.classList.remove('swiper-slide-active-custom');
                    });
                },
                slideChangeTransitionEnd: function() {
                    // Add active class to current slide
                    if (this.slides[this.activeIndex]) {
                        this.slides[this.activeIndex].classList.add('swiper-slide-active-custom');
                    }
                }
            }
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                heroSwiper.slidePrev();
            } else if (e.key === 'ArrowRight') {
                heroSwiper.slideNext();
            }
        });
    }

    // Smooth scroll for CTA buttons
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Parallax effect for hero background elements
    const heroShapes = document.querySelectorAll('.hero-shape');
    const heroPattern = document.querySelector('.hero-pattern');
    
    if (heroShapes.length > 0 || heroPattern) {
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            if (heroPattern) {
                heroPattern.style.transform = `translateY(${rate * 0.3}px)`;
            }
            
            heroShapes.forEach((shape, index) => {
                const shapeRate = rate * (0.2 + index * 0.1);
                shape.style.transform = `translateY(${shapeRate}px)`;
            });
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }

    // Enhanced Newsletter form submission
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = emailInput.value.trim();
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showNotification('Voer een geldig e-mailadres in.', 'error');
                return;
            }
            
            // Show loading state
            const originalBtnText = submitBtn.textContent;
            submitBtn.textContent = 'Bezig...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                showNotification('Bedankt voor je inschrijving! Je ontvangt binnenkort een bevestiging.', 'success');
                emailInput.value = '';
                submitBtn.textContent = originalBtnText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }

    // Notification system
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${getNotificationClass(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="mr-3">${getNotificationIcon(type)}</span>
                <span>${message}</span>
                <button class="ml-4 text-white/80 hover:text-white" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.transform = 'translateX(full)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    function getNotificationClass(type) {
        switch(type) {
            case 'success': return 'bg-green-500 text-white';
            case 'error': return 'bg-red-500 text-white';
            case 'warning': return 'bg-yellow-500 text-white';
            default: return 'bg-blue-500 text-white';
        }
    }
    
    function getNotificationIcon(type) {
        switch(type) {
            case 'success': return '✓';
            case 'error': return '✕';
            case 'warning': return '⚠';
            default: return 'ℹ';
        }
    }

    // Loading states for buttons
    document.querySelectorAll('.hero-cta-button').forEach(button => {
        button.addEventListener('click', function(e) {
            // Add subtle loading effect
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe elements for scroll animations
    document.querySelectorAll('[data-aos]').forEach(el => {
        observer.observe(el);
    });

    // Performance optimization: Debounced resize handler
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Recalculate positions if needed
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }, 250);
    });

    // Add custom cursor effect for hero section (optional enhancement)
    const heroSection = document.querySelector('.hero-section');
    if (heroSection && window.innerWidth > 1024) {
        let mouseX = 0;
        let mouseY = 0;
        
        heroSection.addEventListener('mousemove', function(e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
            
            // Subtle mouse-follow effect for shapes
            heroShapes.forEach((shape, index) => {
                const rect = heroSection.getBoundingClientRect();
                const x = (mouseX - rect.left) / rect.width;
                const y = (mouseY - rect.top) / rect.height;
                
                const moveX = (x - 0.5) * 20 * (index + 1);
                const moveY = (y - 0.5) * 20 * (index + 1);
                
                shape.style.transform += ` translate(${moveX}px, ${moveY}px)`;
            });
        });
    }
}); 