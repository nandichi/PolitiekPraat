-- =============================================================
-- Blogs CMS-uitbreidingen voor autonoom bloggen via MCP.
--
-- Voegt kolommen toe aan `blogs` voor draft/published/scheduled
-- workflow, SEO-velden, tags, excerpt, updated_at en reading_time.
--
-- Idempotent: gebruikt `ADD COLUMN IF NOT EXISTS` (MariaDB 10.0.2+).
-- Indexen worden in PHP via SHOW INDEX aangemaakt om compatibel
-- te blijven met MariaDB < 10.5.3.
-- =============================================================

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS status ENUM('draft','published','scheduled') NOT NULL DEFAULT 'published';

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS scheduled_for DATETIME NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS excerpt VARCHAR(500) NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS tags JSON NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS seo_title VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS seo_description VARCHAR(320) NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS meta_robots VARCHAR(100) NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS canonical_url VARCHAR(500) NULL DEFAULT NULL;

ALTER TABLE blogs
    ADD COLUMN IF NOT EXISTS reading_time INT NULL DEFAULT NULL;
