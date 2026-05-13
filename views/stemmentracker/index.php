<?php
$pageTitle = 'Stemmentracker - Stemgedrag in de Tweede Kamer';
$pageDescription = 'Bekijk hoe Nederlandse politieke partijen stemmen over moties in de Tweede Kamer. Filter op thema, onderwerp en uitslag.';

include_once 'views/templates/header.php';

$moties = $moties ?? [];
$themas = $themas ?? [];
$onderwerpen = $onderwerpen ?? [];
$statistieken = $statistieken ?? [];
$filters = $filters ?? ['search' => '', 'thema' => '', 'onderwerp' => '', 'uitslag' => '', 'jaar' => '', 'sort' => 'datum_desc'];

if (!function_exists('pp_motie_date')) {
    function pp_motie_date($value): string {
        if (empty($value)) return '';
        $ts = is_int($value) ? $value : strtotime((string) $value);
        if (!$ts) return '';
        $months = [1=>'jan','feb','mrt','apr','mei','jun','jul','aug','sep','okt','nov','dec'];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$uitslagBadge = [
    'aangenomen' => ['Aangenomen', 'olive'],
    'verworpen' => ['Verworpen', 'terracotta'],
    'ingetrokken' => ['Ingetrokken', 'neutral'],
];
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Stemmentracker',
    'title'   => 'Hoe stemde de Tweede Kamer?',
    'lead'    => 'Een doorzoekbaar archief van moties: filter op thema, onderwerp of uitslag en zie per motie het stemgedrag van alle partijen.',
]) ?>

<?php
$uitslagCount = ['aangenomen' => 0, 'verworpen' => 0, 'ingetrokken' => 0];
if (!empty($statistieken['uitslagen'])) {
    foreach ($statistieken['uitslagen'] as $row) {
        if (!empty($row->uitslag)) {
            $uitslagCount[$row->uitslag] = (int) ($row->count ?? 0);
        }
    }
}
?>

<section class="pp-container pp-container--xl mt-10 mb-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Moties</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format((int) ($statistieken['total_moties'] ?? 0), 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-olive)] mb-2">Aangenomen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format($uitslagCount['aangenomen'], 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-terracotta)] mb-2">Verworpen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format($uitslagCount['verworpen'], 0, ',', '.') ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Recent (3 mnd)</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= number_format((int) ($statistieken['recent_count'] ?? 0), 0, ',', '.') ?></div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mb-12">
    <form method="GET" action="<?= pp_e(pp_url('/stemmentracker')) ?>"
          class="keyline-card p-5 md:p-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-5">
            <label for="st-search" class="field__label">Zoek op titel, indiener of beschrijving</label>
            <input type="search" id="st-search" name="search" class="input"
                   value="<?= pp_e($filters['search']) ?>"
                   placeholder="Bijv. asielzoekers, klimaat, AOW...">
        </div>
        <div class="md:col-span-3">
            <label for="st-thema" class="field__label">Thema</label>
            <select id="st-thema" name="thema" class="select">
                <option value="">Alle thema's</option>
                <?php foreach ($themas as $thema): ?>
                    <option value="<?= pp_e($thema->name) ?>"
                            <?= $filters['thema'] === $thema->name ? 'selected' : '' ?>>
                        <?= pp_e($thema->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label for="st-uitslag" class="field__label">Uitslag</label>
            <select id="st-uitslag" name="uitslag" class="select">
                <option value="">Alle</option>
                <option value="aangenomen" <?= $filters['uitslag'] === 'aangenomen' ? 'selected' : '' ?>>Aangenomen</option>
                <option value="verworpen" <?= $filters['uitslag'] === 'verworpen' ? 'selected' : '' ?>>Verworpen</option>
                <option value="ingetrokken" <?= $filters['uitslag'] === 'ingetrokken' ? 'selected' : '' ?>>Ingetrokken</option>
            </select>
        </div>
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="btn btn--primary w-full">
                <?= pp_icon('filter', 14) ?>
                Filter
            </button>
        </div>
    </form>
</section>

<section class="pp-container pp-container--xl mb-24">
    <?= pp_render_component('section/section-header', [
        'label' => 'Resultaten',
        'title' => count($moties) . ' moties gevonden',
    ]) ?>

    <?php if (empty($moties)): ?>
        <div class="text-center py-16 text-[color:var(--color-ink-muted)]">
            <div class="flex justify-center mb-4 text-[color:var(--color-ink-faint)]"><?= pp_icon('inbox', 32) ?></div>
            Geen moties gevonden. Pas de filters aan.
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($moties as $motie): ?>
                <?php
                $uitslag = $motie->uitslag ?? '';
                [$uitslagLabel, $uitslagAccent] = $uitslagBadge[$uitslag] ?? ['Onbekend', 'neutral'];
                $themaList = !empty($motie->themas) ? explode(', ', $motie->themas) : [];
                ?>
                <a href="<?= pp_e(pp_url('/stemmentracker?action=detail&id=' . (int) $motie->id)) ?>"
                   class="keyline-card p-5 md:p-6 block transition hover:border-[color:var(--color-line-strong)]">
                    <div class="flex flex-wrap items-center gap-3 mb-3 text-xs">
                        <span class="badge badge--<?= pp_e($uitslagAccent) ?>"><?= pp_e($uitslagLabel) ?></span>
                        <?php foreach (array_slice($themaList, 0, 3) as $themaName): ?>
                            <span class="font-display text-[color:var(--color-ink-muted)]"><?= pp_e($themaName) ?></span>
                        <?php endforeach; ?>
                        <span class="ml-auto font-mono text-tabular text-[color:var(--color-ink-faint)]">
                            <?= pp_e(pp_motie_date($motie->datum_stemming ?? '')) ?>
                        </span>
                    </div>
                    <h3 class="font-display text-lg md:text-xl text-[color:var(--color-ink)] leading-tight mb-2">
                        <?= pp_e($motie->title ?? '') ?>
                    </h3>
                    <?php if (!empty($motie->description)): ?>
                        <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed line-clamp-2 mb-3">
                            <?= pp_e(mb_substr((string) $motie->description, 0, 220)) ?><?= mb_strlen((string) $motie->description) > 220 ? '...' : '' ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-[color:var(--color-ink-faint)]">
                        <?php if (!empty($motie->indiener)): ?>
                            <span><strong class="text-[color:var(--color-ink-muted)]">Indiener:</strong> <?= pp_e($motie->indiener) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($motie->onderwerp)): ?>
                            <span><?= pp_e($motie->onderwerp) ?></span>
                        <?php endif; ?>
                        <span class="ml-auto inline-flex items-center gap-1 font-display text-[color:var(--color-hague)]">
                            Stemgedrag
                            <?= pp_icon('arrow-right', 12) ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'views/templates/footer.php'; ?>
