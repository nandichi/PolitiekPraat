<?php
/**
 * Categorie-tag (pastel tints).
 *
 * Props:
 *   label   string  Tekst (wordt ge-uppercased via CSS)
 *   tone    string  ochre|olive|rose|moss|hague|terracotta|neutral (default: neutral)
 *   href    string|null  Optioneel klikbaar
 *   icon    string|null  Optioneel Lucide-icoon vooraan
 *   class   string
 */
$label = $label ?? ($props['label'] ?? '');
$tone = $tone ?? ($props['tone'] ?? 'neutral');
$href = $href ?? ($props['href'] ?? null);
$icon = $icon ?? ($props['icon'] ?? null);
$extraClass = $class ?? ($props['class'] ?? '');

$classes = pp_class('tag', 'tag--' . $tone, $extraClass);
$tag = $href !== null ? 'a' : 'span';
$attrs = ['class' => $classes];
if ($tag === 'a') {
    $attrs['href'] = pp_url($href);
}
?>
<<?= $tag ?> <?= pp_attr($attrs) ?>>
    <?php if ($icon): ?><?= pp_icon($icon, 12) ?><?php endif; ?>
    <?= pp_e($label) ?>
</<?= $tag ?>>
