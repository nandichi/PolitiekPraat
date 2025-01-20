<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/Router.php';

$router = new Router();

// Routes toevoegen
$router->add('', 'controllers/home.php');
$router->add('home', 'controllers/home.php');
$router->add('blogs', 'controllers/blogs.php');
$router->add('forum', 'controllers/forum.php');
$router->add('contact', 'controllers/contact.php');
$router->add('login', 'controllers/auth/login.php');
$router->add('register', 'controllers/auth/register.php');
$router->add('logout', 'controllers/auth/logout.php');

// Route dispatcher
$controller = $router->dispatch($_SERVER['REQUEST_URI']);

// Controller laden
if (file_exists("../{$controller}")) {
    require_once "../{$controller}";
} else {
    require_once "../controllers/404.php";
} 