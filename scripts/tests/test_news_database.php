<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';

// Initialiseer database
$db = new Database();

// Test de database connectie
try {
    $result = $db->directQuery("SELECT COUNT(*) as count FROM news_articles");
    $count = $result->fetch(PDO::FETCH_ASSOC);
    echo "<h2>Database Test Resultaten</h2>";
    echo "<p>Totaal aantal artikelen in database: " . $count['count'] . "</p>";
    
    // Haal wat voorbeelddata op
    $result = $db->directQuery("SELECT * FROM news_articles ORDER BY published_at DESC LIMIT 5");
    $articles = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Laatste 5 artikelen:</h3>";
    echo "<ul>";
    foreach ($articles as $article) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($article['title']) . "</strong><br>";
        echo "Bron: " . htmlspecialchars($article['source']) . " | ";
        echo "Bias: " . htmlspecialchars($article['bias']) . " | ";
        echo "Oriëntatie: " . htmlspecialchars($article['orientation']) . "<br>";
        echo "Gepubliceerd: " . $article['published_at'] . "<br>";
        echo "<em>" . htmlspecialchars(substr($article['description'], 0, 150)) . "...</em>";
        echo "</li><br>";
    }
    echo "</ul>";
    
    // Test filters
    echo "<h3>Test filters:</h3>";
    
    // Progressieve artikelen
    $result = $db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE orientation = 'links'");
    $progressive_count = $result->fetch(PDO::FETCH_ASSOC);
    echo "<p>Progressieve artikelen: " . $progressive_count['count'] . "</p>";
    
    // Conservatieve artikelen  
    $result = $db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE orientation = 'rechts'");
    $conservative_count = $result->fetch(PDO::FETCH_ASSOC);
    echo "<p>Conservatieve artikelen: " . $conservative_count['count'] . "</p>";
    
    echo "<p><strong>Database migratie succesvol!</strong></p>";
    echo "<p><a href='nieuws.php'>Bekijk de nieuwspagina</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database fout: " . $e->getMessage() . "</p>";
}
?> 