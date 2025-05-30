<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

$db = new Database();

echo "Starting database cleanup...\n\n";

try {
    // 1. Verwijder alle stemwijzer data
    echo "Deleting all stemwijzer data...\n";
    
    $db->query("DELETE FROM stemwijzer_positions");
    $db->execute();
    echo "✓ Deleted all positions\n";
    
    $db->query("DELETE FROM stemwijzer_questions");
    $db->execute();
    echo "✓ Deleted all questions\n";
    
    $db->query("DELETE FROM stemwijzer_parties");
    $db->execute();
    echo "✓ Deleted all parties\n";
    
    // 2. Reset auto-increment
    $db->query("ALTER TABLE stemwijzer_positions AUTO_INCREMENT = 1");
    $db->execute();
    
    $db->query("ALTER TABLE stemwijzer_questions AUTO_INCREMENT = 1");
    $db->execute();
    
    $db->query("ALTER TABLE stemwijzer_parties AUTO_INCREMENT = 1");
    $db->execute();
    
    echo "✓ Reset auto-increment values\n\n";
    
    echo "Database cleanup completed successfully!\n";
    echo "You can now run the migration script again to import fresh data.\n";
    
} catch (Exception $e) {
    echo "Error during cleanup: " . $e->getMessage() . "\n";
}
?> 