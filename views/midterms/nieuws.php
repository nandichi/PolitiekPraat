<?php require_once 'views/templates/header.php'; ?>

<?php /** @var array $news */ ?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Nieuws',
    'title' => 'Het laatste nieuws over de midterms',
    'lead' => 'Een selectie van actueel nieuws over de Amerikaanse tussentijdse verkiezingen, met een korte toelichting in het Nederlands en een link naar de oorspronkelijke bron.',
]) ?>

<?php $active = 'nieuws'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--lg">
        <?php if (!empty($news)): ?>
            <div class="midterms-news-grid">
                <?php foreach ($news as $item): ?>
                    <article class="midterms-news-card">
                        <?php if (!empty($item->image_url)): ?>
                            <a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow" class="midterms-news-card__media">
                                <img src="<?= pp_e($item->image_url) ?>" alt="" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="midterms-news-card__body">
                            <div class="midterms-news-card__meta">
                                <span><?= pp_e($item->source ?? 'bron') ?></span>
                                <?php if (!empty($item->published_at)): ?>
                                    <span><?= pp_e(mt_date_nl($item->published_at)) ?></span>
                                <?php endif; ?>
                            </div>
                            <h2 class="midterms-news-card__title">
                                <a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow">
                                    <?= pp_e($item->title ?? '') ?>
                                </a>
                            </h2>
                            <?php if (!empty($item->intro_nl)): ?>
                                <p class="midterms-news-card__intro"><?= pp_e($item->intro_nl) ?></p>
                            <?php endif; ?>
                            <a href="<?= pp_e($item->url ?? '#') ?>" class="midterms-news-card__link" target="_blank" rel="noopener nofollow">
                                Lees verder <?= pp_icon('external-link', 12) ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="midterms-empty">Er is op dit moment nog geen nieuws geladen. Het nieuws wordt automatisch ververst zodra de koppeling met de bronnen actief is.</p>
        <?php endif; ?>

        <?php $text = 'Het nieuws wordt automatisch verzameld via een nieuwszoekmachine. De korte introductie is door de redactie toegevoegd; de volledige berichten staan bij de oorspronkelijke bron.'; require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
