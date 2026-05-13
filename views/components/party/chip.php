<?php
/**
 * Partij chip - kleine label met partij-afkorting en kleur-accent.
 *
 * Props:
 *   key   string    Party key (VVD, PVV, etc.)
 *   color string    Hex color
 *   href  string|null
 *   size  'sm'|'md' Default md
 */
$partyKey = $key ?? '';
$color = $color ?? '#6B7280';
$href = $href ?? null;
$size = $size ?? 'md';

$padding = $size === 'sm' ? '0.25rem 0.6rem' : '0.4rem 0.85rem';
$fontSize = $size === 'sm' ? '0.7rem' : '0.78rem';

$styles = sprintf(
    'display: inline-flex; align-items: center; gap: 0.4rem; padding: %s; font-family: var(--font-sans); font-size: %s; font-weight: 600; letter-spacing: 0.04em; color: var(--color-ink); background: var(--color-paper-2); border: 1px solid var(--color-keyline); border-radius: 999px;',
    $padding,
    $fontSize
);

$content = '<span style="width: 6px; height: 6px; border-radius: 999px; background:' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . ';" aria-hidden="true"></span>'
         . '<span>' . htmlspecialchars($partyKey, ENT_QUOTES, 'UTF-8') . '</span>';

if ($href) {
    echo '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" style="' . $styles . '" class="transition hover:bg-[color:var(--color-paper-3)]">';
    echo $content;
    echo '</a>';
} else {
    echo '<span style="' . $styles . '">' . $content . '</span>';
}
?>
