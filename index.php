<?php
// Error reporting aanzetten
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';
require_once 'includes/Router.php';

// Debug informatie (tijdelijk)
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
    echo "URLROOT: " . URLROOT . "\n";
    echo "Current Script: " . $_SERVER['SCRIPT_NAME'] . "\n";
    echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
    echo "PHP Include Path: " . get_include_path() . "\n";
    echo "</pre>";
}

// Definieer het basis pad voor de applicatie
$scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
define('BASE_PATH', $scriptDir);

// Voeg het base path toe aan de include path
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_PATH);

$router = new Router();

// Routes toevoegen met relatieve paden
$router->add('', 'controllers/home.php');
$router->add('home', 'controllers/home.php');
$router->add('blogs', 'controllers/blogs.php');
$router->add('forum', 'controllers/forum.php');
$router->add('contact', 'controllers/contact.php');
$router->add('login', 'controllers/auth/login.php');
$router->add('register', 'controllers/auth/register.php');
$router->add('logout', 'controllers/auth/logout.php');
$router->add('themas', 'controllers/themas.php');
$router->add('thema/([^/]+)', 'controllers/thema.php');

// Route dispatcher
$route = $router->dispatch($_SERVER['REQUEST_URI']);
$controller = BASE_PATH . '/' . $route['controller'];
$params = $route['params'];

if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "Script Dir: " . $scriptDir . "\n";
    echo "Base Path: " . BASE_PATH . "\n";
    echo "Controller Path: " . $controller . "\n";
    echo "Full Path: " . realpath($controller) . "\n";
    echo "File Exists: " . (file_exists($controller) ? 'Yes' : 'No') . "\n";
    echo "File Permissions: " . substr(sprintf('%o', fileperms($controller)), -4) . "\n";
    echo "File Owner: " . posix_getpwuid(fileowner($controller))['name'] . "\n";
    echo "Current PHP User: " . get_current_user() . "\n";
    echo "</pre>";
}

// Controller laden
try {
    if (file_exists($controller)) {
        require_once $controller;
    } else {
        require_once BASE_PATH . "/controllers/404.php";
    }
} catch (Exception $e) {
    echo "<pre>";
    echo "Error loading controller: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    echo "</pre>";
} 