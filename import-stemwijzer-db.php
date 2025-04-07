<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includes voor de database configuratie
require_once 'includes/config.php';
require_once 'includes/Database.php';

echo "<h1>Stemwijzer Database Importeren</h1>";

// Lees het SQL-bestand
$sqlFile = 'database/migrations/stemwijzer.sql';

if (!file_exists($sqlFile)) {
    die("<p>❌ SQL bestand niet gevonden: $sqlFile</p>");
}

echo "<p>✅ SQL bestand gevonden: $sqlFile</p>";

// Verbinden met de database
try {
    $database = new Database();
    $db = $database->getConnection();
    echo "<p>✅ Database connectie succesvol</p>";

    // Lees het SQL-bestand in
    $sql = file_get_contents($sqlFile);
    
    // Verwijder de commentaarregels
    $sql = preg_replace('/--.*$/m', '', $sql);
    
    // Split de queries (op basis van ';')
    $queries = explode(';', $sql);
    
    echo "<h2>Import resultaten:</h2>";
    echo "<div style='background-color: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto;'>";
    
    // Voer elke query uit
    $errors = [];
    $success = 0;
    foreach ($queries as $i => $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        try {
            if ($db->exec($query) !== false) {
                echo "<p>✅ Query #" . ($i + 1) . " succesvol uitgevoerd</p>";
                $success++;
            } else {
                echo "<p>⚠️ Query #" . ($i + 1) . " mogelijk niet correct uitgevoerd</p>";
                $errors[] = "Query #" . ($i + 1) . ": Mogelijk niet correct uitgevoerd";
            }
        } catch (PDOException $e) {
            echo "<p>❌ Query #" . ($i + 1) . " fout: " . $e->getMessage() . "</p>";
            $errors[] = "Query #" . ($i + 1) . ": " . $e->getMessage();
        }
    }
    
    echo "</div>";
    
    // Resultaat
    echo "<h2>Samenvatting:</h2>";
    echo "<p>Totaal aantal queries: " . count($queries) . "</p>";
    echo "<p>Aantal succesvolle queries: " . $success . "</p>";
    echo "<p>Aantal fouten: " . count($errors) . "</p>";
    
    if (count($errors) > 0) {
        echo "<h3>Fouten:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    }
    
    echo "<h2>Volgende stap:</h2>";
    echo "<p>Ga naar <a href='check-tables.php'>check-tables.php</a> om te controleren of de tabellen succesvol zijn aangemaakt.</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Database connectie fout: " . $e->getMessage() . "</p>";
} 