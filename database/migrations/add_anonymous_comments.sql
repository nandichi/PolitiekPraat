-- Database migratie voor anonieme comments
-- Deze migratie maakt het mogelijk om anonieme reacties te plaatsen

-- Voeg anonymous_name kolom toe
ALTER TABLE comments ADD COLUMN anonymous_name VARCHAR(100) NULL;

-- Maak user_id nullable voor anonieme comments
ALTER TABLE comments MODIFY COLUMN user_id INT NULL;

-- Update bestaande foreign key constraint
ALTER TABLE comments DROP FOREIGN KEY comments_ibfk_2;
ALTER TABLE comments ADD CONSTRAINT comments_ibfk_2 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Voeg een check constraint toe om ervoor te zorgen dat óf user_id óf anonymous_name is ingevuld
-- (MySQL 8.0+ ondersteunt check constraints, voor eerdere versies wordt dit gecontroleerd in de applicatie)
ALTER TABLE comments ADD CONSTRAINT chk_comment_author 
    CHECK ((user_id IS NOT NULL AND anonymous_name IS NULL) OR (user_id IS NULL AND anonymous_name IS NOT NULL)); 
    