<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/Database.php';

$user_id = $_SESSION['user_id'];

// Maak database verbinding
$db = new Database();
$pdo = $db->getConnection();

// Haal gebruikersgegevens op
$stmt = $pdo->prepare("SELECT username, email, created_at, bio, profile_photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Haal statistieken op
$stats = array();

// 1. Aantal blogs
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE author_id = ?");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$stats['blogs'] = $result['count'];

// 2. Aantal reacties
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM comments WHERE user_id = ?");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$stats['comments'] = $result['count'];

// 3. Totaal aantal likes ontvangen op blogs
// Controleer eerst of de likes kolom bestaat in de blogs tabel
$likes_column_exists = false;
try {
    $check_column = $pdo->query("SHOW COLUMNS FROM blogs LIKE 'likes'");
    $likes_column_exists = $check_column->rowCount() > 0;
} catch (PDOException $e) {
    // Negeer fouten
}

if ($likes_column_exists) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(likes), 0) as total_likes 
        FROM blogs 
        WHERE author_id = ?
    ");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    $stats['likes_received'] = $result['total_likes'];
} else {
    $stats['likes_received'] = 0;
}

// Bereken activiteitsniveau (percentage)
$total_activity = $stats['blogs'] + $stats['comments'];
$activity_ratio = min(1, $total_activity / 10); // 10 acties is 100%
$stats['activity_level'] = round($activity_ratio * 100) . '%';

// Bereken engagement score (meer gedetailleerd en interessant)
// Blogs tellen zwaarder dan comments, en likes verhogen ook de score
$blog_weight = 10;
$comment_weight = 3;
$like_weight = 0.5;

$engagement_score = ($stats['blogs'] * $blog_weight) + 
                   ($stats['comments'] * $comment_weight) + 
                   ($stats['likes_received'] * $like_weight);

// Bepaal niveau op basis van score
if ($engagement_score >= 100) {
    $stats['engagement_level'] = 'Expert';
    $stats['engagement_percentage'] = '95%';
    $stats['engagement_color'] = 'from-purple-500 to-indigo-600';
} elseif ($engagement_score >= 50) {
    $stats['engagement_level'] = 'Gevorderd';
    $stats['engagement_percentage'] = '75%';
    $stats['engagement_color'] = 'from-blue-500 to-primary';
} elseif ($engagement_score >= 25) {
    $stats['engagement_level'] = 'Actief';
    $stats['engagement_percentage'] = '50%';
    $stats['engagement_color'] = 'from-green-500 to-blue-500';
} elseif ($engagement_score >= 10) {
    $stats['engagement_level'] = 'Regelmatig';
    $stats['engagement_percentage'] = '30%';
    $stats['engagement_color'] = 'from-yellow-400 to-green-500';
} else {
    $stats['engagement_level'] = 'Beginner';
    $stats['engagement_percentage'] = '15%';
    $stats['engagement_color'] = 'from-orange-400 to-yellow-400';
}

// Haal recente activiteit op
$recent_activity = array();

// 1. Recente blogs
try {
    $stmt = $pdo->prepare("
        SELECT 'blog' as type, title, id as blog_id,
              SUBSTRING(COALESCE(summary, content), 1, 100) as description,
              published_at as date
        FROM blogs 
        WHERE author_id = ? 
        ORDER BY published_at DESC 
        LIMIT 3
    ");
    $stmt->execute([$user_id]);
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($blogs)) {
        foreach ($blogs as &$blog) {
            // Voeg ellipsis toe als de beschrijving is ingekort
            if (strlen($blog['description']) >= 100) {
                $blog['description'] .= '...';
            }
            
            // Zorg ervoor dat blog_id beschikbaar is
            if (!isset($blog['blog_id']) && isset($blog['id'])) {
                $blog['blog_id'] = $blog['id'];
            }
        }
        $recent_activity = array_merge($recent_activity, $blogs);
    }
} catch (PDOException $e) {
    // Negeer fouten, ga door met andere activiteiten
    error_log("Error fetching blogs: " . $e->getMessage());
}

// 2. Recente comments
try {
    $stmt = $pdo->prepare("
        SELECT 'comment' as type, 
              CONCAT('Reactie op: ', COALESCE(b.title, 'een blog')) as title,
              SUBSTRING(c.content, 1, 100) as description,
              c.created_at as date,
              c.blog_id
        FROM comments c
        LEFT JOIN blogs b ON c.blog_id = b.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
        LIMIT 3
    ");
    $stmt->execute([$user_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($comments)) {
        foreach ($comments as &$comment) {
            // Voeg ellipsis toe als de beschrijving is ingekort
            if (strlen($comment['description']) >= 100) {
                $comment['description'] .= '...';
            }
            
            // Controleer of blog_id een geldige waarde heeft
            if (empty($comment['blog_id'])) {
                error_log("Comment without valid blog_id found");
            }
        }
        $recent_activity = array_merge($recent_activity, $comments);
    }
} catch (PDOException $e) {
    // Negeer fouten
    error_log("Error fetching comments: " . $e->getMessage());
}

// Sorteer op datum (nieuwste eerst)
if (!empty($recent_activity)) {
    usort($recent_activity, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // Beperk tot maximaal 5 activiteiten
    $recent_activity = array_slice($recent_activity, 0, 5);

    // Format date for display
    foreach ($recent_activity as &$activity) {
        // Ensure blog_id is available and a valid integer
        if ($activity['type'] == 'blog' && isset($activity['blog_id'])) {
            $activity['blog_id'] = (int)$activity['blog_id'];
        } elseif ($activity['type'] == 'comment' && isset($activity['blog_id'])) {
            $activity['blog_id'] = (int)$activity['blog_id'];
        }
        
        $date = new DateTime($activity['date']);
        $now = new DateTime();
        $interval = $date->diff($now);
        
        if ($interval->d == 0) {
            if ($interval->h == 0) {
                $activity['date'] = $interval->i . ' minuten geleden';
            } else {
                $activity['date'] = $interval->h . ' uur geleden';
            }
        } elseif ($interval->d < 7) {
            $activity['date'] = $interval->d . ' dagen geleden';
        } else {
            $activity['date'] = $date->format('d F Y');
        }
    }
    
    // Bereken datum laatste activiteit
    $stats['last_activity'] = !empty($recent_activity) ? $recent_activity[0]['date'] : 'Geen recente activiteit';
}

// Haal success message op uit sessie als die bestaat
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Verwijder het bericht na gebruik
}

// Fix voor blog_id issues in URLs
if (!empty($recent_activity)) {
    foreach ($recent_activity as &$activity) {
        if (!isset($activity['blog_id']) && $activity['type'] == 'blog') {
            // Log error
            error_log("Error: Missing blog_id for blog in recent activity");
        } elseif (!isset($activity['blog_id']) && $activity['type'] == 'comment') {
            // Log error
            error_log("Error: Missing blog_id for comment in recent activity");
        }
    }
}

require_once BASE_PATH . '/views/profile/index.php'; 