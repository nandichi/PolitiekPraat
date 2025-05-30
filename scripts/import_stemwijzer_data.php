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

// Vragen data
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
    // START NIEUWE VRAGEN VANAF HIER (order_number 3 t/m 27)
    [
        'title' => 'Kernenergie',
        'description' => 'Nederland moet investeren in de bouw van nieuwe kerncentrales om de energiedoelen te halen en minder afhankelijk te zijn van fossiele brandstoffen.',
        'context' => 'De discussie over kernenergie is opnieuw actueel vanwege de klimaatdoelstellingen en de wens om energieonafhankelijkheid te vergroten. Voorstanders wijzen op de CO2-arme energieproductie, tegenstanders op de kosten, bouwtijd en het kernafval.',
        'left_view' => 'Zien kernenergie als een noodzakelijke en betrouwbare aanvulling op duurzame energiebronnen zoals zon en wind.',
        'right_view' => 'Hebben zorgen over de veiligheid, het afvalprobleem en de hoge investeringskosten, en geven de voorkeur aan andere duurzame oplossingen.',
        'order_number' => 3,
        'positions' => [
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV is een groot voorstander van nieuwe kerncentrales voor energiezekerheid.'],
            'VVD' => ['position' => 'eens', 'explanation' => 'VVD ziet kernenergie als een belangrijke optie voor de energiemix en CO2-reductie.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC staat open voor kernenergie, mits veilig en economisch haalbaar, als onderdeel van een brede energiestrategie.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB ziet kernenergie als een realistische optie om de energietransitie te ondersteunen.'],
            'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA investeert liever in zon, wind en energiebesparing en wijst kernenergie af vanwege risico\'s en kosten.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 sluit kernenergie niet uit, maar legt de prioriteit bij hernieuwbare bronnen en innovatie.'],
            'SP' => ['position' => 'oneens', 'explanation' => 'SP is tegen kernenergie vanwege de risico\'s en het afval, en wil focus op publieke duurzame energie.'],
            'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD is fel tegen kernenergie en pleit voor een volledige focus op natuurvriendelijke duurzame energie.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA staat open voor een rol voor kernenergie in de energiemix, maar benadrukt zorgvuldige afweging.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 is een sterk voorstander van kernenergie voor betrouwbare en betaalbare energie.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP ziet kernenergie als een verantwoorde optie om de energievoorziening te waarborgen.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD is een uitgesproken voorstander van kernenergie.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK weegt de voor- en nadelen af en legt de nadruk op een rechtvaardige energietransitie.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt staat open voor moderne kernenergie als onderdeel van een Europese energiestrategie, met strenge veiligheidseisen.']
        ]
    ],
    [
        'title' => 'Betaalbaarheid Wonen',
        'description' => 'De overheid moet veel meer sociale huurwoningen en betaalbare koopwoningen bouwen, desnoods door de regie over de volkshuisvesting strenger te nemen.',
        'context' => 'De woningnood in Nederland is hoog, met name voor starters en lagere- en middeninkomens. Deze stelling gaat over de rol van de overheid in het oplossen van dit probleem door actief in te grijpen in de woningmarkt.',
        'left_view' => 'Vinden dat de overheid een kerntaak heeft in het zorgen voor voldoende betaalbare woningen en de markt hierin faalt.',
        'right_view' => 'Vinden dat de marktwerking gestimuleerd moet worden en dat te veel overheidsingrijpen de bouw juist kan vertragen of de kwaliteit kan verlagen.',
        'order_number' => 4,
        'positions' => [ // Generieke posities, dienen specifiek gemaakt te worden
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil meer betaalbare woningen voor Nederlanders en minder voor statushouders.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil doorbouwen en processen versnellen, met ruimte voor zowel sociale als vrije sector huur en koop.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC ziet volkshuisvesting als een prioriteit en wil dat de overheid meer regie neemt.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil meer bouwen, met name in landelijke gebieden, en aandacht voor betaalbaarheid.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor forse investeringen in sociale huur en het aanpakken van speculatie op de woningmarkt.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil meer en sneller bouwen, met aandacht voor duurzaamheid en toegankelijkheid.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil een actieve rol voor de overheid, speculatie tegengaan en de sociale huursector versterken.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD legt de nadruk op duurzaam bouwen en het herbestemmen van bestaand vastgoed, naast nieuwbouw.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA wil een evenwichtige woningmarkt met aandacht voor starters, gezinnen en ouderen.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 wil bouwen, maar is kritisch op te veel overheidsingrijpen dat de markt kan verstoren.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP benadrukt het belang van een solide woningmarkt en afweging van belangen.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD wil minder regulering en meer vrijheid voor de markt om te bouwen.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil de woningnood aanpakken met focus op betaalbaarheid en tegengaan van discriminatie.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor innovatieve en duurzame oplossingen voor de woningcrisis, mogelijk met Europese samenwerking.']
        ]
    ],
    [
        'title' => 'Stikstofcrisis en Landbouw',
        'description' => 'De overheid moet de stikstofdoelen van 2030 handhaven, ook als dit betekent dat een deel van de agrarische sector moet inkrimpen of verplaatsen.',
        'context' => 'Nederland heeft te maken met een stikstofoverschot dat schadelijk is voor de natuur. De landbouw is een grote uitstoter. Deze stelling gaat over de balans tussen natuurbescherming en de toekomst van de agrarische sector.',
        'left_view' => 'Vinden dat natuurherstel prioriteit heeft en de stikstofdoelen cruciaal zijn, wat aanpassingen in de landbouw onvermijdelijk maakt.',
        'right_view' => 'Vinden dat de landbouwsector onevenredig hard wordt getroffen en pleiten voor meer tijd, innovatie en minder strenge of andere doelen.',
        'order_number' => 5,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil de boeren niet wegjagen en is kritisch op de huidige stikstofdoelen.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil stikstofreductie, maar met oog voor de boeren en realistische oplossingen.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC zoekt een balans tussen natuurherstel en een duurzame toekomst voor de landbouw.'],
            'BBB' => ['position' => 'oneens', 'explanation' => 'BBB verzet zich tegen gedwongen krimp en wil andere meetmethoden en innovatie stimuleren.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA vindt natuurherstel urgent en wil vasthouden aan de doelen, met steun voor boeren die omschakelen.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil de stikstofcrisis aanpakken om natuur te herstellen en ruimte te creëren voor andere ontwikkelingen.'],
            'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil een eerlijke transitie voor boeren en geen oneerlijke lastenverdeling.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een forse krimp van de veestapel en een omslag naar plantaardige landbouw.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA zoekt naar een evenwichtige oplossing met perspectief voor boeren en natuur.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is zeer kritisch op het huidige stikstofbeleid en de gevolgen voor boeren.'],
            'SGP' => ['position' => 'oneens', 'explanation' => 'SGP wil de positie van boeren beschermen en meer realisme in het stikstofbeleid.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD ontkent de ernst van de stikstofcrisis en verzet zich tegen maatregelen.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK weegt de verschillende belangen af en wil een duurzame oplossing.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een wetenschappelijk onderbouwde aanpak van de stikstofcrisis, met oog voor innovatie.']
        ]
    ],
    [
        'title' => 'Zorgkosten en Eigen Risico',
        'description' => 'Het verplichte eigen risico in de zorgverzekering moet worden afgeschaft of aanzienlijk verlaagd, ook als dit leidt tot een hogere zorgpremie voor iedereen.',
        'context' => 'Het eigen risico is een drempel voor zorggebruik, maar kan ook leiden tot zorgmijding. Afschaffing of verlaging maakt zorg toegankelijker, maar de kosten moeten wel gedekt worden, mogelijk via de premie.',
        'left_view' => 'Vinden dat het eigen risico een financiële drempel is die de toegang tot noodzakelijke zorg belemmert en solidariteit ondermijnt.',
        'right_view' => 'Vinden dat het eigen risico mensen bewust maakt van zorgkosten en bijdraagt aan de betaalbaarheid van het zorgstelsel als geheel.',
        'order_number' => 6,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil het eigen risico afschaffen of fors verlagen.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD ziet het eigen risico als een belangrijk instrument voor kostenbewustzijn.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil kijken naar hervorming van het eigen risico om zorgmijding tegen te gaan, maar ook betaalbaarheid te waarborgen.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB is voorstander van het verlagen of afschaffen van het eigen risico.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil het eigen risico afschaffen om de zorg toegankelijker te maken.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil het eigen risico hervormen, mogelijk inkomensafhankelijk maken.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP strijdt al jaren voor de afschaffing van het eigen risico.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD wil het eigen risico afschaffen en focussen op preventie.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil het eigen risico hervormen om het eerlijker te maken.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil het eigen risico behouden voor kostenbewustzijn.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging maken tussen toegankelijkheid en betaalbaarheid.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD heeft wisselende standpunten gehad, focus op hervorming zorgstelsel.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil het eigen risico afschaffen of fors verlagen.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt staat open voor hervorming van het eigen risico binnen een breder plan voor de zorg.']
        ]
    ],
    [
        'title' => 'Minimumloon',
        'description' => 'Het wettelijk minimumloon moet substantieel verder omhoog, bijvoorbeeld naar 16 euro per uur, om armoede te bestrijden en de koopkracht te verbeteren.',
        'context' => 'Een hoger minimumloon kan mensen aan de onderkant van de arbeidsmarkt helpen, maar werkgevers waarschuwen voor hogere loonkosten die kunnen leiden tot prijsstijgingen of banenverlies.',
        'left_view' => 'Vinden dat een hoger minimumloon noodzakelijk is voor een bestaanszekerheid en een eerlijke beloning voor werk.',
        'right_view' => 'Zijn bezorgd dat een te snelle of te grote stijging van het minimumloon de werkgelegenheid schaadt en de inflatie aanwakkert.',
        'order_number' => 7,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil hogere lonen maar weegt ook de lasten voor werkgevers.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD is voorzichtig met grote verhogingen, wijst op mogelijke negatieve effecten voor werkgelegenheid.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een fatsoenlijk loon, maar kijkt ook naar de bredere economische effecten.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil een eerlijk loon, maar let op de lasten voor MKB-bedrijven.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor een forse verhoging van het minimumloon naar €16.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil het minimumloon verhogen, maar in stappen en gekoppeld aan productiviteit.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil een substantieel hoger minimumloon om armoede te bestrijden.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD steunt een hoger minimumloon als onderdeel van een rechtvaardige economie.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een hoger minimumloon, maar met oog voor de draagkracht van werkgevers.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is terughoudend met grote verhogingen vanwege de lasten voor ondernemers.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP pleit voor een evenwichtige benadering van loonvorming.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen grote overheidsingrijpen in de loonvorming.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een forse verhoging van het minimumloon.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt steunt een Europees kader voor minimumlonen en een substantiële verhoging in Nederland.']
        ]
    ],
    [
        'title' => 'Rekeningrijden',
        'description' => 'Er moet een vorm van rekeningrijden (betalen per gereden kilometer) worden ingevoerd om de filedruk te verminderen en automobiliteit eerlijker te belasten.',
        'context' => 'Rekeningrijden kan autogebruik ontmoedigen, vooral tijdens spitsuren en in drukke gebieden, en zo bijdragen aan minder files en een lagere CO2-uitstoot. Critici vrezen hogere kosten voor automobilisten, vooral in regio\'s met minder alternatieven.',
        'left_view' => 'Zien rekeningrijden als een effectief middel om files te bestrijden, luchtvervuiling te verminderen en het gebruik van openbaar vervoer te stimuleren.',
        'right_view' => 'Vrezen dat rekeningrijden een extra belasting is voor automobilisten, de privacy aantast en nadelig is voor mensen die afhankelijk zijn van de auto.',
        'order_number' => 8,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is tegen rekeningrijden, ziet het als een extra belasting voor de automobilist.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD staat open voor betalen naar gebruik, mits de totale lasten voor automobilisten niet stijgen.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil de effecten zorgvuldig onderzoeken, met aandacht voor regionale verschillen en alternatieven.'],
            'BBB' => ['position' => 'oneens', 'explanation' => 'BBB is tegen rekeningrijden, vreest nadelen voor het platteland.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA is voorstander van rekeningrijden om duurzame mobiliteit te stimuleren.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 ziet rekeningrijden als een slimme manier om mobiliteit te sturen en te verduurzamen.'],
            'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil dat de lasten eerlijk verdeeld worden en dat er goede alternatieven zijn.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD is voor rekeningrijden als middel om de ecologische voetafdruk van verkeer te verkleinen.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA staat open voor onderzoek, maar wil geen lastenverzwaring voor de automobilist zonder goede alternatieven.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen rekeningrijden.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP weegt de voor- en nadelen af, met aandacht voor bereikbaarheid en lasten.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is fel tegen rekeningrijden.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een eerlijke verdeling van de lasten en goede OV-alternatieven.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt is voorstander van een slimme kilometerheffing, mogelijk in Europees verband.']
        ]
    ],
    [
        'title' => 'Defensie-uitgaven',
        'description' => 'Nederland moet de defensie-uitgaven verder verhogen, ruim boven de NAVO-norm van 2% van het BBP, gezien de huidige geopolitieke spanningen.',
        'context' => 'De oorlog in Oekraïne en andere internationale spanningen hebben de discussie over defensiebudgetten aangewakkerd. De NAVO-norm is 2% van het Bruto Binnenlands Product. Meer uitgeven betekent meer militaire capaciteit, maar gaat ten koste van andere overheidsuitgaven.',
        'left_view' => 'Benadrukken het belang van diplomatie en ontwikkelingssamenwerking en zijn terughoudend met forse stijgingen van defensiebudgetten, of willen dat dit via Europese samenwerking gebeurt.',
        'right_view' => 'Vinden een sterke defensie essentieel voor nationale veiligheid en internationale stabiliteit en pleiten voor aanzienlijk hogere investeringen.',
        'order_number' => 9,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil een sterke Nederlandse defensie en hogere uitgaven.'],
            'VVD' => ['position' => 'eens', 'explanation' => 'VVD is voorstander van het halen en overschrijden van de NAVO-norm.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC vindt een robuuste defensie belangrijk en steunt hogere uitgaven.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB steunt investeringen in defensie voor nationale veiligheid.'],
            'GL-PvdA' => ['position' => 'neutraal', 'explanation' => 'GL-PvdA erkent de noodzaak van een sterke defensie, maar legt ook nadruk op Europese samenwerking en vredesopbouw.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil investeren in een moderne, Europese defensie en de NAVO-norm halen.'],
            'SP' => ['position' => 'oneens', 'explanation' => 'SP is kritisch op hogere defensie-uitgaven en wil focus op de-escalatie en diplomatie.'],
            'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD is tegen verhoging van defensie-uitgaven en pleit voor investeringen in vrede en duurzaamheid.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA steunt een sterke defensie en het voldoen aan internationale verplichtingen.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 is een sterke voorstander van hogere defensie-uitgaven.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP vindt een sterke defensie noodzakelijk en steunt hogere uitgaven.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD is voor een sterke defensie maar kritisch op NAVO en internationale inmenging.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK legt de nadruk op internationale rechtsorde en diplomatie, maar erkent noodzaak defensie.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een sterker Europees defensiebeleid en gezamenlijke investeringen.']
        ]
    ],
    [
        'title' => 'Kunstmatige Intelligentie (AI)',
        'description' => 'De overheid moet strengere regels opstellen voor de ontwikkeling en het gebruik van Kunstmatige Intelligentie (AI) om risico\'s rond privacy, discriminatie en banenverlies te beperken.',
        'context' => 'AI biedt enorme kansen, maar brengt ook risico\'s met zich mee. Deze stelling gaat over de vraag of de overheid proactief moet ingrijpen met regulering, of dat dit innovatie kan remmen.',
        'left_view' => 'Vinden dat strenge regulering nu nodig is om de negatieve maatschappelijke gevolgen van AI te voorkomen en grondrechten te beschermen.',
        'right_view' => 'Waarschuwen dat te strenge of te vroege regulering de technologische ontwikkeling en economische kansen van AI kan belemmeren.',
        'order_number' => 10,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'Deze partij weegt de kansen en risico\'s van AI zorgvuldig af.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil innovatie stimuleren, maar met duidelijke kaders voor ethisch gebruik van AI.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC benadrukt het belang van ethische richtlijnen en toezicht op AI.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB ziet kansen voor AI in de landbouw, maar wil waken voor negatieve effecten.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor strenge regulering om publieke waarden te borgen.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil duidelijke Europese regels voor AI die innovatie en grondrechten balanceren.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP is bezorgd over de impact van AI op werkgelegenheid en privacy, en wil sterke regulering.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD benadrukt de ethische en maatschappelijke risico\'s en wil strenge kaders.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een verantwoorde ontwikkeling van AI, met oog voor menselijke maat.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 wil de kansen van AI benutten, maar is alert op veiligheidsrisico\'s.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP benadrukt ethische overwegingen bij de ontwikkeling van AI.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is kritisch op te veel overheidsingrijpen en wil innovatie de ruimte geven.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil AI reguleren om discriminatie en misbruik tegen te gaan.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor Europese regelgeving die innovatie ondersteunt en burgers beschermt.']
        ]
    ],
     [
        'title' => 'Kansengelijkheid Onderwijs',
        'description' => 'De overheid moet fors extra investeren in scholen met veel achterstandsleerlingen om kansengelijkheid in het onderwijs te bevorderen.',
        'context' => 'Niet alle kinderen krijgen dezelfde kansen in het onderwijs. Deze stelling gaat over de vraag of gerichte extra financiering voor scholen in kwetsbare wijken een effectieve manier is om deze ongelijkheid te verminderen.',
        'left_view' => 'Vinden dat extra middelen cruciaal zijn om ongelijke startposities te compenseren en ieder kind een eerlijke kans te geven.',
        'right_view' => 'Vinden dat de focus moet liggen op de kwaliteit van alle scholen en dat extra geld niet altijd de oplossing is, of dat dit stigmatiserend kan werken.',
        'order_number' => 11,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil goed onderwijs voor iedereen, maar weegt de inzet van middelen af.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil investeren in onderwijskwaliteit, maar is kritisch op enkel focussen op extra geld.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC vindt kansengelijkheid in het onderwijs zeer belangrijk en steunt gerichte investeringen.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil gelijke kansen voor kinderen in zowel stad als platteland.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor forse investeringen om kansenongelijkheid aan te pakken.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil extra investeren in scholen waar dat het hardst nodig is.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil de kansenongelijkheid in het onderwijs bestrijden met extra middelen en kleinere klassen.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD steunt investeringen in kansengelijkheid en brede ontwikkeling van kinderen.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA wil investeren in gelijke kansen, met aandacht voor de rol van de gemeenschap rond de school.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 wil de focus op basisvaardigheden en kwaliteit, minder op herverdeling van middelen.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP hecht aan goed onderwijs voor iedereen en ondersteunt maatregelen die kansengelijkheid bevorderen.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is kritisch op wat zij zien als nivelleringsbeleid in het onderwijs.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK strijdt voor gelijke kansen en wil extra middelen voor scholen met veel achterstandsleerlingen.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt wil investeren in gelijke kansen en ziet onderwijs als cruciale factor voor maatschappelijke vooruitgang.']
        ]
    ],
    [
        'title' => 'Arbeidsmigratie',
        'description' => 'De komst van arbeidsmigranten van buiten de EU moet strenger worden beperkt, ook als dit leidt tot personeelstekorten in bepaalde sectoren.',
        'context' => 'Nederland kent tekorten op de arbeidsmarkt, deels opgevuld door arbeidsmigranten. Deze stelling weegt het belang van economische behoeften af tegen zorgen over maatschappelijke gevolgen van migratie.',
        'left_view' => 'Benadrukken de noodzaak van arbeidsmigranten voor de economie en pleiten voor goede regulering en integratie in plaats van strenge beperkingen.',
        'right_view' => 'Maken zich zorgen over de druk op voorzieningen, huisvesting en de lonen van Nederlandse werknemers en willen de instroom beperken.',
        'order_number' => 12,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil arbeidsmigratie sterk beperken.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil gerichte arbeidsmigratie voor specifieke tekorten, met goede regulering.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil meer grip op migratie, inclusief arbeidsmigratie, en kijkt naar de maatschappelijke impact.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil arbeidsmigratie reguleren en misstanden aanpakken, met oog voor de landbouwsector.'],
            'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA ziet de noodzaak van arbeidsmigranten, maar wil uitbuiting tegengaan en goede arbeidsvoorwaarden.'],
            'D66' => ['position' => 'oneens', 'explanation' => 'D66 ziet arbeidsmigratie als noodzakelijk, maar pleit voor betere regulering en Europese afspraken.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP is kritisch op de huidige vorm van arbeidsmigratie en wil uitbuiting en verdringing aanpakken.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD wil de focus op een economie die minder afhankelijk is van continue groei en lage lonen.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een selectiever beleid voor arbeidsmigratie, met aandacht voor integratie.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 wil arbeidsmigratie van buiten de EU sterk beperken.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging maken tussen economische belangen en maatschappelijke gevolgen.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil immigratie, inclusief arbeidsmigratie, drastisch beperken.'],
            'DENK' => ['position' => 'oneens', 'explanation' => 'DENK ziet de bijdrage van arbeidsmigranten, maar wil misstanden en discriminatie aanpakken.'],
            'Volt' => ['position' => 'oneens', 'explanation' => 'Volt pleit voor een gereguleerd Europees migratiebeleid dat rekening houdt met economische behoeften en mensenrechten.']
        ]
    ],
    [
        'title' => 'Vliegverkeer Schiphol',
        'description' => 'Het aantal vluchten op Schiphol moet verder krimpen om geluidsoverlast en milieubelasting te verminderen, ook als dit ten koste gaat van de economische positie van de luchthaven.',
        'context' => 'Schiphol is een belangrijke economische motor, maar veroorzaakt ook overlast en vervuiling. Deze stelling gaat over de afweging tussen economisch belang en leefbaarheid/milieu.',
        'left_view' => 'Vinden dat de negatieve gevolgen van vliegverkeer voor omwonenden en het milieu zwaarder wegen en krimp onvermijdelijk is.',
        'right_view' => 'Benadrukken het economisch belang van Schiphol als internationale hub en waarschuwen dat krimp banen en welvaart kost.',
        'order_number' => 13,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV wil de hubfunctie van Schiphol behouden en is tegen krimp.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD hecht aan de economische rol van Schiphol, maar wil wel verduurzaming en hinderbeperking.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC zoekt een nieuwe balans tussen het belang van Schiphol en de leefomgeving.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil de belangen van de regio en de luchthaven zorgvuldig afwegen.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor krimp van Schiphol en investeringen in duurzame alternatieven.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil dat Schiphol krimpt en verduurzaamt, met een nachtsluiting.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil minder vluchten en meer aandacht voor de belangen van omwonenden.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een forse krimp van de luchtvaart en een einde aan de uitzonderingspositie.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een toekomstbestendige luchtvaart, met oog voor economie, milieu en leefbaarheid.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil de positie van Schiphol als internationale hub behouden.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging, met aandacht voor zondagsrust en milieu.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen beperkingen voor Schiphol.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK weegt de economische belangen af tegen de impact op de leefomgeving.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt wil luchtvaart verduurzamen, kortere vluchten vervangen door treinen en nachtvluchten beperken.']
        ]
    ],
    [
        'title' => 'Flexibilisering Arbeidsmarkt',
        'description' => 'De overheid moet de doorgeschoten flexibilisering van de arbeidsmarkt tegengaan door flexcontracten duurder en minder aantrekkelijk te maken en vaste contracten te stimuleren.',
        'context' => 'Veel mensen werken op flexibele contracten, wat onzekerheid kan geven. Deze stelling gaat over de balans tussen flexibiliteit voor werkgevers en zekerheid voor werknemers.',
        'left_view' => 'Vinden dat flexwerk te ver is doorgeschoten en leidt tot een tweedeling op de arbeidsmarkt en bestaansonzekerheid.',
        'right_view' => 'Benadrukken dat flexibiliteit nodig is voor bedrijven om te kunnen concurreren en in te spelen op veranderende marktomstandigheden.',
        'order_number' => 14,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil baanzekerheid, maar weegt ook belangen van ondernemers.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD hecht aan flexibiliteit voor ondernemers, maar wil wel excessen aanpakken.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil meer vaste contracten en minder onzekerheid voor werkenden.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil een goede balans tussen flexibiliteit en zekerheid, met aandacht voor MKB.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil flexwerk sterk terugdringen en vaste banen stimuleren.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil de balans tussen flex en vast herstellen, met meer zekerheid voor werkenden.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP strijdt tegen doorgeschoten flex en voor echte banen met zekerheid.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD wil meer zekerheid voor werknemers en minder focus op flexibiliteit ten koste van alles.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA wil de waarde van vast werk herstellen en flexibilisering aan banden leggen.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 wil de flexibiliteit voor ondernemers behouden.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP zoekt een evenwicht tussen de belangen van werkgevers en werknemers.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD wil minder regels voor ondernemers, inclusief rond arbeidscontracten.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil de positie van flexwerkers versterken en vaste contracten stimuleren.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt wil een moderne arbeidsmarkt met meer zekerheid en ontwikkelingskansen voor iedereen.']
        ]
    ],
    [
        'title' => 'Digitale Overheid en Privacy',
        'description' => 'De overheid moet terughoudender zijn met het verzamelen en koppelen van data van burgers, ook als dit ten koste gaat van efficiëntie of fraudebestrijding.',
        'context' => 'De overheid verzamelt veel data om taken uit te voeren. Dit kan efficiënt zijn, maar er zijn zorgen over privacy en de mogelijkheid van een controlestaat. Denk aan de toeslagenaffaire.',
        'left_view' => 'Vinden dat privacy een fundamenteel recht is dat zwaarder weegt en dat de overheid zeer voorzichtig moet zijn met data.',
        'right_view' => 'Benadrukken dat dataverzameling nodig is voor effectief overheidsoptreden, fraudebestrijding en veiligheid, mits binnen wettelijke kaders.',
        'order_number' => 15,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil effectieve fraudebestrijding, maar is ook kritisch op overmatige dataverzameling.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een balans tussen effectief overheidshandelen en privacybescherming.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC pleit voor een terughoudende overheid op het gebied van data, lessen trekkend uit de toeslagenaffaire.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB is kritisch op grootschalige dataverzameling door de overheid.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil de privacy van burgers beter beschermen tegen de overheid.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 is een sterke voorvechter van privacy en digitale grondrechten.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil een overheid die de privacy van burgers respecteert en niet onnodig data verzamelt.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor maximale privacybescherming en terughoudendheid van de overheid.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een zorgvuldige omgang met data, met oog voor zowel privacy als publieke belangen.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 wil een effectieve overheid, maar is ook waakzaam voor te veel staatsmacht.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP benadrukt het belang van privacy, maar ook de noodzaak van ordehandhaving.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD is zeer kritisch op dataverzameling door de overheid en wat zij zien als een controlestaat.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil burgers beschermen tegen onnodige dataverzameling en profileren door de overheid.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor sterke Europese privacyregels en transparantie in dataverwerking door overheden.']
        ]
    ],
    [
        'title' => 'Rol van de EU',
        'description' => 'Nederland moet streven naar minder macht voor de Europese Unie en meer nationale soevereiniteit, ook als dit de Europese samenwerking bemoeilijkt.',
        'context' => 'De EU heeft invloed op veel beleidsterreinen. Sommigen willen meer nationale controle, anderen juist meer Europese integratie om grote problemen aan te pakken.',
        'left_view' => 'Zien Europese samenwerking als essentieel voor vrede, welvaart en het aanpakken van grensoverschrijdende problemen zoals klimaat en migratie.',
        'right_view' => 'Vinden dat de EU te veel macht heeft en dat nationale belangen en soevereiniteit voorop moeten staan.',
        'order_number' => 16,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil een Nexit en maximale nationale soevereiniteit.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD is voor Europese samenwerking waar het Nederland sterker maakt, maar kritisch op onnodige machtsoverdracht.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een kritisch-constructieve houding ten opzichte van de EU, met focus op subsidiariteit.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB is kritisch op de EU en wil meer macht terug naar Nederland, vooral op landbouwgebied.'],
            'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA is pro-Europees en wil de EU versterken om grote uitdagingen aan te gaan.'],
            'D66' => ['position' => 'oneens', 'explanation' => 'D66 is een uitgesproken voorstander van meer en diepere Europese integratie.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP is kritisch op de huidige EU en wil een socialer en democratischer Europa, met meer nationale zeggenschap.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD is kritisch op de EU, maar ziet ook de noodzaak van internationale samenwerking voor milieu en dierenwelzijn.'],
            'CDA' => ['position' => 'oneens', 'explanation' => 'CDA is voor een sterke EU, maar wil wel dat besluiten zo dicht mogelijk bij de burger worden genomen.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 is eurosceptisch en wil minder macht naar Brussel.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP is kritisch op de EU en wil nationale soevereiniteit behouden.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil een Nexit en volledige soevereiniteit.'],
            'DENK' => ['position' => 'oneens', 'explanation' => 'DENK ziet de voordelen van de EU, maar wil dat Nederland een sterke stem heeft.'],
            'Volt' => ['position' => 'oneens', 'explanation' => 'Volt is een pan-Europese partij en pleit voor een federale Europese Unie.']
        ]
    ],
    [
        'title' => 'Drugsbeleid (Cannabis)',
        'description' => 'De overheid moet overgaan tot volledige legalisering en regulering van de productie en verkoop van cannabis, vergelijkbaar met alcohol.',
        'context' => 'Het huidige gedoogbeleid rond cannabis leidt tot problemen (achterdeurproblematiek). Legalisering kan dit oplossen en belastinginkomsten genereren, maar er zijn ook zorgen over volksgezondheid en internationale verdragen.',
        'left_view' => 'Vinden dat legalisering criminaliteit vermindert, de kwaliteit controleerbaar maakt en de overheid inkomsten oplevert.',
        'right_view' => 'Vrezen dat legalisering leidt tot meer drugsgebruik, gezondheidsproblemen en dat Nederland een narcostaat wordt.',
        'order_number' => 17,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is tegen legalisering van drugs.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD is verdeeld; sommigen voor regulering, anderen terughoudend.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil de experimenten met legale wietteelt afwachten en evalueren.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil de resultaten van de wietexperimenten afwachten.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA is voorstander van legalisering en regulering van cannabis.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit al langer voor regulering van cannabis.'],
            'SP' => ['position' => 'neutraal', 'explanation' => 'SP is voorzichtig, wil goede regulering en preventie als er stappen worden gezet.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD is voor legalisering, met focus op gezondheid en niet op commercie.'],
            'CDA' => ['position' => 'oneens', 'explanation' => 'CDA is tegen legalisering van cannabis.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen verdere legalisering van drugs.'],
            'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is principieel tegen legalisering van drugs.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD heeft geen eenduidig standpunt, sommigen voor, anderen tegen.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil een zorgvuldige afweging maken, met aandacht voor gezondheid en veiligheid.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt is voorstander van legalisering en regulering, mogelijk in Europees verband.']
        ]
    ],
     [
        'title' => 'Pensioenstelsel',
        'description' => 'Het nieuwe pensioenstelsel, met meer individuele potjes en beweeglijkere uitkeringen, moet ongewijzigd worden doorgevoerd ondanks kritiek over onzekerheid.',
        'context' => 'Nederland is overgestapt naar een nieuw pensioenstelsel. Dit moet toekomstbestendiger zijn, maar er is discussie over de gevolgen voor de hoogte en zekerheid van pensioenen.',
        'left_view' => 'Vinden dat het nieuwe stelsel te veel onzekerheid met zich meebrengt voor (toekomstige) gepensioneerden en aanpassingen nodig zijn.',
        'right_view' => 'Vinden dat het nieuwe stelsel noodzakelijk is voor de lange termijn houdbaarheid en dat het meer transparantie en flexibiliteit biedt.',
        'order_number' => 18,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV is kritisch op onderdelen, wil zekerheid voor gepensioneerden.'],
            'VVD' => ['position' => 'eens', 'explanation' => 'VVD steunt het nieuwe pensioenstelsel en wil het implementeren.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een zorgvuldige invoering en monitoren van de effecten.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB is kritisch op de overgang en wil de belangen van deelnemers goed borgen.'],
            'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA heeft veel kritiek op het nieuwe stelsel en wil aanpassingen.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 steunt het nieuwe stelsel als een noodzakelijke modernisering.'],
            'SP' => ['position' => 'oneens', 'explanation' => 'SP is fel tegen het nieuwe pensioenstelsel en wil het terugdraaien.'],
            'PvdD' => ['position' => 'oneens', 'explanation' => 'PvdD is kritisch op het nieuwe stelsel en de risico\'s voor deelnemers.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA heeft het stelsel mede vormgegeven en wil het zorgvuldig invoeren.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 is kritisch, maar ziet ook de noodzaak van hervorming.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een solide pensioenstelsel en weegt de belangen zorgvuldig.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen het nieuwe pensioenstelsel.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil dat de overgang naar het nieuwe stelsel eerlijk en transparant verloopt.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt wil een toekomstbestendig pensioenstelsel, mogelijk met Europese coördinatie.']
        ]
    ],
    [
        'title' => 'Cultuursector Subsidies',
        'description' => 'De overheid moet de subsidies aan de cultuursector (musea, theater, muziek) verhogen om de sector toegankelijk en divers te houden.',
        'context' => 'De cultuursector is deels afhankelijk van overheidssubsidies. Meer subsidie kan zorgen voor een breder en toegankelijker aanbod, maar er is ook discussie of de overheid hierin een grote rol moet spelen.',
        'left_view' => 'Vinden cultuur een essentieel onderdeel van de samenleving dat publieke steun verdient voor toegankelijkheid en innovatie.',
        'right_view' => 'Vinden dat de cultuursector meer op eigen benen moet staan en dat subsidies selectiever en efficiënter ingezet moeten worden.',
        'order_number' => 19,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is kritisch op cultuursubsidies en wil bezuinigen.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil een efficiënte inzet van subsidies, met ruimte voor zowel publiek als privaat.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC erkent het belang van cultuur, maar wil subsidies goed onderbouwd zien.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil aandacht voor cultuur in de regio en een eerlijke verdeling van middelen.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil meer investeren in cultuur en de sector versterken.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 ziet cultuur als een belangrijke investering en wil de sector steunen.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil cultuur toegankelijk maken voor iedereen en de makers ondersteunen.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD steunt cultuur die bijdraagt aan bewustwording en maatschappelijke verandering.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA waardeert cultuur, maar wil een evenwichtige verdeling van middelen.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is kritisch op de hoogte van cultuursubsidies.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil selectief zijn met cultuursubsidies, met oog voor cultureel erfgoed.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is zeer kritisch op de huidige cultuursubsidies en wil drastisch snijden.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een inclusieve cultuursector die de diversiteit van Nederland weerspiegelt.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt ziet cultuur als een verbindende factor en wil investeren in een diverse en Europese cultuursector.']
        ]
    ],
    [
        'title' => 'Watermanagement en Klimaatadaptatie',
        'description' => 'Er moet fors meer geïnvesteerd worden in watermanagement (dijken, rivierverruiming, wateropslag) om Nederland te beschermen tegen zeespiegelstijging en extreme weersomstandigheden.',
        'context' => 'Nederland is kwetsbaar voor water. Klimaatverandering verergert dit. Investeringen zijn nodig, maar kosten geld en ruimte.',
        'left_view' => 'Vinden dat grootschalige en urgente investeringen nodig zijn om de veiligheid op lange termijn te garanderen, waarbij natuurlijke oplossingen de voorkeur hebben.',
        'right_view' => 'Erkennen de noodzaak, maar benadrukken ook de kosten en de noodzaak van een realistische en gefaseerde aanpak, eventueel met meer focus op technologische oplossingen.',
        'order_number' => 20,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV vindt bescherming tegen water een kerntaak van de overheid.'],
            'VVD' => ['position' => 'eens', 'explanation' => 'VVD wil investeren in waterveiligheid als essentieel voor Nederland.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC ziet watermanagement als een cruciale langetermijninvestering.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB benadrukt het belang van waterbeheer, ook voor de landbouw.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil forse investeringen in waterveiligheid en klimaatadaptatie, met oog voor natuur.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil proactief investeren in innovatieve en duurzame oplossingen voor watermanagement.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP vindt waterveiligheid een publieke taak en wil voldoende middelen.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor investeringen die samengaan met natuurherstel en biodiversiteit.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA ziet watermanagement als een traditioneel belangrijke taak voor Nederland.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 steunt investeringen in waterveiligheid.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP vindt zorg voor waterveiligheid een belangrijke verantwoordelijkheid.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD erkent het belang, maar is kritisch op de "klimaatalarmisme" context.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat Nederland goed beschermd is tegen water, met aandacht voor kwetsbare gebieden.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een proactieve en Europees gecoördineerde aanpak van watermanagement en klimaatadaptatie.']
        ]
    ],
    [
        'title' => 'Regionale Spreiding (Overheidsdiensten)',
        'description' => 'Belangrijke overheidsdiensten en -kantoren moeten vaker buiten de Randstad worden gevestigd om de regionale economie en werkgelegenheid te stimuleren.',
        'context' => 'Veel overheidsinstellingen zijn geconcentreerd in de Randstad. Spreiding kan bijdragen aan de leefbaarheid en economie van andere regio\'s, maar kan ook leiden tot hogere kosten en minder efficiëntie.',
        'left_view' => 'Vinden dat spreiding van overheidsdiensten bijdraagt aan een evenwichtiger Nederland en de leefbaarheid van regio\'s buiten de Randstad versterkt.',
        'right_view' => 'Benadrukken de efficiëntievoordelen van concentratie en de mogelijke kosten en complexiteit van spreiding, of vinden dat de locatiekeuze primair op efficiëntie gebaseerd moet zijn.',
        'order_number' => 21,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil een efficiënte overheid, maar heeft oog voor regionale belangen.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD weegt efficiëntie en bereikbaarheid af tegen regionale ontwikkeling.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC pleit voor meer aandacht voor regio\'s buiten de Randstad en spreiding van diensten.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB is een sterke voorstander van spreiding van overheidsdiensten naar het platteland en regio\'s.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil een betere spreiding van werkgelegenheid en voorzieningen over het land.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 staat open voor spreiding, mits dit efficiënt en effectief gebeurt.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil overheidsdiensten dicht bij de mensen, ook in de regio.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD focust meer op duurzaamheid dan op geografische spreiding van diensten op zich.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA hecht aan sterke regio\'s en staat positief tegenover spreiding van overheidsdiensten.'],
            'JA21' => ['position' => 'neutraal', 'explanation' => 'JA21 legt de nadruk op een efficiënte en effectieve overheid.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging van belangen, inclusief die van regio\'s.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD wil een kleinere, efficiëntere overheid, locatie minder van belang.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil dat overheidsdiensten toegankelijk zijn voor iedereen, ongeacht woonplaats.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt kijkt naar efficiëntie en effectiviteit, mogelijk met decentrale hubs of digitale oplossingen.']
        ]
    ],
    [
        'title' => 'Leenstelsel Compensatie',
        'description' => 'Studenten die onder het leenstelsel hebben gestudeerd en geen basisbeurs hebben ontvangen, moeten een ruimere financiële compensatie krijgen dan nu is voorgesteld.',
        'context' => 'Het leenstelsel (2015-2023) verving de basisbeurs. Studenten bouwden vaak hogere schulden op. Er is een tegemoetkoming, maar velen vinden die te laag.',
        'left_view' => 'Vinden dat de "pechgeneratie" oneerlijk is behandeld en recht heeft op een substantiële compensatie voor de misgelopen basisbeurs en opgebouwde schulden.',
        'right_view' => 'Vinden dat de huidige tegemoetkoming voldoende is, dat studeren een investering in de eigen toekomst is, of dat verdere compensatie te veel kost.',
        'order_number' => 22,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV is kritisch op het leenstelsel, maar weegt de kosten van compensatie.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD vindt de huidige tegemoetkoming voldoende.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC erkent de problematiek, maar weegt de budgettaire ruimte voor extra compensatie.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil een ruimere compensatie voor de leenstelselstudenten.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA pleit voor een fors hogere compensatie voor de pechgeneratie.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 heeft het leenstelsel ingevoerd, maar staat open voor een eerlijke oplossing voor de gedupeerden.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil een volledige compensatie voor de studenten die onder het leenstelsel vielen.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD steunt een ruimhartige compensatie voor de leenstelselgeneratie.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een redelijke oplossing, maar weegt ook de overheidsfinanciën.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is terughoudend met verdere compensatie.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging van de belangen van studenten en de staatskas.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD is kritisch op het leenstelsel, maar specifieke standpunt over compensatie varieert.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil een ruimhartige compensatie voor de gedupeerde studenten.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor een eerlijke compensatie voor de leenstelselgeneratie.']
        ]
    ],
    [
        'title' => 'Aanpak Ondermijnende Criminaliteit',
        'description' => 'De overheid moet meer bevoegdheden en middelen krijgen om ondermijnende (drugs)criminaliteit hard aan te pakken, ook als dit ten koste gaat van bepaalde privacyrechten.',
        'context' => 'Ondermijning, waarbij de onderwereld de bovenwereld binnendringt, is een groot probleem. De vraag is hoever de overheid mag gaan in de bestrijding ervan, en welke rechten daarvoor eventueel moeten wijken.',
        'left_view' => 'Benadrukken het belang van rechtsbescherming en privacy, en waarschuwen voor een te repressieve aanpak die onschuldige burgers kan treffen.',
        'right_view' => 'Vinden dat de harde aanpak van zware criminaliteit prioriteit heeft en dat de overheid daarvoor de nodige instrumenten moet krijgen, zelfs als dit enige privacy-inbreuk betekent.',
        'order_number' => 23,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil een keiharde aanpak van criminaliteit en meer bevoegdheden voor opsporingsdiensten.'],
            'VVD' => ['position' => 'eens', 'explanation' => 'VVD wil ondermijning krachtig bestrijden en is bereid daarvoor extra middelen en bevoegdheden in te zetten.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een effectieve aanpak, maar met waarborgen voor de rechtsstaat en privacy.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil een stevige aanpak van criminaliteit, ook op het platteland.'],
            'GL-PvdA' => ['position' => 'neutraal', 'explanation' => 'GL-PvdA wil criminaliteit bestrijden, maar met respect voor grondrechten en focus op preventie.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 wil een effectieve aanpak van ondermijning, maar met strenge toetsing aan rechtsstatelijke principes.'],
            'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil criminaliteit aanpakken, maar waakt voor te vergaande bevoegdheden en wil investeren in wijken.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD legt de focus op preventie en het aanpakken van de oorzaken van criminaliteit.'],
            'CDA' => ['position' => 'eens', 'explanation' => 'CDA wil een stevige aanpak van ondermijning, met oog voor de rechtsstaat.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 wil een zeer harde aanpak van zware criminaliteit.'],
            'SGP' => ['position' => 'eens', 'explanation' => 'SGP steunt een krachtige aanpak van criminaliteit, met respect voor de rechtsorde.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil een harde aanpak van criminaliteit, maar is kritisch op de huidige rechtsstaat.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK wil effectieve misdaadbestrijding, maar met waarborgen tegen etnisch profileren en voor privacy.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt pleit voor een effectieve, rechtsstatelijke en Europees gecoördineerde aanpak van georganiseerde misdaad.']
        ]
    ],
    [
        'title' => 'Rol van de Koning',
        'description' => 'De Koning moet een puur ceremoniële functie krijgen en geen politieke invloed of rol meer hebben in de formatie of bij het ondertekenen van wetten.',
        'context' => 'De Koning is staatshoofd en lid van de regering. Er is discussie over de wenselijkheid van zijn politieke bevoegdheden in een moderne democratie.',
        'left_view' => 'Vinden dat in een democratie alle politieke macht bij gekozen vertegenwoordigers moet liggen en de rol van de Koning louter ceremonieel moet zijn.',
        'right_view' => 'Zien de Koning als een symbool van nationale eenheid en continuïteit, en vinden dat zijn huidige rol, inclusief beperkte politieke invloed, waardevol is.',
        'order_number' => 24,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV is voor het koningshuis, maar de precieze invulling van de rol is minder een speerpunt.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD steunt de huidige constitutionele monarchie.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC staat achter de monarchie, maar discussie over modernisering is mogelijk.'],
            'BBB' => ['position' => 'oneens', 'explanation' => 'BBB steunt het koningshuis in zijn huidige vorm.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA is voor een modernisering naar een puur ceremonieel koningschap.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 pleit al langer voor een ceremonieel koningschap en modernisering van de monarchie.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP is republikeins en wil de monarchie afschaffen, of op zijn minst ceremonieel maken.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD is voor een ceremonieel koningschap.'],
            'CDA' => ['position' => 'oneens', 'explanation' => 'CDA hecht aan de huidige rol van de Koning als staatshoofd.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 steunt de monarchie in zijn huidige vorm.'],
            'SGP' => ['position' => 'oneens', 'explanation' => 'SGP is een fervent voorstander van de monarchie, inclusief de huidige rol.'],
            'FvD' => ['position' => 'neutraal', 'explanation' => 'FvD is over het algemeen voor de monarchie, maar details kunnen verschillen.'],
            'DENK' => ['position' => 'neutraal', 'explanation' => 'DENK focust op andere prioriteiten, maar staat open voor discussie over modernisering.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt is voor een ceremonieel staatshoofd, en ziet de huidige rol van de Koning als niet passend in een moderne democratie.']
        ]
    ],
    [
        'title' => 'Verbod op Ongezond Voedselreclame',
        'description' => 'Er moet een verbod komen op reclame voor ongezonde voedingsmiddelen die gericht is op kinderen, om overgewicht en ongezonde eetpatronen tegen te gaan.',
        'context' => 'Overgewicht bij kinderen is een groeiend probleem. Een reclameverbod kan helpen, maar raakt ook de voedingsindustrie en de vrijheid van meningsuiting.',
        'left_view' => 'Vinden dat de gezondheid van kinderen voorop staat en dat de overheid hen moet beschermen tegen marketing van ongezonde producten.',
        'right_view' => 'Vinden dat een reclameverbod te betuttelend is, de verantwoordelijkheid primair bij ouders ligt en de economische vrijheid van bedrijven wordt beperkt.',
        'order_number' => 25,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'oneens', 'explanation' => 'PVV is tegen betutteling en overheidsingrijpen in persoonlijke keuzes.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD legt de nadruk op eigen verantwoordelijkheid en zelfregulering door de industrie.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC wil een gezonde leefstijl bevorderen, maar weegt de effectiviteit en proportionaliteit van een verbod.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil gezonde voeding stimuleren, maar is voorzichtig met verboden.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA wil kinderen beschermen tegen ongezonde verleidingen en pleit voor een reclameverbod.'],
            'D66' => ['position' => 'eens', 'explanation' => 'D66 wil maatregelen om een gezonde keuze makkelijker te maken, inclusief beperking van kindermarketing.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP wil de gezondheid van kinderen beschermen en is voor een verbod op junkfoodreclame.'],
            'PvdD' => ['position' => 'eens', 'explanation' => 'PvdD pleit voor een brede aanpak van ongezonde voeding, inclusief reclameverboden.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een gezonde leefomgeving, maar zet in op een mix van voorlichting en afspraken met de sector.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is tegen overheidsbemoeienis met voedselkeuzes en reclame.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP benadrukt de rol van ouders en is terughoudend met verboden, maar ziet wel belang van gezondheid.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen overheidsingrijpen in de voedselindustrie en persoonlijke vrijheid.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK wil de gezondheid van kinderen bevorderen en staat open voor maatregelen tegen ongezonde reclames.'],
            'Volt' => ['position' => 'eens', 'explanation' => 'Volt pleit voor maatregelen om een gezonde leefstijl te bevorderen, waaronder het beperken van reclame voor ongezond voedsel.']
        ]
    ],
    [
        'title' => 'Generatiepact voor Ouderen en Jongeren',
        'description' => 'Er moet een "generatiepact" komen waarbij ouderen die eerder stoppen met werken plaatsmaken voor jongeren, eventueel met financiële stimulansen van de overheid.',
        'context' => 'De arbeidsmarkt kent zowel vergrijzing als jeugdwerkloosheid of -onderbenutting. Een generatiepact zou de doorstroom kunnen bevorderen, maar roept vragen op over kosten en vrijwilligheid.',
        'left_view' => 'Zien een generatiepact als een solidair middel om jongeren kansen te bieden en ouderen de mogelijkheid te geven gezond hun pensioen te halen.',
        'right_view' => 'Zijn sceptisch over de effectiviteit en betaalbaarheid, en benadrukken de individuele keuzevrijheid en krapte op de arbeidsmarkt in veel sectoren.',
        'order_number' => 26,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'neutraal', 'explanation' => 'PVV wil goede kansen voor jong en oud, maar weegt de uitvoerbaarheid.'],
            'VVD' => ['position' => 'oneens', 'explanation' => 'VVD gelooft meer in arbeidsmarktflexibiliteit en langer doorwerken waar mogelijk.'],
            'NSC' => ['position' => 'neutraal', 'explanation' => 'NSC staat open voor ideeën die solidariteit tussen generaties bevorderen.'],
            'BBB' => ['position' => 'neutraal', 'explanation' => 'BBB wil perspectief voor jongeren en een goede oude dag voor ouderen.'],
            'GL-PvdA' => ['position' => 'eens', 'explanation' => 'GL-PvdA ziet een generatiepact als een goede manier om de arbeidsmarkt te balanceren.'],
            'D66' => ['position' => 'neutraal', 'explanation' => 'D66 staat open voor innovatieve arbeidsmarktoplossingen, mits effectief.'],
            'SP' => ['position' => 'eens', 'explanation' => 'SP is voorstander van regelingen die het voor ouderen mogelijk maken eerder te stoppen en jongeren aan werk helpen.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD focust op een bredere welvaart en zingeving, en hoe werk daarin past voor alle leeftijden.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA hecht aan intergenerationele solidariteit en wil de mogelijkheden onderzoeken.'],
            'JA21' => ['position' => 'oneens', 'explanation' => 'JA21 is terughoudend met dergelijke overheidsgestuurde arbeidsmarktmaatregelen.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil een zorgvuldige afweging van de voor- en nadelen voor alle generaties.'],
            'FvD' => ['position' => 'oneens', 'explanation' => 'FvD is tegen dergelijke overheidsingrijpen in de arbeidsmarkt.'],
            'DENK' => ['position' => 'eens', 'explanation' => 'DENK staat positief tegenover maatregelen die de kansen voor jongeren vergroten.'],
            'Volt' => ['position' => 'neutraal', 'explanation' => 'Volt wil een dynamische arbeidsmarkt en staat open voor het onderzoeken van een generatiepact.']
        ]
    ],
    [
        'title' => 'Internationale Studenten',
        'description' => 'Het aantal internationale studenten aan Nederlandse universiteiten en hogescholen moet actief worden beperkt om de druk op huisvesting en de kwaliteit van het onderwijs te verminderen.',
        'context' => 'Internationale studenten dragen bij aan de kenniseconomie, maar er zijn zorgen over de toegankelijkheid van onderwijs en huisvesting voor Nederlandse studenten en de voertaal (verengelsing).',
        'left_view' => 'Benadrukken de voordelen van internationalisering voor de kenniseconomie en culturele diversiteit, en willen inzetten op het oplossen van knelpunten zoals huisvesting.',
        'right_view' => 'Vinden dat de nadelen (druk op voorzieningen, verengelsing) zwaarder wegen en dat er een limiet moet komen op de instroom.',
        'order_number' => 27,
        'positions' => [ // Generieke posities
            'PVV' => ['position' => 'eens', 'explanation' => 'PVV wil een sterke beperking van het aantal internationale studenten.'],
            'VVD' => ['position' => 'neutraal', 'explanation' => 'VVD wil meer sturing op de instroom, met behoud van talent voor tekortsectoren.'],
            'NSC' => ['position' => 'eens', 'explanation' => 'NSC wil de instroom van internationale studenten beter beheersen en het Nederlands als voertaal bevorderen.'],
            'BBB' => ['position' => 'eens', 'explanation' => 'BBB wil de instroom beperken om de druk op huisvesting te verlichten.'],
            'GL-PvdA' => ['position' => 'oneens', 'explanation' => 'GL-PvdA ziet de waarde van internationale studenten, maar wil wel knelpunten aanpakken.'],
            'D66' => ['position' => 'oneens', 'explanation' => 'D66 is voor internationalisering, maar erkent de noodzaak om de groei beheersbaar te houden.'],
            'SP' => ['position' => 'neutraal', 'explanation' => 'SP wil dat onderwijs toegankelijk blijft voor Nederlandse studenten en dat de kwaliteit gewaarborgd is.'],
            'PvdD' => ['position' => 'neutraal', 'explanation' => 'PvdD kijkt kritisch naar de groei en de impact op duurzaamheid en huisvesting.'],
            'CDA' => ['position' => 'neutraal', 'explanation' => 'CDA wil een balans tussen de voordelen van internationalisering en de beheersbaarheid ervan.'],
            'JA21' => ['position' => 'eens', 'explanation' => 'JA21 wil de instroom van internationale studenten fors beperken.'],
            'SGP' => ['position' => 'neutraal', 'explanation' => 'SGP wil sturen op de instroom en het behoud van het Nederlands in het onderwijs.'],
            'FvD' => ['position' => 'eens', 'explanation' => 'FvD wil een sterke reductie van internationale studenten en focus op Nederlandstalig onderwijs.'],
            'DENK' => ['position' => 'oneens', 'explanation' => 'DENK ziet de meerwaarde van internationale studenten, maar wil problemen rond huisvesting aanpakken.'],
            'Volt' => ['position' => 'oneens', 'explanation' => 'Volt is voorstander van internationalisering en Europese samenwerking in het hoger onderwijs, maar met aandacht voor lokale capaciteit.']
        ]
    ]
    // EINDE NIEUWE VRAGEN
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
            // echo "✗ Failed to import party: {$party['short_name']} (might already exist)\n";
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
        
        $questionImportedSuccessfully = $db->execute();
        $questionId = $db->lastInsertId();

        if ($questionImportedSuccessfully) {
            echo "✓ Imported question: {$question['title']} (ID: {$questionId})\n";
        } else {
             // Probeer de bestaande vraag ID op te halen als order_number uniek is en het mislukt door IGNORE
            $db->query("SELECT id FROM stemwijzer_questions WHERE order_number = :order_number");
            $db->bind(':order_number', $question['order_number']);
            $existingQuestion = $db->single();
            if ($existingQuestion) {
                $questionId = $existingQuestion->id;
                echo "° Question '{$question['title']}' already exists (ID: {$questionId}). Updating positions.\n";
            } else {
                echo "✗ Failed to import question: {$question['title']}\n";
                continue; // Ga verder met de volgende vraag als deze niet geïmporteerd kan worden
            }
        }
            
        // Importeer standpunten voor deze vraag
        foreach ($question['positions'] as $partyShortName => $positionData) {
            // Haal party ID op
            $db->query("SELECT id FROM stemwijzer_parties WHERE short_name = :short_name");
            $db->bind(':short_name', $partyShortName);
            $party = $db->single();
            
            if ($party && $questionId) {
                // Controleer of het standpunt al bestaat
                $db->query("SELECT id FROM stemwijzer_positions WHERE question_id = :question_id AND party_id = :party_id");
                $db->bind(':question_id', $questionId);
                $db->bind(':party_id', $party->id);
                $existingPosition = $db->single();

                if ($existingPosition) {
                    // Update bestaand standpunt
                    $db->query("
                        UPDATE stemwijzer_positions 
                        SET position = :position, explanation = :explanation 
                        WHERE id = :id
                    ");
                    $db->bind(':position', $positionData['position']);
                    $db->bind(':explanation', $positionData['explanation']);
                    $db->bind(':id', $existingPosition->id);
                    if ($db->execute()) {
                        echo "  ~ Updated position for {$partyShortName}: {$positionData['position']}\n";
                    } else {
                        echo "  ✗ Failed to update position for {$partyShortName}\n";
                    }
                } else {
                    // Voeg nieuw standpunt toe
                    $db->query("
                        INSERT INTO stemwijzer_positions 
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
            } else {
                if (!$party) echo "  ! Party with short_name '{$partyShortName}' not found for question '{$question['title']}'.\n";
                if (!$questionId) echo "  ! QuestionId not available for question '{$question['title']}'.\n";
            }
        }
        echo "\n";
    }
    
    echo "Data import completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error during import: " . $e->getMessage() . "\n";
}
?>