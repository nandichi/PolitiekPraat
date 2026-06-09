<?php
require_once 'includes/Database.php';
require_once 'includes/config.php';
require_once 'includes/party_color_helpers.php';
require_once 'models/PartyModel.php';

/**
 * Politiek-Kompas: twee-assen positionering van de Nederlandse partijen.
 *
 *   X-as (-100 .. 100): economisch links (herverdeling, sterke staat)
 *                       <-> economisch rechts (lage lasten, vrije markt)
 *   Y-as (-100 .. 100): cultureel progressief (open, secelier-liberaal)
 *                       <-> cultureel conservatief (nationaal, traditioneel)
 *
 * De coordinaten en toelichting komen uit includes/data/partijen_profiel.php
 * (een redactionele schatting). Zetels, leider en kleur worden verrijkt vanuit
 * de databron, zodat het kompas automatisch de huidige Kamer volgt.
 */
$profiles = require __DIR__ . '/../includes/data/partijen_profiel.php';

$partyModel = new PartyModel();
$dbParties = $partyModel->getAllParties();

$compassPositions = [];
foreach ($profiles as $key => $profile) {
    if (empty($profile['compass'])) {
        continue;
    }
    $db = $dbParties[$key] ?? [];
    $x = (int) $profile['compass']['x'];
    $y = (int) $profile['compass']['y'];

    // Kwadrant op basis van de tekens. y>0 = conservatief (boven), y<0 = progressief (onder).
    if ($x < 0 && $y >= 0) {
        $quadrant = 'links-conservatief';
    } elseif ($x >= 0 && $y >= 0) {
        $quadrant = 'rechts-conservatief';
    } elseif ($x < 0 && $y < 0) {
        $quadrant = 'links-progressief';
    } else {
        $quadrant = 'rechts-progressief';
    }

    $compassPositions[$key] = [
        'name'          => $key,
        'full_name'     => $db['name'] ?? $key,
        'x'             => $x,
        'y'             => $y,
        'color'         => getPartyColor($key),
        'logo'          => $db['logo'] ?? null,
        'leader'        => $db['leader'] ?? ($profile['leider'] ?? null),
        'current_seats' => (int) ($db['current_seats'] ?? 0),
        'spectrum'      => $profile['stroming'] ?? '',
        'tekst'         => $profile['compass']['tekst'] ?? '',
        'quadrant'      => $quadrant,
    ];
}

// Sorteer op zetels (grootste eerst) voor de standaardweergave.
uasort($compassPositions, function ($a, $b) {
    return $b['current_seats'] - $a['current_seats'];
});

// Groepeer per kwadrant voor de toelichtende sectie.
$quadrantMeta = [
    'links-progressief'   => ['titel' => 'Links-progressief', 'omschrijving' => 'Meer herverdeling en een sterke overheid, gecombineerd met een open, progressieve samenleving.'],
    'rechts-progressief'  => ['titel' => 'Rechts-progressief', 'omschrijving' => 'Marktgericht en liberaal op economie, maar progressief en internationaal op cultuur.'],
    'links-conservatief'  => ['titel' => 'Links-conservatief', 'omschrijving' => 'Sociaal-economisch links, maar behoudender op migratie, identiteit of ethiek.'],
    'rechts-conservatief' => ['titel' => 'Rechts-conservatief', 'omschrijving' => 'Rechts op economie en uitgesproken conservatief of nationaal op cultuur.'],
];
$quadrantGroups = ['links-progressief' => [], 'rechts-progressief' => [], 'links-conservatief' => [], 'rechts-conservatief' => []];
foreach ($compassPositions as $key => $pos) {
    $quadrantGroups[$pos['quadrant']][$key] = $pos;
}

$pageTitle = 'Politiek Kompas - PolitiekPraat';
$pageDescription = 'Het Politiek Kompas: alle Nederlandse partijen geplot op twee assen, economisch links-rechts en cultureel progressief-conservatief. Met uitleg, kwadranten en standpunten.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

require_once 'views/politiek-kompas/index.php';
