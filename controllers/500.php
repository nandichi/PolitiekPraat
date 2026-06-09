<?php
/**
 * 500 Internal Server Error pagina.
 *
 * Wordt gerendered wanneer een controller een onverwachte Exception throwt.
 * Verwacht optioneel een $pp_router_exception variabele met de Throwable.
 */

if (!headers_sent()) {
    http_response_code(500);
}

$pp_show_debug = defined('APP_DEBUG') && APP_DEBUG === true;
$pp_exception   = $pp_router_exception ?? null;

// Foutpagina's mogen niet geindexeerd worden.
$data = [
    'title' => 'Er ging iets mis',
    'description' => 'Er trad een tijdelijke serverfout op. Probeer het later opnieuw of ga terug naar de homepage van PolitiekPraat.',
    'meta_robots' => 'noindex, follow',
];

require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-24 md:py-32 text-center">
    <div class="font-mono text-tabular text-display-3xl text-[color:var(--color-ink-faint)] mb-4 leading-none">500</div>
    <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] mb-4 leading-[1.1]">Er ging iets mis</h1>
    <p class="text-[color:var(--color-ink-muted)] max-w-md mx-auto mb-10 leading-relaxed">
        We konden deze pagina even niet laden door een tijdelijke fout op de server. Probeer het over een minuutje opnieuw, of ga terug naar de homepage.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
        <a href="<?= pp_e(pp_url('/')) ?>" class="btn btn--primary">
            <?= pp_icon('home', 14) ?>
            Naar de homepage
        </a>
        <a href="javascript:location.reload()" class="btn btn--ghost">
            <?= pp_icon('refresh-cw', 14) ?>
            Opnieuw proberen
        </a>
    </div>

    <?php if ($pp_show_debug && $pp_exception instanceof Throwable): ?>
        <details class="keyline-card text-left p-6 mt-12 max-w-2xl mx-auto">
            <summary class="cursor-pointer text-sm font-medium text-[color:var(--color-ink-muted)] mb-3">
                Debug-informatie (alleen zichtbaar in development)
            </summary>
            <div class="mt-4 space-y-3 text-sm">
                <div>
                    <span class="eyebrow text-[color:var(--color-ink-faint)]">Bericht</span>
                    <p class="font-mono text-tabular text-[color:var(--color-ink)] mt-1 break-words"><?= pp_e($pp_exception->getMessage()) ?></p>
                </div>
                <div>
                    <span class="eyebrow text-[color:var(--color-ink-faint)]">Locatie</span>
                    <p class="font-mono text-tabular text-[color:var(--color-ink-muted)] mt-1 break-all"><?= pp_e($pp_exception->getFile() . ':' . $pp_exception->getLine()) ?></p>
                </div>
                <div>
                    <span class="eyebrow text-[color:var(--color-ink-faint)]">Stack trace</span>
                    <pre class="font-mono text-tabular text-xs text-[color:var(--color-ink-muted)] mt-1 overflow-x-auto whitespace-pre-wrap break-all"><?= pp_e($pp_exception->getTraceAsString()) ?></pre>
                </div>
            </div>
        </details>
    <?php endif; ?>

    <div class="border-t border-[color:var(--color-keyline)] pt-8 max-w-md mx-auto">
        <div class="eyebrow mb-4">Andere opties</div>
        <div class="flex flex-col gap-2 text-sm">
            <a href="<?= pp_e(pp_url('/blogs')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Recente blogs</a>
            <a href="<?= pp_e(pp_url('/nieuws')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Politiek nieuws</a>
            <a href="<?= pp_e(pp_url('/contact')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Contact opnemen</a>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
