<script>
// Global variables
let isLikeProcessing = false;
let likedBlogs = {};
let currentBlogSlug = '<?php echo $blog->slug; ?>';

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Blog view script loaded');
    
    // Initialize all components
    initializeReadingProgress();
    initializeReadingTime();
    initializeLikeSystem();
    initializeBiasAnalysis();
    initializePartyPerspective();
    initializeAudioFeatures();
    
    // Load liked blogs from localStorage
    likedBlogs = JSON.parse(localStorage.getItem('likedBlogs') || '{}');
    updateLikeButtonStates();
});

// Reading Progress Bar - optimized with throttling
function initializeReadingProgress() {
    let ticking = false;
    let lastUpdateTime = 0;
    const updateThrottle = 16; // ~60fps
    
    function updateReadingProgress() {
        const now = Date.now();
        if (now - lastUpdateTime < updateThrottle) {
            ticking = false;
            return;
        }
        lastUpdateTime = now;
        
        const article = document.getElementById('blog-content');
        if (!article) {
            ticking = false;
            return;
        }
        
        const articleHeight = article.offsetHeight;
        const articleTop = article.offsetTop;
        const scrollPosition = window.scrollY;
        const windowHeight = window.innerHeight;
        
        const progress = Math.max(0, Math.min(100, 
            ((scrollPosition + windowHeight - articleTop) / articleHeight) * 100
        ));
        
        const progressBar = document.getElementById('reading-progress');
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        ticking = false;
    }
    
    function requestUpdate() {
        if (!ticking) {
            requestAnimationFrame(updateReadingProgress);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate, { passive: true });
    updateReadingProgress();
}

// Reading Time Calculation
function initializeReadingTime() {
    const content = document.getElementById('blog-content');
    if (!content) return;
    
    const text = content.textContent || content.innerText;
    const words = text.trim().split(/\s+/).length;
    const wordsPerMinute = 200;
    const minutes = Math.ceil(words / wordsPerMinute);
    
    const readingTimeElements = document.querySelectorAll('#reading-minutes, #reading-minutes-content');
    readingTimeElements.forEach(element => {
        if (element) {
            element.textContent = minutes;
        }
    });
}

// Like System
function initializeLikeSystem() {
    const likeButton = document.getElementById('likeButton');
    const heroLikeButton = document.getElementById('heroLikeButton');
    
    if (likeButton) {
        likeButton.addEventListener('click', handleLikeClick);
        console.log('Like button event listener added');
    }
    
    if (heroLikeButton) {
        heroLikeButton.addEventListener('click', handleLikeClick);
        console.log('Hero like button event listener added');
    }
}

async function handleLikeClick(event) {
    if (isLikeProcessing) {
        console.log('Like already processing, ignoring click');
        return;
    }
    
    console.log('Like button clicked');
    
    const button = event.currentTarget;
    const slug = button.getAttribute('data-slug') || currentBlogSlug;
    
    console.log('Current blog slug:', currentBlogSlug);
    console.log('Button slug:', slug);
    console.log('LikedBlogs state:', likedBlogs);
    
    if (!slug) {
        console.error('No slug found for like action');
        showNotification('Er ging iets mis. Probeer het opnieuw.', 'error');
        return;
    }
    
    const action = likedBlogs[slug] ? 'unlike' : 'like';
    isLikeProcessing = true;
    
    // Visual feedback
    button.style.transform = 'scale(0.95)';
    
    // Disable buttons
    const allLikeButtons = document.querySelectorAll('#likeButton, #heroLikeButton');
    allLikeButtons.forEach(btn => btn.disabled = true);
    
    try {
        console.log(`Performing ${action} action for slug: ${slug}`);
        
        // Create endpoint URL
        const likeEndpoint = `<?php echo URLROOT; ?>/views/blogs/update_likes.php`;
        console.log('Like endpoint URL:', likeEndpoint);
        
        const formData = new FormData();
        formData.append('slug', slug);
        formData.append('action', action);
        
        console.log('Sending FormData:', {
            slug: formData.get('slug'),
            action: formData.get('action')
        });
        
        const response = await fetch(likeEndpoint, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error text:', errorText);
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }
        
        const responseText = await response.text();
        console.log('Raw response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            console.error('Response was not valid JSON:', responseText);
            throw new Error('Server response was not valid JSON');
        }
        
        console.log('Parsed response data:', data);
        
        if (data.success) {
            // Update like counts
            updateLikeCounts(data.likes);
            
            // Update local storage
            if (action === 'like') {
                likedBlogs[slug] = true;
                showNotification('Artikel geliked! ❤️', 'success');
                createConfetti(button);
            } else {
                delete likedBlogs[slug];
                showNotification('Like verwijderd', 'info');
            }
            
            localStorage.setItem('likedBlogs', JSON.stringify(likedBlogs));
            updateLikeButtonStates();
            
        } else {
            throw new Error(data.error || 'Onbekende fout');
        }
        
    } catch (error) {
        console.error('Like error details:', error);
        showNotification('Er ging iets mis bij het liken. Probeer het opnieuw.', 'error');
    } finally {
        isLikeProcessing = false;
        button.style.transform = 'scale(1)';
        allLikeButtons.forEach(btn => btn.disabled = false);
    }
}

function updateLikeCounts(newCount) {
    // Update all like count displays
    const likeCountDisplay = document.getElementById('likeCountDisplay');
    const heroLikeCount = document.getElementById('hero-like-count');
    
    if (likeCountDisplay) {
        likeCountDisplay.style.transform = 'scale(1.2)';
        setTimeout(() => {
            likeCountDisplay.textContent = newCount;
            likeCountDisplay.style.transform = 'scale(1)';
        }, 150);
    }
    
    if (heroLikeCount) {
        heroLikeCount.style.transform = 'scale(1.1)';
        setTimeout(() => {
            heroLikeCount.textContent = newCount + ' likes';
            heroLikeCount.style.transform = 'scale(1)';
        }, 150);
    }
}

function updateLikeButtonStates() {
    const isLiked = likedBlogs[currentBlogSlug] || false;
    const likeButton = document.getElementById('likeButton');
    const heroLikeButton = document.getElementById('heroLikeButton');
    
    if (likeButton) {
        if (isLiked) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }
    }
    
    if (heroLikeButton) {
        const svg = heroLikeButton.querySelector('svg');
        if (isLiked) {
            heroLikeButton.classList.add('liked');
            if (svg) svg.setAttribute('fill', 'currentColor');
        } else {
            heroLikeButton.classList.remove('liked');
            if (svg) svg.setAttribute('fill', 'none');
        }
    }
}

function createConfetti(button) {
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7'];
    const buttonRect = button.getBoundingClientRect();
    
    for (let i = 0; i < 20; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.left = (buttonRect.left + buttonRect.width / 2) + 'px';
            confetti.style.top = (buttonRect.top + buttonRect.height / 2) + 'px';
            confetti.style.width = '6px';
            confetti.style.height = '6px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.borderRadius = '50%';
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            
            document.body.appendChild(confetti);
            
            const angle = (Math.PI * 2 * i) / 20;
            const velocity = 2 + Math.random() * 2;
            const gravity = 0.1;
            let vx = Math.cos(angle) * velocity;
            let vy = Math.sin(angle) * velocity;
            let x = 0;
            let y = 0;
            
            const animate = () => {
                x += vx;
                y += vy;
                vy += gravity;
                
                confetti.style.transform = `translate(${x}px, ${y}px) rotate(${x}deg)`;
                confetti.style.opacity = Math.max(0, 1 - Math.abs(y) / 200);
                
                if (confetti.style.opacity > 0) {
                    requestAnimationFrame(animate);
                } else {
                    confetti.remove();
                }
            };
            
            requestAnimationFrame(animate);
        }, i * 30);
    }
}

// Bias Analysis System
function initializeBiasAnalysis() {
    const biasButton = document.getElementById('biasButton');
    const biasModal = document.getElementById('biasModal');
    const closeBiasModal = document.getElementById('closeBiasModal');
    const closeBiasModalFooter = document.getElementById('closeBiasModalFooter');
    const retryBiasAnalysis = document.getElementById('retryBiasAnalysis');
    
    if (biasButton) {
        biasButton.addEventListener('click', function() {
            console.log('Bias button clicked');
            const slug = this.getAttribute('data-slug') || currentBlogSlug;
            if (slug) {
                showBiasModal();
                performBiasAnalysis(slug);
            }
        });
    }
    
    if (closeBiasModal) {
        closeBiasModal.addEventListener('click', hideBiasModal);
    }
    
    if (closeBiasModalFooter) {
        closeBiasModalFooter.addEventListener('click', hideBiasModal);
    }
    
    if (retryBiasAnalysis) {
        retryBiasAnalysis.addEventListener('click', function() {
            const slug = biasButton?.getAttribute('data-slug') || currentBlogSlug;
            if (slug) {
                performBiasAnalysis(slug);
            }
        });
    }
    
    // Close modal on background click
    if (biasModal) {
        biasModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideBiasModal();
            }
        });
    }
}

function showBiasModal() {
    const modal = document.getElementById('biasModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset modal state
        document.getElementById('biasLoading')?.classList.remove('hidden');
        document.getElementById('biasError')?.classList.add('hidden');
        document.getElementById('biasResults')?.classList.add('hidden');
        document.getElementById('retryBiasAnalysis')?.classList.add('hidden');
    }
}

function hideBiasModal() {
    const modal = document.getElementById('biasModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

async function performBiasAnalysis(slug) {
    console.log('Starting bias analysis for slug:', slug);
    
    try {
        // Show loading state
        document.getElementById('biasLoading')?.classList.remove('hidden');
        document.getElementById('biasError')?.classList.add('hidden');
        document.getElementById('biasResults')?.classList.add('hidden');
        document.getElementById('retryBiasAnalysis')?.classList.add('hidden');
        
        const formData = new FormData();
        formData.append('slug', slug);
        
        const response = await fetch('<?php echo URLROOT; ?>/controllers/blogs/analyze-bias.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Bias analysis response:', data);
        
        // Hide loading
        document.getElementById('biasLoading')?.classList.add('hidden');
        
        if (data.success && data.analysis) {
            showBiasResults(data.analysis);
        } else {
            showBiasError(data.error || 'Onbekende fout bij de analyse');
        }
        
    } catch (error) {
        console.error('Bias analysis error:', error);
        document.getElementById('biasLoading')?.classList.add('hidden');
        showBiasError('Netwerk fout: Kon geen verbinding maken met de server. Controleer je internetverbinding en probeer het opnieuw.');
    }
}

function showBiasResults(analysis) {
    console.log('Showing bias results:', analysis);
    
    // Show results container
    document.getElementById('biasResults')?.classList.remove('hidden');
    
    // === OVERALL ORIENTATION ===
    const orientationBadge = document.getElementById('orientationBadge');
    if (orientationBadge && analysis.overall?.orientatie) {
        orientationBadge.textContent = analysis.overall.orientatie;
    }
    
    // Set confidence with animated circle
    const confidenceText = document.getElementById('confidenceText');
    const confidenceCircle = document.getElementById('confidenceCircle');
    if (confidenceText && analysis.overall?.confidence) {
        const confidence = analysis.overall.confidence;
        confidenceText.textContent = confidence;
        
        // Animate the SVG circle (r=24, circumference = 2 * PI * 24 = 150.8)
        if (confidenceCircle) {
            const circumference = 2 * Math.PI * 24;
            const offset = circumference - (confidence / 100) * circumference;
            setTimeout(() => {
                confidenceCircle.style.strokeDashoffset = offset;
            }, 100);
        }
    }
    
    // Set overall summary
    const overallSummary = document.getElementById('overallSummary');
    if (overallSummary && analysis.overall?.samenvatting) {
        overallSummary.textContent = analysis.overall.samenvatting;
    }
    
    // === SPECTRUM BREAKDOWN ===
    if (analysis.spectrum) {
        // Economisch
        setSpectrumMarker('economisch', analysis.spectrum.economisch);
        // Sociaal-cultureel
        setSpectrumMarker('sociaal_cultureel', analysis.spectrum.sociaal_cultureel);
        // EU/Internationaal
        setSpectrumMarker('eu_internationaal', analysis.spectrum.eu_internationaal);
        // Klimaat
        setSpectrumMarker('klimaat', analysis.spectrum.klimaat);
        // Immigratie
        setSpectrumMarker('immigratie', analysis.spectrum.immigratie);
    }
    
    // === RETORISCHE ANALYSE ===
    if (analysis.retoriek) {
        setTextContent('retoriekToon', capitalizeFirst(analysis.retoriek.toon || '-'));
        setTextContent('retoriekFraming', capitalizeFirst(analysis.retoriek.framing || '-'));
        setTextContent('retoriekStijl', capitalizeFirst(analysis.retoriek.stijl || '-'));
        
        const objectiviteit = analysis.retoriek.objectiviteit || 0;
        setTextContent('retoriekObjectiviteitValue', objectiviteit + '%');
        const objBar = document.getElementById('retoriekObjectiviteitBar');
        if (objBar) {
            setTimeout(() => { objBar.style.width = objectiviteit + '%'; }, 100);
        }
        
        setTextContent('retoriekToelichting', analysis.retoriek.toelichting || '');
    }
    
    // === SCHRIJFSTIJL ===
    if (analysis.schrijfstijl) {
        const feitelijk = analysis.schrijfstijl.feitelijk_vs_mening || 50;
        setTextContent('schrijfstijlFeitelijkValue', feitelijk + '% mening');
        const feitelijkBar = document.getElementById('schrijfstijlFeitelijkBar');
        if (feitelijkBar) {
            setTimeout(() => { feitelijkBar.style.width = feitelijk + '%'; }, 100);
        }
        
        const emotie = analysis.schrijfstijl.emotionele_lading || 0;
        setTextContent('schrijfstijlEmotieValue', emotie + '%');
        const emotieBar = document.getElementById('schrijfstijlEmotieBar');
        if (emotieBar) {
            setTimeout(() => { emotieBar.style.width = emotie + '%'; }, 100);
        }
        
        setTextContent('schrijfstijlBronnen', capitalizeFirst(analysis.schrijfstijl.bronverwijzingen || '-'));
        setTextContent('schrijfstijlArgumentatie', capitalizeFirst(analysis.schrijfstijl.argumentatie_balans || '-'));
    }
    
    // === PARTIJ MATCHING ===
    if (analysis.partij_match) {
        setTextContent('partijBestMatch', analysis.partij_match.best_match || '-');
        
        // Partijen die zouden onderschrijven
        const onderschrijvenContainer = document.getElementById('partijOnderschrijven');
        if (onderschrijvenContainer && analysis.partij_match.zou_onderschrijven) {
            onderschrijvenContainer.innerHTML = '';
            analysis.partij_match.zou_onderschrijven.forEach(partij => {
                const badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-medium';
                badge.textContent = partij;
                onderschrijvenContainer.appendChild(badge);
            });
        }
        
        // Partijen die zouden afwijzen
        const afwijzenContainer = document.getElementById('partijAfwijzen');
        if (afwijzenContainer && analysis.partij_match.zou_afwijzen) {
            afwijzenContainer.innerHTML = '';
            analysis.partij_match.zou_afwijzen.forEach(partij => {
                const badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-medium';
                badge.textContent = partij;
                afwijzenContainer.appendChild(badge);
            });
        }
        
        setTextContent('partijToelichting', analysis.partij_match.toelichting || '');
    }
    
    // === DOELGROEP ===
    if (analysis.doelgroep) {
        setTextContent('doelgroepPrimair', analysis.doelgroep.primair || '-');
        setTextContent('doelgroepDemografisch', analysis.doelgroep.demografisch || '');
        setTextContent('doelgroepPolitiek', analysis.doelgroep.politiek_profiel || '');
    }
    
    // === KERNPUNTEN ===
    const kernpuntenList = document.getElementById('kernpuntenList');
    if (kernpuntenList && analysis.kernpunten) {
        kernpuntenList.innerHTML = '';
        analysis.kernpunten.forEach(punt => {
            const li = document.createElement('li');
            li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
            li.innerHTML = `
                <svg class="w-3 h-3 text-secondary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>${punt}</span>
            `;
            kernpuntenList.appendChild(li);
        });
    }
}

function setSpectrumMarker(spectrumKey, data) {
    if (!data) return;
    
    // Map spectrum keys to element IDs
    const idMappings = {
        'economisch': { marker: 'economischMarker', label: 'economischLabel', toelichting: 'economischToelichting' },
        'sociaal_cultureel': { marker: 'sociaal_cultureel_marker', label: 'sociaal_cultureel_label', toelichting: 'sociaal_cultureel_toelichting' },
        'eu_internationaal': { marker: 'eu_internationaal_marker', label: 'eu_internationaal_label', toelichting: 'eu_internationaal_toelichting' },
        'klimaat': { marker: 'klimaatMarker', label: 'klimaatLabel', toelichting: 'klimaatToelichting' },
        'immigratie': { marker: 'immigratieMarker', label: 'immigratieLabel', toelichting: 'immigratieToelichting' }
    };
    
    const ids = idMappings[spectrumKey];
    if (!ids) return;
    
    const marker = document.getElementById(ids.marker);
    const label = document.getElementById(ids.label);
    const toelichting = document.getElementById(ids.toelichting);
    
    if (marker && typeof data.score === 'number') {
        // Convert score (-100 to +100) to percentage (0 to 100)
        const percentage = ((data.score + 100) / 200) * 100;
        setTimeout(() => {
            marker.style.left = `calc(${percentage}% - 10px)`;
        }, 100);
    }
    
    if (label && data.label) {
        label.textContent = data.label;
    }
    
    if (toelichting && data.toelichting) {
        toelichting.textContent = data.toelichting;
    }
}

function setTextContent(elementId, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
    }
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function showBiasError(errorMessage) {
    document.getElementById('biasError')?.classList.remove('hidden');
    document.getElementById('retryBiasAnalysis')?.classList.remove('hidden');
    
    const errorMessageElement = document.getElementById('biasErrorMessage');
    if (errorMessageElement) {
        errorMessageElement.textContent = errorMessage;
    }
}

function getOrientationLabel(orientation) {
    const labels = {
        'links': 'Links',
        'rechts': 'Rechts', 
        'centrum': 'Centrum',
        'neutraal': 'Neutraal'
    };
    return labels[orientation] || orientation;
}

function getOrientationColors(orientation) {
    const colors = {
        'links': 'bg-red-100 text-red-800 border border-red-200',
        'rechts': 'bg-blue-100 text-blue-800 border border-blue-200',
        'centrum': 'bg-purple-100 text-purple-800 border border-purple-200',
        'neutraal': 'bg-gray-100 text-gray-800 border border-gray-200'
    };
    return colors[orientation] || 'bg-gray-100 text-gray-800 border border-gray-200';
}

// Party Perspective System  
function initializePartyPerspective() {
    const partyPerspectiveButton = document.getElementById('partyPerspectiveButton');
    const partyModal = document.getElementById('partyModal');
    const closePartyModal = document.getElementById('closePartyModal');
    const closePartyModalFooter = document.getElementById('closePartyModalFooter');
    const backToPartySelection = document.getElementById('backToPartySelection');
    
    if (partyPerspectiveButton) {
        partyPerspectiveButton.addEventListener('click', function() {
            console.log('Party perspective button clicked');
            showPartyModal();
        });
    }
    
    if (closePartyModal) {
        closePartyModal.addEventListener('click', hidePartyModal);
    }
    
    if (closePartyModalFooter) {
        closePartyModalFooter.addEventListener('click', hidePartyModal);
    }
    
    if (backToPartySelection) {
        backToPartySelection.addEventListener('click', showPartySelection);
    }
    
    // Background click to close
    if (partyModal) {
        partyModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hidePartyModal();
            }
        });
    }
    
    // Party selection buttons
    document.querySelectorAll('.party-select-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const party = this.getAttribute('data-party');
            if (party) {
                console.log('Party selected:', party);
                performPartyAnalysis(party);
            }
        });
    });
}

function showPartyModal() {
    const modal = document.getElementById('partyModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        showPartySelection();
    }
}

function hidePartyModal() {
    const modal = document.getElementById('partyModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function showPartySelection() {
    document.getElementById('partySelectionGrid')?.classList.remove('hidden');
    document.getElementById('partyLoading')?.classList.add('hidden');
    document.getElementById('partyError')?.classList.add('hidden');
    document.getElementById('partyResults')?.classList.add('hidden');
    document.getElementById('backToPartySelection')?.classList.add('hidden');
}

async function performPartyAnalysis(party) {
    console.log('Starting party analysis for:', party);
    
    try {
        // Hide selection, show loading
        document.getElementById('partySelectionGrid')?.classList.add('hidden');
        document.getElementById('partyLoading')?.classList.remove('hidden');
        document.getElementById('partyError')?.classList.add('hidden');
        document.getElementById('partyResults')?.classList.add('hidden');
        
        const slug = currentBlogSlug;
        if (!slug) return;
        
        const formData = new FormData();
        formData.append('slug', slug);
        formData.append('party', party);
        formData.append('type', 'leader');
        
        const response = await fetch('<?php echo URLROOT; ?>/controllers/blogs/party-perspective.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Party analysis response:', data);
        
        // Hide loading
        document.getElementById('partyLoading')?.classList.add('hidden');
        
        if (data.success) {
            showPartyResults(data, party);
        } else {
            showPartyError(data.error || 'Onbekende fout bij het genereren');
        }
        
    } catch (error) {
        console.error('Party analysis error:', error);
        document.getElementById('partyLoading')?.classList.add('hidden');
        showPartyError('Netwerk fout: Kon geen verbinding maken met de server. Controleer je internetverbinding en probeer het opnieuw.');
    }
}

function showPartyResults(data, partyKey) {
    console.log('Showing party results for:', partyKey, data);
    
    // Dynamisch geladen partij data uit database
    const partyData = <?php 
        $jsPartyData = [];
        foreach ($dbParties as $key => $party) {
            $jsPartyData[$key] = [
                'name' => $party['name'],
                'leader' => $party['leader'],
                'logo' => $party['logo'],
                'leaderPhoto' => $party['leader_photo'] ?? ''
            ];
        }
        echo json_encode($jsPartyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?>;
    
    const party = partyData[partyKey];
    if (!party) return;
    
    // Check if we have structured data or legacy plain text
    const analysis = data.analysis || data;
    const isStructured = analysis.reactie && analysis.analyse;
    
    // Show results container
    document.getElementById('partyResults')?.classList.remove('hidden');
    document.getElementById('backToPartySelection')?.classList.remove('hidden');
    
    // === LEIDER HERO CARD ===
    const photo = document.getElementById('partyResultPhoto');
    if (photo) {
        photo.src = party.leaderPhoto || party.logo;
        photo.alt = party.leader;
    }
    
    const logo = document.getElementById('partyResultLogo');
    if (logo) {
        logo.src = party.logo;
        logo.alt = partyKey;
    }
    
    const name = document.getElementById('partyResultName');
    if (name) {
        name.textContent = party.leader;
    }
    
    const leader = document.getElementById('partyResultLeader');
    if (leader) {
        leader.textContent = `Partijleider ${party.name}`;
    }
    
    if (isStructured) {
        // === REACTIE ===
        setTextContent('partyResultOpening', analysis.reactie?.opening || '');
        setTextContent('partyResultContent', analysis.reactie?.hoofdtekst || '');
        setTextContent('partyResultAfsluiting', analysis.reactie?.afsluiting || '');
        
        // === TOON ANALYSE ===
        setTextContent('partyToon', capitalizeFirst(analysis.analyse?.toon || '-'));
        setTextContent('partyEmotie', capitalizeFirst(analysis.analyse?.emotie || '-'));
        
        const sentiment = analysis.analyse?.sentiment || 0;
        setTextContent('partySentimentValue', (sentiment >= 0 ? '+' : '') + sentiment);
        const sentimentMarker = document.getElementById('partySentimentMarker');
        if (sentimentMarker) {
            const percentage = ((sentiment + 100) / 200) * 100;
            setTimeout(() => {
                sentimentMarker.style.left = `calc(${percentage}% - 6px)`;
            }, 100);
        }
        
        // === AUTHENTICITEIT ===
        const authenticiteit = analysis.meta?.authenticiteit_score || 0;
        setTextContent('partyAuthenticiteit', authenticiteit + '%');
        const authBar = document.getElementById('partyAuthenticiteitBar');
        if (authBar) {
            setTimeout(() => { authBar.style.width = authenticiteit + '%'; }, 100);
        }
        setTextContent('partyRetoriek', capitalizeFirst(analysis.meta?.retorische_stijl || '-'));
        
        // === STANDPUNTEN ===
        const kernpuntenList = document.getElementById('partyKernpunten');
        if (kernpuntenList && analysis.standpunten?.kernpunten) {
            kernpuntenList.innerHTML = '';
            analysis.standpunten.kernpunten.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
                li.innerHTML = `
                    <svg class="w-3 h-3 text-secondary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span>${punt}</span>
                `;
                kernpuntenList.appendChild(li);
            });
        }
        
        // Eens met artikel
        const eensList = document.getElementById('partyEens');
        if (eensList && analysis.standpunten?.eens_met_artikel) {
            eensList.innerHTML = '';
            analysis.standpunten.eens_met_artikel.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'text-xs text-green-800';
                li.textContent = punt;
                eensList.appendChild(li);
            });
        }
        
        // Oneens met artikel
        const oneensList = document.getElementById('partyOneens');
        if (oneensList && analysis.standpunten?.oneens_met_artikel) {
            oneensList.innerHTML = '';
            analysis.standpunten.oneens_met_artikel.forEach(punt => {
                const li = document.createElement('li');
                li.className = 'text-xs text-red-800';
                li.textContent = punt;
                oneensList.appendChild(li);
            });
        }
        
        // === PARTIJ CONTEXT ===
        const beloftesList = document.getElementById('partyBeloftes');
        if (beloftesList && analysis.partij_context?.relevante_beloftes) {
            beloftesList.innerHTML = '';
            analysis.partij_context.relevante_beloftes.forEach(belofte => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5 text-xs text-gray-700';
                li.innerHTML = `
                    <svg class="w-3 h-3 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>${belofte}</span>
                `;
                beloftesList.appendChild(li);
            });
        }
        
        setTextContent('partyOplossing', analysis.partij_context?.voorgestelde_oplossing || '');
        
    } else {
        // Fallback for legacy plain text response
        const content = data.content || '';
        setTextContent('partyResultOpening', '');
        setTextContent('partyResultContent', content);
        setTextContent('partyResultAfsluiting', '');
        
        // Hide structured elements
        ['partyToon', 'partyEmotie', 'partyAuthenticiteit', 'partyRetoriek'].forEach(id => {
            setTextContent(id, '-');
        });
    }
}

function showPartyError(errorMessage) {
    document.getElementById('partyError')?.classList.remove('hidden');
    document.getElementById('backToPartySelection')?.classList.remove('hidden');
    
    const errorMessageElement = document.getElementById('partyErrorMessage');
    if (errorMessageElement) {
        errorMessageElement.textContent = errorMessage;
    }
}

// Audio Features
function initializeAudioFeatures() {
    // Initialize any audio-related features
    <?php if (!empty($blog->audio_path) || !empty($blog->audio_url)): ?>
    console.log('Audio features initialized');
    <?php endif; ?>
}

// Notification System
function showNotification(message, type = 'info') {
    console.log(`Notification: ${message} (${type})`);
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.className += ` ${bgColors[type] || bgColors.info} text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Utility functions for share buttons
function shareOnTwitter() {
    const title = '<?php echo addslashes($blog->title); ?>';
    const url = window.location.href;
    const text = `${title} via @PolitiekPraat`;
    
    window.open(
        `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=400'
    );
}

function shareOnLinkedIn() {
    const url = window.location.href;
    window.open(
        `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=400'
    );
}

function shareOnFacebook() {
    const url = window.location.href;
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=400'
    );
}

async function copyToClipboard() {
    try {
        await navigator.clipboard.writeText(window.location.href);
        showNotification('Link gekopieerd naar klembord!', 'success');
    } catch (err) {
        console.error('Kon link niet kopiëren:', err);
        showNotification('Kon link niet kopiëren', 'error');
    }
}

// Character count for comment textarea
const contentTextarea = document.getElementById('content');
const charCount = document.getElementById('charCount');

if (contentTextarea && charCount) {
    function updateCharCount() {
        const length = contentTextarea.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 1000) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    }
    
    contentTextarea.addEventListener('input', updateCharCount);
    updateCharCount();
}

// Comment like functionality
function initializeCommentLikes() {
    const likeButtons = document.querySelectorAll('.comment-like-btn');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', handleCommentLike);
    });
}

async function handleCommentLike(event) {
    const button = event.currentTarget;
    const commentId = button.getAttribute('data-comment-id');
    const isLiked = button.getAttribute('data-liked') === 'true';
    const action = isLiked ? 'unlike' : 'like';
    
    // Disable button during request
    button.disabled = true;
    
    try {
        const response = await fetch('<?php echo URLROOT; ?>/ajax/comment-like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                comment_id: parseInt(commentId),
                action: action
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update button state
            const isNowLiked = data.is_liked;
            button.setAttribute('data-liked', isNowLiked.toString());
            
            const svg = button.querySelector('svg');
            const span = button.querySelector('span');
            
            if (isNowLiked) {
                // Liked state
                button.classList.remove('text-gray-500', 'hover:text-red-600');
                button.classList.add('text-red-600');
                svg.setAttribute('fill', 'currentColor');
                svg.classList.add('scale-110');
                span.textContent = 'Leuk gevonden';
                button.title = 'Reactie niet meer leuk vinden';
                
                // Add liked badge if not exists and update comment styling
                const commentContainer = button.closest('.rounded-2xl');
                const commentHeader = commentContainer.querySelector('.px-6.py-4');
                const actionsDiv = button.closest('.flex');
                const badgeContainer = actionsDiv.querySelector('.flex.items-center.gap-3');
                
                // Update comment container styling
                commentContainer.className = 'bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border-2 border-red-300 shadow-xl ring-2 ring-red-200 ring-opacity-50 rounded-2xl overflow-hidden transition-all duration-500 transform hover:scale-[1.02] hover:shadow-2xl';
                
                // Update header styling
                commentHeader.className = 'px-6 py-4 bg-gradient-to-r from-red-100 via-pink-100 to-red-100 border-b border-red-200';
                
                if (!badgeContainer.querySelector('.bg-gradient-to-r.from-red-500')) {
                    const badge = document.createElement('div');
                    badge.className = 'inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 border border-red-400 rounded-full shadow-lg creator-liked-badge';
                    badge.innerHTML = `
                        <svg class="w-4 h-4 text-white animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                                                 <span class="text-white text-sm font-bold">Leuk gevonden door auteur</span>
                    `;
                    badgeContainer.appendChild(badge);
                }
                
                // Add like animation and special effects
                createLikeAnimation(button);
                
                // Add sparkle effect to the comment
                createSparkleEffect(commentContainer);
                
            } else {
                // Unliked state
                button.classList.remove('text-red-600');
                button.classList.add('text-gray-500', 'hover:text-red-600');
                svg.setAttribute('fill', 'none');
                svg.classList.remove('scale-110');
                span.textContent = 'Leuk vinden';
                button.title = 'Reactie leuk vinden';
                
                // Reset comment container styling
                const commentContainer = button.closest('.rounded-2xl');
                const commentHeader = commentContainer.querySelector('.px-6.py-4');
                commentContainer.className = 'bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-md';
                commentHeader.className = 'px-6 py-4 bg-gradient-to-r from-gray-50 to-primary/5 border-b border-gray-100';
                
                // Remove liked badge
                const actionsDiv = button.closest('.flex');
                const badgeContainer = actionsDiv.querySelector('.flex.items-center.gap-3');
                const badge = badgeContainer.querySelector('.creator-liked-badge');
                if (badge) {
                    badge.remove();
                }
            }
            
            // Show success message
            const message = isNowLiked ? 'Reactie gemarkeerd als leuk gevonden!' : 'Reactie niet meer leuk gevonden';
            showNotification(message, 'success');
            
        } else {
            throw new Error(data.error || 'Unknown error');
        }
        
    } catch (error) {
        console.error('Comment like error:', error);
        showNotification('Er ging iets mis bij het liken van de reactie', 'error');
    } finally {
        button.disabled = false;
    }
}

function createLikeAnimation(button) {
    // Create floating hearts animation
    const hearts = ['❤️', '💖', '💕', '💗', '💘', '💝'];
    
    for (let i = 0; i < 8; i++) {
        setTimeout(() => {
            const heart = document.createElement('div');
            heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
            heart.className = 'absolute pointer-events-none text-lg';
            heart.style.left = '50%';
            heart.style.top = '50%';
            heart.style.transform = 'translate(-50%, -50%)';
            heart.style.zIndex = '9999';
            
            button.appendChild(heart);
            
            // Animate heart
            const angle = (Math.PI * 2 * i) / 8;
            const distance = 40 + Math.random() * 30;
            const duration = 1200 + Math.random() * 600;
            
            heart.animate([
                {
                    transform: 'translate(-50%, -50%) scale(0) rotate(0deg)',
                    opacity: 1
                },
                {
                    transform: `translate(${Math.cos(angle) * distance - 50}%, ${Math.sin(angle) * distance - 50}%) scale(1.2) rotate(360deg)`,
                    opacity: 0
                }
            ], {
                duration: duration,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            }).onfinish = () => heart.remove();
            
        }, i * 80);
    }
}

function createSparkleEffect(commentContainer) {
    // Create sparkle effects around the comment
    const sparkles = ['✨', '💫', '🌟', '⭐'];
    
    for (let i = 0; i < 12; i++) {
        setTimeout(() => {
            const sparkle = document.createElement('div');
            sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
            sparkle.className = 'absolute pointer-events-none text-sm';
            sparkle.style.zIndex = '9998';
            
            // Random position around the comment
            const rect = commentContainer.getBoundingClientRect();
            const x = Math.random() * rect.width;
            const y = Math.random() * rect.height;
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            
            commentContainer.appendChild(sparkle);
            
            // Animate sparkle
            const moveX = (Math.random() - 0.5) * 60;
            const moveY = (Math.random() - 0.5) * 60;
            const duration = 1500 + Math.random() * 1000;
            
            sparkle.animate([
                {
                    transform: 'scale(0) rotate(0deg)',
                    opacity: 1
                },
                {
                    transform: `translate(${moveX}px, ${moveY}px) scale(1.5) rotate(180deg)`,
                    opacity: 0.7
                },
                {
                    transform: `translate(${moveX * 2}px, ${moveY * 2}px) scale(0) rotate(360deg)`,
                    opacity: 0
                }
            ], {
                duration: duration,
                easing: 'ease-out'
            }).onfinish = () => sparkle.remove();
            
        }, i * 120);
    }
}

// Load More Comments Functionality
function initializeLoadMoreComments() {
    const loadMoreBtn = document.getElementById('loadMoreComments');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Find all hidden comments
            const hiddenComments = document.querySelectorAll('.comment-hidden');
            
            // Show all hidden comments with animation
            hiddenComments.forEach((comment, index) => {
                setTimeout(() => {
                    comment.classList.remove('hidden', 'comment-hidden');
                    comment.style.opacity = '0';
                    comment.style.transform = 'translateY(20px)';
                    
                    // Animate in
                    setTimeout(() => {
                        comment.style.transition = 'all 0.5s ease-out';
                        comment.style.opacity = '1';
                        comment.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100); // Stagger animation
            });
            
            // Hide the load more button with animation
            loadMoreContainer.style.transition = 'all 0.3s ease-out';
            loadMoreContainer.style.opacity = '0';
            loadMoreContainer.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                loadMoreContainer.style.display = 'none';
            }, 300);
        });
    }
}

// Poll functionaliteit
function votePoll(choice) {
    const pollContainer = document.getElementById('pollContainer');
    const pollId = pollContainer.getAttribute('data-poll-id');
    
    if (!pollId) {
        showNotification('Poll ID niet gevonden', 'error');
        return;
    }
    
    // Disable poll buttons tijdens stemmen
    const pollButtons = document.querySelectorAll('.poll-option');
    pollButtons.forEach(button => {
        button.disabled = true;
        button.style.opacity = '0.5';
    });
    
    // Toon loading state
    showNotification('Stem wordt verwerkt...', 'info');
    
    // Verstuur AJAX request
    const baseUrl = '<?php echo URLROOT; ?>';
    fetch(baseUrl + '/ajax/poll-vote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `poll_id=${pollId}&choice=${choice}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toon succes bericht
            showNotification(data.message, 'success');
            
            // Update poll weergave met resultaten
            updatePollResults(data.poll, choice);
        } else {
            // Toon foutmelding
            showNotification(data.message, 'error');
            
            // Re-enable buttons bij fout
            pollButtons.forEach(button => {
                button.disabled = false;
                button.style.opacity = '1';
            });
        }
    })
    .catch(error => {
        console.error('Poll voting error:', error);
        showNotification('Er is een fout opgetreden bij het stemmen', 'error');
        
        // Re-enable buttons bij fout
        pollButtons.forEach(button => {
            button.disabled = false;
            button.style.opacity = '1';
        });
    });
}

function updatePollResults(poll, userChoice) {
    const pollContainer = document.getElementById('pollContainer');
    
    // Bereken percentages
    const totalVotes = parseInt(poll.option_a_votes) + parseInt(poll.option_b_votes);
    const optionAPercentage = totalVotes > 0 ? Math.round((poll.option_a_votes / totalVotes) * 100 * 10) / 10 : 0;
    const optionBPercentage = totalVotes > 0 ? Math.round((poll.option_b_votes / totalVotes) * 100 * 10) / 10 : 0;
    
    // Update HTML met nieuwe clean design
    pollContainer.innerHTML = `
        <div class="space-y-4 mb-6">
            <!-- Optie A -->
            <div class="relative group">
                <div class="bg-white border-2 ${userChoice === 'A' ? 'border-blue-500' : 'border-gray-200'} rounded-xl overflow-hidden">
                    <!-- Progress bar -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-blue-100 opacity-60 transition-all duration-1000 ease-out" style="width: ${optionAPercentage}%"></div>
                    
                    <div class="relative p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    A
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-base break-words">
                                        ${poll.option_a}
                                    </p>
                                    ${userChoice === 'A' ? `
                                        <p class="text-sm text-blue-600 font-medium mt-1">
                                            ✓ Jouw keuze
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <div class="text-2xl font-bold text-blue-600">
                                    ${optionAPercentage}%
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${poll.option_a_votes} ${poll.option_a_votes === 1 ? 'stem' : 'stemmen'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optie B -->
            <div class="relative group">
                <div class="bg-white border-2 ${userChoice === 'B' ? 'border-red-500' : 'border-gray-200'} rounded-xl overflow-hidden">
                    <!-- Progress bar -->
                    <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-red-100 opacity-60 transition-all duration-1000 ease-out" style="width: ${optionBPercentage}%"></div>
                    
                    <div class="relative p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    B
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-base break-words">
                                        ${poll.option_b}
                                    </p>
                                    ${userChoice === 'B' ? `
                                        <p class="text-sm text-red-600 font-medium mt-1">
                                            ✓ Jouw keuze
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <div class="text-2xl font-bold text-red-600">
                                    ${optionBPercentage}%
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${poll.option_b_votes} ${poll.option_b_votes === 1 ? 'stem' : 'stemmen'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bedank bericht -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3 text-green-800">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-medium">Bedankt voor je stem! Je hebt bijgedragen aan het politieke debat.</p>
            </div>
        </div>
    `;
    
    // Update totaal aantal stemmen in header
    const totalVotesElements = document.querySelectorAll('.font-semibold.text-primary');
    totalVotesElements.forEach(element => {
        if (element.textContent && !isNaN(parseInt(element.textContent))) {
            element.textContent = totalVotes;
            
            // Update bijbehorende tekst
            const nextElement = element.nextSibling;
            if (nextElement && nextElement.textContent) {
                nextElement.textContent = totalVotes === 1 ? ' persoon heeft gestemd' : ' mensen hebben gestemd';
            }
        }
    });
}

// Initialize comment likes when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCommentLikes();
    initializeLoadMoreComments();
});

// ============================================
// Instagram Story Generator Class
// ============================================
class InstagramStoryGenerator {
    constructor(options) {
        this.title = options.title || 'Blog';
        this.author = options.author || 'PolitiekPraat';
        this.date = options.date || '';
        this.readingTime = options.readingTime || '5 min';
        this.blogUrl = options.blogUrl || window.location.href;
        this.imageUrl = options.imageUrl || null;
        
        // Instagram Story dimensions (9:16 aspect ratio)
        this.width = 1080;
        this.height = 1920;
        
        // PolitiekPraat colors
        this.colors = {
            primaryDark: '#0f2a44',
            primary: '#1a365d',
            primaryLight: '#2d4a6b',
            secondary: '#c41e3a',
            secondaryLight: '#d63856',
            accent: '#F59E0B'
        };
    }
    
    async generateStory() {
        const canvas = document.createElement('canvas');
        canvas.width = this.width;
        canvas.height = this.height;
        const ctx = canvas.getContext('2d');
        
        // Draw background (with image if available)
        await this.drawBackground(ctx);
        
        // Draw content
        this.drawContent(ctx);
        
        // Draw branding
        this.drawBranding(ctx);
        
        return canvas;
    }
    
    async drawBackground(ctx) {
        // Try to load and draw the blog image first
        if (this.imageUrl) {
            try {
                const img = await this.loadImage(this.imageUrl);
                
                // Calculate cover dimensions (fill entire canvas)
                const imgRatio = img.width / img.height;
                const canvasRatio = this.width / this.height;
                
                let drawWidth, drawHeight, drawX, drawY;
                
                if (imgRatio > canvasRatio) {
                    // Image is wider - fit height, crop width
                    drawHeight = this.height;
                    drawWidth = img.width * (this.height / img.height);
                    drawX = (this.width - drawWidth) / 2;
                    drawY = 0;
                } else {
                    // Image is taller - fit width, crop height
                    drawWidth = this.width;
                    drawHeight = img.height * (this.width / img.width);
                    drawX = 0;
                    drawY = (this.height - drawHeight) / 2;
                }
                
                // Draw the image
                ctx.drawImage(img, drawX, drawY, drawWidth, drawHeight);
                
                // Add dark overlay gradient for text readability
                const overlay = ctx.createLinearGradient(0, 0, 0, this.height);
                overlay.addColorStop(0, 'rgba(15, 42, 68, 0.85)');
                overlay.addColorStop(0.3, 'rgba(15, 42, 68, 0.7)');
                overlay.addColorStop(0.5, 'rgba(15, 42, 68, 0.6)');
                overlay.addColorStop(0.7, 'rgba(15, 42, 68, 0.7)');
                overlay.addColorStop(1, 'rgba(15, 42, 68, 0.9)');
                ctx.fillStyle = overlay;
                ctx.fillRect(0, 0, this.width, this.height);
                
                // Add colored accent overlay
                const accentOverlay = ctx.createLinearGradient(0, this.height * 0.6, 0, this.height);
                accentOverlay.addColorStop(0, 'transparent');
                accentOverlay.addColorStop(1, 'rgba(196, 30, 58, 0.3)');
                ctx.fillStyle = accentOverlay;
                ctx.fillRect(0, 0, this.width, this.height);
                
            } catch (e) {
                console.log('Could not load blog image, using gradient background');
                this.drawGradientBackground(ctx);
            }
        } else {
            this.drawGradientBackground(ctx);
        }
        
        // Add subtle decorative elements
        this.drawDecorations(ctx);
    }
    
    loadImage(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Failed to load image'));
            img.src = url;
        });
    }
    
    drawGradientBackground(ctx) {
        // Create diagonal gradient
        const gradient = ctx.createLinearGradient(0, 0, this.width, this.height);
        gradient.addColorStop(0, this.colors.primaryDark);
        gradient.addColorStop(0.4, this.colors.primary);
        gradient.addColorStop(0.7, this.colors.primaryLight);
        gradient.addColorStop(1, this.colors.secondary);
        
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, this.width, this.height);
    }
    
    drawDecorations(ctx) {
        // Top ambient glow
        const topGlow = ctx.createRadialGradient(
            this.width * 0.3, 150, 0,
            this.width * 0.3, 150, 350
        );
        topGlow.addColorStop(0, 'rgba(196, 30, 58, 0.2)');
        topGlow.addColorStop(1, 'transparent');
        ctx.fillStyle = topGlow;
        ctx.fillRect(0, 0, this.width, 500);
        
        // Bottom ambient glow
        const bottomGlow = ctx.createRadialGradient(
            this.width * 0.7, this.height - 200, 0,
            this.width * 0.7, this.height - 200, 400
        );
        bottomGlow.addColorStop(0, 'rgba(26, 54, 93, 0.25)');
        bottomGlow.addColorStop(1, 'transparent');
        ctx.fillStyle = bottomGlow;
        ctx.fillRect(0, this.height - 600, this.width, 600);
        
        // Decorative circles (subtle)
        ctx.globalAlpha = 0.06;
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2;
        
        ctx.beginPath();
        ctx.arc(this.width - 80, 120, 150, 0, Math.PI * 2);
        ctx.stroke();
        
        ctx.beginPath();
        ctx.arc(80, this.height - 350, 100, 0, Math.PI * 2);
        ctx.stroke();
        
        ctx.globalAlpha = 1;
    }
    
    drawContent(ctx) {
        const centerX = this.width / 2;
        const padding = 60;
        const contentWidth = this.width - (padding * 2);
        
        // ===== TOP SECTION =====
        // "POLITIEK ARTIKEL" badge
        const badgeY = 180;
        ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
        this.roundRect(ctx, centerX - 130, badgeY - 22, 260, 44, 22);
        ctx.fill();
        
        ctx.font = 'bold 22px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.fillText('POLITIEK ARTIKEL', centerX, badgeY + 7);
        
        // ===== TITLE SECTION =====
        // Calculate title with proper sizing
        const titleFontSize = this.calculateTitleFontSize(ctx, this.title, contentWidth);
        const titleLines = this.wrapText(ctx, this.title, contentWidth - 40, titleFontSize);
        const lineHeight = titleFontSize * 1.2;
        const titleBlockHeight = (titleLines.length * lineHeight) + 60;
        
        // Position title card in upper-middle area
        const titleCardY = 280;
        
        // Title background card
        ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
        this.roundRect(ctx, padding, titleCardY, contentWidth, titleBlockHeight, 24);
        ctx.fill();
        
        // Border accent
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
        ctx.lineWidth = 2;
        this.roundRect(ctx, padding, titleCardY, contentWidth, titleBlockHeight, 24);
        ctx.stroke();
        
        // Draw title text
        ctx.font = `bold ${titleFontSize}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        
        const titleStartY = titleCardY + 30 + titleFontSize;
        titleLines.forEach((line, index) => {
            ctx.fillText(line, centerX, titleStartY + (index * lineHeight));
        });
        
        // ===== META INFO SECTION =====
        const metaY = titleCardY + titleBlockHeight + 50;
        
        // Author
        ctx.font = '600 32px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
        ctx.fillText(`Door ${this.author}`, centerX, metaY);
        
        // Date and reading time
        ctx.font = '400 26px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
        ctx.fillText(`${this.date}  |  ${this.readingTime} leestijd`, centerX, metaY + 45);
        
        // Decorative separator with diamond
        const sepY = metaY + 100;
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(centerX - 80, sepY);
        ctx.lineTo(centerX + 80, sepY);
        ctx.stroke();
        
        // Diamond accent
        ctx.fillStyle = this.colors.secondaryLight;
        ctx.save();
        ctx.translate(centerX, sepY);
        ctx.rotate(Math.PI / 4);
        ctx.fillRect(-7, -7, 14, 14);
        ctx.restore();
    }
    
    calculateTitleFontSize(ctx, text, maxWidth) {
        // Start with ideal size and reduce if needed
        const sizes = [56, 52, 48, 44, 40, 36];
        
        for (const size of sizes) {
            ctx.font = `bold ${size}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
            const lines = this.wrapText(ctx, text, maxWidth - 40, size);
            
            // Max 4 lines with reasonable total height
            if (lines.length <= 4) {
                return size;
            }
        }
        
        return 36; // Minimum size
    }
    
    drawBranding(ctx) {
        const centerX = this.width / 2;
        const brandingY = this.height - 320;
        const padding = 60;
        const contentWidth = this.width - (padding * 2);
        
        // CTA background card
        ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
        this.roundRect(ctx, padding + 20, brandingY - 30, contentWidth - 40, 170, 20);
        ctx.fill();
        
        // "Lees het volledige artikel op" text
        ctx.font = '400 28px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.textAlign = 'center';
        ctx.fillText('Lees het volledige artikel op', centerX, brandingY + 20);
        
        // PolitiekPraat.nl - prominent
        ctx.font = 'bold 48px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.fillText('POLITIEKPRAAT.NL', centerX, brandingY + 85);
        
        // Swipe up indicator
        const arrowY = this.height - 90;
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        
        // Arrow up
        ctx.beginPath();
        ctx.moveTo(centerX - 18, arrowY + 8);
        ctx.lineTo(centerX, arrowY - 8);
        ctx.lineTo(centerX + 18, arrowY + 8);
        ctx.stroke();
        
        // "Veeg omhoog" text
        ctx.font = '400 22px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.45)';
        ctx.fillText('Veeg omhoog voor meer', centerX, arrowY + 45);
    }
    
    wrapText(ctx, text, maxWidth, fontSize) {
        ctx.font = `bold ${fontSize}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`;
        const words = text.split(' ');
        const lines = [];
        let currentLine = '';
        
        for (const word of words) {
            const testLine = currentLine ? `${currentLine} ${word}` : word;
            const metrics = ctx.measureText(testLine);
            
            if (metrics.width > maxWidth && currentLine) {
                lines.push(currentLine);
                currentLine = word;
            } else {
                currentLine = testLine;
            }
        }
        
        if (currentLine) {
            lines.push(currentLine);
        }
        
        // Limit to 4 lines max, truncate if needed
        if (lines.length > 4) {
            lines.length = 4;
            const lastLine = lines[3];
            // Truncate last line properly
            while (ctx.measureText(lastLine + '...').width > maxWidth && lastLine.length > 0) {
                lines[3] = lastLine.substring(0, lastLine.length - 1);
            }
            lines[3] = lines[3].trim() + '...';
        }
        
        return lines;
    }
    
    roundRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }
    
    async toBlob() {
        const canvas = await this.generateStory();
        return new Promise((resolve) => {
            canvas.toBlob(resolve, 'image/png', 1.0);
        });
    }
    
    async toDataUrl() {
        const canvas = await this.generateStory();
        return canvas.toDataURL('image/png');
    }
}

// ============================================
// Instagram Story Share Functions
// ============================================
async function shareToInstagramStory() {
    const shareButton = document.getElementById('instagramStoryBtn');
    const originalContent = shareButton.innerHTML;
    
    // Show loading state
    shareButton.disabled = true;
    shareButton.innerHTML = `
        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex items-center gap-3">
            <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="hidden sm:block text-sm font-semibold">Genereren...</span>
        </div>
    `;
    
    try {
        // Get blog data from PHP
        const blogData = {
            title: <?php echo json_encode($blog->title); ?>,
            author: <?php echo json_encode($blog->author_name); ?>,
            date: <?php echo json_encode((new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($blog->published_at))); ?>,
            readingTime: document.getElementById('reading-minutes')?.textContent + ' min' || '5 min',
            blogUrl: window.location.href,
            imageUrl: <?php echo json_encode($blog->image_path ? getBlogImageUrl($blog->image_path) : null); ?>
        };
        
        // Generate the story image
        const generator = new InstagramStoryGenerator(blogData);
        const blob = await generator.toBlob();
        const file = new File([blob], 'politiekpraat-story.png', { type: 'image/png' });
        
        // Check if Web Share API with files is supported (mobile)
        if (navigator.canShare && navigator.canShare({ files: [file] })) {
            await navigator.share({
                files: [file],
                title: blogData.title,
                text: `Lees "${blogData.title}" op PolitiekPraat.nl`,
                url: blogData.blogUrl
            });
            showNotification('Story succesvol gedeeld!', 'success');
        } else {
            // Fallback: download the image
            downloadStoryImage(blob, blogData.title);
        }
    } catch (error) {
        if (error.name !== 'AbortError') {
            console.error('Error sharing story:', error);
            showNotification('Er ging iets mis bij het genereren van de story', 'error');
        }
    } finally {
        // Restore button state
        shareButton.disabled = false;
        shareButton.innerHTML = originalContent;
    }
}

function downloadStoryImage(blob, title) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `politiekpraat-${title.toLowerCase().replace(/[^a-z0-9]+/g, '-').substring(0, 50)}.png`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('Story afbeelding gedownload! Upload deze naar je Instagram Story.', 'success');
}

// Preview function for testing
async function previewInstagramStory() {
    const blogData = {
        title: <?php echo json_encode($blog->title); ?>,
        author: <?php echo json_encode($blog->author_name); ?>,
        date: <?php echo json_encode((new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($blog->published_at))); ?>,
        readingTime: document.getElementById('reading-minutes')?.textContent + ' min' || '5 min',
        blogUrl: window.location.href,
        imageUrl: <?php echo json_encode($blog->image_path ? getBlogImageUrl($blog->image_path) : null); ?>
    };
    
    const generator = new InstagramStoryGenerator(blogData);
    const dataUrl = await generator.toDataUrl();
    
    // Open preview in new window
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Story Preview - ${blogData.title}</title>
            <style>
                body { 
                    margin: 0; 
                    display: flex; 
                    justify-content: center; 
                    align-items: center; 
                    min-height: 100vh; 
                    background: #1a1a1a;
                }
                img { 
                    max-height: 90vh; 
                    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
                    border-radius: 20px;
                }
            </style>
        </head>
        <body>
            <img src="${dataUrl}" alt="Instagram Story Preview">
        </body>
        </html>
    `);
}

console.log('Blog view script fully loaded and initialized');
</script>