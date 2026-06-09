-- PartijMeter 2026 database tabellen
--
-- Eigen, schone dataset voor de landelijke PartijMeter, volledig losgekoppeld
-- van de (gemeentelijke) stemwijzer_* tabellen en de Ede-dataset. Partij-info
-- (naam, logo, kleur, leider, zetels) komt uit de bestaande tabel
-- political_parties via party_key; hier slaan we alleen stellingen,
-- standpunten en resultaten op.
--
-- Idempotent: opnieuw draaien is veilig.

CREATE TABLE IF NOT EXISTS partijmeter_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme VARCHAR(80) NOT NULL,                 -- thema, bv. "Klimaat en energie"
    title VARCHAR(255) NOT NULL,                -- de stelling zelf
    explanation TEXT NULL,                      -- extra context / toelichting
    -- Kompas-assen: -1, 0 of +1 geven aan welke kant een 'eens'-antwoord op de
    -- as duwt. economic: +1 = economisch rechts, -1 = economisch links.
    -- cultural: +1 = conservatief, -1 = progressief.
    axis_economic TINYINT NOT NULL DEFAULT 0,
    axis_cultural TINYINT NOT NULL DEFAULT 0,
    order_number INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_active (order_number, is_active),
    INDEX idx_theme (theme)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS partijmeter_positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    party_key VARCHAR(20) NOT NULL,             -- verwijst naar political_parties.party_key
    position ENUM('eens', 'neutraal', 'oneens') NOT NULL,
    explanation TEXT NULL,                      -- korte onderbouwing per partij
    source_url VARCHAR(500) NULL,               -- bron (programma / stemgedrag)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES partijmeter_questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_question_party (question_id, party_key),
    INDEX idx_question (question_id),
    INDEX idx_party (party_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS partijmeter_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    share_id VARCHAR(32) NOT NULL,
    user_id INT NULL,
    answers JSON NOT NULL,
    weights JSON NULL,
    results JSON NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_share (share_id),
    INDEX idx_session (session_id),
    INDEX idx_user (user_id),
    INDEX idx_completed (completed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
