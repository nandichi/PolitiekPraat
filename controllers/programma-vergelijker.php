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
                'icon' => '🛂'
            ],
            'Klimaat' => [
                'title' => 'Klimaat',
                'description' => 'CO2-reductie en milieumaatregelen',
                'icon' => '🌍'
            ],
            'Zorg' => [
                'title' => 'Zorg',
                'description' => 'Eigen risico en toegankelijkheid',
                'icon' => '🏥'
            ],
            'Energie' => [
                'title' => 'Energie',
                'description' => 'Kernenergie en duurzame bronnen',
                'icon' => '⚡'
            ],
            'Economie' => [
                'title' => 'Economie',
                'description' => 'Belastingen en ondernemerschap',
                'icon' => '💰'
            ],
            'Onderwijs' => [
                'title' => 'Onderwijs',
                'description' => 'Kwaliteit en toegankelijkheid',
                'icon' => '📚'
            ],
            'Woningmarkt' => [
                'title' => 'Woningmarkt',
                'description' => 'Betaalbare woningen en huurprijzen',
                'icon' => '🏠'
            ],
            'Veiligheid' => [
                'title' => 'Veiligheid',
                'description' => 'Politie en criminaliteitsbestrijding',
                'icon' => '🛡️'
            ]
        ];

        // Partij informatie uit de bestaande partijen controller
        $parties = [
    'PVV' => [
        'name' => 'Partij voor de Vrijheid',
        'leader' => 'Geert Wilders',
        'logo' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
        'description' => 'De Partij voor de Vrijheid (PVV) is een rechtse nationalistische partij die zich richt op het behoud van de Nederlandse identiteit, cultuur en soevereiniteit. Onder leiding van Geert Wilders pleit de partij voor een harde aanpak van immigratie en een sceptische houding tegenover internationale samenwerking, met name binnen de EU.',
        'standpoints' => [
            'Immigratie' => 'De PVV pleit voor een zeer streng immigratiebeleid, waaronder een volledige stop op asielaanvragen en strengere regels voor gezinshereniging. De partij wil de instroom van migranten minimaliseren om de Nederlandse cultuur en sociale cohesie te beschermen, met een focus op grensbewaking en snelle uitzetting van uitgeprocedeerde asielzoekers.',
            'Klimaat' => 'De PVV is zeer kritisch over ambitieuze klimaatmaatregelen, vooral als deze economische groei of de portemonnee van burgers belasten. De partij stelt dat klimaatbeleid realistisch en betaalbaar moet zijn, en verzet zich tegen wat zij ziet als overdreven milieuactivisme, zoals windmolenparken of hoge CO2-belastingen.',
            'Zorg' => 'De partij wil het eigen risico in de zorg volledig afschaffen om de toegang tot gezondheidszorg voor alle Nederlanders te verbeteren. Daarnaast pleit de PVV voor meer investeringen in zorgpersoneel en kortere wachttijden, met een focus op directe zorgverlening in plaats van bureaucratie.',
            'Energie' => 'De PVV steunt de ontwikkeling van kernenergie als een betrouwbare en betaalbare energiebron binnen een brede energiemix. De partij ziet kernenergie als een oplossing om de afhankelijkheid van fossiele brandstoffen te verminderen zonder de economie te schaden door dure groene alternatieven.',
            'Economie' => 'De PVV wil minder EU-regelgeving en meer nationale controle over economische beslissingen. De partij pleit voor belastingverlaging voor burgers en het midden- en kleinbedrijf (mkb), en is tegen wat zij ziet als overmatige bemoeienis van Brussel met de Nederlandse economie.',
            'Onderwijs' => 'De PVV wil meer nadruk op Nederlandse geschiedenis, cultuur en waarden in het onderwijs. De partij pleit voor een curriculum dat nationale trots versterkt en minder aandacht besteedt aan multiculturele thema’s. Daarnaast wil de PVV investeren in praktisch onderwijs en vakmanschap.',
            'Woningmarkt' => 'De PVV pleit voor voorrang voor Nederlandse staatsburgers bij de toewijzing van sociale huurwoningen. De partij wil de woningnood aanpakken door meer betaalbare woningen te bouwen en de instroom van migranten, die volgens de PVV de woningmarkt belast, te beperken.',
            'Veiligheid' => 'De PVV pleit voor een harde aanpak van criminaliteit met strengere straffen, meer politie op straat en een zerotolerancebeleid. De partij richt zich vooral op het aanpakken van overlast, geweld en criminele activiteiten die volgens haar worden versterkt door immigratie.'
        ],
        'current_seats' => 37,
        'color' => '#1E40AF'
    ],
    'VVD' => [
        'name' => 'Volkspartij voor Vrijheid en Democratie',
        'leader' => 'Dilan Yeşilgöz-Zegerius',
        'logo' => 'https://logo.clearbit.com/vvd.nl',
        'description' => 'De Volkspartij voor Vrijheid en Democratie (VVD) is een rechtsliberale partij die zich inzet voor individuele vrijheid, economische groei en een slagvaardige, efficiënte overheid. Onder leiding van Dilan Yeşilgöz-Zegerius combineert de VVD een pro-marktbenadering met een pragmatische visie op maatschappelijke uitdagingen.',
        'standpoints' => [
            'Immigratie' => 'De VVD pleit voor een streng maar rechtvaardig immigratiebeleid, met een focus op selectieve toelating van asielzoekers en internationale samenwerking om migratiestromen te beheersen. De partij wil snelle integratie van toegelaten migranten en een harde aanpak van illegale migratie.',
            'Klimaat' => 'De VVD ondersteunt klimaatdoelstellingen, zoals het verminderen van CO2-uitstoot, maar benadrukt dat deze maatregelen economisch haalbaar moeten zijn. De partij wil innovatie in groene technologie stimuleren zonder bedrijven of huishoudens onevenredig te belasten met hoge kosten.',
            'Zorg' => 'De VVD wil het eigen risico in de zorg behouden om de zorgkosten beheersbaar te houden, maar pleit voor meer efficiëntie in de zorgsector. De partij steunt investeringen in preventie en digitalisering om de kwaliteit van zorg te verbeteren en wachttijden te verkorten.',
            'Energie' => 'De VVD ziet kernenergie als een belangrijke aanvulling op duurzame energiebronnen zoals wind en zon. De partij wil investeren in nieuwe kerncentrales om een stabiele en betaalbare energievoorziening te garanderen, terwijl de afhankelijkheid van buitenlandse energie afneemt.',
            'Economie' => 'De VVD pleit voor lagere belastingen, minder bureaucratie en een gunstig vestigingsklimaat voor bedrijven. De partij wil ondernemerschap stimuleren en Nederland aantrekkelijk houden voor internationale investeerders, met een focus op innovatie en digitalisering.',
            'Onderwijs' => 'De VVD wil meer autonomie voor scholen en een focus op excellentie in het onderwijs. De partij pleit voor investeringen in bèta- en techniekonderwijs, en wil dat het onderwijssysteem flexibel is om talent te stimuleren en kansengelijkheid te bevorderen.',
            'Woningmarkt' => 'De VVD wil de woningbouw versnellen door marktwerking te stimuleren en overbodige regels te schrappen. De partij pleit voor meer samenwerking met private partijen om betaalbare woningen te bouwen, met een focus op starters en middeninkomens.',
            'Veiligheid' => 'De VVD wil investeren in een sterke politie en betere cybersecurity om Nederland veiliger te maken. De partij pleit voor een harde aanpak van georganiseerde misdaad en drugscriminaliteit, en wil dat daders streng worden gestraft.'
        ],
        'current_seats' => 24,
        'color' => '#FF6B35'
    ],
    'NSC' => [
        'name' => 'Nieuw Sociaal Contract',
        'leader' => 'Pieter Omtzigt',
        'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
        'description' => 'Nieuw Sociaal Contract (NSC) is een relatief nieuwe partij die onder leiding van Pieter Omtzigt streeft naar transparant en eerlijk bestuur. De partij wil een fundamentele hervorming van de democratische instituties en een samenleving waarin burgers weer vertrouwen hebben in de overheid.',
        'standpoints' => [
            'Immigratie' => 'NSC pleit voor een doordacht asielbeleid dat zowel veiligheid als humanitaire verplichtingen waarborgt. De partij wil een gecontroleerde instroom van migranten, met een focus op snelle integratie en duidelijke regels voor wie in Nederland mag blijven.',
            'Klimaat' => 'NSC streeft naar een evenwichtige aanpak van klimaatbeleid, waarbij zowel ecologische als economische belangen worden meegewogen. De partij wil realistische doelen stellen en investeren in innovatieve oplossingen die de energietransitie betaalbaar houden.',
            'Zorg' => 'NSC overweegt aanpassingen aan het eigen risico in de zorg om de toegankelijkheid te verbeteren, maar is terughoudend met volledige afschaffing vanwege de kosten. De partij wil meer investeren in preventie en betere werkomstandigheden voor zorgpersoneel.',
            'Energie' => 'NSC staat open voor kernenergie als onderdeel van een stabiele en duurzame energiemix, mits veiligheid en kosten-effectiviteit zijn gegarandeerd. De partij wil ook investeren in andere vormen van groene energie, zoals waterstof.',
            'Economie' => 'NSC wil het vertrouwen in de overheid herstellen door transparantie en goed bestuur te bevorderen. De partij pleit voor een eerlijke economie waarin zowel grote bedrijven als het mkb gelijke kansen krijgen, met aandacht voor regionale ontwikkeling.',
            'Onderwijs' => 'NSC wil de kwaliteit en toegankelijkheid van onderwijs verbeteren, met een focus op kansengelijkheid. De partij pleit voor meer investeringen in leraren en een curriculum dat aansluit bij de behoeften van de moderne arbeidsmarkt.',
            'Woningmarkt' => 'NSC wil betaalbare woningen voor starters en de middenklasse door meer woningbouw en betere regulering van de huurmarkt. De partij pleit ook voor een eerlijke verdeling van woningen en aandacht voor regionale verschillen.',
            'Veiligheid' => 'NSC wil een effectieve aanpak van criminaliteit, met een focus op preventie en handhaving. De partij pleit voor meer zichtbare politie en betere bescherming van burgerrechten, terwijl de oorzaken van criminaliteit worden aangepakt.'
        ],
        'current_seats' => 20,
        'color' => '#059669'
    ],
    'BBB' => [
        'name' => 'BoerBurgerBeweging',
        'leader' => 'Caroline van der Plas',
        'logo' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
        'description' => 'De BoerBurgerBeweging (BBB) vertegenwoordigt de belangen van de agrarische sector en het platteland. Onder leiding van Caroline van der Plas combineert de partij een focus op traditionele waarden met een pragmatische visie op innovatie en duurzaamheid voor het platteland.',
        'standpoints' => [
            'Immigratie' => 'BBB steunt een streng asielbeleid en wil de instroom van migranten beperken om de druk op plattelandsgebieden te verminderen. De partij pleit voor betere integratie van nieuwkomers in kleine gemeenschappen, met respect voor lokale tradities.',
            'Klimaat' => 'BBB is sceptisch over ingrijpende klimaatmaatregelen die de agrarische sector onevenredig treffen. De partij wil boeren ondersteunen bij verduurzaming, maar verzet zich tegen wat zij ziet als onrealistische milieudoelen die de voedselproductie bedreigen.',
            'Zorg' => 'BBB wil het eigen risico in de zorg verlagen om de toegang tot zorg voor plattelandsbewoners te verbeteren. De partij pleit ook voor meer zorgfaciliteiten in dunbevolkte gebieden en betere ondersteuning voor mantelzorgers.',
            'Energie' => 'BBB ziet kernenergie als een betrouwbare en duurzame oplossing binnen de energietransitie. De partij wil dat energie betaalbaar blijft voor burgers en bedrijven, en pleit voor een mix van energiebronnen, waaronder zonne- en windenergie op geschikte locaties.',
            'Economie' => 'BBB wil de plattelandseconomie versterken door boeren en kleine ondernemers te ondersteunen. De partij pleit voor minder bureaucratie en meer subsidies voor duurzame landbouw, met een focus op voedselzekerheid en lokale productie.',
            'Onderwijs' => 'BBB pleit voor praktijkgericht onderwijs en meer aandacht voor landbouw en voedselproductie in het curriculum. De partij wil ook dat plattelandscholen beter worden gefinancierd om de leefbaarheid van dorpen te behouden.',
            'Woningmarkt' => 'BBB wil meer woningbouw op het platteland om jonge gezinnen en starters aan te trekken. De partij pleit voor het behoud van dorpskernen en een eerlijke verdeling van woningen, met minder nadruk op grootschalige urbanisatie.',
            'Veiligheid' => 'BBB wil de veiligheid op het platteland vergroten door meer politie-inzet en betere bescherming van boeren tegen diefstal en activisme. De partij pleit ook voor een aanpak van overlast en criminaliteit in kleine gemeenschappen.'
        ],
        'current_seats' => 7,
        'color' => '#16A34A'
    ],
    'GL-PvdA' => [
        'name' => 'GroenLinks-PvdA',
        'leader' => 'Frans Timmermans',
        'logo' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
        'description' => 'GroenLinks-PvdA is een progressieve alliantie die onder leiding van Frans Timmermans strijdt voor sociale rechtvaardigheid, duurzaamheid en gelijke kansen. De partij combineert de groene idealen van GroenLinks met de sociaaldemocratische waarden van de PvdA.',
        'standpoints' => [
            'Immigratie' => 'GL-PvdA pleit voor een humaan asielbeleid waarin mensenrechten en internationale verplichtingen centraal staan. De partij wil veilige en legale migratieroutes, betere opvangvoorzieningen en een eerlijke verdeling van asielzoekers binnen Europa.',
            'Klimaat' => 'GL-PvdA steunt ambitieuze klimaatmaatregelen, zoals een snelle overgang naar hernieuwbare energie en een forse reductie van CO2-uitstoot. De partij is bereid economische offers te brengen om klimaatverandering tegen te gaan en wil dat de vervuiler betaalt.',
            'Zorg' => 'GL-PvdA wil het eigen risico in de zorg afschaffen om de toegang tot gezondheidszorg voor iedereen te garanderen. De partij pleit ook voor meer investeringen in zorgpersoneel, preventie en mentale gezondheidszorg.',
            'Energie' => 'GL-PvdA is tegen kernenergie vanwege de veiligheidsrisico’s en lange bouwtijden. De partij wil vol inzetten op hernieuwbare energiebronnen zoals wind, zon en waterstof, en pleit voor een snelle afbouw van fossiele brandstoffen.',
            'Economie' => 'GL-PvdA wil een eerlijkere verdeling van welvaart door hogere belastingen voor topinkomens en multinationals. De partij pleit voor een sterke publieke sector en betere arbeidsvoorwaarden, met een focus op het bestrijden van ongelijkheid.',
            'Onderwijs' => 'GL-PvdA wil gelijke kansen in het onderwijs door meer investeringen in achterstandsscholen en het verlagen van studiekosten. De partij pleit ook voor een inclusief curriculum dat duurzaamheid en diversiteit benadrukt.',
            'Woningmarkt' => 'GL-PvdA wil meer sociale huurwoningen en strengere regulering van huurprijzen om wonen betaalbaar te maken. De partij pleit voor een stop op speculatie door grote beleggers en meer rechten voor huurders.',
            'Veiligheid' => 'GL-PvdA wil criminaliteit aanpakken door te investeren in preventie en de sociale oorzaken van misdaad, zoals armoede en ongelijkheid. De partij pleit voor een humane aanpak van detentie en meer aandacht voor re-integratie.'
        ],
        'current_seats' => 25,
        'color' => '#DC2626'
    ],
    'D66' => [
        'name' => 'Democraten 66',
        'leader' => 'Rob Jetten',
        'logo' => 'https://logo.clearbit.com/d66.nl',
        'description' => 'Democraten 66 (D66) is een progressief-liberale partij die onder leiding van Rob Jetten pleit voor een open en inclusieve samenleving. De partij legt de nadruk op onderwijs, innovatie en democratische vernieuwing.',
        'standpoints' => [
            'Immigratie' => 'D66 pleit voor een humaan maar gestructureerd asielbeleid met veilige en legale migratieroutes. De partij wil internationale samenwerking om migratiestromen te beheren en investeert in integratieprogramma’s voor nieuwkomers.',
            'Klimaat' => 'D66 wil dat Nederland een leidende rol speelt in de klimaattransitie, met ambitieuze doelen voor CO2-reductie en investeringen in groene technologie. De partij pleit voor een snelle overgang naar een circulaire economie.',
            'Zorg' => 'D66 wil het eigen risico in de zorg bevriezen en een maximumbedrag per behandeling instellen om de kosten voor burgers te beperken. De partij pleit ook voor meer aandacht voor preventie en digitalisering in de zorg.',
            'Energie' => 'D66 is kritisch over kernenergie, maar staat open voor innovatieve en veilige toepassingen. De partij geeft echter de voorkeur aan hernieuwbare energiebronnen zoals wind en zon, en wil fossiele brandstoffen zo snel mogelijk uitfaseren.',
            'Economie' => 'D66 wil innovatie en ondernemerschap stimuleren door te investeren in technologie en startups. De partij pleit voor een open economie met een sterke positie voor Nederland in de EU en de wereldmarkt.',
            'Onderwijs' => 'D66 wil fors investeren in onderwijs om Nederland een kenniseconomie van wereldklasse te maken. De partij pleit voor meer aandacht voor digitalisering, wetenschap en gelijke kansen voor alle leerlingen.',
            'Woningmarkt' => 'D66 pleit voor slimme verdichting en duurzame woningbouw om de woningnood aan te pakken. De partij wil meer flexibiliteit in de huurmarkt en ondersteuning voor starters en middeninkomens.',
            'Veiligheid' => 'D66 wil veiligheidsdiensten moderniseren en investeren in cybersecurity. De partij pleit voor een evenwichtige aanpak van criminaliteit, met aandacht voor preventie en respect voor burgerrechten.'
        ],
        'current_seats' => 9,
        'color' => '#7C3AED'
    ],
    'SP' => [
        'name' => 'Socialistische Partij',
        'leader' => 'Jimmy Dijk',
        'logo' => 'https://logo.clearbit.com/sp.nl',
        'description' => 'De Socialistische Partij (SP) is een linkse partij die onder leiding van Jimmy Dijk strijdt tegen sociale ongelijkheid en voor een sterke verzorgingsstaat. De partij zet zich in voor de belangen van gewone burgers en arbeiders.',
        'standpoints' => [
            'Immigratie' => 'De SP wil een humaan asielbeleid met goede opvang en integratie, maar pleit ook voor beheersing van de instroom om de druk op sociale voorzieningen te beperken. De partij legt de nadruk op het aanpakken van mondiale oorzaken van migratie.',
            'Klimaat' => 'De SP steunt klimaatmaatregelen, maar wil dat de kosten eerlijk worden verdeeld. De partij pleit voor een klimaattransitie waarin grote bedrijven en rijken meer bijdragen, en gewone burgers worden ontzien.',
            'Zorg' => 'De SP wil het eigen risico in de zorg afschaffen om gezondheidszorg voor iedereen toegankelijk te maken. De partij pleit ook voor een nationaal zorgfonds en meer investeringen in zorgpersoneel en publieke zorginstellingen.',
            'Energie' => 'De SP is tegen kernenergie vanwege de risico’s en kosten, en wil vol inzetten op hernieuwbare energiebronnen zoals wind en zon. De partij pleit voor een publieke energievoorziening die betaalbaar is voor iedereen.',
            'Economie' => 'De SP wil ongelijkheid bestrijden door hogere belastingen voor de rijken en betere arbeidsrechten. De partij pleit voor een sterke publieke sector en meer bescherming voor werknemers tegen flexwerk en ontslag.',
            'Onderwijs' => 'De SP wil gratis onderwijs voor iedereen, van basisschool tot universiteit. De partij pleit voor kleinere klassen, meer leraren en een inclusief onderwijssysteem dat kansengelijkheid bevordert.',
            'Woningmarkt' => 'De SP wil meer betaalbare huurwoningen en strengere regulering van huurprijzen. De partij pleit voor een stop op de verkoop van sociale huurwoningen en meer rechten voor huurders.',
            'Veiligheid' => 'De SP wil criminaliteit aanpakken door de sociale oorzaken, zoals armoede en werkloosheid, te bestrijden. De partij pleit voor meer buurtpreventie en een humane aanpak van detentie en re-integratie.'
        ],
        'current_seats' => 5,
        'color' => '#EF4444'
    ],
    'PvdD' => [
        'name' => 'Partij voor de Dieren',
        'leader' => 'Esther Ouwehand',
        'logo' => 'https://logo.clearbit.com/partijvoordedieren.nl',
        'description' => 'De Partij voor de Dieren (PvdD) onder leiding van Esther Ouwehand richt zich op dierenwelzijn, duurzaamheid en natuurbehoud. De partij heeft een brede visie op een rechtvaardige en ecologisch verantwoorde samenleving.',
        'standpoints' => [
            'Immigratie' => 'De PvdD pleit voor een asielbeleid dat mensenrechten respecteert en rekening houdt met ecologische draagkracht. De partij wil mondiale ongelijkheid en klimaatverandering aanpakken als oorzaken van migratie.',
            'Klimaat' => 'De PvdD steunt radicaal klimaatbeleid, zoals een snelle afbouw van fossiele brandstoffen en strenge CO2-reductie. De partij is bereid economische groei op korte termijn op te offeren voor een leefbare planeet.',
            'Zorg' => 'De PvdD wil zorg toegankelijk maken zonder financiële drempels, zoals het eigen risico. De partij pleit ook voor meer aandacht voor preventie, gezonde voeding en mentale gezondheid in het zorgsysteem.',
            'Energie' => 'De PvdD is tegen kernenergie vanwege de risico’s en pleit voor een snelle overgang naar hernieuwbare energiebronnen zoals wind, zon en geothermische energie. De partij wil een volledig duurzame energievoorziening.',
            'Economie' => 'De PvdD pleit voor een circulaire economie waarin duurzaamheid en eerlijkheid voorop staan. De partij wil af van groeidenken en steunt lokale, kleinschalige bedrijven en eerlijke handelspraktijken.',
            'Onderwijs' => 'De PvdD wil meer aandacht voor duurzaamheid, dierenwelzijn en ecologie in het onderwijs. De partij pleit voor een inclusief curriculum dat jongeren voorbereidt op een duurzame toekomst.',
            'Woningmarkt' => 'De PvdD wil duurzame woningbouw die rekening houdt met natuur en biodiversiteit. De partij pleit voor betaalbare woningen en een stop op grootschalige bouwprojecten die het milieu schaden.',
            'Veiligheid' => 'De PvdD wil dieren en natuur beter beschermen tegen mishandeling en vernietiging. De partij pleit ook voor een humane aanpak van criminaliteit, met aandacht voor preventie en sociale rechtvaardigheid.'
        ],
        'current_seats' => 6,
        'color' => '#10B981'
    ],
    'CDA' => [
        'name' => 'Christen-Democratisch Appèl',
        'leader' => 'Henri Bontenbal',
        'logo' => 'https://logo.clearbit.com/cda.nl',
        'description' => 'Het Christen-Democratisch Appèl (CDA) onder leiding van Henri Bontenbal staat voor een samenleving gebaseerd op christendemocratische waarden zoals solidariteit, rentmeesterschap en gemeenschapszin. De partij zoekt een balans tussen traditie en vooruitgang.',
        'standpoints' => [
            'Immigratie' => 'Het CDA pleit voor een onderscheidend asielbeleid dat tijdelijke bescherming biedt aan echte vluchtelingen, maar permanente verblijfsrechten beperkt. De partij wil snelle integratie en een harde aanpak van illegale migratie.',
            'Klimaat' => 'Het CDA steunt klimaatmaatregelen, maar wil deze combineren met economische stabiliteit. De partij pleit voor een realistische energietransitie en samenwerking met bedrijven om groene innovaties te stimuleren.',
            'Zorg' => 'Het CDA wil het eigen risico in de zorg gericht verlagen om de toegankelijkheid te verbeteren, met name voor chronisch zieken. De partij pleit ook voor meer ondersteuning voor mantelzorgers en lokale zorgvoorzieningen.',
            'Energie' => 'Het CDA ziet kernenergie als een belangrijk onderdeel van een brede energiemix, mits veiligheid is gegarandeerd. De partij wil ook investeren in duurzame energiebronnen en energiebesparing.',
            'Economie' => 'Het CDA pleit voor een sociale markteconomie waarin bedrijven en gemeenschappen samenwerken. De partij wil een eerlijk speelveld voor het mkb en pleit voor meer regionale economische ontwikkeling.',
            'Onderwijs' => 'Het CDA wil waardegedreven onderwijs met ruimte voor keuzevrijheid. De partij pleit voor investeringen in leraren en een curriculum dat zowel academische als praktische vaardigheden benadrukt.',
            'Woningmarkt' => 'Het CDA wil betaalbare woningen voor gezinnen en starters door meer woningbouw en betere samenwerking tussen overheden en marktpartijen. De partij pleit ook voor het behoud van leefbare wijken.',
            'Veiligheid' => 'Het CDA wil veiligheid en rechtsorde handhaven met respect voor individuele rechten. De partij pleit voor meer zichtbare politie, een harde aanpak van criminaliteit en aandacht voor preventie.'
        ],
        'current_seats' => 5,
        'color' => '#F59E0B'
    ],
    'SGP' => [
        'name' => 'Staatkundig Gereformeerde Partij',
        'leader' => 'Chris Stoffer',
        'logo' => 'https://logo.clearbit.com/sgp.nl',
        'description' => 'De Staatkundig Gereformeerde Partij (SGP) is een christelijke partij die onder leiding van Chris Stoffer staat voor Bijbels-geïnspireerde politiek. De partij legt de nadruk op traditionele waarden, gezinsbeleid en een sobere, betrouwbare overheid.',
        'standpoints' => [
            'Immigratie' => 'De SGP pleit voor een streng en selectief immigratiebeleid, waarbij de Nederlandse identiteit en sociale cohesie worden beschermd. De partij wil een gecontroleerde instroom van migranten en snelle uitzetting van illegalen, met aandacht voor humanitaire verplichtingen.',
            'Klimaat' => 'De SGP steunt een rentmeesterschapsbenadering van klimaatbeleid, waarbij zorg voor het milieu wordt gecombineerd met economische stabiliteit. De partij pleit voor realistische maatregelen en technologische innovatie in plaats van hoge belastingen.',
            'Zorg' => 'De SGP wil de zorg toegankelijk houden en overweegt een verlaging van het eigen risico, met name voor lagere inkomens. De partij pleit voor meer aandacht voor ethische kwesties in de zorg, zoals euthanasie en abortus.',
            'Energie' => 'De SGP staat open voor kernenergie als een stabiele en schone energiebron, mits veiligheid gewaarborgd is. De partij wil een gebalanceerde energiemix met aandacht voor zowel duurzame energie als betaalbaarheid.',
            'Economie' => 'De SGP pleit voor een sobere economie met een sterke focus op het gezin als hoeksteen van de samenleving. De partij wil lagere lasten voor gezinnen en het mkb, en is voorstander van een beperkte overheidsrol.',
            'Onderwijs' => 'De SGP wil vrijheid van onderwijs waarborgen, met ruimte voor christelijk en ander bijzonder onderwijs. De partij pleit voor een curriculum dat traditionele waarden en normen benadrukt.',
            'Woningmarkt' => 'De SGP wil betaalbare woningen voor gezinnen en starters, met aandacht voor het behoud van dorps- en gemeenschapsstructuren. De partij pleit voor meer woningbouw zonder aantasting van natuur en landschap.',
            'Veiligheid' => 'De SGP wil een harde aanpak van criminaliteit en pleit voor meer politie-inzet en strengere straffen. De partij legt ook nadruk op het belang van gezin en gemeenschap in het voorkomen van criminaliteit.'
        ],
        'current_seats' => 3,
        'color' => '#003087'
    ],
    'FvD' => [
        'name' => 'Forum voor Democratie',
        'leader' => 'Thierry Baudet',
        'logo' => 'https://logo.clearbit.com/fvd.nl',
        'description' => 'Forum voor Democratie (FvD) is een conservatieve, nationalistische partij onder leiding van Thierry Baudet. De partij pleit voor directe democratie, nationale soevereiniteit en een kritische houding tegenover globalisering en de EU.',
        'standpoints' => [
            'Immigratie' => 'FvD wil een zeer streng immigratiebeleid met een stop op asielaanvragen en een focus op remigratie van niet-geïntegreerde migranten. De partij ziet immigratie als een bedreiging voor de Nederlandse cultuur en veiligheid.',
            'Klimaat' => 'FvD is uiterst sceptisch over klimaatbeleid en ziet veel maatregelen als onnodig en economisch schadelijk. De partij pleit voor aanpassing in plaats van mitigatie en wil af van subsidies voor wind- en zonne-energie.',
            'Zorg' => 'FvD wil het eigen risico in de zorg afschaffen om de toegankelijkheid te verbeteren. De partij pleit voor minder bureaucratie in de zorgsector en meer autonomie voor zorgverleners.',
            'Energie' => 'FvD steunt kernenergie als een kosteneffectieve oplossing voor energieonafhankelijkheid. De partij is tegen grootschalige investeringen in wind- en zonne-energie vanwege de kosten en landschapsvervuiling.',
            'Economie' => 'FvD wil minder EU-regelgeving en een focus op nationale economische belangen. De partij pleit voor lagere belastingen en deregulering om ondernemerschap te stimuleren.',
            'Onderwijs' => 'FvD wil een onderwijssysteem dat de Nederlandse identiteit en geschiedenis benadrukt. De partij is tegen wat zij ziet als ‘woke’ invloeden in het onderwijs en pleit voor meer aandacht voor klassieke kennis.',
            'Woningmarkt' => 'FvD wil de woningnood aanpakken door meer bouwvrijheid en minder bureaucratie. De partij pleit voor voorrang voor Nederlanders bij sociale huurwoningen en een beperking van migratie om de druk op de woningmarkt te verlichten.',
            'Veiligheid' => 'FvD pleit voor een zerotolerancebeleid tegen criminaliteit, met strengere straffen en meer politie. De partij legt een sterke nadruk op het aanpakken van misdaad die zij koppelt aan immigratie.'
        ],
        'current_seats' => 3,
        'color' => '#8B0000'
    ],
    'DENK' => [
        'name' => 'DENK',
        'leader' => 'Stephan van Baarle',
        'logo' => 'https://i.ibb.co/JwdSLG7g/DENK.jpg',
        'description' => 'DENK is een partij die onder leiding van Stephan van Baarle opkomt voor diversiteit, gelijke kansen en inclusie. De partij richt zich vooral op de belangen van minderheidsgroepen en pleit voor een rechtvaardige samenleving.',
        'standpoints' => [
            'Immigratie' => 'DENK pleit voor een humaan asielbeleid en betere integratie van migranten. De partij wil discriminatie op de arbeids- en woningmarkt aanpakken en pleit voor gelijke behandeling van alle burgers.',
            'Klimaat' => 'DENK steunt klimaatbeleid, maar wil dat de kosten eerlijk worden verdeeld en dat burgers met lagere inkomens worden ontzien. De partij pleit voor toegankelijke groene oplossingen voor iedereen.',
            'Zorg' => 'DENK wil het eigen risico in de zorg afschaffen om gezondheidszorg toegankelijk te maken voor iedereen. De partij pleit ook voor meer aandacht voor cultureel sensitieve zorg en betere arbeidsvoorwaarden in de zorgsector.',
            'Energie' => 'DENK is voorstander van een snelle overgang naar hernieuwbare energie, zoals wind en zon. De partij wil dat energie betaalbaar blijft en pleit voor subsidies voor duurzame initiatieven in achterstandswijken.',
            'Economie' => 'DENK wil ongelijkheid aanpakken door hogere belastingen voor de rijken en betere ondersteuning voor kleine ondernemers. De partij pleit voor een inclusieve economie waarin iedereen kansen krijgt.',
            'Onderwijs' => 'DENK wil gelijke kansen in het onderwijs door te investeren in achterstandsscholen en het aanpakken van discriminatie. De partij pleit voor een inclusief curriculum dat diversiteit viert.',
            'Woningmarkt' => 'DENK wil meer sociale huurwoningen en strengere regulering van huurprijzen. De partij pleit voor een eerlijke verdeling van woningen en maatregelen tegen discriminatie op de woningmarkt.',
            'Veiligheid' => 'DENK wil criminaliteit aanpakken door te investeren in preventie en sociale programma’s. De partij pleit voor een inclusieve aanpak van veiligheid, met aandacht voor discriminatie door politie.'
        ],
        'current_seats' => 3,
        'color' => '#00CED1'
    ],
    'Volt' => [
        'name' => 'Volt Nederland',
        'leader' => 'Laurens Dassen',
        'logo' => 'https://logo.clearbit.com/voltnederland.org',
        'description' => 'Volt Nederland is een progressieve, pro-Europese partij onder leiding van Laurens Dassen. De partij pleit voor grensoverschrijdende samenwerking, duurzaamheid en democratische vernieuwing binnen Europa.',
        'standpoints' => [
            'Immigratie' => 'Volt pleit voor een humaan en Europees gecoördineerd asielbeleid met veilige migratieroutes. De partij wil investeren in integratie en pleit voor een eerlijke verdeling van asielzoekers binnen de EU.',
            'Klimaat' => 'Volt wil een ambitieus klimaatbeleid met een snelle overgang naar een CO2-neutrale economie. De partij pleit voor Europese samenwerking om groene technologieën te ontwikkelen en klimaatdoelen te halen.',
            'Zorg' => 'Volt wil het eigen risico in de zorg verlagen en investeren in toegankelijke gezondheidszorg. De partij pleit voor Europese samenwerking op het gebied van zorginnovatie en pandemiepreventie.',
            'Energie' => 'Volt is kritisch over kernenergie en geeft de voorkeur aan hernieuwbare energiebronnen zoals wind, zon en waterstof. De partij wil een Europese energiemarkt die duurzaam en betaalbaar is.',
            'Economie' => 'Volt pleit voor een sterke Europese economie met minder bureaucratie en meer innovatie. De partij wil startups en het mkb ondersteunen en pleit voor een eerlijke belastingheffing op multinationals.',
            'Onderwijs' => 'Volt wil een Europees onderwijssysteem dat excellentie en toegankelijkheid combineert. De partij pleit voor meer investeringen in digitalisering en een curriculum dat Europese waarden benadrukt.',
            'Woningmarkt' => 'Volt wil de woningnood aanpakken door duurzame woningbouw en Europese samenwerking. De partij pleit voor betaalbare woningen en een eerlijke huurmarkt voor starters en jongeren.',
            'Veiligheid' => 'Volt wil Europese samenwerking op het gebied van veiligheid en cybersecurity. De partij pleit voor een moderne aanpak van criminaliteit, met aandacht voor preventie en mensenrechten.'
        ],
        'current_seats' => 2,
        'color' => '#502379'
    ],
    'CU' => [
        'name' => 'ChristenUnie',
        'leader' => 'Mirjam Bikker',
        'logo' => 'https://logo.clearbit.com/christenunie.nl',
        'description' => 'De ChristenUnie (CU) is een christelijke partij onder leiding van Mirjam Bikker, die zich inzet voor een samenleving gebaseerd op Bijbelse waarden, zoals gerechtigheid, zorg voor de naaste en rentmeesterschap.',
        'standpoints' => [
            'Immigratie' => 'De CU pleit voor een humaan asielbeleid dat vluchtelingen beschermt, maar ook de draagkracht van de samenleving respecteert. De partij wil betere opvang en integratie, met aandacht voor kwetsbare groepen.',
            'Klimaat' => 'De CU steunt een rentmeesterschapsbenadering van klimaatbeleid, met ambitieuze doelen voor CO2-reductie. De partij wil duurzame innovatie stimuleren en pleit voor een eerlijke verdeling van de kosten.',
            'Zorg' => 'De CU wil het eigen risico in de zorg verlagen en investeren in toegankelijke zorg, met name voor ouderen en chronisch zieken. De partij pleit ook voor meer aandacht voor ethische kwesties in de zorg.',
            'Energie' => 'De CU staat open voor kernenergie als onderdeel van een duurzame energiemix, maar geeft de voorkeur aan hernieuwbare energiebronnen. De partij wil energie betaalbaar houden voor gezinnen.',
            'Economie' => 'De CU pleit voor een eerlijke economie waarin gezinnen en het mkb centraal staan. De partij wil een sterke sociale zekerheid en pleit voor een eerlijke verdeling van welvaart.',
            'Onderwijs' => 'De CU wil vrijheid van onderwijs waarborgen en investeren in leraren. De partij pleit voor een curriculum dat waarden zoals respect en verantwoordelijkheid benadrukt.',
            'Woningmarkt' => 'De CU wil betaalbare woningen voor gezinnen en starters door meer woningbouw en betere regulering van de huurmarkt. De partij pleit voor het behoud van leefbare gemeenschappen.',
            'Veiligheid' => 'De CU wil een rechtvaardige aanpak van criminaliteit, met aandacht voor preventie en re-integratie. De partij pleit voor meer zichtbare politie en een focus op het gezin als basis voor veiligheid.'
        ],
        'current_seats' => 3,
        'color' => '#005F6B'
    ],
    'JA21' => [
        'name' => 'Juiste Antwoord 2021',
        'leader' => 'Joost Eerdmans',
        'logo' => 'https://logo.clearbit.com/ja21.nl',
        'description' => 'Juiste Antwoord 2021 (JA21) is een conservatieve partij onder leiding van Joost Eerdmans, die zich richt op nationale soevereiniteit, veiligheid en een pragmatische aanpak van maatschappelijke problemen.',
        'standpoints' => [
            'Immigratie' => 'JA21 pleit voor een streng immigratiebeleid met een focus op het beperken van asielinstroom en het bevorderen van remigratie. De partij wil de Nederlandse identiteit beschermen en illegalen snel uitzetten.',
            'Klimaat' => 'JA21 is kritisch over dure klimaatmaatregelen en wil een pragmatische aanpak die de economie niet schaadt. De partij pleit voor technologische oplossingen en is sceptisch over grootschalige groene projecten.',
            'Zorg' => 'JA21 wil het eigen risico in de zorg verlagen om de toegankelijkheid te verbeteren. De partij pleit voor minder bureaucratie en meer investeringen in directe zorgverlening.',
            'Energie' => 'JA21 steunt kernenergie als een betrouwbare en betaalbare energiebron. De partij wil een gebalanceerde energiemix en is tegen dure subsidies voor wind- en zonne-energie.',
            'Economie' => 'JA21 wil lagere belastingen en minder regelgeving om ondernemerschap te stimuleren. De partij pleit voor een sterke nationale economie en minder afhankelijkheid van de EU.',
            'Onderwijs' => 'JA21 wil een onderwijssysteem dat de Nederlandse cultuur en geschiedenis benadrukt. De partij pleit voor meer discipline in het onderwijs en investeringen in praktische vaardigheden.',
            'Woningmarkt' => 'JA21 wil de woningnood aanpakken door meer bouwvrijheid en minder bureaucratie. De partij pleit voor voorrang voor Nederlanders bij sociale huurwoningen en een beperking van migratie.',
            'Veiligheid' => 'JA21 pleit voor een harde aanpak van criminaliteit, met strengere straffen en meer politie. De partij wil ook meer aandacht voor de bestrijding van drugscriminaliteit en illegale migratie.'
        ],
        'current_seats' => 1,
        'color' => '#FFD700'
    ]
        ];

        // Data structureren voor de view
        $data = [
            'parties' => $parties,
            'themes' => $themes
        ];

        require_once 'views/programma-vergelijker/index.php';
    }
}

// Instantieer de controller
$programmaVergelijkerController = new ProgrammaVergelijkerController();

// Voer de juiste methode uit
$programmaVergelijkerController->index(); 