-- Migratie voor Blog Polls functionaliteit
-- Voegt poll functionaliteit toe aan blogs

-- Tabel voor polls gekoppeld aan blogs
CREATE TABLE IF NOT EXISTS blog_polls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    question VARCHAR(500) NOT NULL,
    description TEXT,
    poll_type ENUM('single', 'multiple') DEFAULT 'single',
    is_active BOOLEAN DEFAULT TRUE,
    show_results ENUM('after_vote', 'always', 'never') DEFAULT 'after_vote',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    INDEX idx_blog_id (blog_id),
    INDEX idx_active (is_active)
);

-- Tabel voor poll opties
CREATE TABLE IF NOT EXISTS poll_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poll_id INT NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    option_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poll_id) REFERENCES blog_polls(id) ON DELETE CASCADE,
    INDEX idx_poll_id (poll_id),
    INDEX idx_order (option_order)
);

-- Tabel voor poll stemmen
CREATE TABLE IF NOT EXISTS poll_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poll_id INT NOT NULL,
    option_id INT NOT NULL,
    user_id INT NULL, -- NULL voor anonieme stemmen
    session_id VARCHAR(255), -- Voor anonieme gebruikers
    ip_address VARCHAR(45),
    user_agent TEXT,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poll_id) REFERENCES blog_polls(id) ON DELETE CASCADE,
    FOREIGN KEY (option_id) REFERENCES poll_options(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_poll_id (poll_id),
    INDEX idx_option_id (option_id),
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id),
    -- Voorkom dubbele stemmen per gebruiker per poll
    UNIQUE KEY unique_user_poll (poll_id, user_id),
    UNIQUE KEY unique_session_poll (poll_id, session_id)
);

-- Voeg poll_id kolom toe aan blogs tabel (optioneel)
ALTER TABLE blogs ADD COLUMN has_poll BOOLEAN DEFAULT FALSE;

-- Update trigger om has_poll bij te werken
DELIMITER //
CREATE TRIGGER update_blog_has_poll
    AFTER INSERT ON blog_polls
    FOR EACH ROW
BEGIN
    UPDATE blogs SET has_poll = TRUE WHERE id = NEW.blog_id;
END//

CREATE TRIGGER remove_blog_has_poll
    AFTER DELETE ON blog_polls
    FOR EACH ROW
BEGIN
    UPDATE blogs SET has_poll = FALSE WHERE id = OLD.blog_id;
END//
DELIMITER ;

-- Voeg index toe voor betere performance
ALTER TABLE blogs ADD INDEX idx_has_poll (has_poll); 