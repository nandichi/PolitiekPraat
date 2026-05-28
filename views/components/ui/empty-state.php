<?php
/**
 * Editorial empty state.
 *
 * @var string $title
 * @var string $message
 * @var string|null $icon
 * @var string|null $cta_label
 * @var string|null $cta_href
 * @var string|null $secondary_label
 * @var string|null $secondary_href
 */
$title = $title ?? 'Nog geen data';
$message = $message ?? '';
$icon = $icon ?? 'inbox';
?>
<div class="keyline-card p-10 md:p-14 text-center max-w-2xl mx-auto">
    <div class="flex justify-center mb-5 text-[color:var(--color-ink-faint)]">
        <?= pp_icon($icon, 36) ?>
    </div>
    <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-3"><?= pp_e($title) ?></h2>
    <?php if ($message !== ''): ?>
        <p class="text-[color:var(--color-ink-muted)] leading-relaxed mb-8"><?= pp_e($message) ?></p>
    <?php endif; ?>
    <?php if (!empty($cta_label) && !empty($cta_href)): ?>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= pp_e(pp_url($cta_href)) ?>" class="btn btn--primary"><?= pp_e($cta_label) ?></a>
            <?php if (!empty($secondary_label) && !empty($secondary_href)): ?>
                <a href="<?= pp_e(pp_url($secondary_href)) ?>" class="btn btn--ghost"><?= pp_e($secondary_label) ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
