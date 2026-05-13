<?php
/**
 * Compacte meta-row voor article-card (categorie + datum + leestijd).
 *
 * Props:
 *   category       string|null  Categorie label (rendert als tag)
 *   category_tone  string|null  Override van automatische tone (zie pp_category_tone)
 *   date           string|null  Leesvriendelijke datum
 *   reading_time   int|null     Leestijd in minuten
 */
$category = $category ?? ($props['category'] ?? null);
$category_tone = $category_tone ?? ($props['category_tone'] ?? null);
$date = $date ?? ($props['date'] ?? null);
$reading_time = $reading_time ?? ($props['reading_time'] ?? null);
$tone = $category_tone ?: pp_category_tone($category);
?>
<div class="meta-row">
    <?php if ($category): ?>
        <span class="tag tag--<?= pp_e($tone) ?>"><?= pp_e($category) ?></span>
    <?php endif; ?>
    <?php if ($date): ?>
        <?php if ($category): ?><span class="meta-row__dot"></span><?php endif; ?>
        <span><?= pp_e($date) ?></span>
    <?php endif; ?>
    <?php if ($reading_time): ?>
        <?php if ($category || $date): ?><span class="meta-row__dot"></span><?php endif; ?>
        <span><?= (int) $reading_time ?> min</span>
    <?php endif; ?>
</div>
