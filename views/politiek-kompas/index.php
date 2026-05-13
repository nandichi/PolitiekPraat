<?php require_once 'views/templates/header.php'; ?>

<?php
// Helper voor coordinaat-conversie (-100..100 -> 0..100 percentage)
if (!function_exists('pp_compass_pct')) {
    function pp_compass_pct(int $value): float {
        return ((float) $value + 100.0) / 2.0;
    }
}

$positions = $compassPositions ?? [];

// Sorteer voor de legenda (alfabetisch op naam)
$sortedPositions = $positions;
ksort($sortedPositions);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Politiek Kompas',
    'title'   => 'Waar staan de Nederlandse partijen?',
    'lead'    => 'Een redactioneel kompas: alle partijen geplot op twee assen. Horizontaal de economische as (links - rechts), verticaal de culturele as (progressief - conservatief). Een momentopname op basis van programma\'s en stemgedrag.',
]) ?>

<section class="pp-container pp-container--xl mt-12 mb-24">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 items-start">
        <div class="keyline-card p-6 md:p-10">
            <div class="relative w-full" style="aspect-ratio: 1 / 1; max-width: 720px; margin: 0 auto;">
                <div class="absolute inset-0 bg-[color:var(--color-paper-2)] rounded-md border border-[color:var(--color-keyline)]"></div>

                <!-- As-labels -->
                <div class="absolute -top-1 left-1/2 -translate-x-1/2 -translate-y-full pb-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap">Conservatief</div>
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 translate-y-full pt-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap">Progressief</div>
                <div class="absolute top-1/2 -left-2 -translate-x-full -translate-y-1/2 pr-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap rotate-180" style="writing-mode: vertical-rl;">Links</div>
                <div class="absolute top-1/2 -right-2 translate-x-full -translate-y-1/2 pl-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap" style="writing-mode: vertical-rl;">Rechts</div>

                <!-- Kompas grid (SVG) -->
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet"
                     class="absolute inset-0 w-full h-full">
                    <!-- Quadranten -->
                    <rect x="0" y="0" width="50" height="50" fill="var(--color-hague-soft)" opacity="0.25"/>
                    <rect x="50" y="0" width="50" height="50" fill="var(--color-terracotta-soft)" opacity="0.25"/>
                    <rect x="0" y="50" width="50" height="50" fill="var(--color-sage-soft)" opacity="0.30"/>
                    <rect x="50" y="50" width="50" height="50" fill="var(--color-paper-3)" opacity="0.50"/>

                    <!-- Sub-grid lijnen -->
                    <g stroke="var(--color-keyline)" stroke-width="0.15">
                        <line x1="25" y1="0" x2="25" y2="100"/>
                        <line x1="75" y1="0" x2="75" y2="100"/>
                        <line x1="0" y1="25" x2="100" y2="25"/>
                        <line x1="0" y1="75" x2="100" y2="75"/>
                    </g>

                    <!-- Hoofdassen -->
                    <g stroke="var(--color-ink-muted)" stroke-width="0.35">
                        <line x1="50" y1="0" x2="50" y2="100"/>
                        <line x1="0" y1="50" x2="100" y2="50"/>
                    </g>

                    <!-- Hoek-labels (klein) -->
                    <g fill="var(--color-ink-faint)" font-size="2.6" font-family="JetBrains Mono, monospace" letter-spacing="0.05">
                        <text x="3" y="6" text-anchor="start">links-conservatief</text>
                        <text x="97" y="6" text-anchor="end">rechts-conservatief</text>
                        <text x="3" y="97.5" text-anchor="start">links-progressief</text>
                        <text x="97" y="97.5" text-anchor="end">rechts-progressief</text>
                    </g>

                    <!-- Partij-punten -->
                    <?php foreach ($positions as $key => $pos): ?>
                        <?php
                        $cx = pp_compass_pct((int) $pos['x']);
                        // SVG y is naar beneden, maar wij willen "conservatief boven", dus inverteren
                        $cy = pp_compass_pct(-1 * (int) $pos['y']);
                        $color = $pos['color'] ?? '#1F3A5F';
                        ?>
                        <g class="compass-point" data-party="<?= pp_e($key) ?>">
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="2.3"
                                    fill="<?= pp_e($color) ?>"
                                    stroke="var(--color-paper)" stroke-width="0.6"/>
                            <text x="<?= $cx ?>" y="<?= $cy - 3.5 ?>"
                                  text-anchor="middle"
                                  fill="var(--color-ink)"
                                  font-size="2.6"
                                  font-family="Fraunces, serif"
                                  font-weight="600">
                                <?= pp_e($pos['name']) ?>
                            </text>
                        </g>
                    <?php endforeach; ?>
                </svg>
            </div>

            <p class="mt-8 text-sm text-[color:var(--color-ink-muted)] text-center leading-relaxed max-w-xl mx-auto">
                Posities zijn een redactionele schatting op basis van publieke programma's en stemgedrag.
                Het kompas geeft richting, geen exacte coordinaten.
            </p>
        </div>

        <aside class="space-y-6 lg:sticky lg:top-24">
            <div>
                <div class="eyebrow mb-3">Legenda</div>
                <div class="space-y-2">
                    <?php foreach ($sortedPositions as $key => $pos): ?>
                        <a href="<?= pp_e(pp_url('/partijen/' . urlencode($key))) ?>"
                           class="flex items-center gap-3 py-2 border-b border-[color:var(--color-keyline)] last:border-b-0 hover:bg-[color:var(--color-paper-2)] -mx-2 px-2 rounded transition">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                  style="background-color: <?= pp_e($pos['color'] ?? '#1F3A5F') ?>"></span>
                            <span class="font-display text-sm text-[color:var(--color-ink)] flex-1"><?= pp_e($pos['name']) ?></span>
                            <span class="font-mono text-tabular text-xs text-[color:var(--color-ink-muted)]">
                                <?= ($pos['x'] > 0 ? '+' : '') . (int) $pos['x'] ?> /
                                <?= ($pos['y'] > 0 ? '+' : '') . (int) $pos['y'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-[color:var(--color-ink-faint)] mt-3 leading-relaxed">
                    Coordinaten: economisch / cultureel. Range -100 tot +100.
                </p>
            </div>

            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Vergelijken</div>
                <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2 leading-snug">
                    Wil je een uitgebreidere vergelijking?
                </h3>
                <p class="text-sm text-[color:var(--color-ink-muted)] mb-4 leading-relaxed">
                    Met de PartijMeter beantwoord je 25 stellingen en zie je per partij de overlap met jouw standpunten.
                </p>
                <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary btn--sm">
                    <?= pp_icon('arrow-right', 14) ?>
                    Naar de PartijMeter
                </a>
            </div>
        </aside>
    </div>
</section>

<section class="pp-container pp-container--md mb-24">
    <?= pp_render_component('section/section-header', [
        'label' => 'Toelichting',
        'title' => 'Hoe lees je dit kompas?',
    ]) ?>

    <div class="prose-editorial">
        <p>
            Het Politiek Kompas plot iedere partij op twee assen.
            <strong>Horizontaal</strong> staat de economische dimensie: links betekent meer
            herverdeling, sterkere overheid en publieke voorzieningen; rechts betekent
            lagere lasten, vrije markt en kleinere staat.
        </p>
        <p>
            <strong>Verticaal</strong> staat de culturele dimensie: onderaan progressief
            (open samenleving, individuele vrijheden, Europese samenwerking) en bovenaan
            conservatief (nationale identiteit, traditionele waarden, soevereiniteit).
        </p>
        <p>
            Partijen die dichtbij elkaar staan, hebben gemiddeld vergelijkbare standpunten.
            Het kompas is een momentopname; coalities en programma's veranderen, en niet elk
            standpunt past in twee assen.
        </p>
    </div>
</section>

<style>
    .compass-point text {
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }
    .compass-point:hover text {
        opacity: 1;
    }
    .compass-point circle {
        transition: r 0.2s ease;
    }
    .compass-point:hover circle {
        r: 3;
    }
</style>

<?php require_once 'views/templates/footer.php'; ?>
