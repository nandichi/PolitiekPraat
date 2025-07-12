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
 * Verkort blog content voor veilige API calls
 */
function truncateContentForAPI($content, $maxLength = 3000) {
    if (empty($content)) {
        return $content;
    }
    
    // Strip Markdown syntax eerst
    $content = stripMarkdownSyntax($content);
    $originalLength = mb_strlen($content);
    
    // Als content al kort genoeg is, return direct
    if ($originalLength <= $maxLength) {
        return $content;
    }
    
    // Voor extreem lange artikelen (>15000 chars), gebruik een agressievere strategie
    if ($originalLength > 15000) {
        return createSummaryFromLongContent($content, $maxLength);
    }
    
    // Voor zeer lange content (>6000 chars), gebruik een samenvatting strategie
    if ($originalLength > 6000) {
        return createSummaryFromContent($content, $maxLength);
    }
    
    // Voor matig lange content, gebruik bestaande methode
    return truncateNormalContent($content, $maxLength);
}

/**
 * Creeër een samenvatting voor extreem lange artikelen
 */
function createSummaryFromLongContent($content, $maxLength) {
    // Haal de eerste 3 alinea's (opening + context)
    $paragraphs = explode("\n\n", $content);
    $summary = '';
    $usedParagraphs = 0;
    
    // Voeg eerste alinea's toe tot we 40% van maxLength hebben
    $targetLength = $maxLength * 0.4;
    foreach ($paragraphs as $paragraph) {
        if (mb_strlen($summary . $paragraph) > $targetLength || $usedParagraphs >= 3) {
            break;
        }
        $summary .= trim($paragraph) . "\n\n";
        $usedParagraphs++;
    }
    
    // Voeg een selectie van zinnen toe uit de rest van het artikel
    $remainingContent = implode("\n\n", array_slice($paragraphs, $usedParagraphs));
    $sentences = explode('.', $remainingContent);
    
    // Selecteer elke 5e zin om een goede vertegenwoordiging te krijgen
    $selectedSentences = [];
    for ($i = 0; $i < count($sentences); $i += 5) {
        if (!empty(trim($sentences[$i]))) {
            $selectedSentences[] = trim($sentences[$i]) . '.';
            if (mb_strlen(implode(' ', $selectedSentences)) > ($maxLength - mb_strlen($summary) - 100)) {
                break;
            }
        }
    }
    
    $summary .= implode(' ', $selectedSentences);
    
    // Verkort tot maxLength indien nodig
    if (mb_strlen($summary) > $maxLength) {
        $summary = mb_substr($summary, 0, $maxLength - 3) . '...';
    }
    
    return trim($summary);
}

/**
 * Creeër een samenvatting voor lange artikelen
 */
function createSummaryFromContent($content, $maxLength) {
    // Haal de eerste 2 alinea's
    $paragraphs = explode("\n\n", $content);
    $summary = '';
    
    // Voeg eerste 2 alinea's toe
    for ($i = 0; $i < min(2, count($paragraphs)); $i++) {
        if (mb_strlen($summary . $paragraphs[$i]) < $maxLength * 0.6) {
            $summary .= trim($paragraphs[$i]) . "\n\n";
        }
    }
    
    // Voeg de laatste alinea toe (conclusie)
    if (count($paragraphs) > 2) {
        $lastParagraph = end($paragraphs);
        if (mb_strlen($summary . $lastParagraph) < $maxLength) {
            $summary .= "...\n\n" . trim($lastParagraph);
        }
    }
    
    // Verkort tot maxLength indien nodig
    if (mb_strlen($summary) > $maxLength) {
        $summary = mb_substr($summary, 0, $maxLength - 3) . '...';
    }
    
    return trim($summary);
}

/**
 * Verkort normale content
 */
function truncateNormalContent($content, $maxLength) {
    // Verkort tot maxLength, maar eindig op een volledige zin
    $truncated = mb_substr($content, 0, $maxLength);
    
    // Zoek de laatste punt, uitroepteken of vraagteken
    $lastSentenceEnd = max(
        mb_strrpos($truncated, '.'),
        mb_strrpos($truncated, '!'),
        mb_strrpos($truncated, '?')
    );
    
    if ($lastSentenceEnd !== false && $lastSentenceEnd > $maxLength * 0.6) {
        // Als we een goede zin-einde vinden binnen 60% van de lengte, gebruik die
        $truncated = mb_substr($truncated, 0, $lastSentenceEnd + 1);
    } else {
        // Anders, zoek de laatste spatie om midden in een woord te voorkomen
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLength * 0.7) {
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
    
    // Bepaal de maximale lengte op basis van de originele artikel lengte
    $originalLength = mb_strlen(stripMarkdownSyntax($blog->content));
    $maxLength = 1200; // Default voor lange artikelen
    
    if ($originalLength > 20000) {
        $maxLength = 800;  // Extreem lange artikelen (zoals China artikel)
    } elseif ($originalLength > 10000) {
        $maxLength = 1000; // Zeer lange artikelen
    } elseif ($originalLength > 5000) {
        $maxLength = 1200; // Lange artikelen
    } else {
        $maxLength = 1500; // Normale artikelen
    }
    
    // Verkort blog content voor veilige API call
    $truncatedContent = truncateContentForAPI($blog->content, $maxLength);
    
    // Extra fallback voor extreem lange artikelen - forceer een laatste verkorten indien nodig
    if ($originalLength > 20000 && mb_strlen($truncatedContent) > 600) {
        // Voor China-achtige artikelen: alleen de eerste 2 zinnen + titel
        $sentences = explode('.', $truncatedContent);
        $shortSummary = $blog->title . ". ";
        $sentenceCount = 0;
        foreach ($sentences as $sentence) {
            if ($sentenceCount >= 2) break;
            if (!empty(trim($sentence))) {
                $shortSummary .= trim($sentence) . ". ";
                $sentenceCount++;
            }
        }
        $truncatedContent = trim($shortSummary);
    }
    
    // Log voor debugging (kan later weggehaald worden)
    error_log("Article length: $originalLength chars, Using maxLength: $maxLength, Final truncated to: " . mb_strlen($truncatedContent) . " chars");
    
    // Controleer of de blog content nog steeds te lang is
    if (mb_strlen($truncatedContent) > $maxLength + 100) {
        throw new Exception('Blog artikel is te complex voor verwerking. De AI kan dit artikel niet verwerken vanwege de lengte en complexiteit.');
    }
    
    // Initialiseer ChatGPT API
    try {
        $chatGPT = new ChatGPTAPI();
    } catch (Exception $e) {
        throw new Exception('AI-service is momenteel niet beschikbaar. Probeer het later opnieuw.');
    }
    
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