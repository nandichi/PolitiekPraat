-- Migratie om audio functionaliteit toe te voegen aan blogs
-- Voor het uploaden van tekst-naar-spraak audio bestanden

USE politiek_db;

-- Voeg audio_path kolom toe aan blogs tabel
ALTER TABLE blogs 
ADD audio_path VARCHAR(255) NULL;

-- Migratie om audio ondersteuning toe te voegen aan blogs tabel
-- Datum: 2024-01-XX
-- Beschrijving: Voegt audio_path, audio_url en soundcloud_url kolommen toe voor uitgebreide audio ondersteuning

-- Voeg audio_path kolom toe voor geüploade audio bestanden (als deze nog niet bestaat)
ALTER TABLE blogs ADD audio_path VARCHAR(255) NULL;

-- Voeg audio_url kolom toe voor Google Drive audio links (als deze nog niet bestaat)  
ALTER TABLE blogs ADD audio_url VARCHAR(500) NULL;

-- Voeg soundcloud_url kolom toe voor SoundCloud audio links (als deze nog niet bestaat)
ALTER TABLE blogs ADD soundcloud_url VARCHAR(500) NULL; 