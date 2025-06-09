-- =================================================================
-- EENVOUDIG Script voor het toevoegen van een nieuwe stemwijzer vraag
-- =================================================================
-- Dit is een eenvoudiger script dat je kunt aanpassen en uitvoeren
-- =================================================================

-- INSTRUCTIES:
-- 1. Vervang de waarden hieronder met jouw vraag informatie
-- 2. Pas het volgorde nummer aan (verhoog met 1 voor elke nieuwe vraag)
-- 3. Voer het script uit
-- 4. Gebruik daarna de admin interface om standpunten aan te passen

-- =================================================================
-- STAP 1: VOEG DE VRAAG TOE
-- =================================================================

INSERT INTO stemwijzer_questions (
    title, 
    description, 
    context, 
    left_view, 
    right_view, 
    order_number, 
    is_active,
    created_at,
    updated_at
) VALUES (
    'Nederland moet de staat Palestina officieel erkennen',
    'Sommige landen hebben Palestina als onafhankelijke staat erkend, andere wachten op een vredesakkoord tussen Israël en de Palestijnen. In Nederland pleiten sommige partijen voor directe erkenning om Palestijnse onafhankelijkheid te steunen. Tegenstanders vinden dat erkenning pas zinvol is na onderhandelingen. Deze stelling gaat over de vraag of Nederland nu, onafhankelijk van een vredesakkoord, Palestina als staat moet erkennen.',
    'Sinds de Oslo-akkoorden in de jaren 90 streven veel landen naar een tweestatenoplossing. Palestina wordt al door ruim 140 landen erkend, waaronder Zweden, Spanje, Ierland en Slovenië. Nederland erkent Palestina nog niet als staat, maar ondersteunt de Palestijnse gebieden financieel. De recente escalatie van geweld tussen Israël en Hamas heeft de roep om erkenning opnieuw aangewakkerd. Ook in de VN wordt gesproken over het lidmaatschap van Palestina.',
    'Erkenning van Palestina is een belangrijke stap naar gelijkheid en vrede. Het toont solidariteit met het Palestijnse volk en versterkt hun positie in internationale onderhandelingen.',
    'Zonder vredesakkoord heeft erkenning geen betekenis en kan het spanningen vergroten. Nederland moet neutraal blijven en eerst inzetten op diplomatie en veiligheid.',
    26, -- VERHOOG DIT GETAL VOOR ELKE NIEUWE VRAAG
    1,   -- 1 = actief, 0 = inactief
    NOW(),
    NOW()
);

-- =================================================================
-- STAP 2: VOEG STANDPUNTEN TOE VOOR ALLE PARTIJEN
-- =================================================================
-- Deze sectie voegt standpunten toe voor alle partijen met specifieke posities

-- Haal het ID van de nieuw toegevoegde vraag op
SET @laatst_toegevoegde_vraag_id = LAST_INSERT_ID();

-- Voeg standpunten toe voor specifieke partijen
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @laatst_toegevoegde_vraag_id,
    sp.id,
    CASE 
        WHEN sp.name = 'PVV' THEN 'oneens'
        WHEN sp.name = 'VVD' THEN 'oneens'
        WHEN sp.name = 'NSC' THEN 'neutraal'
        WHEN sp.name = 'BBB' THEN 'oneens'
        WHEN sp.name = 'GL-PvdA' THEN 'eens'
        WHEN sp.name = 'D66' THEN 'eens'
        WHEN sp.name = 'SP' THEN 'eens'
        WHEN sp.name = 'PvdD' THEN 'eens'
        WHEN sp.name = 'CDA' THEN 'neutraal'
        WHEN sp.name = 'JA21' THEN 'oneens'
        WHEN sp.name = 'SGP' THEN 'oneens'
        WHEN sp.name = 'FvD' THEN 'oneens'
        WHEN sp.name = 'DENK' THEN 'eens'
        WHEN sp.name = 'Volt' THEN 'eens'
        ELSE 'neutraal'
    END,
    CASE 
        WHEN sp.name = 'PVV' THEN 'Erkenning zou de veiligheid van Israël ondermijnen en extremisme legitimeren.'
        WHEN sp.name = 'VVD' THEN 'Eerst een duurzaam vredesakkoord voordat erkenning overwogen kan worden.'
        WHEN sp.name = 'NSC' THEN 'Erkenning mag geen symbolisch gebaar zijn, maar moet onderdeel zijn van een realistisch vredesproces.'
        WHEN sp.name = 'BBB' THEN 'Nederland moet zich niet mengen in een conflict zonder uitzicht op vrede.'
        WHEN sp.name = 'GL-PvdA' THEN 'Palestina verdient erkenning als stap naar een eerlijke tweestatenoplossing.'
        WHEN sp.name = 'D66' THEN 'Vroegtijdige erkenning versterkt de positie van gematigde Palestijnse leiders.'
        WHEN sp.name = 'SP' THEN 'Palestina erkennen is een kwestie van rechtvaardigheid en internationale solidariteit.'
        WHEN sp.name = 'PvdD' THEN 'Eerlijke vrede begint met wederzijdse erkenning en gelijke behandeling.'
        WHEN sp.name = 'CDA' THEN 'Erkenning moet volgen uit onderhandelingen, niet voorafgaan daaraan.'
        WHEN sp.name = 'JA21' THEN 'Nederland moet zich terughoudend opstellen in internationale conflicten.'
        WHEN sp.name = 'SGP' THEN 'Israël heeft bestaansrecht; erkenning van Palestina mag dat niet ondermijnen.'
        WHEN sp.name = 'FvD' THEN 'Buitenlandse conflicten zijn geen Nederlandse verantwoordelijkheid.'
        WHEN sp.name = 'DENK' THEN 'Palestina moet erkend worden om een gelijkwaardige dialoog mogelijk te maken.'
        WHEN sp.name = 'Volt' THEN 'Erkenning van Palestina is een noodzakelijke stap voor duurzame vrede in de regio.'
        ELSE CONCAT(sp.name, ' heeft nog geen standpunt ingenomen over dit onderwerp.')
    END,
    NOW(),
    NOW()
FROM stemwijzer_parties sp
WHERE sp.is_active = 1;

-- =================================================================
-- STAP 3: CONTROLEER HET RESULTAAT
-- =================================================================

-- Toon de nieuw toegevoegde vraag
SELECT 
    'NIEUWE VRAAG TOEGEVOEGD:' as status,
    q.id,
    q.title,
    q.order_number,
    q.is_active
FROM stemwijzer_questions q 
WHERE q.id = @laatst_toegevoegde_vraag_id;

-- Toon hoeveel standpunten zijn toegevoegd
SELECT 
    'STANDPUNTEN TOEGEVOEGD:' as status,
    COUNT(*) as aantal_partijen
FROM stemwijzer_positions 
WHERE question_id = @laatst_toegevoegde_vraag_id;

-- Toon overzicht van alle standpunten
SELECT 
    'OVERZICHT STANDPUNTEN:' as status,
    p.name as partij_naam,
    pos.position as standpunt,
    pos.explanation as uitleg
FROM stemwijzer_positions pos
JOIN stemwijzer_parties p ON pos.party_id = p.id
WHERE pos.question_id = @laatst_toegevoegde_vraag_id
ORDER BY p.name;

-- =================================================================
-- KLAAR!
-- =================================================================
-- Je nieuwe vraag is toegevoegd!
-- Ga nu naar de admin interface om de partij standpunten aan te passen:
-- - admin/stemwijzer-standpunten-beheer.php
-- ================================================================= 