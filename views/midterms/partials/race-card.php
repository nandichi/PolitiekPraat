<?php
/** Racekaart met transparante rating, status en geverifieerde kandidaten. */
$race = $race ?? ($props['race'] ?? null);
if ($race === null) return;
$r = is_array($race) ? (object) $race : $race;

$chamberLabel = [
    'senate' => 'Senaat',
    'house' => 'Huis',
    'governor' => 'Gouverneur',
][$r->chamber ?? ''] ?? '';

$location = $r->state_name ?? ($r->state_code ?? '');
if (($r->chamber ?? '') === 'house' && !empty($r->district)) {
    $location .= ' · district ' . ($r->district === 'AL' ? 'at-large' : ltrim((string) $r->district, '0'));
}

$rating = $r->rating ?? 'tossup';
$party = mt_rating_party($rating);
$candidateD = trim((string) ($r->candidate_d ?? ''));
$candidateR = trim((string) ($r->candidate_r ?? ''));
if (strtolower($candidateD) === 'nog onbekend') $candidateD = '';
if (strtolower($candidateR) === 'nog onbekend') $candidateR = '';
$incumbent = trim((string) ($r->incumbent_name ?? ''));
if (strtolower($incumbent) === 'nog onbekend') $incumbent = '';
?>
<article class="midterms-race mt-rating-border--<?= pp_e($rating) ?>"
         data-mt-race data-race-card data-chamber="<?= pp_e($r->chamber ?? '') ?>"
         data-rating="<?= pp_e($rating) ?>"
         data-party="<?= pp_e(strtolower((string) ($r->incumbent_party ?? ''))) ?>"
         data-open="<?= !empty($r->is_open) ? '1' : '0' ?>">
    <div class="midterms-race__topline">
        <span class="midterms-race__chamber"><?= pp_e($chamberLabel) ?></span>
        <span class="midterms-race__rating mt-rating--<?= pp_e($rating) ?>">
            <i></i><?= pp_e(mt_rating_label($rating, true)) ?>
        </span>
    </div>

    <div class="midterms-race__head">
        <div>
            <span class="midterms-race__statecode"><?= pp_e($r->state_code ?? '') ?></span>
            <h3 class="midterms-race__title"><?= pp_e($location) ?></h3>
        </div>
        <?php if (!empty($r->is_open)): ?><span class="midterms-race__open">Open zetel</span><?php endif; ?>
    </div>

    <?php if ($candidateD !== '' || $candidateR !== ''): ?>
        <div class="midterms-race__candidates" aria-label="Kandidaten">
            <?php if ($candidateD !== ''): ?>
                <div class="midterms-race__cand">
                    <span class="midterms-race__cand-party mt-party--dem">D</span>
                    <span class="midterms-race__cand-name"><?= pp_e($candidateD) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($candidateR !== ''): ?>
                <div class="midterms-race__cand">
                    <span class="midterms-race__cand-party mt-party--gop">R</span>
                    <span class="midterms-race__cand-name"><?= pp_e($candidateR) ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php elseif ($incumbent !== '' && empty($r->is_open)): ?>
        <p class="midterms-race__incumbent">
            <span class="midterms-race__cand-party mt-party--<?= pp_e(mt_party_class($r->incumbent_party ?? null)) ?>"><?= pp_e($r->incumbent_party ?? '') ?></span>
            Zittend: <strong><?= pp_e($incumbent) ?></strong>
        </p>
    <?php endif; ?>

    <?php if (!empty($r->summary_nl)): ?>
        <p class="midterms-race__summary"><?= pp_e($r->summary_nl) ?></p>
    <?php endif; ?>

    <footer class="midterms-race__footer">
        <span>Rating gecontroleerd 19 aug</span>
        <?php if (!empty($r->source_url)): ?>
            <a href="<?= pp_e($r->source_url) ?>" target="_blank" rel="noopener nofollow">
                Bekijk bron <?= pp_icon('external-link', 12) ?>
            </a>
        <?php endif; ?>
    </footer>
</article>
