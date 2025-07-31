<?php
/**
 * DIRECTE Blog Categories Migration - Werkt gegarandeerd op live server
 * Deze script maakt alles handmatig zonder externe SQL bestanden
 */

// Simpele database connectie zonder afhankelijkheden
require_once '../includes/config.php';

try {
    echo "<h2>🚀 LIVE BLOG CATEGORIES MIGRATION</h2>";
    echo "<p>Starting direct database migration...</p>";
    
    // Directe PDO connectie
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>📋 Step 1: Create blog_categories table</h3>";
    
    // 1. Maak blog_categories tabel
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS blog_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        color VARCHAR(7) DEFAULT '#3B82F6',
        icon VARCHAR(50) DEFAULT 'folder',
        sort_order INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_active (is_active),
        INDEX idx_sort_order (sort_order)
    ) ENGINE=InnoDB;";
    
    $pdo->exec($createTableSQL);
    echo "✅ blog_categories table created<br>";
    
    echo "<h3>📋 Step 2: Add category_id to blogs table</h3>";
    
    // 2. Controleer of category_id kolom al bestaat
    $checkColumnSQL = "SHOW COLUMNS FROM blogs LIKE 'category_id'";
    $stmt = $pdo->query($checkColumnSQL);
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        // Voeg category_id kolom toe
        $addColumnSQL = "ALTER TABLE blogs ADD COLUMN category_id INT NULL";
        $pdo->exec($addColumnSQL);
        echo "✅ category_id column added to blogs table<br>";
        
        // Voeg foreign key constraint toe
        try {
            $addConstraintSQL = "ALTER TABLE blogs ADD CONSTRAINT fk_blog_category FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL";
            $pdo->exec($addConstraintSQL);
            echo "✅ Foreign key constraint added<br>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "⚠️ Foreign key constraint may already exist: " . $e->getMessage() . "<br>";
            }
        }
    } else {
        echo "✅ category_id column already exists<br>";
    }
    
    echo "<h3>📋 Step 3: Insert default categories</h3>";
    
    // 3. Voeg standaard categorieën toe
    $categories = [
        ['Nederlandse Politiek', 'nederlandse-politiek', 'Diepgaande analyses van de Nederlandse politiek.', '#FF8C00', 'flag', 10],
        ['Internationale Politiek', 'internationale-politiek', 'Inzichten in wereldwijde politieke ontwikkelingen.', '#4682B4', 'globe', 20],
        ['Verkiezingen', 'verkiezingen', 'Alles over verkiezingen, campagnes en uitslagen.', '#228B22', 'vote', 30],
        ['Partijanalyses', 'partijanalyses', 'Gedetailleerde blik op politieke partijen en hun standpunten.', '#800080', 'users', 40],
        ['Democratie & Instituties', 'democratie-instituties', 'Artikelen over de werking van democratie en overheidsinstellingen.', '#FFD700', 'gavel', 50],
        ['Politieke Geschiedenis', 'politieke-geschiedenis', 'Historische context en lessen uit het verleden.', '#B22222', 'book', 60],
        ['Opinies & Columns', 'opinies-columns', 'Persoonlijke meningen en commentaar op actuele zaken.', '#FF69B4', 'pen', 70],
        ['Actuele Themas', 'actuele-themas', 'Discussies over de meest recente en relevante politieke onderwerpen.', '#00CED1', 'chart-bar', 80]
    ];
    
    $insertSQL = "INSERT IGNORE INTO blog_categories (name, slug, description, color, icon, sort_order) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertSQL);
    
    $insertedCount = 0;
    foreach ($categories as $category) {
        $stmt->execute($category);
        if ($stmt->rowCount() > 0) {
            $insertedCount++;
        }
    }
    
    echo "✅ {$insertedCount} categories inserted<br>";
    
    echo "<h3>📋 Step 4: Verification</h3>";
    
    // 4. Verificatie
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blog_categories");
    $categoryCount = $stmt->fetch()['count'];
    echo "📊 Total categories in database: {$categoryCount}<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blogs WHERE category_id IS NULL");
    $blogsWithoutCategory = $stmt->fetch()['count'];
    echo "📝 Blogs without category: {$blogsWithoutCategory}<br>";
    
    echo "<h3>🎉 MIGRATION COMPLETED SUCCESSFULLY!</h3>";
    echo "<p><strong>✅ All database changes applied successfully!</strong></p>";
    echo "<p>🔄 <a href='/blogs'>Test blogs page now</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ MIGRATION FAILED</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
}
?>