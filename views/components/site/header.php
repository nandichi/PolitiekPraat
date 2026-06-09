<?php
/**
 * Editorial site header met sticky navigation + dropdown-menu's.
 *
 * De hoofdnavigatie bundelt alle pagina's (voorheen alleen in de footer) in
 * nette, gesorteerde dropdowns. Midterms 2026 blijft een losse accent-knop.
 *
 * Verwacht globale URLROOT + SITENAME + optionele $_SESSION.
 * `getCurrentPageContext()` wordt gebruikt voor de active-state.
 */
$context = function_exists('getCurrentPageContext') ? getCurrentPageContext() : ['section' => ''];
$section = $context['section'] ?? '';

$navItems = [
    [
        'type'  => 'dropdown',
        'label' => 'Stemhulpen',
        'id'    => 'nav-stemhulpen',
        'match' => ['partijmeter', 'politiek-kompas', 'stemwijzer', 'resultaten'],
        'groups' => [
            [
                'items' => [
                    ['label' => 'PartijMeter',         'href' => '/partijmeter',     'icon' => 'scale',     'desc' => 'Vergelijk 30 standpunten'],
                    ['label' => 'Politiek Kompas',     'href' => '/politiek-kompas', 'icon' => 'pie-chart', 'desc' => 'Jouw positie op twee assen'],
                    ['label' => 'Stemwijzer Ede 2026', 'href' => '/stemwijzer',      'icon' => 'vote',      'desc' => '25 stellingen voor gemeente Ede'],
                ],
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Politiek',
        'id'    => 'nav-politiek',
        'match' => ['partijen', 'stemmentracker', 'themas', 'thema'],
        'groups' => [
            [
                'items' => [
                    ['label' => 'Partijen',       'href' => '/partijen',       'icon' => 'users',       'desc' => 'Alle partijen en standpunten'],
                    ['label' => 'Stemmentracker', 'href' => '/stemmentracker', 'icon' => 'bar-chart-3', 'desc' => 'Hoe stemt elke partij?'],
                    ['label' => "Thema's",        'href' => '/themas',         'icon' => 'layers',      'desc' => 'Politiek per onderwerp'],
                ],
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Lezen',
        'id'    => 'nav-lezen',
        'match' => ['blogs', 'nieuws'],
        'groups' => [
            [
                'items' => [
                    ['label' => 'Blogs',  'href' => '/blogs',  'icon' => 'feather',   'desc' => 'Analyses en opinie'],
                    ['label' => 'Nieuws', 'href' => '/nieuws', 'icon' => 'newspaper', 'desc' => 'Politiek nieuws, twee kanten'],
                ],
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Verkiezingen',
        'id'    => 'nav-verkiezingen',
        'match' => ['nederlandse-verkiezingen', 'amerikaanse-verkiezingen'],
        'groups' => [
            [
                'items' => [
                    ['label' => 'Nederlandse verkiezingen', 'href' => '/nederlandse-verkiezingen', 'icon' => 'landmark', 'desc' => '175 jaar democratie'],
                    ['label' => 'Amerikaanse verkiezingen', 'href' => '/amerikaanse-verkiezingen', 'icon' => 'flag',     'desc' => 'Van Washington tot nu'],
                ],
            ],
        ],
    ],
    [
        'type'    => 'usa',
        'label'   => 'Midterms 2026',
        'href'    => '/midterms-2026',
        'match'   => ['midterms-2026'],
        'icon'    => 'flag',
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Over',
        'id'    => 'nav-over',
        'align' => 'end',
        'match' => ['over-mij', 'contact', 'donatie', 'privacy-policy', 'cookie-policy', 'toegankelijkheid', 'gebruiksvoorwaarden'],
        'groups' => [
            [
                'items' => [
                    ['label' => 'Over PolitiekPraat', 'href' => '/over-mij', 'icon' => 'info',   'desc' => 'Missie en platform'],
                    ['label' => 'Contact',            'href' => '/contact',  'icon' => 'mail',   'desc' => 'Vragen of feedback'],
                    ['label' => 'Steun ons',          'href' => '/donatie',  'icon' => 'coffee', 'desc' => 'Houd ons onafhankelijk'],
                ],
            ],
            [
                'label' => 'Juridisch',
                'type'  => 'links',
                'items' => [
                    ['label' => 'Privacy',          'href' => '/privacy-policy'],
                    ['label' => 'Cookies',          'href' => '/cookie-policy'],
                    ['label' => 'Toegankelijkheid', 'href' => '/toegankelijkheid'],
                    ['label' => 'Voorwaarden',      'href' => '/gebruiksvoorwaarden'],
                ],
            ],
        ],
    ],
];

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isAdmin();
$username = $_SESSION['username'] ?? '';

$ppNavActive = static function (array $item) use ($section): bool {
    return in_array($section, $item['match'] ?? [], true);
};
?>
<header class="site-header" role="banner">
    <div class="pp-container site-header__inner">
        <a href="<?= pp_e(pp_url('/')) ?>" class="site-header__brand" aria-label="<?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?> - home">
            <?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?>
        </a>

        <nav class="site-nav" aria-label="Hoofdnavigatie">
            <?php foreach ($navItems as $item): ?>
                <?php $isActive = $ppNavActive($item); ?>

                <?php if (($item['type'] ?? '') === 'usa'): ?>
                    <a href="<?= pp_e(pp_url($item['href'])) ?>"
                       class="site-nav__link site-nav__link--usa"
                       <?= $isActive ? 'aria-current="page"' : '' ?>>
                        <span class="site-nav__usa-inner">
                            <?php if (!empty($item['icon'])): ?><span class="site-nav__link-icon" aria-hidden="true"><?= pp_icon($item['icon'], 14) ?></span><?php endif; ?>
                            <?= pp_e($item['label']) ?>
                        </span>
                    </a>

                <?php elseif (($item['type'] ?? '') === 'dropdown'): ?>
                    <div class="site-nav__item<?= ($item['align'] ?? '') === 'end' ? ' site-nav__item--end' : '' ?>" data-nav-dropdown>
                        <button type="button"
                                class="site-nav__trigger<?= $isActive ? ' site-nav__trigger--active' : '' ?>"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="<?= pp_e($item['id']) ?>"
                                data-nav-trigger>
                            <?= pp_e($item['label']) ?>
                            <span class="site-nav__chevron" aria-hidden="true"><?= pp_icon('chevron-down', 14) ?></span>
                        </button>
                        <div class="site-nav__panel" id="<?= pp_e($item['id']) ?>" data-nav-panel>
                            <div class="site-nav__panel-inner">
                                <?php foreach ($item['groups'] as $group): ?>
                                    <?php $isLinksGroup = ($group['type'] ?? '') === 'links'; ?>
                                    <div class="site-nav__group<?= $isLinksGroup ? ' site-nav__group--links' : '' ?>">
                                        <?php if (!empty($group['label'])): ?>
                                            <span class="site-nav__group-label"><?= pp_e($group['label']) ?></span>
                                        <?php endif; ?>

                                        <?php if ($isLinksGroup): ?>
                                            <div class="site-nav__links-row">
                                                <?php foreach ($group['items'] as $entry): ?>
                                                    <a href="<?= pp_e(pp_url($entry['href'])) ?>" class="site-nav__link-sm"><?= pp_e($entry['label']) ?></a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($group['items'] as $entry): ?>
                                                <a href="<?= pp_e(pp_url($entry['href'])) ?>" class="site-nav__entry">
                                                    <?php if (!empty($entry['icon'])): ?>
                                                        <span class="site-nav__entry-icon" aria-hidden="true"><?= pp_icon($entry['icon'], 18) ?></span>
                                                    <?php endif; ?>
                                                    <span class="site-nav__entry-text">
                                                        <span class="site-nav__entry-label"><?= pp_e($entry['label']) ?></span>
                                                        <?php if (!empty($entry['desc'])): ?>
                                                            <span class="site-nav__entry-desc"><?= pp_e($entry['desc']) ?></span>
                                                        <?php endif; ?>
                                                    </span>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <div class="flex items-center gap-2 ml-auto">
            <?= pp_render_component('site/theme-toggle') ?>

            <?php if ($isLoggedIn): ?>
                <div class="hidden sm:flex items-center gap-2">
                    <?php if ($isAdmin): ?>
                        <a href="<?= pp_e(pp_url('/admin')) ?>" class="btn btn--ghost btn--sm" title="Beheer">
                            <?= pp_icon('settings', 16) ?>
                            <span class="hidden lg:inline">Admin</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= pp_e(pp_url('/profile')) ?>" class="btn btn--ghost btn--sm">
                        <?= pp_icon('user', 16) ?>
                        <span class="hidden lg:inline"><?= pp_e($username) ?></span>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?= pp_e(pp_url('/login')) ?>" class="btn btn--ghost btn--sm hidden sm:inline-flex">
                    Inloggen
                </a>
                <a href="<?= pp_e(pp_url('/register')) ?>" class="btn btn--primary btn--sm hidden sm:inline-flex">
                    Aanmelden
                </a>
            <?php endif; ?>

            <button type="button"
                    class="mobile-menu-trigger"
                    aria-label="Menu openen"
                    aria-expanded="false"
                    aria-controls="pp-mobile-menu"
                    data-mobile-menu-trigger>
                <?= pp_icon('menu', 20) ?>
            </button>
        </div>
    </div>
</header>
