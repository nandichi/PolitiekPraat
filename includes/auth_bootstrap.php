<?php

if (php_sapi_name() === 'cli') {
    return;
}

require_once __DIR__ . '/auth_remember.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

remember_restore_session_from_cookie();
