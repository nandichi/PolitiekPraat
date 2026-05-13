<?php
/**
 * Party stat row - horizontale balk met partij-naam, kleur en zetels.
 *
 * Props:
 *   party_key  string
 *   name       string
 *   color      string
 *   seats      int
 *   max        int    (voor schaling)
 *   highlight  bool   Default false
 */
$partyKey = $party_key ?? '';
$partyName = $name ?? $partyKey;
$color = $color ?? '#6B7280';
$seats = (int) ($seats ?? 0);
$max = max(1, (int) ($max ?? 50));
$pct = round(($seats / $max) * 100, 1);
$highlight = !empty($highlight);
?>
<div class="flex items-center gap-3">
    <div class="w-1 flex-shrink-0 self-stretch rounded" style="background: <?= pp_e($color) ?>;"></div>
    <div class="flex-1 min-w-0">
        <div class="flex items-baseline justify-between mb-1">
            <div class="flex items-center gap-2 min-w-0">
                <span class="font-display text-sm text-[color:var(--color-ink)] truncate"><?= pp_e($partyKey) ?></span>
                <span class="text-xs text-[color:var(--color-ink-muted)] truncate hidden sm:inline"><?= pp_e($partyName) ?></span>
            </div>
            <span class="font-mono text-tabular text-sm text-[color:var(--color-ink)] flex-shrink-0">
                <?= $seats ?>
                <span class="text-[color:var(--color-ink-faint)] text-xs ml-0.5">zetels</span>
            </span>
        </div>
        <div class="h-1.5 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-[width] duration-700"
                 style="width: <?= $pct ?>%; background: <?= pp_e($color) ?>;"></div>
        </div>
    </div>
</div>
