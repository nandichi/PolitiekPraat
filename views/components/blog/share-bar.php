<?php
/**
 * Blog share bar — zichtbaar op mobiel en desktop.
 *
 * @var object $blog Blog record
 * @var string $shareUrl Canonical share URL
 * @var string $layout 'inline' | 'stack' (default inline)
 */
$shareUrl = $shareUrl ?? rtrim(URLROOT, '/') . '/blogs/' . ($blog->slug ?? '');
$shareTitle = $blog->title ?? '';
$encodedUrl = rawurlencode($shareUrl);
$encodedTitle = rawurlencode($shareTitle);
$layout = $layout ?? 'inline';
$layoutClass = $layout === 'stack' ? 'blog-share-bar--stack' : 'blog-share-bar--inline';
?>
<div class="blog-share-bar <?= pp_e($layoutClass) ?>" data-share-url="<?= pp_e($shareUrl) ?>" data-share-title="<?= pp_e($shareTitle) ?>">
    <span class="blog-share-bar__label">Deel dit artikel</span>
    <div class="blog-share-bar__actions">
        <button type="button"
                class="btn btn--ghost btn--sm blog-share-bar__btn"
                data-share-action="native"
                aria-label="Deel via apparaat">
            <?= pp_icon('share-2', 16) ?>
            <span class="blog-share-bar__text">Deel</span>
        </button>
        <a href="https://wa.me/?text=<?= $encodedTitle ?>%20<?= $encodedUrl ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn--ghost btn--sm blog-share-bar__btn"
           data-share-action="whatsapp"
           aria-label="Deel via WhatsApp">
            <?= pp_icon('message-circle', 16) ?>
            <span class="blog-share-bar__text">WhatsApp</span>
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?= $encodedTitle ?>&url=<?= $encodedUrl ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn--ghost btn--sm blog-share-bar__btn"
           data-share-action="twitter"
           aria-label="Deel op X">
            <?= pp_icon('twitter', 16) ?>
            <span class="blog-share-bar__text">X</span>
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $encodedUrl ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn--ghost btn--sm blog-share-bar__btn"
           data-share-action="linkedin"
           aria-label="Deel op LinkedIn">
            <?= pp_icon('linkedin', 16) ?>
            <span class="blog-share-bar__text">LinkedIn</span>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn--ghost btn--sm blog-share-bar__btn"
           data-share-action="facebook"
           aria-label="Deel op Facebook">
            <?= pp_icon('facebook', 16) ?>
            <span class="blog-share-bar__text">Facebook</span>
        </a>
        <button type="button"
                class="btn btn--ghost btn--sm blog-share-bar__btn"
                data-share-action="copy"
                aria-label="Kopieer link">
            <?= pp_icon('link', 16) ?>
            <span class="blog-share-bar__text">Kopieer</span>
        </button>
        <button type="button"
                id="instagramStoryBtn"
                class="btn btn--ghost btn--sm blog-share-bar__btn blog-share-bar__btn--story"
                aria-label="Deel als Instagram Story">
            <?= pp_icon('instagram', 16) ?>
            <span class="blog-share-bar__text">Story</span>
        </button>
    </div>
</div>
