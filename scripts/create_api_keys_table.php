<?php
/**
 * Migratiescript voor API Keys Systeem
 * Maakt de api_keys tabel aan voor het authenticeren van blog-publicaties via API-sleutel
 */

$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);

require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/Database.php';

echo "=== API Keys Tabel Migratie ===\n\n";

try {
    $db = new Database();

    echo "Aanmaken tabel: api_keys...\n";
    $db->query("
        CREATE TABLE IF NOT EXISTS api_keys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            key_hash VARCHAR(64) NOT NULL UNIQUE,
            user_id INT NOT NULL DEFAULT 1,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_used_at DATETIME NULL,
            INDEX idx_key_hash (key_hash),
            INDEX idx_user_id (user_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $db->execute();
    echo "Tabel api_keys aangemaakt.\n";

    echo "\n=== Migratie voltooid! ===\n";

} catch (Exception $e) {
    echo "FOUT: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
