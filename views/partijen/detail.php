<?php
/**
 * Uitgebreide partij-detailpagina.
 *
 * Beschikbare data:
 *   $party               array (PartyModel::getParty())
 *   $partyKey            string  (bijv. 'D66', 'GL-PvdA')
 *   $partyColor          hex
 *   $profile             array|null  (includes/data/partijen_profiel.php)
 *   $partyThemePositions array       (standpunt per thema)
 */
require_once BASE_PATH . '/views/templates/header.php';

$profile = $profile ?? null;
$positions = $partyThemePositions ?? [];

$pollingSeats = (int) ($party['polling']['seats'] ?? 0);
$currentSeats = (int) ($party['current_seats'] ?? 0);
$pollingChange = $pollingSeats - $currentSeats;

$coalitionParties = ['D66', 'VVD', 'CDA'];
$inCoalition = in_array($partyKey, $coalitionParties, true);

$heroImage = '';
if ($profile && !empty($profile['hero_image']) && file_exists(BASE_PATH . $profile['hero_image'])) {
    $heroImage = $profile['hero_image'];
}

$leaderPhoto = $party['leader_photo'] ?? ($profile['leider_foto'] ?? '');
$leaderName = $profile['leider'] ?? ($party['leader'] ?? '');
$leaderRole = $profile['leider_rol'] ?? '';
$fractieLeider = $profile['fractievoorzitter'] ?? '';

$seatHistory = ($profile['seat_history'] ?? []);
$historyMax = 1;
foreach ($seatHistory as $v) { $historyMax = max($historyMax, (int) $v); }
$historyMax = max($historyMax, $currentSeats, $pollingSeats);
?>

<article class="bg-[color:var(--color-canvas)]">

    <!-- HERO -->
    <header class="pp-container pp-container--xl pt-8 md:pt-12 pb-10">
        <a href="<?= pp_e(pp_url('/partijen')) ?>"
           class="inline-flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-ink)] mb-8">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar alle partijen
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-10 items-center">
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:<?= pp_e($partyColor) ?>;"></span>
                    <span class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)]"><?= pp_e($partyKey) ?></span>
                    <?php if (!empty($profile['stroming'])): ?>
                        <span class="text-[color:var(--color-ink-faint)]">/</span>
                        <span class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)]"><?= pp_e($profile['stroming']) ?></span>
                    <?php endif; ?>
                </div>

                <h1 class="font-display text-display-2xl md:text-display-3xl leading-[1.02] tracking-tight text-[color:var(--color-ink)] mb-4">
                    <?= pp_e($party['name'] ?? $partyKey) ?>
                </h1>

                <?php if (!empty($profile['tagline'])): ?>
                    <p class="font-display text-xl md:text-2xl leading-snug text-[color:var(--color-ink-muted)] max-w-2xl mb-6">
                        <?= pp_e($profile['tagline']) ?>
                    </p>
                <?php endif; ?>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium"
                          style="background:<?= pp_e(adjustColorOpacity($partyColor, 0.12)) ?>;color:<?= pp_e($partyColor) ?>;">
                        <?= pp_icon('users', 14) ?>
                        <?= $currentSeats ?> zetels
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-keyline)] px-3 py-1.5 text-sm text-[color:var(--color-ink-muted)]">
                        <?= $inCoalition ? pp_icon('check-circle', 14) . ' Regeringspartij' : pp_icon('message-circle', 14) . ' Oppositie' ?>
                    </span>
                    <?php if (!empty($profile['founded'])): ?>
                        <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-keyline)] px-3 py-1.5 text-sm text-[color:var(--color-ink-muted)]">
                            <?= pp_icon('flag', 14) ?> Opgericht in <?= (int) $profile['founded'] ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="relative">
                <?php if ($heroImage): ?>
                    <div class="relative rounded-xl overflow-hidden border border-[color:var(--color-keyline)] aspect-[4/3]">
                        <img src="<?= pp_e(pp_url($heroImage)) ?>" alt="<?= pp_e($profile['hero_alt'] ?? ($party['name'] ?? $partyKey)) ?>"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:<?= pp_e($partyColor) ?>;"></div>
                    </div>
                <?php else: ?>
                    <div class="relative rounded-xl overflow-hidden border border-[color:var(--color-keyline)] aspect-[4/3] flex items-center justify-center"
                         style="background:linear-gradient(135deg, <?= pp_e(adjustColorOpacity($partyColor, 0.14)) ?>, <?= pp_e(adjustColorOpacity($partyColor, 0.04)) ?>);">
                        <?php if (!empty($party['logo'])): ?>
                            <img src="<?= pp_e($party['logo']) ?>" alt="<?= pp_e($party['name'] ?? $partyKey) ?>" class="max-h-28 max-w-[60%] object-contain">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($party['logo']) && $heroImage): ?>
                    <div class="absolute -bottom-5 -left-3 w-20 h-20 rounded-lg border border-[color:var(--color-keyline)] bg-white p-2.5 flex items-center justify-center shadow-sm">
                        <img src="<?= pp_e($party['logo']) ?>" alt="<?= pp_e($party['name'] ?? $partyKey) ?>" class="max-w-full max-h-full object-contain">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="height:2px;background:<?= pp_e($partyColor) ?>;margin-top:2.5rem;opacity:0.55;"></div>
    </header>

    <!-- STATS -->
    <section class="pp-container pp-container--xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Huidige zetels</div>
                <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= $currentSeats ?></div>
                <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">van de 150</div>
            </div>
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">In peilingen</div>
                <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= $pollingSeats ?: '-' ?></div>
                <?php if ($pollingChange !== 0 && $pollingSeats > 0): ?>
                    <div class="text-xs mt-1 font-mono" style="color: <?= $pollingChange > 0 ? 'var(--color-moss-deep, #4a6741)' : 'var(--color-terracotta)' ?>;">
                        <?= $pollingChange > 0 ? '+' : '' ?><?= $pollingChange ?> vs. huidig
                    </div>
                <?php endif; ?>
            </div>
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Stroming</div>
                <div class="font-display text-lg text-[color:var(--color-ink)] leading-tight mt-1"><?= pp_e($profile['stroming'] ?? '-') ?></div>
            </div>
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Opgericht</div>
                <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) ($profile['founded'] ?? 0) ?: '-' ?></div>
                <?php if (!empty($profile['founder'])): ?>
                    <div class="text-sm text-[color:var(--color-ink-muted)] mt-1 line-clamp-2"><?= pp_e($profile['founder']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- OVER DE PARTIJ -->
    <section class="pp-container pp-container--md mt-20">
        <?= pp_render_component('section/section-header', [
            'label' => 'Over de partij',
            'title' => 'Waar staat ' . ($party['name'] ?? $partyKey) . ' voor?',
        ]) ?>
        <div class="prose-editorial">
            <?php if (!empty($profile['intro'])): ?>
                <p><?= pp_e($profile['intro']) ?></p>
            <?php endif; ?>
            <?php if (!empty($profile['stroming_lang'])): ?>
                <p><?= pp_e($profile['stroming_lang']) ?></p>
            <?php elseif (!empty($party['description'])): ?>
                <p><?= pp_e($party['description']) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- LIJSTTREKKER / LEIDER -->
    <?php if ($leaderName): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Politiek leider',
                'title' => $leaderName,
            ]) ?>
            <div class="keyline-card p-6 md:p-8">
                <div class="flex flex-col sm:flex-row gap-6">
                    <?php if (!empty($leaderPhoto)): ?>
                        <img src="<?= pp_e($leaderPhoto) ?>" alt="<?= pp_e($leaderName) ?>"
                             class="w-28 h-28 rounded-xl object-cover border border-[color:var(--color-keyline)] flex-shrink-0">
                    <?php endif; ?>
                    <div class="min-w-0">
                        <?php if ($leaderRole): ?>
                            <p class="text-sm font-medium text-[color:var(--color-ink)] mb-1"><?= pp_e($leaderRole) ?></p>
                        <?php endif; ?>
                        <?php if ($fractieLeider && $fractieLeider !== $leaderName): ?>
                            <p class="text-sm text-[color:var(--color-ink-muted)] mb-3">
                                Fractievoorzitter in de Tweede Kamer: <strong class="text-[color:var(--color-ink)]"><?= pp_e($fractieLeider) ?></strong>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($party['leader_info'])): ?>
                            <p class="text-[color:var(--color-ink-muted)] leading-relaxed"><?= nl2br(pp_e($party['leader_info'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- KERNPUNTEN -->
    <?php if (!empty($profile['kernpunten'])): ?>
        <section class="pp-container pp-container--xl mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Kernpunten',
                'title' => 'De belangrijkste speerpunten',
            ]) ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php $i = 1; foreach ($profile['kernpunten'] as $punt): ?>
                    <div class="num-item">
                        <span class="num-item__index"><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
                        <div class="num-item__body">
                            <p class="num-item__lead"><?= pp_e($punt) ?></p>
                        </div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- STANDPUNT PER THEMA -->
    <?php if (!empty($positions)): ?>
        <section class="pp-container pp-container--xl mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Standpunten per thema',
                'title' => 'Wat vindt ' . ($party['name'] ?? $partyKey) . ' over...',
            ]) ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php foreach ($positions as $pos): ?>
                    <a href="<?= pp_e(pp_url('/thema/' . $pos['slug'])) ?>"
                       class="keyline-card p-5 flex gap-4 hover:bg-[color:var(--color-paper-2)] transition group">
                        <span class="flex-shrink-0 w-11 h-11 rounded-lg flex items-center justify-center"
                              style="background:<?= pp_e(adjustColorOpacity($partyColor, 0.10)) ?>;color:<?= pp_e($partyColor) ?>;">
                            <?= pp_icon($pos['icon'], 20) ?>
                        </span>
                        <div class="min-w-0">
                            <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-1 group-hover:underline"><?= pp_e($pos['title']) ?></h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($pos['tekst']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ZETELHISTORIE -->
    <?php if (!empty($seatHistory)): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Zetelhistorie',
                'title' => 'Resultaten bij de verkiezingen',
            ]) ?>
            <div class="keyline-card p-6 md:p-8">
                <div class="space-y-5">
                    <?php
                    $bars = $seatHistory;
                    if ($pollingSeats > 0) { $bars['Peiling 2026'] = $pollingSeats; }
                    foreach ($bars as $label => $val):
                        $val = (int) $val;
                        $pct = $historyMax > 0 ? round(($val / $historyMax) * 100) : 0;
                        $isPoll = ($label === 'Peiling 2026');
                    ?>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm <?= $isPoll ? 'text-[color:var(--color-ink-muted)] italic' : 'text-[color:var(--color-ink)] font-medium' ?>"><?= pp_e((string) $label) ?></span>
                                <span class="font-mono text-tabular text-sm text-[color:var(--color-ink)]"><?= $val ?> zetels</span>
                            </div>
                            <div class="h-3 rounded-full bg-[color:var(--color-paper-3)] overflow-hidden">
                                <div class="h-full rounded-full" style="width: <?= $pct ?>%; background: <?= $isPoll ? pp_e(adjustColorOpacity($partyColor, 0.45)) : pp_e($partyColor) ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-[color:var(--color-ink-faint)] mt-6">
                    Zetels bij de Tweede Kamerverkiezingen. Peilingen zijn een momentopname en geen voorspelling.
                </p>
            </div>
        </section>
    <?php endif; ?>

    <!-- STERKE PUNTEN / KRITIEK -->
    <?php if (!empty($profile['sterke_punten']) || !empty($profile['kritiek'])): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Twee kanten',
                'title' => 'Sterke punten en kritiek',
            ]) ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($profile['sterke_punten'])): ?>
                    <div class="keyline-card p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span style="color: var(--color-moss-deep, #4a6741);"><?= pp_icon('thumbs-up', 18) ?></span>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)]">Wat aanhangers waarderen</h3>
                        </div>
                        <ul class="space-y-3">
                            <?php foreach ($profile['sterke_punten'] as $p): ?>
                                <li class="flex gap-3 text-[color:var(--color-ink-muted)] leading-relaxed">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-shrink-0" style="background: var(--color-moss-deep, #4a6741);"></span>
                                    <span><?= pp_e($p) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (!empty($profile['kritiek'])): ?>
                    <div class="keyline-card p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span style="color: var(--color-terracotta);"><?= pp_icon('message-circle', 18) ?></span>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)]">Veelgehoorde kritiek</h3>
                        </div>
                        <ul class="space-y-3">
                            <?php foreach ($profile['kritiek'] as $p): ?>
                                <li class="flex gap-3 text-[color:var(--color-ink-muted)] leading-relaxed">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-shrink-0" style="background: var(--color-terracotta);"></span>
                                    <span><?= pp_e($p) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- GESCHIEDENIS -->
    <?php if (!empty($profile['history'])): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Geschiedenis',
                'title' => 'De partij door de jaren heen',
            ]) ?>
            <div class="space-y-0">
                <?php foreach ($profile['history'] as $h): ?>
                    <div class="flex gap-5 pb-8 last:pb-0 relative">
                        <div class="flex-shrink-0 w-20 text-right">
                            <span class="font-mono text-sm font-semibold" style="color: <?= pp_e($partyColor) ?>;"><?= pp_e($h['jaar']) ?></span>
                        </div>
                        <div class="relative flex flex-col items-center">
                            <span class="w-3 h-3 rounded-full flex-shrink-0 mt-1" style="background: <?= pp_e($partyColor) ?>;"></span>
                            <span class="w-px flex-1 bg-[color:var(--color-keyline)] mt-1"></span>
                        </div>
                        <div class="min-w-0 pb-2">
                            <p class="text-[color:var(--color-ink-muted)] leading-relaxed"><?= pp_e($h['tekst']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- CTA -->
    <section class="pp-container pp-container--xl mt-20 mb-24">
        <div class="keyline-card p-8 md:p-10">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">Verder kijken</div>
            <h3 class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)] mb-4">
                Hoe verhoudt <?= pp_e($party['name'] ?? $partyKey) ?> zich tot de andere partijen?
            </h3>
            <div class="flex flex-wrap gap-3">
                <?php if (!empty($profile['website'])): ?>
                    <a href="<?= pp_e($profile['website']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">
                        <?= pp_icon('external-link', 16) ?>
                        Officiele website
                    </a>
                <?php endif; ?>
                <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="btn btn--ghost">
                    <?= pp_icon('scale', 16) ?>
                    Politiek Kompas
                </a>
                <a href="<?= pp_e(pp_url('/partijen')) ?>" class="btn btn--secondary">
                    <?= pp_icon('users', 16) ?>
                    Alle partijen
                </a>
                <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary">
                    <?= pp_icon('vote', 16) ?>
                    Doe de Partijmeter
                </a>
            </div>
        </div>
    </section>
</article>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
