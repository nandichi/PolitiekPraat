<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

// Maak database connectie
$db = new Database();
$conn = $db->getConnection();

// Functie om een slug te genereren
function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Array met verschillende politieke onderwerpen
$topics = [
    "Klimaatbeleid in Nederland",
    "Sociale Zekerheid",
    "Immigratie en Integratie",
    "Woningmarkt",
    "Gezondheidszorg",
    "Onderwijs",
    "Economie",
    "Defensie",
    "Europese Samenwerking",
    "Digitale Veiligheid"
];

// Voeg 20 blogs toe
for ($i = 1; $i <= 20; $i++) {
    $topic = $topics[array_rand($topics)];
    $title = $topic . " - Deel " . $i;
    $slug = generateSlug($title);
    
    $summary = "Een diepgaande analyse over " . strtolower($topic) . " en de impact op de Nederlandse samenleving.";
    
    $content = "In deze blog bespreken we de belangrijke ontwikkelingen rondom " . strtolower($topic) . ".\n\n";
    $content .= "De Nederlandse politiek staat voor grote uitdagingen als het gaat om " . strtolower($topic) . ".\n\n";
    $content .= "Er zijn verschillende standpunten over hoe we dit vraagstuk moeten aanpakken.\n\n";
    $content .= "Sommige partijen pleiten voor meer overheidsingrijpen, terwijl anderen juist meer ruimte willen geven aan de markt.\n\n";
    $content .= "Het is belangrijk dat we als samenleving een goede discussie voeren over deze onderwerpen.";

    // Voeg de blog toe aan de database
    $stmt = $conn->prepare("INSERT INTO blogs (title, slug, summary, content, author_id, views) VALUES (?, ?, ?, ?, 1, 0)");
    $stmt->execute([$title, $slug, $summary, $content]);
}

echo "Er zijn succesvol 20 blogs gegenereerd!\n"; 