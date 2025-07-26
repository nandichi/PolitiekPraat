<?php 
 // Set the base path 
 $basePath = dirname(__DIR__); 

 // Include necessary files 
 require_once $basePath . '/includes/config.php'; 
 require_once $basePath . '/includes/Database.php'; 

 // Connect to the database 
 $db = new Database(); 

 // Partijen data 
 $parties = [ 
     ['name' => 'Partij voor de Vrijheid', 'short_name' => 'PVV', 'logo_url' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg'], 
     ['name' => 'Volkspartij voor Vrijheid en Democratie', 'short_name' => 'VVD', 'logo_url' => 'https://logo.clearbit.com/vvd.nl'], 
     ['name' => 'Nieuw Sociaal Contract', 'short_name' => 'NSC', 'logo_url' => 'https://i.ibb.co/YT2fJZb4/nsc.png'], 
     ['name' => 'BoerBurgerBeweging', 'short_name' => 'BBB', 'logo_url' => 'https://i.ibb.co/qMjw7jDV/bbb.png'], 
     ['name' => 'GroenLinks-PvdA', 'short_name' => 'GL-PvdA', 'logo_url' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png'], 
     ['name' => 'Democraten 66', 'short_name' => 'D66', 'logo_url' => 'https://logo.clearbit.com/d66.nl'], 
     ['name' => 'Socialistische Partij', 'short_name' => 'SP', 'logo_url' => 'https://logo.clearbit.com/sp.nl'], 
     ['name' => 'Partij voor de Dieren', 'short_name' => 'PvdD', 'logo_url' => 'https://logo.clearbit.com/partijvoordedieren.nl'], 
     ['name' => 'Christen-Democratisch Appèl', 'short_name' => 'CDA', 'logo_url' => 'https://logo.clearbit.com/cda.nl'], 
     ['name' => 'JA21', 'short_name' => 'JA21', 'logo_url' => 'https://logo.clearbit.com/ja21.nl'], 
     ['name' => 'Staatkundig Gereformeerde Partij', 'short_name' => 'SGP', 'logo_url' => 'https://logo.clearbit.com/sgp.nl'], 
     ['name' => 'Forum voor Democratie', 'short_name' => 'FvD', 'logo_url' => 'https://logo.clearbit.com/fvd.nl'], 
     ['name' => 'DENK', 'short_name' => 'DENK', 'logo_url' => 'https://logo.clearbit.com/bewegingdenk.nl'], 
     ['name' => 'Volt Nederland', 'short_name' => 'Volt', 'logo_url' => 'https://logo.clearbit.com/voltnederland.org'] 
 ]; 

 // Vragen data (een paar voorbeelden) 
 $questions = [ 
     [ 
         'title' => 'Asielbeleid', 
         'description' => 'Nederland moet een strenger asielbeleid voeren met een asielstop en lagere immigratiecijfers.', 
         'context' => 'Bij deze stelling gaat het erom hoe Nederland omgaat met mensen die asiel aanvragen. Een strenger asielbeleid betekent dat er strengere regels komen en dat minder mensen worden toegelaten. Een asielstop betekent dat er tijdelijk helemaal geen nieuwe asielzoekers worden toegelaten. Dit onderwerp gaat over de balans tussen veiligheid, controle en humanitaire zorg.', 
         'left_view' => 'Vinden dat Nederland humaan moet blijven en vluchtelingen moet opvangen. Zij vinden dat mensen in nood geholpen moeten worden.', 
         'right_view' => 'Willen de instroom van asielzoekers beperken omdat zij vinden dat dit de druk op de samenleving verlaagt.', 
         'order_number' => 1, 
         'positions' => [ 
             'PVV' => ['position' => 'eens', 'explanation' => 'Deze partij steunt een strenger asielbeleid met een volledige asielstop. Zij vinden dat Nederland zo de controle over migratie behoudt.'], 
             'VVD' => ['position' => 'eens', 'explanation' => 'VVD pleit voor een strengere selectie en beperking van asielaanvragen, maar met internationale samenwerking.'], 
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC benadrukt dat een doordacht asielbeleid zowel veiligheid als humanitaire zorg moet waarborgen.'], 
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB ondersteunt een streng asielbeleid en wil de instroom beperken door regionale opvang te stimuleren.'], 
             'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA vindt dat humanitaire principes centraal moeten staan en verzet zich tegen een asielstop.'], 
             'D66' => ['position' => 'oneens', 'explanation' => 'D66 wil een humaan maar gestructureerd asielbeleid met veilige en legale routes.'], 
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP vindt dat het verbeteren van opvang en integratie even belangrijk is als het beperken van instroom.'], 
             'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD wil een asielbeleid dat mensenrechten respecteert en aandacht heeft voor de ecologische context.'], 
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA pleit voor een onderscheidend beleid met duidelijke scheiding tussen tijdelijke en permanente bescherming.'], 
             'JA21' => ['position' => 'eens', 'explanation' => 'JA21 ondersteunt een restrictief asielbeleid met strikte toelatingscriteria.'], 
             'SGP' => ['position' => 'eens', 'explanation' => 'SGP wil een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan.'], 
             'FvD' => ['position' => 'eens', 'explanation' => 'FvD pleit voor het beëindigen van het internationale asielkader en wil asielaanvragen sterk beperken.'], 
             'DENK' => ['position' => 'oneens', 'explanation' => 'DENK kiest voor een humaan asielbeleid dat ook aandacht heeft voor solidariteit en internationale samenwerking.'], 
             'Volt' => ['position' => 'oneens', 'explanation' => 'Volt staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert.'] 
         ] 
     ], 
     [ 
         'title' => 'Klimaatmaatregelen', 
         'description' => 'Nederland moet vooroplopen in de klimaattransitie, ook als dit op korte termijn economische groei kost.', 
         'context' => 'Deze stelling gaat over hoe snel Nederland moet overschakelen naar een klimaatvriendelijke economie. Het idee is dat we sneller moeten handelen om de opwarming van de aarde te stoppen. Dit kan betekenen dat bedrijven moeten investeren in nieuwe, duurzame technologieën en dat producten op korte termijn duurder worden. Het onderwerp gaat over de afweging tussen het beschermen van het milieu en de mogelijke economische nadelen op de korte termijn.', 
         'left_view' => 'Vinden dat Nederland snel actie moet ondernemen om de opwarming van de aarde tegen te gaan, ook als dit even wat kosten met zich meebrengt.', 
         'right_view' => 'Zien dat verduurzaming belangrijk is, maar vinden dat dit niet te snel mag gaan zodat bedrijven en burgers niet te veel last krijgen van de kosten.', 
         'order_number' => 2, 
         'positions' => [ 
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV verzet zich tegen ambitieuze klimaatmaatregelen als deze ten koste gaan van economische groei.'], 
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD ondersteunt klimaatmaatregelen, maar vindt dat de economie niet op de achtergrond mag raken.'], 
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC vindt zowel klimaat als economie belangrijk en pleit voor een evenwichtige aanpak.'], 
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is sceptisch over ingrijpende klimaatmaatregelen, zeker als deze de agrarische sector schaden.'], 
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA is voor ambitieuze klimaatmaatregelen, ook al moet daarvoor op korte termijn wat opgeofferd worden.'], 
             'D66' => ['position' => 'eens', 'explanation' => 'D66 wil dat Nederland een leidende rol speelt in de klimaattransitie, met oog voor veiligheid en innovatie.'], 
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP vindt dat klimaatmaatregelen eerlijk moeten worden verdeeld, zodat zowel ecologische als economische belangen worden meegenomen.'], 
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD staat voor radicaal klimaatbeleid, ongeacht economische kortetermijnnadelen.'], 
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA pleit voor een combinatie van klimaatmaatregelen en behoud van economische stabiliteit.'], 
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil niet dat klimaatmaatregelen de economische groei te veel hinderen.'], 
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP vindt dat maatregelen verantwoord moeten zijn en de economie niet te zwaar belasten.'], 
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD betwist de urgentie van de klimaatcrisis en wil geen maatregelen die de economie schaden.'], 
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een genuanceerde aanpak waarbij zowel klimaat als economie worden meegenomen.'], 
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor ambitieuze maatregelen en gelooft dat de lange termijn voordelen opwegen tegen de korte termijn kosten.'] 
         ] 
     ],
     [
         'title' => 'Woningmarkt',
         'description' => 'De overheid moet veel meer ingrijpen op de woningmarkt om betaalbare huizen voor iedereen te garanderen, bijvoorbeeld door huurplafonds en meer sociale woningbouw.',
         'context' => 'Deze stelling gaat over de rol van de overheid bij het oplossen van de krapte en de hoge prijzen op de woningmarkt. Ingrepen zoals huurplafonds beperken de maximale huurprijs van woningen, en meer sociale woningbouw betekent dat de overheid meer betaalbare huurwoningen bouwt voor mensen met een lager inkomen. Dit onderwerp raakt aan de betaalbaarheid van wonen en de verantwoordelijkheid van de overheid hierin.',
         'left_view' => 'Vinden dat de overheid een actieve rol moet spelen om gelijke kansen op de woningmarkt te creëren en de huurprijzen te reguleren.',
         'right_view' => 'Geloven dat de markt het beste zelf de prijzen reguleert en dat te veel overheidsingrijpen innovatie en aanbod kan belemmeren.',
         'order_number' => 3,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil betaalbare woningen, maar legt de nadruk op minder immigratie als oplossing voor de krapte.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD gelooft in marktwerking op de woningmarkt en is terughoudend met huurplafonds, maar stimuleert wel de bouw van nieuwe woningen.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil meer regie van de overheid op de woningmarkt, met aandacht voor betaalbaarheid en voldoende aanbod.'],
             'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB focust op het behoud van woningen in plattelandsgebieden en minder regelgeving, maar erkent de noodzaak van betaalbaarheid.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil ingrijpende overheidsmaatregelen om de woningcrisis aan te pakken, waaronder een nationaal woonfonds en fors meer sociale huurwoningen.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 stimuleert doorstroom op de woningmarkt en efficiëntie in bouwprocessen, met beperkte directe overheidsingrepen.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP pleit voor een radicale aanpak van de woningmarkt door nationalisatie van grond en grootschalige sociale woningbouw.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD verbindt de woningmarkt aan bredere vraagstukken van duurzaamheid en leefbaarheid, en pleit voor betaalbare, groene woningen.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een evenwicht tussen marktwerking en overheidsregie om betaalbaarheid te garanderen, met nadruk op doorstroming.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen te veel overheidsbemoeienis en wil marktwerking de ruimte geven om de woningmarkt te reguleren.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een stabiele woningmarkt en erkent de noodzaak van betaalbaarheid, maar is terughoudend met vergaande overheidsingrepen.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen overheidsingrijpen op de woningmarkt en ziet dit als belemmering van de vrije markt.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat de overheid stevig ingrijpt om betaalbare woningen te garanderen en discriminatie op de woningmarkt aan te pakken.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees woonbeleid en nationale regie om de woningmarkt toegankelijker te maken, met nadruk op duurzaamheid.']
         ]
     ],
     [
         'title' => 'Zorgsysteem',
         'description' => 'Het Nederlandse zorgsysteem moet verder gesocialiseerd worden, met een grotere rol voor de overheid en minder marktwerking.',
         'context' => 'Deze stelling gaat over de organisatie van de gezondheidszorg in Nederland. Socialiseren betekent dat de overheid meer zeggenschap en verantwoordelijkheid krijgt over de zorg, en dat commerciële belangen van verzekeraars en zorginstellingen minder leidend worden. Dit kan leiden tot een toegankelijker systeem voor iedereen, ongeacht inkomen, maar sommigen vrezen ook voor bureaucratie en minder keuzevrijheid.',
         'left_view' => 'Vinden dat gezondheidszorg een publieke voorziening is en dat de overheid de regie moet hebben om gelijke toegang voor iedereen te garanderen.',
         'right_view' => 'Geloven dat marktwerking in de zorg efficiëntie en innovatie stimuleert, en dat de invloed van de overheid beperkt moet blijven.',
         'order_number' => 4,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil de zorg betaalbaar houden voor iedereen, maar legt de nadruk op het terugdringen van de bureaucratie.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil de marktwerking in de zorg handhaven, met ruimte voor concurrentie en eigen verantwoordelijkheid.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil de solidariteit in de zorg versterken en zoekt een balans tussen overheidsregie en de rol van zorgprofessionals.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB wil minder bureaucratie in de zorg en meer zeggenschap bij de zorgverleners, met behoud van de huidige structuur.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de marktwerking uit de zorg halen en pleit voor een nationaal zorgfonds en een grotere rol voor de overheid.'],
             'D66' => ['position' => 'oneens', 'explanation' => 'D66 is voor een gezonde balans tussen marktwerking en publieke sturing om de kwaliteit en toegankelijkheid te waarborgen.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil de zorg volledig in publieke handen brengen, met afschaffing van het eigen risico en commerciële zorgverzekeraars.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD ziet de zorg als een fundamenteel recht en pleit voor een zorgsysteem dat aandacht heeft voor preventie en de connectie met de natuur.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een mix van solidariteit en eigen verantwoordelijkheid in de zorg, met aandacht voor de positie van de patiënt.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil minder overheidsbemoeienis in de zorg en meer eigen verantwoordelijkheid, met een focus op efficiëntie.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgsysteem dat solide en betaalbaar is, met aandacht voor ethische aspecten en de rol van familie.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen verdere socialisatie van de zorg en pleit voor meer vrijheid en eigen keuze in de zorg.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een toegankelijke en betaalbare zorg voor iedereen, waarbij de overheid meer regie neemt en discriminatie wordt tegengegaan.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een Europees gezondheidssysteem dat efficiënter en toegankelijker is, met een focus op grensoverschrijdende samenwerking.']
         ]
     ],
     [
         'title' => 'Arbeidsmarkt',
         'description' => 'De positie van flexwerkers en zzp\'ers moet fors verbeterd worden door strengere regulering, ook als dit de werkgevers lasten verhoogt.',
         'context' => 'Deze stelling gaat over de bescherming van werknemers met flexibele contracten en zelfstandigen zonder personeel (zzp\'ers). Strengere regulering kan betekenen dat werkgevers meer verantwoordelijkheid krijgen voor de zekerheid van flexwerkers, bijvoorbeeld door sneller vaste contracten aan te bieden of hogere premies te betalen. Dit kan de kosten voor werkgevers verhogen, maar biedt meer zekerheid voor de werknemer. Het onderwerp raakt aan de balans tussen flexibiliteit en zekerheid op de arbeidsmarkt.',
         'left_view' => 'Vinden dat de overheid de kwetsbare positie van flexwerkers en zzp\'ers moet beschermen door strenge regelgeving en betere sociale zekerheid.',
         'right_view' => 'Geloven dat te veel regulering de arbeidsmarkt minder flexibel maakt en werkgelegenheid kan schaden, en dat flexibiliteit ook voordelen heeft.',
         'order_number' => 5,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil de arbeidsmarkt inrichten op basis van Nederlandse belangen, met aandacht voor zekerheid voor Nederlandse werknemers.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil de flexibiliteit op de arbeidsmarkt behouden en is terughoudend met te veel regulering, maar erkent de noodzaak van een vangnet.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil de balans tussen flexibiliteit en zekerheid verbeteren, met meer aandacht voor de positie van flexwerkers en zzp’ers.'],
             'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil minder regeldruk voor werkgevers en boeren, maar erkent de noodzaak van zekerheid voor werkenden.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een vaste baan als norm en pleit voor het aanpakken van schijnzelfstandigheid en betere bescherming van zzp\'ers.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 zoekt naar een evenwicht tussen flexibiliteit en zekerheid, met oog voor de kansen die de flexibele arbeidsmarkt biedt.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil alle flexcontracten afschaffen en zzp\'ers dezelfde rechten en plichten geven als werknemers in loondienst.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een duurzame arbeidsmarkt waar iedereen een eerlijke positie heeft en aandacht is voor welzijn.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil de zekerheid voor werkenden vergroten, maar ook ruimte laten voor ondernemerschap en flexibiliteit.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen te veel regulering en wil de vrije keuze van zzp\'ers en flexwerkers niet beperken.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een arbeidsmarkt die stabiel en rechtvaardig is, met aandacht voor de gezinssituatie van werknemers.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen extra regulering van de arbeidsmarkt en wil de vrije keuze van arbeidsovereenkomsten behouden.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil de positie van flexwerkers en zzp\'ers verbeteren en discriminatie op de arbeidsmarkt tegengaan.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees sociaal beleid dat eerlijke arbeidsvoorwaarden voor iedereen garandeert, inclusief flexwerkers.']
         ]
     ],
     [
         'title' => 'Armoedebestrijding',
         'description' => 'De inkomensongelijkheid in Nederland moet actief worden verkleind door hogere belastingen voor de rijksten en een verhoging van het minimumloon en de uitkeringen.',
         'context' => 'Deze stelling gaat over hoe Nederland de kloof tussen arm en rijk moet aanpakken. Het verkleinen van inkomensongelijkheid kan betekenen dat mensen met hoge inkomens meer belasting betalen, en dat het minimumloon en uitkeringen stijgen. Dit kan leiden tot meer gelijke kansen en minder armoede, maar critici waarschuwen voor mogelijke effecten op de economie en werkgelegenheid. Het onderwerp raakt aan sociale rechtvaardigheid en de verdeling van welvaart.',
         'left_view' => 'Vinden dat de overheid een actieve rol moet spelen in het herverdelen van welvaart en het verkleinen van inkomensongelijkheid.',
         'right_view' => 'Geloven dat te veel herverdeling de economische groei kan belemmeren en dat mensen zelf verantwoordelijk zijn voor hun welvaart.',
         'order_number' => 6,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil de koopkracht van de gewone Nederlander verbeteren, maar legt de nadruk op het beteugelen van immigratie en lastenverlichting.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD gelooft in belonen van werken en is terughoudend met hogere belastingen en te hoge uitkeringen.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil de bestaanszekerheid vergroten en de armoede aanpakken door een sterke overheid en eerlijke verdeling.'],
             'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil de koopkracht van het platteland en de middenklasse beschermen, en is terughoudend met extra belastingen.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de kloof tussen arm en rijk verkleinen door hogere belastingen voor vermogen en bedrijven, en een verhoging van het minimumloon en uitkeringen.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 stimuleert doorstroom via onderwijs en werk, met aandacht voor een eerlijk belastingstelsel.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil een eerlijkere verdeling van welvaart door hogere belastingen voor multinationals en de allerrijksten, en een flinke verhoging van het minimumloon.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD ziet armoedebestrijding in samenhang met een duurzame en rechtvaardige samenleving, met aandacht voor basisinkomen en herverdeling.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil armoede bestrijden door werk te stimuleren en een vangnet te bieden, met een gebalanceerd belastingstelsel.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen herverdeling van welvaart en pleit voor lagere belastingen voor iedereen.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil armoede aanpakken via maatschappelijke initiatieven en een verantwoorde overheid, met behoud van eigen verantwoordelijkheid.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen herverdeling en wil lagere belastingen en minder overheidsbemoeienis.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil de armoede in Nederland bestrijden door een eerlijke verdeling van welvaart en gelijke kansen voor iedereen.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees sociaal beleid om armoede te bestrijden en inkomensongelijkheid te verminderen, met aandacht voor een basisinkomen.']
         ]
     ],
     [
         'title' => 'Onderwijs',
         'description' => 'Investeringen in het publieke onderwijs moeten drastisch omhoog, ook ten koste van belastingverlagingen of andere uitgaven.',
         'context' => 'Deze stelling gaat over de financiering van het Nederlandse onderwijs. Het idee is dat er meer geld moet gaan naar publieke scholen, lerarensalarissen, en lesmaterialen, zelfs als dat betekent dat er minder geld overblijft voor andere zaken of dat belastingen hoger blijven. Dit kan leiden tot beter onderwijs en gelijke kansen voor alle leerlingen, maar kan ook spanningen veroorzaken met andere begrotingsposten. Het onderwerp raakt aan de prioriteiten van de overheid en de toekomst van het onderwijs.',
         'left_view' => 'Vinden dat goed onderwijs een investering in de toekomst is en dat de overheid hier maximaal in moet investeren, ongeacht de kosten.',
         'right_view' => 'Geloven dat efficiëntie in het onderwijs belangrijker is dan extra uitgaven, en dat belastingverlagingen ook gunstig zijn voor de economie.',
         'order_number' => 7,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil kwalitatief goed onderwijs, maar legt de nadruk op het terugdringen van islamisering en lesuitval.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil investeren in kansengelijkheid in het onderwijs, maar met een efficiënte inzet van middelen.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil fors investeren in het onderwijs, met aandacht voor lerarentekorten en de kwaliteit van het onderwijs.'],
             'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil aandacht voor praktijkgericht onderwijs en minder regeldruk voor scholen, met een focus op de regio.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil grootschalig investeren in het publieke onderwijs, met hogere lerarensalarissen en kleinere klassen.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een ambitieuze onderwijsbegroting, met aandacht voor innovatie en gelijke kansen.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil alle commerciële invloeden uit het onderwijs bannen en massaal investeren in publiek onderwijs.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD wil dat onderwijs gericht is op brede ontwikkeling en duurzaamheid, met voldoende middelen voor kwaliteit.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil investeren in goed onderwijs, met aandacht voor de rol van ouders en de christelijke identiteit van scholen.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil minder bureaucratie in het onderwijs en is kritisch op extra uitgaven zonder duidelijke resultaten.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil investeren in degelijk onderwijs, met aandacht voor normen en waarden en de vrijheid van onderwijs.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is kritisch op de huidige onderwijsuitgaven en wil meer focus op klassieke kennis en minder overheidsbemoeienis.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil gelijke kansen in het onderwijs en extra investeringen om achterstanden te bestrijden en discriminatie tegen te gaan.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees onderwijsbeleid dat innovatie en gelijke toegang bevordert, met voldoende investeringen.']
         ]
     ],
     [
         'title' => 'Pensioenleeftijd',
         'description' => 'De pensioenleeftijd moet weer omlaag, zelfs als dat betekent dat de premies stijgen of uitkeringen lager worden.',
         'context' => 'Deze stelling gaat over de AOW-leeftijd en de leeftijd waarop mensen met pensioen kunnen gaan. Het idee is om de pensioenleeftijd te verlagen, wat betekent dat mensen eerder kunnen stoppen met werken. Dit kan leiden tot hogere premies die werkenden en werkgevers betalen, of lagere pensioenuitkeringen voor toekomstige gepensioneerden. Het onderwerp raakt aan de houdbaarheid van het pensioenstelsel en de wens van mensen om eerder met pensioen te gaan.',
         'left_view' => 'Vinden dat de pensioenleeftijd moet worden verlaagd om mensen eerder van hun welverdiende rust te laten genieten, en dat solidariteit hiervoor nodig is.',
         'right_view' => 'Geloven dat de pensioenleeftijd moet worden gekoppeld aan de levensverwachting om het pensioenstelsel betaalbaar te houden voor toekomstige generaties.',
         'order_number' => 8,
         'positions' => [
             'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil de pensioenleeftijd zo snel mogelijk verlagen naar 65 jaar.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil de pensioenleeftijd gekoppeld houden aan de levensverwachting om de betaalbaarheid van het pensioenstelsel te garanderen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een evenwichtig pensioenstelsel dat houdbaar is en mensen voldoende zekerheid biedt, met aandacht voor zware beroepen.'],
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil de pensioenleeftijd verlagen en extra aandacht voor mensen met zware beroepen.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de pensioenleeftijd bevriezen en op termijn verlagen, met speciale regelingen voor zware beroepen.'],
             'D66' => ['position' => 'oneens', 'explanation' => 'D66 is voor een flexibele pensioenleeftijd, waarbij mensen zelf kunnen kiezen wanneer ze stoppen met werken, met behoud van de betaalbaarheid.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil de pensioenleeftijd terug naar 65 jaar en een volksverzekering voor iedereen.'],
             'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD kijkt naar de duurzaamheid van het pensioenstelsel en de gezondheid van mensen, met aandacht voor een werkbaar leven.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een solide pensioenstelsel dat recht doet aan werkenden en gepensioneerden, met aandacht voor maatwerk.'],
             'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 wil de pensioenleeftijd koppelen aan de levensverwachting, maar is kritisch op te snelle verhogingen.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP wil een houdbaar pensioenstelsel en vindt dat de pensioenleeftijd gekoppeld moet blijven aan de levensverwachting.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen het verlagen van de pensioenleeftijd en wil de eigen verantwoordelijkheid van burgers benadrukken.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat mensen met zware beroepen eerder met pensioen kunnen en de pensioenleeftijd niet verder stijgt.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een Europees pensioensysteem dat toekomstbestendig en solidair is, met flexibele mogelijkheden.']
         ]
     ],
     [
         'title' => 'Openbaar Vervoer',
         'description' => 'Het openbaar vervoer moet gratis worden of de tarieven moeten drastisch omlaag, gefinancierd uit algemene middelen, zelfs als dat hogere belastingen betekent.',
         'context' => 'Deze stelling gaat over de financiering en toegankelijkheid van het openbaar vervoer. Het idee is om het OV gratis te maken of de kosten sterk te verlagen, zodat meer mensen er gebruik van maken. De kosten hiervan zouden dan niet meer door de reiziger betaald worden, maar uit de algemene belastingpot. Dit kan leiden tot minder files en CO2-uitstoot, maar kan ook hogere belastingen met zich meebrengen. Het onderwerp raakt aan duurzaamheid, mobiliteit en de rol van de overheid.',
         'left_view' => 'Vinden dat openbaar vervoer een basisrecht is en gratis of zeer betaalbaar moet zijn voor iedereen om de mobiliteit en duurzaamheid te bevorderen.',
         'right_view' => 'Geloven dat openbaar vervoer primair door de gebruiker moet worden betaald, en dat gratis OV een onnodige belastingdruk creëert.',
         'order_number' => 9,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil goede bereikbaarheid, maar is terughoudend met te hoge subsidies voor OV.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil een efficiënt OV-systeem dat betaalbaar is, maar waar gebruikers wel voor betalen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil investeren in goede infrastructuur, waaronder OV, met aandacht voor de bereikbaarheid van regio’s.'],
             'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil vooral investeren in bereikbaarheid op het platteland en minder regelgeving voor het OV.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil gratis openbaar vervoer voor jongeren en studenten, en fors lagere tarieven voor iedereen.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 wil een aantrekkelijk OV-netwerk en stimuleert het gebruik van OV als duurzaam alternatief.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil gratis openbaar vervoer voor iedereen en ziet dit als een publieke voorziening.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een duurzaam mobiliteitssysteem met gratis OV als speerpunt om autoafhankelijkheid te verminderen.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een goed en betaalbaar OV-netwerk, met aandacht voor de verbinding tussen stad en platteland.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen gratis OV en wil dat de gebruiker betaalt voor de dienst.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een efficiënt OV-systeem dat de behoeften van de bevolking dient, met aandacht voor kostenbeheersing.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen gratis OV en wil minder overheidsuitgaven.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil toegankelijk en betaalbaar openbaar vervoer, met aandacht voor de verbindingen in achterstandswijken.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees hogesnelheidsnetwerk en stimuleert gratis OV als duurzame mobiliteitsoplossing.']
         ]
     ],
     [
         'title' => 'Landbouwbeleid',
         'description' => 'De intensieve veehouderij moet drastisch worden ingekrompen ten gunste van natuur en milieu, ook als dit ten koste gaat van de exportpositie van Nederland.',
         'context' => 'Deze stelling gaat over de toekomst van de Nederlandse landbouw. Het idee is om de intensieve veehouderij (grote stallen met veel dieren) te verkleinen om de uitstoot van stikstof en andere milieuschadelijke stoffen te verminderen. Dit kan leiden tot een gezonder milieu en meer ruimte voor natuur, maar kan ook de export van landbouwproducten verminderen en boeren hard raken. Het onderwerp raakt aan duurzaamheid, economie en de toekomst van de agrarische sector.',
         'left_view' => 'Vinden dat natuur en milieu voorrang moeten krijgen boven economische belangen in de landbouw, en dat een overgang naar duurzame landbouw noodzakelijk is.',
         'right_view' => 'Geloven dat de exportpositie van Nederland belangrijk is en dat boeren de ruimte moeten krijgen om te ondernemen, met aandacht voor innovatie en minder strenge regels.',
         'order_number' => 10,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil de Nederlandse boeren beschermen en is tegen het inkrimpen van de veestapel.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een toekomstbestendige landbouwsector, met aandacht voor innovatie en duurzaamheid, maar zonder drastische inkrimping.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een gezonde landbouwsector die duurzaam produceert en in balans is met de natuur.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is fel tegen het inkrimpen van de veestapel en wil de boeren de ruimte geven om te boeren.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een halvering van de veestapel en een transitie naar kringlooplandbouw.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 wil een duurzame landbouwsector die in balans is met de natuur en pleit voor een transitie weg van de intensieve veehouderij.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil een eerlijke prijs voor boeren en een duurzame landbouw zonder intensieve veehouderij.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD wil een radicale transitie naar een plantaardige en natuurinclusieve landbouw, met een forse inkrimping van de veestapel.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een vitale landbouwsector die duurzaam produceert en in balans is met de omgeving.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen het inkrimpen van de veestapel en wil minder stikstofregels.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP wil een sterke agrarische sector die bijdraagt aan de voedselzekerheid en de economie.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen het inkrimpen van de veestapel en ontkent de ernst van de stikstofcrisis.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een gezonde landbouwsector die duurzaam en maatschappelijk verantwoord produceert.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees landbouwbeleid dat de transitie naar duurzame, circulaire landbouw stimuleert.']
         ]
     ],
     [
         'title' => 'Drugsbeleid',
         'description' => 'Harddrugs moeten gelegaliseerd worden, waarbij de overheid de productie en distributie controleert, om criminaliteit te verminderen en de volksgezondheid te verbeteren.',
         'context' => 'Deze stelling gaat over de aanpak van harddrugs in Nederland. Legalisering betekent dat harddrugs, zoals XTC of cocaïne, legaal worden en dat de overheid de productie en verkoop ervan reguleert, net zoals bij alcohol en tabak. Dit kan de criminele onderwereld aanpakken en de kwaliteit van drugs controleren, maar critici vrezen voor een toename van drugsgebruik en gezondheidsproblemen. Het onderwerp raakt aan veiligheid, volksgezondheid en ethiek.',
         'left_view' => 'Vinden dat legalisering van harddrugs de criminaliteit zal verminderen en de volksgezondheid zal verbeteren door regulering en voorlichting.',
         'right_view' => 'Geloven dat legalisering van harddrugs leidt tot meer drugsgebruik en grotere maatschappelijke problemen, en dat handhaving de beste aanpak is.',
         'order_number' => 11,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is tegen legalisering van harddrugs en pleit voor een strengere aanpak van drugscriminaliteit.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD is tegen legalisering van harddrugs en wil inzetten op handhaving en preventie.'],
             'NSC' => ['position' => 'oneens', 'explanation' => 'NSC is tegen legalisering van harddrugs en pleit voor een integrale aanpak van drugsgerelateerde criminaliteit.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is tegen legalisering van harddrugs en wil de focus leggen op handhaving en het bestrijden van ondermijning.'],
             'GL-PvdA' => ['position' => 'neutraal', 'explanation' => 'GL-PvdA staat open voor experimenten met regulering van soft- en harddrugs, met oog voor volksgezondheid en criminaliteitsbestrijding.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een regulering van drugs, inclusief harddrugs, om criminaliteit te bestrijden en de volksgezondheid te verbeteren.'],
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP is terughoudend met legalisering van harddrugs, maar wil de focus leggen op preventie en zorg voor verslaafden.'],
             'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD is voor een humaan drugsbeleid met aandacht voor volksgezondheid en preventie, en staat open voor wetenschappelijk onderzoek naar regulering.'],
             'CDA' => ['position' => 'oneens', 'explanation' => 'CDA is tegen legalisering van harddrugs en pleit voor een zero-tolerance beleid.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen legalisering van harddrugs en wil een strengere aanpak van drugscriminaliteit.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is principieel tegen legalisering van harddrugs en pleit voor een verbod op alle drugs.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen legalisering van harddrugs en pleit voor een harder optreden tegen drugscriminaliteit.'],
             'DENK' => ['position' => 'oneens', 'explanation' => 'DENK is tegen legalisering van harddrugs en pleit voor een strengere aanpak van drugscriminaliteit en preventie.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een Europees drugsbeleid dat gericht is op schadebeperking, preventie en een humane aanpak.']
         ]
     ],
     [
         'title' => 'Internationale Samenwerking',
         'description' => 'Nederland moet meer bijdragen aan internationale ontwikkelingssamenwerking, ook als dit ten koste gaat van nationale uitgaven.',
         'context' => 'Deze stelling gaat over de hoeveelheid geld die Nederland besteedt aan hulp aan ontwikkelingslanden. Het idee is om een groter deel van het nationale budget te besteden aan ontwikkelingshulp, zelfs als dat betekent dat er minder geld overblijft voor uitgaven binnen Nederland, zoals infrastructuur of gezondheidszorg. Dit kan bijdragen aan het oplossen van wereldwijde problemen zoals armoede en klimaatverandering, maar kan ook leiden tot discussies over de prioriteiten van de overheid. Het onderwerp raakt aan solidariteit, mondiale verantwoordelijkheid en nationale belangen.',
         'left_view' => 'Vinden dat Nederland een morele plicht heeft om bij te dragen aan een betere wereld en dat ontwikkelingssamenwerking cruciaal is voor mondiale stabiliteit.',
         'right_view' => 'Geloven dat nationale belangen voorop moeten staan en dat te veel geld naar ontwikkelingshulp een onnodige belasting vormt voor de Nederlandse schatkist.',
         'order_number' => 12,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil stoppen met ontwikkelingshulp en het geld in Nederland besteden.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil kritisch kijken naar ontwikkelingshulp en focussen op handel en private investeringen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een effectief ontwikkelingsbeleid dat bijdraagt aan stabiliteit en veiligheid in de wereld.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB wil dat de focus ligt op Nederlandse belangen en is kritisch op het verhogen van uitgaven voor ontwikkelingshulp.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de Nederlandse bijdrage aan ontwikkelingssamenwerking fors verhogen en focussen op klimaat en armoedebestrijding.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een ambitieus ontwikkelingsbeleid, met aandacht voor mensenrechten en duurzame ontwikkeling.'],
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP is kritisch op de effectiviteit van ontwikkelingshulp, maar wil wel bijdragen aan armoedebestrijding in de wereld.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een rechtvaardige wereld en wil de Nederlandse bijdrage aan ontwikkelingssamenwerking vergroten, met aandacht voor ecologische aspecten.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een effectief ontwikkelingsbeleid dat bijdraagt aan vrede, veiligheid en welvaart.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil minder geld naar ontwikkelingshulp en meer naar nationale prioriteiten.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een verantwoord ontwikkelingsbeleid dat bijdraagt aan evangelisatie en wederopbouw.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD wil stoppen met ontwikkelingshulp en focussen op Nederlandse belangen.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat Nederland zijn verantwoordelijkheid neemt in internationale samenwerking en bijdraagt aan een rechtvaardige wereld.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een versterking van de EU als geopolitieke speler en een toename van internationale samenwerking.']
         ]
     ],
     [
         'title' => 'Belasting op vermogen',
         'description' => 'De belasting op vermogen (zoals spaargeld, beleggingen en vastgoed) moet fors omhoog om de ongelijkheid te verminderen en meer publieke voorzieningen te kunnen financieren.',
         'context' => 'Deze stelling gaat over de belasting die mensen betalen over hun bezittingen, zoals spaargeld, aandelen en huizen die ze verhuren. Het idee is om deze belasting te verhogen, zodat de rijksten meer bijdragen aan de samenleving. Dit kan leiden tot een eerlijkere verdeling van welvaart en meer geld voor bijvoorbeeld zorg, onderwijs of infrastructuur, maar kan ook discussies oproepen over het effect op investeringen en ondernemerschap. Het onderwerp raakt aan de verdeling van welvaart, de financiering van de overheid en economische prikkels.',
         'left_view' => 'Vinden dat vermogen zwaarder belast moet worden om de ongelijkheid te verkleinen en de publieke sector te versterken.',
         'right_view' => 'Geloven dat hoge belastingen op vermogen investeringen en economische groei afremmen, en dat mensen gestimuleerd moeten worden om te sparen en te investeren.',
         'order_number' => 13,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil de lasten voor Nederlandse burgers en ondernemers verlagen, ook op vermogen.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD is tegen hogere belastingen op vermogen en wil de economie stimuleren door lagere lasten.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil een eerlijk belastingstelsel, waarbij vermogen eerlijker wordt belast om de lasten op arbeid te verlagen.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB wil minder belastingdruk voor ondernemers en boeren, en is tegen hogere belastingen op vermogen.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een forse verhoging van de belasting op grote vermogens, met name op vastgoed en aandelen.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil een evenwichtig belastingstelsel dat stimuleert om te investeren, maar met aandacht voor vermogensongelijkheid.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil dat grote vermogens veel zwaarder belast worden om armoede te bestrijden en publieke voorzieningen te financieren.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een duurzaam belastingstelsel dat vermogensongelijkheid aanpakt en ecologische doelen dient.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een belastingstelsel dat recht doet aan iedereen, met aandacht voor de middenklasse en ondernemers.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen hogere belastingen op vermogen en wil lagere lasten voor iedereen.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP wil een stabiel belastingstelsel en is terughoudend met extra belastingen op vermogen.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen hogere belastingen op vermogen en wil de staat afbouwen.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een eerlijker belastingstelsel waarbij de sterkste schouders de zwaarste lasten dragen.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees belastingstelsel dat vermogensongelijkheid aanpakt en de publieke sector financiert.']
         ]
     ],
     [
         'title' => 'EU-integratie',
         'description' => 'Nederland moet streven naar meer Europese integratie, inclusief meer bevoegdheden voor Brussel op het gebied van veiligheid, migratie en klimaat.',
         'context' => 'Deze stelling gaat over de mate waarin Nederland bevoegdheden wil overdragen aan de Europese Unie. Meer Europese integratie betekent dat de EU meer zeggenschap krijgt over nationale beleidsterreinen zoals defensie, asielzaken en klimaatmaatregelen. Dit kan leiden tot een sterkere en effectievere EU op wereldwijd niveau, maar critici vrezen voor verlies van nationale soevereiniteit en democratische controle. Het onderwerp raakt aan soevereiniteit, internationale samenwerking en de toekomst van Europa.',
         'left_view' => 'Vinden dat een sterk en geïntegreerd Europa essentieel is om de grote uitdagingen van deze tijd aan te pakken, zoals klimaatverandering en veiligheid.',
         'right_view' => 'Geloven dat Nederland zijn nationale soevereiniteit moet behouden en dat te veel macht bij Brussel democratische problemen veroorzaakt.',
         'order_number' => 14,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil een Nexit en is fel tegen meer Europese integratie.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een sterk en efficiënt Europa, maar is kritisch op te veel bevoegdheden voor Brussel.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een krachtig Europa dat werkt aan vrede, veiligheid en welvaart, met behoud van nationale identiteit.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is kritisch op de huidige EU en wil dat de focus ligt op samenwerking waar het nodig is, maar zonder verdere integratie.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een sterker en meer solidair Europa, met meer bevoegdheden voor de EU op belangrijke beleidsterreinen.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 is een uitgesproken voorstander van verdere Europese integratie en een federaler Europa.'],
             'SP' => ['position' => 'oneens', 'explanation' => 'SP is kritisch op de EU en wil minder bevoegdheden voor Brussel en meer nationale zeggenschap.'],
             'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD ziet de EU als een platform voor duurzame samenwerking, maar is kritisch op de huidige koers en lobbyïnvloeden.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een sterk en effectief Europa, maar met respect voor nationale soevereiniteit en subsidiariteit.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil minder bevoegdheden voor de EU en meer nationale zeggenschap.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is kritisch op de EU en wil dat Nederland zijn nationale soevereiniteit behoudt.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen de EU en pleit voor een Nexit.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een effectieve samenwerking binnen de EU, met aandacht voor gelijke kansen en de bestrijding van discriminatie.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt is dé partij voor verdere Europese integratie en een federaal Europa.']
         ]
     ],
     [
         'title' => 'Kernenergie',
         'description' => 'Nederland moet fors investeren in kernenergie als duurzame en betrouwbare energiebron, ook als dit betekent dat er minder geld is voor andere duurzame alternatieven zoals wind- en zonne-energie.',
         'context' => 'Deze stelling gaat over de rol van kernenergie in de Nederlandse energietransitie. Het idee is om zwaar te investeren in kerncentrales, die een constante en CO2-vrije energieproductie leveren. Dit kan bijdragen aan energieonafhankelijkheid en klimaatdoelen, maar roept ook vragen op over veiligheid, afvalverwerking en de hoge opstartkosten. Het onderwerp raakt aan energiezekerheid, duurzaamheid en de kosten van de energietransitie.',
         'left_view' => 'Vinden dat kernenergie risico\'s met zich meebrengt en dat de voorkeur moet uitgaan naar veiligere en sneller inzetbare duurzame energiebronnen zoals wind- en zonne-energie.',
         'right_view' => 'Geloven dat kernenergie een cruciale rol speelt in een stabiele en CO2-vrije energievoorziening en dat de nadelen opwegen tegen de voordelen.',
         'order_number' => 15,
         'positions' => [
             'PVV' => ['position' => 'eens', 'explanation' => 'PVV is voor meer kernenergie en wil minder afhankelijk zijn van gas en windmolens.'],
             'VVD' => ['position' => 'eens', 'explanation' => 'VVD wil fors investeren in kernenergie als onderdeel van de duurzame energiemix.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC is voor de inzet van kernenergie als CO2-vrije energiebron voor de lange termijn.'],
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB is voor de bouw van nieuwe kerncentrales om de energieonafhankelijkheid te vergroten.'],
             'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA is tegen kernenergie vanwege de veiligheidsrisico\'s en het kernafval, en pleit voor vol inzetten op wind- en zonne-energie.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 is kritisch op kernenergie, maar sluit het niet uit als onderdeel van een brede energiemix, met focus op duurzaamheid.'],
             'SP' => ['position' => 'oneens', 'explanation' => 'SP is tegen kernenergie en pleit voor een duurzame energiemix met wind, zon en geothermie.'],
             'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD is fel tegen kernenergie vanwege de risico\'s voor mens en milieu en pleit voor 100% duurzame energie.'],
             'CDA' => ['position' => 'eens', 'explanation' => 'CDA is voor de inzet van kernenergie als een betrouwbare en CO2-vrije energiebron.'],
             'JA21' => ['position' => 'eens', 'explanation' => 'JA21 is voor de bouw van nieuwe kerncentrales en wil minder afhankelijk zijn van wind en zon.'],
             'SGP' => ['position' => 'eens', 'explanation' => 'SGP is voor de inzet van kernenergie als onderdeel van een stabiele energievoorziening.'],
             'FvD' => ['position' => 'eens', 'explanation' => 'FvD is voor de bouw van nieuwe kerncentrales en is tegen windmolens en zonnepanelen.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een duurzame energiemix en staat open voor kernenergie als die veilig en betaalbaar is.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een Europees energiebeleid en staat open voor een mix van duurzame energiebronnen, inclusief kernenergie.']
         ]
     ],
     [
         'title' => 'Noodtoestand klimaat',
         'description' => 'De overheid moet de klimaatcrisis uitroepen tot noodtoestand, met vergaande maatregelen die ook op korte termijn pijn doen, zoals het drastisch beperken van vliegverkeer en vleesconsumptie.',
         'context' => 'Deze stelling gaat over de urgentie van de klimaatcrisis en de noodzaak van radicale maatregelen. Het uitroepen van een noodtoestand zou de overheid de mogelijkheid geven om snel en ingrijpend beleid door te voeren, zoals het beperken van vliegreizen en het verminderen van vleesconsumptie. Dit kan leiden tot een snellere vermindering van CO2-uitstoot, maar kan ook als een te grote inbreuk op persoonlijke vrijheden worden gezien. Het onderwerp raakt aan klimaaturgentie, overheidsingrijpen en individuele vrijheid.',
         'left_view' => 'Vinden dat de klimaatcrisis zo urgent is dat vergaande maatregelen noodzakelijk zijn, zelfs als dit betekent dat het de levensstijl van mensen beïnvloedt.',
         'right_view' => 'Geloven dat te vergaande maatregelen de economie schaden en de persoonlijke vrijheid beperken, en dat klimaatbeleid geleidelijk en economisch verantwoord moet zijn.',
         'order_number' => 16,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV ontkent de urgentie van de klimaatcrisis en verzet zich tegen maatregelen die de vrijheid beperken.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD gelooft in een geleidelijke transitie met aandacht voor economische groei, en is tegen te vergaande overheidsingrijpen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een effectief klimaatbeleid, maar met aandacht voor draagvlak en proportionaliteit.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is tegen verregaande klimaatmaatregelen en vindt dat de focus moet liggen op het behoud van de agrarische sector.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de klimaatcrisis uitroepen tot nationale noodtoestand en met spoed vergaande maatregelen nemen, waaronder het fors belasten van vliegverkeer en vlees.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 wil een ambitieus klimaatbeleid en is bereid tot vergaande maatregelen, met oog voor innovatie en kansen.'],
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil een rechtvaardige klimaattransitie, waarbij de vervuiler betaalt en de lasten niet bij de gewone burger komen.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor het uitroepen van een ecologische noodtoestand en radicaal beleid om de planeet te redden.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een evenwichtig klimaatbeleid dat rekening houdt met alle belangen.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen klimaatmaatregelen die de economie schaden en de persoonlijke vrijheid beperken.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is kritisch op het uitroepen van een noodtoestand en wil een verantwoord klimaatbeleid.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD ontkent de klimaatcrisis en verzet zich tegen alle klimaatmaatregelen.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een verantwoord klimaatbeleid dat rekening houdt met de sociale impact en de economie.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een ambitieus Europees klimaatbeleid en de uitroeping van een klimaatnoodtoestand.']
         ]
     ],
     [
         'title' => 'Privatisering',
         'description' => 'De overheid moet geprivatiseerde publieke diensten, zoals energiebedrijven en delen van het openbaar vervoer, terugbrengen in overheidshanden.',
         'context' => 'Deze stelling gaat over de eigendom van publieke diensten. Privatisering betekent dat diensten die voorheen door de overheid werden gerund, zoals energievoorziening of delen van het spoor, in handen zijn gekomen van commerciële bedrijven. Het terugbrengen in overheidshanden (nationalisatie) betekent dat de staat weer eigenaar wordt. Dit kan leiden tot meer controle over de kwaliteit en betaalbaarheid van diensten, maar critici waarschuwen voor inefficiëntie en gebrek aan innovatie. Het onderwerp raakt aan de rol van de overheid, markteconomie en publiek belang.',
         'left_view' => 'Vinden dat essentiële publieke diensten in publieke handen moeten zijn om winstbejag te voorkomen en de kwaliteit en toegankelijkheid te garanderen.',
         'right_view' => 'Geloven dat privatisering zorgt voor efficiëntie, innovatie en betere dienstverlening door concurrentie.',
         'order_number' => 17,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil dat publieke diensten in het belang van de Nederlandse burger werken, maar is kritisch op nationalisatie.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD is voorstander van marktwerking en privatisering waar mogelijk, om efficiëntie te bevorderen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een effectieve overheid en een sterke publieke sector, met aandacht voor de regulering van geprivatiseerde diensten.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB wil minder overheidsbemoeienis en meer ruimte voor private initiatieven.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil essentiële publieke diensten, zoals delen van het openbaar vervoer en energiebedrijven, terug in overheidshanden.'],
             'D66' => ['position' => 'oneens', 'explanation' => 'D66 is voorstander van marktwerking waar mogelijk en regulering waar nodig, maar is tegen grootschalige nationalisatie.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil alle geprivatiseerde publieke diensten terug in overheidshanden en afschaffing van winst in de zorg.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een duurzame publieke sector en wil essentiële diensten in publieke handen, met aandacht voor ecologische aspecten.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een evenwicht tussen publieke en private initiatieven, met aandacht voor publiek belang.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen nationalisatie en wil minder overheidsbemoeienis.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een efficiënte overheid en is terughoudend met zowel privatisering als nationalisatie.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen nationalisatie en pleit voor een zo klein mogelijke overheid.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat essentiële publieke diensten voor iedereen toegankelijk en betaalbaar zijn, en pleit voor nationalisatie waar nodig.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een efficiënte publieke sector en een mix van publieke en private oplossingen op Europees niveau.']
         ]
     ],
     [
         'title' => 'Immigratie en Integratie',
         'description' => 'De overheid moet actief beleid voeren om de integratie van immigranten te bevorderen, door middel van taallessen, scholing en begeleiding naar werk, zelfs als dit extra investeringen vereist.',
         'context' => 'Deze stelling gaat over de aanpak van de integratie van nieuwkomers in Nederland. Actief beleid betekent dat de overheid proactief investeert in programma\'s voor taallessen, onderwijs en hulp bij het vinden van werk voor immigranten. Dit kan leiden tot een snellere en succesvollere integratie, maar kan ook als een te grote financiële last worden gezien of als een taak die primair bij de nieuwkomers zelf ligt. Het onderwerp raakt aan sociale cohesie, economie en de rol van de overheid.',
         'left_view' => 'Vinden dat investeren in integratie cruciaal is voor een inclusieve samenleving en om iedereen gelijke kansen te bieden.',
         'right_view' => 'Geloven dat integratie primair een eigen verantwoordelijkheid is en dat te veel overheidsingrijpen leidt tot afhankelijkheid.',
         'order_number' => 18,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is tegen actieve integratie en wil de immigratie stoppen.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil inburgering stimuleren, maar met de nadruk op eigen verantwoordelijkheid.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een effectief integratiebeleid, met aandacht voor taal, werk en wederkerigheid.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is kritisch op de effectiviteit van integratiebeleid en wil minder immigratie.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil fors investeren in integratiebeleid, met aandacht voor taallessen, scholing en begeleiding naar werk.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een humaan en effectief integratiebeleid, met aandacht voor kansengelijkheid.'],
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil dat integratie gericht is op deelname aan de samenleving, met aandacht voor de Nederlandse taal.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een inclusieve samenleving en wil investeren in integratie om gelijke kansen te bevorderen.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een evenwichtig integratiebeleid, met aandacht voor wederzijdse aanpassing.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen actieve integratie en wil dat immigranten zich aanpassen aan de Nederlandse cultuur.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil dat immigranten de Nederlandse taal en cultuur omarmen, met aandacht voor christelijke waarden.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen immigratie en integratiebeleid en wil een rem op alle immigratie.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil investeren in inclusief integratiebeleid dat discriminatie tegengaat en gelijke kansen creëert.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees integratiebeleid dat solidariteit en kansengelijkheid bevordert.']
         ]
     ],
     [
         'title' => 'Defensie',
         'description' => 'Nederland moet de defensie-uitgaven drastisch verhogen, ook als dit betekent dat er minder geld is voor sociale voorzieningen of andere publieke uitgaven.',
         'context' => 'Deze stelling gaat over de hoogte van het Nederlandse defensiebudget. Drastische verhoging betekent dat er veel meer geld wordt geïnvesteerd in het leger, wapens, en materieel, zelfs als dat ten koste gaat van andere overheidsuitgaven zoals zorg, onderwijs of sociale zekerheid. Dit kan leiden tot een sterkere defensie en een grotere rol op het wereldtoneel, maar kan ook discussies oproepen over de prioriteiten van de overheid en de verdeling van middelen. Het onderwerp raakt aan veiligheid, internationale verantwoordelijkheid en nationale begrotingskeuzes.',
         'left_view' => 'Vinden dat investeren in sociale voorzieningen en het welzijn van burgers belangrijker is dan grootschalige defensie-uitgaven, en dat diplomatie en conflictpreventie de voorkeur hebben.',
         'right_view' => 'Geloven dat een sterke defensie essentieel is voor nationale veiligheid en internationale stabiliteit, en dat hierin fors geïnvesteerd moet worden.',
         'order_number' => 19,
         'positions' => [
             'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil fors investeren in de Nederlandse defensie en het leger versterken.'],
             'VVD' => ['position' => 'eens', 'explanation' => 'VVD wil de defensie-uitgaven verhogen naar de NAVO-norm en investeren in een slagvaardig leger.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil een krachtige defensie die de veiligheid van Nederland kan garanderen en een rol kan spelen in internationale missies.'],
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil fors investeren in de defensie en de krijgsmacht versterken.'],
             'GL-PvdA' => ['position' => 'neutraal', 'explanation' => 'GL-PvdA wil een sterkere focus op diplomatie en conflictpreventie, maar erkent de noodzaak van een effectieve defensie.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil een sterke en efficiënte defensie, met aandacht voor internationale samenwerking en cybersecurity.'],
             'SP' => ['position' => 'oneens', 'explanation' => 'SP is kritisch op grote defensie-uitgaven en wil de focus leggen op vredesopbouw en diplomatie.'],
             'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD pleit voor een vreedzame wereld en wil de defensie-uitgaven fors verlagen ten gunste van duurzaamheid en sociale voorzieningen.'],
             'CDA' => ['position' => 'eens', 'explanation' => 'CDA wil investeren in een krachtige defensie die bijdraagt aan vrede en veiligheid in de wereld.'],
             'JA21' => ['position' => 'eens', 'explanation' => 'JA21 wil fors investeren in de Nederlandse defensie en het leger.'],
             'SGP' => ['position' => 'eens', 'explanation' => 'SGP wil een sterke defensie die de nationale veiligheid kan waarborgen.'],
             'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil fors investeren in defensie en de grenzen sluiten.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een effectieve defensie die de nationale veiligheid waarborgt, met aandacht voor diplomatie.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees leger en een sterkere Europese defensiecapaciteit.']
         ]
     ],
     [
         'title' => 'Participatie Democratie',
         'description' => 'Burgers moeten meer directe inspraak krijgen via referenda en burgerfora, zelfs als dit de besluitvorming complexer maakt.',
         'context' => 'Deze stelling gaat over de mate van directe invloed die burgers moeten hebben op politieke beslissingen. Referenda geven burgers de mogelijkheid om direct te stemmen over specifieke onderwerpen, en burgerfora laten een willekeurige groep burgers meedenken en adviseren over beleid. Dit kan de democratie versterken en de betrokkenheid van burgers vergroten, maar kan ook leiden tot langere en complexere besluitvorming, of besluiten die minder goed zijn afgewogen. Het onderwerp raakt aan democratische vernieuwing, representatie en de rol van de burger.',
         'left_view' => 'Vinden dat directe democratie essentieel is om de kloof tussen burger en politiek te dichten en de legitimiteit van besluiten te vergroten.',
         'right_view' => 'Geloven dat de representatieve democratie het beste werkt, en dat te veel directe inspraak leidt tot ongefundeerde besluiten en vertraging.',
         'order_number' => 20,
         'positions' => [
             'PVV' => ['position' => 'eens', 'explanation' => 'PVV is voorstander van bindende referenda en meer directe democratie.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD is terughoudend met bindende referenda en gelooft in de representatieve democratie.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil de democratie versterken en staat open voor burgerfora, maar behoudt de representatieve democratie als basis.'],
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB is voorstander van referenda en meer inspraak voor burgers, vooral in de regio.'],
             'GL-PvdA' => ['position' => 'neutraal', 'explanation' => 'GL-PvdA staat open voor burgerfora en meer participatie, maar met behoud van de parlementaire democratie.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 is voorstander van democratische vernieuwing, waaronder bindende referenda en burgerfora.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil meer directe democratie via referenda en volksinitiatieven.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor meer directe democratie en burgerparticipatie om de stem van de burger te versterken.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil de representatieve democratie versterken, met aandacht voor de betrokkenheid van burgers.'],
             'JA21' => ['position' => 'eens', 'explanation' => 'JA21 is voorstander van bindende referenda over belangrijke onderwerpen.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is tegen directe democratie via referenda en gelooft in de representatieve democratie.'],
             'FvD' => ['position' => 'eens', 'explanation' => 'FvD is voorstander van bindende referenda en directe democratie.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil dat de stem van de burger beter wordt gehoord, met aandacht voor inclusieve participatie.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor transnationale referenda en een Europees burgerinitiatief om de Europese democratie te versterken.']
         ]
     ],
     [
         'title' => 'Jeugdzorg',
         'description' => 'De jeugdzorg moet volledig onder landelijke regie komen, met meer financiering en minder regionale verschillen, ook als dit ten koste gaat van de autonomie van gemeenten.',
         'context' => 'Deze stelling gaat over de organisatie en financiering van de jeugdzorg in Nederland. Volledig onder landelijke regie betekent dat de centrale overheid de volledige verantwoordelijkheid en financiering van de jeugdzorg overneemt van gemeenten. Dit kan leiden tot minder bureaucratie, betere kwaliteit en gelijke toegang tot zorg voor alle kinderen, maar kan ook ten koste gaan van lokale maatwerkoplossingen en de autonomie van gemeenten. Het onderwerp raakt aan de kwaliteit van zorg, de rol van de overheid en de verantwoordelijkheid van gemeenten.',
         'left_view' => 'Vinden dat jeugdzorg een nationale verantwoordelijkheid is en dat gelijke kwaliteit en toegang voor alle kinderen gegarandeerd moeten worden door centrale sturing.',
         'right_view' => 'Geloven dat gemeenten het beste kunnen inspelen op lokale behoeften en dat te veel landelijke regie leidt tot bureaucratie en minder maatwerk.',
         'order_number' => 21,
         'positions' => [
             'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil een efficiënte jeugdzorg, maar is kritisch op het overhevelen van taken.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil verbeteringen in de jeugdzorg, maar behoudt de verantwoordelijkheid zoveel mogelijk bij gemeenten.'],
             'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil de jeugdzorg sterk verbeteren en staat open voor meer landelijke regie als dat nodig is voor kwaliteit en toegankelijkheid.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB wil meer ruimte voor gemeenten en minder landelijke regels in de jeugdzorg.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de jeugdzorg weer volledig onder landelijke regie brengen, met meer financiering en een einde aan de marktwerking.'],
             'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil dat de jeugdzorg effectief is en staat open voor verbeteringen in de organisatie, met behoud van lokale aanpak.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil de jeugdzorg terug in publieke handen en onder landelijke regie, met afschaffing van commerciële aanbieders.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een jeugdzorg die de ontwikkeling van kinderen centraal stelt, met voldoende middelen en landelijke coördinatie.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een jeugdzorg die dichtbij gezinnen staat, met een goede samenwerking tussen gemeenten en de landelijke overheid.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil minder bemoeienis van de landelijke overheid in de jeugdzorg en meer verantwoordelijkheid bij gezinnen.'],
             'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een solide jeugdzorg die het gezin ondersteunt, met aandacht voor lokale verantwoordelijkheid.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD wil minder overheidsbemoeienis in de jeugdzorg en meer eigen verantwoordelijkheid.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat de jeugdzorg toegankelijk en kwalitatief goed is voor alle kinderen, met landelijke regie om ongelijkheid tegen te gaan.'],
             'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een Europees kader voor jeugdzorg en een effectieve samenwerking om de beste zorg voor kinderen te garanderen.']
         ]
     ],
     [
         'title' => 'Cultuur en Kunst',
         'description' => 'De overheid moet fors meer investeren in kunst en cultuur, ook als dit betekent dat de belastingen omhoog moeten of andere uitgaven worden gekort.',
         'context' => 'Deze stelling gaat over de financiering van de culturele sector in Nederland. Fors meer investeren betekent dat de overheid een aanzienlijk groter deel van het budget besteedt aan kunstinstellingen, musea, theaters, en individuele kunstenaars. Dit kan leiden tot een rijkere cultuur, meer creativiteit en een bredere toegang tot kunst, maar kan ook leiden tot discussies over de prioriteiten van de overheid en de verdeling van middelen. Het onderwerp raakt aan de waarde van cultuur, de rol van de overheid en de financiering van de publieke sector.',
         'left_view' => 'Vinden dat kunst en cultuur essentieel zijn voor een bloeiende samenleving en dat de overheid hierin volop moet investeren.',
         'right_view' => 'Geloven dat kunst en cultuur primair door de markt en private initiatieven moeten worden gefinancierd, en dat overheidssteun beperkt moet blijven.',
         'order_number' => 22,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil bezuinigen op subsidies voor kunst en cultuur en meer focus op Nederlandse identiteit.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een bloeiende culturele sector, maar stimuleert ook private financiering en eigen verdienvermogen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een vitale culturele sector en een evenwicht tussen publieke en private financiering.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is kritisch op te hoge subsidies voor kunst en cultuur en wil meer focus op regionale cultuur.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil fors investeren in kunst en cultuur en het culturele erfgoed beschermen, met aandacht voor eerlijke beloning van kunstenaars.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een ambitieuze cultuurbegroting en ziet cultuur als een motor voor innovatie en verbinding.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil dat kunst en cultuur voor iedereen toegankelijk zijn en pleit voor een forse verhoging van de subsidies.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD ziet cultuur als een uiting van menselijke creativiteit en pleit voor investeringen die bijdragen aan een duurzame en inclusieve samenleving.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een levendige culturele sector, met aandacht voor traditie en de rol van maatschappelijke organisaties.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil bezuinigen op kunst en cultuur en is kritisch op de besteding van subsidies.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is kritisch op subsidies voor kunst en cultuur die niet aansluiten bij christelijke normen en waarden.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD wil bezuinigen op kunst en cultuur en is tegen "linkse" subsidies.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat kunst en cultuur diversiteit weerspiegelen en toegankelijk zijn voor iedereen, met voldoende financiering.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees cultuurbeleid dat diversiteit en grensoverschrijdende samenwerking stimuleert.']
         ]
     ],
     [
         'title' => 'Politiegeweld',
         'description' => 'Er moet een onafhankelijk onderzoeksorgaan komen dat alle gevallen van excessief politiegeweld onderzoekt en verantwoordelijken strenger straft.',
         'context' => 'Deze stelling gaat over het toezicht op en de verantwoording van het handelen van de politie. Een onafhankelijk onderzoeksorgaan zou de bevoegdheid krijgen om klachten over politiegeweld te onderzoeken, los van de politie zelf. Dit kan leiden tot meer transparantie, vertrouwen in de politie en een effectievere aanpak van misstanden, maar kan ook als een te grote inmenging in het werk van de politie worden gezien. Het onderwerp raakt aan rechtshandhaving, burgerrechten en overheidsverantwoording.',
         'left_view' => 'Vinden dat onafhankelijk toezicht essentieel is om de rechtstaat te waarborgen en excessief politiegeweld aan te pakken.',
         'right_view' => 'Geloven dat de politie voldoende interne mechanismen heeft voor controle en dat te veel extern toezicht hun werk bemoeilijkt.',
         'order_number' => 23,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV steunt de politie en is tegen extra controle op hun werk.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD vertrouwt op de interne mechanismen van de politie en is terughoudend met extra externe organen.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil de professionaliteit van de politie waarborgen en staat open voor effectieve toezichtsmogelijkheden.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB steunt de politie en wil meer ruimte voor hun werk, zonder extra controle.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een onafhankelijk onderzoeksorgaan voor politiegeweld en strengere straffen voor misstanden.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een onafhankelijk onderzoek bij klachten over politiegeweld om het vertrouwen in de politie te vergroten.'],
             'SP' => ['position' => 'eens', 'explanation' => 'SP wil een onafhankelijke klachtencommissie en strenger toezicht op de politie.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een rechtvaardige samenleving en wil onafhankelijk toezicht op overheidsgeweld, inclusief dat van de politie.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil dat de politie effectief kan optreden, met aandacht voor een zorgvuldige klachtenafhandeling.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 steunt de politie en is tegen extra controle die hun werk bemoeilijkt.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP vertrouwt op de integriteit van de politie en is terughoudend met extra externe organen.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD steunt de politie en is tegen de "ondermijning" van het gezag van de politie.'],
             'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een onafhankelijk onderzoek naar politiegeweld en structurele aanpak van discriminatie binnen de politie.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees kader voor de bescherming van mensenrechten en onafhankelijk toezicht op rechtshandhaving.']
         ]
     ],
     [
         'title' => 'Publieke Omroep',
         'description' => 'De publieke omroep (NPO) moet kritisch worden bekeken en gesaneerd, met minder budget en minder commerciële activiteiten, en meer focus op kerntaken.',
         'context' => 'Deze stelling gaat over de rol en omvang van de publieke omroep in Nederland. Sanering betekent dat de NPO minder geld krijgt en minder commerciële activiteiten mag ontplooien, met een focus op haar kerntaken zoals journalistiek, educatie en cultuur. Dit kan leiden tot een efficiëntere publieke omroep en minder concurrentie met commerciële media, maar kan ook ten koste gaan van de diversiteit en kwaliteit van het aanbod. Het onderwerp raakt aan mediavrijheid, overheidsfinanciering en de rol van de publieke sector.',
         'left_view' => 'Vinden dat een sterke en onafhankelijke publieke omroep essentieel is voor een geïnformeerde samenleving en dat hierin voldoende geïnvesteerd moet worden.',
         'right_view' => 'Geloven dat de publieke omroep te groot en te commercieel is geworden en dat er bezuinigd moet worden, met meer ruimte voor commerciële partijen.',
         'order_number' => 24,
         'positions' => [
             'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil fors bezuinigen op de NPO en deze omvormen tot een staatszender die het Nederlandse volk dient.'],
             'VVD' => ['position' => 'oneens', 'explanation' => 'VVD wil de NPO hervormen en efficiënter maken, maar behoudt de publieke functie.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een pluriforme en onafhankelijke publieke omroep, met aandacht voor de effectiviteit van subsidies.'],
             'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil bezuinigen op de NPO en meer aandacht voor regionale omroepen.'],
             'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA wil een sterke en onafhankelijke publieke omroep en is tegen verdere bezuinigingen.'],
             'D66' => ['position' => 'oneens', 'explanation' => 'D66 pleit voor een sterke publieke omroep die maatschappelijk van belang is en goed gefinancierd wordt.'],
             'SP' => ['position' => 'oneens', 'explanation' => 'SP wil een sterke publieke omroep die niet commercieel is en een breed aanbod biedt voor iedereen.'],
             'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD ziet de publieke omroep als een belangrijke pilaar van de democratie en pleit voor voldoende financiering.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een publieke omroep die verbindt en informeert, met aandacht voor maatschappelijke relevantie.'],
             'JA21' => ['position' => 'eens', 'explanation' => 'JA21 wil fors bezuinigen op de NPO en de publieke omroep privatiseren.'],
             'SGP' => ['position' => 'eens', 'explanation' => 'SGP is kritisch op de NPO en wil meer focus op de kerntaken en minder commerciële activiteiten.'],
             'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil de NPO afschaffen en de media privatiseren.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een publieke omroep die diversiteit weerspiegelt en maatschappelijk van belang is.'],
             'Volt' => ['position' => 'oneens', 'explanation' => 'Volt pleit voor een Europees publiek mediabeleid dat pluralisme en onafhankelijkheid garandeert.']
         ]
     ],
     [
         'title' => 'Stikstofbeleid',
         'description' => 'De huidige stikstofmaatregelen (zoals het uitkopen van boeren en het verminderen van de veestapel) moeten volledig worden uitgevoerd, ook als dit ten koste gaat van landbouwbedrijven.',
         'context' => 'Deze stelling gaat over het huidige Nederlandse stikstofbeleid dat stikstofemissies wil verminderen om natuurgebieden te beschermen. De maatregelen omvatten onder andere het uitkopen van boerenbedrijven, het verminderen van de veestapel, en het aanscherpen van vergunningen. Voorstanders zien dit als noodzakelijk om de natuur te herstellen, tegenstanders vinden het te ingrijpend voor de landbouwsector.',
         'left_view' => 'Vinden dat de natuur en het milieu voorrang moeten krijgen en dat het stikstofbeleid snel en effectief moet worden uitgevoerd.',
         'right_view' => 'Geloven dat het stikstofbeleid te ver gaat en dat de belangen van boeren en de agrarische sector meer gewicht moeten krijgen.',
         'order_number' => 25,
         'positions' => [
             'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil het stikstofbeleid afschaffen en de boeren de ruimte geven.'],
             'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een evenwichtig stikstofbeleid dat de natuur beschermt, maar ook perspectief biedt voor boeren.'],
             'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een realistisch en haalbaar stikstofbeleid dat natuurherstel combineert met een toekomst voor de landbouw.'],
             'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is fel tegen het huidige stikstofbeleid en wil dat de boeren weer centraal staan.'],
             'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil het stikstofbeleid onverkort doorzetten en de stikstofuitstoot drastisch verminderen om de natuur te herstellen.'],
             'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit voor een ambitieus stikstofbeleid om de natuur te herstellen en de gezondheid te beschermen.'],
             'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil een stikstofbeleid dat recht doet aan de boeren en de natuur, met aandacht voor duurzame oplossingen.'],
             'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD wil een radicale aanpak van het stikstofprobleem door een inkrimping van de veestapel en een transitie naar plantaardige landbouw.'],
             'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een stikstofbeleid dat de natuur beschermt, maar ook perspectief biedt voor de boeren en de landbouwsector.'],
             'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil het stikstofbeleid opschorten en de boeren beschermen.'],
             'SGP' => ['position' => 'oneens', 'explanation' => 'SGP wil een realistisch stikstofbeleid dat de boeren niet onevenredig treft.'],
             'FvD' => ['position' => 'oneens', 'explanation' => 'FvD ontkent de ernst van de stikstofcrisis en wil het stikstofbeleid afschaffen.'],
             'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een stikstofbeleid dat de natuur beschermt, maar ook rekening houdt met de belangen van boeren.'],
             'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een Europees stikstofbeleid dat de natuur beschermt en de transitie naar duurzame landbouw stimuleert.']
         ]
     ]
 ]; 

 try { 
     echo "Starting data import...\n\n"; 
      
     // 1. Importeer partijen 
     echo "Importing parties...\n"; 
     foreach ($parties as $party) { 
         $db->query(" 
             INSERT IGNORE INTO stemwijzer_parties (name, short_name, logo_url)  
             VALUES (:name, :short_name, :logo_url) 
         "); 
         $db->bind(':name', $party['name']); 
         $db->bind(':short_name', $party['short_name']); 
         $db->bind(':logo_url', $party['logo_url']); 
          
         if ($db->execute()) { 
             echo "✓ Imported party: {$party['short_name']}\n"; 
         } else { 
             echo "✗ Failed to import party: {$party['short_name']}\n"; 
         } 
     } 
      
     echo "\n"; 
      
     // 2. Importeer vragen 
     echo "Importing questions...\n"; 
     foreach ($questions as $question) { 
         // Importeer vraag 
         $db->query(" 
             INSERT IGNORE INTO stemwijzer_questions  
             (title, description, context, left_view, right_view, order_number)  
             VALUES (:title, :description, :context, :left_view, :right_view, :order_number) 
         "); 
         $db->bind(':title', $question['title']); 
         $db->bind(':description', $question['description']); 
         $db->bind(':context', $question['context']); 
         $db->bind(':left_view', $question['left_view']); 
         $db->bind(':right_view', $question['right_view']); 
         $db->bind(':order_number', $question['order_number']); 
          
         if ($db->execute()) { 
             $questionId = $db->lastInsertId(); 
             echo "✓ Imported question: {$question['title']}\n"; 
              
             // Importeer standpunten voor deze vraag 
             foreach ($question['positions'] as $partyShortName => $positionData) { 
                 // Haal party ID op 
                 $db->query("SELECT id FROM stemwijzer_parties WHERE short_name = :short_name"); 
                 $db->bind(':short_name', $partyShortName); 
                 $party = $db->single(); 
                  
                 if ($party) { 
                     $db->query(" 
                         INSERT IGNORE INTO stemwijzer_positions  
                         (question_id, party_id, position, explanation)  
                         VALUES (:question_id, :party_id, :position, :explanation) 
                     "); 
                     $db->bind(':question_id', $questionId); 
                     $db->bind(':party_id', $party->id); 
                     $db->bind(':position', $positionData['position']); 
                     $db->bind(':explanation', $positionData['explanation']); 
                      
                     if ($db->execute()) { 
                         echo "  ✓ Added position for {$partyShortName}: {$positionData['position']}\n"; 
                     } else { 
                         echo "  ✗ Failed to add position for {$partyShortName}\n"; 
                     } 
                 } 
             } 
         } else { 
             echo "✗ Failed to import question: {$question['title']}\n"; 
         } 
         echo "\n"; 
     } 
      
     echo "Data import completed successfully!\n"; 
      
 } catch (Exception $e) { 
     echo "Error during import: " . $e->getMessage() . "\n"; 
 }