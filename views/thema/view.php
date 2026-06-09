<?php require_once 'views/templates/header.php'; ?>

<?php
// ---------------------------------------------------------------------------
// Thema-detailpagina. Alle content komt uit includes/data/themas.php en de
// partijstandpunten uit includes/data/standpunten.php (via PoliticalParties).
// ---------------------------------------------------------------------------
$explicitIcon = (isset($thema['icon']) && is_string($thema['icon']) && preg_match('/^[a-z0-9-]+$/', $thema['icon'])) ? $thema['icon'] : null;
$themaIcon       = $explicitIcon ?? 'landmark';
$themaTitle      = $thema['title'] ?? '';
$category        = $thema['category'] ?? 'Thema';
$tagline         = $thema['tagline'] ?? '';
$intro           = $thema['intro'] ?? ($thema['description'] ?? '');
$heroImg         = $thema['hero'] ?? '';
$heroAlt         = $thema['hero_alt'] ?? $themaTitle;
$inHetKort       = is_array($thema['in_het_kort'] ?? null) ? $thema['in_het_kort'] : [];
$wat             = is_array($thema['wat'] ?? null) ? $thema['wat'] : [];
$waarom          = is_array($thema['waarom'] ?? null) ? $thema['waarom'] : [];
$cijfers         = is_array($thema['cijfers'] ?? null) ? $thema['cijfers'] : [];
$debat           = is_array($thema['debat'] ?? null) ? $thema['debat'] : [];
$begrippen       = is_array($thema['begrippen'] ?? null) ? $thema['begrippen'] : [];
$vragen          = is_array($thema['vragen'] ?? null) ? $thema['vragen'] : [];
$keyPoints       = is_array($thema['key_points'] ?? null) ? $thema['key_points'] : [];
$standpunten     = is_array($standpunten ?? null) ? $standpunten : [];
$gerelateerde    = is_array($gerelateerdeThemas ?? null) ? $gerelateerdeThemas : [];
$themaNews       = is_array($themaNews ?? null) ? $themaNews : [];

// Bestaat de herofoto al op schijf? Zo niet: gebruik een decoratieve fallback.
$heroFsPath = $heroImg !== '' ? dirname(__DIR__, 2) . $heroImg : '';
$heroExists = $heroFsPath !== '' && is_file($heroFsPath);
?>

<section class="bg-[color:var(--color-canvas-raised)] border-b border-[color:var(--color-keyline)]">
    <div class="pp-container pp-container--xl py-10 md:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            <div class="lg:col-span-7">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-[color:var(--color-hague-tint)] text-[color:var(--color-hague)]">
                        <?= pp_icon($themaIcon, 18) ?>
                    </span>
                    <span class="tag tag--hague"><?= pp_e($category) ?></span>
                </div>
                <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] leading-[1.05] mb-4"><?= pp_e($themaTitle) ?></h1>
                <?php if ($tagline !== ''): ?>
                    <p class="font-display text-display-md text-[color:var(--color-hague)] mb-4 leading-snug"><?= pp_e($tagline) ?></p>
                <?php endif; ?>
                <?php if ($intro !== ''): ?>
                    <p class="text-body-lg text-[color:var(--color-ink-muted)] leading-relaxed max-w-2xl"><?= pp_e($intro) ?></p>
                <?php endif; ?>
                <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-[color:var(--color-ink-muted)]">
                    <li class="inline-flex items-center gap-2"><span class="text-[color:var(--color-hague)]"><?= pp_icon('users', 15) ?></span><?= count($standpunten) ?> partijen</li>
                    <li class="inline-flex items-center gap-2"><span class="text-[color:var(--color-hague)]"><?= pp_icon('list-checks', 15) ?></span><?= count($keyPoints) ?> kernpunten</li>
                    <li class="inline-flex items-center gap-2"><span class="text-[color:var(--color-hague)]"><?= pp_icon('help-circle', 15) ?></span><?= count($vragen) ?> vragen &amp; antwoorden</li>
                </ul>
            </div>
            <div class="lg:col-span-5">
                <div class="keyline-card overflow-hidden aspect-[4/3] relative">
                    <?php if ($heroExists): ?>
                        <img src="<?= pp_e(pp_url($heroImg)) ?>" alt="<?= pp_e($heroAlt) ?>" class="w-full h-full object-cover" loading="eager">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[color:var(--color-hague-tint)] to-[color:var(--color-canvas-sunken)] text-[color:var(--color-hague)]">
                            <?= pp_icon($themaIcon, 72) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="pp-container pp-container--xl py-10 md:py-14 space-y-12 md:space-y-16">

    <?php if (!empty($inHetKort)): ?>
        <section aria-labelledby="in-het-kort" class="keyline-card p-6 md:p-8 bg-[color:var(--color-hague-tint)]">
            <div class="eyebrow mb-4" id="in-het-kort">In het kort</div>
            <ul class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php foreach ($inHetKort as $i => $point): ?>
                    <li class="flex items-start gap-3">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[color:var(--color-hague)] text-white font-display text-sm"><?= $i + 1 ?></span>
                        <span class="text-[color:var(--color-ink)] leading-relaxed"><?= pp_e($point) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($wat) || !empty($waarom)): ?>
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <?php if (!empty($wat)): ?>
                <div>
                    <div class="eyebrow mb-3">Uitleg</div>
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">Wat is dit thema?</h2>
                    <div class="space-y-4 text-body-lg text-[color:var(--color-ink-muted)] leading-relaxed">
                        <?php foreach ($wat as $p): ?><p><?= pp_e($p) ?></p><?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($waarom)): ?>
                <div>
                    <div class="eyebrow mb-3">Waarom het ertoe doet</div>
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">Waarom is dit belangrijk?</h2>
                    <div class="space-y-4 text-body-lg text-[color:var(--color-ink-muted)] leading-relaxed">
                        <?php foreach ($waarom as $p): ?><p><?= pp_e($p) ?></p><?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($cijfers)): ?>
        <section aria-labelledby="cijfers-kop">
            <div class="eyebrow mb-3">In cijfers</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="cijfers-kop">De feiten op een rij</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($cijfers as $c): ?>
                    <div class="keyline-card p-6">
                        <div class="font-display text-4xl text-[color:var(--color-hague)] leading-none mb-2"><?= pp_e($c['cijfer'] ?? '') ?></div>
                        <div class="text-[color:var(--color-ink)] leading-snug mb-3"><?= pp_e($c['label'] ?? '') ?></div>
                        <?php if (!empty($c['bron'])): ?>
                            <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Bron: <?= pp_e($c['bron']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($keyPoints)): ?>
        <section class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Kernpunten</div>
            <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-6 leading-tight">Waar het bij dit thema om draait</h2>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <?php foreach ($keyPoints as $point): ?>
                    <li class="flex items-start gap-3 p-4 border border-[color:var(--color-keyline)] rounded-md">
                        <span class="text-[color:var(--color-hague)] mt-0.5 flex-shrink-0"><?= pp_icon('check', 16) ?></span>
                        <span class="text-sm text-[color:var(--color-ink)]"><?= pp_e($point) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($debat)): ?>
        <section aria-labelledby="debat-kop">
            <div class="eyebrow mb-3">Het debat</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="debat-kop">Waar gaat de discussie over?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php foreach ($debat as $d): ?>
                    <div class="keyline-card p-6 border-t-2 border-[color:var(--color-terracotta)]">
                        <h3 class="font-display text-display-sm text-[color:var(--color-ink)] mb-2 leading-snug"><?= pp_e($d['kop'] ?? '') ?></h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($d['tekst'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($standpunten)): ?>
        <section aria-labelledby="standpunten-kop" data-party-standpunten>
            <div class="eyebrow mb-3">Politieke perspectieven</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight" id="standpunten-kop">Standpunten van de partijen</h2>
            <p class="text-sm text-[color:var(--color-ink-muted)] mb-6 max-w-3xl">Hoe denken de partijen in de Tweede Kamer over dit thema? Op volgorde van zetelaantal, op basis van de uitslag van de verkiezingen van 29 oktober 2025.</p>

            <div class="flex flex-wrap gap-2 mb-6" role="group" aria-label="Filter standpunten op politieke richting">
                <button type="button" class="pp-filter-chip is-active" data-filter="all" aria-pressed="true">Alle partijen</button>
                <button type="button" class="pp-filter-chip" data-filter="links" aria-pressed="false">Links</button>
                <button type="button" class="pp-filter-chip" data-filter="midden" aria-pressed="false">Midden</button>
                <button type="button" class="pp-filter-chip" data-filter="rechts" aria-pressed="false">Rechts</button>
                <button type="button" class="pp-filter-chip" data-filter="coalitie" aria-pressed="false">Coalitie</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach ($standpunten as $p): ?>
                    <?php
                        $pColor = $p['color'] ?? 'var(--color-hague)';
                        $logo = $p['logo_file'] ?? '';
                        $logoFs = $logo !== '' ? dirname(__DIR__, 2) . $logo : '';
                        $logoExists = $logoFs !== '' && is_file($logoFs);
                    ?>
                    <article class="keyline-card p-5 flex flex-col h-full border-l-[3px]"
                             style="border-left-color: <?= pp_e($pColor) ?>"
                             data-blok="<?= pp_e($p['blok'] ?? '') ?>"
                             data-coalitie="<?= !empty($p['coalitie']) ? '1' : '0' ?>">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-md bg-white border border-[color:var(--color-keyline)] p-1.5 overflow-hidden">
                                <?php if ($logoExists): ?>
                                    <img src="<?= pp_e(pp_url($logo)) ?>" alt="Logo <?= pp_e($p['name'] ?? '') ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <span class="font-display text-xs" style="color: <?= pp_e($pColor) ?>"><?= pp_e($p['name'] ?? '') ?></span>
                                <?php endif; ?>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="font-display text-base text-[color:var(--color-ink)] leading-tight truncate"><?= pp_e($p['name'] ?? '') ?></div>
                                <div class="text-[11px] font-semibold uppercase tracking-wider" style="color: <?= pp_e($pColor) ?>"><?= pp_e($p['spectrum'] ?? '') ?></div>
                            </div>
                            <span class="flex-shrink-0 text-xs font-medium text-[color:var(--color-ink-muted)] bg-[color:var(--color-canvas-sunken)] rounded-full px-2.5 py-1"><?= (int) ($p['seats'] ?? 0) ?> <?= ((int) ($p['seats'] ?? 0) === 1 ? 'zetel' : 'zetels') ?></span>
                        </div>
                        <?php if (!empty($p['coalitie'])): ?>
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-medium text-[color:var(--color-hague)] mb-2">
                                <?= pp_icon('check-circle', 13) ?> Regeringspartij (Kabinet-Jetten)
                            </div>
                        <?php endif; ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($p['standpunt'] ?? '') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="mt-5 text-xs text-[color:var(--color-ink-faint)] max-w-3xl">Dit zijn beknopte samenvattingen van de algemene richting van elke partij, bedoeld om snel een beeld te krijgen. Voor de exacte standpunten verwijzen we naar de verkiezingsprogramma's van de partijen.</p>
        </section>
    <?php endif; ?>

    <?php if (!empty($begrippen)): ?>
        <section aria-labelledby="begrippen-kop">
            <div class="eyebrow mb-3">Jargon uitgelegd</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="begrippen-kop">Belangrijke begrippen</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($begrippen as $b): ?>
                    <div class="keyline-card p-5">
                        <dt class="font-display text-base text-[color:var(--color-ink)] mb-1"><?= pp_e($b['term'] ?? '') ?></dt>
                        <dd class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($b['uitleg'] ?? '') ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>
    <?php endif; ?>

    <?php if (!empty($vragen)): ?>
        <section aria-labelledby="vragen-kop">
            <div class="eyebrow mb-3">Veelgestelde vragen</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="vragen-kop">Vragen en antwoorden</h2>
            <div class="space-y-3">
                <?php foreach ($vragen as $v): ?>
                    <details class="keyline-card group">
                        <summary class="flex items-center justify-between gap-4 cursor-pointer p-5 list-none">
                            <span class="font-display text-base text-[color:var(--color-ink)] leading-snug"><?= pp_e($v['vraag'] ?? '') ?></span>
                            <span class="flex-shrink-0 text-[color:var(--color-hague)] transition-transform group-open:rotate-180"><?= pp_icon('chevron-down', 18) ?></span>
                        </summary>
                        <div class="px-5 pb-5 -mt-1 text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($v['antwoord'] ?? '') ?></div>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section aria-labelledby="actueel-kop">
        <div class="eyebrow mb-3">Actueel</div>
        <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="actueel-kop">Laatste berichten</h2>
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
                                <img src="<?= pp_e($news['image']) ?>" alt="<?= pp_e($news['title'] ?? '') ?>" loading="lazy" class="w-full h-full object-cover">
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
                                <a href="<?= pp_e($news['url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-medium text-[color:var(--color-hague)] hover:underline mt-auto">
                                    Lees artikel <?= pp_icon('external-link', 14) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php if (!empty($gerelateerde)): ?>
        <section aria-labelledby="gerelateerd-kop">
            <div class="eyebrow mb-3">Verder lezen</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight" id="gerelateerd-kop">Gerelateerde thema's</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php foreach ($gerelateerde as $relSlug => $rel): ?>
                    <a href="<?= pp_e(pp_url('/thema/' . $relSlug)) ?>" class="keyline-card p-6 flex items-start gap-4 group">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-md bg-[color:var(--color-hague-tint)] text-[color:var(--color-hague)]">
                            <?= pp_icon($rel['icon'] ?? 'landmark', 20) ?>
                        </span>
                        <span class="min-w-0">
                            <span class="block font-display text-display-sm text-[color:var(--color-ink)] leading-snug group-hover:text-[color:var(--color-hague)] transition-colors"><?= pp_e($rel['title'] ?? '') ?></span>
                            <span class="block text-sm text-[color:var(--color-ink-muted)] mt-1 line-clamp-2"><?= pp_e($rel['description'] ?? '') ?></span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="keyline-card p-6 md:p-8 text-center">
        <div class="eyebrow mb-3">Zelf aan zet</div>
        <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-4 leading-tight">Benieuwd welke partij bij jou past?</h2>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="<?= pp_e(pp_url('/themas')) ?>" class="btn btn--ghost">
                <?= pp_icon('arrow-left', 14) ?> Alle thema's
            </a>
            <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary">
                <?= pp_icon('vote', 14) ?> Doe de PartijMeter
            </a>
        </div>
    </section>
</div>

<script>
(function () {
    var root = document.querySelector('[data-party-standpunten]');
    if (!root) return;
    var chips = root.querySelectorAll('.pp-filter-chip');
    var cards = root.querySelectorAll('[data-blok]');
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            var filter = chip.getAttribute('data-filter');
            chips.forEach(function (c) {
                var active = c === chip;
                c.classList.toggle('is-active', active);
                c.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
            cards.forEach(function (card) {
                var show = filter === 'all'
                    || (filter === 'coalitie' && card.getAttribute('data-coalitie') === '1')
                    || (card.getAttribute('data-blok') === filter);
                card.style.display = show ? '' : 'none';
            });
        });
    });
})();
</script>

<?php require_once 'views/templates/footer.php'; ?>
