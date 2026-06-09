<?php require_once 'views/templates/header.php'; ?>

<?php
$themas = $themas ?? [];
$actueleThemas = $actueleThemas ?? [];

$themaIconMap = [
    // Canonieke slugs
    'klimaat-en-energie'    => 'leaf',
    'economie-en-financien' => 'trending-up',
    'onderwijs'             => 'graduation-cap',
    'zorg-en-welzijn'       => 'heart-pulse',
    'migratie-en-asiel'     => 'users',
    'veiligheid-en-justitie' => 'shield',
    'europa'                => 'globe',
    'defensie'              => 'shield-check',
    'landbouw-en-natuur'    => 'sprout',
    'wonen'                 => 'home',
    'mobiliteit-en-verkeer' => 'train-front',
    'digitalisering'        => 'cpu',
    // Legacy slugs (voor de zekerheid)
    'klimaatbeleid' => 'leaf',
    'woningmarkt'   => 'home',
    'economie'      => 'trending-up',
    'zorg'          => 'heart-pulse',
    'arbeidsmarkt'  => 'briefcase',
    'immigratie'    => 'users',
    'veiligheid'    => 'shield',
    'duurzaamheid'  => 'leaf',
    'milieu'        => 'leaf',
    'energie'       => 'zap',
];

// Geldige canonieke slugs (om kapotte /thema/-links te voorkomen).
$themaValidSlugs = array_keys(require __DIR__ . '/../../includes/data/themas.php');

if (!function_exists('pp_thema_icon')) {
    function pp_thema_icon(array $iconMap, $slug, $explicit = null): string {
        if (is_string($explicit) && $explicit !== '') {
            return $explicit;
        }
        $key = strtolower((string) $slug);
        return $iconMap[$key] ?? 'tag';
    }
}

if (!function_exists('pp_thema_href')) {
    function pp_thema_href(array $validSlugs, $slug): string {
        $slug = (string) $slug;
        if ($slug !== '' && in_array(strtolower($slug), $validSlugs, true)) {
            return pp_url('/thema/' . urlencode($slug));
        }
        // Onbekende/lege slug: stuur naar het overzicht in plaats van een 404.
        return pp_url('/themas');
    }
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Thema\'s',
    'title'   => 'Politieke onderwerpen, helder uitgelegd',
    'lead'    => 'Verdiep je in de thema\'s die de Nederlandse politiek bepalen. Per thema vind je achtergrond, standpunten van partijen en lopende debatten.',
]) ?>

<?php if (!empty($actueleThemas)): ?>
    <section class="pp-container pp-container--xl mt-12 mb-20">
        <?= pp_render_component('section/section-header', [
            'label' => 'Actueel',
            'title' => 'Thema\'s in het politieke debat',
        ]) ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($actueleThemas as $thema): ?>
                <?php
                $slug = $thema['slug'] ?? '';
                $title = $thema['title'] ?? '';
                $desc = $thema['description'] ?? '';
                $iconName = (isset($thema['icon']) && is_string($thema['icon']) && preg_match('/^[a-z0-9-]+$/', $thema['icon'])) ? $thema['icon'] : null;
                ?>
                <a href="<?= pp_e(pp_thema_href($themaValidSlugs, $slug)) ?>"
                   class="keyline-card p-6 transition hover:border-[color:var(--color-line-strong)] flex flex-col">
                    <div class="text-[color:var(--color-hague)] mb-4">
                        <?= pp_icon(pp_thema_icon($themaIconMap, $slug, $iconName), 26) ?>
                    </div>
                    <h3 class="font-display text-xl text-[color:var(--color-ink)] mb-3 leading-tight"><?= pp_e($title) ?></h3>
                    <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-5 flex-1"><?= pp_e($desc) ?></p>
                    <div class="inline-flex items-center gap-1.5 text-sm font-display text-[color:var(--color-hague)] mt-auto">
                        Lees verder
                        <?= pp_icon('arrow-right', 13) ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="pp-container pp-container--xl mb-24">
    <?= pp_render_component('section/section-header', [
        'label' => 'Alle thema\'s',
        'title' => 'Het complete overzicht',
        'lead'  => 'De thema\'s die structureel terugkomen in Nederlandse verkiezingsprogramma\'s en coalitieonderhandelingen.',
    ]) ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($themas as $thema): ?>
            <?php
            $slug = $thema['slug'] ?? '';
            $title = $thema['title'] ?? '';
            $desc = $thema['description'] ?? '';
            $iconName = (isset($thema['icon']) && is_string($thema['icon']) && preg_match('/^[a-z0-9-]+$/', $thema['icon'])) ? $thema['icon'] : null;
            ?>
            <a href="<?= pp_e(pp_thema_href($themaValidSlugs, $slug)) ?>"
               class="keyline-card p-5 transition hover:border-[color:var(--color-line-strong)] flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    <span class="text-[color:var(--color-hague)] flex-shrink-0 mt-0.5"><?= pp_icon(pp_thema_icon($themaIconMap, $slug, $iconName), 22) ?></span>
                    <h3 class="font-display text-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e($title) ?></h3>
                </div>
                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed flex-1"><?= pp_e($desc) ?></p>
                <span class="inline-flex items-center gap-1.5 text-sm font-display text-[color:var(--color-hague)] mt-4">
                    Lees verder
                    <?= pp_icon('arrow-right', 13) ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'views/templates/footer.php'; ?>
