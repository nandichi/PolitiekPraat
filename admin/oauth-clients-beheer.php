<?php
/**
 * Admin UI voor OAuth 2.0 clients (registreren, bekijken, verwijderen).
 *
 * Werkt samen met de database-tabellen uit
 * database/migrations/create_oauth_tables.sql.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/oauth/OAuthServer.php';

if (!isAdmin()) {
    redirect('login.php');
}

use PolitiekPraat\OAuth\OAuthServer;
use PolitiekPraat\OAuth\Scopes;

$server  = new OAuthServer(new Database());
$clients = $server->clients();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$oneTimeSecret = null;
$oneTimeClientId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $redirectUris = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($_POST['redirect_uris'] ?? '')))));
        $scopesInput = array_values(array_filter(array_map('trim', explode(' ', (string) ($_POST['scopes'] ?? '')))));
        $grantTypes  = array_values($_POST['grant_types'] ?? ['authorization_code']);

        $created = $clients->create([
            'client_name'                => trim((string) ($_POST['client_name'] ?? 'Unnamed')),
            'client_uri'                 => $_POST['client_uri'] ?? null,
            'logo_uri'                   => $_POST['logo_uri'] ?? null,
            'redirect_uris'              => $redirectUris,
            'grant_types'                => $grantTypes,
            'response_types'             => ['code'],
            'scopes'                     => $scopesInput ?: Scopes::supported(),
            'token_endpoint_auth_method' => $_POST['auth_method'] ?? 'client_secret_basic',
            'is_public'                  => ($_POST['auth_method'] ?? '') === 'none',
            'owner_user_id'              => $_SESSION['user_id'] ?? null,
        ]);

        if (!empty($created['__plain_client_secret'])) {
            $oneTimeSecret = $created['__plain_client_secret'];
            $oneTimeClientId = $created['client_id'];
        }
    }

    if ($action === 'delete' && !empty($_POST['client_id'])) {
        $clients->delete((string) $_POST['client_id']);
    }
}

$all = $clients->all();

require_once __DIR__ . '/../views/templates/header.php';
?>
<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50 py-10">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">OAuth 2.0 clients</h1>
                <p class="text-gray-600 mt-1">Beheer applicaties die namens gebruikers toegang krijgen tot de PolitiekPraat API.</p>
            </div>
            <a href="dashboard.php" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Terug</a>
        </div>

        <?php if ($oneTimeSecret !== null): ?>
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                <h2 class="font-semibold text-yellow-900">Client secret (eenmalig getoond)</h2>
                <p class="text-sm text-yellow-800 mt-1">Bewaar dit veilig. Dit secret wordt niet meer getoond.</p>
                <div class="mt-3 font-mono text-sm bg-white border border-yellow-200 rounded-lg p-3">
                    <div>client_id: <strong><?php echo htmlspecialchars($oneTimeClientId); ?></strong></div>
                    <div>client_secret: <strong><?php echo htmlspecialchars($oneTimeSecret); ?></strong></div>
                </div>
            </div>
        <?php endif; ?>

        <section class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Nieuwe client registreren</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Naam</label>
                        <input type="text" name="client_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Client URI</label>
                        <input type="url" name="client_uri" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Redirect URIs (één per regel)</label>
                    <textarea name="redirect_uris" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-sm" placeholder="https://example.com/callback"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Token auth method</label>
                        <select name="auth_method" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="client_secret_basic">client_secret_basic (vertrouwelijke clients)</option>
                            <option value="client_secret_post">client_secret_post</option>
                            <option value="none">none (public clients met PKCE)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Grant types</label>
                        <div class="flex flex-wrap gap-2 text-sm">
                            <?php foreach (['authorization_code','refresh_token','client_credentials'] as $gt): ?>
                                <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1.5">
                                    <input type="checkbox" name="grant_types[]" value="<?php echo $gt; ?>" <?php echo in_array($gt, ['authorization_code','refresh_token'], true) ? 'checked' : ''; ?>>
                                    <?php echo $gt; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Scopes (spatie-gescheiden)</label>
                    <input type="text" name="scopes" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-sm"
                           value="<?php echo htmlspecialchars(Scopes::format(Scopes::supported())); ?>">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">Client aanmaken</button>
            </form>
        </section>

        <section class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Geregistreerde clients (<?php echo count($all); ?>)</h2>
            <div class="divide-y divide-gray-200">
                <?php foreach ($all as $c): ?>
                    <div class="py-4 flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($c['client_name']); ?></div>
                            <div class="text-xs text-gray-500 font-mono"><?php echo htmlspecialchars($c['client_id']); ?></div>
                            <div class="mt-2 text-sm text-gray-700">Redirects:
                                <code class="block font-mono text-xs bg-gray-50 rounded p-2 mt-1"><?php echo htmlspecialchars(implode("\n", (array) $c['redirect_uris'])); ?></code>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                Grants: <?php echo htmlspecialchars(implode(', ', (array) $c['grant_types'])); ?>
                                &middot; Auth: <?php echo htmlspecialchars($c['token_endpoint_auth_method']); ?>
                                &middot; Scopes: <?php echo htmlspecialchars(Scopes::format((array) $c['scopes'])); ?>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('Client verwijderen?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($c['client_id']); ?>">
                            <button class="text-sm text-red-600 hover:underline">Verwijderen</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($all)): ?>
                    <p class="text-sm text-gray-500 py-4">Nog geen clients geregistreerd.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>
<?php
require_once __DIR__ . '/../views/templates/footer.php';
