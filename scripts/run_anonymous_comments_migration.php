<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "=== Anonieme Comments Migratie ===\n";

try {
    $db = new Database();
    
    echo "Stap 1: Voeg anonymous_name kolom toe...\n";
    $db->query("ALTER TABLE comments ADD COLUMN anonymous_name VARCHAR(100) NULL");
    $db->execute();
    echo "✓ anonymous_name kolom toegevoegd\n";
    
    echo "Stap 2: Maak user_id nullable...\n";
    $db->query("ALTER TABLE comments MODIFY COLUMN user_id INT NULL");
    $db->execute();
    echo "✓ user_id is nu nullable\n";
    
    echo "Stap 3: Update foreign key constraint...\n";
    // Eerst de bestaande constraint verwijderen
    try {
        $db->query("ALTER TABLE comments DROP FOREIGN KEY comments_ibfk_2");
        $db->execute();
        echo "✓ Oude foreign key constraint verwijderd\n";
    } catch (Exception $e) {
        echo "Note: Oude constraint niet gevonden (mogelijk al verwijderd)\n";
    }
    
    // Nieuwe constraint toevoegen
    $db->query("ALTER TABLE comments ADD CONSTRAINT comments_user_fk 
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
    $db->execute();
    echo "✓ Nieuwe foreign key constraint toegevoegd\n";
    
    echo "\n=== Migratie succesvol voltooid! ===\n";
    echo "Anonieme comments zijn nu mogelijk.\n";
    
} catch (Exception $e) {
    echo "FOUT: " . $e->getMessage() . "\n";
    exit(1);
}
?> 