-- OAuth 2.0 / OIDC authorization server tabellen
--
-- Publiceert een complete OAuth 2.0 + OpenID Connect authorization server:
--   * oauth_clients               - geregistreerde clients (static + dynamic)
--   * oauth_authorization_codes   - tijdelijke codes voor authorization_code flow (+ PKCE)
--   * oauth_access_tokens         - uitgegeven access tokens (JTI tracking voor revocation)
--   * oauth_refresh_tokens        - refresh tokens met rotation support
--   * oauth_jwks                  - RS256 signing keys (rotatie-bewust)
--   * oauth_consents              - opgeslagen user consents per client+scope combinatie
--
-- Design beslissingen:
--   - Access tokens zijn RS256 JWTs; we bewaren alleen de JTI om revocation te
--     ondersteunen. De claim-data zit in de JWT zelf.
--   - Refresh tokens bewaren we gehashd (SHA-256 raw).
--   - Client secrets worden gehashd opgeslagen (password_hash).
--   - Redirect URIs, grant_types en scopes zijn JSON arrays voor flexibiliteit.

CREATE TABLE IF NOT EXISTS oauth_clients (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    client_id                       VARCHAR(100) NOT NULL UNIQUE,
    client_secret_hash              VARCHAR(255) NULL,
    client_name                     VARCHAR(200) NOT NULL,
    client_uri                      VARCHAR(500) NULL,
    logo_uri                        VARCHAR(500) NULL,
    tos_uri                         VARCHAR(500) NULL,
    policy_uri                      VARCHAR(500) NULL,
    redirect_uris                   JSON NOT NULL,
    grant_types                     JSON NOT NULL,
    response_types                  JSON NOT NULL,
    scopes                          JSON NOT NULL,
    token_endpoint_auth_method      VARCHAR(50) NOT NULL DEFAULT 'client_secret_basic',
    is_public                       TINYINT(1) NOT NULL DEFAULT 0,
    owner_user_id                   INT NULL,
    registration_access_token_hash  VARCHAR(255) NULL,
    registration_client_uri         VARCHAR(500) NULL,
    created_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_owner_user_id (owner_user_id),
    FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS oauth_authorization_codes (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    code_hash               CHAR(64) NOT NULL UNIQUE,
    client_id               VARCHAR(100) NOT NULL,
    user_id                 INT NOT NULL,
    redirect_uri            VARCHAR(500) NOT NULL,
    scope                   VARCHAR(1000) NOT NULL DEFAULT '',
    code_challenge          VARCHAR(255) NULL,
    code_challenge_method   VARCHAR(20) NULL,
    nonce                   VARCHAR(255) NULL,
    state                   VARCHAR(255) NULL,
    expires_at              DATETIME NOT NULL,
    used_at                 DATETIME NULL,
    created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_client_id (client_id),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS oauth_access_tokens (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    jti             VARCHAR(100) NOT NULL UNIQUE,
    client_id       VARCHAR(100) NOT NULL,
    user_id         INT NULL,
    scope           VARCHAR(1000) NOT NULL DEFAULT '',
    token_type      VARCHAR(20) NOT NULL DEFAULT 'Bearer',
    expires_at      DATETIME NOT NULL,
    revoked_at      DATETIME NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_client_id (client_id),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS oauth_refresh_tokens (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    token_hash      CHAR(64) NOT NULL UNIQUE,
    client_id       VARCHAR(100) NOT NULL,
    user_id         INT NULL,
    scope           VARCHAR(1000) NOT NULL DEFAULT '',
    parent_jti      VARCHAR(100) NULL,
    expires_at      DATETIME NOT NULL,
    revoked_at      DATETIME NULL,
    rotated_to_id   INT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_client_id (client_id),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS oauth_jwks (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    kid                  VARCHAR(64) NOT NULL UNIQUE,
    algorithm            VARCHAR(20) NOT NULL DEFAULT 'RS256',
    private_key_pem      LONGTEXT NOT NULL,
    public_jwk_json      LONGTEXT NOT NULL,
    status               VARCHAR(20) NOT NULL DEFAULT 'active',
    activated_at         DATETIME NOT NULL,
    retires_at           DATETIME NULL,
    retired_at           DATETIME NULL,
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

CREATE TABLE IF NOT EXISTS oauth_consents (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    client_id       VARCHAR(100) NOT NULL,
    scope           VARCHAR(1000) NOT NULL DEFAULT '',
    granted_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    revoked_at      DATETIME NULL,
    UNIQUE KEY uniq_consent (user_id, client_id),
    INDEX idx_client_id (client_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
