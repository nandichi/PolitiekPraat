<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/StemwijzerController.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$stemwijzerController = new StemwijzerController();

// Haal uitgebreide statistieken op
try {
    $db = new Database();
    
    // Basis statistieken
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_results");
    $totalResults = $db->single()->count ?? 0;
    
    // Inzendingen vandaag
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_results WHERE DATE(completed_at) = CURDATE()");
    $todayResults = $db->single()->count ?? 0;
    
    // Inzendingen deze week
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_results WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $weekResults = $db->single()->count ?? 0;
    
    // Inzendingen deze maand
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_results WHERE MONTH(completed_at) = MONTH(NOW()) AND YEAR(completed_at) = YEAR(NOW())");
    $monthResults = $db->single()->count ?? 0;
    
    // Inzendingen met paginering en filtering (standaard laatste 50)
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 50;
    $offset = ($page - 1) * $limit;
    
    // Filter parameters
    $searchTerm = $_GET['search'] ?? '';
    $dateFrom = $_GET['date_from'] ?? '';
    $dateTo = $_GET['date_to'] ?? '';
    
    // Bouw WHERE clause
    $whereConditions = [];
    $params = [];
    
    if (!empty($searchTerm)) {
        $whereConditions[] = "(session_id LIKE ? OR ip_address LIKE ?)";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
    }
    
    if (!empty($dateFrom)) {
        $whereConditions[] = "DATE(completed_at) >= ?";
        $params[] = $dateFrom;
    }
    
    if (!empty($dateTo)) {
        $whereConditions[] = "DATE(completed_at) <= ?";
        $params[] = $dateTo;
    }
    
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    // Totaal aantal records met filters
    $countQuery = "SELECT COUNT(*) as total FROM stemwijzer_results $whereClause";
    if (!empty($params)) {
        $db->query($countQuery, $params);
    } else {
        $db->query($countQuery);
    }
    $totalRecords = $db->single()->total ?? 0;
    $totalPages = ceil($totalRecords / $limit);
    
    // Haal gefilterde resultaten op
    $dataQuery = "SELECT id, session_id, completed_at, ip_address, answers, results FROM stemwijzer_results $whereClause ORDER BY completed_at DESC LIMIT $limit OFFSET $offset";
    if (!empty($params)) {
        $db->query($dataQuery, $params);
    } else {
        $db->query($dataQuery);
    }
    $recentResults = $db->resultSet() ?? [];
    
    // Statistieken per dag van afgelopen 7 dagen
    $db->query("
        SELECT 
            DATE(completed_at) as date,
            COUNT(*) as count
        FROM stemwijzer_results 
        WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(completed_at)
        ORDER BY DATE(completed_at) ASC
    ");
    $dailyStats = $db->resultSet() ?? [];
    
    // Populairste antwoorden per vraag (top 3 antwoorden)
    $db->query("SELECT answers FROM stemwijzer_results WHERE answers IS NOT NULL AND answers != 'null'");
    $allAnswers = $db->resultSet() ?? [];
    
    // Verwerk antwoorden statistieken
    $answerStats = [];
    foreach ($allAnswers as $result) {
        $answers = json_decode($result->answers, true);
        if ($answers && is_array($answers)) {
            foreach ($answers as $questionIndex => $answer) {
                if (!isset($answerStats[$questionIndex])) {
                    $answerStats[$questionIndex] = ['eens' => 0, 'oneens' => 0, 'neutraal' => 0];
                }
                if (isset($answerStats[$questionIndex][$answer])) {
                    $answerStats[$questionIndex][$answer]++;
                }
            }
        }
    }
    
    // Haal vragen op voor display
    $db->query("SELECT id, title, order_number FROM stemwijzer_questions ORDER BY order_number ASC");
    $questions = $db->resultSet() ?? [];
    
    // Populairste partijen uit resultaten
    $db->query("SELECT results FROM stemwijzer_results WHERE results IS NOT NULL AND results != 'null'");
    $allResults = $db->resultSet() ?? [];
    
    $partyScores = [];
    foreach ($allResults as $result) {
        $results = json_decode($result->results, true);
        if ($results && is_array($results)) {
            foreach ($results as $partyName => $partyData) {
                if (!isset($partyScores[$partyName])) {
                    $partyScores[$partyName] = ['total_score' => 0, 'count' => 0];
                }
                if (isset($partyData['agreement'])) {
                    $partyScores[$partyName]['total_score'] += $partyData['agreement'];
                    $partyScores[$partyName]['count']++;
                }
            }
        }
    }
    
    // Bereken gemiddelde scores per partij
    $averagePartyScores = [];
    foreach ($partyScores as $party => $data) {
        if ($data['count'] > 0) {
            $averagePartyScores[$party] = [
                'average_score' => round($data['total_score'] / $data['count'], 1),
                'total_votes' => $data['count']
            ];
        }
    }
    
    // Sorteer partijen op gemiddelde score
    uasort($averagePartyScores, function($a, $b) {
        return $b['average_score'] <=> $a['average_score'];
    });
    
} catch (Exception $e) {
    $totalResults = 0;
    $todayResults = 0;
    $weekResults = 0;
    $monthResults = 0;
    $recentResults = [];
    $dailyStats = [];
    $answerStats = [];
    $questions = [];
    $averagePartyScores = [];
    $totalRecords = 0;
    $totalPages = 1;
    $searchTerm = '';
    $dateFrom = '';
    $dateTo = '';
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

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.progress-bar {
    transition: width 0.3s ease-in-out;
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Stemwijzer Statistieken</h1>
                    <p class="text-blue-100 text-lg">Inzichten in hoe mensen de stemwijzer hebben ingevuld</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        ← Terug naar Dashboard
                    </a>
                    <a href="export-stemwijzer.php" 
                       class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition-all duration-300 font-semibold">
                        Exporteer CSV
                    </a>
                    <button onclick="window.location.reload()" 
                            class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold">
                        Vernieuwen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Inzendingen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalResults ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Vandaag</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $todayResults ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Deze Week</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $weekResults ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Deze Maand</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $monthResults ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Daily Statistics Chart -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Inzendingen Afgelopen 7 Dagen</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($dailyStats)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen data beschikbaar</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php 
                            $maxCount = max(array_map(function($stat) { return $stat->count; }, $dailyStats));
                            foreach ($dailyStats as $stat): 
                                $percentage = $maxCount > 0 ? ($stat->count / $maxCount) * 100 : 0;
                            ?>
                                <div class="flex items-center justify-between">
                                    <div class="w-24 text-sm text-gray-600">
                                        <?= formatDate($stat->date) ?>
                                    </div>
                                    <div class="flex-1 mx-4">
                                        <div class="bg-gray-200 rounded-full h-3">
                                            <div class="progress-bar bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full" 
                                                 style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="w-12 text-right text-sm font-semibold text-gray-800">
                                        <?= $stat->count ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top Parties -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Hoogste Gemiddelde Scores</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($averagePartyScores)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen resultaten beschikbaar</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php 
                            $index = 0;
                            foreach (array_slice($averagePartyScores, 0, 8) as $party => $data): 
                                $index++;
                            ?>
                                <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                            <?= $index ?>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($party) ?></p>
                                            <p class="text-xs text-gray-500"><?= $data['total_votes'] ?> keer gekozen</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800"><?= $data['average_score'] ?>%</p>
                                        <p class="text-xs text-gray-500">gemiddeld</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Detailed Submissions -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Gedetailleerde Inzendingen</h2>
                    <div class="flex items-center space-x-4">
                        <select id="limitSelect" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25 per pagina</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 per pagina</option>
                            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100 per pagina</option>
                        </select>
                        <span class="text-sm text-gray-600">
                            Totaal: <?= $totalRecords ?> inzendingen
                        </span>
                    </div>
                </div>
                
                <!-- Filter sectie -->
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" 
                               placeholder="Session ID of IP adres..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Van datum</label>
                        <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tot datum</label>
                        <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <div class="flex space-x-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">
                            Filter
                        </button>
                        <a href="stemwijzer-statistieken.php" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-sm font-medium transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="p-6">
                <?php if (empty($recentResults)): ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <p class="text-gray-600">Nog geen inzendingen</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-600 border-b border-gray-200">
                                    <th class="pb-3 font-semibold">ID</th>
                                    <th class="pb-3 font-semibold">Session</th>
                                    <th class="pb-3 font-semibold">Tijdstip</th>
                                    <th class="pb-3 font-semibold">IP Adres</th>
                                    <th class="pb-3 font-semibold">Top Partij</th>
                                    <th class="pb-3 font-semibold">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($recentResults as $result): 
                                    // Verwerk resultaten om top partij te vinden
                                    $topParty = 'Onbekend';
                                    $topScore = 0;
                                    if ($result->results && $result->results != 'null') {
                                        $results = json_decode($result->results, true);
                                        if (is_array($results)) {
                                            foreach ($results as $party => $data) {
                                                if (isset($data['agreement']) && $data['agreement'] > $topScore) {
                                                    $topScore = $data['agreement'];
                                                    $topParty = $party;
                                                }
                                            }
                                        }
                                    }
                                ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 text-sm font-medium">#<?= $result->id ?></td>
                                        <td class="py-3 text-sm text-gray-600">
                                            <span class="font-mono text-xs"><?= substr($result->session_id, 0, 12) ?>...</span>
                                        </td>
                                        <td class="py-3 text-sm"><?= date('d-m-Y H:i', strtotime($result->completed_at)) ?></td>
                                        <td class="py-3 text-sm text-gray-600"><?= htmlspecialchars($result->ip_address) ?></td>
                                        <td class="py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= htmlspecialchars($topParty) ?> (<?= round($topScore, 1) ?>%)
                                            </span>
                                        </td>
                                        <td class="py-3 text-sm">
                                            <button onclick="showDetails(<?= $result->id ?>)" 
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Pagina <?= $page ?> van <?= $totalPages ?>
                            </div>
                            <div class="flex space-x-2">
                                <?php if ($page > 1): ?>
                                    <a href="?<?= $queryParams ?>&page=<?= $page - 1 ?>" 
                                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Vorige
                                    </a>
                                <?php endif; ?>
                                
                                <?php 
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                $queryParams = http_build_query(array_merge($_GET, ['page' => '']));
                                for ($i = $startPage; $i <= $endPage; $i++): 
                                ?>
                                    <a href="?<?= $queryParams ?>&page=<?= $i ?>" 
                                       class="px-3 py-2 text-sm font-medium <?= $i == $page ? 'text-indigo-600 bg-indigo-50 border-indigo-500' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' ?> border rounded-md">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <a href="?<?= $queryParams ?>&page=<?= $page + 1 ?>" 
                                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Volgende
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Inzending Details</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent" class="space-y-6">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Answer Statistics -->
        <?php if (!empty($answerStats) && !empty($questions)): ?>
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Antwoord Verdeling per Vraag</h2>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <?php foreach (array_slice($questions, 0, 5) as $question): 
                        if (isset($answerStats[$question->order_number - 1])):
                            $stats = $answerStats[$question->order_number - 1];
                            $total = $stats['eens'] + $stats['oneens'] + $stats['neutraal'];
                    ?>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <h3 class="font-semibold text-gray-800 mb-3"><?= htmlspecialchars($question->title) ?></h3>
                            
                            <?php if ($total > 0): ?>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="bg-green-100 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-green-600"><?= $stats['eens'] ?></div>
                                            <div class="text-sm text-green-700">Eens</div>
                                            <div class="text-xs text-gray-500"><?= round(($stats['eens'] / $total) * 100, 1) ?>%</div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="bg-gray-100 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-gray-600"><?= $stats['neutraal'] ?></div>
                                            <div class="text-sm text-gray-700">Neutraal</div>
                                            <div class="text-xs text-gray-500"><?= round(($stats['neutraal'] / $total) * 100, 1) ?>%</div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="bg-red-100 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-red-600"><?= $stats['oneens'] ?></div>
                                            <div class="text-sm text-red-700">Oneens</div>
                                            <div class="text-xs text-gray-500"><?= round(($stats['oneens'] / $total) * 100, 1) ?>%</div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm">Nog geen antwoorden voor deze vraag</p>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
                
                <?php if (count($questions) > 5): ?>
                    <div class="mt-6 text-center">
                        <p class="text-gray-600 text-sm">
                            Toont de eerste 5 vragen van <?= count($questions) ?> totaal
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters
    const counters = document.querySelectorAll('.text-3xl.font-bold');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        if (target > 0) {
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 50);
        }
    });
    
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
    
    // Handle limit change
    document.getElementById('limitSelect').addEventListener('change', function() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('limit', this.value);
        currentUrl.searchParams.set('page', '1'); // Reset to first page
        window.location.href = currentUrl.toString();
    });
});

// Results data for modal
const resultsData = <?= json_encode($recentResults) ?>;
const questionsData = <?= json_encode($questions) ?>;

function showDetails(resultId) {
    const result = resultsData.find(r => r.id == resultId);
    if (!result) return;
    
    document.getElementById('modalTitle').textContent = `Inzending #${result.id} Details`;
    
    let answersHtml = '';
    let resultsHtml = '';
    
    // Parse antwoorden
    if (result.answers && result.answers !== 'null') {
        try {
            const answers = JSON.parse(result.answers);
            if (Array.isArray(answers) || typeof answers === 'object') {
                answersHtml = '<div class="bg-gray-50 rounded-lg p-4"><h4 class="font-semibold mb-3 text-gray-800">Antwoorden per Vraag</h4><div class="space-y-2">';
                
                Object.entries(answers).forEach(([questionIndex, answer]) => {
                    const questionNum = parseInt(questionIndex) + 1;
                    const question = questionsData.find(q => q.order_number == questionNum);
                    const questionTitle = question ? question.title : `Vraag ${questionNum}`;
                    
                    const answerClass = answer === 'eens' ? 'bg-green-100 text-green-800' : 
                                      answer === 'oneens' ? 'bg-red-100 text-red-800' : 
                                      'bg-gray-100 text-gray-800';
                    
                    answersHtml += `
                        <div class="flex justify-between items-center py-2 px-3 bg-white rounded border">
                            <span class="text-sm text-gray-700">${questionTitle}</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${answerClass}">${answer}</span>
                        </div>
                    `;
                });
                answersHtml += '</div></div>';
            }
        } catch (e) {
            answersHtml = '<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"><p class="text-yellow-800">Fout bij het laden van antwoorden</p></div>';
        }
    } else {
        answersHtml = '<div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-600">Geen antwoorden beschikbaar</p></div>';
    }
    
    // Parse resultaten
    if (result.results && result.results !== 'null') {
        try {
            const results = JSON.parse(result.results);
            if (typeof results === 'object') {
                const sortedResults = Object.entries(results).sort((a, b) => b[1].agreement - a[1].agreement);
                
                resultsHtml = '<div class="bg-gray-50 rounded-lg p-4"><h4 class="font-semibold mb-3 text-gray-800">Partij Scores</h4><div class="space-y-2">';
                
                sortedResults.forEach(([party, data], index) => {
                    const percentage = data.agreement || 0;
                    const bgColor = index === 0 ? 'bg-gradient-to-r from-blue-500 to-indigo-600' : 'bg-gray-300';
                    const textColor = index === 0 ? 'text-white' : 'text-gray-700';
                    
                    resultsHtml += `
                        <div class="flex items-center justify-between py-2 px-3 bg-white rounded border">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 ${bgColor} rounded-lg flex items-center justify-center ${textColor} text-xs font-bold">
                                    ${index + 1}
                                </div>
                                <span class="text-sm font-medium text-gray-800">${party}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700">${percentage.toFixed(1)}%</span>
                        </div>
                    `;
                });
                resultsHtml += '</div></div>';
            }
        } catch (e) {
            resultsHtml = '<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"><p class="text-yellow-800">Fout bij het laden van resultaten</p></div>';
        }
    } else {
        resultsHtml = '<div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-600">Geen resultaten beschikbaar</p></div>';
    }
    
    // Basis informatie
    const basicInfoHtml = `
        <div class="bg-blue-50 rounded-lg p-4">
            <h4 class="font-semibold mb-3 text-gray-800">Basis Informatie</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-600">Session ID:</span>
                    <p class="font-mono text-sm">${result.session_id}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600">IP Adres:</span>
                    <p class="text-sm">${result.ip_address}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Voltooid op:</span>
                    <p class="text-sm">${new Date(result.completed_at).toLocaleString('nl-NL')}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Inzending ID:</span>
                    <p class="text-sm font-semibold">#${result.id}</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = basicInfoHtml + answersHtml + resultsHtml;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 