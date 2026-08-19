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
    <div class="midterms-map__toolbar">
        <div class="midterms-map__filters" role="group" aria-label="Filter kaart">
            <button type="button" class="is-active" data-map-filter="all">Alle</button>
            <button type="button" data-map-filter="competitive">Competitief</button>
            <button type="button" data-map-filter="tossup">Toss-ups</button>
            <button type="button" data-map-filter="open">Open zetels</button>
        </div>
        <div class="midterms-map__zoom" role="group" aria-label="Kaart vergroten of verkleinen">
            <button type="button" data-map-zoom="in" aria-label="Inzoomen">+</button>
            <button type="button" data-map-zoom="out" aria-label="Uitzoomen">−</button>
            <button type="button" data-map-zoom="reset" aria-label="Kaart herstellen"><?= pp_icon('maximize', 14) ?></button>
        </div>
    </div>

    <?php if ($chamber === 'house'): ?>
        <aside class="midterms-map__notice" role="note">
            <?= pp_icon('info', 15) ?>
            <span>De kaart toont de huidige 119e-Congresbasis. Nieuwe 2026-grenzen in tien staten worden toegevoegd zodra de landelijke Census-bestanden compleet zijn.</span>
        </aside>
    <?php endif; ?>

    <div class="midterms-map__stage">
        <div class="midterms-map__canvas" data-map-canvas aria-hidden="true">
            <div class="midterms-map__loading" data-map-loading>
                <span class="midterms-map__loader" aria-hidden="true"></span>
                <span>Verkiezingskaart laden</span>
            </div>
        </div>
        <div class="midterms-map__tooltip" data-map-tooltip hidden></div>
        <aside class="midterms-map__inspector" data-map-inspector aria-live="polite">
            <span class="mt-panel-label">Selecteer een gebied</span>
            <strong>Beweeg over de kaart</strong>
            <p>Je ziet hier de rating, zittende partij en status van de race.</p>
        </aside>
    </div>

    <?php require __DIR__ . '/legend.php'; ?>

    <details class="midterms-map__fallback">
        <summary><?= pp_e($title) ?> als toegankelijke tabel</summary>
        <?php if (!empty($fallbackRows)): ?>
            <div class="midterms-table-wrap">
                <table class="midterms-table">
                    <thead><tr><th scope="col">Gebied</th><th scope="col">Inschatting</th></tr></thead>
                    <tbody>
                    <?php foreach ($fallbackRows as $row): ?>
                        <tr>
                            <td><?= pp_e($row['name'] ?? '') ?></td>
                            <td><span class="mt-rating-dot mt-rating--<?= pp_e($row['rating'] ?? 'tossup') ?>"></span><?= pp_e($ratingMeta[$row['rating'] ?? 'tossup']['label'] ?? 'Onbeslist') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Geen gegevens beschikbaar voor de tabelweergave.</p>
        <?php endif; ?>
    </details>
</div>
