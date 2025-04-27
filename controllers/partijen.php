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
        'polling' => ['seats' => 28, 'percentage' => 18.7, 'change' => -9],
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
        'polling' => ['seats' => 26, 'percentage' => 17.3, 'change' => +2],
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
        'polling' => ['seats' => 1, 'percentage' => 0.7, 'change' => -19],
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
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => -4],
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
        'polling' => ['seats' => 29, 'percentage' => 19.3, 'change' => +4],
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
        'polling' => ['seats' => 8, 'percentage' => 5.3, 'change' => -1],
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
        'polling' => ['seats' => 8, 'percentage' => 5.3, 'change' => +3],
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
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => -2],
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
        'polling' => ['seats' => 19, 'percentage' => 12.7, 'change' => +14],
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
        'current_seats' => 2,
        'polling' => ['seats' => 5, 'percentage' => 3.3, 'change' => +3],
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
        'polling' => ['seats' => 4, 'percentage' => 2.7, 'change' => +2],
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
                <div class="party-card bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 group relative">
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
                        
                        <!-- Action buttons with hover effects -->
                        <div class="flex gap-3">
                            <button class="party-btn flex-1 text-white text-sm px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center font-medium shadow-sm hover:shadow-md" 
                                    style="background-color: <?php echo getPartyColor($partyKey); ?>; 
                                           box-shadow: 0 2px 0 <?php echo adjustColorBrightness(getPartyColor($partyKey), -30); ?>;"
                                    data-party="<?php echo htmlspecialchars($partyKey); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Partij
                            </button>
                            <button class="leader-btn flex-1 bg-white border border-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center font-medium hover:bg-gray-50 shadow-sm hover:shadow-md"
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
            <button class="close-modal text-gray-500 hover:text-gray-800 transition-colors">
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
            <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg transition-colors">
                Sluiten
            </button>
        </div>
    </div>
</div>

<div id="leader-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 id="leader-modal-title" class="text-2xl font-bold text-gray-800"></h2>
            <button class="close-modal text-gray-500 hover:text-gray-800 transition-colors">
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
            <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-5 py-2.5 rounded-lg transition-colors">
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
                    <button id="polling-tab" class="tab-btn py-3 px-6 text-gray-500 hover:text-gray-700 font-medium border-b-2 border-transparent">
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
                <div class="chamber relative w-full max-w-4xl">
                    <div class="absolute inset-0 chamber-grid" id="current-seats-chamber">
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
                            echo '<div class="flex items-center bg-gray-50 p-2 rounded-lg">';
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
                <div class="chamber relative w-full max-w-4xl">
                    <div class="absolute inset-0 chamber-grid" id="polling-seats-chamber">
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
                            
                            echo '<div class="flex items-center bg-gray-50 p-2 rounded-lg">';
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
                    <span class="text-sm text-blue-700">Hover over zetels voor meer informatie. Klik op een partij in de legenda om alle zetels van die partij te markeren.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 mb-12">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="relative">
            <div class="bg-gradient-to-br from-blue-500 to-red-600 p-6 text-white">
                <h2 class="text-2xl font-bold mb-2">Coalitiemaker</h2>
                <p class="text-white/80">Stel je eigen coalitie samen en bekijk of je een meerderheid kunt behalen</p>
            </div>
            
            <!-- Decorative pattern -->
            <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.25)\"%3E%3C/path%3E%3C/svg%3E')]"></div>
        </div>
        
        <div class="p-6">
            <!-- Tab buttons -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button id="coalition-current-tab" class="coalition-tab-btn py-3 px-6 text-primary border-b-2 border-primary font-medium">
                        Huidige zetelverdeling
                    </button>
                    <button id="coalition-polling-tab" class="coalition-tab-btn py-3 px-6 text-gray-500 hover:text-gray-700 font-medium border-b-2 border-transparent">
                        Peilingen
                    </button>
                </nav>
            </div>
            
            <!-- Coalition maker interface -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left column: Available parties -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Beschikbare partijen</h3>
                    <div id="available-parties" class="space-y-2 max-h-[500px] overflow-y-auto pr-1">
                        <!-- Parties will be added by JavaScript -->
                        <div class="text-center text-gray-500 py-4">Partijen worden geladen...</div>
                    </div>
                </div>
                
                <!-- Middle column: Selected coalition -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Jouw coalitie</h3>
                    <div id="selected-coalition" class="space-y-2 min-h-[250px] max-h-[500px] overflow-y-auto pr-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <p class="text-gray-500 text-center italic">Sleep partijen hierheen om een coalitie te vormen</p>
                    </div>
                    
                    <div class="mt-6">
                        <button id="clear-coalition" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            Wissen
                        </button>
                    </div>
                </div>
                
                <!-- Right column: Coalition summary -->
                <div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Coalitieoverzicht</h3>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Coalitiezetels:</span>
                                <span id="coalition-seats" class="font-bold text-lg">0</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                <div id="coalition-progress" class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                            
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>0</span>
                                <span>Benodigde meerderheid: 76</span>
                                <span>150</span>
                            </div>
                        </div>
                        
                        <div id="coalition-status" class="mt-4 text-center py-2 rounded-lg bg-gray-200 text-gray-700">
                            Nog geen meerderheid
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 hidden">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Politieke verdeling</h3>
                        <div id="coalition-spectrum-container" class="flex items-center justify-between h-8 bg-gradient-to-r from-blue-600 via-purple-500 to-red-600 rounded-full overflow-hidden relative mb-2">
                            <div id="coalition-spectrum-indicator" class="absolute h-12 w-4 bg-white shadow-md opacity-80 top-0 left-50% transition-all duration-300 -mt-2 border-2 border-gray-800" style="left: 50%; transform: translateX(-50%)"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-blue-600 mr-1"></div>
                                <span class="text-xs font-medium">Links</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-purple-500 mr-1"></div>
                                <span class="text-xs font-medium">Midden</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-red-600 mr-1"></div>
                                <span class="text-xs font-medium">Rechts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Coalition combinations suggestions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 hidden">Suggesties</h3>
                <div id="coalition-suggestions" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Suggestions will be added by JavaScript -->
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
        
        // Add event listeners to legend items for highlighting
        document.querySelectorAll('.bg-gray-50').forEach(legendItem => {
            legendItem.addEventListener('mouseenter', function() {
                const partyName = this.querySelector('span:nth-child(2)').textContent;
                highlightPartySeats(partyName);
            });
            
            legendItem.addEventListener('mouseleave', function() {
                resetHighlights();
            });
        });
    }
    
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
            row.className = `chamber-row chamber-row-${rowIndex}`;
            
            for (let i = 0; i < seatsInRow; i++) {
                if (seatCount < totalSeats) {
                    const seat = document.createElement('div');
                    const seatData = allSeats[seatCount];
                    
                    seat.className = 'chamber-seat';
                    seat.style.backgroundColor = seatData.color;
                    seat.dataset.party = seatData.party;
                    
                    if (seatData.party !== 'empty') {
                        const partyInfo = partyData[seatData.party];
                        seat.setAttribute('title', `${seatData.party}: ${partyInfo.name}`);
                        
                        // Add tooltip content
                        const tooltip = document.createElement('div');
                        tooltip.className = 'seat-tooltip';
                        tooltip.innerHTML = `
                            <div class="font-bold">${seatData.party}</div>
                            <div>${partyInfo.name}</div>
                        `;
                        seat.appendChild(tooltip);
                        
                        // Event listeners for tooltip
                        seat.addEventListener('mouseenter', function() {
                            this.classList.add('active');
                        });
                        
                        seat.addEventListener('mouseleave', function() {
                            this.classList.remove('active');
                        });
                    }
                    
                    row.appendChild(seat);
                    seatCount++;
                }
            }
            
            container.appendChild(row);
        });
    }
    
    function highlightPartySeats(partyKey) {
        document.querySelectorAll('.chamber-seat').forEach(seat => {
            if (seat.dataset.party === partyKey) {
                seat.classList.add('highlight');
            } else {
                seat.classList.add('dim');
            }
        });
    }
    
    function resetHighlights() {
        document.querySelectorAll('.chamber-seat').forEach(seat => {
            seat.classList.remove('highlight', 'dim');
        });
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
            'DENK': '#39A935',
            'Volt': '#502379',
            'CU': '#00AEEF'
        };
        
        return partyColors[partyKey] || '#A0A0A0';
    }
    
    // Create visualization after DOM loaded
    createChamberVisualization();
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

// Coalition Maker Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Definieer de partijen direct op basis van de PHP-data om problemen met scope te vermijden
    const coalitionParties = <?php echo json_encode($parties); ?>;
    
    console.log('Coalition maker geladen met', Object.keys(coalitionParties).length, 'partijen');
    
    // Define political spectrum positions (0 = far right, 100 = far left)
    const partyPositions = {
        'PVV': 15,
        'VVD': 30,
        'NSC': 55,
        'BBB': 40,
        'GL-PvdA': 85,
        'D66': 70,
        'SP': 90,
        'PvdD': 80,
        'CDA': 50,
        'JA21': 20,
        'SGP': 25,
        'FvD': 10,
        'DENK': 75,
        'Volt': 65,
        'CU': 60
    };
    
    // Pre-defined coalition suggestions
    const coalitionSuggestions = [
        {
            name: "Rechts blok",
            parties: ["PVV", "VVD", "BBB", "JA21"],
            description: "Conservatieve coalitie met focus op streng immigratiebeleid en economische vrijheid"
        },
        {
            name: "Progressief links",
            parties: ["GL-PvdA", "D66", "SP", "PvdD", "Volt"],
            description: "Progressieve coalitie met ambitieus klimaatbeleid en focus op sociale rechtvaardigheid"
        },
        {
            name: "Breed midden",
            parties: ["NSC", "VVD", "CDA", "D66", "BBB"],
            description: "Brede centrumcoalitie gericht op stabiliteit en pragmatische oplossingen"
        },
        {
            name: "Motorblok",
            parties: ["VVD", "NSC", "GL-PvdA", "D66"],
            description: "Combinatie van progressief en behoudend met nadruk op bestuurlijke vernieuwing"
        },
        {
            name: "Extraparlementair",
            parties: ["PVV", "NSC", "VVD", "BBB", "CDA", "SGP"],
            description: "Rechtse meerderheid met gedoogsteun van kleine christelijke partijen"
        }
    ];
    
    // Generate available party cards based on current view
    function generateAvailableParties() {
        const container = document.getElementById('available-parties');
        container.innerHTML = '';
        
        if (!coalitionParties || Object.keys(coalitionParties).length === 0) {
            console.error('Geen partijgegevens beschikbaar om weer te geven!');
            container.innerHTML = '<div class="text-center text-red-500 py-4">Fout bij het laden van partijen</div>';
            return;
        }
        
        console.log('Generating party cards from:', coalitionParties);
        
        // Sort parties by seats (descending)
        let partiesSorted = Object.entries(coalitionParties).sort((a, b) => {
            const aSeats = currentView === 'current' ? a[1].current_seats : a[1].polling.seats;
            const bSeats = currentView === 'current' ? b[1].current_seats : b[1].polling.seats;
            return bSeats - aSeats;
        });
        
        // Create card for each party with seats > 0
        partiesSorted.forEach(([partyKey, party]) => {
            const seats = currentView === 'current' ? party.current_seats : party.polling.seats;
            
            console.log('Processing party:', partyKey, 'with seats:', seats);
            
            if (seats > 0) {
                const card = document.createElement('div');
                card.className = 'party-item bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between cursor-move transition-all hover:shadow-md mb-2';
                card.setAttribute('draggable', 'true');
                card.setAttribute('data-party', partyKey);
                card.setAttribute('data-seats', seats);
                
                const color = getPartyColor(partyKey);
                
                card.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full overflow-hidden mr-2 bg-white flex items-center justify-center border" style="border-color: ${color}">
                            <img src="${party.logo}" alt="${party.name}" class="w-5 h-5 object-contain">
                        </div>
                        <span class="font-medium">${partyKey}</span>
                    </div>
                    <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded-full">${seats}</span>
                `;
                
                container.appendChild(card);
            }
        });
    }
    
    // Calculate total seats for a coalition
    function calculateCoalitionSeats(parties, view) {
        return parties.reduce((total, partyKey) => {
            const seats = view === 'current' ? 
                coalitionParties[partyKey]?.current_seats || 0 : 
                coalitionParties[partyKey]?.polling.seats || 0;
            return total + seats;
        }, 0);
    }
    
    // Update coalition status (seats, majority, political spectrum)
    function updateCoalitionStatus() {
        // Calculate total seats
        let totalSeats = 0;
        let totalPosition = 0;
        
        currentCoalition.forEach(partyKey => {
            const seats = currentView === 'current' ? 
                coalitionParties[partyKey].current_seats : 
                coalitionParties[partyKey].polling.seats;
            
            totalSeats += seats;
            
            // Calculate weighted political position
            totalPosition += partyPositions[partyKey] * seats;
        });
        
        // Update seats display
        document.getElementById('coalition-seats').textContent = totalSeats;
        
        // Update progress bar
        const progressBar = document.getElementById('coalition-progress');
        const progressPercent = Math.min(100, (totalSeats / 150) * 100);
        progressBar.style.width = `${progressPercent}%`;
        
        // Update status message
        const statusEl = document.getElementById('coalition-status');
        if (totalSeats >= 76) {
            statusEl.textContent = 'Meerderheid behaald!';
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-green-100 text-green-800 font-medium';
        } else if (totalSeats > 0) {
            const needed = 76 - totalSeats;
            statusEl.textContent = `Nog ${needed} zetels nodig voor meerderheid`;
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-yellow-100 text-yellow-800 font-medium';
        } else {
            statusEl.textContent = 'Nog geen meerderheid';
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-gray-200 text-gray-700';
        }
        
        // Update political spectrum indicator
        const spectrumIndicator = document.getElementById('coalition-spectrum-indicator');
        if (totalSeats > 0) {
            // Calculate average position, weighted by seats
            const avgPosition = totalPosition / totalSeats;
            spectrumIndicator.style.left = `${avgPosition}%`;
            spectrumIndicator.style.display = 'block';
        } else {
            spectrumIndicator.style.display = 'none';
        }
    }
    
    let currentCoalition = [];
    let currentView = 'current'; // 'current' or 'polling'
    
    // Initialize the coalition maker
    function initCoalitionMaker() {
        console.log('Initializing coalition maker...');
        
        // Set up tab switching
        document.getElementById('coalition-current-tab').addEventListener('click', function() {
            switchCoalitionView('current');
        });
        
        document.getElementById('coalition-polling-tab').addEventListener('click', function() {
            switchCoalitionView('polling');
        });
        
        // Clear coalition button
        document.getElementById('clear-coalition').addEventListener('click', clearCoalition);
        
        // Generate party cards
        generateAvailableParties();
        
        // Generate coalition suggestions
        generateCoalitionSuggestions();
        
        // Set up drag and drop
        setupDragAndDrop();
        
        console.log('Coalition maker initialization complete');
    }
    
    // Switch between current seats and polling views
    function switchCoalitionView(view) {
        currentView = view;
        
        // Update tab styling
        const currentTab = document.getElementById('coalition-current-tab');
        const pollingTab = document.getElementById('coalition-polling-tab');
        
        if (view === 'current') {
            currentTab.classList.add('text-primary', 'border-primary');
            currentTab.classList.remove('text-gray-500', 'border-transparent');
            
            pollingTab.classList.remove('text-primary', 'border-primary');
            pollingTab.classList.add('text-gray-500', 'border-transparent');
        } else {
            pollingTab.classList.add('text-primary', 'border-primary');
            pollingTab.classList.remove('text-gray-500', 'border-transparent');
            
            currentTab.classList.remove('text-primary', 'border-primary');
            currentTab.classList.add('text-gray-500', 'border-transparent');
        }
        
        // Reset coalition and regenerate parties
        clearCoalition();
        generateAvailableParties();
    }
    
    // Generate suggested coalition cards
    function generateCoalitionSuggestions() {
        const container = document.getElementById('coalition-suggestions');
        container.innerHTML = '';
        
        coalitionSuggestions.forEach(suggestion => {
            const card = document.createElement('div');
            card.className = 'bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all';
            
            // Calculate current and polling seats for this suggestion
            const currentSeats = calculateCoalitionSeats(suggestion.parties, 'current');
            const pollingSeats = calculateCoalitionSeats(suggestion.parties, 'polling');
            
            // Determine if this coalition has a majority in either view
            const currentMajority = currentSeats >= 76;
            const pollingMajority = pollingSeats >= 76;
            
            const currentBadgeClass = currentMajority ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const pollingBadgeClass = pollingMajority ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            
            // Create party color dots
            let partyDots = '';
            suggestion.parties.forEach(party => {
                const color = getPartyColor(party);
                partyDots += `<div class="w-4 h-4 rounded-full" style="background-color: ${color}" title="${party}"></div>`;
            });
            
            card.innerHTML = `
                <h4 class="font-semibold text-gray-800 mb-2">${suggestion.name}</h4>
                <p class="text-gray-600 text-sm mb-3">${suggestion.description}</p>
                
                <div class="flex items-center gap-1 mb-3">
                    ${partyDots}
                </div>
                
                <div class="flex flex-wrap gap-2 text-xs mb-3">
                    ${suggestion.parties.map(party => `<span class="bg-gray-100 px-2 py-1 rounded-full">${party}</span>`).join('')}
                </div>
                
                <div class="flex justify-between">
                    <div class="text-xs">
                        <span class="text-gray-500">Huidig:</span>
                        <span class="${currentBadgeClass} text-xs px-2 py-0.5 rounded-full font-medium">${currentSeats}/76</span>
                    </div>
                    <div class="text-xs">
                        <span class="text-gray-500">Peiling:</span>
                        <span class="${pollingBadgeClass} text-xs px-2 py-0.5 rounded-full font-medium">${pollingSeats}/76</span>
                    </div>
                </div>
                
                <button class="apply-suggestion mt-3 w-full bg-primary text-white text-sm py-1.5 rounded-lg hover:bg-primary-dark transition-colors">Toepassen</button>
            `;
            
            // Add event listener to apply this suggestion
            card.querySelector('.apply-suggestion').addEventListener('click', function() {
                applySuggestion(suggestion.parties);
            });
            
            container.appendChild(card);
        });
    }
    
    // Apply a suggested coalition
    function applySuggestion(parties) {
        clearCoalition();
        
        // Find each party in the available list and move it to coalition
        parties.forEach(partyKey => {
            const partyEl = document.querySelector(`.party-item[data-party="${partyKey}"]`);
            if (partyEl) {
                moveToCoalition(partyEl);
            }
        });
    }
    
    // Setup drag and drop functionality
    function setupDragAndDrop() {
        // Drag events for party items
        document.addEventListener('dragstart', function(e) {
            if (e.target.classList.contains('party-item')) {
                e.dataTransfer.setData('text/plain', e.target.getAttribute('data-party'));
                e.target.classList.add('opacity-50');
            }
        });
        
        document.addEventListener('dragend', function(e) {
            if (e.target.classList.contains('party-item')) {
                e.target.classList.remove('opacity-50');
            }
        });
        
        // Drop events for coalition container
        const coalitionContainer = document.getElementById('selected-coalition');
        
        coalitionContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('bg-gray-100');
        });
        
        coalitionContainer.addEventListener('dragleave', function() {
            this.classList.remove('bg-gray-100');
        });
        
        coalitionContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('bg-gray-100');
            
            const partyKey = e.dataTransfer.getData('text/plain');
            const partyEl = document.querySelector(`.party-item[data-party="${partyKey}"]`);
            
            if (partyEl && !currentCoalition.includes(partyKey)) {
                moveToCoalition(partyEl);
            }
        });
        
        // Make existing coalition items removable
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-party')) {
                const partyEl = e.target.closest('.party-item');
                const partyKey = partyEl.getAttribute('data-party');
                
                // Remove from coalition array
                currentCoalition = currentCoalition.filter(p => p !== partyKey);
                
                // Move back to available
                const availableContainer = document.getElementById('available-parties');
                
                // Remove close button
                partyEl.querySelector('.remove-party').remove();
                
                // Add back to available parties
                availableContainer.appendChild(partyEl);
                
                // Update status
                updateCoalitionStatus();
            }
        });
    }
    
    // Move a party element to the coalition
    function moveToCoalition(partyEl) {
        const partyKey = partyEl.getAttribute('data-party');
        const coalitionContainer = document.getElementById('selected-coalition');
        
        // Clear placeholder if it exists
        if (currentCoalition.length === 0) {
            coalitionContainer.innerHTML = '';
            // Reset styling when we add the first element
            coalitionContainer.classList.remove('flex', 'flex-col', 'items-center', 'justify-center');
        }
        
        // Add to coalition array
        if (!currentCoalition.includes(partyKey)) {
            currentCoalition.push(partyKey);
        }
        
        // Add close button to the party card
        const closeBtn = document.createElement('button');
        closeBtn.className = 'remove-party ml-2 text-gray-400 hover:text-gray-600';
        closeBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        `;
        
        // Check if the close button already exists before adding
        if (!partyEl.querySelector('.remove-party')) {
            partyEl.querySelector('div').appendChild(closeBtn);
        }
        
        // Move to coalition
        coalitionContainer.appendChild(partyEl);
        
        // Update status
        updateCoalitionStatus();
    }
    
    // Clear the current coalition
    function clearCoalition() {
        const coalitionContainer = document.getElementById('selected-coalition');
        const availableContainer = document.getElementById('available-parties');
        
        // Move all parties back to available
        coalitionContainer.querySelectorAll('.party-item').forEach(item => {
            // Remove close button
            const closeBtn = item.querySelector('.remove-party');
            if (closeBtn) {
                closeBtn.remove();
            }
            
            // Add back to available
            availableContainer.appendChild(item);
        });
        
        // Reset coalition array
        currentCoalition = [];
        
        // Add placeholder back
        coalitionContainer.innerHTML = `
            <p class="text-gray-500 text-center italic">Sleep partijen hierheen om een coalitie te vormen</p>
        `;
        
        // Restore flex styling
        coalitionContainer.classList.add('flex', 'flex-col', 'items-center', 'justify-center');
        
        // Update status
        updateCoalitionStatus();
    }
    
    // Initialize coalition maker
    initCoalitionMaker();
    console.log('Coalition maker init called');
});

// Direct tonen van partijen als een fallback
document.addEventListener('DOMContentLoaded', function() {
    console.log('Directe debug script geladen');
    
    // Definieer de partijkleur functie direct als deze nog niet bestaat
    if (typeof getPartyColor !== 'function') {
        window.getPartyColor = function(partyKey) {
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
        };
        console.log('getPartyColor functie gedefinieerd in fallback');
    }
    
    setTimeout(function() {
        const availableContainer = document.getElementById('available-parties');
        
        // Als er nog steeds geen partijen zijn, probeer ze direct toe te voegen
        if (availableContainer && availableContainer.children.length <= 1) {
            console.log('Geen partijen gevonden, probeer directe toevoeging');
            
            const parties = <?php echo json_encode($parties); ?>;
            
            // Toon alle partijen
            Object.entries(parties)
                .sort((a, b) => b[1].current_seats - a[1].current_seats) // Sorteer op zetels
                .forEach(([partyKey, party]) => {
                 const card = document.createElement('div');
                 card.className = 'party-item bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between cursor-move transition-all hover:shadow-md mb-2';
                 card.setAttribute('draggable', 'true');
                 card.setAttribute('data-party', partyKey);
                 card.setAttribute('data-seats', party.current_seats);
                 
                 // Gebruik dezelfde kleurcode als in de hoofdfunctie
                 let color = '#1a365d'; // Standaard kleur
                 try {
                     // Probeer de kleur te bepalen op basis van de functie getPartyColor als deze bestaat
                     if (typeof getPartyColor === 'function') {
                         color = getPartyColor(partyKey);
                     }
                 } catch(e) {
                     console.error('Kon partijkleur niet bepalen:', e);
                 }
                 
                 card.innerHTML = `
                     <div class="flex items-center">
                         <div class="w-6 h-6 rounded-full overflow-hidden mr-2 bg-white flex items-center justify-center border" style="border-color: ${color}">
                             <img src="${party.logo}" alt="${party.name}" class="w-5 h-5 object-contain">
                         </div>
                         <span class="font-medium">${partyKey}</span>
                     </div>
                     <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded-full">${party.current_seats}</span>
                 `;
                 
                 availableContainer.appendChild(card);
             });
            
            // Verwijder de laadmelding als die er is
            const loadingMsg = availableContainer.querySelector('.text-gray-500');
            if (loadingMsg) loadingMsg.remove();
            
            // Voeg drag & drop functionaliteit toe
            setupDragAndDropFallback();
        }
    }, 1000); // Controleer na 1 seconde
    
    // Eenvoudige fallback drag & drop implementatie
    function setupDragAndDropFallback() {
        console.log('Setting up fallback drag & drop');
        
        // Drag events voor party items
        document.addEventListener('dragstart', function(e) {
            if (e.target.classList.contains('party-item')) {
                e.dataTransfer.setData('text/plain', e.target.getAttribute('data-party'));
                e.target.classList.add('opacity-50');
            }
        });
        
        document.addEventListener('dragend', function(e) {
            if (e.target.classList.contains('party-item')) {
                e.target.classList.remove('opacity-50');
            }
        });
        
        // Drop events voor coalitie container
        const coalitionContainer = document.getElementById('selected-coalition');
        if (coalitionContainer) {
            coalitionContainer.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('bg-gray-100');
            });
            
            coalitionContainer.addEventListener('dragleave', function() {
                this.classList.remove('bg-gray-100');
            });
            
            coalitionContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('bg-gray-100');
                
                const partyKey = e.dataTransfer.getData('text/plain');
                const partyEl = document.querySelector(`.party-item[data-party="${partyKey}"]`);
                
                if (partyEl) {
                    // Verwijder eerst placeholder als deze bestaat
                    if (this.querySelector('p.italic')) {
                        this.innerHTML = '';
                        this.classList.remove('flex', 'flex-col', 'items-center', 'justify-center');
                    }
                    
                    // Voeg sluitknop toe als deze niet bestaat
                    if (!partyEl.querySelector('.remove-party')) {
                        const closeBtn = document.createElement('button');
                        closeBtn.className = 'remove-party ml-2 text-gray-400 hover:text-gray-600';
                        closeBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        `;
                        partyEl.querySelector('div').appendChild(closeBtn);
                    }
                    
                    // Verplaats naar coalitie
                    this.appendChild(partyEl);
                    
                    // Update coalitie status
                    updateCoalitionStatusFallback();
                }
            });
        }
        
        // Click handler voor verwijderknop
        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-party');
            if (removeBtn) {
                const partyEl = removeBtn.closest('.party-item');
                const availableContainer = document.getElementById('available-parties');
                
                // Verwijder sluitknop
                removeBtn.remove();
                
                // Verplaats terug naar beschikbare partijen
                if (availableContainer) {
                    availableContainer.appendChild(partyEl);
                }
                
                // Update status
                updateCoalitionStatusFallback();
                
                // Voeg placeholder toe als er geen partijen meer zijn
                const coalitionContainer = document.getElementById('selected-coalition');
                if (coalitionContainer && coalitionContainer.querySelectorAll('.party-item').length === 0) {
                    coalitionContainer.innerHTML = `<p class="text-gray-500 text-center italic">Sleep partijen hierheen om een coalitie te vormen</p>`;
                    coalitionContainer.classList.add('flex', 'flex-col', 'items-center', 'justify-center');
                }
            }
        });
    }
    
    // Eenvoudige functie om coalitie status bij te werken
    function updateCoalitionStatusFallback() {
        const coalitionContainer = document.getElementById('selected-coalition');
        const coalitionSeats = document.getElementById('coalition-seats');
        const progressBar = document.getElementById('coalition-progress');
        const statusEl = document.getElementById('coalition-status');
        
        if (!coalitionContainer || !coalitionSeats || !progressBar || !statusEl) {
            console.error('Een of meer elementen voor coalitie status niet gevonden');
            return;
        }
        
        // Tel zetels
        let totalSeats = 0;
        coalitionContainer.querySelectorAll('.party-item').forEach(item => {
            totalSeats += parseInt(item.getAttribute('data-seats') || 0);
        });
        
        // Update zetels weergave
        coalitionSeats.textContent = totalSeats;
        
        // Update voortgangsbalk
        const progressPercent = Math.min(100, (totalSeats / 150) * 100);
        progressBar.style.width = `${progressPercent}%`;
        
        // Update status bericht
        if (totalSeats >= 76) {
            statusEl.textContent = 'Meerderheid behaald!';
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-green-100 text-green-800 font-medium';
        } else if (totalSeats > 0) {
            const needed = 76 - totalSeats;
            statusEl.textContent = `Nog ${needed} zetels nodig voor meerderheid`;
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-yellow-100 text-yellow-800 font-medium';
        } else {
            statusEl.textContent = 'Nog geen meerderheid';
            statusEl.className = 'mt-4 text-center py-2 rounded-lg bg-gray-200 text-gray-700';
        }
    }
});
</script>

<style>
/* Add line clamp utility */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Define custom colors */
:root {
    --color-primary: #1a365d;
    --color-primary-dark: #142b4c;
    --color-secondary: #c41e3a;
    --color-secondary-dark: #a51930;
}

.bg-primary {
    background-color: var(--color-primary);
}
.bg-primary-dark {
    background-color: var(--color-primary-dark);
}
.bg-secondary {
    background-color: var(--color-secondary);
}
.bg-secondary-dark {
    background-color: var(--color-secondary-dark);
}
.text-primary {
    color: var(--color-primary);
}
.text-secondary {
    color: var(--color-secondary);
}
.border-primary {
    border-color: var(--color-primary);
}
.border-secondary {
    border-color: var(--color-secondary);
}
.hover\:bg-primary:hover {
    background-color: var(--color-primary);
}
.hover\:bg-primary-dark:hover {
    background-color: var(--color-primary-dark);
}
.hover\:bg-secondary:hover {
    background-color: var(--color-secondary);
}
.hover\:bg-secondary-dark:hover {
    background-color: var(--color-secondary-dark);
}

/* Tweede Kamer chamber visualization styles */
.chamber {
    height: 300px;
    background-color: #F9FAFB;
    border-radius: 150px 150px 0 0;
    position: relative;
    border: 1px solid #E5E7EB;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
}

.chamber-grid {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-top: 10px;
    padding-bottom: 10px;
}

.chamber-row {
    display: flex;
    justify-content: center;
    margin-bottom: 4px;
}

.chamber-seat {
    width: 20px;
    height: 20px;
    margin: 0 4px;
    border-radius: 4px;
    position: relative;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
    cursor: pointer;
}

.chamber-seat:hover {
    transform: scale(1.5);
    z-index: 50;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.chamber-seat.highlight {
    transform: scale(1.2);
    z-index: 20;
    box-shadow: 0 0 0 2px white, 0 0 0 4px currentColor;
}

.chamber-seat.dim {
    opacity: 0.3;
}

.seat-tooltip {
    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 100;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.chamber-seat.active .seat-tooltip {
    opacity: 1;
}

.seat-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

/* Row-specific styling to create half-circle effect */
.chamber-row-0 { margin-top: 0; }
.chamber-row-1 { margin-top: 2px; }
.chamber-row-2 { margin-top: 2px; }
.chamber-row-3 { margin-top: 2px; }
.chamber-row-4 { margin-top: 2px; }
.chamber-row-5 { margin-top: 3px; }
.chamber-row-6 { margin-top: 3px; }
.chamber-row-7 { margin-top: 3px; }
.chamber-row-8 { margin-top: 3px; }
.chamber-row-9 { margin-top: 4px; }
.chamber-row-10 { margin-top: 4px; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .chamber {
        height: 280px;
        border-radius: 140px 140px 0 0;
    }
    
    .chamber-seat {
        width: 16px;
        height: 16px;
        margin: 0 2px;
    }
    
    .chamber-grid {
        padding-bottom: 50px;
    }
}

@media (max-width: 540px) {
    .chamber {
        height: 200px;
        border-radius: 100px 100px 0 0;
    }
    
    .chamber-seat {
        width: 10px;
        height: 10px;
        margin: 0 2px;
    }
    
    .chamber-grid {
        padding-bottom: 40px;
    }
}

/* Enhanced Party Card styling */
.party-card {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transform-origin: center;
    will-change: transform, box-shadow;
    backface-visibility: hidden;
}

.party-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 0.75rem;
    pointer-events: none;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    opacity: 0;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: -1;
}

.party-card:hover {
    transform: translateY(-5px);
}

.party-card:hover::after {
    opacity: 1;
}

.party-card .party-btn,
.party-card .leader-btn {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.party-card .party-btn:after,
.party-card .leader-btn:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120%;
    height: 0;
    padding-bottom: 120%;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    z-index: -1;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: rgba(255, 255, 255, 0.1);
}

.party-card .party-btn:hover:after,
.party-card .leader-btn:hover:after {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}

.party-card .party-btn:active,
.party-card .leader-btn:active {
    transform: translateY(1px);
}

/* Additional styles for Coalition Maker */
#selected-coalition.bg-gray-100 {
    background-color: rgba(243, 244, 246, 0.7);
    border: 2px dashed #d1d5db;
}

.party-item {
    cursor: pointer;
    user-select: none;
}

/* Spectrum visualization */
#coalition-spectrum-container {
    height: 20px;
}

#coalition-spectrum-indicator {
    width: 4px;
    height: 24px;
    background-color: rgba(0, 0, 0, 0.8);
    position: absolute;
    top: -2px;
    transform: translateX(-50%);
    border-radius: 2px;
}
</style>


<?php
// Include the footer
include_once BASE_PATH . '/views/templates/footer.php';
?> 