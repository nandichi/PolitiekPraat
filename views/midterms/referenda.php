<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $referenda */
/** @var array $odds */

$themeLabels = [
    'abortus' => 'Abortus',
    'belastingen' => 'Belastingen',
    'staatsinrichting' => 'Staatsinrichting',
    'kiesrecht' => 'Kiesrecht',
    'drugs' => 'Drugs',
    'minimumloon' => 'Minimumloon',
    'klimaat' => 'Klimaat',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Referenda',
    'title' => 'Referenda en ballot measures',
    'lead' => 'Naast kandidaten stemmen Amerikanen ook rechtstreeks over voorstellen: van abortusrechten tot belastingen. Hieronder de meest besproken referenda van 2026.',
]) ?>

<?php $active = 'referenda'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--lg">
        <?php if (!empty($referenda)): ?>
            <div class="midterms-ref-grid">
                <?php foreach ($referenda as $ref): ?>
                    <?php
                    $oddKey = 'ref_' . ($ref->polymarket_slug ?? '');
                    $refOdd = $odds[$oddKey] ?? null;
                    ?>
                    <article class="midterms-ref-card">
                        <div class="midterms-ref-card__head">
                            <?php if (!empty($ref->theme)): ?>
                                <span class="midterms-ref-card__theme"><?= pp_e($themeLabels[$ref->theme] ?? ucfirst($ref->theme)) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($ref->state_name)): ?>
                                <span class="midterms-ref-card__state"><?= pp_icon('map-pin', 12) ?> <?= pp_e($ref->state_name) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="midterms-ref-card__title"><?= pp_e($ref->title_nl ?? '') ?></h2>
                        <?php if (!empty($ref->description_nl)): ?>
                            <p class="midterms-ref-card__text"><?= pp_e($ref->description_nl) ?></p>
                        <?php endif; ?>

                        <?php if ($refOdd !== null): ?>
                            <?php $odd = $refOdd; require __DIR__ . '/partials/odds-card.php'; ?>
                        <?php elseif (!empty($ref->source_url)): ?>
                            <a href="<?= pp_e($ref->source_url) ?>" class="midterms-ref-card__source" target="_blank" rel="noopener nofollow">
                                Meer over dit voorstel <?= pp_icon('external-link', 12) ?>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="midterms-empty">De referenda worden binnenkort toegevoegd.</p>
        <?php endif; ?>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
