<?php
// Script om de poll database tabellen aan te maken
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

try {
    $db = new Database();
    
    echo "Aanmaken van poll tabellen...\n";
    
    // Lees de SQL migratie
    $sqlFile = __DIR__ . '/../database/migrations/add_blog_polls.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL migratie bestand niet gevonden: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split de SQL in individuele statements
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        // Skip lege statements en USE statements
        if (empty($statement) || stripos($statement, 'USE ') === 0) {
            continue;
        }
        
        try {
            $db->directQuery($statement);
            echo "✓ SQL statement uitgevoerd\n";
        } catch (Exception $e) {
            // Als tabel al bestaat, geef waarschuwing maar stop niet
            if (strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), 'already exists') !== false) {
                echo "⚠ Tabel bestaat al, overgeslagen\n";
            } else {
                throw $e;
            }
        }
    }
    
    echo "\n✅ Poll tabellen succesvol aangemaakt!\n";
    echo "\nAangemaakte tabellen:\n";
    echo "- blog_polls (voor poll gegevens)\n";
    echo "- blog_poll_votes (voor stem gegevens)\n";
    
} catch (Exception $e) {
    echo "\n❌ Fout bij aanmaken van poll tabellen: " . $e->getMessage() . "\n";
    exit(1);
}
?> 