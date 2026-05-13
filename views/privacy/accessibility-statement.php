<?php
$pageTitle = "Toegankelijkheidsverklaring | PolitiekPraat";
$pageDescription = "Toegankelijkheidsverklaring van PolitiekPraat conform de European Accessibility Act (EAA) en WCAG 2.1.";
$pageKeywords = "toegankelijkheid, accessibility, EAA, WCAG, inclusief design";

require_once 'views/templates/header.php';

$lastUpdated = date('d F Y');

$sections = [
    'commitment'   => 'Onze toewijding',
    'compliance'   => 'WCAG 2.1 conformiteit',
    'features'     => 'Toegankelijkheidsfuncties',
    'known-issues' => 'Bekende beperkingen',
    'feedback'     => 'Feedback en hulp',
    'technical'    => 'Technische details',
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Toegankelijkheid',
    'title'   => 'Toegankelijkheidsverklaring',
    'lead'    => 'Onze toewijding aan inclusief design conform de European Accessibility Act (EAA) en WCAG 2.1.',
    'meta'    => [
        ['icon' => 'accessibility', 'text' => 'EAA & WCAG 2.1 AA-niveau'],
        ['icon' => 'calendar',      'text' => 'Bijgewerkt: ' . $lastUpdated],
    ],
]) ?>

<main id="main-content" class="pp-container pp-container--wide py-12 md:py-16" tabindex="-1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <aside class="lg:col-span-3">
            <nav class="sticky top-24" aria-label="Toegankelijkheid navigatie">
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
                    PolitiekPraat zet zich in voor een toegankelijke website voor alle gebruikers, ongeacht hun mogelijkheden of beperkingen. We werken volgens de richtlijnen van de <strong>European Accessibility Act (EAA)</strong> en <strong>Web Content Accessibility Guidelines (WCAG) 2.1 niveau AA</strong>.
                </p>
                <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                    Toegankelijkheid is geen eenmalige actie, maar een doorlopend proces van verbeteren, testen en bijsturen.
                </p>
            </section>

            <section id="commitment" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">1. Onze toewijding aan toegankelijkheid</h2>
                    <div class="prose-editorial max-w-none">
                        <p>We geloven dat het web voor iedereen toegankelijk moet zijn. Daarom investeren we structureel in:</p>
                        <ul>
                            <li>Inclusief design vanaf de ontwerpfase</li>
                            <li>Periodieke toegankelijkheidsaudits</li>
                            <li>Gebruikersonderzoek met diverse doelgroepen</li>
                            <li>Continue verbetering op basis van feedback</li>
                            <li>Training van het team rond toegankelijkheid</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="compliance" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">2. WCAG 2.1 conformiteit</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Onze website is ontwikkeld om te voldoen aan <strong>WCAG 2.1 niveau AA</strong>. Dit omvat de vier principes:</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $principles = [
                            ['icon' => 'eye',        'title' => 'Waarneembaar', 'desc' => 'Informatie en interface-componenten worden gepresenteerd op manieren die gebruikers kunnen waarnemen.'],
                            ['icon' => 'mouse-pointer','title' => 'Bedienbaar',  'desc' => 'Interface-componenten en navigatie moeten bedienbaar zijn voor alle gebruikers.'],
                            ['icon' => 'lightbulb',  'title' => 'Begrijpelijk', 'desc' => 'Informatie en bediening van de interface moeten begrijpelijk zijn.'],
                            ['icon' => 'shield',     'title' => 'Robuust',      'desc' => 'Content moet robuust genoeg zijn om betrouwbaar geinterpreteerd te worden door diverse user agents.'],
                        ];
                        foreach ($principles as $p): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5">
                                <div class="flex items-center gap-2 text-[color:var(--color-hague)] mb-2"><?= pp_icon($p['icon'], 18) ?><h3 class="font-display text-base text-[color:var(--color-ink)] m-0"><?= pp_e($p['title']) ?></h3></div>
                                <p class="text-sm text-[color:var(--color-ink-muted)] m-0"><?= pp_e($p['desc']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="features" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">3. Toegankelijkheidsfuncties</h2>
                    <div class="prose-editorial max-w-none">
                        <p>We hebben diverse functies ingebouwd om de website voor iedereen toegankelijk te maken:</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <?php
                        $features = [
                            ['title' => 'Toetsenbordnavigatie',  'items' => ['Volledige Tab-navigatie', 'Skip-links naar hoofdcontent', 'Logische tab-volgorde', 'Zichtbare focus-indicatoren']],
                            ['title' => 'Schermlezer-ondersteuning','items' => ['Semantische HTML5', 'ARIA-labels en -roles', 'Alt-tekst voor afbeeldingen', 'Logische heading-structuur']],
                            ['title' => 'Visuele toegankelijkheid','items' => ['Voldoende kleurcontrast (4.5:1+)', 'Schalbare tekst tot 200%', 'High contrast-modus', 'Donker en licht thema']],
                            ['title' => 'Motorische toegankelijkheid','items' => ['Grote klikbare gebieden (44px+)', 'Geen tijd-gebonden interacties', 'Geen onverwachte beweging', 'Reduced motion-ondersteuning']],
                            ['title' => 'Cognitieve toegankelijkheid','items' => ['Heldere, eenvoudige taal', 'Consistente navigatie', 'Foutmeldingen met uitleg', 'Voortgangsindicatoren']],
                            ['title' => 'Multimedia',           'items' => ['Onderschriften bij video', 'Audio-beschrijvingen', 'Transcripties bij podcasts', 'Pauze- en volumecontrole']],
                        ];
                        foreach ($features as $f): ?>
                            <div class="border border-[color:var(--color-keyline)] rounded-md p-5 bg-[color:var(--color-paper-2)]">
                                <h3 class="font-display text-base text-[color:var(--color-ink)] mb-2"><?= pp_e($f['title']) ?></h3>
                                <ul class="text-sm text-[color:var(--color-ink-muted)] space-y-1 list-disc list-inside">
                                    <?php foreach ($f['items'] as $it): ?><li><?= pp_e($it) ?></li><?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="known-issues" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">4. Bekende beperkingen</h2>
                    <div class="prose-editorial max-w-none">
                        <p>We zijn transparant over gebieden waar we nog verbeteringen aanbrengen:</p>
                        <ul>
                            <li>Sommige oudere PDF-documenten zijn nog niet volledig toegankelijk; we werken aan toegankelijke vervangingen.</li>
                            <li>Bepaalde ingesloten video's van derden hebben mogelijk geen onderschriften; we maken transcripties beschikbaar waar nodig.</li>
                            <li>Embedded social media-content kan beperkte toegankelijkheid hebben; we bieden alternatieve toegangsroutes.</li>
                        </ul>
                        <p>Heb je een toegankelijkheidsprobleem ondervonden dat hier niet wordt genoemd? Laat het ons weten via het feedback-formulier hieronder.</p>
                    </div>
                </div>
            </section>

            <section id="feedback" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">5. Feedback en hulp</h2>
                    <div class="prose-editorial max-w-none">
                        <p>Je feedback helpt ons om de toegankelijkheid van onze website verder te verbeteren. Ondervind je problemen of heb je suggesties? Laat het ons weten.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <a href="mailto:toegankelijkheid@politiekpraat.nl" class="keyline-card p-5 hover:bg-[color:var(--color-paper-2)] transition-colors">
                            <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('mail', 22) ?></div>
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">E-mail</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)]">toegankelijkheid@politiekpraat.nl</p>
                        </a>
                        <a href="<?= pp_e(URLROOT) ?>/contact" class="keyline-card p-5 hover:bg-[color:var(--color-paper-2)] transition-colors">
                            <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('message-circle', 22) ?></div>
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Contactformulier</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)]">Vul ons formulier in voor uitgebreide feedback.</p>
                        </a>
                        <div class="keyline-card p-5">
                            <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('clock', 22) ?></div>
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Reactietijd</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)]">We streven naar reactie binnen vijf werkdagen.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="technical" tabindex="-1">
                <div class="keyline-card p-6 md:p-8">
                    <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-4 leading-tight">6. Technische details</h2>
                    <div class="prose-editorial max-w-none">
                        <h3>Technologieen</h3>
                        <ul>
                            <li>HTML5 met semantische structuur</li>
                            <li>CSS3 met progressive enhancement</li>
                            <li>JavaScript met graceful degradation</li>
                            <li>ARIA voor verrijkte interactiviteit</li>
                        </ul>
                        <h3>Compatibiliteit</h3>
                        <p>Onze website is getest met:</p>
                        <ul>
                            <li>Moderne browsers (Chrome, Firefox, Safari, Edge)</li>
                            <li>Schermlezers (NVDA, JAWS, VoiceOver, TalkBack)</li>
                            <li>Toetsenbordnavigatie</li>
                            <li>Mobile assistive technologies</li>
                        </ul>
                        <h3>Evaluatiemethode</h3>
                        <p>We evalueren toegankelijkheid via:</p>
                        <ul>
                            <li>Automatische tools (axe DevTools, WAVE, Lighthouse)</li>
                            <li>Handmatige tests door toegankelijkheidsexperts</li>
                            <li>Gebruikerstests met mensen met beperkingen</li>
                            <li>Periodieke audits</li>
                        </ul>
                    </div>
                </div>
            </section>

            <div class="keyline-card p-8 bg-[color:var(--color-paper-2)] text-center">
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight">Samen werken aan toegankelijkheid</h2>
                <p class="text-[color:var(--color-ink-muted)] mb-5">Heb je suggesties of ondervind je problemen? Laat het ons weten.</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="mailto:toegankelijkheid@politiekpraat.nl" class="btn btn--primary">
                        <?= pp_icon('mail', 14) ?>
                        Direct e-mailen
                    </a>
                    <a href="<?= pp_e(URLROOT) ?>/contact" class="btn btn--ghost">
                        <?= pp_icon('message-circle', 14) ?>
                        Contactformulier
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'views/templates/footer.php'; ?>
