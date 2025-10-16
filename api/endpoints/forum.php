<?php
/**
 * Forum API Endpoint
 * Handles forum topics and replies
 */

class ForumAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $resource = $segments[1] ?? null;
        $id = $segments[2] ?? null;
        $action = $segments[3] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($resource === 'topics' && $id) {
                    $this->getTopic($id);
                } elseif ($resource === 'topics') {
                    $this->getAllTopics();
                } else {
                    sendApiError('Ongeldige route', 404);
                }
                break;
                
            case 'POST':
                if ($resource === 'topics' && $id && $action === 'replies') {
                    $this->createReply($id);
                } elseif ($resource === 'topics') {
                    $this->createTopic();
                } else {
                    sendApiError('Ongeldige route', 404);
                }
                break;
                
            case 'PUT':
                if ($resource === 'topics' && $id) {
                    $this->updateTopic($id);
                } elseif ($resource === 'replies' && $id) {
                    $this->updateReply($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($resource === 'topics' && $id) {
                    $this->deleteTopic($id);
                } elseif ($resource === 'replies' && $id) {
                    $this->deleteReply($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllTopics() {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 20;
            $offset = ($page - 1) * $limit;
            
            $this->db->query("SELECT COUNT(*) as total FROM forum_topics");
            $total = $this->db->single()->total;
            
            $this->db->query("SELECT 
                    forum_topics.*,
                    users.username as author_name,
                    (SELECT COUNT(*) FROM forum_replies WHERE topic_id = forum_topics.id) as reply_count
                FROM forum_topics
                JOIN users ON forum_topics.author_id = users.id
                ORDER BY forum_topics.last_activity DESC
                LIMIT :limit OFFSET :offset");
            
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            
            $topics = $this->db->resultSet();
            
            foreach ($topics as &$topic) {
                $topic->reply_count = intval($topic->reply_count);
                $topic->views = intval($topic->views);
            }
            
            sendApiResponse([
                'topics' => $topics,
                'pagination' => [
                    'current_page' => $page,
                    'limit' => $limit,
                    'total' => intval($total),
                    'total_pages' => ceil($total / $limit),
                    'has_next' => $page < ceil($total / $limit),
                    'has_prev' => $page > 1
                ]
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen topics: ' . $e->getMessage(), 500);
        }
    }
    
    private function getTopic($id) {
        try {
            $this->db->query("SELECT 
                    forum_topics.*,
                    users.username as author_name
                FROM forum_topics
                JOIN users ON forum_topics.author_id = users.id
                WHERE forum_topics.id = :id");
            
            $this->db->bind(':id', $id);
            $topic = $this->db->single();
            
            if (!$topic) {
                sendApiError('Topic niet gevonden', 404);
            }
            
            $this->db->query("UPDATE forum_topics SET views = views + 1 WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            $this->db->query("SELECT 
                    forum_replies.*,
                    users.username as author_name
                FROM forum_replies
                JOIN users ON forum_replies.user_id = users.id
                WHERE forum_replies.topic_id = :topic_id
                ORDER BY forum_replies.created_at ASC");
            
            $this->db->bind(':topic_id', $id);
            $replies = $this->db->resultSet();
            
            sendApiResponse([
                'topic' => $topic,
                'replies' => $replies,
                'reply_count' => count($replies)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen topic: ' . $e->getMessage(), 500);
        }
    }
    
    private function createTopic() {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['title']) || empty($data['content'])) {
                sendApiError('Titel en content zijn verplicht', 400);
                return;
            }
            
            $this->db->query("INSERT INTO forum_topics (title, content, author_id) 
                VALUES (:title, :content, :author_id)");
            
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':author_id', $_SESSION['user_id']);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Topic succesvol aangemaakt',
                    'topic_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken topic', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken topic: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateTopic($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT author_id FROM forum_topics WHERE id = :id");
            $this->db->bind(':id', $id);
            $topic = $this->db->single();
            
            if (!$topic) {
                sendApiError('Topic niet gevonden', 404);
                return;
            }
            
            if ($topic->author_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $fields = [];
            if (isset($data['title'])) $fields[] = 'title = :title';
            if (isset($data['content'])) $fields[] = 'content = :content';
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE forum_topics SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($query);
            
            if (isset($data['title'])) $this->db->bind(':title', $data['title']);
            if (isset($data['content'])) $this->db->bind(':content', $data['content']);
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Topic succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken topic', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken topic: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteTopic($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT author_id FROM forum_topics WHERE id = :id");
            $this->db->bind(':id', $id);
            $topic = $this->db->single();
            
            if (!$topic) {
                sendApiError('Topic niet gevonden', 404);
                return;
            }
            
            if ($topic->author_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $this->db->query("DELETE FROM forum_topics WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Topic succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen topic', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen topic: ' . $e->getMessage(), 500);
        }
    }
    
    private function createReply($topicId) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT id FROM forum_topics WHERE id = :id");
            $this->db->bind(':id', $topicId);
            if (!$this->db->single()) {
                sendApiError('Topic niet gevonden', 404);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['content'])) {
                sendApiError('Content is verplicht', 400);
                return;
            }
            
            $this->db->query("INSERT INTO forum_replies (topic_id, user_id, content) 
                VALUES (:topic_id, :user_id, :content)");
            
            $this->db->bind(':topic_id', $topicId);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':content', $data['content']);
            
            if ($this->db->execute()) {
                $this->db->query("UPDATE forum_topics SET last_activity = CURRENT_TIMESTAMP WHERE id = :id");
                $this->db->bind(':id', $topicId);
                $this->db->execute();
                
                sendApiResponse([
                    'message' => 'Reply succesvol toegevoegd',
                    'reply_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij toevoegen reply', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij toevoegen reply: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateReply($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT user_id FROM forum_replies WHERE id = :id");
            $this->db->bind(':id', $id);
            $reply = $this->db->single();
            
            if (!$reply) {
                sendApiError('Reply niet gevonden', 404);
                return;
            }
            
            if ($reply->user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['content'])) {
                sendApiError('Content is verplicht', 400);
                return;
            }
            
            $this->db->query("UPDATE forum_replies SET content = :content WHERE id = :id");
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Reply succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken reply', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken reply: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteReply($id) {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $this->db->query("SELECT user_id FROM forum_replies WHERE id = :id");
            $this->db->bind(':id', $id);
            $reply = $this->db->single();
            
            if (!$reply) {
                sendApiError('Reply niet gevonden', 404);
                return;
            }
            
            if ($reply->user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Geen toegang', 403);
                return;
            }
            
            $this->db->query("DELETE FROM forum_replies WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Reply succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen reply', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen reply: ' . $e->getMessage(), 500);
        }
    }
}

