<?php
/**
 * Admin UI voor Personal Access Tokens (PAT).
 *
 * Maakt het mogelijk om long-lived bearer tokens te genereren voor
 * externe tools (Cursor, Claude Desktop, scripts) zonder door de
 * OAuth 2.0 authorization_code flow te gaan. De plain-token wordt
 * eenmalig getoond; daarna alleen een prefix.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/oauth/Scopes.php';
require_once __DIR__ . '/../includes/oauth/PersonalAccessTokens.php';

if (!isAdmin()) {
    redirect('login.php');
}

use PolitiekPraat\OAuth\PersonalAccessTokens;
use PolitiekPraat\OAuth\Scopes;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pat = new PersonalAccessTokens(new Database());
$userId = (int) ($_SESSION['user_id'] ?? 0);

$justCreated = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $scopes = $_POST['scopes'] ?? [];
        if (!is_array($scopes)) {
            $scopes = [];
        }
        $expiresInDays = (int) ($_POST['expires_in_days'] ?? 0);
        $expiresAt = $expiresInDays > 0
            ? date('Y-m-d H:i:s', time() + $expiresInDays * 86400)
            : null;

        try {
            $justCreated = $pat->create($userId, $name, $scopes, $expiresAt);
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }

    if ($action === 'revoke' && !empty($_POST['token_id'])) {
        $pat->revoke((int) $_POST['token_id'], $userId);
    }
}

$tokens = $pat->listForUser($userId);
$scopeDefs = Scopes::definitions();

require_once __DIR__ . '/../views/templates/header.php';
?>
<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50 py-10">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Personal Access Tokens</h1>
                <p class="text-gray-600 mt-1">Long-lived bearer tokens voor externe tools (Cursor, Claude Desktop, scripts) zonder OAuth-login.</p>
            </div>
            <a href="dashboard.php" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Terug</a>
        </div>

        <?php if ($justCreated !== null): ?>
            <div class="mb-6 bg-yellow-50 border border-yellow-300 rounded-xl p-5">
                <h2 class="font-semibold text-yellow-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"></path><path d="M12 17h.01"></path><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path></svg>
                    Token eenmalig getoond
                </h2>
                <p class="text-sm text-yellow-800 mt-1">Kopieer deze token nu. Wij bewaren alleen de hash; je kunt hem niet opnieuw opvragen.</p>
                <div class="mt-3 font-mono text-sm bg-white border border-yellow-300 rounded-lg p-3 break-all select-all">
                    <?php echo htmlspecialchars($justCreated['plain_token']); ?>
                </div>
                <div class="mt-3 text-sm text-yellow-900">
                    <strong>Naam:</strong> <?php echo htmlspecialchars($justCreated['name']); ?><br>
                    <strong>Scopes:</strong> <code><?php echo htmlspecialchars(Scopes::format($justCreated['scopes'])); ?></code><br>
                    <?php if (!empty($justCreated['expires_at'])): ?>
                        <strong>Vervalt:</strong> <?php echo htmlspecialchars($justCreated['expires_at']); ?>
                    <?php else: ?>
                        <strong>Vervalt:</strong> nooit
                    <?php endif; ?>
                </div>
                <details class="mt-4 text-sm">
                    <summary class="cursor-pointer font-semibold text-yellow-900">Hoe gebruik ik deze token in Cursor?</summary>
                    <div class="mt-2 text-yellow-900">
                        <p>Voeg de token toe aan <code>~/.cursor/mcp.json</code> onder de PolitiekPraat-server:</p>
<pre class="mt-2 bg-white border border-yellow-200 rounded p-3 text-xs overflow-x-auto"><code>"politiekpraat": {
  "url": "https://politiekpraat.nl/mcp",
  "headers": {
    "Authorization": "Bearer <?php echo htmlspecialchars($justCreated['plain_token']); ?>"
  }
}</code></pre>
                        <p class="mt-2">Herstart Cursor of doe "Reload MCP servers" na het opslaan.</p>
                    </div>
                </details>
            </div>
        <?php endif; ?>

        <?php if ($error !== null): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-red-800">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <section class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Nieuwe token aanmaken</h2>
            <form method="POST" class="space-y-5">
                <input type="hidden" name="action" value="create">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Naam (ter herkenning)</label>
                        <input type="text" name="name" required maxlength="120" placeholder="Cursor - Naoufal Macbook"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Vervalt over (dagen, 0 = nooit)</label>
                        <input type="number" name="expires_in_days" min="0" max="3650" value="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Scopes</label>
                    <p class="text-xs text-gray-500 mb-3">Selecteer wat deze token mag doen. Wees zo beperkt mogelijk.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <?php foreach ($scopeDefs as $scope => $def): ?>
                            <label class="flex items-start gap-2 p-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="<?php echo htmlspecialchars($scope); ?>"
                                       class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       <?php echo in_array($scope, ['mcp.read', 'mcp.write', 'blogs.read', 'blogs.write', 'media.write'], true) ? 'checked' : ''; ?>>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($def['label']); ?></div>
                                    <div class="text-xs text-gray-600"><?php echo htmlspecialchars($def['description']); ?></div>
                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5"><?php echo htmlspecialchars($scope); ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Token genereren
                </button>
            </form>
        </section>

        <section class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Bestaande tokens</h2>
            <?php if (empty($tokens)): ?>
                <p class="text-gray-500">Je hebt nog geen tokens aangemaakt.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-200">
                                <th class="py-2 pr-3 font-semibold">Naam</th>
                                <th class="py-2 pr-3 font-semibold">Prefix</th>
                                <th class="py-2 pr-3 font-semibold">Scopes</th>
                                <th class="py-2 pr-3 font-semibold">Laatst gebruikt</th>
                                <th class="py-2 pr-3 font-semibold">Vervalt</th>
                                <th class="py-2 pr-3 font-semibold">Status</th>
                                <th class="py-2 pr-3 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tokens as $t): ?>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 pr-3 font-semibold text-gray-900"><?php echo htmlspecialchars((string) $t->name); ?></td>
                                    <td class="py-3 pr-3 font-mono text-xs text-gray-600"><?php echo htmlspecialchars((string) $t->token_prefix); ?>...</td>
                                    <td class="py-3 pr-3">
                                        <div class="flex flex-wrap gap-1">
                                            <?php foreach (explode(' ', (string) $t->scopes) as $scope): ?>
                                                <span class="inline-block px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-mono"><?php echo htmlspecialchars($scope); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 text-gray-600 text-xs">
                                        <?php if ($t->last_used_at): ?>
                                            <?php echo htmlspecialchars((string) $t->last_used_at); ?>
                                            <?php if ($t->last_used_ip): ?>
                                                <br><span class="text-gray-400">IP: <?php echo htmlspecialchars((string) $t->last_used_ip); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">nooit</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 pr-3 text-gray-600 text-xs">
                                        <?php echo $t->expires_at ? htmlspecialchars((string) $t->expires_at) : '<span class="text-gray-400">nooit</span>'; ?>
                                    </td>
                                    <td class="py-3 pr-3">
                                        <?php if ($t->revoked_at): ?>
                                            <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">ingetrokken</span>
                                        <?php elseif ($t->expires_at && strtotime((string) $t->expires_at) < time()): ?>
                                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-semibold">verlopen</span>
                                        <?php else: ?>
                                            <span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">actief</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 pr-3 text-right">
                                        <?php if (!$t->revoked_at): ?>
                                            <form method="POST" onsubmit="return confirm('Weet je zeker dat je deze token wilt intrekken? Scripts die hem gebruiken stoppen direct met werken.');">
                                                <input type="hidden" name="action" value="revoke">
                                                <input type="hidden" name="token_id" value="<?php echo (int) $t->id; ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">intrekken</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php require_once __DIR__ . '/../views/templates/footer.php'; ?>
