<?php
// Include common API configuration
require_once 'config.php';

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = isset($input['query']) ? trim($input['query']) : '';
    $limit = isset($input['limit']) ? (int)$input['limit'] : 20;
    $categories = isset($input['categories']) ? $input['categories'] : [];
    $dateRange = isset($input['dateRange']) ? $input['dateRange'] : null;
    
    if (empty($query)) {
        sendErrorResponse(400, 'Zoekterm is vereist');
    }
    
    // Limit maximum results
    $limit = min($limit, 50);
    
    try {
        $database = getDatabase();
        $pdo = $database->getConnection();
        
        // Build search query for blogs table
        $searchQuery = "
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
                b.views as view_count,
                MATCH(b.title, b.summary, b.content) AGAINST (? IN BOOLEAN MODE) as relevance_score
            FROM blogs b
            LEFT JOIN users u ON b.author_id = u.id
            WHERE MATCH(b.title, b.summary, b.content) AGAINST (? IN BOOLEAN MODE)
        ";
        
        $params = [$query, $query];
        
        // Add date range filter
        if ($dateRange) {
            switch ($dateRange) {
                case 'today':
                    $searchQuery .= " AND DATE(b.published_at) = CURDATE()";
                    break;
                case 'week':
                    $searchQuery .= " AND b.published_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                    break;
                case 'month':
                    $searchQuery .= " AND b.published_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                    break;
                case 'year':
                    $searchQuery .= " AND b.published_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                    break;
            }
        }
        
        // Add ordering and limit
        $searchQuery .= " AND b.published_at <= NOW() ORDER BY relevance_score DESC, b.published_at DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $pdo->prepare($searchQuery);
        $stmt->execute($params);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Process results
        $processedArticles = [];
        foreach ($articles as $article) {
            // Calculate reading time if not set
            $readingTime = $article['reading_time'] ?: calculateReadingTime($article['content']);
            
            // Extract tags from content
            $tags = extractTags($article['content'], $article['category']);
            
            // Build full URL using slug if available, otherwise ID
            $slug = isset($article['slug']) ? $article['slug'] : $article['id'];
            $url = URLROOT . "/blog/" . $slug;
            
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
                'relevance_score' => (float)$article['relevance_score'],
                'reading_time' => (int)$readingTime,
                'tags' => $tags
            ];
        }
        
        sendSuccessResponse([
            'articles' => $processedArticles,
            'totalCount' => count($processedArticles)
        ]);
        
    } catch (PDOException $e) {
        sendErrorResponse(500, 'Zoeken mislukt: ' . $e->getMessage());
    } catch (Exception $e) {
        sendErrorResponse(500, 'Server fout: ' . $e->getMessage());
    }
    
} else {
    sendErrorResponse(405, 'Alleen POST requests toegestaan');
}
?> 