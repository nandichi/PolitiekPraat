<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<?php
$jaar = $verkiezing->jaar ?? '';
$winnaar = $verkiezing->winnaar ?? 'Onbekend';
$winnaarPartij = $verkiezing->winnaar_partij ?? '';
$winnaarFoto = $verkiezing->winnaar_foto_url ?? '';
$winnaarKiesmannen = (int) ($verkiezing->winnaar_kiesmannen ?? 0);
$winnaarStemmen = (int) ($verkiezing->winnaar_stemmen_populair ?? 0);
$winnaarPct = (float) ($verkiezing->winnaar_percentage_populair ?? 0);

$verliezer = $verkiezing->verliezer ?? 'Onbekend';
$verliezerPartij = $verkiezing->verliezer_partij ?? '';
$verliezerFoto = $verkiezing->verliezer_foto_url ?? '';
$verliezerKiesmannen = (int) ($verkiezing->verliezer_kiesmannen ?? 0);
$verliezerStemmen = (int) ($verkiezing->verliezer_stemmen_populair ?? 0);
$verliezerPct = (float) ($verkiezing->verliezer_percentage_populair ?? 0);

$totaalKiesmannen = (int) ($verkiezing->totaal_kiesmannen ?? 538);
$opkomst = $verkiezing->opkomst_percentage ?? null;
$verkiezingsdatum = $verkiezing->verkiezingsdata_formatted ?? null;
$inhuldigingDatum = $verkiezing->inhuldiging_data_formatted ?? null;
$themas = $verkiezing->belangrijkste_themas ?? [];
$gebeurtenissen = $verkiezing->belangrijke_gebeurtenissen ?? '';
$opvallendeFeiten = $verkiezing->opvallende_feiten ?? '';
$beschrijving = $verkiezing->beschrijving ?? '';

$totaalStemmen = $winnaarStemmen + $verliezerStemmen;
$marginStemmen = abs($winnaarStemmen - $verliezerStemmen);
$marginPct = abs($winnaarPct - $verliezerPct);
$benodigdKiesmannen = (int) ceil($totaalKiesmannen / 2);

$heroMeta = [];
if ($verkiezingsdatum) {
    $heroMeta[] = ['icon' => 'calendar', 'text' => $verkiezingsdatum];
}
$heroMeta[] = ['icon' => 'landmark', 'text' => 'Amerikaanse presidentsverkiezing'];
if ($opkomst !== null) {
    $heroMeta[] = ['icon' => 'users', 'text' => 'Opkomst ' . number_format((float) $opkomst, 1) . '%'];
}

$lead = trim((string) $beschrijving) !== ''
    ? mb_substr(strip_tags($beschrijving), 0, 220) . (mb_strlen(strip_tags($beschrijving)) > 220 ? '...' : '')
    : 'Uitslag, kiesmannen en de meest opvallende momenten van de Amerikaanse presidentsverkiezing van ' . pp_e((string) $jaar) . '.';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Amerikaanse presidentsverkiezing',
    'title'   => (string) $jaar,
    'lead'    => $lead,
    'meta'    => $heroMeta,
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

    <div>
        <div class="eyebrow mb-3">Uitslag</div>
        <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Winnaar en verliezer</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="keyline-card overflow-hidden flex flex-col">
                <?php if ($winnaarFoto): ?>
                    <div class="photo-frame" style="border: none; padding: 0; border-radius: 0; box-shadow: none;">
                        <img src="<?= pp_e($winnaarFoto) ?>"
                             alt="<?= pp_e($winnaar) ?>"
                             loading="lazy"
                             style="aspect-ratio: 4/3; object-fit: cover; border-radius: 0;">
                    </div>
                <?php endif; ?>
                <div class="p-6 md:p-8 flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <span class="badge badge--olive">Winnaar</span>
                        <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-faint)]"><?= number_format($winnaarPct, 1) ?>%</span>
                    </div>
                    <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($winnaar) ?></h3>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mb-5"><?= pp_e($winnaarPartij) ?></p>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div class="border-l-2 border-[color:var(--color-olive)] pl-3">
                            <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Kiesmannen</dt>
                            <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= number_format($winnaarKiesmannen) ?></dd>
                        </div>
                        <div class="border-l-2 border-[color:var(--color-olive)] pl-3">
                            <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Stemmen</dt>
                            <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= number_format($winnaarStemmen) ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="keyline-card overflow-hidden flex flex-col">
                <?php if ($verliezerFoto): ?>
                    <div class="photo-frame" style="border: none; padding: 0; border-radius: 0; box-shadow: none;">
                        <img src="<?= pp_e($verliezerFoto) ?>"
                             alt="<?= pp_e($verliezer) ?>"
                             loading="lazy"
                             style="aspect-ratio: 4/3; object-fit: cover; border-radius: 0;">
                    </div>
                <?php endif; ?>
                <div class="p-6 md:p-8 flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <span class="badge badge--terracotta">Verliezer</span>
                        <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-faint)]"><?= number_format($verliezerPct, 1) ?>%</span>
                    </div>
                    <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($verliezer) ?></h3>
                    <p class="text-sm text-[color:var(--color-ink-muted)] mb-5"><?= pp_e($verliezerPartij) ?></p>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div class="border-l-2 border-[color:var(--color-terracotta)] pl-3">
                            <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Kiesmannen</dt>
                            <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= number_format($verliezerKiesmannen) ?></dd>
                        </div>
                        <div class="border-l-2 border-[color:var(--color-terracotta)] pl-3">
                            <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Stemmen</dt>
                            <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= number_format($verliezerStemmen) ?></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="keyline-card p-5">
            <div class="eyebrow mb-2">Totaal kiesmannen</div>
            <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= number_format($totaalKiesmannen) ?></div>
            <p class="text-xs text-[color:var(--color-ink-muted)] mt-2"><?= $benodigdKiesmannen ?> nodig voor winst</p>
        </div>
        <div class="keyline-card p-5">
            <div class="eyebrow mb-2">Marge stemmen</div>
            <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= number_format($marginStemmen) ?></div>
            <p class="text-xs text-[color:var(--color-ink-muted)] mt-2"><?= number_format($marginPct, 1) ?>%-punten</p>
        </div>
        <?php if ($opkomst !== null): ?>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Opkomst</div>
                <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= number_format((float) $opkomst, 1) ?>%</div>
                <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Stemgerechtigden</p>
            </div>
        <?php endif; ?>
        <div class="keyline-card p-5">
            <div class="eyebrow mb-2">Totaal stemmen</div>
            <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= number_format($totaalStemmen) ?></div>
            <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Winnaar + verliezer</p>
        </div>
    </div>

    <div class="keyline-card p-6 md:p-8">
        <div class="eyebrow mb-3">Verdelingen</div>
        <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Kiesmannen versus populaire stemmen</h2>
        <div class="space-y-6">
            <div>
                <div class="flex items-baseline justify-between text-sm mb-2">
                    <span class="text-[color:var(--color-ink)] font-medium">Kiesmannen</span>
                    <span class="text-[color:var(--color-ink-muted)] font-mono text-tabular"><?= number_format($winnaarKiesmannen + $verliezerKiesmannen) ?> / <?= number_format($totaalKiesmannen) ?></span>
                </div>
                <div class="h-3 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden flex">
                    <div class="bg-[color:var(--color-olive)]" style="width: <?= $totaalKiesmannen > 0 ? ($winnaarKiesmannen / $totaalKiesmannen * 100) : 0 ?>%;"></div>
                    <div class="bg-[color:var(--color-terracotta)]" style="width: <?= $totaalKiesmannen > 0 ? ($verliezerKiesmannen / $totaalKiesmannen * 100) : 0 ?>%;"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-[color:var(--color-ink-muted)]">
                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[color:var(--color-olive)]"></span> <?= pp_e($winnaar) ?>: <?= number_format($winnaarKiesmannen) ?></span>
                    <span class="inline-flex items-center gap-1"><?= pp_e($verliezer) ?>: <?= number_format($verliezerKiesmannen) ?> <span class="w-2 h-2 rounded-full bg-[color:var(--color-terracotta)]"></span></span>
                </div>
            </div>

            <div>
                <div class="flex items-baseline justify-between text-sm mb-2">
                    <span class="text-[color:var(--color-ink)] font-medium">Populaire stem</span>
                    <span class="text-[color:var(--color-ink-muted)] font-mono text-tabular"><?= number_format($winnaarPct + $verliezerPct, 1) ?>%</span>
                </div>
                <div class="h-3 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden flex">
                    <div class="bg-[color:var(--color-olive)]" style="width: <?= number_format($winnaarPct, 2) ?>%;"></div>
                    <div class="bg-[color:var(--color-terracotta)]" style="width: <?= number_format($verliezerPct, 2) ?>%;"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-[color:var(--color-ink-muted)]">
                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[color:var(--color-olive)]"></span> <?= pp_e($winnaar) ?>: <?= number_format($winnaarPct, 1) ?>%</span>
                    <span class="inline-flex items-center gap-1"><?= pp_e($verliezer) ?>: <?= number_format($verliezerPct, 1) ?>% <span class="w-2 h-2 rounded-full bg-[color:var(--color-terracotta)]"></span></span>
                </div>
            </div>
        </div>

        <?php if ($winnaarPct < 50 && $winnaarPct > 0): ?>
            <p class="mt-5 text-sm text-[color:var(--color-ink-muted)]">
                <span class="eyebrow text-[color:var(--color-ochre)]">Let op</span>
                De winnaar haalde geen absolute meerderheid van de populaire stem.
            </p>
        <?php endif; ?>
    </div>

    <?php if ($verkiezingsdatum || $inhuldigingDatum): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if ($verkiezingsdatum): ?>
                <div class="keyline-card p-6">
                    <div class="eyebrow mb-2">Verkiezingsdatum</div>
                    <div class="font-display text-display-md text-[color:var(--color-ink)] leading-tight font-mono text-tabular"><?= pp_e($verkiezingsdatum) ?></div>
                </div>
            <?php endif; ?>
            <?php if ($inhuldigingDatum): ?>
                <div class="keyline-card p-6">
                    <div class="eyebrow mb-2">Inhuldiging</div>
                    <div class="font-display text-display-md text-[color:var(--color-ink)] leading-tight font-mono text-tabular"><?= pp_e($inhuldigingDatum) ?></div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($themas)): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Belangrijkste thema's</div>
            <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-5 leading-tight">Waar ging deze verkiezing over?</h2>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($themas as $thema): ?>
                    <li class="flex items-start gap-3 p-3 border border-[color:var(--color-keyline)] rounded-md">
                        <span class="text-[color:var(--color-hague)] mt-0.5 flex-shrink-0"><?= pp_icon('check', 14) ?></span>
                        <span class="text-sm text-[color:var(--color-ink)]"><?= pp_e($thema) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($gebeurtenissen || $opvallendeFeiten): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if ($gebeurtenissen): ?>
                <div class="keyline-card p-6 md:p-8">
                    <div class="eyebrow mb-3">Belangrijke gebeurtenissen</div>
                    <div class="prose-editorial text-sm text-[color:var(--color-ink-muted)] leading-relaxed">
                        <?= nl2br(pp_e($gebeurtenissen)) ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($opvallendeFeiten): ?>
                <div class="keyline-card p-6 md:p-8">
                    <div class="eyebrow mb-3">Opvallende feiten</div>
                    <div class="prose-editorial text-sm text-[color:var(--color-ink-muted)] leading-relaxed">
                        <?= nl2br(pp_e($opvallendeFeiten)) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($beschrijving): ?>
        <div class="keyline-card p-6 md:p-10">
            <div class="eyebrow mb-3">Achtergrond</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Een uitgebreide blik op <?= pp_e((string) $jaar) ?></h2>
            <div class="prose-editorial max-w-prose text-[color:var(--color-ink)] leading-relaxed">
                <?= nl2br(pp_e($beschrijving)) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($gerelateerdeVerkiezingen) && ($gerelateerdeVerkiezingen['vorige'] || $gerelateerdeVerkiezingen['volgende'])): ?>
        <div>
            <div class="eyebrow mb-3">Door de jaren heen</div>
            <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Andere verkiezingen</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($gerelateerdeVerkiezingen['vorige'])):
                    $vorige = $gerelateerdeVerkiezingen['vorige'];
                ?>
                    <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/<?= pp_e((string) $vorige->jaar) ?>"
                       class="keyline-card p-6 md:p-8 hover:border-[color:var(--color-hague)] transition-colors block">
                        <div class="flex items-center gap-2 text-[color:var(--color-ink-muted)] mb-3">
                            <?= pp_icon('arrow-left', 14) ?>
                            <span class="eyebrow">Vorige verkiezing</span>
                        </div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] mb-2 leading-none"><?= pp_e((string) $vorige->jaar) ?></div>
                        <div class="text-sm text-[color:var(--color-ink-muted)]">
                            Winnaar: <span class="text-[color:var(--color-ink)] font-medium"><?= pp_e($vorige->winnaar ?? 'Onbekend') ?></span><br>
                            <?= pp_e($vorige->winnaar_partij ?? '') ?>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (!empty($gerelateerdeVerkiezingen['volgende'])):
                    $volgende = $gerelateerdeVerkiezingen['volgende'];
                ?>
                    <a href="<?= URLROOT ?>/amerikaanse-verkiezingen/<?= pp_e((string) $volgende->jaar) ?>"
                       class="keyline-card p-6 md:p-8 hover:border-[color:var(--color-hague)] transition-colors block">
                        <div class="flex items-center justify-end gap-2 text-[color:var(--color-ink-muted)] mb-3">
                            <span class="eyebrow">Volgende verkiezing</span>
                            <?= pp_icon('arrow-right', 14) ?>
                        </div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] mb-2 leading-none text-right"><?= pp_e((string) $volgende->jaar) ?></div>
                        <div class="text-sm text-[color:var(--color-ink-muted)] text-right">
                            Winnaar: <span class="text-[color:var(--color-ink)] font-medium"><?= pp_e($volgende->winnaar ?? 'Onbekend') ?></span><br>
                            <?= pp_e($volgende->winnaar_partij ?? '') ?>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="<?= URLROOT ?>/amerikaanse-verkiezingen" class="btn btn--ghost">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar alle Amerikaanse verkiezingen
        </a>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
