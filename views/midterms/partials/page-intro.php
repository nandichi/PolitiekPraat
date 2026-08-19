<?php
/**
 * Eigen paginakop voor alle Midterms-subpagina's.
 * Verwacht: $eyebrow, $title, $lead en optioneel $meta (lijst strings).
 */
$eyebrow = $eyebrow ?? 'Midterms 2026';
$title = $title ?? 'Midterms 2026';
$lead = $lead ?? '';
$meta = $meta ?? [];
$midtermsContext = $midtermsContext ?? [];
$verifiedAt = $midtermsContext['verified_at'] ?? MidtermsModel::DATA_VERIFIED_AT;
?>
<section class="mt-page-intro" data-mt-reveal>
    <div class="mt-page-intro__glow" aria-hidden="true"></div>
    <div class="pp-container pp-container--xl mt-page-intro__inner">
        <div class="mt-page-intro__copy">
            <div class="mt-kicker">
                <span class="mt-live-dot" aria-hidden="true"></span>
                <?= pp_e($eyebrow) ?>
            </div>
            <h1 class="mt-page-intro__title"><?= pp_e($title) ?></h1>
            <?php if ($lead !== ''): ?>
                <p class="mt-page-intro__lead"><?= pp_e($lead) ?></p>
            <?php endif; ?>
            <div class="mt-page-intro__meta">
                <span><?= pp_icon('calendar', 14) ?> 3 november 2026</span>
                <span><?= pp_icon('check-circle', 14) ?> Data gecontroleerd <?= pp_e(mt_date_nl($verifiedAt)) ?></span>
                <?php foreach ($meta as $item): ?>
                    <span><?= pp_icon($item['icon'] ?? 'activity', 14) ?> <?= pp_e($item['text'] ?? '') ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mt-page-intro__monogram" aria-hidden="true">
            <span>US</span>
            <small>26</small>
        </div>
    </div>
</section>
