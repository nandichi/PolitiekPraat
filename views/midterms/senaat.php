<?php require_once 'views/templates/header.php'; ?>
<?php
$competitive = array_values(array_filter($races, static fn ($r) => !empty($r->is_competitive)));
$other = array_values(array_filter($races, static fn ($r) => empty($r->is_competitive)));
$fallbackRows = array_map(static fn ($r) => ['name' => $r->state_name, 'rating' => $r->rating], $races);
$eyebrow = 'Midterms 2026 · Senaat';
$title = 'Vier zetels scheiden de Democraten van de meerderheid.';
$lead = 'Van Alaska tot Maine: 35 Senaatsraces bepalen of president Trump ook na januari 2027 kan rekenen op een Republikeinse meerderheid voor wetgeving en benoemingen.';
$meta = [
    ['icon' => 'landmark', 'text' => '35 races'],
    ['icon' => 'target', 'text' => '4 toss-ups'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'senaat'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Marktpuls</span><h2>Controle over de Senaat</h2></div>
                <p>De 53 Republikeinen beginnen met het voordeel. Twee onafhankelijken stemmen doorgaans met de Democraten mee.</p>
            </div>
            <div class="midterms-odds-row">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php if (!empty($odds['rep_senate_seats'])): $odd = $odds['rep_senate_seats']; require __DIR__ . '/partials/odds-card.php'; endif; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" aria-labelledby="senate-map-heading">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Racekaart</span><h2 id="senate-map-heading">Alle Senaatsraces</h2></div>
                <p>Filter op competitieve races of open zetels. Klik of focus op een staat voor de details.</p>
            </div>
            <?php
            $summary = $midtermsContext['senate']['rating_summary'] ?? [];
            $barTitle = 'Cook-ratingverdeling';
            $barNote = '9 zeker D · 4 toss-up · 14 zeker R. Gecontroleerd 19 augustus 2026.';
            require __DIR__ . '/partials/rating-bar.php';
            ?>
            <div class="mt-map-shell">
                <?php $chamber = 'senate'; $title = 'Senaatsraces 2026'; require __DIR__ . '/partials/map.php'; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" aria-labelledby="senate-battlegrounds">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Battleboard</span><h2 id="senate-battlegrounds">De slagvelden</h2></div>
                <p>Toss-ups en races die naar een partij neigen, maar nog niet veilig zijn.</p>
            </div>
            <div class="midterms-race-grid">
                <?php foreach ($competitive as $race): require __DIR__ . '/partials/race-card.php'; endforeach; ?>
            </div>
        </section>

        <section class="mt-dashboard-section">
            <details class="mt-race-archive" data-mt-reveal>
                <summary><span><small>Volledige lijst</small>Bekijk de overige <?= count($other) ?> Senaatsraces</span><?= pp_icon('chevron-down', 18) ?></summary>
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
