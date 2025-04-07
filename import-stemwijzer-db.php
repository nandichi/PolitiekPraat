<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includes voor de database configuratie
require_once 'includes/config.php';
require_once 'includes/Database.php';

// SQL-bestand controleren
$sqlFile = isset($_GET['file']) ? $_GET['file'] : 'database/migrations/stemwijzer.sql';

// Controleer of het bestand bestaat en of het een toegestaan bestand is
$allowedFiles = [
    'database/migrations/stemwijzer.sql', 
    'database/migrations/stemwijzer-fix.sql'
];

if (!in_array($sqlFile, $allowedFiles)) {
    // Veiligheidscheck, sta alleen toegestane bestanden toe
    $sqlFile = 'database/migrations/stemwijzer.sql';
}

if (!file_exists($sqlFile)) {
    die('SQL-bestand niet gevonden: ' . $sqlFile);
}

// HTML-header
echo '<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stemwijzer Database Import</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
        h1, h2 { color: #2c3e50; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: #28a745; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #28a745; margin-bottom: 15px; }
        .warning { color: #ffc107; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #ffc107; margin-bottom: 15px; }
        .error { color: #dc3545; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #dc3545; margin-bottom: 15px; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background-color: #0069d9; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        pre { background-color: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        tr:hover { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stemwijzer Database Importeren</h1>';

try {
    // Database connectie maken
    $database = new Database();
    $db = $database->getConnection();
    echo '<div class="success">✅ Database connectie succesvol</div>';
    
    // Controleer of er een specifieke query moet worden uitgevoerd
    if (isset($_GET['query']) && $_GET['query'] === 'fix') {
        echo '<h2>Kolom Hernoemen</h2>';
        
        try {
            // Controleer eerst of de kolom 'stance' bestaat
            $stmt = $db->prepare("SHOW COLUMNS FROM positions LIKE 'stance'");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                // Voer de hernoem query uit
                $alterQuery = "ALTER TABLE positions CHANGE COLUMN stance position ENUM('eens','oneens','neutraal') NOT NULL";
                $db->exec($alterQuery);
                echo '<div class="success">✅ Kolom succesvol hernoemd van "stance" naar "position"</div>';
            } else {
                echo '<div class="warning">⚠️ Kolom "stance" niet gevonden. Misschien is de kolom al hernoemd of heeft deze een andere naam.</div>';
            }
            
            // Toon link naar instructies
            echo '<div style="margin: 20px 0;">';
            echo '<a href="stemwijzer-fix-instructies.php" class="btn">Terug naar instructies</a>';
            echo '<a href="diagnose-schema.php" class="btn" style="margin-left: 10px;">Schema Diagnosticeren</a>';
            echo '</div>';
            
            echo '</div></body></html>';
            exit;
        } catch (PDOException $e) {
            echo '<div class="error">❌ Fout bij uitvoeren query: ' . $e->getMessage() . '</div>';
            
            echo '<div style="margin: 20px 0;">';
            echo '<a href="stemwijzer-fix-instructies.php" class="btn">Terug naar instructies</a>';
            echo '</div>';
            
            echo '</div></body></html>';
            exit;
        }
    }
    
    // Controleer of tabellen bestaan
    $tables = ['parties', 'questions', 'positions'];
    $existingTables = [];
    
    foreach ($tables as $table) {
        $stmt = $db->prepare("SHOW TABLES LIKE '$table'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $existingTables[] = $table;
            
            // Haal kolomnamen op
            $stmt = $db->prepare("DESCRIBE $table");
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "<div class='warning'>⚠️ Tabel '$table' bestaat al met kolommen: " . implode(', ', $columns) . "</div>";
            
            // Check hoeveel rijen de tabel heeft
            $stmt = $db->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            echo "<div class='warning'>ℹ️ Tabel '$table' bevat $count rijen</div>";
        }
    }
    
    if (count($existingTables) > 0) {
        echo '<div class="warning">⚠️ Let op: sommige tabellen bestaan al. Importeren zal bestaande tabellen verwijderen en opnieuw aanmaken!</div>';
        
        // Alleen formulier tonen als er nog niet is geïmporteerd
        if (!isset($_POST['confirm_import'])) {
            echo '<form method="post" style="margin: 20px 0;">
                <p>Weet je zeker dat je de stemwijzer tabellen wilt importeren? Alle bestaande gegevens worden verwijderd!</p>
                <input type="hidden" name="confirm_import" value="1">
                <button type="submit" class="btn btn-danger">Ja, importeer en overschrijf bestaande tabellen</button>
                <a href="index.php" class="btn" style="margin-left: 10px;">Annuleren</a>
            </form>';
            
            echo '</div></body></html>';
            exit;
        }
    }
    
    // SQL bestand inlezen en uitvoeren
    echo '<h2>SQL Import uitvoeren</h2>';
    
    $sqlContent = file_get_contents($sqlFile);
    $sqlQueries = preg_split('/;\s*$/m', $sqlContent);
    
    $totalQueries = 0;
    $successQueries = 0;
    $errorQueries = 0;
    $errors = [];
    
    foreach ($sqlQueries as $query) {
        $query = trim($query);
        if (empty($query) || strpos($query, '--') === 0) {
            continue; // Lege regels of commentaar overslaan
        }
        
        $totalQueries++;
        
        try {
            $result = $db->exec($query);
            $successQueries++;
            echo '<div class="success">✅ Query uitgevoerd: ' . substr(htmlspecialchars($query), 0, 100) . '...</div>';
        } catch (PDOException $e) {
            $errorQueries++;
            $errorMsg = $e->getMessage();
            $errors[] = ['query' => $query, 'error' => $errorMsg];
            echo '<div class="error">❌ Fout bij query: ' . htmlspecialchars($query) . '<br>Foutmelding: ' . $errorMsg . '</div>';
        }
    }
    
    echo '<h2>Import Resultaat</h2>';
    echo '<div class="' . ($errorQueries > 0 ? 'warning' : 'success') . '">';
    echo "<p>Totaal aantal queries: $totalQueries</p>";
    echo "<p>Succesvol uitgevoerd: $successQueries</p>";
    echo "<p>Fouten: $errorQueries</p>";
    echo '</div>';
    
    if ($errorQueries > 0) {
        echo '<h3>Fouten Details</h3>';
        echo '<pre>';
        foreach ($errors as $index => $error) {
            echo "Fout " . ($index + 1) . ":\n";
            echo "Query: " . htmlspecialchars($error['query']) . "\n";
            echo "Foutmelding: " . $error['error'] . "\n\n";
        }
        echo '</pre>';
    }
    
    // Controleer resultaat
    echo '<h2>Huidige Tabellen</h2>';
    echo '<table>';
    echo '<tr><th>Tabel</th><th>Aantal Rijen</th><th>Kolommen</th></tr>';
    
    foreach ($tables as $table) {
        $stmt = $db->prepare("SHOW TABLES LIKE '$table'");
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Haal rijen op
            $stmt = $db->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            // Haal kolomnamen op
            $stmt = $db->prepare("DESCRIBE $table");
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>$count</td>";
            echo "<td>" . implode(', ', $columns) . "</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td colspan='2' class='error'>Tabel niet gevonden</td>";
            echo "</tr>";
        }
    }
    
    echo '</table>';
    
    // Link naar diagnose pagina
    echo '<div style="margin-top: 20px;">';
    echo '<a href="diagnose-schema.php" class="btn">Schema Diagnosticeren</a>';
    echo '<a href="index.php" class="btn" style="margin-left: 10px;">Terug naar Home</a>';
    echo '</div>';
    
} catch (Exception $e) {
    echo '<div class="error">❌ Er is een fout opgetreden: ' . $e->getMessage() . '</div>';
}

echo '</div></body></html>'; 