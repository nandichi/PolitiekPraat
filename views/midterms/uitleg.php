<?php require_once 'views/templates/header.php'; ?>

<?php
$seedContent = (function () {
    $path = __DIR__ . '/../../includes/data/midterms_2026_seed.php';
    if (is_readable($path)) {
        $d = require $path;
        return $d['content'] ?? [];
    }
    return [];
})();
$blocks = $seedContent['uitleg'] ?? [];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Uitleg',
    'title' => 'Hoe werken de Amerikaanse midterms?',
    'lead' => 'De midterms zijn de tussentijdse verkiezingen halverwege de ambtstermijn van de Amerikaanse president. Op deze pagina leggen we in het kort uit hoe het werkt, voor Nederlandse lezers.',
]) ?>

<?php $active = 'uitleg'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--lg">

        <div class="midterms-facts">
            <div class="midterms-facts__item">
                <span class="midterms-facts__num font-mono">435</span>
                <span class="midterms-facts__label">zetels in het Huis (allemaal verkiesbaar)</span>
            </div>
            <div class="midterms-facts__item">
                <span class="midterms-facts__num font-mono">35</span>
                <span class="midterms-facts__label">Senaatszetels op de stembus in 2026</span>
            </div>
            <div class="midterms-facts__item">
                <span class="midterms-facts__num font-mono">36</span>
                <span class="midterms-facts__label">gouverneursposten</span>
            </div>
            <div class="midterms-facts__item">
                <span class="midterms-facts__num font-mono">3 nov</span>
                <span class="midterms-facts__label">verkiezingsdag 2026</span>
            </div>
        </div>

        <div class="midterms-uitleg">
            <?php foreach ($blocks as $block): ?>
                <section class="midterms-uitleg__block">
                    <h2 class="midterms-uitleg__title"><?= pp_e($block['titel'] ?? '') ?></h2>
                    <p class="midterms-uitleg__text"><?= pp_e($block['tekst'] ?? '') ?></p>
                </section>
            <?php endforeach; ?>
        </div>

        <div class="midterms-uitleg__cta">
            <a href="<?= pp_e(pp_url('/midterms-2026/senaat')) ?>" class="btn btn--primary"><?= pp_icon('landmark', 16) ?> Naar de Senaatskaart</a>
            <a href="<?= pp_e(pp_url('/midterms-2026')) ?>" class="btn btn--ghost"><?= pp_icon('arrow-left', 16) ?> Terug naar overzicht</a>
        </div>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
