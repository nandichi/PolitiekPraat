<?php
/**
 * Badge - kleine pill voor leestijd / counts / labels.
 *
 * Props:
 *   label  string
 *   icon   string|null
 *   tone   string  default|hague|terracotta|ochre|moss|olive (kleur)
 *   class  string
 */
$label = $label ?? ($props['label'] ?? '');
$icon = $icon ?? ($props['icon'] ?? null);
$tone = $tone ?? ($props['tone'] ?? 'default');
$extraClass = $class ?? ($props['class'] ?? '');

$toneStyles = [
    'default'    => 'background-color: var(--color-canvas-sunken); color: var(--color-ink-muted);',
    'hague'      => 'background-color: var(--color-hague-tint); color: var(--color-hague);',
    'terracotta' => 'background-color: var(--color-terracotta-tint); color: var(--color-terracotta);',
    'ochre'      => 'background-color: var(--color-ochre-tint); color: var(--color-ochre);',
    'olive'      => 'background-color: var(--color-olive-tint); color: var(--color-olive);',
    'moss'       => 'background-color: var(--color-moss-tint); color: var(--color-moss);',
    'rose'       => 'background-color: var(--color-rose-tint); color: var(--color-rose);',
];
$style = $toneStyles[$tone] ?? $toneStyles['default'];
?>
<span class="<?= pp_e(pp_class('inline-flex items-center gap-1 px-2 py-0.5 rounded text-[0.6875rem] font-medium', $extraClass)) ?>"
      style="<?= pp_e($style) ?>">
    <?php if ($icon): ?><?= pp_icon($icon, 12) ?><?php endif; ?>
    <?= pp_e($label) ?>
</span>
