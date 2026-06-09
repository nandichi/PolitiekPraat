<?php
require_once __DIR__ . '/_bootstrap.php';

$db = new Database();

// ---------------------------------------------------------------- POST acties
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirect = 'partijmeter-vragen-beheer.php';

    try {
        if ($action === 'save') {
            $id = (int) ($_POST['id'] ?? 0);
            $theme = trim((string) ($_POST['theme'] ?? ''));
            $title = trim((string) ($_POST['title'] ?? ''));
            $explanation = trim((string) ($_POST['explanation'] ?? ''));
            $axisE = (int) ($_POST['axis_economic'] ?? 0);
            $axisC = (int) ($_POST['axis_cultural'] ?? 0);
            $order = (int) ($_POST['order_number'] ?? 0);
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            $axisE = max(-1, min(1, $axisE));
            $axisC = max(-1, min(1, $axisC));

            if ($theme === '' || $title === '') {
                throw new RuntimeException('Thema en stelling zijn verplicht.');
            }

            if ($id > 0) {
                $db->query('UPDATE partijmeter_questions SET theme = :theme, title = :title, explanation = :explanation, axis_economic = :ae, axis_cultural = :ac, order_number = :ord, is_active = :act WHERE id = :id');
                $db->bind(':id', $id);
            } else {
                $db->query('INSERT INTO partijmeter_questions (theme, title, explanation, axis_economic, axis_cultural, order_number, is_active) VALUES (:theme, :title, :explanation, :ae, :ac, :ord, :act)');
            }
            $db->bind(':theme', $theme);
            $db->bind(':title', $title);
            $db->bind(':explanation', $explanation !== '' ? $explanation : null);
            $db->bind(':ae', $axisE);
            $db->bind(':ac', $axisC);
            $db->bind(':ord', $order);
            $db->bind(':act', $isActive);
            $db->execute();

            header('Location: ' . $redirect . '?msg=saved');
            exit;
        }

        if ($action === 'toggle') {
            $id = (int) ($_POST['id'] ?? 0);
            $db->query('UPDATE partijmeter_questions SET is_active = 1 - is_active WHERE id = :id');
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . $redirect . '?msg=toggled');
            exit;
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            $db->query('DELETE FROM partijmeter_questions WHERE id = :id');
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . $redirect . '?msg=deleted');
            exit;
        }
    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}

$adminPageTitle = 'PartijMeter stellingen';
$adminPageDescription = 'Beheer de stellingen van de landelijke stemhulp';
$adminActiveNav = 'partijmeter-vragen';
require_once __DIR__ . '/partials/admin-header.php';

// Bericht
$msg = $_GET['msg'] ?? '';
$messages = [
    'saved' => 'Stelling opgeslagen.',
    'toggled' => 'Status gewijzigd.',
    'deleted' => 'Stelling verwijderd.',
];

// Bewerken?
$editQuestion = null;
if (isset($_GET['edit'])) {
    $db->query('SELECT * FROM partijmeter_questions WHERE id = :id');
    $db->bind(':id', (int) $_GET['edit']);
    $editQuestion = $db->single() ?: null;
}

// Lijst
try {
    $db->query('SELECT id, theme, title, axis_economic, axis_cultural, order_number, is_active FROM partijmeter_questions ORDER BY order_number ASC, id ASC');
    $questions = $db->resultSet() ?: [];
} catch (Throwable $e) {
    $questions = [];
}

$axisEconLabels = [-1 => 'Links', 0 => 'Geen', 1 => 'Rechts'];
$axisCultLabels = [-1 => 'Progressief', 0 => 'Geen', 1 => 'Conservatief'];

$ev = static function ($field, $default = '') use ($editQuestion) {
    if (!$editQuestion) {
        return $default;
    }
    return $editQuestion->$field ?? $default;
};
?>

<div class="admin-content__inner max-w-6xl">

    <?php if (!empty($errorMessage)): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-200 text-red-800"><?= pp_e($errorMessage) ?></div>
    <?php elseif ($msg && isset($messages[$msg])): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-200 text-green-800"><?= pp_e($messages[$msg]) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Formulier -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4"><?= $editQuestion ? 'Stelling bewerken' : 'Nieuwe stelling' ?></h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="save">
                    <?php if ($editQuestion): ?>
                        <input type="hidden" name="id" value="<?= (int) $editQuestion->id ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thema</label>
                        <input type="text" name="theme" value="<?= pp_e((string) $ev('theme')) ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stelling</label>
                        <textarea name="title" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= pp_e((string) $ev('title')) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Toelichting (optioneel)</label>
                        <textarea name="explanation" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= pp_e((string) $ev('explanation')) ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">As economisch</label>
                            <select name="axis_economic" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <?php foreach ($axisEconLabels as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= (string) $ev('axis_economic', '0') === (string) $val ? 'selected' : '' ?>><?= pp_e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">As cultureel</label>
                            <select name="axis_cultural" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <?php foreach ($axisCultLabels as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= (string) $ev('axis_cultural', '0') === (string) $val ? 'selected' : '' ?>><?= pp_e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Volgorde</label>
                            <input type="number" name="order_number" value="<?= pp_e((string) $ev('order_number', '0')) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <label class="flex items-center gap-2 pb-2">
                            <input type="checkbox" name="is_active" value="1" <?= ((string) $ev('is_active', '1')) === '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-gray-700">Actief</span>
                        </label>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700"><?= $editQuestion ? 'Opslaan' : 'Toevoegen' ?></button>
                        <?php if ($editQuestion): ?>
                            <a href="partijmeter-vragen-beheer.php" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200">Annuleren</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lijst -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Alle stellingen (<?= count($questions) ?>)</h2>
                </div>
                <?php if (empty($questions)): ?>
                    <div class="p-8 text-center text-gray-600">Nog geen stellingen.</div>
                <?php else: ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($questions as $q): ?>
                            <div class="flex items-start gap-3 p-4">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 font-bold text-sm flex items-center justify-center shrink-0"><?= (int) $q->order_number ?></span>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-800 text-sm leading-snug"><?= pp_e($q->title) ?></p>
                                    <div class="flex flex-wrap gap-2 mt-1 text-xs text-gray-500">
                                        <span class="px-2 py-0.5 rounded bg-gray-100"><?= pp_e($q->theme) ?></span>
                                        <span>economisch: <?= pp_e($axisEconLabels[(int) $q->axis_economic] ?? '-') ?></span>
                                        <span>cultureel: <?= pp_e($axisCultLabels[(int) $q->axis_cultural] ?? '-') ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2 py-1 rounded-full text-xs <?= $q->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $q->is_active ? 'Actief' : 'Inactief' ?></span>
                                    <a href="partijmeter-vragen-beheer.php?edit=<?= (int) $q->id ?>" class="text-indigo-600 text-sm font-medium">Bewerken</a>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= (int) $q->id ?>">
                                        <button type="submit" class="text-gray-500 text-sm hover:text-gray-800"><?= $q->is_active ? 'Deactiveer' : 'Activeer' ?></button>
                                    </form>
                                    <form method="POST" class="inline" onsubmit="return confirm('Deze stelling en alle bijbehorende standpunten verwijderen?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $q->id ?>">
                                        <button type="submit" class="text-red-600 text-sm hover:text-red-800">Verwijder</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/admin-footer.php'; ?>
