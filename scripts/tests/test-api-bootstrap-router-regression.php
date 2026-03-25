<?php

function can_access_diagnostics(): bool {
    return false;
}

require_once __DIR__ . '/../../includes/api_error_helpers.php';

function assert_true($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/blogs';
$_SERVER['HTTP_X_REQUEST_ID'] = 'req-bootstrap-130';

$bootstrapPayload = api_build_error_response('Interne serverfout', 500, [
    'context' => 'include_bootstrap',
    'exception' => 'PDOException: SQLSTATE[HY000] Access denied',
    'file' => '/var/www/html/api/index.php',
    'line' => 181,
    'trace' => 'stacktrace...'
]);

assert_true($bootstrapPayload['error'] === 'Interne serverfout', '5xx bootstrap fout moet gesanitized blijven');
assert_true(!isset($bootstrapPayload['debug']), 'bootstrap response mag geen debug payload bevatten zonder diagnostics');

$routerPayload = api_build_error_response('Interne serverfout', 500, [
    'context' => 'router_dispatch',
    'exception' => 'Undefined class FooBar',
    'file' => '/var/www/html/api/index.php',
    'line' => 302,
    'trace' => 'stacktrace...'
]);

assert_true($routerPayload['error'] === 'Interne serverfout', 'router fout moet gesanitized blijven');
assert_true(!isset($routerPayload['debug']), 'router response mag geen debug payload bevatten zonder diagnostics');
assert_true(($routerPayload['request_id'] ?? '') === 'req-bootstrap-130', 'request_id moet doorgegeven worden');

echo "OK: bootstrap/router error response regression geslaagd\n";
