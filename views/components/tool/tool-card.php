<?php
/**
 * Tool / feature card (gebruikt voor PartijMeter, Stemwijzer, Kompas, Stemmentracker).
 *
 * Props:
 *   href    string
 *   title   string
 *   lead    string|null
 *   icon    string|null   Lucide icon name
 *   tone    string|null   ochre/olive/rose/moss/hague/terracotta (icon-kleur)
 *   cta     string|null   CTA-label (default: 'Start')
 */
$href = $href ?? ($props['href'] ?? '#');
$title = $title ?? ($props['title'] ?? '');
$lead = $lead ?? ($props['lead'] ?? null);
$icon = $icon ?? ($props['icon'] ?? 'arrow-right');
$tone = $tone ?? ($props['tone'] ?? 'hague');
$cta = $cta ?? ($props['cta'] ?? 'Start');

$tonesCss = [
    'hague'      => 'color: var(--color-hague); background-color: var(--color-hague-tint);',
    'terracotta' => 'color: var(--color-terracotta); background-color: var(--color-terracotta-tint);',
    'ochre'      => 'color: var(--color-ochre); background-color: var(--color-ochre-tint);',
    'olive'      => 'color: var(--color-olive); background-color: var(--color-olive-tint);',
    'rose'       => 'color: var(--color-rose); background-color: var(--color-rose-tint);',
    'moss'       => 'color: var(--color-moss); background-color: var(--color-moss-tint);',
];
$style = $tonesCss[$tone] ?? $tonesCss['hague'];
?>
<a class="tool-card" href="<?= pp_e(pp_url($href)) ?>">
    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full" style="<?= pp_e($style) ?>">
        <?= pp_icon($icon, 22) ?>
    </span>
    <h3 class="tool-card__title"><?= pp_e($title) ?></h3>
    <?php if ($lead): ?>
        <p class="tool-card__lead"><?= pp_e($lead) ?></p>
    <?php endif; ?>
    <span class="tool-card__cta">
        <?= pp_e($cta) ?>
        <?= pp_icon('arrow-right', 14) ?>
    </span>
</a>
