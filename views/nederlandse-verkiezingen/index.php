<?php require_once 'views/templates/header.php'; ?>

<?php
$verkiezingenPerPeriode = $verkiezingenPerPeriode ?? [];
$statistieken = $statistieken ?? (object) [];
$recentePremiersData = $recentePremiersData ?? [];

if (!function_exists('pp_format_int')) {
    function pp_format_int($value): string {
        return number_format((int) ($value ?? 0), 0, ',', '.');
    }
}
if (!function_exists('pp_format_pct')) {
    function pp_format_pct($value, int $decimals = 1): string {
        if ($value === null || $value === '') return '-';
        return number_format((float) $value, $decimals, ',', '.') . '%';
    }
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Nederlandse verkiezingen',
    'title'   => 'Een eeuw aan parlementaire democratie',
    'lead'    => 'Een redactioneel overzicht van Nederlandse Tweede Kamerverkiezingen sinds 1848 - met uitslagen, coalities en de premiers die uit deze stembussen voortkwamen.',
]) ?>

<section class="pp-container pp-container--xl mt-10 mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Verkiezingen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_format_int($statistieken->totaal_verkiezingen ?? 0) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Premiers</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_format_int($statistieken->totaal_premiers ?? 0) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Gemiddelde opkomst</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_format_pct($statistieken->gemiddelde_opkomst ?? null) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Hoogste opkomst</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_format_pct($statistieken->hoogste_opkomst ?? null) ?></div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mb-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Tijdlijn',
        'title' => 'Alle Kamerverkiezingen per tijdvak',
        'lead'  => 'Klik door naar een specifiek jaar voor een uitgebreid overzicht van uitslagen, coalitie en duiding.',
    ]) ?>

    <?php foreach ($verkiezingenPerPeriode as $era => $verkiezingen): ?>
        <div class="mb-14">
            <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-6 pb-3 border-b border-[color:var(--color-line-strong)]">
                <?= pp_e($era) ?>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                <?php foreach ($verkiezingen as $verkiezing): ?>
                    <article class="border-t border-[color:var(--color-keyline)] pt-5">
                        <div class="flex items-baseline justify-between mb-3">
                            <a href="<?= pp_e(pp_url('/nederlandse-verkiezingen?jaar=' . (int) $verkiezing->jaar)) ?>"
                               class="font-display text-display-md text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)]">
                                <?= (int) $verkiezing->jaar ?>
                            </a>
                            <?php if (!empty($verkiezing->opkomst_percentage)): ?>
                                <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-muted)]">
                                    <?= pp_format_pct($verkiezing->opkomst_percentage) ?> opkomst
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($verkiezing->grootste_partij)): ?>
                            <div class="text-sm text-[color:var(--color-ink-muted)] mb-3 leading-relaxed">
                                Grootste partij: <strong class="text-[color:var(--color-ink)]"><?= pp_e($verkiezing->grootste_partij) ?></strong>
                                <?php if (!empty($verkiezing->grootste_partij_zetels)): ?>
                                    (<?= (int) $verkiezing->grootste_partij_zetels ?> zetels)
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($verkiezing->minister_president)): ?>
                            <div class="text-sm text-[color:var(--color-ink-muted)] mb-3 leading-relaxed">
                                Premier: <strong class="text-[color:var(--color-ink)]"><?= pp_e($verkiezing->minister_president) ?></strong>
                            </div>
                        <?php endif; ?>

                        <a href="<?= pp_e(pp_url('/nederlandse-verkiezingen?jaar=' . (int) $verkiezing->jaar)) ?>"
                           class="inline-flex items-center gap-1.5 text-sm font-display text-[color:var(--color-hague)] hover:underline underline-offset-2 mt-2">
                            Lees het volledige overzicht
                            <?= pp_icon('arrow-right', 13) ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($verkiezingenPerPeriode)): ?>
        <div class="text-center py-16 text-[color:var(--color-ink-muted)]">
            Geen verkiezingsdata beschikbaar.
        </div>
    <?php endif; ?>
</section>

<?php if (!empty($recentePremiersData)): ?>
    <section class="pp-container pp-container--xl mb-24">
        <?= pp_render_component('section/section-header', [
            'label' => 'Premiers',
            'title' => 'Recente ministers-presidenten',
            'cta_label' => 'Bekijk alle premiers',
            'cta_href'  => '/nederlandse-verkiezingen?actie=ministers-presidenten',
        ]) ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            <?php foreach ($recentePremiersData as $premier): ?>
                <a href="<?= pp_e(pp_url('/nederlandse-verkiezingen?actie=ministers-presidenten')) ?>"
                   class="block text-center group">
                    <div class="editorial-frame editorial-frame--3-4 mb-3">
                        <?php if (!empty($premier->foto_url)): ?>
                            <img src="<?= pp_e($premier->foto_url) ?>" alt="<?= pp_e($premier->naam) ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="font-display text-sm md:text-base text-[color:var(--color-ink)] leading-tight group-hover:text-[color:var(--color-hague)]">
                        <?= pp_e($premier->naam ?? '') ?>
                    </div>
                    <?php if (!empty($premier->partij)): ?>
                        <div class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= pp_e($premier->partij) ?></div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php require_once 'views/templates/footer.php'; ?>
