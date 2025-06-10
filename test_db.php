<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';

echo "=== Database Test ===\n";

try {
    $db = new Database();
    echo "✓ Database connectie succesvol\n";
    
    // Check if table exists
    $result = $db->directQuery('SHOW TABLES LIKE "news_articles"');
    $table_exists = $result->fetch();
    
    if ($table_exists) {
        echo "✓ Tabel news_articles bestaat\n";
        
        // Check table structure
        $result = $db->directQuery('DESCRIBE news_articles');
        echo "\nTabel structuur:\n";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
        
        // Check record count
        $count = $db->directQuery('SELECT COUNT(*) as count FROM news_articles')->fetch();
        echo "\nAantal records: " . $count['count'] . "\n";
        
        // Test a simple insert
        echo "\n=== Test Insert ===\n";
        $sql = "INSERT INTO news_articles (title, description, url, source, bias, orientation, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $db->query($sql);
        $db->bind(1, 'Test Artikel');
        $db->bind(2, 'Dit is een test artikel');
        $db->bind(3, 'https://test.nl/artikel');
        $db->bind(4, 'Test Bron');
        $db->bind(5, 'Neutraal');
        $db->bind(6, 'links');
        $db->bind(7, date('Y-m-d H:i:s'));
        
        if ($db->execute()) {
            echo "✓ Test insert succesvol\n";
            
            // Remove test record
            $db->directQuery("DELETE FROM news_articles WHERE title = 'Test Artikel'");
            echo "✓ Test record verwijderd\n";
        } else {
            echo "✗ Test insert gefaald\n";
        }
        
    } else {
        echo "✗ Tabel news_articles bestaat niet\n";
        echo "Voer de database migratie uit:\n";
        echo "mysql -u " . DB_USER . " -p " . DB_NAME . " < database/migrations/create_news_articles_table.sql\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database fout: " . $e->getMessage() . "\n";
}

echo "\n=== Test Voltooid ===\n";
?> 