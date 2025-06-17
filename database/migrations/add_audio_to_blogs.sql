-- Migratie om audio functionaliteit toe te voegen aan blogs
-- Voor het uploaden van tekst-naar-spraak audio bestanden

USE politiek_db;

-- Voeg audio_path kolom toe aan blogs tabel
ALTER TABLE blogs 
ADD audio_path VARCHAR(255) NULL;

-- Verifieer dat de kolom is toegevoegd
DESCRIBE blogs; 