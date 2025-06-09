-- =================================================================
-- Script voor het toevoegen van een nieuwe stemwijzer vraag
-- =================================================================
-- Dit script helpt je om snel een nieuwe vraag toe te voegen aan de stemwijzer
-- inclusief alle partij standpunten in één keer.
--
-- INSTRUCTIES:
-- 1. Vervang de waarden tussen de '' aanhalingstekens
-- 2. Pas de standpunten aan bij "-- PARTIJ STANDPUNTEN SECTIE"
-- 3. Voer het hele script uit
--
-- =================================================================

-- =================================================================
-- VRAAG CONFIGURATIE
-- =================================================================
-- Pas deze waarden aan voor jouw nieuwe vraag:

SET @vraag_titel = 'Voorbeeld: Belasting op vlees';
SET @vraag_beschrijving = 'Er moet een belasting komen op vlees om de vleesconsumptie te verminderen en het milieu te beschermen.';
SET @vraag_context = 'Deze stelling gaat over het invoeren van een belasting op vleesproducten. Het doel is om mensen aan te moedigen minder vlees te eten, wat goed is voor het milieu omdat veeteelt veel CO2 uitstoot veroorzaakt. Tegelijkertijd betekent dit hogere prijzen voor consumenten en mogelijk economische nadelen voor boeren en vleesproducenten.';
SET @vraag_links_perspectief = 'Vinden dat een vleesbelasting nodig is om de klimaatdoelen te halen en mensen aan te moedigen duurzamer te eten.';
SET @vraag_rechts_perspectief = 'Vinden dat zo een belasting mensen beperkt in hun vrijheid en vooral mensen met lage inkomens treft.';
SET @vraag_volgorde = 26; -- Verhoog dit getal voor elke nieuwe vraag
SET @vraag_actief = 1; -- 1 = actief, 0 = inactief

-- =================================================================
-- VRAAG TOEVOEGEN
-- =================================================================

-- Controleer eerst of het volgorde nummer al bestaat
SELECT 'CHECKING ORDER NUMBER...' as status;
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN CONCAT('WARNING: Order number ', @vraag_volgorde, ' already exists!')
        ELSE CONCAT('OK: Order number ', @vraag_volgorde, ' is available')
    END as order_check_result
FROM stemwijzer_questions 
WHERE order_number = @vraag_volgorde;

-- Voeg de vraag toe
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
    @vraag_titel,
    @vraag_beschrijving,
    @vraag_context,
    @vraag_links_perspectief,
    @vraag_rechts_perspectief,
    @vraag_volgorde,
    @vraag_actief,
    NOW(),
    NOW()
);

-- Haal het ID van de nieuwe vraag op
SET @nieuw_vraag_id = LAST_INSERT_ID();

SELECT CONCAT('SUCCESS: Question added with ID ', @nieuw_vraag_id) as result;

-- =================================================================
-- PARTIJ STANDPUNTEN SECTIE
-- =================================================================
-- Hier kun je voor elke partij het standpunt instellen
-- Beschikbare posities: 'eens', 'neutraal', 'oneens'
--
-- INSTRUCTIES:
-- - Vervang 'neutraal' met 'eens' of 'oneens' waar nodig
-- - Pas de uitleg aan voor elke partij
-- - Laat regels onveranderd voor standaard 'neutraal' standpunt

-- PVV (Partij voor de Vrijheid)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'PVV heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'PVV';

-- VVD (Volkspartij voor Vrijheid en Democratie)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'VVD heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'VVD';

-- NSC (Nieuw Sociaal Contract)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'NSC heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'NSC';

-- BBB (BoerBurgerBeweging)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'BBB heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'BBB';

-- GL-PvdA (GroenLinks-PvdA)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'GL-PvdA heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'GL-PvdA';

-- D66 (Democraten 66)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'D66 heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'D66';

-- SP (Socialistische Partij)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'SP heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'SP';

-- PvdD (Partij voor de Dieren)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'PvdD heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'PvdD';

-- CDA (Christen-Democratisch Appèl)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'CDA heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'CDA';

-- JA21 (Juiste Antwoord 2021)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'JA21 heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'JA21';

-- SGP (Staatkundig Gereformeerde Partij)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'SGP heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'SGP';

-- FvD (Forum voor Democratie)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'FvD heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'FvD';

-- DENK
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'DENK heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'DENK';

-- Volt
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'Volt heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'Volt';

-- ChristenUnie
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal', -- Verander naar 'eens' of 'oneens'
    'ChristenUnie heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.', -- Pas aan
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'CU';

-- =================================================================
-- RESULTAAT CONTROLE
-- =================================================================

SELECT 'FINAL RESULT:' as status;

-- Toon de toegevoegde vraag
SELECT 
    q.id,
    q.title,
    q.order_number,
    q.is_active,
    q.created_at
FROM stemwijzer_questions q 
WHERE q.id = @nieuw_vraag_id;

-- Tel hoeveel standpunten zijn toegevoegd
SELECT 
    COUNT(*) as total_positions_added,
    CONCAT('Positions added for question ID ', @nieuw_vraag_id) as note
FROM stemwijzer_positions 
WHERE question_id = @nieuw_vraag_id;

-- Toon alle standpunten voor de nieuwe vraag
SELECT 
    p.name as party_name,
    pos.position,
    pos.explanation
FROM stemwijzer_positions pos
JOIN stemwijzer_parties p ON pos.party_id = p.id
WHERE pos.question_id = @nieuw_vraag_id
ORDER BY p.name;

-- =================================================================
-- KLAAR!
-- =================================================================
-- Je nieuwe vraag is toegevoegd met ID: @nieuw_vraag_id
-- Alle partijen hebben standpunt 'neutraal' gekregen als standaard
-- Je kunt nu via de admin interface de standpunten aanpassen
-- ================================================================= 