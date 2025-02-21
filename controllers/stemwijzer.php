<?php
require_once 'includes/config.php';
require_once 'views/templates/header.php';
?>

<main class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Stemwijzer 2025
                </span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Ontdek welke partij het beste bij jouw standpunten past
            </p>
        </div>

        <!-- Stemwijzer App -->
        <div class="max-w-3xl mx-auto" x-data="stemwijzer()">
            <!-- Progress Bar -->
            <div class="mb-8 bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-primary/80 
                                    flex items-center justify-center text-white font-medium shadow-md">
                            <span x-text="currentStep + 1"></span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            van <span x-text="totalSteps"></span> stellingen
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Nog ongeveer <span x-text="Math.ceil((totalSteps - currentStep) * 0.5)"></span> minuten</span>
                    </div>
                </div>

                <!-- Progress track -->
                <div class="relative h-3 bg-gray-100 rounded-full overflow-hidden">
                    <!-- Background pattern -->
                    <div class="absolute inset-0 bg-opacity-10"
                         style="background-image: repeating-linear-gradient(
                            45deg,
                            transparent,
                            transparent 10px,
                            rgba(0,0,0,0.05) 10px,
                            rgba(0,0,0,0.05) 20px
                         );">
                    </div>

                    <!-- Progress fill -->
                    <div class="h-full bg-gradient-to-r from-primary via-primary/90 to-secondary 
                                transition-all duration-300 ease-out relative"
                         :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                        <!-- Animated shine effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent
                                    -translate-x-full animate-[shine_2s_ease-in-out_infinite]">
                        </div>
                    </div>

                    <!-- Progress markers -->
                    <div class="absolute inset-0 flex items-center justify-between px-2">
                        <template x-for="index in totalSteps" :key="index">
                            <div class="w-1 h-1 rounded-full bg-white/50"
                                 :class="{ 'bg-white': currentStep >= index - 1 }">
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Labels -->
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>Start</span>
                    <span>Halverwege</span>
                    <span>Einde</span>
                </div>
            </div>

            <style>
            @keyframes shine {
                0% { transform: translateX(-100%); }
                50%, 100% { transform: translateX(100%); }
            }
            </style>

            <!-- Start Screen -->
            <div x-show="screen === 'start'" class="bg-white rounded-2xl shadow-lg p-8 relative overflow-hidden">
                <!-- Decoratieve achtergrond elementen -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-secondary/5 to-primary/5 rounded-full blur-2xl -z-10 transform -translate-x-1/2 translate-y-1/2"></div>

                <!-- Header met icon -->
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Welkom bij de Stemwijzer</h2>
                        <div class="flex items-center mt-1">
                            <span class="text-sm text-gray-500">Verkiezingen 2024</span>
                            <span class="mx-2 text-gray-300">•</span>
                            <span class="text-sm text-gray-500">±10 minuten</span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-6">
                    <p class="text-gray-600 leading-relaxed">
                        Deze stemwijzer helpt je om te ontdekken welke partij het beste bij jouw politieke voorkeuren past. 
                        Je krijgt een aantal stellingen te zien waarop je kunt aangeven in hoeverre je het ermee eens bent.
                    </p>

                    <!-- Features/voordelen -->
                    <div class="grid grid-cols-2 gap-4 py-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600">Gebaseerd op actuele partijstandpunten</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600">Volledig anoniem en privacy-vriendelijk</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600">Gedetailleerde resultaten</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600">Slechts 25 belangrijke stellingen</span>
                        </div>
                    </div>
                </div>

                <!-- Start button -->
                <button @click="startQuiz()" 
                        class="w-full mt-8 bg-gradient-to-r from-primary to-primary/90 text-white font-semibold 
                               py-4 px-6 rounded-xl shadow-lg shadow-primary/10
                               hover:shadow-xl hover:shadow-primary/20 
                               transform transition-all duration-300 hover:scale-[1.02]
                               focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2">
                    <div class="flex items-center justify-center space-x-2">
                        <span>Start de Stemwijzer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </button>
            </div>

            <!-- Questions Screen -->
            <div x-show="screen === 'questions'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="max-w-[1400px] mx-auto">

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Question & Answers -->
                    <div class="lg:col-span-7 bg-white rounded-xl shadow-lg p-6">
                        <!-- Question Header -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-medium text-gray-600">
                                    Vraag <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span>
                                </span>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <button @click="previousQuestion()" 
                                            x-show="currentStep > 0"
                                            class="flex items-center space-x-1 hover:text-gray-900 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="hidden sm:inline">Vorige</span>
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button @click="skipQuestion()"
                                            class="flex items-center space-x-1 hover:text-gray-900 transition-colors">
                                        <span class="hidden sm:inline">Overslaan</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3" x-text="questions[currentStep].title"></h2>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed" x-text="questions[currentStep].description"></p>
                        </div>

                        <!-- Answer Options -->
                        <div class="grid grid-cols-1 gap-3">
                            <button @click="answerQuestion('eens')"
                                    class="group relative bg-white border-2 border-primary rounded-lg p-4 transition-all duration-300
                                           hover:bg-primary hover:text-white hover:shadow-md hover:shadow-primary/10">
                                <div class="relative flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-md bg-primary/10 group-hover:bg-white/10 
                                                    flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-primary group-hover:text-white transition-colors" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <span class="text-base font-medium">Eens</span>
                                    </div>
                                </div>
                            </button>

                            <!-- Neutraal button (vergelijkbare aanpassingen) -->
                            <button @click="answerQuestion('neutraal')"
                                    class="group relative bg-white border-2 border-gray-200 rounded-lg p-4 transition-all duration-300
                                           hover:border-gray-300 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-md bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"/>
                                            </svg>
                                        </div>
                                        <span class="text-base font-medium text-gray-700">Neutraal</span>
                                    </div>
                                </div>
                            </button>

                            <!-- Oneens button (vergelijkbare aanpassingen) -->
                            <button @click="answerQuestion('oneens')"
                                    class="group relative bg-white border-2 border-secondary rounded-lg p-4 transition-all duration-300
                                           hover:bg-secondary hover:text-white hover:shadow-md hover:shadow-secondary/10">
                                <div class="relative flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-md bg-secondary/10 group-hover:bg-white/10 
                                                    flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-secondary group-hover:text-white transition-colors" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                        <span class="text-base font-medium">Oneens</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Party Positions -->
                    <div class="lg:col-span-5 sticky top-6">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <!-- Header -->
                            <div class="p-4 border-b border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900">Partijstandpunten</h3>
                            </div>

                            <!-- Tabs -->
                            <div x-data="{ activeTab: 'eens' }" class="overflow-x-auto">
                                <div class="flex border-b border-gray-100 min-w-full">
                                    <button @click="activeTab = 'eens'" 
                                            :class="{ 'border-primary text-primary': activeTab === 'eens',
                                                    'border-transparent text-gray-500': activeTab !== 'eens' }"
                                            class="flex-1 py-3 px-2 sm:px-4 text-center text-sm font-medium border-b-2 transition-all duration-200
                                                   hover:text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                            <span>Eens</span>
                                        </div>
                                    </button>
                                    <button @click="activeTab = 'neutraal'"
                                            :class="{ 'border-gray-900 text-gray-900': activeTab === 'neutraal',
                                                    'border-transparent text-gray-500': activeTab !== 'neutraal' }"
                                            class="flex-1 py-3 px-2 sm:px-4 text-center text-sm font-medium border-b-2 transition-all duration-200
                                                   hover:text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                            <span>Neutraal</span>
                                        </div>
                                    </button>
                                    <button @click="activeTab = 'oneens'"
                                            :class="{ 'border-secondary text-secondary': activeTab === 'oneens',
                                                    'border-transparent text-gray-500': activeTab !== 'oneens' }"
                                            class="flex-1 py-3 px-2 sm:px-4 text-center text-sm font-medium border-b-2 transition-all duration-200
                                                   hover:text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-secondary"></div>
                                            <span>Oneens</span>
                                        </div>
                                    </button>
                                </div>

                                <!-- Party List -->
                                <div class="overflow-y-auto max-h-[300px] sm:max-h-[500px]">
                                    <div class="divide-y divide-gray-100">
                                        <template x-for="(position, party) in questions[currentStep].positions" :key="party">
                                            <div x-show="position === activeTab"
                                                 class="p-4 hover:bg-gray-50 transition-colors">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-1" x-text="party"></h4>
                                                <p class="text-sm text-gray-600" x-text="questions[currentStep].explanations[party]"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Screen -->
            <div x-show="screen === 'results'" class="bg-white rounded-2xl shadow-lg p-4 sm:p-8">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Jouw Resultaten</h2>
                <p class="text-sm sm:text-base text-gray-600 mb-6">
                    Op basis van je antwoorden zijn dit de partijen die het beste bij je passen:
                </p>

                <div class="space-y-4">
                    <template x-for="(result, index) in results" :key="index">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-primary to-secondary 
                                                flex items-center justify-center text-white font-bold text-lg"
                                         x-text="result.party.substring(0, 2)"></div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900" x-text="result.party"></h3>
                                        <p class="text-sm text-gray-600">
                                            <span x-text="Math.round(result.match)"></span>% overeenkomst
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-24 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-gradient-to-r from-primary to-secondary rounded-full"
                                         :style="'width: ' + result.match + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button @click="restartQuiz()"
                        class="w-full mt-8 bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 px-6 
                               rounded-xl hover:opacity-90 transition-all transform hover:scale-105">
                    Opnieuw beginnen
                </button>
            </div>
        </div>
    </div>
</main>

<script>
function stemwijzer() {
    return {
        screen: 'start',
        currentStep: 0,
        totalSteps: 25,
        questions: [
            {
                title: "Asielbeleid",
                description: "Nederland moet een strenger asielbeleid voeren met een asielstop en lagere immigratiecijfers.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'eens',
                    'NSC': 'eens',
                    'BBB': 'eens',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'neutraal',
                    'PvdD': 'oneens',
                    'CDA': 'neutraal',
                    'JA21': 'eens',
                    'SGP': 'eens',
                    'FvD': 'eens',
                    'DENK': 'oneens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "Deze partij steunt een strenger asielbeleid met een volledige asielstop en minimale opvang. Ze vinden dat Nederland hierdoor beter controle houdt over migratie.",
                    'VVD': "Zij pleiten voor een strengere selectie en beperking van asielaanvragen, maar met een duidelijke rol voor internationale samenwerking. Hun standpunt benadrukt efficiëntie en veiligheid.",
                    'NSC': "Neutraal ingesteld benadrukken zij dat een doordacht asielbeleid zowel veiligheid als humanitaire zorg moet waarborgen. Ze zien kansen in samenwerking met Europese partners.",
                    'BBB': "Deze partij ondersteunt een streng asielbeleid en wil de instroom beperken door regionale opvang te stimuleren. Ze benadrukken daarbij het behoud van landelijke belangen.",
                    'GL-PvdA': "Zij verzetten zich tegen een asielstop en vinden dat humanitaire principes centraal moeten staan. Hun visie is gericht op solidariteit en een eerlijke verdeling binnen de EU.",
                    'D66': "D66 pleit voor een humaan maar gestructureerd asielbeleid met nadruk op veilige en legale routes. Zij vinden dat Nederland zijn verantwoordelijkheden binnen de EU moet nakomen.",
                    'SP': "De SP zet in op verbetering van de opvang en integratie van asielzoekers. Zij benadrukken dat het aanpakken van migratieoorzaken even belangrijk is als het beperken van instroom.",
                    'PvdD': "Deze partij wil een asielbeleid dat volledig mensenrechten respecteert en de ecologische context niet uit het oog verliest. Hun standpunt benadrukt zorg en duurzaamheid.",
                    'CDA': "Het CDA pleit voor een onderscheidend beleid waarin tijdelijke en permanente bescherming duidelijk wordt gescheiden. Zij vinden dat zowel nationale als internationale belangen beschermd moeten worden.",
                    'JA21': "JA21 ondersteunt een restrictief asielbeleid met nadruk op regionale opvang en strikte toelatingscriteria. Zij zijn voorstander van een beleid dat de Nederlandse samenleving beschermt.",
                    'SGP': "De SGP wil een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan. Ze zijn van mening dat opvang primair regionaal geregeld moet worden.",
                    'FvD': "FvD pleit voor het beëindigen van het huidige internationale asielkader en wil asielaanvragen in Nederland sterk beperken. Hun visie draait om nationale soevereiniteit en veiligheid.",
                    'DENK': "DENK kiest voor een humaan asielbeleid dat ook aandacht heeft voor solidariteit en internationale samenwerking. Zij benadrukken een eerlijke verdeling van asielzoekers binnen Europa.",
                    'Volt': "Volt staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert. Zij vinden dat een uniforme aanpak de beste bescherming biedt voor asielzoekers."
                }
            },
            {
                title: "Klimaatmaatregelen",
                description: "Nederland moet vooroplopen in de klimaattransitie, ook als dit op korte termijn economische groei kost.",
                positions: {
                    'PVV': 'oneens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'oneens',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'neutraal',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'neutraal',
                    'FvD': 'oneens',
                    'DENK': 'neutraal',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV verzet zich tegen ambitieuze klimaatmaatregelen als ze ten koste gaan van economische groei. Zij benadrukken dat prioriteit moet liggen bij het behoud van werkgelegenheid en welvaart.",
                    'VVD': "De VVD ondersteunt klimaatmaatregelen, maar vindt dat economische groei niet op de achtergrond mag raken. Zij pleiten voor een realistische transitie met behoud van concurrentiekracht.",
                    'NSC': "NSC neemt een neutrale positie in en benadrukt dat zowel het klimaat als de economie belangrijk zijn. Zij vinden dat er een evenwichtige aanpak moet komen.",
                    'BBB': "BBB staat sceptisch tegenover ingrijpende klimaatmaatregelen, zeker als deze de agrarische sector schaden. Zij pleiten voor praktische oplossingen die lokaal haalbaar zijn.",
                    'GL-PvdA': "GL-PvdA is voorstander van ambitieuze klimaatmaatregelen en ziet economische offers op korte termijn als noodzakelijk kwaad. Zij vinden dat de transitie ook eerlijk moet verlopen.",
                    'D66': "D66 pleit voor een leidende rol in de klimaattransitie en is bereid om economische concessies te accepteren. Zij zien innovatie en duurzaamheid als motoren voor toekomstige groei.",
                    'SP': "SP benadrukt dat klimaatmaatregelen sociale rechtvaardigheid moeten garanderen en de lasten eerlijk verdeeld moeten worden. Zij vinden dat zowel ecologische als economische belangen meegenomen moeten worden.",
                    'PvdD': "PvdD staat voor radicale en verregaande klimaatmaatregelen, ongeacht korte termijn economische nadelen. Zij zien de urgentie van de klimaatcrisis als prioriteit.",
                    'CDA': "Het CDA kiest voor een gebalanceerde aanpak waarin klimaatmaatregelen worden gecombineerd met behoud van economische stabiliteit. Zij pleiten voor realistische en haalbare doelen.",
                    'JA21': "JA21 verzet zich tegen klimaatmaatregelen die economische groei zouden belemmeren en vindt dat de kosten en baten goed afgewogen moeten worden. Zij benadrukken het belang van economische zekerheid.",
                    'SGP': "De SGP vindt dat klimaatmaatregelen verantwoord moeten zijn en de economische draagkracht niet overschrijden. Zij pleiten voor maatregelen die in lijn zijn met traditionele waarden en werkgelegenheid.",
                    'FvD': "FvD betwist de urgentie van de klimaatcrisis en verzet zich tegen maatregelen die de economie negatief beïnvloeden. Zij benadrukken dat er andere prioriteiten zijn voor de Nederlandse samenleving.",
                    'DENK': "DENK kiest voor een genuanceerde aanpak waarbij zowel klimaat als economische belangen worden meegewogen. Zij zien kansen in duurzame investeringen als motor voor groei.",
                    'Volt': "Volt pleit voor ambitieuze klimaatmaatregelen en gelooft dat de transitie de basis kan vormen voor een duurzame economie. Zij vinden dat de korte termijn offers opwegen tegen de lange termijn voordelen."
                }
            },
            {
                title: "Eigen Risico Zorg",
                description: "Het eigen risico in de zorg moet worden afgeschaft.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'eens',
                    'GL-PvdA': 'eens',
                    'D66': 'oneens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'neutraal',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'neutraal'
                },
                explanations: {
                    'PVV': "De PVV wil het eigen risico in de zorg volledig afschaffen zodat iedereen zonder financiële drempel toegang heeft tot zorg. Ze benadrukken dat dit de toegankelijkheid en solidariteit versterkt.",
                    'VVD': "VVD behoudt bij voorkeur het huidige systeem met een eigen risico, omdat dit volgens hen preventief werkt en kosten beheersbaar houdt. Zij vinden dat individuele verantwoordelijkheid belangrijk is.",
                    'NSC': "NSC neemt een neutrale positie in en overweegt aanpassingen per behandeling om het zorgsysteem betaalbaar te houden. Zij zoeken naar een balans tussen toegankelijkheid en kostenbeheersing.",
                    'BBB': "BBB is voorstander van een sterke verlaging van het eigen risico, met als doel de zorgtoegankelijkheid te vergroten. Zij pleiten voor stapsgewijze afschaffing zonder de zorgfinanciering te ondermijnen.",
                    'GL-PvdA': "GL-PvdA wil het eigen risico volledig afschaffen en streeft naar een zorgsysteem waarin iedereen gelijke toegang heeft. Zij zien dit als een stap richting echte solidariteit in de zorg.",
                    'D66': "D66 stelt voor om het eigen risico te bevriezen en tegelijkertijd een limiet per behandeling in te stellen, zodat zorg betaalbaar blijft. Zij zoeken een middenweg die recht doet aan zowel preventie als toegankelijkheid.",
                    'SP': "SP pleit voor volledige afschaffing van het eigen risico en wil een nationaal zorgfonds instellen. Zij benadrukken dat zorg een recht is en niet afhankelijk mag zijn van ieders financiële situatie.",
                    'PvdD': "PvdD streeft naar een zorgsysteem zonder financiële drempels, waarbij het eigen risico wordt afgeschaft. Zij vinden dat dit leidt tot een meer mensgerichte zorgverlening.",
                    'CDA': "Het CDA neemt een afgewogen standpunt in en wil niet te drastisch veranderen, maar wel het eigen risico verlagen waar mogelijk. Zij vinden dat betaalbaarheid en verantwoordelijkheid hand in hand gaan.",
                    'JA21': "JA21 is tegen het volledig afschaffen van het eigen risico omdat zij vinden dat een zekere mate van eigen bijdrage noodzakelijk is voor efficiëntie. Zij pleiten voor behoud met eventuele gerichte verlagingen.",
                    'SGP': "De SGP kiest voor een behoudende aanpak en ziet het eigen risico als een middel om onnodig gebruik van zorg tegen te gaan. Zij vinden echter dat er ruimte moet zijn voor verlaging bij kwetsbare groepen.",
                    'FvD': "FvD ondersteunt het afschaffen van het eigen risico en ziet dit als een stap richting een toegankelijke en betaalbare zorg voor iedereen. Zij benadrukken dat een overheidsgestuurd systeem beter kan inspelen op de behoeften van de burger.",
                    'DENK': "DENK is voorstander van het afschaffen of aanzienlijk verlagen van het eigen risico om zorg voor iedereen bereikbaar te maken. Zij benadrukken dat dit een kwestie van rechtvaardigheid en solidariteit is.",
                    'Volt': "Volt kiest voor een neutrale aanpak en staat open voor het verlagen van het eigen risico mits dit financieel houdbaar blijft. Zij vinden dat de zorg toegankelijk moet zijn zonder onnodige financiële barrières."
                }
            },
            {
                title: "Kernenergie",
                description: "Nederland moet investeren in nieuwe kerncentrales als onderdeel van de energietransitie.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'eens',
                    'NSC': 'eens',
                    'BBB': 'eens',
                    'GL-PvdA': 'oneens',
                    'D66': 'neutraal',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'eens',
                    'JA21': 'eens',
                    'SGP': 'eens',
                    'FvD': 'eens',
                    'DENK': 'neutraal',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV steunt de investering in nieuwe kerncentrales als onderdeel van een veilige en onafhankelijke energievoorziening. Zij zien kernenergie als een noodzakelijke stap om de energietransitie te versnellen.",
                    'VVD': "VVD is voorstander van kernenergie als aanvulling op duurzame energiebronnen, mits veiligheid en kosten beheersbaar blijven. Zij benadrukken dat diversificatie van energiebronnen cruciaal is voor energiezekerheid.",
                    'NSC': "NSC neemt een neutrale positie in en staat open voor investeringen in kernenergie als dit bijdraagt aan een stabiele energiemix. Zij willen dat er transparante veiligheidsnormen worden gehanteerd.",
                    'BBB': "BBB ondersteunt kernenergie als een betrouwbare en efficiënte energiebron voor de toekomst. Zij vinden dat investeringen in kerncentrales een belangrijk onderdeel zijn van de energietransitie, mits het milieu gewaarborgd blijft.",
                    'GL-PvdA': "GL-PvdA verwerpt kernenergie vanwege de gepercipieerde risico's en de lange doorlooptijd van projecten. Zij pleiten in plaats daarvan voor investeringen in duurzame en hernieuwbare energiebronnen.",
                    'D66': "D66 kiest voor een neutrale benadering waarbij kernenergie niet uitgesloten wordt, maar wel kritisch wordt bekeken op basis van risico's en kosten. Zij vinden dat innovatie en veiligheid centraal moeten staan in de energietransitie.",
                    'SP': "SP is tegen investeringen in nieuwe kerncentrales en benadrukt dat publieke middelen beter kunnen worden besteed aan duurzame energie. Zij wijzen op de lange doorlooptijden en risico's van kernenergie.",
                    'PvdD': "PvdD verzet zich tegen kernenergie en ziet dit als een verouderde technologie met milieu- en veiligheidsrisico's. Zij pleiten voor een versnelling van investeringen in puur hernieuwbare energiebronnen.",
                    'CDA': "Het CDA staat voor een pragmatische aanpak en overweegt kernenergie als onderdeel van een brede energiemix. Zij vinden dat kernenergie kan bijdragen aan energieonafhankelijkheid mits strikt gereguleerd.",
                    'JA21': "JA21 ondersteunt kernenergie als een essentieel onderdeel van een betrouwbare energievoorziening. Zij vinden dat kerncentrales kunnen bijdragen aan zowel energiezekerheid als emissiereductie.",
                    'SGP': "De SGP ondersteunt kernenergie en ziet dit als een manier om de afhankelijkheid van fossiele brandstoffen te verminderen. Zij vinden dat veiligheid en morele verantwoordelijkheid gewaarborgd moeten blijven.",
                    'FvD': "FvD is voorstander van kernenergie en pleit voor de ontwikkeling van nieuwe kerncentrales als alternatief voor fossiele brandstoffen. Zij benadrukken dat dit essentieel is voor een toekomstbestendige energievoorziening.",
                    'DENK': "DENK neemt een neutrale positie in en staat open voor kernenergie mits dit veilig en verantwoord gebeurt. Zij vinden dat een brede energiemix nodig is voor een stabiele toekomst.",
                    'Volt': "Volt steunt kernenergie niet standaard, maar staat open voor de optie indien aan strenge veiligheidseisen wordt voldaan. Zij geven de voorkeur aan een versnelling van hernieuwbare energie, met kernenergie als mogelijke aanvullende bron."
                }
            },
            {
                title: "Woningmarkt",
                description: "Er moet een nationaal bouwprogramma komen waarbij de overheid zelf woningen gaat bouwen.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'neutraal',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'neutraal',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV is voorstander van een nationaal bouwprogramma waarin de overheid zelf woningen bouwt om zo de woningnood tegen te gaan. Zij vinden dat dit de betaalbaarheid en controle over de woningmarkt vergroot.",
                    'VVD': "VVD verzet zich tegen directe overheidsinmenging in de woningbouw en pleit voor samenwerking met de private sector. Zij geloven dat marktwerking innovatie en efficiëntie stimuleert.",
                    'NSC': "NSC neemt een neutrale positie in en benadrukt dat zowel publieke als private initiatieven nodig zijn om de woningnood aan te pakken. Zij vinden dat de overheid vooral faciliterend moet optreden.",
                    'BBB': "BBB ziet kansen in een overheidsprogramma voor woningbouw, zeker in landelijke gebieden. Zij vinden dat de overheid een actieve rol moet spelen in het creëren van betaalbare woningen.",
                    'GL-PvdA': "GL-PvdA pleit voor een sterke overheidsrol in de woningmarkt om sociale gelijkheid en betaalbaarheid te garanderen. Zij vinden dat de overheid moet ingrijpen om de winstoogmerkgedreven aanpak van de markt tegen te gaan.",
                    'D66': "D66 kiest voor een gemengde aanpak, waarbij de overheid samenwerkt met private partijen om voldoende woningen te bouwen. Zij vinden dat innovatie en duurzaamheid hierin centraal moeten staan.",
                    'SP': "SP ondersteunt overheidsinitiatieven in de woningbouw en wil dat sociale huurwoningen prioriteit krijgen. Zij vinden dat het recht op huisvesting voor iedereen gegarandeerd moet worden.",
                    'PvdD': "PvdD is voorstander van een sterk overheidsprogramma om de woningnood structureel aan te pakken. Zij vinden dat de overheid meer moet investeren in duurzame en betaalbare woningen.",
                    'CDA': "Het CDA pleit voor een gerichte overheidsrol in de woningbouw, vooral voor kwetsbare groepen. Zij vinden dat samenwerking met private partijen de efficiëntie kan vergroten.",
                    'JA21': "JA21 is tegen een grootschalig overheidsprogramma voor woningbouw en verkiest marktgedreven oplossingen. Zij vinden dat subsidieregelingen en regelgeving voldoende zijn om de woningmarkt te stimuleren.",
                    'SGP': "De SGP steunt een aanpak waarbij woningcorporaties voorrang krijgen bij nieuwbouw, om zo sociale stabiliteit te waarborgen. Zij vinden dat dit de solidariteit in de samenleving versterkt.",
                    'FvD': "FvD neemt een neutrale tot terughoudende positie in over overheidsoplossingen en benadrukt de kracht van private initiatieven. Zij vinden dat regelgeving moet worden versoepeld voor een soepelere marktwerking.",
                    'DENK': "DENK is voorstander van een actieve overheidsrol bij het bouwen van betaalbare woningen voor iedereen. Zij vinden dat dit helpt om de maatschappelijke kloof te verkleinen.",
                    'Volt': "Volt pleit voor innovatieve en duurzame oplossingen in de woningbouw, met een duidelijke rol voor zowel overheid als marktpartijen. Zij zien samenwerking als de sleutel tot een toekomstbestendige woningmarkt."
                }
            },
            {
                title: "Minimumloon",
                description: "Het minimumloon moet verder omhoog naar 16 euro per uur.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'neutraal',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'oneens',
                    'DENK': 'eens',
                    'Volt': 'neutraal'
                },
                explanations: {
                    'PVV': "De PVV neemt een neutrale positie in over het verhogen van het minimumloon en stelt dat economische realiteiten hierbij wel meegewogen moeten worden. Zij vinden dat het minimumloon een balans moet vinden tussen leefbaarheid en werkgelegenheid.",
                    'VVD': "VVD is tegen een verhoging van het minimumloon naar 16 euro per uur, omdat dit de werkgelegenheid kan schaden. Zij benadrukken dat economische flexibiliteit belangrijk is voor bedrijven.",
                    'NSC': "NSC is kritisch en vindt dat een te hoge verhoging de concurrentiekracht van bedrijven kan verminderen. Zij pleiten voor een stapsgewijze benadering, rekening houdend met de economische situatie.",
                    'BBB': "BBB houdt een neutrale positie aan en benadrukt dat het minimumloon in lijn moet zijn met economische realiteiten. Zij vinden dat zowel werknemers als werkgevers beschermd moeten worden.",
                    'GL-PvdA': "GL-PvdA steunt een verhoging van het minimumloon naar 16 euro per uur om een eerlijk loon te garanderen. Zij zien dit als een stap richting sociale rechtvaardigheid en gelijkheid.",
                    'D66': "D66 kiest voor een neutrale aanpak en vindt dat het minimumloon moet worden verhoogd als de economische omstandigheden dat toelaten. Zij pleiten voor een evenwichtige aanpak die zowel werknemers als de economie ten goede komt.",
                    'SP': "SP is voorstander van een verhoging naar 16 euro per uur en ziet dit als noodzakelijk voor een leefbaar loon. Zij vinden dat een hoger minimumloon de koopkracht en sociale gelijkheid versterkt.",
                    'PvdD': "PvdD ondersteunt een verhoging omdat zij geloven in een samenleving waarin iedereen een eerlijk inkomen verdient. Zij benadrukken dat een hoger minimumloon bijdraagt aan een duurzamere economie.",
                    'CDA': "Het CDA neemt een neutrale positie in en stelt dat een verhoging alleen verantwoord is als deze gekoppeld is aan productiviteitswinsten. Zij vinden dat zowel werkgevers als werknemers hierbij moeten meebetalen.",
                    'JA21': "JA21 is tegen een verhoging naar 16 euro per uur, omdat zij vrezen dat dit leidt tot banenverlies en hogere kosten voor bedrijven. Zij pleiten voor behoud van het huidige niveau met gerichte ondersteuning voor kwetsbare groepen.",
                    'SGP': "De SGP verzet zich tegen een forse verhoging van het minimumloon en benadrukt de noodzaak van een marktconforme aanpak. Zij vinden dat economische draagkracht en werkgelegenheid beschermd moeten worden.",
                    'FvD': "FvD is tegen een verhoging, omdat zij vrezen dat dit de kosten voor werkgevers onnodig verhoogt. Zij benadrukken dat economische groei en concurrentievermogen eerst op de agenda staan.",
                    'DENK': "DENK ondersteunt een verhoging naar 16 euro per uur om inkomensongelijkheid tegen te gaan. Zij vinden dat een eerlijk loon fundamenteel is voor sociale cohesie.",
                    'Volt': "Volt kiest voor een neutrale maar progressieve aanpak en steunt een verhoging mits dit gepaard gaat met structurele investeringen in de economie. Zij zien dit als een stap richting een inclusieve samenleving."
                }
            },
            {
                title: "Europese Unie",
                description: "Nederland moet uit de Europese Unie stappen (Nexit).",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'oneens',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'oneens',
                    'JA21': 'neutraal',
                    'SGP': 'oneens',
                    'FvD': 'eens',
                    'DENK': 'oneens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "De PVV is voorstander van een vertrek uit de EU (Nexit) en wil zo de nationale soevereiniteit herstellen. Zij vinden dat Nederland meer grip krijgt op eigen beleid buiten de EU-kaders.",
                    'VVD': "VVD is tegen een Nexit en gelooft dat samenwerking binnen de EU essentieel is voor economische en veiligheidsbelangen. Zij benadrukken dat Europese samenwerking meer voordelen dan nadelen biedt.",
                    'NSC': "NSC is kritisch over een eenzijdig vertrek en ziet zowel kansen als risico's in het EU-lidmaatschap. Zij vinden dat Nederland goed kan profiteren van de Europese interne markt.",
                    'BBB': "BBB is overwegend tegen Nexit en pleit voor een meer pragmatische samenwerking binnen de EU. Zij vinden dat de voordelen van een gezamenlijke markt en veiligheidsoverwegingen zwaarder wegen.",
                    'GL-PvdA': "GL-PvdA verzet zich krachtig tegen een vertrek uit de EU en ziet de unie als cruciaal voor solidariteit en mensenrechten. Zij vinden dat Europese samenwerking onmisbaar is voor een sterke internationale positie.",
                    'D66': "D66 is fel tegen Nexit en pleit voor verdieping van de Europese samenwerking. Zij vinden dat gezamenlijke oplossingen op het gebied van klimaat, veiligheid en economie de toekomst zijn.",
                    'SP': "SP is tegen het verlaten van de EU omdat zij geloven dat internationale solidariteit noodzakelijk is om sociale en economische uitdagingen aan te pakken. Zij vinden dat samenwerking de weg vooruit is.",
                    'PvdD': "PvdD verzet zich tegen Nexit en benadrukt dat milieubescherming en duurzaamheid effectiever zijn binnen een gezamenlijke Europese aanpak. Zij vinden dat de EU kansen biedt voor een groene transitie.",
                    'CDA': "Het CDA is voorstander van het blijven in de EU, mits er meer aandacht is voor nationale belangen binnen de unie. Zij vinden dat samenwerking met Europese partners belangrijk is voor stabiliteit en welvaart.",
                    'JA21': "JA21 neemt een neutrale tot kritische positie in en vindt dat de EU hervormd moet worden, maar niet per se verlaten. Zij benadrukken dat nationale belangen beter beschermd worden met hervorming dan met vertrek.",
                    'SGP': "De SGP is tegen Nexit en pleit voor een EU die werkt volgens christelijke waarden en normen. Zij vinden dat samenwerking binnen Europa bijdraagt aan vrede en veiligheid.",
                    'FvD': "FvD steunt een Nexit en wil dat Nederland zich ontdoet van wat zij zien als bureaucratische beperkingen van de EU. Zij benadrukken nationale soevereiniteit en onafhankelijkheid.",
                    'DENK': "DENK is tegen een vertrek uit de EU en vindt dat samenwerking essentieel is om internationale uitdagingen gezamenlijk aan te pakken. Zij pleiten voor een meer inclusief en democratisch Europa.",
                    'Volt': "Volt verwerpt een Nexit en staat voor een versterkte Europese integratie op basis van solidariteit en innovatie. Zij zien de EU als een platform voor gezamenlijke vooruitgang."
                }
            },
            {
                title: "Defensie-uitgaven",
                description: "Nederland moet de defensie-uitgaven verhogen naar minimaal 2% van het BBP.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'eens',
                    'NSC': 'eens',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'neutraal',
                    'D66': 'eens',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'eens',
                    'JA21': 'eens',
                    'SGP': 'eens',
                    'FvD': 'oneens',
                    'DENK': 'oneens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in en vindt dat de focus bij defensie ligt op efficiëntie in plaats van een forse budgetverhoging. Zij benadrukken dat veiligheid op andere wijze ook bereikt kan worden.",
                    'VVD': "VVD steunt een verhoging van de defensie-uitgaven naar minimaal 2% van het BBP om de internationale positie en veiligheid te waarborgen. Zij vinden dat een sterke defensie onmisbaar is in een onzekere wereld.",
                    'NSC': "NSC is voorstander van een budgetverhoging, maar ziet dit als onderdeel van een bredere herwaardering van nationale veiligheid. Zij pleiten voor een efficiënte inzet van middelen.",
                    'BBB': "BBB kiest voor een neutrale benadering en vindt dat defensie-uitgaven in lijn moeten zijn met de daadwerkelijke dreigingen. Zij benadrukken dat kosten en baten zorgvuldig afgewogen moeten worden.",
                    'GL-PvdA': "GL-PvdA houdt een neutrale positie aan en ziet geen reden voor een forse verhoging als dit ten koste gaat van sociale uitgaven. Zij pleiten voor een evenwicht tussen veiligheid en welzijn.",
                    'D66': "D66 is voorstander van het verhogen van de defensie-uitgaven om beter voorbereid te zijn op internationale crises. Zij zien dit als een investering in een sterk en betrouwbaar bondgenootschap.",
                    'SP': "SP is tegen een verhoging en vindt dat geld beter besteed kan worden aan sociale programma's en welzijn. Zij benadrukken dat veiligheid ook begint bij een sterke samenleving.",
                    'PvdD': "PvdD verzet zich tegen verdere verhoging en pleit voor een kritische evaluatie van de defensie-uitgaven. Zij vinden dat transparantie en efficiëntie voorop moeten staan.",
                    'CDA': "Het CDA steunt een verhoging van defensie-uitgaven, mits dit gepaard gaat met concrete investeringen in moderne technologie. Zij zien dit als essentieel voor zowel nationale als internationale veiligheid.",
                    'JA21': "JA21 is voorstander van een verhoging en vindt dat Nederland zijn verantwoordelijkheid in internationale veiligheid moet waarmaken. Zij pleiten voor een scherp en efficiënt defensiebeleid.",
                    'SGP': "De SGP ondersteunt een verhoging van defensie-uitgaven als onderdeel van een brede strategie voor nationale veiligheid. Zij vinden dat de bescherming van burgers prioriteit heeft.",
                    'FvD': "FvD verzet zich tegen verdere verhoging omdat zij vrezen dat dit leidt tot onnodige militaire betrokkenheid. Zij benadrukken dat defensie efficiënt en doelgericht moet opereren.",
                    'DENK': "DENK kiest voor een kritische maar genuanceerde benadering en vindt dat defensie-uitgaven in balans moeten zijn met maatschappelijke behoeften. Zij pleiten voor transparantie in de besteding van middelen.",
                    'Volt': "Volt steunt een verhoging van de defensie-uitgaven om beter voorbereid te zijn op internationale uitdagingen. Zij vinden dat een sterke defensie bijdraagt aan een stabiele Europese samenwerking."
                }
            },
            {
                title: "Stikstofbeleid",
                description: "Het huidige stikstofbeleid moet worden versoepeld om boeren meer ruimte te geven.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'eens',
                    'NSC': 'eens',
                    'BBB': 'eens',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'neutraal',
                    'PvdD': 'oneens',
                    'CDA': 'neutraal',
                    'JA21': 'eens',
                    'SGP': 'eens',
                    'FvD': 'eens',
                    'DENK': 'neutraal',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "De PVV steunt versoepeling van het stikstofbeleid om boeren meer ruimte te geven. Zij vinden dat economische belangen van de agrarische sector prioriteit hebben.",
                    'VVD': "VVD is voorstander van versoepeling, mits dit gepaard gaat met maatregelen om de natuur te beschermen. Zij vinden dat boeren een eerlijke kans moeten krijgen zonder onnodige bureaucratie.",
                    'NSC': "NSC neemt een neutrale positie in en ziet zowel de belangen van boeren als de noodzaak van natuurbehoud als belangrijk. Zij pleiten voor een gebalanceerde aanpak.",
                    'BBB': "BBB ondersteunt versoepeling van het stikstofbeleid, omdat zij vinden dat de huidige regels te rigide zijn. Zij benadrukken dat boeren economische kansen niet mogen verliezen.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen versoepeling omdat zij vinden dat natuur en klimaatbescherming essentieel zijn, ook voor toekomstige generaties. Zij pleiten voor innovatieve oplossingen voor duurzame landbouw.",
                    'D66': "D66 is tegen versoepeling en wil juist inzetten op technologische innovaties in de landbouw om zowel natuur als economie te beschermen. Zij vinden dat ambitieuze klimaatdoelen niet mogen wijken voor economische druk.",
                    'SP': "SP neemt een neutrale positie in en pleit voor een beleid dat zowel de agrarische sector ondersteunt als de natuur beschermt. Zij vinden dat sociale en ecologische belangen hand in hand moeten gaan.",
                    'PvdD': "PvdD is tegen versoepeling en ziet de urgentie van het stikstofprobleem als een symptoom van een ongezond economisch model. Zij pleiten voor een duurzame herinrichting van de landbouwsector.",
                    'CDA': "CDA kiest voor een afgewogen aanpak en stelt dat versoepeling mogelijk is als dit leidt tot duurzame en innovatieve oplossingen. Zij vinden dat boeren ondersteund moeten worden in deze transitie.",
                    'JA21': "JA21 steunt versoepeling en vindt dat boeren meer ruimte moeten krijgen om economisch te kunnen floreren. Zij pleiten voor minder overheidsbemoeienis in de landbouwsector.",
                    'SGP': "De SGP ondersteunt versoepeling van het stikstofbeleid als dit de leefbaarheid in landelijke gebieden bevordert. Zij vinden dat economische belangen en behoud van tradities hand in hand moeten gaan.",
                    'FvD': "FvD pleit voor versoepeling en wijst op de noodzaak om de agrarische sector te beschermen tegen te strenge milieuregels. Zij vinden dat natuurbeleid niet ten koste mag gaan van boerenlevensonderhoud.",
                    'DENK': "DENK neemt een neutrale positie in en benadrukt dat de belangen van boeren en natuur beide gehoord moeten worden. Zij pleiten voor een inclusieve dialoog over duurzame landbouw.",
                    'Volt': "Volt is tegen versoepeling en wil juist inzetten op een integrale aanpak waarin natuurherstel en innovatie in de landbouw centraal staan. Zij vinden dat een duurzame transitie de enige weg vooruit is."
                }
            },
            {
                title: "Studiefinanciering",
                description: "De basisbeurs voor studenten moet worden verhoogd.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'eens',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'neutraal',
                    'SGP': 'neutraal',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV ondersteunt een verhoging van de basisbeurs om zo studenten meer financiële ruimte te geven. Zij vinden dat dit de toegankelijkheid van hoger onderwijs bevordert.",
                    'VVD': "VVD verzet zich tegen een verhoging omdat zij vrezen dat dit de studiekosten onnodig opdrijft. Zij pleiten voor een systeem dat meer leunt op prestatie en verantwoordelijkheid.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat de studiefinanciering in balans moet zijn met maatschappelijke en economische realiteiten. Zij zien ruimte voor zowel verhoging als efficiëntieverbetering.",
                    'BBB': "BBB ondersteunt een verhoging van de basisbeurs als middel om studiekosten voor jongeren te verlagen. Zij vinden dat onderwijs toegankelijk moet zijn voor iedereen, zeker in landelijke gebieden.",
                    'GL-PvdA': "GL-PvdA pleit voor een flinke verhoging van de basisbeurs om ongelijkheden in het onderwijs tegen te gaan. Zij zien dit als een investering in de toekomst van jongeren.",
                    'D66': "D66 steunt een verhoging van de studiefinanciering en wil dat dit gepaard gaat met modernisering van het onderwijssysteem. Zij vinden dat investeren in jongeren de sleutel is tot innovatie en vooruitgang.",
                    'SP': "SP is voorstander van een verhoogde basisbeurs om studenten financieel te ontlasten en kansen te egaliseren. Zij vinden dat onderwijs een recht is en niet afhankelijk mag zijn van financiële mogelijkheden.",
                    'PvdD': "PvdD pleit voor een verhoging van de studiefinanciering zodat studenten meer focus kunnen hebben op hun opleiding. Zij zien dit als een stap richting een eerlijker onderwijssysteem.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat studiefinanciering moet worden aangepast aan de veranderende economische realiteit. Zij pleiten voor een systeem dat zowel rechtvaardig als duurzaam is.",
                    'JA21': "JA21 is voorzichtig met een verhoging en vreest dat dit onnodige kosten met zich meebrengt voor de samenleving. Zij pleiten voor efficiëntie in het huidige systeem met gerichte ondersteuning voor kwetsbare groepen.",
                    'SGP': "De SGP ondersteunt een verhoging, maar alleen als dit leidt tot een structurele verbetering van het onderwijssysteem. Zij vinden dat een eerlijk toegankelijke studiefinanciering essentieel is voor maatschappelijke stabiliteit.",
                    'FvD': "FvD is voorstander van een verhoging omdat zij geloven dat een hoger budget jongeren meer kansen biedt. Zij vinden echter dat dit gepaard moet gaan met strengere criteria en transparantie.",
                    'DENK': "DENK pleit voor een hogere basisbeurs om economische ongelijkheden in het onderwijs te verkleinen. Zij vinden dat investeren in onderwijs de samenleving als geheel vooruit helpt.",
                    'Volt': "Volt ondersteunt een verhoging van de basisbeurs als onderdeel van een bredere investering in jongeren en innovatie. Zij vinden dat toegankelijkheid en gelijke kansen centraal moeten staan."
                }
            },
            {
                title: "Belastingen",
                description: "De belastingen voor grote bedrijven moeten omhoog.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'neutraal',
                    'FvD': 'oneens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV is voorstander van hogere belastingen voor grote bedrijven om zo de overheidsfinanciën te versterken. Zij vinden dat grote bedrijven meer moeten bijdragen aan de samenleving.",
                    'VVD': "VVD verzet zich tegen een verhoging omdat zij vrezen dat dit de economische groei en innovatie belemmert. Zij pleiten voor een belastingbeleid dat concurrentie en ondernemerschap stimuleert.",
                    'NSC': "NSC neemt een neutrale positie in en ziet dat een verhoging zowel voordelen als nadelen kent voor de economie. Zij pleiten voor een weloverwogen beleid dat rekening houdt met internationale concurrentie.",
                    'BBB': "BBB houdt een neutrale positie aan en vindt dat grote bedrijven wel een extra bijdrage moeten leveren, maar dat een te hoge belastingdruk schadelijk kan zijn. Zij benadrukken de noodzaak van een evenwichtig belastingstelsel.",
                    'GL-PvdA': "GL-PvdA steunt hogere belastingen voor grote bedrijven als middel om sociale ongelijkheid tegen te gaan. Zij zien dit als een investering in publieke voorzieningen en sociale rechtvaardigheid.",
                    'D66': "D66 is voorstander van een verhoging mits de extra opbrengsten worden geïnvesteerd in innovatie en duurzaamheid. Zij vinden dat bedrijven niet te veel voordeel mogen trekken uit de maatschappij zonder terug te geven.",
                    'SP': "SP pleit voor hogere belastingen voor grote bedrijven om inkomensongelijkheid te verkleinen. Zij vinden dat de winsten van multinationals eerlijker verdeeld moeten worden over de samenleving.",
                    'PvdD': "PvdD ondersteunt een verhoging omdat zij vinden dat grote bedrijven een grotere maatschappelijke verantwoordelijkheid hebben. Zij benadrukken dat een eerlijke bijdrage bijdraagt aan een duurzame samenleving.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat een verhoging zorgvuldig moet worden afgewogen tegen de risico's voor economische groei. Zij pleiten voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.",
                    'JA21': "JA21 verzet zich tegen een verhoging omdat zij vrezen dat dit innovatie en werkgelegenheid in de weg staat. Zij vinden dat belastingbeleid de economische dynamiek niet mag verstoren.",
                    'SGP': "De SGP is neutraal en vindt dat grote bedrijven een bijdrage moeten leveren, maar pleit voor stabiliteit en voorspelbaarheid in het belastingstelsel. Zij benadrukken dat abrupt veranderende regels de markt kunnen ontwrichten.",
                    'FvD': "FvD is tegen een verhoging van de winstbelasting en vindt dat een lager tarief juist investeringen en economische groei stimuleert. Zij zien belastingverlagingen als een manier om Nederland aantrekkelijk te houden voor bedrijven.",
                    'DENK': "DENK pleit voor een hogere winstbelasting als middel om sociale investeringen te financieren en ongelijkheid te verminderen. Zij vinden dat multinationals hun eerlijke deel moeten bijdragen aan de samenleving.",
                    'Volt': "Volt steunt een verhoging mits dit bijdraagt aan duurzame investeringen en innovatie. Zij vinden dat een eerlijk belastingsysteem moet bijdragen aan een inclusieve economie."
                }
            },
            {
                title: "AOW-leeftijd",
                description: "De AOW-leeftijd moet worden verlaagd naar 65 jaar.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'eens',
                    'PvdD': 'neutraal',
                    'CDA': 'oneens',
                    'JA21': 'neutraal',
                    'SGP': 'oneens',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "PVV is voorstander van een verlaging van de AOW-leeftijd naar 65 jaar om zo de huidige generaties meer rust en zekerheid te bieden. Zij vinden dat dit bijdraagt aan een eerlijker pensioenstelsel.",
                    'VVD': "VVD is tegen verlaging en vindt dat de AOW-leeftijd in lijn moet blijven met de stijgende levensverwachting. Zij pleiten voor een duurzaam pensioenstelsel dat realistisch is op lange termijn.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat de AOW-leeftijd zorgvuldig moet worden afgewogen met demografische ontwikkelingen. Zij zien zowel voor- als nadelen bij een verlaging.",
                    'BBB': "BBB is neutraal en benadrukt dat de pensioenleeftijd moet aansluiten bij de economische realiteit van werk en gezondheid. Zij pleiten voor een maatstaf die zowel recht doet aan ouderen als aan de economie.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen verlaging omdat zij vinden dat een eerlijk pensioenstelsel juist flexibel moet inspelen op veranderende levensverwachtingen. Zij pleiten voor een bredere aanpak met aandacht voor sociale zekerheid.",
                    'D66': "D66 is tegen een verlaging en ziet de verhoging van de levensverwachting als reden om de AOW-leeftijd geleidelijk te laten stijgen. Zij vinden dat dit bijdraagt aan een houdbaar pensioenstelsel voor de toekomst.",
                    'SP': "SP steunt een verlaging naar 65 jaar, zodat mensen eerder kunnen genieten van hun pensioen. Zij benadrukken dat dit vooral bijdraagt aan sociale rechtvaardigheid en een waardig ouder worden.",
                    'PvdD': "PvdD is voorstander van verlaging omdat zij vinden dat ouderen niet onnodig lang moeten doorwerken. Zij zien dit als een stap richting meer menselijkheid binnen het pensioenbeleid.",
                    'CDA': "Het CDA is neutraal en pleit voor een pensioenstelsel dat zowel rekening houdt met de gezondheid van ouderen als met de economische draagkracht van het land. Zij vinden dat er maatwerk moet zijn in plaats van een standaardleeftijd.",
                    'JA21': "JA21 is tegen verlaging en vindt dat een verhoging van de levensverwachting een natuurlijke reden is om de pensioenleeftijd te handhaven of zelfs te verhogen. Zij benadrukken dat economische haalbaarheid voorop moet staan.",
                    'SGP': "De SGP verzet zich tegen een verlaging en benadrukt dat een stabiel pensioenstelsel gebaseerd moet zijn op lange termijn planning. Zij vinden dat iedereen moet bijdragen aan een duurzaam systeem.",
                    'FvD': "FvD steunt een verlaging omdat zij vinden dat mensen eerder met pensioen mogen gaan. Zij benadrukken dat dit de levenskwaliteit van ouderen ten goede komt, mits het financieel verantwoord is.",
                    'DENK': "DENK pleit voor verlaging en vindt dat een lager instapmoment voor pensioen bijdraagt aan meer sociale rechtvaardigheid. Zij zien dit als een manier om de druk op werkende generaties te verlichten.",
                    'Volt': "Volt is tegen verlaging en stelt dat een realistische AOW-leeftijd moet aansluiten bij demografische ontwikkelingen. Zij pleiten voor een systeem dat zowel recht doet aan ouderen als financieel duurzaam is."
                }
            },
            {
                title: "Sociale Huurwoningen",
                description: "Woningcorporaties moeten voorrang krijgen bij het bouwen van nieuwe woningen.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'neutraal',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'neutraal',
                    'FvD': 'oneens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV steunt de prioriteit voor woningcorporaties bij nieuwbouw om de sociale huurmarkt te versterken. Zij vinden dat zo de betaalbaarheid en toegankelijkheid voor kwetsbare groepen wordt gewaarborgd.",
                    'VVD': "VVD is tegen een dwingende voorrang voor woningcorporaties en pleit voor een marktgerichte aanpak. Zij vinden dat iedereen gelijke kansen moet hebben binnen de woningmarkt.",
                    'NSC': "NSC neemt een neutrale positie in en ziet de meerwaarde van zowel publieke als private initiatieven in het bouwen van woningen. Zij pleiten voor een gebalanceerde aanpak waarin sociale doelen worden bereikt zonder marktverstoring.",
                    'BBB': "BBB ondersteunt voorrang voor woningcorporaties omdat zij vinden dat dit de sociale cohesie bevordert. Zij benadrukken dat overheidsinitiatieven noodzakelijk zijn om betaalbare huisvesting te realiseren.",
                    'GL-PvdA': "GL-PvdA is voorstander van voorrang voor woningcorporaties als middel om de woningnood voor lagere inkomens op te lossen. Zij vinden dat dit bijdraagt aan een eerlijkere verdeling van middelen.",
                    'D66': "D66 kiest voor een neutrale aanpak en ziet zowel de rol van woningcorporaties als private bouwinitiatieven als belangrijk. Zij pleiten voor samenwerking waarbij sociale doelen centraal staan.",
                    'SP': "SP steunt de voorrang voor woningcorporaties om de sociale huursector te versterken en ongelijkheid tegen te gaan. Zij vinden dat de overheid een actieve rol moet spelen in het waarborgen van betaalbare woningen.",
                    'PvdD': "PvdD pleit voor voorrang voor woningcorporaties en ziet dit als een stap naar meer sociale rechtvaardigheid. Zij benadrukken dat het recht op een betaalbare woning voor iedereen moet gelden.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat voorrang voor woningcorporaties logisch is, mits dit gepaard gaat met innovatie in de bouwsector. Zij pleiten voor een mix van oplossingen voor een gezonde woningmarkt.",
                    'JA21': "JA21 is tegen een dwingende voorrang voor woningcorporaties en vindt dat de markt zelf efficiënte oplossingen kan bieden. Zij pleiten voor minder overheidsbemoeienis in de woningsector.",
                    'SGP': "De SGP steunt voorrang voor woningcorporaties als zij bijdragen aan de stabiliteit van de sociale huurmarkt. Zij vinden dat dit een noodzakelijk middel is om betaalbare woningen voor iedereen te realiseren.",
                    'FvD': "FvD neemt een neutrale tot tegenstrijdige positie in en benadrukt dat zowel publieke als private initiatieven hun plaats hebben. Zij vinden dat te veel overheidsbemoeienis innovatie kan belemmeren.",
                    'DENK': "DENK steunt voorrang voor woningcorporaties en ziet dit als een instrument om structurele ongelijkheid in de woningmarkt aan te pakken. Zij vinden dat de overheid hierin een sturende rol moet aannemen.",
                    'Volt': "Volt pleit voor een geïntegreerde aanpak waarbij woningcorporaties samen met private partijen werken aan betaalbare huisvesting. Zij vinden dat samenwerking de sleutel is tot een duurzame oplossing voor de woningnood."
                }
            },
            {
                title: "Ontwikkelingshulp",
                description: "Nederland moet bezuinigen op ontwikkelingshulp.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'eens',
                    'NSC': 'neutraal',
                    'BBB': 'eens',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'oneens',
                    'JA21': 'eens',
                    'SGP': 'neutraal',
                    'FvD': 'eens',
                    'DENK': 'oneens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "PVV is voorstander van bezuinigingen op ontwikkelingshulp omdat zij vinden dat de middelen beter in Nederland ingezet kunnen worden. Zij benadrukken dat nationale belangen voorop moeten staan.",
                    'VVD': "VVD steunt bezuinigingen op ontwikkelingshulp om de eigen financiën op orde te krijgen. Zij vinden dat internationale hulp zorgvuldig en selectief moet worden ingezet.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat ontwikkelingshulp zowel kansen als risico's met zich meebrengt. Zij pleiten voor een strategisch beleid dat rendement op lange termijn oplevert.",
                    'BBB': "BBB ondersteunt bezuinigingen op ontwikkelingshulp en vindt dat de focus moet liggen op nationale economische ontwikkeling. Zij benadrukken dat hulp alleen effectief is als deze gericht en gecontroleerd is.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen bezuinigingen op ontwikkelingshulp en ziet dit als een essentiële manier om internationale solidariteit te tonen. Zij vinden dat investeren in ontwikkelingslanden ook bijdraagt aan een stabielere wereld.",
                    'D66': "D66 is tegen bezuinigingen en pleit voor behoud van ontwikkelingshulp als onderdeel van internationale samenwerking en duurzaamheid. Zij zien dit als een investering in een vreedzamere wereldorde.",
                    'SP': "SP is fel tegen bezuinigingen op ontwikkelingshulp en vindt dat Nederland meer verantwoordelijkheid heeft in het ondersteunen van kwetsbare landen. Zij benadrukken dat solidariteit essentieel is voor internationale stabiliteit.",
                    'PvdD': "PvdD verzet zich tegen bezuinigingen en ziet ontwikkelingshulp als een morele verplichting richting armere landen. Zij vinden dat duurzaamheid en milieubescherming ook internationaal aandacht verdienen.",
                    'CDA': "Het CDA neemt een afgewogen positie in en vindt dat ontwikkelingshulp gericht en doelbewust moet worden ingezet. Zij pleiten voor samenwerking met internationale partners om effectiviteit te waarborgen.",
                    'JA21': "JA21 staat neutraal en vindt dat ontwikkelingshulp kritisch geëvalueerd moet worden op effectiviteit, maar behoudt geen uitgesproken standpunt voor ingrijpende bezuinigingen. Zij benadrukken transparantie in de besteding.",
                    'SGP': "De SGP is terughoudend met bezuinigingen en vindt dat ontwikkelingshulp essentieel is voor internationale solidariteit. Zij pleiten voor een beleid dat bijdraagt aan duurzame ontwikkeling in arme landen.",
                    'FvD': "FvD steunt bezuinigingen op ontwikkelingshulp omdat zij vinden dat dit budget beter in eigen land kan worden gebruikt. Zij benadrukken nationale prioriteiten en efficiënte besteding van middelen.",
                    'DENK': "DENK verzet zich tegen bezuinigingen en pleit voor een humaan ontwikkelingsbeleid dat internationale ongelijkheid tegengaat. Zij vinden dat hulp een investering in een betere wereld is.",
                    'Volt': "Volt is tegen bezuinigingen en wil ontwikkelingshulp juist versterken als onderdeel van een internationale duurzaamheidsagenda. Zij vinden dat samenwerking cruciaal is voor globale vooruitgang."
                }
            },
            {
                title: "Zorgverzekering",
                description: "Er moet één publieke zorgverzekering komen in plaats van verschillende private verzekeraars.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'oneens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'oneens',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'neutraal',
                    'DENK': 'eens',
                    'Volt': 'neutraal'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in en ziet geen directe meerwaarde in het invoeren van één publieke zorgverzekering. Zij vinden dat de marktwerking binnen de zorg ook voordelen kent.",
                    'VVD': "VVD is tegen het idee van één publieke zorgverzekering omdat zij vrezen dat dit innovatie en keuzevrijheid beperkt. Zij pleiten voor een concurrerende markt die kwaliteit stimuleert.",
                    'NSC': "NSC is neutraal en vindt dat zowel publieke als private oplossingen hun merites hebben. Zij zien dat een integrale aanpak nodig is om de zorg betaalbaar en toegankelijk te houden.",
                    'BBB': "BBB neemt een neutrale positie in en stelt dat één publieke zorgverzekering de administratieve lasten kan verminderen, maar waarschuwt voor te veel centralisatie. Zij pleiten voor een pragmatische aanpak.",
                    'GL-PvdA': "GL-PvdA steunt één publieke zorgverzekering als middel om solidariteit en gelijke toegang tot zorg te waarborgen. Zij vinden dat dit systeem oneerlijke winsten en ongelijkheid tegengaat.",
                    'D66': "D66 verzet zich tegen een monopolie in de zorgverzekering en ziet dit als een beperking van innovatie. Zij pleiten voor een gereguleerde markt met behoud van keuzevrijheid.",
                    'SP': "SP is voorstander van één publieke zorgverzekering om de zorgkosten te verlagen en gelijke behandeling te garanderen. Zij vinden dat een dergelijk systeem zorgt voor meer transparantie en solidariteit.",
                    'PvdD': "PvdD ondersteunt één publieke zorgverzekering als een stap richting een menselijker en efficiënter zorgsysteem. Zij benadrukken dat dit de zorgtoegang voor iedereen moet verbeteren.",
                    'CDA': "Het CDA neemt een neutrale positie in en benadrukt dat het belangrijk is om zowel efficiëntie als toegankelijkheid te waarborgen in de zorg. Zij vinden dat een goede mix van publiek en privaat de beste resultaten levert.",
                    'JA21': "JA21 verzet zich tegen één publieke zorgverzekering omdat zij vrezen voor verlies van keuzevrijheid en bureaucratische rompslomp. Zij pleiten voor behoud van meerdere aanbieders onder streng toezicht.",
                    'SGP': "De SGP is tegen één publieke zorgverzekering en hecht waarde aan een pluralistisch systeem waarin meerdere aanbieders concurreren. Zij vinden dat dit de kwaliteit en innovatie in de zorg stimuleert.",
                    'FvD': "FvD kiest voor een neutrale tot terughoudende positie en ziet de huidige markt als bewezen werkbaar. Zij vinden dat een publieke monopoliestructuur juist nadelen kan opleveren.",
                    'DENK': "DENK steunt één publieke zorgverzekering als middel om structurele ongelijkheden in de zorg tegen te gaan. Zij vinden dat een dergelijk systeem meer recht doet aan de basisrechten van burgers.",
                    'Volt': "Volt kiest voor een neutrale benadering en pleit voor een zorgsysteem dat toegankelijk en transparant is, ongeacht de vorm. Zij vinden dat innovatie en samenwerking centraal moeten staan in de zorgtransitie."
                }
            },
            {
                title: "Referendum",
                description: "Er moet een bindend referendum komen waarbij burgers kunnen meebeslissen over belangrijke onderwerpen.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'eens',
                    'GL-PvdA': 'neutraal',
                    'D66': 'eens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'oneens',
                    'JA21': 'eens',
                    'SGP': 'oneens',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV is voorstander van een bindend referendum zodat burgers direct kunnen meebeslissen over belangrijke onderwerpen. Zij zien dit als een versterking van de democratische legitimiteit.",
                    'VVD': "VVD verzet zich tegen een bindend referendum omdat zij vrezen dat dit politieke besluitvorming vertraagt en polarisatie versterkt. Zij pleiten voor vertegenwoordigde democratie met goed geïnformeerde besluitvorming.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat referenda een aanvulling kunnen zijn, mits ze zorgvuldig worden georganiseerd. Zij benadrukken dat directe democratie niet ten koste mag gaan van beleidscontinuïteit.",
                    'BBB': "BBB steunt referenda als een middel om burgers meer invloed te geven op het beleid. Zij vinden dat directe inspraak het vertrouwen in de politiek kan vergroten.",
                    'GL-PvdA': "GL-PvdA is neutraal en ziet zowel voordelen als risico's in bindende referenda. Zij pleiten voor zorgvuldige voorwaarden en voldoende voorlichting aan de burger.",
                    'D66': "D66 steunt een bindend referendum in principe, maar vindt dat dit alleen moet gebeuren bij onderwerpen van nationaal belang. Zij benadrukken dat referenda de complexiteit van beleidsvraagstukken soms niet volledig vatten.",
                    'SP': "SP is voorstander van een bindend referendum omdat zij geloven dat dit de burger dichter bij de politiek brengt. Zij vinden dat meer directe inspraak leidt tot meer democratische betrokkenheid.",
                    'PvdD': "PvdD steunt referenda als middel om mensenrechten en milieubescherming direct in de besluitvorming te verankeren. Zij vinden dat burgers beter geïnformeerd moeten worden over de consequenties van hun keuzes.",
                    'CDA': "Het CDA is tegen een al te frequente inzet van referenda en vindt dat vertegenwoordigde democratie stabieler is. Zij pleiten voor gerichte referenda bij echt onoverbrugbare meningsverschillen.",
                    'JA21': "JA21 is voorstander van referenda als het gaat om belangrijke beleidsvragen en wil burgers meer directe invloed geven. Zij vinden dat dit het vertrouwen in de politiek kan herstellen.",
                    'SGP': "De SGP verzet zich tegen een brede inzet van bindende referenda omdat zij vrezen voor populisme en simplistische besluitvorming. Zij vinden dat deskundigheid en ervaring belangrijker zijn in beleidskeuzes.",
                    'FvD': "FvD steunt referenda als een manier om de macht terug te geven aan het volk en de invloed van politieke elites te beperken. Zij benadrukken dat directe democratie de weg naar echte soevereiniteit is.",
                    'DENK': "DENK is voorstander van referenda als middel om de stem van minderheden en gemarginaliseerde groepen kracht bij te zetten. Zij vinden dat directe inspraak een brug kan slaan tussen burger en overheid.",
                    'Volt': "Volt pleit voor een bindend referendum als aanvulling op de representatieve democratie, mits goed gefaciliteerd. Zij vinden dat dit de betrokkenheid en transparantie in de politiek verhoogt."
                }
            },
            {
                title: "Winstbelasting",
                description: "De winstbelasting voor grote bedrijven moet omhoog.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'neutraal',
                    'FvD': 'oneens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "De PVV steunt een hogere winstbelasting voor grote bedrijven als een manier om de lasten eerlijker te verdelen. Zij vinden dat multinationals een grotere maatschappelijke verantwoordelijkheid hebben.",
                    'VVD': "VVD verzet zich tegen een verhoging omdat zij vrezen dat dit de concurrentiepositie van Nederlandse bedrijven schaadt. Zij pleiten voor een belastingbeleid dat groei en innovatie stimuleert.",
                    'NSC': "NSC neemt een neutrale positie in en ziet dat een verhoging zowel voordelen als nadelen kent voor de economie. Zij pleiten voor een weloverwogen beleid dat rekening houdt met internationale concurrentie.",
                    'BBB': "BBB houdt een neutrale positie aan en vindt dat grote bedrijven wel een extra bijdrage moeten leveren, maar dat een te hoge belastingdruk schadelijk kan zijn. Zij benadrukken de noodzaak van een evenwichtig belastingstelsel.",
                    'GL-PvdA': "GL-PvdA steunt hogere belastingen voor grote bedrijven als middel om sociale ongelijkheid tegen te gaan. Zij zien dit als een investering in publieke voorzieningen en sociale rechtvaardigheid.",
                    'D66': "D66 is voorstander van een verhoging mits de extra opbrengsten worden geïnvesteerd in innovatie en duurzaamheid. Zij vinden dat bedrijven niet te veel voordeel mogen trekken uit de maatschappij zonder terug te geven.",
                    'SP': "SP pleit voor hogere belastingen voor grote bedrijven om inkomensongelijkheid te verkleinen. Zij vinden dat de winsten van multinationals eerlijker verdeeld moeten worden over de samenleving.",
                    'PvdD': "PvdD ondersteunt een verhoging omdat zij vinden dat grote bedrijven een grotere maatschappelijke verantwoordelijkheid hebben. Zij benadrukken dat een eerlijke bijdrage bijdraagt aan een duurzame samenleving.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat een verhoging zorgvuldig moet worden afgewogen tegen de risico's voor economische groei. Zij pleiten voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.",
                    'JA21': "JA21 verzet zich tegen een verhoging omdat zij vrezen dat dit innovatie en werkgelegenheid in de weg staat. Zij vinden dat belastingbeleid de economische dynamiek niet mag verstoren.",
                    'SGP': "De SGP is neutraal en vindt dat grote bedrijven een bijdrage moeten leveren, maar pleit voor stabiliteit en voorspelbaarheid in het belastingstelsel. Zij benadrukken dat abrupt veranderende regels de markt kunnen ontwrichten.",
                    'FvD': "FvD is tegen een verhoging van de winstbelasting en vindt dat een lager tarief juist investeringen en economische groei stimuleert. Zij zien belastingverlagingen als een manier om Nederland aantrekkelijk te houden voor bedrijven.",
                    'DENK': "DENK pleit voor een hogere winstbelasting als middel om sociale investeringen te financieren en ongelijkheid te verminderen. Zij vinden dat multinationals hun eerlijke deel moeten bijdragen aan de samenleving.",
                    'Volt': "Volt steunt een verhoging mits dit bijdraagt aan duurzame investeringen en innovatie. Zij vinden dat een eerlijk belastingsysteem moet bijdragen aan een inclusieve economie."
                }
            },
            {
                title: "Legalisering Drugs",
                description: "Alle drugs moeten worden gelegaliseerd en gereguleerd.",
                positions: {
                    'PVV': 'oneens',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'oneens',
                    'GL-PvdA': 'neutraal',
                    'D66': 'eens',
                    'SP': 'oneens',
                    'PvdD': 'neutraal',
                    'CDA': 'oneens',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'neutraal',
                    'DENK': 'oneens',
                    'Volt': 'neutraal'
                },
                explanations: {
                    'PVV': "PVV is fel tegen de legalisering van alle drugs en vindt dat dit leidt tot meer maatschappelijke problemen. Zij pleiten voor een streng verbod ter bescherming van de volksgezondheid.",
                    'VVD': "VVD verzet zich tegen legalisering en is van mening dat drugscriminaliteit juist toeneemt als gevolg van versoepelde regels. Zij vinden dat preventie en handhaving voorrang hebben.",
                    'NSC': "NSC neemt een neutrale positie in en ziet zowel risico's als voordelen in legalisering, afhankelijk van de aanpak. Zij pleiten voor een wetenschappelijk onderbouwd beleid dat de volksgezondheid beschermt.",
                    'BBB': "BBB is tegen de legalisering van alle drugs omdat zij vrezen voor een toename in misbruik en sociale problemen. Zij vinden dat het huidige verbod noodzakelijk is voor de volksgezondheid.",
                    'GL-PvdA': "GL-PvdA kiest voor een neutrale benadering en is bereid naar gereguleerde legalisering te kijken, mits dit leidt tot betere zorg en preventie. Zij vinden dat de focus moet liggen op gezondheidszorg in plaats van criminalisering.",
                    'D66': "D66 is voorstander van legalisering en regulering van alle drugs als middel om controle te krijgen over kwaliteit en veiligheid. Zij vinden dat een gereguleerde markt meer ruimte biedt voor preventie en behandeling.",
                    'SP': "SP is tegen volledige legalisering en vindt dat drugsproblematica vooral aangepakt moeten worden via preventie en sociale hulp. Zij benadrukken dat maatschappelijke veiligheid en gezondheid voorop moeten staan.",
                    'PvdD': "PvdD kiest voor een neutrale positie en ziet legalisering als een mogelijke stap richting een effectiever gezondheidsbeleid, mits goed gereguleerd. Zij vinden dat de focus ligt op schadebeperking in plaats van strafrecht.",
                    'CDA': "Het CDA is tegen legalisering omdat zij vrezen voor een afname van de volksgezondheid en maatschappelijke onrust. Zij pleiten voor behoud van duidelijke grenzen en preventieve maatregelen.",
                    'JA21': "JA21 is fel tegen de legalisering van drugs en ziet hierin een directe bedreiging voor de openbare orde. Zij vinden dat het huidige verbod noodzakelijk is om jongeren te beschermen.",
                    'SGP': "De SGP verzet zich tegen legalisering en benadrukt de morele en maatschappelijke risico's die hiermee gepaard gaan. Zij vinden dat de overheid moet inzetten op preventie en handhaving.",
                    'FvD': "FvD kiest voor een neutrale tot terughoudende positie en vindt dat er meer onderzoek nodig is naar de gevolgen van legalisering. Zij benadrukken dat volksgezondheid en veiligheid voorop moeten staan.",
                    'DENK': "DENK is tegen legalisering omdat zij vrezen dat dit leidt tot extra maatschappelijke kwetsbaarheden. Zij pleiten voor een beleid dat de schade beperkt en investeert in preventie.",
                    'Volt': "Volt steunt een gereguleerde legalisering als dit leidt tot betere controle en minder criminaliteit. Zij vinden dat een transparante aanpak de volksgezondheid ten goede kan komen."
                }
            },
            {
                title: "Kilometerheffing",
                description: "Er moet een kilometerheffing komen voor autorijders.",
                positions: {
                    'PVV': 'oneens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'oneens',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'oneens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'oneens',
                    'DENK': 'neutraal',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV is fel tegen de invoering van een kilometerheffing omdat zij vrezen dat dit burgers oneerlijk treft. Zij vinden dat de lasten voor automobilisten niet verder mogen worden opgelegd.",
                    'VVD': "VVD verzet zich tegen de kilometerheffing en benadrukt dat dit de mobiliteit en economie kan schaden. Zij pleiten voor minder overheidsbemoeienis in het verkeer.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat de invoering van een kilometerheffing zowel voordelen als nadelen kent. Zij pleiten voor een grondige analyse van de economische en sociale impact.",
                    'BBB': "BBB is tegen de kilometerheffing en vindt dat deze maatregel vooral een last is voor de burger. Zij pleiten voor alternatieve oplossingen die de mobiliteit niet beperken.",
                    'GL-PvdA': "GL-PvdA steunt de kilometerheffing als een instrument om duurzame mobiliteit te bevorderen en verkeerscongestie te verminderen. Zij vinden dat de opbrengsten gebruikt moeten worden voor openbaar vervoer en groene initiatieven.",
                    'D66': "D66 is voorstander van de kilometerheffing en ziet dit als een belangrijke stap richting een schoner en duurzamer mobiliteitssysteem. Zij vinden dat de heffing eerlijk verdeeld moet worden en de opbrengsten moeten investeren in infrastructuur.",
                    'SP': "SP is tegen de kilometerheffing omdat zij vrezen dat dit met name lagere inkomens onevenredig treft. Zij pleiten voor maatregelen die sociale rechtvaardigheid in de mobiliteit waarborgen.",
                    'PvdD': "PvdD steunt de invoering van een kilometerheffing als een manier om de ecologische voetafdruk te verkleinen. Zij vinden echter dat de implementatie zorgvuldig moet gebeuren om sociale ongelijkheid te voorkomen.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat een kilometerheffing alleen kan als er duidelijke voordelen zijn voor zowel milieu als infrastructuur. Zij pleiten voor een afgewogen aanpak waarbij de lasten eerlijk worden verdeeld.",
                    'JA21': "JA21 is tegen de kilometerheffing en ziet hierin een onnodige belemmering voor de mobiliteit van burgers. Zij vinden dat overheidsingrijpen in het verkeer beperkt moet blijven.",
                    'SGP': "De SGP verzet zich tegen een kilometerheffing en benadrukt dat dit vooral de burger raakt zonder voldoende compensatie. Zij pleiten voor alternatieve maatregelen die niet tot extra lasten leiden.",
                    'FvD': "FvD is fel tegen de invoering van een kilometerheffing omdat zij dit zien als een onnodige belasting op mobiliteit. Zij vinden dat dergelijke maatregelen de economische activiteit juist kunnen belemmeren.",
                    'DENK': "DENK neemt een neutrale positie in en pleit voor een debat over de sociale en economische impact van een kilometerheffing. Zij vinden dat als het wordt ingevoerd, dit gepaard moet gaan met compensatieregelingen voor kwetsbare groepen.",
                    'Volt': "Volt steunt de kilometerheffing als onderdeel van een bredere transitie naar duurzame mobiliteit, mits dit gepaard gaat met eerlijke compensatiemechanismen. Zij vinden dat dit een noodzakelijke stap is richting een toekomstbestendige infrastructuur."
                }
            },
            {
                title: "Kinderopvang",
                description: "Kinderopvang moet gratis worden voor alle ouders.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'eens',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'oneens',
                    'DENK': 'eens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in en vindt dat kinderopvang niet volledig gratis moet zijn, zodat er sprake blijft van persoonlijke verantwoordelijkheid. Zij benadrukken dat de kwaliteit en toegankelijkheid gewaarborgd moeten blijven.",
                    'VVD': "VVD is tegen gratis kinderopvang omdat zij vrezen voor een te grote overheidsbemoeienis en hogere belastingdruk. Zij pleiten voor een marktgerichte oplossing met subsidiemogelijkheden voor gezinnen in nood.",
                    'NSC': "NSC is neutraal en ziet zowel de voordelen als de kosten van gratis kinderopvang. Zij pleiten voor een aanpak die toegankelijkheid combineert met economische haalbaarheid.",
                    'BBB': "BBB neemt een neutrale positie in en vindt dat kinderopvang betaalbaar moet blijven, maar niet noodzakelijkerwijs volledig gratis. Zij benadrukken dat regionale initiatieven hierbij kunnen helpen.",
                    'GL-PvdA': "GL-PvdA steunt gratis kinderopvang als een middel om gelijke kansen voor kinderen te garanderen. Zij vinden dat de overheid moet investeren in een toegankelijk en kwalitatief hoogstaand systeem.",
                    'D66': "D66 is voorstander van gratis kinderopvang om werk en gezin beter te combineren en sociale ongelijkheid te verminderen. Zij pleiten voor een innovatieve aanpak die zowel betaalbaarheid als kwaliteit garandeert.",
                    'SP': "SP steunt gratis kinderopvang omdat zij geloven dat dit bijdraagt aan sociale gelijkheid en de ontwikkeling van kinderen. Zij vinden dat het een investering is in de toekomst van de samenleving.",
                    'PvdD': "PvdD ondersteunt gratis kinderopvang als onderdeel van een progressieve en inclusieve sociale agenda. Zij vinden dat dit ouders ontzorgt en gelijke kansen voor alle kinderen bevordert.",
                    'CDA': "Het CDA neemt een neutrale positie in en pleit voor kinderopvang die toegankelijk is voor iedereen, zonder te vervallen in volledige gratis voorzieningen. Zij vinden dat een mix van publiek en privaat de beste resultaten oplevert.",
                    'JA21': "JA21 is tegen gratis kinderopvang omdat zij vrezen dat dit leidt tot te hoge overheidskosten en inefficiënties. Zij pleiten voor een systeem met eigen bijdrage dat wel betaalbaar blijft.",
                    'SGP': "De SGP steunt geen volledig gratis systeem en vindt dat ouders een deel van de kosten zelf moeten dragen. Zij benadrukken dat verantwoordelijkheid en betrokkenheid van de ouder belangrijk zijn.",
                    'FvD': "FvD is tegen gratis kinderopvang omdat zij vrezen voor overmatige overheidsinmenging in het gezinsleven. Zij pleiten voor een systeem dat ruimte laat voor keuze en eigen verantwoordelijkheid.",
                    'DENK': "DENK steunt gratis kinderopvang als een middel om sociale barrières te slechten en kansen voor alle kinderen te vergroten. Zij vinden dat de overheid hierin een centrale rol moet spelen.",
                    'Volt': "Volt pleit voor gratis kinderopvang als investering in de toekomst, mits dit gepaard gaat met hoge kwaliteitsnormen. Zij vinden dat dit ouders helpt te combineren en de maatschappelijke participatie bevordert."
                }
            },
            {
                title: "Kernwapens",
                description: "Amerikaanse kernwapens moeten van Nederlands grondgebied worden verwijderd.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'eens',
                    'D66': 'neutraal',
                    'SP': 'eens',
                    'PvdD': 'eens',
                    'CDA': 'oneens',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in en vindt dat kernwapens op Nederlands grondgebied geen prioriteit hebben. Zij pleiten voor een sterke eigen defensie zonder afhankelijkheid van buitenlandse wapens.",
                    'VVD': "VVD is tegen het verwijderen van Amerikaanse kernwapens, omdat zij dit zien als een garantie voor veiligheid en afschrikking. Zij vinden dat internationale veiligheidssovereenkomsten hier een rol in spelen.",
                    'NSC': "NSC is tegen het verwijderen van kernwapens en benadrukt dat veiligheid en strategische afschrikking cruciaal zijn in een onzekere wereld. Zij pleiten voor samenwerking met bondgenoten op dit gebied.",
                    'BBB': "BBB neemt een neutrale positie in en ziet dat de aanwezigheid van kernwapens op Nederlands grondgebied zowel voor als tegen kan werken. Zij pleiten voor een objectieve evaluatie van de veiligheidssituatie.",
                    'GL-PvdA': "GL-PvdA steunt de verwijdering van Amerikaanse kernwapens en wil inzetten op een onafhankelijke en pacifistische defensie. Zij vinden dat Nederland moet bijdragen aan een wereld zonder nucleaire dreiging.",
                    'D66': "D66 is neutraal maar neigt naar verwijdering, mits dit leidt tot versterking van internationale ontwapening en veiligheid. Zij vinden dat diplomatieke oplossingen altijd de voorkeur verdienen.",
                    'SP': "SP steunt de verwijdering van kernwapens en ziet dit als een noodzakelijke stap richting een vreedzamere wereld. Zij pleiten voor een beleid dat militair geweld zoveel mogelijk ontmoedigt.",
                    'PvdD': "PvdD is voorstander van het verwijderen van kernwapens en pleit voor een wereld zonder nucleaire dreiging. Zij vinden dat veiligheid beter bereikt wordt door samenwerking en ontwapening.",
                    'CDA': "Het CDA is tegen de verwijdering omdat zij vrezen voor een verzwakte afschrikking in een onstabiele wereld. Zij pleiten voor een weloverwogen balans tussen veiligheid en ontwapening.",
                    'JA21': "JA21 is tegen verwijdering en vindt dat kernwapens een belangrijk afschrikmiddel zijn in internationale betrekkingen. Zij pleiten voor behoud als onderdeel van een robuust defensiebeleid.",
                    'SGP': "De SGP verzet zich tegen de verwijdering en benadrukt dat de aanwezigheid van kernwapens bijdraagt aan de nationale veiligheid. Zij vinden dat afschrikking essentieel is in een onzekere wereld.",
                    'FvD': "FvD steunt de verwijdering van Amerikaanse kernwapens en pleit voor een meer onafhankelijke veiligheidsstrategie. Zij vinden dat Nederland moet inzetten op eigen defensie zonder nucleaire afhankelijkheden.",
                    'DENK': "DENK is voorstander van verwijdering, omdat zij geloven in een wereld waarin wapens van massavernietiging geen rol meer spelen. Zij pleiten voor internationale samenwerking op het gebied van ontwapening.",
                    'Volt': "Volt steunt de verwijdering van kernwapens als onderdeel van een bredere internationale ontwapeningsagenda. Zij vinden dat een vreedzamere wereld alleen mogelijk is als de nucleaire dreiging wordt weggenomen."
                }
            },
            {
                title: "Monarchie",
                description: "Nederland moet een republiek worden in plaats van een monarchie.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'oneens',
                    'NSC': 'oneens',
                    'BBB': 'oneens',
                    'GL-PvdA': 'neutraal',
                    'D66': 'neutraal',
                    'SP': 'eens',
                    'PvdD': 'neutraal',
                    'CDA': 'oneens',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'eens',
                    'DENK': 'neutraal',
                    'Volt': 'neutraal'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in over de toekomst van de monarchie en vindt dat het institutionele karakter belangrijk is voor stabiliteit. Zij zien geen dringende reden voor een verandering.",
                    'VVD': "VVD is tegen het omvormen naar een republiek en beschouwt de monarchie als een onmisbaar onderdeel van de nationale identiteit. Zij vinden dat de monarchie symbool staat voor continuïteit en traditie.",
                    'NSC': "NSC neemt een neutrale positie in en vindt dat zowel monarchie als republiek voor- en nadelen hebben. Zij pleiten voor behoud als het past binnen de maatschappelijke consensus.",
                    'BBB': "BBB is tegen het afschaffen van de monarchie en hecht waarde aan de traditionele waarden die daarmee gepaard gaan. Zij vinden dat de monarchie een bindende factor is in de samenleving.",
                    'GL-PvdA': "GL-PvdA steunt in principe de monarchie, maar vindt dat deze modernisering behoeft om representatief te blijven. Zij pleiten voor meer transparantie en een symbolische rol in een moderne democratie.",
                    'D66': "D66 is neutraal over de monarchie en ziet het als een institutioneel erfgoed, mits dit modern en democratisch wordt ingevuld. Zij vinden dat de monarchie moet evolueren met de tijd.",
                    'SP': "SP steunt een republiek en vindt dat een democratisch gekozen staatshoofd beter past bij een moderne samenleving. Zij pleiten voor een scheiding tussen symboliek en daadwerkelijke politieke macht.",
                    'PvdD': "PvdD is neutraal en benadrukt dat de discussie over de monarchie vooral symbolisch is. Zij vinden dat politieke inhoud belangrijker is dan de vorm van het staatshoofd.",
                    'CDA': "Het CDA steunt de monarchie als een symbool van eenheid en continuïteit. Zij vinden dat traditie en moderniteit hand in hand kunnen gaan binnen de monarchale inrichting.",
                    'JA21': "JA21 is tegen een republikeinse omwenteling en pleit voor behoud van de monarchie als onderdeel van de nationale identiteit. Zij vinden dat de monarchie stabiliteit en continuïteit biedt.",
                    'SGP': "De SGP is fel tegen het afschaffen van de monarchie en ziet deze als een essentieel onderdeel van de Nederlandse traditie. Zij vinden dat de monarchie een moreel en cultureel anker is in de samenleving.",
                    'FvD': "FvD steunt de monarchie en beschouwt deze als een belangrijk symbool van nationale soevereiniteit en traditie. Zij vinden dat de monarchie juist een verbindende factor is in een verdeelde samenleving.",
                    'DENK': "DENK neemt een neutrale positie in en vindt dat de discussie over monarchie vooral over symboliek gaat. Zij pleiten voor een debat dat meer aandacht besteedt aan daadwerkelijke beleidsuitdagingen.",
                    'Volt': "Volt is neutraal en ziet de monarchie als een historisch instituut dat aangepast kan worden aan moderne democratische waarden. Zij vinden dat symbolische representatie moet samengaan met transparante governance."
                }
            },
            {
                title: "Pensioenstelsel",
                description: "Het nieuwe pensioenstelsel moet worden teruggedraaid.",
                positions: {
                    'PVV': 'eens',
                    'VVD': 'oneens',
                    'NSC': 'neutraal',
                    'BBB': 'eens',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'eens',
                    'PvdD': 'neutraal',
                    'CDA': 'oneens',
                    'JA21': 'neutraal',
                    'SGP': 'neutraal',
                    'FvD': 'eens',
                    'DENK': 'eens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "PVV steunt het terugdraaien van het nieuwe pensioenstelsel om zo de oude zekerheden te herstellen. Zij vinden dat het huidige stelsel te complex en nadelig is voor de burger.",
                    'VVD': "VVD verzet zich tegen terugdraaiing en pleit voor een modern en duurzaam pensioenstelsel dat aansluit bij demografische ontwikkelingen. Zij vinden dat hervormingen nodig zijn om het stelsel toekomstbestendig te maken.",
                    'NSC': "NSC neemt een neutrale positie in en ziet zowel de voordelen als de nadelen van het nieuwe stelsel. Zij pleiten voor aanpassingen op basis van economische en demografische realiteiten.",
                    'BBB': "BBB steunt het terugdraaien van het nieuwe pensioenstelsel en wil zo de rechten van gepensioneerden beter waarborgen. Zij vinden dat eenvoud en zekerheid voor de oudere generatie voorop moeten staan.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen terugdraaiing en vindt dat hervormingen in het pensioenstelsel nodig zijn om de duurzaamheid te waarborgen. Zij pleiten voor een evenwichtige aanpak die zowel solidariteit als duurzaamheid combineert.",
                    'D66': "D66 is tegen terugdraaiing en wil inzetten op innovatie en hervorming van het pensioenstelsel. Zij vinden dat aanpassingen noodzakelijk zijn in het licht van een veranderende samenleving.",
                    'SP': "SP steunt het terugdraaien van het nieuwe pensioenstelsel omdat zij geloven dat dit de belangen van werkenden en gepensioneerden beter beschermt. Zij vinden dat het systeem eerlijker moet worden ingericht.",
                    'PvdD': "PvdD is neutraal maar benadrukt dat het pensioenstelsel menselijk en duurzaam moet zijn. Zij pleiten voor hervormingen die de burger daadwerkelijk ten goede komen zonder overmatige financiële risico's.",
                    'CDA': "Het CDA neemt een neutrale positie in en vindt dat het pensioenstelsel moet aansluiten bij de realiteit van de arbeidsmarkt en demografie. Zij pleiten voor een pragmatische mix van hervorming en stabiliteit.",
                    'JA21': "JA21 steunt terugdraaiing van het nieuwe pensioenstelsel om zo de oude zekerheden te herstellen. Zij vinden dat de huidige hervormingen de burger te veel benadelen.",
                    'SGP': "De SGP is neutraal en benadrukt dat een pensioenstelsel zowel rechtvaardig als houdbaar moet zijn. Zij pleiten voor aanpassingen die zowel de belangen van gepensioneerden als de toekomstige generaties dienen.",
                    'FvD': "FvD steunt het terugdraaien van het nieuwe pensioenstelsel en vindt dat de overheid de burger beter moet beschermen tegen onzekerheid. Zij benadrukken dat eenvoud en transparantie cruciaal zijn in een pensioenstelsel.",
                    'DENK': "DENK pleit voor het terugdraaien van het nieuwe pensioenstelsel om sociale rechtvaardigheid te waarborgen. Zij vinden dat een eerlijk pensioen voor iedereen de basis vormt van een stabiele samenleving.",
                    'Volt': "Volt is tegen het terugdraaien van het nieuwe pensioenstelsel en pleit voor een toekomstbestendige hervorming. Zij vinden dat innovatie en flexibiliteit centraal moeten staan om de pensioenzekerheid te waarborgen."
                }
            },
            {
                title: "Defensiesamenwerking",
                description: "Nederland moet streven naar een Europees leger.",
                positions: {
                    'PVV': 'oneens',
                    'VVD': 'neutraal',
                    'NSC': 'neutraal',
                    'BBB': 'oneens',
                    'GL-PvdA': 'neutraal',
                    'D66': 'eens',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'neutraal',
                    'JA21': 'oneens',
                    'SGP': 'oneens',
                    'FvD': 'oneens',
                    'DENK': 'oneens',
                    'Volt': 'eens'
                },
                explanations: {
                    'PVV': "PVV is tegen het idee van een Europees leger en vindt dat Nederland zijn eigen defensiecapaciteiten moet behouden. Zij vrezen dat een dergelijk initiatief de nationale soevereiniteit ondermijnt.",
                    'VVD': "VVD neemt een neutrale tot voorzichtige positie in en is bereid Europese defensiesamenwerking te overwegen, mits dit de nationale belangen niet schaadt. Zij vinden dat samenwerking op maat moet gebeuren.",
                    'NSC': "NSC is neutraal en ziet voordelen in een Europees leger, maar vindt dat nationale belangen altijd voorop moeten staan. Zij pleiten voor een gebalanceerde samenwerking op het gebied van veiligheid.",
                    'BBB': "BBB is tegen een Europees leger en pleit voor behoud van nationale controle over defensie. Zij vinden dat Nederland beter beschermd is wanneer het zelfstandig opereert.",
                    'GL-PvdA': "GL-PvdA ondersteunt de ambitie voor een Europees leger als middel om gezamenlijke veiligheid te garanderen. Zij vinden dat samenwerking leidt tot meer slagkracht en solidariteit in Europa.",
                    'D66': "D66 pleit voor een Europees leger en ziet dit als een logische stap richting een hechtere samenwerking binnen de EU op het gebied van veiligheid. Zij vinden dat gezamenlijke middelen leiden tot efficiëntere defensie.",
                    'SP': "SP is tegen een Europees leger en vreest dat dit leidt tot een verlaging van de democratische controle over defensie. Zij vinden dat veiligheid lokaal en menselijk georganiseerd moet worden.",
                    'PvdD': "PvdD verzet zich tegen een Europees leger omdat zij vinden dat nationale democratie en controle gewaarborgd moeten blijven. Zij pleiten voor samenwerking op specifieke terreinen in plaats van een alomvattend leger.",
                    'CDA': "Het CDA is neutraal en vindt dat defensiesamenwerking op Europees niveau kansen biedt, mits nationale belangen niet ondergeschikt worden gemaakt. Zij pleiten voor een hybride model waarin samenwerking en zelfstandigheid hand in hand gaan.",
                    'JA21': "JA21 is tegen een Europees leger en vindt dat nationale defensie prioriteit moet krijgen boven Europese integratie. Zij pleiten voor behoud van volledige soevereiniteit op defensiegebied.",
                    'SGP': "De SGP verzet zich tegen een Europees leger en benadrukt dat nationale veiligheid moet worden gewaarborgd door eigen controle. Zij vinden dat samenwerking altijd in het belang van de eigen bevolking moet zijn.",
                    'FvD': "FvD is tegen defensiesamenwerking in de vorm van een Europees leger omdat zij vrezen voor een verlies aan nationale autonomie. Zij pleiten voor een sterke, onafhankelijke Nederlandse defensie.",
                    'DENK': "DENK steunt beperkte Europese defensiesamenwerking maar is tegen een volledig geïntegreerd Europees leger. Zij vinden dat nationale belangen altijd eerst komen, zelfs binnen samenwerking.",
                    'Volt': "Volt is voorstander van een Europees leger als dit leidt tot een sterkere en efficiëntere collectieve veiligheid. Zij vinden dat samenwerking de weg vooruit is in een geglobaliseerde wereld."
                }
            },
            {
                title: "Belastingstelsel",
                description: "Er moet een vlaktaks komen: één belastingtarief voor alle inkomens.",
                positions: {
                    'PVV': 'neutraal',
                    'VVD': 'eens',
                    'NSC': 'neutraal',
                    'BBB': 'neutraal',
                    'GL-PvdA': 'oneens',
                    'D66': 'oneens',
                    'SP': 'oneens',
                    'PvdD': 'oneens',
                    'CDA': 'neutraal',
                    'JA21': 'eens',
                    'SGP': 'neutraal',
                    'FvD': 'eens',
                    'DENK': 'oneens',
                    'Volt': 'oneens'
                },
                explanations: {
                    'PVV': "PVV neemt een neutrale positie in over een vlaktaks en benadrukt dat belastingheffing eerlijk moet zijn, maar de huidige progressiviteit niet overboord mag. Zij vinden dat eenvoud belangrijk is, mits het niet ten koste gaat van sociale gelijkheid.",
                    'VVD': "VVD steunt een vlaktaks omdat zij geloven dat dit de belastingdruk vereenvoudigt en ondernemerschap stimuleert. Zij vinden dat een uniforme tariefstructuur de economie kan versterken.",
                    'NSC': "NSC is neutraal en ziet zowel voordelen als nadelen in een vlaktaks. Zij pleiten voor een systeem dat eerlijk is en tegelijkertijd de economische groei bevordert.",
                    'BBB': "BBB neemt een neutrale positie in en vindt dat een vlaktaks wel eens aantrekkelijk kan zijn voor de eenvoud, maar dat er voldoende ruimte moet zijn voor aftrekposten en sociale zekerheid. Zij pleiten voor een evenwichtige hervorming.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen een vlaktaks omdat zij vrezen dat dit leidt tot minder solidariteit en hogere lasten voor lagere inkomens. Zij vinden dat een progressief stelsel eerlijker is en sociale cohesie bevordert.",
                    'D66': "D66 is tegen een vlaktaks en pleit voor een belastingstelsel dat recht doet aan verschillen in draagkracht. Zij vinden dat maatwerk en progressiviteit nodig zijn voor een eerlijke verdeling van lasten.",
                    'SP': "SP verzet zich fel tegen een vlaktaks omdat zij vrezen dat dit de ongelijkheid vergroot en de sociale bescherming ondermijnt. Zij pleiten voor een progressief stelsel waarin de rijksten meer bijdragen.",
                    'PvdD': "PvdD is neutraal maar kritisch en vindt dat een vlaktaks simplistisch kan zijn. Zij pleiten voor een systeem dat zowel eenvoud als rechtvaardigheid combineert.",
                    'CDA': "Het CDA staat neutraal tegenover de vlaktaks en vindt dat eenvoud in belastingheffing aantrekkelijk is, mits dit de sociale verdeling niet schaadt. Zij pleiten voor een hervorming die zowel efficiënt als rechtvaardig is.",
                    'JA21': "JA21 steunt een vlaktaks omdat zij geloven dat dit een eerlijk en overzichtelijk belastingstelsel oplevert. Zij vinden dat een uniform belastingtarief de economie ten goede komt.",
                    'SGP': "De SGP neemt een neutrale tot terughoudende positie in en benadrukt dat eenvoud in belastingheffing belangrijk is, maar dat solidariteit niet mag worden opgeofferd. Zij pleiten voor een balans tussen efficiëntie en sociale rechtvaardigheid.",
                    'FvD': "FvD is voorstander van een vlaktaks omdat zij geloven dat dit belastingontwijking tegengaat en de economie stimuleert. Zij vinden dat een lager en uniform tarief voor iedereen eerlijk is.",
                    'DENK': "DENK verzet zich tegen een vlaktaks omdat zij vrezen dat dit leidt tot een oneerlijke lastverdeling en minder middelen voor sociale programma's. Zij pleiten voor een progressief stelsel dat rekening houdt met verschillen in draagkracht.",
                    'Volt': "Volt is tegen een vlaktaks en pleit voor een belastingstelsel waarin de rijken proportioneel meer bijdragen. Zij vinden dat sociale rechtvaardigheid en efficiëntie hand in hand moeten gaan."
                }
            }
        ],
        answers: [],
        results: [],
        currentQuestion() {
            return this.questions[this.currentStep];
        },
        startQuiz() {
            this.screen = 'questions';
        },
        answerQuestion(answer) {
            this.answers[this.currentStep] = answer;
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
            } else {
                this.calculateResults();
            }
        },
        skipQuestion() {
            this.answers[this.currentStep] = 'skip';
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
            } else {
                this.calculateResults();
            }
        },
        previousQuestion() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },
        calculateResults() {
            const partyPositions = {};
            
            // Voor elke vraag, haal de posities van alle partijen op
            this.questions.forEach((question, index) => {
                Object.entries(question.positions).forEach(([party, position]) => {
                    if (!partyPositions[party]) {
                        partyPositions[party] = [];
                    }
                    partyPositions[party][index] = position;
                });
            });

            this.results = Object.entries(partyPositions).map(([party, positions]) => {
                let matches = 0;
                let totalAnswered = 0;

                positions.forEach((pos, index) => {
                    if (this.answers[index] !== 'skip') {
                        totalAnswered++;
                        if (this.answers[index] === pos) {
                            matches++;
                        } else if (this.answers[index] === 'neutraal' || pos === 'neutraal') {
                            matches += 0.5;
                        }
                    }
                });

                return {
                    party,
                    match: (matches / totalAnswered) * 100
                };
            });

            // Sorteer resultaten op match percentage
            this.results.sort((a, b) => b.match - a.match);
            this.screen = 'results';
        },
        restartQuiz() {
            this.screen = 'start';
            this.currentStep = 0;
            this.answers = [];
            this.results = [];
        }
    }
}
</script>


<?php require_once 'views/templates/footer.php'; ?> 