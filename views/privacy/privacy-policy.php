<?php
$pageTitle = "Privacy Policy | PolitiekPraat";
$pageDescription = "Privacy Policy van PolitiekPraat - Hoe wij persoonlijke gegevens verzamelen, gebruiken en beschermen conform de AVG/GDPR.";
$pageKeywords = "privacy policy, AVG, GDPR, persoonlijke gegevens, cookies, PolitiekPraat";

require_once 'views/templates/header.php';

$lastUpdated = date('d F Y');

$sections = [
    'contact-info'    => 'Contactgegevens',
    'data-collection' => 'Welke gegevens verzamelen wij',
    'data-usage'      => 'Hoe wij gegevens gebruiken',
    'cookies'         => 'Cookies en tracking',
    'rights'          => 'Uw rechten',
    'security'        => 'Beveiliging',
    'retention'       => 'Bewaartermijnen en overdracht',
    'complaints'      => 'Klachten en wijzigingen',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Privacy',
    'title'   => 'Privacy Policy',
    'lead'    => 'Hoe PolitiekPraat persoonlijke gegevens verzamelt, gebruikt en beschermt conform de AVG en GDPR.',
    'meta'    => [
        ['icon' => 'shield-check', 'text' => 'AVG / GDPR compliant'],
        ['icon' => 'calendar',     'text' => 'Bijgewerkt: ' . $lastUpdated],
    ],
]) ?>

<main id="main-content" class="pp-container pp-container--wide py-12 md:py-16" tabindex="-1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <aside class="lg:col-span-3">
            <nav class="sticky top-24" aria-label="Privacy policy navigatie">
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
            <section class="prose-editorial max-w-none">
                <p class="text-lg leading-relaxed text-[color:var(--color-ink-muted)]">
                    PolitiekPraat ("wij", "ons", "onze") respecteert uw privacy en zet zich in voor de bescherming van uw persoonlijke gegevens. Deze Privacy Policy legt uit hoe wij uw gegevens verzamelen, gebruiken, delen en beschermen wanneer u onze website <strong>politiekpraat.nl</strong> bezoekt of onze diensten gebruikt.
                </p>
                <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                    Deze policy is van toepassing op alle bezoekers en gebruikers van onze website. Door gebruik te maken van politiekpraat.nl gaat u akkoord met de gegevenspraktijken die hieronder worden beschreven.
                </p>
                <div class="border-l-2 border-[color:var(--color-hague)] bg-[color:var(--color-hague-tint)] px-5 py-3 my-6 text-sm">
                    <strong class="text-[color:var(--color-hague)]">Laatst bijgewerkt:</strong>
                    <span class="text-[color:var(--color-ink-muted)]"><?= pp_e($lastUpdated) ?></span>
                </div>
            </section>

            <section id="contact-info" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">1. Contactgegevens van de verwerkingsverantwoordelijke</h2>
                    <div class="prose-editorial max-w-none">
                        <div class="bg-[color:var(--color-paper-2)] rounded-md p-5 border border-[color:var(--color-keyline)]">
                            <ul class="space-y-1.5 text-sm text-[color:var(--color-ink-muted)]">
                                <li><strong class="text-[color:var(--color-ink)]">Naam:</strong> PolitiekPraat</li>
                                <li><strong class="text-[color:var(--color-ink)]">Website:</strong> <a href="https://politiekpraat.nl" class="text-[color:var(--color-hague)] underline underline-offset-2">politiekpraat.nl</a></li>
                                <li><strong class="text-[color:var(--color-ink)]">Privacy-e-mail:</strong> <a href="mailto:privacy@politiekpraat.nl" class="text-[color:var(--color-hague)] underline underline-offset-2">privacy@politiekpraat.nl</a></li>
                                <li><strong class="text-[color:var(--color-ink)]">Algemeen:</strong> <a href="mailto:info@politiekpraat.nl" class="text-[color:var(--color-hague)] underline underline-offset-2">info@politiekpraat.nl</a></li>
                            </ul>
                        </div>
                        <p class="text-sm text-[color:var(--color-ink-muted)] mt-4">
                            Voor vragen over deze Privacy Policy of het gebruik van uw persoonlijke gegevens kunt u contact opnemen via bovenstaande gegevens.
                        </p>
                    </div>
                </div>
            </section>

            <section id="data-collection" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">2. Welke gegevens verzamelen wij</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>2.1 Gegevens die u vrijwillig verstrekt</h3>
                        <div class="not-prose grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
                            <div class="border-l-2 border-[color:var(--color-olive)] pl-4">
                                <h4 class="font-display text-base text-[color:var(--color-ink)] mb-2">Account-registratie</h4>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <li>Naam (voor- en achternaam)</li>
                                    <li>E-mailadres</li>
                                    <li>Wachtwoord (versleuteld)</li>
                                    <li>Profielfoto (optioneel)</li>
                                    <li>Biografie (optioneel)</li>
                                </ul>
                            </div>
                            <div class="border-l-2 border-[color:var(--color-hague)] pl-4">
                                <h4 class="font-display text-base text-[color:var(--color-ink)] mb-2">Communicatie</h4>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <li>Contactformulieren</li>
                                    <li>E-mails naar ons</li>
                                    <li>Nieuwsbrief-inschrijvingen</li>
                                    <li>Forum-reacties</li>
                                </ul>
                            </div>
                            <div class="border-l-2 border-[color:var(--color-ochre)] pl-4">
                                <h4 class="font-display text-base text-[color:var(--color-ink)] mb-2">Website-interactie</h4>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <li>Blog-reacties</li>
                                    <li>Stemwijzer-resultaten (geanonimiseerd)</li>
                                    <li>Poll-stemmen</li>
                                    <li>Likes en shares</li>
                                </ul>
                            </div>
                        </div>

                        <h3>2.2 Automatisch verzamelde gegevens</h3>
                        <div class="not-prose grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                            <div class="bg-[color:var(--color-paper-2)] rounded-md p-5 border border-[color:var(--color-keyline)]">
                                <h4 class="font-display text-base text-[color:var(--color-ink)] mb-2">Technische gegevens</h4>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <li>IP-adres (geanonimiseerd)</li>
                                    <li>Browser-type en versie</li>
                                    <li>Besturingssysteem</li>
                                    <li>Schermresolutie</li>
                                    <li>Taalvoorkeuren</li>
                                    <li>Tijdstip van bezoek</li>
                                </ul>
                            </div>
                            <div class="bg-[color:var(--color-paper-2)] rounded-md p-5 border border-[color:var(--color-keyline)]">
                                <h4 class="font-display text-base text-[color:var(--color-ink)] mb-2">Gebruiksgegevens</h4>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <li>Bezochte pagina's en volgorde</li>
                                    <li>Tijd op pagina's</li>
                                    <li>Klikgedrag en navigatie</li>
                                    <li>Zoektermen op onze site</li>
                                    <li>Verwijzende websites</li>
                                </ul>
                            </div>
                        </div>

                        <h3>2.3 Gegevens van derden</h3>
                        <p>Wij verzamelen geen persoonlijke gegevens van derde partijen zonder uw uitdrukkelijke toestemming. Indien u inlogt via een sociale media-platform (indien beschikbaar), ontvangen wij alleen gegevens die u ons toestaat te delen.</p>
                    </div>
                </div>
            </section>

            <section id="data-usage" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">3. Hoe wij uw gegevens gebruiken</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Wij gebruiken uw gegevens alleen voor legitieme doeleinden op basis van een geldige rechtsgrond volgens de AVG.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $usage = [
                            ['icon' => 'check-circle', 'title' => 'Dienstverlening',     'rg' => 'Contractuele verplichting',                       'items' => ['Account-beheer en authenticatie', 'Personalisatie van content', 'Opslaan van voorkeuren', 'Stemwijzer-functionaliteit']],
                            ['icon' => 'mail',         'title' => 'Communicatie',        'rg' => 'Toestemming / Gerechtvaardigd belang',           'items' => ['Beantwoorden van vragen', 'Nieuwsbrief-verzending', 'Belangrijke updates', 'Technische support']],
                            ['icon' => 'bar-chart-3',  'title' => 'Verbetering & analyse','rg' => 'Gerechtvaardigd belang',                         'items' => ['Website-prestaties meten', 'Gebruikerservaring optimaliseren', 'Content afstemmen', 'Technische problemen oplossen']],
                            ['icon' => 'shield',       'title' => 'Veiligheid & naleving','rg' => 'Gerechtvaardigd belang / Wettelijke verplichting','items' => ['Fraude-detectie', 'Spam-voorkoming', 'Wettelijke verplichtingen', 'Beveiliging van accounts']],
                        ];
                        foreach ($usage as $u): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5 bg-[color:var(--color-paper)]">
                                <div class="flex items-center gap-2 text-[color:var(--color-hague)] mb-2"><?= pp_icon($u['icon'], 18) ?><h3 class="font-display text-base text-[color:var(--color-ink)] m-0"><?= pp_e($u['title']) ?></h3></div>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 mb-3 list-disc list-inside">
                                    <?php foreach ($u['items'] as $it): ?><li><?= pp_e($it) ?></li><?php endforeach; ?>
                                </ul>
                                <p class="text-xs text-[color:var(--color-ink-faint)] m-0"><strong>Rechtsgrond:</strong> <?= pp_e($u['rg']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-ochre)] bg-[color:var(--color-ochre-tint)] px-5 py-3 mt-6 text-sm text-[color:var(--color-ink)]">
                        <strong>Belangrijk:</strong> Wij verkopen, verhuren of delen uw persoonlijke gegevens nooit met derde partijen voor commerciele doeleinden zonder uw uitdrukkelijke toestemming.
                    </div>
                </div>
            </section>

            <section id="cookies" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">4. Cookies en tracking-technologieen</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Onze website gebruikt cookies en vergelijkbare technologieen om uw ervaring te verbeteren en onze diensten te optimaliseren. U heeft controle over welke cookies worden geplaatst.</p>
                    </div>
                    <div class="space-y-4 mt-4">
                        <?php
                        $cookieTypes = [
                            ['color' => 'olive',      'title' => 'Noodzakelijke cookies (altijd actief)', 'desc' => 'Essentieel voor het functioneren van onze website. Kunnen niet worden uitgeschakeld.', 'items' => ['Sessie-cookies voor inloggen', 'Beveiligings-tokens', 'Taalvoorkeuren', 'Cookie-consent voorkeuren'], 'term' => 'Sessie of 1 jaar'],
                            ['color' => 'hague',      'title' => 'Analytische cookies (optioneel)',       'desc' => 'Helpen ons begrijpen hoe bezoekers de website gebruiken.',                                  'items' => ['Bezoekersaantallen', 'Populaire content', 'Gebruikspatronen', 'Geanonimiseerde IP-adressen'], 'term' => '2 jaar'],
                            ['color' => 'terracotta', 'title' => 'Marketing-cookies (optioneel)',         'desc' => 'Worden gebruikt voor het tonen van relevante advertenties en content.',                       'items' => ['Interesse-profielen', 'Content-personalisatie', 'Social media-integratie'], 'term' => '1 jaar'],
                        ];
                        foreach ($cookieTypes as $c): ?>
                            <div class="border-l-2 border-[color:var(--color-<?= pp_e($c['color']) ?>)] bg-[color:var(--color-<?= pp_e($c['color']) ?>-tint)] p-5 rounded-r">
                                <h3 class="font-display text-base text-[color:var(--color-ink)] mb-2"><?= pp_e($c['title']) ?></h3>
                                <p class="text-sm text-[color:var(--color-ink-muted)] mb-2"><?= pp_e($c['desc']) ?></p>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside mb-2">
                                    <?php foreach ($c['items'] as $it): ?><li><?= pp_e($it) ?></li><?php endforeach; ?>
                                </ul>
                                <p class="text-xs text-[color:var(--color-ink-faint)]"><strong>Bewaartermijn:</strong> <?= pp_e($c['term']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mt-6">
                        Zie ook ons uitgebreide <a href="<?= pp_e(URLROOT) ?>/cookie-policy" class="text-[color:var(--color-hague)] underline underline-offset-2">cookie-overzicht</a> voor instellingen.
                    </p>
                </div>
            </section>

            <section id="rights" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">5. Uw rechten onder de AVG</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Onder de Algemene Verordening Gegevensbescherming heeft u verschillende rechten met betrekking tot uw persoonlijke gegevens.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $rights = [
                            ['icon' => 'eye',       'title' => 'Recht op inzage',                'desc' => 'U heeft het recht om te weten welke persoonlijke gegevens wij over u verwerken.'],
                            ['icon' => 'edit-3',    'title' => 'Recht op rectificatie',           'desc' => 'U kunt onjuiste of onvolledige gegevens laten corrigeren.'],
                            ['icon' => 'trash-2',   'title' => 'Recht op verwijdering',           'desc' => 'In bepaalde gevallen kunt u verzoeken om verwijdering van uw gegevens ("right to be forgotten").'],
                            ['icon' => 'pause',     'title' => 'Recht op beperking',              'desc' => 'U kunt vragen om de verwerking van uw gegevens tijdelijk te beperken.'],
                            ['icon' => 'download',  'title' => 'Recht op overdraagbaarheid',      'desc' => 'U kunt uw gegevens in een gestructureerd formaat opvragen voor overdracht.'],
                            ['icon' => 'shield-off','title' => 'Recht van bezwaar',               'desc' => 'U kunt bezwaar maken tegen verwerking op basis van gerechtvaardigd belang.'],
                        ];
                        foreach ($rights as $r): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5 bg-[color:var(--color-paper)]">
                                <div class="flex items-center gap-2 text-[color:var(--color-hague)] mb-2"><?= pp_icon($r['icon'], 18) ?><h3 class="font-display text-base text-[color:var(--color-ink)] m-0"><?= pp_e($r['title']) ?></h3></div>
                                <p class="text-sm text-[color:var(--color-ink-muted)]"><?= pp_e($r['desc']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mt-6">
                        Om gebruik te maken van uw rechten kunt u contact opnemen via <a href="mailto:privacy@politiekpraat.nl" class="text-[color:var(--color-hague)] underline underline-offset-2">privacy@politiekpraat.nl</a>. Wij reageren binnen 30 dagen op uw verzoek.
                    </p>
                </div>
            </section>

            <section id="security" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">6. Beveiliging van uw gegevens</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Wij nemen passende technische en organisatorische maatregelen om uw persoonlijke gegevens te beschermen tegen ongeautoriseerde toegang, verlies, vernietiging of wijziging.</p>
                        <h3>Technische maatregelen</h3>
                        <ul>
                            <li>SSL/TLS-encryptie voor alle datatransmissie</li>
                            <li>Versleutelde opslag van wachtwoorden (bcrypt)</li>
                            <li>Regelmatige beveiligingsupdates</li>
                            <li>Firewall en intrusion detection</li>
                            <li>Periodieke backups</li>
                        </ul>
                        <h3>Organisatorische maatregelen</h3>
                        <ul>
                            <li>Toegangscontrole en logging</li>
                            <li>Geheimhoudingsplichten voor medewerkers</li>
                            <li>Privacy by design en privacy by default</li>
                            <li>Periodieke privacy-audits</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="retention" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">7. Bewaartermijnen en internationale overdracht</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Bewaartermijnen</h3>
                        <p>Wij bewaren uw gegevens niet langer dan noodzakelijk voor de doeleinden waarvoor ze zijn verzameld:</p>
                        <ul>
                            <li><strong>Account-gegevens:</strong> tot opzegging plus 30 dagen</li>
                            <li><strong>Communicatie:</strong> 3 jaar</li>
                            <li><strong>Analytics:</strong> 26 maanden (geanonimiseerd)</li>
                            <li><strong>Logbestanden:</strong> 12 maanden</li>
                        </ul>
                        <h3>Internationale overdracht</h3>
                        <p>Uw gegevens worden hoofdzakelijk binnen de Europese Economische Ruimte (EER) verwerkt. Indien gegevens buiten de EER worden overgedragen, zorgen wij voor passende waarborgen via standaardcontractbepalingen of een adequaatheidsbesluit.</p>
                    </div>
                </div>
            </section>

            <section id="complaints" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">8. Klachten en wijzigingen</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Klachten</h3>
                        <p>Indien u een klacht heeft over de manier waarop wij met uw persoonlijke gegevens omgaan, kunt u contact opnemen via <a href="mailto:privacy@politiekpraat.nl">privacy@politiekpraat.nl</a>. U heeft daarnaast het recht om een klacht in te dienen bij de <a href="https://autoriteitpersoonsgegevens.nl" target="_blank" rel="noopener">Autoriteit Persoonsgegevens</a>.</p>
                        <h3>Wijzigingen</h3>
                        <p>Wij kunnen deze Privacy Policy van tijd tot tijd aanpassen. De laatste versie staat altijd op deze pagina. Bij belangrijke wijzigingen informeren wij u via e-mail of een melding op de website.</p>
                        <p class="text-sm text-[color:var(--color-ink-faint)] mt-2"><strong>Advies:</strong> Controleer deze pagina regelmatig voor updates.</p>
                    </div>
                </div>
            </section>

            <div class="keyline-card p-8 bg-[color:var(--color-paper-2)] text-center">
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight">Vragen over privacy?</h2>
                <p class="text-[color:var(--color-ink-muted)] mb-5">We beantwoorden privacy-vragen graag persoonlijk.</p>
                <a href="mailto:privacy@politiekpraat.nl" class="btn btn--primary">
                    <?= pp_icon('mail', 14) ?>
                    Stuur een e-mail
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
