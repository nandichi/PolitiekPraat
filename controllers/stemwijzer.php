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
                    Stemwijzer 2024
                </span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Ontdek welke partij het beste bij jouw standpunten past
            </p>
        </div>

        <!-- Stemwijzer App -->
        <div class="max-w-3xl mx-auto" x-data="stemwijzer()">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="h-2 w-full bg-gray-200 rounded-full">
                    <div class="h-2 bg-gradient-to-r from-primary to-secondary rounded-full transition-all duration-300"
                         :style="'width: ' + (currentStep / totalSteps * 100) + '%'"></div>
                </div>
                <div class="text-sm text-gray-600 mt-2">
                    Vraag <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span>
                </div>
            </div>

            <!-- Start Screen -->
            <div x-show="screen === 'start'" class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Welkom bij de Stemwijzer</h2>
                <p class="text-gray-600 mb-6">
                    Deze stemwijzer helpt je om te ontdekken welke partij het beste bij jouw politieke voorkeuren past. 
                    Je krijgt een aantal stellingen te zien waarop je kunt aangeven in hoeverre je het ermee eens bent.
                </p>
                <button @click="startQuiz()" 
                        class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 px-6 rounded-xl
                               hover:opacity-90 transition-all transform hover:scale-105">
                    Start de Stemwijzer
                </button>
            </div>

            <!-- Questions Screen -->
            <div x-show="screen === 'questions'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-white rounded-2xl shadow-lg p-8 relative overflow-hidden">
                
                <!-- Decorative Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="relative">
                    <!-- Question Content -->
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4" x-text="questions[currentStep].title"></h2>
                        <p class="text-gray-600 text-lg" x-text="questions[currentStep].description"></p>
                    </div>

                    <!-- Answer Buttons -->
                    <div class="space-y-4">
                        <button @click="answerQuestion('eens')"
                                class="group w-full bg-white px-6 py-4 rounded-xl border-2 border-primary relative overflow-hidden transition-all duration-300
                                       hover:bg-primary hover:text-white hover:shadow-lg hover:shadow-primary/20 hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-gradient-to-r from-primary to-primary/80 transform scale-x-0 group-hover:scale-x-100 
                                        transition-transform origin-left"></div>
                            <div class="relative flex items-center justify-between">
                                <span class="font-semibold text-lg">Eens</span>
                                <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </button>

                        <button @click="answerQuestion('neutraal')"
                                class="group w-full bg-white px-6 py-4 rounded-xl border-2 border-gray-300 relative overflow-hidden transition-all duration-300
                                       hover:bg-gray-50 hover:border-gray-400 hover:shadow-lg hover:shadow-gray-200/50 hover:scale-[1.02]">
                            <div class="relative flex items-center justify-between">
                                <span class="font-semibold text-lg text-gray-700">Neutraal</span>
                                <svg class="w-6 h-6 text-gray-400 transform group-hover:scale-110 transition-transform" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"/>
                                </svg>
                            </div>
                        </button>

                        <button @click="answerQuestion('oneens')"
                                class="group w-full bg-white px-6 py-4 rounded-xl border-2 border-secondary relative overflow-hidden transition-all duration-300
                                       hover:bg-secondary hover:text-white hover:shadow-lg hover:shadow-secondary/20 hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-secondary/80 transform scale-x-0 group-hover:scale-x-100 
                                        transition-transform origin-left"></div>
                            <div class="relative flex items-center justify-between">
                                <span class="font-semibold text-lg">Oneens</span>
                                <svg class="w-6 h-6 transform group-hover:-translate-x-2 transition-transform" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Party Positions Button -->
                    <div x-data="{ showPositions: false }" class="mt-8">
                        <button @click="showPositions = !showPositions"
                                class="group w-full flex items-center justify-between px-6 py-3 bg-gray-50 rounded-xl
                                       hover:bg-gray-100 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-secondary/20 
                                            flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Partijstandpunten</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                 :class="{ 'rotate-180': showPositions }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="showPositions"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="mt-4 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            
                            <!-- Position Categories -->
                            <div class="grid grid-cols-1 divide-y divide-gray-100">
                                <!-- Eens -->
                                <div class="p-4">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                                        <h4 class="font-medium text-primary">Eens</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(position, party) in questions[currentStep].positions" :key="party">
                                            <div x-show="position === 'eens'"
                                                 class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary/5 hover:bg-primary/10 transition-colors">
                                                <span x-text="party" class="text-gray-900"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Neutraal -->
                                <div class="p-4">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                        <h4 class="font-medium text-gray-600">Neutraal</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(position, party) in questions[currentStep].positions" :key="party">
                                            <div x-show="position === 'neutraal'"
                                                 class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                                <span x-text="party" class="text-gray-900"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Oneens -->
                                <div class="p-4">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="w-2 h-2 rounded-full bg-secondary"></div>
                                        <h4 class="font-medium text-secondary">Oneens</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(position, party) in questions[currentStep].positions" :key="party">
                                            <div x-show="position === 'oneens'"
                                                 class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-secondary/5 hover:bg-secondary/10 transition-colors">
                                                <span x-text="party" class="text-gray-900"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between mt-8 pt-6 border-t border-gray-100">
                    <button @click="previousQuestion()" 
                            x-show="currentStep > 0"
                            class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Vorige vraag</span>
                    </button>
                    <button @click="skipQuestion()"
                            class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <span>Sla over</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Results Screen -->
            <div x-show="screen === 'results'" class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Jouw Resultaten</h2>
                <p class="text-gray-600 mb-6">
                    Op basis van je antwoorden zijn dit de partijen die het beste bij je passen:
                </p>

                <div class="space-y-4">
                    <template x-for="(result, index) in results" :key="index">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary to-secondary 
                                                flex items-center justify-center text-white font-bold text-lg"
                                         x-text="result.party.substring(0, 2)"></div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900" x-text="result.party"></h3>
                                        <p class="text-sm text-gray-600">
                                            <span x-text="Math.round(result.match)"></span>% overeenkomst
                                        </p>
                                    </div>
                                </div>
                                <div class="w-24 h-2 bg-gray-200 rounded-full">
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