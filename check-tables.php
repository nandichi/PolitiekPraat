<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includes voor de database configuratie
require_once 'includes/config.php';
require_once 'includes/Database.php';

echo "<h1>Database Tabellen Controle</h1>";

// Verbinden met de database
try {
    $database = new Database();
    $db = $database->getConnection();
    echo "<p>✅ Database connectie succesvol</p>";

    // Controleer de configuratie
    echo "<h2>Database configuratie:</h2>";
    echo "<ul>";
    echo "<li>Host: " . DB_HOST . "</li>";
    echo "<li>Database: " . DB_NAME . "</li>";
    echo "<li>Gebruiker: " . DB_USER . "</li>";
    echo "<li>Wachtwoord: " . (strlen(DB_PASS) > 0 ? "****" : "<em>leeg</em>") . "</li>";
    echo "</ul>";
    
    // Arrays voor tabellen en hun status
    $tables = ['parties', 'questions', 'positions'];
    $tablesExist = [];
    $rowCounts = [];
    
    // Controleer elke tabel
    echo "<h2>Tabellen controle:</h2>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Tabel</th><th>Bestaat</th><th>Aantal rijen</th></tr>";
    
    $allTablesExist = true;
    
    foreach ($tables as $table) {
        try {
            // Controleer of de tabel bestaat
            $stmt = $db->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $tablesExist[$table] = true;
            
            // Haal het aantal rijen op
            $rowCount = $stmt->fetchColumn();
            $rowCounts[$table] = $rowCount;
            
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td style='color: green;'>✅ Ja</td>";
            echo "<td>$rowCount</td>";
            echo "</tr>";
        } catch (PDOException $e) {
            $tablesExist[$table] = false;
            $allTablesExist = false;
            
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td style='color: red;'>❌ Nee</td>";
            echo "<td>-</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Actie als tabellen niet bestaan
    if (!$allTablesExist) {
        echo "<h2>Tabellen ontbreken</h2>";
        echo "<p>Niet alle benodigde tabellen bestaan in de database. Je kunt het SQL migratie bestand uitvoeren om ze aan te maken.</p>";
        echo "<p><a href='import-stemwijzer-db.php' style='display: inline-block; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Importeer SQL Bestand</a></p>";
        echo "<p>Of voer het SQL bestand handmatig uit: <code>database/migrations/stemwijzer.sql</code></p>";
    } else {
        echo "<h2>Alle tabellen bestaan</h2>";
        
        // Controleer of er data in de tabellen zit
        $emptyTables = [];
        foreach ($rowCounts as $table => $count) {
            if ($count == 0) {
                $emptyTables[] = $table;
            }
        }
        
        if (count($emptyTables) > 0) {
            echo "<p>Let op: De volgende tabellen bestaan, maar bevatten geen data:</p>";
            echo "<ul>";
            foreach ($emptyTables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
            echo "<p>Je kunt het SQL bestand importeren om testdata toe te voegen:</p>";
            echo "<p><a href='import-stemwijzer-db.php' style='display: inline-block; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Importeer SQL Bestand</a></p>";
        } else {
            echo "<p>✅ Alle tabellen bestaan en bevatten data.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Database connectie fout: " . $e->getMessage() . "</p>";
    echo "<p>Controleer de database configuratie in <code>includes/config.php</code></p>";
} 