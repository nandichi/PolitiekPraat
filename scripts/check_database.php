<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

$db = new Database();

// Check aantal vragen
$db->query('SELECT COUNT(*) as count FROM stemwijzer_questions');
$questionCount = $db->single();
echo "Aantal vragen in database: " . $questionCount->count . "\n";

// Check aantal partijen
$db->query('SELECT COUNT(*) as count FROM stemwijzer_parties');
$partyCount = $db->single();
echo "Aantal partijen in database: " . $partyCount->count . "\n";

// Check aantal posities
$db->query('SELECT COUNT(*) as count FROM stemwijzer_positions');
$positionCount = $db->single();
echo "Aantal posities in database: " . $positionCount->count . "\n";

// Toon alle vragen met order_number
$db->query('SELECT id, title, order_number FROM stemwijzer_questions ORDER BY order_number');
$questions = $db->resultSet();

echo "\nAlle vragen:\n";
foreach ($questions as $question) {
    echo "ID: {$question->id}, Order: {$question->order_number}, Title: {$question->title}\n";
}
?> 