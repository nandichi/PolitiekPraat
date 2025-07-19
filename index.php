<?php
// Error reporting aanzetten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definieer het basis pad voor de applicatie
$scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $scriptDir);
}

// Voeg het base path toe aan de include path
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_PATH);

// Composer autoloader
require_once 'vendor/autoload.php';

// Include function definitions first to avoid conflicts
require_once 'includes/functions.php';

// Then include other files that might use those functions
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/Router.php';
require_once 'includes/BlogController.php';
require_once 'controllers/blogs.php';  // Add BlogsController

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

// Start de sessie als die nog niet is gestart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

// Routes toevoegen met relatieve paden
$router->add('', 'controllers/home.php');
$router->add('home', 'controllers/home.php');
$router->add('blogs', function() {
    $controller = new BlogsController();
    $controller->index();
});
$router->add('blogs/create', function() {
    $controller = new BlogsController();
    $controller->create();
});
$router->add('blogs/manage', function() {
    $controller = new BlogsController();
    $controller->manage();
});
$router->add('blogs/edit/([0-9]+)', function($id) {
    $controller = new BlogsController();
    $controller->edit($id);
});
$router->add('blogs/delete/([0-9]+)', function($id) {
    $controller = new BlogsController();
    $controller->delete($id);
});
$router->add('blogs/updateLikes/([0-9]+)', function($id) {
    $controller = new BlogsController();
    $controller->updateLikes($id);
});
$router->add('blogs/like/([^/]+)', function($slug) {
    $controller = new BlogsController();
    $controller->handleLike($slug);
});
$router->add('blogs/([^/]+)', function($slug) {
    $controller = new BlogsController();
    $controller->view($slug);
});
$router->add('forum', 'controllers/forum.php');
$router->add('forum/create', 'controllers/forum/create.php');
$router->add('contact', 'controllers/contact.php');
$router->add('login', 'controllers/auth/login.php');
$router->add('register', 'controllers/auth/register.php');
$router->add('logout', 'controllers/auth/logout.php');
$router->add('themas', 'controllers/themas.php');
$router->add('thema/([^/]+)', 'controllers/thema.php');
$router->add('over-mij', 'controllers/over-mij.php');
$router->add('nieuws', 'controllers/nieuws.php');
$router->add('profile', 'controllers/profile/index.php');
$router->add('profile/edit', 'controllers/profile/edit.php');
$router->add('newsletter/subscribe', 'controllers/newsletter.php');
$router->add('newsletter/unsubscribe', 'controllers/newsletter.php?action=unsubscribe');
$router->add('newsletter/unsubscribe-success', 'controllers/newsletter.php?action=unsubscribe-success');
$router->add('newsletter/unsubscribe-error', 'controllers/newsletter.php?action=unsubscribe-error');
$router->add('stemwijzer', 'controllers/stemwijzer.php');
$router->add('resultaten/([a-zA-Z0-9]+)', function($shareId) {
    $_GET['id'] = $shareId;
    require_once 'controllers/resultaten.php';
});
$router->add('partijen', 'controllers/partijen.php');
$router->add('partijen/([^/]+)', function($partySlug) {
    $_GET['party'] = $partySlug;
    require_once 'controllers/partijen-detail.php';
});
$router->add('programma-vergelijker', 'controllers/programma-vergelijker.php');
$router->add('amerikaanse-verkiezingen', 'controllers/amerikaanse-verkiezingen.php');
$router->add('amerikaanse-verkiezingen/([0-9]+)', function($jaar) {
    $_GET['jaar'] = $jaar;
    require_once 'controllers/amerikaanse-verkiezingen.php';
});

// Get the requested URL
$request = $_SERVER['REQUEST_URI'];

// Remove query string
$request = strtok($request, '?');

// Remove trailing slash
$request = rtrim($request, '/');

// Remove leading slash
$request = ltrim($request, '/');

// Route dispatcher
try {
    $route = $router->dispatch($request);
    if (is_callable($route['controller'])) {
        call_user_func_array($route['controller'], $route['params']);
    } else {
        $controller = BASE_PATH . '/' . $route['controller'];
        if (file_exists($controller)) {
            require_once $controller;
        } else {
            require_once BASE_PATH . "/controllers/404.php";
        }
    }
} catch (Exception $e) {
    require_once BASE_PATH . "/controllers/404.php";
}

if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "Script Dir: " . $scriptDir . "\n";
    echo "Base Path: " . BASE_PATH . "\n";
    echo "Controller Path: " . $controller . "\n";
    echo "Full Path: " . realpath($controller) . "\n";
    echo "File Exists: " . (file_exists($controller) ? 'Yes' : 'No') . "\n";
    if (file_exists($controller)) {
        echo "File Permissions: " . substr(sprintf('%o', fileperms($controller)), -4) . "\n";
        echo "File Owner: " . posix_getpwuid(fileowner($controller))['name'] . "\n";
    }
    echo "Current PHP User: " . get_current_user() . "\n";
    echo "</pre>";
} 