<?php
/**
 * Editorial blog index (Wave 2).
 *
 * Beschikbare data:
 *   $blogs            - object[] (paginated)
 *   $categories       - object[] (alle blogcategorieën)
 *   $selectedCategory - object|null
 *   $paginationData   - {currentPage, totalPages, perPage, totalBlogs}
 */
require_once 'views/templates/header.php';

if (!function_exists('pp_blogs_format_date')) {
    function pp_blogs_format_date($value): string
    {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        $months = [1=>'januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$activeCategorySlug = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
$leadBlog = $blogs[0] ?? null;
$gridBlogs = array_slice($blogs, 1, 6);
$listBlogs = array_slice($blogs, 7);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => $selectedCategory ? 'Categorie' : 'Blogs',
    'title'   => $selectedCategory ? $selectedCategory->name : 'Politieke blogs en opinie',
    'lead'    => $selectedCategory && !empty($selectedCategory->description)
        ? $selectedCategory->description
        : 'Diepgaande analyses, persoonlijke columns en helder commentaar over Nederlandse politiek - geschreven door ervaren auteurs en gepassioneerde stemmen.',
]) ?>

<?php if (!empty($categories)): ?>
<section class="pp-container pp-container--xl mt-4">
    <div class="flex flex-wrap gap-2.5 items-center">
        <a href="<?= pp_e(pp_url('/blogs')) ?>"
           class="tag tag--<?= $activeCategorySlug === '' ? 'hague' : 'neutral' ?>"
           style="text-transform: none; letter-spacing: 0; padding: 0.45rem 0.9rem;">
            Alle blogs
        </a>
        <?php
        $toneCycle = ['terracotta', 'ochre', 'olive', 'moss', 'rose', 'hague'];
        foreach ($categories as $i => $cat):
            $tone = $toneCycle[$i % count($toneCycle)];
            $isActive = $activeCategorySlug === ($cat->slug ?? '');
        ?>
            <a href="<?= pp_e(pp_url('/blogs?category=' . urlencode($cat->slug ?? ''))) ?>"
               class="tag tag--<?= pp_e($isActive ? $tone : 'neutral') ?>"
               style="text-transform: none; letter-spacing: 0; padding: 0.45rem 0.9rem;">
                <?= pp_e($cat->name ?? '') ?>
            </a>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= pp_e(pp_url('/blogs/create')) ?>" class="btn btn--ghost btn--sm ml-auto">
                <?= pp_icon('feather', 14) ?>
                Schrijf een blog
            </a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($leadBlog): ?>
<section class="pp-container pp-container--xl mt-12">
    <?= pp_render_component('article/article-hero', [
        'href'         => '/blogs/' . ($leadBlog->slug ?? ''),
        'title'        => $leadBlog->title ?? '',
        'lead'         => substr(strip_tags($leadBlog->summary ?? ''), 0, 220),
        'image'        => !empty($leadBlog->image_path) ? getBlogImageUrl($leadBlog->image_path) : null,
        'image_alt'    => $leadBlog->title ?? '',
        'category'     => $leadBlog->category_name ?? null,
        'date'         => pp_blogs_format_date($leadBlog->published_at ?? null),
        'reading_time' => pp_reading_time($leadBlog->content ?? null),
        'author'       => ['name' => $leadBlog->author_name ?? 'Redactie'],
    ]) ?>
</section>
<?php endif; ?>

<?php if (!empty($gridBlogs)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Recent',
        'title' => 'Meer blogs',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($gridBlogs as $blog): ?>
            <?= pp_render_component('article/article-card', [
                'href'         => '/blogs/' . ($blog->slug ?? ''),
                'title'        => $blog->title ?? '',
                'excerpt'      => substr(strip_tags($blog->summary ?? ''), 0, 140),
                'image'        => !empty($blog->image_path) ? getBlogImageUrl($blog->image_path) : null,
                'category'     => $blog->category_name ?? null,
                'date'         => pp_blogs_format_date($blog->published_at ?? null),
                'reading_time' => pp_reading_time($blog->content ?? null),
                'author'       => ['name' => $blog->author_name ?? 'Redactie'],
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($listBlogs)): ?>
<section class="pp-container pp-container--xl mt-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Archief',
        'title' => 'Alle blogs van deze pagina',
    ]) ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12">
        <?php foreach ($listBlogs as $blog): ?>
            <?= pp_render_component('article/article-card', [
                'variant'      => 'list',
                'href'         => '/blogs/' . ($blog->slug ?? ''),
                'title'        => $blog->title ?? '',
                'excerpt'      => substr(strip_tags($blog->summary ?? ''), 0, 110),
                'image'        => !empty($blog->image_path) ? getBlogImageUrl($blog->image_path) : null,
                'category'     => $blog->category_name ?? null,
                'date'         => pp_blogs_format_date($blog->published_at ?? null),
                'reading_time' => pp_reading_time($blog->content ?? null),
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (empty($blogs)): ?>
<section class="pp-container pp-container--xl mt-12">
    <div class="keyline-card p-12 text-center">
        <h3 class="font-display text-2xl text-[color:var(--color-ink)] mb-2">Nog geen blogs in deze categorie</h3>
        <p class="text-[color:var(--color-ink-muted)] mb-6">
            Onze auteurs werken aan nieuwe verhalen. Bekijk de andere categorieën of kom binnenkort terug.
        </p>
        <a href="<?= pp_e(pp_url('/blogs')) ?>" class="btn btn--primary">Alle blogs</a>
    </div>
</section>
<?php endif; ?>

<?php
$pg = $paginationData ?? null;
if ($pg && (int) ($pg['totalPages'] ?? 0) > 1):
    $current = (int) $pg['currentPage'];
    $total = (int) $pg['totalPages'];
    $catParam = $activeCategorySlug !== '' ? '&category=' . urlencode($activeCategorySlug) : '';
?>
<section class="pp-container pp-container--xl mt-16">
    <nav class="pagination" aria-label="Blog paginering">
        <?php if ($current > 1): ?>
            <a class="pagination__item" href="<?= pp_e(pp_url('/blogs?page=' . ($current - 1) . $catParam)) ?>">
                <?= pp_icon('arrow-left', 14) ?>
                <span class="ml-1 hidden sm:inline">Vorige</span>
            </a>
        <?php endif; ?>

        <?php
        $start = max(1, $current - 2);
        $end = min($total, $current + 2);
        if ($start > 1): ?>
            <a class="pagination__item" href="<?= pp_e(pp_url('/blogs?page=1' . $catParam)) ?>">1</a>
            <?php if ($start > 2): ?><span class="pagination__item" style="border: 0;">...</span><?php endif; ?>
        <?php endif; ?>

        <?php for ($p = $start; $p <= $end; $p++): ?>
            <a class="pagination__item<?= $p === $current ? ' pagination__item--active' : '' ?>"
               href="<?= pp_e(pp_url('/blogs?page=' . $p . $catParam)) ?>">
                <?= (int) $p ?>
            </a>
        <?php endfor; ?>

        <?php if ($end < $total): ?>
            <?php if ($end < $total - 1): ?><span class="pagination__item" style="border: 0;">...</span><?php endif; ?>
            <a class="pagination__item" href="<?= pp_e(pp_url('/blogs?page=' . $total . $catParam)) ?>"><?= (int) $total ?></a>
        <?php endif; ?>

        <?php if ($current < $total): ?>
            <a class="pagination__item" href="<?= pp_e(pp_url('/blogs?page=' . ($current + 1) . $catParam)) ?>">
                <span class="mr-1 hidden sm:inline">Volgende</span>
                <?= pp_icon('arrow-right', 14) ?>
            </a>
        <?php endif; ?>
    </nav>
</section>
<?php endif; ?>

<?php require_once 'views/templates/footer.php'; ?>
