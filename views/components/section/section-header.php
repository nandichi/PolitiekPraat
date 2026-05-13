<?php
/**
 * Section header (label + title + optional CTA-link).
 *
 * Props:
 *   label    string|null  Uppercase label boven titel (terracotta accent)
 *   title    string       Sectie titel (Fraunces)
 *   cta_label string|null
 *   cta_href  string|null
 */
$label = $label ?? ($props['label'] ?? null);
$title = $title ?? ($props['title'] ?? '');
$cta_label = $cta_label ?? ($props['cta_label'] ?? null);
$cta_href = $cta_href ?? ($props['cta_href'] ?? null);
?>
<header class="section-header">
    <div>
        <?php if ($label): ?>
            <span class="section-header__label"><?= pp_e($label) ?></span>
        <?php endif; ?>
        <h2 class="section-header__title"><?= pp_e($title) ?></h2>
    </div>
    <?php if ($cta_label && $cta_href): ?>
        <a href="<?= pp_e(pp_url($cta_href)) ?>" class="section-header__cta">
            <?= pp_e($cta_label) ?>
            <?= pp_icon('arrow-right', 14) ?>
        </a>
    <?php endif; ?>
</header>
