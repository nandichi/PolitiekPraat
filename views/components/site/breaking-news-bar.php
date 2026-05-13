<?php
/**
 * Slim breaking news / aankondiging bar bovenaan site.
 * Vervangt de oude "Elke zondag een nieuwe blog" gradient banner.
 *
 * Props:
 *   message  string  De aankondiging
 *   label    string  Korte label (default: "ACTUEEL")
 *   href     string|null  Optionele link voor het hele bar
 */
$message = $message ?? ($props['message'] ?? 'Elke zondag een nieuwe blog. Doordeweeks extra bij actueel nieuws.');
$label   = $label   ?? ($props['label']   ?? 'Actueel');
$href    = $href    ?? ($props['href']    ?? null);
?>
<div class="breaking-bar" role="region" aria-label="Aankondigingen">
    <div class="pp-container breaking-bar__inner">
        <span class="breaking-bar__label"><?= pp_e($label) ?></span>
        <?php if ($href !== null): ?>
            <a class="breaking-bar__content" style="text-decoration: none; color: inherit;" href="<?= pp_e(pp_url($href)) ?>">
                <?= pp_e($message) ?>
            </a>
        <?php else: ?>
            <span class="breaking-bar__content"><?= pp_e($message) ?></span>
        <?php endif; ?>
    </div>
</div>
