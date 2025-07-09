<?php
// Include common API configuration
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get article ID from URL path
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    $articleId = end($pathParts);
    
    // Validate article ID
    if (!is_numeric($articleId)) {
        sendErrorResponse(400, 'Ongeldig artikel ID');
    }
    
    try {
        $database = getDatabase();
        $pdo = $database->getConnection();
        
        $query = "
            SELECT 
                b.id,
                b.title,
                b.slug,
                b.summary,
                b.content,
                'blog' as category,
                COALESCE(u.username, 'PolitiekPraat') as author,
                b.published_at as published_date,
                b.image_path as image_url,
                CEIL(CHAR_LENGTH(STRIP_TAGS(b.content))/1000) as reading_time,
                b.views as view_count
            FROM blogs b
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.id = ? AND b.published_at <= NOW()
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([(int)$articleId]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$article) {
            sendErrorResponse(404, 'Artikel niet gevonden');
        }
        
        // Calculate reading time if not set
        $readingTime = $article['reading_time'] ?: calculateReadingTime($article['content']);
        
        // Extract tags from content
        $tags = extractTags($article['content'], $article['category']);
        
        // Build full URL using slug
        $url = URLROOT . "/blog/" . $article['slug'];
        
        // Update view count
        $updateQuery = "UPDATE blogs SET views = views + 1 WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([(int)$articleId]);
        
        $processedArticle = [
            'id' => (int)$article['id'],
            'title' => $article['title'],
            'summary' => $article['summary'] ?: substr(strip_tags($article['content']), 0, 200) . '...',
            'content' => $article['content'],
            'category' => $article['category'],
            'author' => $article['author'] ?: 'PolitiekPraat',
            'published_date' => $article['published_date'],
            'url' => $url,
            'image_url' => $article['image_url'] ? URLROOT . "/" . $article['image_url'] : null,
            'relevance_score' => 1.0,
            'reading_time' => (int)$readingTime,
            'tags' => $tags
        ];
        
        sendSuccessResponse([
            'article' => $processedArticle
        ]);
        
    } catch (PDOException $e) {
        sendErrorResponse(500, 'Artikel ophalen mislukt: ' . $e->getMessage());
    } catch (Exception $e) {
        sendErrorResponse(500, 'Server fout: ' . $e->getMessage());
    }
    
} else {
    sendErrorResponse(405, 'Alleen GET requests toegestaan');
}
?> 