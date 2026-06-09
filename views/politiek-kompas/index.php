<?php require_once 'views/templates/header.php'; ?>

<?php
// Helper voor coordinaat-conversie (-100..100 -> 0..100 percentage)
if (!function_exists('pp_compass_pct')) {
    function pp_compass_pct(int $value): float {
        return ((float) $value + 100.0) / 2.0;
    }
}

$positions = $compassPositions ?? [];

// Legenda alfabetisch op naam
$sortedPositions = $positions;
ksort($sortedPositions);

$totalSeats = 0;
foreach ($positions as $p) {
    $totalSeats += (int) ($p['current_seats'] ?? 0);
}
$maxSeats = 1;
foreach ($positions as $p) {
    $maxSeats = max($maxSeats, (int) ($p['current_seats'] ?? 0));
}

// ---------------------------------------------------------------------------
// Plotgeometrie + automatische label-ontwarring (server-side).
// Namen en stippen mogen elkaar nooit overlappen, ongeacht hoe dicht partijen
// bij elkaar staan. We berekenen de stip-posities en duwen daarna de labels
// met een lichte kracht-simulatie uit elkaar (en weg van de stippen).
// ---------------------------------------------------------------------------
$plotPoints = [];
foreach ($positions as $key => $pos) {
    $cx = pp_compass_pct((int) $pos['x']);
    $cy = pp_compass_pct(-1 * (int) $pos['y']); // y omkeren: conservatief boven
    $seats = (int) ($pos['current_seats'] ?? 0);
    $r = 1.9 + 2.0 * sqrt($seats / max(1, $maxSeats)); // 1.9 .. ~3.9
    $plotPoints[$key] = [
        'cx'    => $cx,
        'cy'    => $cy,
        'r'     => $r,
        'name'  => (string) $pos['name'],
        'color' => $pos['color'] ?? '#1F3A5F',
    ];
}

$ppLabelFs  = 2.55; // tekengrootte labels (SVG-eenheden)
$ppGlyphW   = 1.50; // geschatte breedte per teken (Fraunces, ruim genomen)
$ppLabelH   = 3.20; // hoogte van een label-box
$ppLabelGap = 1.10; // ruimte tussen stip-rand en label

// De vier hoek-labels ("links-conservatief" enz.) zijn vaste obstakels:
// een partijnaam mag er nooit overheen lopen.
$ppCornerObstacles = [
    ['cx' => 3.0 + 14.5,  'cy' => 5.2,  'hw' => 14.5, 'hh' => 2.4], // links-conservatief
    ['cx' => 97.0 - 15.0, 'cy' => 5.2,  'hw' => 15.0, 'hh' => 2.4], // rechts-conservatief
    ['cx' => 3.0 + 13.6,  'cy' => 95.8, 'hw' => 13.6, 'hh' => 2.4], // links-progressief
    ['cx' => 97.0 - 14.3, 'cy' => 95.8, 'hw' => 14.3, 'hh' => 2.4], // rechts-progressief
];

// Hoeveel overlap heeft een label-box (midden $bx,$by) met de hoek-labels?
// Wordt gebruikt om per stip de beste kant (links/rechts) te kiezen.
$ppCornerOverlap = function (float $bx, float $by, float $w, float $h) use ($ppCornerObstacles): float {
    $sum = 0.0;
    foreach ($ppCornerObstacles as $ob) {
        $ovx = ($w / 2 + $ob['hw']) - abs($bx - $ob['cx']);
        $ovy = ($h / 2 + $ob['hh']) - abs($by - $ob['cy']);
        if ($ovx > 0 && $ovy > 0) {
            $sum += $ovx * $ovy;
        }
    }
    return $sum;
};

$plotLabels = [];
foreach ($plotPoints as $key => $p) {
    $w = max(3.2, mb_strlen($p['name']) * $ppGlyphW) + 1.2;
    $offset = $p['r'] + $ppLabelGap + $w / 2;
    $xRight = $p['cx'] + $offset;
    $xLeft  = $p['cx'] - $offset;

    // Standaard rechts van de stip; bij stippen tegen de rechterrand naar links.
    $side = ($p['cx'] > 64) ? -1 : 1; // -1 = links, +1 = rechts
    $chosenX = ($side === 1) ? $xRight : $xLeft;
    $otherX  = ($side === 1) ? $xLeft : $xRight;

    // Loopt de gekozen kant in een hoek-label en de andere kant niet? Omklappen.
    $hitChosen = $ppCornerOverlap($chosenX, $p['cy'], $w, $ppLabelH);
    $hitOther  = $ppCornerOverlap($otherX, $p['cy'], $w, $ppLabelH);
    if ($hitChosen > 0 && $hitOther < $hitChosen) {
        $side = -$side;
    }

    $ax = $p['cx'] + $side * $offset;
    $plotLabels[$key] = [
        'w'    => $w,
        'h'    => $ppLabelH,
        'side' => $side,
        'ax'   => $ax,        // gewenste (anker) midden-x
        'ay'   => $p['cy'],   // gewenste midden-y
        'x'    => $ax,        // huidige midden-x
        'y'    => $p['cy'],   // huidige midden-y
    ];
}

// We houden de x-positie vast op het anker (links/rechts van de stip) en
// ontwarren uitsluitend verticaal: er is volop verticale ruimte, dus labels
// die op dezelfde hoogte botsen schuiven netjes boven/onder elkaar.
for ($it = 0; $it < 320; $it++) {
    foreach ($plotLabels as $ki => &$Li) {
        $my = 0.0;

        // Labels onderling: verticaal uit elkaar duwen wanneer de boxen overlappen.
        foreach ($plotLabels as $kj => $Lj) {
            if ($ki === $kj) {
                continue;
            }
            $ovx = ($Li['w'] + $Lj['w']) / 2 + 0.4 - abs($Li['x'] - $Lj['x']);
            $ovy = ($Li['h'] + $Lj['h']) / 2 + 0.4 - abs($Li['y'] - $Lj['y']);
            if ($ovx > 0 && $ovy > 0) {
                $my += (($Li['y'] >= $Lj['y']) ? 1 : -1) * ($ovy / 2 + 0.04);
            }
        }

        // Labels mogen geen enkele stip bedekken.
        foreach ($plotPoints as $pp2) {
            $ovx = ($Li['w'] / 2 + $pp2['r'] + 0.3) - abs($Li['x'] - $pp2['cx']);
            $ovy = ($Li['h'] / 2 + $pp2['r'] + 0.3) - abs($Li['y'] - $pp2['cy']);
            if ($ovx > 0 && $ovy > 0) {
                $my += (($Li['y'] >= $pp2['cy']) ? 1 : -1) * ($ovy / 2);
            }
        }

        // Veiligheidsnet: nooit over een hoek-label heen.
        foreach ($ppCornerObstacles as $ob) {
            $ovx = ($Li['w'] / 2 + $ob['hw']) - abs($Li['x'] - $ob['cx']);
            $ovy = ($Li['h'] / 2 + $ob['hh']) - abs($Li['y'] - $ob['cy']);
            if ($ovx > 0 && $ovy > 0) {
                $my += (($Li['y'] >= $ob['cy']) ? 1 : -1) * ($ovy / 2);
            }
        }

        // Veerkracht terug naar de gewenste hoogte (dicht bij de eigen stip).
        $my += ($Li['ay'] - $Li['y']) * 0.05;

        $Li['y'] += max(-1.0, min(1.0, $my));
        $Li['y'] = max($Li['h'] / 2 + 0.5, min(100 - $Li['h'] / 2 - 0.5, $Li['y']));
    }
    unset($Li);
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Politiek Kompas',
    'title'   => 'Waar staan de Nederlandse partijen?',
    'lead'    => 'Een redactioneel kompas: alle partijen uit de Tweede Kamer geplot op twee assen. Horizontaal de economische as (links - rechts), verticaal de culturele as (progressief - conservatief). Een momentopname op basis van programma\'s en stemgedrag.',
]) ?>

<section class="pp-container pp-container--xl mt-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Partijen</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= count($positions) ?></div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">in de Tweede Kamer</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Assen</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular">2</div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">economisch en cultureel</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Kwadranten</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular">4</div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">politieke richtingen</div>
        </div>
        <div class="keyline-card p-5">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Zetels</div>
            <div class="font-display text-3xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $totalSeats ?></div>
            <div class="text-sm text-[color:var(--color-ink-muted)] mt-1">samen in de Kamer</div>
        </div>
    </div>
</section>

<section class="pp-container pp-container--xl mt-12 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 items-start">
        <div class="keyline-card p-6 md:p-10">
            <div class="relative w-full" style="aspect-ratio: 1 / 1; max-width: 760px; margin: 0 auto;">
                <div class="absolute inset-0 bg-[color:var(--color-paper-2)] rounded-md border border-[color:var(--color-keyline)]"></div>

                <!-- As-labels -->
                <div class="absolute -top-1 left-1/2 -translate-x-1/2 -translate-y-full pb-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap">Conservatief</div>
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 translate-y-full pt-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap">Progressief</div>
                <div class="absolute top-1/2 -left-2 -translate-x-full -translate-y-1/2 pr-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap rotate-180" style="writing-mode: vertical-rl;">Links</div>
                <div class="absolute top-1/2 -right-2 translate-x-full -translate-y-1/2 pl-2 text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] whitespace-nowrap" style="writing-mode: vertical-rl;">Rechts</div>

                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet"
                     class="absolute inset-0 w-full h-full">
                    <!-- Quadranten -->
                    <rect x="0" y="0" width="50" height="50" fill="var(--color-hague-soft)" opacity="0.25"/>
                    <rect x="50" y="0" width="50" height="50" fill="var(--color-terracotta-soft)" opacity="0.25"/>
                    <rect x="0" y="50" width="50" height="50" fill="var(--color-sage-soft)" opacity="0.30"/>
                    <rect x="50" y="50" width="50" height="50" fill="var(--color-paper-3)" opacity="0.50"/>

                    <!-- Sub-grid -->
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

                    <!-- Hoek-labels -->
                    <g fill="var(--color-ink-faint)" font-size="2.5" font-family="JetBrains Mono, monospace" letter-spacing="0.05">
                        <text x="3" y="6" text-anchor="start">links-conservatief</text>
                        <text x="97" y="6" text-anchor="end">rechts-conservatief</text>
                        <text x="3" y="97.5" text-anchor="start">links-progressief</text>
                        <text x="97" y="97.5" text-anchor="end">rechts-progressief</text>
                    </g>

                    <!-- Verbindingslijntjes van label naar stip (alleen waar het label verschoven is) -->
                    <?php foreach ($plotPoints as $key => $p): ?>
                        <?php
                        $L = $plotLabels[$key];
                        $nearX = $L['x'] - $L['side'] * ($L['w'] / 2);
                        $nearY = $L['y'];
                        $dx = $nearX - $p['cx'];
                        $dy = $nearY - $p['cy'];
                        $dist = sqrt($dx * $dx + $dy * $dy);
                        if ($dist > $p['r'] + 1.4):
                            $sx = $p['cx'] + ($dx / $dist) * $p['r'];
                            $sy = $p['cy'] + ($dy / $dist) * $p['r'];
                        ?>
                            <line x1="<?= round($sx, 2) ?>" y1="<?= round($sy, 2) ?>"
                                  x2="<?= round($nearX, 2) ?>" y2="<?= round($nearY, 2) ?>"
                                  stroke="<?= pp_e($p['color']) ?>" stroke-width="0.3" opacity="0.45"/>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Partij-stippen (geschaald op zetels) -->
                    <?php foreach ($plotPoints as $key => $p): ?>
                        <g class="compass-point" data-party="<?= pp_e($key) ?>">
                            <circle cx="<?= round($p['cx'], 2) ?>" cy="<?= round($p['cy'], 2) ?>" r="<?= round($p['r'], 2) ?>"
                                    fill="<?= pp_e($p['color']) ?>"
                                    stroke="var(--color-paper)" stroke-width="0.6"/>
                        </g>
                    <?php endforeach; ?>

                    <!-- Partij-labels (automatisch ontward) -->
                    <?php foreach ($plotLabels as $key => $L): ?>
                        <text x="<?= round($L['x'], 2) ?>" y="<?= round($L['y'], 2) ?>"
                              text-anchor="middle" dominant-baseline="central"
                              fill="var(--color-ink)"
                              font-size="<?= $ppLabelFs ?>"
                              font-family="Fraunces, serif"
                              font-weight="600"
                              paint-order="stroke"
                              stroke="var(--color-paper)" stroke-width="0.7"
                              stroke-linejoin="round"
                              pointer-events="none">
                            <?= pp_e($plotPoints[$key]['name']) ?>
                        </text>
                    <?php endforeach; ?>
                </svg>
            </div>

            <p class="mt-8 text-sm text-[color:var(--color-ink-muted)] text-center leading-relaxed max-w-xl mx-auto">
                De grootte van een stip schaalt mee met het aantal zetels. Posities zijn een redactionele
                schatting op basis van publieke programma's en stemgedrag; het kompas geeft richting, geen exacte coordinaten.
            </p>
        </div>

        <aside class="space-y-6 lg:sticky lg:top-24">
            <div>
                <div class="eyebrow mb-3">Legenda</div>
                <div class="space-y-1">
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
                    Met de PartijMeter beantwoord je stellingen en zie je per partij de overlap met jouw standpunten.
                </p>
                <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary btn--sm">
                    <?= pp_icon('arrow-right', 14) ?>
                    Naar de PartijMeter
                </a>
            </div>
        </aside>
    </div>
</section>

<!-- Kwadranten -->
<section class="pp-container pp-container--xl mb-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'De vier kwadranten',
        'title' => 'Welke partijen horen bij elkaar?',
    ]) ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($quadrantMeta as $qkey => $meta): ?>
            <?php $group = $quadrantGroups[$qkey] ?? []; ?>
            <div class="keyline-card p-6">
                <div class="flex items-baseline justify-between gap-3 mb-1">
                    <h3 class="font-display text-xl text-[color:var(--color-ink)]"><?= pp_e($meta['titel']) ?></h3>
                    <span class="font-mono text-xs text-[color:var(--color-ink-muted)]"><?= count($group) ?> partijen</span>
                </div>
                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-4"><?= pp_e($meta['omschrijving']) ?></p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($group as $key => $pos): ?>
                        <a href="<?= pp_e(pp_url('/partijen/' . urlencode($key))) ?>"
                           class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-keyline)] bg-[color:var(--color-paper)] px-3 py-1.5 text-sm text-[color:var(--color-ink)] hover:bg-[color:var(--color-paper-2)] transition">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?= pp_e($pos['color']) ?>"></span>
                            <span class="font-display"><?= pp_e($pos['name']) ?></span>
                            <span class="font-mono text-tabular text-xs text-[color:var(--color-ink-muted)]"><?= (int) $pos['current_seats'] ?></span>
                        </a>
                    <?php endforeach; ?>
                    <?php if (empty($group)): ?>
                        <span class="text-sm text-[color:var(--color-ink-faint)]">Geen partijen in dit kwadrant.</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Volledige positielijst -->
<section class="pp-container pp-container--xl mb-20">
    <?= pp_render_component('section/section-header', [
        'label' => 'Alle posities',
        'title' => 'Overzicht per partij',
    ]) ?>

    <div class="keyline-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[color:var(--color-keyline)] text-xs uppercase tracking-[0.14em] text-[color:var(--color-ink-muted)]">
                        <th class="py-3 px-5 font-medium">Partij</th>
                        <th class="py-3 px-5 font-medium">Stroming</th>
                        <th class="py-3 px-5 font-medium text-right">Economisch</th>
                        <th class="py-3 px-5 font-medium text-right">Cultureel</th>
                        <th class="py-3 px-5 font-medium text-right">Zetels</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $key => $pos): ?>
                        <tr class="border-b border-[color:var(--color-keyline)] last:border-b-0 hover:bg-[color:var(--color-paper-2)] transition">
                            <td class="py-3 px-5">
                                <a href="<?= pp_e(pp_url('/partijen/' . urlencode($key))) ?>" class="inline-flex items-center gap-3 group">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: <?= pp_e($pos['color']) ?>"></span>
                                    <span class="font-display text-[color:var(--color-ink)] group-hover:underline"><?= pp_e($pos['name']) ?></span>
                                </a>
                            </td>
                            <td class="py-3 px-5 text-sm text-[color:var(--color-ink-muted)]"><?= pp_e($pos['spectrum']) ?></td>
                            <td class="py-3 px-5 text-right font-mono text-tabular text-sm text-[color:var(--color-ink)]"><?= ($pos['x'] > 0 ? '+' : '') . (int) $pos['x'] ?></td>
                            <td class="py-3 px-5 text-right font-mono text-tabular text-sm text-[color:var(--color-ink)]"><?= ($pos['y'] > 0 ? '+' : '') . (int) $pos['y'] ?></td>
                            <td class="py-3 px-5 text-right font-mono text-tabular text-sm text-[color:var(--color-ink)]"><?= (int) $pos['current_seats'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
            herverdeling, een sterkere overheid en stevige publieke voorzieningen; rechts betekent
            lagere lasten, meer vrije markt en een kleinere staat.
        </p>
        <p>
            <strong>Verticaal</strong> staat de culturele dimensie: onderaan progressief
            (open samenleving, individuele vrijheden, Europese samenwerking) en bovenaan
            conservatief (nationale identiteit, traditionele waarden, soevereiniteit).
        </p>
        <p>
            Partijen die dicht bij elkaar staan, hebben gemiddeld vergelijkbare standpunten.
            Toch zegt de afstand niet alles: twee partijen kunnen in hetzelfde kwadrant staan en
            elkaar op een concreet thema fel bestrijden. Het kompas is een hulpmiddel om het
            landschap te overzien, geen wiskundige waarheid.
        </p>
        <p>
            De posities zijn een <strong>redactionele schatting</strong> op basis van publieke
            verkiezingsprogramma's en stemgedrag in de Tweede Kamer. Coalities en programma's
            veranderen, en niet elk standpunt past in twee assen. Wil je weten waar jij zelf staat?
            Doe dan de PartijMeter.
        </p>
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="<?= pp_e(pp_url('/partijen')) ?>" class="btn btn--ghost">
            <?= pp_icon('users', 16) ?>
            Alle partijen
        </a>
        <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="btn btn--primary">
            <?= pp_icon('vote', 16) ?>
            Doe de PartijMeter
        </a>
    </div>
</section>

<style>
    .compass-point text {
        opacity: 1;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }
    .compass-point circle {
        transition: r 0.2s ease, filter 0.2s ease;
        cursor: pointer;
    }
    .compass-point:hover circle {
        filter: drop-shadow(0 0 1.5px rgba(0,0,0,0.35));
    }
</style>

<?php require_once 'views/templates/footer.php'; ?>
