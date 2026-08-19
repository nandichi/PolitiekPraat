<?php require_once 'views/templates/header.php'; ?>
<?php
$catLabels = ['primary' => 'Voorverkiezing', 'event' => 'Context', 'deadline' => 'Mijlpaal', 'news' => 'Nieuws', 'debate' => 'Debat'];
$eyebrow = 'Midterms 2026 · Tijdlijn';
$title = 'Van de eerste primary tot Election Day.';
$lead = 'De Amerikaanse verkiezingskalender is geen nationale sprint maar een estafette per staat. Hier staan de momenten die het kandidatenveld en de machtsstrijd vormgeven.';
$meta = [
    ['icon' => 'calendar', 'text' => 'Election Day 3 november'],
    ['icon' => 'check-circle', 'text' => 'Bronnen per gebeurtenis'],
];
require __DIR__ . '/partials/page-intro.php';
?>

<?php $active = 'voorverkiezingen'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--lg">
        <section class="mt-dashboard-section">
            <div class="mt-timeline-progress" data-mt-reveal>
                <div><span class="mt-panel-label">Campagnepad</span><strong><?= max(0, (int) $daysLeft) ?> dagen tot de stembus</strong></div>
                <span class="mt-timeline-progress__track"><i style="--progress:<?= max(0, min(100, 100 - (($daysLeft / 652) * 100))) ?>%"></i></span>
                <p>De tijdlijn start bij de inauguratie op 20 januari 2025 en eindigt bij de verkiezingen voor het 120e Congres.</p>
            </div>

            <?php if (!empty($timeline)): ?>
                <ol class="midterms-timeline midterms-timeline--full">
                    <?php foreach ($timeline as $event): ?>
                        <?php $isFuture = !empty($event->event_date) && strtotime($event->event_date) > time(); ?>
                        <li class="midterms-timeline__item midterms-timeline__item--full<?= $isFuture ? ' is-future' : ' is-past' ?>" data-mt-reveal>
                            <div class="midterms-timeline__rail"><span></span></div>
                            <div class="midterms-timeline__dateblock">
                                <time datetime="<?= pp_e($event->event_date ?? '') ?>"><?= pp_e(mt_date_nl($event->event_date ?? '')) ?></time>
                                <span class="midterms-timeline__cat midterms-timeline__cat--<?= pp_e($event->category ?? 'event') ?>"><?= pp_e($catLabels[$event->category ?? 'event'] ?? 'Gebeurtenis') ?></span>
                            </div>
                            <article class="midterms-timeline__body">
                                <h2 class="midterms-timeline__title"><?= pp_e($event->title_nl ?? '') ?></h2>
                                <p class="midterms-timeline__text"><?= pp_e($event->description_nl ?? '') ?></p>
                                <?php if (!empty($event->source_url)): ?>
                                    <a href="<?= pp_e($event->source_url) ?>" class="midterms-timeline__source" target="_blank" rel="noopener nofollow">Bekijk bron <?= pp_icon('arrow-up-right', 12) ?></a>
                                <?php endif; ?>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p class="midterms-empty">De tijdlijn is tijdelijk niet beschikbaar.</p>
            <?php endif; ?>
        </section>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
