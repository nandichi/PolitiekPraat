-- Midterms 2026 sectie: databasetabellen
-- Alle tabellen utf8mb4 / InnoDB. Idempotent via IF NOT EXISTS.

-- Races (Senaat, Huis, Gouverneurs) met ratings en kandidaten
CREATE TABLE IF NOT EXISTS midterms_races (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chamber ENUM('senate','house','governor') NOT NULL,
    state_code CHAR(2) NOT NULL,
    state_name VARCHAR(64) NOT NULL,
    district VARCHAR(8) NULL,
    incumbent_name VARCHAR(128) NULL,
    incumbent_party ENUM('D','R','I') NULL,
    is_open TINYINT(1) NOT NULL DEFAULT 0,
    rating ENUM('safe_d','likely_d','lean_d','tossup','lean_r','likely_r','safe_r') NOT NULL DEFAULT 'tossup',
    is_competitive TINYINT(1) NOT NULL DEFAULT 0,
    candidate_d VARCHAR(128) NULL,
    candidate_r VARCHAR(128) NULL,
    candidates_json TEXT NULL,
    summary_nl TEXT NULL,
    source_url VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_race (chamber, state_code, district),
    KEY idx_chamber (chamber),
    KEY idx_competitive (is_competitive),
    KEY idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tijdlijn (voorverkiezingen, key events, deadlines)
CREATE TABLE IF NOT EXISTS midterms_timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_date DATE NOT NULL,
    title_nl VARCHAR(200) NOT NULL,
    description_nl TEXT NULL,
    category VARCHAR(32) NOT NULL DEFAULT 'event',
    state_code CHAR(2) NULL,
    source_url VARCHAR(255) NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_event_date (event_date),
    KEY idx_category (category),
    KEY idx_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referenda / ballot measures
CREATE TABLE IF NOT EXISTS midterms_referenda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_code CHAR(2) NULL,
    state_name VARCHAR(64) NULL,
    measure_code VARCHAR(32) NULL,
    title_nl VARCHAR(200) NOT NULL,
    theme VARCHAR(48) NULL,
    description_nl TEXT NULL,
    polymarket_slug VARCHAR(160) NULL,
    yes_label_nl VARCHAR(160) NULL,
    election_date DATE NULL,
    source_url VARCHAR(255) NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_theme (theme),
    KEY idx_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Polymarket odds cache (gevuld door cron-script)
CREATE TABLE IF NOT EXISTS midterms_odds_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    market_key VARCHAR(64) NOT NULL,
    event_slug VARCHAR(160) NOT NULL,
    title_nl VARCHAR(200) NULL,
    category VARCHAR(32) NULL,
    outcomes_json MEDIUMTEXT NULL,
    volume DECIMAL(16,2) NULL,
    source_url VARCHAR(255) NULL,
    fetched_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_market (market_key),
    KEY idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nieuws cache (gevuld door Brave-cron-script)
CREATE TABLE IF NOT EXISTS midterms_news_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_hash CHAR(40) NOT NULL,
    title VARCHAR(300) NOT NULL,
    url VARCHAR(500) NOT NULL,
    source VARCHAR(120) NULL,
    intro_nl TEXT NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    published_at DATETIME NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    fetched_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_url_hash (url_hash),
    KEY idx_published_at (published_at),
    KEY idx_is_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
