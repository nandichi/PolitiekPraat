-- Migratie om poll functionaliteit toe te voegen aan blogs
-- Datum: 2024-01-XX
-- Beschrijving: Voegt blog polls toe waarbij lezers kunnen stemmen tussen twee opties

-- USE politiek_db; -- Database wordt automatisch geselecteerd door de connection

-- Maak polls tabel aan
CREATE TABLE IF NOT EXISTS blog_polls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    question VARCHAR(500) NOT NULL,
    option_a VARCHAR(200) NOT NULL,
    option_b VARCHAR(200) NOT NULL,
    option_a_votes INT DEFAULT 0,
    option_b_votes INT DEFAULT 0,
    total_votes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    INDEX idx_blog_id (blog_id)
);

-- Maak poll votes tabel aan om bij te houden wie heeft gestemd
CREATE TABLE IF NOT EXISTS blog_poll_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poll_id INT NOT NULL,
    user_id INT NULL,
    anonymous_voter_ip VARCHAR(45) NULL,
    anonymous_voter_fingerprint VARCHAR(100) NULL,
    chosen_option ENUM('A', 'B') NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poll_id) REFERENCES blog_polls(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_poll_id (poll_id),
    INDEX idx_user_id (user_id),
    INDEX idx_anonymous_voter (anonymous_voter_ip, anonymous_voter_fingerprint),
    -- Voorkom dubbele stemmen
    UNIQUE KEY unique_user_vote (poll_id, user_id),
    UNIQUE KEY unique_anonymous_vote (poll_id, anonymous_voter_ip, anonymous_voter_fingerprint)
); 