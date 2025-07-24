<?php
$pageTitle = "Cookie Policy | PolitiekPraat";
$pageDescription = "Cookie Policy van PolitiekPraat - Hoe wij cookies gebruiken en hoe u uw voorkeuren kunt beheren.";
$pageKeywords = "cookie policy, cookies, tracking, privacy, voorkeuren, GDPR";

require_once 'views/templates/header.php';
?>

<!-- Cookie Policy Content -->
<main id="main-content" class="bg-gray-50 min-h-screen privacy-content" tabindex="-1">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-block p-4 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <span class="text-6xl">🍪</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Cookie Policy</h1>
                <p class="text-xl text-white max-w-2xl mx-auto">
                    Alles wat u moet weten over hoe wij cookies gebruiken op onze website
                </p>
                <div class="mt-8 flex items-center justify-center space-x-4 text-white">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        GDPR Compliant
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Transparant
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Section -->
    <section class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <nav class="keyboard-nav-group" role="menubar" aria-label="Cookie Policy navigatie">
                <div class="flex flex-wrap gap-2 justify-center">
                    <a href="#what-are-cookies" class="keyboard-nav-item bg-white text-gray-800 border border-gray-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 focus:bg-gray-50 transition-colors" role="menuitem">Wat zijn cookies?</a>
                    <a href="#cookie-types" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Soorten cookies</a>
                    <a href="#cookie-management" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Beheer</a>
                    <a href="#third-party" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Derde partijen</a>
                    <a href="#contact-cookies" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Contact</a>
                </div>
            </nav>
        </div>
    </section>

    <!-- Cookie Policy Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Introductie -->
                <div id="what-are-cookies" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Wat zijn cookies?</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Cookies zijn kleine tekstbestanden die op uw apparaat worden geplaatst wanneer u onze website bezoekt. 
                            Ze helpen ons om uw voorkeuren te onthouden, uw ervaring te personaliseren en onze website te verbeteren.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Deze Cookie Policy legt uit welke cookies wij gebruiken, waarom wij ze gebruiken en hoe u uw cookie-voorkeuren kunt beheren.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 my-6">
                            <p class="text-blue-800 font-medium">
                                <strong>Laatst bijgewerkt:</strong> <?php echo date('d F Y'); ?><br>
                                <strong>Effectief vanaf:</strong> <?php echo date('d F Y'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Soorten cookies -->
                <div id="cookie-types" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Soorten cookies die wij gebruiken
                    </h2>
                    
                    <div class="space-y-8">
                        <!-- Noodzakelijke cookies -->
                        <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-green-800">Noodzakelijke Cookies</h3>
                                    <p class="text-green-600 text-sm">Altijd actief - vereist voor basisfunctionaliteit</p>
                                </div>
                            </div>
                            
                            <p class="text-green-700 mb-4">
                                Deze cookies zijn essentieel voor het functioneren van onze website. Zonder deze cookies 
                                kunnen bepaalde delen van onze website niet correct werken.
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-green-800 mb-2">Sessie Management</h4>
                                    <ul class="text-sm text-green-700 space-y-1">
                                        <li>• Inlogstatus onthouden</li>
                                        <li>• Winkelwagen beheer</li>
                                        <li>• Formulier gegevens bewaren</li>
                                    </ul>
                                    <p class="text-xs text-green-600 mt-2">Bewaartermijn: Tot einde sessie</p>
                                </div>
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-green-800 mb-2">Beveiliging</h4>
                                    <ul class="text-sm text-green-700 space-y-1">
                                        <li>• CSRF bescherming</li>
                                        <li>• Beveiligings tokens</li>
                                        <li>• Anti-spam maatregelen</li>
                                    </ul>
                                    <p class="text-xs text-green-600 mt-2">Bewaartermijn: 24 uur</p>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics cookies -->
                        <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-blue-800">Analytics Cookies</h3>
                                    <p class="text-blue-600 text-sm">Optioneel - help ons onze website te verbeteren</p>
                                </div>
                            </div>
                            
                            <p class="text-blue-700 mb-4">
                                Deze cookies helpen ons begrijpen hoe bezoekers onze website gebruiken door anonieme 
                                informatie te verzamelen over bezoekersgedrag.
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">Google Analytics</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• _ga - Unieke bezoekers identificeren</li>
                                        <li>• _gid - Sessie informatie</li>
                                        <li>• _gat - Request rate beperking</li>
                                    </ul>
                                    <p class="text-xs text-blue-600 mt-2">Bewaartermijn: 2 jaar</p>
                                </div>
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">Interne Analytics</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Pagina weergave tracking</li>
                                        <li>• Gebruikersstroom analyse</li>
                                        <li>• Performance metingen</li>
                                    </ul>
                                    <p class="text-xs text-blue-600 mt-2">Bewaartermijn: 1 jaar</p>
                                </div>
                            </div>
                        </div>

                        <!-- Functionele cookies -->
                        <div class="border border-purple-200 bg-purple-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-purple-800">Functionele Cookies</h3>
                                    <p class="text-purple-600 text-sm">Optioneel - verbetert uw gebruikerservaring</p>
                                </div>
                            </div>
                            
                            <p class="text-purple-700 mb-4">
                                Deze cookies onthouden uw voorkeuren en instellingen om uw ervaring op onze website 
                                te personaliseren en te verbeteren.
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-purple-800 mb-2">Voorkeuren</h4>
                                    <ul class="text-sm text-purple-700 space-y-1">
                                        <li>• Taalvoorkeur</li>
                                        <li>• Thema (donker/licht modus)</li>
                                        <li>• Lettergrootte instellingen</li>
                                    </ul>
                                    <p class="text-xs text-purple-600 mt-2">Bewaartermijn: 1 jaar</p>
                                </div>
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-purple-800 mb-2">Toegankelijkheid</h4>
                                    <ul class="text-sm text-purple-700 space-y-1">
                                        <li>• High contrast modus</li>
                                        <li>• Reduced motion instellingen</li>
                                        <li>• Keyboard navigation voorkeuren</li>
                                    </ul>
                                    <p class="text-xs text-purple-600 mt-2">Bewaartermijn: 1 jaar</p>
                                </div>
                            </div>
                        </div>

                        <!-- Marketing cookies -->
                        <div class="border border-orange-200 bg-orange-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-orange-800">Marketing Cookies</h3>
                                    <p class="text-orange-600 text-sm">Optioneel - voor gerichte advertenties</p>
                                </div>
                            </div>
                            
                            <p class="text-orange-700 mb-4">
                                Deze cookies worden gebruikt om u relevante advertenties te tonen en de effectiviteit 
                                van onze marketingcampagnes te meten.
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-orange-800 mb-2">Advertentie Tracking</h4>
                                    <ul class="text-sm text-orange-700 space-y-1">
                                        <li>• Google Ads conversie tracking</li>
                                        <li>• Facebook Pixel</li>
                                        <li>• Retargeting cookies</li>
                                    </ul>
                                    <p class="text-xs text-orange-600 mt-2">Bewaartermijn: 90 dagen</p>
                                </div>
                                <div class="bg-white rounded p-4">
                                    <h4 class="font-semibold text-orange-800 mb-2">Social Media</h4>
                                    <ul class="text-sm text-orange-700 space-y-1">
                                        <li>• Social media integraties</li>
                                        <li>• Share functionaliteit</li>
                                        <li>• Embedded content</li>
                                    </ul>
                                    <p class="text-xs text-orange-600 mt-2">Bewaartermijn: 30 dagen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cookie beheer -->
                <div id="cookie-management" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Uw Cookie Voorkeuren Beheren
                    </h2>
                    
                    <div class="space-y-6">
                        <p class="text-gray-700 leading-relaxed">
                            U heeft volledige controle over welke cookies op uw apparaat worden geplaatst. 
                            Hieronder vindt u verschillende manieren om uw cookie-voorkeuren te beheren:
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg>
                                    Via Onze Website
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    Gebruik onze cookie instellingen om uw voorkeuren direct te wijzigen:
                                </p>
                                <button onclick="window.cookieConsent.showSettings()" 
                                        class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Cookie Instellingen Openen
                                </button>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h3.5a3.5 3.5 0 100-7H11V3.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 4.414V6H5.5A3.5 3.5 0 002 9.5z" clip-rule="evenodd"/>
                                    </svg>
                                    Via Uw Browser
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    De meeste browsers bieden opties om cookies te beheren:
                                </p>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Chrome:</span>
                                        <span class="text-blue-600">Instellingen → Privacy</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Firefox:</span>
                                        <span class="text-blue-600">Opties → Privacy</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Safari:</span>
                                        <span class="text-blue-600">Voorkeuren → Privacy</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Edge:</span>
                                        <span class="text-blue-600">Instellingen → Privacy</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Belangrijk om te weten
                            </h3>
                            <ul class="text-yellow-800 text-sm space-y-1 list-disc list-inside">
                                <li>Het uitschakelen van cookies kan de functionaliteit van onze website beperken</li>
                                <li>Noodzakelijke cookies kunnen niet worden uitgeschakeld</li>
                                <li>Uw voorkeuren worden onthouden voor toekomstige bezoeken</li>
                                <li>U kunt uw keuzes op elk moment wijzigen</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Derde partijen -->
                <div id="third-party" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cookies van Derde Partijen
                    </h2>
                    
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Sommige cookies op onze website worden geplaatst door derde partijen. 
                        Hier is een overzicht van deze services en hun cookie policies:
                    </p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5 mr-2">
                                Google Analytics
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                Wij gebruiken Google Analytics om websiteverkeer en gebruikersgedrag te analyseren.
                            </p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Cookie namen:</span>
                                    <span class="text-gray-800">_ga, _gid, _gat</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bewaartermijn:</span>
                                    <span class="text-gray-800">2 jaar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Privacy Policy:</span>
                                    <a href="https://policies.google.com/privacy" class="text-blue-600 underline" target="_blank">Google Privacy</a>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/yo/r/iRmz9lCMBD2.ico" alt="Facebook" class="w-5 h-5 mr-2">
                                Facebook Pixel
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                Gebruikt voor het meten van advertentie-effectiviteit en retargeting.
                            </p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Cookie namen:</span>
                                    <span class="text-gray-800">_fbp, fbq</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bewaartermijn:</span>
                                    <span class="text-gray-800">90 dagen</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Privacy Policy:</span>
                                    <a href="https://www.facebook.com/privacy/explanation" class="text-blue-600 underline" target="_blank">Facebook Privacy</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">Opt-out Opties</h3>
                        <p class="text-blue-700 text-sm mb-4">
                            Voor derde partij cookies kunt u ook direct opt-out via deze links:
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://tools.google.com/dlpage/gaoptout" 
                               class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded hover:bg-blue-50 transition-colors text-sm"
                               target="_blank">
                                Google Analytics Opt-out
                            </a>
                            <a href="https://www.facebook.com/ads/preferences" 
                               class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded hover:bg-blue-50 transition-colors text-sm"
                               target="_blank">
                                Facebook Ads Opt-out
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact en updates -->
                <div id="contact-cookies" class="grid md:grid-cols-2 gap-8 mb-8" tabindex="-1">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Vragen over Cookies?
                        </h2>
                        <div class="text-sm text-gray-700">
                            <p class="mb-4">
                                Heeft u vragen over onze cookie policy of hoe wij cookies gebruiken? 
                                Neem gerust contact met ons op:
                            </p>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">E-mail:</span>
                                    <a href="mailto:privacy@politiekpraat.nl" class="text-primary underline ml-2">privacy@politiekpraat.nl</a>
                                </div>
                                <div>
                                    <span class="font-medium">Algemeen:</span>
                                    <a href="mailto:info@politiekpraat.nl" class="text-primary underline ml-2">info@politiekpraat.nl</a>
                                </div>
                                <div>
                                    <span class="font-medium">Response tijd:</span>
                                    <span class="ml-2">Binnen 48 uur</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Updates van deze Policy
                        </h2>
                        <div class="text-sm text-gray-700">
                            <p class="mb-4">
                                Wij kunnen deze Cookie Policy van tijd tot tijd bijwerken om veranderingen 
                                in onze practices of om wettelijke redenen weer te geven.
                            </p>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Laatste update:</span>
                                    <span class="ml-2"><?php echo date('d F Y'); ?></span>
                                </div>
                                <div>
                                    <span class="font-medium">Wijzigingen:</span>
                                    <span class="ml-2">U ontvangt een melding</span>
                                </div>
                                <div>
                                    <span class="font-medium">Nieuwe cookies:</span>
                                    <span class="ml-2">Vereisen nieuwe toestemming</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="bg-gradient-to-br from-primary via-primary-dark to-secondary rounded-2xl text-white p-8 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-white">Beheer Uw Cookie Voorkeuren</h2>
                    <p class="text-gray-100 mb-6 max-w-2xl mx-auto">
                        U heeft volledige controle over uw privacy. Pas uw cookie-instellingen aan naar uw wensen.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <button onclick="window.cookieConsent.showSettings()" 
                                class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            Cookie Instellingen
                        </button>
                        <a href="<?php echo URLROOT; ?>/privacy-policy" 
                           class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            Lees Privacy Policy
                        </a>
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
                    item.classList.add('bg-white', 'text-primary', 'border-2', 'border-primary');
                });
                
                // Add active class to current nav item
                if (navLink) {
                    navLink.classList.remove('bg-white', 'text-primary', 'border-2', 'border-primary');
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

    // Cookie demonstraties
    const demonstrationButtons = document.querySelectorAll('[data-demo-cookie]');
    demonstrationButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cookieType = this.dataset.demoCookie;
            
            // Toon wat elke cookie type doet
            let message = '';
            switch(cookieType) {
                case 'necessary':
                    message = 'Noodzakelijke cookies zijn actief - deze zorgen ervoor dat de website correct functioneert.';
                    break;
                case 'analytics':
                    message = 'Analytics cookies zouden uw bezoek anoniem tracken voor website verbetering.';
                    break;
                case 'functional':
                    message = 'Functionele cookies zouden uw voorkeuren onthouden.';
                    break;
                case 'marketing':
                    message = 'Marketing cookies zouden uw interesses bijhouden voor gerichte advertenties.';
                    break;
            }
            
            if (window.announceToScreenReader) {
                window.announceToScreenReader(message);
            }
            
            // Toon een tijdelijke melding
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-600 text-white p-4 rounded-lg shadow-lg z-50 max-w-md';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 