<?php
// Script voor het uitvoeren van de blog polls migratie
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "🚀 Blog Polls Migratie Starten...\n\n";

try {
    $db = new Database();
    
    // Lees het migratie bestand
    $migrationFile = __DIR__ . '/../database/migrations/add_blog_polls.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migratie bestand niet gevonden: {$migrationFile}");
    }
    
    $sql = file_get_contents($migrationFile);
    
    if (empty($sql)) {
        throw new Exception("Migratie bestand is leeg");
    }
    
    echo "📖 Migratie bestand gelezen: add_blog_polls.sql\n";
    
    // Split SQL statements (eenvoudige split op ; gevolgd door nieuwe regel)
    $statements = array_filter(
        array_map('trim', explode(";\n", $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^(--|#)/', $stmt);
        }
    );
    
    echo "🔧 Gevonden " . count($statements) . " SQL statements\n\n";
    
    // Voer elke statement uit
    $successCount = 0;
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Skip comments en lege regels
        if (preg_match('/^(--|#)/', $statement)) continue;
        
        echo "Uitvoeren statement " . ($index + 1) . ":\n";
        echo "  " . substr($statement, 0, 80) . (strlen($statement) > 80 ? '...' : '') . "\n";
        
        try {
            // Handle DELIMITER statements for triggers
            if (preg_match('/^DELIMITER\s+(.+)$/i', $statement, $matches)) {
                echo "  ✅ Delimiter ingesteld\n";
                continue;
            }
            
            $db->query($statement);
            $db->execute();
            $successCount++;
            echo "  ✅ Succesvol uitgevoerd\n";
        } catch (Exception $e) {
            // Check if it's a "table already exists" error
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "  ⚠️  Tabel bestaat al (wordt overgeslagen)\n";
            } else {
                echo "  ❌ Fout: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
        echo "\n";
    }
    
    echo "✅ Migratie succesvol voltooid!\n";
    echo "📊 {$successCount} statements uitgevoerd\n\n";
    
    // Verificatie: controleer of tabellen bestaan
    echo "🔍 Verificatie van aangemaakte tabellen:\n";
    
    $tables = ['blog_polls', 'poll_options', 'poll_votes'];
    
    foreach ($tables as $table) {
        try {
            $db->query("SHOW TABLES LIKE :table");
            $db->bind(':table', $table);
            $result = $db->single();
            
            if ($result) {
                // Tel records in tabel
                $db->query("SELECT COUNT(*) as count FROM {$table}");
                $count = $db->single();
                echo "  ✅ {$table} - OK ({$count->count} records)\n";
            } else {
                echo "  ❌ {$table} - NIET GEVONDEN\n";
            }
        } catch (Exception $e) {
            echo "  ❌ {$table} - FOUT: " . $e->getMessage() . "\n";
        }
    }
    
    // Check of has_poll kolom is toegevoegd aan blogs tabel
    try {
        $db->query("SHOW COLUMNS FROM blogs LIKE 'has_poll'");
        $column = $db->single();
        if ($column) {
            echo "  ✅ blogs.has_poll kolom - OK\n";
        } else {
            echo "  ❌ blogs.has_poll kolom - NIET GEVONDEN\n";
        }
    } catch (Exception $e) {
        echo "  ❌ blogs.has_poll kolom - FOUT: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Blog Polls functionaliteit is succesvol geïnstalleerd!\n";
    echo "\n📝 Je kunt nu:\n";
    echo "   - Polls toevoegen bij het maken van nieuwe blogs\n";
    echo "   - Bestaande blogs bekijken met poll functionaliteit\n";
    echo "   - Gebruikers kunnen stemmen op polls in blogs\n";
    echo "   - Realtime resultaten bekijken na stemmen\n\n";
    
} catch (Exception $e) {
    echo "\n❌ FOUT tijdens migratie:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "\n🔧 Mogelijke oplossingen:\n";
    echo "   - Controleer database verbinding in includes/config.php\n";
    echo "   - Zorg dat database gebruiker voldoende rechten heeft\n";
    echo "   - Controleer of alle vereiste tabellen bestaan (blogs, users)\n";
    echo "   - Bekijk database logs voor meer details\n\n";
    exit(1);
}
?> 