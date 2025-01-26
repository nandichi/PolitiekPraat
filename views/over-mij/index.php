<?php
// Voorkom directe toegang tot dit bestand
if (!defined('URLROOT')) {
    exit;
}
?>

<main class="bg-gradient-to-br from-primary/5 via-white to-secondary/5">
    <!-- Hero Section met moderne geometrische patronen -->
    <div class="relative overflow-hidden">
        <!-- Geometrisch patroon overlay -->
        <div class="absolute inset-0 z-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #2563eb 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <!-- Decoratieve elementen die democratie symboliseren -->
        <div class="absolute top-0 right-0 w-1/3 h-64 bg-gradient-to-bl from-primary/10 to-transparent transform -rotate-12"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-64 bg-gradient-to-tr from-secondary/10 to-transparent transform rotate-12"></div>

        <div class="container mx-auto px-4 py-16 relative z-10">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 tracking-tight">
                    Over PolitiekPraat
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Een platform waar technologie en democratie samenkomen
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-stretch">
                <!-- Persoonlijke Sectie -->
                <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 border border-primary/10 shadow-lg h-full transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:border-primary/30 group">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-8 h-8 transform transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Over de Oprichter</h3>
                            <div class="prose prose-lg">
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Hoi! Ik ben Naoufal Andichi, 20 jaar oud en zit in mijn laatste jaar van de opleiding Software Development op het MBO niveau 4. 
                                    Na mijn diploma ga ik Rechten studeren aan de HAN. Ik vind het leuk om met computers te werken, maar ik wil ook graag 
                                    meer leren over hoe onze samenleving in elkaar zit. Daarom kies ik voor deze bijzondere combinatie.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Politiek heeft mij altijd al geboeid. Als kind keek ik al naar het Jeugdjournaal en stelde ik veel vragen over 
                                    wat er in Nederland gebeurde. Nu volg ik het nieuws elke dag en praat ik graag met anderen over wat er speelt in 
                                    ons land. Als lid van D66 geloof ik dat iedereen een eerlijke kans verdient en dat wij door goed onderwijs en 
                                    nieuwe ideeën Nederland beter kunnen maken.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Op sociale media zie ik vaak dat mensen boos worden als ze het over politiek hebben. Dat vind ik jammer, 
                                    want zo leren we niet van elkaar. Daarom heb ik PolitiekPraat gemaakt: een plek waar je rustig kunt praten 
                                    over politiek, zonder dat het uit de hand loopt. Hier mag je het oneens zijn met elkaar, als je maar respect 
                                    houdt voor elkaars mening.
                                </p>
                                <p class="text-gray-700 leading-relaxed">
                                    Met mijn kennis van computers en mijn interesse in politiek wil ik ervoor zorgen dat PolitiekPraat een fijne 
                                    plek wordt voor iedereen. Een plek waar je vragen kunt stellen, je mening kunt delen en kunt leren van anderen. 
                                    Want ik geloof dat we door naar elkaar te luisteren allemaal wijzer worden.
                                </p>
                            </div>
                            <div class="mt-6 flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    D66
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-secondary/10 text-secondary">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                    Developer
                                </span>
                                <a href="https://www.linkedin.com/in/naoufal-andichi" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-300">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                    LinkedIn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Missie & Visie -->
                <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 border border-primary/10 shadow-lg h-full transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:border-primary/30 group">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-8 h-8 transform transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Onze Missie</h2>
                            <div class="prose prose-lg">
                                <p class="text-gray-700 leading-relaxed mb-6">
                                    Stel je voor: een website waar je gewoon jezelf kunt zijn en kunt praten over politiek zonder gedoe. 
                                    Dat is precies wat PolitiekPraat wil zijn! Het maakt niet uit of je links of rechts bent, of je al 
                                    jaren met politiek bezig bent of er net mee begint. Bij ons is iedereen welkom om mee te doen en 
                                    zijn of haar verhaal te delen.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-6">
                                    Wij vinden het belangrijk dat jongeren ook hun stem laten horen in de politiek. Daarom hebben wij 
                                    PolitiekPraat zo gemaakt dat het leuk en makkelijk is om mee te doen. Je kunt hier je eigen blogs 
                                    schrijven, reageren op anderen en meedoen aan polls over actuele onderwerpen. Het is een beetje als 
                                    sociale media, maar dan speciaal voor mensen die geïnteresseerd zijn in politiek en Nederland.
                                </p>
                                <p class="text-gray-700 leading-relaxed mb-6">
                                    Heb jij een mening over het klimaat? Of wil je vertellen hoe jij denkt over onderwijs? 
                                    Schrijf er een blog over! Of misschien wil je juist weten wat anderen van een bepaald onderwerp vinden? 
                                    Start dan een discussie. Op PolitiekPraat kun je op een respectvolle manier met elkaar in gesprek gaan. 
                                    Wij zorgen ervoor dat het gezellig en leerzaam blijft.
                                </p>
                                <p class="text-gray-700 leading-relaxed">
                                    Doe mee en help ons om van PolitiekPraat dé plek te maken waar jongeren over politiek praten. 
                                    Plaats je eerste blog, deel je ideeën in de comments of doe mee aan onze wekelijkse polls. 
                                    Jouw stem is belangrijk en verdient het om gehoord te worden. Want politiek gaat over jouw 
                                    toekomst, dus praat mee!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> 