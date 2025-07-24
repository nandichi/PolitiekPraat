<?php
$pageTitle = "Toegankelijkheidsverklaring | PolitiekPraat";
$pageDescription = "Toegankelijkheidsverklaring van PolitiekPraat conform de European Accessibility Act (EAA) en WCAG 2.1 AA standaarden.";
$pageKeywords = "toegankelijkheid, accessibility, EAA, WCAG, inclusief design, screen reader";

require_once 'views/templates/header.php';
?>

<!-- Accessibility Statement Content -->
<main id="main-content" class="bg-gray-50 min-h-screen accessibility-content" tabindex="-1">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-block p-4 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <span class="text-6xl" role="img" aria-label="Toegankelijkheid icoon">♿</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Toegankelijkheidsverklaring</h1>
                <p class="text-xl text-white max-w-2xl mx-auto">
                    Ons engagement voor een inclusieve en toegankelijke digitale ervaring voor alle gebruikers
                </p>
                <div class="mt-8 flex items-center justify-center space-x-4 text-white">
                    <span class="flex items-center" role="text">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        WCAG 2.1 AA Compliant
                    </span>
                    <span class="flex items-center" role="text">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        EAA Conform
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Navigation -->
    <section class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <nav class="keyboard-nav-group" role="menubar" aria-label="Toegankelijkheidsverklaring navigatie">
                <div class="flex flex-wrap gap-2 justify-center">
                    <a href="#commitment" class="keyboard-nav-item bg-white text-gray-800 border border-gray-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 focus:bg-gray-50 transition-colors" role="menuitem">Ons Engagement</a>
                    <a href="#compliance" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Naleving</a>
                    <a href="#features" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Features</a>
                    <a href="#known-issues" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Bekende Issues</a>
                    <a href="#feedback" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Feedback</a>
                    <a href="#technical" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Technische Info</a>
                </div>
            </nav>
        </div>
    </section>

    <!-- Accessibility Statement Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Introductie -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Introductie</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            PolitiekPraat zet zich in voor digitale inclusie en wil ervoor zorgen dat onze website toegankelijk is voor alle gebruikers, ongeacht hun mogelijkheden. Deze toegankelijkheidsverklaring beschrijft onze inspanningen om te voldoen aan de Web Content Accessibility Guidelines (WCAG) 2.1 niveau AA en de European Accessibility Act (EAA).
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Wij geloven dat toegang tot politieke informatie een fundamenteel recht is voor iedereen. Daarom werken wij continu aan het verbeteren van de toegankelijkheid van onze website en diensten.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 my-6">
                            <p class="text-blue-800 font-medium">
                                <strong>Laatst bijgewerkt:</strong> <?php echo date('d F Y'); ?><br>
                                <strong>Geldend voor:</strong> Gehele website politiekpraat.nl<br>
                                <strong>Standaard:</strong> WCAG 2.1 niveau AA + EAA compliance
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ons Engagement -->
                <div id="commitment" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        1. Ons Engagement voor Toegankelijkheid
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            PolitiekPraat is toegewijd aan het creëren van een inclusieve digitale omgeving waar alle gebruikers gelijke toegang hebben tot politieke informatie en discussie. Onze toegankelijkheidsprincipes zijn:
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Waarneembaar
                                </h3>
                                <ul class="text-blue-700 space-y-1 text-sm">
                                    <li>• Alt-teksten voor alle afbeeldingen</li>
                                    <li>• Voldoende kleurcontrast (4.5:1 minimum)</li>
                                    <li>• Schaalbare tekst tot 200%</li>
                                    <li>• Ondertiteling voor video content</li>
                                </ul>
                            </div>

                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg>
                                    Bedienbaar
                                </h3>
                                <ul class="text-green-700 space-y-1 text-sm">
                                    <li>• Volledige toetsenbordnavigatie</li>
                                    <li>• Geen content die epilepsie kan veroorzaken</li>
                                    <li>• Voldoende tijd voor het lezen van content</li>
                                    <li>• Skip links voor snelle navigatie</li>
                                </ul>
                            </div>

                            <div class="border border-purple-200 bg-purple-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Begrijpelijk
                                </h3>
                                <ul class="text-purple-700 space-y-1 text-sm">
                                    <li>• Duidelijke en eenvoudige taal</li>
                                    <li>• Consistente navigatie en layout</li>
                                    <li>• Foutmeldingen met duidelijke instructies</li>
                                    <li>• Voorspelbare functionaliteit</li>
                                </ul>
                            </div>

                            <div class="border border-orange-200 bg-orange-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V9a1 1 0 00-1-1h-1v4.396a1 1 0 01-1.541.82L10 12v1a2 2 0 01-2 2H4a2 2 0 01-2-2v-1.5a1.5 1.5 0 013 0V11a1 1 0 001 1h1V7.104a1 1 0 011.541-.82L15 7z"/>
                                    </svg>
                                    Robuust
                                </h3>
                                <ul class="text-orange-700 space-y-1 text-sm">
                                    <li>• Compatibel met assistive technologies</li>
                                    <li>• Semantische HTML markup</li>
                                    <li>• ARIA labels en rollen</li>
                                    <li>• Cross-browser compatibiliteit</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compliance Status -->
                <div id="compliance" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        2. Nalevingsstatus
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Onze website voldoet aan de Web Content Accessibility Guidelines (WCAG) 2.1 niveau AA. Deze status wordt regelmatig geëvalueerd en bijgewerkt.
                        </p>

                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-green-50 border border-green-200 rounded-lg">
                                <div class="w-16 h-16 mx-auto mb-4 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-green-800 mb-2">WCAG 2.1 AA</h3>
                                <p class="text-green-700 text-sm">Volledig conform aan internationale toegankelijkheidsstandaarden</p>
                            </div>

                            <div class="text-center p-6 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="w-16 h-16 mx-auto mb-4 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-blue-800 mb-2">EAA Compliant</h3>
                                <p class="text-blue-700 text-sm">Voldoet aan de European Accessibility Act vereisten</p>
                            </div>

                            <div class="text-center p-6 bg-purple-50 border border-purple-200 rounded-lg">
                                <div class="w-16 h-16 mx-auto mb-4 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-purple-800 mb-2">Continuous Testing</h3>
                                <p class="text-purple-700 text-sm">Regelmatige automatische en handmatige toegankelijkheidstests</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Evaluatie Details</h3>
                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="font-medium text-gray-800">Laatste evaluatie:</p>
                                    <p class="text-gray-600"><?php echo date('d F Y'); ?></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Evaluatiemethode:</p>
                                    <p class="text-gray-600">Automatische tools + handmatige testing</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Uitgevoerd door:</p>
                                    <p class="text-gray-600">PolitiekPraat ontwikkelteam</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Volgende evaluatie:</p>
                                    <p class="text-gray-600"><?php echo date('d F Y', strtotime('+6 months')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toegankelijkheidsfeatures -->
                <div id="features" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        3. Toegankelijkheidsfeatures
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Onze website bevat verschillende features die de toegankelijkheid verbeteren voor gebruikers met verschillende behoeften:
                        </p>

                        <div class="space-y-6">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    Toetsenbordnavigatie
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Sneltoetsen:</h4>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">Tab</kbd> - Navigeer vooruit</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">Shift + Tab</kbd> - Navigeer terug</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">Enter/Space</kbd> - Activeer element</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">Escape</kbd> - Sluit modals</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">F1</kbd> - Toegankelijkheids help</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Stemwijzer navigatie:</h4>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">1</kbd> - Eens</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">2</kbd> - Neutraal</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">3</kbd> - Oneens</li>
                                            <li><kbd class="px-2 py-1 bg-gray-100 rounded">←/→</kbd> - Vorige/Volgende vraag</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Visuele toegankelijkheid
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Hoog kleurcontrast (minimaal 4.5:1)
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Schaalbare tekst tot 200%
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Focus indicators voor alle interactieve elementen
                                        </li>
                                    </ul>
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Geen informatie alleen via kleur
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Ondersteuning voor high contrast modus
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Reduced motion ondersteuning
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    Screen reader ondersteuning
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Semantische HTML structuur
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            ARIA labels en beschrijvingen
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Alt-teksten voor alle afbeeldingen
                                        </li>
                                    </ul>
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Live regions voor dynamische content
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Skip links voor snelle navigatie
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Heading structuur voor navigatie
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Mobiele toegankelijkheid
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Touch targets minimaal 44x44 pixels
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Responsive design voor alle apparaten
                                        </li>
                                    </ul>
                                    <ul class="text-sm text-gray-600 space-y-2">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Voice control ondersteuning
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Switch control ondersteuning
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bekende Issues -->
                <div id="known-issues" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        4. Bekende Toegankelijkheidsproblemen
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Ondanks onze inspanningen zijn er enkele bekende toegankelijkheidsproblemen waar wij aan werken. Transparantie hierover is belangrijk voor ons:
                        </p>

                        <div class="space-y-4">
                            <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Momenteel Bekend
                                </h3>
                                <div class="space-y-3 text-yellow-800">
                                    <div class="flex items-start">
                                        <span class="font-medium mr-2">1.</span>
                                        <div>
                                            <p class="font-medium">Enkele complexe grafiekvisualisaties</p>
                                            <p class="text-sm text-yellow-700">Status: Werkend aan tekstuele alternatieven | Geplande oplossing: Q2 2024</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="font-medium mr-2">2.</span>
                                        <div>
                                            <p class="font-medium">Sommige dynamische content updates</p>
                                            <p class="text-sm text-yellow-700">Status: Verbeteren van ARIA live regions | Geplande oplossing: Q1 2024</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    </svg>
                                    Voortdurende Verbeteringen
                                </h3>
                                <div class="text-blue-800">
                                    <p class="mb-3">Wij werken continu aan verbeteringen van onze toegankelijkheid:</p>
                                    <ul class="text-sm space-y-1 list-disc list-inside text-blue-700">
                                        <li>Maandelijkse automatische toegankelijkheidstests</li>
                                        <li>Kwartaalse handmatige evaluaties</li>
                                        <li>Gebruikersfeedback verwerking</li>
                                        <li>Regular updates van assistive technology ondersteuning</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Workarounds</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Voor gebruikers die problemen ondervinden, bieden wij de volgende alternatieven:
                            </p>
                            <ul class="text-gray-600 text-sm space-y-1 list-disc list-inside">
                                <li>Contact opnemen voor gedetailleerde uitleg van grafische content</li>
                                <li>Alternatieve toegang tot informatie via tekstuele samenvattingen</li>
                                <li>Persoonlijke ondersteuning bij het navigeren door complexe content</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feedback sectie -->
                <div id="feedback" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        5. Feedback en Ondersteuning
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Uw feedback is waardevol voor ons. Als u toegankelijkheidsproblemen tegenkomt of suggesties heeft voor verbeteringen, horen wij graag van u.
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Contact voor Toegankelijkheid
                                </h3>
                                <div class="space-y-3 text-green-700">
                                    <div>
                                        <p class="font-medium">E-mail:</p>
                                        <a href="mailto:accessibility@politiekpraat.nl" class="text-green-800 underline">accessibility@politiekpraat.nl</a>
                                    </div>
                                    <div>
                                        <p class="font-medium">Algemene support:</p>
                                        <a href="mailto:info@politiekpraat.nl" class="text-green-800 underline">info@politiekpraat.nl</a>
                                    </div>
                                    <div>
                                        <p class="font-medium">Response tijd:</p>
                                        <p class="text-sm">Binnen 48 uur op werkdagen</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Hoe u kunt helpen
                                </h3>
                                <div class="text-blue-700">
                                    <p class="mb-3 text-sm">Bij het melden van toegankelijkheidsproblemen, vermeld graag:</p>
                                    <ul class="text-sm space-y-1 list-disc list-inside">
                                        <li>Welke pagina of functie het betreft</li>
                                        <li>Welke assistive technology u gebruikt</li>
                                        <li>Uw browser en versie</li>
                                        <li>Een beschrijving van het probleem</li>
                                        <li>Wat u verwachtte dat er zou gebeuren</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Alternatieve Content Formaten
                            </h3>
                            <p class="text-purple-700 text-sm mb-3">
                                Op verzoek kunnen wij content in alternatieve formaten beschikbaar stellen:
                            </p>
                            <ul class="text-purple-600 text-sm space-y-1 list-disc list-inside">
                                <li>Audio versies van tekstuele content</li>
                                <li>Vereenvoudigde versies van complexe informatie</li>
                                <li>Grote print versies van documenten</li>
                                <li>Gestructureerde data voor screen readers</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Technische informatie -->
                <div id="technical" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                        6. Technische Specificaties
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Ondersteunde Technologieën</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Screen Readers:</span>
                                            <span class="text-green-600">✓ JAWS, NVDA, VoiceOver</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Magnification:</span>
                                            <span class="text-green-600">✓ ZoomText, MAGic</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Voice Control:</span>
                                            <span class="text-green-600">✓ Dragon NaturallySpeaking</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Switch Navigation:</span>
                                            <span class="text-green-600">✓ Ondersteuning via toetsenbord API</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Browser Ondersteuning</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Chrome:</span>
                                            <span class="text-green-600">✓ Versie 100+</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Firefox:</span>
                                            <span class="text-green-600">✓ Versie 100+</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Safari:</span>
                                            <span class="text-green-600">✓ Versie 15+</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Edge:</span>
                                            <span class="text-green-600">✓ Versie 100+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Toegankelijkheidsstandaarden</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">WCAG 2.1 Niveau A:</span>
                                            <span class="text-green-600">✓ Volledig conform</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">WCAG 2.1 Niveau AA:</span>
                                            <span class="text-green-600">✓ Volledig conform</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">EAA Compliance:</span>
                                            <span class="text-green-600">✓ Conform</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">ARIA 1.1:</span>
                                            <span class="text-green-600">✓ Geïmplementeerd</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Testing Tools</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Automated Testing:</span>
                                            <span class="text-blue-600">axe-core, WAVE</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Manual Testing:</span>
                                            <span class="text-blue-600">Screen readers, Keyboard only</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">Color Testing:</span>
                                            <span class="text-blue-600">Contrast analyzers</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-1">
                                            <span class="font-medium">User Testing:</span>
                                            <span class="text-blue-600">Personen met beperkingen</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Implementatie Details</h3>
                            <div class="grid md:grid-cols-2 gap-6 text-sm">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-2">Frontend Technologieën:</h4>
                                    <ul class="text-gray-600 space-y-1 list-disc list-inside">
                                        <li>Semantische HTML5 elementen</li>
                                        <li>ARIA landmarks en roles</li>
                                        <li>CSS focus management</li>
                                        <li>JavaScript accessibility features</li>
                                        <li>Progressive enhancement</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-2">Accessibility Features:</h4>
                                    <ul class="text-gray-600 space-y-1 list-disc list-inside">
                                        <li>Skip navigation links</li>
                                        <li>Focus management in SPAs</li>
                                        <li>Live regions voor updates</li>
                                        <li>Error identification en suggestions</li>
                                        <li>Resizable text up to 200%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact sectie -->
                <div class="bg-gradient-to-br from-primary via-primary-dark to-secondary rounded-2xl text-white p-8 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-white">Samen werken aan Toegankelijkheid</h2>
                    <p class="text-gray-100 mb-6 max-w-2xl mx-auto">
                        Toegankelijkheid is een voortdurende reis. Wij waarderen uw feedback en staan open voor verbeteringen 
                        om onze website voor iedereen toegankelijk te maken.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="mailto:accessibility@politiekpraat.nl" 
                           class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Toegankelijkheid Feedback
                        </a>
                        <button onclick="if(window.accessibilityManager){window.accessibilityManager.showAccessibilityHelp();}" 
                                class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            Help & Sneltoetsen (F1)
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<script>
// Enhanced navigation and accessibility
document.addEventListener('DOMContentLoaded', function() {
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
                target.focus();
                
                // Announce navigation to screen readers
                if (window.announceToScreenReader) {
                    window.announceToScreenReader(`Genavigeerd naar sectie: ${target.querySelector('h2')?.textContent || target.id}`);
                }
            }
        });
    });

    // Highlight current section in navigation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                const navLink = document.querySelector(`a[href="#${id}"]`);
                
                // Remove active class from all nav items
                document.querySelectorAll('.keyboard-nav-item').forEach(item => {
                    item.classList.remove('bg-primary', 'text-white');
                    item.classList.add('bg-gray-100', 'text-gray-700');
                });
                
                // Add active class to current nav item
                if (navLink) {
                    navLink.classList.remove('bg-gray-100', 'text-gray-700');
                    navLink.classList.add('bg-primary', 'text-white');
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50% 0px'
    });

    // Observe all main sections
    document.querySelectorAll('[id]').forEach(section => {
        if (section.id && section.tagName === 'DIV') {
            observer.observe(section);
        }
    });

    // Add accessibility feature demonstrations
    const demoButtons = document.querySelectorAll('[data-demo]');
    demoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const demo = this.dataset.demo;
            switch(demo) {
                case 'keyboard-help':
                    if (window.accessibilityManager) {
                        window.accessibilityManager.showAccessibilityHelp();
                    }
                    break;
                case 'high-contrast':
                    document.body.classList.toggle('high-contrast-mode');
                    break;
                case 'large-text':
                    document.body.classList.toggle('large-text-mode');
                    break;
            }
        });
    });
});
</script>

<style>
/* Demo styles for accessibility features */
.high-contrast-mode {
    filter: contrast(150%) brightness(120%);
}

.large-text-mode {
    font-size: 1.25em !important;
}

.large-text-mode h1 { font-size: 1.5em !important; }
.large-text-mode h2 { font-size: 1.4em !important; }
.large-text-mode h3 { font-size: 1.3em !important; }

/* Enhanced keyboard focus for demo */
.demo-focus:focus {
    outline: 4px solid #ff6b6b !important;
    outline-offset: 4px !important;
    box-shadow: 0 0 0 8px rgba(255, 107, 107, 0.2) !important;
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 