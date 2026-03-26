<?php

require_once __DIR__ . '/../../includes/auth_csrf.php';

function assert_true($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$_SESSION = [];
$_POST = [];
http_response_code(200);

$generated_token = auth_ensure_csrf_token();
assert_true(is_string($generated_token) && strlen($generated_token) === 64, 'CSRF token moet worden gegenereerd');
assert_true(isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $generated_token, 'CSRF token moet in session staan');

$_POST['csrf_token'] = $generated_token;
http_response_code(200);
assert_true(auth_require_csrf_token_from_post() === true, 'Geldige CSRF token moet slagen');
assert_true(http_response_code() === 200, 'Geldige request moet 200 houden');

unset($_POST['csrf_token']);
http_response_code(200);
assert_true(auth_require_csrf_token_from_post() === false, 'POST zonder CSRF token moet falen');
assert_true(http_response_code() === 403, 'POST zonder CSRF token moet 403 geven');

echo "OK: auth csrf regressietest geslaagd\n";
