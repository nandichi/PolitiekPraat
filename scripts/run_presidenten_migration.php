<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "🇺🇸 Amerikaanse Presidenten Tabel Migratie\n";
echo "=========================================\n\n";

try {
    $db = new Database();
    echo "✅ Database connectie succesvol\n\n";
    
    // Lees het migratie bestand
    $migrationPath = __DIR__ . '/../database/migrations/create_amerikaanse_presidenten_table.sql';
    $migrationContent = file_get_contents($migrationPath);
    
    if ($migrationContent === false) {
        throw new Exception("Kan migratie bestand niet lezen: $migrationPath");
    }
    
    echo "Migratie bestand gelezen\n";
    
    // Voer de migratie uit
    $db->getConnection()->exec($migrationContent);
    
    echo "✅ Amerikaanse presidenten tabel succesvol aangemaakt!\n";
    echo "De tabel is klaar voor het populate script.\n\n";
    
    // Controleer of de tabel bestaat
    $db->query("SHOW TABLES LIKE 'amerikaanse_presidenten'");
    $result = $db->single();
    
    if ($result) {
        echo "✅ Tabel verificatie succesvol - amerikaanse_presidenten bestaat\n";
    } else {
        echo "❌ Tabel verificatie mislukt\n";
    }
    
} catch (Exception $e) {
    echo "❌ Fout bij migratie: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🎉 Migratie voltooid! Je kunt nu het populate script uitvoeren.\n"; 