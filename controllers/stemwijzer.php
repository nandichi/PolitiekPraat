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
                <!-- Header met vraagnummer en tijd -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary/10 to-primary/5 
                                       flex items-center justify-center">
                                <span class="text-lg font-semibold text-primary" x-text="currentStep + 1"></span>
                                <div class="absolute inset-0 rounded-lg border-2 border-primary/20 
                                           animate-pulse-subtle"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">Vraag</span>
                            <span class="text-xs text-gray-500">
                                van <span x-text="totalSteps"></span> stellingen
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs">
                            Nog <span x-text="Math.ceil((totalSteps - currentStep) * 0.5)"></span> min
                        </span>
                    </div>
                </div>
                
                <!-- Progress track -->
                <div class="relative">
                    <!-- Achtergrond -->
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <!-- Voortgangsbalk -->
                        <div class="h-full bg-gradient-to-r from-primary/80 via-primary to-primary/90
                                    transition-all duration-500 ease-out relative"
                             :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                            <!-- Glow effect -->
                            <div class="absolute inset-0 flex">
                                <div class="w-1/2 bg-gradient-to-r from-transparent to-white/20"></div>
                                <div class="w-1/2 bg-gradient-to-l from-transparent to-white/20"></div>
                            </div>
                            
                            <!-- Pulse effect aan het einde -->
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 
                                       bg-white rounded-full shadow-glow
                                       animate-pulse-slow"></div>
                        </div>
                    </div>

                    <!-- Stap markers -->
                    <div class="absolute top-1/2 -translate-y-1/2 w-full flex justify-between px-[1px]">
                        <template x-for="index in totalSteps" :key="index">
                            <div class="relative group">
                                <div class="w-1 h-1 rounded-full transition-all duration-300"
                                     :class="currentStep >= index - 1 ? 'bg-primary' : 'bg-gray-300'">
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full mb-2 -translate-x-1/2 left-1/2
                                           opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap">
                                        Vraag <span x-text="index"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Labels -->
                <div class="flex justify-between mt-3 text-xs">
                    <span class="text-gray-400 font-medium">Start</span>
                    <span class="text-gray-400 font-medium">Halverwege</span>
                    <span class="text-gray-400 font-medium">Einde</span>
                </div>
            </div>

            <!-- Voeg deze custom animaties toe aan je bestaande style sectie -->
            <style>
            @keyframes pulse-subtle {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            @keyframes pulse-slow {
                0%, 100% { transform: translateY(-50%) scale(1); opacity: 1; }
                50% { transform: translateY(-50%) scale(1.3); opacity: 0.6; }
            }

            .shadow-glow {
                box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.7);
            }

            .animate-pulse-subtle {
                animation: pulse-subtle 2s ease-in-out infinite;
            }

            .animate-pulse-slow {
                animation: pulse-slow 2s ease-in-out infinite;
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
                            
                            <!-- Nieuwe uitleg knop -->
                            <button @click="showExplanation = !showExplanation"
                                    class="mt-4 text-sm text-primary hover:text-primary-dark flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Uitleg over deze stelling</span>
                            </button>

                            <!-- Uitleg panel -->
                            <div x-show="showExplanation" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-4 bg-gray-50 rounded-lg p-4 text-sm">
                                <div x-text="questions[currentStep].context" 
                                     class="text-gray-700 mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-100 leading-relaxed">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                        <h4 class="font-medium text-blue-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                            Linkse partijen vinden:
                                        </h4>
                                        <p x-text="questions[currentStep].leftView" 
                                           class="text-blue-800 leading-relaxed"></p>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                        <h4 class="font-medium text-red-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                            </svg>
                                            Rechtse partijen vinden:
                                        </h4>
                                        <p x-text="questions[currentStep].rightView" 
                                           class="text-red-800 leading-relaxed"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Options -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Eens button -->
                            <button @click="answerQuestion('eens')"
                                    class="relative bg-gradient-to-r from-emerald-50 to-white border-2 border-emerald-500 rounded-xl p-6 
                                           transition-all duration-300 hover:shadow-lg hover:shadow-emerald-100 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center 
                                                    transition-transform group-hover:scale-110">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-emerald-700 group-hover:text-emerald-800">Eens</span>
                                    </div>
                                    <div class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>

                            <!-- Neutraal button -->
                            <button @click="answerQuestion('neutraal')"
                                    class="relative bg-gradient-to-r from-gray-50 to-white border-2 border-gray-300 rounded-xl p-6 
                                           transition-all duration-300 hover:shadow-lg hover:shadow-gray-100 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-lg bg-gray-400 flex items-center justify-center 
                                                    transition-transform group-hover:scale-110">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h8"/>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-600 group-hover:text-gray-700">Neutraal</span>
                                    </div>
                                    <div class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>

                            <!-- Oneens button -->
                            <button @click="answerQuestion('oneens')"
                                    class="relative bg-gradient-to-r from-rose-50 to-white border-2 border-rose-500 rounded-xl p-6 
                                           transition-all duration-300 hover:shadow-lg hover:shadow-rose-100 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-lg bg-rose-500 flex items-center justify-center 
                                                    transition-transform group-hover:scale-110">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-rose-700 group-hover:text-rose-800">Oneens</span>
                                    </div>
                                    <div class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
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
                
                <!-- Politiek kompas -->
                <div class="mb-12">
                    <h3 class="text-lg font-semibold mb-6">Jouw politieke positie</h3>
                    <div class="relative w-full h-[400px] bg-gradient-to-br from-gray-50 to-white rounded-2xl p-8 shadow-lg overflow-hidden">
                        <!-- Grid lijnen -->
                        <div class="absolute inset-0 grid grid-cols-4 grid-rows-4">
                            <template x-for="i in 4">
                                <div class="border-r border-gray-200"></div>
                            </template>
                            <template x-for="i in 4">
                                <div class="border-b border-gray-200"></div>
                            </template>
                        </div>
                        
                        <!-- Labels -->
                        <div class="absolute inset-0 p-4">
                            <div class="relative w-full h-full">
                                <!-- Progressief/Conservatief as -->
                                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 
                                            bg-gradient-to-b from-primary/20 to-transparent text-center py-2 px-4 rounded-t-lg">
                                    <span class="text-sm font-medium text-primary-dark">Progressief</span>
                                </div>
                                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1 
                                            bg-gradient-to-t from-secondary/20 to-transparent text-center py-2 px-4 rounded-b-lg">
                                    <span class="text-sm font-medium text-secondary-dark">Conservatief</span>
                                </div>
                                
                                <!-- Links/Rechts as -->
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1 
                                            bg-gradient-to-r from-blue-500/20 to-transparent text-center py-2 px-4 rounded-l-lg 
                                            transform -rotate-90 origin-right">
                                    <span class="text-sm font-medium text-blue-700">Links</span>
                                </div>
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1 
                                            bg-gradient-to-l from-red-500/20 to-transparent text-center py-2 px-4 rounded-r-lg 
                                            transform rotate-90 origin-left">
                                    <span class="text-sm font-medium text-red-700">Rechts</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Positie marker -->
                        <div class="absolute transition-all duration-500 ease-out"
                             :style="'left: ' + politicalPosition.x + '%; top: ' + politicalPosition.y + '%'">
                            <div class="relative -translate-x-1/2 -translate-y-1/2">
                                <!-- Pulse effect -->
                                <div class="absolute inset-0 animate-ping rounded-full bg-primary/30"></div>
                                <!-- Marker -->
                                <div class="relative w-6 h-6 bg-gradient-to-br from-primary to-primary-dark 
                                            rounded-full shadow-lg shadow-primary/30 border-2 border-white
                                            flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gedetailleerde resultaten -->
                <div class="space-y-6">
                    <template x-for="(result, index) in results" :key="index">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
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
                                <div class="w-full sm:w-48 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-2 bg-gradient-to-r from-primary to-secondary transition-all duration-1000"
                                         :style="'width: ' + result.match + '%'"></div>
                                </div>
                            </div>
                            
                            <!-- Belangrijkste overeenkomsten -->
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Belangrijkste overeenkomsten:</h4>
                                <div class="space-y-2">
                                    <template x-for="match in result.topMatches" :key="match.question">
                                        <div class="text-sm text-gray-600 flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span x-text="match.question"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Deel resultaten -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button @click="shareResults()"
                            class="flex-1 bg-primary text-white font-semibold py-3 px-6 rounded-xl 
                                   hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                        <span>Deel resultaten</span>
                    </button>
                    <button @click="saveResults()"
                            class="flex-1 bg-secondary text-white font-semibold py-3 px-6 rounded-xl 
                                   hover:bg-secondary-dark transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        <span>Bewaar resultaten</span>
                    </button>
                </div>
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
        showExplanation: false,
        questions: [
            {
                title: "Asielbeleid",
                description: "Nederland moet een strenger asielbeleid voeren met een asielstop en lagere immigratiecijfers.",
                context: "Bij deze stelling gaat het erom hoe Nederland omgaat met mensen die asiel aanvragen. Een strenger asielbeleid betekent dat er strengere regels komen en dat minder mensen worden toegelaten. Een asielstop betekent dat er tijdelijk helemaal geen nieuwe asielzoekers worden toegelaten. Dit onderwerp gaat over de balans tussen veiligheid, controle en humanitaire zorg.",
                leftView: "Vinden dat Nederland humaan moet blijven en vluchtelingen moet opvangen. Zij vinden dat mensen in nood geholpen moeten worden.",
                rightView: "Willen de instroom van asielzoekers beperken omdat zij vinden dat dit de druk op de samenleving verlaagt.",
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
                    'PVV': "Deze partij steunt een strenger asielbeleid met een volledige asielstop. Zij vinden dat Nederland zo de controle over migratie behoudt.",
                    'VVD': "VVD pleit voor een strengere selectie en beperking van asielaanvragen, maar met internationale samenwerking.",
                    'NSC': "NSC benadrukt dat een doordacht asielbeleid zowel veiligheid als humanitaire zorg moet waarborgen.",
                    'BBB': "BBB ondersteunt een streng asielbeleid en wil de instroom beperken door regionale opvang te stimuleren.",
                    'GL-PvdA': "GL-PvdA vindt dat humanitaire principes centraal moeten staan en verzet zich tegen een asielstop.",
                    'D66': "D66 wil een humaan maar gestructureerd asielbeleid met veilige en legale routes.",
                    'SP': "SP vindt dat het verbeteren van opvang en integratie even belangrijk is als het beperken van instroom.",
                    'PvdD': "PvdD wil een asielbeleid dat mensenrechten respecteert en aandacht heeft voor de ecologische context.",
                    'CDA': "CDA pleit voor een onderscheidend beleid met duidelijke scheiding tussen tijdelijke en permanente bescherming.",
                    'JA21': "JA21 ondersteunt een restrictief asielbeleid met strikte toelatingscriteria.",
                    'SGP': "SGP wil een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan.",
                    'FvD': "FvD pleit voor het beëindigen van het internationale asielkader en wil asielaanvragen sterk beperken.",
                    'DENK': "DENK kiest voor een humaan asielbeleid dat ook aandacht heeft voor solidariteit en internationale samenwerking.",
                    'Volt': "Volt staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert."
                }
            },
            {
                title: "Klimaatmaatregelen",
                description: "Nederland moet vooroplopen in de klimaattransitie, ook als dit op korte termijn economische groei kost.",
                context: "Deze stelling gaat over hoe snel Nederland moet overschakelen naar een klimaatvriendelijke economie. Het idee is dat we sneller moeten handelen om de opwarming van de aarde te stoppen. Dit kan betekenen dat bedrijven moeten investeren in nieuwe, duurzame technologieën en dat producten op korte termijn duurder worden. Het onderwerp gaat over de afweging tussen het beschermen van het milieu en de mogelijke economische nadelen op de korte termijn.",
                leftView: "Vinden dat Nederland snel actie moet ondernemen om de opwarming van de aarde tegen te gaan, ook als dit even wat kosten met zich meebrengt.",
                rightView: "Zien dat verduurzaming belangrijk is, maar vinden dat dit niet te snel mag gaan zodat bedrijven en burgers niet te veel last krijgen van de kosten.",
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
                    'PVV': "PVV verzet zich tegen ambitieuze klimaatmaatregelen als deze ten koste gaan van economische groei.",
                    'VVD': "VVD ondersteunt klimaatmaatregelen, maar vindt dat de economie niet op de achtergrond mag raken.",
                    'NSC': "NSC vindt zowel klimaat als economie belangrijk en pleit voor een evenwichtige aanpak.",
                    'BBB': "BBB is sceptisch over ingrijpende klimaatmaatregelen, zeker als deze de agrarische sector schaden.",
                    'GL-PvdA': "GL-PvdA is voor ambitieuze klimaatmaatregelen, ook al moet daarvoor op korte termijn wat opgeofferd worden.",
                    'D66': "D66 wil dat Nederland een leidende rol speelt in de klimaattransitie, met oog voor veiligheid en innovatie.",
                    'SP': "SP vindt dat klimaatmaatregelen eerlijk moeten worden verdeeld, zodat zowel ecologische als economische belangen worden meegenomen.",
                    'PvdD': "PvdD staat voor radicaal klimaatbeleid, ongeacht economische kortetermijnnadelen.",
                    'CDA': "CDA pleit voor een combinatie van klimaatmaatregelen en behoud van economische stabiliteit.",
                    'JA21': "JA21 wil niet dat klimaatmaatregelen de economische groei te veel hinderen.",
                    'SGP': "SGP vindt dat maatregelen verantwoord moeten zijn en de economie niet te zwaar belasten.",
                    'FvD': "FvD betwist de urgentie van de klimaatcrisis en wil geen maatregelen die de economie schaden.",
                    'DENK': "DENK wil een genuanceerde aanpak waarbij zowel klimaat als economie worden meegenomen.",
                    'Volt': "Volt pleit voor ambitieuze maatregelen en gelooft dat de lange termijn voordelen opwegen tegen de korte termijn kosten."
                }
            },
            {
                title: "Eigen Risico Zorg",
                description: "Het eigen risico in de zorg moet worden afgeschaft.",
                context: "Het eigen risico is het bedrag dat je zelf moet betalen voordat de zorgverzekering de rest van de kosten vergoedt. Momenteel is dit ongeveer 385 euro per jaar. Het idee achter deze stelling is dat iedereen direct de zorg kan krijgen zonder eerst zelf te moeten betalen, zodat vooral mensen met een laag inkomen niet worden benadeeld.",
                leftView: "Vinden dat het eigen risico vooral mensen met een laag inkomen te veel kost. Zij willen dat iedereen zonder financiële zorgen zorg kan krijgen.",
                rightView: "Vinden dat het eigen risico nodig is om de zorgkosten beheersbaar te houden en dat mensen bewuster met zorg omgaan als ze een deel zelf moeten betalen.",
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
                    'PVV': "PVV wil het eigen risico volledig afschaffen zodat iedereen direct toegang heeft tot zorg.",
                    'VVD': "VVD vindt dat het eigen risico helpt om zorgkosten beheersbaar te houden en stimuleert verantwoordelijk gebruik.",
                    'NSC': "NSC overweegt aanpassingen in plaats van volledige afschaffing om zo de betaalbaarheid te garanderen.",
                    'BBB': "BBB wil het eigen risico verlagen om de zorgtoegankelijkheid te vergroten, maar wel met een stapsgewijze aanpak.",
                    'GL-PvdA': "GL-PvdA pleit voor afschaffing om gelijke toegang tot zorg te realiseren.",
                    'D66': "D66 stelt voor het eigen risico te bevriezen en per behandeling een limiet in te stellen.",
                    'SP': "SP vindt dat het afschaffen van het eigen risico zorgt voor een eerlijker zorgsysteem.",
                    'PvdD': "PvdD wil dat zorg toegankelijk is zonder financiële drempels.",
                    'CDA': "CDA pleit voor een gerichte verlaging van het eigen risico, gekoppeld aan verantwoordelijkheid.",
                    'JA21': "JA21 vindt een zekere mate van eigen bijdrage noodzakelijk voor efficiëntie.",
                    'SGP': "SGP ziet het eigen risico als een middel om onnodig gebruik van zorg te beperken, maar met ruimte voor verlaging bij kwetsbare groepen.",
                    'FvD': "FvD ondersteunt afschaffing omdat zij geloven in een toegankelijke zorg voor iedereen.",
                    'DENK': "DENK wil het eigen risico aanzienlijk verlagen om zorg voor iedereen bereikbaar te maken.",
                    'Volt': "Volt staat open voor verlaging van het eigen risico, mits dit financieel haalbaar is."
                }
            },
            {
                title: "Kernenergie",
                description: "Nederland moet investeren in nieuwe kerncentrales als onderdeel van de energietransitie.",
                context: "Deze stelling gaat over het bouwen van nieuwe kerncentrales om elektriciteit op te wekken. Kerncentrales produceren veel stroom zonder CO2-uitstoot, maar ze zorgen ook voor radioactief afval en hoge bouwkosten. Het debat gaat over de afweging tussen een betrouwbare energievoorziening en de risico's op het gebied van veiligheid en afvalbeheer.",
                leftView: "Zijn vaak tegen kernenergie omdat ze bezorgd zijn over veiligheid en afval. Zij willen liever investeren in zon en wind.",
                rightView: "Zien kernenergie als een schone en betrouwbare bron die nodig is naast andere duurzame energiebronnen.",
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
                    'PVV': "PVV steunt investering in kerncentrales als een manier om de energievoorziening veilig te stellen.",
                    'VVD': "VVD ziet kernenergie als aanvulling op duurzame bronnen, mits veiligheid en kosten in balans zijn.",
                    'NSC': "NSC staat open voor kernenergie als het bijdraagt aan een stabiele energiemix en veiligheid gegarandeerd is.",
                    'BBB': "BBB vindt dat kernenergie een betrouwbaar onderdeel kan zijn van de energietransitie.",
                    'GL-PvdA': "GL-PvdA verwerpt kernenergie vanwege de risico's en lange doorlooptijden.",
                    'D66': "D66 bekijkt kernenergie kritisch en vindt dat innovatie en veiligheid centraal moeten staan.",
                    'SP': "SP wil geen investeringen in kerncentrales en besteedt liever publieke middelen aan duurzame energie.",
                    'PvdD': "PvdD vindt kernenergie verouderd en wil meer inzetten op hernieuwbare energiebronnen.",
                    'CDA': "CDA ziet kernenergie als een onderdeel van een brede energiemix, mits goed gereguleerd.",
                    'JA21': "JA21 steunt kernenergie als een manier om energiezekerheid en emissiereductie te realiseren.",
                    'SGP': "SGP ziet kernenergie als een middel om de afhankelijkheid van fossiele brandstoffen te verminderen.",
                    'FvD': "FvD wil investeren in kernenergie als alternatief voor fossiele brandstoffen.",
                    'DENK': "DENK staat open voor kernenergie als het veilig en verantwoord wordt ingezet.",
                    'Volt': "Volt geeft de voorkeur aan hernieuwbare energie, maar staat open voor kernenergie bij strenge veiligheidseisen."
                }
            },
            {
                title: "Woningmarkt",
                description: "Er moet een nationaal bouwprogramma komen waarbij de overheid zelf woningen gaat bouwen.",
                context: "Deze stelling richt zich op het oplossen van het tekort aan betaalbare woningen. In plaats van dat de markt zelf zorgt voor voldoende huizen, wordt voorgesteld dat de overheid een programma start om zelf woningen te bouwen. Het idee is dat zo meer controle is over de bouw en de prijzen, vooral voor sociale huurwoningen.",
                leftView: "Vinden dat de overheid moet ingrijpen omdat de markt er niet in slaagt voldoende betaalbare woningen te bouwen. Zij pleiten voor sociale huurwoningen.",
                rightView: "Vinden dat de markt dit beter kan oplossen en dat de overheid alleen regels moet versoepelen.",
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
                    'PVV': "PVV steunt een overheidsprogramma om de woningnood aan te pakken.",
                    'VVD': "VVD vindt dat de overheid niet te veel moet ingrijpen en de markt beter functioneert.",
                    'NSC': "NSC pleit voor een mix van publieke en private initiatieven.",
                    'BBB': "BBB ziet kansen in een overheidsprogramma, zeker op het platteland.",
                    'GL-PvdA': "GL-PvdA wil dat de overheid de sociale huurmarkt versterkt.",
                    'D66': "D66 vindt dat de overheid samenwerkt met private partijen voor duurzaam bouwen.",
                    'SP': "SP steunt overheidsinitiatieven om huisvesting voor iedereen toegankelijk te maken.",
                    'PvdD': "PvdD wil een structurele overheidsaanpak voor duurzame en betaalbare woningen.",
                    'CDA': "CDA pleit voor een gerichte overheidsrol bij de bouw voor kwetsbare groepen.",
                    'JA21': "JA21 verkiest marktgedreven oplossingen met subsidieregelingen.",
                    'SGP': "SGP vindt dat woningcorporaties voorrang moeten krijgen voor sociale stabiliteit.",
                    'FvD': "FvD wil dat de regels versoepeld worden zodat de markt soepel werkt.",
                    'DENK': "DENK steunt een actieve rol van de overheid om ongelijkheid in de woningmarkt tegen te gaan.",
                    'Volt': "Volt wil dat overheid en markt samenwerken voor innovatieve oplossingen."
                }
            },
            {
                title: "Minimumloon",
                description: "Het minimumloon moet verder omhoog naar 16 euro per uur.",
                context: "Deze stelling gaat over het verhogen van het minimumloon, het laagste loon dat werkgevers wettelijk moeten betalen. Een hoger minimumloon kan zorgen voor meer inkomen voor werknemers, maar kan ook leiden tot hogere kosten voor bedrijven en mogelijk minder banen. Hier gaat het dus om de afweging tussen sociale zekerheid en economische haalbaarheid.",
                leftView: "Vinden dat een hoger minimumloon nodig is om werknemers een eerlijk loon te geven en armoede te voorkomen.",
                rightView: "Zien risico's in een verhoging omdat het banenverlies of hogere kosten voor werkgevers kan veroorzaken.",
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
                    'PVV': "PVV vindt dat economische realiteiten meegewogen moeten worden bij een verhoging.",
                    'VVD': "VVD is tegen een verhoging omdat dit banen kan schaden.",
                    'NSC': "NSC pleit voor een stapsgewijze benadering, afhankelijk van de economie.",
                    'BBB': "BBB vindt dat het minimumloon in balans moet zijn met economische realiteit.",
                    'GL-PvdA': "GL-PvdA steunt een verhoging voor een eerlijker loon en sociale rechtvaardigheid.",
                    'D66': "D66 is voor een verhoging als de economische omstandigheden dat toelaten.",
                    'SP': "SP vindt dat een hoger minimumloon de koopkracht en gelijkheid versterkt.",
                    'PvdD': "PvdD steunt een verhoging om een eerlijk inkomen voor iedereen te garanderen.",
                    'CDA': "CDA vindt dat een verhoging gekoppeld moet zijn aan productiviteitswinsten.",
                    'JA21': "JA21 is tegen een verhoging uit vrees voor banenverlies.",
                    'SGP': "SGP vindt dat economische draagkracht beschermd moet worden.",
                    'FvD': "FvD is tegen een verhoging vanwege extra kosten voor werkgevers.",
                    'DENK': "DENK pleit voor een verhoging om inkomensongelijkheid tegen te gaan.",
                    'Volt': "Volt steunt een verhoging als dit gepaard gaat met structurele investeringen."
                }
            },
            {
                title: "Europese Unie",
                description: "Nederland moet uit de Europese Unie stappen (Nexit).",
                context: "Deze stelling gaat over het verlaten van de Europese Unie. Het debat richt zich op de vraag of Nederland meer regie over eigen beleid krijgt door uit de EU te stappen, of dat samenwerking binnen de EU juist zorgt voor economische en veiligheidsvoordelen. Hier wordt nagedacht over nationale soevereiniteit versus internationale samenwerking.",
                leftView: "Zien dat een vertrek de nationale soevereiniteit versterkt en Nederland meer regie geeft over eigen beleid.",
                rightView: "Vinden dat samenwerken binnen de EU belangrijk is voor de economie en veiligheid, ondanks enkele nadelen.",
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
                    'PVV': "PVV wil de nationale soevereiniteit herstellen door uit de EU te stappen.",
                    'VVD': "VVD vindt dat samenwerking binnen de EU essentieel is voor veiligheid en economie.",
                    'NSC': "NSC ziet zowel kansen als risico's in het EU-lidmaatschap.",
                    'BBB': "BBB pleit voor pragmatische samenwerking binnen de EU.",
                    'GL-PvdA': "GL-PvdA vindt dat de EU cruciaal is voor solidariteit en mensenrechten.",
                    'D66': "D66 wil de Europese samenwerking verdiepen in plaats van stoppen.",
                    'SP': "SP vindt dat internationale solidariteit belangrijk is om uitdagingen aan te pakken.",
                    'PvdD': "PvdD ziet de EU als essentieel voor milieubescherming en duurzaamheid.",
                    'CDA': "CDA vindt dat Nederland in de EU moet blijven met meer aandacht voor nationale belangen.",
                    'JA21': "JA21 pleit voor hervorming van de EU in plaats van vertrek.",
                    'SGP': "SGP vindt dat de samenwerking binnen Europa de vrede en veiligheid bevordert.",
                    'FvD': "FvD wil uit de EU stappen om bureaucratische beperkingen te doorbreken.",
                    'DENK': "DENK vindt samenwerking belangrijk om internationale uitdagingen samen aan te pakken.",
                    'Volt': "Volt ziet de EU als een platform voor gezamenlijke vooruitgang."
                }
            },
            {
                title: "Defensie-uitgaven",
                description: "Nederland moet de defensie-uitgaven verhogen naar minimaal 2% van het BBP.",
                context: "Deze stelling gaat over het verhogen van het geld dat Nederland uitgeeft aan defensie. Meer uitgaven kunnen zorgen voor een sterkere militaire positie en internationale veiligheid, maar het geld komt ten koste van andere uitgaven zoals zorg en onderwijs. Het gaat hier dus om de afweging tussen veiligheid en andere maatschappelijke behoeften.",
                leftView: "Zien een hogere uitgave als een investering in nationale en internationale veiligheid.",
                rightView: "Vinden dat extra geld voor defensie ten koste kan gaan van sociale voorzieningen en andere prioriteiten.",
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
                    'PVV': "PVV vindt dat veiligheid ook op andere manieren bereikt kan worden dan door een forse budgetverhoging.",
                    'VVD': "VVD steunt een verhoging om de internationale positie en veiligheid te waarborgen.",
                    'NSC': "NSC wil dat de uitgaven efficiënt worden ingezet als onderdeel van een bredere veiligheidsstrategie.",
                    'BBB': "BBB vindt dat de uitgaven in lijn moeten zijn met de daadwerkelijke dreigingen.",
                    'GL-PvdA': "GL-PvdA ziet geen reden voor een forse verhoging als dit sociale uitgaven schaadt.",
                    'D66': "D66 wil investeren in defensie om beter voorbereid te zijn op crises.",
                    'SP': "SP vindt dat geld beter besteed kan worden aan sociale programma's.",
                    'PvdD': "PvdD pleit voor transparantie en efficiëntie bij de besteding van defensiegeld.",
                    'CDA': "CDA steunt een verhoging, mits dit gepaard gaat met moderne investeringen.",
                    'JA21': "JA21 vindt dat Nederland zijn verantwoordelijkheid in internationale veiligheid moet waarmaken.",
                    'SGP': "SGP wil dat de bescherming van burgers vooropstaat bij de verhoging van de uitgaven.",
                    'FvD': "FvD vindt dat defensie efficiënt en doelgericht moet opereren, zonder extra verhoging.",
                    'DENK': "DENK pleit voor een kritische benadering waarbij maatschappelijke behoeften meewegen.",
                    'Volt': "Volt steunt een verhoging als dit leidt tot betere Europese samenwerking in veiligheid."
                }
            },
            {
                title: "Stikstofbeleid",
                description: "Het huidige stikstofbeleid moet worden versoepeld om boeren meer ruimte te geven.",
                context: "Deze stelling gaat over het aanpassen van de regels omtrent stikstof. Huidige regels zijn erg streng en kunnen boeren belemmeren in hun werkzaamheden. Versoepeling zou hen meer ruimte geven om economisch te floreren, maar dit kan ook nadelige gevolgen hebben voor natuur en milieu. Het debat gaat dus over de balans tussen agrarische belangen en de bescherming van natuur en biodiversiteit.",
                leftView: "Zien versoepeling als een manier om de economische positie van boeren te verbeteren.",
                rightView: "Vinden dat natuur- en milieubescherming voorop moet staan en de regels niet te los mogen worden.",
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
                    'PVV': "PVV wil versoepeling om boeren meer ruimte te geven, zodat hun economische belangen beschermd worden.",
                    'VVD': "VVD steunt versoepeling, mits natuur ook beschermd blijft.",
                    'NSC': "NSC vindt dat er een evenwicht moet komen tussen de belangen van boeren en de natuur.",
                    'BBB': "BBB vindt dat de huidige regels te streng zijn en boeren kansen ontnemen.",
                    'GL-PvdA': "GL-PvdA vindt dat natuur en klimaatbescherming niet ondergeschikt mogen worden gemaakt.",
                    'D66': "D66 wil inzetten op technologische innovaties in de landbouw in plaats van versoepeling.",
                    'SP': "SP pleit voor een beleid dat zowel de agrarische sector als de natuur ondersteunt.",
                    'PvdD': "PvdD vindt dat de focus moet liggen op een duurzame herinrichting van de landbouw.",
                    'CDA': "CDA vindt dat boeren ondersteund moeten worden, maar de natuur ook beschermd moet worden.",
                    'JA21': "JA21 steunt versoepeling zodat boeren economisch kunnen floreren.",
                    'SGP': "SGP vindt dat versoepeling bijdraagt aan de leefbaarheid in landelijke gebieden.",
                    'FvD': "FvD wil versoepeling om boeren te beschermen tegen te strenge milieuregels.",
                    'DENK': "DENK pleit voor een inclusieve dialoog over duurzame landbouw.",
                    'Volt': "Volt wil juist een integrale aanpak met natuurherstel en innovatie in de landbouw."
                }
            },
            {
                title: "Studiefinanciering",
                description: "De basisbeurs voor studenten moet worden verhoogd.",
                context: "Deze stelling gaat over het verhogen van de studiefinanciering voor studenten. Een hogere basisbeurs kan studenten helpen om zich beter op hun studie te concentreren, zonder zich zorgen te maken over geld. Dit kost wel extra geld aan de overheid, maar kan leiden tot meer kansen in het onderwijs.",
                leftView: "Vinden dat een hogere basisbeurs studenten helpt om zich op hun studie te concentreren zonder financiële zorgen.",
                rightView: "Vinden dat een verhoging extra kosten met zich meebrengt en dat er ook gekeken moet worden naar efficiëntie in het systeem.",
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
                    'PVV': "PVV wil meer studiefinanciering om de toegankelijkheid van hoger onderwijs te verbeteren.",
                    'VVD': "VVD is tegen een verhoging omdat dit de studiekosten kan verhogen.",
                    'NSC': "NSC wil dat de financiering in balans is met maatschappelijke realiteiten.",
                    'BBB': "BBB vindt dat een hogere basisbeurs de financiële druk op studenten verlaagt.",
                    'GL-PvdA': "GL-PvdA ziet een verhoging als een investering in de toekomst van jongeren.",
                    'D66': "D66 wil modernisering van het onderwijssysteem samen met een verhoging van de financiering.",
                    'SP': "SP steunt een verhoging om de sociale gelijkheid in het onderwijs te bevorderen.",
                    'PvdD': "PvdD vindt dat een hogere basisbeurs de focus op studie bevordert.",
                    'CDA': "CDA pleit voor aanpassing aan de economische realiteit, met behoud van efficiëntie.",
                    'JA21': "JA21 wil voorzichtig zijn met verhoging vanwege mogelijke extra kosten.",
                    'SGP': "SGP steunt een verhoging als dit leidt tot structurele verbeteringen in het onderwijs.",
                    'FvD': "FvD wil een verhoging als dit jongeren meer kansen biedt, maar met strenge criteria.",
                    'DENK': "DENK ziet een hogere basisbeurs als een manier om ongelijkheden in het onderwijs te verkleinen.",
                    'Volt': "Volt vindt dat meer investeringen in onderwijs de toegankelijkheid vergroten."
                }
            },
            {
                title: "Belastingen",
                description: "De belastingen voor grote bedrijven moeten omhoog.",
                context: "Deze stelling gaat over het verhogen van de belastingen voor grote bedrijven. Het doel hiervan is dat bedrijven een groter deel bijdragen aan de samenleving. Hierdoor komt er meer geld beschikbaar voor publieke voorzieningen zoals zorg, onderwijs en infrastructuur. Tegelijkertijd is er zorg dat te hoge belastingen de concurrentiepositie van bedrijven negatief beïnvloeden.",
                leftView: "Vinden dat grote bedrijven meer moeten bijdragen aan de samenleving zodat er extra geld is voor publieke zaken.",
                rightView: "Vinden dat hogere belastingen voor bedrijven de concurrentie en innovatie kunnen schaden.",
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
                    'PVV': "PVV wil dat grote bedrijven extra moeten bijdragen voor een sterkere overheid.",
                    'VVD': "VVD vindt dat te hoge belastingen innovatie en groei belemmeren.",
                    'NSC': "NSC pleit voor een weloverwogen belastingbeleid dat ook de economie beschermt.",
                    'BBB': "BBB vindt dat er een balans moet zijn tussen bijdragen en concurrentiekracht.",
                    'GL-PvdA': "GL-PvdA ziet hogere belastingen als een manier om sociale ongelijkheid te verkleinen.",
                    'D66': "D66 wil dat extra opbrengsten geïnvesteerd worden in innovatie en duurzaamheid.",
                    'SP': "SP vindt dat de winsten van multinationals eerlijker verdeeld moeten worden.",
                    'PvdD': "PvdD vindt dat grote bedrijven hun maatschappelijke verantwoordelijkheid moeten nemen.",
                    'CDA': "CDA pleit voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.",
                    'JA21': "JA21 vreest dat hogere belastingen innovatie en werkgelegenheid in de weg staan.",
                    'SGP': "SGP wil dat stabiliteit in het belastingstelsel behouden blijft.",
                    'FvD': "FvD vindt dat een lager tarief juist investeringen stimuleert.",
                    'DENK': "DENK wil dat multinationals hun eerlijke deel bijdragen aan de samenleving.",
                    'Volt': "Volt steunt een verhoging als dit bijdraagt aan duurzame investeringen."
                }
            },
            {
                title: "AOW-leeftijd",
                description: "De AOW-leeftijd moet worden verlaagd naar 65 jaar.",
                context: "Deze stelling gaat over het verlagen van de AOW-leeftijd, oftewel de leeftijd waarop mensen met pensioen kunnen gaan. Een lagere AOW-leeftijd kan ervoor zorgen dat ouderen eerder met pensioen kunnen gaan en meer rust ervaren. Tegelijkertijd kan dit de financiële druk op het pensioenstelsel vergroten, omdat er dan langer pensioenuitkeringen gedaan moeten worden.",
                leftView: "Vinden dat een lagere AOW-leeftijd nodig is voor een eerlijker pensioen en om ouderen meer rust te geven.",
                rightView: "Vinden dat de AOW-leeftijd aangepast moet worden aan de stijgende levensverwachting en financieel houdbaar moet zijn.",
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
                    'PVV': "PVV wil een verlaging zodat mensen eerder met pensioen kunnen.",
                    'VVD': "VVD vindt dat de AOW-leeftijd in lijn moet blijven met de levensverwachting.",
                    'NSC': "NSC wil een afweging maken tussen gezondheid en economische haalbaarheid.",
                    'BBB': "BBB pleit voor een pensioenleeftijd die past bij de economische realiteit.",
                    'GL-PvdA': "GL-PvdA vindt dat flexibiliteit belangrijk is, maar niet ten koste van de zekerheid.",
                    'D66': "D66 wil dat de AOW-leeftijd geleidelijk stijgt gezien de demografische ontwikkelingen.",
                    'SP': "SP steunt een verlaging zodat ouderen eerder kunnen genieten van hun pensioen.",
                    'PvdD': "PvdD vindt dat ouderen niet onnodig lang moeten doorwerken.",
                    'CDA': "CDA wil een pensioenstelsel dat maatwerk biedt in plaats van een vaste leeftijd.",
                    'JA21': "JA21 vindt dat de economische haalbaarheid voorop moet staan.",
                    'SGP': "SGP pleit voor een stabiel en duurzaam pensioenstelsel.",
                    'FvD': "FvD steunt een verlaging zodat de levenskwaliteit van ouderen verbetert.",
                    'DENK': "DENK wil dat een lagere pensioenleeftijd de druk op werkenden vermindert.",
                    'Volt': "Volt vindt dat de AOW-leeftijd realistisch moet zijn, rekening houdend met demografie."
                }
            },
            {
                title: "Sociale Huurwoningen",
                description: "Woningcorporaties moeten voorrang krijgen bij het bouwen van nieuwe woningen.",
                context: "Deze stelling gaat over wie de hoofdrol moet spelen bij de bouw van nieuwe woningen. Het idee is dat woningcorporaties, die zich richten op sociale huurwoningen, voorrang krijgen. Hierdoor wordt er extra aandacht besteed aan betaalbare huisvesting voor mensen met een laag inkomen. Er wordt nagedacht over de rol van de overheid versus de markt in het oplossen van de woningnood.",
                leftView: "Vinden dat woningcorporaties als eerste aan de beurt moeten komen om de woningnood voor mensen met lage inkomens te verlichten.",
                rightView: "Vinden dat de markt dit beter kan oplossen en dat er ruimte moet zijn voor zowel publieke als private initiatieven.",
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
                    'PVV': "PVV steunt voorrang voor woningcorporaties om de woningnood aan te pakken.",
                    'VVD': "VVD wil een marktgerichte aanpak zonder dwingende voorrang.",
                    'NSC': "NSC ziet zowel publieke als private initiatieven als noodzakelijk voor de woningmarkt.",
                    'BBB': "BBB vindt dat voorrang de sociale cohesie kan bevorderen.",
                    'GL-PvdA': "GL-PvdA wil dat woningcorporaties helpen bij het oplossen van de woningnood.",
                    'D66': "D66 pleit voor samenwerking tussen overheid en private sector.",
                    'SP': "SP wil dat de overheid een actieve rol speelt in het waarborgen van betaalbare woningen.",
                    'PvdD': "PvdD steunt een sterke overheidsaanpak voor duurzame en betaalbare woningen.",
                    'CDA': "CDA pleit voor een gerichte overheidsrol bij de bouw voor kwetsbare groepen.",
                    'JA21': "JA21 verkiest marktgedreven oplossingen met subsidieregelingen.",
                    'SGP': "SGP vindt dat woningcorporaties de sociale stabiliteit kunnen waarborgen.",
                    'FvD': "FvD wil dat de regels versoepeld worden zodat de markt soepel werkt.",
                    'DENK': "DENK steunt een actieve rol van de overheid om ongelijkheid in de woningmarkt tegen te gaan.",
                    'Volt': "Volt wil dat overheid en markt samenwerken voor innovatieve oplossingen."
                }
            },
            {
                title: "Ontwikkelingshulp",
                description: "Nederland moet bezuinigen op ontwikkelingshulp.",
                context: "Deze stelling gaat over het verminderen van de financiële hulp aan ontwikkelingslanden. Het idee is dat Nederland eerst zijn eigen problemen moet oplossen voordat het geld geeft aan andere landen. Tegelijkertijd speelt internationale solidariteit een rol. Er wordt dus gekeken naar de afweging tussen binnenlandse belangen en internationale hulpverplichtingen.",
                leftView: "Vinden dat Nederland eerst eigen prioriteiten moet oplossen en daarom minder geld aan ontwikkelingshulp moet uitgeven.",
                rightView: "Vinden dat ontwikkelingshulp belangrijk is voor internationale solidariteit en het bestrijden van armoede.",
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
                    'PVV': "PVV wil dat de middelen beter in Nederland gebruikt worden.",
                    'VVD': "VVD steunt bezuinigingen om de eigen financiën op orde te krijgen.",
                    'NSC': "NSC wil dat ontwikkelingshulp doelbewust en strategisch wordt ingezet.",
                    'BBB': "BBB vindt dat de focus eerst op de eigen economie moet liggen.",
                    'GL-PvdA': "GL-PvdA vindt ontwikkelingshulp belangrijk voor internationale solidariteit.",
                    'D66': "D66 wil behoud van hulp als onderdeel van internationale samenwerking.",
                    'SP': "SP vindt dat Nederland meer verantwoordelijkheid heeft richting kwetsbare landen.",
                    'PvdD': "PvdD ziet ontwikkelingshulp als een morele verplichting.",
                    'CDA': "CDA pleit voor doelgerichte hulp in samenwerking met internationale partners.",
                    'JA21': "JA21 vindt dat hulp kritisch geëvalueerd moet worden op effectiviteit.",
                    'SGP': "SGP ziet hulp als essentieel voor internationale solidariteit, maar met voorwaarden.",
                    'FvD': "FvD wil dat het budget beter in eigen land wordt gebruikt.",
                    'DENK': "DENK pleit voor een humaan ontwikkelingsbeleid dat ongelijkheid tegengaat.",
                    'Volt': "Volt wil ontwikkelingshulp juist versterken als onderdeel van een duurzame agenda."
                }
            },
            {
                title: "Zorgverzekering",
                description: "Er moet één publieke zorgverzekering komen in plaats van verschillende private verzekeraars.",
                context: "Deze stelling gaat over de organisatie van de zorgverzekering. Momenteel kiezen mensen tussen verschillende private zorgverzekeraars. Het voorstel is om één publieke zorgverzekering in te voeren. Dit kan zorgen voor meer solidariteit en lagere kosten, maar kan ook de keuzevrijheid verminderen. De discussie gaat over de balans tussen efficiëntie en vrijheid in de zorg.",
                leftView: "Zien één publieke zorgverzekering als een manier om de zorg toegankelijker en eerlijker te maken.",
                rightView: "Vinden dat meerdere verzekeraars voor concurrentie zorgen en innovatie stimuleren.",
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
                    'PVV': "PVV ziet geen meerwaarde in één publieke zorgverzekering en waardeert marktwerking in de zorg.",
                    'VVD': "VVD vreest dat een publieke monopoliestructuur innovatie en keuzevrijheid beperkt.",
                    'NSC': "NSC vindt dat zowel publieke als private systemen hun voordelen hebben.",
                    'BBB': "BBB ziet in één verzekering mogelijkheden voor lagere administratieve lasten, maar waarschuwt voor centralisatie.",
                    'GL-PvdA': "GL-PvdA wil dat solidariteit en gelijke toegang tot zorg centraal staan.",
                    'D66': "D66 verzet zich tegen een monopolie in de zorgverzekering.",
                    'SP': "SP ziet één publieke verzekering als een middel om de zorgkosten te verlagen.",
                    'PvdD': "PvdD wil een menselijker en efficiënter zorgsysteem via één publieke verzekering.",
                    'CDA': "CDA vindt dat een mix van publieke en private aanbieders de beste balans biedt.",
                    'JA21': "JA21 vreest dat één verzekering leidt tot minder keuzevrijheid voor burgers.",
                    'SGP': "SGP hecht waarde aan een pluralistisch systeem met concurrentie.",
                    'FvD': "FvD ziet de huidige markt als bewezen werkbaar.",
                    'DENK': "DENK steunt één publieke verzekering als middel om structurele ongelijkheden tegen te gaan.",
                    'Volt': "Volt pleit voor een zorgsysteem dat toegankelijk en transparant blijft, ongeacht de vorm."
                }
            },
            {
                title: "Referendum",
                description: "Er moet een bindend referendum komen waarbij burgers kunnen meebeslissen over belangrijke onderwerpen.",
                context: "Deze stelling gaat over het vergroten van de directe invloed van burgers op belangrijke besluiten. Met een bindend referendum kunnen mensen direct stemmen over belangrijke onderwerpen, in plaats van dat politici alle beslissingen nemen. Het idee is dat dit de democratie versterkt, maar het kan ook leiden tot vertraging in het besluitvormingsproces.",
                leftView: "Vinden dat burgers meer directe invloed moeten hebben op beleidskeuzes door een bindend referendum.",
                rightView: "Vinden dat vertegenwoordigde democratie beter werkt en dat referenda voor vertraging kunnen zorgen.",
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
                    'PVV': "PVV wil dat burgers direct meebeslissen om de democratische legitimiteit te versterken.",
                    'VVD': "VVD vreest dat referenda de besluitvorming vertragen en polariserend werken.",
                    'NSC': "NSC vindt dat referenda een aanvulling kunnen zijn, mits goed georganiseerd.",
                    'BBB': "BBB ziet in referenda een manier om het vertrouwen in de politiek te vergroten.",
                    'GL-PvdA': "GL-PvdA pleit voor voorwaarden en goede voorlichting bij referenda.",
                    'D66': "D66 vindt dat referenda vooral bij nationale belangrijke onderwerpen moeten worden ingezet.",
                    'SP': "SP vindt dat meer directe inspraak leidt tot meer democratische betrokkenheid.",
                    'PvdD': "PvdD wil dat burgers beter geïnformeerd worden over de consequenties van hun keuzes.",
                    'CDA': "CDA vindt dat vertegenwoordigde democratie stabieler is dan te vaak referenda.",
                    'JA21': "JA21 wil dat burgers meer directe invloed krijgen, mits dit goed gefaciliteerd wordt.",
                    'SGP': "SGP vreest dat referenda leiden tot simplistische besluitvorming.",
                    'FvD': "FvD ziet referenda als een manier om de macht van politieke elites te beperken.",
                    'DENK': "DENK vindt dat referenda de stem van minderheden kunnen versterken.",
                    'Volt': "Volt pleit voor een bindend referendum als aanvulling op de representatieve democratie."
                }
            },
            {
                title: "Winstbelasting",
                description: "De winstbelasting voor grote bedrijven moet omhoog.",
                context: "Deze stelling gaat over het verhogen van de belasting op de winst van grote bedrijven. Het idee is dat door grotere bedrijven meer te laten bijdragen, er extra geld beschikbaar komt voor publieke voorzieningen en sociale zaken. Er wordt gekeken naar de vraag of een hogere belastingdruk de economische groei negatief beïnvloedt.",
                leftView: "Vinden dat grote bedrijven meer moeten bijdragen aan de samenleving, zodat er extra geld is voor publieke zaken.",
                rightView: "Vinden dat een hogere winstbelasting de concurrentiepositie van bedrijven kan schaden.",
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
                    'PVV': "PVV vindt dat grote bedrijven extra moeten bijdragen voor een sterkere overheid.",
                    'VVD': "VVD is tegen een verhoging omdat dit de concurrentie kan schaden.",
                    'NSC': "NSC pleit voor een weloverwogen beleid dat ook de economie beschermt.",
                    'BBB': "BBB vindt dat er een balans moet zijn tussen bijdragen en concurrentiekracht.",
                    'GL-PvdA': "GL-PvdA ziet hogere belastingen als een manier om sociale ongelijkheid te verkleinen.",
                    'D66': "D66 wil dat extra opbrengsten geïnvesteerd worden in innovatie en duurzaamheid.",
                    'SP': "SP vindt dat de winsten van multinationals eerlijker verdeeld moeten worden.",
                    'PvdD': "PvdD vindt dat grote bedrijven hun maatschappelijke verantwoordelijkheid moeten nemen.",
                    'CDA': "CDA pleit voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.",
                    'JA21': "JA21 vreest dat hogere belastingen innovatie en werkgelegenheid in de weg staan.",
                    'SGP': "SGP wil dat stabiliteit in het belastingstelsel behouden blijft.",
                    'FvD': "FvD vindt dat een lager tarief juist investeringen stimuleert.",
                    'DENK': "DENK wil dat multinationals hun eerlijke deel bijdragen aan de samenleving.",
                    'Volt': "Volt steunt een verhoging als dit bijdraagt aan duurzame investeringen."
                }
            },
            {
                title: "Legalisering Drugs",
                description: "Alle drugs moeten worden gelegaliseerd en gereguleerd.",
                context: "Deze stelling gaat over het volledig legaliseren van alle drugs. Legalering betekent dat de productie, verkoop en consumptie van drugs niet langer illegaal is, maar gereguleerd wordt. Het doel is om de kwaliteit en veiligheid te verbeteren en criminaliteit te verminderen, maar er bestaat ook zorg dat dit tot meer misbruik kan leiden.",
                leftView: "Zien legalisering als een manier om de kwaliteit en veiligheid van drugs te bewaken en criminaliteit te verminderen.",
                rightView: "Vinden dat legalisering kan leiden tot meer maatschappelijke problemen en misbruik.",
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
                    'PVV': "PVV is fel tegen legalisering omdat zij vrezen voor meer maatschappelijke problemen.",
                    'VVD': "VVD vreest dat legalisering leidt tot meer drugscriminaliteit.",
                    'NSC': "NSC ziet zowel risico's als kansen, afhankelijk van de aanpak.",
                    'BBB': "BBB vindt dat het huidige verbod noodzakelijk is voor de volksgezondheid.",
                    'GL-PvdA': "GL-PvdA kijkt neutraal en wil eerst de gevolgen goed onderzoeken.",
                    'D66': "D66 is voor legalisering zodat er betere controle komt op kwaliteit en veiligheid.",
                    'SP': "SP vindt dat de aanpak van drugs vooral gericht moet zijn op preventie en hulp.",
                    'PvdD': "PvdD ziet legalisering als een manier om schade te beperken, mits goed geregeld.",
                    'CDA': "CDA is tegen legalisering uit vrees voor negatieve volksgezondheidsgevolgen.",
                    'JA21': "JA21 is fel tegen legalisering om jongeren te beschermen.",
                    'SGP': "SGP verzet zich tegen legalisering en wil inzetten op preventie en handhaving.",
                    'FvD': "FvD wil eerst meer onderzoek voordat een besluit wordt genomen.",
                    'DENK': "DENK is tegen legalisering omdat zij vrezen voor extra maatschappelijke kwetsbaarheden.",
                    'Volt': "Volt steunt gereguleerde legalisering als dit leidt tot betere controle en minder criminaliteit."
                }
            },
            {
                title: "Kilometerheffing",
                description: "Er moet een kilometerheffing komen voor autorijders.",
                context: "Deze stelling gaat over het invoeren van een heffing per gereden kilometer voor auto's. Het doel is om mensen aan te moedigen minder te rijden, waardoor het milieu minder belast wordt en er minder files ontstaan. Tegelijkertijd betekent dit extra kosten voor bestuurders, wat vooral mensen met een laag inkomen kan raken.",
                leftView: "Zien de kilometerheffing als een stimulans voor duurzamer vervoer en een manier om het milieu te beschermen.",
                rightView: "Vinden dat extra kosten voor autorijders vooral mensen met een laag inkomen benadelen.",
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
                    'PVV': "PVV is fel tegen de kilometerheffing omdat zij vrezen dat dit burgers oneerlijk treft.",
                    'VVD': "VVD vindt dat de heffing de mobiliteit en economie kan schaden.",
                    'NSC': "NSC wil dat er eerst een analyse komt van de sociale en economische impact.",
                    'BBB': "BBB is tegen de heffing omdat dit vooral een last voor de burger betekent.",
                    'GL-PvdA': "GL-PvdA steunt de heffing als middel om duurzame mobiliteit te bevorderen.",
                    'D66': "D66 ziet de heffing als een stap naar een schoner vervoerssysteem.",
                    'SP': "SP vindt dat de heffing vooral mensen met een laag inkomen treft.",
                    'PvdD': "PvdD steunt de invoering als het helpt de ecologische voetafdruk te verkleinen.",
                    'CDA': "CDA vindt dat de heffing alleen werkt als er duidelijke voordelen voor het milieu zijn.",
                    'JA21': "JA21 is tegen de heffing omdat deze de mobiliteit belemmert.",
                    'SGP': "SGP vreest dat de heffing te veel extra kosten voor burgers veroorzaakt.",
                    'FvD': "FvD vindt dat een extra belasting op mobiliteit de economie kan schaden.",
                    'DENK': "DENK pleit voor een debat over compensatie voor kwetsbare groepen als een heffing wordt ingevoerd.",
                    'Volt': "Volt steunt de heffing als dit gepaard gaat met eerlijke compensatiemechanismen."
                }
            },
            {
                title: "Kinderopvang",
                description: "Kinderopvang moet gratis worden voor alle ouders.",
                context: "Deze stelling gaat over het gratis maken van kinderopvang. Het idee is dat ouders hierdoor makkelijker werk en gezin kunnen combineren en dat kinderen gelijke kansen krijgen. Gratis kinderopvang betekent extra kosten voor de overheid, maar kan ook leiden tot een betere ontwikkeling van kinderen en meer participatie van ouders op de arbeidsmarkt.",
                leftView: "Vinden dat gratis kinderopvang kansen voor kinderen creëert en ouders ontzorgt.",
                rightView: "Vinden dat gratis kinderopvang te hoge kosten met zich meebrengt en dat een eigen bijdrage nodig is.",
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
                    'PVV': "PVV vindt dat kinderopvang niet volledig gratis hoeft te zijn zodat er ook sprake blijft van eigen verantwoordelijkheid.",
                    'VVD': "VVD is tegen gratis kinderopvang uit vrees voor een te grote overheidsbemoeienis en hogere belastingen.",
                    'NSC': "NSC zoekt naar een balans tussen toegankelijkheid en economische haalbaarheid.",
                    'BBB': "BBB vindt dat regionale initiatieven kunnen helpen om de kosten laag te houden.",
                    'GL-PvdA': "GL-PvdA steunt gratis kinderopvang om gelijke kansen voor alle kinderen te waarborgen.",
                    'D66': "D66 wil gratis kinderopvang zodat werk en gezin beter gecombineerd kunnen worden.",
                    'SP': "SP ziet gratis kinderopvang als een investering in de toekomst van de samenleving.",
                    'PvdD': "PvdD wil dat gratis kinderopvang ouders ontzorgt en kansen voor kinderen vergroot.",
                    'CDA': "CDA pleit voor een mix van publiek en privaat zodat kinderopvang toegankelijk blijft.",
                    'JA21': "JA21 vreest dat gratis kinderopvang leidt tot te hoge overheidskosten.",
                    'SGP': "SGP vindt dat ouders een deel van de kosten zelf moeten dragen.",
                    'FvD': "FvD is tegen gratis kinderopvang uit vrees voor overmatige overheidsinmenging.",
                    'DENK': "DENK steunt gratis kinderopvang om sociale barrières te slechten.",
                    'Volt': "Volt vindt dat gratis kinderopvang een investering in de toekomst is, mits de kwaliteit hoog blijft."
                }
            },
            {
                title: "Kernwapens",
                description: "Amerikaanse kernwapens moeten van Nederlands grondgebied worden verwijderd.",
                context: "Deze stelling gaat over de aanwezigheid van Amerikaanse kernwapens in Nederland. Sommige partijen vinden dat deze wapens niet op Nederlands grondgebied horen omdat zij een nucleaire dreiging vormen. Anderen vinden dat deze wapens een belangrijk afschrikmiddel zijn en bijdragen aan de veiligheid. Het debat gaat over nationale veiligheid, internationale afspraken en de afweging tussen onafhankelijkheid en veiligheid.",
                leftView: "Zien verwijdering als een stap richting een onafhankelijkere en veiligere defensie zonder nucleaire dreiging.",
                rightView: "Vinden dat de aanwezigheid van kernwapens een afschrikmiddel is en de veiligheid kan waarborgen.",
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
                    'PVV': "PVV vindt dat kernwapens op Nederlands grondgebied geen prioriteit hebben.",
                    'VVD': "VVD vindt dat kernwapens veiligheid en afschrikking bieden via internationale afspraken.",
                    'NSC': "NSC pleit voor samenwerking met bondgenoten voor strategische afschrikking.",
                    'BBB': "BBB ziet zowel voor- als nadelen in de aanwezigheid van kernwapens.",
                    'GL-PvdA': "GL-PvdA wil verwijdering en een pacifistische defensie.",
                    'D66': "D66 neigt naar verwijdering als dit leidt tot internationale ontwapening.",
                    'SP': "SP steunt verwijdering als stap naar een vreedzamere wereld.",
                    'PvdD': "PvdD wil kernwapens verwijderen om de nucleaire dreiging te verkleinen.",
                    'CDA': "CDA vreest dat verwijdering de afschrikking verzwakt.",
                    'JA21': "JA21 vindt dat kernwapens een belangrijk afschrikmiddel zijn.",
                    'SGP': "SGP hecht waarde aan de afschrikking die kernwapens bieden.",
                    'FvD': "FvD wil verwijdering als onderdeel van een onafhankelijke defensie.",
                    'DENK': "DENK steunt verwijdering om te werken aan internationale ontwapening.",
                    'Volt': "Volt wil kernwapens verwijderen als onderdeel van een bredere ontwapeningsagenda."
                }
            },
            {
                title: "Monarchie",
                description: "Nederland moet een republiek worden in plaats van een monarchie.",
                context: "Deze stelling gaat over de vorm van het staatshoofd. In een monarchie wordt de positie van staatshoofd geërfd, terwijl in een republiek het staatshoofd gekozen wordt. Er wordt gediscussieerd over of een gekozen staatshoofd beter past bij een moderne democratie of dat de traditionele monarchie belangrijk is voor de nationale identiteit en continuïteit.",
                leftView: "Zien een republiek als moderner en democratischer, met een gekozen staatshoofd dat representatief is voor het volk.",
                rightView: "Vinden dat de monarchie een belangrijk historisch en symbolisch onderdeel is van de nationale identiteit.",
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
                    'PVV': "PVV vindt dat de monarchie stabiliteit geeft en ziet geen dringende reden tot verandering.",
                    'VVD': "VVD beschouwt de monarchie als een onmisbaar onderdeel van de nationale identiteit.",
                    'NSC': "NSC vindt dat zowel monarchie als republiek voor- en nadelen hebben.",
                    'BBB': "BBB wil de traditionele waarden van de monarchie behouden.",
                    'GL-PvdA': "GL-PvdA vindt dat de monarchie modernisering behoeft maar ondersteunt de huidige vorm.",
                    'D66': "D66 ziet de monarchie als institutioneel erfgoed dat mee moet evolueren met de tijd.",
                    'SP': "SP steunt een republiek omdat een gekozen staatshoofd democratischer is.",
                    'PvdD': "PvdD vindt dat de discussie vooral symbolisch is en de inhoud belangrijker is.",
                    'CDA': "CDA steunt de monarchie als symbool van eenheid en continuïteit.",
                    'JA21': "JA21 wil de monarchie behouden als onderdeel van de nationale identiteit.",
                    'SGP': "SGP vindt dat de monarchie een moreel en cultureel anker is.",
                    'FvD': "FvD ziet de monarchie als een verbindend symbool in een verdeelde samenleving.",
                    'DENK': "DENK vindt dat de discussie over de monarchie vooral over symboliek gaat.",
                    'Volt': "Volt ziet de monarchie als een historisch instituut dat aangepast kan worden aan moderne waarden."
                }
            },
            {
                title: "Pensioenstelsel",
                description: "Het nieuwe pensioenstelsel moet worden teruggedraaid.",
                context: "Deze stelling gaat over het terugdraaien van de recente hervormingen in het pensioenstelsel. Het idee is dat de oude manier van pensioen betalen meer zekerheid bood aan gepensioneerden. Tegelijkertijd kan het terugdraaien van veranderingen ook betekenen dat er minder ruimte komt voor aanpassing aan de veranderende arbeidsmarkt en demografische ontwikkelingen.",
                leftView: "Vinden dat het oude pensioenstelsel meer zekerheid biedt aan gepensioneerden.",
                rightView: "Zien dat vernieuwing nodig is om het stelsel toekomstbestendig te maken, gezien demografische veranderingen.",
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
                    'PVV': "PVV steunt terugdraaiing om de oude zekerheden te herstellen voor de burger.",
                    'VVD': "VVD vindt dat een modern pensioenstelsel nodig is voor de toekomst.",
                    'NSC': "NSC wil dat het pensioenstelsel aangepast wordt aan economische en demografische realiteiten.",
                    'BBB': "BBB steunt terugdraaiing zodat gepensioneerden beter beschermd worden.",
                    'GL-PvdA': "GL-PvdA wil hervormingen voor een duurzaam stelsel in plaats van terugdraaien.",
                    'D66': "D66 pleit voor innovatie in het pensioenstelsel, gezien de veranderende samenleving.",
                    'SP': "SP vindt dat het terugdraaien van het nieuwe stelsel de belangen van werkenden en gepensioneerden beter beschermt.",
                    'PvdD': "PvdD wil een menselijk en duurzaam pensioen, zonder overmatige financiële risico's.",
                    'CDA': "CDA pleit voor een mix van hervorming en stabiliteit, passend bij de arbeidsmarkt.",
                    'JA21': "JA21 vindt dat de huidige hervormingen te nadelig zijn voor de burger.",
                    'SGP': "SGP wil dat het pensioenstelsel zowel rechtvaardig als houdbaar is.",
                    'FvD': "FvD steunt terugdraaiing zodat de overheid de burger beter beschermt tegen onzekerheid.",
                    'DENK': "DENK wil terugdraaien om sociale rechtvaardigheid te waarborgen.",
                    'Volt': "Volt is tegen terugdraaien en pleit voor een toekomstbestendige hervorming."
                }
            },
            {
                title: "Defensiesamenwerking",
                description: "Nederland moet streven naar een Europees leger.",
                context: "Deze stelling gaat over de samenwerking op defensiegebied binnen Europa. Een Europees leger betekent dat landen samen hun militaire middelen bundelen. Dit kan leiden tot efficiëntere samenwerking en kostenbesparing, maar roept ook vragen op over nationale soevereiniteit en controle over de eigen defensie. Het debat draait om de balans tussen samenwerking en onafhankelijkheid op militair vlak.",
                leftView: "Vinden dat samenwerking leidt tot een sterkere, gezamenlijke veiligheid in Europa.",
                rightView: "Vinden dat nationale defensie altijd voorop moet staan en een Europees leger de soevereiniteit kan verminderen.",
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
                    'PVV': "PVV is tegen een Europees leger omdat zij vinden dat Nederland zijn eigen defensie moet behouden.",
                    'VVD': "VVD staat open voor samenwerking, maar wil nationale belangen eerst.",
                    'NSC': "NSC ziet voordelen in samenwerking, mits nationale belangen gewaarborgd blijven.",
                    'BBB': "BBB wil behoud van nationale controle over defensie.",
                    'GL-PvdA': "GL-PvdA ziet in Europese samenwerking een manier om de veiligheid te versterken.",
                    'D66': "D66 pleit voor een Europees leger als stap naar efficiëntere defensie.",
                    'SP': "SP vindt dat veiligheid lokaal en menselijk georganiseerd moet blijven.",
                    'PvdD': "PvdD wil dat nationale democratie behouden blijft, ook op defensiegebied.",
                    'CDA': "CDA pleit voor een hybride model waarin samenwerking en zelfstandigheid samen gaan.",
                    'JA21': "JA21 vindt dat nationale defensie prioriteit moet krijgen.",
                    'SGP': "SGP wil dat veiligheid primair nationaal wordt geregeld.",
                    'FvD': "FvD is tegen samenwerking die ten koste gaat van nationale autonomie.",
                    'DENK': "DENK steunt beperkte samenwerking maar niet een volledig Europees leger.",
                    'Volt': "Volt vindt dat een Europees leger bijdraagt aan collectieve veiligheid."
                }
            },
            {
                title: "Belastingstelsel",
                description: "Er moet een vlaktaks komen: één belastingtarief voor alle inkomens.",
                context: "Deze stelling gaat over het invoeren van een vlaktaks, waarbij iedereen hetzelfde percentage belasting betaalt, ongeacht het inkomen. Dit systeem is eenvoudiger en overzichtelijker, maar het kan betekenen dat mensen met een laag inkomen relatief meer betalen dan bij een progressief tarief, waarbij de rijkere mensen meer bijdragen. Het debat gaat over de balans tussen eenvoud en sociale rechtvaardigheid.",
                leftView: "Zien een vlaktaks als een manier om de belastingheffing eerlijk en simpel te maken.",
                rightView: "Vinden dat een progressief systeem eerlijker is, omdat de rijksten meer kunnen bijdragen.",
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
                    'PVV': "PVV vindt dat eenvoud in belastingheffing belangrijk is, mits sociale gelijkheid behouden blijft.",
                    'VVD': "VVD steunt een vlaktaks omdat het de belastingdruk vereenvoudigt en ondernemerschap stimuleert.",
                    'NSC': "NSC wil een systeem dat eerlijk is en tegelijkertijd de economie bevordert.",
                    'BBB': "BBB ziet voordelen in de eenvoud, maar wil ruimte voor aftrekposten en sociale zekerheid.",
                    'GL-PvdA': "GL-PvdA verzet zich tegen een vlaktaks omdat zij vrezen dat dit leidt tot minder solidariteit.",
                    'D66': "D66 vindt dat maatwerk en progressiviteit nodig zijn voor een eerlijke lastverdeling.",
                    'SP': "SP wil dat de rijksten meer bijdragen en is daarom tegen een uniforme tariefstructuur.",
                    'PvdD': "PvdD vindt dat een vlaktaks te simplistisch kan zijn en pleit voor een mix van eenvoud en rechtvaardigheid.",
                    'CDA': "CDA steunt eenvoud, mits de sociale verdeling niet wordt geschaad.",
                    'JA21': "JA21 steunt een vlaktaks omdat dit voor overzicht zorgt en de economie stimuleert.",
                    'SGP': "SGP wil een balans tussen efficiëntie en sociale rechtvaardigheid.",
                    'FvD': "FvD is voor een vlaktaks omdat een lager, uniform tarief volgens hen eerlijk is.",
                    'DENK': "DENK verzet zich tegen een vlaktaks uit vrees voor een oneerlijke lastverdeling.",
                    'Volt': "Volt vindt dat de rijken proportioneel meer moeten bijdragen voor een rechtvaardig systeem."
                }
            }
        ],
        answers: [],
        results: [],
        politicalPosition: { x: 50, y: 50 },
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
                        partyPositions[party] = { matches: [] };
                    }
                    
                    if (this.answers[index] !== 'skip') {
                        let match = 0;
                        
                        if (this.answers[index] === position) {
                            match = 1;
                        } else if (this.answers[index] === 'neutraal' || position === 'neutraal') {
                            match = 0.5;
                        }
                        
                        partyPositions[party].matches.push({
                            question: question.title,
                            match: match
                        });
                    }
                });
            });

            // Bereken gewogen gemiddelde en top matches
            this.results = Object.entries(partyPositions).map(([party, data]) => {
                const matchPercentage = data.matches.reduce((acc, curr) => {
                    return acc + curr.match;
                }, 0) / data.matches.length * 100;

                // Sorteer matches op basis van match score
                const topMatches = data.matches
                    .filter(m => m.match > 0.7)
                    .sort((a, b) => b.match - a.match)
                    .slice(0, 3);

                return {
                    party,
                    match: matchPercentage,
                    topMatches: topMatches
                };
            });

            // Sorteer resultaten op match percentage
            this.results.sort((a, b) => b.match - a.match);

            // Bereken politieke positie
            this.calculatePoliticalPosition();
            
            this.screen = 'results';
        },
        calculatePoliticalPosition() {
            let xScore = 0; // Links (-1) vs Rechts (1)
            let yScore = 0; // Progressief (-1) vs Conservatief (1)
            let xCount = 0;
            let yCount = 0;

            // Definieer thema's voor beide assen
            const economicThemes = ['belasting', 'markt', 'minimumloon', 'winstbelasting', 'woningmarkt', 'zorgverzekering'];
            const socialThemes = ['asielbeleid', 'klimaat', 'drugs', 'monarchie', 'kernwapens', 'europese'];

            this.answers.forEach((answer, index) => {
                const title = this.questions[index].title.toLowerCase();
                
                // Skip overgeslagen vragen
                if (answer === 'skip') return;

                // Economische as (Links-Rechts)
                if (economicThemes.some(theme => title.includes(theme))) {
                    xCount++;
                    switch (answer) {
                        case 'eens':
                            // Voor economische thema's: 'eens' is meestal rechts
                            xScore += 1;
                            break;
                        case 'oneens':
                            xScore -= 1;
                            break;
                        case 'neutraal':
                            xScore += 0;
                            break;
                    }
                }

                // Sociale as (Progressief-Conservatief)
                if (socialThemes.some(theme => title.includes(theme))) {
                    yCount++;
                    switch (answer) {
                        case 'eens':
                            // Voor sociale thema's: 'eens' is meestal progressief
                            yScore -= 1;
                            break;
                        case 'oneens':
                            yScore += 1;
                            break;
                        case 'neutraal':
                            yScore += 0;
                            break;
                    }
                }
            });

            // Bereken gemiddelde scores en converteer naar percentages
            // We gebruiken een schaal van 10-90% om te voorkomen dat de stip helemaal aan de rand komt
            const xPercentage = xCount > 0 
                ? 50 + ((xScore / xCount) * 40) // 40% spreiding van het midden (links of rechts)
                : 50;
            
            const yPercentage = yCount > 0 
                ? 50 + ((yScore / yCount) * 40) // 40% spreiding van het midden (progressief of conservatief)
                : 50;

            this.politicalPosition = {
                x: Math.min(90, Math.max(10, xPercentage)), // Begrens tussen 10% en 90%
                y: Math.min(90, Math.max(10, yPercentage))  // Begrens tussen 10% en 90%
            };

            console.log('Political Position:', {
                x: xScore,
                y: yScore,
                xCount,
                yCount,
                position: this.politicalPosition
            });
        },
        shareResults() {
            const text = `Mijn Stemwijzer resultaten:\n${
                this.results.slice(0, 3)
                    .map(r => `${r.party}: ${Math.round(r.match)}%`)
                    .join('\n')
            }`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Mijn Stemwijzer Resultaten',
                    text: text,
                    url: window.location.href
                });
            } else {
                // Fallback: kopieer naar klembord
                navigator.clipboard.writeText(text)
                    .then(() => alert('Resultaten gekopieerd naar klembord!'));
            }
        },
        saveResults() {
            const results = {
                timestamp: new Date().toISOString(),
                matches: this.results,
                answers: this.answers,
                position: this.politicalPosition
            };

            // Sla op in localStorage
            const savedResults = JSON.parse(localStorage.getItem('stemwijzerResults') || '[]');
            savedResults.push(results);
            localStorage.setItem('stemwijzerResults', JSON.stringify(savedResults));

            alert('Je resultaten zijn opgeslagen! Je kunt ze later terugvinden.');
        }
    }
}
</script>



<?php require_once 'views/templates/footer.php'; ?> 