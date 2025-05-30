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
        .output { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>Stemwijzer Database Import</h1>
    
    <?php
    if (isset($_GET['action'])) {
        echo '<div class="output">';
        
        switch ($_GET['action']) {
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
        <h2>Beschikbare acties:</h2>
        
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=check" class="btn">1. Database Status Controleren</a><br>
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=cleanup" class="btn danger">2. Database Opschonen (VERWIJDERT ALLE DATA!)</a><br>
        <a href="?code=<?php echo $SECURITY_CODE; ?>&action=import" class="btn">3. Nieuwe Data Importeren</a><br>
        
        <h3>Aanbevolen volgorde:</h3>
        <ol>
            <li>Eerst controleren wat er in de database staat</li>
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