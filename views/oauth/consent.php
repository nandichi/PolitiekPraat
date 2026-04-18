<?php
/** @var array $client */
/** @var array $user */
/** @var array $granted */
/** @var array $optionalScopes */
/** @var array $scopeDefs */
/** @var string $csrfToken */
/** @var string|null $state */
/** @var string $redirectUri */
/** @var bool $isMcpClient */
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Autoriseer <?php echo htmlspecialchars($client['client_name']); ?> - PolitiekPraat</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com/3.4.1/tailwind.min.css">
    <style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif}</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white px-6 py-8">
            <div class="flex items-center gap-4">
                <?php if (!empty($client['logo_uri'])): ?>
                    <img src="<?php echo htmlspecialchars($client['logo_uri']); ?>" alt="" class="w-14 h-14 rounded-lg bg-white p-2">
                <?php endif; ?>
                <div>
                    <p class="text-sm opacity-80">Applicatie vraagt toegang</p>
                    <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($client['client_name']); ?></h1>
                    <?php if (!empty($client['client_uri'])): ?>
                        <a href="<?php echo htmlspecialchars($client['client_uri']); ?>" class="text-xs underline opacity-90" target="_blank" rel="noopener">
                            <?php echo htmlspecialchars(parse_url($client['client_uri'], PHP_URL_HOST) ?: $client['client_uri']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

            <div class="px-6 py-6">
                <p class="text-gray-700">
                    Je bent ingelogd als <strong><?php echo htmlspecialchars($user['username'] ?? 'onbekend'); ?></strong>.
                </p>

                <?php if (!empty($granted)): ?>
                    <h2 class="mt-5 text-sm font-semibold text-gray-900 uppercase tracking-wide">De applicatie vraagt:</h2>
                    <ul class="mt-2 space-y-2">
                        <?php foreach ($granted as $scope): ?>
                            <?php $def = $scopeDefs[$scope] ?? ['label' => $scope, 'description' => '']; ?>
                            <li class="flex gap-3 items-start p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <span class="inline-flex w-6 h-6 bg-blue-600 text-white rounded-full items-center justify-center text-xs font-bold mt-0.5" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($def['label']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($def['description']); ?></p>
                                    <p class="text-xs text-gray-400 mt-1 font-mono"><?php echo htmlspecialchars($scope); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($isMcpClient && !empty($optionalScopes)): ?>
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <h2 class="text-sm font-semibold text-amber-900 uppercase tracking-wide flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                            Extra rechten voor MCP-agents
                        </h2>
                        <p class="text-sm text-amber-800 mt-1">
                            Vink aan welke extra acties deze MCP-client (bv. Cursor of Claude Desktop) namens jou mag uitvoeren. Deze scopes zijn standaard <strong>uitgevinkt</strong> &mdash; je geeft alleen toegang als je ze aanzet.
                        </p>
                        <ul class="mt-3 space-y-2">
                            <?php foreach ($optionalScopes as $scope): ?>
                                <?php $def = $scopeDefs[$scope] ?? ['label' => $scope, 'description' => '']; ?>
                                <li>
                                    <label class="flex gap-3 items-start p-3 bg-white border border-amber-200 rounded-lg cursor-pointer hover:bg-amber-50 transition-colors">
                                        <input type="checkbox" name="scopes[]" value="<?php echo htmlspecialchars($scope); ?>" class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($def['label']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($def['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1 font-mono"><?php echo htmlspecialchars($scope); ?></p>
                                        </div>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <p class="text-sm text-gray-500 mt-5">
                    Na toestemming wordt je teruggestuurd naar <code class="font-mono text-xs break-all"><?php echo htmlspecialchars($redirectUri); ?></code>.
                    Je kunt deze toegang later intrekken via je profielinstellingen.
                </p>

                <div class="mt-6 flex gap-3">
                    <button type="submit" name="decision" value="deny" class="flex-1 px-4 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                        Weigeren
                    </button>
                    <button type="submit" name="decision" value="allow" class="flex-1 px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Toestaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
