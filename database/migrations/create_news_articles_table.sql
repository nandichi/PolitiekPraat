-- Create news_articles table
CREATE TABLE IF NOT EXISTS news_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    url VARCHAR(1000) NOT NULL,
    source VARCHAR(100) NOT NULL,
    bias VARCHAR(50) NOT NULL DEFAULT 'Neutraal',
    orientation VARCHAR(20) NOT NULL DEFAULT 'neutraal',
    published_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_source (source),
    INDEX idx_bias (bias),
    INDEX idx_orientation (orientation),
    INDEX idx_published_at (published_at)
); 