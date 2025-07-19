-- Create amerikaanse_presidenten table for complete presidential information
CREATE TABLE IF NOT EXISTS amerikaanse_presidenten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    president_nummer INT NOT NULL UNIQUE,
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
    vice_president VARCHAR(255),
    foto_url VARCHAR(2083),
    
    -- Biografische informatie
    biografie TEXT NOT NULL,
    vroeg_leven TEXT,
    politieke_carriere TEXT,
    prestaties JSON, -- Array van belangrijkste prestaties
    fun_facts JSON, -- Array van leuke weetjes
    
    -- Familie informatie
    echtgenote VARCHAR(255),
    kinderen JSON, -- Array van kinderen namen
    familie_connecties TEXT, -- Connecties met andere presidenten/politici
    
    -- Fysieke karakteristieken
    lengte_cm INT,
    gewicht_kg INT,
    
    -- Presidentiële informatie  
    verkiezingsjaren JSON, -- Array van jaren waarin ze gekozen werden
    leeftijd_bij_aantreden INT,
    belangrijkste_gebeurtenissen TEXT,
    bekende_speeches TEXT,
    wetgeving JSON, -- Array van belangrijke wetten
    oorlogen JSON, -- Array van oorlogen tijdens presidentschap
    economische_situatie TEXT,
    
    -- Voor en na presidentschap
    carrierre_voor_president TEXT,
    carrierre_na_president TEXT,
    doodsoorzaak VARCHAR(255),
    begrafenisplaats VARCHAR(255),
    
    -- Legacy en impact
    historische_waardering TEXT,
    controverses TEXT,
    citaten JSON, -- Array van bekende citaten
    monumenten_ter_ere TEXT,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_president_nummer (president_nummer),
    INDEX idx_partij (partij),
    INDEX idx_periode (periode_start, periode_eind),
    INDEX idx_naam (naam)
); 