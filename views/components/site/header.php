<?php
/**
 * Editorial site header met sticky navigation.
 *
 * Verwacht globale URLROOT + SITENAME + optionele $_SESSION.
 * `getCurrentPageContext()` wordt gebruikt voor active-state.
 */
$context = function_exists('getCurrentPageContext') ? getCurrentPageContext() : ['section' => ''];
$section = $context['section'] ?? '';

$navLinks = [
    ['label' => 'Verkiezingen',  'href' => '/partijmeter',           'match' => ['partijmeter','politiek-kompas','amerikaanse-verkiezingen','nederlandse-verkiezingen','resultaten','stemwijzer']],
    ['label' => 'Partijen',      'href' => '/partijen',              'match' => ['partijen']],
    ['label' => 'Thema\'s',      'href' => '/themas',                'match' => ['themas','thema']],
    ['label' => 'Blogs',         'href' => '/blogs',                 'match' => ['blogs']],
    ['label' => 'Nieuws',        'href' => '/nieuws',                'match' => ['nieuws']],
];

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isAdmin();
$username = $_SESSION['username'] ?? '';
?>
<header class="site-header" role="banner">
    <div class="pp-container site-header__inner">
        <a href="<?= pp_e(pp_url('/')) ?>" class="site-header__brand" aria-label="<?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?> - home">
            <?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?>
        </a>

        <nav class="site-nav" aria-label="Hoofdnavigatie">
            <?php foreach ($navLinks as $link): ?>
                <?php $isActive = in_array($section, $link['match'], true); ?>
                <a href="<?= pp_e(pp_url($link['href'])) ?>"
                   class="site-nav__link"
                   <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <?= pp_e($link['label']) ?>
                </a>
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
