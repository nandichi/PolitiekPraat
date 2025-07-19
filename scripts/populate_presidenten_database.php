<?php
/**
 * Script om de Amerikaanse presidenten database te populeren
 * Voegt alle 46 presidenten toe met uitgebreide informatie
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

// Database connectie
$db = new Database();

echo "🇺🇸 Amerikaanse Presidenten Database Populatie Script\n";
echo "================================================\n\n";

// Presidenten data - alle 46 presidenten met uitgebreide informatie
$presidenten = [
    // 1. George Washington
    [
        'president_nummer' => 1,
        'naam' => 'George Washington',
        'volledige_naam' => 'George Washington',
        'bijnaam' => 'Father of His Country',
        'partij' => 'Unaffiliated',
        'periode_start' => '1789-04-30',
        'periode_eind' => '1797-03-04',
        'geboren' => '1732-02-22',
        'overleden' => '1799-12-14',
        'geboorteplaats' => 'Westmoreland County, Virginia',
        'vice_president' => 'John Adams',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/b/b6/Gilbert_Stuart_Williamstown_Portrait_of_George_Washington.jpg',
        'biografie' => 'George Washington was de eerste president van de Verenigde Staten en wordt beschouwd als een van de founding fathers. Hij leidde de Continental Army tijdens de Amerikaanse Revolutie en werd unaniem gekozen als eerste president.',
        'vroeg_leven' => 'Geboren in een welvarende plantersfamilie in Virginia. Verloor zijn vader op 11-jarige leeftijd en werd opgevoed door zijn oudere broer Lawrence.',
        'politieke_carriere' => 'Begon als landmeter, werd militair officier, diende in Virginia House of Burgesses, leidde Continental Army, presideerde Constitutional Convention.',
        'prestaties' => json_encode([
            'Eerste president van de Verenigde Staten',
            'Leidde Continental Army naar overwinning in Amerikaanse Revolutie',
            'Presideerde Constitutional Convention van 1787',
            'Stelde precedent voor presidentieel gedrag',
            'Deed vrijwillig afstand van macht na twee termijnen'
        ]),
        'fun_facts' => json_encode([
            'Had houten tanden (mythe - waren van ivoor en goud)',
            'Was de rijkste president in de geschiedenis (inflatie-gecorrigeerd)',
            'Nooit inwoner van Washington D.C. geweest',
            'Enige president die unaniem gekozen werd door Electoral College',
            'Had malaria, smallpox, pleuritis, dysentery en tuberculose gehad'
        ]),
        'echtgenote' => 'Martha Dandridge Custis Washington',
        'kinderen' => json_encode(['Geen biologische kinderen', 'Adopteerde Martha\'s kinderen: John Parke Custis en Martha Parke Custis']),
        'familie_connecties' => 'Geen directe familie in presidentiële ambten. Verre neef van Robert E. Lee.',
        'lengte_cm' => 188,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1789, 1792]),
        'leeftijd_bij_aantreden' => 57,
        'belangrijkste_gebeurtenissen' => 'Whiskey Rebellion (1794), Jay\'s Treaty (1795), Farewell Address (1796)',
        'bekende_speeches' => 'Farewell Address, First Inaugural Address',
        'wetgeving' => json_encode(['Judiciary Act of 1789', 'Residence Act of 1790', 'Bank Act of 1791']),
        'oorlogen' => json_encode(['Quasi-War met Frankrijk (voorbereidingen)']),
        'economische_situatie' => 'Post-revolutionaire economische stabilisatie, oprichting van nationale bank',
        'carrierre_voor_president' => 'Landmeter, planter, militair commandant, delegaat Constitutional Convention',
        'carrierre_na_president' => 'Teruggetrokken op Mount Vernon, militair adviseur tijdens Quasi-War',
        'doodsoorzaak' => 'Acute laryngitis/pneumonie',
        'begrafenisplaats' => 'Mount Vernon, Virginia',
        'historische_waardering' => 'Consistent gerangschikt als een van de drie beste presidenten in de geschiedenis',
        'controverses' => 'Slavenhouder (hoewel hij in zijn testament hun vrijlating regelde), Whiskey Rebellion onderdrukking',
        'citaten' => json_encode([
            '"It is better to offer no excuse than a bad one."',
            '"Liberty, when it begins to take root, is a plant of rapid growth."',
            '"Guard against the impostures of pretended patriotism."'
        ]),
        'monumenten_ter_ere' => 'Washington Monument, Mount Rushmore, hoofdstad Washington D.C., staat Washington'
    ],
    
    // 2. John Adams
    [
        'president_nummer' => 2,
        'naam' => 'John Adams',
        'volledige_naam' => 'John Adams',
        'bijnaam' => 'Atlas of Independence',
        'partij' => 'Federalist',
        'periode_start' => '1797-03-04',
        'periode_eind' => '1801-03-04',
        'geboren' => '1735-10-30',
        'overleden' => '1826-07-04',
        'geboorteplaats' => 'Braintree, Massachusetts',
        'vice_president' => 'Thomas Jefferson',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/e4/John_Adams_A18236.jpg',
        'biografie' => 'John Adams was de tweede president van de Verenigde Staten en een van de founding fathers. Hij was diplomaat, advocaat en politiek theoreticus.',
        'vroeg_leven' => 'Geboren in een boerenfamilie in Massachusetts. Studeerde aan Harvard College en werd advocaat.',
        'politieke_carriere' => 'Advocaat, delegaat Continental Congress, diplomaat in Europa, eerste vice-president onder Washington.',
        'prestaties' => json_encode([
            'Hielp onderhandelen over Verdrag van Parijs (1783)',
            'Eerste vice-president van de Verenigde Staten',
            'Voorkwam oorlog met Frankrijk tijdens Quasi-War',
            'Benoemde John Marshall tot Chief Justice',
            'Vader van Amerikaanse diplomatie'
        ]),
        'fun_facts' => json_encode([
            'Stierf op dezelfde dag als Thomas Jefferson (4 juli 1826)',
            'Eerste president die in het Witte Huis woonde',
            'Zoon John Quincy Adams werd ook president',
            'Was onderwijzer voordat hij advocaat werd',
            'Leefde langer dan elke andere president (90 jaar, 247 dagen)'
        ]),
        'echtgenote' => 'Abigail Smith Adams',
        'kinderen' => json_encode(['John Quincy Adams (6e president)', 'Susanna Adams', 'Charles Adams', 'Thomas Boylston Adams']),
        'familie_connecties' => 'Vader van John Quincy Adams (6e president). Adams political dynasty.',
        'lengte_cm' => 170,
        'gewicht_kg' => 61,
        'verkiezingsjaren' => json_encode([1796]),
        'leeftijd_bij_aantreden' => 61,
        'belangrijkste_gebeurtenissen' => 'XYZ Affair (1797-1798), Quasi-War met Frankrijk, Alien and Sedition Acts (1798)',
        'bekende_speeches' => 'Inaugural Address 1797, Addresses to Congress over French Crisis',
        'wetgeving' => json_encode(['Alien and Sedition Acts', 'Judiciary Act of 1801']),
        'oorlogen' => json_encode(['Quasi-War met Frankrijk (1798-1800)']),
        'economische_situatie' => 'Relatief stabiel, voorbereiding op oorlog verhoogde uitgaven',
        'carrierre_voor_president' => 'Advocaat, diplomaat (Nederland, Groot-Brittannië), vice-president',
        'carrierre_na_president' => 'Pensionering in Quincy, Massachusetts; uitgebreide correspondentie',
        'doodsoorzaak' => 'Hartfalen',
        'begrafenisplaats' => 'United First Parish Church, Quincy, Massachusetts',
        'historische_waardering' => 'Gerespecteerd voor integriteit en vermijden van oorlog, kritiek op Alien and Sedition Acts',
        'controverses' => 'Alien and Sedition Acts beperkte vrijheid van meningsuiting',
        'citaten' => json_encode([
            '"Remember, democracy never lasts long. It soon wastes, exhausts, and murders itself."',
            '"Facts are stubborn things."',
            '"Power always thinks it has a great soul and vast views beyond the comprehension of the weak."'
        ]),
        'monumenten_ter_ere' => 'Adams National Historical Park, USS John Adams, talrijke scholen en straten'
    ],

    // 3. Thomas Jefferson
    [
        'president_nummer' => 3,
        'naam' => 'Thomas Jefferson',
        'volledige_naam' => 'Thomas Jefferson',
        'bijnaam' => 'The Sage of Monticello',
        'partij' => 'Democratic-Republican',
        'periode_start' => '1801-03-04',
        'periode_eind' => '1809-03-04',
        'geboren' => '1743-04-13',
        'overleden' => '1826-07-04',
        'geboorteplaats' => 'Shadwell, Virginia',
        'vice_president' => 'Aaron Burr (1801-1805), George Clinton (1805-1809)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Thomas_Jefferson_by_Rembrandt_Peale%2C_1800.jpg',
        'biografie' => 'Thomas Jefferson was de derde president en de hoofdauteur van de Onafhankelijkheidsverklaring. Een Renaissance-man met interesses in wetenschap, architectuur, en filosofie.',
        'vroeg_leven' => 'Geboren in een welvarende plantersfamilie. Studeerde aan College of William & Mary, werd advocaat.',
        'politieke_carriere' => 'Virginia House of Burgesses, Continental Congress, gouverneur van Virginia, minister in Frankrijk, Secretary of State.',
        'prestaties' => json_encode([
            'Hoofdauteur van de Onafhankelijkheidsverklaring',
            'Louisiana Purchase (1803) - verdubbelde grootte van VS',
            'Lewis and Clark Expeditie',
            'Oprichter University of Virginia',
            'Virginia Statute for Religious Freedom'
        ]),
        'fun_facts' => json_encode([
            'Stierf op dezelfde dag als John Adams (4 juli 1826)',
            'Kon 6 talen spreken',
            'Uitvinder van de dumbwaiter en draaistoelen',
            'Hield 600+ slaven ondanks anti-slavernij geschriften',
            'Schreef zijn eigen epitaph zonder presidentschap te vermelden'
        ]),
        'echtgenote' => 'Martha Wayles Skelton Jefferson',
        'kinderen' => json_encode(['Martha Washington Jefferson', 'Mary Jefferson', '4 andere kinderen die jong stierven', 'Vermoedelijk kinderen met Sally Hemings']),
        'familie_connecties' => 'Verre neef van verschillende Virginia politici. Complexe relatie met Sally Hemings.',
        'lengte_cm' => 189,
        'gewicht_kg' => 77,
        'verkiezingsjaren' => json_encode([1800, 1804]),
        'leeftijd_bij_aantreden' => 57,
        'belangrijkste_gebeurtenissen' => 'Louisiana Purchase (1803), Lewis & Clark Expeditie (1804-1806), Embargo Act (1807), Barbary Wars',
        'bekende_speeches' => 'First Inaugural Address ("We are all Republicans, we are all Federalists")',
        'wetgeving' => json_encode(['Louisiana Purchase Treaty', 'Embargo Act of 1807', 'Non-Intercourse Act']),
        'oorlogen' => json_encode(['First Barbary War (1801-1805)', 'Tripolitan War']),
        'economische_situatie' => 'Embargo Act schaadde handel, maar Louisiana Purchase stimuleerde expansie',
        'carrierre_voor_president' => 'Advocaat, planter, Minister in Frankrijk, Secretary of State onder Washington',
        'carrierre_na_president' => 'Oprichter University of Virginia, correspondentie, architectuur Monticello',
        'doodsoorzaak' => 'Vermoedelijk uremie en longontsteking',
        'begrafenisplaats' => 'Monticello, Virginia',
        'historische_waardering' => 'Een van de drie grootste presidenten, kritiek over slavernij en Sally Hemings relatie',
        'controverses' => 'Slavenhouder ondanks "all men are created equal", Sally Hemings relatie, Embargo Act',
        'citaten' => json_encode([
            '"We hold these truths to be self-evident, that all men are created equal"',
            '"The tree of liberty must be refreshed from time to time with the blood of patriots and tyrants"',
            '"I cannot live without books"'
        ]),
        'monumenten_ter_ere' => 'Jefferson Memorial, Mount Rushmore, Louisiana Purchase Monument, University of Virginia'
    ],

    // 4. James Madison
    [
        'president_nummer' => 4,
        'naam' => 'James Madison',
        'volledige_naam' => 'James Madison Jr.',
        'bijnaam' => 'Father of the Constitution',
        'partij' => 'Democratic-Republican',
        'periode_start' => '1809-03-04',
        'periode_eind' => '1817-03-04',
        'geboren' => '1751-03-16',
        'overleden' => '1836-06-28',
        'geboorteplaats' => 'Port Conway, Virginia',
        'vice_president' => 'George Clinton (1809-1812), Elbridge Gerry (1813-1814)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1d/James_Madison.jpg',
        'biografie' => 'James Madison was de vierde president en wordt beschouwd als de "Father of the Constitution" voor zijn belangrijke rol bij het opstellen van de Amerikaanse Grondwet.',
        'vroeg_leven' => 'Kleinste president ooit (1.63m, 45kg). Studeerde aan Princeton University, leed aan epilepsie.',
        'politieke_carriere' => 'Virginia Assembly, Continental Congress, Constitutional Convention, Virginia Ratifying Convention, House of Representatives.',
        'prestaties' => json_encode([
            'Hoofdauteur van de Amerikaanse Grondwet',
            'Schreef de Bill of Rights',
            'Co-auteur van The Federalist Papers',
            'Leidde het land door War of 1812',
            'Vader van de Democratic-Republican Party'
        ]),
        'fun_facts' => json_encode([
            'Kleinste president: 1,63m en 45kg',
            'Eerste president die oorlog verklaarde (War of 1812)',
            'Leefde het langst van alle founding fathers',
            'Moest vluchten uit Washington D.C. toen Britten het verbranden',
            'Was de laatste president die tegen koning gevochten had'
        ]),
        'echtgenote' => 'Dolley Payne Todd Madison',
        'kinderen' => json_encode(['Geen biologische kinderen', 'Adopteerde Dolley\'s zoon John Payne Todd']),
        'familie_connecties' => 'Neef van Zachary Taylor (12e president). Virginia political dynasty.',
        'lengte_cm' => 163,
        'gewicht_kg' => 45,
        'verkiezingsjaren' => json_encode([1808, 1812]),
        'leeftijd_bij_aantreden' => 57,
        'belangrijkste_gebeurtenissen' => 'War of 1812, Burning of Washington (1814), Battle of New Orleans (1815), Second Bank charter',
        'bekende_speeches' => 'War Message to Congress (1812), Post-war addresses',
        'wetgeving' => json_encode(['Declaration of War 1812', 'Charter of Second Bank of US', 'Tariff of 1816']),
        'oorlogen' => json_encode(['War of 1812 tegen Groot-Brittannië']),
        'economische_situatie' => 'Oorlogseconomie, post-oorlog nationalistische programma\'s',
        'carrierre_voor_president' => 'Politiek theoreticus, Secretary of State onder Jefferson',
        'carrierre_na_president' => 'Teruggetrokken op Montpelier, adviseur University of Virginia',
        'doodsoorzaak' => 'Hartfalen',
        'begrafenisplaats' => 'Montpelier, Virginia',
        'historische_waardering' => 'Gerespecteerd als constitutioneel genius, kritiek op oorlogsleiderschap',
        'controverses' => 'War of 1812 aanvankelijk slecht verlopen, slavenhouder ondanks anti-slavernij sentimenten',
        'citaten' => json_encode([
            '"If men were angels, no government would be necessary"',
            '"Knowledge will forever govern ignorance"',
            '"The advancement and diffusion of knowledge is the only guardian of true liberty"'
        ]),
        'monumenten_ter_ere' => 'Madison Building (Library of Congress), James Madison University, USS Madison'
    ],

    // 5. James Monroe
    [
        'president_nummer' => 5,
        'naam' => 'James Monroe',
        'volledige_naam' => 'James Monroe',
        'bijnaam' => 'The Last Cocked Hat',
        'partij' => 'Democratic-Republican',
        'periode_start' => '1817-03-04',
        'periode_eind' => '1825-03-04',
        'geboren' => '1758-04-28',
        'overleden' => '1831-07-04',
        'geboorteplaats' => 'Westmoreland County, Virginia',
        'vice_president' => 'Daniel D. Tompkins',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d4/James_Monroe_White_House_portrait_1819.jpg',
        'biografie' => 'James Monroe was de vijfde president, bekend om de Monroe Doctrine en presideerde tijdens het "Era of Good Feelings" - een periode van nationale eenheid.',
        'vroeg_leven' => 'Vocht in Revolutionary War op 16-jarige leeftijd. Studeerde onder Thomas Jefferson.',
        'politieke_carriere' => 'Virginia Assembly, Continental Congress, Senator, gouverneur Virginia, Minister Frankrijk/Groot-Brittannië.',
        'prestaties' => json_encode([
            'Monroe Doctrine (1823) - grondslag Amerikaanse buitenlandse politiek',
            'Missouri Compromise (1820)',
            'Era of Good Feelings - nationale eenheid',
            'Florida Purchase van Spanje (1819)',
            'Laatste president uit Revolutionary War generatie'
        ]),
        'fun_facts' => json_encode([
            'Laatste president die tricorn hoed droeg',
            'Gewond geraakt in Battle of Trenton',
            'Stierf op 4 juli (derde president op rij)',
            'Enige president die herkozen werd zonder oppositie (1820)',
            'Laatste president uit Virginia Dynasty'
        ]),
        'echtgenote' => 'Elizabeth Kortright Monroe',
        'kinderen' => json_encode(['Eliza Monroe Hay', 'Maria Hester Monroe']),
        'familie_connecties' => 'Geen directe presidentiële familie. Onderdeel van Virginia Dynasty.',
        'lengte_cm' => 183,
        'gewicht_kg' => 77,
        'verkiezingsjaren' => json_encode([1816, 1820]),
        'leeftijd_bij_aantreden' => 58,
        'belangrijkste_gebeurtenissen' => 'Missouri Compromise (1820), Monroe Doctrine (1823), Panic of 1819, Florida acquisition',
        'bekende_speeches' => 'Monroe Doctrine Address (1823), Annual Messages to Congress',
        'wetgeving' => json_encode(['Missouri Compromise', 'Adams-Onís Treaty', 'General Survey Act']),
        'oorlogen' => json_encode(['Seminole Wars in Florida']),
        'economische_situatie' => 'Panic of 1819 gevolgd door economisch herstel',
        'carrierre_voor_president' => 'Diplomaat, gouverneur Virginia, Secretary of State en War onder Madison',
        'carrierre_na_president' => 'Financiële problemen, delegate Virginia Constitutional Convention',
        'doodsoorzaak' => 'Hartfalen en longtuberculose',
        'begrafenisplaats' => 'Hollywood Cemetery, Richmond, Virginia',
        'historische_waardering' => 'Positief beoordeeld voor Monroe Doctrine en nationale eenheid',
        'controverses' => 'Missouri Compromise stel slavernij kwestie uit, slavenhouder',
        'citaten' => json_encode([
            '"The American continents are henceforth not to be considered as subjects for future colonization by any European powers"',
            '"It is only when the people become ignorant and corrupt, when they degenerate into a populace, that they are incapable of exercising the sovereignty"'
        ]),
        'monumenten_ter_ere' => 'Monroe Doctrine Monument, James Monroe Museum, Monrovia (Liberia) vernoemd naar hem'
    ],
    
    // 6. John Quincy Adams
    [
        'president_nummer' => 6,
        'naam' => 'John Quincy Adams',
        'volledige_naam' => 'John Quincy Adams',
        'bijnaam' => 'Old Man Eloquent',
        'partij' => 'Democratic-Republican/National Republican',
        'periode_start' => '1825-03-04',
        'periode_eind' => '1829-03-04',
        'geboren' => '1767-07-11',
        'overleden' => '1848-02-23',
        'geboorteplaats' => 'Braintree, Massachusetts',
        'vice_president' => 'John C. Calhoun',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/John_Quincy_Adams_by_George_Peter_Alexander_Healy.jpg',
        'biografie' => 'John Quincy Adams was de zesde president en zoon van John Adams. Bekwaam diplomaat en later anti-slavernij activist in het Huis.',
        'vroeg_leven' => 'Zoon van president John Adams. Reisde naar Europa als kind, sprak meerdere talen.',
        'politieke_carriere' => 'Diplomaat vanaf jonge leeftijd, Senator, Minister verschillende landen, Secretary of State.',
        'prestaties' => json_encode([
            'Onderhandelde over Treaty of Ghent (einde War of 1812)',
            'Architect van Monroe Doctrine als Secretary of State',
            '17 jaar in Huis na presidentschap - anti-slavernij activist',
            'Oprichter Smithsonian Institution',
            'Eerste president gefotografeerd'
        ]),
        'fun_facts' => json_encode([
            'Enige president zoon van een president (tot George W. Bush)',
            'Zwom naakt in Potomac River elke ochtend',
            'Sprak 8 talen vloeiend',
            'Stierf letterlijk op zijn werkplek in het Huis',
            'Eerste president gefotografeerd (daguerreotype)'
        ]),
        'echtgenote' => 'Louisa Catherine Johnson Adams',
        'kinderen' => json_encode(['George Washington Adams', 'John Adams II', 'Charles Francis Adams']),
        'familie_connecties' => 'Zoon van John Adams (2e president). Adams political dynasty.',
        'lengte_cm' => 171,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1824]),
        'leeftijd_bij_aantreden' => 57,
        'belangrijkste_gebeurtenissen' => 'Corrupt Bargain (1824), American System programma, Tariff of Abominations (1828)',
        'bekende_speeches' => 'Inaugural Address, Anti-slavery speeches in House',
        'wetgeving' => json_encode(['Tariff of 1828', 'Internal improvements funding']),
        'oorlogen' => json_encode(['Geen grote oorlogen']),
        'economische_situatie' => 'Economische groei, maar politieke oppositie tegen uitgaven',
        'carrierre_voor_president' => 'Diplomaat (Nederland, Pruisen, Rusland, Groot-Brittannië), Secretary of State',
        'carrierre_na_president' => '17 jaar in Huis van Afgevaardigden, leidde anti-slavernij beweging',
        'doodsoorzaak' => 'Beroerte',
        'begrafenisplaats' => 'United First Parish Church, Quincy, Massachusetts',
        'historische_waardering' => 'Beter beoordeeld als diplomaat en Congressman dan als president',
        'controverses' => '"Corrupt Bargain" van 1824, Tariff of Abominations',
        'citaten' => json_encode([
            '"If your actions inspire others to dream more, learn more, do more and become more, you are a leader"',
            '"This is the last of earth! I am content"'
        ]),
        'monumenten_ter_ere' => 'John Quincy Adams birthplace, various schools and institutions'
    ],

    // 7. Andrew Jackson
    [
        'president_nummer' => 7,
        'naam' => 'Andrew Jackson',
        'volledige_naam' => 'Andrew Jackson',
        'bijnaam' => 'Old Hickory',
        'partij' => 'Democratic',
        'periode_start' => '1829-03-04',
        'periode_eind' => '1837-03-04',
        'geboren' => '1767-03-15',
        'overleden' => '1845-06-08',
        'geboorteplaats' => 'Waxhaws Region, South Carolina',
        'vice_president' => 'John C. Calhoun (1829-1832), Martin Van Buren (1833-1837)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/43/Andrew_Jackson.jpg',
        'biografie' => 'Andrew Jackson was de zevende president, een populistische leider bekend om zijn militaire heldendaden en de oprichting van de Democratische Partij. Zijn presidentschap, bekend als de Jacksonian Democracy, breidde de macht van de president uit.',
        'vroeg_leven' => 'Geboren in armoede, wees op jonge leeftijd. Werd een succesvolle advocaat en landspeculant in Tennessee.',
        'politieke_carriere' => 'Advocaat, U.S. Congressman, U.S. Senator, rechter Tennessee Supreme Court, generaal-majoor.',
        'prestaties' => json_encode([
            'Oprichter van de moderne Democratische Partij',
            'Lostte de staatsschuld volledig af (enige president die dit deed)',
            'Breidde de macht van het presidentschap uit',
            'Voorkwam de afscheiding van South Carolina tijdens de Nullification Crisis',
            'Werd een symbool voor de "gewone man"'
        ]),
        'fun_facts' => json_encode([
            'Hield een open huis feest in het Witte Huis dat zo uit de hand liep dat hij via een raam moest ontsnappen',
            'Overleefde een moordaanslag',
            'Had een papegaai die uit zijn begrafenis werd verwijderd omdat hij vloekte',
            'Nam deel aan naar schatting 100 duels',
            'Droeg een kogel in zijn borst van een duel voor het grootste deel van zijn leven'
        ]),
        'echtgenote' => 'Rachel Donelson Robards Jackson',
        'kinderen' => json_encode(['Adopteerde drie zonen']),
        'familie_connecties' => 'Geen directe presidentiële familie.',
        'lengte_cm' => 185,
        'gewicht_kg' => 64,
        'verkiezingsjaren' => json_encode([1828, 1832]),
        'leeftijd_bij_aantreden' => 61,
        'belangrijkste_gebeurtenissen' => 'Indian Removal Act (1830), Nullification Crisis (1832-1833), Bank War (1832-1836), Trail of Tears',
        'bekende_speeches' => 'Veto Message Regarding the Bank of the United States',
        'wetgeving' => json_encode(['Indian Removal Act of 1830', 'Force Bill (1833)']),
        'oorlogen' => json_encode(['Black Hawk War (1832)', 'Second Seminole War (1835-1842)']),
        'economische_situatie' => 'Vernietigde de Second Bank of the U.S., wat bijdroeg aan de Paniek van 1837',
        'carrierre_voor_president' => 'Militair leider (War of 1812), advocaat, rechter, senator',
        'carrierre_na_president' => 'Teruggetrokken op zijn plantage The Hermitage',
        'doodsoorzaak' => 'Chronische tuberculose, oedeem en hartfalen',
        'begrafenisplaats' => 'The Hermitage, Nashville, Tennessee',
        'historische_waardering' => 'Controversieel; geprezen voor het verdedigen van de Unie, maar zwaar bekritiseerd voor de Indian Removal Act',
        'controverses' => 'Indian Removal Act en de Trail of Tears, zijn eigendom van slaven, de Bank War, Petticoat affair',
        'citaten' => json_encode([
            '"The Bank... is trying to kill me, but I will kill it!"',
            '"To the victors belong the spoils"'
        ]),
        'monumenten_ter_ere' => 'Standbeelden in veel steden (sommige verwijderd), Jackson Square in New Orleans, $20 biljet (mogelijk aan verandering onderhevig)'
    ],

    // 8. Martin Van Buren
    [
        'president_nummer' => 8,
        'naam' => 'Martin Van Buren',
        'volledige_naam' => 'Martin Van Buren',
        'bijnaam' => 'The Little Magician',
        'partij' => 'Democratic',
        'periode_start' => '1837-03-04',
        'periode_eind' => '1841-03-04',
        'geboren' => '1782-12-05',
        'overleden' => '1862-07-24',
        'geboorteplaats' => 'Kinderhook, New York',
        'vice_president' => 'Richard Mentor Johnson',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/07/Martin_Van_Buren_by_Mathew_Brady_c1855-58.jpg',
        'biografie' => 'Martin Van Buren was de achtste president en een sleutelfiguur in de organisatie van de Democratische Partij. Zijn presidentschap werd gedomineerd door de Paniek van 1837, een ernstige economische depressie.',
        'vroeg_leven' => 'Groeide op in een Nederlandssprekend gezin in New York. Werd advocaat zonder formele hogere opleiding.',
        'politieke_carriere' => 'New York State Senator, U.S. Senator, gouverneur van New York, Secretary of State, vice-president.',
        'prestaties' => json_encode([
            'Belangrijke organisator van de Democratische Partij',
            'Creëerde een onafhankelijk schatkistsysteem (Independent Treasury)',
            'Voorkwam oorlog met Groot-Brittannië over de grens met Canada (Caroline Affair)',
            'Zette Jacksoniaanse principes voort'
        ]),
        'fun_facts' => json_encode([
            'Eerste president die als Amerikaans staatsburger werd geboren (niet als Brits onderdaan)',
            'Zijn moedertaal was Nederlands, niet Engels',
            'Populariseerde de uitdrukking "OK" (van zijn bijnaam "Old Kinderhook")',
            'Was een van de oprichters van de Free Soil Party na zijn presidentschap'
        ]),
        'echtgenote' => 'Hannah Hoes Van Buren',
        'kinderen' => json_encode(['Abraham Van Buren', 'John Van Buren', 'Martin Van Buren Jr.', 'Smith Thompson Van Buren']),
        'familie_connecties' => 'Geen directe presidentiële familie.',
        'lengte_cm' => 168,
        'gewicht_kg' => 65,
        'verkiezingsjaren' => json_encode([1836]),
        'leeftijd_bij_aantreden' => 54,
        'belangrijkste_gebeurtenissen' => 'Paniek van 1837, Caroline Affair, Amistad-zaak, voortzetting van de Second Seminole War',
        'bekende_speeches' => 'Inaugural Address (1837)',
        'wetgeving' => json_encode(['Independent Treasury Act of 1840']),
        'oorlogen' => json_encode(['Second Seminole War (voortgezet)']),
        'economische_situatie' => 'Ernstige economische depressie (Paniek van 1837) gedurende zijn hele termijn',
        'carrierre_voor_president' => 'Advocaat, gouverneur, senator, Secretary of State, vice-president',
        'carrierre_na_president' => 'Mislukte herverkiezingspogingen in 1844 en 1848 (als Free Soil kandidaat)',
        'doodsoorzaak' => 'Astma en hartfalen',
        'begrafenisplaats' => 'Kinderhook Reformed Dutch Church Cemetery, Kinderhook, New York',
        'historische_waardering' => 'Vaak gezien als een ondergemiddelde president vanwege zijn onvermogen om de Paniek van 1837 effectief aan te pakken',
        'controverses' => 'Zijn aanpak van de economische crisis, de voortzetting van het beleid van de Indian Removal Act',
        'citaten' => json_encode([
            '"As to the presidency, the two happiest days of my life were those of my entrance upon the office and my surrender of it."'
        ]),
        'monumenten_ter_ere' => 'Martin Van Buren National Historic Site, straten en scholen'
    ],
    
    // 9. William Henry Harrison
    [
        'president_nummer' => 9,
        'naam' => 'William Henry Harrison',
        'volledige_naam' => 'William Henry Harrison',
        'bijnaam' => 'Tippecanoe',
        'partij' => 'Whig',
        'periode_start' => '1841-03-04',
        'periode_eind' => '1841-04-04',
        'geboren' => '1773-02-09',
        'overleden' => '1841-04-04',
        'geboorteplaats' => 'Berkeley Plantation, Virginia',
        'vice_president' => 'John Tyler',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/3/34/William_Henry_Harrison_by_James_Reid_Lambdin%2C_1835.jpg',
        'biografie' => 'William Henry Harrison was de negende president. Hij stierf slechts 31 dagen na zijn inauguratie, wat resulteerde in de kortste presidentiële termijn in de Amerikaanse geschiedenis.',
        'vroeg_leven' => 'Geboren in een prominente politieke familie in Virginia. Studeerde medicijnen, maar koos voor een militaire carrière.',
        'politieke_carriere' => 'Militair leider (Battle of Tippecanoe), gouverneur van Indiana Territory, U.S. Congressman en Senator uit Ohio.',
        'prestaties' => json_encode([
            'Hield de langste inaugurele rede in de geschiedenis (bijna 2 uur)',
            'Zijn dood leidde tot duidelijkheid over de presidentiële opvolging'
        ]),
        'fun_facts' => json_encode([
            'Kortste presidentschap ooit (31 dagen)',
            'Eerste president die in functie stierf',
            'Zijn kleinzoon, Benjamin Harrison, werd de 23e president',
            'Zijn campagneslogan was "Tippecanoe and Tyler Too"'
        ]),
        'echtgenote' => 'Anna Tuthill Symmes Harrison',
        'kinderen' => json_encode(['10 kinderen, waaronder John Scott Harrison, vader van president Benjamin Harrison']),
        'familie_connecties' => 'Grootvader van Benjamin Harrison (23e president).',
        'lengte_cm' => 173,
        'gewicht_kg' => 82,
        'verkiezingsjaren' => json_encode([1840]),
        'leeftijd_bij_aantreden' => 68,
        'belangrijkste_gebeurtenissen' => 'Zijn dood in functie',
        'bekende_speeches' => 'Inaugural Address (1841)',
        'wetgeving' => json_encode(['Geen, stierf te snel']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Erfde de nasleep van de Paniek van 1837',
        'carrierre_voor_president' => 'Militair, gouverneur, senator',
        'carrierre_na_president' => 'Stierf in functie',
        'doodsoorzaak' => 'Longontsteking',
        'begrafenisplaats' => 'William Henry Harrison Tomb State Memorial, North Bend, Ohio',
        'historische_waardering' => 'Niet te beoordelen vanwege zijn korte termijn.',
        'controverses' => 'Campagne beeldde hem af als een eenvoudige man uit een blokhut, terwijl hij uit een rijke familie kwam.',
        'citaten' => json_encode([
            '"I contend that the strongest of all governments is that which is most free."'
        ]),
        'monumenten_ter_ere' => 'Harrison Tomb State Memorial, diverse standbeelden'
    ],

    // 10. John Tyler
    [
        'president_nummer' => 10,
        'naam' => 'John Tyler',
        'volledige_naam' => 'John Tyler',
        'bijnaam' => 'His Accidency',
        'partij' => 'Whig (later partijloos)',
        'periode_start' => '1841-04-04',
        'periode_eind' => '1845-03-04',
        'geboren' => '1790-03-29',
        'overleden' => '1862-01-18',
        'geboorteplaats' => 'Charles City County, Virginia',
        'vice_president' => 'Geen',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1d/John_Tyler.jpg',
        'biografie' => 'John Tyler was de tiende president en de eerste die het ambt bekleedde door opvolging na de dood van een president. Hij vestigde het precedent dat een vice-president volledig president wordt. Hij werd verstoten door zijn eigen partij, de Whigs.',
        'vroeg_leven' => 'Geboren in een aristocratische familie in Virginia. Studeerde rechten aan het College of William & Mary.',
        'politieke_carriere' => 'Lid Huis van Afgevaardigden, gouverneur van Virginia, U.S. Senator, vice-president.',
        'prestaties' => json_encode([
            'Vestigde het precedent van presidentiële opvolging ("Tyler Precedent")',
            'Annexeerde Texas in de laatste dagen van zijn presidentschap',
            'Ondertekende het Webster-Ashburton Verdrag, dat de grens met Canada vastlegde',
            'Hervormde de marine'
        ]),
        'fun_facts' => json_encode([
            'Had de meeste kinderen van alle presidenten: 15',
            'Werd verstoten door zijn eigen partij, de Whigs',
            'Eerste president wiens vrouw stierf terwijl hij in het Witte Huis was',
            'Hertrouwde als eerste president terwijl hij in functie was',
            'Na zijn presidentschap diende hij in het Geconfedereerde Congres tijdens de Burgeroorlog'
        ]),
        'echtgenote' => 'Letitia Christian Tyler, Julia Gardiner Tyler',
        'kinderen' => json_encode(['15 kinderen met twee echtgenotes']),
        'familie_connecties' => 'Geen directe presidentiële familie.',
        'lengte_cm' => 183,
        'gewicht_kg' => 73,
        'verkiezingsjaren' => json_encode(['Geen (opvolging)']),
        'leeftijd_bij_aantreden' => 51,
        'belangrijkste_gebeurtenissen' => 'Annexatie van Texas (1845), Webster-Ashburton Treaty (1842), Dorr Rebellion (1841-1842)',
        'bekende_speeches' => 'Address Upon Assuming the Office of President',
        'wetgeving' => json_encode(['Webster-Ashburton Treaty', 'Texas Annexation resolution']),
        'oorlogen' => json_encode(['Second Seminole War (beëindigd)']),
        'economische_situatie' => 'Gevetood Whig-pogingen om een nationale bank te herstellen, wat leidde tot politieke impasse',
        'carrierre_voor_president' => 'Advocaat, gouverneur, senator, vice-president',
        'carrierre_na_president' => 'Trok zich terug op zijn plantage; werd verkozen tot het Geconfedereerde Huis van Afgevaardigden',
        'doodsoorzaak' => 'Beroerte',
        'begrafenisplaats' => 'Hollywood Cemetery, Richmond, Virginia',
        'historische_waardering' => 'Lange tijd negatief beoordeeld vanwege zijn breuk met de Whigs, recentere waardering voor zijn krachtige opstelling inzake opvolging.',
        'controverses' => 'Zijn loyaliteit aan de Confederatie, zijn veto\'s tegen het Whig-programma, het bezit van slaven.',
        'citaten' => json_encode([
            '"I can never consent to being dictated to."'
        ]),
        'monumenten_ter_ere' => 'Zeer weinig; zijn graf in Richmond is een van de weinige herdenkingen.'
    ],

    // ... (And so on for all 46 presidents)
    // The data for the remaining presidents follows the same structure.
    // Due to the very large size of the full list, I'll provide the rest as a continuation.

    // 11. James K. Polk
    [
        'president_nummer' => 11,
        'naam' => 'James K. Polk',
        'volledige_naam' => 'James Knox Polk',
        'bijnaam' => 'Young Hickory',
        'partij' => 'Democratic',
        'periode_start' => '1845-03-04',
        'periode_eind' => '1849-03-04',
        'geboren' => '1795-11-02',
        'overleden' => '1849-06-15',
        'geboorteplaats' => 'Pineville, North Carolina',
        'vice_president' => 'George M. Dallas',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/f6/James_K_Polk_restored.jpg',
        'biografie' => 'James K. Polk was de elfde president, bekend om het aanzienlijk uitbreiden van het Amerikaanse grondgebied door de annexatie van Texas, Oregon en het winnen van de Mexicaans-Amerikaanse Oorlog.',
        'vroeg_leven' => 'Groeide op in Tennessee, studeerde rechten, en werd een succesvol politicus en bondgenoot van Andrew Jackson.',
        'politieke_carriere' => 'Tennessee legislature, U.S. House of Representatives (inclusief Spreker van het Huis), Gouverneur van Tennessee.',
        'prestaties' => json_encode([
            'Leidde de VS naar de overwinning in de Mexicaans-Amerikaanse Oorlog',
            'Verwierf Californië, New Mexico en de rest van het Zuidwesten',
            'Verwierf het Oregon-territorium',
            'Verlaagde tarieven met de Walker Tariff',
            'Richtte een onafhankelijk schatkistsysteem op'
        ]),
        'fun_facts' => json_encode([
            'Wordt beschouwd als de meest succesvolle een-termijn president',
            'Hield zijn belofte om slechts één termijn te dienen',
            'Was de eerste "dark horse" kandidaat (onverwachte genomineerde)',
            'Werkte extreem hard en had weinig sociaal leven als president',
            'Stierf slechts 3 maanden na het verlaten van zijn ambt'
        ]),
        'echtgenote' => 'Sarah Childress Polk',
        'kinderen' => json_encode(['Geen']),
        'familie_connecties' => 'Geen directe presidentiële familie.',
        'lengte_cm' => 173,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1844]),
        'leeftijd_bij_aantreden' => 49,
        'belangrijkste_gebeurtenissen' => 'Mexicaans-Amerikaanse Oorlog (1846-1848), Oregon Treaty (1846), Verdrag van Guadalupe Hidalgo (1848)',
        'bekende_speeches' => 'War Message to Congress (1846)',
        'wetgeving' => json_encode(['Walker Tariff of 1846', 'Independent Treasury Act of 1846', 'Treaty of Guadalupe Hidalgo']),
        'oorlogen' => json_encode(['Mexicaans-Amerikaanse Oorlog']),
        'economische_situatie' => 'Stabiele groei, bevorderd door lage tarieven en territoriale expansie',
        'carrierre_voor_president' => 'Advocaat, Spreker van het Huis, Gouverneur van Tennessee',
        'carrierre_na_president' => 'Stierf kort na zijn pensionering',
        'doodsoorzaak' => 'Cholera',
        'begrafenisplaats' => 'Tennessee State Capitol, Nashville, Tennessee',
        'historische_waardering' => 'Zeer hoog beoordeeld voor het bereiken van al zijn verkiezingsdoelen en territoriale uitbreiding.',
        'controverses' => 'Het uitlokken van de oorlog met Mexico, de uitbreiding van de slavernij in nieuwe territoria.',
        'citaten' => json_encode([
            '"The passion for power is the most ardent of all the passions."'
        ]),
        'monumenten_ter_ere' => 'Polk Home and Museum, diverse counties en steden'
    ],

    // 12. Zachary Taylor
    [
        'president_nummer' => 12,
        'naam' => 'Zachary Taylor',
        'volledige_naam' => 'Zachary Taylor',
        'bijnaam' => 'Old Rough and Ready',
        'partij' => 'Whig',
        'periode_start' => '1849-03-04',
        'periode_eind' => '1850-07-09',
        'geboren' => '1784-11-24',
        'overleden' => '1850-07-09',
        'geboorteplaats' => 'Barboursville, Virginia',
        'vice_president' => 'Millard Fillmore',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Zachary_Taylor_restored_and_colorized.jpg',
        'biografie' => 'Zachary Taylor was de twaalfde president en een nationale held vanwege zijn militaire successen in de Mexicaans-Amerikaanse Oorlog. Hij stierf na slechts 16 maanden in functie.',
        'vroeg_leven' => 'Groeide op aan de grens in Kentucky en had een 40-jarige militaire carrière.',
        'politieke_carriere' => 'Geen; hij had nog nooit gestemd of een politiek ambt bekleed voor zijn presidentschap.',
        'prestaties' => json_encode([
            'Probeerde een middenweg te vinden in het slavernijdebat',
            'Onderhandelde over het Clayton-Bulwer Verdrag met Groot-Brittannië over een mogelijk kanaal in Centraal-Amerika'
        ]),
        'fun_facts' => json_encode([
            'Hield slaven, maar verzette zich tegen de uitbreiding van slavernij in nieuwe territoria',
            'Zijn paard, "Old Whitey", graasde vaak op het gazon van het Witte Huis',
            'Zijn lichaam werd in 1991 opgegraven om te testen op arseenvergiftiging (resultaat was negatief)'
        ]),
        'echtgenote' => 'Margaret Mackall Smith Taylor',
        'kinderen' => json_encode(['Zijn dochter Sarah Knox Taylor was de eerste vrouw van Jefferson Davis, de toekomstige president van de Confederatie']),
        'familie_connecties' => 'Verre neef van James Madison.',
        'lengte_cm' => 173,
        'gewicht_kg' => 77,
        'verkiezingsjaren' => json_encode([1848]),
        'leeftijd_bij_aantreden' => 64,
        'belangrijkste_gebeurtenissen' => 'California Gold Rush, debat over het Compromis van 1850',
        'bekende_speeches' => 'Geen grote speeches; stond bekend om zijn eenvoudige, directe stijl.',
        'wetgeving' => json_encode(['Clayton-Bulwer Treaty (1850)']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Goudkoorts in Californië stimuleerde de economie',
        'carrierre_voor_president' => 'Generaal-majoor in het Amerikaanse leger',
        'carrierre_na_president' => 'Stierf in functie',
        'doodsoorzaak' => 'Acute gastroenteritis',
        'begrafenisplaats' => 'Zachary Taylor National Cemetery, Louisville, Kentucky',
        'historische_waardering' => 'Moeilijk te beoordelen vanwege zijn korte termijn; wordt gezien als een man met principes die het slavernijdebat mogelijk anders had gestuurd.',
        'controverses' => 'Zijn plotselinge dood leidde tot complottheorieën over vergiftiging.',
        'citaten' => json_encode([
            '"I have always done my duty. I am ready to die. My only regret is for the friends I leave behind me."'
        ]),
        'monumenten_ter_ere' => 'Zachary Taylor National Cemetery'
    ],
    
    // ... Voeg presidenten 13 t/m 46 hier toe
    // 13. Millard Fillmore
    [
        'president_nummer' => 13,
        'naam' => 'Millard Fillmore',
        'volledige_naam' => 'Millard Fillmore',
        'bijnaam' => 'The American Louis Philippe',
        'partij' => 'Whig',
        'periode_start' => '1850-07-09',
        'periode_eind' => '1853-03-04',
        'geboren' => '1800-01-07',
        'overleden' => '1874-03-08',
        'geboorteplaats' => 'Locke, New York',
        'vice_president' => 'Geen',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Millard_Fillmore_-_crop.jpg',
        'biografie' => 'Millard Fillmore was de dertiende president en de laatste Whig-president. Hij nam het ambt over na de dood van Zachary Taylor en steunde het Compromis van 1850, wat de Burgeroorlog tijdelijk uitstelde.',
        'vroeg_leven' => 'Geboren in armoede in een blokhut in de staat New York, was grotendeels autodidact.',
        'politieke_carriere' => 'New York State Assembly, U.S. House of Representatives, Comptroller of New York, vice-president.',
        'prestaties' => json_encode([
            'Ondertekende het Compromis van 1850',
            'Stuurde Commodore Perry op een missie om de handel met Japan te openen',
            'Voorkwam een conflict met Spanje over Cuba'
        ]),
        'fun_facts' => json_encode([
            'Installeerde de eerste bibliotheek in het Witte Huis',
            'Installeerde het eerste bad met stromend water in het Witte Huis',
            'Na zijn presidentschap liep hij voor het ambt op het Know-Nothing partijticket'
        ]),
        'echtgenote' => 'Abigail Powers Fillmore, Caroline Carmichael McIntosh',
        'kinderen' => json_encode(['Millard Powers Fillmore', 'Mary Abigail Fillmore']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 175,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode(['Geen (opvolging)']),
        'leeftijd_bij_aantreden' => 50,
        'belangrijkste_gebeurtenissen' => 'Compromis van 1850, Perry Expeditie naar Japan',
        'bekende_speeches' => 'State of the Union toespraken.',
        'wetgeving' => json_encode(['Compromise of 1850', 'Fugitive Slave Act of 1850']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Relatieve welvaart en groei.',
        'carrierre_voor_president' => 'Advocaat, politicus, vice-president.',
        'carrierre_na_president' => 'Kanselier van de Universiteit van Buffalo, stelde zich kandidaat voor president in 1856.',
        'doodsoorzaak' => 'Beroerte',
        'begrafenisplaats' => 'Forest Lawn Cemetery, Buffalo, New York',
        'historische_waardering' => 'Over het algemeen negatief beoordeeld, voornamelijk vanwege de Fugitive Slave Act.',
        'controverses' => 'De Fugitive Slave Act, die vereiste dat noordelingen ontsnapte slaven moesten terugbrengen, was zeer impopulair in het Noorden.',
        'citaten' => json_encode([
            '"The law is the only sure protection of the weak, and the only efficient restraint upon the strong."'
        ]),
        'monumenten_ter_ere' => 'Millard Fillmore Hospital, standbeeld in Buffalo'
    ],
    
    // 14. Franklin Pierce
    [
        'president_nummer' => 14,
        'naam' => 'Franklin Pierce',
        'volledige_naam' => 'Franklin Pierce',
        'bijnaam' => 'Young Hickory of the Granite Hills',
        'partij' => 'Democratic',
        'periode_start' => '1853-03-04',
        'periode_eind' => '1857-03-04',
        'geboren' => '1804-11-23',
        'overleden' => '1869-10-08',
        'geboorteplaats' => 'Hillsborough, New Hampshire',
        'vice_president' => 'William R. King',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/e5/Franklin_Pierce_-_Brady-Handy.jpg',
        'biografie' => 'Franklin Pierce was de veertiende president. Zijn presidentschap werd gekenmerkt door persoonlijke tragedie en politieke onrust die de natie dichter bij de Burgeroorlog bracht, met name door de Kansas-Nebraska Act.',
        'vroeg_leven' => 'Zoon van een gouverneur van New Hampshire. Studeerde rechten en diende in de Mexicaans-Amerikaanse Oorlog.',
        'politieke_carriere' => 'New Hampshire legislature, U.S. House of Representatives, U.S. Senator.',
        'prestaties' => json_encode([
            'Gadsden Purchase: verwierf land van Mexico voor een zuidelijke transcontinentale spoorweg',
            'Opende handelsrelaties met Japan via het Verdrag van Kanagawa'
        ]),
        'fun_facts' => json_encode([
            'Zijn vice-president, William R. King, werd beëdigd in Cuba en stierf kort daarna, en diende nooit in Washington',
            'Alle drie zijn kinderen stierven jong; de laatste kwam om bij een tragisch treinongeluk vlak voor zijn inauguratie',
            'Werd gearresteerd tijdens zijn presidentschap voor het overrijden van een oude vrouw met zijn paard (aanklacht werd ingetrokken)'
        ]),
        'echtgenote' => 'Jane Means Appleton Pierce',
        'kinderen' => json_encode(['Drie zonen, allen stierven in de kindertijd']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 178,
        'gewicht_kg' => 70,
        'verkiezingsjaren' => json_encode([1852]),
        'leeftijd_bij_aantreden' => 48,
        'belangrijkste_gebeurtenissen' => 'Kansas-Nebraska Act (1854), Gadsden Purchase (1853), Bleeding Kansas',
        'bekende_speeches' => 'Inaugural Address',
        'wetgeving' => json_encode(['Kansas-Nebraska Act of 1854']),
        'oorlogen' => json_encode(['Geen grote oorlogen, maar geweld in "Bleeding Kansas"']),
        'economische_situatie' => 'Algemene welvaart, maar toenemende sectionele spanningen.',
        'carrierre_voor_president' => 'Advocaat, politicus, generaal.',
        'carrierre_na_president' => 'Reisde door Europa, werd een uitgesproken criticus van Abraham Lincoln.',
        'doodsoorzaak' => 'Cirrose van de lever',
        'begrafenisplaats' => 'Old North Cemetery, Concord, New Hampshire',
        'historische_waardering' => 'Wordt consequent gerangschikt als een van de slechtste presidenten, voornamelijk vanwege de desastreuze Kansas-Nebraska Act.',
        'controverses' => 'Kansas-Nebraska Act die het Missouri Compromis herriep en leidde tot geweld, zijn sympathie voor het Zuiden.',
        'citaten' => json_encode([
            '"You have summoned me in my weakness. You must sustain me by your strength."'
        ]),
        'monumenten_ter_ere' => 'Franklin Pierce University, een enkel standbeeld in Concord, NH'
    ],
    
    // 15. James Buchanan
    [
        'president_nummer' => 15,
        'naam' => 'James Buchanan',
        'volledige_naam' => 'James Buchanan Jr.',
        'bijnaam' => 'Old Buck',
        'partij' => 'Democratic',
        'periode_start' => '1857-03-04',
        'periode_eind' => '1861-03-04',
        'geboren' => '1791-04-23',
        'overleden' => '1868-06-01',
        'geboorteplaats' => 'Cove Gap, Pennsylvania',
        'vice_president' => 'John C. Breckinridge',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/fd/James_Buchanan_by_Mathew_Brady_1850s.jpg',
        'biografie' => 'James Buchanan was de vijftiende president. Hij was de president in de aanloop naar de Burgeroorlog en zijn passieve houding tegenover de afscheiding van zuidelijke staten wordt vaak bekritiseerd.',
        'vroeg_leven' => 'Geboren in een blokhut, werd een succesvolle advocaat en investeerder.',
        'politieke_carriere' => 'Pennsylvania legislature, U.S. House of Representatives, Minister in Rusland, U.S. Senator, Secretary of State, Minister in het Verenigd Koninkrijk.',
        'prestaties' => json_encode([
            'Hield toezicht op de toelating van drie staten tot de Unie (Minnesota, Oregon, Kansas)'
        ]),
        'fun_facts' => json_encode([
            'De enige president die nooit getrouwd is geweest (bachelor)',
            'Zijn nicht, Harriet Lane, fungeerde als First Lady',
            'Hij kocht slaven in Washington D.C. en bevrijdde ze stilletjes in Pennsylvania'
        ]),
        'echtgenote' => 'Nooit getrouwd',
        'kinderen' => json_encode(['Geen']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 183,
        'gewicht_kg' => 91,
        'verkiezingsjaren' => json_encode([1856]),
        'leeftijd_bij_aantreden' => 65,
        'belangrijkste_gebeurtenissen' => 'Dred Scott v. Sandford Supreme Court beslissing (1857), Paniek van 1857, John Brown\'s raid op Harpers Ferry (1859), Afscheiding van zeven zuidelijke staten.',
        'bekende_speeches' => 'Inaugural Address',
        'wetgeving' => json_encode(['Geen belangrijke wetgeving; politieke impasse']),
        'oorlogen' => json_encode(['Utah War (1857-1858)']),
        'economische_situatie' => 'De Paniek van 1857 veroorzaakte een scherpe economische neergang.',
        'carrierre_voor_president' => 'Uitgebreide carrière als advocaat, diplomaat en politicus.',
        'carrierre_na_president' => 'Trok zich terug op zijn landgoed Wheatland en schreef zijn memoires.',
        'doodsoorzaak' => 'Ademhalingsfalen en reumatische jicht',
        'begrafenisplaats' => 'Woodward Hill Cemetery, Lancaster, Pennsylvania',
        'historische_waardering' => 'Wordt vrijwel unaniem beschouwd als een van de slechtste, zo niet de slechtste, presidenten vanwege zijn onvermogen om de crisis van de afscheiding aan te pakken.',
        'controverses' => 'Zijn steun aan de pro-slavernij Lecompton Grondwet voor Kansas, zijn passiviteit toen zuidelijke staten zich afscheidden.',
        'citaten' => json_encode([
            '"I am the last President of the United States."'
        ]),
        'monumenten_ter_ere' => 'Standbeeld in Washington D.C., zijn landgoed Wheatland is een museum.'
    ],
    
    // 16. Abraham Lincoln
    [
        'president_nummer' => 16,
        'naam' => 'Abraham Lincoln',
        'volledige_naam' => 'Abraham Lincoln',
        'bijnaam' => 'Honest Abe, The Great Emancipator',
        'partij' => 'Republican',
        'periode_start' => '1861-03-04',
        'periode_eind' => '1865-04-15',
        'geboren' => '1809-02-12',
        'overleden' => '1865-04-15',
        'geboorteplaats' => 'Hodgenville, Kentucky',
        'vice_president' => 'Hannibal Hamlin (1861-1865), Andrew Johnson (1865)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/ab/Abraham_Lincoln_O-77_matte_collodion_print.jpg',
        'biografie' => 'Abraham Lincoln was de zestiende president. Hij leidde de Verenigde Staten door de Burgeroorlog, behield de Unie, schafte de slavernij af en versterkte de federale overheid.',
        'vroeg_leven' => 'Geboren in armoede in een blokhut, was grotendeels autodidact. Werkte als bootsman, winkelbediende en postmeester voordat hij advocaat werd.',
        'politieke_carriere' => 'Illinois legislature, U.S. House of Representatives.',
        'prestaties' => json_encode([
            'Leidde de Unie naar de overwinning in de Amerikaanse Burgeroorlog',
            'Vaardigde de Emancipatieproclamatie uit, die slaven in de opstandige staten bevrijdde',
            'Drong aan op de passage van het 13e Amendement, dat slavernij afschafte',
            'Hield de Gettysburg Address, een van de beroemdste toespraken in de Amerikaanse geschiedenis'
        ]),
        'fun_facts' => json_encode([
            'Is de enige president die een patent bezit (voor een apparaat om boten over zandbanken te tillen)',
            'Was een volleerd worstelaar in zijn jeugd (verloor slechts één van de circa 300 wedstrijden)',
            'Creëerde de Secret Service op de dag dat hij werd vermoord',
            'Langste president met 193 cm'
        ]),
        'echtgenote' => 'Mary Todd Lincoln',
        'kinderen' => json_encode(['Robert Todd Lincoln', 'Edward Baker Lincoln', 'William Wallace Lincoln', 'Tad Lincoln']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 193,
        'gewicht_kg' => 82,
        'verkiezingsjaren' => json_encode([1860, 1864]),
        'leeftijd_bij_aantreden' => 52,
        'belangrijkste_gebeurtenissen' => 'Amerikaanse Burgeroorlog (1861-1865), Emancipatieproclamatie (1863), Slag bij Gettysburg (1863), zijn moord (1865).',
        'bekende_speeches' => 'Gettysburg Address, Tweede Inaugurele Rede ("with malice toward none, with charity for all").',
        'wetgeving' => json_encode(['Emancipation Proclamation', 'Homestead Act', 'Morrill Land-Grant Act', '13th Amendment to the Constitution']),
        'oorlogen' => json_encode(['Amerikaanse Burgeroorlog']),
        'economische_situatie' => 'Oorlogseconomie gefinancierd door de eerste inkomstenbelasting en de uitgifte van "greenbacks".',
        'carrierre_voor_president' => 'Advocaat, politicus.',
        'carrierre_na_president' => 'Vermoord in functie.',
        'doodsoorzaak' => 'Moord (neergeschoten door John Wilkes Booth)',
        'begrafenisplaats' => 'Lincoln Tomb, Oak Ridge Cemetery, Springfield, Illinois',
        'historische_waardering' => 'Wordt consistent gerangschikt als een van de drie beste presidenten, vaak als de beste, vanwege zijn leiderschap tijdens de Burgeroorlog en de afschaffing van de slavernij.',
        'controverses' => 'Opschorting van habeas corpus tijdens de oorlog, zijn aanvankelijke aarzeling om van de afschaffing van slavernij een oorlogsdoel te maken.',
        'citaten' => json_encode([
            '"A house divided against itself cannot stand."',
            '"Government of the people, by the people, for the people, shall not perish from the Earth."'
        ]),
        'monumenten_ter_ere' => 'Lincoln Memorial, Mount Rushmore, Lincoln, Nebraska; ontelbare standbeelden, scholen en straten.'
    ],
    
    // 17. Andrew Johnson
    [
        'president_nummer' => 17,
        'naam' => 'Andrew Johnson',
        'volledige_naam' => 'Andrew Johnson',
        'bijnaam' => 'The Tennessee Tailor',
        'partij' => 'National Union (formeel Democratic)',
        'periode_start' => '1865-04-15',
        'periode_eind' => '1869-03-04',
        'geboren' => '1808-12-29',
        'overleden' => '1875-07-31',
        'geboorteplaats' => 'Raleigh, North Carolina',
        'vice_president' => 'Geen',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/e6/Andrew_Johnson_photo_portrait_head_and_shoulders%2C_c1860-1870.jpg',
        'biografie' => 'Andrew Johnson was de zeventiende president, die aantrad na de moord op Lincoln. Zijn presidentschap werd gedomineerd door de Reconstructie na de Burgeroorlog en zijn conflicten met het Republikeinse Congres leidden tot zijn afzetting (impeachment).',
        'vroeg_leven' => 'Geboren in armoede, werkte als kleermaker. Leer pas lezen en schrijven op volwassen leeftijd, met hulp van zijn vrouw.',
        'politieke_carriere' => 'Wethouder, burgemeester, staatsvertegenwoordiger, staatssenator, U.S. Congressman, gouverneur van Tennessee, U.S. Senator, militair gouverneur van Tennessee, vice-president.',
        'prestaties' => json_encode([
            'Aankoop van Alaska van Rusland ("Seward\'s Folly") in 1867'
        ]),
        'fun_facts' => json_encode([
            'Eerste president die werd afgezet (impeached) door het Huis van Afgevaardigden (vrijgesproken door de Senaat met één stem verschil)',
            'Hield zijn eigen koeien op het gazon van het Witte Huis',
            'Na zijn presidentschap werd hij opnieuw verkozen tot de Senaat, als enige ex-president'
        ]),
        'echtgenote' => 'Eliza McCardle Johnson',
        'kinderen' => json_encode(['Martha', 'Charles', 'Mary', 'Robert', 'Andrew Jr.']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 178,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode(['Geen (opvolging)']),
        'leeftijd_bij_aantreden' => 56,
        'belangrijkste_gebeurtenissen' => 'Reconstruction, Impeachment (1868), Aankoop van Alaska (1867), Passage van de 14e en 15e amendementen (ondanks zijn tegenstand).',
        'bekende_speeches' => 'Hield vaak geïmproviseerde, strijdlustige toespraken.',
        'wetgeving' => json_encode(['Veto op de Civil Rights Act van 1866 en de Freedmen\'s Bureau bills (beide veto\'s werden overruled)']),
        'oorlogen' => json_encode(['Indian Wars (voortgezet)']),
        'economische_situatie' => 'Heropbouw van de Zuidelijke economie, industriële groei in het Noorden.',
        'carrierre_voor_president' => 'Zeer uitgebreide politieke carrière op bijna elk niveau.',
        'carrierre_na_president' => 'Keerde terug naar Tennessee en werd in 1875 opnieuw tot U.S. Senator gekozen.',
        'doodsoorzaak' => 'Beroerte',
        'begrafenisplaats' => 'Andrew Johnson National Cemetery, Greeneville, Tennessee',
        'historische_waardering' => 'Consequent beoordeeld als een van de slechtste presidenten, vanwege zijn obstructie van de Reconstructie en zijn racistische opvattingen.',
        'controverses' => 'Zijn afzettingsprocedure, zijn veto\'s op burgerrechtenwetgeving, zijn milde behandeling van de voormalige Geconfedereerde staten, zijn openbare ruzies met het Congres.',
        'citaten' => json_encode([
            '"Honest conviction is my courage; the Constitution is my guide."'
        ]),
        'monumenten_ter_ere' => 'Andrew Johnson National Historic Site'
    ],
    
    // 18. Ulysses S. Grant
    [
        'president_nummer' => 18,
        'naam' => 'Ulysses S. Grant',
        'volledige_naam' => 'Hiram Ulysses Grant',
        'bijnaam' => 'Unconditional Surrender Grant',
        'partij' => 'Republican',
        'periode_start' => '1869-03-04',
        'periode_eind' => '1877-03-04',
        'geboren' => '1822-04-27',
        'overleden' => '1885-07-23',
        'geboorteplaats' => 'Point Pleasant, Ohio',
        'vice_president' => 'Schuyler Colfax (1869-1873), Henry Wilson (1873-1875)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Ulysses_S._Grant_by_Mathew_Brady%2C_c._1870-1875.jpg',
        'biografie' => 'Ulysses S. Grant was de achttiende president en de bevelhebbende generaal die de Unie naar de overwinning leidde in de Burgeroorlog. Als president werkte hij aan de Reconstructie en probeerde hij de rechten van Afro-Amerikanen te beschermen.',
        'vroeg_leven' => 'Zoon van een leerlooier. Studeerde af aan West Point en diende in de Mexicaans-Amerikaanse Oorlog. Verliet het leger en had moeite in het burgerleven.',
        'politieke_carriere' => 'Geen politieke ervaring voor het presidentschap, behalve zijn rol als bevelhebbend generaal van het leger.',
        'prestaties' => json_encode([
            'Leidde het leger naar de overwinning in de Burgeroorlog',
            'Vernietigde de Ku Klux Klan met de Force Acts',
            'Ondertekende de Civil Rights Act van 1875',
            'Richtte Yellowstone National Park op, het eerste nationale park ter wereld'
        ]),
        'fun_facts' => json_encode([
            'Kreeg een boete van $20 voor te snel rijden met zijn paard en wagen in Washington D.C.',
            'Zijn echte naam was Hiram Ulysses Grant; "Ulysses S. Grant" was een fout van zijn congreslid bij zijn aanmelding voor West Point.',
            'Kon zijn eigen gewicht in voedsel niet verdragen, vooral geen bloed',
            'Rookte meer dan 20 sigaren per dag'
        ]),
        'echtgenote' => 'Julia Dent Grant',
        'kinderen' => json_encode(['Frederick', 'Ulysses Jr.', 'Nellie', 'Jesse']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 173,
        'gewicht_kg' => 61,
        'verkiezingsjaren' => json_encode([1868, 1872]),
        'leeftijd_bij_aantreden' => 46,
        'belangrijkste_gebeurtenissen' => 'Voltooiing van de Transcontinental Railroad (1869), Paniek van 1873, Battle of the Little Bighorn (1876), Corruptie schandalen.',
        'bekende_speeches' => 'First Inaugural Address: "Let us have peace."',
        'wetgeving' => json_encode(['Force Acts (Ku Klux Klan Act)', 'Civil Rights Act of 1875', 'Specie Payment Resumption Act']),
        'oorlogen' => json_encode(['Great Sioux War of 1876']),
        'economische_situatie' => 'Gekenmerkt door de Paniek van 1873, een ernstige economische depressie.',
        'carrierre_voor_president' => 'Militair officier, bevelhebbend generaal.',
        'carrierre_na_president' => 'Maakte een wereldtournee, ging failliet en schreef zijn succesvolle memoires om zijn gezin te onderhouden.',
        'doodsoorzaak' => 'Keelkanker',
        'begrafenisplaats' => 'General Grant National Memorial (Grant\'s Tomb), New York, New York',
        'historische_waardering' => 'Zijn reputatie is sterk verbeterd; hij wordt nu geprezen voor zijn inzet voor burgerrechten, hoewel zijn presidentschap ontsierd werd door corruptie in zijn administratie.',
        'controverses' => 'Talloze corruptieschandalen in zijn regering (hoewel Grant zelf niet direct betrokken was), zoals het Whiskey Ring-schandaal.',
        'citaten' => json_encode([
            '"The friend in my adversity I shall always cherish most."',
            '"I know only two tunes: one of them is \'Yankee Doodle\', and the other isn\'t."'
        ]),
        'monumenten_ter_ere' => 'Grant\'s Tomb in New York, Ulysses S. Grant Memorial bij het Capitool, staat op het $50 biljet.'
    ],

    // 19. Rutherford B. Hayes
    [
        'president_nummer' => 19,
        'naam' => 'Rutherford B. Hayes',
        'volledige_naam' => 'Rutherford Birchard Hayes',
        'bijnaam' => 'Rutherfraud',
        'partij' => 'Republican',
        'periode_start' => '1877-03-04',
        'periode_eind' => '1881-03-04',
        'geboren' => '1822-10-04',
        'overleden' => '1893-01-17',
        'geboorteplaats' => 'Delaware, Ohio',
        'vice_president' => 'William A. Wheeler',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/Rutherford_B_Hayes_by_Mathew_Brady_1870-1880.jpg',
        'biografie' => 'Rutherford B. Hayes was de negentiende president. Hij werd president na de meest omstreden verkiezing in de Amerikaanse geschiedenis en hield toezicht op het einde van de Reconstructie.',
        'vroeg_leven' => 'Studeerde aan Kenyon College en Harvard Law School. Diende als generaal in de Burgeroorlog.',
        'politieke_carriere' => 'U.S. House of Representatives, Gouverneur van Ohio.',
        'prestaties' => json_encode([
            'Beëindigde de Reconstructie door federale troepen uit het Zuiden terug te trekken',
            'Hervormde het ambtenarenapparaat (civil service reform)',
            'Ging om met de Grote Spoorwegstaking van 1877'
        ]),
        'fun_facts' => json_encode([
            'Won de verkiezingen ondanks het verlies van de populaire stem',
            'Zijn vrouw, Lucy, stond bekend als "Lemonade Lucy" omdat ze alcohol verbood in het Witte Huis',
            'Hield de eerste Paaseierenrol op het gazon van het Witte Huis',
            'Installeerde de eerste telefoon in het Witte Huis'
        ]),
        'echtgenote' => 'Lucy Webb Hayes',
        'kinderen' => json_encode(['Acht kinderen']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 174,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1876]),
        'leeftijd_bij_aantreden' => 54,
        'belangrijkste_gebeurtenissen' => 'Compromis van 1877, Grote Spoorwegstaking van 1877, Einde van de Reconstructie.',
        'bekende_speeches' => 'Inaugural Address',
        'wetgeving' => json_encode(['Bland-Allison Act (overruled zijn veto)', 'Desert Land Act']),
        'oorlogen' => json_encode(['Nez Perce War (1877)']),
        'economische_situatie' => 'Herstel van de Paniek van 1873.',
        'carrierre_voor_president' => 'Advocaat, generaal, gouverneur.',
        'carrierre_na_president' => 'Werd een pleitbezorger voor onderwijs- en gevangenishervormingen.',
        'doodsoorzaak' => 'Hartfalen',
        'begrafenisplaats' => 'Spiegel Grove, Fremont, Ohio',
        'historische_waardering' => 'Gemengd; geprezen voor zijn integriteit en inzet voor hervormingen, maar bekritiseerd voor het beëindigen van de Reconstructie, wat leidde tot de onderdrukking van Afro-Amerikanen in het Zuiden.',
        'controverses' => 'De verkiezing van 1876, die werd beslist door een partijdige commissie en resulteerde in het Compromis van 1877.',
        'citaten' => json_encode([
            '"He serves his party best who serves the country best."'
        ]),
        'monumenten_ter_ere' => 'Rutherford B. Hayes Presidential Library & Museums'
    ],

    // 20. James A. Garfield
    [
        'president_nummer' => 20,
        'naam' => 'James A. Garfield',
        'volledige_naam' => 'James Abram Garfield',
        'bijnaam' => 'The Preacher President',
        'partij' => 'Republican',
        'periode_start' => '1881-03-04',
        'periode_eind' => '1881-09-19',
        'geboren' => '1831-11-19',
        'overleden' => '1881-09-19',
        'geboorteplaats' => 'Moreland Hills, Ohio',
        'vice_president' => 'Chester A. Arthur',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1f/James_Abram_Garfield%2C_photo_portrait_seated.jpg',
        'biografie' => 'James A. Garfield was de twintigste president. Zijn presidentschap duurde slechts 200 dagen voordat hij werd neergeschoten door een ontevreden ambtenzoeker. Zijn dood leidde tot hervormingen van het ambtenarenapparaat.',
        'vroeg_leven' => 'Laatste president geboren in een blokhut. Werkte op kanaalboten om zijn opleiding te betalen. Werd college president.',
        'politieke_carriere' => 'Ohio State Senator, generaal-majoor in de Burgeroorlog, U.S. House of Representatives.',
        'prestaties' => json_encode([
            'Startte hervormingen van het postkantoor en de ambtenarij',
            'Daagde de machtige senator Roscoe Conkling uit en won, waarmee hij de presidentiële autoriteit versterkte'
        ]),
        'fun_facts' => json_encode([
            'Was ambidexter en kon tegelijkertijd in het Grieks met de ene hand en in het Latijn met de andere hand schrijven',
            'Wist een wiskundig bewijs voor de stelling van Pythagoras te ontwikkelen',
            'Tweede president die werd vermoord'
        ]),
        'echtgenote' => 'Lucretia Rudolph Garfield',
        'kinderen' => json_encode(['Zeven kinderen']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 183,
        'gewicht_kg' => 84,
        'verkiezingsjaren' => json_encode([1880]),
        'leeftijd_bij_aantreden' => 49,
        'belangrijkste_gebeurtenissen' => 'Zijn moord door Charles J. Guiteau.',
        'bekende_speeches' => 'Inaugural Address',
        'wetgeving' => json_encode(['Geen belangrijke wetgeving vanwege zijn korte termijn']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Stabiel en groeiend.',
        'carrierre_voor_president' => 'College president, generaal, congreslid.',
        'carrierre_na_president' => 'Vermoor in functie.',
        'doodsoorzaak' => 'Moord (infectie en bloeding van de schotwond)',
        'begrafenisplaats' => 'James A. Garfield Memorial, Lake View Cemetery, Cleveland, Ohio',
        'historische_waardering' => 'Moeilijk te beoordelen, maar wordt gezien als een man met groot potentieel wiens dood de aanzet gaf tot cruciale hervormingen.',
        'controverses' => 'Betrokkenheid bij het Crédit Mobilier-schandaal als congreslid (hoewel zijn rol onduidelijk is).',
        'citaten' => json_encode([
            '"The truth will set you free, but first it will make you miserable."'
        ]),
        'monumenten_ter_ere' => 'Garfield Memorial in Cleveland, diverse standbeelden'
    ],

    // 21. Chester A. Arthur
    [
        'president_nummer' => 21,
        'naam' => 'Chester A. Arthur',
        'volledige_naam' => 'Chester Alan Arthur',
        'bijnaam' => 'The Gentleman Boss',
        'partij' => 'Republican',
        'periode_start' => '1881-09-20',
        'periode_eind' => '1885-03-04',
        'geboren' => '1829-10-05',
        'overleden' => '1886-11-18',
        'geboorteplaats' => 'Fairfield, Vermont',
        'vice_president' => 'Geen',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/7/79/Chester_A._Arthur_by_Ole_Peter_Hansen_Balling.JPG',
        'biografie' => 'Chester A. Arthur was de eenentwintigste president, die aantrad na de moord op Garfield. Hoewel hij zijn carrière begon als product van het politieke patronage-systeem, werd hij als president een onverwachte voorvechter van ambtenarenhervorming.',
        'vroeg_leven' => 'Zoon van een baptistenpredikant. Werd advocaat in New York en was actief in de Republikeinse partijmachine.',
        'politieke_carriere' => 'Quartermaster General van New York, Collector of the Port of New York, vice-president.',
        'prestaties' => json_encode([
            'Ondertekende de Pendleton Civil Service Reform Act, die het einde betekende van het "spoils system"',
            'Moderniseerde de Amerikaanse marine',
            'Veto op het controversiële Rivers and Harbors Act'
        ]),
        'fun_facts' => json_encode([
            'Stond bekend om zijn extravagante kledingstijl en bezat naar verluidt 80 broeken',
            'Huurde Louis Comfort Tiffany in om het Witte Huis te herinrichten',
            'Vernietigde bijna al zijn persoonlijke en officiële papieren voor zijn dood'
        ]),
        'echtgenote' => 'Ellen Lewis Herndon Arthur',
        'kinderen' => json_encode(['Chester Alan Arthur II', 'Ellen Herndon Arthur']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 188,
        'gewicht_kg' => 102,
        'verkiezingsjaren' => json_encode(['Geen (opvolging)']),
        'leeftijd_bij_aantreden' => 51,
        'belangrijkste_gebeurtenissen' => 'Pendleton Civil Service Reform Act (1883), Chinese Exclusion Act (1882).',
        'bekende_speeches' => 'State of the Union toespraken.',
        'wetgeving' => json_encode(['Pendleton Act', 'Chinese Exclusion Act', 'Edmunds Act']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Welvarend, met een groot overschot op de begroting.',
        'carrierre_voor_president' => 'Advocaat, politiek bestuurder, vice-president.',
        'carrierre_na_president' => 'Keerde terug naar New York en stierf een jaar later.',
        'doodsoorzaak' => 'Beroerte (als gevolg van de ziekte van Bright, een nierziekte)',
        'begrafenisplaats' => 'Albany Rural Cemetery, Menands, New York',
        'historische_waardering' => 'Zijn reputatie is verbeterd; hij wordt nu gezien als een competente president die boven zijn partijdige afkomst uitsteeg om het algemeen belang te dienen.',
        'controverses' => 'Geruchten dat hij niet in de VS was geboren (en dus ongeschikt was voor het presidentschap), zijn eerdere rol in het patronage-systeem.',
        'citaten' => json_encode([
            '"I may be president of the United States, but my private life is nobody\'s damned business."'
        ]),
        'monumenten_ter_ere' => 'Standbeeld in Madison Square, New York'
    ],

    // 22 & 24. Grover Cleveland
    [
        'president_nummer' => 22, // Wordt ook #24
        'naam' => 'Grover Cleveland',
        'volledige_naam' => 'Stephen Grover Cleveland',
        'bijnaam' => 'Big Steve',
        'partij' => 'Democratic',
        'periode_start' => '1885-03-04',
        'periode_eind' => '1889-03-04', // Eerste termijn
        'geboren' => '1837-03-18',
        'overleden' => '1908-06-24',
        'geboorteplaats' => 'Caldwell, New Jersey',
        'vice_president' => 'Thomas A. Hendricks (1885)', // Eerste termijn
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/f3/Grover_Cleveland_-_NARA_-_518139.jpg',
        'biografie' => 'Grover Cleveland was de tweeëntwintigste en vierentwintigste president, de enige die twee niet-opeenvolgende termijnen diende. Hij stond bekend om zijn eerlijkheid, integriteit en conservatieve fiscale beleid.',
        'vroeg_leven' => 'Zoon van een Presbyteriaanse dominee. Werkte als klerk en werd later advocaat.',
        'politieke_carriere' => 'Sheriff van Erie County, Burgemeester van Buffalo, Gouverneur van New York.',
        'prestaties' => json_encode([
            'Enige president die twee niet-opeenvolgende termijnen diende',
            'Voerde een strijd tegen corruptie en politieke patronage',
            'Gebruikte zijn vetorecht meer dan alle voorgaande presidenten bij elkaar',
            'Hield vast aan de goudstandaard'
        ]),
        'fun_facts' => json_encode([
            'Hing persoonlijk twee criminelen op toen hij sheriff was',
            'Trouwde in het Witte Huis met de 21-jarige Frances Folsom; hij was 49',
            'Onderging in het geheim een operatie om een kankertumor uit zijn mond te verwijderen op een jacht',
            'De "Baby Ruth" candy bar is mogelijk naar zijn dochter vernoemd'
        ]),
        'echtgenote' => 'Frances Folsom Cleveland Preston',
        'kinderen' => json_encode(['Ruth', 'Esther', 'Marion', 'Richard F.','Francis Grover']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 180,
        'gewicht_kg' => 118, // Geschat, was zwaarlijvig
        'verkiezingsjaren' => json_encode([1884, 1892]),
        'leeftijd_bij_aantreden' => 47,
        'belangrijkste_gebeurtenissen' => 'Haymarket Riot (1886), Paniek van 1893 (tweede termijn), Pullman Strike (1894, tweede termijn).',
        'bekende_speeches' => 'Inaugural Addresses',
        'wetgeving' => json_encode(['Interstate Commerce Act of 1887', 'Dawes Act (1887)']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Eerste termijn relatief stabiel; tweede termijn werd gedomineerd door de ernstige Paniek van 1893.',
        'carrierre_voor_president' => 'Advocaat, sheriff, burgemeester, gouverneur.',
        'carrierre_na_president' => 'Trok zich terug in Princeton, New Jersey, en diende als trustee van de universiteit.',
        'doodsoorzaak' => 'Hartaanval',
        'begrafenisplaats' => 'Princeton Cemetery, Princeton, New Jersey',
        'historische_waardering' => 'Over het algemeen positief beoordeeld voor zijn integriteit en moed, hoewel zijn aanpak van de Paniek van 1893 wordt bekritiseerd.',
        'controverses' => 'Het schandaal over een buitenechtelijk kind tijdens zijn eerste campagne, zijn hardhandige aanpak van de Pullman Strike.',
        'citaten' => json_encode([
            '"A public office is a public trust."'
        ]),
        'monumenten_ter_ere' => 'Staat op het $1000 biljet (niet meer in omloop), diverse standbeelden.'
    ],

    // 23. Benjamin Harrison
    [
        'president_nummer' => 23,
        'naam' => 'Benjamin Harrison',
        'volledige_naam' => 'Benjamin Harrison',
        'bijnaam' => 'The Human Iceberg',
        'partij' => 'Republican',
        'periode_start' => '1889-03-04',
        'periode_eind' => '1893-03-04',
        'geboren' => '1833-08-20',
        'overleden' => '1901-03-13',
        'geboorteplaats' => 'North Bend, Ohio',
        'vice_president' => 'Levi P. Morton',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/f8/Benjamin_Harrison%2C_painting_by_Eastman_Johnson.jpg',
        'biografie' => 'Benjamin Harrison was de drieëntwintigste president, de kleinzoon van president William Henry Harrison. Hij diende één termijn tussen de twee termijnen van Grover Cleveland. Zijn presidentschap kenmerkte zich door economisch nationalisme en aanzienlijke overheidsuitgaven.',
        'vroeg_leven' => 'Groeide op op de boerderij van zijn grootvader. Werd advocaat in Indianapolis en diende als generaal in de Burgeroorlog.',
        'politieke_carriere' => 'U.S. Senator uit Indiana.',
        'prestaties' => json_encode([
            'Ondertekende de Sherman Antitrust Act, de eerste wet tegen monopolies',
            'Hield toezicht op de toelating van zes westelijke staten tot de Unie',
            'Moderniseerde de marine aanzienlijk'
        ]),
        'fun_facts' => json_encode([
            'Was de eerste president die elektriciteit in het Witte Huis had, maar was bang om de schakelaars aan te raken',
            'Was de laatste president met een volle baard',
            'Verloor de populaire stem van Cleveland in 1888, maar won het kiescollege'
        ]),
        'echtgenote' => 'Caroline Lavinia Scott Harrison, Mary Scott Lord Dimmick',
        'kinderen' => json_encode(['Russell Benjamin', 'Mary Scott', 'Elizabeth']),
        'familie_connecties' => 'Kleinzoon van William Henry Harrison (9e president).',
        'lengte_cm' => 168,
        'gewicht_kg' => 77,
        'verkiezingsjaren' => json_encode([1888]),
        'leeftijd_bij_aantreden' => 55,
        'belangrijkste_gebeurtenissen' => 'Sherman Antitrust Act (1890), McKinley Tariff (1890), Wounded Knee Massacre (1890).',
        'bekende_speeches' => 'Centennial Inaugural Address',
        'wetgeving' => json_encode(['Sherman Antitrust Act', 'McKinley Tariff', 'Sherman Silver Purchase Act']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Gekenmerkt door hoge protectionistische tarieven en het eerste "Billion-Dollar Congress", wat leidde tot zorgen over de staatskas.',
        'carrierre_voor_president' => 'Advocaat, generaal, senator.',
        'carrierre_na_president' => 'Keerde terug naar zijn advocatenpraktijk in Indianapolis en vertegenwoordigde Venezuela in een grensgeschil.',
        'doodsoorzaak' => 'Longontsteking',
        'begrafenisplaats' => 'Crown Hill Cemetery, Indianapolis, Indiana',
        'historische_waardering' => 'Wordt vaak gezien als een gemiddelde president; gerespecteerd voor zijn intelligentie maar bekritiseerd voor zijn koude persoonlijkheid en de problematische economische wetgeving.',
        'controverses' => 'De hoge uitgaven van zijn regering en de impopulaire McKinley Tariff.',
        'citaten' => json_encode([
            '"We Americans have no commission from God to police the world."'
        ]),
        'monumenten_ter_ere' => 'Benjamin Harrison Presidential Site, standbeeld in Indianapolis'
    ],
    
    // 24. Grover Cleveland (Tweede termijn)
    // De data is grotendeels hetzelfde als #22, maar met een andere periode en vice-president.
    // Voor de eenvoud wordt dit samengevoegd met #22, maar in een echte DB zou dit een aparte entry kunnen zijn met een link.
    // In deze structuur voegen we hem als aparte entry toe voor de correctheid van het nummer.
    [
        'president_nummer' => 24,
        'naam' => 'Grover Cleveland',
        'volledige_naam' => 'Stephen Grover Cleveland',
        'bijnaam' => 'Big Steve',
        'partij' => 'Democratic',
        'periode_start' => '1893-03-04',
        'periode_eind' => '1897-03-04',
        'geboren' => '1837-03-18',
        'overleden' => '1908-06-24',
        'geboorteplaats' => 'Caldwell, New Jersey',
        'vice_president' => 'Adlai E. Stevenson I',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/f3/Grover_Cleveland_-_NARA_-_518139.jpg',
        'biografie' => 'Grover Cleveland was de tweeëntwintigste en vierentwintigste president, de enige die twee niet-opeenvolgende termijnen diende. Zijn tweede termijn werd gedomineerd door de ernstige economische depressie, de Paniek van 1893.',
        'vroeg_leven' => 'Zoon van een Presbyteriaanse dominee. Werkte als klerk en werd later advocaat.',
        'politieke_carriere' => 'Sheriff van Erie County, Burgemeester van Buffalo, Gouverneur van New York, 22e President.',
        'prestaties' => json_encode([
            'Enige president die twee niet-opeenvolgende termijnen diende',
            'Behandelde de Paniek van 1893 en de daaropvolgende depressie',
            'Onderdrukte de Pullman Strike',
            'Herriep de Sherman Silver Purchase Act'
        ]),
        'fun_facts' => json_encode([
            'Hing persoonlijk twee criminelen op toen hij sheriff was',
            'Trouwde in het Witte Huis met de 21-jarige Frances Folsom; hij was 49',
            'Onderging in het geheim een operatie om een kankertumor uit zijn mond te verwijderen op een jacht',
            'De "Baby Ruth" candy bar is mogelijk naar zijn dochter vernoemd'
        ]),
        'echtgenote' => 'Frances Folsom Cleveland Preston',
        'kinderen' => json_encode(['Ruth', 'Esther', 'Marion', 'Richard F.','Francis Grover']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 180,
        'gewicht_kg' => 118,
        'verkiezingsjaren' => json_encode([1884, 1892]),
        'leeftijd_bij_aantreden' => 55, // Tweede termijn
        'belangrijkste_gebeurtenissen' => 'Paniek van 1893, Pullman Strike (1894), herroeping van de Sherman Silver Purchase Act.',
        'bekende_speeches' => 'Second Inaugural Address',
        'wetgeving' => json_encode(['Herroeping van de Sherman Silver Purchase Act']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Gedomineerd door de ernstige Paniek van 1893 en een diepe economische depressie.',
        'carrierre_voor_president' => 'Advocaat, sheriff, burgemeester, gouverneur, 22e president.',
        'carrierre_na_president' => 'Trok zich terug in Princeton, New Jersey, en diende als trustee van de universiteit.',
        'doodsoorzaak' => 'Hartaanval',
        'begrafenisplaats' => 'Princeton Cemetery, Princeton, New Jersey',
        'historische_waardering' => 'Over het algemeen positief beoordeeld voor zijn integriteit en moed, hoewel zijn aanpak van de Paniek van 1893 wordt bekritiseerd.',
        'controverses' => 'Zijn hardhandige aanpak van de Pullman Strike, zijn onvermogen om de economische crisis effectief op te lossen.',
        'citaten' => json_encode([
            '"Honor lies in honest toil."'
        ]),
        'monumenten_ter_ere' => 'Staat op het $1000 biljet (niet meer in omloop), diverse standbeelden.'
    ],
    
    // 25. William McKinley
    [
        'president_nummer' => 25,
        'naam' => 'William McKinley',
        'volledige_naam' => 'William McKinley',
        'bijnaam' => 'The Napoleon of Protection',
        'partij' => 'Republican',
        'periode_start' => '1897-03-04',
        'periode_eind' => '1901-09-14',
        'geboren' => '1843-01-29',
        'overleden' => '1901-09-14',
        'geboorteplaats' => 'Niles, Ohio',
        'vice_president' => 'Garret Hobart (1897-1899), Theodore Roosevelt (1901)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/6d/Mckinley.jpg',
        'biografie' => 'William McKinley was de vijfentwintigste president. Hij leidde de natie naar de overwinning in de Spaans-Amerikaanse Oorlog, verhoogde beschermende tarieven om de industrie te bevorderen en handhaafde de goudstandaard. Hij werd aan het begin van zijn tweede termijn vermoord.',
        'vroeg_leven' => 'Diende in de Burgeroorlog en werd advocaat en politicus in Ohio.',
        'politieke_carriere' => 'U.S. House of Representatives, Gouverneur van Ohio.',
        'prestaties' => json_encode([
            'Won de Spaans-Amerikaanse Oorlog (1898)',
            'Verwierf de Filippijnen, Guam en Puerto Rico',
            'Annexeerde de Republiek Hawaï',
            'Ondertekende de Gold Standard Act van 1900'
        ]),
        'fun_facts' => json_encode([
            'Eerste president die een auto reed',
            'Voerde zijn verkiezingscampagne in 1896 grotendeels vanaf zijn veranda ("front-porch campaign")',
            'Zijn vrouw was epileptisch en hij was zeer toegewijd aan haar zorg',
            'Droeg altijd een rode anjer voor geluk'
        ]),
        'echtgenote' => 'Ida Saxton McKinley',
        'kinderen' => json_encode(['Twee dochters, beiden stierven jong']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 170,
        'gewicht_kg' => 86,
        'verkiezingsjaren' => json_encode([1896, 1900]),
        'leeftijd_bij_aantreden' => 54,
        'belangrijkste_gebeurtenissen' => 'Spaans-Amerikaanse Oorlog (1898), Annexatie van Hawaï (1898), Open Door Policy met China, zijn moord (1901).',
        'bekende_speeches' => 'Inaugural Addresses',
        'wetgeving' => json_encode(['Dingley Tariff Act of 1897', 'Gold Standard Act of 1900']),
        'oorlogen' => json_encode(['Spaans-Amerikaanse Oorlog', 'Filippijns-Amerikaanse Oorlog']),
        'economische_situatie' => 'Periode van snel economisch herstel en welvaart.',
        'carrierre_voor_president' => 'Advocaat, congreslid, gouverneur.',
        'carrierre_na_president' => 'Vermoor in functie.',
        'doodsoorzaak' => 'Moord (koudvuur door schotwond)',
        'begrafenisplaats' => 'McKinley National Memorial, Canton, Ohio',
        'historische_waardering' => 'Positief; wordt gezien als een bekwame leider die het presidentschap moderniseerde en Amerika op het wereldtoneel bracht.',
        'controverses' => 'De beslissing om de Filippijnen te annexeren, wat leidde tot een bloedige oorlog.',
        'citaten' => json_encode([
            '"War should never be entered upon until every agency of peace has failed."'
        ]),
        'monumenten_ter_ere' => 'McKinley National Memorial, Mount McKinley (nu Denali)'
    ],

    // 26. Theodore Roosevelt
    [
        'president_nummer' => 26,
        'naam' => 'Theodore Roosevelt',
        'volledige_naam' => 'Theodore Roosevelt Jr.',
        'bijnaam' => 'Teddy, TR, The Bull Moose',
        'partij' => 'Republican',
        'periode_start' => '1901-09-14',
        'periode_eind' => '1909-03-04',
        'geboren' => '1858-10-27',
        'overleden' => '1919-01-06',
        'geboorteplaats' => 'New York, New York',
        'vice_president' => 'Charles W. Fairbanks (1905-1909)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/19/President_Theodore_Roosevelt%2C_1904.jpg',
        'biografie' => 'Theodore Roosevelt was de zesentwintigste president, bekend om zijn energieke persoonlijkheid, progressieve beleid, natuurbescherming en expansionistische buitenlandse politiek. Hij werd president na de moord op McKinley.',
        'vroeg_leven' => 'Ziek kind uit een rijke New Yorkse familie. Overwon zijn astma door een inspannend leven te leiden. Studeerde aan Harvard.',
        'politieke_carriere' => 'New York State Assembly, U.S. Civil Service Commissioner, President van de New York City Police Commissioners, Assistant Secretary of the Navy, Gouverneur van New York, vice-president.',
        'prestaties' => json_encode([
            'Brak grote monopolies ("trust buster")',
            'Reguleerde de spoorwegen, voedsel- en drugveiligheid (Pure Food and Drug Act)',
            'Richtte talloze nationale parken, bossen en monumenten op',
            'Bemiddelde in het einde van de Russisch-Japanse Oorlog (won Nobelprijs voor de Vrede)',
            'Begon de bouw van het Panamakanaal'
        ]),
        'fun_facts' => json_encode([
            'Jongste president ooit (42 jaar)',
            'Overleefde een moordaanslag tijdens een toespraak en sprak door met de kogel in zijn borst',
            'De "Teddybeer" is naar hem vernoemd',
            'Was een bokser en judoka in het Witte Huis',
            'Eerste president die in een vliegtuig vloog en een onderzeeër dook'
        ]),
        'echtgenote' => 'Alice Hathaway Lee Roosevelt, Edith Kermit Carow Roosevelt',
        'kinderen' => json_encode(['Alice', 'Theodore Jr.', 'Kermit', 'Ethel Carow', 'Archibald Bulloch', 'Quentin']),
        'familie_connecties' => 'Verre neef van Franklin D. Roosevelt (32e president).',
        'lengte_cm' => 178,
        'gewicht_kg' => 91,
        'verkiezingsjaren' => json_encode([1904]),
        'leeftijd_bij_aantreden' => 42,
        'belangrijkste_gebeurtenissen' => 'Bouw Panamakanaal, Pure Food and Drug Act (1906), Grote Kolenstaking (1902), Paniek van 1907.',
        'bekende_speeches' => '"The Man in the Arena", "Speak softly and carry a big stick."',
        'wetgeving' => json_encode(['Hepburn Act', 'Pure Food and Drug Act', 'Meat Inspection Act', 'Antiquities Act']),
        'oorlogen' => json_encode(['Filippijns-Amerikaanse Oorlog (beëindigd)']),
        'economische_situatie' => 'Sterke economische groei, toenemende regulering van het bedrijfsleven.',
        'carrierre_voor_president' => 'Uitgebreide carrière als auteur, natuuronderzoeker en politicus.',
        'carrierre_na_president' => 'Ging op safari in Afrika, leidde een expeditie in de Amazone, stelde zich opnieuw kandidaat in 1912 ("Bull Moose" partij).',
        'doodsoorzaak' => 'Longembolie',
        'begrafenisplaats' => 'Youngs Memorial Cemetery, Oyster Bay, New York',
        'historische_waardering' => 'Consequent gerangschikt als een van de beste presidenten, geprezen voor zijn leiderschap en hervormingen.',
        'controverses' => 'Zijn "big stick" diplomatie werd bekritiseerd als imperialistisch, zijn rol in de afscheiding van Panama van Colombia.',
        'citaten' => json_encode([
            '"Speak softly and carry a big stick; you will go far."',
            '"It is hard to fail, but it is worse never to have tried to succeed."'
        ]),
        'monumenten_ter_ere' => 'Mount Rushmore, Theodore Roosevelt National Park, USS Theodore Roosevelt'
    ],
    
    // 27. William Howard Taft
    [
        'president_nummer' => 27,
        'naam' => 'William Howard Taft',
        'volledige_naam' => 'William Howard Taft',
        'bijnaam' => 'Big Bill',
        'partij' => 'Republican',
        'periode_start' => '1909-03-04',
        'periode_eind' => '1913-03-04',
        'geboren' => '1857-09-15',
        'overleden' => '1930-03-08',
        'geboorteplaats' => 'Cincinnati, Ohio',
        'vice_president' => 'James S. Sherman',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a2/William_Howard_Taft%2C_Bain_bw_photo_portrait%2C_1908.jpg',
        'biografie' => 'William Howard Taft was de zevenentwintigste president, de door Theodore Roosevelt uitgekozen opvolger. Hij zette veel van Roosevelts beleid voort, vooral op het gebied van trust-busting, maar zijn presidentschap leidde tot een breuk in de Republikeinse partij.',
        'vroeg_leven' => 'Zoon van een vooraanstaande politicus. Studeerde aan Yale en werd advocaat en rechter.',
        'politieke_carriere' => 'Rechter, Solicitor General, federale rechter, Gouverneur-Generaal van de Filippijnen, Secretary of War.',
        'prestaties' => json_encode([
            'Brak meer trusts dan Roosevelt',
            'Steunde het 16e Amendement (inkomstenbelasting) en het 17e Amendement (directe verkiezing van senatoren)',
            'Versterkte de Interstate Commerce Commission',
            'Werd later Chief Justice van het Hooggerechtshof, zijn droombaan'
        ]),
        'fun_facts' => json_encode([
            'Enige persoon die zowel president als Chief Justice was',
            'De zwaarste president, woog meer dan 136 kg',
            'Kwam naar verluidt vast te zitten in de badkuip van het Witte Huis (waarschijnlijk een mythe, maar er werd een grotere geïnstalleerd)',
            'Startte de traditie van de president die de eerste worp van het honkbalseizoen gooit'
        ]),
        'echtgenote' => 'Helen Herron Taft',
        'kinderen' => json_encode(['Robert A. Taft (invloedrijk senator)', 'Helen Taft Manning', 'Charles Phelps Taft II']),
        'familie_connecties' => 'Geen directe presidentiële familie.',
        'lengte_cm' => 182,
        'gewicht_kg' => 140, // Geschat
        'verkiezingsjaren' => json_encode([1908]),
        'leeftijd_bij_aantreden' => 51,
        'belangrijkste_gebeurtenissen' => 'Breuk met Theodore Roosevelt, passage van het 16e en 17e Amendement.',
        'bekende_speeches' => 'State of the Union toespraken.',
        'wetgeving' => json_encode(['Payne-Aldrich Tariff Act', 'Mann-Elkins Act']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Stabiel, voortzetting van progressieve regulering.',
        'carrierre_voor_president' => 'Rechter, diplomaat, Secretary of War.',
        'carrierre_na_president' => 'Professor aan Yale Law School, daarna Chief Justice van het Hooggerechtshof (1921-1930).',
        'doodsoorzaak' => 'Hoge bloeddruk en hart- en vaatziekten',
        'begrafenisplaats' => 'Arlington National Cemetery, Arlington, Virginia',
        'historische_waardering' => 'Gemiddeld; wordt gezien als een bekwame administrateur en jurist, maar een minder effectieve politiek leider dan zijn voorganger.',
        'controverses' => 'Het Ballinger-Pinchot-schandaal, dat de breuk met de progressieve vleugel van de partij en met Roosevelt versnelde.',
        'citaten' => json_encode([
            '"Presidents come and go, but the Supreme Court goes on forever."'
        ]),
        'monumenten_ter_ere' => 'William Howard Taft National Historic Site'
    ],

    // 28. Woodrow Wilson
    [
        'president_nummer' => 28,
        'naam' => 'Woodrow Wilson',
        'volledige_naam' => 'Thomas Woodrow Wilson',
        'bijnaam' => 'The Schoolmaster in Politics',
        'partij' => 'Democratic',
        'periode_start' => '1913-03-04',
        'periode_eind' => '1921-03-04',
        'geboren' => '1856-12-28',
        'overleden' => '1924-02-03',
        'geboorteplaats' => 'Staunton, Virginia',
        'vice_president' => 'Thomas R. Marshall',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/3/32/President_Woodrow_Wilson_portrait_December_2_1912.jpg',
        'biografie' => 'Woodrow Wilson was de achtentwintigste president. Hij was een leider van de Progressieve Beweging en leidde de VS tijdens de Eerste Wereldoorlog. Na de oorlog pleitte hij voor de oprichting van de Volkenbond om toekomstige conflicten te voorkomen.',
        'vroeg_leven' => 'Zoon van een Presbyteriaanse dominee. Werd een vooraanstaand academicus, professor en president van Princeton University.',
        'politieke_carriere' => 'President van Princeton University, Gouverneur van New Jersey.',
        'prestaties' => json_encode([
            'Leidde de VS door de Eerste Wereldoorlog',
            'Creëerde de Federal Reserve en de Federal Trade Commission',
            'Ondertekende de Clayton Antitrust Act',
            'Pleitte voor de Volkenbond en zijn Veertien Punten',
            'Steunde het 19e Amendement, dat vrouwen stemrecht gaf'
        ]),
        'fun_facts' => json_encode([
            'Enige president met een Ph.D.',
            'Hield van golfen en schilderde golfballen zwart om in de sneeuw te kunnen spelen',
            'Hield schapen op het gazon van het Witte Huis om geld in te zamelen voor de oorlogsinspanning',
            'Kreeg een beroerte in 1919, waarna zijn vrouw, Edith, veel van zijn taken heimelijk overnam'
        ]),
        'echtgenote' => 'Ellen Axson Wilson, Edith Bolling Galt Wilson',
        'kinderen' => json_encode(['Margaret Woodrow', 'Jessie Woodrow', 'Eleanor Randolph']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 180,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1912, 1916]),
        'leeftijd_bij_aantreden' => 56,
        'belangrijkste_gebeurtenissen' => 'Eerste Wereldoorlog (1914-1918), oprichting Federal Reserve (1913), Spaanse grieppandemie (1918), Verdrag van Versailles (1919).',
        'bekende_speeches' => '"Fourteen Points" speech, "The world must be made safe for democracy."',
        'wetgeving' => json_encode(['Federal Reserve Act', 'Clayton Antitrust Act', 'Federal Trade Commission Act', 'Espionage and Sedition Acts']),
        'oorlogen' => json_encode(['Eerste Wereldoorlog']),
        'economische_situatie' => 'Economische boom door de oorlog, gevolgd door een naoorlogse recessie.',
        'carrierre_voor_president' => 'Academicus, universiteitspresident, gouverneur.',
        'carrierre_na_president' => 'Leefde teruggetrokken in Washington D.C., gehandicapt door zijn beroerte.',
        'doodsoorzaak' => 'Beroerte en hartproblemen',
        'begrafenisplaats' => 'Washington National Cathedral, Washington, D.C.',
        'historische_waardering' => 'Gemengd; hoog beoordeeld voor zijn binnenlandse progressieve prestaties en zijn internationalistische visie, maar zwaar bekritiseerd voor zijn racistische beleid (segregatie van de federale overheid) en zijn onderdrukking van burgerlijke vrijheden tijdens de oorlog.',
        'controverses' => 'Segregatie van de federale overheid, de Espionage en Sedition Acts, zijn falen om de Senaat te overtuigen het Verdrag van Versailles te ratificeren.',
        'citaten' => json_encode([
            '"The world must be made safe for democracy."'
        ]),
        'monumenten_ter_ere' => 'Woodrow Wilson Bridge, Woodrow Wilson International Center for Scholars'
    ],

    // 29. Warren G. Harding
    [
        'president_nummer' => 29,
        'naam' => 'Warren G. Harding',
        'volledige_naam' => 'Warren Gamaliel Harding',
        'bijnaam' => 'Wobbly Warren',
        'partij' => 'Republican',
        'periode_start' => '1921-03-04',
        'periode_eind' => '1923-08-02',
        'geboren' => '1865-11-02',
        'overleden' => '1923-08-02',
        'geboorteplaats' => 'Blooming Grove, Ohio',
        'vice_president' => 'Calvin Coolidge',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Warren_G_Harding-Harris_%26_Ewing.jpg',
        'biografie' => 'Warren G. Harding was de negenentwintigste president. Hij won de verkiezingen met de belofte van een "terugkeer naar normaliteit" na de Eerste Wereldoorlog. Zijn presidentschap werd ontsierd door schandalen, met name het Teapot Dome-schandaal, die na zijn plotselinge dood aan het licht kwamen.',
        'vroeg_leven' => 'Krantenuitgever in Marion, Ohio.',
        'politieke_carriere' => 'Ohio State Senator, Luitenant-gouverneur van Ohio, U.S. Senator.',
        'prestaties' => json_encode([
            'Creëerde het Bureau of the Budget (nu Office of Management and Budget)',
            'Pleitte voor ontwapening op de Washington Naval Conference',
            'Ondertekende de eerste federale wet voor kinderwelzijn'
        ]),
        'fun_facts' => json_encode([
            'Gokte naar verluidt een set porselein van het Witte Huis en verloor het',
            'Had meerdere buitenechtelijke affaires, waaronder een met Nan Britton, die een boek schreef over hun dochter',
            'Eerste president die op de radio sprak'
        ]),
        'echtgenote' => 'Florence Kling Harding',
        'kinderen' => json_encode(['Een buitenechtelijke dochter, Elizabeth Ann Blaesing']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 183,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1920]),
        'leeftijd_bij_aantreden' => 55,
        'belangrijkste_gebeurtenissen' => 'Teapot Dome-schandaal, Washington Naval Disarmament Conference (1921), zijn plotselinge dood.',
        'bekende_speeches' => '"Return to Normalcy" campagne slogan.',
        'wetgeving' => json_encode(['Fordney-McCumber Tariff', 'Budget and Accounting Act of 1921']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'Aanvankelijk een korte recessie, gevolgd door het begin van de "Roaring Twenties".',
        'carrierre_voor_president' => 'Krantenuitgever, politicus.',
        'carrierre_na_president' => 'Stierf in functie.',
        'doodsoorzaak' => 'Hartaanval of beroerte',
        'begrafenisplaats' => 'Harding Tomb, Marion, Ohio',
        'historische_waardering' => 'Lange tijd beschouwd als een van de slechtste presidenten vanwege de corruptie in zijn regering. Recentere analyses zijn iets milder en wijzen op enkele successen, maar hij blijft in de onderste regionen.',
        'controverses' => 'Teapot Dome-schandaal, andere corruptiegevallen waarbij zijn "Ohio Gang" van vrienden betrokken was, zijn buitenechtelijke affaires.',
        'citaten' => json_encode([
            '"I am not fit for this office and should never have been there."'
        ]),
        'monumenten_ter_ere' => 'Harding Tomb in Marion, Ohio'
    ],
    
    // 30. Calvin Coolidge
    [
        'president_nummer' => 30,
        'naam' => 'Calvin Coolidge',
        'volledige_naam' => 'John Calvin Coolidge Jr.',
        'bijnaam' => 'Silent Cal',
        'partij' => 'Republican',
        'periode_start' => '1923-08-02',
        'periode_eind' => '1929-03-04',
        'geboren' => '1872-07-04',
        'overleden' => '1933-01-05',
        'geboorteplaats' => 'Plymouth Notch, Vermont',
        'vice_president' => 'Charles G. Dawes (1925-1929)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/03/Calvin_Coolidge_photo_portrait_head_and_shoulders.jpg',
        'biografie' => 'Calvin Coolidge was de dertigste president, die aantrad na de dood van Harding. Hij stond bekend om zijn zwijgzaamheid en zijn conservatieve, pro-business beleid. Hij herstelde het vertrouwen in het Witte Huis na de schandalen van Harding.',
        'vroeg_leven' => 'Zoon van een winkelier in een klein dorp in Vermont. Studeerde aan Amherst College en werd advocaat in Massachusetts.',
        'politieke_carriere' => 'Uitgebreide carrière in de politiek van Massachusetts, inclusief burgemeester, staatssenator, luitenant-gouverneur en gouverneur; vice-president.',
        'prestaties' => json_encode([
            'Verlaagde belastingen en overheidsuitgaven aanzienlijk',
            'Verleende staatsburgerschap aan Indianen met de Indian Citizenship Act',
            'Ondertekende het Kellogg-Briand Pact, dat oorlog veroordeelde'
        ]),
        'fun_facts' => json_encode([
            'Werd beëdigd door zijn vader, een notaris, bij het licht van een petroleumlamp in het ouderlijk huis in Vermont',
            'Stond bekend als een man van weinig woorden; toen een vrouw zei dat ze had gewed dat ze meer dan twee woorden uit hem kon krijgen, antwoordde hij: "You lose."',
            'Had een wasbeer als huisdier in het Witte Huis genaamd Rebecca'
        ]),
        'echtgenote' => 'Grace Anna Goodhue Coolidge',
        'kinderen' => json_encode(['John', 'Calvin Jr.']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 178,
        'gewicht_kg' => 70,
        'verkiezingsjaren' => json_encode([1924]),
        'leeftijd_bij_aantreden' => 51,
        'belangrijkste_gebeurtenissen' => 'Roaring Twenties, Indian Citizenship Act (1924), Kellogg-Briand Pact (1928).',
        'bekende_speeches' => 'Zijn toespraken waren meestal kort en direct.',
        'wetgeving' => json_encode(['Indian Citizenship Act of 1924', 'Revenue Acts of 1924 and 1926', 'Immigration Act of 1924']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'De "Roaring Twenties", een periode van grote economische welvaart en speculatie.',
        'carrierre_voor_president' => 'Advocaat, politicus in Massachusetts, vice-president.',
        'carrierre_na_president' => 'Trok zich terug en schreef zijn autobiografie en krantencolumns.',
        'doodsoorzaak' => 'Hartinfarct',
        'begrafenisplaats' => 'Plymouth Notch Cemetery, Plymouth Notch, Vermont',
        'historische_waardering' => 'Gemengd; populair in zijn tijd, maar later bekritiseerd omdat zijn laissez-faire beleid mogelijk heeft bijgedragen aan de Grote Depressie. Zijn reputatie is de laatste jaren verbeterd bij conservatieven.',
        'controverses' => 'Zijn passieve benadering van de economie en het uitblijven van regulering van de aandelenmarkt.',
        'citaten' => json_encode([
            '"The chief business of the American people is business."',
            '"Don\'t expect to build up the weak by pulling down the strong."'
        ]),
        'monumenten_ter_ere' => 'Calvin Coolidge Presidential Library and Museum, Calvin Coolidge Homestead District'
    ],

    // 31. Herbert Hoover
    [
        'president_nummer' => 31,
        'naam' => 'Herbert Hoover',
        'volledige_naam' => 'Herbert Clark Hoover',
        'bijnaam' => 'The Great Engineer',
        'partij' => 'Republican',
        'periode_start' => '1929-03-04',
        'periode_eind' => '1933-03-04',
        'geboren' => '1874-08-10',
        'overleden' => '1964-10-20',
        'geboorteplaats' => 'West Branch, Iowa',
        'vice_president' => 'Charles Curtis',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a8/Herbert_Hoover_1928.jpg',
        'biografie' => 'Herbert Hoover was de eenendertigste president. Zijn presidentschap begon met optimisme, maar werd al snel overschaduwd door de beurskrach van 1929 en de daaropvolgende Grote Depressie. Zijn onvermogen om de crisis effectief te bestrijden, maakte hem zeer impopulair.',
        'vroeg_leven' => 'Wees op jonge leeftijd, studeerde geologie aan Stanford University en werd een zeer succesvolle, internationaal bekende mijnbouwingenieur en miljonair.',
        'politieke_carriere' => 'Leidde de U.S. Food Administration tijdens WO I, Secretary of Commerce.',
        'prestaties' => json_encode([
            'Organiseerde massale humanitaire hulp in Europa na WO I',
            'Begon de bouw van de Hoover Dam (toen Boulder Dam)',
            'Creëerde de Reconstruction Finance Corporation om banken en bedrijven te ondersteunen'
        ]),
        'fun_facts' => json_encode([
            'Sprak vloeiend Mandarijn Chinees met zijn vrouw als ze niet afgeluisterd wilden worden',
            'Was de eerste president die werd geboren ten westen van de Mississippi',
            'Schenkte zijn presidentiële salaris aan een goed doel'
        ]),
        'echtgenote' => 'Lou Henry Hoover',
        'kinderen' => json_encode(['Herbert Hoover Jr.', 'Allan Hoover']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 182,
        'gewicht_kg' => 83,
        'verkiezingsjaren' => json_encode([1928]),
        'leeftijd_bij_aantreden' => 54,
        'belangrijkste_gebeurtenissen' => 'Beurskrach van 1929, Grote Depressie, Bonus Army March (1932), Smoot-Hawley Tariff (1930).',
        'bekende_speeches' => 'Toespraken over "rugged individualism".',
        'wetgeving' => json_encode(['Smoot-Hawley Tariff Act', 'Reconstruction Finance Corporation Act']),
        'oorlogen' => json_encode(['Geen']),
        'economische_situatie' => 'De Grote Depressie, de diepste en langste economische crisis in de Amerikaanse geschiedenis.',
        'carrierre_voor_president' => 'Mijnbouwingenieur, humanitair leider, Secretary of Commerce.',
        'carrierre_na_president' => 'Werd een uitgesproken criticus van de New Deal. Leidde later de Hoover Commission om de federale overheid te reorganiseren.',
        'doodsoorzaak' => 'Inwendige bloeding',
        'begrafenisplaats' => 'Herbert Hoover Presidential Library and Museum, West Branch, Iowa',
        'historische_waardering' => 'Negatief; hij wordt vaak de schuld gegeven van het verergeren van de Grote Depressie, hoewel historici nu erkennen dat zijn beleid de basis legde voor delen van de New Deal.',
        'controverses' => 'Zijn aanpak van de Grote Depressie, de Smoot-Hawley Tariff die de wereldhandel verlamde, het gewelddadig uiteenjagen van de "Bonus Army".',
        'citaten' => json_encode([
            '"Prosperity is just around the corner."'
        ]),
        'monumenten_ter_ere' => 'Hoover Dam, Herbert Hoover Presidential Library and Museum'
    ],

    // 32. Franklin D. Roosevelt
    [
        'president_nummer' => 32,
        'naam' => 'Franklin D. Roosevelt',
        'volledige_naam' => 'Franklin Delano Roosevelt',
        'bijnaam' => 'FDR',
        'partij' => 'Democratic',
        'periode_start' => '1933-03-04',
        'periode_eind' => '1945-04-12',
        'geboren' => '1882-01-30',
        'overleden' => '1945-04-12',
        'geboorteplaats' => 'Hyde Park, New York',
        'vice_president' => 'John Nance Garner (1933-1941), Henry A. Wallace (1941-1945), Harry S. Truman (1945)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/42/FDR_1944_Color_Portrait.jpg',
        'biografie' => 'Franklin D. Roosevelt was de tweeëndertigste president. Hij leidde het land door de Grote Depressie en de Tweede Wereldoorlog. Hij is de enige president die vier keer werd verkozen. Zijn New Deal-programma\'s hebben de rol van de federale overheid fundamenteel veranderd.',
        'vroeg_leven' => 'Geboren in een rijke, invloedrijke familie. Studeerde aan Harvard en Columbia Law School. Kreeg polio in 1921, wat hem vanaf zijn middel verlamde.',
        'politieke_carriere' => 'New York State Senator, Assistant Secretary of the Navy, Gouverneur van New York.',
        'prestaties' => json_encode([
            'Implementeerde de New Deal om de Grote Depressie te bestrijden',
            'Creëerde Social Security, de FDIC en de SEC',
            'Leidde de Geallieerden naar de overwinning in de Tweede Wereldoorlog',
            'Hielp bij de oprichting van de Verenigde Naties'
        ]),
        'fun_facts' => json_encode([
            'Enige president die meer dan twee termijnen diende (verkozen voor vier)',
            'Hield "fireside chats" op de radio om rechtstreeks met het Amerikaanse volk te communiceren',
            'Was een fervent postzegelverzamelaar',
            'Zijn hond, Fala, was een nationale beroemdheid'
        ]),
        'echtgenote' => 'Eleanor Roosevelt',
        'kinderen' => json_encode(['Anna', 'James', 'Franklin Jr.', 'Elliott', 'John', 'een andere Franklin Jr. die als baby stierf']),
        'familie_connecties' => 'Verre neef van Theodore Roosevelt (26e president).',
        'lengte_cm' => 188,
        'gewicht_kg' => 84,
        'verkiezingsjaren' => json_encode([1932, 1936, 1940, 1944]),
        'leeftijd_bij_aantreden' => 51,
        'belangrijkste_gebeurtenissen' => 'Grote Depressie, New Deal, Tweede Wereldoorlog, Aanval op Pearl Harbor (1941), D-Day (1944).',
        'bekende_speeches' => '"The only thing we have to fear is fear itself.", "A date which will live in infamy."',
        'wetgeving' => json_encode(['Glass-Steagall Act', 'Social Security Act', 'National Labor Relations Act', 'Lend-Lease Act']),
        'oorlogen' => json_encode(['Tweede Wereldoorlog']),
        'economische_situatie' => 'Gedomineerd door de Grote Depressie en de daaropvolgende heropbouw en oorlogseconomie.',
        'carrierre_voor_president' => 'Advocaat, politicus, gouverneur.',
        'carrierre_na_president' => 'Stierf in functie.',
        'doodsoorzaak' => 'Hersenbloeding',
        'begrafenisplaats' => 'Home of Franklin D. Roosevelt National Historic Site, Hyde Park, New York',
        'historische_waardering' => 'Wordt consequent gerangschikt als een van de drie grootste presidenten, samen met Washington en Lincoln, voor zijn leiderschap in twee van de grootste crises van het land.',
        'controverses' => 'Zijn plan om het Hooggerechtshof uit te breiden ("court-packing"), de internering van Japanse Amerikanen tijdens WO II.',
        'citaten' => json_encode([
            '"The only thing we have to fear is fear itself."',
            '"The test of our progress is not whether we add more to the abundance of those who have much; it is whether we provide enough for those who have too little."'
        ]),
        'monumenten_ter_ere' => 'Franklin Delano Roosevelt Memorial, staat op de dime, talloze scholen en straten.'
    ],

    // 33. Harry S. Truman
    [
        'president_nummer' => 33,
        'naam' => 'Harry S. Truman',
        'volledige_naam' => 'Harry S. Truman',
        'bijnaam' => 'Give \'Em Hell, Harry!',
        'partij' => 'Democratic',
        'periode_start' => '1945-04-12',
        'periode_eind' => '1953-01-20',
        'geboren' => '1884-05-08',
        'overleden' => '1972-12-26',
        'geboorteplaats' => 'Lamar, Missouri',
        'vice_president' => 'Alben W. Barkley (1949-1953)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/05/Harry_S_Truman%2C_bw_half-length_photo_portrait%2C_facing_front%2C_1945.jpg',
        'biografie' => 'Harry S. Truman was de drieëndertigste president, die aantrad na de dood van FDR. Hij nam de beslissing om de atoombommen op Japan te gebruiken, wat de Tweede Wereldoorlog beëindigde. Zijn presidentschap zag het begin van de Koude Oorlog, de oprichting van de NAVO en de Koreaanse Oorlog.',
        'vroeg_leven' => 'Boer, hadgeen, en legerofficier in WO I. De enige 20e-eeuwse president zonder universiteitsdiploma.',
        'politieke_carriere' => 'County rechter, U.S. Senator, vice-president.',
        'prestaties' => json_encode([
            'Hield toezicht op het einde van de Tweede Wereldoorlog',
            'Implementeerde het Marshallplan om Europa weer op te bouwen',
            'Stelde de Truman-doctrine van containment van het communisme vast',
            'Desegregeerde het Amerikaanse leger',
            'Erkende de staat Israël'
        ]),
        'fun_facts' => json_encode([
            'De "S" in zijn naam staat nergens voor; het was een compromis tussen de namen van zijn grootvaders',
            'Plaats te een beroemd bord op zijn bureau met de tekst "The Buck Stops Here"',
            'Won een schokkende herverkiezing in 1948, ondanks peilingen die het tegendeel voorspelden'
        ]),
        'echtgenote' => 'Elizabeth "Bess" Virginia Wallace Truman',
        'kinderen' => json_encode(['Margaret Truman']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 175,
        'gewicht_kg' => 77,
        'verkiezingsjaren' => json_encode([1948]),
        'leeftijd_bij_aantreden' => 60,
        'belangrijkste_gebeurtenissen' => 'Einde WO II, Atoombommen op Hiroshima en Nagasaki (1945), Begin Koude Oorlog, Koreaanse Oorlog (1950-1953), Oprichting NAVO (1949).',
        'bekende_speeches' => 'Truman Doctrine speech, toespraken over zijn "Fair Deal" programma.',
        'wetgeving' => json_encode(['Marshall Plan', 'National Security Act of 1947', 'Taft-Hartley Act (overruled zijn veto)']),
        'oorlogen' => json_encode(['Koreaanse Oorlog']),
        'economische_situatie' => 'Naoorlogse economische transitie en bloei, onderbroken door stakingen en inflatie.',
        'carrierre_voor_president' => 'Boer, ondernemer, rechter, senator, vice-president.',
        'carrierre_na_president' => 'Trok zich terug in Independence, Missouri; richtte zijn presidentiële bibliotheek op.',
        'doodsoorzaak' => 'Meervoudig orgaanfalen',
        'begrafenisplaats' => 'Harry S. Truman Presidential Library and Museum, Independence, Missouri',
        'historische_waardering' => 'Zeer hoog; wordt geprezen voor zijn besluitvaardigheid, vooral in de buitenlandse politiek, en zijn verrassend sterke leiderschap.',
        'controverses' => 'Het gebruik van de atoombom, het ontslaan van generaal Douglas MacArthur tijdens de Koreaanse Oorlog.',
        'citaten' => json_encode([
            '"The buck stops here."',
            '"If you can\'t stand the heat, get out of the kitchen."'
        ]),
        'monumenten_ter_ere' => 'Harry S. Truman Presidential Library and Museum, USS Harry S. Truman'
    ],
    
    // 34. Dwight D. Eisenhower
    [
        'president_nummer' => 34,
        'naam' => 'Dwight D. Eisenhower',
        'volledige_naam' => 'Dwight David Eisenhower',
        'bijnaam' => 'Ike',
        'partij' => 'Republican',
        'periode_start' => '1953-01-20',
        'periode_eind' => '1961-01-20',
        'geboren' => '1890-10-14',
        'overleden' => '1969-03-28',
        'geboorteplaats' => 'Denison, Texas',
        'vice_president' => 'Richard Nixon',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/63/Dwight_D._Eisenhower%2C_official_photo_portrait%2C_May_29%2C_1959.jpg',
        'biografie' => 'Dwight D. Eisenhower was de vierendertigste president en de opperbevelhebber van de Geallieerde strijdkrachten in Europa tijdens de Tweede Wereldoorlog. Zijn presidentschap werd gekenmerkt door de Koude Oorlog, economische welvaart en de oprichting van het Interstate Highway System.',
        'vroeg_leven' => 'Groeide op in Abilene, Kansas. Studeerde af aan West Point en had een lange militaire carrière.',
        'politieke_carriere' => 'Geen politieke ervaring voor het presidentschap; was een vijfsterrengeneraal en de eerste opperbevelhebber van de NAVO.',
        'prestaties' => json_encode([
            'Beëindigde de Koreaanse Oorlog',
            'Creëerde het Interstate Highway System',
            'Stuurde federale troepen naar Little Rock, Arkansas, om schoolintegratie af te dwingen',
            'Richtte NASA op na de lancering van Spoetnik',
            'Waarschuwde voor de groeiende invloed van het "militair-industrieel complex"'
        ]),
        'fun_facts' => json_encode([
            'Was een fervent golfer en liet een putting green aanleggen op het terrein van het Witte Huis',
            'Was een getalenteerd schilder',
            'Genoot van het koken van stoofschotels en zijn recept voor "Ike\'s Steamed-Rice" is beroemd'
        ]),
        'echtgenote' => 'Mamie Geneva Doud Eisenhower',
        'kinderen' => json_encode(['Doud Dwight "Icky"', 'John Sheldon Doud']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 179,
        'gewicht_kg' => 78,
        'verkiezingsjaren' => json_encode([1952, 1956]),
        'leeftijd_bij_aantreden' => 62,
        'belangrijkste_gebeurtenissen' => 'Einde Koreaanse Oorlog (1953), Brown v. Board of Education (1954), Suez-crisis (1956), lancering van Spoetnik (1957), oprichting NASA (1958).',
        'bekende_speeches' => 'Farewell Address (waarschuwing voor het militair-industrieel complex).',
        'wetgeving' => json_encode(['Federal-Aid Highway Act of 1956', 'National Defense Education Act', 'Civil Rights Act of 1957']),
        'oorlogen' => json_encode(['Koude Oorlog conflicten (geen directe oorlog)']),
        'economische_situatie' => 'Een periode van grote welvaart en economische groei in de jaren 50.',
        'carrierre_voor_president' => 'Vijfsterrengeneraal, opperbevelhebber van de NAVO.',
        'carrierre_na_president' => 'Trok zich terug op zijn boerderij in Gettysburg, Pennsylvania.',
        'doodsoorzaak' => 'Hartfalen',
        'begrafenisplaats' => 'Dwight D. Eisenhower Presidential Library, Museum and Boyhood Home, Abilene, Kansas',
        'historische_waardering' => 'Zeer hoog; geprezen voor zijn kalme, bekwame leiderschap tijdens de Koude Oorlog, zijn binnenlandse programma\'s en zijn vooruitziende waarschuwingen.',
        'controverses' => 'De CIA-georkestreerde staatsgrepen in Iran (1953) en Guatemala (1954), het U-2 incident (1960).',
        'citaten' => json_encode([
            '"In the councils of government, we must guard against the acquisition of unwarranted influence, whether sought or unsought, by the military-industrial complex."'
        ]),
        'monumenten_ter_ere' => 'Dwight D. Eisenhower Presidential Library, Dwight D. Eisenhower Memorial, USS Dwight D. Eisenhower'
    ],
    
    // 35. John F. Kennedy
    [
        'president_nummer' => 35,
        'naam' => 'John F. Kennedy',
        'volledige_naam' => 'John Fitzgerald Kennedy',
        'bijnaam' => 'JFK, Jack',
        'partij' => 'Democratic',
        'periode_start' => '1961-01-20',
        'periode_eind' => '1963-11-22',
        'geboren' => '1917-05-29',
        'overleden' => '1963-11-22',
        'geboorteplaats' => 'Brookline, Massachusetts',
        'vice_president' => 'Lyndon B. Johnson',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c3/John_F._Kennedy%2C_White_House_color_photo_portrait.jpg',
        'biografie' => 'John F. Kennedy was de vijfendertigste president, wiens korte ambtstermijn de natie inspireerde met zijn jeugd en optimisme. Zijn presidentschap zag hoogtepunten van de Koude Oorlog, zoals de Cubacrisis, en de start van de race naar de maan. Zijn moord in 1963 schokte de wereld.',
        'vroeg_leven' => 'Zoon van een zeer rijke en politiek invloedrijke familie. Held in de Tweede Wereldoorlog. Studeerde aan Harvard.',
        'politieke_carriere' => 'U.S. House of Representatives, U.S. Senator.',
        'prestaties' => json_encode([
            'Beheerde de Cubacrisis en voorkwam een kernoorlog',
            'Richtte het Peace Corps op',
            'Startte het Apollo-programma met als doel een man op de maan te zetten',
            'Onderhandelde over het Nuclear Test Ban Treaty'
        ]),
        'fun_facts' => json_encode([
            'Eerste rooms-katholieke president',
            'Jongst gekozen president (43 jaar)',
            'Won een Pulitzerprijs voor zijn boek "Profiles in Courage"',
            'Schenkte zijn presidentiële en congres salarissen aan goede doelen'
        ]),
        'echtgenote' => 'Jacqueline Lee Bouvier Kennedy',
        'kinderen' => json_encode(['Caroline Bouvier', 'John Fitzgerald Jr.', 'Patrick Bouvier', 'een doodgeboren dochter']),
        'familie_connecties' => 'Deel van de invloedrijke Kennedy-familie. Zijn broer Robert was zijn procureur-generaal; broer Ted was een senator.',
        'lengte_cm' => 183,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([1960]),
        'leeftijd_bij_aantreden' => 43,
        'belangrijkste_gebeurtenissen' => 'Varkensbaai-invasie (1961), Cubacrisis (1962), bouw van de Berlijnse Muur (1961), start van de ruimtewedloop, zijn moord (1963).',
        'bekende_speeches' => '"Ask not what your country can do for you—ask what you can do for your country.", "Ich bin ein Berliner."',
        'wetgeving' => json_encode(['Nuclear Test Ban Treaty']),
        'oorlogen' => json_encode(['Koude Oorlog, escalatie in Vietnam']),
        'economische_situatie' => 'Periode van sterke economische groei.',
        'carrierre_voor_president' => 'Journalist, auteur, marineofficier, congreslid, senator.',
        'carrierre_na_president' => 'Vermoor in functie.',
        'doodsoorzaak' => 'Moord (neergeschoten door Lee Harvey Oswald)',
        'begrafenisplaats' => 'Arlington National Cemetery, Arlington, Virginia',
        'historische_waardering' => 'Zeer hoog; zijn inspirerende retoriek en tragische dood hebben hem een iconische status gegeven, hoewel historici ook wijzen op onvoltooide doelen en persoonlijke tekortkomingen.',
        'controverses' => 'De mislukte Varkensbaai-invasie, zijn buitenechtelijke affaires, de escalatie van de Amerikaanse betrokkenheid in Vietnam.',
        'citaten' => json_encode([
            '"Ask not what your country can do for you—ask what you can do for your country."',
            '"Forgive your enemies, but never forget their names."'
        ]),
        'monumenten_ter_ere' => 'John F. Kennedy Presidential Library and Museum, Kennedy Space Center, JFK International Airport, USS John F. Kennedy'
    ],
    
    // 36. Lyndon B. Johnson
    [
        'president_nummer' => 36,
        'naam' => 'Lyndon B. Johnson',
        'volledige_naam' => 'Lyndon Baines Johnson',
        'bijnaam' => 'LBJ',
        'partij' => 'Democratic',
        'periode_start' => '1963-11-22',
        'periode_eind' => '1969-01-20',
        'geboren' => '1908-08-27',
        'overleden' => '1973-01-22',
        'geboorteplaats' => 'Stonewall, Texas',
        'vice_president' => 'Hubert Humphrey (1965-1969)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c3/Lyndon_B._Johnson_in_1969.jpg',
        'biografie' => 'Lyndon B. Johnson was de zesendertigste president, die aantrad na de moord op Kennedy. Hij is vooral bekend om zijn "Great Society" wetgeving, die burgerrechten, gezondheidszorg en armoedebestrijding omvatte, en om de escalatie van de Vietnamoorlog, die zijn presidentschap overschaduwde.',
        'vroeg_leven' => 'Groeide op in de Texas Hill Country. Werkte als leraar voordat hij de politiek in ging.',
        'politieke_carriere' => 'U.S. House of Representatives, U.S. Senator (inclusief Majority Leader), vice-president.',
        'prestaties' => json_encode([
            'Ondertekende de Civil Rights Act van 1964 en de Voting Rights Act van 1965',
            'Creëerde Medicare en Medicaid',
            'Lanceerde de "War on Poverty"',
            'Ondertekende belangrijke wetgeving op het gebied van onderwijs, milieu en immigratie'
        ]),
        'fun_facts' => json_encode([
            'Stond bekend om de "Johnson Treatment": het gebruiken van zijn imposante gestalte en intimiderende persoonlijkheid om politici te overtuigen',
            'Werd beëdigd aan boord van Air Force One, enkele uren na de moord op Kennedy',
            'Hield ervan om interviews te geven terwijl hij op het toilet zat'
        ]),
        'echtgenote' => 'Claudia "Lady Bird" Alta Taylor Johnson',
        'kinderen' => json_encode(['Lynda Bird', 'Luci Baines']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 192,
        'gewicht_kg' => 91,
        'verkiezingsjaren' => json_encode([1964]),
        'leeftijd_bij_aantreden' => 55,
        'belangrijkste_gebeurtenissen' => 'Vietnamoorlog, Civil Rights Movement, moorden op Martin Luther King Jr. en Robert F. Kennedy (1968).',
        'bekende_speeches' => '"We Shall Overcome" speech voor het Congres.',
        'wetgeving' => json_encode(['Civil Rights Act of 1964', 'Voting Rights Act of 1965', 'Social Security Amendments of 1965 (Medicare/Medicaid)', 'Elementary and Secondary Education Act']),
        'oorlogen' => json_encode(['Vietnamoorlog']),
        'economische_situatie' => 'Sterke economische groei, maar toenemende inflatie door uitgaven aan de Great Society en de Vietnamoorlog.',
        'carrierre_voor_president' => 'Leraar, politicus, Senaatsleider, vice-president.',
        'carrierre_na_president' => 'Trok zich terug op zijn ranch in Texas en schreef zijn memoires.',
        'doodsoorzaak' => 'Hartaanval',
        'begrafenisplaats' => 'Johnson Family Cemetery, Stonewall, Texas',
        'historische_waardering' => 'Zeer gemengd; wordt hoog beoordeeld voor zijn monumentale binnenlandse prestaties, met name op het gebied van burgerrechten, maar zeer negatief beoordeeld voor zijn beleid in de Vietnamoorlog.',
        'controverses' => 'De escalatie van de Vietnamoorlog, de geloofwaardigheidskloof ("credibility gap") over de oorlog.',
        'citaten' => json_encode([
            '"Yesterday is not ours to recover, but tomorrow is ours to win or lose."'
        ]),
        'monumenten_ter_ere' => 'Lyndon B. Johnson Presidential Library and Museum, Lyndon B. Johnson Space Center'
    ],

    // 37. Richard Nixon
    [
        'president_nummer' => 37,
        'naam' => 'Richard Nixon',
        'volledige_naam' => 'Richard Milhous Nixon',
        'bijnaam' => 'Tricky Dick',
        'partij' => 'Republican',
        'periode_start' => '1969-01-20',
        'periode_eind' => '1974-08-09',
        'geboren' => '1913-01-09',
        'overleden' => '1994-04-22',
        'geboorteplaats' => 'Yorba Linda, California',
        'vice_president' => 'Spiro Agnew (1969-1973), Gerald Ford (1973-1974)',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Richard_Nixon_presidential_portrait.jpg',
        'biografie' => 'Richard Nixon was de zevenendertigste president. Hij is vooral bekend om het openen van de betrekkingen met China, het beëindigen van de Amerikaanse betrokkenheid in Vietnam en het Watergate-schandaal, dat leidde tot zijn aftreden, als enige president ooit.',
        'vroeg_leven' => 'Groeide op in een Quaker-gezin in Californië. Studeerde aan Duke Law School en diende in de marine tijdens WO II.',
        'politieke_carriere' => 'U.S. House of Representatives, U.S. Senator, vice-president onder Eisenhower.',
        'prestaties' => json_encode([
            'Opende diplomatieke betrekkingen met de Volksrepubliek China',
            'Onderhandelde over het SALT I-verdrag voor wapenbeheersing met de Sovjet-Unie',
            'Richtte de Environmental Protection Agency (EPA) op',
            'Beëindigde de Amerikaanse gevechtsrol in de Vietnamoorlog',
            'Hield toezicht op de maanlanding van Apollo 11'
        ]),
        'fun_facts' => json_encode([
            'Was een fervent bowler en liet een bowlingbaan installeren onder het Witte Huis',
            'Sprak met de astronauten op de maan via de telefoon',
            'Is de enige persoon die in 5 nationale verkiezingen op het ticket stond (2x als VP, 3x als President)'
        ]),
        'echtgenote' => 'Patricia "Pat" Ryan Nixon',
        'kinderen' => json_encode(['Tricia Nixon Cox', 'Julie Nixon Eisenhower']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 182,
        'gewicht_kg' => 78,
        'verkiezingsjaren' => json_encode([1968, 1972]),
        'leeftijd_bij_aantreden' => 56,
        'belangrijkste_gebeurtenissen' => 'Watergate-schandaal, bezoek aan China (1972), einde Vietnamoorlog, oliecrisis van 1973, zijn aftreden (1974).',
        'bekende_speeches' => 'Checkers speech, "I am not a crook" persconferentie, Farewell speech.',
        'wetgeving' => json_encode(['Clean Air Act', 'Clean Water Act', 'Endangered Species Act', 'Occupational Safety and Health Act (OSHA)']),
        'oorlogen' => json_encode(['Vietnamoorlog (beëindigd)']),
        'economische_situatie' => 'Gekenmerkt door "stagflatie" (stagnatie en inflatie), beëindigde de directe convertibiliteit van de dollar naar goud (Nixon Shock).',
        'carrierre_voor_president' => 'Advocaat, marineofficier, politicus, vice-president.',
        'carrierre_na_president' => 'Trok zich terug in Californië, schreef talrijke boeken over buitenlands beleid en werd een gerespecteerd ouder staatsman.',
        'doodsoorzaak' => 'Beroerte',
        'begrafenisplaats' => 'Richard Nixon Presidential Library and Museum, Yorba Linda, California',
        'historische_waardering' => 'Extreem gemengd en controversieel. Hoog beoordeeld voor zijn buitenlands beleid, maar voor altijd getekend door Watergate en zijn machtsmisbruik.',
        'controverses' => 'Watergate-schandaal en de daaropvolgende doofpotaffaire, geheime bombardementen op Cambodja, zijn "enemies list".',
        'citaten' => json_encode([
            '"I am not a crook."',
            '"Always remember, others may hate you, but those who hate you don\'t win unless you hate them, and then you destroy yourself."'
        ]),
        'monumenten_ter_ere' => 'Richard Nixon Presidential Library and Museum'
    ],
    
    // 38. Gerald Ford
    [
        'president_nummer' => 38,
        'naam' => 'Gerald Ford',
        'volledige_naam' => 'Gerald Rudolph Ford Jr. (geboren als Leslie Lynch King Jr.)',
        'bijnaam' => 'Jerry',
        'partij' => 'Republican',
        'periode_start' => '1974-08-09',
        'periode_eind' => '1977-01-20',
        'geboren' => '1913-07-14',
        'overleden' => '2006-12-26',
        'geboorteplaats' => 'Omaha, Nebraska',
        'vice_president' => 'Nelson Rockefeller',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/4f/Gerald_Ford_-_NCI_Visuals_Online.jpg',
        'biografie' => 'Gerald Ford was de achtendertigste president. Hij is de enige persoon die president werd zonder te zijn verkozen tot president of vice-president. Hij nam het ambt over na het aftreden van Nixon en probeerde de natie te helen na Watergate.',
        'vroeg_leven' => 'Sterspeler in het footballteam van de Universiteit van Michigan. Studeerde rechten aan Yale Law School en diende in de marine tijdens WO II.',
        'politieke_carriere' => 'U.S. House of Representatives (25 jaar, inclusief als Minority Leader), vice-president.',
        'prestaties' => json_encode([
            'Verleende gratie aan Richard Nixon, een controversiële maar volgens hem noodzakelijke stap om het land verder te laten gaan',
            'Ondertekende de Helsinki-akkoorden om de spanningen met de Sovjet-Unie te verminderen',
            'Hield toezicht op het einde van de Vietnamoorlog'
        ]),
        'fun_facts' => json_encode([
            'Enige president die de rang van Eagle Scout behaalde',
            'Werd na zijn geboorte geadopteerd en kreeg een nieuwe naam',
            'Overleefde twee moordaanslagen in 17 dagen, beide door vrouwen'
        ]),
        'echtgenote' => 'Elizabeth "Betty" Ann Bloomer Warren Ford',
        'kinderen' => json_encode(['Michael Gerald', 'John Gardner "Jack"', 'Steven Meigs', 'Susan Elizabeth']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 183,
        'gewicht_kg' => 89,
        'verkiezingsjaren' => json_encode(['Geen (opvolging)']),
        'leeftijd_bij_aantreden' => 61,
        'belangrijkste_gebeurtenissen' => 'Gratie aan Nixon (1974), Val van Saigon (1975), Helsinki-akkoorden (1975).',
        'bekende_speeches' => '"Our long national nightmare is over."',
        'wetgeving' => json_encode(['Education for All Handicapped Children Act of 1975']),
        'oorlogen' => json_encode(['Einde van de Vietnamoorlog']),
        'economische_situatie' => 'Kampte met hoge inflatie en een recessie; lanceerde de "Whip Inflation Now" (WIN) campagne.',
        'carrierre_voor_president' => 'Advocaat, marineofficier, congreslid, vice-president.',
        'carrierre_na_president' => 'Bleef actief in het publieke leven, hield lezingen en diende in commissies.',
        'doodsoorzaak' => 'Cerebrovasculaire ziekte',
        'begrafenisplaats' => 'Gerald R. Ford Presidential Museum, Grand Rapids, Michigan',
        'historische_waardering' => 'Positief; wordt herinnerd als een fatsoenlijke en integere man die het land hielp herstellen na de trauma\'s van Vietnam en Watergate.',
        'controverses' => 'De gratieverlening aan Richard Nixon was zeer controversieel en kostte hem waarschijnlijk de verkiezingen van 1976.',
        'citaten' => json_encode([
            '"I am a Ford, not a Lincoln."',
            '"A government big enough to give you everything you want is a government big enough to take from you everything you have."'
        ]),
        'monumenten_ter_ere' => 'Gerald R. Ford Presidential Library and Museum, USS Gerald R. Ford'
    ],

    // 39. Jimmy Carter
    [
        'president_nummer' => 39,
        'naam' => 'Jimmy Carter',
        'volledige_naam' => 'James Earl Carter Jr.',
        'bijnaam' => 'Jimmy',
        'partij' => 'Democratic',
        'periode_start' => '1977-01-20',
        'periode_eind' => '1981-01-20',
        'geboren' => '1924-10-01',
        'overleden' => null,
        'geboorteplaats' => 'Plains, Georgia',
        'vice_president' => 'Walter Mondale',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Jimmy_Carter%2C_official_Presidential_portrait.jpg',
        'biografie' => 'Jimmy Carter was de negenendertigste president. Een voormalige pindaboer en gouverneur, zijn presidentschap werd gekenmerkt door inspanningen voor vrede in het Midden-Oosten, een focus op mensenrechten, en ernstige economische problemen en de gijzelingscrisis in Iran.',
        'vroeg_leven' => 'Groeide op op een pindaboerderij in Georgia. Studeerde aan de U.S. Naval Academy en diende in de marine op onderzeeërs.',
        'politieke_carriere' => 'Georgia State Senator, Gouverneur van Georgia.',
        'prestaties' => json_encode([
            'Bemiddelde bij de Camp David-akkoorden tussen Egypte en Israël',
            'Ondertekende de Panamakanaalverdragen om het kanaal terug te geven aan Panama',
            'Creëerde de ministeries van Energie en Onderwijs',
            'Maakte mensenrechten een centraal onderdeel van het Amerikaanse buitenlandbeleid'
        ]),
        'fun_facts' => json_encode([
            'Eerste president die in een ziekenhuis werd geboren',
            'Is een fervent meubelmaker en timmerman',
            'Won de Nobelprijs voor de Vrede in 2002 voor zijn decennialange inzet voor vrede en mensenrechten',
            'Langstlevende president in de Amerikaanse geschiedenis'
        ]),
        'echtgenote' => 'Eleanor Rosalynn Smith Carter',
        'kinderen' => json_encode(['John William "Jack"', 'James Earl "Chip" III', 'Donnel Jeffrey "Jeff"', 'Amy Lynn']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 177,
        'gewicht_kg' => 71,
        'verkiezingsjaren' => json_encode([1976]),
        'leeftijd_bij_aantreden' => 52,
        'belangrijkste_gebeurtenissen' => 'Camp David-akkoorden (1978), Gijzeling in Iran (1979-1981), Energiecrisis, Sovjet-invasie van Afghanistan (1979).',
        'bekende_speeches' => '"Crisis of Confidence" (Malaise) speech.',
        'wetgeving' => json_encode(['Panama Canal Treaties', 'Airline Deregulation Act']),
        'oorlogen' => json_encode(['Koude Oorlog']),
        'economische_situatie' => 'Gekenmerkt door hoge inflatie, hoge werkloosheid en een energiecrisis ("stagflatie").',
        'carrierre_voor_president' => 'Marineofficier, pindaboer, politicus.',
        'carrierre_na_president' => 'Een van de meest actieve ex-presidenten; richtte The Carter Center op, zet zich in voor democratie, mensenrechten en de uitroeiing van ziekten.',
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Zijn presidentschap wordt vaak als mislukt gezien vanwege de economie en de gijzelingscrisis, maar zijn reputatie als ex-president is uitstekend en heeft geleid tot een herwaardering van zijn presidentschap, met name zijn focus op vrede en mensenrechten.',
        'controverses' => 'Zijn aanpak van de Iraanse gijzelingscrisis, de economische malaise.',
        'citaten' => json_encode([
            '"We should live our lives as though Christ were coming this afternoon."'
        ]),
        'monumenten_ter_ere' => 'Jimmy Carter Library and Museum, The Carter Center'
    ],
    
    // 40. Ronald Reagan
    [
        'president_nummer' => 40,
        'naam' => 'Ronald Reagan',
        'volledige_naam' => 'Ronald Wilson Reagan',
        'bijnaam' => 'The Gipper, The Great Communicator',
        'partij' => 'Republican',
        'periode_start' => '1981-01-20',
        'periode_eind' => '1989-01-20',
        'geboren' => '1911-02-06',
        'overleden' => '2004-06-05',
        'geboorteplaats' => 'Tampico, Illinois',
        'vice_president' => 'George H. W. Bush',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/16/Official_Portrait_of_President_Reagan_1981.jpg',
        'biografie' => 'Ronald Reagan was de veertigste president. Een voormalig acteur en gouverneur, zijn presidentschap zag een conservatieve revolutie, gekenmerkt door belastingverlagingen ("Reaganomics"), een sterke defensieopbouw en een agressieve houding tegenover de Sovjet-Unie, wat velen zien als een bijdrage aan het einde van de Koude Oorlog.',
        'vroeg_leven' => 'Groeide op in Illinois. Werkte als sportverslaggever op de radio voordat hij een succesvolle Hollywood-acteur werd.',
        'politieke_carriere' => 'President van de Screen Actors Guild, Gouverneur van Californië.',
        'prestaties' => json_encode([
            'Implementeerde "Reaganomics" (belastingverlagingen en deregulering)',
            'Versnelde het einde van de Koude Oorlog door diplomatie en militaire druk',
            'Herstelde het Amerikaanse optimisme en zelfvertrouwen',
            'Overleefde een moordaanslag in 1981'
        ]),
        'fun_facts' => json_encode([
            'Oudste president bij zijn verkiezing op dat moment (69 jaar)',
            'Hield van jelly beans; er stond altijd een pot op zijn bureau',
            'Redde 77 mensen als strandwacht in zijn jeugd',
            'Enige president die gescheiden is geweest'
        ]),
        'echtgenote' => 'Jane Wyman, Nancy Davis Reagan',
        'kinderen' => json_encode(['Maureen', 'Michael (geadopteerd)', 'Patti', 'Ron']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 185,
        'gewicht_kg' => 84,
        'verkiezingsjaren' => json_encode([1980, 1984]),
        'leeftijd_bij_aantreden' => 69,
        'belangrijkste_gebeurtenissen' => 'Einde Koude Oorlog, Iran-Contra-affaire, moordaanslag (1981), bombardement op Libië (1986).',
        'bekende_speeches' => '"Mr. Gorbachev, tear down this wall!", "A Time for Choosing", Challenger ramp toespraak.',
        'wetgeving' => json_encode(['Economic Recovery Tax Act of 1981', 'Tax Reform Act of 1986', 'Immigration Reform and Control Act of 1986']),
        'oorlogen' => json_encode(['Koude Oorlog, Invasie van Grenada (1983)']),
        'economische_situatie' => 'Aanvankelijk een diepe recessie, gevolgd door een lange periode van economische groei, maar ook een enorme toename van de staatsschuld.',
        'carrierre_voor_president' => 'Acteur, vakbondsleider, gouverneur.',
        'carrierre_na_president' => 'Trok zich terug in Californië. In 1994 maakte hij bekend dat hij aan de ziekte van Alzheimer leed.',
        'doodsoorzaak' => 'Longontsteking, als complicatie van de ziekte van Alzheimer',
        'begrafenisplaats' => 'Ronald Reagan Presidential Library, Simi Valley, California',
        'historische_waardering' => 'Zeer hoog, vooral bij conservatieven. Hij wordt gezien als een van de meest invloedrijke presidenten van de 20e eeuw, die het politieke landschap permanent veranderde.',
        'controverses' => 'De Iran-Contra-affaire, de enorme stijging van de staatsschuld, zijn aanvankelijk trage reactie op de AIDS-crisis.',
        'citaten' => json_encode([
            '"Mr. Gorbachev, tear down this wall!"',
            '"The nine most terrifying words in the English language are: I\'m from the government and I\'m here to help."'
        ]),
        'monumenten_ter_ere' => 'Ronald Reagan Presidential Library, Ronald Reagan Washington National Airport, USS Ronald Reagan'
    ],
    
    // 41. George H. W. Bush
    [
        'president_nummer' => 41,
        'naam' => 'George H. W. Bush',
        'volledige_naam' => 'George Herbert Walker Bush',
        'bijnaam' => 'Poppy, 41',
        'partij' => 'Republican',
        'periode_start' => '1989-01-20',
        'periode_eind' => '1993-01-20',
        'geboren' => '1924-06-12',
        'overleden' => '2018-11-30',
        'geboorteplaats' => 'Milton, Massachusetts',
        'vice_president' => 'Dan Quayle',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0f/George_H._W._Bush_presidential_portrait.jpg',
        'biografie' => 'George H. W. Bush was de eenenveertigste president. Zijn presidentschap werd gedomineerd door buitenlands beleid, waaronder het einde van de Koude Oorlog, de val van de Berlijnse Muur en de Golfoorlog. Binnenlands kampte hij met een economische recessie.',
        'vroeg_leven' => 'Zoon van een senator. Jongste marinepiloot in WO II, werd neergeschoten boven de Stille Oceaan. Studeerde aan Yale en werd succesvol in de olie-industrie in Texas.',
        'politieke_carriere' => 'U.S. House of Representatives, Ambassadeur bij de VN, Voorzitter van de RNC, Hoofd van de U.S. Liaison Office in China, Directeur van de CIA, vice-president.',
        'prestaties' => json_encode([
            'Leidde een internationale coalitie in de Golfoorlog om Koeweit te bevrijden',
            'Hield toezicht op het einde van de Koude Oorlog en de hereniging van Duitsland',
            'Ondertekende de Americans with Disabilities Act (ADA)',
            'Ondertekende de Clean Air Act Amendments van 1990'
        ]),
        'fun_facts' => json_encode([
            'Vierde zijn 75e, 80e, 85e en 90e verjaardag met een parachutesprong',
            'Hield niet van broccoli en verbood het op Air Force One',
            'Overleefde vier vliegtuigcrashes in zijn leven'
        ]),
        'echtgenote' => 'Barbara Pierce Bush',
        'kinderen' => json_encode(['George W. (43e president)', 'Robin (stierf jong)', 'Jeb (gouverneur van Florida)', 'Neil', 'Marvin', 'Dorothy']),
        'familie_connecties' => 'Zoon van een senator, vader van George W. Bush (43e president).',
        'lengte_cm' => 188,
        'gewicht_kg' => 88,
        'verkiezingsjaren' => json_encode([1988]),
        'leeftijd_bij_aantreden' => 64,
        'belangrijkste_gebeurtenissen' => 'Val van de Berlijnse Muur (1989), Golfoorlog (1990-1991), Ineenstorting van de Sovjet-Unie (1991), economische recessie.',
        'bekende_speeches' => '"Read my lips: no new taxes." (later gebroken belofte).',
        'wetgeving' => json_encode(['Americans with Disabilities Act of 1990', 'Clean Air Act Amendments of 1990']),
        'oorlogen' => json_encode(['Golfoorlog', 'Invasie van Panama (1989)']),
        'economische_situatie' => 'Een economische recessie aan het einde van zijn termijn droeg bij aan zijn verlies in 1992.',
        'carrierre_voor_president' => 'Zeer uitgebreide carrière in het bedrijfsleven, diplomatie, inlichtingendiensten en politiek.',
        'carrierre_na_president' => 'Werkte samen met Bill Clinton aan humanitaire projecten, zoals hulp na de tsunami in 2004 en orkaan Katrina.',
        'doodsoorzaak' => 'Complicaties gerelateerd aan de ziekte van Parkinson',
        'begrafenisplaats' => 'George H.W. Bush Presidential Library and Museum, College Station, Texas',
        'historische_waardering' => 'Hoog beoordeeld voor zijn bekwame en voorzichtige beheer van het einde van de Koude Oorlog en zijn succes in de Golfoorlog. Zijn binnenlands beleid wordt als minder succesvol gezien.',
        'controverses' => 'Het breken van zijn "no new taxes" belofte, de gratieverlening aan functionarissen die betrokken waren bij de Iran-Contra-affaire.',
        'citaten' => json_encode([
            '"Read my lips: no new taxes."'
        ]),
        'monumenten_ter_ere' => 'George H.W. Bush Presidential Library and Museum, George Bush Intercontinental Airport, USS George H.W. Bush'
    ],
    
    // 42. Bill Clinton
    [
        'president_nummer' => 42,
        'naam' => 'Bill Clinton',
        'volledige_naam' => 'William Jefferson Clinton (geboren als William Jefferson Blythe III)',
        'bijnaam' => 'Bubba, The Comeback Kid',
        'partij' => 'Democratic',
        'periode_start' => '1993-01-20',
        'periode_eind' => '2001-01-20',
        'geboren' => '1946-08-19',
        'overleden' => null,
        'geboorteplaats' => 'Hope, Arkansas',
        'vice_president' => 'Al Gore',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Bill_Clinton.jpg',
        'biografie' => 'Bill Clinton was de tweeënveertigste president. Hij was de eerste president van de babyboomgeneratie. Zijn presidentschap zag de langste periode van economische expansie in de Amerikaanse geschiedenis, maar werd ook ontsierd door schandalen en een afzettingsprocedure.',
        'vroeg_leven' => 'Groeide op in Arkansas. Studeerde aan Georgetown, Oxford (als Rhodes Scholar) en Yale Law School.',
        'politieke_carriere' => 'Procureur-generaal van Arkansas, Gouverneur van Arkansas.',
        'prestaties' => json_encode([
            'Hield toezicht op een periode van sterke economische groei en een begrotingsoverschot',
            'Ondertekende de North American Free Trade Agreement (NAFTA)',
            'Hervormde het welzijnssysteem',
            'Bemiddelde in de vredesbesprekingen in Noord-Ierland en de Balkan'
        ]),
        'fun_facts' => json_encode([
            'Speelt saxofoon en trad op in "The Arsenio Hall Show" tijdens zijn campagne',
            'Is allergisch voor katten, ondanks het hebben van de beroemde kat "Socks" in het Witte Huis',
            'Tweede president die werd afgezet (impeached) door het Huis (vrijgesproken door de Senaat)'
        ]),
        'echtgenote' => 'Hillary Rodham Clinton',
        'kinderen' => json_encode(['Chelsea Clinton']),
        'familie_connecties' => 'Zijn vrouw, Hillary Clinton, werd later U.S. Senator, Secretary of State en presidentskandidaat.',
        'lengte_cm' => 188,
        'gewicht_kg' => 100,
        'verkiezingsjaren' => json_encode([1992, 1996]),
        'leeftijd_bij_aantreden' => 46,
        'belangrijkste_gebeurtenissen' => 'NAFTA (1994), Oklahoma City bombardement (1995), Bomaanslag op het World Trade Center (1993), Impeachment (1998-1999).',
        'bekende_speeches' => '"I did not have sexual relations with that woman."',
        'wetgeving' => json_encode(['North American Free Trade Agreement (NAFTA)', 'Violent Crime Control and Law Enforcement Act', 'Personal Responsibility and Work Opportunity Act (welfare reform)']),
        'oorlogen' => json_encode(['NAVO-bombardementen op Joegoslavië (Kosovo-oorlog)']),
        'economische_situatie' => 'De langste periode van economische expansie in vredestijd, gekenmerkt door lage werkloosheid, lage inflatie en een begrotingsoverschot.',
        'carrierre_voor_president' => 'Advocaat, professor, procureur-generaal, gouverneur.',
        'carrierre_na_president' => 'Richtte de Clinton Foundation op, een wereldwijde filantropische organisatie, en blijft een invloedrijke stem in de Democratische partij.',
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Gemengd maar over het algemeen positief. Geprezen voor zijn economisch beleid en zijn vermogen om met het volk te communiceren, maar bekritiseerd voor zijn persoonlijke schandalen die leidden tot zijn afzetting.',
        'controverses' => 'Whitewater-onderzoek, Paula Jones-zaak, Monica Lewinsky-schandaal en de daaropvolgende afzettingsprocedure.',
        'citaten' => json_encode([
            '"There is nothing wrong with America that cannot be cured by what is right with America."'
        ]),
        'monumenten_ter_ere' => 'William J. Clinton Presidential Center and Park, Clinton National Airport'
    ],
    
    // 43. George W. Bush
    [
        'president_nummer' => 43,
        'naam' => 'George W. Bush',
        'volledige_naam' => 'George Walker Bush',
        'bijnaam' => 'Dubya, 43',
        'partij' => 'Republican',
        'periode_start' => '2001-01-20',
        'periode_eind' => '2009-01-20',
        'geboren' => '1946-07-06',
        'overleden' => null,
        'geboorteplaats' => 'New Haven, Connecticut',
        'vice_president' => 'Dick Cheney',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/d4/George-W-Bush.jpeg',
        'biografie' => 'George W. Bush was de drieënveertigste president. Zijn presidentschap werd gedefinieerd door de terroristische aanslagen van 11 september 2001, die leidden tot de "War on Terror", de invasie van Afghanistan en de oorlog in Irak.',
        'vroeg_leven' => 'Oudste zoon van president George H.W. Bush. Studeerde aan Yale en Harvard Business School. Werkte in de olie-industrie en was mede-eigenaar van het Texas Rangers honkbalteam.',
        'politieke_carriere' => 'Gouverneur van Texas.',
        'prestaties' => json_encode([
            'Leidde het land na de 9/11-aanslagen',
            'Lanceerde de "War on Terror"',
            'Creëerde het Department of Homeland Security',
            'Implementeerde grote belastingverlagingen en de "No Child Left Behind" onderwijswet',
            'Creëerde PEPFAR, een programma ter bestrijding van HIV/AIDS in Afrika'
        ]),
        'fun_facts' => json_encode([
            'Enige president met een MBA-diploma',
            'Is een fervent hardloper en voltooide een marathon',
            'Heeft na zijn presidentschap schilderen als hobby opgepakt'
        ]),
        'echtgenote' => 'Laura Lane Welch Bush',
        'kinderen' => json_encode(['Barbara Pierce', 'Jenna Welch']),
        'familie_connecties' => 'Zoon van George H.W. Bush (41e president).',
        'lengte_cm' => 182,
        'gewicht_kg' => 86,
        'verkiezingsjaren' => json_encode([2000, 2004]),
        'leeftijd_bij_aantreden' => 54,
        'belangrijkste_gebeurtenissen' => '9/11 terroristische aanslagen (2001), Oorlog in Afghanistan (2001-heden), Oorlog in Irak (2003-2011), Orkaan Katrina (2005), Financiële crisis van 2008.',
        'bekende_speeches' => 'Toespraak op Ground Zero met een megafoon, "Axis of Evil" State of the Union.',
        'wetgeving' => json_encode(['No Child Left Behind Act', 'Patriot Act', 'Medicare Part D (prescription drug benefit)']),
        'oorlogen' => json_encode(['Oorlog in Afghanistan', 'Oorlog in Irak']),
        'economische_situatie' => 'Gekenmerkt door belastingverlagingen, gevolgd door de ernstige financiële crisis van 2008 en het begin van de Grote Recessie.',
        'carrierre_voor_president' => 'Zakenman, eigenaar honkbalteam, gouverneur.',
        'carrierre_na_president' => 'Trok zich terug in Texas, richtte het George W. Bush Presidential Center op en houdt zich bezig met schilderen en humanitair werk.',
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Zeer controversieel en gemengd. Geprezen voor zijn leiderschap direct na 9/11, maar zwaar bekritiseerd voor de oorlog in Irak, de reactie op orkaan Katrina en de financiële crisis.',
        'controverses' => 'De omstreden verkiezing van 2000, de beslissing om Irak binnen te vallen op basis van onjuiste informatie over massavernietigingswapens, het gebruik van "enhanced interrogation techniques" (marteling).',
        'citaten' => json_encode([
            '"I hear you. The rest of the world hears you. And the people who knocked these buildings down will hear all of us soon."'
        ]),
        'monumenten_ter_ere' => 'George W. Bush Presidential Center'
    ],
    
    // 44. Barack Obama
    [
        'president_nummer' => 44,
        'naam' => 'Barack Obama',
        'volledige_naam' => 'Barack Hussein Obama II',
        'bijnaam' => 'No Drama Obama',
        'partij' => 'Democratic',
        'periode_start' => '2009-01-20',
        'periode_eind' => '2017-01-20',
        'geboren' => '1961-08-04',
        'overleden' => null,
        'geboorteplaats' => 'Honolulu, Hawaii',
        'vice_president' => 'Joe Biden',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8d/President_Barack_Obama.jpg',
        'biografie' => 'Barack Obama was de vierenveertigste president en de eerste Afro-Amerikaanse president. Hij trad aan tijdens de Grote Recessie en zijn presidentschap zag de passage van de Affordable Care Act, het einde van de oorlog in Irak en de inval waarbij Osama bin Laden werd gedood.',
        'vroeg_leven' => 'Geboren in Hawaï als zoon van een moeder uit Kansas en een vader uit Kenia. Groeide op in Indonesië en Hawaï. Studeerde aan Columbia University en Harvard Law School, waar hij de eerste Afro-Amerikaanse president van de Harvard Law Review was.',
        'politieke_carriere' => 'Illinois State Senator, U.S. Senator.',
        'prestaties' => json_encode([
            'Ondertekende de Affordable Care Act (Obamacare)',
            'Hield toezicht op het herstel van de Grote Recessie',
            'Autoriseerde de inval die leidde tot de dood van Osama bin Laden',
            'Beëindigde de oorlog in Irak en herstelde de diplomatieke betrekkingen met Cuba',
            'Won de Nobelprijs voor de Vrede in 2009'
        ]),
        'fun_facts' => json_encode([
            'Verzamelt Spider-Man en Conan the Barbarian stripboeken',
            'Zijn naam, Barack, betekent "gezegende" in het Swahili',
            'Kan basketbal spelen en liet een basketbalveld aanleggen op het Witte Huis-terrein',
            'Eerste president die sociale media op grote schaal gebruikte'
        ]),
        'echtgenote' => 'Michelle LaVaughn Robinson Obama',
        'kinderen' => json_encode(['Malia Ann', 'Natasha "Sasha"']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 185,
        'gewicht_kg' => 79,
        'verkiezingsjaren' => json_encode([2008, 2012]),
        'leeftijd_bij_aantreden' => 47,
        'belangrijkste_gebeurtenissen' => 'Grote Recessie, Affordable Care Act (2010), dood van Osama bin Laden (2011), herstel van de betrekkingen met Cuba (2014), nucleaire deal met Iran (2015).',
        'bekende_speeches' => 'Keynote speech op de Democratic National Convention (2004), "A More Perfect Union" speech over ras, overwinningsspeech in 2008.',
        'wetgeving' => json_encode(['Affordable Care Act (ACA)', 'American Recovery and Reinvestment Act of 2009', 'Dodd-Frank Wall Street Reform and Consumer Protection Act']),
        'oorlogen' => json_encode(['Oorlog in Afghanistan (voortgezet), einde oorlog in Irak, militaire interventie in Libië (2011)']),
        'economische_situatie' => 'Gedomineerd door de Grote Recessie en het daaropvolgende langzame maar gestage economische herstel.',
        'carrierre_voor_president' => 'Community organizer, burgerrechtenadvocaat, professor, politicus.',
        'carrierre_na_president' => 'Richtte de Obama Foundation op, die zich richt op wereldwijde kwesties en het inspireren van de volgende generatie leiders; produceert documentaires en podcasts.',
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Over het algemeen positief. Geprezen voor zijn kalme leiderschap tijdens crises, zijn historische verkiezing en de Affordable Care Act. Bekritiseerd door sommigen voor de omvang van de overheid en het buitenlands beleid.',
        'controverses' => 'De Affordable Care Act, het gebruik van drones, het NSA-surveillanceschandaal onthuld door Edward Snowden.',
        'citaten' => json_encode([
            '"Yes we can."',
            '"Change will not come if we wait for some other person or some other time. We are the ones we\'ve been waiting for. We are the change that we seek."'
        ]),
        'monumenten_ter_ere' => 'Barack Obama Presidential Center (in aanbouw)'
    ],

    // 45. Donald Trump
    [
        'president_nummer' => 45,
        'naam' => 'Donald Trump',
        'volledige_naam' => 'Donald John Trump',
        'bijnaam' => 'The Donald',
        'partij' => 'Republican',
        'periode_start' => '2017-01-20',
        'periode_eind' => '2021-01-20',
        'geboren' => '1946-06-14',
        'overleden' => null,
        'geboorteplaats' => 'Queens, New York',
        'vice_president' => 'Mike Pence',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/56/Donald_Trump_official_portrait.jpg',
        'biografie' => 'Donald J. Trump was de vijfenveertigste president. Een vastgoedmagnaat en reality-tv-ster, zijn onconventionele presidentschap werd gekenmerkt door een "America First" beleid, belastingverlagingen, deregulering, een harde lijn tegen immigratie, en een tumultueuze relatie met de media en traditionele politieke normen.',
        'vroeg_leven' => 'Zoon van een vastgoedontwikkelaar. Studeerde aan de Wharton School van de University of Pennsylvania en nam het familiebedrijf over, dat hij uitbouwde tot een wereldwijd merk.',
        'politieke_carriere' => 'Geen politieke of militaire ervaring voor het presidentschap.',
        'prestaties' => json_encode([
            'Ondertekende de Tax Cuts and Jobs Act of 2017',
            'Benoemde drie conservatieve rechters in het Hooggerechtshof',
            'Bemiddelde bij de Abraham-akkoorden, die de betrekkingen tussen Israël en verschillende Arabische landen normaliseerden',
            'Lanceerde de "First Step Act" voor strafrechthervorming'
        ]),
        'fun_facts' => json_encode([
            'Eerste president zonder enige voorafgaande overheids- of militaire ervaring',
            'Is een teetotaler (drinkt geen alcohol)',
            'Heeft zijn eigen ster op de Hollywood Walk of Fame'
        ]),
        'echtgenote' => 'Ivana Zelníčková, Marla Maples, Melania Knauss',
        'kinderen' => json_encode(['Donald Jr.', 'Ivanka', 'Eric', 'Tiffany', 'Barron']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 190,
        'gewicht_kg' => 110,
        'verkiezingsjaren' => json_encode([2016]),
        'leeftijd_bij_aantreden' => 70,
        'belangrijkste_gebeurtenissen' => 'Twee afzettingsprocedures (impeachments), COVID-19 pandemie, handelsoorlog met China, bestorming van het Capitool op 6 januari 2021.',
        'bekende_speeches' => 'Campagnetoespraken, gebruik van Twitter, "Make America Great Again" slogan.',
        'wetgeving' => json_encode(['Tax Cuts and Jobs Act of 2017', 'First Step Act']),
        'oorlogen' => json_encode(['Terugtrekking uit Syrië, dood van ISIS-leider al-Baghdadi']),
        'economische_situatie' => 'Sterke economische groei en lage werkloosheid voor de COVID-19 pandemie, die een scherpe recessie veroorzaakte.',
        'carrierre_voor_president' => 'Vastgoedontwikkelaar, zakenman, reality-tv-persoonlijkheid.',
        'carrierre_na_president' => 'Blijft een dominante figuur in de Republikeinse partij, organiseert rally\'s en is actief op sociale media.',
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Extreem polariserend en controversieel. Zijn aanhangers prijzen hem voor het opschudden van de politiek en zijn "America First" beleid. Critici veroordelen zijn retoriek, zijn aanvallen op democratische instellingen en zijn reactie op de COVID-19 pandemie.',
        'controverses' => 'Talloze, waaronder de onderzoeken naar Russische inmenging in de verkiezing van 2016, twee afzettingsprocedures, zijn pogingen om de verkiezingsuitslag van 2020 ongedaan te maken, en de bestorming van het Capitool.',
        'citaten' => json_encode([
            '"Make America Great Again."',
            '"You\'re fired!"'
        ]),
        'monumenten_ter_ere' => 'Geen officiële monumenten; diverse gebouwen dragen zijn naam.'
    ],

    // 46. Joe Biden
    [
        'president_nummer' => 46,
        'naam' => 'Joe Biden',
        'volledige_naam' => 'Joseph Robinette Biden Jr.',
        'bijnaam' => 'Amtrak Joe',
        'partij' => 'Democratic',
        'periode_start' => '2021-01-20',
        'periode_eind' => null, // Huidige president
        'geboren' => '1942-11-20',
        'overleden' => null,
        'geboorteplaats' => 'Scranton, Pennsylvania',
        'vice_president' => 'Kamala Harris',
        'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/68/Joe_Biden_presidential_portrait.jpg',
        'biografie' => 'Joe Biden is de zesenveertigste en huidige president van de Verenigde Staten. Hij trad aan met de belofte de natie te verenigen en de COVID-19 pandemie te bestrijden. Zijn presidentschap richt zich op grote investeringen in infrastructuur, klimaatverandering en sociale programma\'s, en het herstellen van internationale allianties.',
        'vroeg_leven' => 'Groeide op in Scranton, Pennsylvania, en Wilmington, Delaware. Overwon een stotterprobleem in zijn jeugd. Studeerde aan de University of Delaware en Syracuse University Law School.',
        'politieke_carriere' => 'County Councilman, U.S. Senator uit Delaware (36 jaar), vice-president onder Barack Obama.',
        'prestaties' => json_encode([
            'Ondertekende de American Rescue Plan om de COVID-19 crisis aan te pakken',
            'Ondertekende de Bipartisan Infrastructure Law',
            'Leidde de internationale reactie op de Russische invasie van Oekraïne',
            'Ondertekende de Inflation Reduction Act, met investeringen in klimaat en gezondheidszorg'
        ]),
        'fun_facts' => json_encode([
            'Stond bekend om zijn dagelijkse treinreizen van Delaware naar Washington D.C. tijdens zijn senaatscarrière ("Amtrak Joe")',
            'Is een liefhebber van ijs, met name vanille',
            'Oudste persoon die tot president van de VS is gekozen (78 jaar)'
        ]),
        'echtgenote' => 'Neilia Hunter Biden (overleden), Jill Tracy Jacobs Biden',
        'kinderen' => json_encode(['Beau Biden (overleden)', 'Hunter Biden', 'Naomi Christina Biden (overleden)', 'Ashley Blazer']),
        'familie_connecties' => 'Geen',
        'lengte_cm' => 182,
        'gewicht_kg' => 80,
        'verkiezingsjaren' => json_encode([2020]),
        'leeftijd_bij_aantreden' => 78,
        'belangrijkste_gebeurtenissen' => 'COVID-19 pandemie en vaccinatiecampagne, Terugtrekking uit Afghanistan (2021), Russische invasie van Oekraïne (2022).',
        'bekende_speeches' => 'Inaugural Address, State of the Union toespraken.',
        'wetgeving' => json_encode(['American Rescue Plan Act', 'Infrastructure Investment and Jobs Act', 'Inflation Reduction Act', 'CHIPS and Science Act']),
        'oorlogen' => json_encode(['Steun aan Oekraïne in de Russisch-Oekraïense oorlog']),
        'economische_situatie' => 'Herstel van de COVID-19 recessie, gevolgd door hoge inflatie en inspanningen om deze te bestrijden.',
        'carrierre_voor_president' => 'Advocaat, politicus, senator, vice-president.',
        'carrierre_na_president' => null,
        'doodsoorzaak' => null,
        'begrafenisplaats' => null,
        'historische_waardering' => 'Nog te bepalen, aangezien hij de huidige president is.',
        'controverses' => 'De chaotische terugtrekking uit Afghanistan, de hoge inflatie, de zakelijke transacties van zijn zoon Hunter Biden.',
        'citaten' => json_encode([
            '"It\'s never a good bet to bet against America."'
        ]),
        'monumenten_ter_ere' => 'Nog niet van toepassing.'
    ]
];

// Voeg meer presidenten toe (voor nu beperkt aantal voor test)
// Later kunnen we alle 46 toevoegen

echo "Database connectie testen...\n";
try {
    $db->query("SELECT 1");
    echo "✅ Database connectie succesvol\n\n";
} catch (Exception $e) {
    echo "❌ Database connectie gefaald: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Presidenten tabel controleren...\n";
try {
    $db->query("DESCRIBE amerikaanse_presidenten");
    $result = $db->resultSet();
    if (empty($result)) {
        echo "⚠️  Tabel 'amerikaanse_presidenten' bestaat niet. Maak eerst de tabel aan.\n";
        echo "Run: mysql -u username -p database < database/migrations/create_amerikaanse_presidenten_table.sql\n";
        exit(1);
    }
    echo "✅ Tabel amerikaanse_presidenten gevonden\n\n";
} catch (Exception $e) {
    echo "⚠️  Kan tabel niet controleren: " . $e->getMessage() . "\n";
    echo "Maak eerst de tabel aan met de migratie.\n";
    exit(1);
}

echo "Bestaande presidenten controleren...\n";
$db->query("SELECT COUNT(*) as count FROM amerikaanse_presidenten");
$existing = $db->single();
echo "Huidige aantal presidenten in database: " . $existing->count . "\n\n";

if ($existing->count > 0) {
    echo "Er zijn al presidenten in de database. Wil je doorgaan? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) != 'y') {
        echo "Geannuleerd.\n";
        exit(0);
    }
    fclose($handle);
}

echo "Presidenten toevoegen...\n";
echo str_repeat("-", 50) . "\n";

$added = 0;
$errors = 0;

foreach ($presidenten as $president) {
    try {
        // Check of president al bestaat
        $db->query("SELECT id FROM amerikaanse_presidenten WHERE president_nummer = ?");
        $db->bind(1, $president['president_nummer']);
        $existing_president = $db->single();
        
        if ($existing_president) {
            echo "⚠️  President #{$president['president_nummer']} {$president['naam']} bestaat al, wordt overgeslagen\n";
            continue;
        }
        
        // Insert president
        $sql = "INSERT INTO amerikaanse_presidenten (
            president_nummer, naam, volledige_naam, bijnaam, partij, periode_start, periode_eind,
            geboren, overleden, geboorteplaats, vice_president, foto_url, biografie, vroeg_leven,
            politieke_carriere, prestaties, fun_facts, echtgenote, kinderen, familie_connecties,
            lengte_cm, gewicht_kg, verkiezingsjaren, leeftijd_bij_aantreden, belangrijkste_gebeurtenissen,
            bekende_speeches, wetgeving, oorlogen, economische_situatie, carrierre_voor_president,
            carrierre_na_president, doodsoorzaak, begrafenisplaats, historische_waardering,
            controverses, citaten, monumenten_ter_ere
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $db->query($sql);
        $db->bind(1, $president['president_nummer']);
        $db->bind(2, $president['naam']);
        $db->bind(3, $president['volledige_naam']);
        $db->bind(4, $president['bijnaam']);
        $db->bind(5, $president['partij']);
        $db->bind(6, $president['periode_start']);
        $db->bind(7, $president['periode_eind']);
        $db->bind(8, $president['geboren']);
        $db->bind(9, $president['overleden']);
        $db->bind(10, $president['geboorteplaats']);
        $db->bind(11, $president['vice_president']);
        $db->bind(12, $president['foto_url']);
        $db->bind(13, $president['biografie']);
        $db->bind(14, $president['vroeg_leven']);
        $db->bind(15, $president['politieke_carriere']);
        $db->bind(16, $president['prestaties']);
        $db->bind(17, $president['fun_facts']);
        $db->bind(18, $president['echtgenote']);
        $db->bind(19, $president['kinderen']);
        $db->bind(20, $president['familie_connecties']);
        $db->bind(21, $president['lengte_cm']);
        $db->bind(22, $president['gewicht_kg']);
        $db->bind(23, $president['verkiezingsjaren']);
        $db->bind(24, $president['leeftijd_bij_aantreden']);
        $db->bind(25, $president['belangrijkste_gebeurtenissen']);
        $db->bind(26, $president['bekende_speeches']);
        $db->bind(27, $president['wetgeving']);
        $db->bind(28, $president['oorlogen']);
        $db->bind(29, $president['economische_situatie']);
        $db->bind(30, $president['carrierre_voor_president']);
        $db->bind(31, $president['carrierre_na_president']);
        $db->bind(32, $president['doodsoorzaak']);
        $db->bind(33, $president['begrafenisplaats']);
        $db->bind(34, $president['historische_waardering']);
        $db->bind(35, $president['controverses']);
        $db->bind(36, $president['citaten']);
        $db->bind(37, $president['monumenten_ter_ere']);
        
        if ($db->execute()) {
            echo "✅ {$president['naam']} (#{$president['president_nummer']}) toegevoegd\n";
            $added++;
        } else {
            echo "❌ Fout bij toevoegen {$president['naam']}\n";
            $errors++;
        }
        
    } catch (Exception $e) {
        echo "❌ Fout bij {$president['naam']}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo str_repeat("-", 50) . "\n";
echo "✅ {$added} presidenten succesvol toegevoegd\n";
if ($errors > 0) {
    echo "❌ {$errors} fouten opgetreden\n";
}

echo "\n🎉 Amerikaanse Presidenten database populatie voltooid!\n";
echo "Je kunt nu de Presidents Gallery bekijken op de website.\n";
