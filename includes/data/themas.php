<?php
/**
 * Canonieke politieke thema's voor PolitiekPraat.
 *
 * Dit is de enige bron van waarheid voor de thema-slugs. De slugs komen exact
 * overeen met:
 *   - generate-sitemap.php (de URL's die in de sitemap staan)
 *   - controllers/themas.php (het overzicht op /themas)
 *   - controllers/thema.php (de detailpagina op /thema/{slug})
 *
 * Hierdoor kunnen er geen 404's meer ontstaan door een slug-mismatch.
 *
 * Velden:
 *   - title:            weergavenaam
 *   - icon:             lucide-icoonnaam (geen emoji)
 *   - description:      korte omschrijving (overzichtskaart)
 *   - long_description: uitgebreide omschrijving (detail hero)
 *   - key_points:       kernpunten van het thema
 *   - standpunten_key:  sleutel in PoliticalParties (of null als er nog geen
 *                       standpunten zijn vastgelegd)
 *   - news_key:         sleutel voor de nieuwsfeed-mapping
 */

return [
    'klimaat-en-energie' => [
        'title' => 'Klimaat en energie',
        'icon' => 'leaf',
        'description' => 'Energietransitie, CO2-reductie en de betaalbaarheid van verduurzaming.',
        'long_description' => 'Het klimaat- en energiebeleid bepaalt hoe Nederland de uitstoot terugdringt en tegelijk de energievoorziening betrouwbaar en betaalbaar houdt. Van kernenergie en wind op zee tot de warmtepomp bij mensen thuis: de keuzes raken iedereen.',
        'key_points' => ['CO2-reductie en Klimaatwet', 'Energietransitie en netcongestie', 'Kernenergie en duurzame opwek', 'Betaalbaarheid voor huishoudens'],
        'standpunten_key' => 'klimaatbeleid',
        'news_key' => 'klimaat',
    ],
    'economie-en-financien' => [
        'title' => 'Economie en financiën',
        'icon' => 'trending-up',
        'description' => 'Koopkracht, belastingen, begroting en de arbeidsmarkt.',
        'long_description' => 'Economie en financiën gaan over koopkracht, belastingen, de rijksbegroting en een gezonde arbeidsmarkt. Partijen verschillen sterk over de rol van de overheid, lastenverlichting en investeringen in publieke voorzieningen.',
        'key_points' => ['Koopkracht en inflatie', 'Belastingstelsel en lasten', 'Arbeidsmarkt en vast werk', 'Overheidsfinanciën en begroting'],
        'standpunten_key' => 'economie',
        'news_key' => 'economie',
    ],
    'onderwijs' => [
        'title' => 'Onderwijs',
        'icon' => 'graduation-cap',
        'description' => 'Onderwijskwaliteit, kansengelijkheid en het lerarentekort.',
        'long_description' => 'Het onderwijs staat voor grote opgaven: het lerarentekort, dalende prestaties op taal en rekenen en groeiende kansenongelijkheid. Het beleid bepaalt de kwaliteit en toegankelijkheid van onderwijs voor alle leerlingen en studenten.',
        'key_points' => ['Lerarentekort', 'Onderwijskwaliteit', 'Kansengelijkheid', 'Studiefinanciering'],
        'standpunten_key' => 'onderwijs',
        'news_key' => 'default',
    ],
    'zorg-en-welzijn' => [
        'title' => 'Zorg en welzijn',
        'icon' => 'heart-pulse',
        'description' => 'Toegankelijkheid, betaalbaarheid en personeel in de zorg.',
        'long_description' => 'De zorg staat onder druk door personeelstekorten, wachtlijsten en stijgende kosten. Het debat gaat over het eigen risico, marktwerking en hoe we de zorg ook in de toekomst toegankelijk en betaalbaar houden.',
        'key_points' => ['Eigen risico en zorgkosten', 'Personeelstekorten', 'Wachtlijsten', 'Ouderenzorg en preventie'],
        'standpunten_key' => 'zorg',
        'news_key' => 'gezondheid',
    ],
    'migratie-en-asiel' => [
        'title' => 'Migratie en asiel',
        'icon' => 'users',
        'description' => 'Asielbeleid, arbeidsmigratie en integratie.',
        'long_description' => 'Migratie en asiel behoren tot de meest besproken politieke thema\'s. Het gaat over de instroom van asielzoekers, arbeidsmigratie, opvang en integratie, en het draagvlak in de samenleving.',
        'key_points' => ['Asielbeleid en instroom', 'Arbeidsmigratie', 'Opvang en huisvesting', 'Integratie en inburgering'],
        'standpunten_key' => 'immigratie',
        'news_key' => 'default',
    ],
    'veiligheid-en-justitie' => [
        'title' => 'Veiligheid en justitie',
        'icon' => 'shield',
        'description' => 'Criminaliteitsbestrijding, politie, ondermijning en cyberveiligheid.',
        'long_description' => 'Veiligheid en justitie gaan over de aanpak van criminaliteit, georganiseerde ondermijning en cyberdreigingen, en over de capaciteit van politie en justitie. Partijen verschillen over preventie versus harde aanpak.',
        'key_points' => ['Georganiseerde criminaliteit', 'Politiecapaciteit', 'Cyberveiligheid', 'Preventie versus straffen'],
        'standpunten_key' => 'veiligheid',
        'news_key' => 'default',
    ],
    'europa' => [
        'title' => 'Europa',
        'icon' => 'globe',
        'description' => 'De rol van Nederland in de Europese Unie.',
        'long_description' => 'Het Europabeleid bepaalt hoe Nederland zich verhoudt tot de Europese Unie: van de interne markt en de euro tot uitbreiding, soevereiniteit en gezamenlijk buitenlandbeleid. De EU raakt vrijwel elk ander beleidsterrein.',
        'key_points' => ['Interne markt en euro', 'Uitbreiding van de EU', 'Soevereiniteit en regelgeving', 'Gezamenlijk buitenlandbeleid'],
        'standpunten_key' => null,
        'news_key' => 'default',
    ],
    'defensie' => [
        'title' => 'Defensie',
        'icon' => 'shield-check',
        'description' => 'Krijgsmacht, NAVO-norm en nationale veiligheid.',
        'long_description' => 'Door de oorlog in Oekraïne en oplopende internationale spanningen staat defensie hoog op de agenda. Het gaat over de hoogte van het defensiebudget, de NAVO-norm van 2 procent en de slagkracht van de krijgsmacht.',
        'key_points' => ['NAVO-norm van 2 procent', 'Versterking krijgsmacht', 'Steun aan Oekraïne', 'Europese samenwerking'],
        'standpunten_key' => null,
        'news_key' => 'default',
    ],
    'landbouw-en-natuur' => [
        'title' => 'Landbouw en natuur',
        'icon' => 'sprout',
        'description' => 'Stikstof, boeren, natuurherstel en voedselproductie.',
        'long_description' => 'Landbouw en natuur draaien om de balans tussen een sterke agrarische sector en het herstel van natuur en biodiversiteit. Het stikstofdossier en de toekomst van de boer staan centraal in het debat.',
        'key_points' => ['Stikstofaanpak', 'Toekomst van de boer', 'Natuurherstel en biodiversiteit', 'Voedselzekerheid'],
        'standpunten_key' => 'duurzaamheid',
        'news_key' => 'default',
    ],
    'wonen' => [
        'title' => 'Wonen',
        'icon' => 'home',
        'description' => 'Woningtekort, betaalbaarheid en de huurmarkt.',
        'long_description' => 'De wooncrisis is voor veel mensen voelbaar: er zijn te weinig betaalbare woningen en de prijzen blijven hoog. Het beleid gaat over bouwtempo, regulering van de huurmarkt en betaalbaar wonen voor starters en middeninkomens.',
        'key_points' => ['Woningtekort en bouwtempo', 'Betaalbaarheid voor starters', 'Huurmarkt en regulering', 'Verduurzaming van woningen'],
        'standpunten_key' => 'woningmarkt',
        'news_key' => 'default',
    ],
    'mobiliteit-en-verkeer' => [
        'title' => 'Mobiliteit en verkeer',
        'icon' => 'train-front',
        'description' => 'Openbaar vervoer, wegen, fiets en verduurzaming van transport.',
        'long_description' => 'Mobiliteit en verkeer gaan over bereikbaarheid: van investeringen in het spoor en openbaar vervoer tot wegen, de fiets en de overstap naar elektrisch rijden. Bereikbaarheid en duurzaamheid moeten in balans.',
        'key_points' => ['Openbaar vervoer en spoor', 'Wegen en bereikbaarheid', 'Elektrisch en schoon vervoer', 'Fiets en verkeersveiligheid'],
        'standpunten_key' => null,
        'news_key' => 'default',
    ],
    'digitalisering' => [
        'title' => 'Digitalisering',
        'icon' => 'cpu',
        'description' => 'Privacy, AI, cyberveiligheid en de digitale overheid.',
        'long_description' => 'Digitalisering raakt steeds meer aspecten van de samenleving: van kunstmatige intelligentie en online privacy tot een betrouwbare digitale overheid en weerbaarheid tegen cyberaanvallen.',
        'key_points' => ['Privacy en gegevensbescherming', 'Kunstmatige intelligentie', 'Digitale overheid', 'Cyberweerbaarheid'],
        'standpunten_key' => null,
        'news_key' => 'tech',
    ],
];
