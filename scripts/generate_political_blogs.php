<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/NewsAPI.php';

// Database connectie
$db = new Database();
$conn = $db->getConnection();

// Functie om een slug te genereren
function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Array met blog templates voor verschillende onderwerpen
$blogTemplates = [
    [
        'title' => 'Trump en de Bedreiging voor Onze Democratie: Een Diepgaande Analyse',
        'content' => "De recente ontwikkelingen rond Donald Trump's politieke comeback zijn meer dan zorgwekkend - ze vormen een directe bedreiging voor de fundamenten van onze democratische samenleving. Als progressieve denkers moeten we niet alleen de alarmbellen luiden, maar ook begrijpen waarom deze situatie zo gevaarlijk is voor onze gezamenlijke toekomst.

Trump's retoriek is niet simpelweg polariserend - het is een doelbewuste strategie om democratische instituties te ondermijnen en verdeeldheid te zaaien. Zijn recente overwinningen in de voorverkiezingen laten zien dat een significant deel van het Amerikaanse electoraat nog steeds vatbaar is voor zijn populistische boodschap. Dit is geen geïsoleerd Amerikaans probleem - de golven van dit antidemocratische sentiment bereiken ook onze kusten.

Wat we zien is een patroon dat historici maar al te goed herkennen: het delegitimeren van verkiezingen, het aanvallen van de vrije pers, het criminaliseren van politieke tegenstanders, en het aanwakkeren van xenofobie. Deze tactiek komt rechtstreeks uit het handboek van autoritaire leiders.

Maar er is hoop. De progressieve beweging groeit wereldwijd, vooral onder jongere generaties die inzien dat de oude manieren van politiek bedrijven niet meer werken. We zien een toenemend bewustzijn voor klimaatverandering, sociale rechtvaardigheid en economische gelijkheid.

Wat kunnen we doen?
1. Blijf geïnformeerd en verspreid betrouwbare informatie
2. Steun progressieve kandidaten en bewegingen
3. Engage in lokale politiek en gemeenschapsactivisme
4. Bouw bruggen met mensen die anders denken
5. Verdedig democratische instituties actief

De komende jaren worden cruciaal voor de toekomst van de democratie. We kunnen het ons niet veroorloven om passief toe te kijken hoe populisten en autoritaire figuren onze democratische waarden ondermijnen.",
    ],
    [
        'title' => 'De Klimaatcrisis: Waarom Halfslachtige Maatregelen Niet Meer Volstaan',
        'content' => "De klimaatcrisis is niet langer een ver-van-ons-bed show - het is een acute noodsituatie die directe en radicale actie vereist. Het rechtse narratief van 'economische groei boven alles' heeft ons in deze penibele situatie gebracht, en het is tijd om het roer drastisch om te gooien.

De feiten zijn onverbiddelijk:
- De laatste zeven jaar waren de warmste ooit gemeten
- Extreme weersomstandigheden nemen toe in frequentie en intensiteit
- Biodiversiteit neemt in alarmerend tempo af
- De zeespiegel stijgt sneller dan voorspeld

Het huidige kabinetsbeleid schiet schromelijk tekort. Terwijl we symbolische stappen zetten met windmolens en zonnepanelen, blijven de grote vervuilers grotendeels buiten schot. De lobby van grote industriële bedrijven heeft nog steeds een wurggreep op ons klimaatbeleid.

Wat we nodig hebben is een Groene Revolutie:

1. Directe Maatregelen:
- Onmiddellijke sluiting van alle kolencentrales
- Massieve investeringen in duurzame energie
- Gratis openbaar vervoer
- Verbod op nieuwe olie- en gasboringen

2. Structurele Veranderingen:
- Een eerlijke CO2-belasting voor grote vervuilers
- Grootschalige natuurherstelprojecten
- Verplichte verduurzaming van de industrie
- Stimulering van plantaardig dieet

3. Sociale Rechtvaardigheid:
- Compensatie voor lage inkomens
- Omscholingsprogramma's voor werknemers in vervuilende industrieën
- Subsidies voor verduurzaming van woningen

De tijd van polderen en compromissen sluiten is voorbij. We hebben nog maar enkele jaren om catastrofale klimaatverandering te voorkomen. Het is tijd voor radicale verandering.",
    ],
    [
        'title' => 'Woningcrisis: Het Falen van de Vrije Markt',
        'content' => "De Nederlandse woningmarkt is volledig vastgelopen, en dit is geen natuurramp - het is het directe gevolg van jarenlang neoliberaal beleid. De mythe van de zelfregulererende markt is definitief ontmaskerd, maar de gevolgen zijn desastreus voor een hele generatie.

De harde cijfers:
- Starters maken nauwelijks kans op een koopwoning
- Huurprijzen in de vrije sector zijn onbetaalbaar
- Wachtlijsten voor sociale huurwoningen lopen op tot 15 jaar
- Dakloosheid neemt toe, vooral onder jongeren

Hoe zijn we hier gekomen?
- Systematische afbraak van de sociale huursector
- Verkoop van sociale huurwoningen aan de hoogste bieder
- Fiscale voordelen voor beleggers en speculanten
- Gebrek aan overheidsregie in woningbouw

De oplossing ligt niet in meer marktwerking, maar in drastisch overheidsingrijpen:

1. Directe Maatregelen:
- Nationalisatie van grote vastgoedbedrijven
- Maximering van huurprijzen in de vrije sector
- Verbod op huisjesmelkerij
- Voorrang voor starters en sociale huur

2. Structurele Aanpak:
- Massaal bouwen van betaalbare woningen
- Herinvoering van het Ministerie van Volkshuisvesting
- Afschaffing van hypotheekrenteaftrek voor dure woningen
- Zware belasting op tweede woningen

3. Sociale Rechtvaardigheid:
- Huursubsidie voor middeninkomens
- Bescherming van huurders tegen uitbuiting
- Gemeenschappelijke woonprojecten stimuleren

Wonen is een mensenrecht, geen verdienmodel. Het is tijd dat we dit recht weer centraal stellen in ons beleid.",
    ],
    [
        'title' => 'De Groeiende Ongelijkheid: Een Tijdbom Onder Onze Samenleving',
        'content' => "De kloof tussen arm en rijk in Nederland groeit gestaag en heeft inmiddels alarmerend niveau bereikt. Dit is geen natuurverschijnsel, maar het resultaat van bewuste politieke keuzes die de afgelopen decennia zijn gemaakt.

De harde realiteit:
- De rijkste 1% bezit 26% van alle vermogen
- Kinderarmoede neemt toe
- Werkende armen worden een steeds grotere groep
- Sociale mobiliteit neemt af

Het neoliberale sprookje van 'werk jezelf omhoog' is voor velen een onbereikbare droom geworden. De coronacrisis heeft deze ongelijkheid nog verder versterkt, waarbij de rijken rijker werden en de armen verder in de problemen kwamen.

Concrete maatregelen die nodig zijn:

1. Belastinghervorming:
- Invoering vermogensbelasting
- Hogere winstbelasting voor grote bedrijven
- Aanpak van belastingontwijking
- Progressive inkomstenbelasting

2. Sociale Zekerheid:
- Verhoging minimumloon naar €15 per uur
- Basisinkomen pilot projecten
- Gratis kinderopvang
- Schuldsanering voor mensen in armoede

3. Publieke Voorzieningen:
- Investering in onderwijs in achterstandswijken
- Toegankelijke gezondheidszorg
- Betaalbaar openbaar vervoer
- Culturele voorzieningen voor iedereen

Het is tijd voor een radicale herverdeling van welvaart in Nederland.",
    ],
    [
        'title' => 'Immigratie en Integratie: Voorbij de Rechtse Angstpolitiek',
        'content' => "Het immigratiedebat in Nederland wordt al jaren gedomineerd door rechtse angstretoriek en xenofobie. Het is tijd voor een progressief tegengeluid dat uitgaat van menselijkheid, solidariteit en de realiteit van een globaliserende wereld.

De feiten die rechts liever negeert:
- Immigranten zijn essentieel voor onze economie
- Diversiteit versterkt innovatie en creativiteit
- Tweede generatie immigranten presteren steeds beter
- Vergrijzing maakt arbeidsmigratie noodzakelijk

Een humaan en effectief immigratiebeleid:

1. Vluchtelingenopvang:
- Menswaardige opvanglocaties
- Snellere asielprocedures
- Betere integratieprogramma's
- Gezinshereniging faciliteren

2. Arbeidsmigratie:
- Eerlijke arbeidsvoorwaarden
- Aanpak van uitbuiting
- Erkenning van buitenlandse diploma's
- Taalonderwijs vanaf dag één

3. Integratie:
- Investeren in gemengde wijken
- Intercultureel onderwijs
- Discriminatie actief bestrijden
- Culturele uitwisseling stimuleren

Nederland is altijd een immigratieland geweest en dat heeft ons sterker gemaakt.",
    ],
    [
        'title' => 'Onderwijs: De Sleutel tot een Rechtvaardige Samenleving',
        'content' => "Ons onderwijssysteem reproduceert sociale ongelijkheid in plaats van deze te doorbreken. De marktwerking in het onderwijs heeft geleid tot een tweedeling die onacceptabel is in een democratische samenleving.

Belangrijkste problemen:
- Groeiende kansenongelijkheid
- Lerarentekort in achterstandswijken
- Werkdruk en burn-outs
- Toenemende segregatie

Een progressieve onderwijsagenda:

1. Structurele Hervormingen:
- Afschaffing marktwerking
- Kleinere klassen
- Hogere salarissen voor leraren
- Gratis schoolmaaltijden

2. Inhoudelijke Vernieuwing:
- Focus op kritisch denken
- Burgerschapsonderwijs
- Klimaateducatie
- Digitale vaardigheden

3. Toegankelijkheid:
- Gratis kinderopvang
- Afschaffing collegegeld
- Extra ondersteuning voor achterstandsleerlingen
- Leven lang leren faciliteren

Onderwijs moet weer een emancipatiemotor worden.",
    ],
    [
        'title' => 'Democratische Vernieuwing: Voorbij het Oude Politieke Systeem',
        'content' => "Ons huidige democratische systeem kraakt in zijn voegen. Terwijl we de grootste uitdagingen uit de menselijke geschiedenis het hoofd moeten bieden, blijft ons politieke systeem steken in oude patronen en machtsstructuren.

Fundamentele problemen:
- Groeiend wantrouwen in politiek
- Lobbymacht van grote bedrijven
- Ondervertegenwoordiging van minderheden
- Gebrek aan burgerparticipatie

Noodzakelijke vernieuwingen:

1. Democratische Experimenten:
- Burgerberaden
- Participatief begroten
- Lokale referenda
- Online deliberatie platforms

2. Institutionele Hervormingen:
- Transparantie in lobbying
- Quotas voor diversiteit
- Verlaging stemleeftijd
- Hervorming kiesstelsel

3. Burgerparticipatie:
- Buurtbudgetten
- Burgerfora
- Jongerenraden
- Digitale participatietools

Echte democratie vereist continue vernieuwing en aanpassing.",
    ],
    [
        'title' => 'Digitale Rechten: Privacy in het Tijdperk van Big Tech',
        'content' => "De macht van grote technologiebedrijven over ons dagelijks leven is problematisch geworden. Als progressieven moeten we opkomen voor digitale rechten en democratische controle over technologie.

Centrale uitdagingen:
- Dataverzameling en privacy
- Algoritimische discriminatie
- Desinformatie en nepnieuws
- Monopolievorming in tech sector

Een progressieve digitale agenda:

1. Privacy Bescherming:
- Striktere privacywetgeving
- Recht op encryptie
- Verbod op gezichtsherkenning
- Dataportabiliteit

2. Democratische Controle:
- Opsplitsing tech giganten
- Publieke alternatieven
- Algoritmische transparantie
- Digitale commons

3. Digitale Rechten:
- Recht op internet
- Netneutraliteit
- Digitale geletterdheid
- Open source stimuleren

Technologie moet dienend zijn aan de samenleving, niet andersom.",
    ],
    [
        'title' => 'Economische Transformatie: Van Groei naar Welzijn',
        'content' => "Het huidige economische systeem is niet houdbaar - niet voor de planeet, niet voor de mensen. We moeten af van de obsessie met economische groei en toe naar een economie die welzijn centraal stelt.

Fundamentele problemen:
- Oneindige groei op eindige planeet
- Burn-out epidemie
- Overconsumptie
- Vervreemding van werk

Een nieuwe economische visie:

1. Welzijnseconomie:
- Kortere werkweek
- Basisinkomen experimenten
- Welzijnsindicatoren
- Circulaire productie

2. Democratische Economie:
- Werknemerscoöperaties
- Lokale economische circuits
- Publieke banken
- Commons-based productie

3. Duurzame Transitie:
- Groene industriepolitiek
- Reparatie-economie
- Deeleconomie
- Lokale voedselproductie

Een andere economie is niet alleen mogelijk, maar noodzakelijk.",
    ],
    [
        'title' => 'Gezondheidszorg: Een Publiek Goed, Geen Markt',
        'content' => "De marktwerking in de zorg heeft gefaald. Het is tijd om gezondheidszorg weer te zien als een fundamenteel mensenrecht en publiek goed, niet als een verdienmodel voor verzekeraars en farmaceutische bedrijven.

De crisis in de zorg:
- Stijgende zorgkosten
- Personeelstekorten
- Bureaucratische rompslomp
- Tweedeling in toegang

Een progressieve zorgagenda:

1. Systeemverandering:
- Nationaal Zorgfonds
- Afschaffing marktwerking
- Lagere eigen bijdragen
- Preventie centraal

2. Personeel:
- Hogere salarissen
- Minder administratie
- Betere arbeidsvoorwaarden
- Meer zeggenschap

3. Toegankelijkheid:
- Kleinere praktijken
- Meer wijkzorg
- Mentale zorg zonder wachtlijst
- Preventieve programma's

Gezondheid is een recht, geen privilege.",
    ]
];

// Voeg de blogs toe aan de database
foreach ($blogTemplates as $template) {
    $title = $template['title'];
    $slug = generateSlug($title);
    $content = $template['content'];
    $summary = substr(strip_tags($content), 0, 200) . '...';

    $stmt = $conn->prepare("INSERT INTO blogs (title, slug, summary, content, author_id, views) VALUES (?, ?, ?, ?, 1, 0)");
    $stmt->execute([$title, $slug, $summary, $content]);
}

echo "Er zijn succesvol 10 uitgebreide politieke blogs gegenereerd!\n"; 