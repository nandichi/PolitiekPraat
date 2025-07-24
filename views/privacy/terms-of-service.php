<?php
$pageTitle = "Gebruiksvoorwaarden | PolitiekPraat";
$pageDescription = "Gebruiksvoorwaarden van PolitiekPraat - De regels en voorwaarden voor het gebruik van onze website en diensten.";
$pageKeywords = "gebruiksvoorwaarden, terms of service, regels, voorwaarden, PolitiekPraat";

require_once 'views/templates/header.php';
?>

<!-- Terms of Service Content -->
<main id="main-content" class="bg-gray-50 min-h-screen privacy-content" tabindex="-1">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-block p-4 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <span class="text-6xl" role="img" aria-label="Juridisch document icoon">📋</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Gebruiksvoorwaarden</h1>
                <p class="text-xl text-white max-w-2xl mx-auto">
                    De regels en voorwaarden die van toepassing zijn op het gebruik van PolitiekPraat
                </p>
                <div class="mt-8 flex items-center justify-center space-x-4 text-blue-200">
                    <span class="flex items-center" role="text">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Rechtsgeldig
                    </span>
                    <span class="flex items-center" role="text">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Transparant
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Navigation -->
    <section class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <nav class="keyboard-nav-group" role="menubar" aria-label="Gebruiksvoorwaarden navigatie">
                <div class="flex flex-wrap gap-2 justify-center">
                                         <a href="#acceptance" class="keyboard-nav-item bg-white text-gray-800 border border-gray-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 focus:bg-gray-50 transition-colors" role="menuitem">Aanvaarding</a>
                                         <a href="#services" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Diensten</a>
                     <a href="#user-conduct" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Gedragsregels</a>
                     <a href="#content" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Content</a>
                     <a href="#liability" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Aansprakelijkheid</a>
                     <a href="#contact-terms" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Contact</a>
                </div>
            </nav>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Introductie -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Introductie</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Welkom bij PolitiekPraat! Deze gebruiksvoorwaarden ("Voorwaarden") bepalen uw gebruik van onze website 
                            en diensten. Door gebruik te maken van PolitiekPraat gaat u akkoord met deze voorwaarden.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            PolitiekPraat is een platform voor politieke discussie, nieuws en educatie. Wij streven naar een 
                            respectvolle en informatieve omgeving voor alle gebruikers.
                        </p>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 my-6">
                            <p class="text-red-800 font-medium">
                                <strong>Belangrijke informatie:</strong><br>
                                <strong>Laatst bijgewerkt:</strong> <?php echo date('d F Y'); ?><br>
                                <strong>Effectief vanaf:</strong> <?php echo date('d F Y'); ?><br>
                                <strong>Toepasselijk recht:</strong> Nederlands recht
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Aanvaarding van voorwaarden -->
                <div id="acceptance" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        1. Aanvaarding van Voorwaarden
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Door toegang te krijgen tot en gebruik te maken van PolitiekPraat, verklaart u dat u:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                            <li>Deze gebruiksvoorwaarden hebt gelezen en begrepen</li>
                            <li>Akkoord gaat met alle bepalingen in deze voorwaarden</li>
                            <li>Ten minste 16 jaar oud bent (of toestemming van ouders/voogd heeft)</li>
                            <li>De bevoegdheid heeft om juridisch bindende overeenkomsten aan te gaan</li>
                            <li>Zich zult houden aan alle toepasselijke wet- en regelgeving</li>
                        </ul>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Wijzigingen van Voorwaarden
                            </h3>
                            <p class="text-yellow-800 text-sm">
                                Wij behouden ons het recht voor om deze voorwaarden op elk moment te wijzigen. 
                                Significante wijzigingen worden aangekondigd via onze website of per e-mail. 
                                Voortgezet gebruik na wijzigingen betekent aanvaarding van de nieuwe voorwaarden.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Onze diensten -->
                <div id="services" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        2. Onze Diensten
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            PolitiekPraat biedt de volgende diensten aan:
                        </p>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Gratis Diensten
                                </h3>
                                <ul class="text-blue-700 text-sm space-y-1">
                                    <li>• Toegang tot politiek nieuws en analyses</li>
                                    <li>• Stemwijzer en partij vergelijkingstools</li>
                                    <li>• Politiek kompas en educatieve content</li>
                                    <li>• Community forum en discussieplatform</li>
                                    <li>• Nieuwsbrief abonnement</li>
                                </ul>
                            </div>
                            
                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Premium Features
                                </h3>
                                <ul class="text-green-700 text-sm space-y-1">
                                    <li>• Geavanceerde analytics en inzichten</li>
                                    <li>• Advertentievrije ervaring</li>
                                    <li>• Exclusieve content en interviews</li>
                                    <li>• Early access tot nieuwe features</li>
                                    <li>• Priority support</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Servicebeschikbaarheid</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Wij streven ernaar onze diensten 24/7 beschikbaar te houden, maar kunnen geen 100% uptime garanderen. 
                                Onderhoud en updates kunnen tijdelijke onderbreking veroorzaken.
                            </p>
                            <div class="grid md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-800">Gepland onderhoud:</span>
                                    <p class="text-gray-600">Wordt van tevoren aangekondigd</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800">Noodonderhoud:</span>
                                    <p class="text-gray-600">Zo snel mogelijk hersteld</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800">Service updates:</span>
                                    <p class="text-gray-600">Meestal buiten kantooruren</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gebruikersgedrag -->
                <div id="user-conduct" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        3. Gedragsregels voor Gebruikers
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Om een respectvolle en constructieve omgeving te behouden, verwachten wij dat alle gebruikers zich aan de volgende regels houden:
                        </p>

                        <div class="space-y-6">
                            <!-- Toegestaan gedrag -->
                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Toegestaan en Aangemoedigd
                                </h3>
                                <ul class="text-green-700 text-sm space-y-1">
                                    <li>• Respectvolle politieke discussie en meningsuiting</li>
                                    <li>• Het delen van relevante, feitelijke informatie</li>
                                    <li>• Constructieve kritiek en feedback</li>
                                    <li>• Het stellen van vragen voor educatieve doeleinden</li>
                                    <li>• Het delen van persoonlijke politieke ervaringen</li>
                                    <li>• Het modereren van eigen bijdragen en reacties</li>
                                </ul>
                            </div>

                            <!-- Verboden gedrag -->
                            <div class="border border-red-200 bg-red-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Strikt Verboden
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-red-800 mb-2">Schadelijke Content</h4>
                                        <ul class="text-red-700 text-sm space-y-1">
                                            <li>• Haatdragende uitingen of discriminatie</li>
                                            <li>• Gewelddadige of dreigende taal</li>
                                            <li>• Pesten, intimidatie of harassment</li>
                                            <li>• Pornografische of seksueel expliciete content</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-red-800 mb-2">Illegale Activiteiten</h4>
                                        <ul class="text-red-700 text-sm space-y-1">
                                            <li>• Spam of ongewenste commercial berichten</li>
                                            <li>• Verspreiding van malware of virussen</li>
                                            <li>• Inbreuk op intellectuele eigendomsrechten</li>
                                            <li>• Identity theft of impersonation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-orange-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Gevolgen van Overtreding
                            </h3>
                            <p class="text-orange-800 text-sm mb-3">
                                Overtredingen van deze regels kunnen leiden tot:
                            </p>
                            <div class="grid md:grid-cols-4 gap-3 text-sm">
                                <div class="text-center">
                                    <span class="font-medium text-orange-800">Waarschuwing</span>
                                    <p class="text-orange-700">Eerste overtreding</p>
                                </div>
                                <div class="text-center">
                                    <span class="font-medium text-orange-800">Tijdelijke Blokkering</span>
                                    <p class="text-orange-700">7-30 dagen</p>
                                </div>
                                <div class="text-center">
                                    <span class="font-medium text-orange-800">Account Opschorting</span>
                                    <p class="text-orange-700">Herhaalde overtredingen</p>
                                </div>
                                <div class="text-center">
                                    <span class="font-medium text-orange-800">Permanente Ban</span>
                                    <p class="text-orange-700">Ernstige overtredingen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content en intellectuele eigendom -->
                <div id="content" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        4. Content en Intellectuele Eigendom
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <div class="space-y-6">
                            <!-- Onze content -->
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                    </svg>
                                    PolitiekPraat Content
                                </h3>
                                <p class="text-blue-700 text-sm mb-3">
                                    Alle content op PolitiekPraat, inclusief teksten, afbeeldingen, logo's, designs en software, 
                                    is eigendom van PolitiekPraat of gelicentieerd voor gebruik.
                                </p>
                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-800">Toegestaan gebruik:</span>
                                        <ul class="text-blue-700 mt-1 space-y-1">
                                            <li>• Persoonlijk, niet-commercieel gebruik</li>
                                            <li>• Educatieve doeleinden met bronvermelding</li>
                                            <li>• Delen via social media met link naar origineel</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Verboden gebruik:</span>
                                        <ul class="text-blue-700 mt-1 space-y-1">
                                            <li>• Commerciële exploitatie zonder toestemming</li>
                                            <li>• Bewerking of modificatie zonder toestemming</li>
                                            <li>• Heruitgave als eigen werk</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Gebruiker content -->
                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                    Gebruiker-Gegenereerde Content
                                </h3>
                                <p class="text-green-700 text-sm mb-3">
                                    Wanneer u content plaatst op PolitiekPraat (blogs, comments, forum posts), behoudt u het eigendom 
                                    maar verleent u ons bepaalde rechten voor het gebruik van de content.
                                </p>
                                
                                <div class="space-y-3">
                                    <div>
                                        <span class="font-medium text-green-800">Uw rechten:</span>
                                        <ul class="text-green-700 text-sm mt-1 space-y-1">
                                            <li>• U behoudt het eigendom van uw originele content</li>
                                            <li>• U kunt uw content bewerken of verwijderen</li>
                                            <li>• U bepaalt onder welke licentie u publiceert</li>
                                        </ul>
                                    </div>
                                    
                                    <div>
                                        <span class="font-medium text-green-800">Onze rechten:</span>
                                        <ul class="text-green-700 text-sm mt-1 space-y-1">
                                            <li>• Niet-exclusieve licentie om uw content te tonen en distribueren</li>
                                            <li>• Recht om content te modereren of te verwijderen</li>
                                            <li>• Mogelijkheid om content te gebruiken voor promotie van de platform</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- DMCA compliance -->
                            <div class="border border-purple-200 bg-purple-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Copyright en DMCA Compliance
                                </h3>
                                <p class="text-purple-700 text-sm mb-3">
                                    Wij respecteren intellectuele eigendomsrechten en verwachten hetzelfde van onze gebruikers. 
                                    Voor copyright claims volgen wij de DMCA procedure.
                                </p>
                                
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-medium text-purple-800 mb-2">DMCA Takedown Verzoek Indienen</h4>
                                    <p class="text-purple-700 text-sm mb-2">
                                        Stuur een e-mail naar <a href="mailto:legal@politiekpraat.nl" class="underline">legal@politiekpraat.nl</a> met:
                                    </p>
                                    <ul class="text-purple-700 text-sm space-y-1">
                                        <li>• Identificatie van het beschermde werk</li>
                                        <li>• Locatie van het inbreukmakende materiaal</li>
                                        <li>• Uw contactinformatie</li>
                                        <li>• Verklaring van goed geloof</li>
                                        <li>• Elektronische of fysieke handtekening</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aansprakelijkheid -->
                <div id="liability" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        5. Aansprakelijkheid en Disclaimers
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <div class="space-y-6">
                            <!-- Service disclaimer -->
                            <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Service "As-Is" Disclaimer
                                </h3>
                                <p class="text-yellow-800 text-sm mb-3">
                                    PolitiekPraat wordt aangeboden "zoals het is" en "zoals beschikbaar" zonder enige vorm van garantie, 
                                    expliciet of impliciet. Wij maken geen garanties betreffende:
                                </p>
                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                    <ul class="text-yellow-800 space-y-1">
                                        <li>• Ononderbroken beschikbaarheid van de service</li>
                                        <li>• Volledigheid of nauwkeurigheid van informatie</li>
                                        <li>• Geschiktheid voor specifieke doeleinden</li>
                                    </ul>
                                    <ul class="text-yellow-800 space-y-1">
                                        <li>• Vrijheid van fouten of virussen</li>
                                        <li>• Veiligheid van uw gegevens</li>
                                        <li>• Resultaten van het gebruik van onze tools</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Beperking van aansprakelijkheid -->
                            <div class="border border-red-200 bg-red-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Beperking van Aansprakelijkheid
                                </h3>
                                <p class="text-red-800 text-sm mb-3">
                                    Voor zover wettelijk toegestaan is onze aansprakelijkheid beperkt. Wij zijn niet aansprakelijk voor:
                                </p>
                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-red-800">Directe schade:</span>
                                        <ul class="text-red-700 mt-1 space-y-1">
                                            <li>• Verlies van gegevens</li>
                                            <li>• Bedrijfsonderbreking</li>
                                            <li>• Gederfde winst</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <span class="font-medium text-red-800">Indirecte schade:</span>
                                        <ul class="text-red-700 mt-1 space-y-1">
                                            <li>• Gevolgen van politieke beslissingen</li>
                                            <li>• Reputatieschade</li>
                                            <li>• Consequentiële verliezen</li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="text-red-800 text-sm mt-3 font-medium">
                                    Maximale aansprakelijkheid: €100 of het bedrag dat u in de afgelopen 12 maanden heeft betaald voor onze diensten.
                                </p>
                            </div>

                            <!-- Indemnificatie -->
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Gebruikersindemnificatie
                                </h3>
                                <p class="text-blue-800 text-sm">
                                    U stemt ermee in om PolitiekPraat schadeloos te stellen voor alle claims, schade, kosten 
                                    en uitgaven (inclusief redelijke advocaatkosten) die voortvloeien uit:
                                </p>
                                <ul class="text-blue-700 text-sm mt-3 space-y-1">
                                    <li>• Uw gebruik van onze diensten</li>
                                    <li>• Uw schending van deze voorwaarden</li>
                                    <li>• Content die u plaatst op ons platform</li>
                                    <li>• Uw schending van rechten van derden</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overige bepalingen -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        6. Overige Bepalingen
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Toepasselijk Recht</h3>
                                <p class="text-gray-600 text-sm">
                                    Deze voorwaarden worden beheerst door Nederlands recht. 
                                    Geschillen worden voorgelegd aan de bevoegde Nederlandse rechter.
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Overdraagbaarheid</h3>
                                <p class="text-gray-600 text-sm">
                                    U mag uw rechten onder deze voorwaarden niet overdragen. 
                                    Wij kunnen onze rechten en verplichtingen overdragen aan derden.
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Gehele Overeenkomst</h3>
                                <p class="text-gray-600 text-sm">
                                    Deze voorwaarden, samen met ons Privacy Policy en Cookie Policy, 
                                    vormen de gehele overeenkomst tussen u en PolitiekPraat.
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Nietigheid Clausule</h3>
                                <p class="text-gray-600 text-sm">
                                    Als een deel van deze voorwaarden nietig wordt verklaard, 
                                    blijven de overige bepalingen van kracht.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact informatie -->
                <div id="contact-terms" class="bg-gradient-to-br from-primary via-primary-dark to-secondary rounded-2xl text-white p-8 text-center" tabindex="-1">
                    <h2 class="text-2xl font-bold mb-4 text-white">Vragen over de Gebruiksvoorwaarden?</h2>
                    <p class="text-gray-100 mb-6 max-w-2xl mx-auto">
                        Heeft u vragen over deze gebruiksvoorwaarden of onze diensten? 
                        Wij helpen u graag verder.
                    </p>
                    <div class="grid md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                        <div class="bg-white/20 backdrop-blur rounded-lg p-4 border border-white/30">
                            <h3 class="font-semibold mb-2 text-white">Algemene Vragen</h3>
                            <a href="mailto:info@politiekpraat.nl" class="text-gray-100 hover:text-white transition-colors">
                                info@politiekpraat.nl
                            </a>
                        </div>
                        <div class="bg-white/20 backdrop-blur rounded-lg p-4 border border-white/30">
                            <h3 class="font-semibold mb-2 text-white">Juridische Zaken</h3>
                            <a href="mailto:legal@politiekpraat.nl" class="text-gray-100 hover:text-white transition-colors">
                                legal@politiekpraat.nl
                            </a>
                        </div>
                        <div class="bg-white/20 backdrop-blur rounded-lg p-4 border border-white/30">
                            <h3 class="font-semibold mb-2 text-white">Privacy & Cookies</h3>
                            <a href="mailto:privacy@politiekpraat.nl" class="text-gray-100 hover:text-white transition-colors">
                                privacy@politiekpraat.nl
                            </a>
                        </div>
                    </div>
                    <div class="mt-6 text-gray-100 text-sm">
                        <p>Responstijd: Binnen 48 uur op werkdagen</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
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
                
                // Screen reader announcement
                if (window.announceToScreenReader) {
                    const heading = target.querySelector('h2, h3');
                    if (heading) {
                        window.announceToScreenReader(`Genavigeerd naar sectie: ${heading.textContent}`);
                    }
                }
            }
        });
    });

    // Enhanced accessibility for interactive elements
    document.querySelectorAll('button, [role="button"]').forEach(element => {
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });

    // Announce page load
    setTimeout(() => {
        if (window.announceToScreenReader) {
            window.announceToScreenReader('Gebruiksvoorwaarden pagina geladen. Gebruik de navigatie om naar specifieke secties te gaan.');
        }
    }, 1000);
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 