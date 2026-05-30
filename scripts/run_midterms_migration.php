<?php
/**
 * Migratie-runner voor de Midterms 2026 sectie.
 *
 * Maakt de midterms_* tabellen aan op basis van
 * database/migrations/create_midterms_tables.sql.
 *
 * Gebruik (CLI):  php scripts/run_midterms_migration.php
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

echo "Midterms 2026 - tabelmigratie\n";
echo "=============================\n\n";

$migrationPath = __DIR__ . '/../database/migrations/create_midterms_tables.sql';

if (!is_readable($migrationPath)) {
    echo "FOUT: migratiebestand niet leesbaar: {$migrationPath}\n";
    exit(1);
}

$sql = file_get_contents($migrationPath);
if ($sql === false) {
    echo "FOUT: kan migratiebestand niet lezen.\n";
    exit(1);
}

/**
 * Splits de SQL in losse statements. Comment-regels (-- ...) worden genegeerd.
 * Statements eindigen op een regel die met ';' afsluit.
 */
function midterms_split_sql(string $sql): array {
    $statements = [];
    $buffer = '';
    foreach (preg_split('/\r\n|\r|\n/', $sql) as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '--')) {
            continue;
        }
        $buffer .= $line . "\n";
        if (str_ends_with($trimmed, ';')) {
            $statements[] = trim($buffer);
            $buffer = '';
        }
    }
    if (trim($buffer) !== '') {
        $statements[] = trim($buffer);
    }
    return $statements;
}

try {
    $db = new Database();
    echo "Databaseverbinding OK.\n\n";

    $pdo = $db->getConnection();
    $statements = midterms_split_sql($sql);

    foreach ($statements as $statement) {
        // Toon kort welk object wordt aangemaakt
        if (preg_match('/CREATE TABLE IF NOT EXISTS\s+([a-z_]+)/i', $statement, $m)) {
            echo "-> Tabel aanmaken: {$m[1]}\n";
        } else {
            echo "-> Statement uitvoeren\n";
        }
        $pdo->exec($statement);
    }

    echo "\nVerificatie:\n";
    $tables = ['midterms_races', 'midterms_timeline', 'midterms_referenda', 'midterms_odds_cache', 'midterms_news_cache'];
    foreach ($tables as $table) {
        $db->query("SHOW TABLES LIKE :t");
        $db->bind(':t', $table);
        $exists = $db->single();
        echo ($exists ? "   OK   " : "  MIST ") . $table . "\n";
    }

    echo "\nMigratie voltooid.\n";
} catch (Throwable $e) {
    echo "FOUT bij migratie: " . $e->getMessage() . "\n";
    exit(1);
}
