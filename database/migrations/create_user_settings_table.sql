CREATE TABLE IF NOT EXISTS user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profile_public TINYINT(1) DEFAULT 1,
    show_email TINYINT(1) DEFAULT 0,
    show_activity TINYINT(1) DEFAULT 1,
    data_analytics TINYINT(1) DEFAULT 1,
    notify_comments TINYINT(1) DEFAULT 1,
    notify_replies TINYINT(1) DEFAULT 1,
    notify_newsletter TINYINT(1) DEFAULT 1,
    notify_site_comments TINYINT(1) DEFAULT 1,
    notify_site_likes TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
); 