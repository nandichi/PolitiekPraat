<?php
$pageTitle = "Privacy Policy | PolitiekPraat";
$pageDescription = "Privacy Policy van PolitiekPraat - Hoe wij uw persoonlijke gegevens verzamelen, gebruiken en beschermen conform de AVG/GDPR.";
$pageKeywords = "privacy policy, AVG, GDPR, persoonlijke gegevens, cookies, PolitiekPraat";

require_once 'views/templates/header.php';
?>

<!-- Privacy Policy Content -->
<main id="main-content" class="bg-gray-50 min-h-screen privacy-content" tabindex="-1">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="inline-block p-4 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <span class="text-6xl">🔒</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Privacy Policy</h1>
                <p class="text-xl text-white max-w-2xl mx-auto">
                    Transparantie over hoe wij uw persoonlijke gegevens verzamelen, gebruiken en beschermen
                </p>
                <div class="mt-8 flex items-center justify-center space-x-4 text-blue-200">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        AVG/GDPR Compliant
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Laatste update: <?php echo date('d F Y'); ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Navigation -->
    <section class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <nav class="keyboard-nav-group" role="menubar" aria-label="Privacy Policy navigatie">
                <div class="flex flex-wrap gap-2 justify-center">
                                         <a href="#contact-info" class="keyboard-nav-item bg-white text-gray-800 border border-gray-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 focus:bg-gray-50 transition-colors" role="menuitem">Contactgegevens</a>
                                         <a href="#data-collection" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Gegevensverzameling</a>
                     <a href="#data-usage" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Gebruik gegevens</a>
                     <a href="#cookies" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Cookies</a>
                     <a href="#rights" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Uw rechten</a>
                     <a href="#security" class="keyboard-nav-item bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-gray-800 hover:border-gray-400 focus:bg-white focus:text-gray-800 transition-colors" role="menuitem">Beveiliging</a>
                </div>
            </nav>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Introductie -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Introductie</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            PolitiekPraat ("wij", "ons", "onze") respecteert uw privacy en zet zich in voor de bescherming van uw persoonlijke gegevens. Deze Privacy Policy legt uit hoe wij uw gegevens verzamelen, gebruiken, delen en beschermen wanneer u onze website <strong>politiekpraat.nl</strong> bezoekt of onze diensten gebruikt.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Deze Privacy Policy is van toepassing op alle bezoekers, gebruikers en anderen die toegang hebben tot of gebruik maken van onze website en diensten. Door gebruik te maken van onze website, gaat u akkoord met de gegevenspraktijken die in deze Privacy Policy worden beschreven.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 my-6">
                            <p class="text-blue-800 font-medium">
                                <strong>Laatst bijgewerkt:</strong> <?php echo date('d F Y'); ?><br>
                                <strong>Effective datum:</strong> <?php echo date('d F Y'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contactgegevens -->
                <div id="contact-info" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        1. Contactgegevens van de Verwerkingsverantwoordelijke
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verwerkingsverantwoordelijke:</h3>
                            <div class="space-y-2 text-gray-700">
                                <p><strong>Naam:</strong> PolitiekPraat</p>
                                <p><strong>Website:</strong> <a href="https://politiekpraat.nl" class="text-primary underline">politiekpraat.nl</a></p>
                                <p><strong>E-mail:</strong> <a href="mailto:privacy@politiekpraat.nl" class="text-primary underline">privacy@politiekpraat.nl</a></p>
                                <p><strong>Algemeen contact:</strong> <a href="mailto:info@politiekpraat.nl" class="text-primary underline">info@politiekpraat.nl</a></p>
                            </div>
                        </div>
                        <p class="text-gray-700 leading-relaxed mt-4">
                            Voor vragen over deze Privacy Policy of het gebruik van uw persoonlijke gegevens kunt u contact met ons opnemen via bovenstaande contactgegevens.
                        </p>
                    </div>
                </div>

                <!-- Gegevensverzameling -->
                <div id="data-collection" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        2. Welke Gegevens Verzamelen Wij
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        
                        <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2.1 Persoonlijke Gegevens die U Vrijwillig Verstrekt</h3>
                        <div class="space-y-4">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Account Registratie:</h4>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li>Naam (voor- en achternaam)</li>
                                    <li>E-mailadres</li>
                                    <li>Wachtwoord (versleuteld opgeslagen)</li>
                                    <li>Profielfoto (optioneel)</li>
                                    <li>Biografie (optioneel)</li>
                                </ul>
                            </div>
                            
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Communicatie:</h4>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li>Berichten via contactformulieren</li>
                                    <li>E-mails die u naar ons verstuurt</li>
                                    <li>Nieuwsbrief inschrijvingen</li>
                                    <li>Reacties en forum posts</li>
                                </ul>
                            </div>

                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Website Interactie:</h4>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li>Blog reacties en beoordelingen</li>
                                    <li>Stemwijzer resultaten (geanonimiseerd)</li>
                                    <li>Poll stemmen</li>
                                    <li>Likes en shares</li>
                                </ul>
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2.2 Automatisch Verzamelde Gegevens</h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-semibold text-gray-900 mb-3">Technische Gegevens:</h4>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li>IP-adres (geanonimiseerd voor analytics)</li>
                                    <li>Browser type en versie</li>
                                    <li>Besturingssysteem</li>
                                    <li>Schermresolutie</li>
                                    <li>Taalvoorkeuren</li>
                                    <li>Tijdstip van bezoek</li>
                                </ul>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-semibold text-gray-900 mb-3">Gebruiksgegevens:</h4>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li>Bezochte pagina's en volgorde</li>
                                    <li>Tijd besteed op pagina's</li>
                                    <li>Klikgedrag en navigatiepatronen</li>
                                    <li>Zoektermen gebruikt op onze website</li>
                                    <li>Verwijzende websites (referrers)</li>
                                </ul>
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2.3 Gegevens van Derden</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Wij verzamelen geen persoonlijke gegevens van derde partijen zonder uw uitdrukkelijke toestemming. Indien u ervoor kiest om in te loggen via sociale media platforms (indien geïmplementeerd), verzamelen wij alleen de gegevens die u ons toestaat te ontvangen.
                        </p>
                    </div>
                </div>

                <!-- Gebruik van gegevens -->
                <div id="data-usage" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        3. Hoe Wij Uw Gegevens Gebruiken
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Wij gebruiken uw persoonlijke gegevens alleen voor legitieme doeleinden en op basis van een geldige rechtsgrond volgens de AVG. Hieronder vindt u een overzicht van hoe en waarom wij uw gegevens gebruiken:
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-600 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Dienstverlening
                                </h3>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Account beheer en authenticatie</li>
                                    <li>• Personalisatie van content</li>
                                    <li>• Opslaan van voorkeuren</li>
                                    <li>• Stemwijzer functionaliteit</li>
                                </ul>
                                <p class="text-xs text-gray-500 mt-2"><strong>Rechtsgrond:</strong> Contractuele verplichting</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-600 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Communicatie
                                </h3>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Beantwoorden van vragen</li>
                                    <li>• Nieuwsbrief verzending</li>
                                    <li>• Belangrijke updates</li>
                                    <li>• Technische support</li>
                                </ul>
                                <p class="text-xs text-gray-500 mt-2"><strong>Rechtsgrond:</strong> Toestemming / Gerechtvaardigd belang</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-600 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Verbetering & Analyse
                                </h3>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Website prestaties meten</li>
                                    <li>• Gebruikerservaring optimaliseren</li>
                                    <li>• Content aanpassen aan interesse</li>
                                    <li>• Technische problemen oplossen</li>
                                </ul>
                                <p class="text-xs text-gray-500 mt-2"><strong>Rechtsgrond:</strong> Gerechtvaardigd belang</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-600 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Veiligheid & Naleving
                                </h3>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Fraude detectie</li>
                                    <li>• Spam voorkoming</li>
                                    <li>• Wettelijke verplichtingen</li>
                                    <li>• Beveiliging van accounts</li>
                                </ul>
                                <p class="text-xs text-gray-500 mt-2"><strong>Rechtsgrond:</strong> Gerechtvaardigd belang / Wettelijke verplichting</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mt-8">
                            <p class="text-yellow-800">
                                <strong>Belangrijk:</strong> Wij verkopen, verhuren of delen uw persoonlijke gegevens nooit met derde partijen voor commerciële doeleinden zonder uw uitdrukkelijke toestemming.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Cookies sectie -->
                <div id="cookies" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                        </svg>
                        4. Cookies en Tracking Technologieën
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Onze website gebruikt cookies en vergelijkbare technologieën om uw ervaring te verbeteren en onze diensten te optimaliseren. U heeft controle over welke cookies worden geplaatst.
                        </p>

                        <div class="space-y-6">
                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                                    Noodzakelijke Cookies (Altijd Actief)
                                </h3>
                                <p class="text-green-700 mb-3">Deze cookies zijn essentieel voor het functioneren van onze website en kunnen niet worden uitgeschakeld.</p>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• Sessie cookies voor inloggen</li>
                                    <li>• Beveiligings tokens</li>
                                    <li>• Taalvoorkeuren</li>
                                    <li>• Cookie consent voorkeuren</li>
                                </ul>
                                <p class="text-xs text-green-600 mt-2"><strong>Bewaartermijn:</strong> Sessie of 1 jaar</p>
                            </div>

                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                                    Analytics Cookies
                                </h3>
                                <p class="text-blue-700 mb-3">Helpen ons begrijpen hoe bezoekers onze website gebruiken via anonieme statistieken.</p>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Google Analytics (_ga, _gid)</li>
                                    <li>• Pagina weergave statistieken</li>
                                    <li>• Gebruikersstroom analyse</li>
                                    <li>• Prestatie metingen</li>
                                </ul>
                                <p class="text-xs text-blue-600 mt-2"><strong>Bewaartermijn:</strong> 2 jaar</p>
                            </div>

                            <div class="border border-purple-200 bg-purple-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-3"></span>
                                    Functionele Cookies
                                </h3>
                                <p class="text-purple-700 mb-3">Onthouden uw voorkeuren en instellingen voor een betere gebruikerservaring.</p>
                                <ul class="text-sm text-purple-700 space-y-1">
                                    <li>• Thema voorkeuren (donker/licht)</li>
                                    <li>• Lettergrootte instellingen</li>
                                    <li>• Toegankelijkheidsopties</li>
                                    <li>• Favoriete partijen/thema's</li>
                                </ul>
                                <p class="text-xs text-purple-600 mt-2"><strong>Bewaartermijn:</strong> 1 jaar</p>
                            </div>

                            <div class="border border-orange-200 bg-orange-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-800 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-3"></span>
                                    Marketing Cookies
                                </h3>
                                <p class="text-orange-700 mb-3">Gebruikt voor gerichte advertenties en het meten van campagne-effectiviteit.</p>
                                <ul class="text-sm text-orange-700 space-y-1">
                                    <li>• Google Ads tracking</li>
                                    <li>• Social media integraties</li>
                                    <li>• Retargeting pixels</li>
                                    <li>• Conversie tracking</li>
                                </ul>
                                <p class="text-xs text-orange-600 mt-2"><strong>Bewaartermijn:</strong> 90 dagen</p>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                            <h3 class="text-lg font-semibold text-blue-800 mb-3">Cookie Beheer</h3>
                            <p class="text-blue-700 mb-4">U kunt uw cookie voorkeuren op elk moment wijzigen:</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#browser-settings" class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                    Browser Instellingen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rechten sectie -->
                <div id="rights" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        5. Uw Rechten onder de AVG
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Onder de Algemene Verordening Gegevensbescherming (AVG) heeft u verschillende rechten betreffende uw persoonlijke gegevens. Wij respecteren deze rechten en maken het u gemakkelijk om ze uit te oefenen.
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Inzage
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U heeft het recht om te weten welke persoonlijke gegevens wij van u hebben en hoe wij deze gebruiken.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Stuur een verzoek naar privacy@politiekpraat.nl</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Rectificatie
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U kunt onjuiste of onvolledige persoonlijke gegevens laten corrigeren of aanvullen.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Via uw account instellingen of contact opnemen</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3l1.5 1.5a1 1 0 01-1.414 1.414L10 10.414V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Vergetelheid
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U kunt verzoeken om uw persoonlijke gegevens te laten verwijderen onder bepaalde omstandigheden.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Account verwijderen of schriftelijk verzoek indienen</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Dataportabiliteit
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U kunt uw gegevens in een gestructureerd, machineleesbaar formaat ontvangen.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Data export aanvragen via privacy@politiekpraat.nl</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Beperking
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U kunt de verwerking van uw gegevens laten beperken in bepaalde situaties.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Schriftelijk verzoek met onderbouwing</p>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Recht op Bezwaar
                                </h3>
                                <p class="text-gray-700 text-sm mb-3">U kunt bezwaar maken tegen de verwerking van uw gegevens voor directe marketing.</p>
                                <p class="text-xs text-gray-500"><strong>Hoe:</strong> Uitschrijven via nieuwsbrief of contact opnemen</p>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mt-8">
                            <h3 class="text-lg font-semibold text-green-800 mb-3">Verzoeken Indienen</h3>
                            <p class="text-green-700 mb-4">Om uw rechten uit te oefenen, kunt u contact met ons opnemen via:</p>
                            <div class="space-y-2 text-green-700">
                                <p><strong>E-mail:</strong> <a href="mailto:privacy@politiekpraat.nl" class="underline">privacy@politiekpraat.nl</a></p>
                                <p><strong>Onderwerp:</strong> "AVG Verzoek - [Type verzoek]"</p>
                                <p><strong>Responstijd:</strong> Wij reageren binnen 30 dagen op uw verzoek</p>
                            </div>
                            <div class="mt-4 p-4 bg-green-100 rounded border border-green-300">
                                <p class="text-green-800 text-sm">
                                    <strong>Verificatie:</strong> Voor uw veiligheid kunnen wij om aanvullende identificatie vragen voordat wij uw verzoek behandelen.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Beveiliging sectie -->
                <div id="security" class="bg-white rounded-2xl shadow-lg p-8 mb-8" tabindex="-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        6. Beveiliging van Uw Gegevens
                    </h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Wij nemen de beveiliging van uw persoonlijke gegevens zeer serieus en hebben passende technische en organisatorische maatregelen getroffen om uw gegevens te beschermen tegen ongeoorloofde toegang, verlies, diefstal of misbruik.
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Technische Beveiliging
                                </h3>
                                <ul class="text-blue-700 space-y-2 text-sm">
                                    <li>• SSL/TLS encryptie voor alle datatransmissie</li>
                                    <li>• Wachtwoorden worden versleuteld opgeslagen</li>
                                    <li>• Regelmatige beveiligingsupdates</li>
                                    <li>• Firewall en intrusion detection</li>
                                    <li>• Secure hosting infrastructure</li>
                                    <li>• Database encryptie</li>
                                </ul>
                            </div>

                            <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Organisatorische Maatregelen
                                </h3>
                                <ul class="text-green-700 space-y-2 text-sm">
                                    <li>• Toegangscontrole op basis van noodzaak</li>
                                    <li>• Regelmatige training van medewerkers</li>
                                    <li>• Privacy by design principes</li>
                                    <li>• Incident response procedures</li>
                                    <li>• Regelmatige beveiligingsaudits</li>
                                    <li>• Data minimalisatie strategie</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Data Breach Procedure
                            </h3>
                            <p class="text-yellow-800 text-sm mb-3">
                                In het onwaarschijnlijke geval van een datalek volgen wij een strikte procedure:
                            </p>
                            <ol class="text-yellow-700 text-sm space-y-1 list-decimal list-inside">
                                <li>Onmiddellijke containment van het incident</li>
                                <li>Beoordeling van de omvang en impact</li>
                                <li>Melding bij de toezichthouder binnen 72 uur</li>
                                <li>Directe communicatie naar betrokken gebruikers</li>
                                <li>Implementatie van aanvullende beveiligingsmaatregelen</li>
                            </ol>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-blue-800 mb-3">Uw Rol in Beveiliging</h3>
                            <p class="text-blue-700 text-sm mb-3">
                                U kunt ook bijdragen aan de beveiliging van uw gegevens:
                            </p>
                            <ul class="text-blue-700 text-sm space-y-1 list-disc list-inside">
                                <li>Gebruik een sterk, uniek wachtwoord</li>
                                <li>Log uit na gebruik op gedeelde computers</li>
                                <li>Meld verdachte activiteiten direct</li>
                                <li>Houd uw contactgegevens up-to-date</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bewaartermijnen en Internationale overdracht -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            7. Bewaartermijnen
                        </h2>
                        <div class="space-y-4 text-sm">
                            <div class="border-l-4 border-green-500 pl-4">
                                <p class="font-semibold text-gray-900">Account Gegevens</p>
                                <p class="text-gray-600">Tot account verwijdering</p>
                            </div>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <p class="font-semibold text-gray-900">Analytics Data</p>
                                <p class="text-gray-600">26 maanden (Google Analytics)</p>
                            </div>
                            <div class="border-l-4 border-purple-500 pl-4">
                                <p class="font-semibold text-gray-900">Stemwijzer Resultaten</p>
                                <p class="text-gray-600">5 jaar (geanonimiseerd)</p>
                            </div>
                            <div class="border-l-4 border-orange-500 pl-4">
                                <p class="font-semibold text-gray-900">Log Files</p>
                                <p class="text-gray-600">12 maanden</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                            </svg>
                            8. Internationale Overdracht
                        </h2>
                        <div class="text-sm text-gray-700">
                            <p class="mb-4">
                                Uw gegevens worden binnen de EU/EER verwerkt. Voor bepaalde diensten kunnen gegevens naar derde landen worden overgedragen:
                            </p>
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded p-3">
                                    <p class="font-semibold">Google Analytics (VS)</p>
                                    <p class="text-xs text-gray-600">Adequaatheidsbesluit + Standard Contractual Clauses</p>
                                </div>
                                <div class="bg-gray-50 rounded p-3">
                                    <p class="font-semibold">Hosting (Nederland)</p>
                                    <p class="text-xs text-gray-600">Binnen EU - geen overdracht</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Klachten en Contact -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            9. Klachten
                        </h2>
                        <div class="text-sm text-gray-700">
                            <p class="mb-4">
                                Heeft u een klacht over hoe wij met uw persoonlijke gegevens omgaan? U kunt:
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="text-primary font-bold mr-2">1.</span>
                                    <p>Contact opnemen via <a href="mailto:privacy@politiekpraat.nl" class="text-primary underline">privacy@politiekpraat.nl</a></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-primary font-bold mr-2">2.</span>
                                    <p>Een klacht indienen bij de <a href="https://autoriteitpersoonsgegevens.nl" class="text-primary underline" target="_blank">Autoriteit Persoonsgegevens</a></p>
                                </div>
                            </div>
                            <div class="bg-blue-50 rounded p-4 mt-4">
                                <p class="text-blue-800 text-xs">
                                    <strong>AP Contact:</strong><br>
                                    Autoriteit Persoonsgegevens<br>
                                    Postbus 93374<br>
                                    2509 AJ Den Haag<br>
                                    <a href="https://autoriteitpersoonsgegevens.nl" class="underline">autoriteitpersoonsgegevens.nl</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            10. Wijzigingen in deze Policy
                        </h2>
                        <div class="text-sm text-gray-700">
                            <p class="mb-4">
                                Wij kunnen deze Privacy Policy van tijd tot tijd bijwerken om wijzigingen in onze praktijken of om andere operationele, juridische of regelgevingseisen weer te geven.
                            </p>
                            <div class="space-y-3">
                                <div class="bg-green-50 rounded p-3">
                                    <p class="font-semibold text-green-800">Kleine wijzigingen</p>
                                    <p class="text-green-700 text-xs">Worden gemarkeerd met een nieuwe effectieve datum</p>
                                </div>
                                <div class="bg-orange-50 rounded p-3">
                                    <p class="font-semibold text-orange-800">Belangrijke wijzigingen</p>
                                    <p class="text-orange-700 text-xs">U ontvangt een e-mail notificatie en/of website melding</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-xs mt-4">
                                <strong>Advies:</strong> Controleer deze pagina regelmatig voor updates.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact sectie -->
                <div class="bg-gradient-to-br from-primary via-primary-dark to-secondary rounded-2xl text-white p-8 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-white">Vragen over Privacy?</h2>
                    <p class="text-gray-100 mb-6 max-w-2xl mx-auto">
                        Wij staan klaar om al uw privacy-gerelateerde vragen te beantwoorden. 
                        Neem gerust contact met ons op voor meer informatie.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="mailto:privacy@politiekpraat.nl" 
                           class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            E-mail Sturen
                        </a>
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
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 