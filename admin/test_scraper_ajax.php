<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../models/NewsModel.php';
require_once '../includes/NewsScraper.php';

// Test AJAX response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_start();
    
    try {
        $db = new Database();
        $newsModel = new NewsModel($db);
        $scraper = new NewsScraper($newsModel);
        
        ob_clean();
        
        // Test basic response
        $testResult = [
            'scraped_count' => 0,
            'errors' => [],
            'timestamp' => date('Y-m-d H:i:s'),
            'test' => true
        ];
        
        if (ob_get_length()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        echo json_encode($testResult);
        exit;
        
    } catch (Exception $e) {
        if (ob_get_length()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage()
        ]);
        exit;
    }
    
    ob_end_clean();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Scraper AJAX</title>
</head>
<body>
    <h1>Test Scraper AJAX Response</h1>
    <button onclick="testAjax()">Test AJAX Request</button>
    <div id="result"></div>

    <script>
        function testAjax() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'test=1'
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';
                
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON:', data);
                    document.getElementById('result').innerHTML += '<p>✅ JSON parsing succesvol</p>';
                } catch (e) {
                    console.error('JSON parse error:', e);
                    document.getElementById('result').innerHTML += '<p>❌ JSON parsing gefaald: ' + e.message + '</p>';
                }
            })
            .catch(error => {
                console.error('Request error:', error);
                document.getElementById('result').innerHTML = '<p>❌ Request error: ' + error.message + '</p>';
            });
        }
    </script>
</body>
</html> 