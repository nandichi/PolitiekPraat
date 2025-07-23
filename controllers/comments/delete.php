<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

if (!isset($params['id'])) {
    header('Location: ' . URLROOT . '/blogs');
    exit;
}

$db = new Database();

// Haal comment op
$db->query("SELECT comments.*, blogs.slug as blog_slug,
           CASE 
               WHEN comments.user_id IS NOT NULL THEN 'registered'
               ELSE 'anonymous'
           END as author_type
           FROM comments 
           JOIN blogs ON comments.blog_id = blogs.id 
           WHERE comments.id = :id");
$db->bind(':id', $params['id']);
$comment = $db->single();

if (!$comment) {
    header('Location: ' . URLROOT . '/blogs');
    exit;
}

// Controleer of gebruiker de eigenaar is of admin
// Anonieme comments kunnen alleen door admins worden verwijderd
if ($comment->author_type === 'anonymous') {
    if (!$_SESSION['is_admin']) {
        header('Location: ' . URLROOT . '/blogs/' . $comment->blog_slug);
        exit;
    }
} elseif ($_SESSION['user_id'] != $comment->user_id && !$_SESSION['is_admin']) {
    header('Location: ' . URLROOT . '/blogs/' . $comment->blog_slug);
    exit;
}

// Verwijder comment
$db->query("DELETE FROM comments WHERE id = :id");
$db->bind(':id', $params['id']);
$db->execute();

// Redirect terug naar blog
    header('Location: ' . URLROOT . '/blogs/' . $comment->blog_slug . '#comments');
exit; 