<?php
// Common API configuration
if (!headers_sent()) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

// Handle preflight OPTIONS request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files - try different paths
$config_path = null;
$database_path = null;

// Try different relative paths based on where this is called from
$possible_config_paths = [
    '../../includes/config.php',     // From api/api/ 
    './includes/config.php',         // From root
    '../includes/config.php'         // From api/
];

$possible_database_paths = [
    '../../includes/Database.php',   // From api/api/
    './includes/Database.php',       // From root  
    '../includes/Database.php'       // From api/
];

foreach ($possible_config_paths as $path) {
    if (file_exists($path)) {
        $config_path = $path;
        break;
    }
}

foreach ($possible_database_paths as $path) {
    if (file_exists($path)) {
        $database_path = $path;
        break;
    }
}

if (!$config_path || !$database_path) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo json_encode([
        'success' => false,
        'error' => 'Configuration files not found'
    ]);
    exit();
}

require_once $config_path;
require_once $database_path;

// Create database instance
function getDatabase() {
    static $database = null;
    if ($database === null) {
        $database = new Database();
    }
    return $database;
}

// Common error response function
function sendErrorResponse($code, $message) {
    if (!headers_sent()) {
        http_response_code($code);
    }
    echo json_encode([
        'success' => false,
        'error' => $message
    ]);
    exit();
}

// Common success response function
function sendSuccessResponse($data, $message = null) {
    $response = [
        'success' => true
    ];
    
    if ($message) {
        $response['message'] = $message;
    }
    
    // Merge data into response
    $response = array_merge($response, $data);
    
    echo json_encode($response);
    exit();
}

// Common function to extract tags from content
function extractTags($content, $category) {
    $tags = [$category];
    
    // Common political keywords
    $keywords = [
        'verkiezingen', 'stemmen', 'partij', 'coalitie', 'oppositie',
        'kamer', 'minister', 'premier', 'kabinet', 'regering',
        'europa', 'nederland', 'gemeenteraad', 'provincie',
        'politiek', 'democratie', 'beleid', 'wet', 'motie'
    ];
    
    $contentLower = strtolower(strip_tags($content));
    
    foreach ($keywords as $keyword) {
        if (strpos($contentLower, $keyword) !== false) {
            $tags[] = $keyword;
        }
    }
    
    return array_unique($tags);
}

// Function to calculate reading time
function calculateReadingTime($content) {
    $wordCount = str_word_count(strip_tags($content));
    return max(1, ceil($wordCount / 200)); // Assuming 200 words per minute
}
?> 