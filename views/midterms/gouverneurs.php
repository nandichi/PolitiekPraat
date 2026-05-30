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
    'eyebrow' => 'Midterms 2026 - Gouverneurs',
    'title' => 'De gouverneursraces',
    'lead' => 'In 2026 worden 36 gouverneursposten gekozen. Gouverneurs bepalen veel over onderwijs, abortusrecht, kiesregels en begroting in hun staat.',
    'meta' => [
        ['icon' => 'map-pin', 'text' => '36 staten'],
        ['icon' => 'calendar', 'text' => 'Verkiezingsdag 3 november 2026'],
    ],
]) ?>

<?php $active = 'gouverneurs'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">

        <?php if (!empty($odds['rep_governors'])): ?>
        <section class="midterms-block">
            <div class="midterms-odds-row">
                <?php $odd = $odds['rep_governors']; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Interactieve kaart',
                'title' => 'Alle gouverneursraces',
            ]) ?>
            <p class="midterms-block__intro">Beweeg over een staat voor de inschatting van de race.</p>
            <?php
            $chamber = 'governor';
            $title = 'Gouverneursraces 2026';
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
