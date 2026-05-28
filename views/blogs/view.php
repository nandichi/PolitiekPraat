<?php
/**
 * Editorial blog detail view (Wave 2).
 *
 * Editorial blog detail (SEO meta, polls, likes). Geen reactiesectie.
 */
require_once __DIR__ . '/../../models/PartyModel.php';

$partyModel = new PartyModel();
$dbParties = $partyModel->getAllParties();

$blogUrl = rtrim(URLROOT, '/') . '/blogs/' . $blog->slug;
$pageTitle = !empty($blog->seo_title) ? (string) $blog->seo_title : (string) $blog->title;
$pageDescription = !empty($blog->seo_description)
    ? (string) $blog->seo_description
    : htmlspecialchars(stripMarkdownForSocialMedia($blog->summary, 160), ENT_QUOTES, 'UTF-8');

if ($blog->image_path) {
    $pageImage = getBlogImageUrl($blog->image_path);
} else {
    $pageImage = rtrim(URLROOT, '/') . '/metadata-foto.png';
}

$publishedTs = strtotime((string) $blog->published_at) ?: time();
$modifiedTs = strtotime((string) ($blog->updated_at ?? $blog->published_at)) ?: $publishedTs;

$data = [
    'title' => $pageTitle,
    'headline' => (string) $blog->title,
    'description' => $pageDescription,
    'keywords' => $blog->meta_keywords ?? null,
    'meta_robots' => !empty($blog->meta_robots) ? (string) $blog->meta_robots : null,
    'canonical_url' => !empty($blog->canonical_url) ? (string) $blog->canonical_url : $blogUrl,
    'image' => $pageImage,
    'og_type' => 'article',
    'og_url' => $blogUrl,
    'is_blog' => true,
    'author' => $blog->author_name,
    'created_at' => $blog->published_at,
    'updated_at' => $blog->updated_at ?? $blog->published_at,
    'article_author' => $blog->author_name,
    'article_published_time' => date('c', $publishedTs),
    'article_modified_time' => date('c', $modifiedTs),
    'article_section' => $blog->category_name ?? null,
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => rtrim(URLROOT, '/') . '/'],
        ['name' => 'Blogs', 'url' => rtrim(URLROOT, '/') . '/blogs'],
        ['name' => (string) $blog->title, 'url' => $blogUrl],
    ],
];

if (!function_exists('pp_blog_detail_date')) {
    function pp_blog_detail_date($value): string
    {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        $months = [1=>'januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$readingTime = pp_reading_time($blog->content ?? '');
$profilePhotoData = getProfilePhotoUrl($blog->author_photo ?? null, $blog->author_name);
$videoEmbed = null;
if (!empty($blog->video_url)) {
    $videoEmbed = $blog->video_url;
}

require_once 'views/templates/header.php';
?>

<div id="reading-progress"
     class="fixed top-0 left-0 h-[2px] bg-[color:var(--color-terracotta)] z-50 transition-[width] duration-300 ease-out"
     style="width: 0%"></div>

<article class="bg-[color:var(--color-canvas)]">
    <header class="pp-container pp-container--md pt-12 md:pt-16">
        <?php if (!empty($blog->category_name)): ?>
            <div class="mb-6">
                <a href="<?= pp_e(pp_url('/blogs?category=' . urlencode($blog->category_slug ?? ''))) ?>"
                   class="tag tag--<?= pp_e(pp_category_tone($blog->category_name)) ?>">
                    <?= pp_e($blog->category_name) ?>
                </a>
            </div>
        <?php endif; ?>

        <h1 class="font-display text-display-md md:text-display-lg leading-[1.05] tracking-tight text-[color:var(--color-ink)] mb-6">
            <?= pp_e($blog->title) ?>
        </h1>

        <?php if (!empty($blog->summary)): ?>
            <p class="font-display text-xl md:text-2xl leading-snug text-[color:var(--color-ink-muted)] mb-8 max-w-2xl">
                <?= pp_e(strip_tags($blog->summary)) ?>
            </p>
        <?php endif; ?>

        <div class="flex items-center justify-between flex-wrap gap-6 pt-6 border-t border-[color:var(--color-keyline)]">
            <div class="flex items-center gap-4">
                <?php if ($profilePhotoData['type'] === 'img'): ?>
                    <img src="<?= pp_e($profilePhotoData['value']) ?>"
                         alt="<?= pp_e($blog->author_name) ?>"
                         class="w-12 h-12 rounded-full object-cover border border-[color:var(--color-keyline)]">
                <?php else: ?>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[color:var(--color-paper-2)] text-[color:var(--color-hague)] font-display font-semibold text-base border border-[color:var(--color-keyline)]">
                        <?= pp_e($profilePhotoData['value']) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <p class="font-display text-base text-[color:var(--color-ink)] leading-tight">
                        <?= pp_e($blog->author_name) ?>
                    </p>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mt-0.5">
                        <?= pp_e(pp_blog_detail_date($blog->published_at)) ?> &middot; <span id="reading-minutes"><?= (int) $readingTime ?></span> min lezen
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button id="heroLikeButton"
                        class="btn btn--ghost btn--sm"
                        data-slug="<?= pp_e($blog->slug) ?>"
                        aria-label="Vind dit artikel waardevol">
                    <?= pp_icon('heart', 16) ?>
                    <span id="hero-like-count" class="font-mono text-tabular text-sm">
                        <?= (int) ($blog->likes ?? 0) ?>
                    </span>
                </button>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-[color:var(--color-keyline)]">
            <?= pp_render_component('blog/share-bar', [
                'blog' => $blog,
                'shareUrl' => $blogUrl,
                'layout' => 'inline',
            ]) ?>
        </div>
    </header>

    <?php if (!empty($blog->image_path)): ?>
        <figure class="pp-container pp-container--xl mt-12 md:mt-16">
            <div class="editorial-frame">
                <img src="<?= pp_e(getBlogImageUrl($blog->image_path)) ?>"
                     alt="<?= pp_e($blog->title) ?>"
                     loading="eager">
            </div>
        </figure>
    <?php endif; ?>

    <?php if (!empty($blog->video_path) || !empty($blog->video_url)): ?>
        <div class="pp-container pp-container--md mt-12">
            <div class="keyline-card overflow-hidden">
                <?php if (!empty($blog->video_path)): ?>
                    <video controls
                           class="w-full aspect-video bg-black"
                           <?php if (!empty($blog->image_path)): ?>poster="<?= pp_e(getBlogImageUrl($blog->image_path)) ?>"<?php endif; ?>>
                        <source src="<?= pp_e(getBlogVideoUrl($blog->video_path)) ?>" type="video/mp4">
                        Je browser ondersteunt geen video.
                    </video>
                <?php elseif (!empty($blog->video_url)): ?>
                    <div class="aspect-video">
                        <iframe src="<?= pp_e($blog->video_url) ?>"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <section class="pp-container pp-container--md mt-12 md:mt-16">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_240px] gap-12">
            <div id="blog-content" class="prose-editorial">
                <?= $blog->content ?>
            </div>

            <aside class="hidden lg:block">
                <div class="sticky top-28 space-y-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                            Auteur
                        </div>
                        <div class="flex items-start gap-3">
                            <?php if ($profilePhotoData['type'] === 'img'): ?>
                                <img src="<?= pp_e($profilePhotoData['value']) ?>"
                                     alt="<?= pp_e($blog->author_name) ?>"
                                     class="w-10 h-10 rounded-full object-cover border border-[color:var(--color-keyline)] flex-shrink-0">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[color:var(--color-paper-2)] text-[color:var(--color-hague)] font-display font-semibold text-sm border border-[color:var(--color-keyline)] flex-shrink-0">
                                    <?= pp_e($profilePhotoData['value']) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-display text-base text-[color:var(--color-ink)] leading-tight">
                                    <?= pp_e($blog->author_name) ?>
                                </p>
                                <p class="text-sm text-[color:var(--color-ink-muted)] mt-0.5">
                                    Auteur
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($blog->category_name)): ?>
                        <div>
                            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                                Onderwerp
                            </div>
                            <a href="<?= pp_e(pp_url('/blogs?category=' . urlencode($blog->category_slug ?? ''))) ?>"
                               class="tag tag--<?= pp_e(pp_category_tone($blog->category_name)) ?>">
                                <?= pp_e($blog->category_name) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </section>

    <?php if (!empty($blog->poll)): ?>
        <section class="pp-container pp-container--md mt-20">
            <div class="keyline-card p-8 md:p-10">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                    Peiling
                </div>
                <h3 class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)] mb-2">
                    <?= pp_e($blog->poll->question) ?>
                </h3>
                <?php if ($blog->poll->total_votes > 0): ?>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mb-6">
                        <?= (int) $blog->poll->total_votes ?>
                        <?= $blog->poll->total_votes === 1 ? 'persoon heeft gestemd' : 'mensen hebben gestemd' ?>
                    </p>
                <?php else: ?>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mb-6">Stem mee in deze peiling.</p>
                <?php endif; ?>

                <div id="pollContainer" data-poll-id="<?= (int) $blog->poll->id ?>">
                    <?php if ($blog->poll->user_has_voted): ?>
                        <?php
                        $options = [
                            ['letter' => 'A', 'label' => $blog->poll->option_a, 'pct' => $blog->poll->option_a_percentage, 'chosen' => $blog->poll->user_choice === 'A'],
                            ['letter' => 'B', 'label' => $blog->poll->option_b, 'pct' => $blog->poll->option_b_percentage, 'chosen' => $blog->poll->user_choice === 'B'],
                        ];
                        foreach ($options as $opt):
                        ?>
                            <div class="mb-3">
                                <div class="flex items-baseline justify-between mb-1.5">
                                    <span class="font-display text-base text-[color:var(--color-ink)]">
                                        <span class="font-mono text-sm text-[color:var(--color-ink-muted)] mr-2"><?= pp_e($opt['letter']) ?></span>
                                        <?= pp_e($opt['label']) ?>
                                        <?php if ($opt['chosen']): ?>
                                            <span class="text-xs text-[color:var(--color-terracotta)] ml-2">Jouw keuze</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-mono text-tabular text-sm text-[color:var(--color-ink)]">
                                        <?= number_format((float) $opt['pct'], 0) ?>%
                                    </span>
                                </div>
                                <div class="progress">
                                    <div class="progress__fill" style="width: <?= (float) $opt['pct'] ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php foreach ([['A', $blog->poll->option_a], ['B', $blog->poll->option_b]] as [$letter, $label]): ?>
                                <button type="button"
                                        class="vote-btn keyline-card p-4 text-left transition hover:border-[color:var(--color-hague)]"
                                        data-choice="<?= pp_e($letter) ?>"
                                        data-poll-id="<?= (int) $blog->poll->id ?>">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-[color:var(--color-paper-2)] flex items-center justify-center font-display font-semibold text-[color:var(--color-hague)]">
                                            <?= pp_e($letter) ?>
                                        </span>
                                        <span class="font-display text-base text-[color:var(--color-ink)]"><?= pp_e($label) ?></span>
                                    </div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</article>

<?php require __DIR__ . '/partials/blog-scripts.php'; ?>

<?php require_once 'views/templates/footer.php'; ?>
