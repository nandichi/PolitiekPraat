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

// News items voor "Vandaag in 5"
$topNews = array_slice($latest_news ?? [], 0, 5);

// Featured blogs voor secundaire grid (skip de hero blog als dezelfde)
$featuredForGrid = array_filter($featured_blogs ?? [], function ($b) use ($heroBlog) {
    return !$heroBlog || ($b->id ?? null) !== ($heroBlog->id ?? null);
});
$featuredForGrid = array_slice(array_values($featuredForGrid), 0, 3);

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
            'avatar' => !empty($heroBlog->author_photo) ? getProfilePhotoUrl($heroBlog->author_photo, $heroBlog->author_name ?? '')['url'] ?? null : null,
        ],
    ]) ?>
</section>
<?php endif; ?>

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
            'href'  => '/stemwijzer',
            'title' => 'Stemwijzer Ede 2026',
            'lead'  => 'Gemeentelijke stemwijzer met 25 stellingen en weging - speciaal voor de raadsverkiezingen.',
            'icon'  => 'vote',
            'tone'  => 'terracotta',
            'cta'   => 'Start stemwijzer',
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

<!-- Uitgelichte blogs -->
<?php if (!empty($featuredForGrid)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Uitgelicht',
        'title' => 'Lees verder',
        'cta_label' => 'Alle blogs',
        'cta_href'  => '/blogs',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($featuredForGrid as $blog):
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
                    'name' => $blog->author_name ?? 'Redactie',
                ],
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Actuele thema's -->
<?php if (!empty($actuele_themas)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Thema\'s',
        'title' => 'Wat speelt er in Nederland?',
        'cta_label' => 'Alle thema\'s',
        'cta_href'  => '/themas',
    ]) ?>
    <div class="flex flex-wrap gap-3">
        <?php
        $toneCycle = ['hague', 'terracotta', 'ochre', 'olive', 'moss', 'rose'];
        foreach (array_slice($actuele_themas, 0, 14) as $i => $thema):
            $label = is_array($thema) ? ($thema['naam'] ?? $thema['titel'] ?? '') : (string) $thema;
            if ($label === '') continue;
            $slug = is_array($thema) ? ($thema['slug'] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '-', $label))) : strtolower(preg_replace('/[^a-z0-9]+/i', '-', $label));
        ?>
            <?= pp_render_component('section/theme-chip', [
                'href'  => '/thema/' . trim($slug, '-'),
                'label' => $label,
                'tone'  => $toneCycle[$i % count($toneCycle)],
            ]) ?>
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

<!-- Laatste blogs (list-stijl) -->
<?php if (!empty($latest_blogs)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Recent gepubliceerd',
        'title' => 'Nieuwste blogs',
        'cta_label' => 'Alle blogs',
        'cta_href'  => '/blogs',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10">
        <?php foreach (array_slice($latest_blogs, 0, 6) as $blog):
            $img = !empty($blog->image_path) ? getBlogImageUrl($blog->image_path) : null;
        ?>
            <?= pp_render_component('article/article-card', [
                'variant'      => 'list',
                'href'         => '/blogs/' . ($blog->slug ?? ''),
                'title'        => $blog->title ?? '',
                'excerpt'      => substr(strip_tags($blog->summary ?? ''), 0, 110),
                'image'        => $img,
                'category'     => $blog->category_name ?? null,
                'date'         => pp_home_format_date($blog->published_at ?? null),
                'reading_time' => pp_reading_time($blog->content ?? null),
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

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
