<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

$db = new Database();

echo "Creating stemwijzer database tables...\n\n";

try {
    // 1. Create stemwijzer_parties table
    $db->query("CREATE TABLE IF NOT EXISTS stemwijzer_parties (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        short_name VARCHAR(50) NOT NULL,
        logo_url VARCHAR(500) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_name (name),
        UNIQUE KEY unique_short_name (short_name)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemwijzer_parties\n";
    } else {
        echo "✗ Failed to create table: stemwijzer_parties\n";
    }

    // 2. Create stemwijzer_questions table
    $db->query("CREATE TABLE IF NOT EXISTS stemwijzer_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        context TEXT DEFAULT NULL,
        left_view TEXT DEFAULT NULL,
        right_view TEXT DEFAULT NULL,
        order_number INT NOT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_order (order_number),
        INDEX idx_active (is_active),
        INDEX idx_order (order_number)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemwijzer_questions\n";
    } else {
        echo "✗ Failed to create table: stemwijzer_questions\n";
    }

    // 3. Create stemwijzer_positions table
    $db->query("CREATE TABLE IF NOT EXISTS stemwijzer_positions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        party_id INT NOT NULL,
        position ENUM('eens', 'oneens', 'neutraal') NOT NULL,
        explanation TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (question_id) REFERENCES stemwijzer_questions(id) ON DELETE CASCADE,
        FOREIGN KEY (party_id) REFERENCES stemwijzer_parties(id) ON DELETE CASCADE,
        UNIQUE KEY unique_question_party (question_id, party_id),
        INDEX idx_question (question_id),
        INDEX idx_party (party_id),
        INDEX idx_position (position)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemwijzer_positions\n";
    } else {
        echo "✗ Failed to create table: stemwijzer_positions\n";
    }

    // 4. Create stemwijzer_results table (voor het opslaan van gebruikersresultaten)
    $db->query("CREATE TABLE IF NOT EXISTS stemwijzer_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
        session_id VARCHAR(255) DEFAULT NULL,
        answers JSON NOT NULL,
        results JSON NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent TEXT DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_user (user_id),
        INDEX idx_session (session_id),
        INDEX idx_completed (completed_at)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemwijzer_results\n";
    } else {
        echo "✗ Failed to create table: stemwijzer_results\n";
    }

    echo "\nStemwijzer database tables created successfully!\n";
    echo "You can now proceed with importing the data.\n";

} catch (Exception $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
}
?> 