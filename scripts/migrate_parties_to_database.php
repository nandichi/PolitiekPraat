<?php
// Migration script to move party data from hardcoded arrays to database
// Run this script to populate the political_parties table

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path setup
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include database connection
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';

// Create database connection
$db = new Database();
$conn = $db->getConnection();

// All party data from the original hardcoded arrays
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
        ],
        'color' => '#1E3A8A'
    ],
    'VVD' => [
        'name' => 'Volkspartij voor Vrijheid en Democratie',
        'leader' => 'Dilan Yesilgoz-Zegerius',
        'logo' => 'https://logo.clearbit.com/vvd.nl',
        'leader_photo' => '/partijleiders/dilan.jpg',
        'description' => 'De VVD is een dynamische rechtsliberale partij die inzet op individuele vrijheid, economische groei en een efficiënte overheid. Zij pleiten voor lagere belastingen, minder bureaucratie en een marktgerichte economie waarin ondernemerschap centraal staat. Met een focus op pragmatische oplossingen wil de partij een stabiele en toekomstgerichte samenleving creëren, waarin ruimte is voor zowel innovatie als traditionele waarden.',
        'leader_info' => 'Dilan Yesilgoz-Zegerius, sinds 2023 partijleider, brengt een frisse wind in de VVD. Met haar achtergrond als minister van Justitie en Veiligheid en haar ervaring in de lokale politiek weet zij complexe vraagstukken op een heldere en toegankelijke manier te presenteren. Haar modernisering van de partij en haar focus op zowel economische als maatschappelijke vernieuwing maken haar een inspirerende leider voor een nieuwe generatie kiezers.',
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
        ],
        'color' => '#0066CC'
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
        ],
        'color' => '#FF6600'
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
        ],
        'color' => '#32CD32'
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
        ],
        'color' => '#DC143C'
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
        ],
        'color' => '#32CD32'
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
        'polling' => ['seats' => 7, 'percentage' => 4.7, 'change' => +2],
        'perspectives' => [
            'left' => 'De SP vecht consequent tegen ongelijkheid en voor een robuust sociaal vangnet, en komt op voor de rechten van werknemers en economisch kwetsbare groepen.',
            'right' => 'Hun benadering van politiek vanuit de basis en focus op het luisteren naar zorgen van gewone burgers helpt echte gemeenschapsproblemen aan te pakken.'
        ],
        'color' => '#FF0000'
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
        ],
        'color' => '#228B22'
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
        ],
        'color' => '#FFD700'
    ],
    'JA21' => [
        'name' => 'JA21',
        'leader' => 'Joost Eerdmans',
        'logo' => 'https://i.ibb.co/J7Mp7bj/ja21.png',
        'leader_photo' => '/partijleiders/joost.jpg',
        'description' => 'JA21 positioneert zich als een conservatief-liberale partij die een verfijnde balans zoekt tussen economische vrijheid en conservatieve waarden. Ze pleiten voor een kleinere overheid met efficiënte uitvoering, terwijl ze tegelijkertijd traditionele normen en waarden koesteren. Met een pragmatische benadering van complexe maatschappelijke vraagstukken willen ze Nederland weer op het rechte pad brengen zonder in extremisme te vervallen.',
        'leader_info' => 'Joost Eerdmans, sinds 2020 de drijvende kracht achter JA21, brengt een unieke combinatie van ervaring mee uit zowel de lokale als nationale politiek. Zijn achtergrond in Rotterdam en zijn scherpe analytische vaardigheden maken hem tot een betrouwbare stem voor conservatieve vernieuwing en economische stabiliteit.',
        'standpoints' => [
            'Immigratie' => 'Streng maar rechtvaardig asielbeleid met focus op veiligheid en integratie',
            'Klimaat' => 'Marktgerichte klimaatoplossingen zonder economische schade',
            'Zorg' => 'Geleidelijke aanpassingen aan het zorgsysteem in plaats van radicale veranderingen',
            'Energie' => 'Kernenergie als pragmatische oplossing voor klimaatdoelen'
        ],
        'current_seats' => 1,
        'polling' => ['seats' => 2, 'percentage' => 1.3, 'change' => +1],
        'perspectives' => [
            'left' => 'JA21 ondersteunt een efficiënte overheid die waar nodig ingrijpt om maatschappelijke problemen op te lossen.',
            'right' => 'Ze pleiten voor traditionele waarden, economische vrijheid en een pragmatische aanpak van immigratie en veiligheid.'
        ],
        'color' => '#4169E1'
    ],
    'SGP' => [
        'name' => 'Staatkundig Gereformeerde Partij',
        'leader' => 'Chris Stoffer',
        'logo' => 'https://logo.clearbit.com/sgp.nl',
        'leader_photo' => '/partijleiders/Chris.jpg',
        'description' => 'De SGP is een orthodox-protestantse partij die zich inzet voor een samenleving gebaseerd op bijbelse principes en traditionele christelijke waarden. Ze pleiten voor een sterke morele grondslag in de politiek, waarbij respect voor traditie en gezinsstructuren centraal staat. Hun visie combineert conservatieve sociale standpunten met een verantwoordelijke omgang met natuurlijke hulpbronnen vanuit een rentmeesterschap-gedachte.',
        'leader_info' => 'Chris Stoffer, partijleider sinds 2023, brengt een diepe spirituele toewijding mee die zijn politieke visie vormgeeft. Zijn achtergrond in de gereformeerde gemeenschap en zijn ervaring in de Tweede Kamer maken hem tot een authentieke vertegenwoordiger van orthodox-protestantse waarden in een moderne politieke context.',
        'standpoints' => [
            'Immigratie' => 'Asielbeleid moet gebaseerd zijn op christelijke naastenliefde en praktische haalbaarheid',
            'Klimaat' => 'Rentmeesterschap van de schepping, maar met respect voor economische realiteit',
            'Zorg' => 'Zorg is een christelijke plicht, maar financiële houdbaarheid is belangrijk',
            'Energie' => 'Kernenergie is acceptabel als onderdeel van rentmeesterschap'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => 0],
        'perspectives' => [
            'left' => 'SGP benadrukt christelijke naastenliefde en zorg voor kwetsbare leden van de samenleving.',
            'right' => 'Ze verdedigen traditionele waarden, het belang van het gezin en een stabiele maatschappelijke orde gebaseerd op christelijke principes.'
        ],
        'color' => '#FF7F00'
    ],
    'FvD' => [
        'name' => 'Forum voor Democratie',
        'leader' => 'Thierry Baudet',
        'logo' => 'https://logo.clearbit.com/fvd.nl',
        'leader_photo' => '/partijleiders/thierry.jpg',
        'description' => 'Forum voor Democratie presenteert zich als een intellectuele rechtse partij die zich inzet voor directe democratie, nationale soevereiniteit en het behoud van de westerse beschaving. Ze pleiten voor een radicale herziening van het politieke systeem, waarbij referenda een centrale rol spelen en de stem van het volk direct wordt gehoord. Hun agenda combineert cultureel conservatisme met economische vrijheid en een kritische houding tegenover internationale instellingen.',
        'leader_info' => 'Thierry Baudet, de oprichter en leider van FvD, is een controversiële figuur die bekendstaat om zijn intellectuele benadering van politiek en zijn onconventionele standpunten. Zijn achtergrond in de rechtswetenschappen en zijn charismatische persoonlijkheid maken hem tot een polariserende maar invloedrijke stem in het Nederlandse politieke landschap.',
        'standpoints' => [
            'Immigratie' => 'Zeer streng asielbeleid met prioriteit voor de Nederlandse cultuur',
            'Klimaat' => 'Sceptisch over klimaatverandering en tegen kostbare klimaatmaatregelen',
            'Zorg' => 'Meer marktwerking in de zorg voor betere kwaliteit en efficiëntie',
            'Energie' => 'Kernenergie en fossiele brandstoffen zijn nog steeds nodig'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => 0],
        'perspectives' => [
            'left' => 'FvD pleit voor directe democratie en meer invloed van burgers op politieke beslissingen.',
            'right' => 'Ze verdedigen nationale soevereiniteit, culturele traditie en verzetten zich tegen globalisering en EU-integratie.'
        ],
        'color' => '#8B008B'
    ],
    'DENK' => [
        'name' => 'DENK',
        'leader' => 'Stephan van Baarle',
        'logo' => 'https://logo.clearbit.com/bewegingdenk.nl',
        'leader_photo' => '/partijleiders/baarle.jpg',
        'description' => 'DENK is een multiculturele partij die zich inzet voor gelijkheid, antiracisme en de rechten van minderheden. Ze pleiten voor een inclusieve samenleving waarin iedereen, ongeacht afkomst of geloof, gelijke kansen heeft. Hun agenda richt zich op het bestrijden van discriminatie, het verbeteren van integratie en het creëren van een Nederland waarin diversiteit als kracht wordt gezien.',
        'leader_info' => 'Stephan van Baarle, partijleider sinds 2023, brengt een frisse energie mee in de strijd voor gelijkheid en sociale rechtvaardigheid. Zijn achtergrond in sociale bewegingen en zijn toewijding aan antiracisme maken hem tot een krachtige stem voor multiculturele vertegenwoordiging in de Nederlandse politiek.',
        'standpoints' => [
            'Immigratie' => 'Humaan asielbeleid met focus op integratie en gelijke behandeling',
            'Klimaat' => 'Klimaatmaatregelen moeten sociale gevolgen voor kwetsbare groepen meenemen',
            'Zorg' => 'Zorg moet voor iedereen toegankelijk zijn, ongeacht sociaaleconomische status',
            'Energie' => 'Investeren in duurzame energie met aandacht voor sociale impact'
        ],
        'current_seats' => 1,
        'polling' => ['seats' => 1, 'percentage' => 0.7, 'change' => 0],
        'perspectives' => [
            'left' => 'DENK strijdt consequent tegen discriminatie en voor gelijke rechten voor alle bevolkingsgroepen.',
            'right' => 'Hun focus op integratie en gemeenschapsopbouw helpt sociale cohesie te versterken.'
        ],
        'color' => '#00CED1'
    ],
    'Volt' => [
        'name' => 'Volt',
        'leader' => 'Laurens Dassen',
        'logo' => 'https://logo.clearbit.com/voltnederland.org',
        'leader_photo' => '/partijleiders/dassen.jpg',
        'description' => 'Volt is een pan-Europese progressieve partij die zich inzet voor Europese eenheid, innovatie en een duurzame toekomst. Ze pleiten voor een federaal Europa, digitale transformatie en een wetenschappelijke benadering van politieke problemen. Hun agenda combineert pro-Europese standpunten met lokale betrokkenheid en pragmatische oplossingen voor moderne uitdagingen.',
        'leader_info' => 'Laurens Dassen, partijleider sinds 2020, brengt een internationaal perspectief mee in de Nederlandse politiek. Zijn achtergrond in economie en zijn ervaring met Europese netwerken maken hem tot een inspirerende leider voor een nieuwe generatie die gelooft in Europese samenwerking en technologische innovatie.',
        'standpoints' => [
            'Immigratie' => 'Europese samenwerking voor een eerlijke verdeling van asielzoekers',
            'Klimaat' => 'Ambitieuze klimaatdoelen met Europese coördinatie',
            'Zorg' => 'Modernisering van het zorgsysteem met behoud van toegankelijkheid',
            'Energie' => 'Kernenergie kan onderdeel zijn van een duurzame energiemix'
        ],
        'current_seats' => 2,
        'polling' => ['seats' => 2, 'percentage' => 1.3, 'change' => 0],
        'perspectives' => [
            'left' => 'Volt pleit voor Europese samenwerking bij het oplossen van grensoverschrijdende problemen en sociale uitdagingen.',
            'right' => 'Hun focus op innovatie en digitalisering helpt de economie te moderniseren en concurrentievoordeel te behouden.'
        ],
        'color' => '#663399'
    ],
    'CU' => [
        'name' => 'ChristenUnie',
        'leader' => 'Mirjam Bikker',
        'logo' => 'https://logo.clearbit.com/christenunie.nl',
        'leader_photo' => '/partijleiders/mirjam.jpg',
        'description' => 'De ChristenUnie is een christelijke partij die zich inzet voor een rechtvaardige samenleving op basis van christelijke waarden. Ze combineren sociale betrokkenheid met respect voor traditie en pleiten voor een evenwichtige aanpak van maatschappelijke vraagstukken. Hun agenda richt zich op het beschermen van kwetsbare groepen, het bevorderen van duurzaamheid en het behouden van christelijke waarden in een moderne context.',
        'leader_info' => 'Mirjam Bikker, partijleider sinds 2023, brengt een frisse benadering mee in de christelijke politiek. Haar achtergrond in sociale zaken en haar toewijding aan rechtvaardige verdeling maken haar tot een inspirerende leider die christelijke waarden vertaalt naar moderne politieke oplossingen.',
        'standpoints' => [
            'Immigratie' => 'Christelijke naastenliefde en praktische grenzen moeten in balans zijn',
            'Klimaat' => 'Rentmeesterschap van de schepping vereist actief klimaatbeleid',
            'Zorg' => 'Zorg is een christelijke plicht, verdere verlaging eigen risico gewenst',
            'Energie' => 'Kernenergie is acceptabel als onderdeel van duurzame energiemix'
        ],
        'current_seats' => 3,
        'polling' => ['seats' => 3, 'percentage' => 2.0, 'change' => 0],
        'perspectives' => [
            'left' => 'ChristenUnie toont sterke sociale betrokkenheid en zet zich in voor kwetsbare groepen en rechtvaardige verdeling.',
            'right' => 'Ze verdedigen christelijke waarden en tradities als fundament voor een stabiele samenleving.'
        ],
        'color' => '#FFA500'
    ]
];

echo "Starting political parties migration...\n";

// Prepare statement
$stmt = $conn->prepare("
    INSERT INTO political_parties (
        party_key, name, leader, logo, leader_photo, description, leader_info, 
        standpoints, current_seats, polling, perspectives, color, is_active
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$success_count = 0;
$total_count = count($parties);

foreach ($parties as $party_key => $party_data) {
    try {
        $stmt->execute([
            $party_key,
            $party_data['name'],
            $party_data['leader'],
            $party_data['logo'],
            $party_data['leader_photo'],
            $party_data['description'],
            $party_data['leader_info'],
            json_encode($party_data['standpoints'], JSON_UNESCAPED_UNICODE),
            $party_data['current_seats'],
            json_encode($party_data['polling'], JSON_UNESCAPED_UNICODE),
            json_encode($party_data['perspectives'], JSON_UNESCAPED_UNICODE),
            $party_data['color'],
            true
        ]);
        
        $success_count++;
        echo "✓ {$party_key} - {$party_data['name']} successfully migrated\n";
    } catch (Exception $e) {
        echo "✗ Error migrating {$party_key}: " . $e->getMessage() . "\n";
    }
}

echo "\nMigration completed!\n";
echo "Successfully migrated: {$success_count}/{$total_count} parties\n";

if ($success_count === $total_count) {
    echo "🎉 All parties have been successfully migrated to the database!\n";
} else {
    echo "⚠️  Some parties failed to migrate. Check the errors above.\n";
}
?> 