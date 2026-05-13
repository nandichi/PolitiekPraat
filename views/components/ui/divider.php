<?php
/**
 * Editorial section rule (dunne lijn met optioneel uppercase label).
 *
 * Props:
 *   label  string|null  Optionele label (verschijnt links)
 *   class  string
 */
$label = $label ?? ($props['label'] ?? null);
$extraClass = $class ?? ($props['class'] ?? '');
?>
<div class="<?= pp_e(pp_class('section-rule', $extraClass)) ?>">
    <?php if ($label !== null): ?>
        <span class="section-rule__label"><?= pp_e($label) ?></span>
    <?php endif; ?>
</div>
