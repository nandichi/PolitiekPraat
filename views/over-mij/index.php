<?php
if (!defined('URLROOT')) {
    exit;
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Over PolitiekPraat',
    'title'   => 'Naoufal Andichi',
    'lead'    => 'Developer en politiek-liefhebber. Ik schrijf op PolitiekPraat om de Nederlandse politiek begrijpelijker te maken en bouw de tools die je hier vindt.',
]) ?>

<section class="pp-container pp-container--wide pb-12 md:pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
        <div class="lg:col-span-4">
            <div class="sticky top-24 space-y-6">
                <figure class="photo-frame">
                    <img src="<?= pp_e(URLROOT) ?>/public/images/naoufal-foto.jpg"
                         onerror="if(this.src.indexOf('/images/naoufal-foto.jpg') === -1) this.src='<?= pp_e(URLROOT) ?>/images/naoufal-foto.jpg';"
                         alt="Foto van Naoufal Andichi"
                         class="w-full aspect-[4/5] object-cover">
                    <figcaption class="photo-frame__caption">Naoufal Andichi, oprichter van PolitiekPraat</figcaption>
                </figure>

                <div class="flex flex-wrap gap-2">
                    <span class="chip">Liberaal</span>
                    <span class="chip">Developer</span>
                    <span class="chip">Analist</span>
                    <span class="chip">Blogger</span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="https://www.linkedin.com/in/naoufalandichi/" target="_blank" rel="noopener noreferrer"
                       class="btn btn--ghost">
                        <?= pp_icon('linkedin', 14) ?>
                        LinkedIn
                    </a>
                    <a href="mailto:naoufal@politiekpraat.nl" class="btn btn--ghost">
                        <?= pp_icon('mail', 14) ?>
                        E-mail
                    </a>
                    <a href="<?= pp_e(URLROOT) ?>/blogs" class="btn btn--ghost">
                        <?= pp_icon('book-open', 14) ?>
                        Blogs
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-12">
            <article class="prose-editorial max-w-none">
                <div class="eyebrow mb-3">Mijn verhaal</div>
                <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-5 leading-[1.15]">Hoe PolitiekPraat is ontstaan</h2>
                <p class="text-lg leading-relaxed text-[color:var(--color-ink-muted)] mb-5">
                    Ik ben Naoufal, een nerd op twee gebieden: programmeren en politiek. Een rare combinatie, maar ze versterken elkaar. Tijdens mijn studie merkte ik hoe weinig mensen de Nederlandse politiek echt begrijpen, terwijl beslissingen in Den Haag onze levens dagelijks raken.
                </p>
                <p class="leading-relaxed text-[color:var(--color-ink-muted)] mb-5">
                    Met een laptop, koffie en geduld bouwde ik PolitiekPraat. Geen agency, geen redactiekantoor, gewoon een platform dat politiek toegankelijk wil maken, met heldere taal en zonder partijdige bril. Liberaal van origine, maar bovenal nieuwsgierig.
                </p>
                <p class="leading-relaxed text-[color:var(--color-ink-muted)]">
                    Mijn doel is simpel: politiek leesbaar maken voor iedereen die wel eens denkt 'ik snap er niets van'. Op deze site lees je analyses, vergelijk je partijen en gebruik je tools om je eigen positie te bepalen.
                </p>
            </article>

            <article>
                <div class="eyebrow mb-3">Mijn filosofie</div>
                <blockquote class="pull-quote">
                    Politiek hoeft niet ingewikkeld te zijn als je het gewoon normaal uitlegt.
                </blockquote>
                <p class="text-[color:var(--color-ink-muted)] leading-relaxed mt-5">
                    Ik probeer politieke onderwerpen uit te leggen zoals ik ze zelf zou willen horen. Zonder jargon, zonder belerend toontje, met ruimte voor nuance en af en toe een knipoog.
                </p>
            </article>

            <article>
                <div class="eyebrow mb-3">Expertise</div>
                <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-[1.15]">Waar ik aan werk</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php
                    $expertise = [
                        ['icon' => 'landmark',      'title' => 'Politieke analyse', 'desc' => 'Duiding van Kamerstukken, debatten en peilingen.'],
                        ['icon' => 'bar-chart-3',   'title' => 'Data-visualisatie', 'desc' => 'Cijfers helder presenteren voor brede lezers.'],
                        ['icon' => 'pen-tool',      'title' => 'Content & redactie', 'desc' => 'Blogs, explainers en weekoverzichten.'],
                        ['icon' => 'code',          'title' => 'Webdevelopment',    'desc' => 'PHP, JavaScript en moderne front-end.'],
                        ['icon' => 'layout',        'title' => 'UX & design',       'desc' => 'Leesbare, rustige interfaces.'],
                        ['icon' => 'target',        'title' => 'Strategie',         'desc' => 'Producten en redactionele richting.'],
                    ];
                    foreach ($expertise as $item):
                    ?>
                        <div class="keyline-card p-5">
                            <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon($item['icon'], 20) ?></div>
                            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1"><?= pp_e($item['title']) ?></h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($item['desc']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>

            <article>
                <div class="eyebrow mb-3">Mijn reis</div>
                <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-[1.15]">Hoe ik hier kwam</h2>
                <ol class="border-l border-[color:var(--color-keyline)] pl-6 space-y-6">
                    <?php
                    $reis = [
                        ['title' => 'Hoe het begon',          'desc' => 'Tijdens mijn studie kreeg ik door dat politiek eigenlijk best belangrijk is, maar dat veel mensen er weinig van snappen.'],
                        ['title' => 'Eerste artikelen',       'desc' => 'Ik begon dingen op te schrijven die ik interessant vond. Bleek dat anderen het ook leuk vonden om te lezen.'],
                        ['title' => 'PolitiekPraat ontstaat', 'desc' => 'Met wat programmeerskills en veel koffie bouwde ik deze site. Geen fancy agency, gewoon ik achter mijn laptop.'],
                        ['title' => 'Wat er komt',            'desc' => 'Meer artikelen, meer tools zoals de PartijMeter, en hopelijk steeds meer mensen die politiek interessant gaan vinden.'],
                    ];
                    foreach ($reis as $stap):
                    ?>
                        <li class="relative">
                            <span class="absolute -left-[31px] top-1.5 w-3 h-3 rounded-full bg-[color:var(--color-hague)] ring-4 ring-[color:var(--color-paper)]"></span>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-1"><?= pp_e($stap['title']) ?></h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($stap['desc']) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </article>

            <div class="keyline-card p-8 md:p-10 bg-[color:var(--color-paper-2)]">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div class="md:col-span-2">
                        <div class="eyebrow mb-2">Verbinden</div>
                        <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight">Heb je een vraag of idee?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            Vragen, suggesties of zin om mee te denken? Stuur gerust een berichtje. Reacties komen meestal binnen twee werkdagen terug.
                        </p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="mailto:naoufal@politiekpraat.nl" class="btn btn--primary justify-center">
                            <?= pp_icon('mail', 14) ?>
                            Stuur een e-mail
                        </a>
                        <a href="<?= pp_e(URLROOT) ?>/contact" class="btn btn--ghost justify-center">
                            <?= pp_icon('message-circle', 14) ?>
                            Contactformulier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
