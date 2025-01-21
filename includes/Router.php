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
        
        // Split URI in delen
        $uri_parts = explode('/', $uri);
        
        // Check voor exacte match
        if (array_key_exists($uri, $this->routes)) {
            return ['controller' => $this->routes[$uri], 'params' => []];
        }
        
        // Check voor blog view route
        if (count($uri_parts) === 3 && $uri_parts[0] === 'blogs' && $uri_parts[1] === 'view') {
            return [
                'controller' => 'controllers/blogs/view.php',
                'params' => ['slug' => $uri_parts[2]]
            ];
        }
        
        // Check voor blog create route
        if (count($uri_parts) === 2 && $uri_parts[0] === 'blogs' && $uri_parts[1] === 'create') {
            return [
                'controller' => 'controllers/blogs/create.php',
                'params' => []
            ];
        }
        
        // Check voor forum topic route
        if (count($uri_parts) === 3 && $uri_parts[0] === 'forum' && $uri_parts[1] === 'topic') {
            return [
                'controller' => 'controllers/forum/topic.php',
                'params' => ['id' => $uri_parts[2]]
            ];
        }
        
        // Check voor forum create route
        if (count($uri_parts) === 2 && $uri_parts[0] === 'forum' && $uri_parts[1] === 'create') {
            return [
                'controller' => 'controllers/forum/create.php',
                'params' => []
            ];
        }
        
        // Check voor forum reply delete route
        if (count($uri_parts) === 4 && $uri_parts[0] === 'forum' && $uri_parts[1] === 'reply' && $uri_parts[2] === 'delete') {
            return [
                'controller' => 'controllers/forum/reply/delete.php',
                'params' => ['id' => $uri_parts[3]]
            ];
        }
        
        // Check voor comment delete route
        if (count($uri_parts) === 3 && $uri_parts[0] === 'comments' && $uri_parts[1] === 'delete') {
            return [
                'controller' => 'controllers/comments/delete.php',
                'params' => ['id' => $uri_parts[2]]
            ];
        }
        
        // Check voor eerste deel van URI
        if (array_key_exists($uri_parts[0], $this->routes)) {
            return ['controller' => $this->routes[$uri_parts[0]], 'params' => []];
        }
        
        return ['controller' => 'controllers/404.php', 'params' => []];
    }
} 