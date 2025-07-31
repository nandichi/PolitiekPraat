<?php
/**
 * Script om de blog categories migratie uit te voeren
 * Run dit script om categorieën toe te voegen aan de blogs
 */

// Bepaal het BASE_PATH
define('BASE_PATH', dirname(__DIR__));

// Laad alle benodigde includes
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';

try {
    echo "Blog Categories Migratie wordt uitgevoerd...\n";
    
    // Lees de migratie SQL
    $migrationSQL = file_get_contents(dirname(__DIR__) . '/database/migrations/add_blog_categories.sql');
    
    if (!$migrationSQL) {
        throw new Exception("Kon migratie bestand niet lezen");
    }
    
    // Database connectie
    $db = new Database();
    
    // Split SQL in afzonderlijke queries (op basis van ';' scheidingstekens)
    $queries = array_filter(
        array_map('trim', explode(';', $migrationSQL)), 
        function($query) {
            return !empty($query) && !preg_match('/^\s*--/', $query);
        }
    );
    
    // Voer elke query uit
    foreach ($queries as $query) {
        if (trim($query)) {
            echo "Uitvoeren: " . substr(trim($query), 0, 50) . "...\n";
            $db->query($query);
            $db->execute();
        }
    }
    
    echo "\n✅ Blog Categories Migratie succesvol voltooid!\n";
    echo "\nDe volgende categorieën zijn toegevoegd:\n";
    echo "- Nederlandse Politiek\n";
    echo "- Internationale Politiek\n";
    echo "- Verkiezingen\n";
    echo "- Partijanalyses\n";
    echo "- Democratie & Instituties\n";
    echo "- Politieke Geschiedenis\n";
    echo "- Opinies & Columns\n";
    echo "- Actuele Thema's\n";
    
    echo "\nBestaande blogs zijn automatisch toegewezen aan 'Nederlandse Politiek'.\n";
    echo "Je kunt nu categorieën gebruiken bij het aanmaken van blogs!\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Fout bij migratie: " . $e->getMessage() . "\n";
    echo "Controleer de database configuratie en probeer opnieuw.\n\n";
}
?>