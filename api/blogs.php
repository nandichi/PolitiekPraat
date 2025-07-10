<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

class BlogsAPI {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllBlogs() {
        try {
            $this->db->query("
                SELECT 
                    b.id,
                    b.title,
                    b.slug,
                    b.summary,
                    b.content,
                    b.image_path,
                    b.video_path,
                    b.video_url,
                    b.views,
                    b.published_at,
                    u.username as author_name
                FROM blogs b
                LEFT JOIN users u ON b.author_id = u.id
                ORDER BY b.published_at DESC
            ");
            
            $blogs = $this->db->resultSet();
            
            // Verwerk de data voor veilige JSON output
            $processedBlogs = [];
            foreach ($blogs as $blog) {
                $processedBlogs[] = [
                    'id' => (int)$blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'summary' => $blog->summary,
                    'content' => $blog->content,
                    'image_path' => $blog->image_path,
                    'video_path' => $blog->video_path,
                    'video_url' => $blog->video_url,
                    'views' => (int)$blog->views,
                    'published_at' => $blog->published_at,
                    'author_name' => $blog->author_name
                ];
            }
            
            return [
                'success' => true,
                'data' => $processedBlogs,
                'count' => count($processedBlogs),
                'message' => 'Blogs succesvol opgehaald'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het ophalen van de blogs'
            ];
        }
    }

    public function getBlogById($id) {
        try {
            $this->db->query("
                SELECT 
                    b.id,
                    b.title,
                    b.slug,
                    b.summary,
                    b.content,
                    b.image_path,
                    b.video_path,
                    b.video_url,
                    b.views,
                    b.published_at,
                    u.username as author_name
                FROM blogs b
                LEFT JOIN users u ON b.author_id = u.id
                WHERE b.id = :id
            ");
            
            $this->db->bind(':id', $id);
            $blog = $this->db->single();
            
            if (!$blog) {
                return [
                    'success' => false,
                    'error' => 'Blog not found',
                    'message' => 'Blog niet gevonden'
                ];
            }
            
            // Verhoog views
            $this->db->query("UPDATE blogs SET views = views + 1 WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            $processedBlog = [
                'id' => (int)$blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'summary' => $blog->summary,
                'content' => $blog->content,
                'image_path' => $blog->image_path,
                'video_path' => $blog->video_path,
                'video_url' => $blog->video_url,
                'views' => (int)$blog->views + 1,
                'published_at' => $blog->published_at,
                'author_name' => $blog->author_name
            ];
            
            return [
                'success' => true,
                'data' => $processedBlog,
                'message' => 'Blog succesvol opgehaald'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het ophalen van de blog'
            ];
        }
    }
}

// Initialize API
$blogsAPI = new BlogsAPI();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $blogId = $_GET['id'] ?? null;
            
            if ($blogId) {
                // Haal specifieke blog op
                $result = $blogsAPI->getBlogById($blogId);
            } else {
                // Haal alle blogs op
                $result = $blogsAPI->getAllBlogs();
            }
            
            if (!$result['success']) {
                http_response_code(404);
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