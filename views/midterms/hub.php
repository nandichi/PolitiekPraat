<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $odds */
/** @var array $news */
/** @var array $timeline */
/** @var array $topRaces */
/** @var int $daysLeft */
/** @var string $electionDate */
/** @var array $ratingMeta */
/** @var MidtermsModel $model */

$content = $model instanceof MidtermsModel ? null : null;
$seedContent = (function () {
    $path = __DIR__ . '/../../includes/data/midterms_2026_seed.php';
    if (is_readable($path)) {
        $d = require $path;
        return $d['content'] ?? [];
    }
    return [];
})();
$hubIntro = $seedContent['hub_intro'] ?? '';
$disclaimerText = $seedContent['disclaimer'] ?? null;

$senateRaces = $model->getRacesByChamber('senate');
$senateFallback = array_map(static fn ($r) => ['name' => $r->state_name, 'rating' => $r->rating], $senateRaces);
?>

<section class="midterms-hero">
    <div class="pp-container pp-container--xl">
        <div class="midterms-hero__grid">
            <div class="midterms-hero__intro">
                <span class="midterms-hero__eyebrow">
                    <?= pp_icon('landmark', 14) ?>
                    Verenigde Staten - 3 november 2026
                </span>
                <h1 class="midterms-hero__title">De Amerikaanse midterms van 2026</h1>
                <p class="midterms-hero__lead"><?= pp_e($hubIntro) ?></p>

                <?php require __DIR__ . '/partials/countdown.php'; ?>

                <div class="midterms-hero__cta">
                    <a href="<?= pp_e(pp_url('/midterms-2026/senaat')) ?>" class="btn btn--primary">
                        <?= pp_icon('landmark', 16) ?> Senaat
                    </a>
                    <a href="<?= pp_e(pp_url('/midterms-2026/huis')) ?>" class="btn btn--ghost">
                        <?= pp_icon('users', 16) ?> Huis
                    </a>
                    <a href="<?= pp_e(pp_url('/midterms-2026/uitleg')) ?>" class="btn btn--ghost">
                        <?= pp_icon('help-circle', 16) ?> Hoe werkt het?
                    </a>
                </div>
            </div>

            <div class="midterms-hero__odds">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </div>
    </div>
</section>

<?php $active = 'hub'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">

        <!-- Kaartpreview Senaat -->
        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Interactieve kaart',
                'title' => 'De strijd om de Senaat',
                'cta_label' => 'Volledige Senaatskaart',
                'cta_href' => '/midterms-2026/senaat',
            ]) ?>
            <p class="midterms-block__intro">In 2026 staan 35 Senaatszetels op de stembus. Beweeg over de staten voor de inschatting per race.</p>
            <?php
            $chamber = 'senate';
            $title = 'Senaat 2026';
            $fallbackRows = $senateFallback;
            require __DIR__ . '/partials/map.php';
            ?>
        </section>

        <!-- Sleutelraces -->
        <?php if (!empty($topRaces)): ?>
        <section class="midterms-block">
            <?= pp_render_component('section/section-header', [
                'label' => 'Om in de gaten te houden',
                'title' => 'Sleutelraces',
                'cta_label' => 'Alle sleutelraces',
                'cta_href' => '/midterms-2026/races',
            ]) ?>
            <div class="midterms-race-grid">
                <?php foreach ($topRaces as $race): ?>
                    <?php require __DIR__ . '/partials/race-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <div class="midterms-columns">
            <!-- Laatste nieuws -->
            <section class="midterms-block">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Actueel',
                    'title' => 'Laatste nieuws',
                    'cta_label' => 'Meer nieuws',
                    'cta_href' => '/midterms-2026/nieuws',
                ]) ?>
                <?php if (!empty($news)): ?>
                    <ul class="midterms-news-list">
                        <?php foreach ($news as $item): ?>
                            <li class="midterms-news-item">
                                <a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow">
                                    <span class="midterms-news-item__title"><?= pp_e($item->title ?? '') ?></span>
                                    <?php if (!empty($item->intro_nl)): ?>
                                        <span class="midterms-news-item__intro"><?= pp_e($item->intro_nl) ?></span>
                                    <?php endif; ?>
                                    <span class="midterms-news-item__source">
                                        <?= pp_e($item->source ?? 'bron') ?>
                                        <?php if (!empty($item->published_at)): ?>
                                            &middot; <?= pp_e(mt_date_nl($item->published_at)) ?>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="midterms-empty">Nog geen nieuws geladen. Kom binnenkort terug.</p>
                <?php endif; ?>
            </section>

            <!-- Tijdlijn -->
            <section class="midterms-block">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Op weg naar 3 november',
                    'title' => 'Tijdlijn',
                    'cta_label' => 'Volledige tijdlijn',
                    'cta_href' => '/midterms-2026/voorverkiezingen',
                ]) ?>
                <?php if (!empty($timeline)): ?>
                    <ol class="midterms-timeline midterms-timeline--compact">
                        <?php foreach ($timeline as $event): ?>
                            <li class="midterms-timeline__item">
                                <span class="midterms-timeline__date"><?= pp_e(mt_date_nl($event->event_date ?? '')) ?></span>
                                <span class="midterms-timeline__title"><?= pp_e($event->title_nl ?? '') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <p class="midterms-empty">Tijdlijn wordt binnenkort gevuld.</p>
                <?php endif; ?>
            </section>
        </div>

        <?php $text = $disclaimerText; require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
