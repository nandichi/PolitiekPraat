<?php require_once 'views/templates/header.php'; ?>
<?php
$competitive = $competitive ?? [];
$fallbackRows = [];
foreach ($competitive as $race) {
    $fallbackRows[] = [
        'name' => ($race->state_name ?? '') . ' ' . (($race->district ?? '') === 'AL' ? '(at-large)' : ($race->district ?? '')),
        'rating' => $race->rating ?? 'tossup',
    ];
}
$house = $midtermsContext['house'] ?? [];
$current = $house['current'] ?? [];
$eyebrow = 'Midterms 2026 · Huis van Afgevaardigden';
$title = 'Alle 435 zetels. Eén landelijke krachtmeting.';
$lead = 'De Republikeinse meerderheid is klein en iedere zetel wordt opnieuw gekozen. De partij die 218 haalt, kiest de Speaker en bepaalt de agenda van het Huis.';
$meta = [
    ['icon' => 'users', 'text' => '435 districten'],
    ['icon' => 'target', 'text' => '218 voor een meerderheid'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'huis'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Huidige 119e Congres</span><h2>Een meerderheid met nauwelijks marge</h2></div>
                <p>Vacatures en een onafhankelijke zetel maken de dagelijkse stemverhouding beweeglijk. De verkiezing start voor beide partijen opnieuw bij nul.</p>
            </div>
            <div class="mt-chamber-balance" data-mt-reveal>
                <div class="mt-chamber-balance__numbers">
                    <div class="mt-party-text--dem"><span>Democraten</span><strong data-mt-count="<?= (int) ($current['D'] ?? 212) ?>"><?= (int) ($current['D'] ?? 212) ?></strong></div>
                    <div class="mt-chamber-balance__majority"><span>Meerderheid</span><strong><?= (int) ($house['majority'] ?? 218) ?></strong></div>
                    <div class="mt-party-text--gop"><span>Republikeinen</span><strong data-mt-count="<?= (int) ($current['R'] ?? 218) ?>"><?= (int) ($current['R'] ?? 218) ?></strong></div>
                </div>
                <div class="mt-chamber-balance__track" role="img" aria-label="Huidige zetelverdeling: <?= (int) ($current['D'] ?? 212) ?> Democraten, <?= (int) ($current['R'] ?? 218) ?> Republikeinen, <?= (int) ($current['I'] ?? 1) ?> onafhankelijke en <?= (int) ($current['vacant'] ?? 4) ?> vacatures">
                    <span class="mt-party--dem" style="--seats:<?= (int) ($current['D'] ?? 212) ?>"></span>
                    <span class="mt-party--ind" style="--seats:<?= (int) ($current['I'] ?? 1) ?>"></span>
                    <span class="mt-party--neutral" style="--seats:<?= (int) ($current['vacant'] ?? 4) ?>"></span>
                    <span class="mt-party--gop" style="--seats:<?= (int) ($current['R'] ?? 218) ?>"></span>
                    <i style="--majority:<?= (int) ($house['majority'] ?? 218) ?>"></i>
                </div>
                <div class="mt-chamber-balance__foot">
                    <span><?= (int) ($current['I'] ?? 1) ?> onafhankelijk</span>
                    <span><?= (int) ($current['vacant'] ?? 4) ?> vacatures</span>
                    <a href="https://pressgallery.house.gov/member-data/party-breakdown" target="_blank" rel="noopener">House Press Gallery <?= pp_icon('external-link', 12) ?></a>
                </div>
            </div>
        </section>

        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Landelijk beeld</span><h2>Waar de 435 races nu staan</h2></div>
                <p>De verdeling hieronder vat de actuele Cook-categorieën samen, zonder de volledige dataset te kopiëren.</p>
            </div>
            <?php
            $summary = $house['rating_summary'] ?? [];
            $barTitle = 'Ratingverdeling Huis';
            $barNote = '19 districten zijn toss-up. Bron: Cook Political Report, 13 augustus 2026.';
            require __DIR__ . '/partials/rating-bar.php';
            ?>
            <div class="midterms-odds-row mt-market-row--single">
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" aria-labelledby="house-races-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Geverifieerde races</span><h2 id="house-races-title">Vier districten om te volgen</h2></div>
                <p>Kandidaten en zetelstatus zijn bijgewerkt na de voorverkiezingen.</p>
            </div>
            <div class="midterms-race-grid">
                <?php foreach ($competitive as $race): require __DIR__ . '/partials/race-card.php'; endforeach; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" aria-labelledby="house-map-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Kaart in transitie</span><h2 id="house-map-title">Districtenbasis 119e Congres</h2></div>
                <p><?= pp_e($house['boundary_note'] ?? '') ?></p>
            </div>
            <div class="mt-map-shell mt-map-shell--muted">
                <?php $chamber = 'house'; $title = 'Huis-districten, huidige basis'; require __DIR__ . '/partials/map.php'; ?>
            </div>
        </section>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
