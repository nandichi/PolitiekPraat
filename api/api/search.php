<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
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

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = isset($input['query']) ? trim($input['query']) : '';
    $limit = isset($input['limit']) ? (int)$input['limit'] : 20;
    $categories = isset($input['categories']) ? $input['categories'] : [];
    $dateRange = isset($input['dateRange']) ? $input['dateRange'] : null;
    
    if (empty($query)) {
        echo json_encode([
            'success' => false,
            'error' => 'Zoekterm is vereist'
        ]);
        exit();
    }
    
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
    
    // Note: Category filter removed since blogs table doesn't have category column
    // You can add author filter instead if needed
    
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
    
    try {
        $stmt = $pdo->prepare($searchQuery);
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
            
            // Extract tags from content or use default categories
            $tags = extractTags($article['content'], $article['category']);
            
            // Build full URL using slug if available, otherwise ID
            $slug = isset($article['slug']) ? $article['slug'] : $article['id'];
            $url = "https://politiekpraat.nl/blog/" . $slug;
            
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
                'relevance_score' => (float)$article['relevance_score'],
                'reading_time' => $readingTime,
                'tags' => $tags
            ];
        }
        
        echo json_encode([
            'success' => true,
            'articles' => $processedArticles,
            'totalCount' => count($processedArticles)
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Zoeken mislukt: ' . $e->getMessage()
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Alleen POST requests toegestaan'
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