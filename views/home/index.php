<?php
/**
 * Editorial homepage (Wave 2).
 *
 * Beschikbare data (gedeclareerd in controllers/home.php):
 *   $latestBlog                 - object|null  (nieuwste blog)
 *   $latest_blogs               - array<object>
 *   $featured_blogs             - array<object>
 *   $latest_news                - array<array>  {title, description, url, source, publishedAt, ...}
 *   $actuele_themas             - array
 *   $debatten, $agenda_items    - array
 *   $kamerStats, $coalitieStatus, $partijData - data
 *   $latestPolls, $historicalPolls, $peilingData - polling data
 *   $amerikaanse_verkiezingen, $nederlandse_verkiezingen - object[]
 *   $latest_election_year, $next_election_year - int
 *   $resolveNewsLogo            - Closure(string $source): ?string
 */

require_once 'views/templates/header.php';

$heroBlog = $latestBlog ?? ($latest_blogs[0] ?? null);
$heroImage = null;
if ($heroBlog && !empty($heroBlog->image_path)) {
    $heroImage = getBlogImageUrl($heroBlog->image_path);
}

// Resolve auteur-profielfoto naar een echte URL (anders valt de byline terug op initialen).
// getProfilePhotoUrl() geeft de URL terug onder ['value'] met ['type'] = img|initial.
$ppAuthorAvatar = static function ($photo, $name) {
    if (empty($photo)) {
        return null;
    }
    $resolved = getProfilePhotoUrl($photo, (string) $name);
    return (($resolved['type'] ?? '') === 'img') ? ($resolved['value'] ?? null) : null;
};

// Nieuwste blogs zonder de hero-blog, zodat die niet direct onder de hero wordt herhaald.
$latestForList = array_values(array_filter($latest_blogs ?? [], function ($b) use ($heroBlog) {
    return !$heroBlog || ($b->id ?? null) !== ($heroBlog->id ?? null);
}));

// News items voor "Vandaag in 5"
$topNews = array_slice($latest_news ?? [], 0, 5);

// Helpers
function pp_home_news_time(?string $isoTime): string {
    if (empty($isoTime)) return '';
    $ts = strtotime($isoTime);
    if ($ts === false) return '';
    $diff = max(1, time() - $ts);
    if ($diff < 60) return $diff . ' sec geleden';
    if ($diff < 3600) return floor($diff / 60) . ' min geleden';
    if ($diff < 86400) return floor($diff / 3600) . ' uur geleden';
    if ($diff < 604800) return floor($diff / 86400) . ' dagen geleden';
    return date('j F', $ts);
}
function pp_home_format_date($value): string {
    if (empty($value)) return '';
    $ts = is_int($value) ? $value : strtotime((string) $value);
    if (!$ts) return '';
    $months = [1=>'januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
    return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

// Bepaalt de leidende uitkomst (hoogste kans) van een Polymarket-markt.
$ppOddsLeader = static function ($odd): ?array {
    if (!$odd) return null;
    $outcomes = is_object($odd) ? ($odd->outcomes ?? []) : ($odd['outcomes'] ?? []);
    if (!is_array($outcomes) || empty($outcomes)) return null;
    $best = null;
    foreach ($outcomes as $o) {
        $price = is_array($o) ? ($o['price'] ?? null) : ($o->price ?? null);
        $label = is_array($o) ? ($o['label_nl'] ?? ($o['label'] ?? null)) : ($o->label_nl ?? null);
        if ($price === null) continue;
        $price = (float) $price;
        if ($best === null || $price > $best['price']) {
            $best = ['label' => (string) $label, 'price' => $price];
        }
    }
    return $best;
};

// Partijkleur (VS) op basis van het label.
$ppUsaPartyTone = static function ($label): array {
    $l = mb_strtolower((string) $label);
    if (strpos($l, 'republik') !== false) return ['name' => 'Republikeinen', 'color' => '#b31942'];
    if (strpos($l, 'democr') !== false)   return ['name' => 'Democraten',    'color' => '#3b6fd0'];
    return ['name' => (string) $label, 'color' => '#9aa7b4'];
};
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Welkom bij PolitiekPraat',
    'title'   => 'Heldere politiek, gewone taal.',
    'lead'    => 'Onafhankelijke analyses, stemhulpen en open gesprekken over Nederlandse politiek van Den Haag tot je eigen gemeente.',
]) ?>

<?php if ($heroBlog): ?>
<section class="pp-container pp-container--xl">
    <?= pp_render_component('article/article-hero', [
        'href'         => '/blogs/' . ($heroBlog->slug ?? ''),
        'title'        => $heroBlog->title ?? '',
        'lead'         => substr(strip_tags($heroBlog->summary ?? ''), 0, 220),
        'image'        => $heroImage,
        'image_alt'    => $heroBlog->title ?? '',
        'category'     => $heroBlog->category_name ?? 'Hoofdartikel',
        'date'         => pp_home_format_date($heroBlog->published_at ?? null),
        'reading_time' => pp_reading_time($heroBlog->content ?? null),
        'author'       => [
            'name'   => $heroBlog->author_name ?? 'Redactie',
            'avatar' => $ppAuthorAvatar($heroBlog->author_photo ?? null, $heroBlog->author_name ?? ''),
        ],
    ]) ?>
</section>
<?php endif; ?>

<!-- Nieuwste blogs (blogs staan centraal: direct onder de hero) -->
<?php if (!empty($latestForList)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Recent gepubliceerd',
        'title' => 'Nieuwste blogs',
        'cta_label' => 'Alle blogs',
        'cta_href'  => '/blogs',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach (array_slice($latestForList, 0, 6) as $blog):
            $img = !empty($blog->image_path) ? getBlogImageUrl($blog->image_path) : null;
        ?>
            <?= pp_render_component('article/article-card', [
                'href'         => '/blogs/' . ($blog->slug ?? ''),
                'title'        => $blog->title ?? '',
                'excerpt'      => substr(strip_tags($blog->summary ?? ''), 0, 140),
                'image'        => $img,
                'category'     => $blog->category_name ?? null,
                'date'         => pp_home_format_date($blog->published_at ?? null),
                'reading_time' => pp_reading_time($blog->content ?? null),
                'author'       => [
                    'name'   => $blog->author_name ?? 'Redactie',
                    'avatar' => $ppAuthorAvatar($blog->author_photo ?? null, $blog->author_name ?? ''),
                ],
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Midterms 2026 feature band -->
<section class="pp-usa-band mt-20" aria-labelledby="pp-midterms-heading">
    <span class="pp-usa-band__stripes" aria-hidden="true"></span>
    <div class="pp-container pp-container--xl">
        <div class="pp-usa-band__grid">
            <div class="pp-usa-band__intro">
                <span class="pp-usa-band__eyebrow">
                    <span class="pp-usa-band__eyebrow-icon" aria-hidden="true"><?= pp_icon('flag', 14) ?></span>
                    Verenigde Staten &middot; Tussentijdse verkiezingen
                </span>
                <h2 id="pp-midterms-heading" class="pp-usa-band__title">Midterms 2026</h2>
                <p class="pp-usa-band__lead">
                    Wie krijgt de macht in de Senaat en het Huis? Volg de races, live voorspellingen
                    en interactieve kaarten van de Amerikaanse tussentijdse verkiezingen, in het Nederlands.
                </p>

                <?php if ($midtermsDaysLeft !== null && $midtermsDaysLeft >= 0): ?>
                <div class="pp-usa-band__countdown">
                    <span class="pp-usa-band__count-num"><?= (int) $midtermsDaysLeft ?></span>
                    <span class="pp-usa-band__count-label">dagen tot Election Day<br><strong>3 november 2026</strong></span>
                </div>
                <?php endif; ?>

                <div class="pp-usa-band__cta">
                    <a href="<?= pp_e(pp_url('/midterms-2026')) ?>" class="pp-usa-btn">
                        Bekijk de live kaarten
                        <?= pp_icon('arrow-right', 16) ?>
                    </a>
                    <a href="<?= pp_e(pp_url('/midterms-2026/uitleg')) ?>" class="pp-usa-band__textlink">Hoe werken de midterms?</a>
                </div>
            </div>

            <div class="pp-usa-band__data">
                <?php
                $ppChambers = [
                    'senate_control' => 'Senaat',
                    'house_control'  => 'Huis van Afgevaardigden',
                ];
                $ppHasOdds = false;
                foreach ($ppChambers as $oddKey => $chamberLabel):
                    $odd = $midtermsOdds[$oddKey] ?? null;
                    $leader = $ppOddsLeader($odd);
                    if (!$leader) continue;
                    $ppHasOdds = true;
                    $tone = $ppUsaPartyTone($leader['label']);
                    $pct = (int) round($leader['price'] * 100);
                ?>
                    <div class="pp-odds-card">
                        <div class="pp-odds-card__top">
                            <span class="pp-odds-card__chamber"><?= pp_e($chamberLabel) ?></span>
                            <span class="pp-odds-card__pct"><?= $pct ?>%</span>
                        </div>
                        <div class="pp-odds-card__bar">
                            <span style="width: <?= $pct ?>%; background-color: <?= pp_e($tone['color']) ?>;"></span>
                        </div>
                        <div class="pp-odds-card__meta"><?= pp_e($tone['name']) ?> aan kop &middot; bron Polymarket</div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$ppHasOdds): ?>
                    <div class="pp-odds-card">
                        <div class="pp-odds-card__top">
                            <span class="pp-odds-card__chamber">Senaat &amp; Huis</span>
                        </div>
                        <div class="pp-odds-card__meta">Live voorspellingen, kaarten en races op de midterms-pagina.</div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($midtermsTopRaces)): ?>
                <div class="pp-usa-band__races">
                    <span class="pp-usa-band__races-label">Spannendste races</span>
                    <div class="pp-usa-band__chips">
                        <?php
                        $ppOfficeLabels = ['senate' => 'Senaat', 'house' => 'Huis', 'governor' => 'Gouverneur'];
                        foreach (array_slice($midtermsTopRaces, 0, 5) as $race):
                            $ratingKey = is_object($race) ? ($race->rating ?? '') : ($race['rating'] ?? '');
                            $stateName = is_object($race) ? ($race->state_name ?? '') : ($race['state_name'] ?? '');
                            $chamber   = is_object($race) ? ($race->chamber ?? '') : ($race['chamber'] ?? '');
                            $district  = is_object($race) ? ($race->district ?? '') : ($race['district'] ?? '');
                            $ratingShort = $midtermsRatingMeta[$ratingKey]['short'] ?? '';
                            if ($stateName === '') continue;
                            $office = $ppOfficeLabels[$chamber] ?? '';
                            if ($chamber === 'house' && (string) $district !== '') {
                                $office = 'Huis ' . $district;
                            }
                        ?>
                            <span class="pp-race-chip">
                                <span class="pp-race-chip__state"><?= pp_e($stateName) ?></span>
                                <?php if ($office !== ''): ?><span class="pp-race-chip__office"><?= pp_e($office) ?></span><?php endif; ?>
                                <?php if ($ratingShort !== ''): ?><span class="pp-race-chip__rating"><?= pp_e($ratingShort) ?></span><?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Vandaag in vijf + featured -->
<section class="pp-container pp-container--xl mt-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-14">
        <div class="lg:col-span-2">
            <?= pp_render_component('section/section-header', [
                'label' => 'Vandaag',
                'title' => 'Vandaag in vijf',
                'cta_label' => 'Alle nieuws',
                'cta_href'  => '/nieuws',
            ]) ?>
            <div class="flex flex-col">
                <?php if (!empty($topNews)): foreach ($topNews as $i => $news): ?>
                    <?= pp_render_component('article/article-list-item', [
                        'index'  => $i + 1,
                        'href'   => $news['url'] ?? '/nieuws',
                        'title'  => $news['title'] ?? '',
                        'lead'   => $news['description'] ?? null,
                        'source' => $news['source'] ?? null,
                        'time'   => pp_home_news_time($news['publishedAt'] ?? null),
                    ]) ?>
                <?php endforeach; else: ?>
                    <p class="text-[color:var(--color-ink-faint)]">Geen nieuws beschikbaar.</p>
                <?php endif; ?>
            </div>
        </div>

        <aside class="space-y-6">
            <?= pp_render_component('section/section-header', [
                'label' => 'Volg ons',
                'title' => 'Nieuwsbrief',
            ]) ?>
            <?= pp_render_component('forms/newsletter-inline', [
                'title' => 'Elke zondag in je inbox',
                'lead'  => 'Een rustige weekbrief: belangrijkste politieke ontwikkelingen, nieuwe blogs en handige stemhulpen. Geen ruis, geen reclame.',
            ]) ?>
        </aside>
    </div>
</section>

<!-- Stemhulpen -->
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Stemhulpen',
        'title' => 'Maak een geïnformeerde keuze',
        'cta_label' => 'Alle tools',
        'cta_href'  => '/partijmeter',
    ]) ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?= pp_render_component('tool/tool-card', [
            'href'  => '/partijmeter',
            'title' => 'PartijMeter',
            'lead'  => 'Vergelijk in vijf minuten 30 standpunten met alle Nederlandse partijen en zie welke het beste bij jou past.',
            'icon'  => 'scale',
            'tone'  => 'hague',
            'cta'   => 'Start de test',
        ]) ?>
        <?= pp_render_component('tool/tool-card', [
            'href'  => '/politiek-kompas',
            'title' => 'Politiek Kompas',
            'lead'  => 'Twee-assen analyse op economie en samenleving. Ontdek je positie op het politieke spectrum.',
            'icon'  => 'pie-chart',
            'tone'  => 'olive',
            'cta'   => 'Open kompas',
        ]) ?>
        <?= pp_render_component('tool/tool-card', [
            'href'  => '/midterms-2026',
            'title' => 'Midterms 2026',
            'lead'  => 'Volg de Amerikaanse tussentijdse verkiezingen: live odds, interactieve kaarten en uitleg in het Nederlands.',
            'icon'  => 'flag',
            'tone'  => 'terracotta',
            'cta'   => 'Bekijk midterms',
        ]) ?>
        <?= pp_render_component('tool/tool-card', [
            'href'  => '/stemmentracker',
            'title' => 'Stemmentracker',
            'lead'  => 'Volg per motie hoe iedere partij stemt in de Tweede Kamer - transparant en feitelijk.',
            'icon'  => 'bar-chart-3',
            'tone'  => 'moss',
            'cta'   => 'Bekijk stemmen',
        ]) ?>
    </div>
</section>

<!-- Thema's -->
<?php if (!empty($home_themas)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => "Thema's",
        'title' => 'Politiek per onderwerp',
        'cta_label' => "Alle thema's",
        'cta_href'  => '/themas',
    ]) ?>
    <div class="pp-theme-grid">
        <?php foreach ($home_themas as $themaSlug => $thema): ?>
            <a href="<?= pp_e(pp_url('/thema/' . $themaSlug)) ?>" class="pp-theme-link">
                <span class="pp-theme-link__icon" aria-hidden="true"><?= pp_icon($thema['icon'] ?? 'tag', 18) ?></span>
                <span class="pp-theme-link__label"><?= pp_e($thema['title'] ?? $themaSlug) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Partijen -->
<?php if (!empty($home_parties)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Partijen',
        'title' => 'Alle partijen op een rij',
        'cta_label' => 'Naar partijen',
        'cta_href'  => '/partijen',
    ]) ?>
    <div class="pp-party-wall">
        <?php foreach (array_slice($home_parties, 0, 18) as $party): ?>
            <a href="<?= pp_e(pp_url('/partijen/' . $party['slug'])) ?>" class="pp-party-tile" title="<?= pp_e($party['name']) ?>">
                <span class="pp-party-tile__logo">
                    <img src="<?= pp_e($party['logo']) ?>" alt="Logo <?= pp_e($party['name']) ?>" loading="lazy" decoding="async">
                </span>
                <span class="pp-party-tile__name"><?= pp_e($party['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Verkiezingen overzicht -->
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Geschiedenis',
        'title' => 'Verkiezingen door de jaren heen',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="<?= pp_e(pp_url('/nederlandse-verkiezingen')) ?>"
           class="keyline-card p-8 group transition-colors">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full"
                      style="background-color: var(--color-hague-tint); color: var(--color-hague);">
                    <?= pp_icon('landmark', 20) ?>
                </span>
                <span class="text-tag uppercase tracking-wider text-[color:var(--color-ink-faint)] font-medium">Nederland</span>
            </div>
            <h3 class="font-display text-2xl text-[color:var(--color-ink)] mb-2 group-hover:text-[color:var(--color-hague)] transition-colors">
                175 jaar Nederlandse democratie
            </h3>
            <p class="text-[color:var(--color-ink-muted)] mb-4 leading-relaxed">
                Van Thorbeckes grondwet (1848) tot het huidige coalitiekabinet - alle verkiezingen, ministers-presidenten en politieke verschuivingen op één plek.
            </p>
            <span class="text-[color:var(--color-hague)] text-sm font-medium inline-flex items-center gap-1.5 group-hover:text-[color:var(--color-terracotta)] transition-colors">
                Verken de tijdlijn <?= pp_icon('arrow-right', 14) ?>
            </span>
        </a>
        <a href="<?= pp_e(pp_url('/amerikaanse-verkiezingen')) ?>"
           class="keyline-card p-8 group transition-colors">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full"
                      style="background-color: var(--color-terracotta-tint); color: var(--color-terracotta);">
                    <?= pp_icon('flag', 20) ?>
                </span>
                <span class="text-tag uppercase tracking-wider text-[color:var(--color-ink-faint)] font-medium">Verenigde Staten</span>
            </div>
            <h3 class="font-display text-2xl text-[color:var(--color-ink)] mb-2 group-hover:text-[color:var(--color-hague)] transition-colors">
                Van Washington tot Biden
            </h3>
            <p class="text-[color:var(--color-ink-muted)] mb-4 leading-relaxed">
                Een complete geschiedenis van Amerikaanse presidentsverkiezingen sinds 1789 - inclusief kiesmannen, populaire stem en kantelmomenten in de Amerikaanse politiek.
            </p>
            <span class="text-[color:var(--color-hague)] text-sm font-medium inline-flex items-center gap-1.5 group-hover:text-[color:var(--color-terracotta)] transition-colors">
                Bekijk alle verkiezingen <?= pp_icon('arrow-right', 14) ?>
            </span>
        </a>
    </div>
</section>

<!-- Steun ons CTA -->
<section class="pp-container pp-container--xl mt-20">
    <div class="keyline-card p-10 md:p-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="max-w-2xl">
            <span class="text-tag uppercase tracking-wider text-[color:var(--color-terracotta)] font-semibold">Onafhankelijk</span>
            <h3 class="font-display text-3xl text-[color:var(--color-ink)] mt-2 mb-3">PolitiekPraat is gratis en advertentievrij.</h3>
            <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                We worden uitsluitend gedragen door lezers. Geen reclame, geen tracking-deals, geen partijbelangen. Help ons om onafhankelijk te blijven met een eenmalige of maandelijkse bijdrage.
            </p>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <a href="<?= pp_e(pp_url('/donatie')) ?>" class="btn btn--terracotta">
                <?= pp_icon('coffee', 18) ?>
                <span>Steun ons</span>
            </a>
            <a href="<?= pp_e(pp_url('/over-mij')) ?>" class="btn btn--ghost">Lees waarom</a>
        </div>
    </div>
</section>

<?php require_once 'views/templates/footer.php'; ?>
