<?php
/**
 * Kleurlegenda voor de race-ratings (zeven categorieën).
 */
$ratingMeta = $ratingMeta ?? ($props['ratingMeta'] ?? MidtermsModel::ratingMeta());
?>
<ul class="midterms-legend" aria-label="Legenda kleuren">
    <?php foreach ($ratingMeta as $key => $meta): ?>
        <li class="midterms-legend__item">
            <span class="midterms-legend__swatch mt-rating--<?= pp_e($key) ?>"></span>
            <span class="midterms-legend__label"><?= pp_e($meta['short']) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
