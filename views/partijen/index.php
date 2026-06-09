<?php
/**
 * Editorial partijen index (Wave 3).
 *
 * Beschikbare data:
 *   $parties           - assoc[partyKey => party]
 *   $partiesSorted     - gesorteerd op huidige zetels
 *   $partiesByPolling  - gesorteerd op peilingen
 *   $totalSeats        - int
 *   $totalPollingSeats - int
 *   $maxSeats / $maxPolling - int (voor schaling)
 */
require_once BASE_PATH . '/views/templates/header.php';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Partijen',
    'title'   => 'Wie zijn de spelers in Den Haag?',
    'lead'    => 'Een rustig overzicht van alle Nederlandse politieke partijen. Bekijk lijsttrekkers, zetelaantallen en hun belangrijkste standpunten - zonder ruis.',
]) ?>

<section class="pp-container pp-container--xl mt-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Partijen</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= count($parties) ?></div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">in de Tweede Kamer</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Zetels</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $totalSeats ?></div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">verdeeld in de Kamer</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">In peilingen</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $totalPollingSeats ?></div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">geprojecteerde zetels</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Verkiezingen</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular">2026</div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">laatste TK-verkiezingen</div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mt-20" id="zetels">
    <?= pp_render_component('section/section-header', [
        'label'     => 'Krachtsverhouding',
        'title'     => 'Zetelverdeling op dit moment',
        'cta_label' => 'Naar peilingen',
        'cta_href'  => '#peilingen',
    ]) ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="keyline-card p-6">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-4">
                Huidige Tweede Kamer
            </div>
            <div class="space-y-4">
                <?php
                $topNow = array_slice($partiesSorted, 0, 10, true);
                foreach ($topNow as $key => $party):
                    if ((int) ($party['current_seats'] ?? 0) === 0) continue;
                ?>
                    <?= pp_render_component('party/stat-row', [
                        'party_key' => $key,
                        'name'      => $party['name'] ?? $key,
                        'color'     => getPartyColor($key),
                        'seats'     => (int) ($party['current_seats'] ?? 0),
                        'max'       => $maxSeats,
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="keyline-card p-6" id="peilingen">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-4">
                Peilingen (recent)
            </div>
            <div class="space-y-4">
                <?php
                $topPoll = array_slice($partiesByPolling, 0, 10, true);
                foreach ($topPoll as $key => $party):
                    $pollSeats = (int) ($party['polling']['seats'] ?? 0);
                    if ($pollSeats === 0) continue;
                ?>
                    <?= pp_render_component('party/stat-row', [
                        'party_key' => $key,
                        'name'      => $party['name'] ?? $key,
                        'color'     => getPartyColor($key),
                        'seats'     => $pollSeats,
                        'max'       => $maxPolling,
                    ]) ?>
                <?php endforeach; ?>
            </div>
            <p class="text-xs text-[color:var(--color-ink-faint)] mt-6">
                Peilingen zijn momentopnames. Tussen peiling en stembus kan veel gebeuren.
            </p>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mt-20" id="alle-partijen">
    <?= pp_render_component('section/section-header', [
        'label' => 'Alle partijen',
        'title' => 'Profielen van alle partijen',
    ]) ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($partiesSorted as $key => $party): ?>
            <?php
            $slug = strtolower(str_replace(['/', ' '], '-', $key));
            ?>
            <?= pp_render_component('party/card', [
                'party_key'     => $key,
                'name'          => $party['name'] ?? $key,
                'leader'        => $party['leader'] ?? null,
                'leader_photo'  => $party['leader_photo'] ?? null,
                'logo'          => $party['logo'] ?? null,
                'color'         => getPartyColor($key),
                'current_seats' => $party['current_seats'] ?? null,
                'polling_seats' => $party['polling']['seats'] ?? null,
                'description'   => $party['description'] ?? null,
                'spectrum'      => $partyProfiles[$key]['stroming'] ?? null,
                'href'          => pp_url('/partijen/' . urlencode($slug)),
            ]) ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="pp-container pp-container--xl mt-20 mb-24">
    <div class="keyline-card p-8 md:p-12">
        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-8 items-center">
            <div>
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                    Voer een check uit
                </div>
                <h3 class="font-display text-3xl md:text-4xl text-[color:var(--color-ink)] leading-tight mb-2">
                    Welke partij past het beste bij jou?
                </h3>
                <p class="text-[color:var(--color-ink-muted)] max-w-xl">
                    Doe de Partijmeter, Stemwijzer Ede 2026 of het Politiek Kompas en zie waar je staat.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary">
                    <?= pp_icon('vote', 16) ?>
                    Partijmeter
                </a>
                <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="btn btn--secondary">
                    <?= pp_icon('scale', 16) ?>
                    Politiek Kompas
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
