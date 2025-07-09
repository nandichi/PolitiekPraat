<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuratie
define('DB_HOST', 'localhost');
define('DB_USER', 'naoufal_politiekpraat_user');
define('DB_PASS', 'Naoufal2004!');
define('DB_NAME', 'naoufal_politiekpraat_db');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database verbinding mislukt'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $category = isset($_GET['category']) ? trim($_GET['category']) : null;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    // Limit maximum results
    $limit = min($limit, 50);
    
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
    
    // Note: Category filter removed since blogs table doesn't have category column
    // You can add author filter instead if needed
    
    // Order by date and limit
    $query .= " ORDER BY b.published_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $articles = $stmt->fetchAll();
        
        // Process results
        $processedArticles = [];
        foreach ($articles as $article) {
            // Calculate reading time if not set
            if (!$article['reading_time']) {
                $wordCount = str_word_count(strip_tags($article['content']));
                $readingTime = max(1, ceil($wordCount / 200)); // Assuming 200 words per minute
            } else {
                $readingTime = (int)$article['reading_time'];
            }
            
            // Extract tags from content
            $tags = extractTags($article['content'], $article['category']);
            
            // Build full URL using slug
            $url = "https://politiekpraat.nl/blog/" . $article['slug'];
            
            $processedArticles[] = [
                'id' => (int)$article['id'],
                'title' => $article['title'],
                'summary' => $article['summary'] ?: substr(strip_tags($article['content']), 0, 200) . '...',
                'content' => $article['content'],
                'category' => $article['category'],
                'author' => $article['author'] ?: 'PolitiekPraat',
                'published_date' => $article['published_date'],
                'url' => $url,
                'image_url' => $article['image_url'] ? "https://politiekpraat.nl/" . $article['image_url'] : null,
                'relevance_score' => 1.0, // Default relevance for latest articles
                'reading_time' => $readingTime,
                'tags' => $tags
            ];
        }
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM blogs WHERE published_at <= NOW()";
        $countParams = [];
        
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = $countStmt->fetch()['total'];
        
        echo json_encode([
            'success' => true,
            'articles' => $processedArticles,
            'totalCount' => (int)$totalCount,
            'hasMore' => ($offset + $limit) < $totalCount
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Artikelen ophalen mislukt: ' . $e->getMessage()
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Alleen GET requests toegestaan'
    ]);
}

function extractTags($content, $category) {
    $tags = [$category];
    
    // Common political keywords
    $keywords = [
        'verkiezingen', 'stemmen', 'partij', 'coalitie', 'oppositie',
        'kamer', 'minister', 'premier', 'kabinet', 'regering',
        'europa', 'nederland', 'gemeenteraad', 'provincie'
    ];
    
    $contentLower = strtolower(strip_tags($content));
    
    foreach ($keywords as $keyword) {
        if (strpos($contentLower, $keyword) !== false) {
            $tags[] = $keyword;
        }
    }
    
    return array_unique($tags);
}
?> 