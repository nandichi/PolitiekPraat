<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['flash_message'])) {
    return;
}

$flash = $_SESSION['flash_message'];
unset($_SESSION['flash_message']);

$type = $flash['type'] ?? 'info';
$text = trim((string) ($flash['text'] ?? ''));

if ($text === '') {
    return;
}

$tone = [
    'success' => [
        'border' => 'var(--color-olive)',
        'bg'     => 'var(--color-olive-tint)',
        'color'  => 'var(--color-olive)',
        'icon'   => 'check-circle',
    ],
    'error'   => [
        'border' => 'var(--color-terracotta)',
        'bg'     => 'var(--color-terracotta-tint)',
        'color'  => 'var(--color-terracotta)',
        'icon'   => 'alert-circle',
    ],
    'info'    => [
        'border' => 'var(--color-hague)',
        'bg'     => 'var(--color-paper-2)',
        'color'  => 'var(--color-hague)',
        'icon'   => 'info',
    ],
][$type] ?? null;

if ($tone === null) {
    $tone = [
        'border' => 'var(--color-hague)',
        'bg'     => 'var(--color-paper-2)',
        'color'  => 'var(--color-hague)',
        'icon'   => 'info',
    ];
}
?>
<div class="pp-container pp-container--xl pt-6">
    <div class="border-l-4 p-4 rounded-r flex items-start gap-3"
         style="border-color: <?= $tone['border'] ?>; background: <?= $tone['bg'] ?>; color: <?= $tone['color'] ?>;">
        <span class="flex-shrink-0 mt-0.5"><?= pp_icon($tone['icon'], 18) ?></span>
        <span class="text-sm leading-relaxed"><?= pp_e($text) ?></span>
    </div>
</div>
