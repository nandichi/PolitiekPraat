<?php
require_once '../../includes/config.php';
require_once '../../includes/Database.php';
require_once '../../includes/ChatGPTAPI.php';

// Zorg ervoor dat dit alleen via AJAX wordt aangeroepen
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    http_response_code(405);
    exit('Method not allowed');
}

// Set content type naar JSON
header('Content-Type: application/json');

try {
    // Controleer vereiste parameters
    if (!isset($_POST['slug']) || empty($_POST['slug'])) {
        throw new Exception('Blog slug is vereist');
    }
    
    if (!isset($_POST['type']) || !in_array($_POST['type'], ['party', 'leader'])) {
        throw new Exception('Type moet party of leader zijn');
    }
    
    if (!isset($_POST['party']) || empty($_POST['party'])) {
        throw new Exception('Partij is vereist');
    }

    $slug = trim($_POST['slug']);
    $type = $_POST['type'];
    $partyKey = $_POST['party'];
    
    // Party data - normaal zou dit uit een database komen
    $parties = [
        'PVV' => [
            'name' => 'Partij voor de Vrijheid',
            'leader' => 'Geert Wilders',
            'logo' => 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg',
            'leader_photo' => '/partijleiders/geert.jpg',
            'description' => 'De PVV is een uitgesproken rechtse partij die zich met volle overgave inzet voor het behoud van de Nederlandse identiteit en cultuur. Ze pleiten voor extreem strengere immigratieregels, een harde lijn tegen islamisering en een duidelijke terugtrekking uit Europese besluitvorming.',
            'leader_info' => 'Geert Wilders leidt de PVV sinds 2006 en is een van de meest controversiële en herkenbare figuren in de Nederlandse politiek. Zijn scherpe uitspraken en gedurfde aanpak hebben hem zowel lovende als felle critici opgeleverd.',
            'standpoints' => [
                'Immigratie' => 'Een strenger asielbeleid met een volledige asielstop',
                'Klimaat' => 'Kritisch over ambitieuze klimaatmaatregelen als deze economische groei belemmeren',
                'Zorg' => 'Afschaffen van het eigen risico in de zorg',
                'Energie' => 'Voorstander van kernenergie als onderdeel van de energiemix'
            ]
        ],
        'VVD' => [
            'name' => 'Volkspartij voor Vrijheid en Democratie',
            'leader' => 'Dilan Yeşilgöz-Zegerius',
            'logo' => 'https://logo.clearbit.com/vvd.nl',
            'leader_photo' => '/partijleiders/dilan.jpg',
            'description' => 'De VVD is een dynamische rechtsliberale partij die inzet op individuele vrijheid, economische groei en een efficiënte overheid. Zij pleiten voor lagere belastingen, minder bureaucratie en een marktgerichte economie waarin ondernemerschap centraal staat.',
            'leader_info' => 'Dilan Yeşilgöz-Zegerius, sinds 2023 partijleider, brengt een frisse wind in de VVD. Met haar achtergrond als minister van Justitie en Veiligheid weet zij complexe vraagstukken op een heldere en toegankelijke manier te presenteren.',
            'standpoints' => [
                'Immigratie' => 'Strengere selectie en beperking van asielaanvragen, met internationale samenwerking',
                'Klimaat' => 'Ondersteunt klimaatmaatregelen maar niet ten koste van economische groei',
                'Zorg' => 'Behoud van eigen risico om zorgkosten beheersbaar te houden',
                'Energie' => 'Voorstander van kernenergie als aanvulling op duurzame bronnen'
            ]
        ],
        'GL-PvdA' => [
            'name' => 'GroenLinks-PvdA',
            'leader' => 'Frans Timmermans',
            'logo' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
            'leader_photo' => '/partijleiders/frans.jpg',
            'description' => 'GL-PvdA is een krachtige progressieve alliantie die zich inzet voor sociale rechtvaardigheid, duurzaamheid en gelijke kansen voor iedereen. Ze combineren de idealen van groen beleid met de solidariteit en maatschappelijke betrokkenheid van de PvdA.',
            'leader_info' => 'Frans Timmermans, de lijsttrekker sinds 2023, brengt een schat aan internationale ervaring mee door zijn jarenlange betrokkenheid bij de Europese Commissie en de Europese Green Deal.',
            'standpoints' => [
                'Immigratie' => 'Humanitaire principes moeten centraal staan in het asielbeleid',
                'Klimaat' => 'Ambitieuze klimaatmaatregelen, ook als daar op korte termijn offers voor nodig zijn',
                'Zorg' => 'Voorstander van afschaffing van het eigen risico voor gelijke toegang tot zorg',
                'Energie' => 'Tegen kernenergie vanwege risico\'s en lange doorlooptijden'
            ]
        ],
        'NSC' => [
            'name' => 'Nieuw Sociaal Contract',
            'leader' => 'Nicolien van Vroonhoven',
            'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
            'leader_photo' => 'https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg',
            'description' => 'NSC is een baanbrekende partij die staat voor transparantie, eerlijk bestuur en een fundamentele herwaardering van de democratische instituties. Zij leggen de nadruk op integriteit, een verantwoordelijke overheid en het herstel van het vertrouwen in de politiek.',
            'leader_info' => 'Nicolien van Vroonhoven, de huidige leider van NSC, zet het werk van oprichter Pieter Omtzigt voort met dezelfde toewijding aan transparantie en integriteit.',
            'standpoints' => [
                'Immigratie' => 'Een doordacht asielbeleid dat zowel veiligheid als humanitaire zorg waarborgt',
                'Klimaat' => 'Evenwichtige aanpak waarbij zowel klimaat als economie belangrijk zijn',
                'Zorg' => 'Overweegt aanpassingen in plaats van volledige afschaffing van het eigen risico',
                'Energie' => 'Open voor kernenergie als het bijdraagt aan een stabiele energiemix'
            ]
        ],
        'BBB' => [
            'name' => 'BoerBurgerBeweging',
            'leader' => 'Caroline van der Plas',
            'logo' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
            'leader_photo' => '/partijleiders/plas.jpg',
            'description' => 'BBB vertegenwoordigt de belangen van de agrarische sector en het platteland met een visie die traditie en innovatie combineert. Zij staan voor een duurzaam boerenbeleid, investeren in lokale gemeenschappen en streven ernaar de kloof tussen stad en platteland drastisch te verkleinen.',
            'leader_info' => 'Caroline van der Plas is sinds 2019 de charismatische leider van BBB. Met een achtergrond in agrarische journalistiek en PR weet zij de zorgen en ambities van de boeren direct over te brengen.',
            'standpoints' => [
                'Immigratie' => 'Ondersteuning van een streng asielbeleid en beperking van de instroom',
                'Klimaat' => 'Sceptisch over ingrijpende klimaatmaatregelen, vooral als deze de agrarische sector schaden',
                'Zorg' => 'Voorstander van het verlagen van het eigen risico voor betere zorgtoegankelijkheid',
                'Energie' => 'Ziet kernenergie als betrouwbaar onderdeel van de energietransitie'
            ]
        ],
        'D66' => [
            'name' => 'Democraten 66',
            'leader' => 'Rob Jetten',
            'logo' => 'https://logo.clearbit.com/d66.nl',
            'leader_photo' => '/partijleiders/rob.jpg',
            'description' => 'D66 staat voor een open, progressief-liberale samenleving waarin onderwijs, innovatie en democratische vernieuwing centraal staan. Ze pleiten voor een moderne overheid die inspeelt op de uitdagingen van de 21e eeuw.',
            'leader_info' => 'Rob Jetten, die de leiding overnam na Sigrid Kaag, is een visionaire politicus met een indrukwekkende staat van dienst als voormalig minister voor Klimaat en Energie.',
            'standpoints' => [
                'Immigratie' => 'Humaan maar gestructureerd asielbeleid met veilige en legale routes',
                'Klimaat' => 'Nederland moet een leidende rol spelen in de klimaattransitie',
                'Zorg' => 'Voorstander van bevriezen eigen risico met een limiet per behandeling',
                'Energie' => 'Kritisch over kernenergie, maar innovatie en veiligheid kunnen doorslaggevend zijn'
            ]
        ],
        'SP' => [
            'name' => 'Socialistische Partij',
            'leader' => 'Jimmy Dijk',
            'logo' => 'https://logo.clearbit.com/sp.nl',
            'leader_photo' => '/partijleiders/jimmy.jpg',
            'description' => 'De SP is een vurige linkse partij die strijdbaar opkomt tegen sociale ongelijkheid en voor een krachtige verzorgingsstaat.',
            'leader_info' => 'Jimmy Dijk, sinds 2023 de energieke leider van de SP, komt uit een achtergrond van lokale politiek en vakbewegingen.',
            'standpoints' => [
                'Immigratie' => 'Verbetering van opvang en integratie is even belangrijk als beperking van instroom',
                'Klimaat' => 'Klimaatmaatregelen moeten eerlijk worden verdeeld',
                'Zorg' => 'Afschaffen van het eigen risico voor een eerlijker zorgsysteem',
                'Energie' => 'Tegen investeringen in kerncentrales, liever inzetten op duurzame energie'
            ]
        ],
        'PvdD' => [
            'name' => 'Partij voor de Dieren',
            'leader' => 'Esther Ouwehand',
            'logo' => 'https://logo.clearbit.com/partijvoordedieren.nl',
            'leader_photo' => '/partijleiders/esther.jpg',
            'description' => 'De PvdD combineert dierenwelzijn met een brede visie op duurzaamheid en natuurbehoud.',
            'leader_info' => 'Esther Ouwehand is sinds 2019 de drijvende kracht achter de PvdD.',
            'standpoints' => [
                'Immigratie' => 'Asielbeleid moet mensenrechten respecteren en aandacht hebben voor ecologische context',
                'Klimaat' => 'Voorstander van radicaal klimaatbeleid, ongeacht economische kortetermijnnadelen',
                'Zorg' => 'Zorg moet toegankelijk zijn zonder financiële drempels',
                'Energie' => 'Kernenergie is verouderd, inzetten op hernieuwbare energiebronnen'
            ]
        ],
        'CDA' => [
            'name' => 'Christen-Democratisch Appèl',
            'leader' => 'Henri Bontenbal',
            'logo' => 'https://logo.clearbit.com/cda.nl',
            'leader_photo' => '/partijleiders/Henri.jpg',
            'description' => 'Het CDA staat voor een samenleving gebaseerd op christendemocratische waarden, waarbij solidariteit, rentmeesterschap en gemeenschapszin centraal staan.',
            'leader_info' => 'Henri Bontenbal, partijleider sinds 2023, brengt een rijke ervaring mee uit zowel de energiesector als zijn tijd als Tweede Kamerlid.',
            'standpoints' => [
                'Immigratie' => 'Pleit voor een onderscheidend beleid met duidelijke scheiding tussen tijdelijke en permanente bescherming',
                'Klimaat' => 'Combinatie van klimaatmaatregelen en behoud van economische stabiliteit',
                'Zorg' => 'Voorstander van gerichte verlaging van het eigen risico',
                'Energie' => 'Kernenergie als onderdeel van een brede energiemix, mits goed gereguleerd'
            ]
        ],
        'JA21' => [
            'name' => 'Juiste Antwoord 2021',
            'leader' => 'Joost Eerdmans',
            'logo' => 'https://logo.clearbit.com/ja21.nl',
            'leader_photo' => '/partijleiders/joost.jpg',
            'description' => 'JA21 is een conservatief-liberale partij die met een no-nonsense aanpak de politieke status quo uitdaagt.',
            'leader_info' => 'Joost Eerdmans, medeoprichter en partijleider, brengt een kleurrijke en gedurfde achtergrond mee als voormalig wethouder en Kamerlid.',
            'standpoints' => [
                'Immigratie' => 'Ondersteuning van een restrictief asielbeleid met strikte toelatingscriteria',
                'Klimaat' => 'Wil niet dat klimaatmaatregelen de economische groei te veel hinderen',
                'Zorg' => 'Vindt een zekere mate van eigen bijdrage noodzakelijk voor efficiëntie',
                'Energie' => 'Voorstander van kernenergie voor energiezekerheid en emissiereductie'
            ]
        ],
        'SGP' => [
            'name' => 'Staatkundig Gereformeerde Partij',
            'leader' => 'Chris Stoffer',
            'logo' => 'https://logo.clearbit.com/sgp.nl',
            'leader_photo' => '/partijleiders/Chris.jpg',
            'description' => 'De SGP is een traditionele, christelijke partij die haar politiek baseert op strikte bijbelse principes en morele waarden.',
            'leader_info' => 'Chris Stoffer leidt de SGP sinds 2023 en staat bekend om zijn compromisloze inzet voor christelijke waarden.',
            'standpoints' => [
                'Immigratie' => 'Voorstander van een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan',
                'Klimaat' => 'Vindt dat maatregelen verantwoord moeten zijn en de economie niet te zwaar mogen belasten',
                'Zorg' => 'Eigen risico als middel om onnodig gebruik van zorg te beperken, met ruimte voor verlaging bij kwetsbare groepen',
                'Energie' => 'Kernenergie als middel om de afhankelijkheid van fossiele brandstoffen te verminderen'
            ]
        ],
        'FvD' => [
            'name' => 'Forum voor Democratie',
            'leader' => 'Thierry Baudet',
            'logo' => 'https://logo.clearbit.com/fvd.nl',
            'leader_photo' => '/partijleiders/thierry.jpg',
            'description' => 'FvD is een controversiële rechts-conservatieve partij die met felle retoriek opkomt voor nationale soevereiniteit en directe democratie.',
            'leader_info' => 'Thierry Baudet, de oprichter van FvD in 2016, is een intellectueel en controversieel denker die met zijn scherpe analyses en provocerende uitspraken de gevestigde orde voortdurend uitdaagt.',
            'standpoints' => [
                'Immigratie' => 'Pleit voor het beëindigen van het internationale asielkader en wil asielaanvragen sterk beperken',
                'Klimaat' => 'Betwist de urgentie van de klimaatcrisis en wil geen maatregelen die de economie schaden',
                'Zorg' => 'Voorstander van afschaffing van het eigen risico voor toegankelijke zorg',
                'Energie' => 'Wil investeren in kernenergie als alternatief voor fossiele brandstoffen'
            ]
        ],
        'DENK' => [
            'name' => 'DENK',
            'leader' => 'Stephan van Baarle',
            'logo' => 'https://logo.clearbit.com/bewegingdenk.nl',
            'leader_photo' => '/partijleiders/baarle.jpg',
            'description' => 'DENK staat voor een inclusieve samenleving waarin iedereen, ongeacht achtergrond, gelijke kansen krijgt.',
            'leader_info' => 'Stephan van Baarle, partijleider sinds 2021, komt uit een lokale politieke achtergrond en heeft zich altijd ingezet voor de rechten van minderheden.',
            'standpoints' => [
                'Immigratie' => 'Kiest voor een humaan asielbeleid met aandacht voor solidariteit en internationale samenwerking',
                'Klimaat' => 'Wil een genuanceerde aanpak waarbij zowel klimaat als economie worden meegenomen',
                'Zorg' => 'Wil het eigen risico aanzienlijk verlagen om zorg voor iedereen bereikbaar te maken',
                'Energie' => 'Staat open voor kernenergie als het veilig en verantwoord wordt ingezet'
            ]
        ],
        'Volt' => [
            'name' => 'Volt Nederland',
            'leader' => 'Laurens Dassen',
            'logo' => 'https://logo.clearbit.com/voltnederland.org',
            'leader_photo' => '/partijleiders/dassen.jpg',
            'description' => 'Volt Nederland is een vernieuwende pan-Europese partij die zich inzet voor een geïntegreerd en democratisch Europa.',
            'leader_info' => 'Laurens Dassen, medeoprichter en partijleider, brengt met zijn achtergrond in de financiële sector en zijn sterke pro-Europese visie een frisse, internationale blik in de Nederlandse politiek.',
            'standpoints' => [
                'Immigratie' => 'Staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert',
                'Klimaat' => 'Pleit voor ambitieuze maatregelen en gelooft dat de lange termijn voordelen opwegen tegen de korte termijn kosten',
                'Zorg' => 'Open voor verlaging van het eigen risico, mits dit financieel haalbaar is',
                'Energie' => 'Voorkeur voor hernieuwbare energie, maar open voor kernenergie bij strenge veiligheidseisen'
            ]
        ],
        'CU' => [
            'name' => 'ChristenUnie',
            'leader' => 'Mirjam Bikker',
            'logo' => 'https://logo.clearbit.com/christenunie.nl',
            'leader_photo' => 'https://i.ibb.co/wh3wwQ66/Bikker.jpg',
            'description' => 'De ChristenUnie is een sociaal-christelijke partij die geloof en politiek combineert met een sterke focus op duurzaamheid, sociale rechtvaardigheid en gezinswaarden.',
            'leader_info' => 'Mirjam Bikker, partijleider sinds 2023, staat bekend om haar doortastende en constructieve aanpak.',
            'standpoints' => [
                'Immigratie' => 'Een humaan asielbeleid met nadruk op veilige opvang en integratie',
                'Klimaat' => 'Ambitieuze klimaatdoelen vanuit rentmeesterschap',
                'Zorg' => 'Verlaging van het eigen risico voor kwetsbare groepen',
                'Energie' => 'Inzet op duurzame energie, kritisch op kernenergie'
            ]
        ]
    ];
    
    if (!isset($parties[$partyKey])) {
        throw new Exception('Onbekende partij');
    }
    
    $partyData = $parties[$partyKey];
    
    // Haal blog gegevens op
    $db = new Database();
    $db->query("SELECT blogs.*, users.username as author_name 
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                WHERE blogs.slug = :slug");
    $db->bind(':slug', $slug);
    $blog = $db->single();
    
    if (!$blog) {
        throw new Exception('Blog niet gevonden');
    }
    
    // Initialiseer ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    // Genereer perspectief
    if ($type === 'party') {
        $result = $chatGPT->generatePartyPerspective(
            $partyData['name'],
            $partyData,
            $blog->title,
            $blog->content
        );
    } else {
        $result = $chatGPT->generateLeaderPerspective(
            $partyData['leader'],
            $partyData['name'],
            $partyData,
            $blog->title,
            $blog->content
        );
    }
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'content' => $result['content'],
            'party' => $partyData['name'],
            'leader' => $partyData['leader'],
            'leaderPhoto' => $partyData['leader_photo'] ?? null,
            'logo' => $partyData['logo'] ?? null,
            'type' => $type
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception($result['error'] ?? 'Fout bij genereren perspectief');
    }
    
} catch (Exception $e) {
    // Error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 