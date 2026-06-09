-- Update political_parties naar de stand van juni 2026.
--
-- 1) Voegt 50PLUS toe (teruggekeerd in de Tweede Kamer met 2 zetels in 2025).
-- 2) Deactiveert NSC (verdween naar 0 zetels en zit niet meer in de Kamer).
-- 3) Corrigeert de FvD-leider (Lidewij de Vos volgde Thierry Baudet op).
-- 4) Zet de partijkleuren gelijk aan de merkkleuren (getPartyColor).
-- 5) Wijst betrouwbare lokale lijsttrekkersfoto's toe waar die zijn ververst.
--
-- Idempotent: opnieuw draaien is veilig.

-- 1) 50PLUS toevoegen of bijwerken
INSERT INTO political_parties
    (party_key, name, leader, logo, leader_photo, description, leader_info,
     standpoints, current_seats, tk2023_seats, polling, perspectives, color, is_active)
VALUES
    ('50PLUS', '50PLUS', 'Jan Struijs',
     '/public/images/party-logos/50plus.svg',
     '/partijleiders/struijs.jpg',
     '50PLUS is de partij die opkomt voor ouderen: bescherming van de AOW en pensioenen, goede ouderenzorg en betaalbaar wonen. Na jaren van afwezigheid keerde de partij in 2025 onder Jan Struijs terug in de Tweede Kamer.',
     'Jan Struijs is oud-voorzitter van de Nederlandse Politiebond. Als lijsttrekker leidde hij 50PLUS in 2025 terug naar de Tweede Kamer en bracht hij rust na jaren van interne conflicten. Hij profileert 50PLUS als een brede partij die met zowel links als rechts kan samenwerken.',
     '{"AOW en pensioen":"Bescherming en verhoging van de AOW en eerlijke pensioenen.","Ouderenzorg":"Goede en betaalbare ouderenzorg dichtbij huis.","Wonen":"Meer woningen voor ouderen en meer-generatiewoningen.","Migratie":"Aanpassing van het Vluchtelingenverdrag en grip op migratie."}',
     2, 0,
     '{"seats":2,"percentage":1.3,"change":0,"source":"Schatting (juni 2026)","date":"2026-06-01"}',
     '{"left":"50PLUS verdedigt de AOW, pensioenen en publieke ouderenzorg en werkt graag samen met links aan bestaanszekerheid.","right":"De partij wil grip op migratie en een aangepast Vluchtelingenverdrag en zoekt daarin samenwerking met rechts."}',
     '#92278F', 1)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    leader = VALUES(leader),
    logo = VALUES(logo),
    leader_photo = VALUES(leader_photo),
    description = VALUES(description),
    leader_info = VALUES(leader_info),
    standpoints = VALUES(standpoints),
    current_seats = VALUES(current_seats),
    tk2023_seats = VALUES(tk2023_seats),
    polling = VALUES(polling),
    perspectives = VALUES(perspectives),
    color = VALUES(color),
    is_active = VALUES(is_active);

-- 2) NSC deactiveren (geen zetels meer in de Kamer)
UPDATE political_parties SET is_active = 0 WHERE party_key = 'NSC';

-- 3) FvD-leiderschap corrigeren
UPDATE political_parties
SET leader = 'Lidewij de Vos',
    leader_photo = '/partijleiders/lidewij.jpg',
    leader_info = 'Lidewij de Vos (1997) studeerde biochemie en neurowetenschappen en is violiste. In 2025 nam zij het lijsttrekkerschap en fractievoorzitterschap van Forum voor Democratie over van oprichter Thierry Baudet, die partijvoorzitter bleef. Onder haar leiding groeide FvD van 3 naar 7 zetels.'
WHERE party_key = 'FvD';

-- 4) Merkkleuren herstellen
UPDATE political_parties SET color = '#0078D7' WHERE party_key = 'PVV';
UPDATE political_parties SET color = '#FF9900' WHERE party_key = 'VVD';
UPDATE political_parties SET color = '#00B13C' WHERE party_key = 'D66';
UPDATE political_parties SET color = '#008800' WHERE party_key = 'GL-PvdA';
UPDATE political_parties SET color = '#1E8449' WHERE party_key = 'CDA';
UPDATE political_parties SET color = '#0066CC' WHERE party_key = 'JA21';
UPDATE political_parties SET color = '#811E1E' WHERE party_key = 'FvD';
UPDATE political_parties SET color = '#95c119' WHERE party_key = 'BBB';
UPDATE political_parties SET color = '#EE0000' WHERE party_key = 'SP';
UPDATE political_parties SET color = '#007E3A' WHERE party_key = 'PvdD';
UPDATE political_parties SET color = '#FF6600' WHERE party_key = 'SGP';
UPDATE political_parties SET color = '#00b7b2' WHERE party_key = 'DENK';
UPDATE political_parties SET color = '#00AEEF' WHERE party_key = 'CU';
UPDATE political_parties SET color = '#502379' WHERE party_key = 'Volt';
UPDATE political_parties SET color = '#92278F' WHERE party_key = '50PLUS';

-- 5) Lokale lijsttrekkersfoto's toewijzen (na vervanging van leiders)
UPDATE political_parties SET leader_photo = '/partijleiders/klaver.jpg' WHERE party_key = 'GL-PvdA';
UPDATE political_parties SET leader_photo = '/partijleiders/bikker.jpg' WHERE party_key = 'CU';
