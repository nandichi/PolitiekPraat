<?php
/**
 * Migratiescript voor Auto Likes Systeem
 * Maakt de benodigde tabellen aan en vult deze met initiële data
 */

$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);

require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/Database.php';

echo "=== Auto Likes Migratie ===\n\n";

try {
    $db = new Database();
    
    // Maak auto_likes_config tabel aan
    echo "Aanmaken tabel: auto_likes_config...\n";
    $db->query("
        CREATE TABLE IF NOT EXISTS auto_likes_config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            blog_id INT NOT NULL UNIQUE,
            enabled TINYINT(1) DEFAULT 1,
            min_likes_per_run INT DEFAULT 1,
            max_likes_per_run INT DEFAULT 5,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $db->execute();
    echo "Tabel auto_likes_config aangemaakt.\n";
    
    // Maak auto_likes_log tabel aan
    echo "Aanmaken tabel: auto_likes_log...\n";
    $db->query("
        CREATE TABLE IF NOT EXISTS auto_likes_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            blog_id INT NOT NULL,
            likes_added INT NOT NULL,
            run_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
            INDEX idx_blog_id (blog_id),
            INDEX idx_run_time (run_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $db->execute();
    echo "Tabel auto_likes_log aangemaakt.\n";
    
    // Maak auto_likes_settings tabel aan voor globale instellingen
    echo "Aanmaken tabel: auto_likes_settings...\n";
    $db->query("
        CREATE TABLE IF NOT EXISTS auto_likes_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) NOT NULL UNIQUE,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $db->execute();
    echo "Tabel auto_likes_settings aangemaakt.\n";
    
    // Voeg standaard instellingen toe
    echo "Toevoegen standaard instellingen...\n";
    $defaultSettings = [
        ['global_enabled', '1'],
        ['default_min_likes', '1'],
        ['default_max_likes', '5'],
        ['like_chance_min', '30'],
        ['like_chance_max', '70'],
        ['last_run', '0']
    ];
    
    foreach ($defaultSettings as $setting) {
        $db->query("INSERT IGNORE INTO auto_likes_settings (setting_key, setting_value) VALUES (:key, :value)");
        $db->bind(':key', $setting[0]);
        $db->bind(':value', $setting[1]);
        $db->execute();
    }
    echo "Standaard instellingen toegevoegd.\n";
    
    // Haal alle bestaande blogs op en voeg ze toe aan auto_likes_config
    echo "Toevoegen bestaande blogs aan auto_likes_config...\n";
    $db->query("SELECT id FROM blogs");
    $blogs = $db->resultSet();
    
    $addedCount = 0;
    foreach ($blogs as $blog) {
        $db->query("INSERT IGNORE INTO auto_likes_config (blog_id, enabled, min_likes_per_run, max_likes_per_run) VALUES (:blog_id, 1, 1, 5)");
        $db->bind(':blog_id', $blog->id);
        if ($db->execute()) {
            $addedCount++;
        }
    }
    
    echo "Toegevoegd: {$addedCount} blogs aan auto_likes_config.\n";
    
    echo "\n=== Migratie voltooid! ===\n";
    
} catch (Exception $e) {
    echo "FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
