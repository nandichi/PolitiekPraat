<?php
// Test bestand voor de PolitiekPraat API
// Gebruik dit bestand om de API endpoints te testen

require_once dirname(__DIR__) . '/includes/config.php';

class APITester {
    private $baseUrl;
    
    public function __construct() {
        $this->baseUrl = URLROOT . '/api';
    }
    
    public function testBlogsAPI() {
        echo "=== Testing Blogs API ===\n";
        
        // Test 1: Alle blogs ophalen
        echo "1. Testing GET /api/blogs.php\n";
        $response = $this->makeRequest($this->baseUrl . '/blogs.php', 'GET');
        $this->printResponse($response);
        
        // Test 2: Specifieke blog ophalen (als er blogs zijn)
        if (isset($response['data']) && !empty($response['data'])) {
            $firstBlogId = $response['data'][0]['id'];
            echo "\n2. Testing GET /api/blogs.php?id={$firstBlogId}\n";
            $response = $this->makeRequest($this->baseUrl . '/blogs.php?id=' . $firstBlogId, 'GET');
            $this->printResponse($response);
        }
        
        echo "\n";
    }
    
    public function testAuthAPI() {
        echo "=== Testing Auth API ===\n";
        
        // Test 1: Registreren van nieuwe gebruiker
        echo "1. Testing POST /api/auth.php?action=register\n";
        $testUser = [
            'username' => 'testuser_' . time(),
            'email' => 'test_' . time() . '@example.com',
            'password' => 'testpassword123'
        ];
        
        $response = $this->makeRequest($this->baseUrl . '/auth.php?action=register', 'POST', $testUser);
        $this->printResponse($response);
        
        $token = null;
        if ($response['success']) {
            $token = $response['data']['token'];
        }
        
        // Test 2: Inloggen
        echo "\n2. Testing POST /api/auth.php?action=login\n";
        $loginData = [
            'email' => $testUser['email'],
            'password' => $testUser['password']
        ];
        
        $response = $this->makeRequest($this->baseUrl . '/auth.php?action=login', 'POST', $loginData);
        $this->printResponse($response);
        
        if ($response['success']) {
            $token = $response['data']['token'];
        }
        
        // Test 3: Token verifiëren
        if ($token) {
            echo "\n3. Testing POST /api/auth.php?action=verify\n";
            $response = $this->makeRequest($this->baseUrl . '/auth.php?action=verify', 'POST', [], $token);
            $this->printResponse($response);
        }
        
        echo "\n";
    }
    
    public function testErrorHandling() {
        echo "=== Testing Error Handling ===\n";
        
        // Test 1: Ongeldige blog ID
        echo "1. Testing GET /api/blogs.php?id=999999\n";
        $response = $this->makeRequest($this->baseUrl . '/blogs.php?id=999999', 'GET');
        $this->printResponse($response);
        
        // Test 2: Ongeldige login
        echo "\n2. Testing POST /api/auth.php?action=login (invalid credentials)\n";
        $invalidData = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ];
        
        $response = $this->makeRequest($this->baseUrl . '/auth.php?action=login', 'POST', $invalidData);
        $this->printResponse($response);
        
        // Test 3: Ontbrekende parameters
        echo "\n3. Testing POST /api/auth.php?action=login (missing parameters)\n";
        $incompleteData = [
            'email' => 'test@example.com'
            // password ontbreekt
        ];
        
        $response = $this->makeRequest($this->baseUrl . '/auth.php?action=login', 'POST', $incompleteData);
        $this->printResponse($response);
        
        // Test 4: Ongeldige HTTP methode
        echo "\n4. Testing POST /api/blogs.php (wrong method)\n";
        $response = $this->makeRequest($this->baseUrl . '/blogs.php', 'POST');
        $this->printResponse($response);
        
        echo "\n";
    }
    
    private function makeRequest($url, $method = 'GET', $data = null, $token = null) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ]);
        }
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'raw_response' => $response
        ];
    }
    
    private function printResponse($result) {
        echo "HTTP Code: " . $result['http_code'] . "\n";
        
        if ($result['response']) {
            echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        } else {
            echo "Raw Response: " . $result['raw_response'] . "\n";
        }
    }
    
    public function runAllTests() {
        echo "PolitiekPraat API Test Suite\n";
        echo "============================\n\n";
        
        $this->testBlogsAPI();
        $this->testAuthAPI();
        $this->testErrorHandling();
        
        echo "Test suite completed!\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $tester = new APITester();
    $tester->runAllTests();
} 