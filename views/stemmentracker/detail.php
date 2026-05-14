<?php
$pageTitle = htmlspecialchars($motie->title) . ' - Stemmentracker';
$pageDescription = 'Bekijk hoe alle politieke partijen hebben gestemd over: ' . htmlspecialchars($motie->title);
$pageKeywords = 'stemmentracker, ' . htmlspecialchars($motie->onderwerp) . ', stemgedrag, tweede kamer, politieke partijen';

include_once 'views/templates/header.php';

if (!function_exists('pp_motie_date_long')) {
    function pp_motie_date_long($value): string {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        $months = [
            1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
            5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
            9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
        ];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$voorCount = 0;
$tegenCount = 0;
$nietGestemdCount = 0;
$afwezigCount = 0;
$totalVotes = 0;
$voorParties = [];
$tegenParties = [];
$nietGestemdParties = [];
$afwezigParties = [];

if (!empty($stemgedrag)) {
    foreach ($stemgedrag as $stem) {
        $totalVotes++;
        switch ($stem->vote) {
            case 'voor':
                $voorCount++;
                $voorParties[] = $stem;
                break;
            case 'tegen':
                $tegenCount++;
                $tegenParties[] = $stem;
                break;
            case 'niet_gestemd':
                $nietGestemdCount++;
                $nietGestemdParties[] = $stem;
                break;
            case 'afwezig':
                $afwezigCount++;
                $afwezigParties[] = $stem;
                break;
        }
    }
}

$uitslagBadgeMap = [
    'aangenomen'  => ['Aangenomen', 'badge--olive'],
    'verworpen'   => ['Verworpen', 'badge--terracotta'],
    'ingetrokken' => ['Ingetrokken', 'badge--ochre'],
];
$uitslagKey = $motie->uitslag ?? '';
$uitslagInfo = $uitslagBadgeMap[$uitslagKey] ?? null;

$heroMeta = [];
if (!empty($motie->datum_stemming)) {
    $heroMeta[] = ['icon' => 'calendar', 'text' => pp_motie_date_long($motie->datum_stemming)];
}
if (!empty($motie->onderwerp)) {
    $heroMeta[] = ['icon' => 'tag', 'text' => $motie->onderwerp];
}
if (!empty($motie->stemming_type)) {
    $heroMeta[] = ['icon' => 'vote', 'text' => ucfirst($motie->stemming_type)];
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Stemmentracker / Motie',
    'title'   => $motie->title ?? 'Motie',
    'lead'    => null,
    'meta'    => $heroMeta,
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

    <?php if ($uitslagInfo || !empty($motie_themas) || !empty($motie->motie_nummer)): ?>
        <div class="flex flex-wrap items-center gap-3">
            <?php if ($uitslagInfo): ?>
                <span class="badge <?= $uitslagInfo[1] ?>" style="font-size: 0.875rem; padding: 0.4rem 0.85rem;">
                    <?= pp_e($uitslagInfo[0]) ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($motie->motie_nummer)): ?>
                <span class="chip"><?= pp_e($motie->motie_nummer) ?></span>
            <?php endif; ?>
            <?php if (!empty($motie_themas)): ?>
                <?php foreach ($motie_themas as $thema): ?>
                    <span class="chip"><?= pp_e($thema->name) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($motie->description)): ?>
        <div class="keyline-card p-6 md:p-8">
            <div class="eyebrow mb-3">Motie</div>
            <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Wat staat er in de motie?</h2>
            <div class="prose-editorial max-w-prose text-[color:var(--color-ink)] leading-relaxed">
                <?= nl2br(pp_e($motie->description)) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php if (!empty($motie->indiener)): ?>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Indiener</div>
                <div class="font-display text-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e($motie->indiener) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($motie->onderwerp)): ?>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Onderwerp</div>
                <div class="font-display text-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e($motie->onderwerp) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($motie->stemming_type)): ?>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Stemming-type</div>
                <div class="font-display text-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e(ucfirst($motie->stemming_type)) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($motie->datum_stemming)): ?>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Datum stemming</div>
                <div class="font-display text-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e(pp_motie_date_long($motie->datum_stemming)) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($motie->kamer_stuk_url)): ?>
        <div>
            <a href="<?= pp_e($motie->kamer_stuk_url) ?>"
               target="_blank"
               rel="noopener"
               class="btn btn--ghost">
                <?= pp_icon('external-link', 14) ?>
                Bekijk officieel kamerstuk
            </a>
        </div>
    <?php endif; ?>

    <?php if (empty($stemgedrag)): ?>
        <div class="keyline-card p-8 md:p-12 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[color:var(--color-paper-2)] mb-4 text-[color:var(--color-ink-muted)]">
                <?= pp_icon('vote', 22) ?>
            </div>
            <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-2">Geen stemdata beschikbaar</h3>
            <p class="text-sm text-[color:var(--color-ink-muted)] max-w-md mx-auto">
                Voor deze motie is nog geen stemgedrag van politieke partijen vastgelegd in onze database.
            </p>
        </div>
    <?php else: ?>
        <div>
            <div class="eyebrow mb-3">Uitslag</div>
            <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Hoe stemden de partijen?</h2>

            <div class="keyline-card p-6 md:p-8 mb-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6">
                    <div class="border-l-2 border-[color:var(--color-olive)] pl-3">
                        <div class="eyebrow text-[color:var(--color-olive)] mb-1">Voor</div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $voorCount ?></div>
                        <p class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= $totalVotes > 0 ? round(($voorCount / $totalVotes) * 100) : 0 ?>%</p>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-terracotta)] pl-3">
                        <div class="eyebrow text-[color:var(--color-terracotta)] mb-1">Tegen</div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $tegenCount ?></div>
                        <p class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= $totalVotes > 0 ? round(($tegenCount / $totalVotes) * 100) : 0 ?>%</p>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-ochre)] pl-3">
                        <div class="eyebrow text-[color:var(--color-ochre)] mb-1">Niet gestemd</div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $nietGestemdCount ?></div>
                        <p class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= $totalVotes > 0 ? round(($nietGestemdCount / $totalVotes) * 100) : 0 ?>%</p>
                    </div>
                    <div class="border-l-2 border-[color:var(--color-ink-faint)] pl-3">
                        <div class="eyebrow mb-1">Afwezig</div>
                        <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $afwezigCount ?></div>
                        <p class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= $totalVotes > 0 ? round(($afwezigCount / $totalVotes) * 100) : 0 ?>%</p>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-[color:var(--color-ink-muted)] mb-2 font-mono text-tabular flex justify-between">
                        <span><?= $totalVotes ?> partijen</span>
                        <span>100%</span>
                    </div>
                    <div class="h-3 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden flex">
                        <?php if ($voorCount > 0): ?>
                            <div class="bg-[color:var(--color-olive)]" style="width: <?= ($voorCount / $totalVotes) * 100 ?>%;" title="Voor"></div>
                        <?php endif; ?>
                        <?php if ($tegenCount > 0): ?>
                            <div class="bg-[color:var(--color-terracotta)]" style="width: <?= ($tegenCount / $totalVotes) * 100 ?>%;" title="Tegen"></div>
                        <?php endif; ?>
                        <?php if ($nietGestemdCount > 0): ?>
                            <div class="bg-[color:var(--color-ochre)]" style="width: <?= ($nietGestemdCount / $totalVotes) * 100 ?>%;" title="Niet gestemd"></div>
                        <?php endif; ?>
                        <?php if ($afwezigCount > 0): ?>
                            <div class="bg-[color:var(--color-ink-faint)]" style="width: <?= ($afwezigCount / $totalVotes) * 100 ?>%;" title="Afwezig"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                $voteColumns = [
                    ['label' => 'Voor', 'count' => $voorCount, 'parties' => $voorParties, 'color' => 'var(--color-olive)', 'tintBg' => 'var(--color-olive-tint)', 'icon' => 'thumbs-up'],
                    ['label' => 'Tegen', 'count' => $tegenCount, 'parties' => $tegenParties, 'color' => 'var(--color-terracotta)', 'tintBg' => 'var(--color-terracotta-tint)', 'icon' => 'thumbs-down'],
                    ['label' => 'Niet gestemd', 'count' => $nietGestemdCount, 'parties' => $nietGestemdParties, 'color' => 'var(--color-ochre)', 'tintBg' => 'var(--color-paper-2)', 'icon' => 'minus'],
                    ['label' => 'Afwezig', 'count' => $afwezigCount, 'parties' => $afwezigParties, 'color' => 'var(--color-ink-faint)', 'tintBg' => 'var(--color-paper-2)', 'icon' => 'user-x'],
                ];
                ?>
                <?php foreach ($voteColumns as $col): ?>
                    <?php if ($col['count'] > 0): ?>
                        <div class="keyline-card p-6">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-[color:var(--color-keyline)]">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full"
                                          style="background: <?= $col['tintBg'] ?>; color: <?= $col['color'] ?>;">
                                        <?= pp_icon($col['icon'], 16) ?>
                                    </span>
                                    <h3 class="font-display text-display-md text-[color:var(--color-ink)] leading-none"><?= pp_e($col['label']) ?></h3>
                                </div>
                                <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-muted)]">
                                    <?= $col['count'] ?> <?= $col['count'] === 1 ? 'partij' : 'partijen' ?>
                                </span>
                            </div>
                            <ul class="space-y-3">
                                <?php foreach ($col['parties'] as $stem): ?>
                                    <li class="flex items-start gap-3">
                                        <?php if (!empty($stem->logo_url)): ?>
                                            <img src="<?= pp_e($stem->logo_url) ?>"
                                                 alt="<?= pp_e($stem->party_name) ?>"
                                                 loading="lazy"
                                                 class="w-9 h-9 rounded-sm object-contain flex-shrink-0 mt-0.5"
                                                 style="background: var(--color-paper-2); border: 1px solid var(--color-keyline);">
                                        <?php else: ?>
                                            <span class="w-9 h-9 rounded-sm flex items-center justify-center flex-shrink-0 mt-0.5"
                                                  style="background: var(--color-paper-2); border: 1px solid var(--color-keyline); color: var(--color-ink-faint);">
                                                <?= pp_icon('flag', 16) ?>
                                            </span>
                                        <?php endif; ?>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-display text-[color:var(--color-ink)] leading-tight"><?= pp_e($stem->party_name) ?></div>
                                            <?php if (!empty($stem->opmerking)): ?>
                                                <div class="text-xs text-[color:var(--color-ink-muted)] mt-1 leading-snug"><?= pp_e($stem->opmerking) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="<?= pp_url('/stemmentracker') ?>" class="btn btn--ghost">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar Stemmentracker
        </a>
    </div>
</section>

<?php include_once 'views/templates/footer.php'; ?>
