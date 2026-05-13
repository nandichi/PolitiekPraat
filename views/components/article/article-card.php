<?php
/**
 * Editorial article card.
 *
 * Props:
 *   href          string         Doel-URL
 *   title         string         Titel
 *   excerpt       string|null    Korte excerpt
 *   image         string|null    Cover-URL (16:10)
 *   image_alt     string|null    Alt-tekst
 *   category      string|null    Categorie-label
 *   category_tone string|null    Override van auto-tone (ochre/olive/...)
 *   date          string|null
 *   reading_time  int|null
 *   author        array|null     {name, avatar, role}
 *   featured      bool           Featured = grotere typo + 16:9 frame
 *   variant       string         default|list (list = horizontaal)
 *   class         string
 */
$href = $href ?? ($props['href'] ?? '#');
$title = $title ?? ($props['title'] ?? 'Geen titel');
$excerpt = $excerpt ?? ($props['excerpt'] ?? null);
$image = $image ?? ($props['image'] ?? null);
$image_alt = $image_alt ?? ($props['image_alt'] ?? $title);
$category = $category ?? ($props['category'] ?? null);
$category_tone = $category_tone ?? ($props['category_tone'] ?? null);
$date = $date ?? ($props['date'] ?? null);
$reading_time = $reading_time ?? ($props['reading_time'] ?? null);
$author = $author ?? ($props['author'] ?? null);
$featured = $featured ?? ($props['featured'] ?? false);
$variant = $variant ?? ($props['variant'] ?? 'default');
$extraClass = $class ?? ($props['class'] ?? '');

$classes = pp_class('article-card', [
    'article-card--featured' => $featured,
    'article-card--list'     => $variant === 'list',
], $extraClass);
?>
<a class="<?= pp_e($classes) ?>" href="<?= pp_e(pp_url($href)) ?>">
    <?php if ($image): ?>
        <div class="article-card__media">
            <img src="<?= pp_e($image) ?>"
                 alt="<?= pp_e($image_alt) ?>"
                 loading="lazy"
                 decoding="async">
        </div>
    <?php endif; ?>
    <div class="article-card__body flex flex-col gap-2">
        <?= pp_render_component('article/meta-row', [
            'category' => $category,
            'category_tone' => $category_tone,
            'date' => $date,
            'reading_time' => $reading_time,
        ]) ?>
        <h3 class="article-card__title"><?= pp_e($title) ?></h3>
        <?php if ($excerpt): ?>
            <p class="article-card__excerpt"><?= pp_e($excerpt) ?></p>
        <?php endif; ?>
        <?php if ($author && !empty($author['name'])): ?>
            <div class="mt-1">
                <?= pp_render_component('article/byline', [
                    'name' => $author['name'],
                    'avatar' => $author['avatar'] ?? null,
                    'role' => $author['role'] ?? null,
                    'compact' => true,
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</a>
