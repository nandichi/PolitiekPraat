-- Migratie om blog categorieën toe te voegen
-- Datum: 2024-01-XX
-- Beschrijving: Voegt categorieën toe aan blogs voor betere organisatie en filtering

-- USE politiek_db; -- Database wordt automatisch geselecteerd door de connection

-- Maak blog_categories tabel aan
CREATE TABLE IF NOT EXISTS blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6', -- Hex color voor frontend styling
    icon VARCHAR(50) DEFAULT 'folder', -- Icon identifier voor frontend
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active),
    INDEX idx_sort_order (sort_order)
);

-- Voeg category_id kolom toe aan blogs tabel
ALTER TABLE blogs 
ADD COLUMN category_id INT NULL,
ADD FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
ADD INDEX idx_category_id (category_id);

-- Voeg standaard categorieën toe die passen bij PolitiekPraat
INSERT INTO blog_categories (name, slug, description, color, icon, sort_order) VALUES
('Nederlandse Politiek', 'nederlandse-politiek', 'Analyses en nieuws over de Nederlandse politieke situatie', '#FF6B35', 'flag-nl', 1),
('Internationale Politiek', 'internationale-politiek', 'Wereldwijde politieke ontwikkelingen en hun impact', '#3B82F6', 'globe', 2),
('Verkiezingen', 'verkiezingen', 'Verkiezingsuitslagen, peilingen en campagne-analyses', '#10B981', 'ballot', 3),
('Partijanalyses', 'partijanalyses', 'Diepgaande analyses van politieke partijen en hun standpunten', '#8B5CF6', 'users', 4),
('Democratie & Instituties', 'democratie-instituties', 'Over democratische processen en politieke instituties', '#F59E0B', 'institution', 5),
('Politieke Geschiedenis', 'politieke-geschiedenis', 'Historische politieke gebeurtenissen en hun lessen', '#EF4444', 'history', 6),
('Opinies & Columns', 'opinies-columns', 'Persoonlijke opinies en columns over politieke thema\'s', '#EC4899', 'edit', 7),
('Actuele Thema\'s', 'actuele-themas', 'Politieke duiding van actuele maatschappelijke thema\'s', '#06B6D4', 'trending', 8);

-- Stel een default categorie in voor bestaande blogs (Nederlandse Politiek)
UPDATE blogs 
SET category_id = (SELECT id FROM blog_categories WHERE slug = 'nederlandse-politiek' LIMIT 1) 
WHERE category_id IS NULL;