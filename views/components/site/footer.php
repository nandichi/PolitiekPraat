<?php
/**
 * Editorial site footer (4-koloms, geen golf-SVG, geen gradient-balk).
 */
$year = (int) date('Y');
$displayYear = max($year, 2026);

$navColumns = [
    [
        'title' => 'Stemhulpen',
        'links' => [
            ['label' => 'Partijmeter',             'href' => '/partijmeter'],
            ['label' => 'Politiek Kompas',         'href' => '/politiek-kompas'],
            ['label' => 'Stemwijzer Ede 2026',     'href' => '/stemwijzer'],
            ['label' => 'Amerikaanse verkiezingen','href' => '/amerikaanse-verkiezingen'],
            ['label' => 'Nederlandse verkiezingen','href' => '/nederlandse-verkiezingen'],
        ],
    ],
    [
        'title' => 'Politiek',
        'links' => [
            ['label' => 'Partijen',        'href' => '/partijen'],
            ['label' => 'Stemmentracker',  'href' => '/stemmentracker'],
            ['label' => 'Thema\'s',        'href' => '/themas'],
            ['label' => 'Blogs',           'href' => '/blogs'],
            ['label' => 'Nieuws',          'href' => '/nieuws'],
        ],
    ],
    [
        'title' => 'Platform',
        'links' => [
            ['label' => 'Over PolitiekPraat', 'href' => '/over-mij'],
            ['label' => 'Contact',            'href' => '/contact'],
            ['label' => 'Steun ons',          'href' => '/donatie'],
            ['label' => 'Privacy',            'href' => '/privacy-policy'],
            ['label' => 'Cookies',            'href' => '/cookie-policy'],
            ['label' => 'Toegankelijkheid',   'href' => '/toegankelijkheid'],
            ['label' => 'Voorwaarden',        'href' => '/gebruiksvoorwaarden'],
        ],
    ],
];

$socials = [
    ['icon' => 'instagram', 'href' => 'https://www.instagram.com/politiekpraat/',     'label' => 'Instagram'],
    ['icon' => 'twitter',   'href' => 'https://x.com/PolitiekPraatNL',                'label' => 'X (Twitter)'],
    ['icon' => 'linkedin',  'href' => 'https://www.linkedin.com/in/naoufalandichi/',  'label' => 'LinkedIn'],
];
?>
<footer class="site-footer" role="contentinfo">
    <div class="pp-container">
        <div class="site-footer__columns">
            <div>
                <p class="site-footer__brand-title"><?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?></p>
                <p class="site-footer__tagline">
                    Een platform voor heldere Nederlandse politiek. We brengen burgers en politiek
                    dichter bij elkaar door uitleg in normale taal, eerlijke duiding en open gesprek.
                </p>
                <div class="site-footer__socials">
                    <?php foreach ($socials as $social): ?>
                        <a href="<?= pp_e($social['href']) ?>"
                           class="site-footer__social"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="<?= pp_e($social['label']) ?>">
                            <?= pp_icon($social['icon'], 18) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php foreach ($navColumns as $column): ?>
                <div>
                    <h4 class="site-footer__column-title"><?= pp_e($column['title']) ?></h4>
                    <ul class="site-footer__links">
                        <?php foreach ($column['links'] as $link): ?>
                            <li>
                                <a href="<?= pp_e(pp_url($link['href'])) ?>" class="site-footer__link">
                                    <?= pp_e($link['label']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="site-footer__bottom">
            <p>&copy; <?= $displayYear ?> <?= pp_e(defined('SITENAME') ? SITENAME : 'PolitiekPraat') ?>. Onafhankelijk en advertentievrij gehouden door lezers.</p>
            <p>
                Gemaakt door
                <a href="https://www.linkedin.com/in/naoufalandichi/" class="site-footer__link" style="text-decoration: underline; text-underline-offset: 3px;" target="_blank" rel="noopener noreferrer">
                    Naoufal Andichi
                </a>
            </p>
        </div>
    </div>
</footer>
