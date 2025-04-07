<?php
// Debug inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stemwijzer Database Fix Instructies</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
        h1, h2, h3 { color: #2c3e50; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        pre { background-color: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        code { font-family: Consolas, Monaco, 'Andale Mono', monospace; background-color: #f1f1f1; padding: 2px 4px; border-radius: 4px; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .btn:hover { background-color: #0069d9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stemwijzer Database Fix Instructies</h1>
        
        <div class="card">
            <h2>Probleem Gedetecteerd</h2>
            <div class="alert alert-warning">
                <strong>⚠️ Kolomnaam probleem:</strong> Er is een mismatch tussen de database tabelstructuur en de SQL-queries.
            </div>
            <p>
                In de tabeldefinitie wordt de kolom <code>position</code> genoemd, maar in sommige INSERT 
                statements en SQL-queries wordt <code>stance</code> gebruikt. Dit veroorzaakt de foutmelding:
            </p>
            <pre>SQLSTATE[42S22]: Column not found: 1054 Unknown column 'stance' in 'field list'</pre>
        </div>
        
        <div class="card">
            <h2>Oplossing 1: Nieuw Schema Importeren</h2>
            <p>
                We hebben een gecorrigeerd SQL-script gemaakt (<code>stemwijzer-fix.sql</code>), dat alle tabellen 
                met de juiste kolomnamen aanmaakt en voorbeelddata voor de eerste 3 vragen invoert.
            </p>
            <div class="alert alert-info">
                <strong>ℹ️ Let op:</strong> Als je deze oplossing kiest, worden alle bestaande tabellen (<code>parties</code>, 
                <code>questions</code>, <code>positions</code>) verwijderd en opnieuw aangemaakt!
            </div>
            <p><a href="import-stemwijzer-db.php?file=stemwijzer-fix.sql" class="btn">Fix Script Importeren</a></p>
        </div>
        
        <div class="card">
            <h2>Oplossing 2: Kolomnamen Handmatig Corrigeren</h2>
            <p>
                Als je de bestaande data wilt behouden, kun je de kolomnamen handmatig aanpassen met de volgende SQL-query:
            </p>
            <pre>
ALTER TABLE positions CHANGE COLUMN stance position ENUM('eens','oneens','neutraal') NOT NULL;</pre>
            <p>
                Deze query hernoemt de kolom <code>stance</code> naar <code>position</code> zonder de data te verliezen.
            </p>
            <div class="alert alert-danger">
                <strong>⚠️ Waarschuwing:</strong> Voer deze query alleen uit als je zeker weet dat je tabel een kolom <code>stance</code> heeft!
            </div>
            <p><a href="import-stemwijzer-db.php?query=fix" class="btn">Kolom Hernoemen</a></p>
        </div>
        
        <div class="card">
            <h2>Wat is er gebeurd?</h2>
            <p>
                Het probleem is ontstaan doordat de tabeldefinitie in het SQL-bestand een kolom <code>position</code> definieert:
            </p>
            <pre>
CREATE TABLE positions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  party_id INT NOT NULL,
  position ENUM('eens','oneens','neutraal') NOT NULL,
  explanation TEXT,
  ...
);</pre>
            <p>
                Maar de INSERT statements gebruiken <code>stance</code>:
            </p>
            <pre>
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(1, 1, 'eens', 'Uitleg hier...');</pre>

            <p>
                De API is aangepast om met beide kolomnamen te werken, maar de database zelf moet consistent zijn.
            </p>
        </div>
        
        <div class="card">
            <h2>Andere Opties</h2>
            <ul>
                <li><a href="diagnose-schema.php">Schema Diagnosticeren</a> - Controleer de huidige database structuur</li>
                <li><a href="import-stemwijzer-db.php">Origineel SQL-script Importeren</a> - Probeer het originele script opnieuw (zal nog steeds fouten geven)</li>
                <li><a href="index.php">Terug naar Home</a> - Terug naar de hoofdpagina</li>
            </ul>
        </div>
    </div>
</body>
</html> 