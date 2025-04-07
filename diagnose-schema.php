<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includes voor de database configuratie
require_once 'includes/config.php';
require_once 'includes/Database.php';

// Verwachte tabelstructuur
$expectedStructure = [
    'parties' => [
        'id' => ['expected' => 'int', 'found' => null], 
        'name' => ['expected' => 'varchar', 'found' => null],
        'logo_url' => ['expected' => 'varchar', 'found' => null]
    ],
    'questions' => [
        'id' => ['expected' => 'int', 'found' => null], 
        'title' => ['expected' => 'varchar', 'found' => null],
        'description' => ['expected' => 'text', 'found' => null],
        'context' => ['expected' => 'text', 'found' => null],
        'left_view' => ['expected' => 'text', 'found' => null],
        'right_view' => ['expected' => 'text', 'found' => null]
    ],
    'positions' => [
        'id' => ['expected' => 'int', 'found' => null], 
        'question_id' => ['expected' => 'int', 'found' => null],
        'party_id' => ['expected' => 'int', 'found' => null],
        'position' => ['expected' => 'enum', 'found' => null],
        'explanation' => ['expected' => 'text', 'found' => null]
    ]
];

// Oude structuur (voor het geval dat)
$oldStructure = [
    'parties' => [
        'party_id' => ['expected' => 'int', 'found' => null], 
        'party_name' => ['expected' => 'varchar', 'found' => null],
        'party_logo' => ['expected' => 'varchar', 'found' => null]
    ],
    'questions' => [
        'question_id' => ['expected' => 'int', 'found' => null], 
        'title' => ['expected' => 'varchar', 'found' => null],
        'description' => ['expected' => 'text', 'found' => null],
        'context' => ['expected' => 'text', 'found' => null],
        'left_view' => ['expected' => 'text', 'found' => null],
        'right_view' => ['expected' => 'text', 'found' => null]
    ],
    'positions' => [
        'position_id' => ['expected' => 'int', 'found' => null], 
        'question_id' => ['expected' => 'int', 'found' => null],
        'party_id' => ['expected' => 'int', 'found' => null],
        'stance' => ['expected' => 'enum', 'found' => null],
        'explanation' => ['expected' => 'text', 'found' => null]
    ]
];

echo "<!DOCTYPE html>
<html lang='nl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Schema Diagnose</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        tr:hover { background-color: #f5f5f5; }
        .success { color: #28a745; }
        .warning { color: #ffc107; }
        .error { color: #dc3545; }
        .cta { display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .cta:hover { background-color: #0069d9; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Database Schema Diagnose</h1>";

try {
    $database = new Database();
    $db = $database->getConnection();
    echo "<p class='success'>✅ Database connectie succesvol</p>";

    // Controleer tabellen en kolommen
    $tables = ['parties', 'questions', 'positions'];
    $tableStructures = [];
    $usesOldSchema = false;
    $usesNewSchema = false;
    
    foreach ($tables as $table) {
        try {
            // Controleer of de tabel bestaat
            $stmt = $db->prepare("SHOW TABLES LIKE '$table'");
            $stmt->execute();
            $tableExists = $stmt->rowCount() > 0;
            
            if ($tableExists) {
                // Haal kolomdetails op
                $stmt = $db->prepare("DESCRIBE $table");
                $stmt->execute();
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $tableStructures[$table] = $columns;
                
                // Controleer de structuur
                $columnNames = array_column($columns, 'Field');
                
                // Check voor oude schema (party_id etc.)
                $oldCount = 0;
                foreach ($oldStructure[$table] as $colName => $colDef) {
                    if (in_array($colName, $columnNames)) {
                        $oldCount++;
                    }
                }
                
                // Check voor nieuwe schema (id etc.)
                $newCount = 0;
                foreach ($expectedStructure[$table] as $colName => $colDef) {
                    if (in_array($colName, $columnNames)) {
                        $newCount++;
                    }
                }
                
                if ($oldCount > $newCount) {
                    $usesOldSchema = true;
                }
                
                if ($newCount > $oldCount) {
                    $usesNewSchema = true;
                }
                
            } else {
                echo "<p class='error'>❌ Tabel '$table' bestaat niet</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Kan tabel '$table' niet controleren: " . $e->getMessage() . "</p>";
        }
    }
    
    // Bepaal welk schema in gebruik is
    $schemaInUse = $usesOldSchema ? "oud" : ($usesNewSchema ? "nieuw" : "onbekend");
    echo "<div class='card'>";
    echo "<h2>Schema Diagnose</h2>";
    echo "<p>Gedetecteerd schema type: <strong>" . $schemaInUse . "</strong></p>";
    
    if ($schemaInUse === "oud") {
        echo "<div class='warning'>";
        echo "<p>⚠️ Je database gebruikt nog het oude schema met kolomnamen zoals 'party_id'.</p>";
        echo "<p>De API is bijgewerkt om deze te ondersteunen, maar je kunt overwegen om het schema te updaten naar de nieuwe structuur.</p>";
        echo "</div>";
    } elseif ($schemaInUse === "nieuw") {
        echo "<div class='success'>";
        echo "<p>✅ Je database gebruikt het nieuwe schema met kolomnamen zoals 'id'.</p>";
        echo "<p>Je moet de API code aanpassen om de juiste kolomnamen te gebruiken.</p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<p>❓ Het schema type kon niet worden bepaald. Dit kan gebeuren als je tabellen een mix van kolomnamen gebruiken.</p>";
        echo "</div>";
    }
    echo "</div>";
    
    // Toon gedetailleerde tabelstructuur
    echo "<h2>Tabelstructuur Details</h2>";
    
    foreach ($tables as $table) {
        if (isset($tableStructures[$table])) {
            echo "<div class='card'>";
            echo "<h3>Tabel: $table</h3>";
            echo "<table>";
            echo "<tr><th>Kolom</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            
            foreach ($tableStructures[$table] as $column) {
                echo "<tr>";
                foreach ($column as $key => $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            
            echo "</table>";
            echo "</div>";
        }
    }
    
    // Mogelijke oplossingen
    echo "<div class='card'>";
    echo "<h2>Actie Nodig</h2>";
    
    if ($schemaInUse === "oud") {
        echo "<p>De volgende optie is beschikbaar:</p>";
        echo "<ol>";
        echo "<li><strong>Schema migreren:</strong> Als je het schema wilt bijwerken naar het nieuwe format, kun je het SQL script opnieuw uitvoeren.</li>";
        echo "</ol>";
        echo "<a href='import-stemwijzer-db.php' class='cta'>SQL Schema Importeren</a>";
    } elseif ($schemaInUse === "nieuw") {
        echo "<p>De volgende opties zijn beschikbaar:</p>";
        echo "<ol>";
        echo "<li><strong>API Code Aanpassen:</strong> Pas de API code aan om de juiste kolomnamen te gebruiken.</li>";
        echo "</ol>";
    } else {
        echo "<p>Je moet eerst het schema controleren en beslissen welke kolomnamen je wilt gebruiken.</p>";
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connectie fout: " . $e->getMessage() . "</p>";
}

echo "</body></html>"; 