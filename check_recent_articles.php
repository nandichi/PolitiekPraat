<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'models/NewsModel.php';

echo "=== Recente Artikelen Check ===\n\n";

try {
    $db = new Database();
    $newsModel = new NewsModel($db);
    
    // Haal laatste 10 artikelen op
    $recent_articles = $newsModel->getAllNews(10);
    
    echo "Laatste 10 artikelen:\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($recent_articles as $article) {
        echo "Titel: " . $article['title'] . "\n";
        echo "Bron: " . $article['source'] . " (" . $article['orientation'] . ")\n";
        echo "Gepubliceerd: " . $article['publishedAt'] . "\n";
        echo "URL: " . $article['url'] . "\n";
        echo str_repeat("-", 80) . "\n";
    }
    
    // Statistieken per bron
    echo "\n=== Artikelen per Bron ===\n";
    $result = $db->directQuery("SELECT source, COUNT(*) as count, MAX(published_at) as laatste FROM news_articles GROUP BY source ORDER BY count DESC");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['source'] . ": " . $row['count'] . " artikelen (laatste: " . $row['laatste'] . ")\n";
    }
    
    // Artikelen van vandaag
    echo "\n=== Artikelen van Vandaag ===\n";
    $today = date('Y-m-d');
    $result = $db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE DATE(published_at) = '$today'");
    $today_count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Artikelen van vandaag ($today): $today_count\n";
    
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage() . "\n";
}
?> 