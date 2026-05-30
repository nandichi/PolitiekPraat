<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $races */
/** @var array $competitive */
/** @var array $odds */
/** @var array $ratingMeta */
/** @var MidtermsModel $model */

$competitive = $competitive ?? [];
$fallbackRows = [];
foreach ($competitive as $r) {
    $dist = $r->district ?? '';
    $fallbackRows[] = [
        'name' => ($r->state_name ?? '') . ' ' . ($dist === 'AL' ? '(at-large)' : $dist),
        'rating' => $r->rating ?? 'tossup',
    ];
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Huis van Afgevaardigden',
    'title' => 'De strijd om het Huis',
    'lead' => 'Alle 435 zetels van het Huis van Afgevaardigden worden opnieuw gekozen. De partij met de meerderheid levert de Speaker en bepaalt de wetgevende agenda.',
    'meta' => [
        ['icon' => 'users', 'text' => '435 districten'],
        ['icon' => 'calendar', 'text' => 'Verkiezingsdag 3 november 2026'],
    ],
]) ?>

<?php $active = 'huis'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">

        <section class="midterms-block">
            <div class="midterms-odds-row">
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php if (!empty($odds['rep_house_seats'])): ?>
                    <?php $odd = $odds['rep_house_seats']; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Interactieve kaart',
                'title' => 'Alle 435 districten',
            ]) ?>
            <p class="midterms-block__intro">De kaart kleurt elk district naar de huidige partij; competitieve districten krijgen een aparte inschatting. Beweeg over een district voor details.</p>
            <?php
            $chamber = 'house';
            $title = 'Huis-districten 2026';
            require __DIR__ . '/partials/map.php';
            ?>
        </section>

        <?php if (!empty($competitive)): ?>
        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Slagvelden',
                'title' => 'Competitieve districten',
            ]) ?>
            <div class="midterms-race-grid">
                <?php foreach ($competitive as $race): ?>
                    <?php require __DIR__ . '/partials/race-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php else: ?>
        <section class="midterms-block">
            <p class="midterms-empty">De competitieve districten worden binnenkort toegevoegd.</p>
        </section>
        <?php endif; ?>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
