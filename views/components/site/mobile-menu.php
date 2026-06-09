<?php
/**
 * Mobile navigation drawer (rechts).
 *
 * Wordt geopend door .mobile-menu-trigger uit de site-header.
 * Communicatie via vanilla JS (data-mobile-menu-trigger / data-mobile-menu).
 */
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isAdmin();

$sections = [
    [
        'label' => 'Stemhulpen',
        'links' => [
            ['label' => 'PartijMeter',         'href' => '/partijmeter'],
            ['label' => 'Politiek Kompas',     'href' => '/politiek-kompas'],
            ['label' => 'Stemwijzer Ede 2026', 'href' => '/stemwijzer'],
        ],
    ],
    [
        'label' => 'Politiek',
        'links' => [
            ['label' => 'Partijen',       'href' => '/partijen'],
            ['label' => 'Stemmentracker', 'href' => '/stemmentracker'],
            ['label' => "Thema's",        'href' => '/themas'],
        ],
    ],
    [
        'label' => 'Lezen',
        'links' => [
            ['label' => 'Blogs',  'href' => '/blogs'],
            ['label' => 'Nieuws', 'href' => '/nieuws'],
        ],
    ],
    [
        'label' => 'Verkiezingen',
        'links' => [
            ['label' => 'Nederlandse verkiezingen', 'href' => '/nederlandse-verkiezingen'],
            ['label' => 'Amerikaanse verkiezingen', 'href' => '/amerikaanse-verkiezingen'],
        ],
    ],
    [
        'label' => 'Over',
        'links' => [
            ['label' => 'Over PolitiekPraat', 'href' => '/over-mij'],
            ['label' => 'Contact',            'href' => '/contact'],
            ['label' => 'Steun ons',          'href' => '/donatie'],
        ],
    ],
    [
        'label' => 'Juridisch',
        'links' => [
            ['label' => 'Privacy',          'href' => '/privacy-policy'],
            ['label' => 'Cookies',          'href' => '/cookie-policy'],
            ['label' => 'Toegankelijkheid', 'href' => '/toegankelijkheid'],
            ['label' => 'Voorwaarden',      'href' => '/gebruiksvoorwaarden'],
        ],
    ],
];
?>
<div id="pp-mobile-menu"
     class="mobile-menu"
     data-mobile-menu
     aria-hidden="true">
    <div class="mobile-menu__backdrop" data-mobile-menu-close></div>
    <aside class="mobile-menu__panel" role="dialog" aria-label="Hoofdmenu" aria-modal="true">
        <div class="mobile-menu__header">
            <span style="font-family: var(--font-display); font-weight: 700; font-size: 1.25rem; color: var(--color-ink);">
                <?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?>
            </span>
            <button type="button"
                    class="mobile-menu-trigger"
                    style="display: inline-flex;"
                    aria-label="Menu sluiten"
                    data-mobile-menu-close>
                <?= pp_icon('x', 20) ?>
            </button>
        </div>

        <nav class="mobile-menu__nav" aria-label="Hoofdmenu (mobiel)">
            <a href="<?= pp_e(pp_url('/')) ?>" class="mobile-menu__link">Home</a>
            <a href="<?= pp_e(pp_url('/midterms-2026')) ?>" class="mobile-menu__link mobile-menu__link--usa" style="font-size: 1.125rem;">
                <span class="mobile-menu__link-icon" aria-hidden="true"><?= pp_icon('flag', 16) ?></span>
                Midterms 2026 (VS)
            </a>

            <?php foreach ($sections as $section): ?>
                <h3 class="mobile-menu__section-label"><?= pp_e($section['label']) ?></h3>
                <?php foreach ($section['links'] as $link): ?>
                    <?php $isUsa = ($link['variant'] ?? null) === 'usa'; ?>
                    <a href="<?= pp_e(pp_url($link['href'])) ?>" class="mobile-menu__link<?= $isUsa ? ' mobile-menu__link--usa' : '' ?>" style="font-size: 1.125rem;">
                        <?php if ($isUsa && !empty($link['icon'])): ?><span class="mobile-menu__link-icon" aria-hidden="true"><?= pp_icon($link['icon'], 16) ?></span><?php endif; ?>
                        <?= pp_e($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php if ($isLoggedIn): ?>
                <h3 class="mobile-menu__section-label">Account</h3>
                <a href="<?= pp_e(pp_url('/profile')) ?>" class="mobile-menu__link" style="font-size: 1.125rem;">Mijn profiel</a>
                <a href="<?= pp_e(pp_url('/blogs/manage')) ?>" class="mobile-menu__link" style="font-size: 1.125rem;">Mijn blogs</a>
                <?php if ($isAdmin): ?>
                    <a href="<?= pp_e(pp_url('/admin')) ?>" class="mobile-menu__link" style="font-size: 1.125rem;">Beheer</a>
                <?php endif; ?>
                <a href="<?= pp_e(pp_url('/logout')) ?>" class="mobile-menu__link" style="font-size: 1.125rem; color: var(--color-terracotta);">Uitloggen</a>
            <?php else: ?>
                <h3 class="mobile-menu__section-label">Account</h3>
                <a href="<?= pp_e(pp_url('/login')) ?>" class="mobile-menu__link" style="font-size: 1.125rem;">Inloggen</a>
                <a href="<?= pp_e(pp_url('/register')) ?>" class="mobile-menu__link" style="font-size: 1.125rem;">Aanmelden</a>
            <?php endif; ?>
        </nav>
    </aside>
</div>
