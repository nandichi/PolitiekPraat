<?php
/**
 * Editorial button component.
 *
 * Props:
 *   label   string                 Tekst-label
 *   href    string|null            Optioneel - rendert als <a> i.p.v. <button>
 *   variant string                 primary|secondary|ghost|terracotta|link (default: secondary)
 *   size    string                 sm|md|lg|block (default: md)
 *   icon    string|null            Lucide icon name (optioneel)
 *   icon_end string|null           Trailing icon (optioneel)
 *   type    string                 button|submit|reset (default: button)
 *   attrs   array<string,mixed>    Extra attributes
 *   class   string                 Extra classes
 *   disabled bool
 */

$label = $label ?? ($props['label'] ?? '');
$href = $href ?? ($props['href'] ?? null);
$variant = $variant ?? ($props['variant'] ?? 'secondary');
$size = $size ?? ($props['size'] ?? 'md');
$icon = $icon ?? ($props['icon'] ?? null);
$icon_end = $icon_end ?? ($props['icon_end'] ?? null);
$type = $type ?? ($props['type'] ?? 'button');
$attrs = $attrs ?? ($props['attrs'] ?? []);
$disabled = $disabled ?? ($props['disabled'] ?? false);
$extraClass = $class ?? ($props['class'] ?? '');

$classes = pp_class('btn', 'btn--' . $variant, [
    'btn--sm' => $size === 'sm',
    'btn--lg' => $size === 'lg',
    'btn--block' => $size === 'block',
], $extraClass);

$tag = $href !== null ? 'a' : 'button';
$baseAttrs = $tag === 'a'
    ? ['href' => pp_url($href), 'class' => $classes]
    : ['type' => $type, 'class' => $classes];
if ($disabled) {
    if ($tag === 'button') {
        $baseAttrs['disabled'] = true;
    }
    $baseAttrs['aria-disabled'] = 'true';
}
$baseAttrs = array_merge($baseAttrs, $attrs);
?>
<<?= $tag ?> <?= pp_attr($baseAttrs) ?>>
    <?php if ($icon): ?><?= pp_icon($icon, ['size' => $size === 'sm' ? 16 : 18]) ?><?php endif; ?>
    <span><?= pp_e($label) ?></span>
    <?php if ($icon_end): ?><?= pp_icon($icon_end, ['size' => $size === 'sm' ? 16 : 18]) ?><?php endif; ?>
</<?= $tag ?>>
