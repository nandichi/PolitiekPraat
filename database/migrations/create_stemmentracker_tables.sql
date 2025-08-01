-- StemmenTracker database tabellen
-- Voor het bijhouden van hoe partijen hebben gestemd over moties in de Tweede Kamer

CREATE TABLE IF NOT EXISTS stemmentracker_moties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    motie_nummer VARCHAR(50),
    kamerstuk_nummer VARCHAR(50),
    datum_stemming DATE NOT NULL,
    onderwerp VARCHAR(255) NOT NULL,
    indiener VARCHAR(255),
    uitslag ENUM('aangenomen', 'verworpen', 'ingetrokken') DEFAULT NULL,
    stemming_type ENUM('hoofdelijke', 'handopsteken') DEFAULT 'hoofdelijke',
    kamer_stuk_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_datum (datum_stemming),
    INDEX idx_onderwerp (onderwerp),
    INDEX idx_active (is_active),
    INDEX idx_uitslag (uitslag)
);

CREATE TABLE IF NOT EXISTS stemmentracker_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    motie_id INT NOT NULL,
    party_id INT NOT NULL,
    vote ENUM('voor', 'tegen', 'niet_gestemd', 'afwezig') NOT NULL,
    aantal_zetels INT DEFAULT 1,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (motie_id) REFERENCES stemmentracker_moties(id) ON DELETE CASCADE,
    FOREIGN KEY (party_id) REFERENCES stemwijzer_parties(id) ON DELETE CASCADE,
    UNIQUE KEY unique_motie_party (motie_id, party_id),
    INDEX idx_motie (motie_id),
    INDEX idx_party (party_id),
    INDEX idx_vote (vote)
);

CREATE TABLE IF NOT EXISTS stemmentracker_themas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
);

CREATE TABLE IF NOT EXISTS stemmentracker_motie_themas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    motie_id INT NOT NULL,
    thema_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (motie_id) REFERENCES stemmentracker_moties(id) ON DELETE CASCADE,
    FOREIGN KEY (thema_id) REFERENCES stemmentracker_themas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_motie_thema (motie_id, thema_id),
    INDEX idx_motie (motie_id),
    INDEX idx_thema (thema_id)
);