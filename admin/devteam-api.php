<?php
declare(strict_types=1);
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

header('Content-Type: application/json');
header('Cache-Control: no-cache');

$queue_file = __DIR__ . '/devteam-queue.json';
$state_file = __DIR__ . '/devteam-state.json';

function read_queue(string $path): array {
    if (!file_exists($path)) return ['commands' => []];
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : ['commands' => []];
}

function write_queue(string $path, array $data): void {
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'trigger':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'POST required']);
            exit;
        }
        $agent_id = $_POST['agent_id'] ?? '';
        $agent_name = $_POST['agent_name'] ?? '';
        if (!$agent_id) {
            echo json_encode(['error' => 'agent_id required']);
            exit;
        }
        $queue = read_queue($queue_file);
        $pending = array_filter($queue['commands'] ?? [], fn($c) => $c['agent_id'] === $agent_id && $c['status'] === 'pending');
        if (count($pending) > 0) {
            echo json_encode(['error' => 'agent_already_queued', 'msg' => 'Deze agent heeft al een wachtend commando.']);
            exit;
        }
        $cmd = [
            'id' => bin2hex(random_bytes(8)),
            'ts' => date('c'),
            'type' => 'trigger',
            'agent_id' => $agent_id,
            'agent_name' => $agent_name,
            'message' => null,
            'status' => 'pending',
            'response' => null,
        ];
        $queue['commands'][] = $cmd;
        if (count($queue['commands']) > 50) {
            $queue['commands'] = array_slice($queue['commands'], -50);
        }
        write_queue($queue_file, $queue);
        echo json_encode(['ok' => true, 'command' => $cmd]);
        break;

    case 'chat':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'POST required']);
            exit;
        }
        $agent_id = $_POST['agent_id'] ?? '';
        $agent_name = $_POST['agent_name'] ?? '';
        $message = trim($_POST['message'] ?? '');
        if (!$agent_id || !$message) {
            echo json_encode(['error' => 'agent_id and message required']);
            exit;
        }
        $queue = read_queue($queue_file);
        $cmd = [
            'id' => bin2hex(random_bytes(8)),
            'ts' => date('c'),
            'type' => 'chat',
            'agent_id' => $agent_id,
            'agent_name' => $agent_name,
            'message' => $message,
            'status' => 'pending',
            'response' => null,
        ];
        $queue['commands'][] = $cmd;
        if (count($queue['commands']) > 50) {
            $queue['commands'] = array_slice($queue['commands'], -50);
        }
        write_queue($queue_file, $queue);
        echo json_encode(['ok' => true, 'command' => $cmd]);
        break;

    case 'queue_status':
        $queue = read_queue($queue_file);
        echo json_encode($queue);
        break;

    case 'chat_history':
        $agent_id = $_GET['agent_id'] ?? '';
        $state = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $chats = $state['agent_chats'][$agent_id] ?? [];
        echo json_encode(['messages' => $chats]);
        break;

    default:
        echo json_encode(['error' => 'unknown action', 'available' => ['trigger', 'chat', 'queue_status', 'chat_history']]);
}
