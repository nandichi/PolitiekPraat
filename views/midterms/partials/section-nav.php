<?php
/**
 * Sub-navigatie voor de Midterms 2026 sectie.
 * Prop: $active (string) = sleutel van de actieve sectie.
 */
$active = $active ?? ($props['active'] ?? '');

$links = [
    'hub'              => ['label' => 'Overzicht',        'href' => '/midterms-2026'],
    'senaat'           => ['label' => 'Senaat',           'href' => '/midterms-2026/senaat'],
    'huis'             => ['label' => 'Huis',             'href' => '/midterms-2026/huis'],
    'gouverneurs'      => ['label' => 'Gouverneurs',      'href' => '/midterms-2026/gouverneurs'],
    'races'            => ['label' => 'Sleutelraces',     'href' => '/midterms-2026/races'],
    'referenda'        => ['label' => 'Referenda',        'href' => '/midterms-2026/referenda'],
    'voorverkiezingen' => ['label' => 'Tijdlijn',         'href' => '/midterms-2026/voorverkiezingen'],
    'nieuws'           => ['label' => 'Nieuws',           'href' => '/midterms-2026/nieuws'],
    'uitleg'           => ['label' => 'Uitleg',           'href' => '/midterms-2026/uitleg'],
];
?>
<nav class="midterms-subnav" aria-label="Midterms secties">
    <div class="pp-container pp-container--xl">
        <ul class="midterms-subnav__list">
            <?php foreach ($links as $key => $link): ?>
                <li>
                    <a href="<?= pp_e(pp_url($link['href'])) ?>"
                       class="midterms-subnav__link<?= $key === $active ? ' is-active' : '' ?>"
                       <?= $key === $active ? 'aria-current="page"' : '' ?>>
                        <?= pp_e($link['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
