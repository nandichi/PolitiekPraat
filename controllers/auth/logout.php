<?php
require_once BASE_PATH . '/includes/auth_remember.php';

remember_invalidate_current_token();

// Verwijder alle sessie variabelen
session_unset();

// Vernietig de sessie
session_destroy();

// Redirect naar homepage
header('Location: ' . URLROOT);
exit; 