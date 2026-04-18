<?php
/**
 * Run migratie voor oauth_personal_access_tokens tabel.
 * Idempotent: kan meerdere keren worden uitgevoerd.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

$db = new Database();

$sql = file_get_contents(__DIR__ . '/../database/migrations/create_oauth_personal_access_tokens.sql');
if ($sql === false) {
    fwrite(STDERR, "Kon migratie SQL niet lezen.\n");
    exit(1);
}

// Voer statements één voor één uit
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    static fn($s) => $s !== '' && !str_starts_with($s, '--')
);

foreach ($statements as $stmt) {
    try {
        $db->query($stmt);
        $db->execute();
        echo "[OK] " . substr($stmt, 0, 80) . "...\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "[FOUT] " . $e->getMessage() . "\n");
        fwrite(STDERR, "Statement: " . substr($stmt, 0, 200) . "\n");
        exit(1);
    }
}

echo "\nMigratie voltooid.\n";
