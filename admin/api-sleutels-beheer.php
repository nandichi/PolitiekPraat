<?php
/**
 * API Sleutels Beheer
 * Genereer en beheer API-sleutels voor het publiceren van blogs via de API
 */
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

$db = new Database();

// Controleer of de api_keys tabel bestaat
$db->query("SHOW TABLES LIKE 'api_keys'");
$tableExists = $db->single();

if (!$tableExists) {
    require_once '../views/templates/header.php';
    ?>
    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-red-200">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Database Migratie Vereist</h1>
                        <p class="text-gray-600">De api_keys tabel bestaat nog niet</p>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <p class="text-amber-800 mb-2">Voer de volgende opdracht uit op de server om de tabel aan te maken:</p>
                    <code class="block bg-gray-800 text-green-400 p-3 rounded-lg text-sm overflow-x-auto">
                        php <?= dirname(dirname(__FILE__)) ?>/scripts/create_api_keys_table.php
                    </code>
                </div>
                <div class="flex space-x-4">
                    <a href="dashboard.php" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                        Terug naar Dashboard
                    </a>
                    <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" class="bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 transition-colors">
                        Opnieuw Proberen
                    </a>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once '../views/templates/footer.php';
    exit;
}

$message = null;
$messageType = 'success';
$newKeyPlaintext = null;

// Verwerk formulieracties
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $message = 'Ongeldige beveiligingstoken. Probeer opnieuw.';
        $messageType = 'error';
    } elseif (isset($_POST['action'])) {

        if ($_POST['action'] === 'generate') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $message = 'Geef een naam op voor de sleutel.';
                $messageType = 'error';
            } else {
                // Genereer sleutel: pp_ + 40 hex tekens (160 bit entropie)
                $rawKey = 'pp_' . bin2hex(random_bytes(20));
                $keyHash = hash('sha256', $rawKey);

                $db->query("INSERT INTO api_keys (name, key_hash, user_id, is_active) VALUES (:name, :key_hash, 1, 1)");
                $db->bind(':name', htmlspecialchars($name, ENT_QUOTES));
                $db->bind(':key_hash', $keyHash);

                if ($db->execute()) {
                    $newKeyPlaintext = $rawKey;
                    $message = 'API-sleutel succesvol aangemaakt. Kopieer de sleutel hieronder; deze wordt slechts eenmalig getoond.';
                    $messageType = 'success';
                } else {
                    $message = 'Fout bij aanmaken van de sleutel.';
                    $messageType = 'error';
                }
            }

        } elseif ($_POST['action'] === 'deactivate') {
            $id = intval($_POST['key_id'] ?? 0);
            if ($id > 0) {
                $db->query("UPDATE api_keys SET is_active = 0 WHERE id = :id");
                $db->bind(':id', $id);
                $db->execute();
                $message = 'Sleutel gedeactiveerd.';
            }

        } elseif ($_POST['action'] === 'activate') {
            $id = intval($_POST['key_id'] ?? 0);
            if ($id > 0) {
                $db->query("UPDATE api_keys SET is_active = 1 WHERE id = :id");
                $db->bind(':id', $id);
                $db->execute();
                $message = 'Sleutel geactiveerd.';
            }

        } elseif ($_POST['action'] === 'delete') {
            $id = intval($_POST['key_id'] ?? 0);
            if ($id > 0) {
                $db->query("DELETE FROM api_keys WHERE id = :id");
                $db->bind(':id', $id);
                $db->execute();
                $message = 'Sleutel verwijderd.';
            }
        }
    }
}

// CSRF token genereren
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Haal alle sleutels op
$db->query("SELECT ak.*, u.username FROM api_keys ak JOIN users u ON ak.user_id = u.id ORDER BY ak.created_at DESC");
$apiKeys = $db->resultSet();

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

.key-display {
    font-family: 'Courier New', Courier, monospace;
    word-break: break-all;
    letter-spacing: 0.03em;
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    <div class="container mx-auto px-4 py-10 max-w-5xl">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">API Sleutels</h1>
                <p class="text-gray-500 mt-1">Beheer API-sleutels voor het publiceren van blogs via de REST API</p>
            </div>
            <a href="dashboard.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Dashboard
            </a>
        </div>

        <!-- Melding -->
        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl border <?= $messageType === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800' ?> flex items-start gap-3">
            <?php if ($messageType === 'error'): ?>
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?php else: ?>
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?php endif; ?>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
        <?php endif; ?>

        <!-- Nieuw aangemaakte sleutel: eenmalig tonen -->
        <?php if ($newKeyPlaintext): ?>
        <div class="mb-6 bg-amber-50 border-2 border-amber-400 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-6 h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <div>
                    <p class="font-bold text-amber-800">Kopieer uw API-sleutel nu</p>
                    <p class="text-amber-700 text-sm">Deze sleutel wordt nooit meer volledig getoond. Sla hem op een veilige plek op.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <code id="new-api-key" class="key-display flex-1 bg-gray-900 text-green-400 px-4 py-3 rounded-xl text-sm"><?= htmlspecialchars($newKeyPlaintext) ?></code>
                <button onclick="copyApiKey()" class="shrink-0 bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 rounded-xl font-medium text-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Kopieer
                </button>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Formulier nieuwe sleutel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-900">Nieuwe Sleutel</h2>
                            <p class="text-xs text-gray-500">Genereer een nieuwe API-sleutel</p>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="action" value="generate">

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Naam / Label</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                placeholder="Bijv. n8n workflow, externe app"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            >
                            <p class="text-xs text-gray-400 mt-1">Gebruik een beschrijvende naam om de sleutel later te herkennen.</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 text-xs text-blue-700">
                            <strong>Let op:</strong> blogs geplaatst via API-sleutel worden gepubliceerd onder de gebruiker <strong>naoufal</strong>.
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-medium text-sm transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Sleutel Genereren
                        </button>
                    </form>

                    <!-- Gebruik instructies -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Gebruik</h3>
                        <p class="text-xs text-gray-500 mb-2">Stuur de sleutel mee als HTTP-header:</p>
                        <code class="block bg-gray-900 text-green-400 px-3 py-2 rounded-lg text-xs break-all">X-API-Key: uw-sleutel</code>
                        <p class="text-xs text-gray-500 mt-3 mb-2">Voorbeeld POST naar:</p>
                        <code class="block bg-gray-900 text-blue-300 px-3 py-2 rounded-lg text-xs break-all"><?= defined('URLROOT') ? URLROOT : '' ?>/api/blogs</code>
                    </div>
                </div>
            </div>

            <!-- Lijst van sleutels -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900">Bestaande Sleutels</h2>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium"><?= count($apiKeys) ?> sleutel<?= count($apiKeys) !== 1 ? 's' : '' ?></span>
                    </div>

                    <?php if (empty($apiKeys)): ?>
                    <div class="p-12 text-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Geen sleutels aangemaakt</p>
                        <p class="text-gray-400 text-sm mt-1">Genereer uw eerste API-sleutel via het formulier.</p>
                    </div>
                    <?php else: ?>
                    <div class="divide-y divide-gray-50">
                        <?php foreach ($apiKeys as $key): ?>
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">

                            <!-- Status indicator -->
                            <div class="shrink-0">
                                <span class="w-2.5 h-2.5 rounded-full inline-block <?= $key->is_active ? 'bg-green-400' : 'bg-gray-300' ?>"></span>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="font-medium text-gray-900 truncate"><?= htmlspecialchars($key->name) ?></span>
                                    <?php if ($key->is_active): ?>
                                    <span class="shrink-0 bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">Actief</span>
                                    <?php else: ?>
                                    <span class="shrink-0 bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full font-medium">Inactief</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <?= htmlspecialchars($key->username) ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Aangemaakt <?= date('d M Y', strtotime($key->created_at)) ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?= $key->last_used_at ? 'Laatst gebruikt ' . date('d M Y H:i', strtotime($key->last_used_at)) : 'Nog niet gebruikt' ?>
                                    </span>
                                </div>
                                <div class="mt-1 text-xs text-gray-300 key-display truncate">
                                    Hash: <?= substr($key->key_hash, 0, 12) ?>...<?= substr($key->key_hash, -8) ?>
                                </div>
                            </div>

                            <!-- Acties -->
                            <div class="shrink-0 flex items-center gap-2">
                                <?php if ($key->is_active): ?>
                                <form method="POST" onsubmit="return confirm('Sleutel deactiveren?')">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="action" value="deactivate">
                                    <input type="hidden" name="key_id" value="<?= (int)$key->id ?>">
                                    <button type="submit" title="Deactiveren" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="action" value="activate">
                                    <input type="hidden" name="key_id" value="<?= (int)$key->id ?>">
                                    <button type="submit" title="Activeren" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <form method="POST" onsubmit="return confirm('Sleutel permanent verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="key_id" value="<?= (int)$key->id ?>">
                                    <button type="submit" title="Verwijderen" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
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
</main>

<script>
function copyApiKey() {
    const keyEl = document.getElementById('new-api-key');
    if (!keyEl) return;
    navigator.clipboard.writeText(keyEl.textContent.trim()).then(function() {
        const btn = keyEl.nextElementSibling;
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Gekopieerd';
        btn.classList.replace('bg-amber-500', 'bg-green-500');
        btn.classList.replace('hover:bg-amber-600', 'hover:bg-green-600');
        setTimeout(function() {
            btn.innerHTML = original;
            btn.classList.replace('bg-green-500', 'bg-amber-500');
            btn.classList.replace('hover:bg-green-600', 'hover:bg-amber-600');
        }, 2500);
    });
}
</script>

<?php require_once '../views/templates/footer.php'; ?>
