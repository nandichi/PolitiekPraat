<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

if (!isset($params['id'])) {
    header('Location: ' . URLROOT . '/forum');
    exit;
}

$db = new Database();

// Haal reactie op
$db->query("SELECT forum_replies.*, forum_topics.id as topic_id 
           FROM forum_replies 
           JOIN forum_topics ON forum_replies.topic_id = forum_topics.id 
           WHERE forum_replies.id = :id");
$db->bind(':id', $params['id']);
$reply = $db->single();

if (!$reply) {
    header('Location: ' . URLROOT . '/forum');
    exit;
}

// Controleer of gebruiker de eigenaar is of admin
if ($_SESSION['user_id'] != $reply->user_id && !$_SESSION['is_admin']) {
    header('Location: ' . URLROOT . '/forum/topic/' . $reply->topic_id);
    exit;
}

// Verwijder reactie
$db->query("DELETE FROM forum_replies WHERE id = :id");
$db->bind(':id', $params['id']);
$db->execute();

// Update last_activity van het topic naar de laatste reactie
$db->query("UPDATE forum_topics 
           SET last_activity = IFNULL(
               (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = :topic_id),
               created_at
           )
           WHERE id = :topic_id");
$db->bind(':topic_id', $reply->topic_id);
$db->execute();

// Redirect terug naar topic
header('Location: ' . URLROOT . '/forum/topic/' . $reply->topic_id . '#replies');
exit; 