<?php
/**
 * Sub-navigatie voor de Midterms 2026 sectie.
 * Prop: $active (string) = sleutel van de actieve sectie.
 */
$active = $active ?? ($props['active'] ?? '');

$links = [
    'hub'              => ['label' => 'Overzicht',    'href' => '/midterms-2026',                   'icon' => 'layout-dashboard'],
    'senaat'           => ['label' => 'Senaat',       'href' => '/midterms-2026/senaat',            'icon' => 'landmark'],
    'huis'             => ['label' => 'Huis',         'href' => '/midterms-2026/huis',              'icon' => 'users'],
    'gouverneurs'      => ['label' => 'Gouverneurs',  'href' => '/midterms-2026/gouverneurs',       'icon' => 'map-pin'],
    'races'            => ['label' => 'Sleutelraces', 'href' => '/midterms-2026/races',             'icon' => 'target'],
    'referenda'        => ['label' => 'Referenda',    'href' => '/midterms-2026/referenda',         'icon' => 'check-square'],
    'voorverkiezingen' => ['label' => 'Tijdlijn',     'href' => '/midterms-2026/voorverkiezingen', 'icon' => 'calendar'],
    'nieuws'           => ['label' => 'Nieuws',       'href' => '/midterms-2026/nieuws',            'icon' => 'newspaper'],
    'uitleg'           => ['label' => 'Uitleg',       'href' => '/midterms-2026/uitleg',            'icon' => 'help-circle'],
];
?>
<nav class="midterms-subnav" aria-label="Midterms secties" data-mt-subnav>
    <div class="pp-container pp-container--xl">
        <ul class="midterms-subnav__list">
            <?php foreach ($links as $key => $link): ?>
                <li>
                    <a href="<?= pp_e(pp_url($link['href'])) ?>"
                       class="midterms-subnav__link<?= $key === $active ? ' is-active' : '' ?>"
                       <?= $key === $active ? 'aria-current="page"' : '' ?>>
                        <span aria-hidden="true"><?= pp_icon($link['icon'], 15) ?></span>
                        <?= pp_e($link['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
