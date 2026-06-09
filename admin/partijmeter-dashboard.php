<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../includes/PartijMeterController.php';
require_once __DIR__ . '/../models/PartyModel.php';

$adminPageTitle = 'PartijMeter';
$adminPageDescription = 'Landelijke stemhulp - overzicht en beheer';
$adminActiveNav = 'partijmeter';
require_once __DIR__ . '/partials/admin-header.php';

$controller = new PartijMeterController();
$stats = $controller->getStats();

$db = new Database();

// Recente stellingen
try {
    $db->query('SELECT id, theme, title, order_number, is_active, updated_at FROM partijmeter_questions ORDER BY order_number ASC, id ASC LIMIT 8');
    $recentQuestions = $db->resultSet() ?: [];
} catch (Throwable $e) {
    $recentQuestions = [];
}

// Actieve partijen (kolommen voor de standpunten)
try {
    $activeParties = (new PartyModel())->getAllParties();
} catch (Throwable $e) {
    $activeParties = [];
}

$expectedPositions = $stats['questions'] * count($activeParties);
$coverage = $expectedPositions > 0 ? round(100 * $stats['positions'] / $expectedPositions) : 0;
?>

<div class="admin-content__inner max-w-6xl">

    <!-- KPI's -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card rounded-xl p-5">
            <p class="text-sm text-gray-500 mb-1">Stellingen</p>
            <p class="text-3xl font-bold text-gray-800"><?= (int) $stats['questions'] ?></p>
        </div>
        <div class="stat-card rounded-xl p-5">
            <p class="text-sm text-gray-500 mb-1">Standpunten</p>
            <p class="text-3xl font-bold text-gray-800"><?= (int) $stats['positions'] ?></p>
            <p class="text-xs text-gray-500 mt-1"><?= $coverage ?>% van <?= (int) $expectedPositions ?></p>
        </div>
        <div class="stat-card rounded-xl p-5">
            <p class="text-sm text-gray-500 mb-1">Actieve partijen</p>
            <p class="text-3xl font-bold text-gray-800"><?= count($activeParties) ?></p>
        </div>
        <div class="stat-card rounded-xl p-5">
            <p class="text-sm text-gray-500 mb-1">Ingevulde tests</p>
            <p class="text-3xl font-bold text-gray-800"><?= (int) $stats['results'] ?></p>
        </div>
    </div>

    <!-- Snelle acties -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="partijmeter-vragen-beheer.php" class="card-hover bg-white rounded-xl p-5 border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-1">Stellingen beheren</h3>
            <p class="text-sm text-gray-600">Voeg stellingen toe, bewerk thema, toelichting en as-tags.</p>
        </a>
        <a href="partijmeter-standpunten-beheer.php" class="card-hover bg-white rounded-xl p-5 border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-1">Standpunten beheren</h3>
            <p class="text-sm text-gray-600">Bewerk per stelling per partij het standpunt, de toelichting en de bron.</p>
        </a>
        <a href="<?= pp_e(pp_url('/partijmeter')) ?>" target="_blank" class="card-hover bg-white rounded-xl p-5 border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-1">Preview</h3>
            <p class="text-sm text-gray-600">Bekijk de PartijMeter zoals bezoekers die zien.</p>
        </a>
    </div>

    <!-- Onderhoud (migratie/seed) -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 mb-8">
        <h3 class="font-semibold text-gray-800 mb-1">Onderhoud</h3>
        <p class="text-sm text-gray-600 mb-4">Draai de migratie (tabellen) of vul de stellingen en standpunten vanuit de git-dataset. Veilig en idempotent.</p>
        <div class="flex flex-wrap gap-3">
            <button type="button" onclick="pmRun('../scripts/run_partijmeter_migration.php?local_dev=true','Migratie draaien (tabellen aanmaken)?')"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Migratie draaien</button>
            <button type="button" onclick="pmRun('../scripts/seed_partijmeter.php?local_dev=true','Stellingen en standpunten (her)laden uit de dataset?')"
                    class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">Seed uit dataset</button>
        </div>
        <pre id="pmRunOutput" class="hidden mt-4 p-3 bg-gray-900 text-gray-100 text-xs rounded-lg overflow-auto max-h-64"></pre>
    </div>

    <!-- Recente stellingen -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Stellingen</h2>
            <a href="partijmeter-vragen-beheer.php" class="text-indigo-600 text-sm font-medium">Alle stellingen</a>
        </div>
        <div class="p-5">
            <?php if (empty($recentQuestions)): ?>
                <p class="text-gray-600 text-sm">Nog geen stellingen. Draai de seed of voeg een stelling toe.</p>
            <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($recentQuestions as $q): ?>
                        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg">
                            <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-sm flex items-center justify-center shrink-0"><?= (int) $q->order_number ?></span>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-800 text-sm truncate"><?= pp_e($q->title) ?></p>
                                <p class="text-xs text-gray-500"><?= pp_e($q->theme) ?></p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs <?= $q->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $q->is_active ? 'Actief' : 'Inactief' ?></span>
                            <a href="partijmeter-vragen-beheer.php?edit=<?= (int) $q->id ?>" class="text-indigo-600 text-sm font-medium">Bewerken</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function pmRun(url, confirmMsg) {
    if (!confirm(confirmMsg)) return;
    var out = document.getElementById('pmRunOutput');
    out.classList.remove('hidden');
    out.textContent = 'Bezig...';
    fetch(url)
        .then(function (r) { return r.text(); })
        .then(function (t) { out.textContent = t; })
        .catch(function (e) { out.textContent = 'Fout: ' + e.message; });
}
</script>

<?php require_once __DIR__ . '/partials/admin-footer.php'; ?>
