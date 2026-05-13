<?php require_once 'views/templates/header.php'; ?>

<?php
$verkiezingenPerPeriode = $verkiezingenPerPeriode ?? [];
$statistieken = $statistieken ?? null;
$keyFiguresPresidenten = $keyFiguresPresidenten ?? [];

if (!function_exists('pp_us_format_int')) {
    function pp_us_format_int($value): string {
        return number_format((int) ($value ?? 0), 0, ',', '.');
    }
}
if (!function_exists('pp_us_get')) {
    function pp_us_get($data, string $key, $default = null) {
        if (is_array($data) && array_key_exists($key, $data)) return $data[$key];
        if (is_object($data) && isset($data->{$key})) return $data->{$key};
        return $default;
    }
}
if (!function_exists('pp_us_party_accent')) {
    function pp_us_party_accent($partij): string {
        $p = strtolower((string) $partij);
        if (str_contains($p, 'republic')) return 'terracotta';
        if (str_contains($p, 'democrat')) return 'hague';
        return 'neutral';
    }
}

$totaalVerkiezingen = pp_us_get($statistieken, 'totaal_verkiezingen', 0);
$demWins = pp_us_get($statistieken, 'democratic_overwinningen', null)
    ?? pp_us_get($statistieken, 'democraat_overwinningen', 0);
$repWins = pp_us_get($statistieken, 'republican_overwinningen', null)
    ?? pp_us_get($statistieken, 'republikein_overwinningen', 0);
$gemOpkomst = pp_us_get($statistieken, 'gemiddelde_opkomst', null);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Amerikaanse verkiezingen',
    'title'   => 'Twee eeuwen Amerikaanse presidentsverkiezingen',
    'lead'    => 'Een historisch overzicht van Amerikaanse presidentsverkiezingen, met uitslagen, electorale stemmen en context per periode.',
]) ?>

<section class="pp-container pp-container--xl mt-10 mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Verkiezingen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_us_format_int($totaalVerkiezingen) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-hague)] mb-2">Democraten</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_us_format_int($demWins) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-terracotta)] mb-2">Republikeinen</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]"><?= pp_us_format_int($repWins) ?></div>
        </div>
        <div class="keyline-card p-4">
            <div class="eyebrow text-[color:var(--color-ink-faint)] mb-2">Gemiddelde opkomst</div>
            <div class="font-display text-2xl md:text-3xl text-[color:var(--color-ink)]">
                <?= $gemOpkomst !== null ? number_format((float) $gemOpkomst, 1, ',', '.') . '%' : '-' ?>
            </div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mb-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Tijdlijn',
        'title' => 'Alle presidentsverkiezingen per tijdvak',
        'cta_label' => 'Bekijk alle presidenten',
        'cta_href'  => '/amerikaanse-verkiezingen?actie=presidenten',
    ]) ?>

    <?php foreach ($verkiezingenPerPeriode as $era => $verkiezingen): ?>
        <div class="mb-14">
            <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-6 pb-3 border-b border-[color:var(--color-line-strong)]">
                <?= pp_e($era) ?>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                <?php foreach ($verkiezingen as $verkiezing): ?>
                    <?php
                    $v = is_object($verkiezing) ? $verkiezing : (object) $verkiezing;
                    $jaar = (int) ($v->jaar ?? 0);
                    $winnaar = $v->winnaar ?? '';
                    $winnaarPartij = $v->winnaar_partij ?? '';
                    $accent = pp_us_party_accent($winnaarPartij);
                    ?>
                    <article class="border-t border-[color:var(--color-keyline)] pt-5">
                        <div class="flex items-baseline justify-between mb-3">
                            <a href="<?= pp_e(pp_url('/amerikaanse-verkiezingen?jaar=' . $jaar)) ?>"
                               class="font-display text-display-md text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)]">
                                <?= $jaar ?>
                            </a>
                            <?php if (!empty($v->opkomst_percentage)): ?>
                                <span class="font-mono text-tabular text-sm text-[color:var(--color-ink-muted)]">
                                    <?= number_format((float) $v->opkomst_percentage, 1, ',', '.') ?>% opkomst
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($winnaar): ?>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge badge--<?= pp_e($accent) ?>">
                                    <?= pp_e($winnaarPartij ?: 'Winnaar') ?>
                                </span>
                                <span class="font-display text-base text-[color:var(--color-ink)]"><?= pp_e($winnaar) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($v->winnaar_kiesmannen)): ?>
                            <div class="text-sm text-[color:var(--color-ink-muted)] mb-3">
                                <span class="font-mono text-tabular text-[color:var(--color-ink)]">
                                    <?= (int) $v->winnaar_kiesmannen ?>
                                </span>
                                <?php if (!empty($v->verliezer_kiesmannen)): ?>
                                    -
                                    <span class="font-mono text-tabular"><?= (int) $v->verliezer_kiesmannen ?></span>
                                <?php endif; ?>
                                kiesmannen
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($v->verliezer)): ?>
                            <div class="text-xs text-[color:var(--color-ink-faint)] mb-3">
                                versus <?= pp_e($v->verliezer) ?>
                            </div>
                        <?php endif; ?>

                        <a href="<?= pp_e(pp_url('/amerikaanse-verkiezingen?jaar=' . $jaar)) ?>"
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

<?php require_once 'views/templates/footer.php'; ?>
