<?php
require_once 'includes/config.php';

echo "🔧 TESTING RELATED BLOGS SECTION FIX\n\n";

try {
    // Direct database test zoals in BlogController->getAll()
    $db = new Database();
    
    $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo,
                   blog_categories.name as category_name, blog_categories.slug as category_slug, 
                   blog_categories.color as category_color, blog_categories.icon as category_icon
            FROM blogs 
            JOIN users ON blogs.author_id = users.id 
            LEFT JOIN blog_categories ON blogs.category_id = blog_categories.id 
            WHERE blogs.is_published = 1 
            ORDER BY blogs.published_at DESC 
            LIMIT 3";
    
    $db->query($sql);
    $otherBlogs = $db->resultSet();
    
    if (empty($otherBlogs)) {
        echo "❌ No blogs found!\n";
        exit;
    }
    
    echo "📝 TESTING " . count($otherBlogs) . " RELATED BLOGS:\n\n";
    
    foreach ($otherBlogs as $index => $relatedBlog) {
        echo "--- RELATED BLOG " . ($index + 1) . " ---\n";
        echo "Title: " . $relatedBlog->title . "\n";
        echo "Author Name: " . ($relatedBlog->author_name ?? '[NULL]') . "\n";
        echo "Author Photo: " . ($relatedBlog->author_photo ?? '[NULL]') . "\n";
        echo "Profile Photo: " . ($relatedBlog->profile_photo ?? '[NULL]') . "\n";
        
        // Test the helper function like in the related blogs section
        if (isset($relatedBlog->author_photo)) {
            $relatedProfilePhotoData = getProfilePhotoUrl($relatedBlog->author_photo ?? null, $relatedBlog->author_name);
            echo "Helper Result Type: " . $relatedProfilePhotoData['type'] . "\n";
            echo "Helper Result Value: " . $relatedProfilePhotoData['value'] . "\n";
            
            if ($relatedProfilePhotoData['type'] === 'img') {
                // Test URL accessibility
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $relatedProfilePhotoData['value']);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                echo "URL Status: " . $httpCode . " " . ($httpCode == 200 ? "✅ ACCESSIBLE" : "❌ NOT ACCESSIBLE") . "\n";
            }
        }
        echo "\n";
    }
    
    echo "🎉 RELATED BLOGS FIX TEST COMPLETED!\n";
    
} catch (Exception $e) {
    echo "❌ Error during test: " . $e->getMessage() . "\n";
}
