<?php

class BlogsAPI {
    private $db;
    private $blogController;
    private $secretKey;
    
    public function __construct() {
        $this->db = new Database();
        $this->blogController = new BlogController();
        $this->secretKey = 'PolitiekPraat_JWT_Secret_2024_Secure_Key_' . (defined('DB_NAME') ? DB_NAME : 'default');
    }
    
    public function handle($method, $segments) {
        switch ($method) {
            case 'GET':
                if (isset($segments[1])) {
                    // GET /api/blogs/{id} of /api/blogs/{slug}
                    $this->getBlog($segments[1]);
                } else {
                    // GET /api/blogs
                    $this->getAllBlogs();
                }
                break;
                
            case 'POST':
                // POST /api/blogs - Nieuwe blog aanmaken
                $this->createBlog();
                break;
                
            case 'PUT':
                if (isset($segments[1])) {
                    // PUT /api/blogs/{id}
                    $this->updateBlog($segments[1]);
                } else {
                    $this->sendError('Blog ID is verplicht voor update', 400);
                }
                break;
                
            case 'DELETE':
                if (isset($segments[1])) {
                    // DELETE /api/blogs/{id}
                    $this->deleteBlog($segments[1]);
                } else {
                    $this->sendError('Blog ID is verplicht voor verwijdering', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }
    
    private function getAllBlogs() {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
            $author_id = isset($_GET['author_id']) ? intval($_GET['author_id']) : null;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            $offset = ($page - 1) * $limit;
            
            // Build query
            $whereConditions = [];
            $bindings = [];
            
            if ($author_id) {
                $whereConditions[] = "blogs.author_id = :author_id";
                $bindings[':author_id'] = $author_id;
            }
            
            if ($search) {
                $whereConditions[] = "(blogs.title LIKE :search OR blogs.content LIKE :search)";
                $bindings[':search'] = "%$search%";
            }
            
            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
            
            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM blogs 
                        JOIN users ON blogs.author_id = users.id 
                        $whereClause";
            
            $this->db->query($countSql);
            foreach ($bindings as $param => $value) {
                $this->db->bind($param, $value);
            }
            $totalResult = $this->db->single();
            $total = $totalResult->total;
            
            // Get blogs
            $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                   FROM blogs 
                   JOIN users ON blogs.author_id = users.id 
                   $whereClause
                   ORDER BY blogs.published_at DESC 
                   LIMIT :limit OFFSET :offset";
            
            $this->db->query($sql);
            foreach ($bindings as $param => $value) {
                $this->db->bind($param, $value);
            }
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            $blogs = $this->db->resultSet();
            
            // Format blogs for API
            $formattedBlogs = array_map([$this, 'formatBlogForAPI'], $blogs);
            
            $this->sendResponse([
                'blogs' => $formattedBlogs,
                'pagination' => [
                    'current_page' => $page,
                    'limit' => $limit,
                    'total' => (int)$total,
                    'total_pages' => ceil($total / $limit),
                    'has_next' => ($page * $limit) < $total,
                    'has_prev' => $page > 1
                ]
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function getBlog($identifier) {
        try {
            // Check if identifier is numeric (ID) or string (slug)
            if (is_numeric($identifier)) {
                $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                       FROM blogs 
                       JOIN users ON blogs.author_id = users.id 
                       WHERE blogs.id = :identifier";
            } else {
                $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                       FROM blogs 
                       JOIN users ON blogs.author_id = users.id 
                       WHERE blogs.slug = :identifier";
            }
            
            $this->db->query($sql);
            $this->db->bind(':identifier', $identifier);
            $blog = $this->db->single();
            
            if (!$blog) {
                $this->sendError('Blog niet gevonden', 404);
            }
            
            // Update view count
            $this->db->query("UPDATE blogs SET views = views + 1 WHERE id = :id");
            $this->db->bind(':id', $blog->id);
            $this->db->execute();
            
            $this->sendResponse([
                'blog' => $this->formatBlogForAPI($blog, true)
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function createBlog() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        $input = $this->getJsonInput();
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $title = trim($input['title'] ?? '');
        $content = trim($input['content'] ?? '');
        $summary = trim($input['summary'] ?? '');
        $image_url = trim($input['image_url'] ?? '');
        $video_url = trim($input['video_url'] ?? '');
        $audio_url = trim($input['audio_url'] ?? '');
        
        // Validatie
        if (empty($title)) {
            $this->sendError('Titel is verplicht', 400);
        }
        
        if (empty($content)) {
            $this->sendError('Content is verplicht', 400);
        }
        
        if (strlen($title) > 255) {
            $this->sendError('Titel mag maximaal 255 karakters bevatten', 400);
        }
        
        try {
            // Generate slug
            $slug = $this->generateSlug($title);
            
            // Check if slug already exists
            $this->db->query("SELECT id FROM blogs WHERE slug = :slug");
            $this->db->bind(':slug', $slug);
            if ($this->db->single()) {
                $slug = $slug . '-' . time();
            }
            
            // Auto-generate summary if not provided
            if (empty($summary)) {
                $summary = substr(strip_tags($content), 0, 200) . '...';
            }
            
            // Create blog
            $this->db->query("INSERT INTO blogs (title, slug, content, summary, image_path, video_url, audio_url, author_id, published_at) 
                             VALUES (:title, :slug, :content, :summary, :image_path, :video_url, :audio_url, :author_id, NOW())");
            
            $this->db->bind(':title', $title);
            $this->db->bind(':slug', $slug);
            $this->db->bind(':content', $content);
            $this->db->bind(':summary', $summary);
            $this->db->bind(':image_path', $image_url);
            $this->db->bind(':video_url', $video_url);
            $this->db->bind(':audio_url', $audio_url);
            $this->db->bind(':author_id', $user->id);
            
            if ($this->db->execute()) {
                $blogId = $this->db->lastInsertId();
                
                // Get the created blog
                $this->db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                                 FROM blogs 
                                 JOIN users ON blogs.author_id = users.id 
                                 WHERE blogs.id = :id");
                $this->db->bind(':id', $blogId);
                $newBlog = $this->db->single();
                
                $this->sendResponse([
                    'message' => 'Blog succesvol aangemaakt',
                    'blog' => $this->formatBlogForAPI($newBlog, true)
                ], 201);
            } else {
                $this->sendError('Fout bij aanmaken van blog', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateBlog($id) {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        // Check if blog exists and user is owner or admin
        $this->db->query("SELECT * FROM blogs WHERE id = :id");
        $this->db->bind(':id', $id);
        $existingBlog = $this->db->single();
        
        if (!$existingBlog) {
            $this->sendError('Blog niet gevonden', 404);
        }
        
        if ($existingBlog->author_id != $user->id && !$user->is_admin) {
            $this->sendError('Geen toestemming om deze blog te bewerken', 403);
        }
        
        $input = $this->getJsonInput();
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $title = trim($input['title'] ?? $existingBlog->title);
        $content = trim($input['content'] ?? $existingBlog->content);
        $summary = trim($input['summary'] ?? $existingBlog->summary);
        $image_url = trim($input['image_url'] ?? $existingBlog->image_path);
        $video_url = trim($input['video_url'] ?? $existingBlog->video_url);
        $audio_url = trim($input['audio_url'] ?? $existingBlog->audio_url);
        
        // Validatie
        if (empty($title)) {
            $this->sendError('Titel is verplicht', 400);
        }
        
        if (empty($content)) {
            $this->sendError('Content is verplicht', 400);
        }
        
        try {
            // Update blog
            $this->db->query("UPDATE blogs 
                             SET title = :title, content = :content, summary = :summary, 
                                 image_path = :image_path, video_url = :video_url, audio_url = :audio_url
                             WHERE id = :id");
            
            $this->db->bind(':title', $title);
            $this->db->bind(':content', $content);
            $this->db->bind(':summary', $summary);
            $this->db->bind(':image_path', $image_url);
            $this->db->bind(':video_url', $video_url);
            $this->db->bind(':audio_url', $audio_url);
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                // Get updated blog
                $this->db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                                 FROM blogs 
                                 JOIN users ON blogs.author_id = users.id 
                                 WHERE blogs.id = :id");
                $this->db->bind(':id', $id);
                $updatedBlog = $this->db->single();
                
                $this->sendResponse([
                    'message' => 'Blog succesvol bijgewerkt',
                    'blog' => $this->formatBlogForAPI($updatedBlog, true)
                ]);
            } else {
                $this->sendError('Fout bij bijwerken van blog', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteBlog($id) {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        // Check if blog exists and user is owner or admin
        $this->db->query("SELECT * FROM blogs WHERE id = :id");
        $this->db->bind(':id', $id);
        $existingBlog = $this->db->single();
        
        if (!$existingBlog) {
            $this->sendError('Blog niet gevonden', 404);
        }
        
        if ($existingBlog->author_id != $user->id && !$user->is_admin) {
            $this->sendError('Geen toestemming om deze blog te verwijderen', 403);
        }
        
        try {
            $this->db->query("DELETE FROM blogs WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                $this->sendResponse([
                    'message' => 'Blog succesvol verwijderd'
                ]);
            } else {
                $this->sendError('Fout bij verwijderen van blog', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function formatBlogForAPI($blog, $includeContent = false) {
        $formatted = [
            'id' => (int)$blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'summary' => $blog->summary,
            'author' => [
                'id' => (int)$blog->author_id,
                'name' => $blog->author_name,
                'profile_photo' => $blog->author_photo
            ],
            'published_at' => $blog->published_at,
            'views' => (int)($blog->views ?? 0),
            'likes' => (int)($blog->likes ?? 0),
            'media' => [
                'image_url' => $blog->image_path ? $this->formatMediaUrl($blog->image_path) : null,
                'video_url' => $blog->video_url ?: null,
                'audio_url' => $blog->audio_url ?: null
            ]
        ];
        
        if ($includeContent) {
            $formatted['content'] = $blog->content;
        }
        
        return $formatted;
    }
    
    private function formatMediaUrl($path) {
        if (empty($path)) return null;
        
        // If already a full URL, return as is
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }
        
        // Convert relative path to full URL
        return URLROOT . '/' . ltrim($path, '/');
    }
    
    private function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    private function getCurrentUserFromToken() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return null;
        }
        
        $token = $matches[1];
        $payload = $this->verifyJWT($token);
        
        if (!$payload) {
            return null;
        }
        
        // Get current user data from database
        try {
            $this->db->query("SELECT id, username, email, is_admin, profile_photo, created_at FROM users WHERE id = :id");
            $this->db->bind(':id', $payload['user_id']);
            return $this->db->single();
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function verifyJWT($token) {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) !== 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $tokenParts;
        
        // Verify signature
        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        // Decode payload
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);
        
        // Check expiration
        if ($payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
    
    private function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
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