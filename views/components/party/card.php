<?php
/**
 * Partij-card - editorial card met logo, leider, zetels.
 *
 * Props:
 *   party_key      string  Sleutel (VVD, PVV, etc.)
 *   name           string  Volle naam
 *   leader         string|null
 *   leader_photo   string|null
 *   logo           string|null
 *   color          string  Hex color (accent line)
 *   current_seats  int|null
 *   polling_seats  int|null
 *   description    string|null
 *   href           string|null Detail-URL
 */
$partyKey = $party_key ?? '';
$partyName = $name ?? $partyKey;
$leader = $leader ?? null;
$leaderPhoto = $leader_photo ?? null;
$logo = $logo ?? null;
$color = $color ?? '#1F2A44';
$currentSeats = $current_seats ?? null;
$pollingSeats = $polling_seats ?? null;
$description = $description ?? null;
$href = $href ?? null;
$spectrum = $spectrum ?? null;
?>
<article class="keyline-card" style="overflow: hidden; height: 100%; display: flex; flex-direction: column;">
    <div style="height: 4px; background: <?= pp_e($color) ?>;"></div>
    <div class="p-5 md:p-6" style="display: flex; flex-direction: column; gap: 1rem; flex: 1;">
        <div class="flex items-start gap-4">
            <?php if ($logo): ?>
                <div class="flex-shrink-0 w-12 h-12 rounded-md border border-[color:var(--color-keyline)] bg-white p-1.5 flex items-center justify-center">
                    <img src="<?= pp_e($logo) ?>" alt="<?= pp_e($partyName) ?>" class="max-w-full max-h-full object-contain">
                </div>
            <?php endif; ?>
            <div class="min-w-0 flex-1">
                <div class="font-mono text-xs uppercase tracking-[0.1em] text-[color:var(--color-ink-muted)] mb-1">
                    <?= pp_e($partyKey) ?>
                </div>
                <?php if ($href): ?>
                    <a href="<?= pp_e($href) ?>" class="font-display text-lg leading-tight text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)]">
                        <?= pp_e($partyName) ?>
                    </a>
                <?php else: ?>
                    <h3 class="font-display text-lg leading-tight text-[color:var(--color-ink)]">
                        <?= pp_e($partyName) ?>
                    </h3>
                <?php endif; ?>
                <?php if ($spectrum): ?>
                    <div class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= pp_e($spectrum) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($leader): ?>
            <div class="flex items-center gap-3 pt-1">
                <?php if ($leaderPhoto): ?>
                    <img src="<?= pp_e($leaderPhoto) ?>" alt="<?= pp_e($leader) ?>"
                         class="w-9 h-9 rounded-full object-cover border border-[color:var(--color-keyline)]"
                         loading="lazy">
                <?php else: ?>
                    <div class="w-9 h-9 rounded-full bg-[color:var(--color-paper-2)] flex items-center justify-center text-xs font-display font-semibold text-[color:var(--color-ink)] border border-[color:var(--color-keyline)]">
                        <?= pp_e(mb_substr($leader, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="min-w-0">
                    <div class="text-xs uppercase tracking-[0.12em] text-[color:var(--color-ink-faint)]">Lijsttrekker</div>
                    <div class="font-display text-sm text-[color:var(--color-ink)] truncate"><?= pp_e($leader) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($currentSeats !== null || $pollingSeats !== null): ?>
            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[color:var(--color-keyline)]">
                <?php if ($currentSeats !== null): ?>
                    <div>
                        <div class="text-[10px] uppercase tracking-[0.12em] text-[color:var(--color-ink-faint)] mb-0.5">Huidige zetels</div>
                        <div class="font-display text-xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $currentSeats ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($pollingSeats !== null): ?>
                    <div>
                        <div class="text-[10px] uppercase tracking-[0.12em] text-[color:var(--color-ink-faint)] mb-0.5">In peilingen</div>
                        <div class="font-display text-xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) $pollingSeats ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($description): ?>
            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed">
                <?= pp_e(mb_substr(strip_tags($description), 0, 140)) ?><?= mb_strlen(strip_tags($description)) > 140 ? '...' : '' ?>
            </p>
        <?php endif; ?>

        <?php if ($href): ?>
            <a href="<?= pp_e($href) ?>" class="inline-flex items-center gap-1.5 text-sm text-[color:var(--color-hague)] font-medium mt-auto pt-2 hover:underline">
                Bekijk profiel
                <?= pp_icon('arrow-right', 14) ?>
            </a>
        <?php endif; ?>
    </div>
</article>
