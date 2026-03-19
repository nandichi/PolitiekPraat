<?php

require_once __DIR__ . '/../../includes/api_error_helpers.php';

function assert_true($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/test';
$_SERVER['HTTP_X_REQUEST_ID'] = 'req-test-1234';

$payload500 = api_build_error_response('SQLSTATE[42S22]: Unknown column users.password', 500, ['file' => '/var/www/private.php']);
assert_true($payload500['success'] === false, 'success moet false zijn');
assert_true($payload500['error'] === 'Interne serverfout', '500 fouten mogen geen interne details lekken');
assert_true($payload500['request_id'] === 'req-test-1234', 'request_id moet uit header komen');

$payload400 = api_build_error_response('Ongeldige parameter: page', 400);
assert_true($payload400['error'] === 'Ongeldige parameter: page', '4xx foutmelding mag behouden blijven');

// Simuleer debug/diagnostics mode
function can_access_diagnostics(): bool {
    return true;
}

$payload500Debug = api_build_error_response('SQLSTATE test detail', 500, ['query' => 'SELECT *']);
assert_true($payload500Debug['error'] === 'SQLSTATE test detail', 'in diagnostics mode mag detail zichtbaar zijn');
assert_true(isset($payload500Debug['debug']), 'debug payload moet aanwezig zijn in diagnostics mode');

echo "OK: api error sanitization regressietest geslaagd\n";
