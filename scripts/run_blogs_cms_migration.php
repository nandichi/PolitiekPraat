<?php
/**
 * Migration runner voor blogs CMS-uitbreidingen (status, scheduled_for,
 * SEO-velden, tags, excerpt, updated_at, reading_time + indexen).
 *
 * Uitvoeren:
 *   CLI:    php scripts/run_blogs_cms_migration.php
 *   Web:    /scripts/run_blogs_cms_migration.php?local_dev=true (alleen lokaal)
 *           of ingelogd als admin.
 *
 * Idempotent via `ADD COLUMN IF NOT EXISTS` (MariaDB 10.0.2+).
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
    $bypass  = isset($_GET['local_dev']) && $_GET['local_dev'] === 'true' && $isLocal;
    if (!$isAdmin && !$bypass) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
    header('Content-Type: text/plain; charset=UTF-8');
}

$db  = new Database();
$pdo = $db->getConnection();

$sqlFile = __DIR__ . '/../database/migrations/blogs_cms_extensions.sql';
if (!is_readable($sqlFile)) {
    echo "SQL file niet gevonden: {$sqlFile}\n";
    exit(1);
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Kan SQL bestand niet lezen.\n";
    exit(1);
}

// Strip `--` comments voordat we splitsen op `;`.
$sqlNoComments = preg_replace('/^\s*--.*$/m', '', $sql);
$statements    = array_values(array_filter(
    array_map('trim', explode(';', $sqlNoComments)),
    static fn($s) => $s !== ''
));

echo "Blogs CMS migration: " . count($statements) . " statement(s)\n";

foreach ($statements as $stmt) {
    try {
        $pdo->exec($stmt);
        $head = substr(preg_replace('/\s+/', ' ', $stmt), 0, 80);
        echo "  OK  {$head}\n";
    } catch (Throwable $e) {
        $msg = $e->getMessage();
        // Op oudere MariaDB (< 10.5.3 voor indexen) bestaat `IF NOT EXISTS`
        // voor indexen niet. We negeren "duplicate" fouten expliciet.
        if (stripos($msg, 'duplicate') !== false || stripos($msg, 'exists') !== false) {
            echo "  SKIP {$msg}\n";
        } else {
            echo "  ERR {$msg}\n";
        }
    }
}

// ----- Indexen toevoegen (handmatig om MariaDB < 10.5.3 te ondersteunen) -----

$indexes = [
    'idx_blogs_status_published' => 'CREATE INDEX idx_blogs_status_published ON blogs (status, published_at)',
    'idx_blogs_status_scheduled' => 'CREATE INDEX idx_blogs_status_scheduled ON blogs (status, scheduled_for)',
];

foreach ($indexes as $name => $createSql) {
    try {
        $stmt = $pdo->prepare("SHOW INDEX FROM blogs WHERE Key_name = :name");
        $stmt->execute([':name' => $name]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            echo "  SKIP index {$name} bestaat al\n";
            continue;
        }
        $pdo->exec($createSql);
        echo "  OK   index {$name}\n";
    } catch (Throwable $e) {
        echo "  ERR  index {$name}: " . $e->getMessage() . "\n";
    }
}

echo "Klaar.\n";
