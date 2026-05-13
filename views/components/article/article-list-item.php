<?php
/**
 * Genummerd lijst-item (Semafor-stijl) voor "Vandaag in 5" of "Top stories".
 *
 * Props:
 *   index   int|string
 *   href    string
 *   title   string
 *   lead    string|null
 *   source  string|null  Bron (bv. "NOS Politiek")
 *   time    string|null  Relatieve tijd ("3 uur geleden")
 */
$index = $index ?? ($props['index'] ?? 1);
$href = $href ?? ($props['href'] ?? '#');
$title = $title ?? ($props['title'] ?? '');
$lead = $lead ?? ($props['lead'] ?? null);
$source = $source ?? ($props['source'] ?? null);
$time = $time ?? ($props['time'] ?? null);
?>
<a class="num-item" href="<?= pp_e(pp_url($href)) ?>">
    <span class="num-item__index"><?= pp_e(str_pad((string) $index, 2, '0', STR_PAD_LEFT)) ?></span>
    <div class="num-item__body">
        <h3 class="num-item__title"><?= pp_e($title) ?></h3>
        <?php if ($lead): ?>
            <p class="num-item__lead"><?= pp_e($lead) ?></p>
        <?php endif; ?>
        <?php if ($source || $time): ?>
            <div class="meta-row text-xs">
                <?php if ($source): ?>
                    <span><?= pp_e($source) ?></span>
                <?php endif; ?>
                <?php if ($source && $time): ?><span class="meta-row__dot"></span><?php endif; ?>
                <?php if ($time): ?>
                    <span><?= pp_e($time) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</a>
