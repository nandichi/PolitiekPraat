<?php
/**
 * Race-kaart: een enkele race (Senaat/Huis/Gouverneur).
 * Prop: $race (object)
 */
$race = $race ?? ($props['race'] ?? null);
if ($race === null) {
    return;
}
$r = is_array($race) ? (object) $race : $race;

$chamberLabel = [
    'senate' => 'Senaat',
    'house' => 'Huis',
    'governor' => 'Gouverneur',
][$r->chamber ?? ''] ?? '';

$location = $r->state_name ?? ($r->state_code ?? '');
if (($r->chamber ?? '') === 'house' && !empty($r->district)) {
    $location .= ' - district ' . ($r->district === 'AL' ? 'at-large' : ltrim((string) $r->district, '0'));
}

$rating = $r->rating ?? 'tossup';
$party = mt_rating_party($rating);
?>
<article class="midterms-race mt-rating-border--<?= pp_e($rating) ?>">
    <div class="midterms-race__head">
        <div>
            <span class="midterms-race__chamber"><?= pp_e($chamberLabel) ?></span>
            <h3 class="midterms-race__title"><?= pp_e($location) ?></h3>
        </div>
        <span class="midterms-race__rating mt-rating--<?= pp_e($rating) ?>">
            <?= pp_e(mt_rating_label($rating)) ?>
        </span>
    </div>

    <?php if (!empty($r->candidate_d) || !empty($r->candidate_r)): ?>
        <div class="midterms-race__candidates">
            <div class="midterms-race__cand mt-party--dem">
                <span class="midterms-race__cand-party">D</span>
                <span class="midterms-race__cand-name"><?= pp_e($r->candidate_d ?? 'nog onbekend') ?></span>
            </div>
            <span class="midterms-race__vs">vs</span>
            <div class="midterms-race__cand mt-party--gop">
                <span class="midterms-race__cand-party">R</span>
                <span class="midterms-race__cand-name"><?= pp_e($r->candidate_r ?? 'nog onbekend') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($r->incumbent_name) && empty($r->is_open)): ?>
        <p class="midterms-race__meta">
            Zittend: <?= pp_e($r->incumbent_name) ?> (<?= pp_e(mt_party_label($r->incumbent_party ?? null)) ?>)
        </p>
    <?php elseif (!empty($r->is_open)): ?>
        <p class="midterms-race__meta">Open zetel</p>
    <?php endif; ?>

    <?php if (!empty($r->summary_nl)): ?>
        <p class="midterms-race__summary"><?= pp_e($r->summary_nl) ?></p>
    <?php endif; ?>

    <?php if (!empty($r->source_url)): ?>
        <a href="<?= pp_e($r->source_url) ?>" class="midterms-race__source" target="_blank" rel="noopener nofollow">
            Bron <?= pp_icon('external-link', 12) ?>
        </a>
    <?php endif; ?>
</article>
