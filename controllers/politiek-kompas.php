<?php
require_once 'includes/Database.php';
require_once 'includes/config.php';

class ProgrammaVergelijkerController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        // Thema's voor de vergelijker
        $themes = [
            'Immigratie' => [
                'title' => 'Immigratie',
                'description' => 'Asielbeleid, integratie en migratiestromen',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>'
            ],
            'Klimaat' => [
                'title' => 'Klimaat',
                'description' => 'CO2-reductie en milieumaatregelen',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h-9m9 4.5h-9"/></svg>'
            ],
            'Zorg' => [
                'title' => 'Zorg',
                'description' => 'Eigen risico en toegankelijkheid',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>'
            ],
            'Energie' => [
                'title' => 'Energie',
                'description' => 'Kernenergie en duurzame bronnen',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>'
            ],
            'Economie' => [
                'title' => 'Economie',
                'description' => 'Belastingen en ondernemerschap',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ],
            'Onderwijs' => [
                'title' => 'Onderwijs',
                'description' => 'Kwaliteit en toegankelijkheid',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>'
            ],
            'Woningmarkt' => [
                'title' => 'Woningmarkt',
                'description' => 'Betaalbare woningen en huurprijzen',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>'
            ],
            'Veiligheid' => [
                'title' => 'Veiligheid',
                'description' => 'Politie en criminaliteitsbestrijding',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>'
            ]
        ];

        // Partij informatie uit de bestaande partijen controller
$parties = [
    'PVV' => [
        'name' => 'Partij voor de Vrijheid',
        'leader' => 'Geert Wilders',
        'logo' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
        'description' => 'De Partij voor de Vrijheid (PVV) is een rechts-nationalistische partij die onder leiding van Geert Wilders streeft naar behoud van Nederlandse identiteit en soevereiniteit. De partij benadrukt een harde aanpak van immigratie en is kritisch op EU-samenwerking, met een focus op nationale controle.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'De PVV pleit voor een extreem restrictief immigratiebeleid met een stop op asielaanvragen, strengere gezinshereniging en focus op grensbewaking en uitzetting.',
                'details' => 'De PVV wil grenscontroles herinvoeren, asielopvangplaatsen drastisch reduceren en gezinshereniging voor asielzoekers stoppen. Internationale verdragen zoals het Vluchtelingenverdrag en EVRM moeten worden opgezegd of herzien. Een puntenmodel voor arbeidsmigratie richt zich op hoogopgeleiden met baangarantie. Inburgering wordt strenger met zware sancties bij falen, zoals intrekking van verblijfsrecht. De partij wil ook remigratie stimuleren voor niet-geïntegreerde migranten. Dit beleid is gebaseerd op de overtuiging dat massa-immigratie de Nederlandse cultuur en sociale cohesie bedreigt, met nadruk op assimilatie boven integratie.',
                'feasibility' => [
                    'score' => 'Zeer moeilijk',
                    'explanation' => 'Het opzeggen van internationale verdragen botst met EU-recht en internationale verplichtingen, wat juridische en diplomatieke conflicten veroorzaakt. Permanente grenscontroles schenden Schengen-akkoorden; tijdelijke controles zijn mogelijk maar beperkt. Een asielstop is juridisch vrijwel onhaalbaar zonder grondwetswijziging. Remigratieplannen stuiten op ethische en praktische bezwaren, met beperkte publieke steun. Kritiek: PVV overschat de haalbaarheid en negeert economische afhankelijkheid van migratie in sectoren zoals zorg en bouw.',
                    'costs' => '€1-2 miljard voor grensbewaking en deportatie, besparingen op opvang (€500 miljoen), juridische procedures kosten miljoenen',
                    'timeline' => '3-7 jaar voor gedeeltelijke implementatie, 10+ jaar voor verdragswijzigingen'
                ]
            ],
            'Klimaat' => [
                'summary' => 'De PVV verzet zich tegen ambitieuze klimaatmaatregelen, ziet CO2-reductie als overdreven en wil betaalbare energie boven groene doelen.',
                'details' => 'De PVV wil het Klimaatakkoord van Parijs opzeggen, subsidies voor wind- en zonne-energie schrappen en kolencentrales openhouden. Nieuwe gascentrales moeten energiezekerheid garanderen. Windmolens op land worden verwijderd, en offshore windparken stoppen. Energiebelasting en benzineaccijns worden afgeschaft. De partij betwist de rol van CO2 in klimaatverandering en richt zich op adaptatie (bijv. dijken) in plaats van mitigatie. Dit weerspiegelt scepsis over klimaatwetenschap en een focus op economische groei en lage lasten voor burgers.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Subsidies en energiebelasting schrappen kan binnen 1-2 jaar, maar opzeggen van het Klimaatakkoord botst met EU-verplichtingen, wat boetes of handelsbeperkingen oplevert. Windmolenverwijdering is juridisch complex door contracten en kostbaar. Gascentrales bouwen is haalbaar maar duurt 5-7 jaar. Kritiek: PVV negeert internationale druk en economische risico’s van afwijzing van groene transitie, zoals verlies van EU-fondsen en innovatieachterstand.',
                    'costs' => '€3-5 miljard inkomstenderving door belastingafschaffing, €1-2 miljard voor windmolenverwijdering, mogelijke EU-boetes €500 miljoen+',
                    'timeline' => '1-2 jaar voor belastingwijzigingen, 5-10 jaar voor energie-infrastructuur'
                ]
            ],
            'Zorg' => [
                'summary' => 'De PVV wil eigen risico afschaffen, zorgpremie verlagen en wachttijden verkorten door meer zorgpersoneel en minder bureaucratie.',
                'details' => 'Het eigen risico wordt volledig geschrapt om zorg toegankelijker te maken, vooral voor lagere inkomens. Zorgpremies dalen door overheidsbijdragen. De PVV wil meer zorgpersoneel via betere salarissen, minder administratie en versnelde opleidingen. Wachtlijsten worden aangepakt door ziekenhuisuitbreiding en meer specialisten. Bureaucratie wordt verminderd door managementlagen te schrappen. Nederlandse zorgverleners krijgen voorrang bij vacatures, wat migratiebeperking weerspiegelt. De focus ligt op directe zorg boven preventie of innovatie.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Afschaffing eigen risico kost €2,5-3 miljard, haalbaar met belastingverhoging elders, maar vereist coalitiesteun. Meer zorgpersoneel vergt 5-10 jaar voor opleiding en werving. Voorrang voor Nederlanders is juridisch riskant (discriminatie). Kritiek: PVV onderschat complexiteit van personeelstekorten en afhankelijkheid van buitenlandse zorgverleners. Wachtlijstvermindering vereist structurele hervormingen, geen snelle fix.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €1-2 miljard voor personeel en infrastructuur',
                    'timeline' => '1-2 jaar voor eigen risico, 5-10 jaar voor personeel en wachtlijsten'
                ]
            ],
            'Energie' => [
                'summary' => 'De PVV steunt kernenergie als betrouwbare energiebron en wil fossiele brandstoffen behouden boven dure groene alternatieven.',
                'details' => 'De PVV wil twee nieuwe kerncentrales bouwen en onderzoek doen naar kleine modulaire reactoren (SMR). Gas- en kolencentrales blijven open voor energiezekerheid. Subsidies voor wind- en zonne-energie worden gestopt, en nieuwe windparken geweigerd. De partij ziet kernenergie als oplossing voor energieonafhankelijkheid zonder economische schade door groene transitie. Dit sluit aan bij hun afwijzing van klimaatdogma’s en focus op betaalbaarheid.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Kerncentrales bouwen duurt 10-15 jaar en kost €10-20 miljard, met publieke weerstand en strenge veiligheidsregels. SMR-technologie is nog experimenteel. Fossiele brandstoffen behouden botst met EU-doelen, wat boetes oplevert. Subsidies stoppen is haalbaar maar schaadt groene sector. Kritiek: PVV negeert lange-termijnrisico’s van fossiele afhankelijkheid en onderschat complexiteit van kernenergie.',
                    'costs' => '€10-20 miljard voor kerncentrales, €500 miljoen+ voor SMR-onderzoek, EU-boetes mogelijk',
                    'timeline' => '10-15 jaar voor kerncentrales, 2-3 jaar voor subsidie-stop'
                ]
            ],
            'Economie' => [
                'summary' => 'De PVV wil minder EU-regelgeving, lagere belastingen voor burgers en mkb, en nationale controle over economische besluiten.',
                'details' => 'De PVV pleit voor verlaging van inkomsten- en vennootschapsbelasting, vooral voor mkb. EU-regelgeving wordt beperkt om nationale autonomie te versterken. Subsidies gaan naar lokale bedrijven, niet naar multinationals. De partij wil handelsbarrières met niet-EU-landen verlagen en protectionisme voor Nederlandse producten. Dit weerspiegelt anti-EU-sentiment en focus op nationale welvaart.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Belastingverlaging is mogelijk maar kost €5-10 miljard, wat compensatie vereist (bijv. hogere btw). EU-regelgeving beperken is lastig binnen EU-lidmaatschap; volledige exit is politiek onrealistisch. Protectionisme botst met WTO-regels. Kritiek: PVV onderschat economische afhankelijkheid van EU-markt en risico’s van handelsisolatie.',
                    'costs' => '€5-10 miljard inkomstenderving, juridische kosten bij EU-conflicten',
                    'timeline' => '1-3 jaar voor belastingwijzigingen, 5-10 jaar voor EU-onderhandelingen'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'De PVV wil meer nadruk op Nederlandse geschiedenis en cultuur in onderwijs, met minder multiculturele thema’s en meer praktisch onderwijs.',
                'details' => 'Het curriculum wordt herzien om nationale trots te benadrukken, met focus op Nederlandse geschiedenis, literatuur en waarden. Multiculturele en ‘woke’ thema’s worden geschrapt. Praktisch onderwijs (mbo) krijgt meer budget, en academisch onderwijs wordt selectiever. Leraren krijgen betere salarissen, maar diversiteitsquota worden afgeschaft. Dit reflecteert de PVV’s culturele conservatisme en anti-progressieve houding.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Curriculumaanscherping is mogelijk binnen 2-3 jaar, maar botst met onderwijsvrijheid en diversiteitswetten. Mbo-investeringen zijn haalbaar, maar selectiever academisch onderwijs kan ongelijkheid vergroten. Kritiek: PVV’s focus op nationalisme kan inclusiviteit schaden en weerstand oproepen bij onderwijsinstellingen.',
                    'costs' => '€1-2 miljard voor mbo en salarissen, lage kosten voor curriculumwijzigingen',
                    'timeline' => '2-3 jaar voor curriculum, 3-5 jaar voor mbo-uitbreiding'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'De PVV wil Nederlanders voorrang bij sociale huurwoningen en woningnood aanpakken door migratie te beperken en meer te bouwen.',
                'details' => 'Nederlanders krijgen prioriteit bij sociale huurwoningen, en migranten worden uitgesloten van toegang tot 2030. De PVV wil 100.000 betaalbare woningen bouwen, vooral voor starters, door bureaucratie te schrappen. Migratiebeperking moet druk op de woningmarkt verlagen. Huurprijzen worden bevroren voor lage inkomens. Dit beleid koppelt woningnood aan immigratie.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Voorrang voor Nederlanders is juridisch riskant (discriminatie) en botst met EU-recht. Woningbouw is haalbaar maar kost 5-10 jaar. Migratiebeperking heeft beperkt effect op woningnood, gezien bredere oorzaken (vergrijzing, urbanisatie). Kritiek: PVV oversimplificeert probleem en negeert economische behoefte aan migranten.',
                    'costs' => '€10-20 miljard voor woningbouw, juridische kosten bij discriminatiezaken',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor huurbevriezing'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'De PVV wil strengere straffen, meer politie en zerotolerance tegen criminaliteit, vooral gerelateerd aan immigratie.',
                'details' => 'De PVV pleit voor minimumstraffen, langere gevangenisstraffen en meer politie (10.000 extra agenten). Overlast en geweld, vooral door migranten, worden prioritair aangepakt. De partij wil detentiecentra voor illegalen en snelle uitzetting. Preventie krijgt weinig nadruk; de focus ligt op repressie. Dit weerspiegelt de PVV’s harde anti-migratie- en veiligheidshouding.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Meer politie en strengere straffen zijn mogelijk binnen 3-5 jaar, maar kosten hoog. Detentiecentra zijn juridisch complex en stuiten op mensenrechtenbezwaren. Immigratiegerichte aanpak kan discriminatieclaims opleveren. Kritiek: PVV negeert sociale oorzaken van criminaliteit en effectiviteit van preventie.',
                    'costs' => '€2-3 miljard voor politie en gevangenissen, €500 miljoen voor detentiecentra',
                    'timeline' => '3-5 jaar voor politie-uitbreiding, 2-3 jaar voor strafverhoging'
                ]
            ]
        ],
        'current_seats' => 37,
        'color' => '#1E40AF'
    ],
    'VVD' => [
        'name' => 'Volkspartij voor Vrijheid en Democratie',
        'leader' => 'Dilan Yeşilgöz-Zegerius',
        'logo' => 'https://logo.clearbit.com/vvd.nl',
        'description' => 'De VVD is een rechtsliberale partij onder leiding van Dilan Yeşilgöz-Zegerius, die inzet op individuele vrijheid, economische groei en een efficiënte overheid. De partij combineert marktgerichte oplossingen met pragmatisme.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'De VVD wil een streng maar rechtvaardig immigratiebeleid met selectieve asieltoelating, snelle integratie en aanpak van illegale migratie.',
                'details' => 'De VVD pleit voor een quotum voor asielzoekers en strengere criteria voor toelating, zoals oorlogsvluchtelingen prioriteren. Illegale migratie wordt bestreden met grensbewaking en snelle uitzetting. Integratie is verplicht, met taaltoetsen en werk binnen een jaar. Gezinshereniging wordt beperkt tot kerngezinnen. De partij wil EU-deals met derde landen (bijv. Turkije) uitbreiden om migratiestromen te stoppen. Dit beleid balanceert humanitaire plichten met draagkracht, maar legt sterke nadruk op controle en assimilatie.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Asielquotum is juridisch lastig binnen EU-recht en Vluchtelingenverdrag. EU-deals zijn haalbaar maar kosten tijd en geld. Snelle integratie vereist forse investeringen in taal en werk. Grensbewaking is deels mogelijk binnen Schengen. Kritiek: VVD’s focus op controle kan humane verplichtingen ondermijnen en integratie bemoeilijken door strenge eisen.',
                    'costs' => '€500-800 miljoen voor grensbewaking en uitzetting, €1 miljard voor integratie',
                    'timeline' => '2-4 jaar voor EU-deals, 3-5 jaar voor integratieprogramma’s'
                ]
            ],
            'Klimaat' => [
                'summary' => 'De VVD steunt CO2-reductie maar wil economisch haalbare maatregelen, met focus op innovatie boven hoge belastingen.',
                'details' => 'De VVD wil 55% CO2-reductie in 2030 (EU-doel), maar alleen via innovatie zoals waterstof, CCS en biobrandstoffen. Bedrijven krijgen subsidies voor groene technologie, maar hoge CO2-belastingen worden vermeden. Huishoudens worden ontzien via lagere energiebelasting. Wind- en zonne-energie worden ondersteund, maar kernenergie is cruciaal. De partij wil boeren helpen verduurzamen zonder gedwongen krimp. Dit weerspiegelt een marktgerichte, pragmatische aanpak.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Innovatiesubsidies en kernenergie zijn haalbaar binnen EU-kaders. Lagere belastingen voor huishoudens kunnen snel. Boerensteun is politiek gevoelig maar mogelijk. Kritiek: VVD’s afwijzing van hoge CO2-belastingen kan reductiedoelen vertragen, en afhankelijkheid van innovatie is riskant als technologie achterblijft.',
                    'costs' => '€5-10 miljard voor subsidies, €1-2 miljard voor boerensteun',
                    'timeline' => '2-5 jaar voor subsidies, 10-15 jaar voor kernenergie'
                ]
            ],
            'Zorg' => [
                'summary' => 'De VVD wil eigen risico behouden, maar zorg efficiënter maken via digitalisering en preventie, met kortere wachttijden.',
                'details' => 'Het eigen risico blijft om zorgkosten te beheersen, maar wordt niet verhoogd. Digitalisering (bijv. e-health) en preventieprogramma’s moeten kosten drukken en wachttijden verkorten. De VVD wil meer marktwerking in de zorg, zoals concurrentie tussen ziekenhuizen. Zorgpersoneel krijgt betere arbeidsvoorwaarden, maar geen grootschalige salarisverhoging. Dit beleid prioriteert efficiëntie boven toegankelijkheid.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Digitalisering en preventie zijn breed gedragen en kosten-effectief. Eigen risico behouden is politiek haalbaar, maar stuit op weerstand van links. Marktwerking kan weerstand oproepen bij zorgpersoneel. Kritiek: VVD negeert toegankelijkheidsproblemen voor lage inkomens en risico’s van marktwerking, zoals ongelijke zorgkwaliteit.',
                    'costs' => '€1-2 miljard voor digitalisering, €500-800 miljoen voor preventie',
                    'timeline' => '2-4 jaar voor digitalisering, 3-5 jaar voor wachttijdreductie'
                ]
            ],
            'Energie' => [
                'summary' => 'De VVD ziet kernenergie als sleutel voor stabiele energie, naast wind en zon, en wil energieonafhankelijkheid.',
                'details' => 'De VVD wil vier kerncentrales bouwen tegen 2035 en kleine modulaire reactoren (SMR) ontwikkelen. Offshore windparken en zonne-energie worden uitgebreid, maar kernenergie is prioriteit voor stabiliteit. Gaswinning in Groningen stopt, maar nieuwe gascentrales zijn mogelijk. Energieonafhankelijkheid vermindert afhankelijkheid van Rusland en Qatar. Dit beleid combineert duurzaamheid met pragmatisme.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrales kosten 10-15 jaar en €40-60 miljard, met publieke en politieke weerstand. SMR is nog niet marktrijp. Wind- en zonne-uitbreiding is haalbaar binnen 5-7 jaar. Kritiek: VVD overschat snelheid van kernenergie en negeert kostenrisico’s en veiligheidszorgen.',
                    'costs' => '€40-60 miljard voor kerncentrales, €5-10 miljard voor wind/zon',
                    'timeline' => '10-15 jaar voor kerncentrales, 5-7 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'De VVD wil lagere belastingen, minder bureaucratie en een aantrekkelijk vestigingsklimaat voor bedrijven.',
                'details' => 'De VVD pleit voor verlaging van vennootschapsbelasting en lastenverlichting voor mkb. Bureaucratie wordt verminderd door vergunningen te versnellen. Innovatie krijgt €2-3 miljard aan subsidies, vooral voor tech en startups. Nederland moet aantrekkelijk blijven voor multinationals via belastingvoordelen. Dit weerspiegelt een pro-marktvisie met focus op groei.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Belastingverlaging en subsidies zijn mogelijk binnen 2-3 jaar, maar kosten €5-7 miljard. Bureaucratievermindering is haalbaar maar vereist bestuurlijke hervormingen. Kritiek: VVD’s focus op multinationals kan mkb benadelen en ongelijkheid vergroten.',
                    'costs' => '€5-7 miljard voor belastingverlaging, €2-3 miljard voor subsidies',
                    'timeline' => '2-3 jaar voor belastingwijzigingen, 3-5 jaar voor bureaucratie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'De VVD wil meer autonomie voor scholen, focus op bèta/techniek en kansengelijkheid via excellentie.',
                'details' => 'Scholen krijgen meer vrijheid in curriculum en budget. Bèta- en techniekonderwijs krijgt €1 miljard extra, met focus op digitale vaardigheden. Kansengelijkheid wordt bevorderd via talentprogramma’s, niet via nivellering. Leraren krijgen betere training, maar geen forse salarisverhoging. Dit weerspiegelt een meritocratische visie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Autonomie en bèta-investeringen zijn haalbaar binnen 3-5 jaar. Talentprogramma’s zijn politiek breed gedragen. Kritiek: VVD’s focus op excellentie kan achterstandsgroepen benadelen en lerarentekort negeren.',
                    'costs' => '€1-2 miljard voor bèta/techniek, €500 miljoen voor training',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'De VVD wil woningbouw versnellen via marktwerking en minder regels, met focus op starters en middeninkomens.',
                'details' => 'De VVD wil 100.000 woningen per jaar bouwen door private investeerders te stimuleren en vergunningen te versoepelen. Starters krijgen hypotheeksteun, en middeninkomens toegang tot middenhuur. Sociale huur blijft beperkt om marktwerking te bevorderen. Dit beleid prioriteert economische groei boven sociale gelijkheid.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw is haalbaar maar kost 5-10 jaar door grondtekort en NIMBY-weerstand. Hypotheeksteun kan binnen 2-3 jaar. Kritiek: VVD’s marktaanpak kan betaalbaarheid ondermijnen en sociale huur tekortschieten.',
                    'costs' => '€10-20 miljard voor woningbouw, €1-2 miljard voor hypotheeksteun',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor hypotheeksteun'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'De VVD wil meer politie, betere cybersecurity en een harde aanpak van georganiseerde misdaad.',
                'details' => 'De VVD wil 5.000 extra agenten, strengere straffen voor drugscriminaliteit en €1 miljard voor cybersecurity. Georganiseerde misdaad wordt aangepakt via internationale samenwerking. Preventie krijgt minder nadruk. Dit weerspiegelt een repressieve aanpak.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie-uitbreiding en cybersecurity zijn haalbaar binnen 3-5 jaar. Internationale samenwerking is effectief maar tijdrovend. Kritiek: VVD negeert sociale oorzaken van criminaliteit en effectiviteit van preventie.',
                    'costs' => '€2-3 miljard voor politie, €1-2 miljard voor cybersecurity',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 24,
        'color' => '#FF6B35'
    ],
    'NSC' => [
        'name' => 'Nieuw Sociaal Contract',
        'leader' => 'Pieter Omtzigt',
        'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
        'description' => 'Nieuw Sociaal Contract (NSC) onder leiding van Pieter Omtzigt streeft naar transparant bestuur en een rechtvaardige samenleving. De partij richt zich op hervorming van instituties en herstel van vertrouwen in de overheid.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'NSC wil een gecontroleerd asielbeleid met snelle integratie en respect voor humanitaire verplichtingen.',
                'details' => 'NSC pleit voor een jaarlijks asielplafond, snelle screening van asielaanvragen en verplichte integratie binnen twee jaar (taal, werk). Illegale migratie wordt bestreden met EU-samenwerking. Opvang is kleinschalig en regionaal verspreid. Dit beleid balanceert draagkracht en menselijkheid.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Asielplafond is juridisch complex binnen EU-recht. Snelle integratie vereist forse investeringen. EU-samenwerking is haalbaar maar tijdrovend. Kleinschalige opvang kan binnen 2-3 jaar.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '2-4 jaar voor opvang, 3-5 jaar voor integratie'
                ]
            ],
            'Klimaat' => [
                'summary' => 'NSC wil realistische klimaatdoelen met balans tussen ecologie en economie, en focus op innovatie.',
                'details' => 'NSC steunt 50% CO2-reductie in 2030 via waterstof, CCS en energiebesparing. Boeren en mkb krijgen steun om te verduurzamen. Hoge belastingen worden vermeden, en huishoudens krijgen energiecompensatie. Dit weerspiegelt een pragmatische aanpak.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Innovatie en boerensteun zijn haalbaar binnen 3-5 jaar. Energiecompensatie kan snel. Kritiek: 50% reductie is minder ambitieus dan EU-doelen, wat spanningen kan opleveren.',
                    'costs' => '€3-5 miljard voor innovatie, €1-2 miljard voor compensatie',
                    'timeline' => '2-5 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'NSC wil eigen risico aanpassen en investeren in preventie en zorgpersoneel.',
                'details' => 'Het eigen risico wordt verlaagd voor chronisch zieken en lage inkomens. Preventie krijgt €1 miljard, en zorgpersoneel betere salarissen en minder administratie. Wachttijden worden verkort via regionale samenwerking. Dit beleid richt zich op toegankelijkheid.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen kost €1-2 miljard, haalbaar met coalitiesteun. Preventie en salarissen zijn breed gedragen. Kritiek: structurele personeelstekorten vereisen langetermijnoplossingen.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor personeel',
                    'timeline' => '2-3 jaar voor eigen risico, 5-7 jaar voor personeel'
                ]
            ],
            'Energie' => [
                'summary' => 'NSC steunt kernenergie binnen een duurzame energiemix, met focus op veiligheid en betaalbaarheid.',
                'details' => 'NSC wil één kerncentrale en onderzoek naar SMR. Wind, zon en waterstof krijgen €3-5 miljard. Energieprijzen worden gestabiliseerd via subsidies voor lage inkomens. Dit weerspiegelt een brede aanpak.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrale kost 10-12 jaar en €10-15 miljard. Wind/zon is haalbaar binnen 5-7 jaar. Subsidies zijn snel implementeerbaar. Kritiek: kernenergie kent publieke weerstand.',
                    'costs' => '€10-15 miljard voor kerncentrale, €3-5 miljard voor wind/zon',
                    'timeline' => '10-12 jaar voor kerncentrale, 3-5 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'NSC wil een eerlijke economie met focus op mkb, regionale ontwikkeling en transparantie.',
                'details' => 'NSC pleit voor €2-3 miljard steun voor mkb en regionale economieën. Belastingen worden eerlijker via hogere heffing op multinationals. Bureaucratie wordt verminderd via digitale overheid. Dit weerspiegelt goed bestuur.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mkb-steun en digitalisering zijn haalbaar binnen 3-5 jaar. Belastinghervorming kan weerstand oproepen bij bedrijven. Kritiek: regionale focus kan grote steden benadelen.',
                    'costs' => '€2-3 miljard voor mkb, €1-2 miljard voor digitalisering',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'NSC wil kansengelijkheid, meer leraren en een modern curriculum.',
                'details' => 'NSC investeert €2-3 miljard in leraren en achterstandsscholen. Het curriculum richt zich op digitale vaardigheden en arbeidsmarkt. Kansengelijkheid krijgt prioriteit via extra steun voor kansarme leerlingen.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort oplossen duurt langer door opleidingsduur.',
                    'costs' => '€2-3 miljard voor leraren en scholen',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'NSC wil betaalbare woningen via meer bouw en regulering van huur.',
                'details' => 'NSC wil 80.000 woningen per jaar bouwen, met focus op starters. Huurprijzen worden gereguleerd, en sociale huur uitgebreid. Regionale verschillen krijgen aandacht.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar door grondtekort. Huurregulering is haalbaar binnen 2-3 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€8-15 miljard voor woningbouw, €500 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'NSC wil meer politie, preventie en burgerrechten beschermen.',
                'details' => 'NSC wil 3.000 extra agenten, €1 miljard voor preventie en betere rechtspraak. Burgerrechten worden gewaarborgd via transparante politie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: preventie-effecten duren lang.',
                    'costs' => '€1-2 miljard voor politie, €1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 20,
        'color' => '#059669'
    ],
    'BBB' => [
        'name' => 'BoerBurgerBeweging',
        'leader' => 'Caroline van der Plas',
        'logo' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
        'description' => 'De BoerBurgerBeweging (BBB) onder leiding van Caroline van der Plas verdedigt plattelandswaarden en de agrarische sector, met een pragmatische visie op duurzaamheid.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'BBB wil een streng asielbeleid en betere integratie in plattelandsgemeenten.',
                'details' => 'BBB pleit voor een asielstop behalve voor oorlogsvluchtelingen en snelle uitzetting van illegalen. Integratie richt zich op lokale tradities en werk. Opvang is kleinschalig.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Asielstop is juridisch complex. Kleinschalige opvang is haalbaar maar kost tijd. Kritiek: integratie in kleine gemeenten kan spanningen opleveren.',
                    'costs' => '€500-800 miljoen voor opvang, €300-500 miljoen voor integratie',
                    'timeline' => '2-4 jaar voor opvang, 3-5 jaar voor integratie'
                ]
            ],
            'Klimaat' => [
                'summary' => 'BBB is sceptisch over strenge klimaatmaatregelen en wil boeren ontzien.',
                'details' => 'BBB steunt 40% CO2-reductie in 2030, maar alleen via innovatie en boerensteun. Hoge belastingen worden vermeden, en windmolens beperkt tot zee.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Boerensteun is haalbaar, maar 40% reductie is onder EU-doelen. Windmolenbeperking kan juridisch lastig zijn.',
                    'costs' => '€2-3 miljard voor boerensteun, €1-2 miljard voor innovatie',
                    'timeline' => '2-5 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'BBB wil eigen risico verlagen en meer zorgfaciliteiten op platteland.',
                'details' => 'Het eigen risico wordt gehalveerd, en €1-2 miljard gaat naar plattelandsziekenhuizen en mantelzorg. Personeel krijgt betere voorwaarden.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico halveren kost €1,5-2 miljard, haalbaar met steun. Plattelandszorg is mogelijk maar tijdrovend. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1,5-2 miljard voor eigen risico, €1-2 miljard voor zorgfaciliteiten',
                    'timeline' => '2-3 jaar voor eigen risico, 5-7 jaar voor zorgfaciliteiten'
                ]
            ],
            'Energie' => [
                'summary' => 'BBB steunt kernenergie en een mix van duurzame energiebronnen.',
                'details' => 'BBB wil één kerncentrale en €2-3 miljard voor zon en wind op geschikte locaties. Energieprijzen worden gestabiliseerd via subsidies.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrale kost 10-12 jaar. Zon/wind is haalbaar binnen 5-7 jaar. Kritiek: publieke acceptatie van kernenergie is onzeker.',
                    'costs' => '€10-15 miljard voor kerncentrale, €2-3 miljard voor zon/wind',
                    'timeline' => '10-12 jaar voor kerncentrale, 3-5 jaar voor zon/wind'
                ]
            ],
            'Economie' => [
                'summary' => 'BBB wil plattelandseconomie versterken met steun voor boeren en mkb.',
                'details' => 'BBB investeert €2-3 miljard in boeren en lokale ondernemers. Bureaucratie wordt verminderd, en voedselzekerheid krijgt prioriteit.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Boerensteun en bureaucratievermindering zijn haalbaar binnen 3-5 jaar. Kritiek: focus op platteland kan stedelijke economieën verwaarlozen.',
                    'costs' => '€2-3 miljard voor steun, €500-800 miljoen voor bureaucratie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'BBB wil praktijkgericht onderwijs en meer plattelandscholen.',
                'details' => 'BBB investeert €1-2 miljard in mbo en plattelandscholen. Het curriculum richt zich op landbouw en voedselproductie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mbo-investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: focus op landbouw kan bredere arbeidsmarktbehoeften negeren.',
                    'costs' => '€1-2 miljard voor mbo en plattelandscholen',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'BBB wil meer woningen op platteland en dorpskernen behouden.',
                'details' => 'BBB wil 50.000 woningen per jaar bouwen op platteland, met focus op starters. Huurprijzen worden gereguleerd.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar door grondtekort. Huurregulering is haalbaar binnen 2-3 jaar. Kritiek: focus op platteland kan stedelijke noden negeren.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'BBB wil meer politie op platteland en boeren beschermen.',
                'details' => 'BBB wil 2.000 extra agenten en €500-800 miljoen voor boerenbescherming tegen diefstal. Preventie richt zich op plattelandsgemeenten.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie-uitbreiding is haalbaar binnen 3-5 jaar. Boerenbescherming is specifiek maar mogelijk. Kritiek: focus op platteland kan stedelijke criminaliteit negeren.',
                    'costs' => '€1-2 miljard voor politie, €500-800 miljoen voor boerenbescherming',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 7,
        'color' => '#16A34A'
    ],
    'GL-PvdA' => [
        'name' => 'GroenLinks-PvdA',
        'leader' => 'Frans Timmermans',
        'logo' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
        'description' => 'GroenLinks-PvdA, onder leiding van Frans Timmermans, is een progressieve alliantie die inzet op sociale rechtvaardigheid, duurzaamheid en gelijke kansen.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'GL-PvdA pleit voor een humaan asielbeleid met legale routes en Europese samenwerking.',
                'details' => 'GL-PvdA wil een EU-asielbeleid met verplichte herverdeling van vluchtelingen. Legale migratiekanalen moeten smokkel tegengaan. Nederland neemt 10.000 extra vluchtelingen per jaar op, met €2-3 miljard voor opvang en integratie. Gezinshereniging wordt versoepeld, en kinderen krijgen prioriteit. Een regularisatieregeling komt voor langdurig verblijvenden zonder papieren. Dit weerspiegelt een inclusieve visie.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'EU-herverdeling vereist brede steun, wat politiek lastig is. Opvanguitbreiding is haalbaar maar kostbaar. Regularisatie is mogelijk maar controversieel. Kritiek: extra opvang kan draagvlak in samenleving ondermijnen.',
                    'costs' => '€2-4 miljard voor opvang en integratie, EU-subsidies mogelijk',
                    'timeline' => '2-3 jaar voor opvang, 5-10 jaar voor EU-samenwerking'
                ]
            ],
            'Klimaat' => [
                'summary' => 'GL-PvdA wil ambitieuze CO2-reductie en snelle transitie naar hernieuwbare energie.',
                'details' => 'GL-PvdA steunt 65% CO2-reductie in 2030 via wind, zon en waterstof. Fossiele subsidies stoppen, en vervuilers betalen hogere belastingen. Huishoudens krijgen €2-3 miljard compensatie. Boeren worden geholpen met duurzame transitie.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Wind/zon-uitbreiding is haalbaar binnen 5-7 jaar. Fossiele subsidie-stop kan snel. Hoge belastingen zijn politiek gevoelig. Kritiek: kosten voor huishoudens kunnen weerstand oproepen.',
                    'costs' => '€10-15 miljard voor hernieuwbare energie, €2-3 miljard voor compensatie',
                    'timeline' => '3-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'GL-PvdA wil eigen risico afschaffen en investeren in zorgpersoneel en preventie.',
                'details' => 'Het eigen risico wordt geschrapt (€2,5-3 miljard). Zorgpersoneel krijgt hogere salarissen en minder administratie. Preventie en mentale zorg krijgen €1-2 miljard. Dit richt zich op toegankelijkheid en kwaliteit.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico afschaffen is haalbaar met coalitiesteun. Personeelsinvesteringen kosten tijd. Kritiek: financiering vereist belastingverhoging.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €1-2 miljard voor personeel',
                    'timeline' => '1-2 jaar voor eigen risico, 5-7 jaar voor personeel'
                ]
            ],
            'Energie' => [
                'summary' => 'GL-PvdA is tegen kernenergie en wil volledig duurzame energie.',
                'details' => 'GL-PvdA investeert €15-20 miljard in wind, zon en waterstof. Fossiele brandstoffen worden uitgefaseerd tegen 2040. Energieprijzen worden gestabiliseerd via subsidies.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-10 jaar. Fossiele uitfasering is ambitieus maar mogelijk met EU-steun. Kritiek: afhankelijkheid van import blijft risico.',
                    'costs' => '€15-20 miljard voor hernieuwbare energie, €2-3 miljard voor subsidies',
                    'timeline' => '5-10 jaar voor implementatie'
                ]
            ],
            'Economie' => [
                'summary' => 'GL-PvdA wil eerlijkere welvaart via hogere belastingen voor rijken en multinationals.',
                'details' => 'Topinkomens en multinationals betalen 5-10% meer belasting. €5-7 miljard gaat naar publieke sector en arbeidsvoorwaarden. Flexwerk wordt beperkt.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Belastingverhoging is haalbaar maar kan bedrijven afschrikken. Publieke investeringen zijn mogelijk. Kritiek: economische groei kan afnemen.',
                    'costs' => '€5-7 miljard voor publieke sector',
                    'timeline' => '2-4 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'GL-PvdA wil gelijke kansen en inclusief onderwijs.',
                'details' => 'GL-PvdA investeert €3-5 miljard in achterstandsscholen en studiekostenverlaging. Het curriculum benadrukt diversiteit en duurzaamheid.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort blijft een uitdaging.',
                    'costs' => '€3-5 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'GL-PvdA wil meer sociale huur en strengere huurregulering.',
                'details' => 'GL-PvdA wil 50.000 sociale huurwoningen per jaar en huurprijsbevriezing. Beleggers worden beperkt via belastingen.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Huurregulering is haalbaar binnen 2-3 jaar. Woningbouw kost 5-10 jaar. Kritiek: beleggersbeperking kan investeringen remmen.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'GL-PvdA wil criminaliteit aanpakken via preventie en sociale oorzaken.',
                'details' => 'GL-PvdA investeert €2-3 miljard in preventie en armoedebestrijding. Politie krijgt training tegen discriminatie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Preventie is haalbaar binnen 3-5 jaar. Kritiek: effecten duren lang, en repressieve aanpak ontbreekt.',
                    'costs' => '€2-3 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 25,
        'color' => '#DC2626'
    ],
    'D66' => [
        'name' => 'Democraten 66',
        'leader' => 'Rob Jetten',
        'logo' => 'https://logo.clearbit.com/d66.nl',
        'description' => 'D66, onder leiding van Rob Jetten, is een progressief-liberale partij die inzet op onderwijs, innovatie en een inclusieve samenleving.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'D66 wil een humaan asielbeleid met legale routes en EU-samenwerking.',
                'details' => 'D66 pleit voor veilige migratiekanalen en EU-herverdeling van asielzoekers. Integratie krijgt €1-2 miljard voor taal en werk. Opvang is humaan en kleinschalig.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'EU-herverdeling is politiek complex. Integratie-investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '3-5 jaar voor integratie, 5-10 jaar voor EU-samenwerking'
                ]
            ],
            'Klimaat' => [
                'summary' => 'D66 wil leidende rol in klimaattransitie met CO2-reductie en circulaire economie.',
                'details' => 'D66 steunt 60% CO2-reductie in 2030 via wind, zon en circulaire innovatie. €10-15 miljard gaat naar groene technologie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon en innovatie zijn haalbaar binnen 5-7 jaar. Kritiek: hoge kosten kunnen weerstand oproepen.',
                    'costs' => '€10-15 miljard voor groene technologie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'D66 wil eigen risico bevriezen en investeren in preventie en digitalisering.',
                'details' => 'Het eigen risico krijgt een maximum per behandeling. €1-2 miljard gaat naar e-health en preventie. Personeel krijgt training.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico aanpassen is haalbaar binnen 2-3 jaar. Digitalisering is breed gedragen. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1-2 miljard voor digitalisering, €500-800 miljoen voor preventie',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor digitalisering'
                ]
            ],
            'Energie' => [
                'summary' => 'D66 is kritisch op kernenergie en wil hernieuwbare energiebronnen.',
                'details' => 'D66 investeert €10-12 miljard in wind, zon en waterstof. Fossiele brandstoffen worden uitgefaseerd tegen 2040.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Fossiele uitfasering is ambitieus maar mogelijk. Kritiek: afhankelijkheid van import blijft risico.',
                    'costs' => '€10-12 miljard voor hernieuwbare energie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Economie' => [
                'summary' => 'D66 wil innovatie stimuleren en een open economie.',
                'details' => 'D66 investeert €3-5 miljard in startups en technologie. Multinationals betalen eerlijkere belastingen.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Belastinghervorming kan weerstand oproepen. Kritiek: mkb kan minder profiteren.',
                    'costs' => '€3-5 miljard voor innovatie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'D66 wil investeren in onderwijs en digitale vaardigheden.',
                'details' => 'D66 investeert €3-5 miljard in digitalisering en wetenschap. Kansengelijkheid krijgt prioriteit via extra steun.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort blijft een uitdaging.',
                    'costs' => '€3-5 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'D66 wil duurzame woningbouw en flexibele huurmarkt.',
                'details' => 'D66 wil 80.000 woningen per jaar, met focus op duurzaamheid. Huurmarkt wordt flexibeler via deregulering.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Deregulering is haalbaar binnen 2-3 jaar. Kritiek: flexibiliteit kan huurders schaden.',
                    'costs' => '€8-15 miljard voor woningbouw, €500-800 miljoen voor deregulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor deregulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'D66 wil moderne veiligheidsdiensten en cybersecurity.',
                'details' => 'D66 investeert €1-2 miljard in cybersecurity en preventie. Politie krijgt training voor burgerrechten.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Cybersecurity en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: repressieve aanpak ontbreekt.',
                    'costs' => '€1-2 miljard voor cybersecurity',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 9,
        'color' => '#7C3AED'
    ],
    'SP' => [
        'name' => 'Socialistische Partij',
        'leader' => 'Jimmy Dijk',
        'logo' => 'https://logo.clearbit.com/sp.nl',
        'description' => 'De SP, onder leiding van Jimmy Dijk, is een linkse partij die strijdt tegen ongelijkheid en voor een sterke verzorgingsstaat.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'SP wil humaan asielbeleid en aanpak van mondiale migratie-oorzaken.',
                'details' => 'SP pleit voor betere opvang en integratie, met €1-2 miljard voor taal en werk. Mondiale ongelijkheid wordt aangepakt via ontwikkelingshulp.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Opvang en integratie zijn haalbaar binnen 3-5 jaar. Mondiale aanpak is langetermijn. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '3-5 jaar voor integratie, 5-10 jaar voor mondiale aanpak'
                ]
            ],
            'Klimaat' => [
                'summary' => 'SP wil eerlijke klimaattransitie met bijdrage van rijken.',
                'details' => 'SP steunt 55% CO2-reductie in 2030 via wind en zon. Grote bedrijven betalen hogere belastingen, en huishoudens krijgen compensatie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon en compensatie zijn haalbaar binnen 5-7 jaar. Belastingverhoging is politiek gevoelig. Kritiek: economische groei kan afnemen.',
                    'costs' => '€5-10 miljard voor hernieuwbare energie, €1-2 miljard voor compensatie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'SP wil eigen risico afschaffen en nationaal zorgfonds.',
                'details' => 'Het eigen risico wordt geschrapt (€2,5-3 miljard). Een nationaal zorgfonds vervangt verzekeraars. Personeel krijgt hogere salarissen.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Eigen risico afschaffen is haalbaar. Zorgfonds vereist grote hervorming en weerstand van verzekeraars. Kritiek: financiering is uitdagend.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €5-10 miljard voor zorgfonds',
                    'timeline' => '1-2 jaar voor eigen risico, 5-10 jaar voor zorgfonds'
                ]
            ],
            'Energie' => [
                'summary' => 'SP is tegen kernenergie en wil publieke duurzame energie.',
                'details' => 'SP investeert €10-15 miljard in wind en zon. Energievoorziening wordt genationaliseerd. Fossiele brandstoffen stoppen tegen 2040.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Nationalisering is politiek controversieel. Kritiek: kosten en haalbaarheid zijn onzeker.',
                    'costs' => '€10-15 miljard voor hernieuwbare energie, €5-10 miljard voor nationalisering',
                    'timeline' => '5-7 jaar voor wind/zon, 10-15 jaar voor nationalisering'
                ]
            ],
            'Economie' => [
                'summary' => 'SP wil ongelijkheid bestrijden via hogere belastingen en arbeidsrechten.',
                'details' => 'Topinkomens betalen 10% meer belasting. €5-7 miljard gaat naar publieke sector en flexwerkbeperking.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Belastingverhoging is haalbaar maar kan bedrijven afschrikken. Publieke investeringen zijn mogelijk. Kritiek: economische groei kan afnemen.',
                    'costs' => '€5-7 miljard voor publieke sector',
                    'timeline' => '2-4 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'SP wil gratis onderwijs en kleinere klassen.',
                'details' => 'SP investeert €5-7 miljard in gratis onderwijs en meer leraren. Klasgroottes worden verkleind.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Gratis onderwijs kost veel en vereist belastingverhoging. Meer leraren is haalbaar binnen 5-7 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€5-7 miljard voor onderwijs',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'SP wil meer sociale huur en huurprijsregulering.',
                'details' => 'SP wil 50.000 sociale huurwoningen per jaar en huurprijsbevriezing. Sociale huurverkoop wordt gestopt.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Huurregulering is haalbaar binnen 2-3 jaar. Woningbouw kost 5-10 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'SP wil criminaliteit aanpakken via sociale oorzaken.',
                'details' => 'SP investeert €2-3 miljard in armoedebestrijding en buurtpreventie. Politie krijgt training tegen discriminatie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Preventie is haalbaar binnen 3-5 jaar. Kritiek: effecten duren lang, en repressieve aanpak ontbreekt.',
                    'costs' => '€2-3 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 5,
        'color' => '#EF4444'
    ],
    'PvdD' => [
        'name' => 'Partij voor de Dieren',
        'leader' => 'Esther Ouwehand',
        'logo' => 'https://logo.clearbit.com/partijvoordedieren.nl',
        'description' => 'De PvdD, onder leiding van Esther Ouwehand, richt zich op dierenwelzijn, duurzaamheid en natuurbehoud.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'PvdD wil humaan asielbeleid met focus op ecologische draagkracht.',
                'details' => 'PvdD pleit voor opvang van vluchtelingen, maar rekening houdend met milieu-impact. Mondiale ongelijkheid wordt aangepakt via klimaatbeleid.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Ecologische focus is uniek maar lastig meetbaar. Opvang is haalbaar binnen 2-3 jaar. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€500-800 miljoen voor opvang, €300-500 miljoen voor integratie',
                    'timeline' => '2-3 jaar voor opvang, 5-10 jaar voor mondiale aanpak'
                ]
            ],
            'Klimaat' => [
                'summary' => 'PvdD wil radicaal klimaatbeleid met snelle fossiele afbouw.',
                'details' => 'PvdD steunt 70% CO2-reductie in 2030 via wind, zon en veganisme-promotie. Fossiele brandstoffen stoppen tegen 2035.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Fossiele stop is ambitieus en economisch riskant. Kritiek: veganisme-promotie heeft beperkt draagvlak.',
                    'costs' => '€15-20 miljard voor hernieuwbare energie',
                    'timeline' => '5-7 jaar voor wind/zon, 10-15 jaar voor fossiele stop'
                ]
            ],
            'Zorg' => [
                'summary' => 'PvdD wil eigen risico afschaffen en focus op preventie.',
                'details' => 'Het eigen risico wordt geschrapt (€2,5-3 miljard). €1-2 miljard gaat naar gezonde voeding en mentale zorg.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico afschaffen is haalbaar met coalitiesteun. Preventie is breed gedragen. Kritiek: financiering vereist belastingverhoging.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €1-2 miljard voor preventie',
                    'timeline' => '1-2 jaar voor eigen risico, 3-5 jaar voor preventie'
                ]
            ],
            'Energie' => [
                'summary' => 'PvdD is tegen kernenergie en wil volledig duurzame energie.',
                'details' => 'PvdD investeert €15-20 miljard in wind, zon en geothermische energie. Fossiele brandstoffen stoppen tegen 2035.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Fossiele stop is ambitieus maar mogelijk. Kritiek: afhankelijkheid van import blijft risico.',
                    'costs' => '€15-20 miljard voor hernieuwbare energie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Economie' => [
                'summary' => 'PvdD wil circulaire economie en lokale bedrijven.',
                'details' => 'PvdD investeert €3-5 miljard in circulaire innovatie en lokale mkb. Groeidenken wordt verlaten.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Circulaire innovatie is haalbaar binnen 3-5 jaar. Groeistop is economisch riskant. Kritiek: mkb-steun kan grootbedrijven benadelen.',
                    'costs' => '€3-5 miljard voor innovatie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'PvdD wil duurzaamheid en dierenwelzijn in onderwijs.',
                'details' => 'PvdD investeert €1-2 miljard in ecologisch curriculum en kansengelijkheid.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Curriculumwijziging is haalbaar binnen 2-3 jaar. Kritiek: focus op ecologie kan andere onderwerpen verdringen.',
                    'costs' => '€1-2 miljard voor onderwijs',
                    'timeline' => '2-3 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'PvdD wil duurzame woningbouw en betaalbare woningen.',
                'details' => 'PvdD wil 50.000 duurzame woningen per jaar. Huurprijzen worden gereguleerd.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Huurregulering is haalbaar binnen 2-3 jaar. Kritiek: duurzaamheid kan kosten opdrijven.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'PvdD wil dieren en natuur beschermen en humane criminaliteitsaanpak.',
                'details' => 'PvdD investeert €1-2 miljard in dierenbescherming en sociale preventie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Preventie en dierenbescherming zijn haalbaar binnen 3-5 jaar. Kritiek: repressieve aanpak ontbreekt.',
                    'costs' => '€1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 6,
        'color' => '#10B981'
    ],
    'CDA' => [
        'name' => 'Christen-Democratisch Appèl',
        'leader' => 'Henri Bontenbal',
        'logo' => 'https://logo.clearbit.com/cda.nl',
        'description' => 'Het CDA, onder leiding van Henri Bontenbal, staat voor christendemocratische waarden zoals solidariteit en rentmeesterschap.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'CDA wil tijdelijke asielbescherming en snelle integratie.',
                'details' => 'CDA pleit voor tijdelijke opvang van oorlogsvluchtelingen en snelle uitzetting van illegalen. Integratie krijgt €1-2 miljard voor taal en werk.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Tijdelijke opvang is haalbaar binnen 2-3 jaar. Integratie-investeringen zijn mogelijk. Kritiek: strenge uitzetting kan humane plichten ondermijnen.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '2-3 jaar voor opvang, 3-5 jaar voor integratie'
                ]
            ],
            'Klimaat' => [
                'summary' => 'CDA wil realistische klimaattransitie met economische stabiliteit.',
                'details' => 'CDA steunt 50% CO2-reductie in 2030 via innovatie en boerensteun. €5-7 miljard gaat naar CCS en waterstof.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Innovatie en boerensteun zijn haalbaar binnen 3-5 jaar. Kritiek: 50% reductie is minder ambitieus dan EU-doelen.',
                    'costs' => '€5-7 miljard voor innovatie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'CDA wil eigen risico verlagen en mantelzorg ondersteunen.',
                'details' => 'Het eigen risico wordt verlaagd voor chronisch zieken (€1-2 miljard). Mantelzorg en lokale zorg krijgen €1-2 miljard.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen is haalbaar met coalitiesteun. Lokale zorg is mogelijk. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor mantelzorg',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor mantelzorg'
                ]
            ],
            'Energie' => [
                'summary' => 'CDA steunt kernenergie binnen brede energiemix.',
                'details' => 'CDA wil één kerncentrale en €5-7 miljard voor wind, zon en energiebesparing.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrale kost 10-12 jaar. Wind/zon is haalbaar binnen 5-7 jaar. Kritiek: publieke acceptatie van kernenergie is onzeker.',
                    'costs' => '€10-15 miljard voor kerncentrale, €5-7 miljard voor wind/zon',
                    'timeline' => '10-12 jaar voor kerncentrale, 3-5 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'CDA wil sociale markteconomie met focus op mkb.',
                'details' => 'CDA investeert €2-3 miljard in mkb en regionale economie. Belastingen blijven stabiel.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mkb-steun is haalbaar binnen 3-5 jaar. Kritiek: regionale focus kan grote steden benadelen.',
                    'costs' => '€2-3 miljard voor mkb',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'CDA wil waardegedreven onderwijs en keuzevrijheid.',
                'details' => 'CDA investeert €1-2 miljard in leraren en praktisch onderwijs. Waarden zoals respect krijgen nadruk.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort blijft een uitdaging.',
                    'costs' => '€1-2 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'CDA wil betaalbare woningen voor gezinnen.',
                'details' => 'CDA wil 80.000 woningen per jaar, met focus op gezinnen. Sociale huur wordt uitgebreid.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Sociale huur is haalbaar binnen 3-5 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€8-15 miljard voor woningbouw, €500-800 miljoen voor sociale huur',
                    'timeline' => '5-10 jaar voor woningbouw, 3-5 jaar voor sociale huur'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'CDA wil zichtbare politie en preventie.',
                'details' => 'CDA wil 3.000 extra agenten en €1-2 miljard voor preventie en rechtspraak.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: effecten van preventie duren lang.',
                    'costs' => '€1-2 miljard voor politie, €1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 5,
        'color' => '#F59E0B'
    ],
    'FvD' => [
        'name' => 'Forum voor Democratie',
        'leader' => 'Thierry Baudet',
        'logo' => 'https://logo.clearbit.com/fvd.nl',
        'description' => 'Forum voor Democratie (FvD), onder leiding van Thierry Baudet, is een conservatieve, nationalistische partij die directe democratie, nationale soevereiniteit en een kritische houding tegenover globalisering en de EU voorstaat.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'FvD wil een extreem streng immigratiebeleid met asielstop en remigratie van niet-geïntegreerde migranten.',
                'details' => 'FvD pleit voor een volledige stop op asielaanvragen, sluiting van asielcentra en actieve remigratie van niet-geïntegreerde migranten via financiële prikkels. Grenscontroles worden heringevoerd, en gezinshereniging wordt vrijwel onmogelijk gemaakt. Arbeidsmigratie is beperkt tot hoogopgeleiden met tijdelijke visa. De partij ziet immigratie als een bedreiging voor de Nederlandse cultuur, met nadruk op assimilatie en nationale identiteit.',
                'feasibility' => [
                    'score' => 'Zeer moeilijk',
                    'explanation' => 'Een asielstop en remigratie zijn juridisch vrijwel onhaalbaar binnen EU-recht en internationale verdragen (EVRM, Vluchtelingenverdrag). Grenscontroles schenden Schengen-akkoorden. Remigratieplannen stuiten op ethische en praktische bezwaren, met beperkt draagvlak. Kritiek: FvD overschat de uitvoerbaarheid en negeert economische afhankelijkheid van migranten in sectoren zoals landbouw en technologie.',
                    'costs' => '€1-2 miljard voor grensbewaking en remigratie, besparingen op opvang (€300-500 miljoen), juridische kosten hoog',
                    'timeline' => '3-7 jaar voor gedeeltelijke implementatie, 10+ jaar voor verdragswijzigingen'
                ]
            ],
            'Klimaat' => [
                'summary' => 'FvD is zeer sceptisch over klimaatbeleid en ziet maatregelen als economisch schadelijk.',
                'details' => 'FvD wil alle klimaatdoelen schrappen, inclusief het Klimaatakkoord van Parijs. Subsidies voor wind- en zonne-energie worden gestopt, en fossiele brandstoffen blijven prioriteit. CO2 wordt niet gezien als hoofdprobleem; de partij richt zich op adaptatie (bijv. waterbeheer). Windmolens worden verwijderd, en energiebelastingen verlaagd. Dit weerspiegelt afwijzing van klimaatwetenschap en focus op economische groei.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Subsidies stoppen en belastingen verlagen kan binnen 1-2 jaar, maar botst met EU-verplichtingen, wat boetes oplevert. Windmolenverwijdering is juridisch complex door contracten. Kritiek: FvD negeert lange-termijnrisico’s van klimaatverandering en internationale sancties.',
                    'costs' => '€2-4 miljard inkomstenderving door belastingverlaging, €1-2 miljard voor windmolenverwijdering, EU-boetes €500 miljoen+',
                    'timeline' => '1-2 jaar voor belastingwijzigingen, 5-7 jaar voor energie-aanpassingen'
                ]
            ],
            'Zorg' => [
                'summary' => 'FvD wil eigen risico afschaffen en bureaucratie in de zorg verminderen.',
                'details' => 'Het eigen risico wordt geschrapt om zorg toegankelijker te maken (€2,5-3 miljard). Managementlagen worden verwijderd, en zorgverleners krijgen meer autonomie. Zorgpersoneel krijgt betere salarissen, maar buitenlandse werving wordt beperkt. De focus ligt op directe zorg, niet op preventie of digitalisering. Dit weerspiegelt een anti-bureaucratische houding.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Eigen risico afschaffen is haalbaar met coalitiesteun, maar vereist belastingverhoging elders. Bureaucratievermindering is mogelijk, maar beperking van buitenlands personeel verergert tekorten. Kritiek: FvD onderschat afhankelijkheid van buitenlandse zorgverleners en complexiteit van zorgsysteem.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €500-800 miljoen voor bureaucratievermindering',
                    'timeline' => '1-2 jaar voor eigen risico, 3-5 jaar voor bureaucratie'
                ]
            ],
            'Energie' => [
                'summary' => 'FvD steunt kernenergie en fossiele brandstoffen boven groene energie.',
                'details' => 'FvD wil twee kerncentrales bouwen en onderzoek naar SMR. Fossiele brandstoffen blijven dominant, en subsidies voor wind/zon worden geschrapt. Energieprijzen worden verlaagd via belastingverlaging. Dit weerspiegelt scepsis over groene transitie.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Kerncentrales kosten 10-15 jaar en €10-20 miljard, met publieke weerstand. Fossiele afhankelijkheid botst met EU-doelen, wat boetes oplevert. Kritiek: FvD negeert duurzaamheidstrends en economische risico’s.',
                    'costs' => '€10-20 miljard voor kerncentrales, €1-2 miljard inkomstenderving',
                    'timeline' => '10-15 jaar voor kerncentrales, 1-2 jaar voor subsidie-stop'
                ]
            ],
            'Economie' => [
                'summary' => 'FvD wil minder EU-regelgeving en lagere belastingen voor nationale belangen.',
                'details' => 'FvD pleit voor verlaging van inkomsten- en vennootschapsbelasting (€5-7 miljard). EU-regelgeving wordt geminimaliseerd, en handelsbarrières met niet-EU-landen verlaagd. Subsidies gaan naar lokale bedrijven. Dit weerspiegelt anti-EU-nationalisme.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Belastingverlaging is mogelijk maar kost €5-7 miljard. EU-regelgeving beperken is lastig binnen lidmaatschap. Kritiek: FvD onderschat economische afhankelijkheid van EU.',
                    'costs' => '€5-7 miljard voor belastingverlaging, juridische kosten bij EU-conflicten',
                    'timeline' => '1-3 jaar voor belastingwijzigingen, 5-10 jaar voor EU-onderhandelingen'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'FvD wil onderwijs gericht op Nederlandse identiteit en klassieke kennis.',
                'details' => 'Het curriculum benadrukt Nederlandse geschiedenis en literatuur, met schrapping van ‘woke’ thema’s. Mbo krijgt €1-2 miljard, en academisch onderwijs wordt selectiever. Leraren krijgen betere salarissen.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Curriculumaanscherping is mogelijk binnen 2-3 jaar, maar botst met onderwijsvrijheid. Mbo-investeringen zijn haalbaar. Kritiek: focus op nationalisme kan inclusiviteit schaden.',
                    'costs' => '€1-2 miljard voor mbo, lage kosten voor curriculum',
                    'timeline' => '2-3 jaar voor curriculum, 3-5 jaar voor mbo'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'FvD wil Nederlanders voorrang bij sociale huur en woningnood aanpakken via migratiebeperking.',
                'details' => 'Nederlanders krijgen prioriteit bij sociale huurwoningen tot 2030. FvD wil 80.000 woningen bouwen door bureaucratie te schrappen. Migratiebeperking moet druk op de woningmarkt verlagen.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Voorrang voor Nederlanders is juridisch riskant (discriminatie). Woningbouw kost 5-10 jaar. Migratiebeperking heeft beperkt effect. Kritiek: FvD oversimplificeert woningnood.',
                    'costs' => '€8-15 miljard voor woningbouw, juridische kosten bij discriminatiezaken',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor huurbeleid'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'FvD wil zerotolerance tegen criminaliteit, vooral gerelateerd aan immigratie.',
                'details' => 'FvD pleit voor minimumstraffen, 5.000 extra agenten en detentiecentra voor illegalen. Overlast door migranten krijgt prioriteit. Preventie krijgt weinig aandacht.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Politie-uitbreiding is mogelijk binnen 3-5 jaar. Detentiecentra zijn juridisch complex. Kritiek: focus op migranten kan discriminatieclaims opleveren.',
                    'costs' => '€1-2 miljard voor politie, €500-800 miljoen voor detentiecentra',
                    'timeline' => '3-5 jaar voor politie, 2-3 jaar voor strafverhoging'
                ]
            ]
        ],
        'current_seats' => 3,
        'color' => '#8B0000'
    ],
    'SGP' => [
        'name' => 'Staatkundig Gereformeerde Partij',
        'leader' => 'Chris Stoffer',
        'logo' => 'https://logo.clearbit.com/sgp.nl',
        'description' => 'De SGP, onder leiding van Chris Stoffer, voert Bijbels-geïnspireerde politiek met nadruk op traditionele waarden, gezinsbeleid en een sobere overheid.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'SGP wil een streng immigratiebeleid met focus op Nederlandse identiteit en humanitaire plichten.',
                'details' => 'SGP pleit voor selectieve asieltoelating, met prioriteit voor christelijke vluchtelingen en snelle uitzetting van illegalen. Integratie richt zich op Nederlandse taal en waarden, met €500-800 miljoen voor taalcursussen en werk. Grenscontroles worden versterkt. Dit weerspiegelt een conservatieve, christelijke visie.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Selectieve toelating is juridisch lastig binnen EU-recht. Integratie-investeringen zijn haalbaar binnen 3-5 jaar. Grenscontroles botsen met Schengen. Kritiek: prioriteit voor christelijke vluchtelingen kan als discriminatoir worden gezien.',
                    'costs' => '€500-800 miljoen voor integratie, €300-500 miljoen voor grensbewaking',
                    'timeline' => '2-3 jaar voor integratie, 3-5 jaar voor grenscontroles'
                ]
            ],
            'Klimaat' => [
                'summary' => 'SGP wil rentmeesterschap met realistische klimaatmaatregelen.',
                'details' => 'SGP steunt 40% CO2-reductie in 2030 via energiebesparing en innovatie (€3-5 miljard). Hoge belastingen worden vermeden, en boeren krijgen steun voor verduurzaming. Windmolens op land worden beperkt.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Energiebesparing en boerensteun zijn haalbaar binnen 3-5 jaar. 40% reductie is minder ambitieus dan EU-doelen. Kritiek: beperkte windmolens kunnen transitie vertragen.',
                    'costs' => '€3-5 miljard voor innovatie, €1-2 miljard voor boerensteun',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'SGP wil eigen risico verlagen en ethische kwesties in zorg aanpakken.',
                'details' => 'Het eigen risico wordt verlaagd voor lage inkomens (€1-2 miljard). €1-2 miljard gaat naar mantelzorg en ethische zorg (bijv. tegen euthanasie). Personeel krijgt betere voorwaarden.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen is haalbaar met coalitiesteun. Ethische focus is politiek gevoelig. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor mantelzorg',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor mantelzorg'
                ]
            ],
            'Energie' => [
                'summary' => 'SGP steunt kernenergie en een gebalanceerde energiemix.',
                'details' => 'SGP wil één kerncentrale en €3-5 miljard voor wind, zon en energiebesparing. Energieprijzen worden gestabiliseerd via subsidies.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrale kost 10-12 jaar. Wind/zon is haalbaar binnen 5-7 jaar. Kritiek: publieke acceptatie van kernenergie is onzeker.',
                    'costs' => '€10-15 miljard voor kerncentrale, €3-5 miljard voor wind/zon',
                    'timeline' => '10-12 jaar voor kerncentrale, 3-5 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'SGP wil sobere economie met focus op gezinnen en mkb.',
                'details' => 'SGP investeert €1-2 miljard in mkb en gezinsondersteuning. Belastingen blijven stabiel, en bureaucratie wordt verminderd.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mkb-steun en bureaucratievermindering zijn haalbaar binnen 3-5 jaar. Kritiek: focus op gezinnen kan bredere economie verwaarlozen.',
                    'costs' => '€1-2 miljard voor mkb en gezinnen',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'SGP wil christelijk onderwijs en traditionele waarden.',
                'details' => 'SGP investeert €1-2 miljard in bijzonder onderwijs en leraren. Het curriculum benadrukt waarden zoals respect en verantwoordelijkheid.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: focus op christelijk onderwijs kan inclusiviteit beperken.',
                    'costs' => '€1-2 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'SGP wil betaalbare woningen en behoud van dorpsstructuren.',
                'details' => 'SGP wil 50.000 woningen per jaar, met focus op gezinnen. Sociale huur wordt uitgebreid zonder natuur aan te tasten.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Sociale huur is haalbaar binnen 3-5 jaar. Kritiek: natuurbehoud kan bouw vertragen.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor sociale huur',
                    'timeline' => '5-10 jaar voor woningbouw, 3-5 jaar voor sociale huur'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'SGP wil harde aanpak van criminaliteit en gezin als basis.',
                'details' => 'SGP wil 2.000 extra agenten en strengere straffen. €1-2 miljard gaat naar preventie via gezin en gemeenschap.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: gezin-focus kan bredere oorzaken negeren.',
                    'costs' => '€1-2 miljard voor politie, €1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 3,
        'color' => '#003087'
    ],
    'Volt' => [
        'name' => 'Volt Nederland',
        'leader' => 'Laurens Dassen',
        'logo' => 'https://logo.clearbit.com/voltnederland.org',
        'description' => 'Volt Nederland, onder leiding van Laurens Dassen, is een pro-Europese partij die inzet op grensoverschrijdende samenwerking, duurzaamheid en democratische vernieuwing.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'Volt wil een humaan, Europees gecoördineerd asielbeleid.',
                'details' => 'Volt pleit voor EU-herverdeling van asielzoekers en legale migratiekanalen. €1-2 miljard gaat naar integratie (taal, werk). Opvang is kleinschalig en humaan. Dit weerspiegelt een pro-EU-visie.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'EU-herverdeling is politiek complex. Integratie-investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '3-5 jaar voor integratie, 5-10 jaar voor EU-samenwerking'
                ]
            ],
            'Klimaat' => [
                'summary' => 'Volt wil een CO2-neutrale economie via Europese samenwerking.',
                'details' => 'Volt steunt 60% CO2-reductie in 2030 via wind, zon en waterstof. €10-15 miljard gaat naar groene technologie en EU-fondsen.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon en innovatie zijn haalbaar binnen 5-7 jaar. EU-fondsen vereisen samenwerking. Kritiek: hoge kosten kunnen weerstand oproepen.',
                    'costs' => '€10-15 miljard voor groene technologie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'Volt wil eigen risico verlagen en Europese zorginnovatie.',
                'details' => 'Het eigen risico wordt verlaagd (€1-2 miljard). €1-2 miljard gaat naar e-health en pandemiepreventie via EU-samenwerking.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen is haalbaar met coalitiesteun. E-health is breed gedragen. Kritiek: EU-samenwerking kan traag zijn.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor e-health',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor e-health'
                ]
            ],
            'Energie' => [
                'summary' => 'Volt wil hernieuwbare energie en een Europese energiemarkt.',
                'details' => 'Volt investeert €10-12 miljard in wind, zon en waterstof. Fossiele brandstoffen stoppen tegen 2040. Een EU-energiemarkt wordt ontwikkeld.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. EU-energiemarkt vereist lange-termijnsamenwerking. Kritiek: afhankelijkheid van import blijft risico.',
                    'costs' => '€10-12 miljard voor hernieuwbare energie',
                    'timeline' => '5-7 jaar voor wind/zon, 10-15 jaar voor EU-markt'
                ]
            ],
            'Economie' => [
                'summary' => 'Volt wil innovatie en eerlijke belastingen via EU-samenwerking.',
                'details' => 'Volt investeert €3-5 miljard in startups en technologie. Multinationals betalen eerlijkere belastingen via EU-regels.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Belastinghervorming vereist EU-steun. Kritiek: mkb kan minder profiteren.',
                    'costs' => '€3-5 miljard voor innovatie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'Volt wil Europees onderwijs en digitale vaardigheden.',
                'details' => 'Volt investeert €3-5 miljard in digitalisering en EU-uitwisseling. Het curriculum benadrukt Europese waarden.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort blijft een uitdaging.',
                    'costs' => '€3-5 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'Volt wil duurzame woningbouw en eerlijke huurmarkt.',
                'details' => 'Volt wil 80.000 duurzame woningen per jaar. Huurmarkt wordt gereguleerd via EU-standaarden.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Huurregulering is haalbaar binnen 2-3 jaar. Kritiek: EU-standaarden kunnen traag zijn.',
                    'costs' => '€8-15 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'Volt wil EU-samenwerking op veiligheid en cybersecurity.',
                'details' => 'Volt investeert €1-2 miljard in cybersecurity en preventie via EU-samenwerking. Politie krijgt training voor burgerrechten.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Cybersecurity en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: EU-samenwerking kan traag zijn.',
                    'costs' => '€1-2 miljard voor cybersecurity',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 2,
        'color' => '#502379'
    ],
    'DENK' => [
        'name' => 'DENK',
        'leader' => 'Stephan van Baarle',
        'logo' => 'https://i.ibb.co/JwdSLG7g/DENK.jpg',
        'description' => 'DENK, onder leiding van Stephan van Baarle, komt op voor diversiteit, gelijke kansen en inclusie, met focus op minderheidsgroepen.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'DENK wil humaan asielbeleid en aanpak van discriminatie.',
                'details' => 'DENK pleit voor betere opvang en €1-2 miljard voor integratie (taal, werk). Discriminatie op arbeids- en woningmarkt wordt bestreden via wetgeving. Gezinshereniging wordt versoepeld.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Opvang en integratie zijn haalbaar binnen 3-5 jaar. Discriminatiewetten zijn mogelijk maar vereisen draagvlak. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '3-5 jaar voor integratie, 2-3 jaar voor opvang'
                ]
            ],
            'Klimaat' => [
                'summary' => 'DENK wil eerlijke klimaattransitie met ontzien van lage inkomens.',
                'details' => 'DENK steunt 50% CO2-reductie in 2030 via wind en zon. €5-7 miljard gaat naar toegankelijke groene oplossingen. Lage inkomens krijgen energiecompensatie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon en compensatie zijn haalbaar binnen 5-7 jaar. Kritiek: 50% reductie is minder ambitieus dan EU-doelen.',
                    'costs' => '€5-7 miljard voor hernieuwbare energie, €1-2 miljard voor compensatie',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'DENK wil eigen risico afschaffen en cultureel sensitieve zorg.',
                'details' => 'Het eigen risico wordt geschrapt (€2,5-3 miljard). €1-2 miljard gaat naar cultureel sensitieve zorg en betere arbeidsvoorwaarden.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico afschaffen is haalbaar met coalitiesteun. Culturele zorg is mogelijk. Kritiek: financiering vereist belastingverhoging.',
                    'costs' => '€2,5-3 miljard voor eigen risico, €1-2 miljard voor zorg',
                    'timeline' => '1-2 jaar voor eigen risico, 3-5 jaar voor zorg'
                ]
            ],
            'Energie' => [
                'summary' => 'DENK wil hernieuwbare energie en betaalbare prijzen.',
                'details' => 'DENK investeert €5-7 miljard in wind en zon, met subsidies voor achterstandswijken. Fossiele brandstoffen stoppen tegen 2040.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Subsidies zijn snel implementeerbaar. Kritiek: afhankelijkheid van import blijft risico.',
                    'costs' => '€5-7 miljard voor hernieuwbare energie, €1-2 miljard voor subsidies',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Economie' => [
                'summary' => 'DENK wil ongelijkheid aanpakken en kleine ondernemers steunen.',
                'details' => 'DENK verhoogt belastingen voor rijken en multinationals. €2-3 miljard gaat naar mkb en inclusieve economie.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Belastingverhoging is haalbaar maar kan bedrijven afschrikken. Mkb-steun is mogelijk. Kritiek: economische groei kan afnemen.',
                    'costs' => '€2-3 miljard voor mkb',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'DENK wil gelijke kansen en inclusief curriculum.',
                'details' => 'DENK investeert €2-3 miljard in achterstandsscholen en diversiteit in het curriculum.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: lerarentekort blijft een uitdaging.',
                    'costs' => '€2-3 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'DENK wil meer sociale huur en aanpak van discriminatie.',
                'details' => 'DENK wil 50.000 sociale huurwoningen per jaar en huurprijsbevriezing. Discriminatie op de woningmarkt wordt bestreden.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Huurregulering is haalbaar binnen 2-3 jaar. Woningbouw kost 5-10 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€5-10 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor regulering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'DENK wil preventie en aanpak van discriminatie door politie.',
                'details' => 'DENK investeert €1-2 miljard in sociale preventie en politietraining tegen discriminatie.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Preventie en training zijn haalbaar binnen 3-5 jaar. Kritiek: repressieve aanpak ontbreekt.',
                    'costs' => '€1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 3,
        'color' => '#00CED1'
    ],
    'CU' => [
        'name' => 'ChristenUnie',
        'leader' => 'Mirjam Bikker',
        'logo' => 'https://logo.clearbit.com/christenunie.nl',
        'description' => 'De ChristenUnie, onder leiding van Mirjam Bikker, is een christelijke partij die inzet op Bijbelse waarden, gerechtigheid en rentmeesterschap.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'CU wil humaan asielbeleid met respect voor draagkracht.',
                'details' => 'CU pleit voor opvang van kwetsbare vluchtelingen en snelle integratie (€1-2 miljard voor taal en werk). Illegale migratie wordt beperkt via EU-samenwerking.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Opvang en integratie zijn haalbaar binnen 3-5 jaar. EU-samenwerking is tijdrovend. Kritiek: draagvlak kan afnemen bij hoge instroom.',
                    'costs' => '€1-2 miljard voor integratie, €500-800 miljoen voor opvang',
                    'timeline' => '3-5 jaar voor integratie, 2-3 jaar voor opvang'
                ]
            ],
            'Klimaat' => [
                'summary' => 'CU wil rentmeesterschap met ambitieuze CO2-reductie.',
                'details' => 'CU steunt 55% CO2-reductie in 2030 via wind, zon en energiebesparing. €5-7 miljard gaat naar groene innovatie en boerensteun.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Wind/zon en boerensteun zijn haalbaar binnen 5-7 jaar. Kritiek: financiering vereist coalitiesteun.',
                    'costs' => '€5-7 miljard voor innovatie, €1-2 miljard voor boerensteun',
                    'timeline' => '5-7 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'CU wil eigen risico verlagen en ethische zorg.',
                'details' => 'Het eigen risico wordt verlaagd voor ouderen en chronisch zieken (€1-2 miljard). €1-2 miljard gaat naar mantelzorg en ethische kwesties.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen is haalbaar met coalitiesteun. Ethische focus is mogelijk. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor mantelzorg',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor mantelzorg'
                ]
            ],
            'Energie' => [
                'summary' => 'CU steunt hernieuwbare energie en is open voor kernenergie.',
                'details' => 'CU investeert €5-7 miljard in wind, zon en waterstof. Eén kerncentrale wordt overwogen. Fossiele brandstoffen stoppen tegen 2040.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Wind/zon is haalbaar binnen 5-7 jaar. Kerncentrale kost 10-12 jaar. Kritiek: publieke acceptatie van kernenergie is onzeker.',
                    'costs' => '€10-15 miljard voor kerncentrale, €5-7 miljard voor wind/zon',
                    'timeline' => '10-12 jaar voor kerncentrale, 5-7 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'CU wil eerlijke economie met focus op gezinnen.',
                'details' => 'CU investeert €2-3 miljard in mkb en sociale zekerheid. Belastingen blijven stabiel.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mkb-steun is haalbaar binnen 3-5 jaar. Kritiek: focus op gezinnen kan bredere economie verwaarlozen.',
                    'costs' => '€2-3 miljard voor mkb en sociale zekerheid',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'CU wil waardegedreven onderwijs en keuzevrijheid.',
                'details' => 'CU investeert €1-2 miljard in leraren en bijzonder onderwijs. Waarden zoals respect krijgen nadruk.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: focus op waarden kan inclusiviteit beperken.',
                    'costs' => '€1-2 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'CU wil betaalbare woningen en leefbare gemeenschappen.',
                'details' => 'CU wil 80.000 woningen per jaar, met focus op gezinnen. Sociale huur wordt uitgebreid.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Sociale huur is haalbaar binnen 3-5 jaar. Kritiek: financiering is uitdagend.',
                    'costs' => '€8-15 miljard voor woningbouw, €500-800 miljoen voor sociale huur',
                    'timeline' => '5-10 jaar voor woningbouw, 3-5 jaar voor sociale huur'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'CU wil rechtvaardige aanpak van criminaliteit en preventie.',
                'details' => 'CU wil 2.000 extra agenten en €1-2 miljard voor preventie via gezin en gemeenschap.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie en preventie zijn haalbaar binnen 3-5 jaar. Kritiek: gezin-focus kan bredere oorzaken negeren.',
                    'costs' => '€1-2 miljard voor politie, €1-2 miljard voor preventie',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 3,
        'color' => '#005F6B'
    ],
    'JA21' => [
        'name' => 'Juiste Antwoord 2021',
        'leader' => 'Joost Eerdmans',
        'logo' => 'https://logo.clearbit.com/ja21.nl',
        'description' => 'JA21, onder leiding van Joost Eerdmans, is een conservatieve partij die nationale soevereiniteit, veiligheid en pragmatisme benadrukt.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'JA21 wil streng immigratiebeleid en remigratie bevorderen.',
                'details' => 'JA21 pleit voor een asielplafond, snelle uitzetting van illegalen en remigratie via financiële prikkels. Integratie richt zich op assimilatie (€500-800 miljoen). Grenscontroles worden versterkt.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Asielplafond is juridisch lastig binnen EU-recht. Remigratie stuit op ethische bezwaren. Grenscontroles botsen met Schengen. Kritiek: JA21 overschat uitvoerbaarheid.',
                    'costs' => '€500-800 miljoen voor integratie, €300-500 miljoen voor grensbewaking',
                    'timeline' => '2-3 jaar voor integratie, 3-5 jaar voor grenscontroles'
                ]
            ],
            'Klimaat' => [
                'summary' => 'JA21 wil pragmatisch klimaatbeleid zonder economische schade.',
                'details' => 'JA21 steunt 40% CO2-reductie in 2030 via innovatie (€3-5 miljard). Hoge belastingen worden vermeden, en boeren krijgen steun.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Innovatie en boerensteun zijn haalbaar binnen 3-5 jaar. 40% reductie is minder ambitieus dan EU-doelen. Kritiek: beperkte ambities kunnen transitie vertragen.',
                    'costs' => '€3-5 miljard voor innovatie, €1-2 miljard voor boerensteun',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Zorg' => [
                'summary' => 'JA21 wil eigen risico verlagen en bureaucratie verminderen.',
                'details' => 'Het eigen risico wordt verlaagd (€1-2 miljard). €1-2 miljard gaat naar directe zorg en minder managementlagen.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Eigen risico verlagen is haalbaar met coalitiesteun. Bureaucratievermindering is mogelijk. Kritiek: personeelstekorten blijven een probleem.',
                    'costs' => '€1-2 miljard voor eigen risico, €1-2 miljard voor bureaucratie',
                    'timeline' => '2-3 jaar voor eigen risico, 3-5 jaar voor bureaucratie'
                ]
            ],
            'Energie' => [
                'summary' => 'JA21 steunt kernenergie en een gebalanceerde energiemix.',
                'details' => 'JA21 wil één kerncentrale en €3-5 miljard voor wind en zon. Fossiele brandstoffen blijven tijdelijk nodig.',
                'feasibility' => [
                    'score' => 'Gedeeltelijk haalbaar',
                    'explanation' => 'Kerncentrale kost 10-12 jaar. Wind/zon is haalbaar binnen 5-7 jaar. Kritiek: publieke acceptatie van kernenergie is onzeker.',
                    'costs' => '€10-15 miljard voor kerncentrale, €3-5 miljard voor wind/zon',
                    'timeline' => '10-12 jaar voor kerncentrale, 5-7 jaar voor wind/zon'
                ]
            ],
            'Economie' => [
                'summary' => 'JA21 wil lagere belastingen en nationale economie versterken.',
                'details' => 'JA21 verlaagt belastingen (€3-5 miljard) en investeert €1-2 miljard in mkb. EU-regelgeving wordt beperkt.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Belastingverlaging en mkb-steun zijn haalbaar binnen 3-5 jaar. EU-regelgeving beperken is lastig. Kritiek: economische afhankelijkheid van EU wordt onderschat.',
                    'costs' => '€3-5 miljard voor belastingverlaging, €1-2 miljard voor mkb',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'JA21 wil Nederlands onderwijs en meer discipline.',
                'details' => 'JA21 investeert €1-2 miljard in mbo en discipline in het curriculum. Nederlandse cultuur krijgt nadruk.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Mbo-investeringen zijn haalbaar binnen 3-5 jaar. Kritiek: focus op nationale cultuur kan inclusiviteit beperken.',
                    'costs' => '€1-2 miljard voor onderwijs',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'JA21 wil Nederlanders voorrang en meer bouwvrijheid.',
                'details' => 'JA21 wil 80.000 woningen per jaar en voorrang voor Nederlanders bij sociale huur. Bureaucratie wordt geschrapt.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Woningbouw kost 5-10 jaar. Voorrang voor Nederlanders is juridisch riskant. Kritiek: migratiebeperking overschat effect op woningnood.',
                    'costs' => '€8-15 miljard voor woningbouw, €500-800 miljoen voor regulering',
                    'timeline' => '5-10 jaar voor woningbouw, 2-3 jaar voor huurbeleid'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'JA21 wil harde aanpak van criminaliteit en drugscriminaliteit.',
                'details' => 'JA21 wil 3.000 extra agenten, strengere straffen en €1-2 miljard voor drugsaanpak.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Politie en strafverhoging zijn haalbaar binnen 3-5 jaar. Kritiek: focus op repressie negeert preventie.',
                    'costs' => '€1-2 miljard voor politie, €1-2 miljard voor drugsaanpak',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ]
        ],
        'current_seats' => 1,
        'color' => '#FFD700'
    ],
    
    'BIJ1' => [
        'name' => 'BIJ1',
        'leader' => 'Sylvana Simons',
        'logo' => 'https://i0.wp.com/www.worldwithoutfossilads.org/wp-content/uploads/2023/08/Bij1-logo.png?fit=2223%2C2223&ssl=1',
        'description' => 'BIJ1 is een politieke partij die strijdt voor radicale gelijkwaardigheid en het doorbreken van systemische ongelijkheid. De partij richt zich op antiracisme, dekolonisatie en het bestrijden van alle vormen van discriminatie. BIJ1 streeft naar een inclusieve samenleving waar iedereen vrij kan zijn.',
        'standpoints' => [
            'Immigratie' => [
                'summary' => 'BIJ1 wil een humaan migratiebeleid en afschaffing van mensonwaardige opvangcentra.',
                'details' => 'BIJ1 pleit voor een fundamenteel andere aanpak van migratie gebaseerd op menselijke waardigheid. De partij wil af van detentiecentra en mensonwaardige opvang, en in plaats daarvan investeren in fatsoenlijke huisvesting. Pushbacks en geweld aan de grenzen moeten stoppen. Nederland moet meer vluchtelingen opvangen en familie-hereniging vergemakkelijken. Het asielsysteem moet worden gedekoloniseerd, zonder onderscheid tussen verschillende groepen vluchtelingen.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Requires substantial changes to current immigration infrastructure and EU coordination. Humane housing for asylum seekers needs significant investment and political support. Decolonizing asylum system faces institutional resistance.',
                    'costs' => '€2-3 miljard voor verbeterde opvang en huisvesting',
                    'timeline' => '3-5 jaar voor structurele veranderingen'
                ]
            ],
            'Klimaat' => [
                'summary' => 'BIJ1 wil klimaatrechtvaardigheid en een groene transitie die iedereen ten goede komt.',
                'details' => 'BIJ1 verbindt klimaatbeleid met sociale rechtvaardigheid. De partij wil een groene transitie waarbij niet de gewone mensen de rekening betalen, maar grote vervuilers en multinationals. Fossiele subsidies moeten worden afgeschaft en geïnvesteerd in groene banen. Klimaatrechtvaardigheid betekent dat gemeenschappen die het zwaarst getroffen worden door vervuiling voorrang krijgen bij verduurzaming.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Green transition with social focus is achievable with proper funding and support. Removing fossil subsidies is politically challenging but financially beneficial long-term.',
                    'costs' => '€3-5 miljard voor groene investeringen, besparingen op fossiele subsidies',
                    'timeline' => '5-10 jaar voor transitie'
                ]
            ],
            'Zorg' => [
                'summary' => 'BIJ1 wil gezondheidszorg als grondrecht zonder winstoogmerk.',
                'details' => 'Gezondheidszorg moet een grondrecht zijn dat verankerd wordt in de Grondwet. BIJ1 wil af van het winstoogmerk in de zorg en het eigen risico afschaffen. Zorgverzekeraars moeten verdwijnen en vervangen worden door een publiek zorgstelsel. Meer investering in preventie en mentale gezondheid, vooral in achterstandswijken. Structureel racisme in de zorg moet worden weggenomen.',
                'feasibility' => [
                    'score' => 'Zeer moeilijk',
                    'explanation' => 'Complete overhaul of healthcare system requires massive political consensus and transition period. Constitutional changes need two-thirds majority. Dismantling insurance system faces strong institutional resistance.',
                    'costs' => '€10-15 miljard voor transitie naar publiek stelsel',
                    'timeline' => '10-15 jaar voor volledige hervorming'
                ]
            ],
            'Energie' => [
                'summary' => 'BIJ1 wil publieke controle over energie en een rechtvaardige energietransitie.',
                'details' => 'Energiebedrijven moeten terug in publieke handen om betaalbare en schone energie te garanderen. BIJ1 wil investeren in hernieuwbare energie met democratische controle. Energiearmoede moet worden bestreden door lagere tarieven voor huishoudens met lage inkomens. De transitie mag niet ten koste gaan van werkgelegenheid - groene banen moeten werknemers in fossiele sectoren opvangen.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Nationalizing energy companies requires substantial compensation and legal procedures. Public energy control is achievable but needs careful transition planning and significant investment.',
                    'costs' => '€20-30 miljard voor nationalisatie energiebedrijven',
                    'timeline' => '7-10 jaar voor publieke controle'
                ]
            ],
            'Economie' => [
                'summary' => 'BIJ1 wil een radicaal andere economie met vermogenslimieten en publieke controle.',
                'details' => 'BIJ1 wil een limiet op vermogen en versoepeling van onteigeningswetgeving. Banken, pensioensfondsen en openbaar vervoer moeten in publieke handen. Private bedrijven betalen forse winstbelasting. Nederland mag geen belastingparadijs meer zijn. Schuldhulporganisaties die geld verdienen aan andermans schulden worden verboden. Het recht op huisvesting, voeding en gezondheidszorg wordt verankerd in de Grondwet.',
                'feasibility' => [
                    'score' => 'Zeer moeilijk',
                    'explanation' => 'Wealth caps and nationalization face constitutional and EU law challenges. Massive economic restructuring requires unprecedented political support and international coordination.',
                    'costs' => '€50-100 miljard voor nationalisatie en compensatie',
                    'timeline' => '15-20 jaar voor volledige herstructurering'
                ]
            ],
            'Onderwijs' => [
                'summary' => 'BIJ1 wil gratis onderwijs voor iedereen en dekolonisatie van het curriculum.',
                'details' => 'Het collegegeld wordt afgeschaft en al het onderwijs wordt gratis. Studieschulden van de pechgeneratie worden volledig kwijtgescholden. Het onderwijs moet worden gedekoloniseerd met meer aandacht voor diverse geschiedenis en perspectieven. Scholen moeten volledig toegankelijk zijn voor leerlingen met een beperking. Betere arbeidsvoorwaarden voor onderwijspersoneel: hogere lonen, lagere werkdruk.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Free education is financially demanding but achievable with progressive taxation. Student debt forgiveness requires substantial one-time investment. Curriculum decolonization faces cultural resistance but is implementable.',
                    'costs' => '€3-4 miljard per jaar voor gratis onderwijs, €15-20 miljard voor schuldenvergeving',
                    'timeline' => '3-5 jaar voor implementatie'
                ]
            ],
            'Woningmarkt' => [
                'summary' => 'BIJ1 wil huisvesting als grondrecht en publieke controle over de woningmarkt.',
                'details' => 'Het recht op huisvesting wordt verankerd in de Grondwet. BIJ1 wil massaal investeren in sociale woningbouw en speculatie op de woningmarkt tegengaan. Huurprijzen moeten worden bevroren en later verlaagd. Woningcorporaties krijgen meer middelen en er komt een limiet op verhuur door particuliere beleggers. Wonen is een recht, geen handelswaar.',
                'feasibility' => [
                    'score' => 'Moeilijk',
                    'explanation' => 'Constitutional right to housing requires constitutional amendment. Market intervention faces property rights challenges. Large-scale social housing construction is achievable but needs massive investment and land availability.',
                    'costs' => '€15-25 miljard voor sociale woningbouw en marktinterventie',
                    'timeline' => '7-10 jaar voor structurele verandering'
                ]
            ],
            'Veiligheid' => [
                'summary' => 'BIJ1 wil preventieve veiligheid en afbouw van het strafrechtsysteem.',
                'details' => 'BIJ1 wil investeren in preventie in plaats van repressie. De oorzaken van criminaliteit (armoede, discriminatie, gebrek aan kansen) moeten worden aangepakt. Het gevangeniswezen moet worden hervormd met focus op restorative justice. Etnisch profileren door politie moet stoppen. Meer investeringen in jeugdwerk, onderwijs en sociale voorzieningen in plaats van meer agenten en gevangenissen.',
                'feasibility' => [
                    'score' => 'Haalbaar',
                    'explanation' => 'Preventive approach is evidence-based and cost-effective long-term. Reducing ethnic profiling requires training and oversight. Prison reform faces institutional resistance but is gradually achievable.',
                    'costs' => '€2-3 miljard voor preventieve programmas en hervorming',
                    'timeline' => '5-7 jaar voor structurele verandering'
                ]
            ]
        ],
        'current_seats' => 0,
        'color' => '#E91E63'
    ],
    
];

        // Data structureren voor de view
        $data = [
            'parties' => $parties,
            'themes' => $themes
        ];

        require_once 'views/politiek-kompas/index.php';
    }
}

// Instantieer de controller
$programmaVergelijkerController = new ProgrammaVergelijkerController();

// Voer de juiste methode uit
$programmaVergelijkerController->index(); 