-- Database migratie voor comment likes door blog auteurs
-- Dit maakt het mogelijk voor blog schrijvers om reacties te liken (zoals TikTok's "liked by creator")

-- Voeg likes kolom toe aan comments tabel
ALTER TABLE comments ADD COLUMN is_liked_by_author BOOLEAN DEFAULT FALSE;

-- Voeg likes_count kolom toe voor algemene likes (toekomstige uitbreiding)
ALTER TABLE comments ADD COLUMN likes_count INT DEFAULT 0;

-- Index voor betere performance bij het ophalen van gelikete comments
CREATE INDEX idx_comments_liked_by_author ON comments(is_liked_by_author); 