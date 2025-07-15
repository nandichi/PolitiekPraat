<?php
// Enable error reporting voor debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Set headers voor API responses
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Debug functie
function debug_log($message) {
    if (defined('API_DEBUG') && API_DEBUG) {
        error_log("[API DEBUG] " . $message);
    }
}

// Error handler functie
function sendApiError($message, $statusCode = 500, $debug = null) {
    http_response_code($statusCode);
    $response = [
        'success' => false,
        'error' => $message,
        'timestamp' => date('c')
    ];
    
    if ($debug && defined('API_DEBUG') && API_DEBUG) {
        $response['debug'] = $debug;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// Success response functie
function sendApiResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'success' => true,
        'data' => $data,
        'timestamp' => date('c')
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Get base path
$basePath = dirname(__DIR__);
debug_log("Base path: " . $basePath);

// Include bestanden stap voor stap met error handling
try {
    // Eerst config laden
    $configPath = $basePath . '/includes/config.php';
    if (!file_exists($configPath)) {
        sendApiError("Config file niet gevonden: " . $configPath, 500);
    }
    require_once $configPath;
    debug_log("Config geladen");
    
    // API Debug mode aanzetten als we in development zijn
    if (!defined('API_DEBUG')) {
        define('API_DEBUG', !isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] !== 'politiekpraat.nl');
    }
    
    // Check of we composer hebben
    $composerPath = $basePath . '/vendor/autoload.php';
    if (file_exists($composerPath)) {
        require_once $composerPath;
        debug_log("Composer autoloader geladen");
    } else {
        debug_log("Composer autoloader niet gevonden, dit kan normaal zijn");
    }
    
    // Database class laden
    $databasePath = $basePath . '/includes/Database.php';
    if (!file_exists($databasePath)) {
        sendApiError("Database class niet gevonden", 500);
    }
    require_once $databasePath;
    debug_log("Database class geladen");
    
    // Test database verbinding
    try {
        $testDb = new Database();
        debug_log("Database verbinding succesvol");
    } catch (Exception $e) {
        sendApiError("Database verbinding mislukt: " . $e->getMessage(), 500);
    }
    
    // Andere belangrijke classes laden
    $requiredFiles = [
        '/includes/AuthController.php',
        '/includes/BlogController.php',
        '/includes/helpers.php'
    ];
    
    foreach ($requiredFiles as $file) {
        $filePath = $basePath . $file;
        if (file_exists($filePath)) {
            require_once $filePath;
            debug_log("Geladen: " . $file);
        } else {
            debug_log("Optioneel bestand niet gevonden: " . $file);
        }
    }
    
    // NewsModel laden (dit is optioneel)
    $newsModelPath = $basePath . '/models/NewsModel.php';
    if (file_exists($newsModelPath)) {
        require_once $newsModelPath;
        debug_log("NewsModel geladen");
    }
    
} catch (Exception $e) {
    sendApiError("Include fout: " . $e->getMessage(), 500, [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}

// API Router Class
class APIRouter {
    private $method;
    private $path;
    
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
        
        debug_log("Request: " . $this->method . " " . $this->path);
    }
    
    public function route() {
        try {
            // Split path into segments
            $segments = explode('/', $this->path);
            $endpoint = $segments[0];
            
            debug_log("Endpoint: " . $endpoint);
            
            switch ($endpoint) {
                case 'index':
                case '':
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
                    $this->handleStemwijzer();
                    break;
                    
                case 'test':
                    $this->handleTest();
                    break;
                    
                default:
                    sendApiError('Endpoint niet gevonden: ' . $endpoint, 404);
            }
            
        } catch (Exception $e) {
            sendApiError('Router fout: ' . $e->getMessage(), 500, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => API_DEBUG ? $e->getTraceAsString() : null
            ]);
        }
    }
    
    private function handleIndex() {
        $version = defined('APPVERSION') ? APPVERSION : '1.0.0';
        $urlroot = defined('URLROOT') ? URLROOT : 'http://localhost';
        
        sendApiResponse([
            'message' => 'PolitiekPraat REST API',
            'version' => $version,
            'status' => 'online',
            'time' => date('Y-m-d H:i:s'),
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
                ],
                'test' => [
                    'GET /api/test' => 'API connectie test'
                ]
            ],
            'debug_info' => API_DEBUG ? [
                'php_version' => PHP_VERSION,
                'server' => $_SERVER['HTTP_HOST'] ?? 'unknown',
                'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
                'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'unknown'
            ] : null
        ]);
    }
    
    private function handleTest() {
        $tests = [];
        
        // Test database
        try {
            $db = new Database();
            $tests['database'] = 'OK';
        } catch (Exception $e) {
            $tests['database'] = 'FOUT: ' . $e->getMessage();
        }
        
        // Test config constants
        $tests['config'] = [
            'URLROOT' => defined('URLROOT') ? URLROOT : 'NIET GEDEFINIEERD',
            'APPVERSION' => defined('APPVERSION') ? APPVERSION : 'NIET GEDEFINIEERD',
            'DB_HOST' => defined('DB_HOST') ? 'GEDEFINIEERD' : 'NIET GEDEFINIEERD',
            'DB_NAME' => defined('DB_NAME') ? DB_NAME : 'NIET GEDEFINIEERD'
        ];
        
        // Test classes
        $tests['classes'] = [
            'Database' => class_exists('Database') ? 'OK' : 'NIET GEVONDEN',
            'AuthController' => class_exists('AuthController') ? 'OK' : 'NIET GEVONDEN',
            'BlogController' => class_exists('BlogController') ? 'OK' : 'NIET GEVONDEN',
            'NewsModel' => class_exists('NewsModel') ? 'OK' : 'NIET GEVONDEN'
        ];
        
        // Test file permissions
        $tests['permissions'] = [
            'api_dir_readable' => is_readable(__DIR__) ? 'OK' : 'NIET LEESBAAR',
            'includes_dir_readable' => is_readable(dirname(__DIR__) . '/includes') ? 'OK' : 'NIET LEESBAAR'
        ];
        
        sendApiResponse([
            'test_results' => $tests,
            'overall_status' => 'API is bereikbaar',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    private function handleAuth($segments) {
        $this->requireEndpoint('auth');
        $authAPI = new AuthAPI();
        $authAPI->handle($this->method, $segments);
    }
    
    private function handleBlogs($segments) {
        $this->requireEndpoint('blogs');
        $blogsAPI = new BlogsAPI();
        $blogsAPI->handle($this->method, $segments);
    }
    
    private function handleNews($segments) {
        $this->requireEndpoint('news');
        $newsAPI = new NewsAPI();
        $newsAPI->handle($this->method, $segments);
    }
    
    private function handleUser($segments) {
        $this->requireEndpoint('user');
        $userAPI = new UserAPI();
        $userAPI->handle($this->method, $segments);
    }
    
    private function handleStemwijzer() {
        $stemwijzerPath = __DIR__ . '/stemwijzer.php';
        if (file_exists($stemwijzerPath)) {
            require_once $stemwijzerPath;
            exit();
        } else {
            sendApiError('Stemwijzer API niet gevonden', 404);
        }
    }
    
    private function requireEndpoint($endpoint) {
        $endpointPath = __DIR__ . '/endpoints/' . $endpoint . '.php';
        if (!file_exists($endpointPath)) {
            sendApiError("Endpoint bestand niet gevonden: endpoints/{$endpoint}.php", 500);
        }
        require_once $endpointPath;
        debug_log("Endpoint geladen: " . $endpoint);
    }
}

// Initialize and run the API router
try {
    $router = new APIRouter();
    $router->route();
} catch (Exception $e) {
    sendApiError('Fatale fout: ' . $e->getMessage(), 500, [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => API_DEBUG ? $e->getTraceAsString() : null
    ]);
} 