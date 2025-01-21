<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/Database.php';

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Maak database verbinding
$db = new Database();
$pdo = $db->getConnection();

// Als het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validatie
    if (empty($username) || empty($email)) {
        $error = "Gebruikersnaam en e-mail zijn verplicht.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        try {
            $sql = "UPDATE users SET username = ?, email = ?";
            $params = [$username, $email];

            // Alleen wachtwoord updaten als er een nieuw wachtwoord is opgegeven
            if (!empty($new_password)) {
                $sql .= ", password = ?";
                $params[] = password_hash($new_password, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id = ?";
            $params[] = $user_id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Update sessie gebruikersnaam
            $_SESSION['username'] = $username;
            
            // Sla succesbericht op in sessie
            $_SESSION['success_message'] = "Profiel succesvol bijgewerkt!";
            
            // Redirect naar profielpagina
            header('Location: /profile');
            exit();
        } catch (PDOException $e) {
            $error = "Er is een fout opgetreden bij het bijwerken van je profiel.";
        }
    }
}

// Huidige gebruikersgegevens ophalen
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

require_once __DIR__ . '/../../views/profile/edit.php'; 