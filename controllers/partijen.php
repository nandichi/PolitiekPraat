<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';
require_once BASE_PATH . '/includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Party information
$parties = [
    'PVV' => [
        'name' => 'Partij voor de Vrijheid',
        'leader' => 'Geert Wilders',
        'logo' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
        'leader_photo' => '/partijleiders/geert.jpg',
        'description' => 'De PVV is een uitgesproken rechtse partij die zich met volle overgave inzet voor het behoud van de Nederlandse identiteit en cultuur. Ze pleiten voor extreem strengere immigratieregels, een harde lijn tegen islamisering en een duidelijke terugtrekking uit Europese besluitvorming. Daarnaast staan zij voor meer directe democratie en een samenleving waarin veiligheid, traditie en nationale soevereiniteit vooropstaan. Ze profileren zich als de partij die de gevestigde orde uitdaagt en de stem van de gewone burger verdedigt tegen globaliserende trends.',
        'leader_info' => 'Geert Wilders leidt de PVV sinds 2006 en is een van de meest controversiële en herkenbare figuren in de Nederlandse politiek. Zijn scherpe uitspraken en gedurfde aanpak hebben hem zowel lovende als felle critici opgeleverd. Na de opvallende verkiezingsresultaten van 2023 heeft hij de positie van zijn partij verder versterkt, wat hem een symbool maakt van verzet tegen wat hij ziet als een bedreiging van de nationale identiteit en soevereiniteit.',
        'standpoints' => [
            'Immigratie' => 'Een strenger asielbeleid met een volledige asielstop',
            'Klimaat' => 'Kritisch over ambitieuze klimaatmaatregelen als deze economische groei belemmeren',
            'Zorg' => 'Afschaffen van het eigen risico in de zorg',
            'Energie' => 'Voorstander van kernenergie als onderdeel van de energiemix'
        ],
        'current_seats' => 37,
        'polling' => ['seats' => 28, 'percentage' => 18.7, 'change' => -1],
        'perspectives' => [
            'left' => 'De PVV pleit voor afschaffing van het eigen risico in de zorg, wat de gezondheidszorg toegankelijker en betaalbaarder maakt voor alle burgers, vooral voor mensen met lagere inkomens.',
            'right' => 'De PVV komt sterk op voor nationale soevereiniteit en strenger immigratiebeleid, wat aanhangers zien als bescherming van de Nederlandse cultuur en identiteit.'
        ]
    ],
    'VVD' => [
        'name' => 'Volkspartij voor Vrijheid en Democratie',
        'leader' => 'Dilan Yeşilgöz-Zegerius',
        'logo' => 'https://logo.clearbit.com/vvd.nl',
        'leader_photo' => '/partijleiders/dilan.jpg',
        'description' => 'De VVD is een dynamische rechtsliberale partij die inzet op individuele vrijheid, economische groei en een efficiënte overheid. Zij pleiten voor lagere belastingen, minder bureaucratie en een marktgerichte economie waarin ondernemerschap centraal staat. Met een focus op pragmatische oplossingen wil de partij een stabiele en toekomstgerichte samenleving creëren, waarin ruimte is voor zowel innovatie als traditionele waarden.',
        'leader_info' => 'Dilan Yeşilgöz-Zegerius, sinds 2023 partijleider, brengt een frisse wind in de VVD. Met haar achtergrond als minister van Justitie en Veiligheid en haar ervaring in de lokale politiek weet zij complexe vraagstukken op een heldere en toegankelijke manier te presenteren. Haar modernisering van de partij en haar focus op zowel economische als maatschappelijke vernieuwing maken haar een inspirerende leider voor een nieuwe generatie kiezers.',
        'standpoints' => [
            'Immigratie' => 'Strengere selectie en beperking van asielaanvragen, met internationale samenwerking',
            'Klimaat' => 'Ondersteunt klimaatmaatregelen maar niet ten koste van economische groei',
            'Zorg' => 'Behoud van eigen risico om zorgkosten beheersbaar te houden',
            'Energie' => 'Voorstander van kernenergie als aanvulling op duurzame bronnen'
        ],
        'current_seats' => 24,
        'polling' => ['seats' => 30, 'percentage' => 20.0, 'change' => +6],
        'perspectives' => [
            'left' => 'De VVD steunt praktische klimaatmaatregelen en energietransitieplannen die de industrie niet vervreemden, waardoor een duurzamere economie mogelijk wordt zonder massaal banenverlies.',
            'right' => 'De VVD bevordert economische groei, lagere belastingen en minder bureaucratie, wat ondernemerschap stimuleert en de markteconomie versterkt.'
        ]
    ],
    'NSC' => [
        'name' => 'Nieuw Sociaal Contract',
        'leader' => 'Pieter Omtzigt',
        'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
        'leader_photo' => '/partijleiders/omtzicht.jpg',
        'description' => 'NSC is een baanbrekende partij die staat voor transparantie, eerlijk bestuur en een fundamentele herwaardering van de democratische instituties. Zij leggen de nadruk op integriteit, een verantwoordelijke overheid en het herstel van het vertrouwen in de politiek. Met een duidelijke agenda gericht op grondrechten, tegenmacht en publieke participatie wil NSC de politiek opnieuw in dienst stellen van de burger.',
        'leader_info' => 'Pieter Omtzigt, de oprichter van NSC in 2023, is bekend geworden door zijn vasthoudende onderzoek naar de toeslagenaffaire. Zijn reputatie als een compromisloze waarheidsvinder en zijn scherpe blik op systemische misstanden maken hem tot een symbool van integriteit en rechtvaardigheid. Hij streeft ernaar de politiek te zuiveren van gevestigde belangen en de macht terug te geven aan de gewone burger.',
        'standpoints' => [
            'Immigratie' => 'Een doordacht asielbeleid dat zowel veiligheid als humanitaire zorg waarborgt',
            'Klimaat' => 'Evenwichtige aanpak waarbij zowel klimaat als economie belangrijk zijn',
            'Zorg' => 'Overweegt aanpassingen in plaats van volledige afschaffing van het eigen risico',
            'Energie' => 'Open voor kernenergie als het bijdraagt aan een stabiele energiemix'
        ],
        'current_seats' => 20,
        'polling' => ['seats' => 2, 'percentage' => 1.3, 'change' => -18],
        'perspectives' => [
            'left' => 'NSC legt nadruk op overheidsverantwoording en transparantie, en strijdt tegen systematische onrechtvaardigheden zoals gezien in de toeslagenaffaire.',
            'right' => 'Hun evenwichtige benadering van immigratie- en asielbeleid richt zich zowel op veiligheidsaspecten als humanitaire verplichtingen zonder middelen te overschrijden.'
        ]
    ],
    'BBB' => [
        'name' => 'BoerBurgerBeweging',
        'leader' => 'Caroline van der Plas',
        'logo' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
        'leader_photo' => '/partijleiders/plas.jpg',
        'description' => 'BBB vertegenwoordigt de belangen van de agrarische sector en het platteland met een visie die traditie en innovatie combineert. Zij staan voor een duurzaam boerenbeleid, investeren in lokale gemeenschappen en streven ernaar de kloof tussen stad en platteland drastisch te verkleinen. De partij zet in op pragmatische oplossingen die zowel economische als ecologische duurzaamheid bevorderen, en fungeert als een brug tussen de landelijke waarden en de moderne samenleving.',
        'leader_info' => 'Caroline van der Plas is sinds 2019 de charismatische leider van BBB. Met een achtergrond in agrarische journalistiek en PR weet zij de zorgen en ambities van de boeren direct over te brengen. Haar directe communicatiestijl en nauwe band met de landelijke gemeenschappen maken haar tot een krachtige stem voor een toekomst waarin het platteland en de agrarische sector centraal staan.',
        'standpoints' => [
            'Immigratie' => 'Ondersteuning van een streng asielbeleid en beperking van de instroom',
            'Klimaat' => 'Sceptisch over ingrijpende klimaatmaatregelen, vooral als deze de agrarische sector schaden',
            'Zorg' => 'Voorstander van het verlagen van het eigen risico voor betere zorgtoegankelijkheid',
            'Energie' => 'Ziet kernenergie als betrouwbaar onderdeel van de energietransitie'
        ],
        'current_seats' => 7,
        'polling' => ['seats' => 2, 'percentage' => 1.3, 'change' => -5],
        'perspectives' => [
            'left' => 'BBB pleit voor het behoud van plattelandsgemeenschappen en traditionele landbouwpraktijken, waarmee cultureel erfgoed en lokale economieën tegen globalisering worden beschermd.',
            'right' => 'Ze verzetten zich tegen buitensporige milieuregels die boerenbestaan bedreigen en steunen een pragmatische balans tussen duurzaamheid en economische levensvatbaarheid.'
        ]
    ],
    'GL-PvdA' => [
        'name' => 'GroenLinks-PvdA',
        'leader' => 'Frans Timmermans',
        'logo' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
        'leader_photo' => '/partijleiders/frans.jpg',
        'description' => 'GL-PvdA is een krachtige progressieve alliantie die zich inzet voor sociale rechtvaardigheid, duurzaamheid en gelijke kansen voor iedereen. Ze combineren de idealen van groen beleid met de solidariteit en maatschappelijke betrokkenheid van de PvdA. Hun beleid richt zich op het aanpakken van klimaatverandering, het bevorderen van sociale inclusie en het versterken van internationale samenwerking voor een rechtvaardige wereld.',
        'leader_info' => 'Frans Timmermans, de lijsttrekker sinds 2023, brengt een schat aan internationale ervaring mee door zijn jarenlange betrokkenheid bij de Europese Commissie en de Europese Green Deal. Zijn brede visie en toewijding aan duurzaamheid en sociale gelijkheid maken hem tot een sleutelfiguur in het vormgeven van een progressieve toekomst, zowel in Nederland als in Europa.',
        'standpoints' => [
            'Immigratie' => 'Humanitaire principes moeten centraal staan in het asielbeleid',
            'Klimaat' => 'Ambitieuze klimaatmaatregelen, ook als daar op korte termijn offers voor nodig zijn',
            'Zorg' => 'Voorstander van afschaffing van het eigen risico voor gelijke toegang tot zorg',
            'Energie' => 'Tegen kernenergie vanwege risico\'s en lange doorlooptijden'
        ],
        'current_seats' => 25,
        'polling' => ['seats' => 28, 'percentage' => 18.7, 'change' => +3],
        'perspectives' => [
            'left' => 'Dit verbond steunt ambitieus klimaatbeleid en sociale rechtvaardigheid, en strijdt voor gelijkheid en milieubescherming.',
            'right' => 'Hun focus op sociale cohesie en gemeenschapskracht helpt sociale stabiliteit te behouden, wat een beter ondernemingsklimaat schept en maatschappelijke kosten reduceert.'
        ]
    ],
    'D66' => [
        'name' => 'Democraten 66',
        'leader' => 'Rob Jetten',
        'logo' => 'https://logo.clearbit.com/d66.nl',
        'leader_photo' => '/partijleiders/rob.jpg',
        'description' => 'D66 staat voor een open, progressief-liberale samenleving waarin onderwijs, innovatie en democratische vernieuwing centraal staan. Ze pleiten voor een moderne overheid die inspeelt op de uitdagingen van de 21e eeuw, waarbij individuele vrijheid en maatschappelijke verantwoordelijkheid hand in hand gaan. Hun beleid combineert economische modernisering met sociale inclusiviteit en een sterke Europese betrokkenheid.',
        'leader_info' => 'Rob Jetten, die de leiding overnam na Sigrid Kaag, is een visionaire politicus met een indrukwekkende staat van dienst als voormalig minister voor Klimaat en Energie. Zijn strategische inzichten en nadruk op duurzame innovatie maken hem tot een inspirerende leider die Nederland wil positioneren als voorloper in zowel ecologische als technologische ontwikkelingen.',
        'standpoints' => [
            'Immigratie' => 'Humaan maar gestructureerd asielbeleid met veilige en legale routes',
            'Klimaat' => 'Nederland moet een leidende rol spelen in de klimaattransitie',
            'Zorg' => 'Voorstander van bevriezen eigen risico met een limiet per behandeling',
            'Energie' => 'Kritisch over kernenergie, maar innovatie en veiligheid kunnen doorslaggevend zijn'
        ],
        'current_seats' => 9,
        'polling' => ['seats' => 10, 'percentage' => 6.7, 'change' => +1],
        'perspectives' => [
            'left' => 'D66 pleit voor investering in onderwijs en wetenschap, bevordert gelijke kansen en innovatieve oplossingen voor maatschappelijke problemen.',
            'right' => 'Hun nadruk op individuele vrijheid en progressief-liberale waarden stimuleert persoonlijke verantwoordelijkheid en modernisering van de economie.'
        ]
    ],
    'SP' => [
        'name' => 'Socialistische Partij',
        'leader' => 'Jimmy Dijk',
        'logo' => 'https://logo.clearbit.com/sp.nl',
        'leader_photo' => '/partijleiders/jimmy.jpg',
        'description' => 'De SP is een vurige linkse partij die strijdbaar opkomt tegen sociale ongelijkheid en voor een krachtige verzorgingsstaat. Ze pleiten voor een samenleving waarin publieke voorzieningen, zoals gezondheidszorg en onderwijs, voor iedereen toegankelijk zijn en waarin rijkdom eerlijk wordt herverdeeld. Met een sterke band met de arbeidersklasse en een grassroots mentaliteit zetten zij zich in voor radicale, maar rechtvaardige maatschappelijke veranderingen.',
        'leader_info' => 'Jimmy Dijk, sinds 2023 de energieke leider van de SP, komt uit een achtergrond van lokale politiek en vakbewegingen. Zijn passie voor sociale rechtvaardigheid en zijn praktische benadering van complexe problemen maken hem een herkenbare en toegankelijke stem voor de minderbedeelden in de samenleving.',
        'standpoints' => [
            'Immigratie' => 'Verbetering van opvang en integratie is even belangrijk als beperking van instroom',
            'Klimaat' => 'Klimaatmaatregelen moeten eerlijk worden verdeeld',
            'Zorg' => 'Afschaffen van het eigen risico voor een eerlijker zorgsysteem',
            'Energie' => 'Tegen investeringen in kerncentrales, liever inzetten op duurzame energie'
        ],
        'current_seats' => 5,
        'polling' => ['seats' => 8, 'percentage' => 5.3, 'change' => +2],
        'perspectives' => [
            'left' => 'De SP vecht consequent tegen ongelijkheid en voor een robuust sociaal vangnet, en komt op voor de rechten van werknemers en economisch kwetsbare groepen.',
            'right' => 'Hun benadering van politiek vanuit de basis en focus op het luisteren naar zorgen van gewone burgers helpt echte gemeenschapsproblemen aan te pakken.'
        ]
    ],
    'PvdD' => [
        'name' => 'Partij voor de Dieren',
        'leader' => 'Esther Ouwehand',
        'logo' => 'https://logo.clearbit.com/partijvoordedieren.nl',
        'leader_photo' => '/partijleiders/esther.jpg',
        'description' => 'De PvdD combineert dierenwelzijn met een brede visie op duurzaamheid en natuurbehoud. Ze pleiten voor een fundamentele herziening van ons economisch systeem om ecologische grenzen te respecteren en biodiversiteit te beschermen. Hun agenda is erop gericht de balans te herstellen tussen economische groei en milieubehoud, zodat mens en natuur in harmonie kunnen leven.',
        'leader_info' => 'Esther Ouwehand is sinds 2019 de drijvende kracht achter de PvdD. Met jarenlange ervaring in de Tweede Kamer en een onvermoeibare inzet voor dierenrechten en milieukwesties, is zij uitgegroeid tot een symbool van radicale maar doordachte milieupolitiek. Haar leiderschap kenmerkt zich door een combinatie van idealisme en realisme, gericht op duurzame veranderingen.',
        'standpoints' => [
            'Immigratie' => 'Asielbeleid moet mensenrechten respecteren en aandacht hebben voor ecologische context',
            'Klimaat' => 'Voorstander van radicaal klimaatbeleid, ongeacht economische kortetermijnnadelen',
            'Zorg' => 'Zorg moet toegankelijk zijn zonder financiële drempels',
            'Energie' => 'Kernenergie is verouderd, inzetten op hernieuwbare energiebronnen'
        ],
        'current_seats' => 6,
        'polling' => ['seats' => 5, 'percentage' => 3.3, 'change' => -1],
        'perspectives' => [
            'left' => 'Naast dierenwelzijn pleit PvdD voor een fundamenteel ander economisch systeem dat ecologische grenzen en biodiversiteit respecteert.',
            'right' => 'Hun focus op rentmeesterschap van de natuur sluit aan bij conservatieve tradities van zorgvuldig beheer van hulpbronnen en behoud voor toekomstige generaties.'
        ]
    ],
    'CDA' => [
        'name' => 'Christen-Democratisch Appèl',
        'leader' => 'Henri Bontenbal',
        'logo' => 'https://logo.clearbit.com/cda.nl',
        'leader_photo' => '/partijleiders/Henri.jpg',
        'description' => 'Het CDA staat voor een samenleving gebaseerd op christendemocratische waarden, waarbij solidariteit, rentmeesterschap en gemeenschapszin centraal staan. Ze streven naar een harmonieuze balans tussen economische groei en sociale rechtvaardigheid, met oog voor zowel traditionele waarden als moderne maatschappelijke ontwikkelingen. De partij zet zich in voor een zorgvuldige verdeling van verantwoordelijkheden, waarin familie en lokale gemeenschappen een cruciale rol spelen.',
        'leader_info' => 'Henri Bontenbal, partijleider sinds 2023, brengt een rijke ervaring mee uit zowel de energiesector als zijn tijd als Tweede Kamerlid. Zijn pragmatische aanpak en diepgewortelde geloof in sociale samenhang maken hem tot een stabiele en betrouwbare leider, die traditie en modernisering moeiteloos combineert.',
        'standpoints' => [
            'Immigratie' => 'Pleit voor een onderscheidend beleid met duidelijke scheiding tussen tijdelijke en permanente bescherming',
            'Klimaat' => 'Combinatie van klimaatmaatregelen en behoud van economische stabiliteit',
            'Zorg' => 'Voorstander van gerichte verlaging van het eigen risico',
            'Energie' => 'Kernenergie als onderdeel van een brede energiemix, mits goed gereguleerd'
        ],
        'current_seats' => 5,
        'polling' => ['seats' => 18, 'percentage' => 12.0, 'change' => +13],
        'perspectives' => [
            'left' => 'CDA\'s nadruk op gemeenschapsverantwoordelijkheid en solidariteit bevordert sociale cohesie en zorg voor kwetsbare leden van de samenleving.',
            'right' => 'Ze bevorderen familiewaarden en gemeenschapsinstellingen als essentiële fundamenten van een stabiele samenleving, waardoor minder staatsinterventie nodig is.'
        ]
    ],
    'JA21' => [
        'name' => 'Juiste Antwoord 2021',
        'leader' => 'Joost Eerdmans',
        'logo' => 'https://logo.clearbit.com/ja21.nl',
        'leader_photo' => '/partijleiders/joost.jpg',
        'description' => 'JA21 is een conservatief-liberale partij die met een no-nonsense aanpak de politieke status quo uitdaagt. Zij zetten zich in voor een streng immigratiebeleid, lagere belastingen en directe democratie, waarbij de belangen van de gewone burger centraal staan. Hun beleid is scherp en duidelijk, met een sterke nadruk op nationale veiligheid en soevereiniteit, en een kritische houding ten opzichte van overmatige EU-inmenging.',
        'leader_info' => 'Joost Eerdmans, medeoprichter en partijleider, brengt een kleurrijke en gedurfde achtergrond mee als voormalig wethouder en Kamerlid. Zijn directe en soms provocatieve stijl, gecombineerd met een diepgewortelde overtuiging in de belangen van het nationale volk, maken hem een uitgesproken en herkenbare leider binnen de Nederlandse politiek.',
        'standpoints' => [
            'Immigratie' => 'Ondersteuning van een restrictief asielbeleid met strikte toelatingscriteria',
            'Klimaat' => 'Wil niet dat klimaatmaatregelen de economische groei te veel hinderen',
            'Zorg' => 'Vindt een zekere mate van eigen bijdrage noodzakelijk voor efficiëntie',
            'Energie' => 'Voorstander van kernenergie voor energiezekerheid en emissiereductie'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +1],
        'perspectives' => [
            'left' => 'Hun steun voor directe democratie geeft burgers meer inspraak in beleidsbeslissingen, wat mogelijk de participatie in het democratische proces vergroot.',
            'right' => 'JA21 pleit voor lagere belastingen en duidelijk, rechtlijnig bestuur dat traditionele Nederlandse waarden respecteert.'
        ]
    ],
    'SGP' => [
        'name' => 'Staatkundig Gereformeerde Partij',
        'leader' => 'Chris Stoffer',
        'logo' => 'https://logo.clearbit.com/sgp.nl',
        'leader_photo' => '/partijleiders/Chris.jpg',
        'description' => 'De SGP is een traditionele, christelijke partij die haar politiek baseert op strikte bijbelse principes en morele waarden. Zij streven naar een samenleving waarin familie, integriteit en rentmeesterschap centraal staan en waar ethiek de basis vormt voor alle politieke besluiten. Met een nadruk op culturele continuïteit en verantwoordelijkheidsgevoel verzet de partij zich tegen moderne trends die volgens hen de kernwaarden bedreigen.',
        'leader_info' => 'Chris Stoffer leidt de SGP sinds 2023 en staat bekend om zijn compromisloze inzet voor christelijke waarden. Zijn ervaring in zowel de publieke als de private sector geeft hem een uniek perspectief, waarbij hij altijd streeft naar een balans tussen idealen en praktische realiteit. Zijn leiderschap is doordrenkt van een diepe overtuiging dat traditie en geloof de basis vormen voor een stabiele samenleving.',
        'standpoints' => [
            'Immigratie' => 'Voorstander van een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan',
            'Klimaat' => 'Vindt dat maatregelen verantwoord moeten zijn en de economie niet te zwaar mogen belasten',
            'Zorg' => 'Eigen risico als middel om onnodig gebruik van zorg te beperken, met ruimte voor verlaging bij kwetsbare groepen',
            'Energie' => 'Kernenergie als middel om de afhankelijkheid van fossiele brandstoffen te verminderen'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => 0],
        'perspectives' => [
            'left' => 'Hoewel traditioneel conservatief, toont SGP sterke toewijding aan rentmeesterschap en verantwoord beheer van hulpbronnen.',
            'right' => 'Hun onwankelbare toewijding aan morele waarden en gezinsgerichte beleidsmaatregelen bevordert stabiliteit en sociale orde.'
        ]
    ],
    'FvD' => [
        'name' => 'Forum voor Democratie',
        'leader' => 'Thierry Baudet',
        'logo' => 'https://logo.clearbit.com/fvd.nl',
        'leader_photo' => '/partijleiders/thierry.jpg',
        'description' => 'FvD is een controversiële rechts-conservatieve partij die met felle retoriek opkomt voor nationale soevereiniteit en directe democratie. Zij betwisten de heersende wetenschappelijke consensus over klimaatverandering, verwerpen de invloed van de EU en pleiten voor een drastische herinrichting van het immigratiebeleid. Hun uitgesproken en soms polariserende standpunten maken hen tot een constante bron van discussie en debat in de Nederlandse politieke arena.',
        'leader_info' => 'Thierry Baudet, de oprichter van FvD in 2016, is een intellectueel en controversieel denker die met zijn scherpe analyses en provocerende uitspraken de gevestigde orde voortdurend uitdaagt. Zijn achtergrond als rechtsfilosoof en publicist heeft hem een reputatie opgeleverd als een felle criticus van de mainstream politiek, wat hem zowel aanhangers als tegenstanders oplevert.',
        'standpoints' => [
            'Immigratie' => 'Pleit voor het beëindigen van het internationale asielkader en wil asielaanvragen sterk beperken',
            'Klimaat' => 'Betwist de urgentie van de klimaatcrisis en wil geen maatregelen die de economie schaden',
            'Zorg' => 'Voorstander van afschaffing van het eigen risico voor toegankelijke zorg',
            'Energie' => 'Wil investeren in kernenergie als alternatief voor fossiele brandstoffen'
        ],
        'current_seats' => 2,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => +1],
        'perspectives' => [
            'left' => 'FvD steunt afschaffing van het eigen risico in de zorg, wat de toegankelijkheid van gezondheidszorg voor alle burgers zou verbeteren, ongeacht inkomen.',
            'right' => 'Hun pleidooi voor nationale soevereiniteit en democratische hervorming is gericht op het teruggeven van macht aan burgers en het beperken van bureaucratische overreach.'
        ]
    ],
    'DENK' => [
        'name' => 'DENK',
        'leader' => 'Stephan van Baarle',
        'logo' => 'https://logo.clearbit.com/bewegingdenk.nl',
        'leader_photo' => '/partijleiders/baarle.jpg',
        'description' => 'DENK staat voor een inclusieve samenleving waarin iedereen, ongeacht achtergrond, gelijke kansen krijgt. Zij strijden tegen systematische discriminatie en pleiten voor structurele maatregelen die sociale rechtvaardigheid en gelijkheid bevorderen. Door het aanwakkeren van maatschappelijke discussies en het bieden van een platform voor ondervertegenwoordigde stemmen, streeft DENK naar een samenleving waarin diversiteit een kracht is.',
        'leader_info' => 'Stephan van Baarle, partijleider sinds 2021, komt uit een lokale politieke achtergrond en heeft zich altijd ingezet voor de rechten van minderheden. Zijn betrokkenheid bij burgerinitiatieven en zijn vastberadenheid om discriminatie tegen te gaan, maken hem tot een krachtige voorvechter van inclusiviteit en sociale gelijkheid in Nederland.',
        'standpoints' => [
            'Immigratie' => 'Kiest voor een humaan asielbeleid met aandacht voor solidariteit en internationale samenwerking',
            'Klimaat' => 'Wil een genuanceerde aanpak waarbij zowel klimaat als economie worden meegenomen',
            'Zorg' => 'Wil het eigen risico aanzienlijk verlagen om zorg voor iedereen bereikbaar te maken',
            'Energie' => 'Staat open voor kernenergie als het veilig en verantwoord wordt ingezet'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +1],
        'perspectives' => [
            'left' => 'DENK vecht consequent tegen discriminatie en voor gelijke kansen, en geeft een stem aan ondervertegenwoordigde gemeenschappen.',
            'right' => 'Hun focus op integratiebeleid erkent het belang van sociale cohesie en gedeelde waarden in een diverse samenleving.'
        ]
    ],
    'Volt' => [
        'name' => 'Volt Nederland',
        'leader' => 'Laurens Dassen',
        'logo' => 'https://logo.clearbit.com/voltnederland.org',
        'leader_photo' => '/partijleiders/dassen.jpg',
        'description' => 'Volt Nederland is een vernieuwende pan-Europese partij die zich inzet voor een geïntegreerd en democratisch Europa. Zij pleiten voor een digitale transformatie, duurzame energieoplossingen en sociale gelijkheid over landsgrenzen heen. Hun visie is er een van verbondenheid en vooruitgang, waarbij ze geloven dat gezamenlijke Europese actie de sleutel is tot het oplossen van mondiale uitdagingen.',
        'leader_info' => 'Laurens Dassen, medeoprichter en partijleider, brengt met zijn achtergrond in de financiële sector en zijn sterke pro-Europese visie een frisse, internationale blik in de Nederlandse politiek. Zijn doel is om de traditionele grenzen van nationale politiek te doorbreken en innovatieve, grensoverschrijdende oplossingen te stimuleren voor een toekomstbestendig Europa.',
        'standpoints' => [
            'Immigratie' => 'Staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert',
            'Klimaat' => 'Pleit voor ambitieuze maatregelen en gelooft dat de lange termijn voordelen opwegen tegen de korte termijn kosten',
            'Zorg' => 'Open voor verlaging van het eigen risico, mits dit financieel haalbaar is',
            'Energie' => 'Voorkeur voor hernieuwbare energie, maar open voor kernenergie bij strenge veiligheidseisen'
        ],
        'current_seats' => 2,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => +2],
        'perspectives' => [
            'left' => 'Volts pan-Europese benadering pakt transnationale uitdagingen zoals klimaatverandering en ongelijkheid aan door gecoördineerde actie.',
            'right' => 'Hun nadruk op digitale transformatie en innovatie bevordert economische concurrentiekracht en modernisering.'
        ]
    ],
    'CU' => [
        'name' => 'ChristenUnie',
        'leader' => 'Mirjam Bikker',
        'logo' => 'https://logo.clearbit.com/christenunie.nl',
        'leader_photo' => '/partijleiders/mirjam.jpg',
        'description' => 'De ChristenUnie is een sociaal-christelijke partij die geloof en politiek combineert met een sterke focus op duurzaamheid, sociale rechtvaardigheid en gezinswaarden. Ze streven naar een zorgzame samenleving waarin kwetsbare groepen worden beschermd en waarin rentmeesterschap voor de schepping centraal staat. De partij zoekt naar praktische oplossingen die zowel ethisch verantwoord als maatschappelijk relevant zijn.',
        'leader_info' => 'Mirjam Bikker, partijleider sinds 2023, staat bekend om haar doortastende en constructieve aanpak. Met haar achtergrond als jurist en haar ervaring in de Eerste en Tweede Kamer, combineert ze diepgaande kennis met een warm hart voor sociale thema\'s. Haar leiderschap richt zich op verbinding en het bouwen van bruggen, zowel binnen als buiten de politiek.',
        'standpoints' => [
            'Immigratie' => 'Een humaan asielbeleid met nadruk op veilige opvang en integratie',
            'Klimaat' => 'Ambitieuze klimaatdoelen vanuit rentmeesterschap',
            'Zorg' => 'Verlaging van het eigen risico voor kwetsbare groepen',
            'Energie' => 'Inzet op duurzame energie, kritisch op kernenergie'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => -2],
        'perspectives' => [
            'left' => 'De ChristenUnie legt sterke nadruk op sociale rechtvaardigheid, armoedebestrijding en milieubescherming, wat aansluit bij progressieve idealen.',
            'right' => 'Hun focus op gezinswaarden, gemeenschapszin en ethisch ondernemerschap ondersteunt traditionele maatschappelijke structuren.'
        ]
    ]
];

// Include the header
$title = "Politieke Partijen Overzicht";
$description = "Een overzicht van alle Nederlandse politieke partijen, hun standpunten en lijsttrekkers";
include_once BASE_PATH . '/views/templates/header.php';
?>

<main class="bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <!-- Hero Section - Elegant and professional design zoals in stemwijzer.php -->
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
                        Nederlandse Politieke Partijen
                    </span>
                </h1>
                
                <!-- Subtitle with lighter weight -->
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed font-light">
                    Ontdek de standpunten, lijsttrekkers en actuele peilingen van alle partijen in de Nederlandse politiek
                </p>
            </div>
        </div>
    </section>
    
    <div class="container mx-auto px-4 max-w-7xl -mt-6 relative z-10">
        <!-- Content section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Filter & Sortering -->
            <div class="lg:col-span-3 bg-white rounded-xl shadow p-4 mb-2">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h2 class="text-xl font-bold text-gray-800">Alle politieke partijen</h2>
                    <div class="flex flex-wrap gap-2">
                        <select id="sortOption" class="bg-gray-50 border border-gray-300 text-gray-700 rounded-lg px-3 py-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="name">Sorteer op naam</option>
                            <option value="seats">Sorteer op zetels (hoog-laag)</option>
                            <option value="polling">Sorteer op peilingen (hoog-laag)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <?php foreach ($parties as $partyKey => $party): ?>
                <div class="party-card bg-white rounded-xl overflow-hidden border border-gray-100 shadow-2xl relative">
                    <!-- Decorative accent strip matching party color -->
                    <div class="absolute top-0 left-0 right-0 h-1.5" style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                    
                    <!-- Party header with logo and stats -->
                    <div class="relative p-5 border-b border-gray-100">
                        <div class="flex items-center mb-3">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-full overflow-hidden shadow-md border-2 border-white flex items-center justify-center bg-white">
                                    <img src="<?php echo htmlspecialchars($party['logo']); ?>" alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                        class="w-14 h-14 object-contain">
                                </div>
                                
                                <!-- Current seats badge -->
                                <div class="absolute -bottom-1.5 -right-1.5 bg-primary text-white text-xs font-bold rounded-full w-8 h-8 flex items-center justify-center border-2 border-white shadow-md">
                                    <?php echo $party['current_seats']; ?>
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                <h2 class="text-xl font-extrabold text-gray-800 mb-0.5"><?php echo htmlspecialchars($partyKey); ?></h2>
                                <p class="text-sm text-gray-500 font-medium tracking-tight"><?php echo htmlspecialchars($party['name']); ?></p>
                            </div>
                        </div>
                        
                        <!-- Modern polling indicator with clean design -->
                        <div class="mt-4 bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Zetels in peilingen</h3>
                            </div>
                            
                            <!-- Modern visualization with party color -->
                            <div class="mt-2 flex items-center justify-between">
                                <?php 
                                $changeValue = $party['polling']['change'];
                                $isPositive = $changeValue > 0;
                                $isNegative = $changeValue < 0;
                                $absChange = abs($changeValue);
                                
                                if ($isPositive) {
                                    $trendColor = 'text-green-600';
                                    $trendBg = 'bg-green-50';
                                    $trendBorderColor = 'border-green-100';
                                    $trendIcon = '↑';
                                } elseif ($isNegative) {
                                    $trendColor = 'text-red-600';
                                    $trendBg = 'bg-red-50';
                                    $trendBorderColor = 'border-red-100';
                                    $trendIcon = '↓';
                                } else {
                                    $trendColor = 'text-blue-600';
                                    $trendBg = 'bg-blue-50';
                                    $trendBorderColor = 'border-blue-100';
                                    $trendIcon = '•';
                                }
                                
                                $trendText = $changeValue !== 0 ? ($changeValue > 0 ? '+' : '') . $changeValue . ' zetels' : 'Ongewijzigd';
                                ?>
                                
                                <!-- Zetels with trend in single clear display -->
                                <div class="flex items-center">
                                    <span class="text-4xl font-bold" style="color: <?php echo getPartyColor($partyKey); ?>">
                                        <?php echo $party['polling']['seats']; ?>
                                    </span>
                                    <span class="text-sm text-gray-400 font-medium ml-1 mt-3">/ 150</span>
                                </div>
                                
                                <!-- Single trend indicator -->
                                <div class="flex items-center <?php echo $trendBg; ?> px-3 py-2 rounded-lg border <?php echo $trendBorderColor; ?>">
                                    <span class="<?php echo $trendColor; ?> text-lg font-bold mr-1"><?php echo $trendIcon; ?></span>
                                    <span class="<?php echo $trendColor; ?> font-semibold">
                                        <?php echo $changeValue !== 0 ? ($changeValue > 0 ? '+' : '') . $changeValue : '±0'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Party content -->
                    <div class="p-5">
                        <!-- Party Leader Preview -->
                        <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                            <div class="w-14 h-14 rounded-full overflow-hidden mr-3 border-2 shadow-md" style="border-color: <?php echo getPartyColor($partyKey); ?>">
                                <img src="<?php echo htmlspecialchars($party['leader_photo']); ?>" alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800 mb-0.5"><?php echo htmlspecialchars($party['leader']); ?></p>
                                <p class="text-xs text-gray-500">Partijleider</p>
                            </div>
                        </div>
                        
                        <!-- Party description preview -->
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed"><?php echo htmlspecialchars(mb_substr($party['description'], 0, 150)) . '...'; ?></p>
                        
                        <!-- Key standpoints preview with custom badges -->
                        <div class="mb-5">
                            <h3 class="text-xs uppercase font-bold text-gray-700 mb-2.5 tracking-wider">Kernstandpunten:</h3>
                            <div class="flex flex-wrap gap-1.5">
                                <?php foreach (array_slice(array_keys($party['standpoints']), 0, 3) as $topic): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium" 
                                      style="background-color: <?php echo adjustColorOpacity(getPartyColor($partyKey), 0.15); ?>; 
                                             color: <?php echo adjustColorBrightness(getPartyColor($partyKey), -30); ?>;">
                                    <?php echo htmlspecialchars($topic); ?>
                                </span>
                                <?php endforeach; ?>
                                <span class="bg-gray-100 text-gray-500 text-xs px-2.5 py-1 rounded-full font-medium">
                                    +<?php echo count($party['standpoints']) - 3; ?> meer
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex gap-3">
                            <button class="party-btn flex-1 text-white text-sm px-4 py-2.5 rounded-lg flex items-center justify-center font-medium shadow-md" 
                                    style="background-color: <?php echo getPartyColor($partyKey); ?>; 
                                           box-shadow: 0 2px 0 <?php echo adjustColorBrightness(getPartyColor($partyKey), -30); ?>;"
                                    data-party="<?php echo htmlspecialchars($partyKey); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Partij
                            </button>
                            <button class="leader-btn flex-1 bg-white border border-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded-lg flex items-center justify-center font-medium bg-gray-50 shadow-md"
                                    data-leader="<?php echo htmlspecialchars($partyKey); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Leider
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Enhanced Modals -->
<div id="party-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 id="party-modal-title" class="text-2xl font-bold text-gray-800"></h2>
            <button class="close-modal text-gray-500 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Party header information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
                    <img id="party-modal-logo" src="" alt="" class="w-32 h-32 object-contain mx-auto mb-3">
                    <p id="party-modal-abbr" class="text-2xl font-semibold text-gray-800 mb-1"></p>
                    <p id="party-modal-name" class="text-sm text-gray-500 mb-4"></p>
                    
                    <div class="flex items-center justify-center space-x-4 mb-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Zetels</p>
                            <p id="party-modal-seats" class="text-xl font-bold text-gray-800"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Peilingen</p>
                            <p id="party-modal-polling" class="text-xl font-bold text-gray-800"></p>
                        </div>
                    </div>
                    
                    <div id="party-modal-polling-trend" class="text-sm font-medium mt-2"></div>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Over de partij</h3>
                    <p id="party-modal-description" class="text-gray-600 mb-6"></p>
                    
                    <div class="flex items-center mb-4">
                        <img id="party-modal-leader-photo" src="" alt="" class="w-16 h-16 object-cover rounded-full mr-4 border-2 border-primary">
                        <div>
                            <p class="text-sm text-gray-500">Partijleider</p>
                            <p id="party-modal-leader" class="text-lg font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Party standpoints -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Standpunten</h3>
            <div id="party-modal-standpoints" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Standpoints will be filled by JavaScript -->
            </div>
        </div>
        
        <!-- Political Perspectives Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Politieke Perspectieven</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left perspective card -->
                <div class="perspective-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 shadow-sm relative overflow-hidden">
                    <!-- Decorative pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-10">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M-5.46392e-07 0L80 0L80 80L75 80C33.5786 80 -5.46392e-07 46.4214 -5.46392e-07 5L-5.46392e-07 0Z" fill="#2563EB"/>
                        </svg>
                    </div>
                    <div class="flex items-center mb-3 relative">
                        <div class="bg-blue-500 text-white text-xs uppercase font-bold tracking-wider py-1 px-3 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Links perspectief
                        </div>
                    </div>
                    <p id="party-modal-left-perspective" class="text-gray-700 relative"></p>
                </div>
                
                <!-- Right perspective card -->
                <div class="perspective-card bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-5 shadow-sm relative overflow-hidden">
                    <!-- Decorative pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-10">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M-5.46392e-07 0L80 0L80 80L75 80C33.5786 80 -5.46392e-07 46.4214 -5.46392e-07 5L-5.46392e-07 0Z" fill="#DC2626"/>
                        </svg>
                    </div>
                    <div class="flex items-center mb-3 relative">
                        <div class="bg-red-500 text-white text-xs uppercase font-bold tracking-wider py-1 px-3 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Rechts perspectief
                        </div>
                    </div>
                    <p id="party-modal-right-perspective" class="text-gray-700 relative"></p>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <button class="close-modal bg-gray-200 bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg">
                Sluiten
            </button>
        </div>
    </div>
</div>

<div id="leader-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 id="leader-modal-title" class="text-2xl font-bold text-gray-800"></h2>
            <button class="close-modal text-gray-500 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
                    <img id="leader-modal-photo" src="" alt="" class="w-40 h-40 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-lg">
                    <div class="flex items-center justify-center mb-3">
                        <img id="leader-modal-party-logo" src="" alt="" class="w-8 h-8 object-contain mr-2">
                        <p id="leader-modal-party-abbr" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    <p id="leader-modal-party-name" class="text-sm text-gray-500"></p>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Over de lijsttrekker</h3>
                <p id="leader-modal-info" class="text-gray-600"></p>
            </div>
        </div>
        
        <div class="text-center">
            <button class="close-modal bg-gray-200 bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg">
                Sluiten
            </button>
        </div>
    </div>
</div>

<!-- Visual Tweede Kamer Representation -->
<div class="container mx-auto px-4 py-8 mb-12">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Zetelverdeling Tweede Kamer</h2>
        
        <!-- Tabs for switching between views -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button id="current-tab" class="tab-btn py-3 px-6 text-primary border-b-2 border-primary font-medium">
                        Huidige zetelverdeling
                    </button>
                    <button id="polling-tab" class="tab-btn py-3 px-6 text-gray-500 text-gray-700 font-medium border-b-2 border-transparent">
                        Peilingen
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Current Seats View -->
        <div id="current-view" class="chamber-view">
            <!-- Chamber visualization -->
            <div class="relative flex justify-center mb-8">
                <!-- Half-circle chamber -->
                <div class="chamber-semicircle relative w-full max-w-4xl h-72 md:h-80 border-3 border-slate-300 overflow-hidden shadow-inner">
                    <div class="absolute inset-0 flex flex-col justify-end items-center p-5 md:p-8" id="current-seats-chamber">
                        <!-- Seats will be filled by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Legend for current seats -->
            <div class="mt-8">
                <h3 class="text-base font-semibold text-gray-700 mb-4">Huidige zetelverdeling (150 zetels)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                    <?php
                    // Sort parties by current seats (descending)
                    $seatsSorted = $parties;
                    uasort($seatsSorted, function($a, $b) {
                        return $b['current_seats'] - $a['current_seats'];
                    });
                    
                    foreach ($seatsSorted as $partyKey => $party) {
                        if ($party['current_seats'] > 0) {
                            $color = getPartyColor($partyKey);
                            echo '<div class="flex items-center bg-gray-50 p-2 rounded-lg bg-gray-100 cursor-pointer">';
                            echo '<div class="w-4 h-4 rounded-md mr-2" style="background-color: ' . $color . '"></div>';
                            echo '<span class="text-sm font-medium">' . htmlspecialchars($partyKey) . '</span>';
                            echo '<span class="ml-auto text-sm font-bold">' . $party['current_seats'] . '</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <!-- Polling View (hidden by default) -->
        <div id="polling-view" class="chamber-view hidden">
            <!-- Chamber visualization -->
            <div class="relative flex justify-center mb-8">
                <!-- Half-circle chamber -->
                <div class="chamber-semicircle relative w-full max-w-4xl h-72 md:h-80 border-3 border-slate-300 overflow-hidden shadow-inner">
                    <div class="absolute inset-0 flex flex-col justify-end items-center p-5 md:p-8" id="polling-seats-chamber">
                        <!-- Polling seats will be filled by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Legend for polling -->
            <div class="mt-8">
                <h3 class="text-base font-semibold text-gray-700 mb-4">Peilingen (<?php
                    $totalPollingSeats = array_sum(array_map(function($party) {
                        return $party['polling']['seats'];
                    }, $parties));
                    echo $totalPollingSeats;
                ?> zetels)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                    <?php
                    // Sort parties by polling seats (descending)
                    $pollingSorted = $parties;
                    uasort($pollingSorted, function($a, $b) {
                        return $b['polling']['seats'] - $a['polling']['seats'];
                    });
                    
                    foreach ($pollingSorted as $partyKey => $party) {
                        if ($party['polling']['seats'] > 0) {
                            $color = getPartyColor($partyKey);
                            $change = $party['polling']['seats'] - $party['current_seats'];
                            $changeClass = $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600');
                            $changeText = $change > 0 ? '+' . $change : ($change < 0 ? $change : '');
                            
                            echo '<div class="flex items-center bg-gray-50 p-2 rounded-lg bg-gray-100 cursor-pointer">';
                            echo '<div class="w-4 h-4 rounded-md mr-2" style="background-color: ' . $color . '"></div>';
                            echo '<span class="text-sm font-medium">' . htmlspecialchars($partyKey) . '</span>';
                            echo '<span class="ml-auto flex items-center">';
                            echo '<span class="text-sm font-bold">' . $party['polling']['seats'] . '</span>';
                            if ($changeText) {
                                echo '<span class="text-xs ml-1 ' . $changeClass . '">' . $changeText . '</span>';
                            }
                            echo '</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center">
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 flex items-center max-w-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-blue-700">Hover over zetels voor meer informatie.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 mb-12">
    <!-- Modern Coalition Maker -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <!-- Elegant Header with Gradient -->
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"2\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"10\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"10\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-4xl font-black text-white mb-2 tracking-tight">
                            Coalitiemaker
                        </h2>
                        <p class="text-white/90 text-lg font-medium max-w-2xl">
                            Bouw je ideale coalitie en ontdek welke partijen samen een meerderheid kunnen vormen
                        </p>
                    </div>
                    <div class="hidden md:flex items-center space-x-2 bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white font-semibold text-sm">Live Data</span>
                    </div>
                </div>
                
                <!-- Interactive Tabs -->
                <div class="flex space-x-2 bg-white/20 backdrop-blur-sm rounded-2xl p-2 w-fit">
                    <button id="coalition-current-tab" class="coalition-tab-btn px-6 py-3 rounded-xl font-semibold text-white bg-white/30 backdrop-blur-sm bg-white/40">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span>Huidige Zetels</span>
                        </span>
                    </button>
                    <button id="coalition-polling-tab" class="coalition-tab-btn px-6 py-3 rounded-xl font-semibold text-white/70 text-white bg-white/20">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            <span>Peilingen</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="p-8">
            <!-- Coalition Builder Interface -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- Available Parties Section -->
                <div class="xl:col-span-4">
                    <div class="bg-gradient-to-br from-slate-50 to-gray-100 rounded-2xl border border-gray-200/60 shadow-lg">
                        <div class="p-6 border-b border-gray-200/60">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                                    Beschikbare Partijen
                                </h3>
                                <div id="available-count" class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full">
                                    0
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mt-2">Sleep partijen naar je coalitie</p>
                        </div>
                        <div class="p-6">
                            <div id="available-parties" class="space-y-3 max-h-[600px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                <div class="text-center text-gray-500 py-8">
                                    <div class="animate-spin w-8 h-8 border-2 border-blue-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                                    <p class="font-medium">Partijen worden geladen...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Coalition Builder Section -->
                <div class="xl:col-span-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-2xl border border-emerald-200/60 shadow-lg">
                        <div class="p-6 border-b border-emerald-200/60">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></div>
                                Jouw Coalitie
                            </h3>
                            <p class="text-gray-600 text-sm mt-2">Sleep hier je partijen naartoe</p>
                        </div>
                        <div class="p-6">
                            <div id="selected-coalition" class="min-h-[400px] max-h-[600px] overflow-y-auto rounded-xl border-2 border-dashed border-emerald-300 bg-white/50 backdrop-blur-sm border-emerald-400 bg-white/70">
                                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                                    <div class="bg-emerald-100 p-4 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium mb-2">Bouw je coalitie</p>
                                    <p class="text-gray-400 text-sm">Sleep partijen hierheen om te beginnen</p>
                                </div>
                            </div>
                            
                            <!-- Coalition Actions -->
                            <div class="mt-6 flex space-x-3">
                                <button id="clear-coalition" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-xl">
                                    <span class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Wissen</span>
                                    </span>
                                </button>
                                <button id="shuffle-coalition" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Coalition Analysis Section -->
                <div class="xl:col-span-4">
                    <div class="space-y-6">
                        
                        <!-- Coalition Summary -->
                        <div class="bg-gradient-to-br from-amber-50 to-orange-100 rounded-2xl border border-amber-200/60 shadow-lg">
                            <div class="p-6 border-b border-amber-200/60">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    <div class="w-2 h-2 bg-amber-500 rounded-full mr-3 animate-pulse"></div>
                                    Analyse
                                </h3>
                            </div>
                            <div class="p-6">
                                <!-- Seats Counter -->
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm font-semibold text-gray-700">Coalitiezetels</span>
                                        <div class="flex items-center space-x-2">
                                            <span id="coalition-seats" class="text-3xl font-black text-gray-900">0</span>
                                            <span class="text-sm text-gray-500">/150</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                        <div id="coalition-progress" class="h-full bg-shimmer rounded-full shadow-sm" style="width: 0%"></div>
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                        <!-- Majority Line -->
                                        <div class="absolute top-0 h-full w-0.5 bg-red-500" style="left: 50.67%">
                                            <div class="absolute -top-1 -left-2 w-4 h-6 bg-red-500 rounded-sm"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mt-2">
                                        <span>0</span>
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded font-bold">Meerderheid: 76</span>
                                        <span>150</span>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div id="coalition-status" class="text-center py-4 rounded-xl bg-gray-100 text-gray-600 font-semibold">
                                    Geen coalitie gevormd
                                </div>
                                
                                <!-- Coalition Stats -->
                                <div id="coalition-stats" class="mt-6 space-y-4 hidden">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white/60 p-3 rounded-lg text-center">
                                            <div id="coalition-parties-count" class="text-2xl font-bold text-gray-900">0</div>
                                            <div class="text-xs text-gray-600">Partijen</div>
                                        </div>
                                        <div class="bg-white/60 p-3 rounded-lg text-center">
                                            <div id="coalition-percentage" class="text-2xl font-bold text-gray-900">0%</div>
                                            <div class="text-xs text-gray-600">van Kamer</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Political Spectrum -->
                        <div class="bg-gradient-to-br from-violet-50 to-purple-100 rounded-2xl border border-violet-200/60 shadow-lg">
                            <div class="p-6 border-b border-violet-200/60">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <div class="w-2 h-2 bg-violet-500 rounded-full mr-3"></div>
                                    Politieke Richting
                                </h3>
                            </div>
                            <div class="p-6">
                                <div id="coalition-spectrum-container" class="relative">
                                    <div class="h-6 bg-gradient-to-r from-blue-500 via-purple-500 to-red-500 rounded-full shadow-inner overflow-hidden">
                                        <div id="coalition-spectrum-indicator" class="absolute w-1 h-8 bg-white shadow-lg rounded-full -top-1 border-2 border-gray-800" style="left: 50%; transform: translateX(-50%); display: none;"></div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-3 text-xs">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                            <span class="font-medium text-gray-700">Links</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-purple-500 mr-2"></div>
                                            <span class="font-medium text-gray-700">Midden</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                            <span class="font-medium text-gray-700">Rechts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Party data from PHP
    const partyData = <?php echo json_encode($parties); ?>;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            // Replace with a fallback image if loading fails
            this.src = 'https://i.ibb.co/kXL6rQ8/placeholder-profile.jpg';
            this.onerror = null; // Prevent infinite loop
        };
    });
    
    // Party buttons
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            const party = partyData[partyKey];
            
            // Fill modal with party data
            document.getElementById('party-modal-title').textContent = party.name;
            document.getElementById('party-modal-logo').src = party.logo;
            document.getElementById('party-modal-logo').alt = `${party.name} logo`;
            document.getElementById('party-modal-abbr').textContent = partyKey;
            document.getElementById('party-modal-name').textContent = party.name;
            document.getElementById('party-modal-leader').textContent = party.leader;
            document.getElementById('party-modal-leader-photo').src = party.leader_photo;
            document.getElementById('party-modal-leader-photo').alt = party.leader;
            document.getElementById('party-modal-description').textContent = party.description;
            document.getElementById('party-modal-seats').textContent = party.current_seats;
            document.getElementById('party-modal-polling').textContent = party.polling.seats;
            
            // Fill perspectives
            document.getElementById('party-modal-left-perspective').textContent = party.perspectives.left;
            document.getElementById('party-modal-right-perspective').textContent = party.perspectives.right;
            
            // Display polling trend
            const trendElement = document.getElementById('party-modal-polling-trend');
            const change = party.polling.change;
            const changeClass = change > 0 ? 'text-green-600' : (change < 0 ? 'text-red-600' : 'text-yellow-600');
            const changeIcon = change > 0 ? '↑' : (change < 0 ? '↓' : '→');
            const changeText = change > 0 ? `+${change}` : change;
            
            trendElement.className = `text-sm font-medium ${changeClass}`;
            trendElement.textContent = change !== 0 ? `Trend: ${changeIcon} ${changeText}` : 'Stabiel in peilingen';
            
            // Fill standpoints
            const standpointsContainer = document.getElementById('party-modal-standpoints');
            standpointsContainer.innerHTML = '';
            
            for (const [topic, standpoint] of Object.entries(party.standpoints)) {
                const standpointEl = document.createElement('div');
                standpointEl.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200';
                standpointEl.innerHTML = `
                    <h4 class="font-semibold text-gray-800 mb-2">${topic}</h4>
                    <p class="text-gray-600 text-sm">${standpoint}</p>
                `;
                standpointsContainer.appendChild(standpointEl);
            }
            
            // Show modal
            document.getElementById('party-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    });
    
    // Leader buttons
    document.querySelectorAll('.leader-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-leader');
            const party = partyData[partyKey];
            
            // Fill modal with leader data
            document.getElementById('leader-modal-title').textContent = party.leader;
            document.getElementById('leader-modal-photo').src = party.leader_photo;
            document.getElementById('leader-modal-photo').alt = `${party.leader} foto`;
            document.getElementById('leader-modal-party-logo').src = party.logo;
            document.getElementById('leader-modal-party-logo').alt = `${party.name} logo`;
            document.getElementById('leader-modal-party-name').textContent = party.name;
            document.getElementById('leader-modal-party-abbr').textContent = partyKey;
            document.getElementById('leader-modal-info').textContent = party.leader_info;
            
            // Show modal
            document.getElementById('leader-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    });
    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const partyModal = document.getElementById('party-modal');
        const leaderModal = document.getElementById('leader-modal');
        
        if (event.target === partyModal) {
            partyModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (event.target === leaderModal) {
            leaderModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
    
    // Sorting functionality
    document.getElementById('sortOption').addEventListener('change', function() {
        const sortMethod = this.value;
        const partyCards = Array.from(document.querySelectorAll('.party-card'));
        const partyGrid = document.querySelector('.grid');
        
        // Sort the cards
        partyCards.sort((a, b) => {
            const aPartyKey = a.querySelector('.party-btn').getAttribute('data-party');
            const bPartyKey = b.querySelector('.party-btn').getAttribute('data-party');
            
            if (sortMethod === 'name') {
                return aPartyKey.localeCompare(bPartyKey);
            } else if (sortMethod === 'seats') {
                return partyData[bPartyKey].current_seats - partyData[aPartyKey].current_seats;
            } else if (sortMethod === 'polling') {
                return partyData[bPartyKey].polling.seats - partyData[aPartyKey].polling.seats;
            }
            
            return 0;
        });
        
        // Remove existing cards
        partyCards.forEach(card => card.remove());
        
        // Add the sorted cards back
        partyCards.forEach(card => partyGrid.appendChild(card));
    });
    
    // Tab switching for chamber views
    const currentTab = document.getElementById('current-tab');
    const pollingTab = document.getElementById('polling-tab');
    const currentView = document.getElementById('current-view');
    const pollingView = document.getElementById('polling-view');
    
    currentTab.addEventListener('click', function() {
        currentTab.classList.add('text-primary', 'border-primary');
        currentTab.classList.remove('text-gray-500', 'border-transparent');
        
        pollingTab.classList.remove('text-primary', 'border-primary');
        pollingTab.classList.add('text-gray-500', 'border-transparent');
        
        currentView.classList.remove('hidden');
        pollingView.classList.add('hidden');
    });
    
    pollingTab.addEventListener('click', function() {
        pollingTab.classList.add('text-primary', 'border-primary');
        pollingTab.classList.remove('text-gray-500', 'border-transparent');
        
        currentTab.classList.remove('text-primary', 'border-primary');
        currentTab.classList.add('text-gray-500', 'border-transparent');
        
        pollingView.classList.remove('hidden');
        currentView.classList.add('hidden');
    });
    
    // Create realistic chamber visualization
    function createChamberVisualization() {
        const currentSeatsContainer = document.getElementById('current-seats-chamber');
        const pollingSeatsContainer = document.getElementById('polling-seats-chamber');
        
        // Clear containers
        currentSeatsContainer.innerHTML = '';
        pollingSeatsContainer.innerHTML = '';
        
        // Define the seat layout (rows and seats per row) - resembling the actual Tweede Kamer
        const chamberLayout = [
            6, 8, 10, 12, 14, 16, 16, 17, 17, 17, 17 // 150 seats total
        ];
        
        // Create all seats for current distribution
        createChamberSeats(currentSeatsContainer, chamberLayout, 'current');
        
        // Create all seats for polling distribution
        createChamberSeats(pollingSeatsContainer, chamberLayout, 'polling');
    }
    
    // Create visualization after DOM loaded
    createChamberVisualization();
    
    function createChamberSeats(container, layout, type) {
        let seatCount = 0;
        const totalSeats = 150;
        let partySeats = [];
        
        // Collect all parties with their seats (current or polling)
        for (const [partyKey, party] of Object.entries(partyData)) {
            const seatNum = type === 'current' ? party.current_seats : party.polling.seats;
            if (seatNum > 0) {
                partySeats.push({
                    party: partyKey,
                    count: seatNum,
                    color: getPartyColor(partyKey)
                });
            }
        }
        
        // Sort by seat count (descending) to place largest parties at the back
        partySeats.sort((a, b) => b.count - a.count);
        
        // Flatten into an array of all seats
        let allSeats = [];
        partySeats.forEach(partySeat => {
            for (let i = 0; i < partySeat.count; i++) {
                allSeats.push({
                    party: partySeat.party,
                    color: partySeat.color
                });
            }
        });
        
        // Add empty seats if needed
        while (allSeats.length < totalSeats) {
            allSeats.push({ party: 'empty', color: '#F3F4F6' });
        }
        
        // Cut off if too many seats (shouldn't happen but just in case)
        if (allSeats.length > totalSeats) {
            allSeats = allSeats.slice(0, totalSeats);
        }
        
        // Create rows of seats
        layout.forEach((seatsInRow, rowIndex) => {
            const row = document.createElement('div');
            row.className = 'flex justify-center items-center mb-1 gap-1 md:gap-1.5';
            
            for (let i = 0; i < seatsInRow; i++) {
                if (seatCount < totalSeats) {
                    const seat = document.createElement('div');
                    const seatData = allSeats[seatCount];
                    
                    seat.className = 'w-2.5 h-2.5 md:w-3 md:h-3 lg:w-3.5 lg:h-3.5 rounded-full border-2 border-white cursor-pointer relative shadow-lg z-10';
                    seat.style.backgroundColor = seatData.color;
                    seat.dataset.party = seatData.party;
                    
                    if (seatData.party !== 'empty') {
                        const partyInfo = partyData[seatData.party];
                        seat.setAttribute('title', `${seatData.party}: ${partyInfo.name}`);
                        
                        // Add tooltip content with Tailwind classes
                        const tooltip = document.createElement('div');
                        tooltip.className = 'chamber-seat-tooltip absolute bg-black bg-opacity-90 text-white px-3 py-2 rounded-lg text-xs whitespace-nowrap opacity-0 pointer-events-none z-50';
                        tooltip.innerHTML = `
                            <div class="font-bold">${seatData.party}</div>
                            <div class="text-xs">${partyInfo.name}</div>
                        `;
                        seat.appendChild(tooltip);
                        
                        // Event listeners for tooltip with Tailwind state classes
                        seat.addEventListener('mouseenter', function() {
                            this.querySelector('.chamber-seat-tooltip').classList.remove('opacity-0');
                            this.querySelector('.chamber-seat-tooltip').classList.add('opacity-100');
                        });
                        
                        seat.addEventListener('mouseleave', function() {
                            this.querySelector('.chamber-seat-tooltip').classList.remove('opacity-100');
                            this.querySelector('.chamber-seat-tooltip').classList.add('opacity-0');
                        });
                    }
                    
                    row.appendChild(seat);
                    seatCount++;
                }
            }
            
            container.appendChild(row);
        });
    }
    
    // REMOVED: highlightPartySeats and resetHighlights functions
    // These functions are no longer needed since we removed the hover highlighting functionality
    
    // Create realistic chamber visualization
    function createChamberVisualization() {
        const currentSeatsContainer = document.getElementById('current-seats-chamber');
        const pollingSeatsContainer = document.getElementById('polling-seats-chamber');
        
        // Clear containers
        currentSeatsContainer.innerHTML = '';
        pollingSeatsContainer.innerHTML = '';
        
        // Define the seat layout (rows and seats per row) - resembling the actual Tweede Kamer
        const chamberLayout = [
            6, 8, 10, 12, 14, 16, 16, 17, 17, 17, 17 // 150 seats total
        ];
        
        // Create all seats for current distribution
        createChamberSeats(currentSeatsContainer, chamberLayout, 'current');
        
        // Create all seats for polling distribution
        createChamberSeats(pollingSeatsContainer, chamberLayout, 'polling');
    }
    
    // Assign colors to parties
    function getPartyColor(partyKey) {
        const partyColors = {
            'PVV': '#0078D7', 
            'VVD': '#FF9900',
            'NSC': '#4D7F78',
            'BBB': '#006633',
            'GL-PvdA': '#008800',
            'D66': '#00B13C',
            'SP': '#EE0000',
            'PvdD': '#007E3A',
            'CDA': '#1E8449',
            'JA21': '#0066CC',
            'SGP': '#FF6600', 
            'FvD': '#811E1E',
            'DENK': '#00b7b2',
            'Volt': '#502379',
            'CU': '#00AEEF'
        };
        
        return partyColors[partyKey] || '#A0A0A0';
    }
});

// Helper function for PHP to use the same color mapping
<?php
function getPartyColor($partyKey) {
    $partyColors = [
        'PVV' => '#0078D7',
        'VVD' => '#FF9900',
        'NSC' => '#4D7F78',
        'BBB' => '#006633',
        'GL-PvdA' => '#008800',
        'D66' => '#00B13C',
        'SP' => '#EE0000',
        'PvdD' => '#007E3A',
        'CDA' => '#1E8449',
        'JA21' => '#0066CC',
        'SGP' => '#FF6600',
        'FvD' => '#811E1E',
        'DENK' => '#00b7b2',
        'Volt' => '#502379',
        'CU' => '#00AEEF'
    ];
    
    return isset($partyColors[$partyKey]) ? $partyColors[$partyKey] : '#A0A0A0';
}

// New helper function to adjust color opacity for standpoint badges
function adjustColorOpacity($hex, $opacity) {
    // Convert hex to rgb
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "rgba($r, $g, $b, $opacity)";
}

// New helper function to adjust color brightness
function adjustColorBrightness($hex, $steps) {
    // Convert hex to rgb
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    
    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    // Convert back to hex
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
?>

// Modern Coalition Maker JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Coalition Maker with enhanced features
    const CoalitionMaker = {
        parties: <?php echo json_encode($parties); ?>,
        currentView: 'current',
        coalition: [],
        
        // Political spectrum positions (0 = far right, 100 = far left)
        partyPositions: {
            'PVV': 15, 'VVD': 30, 'NSC': 55, 'BBB': 40, 'GL-PvdA': 85,
            'D66': 70, 'SP': 90, 'PvdD': 80, 'CDA': 50, 'JA21': 20,
            'SGP': 25, 'FvD': 10, 'DENK': 75, 'Volt': 65, 'CU': 60
        },
        
        init() {
            this.setupEventListeners();
            this.generateAvailableParties();
            this.setupDragAndDrop();
            this.updateAnalysis();
            console.log('🚀 Modern Coalition Maker geïnitialiseerd');
        },
        
        setupEventListeners() {
            // Tab switching with smooth animations
            document.getElementById('coalition-current-tab').addEventListener('click', () => {
                this.switchView('current');
            });
            
            document.getElementById('coalition-polling-tab').addEventListener('click', () => {
                this.switchView('polling');
            });
            
            // Enhanced action buttons
            document.getElementById('clear-coalition').addEventListener('click', () => {
                this.clearCoalition();
            });
            
            document.getElementById('shuffle-coalition').addEventListener('click', () => {
                this.shuffleRandomCoalition();
            });
        },
        
        switchView(view) {
            this.currentView = view;
            
            // Update tab styling with smooth transitions
            const currentTab = document.getElementById('coalition-current-tab');
            const pollingTab = document.getElementById('coalition-polling-tab');
            
            if (view === 'current') {
                currentTab.classList.add('bg-white/30', 'backdrop-blur-sm');
                currentTab.classList.remove('text-white/70');
                pollingTab.classList.remove('bg-white/30', 'backdrop-blur-sm');
                pollingTab.classList.add('text-white/70');
            } else {
                pollingTab.classList.add('bg-white/30', 'backdrop-blur-sm');
                pollingTab.classList.remove('text-white/70');
                currentTab.classList.remove('bg-white/30', 'backdrop-blur-sm');
                currentTab.classList.add('text-white/70');
            }
            
            this.clearCoalition();
            this.generateAvailableParties();
        },
        
        generateAvailableParties() {
            const container = document.getElementById('available-parties');
            container.innerHTML = '';
            
            // Sort parties by seats
            const sortedParties = Object.entries(this.parties).sort((a, b) => {
                const aSeats = this.currentView === 'current' ? a[1].current_seats : a[1].polling.seats;
                const bSeats = this.currentView === 'current' ? b[1].current_seats : b[1].polling.seats;
                return bSeats - aSeats;
            });
            
            let totalParties = 0;
            
            sortedParties.forEach(([partyKey, party]) => {
                const seats = this.currentView === 'current' ? party.current_seats : party.polling.seats;
                
                if (seats > 0) {
                    totalParties++;
                    const card = this.createPartyCard(partyKey, party, seats);
                    container.appendChild(card);
                }
            });
            
            // Update counter with animation
            this.animateCounter('available-count', totalParties);
        },
        
        createPartyCard(partyKey, party, seats) {
            const card = document.createElement('div');
            card.className = 'party-card group bg-white rounded-xl shadow-xl cursor-move border border-gray-300';
            card.setAttribute('draggable', 'true');
            card.setAttribute('data-party', partyKey);
            card.setAttribute('data-seats', seats);
            
            const color = this.getPartyColor(partyKey);
            const change = party.polling?.change || 0;
            const changeIcon = change > 0 ? '↗' : change < 0 ? '↘' : '→';
            const changeClass = change > 0 ? 'text-green-600' : change < 0 ? 'text-red-600' : 'text-gray-500';
            
            card.innerHTML = `
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white shadow-sm border-2" style="border-color: ${color}">
                                    <img src="${party.logo}" alt="${party.name}" class="w-full h-full object-contain p-1">
                                </div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full" style="background-color: ${color}"></div>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">${partyKey}</h4>
                                <p class="text-xs text-gray-600 truncate max-w-[100px]">${party.name}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-gray-200 text-gray-800 text-sm font-bold px-3 py-1 rounded-lg">
                                ${seats}
                            </div>
                            ${this.currentView === 'polling' && change !== 0 ? `
                                <div class="text-xs mt-1 ${changeClass} font-medium">
                                    ${changeIcon} ${change > 0 ? '+' : ''}${change}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="text-xs text-gray-500 line-clamp-2 mb-3">
                        ${party.description}
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            👤 ${party.leader}
                        </div>
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            <div class="w-2 h-2 rounded-full" style="background-color: ${color}"></div>
                        </div>
                    </div>
                </div>
            `;
            
            return card;
        },
        
        setupDragAndDrop() {
            // Enhanced drag and drop with visual feedback
            document.addEventListener('dragstart', (e) => {
                if (e.target.classList.contains('party-card')) {
                    e.dataTransfer.setData('text/plain', e.target.getAttribute('data-party'));
                    e.target.style.opacity = '0.5';
                    e.target.style.transform = 'rotate(5deg)';
                }
            });
            
            document.addEventListener('dragend', (e) => {
                if (e.target.classList.contains('party-card')) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'none';
                }
            });
            
            const coalitionContainer = document.getElementById('selected-coalition');
            
            if (coalitionContainer) {
                coalitionContainer.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    coalitionContainer.classList.add('border-emerald-500', 'bg-emerald-50');
                    coalitionContainer.classList.remove('border-emerald-300');
                });
                
                coalitionContainer.addEventListener('dragleave', () => {
                    coalitionContainer.classList.remove('border-emerald-500', 'bg-emerald-50');
                    coalitionContainer.classList.add('border-emerald-300');
                });
                
                coalitionContainer.addEventListener('drop', (e) => {
                    e.preventDefault();
                    coalitionContainer.classList.remove('border-emerald-500', 'bg-emerald-50');
                    coalitionContainer.classList.add('border-emerald-300');
                    
                    const partyKey = e.dataTransfer.getData('text/plain');
                    if (partyKey && !this.coalition.includes(partyKey)) {
                        this.addToCoalition(partyKey);
                    }
                });
            }
            
            // Remove party from coalition when clicked
            document.addEventListener('click', (e) => {
                if (e.target.closest('.remove-party')) {
                    const card = e.target.closest('.party-card');
                    const partyKey = card.getAttribute('data-party');
                    this.removeFromCoalition(partyKey);
                }
            });
        },
        
        updateAnalysis() {
            const totalSeats = this.calculateCoalitionSeats(this.coalition, this.currentView);
            const percentage = Math.round((totalSeats / 150) * 100);
            const hasMajority = totalSeats >= 76;
            
            // Update seats counter with animation
            this.animateCounter('coalition-seats', totalSeats);
            
            // Update progress bar
            const progressBar = document.getElementById('coalition-progress');
            if (progressBar) {
                progressBar.style.width = `${Math.min(100, (totalSeats / 150) * 100)}%`;
            }
            
            // Update status
            const statusEl = document.getElementById('coalition-status');
            if (statusEl) {
                if (totalSeats === 0) {
                    statusEl.textContent = 'Geen coalitie gevormd';
                    statusEl.className = 'text-center py-4 rounded-xl bg-gray-100 text-gray-600 font-semibold';
                } else if (hasMajority) {
                    statusEl.textContent = '🎉 Meerderheid behaald!';
                    statusEl.className = 'text-center py-4 rounded-xl bg-green-100 text-green-800 font-semibold';
                } else {
                    const needed = 76 - totalSeats;
                    statusEl.textContent = `⚠️ ${needed} zetels tekort`;
                    statusEl.className = 'text-center py-4 rounded-xl bg-yellow-100 text-yellow-800 font-semibold';
                }
            }
            
            // Update coalition stats
            const statsEl = document.getElementById('coalition-stats');
            if (statsEl) {
                if (totalSeats > 0) {
                    statsEl.classList.remove('hidden');
                    const partiesCountEl = document.getElementById('coalition-parties-count');
                    const percentageEl = document.getElementById('coalition-percentage');
                    if (partiesCountEl) partiesCountEl.textContent = this.coalition.length;
                    if (percentageEl) percentageEl.textContent = `${percentage}%`;
                } else {
                    statsEl.classList.add('hidden');
                }
            }
            
            // Update political spectrum
            this.updatePoliticalSpectrum();
            
            // Update coalition display
            this.updateCoalitionDisplay();
        },
        
        calculateCoalitionSeats(coalition, view) {
            return coalition.reduce((total, partyKey) => {
                const party = this.parties[partyKey];
                return total + (view === 'current' ? party.current_seats : party.polling.seats);
            }, 0);
        },
        
        clearCoalition() {
            this.coalition = [];
            this.updateAnalysis();
        },
        
        addToCoalition(partyKey) {
            if (this.coalition.includes(partyKey)) return;
            
            this.coalition.push(partyKey);
            this.updateCoalitionDisplay();
            this.updateAnalysis();
            this.removeFromAvailable(partyKey);
        },
        
        removeFromCoalition(partyKey) {
            this.coalition = this.coalition.filter(p => p !== partyKey);
            this.updateCoalitionDisplay();
            this.updateAnalysis();
            this.generateAvailableParties(); // Refresh available parties
        },
        
        updateCoalitionDisplay() {
            const container = document.getElementById('selected-coalition');
            if (!container) return;
            
            if (this.coalition.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                        <div class="bg-emerald-100 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium mb-2">Bouw je coalitie</p>
                        <p class="text-gray-400 text-sm">Sleep partijen hierheen om te beginnen</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = `<div class="space-y-3 p-4"></div>`;
            const coalitionList = container.querySelector('div');
            
            this.coalition.forEach(partyKey => {
                const party = this.parties[partyKey];
                const seats = this.currentView === 'current' ? party.current_seats : party.polling.seats;
                const card = this.createCoalitionPartyCard(partyKey, party, seats);
                coalitionList.appendChild(card);
            });
        },
        
        createCoalitionPartyCard(partyKey, party, seats) {
            const card = document.createElement('div');
            const color = this.getPartyColor(partyKey);
            
            card.className = 'party-card bg-white rounded-lg shadow-md border-l-4';
            card.style.borderLeftColor = color;
            card.setAttribute('data-party', partyKey);
            
            card.innerHTML = `
                <div class="p-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-white border" style="border-color: ${color}">
                            <img src="${party.logo}" alt="${party.name}" class="w-full h-full object-contain p-1">
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-900 text-sm">${partyKey}</h5>
                            <p class="text-xs text-gray-600">${seats} zetels</p>
                        </div>
                    </div>
                    <button class="remove-party text-red-500 p-1 rounded-full bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            return card;
        },
        
        updatePoliticalSpectrum() {
            const indicator = document.getElementById('coalition-spectrum-indicator');
            
            if (!indicator || this.coalition.length === 0) {
                if (indicator) indicator.style.display = 'none';
                return;
            }
            
            let totalPosition = 0;
            let totalSeats = 0;
            
            this.coalition.forEach(partyKey => {
                const seats = this.currentView === 'current' ? 
                    this.parties[partyKey].current_seats : 
                    this.parties[partyKey].polling.seats;
                totalPosition += this.partyPositions[partyKey] * seats;
                totalSeats += seats;
            });
            
            const avgPosition = totalPosition / totalSeats;
            indicator.style.left = `${avgPosition}%`;
            indicator.style.display = 'block';
        },
        
        removeFromAvailable(partyKey) {
            const partyCard = document.querySelector(`#available-parties .party-card[data-party="${partyKey}"]`);
            if (partyCard) {
                partyCard.remove();
            }
        },
        
        shuffleRandomCoalition() {
            this.clearCoalition();
            
            // Get available parties with seats
            const availableParties = Object.keys(this.parties).filter(key => {
                const seats = this.currentView === 'current' ? 
                    this.parties[key].current_seats : 
                    this.parties[key].polling.seats;
                return seats > 0;
            });
            
            // Shuffle and take 3-5 parties
            const shuffled = availableParties.sort(() => Math.random() - 0.5);
            const selectedParties = shuffled.slice(0, Math.floor(Math.random() * 3) + 3);
            
            selectedParties.forEach(party => this.addToCoalition(party));
        },
        
        applySuggestion(parties) {
            this.clearCoalition();
            parties.forEach(party => this.addToCoalition(party));
        },
        
        animateCounter(elementId, targetValue) {
            const element = document.getElementById(elementId);
            const startValue = parseInt(element.textContent) || 0;
            const duration = 500;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
                element.textContent = currentValue;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        getPartyColor(partyKey) {
            const colors = {
                'PVV': '#0078D7', 'VVD': '#FF9900', 'NSC': '#4D7F78', 'BBB': '#006633',
                'GL-PvdA': '#008800', 'D66': '#00B13C', 'SP': '#EE0000', 'PvdD': '#007E3A',
                'CDA': '#1E8449', 'JA21': '#0066CC', 'SGP': '#FF6600', 'FvD': '#811E1E',
                'DENK': '#00b7b2', 'Volt': '#502379', 'CU': '#00AEEF'
            };
            return colors[partyKey] || '#A0A0A0';
        }
    };
    
    // Initialize the Coalition Maker
    CoalitionMaker.init();
    
    // Additional party functionality (existing code)
    const partyData = <?php echo json_encode($parties); ?>;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = 'https://i.ibb.co/kXL6rQ8/placeholder-profile.jpg';
            this.onerror = null;
        };
    });
    
    // Party buttons for modals
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            const party = partyData[partyKey];
            
            // Fill modal with party data
            document.getElementById('party-modal-title').textContent = party.name;
            document.getElementById('party-modal-logo').src = party.logo;
            document.getElementById('party-modal-logo').alt = `${party.name} logo`;
            document.getElementById('party-modal-abbr').textContent = partyKey;
            document.getElementById('party-modal-name').textContent = party.name;
            document.getElementById('party-modal-leader').textContent = party.leader;
            document.getElementById('party-modal-leader-photo').src = party.leader_photo;
            document.getElementById('party-modal-leader-photo').alt = party.leader;
            document.getElementById('party-modal-description').textContent = party.description;
            document.getElementById('party-modal-seats').textContent = party.current_seats;
            document.getElementById('party-modal-polling').textContent = party.polling.seats;
            
            // Fill perspectives
            document.getElementById('party-modal-left-perspective').textContent = party.perspectives.left;
            document.getElementById('party-modal-right-perspective').textContent = party.perspectives.right;
            
            // Display polling trend
            const trendElement = document.getElementById('party-modal-polling-trend');
            const change = party.polling.change;
            const changeClass = change > 0 ? 'text-green-600' : (change < 0 ? 'text-red-600' : 'text-yellow-600');
            const changeIcon = change > 0 ? '↑' : (change < 0 ? '↓' : '→');
            const changeText = change > 0 ? `+${change}` : change;
            
            trendElement.className = `text-sm font-medium ${changeClass}`;
            trendElement.textContent = change !== 0 ? `Trend: ${changeIcon} ${changeText}` : 'Stabiel in peilingen';
            
            // Fill standpoints
            const standpointsContainer = document.getElementById('party-modal-standpoints');
            standpointsContainer.innerHTML = '';
            
            for (const [topic, standpoint] of Object.entries(party.standpoints)) {
                const standpointEl = document.createElement('div');
                standpointEl.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200';
                standpointEl.innerHTML = `
                    <h4 class="font-semibold text-gray-800 mb-2">${topic}</h4>
                    <p class="text-gray-600 text-sm">${standpoint}</p>
                `;
                standpointsContainer.appendChild(standpointEl);
            }
            
            // Show modal
            document.getElementById('party-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Leader buttons for modals
    document.querySelectorAll('.leader-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-leader');
            const party = partyData[partyKey];
            
            // Fill modal with leader data
            document.getElementById('leader-modal-title').textContent = party.leader;
            document.getElementById('leader-modal-photo').src = party.leader_photo;
            document.getElementById('leader-modal-photo').alt = `${party.leader} foto`;
            document.getElementById('leader-modal-party-logo').src = party.logo;
            document.getElementById('leader-modal-party-logo').alt = `${party.name} logo`;
            document.getElementById('leader-modal-party-name').textContent = party.name;
            document.getElementById('leader-modal-party-abbr').textContent = partyKey;
            document.getElementById('leader-modal-info').textContent = party.leader_info;
            
            // Show modal
            document.getElementById('leader-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = '';
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const partyModal = document.getElementById('party-modal');
        const leaderModal = document.getElementById('leader-modal');
        
        if (event.target === partyModal) {
            partyModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (event.target === leaderModal) {
            leaderModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});
</script>

<style>
/* Modern Coalition Maker Styling */

/* Enhanced scrollbar styling */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

/* Text truncation utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced Party Card Animation */
.party-card {
    position: relative;
    backface-visibility: hidden;
}

.party-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    opacity: 0;
    pointer-events: none;
    z-index: 1;
}

.party-card:active {
    
}

/* Drag and Drop Visual Feedback */
.party-card[draggable="true"]:active {
    opacity: 0.8;
    z-index: 1000;
}

/* Coalition Drop Zone Enhanced Styling */
#selected-coalition {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
}

#selected-coalition.bg-emerald-50 {
    background-color: rgba(16, 185, 129, 0.1);
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3), inset 0 0 20px rgba(16, 185, 129, 0.1);
}

/* Progress Bar Enhanced Animation */
#coalition-progress {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    background-size: 200% 100%;
    position: relative;
    overflow: hidden;
}

/* Chamber Visualization Styling */
.chamber {
    width: 100%;
    height: 300px;
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 50% 50% 0 0;
    overflow: hidden;
    border: 3px solid #cbd5e1;
    box-shadow: inset 0 4px 6px rgba(0, 0, 0, 0.1);
}

.chamber-grid {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    padding: 20px;
}

.chamber-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 4px;
    gap: 3px;
}

.chamber-seat {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.8);
    cursor: pointer;
    position: relative;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.chamber-seat.highlight {
    border: 2px solid #fbbf24;
    box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.3), 0 4px 12px rgba(0, 0, 0, 0.4);
    z-index: 20;
}

.chamber-seat.dim {
    opacity: 0.3;
}

.seat-tooltip {
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    z-index: 100;
}

.seat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
}

.chamber-seat.active .seat-tooltip {
    opacity: 1;
}

/* Responsive adjustments for chamber */
@media (max-width: 768px) {
    .chamber {
        height: 200px;
    }
    
    .chamber-seat {
        width: 8px;
        height: 8px;
    }
    
    .chamber-row {
        gap: 2px;
        margin-bottom: 2px;
    }
    
    .chamber-grid {
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .chamber {
        height: 150px;
    }
    
    .chamber-seat {
        width: 6px;
        height: 6px;
    }
    
    .chamber-row {
        gap: 1px;
        margin-bottom: 1px;
    }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Alleen essentiële custom CSS die niet door Tailwind wordt gedekt */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.bg-shimmer {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

.chamber-semicircle {
    border-radius: 50% 50% 0 0;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
}

.chamber-seat-tooltip {
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
}

.chamber-seat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
}

.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
</style>

<?php include_once BASE_PATH . '/views/templates/footer.php'; ?>
</div>
</body>
</html>