<?php
// Eenvoudige beveiligingscode - verander dit naar iets veiligs
$SECURITY_CODE = 'stemwijzer2024';

// Check of de juiste code is ingevoerd
if (!isset($_GET['code']) || $_GET['code'] !== $SECURITY_CODE) {
    die('Access denied. Je hebt een geldige beveiligingscode nodig.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Stemwijzer Database Import</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .btn { padding: 10px 20px; margin: 10px; background: #007cba; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #005a87; }
        .danger { background: #dc3545; }
        .danger:hover { background: #c82333; }
        .success { background: #28a745; }
        .success:hover { background: #218838; }
        .output { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; white-space: pre-wrap; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }
    </style>
</head>
<body>
    <h1>Stemwijzer Database Import</h1>
    
    <?php
    if (isset($_GET['action'])) {
        echo '<div class="output">';
        
        switch ($_GET['action']) {
            case 'create_tables':
                echo "Creating stemwijzer database tables...\n";
                ob_start();
                include 'scripts/create_stemwijzer_tables.php';
                $output = ob_get_clean();
                echo $output;
                break;
                
            case 'cleanup':
                echo "Running cleanup script...\n";
                ob_start();
                include 'scripts/cleanup_duplicates.php';
                $output = ob_get_clean();
                echo $output;
                break;
                
            case 'import':
                echo "Running import script...\n";
                ob_start();
                include 'scripts/run_stemwijzer_migration.php';
                $output = ob_get_clean();
                echo $output;
                break;
                
            case 'check':
                echo "Checking database...\n";
                ob_start();
                include 'scripts/check_database.php';
                $output = ob_get_clean();
                echo $output;
                break;
        }
        
        echo '</div>';
        echo '<a href="?code=' . $SECURITY_CODE . '">&larr; Terug naar menu</a>';
    } else {
        ?>
        <div class="warning">
            <strong>⚠️ Eerste keer gebruiken?</strong><br>
            Als je de stemwijzer voor het eerst installeert, begin dan met stap 0 om de database tabellen aan te maken.
        </div>
        
        <h2>Beschikbare acties:</h2>
        
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=create_tables" class="btn success">0. Database Tabellen Aanmaken (ALLEEN EERSTE KEER!)</a><br>
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=check" class="btn">1. Database Status Controleren</a><br>
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=cleanup" class="btn danger">2. Database Opschonen (VERWIJDERT ALLE DATA!)</a><br>
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=import" class="btn">3. Nieuwe Data Importeren</a><br>
        
        <h3>Aanbevolen volgorde voor nieuwe installatie:</h3>
        <ol>
            <li><strong>Database tabellen aanmaken</strong> (alleen eerste keer)</li>
            <li>Database status controleren</li>
            <li>Nieuwe data importeren</li>
            <li>Nogmaals controleren of alles correct is</li>
        </ol>
        
        <h3>Aanbevolen volgorde voor update:</h3>
        <ol>
            <li>Database status controleren</li>
            <li>Als er te veel/verkeerde data staat: opschonen</li>
            <li>Nieuwe data importeren</li>
            <li>Nogmaals controleren of alles correct is</li>
        </ol>
        
        <p><strong>Let op:</strong> De cleanup actie verwijdert ALLE stemwijzer data uit de database!</p>
        <?php
    }
    ?>
</body>
</html> 