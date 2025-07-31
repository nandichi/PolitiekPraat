<?php
/**
 * Script om te controleren of alle blogs een categorie hebben
 * en om eventuele problemen op te lossen
 */

// Bepaal het BASE_PATH
define('BASE_PATH', dirname(__DIR__));

// Laad alle benodigde includes
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/Database.php';

try {
    echo "Controleren van blog categorieën...\n\n";
    
    // Database connectie
    $db = new Database();
    
    // Controleer of de blog_categories tabel bestaat
    $db->query("SHOW TABLES LIKE 'blog_categories'");
    $categoryTableExists = $db->single();
    
    if (!$categoryTableExists) {
        echo "❌ De blog_categories tabel bestaat niet!\n";
        echo "Run eerst de migratie script: php scripts/run_blog_categories_migration.php\n";
        exit;
    }
    
    echo "✅ Blog_categories tabel bestaat\n\n";
    
    // Controleer alle blogs
    $db->query("SELECT id, title, category_id FROM blogs ORDER BY published_at DESC");
    $blogs = $db->resultSet();
    
    echo "📊 Blogs overzicht:\n";
    echo "Totaal aantal blogs: " . count($blogs) . "\n\n";
    
    $blogsWithoutCategory = 0;
    $blogsWithCategory = 0;
    
    foreach ($blogs as $blog) {
        if ($blog->category_id === null) {
            $blogsWithoutCategory++;
            echo "⚠️  Blog zonder categorie: ID {$blog->id} - {$blog->title}\n";
        } else {
            $blogsWithCategory++;
        }
    }
    
    echo "\n📈 Statistieken:\n";
    echo "Blogs met categorie: {$blogsWithCategory}\n";
    echo "Blogs zonder categorie: {$blogsWithoutCategory}\n\n";
    
    // Als er blogs zijn zonder categorie, bied optie aan om ze toe te wijzen
    if ($blogsWithoutCategory > 0) {
        echo "🔧 Wil je alle blogs zonder categorie automatisch toewijzen aan 'Nederlandse Politiek'? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $response = trim(fgets($handle));
        fclose($handle);
        
        if (strtolower($response) === 'y' || strtolower($response) === 'yes') {
            // Haal de ID van 'Nederlandse Politiek' op
            $db->query("SELECT id FROM blog_categories WHERE slug = 'nederlandse-politiek' LIMIT 1");
            $defaultCategory = $db->single();
            
            if ($defaultCategory) {
                $db->query("UPDATE blogs SET category_id = :category_id WHERE category_id IS NULL");
                $db->bind(':category_id', $defaultCategory->id);
                $result = $db->execute();
                
                if ($result) {
                    echo "✅ Alle blogs zonder categorie zijn toegewezen aan 'Nederlandse Politiek'\n";
                } else {
                    echo "❌ Er is een fout opgetreden bij het toewijzen van categorieën\n";
                }
            } else {
                echo "❌ Kan de categorie 'Nederlandse Politiek' niet vinden\n";
            }
        }
    }
    
    echo "\n✅ Controle voltooid!\n";
    
} catch (Exception $e) {
    echo "\n❌ Fout: " . $e->getMessage() . "\n";
}
?>