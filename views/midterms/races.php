<?php require_once 'views/templates/header.php'; ?>
<?php
$groups = ['senate' => [], 'house' => [], 'governor' => []];
foreach ($competitive as $race) {
    $chamber = $race->chamber ?? '';
    if (isset($groups[$chamber])) $groups[$chamber][] = $race;
}
$eyebrow = 'Midterms 2026 · Battleboard';
$title = 'De races die de machtsbalans kunnen kantelen.';
$lead = 'Niet iedere verkiezing is even spannend. Hier staan de federale en staatsraces waar de uitgangspositie nog echt openligt, met rating, status en bron.';
$meta = [
    ['icon' => 'target', 'text' => count($competitive) . ' gevolgde races'],
    ['icon' => 'check-circle', 'text' => 'Gecontroleerd 19 augustus'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'races'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <section class="mt-dashboard-section">
            <div class="midterms-odds-row">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" data-mt-race-board>
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Race explorer</span><h2>Filter het battleboard</h2></div>
                <div class="mt-filter-pills" role="group" aria-label="Filter sleutelraces" data-race-filters="#mt-race-explorer">
                    <button type="button" class="is-active" data-race-filter="all">Alles <span><?= count($competitive) ?></span></button>
                    <button type="button" data-race-filter="senate">Senaat <span><?= count($groups['senate']) ?></span></button>
                    <button type="button" data-race-filter="house">Huis <span><?= count($groups['house']) ?></span></button>
                    <button type="button" data-race-filter="governor">Gouverneurs <span><?= count($groups['governor']) ?></span></button>
                </div>
            </div>

            <div class="midterms-race-grid midterms-race-grid--explorer" id="mt-race-explorer">
                <?php foreach ($competitive as $race): ?>
                    <?php require __DIR__ . '/partials/race-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php if (empty($competitive)): ?><p class="midterms-empty">Er zijn nu geen sleutelraces gepubliceerd.</p><?php endif; ?>
        </section>

        <aside class="mt-method-note" data-mt-reveal>
            <span><?= pp_icon('info', 17) ?></span>
            <div><strong>Wat betekent een rating?</strong><p>Een rating is een redactionele inschatting van de uitgangspositie, geen kanspercentage. “Toss-up” betekent dat geen partij een duidelijk voordeel heeft.</p></div>
            <a href="<?= pp_e(pp_url('/midterms-2026/uitleg')) ?>">Lees de methodiek</a>
        </aside>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
