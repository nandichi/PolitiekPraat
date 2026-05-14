<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';
require_once 'includes/ChatGPTAPI.php';

$debugMode = APP_DEBUG;

$shareId = $_GET['id'] ?? '';

if (empty($shareId)) {
    header('Location: /partijmeter');
    exit;
}

$partijmeterController = new StemwijzerController();

$savedResults = null;
$partijmeterData = null;
$errorMessage = '';

try {
    $savedResults = $partijmeterController->getResultsByShareId($shareId);

    if ($savedResults) {
        $partijmeterData = $partijmeterController->getStemwijzerData();
    } else {
        $errorMessage = 'Deze link is ongeldig of de resultaten zijn niet meer beschikbaar.';
    }
} catch (Exception $e) {
    $errorMessage = 'Er is een fout opgetreden bij het laden van de resultaten.';
    if ($debugMode) {
        error_log('Resultaten share load error: ' . $e->getMessage());
    }
}

$finalResults = [];
$personalityAnalysis = null;

if ($savedResults && $partijmeterData) {
    foreach ($savedResults->results as $partyName => $result) {
        $finalResults[] = [
            'name'      => $partyName,
            'agreement' => (int) ($result['agreement'] ?? 0),
            'logo'      => $partijmeterData['partyLogos'][$partyName] ?? '',
        ];
    }
    usort($finalResults, static fn($a, $b) => $b['agreement'] <=> $a['agreement']);

    try {
        $personalityAnalysis = $partijmeterController->analyzePoliticalPersonality(
            $savedResults->answers,
            $partijmeterData['questions']
        );
    } catch (Throwable $e) {
        $personalityAnalysis = null;
    }
}

require_once 'views/templates/header.php';
?>

<?php if ($savedResults && $partijmeterData && !empty($finalResults)): ?>

    <?= pp_render_component('section/page-hero', [
        'eyebrow' => 'PartijMeter resultaten',
        'title'   => 'Jouw politieke matches',
        'lead'    => 'Bekijk hieronder welke partijen het dichtst bij jouw standpunten staan. Deze pagina kun je bookmarken of delen.',
        'meta'    => [
            ['icon' => 'calendar', 'text' => 'Opgeslagen op ' . date('d F Y, H:i', strtotime($savedResults->completed_at))],
            ['icon' => 'list-checks', 'text' => count($savedResults->answers) . ' vragen beantwoord'],
            ['icon' => 'vote', 'text' => count($finalResults) . ' partijen vergeleken'],
        ],
    ]) ?>

    <section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

        <div>
            <div class="eyebrow mb-3">Ranglijst</div>
            <h2 class="font-display text-display-xl text-[color:var(--color-ink)] mb-6 leading-tight">Volledige overeenkomst per partij</h2>

            <div class="space-y-4">
                <?php foreach ($finalResults as $index => $result): ?>
                    <div class="keyline-card p-5 md:p-6">
                        <div class="flex items-center gap-4">
                            <div class="font-mono text-tabular text-2xl text-[color:var(--color-ink-faint)] w-10 text-center">
                                <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?>
                            </div>
                            <?php if (!empty($result['logo'])): ?>
                                <img src="<?= pp_e($result['logo']) ?>"
                                     alt="<?= pp_e($result['name']) ?>"
                                     class="w-12 h-12 rounded-md object-contain bg-white border border-[color:var(--color-keyline)] p-1.5">
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between gap-3 mb-2">
                                    <a href="/partijen/<?= pp_e(rawurlencode($result['name'])) ?>"
                                       class="font-display text-lg text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)] transition-colors truncate">
                                        <?= pp_e($result['name']) ?>
                                    </a>
                                    <span class="font-mono text-tabular text-lg text-[color:var(--color-ink)] flex-shrink-0">
                                        <?= (int) $result['agreement'] ?>%
                                    </span>
                                </div>
                                <div class="h-1.5 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
                                    <div class="h-full bg-[color:var(--color-hague)] transition-[width] duration-700"
                                         style="width: <?= (int) $result['agreement'] ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($personalityAnalysis): ?>
            <div class="keyline-card p-6 md:p-8">
                <div class="eyebrow mb-3">Politiek profiel</div>
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-3 leading-tight">
                    <?= pp_e($personalityAnalysis['political_profile']['type'] ?? 'Politiek profiel') ?>
                </h2>
                <?php if (!empty($personalityAnalysis['political_profile']['description'])): ?>
                    <p class="prose-editorial max-w-prose text-[color:var(--color-ink-muted)] mb-6">
                        <?= pp_e($personalityAnalysis['political_profile']['description']) ?>
                    </p>
                <?php endif; ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php
                    $axes = [
                        ['label' => 'Economisch rechts', 'value' => $personalityAnalysis['economic_right_percentage'] ?? 0],
                        ['label' => 'Progressief',       'value' => $personalityAnalysis['progressive_percentage'] ?? 0],
                        ['label' => 'Autoritair',        'value' => $personalityAnalysis['authoritarian_percentage'] ?? 0],
                        ['label' => 'Pro-EU',            'value' => $personalityAnalysis['eu_pro_percentage'] ?? 0],
                    ];
                    foreach ($axes as $axis): ?>
                        <div class="border border-[color:var(--color-keyline)] rounded-md p-4">
                            <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)] mb-2">
                                <?= pp_e($axis['label']) ?>
                            </div>
                            <div class="font-display text-display-md text-[color:var(--color-ink)] font-mono text-tabular mb-2">
                                <?= round((float) $axis['value']) ?>%
                            </div>
                            <div class="h-1 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
                                <div class="h-full bg-[color:var(--color-hague)]" style="width: <?= round((float) $axis['value']) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="keyline-card p-6 md:p-8 text-center">
            <div class="eyebrow mb-3">Wat nu?</div>
            <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-4 leading-tight">Verdiep je verder</h2>
            <p class="text-sm text-[color:var(--color-ink-muted)] mb-6 max-w-prose mx-auto">
                Bekijk de standpunten van jouw top-match, doe ook het Politiek Kompas of deel jouw resultaten met anderen.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="/partijen/<?= pp_e(rawurlencode($finalResults[0]['name'])) ?>" class="btn btn--primary">
                    <?= pp_icon('arrow-right', 14) ?>
                    Bekijk top-1: <?= pp_e($finalResults[0]['name']) ?>
                </a>
                <a href="/politiek-kompas" class="btn btn--ghost">
                    <?= pp_icon('scale', 14) ?>
                    Doe ook het Politiek Kompas
                </a>
                <button type="button" onclick="shareResults()" class="btn btn--ghost">
                    <?= pp_icon('share-2', 14) ?>
                    Link delen
                </button>
                <a href="/partijmeter" class="btn btn--ghost">
                    <?= pp_icon('vote', 14) ?>
                    Nieuwe test
                </a>
            </div>
        </div>
    </section>

<?php else: ?>

    <?= pp_render_component('section/page-hero', [
        'eyebrow' => 'PartijMeter',
        'title'   => 'Resultaten niet gevonden',
        'lead'    => $errorMessage !== '' ? $errorMessage : 'Deze link is ongeldig of de resultaten zijn niet meer beschikbaar.',
    ]) ?>

    <section class="pp-container pp-container--narrow py-10 md:py-14">
        <div class="keyline-card p-6 md:p-10 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] mb-4">
                <?= pp_icon('alert-circle', 22) ?>
            </div>
            <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-3 leading-tight">Geen resultaten beschikbaar</h2>
            <p class="text-sm text-[color:var(--color-ink-muted)] mb-6 max-w-prose mx-auto">
                Mogelijk is de share-link verlopen of incorrect overgenomen. Doe de PartijMeter opnieuw om nieuwe resultaten te genereren.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="/partijmeter" class="btn btn--primary">
                    <?= pp_icon('vote', 14) ?>
                    Doe de PartijMeter
                </a>
                <a href="/" class="btn btn--ghost">
                    <?= pp_icon('arrow-left', 14) ?>
                    Terug naar home
                </a>
            </div>
        </div>
    </section>

<?php endif; ?>

<script>
function shareResults() {
    const url = window.location.href;
    const title = 'Mijn PartijMeter resultaten - PolitiekPraat';
    const text = 'Bekijk mijn politieke matches van de PartijMeter.';

    if (navigator.share) {
        navigator.share({ title, text, url }).catch(() => fallbackShare(url));
    } else {
        fallbackShare(url);
    }
}

function fallbackShare(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(
            () => showNotification('Link gekopieerd naar klembord.', 'success'),
            () => showNotification('Kon link niet kopieren.', 'error')
        );
        return;
    }
    const textArea = document.createElement('textarea');
    textArea.value = url;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showNotification('Link gekopieerd naar klembord.', 'success');
    } catch (err) {
        showNotification('Kon link niet kopieren.', 'error');
    }
    document.body.removeChild(textArea);
}

function showNotification(message, type) {
    const colors = {
        success: { bg: 'var(--color-olive-tint)',     fg: 'var(--color-olive)',     border: 'var(--color-olive)' },
        error:   { bg: 'var(--color-terracotta-tint)', fg: 'var(--color-terracotta)', border: 'var(--color-terracotta)' },
    };
    const tone = colors[type] || { bg: 'var(--color-paper-2)', fg: 'var(--color-ink)', border: 'var(--color-keyline-strong)' };
    const el = document.createElement('div');
    el.style.cssText = `position:fixed;top:1rem;right:1rem;z-index:50;padding:.75rem 1rem;border:1px solid ${tone.border};background:${tone.bg};color:${tone.fg};border-radius:.375rem;font-size:.875rem;box-shadow:0 4px 10px -2px rgba(0,0,0,.1);max-width:22rem;`;
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}
</script>

<?php require_once 'views/templates/footer.php'; ?>
