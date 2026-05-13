<?php
require_once 'includes/Database.php';
require_once 'includes/config.php';
require_once 'includes/party_color_helpers.php';
require_once 'models/PartyModel.php';

$partyModel = new PartyModel();
$dbParties = $partyModel->getAllParties();

/**
 * Politiek-Kompas: twee-assen positionering van Nederlandse partijen.
 *
 *   X-as (-100 .. 100): economisch links (subsidies, herverdeling, sterke staat)
 *                       ↔ economisch rechts (lage lasten, vrije markt)
 *   Y-as (-100 .. 100): cultureel progressief (open grenzen, sec. liberaal)
 *                       ↔ cultureel conservatief (nationaal, traditioneel)
 *
 * Posities zijn een redactionele schatting op basis van publieke programma's
 * en stemgedrag in de Tweede Kamer. Geen exacte wetenschap; bedoeld als kompas.
 */
$compassPositions = [
    'PVV'     => ['x' =>  30, 'y' =>  80, 'name' => 'PVV'],
    'VVD'     => ['x' =>  70, 'y' =>  30, 'name' => 'VVD'],
    'NSC'     => ['x' =>  35, 'y' =>  40, 'name' => 'NSC'],
    'BBB'     => ['x' =>  45, 'y' =>  55, 'name' => 'BBB'],
    'CDA'     => ['x' =>  25, 'y' =>  30, 'name' => 'CDA'],
    'D66'     => ['x' =>  35, 'y' => -55, 'name' => 'D66'],
    'GL-PvdA' => ['x' => -60, 'y' => -50, 'name' => 'GL-PvdA'],
    'SP'      => ['x' => -75, 'y' => -10, 'name' => 'SP'],
    'PvdD'    => ['x' => -45, 'y' => -60, 'name' => 'PvdD'],
    'JA21'    => ['x' =>  55, 'y' =>  55, 'name' => 'JA21'],
    'FvD'     => ['x' =>  20, 'y' =>  85, 'name' => 'FvD'],
    'SGP'     => ['x' =>  20, 'y' =>  70, 'name' => 'SGP'],
    'CU'      => ['x' => -10, 'y' =>  35, 'name' => 'CU'],
    'DENK'    => ['x' => -30, 'y' => -10, 'name' => 'DENK'],
    'Volt'    => ['x' =>  10, 'y' => -65, 'name' => 'Volt'],
];

// Verrijken met logo/kleur uit DB
foreach ($compassPositions as $key => &$pos) {
    if (isset($dbParties[$key])) {
        $pos['logo'] = $dbParties[$key]['logo'] ?? null;
        $pos['color'] = $dbParties[$key]['color'] ?? getPartyColor($key);
        $pos['leader'] = $dbParties[$key]['leader'] ?? null;
        $pos['current_seats'] = $dbParties[$key]['current_seats'] ?? 0;
        $pos['full_name'] = $dbParties[$key]['name'] ?? $key;
    } else {
        $pos['color'] = getPartyColor($key);
        $pos['full_name'] = $key;
    }
}
unset($pos);

$pageTitle = 'Politiek Kompas - PolitiekPraat';
$pageDescription = 'Het Politiek Kompas: positioneert alle Nederlandse partijen op twee assen, economisch links-rechts en cultureel progressief-conservatief.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

require_once 'views/politiek-kompas/index.php';
