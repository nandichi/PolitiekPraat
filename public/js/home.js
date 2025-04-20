document.addEventListener('DOMContentLoaded', function() {
    // Typewriter effect
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
            "Begrijp hoe de verkiezingen werken en waarom jouw stem belangrijk.",
            "Verken de geschiedenis en toekomst van de Nederlandse politiek."
        ];
        
        let currentIndex = 0;
        typewriterElement.textContent = sentences[currentIndex]; // Set initial text immediately
        typewriterElement.style.opacity = 1; // Ensure it's visible

        function changeSentence() {
            currentIndex = (currentIndex + 1) % sentences.length;
            
            typewriterElement.style.opacity = 0; // Start fade out
            
            setTimeout(function() {
                typewriterElement.textContent = sentences[currentIndex]; // Change text when invisible
                typewriterElement.style.opacity = 1; // Start fade in
            }, 600); // Wait for fade out (must match CSS transition duration in home.php)
        }
        
        // Start the sentence changing loop after an initial delay
        setTimeout(() => setInterval(changeSentence, 4000), 3000); // Change every 4 seconds after initial 3s delay
    }

    // Hero blog slider initialization (from the first inline script block)
    const blogSwiperContainer = document.querySelector('.hero-blog-swiper');
    if (blogSwiperContainer) { 
        const blogSwiper = new Swiper(blogSwiperContainer, {
            slidesPerView: 1,
            spaceBetween: 0, // No space between slides in fade effect
            speed: 600, // Slightly faster transition
            loop: true,
            autoplay: {
                delay: 5000, 
                disableOnInteraction: true, // Stop on interaction
                pauseOnMouseEnter: true,    // Pause autoplay on hover
            },
            effect: 'fade', 
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.blog-pagination',
                clickable: true,
                bulletClass: 'swiper-pagination-bullet', // Use classes defined in CSS
                bulletActiveClass: 'swiper-pagination-bullet-active', // Use classes defined in CSS
            },
            navigation: {
                nextEl: '.blog-nav-next',
                prevEl: '.blog-nav-prev',
            }
        });
    }

    // Hero blog slider initialization (from the second inline script block at the end)
    // Note: This seems redundant as the first one already initializes .hero-blog-swiper.
    // We'll keep the more detailed initialization from the end of the original file.
    // If the selector '.hero-blog-swiper' targeted a different element previously, adjust accordingly.
    const heroSwiperDetailed = new Swiper('.hero-blog-swiper', { // Assuming it's the same element
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 800,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false, // Let's keep this consistent with the first init
            pauseOnMouseEnter: true
        },
        loop: true,
        grabCursor: true,
        pagination: {
            el: '.hero-swiper-pagination', // Check if this selector exists, might be '.blog-pagination' from first init
            clickable: true,
            // The original second script had different bullet classes, let's use the more styled ones from the first init
            bulletClass: 'swiper-pagination-bullet',
            bulletActiveClass: 'swiper-pagination-bullet-active'
            // Original second init classes:
            // bulletClass: 'inline-block w-2 h-2 mx-1 cursor-pointer transition-all duration-300 bg-white/30 hover:bg-white/70 rounded-full',
            // bulletActiveClass: 'w-6 bg-gradient-to-r from-primary to-secondary'
        },
        navigation: {
            nextEl: '.hero-swiper-button-next', // Check if this selector exists, might be '.blog-nav-next' from first init
            prevEl: '.hero-swiper-button-prev'  // Check if this selector exists, might be '.blog-nav-prev' from first init
        },
        on: {
            autoplayTimeLeft(s, time, progress) {
                const progressBar = document.querySelector('.swiper-progress-bar'); // Check if this element exists
                if (progressBar) {
                    // Assuming there's a nested .progress element inside .swiper-progress-bar
                    const innerProgress = progressBar.querySelector('.progress'); 
                    if(innerProgress) {
                       innerProgress.style.width = (1 - progress) * 100 + '%';
                    } else {
                       // Fallback if no nested .progress element, apply to the bar itself
                       progressBar.style.width = (1 - progress) * 100 + '%'; 
                    }
                }
            },
            init: function() {
                // Add extra class to the active slide
                this.slides.forEach((slide) => {
                    slide.classList.remove('swiper-slide-active-custom');
                });
                if (this.slides[this.activeIndex]) {
                   this.slides[this.activeIndex].classList.add('swiper-slide-active-custom');
                }
            },
            slideChange: function() {
                // Update the active slide class
                this.slides.forEach((slide) => {
                    slide.classList.remove('swiper-slide-active-custom');
                });
                 if (this.slides[this.activeIndex]) {
                   this.slides[this.activeIndex].classList.add('swiper-slide-active-custom');
                 }
            }
        }
    });

    // Newsletter form submission (if logic is needed)
    const newsletterForm = document.getElementById('newsletterForm');
    const newsletterMessage = document.getElementById('newsletterMessage');
    if (newsletterForm && newsletterMessage) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            const email = emailInput.value;

            // Basic email validation
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                newsletterMessage.textContent = 'Voer een geldig e-mailadres in.';
                newsletterMessage.className = 'mt-4 text-center text-red-600';
                newsletterMessage.classList.remove('hidden');
                return;
            }

            // Simulate form submission (replace with actual AJAX call)
            newsletterMessage.textContent = 'Verwerken...';
            newsletterMessage.className = 'mt-4 text-center text-gray-600';
            newsletterMessage.classList.remove('hidden');

            // Example: Use Fetch API to send data
            /*
            fetch('/api/subscribe', { // Replace with your actual API endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    newsletterMessage.textContent = 'Bedankt voor je inschrijving!';
                    newsletterMessage.className = 'mt-4 text-center text-green-600';
                    emailInput.value = ''; // Clear input
                } else {
                    newsletterMessage.textContent = data.message || 'Er is iets misgegaan. Probeer het opnieuw.';
                    newsletterMessage.className = 'mt-4 text-center text-red-600';
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                newsletterMessage.textContent = 'Er is een fout opgetreden. Controleer de console.';
                newsletterMessage.className = 'mt-4 text-center text-red-600';
            });
            */

            // --- Simulation ---
            setTimeout(() => {
                 // Simulate success
                 newsletterMessage.textContent = 'Bedankt voor je inschrijving!';
                 newsletterMessage.className = 'mt-4 text-center text-green-600';
                 emailInput.value = ''; 

                 // Simulate error
                 // newsletterMessage.textContent = 'Inschrijving mislukt. Probeer het opnieuw.';
                 // newsletterMessage.className = 'mt-4 text-center text-red-600';

            }, 1500);
            // --- End Simulation ---

        });
    }

}); 