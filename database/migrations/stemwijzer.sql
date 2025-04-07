-- -----------------------------
-- 1. (Optioneel) Maak de database zelf aan
--    Pas de naam "stemwijzerdb" aan als je wilt
-- -----------------------------
CREATE DATABASE IF NOT EXISTS stemwijzerdb;
USE stemwijzerdb;

-- -----------------------------
-- 2. Tabellen aanmaken
-- -----------------------------
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS parties;

CREATE TABLE parties (
  party_id INT AUTO_INCREMENT PRIMARY KEY,
  party_name VARCHAR(100) NOT NULL UNIQUE,
  party_logo VARCHAR(255) DEFAULT NULL
);

CREATE TABLE questions (
  question_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  context TEXT NOT NULL,
  left_view TEXT NOT NULL,
  right_view TEXT NOT NULL
);

CREATE TABLE positions (
  position_id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  party_id INT NOT NULL,
  stance ENUM('eens','oneens','neutraal') NOT NULL,
  explanation TEXT,
  CONSTRAINT fk_question
    FOREIGN KEY (question_id) REFERENCES questions(question_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_party
    FOREIGN KEY (party_id) REFERENCES parties(party_id)
    ON DELETE CASCADE
);

-- -----------------------------
-- 3. Partijen (14 stuks) invoeren
-- -----------------------------
INSERT INTO parties (party_name, party_logo) VALUES
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
--    Let op: question_id wordt AUTO_INCREMENT.
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
),
-- 4
('Kernenergie',
 'Nederland moet investeren in nieuwe kerncentrales als onderdeel van de energietransitie.',
 'Deze stelling gaat over het bouwen van nieuwe kerncentrales om elektriciteit op te wekken. Kerncentrales produceren veel stroom zonder CO2-uitstoot, maar ze zorgen ook voor radioactief afval en hoge bouwkosten. Het debat gaat over de afweging tussen een betrouwbare energievoorziening en de risico\'s op het gebied van veiligheid en afvalbeheer.',
 'Zijn vaak tegen kernenergie omdat ze bezorgd zijn over veiligheid en afval. Zij willen liever investeren in zon en wind.',
 'Zien kernenergie als een schone en betrouwbare bron die nodig is naast andere duurzame energiebronnen.'
),
-- 5
('Woningmarkt',
 'Er moet een nationaal bouwprogramma komen waarbij de overheid zelf woningen gaat bouwen.',
 'Deze stelling richt zich op het oplossen van het tekort aan betaalbare woningen. In plaats van dat de markt zelf zorgt voor voldoende huizen, wordt voorgesteld dat de overheid een programma start om zelf woningen te bouwen. Het idee is dat zo meer controle is over de bouw en de prijzen, vooral voor sociale huurwoningen.',
 'Vinden dat de overheid moet ingrijpen omdat de markt er niet in slaagt voldoende betaalbare woningen te bouwen. Zij pleiten voor sociale huurwoningen.',
 'Vinden dat de markt dit beter kan oplossen en dat de overheid alleen regels moet versoepelen.'
),
-- 6
('Minimumloon',
 'Het minimumloon moet verder omhoog naar 16 euro per uur.',
 'Deze stelling gaat over het verhogen van het minimumloon, het laagste loon dat werkgevers wettelijk moeten betalen. Een hoger minimumloon kan zorgen voor meer inkomen voor werknemers, maar kan ook leiden tot hogere kosten voor bedrijven en mogelijk minder banen. Hier gaat het dus om de afweging tussen sociale zekerheid en economische haalbaarheid.',
 'Vinden dat een hoger minimumloon nodig is om werknemers een eerlijk loon te geven en armoede te voorkomen.',
 'Zien risico\'s in een verhoging omdat het banenverlies of hogere kosten voor werkgevers kan veroorzaken.'
),
-- 7
('Europese Unie',
 'Nederland moet uit de Europese Unie stappen (Nexit).',
 'Deze stelling gaat over het verlaten van de Europese Unie. Het debat richt zich op de vraag of Nederland meer regie over eigen beleid krijgt door uit de EU te stappen, of dat samenwerking binnen de EU juist zorgt voor economische en veiligheidsvoordelen. Hier wordt nagedacht over nationale soevereiniteit versus internationale samenwerking.',
 'Zien dat een vertrek de nationale soevereiniteit versterkt en Nederland meer regie geeft over eigen beleid.',
 'Vinden dat samenwerken binnen de EU belangrijk is voor de economie en veiligheid, ondanks enkele nadelen.'
),
-- 8
('Defensie-uitgaven',
 'Nederland moet de defensie-uitgaven verhogen naar minimaal 2% van het BBP.',
 'Deze stelling gaat over het verhogen van het geld dat Nederland uitgeeft aan defensie. Meer uitgaven kunnen zorgen voor een sterkere militaire positie en internationale veiligheid, maar het geld komt ten koste van andere uitgaven zoals zorg en onderwijs. Het gaat hier dus om de afweging tussen veiligheid en andere maatschappelijke behoeften.',
 'Zien een hogere uitgave als een investering in nationale en internationale veiligheid.',
 'Vinden dat extra geld voor defensie ten koste kan gaan van sociale voorzieningen en andere prioriteiten.'
),
-- 9
('Stikstofbeleid',
 'Het huidige stikstofbeleid moet worden versoepeld om boeren meer ruimte te geven.',
 'Deze stelling gaat over het aanpassen van de regels omtrent stikstof. Huidige regels zijn erg streng en kunnen boeren belemmeren in hun werkzaamheden. Versoepeling zou hen meer ruimte geven om economisch te floreren, maar dit kan ook nadelige gevolgen hebben voor natuur en milieu. Het debat gaat dus over de balans tussen agrarische belangen en de bescherming van natuur en biodiversiteit.',
 'Zien versoepeling als een manier om de economische positie van boeren te verbeteren.',
 'Vinden dat natuur- en milieubescherming voorop moet staan en de regels niet te los mogen worden.'
),
-- 10
('Studiefinanciering',
 'De basisbeurs voor studenten moet worden verhoogd.',
 'Deze stelling gaat over het verhogen van de studiefinanciering voor studenten. Een hogere basisbeurs kan studenten helpen om zich beter op hun studie te concentreren, zonder zich zorgen te maken over geld. Dit kost wel extra geld aan de overheid, maar kan leiden tot meer kansen in het onderwijs.',
 'Vinden dat een hogere basisbeurs studenten helpt om zich op hun studie te concentreren zonder financiële zorgen.',
 'Vinden dat een verhoging extra kosten met zich meebrengt en dat er ook gekeken moet worden naar efficiëntie in het systeem.'
),
-- 11
('Belastingen',
 'De belastingen voor grote bedrijven moeten omhoog.',
 'Deze stelling gaat over het verhogen van de belastingen voor grote bedrijven. Het doel hiervan is dat bedrijven een groter deel bijdragen aan de samenleving. Hierdoor komt er meer geld beschikbaar voor publieke voorzieningen zoals zorg, onderwijs en infrastructuur. Tegelijkertijd is er zorg dat te hoge belastingen de concurrentiepositie van bedrijven negatief beïnvloeden.',
 'Vinden dat grote bedrijven meer moeten bijdragen aan de samenleving zodat er extra geld is voor publieke zaken.',
 'Vinden dat hogere belastingen voor bedrijven de concurrentie en innovatie kunnen schaden.'
),
-- 12
('AOW-leeftijd',
 'De AOW-leeftijd moet worden verlaagd naar 65 jaar.',
 'Deze stelling gaat over het verlagen van de AOW-leeftijd, oftewel de leeftijd waarop mensen met pensioen kunnen gaan. Een lagere AOW-leeftijd kan ervoor zorgen dat ouderen eerder met pensioen kunnen gaan en meer rust ervaren. Tegelijkertijd kan dit de financiële druk op het pensioenstelsel vergroten, omdat er dan langer pensioenuitkeringen gedaan moeten worden.',
 'Vinden dat een lagere AOW-leeftijd nodig is voor een eerlijker pensioen en om ouderen meer rust te geven.',
 'Vinden dat de AOW-leeftijd aangepast moet worden aan de stijgende levensverwachting en financieel houdbaar moet zijn.'
),
-- 13
('Sociale Huurwoningen',
 'Woningcorporaties moeten voorrang krijgen bij het bouwen van nieuwe woningen.',
 'Deze stelling gaat over wie de hoofdrol moet spelen bij de bouw van nieuwe woningen. Het idee is dat woningcorporaties, die zich richten op sociale huurwoningen, voorrang krijgen. Hierdoor wordt er extra aandacht besteed aan betaalbare huisvesting voor mensen met een laag inkomen. Er wordt nagedacht over de rol van de overheid versus de markt in het oplossen van de woningnood.',
 'Vinden dat woningcorporaties als eerste aan de beurt moeten komen om de woningnood voor mensen met lage inkomens te verlichten.',
 'Vinden dat de markt dit beter kan oplossen en dat er ruimte moet zijn voor zowel publieke als private initiatieven.'
),
-- 14
('Ontwikkelingshulp',
 'Nederland moet bezuinigen op ontwikkelingshulp.',
 'Deze stelling gaat over het verminderen van de financiële hulp aan ontwikkelingslanden. Het idee is dat Nederland eerst zijn eigen problemen moet oplossen voordat het geld geeft aan andere landen. Tegelijkertijd speelt internationale solidariteit een rol. Er wordt dus gekeken naar de afweging tussen binnenlandse belangen en internationale hulpverplichtingen.',
 'Vinden dat Nederland eerst eigen prioriteiten moet oplossen en daarom minder geld aan ontwikkelingshulp moet uitgeven.',
 'Vinden dat ontwikkelingshulp belangrijk is voor internationale solidariteit en het bestrijden van armoede.'
),
-- 15
('Zorgverzekering',
 'Er moet één publieke zorgverzekering komen in plaats van verschillende private verzekeraars.',
 'Deze stelling gaat over de organisatie van de zorgverzekering. Momenteel kiezen mensen tussen verschillende private zorgverzekeraars. Het voorstel is om één publieke zorgverzekering in te voeren. Dit kan zorgen voor meer solidariteit en lagere kosten, maar kan ook de keuzevrijheid verminderen. De discussie gaat over de balans tussen efficiëntie en vrijheid in de zorg.',
 'Zien één publieke zorgverzekering als een manier om de zorg toegankelijker en eerlijker te maken.',
 'Vinden dat meerdere verzekeraars voor concurrentie zorgen en innovatie stimuleren.'
),
-- 16
('Referendum',
 'Er moet een bindend referendum komen waarbij burgers kunnen meebeslissen over belangrijke onderwerpen.',
 'Deze stelling gaat over het vergroten van de directe invloed van burgers op belangrijke besluiten. Met een bindend referendum kunnen mensen direct stemmen over belangrijke onderwerpen, in plaats van dat politici alle beslissingen nemen. Het idee is dat dit de democratie versterkt, maar het kan ook leiden tot vertraging in het besluitvormingsproces.',
 'Vinden dat burgers meer directe invloed moeten hebben op beleidskeuzes door een bindend referendum.',
 'Vinden dat vertegenwoordigde democratie beter werkt en dat referenda voor vertraging kunnen zorgen.'
),
-- 17
('Winstbelasting',
 'De winstbelasting voor grote bedrijven moet omhoog.',
 'Deze stelling gaat over het verhogen van de belasting op de winst van grote bedrijven. Het idee is dat door grotere bedrijven meer te laten bijdragen, er extra geld beschikbaar komt voor publieke voorzieningen en sociale zaken. Er wordt gekeken naar de vraag of een hogere belastingdruk de economische groei negatief beïnvloedt.',
 'Vinden dat grote bedrijven meer moeten bijdragen aan de samenleving, zodat er extra geld is voor publieke zaken.',
 'Vinden dat een hogere winstbelasting de concurrentiepositie van bedrijven kan schaden.'
),
-- 18
('Legalisering Drugs',
 'Alle drugs moeten worden gelegaliseerd en gereguleerd.',
 'Deze stelling gaat over het volledig legaliseren van alle drugs. Legalering betekent dat de productie, verkoop en consumptie van drugs niet langer illegaal is, maar gereguleerd wordt. Het doel is om de kwaliteit en veiligheid te verbeteren en criminaliteit te verminderen, maar er bestaat ook zorg dat dit tot meer misbruik kan leiden.',
 'Zien legalisering als een manier om de kwaliteit en veiligheid van drugs te bewaken en criminaliteit te verminderen.',
 'Vinden dat legalisering kan leiden tot meer maatschappelijke problemen en misbruik.'
),
-- 19
('Kilometerheffing',
 'Er moet een kilometerheffing komen voor autorijders.',
 'Deze stelling gaat over het invoeren van een heffing per gereden kilometer voor auto\'s. Het doel is om mensen aan te moedigen minder te rijden, waardoor het milieu minder belast wordt en er minder files ontstaan. Tegelijkertijd betekent dit extra kosten voor bestuurders, wat vooral mensen met een laag inkomen kan raken.',
 'Zien de kilometerheffing als een stimulans voor duurzamer vervoer en een manier om het milieu te beschermen.',
 'Vinden dat extra kosten voor autorijders vooral mensen met een laag inkomen benadelen.'
),
-- 20
('Kinderopvang',
 'Kinderopvang moet gratis worden voor alle ouders.',
 'Deze stelling gaat over het gratis maken van kinderopvang. Het idee is dat ouders hierdoor makkelijker werk en gezin kunnen combineren en dat kinderen gelijke kansen krijgen. Gratis kinderopvang betekent extra kosten voor de overheid, maar kan ook leiden tot een betere ontwikkeling van kinderen en meer participatie van ouders op de arbeidsmarkt.',
 'Vinden dat gratis kinderopvang kansen voor kinderen creëert en ouders ontzorgt.',
 'Vinden dat gratis kinderopvang te hoge kosten met zich meebrengt en dat een eigen bijdrage nodig is.'
),
-- 21
('Kernwapens',
 'Amerikaanse kernwapens moeten van Nederlands grondgebied worden verwijderd.',
 'Deze stelling gaat over de aanwezigheid van Amerikaanse kernwapens in Nederland. Sommige partijen vinden dat deze wapens niet op Nederlands grondgebied horen omdat zij een nucleaire dreiging vormen. Anderen vinden dat deze wapens een belangrijk afschrikmiddel zijn en bijdragen aan de veiligheid. Het debat gaat over nationale veiligheid, internationale afspraken en de afweging tussen onafhankelijkheid en veiligheid.',
 'Zien verwijdering als een stap richting een onafhankelijkere en veiligere defensie zonder nucleaire dreiging.',
 'Vinden dat de aanwezigheid van kernwapens een afschrikmiddel is en de veiligheid kan waarborgen.'
),
-- 22
('Monarchie',
 'Nederland moet een republiek worden in plaats van een monarchie.',
 'Deze stelling gaat over de vorm van het staatshoofd. In een monarchie wordt de positie van staatshoofd geërfd, terwijl in een republiek het staatshoofd gekozen wordt. Er wordt gediscussieerd over of een gekozen staatshoofd beter past bij een moderne democratie of dat de traditionele monarchie belangrijk is voor de nationale identiteit en continuïteit.',
 'Zien een republiek als moderner en democratischer, met een gekozen staatshoofd dat representatief is voor het volk.',
 'Vinden dat de monarchie een belangrijk historisch en symbolisch onderdeel is van de nationale identiteit.'
),
-- 23
('Pensioenstelsel',
 'Het nieuwe pensioenstelsel moet worden teruggedraaid.',
 'Deze stelling gaat over het terugdraaien van de recente hervormingen in het pensioenstelsel. Het idee is dat de oude manier van pensioen betalen meer zekerheid bood aan gepensioneerden. Tegelijkertijd kan het terugdraaien van veranderingen ook betekenen dat er minder ruimte komt voor aanpassing aan de veranderende arbeidsmarkt en demografische ontwikkelingen.',
 'Vinden dat het oude pensioenstelsel meer zekerheid biedt aan gepensioneerden.',
 'Zien dat vernieuwing nodig is om het stelsel toekomstbestendig te maken, gezien demografische veranderingen.'
),
-- 24
('Defensiesamenwerking',
 'Nederland moet streven naar een Europees leger.',
 'Deze stelling gaat over de samenwerking op defensiegebied binnen Europa. Een Europees leger betekent dat landen samen hun militaire middelen bundelen. Dit kan leiden tot efficiëntere samenwerking en kostenbesparing, maar roept ook vragen op over nationale soevereiniteit en controle over de eigen defensie. Het debat draait om de balans tussen samenwerking en onafhankelijkheid op militair vlak.',
 'Vinden dat samenwerking leidt tot een sterkere, gezamenlijke veiligheid in Europa.',
 'Vinden dat nationale defensie altijd voorop moet staan en een Europees leger de soevereiniteit kan verminderen.'
),
-- 25
('Belastingstelsel',
 'Er moet een vlaktaks komen: één belastingtarief voor alle inkomens.',
 'Deze stelling gaat over het invoeren van een vlaktaks, waarbij iedereen hetzelfde percentage belasting betaalt, ongeacht het inkomen. Dit systeem is eenvoudiger en overzichtelijker, maar het kan betekenen dat mensen met een laag inkomen relatief meer betalen dan bij een progressief tarief, waarbij de rijkere mensen meer kunnen bijdragen. Het debat gaat over de balans tussen eenvoud en sociale rechtvaardigheid.',
 'Zien een vlaktaks als een manier om de belastingheffing eerlijk en simpel te maken.',
 'Vinden dat een progressief systeem eerlijker is, omdat de rijksten meer kunnen bijdragen.'
);

-- --------------------------------------
-- 5. Positions invoeren
--    We koppelen elke vraag aan elke partij met hun stance + uitleg.
--    Let op: we hebben nu 25 questions en 14 parties.
--    Om die te inserten, moeten we eerst de ID van de party en de question bepalen.
--
--    We doen dit hier in bulk. Voor de leesbaarheid staan de question_id en party_id
--    in commentaar. MySQL genereert question_id in volgorde (1..25),
--    parties (1..14) in volgorde die we hebben ingevoerd.
-- --------------------------------------

-- Helper: we hebben 14 partijen in deze volgorde geïnsert:
--  1  PVV
--  2  VVD
--  3  NSC
--  4  BBB
--  5  GL-PvdA
--  6  D66
--  7  SP
--  8  PvdD
--  9  CDA
-- 10  JA21
-- 11  SGP
-- 12  FvD
-- 13  DENK
-- 14  Volt

-- Voor question_id 1 (Asielbeleid):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(1, 1, 'eens',    'Deze partij steunt een strenger asielbeleid met een volledige asielstop. Zij vinden dat Nederland zo de controle over migratie behoudt.'),    -- PVV
(1, 2, 'eens',    'VVD pleit voor een strengere selectie en beperking van asielaanvragen, maar met internationale samenwerking.'),                           -- VVD
(1, 3, 'eens',    'NSC benadrukt dat een doordacht asielbeleid zowel veiligheid als humanitaire zorg moet waarborgen.'),                                 -- NSC
(1, 4, 'eens',    'BBB ondersteunt een streng asielbeleid en wil de instroom beperken door regionale opvang te stimuleren.'),                           -- BBB
(1, 5, 'oneens',  'GL-PvdA vindt dat humanitaire principes centraal moeten staan en verzet zich tegen een asielstop.'),                                -- GL-PvdA
(1, 6, 'oneens',  'D66 wil een humaan maar gestructureerd asielbeleid met veilige en legale routes.'),                                                -- D66
(1, 7, 'neutraal','SP vindt dat het verbeteren van opvang en integratie even belangrijk is als het beperken van instroom.'),                           -- SP
(1, 8, 'oneens',  'PvdD wil een asielbeleid dat mensenrechten respecteert en aandacht heeft voor de ecologische context.'),                            -- PvdD
(1, 9, 'neutraal','CDA pleit voor een onderscheidend beleid met duidelijke scheiding tussen tijdelijke en permanente bescherming.'),                   -- CDA
(1, 10, 'eens',   'JA21 ondersteunt een restrictief asielbeleid met strikte toelatingscriteria.'),                                                    -- JA21
(1, 11, 'eens',   'SGP wil een zeer restrictief asielbeleid, waarbij nationale identiteit en veiligheid vooropstaan.'),                                -- SGP
(1, 12, 'eens',   'FvD pleit voor het beëindigen van het internationale asielkader en wil asielaanvragen sterk beperken.'),                            -- FvD
(1, 13, 'oneens', 'DENK kiest voor een humaan asielbeleid dat ook aandacht heeft voor solidariteit en internationale samenwerking.'),                  -- DENK
(1, 14, 'oneens', 'Volt staat voor een gemeenschappelijk Europees asielbeleid dat solidariteit tussen lidstaten bevordert.');                          -- Volt

-- question_id 2 (Klimaatmaatregelen):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(2, 1, 'oneens',   'PVV verzet zich tegen ambitieuze klimaatmaatregelen als deze ten koste gaan van economische groei.'),
(2, 2, 'oneens',   'VVD ondersteunt klimaatmaatregelen, maar vindt dat de economie niet op de achtergrond mag raken.'),
(2, 3, 'neutraal', 'NSC vindt zowel klimaat als economie belangrijk en pleit voor een evenwichtige aanpak.'),
(2, 4, 'oneens',   'BBB is sceptisch over ingrijpende klimaatmaatregelen, zeker als deze de agrarische sector schaden.'),
(2, 5, 'eens',     'GL-PvdA is voor ambitieuze klimaatmaatregelen, ook al moet daarvoor op korte termijn wat opgeofferd worden.'),
(2, 6, 'eens',     'D66 wil dat Nederland een leidende rol speelt in de klimaattransitie, met oog voor veiligheid en innovatie.'),
(2, 7, 'neutraal', 'SP vindt dat klimaatmaatregelen eerlijk moeten worden verdeeld, zodat zowel ecologische als economische belangen worden meegenomen.'),
(2, 8, 'eens',     'PvdD staat voor radicaal klimaatbeleid, ongeacht economische kortetermijnnadelen.'),
(2, 9, 'neutraal', 'CDA pleit voor een combinatie van klimaatmaatregelen en behoud van economische stabiliteit.'),
(2, 10, 'oneens',  'JA21 wil niet dat klimaatmaatregelen de economische groei te veel hinderen.'),
(2, 11, 'neutraal','SGP vindt dat maatregelen verantwoord moeten zijn en de economie niet te zwaar belasten.'),
(2, 12, 'oneens',  'FvD betwist de urgentie van de klimaatcrisis en wil geen maatregelen die de economie schaden.'),
(2, 13, 'neutraal','DENK wil een genuanceerde aanpak waarbij zowel klimaat als economie worden meegenomen.'),
(2, 14, 'eens',    'Volt pleit voor ambitieuze maatregelen en gelooft dat de lange termijn voordelen opwegen tegen de korte termijn kosten.');

-- question_id 3 (Eigen Risico Zorg):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(3, 1, 'eens',     'PVV wil het eigen risico volledig afschaffen zodat iedereen direct toegang heeft tot zorg.'),
(3, 2, 'oneens',   'VVD vindt dat het eigen risico helpt om zorgkosten beheersbaar te houden en stimuleert verantwoordelijk gebruik.'),
(3, 3, 'neutraal', 'NSC overweegt aanpassingen in plaats van volledige afschaffing om zo de betaalbaarheid te garanderen.'),
(3, 4, 'eens',     'BBB wil het eigen risico verlagen om de zorgtoegankelijkheid te vergroten, maar wel met een stapsgewijze aanpak.'),
(3, 5, 'eens',     'GL-PvdA pleit voor afschaffing om gelijke toegang tot zorg te realiseren.'),
(3, 6, 'oneens',   'D66 stelt voor het eigen risico te bevriezen en per behandeling een limiet in te stellen.'),
(3, 7, 'eens',     'SP vindt dat het afschaffen van het eigen risico zorgt voor een eerlijker zorgsysteem.'),
(3, 8, 'eens',     'PvdD wil dat zorg toegankelijk is zonder financiële drempels.'),
(3, 9, 'neutraal', 'CDA pleit voor een gerichte verlaging van het eigen risico, gekoppeld aan verantwoordelijkheid.'),
(3, 10, 'oneens',  'JA21 vindt een zekere mate van eigen bijdrage noodzakelijk voor efficiëntie.'),
(3, 11, 'neutraal','SGP ziet het eigen risico als een middel om onnodig gebruik van zorg te beperken, maar met ruimte voor verlaging bij kwetsbare groepen.'),
(3, 12, 'eens',    'FvD ondersteunt afschaffing omdat zij geloven in een toegankelijke zorg voor iedereen.'),
(3, 13, 'eens',    'DENK wil het eigen risico aanzienlijk verlagen om zorg voor iedereen bereikbaar te maken.'),
(3, 14, 'neutraal','Volt staat open voor verlaging van het eigen risico, mits dit financieel haalbaar is.');

-- question_id 4 (Kernenergie):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(4, 1, 'eens',     'PVV steunt investering in kerncentrales als een manier om de energievoorziening veilig te stellen.'),
(4, 2, 'eens',     'VVD ziet kernenergie als aanvulling op duurzame bronnen, mits veiligheid en kosten in balans zijn.'),
(4, 3, 'eens',     'NSC staat open voor kernenergie als het bijdraagt aan een stabiele energiemix en veiligheid gegarandeerd is.'),
(4, 4, 'eens',     'BBB vindt dat kernenergie een betrouwbaar onderdeel kan zijn van de energietransitie.'),
(4, 5, 'oneens',   'GL-PvdA verwerpt kernenergie vanwege de risico\'s en lange doorlooptijden.'),
(4, 6, 'neutraal', 'D66 bekijkt kernenergie kritisch en vindt dat innovatie en veiligheid centraal moeten staan.'),
(4, 7, 'oneens',   'SP wil geen investeringen in kerncentrales en besteedt liever publieke middelen aan duurzame energie.'),
(4, 8, 'oneens',   'PvdD vindt kernenergie verouderd en wil meer inzetten op hernieuwbare energiebronnen.'),
(4, 9, 'eens',     'CDA ziet kernenergie als een onderdeel van een brede energiemix, mits goed gereguleerd.'),
(4, 10, 'eens',    'JA21 steunt kernenergie als een manier om energiezekerheid en emissiereductie te realiseren.'),
(4, 11, 'eens',    'SGP ziet kernenergie als een middel om de afhankelijkheid van fossiele brandstoffen te verminderen.'),
(4, 12, 'eens',    'FvD wil investeren in kernenergie als alternatief voor fossiele brandstoffen.'),
(4, 13, 'neutraal','DENK staat open voor kernenergie als het veilig en verantwoord wordt ingezet.'),
(4, 14, 'eens',    'Volt geeft de voorkeur aan hernieuwbare energie, maar staat open voor kernenergie bij strenge veiligheidseisen.');

-- question_id 5 (Woningmarkt):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(5, 1, 'eens',     'PVV steunt een overheidsprogramma om de woningnood aan te pakken.'),
(5, 2, 'oneens',   'VVD vindt dat de overheid niet te veel moet ingrijpen en de markt beter functioneert.'),
(5, 3, 'neutraal', 'NSC pleit voor een mix van publieke en private initiatieven.'),
(5, 4, 'neutraal', 'BBB ziet kansen in een overheidsprogramma, zeker op het platteland.'),
(5, 5, 'eens',     'GL-PvdA wil dat de overheid de sociale huurmarkt versterkt.'),
(5, 6, 'neutraal', 'D66 vindt dat de overheid samenwerkt met private partijen voor duurzaam bouwen.'),
(5, 7, 'eens',     'SP steunt overheidsinitiatieven om huisvesting voor iedereen toegankelijk te maken.'),
(5, 8, 'eens',     'PvdD wil een structurele overheidsaanpak voor duurzame en betaalbare woningen.'),
(5, 9, 'neutraal', 'CDA pleit voor een gerichte overheidsrol bij de bouw voor kwetsbare groepen.'),
(5, 10, 'oneens',  'JA21 verkiest marktgedreven oplossingen met subsidieregelingen.'),
(5, 11, 'oneens',  'SGP vindt dat woningcorporaties voorrang moeten krijgen voor sociale stabiliteit.'),
(5, 12, 'neutraal','FvD wil dat de regels versoepeld worden zodat de markt soepel werkt.'),
(5, 13, 'eens',    'DENK steunt een actieve rol van de overheid om ongelijkheid in de woningmarkt tegen te gaan.'),
(5, 14, 'eens',    'Volt wil dat overheid en markt samenwerken voor innovatieve oplossingen.');

-- question_id 6 (Minimumloon):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(6, 1, 'neutraal','PVV vindt dat economische realiteiten meegewogen moeten worden bij een verhoging.'),
(6, 2, 'oneens',  'VVD is tegen een verhoging omdat dit banen kan schaden.'),
(6, 3, 'oneens',  'NSC pleit voor een stapsgewijze benadering, afhankelijk van de economie.'),
(6, 4, 'neutraal','BBB vindt dat het minimumloon in balans moet zijn met economische realiteit.'),
(6, 5, 'eens',    'GL-PvdA steunt een verhoging voor een eerlijker loon en sociale rechtvaardigheid.'),
(6, 6, 'neutraal','D66 is voor een verhoging als de economische omstandigheden dat toelaten.'),
(6, 7, 'eens',    'SP vindt dat een hoger minimumloon de koopkracht en gelijkheid versterkt.'),
(6, 8, 'eens',    'PvdD steunt een verhoging om een eerlijk inkomen voor iedereen te garanderen.'),
(6, 9, 'neutraal','CDA vindt dat een verhoging gekoppeld moet zijn aan productiviteitswinsten.'),
(6, 10, 'oneens', 'JA21 is tegen een verhoging uit vrees voor banenverlies.'),
(6, 11, 'oneens', 'SGP vindt dat economische draagkracht beschermd moet worden.'),
(6, 12, 'oneens', 'FvD is tegen een verhoging vanwege extra kosten voor werkgevers.'),
(6, 13, 'eens',   'DENK pleit voor een verhoging om inkomensongelijkheid tegen te gaan.'),
(6, 14, 'neutraal','Volt steunt een verhoging als dit gepaard gaat met structurele investeringen.');

-- question_id 7 (Europese Unie):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(7, 1, 'eens',   'PVV wil de nationale soevereiniteit herstellen door uit de EU te stappen.'),
(7, 2, 'oneens', 'VVD vindt dat samenwerking binnen de EU essentieel is voor veiligheid en economie.'),
(7, 3, 'oneens', 'NSC ziet zowel kansen als risico\'s in het EU-lidmaatschap.'),
(7, 4, 'oneens', 'BBB pleit voor pragmatische samenwerking binnen de EU.'),
(7, 5, 'oneens', 'GL-PvdA vindt dat de EU cruciaal is voor solidariteit en mensenrechten.'),
(7, 6, 'oneens', 'D66 wil de Europese samenwerking verdiepen in plaats van stoppen.'),
(7, 7, 'oneens', 'SP vindt dat internationale solidariteit belangrijk is om uitdagingen aan te pakken.'),
(7, 8, 'oneens', 'PvdD ziet de EU als essentieel voor milieubescherming en duurzaamheid.'),
(7, 9, 'oneens', 'CDA vindt dat Nederland in de EU moet blijven met meer aandacht voor nationale belangen.'),
(7, 10, 'neutraal','JA21 pleit voor hervorming van de EU in plaats van vertrek.'),
(7, 11, 'oneens','SGP vindt dat de samenwerking binnen Europa de vrede en veiligheid bevordert.'),
(7, 12, 'eens',  'FvD wil uit de EU stappen om bureaucratische beperkingen te doorbreken.'),
(7, 13, 'oneens','DENK vindt samenwerking belangrijk om internationale uitdagingen samen aan te pakken.'),
(7, 14, 'oneens','Volt ziet de EU als een platform voor gezamenlijke vooruitgang.');

-- question_id 8 (Defensie-uitgaven):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(8, 1, 'neutraal','PVV vindt dat veiligheid ook op andere manieren bereikt kan worden dan door een forse budgetverhoging.'),
(8, 2, 'eens',   'VVD steunt een verhoging om de internationale positie en veiligheid te waarborgen.'),
(8, 3, 'eens',   'NSC wil dat de uitgaven efficiënt worden ingezet als onderdeel van een bredere veiligheidsstrategie.'),
(8, 4, 'neutraal','BBB vindt dat de uitgaven in lijn moeten zijn met de daadwerkelijke dreigingen.'),
(8, 5, 'neutraal','GL-PvdA ziet geen reden voor een forse verhoging als dit sociale uitgaven schaadt.'),
(8, 6, 'eens',   'D66 wil investeren in defensie om beter voorbereid te zijn op crises.'),
(8, 7, 'oneens', 'SP vindt dat geld beter besteed kan worden aan sociale programma\'s.'),
(8, 8, 'oneens', 'PvdD pleit voor transparantie en efficiëntie bij de besteding van defensiegeld.'),
(8, 9, 'eens',   'CDA steunt een verhoging, mits dit gepaard gaat met moderne investeringen.'),
(8, 10, 'eens',  'JA21 vindt dat Nederland zijn verantwoordelijkheid in internationale veiligheid moet waarmaken.'),
(8, 11, 'eens',  'SGP wil dat de bescherming van burgers vooropstaat bij de verhoging van de uitgaven.'),
(8, 12, 'oneens','FvD vindt dat defensie efficiënt en doelgericht moet opereren, zonder extra verhoging.'),
(8, 13, 'oneens','DENK pleit voor een kritische benadering waarbij maatschappelijke behoeften meewegen.'),
(8, 14, 'eens',  'Volt steunt een verhoging als dit leidt tot betere Europese samenwerking in veiligheid.');

-- question_id 9 (Stikstofbeleid):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(9, 1, 'eens',     'PVV wil versoepeling om boeren meer ruimte te geven, zodat hun economische belangen beschermd worden.'),
(9, 2, 'eens',     'VVD steunt versoepeling, mits natuur ook beschermd blijft.'),
(9, 3, 'eens',     'NSC vindt dat er een evenwicht moet komen tussen de belangen van boeren en de natuur.'),
(9, 4, 'eens',     'BBB vindt dat de huidige regels te streng zijn en boeren kansen ontnemen.'),
(9, 5, 'oneens',   'GL-PvdA vindt dat natuur en klimaatbescherming niet ondergeschikt mogen worden gemaakt.'),
(9, 6, 'oneens',   'D66 wil inzetten op technologische innovaties in de landbouw in plaats van versoepeling.'),
(9, 7, 'neutraal', 'SP pleit voor een beleid dat zowel de agrarische sector als de natuur ondersteunt.'),
(9, 8, 'oneens',   'PvdD vindt dat de focus moet liggen op een duurzame herinrichting van de landbouw.'),
(9, 9, 'neutraal', 'CDA vindt dat boeren ondersteund moeten worden, maar de natuur ook beschermd moet worden.'),
(9, 10, 'eens',    'JA21 steunt versoepeling zodat boeren economisch kunnen floreren.'),
(9, 11, 'eens',    'SGP vindt dat versoepeling bijdraagt aan de leefbaarheid in landelijke gebieden.'),
(9, 12, 'eens',    'FvD wil versoepeling om boeren te beschermen tegen te strenge milieuregels.'),
(9, 13, 'neutraal','DENK pleit voor een inclusieve dialoog over duurzame landbouw.'),
(9, 14, 'oneens',  'Volt wil juist een integrale aanpak met natuurherstel en innovatie in de landbouw.');

-- question_id 10 (Studiefinanciering):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(10, 1, 'eens',    'PVV wil meer studiefinanciering om de toegankelijkheid van hoger onderwijs te verbeteren.'),
(10, 2, 'oneens',  'VVD is tegen een verhoging omdat dit de studiekosten kan verhogen.'),
(10, 3, 'neutraal','NSC wil dat de financiering in balans is met maatschappelijke realiteiten.'),
(10, 4, 'eens',    'BBB vindt dat een hogere basisbeurs de financiële druk op studenten verlaagt.'),
(10, 5, 'eens',    'GL-PvdA ziet een verhoging als een investering in de toekomst van jongeren.'),
(10, 6, 'eens',    'D66 wil modernisering van het onderwijssysteem samen met een verhoging van de financiering.'),
(10, 7, 'eens',    'SP steunt een verhoging om de sociale gelijkheid in het onderwijs te bevorderen.'),
(10, 8, 'eens',    'PvdD vindt dat een hogere basisbeurs de focus op studie bevordert.'),
(10, 9, 'neutraal','CDA pleit voor aanpassing aan de economische realiteit, met behoud van efficiëntie.'),
(10, 10, 'neutraal','JA21 wil voorzichtig zijn met verhoging vanwege mogelijke extra kosten.'),
(10, 11, 'neutraal','SGP steunt een verhoging als dit leidt tot structurele verbeteringen in het onderwijs.'),
(10, 12, 'eens',   'FvD wil een verhoging als dit jongeren meer kansen biedt, maar met strenge criteria.'),
(10, 13, 'eens',   'DENK ziet een hogere basisbeurs als een manier om ongelijkheden in het onderwijs te verkleinen.'),
(10, 14, 'eens',   'Volt vindt dat meer investeringen in onderwijs de toegankelijkheid vergroten.');

-- question_id 11 (Belastingen):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(11, 1, 'eens',   'PVV wil dat grote bedrijven extra moeten bijdragen voor een sterkere overheid.'),
(11, 2, 'oneens', 'VVD vindt dat te hoge belastingen innovatie en groei belemmeren.'),
(11, 3, 'neutraal','NSC pleit voor een weloverwogen belastingbeleid dat ook de economie beschermt.'),
(11, 4, 'neutraal','BBB vindt dat er een balans moet zijn tussen bijdragen en concurrentiekracht.'),
(11, 5, 'eens',   'GL-PvdA ziet hogere belastingen als een manier om sociale ongelijkheid te verkleinen.'),
(11, 6, 'eens',   'D66 wil dat extra opbrengsten geïnvesteerd worden in innovatie en duurzaamheid.'),
(11, 7, 'eens',   'SP vindt dat de winsten van multinationals eerlijker verdeeld moeten worden.'),
(11, 8, 'eens',   'PvdD vindt dat grote bedrijven hun maatschappelijke verantwoordelijkheid moeten nemen.'),
(11, 9, 'neutraal','CDA pleit voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.'),
(11, 10, 'oneens','JA21 vreest dat hogere belastingen innovatie en werkgelegenheid in de weg staan.'),
(11, 11, 'neutraal','SGP wil dat stabiliteit in het belastingstelsel behouden blijft.'),
(11, 12, 'oneens','FvD vindt dat een lager tarief juist investeringen stimuleert.'),
(11, 13, 'eens',  'DENK wil dat multinationals hun eerlijke deel bijdragen aan de samenleving.'),
(11, 14, 'eens',  'Volt steunt een verhoging als dit bijdraagt aan duurzame investeringen.');

-- question_id 12 (AOW-leeftijd):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(12, 1, 'eens',   'PVV wil een verlaging zodat mensen eerder met pensioen kunnen.'),
(12, 2, 'oneens', 'VVD vindt dat de AOW-leeftijd in lijn moet blijven met de levensverwachting.'),
(12, 3, 'oneens', 'NSC wil een afweging maken tussen gezondheid en economische haalbaarheid.'),
(12, 4, 'neutraal','BBB pleit voor een pensioenleeftijd die past bij de economische realiteit.'),
(12, 5, 'oneens', 'GL-PvdA vindt dat flexibiliteit belangrijk is, maar niet ten koste van de zekerheid.'),
(12, 6, 'oneens', 'D66 wil dat de AOW-leeftijd geleidelijk stijgt gezien de demografische ontwikkelingen.'),
(12, 7, 'eens',   'SP steunt een verlaging zodat ouderen eerder kunnen genieten van hun pensioen.'),
(12, 8, 'neutraal','PvdD vindt dat ouderen niet onnodig lang moeten doorwerken.'),
(12, 9, 'oneens', 'CDA wil een pensioenstelsel dat maatwerk biedt in plaats van een vaste leeftijd.'),
(12, 10, 'neutraal','JA21 vindt dat de economische haalbaarheid voorop moet staan.'),
(12, 11, 'oneens','SGP pleit voor een stabiel en duurzaam pensioenstelsel.'),
(12, 12, 'eens',  'FvD steunt een verlaging zodat de levenskwaliteit van ouderen verbetert.'),
(12, 13, 'eens',  'DENK wil dat een lagere pensioenleeftijd de druk op werkenden vermindert.'),
(12, 14, 'oneens','Volt vindt dat de AOW-leeftijd realistisch moet zijn, rekening houdend met demografie.');

-- question_id 13 (Sociale Huurwoningen):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(13, 1, 'eens',   'PVV steunt voorrang voor woningcorporaties om de woningnood aan te pakken.'),
(13, 2, 'oneens', 'VVD wil een marktgerichte aanpak zonder dwingende voorrang.'),
(13, 3, 'neutraal','NSC ziet zowel publieke als private initiatieven als noodzakelijk voor de woningmarkt.'),
(13, 4, 'neutraal','BBB vindt dat voorrang de sociale cohesie kan bevorderen.'),
(13, 5, 'eens',   'GL-PvdA wil dat woningcorporaties helpen bij het oplossen van de woningnood.'),
(13, 6, 'neutraal','D66 pleit voor samenwerking tussen overheid en private sector.'),
(13, 7, 'eens',   'SP wil dat de overheid een actieve rol speelt in het waarborgen van betaalbare woningen.'),
(13, 8, 'eens',   'PvdD steunt een sterke overheidsaanpak voor duurzame en betaalbare woningen.'),
(13, 9, 'neutraal','CDA pleit voor een gerichte overheidsrol bij de bouw voor kwetsbare groepen.'),
(13, 10, 'oneens','JA21 verkiest marktgedreven oplossingen met subsidieregelingen.'),
(13, 11, 'neutraal','SGP vindt dat woningcorporaties de sociale stabiliteit kunnen waarborgen.'),
(13, 12, 'oneens','FvD wil dat de regels versoepeld worden zodat de markt soepel werkt.'),
(13, 13, 'eens',  'DENK steunt een actieve rol van de overheid om ongelijkheid in de woningmarkt tegen te gaan.'),
(13, 14, 'eens',  'Volt wil dat overheid en markt samenwerken voor innovatieve oplossingen.');

-- question_id 14 (Ontwikkelingshulp):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(14, 1, 'eens',   'PVV wil dat de middelen beter in Nederland gebruikt worden.'),
(14, 2, 'eens',   'VVD steunt bezuinigingen om de eigen financiën op orde te krijgen.'),
(14, 3, 'neutraal','NSC wil dat ontwikkelingshulp doelbewust en strategisch wordt ingezet.'),
(14, 4, 'eens',   'BBB vindt dat de focus eerst op de eigen economie moet liggen.'),
(14, 5, 'oneens', 'GL-PvdA vindt ontwikkelingshulp belangrijk voor internationale solidariteit.'),
(14, 6, 'oneens', 'D66 wil behoud van hulp als onderdeel van internationale samenwerking.'),
(14, 7, 'oneens', 'SP vindt dat Nederland meer verantwoordelijkheid heeft richting kwetsbare landen.'),
(14, 8, 'oneens', 'PvdD ziet ontwikkelingshulp als een morele verplichting.'),
(14, 9, 'oneens', 'CDA pleit voor doelgerichte hulp in samenwerking met internationale partners.'),
(14, 10, 'eens',  'JA21 vindt dat hulp kritisch geëvalueerd moet worden op effectiviteit.'),
(14, 11, 'neutraal','SGP ziet hulp als essentieel voor internationale solidariteit, maar met voorwaarden.'),
(14, 12, 'eens',  'FvD wil dat het budget beter in eigen land wordt gebruikt.'),
(14, 13, 'oneens','DENK pleit voor een humaan ontwikkelingsbeleid dat ongelijkheid tegengaat.'),
(14, 14, 'oneens','Volt wil ontwikkelingshulp juist versterken als onderdeel van een duurzame agenda.');

-- question_id 15 (Zorgverzekering):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(15, 1, 'neutraal','PVV ziet geen meerwaarde in één publieke zorgverzekering en waardeert marktwerking in de zorg.'),
(15, 2, 'oneens',  'VVD vreest dat een publieke monopoliestructuur innovatie en keuzevrijheid beperkt.'),
(15, 3, 'oneens',  'NSC vindt dat zowel publieke als private systemen hun voordelen hebben.'),
(15, 4, 'neutraal','BBB ziet in één verzekering mogelijkheden voor lagere administratieve lasten, maar waarschuwt voor centralisatie.'),
(15, 5, 'eens',    'GL-PvdA wil dat solidariteit en gelijke toegang tot zorg centraal staan.'),
(15, 6, 'oneens',  'D66 verzet zich tegen een monopolie in de zorgverzekering.'),
(15, 7, 'eens',    'SP ziet één publieke verzekering als een middel om de zorgkosten te verlagen.'),
(15, 8, 'eens',    'PvdD wil een menselijker en efficiënter zorgsysteem via één publieke verzekering.'),
(15, 9, 'oneens',  'CDA vindt dat een mix van publieke en private aanbieders de beste balans biedt.'),
(15, 10, 'oneens', 'JA21 vreest dat één verzekering leidt tot minder keuzevrijheid voor burgers.'),
(15, 11, 'oneens', 'SGP hecht waarde aan een pluralistisch systeem met concurrentie.'),
(15, 12, 'neutraal','FvD ziet de huidige markt als bewezen werkbaar.'),
(15, 13, 'eens',   'DENK steunt één publieke verzekering als middel om structurele ongelijkheden tegen te gaan.'),
(15, 14, 'neutraal','Volt pleit voor een zorgsysteem dat toegankelijk en transparant blijft, ongeacht de vorm.');

-- question_id 16 (Referendum):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(16, 1, 'eens',   'PVV wil dat burgers direct meebeslissen om de democratische legitimiteit te versterken.'),
(16, 2, 'oneens', 'VVD vreest dat referenda de besluitvorming vertragen en polariserend werken.'),
(16, 3, 'neutraal','NSC vindt dat referenda een aanvulling kunnen zijn, mits goed georganiseerd.'),
(16, 4, 'eens',   'BBB ziet in referenda een manier om het vertrouwen in de politiek te vergroten.'),
(16, 5, 'neutraal','GL-PvdA pleit voor voorwaarden en goede voorlichting bij referenda.'),
(16, 6, 'eens',   'D66 vindt dat referenda vooral bij nationale belangrijke onderwerpen moeten worden ingezet.'),
(16, 7, 'eens',   'SP vindt dat meer directe inspraak leidt tot meer democratische betrokkenheid.'),
(16, 8, 'eens',   'PvdD wil dat burgers beter geïnformeerd worden over de consequenties van hun keuzes.'),
(16, 9, 'oneens', 'CDA vindt dat vertegenwoordigde democratie stabieler is dan te vaak referenda.'),
(16, 10, 'eens',  'JA21 wil dat burgers meer directe invloed krijgen, mits dit goed gefaciliteerd wordt.'),
(16, 11, 'oneens','SGP vreest dat referenda leiden tot simplistische besluitvorming.'),
(16, 12, 'eens',  'FvD ziet referenda als een manier om de macht van politieke elites te beperken.'),
(16, 13, 'eens',  'DENK vindt dat referenda de stem van minderheden kunnen versterken.'),
(16, 14, 'eens',  'Volt pleit voor een bindend referendum als aanvulling op de representatieve democratie.');

-- question_id 17 (Winstbelasting):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(17, 1, 'eens',   'PVV vindt dat grote bedrijven extra moeten bijdragen voor een sterkere overheid.'),
(17, 2, 'oneens', 'VVD is tegen een verhoging omdat dit de concurrentie kan schaden.'),
(17, 3, 'neutraal','NSC pleit voor een weloverwogen beleid dat ook de economie beschermt.'),
(17, 4, 'neutraal','BBB vindt dat er een balans moet zijn tussen bijdragen en concurrentiekracht.'),
(17, 5, 'eens',   'GL-PvdA ziet hogere belastingen als een manier om sociale ongelijkheid te verkleinen.'),
(17, 6, 'eens',   'D66 wil dat extra opbrengsten geïnvesteerd worden in innovatie en duurzaamheid.'),
(17, 7, 'eens',   'SP vindt dat de winsten van multinationals eerlijker verdeeld moeten worden.'),
(17, 8, 'eens',   'PvdD vindt dat grote bedrijven hun maatschappelijke verantwoordelijkheid moeten nemen.'),
(17, 9, 'neutraal','CDA pleit voor gerichte maatregelen die zowel ondernemers als burgers ten goede komen.'),
(17, 10, 'oneens','JA21 vreest dat hogere belastingen innovatie en werkgelegenheid in de weg staan.'),
(17, 11, 'neutraal','SGP wil dat stabiliteit in het belastingstelsel behouden blijft.'),
(17, 12, 'oneens','FvD vindt dat een lager tarief juist investeringen stimuleert.'),
(17, 13, 'eens',  'DENK wil dat multinationals hun eerlijke deel bijdragen aan de samenleving.'),
(17, 14, 'eens',  'Volt steunt een verhoging als dit bijdraagt aan duurzame investeringen.');

-- question_id 18 (Legalisering Drugs):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(18, 1, 'oneens',  'PVV is fel tegen legalisering omdat zij vrezen voor meer maatschappelijke problemen.'),
(18, 2, 'oneens',  'VVD vreest dat legalisering leidt tot meer drugscriminaliteit.'),
(18, 3, 'oneens',  'NSC ziet zowel risico\'s als kansen, afhankelijk van de aanpak.'),
(18, 4, 'oneens',  'BBB vindt dat het huidige verbod noodzakelijk is voor de volksgezondheid.'),
(18, 5, 'neutraal','GL-PvdA kijkt neutraal en wil eerst de gevolgen goed onderzoeken.'),
(18, 6, 'eens',    'D66 is voor legalisering zodat er betere controle komt op kwaliteit en veiligheid.'),
(18, 7, 'oneens',  'SP vindt dat de aanpak van drugs vooral gericht moet zijn op preventie en hulp.'),
(18, 8, 'neutraal','PvdD ziet legalisering als een manier om schade te beperken, mits goed geregeld.'),
(18, 9, 'oneens',  'CDA is tegen legalisering uit vrees voor negatieve volksgezondheidsgevolgen.'),
(18, 10, 'oneens', 'JA21 is fel tegen legalisering om jongeren te beschermen.'),
(18, 11, 'oneens', 'SGP verzet zich tegen legalisering en wil inzetten op preventie en handhaving.'),
(18, 12, 'neutraal','FvD wil eerst meer onderzoek voordat een besluit wordt genomen.'),
(18, 13, 'oneens', 'DENK is tegen legalisering omdat zij vrezen voor extra maatschappelijke kwetsbaarheden.'),
(18, 14, 'neutraal','Volt steunt gereguleerde legalisering als dit leidt tot betere controle en minder criminaliteit.');

-- question_id 19 (Kilometerheffing):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(19, 1, 'oneens',  'PVV is fel tegen de kilometerheffing omdat zij vrezen dat dit burgers oneerlijk treft.'),
(19, 2, 'oneens',  'VVD vindt dat de heffing de mobiliteit en economie kan schaden.'),
(19, 3, 'neutraal','NSC wil dat er eerst een analyse komt van de sociale en economische impact.'),
(19, 4, 'oneens',  'BBB is tegen de heffing omdat dit vooral een last voor de burger betekent.'),
(19, 5, 'eens',    'GL-PvdA steunt de heffing als middel om duurzame mobiliteit te bevorderen.'),
(19, 6, 'eens',    'D66 ziet de heffing als een stap naar een schoner vervoerssysteem.'),
(19, 7, 'oneens',  'SP vindt dat de heffing vooral mensen met een laag inkomen treft.'),
(19, 8, 'eens',    'PvdD steunt de invoering als het helpt de ecologische voetafdruk te verkleinen.'),
(19, 9, 'neutraal','CDA vindt dat de heffing alleen werkt als er duidelijke voordelen voor het milieu zijn.'),
(19, 10, 'oneens', 'JA21 is tegen de heffing omdat deze de mobiliteit belemmert.'),
(19, 11, 'oneens', 'SGP vreest dat de heffing te veel extra kosten voor burgers veroorzaakt.'),
(19, 12, 'oneens', 'FvD vindt dat een extra belasting op mobiliteit de economie kan schaden.'),
(19, 13, 'neutraal','DENK pleit voor een debat over compensatie voor kwetsbare groepen als een heffing wordt ingevoerd.'),
(19, 14, 'eens',   'Volt steunt de heffing als dit gepaard gaat met eerlijke compensatiemechanismen.');

-- question_id 20 (Kinderopvang):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(20, 1, 'neutraal','PVV vindt dat kinderopvang niet volledig gratis hoeft te zijn zodat er ook sprake blijft van eigen verantwoordelijkheid.'),
(20, 2, 'oneens',  'VVD is tegen gratis kinderopvang uit vrees voor een te grote overheidsbemoeienis en hogere belastingen.'),
(20, 3, 'neutraal','NSC zoekt naar een balans tussen toegankelijkheid en economische haalbaarheid.'),
(20, 4, 'neutraal','BBB vindt dat regionale initiatieven kunnen helpen om de kosten laag te houden.'),
(20, 5, 'eens',    'GL-PvdA steunt gratis kinderopvang om gelijke kansen voor alle kinderen te waarborgen.'),
(20, 6, 'eens',    'D66 wil gratis kinderopvang zodat werk en gezin beter gecombineerd kunnen worden.'),
(20, 7, 'eens',    'SP ziet gratis kinderopvang als een investering in de toekomst van de samenleving.'),
(20, 8, 'eens',    'PvdD wil dat gratis kinderopvang ouders ontzorgt en kansen voor kinderen vergroot.'),
(20, 9, 'neutraal','CDA pleit voor een mix van publiek en privaat zodat kinderopvang toegankelijk blijft.'),
(20, 10, 'oneens', 'JA21 vreest dat gratis kinderopvang leidt tot te hoge overheidskosten.'),
(20, 11, 'oneens', 'SGP vindt dat ouders een deel van de kosten zelf moeten dragen.'),
(20, 12, 'oneens', 'FvD is tegen gratis kinderopvang uit vrees voor overmatige overheidsinmenging.'),
(20, 13, 'eens',   'DENK steunt gratis kinderopvang om sociale barrières te slechten.'),
(20, 14, 'eens',   'Volt vindt dat gratis kinderopvang een investering in de toekomst is, mits de kwaliteit hoog blijft.');

-- question_id 21 (Kernwapens):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(21, 1, 'neutraal','PVV vindt dat kernwapens op Nederlands grondgebied geen prioriteit hebben.'),
(21, 2, 'oneens', 'VVD vindt dat kernwapens veiligheid en afschrikking bieden via internationale afspraken.'),
(21, 3, 'oneens', 'NSC pleit voor samenwerking met bondgenoten voor strategische afschrikking.'),
(21, 4, 'neutraal','BBB ziet zowel voor- als nadelen in de aanwezigheid van kernwapens.'),
(21, 5, 'eens',   'GL-PvdA wil verwijdering en een pacifistische defensie.'),
(21, 6, 'neutraal','D66 neigt naar verwijdering als dit leidt tot internationale ontwapening.'),
(21, 7, 'eens',   'SP steunt verwijdering als stap naar een vreedzamere wereld.'),
(21, 8, 'eens',   'PvdD wil kernwapens verwijderen om de nucleaire dreiging te verkleinen.'),
(21, 9, 'oneens', 'CDA vreest dat verwijdering de afschrikking verzwakt.'),
(21, 10, 'oneens','JA21 vindt dat kernwapens een belangrijk afschrikmiddel zijn.'),
(21, 11, 'oneens','SGP hecht waarde aan de afschrikking die kernwapens bieden.'),
(21, 12, 'eens',  'FvD wil verwijdering als onderdeel van een onafhankelijke defensie.'),
(21, 13, 'eens',  'DENK steunt verwijdering om te werken aan internationale ontwapening.'),
(21, 14, 'oneens','Volt wil kernwapens verwijderen als onderdeel van een bredere ontwapeningsagenda.');

-- question_id 22 (Monarchie):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(22, 1, 'neutraal','PVV vindt dat de monarchie stabiliteit geeft en ziet geen dringende reden tot verandering.'),
(22, 2, 'oneens', 'VVD beschouwt de monarchie als een onmisbaar onderdeel van de nationale identiteit.'),
(22, 3, 'oneens', 'NSC vindt dat zowel monarchie als republiek voor- en nadelen hebben.'),
(22, 4, 'oneens', 'BBB wil de traditionele waarden van de monarchie behouden.'),
(22, 5, 'neutraal','GL-PvdA vindt dat de monarchie modernisering behoeft maar ondersteunt de huidige vorm.'),
(22, 6, 'neutraal','D66 ziet de monarchie als institutioneel erfgoed dat mee moet evolueren met de tijd.'),
(22, 7, 'eens',   'SP steunt een republiek omdat een gekozen staatshoofd democratischer is.'),
(22, 8, 'neutraal','PvdD vindt dat de discussie vooral symbolisch is en de inhoud belangrijker is.'),
(22, 9, 'oneens', 'CDA steunt de monarchie als symbool van eenheid en continuïteit.'),
(22, 10, 'oneens','JA21 wil de monarchie behouden als onderdeel van de nationale identiteit.'),
(22, 11, 'oneens','SGP vindt dat de monarchie een moreel en cultureel anker is.'),
(22, 12, 'eens',  'FvD ziet de monarchie als een verbindend symbool in een verdeelde samenleving.'),
(22, 13, 'neutraal','DENK vindt dat de discussie over de monarchie vooral over symboliek gaat.'),
(22, 14, 'neutraal','Volt ziet de monarchie als een historisch instituut dat aangepast kan worden aan moderne waarden.');

-- question_id 23 (Pensioenstelsel):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(23, 1, 'eens',   'PVV steunt terugdraaiing om de oude zekerheden te herstellen voor de burger.'),
(23, 2, 'oneens', 'VVD vindt dat een modern pensioenstelsel nodig is voor de toekomst.'),
(23, 3, 'neutraal','NSC wil dat het pensioenstelsel aangepast wordt aan economische en demografische realiteiten.'),
(23, 4, 'eens',   'BBB steunt terugdraaiing zodat gepensioneerden beter beschermd worden.'),
(23, 5, 'oneens', 'GL-PvdA wil hervormingen voor een duurzaam stelsel in plaats van terugdraaien.'),
(23, 6, 'oneens', 'D66 pleit voor innovatie in het pensioenstelsel, gezien de veranderende samenleving.'),
(23, 7, 'eens',   'SP vindt dat het terugdraaien van het nieuwe stelsel de belangen van werkenden en gepensioneerden beter beschermt.'),
(23, 8, 'neutraal','PvdD wil een menselijk en duurzaam pensioen, zonder overmatige financiële risico\'s.'),
(23, 9, 'oneens', 'CDA pleit voor een mix van hervorming en stabiliteit, passend bij de arbeidsmarkt.'),
(23, 10, 'neutraal','JA21 vindt dat de huidige hervormingen te nadelig zijn voor de burger.'),
(23, 11, 'neutraal','SGP wil dat het pensioenstelsel zowel rechtvaardig als houdbaar is.'),
(23, 12, 'eens',  'FvD steunt terugdraaiing zodat de overheid de burger beter beschermt tegen onzekerheid.'),
(23, 13, 'eens',  'DENK wil terugdraaien om sociale rechtvaardigheid te waarborgen.'),
(23, 14, 'oneens','Volt is tegen terugdraaien en pleit voor een toekomstbestendige hervorming.');

-- question_id 24 (Defensiesamenwerking):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(24, 1, 'oneens', 'PVV is tegen een Europees leger omdat zij vinden dat Nederland zijn eigen defensie moet behouden.'),
(24, 2, 'neutraal','VVD staat open voor samenwerking, maar wil nationale belangen eerst.'),
(24, 3, 'neutraal','NSC ziet voordelen in samenwerking, mits nationale belangen gewaarborgd blijven.'),
(24, 4, 'oneens', 'BBB wil behoud van nationale controle over defensie.'),
(24, 5, 'neutraal','GL-PvdA ziet in Europese samenwerking een manier om de veiligheid te versterken.'),
(24, 6, 'eens',   'D66 pleit voor een Europees leger als stap naar efficiëntere defensie.'),
(24, 7, 'oneens', 'SP vindt dat veiligheid lokaal en menselijk georganiseerd moet blijven.'),
(24, 8, 'oneens', 'PvdD wil dat nationale democratie behouden blijft, ook op defensiegebied.'),
(24, 9, 'neutraal','CDA pleit voor een hybride model waarin samenwerking en zelfstandigheid samen gaan.'),
(24, 10, 'oneens','JA21 vindt dat nationale defensie prioriteit moet krijgen.'),
(24, 11, 'oneens','SGP wil dat veiligheid primair nationaal wordt geregeld.'),
(24, 12, 'oneens','FvD is tegen samenwerking die ten koste gaat van nationale autonomie.'),
(24, 13, 'oneens','DENK steunt beperkte samenwerking maar niet een volledig Europees leger.'),
(24, 14, 'eens',  'Volt vindt dat een Europees leger bijdraagt aan collectieve veiligheid.');

-- question_id 25 (Belastingstelsel: vlaktaks):
INSERT INTO positions (question_id, party_id, stance, explanation) VALUES
(25, 1, 'neutraal','PVV vindt dat eenvoud in belastingheffing belangrijk is, mits sociale gelijkheid behouden blijft.'),
(25, 2, 'eens',   'VVD steunt een vlaktaks omdat het de belastingdruk vereenvoudigt en ondernemerschap stimuleert.'),
(25, 3, 'neutraal','NSC wil een systeem dat eerlijk is en tegelijkertijd de economie bevordert.'),
(25, 4, 'neutraal','BBB ziet voordelen in de eenvoud, maar wil ruimte voor aftrekposten en sociale zekerheid.'),
(25, 5, 'oneens', 'GL-PvdA verzet zich tegen een vlaktaks omdat zij vrezen dat dit leidt tot minder solidariteit.'),
(25, 6, 'oneens', 'D66 vindt dat maatwerk en progressiviteit nodig zijn voor een eerlijke lastverdeling.'),
(25, 7, 'oneens', 'SP wil dat de rijksten meer bijdragen en is daarom tegen een uniforme tariefstructuur.'),
(25, 8, 'oneens', 'PvdD vindt dat een vlaktaks te simplistisch kan zijn en pleit voor een mix van eenvoud en rechtvaardigheid.'),
(25, 9, 'neutraal','CDA steunt eenvoud, mits de sociale verdeling niet wordt geschaad.'),
(25, 10, 'eens',  'JA21 steunt een vlaktaks omdat dit voor overzicht zorgt en de economie stimuleert.'),
(25, 11, 'neutraal','SGP wil een balans tussen efficiëntie en sociale rechtvaardigheid.'),
(25, 12, 'eens',  'FvD is voor een vlaktaks omdat een lager, uniform tarief volgens hen eerlijk is.'),
(25, 13, 'oneens','DENK verzet zich tegen een vlaktaks uit vrees voor een oneerlijke lastverdeling.'),
(25, 14, 'oneens','Volt vindt dat de rijken proportioneel meer moeten bijdragen voor een rechtvaardig systeem.');


-- Klaar! We hebben nu:
--  - 14 partijen
--  - 25 vragen
--  - 350 posities (25 vragen × 14 partijen)
--
-- Je kunt nu SELECT queries doen om de data op te halen.
-- Bijvoorbeeld:
--   SELECT * FROM questions;
--   SELECT * FROM parties;
--   SELECT q.title, p.party_name, s.stance
--   FROM positions s
--   JOIN questions q ON s.question_id = q.question_id
--   JOIN parties p   ON s.party_id    = p.party_id
--   ORDER BY q.question_id, p.party_id;
