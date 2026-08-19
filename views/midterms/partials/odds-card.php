<?php
/**
 * Marktkaart voor een Polymarket-event. Percentages zijn marktprijzen, geen
 * peiling. De bron en cacheleeftijd staan altijd zichtbaar in de kaart.
 */
$odd = $odd ?? ($props['odd'] ?? null);
$compact = $compact ?? ($props['compact'] ?? false);
if ($odd === null) return;

$o = is_array($odd) ? (object) $odd : $odd;
$title = trim((string) ($o->title_nl ?? ''));
$outcomes = $o->outcomes ?? [];
$sourceUrl = $o->source_url ?? null;
$isFallback = !empty($o->is_fallback);
$fetchedAt = $o->fetched_at ?? null;
$volume = isset($o->volume) ? (float) $o->volume : null;

$rows = [];
foreach ($outcomes as $oc) {
    $oc = (array) $oc;
    $label = trim((string) ($oc['label_nl'] ?? ($oc['label'] ?? '')));
    if ($label === '') continue;
    $rows[] = [
        'label' => $label,
        'pct' => max(0, min(100, mt_pct_num($oc['price'] ?? 0))),
        'party' => mt_party_from_label($label),
    ];
}
usort($rows, static fn ($a, $b) => $b['pct'] <=> $a['pct']);
$leader = $rows[0] ?? null;
$leaderClass = $leader ? mt_party_class($leader['party']) : 'neutral';
?>
<article class="midterms-odds<?= $compact ? ' midterms-odds--compact' : '' ?>" data-mt-market>
    <div class="midterms-odds__topline">
        <span class="midterms-odds__type"><i class="mt-live-dot" aria-hidden="true"></i> Voorspellingsmarkt</span>
        <?php if ($fetchedAt): ?><span class="midterms-odds__time"><?= pp_e(mt_freshness_label($fetchedAt)) ?></span><?php endif; ?>
    </div>

    <div class="midterms-odds__headline">
        <div>
            <?php if ($title !== ''): ?><h3 class="midterms-odds__title"><?= pp_e($title) ?></h3><?php endif; ?>
            <p>Prijs op de markt, geen peiling</p>
        </div>
        <?php if ($leader): ?>
            <strong class="midterms-odds__leader mt-party-text--<?= pp_e($leaderClass) ?>" data-mt-count="<?= number_format($leader['pct'], 0, '.', '') ?>" data-mt-suffix="%">
                <?= pp_e(mt_pct($leader['pct'])) ?>
            </strong>
        <?php endif; ?>
    </div>

    <?php if (empty($rows)): ?>
        <p class="midterms-odds__empty">Nog geen actuele marktprijzen beschikbaar.</p>
    <?php else: ?>
        <div class="midterms-odds__bar" role="img"
             aria-label="<?= pp_e($title . ': ' . implode(', ', array_map(fn($r) => $r['label'] . ' ' . round($r['pct']) . '%', $rows))) ?>">
            <?php foreach ($rows as $r): ?>
                <span class="midterms-odds__seg mt-party--<?= pp_e(mt_party_class($r['party'])) ?>"
                      style="--market-width: <?= number_format($r['pct'], 2, '.', '') ?>%"></span>
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

    <footer class="midterms-odds__footer">
        <?php if ($sourceUrl): ?>
            <a href="<?= pp_e($sourceUrl) ?>" target="_blank" rel="noopener nofollow">
                Polymarket <?= pp_icon('external-link', 12) ?>
            </a>
        <?php endif; ?>
        <?php if ($volume !== null && $volume > 0): ?>
            <span>Volume $<?= pp_e(number_format($volume, 0, ',', '.')) ?></span>
        <?php endif; ?>
        <?php if ($isFallback): ?><span class="midterms-odds__stale">fallbackmomentopname</span><?php endif; ?>
    </footer>
</article>
