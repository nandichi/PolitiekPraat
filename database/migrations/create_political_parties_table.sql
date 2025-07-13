-- Create political_parties table for complete party information
CREATE TABLE IF NOT EXISTS political_parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    party_key VARCHAR(10) NOT NULL UNIQUE, -- PVV, VVD, etc.
    name VARCHAR(255) NOT NULL,
    leader VARCHAR(255) NOT NULL,
    logo VARCHAR(500) NOT NULL,
    leader_photo VARCHAR(500) NOT NULL,
    description TEXT NOT NULL,
    leader_info TEXT NOT NULL,
    standpoints JSON NOT NULL,
    current_seats INT NOT NULL,
    polling JSON NOT NULL,
    perspectives JSON NOT NULL,
    color VARCHAR(7) NOT NULL, -- Hex color code
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_party_key (party_key)
); 