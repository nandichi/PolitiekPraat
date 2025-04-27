<?php
// Voorkom directe toegang tot dit bestand
if (!defined('URLROOT')) {
    exit;
}
?>

<main class="bg-white min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <!-- Background design elements -->
        <div class="absolute inset-0 z-0">
            <!-- Subtle dot pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#2563eb 1.5px, transparent 0); background-size: 30px 30px;"></div>
            
            <!-- Decorative shapes -->
            <div class="absolute top-0 right-0 w-1/2 h-96 bg-gradient-to-bl from-primary/5 to-transparent rounded-bl-[100px] -z-1"></div>
            <div class="absolute bottom-0 left-0 w-1/2 h-96 bg-gradient-to-tr from-secondary/5 to-transparent rounded-tr-[100px] -z-1"></div>
            
            <!-- Subtle waves - using SVG directly instead of base64 -->
            <div class="absolute inset-0 opacity-[0.02]">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100px" viewBox="0 0 1280 140" preserveAspectRatio="none" class="absolute bottom-0 w-full">
                    <g fill="#2563eb">
                        <path d="M1280 86c0 0-311.9 0-389 100-66.646 19.26-307.4 101.176-318.412-S343.46 236.791 0 140v-140h14l14 .005 1260-.005z"></path>
                    </g>
                </svg>
            </div>
        </div>

        <div class="container mx-auto px-6 pt-20 pb-12 relative z-10">
            <!-- Page title with subtle animation -->
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="inline-block relative">
                        <span class="absolute -inset-1 rounded-lg bg-gradient-to-r from-primary/20 to-secondary/20 blur-xl opacity-70 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt"></span>
                        <h1 class="relative text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary leading-tight">
                            Over PolitiekPraat
                        </h1>
                    </span>
                </div>
                <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto font-light">
                    <span class="border-b-2 border-primary/30 pb-1">Een platform waar technologie en democratie samenkomen</span>
                </p>
            </div>

            <!-- Main content with modern card design -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start max-w-7xl mx-auto">
                <!-- About the founder - 3 column span -->
                <div class="lg:col-span-3 bg-white rounded-2xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden relative group">
                    <!-- Card top decoration -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-primary to-secondary"></div>
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Profile section with image -->
                        <div class="md:w-1/3 flex flex-col items-center">
                            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg mb-4">
                                <!-- Verschillende paden proberen om de foto te laten werken -->
                                <?php 
                                $imagePath = URLROOT . '/images/naoufal-foto.jpg';
                                $imagePath2 = URLROOT . '/public/images/naoufal-foto.jpg';
                                $imagePath3 = URLROOT . '/public/images/profiles/naoufal-foto.jpg';
                                $imagePath4 = '/img/naoufal-foto.jpg';
                                ?>
                                
                                <!-- Vereenvoudigde fallback zonder complexe JS -->
                                <img src="<?= $imagePath ?>" 
                                     onerror="if(this.src !== '<?= $imagePath2 ?>') this.src='<?= $imagePath2 ?>'; else if(this.src !== '<?= $imagePath3 ?>') this.src='<?= $imagePath3 ?>'; else if(this.src !== '<?= $imagePath4 ?>') this.src='<?= $imagePath4 ?>';"
                                     alt="Foto van Naoufal Andichi" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Social links and badges -->
                            <div class="flex flex-wrap justify-center gap-2 w-full">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Liberaal
                                </span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-secondary/10 text-secondary group-hover:bg-secondary/20 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                    Developer
                                </span>
                                <a href="https://www.linkedin.com/in/naoufalandichi/" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                    LinkedIn
                                </a>
                            </div>
                        </div>
                        
                        <!-- Bio content -->
                        <div class="md:w-2/3">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="bg-gradient-to-r from-primary to-secondary h-6 w-1 rounded mr-3"></span>
                                Over Mij
                            </h2>
                            
                            <div class="prose prose-slate max-w-none">
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Hoi! Ik ben Naoufal Andichi, een gepassioneerde liefhebber van technologie en politiek. Al van jongs af aan ben ik gefascineerd door de manier waarop onze samenleving functioneert en hoe technologie ons dagelijks leven beïnvloedt.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Mijn reis begon met een nieuwsgierigheid naar computers, maar al snel ontdekte ik de kracht van politiek om echte verandering teweeg te brengen. Deze unieke combinatie van interesses heeft me geïnspireerd om PolitiekPraat te creëren.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Politiek is voor mij meer dan alleen een onderwerp; het is een passie. Ik volg het nieuws op de voet en geniet van gesprekken over de toekomst van ons land. Als liberaal geloof ik sterk in individuele vrijheid, persoonlijke verantwoordelijkheid en de kracht van onderwijs om de wereld te verbeteren.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Met PolitiekPraat wil ik een platform bieden waar iedereen zich welkom voelt om te leren en te groeien. Het is mijn missie om een ruimte te creëren waar we samen kunnen praten, vragen kunnen stellen en van elkaar kunnen leren.
                                </p>
                                <p class="text-gray-700 leading-relaxed">
                                    Laten we samen de wereld van de politiek verkennen en ontdekken hoe we een positieve impact kunnen maken. Jouw stem is belangrijk, en ik kijk ernaar uit om samen met jou te groeien.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mission & Vision - 2 column span -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden relative group">
                    <!-- Card top decoration -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-secondary to-primary"></div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="bg-gradient-to-r from-secondary to-primary h-6 w-1 rounded mr-3"></span>
                        Mijn Missie
                    </h2>
                    
                    <!-- Mission statement with styled quotes -->
                    <div class="relative">
                        <div class="absolute -top-2 -left-2 text-6xl text-primary/10">"</div>
                        <div class="prose prose-slate max-w-none pl-6">
                            <p class="text-gray-700 leading-relaxed mb-5">
                                Welkom bij PolitiekPraat! Hier draait alles om het delen van frisse en scherpe inzichten over de politieke wereld. Met een flinke dosis enthousiasme duiken we in actuele onderwerpen en brengen we ze tot leven.
                            </p>
                            <p class="text-gray-700 leading-relaxed mb-5">
                                Of het nu gaat om klimaatverandering, onderwijs of sociale rechtvaardigheid, PolitiekPraat is dé plek waar ideeën en perspectieven samenkomen. We willen je inspireren en uitdagen om mee te denken en te discussiëren.
                            </p>
                            <p class="text-gray-700 leading-relaxed mb-5">
                                Door het delen van krachtige analyses en meningen, streven we ernaar om een beter begrip te creëren van de complexe wereld waarin we leven. Het is een uitnodiging om samen te leren en te groeien in ons inzicht en begrip van de politiek.
                            </p>
                            <p class="text-gray-700 leading-relaxed font-medium">
                                Sluit je aan bij PolitiekPraat en ontdek de energie van geïnformeerde discussie en betrokkenheid. Samen kunnen we een verschil maken!
                            </p>
                        </div>
                        <div class="absolute -bottom-6 -right-2 text-6xl text-primary/10">"</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>