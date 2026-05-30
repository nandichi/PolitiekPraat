<?php require_once 'views/templates/header.php'; ?>

<?php
/** @var array $timeline */

$catLabels = [
    'primary' => 'Voorverkiezing',
    'event' => 'Gebeurtenis',
    'deadline' => 'Deadline',
    'news' => 'Nieuws',
    'debate' => 'Debat',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Midterms 2026 - Tijdlijn',
    'title' => 'Voorverkiezingen en sleutelmomenten',
    'lead' => 'Voordat Amerikanen op 3 november stemmen, kiezen de partijen in elke staat eerst hun kandidaten. Hieronder de belangrijkste momenten op weg naar de midterms.',
    'meta' => [
        ['icon' => 'calendar', 'text' => 'Bijgewerkt richting november 2026'],
    ],
]) ?>

<?php $active = 'voorverkiezingen'; require __DIR__ . '/partials/section-nav.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--lg">
        <?php if (!empty($timeline)): ?>
            <ol class="midterms-timeline">
                <?php foreach ($timeline as $event): ?>
                    <li class="midterms-timeline__item midterms-timeline__item--full">
                        <div class="midterms-timeline__marker" aria-hidden="true"></div>
                        <div class="midterms-timeline__body">
                            <div class="midterms-timeline__head">
                                <span class="midterms-timeline__date"><?= pp_e(mt_date_nl($event->event_date ?? '')) ?></span>
                                <span class="midterms-timeline__cat midterms-timeline__cat--<?= pp_e($event->category ?? 'event') ?>">
                                    <?= pp_e($catLabels[$event->category ?? 'event'] ?? 'Gebeurtenis') ?>
                                </span>
                            </div>
                            <h2 class="midterms-timeline__title"><?= pp_e($event->title_nl ?? '') ?></h2>
                            <?php if (!empty($event->description_nl)): ?>
                                <p class="midterms-timeline__text"><?= pp_e($event->description_nl) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($event->source_url)): ?>
                                <a href="<?= pp_e($event->source_url) ?>" class="midterms-timeline__source" target="_blank" rel="noopener nofollow">
                                    Bron <?= pp_icon('external-link', 12) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p class="midterms-empty">De tijdlijn wordt binnenkort gevuld met de belangrijkste voorverkiezingen en gebeurtenissen.</p>
        <?php endif; ?>

        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
