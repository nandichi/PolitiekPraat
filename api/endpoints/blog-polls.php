<?php
/**
 * Blog Polls API Endpoint
 * Handles blog polls and voting
 */

class BlogPollsAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $id = $segments[1] ?? null;
        $action = $segments[2] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($id && $action === 'results') {
                    $this->getPollResults($id);
                } elseif ($id) {
                    $this->getPoll($id);
                } else {
                    $this->getPollByBlogId();
                }
                break;
                
            case 'POST':
                if ($id && $action === 'vote') {
                    $this->votePoll($id);
                } else {
                    $this->createPoll();
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getPollByBlogId() {
        try {
            $blog_id = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : null;
            
            if (!$blog_id) {
                sendApiError('blog_id parameter is verplicht', 400);
                return;
            }
            
            $this->db->query("SELECT * FROM blog_polls WHERE blog_id = :blog_id");
            $this->db->bind(':blog_id', $blog_id);
            $poll = $this->db->single();
            
            if (!$poll) {
                sendApiError('Poll niet gevonden', 404);
                return;
            }
            
            $poll->option_a_percentage = $poll->total_votes > 0 
                ? round(($poll->option_a_votes / $poll->total_votes) * 100, 1) 
                : 0;
            $poll->option_b_percentage = $poll->total_votes > 0 
                ? round(($poll->option_b_votes / $poll->total_votes) * 100, 1) 
                : 0;
            
            sendApiResponse(['poll' => $poll]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen poll: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPoll($id) {
        try {
            $this->db->query("SELECT * FROM blog_polls WHERE id = :id");
            $this->db->bind(':id', $id);
            $poll = $this->db->single();
            
            if (!$poll) {
                sendApiError('Poll niet gevonden', 404);
                return;
            }
            
            $poll->option_a_percentage = $poll->total_votes > 0 
                ? round(($poll->option_a_votes / $poll->total_votes) * 100, 1) 
                : 0;
            $poll->option_b_percentage = $poll->total_votes > 0 
                ? round(($poll->option_b_votes / $poll->total_votes) * 100, 1) 
                : 0;
            
            sendApiResponse(['poll' => $poll]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen poll: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPollResults($id) {
        $this->getPoll($id);
    }
    
    private function createPoll() {
        if (!isset($_SESSION['user_id'])) {
            sendApiError('Inloggen vereist', 401);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['blog_id']) || empty($data['question']) || 
                empty($data['option_a']) || empty($data['option_b'])) {
                sendApiError('blog_id, question, option_a en option_b zijn verplicht', 400);
                return;
            }
            
            $this->db->query("SELECT author_id FROM blogs WHERE id = :id");
            $this->db->bind(':id', $data['blog_id']);
            $blog = $this->db->single();
            
            if (!$blog) {
                sendApiError('Blog niet gevonden', 404);
                return;
            }
            
            if ($blog->author_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
                sendApiError('Alleen de blog auteur kan een poll aanmaken', 403);
                return;
            }
            
            $this->db->query("INSERT INTO blog_polls (blog_id, question, option_a, option_b) 
                VALUES (:blog_id, :question, :option_a, :option_b)");
            
            $this->db->bind(':blog_id', $data['blog_id']);
            $this->db->bind(':question', $data['question']);
            $this->db->bind(':option_a', $data['option_a']);
            $this->db->bind(':option_b', $data['option_b']);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Poll succesvol aangemaakt',
                    'poll_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken poll', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken poll: ' . $e->getMessage(), 500);
        }
    }
    
    private function votePoll($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['choice']) || !in_array($data['choice'], ['A', 'B'])) {
                sendApiError('Keuze moet A of B zijn', 400);
                return;
            }
            
            $this->db->query("SELECT id FROM blog_polls WHERE id = :id");
            $this->db->bind(':id', $id);
            if (!$this->db->single()) {
                sendApiError('Poll niet gevonden', 404);
                return;
            }
            
            $column = $data['choice'] === 'A' ? 'option_a_votes' : 'option_b_votes';
            $this->db->query("UPDATE blog_polls SET {$column} = {$column} + 1, total_votes = total_votes + 1 WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Stem succesvol geregistreerd']);
            } else {
                sendApiError('Fout bij registreren stem', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij stemmen: ' . $e->getMessage(), 500);
        }
    }
}

