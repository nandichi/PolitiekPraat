<?php
declare(strict_types=1);

/**
 * Admin shell header — editorial design system.
 *
 * @var string $adminPageTitle
 * @var string $adminPageDescription
 * @var string $adminActiveNav
 */
$adminPageTitle = $adminPageTitle ?? 'Beheer';
$adminPageDescription = $adminPageDescription ?? '';
$adminActiveNav = $adminActiveNav ?? '';

$cssAppPath = BASE_PATH . '/public/css/app.build.css';
$cssAppVer = file_exists($cssAppPath) ? filemtime($cssAppPath) : time();

$adminNavGroups = [
    'Content' => [
        ['id' => 'dashboard', 'label' => 'Blog dashboard', 'href' => 'dashboard.php'],
        ['id' => 'news-scraper', 'label' => 'Nieuws scraper', 'href' => 'news-scraper-beheer.php'],
        ['id' => 'scraper', 'label' => 'Scraper dashboard', 'href' => 'scraper_dashboard.php'],
        ['id' => 'auto-likes', 'label' => 'Auto likes', 'href' => 'auto-likes-beheer.php'],
    ],
    'Stemwijzer' => [
        ['id' => 'stemwijzer', 'label' => 'Overzicht', 'href' => 'stemwijzer-dashboard.php'],
        ['id' => 'stemwijzer-vragen', 'label' => 'Vragen', 'href' => 'stemwijzer-vraag-beheer.php'],
        ['id' => 'stemwijzer-partijen', 'label' => 'Partijen', 'href' => 'stemwijzer-partij-beheer.php'],
        ['id' => 'stemwijzer-standpunten', 'label' => 'Standpunten', 'href' => 'stemwijzer-standpunten-beheer.php'],
        ['id' => 'stemwijzer-stats', 'label' => 'Statistieken', 'href' => 'stemwijzer-statistieken.php'],
    ],
    'Stemmentracker' => [
        ['id' => 'stemmentracker-moties', 'label' => 'Moties', 'href' => 'stemmentracker-motie-beheer.php'],
        ['id' => 'stemmentracker-stemgedrag', 'label' => 'Stemgedrag', 'href' => 'stemmentracker-stemgedrag-beheer.php'],
    ],
    'Data' => [
        ['id' => 'partijen', 'label' => 'Politieke partijen', 'href' => 'political-parties-beheer.php'],
        ['id' => 'presidenten', 'label' => 'Presidenten', 'href' => 'presidenten-beheer.php'],
        ['id' => 'midterms', 'label' => 'Midterms 2026', 'href' => 'midterms-beheer.php'],
        ['id' => 'polls', 'label' => 'Polls', 'href' => 'polls-beheer.php'],
    ],
    'Integraties' => [
        ['id' => 'oauth', 'label' => 'OAuth clients', 'href' => 'oauth-clients-beheer.php'],
        ['id' => 'pat', 'label' => 'Access tokens', 'href' => 'personal-access-tokens.php'],
        ['id' => 'api-keys', 'label' => 'API sleutels', 'href' => 'api-sleutels-beheer.php'],
    ],
    'Devteam' => [
        ['id' => 'devteam', 'label' => 'Dashboard', 'href' => 'devteam-dashboard.php'],
        ['id' => 'devteam-agents', 'label' => 'Agents', 'href' => 'devteam-agents.php'],
    ],
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= pp_e($adminPageTitle) ?> - Admin - PolitiekPraat</title>
    <link rel="stylesheet" href="<?= pp_e(rtrim(URLROOT, '/') . '/public/css/app.build.css?v=' . $cssAppVer) ?>">
</head>
<body class="font-body admin-body">
<div class="admin-layout">
    <aside class="admin-sidebar" aria-label="Admin navigatie">
        <div class="admin-sidebar__brand">
            <a href="dashboard.php" class="admin-sidebar__logo">PolitiekPraat</a>
            <span class="admin-sidebar__badge">Admin</span>
        </div>
        <nav class="admin-sidebar__nav">
            <?php foreach ($adminNavGroups as $groupLabel => $items): ?>
                <div class="admin-sidebar__group">
                    <div class="admin-sidebar__group-label"><?= pp_e($groupLabel) ?></div>
                    <?php foreach ($items as $item): ?>
                        <a href="<?= pp_e($item['href']) ?>"
                           class="admin-sidebar__link<?= $adminActiveNav === $item['id'] ? ' is-active' : '' ?>">
                            <?= pp_e($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar__footer">
            <a href="<?= pp_e(pp_url('/')) ?>" class="admin-sidebar__link">Naar website</a>
            <a href="<?= pp_e(pp_url('/logout')) ?>" class="admin-sidebar__link">Uitloggen</a>
        </div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <h1 class="admin-topbar__title"><?= pp_e($adminPageTitle) ?></h1>
                <?php if ($adminPageDescription !== ''): ?>
                    <p class="admin-topbar__desc"><?= pp_e($adminPageDescription) ?></p>
                <?php endif; ?>
            </div>
            <div class="admin-topbar__meta">
                <span class="admin-topbar__user"><?= pp_e($_SESSION['username'] ?? 'Admin') ?></span>
            </div>
        </header>
        <div class="admin-content">
