document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            event.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    const typingElement = document.getElementById('typing-text');
    if (typingElement) {
        const texts = [
            'Waar democratie vorm krijgt...',
            'Waar standpunten botsen...',
            'Waar leiders inspireren...',
            'Waar jouw stem telt...'
        ];

        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        const typeText = () => {
            const currentText = texts[textIndex];
            const nextLength = isDeleting ? charIndex - 1 : charIndex + 1;
            typingElement.textContent = currentText.substring(0, nextLength);
            charIndex = nextLength;

            let typingSpeed = isDeleting ? 50 : 100;

            if (!isDeleting && charIndex === currentText.length) {
                typingSpeed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typingSpeed = 500;
            }

            window.setTimeout(typeText, typingSpeed);
        };

        typeText();
    }

    const leaderCards = document.querySelectorAll('.leader-card');
    const carouselDots = document.querySelectorAll('.carousel-dot');
    if (leaderCards.length && carouselDots.length) {
        let currentLeader = 0;

        const showLeader = (index) => {
            leaderCards.forEach((card, i) => {
                card.classList.toggle('hidden', i !== index);
                card.classList.toggle('active', i === index);
            });

            carouselDots.forEach((dot, i) => {
                dot.classList.toggle('bg-white/30', i !== index);
                dot.classList.toggle('bg-white', i === index);
            });
        };

        window.setInterval(() => {
            currentLeader = (currentLeader + 1) % leaderCards.length;
            showLeader(currentLeader);
        }, 4000);

        carouselDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentLeader = index;
                showLeader(currentLeader);
            });
        });
    }

    const seatBars = document.querySelectorAll('#seat-distribution .h-1');
    if (seatBars.length) {
        window.setTimeout(() => {
            seatBars.forEach((bar, index) => {
                bar.style.transform = 'scaleX(0)';
                bar.style.transformOrigin = 'left';
                window.setTimeout(() => {
                    bar.style.transform = 'scaleX(1)';
                }, index * 200);
            });
        }, 1000);

        window.setInterval(() => {
            seatBars.forEach((bar, index) => {
                window.setTimeout(() => {
                    bar.style.transform = 'scaleX(1.02)';
                    window.setTimeout(() => {
                        bar.style.transform = 'scaleX(1)';
                    }, 200);
                }, index * 100);
            });
        }, 8000);
    }
});
