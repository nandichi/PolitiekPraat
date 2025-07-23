<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Voor productie uitschakelen

// Base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

// Start session
session_start();

// Include necessary files
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Alleen POST requests toegestaan'
    ]);
    exit;
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Ongeldige JSON input');
    }
    
    // Validate required fields
    if (!isset($input['poll_id']) || !isset($input['option_id'])) {
        throw new Exception('Poll ID en Option ID zijn vereist');
    }
    
    $poll_id = (int)$input['poll_id'];
    $option_id = (int)$input['option_id'];
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    
    // Generate session ID for anonymous users
    if (!$user_id) {
        if (!isset($_SESSION['poll_session_id'])) {
            $_SESSION['poll_session_id'] = 'anon_' . bin2hex(random_bytes(16)) . '_' . time();
        }
        $session_id = $_SESSION['poll_session_id'];
    } else {
        $session_id = null;
    }
    
    $db = new Database();
    
    // Check if poll exists and is active
    $db->query("SELECT * FROM blog_polls WHERE id = :poll_id AND is_active = 1");
    $db->bind(':poll_id', $poll_id);
    $poll = $db->single();
    
    if (!$poll) {
        throw new Exception('Poll niet gevonden of niet actief');
    }
    
    // Check if option exists for this poll
    $db->query("SELECT * FROM poll_options WHERE id = :option_id AND poll_id = :poll_id");
    $db->bind(':option_id', $option_id);
    $db->bind(':poll_id', $poll_id);
    $option = $db->single();
    
    if (!$option) {
        throw new Exception('Poll optie niet gevonden');
    }
    
    // Check if user/session has already voted
    if ($user_id) {
        $db->query("SELECT id FROM poll_votes WHERE poll_id = :poll_id AND user_id = :user_id");
        $db->bind(':poll_id', $poll_id);
        $db->bind(':user_id', $user_id);
    } else {
        $db->query("SELECT id FROM poll_votes WHERE poll_id = :poll_id AND session_id = :session_id");
        $db->bind(':poll_id', $poll_id);
        $db->bind(':session_id', $session_id);
    }
    
    $existingVote = $db->single();
    
    if ($existingVote) {
        throw new Exception('Je hebt al gestemd op deze poll');
    }
    
    // Record the vote
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $db->query("INSERT INTO poll_votes (poll_id, option_id, user_id, session_id, ip_address, user_agent) 
               VALUES (:poll_id, :option_id, :user_id, :session_id, :ip_address, :user_agent)");
    $db->bind(':poll_id', $poll_id);
    $db->bind(':option_id', $option_id);
    $db->bind(':user_id', $user_id);
    $db->bind(':session_id', $session_id);
    $db->bind(':ip_address', $ip_address);
    $db->bind(':user_agent', $user_agent);
    
    if (!$db->execute()) {
        throw new Exception('Fout bij opslaan van stem');
    }
    
    // Get updated poll results
    $db->query("SELECT po.id, po.option_text, po.option_order,
                       COUNT(pv.id) as vote_count
                FROM poll_options po
                LEFT JOIN poll_votes pv ON po.id = pv.option_id
                WHERE po.poll_id = :poll_id
                GROUP BY po.id, po.option_text, po.option_order
                ORDER BY po.option_order");
    $db->bind(':poll_id', $poll_id);
    $results = $db->resultSet();
    
    // Calculate total votes and percentages
    $total_votes = array_sum(array_column($results, 'vote_count'));
    
    $poll_results = [];
    foreach ($results as $result) {
        $percentage = $total_votes > 0 ? round(($result->vote_count / $total_votes) * 100, 1) : 0;
        $poll_results[] = [
            'id' => $result->id,
            'text' => $result->option_text,
            'votes' => $result->vote_count,
            'percentage' => $percentage
        ];
    }
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Stem succesvol opgeslagen',
        'results' => $poll_results,
        'total_votes' => $total_votes,
        'user_voted' => true,
        'voted_option_id' => $option_id
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 