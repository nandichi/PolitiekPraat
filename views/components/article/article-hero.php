<?php
/**
 * Editorial article hero (homepage of blog index lead).
 *
 * Volle-breedte image links, tekst rechts (omdraaibaar via 'reverse').
 *
 * Props:
 *   href          string
 *   title         string
 *   lead          string|null  Lead-paragraaf
 *   image         string|null  Hero image
 *   image_alt     string|null
 *   category      string|null
 *   category_tone string|null
 *   date          string|null
 *   reading_time  int|null
 *   author        array|null
 *   reverse       bool         Tekst links, image rechts
 */
$href = $href ?? ($props['href'] ?? '#');
$title = $title ?? ($props['title'] ?? '');
$lead = $lead ?? ($props['lead'] ?? null);
$image = $image ?? ($props['image'] ?? null);
$image_alt = $image_alt ?? ($props['image_alt'] ?? $title);
$category = $category ?? ($props['category'] ?? null);
$category_tone = $category_tone ?? ($props['category_tone'] ?? null);
$date = $date ?? ($props['date'] ?? null);
$reading_time = $reading_time ?? ($props['reading_time'] ?? null);
$author = $author ?? ($props['author'] ?? null);
$reverse = $reverse ?? ($props['reverse'] ?? false);
?>
<article class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center<?= $reverse ? ' md:[direction:rtl]' : '' ?>">
    <a class="block group<?= $reverse ? ' md:[direction:ltr]' : '' ?>" href="<?= pp_e(pp_url($href)) ?>"
       aria-label="<?= pp_e($title) ?>" tabindex="-1">
        <?php if ($image): ?>
            <div class="editorial-frame editorial-frame--4-3">
                <img src="<?= pp_e($image) ?>"
                     alt="<?= pp_e($image_alt) ?>"
                     loading="eager"
                     fetchpriority="high"
                     class="transition-opacity duration-500 group-hover:opacity-90">
            </div>
        <?php else: ?>
            <div class="editorial-frame editorial-frame--4-3"
                 style="background: linear-gradient(135deg, var(--color-hague-tint), var(--color-ochre-tint));">
            </div>
        <?php endif; ?>
    </a>
    <div class="flex flex-col gap-4<?= $reverse ? ' md:[direction:ltr]' : '' ?>">
        <?= pp_render_component('article/meta-row', [
            'category' => $category,
            'category_tone' => $category_tone,
            'date' => $date,
            'reading_time' => $reading_time,
        ]) ?>
        <h2 class="font-display text-[clamp(2rem,3.5vw_+_0.5rem,3.25rem)] leading-[1.08] tracking-[-0.018em] text-[color:var(--color-ink)]">
            <a href="<?= pp_e(pp_url($href)) ?>"
               class="hover:text-[color:var(--color-hague)] transition-colors duration-200">
                <?= pp_e($title) ?>
            </a>
        </h2>
        <?php if ($lead): ?>
            <p class="text-[color:var(--color-ink-muted)] text-[1.125rem] leading-[1.6] max-w-[60ch]">
                <?= pp_e($lead) ?>
            </p>
        <?php endif; ?>
        <?php if ($author && !empty($author['name'])): ?>
            <div class="mt-2">
                <?= pp_render_component('article/byline', [
                    'name' => $author['name'],
                    'avatar' => $author['avatar'] ?? null,
                    'role' => $author['role'] ?? null,
                ]) ?>
            </div>
        <?php endif; ?>
        <div class="mt-2">
            <a href="<?= pp_e(pp_url($href)) ?>"
               class="inline-flex items-center gap-1.5 text-[color:var(--color-hague)] font-medium hover:text-[color:var(--color-terracotta)] transition-colors text-sm">
                <span>Lees het hele artikel</span>
                <?= pp_icon('arrow-right', 16) ?>
            </a>
        </div>
    </div>
</article>
