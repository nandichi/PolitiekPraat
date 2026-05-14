<?php
$pageTitle = "Gebruiksvoorwaarden | PolitiekPraat";
$pageDescription = "Gebruiksvoorwaarden van PolitiekPraat - De regels en voorwaarden voor het gebruik van onze website.";
$pageKeywords = "gebruiksvoorwaarden, terms of service, regels, voorwaarden";

require_once 'views/templates/header.php';

$lastUpdated = date('d F Y');

$sections = [
    'acceptance'  => 'Acceptatie van de voorwaarden',
    'services'    => 'Onze diensten',
    'user-conduct'=> 'Gebruikersgedrag',
    'content'     => 'Content en intellectueel eigendom',
    'liability'   => 'Aansprakelijkheid',
    'changes'     => 'Wijzigingen en beeindiging',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Gebruiksvoorwaarden',
    'title'   => 'Gebruiksvoorwaarden',
    'lead'    => 'De regels en voorwaarden voor het gebruik van PolitiekPraat.',
    'meta'    => [
        ['icon' => 'file-text', 'text' => 'Nederlandse versie'],
        ['icon' => 'calendar',  'text' => 'Bijgewerkt: ' . $lastUpdated],
    ],
]) ?>

<main id="main-content" class="pp-container pp-container--wide py-12 md:py-16" tabindex="-1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <aside class="lg:col-span-3">
            <nav class="sticky top-24" aria-label="Gebruiksvoorwaarden navigatie">
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
                    Welkom bij PolitiekPraat. Deze gebruiksvoorwaarden ("Voorwaarden") regelen het gebruik van onze website <strong>politiekpraat.nl</strong>. Door onze website te gebruiken, ga je akkoord met deze voorwaarden.
                </p>
                <div class="border-l-2 border-[color:var(--color-ochre)] bg-[color:var(--color-ochre-tint)] px-5 py-3 my-6 text-sm not-prose">
                    <strong class="text-[color:var(--color-ink)]">Belangrijk:</strong>
                    <span class="text-[color:var(--color-ink-muted)]">Lees deze voorwaarden zorgvuldig door voordat je onze website gebruikt. Bij vragen kun je altijd contact met ons opnemen.</span>
                </div>
            </section>

            <section id="acceptance" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">1. Acceptatie van de voorwaarden</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Door toegang tot of gebruik van onze website ga je akkoord met deze gebruiksvoorwaarden. Als je niet akkoord gaat met enige bepaling, dien je de website niet te gebruiken.</p>
                        <h3>Wijzigingen in voorwaarden</h3>
                        <p>We kunnen deze voorwaarden van tijd tot tijd bijwerken. Belangrijke wijzigingen worden vooraf gecommuniceerd via:</p>
                        <ul>
                            <li>E-mail naar geregistreerde gebruikers</li>
                            <li>Melding op onze website</li>
                            <li>Update van de "Laatst bijgewerkt"-datum</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="services" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">2. Onze diensten</h2>
                    <div class="prose-editorial max-w-none">
                        <p>PolitiekPraat biedt een platform voor politieke discussie, informatie en analyse.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $services = [
                            ['icon' => 'book-open',      'title' => 'Nieuws en analyses',       'desc' => 'Actuele politieke artikelen, analyses en duiding.'],
                            ['icon' => 'list-checks',    'title' => 'Stemwijzer en tools',      'desc' => 'Interactieve hulpmiddelen om je politieke voorkeuren te bepalen.'],
                            ['icon' => 'users',          'title' => 'Community-features',       'desc' => 'Account-functionaliteit, reacties en interactie tussen gebruikers.'],
                            ['icon' => 'pen-tool',       'title' => 'Blogs en columns',         'desc' => 'Politieke blogs en analyses geschreven door experts en gastbloggers.'],
                        ];
                        foreach ($services as $s): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5">
                                <div class="flex items-center gap-2 text-[color:var(--color-hague)] mb-2"><?= pp_icon($s['icon'], 18) ?><h3 class="font-display text-base text-[color:var(--color-ink)] m-0"><?= pp_e($s['title']) ?></h3></div>
                                <p class="text-sm text-[color:var(--color-ink-muted)] m-0"><?= pp_e($s['desc']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="prose-editorial max-w-none mt-6">
                        <p class="text-sm">We behouden ons het recht voor om diensten te wijzigen, op te schorten of stop te zetten met of zonder voorafgaande kennisgeving.</p>
                    </div>
                </div>
            </section>

            <section id="user-conduct" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">3. Gebruikersgedrag</h2>
                    <div class="prose-editorial max-w-none">
                        <p>We verwachten dat alle gebruikers zich respectvol en verantwoordelijk gedragen.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="border-l-2 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] p-5 rounded-r">
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-3 flex items-center gap-2"><?= pp_icon('check-circle', 18) ?> Wat we wel waarderen</h3>
                            <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                <li>Respectvolle discussies</li>
                                <li>Onderbouwde meningen</li>
                                <li>Constructieve kritiek</li>
                                <li>Diverse perspectieven</li>
                                <li>Feitelijke informatie</li>
                                <li>Open dialoog</li>
                            </ul>
                        </div>
                        <div class="border-l-2 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] p-5 rounded-r">
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-3 flex items-center gap-2"><?= pp_icon('x-circle', 18) ?> Niet toegestaan</h3>
                            <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                <li>Beledigingen of bedreigingen</li>
                                <li>Discriminatie of haat-zaaien</li>
                                <li>Spam of commerciele reclame</li>
                                <li>Illegale activiteiten</li>
                                <li>Auteursrechtschending</li>
                                <li>Verspreiding van misinformatie</li>
                            </ul>
                        </div>
                    </div>
                    <div class="prose-editorial max-w-none mt-6">
                        <h3>Account-verantwoordelijkheden</h3>
                        <ul>
                            <li>Verstrek accurate en actuele informatie</li>
                            <li>Bewaak de veiligheid van je inloggegevens</li>
                            <li>Meld onmiddellijk eventuele beveiligingsincidenten</li>
                            <li>Gebruik je account alleen voor wettige doeleinden</li>
                        </ul>
                        <h3>Sancties bij overtreding</h3>
                        <p>Bij overtreding van deze voorwaarden behouden wij ons het recht voor om accounts tijdelijk of permanent te schorsen, content te verwijderen en juridische stappen te overwegen.</p>
                    </div>
                </div>
            </section>

            <section id="content" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">4. Content en intellectueel eigendom</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Onze content</h3>
                        <p>Alle content op PolitiekPraat, inclusief teksten, beelden, logo's en software, is eigendom van PolitiekPraat of haar licentiegevers en wordt beschermd door auteursrecht en andere intellectuele eigendomsrechten.</p>
                        <h3>Gebruikersgegenereerde content</h3>
                        <p>Door content te plaatsen op onze website verleen je ons een niet-exclusieve, royaltyvrije licentie om die content te gebruiken, te wijzigen en te tonen in het kader van onze diensten. Je behoudt het auteursrecht op je eigen bijdragen.</p>
                        <h3>Citaatrecht</h3>
                        <p>Je mag korte fragmenten van onze content citeren met bronvermelding en link naar de oorspronkelijke pagina, conform Nederlands citaatrecht.</p>
                        <h3>Inbreuk melden</h3>
                        <p>Vermoed je dat content op onze website inbreuk maakt op auteursrecht? Stuur dan een onderbouwde melding naar <a href="mailto:juridisch@politiekpraat.nl">juridisch@politiekpraat.nl</a>.</p>
                    </div>
                </div>
            </section>

            <section id="liability" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">5. Aansprakelijkheid</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Informatie en advies</h3>
                        <p>PolitiekPraat biedt informatie en analyses, maar geen professioneel juridisch, financieel of politiek advies. Gebruik onze content op eigen verantwoordelijkheid.</p>
                        <h3>Beschikbaarheid</h3>
                        <p>We streven naar continue beschikbaarheid maar kunnen geen 100% uptime garanderen. We zijn niet aansprakelijk voor tijdelijke storingen of onderbrekingen.</p>
                        <h3>Externe links</h3>
                        <p>Onze website bevat links naar externe websites. We zijn niet verantwoordelijk voor de inhoud, het privacybeleid of de praktijken van deze externe sites.</p>
                        <h3>Beperking van aansprakelijkheid</h3>
                        <p>Voor zover wettelijk toegestaan is onze aansprakelijkheid beperkt tot het bedrag dat je in de afgelopen 12 maanden voor onze diensten hebt betaald. Indirecte of gevolgschade is uitgesloten.</p>
                    </div>
                </div>
            </section>

            <section id="changes" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">6. Wijzigingen en beeindiging</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Beeindiging door jou</h3>
                        <p>Je kunt je account op elk moment beeindigen door contact met ons op te nemen of via de account-instellingen.</p>
                        <h3>Beeindiging door ons</h3>
                        <p>We kunnen accounts opschorten of beeindigen bij schending van deze voorwaarden, langdurige inactiviteit of om operationele redenen, na redelijke aankondiging waar mogelijk.</p>
                        <h3>Toepasselijk recht</h3>
                        <p>Op deze voorwaarden is Nederlands recht van toepassing. Geschillen worden voorgelegd aan de bevoegde rechter in Nederland.</p>
                    </div>
                </div>
            </section>

            <div id="contact-terms" tabindex="-1" class="keyline-card p-8 bg-[color:var(--color-paper-2)] text-center">
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight">Vragen over de gebruiksvoorwaarden?</h2>
                <p class="text-[color:var(--color-ink-muted)] mb-5">We staan klaar om al je vragen te beantwoorden.</p>
                <a href="mailto:juridisch@politiekpraat.nl" class="btn btn--primary">
                    <?= pp_icon('mail', 14) ?>
                    Stuur een e-mail
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
