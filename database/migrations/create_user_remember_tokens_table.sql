CREATE TABLE IF NOT EXISTS user_remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_remember_tokens_user_id (user_id),
    INDEX idx_user_remember_tokens_expires_at (expires_at),
    UNIQUE KEY uq_user_remember_tokens_token_hash (token_hash),
    CONSTRAINT fk_user_remember_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
