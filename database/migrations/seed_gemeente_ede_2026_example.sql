-- Voorbeeld seed voor Gemeentelijke Stemwijzer Ede 2026 (MVP)
-- Doel: 1 gemeente + 1 verkiezing + 1 stellingversie + sample partijpositie + weging

SET NAMES utf8mb4;

INSERT INTO municipalities (name, slug, province, cbs_code, country_code, is_active)
VALUES ('Ede', 'ede', 'Gelderland', '0228', 'NL', 1)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    province = VALUES(province),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO elections (election_type, election_scope, election_year, election_date, status, title, description, is_active)
VALUES ('gemeenteraad', 'municipal', 2026, '2026-03-18', 'draft', 'Gemeenteraadsverkiezingen 2026', 'Gemeentelijke stemwijzer scope-lock: Ede 2026', 1)
ON DUPLICATE KEY UPDATE
    election_date = VALUES(election_date),
    status = VALUES(status),
    title = VALUES(title),
    description = VALUES(description),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO election_municipalities (election_id, municipality_id, status, weighting_enabled, expected_theses_count)
SELECT e.id, m.id, 'draft', 1, 25
FROM elections e
JOIN municipalities m ON m.slug = 'ede' AND m.country_code = 'NL'
WHERE e.election_type = 'gemeenteraad' AND e.election_scope = 'municipal' AND e.election_year = 2026
ON DUPLICATE KEY UPDATE
    weighting_enabled = VALUES(weighting_enabled),
    expected_theses_count = VALUES(expected_theses_count),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO election_parties (election_municipality_id, party_name, party_key, sort_order, is_active, source_label)
SELECT em.id, 'Voorbeeld Partij Ede', 'VPE', 1, 1, 'seed-example'
FROM election_municipalities em
JOIN elections e ON e.id = em.election_id
JOIN municipalities m ON m.id = em.municipality_id
WHERE e.election_type = 'gemeenteraad' AND e.election_year = 2026 AND m.slug = 'ede'
ON DUPLICATE KEY UPDATE
    party_name = VALUES(party_name),
    sort_order = VALUES(sort_order),
    is_active = VALUES(is_active),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO theses (election_municipality_id, thesis_code, title, description, theme, sort_order, is_active)
SELECT em.id, 'EDE-2026-001', 'Er moeten meer betaalbare woningen in Ede komen.', 'Seed-stelling voor technische validatie van versiebeheer.', 'Wonen', 1, 1
FROM election_municipalities em
JOIN elections e ON e.id = em.election_id
JOIN municipalities m ON m.id = em.municipality_id
WHERE e.election_type = 'gemeenteraad' AND e.election_year = 2026 AND m.slug = 'ede'
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    description = VALUES(description),
    theme = VALUES(theme),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO thesis_versions (thesis_id, version_number, status, is_current, statement_text, context_text, left_label, right_label, change_note, published_at)
SELECT t.id, 1, 'draft', 1,
       'De gemeente Ede moet extra investeren in betaalbare woningbouw.',
       'Technische seedversie voor Sprint 1 datamodelvalidatie.',
       'Oneens', 'Eens', 'Initiële seedversie', NULL
FROM theses t
JOIN election_municipalities em ON em.id = t.election_municipality_id
JOIN elections e ON e.id = em.election_id
JOIN municipalities m ON m.id = em.municipality_id
WHERE t.thesis_code = 'EDE-2026-001' AND e.election_year = 2026 AND m.slug = 'ede'
ON DUPLICATE KEY UPDATE
    statement_text = VALUES(statement_text),
    context_text = VALUES(context_text),
    is_current = VALUES(is_current),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO thesis_weights (thesis_version_id, weight_value, weighting_enabled, rationale)
SELECT tv.id, 2, 1, 'MVP-voorbeeldweging actief voor Ede 2026'
FROM thesis_versions tv
JOIN theses t ON t.id = tv.thesis_id
WHERE t.thesis_code = 'EDE-2026-001' AND tv.version_number = 1
ON DUPLICATE KEY UPDATE
    weight_value = VALUES(weight_value),
    weighting_enabled = VALUES(weighting_enabled),
    rationale = VALUES(rationale),
    updated_at = CURRENT_TIMESTAMP;

INSERT INTO party_positions (election_party_id, thesis_version_id, position, weight_multiplier, explanation, source_label)
SELECT ep.id, tv.id, 'eens', 1.00, 'Voorbeeldstandpunt voor datamodel-validatie.', 'seed-example'
FROM election_parties ep
JOIN election_municipalities em ON em.id = ep.election_municipality_id
JOIN elections e ON e.id = em.election_id
JOIN municipalities m ON m.id = em.municipality_id
JOIN theses t ON t.election_municipality_id = em.id AND t.thesis_code = 'EDE-2026-001'
JOIN thesis_versions tv ON tv.thesis_id = t.id AND tv.version_number = 1
WHERE ep.party_key = 'VPE' AND e.election_year = 2026 AND m.slug = 'ede'
ON DUPLICATE KEY UPDATE
    position = VALUES(position),
    weight_multiplier = VALUES(weight_multiplier),
    explanation = VALUES(explanation),
    updated_at = CURRENT_TIMESTAMP;
