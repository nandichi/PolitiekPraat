<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<?php
$jaar = $verkiezing->jaar ?? '';
$grootstePartij = $verkiezing->grootste_partij ?? 'Onbekend';
$tweedePartij = $verkiezing->tweede_partij ?? 'Onbekend';
$mp = $verkiezing->minister_president ?? 'Onbekend';
$mpPartij = $verkiezing->minister_president_partij ?? '';
$kabinetNaam = $verkiezing->kabinet_naam ?? '';
$opkomst = $verkiezing->opkomst_percentage ?? null;
$totaalZetels = $verkiezing->totaal_zetels ?? 150;
$totaalStemmen = $verkiezing->totaal_stemmen ?? null;
$aantalPartijen = $verkiezing->aantal_partijen_tk ?? null;
$kabinetType = $verkiezing->kabinet_type ?? null;
$formatieDuur = $verkiezing->formatie_duur_dagen ?? null;
$verkiezingsAanleiding = $verkiezing->verkiezings_aanleiding ?? null;
$verkiezingsdatum = $verkiezing->verkiezingsdata ?? null;
$kabinetStart = $verkiezing->kabinet_start_datum ?? null;
$kabinetEind = $verkiezing->kabinet_eind_datum ?? null;
$coalitiePartijen = $verkiezing->coalitie_partijen ?? [];
$oppositiePartijen = $verkiezing->oppositie_partijen ?? [];
$coalitieZetels = $verkiezing->coalitie_zetels ?? null;
$partijUitslagen = $verkiezing->partij_uitslagen ?? [];
$beschrijving = $verkiezing->beschrijving ?? '';
$grootsteWinnaar = $verkiezing->grootste_winnaar ?? '';
$grootsteWinnaarAantal = $verkiezing->grootste_winnaar_aantal ?? null;
$grootsteVerliezer = $verkiezing->grootste_verliezer ?? '';
$grootsteVerliezerAantal = $verkiezing->grootste_verliezer_aantal ?? null;
$kiesdrempel = $verkiezing->kiesdrempel_percentage ?? 0.67;
$themas = $verkiezing->belangrijkste_themas ?? [];
$nieuwePartijen = $verkiezing->nieuwe_partijen ?? [];
$verdwenenPartijen = $verkiezing->verdwenen_partijen ?? [];
$gebeurtenissen = $verkiezing->belangrijke_gebeurtenissen ?? '';
$opvallendeFeiten = $verkiezing->opvallende_feiten ?? '';
$lijsttrekkers = $verkiezing->lijsttrekkers ?? [];
$tvDebatten = $verkiezing->tv_debatten ?? [];
$bronnen = $verkiezing->bronnen ?? [];
$fotoUrl = $verkiezing->foto_url ?? '';
$grootstePartijZetels = $verkiezing->grootste_partij_zetels ?? null;
$grootstePartijPct = $verkiezing->grootste_partij_percentage ?? null;
$grootstePartijStemmen = $verkiezing->grootste_partij_stemmen ?? null;
$tweedePartijZetels = $verkiezing->tweede_partij_zetels ?? null;
$tweedePartijPct = $verkiezing->tweede_partij_percentage ?? null;
$tweedePartijStemmen = $verkiezing->tweede_partij_stemmen ?? null;

$heroMeta = [];
if ($verkiezingsdatum) {
    $heroMeta[] = ['icon' => 'calendar', 'text' => date('j F Y', strtotime($verkiezingsdatum))];
}
$heroMeta[] = ['icon' => 'landmark', 'text' => 'Tweede Kamerverkiezing'];
if ($opkomst !== null) {
    $heroMeta[] = ['icon' => 'users', 'text' => 'Opkomst ' . number_format((float) $opkomst, 1) . '%'];
}
if ($aantalPartijen) {
    $heroMeta[] = ['icon' => 'vote', 'text' => (int) $aantalPartijen . ' partijen in de Kamer'];
}

$lead = trim((string) $beschrijving) !== ''
    ? mb_substr(strip_tags($beschrijving), 0, 220) . (mb_strlen(strip_tags($beschrijving)) > 220 ? '...' : '')
    : 'Het beeld van de Tweede Kamerverkiezing van ' . pp_e((string) $jaar) . ': uitslagen, coalitievorming en de meest opvallende ontwikkelingen.';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Nederlandse Tweede Kamerverkiezing',
    'title'   => (string) $jaar,
    'lead'    => $lead,
    'meta'    => $heroMeta,
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

    <div>
        <div class="eyebrow mb-3">Uitslag</div>
        <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Grootste partijen</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="keyline-card p-6 md:p-8">
                <div class="flex items-baseline justify-between mb-2">
                    <span class="badge badge--ochre">Nr. 1</span>
                    <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-faint)]">
                        <?= $grootstePartijPct !== null ? number_format((float) $grootstePartijPct, 1) . '%' : '&mdash;' ?>
                    </span>
                </div>
                <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($grootstePartij) ?></h3>
                <p class="text-sm text-[color:var(--color-ink-muted)] mb-5">Grootste partij</p>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="border-l-2 border-[color:var(--color-hague)] pl-3">
                        <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Zetels</dt>
                        <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= $grootstePartijZetels !== null ? (int) $grootstePartijZetels : '&mdash;' ?></dd>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-hague)] pl-3">
                        <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Stemmen</dt>
                        <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= $grootstePartijStemmen ? number_format((int) $grootstePartijStemmen, 0, ',', '.') : '&mdash;' ?></dd>
                    </div>
                </dl>
            </div>
            <div class="keyline-card p-6 md:p-8">
                <div class="flex items-baseline justify-between mb-2">
                    <span class="badge badge--neutral">Nr. 2</span>
                    <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-faint)]">
                        <?= $tweedePartijPct !== null ? number_format((float) $tweedePartijPct, 1) . '%' : '&mdash;' ?>
                    </span>
                </div>
                <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($tweedePartij) ?></h3>
                <p class="text-sm text-[color:var(--color-ink-muted)] mb-5">Tweede partij</p>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="border-l-2 border-[color:var(--color-ink-muted)] pl-3">
                        <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Zetels</dt>
                        <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= $tweedePartijZetels !== null ? (int) $tweedePartijZetels : '&mdash;' ?></dd>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-ink-muted)] pl-3">
                        <dt class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Stemmen</dt>
                        <dd class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular"><?= $tweedePartijStemmen ? number_format((int) $tweedePartijStemmen, 0, ',', '.') : '&mdash;' ?></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="keyline-card p-6 md:p-8">
        <div class="eyebrow mb-3">Minister-president</div>
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($mp) ?></h3>
                <p class="text-sm text-[color:var(--color-ink-muted)]">
                    <?= pp_e($mpPartij) ?><?php if ($kabinetNaam): ?> &middot; Kabinet <?= pp_e($kabinetNaam) ?><?php endif; ?>
                </p>
                <?php if ($kabinetStart || $kabinetEind): ?>
                    <p class="mt-3 text-sm text-[color:var(--color-ink-muted)] font-mono text-tabular">
                        <?= $kabinetStart ? date('j F Y', strtotime($kabinetStart)) : '?' ?>
                        &mdash;
                        <?= $kabinetEind ? date('j F Y', strtotime($kabinetEind)) : 'heden' ?>
                    </p>
                <?php endif; ?>
            </div>
            <a href="<?= URLROOT ?>/nederlandse-verkiezingen/ministers-presidenten" class="btn btn--ghost btn--sm flex-shrink-0">
                <?= pp_icon('users', 14) ?>
                Alle ministers-presidenten
            </a>
        </div>
    </div>

    <div>
        <div class="eyebrow mb-3">Kerncijfers</div>
        <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">In context</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php
            $stats = [
                ['label' => 'Totaal zetels',     'value' => (int) $totaalZetels],
                ['label' => 'Opkomst',           'value' => $opkomst !== null ? number_format((float) $opkomst, 1) . '%' : '&mdash;'],
                ['label' => 'Totaal stemmen',    'value' => $totaalStemmen ? number_format((int) $totaalStemmen, 0, ',', '.') : '&mdash;'],
                ['label' => 'Partijen in TK',    'value' => $aantalPartijen ?? '&mdash;'],
                ['label' => 'Kabinet-type',      'value' => $kabinetType ? ucfirst($kabinetType) : '&mdash;'],
                ['label' => 'Formatieduur',      'value' => $formatieDuur ? ((int) $formatieDuur . ' dagen') : '&mdash;'],
            ];
            foreach ($stats as $stat): ?>
                <div class="keyline-card p-4">
                    <div class="eyebrow mb-2"><?= pp_e($stat['label']) ?></div>
                    <div class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular leading-tight">
                        <?= $stat['value'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($verkiezingsAanleiding): ?>
            <p class="mt-4 text-sm text-[color:var(--color-ink-muted)]">
                <span class="eyebrow">Aanleiding</span>
                <?= pp_e(ucfirst((string) $verkiezingsAanleiding)) ?>
            </p>
        <?php endif; ?>
    </div>

    <?php if ($fotoUrl): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Kabinet</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-2 leading-tight">
                Kabinet <?= pp_e($kabinetNaam !== '' ? $kabinetNaam : (string) $jaar) ?>
            </h2>
            <p class="text-sm text-[color:var(--color-ink-muted)] mb-5">Onder leiding van <?= pp_e($mp) ?> (<?= pp_e($mpPartij) ?>)</p>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <figure class="photo-frame lg:col-span-1">
                    <img src="<?= pp_e($fotoUrl) ?>"
                         alt="Kabinet <?= pp_e($kabinetNaam !== '' ? $kabinetNaam : (string) $jaar) ?>"
                         loading="lazy">
                    <figcaption class="photo-frame__caption">Kabinet <?= pp_e($kabinetNaam !== '' ? $kabinetNaam : (string) $jaar) ?></figcaption>
                </figure>
                <div class="lg:col-span-2 space-y-5">
                    <?php if (!empty($coalitiePartijen)): ?>
                        <div>
                            <div class="eyebrow mb-2">Coalitie<?php if ($coalitieZetels): ?> &middot; <?= (int) $coalitieZetels ?> zetels<?php endif; ?></div>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($coalitiePartijen as $partij): ?>
                                    <span class="chip chip--olive"><?= pp_e($partij) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($oppositiePartijen)): ?>
                        <div>
                            <div class="eyebrow mb-2">Oppositie<?php if ($coalitieZetels !== null): ?> &middot; <?= max(0, (int) $totaalZetels - (int) $coalitieZetels) ?> zetels<?php endif; ?></div>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($oppositiePartijen as $partij): ?>
                                    <span class="chip"><?= pp_e($partij) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($partijUitslagen)): ?>
        <div>
            <div class="flex flex-wrap items-baseline justify-between gap-3 mb-6">
                <div>
                    <div class="eyebrow mb-3">Zetelverdeling</div>
                    <h2 class="font-display text-display-xl text-[color:var(--color-ink)] leading-tight">Alle partijen met zetels</h2>
                </div>
                <span class="text-sm text-[color:var(--color-ink-muted)]">Kiesdrempel: <?= number_format((float) $kiesdrempel, 2) ?>%</span>
            </div>

            <div class="keyline-card p-5 md:p-6 mb-6">
                <div class="h-3 w-full bg-[color:var(--color-paper-2)] rounded-full overflow-hidden flex">
                    <?php foreach ($partijUitslagen as $index => $partij):
                        $widthPct = ((int) ($partij->zetels ?? 0)) / max(1, (int) $totaalZetels) * 100;
                        $isCoalition = is_array($coalitiePartijen) && in_array($partij->partij ?? '', $coalitiePartijen, true);
                    ?>
                        <div class="<?= $isCoalition ? 'bg-[color:var(--color-hague)]' : 'bg-[color:var(--color-ink-muted)]' ?>"
                             style="width: <?= $widthPct ?>%; opacity: <?= max(0.4, 1 - $index * 0.05) ?>;"
                             title="<?= pp_e((string) ($partij->partij ?? '')) ?>: <?= (int) ($partij->zetels ?? 0) ?> zetels"></div>
                    <?php endforeach; ?>
                </div>
                <div class="flex items-center justify-between mt-3 text-xs text-[color:var(--color-ink-muted)]">
                    <span>0</span>
                    <span class="font-mono text-tabular">
                        Coalitie: <?= $coalitieZetels !== null ? (int) $coalitieZetels : '?' ?> / <?= (int) $totaalZetels ?>
                        <?php if ($coalitieZetels !== null): ?>
                            &middot; <?= ((int) $coalitieZetels >= 76) ? '<span class="text-[color:var(--color-olive)]">meerderheid</span>' : '<span class="text-[color:var(--color-terracotta)]">minderheid</span>' ?>
                        <?php endif; ?>
                    </span>
                    <span><?= (int) $totaalZetels ?></span>
                </div>
            </div>

            <div class="keyline-card overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[color:var(--color-keyline)] bg-[color:var(--color-paper-2)]">
                            <th class="text-left font-medium text-[color:var(--color-ink-faint)] uppercase tracking-wider text-xs px-4 py-3 w-10">#</th>
                            <th class="text-left font-medium text-[color:var(--color-ink-faint)] uppercase tracking-wider text-xs px-4 py-3">Partij</th>
                            <th class="text-right font-medium text-[color:var(--color-ink-faint)] uppercase tracking-wider text-xs px-4 py-3 w-20">Zetels</th>
                            <th class="text-right font-medium text-[color:var(--color-ink-faint)] uppercase tracking-wider text-xs px-4 py-3 w-24">Stemmen</th>
                            <th class="text-right font-medium text-[color:var(--color-ink-faint)] uppercase tracking-wider text-xs px-4 py-3 w-20">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partijUitslagen as $index => $partij):
                            $isCoalition = is_array($coalitiePartijen) && in_array($partij->partij ?? '', $coalitiePartijen, true);
                        ?>
                            <tr class="border-b border-[color:var(--color-keyline)] last:border-b-0">
                                <td class="px-4 py-3 font-mono text-tabular text-[color:var(--color-ink-faint)]"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-[color:var(--color-ink)]"><?= pp_e((string) ($partij->partij ?? '')) ?></span>
                                    <?php if ($isCoalition): ?>
                                        <span class="badge badge--olive ml-2">Coalitie</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-tabular text-[color:var(--color-ink)]"><?= (int) ($partij->zetels ?? 0) ?></td>
                                <td class="px-4 py-3 text-right font-mono text-tabular text-[color:var(--color-ink-muted)]">
                                    <?= isset($partij->stemmen) && $partij->stemmen ? number_format((int) $partij->stemmen, 0, ',', '.') : '&mdash;' ?>
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-tabular text-[color:var(--color-ink-muted)]">
                                    <?= isset($partij->percentage) && $partij->percentage ? number_format((float) $partij->percentage, 1) . '%' : '&mdash;' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($grootsteWinnaar || $grootsteVerliezer): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if ($grootsteWinnaar): ?>
                <div class="keyline-card p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-3 text-[color:var(--color-olive)]">
                        <?= pp_icon('trending-up', 18) ?>
                        <div class="eyebrow text-[color:var(--color-olive)]">Grootste winnaar</div>
                    </div>
                    <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($grootsteWinnaar) ?></h3>
                    <?php if ($grootsteWinnaarAantal !== null): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)] font-mono text-tabular">+<?= (int) $grootsteWinnaarAantal ?> zetels</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($grootsteVerliezer): ?>
                <div class="keyline-card p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-3 text-[color:var(--color-terracotta)]">
                        <?= pp_icon('alert-triangle', 18) ?>
                        <div class="eyebrow text-[color:var(--color-terracotta)]">Grootste verliezer</div>
                    </div>
                    <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($grootsteVerliezer) ?></h3>
                    <?php if ($grootsteVerliezerAantal !== null): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)] font-mono text-tabular"><?= (int) $grootsteVerliezerAantal ?> zetels</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($themas) || !empty($nieuwePartijen) || !empty($verdwenenPartijen)): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <?php if (!empty($themas)): ?>
                <div class="keyline-card p-6">
                    <div class="eyebrow mb-3">Belangrijkste thema's</div>
                    <ul class="space-y-2">
                        <?php foreach ($themas as $thema): ?>
                            <li class="flex items-start gap-2 text-sm text-[color:var(--color-ink)]">
                                <span class="text-[color:var(--color-hague)] mt-0.5 flex-shrink-0"><?= pp_icon('check', 14) ?></span>
                                <span><?= pp_e($thema) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (!empty($nieuwePartijen)): ?>
                <div class="keyline-card p-6">
                    <div class="eyebrow mb-3">Nieuwe partijen</div>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($nieuwePartijen as $partij): ?>
                            <span class="chip chip--olive"><?= pp_e($partij) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($verdwenenPartijen)): ?>
                <div class="keyline-card p-6">
                    <div class="eyebrow mb-3">Verdwenen partijen</div>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($verdwenenPartijen as $partij): ?>
                            <span class="chip chip--terracotta"><?= pp_e($partij) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($lijsttrekkers)): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Lijsttrekkers</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Gezichten van de campagne</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($lijsttrekkers as $l):
                    $naam = is_object($l) ? ($l->naam ?? '') : (is_array($l) ? ($l['naam'] ?? '') : (string) $l);
                    $partij = is_object($l) ? ($l->partij ?? '') : (is_array($l) ? ($l['partij'] ?? '') : '');
                ?>
                    <div class="border-l-2 border-[color:var(--color-hague)] pl-4 py-1">
                        <div class="font-display text-base text-[color:var(--color-ink)]"><?= pp_e($naam) ?></div>
                        <?php if ($partij): ?>
                            <div class="text-sm text-[color:var(--color-ink-muted)]"><?= pp_e($partij) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
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

    <?php if (!empty($bronnen)): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Bronnen en referenties</div>
            <ul class="space-y-3">
                <?php foreach ($bronnen as $bron):
                    $naam = is_object($bron) ? ($bron->naam ?? '') : (is_array($bron) ? ($bron['naam'] ?? '') : (string) $bron);
                    $url  = is_object($bron) ? ($bron->url ?? '')  : (is_array($bron) ? ($bron['url']  ?? '') : '');
                ?>
                    <li class="flex items-start gap-3 text-sm">
                        <span class="text-[color:var(--color-ink-faint)] mt-0.5 flex-shrink-0"><?= pp_icon('external-link', 14) ?></span>
                        <div>
                            <div class="text-[color:var(--color-ink)] font-medium"><?= pp_e($naam) ?></div>
                            <?php if ($url): ?>
                                <a href="<?= pp_e($url) ?>" target="_blank" rel="noopener noreferrer" class="text-[color:var(--color-hague)] hover:underline break-all">
                                    <?= pp_e($url) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
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
                    <a href="<?= URLROOT ?>/nederlandse-verkiezingen/<?= pp_e((string) $vorige->jaar) ?>"
                       class="keyline-card p-6 md:p-8 group hover:border-[color:var(--color-hague)] transition-colors block">
                        <div class="flex items-center gap-2 text-[color:var(--color-ink-muted)] mb-3">
                            <?= pp_icon('arrow-left', 14) ?>
                            <span class="eyebrow">Vorige verkiezing</span>
                        </div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] mb-2 leading-none"><?= pp_e((string) $vorige->jaar) ?></div>
                        <div class="text-sm text-[color:var(--color-ink-muted)]">
                            Grootste partij: <span class="text-[color:var(--color-ink)] font-medium"><?= pp_e($vorige->grootste_partij ?? 'Onbekend') ?></span><br>
                            MP: <?= pp_e($vorige->minister_president ?? 'Onbekend') ?>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (!empty($gerelateerdeVerkiezingen['volgende'])):
                    $volgende = $gerelateerdeVerkiezingen['volgende'];
                ?>
                    <a href="<?= URLROOT ?>/nederlandse-verkiezingen/<?= pp_e((string) $volgende->jaar) ?>"
                       class="keyline-card p-6 md:p-8 group hover:border-[color:var(--color-hague)] transition-colors block">
                        <div class="flex items-center justify-end gap-2 text-[color:var(--color-ink-muted)] mb-3">
                            <span class="eyebrow">Volgende verkiezing</span>
                            <?= pp_icon('arrow-right', 14) ?>
                        </div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] mb-2 leading-none text-right"><?= pp_e((string) $volgende->jaar) ?></div>
                        <div class="text-sm text-[color:var(--color-ink-muted)] text-right">
                            Grootste partij: <span class="text-[color:var(--color-ink)] font-medium"><?= pp_e($volgende->grootste_partij ?? 'Onbekend') ?></span><br>
                            MP: <?= pp_e($volgende->minister_president ?? 'Onbekend') ?>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="<?= URLROOT ?>/nederlandse-verkiezingen" class="btn btn--ghost">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar alle Nederlandse verkiezingen
        </a>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
