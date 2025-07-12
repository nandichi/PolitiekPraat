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
 * Maakt een beknopte en veilige samenvatting van blog content voor de AI.
 * Deze functie vervangt de vorige, complexe truncate-logica.
 */
function getAISummaryForPerspective($title, $content) {
    $cleanContent = stripMarkdownSyntaxForPerspective($content);
    $length = mb_strlen($cleanContent);

    // Voor extreem lange artikelen (> 5000 chars), geef alleen het thema.
    if ($length > 5000) {
        return "Geef een reactie op het algemene thema van een zeer diepgaand artikel met de titel: \"{$title}\".";
    }

    // Voor lange artikelen, geef een samenvatting van het begin.
    if ($length > 1500) {
        $paragraphs = explode("\n\n", $cleanContent);
        $summary = $paragraphs[0] ?? '';
        if (isset($paragraphs[1])) {
            $summary .= "\n\n" . $paragraphs[1];
        }
        return "Reageer op basis van de volgende samenvatting van een artikel genaamd \"{$title}\":\n\n" . mb_substr($summary, 0, 1200) . '...';
    }

    // Voor normale artikelen, gebruik de titel en volledige content.
    return "Reageer op het volgende artikel met de titel \"{$title}\":\n\n" . $cleanContent;
}

/**
 * Basis Markdown-stripper, geoptimaliseerd voor snelheid.
 */
function stripMarkdownSyntaxForPerspective($text) {
    if (empty($text)) {
        return $text;
    }
    // Verwijder structurele markdown voor een schonere input.
    $text = preg_replace('/^#{1,6}\s+/m', '', $text); // Headers
    $text = preg_replace('/(\*\*|__)(.*?)\1/', '$2', $text); // Bold
    $text = preg_replace('/(\*|_)(.*?)\1/', '$2', $text); // Italic
    $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text); // Links
    $text = preg_replace('/^(\s*[-*+]\s+|\s*\d+\.\s+|^>\s+)/m', '', $text); // Lists & Blockquotes
    return trim(preg_replace('/\s+/', ' ', $text));
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
    
    // Maak een veilige, korte samenvatting voor de AI.
    $aiSummary = getAISummaryForPerspective($blog->title, $blog->content);

    // Finale veiligheidscheck op de lengte.
    if (mb_strlen($aiSummary) > 2500) {
        error_log("Perspective API call afgebroken: Samenvatting is na verwerking nog te lang. Lengte: " . mb_strlen($aiSummary));
        throw new Exception('Het artikel is te complex om een reactie op te genereren.');
    }
    
    // Initialiseer ChatGPT API
    try {
        $chatGPT = new ChatGPTAPI();
    } catch (Exception $e) {
        throw new Exception('AI-service is momenteel niet beschikbaar. Probeer het later opnieuw.');
    }
    
    // Genereer perspectief met de veilige samenvatting
    if ($type === 'party') {
        $result = $chatGPT->generatePartyPerspective(
            $partyData['name'],
            $partyData,
            $blog->title,
            $aiSummary
        );
    } else {
        $result = $chatGPT->generateLeaderPerspective(
            $partyData['leader'],
            $partyData['name'],
            $partyData,
            $blog->title,
            $aiSummary
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
        // Specifieke error handling voor verschillende API fouten
        $errorMessage = $result['error'] ?? 'Onbekende fout bij genereren perspectief';
        
        if (strpos($errorMessage, 'timeout') !== false) {
            throw new Exception('De AI-service reageert niet snel genoeg. Het artikel is mogelijk te complex. Probeer het opnieuw.');
        } elseif (strpos($errorMessage, 'rate limit') !== false) {
            throw new Exception('Te veel verzoeken naar de AI-service. Wacht een moment en probeer het opnieuw.');
        } elseif (strpos($errorMessage, 'content_filter') !== false) {
            throw new Exception('De inhoud van het artikel kan niet verwerkt worden door de AI-service.');
        } else {
            throw new Exception('Kon leider reactie niet genereren: ' . $errorMessage);
        }
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