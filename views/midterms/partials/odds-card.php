<?php
/**
 * Odds-kaart: toont een Polymarket-markt als horizontale balk.
 * Props:
 *   $odd  object|array met: title_nl, outcomes [{label_nl, price}], source_url, is_fallback
 *   $compact bool (optioneel)
 */
$odd = $odd ?? ($props['odd'] ?? null);
$compact = $compact ?? ($props['compact'] ?? false);
if ($odd === null) {
    return;
}
$o = is_array($odd) ? (object) $odd : $odd;
$title = $o->title_nl ?? '';
$outcomes = $o->outcomes ?? [];
$sourceUrl = $o->source_url ?? null;
$isFallback = !empty($o->is_fallback);

// Normaliseer outcomes en sorteer aflopend op kans
$rows = [];
foreach ($outcomes as $oc) {
    $oc = (array) $oc;
    $label = $oc['label_nl'] ?? ($oc['label'] ?? '');
    if ($label === '') {
        continue;
    }
    $rows[] = [
        'label' => $label,
        'pct' => mt_pct_num($oc['price'] ?? 0),
        'party' => mt_party_from_label($label),
    ];
}
usort($rows, static fn ($a, $b) => $b['pct'] <=> $a['pct']);
?>
<div class="midterms-odds<?= $compact ? ' midterms-odds--compact' : '' ?>">
    <?php if ($title !== ''): ?>
        <h3 class="midterms-odds__title"><?= pp_e($title) ?></h3>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
        <p class="midterms-odds__empty">Nog geen actuele cijfers beschikbaar.</p>
    <?php else: ?>
        <div class="midterms-odds__bar" role="img"
             aria-label="<?= pp_e($title . ': ' . implode(', ', array_map(fn($r) => $r['label'] . ' ' . round($r['pct']) . '%', $rows))) ?>">
            <?php foreach ($rows as $r): ?>
                <span class="midterms-odds__seg mt-party--<?= pp_e(mt_party_class($r['party'])) ?>"
                      style="width: <?= number_format(max(0, min(100, $r['pct'])), 2, '.', '') ?>%"></span>
            <?php endforeach; ?>
        </div>
        <ul class="midterms-odds__legend">
            <?php foreach ($rows as $r): ?>
                <li>
                    <span class="midterms-odds__dot mt-party--<?= pp_e(mt_party_class($r['party'])) ?>"></span>
                    <span class="midterms-odds__label"><?= pp_e($r['label']) ?></span>
                    <span class="midterms-odds__pct font-mono"><?= pp_e(mt_pct($r['pct'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($sourceUrl): ?>
        <a href="<?= pp_e($sourceUrl) ?>" class="midterms-odds__source" target="_blank" rel="noopener nofollow">
            Bron: Polymarket <?= pp_icon('external-link', 12) ?>
        </a>
    <?php endif; ?>
    <?php if ($isFallback): ?>
        <span class="midterms-odds__stale">momentopname</span>
    <?php endif; ?>
</div>
