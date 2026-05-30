<?php
/**
 * Container voor de interactieve D3-kaart.
 * Props:
 *   $chamber  'senate'|'house'|'governor'
 *   $title    string (toegankelijkheidslabel)
 *   $ratingMeta array
 *   $fallbackRows array  (lijst voor de tabel-fallback: ['name','rating'])
 */
$chamber = $chamber ?? ($props['chamber'] ?? 'senate');
$title = $title ?? ($props['title'] ?? 'Interactieve kaart');
$ratingMeta = $ratingMeta ?? ($props['ratingMeta'] ?? MidtermsModel::ratingMeta());
$fallbackRows = $fallbackRows ?? ($props['fallbackRows'] ?? []);

$topo = $chamber === 'house'
    ? pp_url('/public/data/us-congress-119.json')
    : pp_url('/public/data/us-states-albers.json');
$mapObject = $chamber === 'house' ? 'districts' : 'states';
$feed = pp_url('/midterms-2026/data/map?chamber=' . $chamber);
?>
<div class="midterms-map"
     data-midterms-map
     data-chamber="<?= pp_e($chamber) ?>"
     data-feed="<?= pp_e($feed) ?>"
     data-topo="<?= pp_e($topo) ?>"
     data-object="<?= pp_e($mapObject) ?>">
    <div class="midterms-map__canvas" data-map-canvas aria-hidden="true">
        <div class="midterms-map__loading" data-map-loading>
            <?= pp_icon('refresh-cw', 18) ?>
            <span>Kaart laden...</span>
        </div>
    </div>
    <div class="midterms-map__tooltip" data-map-tooltip hidden></div>

    <?php require __DIR__ . '/legend.php'; ?>

    <details class="midterms-map__fallback">
        <summary><?= pp_e($title) ?>: tabelweergave</summary>
        <?php if (!empty($fallbackRows)): ?>
            <table class="midterms-table">
                <thead>
                    <tr><th scope="col">Gebied</th><th scope="col">Inschatting</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($fallbackRows as $row): ?>
                        <tr>
                            <td><?= pp_e($row['name'] ?? '') ?></td>
                            <td>
                                <span class="mt-rating-dot mt-rating--<?= pp_e($row['rating'] ?? 'tossup') ?>"></span>
                                <?= pp_e($ratingMeta[$row['rating'] ?? 'tossup']['label'] ?? 'Onbeslist') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-[color:var(--color-ink-muted)] text-sm mt-2">Geen gegevens beschikbaar voor de tabelweergave.</p>
        <?php endif; ?>
    </details>
</div>
