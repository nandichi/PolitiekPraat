<?php

class Router {
    private $routes = [];
    
    public function add($pattern, $controller) {
        $this->routes[$pattern] = $controller;
    }
    
    public function dispatch($uri) {
        // Verwijder query parameters
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Verwijder leading/trailing slashes
        $uri = trim($uri, '/');
        
        // Als de URI leeg is, gebruik de default route
        if (empty($uri)) {
            return ['controller' => $this->routes[''], 'params' => []];
        }

        // Loop door alle routes
        foreach ($this->routes as $pattern => $controller) {
            // Converteer route pattern naar regex
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            // Check of de URI matched met het pattern
            if (preg_match($pattern, $uri, $matches)) {
                // Verwijder de volledige match
                array_shift($matches);
                
                // Voor thema routes, zet de slug in $_GET
                if (strpos($controller, 'thema.php') !== false && !empty($matches)) {
                    $_GET['slug'] = $matches[0];
                }

                return [
                    'controller' => $controller,
                    'params' => $matches
                ];
            }
        }

        // Als geen route matched, return 404
        return ['controller' => 'controllers/404.php', 'params' => []];
    }
} 