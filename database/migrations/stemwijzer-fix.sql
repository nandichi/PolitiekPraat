-- -----------------------------
-- 1. We gebruiken de bestaande database
-- -----------------------------
-- We gebruiken de bestaande database (politiek_db lokaal of naoufal_politiekpraat_db in productie)
-- zoals gedefinieerd in includes/config.php

-- -----------------------------
-- 2. Tabellen aanmaken
-- -----------------------------
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS parties;

CREATE TABLE parties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  logo_url VARCHAR(255) DEFAULT NULL
);

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  context TEXT DEFAULT NULL,
  left_view TEXT DEFAULT NULL,
  right_view TEXT DEFAULT NULL
);

CREATE TABLE positions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  party_id INT NOT NULL,
  position ENUM('eens','oneens','neutraal') NOT NULL,
  explanation TEXT,
  CONSTRAINT fk_question
    FOREIGN KEY (question_id) REFERENCES questions(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_party
    FOREIGN KEY (party_id) REFERENCES parties(id)
    ON DELETE CASCADE
);

-- -----------------------------
-- 3. Partijen (14 stuks) invoeren
-- -----------------------------
INSERT INTO parties (name, logo_url) VALUES
('PVV', 'https://i.ibb.co/DfR8pS2Y/403880390-713625330344634-198487231923339026-n.jpg'),
('VVD', 'https://logo.clearbit.com/vvd.nl'),
('NSC', 'https://i.ibb.co/YT2fJZb4/nsc.png'),
('BBB', 'https://i.ibb.co/qMjw7jDV/bbb.png'),
('GL-PvdA', 'https://i.ibb.co/67hkc5Hv/gl-pvda.png'),
('D66', 'https://logo.clearbit.com/d66.nl'),
('SP', 'https://logo.clearbit.com/sp.nl'),
('PvdD', 'https://logo.clearbit.com/partijvoordedieren.nl'),
('CDA', 'https://logo.clearbit.com/cda.nl'),
('JA21', 'https://logo.clearbit.com/ja21.nl'),
('SGP', 'https://logo.clearbit.com/sgp.nl'),
('FvD', 'https://logo.clearbit.com/fvd.nl'),
('DENK', 'https://logo.clearbit.com/bewegingdenk.nl'),
('Volt', 'https://logo.clearbit.com/voltnederland.org');

-- -----------------------------
-- 4. Vragen invoeren (25 stuks)
--    Let op: id wordt AUTO_INCREMENT.
--    We nemen exact titel, description, context, left_view, right_view over.
-- -----------------------------

INSERT INTO questions (title, description, context, left_view, right_view)
VALUES
-- 1
('Asielbeleid',
 'Nederland moet een strenger asielbeleid voeren met een asielstop en lagere immigratiecijfers.',
 'Bij deze stelling gaat het erom hoe Nederland omgaat met mensen die asiel aanvragen. Een strenger asielbeleid betekent dat er strengere regels komen en dat minder mensen worden toegelaten. Een asielstop betekent dat er tijdelijk helemaal geen nieuwe asielzoekers worden toegelaten. Dit onderwerp gaat over de balans tussen veiligheid, controle en humanitaire zorg.',
 'Vinden dat Nederland humaan moet blijven en vluchtelingen moet opvangen. Zij vinden dat mensen in nood geholpen moeten worden.',
 'Willen de instroom van asielzoekers beperken omdat zij vinden dat dit de druk op de samenleving verlaagt.'
),
-- 2
('Klimaatmaatregelen',
 'Nederland moet vooroplopen in de klimaattransitie, ook als dit op korte termijn economische groei kost.',
 'Deze stelling gaat over hoe snel Nederland moet overschakelen naar een klimaatvriendelijke economie. Het idee is dat we sneller moeten handelen om de opwarming van de aarde te stoppen. Dit kan betekenen dat bedrijven moeten investeren in nieuwe, duurzame technologieën en dat producten op korte termijn duurder worden. Het onderwerp gaat over de afweging tussen het beschermen van het milieu en de mogelijke economische nadelen op de korte termijn.',
 'Vinden dat Nederland snel actie moet ondernemen om de opwarming van de aarde tegen te gaan, ook als dit even wat kosten met zich meebrengt.',
 'Zien dat verduurzaming belangrijk is, maar vinden dat dit niet te snel mag gaan zodat bedrijven en burgers niet te veel last krijgen van de kosten.'
),
-- 3
('Eigen Risico Zorg',
 'Het eigen risico in de zorg moet worden afgeschaft.',
 'Het eigen risico is het bedrag dat je zelf moet betalen voordat de zorgverzekering de rest van de kosten vergoedt. Momenteel is dit ongeveer 385 euro per jaar. Het idee achter deze stelling is dat iedereen direct de zorg kan krijgen zonder eerst zelf te moeten betalen, zodat vooral mensen met een laag inkomen niet worden benadeeld.',
 'Vinden dat het eigen risico vooral mensen met een laag inkomen te veel kost. Zij willen dat iedereen zonder financiële zorgen zorg kan krijgen.',
 'Vinden dat het eigen risico nodig is om de zorgkosten beheersbaar te houden en dat mensen bewuster met zorg omgaan als ze een deel zelf moeten betalen.'
);

-- --------------------------------------
-- 5. Positions invoeren (voorbeelddata voor de eerste 3 vragen)
--    We koppelen elke vraag aan elke partij met hun position + uitleg.
-- --------------------------------------

-- Voor question_id 1 (Asielbeleid):
INSERT INTO positions (question_id, party_id, position, explanation) VALUES
(1, 1, 'eens',    'Deze partij steunt een strenger asielbeleid met een volledige asielstop. Zij vinden dat Nederland zo de controle over migratie behoudt.'),
(1, 2, 'eens',    'VVD pleit voor een strengere selectie en beperking van asielaanvragen, maar met internationale samenwerking.'),
(1, 3, 'neutraal','NSC wil een eerlijk maar streng asielbeleid waarbij de menselijke maat centraal staat.'),
(1, 4, 'eens',    'BBB zet in op strengere maatregelen en het sluiten van de grenzen voor asielzoekers.'),
(1, 5, 'oneens',  'GL-PvdA vindt dat Nederland vluchtelingen moet blijven opvangen en pleit voor een humaan asielbeleid.'),
(1, 6, 'oneens',  'D66 wil een balans tussen strengere procedures en menselijkheid in het asielbeleid.'),
(1, 7, 'oneens',  'SP verzet zich tegen een asielstop, maar pleit wel voor betere spreiding van asielzoekers.'),
(1, 8, 'oneens',  'PvdD is tegen het sluiten van de grenzen en pleit voor een rechtvaardig asielbeleid.'),
(1, 9, 'neutraal','CDA zoekt een balans tussen het verminderen van asielaantallen en het bieden van bescherming aan echte vluchtelingen.'),
(1, 10, 'eens',   'JA21 steunt een asielstop en stelt dat Nederland niet meer asielzoekers kan opvangen.'),
(1, 11, 'eens',   'SGP vindt dat een strengere aanpak nodig is om grip te krijgen op de instroom.'),
(1, 12, 'eens',   'FvD wil een volledige immigratiestop.'),
(1, 13, 'oneens', 'DENK vindt dat mensen in nood recht hebben op hulp en Nederland gastvrij moet blijven.'),
(1, 14, 'oneens', 'Volt pleit voor Europese samenwerking en vindt een asielstop geen oplossing.');

-- question_id 2 (Klimaatmaatregelen):
INSERT INTO positions (question_id, party_id, position, explanation) VALUES
(2, 1, 'oneens',   'PVV verzet zich tegen ambitieuze klimaatmaatregelen als deze ten koste gaan van economische groei.'),
(2, 2, 'oneens',   'VVD ondersteunt klimaatmaatregelen, maar vindt dat de economie niet op de achtergrond mag raken.'),
(2, 3, 'neutraal', 'NSC pleit voor realistische klimaatdoelen waarbij duurzaamheid en economische groei in balans zijn.'),
(2, 4, 'oneens',   'BBB vindt dat klimaatmaatregelen realistisch moeten zijn en niet ten koste mogen gaan van economische groei.'),
(2, 5, 'eens',     'GL-PvdA wil dat Nederland vooroploopt in de strijd tegen klimaatverandering, zelfs als dit op korte termijn geld kost.'),
(2, 6, 'eens',     'D66 pleit voor ambitieuze klimaatmaatregelen en ziet dit als een kans voor economische vernieuwing.'),
(2, 7, 'neutraal', 'SP wil dat klimaatmaatregelen eerlijk verdeeld worden en niet alleen op de schouders van gewone mensen rusten.'),
(2, 8, 'eens',     'PvdD vindt dat ​​de klimaatcrisis prioriteit moet krijgen boven economische groei.'),
(2, 9, 'neutraal', 'CDA wil dat klimaatmaatregelen haalbaar en betaalbaar zijn voor iedereen.'),
(2, 10, 'oneens',  'JA21 vindt dat klimaatmaatregelen realistisch moeten zijn en economische schade moeten voorkomen.'),
(2, 11, 'neutraal','SGP steunt klimaatbeleid dat balans houdt tussen duurzaamheid en economische belangen.'),
(2, 12, 'oneens',  'FvD is tegen het huidige klimaatbeleid en keurt drastische maatregelen af.'),
(2, 13, 'neutraal','DENK pleit voor een eerlijke klimaattransitie waarbij rekening wordt gehouden met verschillende inkomensgroepen.'),
(2, 14, 'eens',    'Volt wil dat Nederland en Europa vooroplopen in klimaatmaatregelen en een duurzame toekomst.'); 

-- question_id 3 (Eigen Risico Zorg):
INSERT INTO positions (question_id, party_id, position, explanation) VALUES
(3, 1, 'eens',     'PVV wil het eigen risico volledig afschaffen zodat iedereen direct toegang heeft tot zorg.'),
(3, 2, 'oneens',   'VVD vindt dat het eigen risico helpt om zorgkosten beheersbaar te houden en stimuleert verantwoordelijk gebruik.'),
(3, 3, 'neutraal', 'NSC wil het eigen risico hervormen zodat gezondheidszorg toegankelijk blijft voor iedereen.'),
(3, 4, 'eens',     'BBB wil het eigen risico afschaffen omdat dit vooral mensen met een laag inkomen treft.'),
(3, 5, 'eens',     'GL-PvdA wil het eigen risico afschaffen en vervangen door een eerlijker systeem.'),
(3, 6, 'neutraal', 'D66 wil het eigen risico hervormen zodat mensen met chronische aandoeningen minder betalen.'),
(3, 7, 'eens',     'SP is voor afschaffing van het eigen risico omdat zorg voor iedereen betaalbaar moet zijn.'),
(3, 8, 'eens',     'PvdD wil het eigen risico afschaffen zodat zorgkosten eerlijker worden verdeeld.'),
(3, 9, 'neutraal', 'CDA wil de hoogte van het eigen risico aanpassen om de zorg toegankelijker te maken.'),
(3, 10, 'oneens',  'JA21 vindt dat het eigen risico nodig is om de zorgkosten te beheersen.'),
(3, 11, 'neutraal','SGP wil het eigen risico behouden maar wel hervormen voor kwetsbare groepen.'),
(3, 12, 'eens',    'FvD wil het eigen risico afschaffen omdat dit een drempel opwerpt voor zorggebruik.'),
(3, 13, 'eens',    'DENK steunt afschaffing van het eigen risico om zorg toegankelijker te maken.'),
(3, 14, 'neutraal','Volt wil het eigen risico vervangen door een eerlijker systeem.');

-- Je kunt nu SELECT queries doen om de data op te halen.
-- Bijvoorbeeld:
--   SELECT * FROM questions;
--   SELECT * FROM parties;
--   SELECT q.title, p.name, s.position
--   FROM positions s
--   JOIN questions q ON s.question_id = q.id
--   JOIN parties p   ON s.party_id    = p.id
--   ORDER BY q.id, p.id; 