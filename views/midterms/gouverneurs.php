<?php require_once 'views/templates/header.php'; ?>
<?php
$competitive = array_values(array_filter($races, static fn ($r) => !empty($r->is_competitive)));
$other = array_values(array_filter($races, static fn ($r) => empty($r->is_competitive)));
$fallbackRows = array_map(static fn ($r) => ['name' => $r->state_name, 'rating' => $r->rating], $races);
$eyebrow = 'Midterms 2026 · Gouverneurs';
$title = 'Vijftig staten, vijftig politieke systemen.';
$lead = 'In 36 staten kiezen Amerikanen een gouverneur. Zij bepalen direct mee over onderwijs, begrotingen, kiesregels, reproductieve rechten en de uitvoering van federaal beleid.';
$meta = [
    ['icon' => 'map-pin', 'text' => '36 staten'],
    ['icon' => 'landmark', 'text' => '26 R · 24 D nu'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'gouverneurs'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <?php if (!empty($odds['rep_governors'])): ?>
        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Marktpuls</span><h2>De landelijke balans na november</h2></div>
                <p>De markt verdeelt de mogelijke uitkomst in bandbreedtes. Dit is geen voorspelling per afzonderlijke staat.</p>
            </div>
            <div class="midterms-odds-row mt-market-row--single">
                <?php $odd = $odds['rep_governors']; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="mt-dashboard-section" aria-labelledby="governor-map-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Interactieve kaart</span><h2 id="governor-map-title">De 36 gouverneursraces</h2></div>
                <p>Filter op toss-ups, competitieve races of open zetels. Een open race heeft geen zittende gouverneur op het stembiljet.</p>
            </div>
            <div class="mt-map-shell">
                <?php $chamber = 'governor'; $title = 'Gouverneursraces 2026'; require __DIR__ . '/partials/map.php'; ?>
            </div>
        </section>

        <?php if (!empty($competitive)): ?>
        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Battleboard</span><h2>Competitieve staten</h2></div>
                <p>De staten waar kandidaten, lokale omstandigheden en nationale trends het meest samenkomen.</p>
            </div>
            <div class="midterms-race-grid">
                <?php foreach ($competitive as $race): require __DIR__ . '/partials/race-card.php'; endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="mt-dashboard-section">
            <details class="mt-race-archive" data-mt-reveal>
                <summary><span><small>Volledige lijst</small>Bekijk de overige <?= count($other) ?> gouverneursraces</span><?= pp_icon('chevron-down', 18) ?></summary>
                <div class="midterms-race-grid">
                    <?php foreach ($other as $race): require __DIR__ . '/partials/race-card.php'; endforeach; ?>
                </div>
            </details>
        </section>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
