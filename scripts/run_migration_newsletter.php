<?php
// Set the base path
$basePath = dirname(__DIR__);

// Include necessary files
require_once $basePath . '/includes/config.php';
require_once $basePath . '/includes/Database.php';

// Connect to the database
$db = new Database();

// Load and execute the migration
$migrationPath = $basePath . '/database/migrations/create_newsletter_subscribers_table.sql';
$migrationContent = file_get_contents($migrationPath);

if ($migrationContent !== false) {
    try {
        // Execute migration
        $db->getConnection()->exec($migrationContent);
        echo "Newsletter subscribers table migration executed successfully.\n";
    } catch (PDOException $e) {
        echo "Error executing migration: " . $e->getMessage() . "\n";
    }
} else {
    echo "Could not read migration file at: $migrationPath\n";
} 