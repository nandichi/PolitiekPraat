-- Politiek Gesprek tabellen

DROP TABLE IF EXISTS politiek_gesprek_results;
DROP TABLE IF EXISTS politiek_gesprek_sessions;
DROP TABLE IF EXISTS politiek_gesprek_rate_limit;

CREATE TABLE politiek_gesprek_sessions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id VARCHAR(100) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    conversation_state LONGTEXT NOT NULL,
    current_question_index INT(11) DEFAULT 0,
    answers LONGTEXT NOT NULL,
    mode_preference VARCHAR(20) DEFAULT 'adaptive',
    started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT,
    PRIMARY KEY (id),
    UNIQUE KEY session_id (session_id),
    KEY idx_user (user_id),
    KEY idx_started (started_at)
);

CREATE TABLE politiek_gesprek_results (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id VARCHAR(100) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    top_party_id INT(11) NOT NULL,
    match_percentage DECIMAL(5,2) NOT NULL,
    all_matches LONGTEXT NOT NULL,
    ai_analysis TEXT NOT NULL,
    political_profile TEXT,
    conversation_summary LONGTEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_session (session_id),
    KEY idx_user (user_id),
    KEY idx_top_party (top_party_id)
);

CREATE TABLE politiek_gesprek_rate_limit (
    id INT(11) NOT NULL AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL,
    last_conversation_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    conversation_count INT(11) DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY ip_address (ip_address)
);
