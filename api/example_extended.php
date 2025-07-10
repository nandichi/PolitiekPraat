<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';
require_once __DIR__ . '/JWTHelper.php';

class ExtendedAPI {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Voorbeeld: Beschermde endpoint die alleen voor ingelogde gebruikers toegankelijk is
    public function getUserProfile() {
        // Vereist authenticatie
        $payload = JWTHelper::requireAuth();
        
        try {
            $this->db->query("SELECT id, username, email, is_admin, created_at FROM users WHERE id = :id");
            $this->db->bind(':id', $payload->user_id);
            $user = $this->db->single();
            
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'User not found',
                    'message' => 'Gebruiker niet gevonden'
                ];
            }
            
            return [
                'success' => true,
                'data' => [
                    'id' => (int)$user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'is_admin' => (bool)$user->is_admin,
                    'created_at' => $user->created_at
                ],
                'message' => 'Profiel succesvol opgehaald'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het ophalen van het profiel'
            ];
        }
    }

    // Voorbeeld: Admin-only endpoint
    public function getAdminStats() {
        // Vereist admin rechten
        $payload = JWTHelper::requireAdmin();
        
        try {
            // Tel alle gebruikers
            $this->db->query("SELECT COUNT(*) as total_users FROM users");
            $userCount = $this->db->single()->total_users;
            
            // Tel alle blogs
            $this->db->query("SELECT COUNT(*) as total_blogs FROM blogs");
            $blogCount = $this->db->single()->total_blogs;
            
            // Tel blogs van vandaag
            $this->db->query("SELECT COUNT(*) as today_blogs FROM blogs WHERE DATE(published_at) = CURDATE()");
            $todayBlogs = $this->db->single()->today_blogs;
            
            return [
                'success' => true,
                'data' => [
                    'total_users' => (int)$userCount,
                    'total_blogs' => (int)$blogCount,
                    'blogs_today' => (int)$todayBlogs,
                    'generated_by' => $payload->username
                ],
                'message' => 'Statistieken succesvol opgehaald'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het ophalen van statistieken'
            ];
        }
    }

    // Voorbeeld: Blog maken (vereist authenticatie)
    public function createBlog($data) {
        // Vereist authenticatie
        $payload = JWTHelper::requireAuth();
        
        // Valideer input
        if (!isset($data['title']) || !isset($data['content'])) {
            return [
                'success' => false,
                'error' => 'Missing required fields',
                'message' => 'Titel en content zijn verplicht'
            ];
        }
        
        try {
            // Genereer slug
            $slug = $this->generateSlug($data['title']);
            
            $this->db->query("INSERT INTO blogs (title, slug, summary, content, author_id) VALUES (:title, :slug, :summary, :content, :author_id)");
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':slug', $slug);
            $this->db->bind(':summary', $data['summary'] ?? '');
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':author_id', $payload->user_id);
            
            if ($this->db->execute()) {
                $blogId = $this->db->lastInsertId();
                
                return [
                    'success' => true,
                    'data' => [
                        'id' => (int)$blogId,
                        'title' => $data['title'],
                        'slug' => $slug,
                        'author_id' => (int)$payload->user_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'message' => 'Blog succesvol aangemaakt'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Blog creation failed',
                    'message' => 'Blog aanmaken mislukt'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het aanmaken van de blog'
            ];
        }
    }

    // Voorbeeld: Blog verwijderen (alleen eigenaar of admin)
    public function deleteBlog($blogId) {
        // Vereist authenticatie
        $payload = JWTHelper::requireAuth();
        
        try {
            // Haal blog op om te controleren of gebruiker eigenaar is
            $this->db->query("SELECT author_id FROM blogs WHERE id = :id");
            $this->db->bind(':id', $blogId);
            $blog = $this->db->single();
            
            if (!$blog) {
                return [
                    'success' => false,
                    'error' => 'Blog not found',
                    'message' => 'Blog niet gevonden'
                ];
            }
            
            // Controleer of gebruiker eigenaar is of admin
            if ($blog->author_id != $payload->user_id && !$payload->is_admin) {
                return [
                    'success' => false,
                    'error' => 'Permission denied',
                    'message' => 'Geen toestemming om deze blog te verwijderen'
                ];
            }
            
            // Verwijder blog
            $this->db->query("DELETE FROM blogs WHERE id = :id");
            $this->db->bind(':id', $blogId);
            
            if ($this->db->execute()) {
                return [
                    'success' => true,
                    'message' => 'Blog succesvol verwijderd'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Blog deletion failed',
                    'message' => 'Blog verwijderen mislukt'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het verwijderen van de blog'
            ];
        }
    }

    private function generateSlug($title) {
        // Eenvoudige slug generatie
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Voeg timestamp toe om uniekheid te garanderen
        $slug .= '-' . time();
        
        return $slug;
    }
}

// Initialize API
$extendedAPI = new ExtendedAPI();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'profile':
                    $result = $extendedAPI->getUserProfile();
                    break;
                    
                case 'admin-stats':
                    $result = $extendedAPI->getAdminStats();
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action',
                        'message' => 'Ongeldige actie'
                    ]);
                    exit();
            }
            
            if (!$result['success']) {
                http_response_code(400);
            }
            
            echo json_encode($result);
            break;
            
        case 'POST':
            $action = $_GET['action'] ?? '';
            $input = json_decode(file_get_contents('php://input'), true);
            
            switch ($action) {
                case 'create-blog':
                    $result = $extendedAPI->createBlog($input);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action',
                        'message' => 'Ongeldige actie'
                    ]);
                    exit();
            }
            
            if (!$result['success']) {
                http_response_code(400);
            }
            
            echo json_encode($result);
            break;
            
        case 'DELETE':
            $action = $_GET['action'] ?? '';
            $blogId = $_GET['id'] ?? null;
            
            switch ($action) {
                case 'delete-blog':
                    if (!$blogId) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Blog ID required',
                            'message' => 'Blog ID is verplicht'
                        ]);
                        exit();
                    }
                    
                    $result = $extendedAPI->deleteBlog($blogId);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action',
                        'message' => 'Ongeldige actie'
                    ]);
                    exit();
            }
            
            if (!$result['success']) {
                http_response_code(400);
            }
            
            echo json_encode($result);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed',
                'message' => 'Deze HTTP methode wordt niet ondersteund'
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
        'message' => 'Er is een interne server fout opgetreden'
    ]);
} 