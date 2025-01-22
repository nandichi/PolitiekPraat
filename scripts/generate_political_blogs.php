<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/NewsAPI.php';

// Database connectie
$db = new Database();
$conn = $db->getConnection();

// Functie om een slug te genereren
function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Array met blog templates voor verschillende onderwerpen
$blogTemplates = [
    [
        'title' => 'Waarom Jongeren de Sleutel zijn tot Betere Politiek',
        'content' => "Als 19-jarige D66'er zie ik elke dag waarom we meer jonge mensen nodig hebben in de politiek. Veel mensen van mijn leeftijd denken dat politiek saai is of dat hun stem niet telt. Maar dat is echt niet zo!

Kijk naar wat er allemaal speelt:
- Geen betaalbare woningen voor starters
- Studieschulden die maar oplopen
- Klimaatverandering die onze toekomst bedreigt
- Banen die veranderen door AI en robots

D66 snapt dat jongeren belangrijk zijn. Daarom willen wij:
1. Meer betaalbare woningen voor starters
2. Een beter studiefinancieringssysteem
3. Slimme oplossingen voor het klimaat
4. Goed onderwijs dat je klaarstoomt voor de banen van morgen

Wat kunnen we nu al doen?
- Ga stemmen! Elke stem telt echt
- Praat met vrienden over politiek
- Word actief bij D66 in jouw stad
- Deel je ideeën op social media

De toekomst is van ons. Laten we er samen iets moois van maken!",
    ],
    [
        'title' => 'Klimaat en Innovatie: Zo Pakken We Het Aan',
        'content' => "Bij D66 geloven we dat we klimaatverandering alleen kunnen stoppen met slimme oplossingen en nieuwe technologie. Niet door alleen maar dingen te verbieden, maar door slim te innoveren.

Dit zijn de feiten:
- De aarde wordt te snel warm
- We hebben nog maar 10 jaar om het tij te keren
- Nederland kan vooroplopen met innovatie
- Er liggen kansen voor nieuwe banen

Wat wil D66?
1. Meer geld voor groene uitvinders en start-ups
2. Schone energie betaalbaar voor iedereen
3. Elektrisch rijden makkelijker maken
4. Huizen sneller verduurzamen

Hoe helpt dit jou?
- Lagere energierekening
- Gezondere lucht in je stad
- Nieuwe baankansen in groene tech
- Een toekomst die er goed uitziet

Dit is geen ver-van-je-bed-show. Het gaat om jouw toekomst, en die kunnen we nu nog beter maken.",
    ],
    [
        'title' => 'Onderwijs: Iedereen Verdient een Eerlijke Kans',
        'content' => "Goed onderwijs is het belangrijkste wat er is. Bij D66 vinden we dat iedereen de kans moet krijgen om het beste uit zichzelf te halen. Of je nu naar het MBO, HBO of de universiteit gaat.

Dit zien we nu:
- Te grote klassen
- Te weinig docenten
- Stress bij studenten
- Ongelijke kansen

D66 wil dit:
1. Kleinere klassen
2. Betere begeleiding
3. Minder stress door schulden
4. Makkelijker doorstromen naar hoger onderwijs

Wat betekent dit voor jou?
- Betere hulp bij je studie
- Meer kans op een leuke baan
- Minder zorgen over geld
- Meer kansen om door te leren

Iedereen verdient de kans om zijn dromen waar te maken. Daar maken wij ons hard voor.",
    ],
    [
        'title' => 'Digitale Toekomst: Kansen Pakken, Risico's Aanpakken',
        'content' => "De wereld wordt steeds digitaler. D66 ziet de kansen van technologie, maar we moeten wel zorgen dat het veilig en eerlijk blijft.

Waar hebben we mee te maken?
- Artificial Intelligence wordt steeds belangrijker
- Privacy staat onder druk
- Cybercrime neemt toe
- Niet iedereen kan mee met digitalisering

Dit zijn onze oplossingen:
1. Betere digitale vaardigheden op school
2. Privacy beter beschermen
3. Veilig internet voor iedereen
4. Hulp voor mensen die moeite hebben met computers

Wat heb jij hieraan?
- Betere bescherming van jouw data
- Nieuwe digitale skills voor je werk
- Veilig online kunnen zijn
- Kansen in de tech-sector

De digitale toekomst is er voor iedereen. Samen zorgen we dat niemand achterblijft.",
    ],
    [
        'title' => 'Europa: Samen Staan We Sterker',
        'content' => "D66 gelooft in een sterk Europa. Waarom? Omdat we samen meer kunnen bereiken dan alleen. Of het nu gaat om klimaat, veiligheid of economie.

Dit speelt er nu:
- Landen die samen moeten werken tegen klimaatverandering
- Bedreigingen voor onze veiligheid
- Economische uitdagingen
- Kansen voor studeren en werken in het buitenland

Wat wil D66?
1. Meer samenwerking tussen landen
2. Makkelijker studeren in het buitenland
3. Samen aanpakken van klimaatproblemen
4. Sterkere democratie in Europa

Wat betekent dit voor jou?
- Meer kansen om in het buitenland te studeren
- Betere bescherming van je rechten
- Schonere lucht in heel Europa
- Sterker staan tegen grote landen als China

Europa is geen ver-van-je-bed-show. Het gaat om jouw toekomst!",
    ]
];

// Voeg de blogs toe aan de database
foreach ($blogTemplates as $template) {
    $title = $template['title'];
    $slug = generateSlug($title);
    $content = $template['content'];
    $summary = substr(strip_tags($content), 0, 200) . '...';

    $stmt = $conn->prepare("INSERT INTO blogs (title, slug, summary, content, author_id, views) VALUES (?, ?, ?, ?, 1, 0)");
    $stmt->execute([$title, $slug, $summary, $content]);
}

echo "Er zijn succesvol 5 uitgebreide politieke blogs gegenereerd!\n";