<?php
// Include common API configuration
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $category = isset($_GET['category']) ? trim($_GET['category']) : null;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    // Limit maximum results
    $limit = min($limit, 50);
    
    try {
        $database = getDatabase();
        $pdo = $database->getConnection();
        
        // Build query for blogs table
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
            WHERE b.published_at <= NOW()
        ";
        
        $params = [];
        
        // Order by date and limit
        $query .= " ORDER BY b.published_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Process results
        $processedArticles = [];
        foreach ($articles as $article) {
            // Calculate reading time if not set
            $readingTime = $article['reading_time'] ?: calculateReadingTime($article['content']);
            
            // Extract tags from content
            $tags = extractTags($article['content'], $article['category']);
            
            // Build full URL using slug
            $url = URLROOT . "/blog/" . $article['slug'];
            
            $processedArticles[] = [
                'id' => (int)$article['id'],
                'title' => $article['title'],
                'summary' => $article['summary'] ?: substr(strip_tags($article['content']), 0, 200) . '...',
                'content' => $article['content'],
                'category' => $article['category'],
                'author' => $article['author'] ?: 'PolitiekPraat',
                'published_date' => $article['published_date'],
                'url' => $url,
                'image_url' => $article['image_url'] ? URLROOT . "/" . $article['image_url'] : null,
                'relevance_score' => 1.0, // Default relevance for latest articles
                'reading_time' => (int)$readingTime,
                'tags' => $tags
            ];
        }
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM blogs WHERE published_at <= NOW()";
        $countParams = [];
        
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        sendSuccessResponse([
            'articles' => $processedArticles,
            'totalCount' => (int)$totalCount,
            'hasMore' => ($offset + $limit) < $totalCount
        ]);
        
    } catch (PDOException $e) {
        sendErrorResponse(500, 'Artikelen ophalen mislukt: ' . $e->getMessage());
    } catch (Exception $e) {
        sendErrorResponse(500, 'Server fout: ' . $e->getMessage());
    }
    
} else {
    sendErrorResponse(405, 'Alleen GET requests toegestaan');
}
?> 