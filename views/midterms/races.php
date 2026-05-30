<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $competitive */
/** @var array $odds */

$groups = ['senate' => [], 'governor' => [], 'house' => []];
foreach ($competitive as $r) {
    $c = $r->chamber ?? '';
    if (isset($groups[$c])) {
        $groups[$c][] = $r;
    }
}
$groupTitles = [
    'senate' => 'Senaat',
    'governor' => 'Gouverneurs',
    'house' => 'Huis',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Sleutelraces',
    'title' => 'De races die de uitslag bepalen',
    'lead' => 'Een overzicht van de meest spannende en beslissende races van 2026, gesorteerd per kamer. Dit zijn de toss-ups en swing states waar de meerderheid wordt beslist.',
]) ?>

<?php $active = 'races'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">

        <section class="midterms-block">
            <div class="midterms-odds-row">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>

        <?php foreach ($groups as $chamber => $list): ?>
            <?php if (!empty($list)): ?>
            <section class="midterms-block">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Sleutelraces',
                    'title' => $groupTitles[$chamber] ?? '',
                ]) ?>
                <div class="midterms-race-grid">
                    <?php foreach ($list as $race): ?>
                        <?php require __DIR__ . '/partials/race-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (empty($competitive)): ?>
            <p class="midterms-empty">De sleutelraces worden binnenkort toegevoegd.</p>
        <?php endif; ?>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
