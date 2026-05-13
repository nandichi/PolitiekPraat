<?php
/**
 * Theme chip (klikbare pill voor thema-overzicht).
 *
 * Props:
 *   href   string
 *   label  string
 *   tone   string  (zie pp_category_tone tones)
 *   count  int|null  Aantal artikelen (optioneel)
 */
$href = $href ?? ($props['href'] ?? '#');
$label = $label ?? ($props['label'] ?? '');
$tone = $tone ?? ($props['tone'] ?? 'neutral');
$count = $count ?? ($props['count'] ?? null);
?>
<a class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium tag tag--<?= pp_e($tone) ?>"
   href="<?= pp_e(pp_url($href)) ?>"
   style="text-transform: none; letter-spacing: 0; padding: 0.5rem 1rem;">
    <span><?= pp_e($label) ?></span>
    <?php if ($count !== null): ?>
        <span class="opacity-60"><?= (int) $count ?></span>
    <?php endif; ?>
</a>
