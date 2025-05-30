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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        h3 {
            color: #34495e;
            margin: 25px 0 15px 0;
            font-size: 1.2rem;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .btn { 
            display: inline-block;
            padding: 15px 25px; 
            margin: 8px; 
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white; 
            text-decoration: none; 
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .btn.danger { 
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .btn.danger:hover { 
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .btn.success { 
            background: linear-gradient(135deg, #27ae60, #229954);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }
        
        .btn.success:hover { 
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }
        
        .output { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0; 
            white-space: pre-wrap;
            border-left: 4px solid #3498db;
            font-family: 'Courier New', monospace;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .warning { 
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0; 
            border-left: 4px solid #f39c12;
            box-shadow: 0 2px 10px rgba(243, 156, 18, 0.2);
        }
        
        .warning strong {
            color: #d68910;
            font-size: 1.1rem;
        }
        
        .action-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .steps-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }
        
        ol {
            padding-left: 20px;
            margin: 15px 0;
        }
        
        li {
            margin: 8px 0;
            color: #555;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .back-link:hover {
            background: #5a6268;
        }
        
        .notice {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            color: #0c5460;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .content-card {
                padding: 20px;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stemwijzer Database Import</h1>
        
        <?php
        if (isset($_GET['action'])) {
            echo '<div class="content-card"><div class="output">';
            
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
            echo '<a href="?code=' . $SECURITY_CODE . '" class="back-link">&larr; Terug naar menu</a></div>';
        } else {
            ?>
            <div class="content-card">
                <div class="warning">
                    <strong>⚠️ Eerste keer gebruiken?</strong><br>
                    Als je de stemwijzer voor het eerst installeert, begin dan met stap 0 om de database tabellen aan te maken.
                </div>
                
                <h2>Beschikbare acties:</h2>
                
                <div class="action-grid">
                    <a href="?code=<?php echo $SECURITY_CODE; ?>&action=create_tables" class="btn success">0. Database Tabellen Aanmaken (ALLEEN EERSTE KEER!)</a>
                    <a href="?code=<?php echo $SECURITY_CODE; ?>&action=check" class="btn">1. Database Status Controleren</a>
                    <a href="?code=<?php echo $SECURITY_CODE; ?>&action=cleanup" class="btn danger">2. Database Opschonen (VERWIJDERT ALLE DATA!)</a>
                    <a href="?code=<?php echo $SECURITY_CODE; ?>&action=import" class="btn">3. Nieuwe Data Importeren</a>
                </div>
                
                <div class="steps-section">
                    <h3>Aanbevolen volgorde voor nieuwe installatie:</h3>
                    <ol>
                        <li><strong>Database tabellen aanmaken</strong> (alleen eerste keer)</li>
                        <li>Database status controleren</li>
                        <li>Nieuwe data importeren</li>
                        <li>Nogmaals controleren of alles correct is</li>
                    </ol>
                </div>
                
                <div class="steps-section">
                    <h3>Aanbevolen volgorde voor update:</h3>
                    <ol>
                        <li>Database status controleren</li>
                        <li>Als er te veel/verkeerde data staat: opschonen</li>
                        <li>Nieuwe data importeren</li>
                        <li>Nogmaals controleren of alles correct is</li>
                    </ol>
                </div>
                
                <div class="notice">
                    <strong>Let op:</strong> De cleanup actie verwijdert ALLE stemwijzer data uit de database!
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html> 