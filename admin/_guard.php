<?php
declare(strict_types=1);

/**
 * Centrale admin-authenticatie.
 *
 * Vereist dat config + functions al geladen zijn (via _bootstrap.php).
 * Voor JSON-endpoints: define('ADMIN_GUARD_JSON', true) vóór require _bootstrap.php.
 */

if (!function_exists('isLoggedIn') || !function_exists('isAdmin')) {
    http_response_code(500);
    exit('Admin guard: auth helpers niet beschikbaar.');
}

$guardJson = defined('ADMIN_GUARD_JSON') && ADMIN_GUARD_JSON === true;

if (!isLoggedIn()) {
    if ($guardJson) {
        if (!headers_sent()) {
            http_response_code(401);
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode(['success' => false, 'error' => 'Niet ingelogd.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    redirect('login');
    exit;
}

if (!isAdmin()) {
    if ($guardJson) {
        if (!headers_sent()) {
            http_response_code(403);
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode(['success' => false, 'error' => 'Geen admin-rechten.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!headers_sent()) {
        http_response_code(403);
    }
    require_once BASE_PATH . '/views/templates/header.php';
    echo '<section class="pp-container pp-container--narrow py-24 text-center">';
    echo '<div class="font-mono text-tabular text-display-3xl text-[color:var(--color-ink-faint)] mb-4">403</div>';
    echo '<h1 class="font-display text-display-xl text-[color:var(--color-ink)] mb-4">Geen toegang</h1>';
    echo '<p class="text-[color:var(--color-ink-muted)] mb-8">Je account heeft geen beheerrechten voor dit gedeelte.</p>';
    echo '<a href="' . pp_e(pp_url('/')) . '" class="btn btn--primary">Naar de homepage</a>';
    echo '</section>';
    require_once BASE_PATH . '/views/templates/footer.php';
    exit;
}
