<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
$basePath = dirname(__DIR__);

// Load Composer autoloader
require_once $basePath . '/vendor/autoload.php';
require_once $basePath . '/includes/config.php';
require_once $basePath . '/includes/Database.php';
require_once $basePath . '/includes/AuthController.php';
require_once $basePath . '/includes/BlogController.php';
require_once $basePath . '/models/NewsModel.php';
require_once $basePath . '/includes/helpers.php';

// API Router Class
class APIRouter {
    private $method;
    private $path;
    private $routes = [];
    
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Get path from REQUEST_URI, remove query string and API prefix
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->path = str_replace('/api', '', $this->path);
        $this->path = trim($this->path, '/');
        
        // If path is empty, set to index
        if (empty($this->path)) {
            $this->path = 'index';
        }
    }
    
    public function route() {
        try {
            // Split path into segments
            $segments = explode('/', $this->path);
            $endpoint = $segments[0];
            
            switch ($endpoint) {
                case 'index':
                    $this->handleIndex();
                    break;
                    
                case 'auth':
                    $this->handleAuth($segments);
                    break;
                    
                case 'blogs':
                    $this->handleBlogs($segments);
                    break;
                    
                case 'news':
                    $this->handleNews($segments);
                    break;
                    
                case 'user':
                    $this->handleUser($segments);
                    break;
                    
                case 'stemwijzer':
                    // Redirect to existing stemwijzer API
                    require_once 'stemwijzer.php';
                    exit();
                    break;
                    
                default:
                    $this->sendError('Endpoint not found', 404);
            }
            
        } catch (Exception $e) {
            $this->sendError('Server error: ' . $e->getMessage(), 500);
        }
    }
    
    private function handleIndex() {
        $this->sendResponse([
            'message' => 'PolitiekPraat REST API',
            'version' => APPVERSION,
            'endpoints' => [
                'auth' => [
                    'POST /api/auth/login' => 'Inloggen',
                    'POST /api/auth/register' => 'Registreren',
                    'POST /api/auth/logout' => 'Uitloggen',
                    'GET /api/auth/me' => 'Huidige gebruiker ophalen'
                ],
                'blogs' => [
                    'GET /api/blogs' => 'Alle blogs ophalen',
                    'GET /api/blogs/{id}' => 'Specifieke blog ophalen',
                    'POST /api/blogs' => 'Nieuwe blog aanmaken',
                    'PUT /api/blogs/{id}' => 'Blog bijwerken',
                    'DELETE /api/blogs/{id}' => 'Blog verwijderen'
                ],
                'news' => [
                    'GET /api/news' => 'Nieuws ophalen',
                    'GET /api/news?filter={filter}' => 'Gefilterd nieuws'
                ],
                'user' => [
                    'GET /api/user/profile' => 'Gebruikersprofiel',
                    'PUT /api/user/profile' => 'Profiel bijwerken'
                ],
                'stemwijzer' => [
                    'GET /api/stemwijzer' => 'Stemwijzer data'
                ]
            ]
        ]);
    }
    
    private function handleAuth($segments) {
        require_once 'endpoints/auth.php';
        $authAPI = new AuthAPI();
        $authAPI->handle($this->method, $segments);
    }
    
    private function handleBlogs($segments) {
        require_once 'endpoints/blogs.php';
        $blogsAPI = new BlogsAPI();
        $blogsAPI->handle($this->method, $segments);
    }
    
    private function handleNews($segments) {
        require_once 'endpoints/news.php';
        $newsAPI = new NewsAPI();
        $newsAPI->handle($this->method, $segments);
    }
    
    private function handleUser($segments) {
        require_once 'endpoints/user.php';
        $userAPI = new UserAPI();
        $userAPI->handle($this->method, $segments);
    }
    
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    private function sendError($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// Initialize and run the API router
$router = new APIRouter();
$router->route(); 