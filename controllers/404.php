<?php
http_response_code(404);
require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-24 md:py-32 text-center">
    <div class="font-mono text-tabular text-display-3xl text-[color:var(--color-ink-faint)] mb-4 leading-none">404</div>
    <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] mb-4 leading-[1.1]">Deze pagina bestaat niet</h1>
    <p class="text-[color:var(--color-ink-muted)] max-w-md mx-auto mb-10 leading-relaxed">
        De pagina die je zoekt is verplaatst of bestaat niet meer. Probeer terug te gaan naar de homepage of een van onze populaire pagina's hieronder.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
        <a href="<?= pp_e(pp_url('/')) ?>" class="btn btn--primary">
            <?= pp_icon('home', 14) ?>
            Naar de homepage
        </a>
        <a href="<?= pp_e(pp_url('/blogs')) ?>" class="btn btn--ghost">
            <?= pp_icon('book-open', 14) ?>
            Lees onze blogs
        </a>
    </div>

    <div class="border-t border-[color:var(--color-keyline)] pt-8 max-w-md mx-auto">
        <div class="eyebrow mb-4">Veelbezochte pagina's</div>
        <div class="flex flex-col gap-2 text-sm">
            <a href="<?= pp_e(pp_url('/partijen')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Alle partijen</a>
            <a href="<?= pp_e(pp_url('/partijmeter')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">PartijMeter 2026</a>
            <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Politiek Kompas</a>
            <a href="<?= pp_e(pp_url('/nieuws')) ?>" class="text-[color:var(--color-hague)] hover:underline underline-offset-2">Politiek nieuws</a>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
