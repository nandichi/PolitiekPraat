<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/Database.php';

$user_id = $_SESSION['user_id'];

// Maak database verbinding
$db = new Database();
$pdo = $db->getConnection();

// Haal gebruikersgegevens op
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Haal success message op uit sessie als die bestaat
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Verwijder het bericht na gebruik
}

require_once __DIR__ . '/../../views/profile/index.php'; 