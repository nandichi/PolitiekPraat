-- Gemeentelijke Stemwijzer (Sprint 1 - Slice #26)
-- Scope lock: Ede 2026, 25 stellingen, weging aan
-- Doel: uitbreidbaar datamodel + versiebeheer, zonder bestaande landelijke stemwijzer te breken

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS municipalities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(160) NOT NULL,
    province VARCHAR(120) DEFAULT NULL,
    cbs_code VARCHAR(16) DEFAULT NULL,
    country_code CHAR(2) NOT NULL DEFAULT 'NL',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_municipalities_slug_country (slug, country_code),
    UNIQUE KEY uq_municipalities_cbs_code (cbs_code),
    INDEX idx_municipalities_active (is_active),
    INDEX idx_municipalities_archived (archived_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_type ENUM('gemeenteraad') NOT NULL,
    election_scope ENUM('municipal') NOT NULL DEFAULT 'municipal',
    election_year SMALLINT NOT NULL,
    election_date DATE DEFAULT NULL,
    status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    title VARCHAR(180) NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_elections_type_scope_year (election_type, election_scope, election_year),
    INDEX idx_elections_status (status),
    INDEX idx_elections_active (is_active),
    INDEX idx_elections_date (election_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS election_municipalities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_id INT NOT NULL,
    municipality_id INT NOT NULL,
    status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    weighting_enabled TINYINT(1) NOT NULL DEFAULT 1,
    expected_theses_count SMALLINT NOT NULL DEFAULT 25,
    published_at DATETIME NULL,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_election_municipality (election_id, municipality_id),
    INDEX idx_election_municipalities_status (status),
    INDEX idx_election_municipalities_weighting (weighting_enabled),
    CONSTRAINT fk_election_municipalities_election
        FOREIGN KEY (election_id) REFERENCES elections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_election_municipalities_municipality
        FOREIGN KEY (municipality_id) REFERENCES municipalities(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS election_parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_municipality_id INT NOT NULL,
    legacy_party_id INT NULL,
    party_name VARCHAR(120) NOT NULL,
    party_key VARCHAR(40) NOT NULL,
    logo_url VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    source_label VARCHAR(120) DEFAULT NULL,
    source_url VARCHAR(255) DEFAULT NULL,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_election_parties_scope_key (election_municipality_id, party_key),
    INDEX idx_election_parties_active (is_active),
    INDEX idx_election_parties_sort (sort_order),
    CONSTRAINT fk_election_parties_scope
        FOREIGN KEY (election_municipality_id) REFERENCES election_municipalities(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_election_parties_legacy
        FOREIGN KEY (legacy_party_id) REFERENCES stemwijzer_parties(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS theses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_municipality_id INT NOT NULL,
    thesis_code VARCHAR(40) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    theme VARCHAR(120) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_theses_scope_code (election_municipality_id, thesis_code),
    INDEX idx_theses_scope_sort (election_municipality_id, sort_order),
    INDEX idx_theses_active (is_active),
    CONSTRAINT fk_theses_scope
        FOREIGN KEY (election_municipality_id) REFERENCES election_municipalities(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS thesis_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thesis_id INT NOT NULL,
    version_number INT NOT NULL,
    status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    statement_text TEXT NOT NULL,
    context_text TEXT NULL,
    left_label VARCHAR(120) DEFAULT NULL,
    right_label VARCHAR(120) DEFAULT NULL,
    change_note VARCHAR(255) DEFAULT NULL,
    published_at DATETIME NULL,
    archived_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_thesis_versions_number (thesis_id, version_number),
    INDEX idx_thesis_versions_current (thesis_id, is_current),
    INDEX idx_thesis_versions_status (status),
    CONSTRAINT fk_thesis_versions_thesis
        FOREIGN KEY (thesis_id) REFERENCES theses(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS party_positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_party_id INT NOT NULL,
    thesis_version_id INT NOT NULL,
    position ENUM('eens', 'neutraal', 'oneens') NOT NULL,
    weight_multiplier DECIMAL(4,2) NOT NULL DEFAULT 1.00,
    explanation TEXT NULL,
    source_label VARCHAR(120) DEFAULT NULL,
    source_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_party_positions_unique (election_party_id, thesis_version_id),
    INDEX idx_party_positions_thesis_version (thesis_version_id),
    INDEX idx_party_positions_position (position),
    CONSTRAINT fk_party_positions_party
        FOREIGN KEY (election_party_id) REFERENCES election_parties(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_party_positions_thesis_version
        FOREIGN KEY (thesis_version_id) REFERENCES thesis_versions(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS thesis_weights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thesis_version_id INT NOT NULL,
    weight_value TINYINT UNSIGNED NOT NULL DEFAULT 1,
    weighting_enabled TINYINT(1) NOT NULL DEFAULT 1,
    rationale VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_thesis_weights_version (thesis_version_id),
    INDEX idx_thesis_weights_enabled (weighting_enabled),
    CONSTRAINT fk_thesis_weights_version
        FOREIGN KEY (thesis_version_id) REFERENCES thesis_versions(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Koppeling naar bestaande opgeslagen resultaten zonder breaking schema changes op stemwijzer_results
CREATE TABLE IF NOT EXISTS stemwijzer_result_contexts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stemwijzer_result_id INT NOT NULL,
    election_municipality_id INT NULL,
    thesis_version_id INT NULL,
    weighting_enabled TINYINT(1) NOT NULL DEFAULT 1,
    context_payload JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_stemwijzer_result_context_result (stemwijzer_result_id),
    INDEX idx_stemwijzer_result_context_scope (election_municipality_id),
    INDEX idx_stemwijzer_result_context_version (thesis_version_id),
    CONSTRAINT fk_stemwijzer_result_context_result
        FOREIGN KEY (stemwijzer_result_id) REFERENCES stemwijzer_results(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_stemwijzer_result_context_scope
        FOREIGN KEY (election_municipality_id) REFERENCES election_municipalities(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_stemwijzer_result_context_version
        FOREIGN KEY (thesis_version_id) REFERENCES thesis_versions(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
