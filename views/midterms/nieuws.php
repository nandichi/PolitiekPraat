<?php require_once 'views/templates/header.php'; ?>
<?php
$featured = $news[0] ?? null;
$moreNews = !empty($news) ? array_slice($news, 1) : [];
$eyebrow = 'Midterms 2026 · Nieuwsmonitor';
$title = 'De campagne, zonder de ruis.';
$lead = 'Een automatisch ververste selectie uit betrouwbare nieuwsbronnen. Koppen en samenvattingen kunnen zijn vertaald; de oorspronkelijke publicatie blijft altijd één klik verwijderd.';
$meta = [
    ['icon' => 'refresh-cw', 'text' => 'Meerdere updates per dag'],
    ['icon' => 'external-link', 'text' => 'Altijd naar de oorspronkelijke bron'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'nieuws'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <?php if ($featured): ?>
        <section class="mt-dashboard-section">
            <a href="<?= pp_e($featured->url ?? '#') ?>" target="_blank" rel="noopener nofollow" class="mt-news-feature" data-mt-reveal>
                <div class="mt-news-feature__media">
                    <?php if (!empty($featured->image_url)): ?>
                        <img src="<?= pp_e($featured->image_url) ?>" alt="" fetchpriority="high" referrerpolicy="no-referrer" onerror="this.parentElement.classList.add('is-empty');this.remove();">
                    <?php endif; ?>
                    <span class="mt-news-feature__wash" aria-hidden="true"></span>
                </div>
                <div class="mt-news-feature__body">
                    <span class="mt-panel-label">Uitgelicht</span>
                    <div class="mt-news-feature__meta"><?= pp_e($featured->source ?? 'bron') ?><?php if (!empty($featured->published_at)): ?> · <?= pp_e(mt_date_nl($featured->published_at)) ?><?php endif; ?></div>
                    <h2><?= pp_e($featured->title ?? '') ?></h2>
                    <?php if (!empty($featured->intro_nl)): ?><p><?= pp_e($featured->intro_nl) ?></p><?php endif; ?>
                    <span class="mt-text-link">Lees bij de bron <?= pp_icon('arrow-up-right', 14) ?></span>
                </div>
            </a>
        </section>
        <?php endif; ?>

        <section class="mt-dashboard-section">
            <div class="mt-section-heading" data-mt-reveal>
                <div><span class="mt-panel-label">Nieuwsstroom</span><h2>Laatste ontwikkelingen</h2></div>
                <p><?= $oddsUpdatedAt ? 'Marktdata ' . pp_e(mt_freshness_label($oddsUpdatedAt)) . '; ' : '' ?>nieuws wordt afzonderlijk meerdere keren per dag ververst.</p>
            </div>

            <?php if (!empty($moreNews)): ?>
                <div class="midterms-news-grid">
                    <?php foreach ($moreNews as $item): ?>
                        <article class="midterms-news-card" data-mt-reveal>
                            <a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow" class="midterms-news-card__media">
                                <?php if (!empty($item->image_url)): ?>
                                    <img src="<?= pp_e($item->image_url) ?>" alt="" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentElement.classList.add('is-empty');this.remove();">
                                <?php else: ?>
                                    <span><?= pp_icon('newspaper', 28) ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="midterms-news-card__body">
                                <div class="midterms-news-card__meta"><span><?= pp_e($item->source ?? 'bron') ?></span><?php if (!empty($item->published_at)): ?><time><?= pp_e(mt_date_nl($item->published_at)) ?></time><?php endif; ?></div>
                                <h2 class="midterms-news-card__title"><a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow"><?= pp_e($item->title ?? '') ?></a></h2>
                                <?php if (!empty($item->intro_nl)): ?><p class="midterms-news-card__intro"><?= pp_e($item->intro_nl) ?></p><?php endif; ?>
                                <a href="<?= pp_e($item->url ?? '#') ?>" class="midterms-news-card__link" target="_blank" rel="noopener nofollow">Naar de bron <?= pp_icon('arrow-up-right', 12) ?></a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php elseif (!$featured): ?>
                <p class="midterms-empty">Er is op dit moment geen nieuws geladen.</p>
            <?php endif; ?>
        </section>

        <?php $text = 'Deze nieuwsmonitor verzamelt berichten via Brave Search en filtert ze op de Amerikaanse midterms van 2026. Een automatische selectie kan fouten maken; PolitiekPraat blijft niet de uitgever van de gelinkte artikelen.'; require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
