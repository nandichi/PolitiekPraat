-- Create nederlandse_verkiezingen table for Dutch parliamentary elections data
CREATE TABLE IF NOT EXISTS nederlandse_verkiezingen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jaar INT NOT NULL UNIQUE,
    
    -- Partij uitslagen (alle partijen met hun resultaten)
    partij_uitslagen JSON NOT NULL, -- Alle partijen: [{"partij": "VVD", "zetels": 34, "stemmen": 2383167, "percentage": 21.9}, ...]
    
    -- Coalitie en regering
    coalitie_partijen JSON, -- Array van coalitiepartijen: ["VVD", "D66", "CDA", "ChristenUnie"]
    coalitie_zetels INT,
    coalitie_type ENUM('meerderheid', 'minderheid') DEFAULT 'meerderheid',
    oppositie_partijen JSON, -- Array van oppositiepartijen
    minister_president VARCHAR(255) NOT NULL,
    minister_president_partij VARCHAR(100) NOT NULL,
    kabinet_naam VARCHAR(255), -- bijv. "Rutte IV", "Balkenende I"
    kabinet_type ENUM('links', 'rechts', 'centrum-links', 'centrum-rechts', 'centraal', 'paars') DEFAULT 'centraal',
    
    -- Verkiezingsgegevens
    totaal_zetels INT NOT NULL DEFAULT 150,
    totaal_stemmen INT NOT NULL,
    opkomst_percentage DECIMAL(5,2) NOT NULL,
    kiesdrempel_percentage DECIMAL(5,2) DEFAULT 0.67, -- Nederlandse kiesdrempel
    verkiezingsdata DATE NOT NULL,
    kabinet_start_datum DATE,
    kabinet_eind_datum DATE,
    formatie_duur_dagen INT, -- Hoelang duurde de formatie
    
    -- Verkiezingscontext
    verkiezings_aanleiding ENUM('regulier', 'kabinetsval', 'vervroegd', 'motie_van_wantrouwen') DEFAULT 'regulier',
    belangrijkste_themas JSON, -- Array van verkiezingsthema's
    belangrijke_gebeurtenissen TEXT,
    opvallende_feiten TEXT,
    
    -- Politieke verschuivingen
    nieuwe_partijen JSON, -- Partijen die voor het eerst in TK kwamen
    verdwenen_partijen JSON, -- Partijen die uit TK verdwenen
    grootste_winnaar VARCHAR(100), -- Partij met meeste zetelwinst
    grootste_winnaar_aantal INT,
    grootste_verliezer VARCHAR(100), -- Partij met meeste zetelverlies  
    grootste_verliezer_aantal INT,
    
    -- Nederlandse politieke eigenarten
    aantal_partijen_tk INT, -- Aantal partijen in Tweede Kamer
    kiesdrempel_gehaald JSON, -- Partijen die net kiesdrempel haalden
    kiesdrempel_gemist JSON, -- Partijen die net kiesdrempel misten
    lijsttrekkers JSON, -- Belangrijke lijsttrekkers die jaar
    
    -- Media en campagne
    tv_debatten JSON, -- Belangrijke TV-debatten
    verkiezingsuitslag_tijd TIME, -- Wanneer uitslag bekend werd
    opkomst_verschil_vorige DECIMAL(5,2), -- Verschil opkomst t.o.v. vorige verkiezing
    
    -- Extra informatie
    beschrijving TEXT,
    bronnen JSON, -- Bronnen zoals Kiesraad, NOS, etc.
    foto_url VARCHAR(2083), -- Foto van verkiezingsavond
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_jaar (jaar),
    INDEX idx_minister_president (minister_president),
    INDEX idx_verkiezingsdata (verkiezingsdata),
    INDEX idx_opkomst (opkomst_percentage),
    INDEX idx_coalitie_type (coalitie_type),
    INDEX idx_verkiezings_aanleiding (verkiezings_aanleiding)
); 