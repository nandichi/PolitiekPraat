<?php require_once 'views/templates/header.php'; ?>
<?php
$themeLabels = [
    'abortus' => 'Reproductieve rechten',
    'belastingen' => 'Belastingen',
    'staatsinrichting' => 'Staatsinrichting',
    'kiesrecht' => 'Kiesrecht',
    'wonen' => 'Wonen',
    'zorg' => 'Zorg & rechten',
];
$eyebrow = 'Midterms 2026 · Ballot measures';
$title = 'Kiezers stemmen ook rechtstreeks over beleid.';
$lead = 'Naast kandidaten liggen in tientallen staten voorstellen op het stembiljet. Van grondwetswijzigingen tot wonen en reproductieve rechten: deze keuzes werken vaak jarenlang door.';
$meta = [
    ['icon' => 'vote', 'text' => '152 statewide measures gecertificeerd'],
    ['icon' => 'map-pin', 'text' => '40 staten'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'referenda'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Selectie</span><h2>Vier landelijke blikvangers</h2></div>
                <p>Dit is een redactionele selectie uit 152 gecertificeerde statewide ballot measures. De formulering is samengevat; de bron bevat de officiële details.</p>
            </div>

            <?php if (!empty($referenda)): ?>
                <div class="midterms-ref-grid">
                    <?php foreach ($referenda as $index => $ref): ?>
                        <?php
                        $oddKey = 'ref_' . ($ref->polymarket_slug ?? '');
                        $refOdd = $odds[$oddKey] ?? null;
                        ?>
                        <article class="midterms-ref-card" data-mt-reveal>
                            <div class="midterms-ref-card__index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>
                            <div class="midterms-ref-card__head">
                                <span class="midterms-ref-card__theme"><?= pp_e($themeLabels[$ref->theme ?? ''] ?? ucfirst((string) ($ref->theme ?? 'Voorstel'))) ?></span>
                                <span class="midterms-ref-card__state"><?= pp_e($ref->state_code ?? '') ?><?php if (!empty($ref->measure_code)): ?> · <?= pp_e($ref->measure_code) ?><?php endif; ?></span>
                            </div>
                            <h2 class="midterms-ref-card__title"><?= pp_e($ref->title_nl ?? '') ?></h2>
                            <p class="midterms-ref-card__text"><?= pp_e($ref->description_nl ?? '') ?></p>
                            <?php if ($refOdd !== null): ?>
                                <?php $odd = $refOdd; require __DIR__ . '/partials/odds-card.php'; ?>
                            <?php elseif (!empty($ref->source_url)): ?>
                                <a href="<?= pp_e($ref->source_url) ?>" class="midterms-ref-card__source" target="_blank" rel="noopener nofollow">
                                    Officiële context <?= pp_icon('arrow-up-right', 13) ?>
                                </a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="midterms-empty">Er zijn nog geen ballot measures gepubliceerd.</p>
            <?php endif; ?>
        </section>

        <section class="mt-explainer-cta mt-explainer-cta--blue" data-mt-reveal>
            <div><span class="mt-panel-label">Terminologie</span><h2>Een ballot measure is geen gewoon referendum.</h2><p>De precieze route, juridische werking en vereiste meerderheid verschillen per staat en type voorstel.</p></div>
            <a href="https://ballotpedia.org/2026_ballot_measures" target="_blank" rel="noopener" class="mt-button mt-button--light">Alle voorstellen <?= pp_icon('external-link', 14) ?></a>
        </section>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
