<?php
require_once 'includes/config.php';
require_once 'views/templates/header.php';
?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen mb-20">
    <!-- Hero Section - Elegant and professional design -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-blue-600 py-20 overflow-hidden">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary to-blue-400"></div>
        
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Abstract wave pattern -->
            <svg class="absolute w-full h-56 -bottom-10 left-0 text-white/5" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path fill="currentColor" fill-opacity="1" d="M0,128L40,138.7C80,149,160,171,240,170.7C320,171,400,149,480,149.3C560,149,640,171,720,192C800,213,880,235,960,229.3C1040,224,1120,192,1200,165.3C1280,139,1360,117,1400,106.7L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
            </svg>
            
            <!-- Decorative circles -->
            <div class="absolute top-20 left-10 w-40 h-40 rounded-full bg-secondary/10 filter blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-blue-500/10 filter blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-20 h-20 rounded-full bg-secondary/20 filter blur-xl"></div>
            
            <!-- Dot pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-30"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Small decorative element above title -->
                <div class="inline-block mb-3">
                    <div class="flex items-center justify-center space-x-1">
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        <span class="block w-3 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="block w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    </div>
                </div>
                
                <!-- Title with gradient text -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-100 via-white to-secondary-light">
                        Stemwijzer 2025
                    </span>
                </h1>
                
                <!-- Subtitle with lighter weight -->
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed font-light">
                    Ontdek welke partij het beste bij jouw standpunten past met onze uitgebreide analyse
                </p>
            </div>
        </div>
    </section>
    
    <div class="container mx-auto px-4 max-w-7xl -mt-6 relative z-10">
        <!-- Stemwijzer App -->
        <div class="max-w-4xl mx-auto" x-data="stemwijzer()">
            <!-- Progress Bar -->
            <div class="mb-8 bg-white rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-2xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-secondary/5 to-primary/5 rounded-full blur-2xl -z-10 transform -translate-x-1/3 translate-y-1/3"></div>
                
                <!-- Header met vraagnummer en tijd -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-5">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary/10 to-primary/5 
                                       flex items-center justify-center">
                                <span class="text-xl font-semibold text-primary" x-text="currentStep + 1"></span>
                                <div class="absolute inset-0 rounded-full border-2 border-primary/20 
                                           animate-pulse-subtle"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-medium text-gray-900">Vraag</span>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-500">
                                    <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span> stellingen
                                </span>
                                <span class="ml-2 px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded-full">
                                    <span x-text="Math.round((currentStep / totalSteps) * 100)"></span>% voltooid
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                    </div>
                </div>
                
                <!-- Progress track -->
                <div class="relative mb-6">
                    <!-- Background track -->
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden backdrop-blur-sm shadow-inner">
                        <!-- Progress bar -->
                        <div class="h-full bg-gradient-to-r from-primary via-primary/90 to-secondary
                                    transition-all duration-500 ease-out-cubic relative"
                             :style="'width: ' + (currentStep / totalSteps * 100) + '%'">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 flex">
                                <div class="w-1/2 bg-gradient-to-r from-transparent to-white/40"></div>
                                <div class="w-1/2 bg-gradient-to-l from-transparent to-white/40"></div>
                            </div>
                            
                            <!-- Progress indicator -->
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-5 h-5 
                                       bg-white rounded-full shadow-lg shadow-primary/30 border-2 border-primary/30
                                       animate-pulse-slow z-10"></div>
                        </div>
                    </div>

                    <!-- Step markers with segments -->
                    <div class="absolute top-1/2 -translate-y-1/2 w-full flex justify-between px-[1px]">
                        <template x-for="(_, index) in Array.from({length: 5})" :key="index">
                            <div class="relative group">
                                <div class="w-1 h-3.5 rounded-sm transition-all duration-300"
                                     :class="currentStep >= Math.floor(index * (totalSteps / 4)) ? 'bg-primary/70' : 'bg-gray-200'">
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full mb-2 -translate-x-1/2 left-1/2
                                           opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                    <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded-md whitespace-nowrap shadow-lg">
                                        <span x-text="Math.round((index / 4) * 100)"></span>% voltooid
                                    </div>
                                    <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 mx-auto"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Labels with icons -->
                <div class="flex justify-between px-1 text-xs font-medium">
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Start</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Halverwege</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mb-1">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-600">Einde</span>
                    </div>
                </div>
            </div>

            <!-- Voeg deze custom animaties toe aan je bestaande style sectie -->
            <style>
            @keyframes pulse-subtle {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            @keyframes pulse-slow {
                0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
                50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes ease-out-cubic {
                0% { transition-timing-function: cubic-bezier(0.33, 1, 0.68, 1); }
                100% { transition-timing-function: cubic-bezier(0.33, 1, 0.68, 1); }
            }

            .shadow-glow {
                box-shadow: 0 0 12px 3px rgba(255, 255, 255, 0.8);
            }

            .animate-pulse-subtle {
                animation: pulse-subtle 3s ease-in-out infinite;
            }

            .animate-pulse-slow {
                animation: pulse-slow 2.5s ease-in-out infinite;
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            </style>

            <!-- Start Screen -->
            <div x-show="screen === 'start'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="bg-white rounded-2xl shadow-xl p-10 relative overflow-hidden mb-20">
                <!-- Decoratieve achtergrond elementen -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl -z-10 transform translate-x-1/3 -translate-y-1/3 animate-float"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-secondary/10 to-primary/10 rounded-full blur-3xl -z-10 transform -translate-x-1/3 translate-y-1/3 animate-float" style="animation-delay: 2s;"></div>

                <!-- Header met icon -->
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-5 mb-8">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-lg shadow-primary/20 mx-auto md:mx-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Welkom bij de Stemwijzer</h2>
                        <div class="flex items-center justify-center md:justify-start mt-2">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-medium text-gray-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                ±10 minuten
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-6">
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Deze stemwijzer helpt je om te ontdekken welke partij het beste bij jouw politieke voorkeuren past. 
                        Je krijgt een aantal stellingen te zien waarop je kunt aangeven in hoeverre je het ermee eens bent.
                    </p>

                    <!-- Features/voordelen -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 py-6">
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Gebaseerd op actuele partijstandpunten</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Volledig anoniem en privacy-vriendelijk</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Gedetailleerde resultaten</span>
                        </div>
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Slechts 25 belangrijke stellingen</span>
                        </div>
                    </div>
                </div>

                <!-- Start button -->
                <button @click="startQuiz()" 
                        class="w-full mt-10 bg-gradient-to-r from-primary to-secondary text-white font-semibold 
                               py-4 px-6 rounded-xl shadow-lg shadow-primary/20
                               hover:shadow-xl hover:shadow-primary/30
                               transform transition-all duration-300 hover:scale-[1.02]
                               focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2">
                    <div class="flex items-center justify-center space-x-3">
                        <span class="text-lg">Start de Stemwijzer</span>
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
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Question & Answers -->
                    <div class="lg:col-span-7 bg-white rounded-2xl shadow-xl p-8">
                        <!-- Question Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-5">
                                <span class="px-4 py-1.5 bg-gray-100 rounded-full text-xs font-medium text-gray-600 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Vraag <span x-text="currentStep + 1"></span> van <span x-text="totalSteps"></span>
                                </span>
                                <div class="flex items-center space-x-3 text-xs text-gray-500">
                                    <button @click="previousQuestion()" 
                                            x-show="currentStep > 0"
                                            class="flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="hidden sm:inline font-medium">Vorige</span>
                                    </button>
                                    <button @click="skipQuestion()"
                                            class="flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                        <span class="hidden sm:inline font-medium">Overslaan</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4" x-text="questions[currentStep].title"></h2>
                            <p class="text-lg text-gray-600 leading-relaxed" x-text="questions[currentStep].description"></p>
                            
                            <!-- Nieuwe uitleg knop -->
                            <button @click="showExplanation = !showExplanation"
                                    class="mt-5 px-4 py-2 bg-gray-50 text-sm text-gray-700 hover:bg-gray-100 rounded-full flex items-center space-x-2 transition-colors border border-gray-200">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Uitleg over deze stelling</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="showExplanation ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Uitleg panel -->
                            <div x-show="showExplanation" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-6 rounded-xl overflow-hidden border border-gray-100">
                                <div class="bg-blue-50 px-5 py-3 border-b border-blue-100">
                                    <h3 class="font-semibold text-blue-900">Uitleg bij deze stelling</h3>
                                </div>
                                <div x-text="questions[currentStep].context" 
                                     class="text-gray-700 p-5 bg-white leading-relaxed">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-t border-gray-100">
                                    <div class="bg-blue-50 p-5 border-r border-blue-100">
                                        <h4 class="font-medium text-blue-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                            Linkse partijen vinden:
                                        </h4>
                                        <p x-text="questions[currentStep].leftView" 
                                           class="text-blue-800 leading-relaxed"></p>
                                    </div>
                                    <div class="bg-red-50 p-5">
                                        <h4 class="font-medium text-red-900 mb-3 flex items-center">
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
                        <div class="grid grid-cols-1 gap-5 mt-8">
                            <!-- Eens button -->
                            <button @click="answerQuestion('eens')"
                                    class="relative bg-gradient-to-r from-emerald-50 to-white border-2 border-emerald-500 rounded-xl p-6 
                                           transition-all duration-300 hover:shadow-lg hover:shadow-emerald-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-emerald-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-emerald-700">Eens</h3>
                                        <p class="text-sm text-emerald-600">Ik ben het eens met deze stelling</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Neutraal button -->
                            <button @click="answerQuestion('neutraal')"
                                    class="relative bg-gradient-to-r from-blue-50 to-white border-2 border-blue-400 rounded-xl p-6
                                          transition-all duration-300 hover:shadow-lg hover:shadow-blue-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-blue-400 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-blue-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-blue-700">Neutraal</h3>
                                        <p class="text-sm text-blue-600">Ik sta hier neutraal tegenover</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Oneens button -->
                            <button @click="answerQuestion('oneens')"
                                    class="relative bg-gradient-to-r from-red-50 to-white border-2 border-red-500 rounded-xl p-6
                                          transition-all duration-300 hover:shadow-lg hover:shadow-red-100 group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center 
                                                transition-transform group-hover:scale-110 shadow-md shadow-red-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-xl font-bold text-red-700">Oneens</h3>
                                        <p class="text-sm text-red-600">Ik ben het oneens met deze stelling</p>
                                    </div>
                                </div>
                                <!-- Hover effect -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Skip button (smaller) -->
                            <button @click="skipQuestion()"
                                    class="mt-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 transition-colors rounded-xl mx-auto flex items-center space-x-2 text-gray-600">
                                <span class="text-sm font-medium">Deze vraag overslaan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Party Information -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Informatie over de vraag -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 relative overflow-hidden">
                            <!-- Decorative elements -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Partijstandpunten
                            </h3>
                            
                            <p class="text-gray-600 mb-6">
                                Bekijk hoe de belangrijkste politieke partijen staan tegenover deze stelling:
                            </p>
                            
                            <div class="space-y-4">
                                <!-- Eens groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-emerald-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Eens met deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2" x-init="updatePartyGroups()">
                                        <template x-for="(partido, index) in $data.eensParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-emerald-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Neutraal groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                        </svg>
                                        Neutraal tegenover deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(partido, index) in $data.neutraalParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-blue-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Oneens groep -->
                                <div>
                                    <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Oneens met deze stelling:
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(partido, index) in $data.oneensParties" :key="index">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-lg shadow-md bg-white p-1 border border-gray-200 hover:border-red-300 transition-all">
                                                    <img :src="$data.partyLogos[partido]" :alt="partido" class="w-full h-full object-contain rounded-md">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Partij uitleg -->
                        <div x-show="selectedParty !== null" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="bg-white rounded-2xl shadow-xl p-8">
                            <div class="flex items-start space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-xl bg-white p-1 border border-gray-200 flex-shrink-0">
                                    <img :src="$data.partyLogos[selectedParty]" :alt="selectedParty" class="w-full h-full object-contain rounded-lg">
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900" x-text="selectedParty"></h3>
                                    <div class="mt-1 flex items-center">
                                        <div class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                                             :class="{
                                                'bg-emerald-100 text-emerald-800': questions[currentStep].positions[selectedParty] === 'eens',
                                                'bg-blue-100 text-blue-800': questions[currentStep].positions[selectedParty] === 'neutraal',
                                                'bg-red-100 text-red-800': questions[currentStep].positions[selectedParty] === 'oneens'
                                             }">
                                            <span x-text="questions[currentStep].positions[selectedParty]"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100" 
                               x-text="questions[currentStep].explanations[selectedParty]"></p>
                               
                            <button @click="selectedParty = null" 
                                    class="mt-4 text-sm text-gray-500 hover:text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Sluiten
                            </button>
                        </div>
                        
                        <!-- Voortgang box -->
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Jouw voortgang
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-2 text-sm">
                                        <span class="font-medium text-gray-700">Beantwoorde vragen</span>
                                        <span class="text-primary font-semibold">
                                            <span x-text="Object.keys(answers).length"></span>/<span x-text="totalSteps"></span>
                                        </span>
                                    </div>
                                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500 ease-out"
                                             :style="'width: ' + (Object.keys(answers).length / totalSteps * 100) + '%'"></div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Eens</span>
                                        <span class="font-medium text-emerald-600" x-text="countAnswerType('eens')"></span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Neutraal</span>
                                        <span class="font-medium text-blue-600" x-text="countAnswerType('neutraal')"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Oneens</span>
                                        <span class="font-medium text-red-600" x-text="countAnswerType('oneens')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Screen -->
            <div x-show="screen === 'results'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="max-w-5xl mx-auto">
                
                <!-- Hero Results -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
                    <!-- Decoratieve achtergrond elementen -->
                    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl -z-10 transform translate-x-1/3 -translate-y-1/3"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-secondary/10 to-primary/10 rounded-full blur-3xl -z-10 transform -translate-x-1/3 translate-y-1/3"></div>
                    
                    <div class="text-center mb-8">
                        <div class="inline-block p-3 bg-primary/10 rounded-full mb-4">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Jouw resultaten</h2>
                        <p class="text-gray-600 max-w-3xl mx-auto">
                            Gebaseerd op je antwoorden hebben we berekend welke partijen het beste bij jouw standpunten passen.
                            Hoe hoger het percentage, hoe meer jullie standpunten overeenkomen.
                        </p>
                    </div>
                    
                    <!-- Top 3 Results -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-20">
                        <!-- #2 Result -->
                        <div class="relative order-2 md:order-1 mt-6 md:mt-10">
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-xl font-bold text-gray-700">2</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 pt-8 text-center h-full relative overflow-hidden">
                                <div class="mb-3 w-20 h-20 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[1]?.logo" :alt="finalResults[1]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="finalResults[1]?.name"></h3>
                                <div class="text-3xl font-bold text-primary" x-text="finalResults[1]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-500 mb-4">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[1])" 
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition-colors">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                        
                        <!-- #1 Result (Larger) -->
                        <div class="relative order-1 md:order-2 transform md:-translate-y-4">
                            <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="bg-gradient-to-b from-primary/5 to-primary/10 rounded-xl border border-primary/20 p-8 text-center h-full relative overflow-hidden shadow-xl">
                                <div class="mb-4 w-24 h-24 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[0]?.logo" :alt="finalResults[0]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-1" x-text="finalResults[0]?.name"></h3>
                                <div class="text-4xl font-bold text-primary mb-1" x-text="finalResults[0]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-600 mb-5">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[0])" 
                                        class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-medium transition-colors shadow-md shadow-primary/20">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                        
                        <!-- #3 Result -->
                        <div class="relative order-3 mt-6 md:mt-10">
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-xl font-bold text-gray-700">3</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 pt-8 text-center h-full relative overflow-hidden">
                                <div class="mb-3 w-20 h-20 mx-auto rounded-full p-2 bg-white shadow-md">
                                    <img :src="finalResults[2]?.logo" :alt="finalResults[2]?.name" class="w-full h-full object-contain rounded-full">
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="finalResults[2]?.name"></h3>
                                <div class="text-3xl font-bold text-primary" x-text="finalResults[2]?.agreement + '%'"></div>
                                <div class="text-sm text-gray-500 mb-4">overeenkomst</div>
                                
                                <button @click="showPartyExplanation(finalResults[2])" 
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition-colors">
                                    Details bekijken
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- All Results Table -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-md">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Alle resultaten</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <template x-for="(result, index) in finalResults.slice(0, 10)" :key="index">
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-white p-1 border border-gray-200 shadow-sm">
                                                <img :src="result.logo" :alt="result.name" class="w-full h-full object-contain rounded-md">
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900" x-text="result.name"></h4>
                                                <div class="flex items-center mt-1">
                                                    <div class="text-xs text-gray-500 mr-2" x-text="'#' + (index + 1)"></div>
                                                    <div class="h-1.5 w-24 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-primary rounded-full" :style="'width: ' + result.agreement + '%'"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-xl font-bold text-gray-900" x-text="result.agreement + '%'"></div>
                                            <button @click="showPartyExplanation(result)" 
                                                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <!-- Party Details Modal -->
                <div x-show="showPartyDetails" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                    
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.away="showPartyDetails = false">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100 sticky top-0 bg-white z-10">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-xl bg-white p-1 border border-gray-200 shadow-sm">
                                        <img :src="$data.partyLogos[detailedParty?.name]" :alt="detailedParty?.name" class="w-full h-full object-contain rounded-md">
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900" x-text="detailedParty?.name"></h3>
                                        <div class="flex items-center mt-1">
                                            <div class="px-3 py-1 bg-primary/10 rounded-full text-sm font-medium text-primary">
                                                <span x-text="detailedParty?.agreement + '% overeenkomst'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="showPartyDetails = false" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Standpunten per stelling</h4>
                            
                            <div class="space-y-6">
                                <template x-for="(question, index) in questions" :key="index">
                                    <div class="p-4 border border-gray-100 rounded-xl hover:border-gray-200 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-block w-6 h-6 text-xs flex items-center justify-center bg-gray-100 text-gray-700 rounded-full font-medium" x-text="index + 1"></span>
                                                    <h5 class="font-semibold text-gray-900" x-text="question.title"></h5>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3" x-text="question.description"></p>
                                            </div>
                                            
                                            <div class="px-3 py-1 rounded-full text-sm font-medium"
                                                 :class="{
                                                    'bg-emerald-100 text-emerald-800': question.positions[detailedParty?.name] === 'eens',
                                                    'bg-blue-100 text-blue-800': question.positions[detailedParty?.name] === 'neutraal',
                                                    'bg-red-100 text-red-800': question.positions[detailedParty?.name] === 'oneens'
                                                 }">
                                                <span x-text="question.positions[detailedParty?.name]"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-700"
                                             x-text="question.explanations[detailedParty?.name]"></div>
                                                 
                                        <div class="mt-2 text-xs text-gray-500 flex items-center">
                                            <template x-if="answers[index]">
                                                <div class="flex items-center space-x-1">
                                                    <span>Jouw antwoord:</span>
                                                    <span class="font-medium px-2 py-0.5 rounded-full"
                                                          :class="{
                                                            'bg-emerald-100 text-emerald-800': answers[index] === 'eens',
                                                            'bg-blue-100 text-blue-800': answers[index] === 'neutraal',
                                                            'bg-red-100 text-red-800': answers[index] === 'oneens'
                                                          }"
                                                          x-text="answers[index]"></span>
                                                </div>
                                            </template>
                                            <template x-if="!answers[index]">
                                                <span>Je hebt deze vraag overgeslagen</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button @click="resetQuiz()" 
                            class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Opnieuw beginnen</span>
                    </button>
                    
                    <button onclick="window.print()" 
                            class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Resultaten afdrukken</span>
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
        selectedParty: null,
        answers: {},
        eensParties: [],
        neutraalParties: [],
        oneensParties: [],
        
        results: {},
        finalResults: [],
        showPartyDetails: false,
        detailedParty: null,
        showingQuestion: null,
        
        partyLogos: {
            'PVV': 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
            'VVD': 'https://logo.clearbit.com/vvd.nl',
            'NSC': 'https://i.ibb.co/YT2fJZb4/nsc.png',
            'BBB': 'https://i.ibb.co/qMjw7jDV/bbb.png',
            'GL-PvdA': 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
            'D66': 'https://logo.clearbit.com/d66.nl',
            'SP': 'https://logo.clearbit.com/sp.nl',
            'PvdD': 'https://logo.clearbit.com/partijvoordedieren.nl',
            'CDA': 'https://logo.clearbit.com/cda.nl',
            'JA21': 'https://logo.clearbit.com/ja21.nl',
            'SGP': 'https://logo.clearbit.com/sgp.nl',
            'FvD': 'https://logo.clearbit.com/fvd.nl',
            'DENK': 'https://logo.clearbit.com/bewegingdenk.nl',
            'Volt': 'https://logo.clearbit.com/voltnederland.org'
        },
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
        answers: {},
        results: {},
        politicalPosition: { x: 50, y: 50 },
        currentQuestion() {
            return this.questions[this.currentStep];
        },
        startQuiz() {
            this.screen = 'questions';
            this.currentStep = 0;
            this.updatePartyGroups();
        },
        updatePartyGroups() {
            if (!this.questions[this.currentStep]) return;
            
            const positions = this.questions[this.currentStep].positions;
            this.eensParties = [];
            this.neutraalParties = [];
            this.oneensParties = [];
            
            Object.keys(positions).forEach(party => {
                if (positions[party] === 'eens') {
                    this.eensParties.push(party);
                } else if (positions[party] === 'neutraal') {
                    this.neutraalParties.push(party);
                } else if (positions[party] === 'oneens') {
                    this.oneensParties.push(party);
                }
            });
        },
        countAnswerType(type) {
            return Object.values(this.answers).filter(answer => answer === type).length;
        },
        answerQuestion(answer) {
            this.answers[this.currentStep] = answer;
            
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            } else {
                this.calculateResults();
                this.screen = 'results';
            }
        },
        skipQuestion() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            } else {
                this.calculateResults();
                this.screen = 'results';
            }
        },
        previousQuestion() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.showExplanation = false;
                this.selectedParty = null;
                this.updatePartyGroups();
            }
        },
        calculateResults() {
            const parties = Object.keys(this.questions[0].positions);
            const results = {};
            
            parties.forEach(party => {
                results[party] = { score: 0, total: 0, agreement: 0 };
            });
            
            Object.keys(this.answers).forEach(questionIndex => {
                const question = this.questions[questionIndex];
                const userAnswer = this.answers[questionIndex];
                
                parties.forEach(party => {
                    const partyAnswer = question.positions[party];
                    
                    if (userAnswer === partyAnswer) {
                        results[party].score += 2;
                    } else if (
                        (userAnswer === 'neutraal' && (partyAnswer === 'eens' || partyAnswer === 'oneens')) ||
                        ((userAnswer === 'eens' || userAnswer === 'oneens') && partyAnswer === 'neutraal')
                    ) {
                        results[party].score += 1;
                    }
                    
                    results[party].total += 2;
                });
            });
            
            // Calculate percentages
            parties.forEach(party => {
                results[party].agreement = Math.round((results[party].score / results[party].total) * 100);
            });
            
            this.results = results;
            
            // Create sorted array for display
            this.finalResults = parties
                .map(party => ({
                    name: party,
                    agreement: results[party].agreement,
                    logo: this.partyLogos[party]
                }))
                .sort((a, b) => b.agreement - a.agreement);
        },
        showPartyExplanation(party) {
            this.detailedParty = party;
            this.showPartyDetails = true;
        },
        resetQuiz() {
            this.screen = 'start';
            this.currentStep = 0;
            this.answers = {};
            this.results = {};
            this.finalResults = [];
            this.showExplanation = false;
            this.selectedParty = null;
        },
        shareResults() {
            const text = `Mijn Stemwijzer resultaten:\n${
                this.finalResults.slice(0, 3)
                    .map(r => `${r.name}: ${r.agreement}%`)
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
                matches: this.finalResults,
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