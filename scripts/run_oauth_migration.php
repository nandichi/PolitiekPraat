<?php
/**
 * Eenmalige migration runner voor de OAuth/OIDC tabellen.
 *
 * Uitvoeren:
 *   CLI:    php scripts/run_oauth_migration.php
 *   Web:    /scripts/run_oauth_migration.php?local_dev=true (alleen lokaal)
 *           of ingelogd als admin.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

$isCli = (PHP_SAPI === 'cli');

if (!$isCli) {
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
}

$db  = new Database();
$pdo = $db->getConnection();

$sqlFile = __DIR__ . '/../database/migrations/create_oauth_tables.sql';
if (!is_readable($sqlFile)) {
    echo "SQL file niet gevonden: {$sqlFile}\n";
    exit(1);
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Kan SQL bestand niet lezen.\n";
    exit(1);
}

// Strip SQL `--` comment lines (tot aan eol) voordat we splitsen op `;`.
$sqlNoComments = preg_replace('/^\s*--.*$/m', '', $sql);
$statements    = array_values(array_filter(
    array_map('trim', explode(';', $sqlNoComments)),
    static fn($s) => $s !== ''
));

echo "OAuth migration: " . count($statements) . " statement(s)\n";

foreach ($statements as $stmt) {
    try {
        $pdo->exec($stmt);
        $head = substr(preg_replace('/\s+/', ' ', $stmt), 0, 80);
        echo "  OK  {$head}\n";
    } catch (Throwable $e) {
        echo "  ERR " . $e->getMessage() . "\n";
    }
}

echo "Klaar.\n";
