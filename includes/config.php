<?php
// Prevent multiple inclusion of config
if (!defined('CONFIG_INCLUDED')) {
    define('CONFIG_INCLUDED', true);

    // Detect environment
    $is_cli = (php_sapi_name() === 'cli' || php_sapi_name() === 'cli-server');
    $is_production_host = ($_SERVER['HTTP_HOST'] ?? '') === 'politiekpraat.nl';
    $is_production_server = $is_cli && strpos(__DIR__, '/home/naoufal/domains/politiekpraat.nl') !== false;
    $is_production = $is_production_host || $is_production_server;

    if (!defined('BASE_PATH')) {
        define('BASE_PATH', dirname(__DIR__));
    }

    // Development fallback: laat lokale env-overrides toe zonder secrets in git
    $local_env_file = BASE_PATH . '/includes/env.local.php';
    if (is_readable($local_env_file)) {
        require_once $local_env_file;
    }

    $app_env = strtolower((string) (getenv('APP_ENV') ?: ($is_production ? 'production' : 'development')));
    define('APP_ENV', $app_env);

    /**
     * Resolve config value from environment with optional default.
     */
    $resolve_env = static function (string $key, ?string $default = null): ?string {
        $value = getenv($key);

        if (!is_string($value)) {
            return $default;
        }

        $value = trim($value);

        if ($value === '') {
            return $default;
        }

        return $value;
    };

    // Database configuratie
    if ($is_production) {
        $db_host = $resolve_env('POLITIEKPRAAT_DB_HOST', 'localhost');
        $db_user = $resolve_env('POLITIEKPRAAT_DB_USER');
        $db_pass = $resolve_env('POLITIEKPRAAT_DB_PASS');
        $db_name = $resolve_env('POLITIEKPRAAT_DB_NAME');

        if ($db_user === null || $db_pass === null || $db_name === null) {
            throw new RuntimeException('Ontbrekende productie DB-config. Zet POLITIEKPRAAT_DB_USER, POLITIEKPRAAT_DB_PASS en POLITIEKPRAAT_DB_NAME in de environment.');
        }

        define('DB_HOST', $db_host);
        define('DB_USER', $db_user);
        define('DB_PASS', $db_pass);
        define('DB_NAME', $db_name);
        define('URLROOT', $resolve_env('POLITIEKPRAAT_URLROOT', 'https://politiekpraat.nl'));
    } else {
        define('DB_HOST', $resolve_env('POLITIEKPRAAT_DB_HOST', 'localhost'));
        define('DB_USER', $resolve_env('POLITIEKPRAAT_DB_USER', 'root'));
        define('DB_PASS', $resolve_env('POLITIEKPRAAT_DB_PASS', ''));
        define('DB_NAME', $resolve_env('POLITIEKPRAAT_DB_NAME', 'naoufal_politiekpraat_db'));
        define('URLROOT', $resolve_env('POLITIEKPRAAT_URLROOT', 'http://localhost:8000'));
    }

    // Site naam
    define('SITENAME', 'PolitiekPraat');

    // App versie
    define('APPVERSION', '1.0.0');

    // Include Database class first (required by session refresh)
    require_once __DIR__ . '/Database.php';

    // Include helper functions only if the HELPERS_INCLUDED constant is not defined
    if (!defined('HELPERS_INCLUDED')) {
        require_once __DIR__ . '/helpers.php';
    }

    // Start sessie
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/auth_remember.php';
    remember_restore_session_from_cookie();

    // Include session refresh mechanism (requires Database class)
    require_once __DIR__ . '/session_refresh.php';

    // Auto-refresh session on every page load to ensure fresh data
    autoRefreshSession();
}
