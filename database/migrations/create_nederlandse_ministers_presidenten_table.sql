-- Create nederlandse_ministers_presidenten table for complete Dutch prime minister information
CREATE TABLE IF NOT EXISTS nederlandse_ministers_presidenten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    minister_president_nummer INT NOT NULL UNIQUE,
    naam VARCHAR(255) NOT NULL,
    volledige_naam VARCHAR(255) NOT NULL,
    bijnaam VARCHAR(255),
    partij VARCHAR(100) NOT NULL,
    periode_start DATE NOT NULL,
    periode_eind DATE,
    is_huidig BOOLEAN DEFAULT FALSE,
    geboren DATE NOT NULL,
    overleden DATE,
    geboorteplaats VARCHAR(255) NOT NULL,
    foto_url VARCHAR(2083),
    
    -- Biografische informatie
    biografie TEXT NOT NULL,
    vroeg_leven TEXT,
    politieke_carriere TEXT,
    prestaties JSON, -- Array van belangrijkste prestaties
    fun_facts JSON, -- Array van leuke weetjes
    
    -- Familie informatie
    echtgenoot_echtgenote VARCHAR(255),
    kinderen JSON, -- Array van kinderen namen
    familie_connecties TEXT, -- Connecties met andere politici
    
    -- Fysieke karakteristieken
    lengte_cm INT,
    
    -- Minister-presidentiële informatie  
    kabinetten JSON, -- Array van kabinetten (bijv. Rutte I, II, III, IV)
    coalitiepartners JSON, -- Array van coalitiepartijen
    leeftijd_bij_aantreden INT,
    belangrijkste_gebeurtenissen TEXT,
    bekende_speeches TEXT,
    wetgeving JSON, -- Array van belangrijke wetten
    crises JSON, -- Array van crises tijdens MP-schap
    economische_situatie TEXT,
    
    -- Voor en na minister-presidentschap
    carrierre_voor_mp TEXT,
    carrierre_na_mp TEXT,
    doodsoorzaak VARCHAR(255),
    begrafenisplaats VARCHAR(255),
    
    -- Legacy en impact
    historische_waardering TEXT,
    controverses TEXT,
    citaten JSON, -- Array van bekende citaten
    monumenten_ter_ere TEXT,
    
    -- Nederlandse specifieke velden
    tweede_kamer_ervaring TEXT,
    ministersposten_voor_mp JSON, -- Array van eerdere ministersposten
    onderwijs VARCHAR(255),
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_minister_president_nummer (minister_president_nummer),
    INDEX idx_partij (partij),
    INDEX idx_periode (periode_start, periode_eind),
    INDEX idx_naam (naam)
); 