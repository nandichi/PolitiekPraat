<?php
if (!function_exists('pp_nieuws_time')) {
    function pp_nieuws_time($value): string {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        $diff = time() - $ts;
        if ($diff < 60) return 'Net binnen';
        if ($diff < 3600) return floor($diff / 60) . ' min geleden';
        if ($diff < 86400) return floor($diff / 3600) . ' uur geleden';
        if ($diff < 7 * 86400) return floor($diff / 86400) . ' dagen geleden';
        $months = [1=>'januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)];
    }
}

if (!function_exists('pp_nieuws_orientation_label')) {
    function pp_nieuws_orientation_label(string $orient): array {
        if ($orient === 'links') {
            return ['Progressief', 'olive'];
        }
        if ($orient === 'rechts') {
            return ['Conservatief', 'terracotta'];
        }
        return ['Overig', 'ochre'];
    }
}

$currentFilter = $filter ?? 'alle';
$articles = $latest_news ?? [];

$totalArticles = $stats['total_articles'] ?? 0;
$progressiveCount = $stats['progressive_count'] ?? 0;
$conservativeCount = $stats['conservative_count'] ?? 0;
$sourceCount = $stats['source_count'] ?? 0;

$lead = array_shift($articles);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Nieuws',
    'title'   => 'Twee perspectieven, een gebalanceerd overzicht',
    'lead'    => 'We tonen actueel nieuws naast elkaar uit progressieve en conservatieve bronnen. Geen algoritme dat je in een bubbel houdt - jij ziet wat beide kanten schrijven.',
]) ?>

<section class="pp-container pp-container--xl mt-10 mb-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Artikelen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format((int) $totalArticles, 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-olive)] mb-2">Progressief</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format((int) $progressiveCount, 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-terracotta)] mb-2">Conservatief</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format((int) $conservativeCount, 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Bronnen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= (int) $sourceCount ?></div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mb-8">
    <div class="border-y border-[color:var(--color-keyline)] py-4 flex flex-wrap items-center gap-x-2 gap-y-2">
        <span class="eyebrow text-[color:var(--color-ink-faint)] mr-3">Filter</span>
        <a href="<?= pp_e(pp_url('/nieuws')) ?>"
           class="chip <?= $currentFilter === 'alle' ? 'chip--active' : '' ?>">Alles</a>
        <a href="<?= pp_e(pp_url('/nieuws?filter=progressief')) ?>"
           class="chip <?= $currentFilter === 'progressief' ? 'chip--active' : '' ?>">Progressief</a>
        <a href="<?= pp_e(pp_url('/nieuws?filter=conservatief')) ?>"
           class="chip <?= $currentFilter === 'conservatief' ? 'chip--active' : '' ?>">Conservatief</a>
    </div>
</section>

<?php if ($lead): ?>
    <?php [$leadLabel, $leadAccent] = pp_nieuws_orientation_label($lead['orientation'] ?? ''); ?>
    <section id="artikelen" class="pp-container pp-container--xl mb-16">
        <a href="<?= pp_e($lead['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer"
           class="block keyline-card p-6 md:p-10 transition hover:border-[color:var(--color-line-strong)]">
            <div class="flex flex-wrap items-center gap-3 mb-5">
                <span class="badge badge--<?= pp_e($leadAccent) ?>"><?= pp_e($leadLabel) ?></span>
                <span class="font-display text-sm text-[color:var(--color-ink)]"><?= pp_e($lead['source'] ?? '') ?></span>
                <span class="text-xs text-[color:var(--color-ink-faint)]">·</span>
                <span class="font-mono text-xs text-[color:var(--color-ink-muted)]"><?= pp_e(pp_nieuws_time($lead['publishedAt'] ?? '')) ?></span>
            </div>
            <h2 class="font-display text-display-xl md:text-display-2xl leading-[1.05] text-[color:var(--color-ink)] mb-5">
                <?= pp_e($lead['title'] ?? '') ?>
            </h2>
            <?php if (!empty($lead['description'])): ?>
                <p class="text-lg md:text-xl leading-snug text-[color:var(--color-ink-muted)] max-w-3xl">
                    <?= pp_e($lead['description']) ?>
                </p>
            <?php endif; ?>
            <div class="mt-6 inline-flex items-center gap-2 font-display text-sm text-[color:var(--color-hague)]">
                Lees verder bij <?= pp_e($lead['source'] ?? '') ?>
                <?= pp_icon('arrow-up-right', 14) ?>
            </div>
        </a>
    </section>
<?php endif; ?>

<?php if (!empty($articles)): ?>
    <section class="pp-container pp-container--xl mb-16">
        <?= pp_render_component('section/section-header', [
            'label' => 'Laatste artikelen',
            'title' => 'Meer uit beide perspectieven',
        ]) ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
            <?php foreach ($articles as $article): ?>
                <?php [$label, $accent] = pp_nieuws_orientation_label($article['orientation'] ?? ''); ?>
                <article class="border-t border-[color:var(--color-keyline)] pt-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="badge badge--<?= pp_e($accent) ?>"><?= pp_e($label) ?></span>
                        <span class="font-display text-xs text-[color:var(--color-ink)]"><?= pp_e($article['source'] ?? '') ?></span>
                    </div>
                    <h3 class="font-display text-lg md:text-xl leading-tight text-[color:var(--color-ink)] mb-3">
                        <a href="<?= pp_e($article['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[color:var(--color-hague)]">
                            <?= pp_e($article['title'] ?? '') ?>
                        </a>
                    </h3>
                    <?php if (!empty($article['description'])): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-4 line-clamp-3">
                            <?= pp_e($article['description']) ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-mono text-[color:var(--color-ink-faint)]">
                            <?= pp_e(pp_nieuws_time($article['publishedAt'] ?? '')) ?>
                        </span>
                        <a href="<?= pp_e($article['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[color:var(--color-hague)] hover:underline underline-offset-2">
                            Bron
                            <?= pp_icon('arrow-up-right', 12) ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php if (empty($lead) && empty($articles)): ?>
    <section class="pp-container pp-container--md py-16 mb-24 text-center">
        <div class="text-[color:var(--color-ink-faint)] mb-4 flex justify-center"><?= pp_icon('inbox', 32) ?></div>
        <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-2">Geen artikelen gevonden</h2>
        <p class="text-[color:var(--color-ink-muted)]">Pas de filter aan of kom later terug.</p>
    </section>
<?php endif; ?>

<?php if (!empty($totalPages) && (int) $totalPages > 1): ?>
    <section class="pp-container pp-container--xl mb-24">
        <nav class="pagination" aria-label="Paginering">
            <?php
            $base = '/nieuws';
            $params = [];
            if (!empty($currentFilter) && $currentFilter !== 'alle') $params['filter'] = $currentFilter;
            $prev = max(1, (int) $newsCurrentPage - 1);
            $next = min((int) $totalPages, (int) $newsCurrentPage + 1);
            $makeLink = function ($page) use ($base, $params) {
                $p = $params;
                if ($page > 1) $p['page'] = $page;
                return pp_url($base . (empty($p) ? '' : ('?' . http_build_query($p))));
            };
            ?>
            <?php if ((int) $newsCurrentPage > 1): ?>
                <a href="<?= pp_e($makeLink($prev)) ?>" class="pagination__link">
                    <?= pp_icon('arrow-left', 14) ?>
                    Vorige
                </a>
            <?php endif; ?>

            <span class="pagination__info">
                Pagina <span class="font-mono"><?= (int) $newsCurrentPage ?></span>
                van <span class="font-mono"><?= (int) $totalPages ?></span>
            </span>

            <?php if ((int) $newsCurrentPage < (int) $totalPages): ?>
                <a href="<?= pp_e($makeLink($next)) ?>" class="pagination__link">
                    Volgende
                    <?= pp_icon('arrow-right', 14) ?>
                </a>
            <?php endif; ?>
        </nav>
    </section>
<?php endif; ?>

<?php require_once 'views/templates/footer.php'; ?>
