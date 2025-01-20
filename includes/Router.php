<?php

class Router {
    private $routes = [];
    
    public function add($path, $controller) {
        $this->routes[$path] = $controller;
    }
    
    public function dispatch($uri) {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        if (empty($uri)) {
            $uri = 'home';
        }
        
        if (array_key_exists($uri, $this->routes)) {
            return $this->routes[$uri];
        }
        
        return 'controllers/404.php';
    }
} 