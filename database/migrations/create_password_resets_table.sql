-- Wachtwoord-reset tokens voor de "wachtwoord vergeten"-flow.
--
-- We slaan nooit de ruwe token op, alleen de SHA-256 hash ervan. De ruwe token
-- staat enkel in de e-mailtoken-link die de gebruiker ontvangt. Zo kan een lek
-- van deze tabel niet leiden tot misbruik van geldige reset-links.
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_password_resets_user_id (user_id),
    INDEX idx_password_resets_expires_at (expires_at),
    UNIQUE KEY uq_password_resets_token_hash (token_hash),
    CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
