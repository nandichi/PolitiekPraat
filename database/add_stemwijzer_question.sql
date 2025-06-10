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

SET @vraag_titel = 'Nederland moet een vermogensbelasting invoeren voor mensen met meer dan €1 miljoen vermogen om de ongelijkheid te verkleinen';
SET @vraag_beschrijving = 'De vermogensongelijkheid in Nederland is groot en groeit nog steeds. De rijkste 10% van de Nederlanders bezit ongeveer 60% van al het vermogen, terwijl de armste 50% slechts 5% bezit. Door inflatie en stijgende huizenprijzen wordt het verschil tussen arm en rijk steeds groter. Veel jongeren kunnen geen huis meer kopen terwijl rijke mensen steeds rijker worden door hun bezittingen. Een vermogensbelasting zou de overheid extra inkomsten geven en de ongelijkheid kunnen verkleinen, maar tegenstanders vrezen dat rijken dan naar het buitenland vertrekken.';
SET @vraag_context = 'Nederland heeft momenteel geen echte vermogensbelasting meer sinds de afschaffing in 2001. In Box 3 wordt wel een fictief rendement belast, maar dit raakt niet het echte vermogen. Verschillende organisaties zoals Oxfam Novib pleiten voor herinvoering. Andere Europese landen zoals Frankrijk hebben wel een vermogensbelasting. De COVID-19 crisis heeft de ongelijkheid verder vergroot omdat kapitaalbezitters profiteerden van stijgende prijzen terwijl werknemers hun baan verloren. Tegelijk heeft de overheid forse schulden gemaakt om de crisis op te vangen.';
SET @vraag_links_perspectief = 'Extreme vermogensongelijkheid is slecht voor de samenleving en democratie. Rijken hebben jarenlang geprofiteerd van belastingvoordelen terwijl gewone mensen de rekening betalen. Een vermogensbelasting is eerlijk omdat vermogen vaak bestaat uit bezittingen die door de samenleving mogelijk zijn gemaakt, zoals huizen die in waarde stijgen door overheidsinvesteringen. De opbrengsten kunnen gebruikt worden voor betere zorg, onderwijs en betaalbare woningen. Andere landen tonen aan dat het kan zonder massale vlucht van rijken.';
SET @vraag_rechts_perspectief = 'Een vermogensbelasting verstoort de economie en jaagt ondernemers en investeerders weg naar het buitenland. Dit kost uiteindelijk meer banen en belastinginkomsten dan het oplevert. Vermogen is vaak al opgebouwd uit al belast inkomen, dus dit is dubbele belasting. Het ondermijnt spaarincentives en ondernemerschap. Administratief is het complex en duur om uit te voeren. Frankrijk schafte de vermogensbelasting uiteindelijk ook af omdat het niet werkte. De vrije markt verdeelt welvaart het beste.';
SET @vraag_volgorde = 34;
SET @vraag_actief = 1;

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
    'oneens',
    'Nederlandse families worden al genoeg belast. Eerst de massa-immigratie stoppen die geld kost.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'PVV';

-- VVD (Volkspartij voor Vrijheid en Democratie)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'oneens',
    'Vermogensbelasting jaagt ondernemers weg en schaadt de economie. Lagere belastingen voor iedereen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'VVD';

-- NSC (Nieuw Sociaal Contract)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal',
    'Ongelijkheid is probleem, maar vermogensbelasting moet goed uitgewerkt worden om kapitaalvlucht te voorkomen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'NSC';

-- BBB (BoerBurgerBeweging)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'oneens',
    'Boerenfamilies met bedrijfsvermogen zouden oneerlijk geraakt worden. Focus op echte problemen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'BBB';

-- GL-PvdA (GroenLinks-PvdA)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'Vermogensongelijkheid ondermijnt de samenleving. Rijken moeten eerlijk bijdragen aan publieke voorzieningen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'GL-PvdA';

-- D66 (Democraten 66)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'Gelijke kansen vereisen eerlijke belastingverdeling. Vermogensbelasting kan ongelijkheid verkleinen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'D66';

-- SP (Socialistische Partij)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'De superrijken moeten eindelijk hun eerlijke deel betalen. Vermogensbelasting is sociale rechtvaardigheid.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'SP';

-- PvdD (Partij voor de Dieren)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'Extreme rijkdom leidt tot overconsumptie en milieuschade. Herverdeling is ook goed voor het milieu.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'PvdD';

-- CDA (Christen-Democratisch Appèl)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal',
    'Solidariteit is belangrijk, maar vermogensbelasting moet uitvoerbaar zijn en niet contraproductief.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'CDA';

-- JA21 (Juiste Antwoord 2021)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'oneens',
    'Vermogensbelasting is socialistische omvolkingspolitiek die de middenklasse kapot maakt.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'JA21';

-- SGP (Staatkundig Gereformeerde Partij)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal',
    'Bijbelse rentmeesterschap betekent ook verantwoordelijkheid van rijken, maar belasting moet rechtvaardige zijn.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'SGP';

-- FvD (Forum voor Democratie)
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'oneens',
    'Vermogensbelasting is diefstal door de staat. Nederland moet juist een belastingparadijs worden.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'FvD';

-- DENK
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'Ongelijkheid raakt migrantengemeenschappen hard. Rijken moeten bijdragen aan een eerlijke samenleving.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'DENK';

-- Volt
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'eens',
    'Europese coördinatie van vermogensbelasting kan kapitaalvlucht voorkomen en ongelijkheid verminderen.',
    NOW(),
    NOW()
FROM stemwijzer_parties WHERE short_name = 'Volt';

-- ChristenUnie
INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at)
SELECT 
    @nieuw_vraag_id,
    id,
    'neutraal',
    'ChristenUnie heeft nog geen duidelijk standpunt ingenomen over dit onderwerp.',
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