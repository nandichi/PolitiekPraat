<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$comment_id = filter_var($input['comment_id'] ?? 0, FILTER_VALIDATE_INT);
$action = $input['action'] ?? '';

if (!$comment_id || !in_array($action, ['like', 'unlike'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

try {
    $db = new Database();
    
    // Get comment and blog info to verify user is the author
    $db->query("SELECT c.*, b.author_id as blog_author_id 
               FROM comments c 
               JOIN blogs b ON c.blog_id = b.id 
               WHERE c.id = :comment_id");
    $db->bind(':comment_id', $comment_id);
    $comment = $db->single();
    
    if (!$comment) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Comment not found']);
        exit;
    }
    
    // Check if user is the blog author
    if ($_SESSION['user_id'] != $comment->blog_author_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Only blog author can like comments']);
        exit;
    }
    
    // Update like status
    $is_liked = ($action === 'like') ? 1 : 0;
    
    $db->query("UPDATE comments 
               SET is_liked_by_author = :is_liked 
               WHERE id = :comment_id");
    $db->bind(':is_liked', $is_liked);
    $db->bind(':comment_id', $comment_id);
    
    if ($db->execute()) {
        echo json_encode([
            'success' => true,
            'is_liked' => $is_liked,
            'message' => $action === 'like' ? 'Reactie gemarkeerd als leuk gevonden!' : 'Reactie niet meer leuk gevonden'
        ]);
    } else {
        throw new Exception('Database update failed');
    }
    
} catch (Exception $e) {
    error_log("Comment like error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
?> 