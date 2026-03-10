<?php
declare(strict_types=1);
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    http_response_code(401);
    header('Content-Type: application/json');
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
    return is_array($data) && isset($data['commands']) ? $data : ['commands' => []];
}

function write_queue(string $path, array $data): void {
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'trigger':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'POST required']);
            exit;
        }
        $agent_id = trim($_POST['agent_id'] ?? '');
        $agent_name = trim($_POST['agent_name'] ?? '');
        if ($agent_id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'agent_id required']);
            exit;
        }
        $queue = read_queue($queue_file);
        $pending = array_filter($queue['commands'], fn($c) => $c['agent_id'] === $agent_id && $c['status'] === 'pending');
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
        $queue['commands'] = array_slice($queue['commands'], -50);
        write_queue($queue_file, $queue);
        echo json_encode(['ok' => true, 'command' => $cmd]);
        break;

    case 'chat':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'POST required']);
            exit;
        }
        $agent_id = trim($_POST['agent_id'] ?? '');
        $agent_name = trim($_POST['agent_name'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if ($agent_id === '' || $message === '') {
            http_response_code(400);
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
            'message' => mb_substr($message, 0, 2000),
            'status' => 'pending',
            'response' => null,
        ];
        $queue['commands'][] = $cmd;
        $queue['commands'] = array_slice($queue['commands'], -50);
        write_queue($queue_file, $queue);

        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $chats = $st['agent_chats'][$agent_id] ?? [];
        $chats[] = ['ts' => date('c'), 'from' => 'user', 'msg' => mb_substr($message, 0, 500)];
        $st['agent_chats'][$agent_id] = array_slice($chats, -20);
        file_put_contents($state_file, json_encode($st, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

        echo json_encode(['ok' => true, 'command' => $cmd]);
        break;

    case 'queue_status':
        echo json_encode(read_queue($queue_file));
        break;

    case 'chat_history':
        $agent_id = trim($_GET['agent_id'] ?? '');
        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $chats = $st['agent_chats'][$agent_id] ?? [];
        echo json_encode(['messages' => $chats]);
        break;

    case 'orchestrate':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'POST required']);
            exit;
        }
        $message = trim($_POST['message'] ?? '');
        if ($message === '') {
            http_response_code(400);
            echo json_encode(['error' => 'message required']);
            exit;
        }
        $queue = read_queue($queue_file);
        $has_pending = count(array_filter($queue['commands'], fn($c) => $c['agent_id'] === 'commander' && in_array($c['status'], ['pending', 'running']))) > 0;
        if ($has_pending) {
            echo json_encode(['error' => 'commander_busy', 'msg' => 'Commander is al bezig met een opdracht.']);
            exit;
        }
        $cmd = [
            'id' => bin2hex(random_bytes(8)),
            'ts' => date('c'),
            'type' => 'orchestrate',
            'agent_id' => 'commander',
            'agent_name' => 'Commander',
            'message' => mb_substr($message, 0, 3000),
            'status' => 'pending',
            'response' => null,
        ];
        $queue['commands'][] = $cmd;
        $queue['commands'] = array_slice($queue['commands'], -50);
        write_queue($queue_file, $queue);

        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $chats = $st['commander_chats'] ?? [];
        $chats[] = ['ts' => date('c'), 'from' => 'user', 'msg' => mb_substr($message, 0, 1500)];
        $st['commander_chats'] = array_slice($chats, -30);
        file_put_contents($state_file, json_encode($st, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

        echo json_encode(['ok' => true, 'command' => $cmd]);
        break;

    case 'commander_history':
        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        echo json_encode([
            'messages' => $st['commander_chats'] ?? [],
            'workflows' => array_slice($st['workflows'] ?? [], -5),
        ]);
        break;

    case 'workflow_status':
        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $wfs = $st['workflows'] ?? [];
        $active = array_values(array_filter($wfs, fn($w) => $w['status'] === 'running'));
        $recent = array_slice(array_filter($wfs, fn($w) => $w['status'] !== 'running'), -5);
        echo json_encode(['active' => $active, 'recent' => array_values($recent)]);
        break;

    case 'full_state':
        $st = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : [];
        $q = read_queue($queue_file);
        echo json_encode([
            'state' => $st,
            'queue' => $q,
            'server_time' => date('c'),
        ], JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'unknown action', 'available' => ['trigger', 'chat', 'orchestrate', 'commander_history', 'workflow_status', 'queue_status', 'chat_history', 'full_state']]);
}
