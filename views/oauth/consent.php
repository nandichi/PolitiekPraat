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

$cssHref = rtrim(URLROOT, '/') . '/public/css/app.build.css';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Autoriseer <?= pp_e($client['client_name'] ?? 'applicatie') ?> - PolitiekPraat</title>
    <link rel="stylesheet" href="<?= pp_e($cssHref) ?>">
</head>
<body class="font-body" style="background: var(--color-paper); min-height: 100vh;">
    <div class="pp-container pp-container--narrow" style="min-height: 100vh; display: flex; align-items: center; padding-block: 2rem;">
        <div class="keyline-card overflow-hidden w-full">
            <div class="p-6 md:p-8 border-b border-[color:var(--color-keyline)]" style="background: var(--color-hague-tint);">
                <div class="flex items-center gap-4">
                    <?php if (!empty($client['logo_uri'])): ?>
                        <img src="<?= pp_e($client['logo_uri']) ?>" alt="" class="w-14 h-14 rounded-lg bg-[color:var(--color-paper)] p-2 object-contain">
                    <?php endif; ?>
                    <div>
                        <p class="eyebrow text-[color:var(--color-hague)] mb-1">Applicatie vraagt toegang</p>
                        <h1 class="font-display text-display-lg text-[color:var(--color-ink)] leading-tight"><?= pp_e($client['client_name'] ?? '') ?></h1>
                        <?php if (!empty($client['client_uri'])): ?>
                            <a href="<?= pp_e($client['client_uri']) ?>" class="text-xs text-[color:var(--color-hague)] underline underline-offset-2 mt-1 inline-block" target="_blank" rel="noopener">
                                <?= pp_e(parse_url($client['client_uri'], PHP_URL_HOST) ?: $client['client_uri']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <form method="POST" class="p-6 md:p-8">
                <input type="hidden" name="csrf_token" value="<?= pp_e($csrfToken) ?>">

                <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                    Je bent ingelogd als <strong class="text-[color:var(--color-ink)]"><?= pp_e($user['username'] ?? 'onbekend') ?></strong>.
                </p>

                <?php if (!empty($granted)): ?>
                    <h2 class="eyebrow mt-6 mb-3 text-[color:var(--color-ink-faint)]">De applicatie vraagt</h2>
                    <ul class="space-y-3">
                        <?php foreach ($granted as $scope):
                            $def = $scopeDefs[$scope] ?? ['label' => $scope, 'description' => ''];
                        ?>
                            <li class="keyline-card p-4 flex gap-3 items-start">
                                <span class="inline-flex w-7 h-7 rounded-full items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--color-olive); color: white;">
                                    <?= pp_icon('check', 14) ?>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-[color:var(--color-ink)]"><?= pp_e($def['label']) ?></p>
                                    <?php if (!empty($def['description'])): ?>
                                        <p class="text-sm text-[color:var(--color-ink-muted)] mt-1"><?= pp_e($def['description']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs font-mono text-tabular text-[color:var(--color-ink-faint)] mt-1"><?= pp_e($scope) ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($isMcpClient && !empty($optionalScopes)): ?>
                    <div class="mt-6 keyline-card p-5" style="border-color: color-mix(in srgb, var(--color-ochre) 40%, transparent); background: var(--color-ochre-tint);">
                        <h2 class="eyebrow text-[color:var(--color-ochre)] mb-2 flex items-center gap-2">
                            <?= pp_icon('layers', 16) ?>
                            Extra rechten voor MCP-agents
                        </h2>
                        <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed">
                            Vink aan welke extra acties deze MCP-client (bv. Cursor of Claude Desktop) namens jou mag uitvoeren. Standaard zijn deze scopes uitgevinkt.
                        </p>
                        <ul class="mt-4 space-y-2">
                            <?php foreach ($optionalScopes as $scope):
                                $def = $scopeDefs[$scope] ?? ['label' => $scope, 'description' => ''];
                            ?>
                                <li>
                                    <label class="keyline-card p-4 flex gap-3 items-start cursor-pointer hover:border-[color:var(--color-hague)] transition-colors">
                                        <input type="checkbox" name="scopes[]" value="<?= pp_e($scope) ?>" class="mt-1">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-[color:var(--color-ink)]"><?= pp_e($def['label']) ?></p>
                                            <?php if (!empty($def['description'])): ?>
                                                <p class="text-sm text-[color:var(--color-ink-muted)] mt-1"><?= pp_e($def['description']) ?></p>
                                            <?php endif; ?>
                                            <p class="text-xs font-mono text-tabular text-[color:var(--color-ink-faint)] mt-1"><?= pp_e($scope) ?></p>
                                        </div>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <p class="text-sm text-[color:var(--color-ink-muted)] mt-6 leading-relaxed">
                    Na toestemming word je teruggestuurd naar
                    <code class="font-mono text-xs break-all text-[color:var(--color-ink)]"><?= pp_e($redirectUri) ?></code>.
                    Je kunt deze toegang later intrekken via je profielinstellingen.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit" name="decision" value="deny" class="btn btn--ghost flex-1 justify-center">
                        Weigeren
                    </button>
                    <button type="submit" name="decision" value="allow" class="btn btn--primary flex-1 justify-center">
                        Toestaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
