<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "=== Comment Likes Migratie ===\n";

try {
    $db = new Database();
    
    echo "Stap 1: Voeg is_liked_by_author kolom toe...\n";
    $db->query("ALTER TABLE comments ADD COLUMN is_liked_by_author BOOLEAN DEFAULT FALSE");
    $db->execute();
    echo "✓ is_liked_by_author kolom toegevoegd\n";
    
    echo "Stap 2: Voeg likes_count kolom toe...\n";
    $db->query("ALTER TABLE comments ADD COLUMN likes_count INT DEFAULT 0");
    $db->execute();
    echo "✓ likes_count kolom toegevoegd\n";
    
    echo "Stap 3: Voeg index toe voor betere performance...\n";
    try {
        $db->query("CREATE INDEX idx_comments_liked_by_author ON comments(is_liked_by_author)");
        $db->execute();
        echo "✓ Index toegevoegd\n";
    } catch (Exception $e) {
        echo "Note: Index mogelijk al aanwezig\n";
    }
    
    echo "\n=== Comment Likes Migratie succesvol voltooid! ===\n";
    echo "'Liked by creator' functionaliteit is nu beschikbaar.\n";
    
} catch (Exception $e) {
    echo "FOUT: " . $e->getMessage() . "\n";
    exit(1);
}
?> 