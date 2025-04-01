-- Create newsletter subscribers table if it doesn't exist
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_notification_at TIMESTAMP NULL DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'active'
); 