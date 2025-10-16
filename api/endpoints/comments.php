<?php
/**
 * Comments API Endpoint
 * Handles blog comments
 */

class CommentsAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $id = $segments[1] ?? null;
        $action = $segments[2] ?? null;
        
        switch ($method) {
            case 'GET':
                $this->getComments();
                break;
                
            case 'POST':
                if ($id && $action === 'like') {
                    $this->likeComment($id);
                } else {
                    $this->createComment();
                }
                break;
                
            case 'PUT':
                if ($id) {
                    $this->updateComment($id);
                } else {
                    sendApiError('Comment ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $this->deleteComment($id);
                } else {
                    sendApiError('Comment ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getComments() {
        try {
            $blog_id = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : null;
            
            if (!$blog_id) {
                sendApiError('blog_id parameter is verplicht', 400);
                return;
            }
            
            $this->db->query("SELECT 
                    comments.*,
                    users.username as author_name,
                    users.profile_photo as author_photo
                FROM comments
                JOIN users ON comments.user_id = users.id
                WHERE comments.blog_id = :blog_id
                ORDER BY comments.created_at DESC");
            
            $this->db->bind(':blog_id', $blog_id);
            $comments = $this->db->resultSet();
            
            sendApiResponse([
                'comments' => $comments,
                'total' => count($comments)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen comments: ' . $e->getMessage(), 500);
        }
    }
    
    private function createComment() {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['blog_id']) || empty($data['content'])) {
                sendApiError('blog_id en content zijn verplicht', 400);
                return;
            }
            
            $this->db->query("SELECT id FROM blogs WHERE id = :id");
            $this->db->bind(':id', $data['blog_id']);
            if (!$this->db->single()) {
                sendApiError('Blog niet gevonden', 404);
                return;
            }
            
            $this->db->query("INSERT INTO comments (blog_id, user_id, content) 
                VALUES (:blog_id, :user_id, :content)");
            
            $this->db->bind(':blog_id', $data['blog_id']);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':content', $data['content']);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Comment succesvol toegevoegd',
                    'comment_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij toevoegen comment', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij toevoegen comment: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateComment($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT user_id FROM comments WHERE id = :id");
            $this->db->bind(':id', $id);
            $comment = $this->db->single();
            
            if (!$comment) {
                sendApiError('Comment niet gevonden', 404);
                return;
            }
            
            if ($comment->user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['content'])) {
                sendApiError('Content is verplicht', 400);
                return;
            }
            
            $this->db->query("UPDATE comments SET content = :content WHERE id = :id");
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Comment succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken comment', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken comment: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteComment($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT user_id FROM comments WHERE id = :id");
            $this->db->bind(':id', $id);
            $comment = $this->db->single();
            
            if (!$comment) {
                sendApiError('Comment niet gevonden', 404);
                return;
            }
            
            if ($comment->user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $this->db->query("DELETE FROM comments WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Comment succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen comment', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen comment: ' . $e->getMessage(), 500);
        }
    }
    
    private function likeComment($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT id FROM comments WHERE id = :id");
            $this->db->bind(':id', $id);
            if (!$this->db->single()) {
                sendApiError('Comment niet gevonden', 404);
                return;
            }
            
            sendApiResponse([
                'message' => 'Like functionaliteit beschikbaar in toekomstige update',
                'comment_id' => $id
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij liken comment: ' . $e->getMessage(), 500);
        }
    }
}

