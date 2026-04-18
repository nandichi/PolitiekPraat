<?php
/** @var array $client */
/** @var array $user */
/** @var array $granted */
/** @var array $scopeDefs */
/** @var string $csrfToken */
/** @var string|null $state */
/** @var string $redirectUri */
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
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg overflow-hidden">
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

        <div class="px-6 py-6">
            <p class="text-gray-700">
                Je bent ingelogd als <strong><?php echo htmlspecialchars($user['username'] ?? 'onbekend'); ?></strong>.
                De applicatie vraagt de volgende rechten:
            </p>

            <ul class="mt-4 space-y-3">
                <?php foreach ($granted as $scope): ?>
                    <?php $def = $scopeDefs[$scope] ?? ['label' => $scope, 'description' => '']; ?>
                    <li class="flex gap-3 items-start p-3 bg-gray-50 rounded-lg">
                        <span class="inline-flex w-6 h-6 bg-blue-100 text-blue-700 rounded-full items-center justify-center text-xs font-bold mt-0.5">+</span>
                        <div>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($def['label']); ?></p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($def['description']); ?></p>
                            <p class="text-xs text-gray-400 mt-1 font-mono"><?php echo htmlspecialchars($scope); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="text-sm text-gray-500 mt-5">
                Na toestemming wordt je teruggestuurd naar <code class="font-mono text-xs"><?php echo htmlspecialchars($redirectUri); ?></code>.
                Je kunt deze toegang later intrekken via je profielinstellingen.
            </p>

            <form method="POST" class="mt-6 flex gap-3">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <button type="submit" name="decision" value="deny" class="flex-1 px-4 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                    Weigeren
                </button>
                <button type="submit" name="decision" value="allow" class="flex-1 px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Toestaan
                </button>
            </form>
        </div>
    </div>
</body>
</html>
