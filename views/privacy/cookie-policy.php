<?php
$pageTitle = "Cookie Policy | PolitiekPraat";
$pageDescription = "Cookie Policy van PolitiekPraat - Hoe wij cookies gebruiken en hoe je je voorkeuren beheert.";
$pageKeywords = "cookie policy, cookies, tracking, privacy, voorkeuren";

require_once 'views/templates/header.php';

$lastUpdated = date('d F Y');

$sections = [
    'what-are-cookies'  => 'Wat zijn cookies',
    'cookie-types'      => 'Soorten cookies',
    'cookie-management' => 'Voorkeuren beheren',
    'third-party'       => 'Derde partijen',
    'contact-cookies'   => 'Contact en updates',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Cookies',
    'title'   => 'Cookie Policy',
    'lead'    => 'Welke cookies wij gebruiken, waarom we ze nodig hebben en hoe jij ze beheert.',
    'meta'    => [
        ['icon' => 'cookie',   'text' => 'Transparant cookiebeleid'],
        ['icon' => 'calendar', 'text' => 'Bijgewerkt: ' . $lastUpdated],
    ],
]) ?>

<main id="main-content" class="pp-container pp-container--wide py-12 md:py-16" tabindex="-1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <aside class="lg:col-span-3">
            <nav class="sticky top-24" aria-label="Cookie policy navigatie">
                <div class="eyebrow mb-3">Inhoud</div>
                <ol class="space-y-2 text-sm">
                    <?php $i = 1; foreach ($sections as $id => $label): ?>
                        <li>
                            <a href="#<?= pp_e($id) ?>" class="block py-1.5 text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-hague)] transition-colors">
                                <span class="font-mono text-tabular text-[color:var(--color-ink-faint)] mr-2"><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
                                <?= pp_e($label) ?>
                            </a>
                        </li>
                    <?php $i++; endforeach; ?>
                </ol>
            </nav>
        </aside>

        <div class="lg:col-span-9 space-y-10">
            <section id="what-are-cookies" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">Wat zijn cookies?</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Cookies zijn kleine tekstbestanden die op je apparaat worden geplaatst wanneer je onze website bezoekt. Ze helpen ons om je voorkeuren te onthouden, je ervaring te personaliseren en onze website te verbeteren.</p>
                        <p>Deze Cookie Policy legt uit welke cookies wij gebruiken, waarom wij ze gebruiken en hoe je je cookie-voorkeuren kunt beheren.</p>
                        <div class="border-l-2 border-[color:var(--color-hague)] bg-[color:var(--color-hague-tint)] px-5 py-3 my-6 text-sm not-prose">
                            <strong class="text-[color:var(--color-hague)]">Laatst bijgewerkt:</strong>
                            <span class="text-[color:var(--color-ink-muted)]"><?= pp_e($lastUpdated) ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cookie-types" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-6 leading-tight">Soorten cookies die wij gebruiken</h2>
                    <div class="space-y-5">
                        <?php
                        $types = [
                            [
                                'color' => 'olive',
                                'title' => 'Noodzakelijke cookies',
                                'badge' => 'Altijd actief',
                                'desc'  => 'Deze cookies zijn essentieel voor het functioneren van onze website. Zonder deze cookies kunnen bepaalde delen niet correct werken.',
                                'groups' => [
                                    ['title' => 'Sessie-management',  'items' => ['Inlogstatus onthouden', 'Winkelwagen-beheer', 'Formuliergegevens bewaren'], 'term' => 'Tot einde sessie'],
                                    ['title' => 'Beveiliging',        'items' => ['CSRF-bescherming', 'Beveiligings-tokens', 'Anti-spam maatregelen'], 'term' => '24 uur'],
                                ],
                            ],
                            [
                                'color' => 'hague',
                                'title' => 'Analytische cookies',
                                'badge' => 'Optioneel',
                                'desc'  => 'Helpen ons begrijpen hoe bezoekers onze website gebruiken via anonieme informatie over gebruikersgedrag.',
                                'groups' => [
                                    ['title' => 'Google Analytics',   'items' => ['_ga - unieke bezoekers identificeren', '_gid - sessie-informatie', '_gat - request rate-beperking'], 'term' => '2 jaar'],
                                    ['title' => 'Interne analytics',  'items' => ['Pagina-weergave tracking', 'Gebruikersstroom-analyse', 'Performance-metingen'], 'term' => '1 jaar'],
                                ],
                            ],
                            [
                                'color' => 'moss',
                                'title' => 'Functionele cookies',
                                'badge' => 'Optioneel',
                                'desc'  => 'Onthouden je voorkeuren en instellingen om je ervaring te personaliseren en te verbeteren.',
                                'groups' => [
                                    ['title' => 'Voorkeuren',         'items' => ['Taalvoorkeur', 'Thema (donker / licht)', 'Lettergrootte-instellingen'], 'term' => '1 jaar'],
                                    ['title' => 'Toegankelijkheid',   'items' => ['High contrast-modus', 'Reduced motion', 'Toetsenbord-navigatievoorkeur'], 'term' => '1 jaar'],
                                ],
                            ],
                            [
                                'color' => 'ochre',
                                'title' => 'Marketing-cookies',
                                'badge' => 'Optioneel',
                                'desc'  => 'Worden gebruikt voor relevante advertenties en het meten van marketing-effectiviteit.',
                                'groups' => [
                                    ['title' => 'Advertentie-tracking','items' => ['Google Ads conversie-tracking', 'Facebook Pixel', 'Retargeting-cookies'], 'term' => '90 dagen'],
                                    ['title' => 'Social media',       'items' => ['Social media-integraties', 'Share-functionaliteit', 'Embedded content'], 'term' => '30 dagen'],
                                ],
                            ],
                        ];
                        foreach ($types as $t):
                        ?>
                            <div class="border-l-2 border-[color:var(--color-<?= pp_e($t['color']) ?>)] bg-[color:var(--color-<?= pp_e($t['color']) ?>-tint)] rounded-r p-5">
                                <div class="flex items-center justify-between mb-2 gap-3 flex-wrap">
                                    <h3 class="font-display text-lg text-[color:var(--color-ink)] m-0"><?= pp_e($t['title']) ?></h3>
                                    <span class="badge badge--<?= pp_e($t['color']) ?>"><?= pp_e($t['badge']) ?></span>
                                </div>
                                <p class="text-sm text-[color:var(--color-ink-muted)] mb-4"><?= pp_e($t['desc']) ?></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php foreach ($t['groups'] as $g): ?>
                                        <div class="bg-[color:var(--color-paper)] border border-[color:var(--color-keyline)] rounded p-4">
                                            <h4 class="font-display text-sm text-[color:var(--color-ink)] mb-2"><?= pp_e($g['title']) ?></h4>
                                            <ul class="text-xs text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside mb-2">
                                                <?php foreach ($g['items'] as $it): ?><li><?= pp_e($it) ?></li><?php endforeach; ?>
                                            </ul>
                                            <p class="text-xs text-[color:var(--color-ink-faint)] m-0">Bewaartermijn: <?= pp_e($g['term']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="cookie-management" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">Je cookie-voorkeuren beheren</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Je hebt volledige controle over welke cookies op je apparaat worden geplaatst. Hieronder vind je verschillende manieren om je voorkeuren te beheren.</p>
                    </div>
                    <div class="border border-[color:var(--color-keyline)] rounded-md p-5 mt-4">
                        <h3 class="font-display text-base text-[color:var(--color-ink)] mb-3 flex items-center gap-2">
                            <?= pp_icon('settings', 18) ?> Via je browser
                        </h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)] mb-3">De meeste browsers bieden opties om cookies te beheren:</p>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                            <?php
                            $browsers = [
                                'Chrome'  => 'Instellingen -> Privacy',
                                'Firefox' => 'Opties -> Privacy',
                                'Safari'  => 'Voorkeuren -> Privacy',
                                'Edge'    => 'Instellingen -> Privacy',
                            ];
                            foreach ($browsers as $browser => $path): ?>
                                <div class="flex items-center justify-between border-b border-[color:var(--color-keyline)] py-1.5">
                                    <dt class="text-[color:var(--color-ink)] font-medium"><?= pp_e($browser) ?></dt>
                                    <dd class="text-[color:var(--color-hague)] font-mono text-xs"><?= pp_e($path) ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>

                    <div class="border-l-2 border-[color:var(--color-ochre)] bg-[color:var(--color-ochre-tint)] px-5 py-4 mt-5 rounded-r">
                        <h3 class="font-display text-base text-[color:var(--color-ink)] mb-2 flex items-center gap-2">
                            <?= pp_icon('alert-triangle', 18) ?> Belangrijk om te weten
                        </h3>
                        <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                            <li>Het uitschakelen van cookies kan de functionaliteit van onze website beperken</li>
                            <li>Noodzakelijke cookies kunnen niet worden uitgeschakeld</li>
                            <li>Je voorkeuren worden onthouden voor toekomstige bezoeken</li>
                            <li>Je kunt je keuzes op elk moment wijzigen</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="third-party" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">Cookies van derde partijen</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Sommige cookies op onze website worden geplaatst door derde partijen. Hieronder een overzicht.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $third = [
                            [
                                'name'    => 'Google Analytics',
                                'desc'    => 'Voor het analyseren van websiteverkeer en gebruikersgedrag.',
                                'fields'  => ['Cookie-namen' => '_ga, _gid, _gat', 'Bewaartermijn' => '2 jaar'],
                                'url'     => 'https://policies.google.com/privacy',
                                'urlText' => 'Google Privacy',
                            ],
                            [
                                'name'    => 'Facebook Pixel',
                                'desc'    => 'Voor het meten van advertentie-effectiviteit en retargeting.',
                                'fields'  => ['Cookie-namen' => '_fbp, fbq', 'Bewaartermijn' => '90 dagen'],
                                'url'     => 'https://www.facebook.com/privacy/explanation',
                                'urlText' => 'Facebook Privacy',
                            ],
                        ];
                        foreach ($third as $t): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5">
                                <h3 class="font-display text-base text-[color:var(--color-ink)] mb-2"><?= pp_e($t['name']) ?></h3>
                                <p class="text-sm text-[color:var(--color-ink-muted)] mb-3"><?= pp_e($t['desc']) ?></p>
                                <dl class="space-y-1.5 text-sm">
                                    <?php foreach ($t['fields'] as $k => $v): ?>
                                        <div class="flex justify-between">
                                            <dt class="text-[color:var(--color-ink-muted)]"><?= pp_e($k) ?>:</dt>
                                            <dd class="text-[color:var(--color-ink)] font-mono text-xs"><?= pp_e($v) ?></dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                                <a href="<?= pp_e($t['url']) ?>" target="_blank" rel="noopener" class="text-sm text-[color:var(--color-hague)] underline underline-offset-2 mt-3 inline-flex items-center gap-1">
                                    <?= pp_e($t['urlText']) ?> <?= pp_icon('external-link', 12) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-l-2 border-[color:var(--color-hague)] bg-[color:var(--color-hague-tint)] px-5 py-4 mt-5 rounded-r">
                        <h3 class="font-display text-base text-[color:var(--color-ink)] mb-2">Opt-out opties</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)] mb-3">Voor derde partij cookies kun je ook direct opt-out via deze links:</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener" class="btn btn--ghost text-sm">
                                Google Analytics opt-out
                            </a>
                            <a href="https://www.facebook.com/ads/preferences" target="_blank" rel="noopener" class="btn btn--ghost text-sm">
                                Facebook Ads opt-out
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact-cookies" tabindex="-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="keyline-card p-6 md:p-8">
                        <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-3 leading-tight">Vragen over cookies?</h2>
                        <p class="text-sm text-[color:var(--color-ink-muted)] mb-4">Heb je vragen over onze cookies of wil je meer informatie? Neem dan contact met ons op.</p>
                        <a href="mailto:privacy@politiekpraat.nl" class="btn btn--primary">
                            <?= pp_icon('mail', 14) ?>
                            Stuur een e-mail
                        </a>
                    </div>
                    <div class="keyline-card p-6 md:p-8">
                        <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-3 leading-tight">Updates</h2>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">We kunnen deze Cookie Policy af en toe aanpassen. De laatste versie staat altijd op deze pagina. Bij belangrijke wijzigingen informeren we je via een melding op de site.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
