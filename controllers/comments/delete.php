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
$db->query("SELECT comments.*, blogs.slug as blog_slug 
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
if ($_SESSION['user_id'] != $comment->user_id && !$_SESSION['is_admin']) {
    header('Location: ' . URLROOT . '/blogs/view/' . $comment->blog_slug);
    exit;
}

// Verwijder comment
$db->query("DELETE FROM comments WHERE id = :id");
$db->bind(':id', $params['id']);
$db->execute();

// Redirect terug naar blog
header('Location: ' . URLROOT . '/blogs/view/' . $comment->blog_slug . '#comments');
exit; 