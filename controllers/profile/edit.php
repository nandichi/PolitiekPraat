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
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Profile photo processing
    $profile_photo = null;
    $photo_updated = false;
    
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['profile_photo']['type'];
        $file_size = $_FILES['profile_photo']['size'];
        $max_size = 5 * 1024 * 1024; // 5MB max size
        
        if (!in_array($file_type, $allowed_types)) {
            $error = "Alleen JPG, PNG, GIF of WEBP afbeeldingen zijn toegestaan.";
        } elseif ($file_size > $max_size) {
            $error = "De afbeelding mag niet groter zijn dan 5MB.";
        } else {
            // Create upload directory if it doesn't exist
            $upload_dir = BASE_PATH . '/public/uploads/profile_photos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $user_id . '_' . uniqid() . '.' . $file_extension;
            $destination = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination)) {
                $profile_photo = 'uploads/profile_photos/' . $filename;
                $photo_updated = true;
                
                // Delete old photo if exists
                $stmt = $pdo->prepare("SELECT profile_photo FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $old_photo = $stmt->fetchColumn();
                
                if ($old_photo && file_exists(BASE_PATH . '/public/' . $old_photo)) {
                    unlink(BASE_PATH . '/public/' . $old_photo);
                }
            } else {
                $error = "Er is een fout opgetreden bij het uploaden van de afbeelding.";
            }
        }
    } elseif (isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1') {
        // Remove existing photo
        $stmt = $pdo->prepare("SELECT profile_photo FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $old_photo = $stmt->fetchColumn();
        
        if ($old_photo && file_exists(BASE_PATH . '/public/' . $old_photo)) {
            unlink(BASE_PATH . '/public/' . $old_photo);
        }
        
        $profile_photo = null;
        $photo_updated = true;
    }
    
    // Privacy instellingen
    $profile_public = isset($_POST['profile_public']) ? 1 : 0;
    $show_email = isset($_POST['show_email']) ? 1 : 0;
    $show_activity = isset($_POST['show_activity']) ? 1 : 0;
    $data_analytics = isset($_POST['data_analytics']) ? 1 : 0;
    
    // Notificatie instellingen
    $notify_comments = isset($_POST['notify_comments']) ? 1 : 0;
    $notify_replies = isset($_POST['notify_replies']) ? 1 : 0;
    $notify_newsletter = isset($_POST['notify_newsletter']) ? 1 : 0;
    $notify_site_comments = isset($_POST['notify_site_comments']) ? 1 : 0;
    $notify_site_likes = isset($_POST['notify_site_likes']) ? 1 : 0;

    // Validatie
    if (empty($username) || empty($email)) {
        $error = "Gebruikersnaam en e-mail zijn verplicht.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        try {
            // Begin een transactie
            $pdo->beginTransaction();
            
            // Update basis gebruikersgegevens
            $sql = "UPDATE users SET username = ?, email = ?, bio = ?";
            $params = [$username, $email, $bio];

            // Alleen wachtwoord updaten als er een nieuw wachtwoord is opgegeven
            if (!empty($new_password)) {
                $sql .= ", password = ?";
                $params[] = password_hash($new_password, PASSWORD_DEFAULT);
            }
            
            // Update profile photo if changed
            if ($photo_updated) {
                $sql .= ", profile_photo = ?";
                $params[] = $profile_photo;
            }

            $sql .= " WHERE id = ?";
            $params[] = $user_id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            // Controleer of de user_settings tabel al een rij heeft voor deze gebruiker
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_settings WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $settingsExist = $stmt->fetchColumn() > 0;
            
            if ($settingsExist) {
                // Update bestaande instellingen
                $stmt = $pdo->prepare("
                    UPDATE user_settings SET 
                        profile_public = ?, 
                        show_email = ?, 
                        show_activity = ?,
                        data_analytics = ?,
                        notify_comments = ?,
                        notify_replies = ?,
                        notify_newsletter = ?,
                        notify_site_comments = ?,
                        notify_site_likes = ?
                    WHERE user_id = ?
                ");
                $stmt->execute([
                    $profile_public, 
                    $show_email, 
                    $show_activity, 
                    $data_analytics,
                    $notify_comments,
                    $notify_replies,
                    $notify_newsletter,
                    $notify_site_comments,
                    $notify_site_likes,
                    $user_id
                ]);
            } else {
                // Voeg een nieuwe rij toe in user_settings (alleen als de tabel bestaat)
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO user_settings (
                            user_id, profile_public, show_email, show_activity, data_analytics,
                            notify_comments, notify_replies, notify_newsletter, notify_site_comments, notify_site_likes
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $user_id, 
                        $profile_public, 
                        $show_email, 
                        $show_activity, 
                        $data_analytics,
                        $notify_comments,
                        $notify_replies,
                        $notify_newsletter,
                        $notify_site_comments,
                        $notify_site_likes
                    ]);
                } catch (PDOException $e) {
                    // Als de tabel niet bestaat, negeer de error en ga door
                    // (de tabel wordt later aangemaakt als dat nodig is)
                }
            }
            
            // Commit de transactie
            $pdo->commit();

            // Update sessie gebruikersnaam
            $_SESSION['username'] = $username;
            
            // Update session profile photo if changed
            if ($photo_updated) {
                $_SESSION['profile_photo'] = $profile_photo;
            }
            
            // Sla succesbericht op in sessie
            $_SESSION['success_message'] = "Profiel succesvol bijgewerkt!";
            
            // Redirect naar profielpagina
            header('Location: /profile');
            exit();
        } catch (PDOException $e) {
            // Rollback bij een error
            $pdo->rollBack();
            $error = "Er is een fout opgetreden bij het bijwerken van je profiel: " . $e->getMessage();
        }
    }
}

// Huidige gebruikersgegevens ophalen
$stmt = $pdo->prepare("
    SELECT u.username, u.email, u.bio, u.profile_photo,
           COALESCE(s.profile_public, 1) as profile_public,
           COALESCE(s.show_email, 0) as show_email,
           COALESCE(s.show_activity, 1) as show_activity,
           COALESCE(s.data_analytics, 1) as data_analytics,
           COALESCE(s.notify_comments, 1) as notify_comments,
           COALESCE(s.notify_replies, 1) as notify_replies,
           COALESCE(s.notify_newsletter, 1) as notify_newsletter,
           COALESCE(s.notify_site_comments, 1) as notify_site_comments,
           COALESCE(s.notify_site_likes, 1) as notify_site_likes
    FROM users u
    LEFT JOIN user_settings s ON u.id = s.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

require_once BASE_PATH . '/views/profile/edit.php'; 