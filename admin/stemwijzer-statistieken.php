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
    $db->query("SELECT id, title, description, context, left_view, right_view, order_number FROM stemwijzer_questions ORDER BY order_number ASC");
    $questions = $db->resultSet() ?? [];
    
    // Populairste partijen uit resultaten (alleen nummer 1 partij per inzending)
    $db->query("SELECT results FROM stemwijzer_results WHERE results IS NOT NULL AND results != 'null'");
    $allResults = $db->resultSet() ?? [];
    
    $partyScores = [];
    foreach ($allResults as $result) {
        $results = json_decode($result->results, true);
        if ($results && is_array($results)) {
            // Vind de partij met de hoogste score (nummer 1)
            $topParty = null;
            $topScore = -1;
            
            foreach ($results as $partyName => $partyData) {
                if (isset($partyData['agreement']) && $partyData['agreement'] > $topScore) {
                    $topScore = $partyData['agreement'];
                    $topParty = $partyName;
                }
            }
            
            // Tel alleen de nummer 1 partij mee
            if ($topParty && $topScore >= 0) {
                if (!isset($partyScores[$topParty])) {
                    $partyScores[$topParty] = ['total_score' => 0, 'count' => 0];
                }
                $partyScores[$topParty]['total_score'] += $topScore;
                $partyScores[$topParty]['count']++;
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
    
    // Sorteer partijen op aantal keer nummer 1
    uasort($averagePartyScores, function($a, $b) {
        return $b['total_votes'] <=> $a['total_votes'];
    });
    
    // Uitgebreide gedragsanalyse
    $behaviorStats = [];
    
    // 1. Antwoordpatronen analyse
    $totalAnswers = 0;
    $answerDistribution = ['eens' => 0, 'oneens' => 0, 'neutraal' => 0];
    $questionCompleteness = [];
    
    foreach ($allAnswers as $result) {
        $answers = json_decode($result->answers, true);
        if ($answers && is_array($answers)) {
            foreach ($answers as $questionIndex => $answer) {
                $totalAnswers++;
                if (isset($answerDistribution[$answer])) {
                    $answerDistribution[$answer]++;
                }
                
                // Track welke vragen het meest worden beantwoord
                if (!isset($questionCompleteness[$questionIndex])) {
                    $questionCompleteness[$questionIndex] = 0;
                }
                $questionCompleteness[$questionIndex]++;
            }
        }
    }
    
    // 2. Score spreiding analyse
    $scoreSpreadStats = [];
    $closeRaces = 0; // Wanneer top 2 partijen minder dan 10% verschil hebben
    $dominantWins = 0; // Wanneer winnaar 20%+ voorsprong heeft
    
    foreach ($allResults as $result) {
        $results = json_decode($result->results, true);
        if ($results && is_array($results)) {
            $scores = [];
            foreach ($results as $party => $data) {
                if (isset($data['agreement'])) {
                    $scores[] = $data['agreement'];
                }
            }
            
            if (count($scores) >= 2) {
                rsort($scores); // Sorteer hoogste eerst
                $difference = $scores[0] - $scores[1];
                
                if ($difference < 10) {
                    $closeRaces++;
                } else if ($difference > 20) {
                    $dominantWins++;
                }
                
                $scoreSpreadStats[] = [
                    'highest' => $scores[0],
                    'lowest' => end($scores),
                    'spread' => $scores[0] - end($scores),
                    'top_difference' => $difference
                ];
            }
        }
    }
    
    // 3. Tijdspatroon analyse
    $db->query("
        SELECT 
            HOUR(completed_at) as hour,
            COUNT(*) as count
        FROM stemwijzer_results 
        WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY HOUR(completed_at)
        ORDER BY hour ASC
    ");
    $hourlyStats = $db->resultSet() ?? [];
    
    $db->query("
        SELECT 
            DAYOFWEEK(completed_at) as day_of_week,
            COUNT(*) as count
        FROM stemwijzer_results 
        WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DAYOFWEEK(completed_at)
        ORDER BY day_of_week ASC
    ");
    $weeklyStats = $db->resultSet() ?? [];
    
    // 4. Bereken gemiddelden
    $avgSpread = !empty($scoreSpreadStats) ? array_sum(array_column($scoreSpreadStats, 'spread')) / count($scoreSpreadStats) : 0;
    $avgTopDifference = !empty($scoreSpreadStats) ? array_sum(array_column($scoreSpreadStats, 'top_difference')) / count($scoreSpreadStats) : 0;
    
    // 5. Vraag populariteit - welke vragen krijgen meeste neutrale antwoorden
    $questionPolarization = [];
    $questionTotalCount = [];
    
    foreach ($questions as $question) {
        $questionIndex = $question->order_number - 1;
        if (isset($answerStats[$questionIndex])) {
            $stats = $answerStats[$questionIndex];
            $total = $stats['eens'] + $stats['oneens'] + $stats['neutraal'];
            
            if ($total > 0) {
                $neutralPercentage = ($stats['neutraal'] / $total) * 100;
                $strongOpinions = (($stats['eens'] + $stats['oneens']) / $total) * 100;
                
                $questionPolarization[] = [
                    'question' => $question,
                    'neutral_percentage' => $neutralPercentage,
                    'strong_opinions' => $strongOpinions,
                    'total_answers' => $total,
                    'stats' => $stats
                ];
            }
        }
    }
    
    // Sorteer vragen op neutrale antwoorden (meest neutrale eerst)
    usort($questionPolarization, function($a, $b) {
        return $b['neutral_percentage'] <=> $a['neutral_percentage'];
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
    
    // Nieuwe variabelen voor gedragsanalyse
    $totalAnswers = 0;
    $answerDistribution = ['eens' => 0, 'oneens' => 0, 'neutraal' => 0];
    $scoreSpreadStats = [];
    $closeRaces = 0;
    $dominantWins = 0;
    $avgSpread = 0;
    $avgTopDifference = 0;
    $hourlyStats = [];
    $weeklyStats = [];
    $questionPolarization = [];
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
                    <h2 class="text-xl font-bold text-gray-800">Meest Gekozen Partijen</h2>
                    <p class="text-sm text-gray-600 mt-1">Aantal keer dat partij als nummer 1 uit de stemwijzer kwam</p>
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
                                            <p class="text-xs text-gray-500">Gemiddeld <?= $data['average_score'] ?>% bij winst</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800"><?= $data['total_votes'] ?></p>
                                        <p class="text-xs text-gray-500">keer nummer 1</p>
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
        <div id="detailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
            <div class="relative p-4 w-full min-h-screen flex items-start justify-center pt-20 pb-20">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 w-full max-w-6xl overflow-hidden">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-primary-dark via-primary to-secondary px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white" id="modalTitle">Inzending Details</h3>
                                    <p class="text-white/80 text-sm">Gedetailleerde analyse van stemwijzer resultaten</p>
                                </div>
                            </div>
                            <button onclick="closeModal()" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white hover:bg-white/30 transition-all duration-300 group">
                                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="max-h-[80vh] overflow-y-auto">
                        <div class="p-6">
                            <div id="modalContent" class="space-y-6">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gedragsanalyse Secties -->
        
        <!-- Antwoordpatronen -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Algemene Antwoordverdeling -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Antwoordverdeling</h2>
                    <p class="text-sm text-gray-600 mt-1">Hoe mensen gemiddeld stemmen</p>
                </div>
                
                <div class="p-6">
                    <?php if ($totalAnswers > 0): ?>
                        <div class="space-y-4">
                            <?php 
                            $eensPerc = ($answerDistribution['eens'] / $totalAnswers) * 100;
                            $oneensPerc = ($answerDistribution['oneens'] / $totalAnswers) * 100;
                            $neutraalPerc = ($answerDistribution['neutraal'] / $totalAnswers) * 100;
                            ?>
                            
                            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                    <span class="font-medium text-gray-800">Eens</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-800"><?= number_format($eensPerc, 1) ?>%</div>
                                    <div class="text-xs text-gray-500"><?= number_format($answerDistribution['eens']) ?> antwoorden</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 bg-gray-500 rounded-full"></div>
                                    <span class="font-medium text-gray-800">Neutraal</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-800"><?= number_format($neutraalPerc, 1) ?>%</div>
                                    <div class="text-xs text-gray-500"><?= number_format($answerDistribution['neutraal']) ?> antwoorden</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                    <span class="font-medium text-gray-800">Oneens</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-800"><?= number_format($oneensPerc, 1) ?>%</div>
                                    <div class="text-xs text-gray-500"><?= number_format($answerDistribution['oneens']) ?> antwoorden</div>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600"><?= number_format($totalAnswers) ?></div>
                                    <div class="text-sm text-blue-700">Totaal beantwoorde vragen</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">Nog geen data beschikbaar</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Resultaat Competitiviteit -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Resultaat Competitiviteit</h2>
                    <p class="text-sm text-gray-600 mt-1">Hoe dicht liggen de partijen bij elkaar</p>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($scoreSpreadStats)): ?>
                        <div class="space-y-6">
                            <!-- Close Races vs Dominant Wins -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-orange-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-orange-600"><?= $closeRaces ?></div>
                                    <div class="text-sm text-orange-700">Spannende Races</div>
                                    <div class="text-xs text-gray-500 mt-1">&lt;10% verschil top 2</div>
                                </div>
                                
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600"><?= $dominantWins ?></div>
                                    <div class="text-sm text-blue-700">Duidelijke Winnaars</div>
                                    <div class="text-xs text-gray-500 mt-1">&gt;20% voorsprong</div>
                                </div>
                            </div>
                            
                            <!-- Gemiddelde statistieken -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm text-gray-700">Gemiddelde spreiding (hoogste - laagste)</span>
                                    <span class="font-bold text-gray-800"><?= number_format($avgSpread, 1) ?>%</span>
                                </div>
                                
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm text-gray-700">Gemiddeld verschil winnaar vs #2</span>
                                    <span class="font-bold text-gray-800"><?= number_format($avgTopDifference, 1) ?>%</span>
                                </div>
                                
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm text-gray-700">Percentage spannende races</span>
                                    <span class="font-bold text-gray-800"><?= count($scoreSpreadStats) > 0 ? number_format(($closeRaces / count($scoreSpreadStats)) * 100, 1) : 0 ?>%</span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">Nog geen data beschikbaar</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tijdspatronen -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Uurpatronen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Activiteit per Uur</h2>
                    <p class="text-sm text-gray-600 mt-1">Afgelopen 30 dagen</p>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($hourlyStats)): ?>
                        <div class="space-y-3">
                            <?php 
                            $maxHourly = max(array_map(function($stat) { return $stat->count; }, $hourlyStats));
                            foreach ($hourlyStats as $stat): 
                                $percentage = $maxHourly > 0 ? ($stat->count / $maxHourly) * 100 : 0;
                                $hour = sprintf('%02d:00', $stat->hour);
                            ?>
                                <div class="flex items-center">
                                    <div class="w-12 text-xs text-gray-600"><?= $hour ?></div>
                                    <div class="flex-1 mx-3">
                                        <div class="bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-2 rounded-full transition-all duration-500" 
                                                 style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="w-8 text-right text-xs font-medium text-gray-800"><?= $stat->count ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">Nog geen data beschikbaar</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Weekdagpatronen -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Activiteit per Weekdag</h2>
                    <p class="text-sm text-gray-600 mt-1">Afgelopen 30 dagen</p>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($weeklyStats)): ?>
                        <div class="space-y-4">
                            <?php 
                            $weekDays = ['', 'Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
                            $maxWeekly = max(array_map(function($stat) { return $stat->count; }, $weeklyStats));
                            foreach ($weeklyStats as $stat): 
                                $percentage = $maxWeekly > 0 ? ($stat->count / $maxWeekly) * 100 : 0;
                                $dayName = $weekDays[$stat->day_of_week] ?? 'Onbekend';
                                $isWeekend = in_array($stat->day_of_week, [1, 7]); // Zondag en Zaterdag
                            ?>
                                <div class="flex items-center justify-between p-3 rounded-lg <?= $isWeekend ? 'bg-blue-50' : 'bg-gray-50' ?>">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-20 text-sm <?= $isWeekend ? 'text-blue-700 font-medium' : 'text-gray-700' ?>">
                                            <?= $dayName ?>
                                        </div>
                                        <div class="flex-1 w-32">
                                            <div class="bg-gray-200 rounded-full h-3">
                                                <div class="<?= $isWeekend ? 'bg-gradient-to-r from-blue-400 to-cyan-500' : 'bg-gradient-to-r from-teal-400 to-cyan-500' ?> h-3 rounded-full transition-all duration-500" 
                                                     style="width: <?= $percentage ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold <?= $isWeekend ? 'text-blue-600' : 'text-gray-800' ?>"><?= $stat->count ?></div>
                                        <?php if ($isWeekend): ?>
                                            <div class="text-xs text-blue-500">weekend</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">Nog geen data beschikbaar</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Vraag Analyse -->
        <?php if (!empty($questionPolarization)): ?>
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Vraag Polarisatie</h2>
                <p class="text-sm text-gray-600 mt-1">Welke vragen zorgen voor de meeste neutrale antwoorden vs sterke meningen</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Meest Neutrale Vragen -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Meest Neutrale Vragen</h3>
                        <div class="space-y-3">
                            <?php foreach (array_slice($questionPolarization, 0, 5) as $index => $data): ?>
                                <div class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1 pr-4">
                                            <p class="text-sm font-medium text-gray-800 line-clamp-2">
                                                <?= htmlspecialchars($data['question']->title) ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                <?= number_format($data['neutral_percentage'], 1) ?>% neutraal
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2 text-xs">
                                        <span class="text-green-600"><?= $data['stats']['eens'] ?> eens</span>
                                        <span class="text-gray-500"><?= $data['stats']['neutraal'] ?> neutraal</span>
                                        <span class="text-red-600"><?= $data['stats']['oneens'] ?> oneens</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Meest Polariserende Vragen -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Meest Polariserende Vragen</h3>
                        <div class="space-y-3">
                            <?php 
                            // Sorteer op laagste neutrale percentage (meest polariserend)
                            $polarizing = array_slice(array_reverse($questionPolarization), 0, 5);
                            foreach ($polarizing as $index => $data): 
                            ?>
                                <div class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1 pr-4">
                                            <p class="text-sm font-medium text-gray-800 line-clamp-2">
                                                <?= htmlspecialchars($data['question']->title) ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <?= number_format($data['strong_opinions'], 1) ?>% sterke mening
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2 text-xs">
                                        <span class="text-green-600"><?= $data['stats']['eens'] ?> eens</span>
                                        <span class="text-gray-500"><?= $data['stats']['neutraal'] ?> neutraal</span>
                                        <span class="text-red-600"><?= $data['stats']['oneens'] ?> oneens</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Gemiddelde Polarisatie Score -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="text-center">
                        <?php 
                        $avgNeutral = !empty($questionPolarization) ? 
                            array_sum(array_column($questionPolarization, 'neutral_percentage')) / count($questionPolarization) : 0;
                        ?>
                        <div class="text-2xl font-bold text-blue-600"><?= number_format($avgNeutral, 1) ?>%</div>
                        <div class="text-sm text-blue-700">Gemiddeld percentage neutrale antwoorden</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Lagere percentages betekenen meer polariserende vragen
                        </div>
                    </div>
                </div>
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
    
    document.getElementById('modalTitle').textContent = `Inzending #${result.id}`;
    
    let answersHtml = '';
    let resultsHtml = '';
    
    // Parse antwoorden
    if (result.answers && result.answers !== 'null') {
        try {
            const answers = JSON.parse(result.answers);
            if (Array.isArray(answers) || typeof answers === 'object') {
                answersHtml = `
                                         <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl border border-primary/20 overflow-hidden">
                         <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white">Antwoorden per Vraag</h4>
                                <span class="bg-white/20 px-3 py-1 rounded-full text-white text-sm font-medium">
                                    ${Object.keys(answers).length} vragen beantwoord
                                </span>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                `;
                
                Object.entries(answers).forEach(([questionIndex, answer]) => {
                    const questionNum = parseInt(questionIndex) + 1;
                    const question = questionsData.find(q => q.order_number == questionNum);
                    
                    if (question) {
                        const answerConfig = {
                            'eens': {
                                bg: 'bg-gradient-to-r from-green-50 to-emerald-50',
                                border: 'border-green-200',
                                badge: 'bg-green-500 text-white',
                                icon: '👍',
                                label: 'Eens'
                            },
                            'oneens': {
                                bg: 'bg-gradient-to-r from-red-50 to-pink-50',
                                border: 'border-red-200',
                                badge: 'bg-red-500 text-white',
                                icon: '👎',
                                label: 'Oneens'
                            },
                            'neutraal': {
                                bg: 'bg-gradient-to-r from-gray-50 to-slate-50',
                                border: 'border-gray-200',
                                badge: 'bg-gray-500 text-white',
                                icon: '🤷‍♂️',
                                label: 'Neutraal'
                            }
                        };
                        
                        const config = answerConfig[answer] || answerConfig['neutraal'];
                        
                        answersHtml += `
                            <div class="group ${config.bg} ${config.border} border rounded-2xl p-6 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                                                                 <div class="w-10 h-10 bg-white/80 rounded-xl flex items-center justify-center font-bold text-primary text-sm">
                                            ${questionNum}
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-800 text-lg">${question.title}</h5>
                                            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-sm font-medium ${config.badge} mt-2">
                                                <span>${config.icon}</span>
                                                <span>${config.label}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/70 rounded-xl p-4 mb-4 border border-white/50">
                                    <p class="text-gray-700 leading-relaxed">${question.description}</p>
                                </div>
                                
                                ${question.context ? `
                                    <div class="bg-white/50 rounded-xl p-4 mb-4 border border-white/30">
                                        <h6 class="font-semibold text-gray-800 mb-2 flex items-center">
                                                                                         <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Context
                                        </h6>
                                        <p class="text-gray-600 text-sm leading-relaxed">${question.context}</p>
                                    </div>
                                ` : ''}
                                
                                ${(question.left_view || question.right_view) ? `
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        ${question.left_view ? `
                                                                                         <div class="bg-primary/5 rounded-xl p-4 border border-primary/20">
                                                 <h6 class="font-semibold text-primary mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1.586z"/>
                                                    </svg>
                                                    Perspectief Pro
                                                </h6>
                                                                                                 <p class="text-primary/70 text-sm leading-relaxed">${question.left_view}</p>
                                            </div>
                                        ` : ''}
                                        ${question.right_view ? `
                                                                                         <div class="bg-secondary/5 rounded-xl p-4 border border-secondary/20">
                                                 <h6 class="font-semibold text-secondary mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"/>
                                                    </svg>
                                                    Perspectief Contra
                                                </h6>
                                                                                                 <p class="text-secondary/70 text-sm leading-relaxed">${question.right_view}</p>
                                            </div>
                                        ` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }
                });
                answersHtml += '</div></div>';
            }
        } catch (e) {
            answersHtml = `
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-800">Fout bij het laden van antwoorden</h4>
                            <p class="text-yellow-700 text-sm">De antwoorddata kon niet worden verwerkt</p>
                        </div>
                    </div>
                </div>
            `;
        }
    } else {
        answersHtml = `
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-gray-700 mb-2">Geen antwoorden beschikbaar</h4>
                <p class="text-gray-500 text-sm">Deze inzending bevat geen antwoorddata</p>
            </div>
        `;
    }
    
    // Parse resultaten
    if (result.results && result.results !== 'null') {
        try {
            const results = JSON.parse(result.results);
            if (typeof results === 'object') {
                const sortedResults = Object.entries(results).sort((a, b) => b[1].agreement - a[1].agreement);
                
                resultsHtml = `
                                         <div class="bg-gradient-to-br from-secondary/5 to-secondary/10 rounded-2xl border border-secondary/20 overflow-hidden">
                         <div class="bg-gradient-to-r from-secondary-dark to-secondary px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white">Partij Resultaten</h4>
                                <span class="bg-white/20 px-3 py-1 rounded-full text-white text-sm font-medium">
                                    ${sortedResults.length} partijen
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid gap-3">
                `;
                
                sortedResults.forEach(([party, data], index) => {
                    const percentage = data.agreement || 0;
                    const isWinner = index === 0;
                    const medalEmoji = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
                    
                    resultsHtml += `
                        <div class="group ${isWinner ? 'bg-gradient-to-r from-yellow-50 to-amber-50 border-yellow-300' : 'bg-white border-gray-200'} border rounded-xl p-4 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 ${isWinner ? 'bg-gradient-to-r from-yellow-400 to-amber-500' : 'bg-gradient-to-r from-gray-400 to-gray-500'} rounded-xl flex items-center justify-center text-white font-bold">
                                            ${index + 1}
                                        </div>
                                        ${medalEmoji ? `<span class="absolute -top-1 -right-1 text-lg">${medalEmoji}</span>` : ''}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-gray-800 text-lg">${party}</h5>
                                        <p class="text-gray-500 text-sm">
                                            ${index === 0 ? 'Beste match' : index === 1 ? 'Tweede keuze' : index === 2 ? 'Derde keuze' : `${index + 1}e plaats`}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold ${isWinner ? 'text-yellow-600' : 'text-gray-700'}">${percentage.toFixed(1)}%</div>
                                    <div class="w-20 h-2 bg-gray-200 rounded-full mt-2">
                                        <div class="${isWinner ? 'bg-gradient-to-r from-yellow-400 to-amber-500' : 'bg-gradient-to-r from-gray-400 to-gray-500'} h-2 rounded-full transition-all duration-500" 
                                             style="width: ${Math.max(5, percentage)}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                resultsHtml += '</div></div></div>';
            }
        } catch (e) {
            resultsHtml = `
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-800">Fout bij het laden van resultaten</h4>
                            <p class="text-red-700 text-sm">De resultaatdata kon niet worden verwerkt</p>
                        </div>
                    </div>
                </div>
            `;
        }
    } else {
        resultsHtml = `
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-gray-700 mb-2">Geen resultaten beschikbaar</h4>
                <p class="text-gray-500 text-sm">Deze inzending bevat geen resultaatdata</p>
            </div>
        `;
    }
    
    // Basis informatie
    const basicInfoHtml = `
                          <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl border border-primary/20 overflow-hidden">
             <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-4">
                 <div class="flex items-center space-x-3">
                     <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                         <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                         </svg>
                     </div>
                     <h4 class="text-xl font-bold text-white">Basis Informatie</h4>
                 </div>
             </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/70 rounded-xl p-4 border border-white/50">
                        <div class="flex items-center space-x-3 mb-2">
                                                         <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                 <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">Inzending ID</span>
                        </div>
                        <p class="text-xl font-bold text-gray-800">#${result.id}</p>
                    </div>
                    
                    <div class="bg-white/70 rounded-xl p-4 border border-white/50">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">Voltooid op</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-700">${new Date(result.completed_at).toLocaleString('nl-NL')}</p>
                    </div>
                    
                    <div class="bg-white/70 rounded-xl p-4 border border-white/50">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">Session ID</span>
                        </div>
                        <p class="font-mono text-sm text-gray-700 break-all">${result.session_id}</p>
                    </div>
                    
                    <div class="bg-white/70 rounded-xl p-4 border border-white/50">
                        <div class="flex items-center space-x-3 mb-2">
                                                         <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                 <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">IP Adres</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">${result.ip_address}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = basicInfoHtml + answersHtml + resultsHtml;
    document.getElementById('detailModal').classList.remove('hidden');
    
    // Scroll to top and focus modal
    setTimeout(() => {
        document.getElementById('detailModal').scrollTop = 0;
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }, 100);
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restore background scrolling
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('detailModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 