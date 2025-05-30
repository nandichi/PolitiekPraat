-- Stemwijzer database tabellen
CREATE TABLE IF NOT EXISTS stemwijzer_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    context TEXT NOT NULL,
    left_view TEXT NOT NULL,
    right_view TEXT NOT NULL,
    order_number INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_active (order_number, is_active)
);

CREATE TABLE IF NOT EXISTS stemwijzer_parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    short_name VARCHAR(10) NOT NULL UNIQUE,
    logo_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
);

CREATE TABLE IF NOT EXISTS stemwijzer_positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    party_id INT NOT NULL,
    position ENUM('eens', 'neutraal', 'oneens') NOT NULL,
    explanation TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES stemwijzer_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (party_id) REFERENCES stemwijzer_parties(id) ON DELETE CASCADE,
    UNIQUE KEY unique_question_party (question_id, party_id),
    INDEX idx_question (question_id),
    INDEX idx_party (party_id)
);

CREATE TABLE IF NOT EXISTS stemwijzer_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    user_id INT NULL,
    answers JSON NOT NULL,
    results JSON NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_session (session_id),
    INDEX idx_user (user_id),
    INDEX idx_completed (completed_at)
); 