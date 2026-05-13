<?php
/**
 * Editorial party detail page (Wave 3).
 *
 * Beschikbare data:
 *   $party      array (zie PartyModel::getAllParties())
 *   $partyKey   string
 *   $partyColor hex
 */
require_once BASE_PATH . '/views/templates/header.php';

$standpoints = $party['standpoints'] ?? [];
if (!is_array($standpoints)) $standpoints = [];

$perspectives = $party['perspectives'] ?? [];
if (!is_array($perspectives)) $perspectives = [];

$pollingSeats = (int) ($party['polling']['seats'] ?? 0);
$currentSeats = (int) ($party['current_seats'] ?? 0);
$pollingChange = $pollingSeats - $currentSeats;
?>

<article class="bg-[color:var(--color-canvas)]">
    <header class="pp-container pp-container--xl pt-10 md:pt-14 pb-12">
        <a href="<?= pp_e(pp_url('/partijen')) ?>"
           class="inline-flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-ink)] mb-8">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar alle partijen
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-10 items-end">
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 999px; background: <?= pp_e($partyColor) ?>;"></span>
                    <span class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)]">
                        <?= pp_e($partyKey) ?>
                    </span>
                </div>
                <h1 class="font-display text-display-2xl md:text-display-3xl leading-[1.02] tracking-tight text-[color:var(--color-ink)] mb-4">
                    <?= pp_e($party['name'] ?? $partyKey) ?>
                </h1>
                <?php if (!empty($party['description'])): ?>
                    <p class="font-display text-xl md:text-2xl leading-snug text-[color:var(--color-ink-muted)] max-w-3xl">
                        <?= pp_e(mb_substr(strip_tags($party['description']), 0, 280)) ?><?= mb_strlen(strip_tags($party['description'])) > 280 ? '...' : '' ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($party['logo'])): ?>
                <div class="hidden lg:flex w-32 h-32 rounded-lg border border-[color:var(--color-keyline)] bg-white p-4 items-center justify-center flex-shrink-0">
                    <img src="<?= pp_e($party['logo']) ?>" alt="<?= pp_e($party['name'] ?? $partyKey) ?>" class="max-w-full max-h-full object-contain">
                </div>
            <?php endif; ?>
        </div>

        <div style="height: 2px; background: <?= pp_e($partyColor) ?>; margin-top: 2.5rem; opacity: 0.6;"></div>
    </header>

    <section class="pp-container pp-container--xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Huidige zetels</div>
                <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= $currentSeats ?></div>
            </div>
            <div class="keyline-card p-5">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">In peilingen</div>
                <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= $pollingSeats ?></div>
                <?php if ($pollingChange !== 0): ?>
                    <div class="text-xs mt-1 font-mono"
                         style="color: <?= $pollingChange > 0 ? 'var(--color-moss-deep, #4a6741)' : 'var(--color-terracotta)' ?>;">
                        <?= $pollingChange > 0 ? '+' : '' ?><?= $pollingChange ?> vs. huidig
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($party['leader'])): ?>
                <div class="keyline-card p-5 col-span-2">
                    <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">Lijsttrekker</div>
                    <div class="flex items-center gap-4">
                        <?php if (!empty($party['leader_photo'])): ?>
                            <img src="<?= pp_e($party['leader_photo']) ?>" alt="<?= pp_e($party['leader']) ?>"
                                 class="w-12 h-12 rounded-full object-cover border border-[color:var(--color-keyline)]">
                        <?php endif; ?>
                        <div class="min-w-0">
                            <div class="font-display text-lg text-[color:var(--color-ink)] truncate"><?= pp_e($party['leader']) ?></div>
                            <?php if (!empty($party['leader_info'])): ?>
                                <div class="text-sm text-[color:var(--color-ink-muted)] line-clamp-2">
                                    <?= pp_e(mb_substr($party['leader_info'], 0, 140)) ?><?= mb_strlen($party['leader_info']) > 140 ? '...' : '' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($party['leader_info'])): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Lijsttrekker',
                'title' => 'Over ' . ($party['leader'] ?? ''),
            ]) ?>
            <div class="prose-editorial">
                <p><?= nl2br(pp_e($party['leader_info'])) ?></p>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($standpoints)): ?>
        <section class="pp-container pp-container--xl mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Standpunten',
                'title' => 'Wat staat er in het programma?',
            ]) ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php
                $i = 1;
                foreach ($standpoints as $topic => $standpoint):
                ?>
                    <div class="num-item">
                        <span class="num-item__index"><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
                        <div class="num-item__body">
                            <h3 class="num-item__title"><?= pp_e(ucfirst($topic)) ?></h3>
                            <p class="num-item__lead"><?= pp_e($standpoint) ?></p>
                        </div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($perspectives) && (!empty($perspectives['left']) || !empty($perspectives['right']))): ?>
        <section class="pp-container pp-container--md mt-20">
            <?= pp_render_component('section/section-header', [
                'label' => 'Perspectieven',
                'title' => 'Hoe wordt er over deze partij gedacht?',
            ]) ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($perspectives['left'])): ?>
                    <div class="keyline-card p-6">
                        <div class="text-xs uppercase tracking-[0.18em] mb-3" style="color: var(--color-rose-deep, #b1545a);">
                            Linkerflank
                        </div>
                        <p class="text-[color:var(--color-ink)] leading-relaxed">
                            <?= pp_e($perspectives['left']) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($perspectives['right'])): ?>
                    <div class="keyline-card p-6">
                        <div class="text-xs uppercase tracking-[0.18em] mb-3" style="color: var(--color-hague);">
                            Rechterflank
                        </div>
                        <p class="text-[color:var(--color-ink)] leading-relaxed">
                            <?= pp_e($perspectives['right']) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="pp-container pp-container--xl mt-20 mb-24">
        <div class="keyline-card p-8 md:p-10">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                Vergelijk
            </div>
            <h3 class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)] mb-4">
                Hoe verhoudt <?= pp_e($party['name'] ?? $partyKey) ?> zich tot andere partijen?
            </h3>
            <div class="flex flex-wrap gap-3">
                <a href="<?= pp_e(pp_url('/partijen')) ?>" class="btn btn--ghost">
                    <?= pp_icon('users', 16) ?>
                    Alle partijen
                </a>
                <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="btn btn--secondary">
                    <?= pp_icon('scale', 16) ?>
                    Politiek Kompas
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
