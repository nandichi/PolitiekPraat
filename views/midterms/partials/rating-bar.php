<?php
/** Horizontale verdeling van alle ratingcategorieën. */
$summary = $summary ?? [];
$barTitle = $barTitle ?? 'Ratingverdeling';
$barNote = $barNote ?? null;
$ratingMeta = $ratingMeta ?? MidtermsModel::ratingMeta();
$total = max(1, mt_sum($summary));
?>
<div class="mt-rating-panel" data-mt-reveal>
    <div class="mt-rating-panel__head">
        <div>
            <span class="mt-panel-label">Prognose</span>
            <h3><?= pp_e($barTitle) ?></h3>
        </div>
        <strong><?= (int) $total ?> races</strong>
    </div>
    <div class="mt-rating-bar" role="img" aria-label="<?= pp_e($barTitle) ?>">
        <?php foreach ($ratingMeta as $key => $meta): ?>
            <?php $count = (int) ($summary[$key] ?? 0); if ($count < 1) continue; ?>
            <span class="mt-rating-bar__segment mt-rating--<?= pp_e($key) ?>"
                  style="--segment: <?= number_format(($count / $total) * 100, 4, '.', '') ?>%"
                  title="<?= pp_e($meta['label']) ?>: <?= $count ?>">
                <span><?= $count ?></span>
            </span>
        <?php endforeach; ?>
    </div>
    <div class="mt-rating-panel__legend">
        <?php foreach ($ratingMeta as $key => $meta): ?>
            <?php $count = (int) ($summary[$key] ?? 0); if ($count < 1) continue; ?>
            <span><i class="mt-rating--<?= pp_e($key) ?>"></i><?= pp_e($meta['short']) ?><b><?= $count ?></b></span>
        <?php endforeach; ?>
    </div>
    <?php if ($barNote): ?><p class="mt-rating-panel__note"><?= pp_e($barNote) ?></p><?php endif; ?>
</div>
