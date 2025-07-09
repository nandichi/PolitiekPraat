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
        'polling' => ['seats' => 29, 'percentage' => 19.3, 'change' => -8],
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
        'polling' => ['seats' => 20, 'percentage' => 13.3, 'change' => -4],
        'perspectives' => [
            'left' => 'De VVD steunt praktische klimaatmaatregelen en energietransitieplannen die de industrie niet vervreemden, waardoor een duurzamere economie mogelijk wordt zonder massaal banenverlies.',
            'right' => 'De VVD bevordert economische groei, lagere belastingen en minder bureaucratie, wat ondernemerschap stimuleert en de markteconomie versterkt.'
        ]
    ],
    'NSC' => [
        'name' => 'Nieuw Sociaal Contract',
        'leader' => 'Nicolien van Vroonhoven',
        'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
        'leader_photo' => 'https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg',
        'description' => 'NSC is een baanbrekende partij die staat voor transparantie, eerlijk bestuur en een fundamentele herwaardering van de democratische instituties. Zij leggen de nadruk op integriteit, een verantwoordelijke overheid en het herstel van het vertrouwen in de politiek. Met een duidelijke agenda gericht op grondrechten, tegenmacht en publieke participatie wil NSC de politiek opnieuw in dienst stellen van de burger.',
        'leader_info' => 'Nicolien van Vroonhoven, de huidige leider van NSC, zet het werk van oprichter Pieter Omtzigt voort met dezelfde toewijding aan transparantie en integriteit. Haar reputatie als een compromisloze waarheidsvinder en haar scherpe blik op systemische misstanden maken haar tot een symbool van integriteit en rechtvaardigheid. Zij streeft ernaar de politiek te zuiveren van gevestigde belangen en de macht terug te geven aan de gewone burger.',
        'standpoints' => [
            'Immigratie' => 'Een doordacht asielbeleid dat zowel veiligheid als humanitaire zorg waarborgt',
            'Klimaat' => 'Evenwichtige aanpak waarbij zowel klimaat als economie belangrijk zijn',
            'Zorg' => 'Overweegt aanpassingen in plaats van volledige afschaffing van het eigen risico',
            'Energie' => 'Open voor kernenergie als het bijdraagt aan een stabiele energiemix'
        ],
        'current_seats' => 20,
        'polling' => ['seats' => 0, 'percentage' => 0.0, 'change' => -20],
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
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => -3],
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
        'polling' => ['seats' => 9, 'percentage' => 6.0, 'change' => 0],
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
        'polling' => ['seats' => 7, 'percentage' => 4.7, 'change' => 2],
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
        'current_seats' => 3,
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +1],
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
        'polling' => ['seats' => 21, 'percentage' => 14.0, 'change' => +16],
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
        'current_seats' => 1,
        'polling' => ['seats' => 10, 'percentage' => 6.7, 'change' => +9],
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
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +1],
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
        'current_seats' => 3,
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +1],
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
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => +1],
        'perspectives' => [
            'left' => 'Volts pan-Europese benadering pakt transnationale uitdagingen zoals klimaatverandering en ongelijkheid aan door gecoördineerde actie.',
            'right' => 'Hun nadruk op digitale transformatie en innovatie bevordert economische concurrentiekracht en modernisering.'
        ]
    ],
    'CU' => [
        'name' => 'ChristenUnie',
        'leader' => 'Mirjam Bikker',
        'logo' => 'https://logo.clearbit.com/christenunie.nl',
        'leader_photo' => 'https://i.ibb.co/wh3wwQ66/Bikker.jpg',
        'description' => 'De ChristenUnie is een sociaal-christelijke partij die geloof en politiek combineert met een sterke focus op duurzaamheid, sociale rechtvaardigheid en gezinswaarden. Ze streven naar een zorgzame samenleving waarin kwetsbare groepen worden beschermd en waarin rentmeesterschap voor de schepping centraal staat. De partij zoekt naar praktische oplossingen die zowel ethisch verantwoord als maatschappelijk relevant zijn.',
        'leader_info' => 'Mirjam Bikker, partijleider sinds 2023, staat bekend om haar doortastende en constructieve aanpak. Met haar achtergrond als jurist en haar ervaring in de Eerste en Tweede Kamer, combineert ze diepgaande kennis met een warm hart voor sociale thema\'s. Haar leiderschap richt zich op verbinding en het bouwen van bruggen, zowel binnen als buiten de politiek.',
        'standpoints' => [
            'Immigratie' => 'Een humaan asielbeleid met nadruk op veilige opvang en integratie',
            'Klimaat' => 'Ambitieuze klimaatdoelen vanuit rentmeesterschap',
            'Zorg' => 'Verlaging van het eigen risico voor kwetsbare groepen',
            'Energie' => 'Inzet op duurzame energie, kritisch op kernenergie'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => 0],
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
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-secondary py-24 overflow-hidden">
        <!-- Subtle background elements -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.03\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1.5\" fill=\"white\"/%3E%3Ccircle cx=\"0\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"60\" cy=\"30\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"0\" r=\"1\" fill=\"white\"/%3E%3Ccircle cx=\"30\" cy=\"60\" r=\"1\" fill=\"white\"/%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        
        <!-- Ambient light effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- Header badge -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <div class="w-2 h-2 bg-secondary-light rounded-full mr-3 animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Live politieke data</span>
                    </div>
                </div>
                
                <!-- Main title -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                        Nederlandse
                        <span class="block bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                            Politieke Partijen
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                        Ontdek alle standpunten, lijsttrekkers en actuele peilingen
                    </p>
                </div>
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2"><?php echo count($parties); ?></div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Partijen</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">150</div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Zetels</div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">2025</div>
                            <div class="text-blue-200 text-sm uppercase tracking-wider">Verkiezingsjaar</div>
                        </div>
                    </div>
                </div>
                
                <!-- Polling Source Attribution -->
                <div class="mt-8 flex justify-center">
                    <div class="inline-flex items-center px-4 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 transition duration-300 hover:bg-white/20">
                        <svg class="w-4 h-4 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-white text-sm font-medium">Peilingen bronnen:</span>
                        <div class="flex items-center ml-2 space-x-2">
                            <span class="text-white text-sm font-bold"><a href="https://maurice.nl/" target="_blank" class="hover:text-secondary-light transition-colors duration-300">Maurice de Hond</a></span>
                            <div class="w-1 h-1 bg-white rounded-full"></div> 
                            <span class="text-white text-sm font-bold"><a href="https://home.noties.nl/peil/" target="_blank" class="hover:text-secondary-light transition-colors duration-300">Peil.nl</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>

    <!-- Partijen Overzicht Sectie - Vergelijkbaar met peilingen -->
    <section class="py-32 bg-gradient-to-b from-white via-slate-50 to-white relative overflow-hidden">
        <!-- Premium decoratieve achtergrond -->
        <div class="absolute inset-0">
            <!-- Animated gradient spheres -->
            <div class="absolute top-20 left-10 w-96 h-96 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-60 right-16 w-80 h-80 bg-gradient-to-br from-secondary/8 to-primary-light/8 rounded-full blur-2xl animate-float-delayed"></div>
            <div class="absolute bottom-32 left-1/3 w-72 h-72 bg-gradient-to-br from-primary-light/6 to-secondary-light/6 rounded-full blur-3xl animate-pulse-slow"></div>
            
            <!-- Geometric pattern overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg opacity=\"0.04\"%3E%3Cpath d=\"M20 0L20 40M0 20L40 20\" stroke=\"%23334155\" stroke-width=\"1\"%3E%3C/path%3E%3Ccircle cx=\"20\" cy=\"20\" r=\"2\" fill=\"%23334155\"%3E%3C/circle%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
            
            <!-- Floating elements -->
            <div class="absolute top-40 right-1/4 w-4 h-4 bg-primary/20 rounded-full animate-bounce"></div>
            <div class="absolute bottom-1/3 left-1/4 w-6 h-6 bg-secondary/20 rounded-full animate-bounce animation-delay-75"></div>
            <div class="absolute top-2/3 right-1/3 w-3 h-3 bg-primary-light/20 rounded-full animate-bounce animation-delay-150"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Uniforme header sectie -->
            <div class="text-center mb-24 relative" data-aos="fade-up" data-aos-once="true">
                <!-- Achtergrond tekst -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                    <span class="text-[120px] sm:text-[160px] lg:text-[200px] xl:text-[280px] font-black text-slate-100/30 select-none tracking-wider transform -rotate-2">PARTIJEN</span>
                </div>
                
                <!-- Main content -->
                <div class="relative z-10 space-y-8">
                    <!-- Hoofdtitel -->
                    <div class="space-y-6">
                        <h2 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-slate-900 leading-tight tracking-tight">
                            <span class="block mb-2">Partijen</span>
                            <span class="bg-gradient-to-r from-primary-dark via-primary to-secondary bg-clip-text text-transparent animate-gradient bg-size-200">
                                Ranglijst
                            </span>
                        </h2>
                        
                        <!-- Decoratieve lijn systeem -->
                        <div class="flex items-center justify-center space-x-6 mt-8">
                            <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-primary to-secondary"></div>
                            <div class="relative">
                                <div class="w-4 h-4 bg-primary rounded-full animate-pulse"></div>
                                <div class="absolute inset-0 w-4 h-4 bg-primary rounded-full animate-ping opacity-30"></div>
                            </div>
                            <div class="w-32 h-0.5 bg-gradient-to-r from-secondary via-primary-light to-secondary-light"></div>
                            <div class="relative">
                                <div class="w-4 h-4 bg-secondary rounded-full animate-pulse animation-delay-300"></div>
                                <div class="absolute inset-0 w-4 h-4 bg-secondary rounded-full animate-ping opacity-30 animation-delay-300"></div>
                            </div>
                            <div class="w-16 h-0.5 bg-gradient-to-r from-secondary-light via-primary-light to-transparent"></div>
                        </div>
                    </div>
            
                    <!-- Subtitel -->
                    <p class="text-xl sm:text-2xl lg:text-3xl text-slate-600 max-w-4xl mx-auto leading-relaxed font-light">
                        Overzicht van alle <span class="font-semibold text-primary">Nederlandse partijen</span> gesorteerd op <span class="font-semibold text-secondary">huidige zetels</span> en peilingen
                    </p>
                    
                    <!-- Status indicator en AI knop -->
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="inline-flex items-center px-6 py-3 bg-slate-900/5 backdrop-blur-sm rounded-full border border-slate-200/50 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-600">Actuele stand: Tweede Kamer 2023-2027</span>
                            </div>
                        </div>

                        <!-- AI Analyse Knop -->
                        <button id="ai-analysis-btn" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-white/20">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>AI Peiling Analyse</span>
                            <div class="ml-2 w-1 h-1 bg-white rounded-full animate-ping"></div>
                        </button>
                    </div>
                </div>
            </div>

            <?php
            // Sorteer partijen op huidige zetels (aflopend)
            $sortedPartiesCurrentSeats = $parties;
            uasort($sortedPartiesCurrentSeats, function($a, $b) {
                return $b['current_seats'] - $a['current_seats'];
            });

            // Sorteer partijen op peiling zetels (aflopend)
            $sortedPartiesPolling = $parties;
            uasort($sortedPartiesPolling, function($a, $b) {
                return $b['polling']['seats'] - $a['polling']['seats'];
            });
            ?>

            <!-- Partijen data -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-16 items-start">
                <!-- Linker kolom: Gecombineerde Zetelverdeling (spans 2 columns) -->
                <div class="xl:col-span-2 relative" data-aos="fade-right">
                    <div class="peiling-card group relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-500">
                        <!-- Animated background gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-white to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <!-- Card header -->
                        <div class="relative z-10 p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">Zetelverdeling</h3>
                                    <p class="text-slate-600 font-medium">Peilingen vs. Huidige zetels</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-slate-600">Live data</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gecombineerde partijen tabel -->
                            <div class="overflow-x-auto -mx-2">
                                <table class="w-full border-collapse text-sm">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/50">
                                            <th class="py-4 px-4 text-left font-bold text-slate-700 tracking-wide">Partij</th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Peiling</span>
                                                <span class="sm:hidden">Peiling</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Huidige zetels</span>
                                                <span class="sm:hidden">Huidig</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Verschil</span>
                                                <span class="sm:hidden">+/-</span>
                                            </th>
                                            <th class="py-4 px-3 text-center font-bold text-slate-700 tracking-wide">
                                                <span class="hidden sm:inline">Lijsttrekker</span>
                                                <span class="sm:hidden">Leider</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sortedPartiesCurrentSeats as $partyKey => $party): 
                                            $change = $party['polling']['seats'] - $party['current_seats'];
                                            $changeClass = $change > 0 ? 'peiling-change-badge bg-emerald-100 text-emerald-800 border-emerald-200' : 
                                                          ($change < 0 ? 'peiling-change-badge bg-red-100 text-red-800 border-red-200' : 
                                                          'peiling-change-badge bg-slate-100 text-slate-600 border-slate-200');
                                            $changeSymbol = $change > 0 ? '+' . $change : ($change < 0 ? $change : '0');
                                        ?>
                                        <tr class="peiling-table-row group border-b border-slate-100/50 hover:bg-gradient-to-r hover:from-primary/5 hover:via-white hover:to-secondary/5 transition-all duration-300">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center group">
                                                    <div class="peiling-party-indicator relative w-4 h-4 rounded-full mr-3 transition-transform duration-300 group-hover:scale-110" 
                                                         style="background-color: <?php echo getPartyColor($partyKey); ?>">
                                                        <div class="absolute inset-0 rounded-full animate-ping opacity-0 group-hover:opacity-30 transition-opacity duration-300" 
                                                             style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-900 group-hover:text-slate-800 transition-colors duration-300"><?php echo $partyKey; ?></span>
                                                        <br>
                                                        <span class="text-xs text-slate-500 hidden sm:inline"><?php echo $party['name']; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <div class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl text-sm shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 border border-blue-400/20">
                                                    <?php echo $party['polling']['seats']; ?>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <div class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-800 text-white font-bold rounded-xl text-sm shadow-md hover:shadow-lg hover:from-slate-800 hover:to-slate-900 transition-all duration-300 border border-slate-600/20">
                                                    <?php echo $party['current_seats']; ?>
                                                </div>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <span class="<?php echo $changeClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border transition-all duration-300">
                                                    <?php echo $changeSymbol; ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <span class="text-sm font-medium text-slate-600"><?php echo explode(' ', $party['leader'])[0]; ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Footer informatie -->
                            <div class="mt-8 pt-6 border-t border-slate-200/50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-600">Peiling zetels</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-slate-900 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-600">Huidige zetels (2023)</span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-slate-500 font-medium">
                                        <span>Totaal: 150 zetels</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shimmer effect -->
                        <div class="absolute inset-0 -top-full bg-gradient-to-b from-transparent via-white/20 to-transparent transform skew-y-12 group-hover:animate-shimmer"></div>
                    </div>
                </div>
                
                <!-- Rechter kolom: Grootste Verschuivingen -->
                <div class="relative" data-aos="fade-left">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Grootste Verschuivingen</h3>
                                    <p class="text-gray-600 text-sm">Ten opzichte van 2023</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-slate-600">Trends</span>
                                </div>
                            </div>
                            
                            <?php
                            // Sorteer partijen op absolute verandering (peiling vs huidig)
                            $sortedByChange = $parties;
                            uasort($sortedByChange, function($a, $b) {
                                $changeA = abs($a['polling']['seats'] - $a['current_seats']);
                                $changeB = abs($b['polling']['seats'] - $b['current_seats']);
                                return $changeB - $changeA;
                            });
                            $topChanges = array_slice($sortedByChange, 0, 8, true);
                            ?>
                            
                            <div class="space-y-3">
                                <?php foreach($topChanges as $partyKey => $party): 
                                    $change = $party['polling']['seats'] - $party['current_seats'];
                                    if ($change == 0) continue;
                                    
                                    $isPositive = $change > 0;
                                    $changeClass = $isPositive ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200';
                                    $changeIcon = $isPositive ? '↗' : '↘';
                                    $changeSymbol = $isPositive ? '+' . $change : $change;
                                ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                        <div>
                                            <span class="font-semibold text-gray-900 block"><?php echo $partyKey; ?></span>
                                            <span class="text-xs text-gray-500"><?php echo $party['current_seats']; ?> → <?php echo $party['polling']['seats']; ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="<?php echo $changeClass; ?> inline-flex items-center px-3 py-1.5 rounded-full font-bold text-xs border">
                                            <?php echo $changeIcon; ?> <?php echo abs($change); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Stabiele partijen -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Stabiele Partijen</h4>
                                <div class="space-y-2">
                                    <?php 
                                    $stableParties = array_filter($parties, function($party) {
                                        return ($party['polling']['seats'] - $party['current_seats']) == 0;
                                    });
                                    foreach($stableParties as $partyKey => $party):
                                    ?>
                                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-2 h-2 rounded-full" style="background-color: <?php echo getPartyColor($partyKey); ?>"></div>
                                        <span class="text-sm font-medium text-gray-700"><?php echo $partyKey; ?></span>
                                        <span class="text-xs text-gray-500 ml-auto"><?php echo $party['current_seats']; ?> zetels</span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Footer informatie -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <span>Winst</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <span>Verlies</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Analyse Modal -->
    <div id="ai-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden transform scale-95 transition-transform duration-300" id="ai-modal-content">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/10 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">AI Peiling Analyse</h3>
                                <p class="text-blue-100 text-sm">Intelligente analyse van de Nederlandse politieke situatie</p>
                            </div>
                        </div>
                        <button id="close-ai-modal" class="p-2 hover:bg-white/10 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <!-- Loading State -->
                    <div id="ai-loading" class="flex flex-col items-center justify-center py-12">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                            <div class="absolute inset-0 w-16 h-16 border-4 border-transparent border-r-secondary rounded-full animate-spin animation-delay-75"></div>
                        </div>
                        <p class="mt-4 text-slate-600 font-medium">AI analyseert de peilingen...</p>
                        <p class="text-sm text-slate-500 mt-2">Dit kan even duren</p>
                    </div>

                    <!-- Content -->
                    <div id="ai-content" class="hidden prose prose-slate max-w-none">
                        <!-- AI gegenereerde content komt hier -->
                    </div>

                    <!-- Error State -->
                    <div id="ai-error" class="hidden text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2">Er ging iets fout</h4>
                        <p class="text-slate-600 mb-4">De AI-analyse kon niet worden geladen.</p>
                        <button id="retry-ai-analysis" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                            Opnieuw proberen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 max-w-7xl -mt-6 relative z-10">
        <!-- Enhanced Header & Controls Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
            <!-- Gradient Header -->
            <div class="bg-gradient-to-r from-primary via-secondary to-primary px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Title Section -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Alle Politieke Partijen</h2>
                            <p class="text-slate-300 text-sm">Ontdek de standpunten en visies van alle Nederlandse partijen</p>
                        </div>
                    </div>
                    <!-- Stats Overview -->
                    <div class="grid grid-cols-3 gap-4 lg:gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white"><?php echo count($parties); ?></div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Partijen</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">150</div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Zetels</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white"><?php 
                                $totalSeatsPolling = array_sum(array_column($parties, 'current_seats'));
                                echo $totalSeatsPolling;
                            ?></div>
                            <div class="text-xs text-slate-300 uppercase tracking-wider">Bezet</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Controls -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Sort & Filter Controls -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <select id="sortOption" class="appearance-none bg-white border-2 border-gray-200 text-gray-700 rounded-xl px-4 py-2.5 pr-10 font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 cursor-pointer shadow-sm">
                                <option value="name">📝 Alfabetisch</option>
                                <option value="seats">🏛️ Huidige zetels</option>
                                <option value="polling">📊 Peilingen</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- View Toggle -->
                        <div class="bg-white rounded-xl p-1 border-2 border-gray-200 flex">
                            <button id="grid-view" class="px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Raster
                            </button>
                            <button id="list-view" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Lijst
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search & Info -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Zoek partij..." 
                                   class="pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 bg-white px-3 py-2 rounded-lg border">
                            <span id="party-counter"><?php echo count($parties); ?></span> partijen
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revolutionary Party Cards Grid -->
        <div id="parties-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
            <?php foreach ($parties as $partyKey => $party): ?>
                <article class="party-card group bg-gradient-to-br from-white to-gray-50 rounded-3xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 relative">
                    
                    <!-- Enhanced Party Header with Elegant Design -->
                    <header class="relative overflow-hidden">
                        <!-- Dynamic Color Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br opacity-5" 
                             style="background: linear-gradient(135deg, <?php echo getPartyColor($partyKey); ?>, <?php echo adjustColorBrightness(getPartyColor($partyKey), 40); ?>);">
                        </div>
                        
                        <!-- Decorative Pattern Overlay -->
                        <div class="absolute inset-0 opacity-5" 
                             style="background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23000000\" fill-opacity=\"0.4\"><circle cx=\"10\" cy=\"10\" r=\"1\"/><circle cx=\"30\" cy=\"30\" r=\"1\"/><circle cx=\"50\" cy=\"50\" r=\"1\"/></g></g></svg>');">
                        </div>
                        
                        <div class="relative p-6 pb-4">
                            <!-- Party Identity Section -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Premium Logo Container -->
                                    <div class="relative group-hover:scale-105 transition-transform duration-300">
                                        <div class="w-16 h-16 rounded-2xl bg-white shadow-lg border-2 border-gray-100 flex items-center justify-center overflow-hidden group-hover:shadow-xl transition-shadow duration-300">
                                            <img src="<?php echo htmlspecialchars($party['logo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($party['name']); ?> logo" 
                                                 class="w-12 h-12 object-contain">
                                        </div>
                                        <!-- Elegant Color Dot -->
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-md" 
                                             style="background-color: <?php echo getPartyColor($partyKey); ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- Party Name & Abbreviation -->
                                    <div class="flex-1">
                                        <h2 class="text-xl font-black text-gray-900 mb-1 tracking-tight">
                                            <?php echo htmlspecialchars($partyKey); ?>
                                        </h2>
                                        <p class="text-sm text-gray-600 font-medium leading-tight">
                                            <?php echo htmlspecialchars($party['name']); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Current Seats Display -->
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white text-lg font-bold px-3 py-2 rounded-xl shadow-lg">
                                        <?php echo $party['current_seats']; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 font-medium">zetels</div>
                                </div>
                            </div>
                            
                            <!-- Sophisticated Polling Display -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Peilingen</div>
                                            <div class="flex items-baseline space-x-1">
                                                <span class="text-2xl font-bold" style="color: <?php echo getPartyColor($partyKey); ?>">
                                                    <?php echo $party['polling']['seats']; ?>
                                                </span>
                                                <span class="text-sm text-gray-400">van 150</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Trend Indicator -->
                                    <?php 
                                    $changeValue = $party['polling']['change'];
                                    $isPositive = $changeValue > 0;
                                    $isNegative = $changeValue < 0;
                                    
                                    if ($isPositive) {
                                        $trendClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $trendIcon = '📈';
                                    } elseif ($isNegative) {
                                        $trendClass = 'bg-red-100 text-red-700 border-red-200';
                                        $trendIcon = '📉';
                                    } else {
                                        $trendClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                        $trendIcon = '➡️';
                                    }
                                    ?>
                                    
                                    <div class="<?php echo $trendClass; ?> px-3 py-2 rounded-lg border font-bold text-sm flex items-center space-x-1">
                                        <span><?php echo $trendIcon; ?></span>
                                        <span><?php echo $changeValue !== 0 ? ($changeValue > 0 ? '+' : '') . $changeValue : '0'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    
                    <!-- Premium Content Section -->
                    <div class="p-6 pt-2 space-y-5">
                        
                        <!-- Elegant Leader Preview -->
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 shadow-md" 
                                     style="border-color: <?php echo getPartyColor($partyKey); ?>">
                                    <img src="<?php echo htmlspecialchars($party['leader_photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($party['leader']); ?>" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate">
                                    <?php echo htmlspecialchars($party['leader']); ?>
                                </p>
                                <p class="text-xs text-gray-600">Partijleider</p>
                            </div>
                        </div>
                        
                        <!-- Refined Description -->
                        <div class="space-y-3">
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-3">
                                <?php echo htmlspecialchars(mb_substr($party['description'], 0, 160)) . '...'; ?>
                            </p>
                        </div>
                        
                        <!-- Premium Standpoints Tags -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3">
                                Kernstandpunten
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php $standpointKeys = array_slice(array_keys($party['standpoints']), 0, 3); ?>
                                <?php foreach ($standpointKeys as $topic): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors duration-200" 
                                          style="background-color: <?php echo adjustColorOpacity(getPartyColor($partyKey), 0.1); ?>; 
                                                 border-color: <?php echo adjustColorOpacity(getPartyColor($partyKey), 0.3); ?>; 
                                                 color: <?php echo adjustColorBrightness(getPartyColor($partyKey), -40); ?>;">
                                        <?php echo htmlspecialchars($topic); ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($party['standpoints']) > 3): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        +<?php echo count($party['standpoints']) - 3; ?> meer
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sophisticated Action Footer -->
                    <footer class="p-6 pt-0">
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Primary Action Button -->
                            <button class="party-btn group relative overflow-hidden text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl" 
                                    style="background: linear-gradient(135deg, <?php echo getPartyColor($partyKey); ?>, <?php echo adjustColorBrightness(getPartyColor($partyKey), -20); ?>);"
                                    data-party="<?php echo htmlspecialchars($partyKey); ?>">
                                <!-- Shimmer Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <span class="relative flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Partij</span>
                                </span>
                            </button>
                            
                            <!-- Secondary Action Button -->
                            <button class="leader-btn group bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-gray-300"
                                    data-leader="<?php echo htmlspecialchars($partyKey); ?>">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Leider</span>
                                </span>
                            </button>
                        </div>
                    </footer>
                    
                </article>
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
    
    // Enhanced Sorting functionality
    document.getElementById('sortOption').addEventListener('change', function() {
        const sortMethod = this.value;
        const partyCards = Array.from(document.querySelectorAll('.party-card'));
        const partyGrid = document.getElementById('parties-grid');
        
        // Add loading animation
        partyGrid.style.opacity = '0.5';
        partyGrid.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
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
            
            // Add the sorted cards back with staggered animation
            partyCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                partyGrid.appendChild(card);
                
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            // Restore grid
            partyGrid.style.transition = 'all 0.3s ease';
            partyGrid.style.opacity = '1';
            partyGrid.style.transform = 'scale(1)';
        }, 150);
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const partyCounter = document.getElementById('party-counter');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const partyCards = document.querySelectorAll('.party-card');
            let visibleCount = 0;
            
            partyCards.forEach(card => {
                const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
                const party = partyData[partyKey];
                const searchText = `${partyKey} ${party.name} ${party.leader}`.toLowerCase();
                
                if (searchText.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update counter
            partyCounter.textContent = visibleCount;
        });
    }
    
    // View toggle functionality (Grid/List)
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const partiesGrid = document.getElementById('parties-grid');
    
    if (gridViewBtn && listViewBtn) {
        listViewBtn.addEventListener('click', function() {
            // Switch to list view
            partiesGrid.className = 'space-y-3 mb-12';
            
            // Update button states
            gridViewBtn.classList.remove('bg-primary', 'text-white');
            gridViewBtn.classList.add('text-gray-600');
            listViewBtn.classList.add('bg-primary', 'text-white');
            listViewBtn.classList.remove('text-gray-600');
            
            // Transform cards for list view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToListView(card);
            });
        });
        
        gridViewBtn.addEventListener('click', function() {
            // Switch to grid view
            partiesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12';
            
            // Update button states
            listViewBtn.classList.remove('bg-primary', 'text-white');
            listViewBtn.classList.add('text-gray-600');
            gridViewBtn.classList.add('bg-primary', 'text-white');
            gridViewBtn.classList.remove('text-gray-600');
            
            // Transform cards back to grid view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToGridView(card);
            });
        });
    }
    
    // Enhanced list view transformation
    function transformToListView(card) {
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        const color = getPartyColor(partyKey);
        const changeValue = party.polling.change;
        
                 // Create horizontal list layout with better alignment
         card.innerHTML = `
             <div class="flex items-center p-6 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                 <!-- Left Section: Party Identity (Fixed width) -->
                 <div class="flex items-center space-x-4 w-80">
                     <!-- Logo & Party Info -->
                     <div class="flex items-center space-x-4">
                         <div class="relative flex-shrink-0">
                             <div class="w-14 h-14 rounded-xl bg-white shadow-md border-2 border-gray-100 flex items-center justify-center overflow-hidden">
                                 <img src="${party.logo}" alt="${party.name} logo" class="w-10 h-10 object-contain">
                             </div>
                             <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: ${color}"></div>
                         </div>
                         <div class="min-w-0">
                             <h3 class="text-lg font-bold text-gray-900">${partyKey}</h3>
                         </div>
                     </div>
                     
                     <!-- Leader Info -->
                     <div class="flex items-center space-x-3 pl-6 border-l border-gray-200">
                         <div class="w-10 h-10 rounded-full overflow-hidden border-2 shadow-sm flex-shrink-0" style="border-color: ${color}">
                             <img src="${party.leader_photo}" alt="${party.leader}" class="w-full h-full object-cover">
                         </div>
                         <div class="min-w-0">
                             <p class="text-sm font-semibold text-gray-800 truncate">${party.leader}</p>
                             <p class="text-xs text-gray-500">Partijleider</p>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Center Section: Key Stats (Fixed widths for alignment) -->
                 <div class="flex items-center space-x-12 flex-1 justify-center">
                     <!-- Current Seats -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold text-gray-900">${party.current_seats}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Huidige zetels</div>
                     </div>
                     
                     <!-- Polling -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold" style="color: ${color}">${party.polling.seats}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Peilingen</div>
                     </div>
                     
                     <!-- Trend -->
                     <div class="text-center w-28">
                         <div class="flex items-center justify-center mb-1">
                             ${changeValue > 0 ? 
                                 `<div class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-200 text-sm font-bold">📈 +${changeValue}</div>` :
                                 changeValue < 0 ? 
                                 `<div class="bg-red-100 text-red-700 px-2 py-1 rounded-lg border border-red-200 text-sm font-bold">📉 ${changeValue}</div>` :
                                 `<div class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg border border-blue-200 text-sm font-bold">➡️ 0</div>`
                             }
                         </div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Trend</div>
                     </div>
                 </div>
                 
                 <!-- Right Section: Actions (Fixed width) -->
                 <div class="flex items-center space-x-3 w-56 justify-end">
                     <button class="party-btn bg-gradient-to-r text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300" 
                             style="background: linear-gradient(135deg, ${color}, ${adjustColorBrightness(color, -20)});"
                             data-party="${partyKey}">
                         <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                         </svg>
                         Partij
                     </button>
                     <button class="leader-btn bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-300"
                             data-leader="${partyKey}">
                         <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                         </svg>
                         Leider
                     </button>
                 </div>
             </div>
         `;
        
        // Re-attach event listeners for the new buttons
        attachButtonListeners(card);
    }
    
    // Restore grid view
    function transformToGridView(card) {
        // Get the original party key to rebuild the card
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        
        // Restore original card HTML (this would need the original template)
        location.reload(); // Simple solution - reload to restore original cards
    }
    
    // Helper function to attach event listeners to new buttons
    function attachButtonListeners(card) {
        const partyBtn = card.querySelector('.party-btn');
        const leaderBtn = card.querySelector('.leader-btn');
        
        if (partyBtn) {
            partyBtn.addEventListener('click', function() {
                const partyKey = this.getAttribute('data-party');
                const party = partyData[partyKey];
                
                // Fill modal with party data (same as existing functionality)
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
        }
        
        if (leaderBtn) {
            leaderBtn.addEventListener('click', function() {
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
        }
    }
    
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
    
    // Helper function to adjust color brightness for JavaScript
    function adjustColorBrightness(hex, steps) {
        // Remove the # if present
        hex = hex.replace('#', '');
        
        // Parse r, g, b values
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        
        // Adjust brightness
        const newR = Math.max(0, Math.min(255, r + steps));
        const newG = Math.max(0, Math.min(255, g + steps));
        const newB = Math.max(0, Math.min(255, b + steps));
        
        // Convert back to hex
        const toHex = (n) => {
            const hex = n.toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };
        
        return '#' + toHex(newR) + toHex(newG) + toHex(newB);
    }
});

// Helper function for PHP to use the same color mapping
<?php
function getPartyColor($partyKey) {
    $partyColors = [
        'PVV' => '#0078D7',
        'VVD' => '#FF9900',
        'NSC' => '#4D7F78',
        'BBB' => '#95c119',
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

    // AI Analysis functionality
    const aiBtn = document.getElementById('ai-analysis-btn');
    const aiModal = document.getElementById('ai-modal');
    const aiModalContent = document.getElementById('ai-modal-content');
    const closeAiModal = document.getElementById('close-ai-modal');
    const aiLoading = document.getElementById('ai-loading');
    const aiContent = document.getElementById('ai-content');
    const aiError = document.getElementById('ai-error');
    const retryBtn = document.getElementById('retry-ai-analysis');

    function openAiModal() {
        aiModal.classList.remove('hidden');
        setTimeout(() => {
            aiModal.classList.remove('opacity-0');
            aiModalContent.classList.remove('scale-95');
            aiModalContent.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
        
        // Start AI analysis
        performAiAnalysis();
    }

    function closeAiModalFunc() {
        aiModal.classList.add('opacity-0');
        aiModalContent.classList.remove('scale-100');
        aiModalContent.classList.add('scale-95');
        setTimeout(() => {
            aiModal.classList.add('hidden');
            // Reset states
            aiLoading.classList.remove('hidden');
            aiContent.classList.add('hidden');
            aiError.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function performAiAnalysis() {
        // Show loading state
        aiLoading.classList.remove('hidden');
        aiContent.classList.add('hidden');
        aiError.classList.add('hidden');

        // Gather polling data for AI analysis
        const pollingData = {
            parties: <?php echo json_encode($parties); ?>,
            date: new Date().toISOString().split('T')[0]
        };

        fetch('ajax/ai-polling-analysis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(pollingData)
        })
        .then(response => response.json())
        .then(data => {
            aiLoading.classList.add('hidden');
            
            if (data.success) {
                aiContent.innerHTML = formatAiResponse(data.content);
                aiContent.classList.remove('hidden');
            } else {
                showAiError();
            }
        })
        .catch(error => {
            console.error('AI Analysis error:', error);
            aiLoading.classList.add('hidden');
            showAiError();
        });
    }

    function showAiError() {
        aiError.classList.remove('hidden');
        aiContent.classList.add('hidden');
        aiLoading.classList.add('hidden');
    }

    function formatAiResponse(content) {
        // Format the AI response with nice styling
        const sections = content.split('\n\n');
        let formattedContent = '';
        
        sections.forEach(section => {
            if (section.trim()) {
                if (section.includes('**') || section.includes('#')) {
                    // Handle headers
                    section = section.replace(/\*\*(.*?)\*\*/g, '<h3 class="text-lg font-bold text-slate-900 mt-6 mb-3">$1</h3>');
                    section = section.replace(/### (.*)/g, '<h3 class="text-lg font-bold text-slate-900 mt-6 mb-3">$1</h3>');
                    section = section.replace(/## (.*)/g, '<h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">$1</h2>');
                } else {
                    // Regular paragraph
                    section = `<p class="text-slate-700 leading-relaxed mb-4">${section}</p>`;
                }
                formattedContent += section;
            }
        });
        
        return formattedContent;
    }

    // Event listeners
    aiBtn.addEventListener('click', openAiModal);
    closeAiModal.addEventListener('click', closeAiModalFunc);
    retryBtn.addEventListener('click', performAiAnalysis);

    // Close modal when clicking outside
    aiModal.addEventListener('click', function(e) {
        if (e.target === aiModal) {
            closeAiModalFunc();
        }
    });

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !aiModal.classList.contains('hidden')) {
            closeAiModalFunc();
        }
    });
});
</script>

<style>
/* Enhanced Party Cards Styling */

/* Modern hover animations and transitions */
.party-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, box-shadow;
}

.party-card:hover {
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Enhanced button shimmer effects */
.party-btn:hover {
    animation: buttonPulse 0.6s ease;
}

@keyframes buttonPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Fade in animation for search results */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced focus states */
input:focus, select:focus, button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Premium gradient backgrounds */
.bg-premium-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Advanced backdrop blur effects */
.backdrop-blur-premium {
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
}

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