<?php
/**
 * Editorial admin overview voor blogs (Wave 2).
 *
 * Beschikbare data:
 *   $blogs          - object[] (paginated)
 *   $paginationData - {currentPage, totalPages, perPage, totalBlogs}
 *   $csrf_token     - string
 */
require_once BASE_PATH . '/views/templates/header.php';

if (!function_exists('pp_admin_blog_date')) {
    function pp_admin_blog_date($value): string
    {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        return date('d-m-Y', $ts);
    }
}

$totalBlogs = (int) ($paginationData['totalBlogs'] ?? count($blogs));
$currentPage = (int) ($paginationData['currentPage'] ?? 1);
$totalPages = (int) ($paginationData['totalPages'] ?? 1);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Admin',
    'title'   => 'Blogbeheer',
    'lead'    => 'Overzicht van alle blogs. Bekijk, bewerk of verwijder publicaties en houd statistieken in de gaten.',
]) ?>

<section class="pp-container pp-container--xl mt-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-3 text-sm text-[color:var(--color-ink-muted)]">
            <span class="font-mono text-tabular text-[color:var(--color-ink)]"><?= (int) count($blogs) ?></span>
            van
            <span class="font-mono text-tabular text-[color:var(--color-ink)]"><?= $totalBlogs ?></span>
            blogs zichtbaar
            <span class="text-[color:var(--color-keyline-strong)]">|</span>
            Pagina
            <span class="font-mono text-tabular text-[color:var(--color-ink)]"><?= $currentPage ?></span>
            van
            <span class="font-mono text-tabular text-[color:var(--color-ink)]"><?= $totalPages ?></span>
        </div>
        <a href="<?= pp_e(pp_url('/blogs/create')) ?>" class="btn btn--primary">
            <?= pp_icon('feather', 16) ?>
            Nieuwe blog
        </a>
    </div>

    <?php if (empty($blogs)): ?>
        <div class="keyline-card p-12 text-center">
            <div class="text-[color:var(--color-ink-faint)] mb-4 inline-block">
                <?= pp_icon('file-text', 48) ?>
            </div>
            <h3 class="font-display text-2xl text-[color:var(--color-ink)] mb-2">Nog geen blogs</h3>
            <p class="text-[color:var(--color-ink-muted)] mb-6 max-w-md mx-auto">
                Schrijf je eerste blog en bereik lezers die wakker liggen van de Nederlandse politiek.
            </p>
            <a href="<?= pp_e(pp_url('/blogs/create')) ?>" class="btn btn--primary">
                <?= pp_icon('feather', 16) ?>
                Eerste blog schrijven
            </a>
        </div>
    <?php else: ?>
        <div class="keyline-card overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50%;">Titel</th>
                            <th scope="col">Categorie</th>
                            <th scope="col">Gepubliceerd</th>
                            <th scope="col" class="text-right">Likes</th>
                            <th scope="col" class="text-right">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $blog): ?>
                            <tr>
                                <td>
                                    <div class="flex items-start gap-4">
                                        <?php if (!empty($blog->image_path)): ?>
                                            <img src="<?= pp_e(getBlogImageUrl($blog->image_path)) ?>"
                                                 alt=""
                                                 class="w-14 h-14 rounded-md object-cover border border-[color:var(--color-keyline)] flex-shrink-0">
                                        <?php else: ?>
                                            <div class="w-14 h-14 rounded-md bg-[color:var(--color-paper-2)] flex items-center justify-center border border-[color:var(--color-keyline)] flex-shrink-0 text-[color:var(--color-ink-faint)]">
                                                <?= pp_icon('image', 20) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <a href="<?= pp_e(pp_url('/blogs/' . $blog->slug)) ?>"
                                               class="font-display text-base text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)] line-clamp-1">
                                                <?= pp_e($blog->title) ?>
                                            </a>
                                            <p class="text-sm text-[color:var(--color-ink-muted)] line-clamp-2 mt-1">
                                                <?= pp_e(strip_tags($blog->summary ?? '')) ?>
                                            </p>
                                            <p class="text-xs text-[color:var(--color-ink-faint)] mt-1 font-mono text-tabular">
                                                #<?= (int) $blog->id ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($blog->category_name)): ?>
                                        <span class="tag tag--<?= pp_e(pp_category_tone($blog->category_name)) ?>">
                                            <?= pp_e($blog->category_name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-[color:var(--color-ink-faint)] text-sm">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-sm text-[color:var(--color-ink)] font-mono text-tabular">
                                        <?= pp_e(pp_admin_blog_date($blog->published_at)) ?>
                                    </div>
                                    <div class="text-xs text-[color:var(--color-ink-muted)] font-mono">
                                        <?= pp_e(date('H:i', strtotime($blog->published_at))) ?>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <span class="inline-flex items-center gap-1.5 text-sm text-[color:var(--color-ink)]">
                                        <?= pp_icon('heart', 14) ?>
                                        <span class="font-mono text-tabular"><?= (int) ($blog->likes ?? 0) ?></span>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="<?= pp_e(pp_url('/blogs/' . $blog->slug)) ?>"
                                           class="btn btn--ghost btn--sm" title="Bekijken" aria-label="Bekijken">
                                            <?= pp_icon('eye', 14) ?>
                                        </a>
                                        <a href="<?= pp_e(pp_url('/blogs/edit/' . $blog->id)) ?>"
                                           class="btn btn--ghost btn--sm" title="Bewerken" aria-label="Bewerken">
                                            <?= pp_icon('edit', 14) ?>
                                        </a>
                                        <form action="<?= pp_e(pp_url('/blogs/delete/' . $blog->id)) ?>"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je deze blog wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                            <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">
                                            <button type="submit"
                                                    class="btn btn--ghost btn--sm"
                                                    style="color: var(--color-terracotta);"
                                                    title="Verwijderen" aria-label="Verwijderen">
                                                <?= pp_icon('trash', 14) ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y divide-[color:var(--color-keyline)]">
                <?php foreach ($blogs as $blog): ?>
                    <div class="p-5">
                        <div class="flex items-start gap-4 mb-3">
                            <?php if (!empty($blog->image_path)): ?>
                                <img src="<?= pp_e(getBlogImageUrl($blog->image_path)) ?>"
                                     alt=""
                                     class="w-16 h-16 rounded-md object-cover border border-[color:var(--color-keyline)] flex-shrink-0">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-md bg-[color:var(--color-paper-2)] flex items-center justify-center border border-[color:var(--color-keyline)] flex-shrink-0 text-[color:var(--color-ink-faint)]">
                                    <?= pp_icon('image', 24) ?>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <a href="<?= pp_e(pp_url('/blogs/' . $blog->slug)) ?>"
                                   class="font-display text-base text-[color:var(--color-ink)] line-clamp-2">
                                    <?= pp_e($blog->title) ?>
                                </a>
                                <p class="text-sm text-[color:var(--color-ink-muted)] line-clamp-2 mt-1">
                                    <?= pp_e(strip_tags($blog->summary ?? '')) ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between flex-wrap gap-3 text-xs text-[color:var(--color-ink-muted)]">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($blog->category_name)): ?>
                                    <span class="tag tag--<?= pp_e(pp_category_tone($blog->category_name)) ?>">
                                        <?= pp_e($blog->category_name) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="font-mono text-tabular">
                                    <?= pp_e(pp_admin_blog_date($blog->published_at)) ?>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <?= pp_icon('heart', 12) ?>
                                    <span class="font-mono text-tabular"><?= (int) ($blog->likes ?? 0) ?></span>
                                </span>
                            </div>
                            <div class="inline-flex items-center gap-1">
                                <a href="<?= pp_e(pp_url('/blogs/' . $blog->slug)) ?>"
                                   class="btn btn--ghost btn--sm" aria-label="Bekijken">
                                    <?= pp_icon('eye', 14) ?>
                                </a>
                                <a href="<?= pp_e(pp_url('/blogs/edit/' . $blog->id)) ?>"
                                   class="btn btn--ghost btn--sm" aria-label="Bewerken">
                                    <?= pp_icon('edit', 14) ?>
                                </a>
                                <form action="<?= pp_e(pp_url('/blogs/delete/' . $blog->id)) ?>"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Weet je zeker?')">
                                    <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">
                                    <button type="submit"
                                            class="btn btn--ghost btn--sm"
                                            style="color: var(--color-terracotta);"
                                            aria-label="Verwijderen">
                                        <?= pp_icon('trash', 14) ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination mt-10" aria-label="Pagineren">
                <?php if ($currentPage > 1): ?>
                    <a class="pagination__item" href="<?= pp_e(pp_url('/blogs/manage?page=' . ($currentPage - 1))) ?>">
                        <?= pp_icon('arrow-left', 14) ?>
                        <span class="ml-1 hidden sm:inline">Vorige</span>
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                if ($start > 1):
                ?>
                    <a class="pagination__item" href="<?= pp_e(pp_url('/blogs/manage?page=1')) ?>">1</a>
                    <?php if ($start > 2): ?><span class="pagination__item" style="border: 0;">...</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($p = $start; $p <= $end; $p++): ?>
                    <a class="pagination__item<?= $p === $currentPage ? ' pagination__item--active' : '' ?>"
                       href="<?= pp_e(pp_url('/blogs/manage?page=' . $p)) ?>">
                        <?= (int) $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?><span class="pagination__item" style="border: 0;">...</span><?php endif; ?>
                    <a class="pagination__item" href="<?= pp_e(pp_url('/blogs/manage?page=' . $totalPages)) ?>">
                        <?= (int) $totalPages ?>
                    </a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a class="pagination__item" href="<?= pp_e(pp_url('/blogs/manage?page=' . ($currentPage + 1))) ?>">
                        <span class="mr-1 hidden sm:inline">Volgende</span>
                        <?= pp_icon('arrow-right', 14) ?>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<div class="mb-24"></div>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
