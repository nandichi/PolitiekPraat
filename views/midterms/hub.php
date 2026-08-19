<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $odds */
/** @var array $news */
/** @var array $timeline */
/** @var array $topRaces */
/** @var int $daysLeft */
/** @var array $midtermsContext */
/** @var MidtermsModel $model */

$seedContent = (function () {
    $path = __DIR__ . '/../../includes/data/midterms_2026_seed.php';
    if (!is_readable($path)) return [];
    $data = require $path;
    return $data['content'] ?? [];
})();
$hubIntro = $seedContent['hub_intro'] ?? '';
$disclaimerText = $seedContent['disclaimer'] ?? null;
$image = $midtermsContext['image'] ?? [];
$senateRaces = $model->getRacesByChamber('senate');
$senateFallback = array_map(static fn ($r) => ['name' => $r->state_name, 'rating' => $r->rating], $senateRaces);

$marketLeader = static function ($market): ?array {
    if (!$market) return null;
    $items = is_object($market) ? ($market->outcomes ?? []) : ($market['outcomes'] ?? []);
    $best = null;
    foreach ($items as $item) {
        $item = (array) $item;
        $price = (float) ($item['price'] ?? 0);
        if ($best === null || $price > $best['price']) {
            $best = ['label' => (string) ($item['label_nl'] ?? ($item['label'] ?? '')), 'price' => $price];
        }
    }
    return $best;
};
$senateLeader = $marketLeader($odds['senate_control'] ?? null);
$houseLeader = $marketLeader($odds['house_control'] ?? null);
?>

<section class="mt-hero">
    <div class="mt-hero__media" aria-hidden="true">
        <img src="<?= pp_e(pp_url($image['path'] ?? '/public/images/midterms/capitol-east-front.jpg')) ?>"
             alt="" fetchpriority="high" decoding="async">
    </div>
    <div class="mt-hero__veil" aria-hidden="true"></div>
    <div class="mt-hero__gridlines" aria-hidden="true"></div>

    <div class="pp-container pp-container--xl mt-hero__inner">
        <div class="mt-hero__copy" data-mt-hero-copy>
            <div class="mt-kicker mt-kicker--light">
                <span class="mt-live-dot" aria-hidden="true"></span>
                Election intelligence · Verenigde Staten
            </div>
            <p class="mt-hero__edition">Midterms <span>2026</span></p>
            <h1 class="mt-hero__title">De macht staat<br>op het spel.</h1>
            <p class="mt-hero__lead"><?= pp_e($hubIntro) ?></p>

            <div class="mt-hero__actions">
                <a href="#kaart" class="mt-button mt-button--light">Bekijk de kaart <?= pp_icon('arrow-down', 15) ?></a>
                <a href="<?= pp_e(pp_url('/midterms-2026/uitleg')) ?>" class="mt-button mt-button--glass">Hoe werkt het?</a>
            </div>
        </div>

        <aside class="mt-hero__desk" aria-label="Live verkiezingsstatus" data-mt-hero-desk>
            <div class="mt-hero__desk-head">
                <div>
                    <span class="mt-panel-label">Election Day</span>
                    <strong>Dinsdag 3 november</strong>
                </div>
                <span class="mt-status-pill"><i></i> Live tracker</span>
            </div>

            <?php require __DIR__ . '/partials/countdown.php'; ?>

            <div class="mt-hero__market-grid">
                <div class="mt-hero__market">
                    <span>Senaat</span>
                    <?php if ($senateLeader): ?>
                        <strong><?= pp_e($senateLeader['label']) ?></strong>
                        <b><?= pp_e(mt_pct($senateLeader['price'])) ?></b>
                    <?php else: ?>
                        <strong>Markt wordt geladen</strong><b>–</b>
                    <?php endif; ?>
                </div>
                <div class="mt-hero__market">
                    <span>Huis</span>
                    <?php if ($houseLeader): ?>
                        <strong><?= pp_e($houseLeader['label']) ?></strong>
                        <b><?= pp_e(mt_pct($houseLeader['price'])) ?></b>
                    <?php else: ?>
                        <strong>Markt wordt geladen</strong><b>–</b>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-hero__desk-foot">
                <span><?= pp_icon('refresh-cw', 13) ?> Marktdata <?= pp_e(mt_freshness_label($oddsUpdatedAt ?? null)) ?></span>
                <span>Geen peiling</span>
            </div>
        </aside>
    </div>

    <?php if (!empty($image['credit'])): ?>
        <a class="mt-hero__credit" href="<?= pp_e($image['source_url'] ?? '#') ?>" target="_blank" rel="noopener">
            Foto: <?= pp_e($image['credit']) ?> · <?= pp_e($image['license'] ?? '') ?>
        </a>
    <?php endif; ?>
</section>

<?php $active = 'hub'; require __DIR__ . '/partials/section-nav.php'; ?>
<?php require __DIR__ . '/partials/snapshot-strip.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <section class="mt-dashboard-section" aria-labelledby="market-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div>
                    <span class="mt-panel-label">Marktpuls</span>
                    <h2 id="market-title">Wat handelaren nu verwachten</h2>
                </div>
                <p>Live prijzen van Polymarket. Ze bewegen continu en zijn nadrukkelijk geen peiling of uitslagmodel.</p>
            </div>
            <div class="midterms-odds-row">
                <?php $odd = $odds['senate_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
                <?php $odd = $odds['house_control'] ?? null; require __DIR__ . '/partials/odds-card.php'; ?>
            </div>
        </section>

        <section class="mt-dashboard-section" id="kaart" aria-labelledby="senate-map-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div>
                    <span class="mt-panel-label">Interactieve kaart</span>
                    <h2 id="senate-map-title">De weg naar een Senaatsmeerderheid</h2>
                </div>
                <p>Vier races zijn toss-up. Democraten moeten netto vier zetels winnen, omdat de vicepresident een 50-50-gelijkstand beslist.</p>
            </div>
            <?php
            $summary = $midtermsContext['senate']['rating_summary'] ?? [];
            $barTitle = 'Alle 35 Senaatsraces';
            $barNote = 'Bron: Cook Political Report, handmatig gecontroleerd op 19 augustus 2026.';
            require __DIR__ . '/partials/rating-bar.php';
            ?>
            <div class="mt-map-shell">
                <?php
                $chamber = 'senate';
                $title = 'Senaatsraces 2026';
                $fallbackRows = $senateFallback;
                require __DIR__ . '/partials/map.php';
                ?>
            </div>
        </section>

        <?php if (!empty($topRaces)): ?>
        <section class="mt-dashboard-section" aria-labelledby="race-title">
            <div class="mt-section-heading" data-mt-reveal>
                <div>
                    <span class="mt-panel-label">Battleboard</span>
                    <h2 id="race-title">Races om nu te volgen</h2>
                </div>
                <a href="<?= pp_e(pp_url('/midterms-2026/races')) ?>" class="mt-text-link">Alle sleutelraces <?= pp_icon('arrow-right', 14) ?></a>
            </div>
            <div class="midterms-race-grid">
                <?php foreach ($topRaces as $race): require __DIR__ . '/partials/race-card.php'; endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="mt-dashboard-section mt-editorial-grid" aria-label="Nieuws en tijdlijn">
            <div class="mt-editorial-panel" data-mt-reveal>
                <div class="mt-section-heading mt-section-heading--compact">
                    <div><span class="mt-panel-label">Actueel</span><h2>Laatste nieuws</h2></div>
                    <a href="<?= pp_e(pp_url('/midterms-2026/nieuws')) ?>" class="mt-text-link">Alles bekijken</a>
                </div>
                <?php if (!empty($news)): ?>
                    <div class="mt-news-stack">
                        <?php foreach (array_slice($news, 0, 4) as $index => $item): ?>
                            <a href="<?= pp_e($item->url ?? '#') ?>" target="_blank" rel="noopener nofollow" class="mt-news-row">
                                <?php if (!empty($item->image_url)): ?>
                                    <img src="<?= pp_e($item->image_url) ?>" alt="" loading="lazy" referrerpolicy="no-referrer">
                                <?php else: ?>
                                    <span class="mt-news-row__index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                                <?php endif; ?>
                                <span class="mt-news-row__body">
                                    <small><?= pp_e($item->source ?? 'bron') ?><?php if (!empty($item->published_at)): ?> · <?= pp_e(mt_date_nl($item->published_at)) ?><?php endif; ?></small>
                                    <strong><?= pp_e($item->title ?? '') ?></strong>
                                </span>
                                <?= pp_icon('arrow-up-right', 14) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="midterms-empty">Er is nu geen nieuws geladen.</p>
                <?php endif; ?>
            </div>

            <div class="mt-editorial-panel" data-mt-reveal>
                <div class="mt-section-heading mt-section-heading--compact">
                    <div><span class="mt-panel-label">Kalender</span><h2>Op weg naar november</h2></div>
                    <a href="<?= pp_e(pp_url('/midterms-2026/voorverkiezingen')) ?>" class="mt-text-link">Volledige tijdlijn</a>
                </div>
                <ol class="midterms-timeline midterms-timeline--compact">
                    <?php foreach (array_slice($timeline ?? [], 0, 5) as $event): ?>
                        <li class="midterms-timeline__item">
                            <time><?= pp_e(mt_date_nl($event->event_date ?? '')) ?></time>
                            <strong><?= pp_e($event->title_nl ?? '') ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>

        <section class="mt-explainer-cta" data-mt-reveal>
            <div>
                <span class="mt-panel-label">Nieuw in Amerikaanse politiek?</span>
                <h2>Waarom 218, 51 en een handvol staten alles bepalen.</h2>
                <p>In tien minuten begrijp je hoe het Huis, de Senaat, gouverneurs en voorspellingsmarkten werken.</p>
            </div>
            <a href="<?= pp_e(pp_url('/midterms-2026/uitleg')) ?>" class="mt-button mt-button--dark">Lees de uitleg <?= pp_icon('arrow-right', 15) ?></a>
        </section>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php $text = $disclaimerText; require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
