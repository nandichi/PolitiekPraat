<?php
// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    // Verwijs naar de actuele login-route. De oude /login.php (rootbestand) had
    // kapotte includes en gaf HTTP 500; de juiste route is /login.
    $loginUrl = (defined('URLROOT') ? URLROOT : '') . '/login';
    header('Location: ' . $loginUrl);
    exit();
} 