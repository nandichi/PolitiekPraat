<?php

require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/api_csrf.php';

function sendApiError($message, $statusCode = 500, $debug = null) {
    throw new RuntimeException($message, (int) $statusCode);
}

function assert_true($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

// Geldige request
$_SESSION = [
    'csrf_token' => 'token-123',
];
$_POST = [];
$_SERVER['HTTP_HOST'] = 'politiekpraat.nl';
$_SERVER['HTTP_ORIGIN'] = 'https://politiekpraat.nl';
$_SERVER['HTTP_X_CSRF_TOKEN'] = 'token-123';

assert_true(api_require_admin_csrf() === true, 'Geldige CSRF request moet slagen');

// Ongeldige token
$_SERVER['HTTP_X_CSRF_TOKEN'] = 'fout-token';
try {
    api_require_admin_csrf();
    assert_true(false, 'Ongeldige token moet falen');
} catch (RuntimeException $e) {
    assert_true($e->getCode() === 403, 'Ongeldige token moet 403 geven');
}

// Geldige token maar foute origin
$_SERVER['HTTP_X_CSRF_TOKEN'] = 'token-123';
$_SERVER['HTTP_ORIGIN'] = 'https://evil.example';
try {
    api_require_admin_csrf();
    assert_true(false, 'Foute origin moet falen');
} catch (RuntimeException $e) {
    assert_true($e->getCode() === 403, 'Foute origin moet 403 geven');
}

echo "OK: api admin csrf regressietest geslaagd\n";
