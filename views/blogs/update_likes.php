<?php
declare(strict_types=1);

/**
 * Blog like API endpoint (JSON only).
 * Route: blogs/updateLikes/{id} via BlogsController::updateLikes
 * Direct POST: slug + action (like/unlike)
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/auth_csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Alleen POST-verzoeken zijn toegestaan'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!auth_require_csrf_token_from_post()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Ongeldige CSRF-token'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if (!isset($_POST['slug'], $_POST['action'])) {
        throw new RuntimeException('Ontbrekende parameters slug of action.');
    }

    $slug = trim((string) $_POST['slug']);
    $action = trim((string) $_POST['action']);

    if (!in_array($action, ['like', 'unlike'], true)) {
        throw new RuntimeException('Ongeldige actie.');
    }

    $db = new Database();
    $db->query('SELECT id, likes FROM blogs WHERE slug = :slug');
    $db->bind(':slug', $slug);
    $currentLikes = $db->single();

    if (!$currentLikes) {
        throw new RuntimeException('Blog niet gevonden.');
    }

    $newLikes = (int) $currentLikes->likes;
    $newLikes = $action === 'like' ? max(0, $newLikes + 1) : max(0, $newLikes - 1);

    $db->query('UPDATE blogs SET likes = :likes WHERE slug = :slug');
    $db->bind(':likes', $newLikes);
    $db->bind(':slug', $slug);

    if (!$db->execute()) {
        throw new RuntimeException('Database update mislukt.');
    }

    echo json_encode([
        'success' => true,
        'likes' => $newLikes,
        'action' => $action,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('[update_likes] ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'De like kon niet worden verwerkt',
    ], JSON_UNESCAPED_UNICODE);
}
