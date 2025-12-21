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

/**
 * Valideer en standaardiseer de uitgebreide leider analyse response
 */
function validateLeaderAnalysis($analysis) {
    $defaults = [
        'reactie' => [
            'opening' => '',
            'hoofdtekst' => '',
            'afsluiting' => ''
        ],
        'analyse' => [
            'toon' => 'neutraal',
            'sentiment' => 0,
            'emotie' => 'neutraal'
        ],
        'standpunten' => [
            'kernpunten' => [],
            'eens_met_artikel' => [],
            'oneens_met_artikel' => []
        ],
        'partij_context' => [
            'relevante_beloftes' => [],
            'voorgestelde_oplossing' => ''
        ],
        'meta' => [
            'authenticiteit_score' => 50,
            'retorische_stijl' => 'neutraal'
        ]
    ];
    
    $result = [];
    
    foreach ($defaults as $key => $defaultValue) {
        if (isset($analysis[$key])) {
            if (is_array($defaultValue) && is_array($analysis[$key])) {
                $result[$key] = array_merge($defaultValue, $analysis[$key]);
            } else {
                $result[$key] = $analysis[$key];
            }
        } else {
            $result[$key] = $defaultValue;
        }
    }
    
    // Zorg ervoor dat sentiment numeriek is en binnen bereik
    if (isset($result['analyse']['sentiment'])) {
        $result['analyse']['sentiment'] = max(-100, min(100, intval($result['analyse']['sentiment'])));
    }
    
    // Zorg ervoor dat authenticiteit numeriek is
    if (isset($result['meta']['authenticiteit_score'])) {
        $result['meta']['authenticiteit_score'] = max(0, min(100, intval($result['meta']['authenticiteit_score'])));
    }
    
    return $result;
}

/**
 * Verkort blog content voor veilige API calls
 */
function truncateContentForAPI($content, $maxLength = 3000) {
    if (empty($content)) {
        return $content;
    }
    
    // Strip Markdown syntax eerst
    $content = stripMarkdownSyntax($content);
    
    // Als content al kort genoeg is, return direct
    if (mb_strlen($content) <= $maxLength) {
        return $content;
    }
    
    // Verkort tot maxLength, maar eindig op een volledige zin
    $truncated = mb_substr($content, 0, $maxLength);
    
    // Zoek de laatste punt, uitroepteken of vraagteken
    $lastSentenceEnd = max(
        mb_strrpos($truncated, '.'),
        mb_strrpos($truncated, '!'),
        mb_strrpos($truncated, '?')
    );
    
    if ($lastSentenceEnd !== false && $lastSentenceEnd > $maxLength * 0.7) {
        // Als we een goede zin-einde vinden binnen 70% van de lengte, gebruik die
        $truncated = mb_substr($truncated, 0, $lastSentenceEnd + 1);
    } else {
        // Anders, zoek de laatste spatie om midden in een woord te voorkomen
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLength * 0.8) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }
        $truncated .= '...';
    }
    
    return trim($truncated);
}

/**
 * Strip Markdown syntax voor betere content extractie
 */
function stripMarkdownSyntax($text) {
    if (empty($text)) {
        return $text;
    }
    
    // Strip verschillende Markdown elementen
    $text = preg_replace('/^#{1,6}\s+/m', '', $text); // Headers
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Bold
    $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Italic
    $text = preg_replace('/`(.*?)`/', '$1', $text); // Inline code
    $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text); // Links
    $text = preg_replace('/^\s*[-*+]\s+/m', '• ', $text); // Unordered lists
    $text = preg_replace('/^\s*\d+\.\s+/m', '', $text); // Ordered lists
    $text = preg_replace('/^>\s+/m', '', $text); // Blockquotes
    $text = preg_replace('/```.*?```/s', '', $text); // Code blocks
    $text = preg_replace('/^\s*---+\s*$/m', '', $text); // Horizontal rules
    
    // Vervang multiple whitespace met single space
    $text = preg_replace('/\s+/', ' ', $text);
    $text = preg_replace('/\n\s*\n/', "\n\n", $text);
    
    return trim($text);
}

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
            'description' => 'De PVV is een rechtse partij die zich fel verzet tegen massa-immigratie en EU-bureaucratie.',
            'leader_info' => 'Geert Wilders, sinds 2006 de onvermoeide leider van de PVV, staat bekend om zijn directe en oncompromis houding.',
            'standpoints' => [
                'Immigratie' => 'Stoppen met massa-immigratie en asielinstroom',
                'Klimaat' => 'Klimaatbeleid mag Nederlandse economie niet kapot maken',
                'Zorg' => 'Afschaffen van het eigen risico',
                'Energie' => 'Kernenergie als schone en betrouwbare energiebron'
            ]
        ],
        'VVD' => [
            'name' => 'Volkspartij voor Vrijheid en Democratie',
            'leader' => 'Dilan Yeşilgöz-Zegerius',
            'logo' => 'https://logo.clearbit.com/vvd.nl',
            'leader_photo' => '/partijleiders/dilan.jpg',
            'description' => 'De VVD is een liberale partij die voorstander is van ondernemerschap, vrijheid en een sterke economie.',
            'leader_info' => 'Dilan Yeşilgöz-Zegerius, partijleider sinds 2023, brengt een rijke ervaring mee als voormalig minister.',
            'standpoints' => [
                'Immigratie' => 'Gecontroleerde immigratie met strenge integratie-eisen',
                'Klimaat' => 'Marktconforme klimaatoplossingen zonder economische schade',
                'Zorg' => 'Behoud van eigen risico voor kostenbewustzijn',
                'Energie' => 'Kernenergie als onderdeel van duurzame energiemix'
            ]
        ],
        'GL-PvdA' => [
            'name' => 'GroenLinks-PvdA',
            'leader' => 'Frans Timmermans',
            'logo' => 'https://i.ibb.co/67hkc5Hv/gl-pvda.png',
            'leader_photo' => '/partijleiders/frans.jpg',
            'description' => 'De GroenLinks-PvdA alliantie staat voor een eerlijke en groene toekomst.',
            'leader_info' => 'Frans Timmermans, voormalig Eurocommissaris, brengt internationale ervaring mee.',
            'standpoints' => [
                'Immigratie' => 'Humaan asielbeleid met goede opvang en integratie',
                'Klimaat' => 'Ambitieus klimaatbeleid met sociale rechtvaardigheid',
                'Zorg' => 'Afschaffen van het eigen risico voor toegankelijke zorg',
                'Energie' => 'Volledig duurzame energie, tegen kernenergie'
            ]
        ],
        'NSC' => [
            'name' => 'Nieuw Sociaal Contract',
            'leader' => 'Nicolien van Vroonhoven',
            'logo' => 'https://i.ibb.co/YT2fJZb4/nsc.png',
            'leader_photo' => 'https://i.ibb.co/NgY27GmZ/nicolien-van-vroonhoven-en-piete.jpg',
            'description' => 'NSC wil de politiek vernieuwen met een nieuwe bestuurscultuur.',
            'leader_info' => 'Nicolien van Vroonhoven, nieuwe partijleider, wil de politiek opnieuw uitvinden.',
            'standpoints' => [
                'Immigratie' => 'Realistisch asielbeleid met goede spreiding',
                'Klimaat' => 'Pragmatische klimaatoplossingen',
                'Zorg' => 'Verlaging van het eigen risico waar mogelijk',
                'Energie' => 'Open voor kernenergie als transitie-oplossing'
            ]
        ],
        'BBB' => [
            'name' => 'BoerBurgerBeweging',
            'leader' => 'Caroline van der Plas',
            'logo' => 'https://i.ibb.co/qMjw7jDV/bbb.png',
            'leader_photo' => '/partijleiders/plas.jpg',
            'description' => 'BBB vertegenwoordigt de belangen van boeren en het platteland.',
            'leader_info' => 'Caroline van der Plas, oprichter van BBB, vecht voor boeren en platteland.',
            'standpoints' => [
                'Immigratie' => 'Strenger asielbeleid om druk op voorzieningen te verminderen',
                'Klimaat' => 'Klimaatbeleid mag boeren niet kapot maken',
                'Zorg' => 'Verlaging van het eigen risico voor lagere inkomens',
                'Energie' => 'Kernenergie als betrouwbare energiebron'
            ]
        ],
        'D66' => [
            'name' => 'Democraten 66',
            'leader' => 'Rob Jetten',
            'logo' => 'https://logo.clearbit.com/d66.nl',
            'leader_photo' => '/partijleiders/rob.jpg',
            'description' => 'D66 staat voor een open, progressief-liberale samenleving.',
            'leader_info' => 'Rob Jetten, voormalig minister voor Klimaat en Energie.',
            'standpoints' => [
                'Immigratie' => 'Humaan maar gestructureerd asielbeleid',
                'Klimaat' => 'Nederland moet leidende rol spelen in klimaattransitie',
                'Zorg' => 'Bevriezen eigen risico met limiet per behandeling',
                'Energie' => 'Kritisch over kernenergie, maar open voor innovatie'
            ]
        ],
        'SP' => [
            'name' => 'Socialistische Partij',
            'leader' => 'Jimmy Dijk',
            'logo' => 'https://logo.clearbit.com/sp.nl',
            'leader_photo' => '/partijleiders/jimmy.jpg',
            'description' => 'De SP strijdt tegen sociale ongelijkheid.',
            'leader_info' => 'Jimmy Dijk, energieke leider uit lokale politiek.',
            'standpoints' => [
                'Immigratie' => 'Verbetering van opvang en integratie',
                'Klimaat' => 'Klimaatmaatregelen moeten eerlijk verdeeld',
                'Zorg' => 'Afschaffen van het eigen risico',
                'Energie' => 'Tegen kernenergie, voor duurzame energie'
            ]
        ],
        'PvdD' => [
            'name' => 'Partij voor de Dieren',
            'leader' => 'Esther Ouwehand',
            'logo' => 'https://logo.clearbit.com/partijvoordedieren.nl',
            'leader_photo' => '/partijleiders/esther.jpg',
            'description' => 'De PvdD combineert dierenwelzijn met duurzaamheid.',
            'leader_info' => 'Esther Ouwehand, drijvende kracht achter de PvdD.',
            'standpoints' => [
                'Immigratie' => 'Asielbeleid moet mensenrechten respecteren',
                'Klimaat' => 'Radicaal klimaatbeleid nodig',
                'Zorg' => 'Zorg zonder financiële drempels',
                'Energie' => 'Kernenergie is verouderd, hernieuwbaar is de toekomst'
            ]
        ],
        'CDA' => [
            'name' => 'Christen-Democratisch Appèl',
            'leader' => 'Henri Bontenbal',
            'logo' => 'https://logo.clearbit.com/cda.nl',
            'leader_photo' => '/partijleiders/Henri.jpg',
            'description' => 'Het CDA staat voor christendemocratische waarden.',
            'leader_info' => 'Henri Bontenbal, ervaren politicus uit energiesector.',
            'standpoints' => [
                'Immigratie' => 'Onderscheidend beleid tussen tijdelijke en permanente bescherming',
                'Klimaat' => 'Klimaatmaatregelen met behoud van economische stabiliteit',
                'Zorg' => 'Gerichte verlaging van het eigen risico',
                'Energie' => 'Kernenergie als onderdeel van energiemix'
            ]
        ],
        'JA21' => [
            'name' => 'Juiste Antwoord 2021',
            'leader' => 'Joost Eerdmans',
            'logo' => 'https://logo.clearbit.com/ja21.nl',
            'leader_photo' => '/partijleiders/joost.jpg',
            'description' => 'JA21 is een conservatief-liberale partij.',
            'leader_info' => 'Joost Eerdmans, medeoprichter met kleurrijke achtergrond.',
            'standpoints' => [
                'Immigratie' => 'Restrictief asielbeleid met strikte criteria',
                'Klimaat' => 'Klimaatmaatregelen mogen economie niet hinderen',
                'Zorg' => 'Eigen bijdrage noodzakelijk voor efficiëntie',
                'Energie' => 'Kernenergie voor energiezekerheid'
            ]
        ],
        'SGP' => [
            'name' => 'Staatkundig Gereformeerde Partij',
            'leader' => 'Chris Stoffer',
            'logo' => 'https://logo.clearbit.com/sgp.nl',
            'leader_photo' => '/partijleiders/Chris.jpg',
            'description' => 'De SGP baseert politiek op bijbelse principes.',
            'leader_info' => 'Chris Stoffer, compromisloze inzet voor christelijke waarden.',
            'standpoints' => [
                'Immigratie' => 'Zeer restrictief asielbeleid',
                'Klimaat' => 'Verantwoorde maatregelen zonder economische schade',
                'Zorg' => 'Eigen risico beperkt onnodig gebruik',
                'Energie' => 'Kernenergie als alternatief voor fossiele brandstoffen'
            ]
        ],
        'FvD' => [
            'name' => 'Forum voor Democratie',
            'leader' => 'Thierry Baudet',
            'logo' => 'https://logo.clearbit.com/fvd.nl',
            'leader_photo' => '/partijleiders/thierry.jpg',
            'description' => 'FvD is een rechts-conservatieve partij.',
            'leader_info' => 'Thierry Baudet, intellectueel en controversieel denker.',
            'standpoints' => [
                'Immigratie' => 'Beëindigen van internationaal asielkader',
                'Klimaat' => 'Betwist urgentie van klimaatcrisis',
                'Zorg' => 'Afschaffen van het eigen risico',
                'Energie' => 'Investeren in kernenergie'
            ]
        ],
        'DENK' => [
            'name' => 'DENK',
            'leader' => 'Stephan van Baarle',
            'logo' => 'https://logo.clearbit.com/bewegingdenk.nl',
            'leader_photo' => '/partijleiders/baarle.jpg',
            'description' => 'DENK staat voor inclusieve samenleving.',
            'leader_info' => 'Stephan van Baarle, inzet voor rechten van minderheden.',
            'standpoints' => [
                'Immigratie' => 'Humaan asielbeleid met solidariteit',
                'Klimaat' => 'Genuanceerde aanpak klimaat en economie',
                'Zorg' => 'Eigen risico aanzienlijk verlagen',
                'Energie' => 'Open voor veilige kernenergie'
            ]
        ],
        'Volt' => [
            'name' => 'Volt Nederland',
            'leader' => 'Laurens Dassen',
            'logo' => 'https://logo.clearbit.com/voltnederland.org',
            'leader_photo' => '/partijleiders/dassen.jpg',
            'description' => 'Volt Nederland is een pan-Europese partij.',
            'leader_info' => 'Laurens Dassen, medeoprichter met financiële achtergrond.',
            'standpoints' => [
                'Immigratie' => 'Gemeenschappelijk Europees asielbeleid',
                'Klimaat' => 'Ambitieuze maatregelen voor lange termijn',
                'Zorg' => 'Open voor verlaging eigen risico',
                'Energie' => 'Voorkeur hernieuwbaar, open voor kernenergie'
            ]
        ],
        'CU' => [
            'name' => 'ChristenUnie',
            'leader' => 'Mirjam Bikker',
            'logo' => 'https://logo.clearbit.com/christenunie.nl',
            'leader_photo' => 'https://i.ibb.co/wh3wwQ66/Bikker.jpg',
            'description' => 'De ChristenUnie is een sociaal-christelijke partij.',
            'leader_info' => 'Mirjam Bikker, doortastende en constructieve aanpak.',
            'standpoints' => [
                'Immigratie' => 'Humaan asielbeleid met veilige opvang',
                'Klimaat' => 'Ambitieuze klimaatdoelen vanuit rentmeesterschap',
                'Zorg' => 'Verlaging eigen risico voor kwetsbare groepen',
                'Energie' => 'Duurzame energie, kritisch op kernenergie'
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
    
    // Verkort blog content voor veilige API call
    $truncatedContent = truncateContentForAPI($blog->content, 2500);
    
    // Initialiseer ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    // Genereer perspectief met verkorte content
    if ($type === 'party') {
        $result = $chatGPT->generatePartyPerspective(
            $partyData['name'],
            $partyData,
            $blog->title,
            $truncatedContent
        );
    } else {
        $result = $chatGPT->generateLeaderPerspective(
            $partyData['leader'],
            $partyData['name'],
            $partyData,
            $blog->title,
            $truncatedContent
        );
    }
    
    if ($result['success']) {
        // Parse JSON response van nieuwe gestructureerde API
        $analysis = json_decode($result['content'], true);
        
        if (json_last_error() === JSON_ERROR_NONE && isset($analysis['reactie'])) {
            // Nieuwe gestructureerde response
            $validatedAnalysis = validateLeaderAnalysis($analysis);
            
            echo json_encode([
                'success' => true,
                'analysis' => $validatedAnalysis,
                'party' => $partyData['name'],
                'leader' => $partyData['leader'],
                'leaderPhoto' => $partyData['leader_photo'] ?? null,
                'logo' => $partyData['logo'] ?? null,
                'type' => $type
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // Fallback naar legacy plain text response
            echo json_encode([
                'success' => true,
                'content' => $result['content'],
                'party' => $partyData['name'],
                'leader' => $partyData['leader'],
                'leaderPhoto' => $partyData['leader_photo'] ?? null,
                'logo' => $partyData['logo'] ?? null,
                'type' => $type
            ], JSON_UNESCAPED_UNICODE);
        }
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