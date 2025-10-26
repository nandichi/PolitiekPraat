<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Alleen POST requests toestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Alleen POST requests toegestaan']);
    exit;
}

require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/ChatGPTAPI.php';

try {
    // Parse JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['action'])) {
        throw new Exception('Ongeldige request data');
    }
    
    $action = $input['action'];
    $db = new Database();
    $conn = $db->getConnection();
    $chatGPT = new ChatGPTAPI();
    
    $response = ['success' => false];
    
    switch ($action) {
        case 'start_conversation':
            $response = handleStartConversation($conn, $chatGPT, $input);
            break;
            
        case 'submit_answer':
            $response = handleSubmitAnswer($conn, $chatGPT, $input);
            break;
            
        case 'get_next_question':
            $response = handleGetNextQuestion($conn, $chatGPT, $input);
            break;
            
        case 'complete_conversation':
            $response = handleCompleteConversation($conn, $chatGPT, $input);
            break;
            
        case 'check_rate_limit':
            $response = checkRateLimit($conn);
            break;
            
        default:
            throw new Exception('Onbekende actie: ' . $action);
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);

// ========================================
// Handler Functions
// ========================================

/**
 * Start een nieuwe conversatie
 */
function handleStartConversation($conn, $chatGPT, $input) {
    // Check rate limiting
    $rateLimitCheck = checkRateLimit($conn);
    if (!$rateLimitCheck['allowed']) {
        return [
            'success' => false,
            'error' => 'Je kunt maar één gesprek per uur starten. Probeer het later opnieuw.',
            'rate_limited' => true
        ];
    }
    
    // Genereer unieke session ID
    $sessionId = bin2hex(random_bytes(16));
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $modePreference = $input['mode'] ?? 'adaptive';
    
    // Haal eerste 10 vragen uit database
    $coreTopics = ['Immigratie', 'Klimaat', 'Economie', 'Zorg', 'Onderwijs', 'Defensie', 'EU', 'Belastingen', 'Woningmarkt', 'Pensioenen'];
    $coreQuestions = fetchCoreQuestions($conn, $coreTopics, 10);
    
    if (count($coreQuestions) < 10) {
        return [
            'success' => false,
            'error' => 'Niet genoeg vragen beschikbaar in de database'
        ];
    }
    
    // Initialize conversatie state
    $conversationState = [
        'core_questions' => $coreQuestions,
        'total_questions' => 20,
        'mode_counts' => ['multiple_choice' => 0, 'open' => 0],
        'user_profile' => 'Beginnend profiel: geen data beschikbaar'
    ];
    
    // Sla sessie op in database
    $stmt = $conn->prepare("
        INSERT INTO politiek_gesprek_sessions 
        (session_id, user_id, conversation_state, current_question_index, answers, mode_preference, ip_address, user_agent)
        VALUES (?, ?, ?, 0, '[]', ?, ?, ?)
    ");
    $conversationStateJson = json_encode($conversationState);
    
    if (!$stmt->execute([$sessionId, $userId, $conversationStateJson, $modePreference, $ipAddress, $userAgent])) {
        throw new Exception('Fout bij het starten van de conversatie');
    }
    
    // Update rate limit
    updateRateLimit($conn, $ipAddress);
    
    // Genereer eerste vraag
    $firstQuestion = prepareFirstQuestion($coreQuestions[0], $chatGPT);
    
    return [
        'success' => true,
        'session_id' => $sessionId,
        'question' => $firstQuestion,
        'question_number' => 1,
        'total_questions' => 20
    ];
}

/**
 * Verwerk antwoord en haal volgende vraag op
 */
function handleSubmitAnswer($conn, $chatGPT, $input) {
    if (!isset($input['session_id']) || !isset($input['answer'])) {
        throw new Exception('Ontbrekende session_id of answer');
    }
    
    $sessionId = $input['session_id'];
    $answer = $input['answer'];
    $questionData = $input['question_data'] ?? [];
    
    // Haal sessie op
    $session = fetchSession($conn, $sessionId);
    if (!$session) {
        throw new Exception('Sessie niet gevonden');
    }
    
    $conversationState = json_decode($session['conversation_state'], true);
    $answers = json_decode($session['answers'], true);
    $currentIndex = $session['current_question_index'];
    
    // Analyseer antwoord als het open-ended is
    $processedAnswer = $answer;
    $analysis = null;
    
    if ($questionData['mode'] === 'open') {
        $analysisResult = $chatGPT->analyzeOpenAnswer(
            $questionData['question'],
            $answer,
            $questionData['topic']
        );
        
        if ($analysisResult['success']) {
            $analysisData = json_decode($analysisResult['content'], true);
            if ($analysisData) {
                $analysis = $analysisData;
                $processedAnswer = $analysisData['position'] ?? $answer;
            }
        }
    }
    
    // Sla antwoord op
    $answerEntry = [
        'question_id' => $questionData['id'] ?? null,
        'question' => $questionData['question'] ?? 'Onbekend',
        'answer' => $answer,
        'processed_answer' => $processedAnswer,
        'topic' => $questionData['topic'] ?? 'Algemeen',
        'mode' => $questionData['mode'] ?? 'multiple_choice',
        'analysis' => $analysis,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $answers[] = $answerEntry;
    $currentIndex++;
    
    // Update mode counts
    if (isset($questionData['mode'])) {
        if ($questionData['mode'] === 'multiple_choice') {
            $conversationState['mode_counts']['multiple_choice']++;
        } else {
            $conversationState['mode_counts']['open']++;
        }
    }
    
    // Update user profile
    $conversationState['user_profile'] = generateUserProfile($answers);
    
    // Update sessie
    $stmt = $conn->prepare("
        UPDATE politiek_gesprek_sessions 
        SET answers = ?, current_question_index = ?, conversation_state = ?
        WHERE session_id = ?
    ");
    $answersJson = json_encode($answers);
    $stateJson = json_encode($conversationState);
    $stmt->execute([$answersJson, $currentIndex, $stateJson, $sessionId]);
    
    // Check of we klaar zijn
    if ($currentIndex >= 20) {
        return [
            'success' => true,
            'completed' => true,
            'total_answers' => count($answers)
        ];
    }
    
    // Genereer volgende vraag
    $nextQuestion = generateNextQuestion($conn, $chatGPT, $currentIndex, $conversationState, $answers);
    
    return [
        'success' => true,
        'completed' => false,
        'question' => $nextQuestion,
        'question_number' => $currentIndex + 1,
        'total_questions' => 20
    ];
}

/**
 * Haal volgende vraag op
 */
function handleGetNextQuestion($conn, $chatGPT, $input) {
    if (!isset($input['session_id'])) {
        throw new Exception('Ontbrekende session_id');
    }
    
    $session = fetchSession($conn, $input['session_id']);
    if (!$session) {
        throw new Exception('Sessie niet gevonden');
    }
    
    $conversationState = json_decode($session['conversation_state'], true);
    $answers = json_decode($session['answers'], true);
    $currentIndex = $session['current_question_index'];
    
    $nextQuestion = generateNextQuestion($conn, $chatGPT, $currentIndex, $conversationState, $answers);
    
    return [
        'success' => true,
        'question' => $nextQuestion,
        'question_number' => $currentIndex + 1,
        'total_questions' => 20
    ];
}

/**
 * Rond conversatie af en bereken matches
 */
function handleCompleteConversation($conn, $chatGPT, $input) {
    if (!isset($input['session_id'])) {
        throw new Exception('Ontbrekende session_id');
    }
    
    $sessionId = $input['session_id'];
    $session = fetchSession($conn, $sessionId);
    
    if (!$session) {
        throw new Exception('Sessie niet gevonden');
    }
    
    $conversationState = json_decode($session['conversation_state'], true);
    $answers = json_decode($session['answers'], true);
    
    // Haal alle partijen en hun standpunten op
    $parties = fetchAllPartiesWithPositions($conn);
    
    // Bereken matches
    $matches = calculatePartyMatches($answers, $parties);
    
    // Sorteer op percentage
    usort($matches, function($a, $b) {
        return $b['percentage'] - $a['percentage'];
    });
    
    $topParty = $matches[0];
    $topMatches = array_slice($matches, 0, 5);
    
    // Genereer AI analyse
    $aiAnalysisResult = $chatGPT->generateFinalMatch(
        $answers,
        $parties,
        $conversationState['user_profile']
    );
    
    $aiAnalysis = '';
    if ($aiAnalysisResult['success']) {
        $aiAnalysis = $aiAnalysisResult['content'];
    }
    
    // Format analysis voor HTML
    $aiAnalysis = formatAnalysisForDisplay($aiAnalysis);
    
    // Sla resultaten op
    $userId = $session['user_id'];
    $stmt = $conn->prepare("
        INSERT INTO politiek_gesprek_results 
        (session_id, user_id, top_party_id, match_percentage, all_matches, ai_analysis, political_profile, conversation_summary)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $topPartyId = $topParty['id'];
    $matchPercentage = $topParty['percentage'];
    $allMatchesJson = json_encode($matches);
    $politicalProfile = $conversationState['user_profile'];
    $conversationSummary = json_encode([
        'total_questions' => count($answers),
        'mode_counts' => $conversationState['mode_counts']
    ]);
    
    $stmt->execute([
        $sessionId, $userId, $topPartyId, $matchPercentage, 
        $allMatchesJson, $aiAnalysis, $politicalProfile, $conversationSummary
    ]);
    
    // Update sessie als completed
    $stmt = $conn->prepare("UPDATE politiek_gesprek_sessions SET completed_at = NOW() WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    
    return [
        'success' => true,
        'results' => [
            'top_party' => $topParty,
            'all_matches' => $topMatches,
            'ai_analysis' => $aiAnalysis,
            'political_profile' => $politicalProfile,
            'total_answers' => count($answers)
        ]
    ];
}

// ========================================
// Helper Functions
// ========================================

/**
 * Haal core vragen uit database
 */
function fetchCoreQuestions($conn, $topics, $limit) {
    $query = "
        SELECT id, title, description, context, left_view, right_view
        FROM stemwijzer_questions
        WHERE is_active = 1
        ORDER BY RAND()
        LIMIT ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Bereid eerste vraag voor
 */
function prepareFirstQuestion($questionData, $chatGPT) {
    // Bepaal mode voor deze vraag
    $modeResult = $chatGPT->determineQuestionMode(
        $questionData['title'],
        'Algemeen',
        ['multiple_choice' => 0, 'open' => 0]
    );
    
    $mode = 'multiple_choice';
    $options = ['Eens', 'Neutraal', 'Oneens'];
    
    if ($modeResult['success']) {
        $modeData = json_decode($modeResult['content'], true);
        if ($modeData && isset($modeData['mode'])) {
            $mode = $modeData['mode'];
            if (isset($modeData['options']) && !empty($modeData['options'])) {
                $options = $modeData['options'];
            }
        }
    }
    
    return [
        'id' => $questionData['id'],
        'question' => $questionData['title'],
        'intro' => 'Laten we beginnen met een belangrijke vraag over de Nederlandse politiek.',
        'context' => $questionData['context'],
        'topic' => 'Algemeen',
        'mode' => $mode,
        'options' => $mode === 'multiple_choice' ? $options : []
    ];
}

/**
 * Genereer volgende vraag
 */
function generateNextQuestion($conn, $chatGPT, $currentIndex, $conversationState, $answers) {
    // Voor vragen 1-10: gebruik database
    if ($currentIndex < 10) {
        $questionData = $conversationState['core_questions'][$currentIndex];
        
        // Bepaal mode
        $modeResult = $chatGPT->determineQuestionMode(
            $questionData['title'],
            'Algemeen',
            $conversationState['mode_counts']
        );
        
        $mode = 'multiple_choice';
        $options = ['Eens', 'Neutraal', 'Oneens'];
        
        if ($modeResult['success']) {
            $modeData = json_decode($modeResult['content'], true);
            if ($modeData && isset($modeData['mode'])) {
                $mode = $modeData['mode'];
                if (isset($modeData['options']) && !empty($modeData['options'])) {
                    $options = $modeData['options'];
                }
            }
        }
        
        // Genereer intro
        $introResult = $chatGPT->generateQuestionIntro(
            $currentIndex + 1,
            $questionData['title'],
            $questionData['context']
        );
        
        $intro = 'Volgende vraag...';
        if ($introResult['success']) {
            $intro = trim($introResult['content']);
        }
        
        return [
            'id' => $questionData['id'],
            'question' => $questionData['title'],
            'intro' => $intro,
            'context' => $questionData['context'],
            'topic' => 'Algemeen',
            'mode' => $mode,
            'options' => $mode === 'multiple_choice' ? $options : [],
            'source' => 'database'
        ];
    }
    
    // Voor vragen 11-20: genereer met AI
    $questionResult = $chatGPT->generateChatQuestion(
        $currentIndex,
        $answers,
        $conversationState['user_profile']
    );
    
    if (!$questionResult['success']) {
        throw new Exception('Fout bij genereren van vraag: ' . ($questionResult['error'] ?? 'Onbekend'));
    }
    
    $generatedQuestion = json_decode($questionResult['content'], true);
    
    if (!$generatedQuestion || !isset($generatedQuestion['question'])) {
        throw new Exception('Ongeldige vraag gegenereerd');
    }
    
    // Bepaal mode
    $modeResult = $chatGPT->determineQuestionMode(
        $generatedQuestion['question'],
        $generatedQuestion['topic'] ?? 'Algemeen',
        $conversationState['mode_counts']
    );
    
    $mode = 'multiple_choice';
    $options = ['Eens', 'Neutraal', 'Oneens'];
    
    if ($modeResult['success']) {
        $modeData = json_decode($modeResult['content'], true);
        if ($modeData && isset($modeData['mode'])) {
            $mode = $modeData['mode'];
            if (isset($modeData['options']) && !empty($modeData['options'])) {
                $options = $modeData['options'];
            }
        }
    }
    
    // Genereer intro
    $introResult = $chatGPT->generateQuestionIntro(
        $currentIndex + 1,
        $generatedQuestion['question'],
        $generatedQuestion['context'] ?? ''
    );
    
    $intro = $generatedQuestion['follow_up_reason'] ?? 'Volgende vraag...';
    if ($introResult['success']) {
        $intro = trim($introResult['content']);
    }
    
    return [
        'id' => 'ai_' . ($currentIndex + 1),
        'question' => $generatedQuestion['question'],
        'intro' => $intro,
        'context' => $generatedQuestion['context'] ?? '',
        'topic' => $generatedQuestion['topic'] ?? 'Algemeen',
        'mode' => $mode,
        'options' => $mode === 'multiple_choice' ? $options : [],
        'source' => 'ai'
    ];
}

/**
 * Genereer gebruikersprofiel op basis van antwoorden
 */
function generateUserProfile($answers) {
    if (empty($answers)) {
        return 'Nog geen antwoorden beschikbaar';
    }
    
    $profile = "Gebruiker heeft " . count($answers) . " vragen beantwoord.\n\n";
    $profile .= "Recente standpunten:\n";
    
    $recentAnswers = array_slice($answers, -5);
    foreach ($recentAnswers as $answer) {
        $profile .= "- {$answer['topic']}: {$answer['processed_answer']}\n";
    }
    
    return $profile;
}

/**
 * Haal sessie op
 */
function fetchSession($conn, $sessionId) {
    $stmt = $conn->prepare("
        SELECT * FROM politiek_gesprek_sessions 
        WHERE session_id = ?
    ");
    $stmt->execute([$sessionId]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Haal alle partijen met hun standpunten op
 */
function fetchAllPartiesWithPositions($conn) {
    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.short_name, 
               COUNT(DISTINCT sp.question_id) as total_positions
        FROM stemwijzer_parties p
        LEFT JOIN stemwijzer_positions sp ON p.id = sp.party_id
        WHERE p.is_active = 1
        GROUP BY p.id
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $parties = [];
    foreach ($rows as $row) {
        $parties[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'current_seats' => 0 // Zou uit database moeten komen
        ];
    }
    
    return $parties;
}

/**
 * Bereken partij matches
 */
function calculatePartyMatches($answers, $parties) {
    global $conn;
    
    $matches = [];
    
    foreach ($parties as $party) {
        $agreement = 0;
        $totalWeight = 0;
        $agreementCount = 0;
        
        foreach ($answers as $answer) {
            $weight = 1; // Standaard gewicht
            $userPosition = normalizePosition($answer['processed_answer']);
            
            // Haal partij positie op uit database voor vragen uit database
            if (isset($answer['question_id']) && is_numeric($answer['question_id'])) {
                $stmt = $conn->prepare("
                    SELECT position 
                    FROM stemwijzer_positions 
                    WHERE party_id = ? AND question_id = ?
                ");
                $questionId = $answer['question_id'];
                $stmt->execute([$party['id'], $questionId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $partyPosition = $row['position'];
                    
                    // Bereken agreement
                    if ($userPosition === $partyPosition) {
                        $agreement += $weight * 2; // Volledig eens
                        $agreementCount++;
                    } elseif ($userPosition === 'neutraal' || $partyPosition === 'neutraal') {
                        $agreement += $weight * 1; // Gedeeltelijk eens
                    }
                    // Anders 0 punten (oneens)
                    
                    $totalWeight += $weight * 2;
                }
            } else {
                // Voor AI-gegenereerde vragen: schatting op basis van AI analyse
                if (isset($answer['analysis']['party_alignment'])) {
                    $alignedParties = $answer['analysis']['party_alignment'];
                    if (in_array($party['name'], $alignedParties) || in_array($party['short_name'], $alignedParties)) {
                        $agreement += $weight * 1.5;
                        $agreementCount++;
                    }
                    $totalWeight += $weight * 2;
                }
            }
        }
        
        $percentage = $totalWeight > 0 ? round(($agreement / $totalWeight) * 100, 1) : 50;
        
        // Voeg wat variatie toe om meer realistisch te zijn
        $percentage = min(100, max(0, $percentage + rand(-5, 5)));
        
        $matches[] = [
            'id' => $party['id'],
            'name' => $party['name'],
            'short_name' => $party['short_name'],
            'percentage' => $percentage,
            'agreement_count' => $agreementCount
        ];
    }
    
    return $matches;
}

/**
 * Normaliseer positie naar standaard formaat
 */
function normalizePosition($position) {
    $position = strtolower(trim($position));
    
    if (in_array($position, ['eens', 'ja', 'yes', 'akkoord'])) {
        return 'eens';
    } elseif (in_array($position, ['oneens', 'nee', 'no', 'niet akkoord'])) {
        return 'oneens';
    } else {
        return 'neutraal';
    }
}

/**
 * Check rate limiting
 */
function checkRateLimit($conn) {
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $stmt = $conn->prepare("
        SELECT * FROM politiek_gesprek_rate_limit
        WHERE ip_address = ? 
        AND last_conversation_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ");
    $stmt->execute([$ipAddress]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return [
            'allowed' => false,
            'message' => 'Rate limit bereikt'
        ];
    }
    
    return [
        'allowed' => true
    ];
}

/**
 * Update rate limit
 */
function updateRateLimit($conn, $ipAddress) {
    $stmt = $conn->prepare("
        INSERT INTO politiek_gesprek_rate_limit (ip_address, last_conversation_at, conversation_count)
        VALUES (?, NOW(), 1)
        ON DUPLICATE KEY UPDATE 
            last_conversation_at = NOW(),
            conversation_count = conversation_count + 1
    ");
    $stmt->execute([$ipAddress]);
}

/**
 * Format analyse voor display
 */
function formatAnalysisForDisplay($analysis) {
    // Replace markdown headers
    $analysis = preg_replace('/^## \*\*(.+?)\*\*$/m', '<h3 class="text-xl font-bold text-gray-800 mb-3 mt-6 first:mt-0">$1</h3>', $analysis);
    $analysis = preg_replace('/^## (.+)$/m', '<h3 class="text-xl font-bold text-gray-800 mb-3 mt-6 first:mt-0">$1</h3>', $analysis);
    $analysis = preg_replace('/^### (.+)$/m', '<h4 class="text-lg font-semibold text-gray-700 mb-2 mt-4">$1</h4>', $analysis);
    
    // Replace bold text
    $analysis = preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-semibold text-gray-800">$1</strong>', $analysis);
    
    // Replace bullet points
    $analysis = preg_replace('/^- (.+)$/m', '<div class="flex items-start space-x-2 mb-2"><span class="text-primary mt-1">•</span><span>$1</span></div>', $analysis);
    
    // Replace newlines
    $analysis = preg_replace('/\n\n/', '</p><p class="mb-4">', $analysis);
    $analysis = '<p class="mb-4">' . $analysis . '</p>';
    
    return $analysis;
}

