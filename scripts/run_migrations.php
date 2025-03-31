<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

// Session wordt al gestart in config.php, dus geen session_start() hier

// Voor lokale development kunnen we een bypass toevoegen
$is_local = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1');
$bypass_auth = isset($_GET['local_dev']) && $_GET['local_dev'] === 'true' && $is_local;

// Check admin rechten of local bypass
if ((!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) && !$bypass_auth) {
    echo "Unauthorized access. Only administrators can run migrations.<br>";
    echo "If you're running this locally for development, try adding ?local_dev=true to the URL.";
    exit;
}

// Initialize database connection
$db = new Database();
$pdo = $db->getConnection();

// Run migrations
$migrations = [];

// Eerst controleren of de bio kolom al bestaat
$column_exists = false;
try {
    $check_column = $pdo->query("SHOW COLUMNS FROM users LIKE 'bio'");
    $column_exists = $check_column->rowCount() > 0;
} catch (PDOException $e) {
    // Negeer fouten bij het controleren
}

// Alleen toevoegen als de kolom nog niet bestaat
if (!$column_exists) {
    $migrations[] = "ALTER TABLE users ADD COLUMN bio TEXT DEFAULT NULL";
}

// Voeg de user_settings tabel toe
$migrations[] = "CREATE TABLE IF NOT EXISTS user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profile_public TINYINT(1) DEFAULT 1,
    show_email TINYINT(1) DEFAULT 0,
    show_activity TINYINT(1) DEFAULT 1,
    data_analytics TINYINT(1) DEFAULT 1,
    notify_comments TINYINT(1) DEFAULT 1,
    notify_replies TINYINT(1) DEFAULT 1,
    notify_newsletter TINYINT(1) DEFAULT 1,
    notify_site_comments TINYINT(1) DEFAULT 1,
    notify_site_likes TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Execute migrations
$result = '<h1>Migration Results</h1>';
if (empty($migrations)) {
    $result .= '<p style="color: blue;">ℹ️ INFO: Geen nieuwe migraties om uit te voeren.</p>';
} else {
    foreach ($migrations as $migration) {
        try {
            $pdo->exec($migration);
            $result .= '<p style="color: green;">✅ SUCCESS: ' . substr($migration, 0, 50) . '...</p>';
        } catch (PDOException $e) {
            $result .= '<p style="color: red;">❌ ERROR: ' . $e->getMessage() . '</p>';
        }
    }
}

// Output results
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migrations</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #1a365d;
            margin-bottom: 1.5rem;
        }
        p {
            margin: 0.5rem 0;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        p[style*="green"] {
            background-color: #f0fff4;
            border: 1px solid #9ae6b4;
        }
        p[style*="red"] {
            background-color: #fff5f5;
            border: 1px solid #feb2b2;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.5rem 1rem;
            background-color: #1a365d;
            color: white;
            text-decoration: none;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    <?php echo $result; ?>
    <a href="/" class="back-link">Terug naar Home</a>
</body>
</html> 