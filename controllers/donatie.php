<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Meta informatie
$title = "Doneer aan PolitiekPraat";
$description = "Steun PolitiekPraat met een donatie. Help ons de website gratis, advertentievrij en onafhankelijk te houden. Elke bijdrage helpt ons verder ontwikkelen.";

require_once BASE_PATH . '/views/templates/header.php';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Steun ons',
    'title'   => 'Doneer aan PolitiekPraat',
    'lead'    => 'Help ons om de website gratis, advertentievrij en politiek onafhankelijk te houden. Elke bijdrage maakt redactioneel werk mogelijk.',
]) ?>

<section class="pp-container pp-container--narrow py-12 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
        <div class="lg:col-span-3">
            <div class="prose-editorial">
                <p class="text-lg leading-relaxed text-[color:var(--color-ink-muted)] mb-6">
                    PolitiekPraat draait op vrijwilligers, een beperkt eigen budget en de bijdragen van lezers zoals jij. We hebben bewust geen advertenties, omdat onze redactionele onafhankelijkheid voorop staat.
                </p>
                <p class="leading-relaxed text-[color:var(--color-ink-muted)] mb-6">
                    Met een donatie steun je hostings- en infrastructuurkosten, ondersteun je onderzoek en help je nieuwe tools zoals de PartijMeter en Stemmentracker te onderhouden. Bedankt voor je vertrouwen.
                </p>
            </div>

            <div class="mt-8 space-y-4">
                <div class="keyline-card p-5">
                    <div class="flex items-start gap-4">
                        <div class="text-[color:var(--color-hague)] mt-1"><?= pp_icon('shield-check', 22) ?></div>
                        <div>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-1">Onafhankelijk en advertentievrij</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed">We verkopen geen advertenties of data. Jouw bijdrage houdt de site onafhankelijk.</p>
                        </div>
                    </div>
                </div>
                <div class="keyline-card p-5">
                    <div class="flex items-start gap-4">
                        <div class="text-[color:var(--color-hague)] mt-1"><?= pp_icon('book-open', 22) ?></div>
                        <div>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-1">Redactioneel werk</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed">Onderzoek, analyses en duiding kosten tijd. Donaties maken meer kwaliteit mogelijk.</p>
                        </div>
                    </div>
                </div>
                <div class="keyline-card p-5">
                    <div class="flex items-start gap-4">
                        <div class="text-[color:var(--color-hague)] mt-1"><?= pp_icon('users', 22) ?></div>
                        <div>
                            <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-1">Voor alle Nederlanders</h3>
                            <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed">Begrijpelijke politiek voor iedereen, gratis toegankelijk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-2">
            <div class="keyline-card p-6 sticky top-24">
                <div class="eyebrow mb-3">Doneren via Tikkie</div>
                <h3 class="font-display text-display-lg text-[color:var(--color-ink)] mb-3 leading-tight">Eenmalig of maandelijks</h3>
                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed mb-6">
                    Iedere bijdrage helpt. Je betaalt veilig via Tikkie en kiest zelf het bedrag.
                </p>
                <a href="https://tikkie.me/pay/PolitiekPraat" target="_blank" rel="noopener noreferrer"
                   class="btn btn--primary btn--lg w-full justify-center">
                    <?= pp_icon('heart', 16) ?>
                    Doneer via Tikkie
                </a>
                <div class="mt-6 pt-6 border-t border-[color:var(--color-keyline)]">
                    <div class="eyebrow mb-2">Veelgekozen bedragen</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="chip">EUR 5</span>
                        <span class="chip">EUR 10</span>
                        <span class="chip">EUR 25</span>
                        <span class="chip">Anders</span>
                    </div>
                </div>
                <div class="mt-6 text-xs text-[color:var(--color-ink-faint)] flex items-center gap-2">
                    <?= pp_icon('lock', 12) ?>
                    Veilige betaling via Tikkie.
                </div>
            </div>
        </aside>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
