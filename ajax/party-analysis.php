<?php
require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');
apply_cors_policy(['POST', 'OPTIONS'], ['Content-Type']);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once BASE_PATH . '/includes/error_bootstrap.php';
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/ChatGPTAPI.php';
require_once BASE_PATH . '/models/PartyModel.php';

if (!function_exists('party_analysis_error')) {
    function party_analysis_error(string $public_message, int $status_code = 400, ?Throwable $exception = null): void
    {
        if ($exception !== null) {
            error_log(sprintf(
                '[ajax/party-analysis] %s in %s:%d',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ));
        }

        http_response_code($status_code);
        echo json_encode([
            'success' => false,
            'error' => $public_message,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    party_analysis_error('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    party_analysis_error('Invalid JSON input');
}

if (!isset($input['type'], $input['partyKey'])) {
    party_analysis_error('Missing required fields: type, partyKey');
}

$type = (string) $input['type'];
$party_key = (string) $input['partyKey'];

try {
    $party_model = new PartyModel();
    $party = $party_model->getParty($party_key);

    if (!$party) {
        party_analysis_error('Party not found', 404);
    }

    $chat_gpt = new ChatGPTAPI();

    switch ($type) {
        case 'party':
            $result = $chat_gpt->analyzePartyProsAndCons($party['name'], $party);
            if (empty($result['success'])) {
                party_analysis_error('Analysis failed', 502);
            }

            echo json_encode([
                'success' => true,
                'type' => 'party',
                'party_name' => $party['name'],
                'content' => $result['content'],
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'leader':
            $result = $chat_gpt->analyzeLeaderProsAndCons(
                $party['leader'],
                $party['name'],
                $party['leader_info'],
                $party
            );

            if (empty($result['success'])) {
                party_analysis_error('Analysis failed', 502);
            }

            echo json_encode([
                'success' => true,
                'type' => 'leader',
                'leader_name' => $party['leader'],
                'party_name' => $party['name'],
                'content' => $result['content'],
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'voter_profile':
            $result = $chat_gpt->generateVoterProfile($party['name'], $party);
            if (empty($result['success'])) {
                party_analysis_error('Analysis failed', 502);
            }

            echo json_encode([
                'success' => true,
                'type' => 'voter_profile',
                'party_name' => $party['name'],
                'content' => $result['content'],
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'timeline':
            $result = $chat_gpt->generatePoliticalTimeline($party['name'], $party);
            if (empty($result['success'])) {
                party_analysis_error('Analysis failed', 502);
            }

            echo json_encode([
                'success' => true,
                'type' => 'timeline',
                'party_name' => $party['name'],
                'content' => $result['content'],
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'question':
            $question = trim((string) ($input['question'] ?? ''));
            if ($question === '') {
                party_analysis_error('Question field is required for Q&A');
            }

            $result = $chat_gpt->answerPartyQuestion($question, $party['name'], $party);
            if (empty($result['success'])) {
                party_analysis_error('Analysis failed', 502);
            }

            echo json_encode([
                'success' => true,
                'type' => 'question',
                'party_name' => $party['name'],
                'question' => $question,
                'content' => $result['content'],
            ], JSON_UNESCAPED_UNICODE);
            break;

        default:
            party_analysis_error('Invalid analysis type. Use: party, leader, voter_profile, timeline, or question');
    }
} catch (Throwable $exception) {
    party_analysis_error('Interne serverfout', 500, $exception);
}
