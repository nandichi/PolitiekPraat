<?php
/**
 * Editorial byline.
 *
 * Props:
 *   name         string  Auteurnaam (verplicht)
 *   avatar       string|null  URL naar avatar (anders initialen)
 *   role         string|null  Korte rol/functie
 *   date         string|null  Geformatteerde datum
 *   reading_time int|null     Leestijd in minuten
 *   compact      bool         Compactere weergave (default: false)
 */
$name = $name ?? ($props['name'] ?? 'Redactie');
$avatar = $avatar ?? ($props['avatar'] ?? null);
$role = $role ?? ($props['role'] ?? null);
$date = $date ?? ($props['date'] ?? null);
$reading_time = $reading_time ?? ($props['reading_time'] ?? null);
$compact = $compact ?? ($props['compact'] ?? false);

$initials = strtoupper(mb_substr(trim((string) $name), 0, 1));
?>
<div class="byline<?= $compact ? ' byline--compact' : '' ?>">
    <span class="byline__avatar">
        <?php if ($avatar): ?>
            <img src="<?= pp_e($avatar) ?>" alt="<?= pp_e($name) ?>" loading="lazy">
        <?php else: ?>
            <?= pp_e($initials) ?>
        <?php endif; ?>
    </span>
    <div class="flex flex-col leading-tight">
        <span><span class="byline__name"><?= pp_e($name) ?></span><?php if ($role): ?> <span class="text-[color:var(--color-ink-faint)]">· <?= pp_e($role) ?></span><?php endif; ?></span>
        <?php if ($date || $reading_time): ?>
            <span class="text-[color:var(--color-ink-faint)] text-[0.78rem]">
                <?php if ($date): ?><?= pp_e($date) ?><?php endif; ?>
                <?php if ($date && $reading_time): ?><span class="byline__separator">·</span><?php endif; ?>
                <?php if ($reading_time): ?><?= (int) $reading_time ?> min lezen<?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
</div>
