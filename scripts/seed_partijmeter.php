<?php
/**
 * Seed-runner voor de PartijMeter 2026.
 *
 * Vult partijmeter_questions en partijmeter_positions met de centrale dataset
 * uit includes/data/partijmeter_dataset.php (git-tracked bron van waarheid).
 *
 * Idempotent: draait alleen als de tabel leeg is. Gebruik --force (CLI) of
 * ?force=true (web) om de bestaande stellingen/standpunten te wissen en
 * opnieuw te seeden. Let op: --force overschrijft handmatige admin-aanpassingen.
 *
 * Uitvoeren:
 *   CLI:    php scripts/seed_partijmeter.php [--force]
 *   Web:    /scripts/seed_partijmeter.php?local_dev=true[&force=true]
 *           of ingelogd als admin.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/data/partijmeter_dataset.php';

$isCli = (PHP_SAPI === 'cli');
$force = false;

if ($isCli) {
    $force = in_array('--force', $argv ?? [], true);
} else {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $isLocal = ($_SERVER['SERVER_NAME'] ?? '') === 'localhost'
        || ($_SERVER['SERVER_ADDR'] ?? '') === '127.0.0.1'
        || ($_SERVER['REMOTE_ADDR'] ?? '') === '127.0.0.1';
    $isAdmin = !empty($_SESSION['is_admin']);
    $bypass = isset($_GET['local_dev']) && $_GET['local_dev'] === 'true' && $isLocal;
    if (!$isAdmin && !$bypass) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
    header('Content-Type: text/plain; charset=UTF-8');
    $force = isset($_GET['force']) && $_GET['force'] === 'true';
}

$db = new Database();

// Controleer of de tabellen bestaan.
try {
    $db->query('SELECT COUNT(*) AS c FROM partijmeter_questions');
    $existing = (int) ($db->single()->c ?? 0);
} catch (Throwable $e) {
    echo "Tabellen ontbreken. Draai eerst scripts/run_partijmeter_migration.php\n";
    exit(1);
}

if ($existing > 0 && !$force) {
    echo "Er staan al {$existing} stellingen in de database. Gebruik --force (CLI) of ?force=true om te overschrijven.\n";
    exit(0);
}

$data = pp_partijmeter_dataset();
$questions = $data['questions'];

$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    if ($force && $existing > 0) {
        // CASCADE ruimt de standpunten op, maar we wissen expliciet voor de zekerheid.
        $pdo->exec('DELETE FROM partijmeter_positions');
        $pdo->exec('DELETE FROM partijmeter_questions');
        echo "Bestaande stellingen en standpunten gewist (--force).\n";
    }

    $insertQuestion = $pdo->prepare(
        'INSERT INTO partijmeter_questions (theme, title, explanation, axis_economic, axis_cultural, order_number, is_active)
         VALUES (:theme, :title, :explanation, :axis_economic, :axis_cultural, :order_number, 1)'
    );
    $insertPosition = $pdo->prepare(
        'INSERT INTO partijmeter_positions (question_id, party_key, position, explanation, source_url)
         VALUES (:question_id, :party_key, :position, :explanation, :source_url)'
    );

    $order = 1;
    $countQuestions = 0;
    $countPositions = 0;

    foreach ($questions as $q) {
        $insertQuestion->execute([
            ':theme' => $q['theme'],
            ':title' => $q['title'],
            ':explanation' => $q['explanation'] ?? null,
            ':axis_economic' => (int) ($q['axis_economic'] ?? 0),
            ':axis_cultural' => (int) ($q['axis_cultural'] ?? 0),
            ':order_number' => $order,
        ]);
        $questionId = (int) $pdo->lastInsertId();
        $countQuestions++;

        $source = $q['source'] ?? null;
        foreach ($q['positions'] as $partyKey => $pos) {
            $insertPosition->execute([
                ':question_id' => $questionId,
                ':party_key' => $partyKey,
                ':position' => $pos['p'],
                ':explanation' => $pos['e'] ?? null,
                ':source_url' => $source,
            ]);
            $countPositions++;
        }

        echo "  + [{$order}] {$q['title']}\n";
        $order++;
    }

    $pdo->commit();
    echo "\nKlaar: {$countQuestions} stellingen en {$countPositions} standpunten geseed.\n";
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "FOUT bij seeden: " . $e->getMessage() . "\n";
    exit(1);
}
