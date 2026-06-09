<?php require_once 'views/templates/header.php'; ?>

<?php
$themaIcons = [
    'Klimaatbeleid' => 'leaf',
    'Woningmarkt'   => 'home',
    'Economie'      => 'trending-up',
    'Zorg'          => 'heart-pulse',
    'Onderwijs'     => 'graduation-cap',
    'Arbeidsmarkt'  => 'briefcase',
    'Immigratie'    => 'users',
    'Veiligheid'    => 'shield',
    'Duurzaamheid'  => 'recycle',
];
// Gebruik bij voorkeur het lucide-icoon uit de thema-data; val anders terug op
// de titelgebaseerde map en daarna op een algemeen icoon.
$explicitIcon = (isset($thema['icon']) && is_string($thema['icon']) && preg_match('/^[a-z0-9-]+$/', $thema['icon'])) ? $thema['icon'] : null;
$themaIcon = $explicitIcon ?? ($themaIcons[$thema['title']] ?? 'landmark');
$themaTitle = $thema['title'] ?? '';
$themaDescription = $thema['long_description'] ?? '';
$keyPoints = $thema['key_points'] ?? [];
$linksePartijen = is_array($linksePartijen ?? null) ? $linksePartijen : [];
$rechtsePartijen = is_array($rechtsePartijen ?? null) ? $rechtsePartijen : [];
$themaNews = is_array($themaNews ?? null) ? $themaNews : [];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Thema',
    'title'   => $themaTitle,
    'lead'    => $themaDescription,
    'meta'    => [
        ['icon' => $themaIcon, 'text' => $themaTitle],
        ['icon' => 'list-checks', 'text' => count($keyPoints) . ' kernpunten'],
        ['icon' => 'newspaper',   'text' => count($themaNews) . ' actuele berichten'],
    ],
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-10">
    <?php if (!empty($keyPoints)): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Kernpunten</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight">Waar het bij dit thema om draait</h2>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <?php foreach ($keyPoints as $point): ?>
                    <li class="flex items-start gap-3 p-4 border border-[color:var(--color-keyline)] rounded-md">
                        <span class="text-[color:var(--color-hague)] mt-0.5 flex-shrink-0"><?= pp_icon('check', 16) ?></span>
                        <span class="text-sm text-[color:var(--color-ink)]"><?= pp_e($point) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($linksePartijen) || !empty($rechtsePartijen)): ?>
        <div>
            <div class="eyebrow mb-3">Politieke perspectieven</div>
            <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Standpunten over <?= pp_e(mb_strtolower($themaTitle)) ?></h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="keyline-card p-6 md:p-8">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-[color:var(--color-keyline)]">
                        <div>
                            <div class="eyebrow mb-1">Progressief</div>
                            <h3 class="font-display text-display-md text-[color:var(--color-ink)] leading-tight">Linkse partijen</h3>
                        </div>
                        <span class="badge badge--hague"><?= count($linksePartijen) ?> partijen</span>
                    </div>
                    <?php if (empty($linksePartijen)): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Nog geen standpunten beschikbaar.</p>
                    <?php else: ?>
                        <dl class="space-y-5">
                            <?php foreach ($linksePartijen as $partij => $standpunt): ?>
                                <div class="border-l-2 border-[color:var(--color-hague)] pl-4">
                                    <dt class="font-display text-base text-[color:var(--color-ink)] mb-1"><?= pp_e($partij) ?></dt>
                                    <dd class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($standpunt) ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>
                </div>

                <div class="keyline-card p-6 md:p-8">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-[color:var(--color-keyline)]">
                        <div>
                            <div class="eyebrow mb-1">Conservatief</div>
                            <h3 class="font-display text-display-md text-[color:var(--color-ink)] leading-tight">Rechtse partijen</h3>
                        </div>
                        <span class="badge badge--terracotta"><?= count($rechtsePartijen) ?> partijen</span>
                    </div>
                    <?php if (empty($rechtsePartijen)): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Nog geen standpunten beschikbaar.</p>
                    <?php else: ?>
                        <dl class="space-y-5">
                            <?php foreach ($rechtsePartijen as $partij => $standpunt): ?>
                                <div class="border-l-2 border-[color:var(--color-terracotta)] pl-4">
                                    <dt class="font-display text-base text-[color:var(--color-ink)] mb-1"><?= pp_e($partij) ?></dt>
                                    <dd class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($standpunt) ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div>
        <div class="eyebrow mb-3">Actueel</div>
        <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Laatste berichten</h2>

        <?php if (empty($themaNews)): ?>
            <div class="keyline-card p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[color:var(--color-paper-2)] mb-4 text-[color:var(--color-ink-muted)]">
                    <?= pp_icon('newspaper', 22) ?>
                </div>
                <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-2 leading-tight">Geen nieuws gevonden</h3>
                <p class="text-sm text-[color:var(--color-ink-muted)]">Er zijn momenteel geen nieuwsartikelen beschikbaar over dit thema.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($themaNews as $news): ?>
                    <article class="keyline-card overflow-hidden flex flex-col">
                        <?php if (!empty($news['image'])): ?>
                            <a href="<?= pp_e($news['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="block relative aspect-[16/10] overflow-hidden bg-[color:var(--color-paper-2)]">
                                <img src="<?= pp_e($news['image']) ?>"
                                     alt="<?= pp_e($news['title'] ?? '') ?>"
                                     loading="lazy"
                                     class="w-full h-full object-cover">
                            </a>
                        <?php endif; ?>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 mb-3 text-xs text-[color:var(--color-ink-muted)]">
                                <span class="inline-flex items-center gap-1.5 text-[color:var(--color-hague)] font-medium uppercase tracking-wider">
                                    <?= pp_icon('newspaper', 12) ?>
                                    <?= pp_e($news['source'] ?? 'Bron') ?>
                                </span>
                                <?php if (!empty($news['publishedAt'])): ?>
                                    <span aria-hidden="true">&middot;</span>
                                    <time datetime="<?= pp_e($news['publishedAt']) ?>"><?= pp_e(date('d M Y', strtotime($news['publishedAt']))) ?></time>
                                <?php endif; ?>
                            </div>
                            <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-2 leading-tight">
                                <a href="<?= pp_e($news['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[color:var(--color-hague)] transition-colors">
                                    <?= pp_e($news['title'] ?? '') ?>
                                </a>
                            </h3>
                            <?php if (!empty($news['description'])): ?>
                                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-4 line-clamp-3 flex-1"><?= pp_e($news['description']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($news['url'])): ?>
                                <a href="<?= pp_e($news['url']) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 text-sm font-medium text-[color:var(--color-hague)] hover:underline mt-auto">
                                    Lees artikel
                                    <?= pp_icon('external-link', 14) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="keyline-card p-6 md:p-8 text-center">
        <div class="eyebrow mb-3">Verder lezen</div>
        <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-4 leading-tight">Vergelijk dit thema met andere</h2>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="/themas" class="btn btn--ghost">
                <?= pp_icon('arrow-left', 14) ?>
                Alle thema's
            </a>
            <a href="/partijmeter" class="btn btn--primary">
                <?= pp_icon('vote', 14) ?>
                Doe de PartijMeter
            </a>
        </div>
    </div>
</section>

<?php require_once 'views/templates/footer.php'; ?>
