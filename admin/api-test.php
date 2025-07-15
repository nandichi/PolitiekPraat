<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.api-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.api-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.response-box {
    background: #1a1a1a;
    color: #e5e5e5;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

.status-success { color: #22c55e; }
.status-error { color: #ef4444; }
.status-pending { color: #f59e0b; }

.loading-spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">API Tester</h1>
                    <p class="text-blue-100 text-lg">Test alle API endpoints van PolitiekPraat</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Terug naar Dashboard
                    </a>
                    <button onclick="testAllApis()" 
                            class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold">
                        Test Alle API's
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10 pb-12">
        
        <!-- Stemwijzer API Tests -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🔵 Stemwijzer API (Bestaand)</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Parties API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Partijen API</h3>
                        <button onclick="testStemwijzerApi('parties')" 
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/stemwijzer.php?action=parties</p>
                    <div id="parties-status" class="mb-2"></div>
                    <div id="parties-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- Questions API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Vragen API</h3>
                        <button onclick="testStemwijzerApi('questions')" 
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/stemwijzer.php?action=questions</p>
                    <div id="questions-status" class="mb-2"></div>
                    <div id="questions-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- Stats API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Statistieken API</h3>
                        <button onclick="testStemwijzerApi('stats')" 
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/stemwijzer.php?action=stats</p>
                    <div id="stats-status" class="mb-2"></div>
                    <div id="stats-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- Save Results API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Resultaten Opslaan</h3>
                        <button onclick="testSaveResults()" 
                                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">POST /api/stemwijzer.php?action=save-results</p>
                    <div id="save-results-status" class="mb-2"></div>
                    <div id="save-results-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>
            </div>
        </div>

        <!-- REST API Tests -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🟢 REST API (Nieuw)</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Auth Login API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Login API</h3>
                        <button onclick="testRestApi('auth/login', 'POST')" 
                                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">POST /api/auth/login</p>
                    <div id="auth-login-status" class="mb-2"></div>
                    <div id="auth-login-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- Blogs API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Blogs API</h3>
                        <button onclick="testRestApi('blogs', 'GET')" 
                                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/blogs</p>
                    <div id="blogs-status" class="mb-2"></div>
                    <div id="blogs-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- News API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">News API</h3>
                        <button onclick="testRestApi('news', 'GET')" 
                                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/news</p>
                    <div id="news-status" class="mb-2"></div>
                    <div id="news-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>

                <!-- User API -->
                <div class="api-card rounded-2xl p-6 border border-white/50 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">User API</h3>
                        <button onclick="testRestApi('user/profile', 'GET')" 
                                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                            Test
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">GET /api/user/profile</p>
                    <div id="user-profile-status" class="mb-2"></div>
                    <div id="user-profile-response" class="response-box rounded-lg p-4 text-sm overflow-auto max-h-64 hidden"></div>
                </div>
            </div>
        </div>

        <!-- Test Summary -->
        <div class="api-card rounded-2xl p-8 border border-white/50 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Test Samenvatting</h2>
            <div id="test-summary" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600" id="passed-count">0</div>
                    <div class="text-sm text-gray-600">Geslaagd</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600" id="failed-count">0</div>
                    <div class="text-sm text-gray-600">Mislukt</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600" id="pending-count">0</div>
                    <div class="text-sm text-gray-600">Wachtend</div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let testResults = {
    passed: 0,
    failed: 0,
    pending: 0
};

// Test Stemwijzer API
async function testStemwijzerApi(action) {
    const statusDiv = document.getElementById(`${action}-status`);
    const responseDiv = document.getElementById(`${action}-response`);
    
    statusDiv.innerHTML = '<div class="status-pending flex items-center"><div class="loading-spinner mr-2"></div>Testing...</div>';
    responseDiv.classList.add('hidden');
    
    try {
        const response = await fetch(`https://politiekpraat.nl/api/stemwijzer.php?action=${action}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.text();
        
        if (response.ok && data.trim() !== '') {
            statusDiv.innerHTML = '<div class="status-success">✅ Success - Response ontvangen</div>';
            try {
                const jsonData = JSON.parse(data);
                responseDiv.innerHTML = JSON.stringify(jsonData, null, 2);
                testResults.passed++;
            } catch(e) {
                responseDiv.innerHTML = data;
                testResults.passed++;
            }
        } else {
            statusDiv.innerHTML = '<div class="status-error">❌ Error - Geen response of lege response</div>';
            responseDiv.innerHTML = `Status: ${response.status}\nResponse: ${data}`;
            testResults.failed++;
        }
        
        responseDiv.classList.remove('hidden');
        
    } catch (error) {
        statusDiv.innerHTML = '<div class="status-error">❌ Error - Network error</div>';
        responseDiv.innerHTML = `Error: ${error.message}`;
        responseDiv.classList.remove('hidden');
        testResults.failed++;
    }
    
    updateTestSummary();
}

// Test Save Results specifically
async function testSaveResults() {
    const statusDiv = document.getElementById('save-results-status');
    const responseDiv = document.getElementById('save-results-response');
    
    statusDiv.innerHTML = '<div class="status-pending flex items-center"><div class="loading-spinner mr-2"></div>Testing...</div>';
    responseDiv.classList.add('hidden');
    
    const testData = {
        sessionId: `test_api_${Date.now()}`,
        answers: {
            "0": "eens",
            "1": "neutraal", 
            "2": "oneens"
        },
        results: {
            "VVD": {"score": 2, "total": 3, "agreement": 67},
            "PVV": {"score": 1, "total": 3, "agreement": 33}
        }
    };
    
    try {
        const response = await fetch('https://politiekpraat.nl/api/stemwijzer.php?action=save-results', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(testData)
        });
        
        const data = await response.text();
        
        if (response.ok && data.trim() !== '') {
            statusDiv.innerHTML = '<div class="status-success">✅ Success - Resultaten opgeslagen</div>';
            try {
                const jsonData = JSON.parse(data);
                responseDiv.innerHTML = JSON.stringify(jsonData, null, 2);
                testResults.passed++;
            } catch(e) {
                responseDiv.innerHTML = data;
                testResults.passed++;
            }
        } else {
            statusDiv.innerHTML = '<div class="status-error">❌ Error - Kan niet opslaan</div>';
            responseDiv.innerHTML = `Status: ${response.status}\nResponse: ${data}`;
            testResults.failed++;
        }
        
        responseDiv.classList.remove('hidden');
        
    } catch (error) {
        statusDiv.innerHTML = '<div class="status-error">❌ Error - Network error</div>';
        responseDiv.innerHTML = `Error: ${error.message}`;
        responseDiv.classList.remove('hidden');
        testResults.failed++;
    }
    
    updateTestSummary();
}

// Test REST API
async function testRestApi(endpoint, method = 'GET') {
    const statusId = endpoint.replace('/', '-');
    const statusDiv = document.getElementById(`${statusId}-status`);
    const responseDiv = document.getElementById(`${statusId}-response`);
    
    if (!statusDiv || !responseDiv) return;
    
    statusDiv.innerHTML = '<div class="status-pending flex items-center"><div class="loading-spinner mr-2"></div>Testing...</div>';
    responseDiv.classList.add('hidden');
    
    let options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    // Add test data for POST requests
    if (method === 'POST' && endpoint === 'auth/login') {
        options.body = JSON.stringify({
            email: 'test@example.com',
            password: 'test123'
        });
    }
    
    try {
        const response = await fetch(`https://politiekpraat.nl/api/${endpoint}`, options);
        const data = await response.text();
        
        if (response.status === 404) {
            statusDiv.innerHTML = '<div class="status-error">❌ Not Found - API nog niet gedeployed</div>';
            responseDiv.innerHTML = 'REST API endpoints zijn nog niet beschikbaar op de live server.';
            testResults.failed++;
        } else if (response.ok && data.trim() !== '') {
            statusDiv.innerHTML = '<div class="status-success">✅ Success - API beschikbaar</div>';
            try {
                const jsonData = JSON.parse(data);
                responseDiv.innerHTML = JSON.stringify(jsonData, null, 2);
                testResults.passed++;
            } catch(e) {
                responseDiv.innerHTML = data;
                testResults.passed++;
            }
        } else {
            statusDiv.innerHTML = '<div class="status-error">❌ Error - Onverwachte response</div>';
            responseDiv.innerHTML = `Status: ${response.status}\nResponse: ${data}`;
            testResults.failed++;
        }
        
        responseDiv.classList.remove('hidden');
        
    } catch (error) {
        statusDiv.innerHTML = '<div class="status-error">❌ Error - Network error</div>';
        responseDiv.innerHTML = `Error: ${error.message}`;
        responseDiv.classList.remove('hidden');
        testResults.failed++;
    }
    
    updateTestSummary();
}

// Test all APIs
async function testAllApis() {
    // Reset counters
    testResults = { passed: 0, failed: 0, pending: 0 };
    updateTestSummary();
    
    // Test Stemwijzer APIs
    await testStemwijzerApi('parties');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testStemwijzerApi('questions');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testStemwijzerApi('stats');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testSaveResults();
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Test REST APIs
    await testRestApi('auth/login', 'POST');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testRestApi('blogs', 'GET');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testRestApi('news', 'GET');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    await testRestApi('user/profile', 'GET');
}

function updateTestSummary() {
    document.getElementById('passed-count').textContent = testResults.passed;
    document.getElementById('failed-count').textContent = testResults.failed;
    document.getElementById('pending-count').textContent = testResults.pending;
}

// Initialize test summary
updateTestSummary();
</script>

<?php require_once '../views/templates/footer.php'; ?> 