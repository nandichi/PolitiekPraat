<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $races */
/** @var array $odds */
/** @var array $ratingMeta */
/** @var MidtermsModel $model */

$competitive = array_values(array_filter($races, static fn ($r) => !empty($r->is_competitive)));
$other = array_values(array_filter($races, static fn ($r) => empty($r->is_competitive)));
$fallbackRows = array_map(static fn ($r) => ['name' => $r->state_name, 'rating' => $r->rating], $races);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Senaat',
    'title' => 'De strijd om de Senaat',
    'lead' => 'In 2026 staan 35 van de 100 Senaatszetels op de stembus. De meerderheid bepaalt of de president kan rekenen op steun voor benoemingen en wetgeving.',
    'meta' => [
        ['icon' => 'landmark', 'text' => '35 races in 2026'],
        ['icon' => 'calendar', 'text' => 'Verkiezingsdag 3 november 2026'],
    ],
]) ?>

<?php $active = 'senaat'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">

        <section class="midterms-block">
            <div class="midterms-odds-row">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php if (!empty($odds['rep_senate_seats'])): ?>
                    <?php $odd = $odds['rep_senate_seats']; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Interactieve kaart',
                'title' => 'Alle Senaatsraces',
            ]) ?>
            <p class="midterms-block__intro">Beweeg over een staat voor de inschatting. Klik voor de details van de race.</p>
            <?php
            $chamber = 'senate';
            $title = 'Senaatsraces 2026';
            require __DIR__ . '/partials/map.php';
            ?>
        </section>

        <?php if (!empty($competitive)): ?>
        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Toss-ups en swing states',
                'title' => 'Competitieve races',
            ]) ?>
            <div class="midterms-race-grid">
                <?php foreach ($competitive as $race): ?>
                    <?php require __DIR__ . '/partials/race-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($other)): ?>
        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Overige',
                'title' => 'Minder spannende races',
            ]) ?>
            <div class="midterms-race-grid">
                <?php foreach ($other as $race): ?>
                    <?php require __DIR__ . '/partials/race-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
