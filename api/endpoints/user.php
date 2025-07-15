<?php

class UserAPI {
    private $db;
    private $secretKey;
    
    public function __construct() {
        $this->db = new Database();
        $this->secretKey = 'PolitiekPraat_JWT_Secret_2024_Secure_Key_' . (defined('DB_NAME') ? DB_NAME : 'default');
    }
    
    public function handle($method, $segments) {
        $action = isset($segments[1]) ? $segments[1] : 'profile';
        
        switch ($method) {
            case 'GET':
                switch ($action) {
                    case 'profile':
                        $this->getUserProfile();
                        break;
                    case 'stats':
                        $this->getUserStats();
                        break;
                    case 'blogs':
                        $this->getUserBlogs();
                        break;
                    default:
                        $this->sendError('Onbekende gebruiker actie', 400);
                }
                break;
                
            case 'PUT':
                switch ($action) {
                    case 'profile':
                        $this->updateUserProfile();
                        break;
                    case 'password':
                        $this->updatePassword();
                        break;
                    default:
                        $this->sendError('Onbekende update actie', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }
    
    private function getUserProfile() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        try {
            // Haal uitgebreide gebruikersgegevens op
            $this->db->query("
                SELECT id, username, email, bio, profile_photo, created_at,
                       (SELECT COUNT(*) FROM blogs WHERE author_id = users.id) as blog_count,
                       (SELECT COUNT(*) FROM comments WHERE user_id = users.id) as comment_count
                FROM users 
                WHERE id = :id
            ");
            $this->db->bind(':id', $user->id);
            $userProfile = $this->db->single();
            
            if (!$userProfile) {
                $this->sendError('Gebruiker niet gevonden', 404);
            }
            
            $this->sendResponse([
                'user' => [
                    'id' => (int)$userProfile->id,
                    'username' => $userProfile->username,
                    'email' => $userProfile->email,
                    'bio' => $userProfile->bio ?: '',
                    'profile_photo' => $userProfile->profile_photo ? $this->formatProfilePhotoUrl($userProfile->profile_photo) : null,
                    'member_since' => $userProfile->created_at,
                    'is_admin' => (bool)$user->is_admin,
                    'stats' => [
                        'blogs_written' => (int)$userProfile->blog_count,
                        'comments_made' => (int)$userProfile->comment_count
                    ]
                ]
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function getUserStats() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        try {
            // Gedetailleerde statistieken
            $stats = [];
            
            // Blog statistieken
            $this->db->query("
                SELECT 
                    COUNT(*) as total_blogs,
                    COALESCE(SUM(views), 0) as total_views,
                    COALESCE(SUM(likes), 0) as total_likes
                FROM blogs 
                WHERE author_id = :user_id
            ");
            $this->db->bind(':user_id', $user->id);
            $blogStats = $this->db->single();
            
            // Recente blog activiteit (laatste 30 dagen)
            $this->db->query("
                SELECT COUNT(*) as recent_blogs
                FROM blogs 
                WHERE author_id = :user_id 
                AND published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $this->db->bind(':user_id', $user->id);
            $recentBlogStats = $this->db->single();
            
            // Comment statistieken
            $this->db->query("
                SELECT COUNT(*) as total_comments
                FROM comments 
                WHERE user_id = :user_id
            ");
            $this->db->bind(':user_id', $user->id);
            $commentStats = $this->db->single();
            
            // Populairste blog
            $this->db->query("
                SELECT title, slug, views, likes
                FROM blogs 
                WHERE author_id = :user_id 
                ORDER BY views DESC 
                LIMIT 1
            ");
            $this->db->bind(':user_id', $user->id);
            $popularBlog = $this->db->single();
            
            $stats = [
                'blogs' => [
                    'total' => (int)$blogStats->total_blogs,
                    'total_views' => (int)$blogStats->total_views,
                    'total_likes' => (int)$blogStats->total_likes,
                    'recent_blogs' => (int)$recentBlogStats->recent_blogs,
                    'avg_views_per_blog' => $blogStats->total_blogs > 0 ? round($blogStats->total_views / $blogStats->total_blogs, 1) : 0
                ],
                'engagement' => [
                    'total_comments' => (int)$commentStats->total_comments,
                    'activity_score' => $this->calculateActivityScore($blogStats, $commentStats)
                ],
                'popular_blog' => $popularBlog ? [
                    'title' => $popularBlog->title,
                    'slug' => $popularBlog->slug,
                    'views' => (int)$popularBlog->views,
                    'likes' => (int)$popularBlog->likes
                ] : null
            ];
            
            $this->sendResponse(['stats' => $stats]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function getUserBlogs() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(20, intval($_GET['limit']))) : 10;
            $offset = ($page - 1) * $limit;
            
            // Haal gebruiker blogs op
            $this->db->query("
                SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                WHERE blogs.author_id = :user_id
                ORDER BY blogs.published_at DESC 
                LIMIT :limit OFFSET :offset
            ");
            $this->db->bind(':user_id', $user->id);
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            $blogs = $this->db->resultSet();
            
            // Haal totaal aantal blogs
            $this->db->query("SELECT COUNT(*) as total FROM blogs WHERE author_id = :user_id");
            $this->db->bind(':user_id', $user->id);
            $totalResult = $this->db->single();
            $total = $totalResult->total;
            
            // Format blogs
            $formattedBlogs = [];
            foreach ($blogs as $blog) {
                $formattedBlogs[] = [
                    'id' => (int)$blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'summary' => $blog->summary,
                    'published_at' => $blog->published_at,
                    'views' => (int)($blog->views ?? 0),
                    'likes' => (int)($blog->likes ?? 0),
                    'image_url' => $blog->image_path ? $this->formatMediaUrl($blog->image_path) : null
                ];
            }
            
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
    
    private function updateUserProfile() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        $input = $this->getJsonInput();
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $username = trim($input['username'] ?? $user->username);
        $email = trim($input['email'] ?? $user->email);
        $bio = trim($input['bio'] ?? '');
        
        // Validatie
        if (empty($username)) {
            $this->sendError('Gebruikersnaam is verplicht', 400);
        }
        
        if (empty($email)) {
            $this->sendError('Email is verplicht', 400);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendError('Ongeldig email formaat', 400);
        }
        
        if (strlen($username) < 3) {
            $this->sendError('Gebruikersnaam moet minimaal 3 karakters bevatten', 400);
        }
        
        try {
            // Controleer of nieuwe username/email al in gebruik zijn (door andere gebruikers)
            if ($username !== $user->username) {
                $this->db->query("SELECT id FROM users WHERE username = :username AND id != :user_id");
                $this->db->bind(':username', $username);
                $this->db->bind(':user_id', $user->id);
                if ($this->db->single()) {
                    $this->sendError('Deze gebruikersnaam is al in gebruik', 409);
                }
            }
            
            if ($email !== $user->email) {
                $this->db->query("SELECT id FROM users WHERE email = :email AND id != :user_id");
                $this->db->bind(':email', $email);
                $this->db->bind(':user_id', $user->id);
                if ($this->db->single()) {
                    $this->sendError('Dit email adres is al in gebruik', 409);
                }
            }
            
            // Update profiel
            $this->db->query("
                UPDATE users 
                SET username = :username, email = :email, bio = :bio
                WHERE id = :id
            ");
            $this->db->bind(':username', $username);
            $this->db->bind(':email', $email);
            $this->db->bind(':bio', $bio);
            $this->db->bind(':id', $user->id);
            
            if ($this->db->execute()) {
                // Haal bijgewerkte gebruikersgegevens op
                $this->db->query("SELECT id, username, email, bio, profile_photo, created_at, is_admin FROM users WHERE id = :id");
                $this->db->bind(':id', $user->id);
                $updatedUser = $this->db->single();
                
                $this->sendResponse([
                    'message' => 'Profiel succesvol bijgewerkt',
                    'user' => [
                        'id' => (int)$updatedUser->id,
                        'username' => $updatedUser->username,
                        'email' => $updatedUser->email,
                        'bio' => $updatedUser->bio ?: '',
                        'profile_photo' => $updatedUser->profile_photo ? $this->formatProfilePhotoUrl($updatedUser->profile_photo) : null,
                        'member_since' => $updatedUser->created_at,
                        'is_admin' => (bool)$updatedUser->is_admin
                    ]
                ]);
            } else {
                $this->sendError('Fout bij bijwerken van profiel', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function updatePassword() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Authenticatie vereist', 401);
        }
        
        $input = $this->getJsonInput();
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $currentPassword = $input['current_password'] ?? '';
        $newPassword = $input['new_password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';
        
        // Validatie
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->sendError('Alle velden zijn verplicht', 400);
        }
        
        if (strlen($newPassword) < 6) {
            $this->sendError('Nieuw wachtwoord moet minimaal 6 karakters bevatten', 400);
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->sendError('Nieuwe wachtwoorden komen niet overeen', 400);
        }
        
        try {
            // Verificeer huidig wachtwoord
            $this->db->query("SELECT password FROM users WHERE id = :id");
            $this->db->bind(':id', $user->id);
            $userPassword = $this->db->single();
            
            if (!$userPassword || !password_verify($currentPassword, $userPassword->password)) {
                $this->sendError('Huidig wachtwoord is onjuist', 401);
            }
            
            // Update wachtwoord
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->db->query("UPDATE users SET password = :password WHERE id = :id");
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':id', $user->id);
            
            if ($this->db->execute()) {
                $this->sendResponse([
                    'message' => 'Wachtwoord succesvol bijgewerkt'
                ]);
            } else {
                $this->sendError('Fout bij bijwerken van wachtwoord', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function calculateActivityScore($blogStats, $commentStats) {
        $blogScore = $blogStats->total_blogs * 10;
        $viewScore = $blogStats->total_views * 1;
        $likeScore = $blogStats->total_likes * 5;
        $commentScore = $commentStats->total_comments * 3;
        
        $totalScore = $blogScore + $viewScore + $likeScore + $commentScore;
        
        // Bereken level (0-100)
        if ($totalScore < 50) return 'Beginner';
        if ($totalScore < 200) return 'Actief';
        if ($totalScore < 500) return 'Expert';
        if ($totalScore < 1000) return 'Veteraan';
        return 'Legende';
    }
    
    private function formatProfilePhotoUrl($path) {
        if (empty($path)) return null;
        
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }
        
        return URLROOT . '/' . ltrim($path, '/');
    }
    
    private function formatMediaUrl($path) {
        if (empty($path)) return null;
        
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }
        
        return URLROOT . '/' . ltrim($path, '/');
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
        
        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);
        
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