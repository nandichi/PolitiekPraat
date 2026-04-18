-- Personal Access Tokens (PAT) voor het PolitiekPraat OAuth-systeem.
--
-- PATs zijn long-lived bearer tokens die door een ingelogde gebruiker
-- handmatig worden gemaakt via de admin. Ze werken als alternatief voor
-- de OAuth 2.0 authorization_code flow voor MCP-clients (Cursor, Claude
-- Desktop) waarin de user scopes vooraf vastlegt.
--
-- Opslag: token_hash = SHA-256 van de platte token (we bewaren nooit de
-- originele token). token_prefix = eerste 12 chars voor identificatie
-- in de UI (bv. "pp_live_abc1").

CREATE TABLE IF NOT EXISTS oauth_personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    token_prefix VARCHAR(24) NOT NULL,
    token_hash CHAR(64) NOT NULL,
    scopes TEXT NOT NULL,
    last_used_at DATETIME NULL DEFAULT NULL,
    last_used_ip VARCHAR(45) NULL DEFAULT NULL,
    expires_at DATETIME NULL DEFAULT NULL,
    revoked_at DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_token_hash (token_hash),
    KEY idx_user (user_id),
    KEY idx_prefix (token_prefix),
    CONSTRAINT fk_pat_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
