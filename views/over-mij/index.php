<?php
// Voorkom directe toegang tot dit bestand
if (!defined('URLROOT')) {
    exit;
}
?>

<!-- Link naar AOS (Animate On Scroll) library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<style>
/* Custom CSS voor over-mij pagina */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

.over-mij-hero {
    background: linear-gradient(135deg, #1a56db 0%, #c41e3a 100%);
    position: relative;
    overflow: hidden;
}

.floating-element {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: floating 6s ease-in-out infinite;
}

.floating-element:nth-child(1) { top: 20%; left: 10%; width: 60px; height: 60px; animation-delay: 0s; }
.floating-element:nth-child(2) { top: 60%; right: 10%; width: 80px; height: 80px; animation-delay: 2s; }
.floating-element:nth-child(3) { bottom: 30%; left: 20%; width: 40px; height: 40px; animation-delay: 4s; }

@keyframes floating {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.profile-card {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.mission-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.skill-badge {
    background: linear-gradient(135deg, #1a56db 0%, #c41e3a 100%);
    color: white;
    transition: all 0.3s ease;
}

.skill-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(26, 86, 219, 0.3);
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, #1a56db 0%, #c41e3a 100%);
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.2);
}

.social-link {
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.social-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #1a56db 0%, #c41e3a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.animated-bg {
    background: linear-gradient(270deg, #1a56db, #c41e3a, #1a56db, #c41e3a);
    background-size: 800% 800%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.tech-item {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.tech-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.geometric-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0.05;
    background-image: 
        radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0);
    background-size: 30px 30px;
}

@media (max-width: 768px) {
    .floating-element { display: none; }
    .tech-grid { grid-template-columns: repeat(2, 1fr); }
    .over-mij-hero {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Hero Section -->
    <section class="over-mij-hero min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Animated floating elements -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        
        <!-- Geometric background pattern -->
        <div class="geometric-bg"></div>
        
        <!-- Hero Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    
                    <!-- Left Column: Profile Info -->
                    <div class="text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                        <!-- Profile Image -->
                        <div class="relative inline-block mb-8">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-red-600 rounded-full blur-lg opacity-30 animate-pulse"></div>
                            <div class="relative w-48 h-48 mx-auto rounded-full overflow-hidden border-4 border-white shadow-2xl">
                                <img src="<?= URLROOT ?>/public/images/naoufal-foto.jpg" 
                                     onerror="if(this.src !== '<?= URLROOT ?>/images/naoufal-foto.jpg') this.src='<?= URLROOT ?>/images/naoufal-foto.jpg'; else if(this.src !== '<?= URLROOT ?>/public/images/profiles/naoufal-foto.jpg') this.src='<?= URLROOT ?>/public/images/profiles/naoufal-foto.jpg';"
                                     alt="Foto van Naoufal Andichi" 
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            </div>
                            <!-- Online indicator -->
                            <div class="absolute bottom-4 right-4 w-6 h-6 bg-green-500 border-4 border-white rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Name and Title -->
                        <h1 class="text-5xl lg:text-7xl font-black text-white mb-4 leading-tight">
                            Naoufal Andichi
                        </h1>
                        <p class="text-2xl lg:text-3xl text-blue-100 mb-6 font-light">
                            Software Developer die dol is op politiek
                        </p>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3 mb-8">
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">🏛️ Liberaal</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">💻 Developer</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">📊 Analist</span>
                            <span class="skill-badge px-4 py-2 rounded-full text-sm font-semibold">✍️ Blogger</span>
                        </div>
                        
                        <!-- Social Links -->
                        <div class="flex justify-center lg:justify-start space-x-4">
                            <a href="https://www.linkedin.com/in/naoufalandichi/" 
                               target="_blank" rel="noopener noreferrer" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="mailto:naoufal@politiekpraat.nl" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            <a href="<?= URLROOT ?>/blogs" 
                               class="social-link p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 text-purple-600 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Column: Quick Stats -->
                    <div class="space-y-8" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">150+</div>
                                <div class="text-gray-600 text-sm font-medium">Artikelen Geschreven</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">50k+</div>
                                <div class="text-gray-600 text-sm font-medium">Lezers Bereikt</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">3+</div>
                                <div class="text-gray-600 text-sm font-medium">Jaren Ervaring</div>
                            </div>
                            <div class="profile-card p-6 rounded-2xl text-center">
                                <div class="text-3xl font-black gradient-text mb-2">20+</div>
                                <div class="text-gray-600 text-sm font-medium">Politieke Thema's</div>
                            </div>
                        </div>
                        
                        <!-- Mission Statement Card -->
                        <div class="mission-card p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold gradient-text mb-4">Wat ik doe</h3>
                            <p class="text-gray-700 leading-relaxed">
                                "Ik probeer politiek gewoon begrijpelijk uit te leggen. Geen ingewikkelde taal, gewoon helder en eerlijk."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-50 to-transparent rounded-full opacity-60"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-purple-50 to-transparent rounded-full opacity-60"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Over <span class="gradient-text">Mij</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-red-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- About Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    <!-- Personal Story -->
                    <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000">
                        <div class="bg-gradient-to-br from-white to-blue-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-blue-600 to-red-600 rounded-full mr-4"></span>
                                Mijn Verhaal
                            </h3>
                            
                            <div class="prose prose-lg text-gray-700 space-y-6">
                                <p class="leading-relaxed">
                                    Hey! Ik ben Naoufal, en ik ben een beetje een nerd als het gaat om twee dingen: programmeren en politiek. Misschien een rare combinatie, maar het werkt voor mij!
                                </p>
                                
                                <p class="leading-relaxed">
                                    Het begon allemaal met computers. Ik was dat kind dat urenlang kon klooien met code. Maar ergens onderweg raakte ik gefascineerd door hoe onze samenleving werkt en hoe beslissingen in Den Haag ons allemaal raken. Zo ontstond het idee voor PolitiekPraat.
                                </p>
                                
                                <p class="leading-relaxed">
                                    Ik ben liberaal ingesteld, geloof in vrijheid, eigen verantwoordelijkheid en dat onderwijs heel veel problemen kan oplossen. Maar bovenal hou ik van een goed debat en probeer ik dingen gewoon helder uit te leggen zonder politieke jargon.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Philosophy & Skills -->
                    <div class="space-y-8" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Philosophy Card -->
                        <div class="bg-gradient-to-br from-white to-purple-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-red-600 to-blue-600 rounded-full mr-4"></span>
                                Mijn Filosofie
                            </h3>
                            
                            <div class="space-y-4">
                                <blockquote class="text-lg italic text-gray-700 border-l-4 border-red-600 pl-6">
                                    "Politiek hoeft niet ingewikkeld te zijn als je het gewoon normaal uitlegt."
                                </blockquote>
                                
                                <p class="text-gray-700 leading-relaxed">
                                    Ik snap best dat politiek vaak saai of verwarrend overkomt. Daarom probeer ik het gewoon uit te leggen zoals ik het zelf zou willen horen, zonder gedoe en met wat humor waar mogelijk.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Skills & Expertise -->
                        <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-xl">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-8 bg-gradient-to-b from-blue-600 to-red-600 rounded-full mr-4"></span>
                                Expertise
                            </h3>
                            
                            <div class="tech-grid">
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">🏛️</div>
                                    <div class="text-sm font-semibold text-gray-700">Politieke Analyse</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">📊</div>
                                    <div class="text-sm font-semibold text-gray-700">Data Visualisatie</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">✍️</div>
                                    <div class="text-sm font-semibold text-gray-700">Content Creatie</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">💻</div>
                                    <div class="text-sm font-semibold text-gray-700">Web Development</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">📱</div>
                                    <div class="text-sm font-semibold text-gray-700">UX Design</div>
                                </div>
                                <div class="tech-item p-4 rounded-xl text-center">
                                    <div class="text-2xl mb-2">🎯</div>
                                    <div class="text-sm font-semibold text-gray-700">Strategy</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
        <!-- Animated background -->
        <div class="animated-bg absolute inset-0 opacity-5"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Mijn <span class="gradient-text">Visie</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-blue-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- Mission Content -->
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white/80 backdrop-blur-lg p-12 rounded-3xl shadow-2xl border border-white/20" data-aos="zoom-in" data-aos-duration="1000">
                        <!-- Quote decoration -->
                        <div class="text-6xl text-blue-600/20 mb-6">"</div>
                        
                        <div class="space-y-8 text-lg leading-relaxed text-gray-700">
                            <p class="text-xl lg:text-2xl font-light text-gray-800 mb-8">
                                Oké, dus waarom PolitiekPraat? Simpel: omdat ik merkte dat politiek vaak zo droog wordt uitgelegd dat mensen er van in slaap vallen. Dat kan beter!
                            </p>
                            
                            <p>
                                Ik schrijf hier over alles wat me interesseert: klimaat, onderwijs, economie, whatever. Niet omdat ik alles beter weet, maar omdat ik graag snap hoe dingen werken en dat delen.
                            </p>
                            
                            <p>
                                Het mooie van politiek is dat het eigenlijk overal over gaat. Van hoeveel je belasting betaalt tot of je straks nog een huis kan kopen. Het raakt ons allemaal, dus waarom zou het saai moeten zijn?
                            </p>
                            
                            <p class="text-xl font-semibold gradient-text">
                                Dus neem een kijkje, lees wat rond, en laat vooral weten wat je ervan vindt. Discussie is goed, dat maakt het alleen maar interessanter!
                            </p>
                        </div>
                        
                        <!-- Quote end decoration -->
                        <div class="text-6xl text-blue-600/20 text-right">"</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Journey Timeline Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6">
                        Mijn <span class="gradient-text">Reis</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-red-600 mx-auto rounded-full"></div>
                </div>
                
                <!-- Timeline -->
                <div class="space-y-12">
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hoe het begon</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Tijdens mijn studie kreeg ik door dat politiek eigenlijk best belangrijk is, maar dat veel mensen er niet zo veel van snappen. Inclusief ikzelf toen nog.
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Eerste artikelen</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Ik begon dingen op te schrijven die ik interessant vond. Bleek dat andere mensen dat ook leuk vonden om te lezen. Wie had dat gedacht?
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">PolitiekPraat ontstaat</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Met wat programmeerskills en veel koffie bouwde ik deze site. Geen fancy agency, gewoon ik achter mijn laptop die iets cools wilde maken.
                        </p>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Wat er komt</h3>
                        <p class="text-gray-600 leading-relaxed">
                                                            Meer artikelen, meer tools zoals de PartijMeter, en hopelijk steeds meer mensen die snappen dat politiek eigenlijk best interessant kan zijn.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

         <!-- Call to Action Section -->
    <section class="py-24 bg-gradient-to-br from-slate-900 via-blue-900 to-red-900 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <!-- Primary floating orbs -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-gradient-to-r from-blue-400/20 to-red-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gradient-to-l from-red-400/15 to-blue-400/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gradient-to-br from-blue-400/25 to-red-400/25 rounded-full blur-2xl animate-pulse" style="animation-delay: 4s;"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="20" cy="20" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <!-- Header Content -->
                <div class="mb-16" data-aos="fade-up" data-aos-duration="1000">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-lg border border-white/20 text-white/90 text-sm font-medium mb-8">
                        <svg class="w-4 h-4 mr-2 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-2-2V10a2 2 0 012-2h2m2-4h6a2 2 0 012 2v6a2 2 0 01-2 2h-6l-4 4V8a2 2 0 012-2z"/>
                        </svg>
                        Laten we Verbinden
                    </div>
                    
                    <h2 class="text-4xl lg:text-6xl font-black text-white mb-6 leading-tight">
                        Heb je een <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">Vraag</span>?
                    </h2>
                    
                    <p class="text-xl lg:text-2xl text-blue-100 leading-relaxed max-w-3xl mx-auto">
                        Vragen over iets politieks? Een idee voor een artikel? Of gewoon zin om te brainstormen? 
                        Stuur me gerust een berichtje!
                    </p>
                </div>
                
                <!-- Contact Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!-- Email Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="100">
                        <a href="mailto:naoufal@politiekpraat.nl" 
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Direct Contact</h3>
                                <p class="text-blue-200 text-sm mb-4">Voor vragen, feedback of samenwerking</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- LinkedIn Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="200">
                        <a href="https://www.linkedin.com/in/naoufalandichi/" 
                           target="_blank" rel="noopener noreferrer"
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Professioneel Netwerk</h3>
                                <p class="text-blue-200 text-sm mb-4">Verbind met me op LinkedIn</p>
                                <div class="text-blue-300 font-medium">Naoufal Andichi</div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Blog Card -->
                    <div class="group" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?= URLROOT ?>/blogs" 
                           class="block bg-white/10 backdrop-blur-lg p-8 rounded-3xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Mijn Analyses</h3>
                                <p class="text-blue-200 text-sm mb-4">Ontdek mijn politieke inzichten</p>
                                <div class="text-blue-300 font-medium">Bekijk Blogs</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Bottom CTA -->
                <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/20" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-left">
                            <h3 class="text-2xl font-bold text-white mb-2">Laten we politiek leuker maken</h3>
                            <p class="text-blue-200">Want het hoeft echt niet zo droog te zijn als het vaak lijkt.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?= URLROOT ?>/partijmeter" 
                               class="px-8 py-4 bg-gradient-to-r from-blue-600 to-red-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:to-red-700 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                Probeer de PartijMeter
                            </a>
                            <a href="<?= URLROOT ?>/blogs" 
                               class="px-8 py-4 bg-white/20 text-white font-bold rounded-2xl border border-white/30 hover:bg-white/30 transition-all duration-300 hover:scale-105">
                                Lees Meer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
});

// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Profile image hover effect
    const profileImg = document.querySelector('.over-mij-hero img');
    if (profileImg) {
        profileImg.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        profileImg.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }
    
    // Smooth scrolling for internal links
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
});
</script>