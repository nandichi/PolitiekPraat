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

    // Hero blog slider initialization 
    const heroSwiperContainer = document.querySelector('.hero-blog-swiper');
    if (heroSwiperContainer) {
        const heroSwiper = new Swiper(heroSwiperContainer, { 
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 800,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false, 
                pauseOnMouseEnter: true
            },
            loop: true,
            grabCursor: true,
            pagination: {
                el: '.blog-pagination', // Corrected selector
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active'
            },
            navigation: {
                nextEl: '.blog-nav-next', // Corrected selector
                prevEl: '.blog-nav-prev'  // Corrected selector
            },
            on: {
                // Removed progress bar logic as element doesn't exist
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
    }

    // Newsletter form submission
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

            // --- Simulation --- 
            // TODO: Replace with actual fetch call to backend API endpoint
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