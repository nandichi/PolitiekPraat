<?php

class Router {
    private $routes = [];
    
    public function add($route, $controller) {
        $this->routes[$route] = [
            'pattern' => $this->convertToRegex($route),
            'controller' => $controller
        ];
    }
    
    private function convertToRegex($route) {
        return '#^' . str_replace('/', '\/', $route) . '$#';
    }
    
    public function dispatch($url) {
        // Verwijder query parameters
        $url = parse_url($url, PHP_URL_PATH);
        
        // Verwijder leading/trailing slashes
        $url = trim($url, '/');
        
        // Als de URL leeg is, gebruik de default route
        if (empty($url)) {
            return [
                'controller' => $this->routes['']['controller'],
                'params' => []
            ];
        }

        // Loop door alle routes
        foreach ($this->routes as $route) {
            // Check of de URI matched met het pattern
            if (preg_match($route['pattern'], $url, $matches)) {
                // Verwijder de volledige match
                array_shift($matches);
                
                // Voor thema en blog routes, zet de slug in $_GET als het geen callable is
                if (is_string($route['controller']) && 
                    (strpos($route['controller'], 'thema.php') !== false || 
                     strpos($route['controller'], 'blogs/view.php') !== false) && 
                    !empty($matches)) {
                    $_GET['slug'] = $matches[0];
                }

                return [
                    'controller' => $route['controller'],
                    'params' => $matches
                ];
            }
        }

        // Als geen route matched, return 404
        return [
            'controller' => 'controllers/404.php',
            'params' => []
        ];
    }
} 