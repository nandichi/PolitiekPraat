<?php
/**
 * Base keyline-card (geen schaduw, dunne lijn).
 *
 * Props:
 *   children string  HTML inhoud
 *   href     string|null Optioneel klikbare card
 *   class    string
 *   padding  string  none|sm|md|lg (default: md)
 */
$children = $children ?? ($props['children'] ?? '');
$href = $href ?? ($props['href'] ?? null);
$extraClass = $class ?? ($props['class'] ?? '');
$padding = $padding ?? ($props['padding'] ?? 'md');

$padMap = ['none' => '', 'sm' => 'p-4', 'md' => 'p-6', 'lg' => 'p-8'];
$classes = pp_class('keyline-card block', $padMap[$padding] ?? 'p-6', $extraClass);

$tag = $href !== null ? 'a' : 'div';
$attrs = ['class' => $classes];
if ($tag === 'a') {
    $attrs['href'] = pp_url($href);
}
?>
<<?= $tag ?> <?= pp_attr($attrs) ?>>
    <?= $children ?>
</<?= $tag ?>>
