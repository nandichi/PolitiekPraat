<?php
require_once 'includes/Database.php';
require_once 'models/AmerikaanseVerkiezingenModel.php';
require_once 'includes/config.php';

class AmerikaanseVerkiezingenController {
    private $db;
    private $verkiezingenModel;

    public function __construct() {
        $this->db = new Database();
        $this->verkiezingenModel = new AmerikaanseVerkiezingenModel($this->db);
    }

    public function index() {
        // Haal alle verkiezingen op uit de database
        $verkiezingen = $this->verkiezingenModel->getAllVerkiezingen();
        
        // Haal statistieken op
        $statistieken = $this->verkiezingenModel->getStatistieken();
        
        // Haal key figures presidenten op voor timeline sectie
        $keyFiguresPresidenten = $this->getKeyFiguresPresidenten();
        
        // Als er geen data in database is, gebruik fallback data
        if (empty($verkiezingen)) {
            $verkiezingen = $this->getFallbackVerkiezingen();
            $statistieken = $this->getFallbackStatistieken();
        }
        
        // Groepeer verkiezingen per periode voor betere presentatie
        $verkiezingenPerPeriode = $this->groeperVerkiezingenPerPeriode($verkiezingen);
        
        require_once BASE_PATH . '/views/amerikaanse-verkiezingen/index.php';
    }

    public function detail($jaar) {
        // Haal specifieke verkiezing op
        $verkiezing = $this->verkiezingenModel->getVerkiezingByJaar($jaar);
        
        // Als verkiezing niet gevonden, gebruik fallback of 404
        if (!$verkiezing) {
            $verkiezing = $this->getFallbackVerkiezingByJaar($jaar);
            if (!$verkiezing) {
                header('Location: ' . URLROOT . '/404');
                exit();
            }
        }
        
        // Haal gerelateerde verkiezingen op (vorige en volgende)
        $gerelateerdeVerkiezingen = $this->getGerelateerdeVerkiezingen($jaar);
        
        require_once BASE_PATH . '/views/amerikaanse-verkiezingen/detail.php';
    }

    public function presidenten() {
        // Haal alle presidenten op uit de database
        $presidenten = $this->verkiezingenModel->getAllPresidenten();
        
        // Haal presidenten per periode op
        $presidentenPerPeriode = $this->verkiezingenModel->getPresidentenPerPeriode();
        
        // Haal familie dynastieën op
        $familieDynastieen = $this->verkiezingenModel->getFamilieDynastieen();
        
        // Haal statistieken op
        $presidentenStatistieken = $this->verkiezingenModel->getPresidentenStatistieken();
        
        // Als er geen data in database is, gebruik fallback data
        if (empty($presidenten)) {
            $presidenten = $this->getFallbackPresidenten();
            $presidentenPerPeriode = $this->groeperPresidentenPerPeriode($presidenten);
            $familieDynastieen = $this->getFallbackFamilieDynastieen();
            $presidentenStatistieken = $this->getFallbackPresidentenStatistieken();
        }
        
        require_once BASE_PATH . '/views/amerikaanse-verkiezingen/presidenten.php';
    }

    private function groeperVerkiezingenPerPeriode($verkiezingen) {
        $periodes = [
            'Moderne Era (2000-heden)' => [],
            'Late 20e Eeuw (1980-1999)' => [],
            'Midden 20e Eeuw (1960-1979)' => [],
            'Vroege 20e Eeuw (1940-1959)' => [],
            'Klassieke Era (voor 1940)' => []
        ];
        
        foreach ($verkiezingen as $verkiezing) {
            $jaar = is_object($verkiezing) ? $verkiezing->jaar : $verkiezing['jaar'];
            
            if ($jaar >= 2000) {
                $periodes['Moderne Era (2000-heden)'][] = $verkiezing;
            } elseif ($jaar >= 1980) {
                $periodes['Late 20e Eeuw (1980-1999)'][] = $verkiezing;
            } elseif ($jaar >= 1960) {
                $periodes['Midden 20e Eeuw (1960-1979)'][] = $verkiezing;
            } elseif ($jaar >= 1940) {
                $periodes['Vroege 20e Eeuw (1940-1959)'][] = $verkiezing;
            } else {
                $periodes['Klassieke Era (voor 1940)'][] = $verkiezing;
            }
        }
        
        // Verwijder lege periodes
        return array_filter($periodes, function($verkiezingen) {
            return !empty($verkiezingen);
        });
    }

    private function getGerelateerdeVerkiezingen($huidigJaar) {
        $alle_verkiezingen = $this->verkiezingenModel->getAllVerkiezingen();
        
        $vorige = null;
        $volgende = null;
        
        foreach ($alle_verkiezingen as $verkiezing) {
            $jaar = is_object($verkiezing) ? $verkiezing->jaar : $verkiezing['jaar'];
            
            if ($jaar < $huidigJaar && (!$vorige || $jaar > $vorige->jaar)) {
                $vorige = $verkiezing;
            }
            if ($jaar > $huidigJaar && (!$volgende || $jaar < $volgende->jaar)) {
                $volgende = $verkiezing;
            }
        }
        
        return ['vorige' => $vorige, 'volgende' => $volgende];
    }

    private function getFallbackVerkiezingen() {
        return [
            (object)[
                'jaar' => 2024,
                'winnaar' => 'Donald Trump',
                'winnaar_partij' => 'Republican',
                'winnaar_kiesmannen' => 312,
                'verliezer' => 'Kamala Harris',
                'verliezer_partij' => 'Democratic',
                'verliezer_kiesmannen' => 226,
                'winnaar_stemmen_populair' => 74223975,
                'verliezer_stemmen_populair' => 70329765,
                'winnaar_percentage_populair' => 50.0,
                'verliezer_percentage_populair' => 47.4,
                'opkomst_percentage' => 64.5,
                'verkiezingsdata' => '2024-11-05',
                'inhuldiging_data' => '2025-01-20',
                'belangrijkste_themas' => ['Economie', 'Immigratie', 'Democratie', 'Abortus'],
                'belangrijke_gebeurtenissen' => 'Trump\'s comeback na zijn verlies in 2020 en verschillende juridische zaken.',
                'opvallende_feiten' => 'Trump wordt de eerste president sinds Grover Cleveland (1892) die niet-opeenvolgende termijnen dient.',
                'beschrijving' => 'De verkiezing van 2024 was een rematch tussen Trump en Biden, maar Harris nam het over na Biden\'s terugtrekking.'
            ],
            (object)[
                'jaar' => 2020,
                'winnaar' => 'Joe Biden',
                'winnaar_partij' => 'Democratic',
                'winnaar_kiesmannen' => 306,
                'verliezer' => 'Donald Trump',
                'verliezer_partij' => 'Republican',
                'verliezer_kiesmannen' => 232,
                'winnaar_stemmen_populair' => 81283501,
                'verliezer_stemmen_populair' => 74223975,
                'winnaar_percentage_populair' => 51.3,
                'verliezer_percentage_populair' => 46.8,
                'opkomst_percentage' => 66.6,
                'verkiezingsdata' => '2020-11-03',
                'inhuldiging_data' => '2021-01-20',
                'belangrijkste_themas' => ['COVID-19', 'Economie', 'Rassengelijkheid', 'Klimaat'],
                'belangrijke_gebeurtenissen' => 'Verkiezing tijdens COVID-19 pandemie met record aantal poststemmen.',
                'opvallende_feiten' => 'Hoogste opkomst in meer dan een eeuw. Trump weigerde nederlaag te erkennen.',
                'beschrijving' => 'De verkiezing werd overschaduwd door de COVID-19 pandemie en resulteerde in de hoogste opkomst sinds 1900.'
            ],
            (object)[
                'jaar' => 2016,
                'winnaar' => 'Donald Trump',
                'winnaar_partij' => 'Republican',
                'winnaar_kiesmannen' => 304,
                'verliezer' => 'Hillary Clinton',
                'verliezer_partij' => 'Democratic',
                'verliezer_kiesmannen' => 227,
                'winnaar_stemmen_populair' => 62984828,
                'verliezer_stemmen_populair' => 65853514,
                'winnaar_percentage_populair' => 46.1,
                'verliezer_percentage_populair' => 48.2,
                'opkomst_percentage' => 55.7,
                'verkiezingsdata' => '2016-11-08',
                'inhuldiging_data' => '2017-01-20',
                'belangrijkste_themas' => ['Handel', 'Immigratie', 'Healthcare', 'E-mails'],
                'belangrijke_gebeurtenissen' => 'Clinton won populaire stemmen maar verloor kiesmannen.',
                'opvallende_feiten' => 'Trump had geen politieke ervaring. Clinton zou eerste vrouwelijke president zijn geweest.',
                'beschrijving' => 'Een verrassende overwinning voor Trump tegen de verwachting van de meeste peilingen in.'
            ],
            (object)[
                'jaar' => 2012,
                'winnaar' => 'Barack Obama',
                'winnaar_partij' => 'Democratic',
                'winnaar_kiesmannen' => 332,
                'verliezer' => 'Mitt Romney',
                'verliezer_partij' => 'Republican',
                'verliezer_kiesmannen' => 206,
                'winnaar_stemmen_populair' => 65915795,
                'verliezer_stemmen_populair' => 60933504,
                'winnaar_percentage_populair' => 51.1,
                'verliezer_percentage_populair' => 47.2,
                'opkomst_percentage' => 54.9,
                'verkiezingsdata' => '2012-11-06',
                'inhuldiging_data' => '2013-01-20',
                'belangrijkste_themas' => ['Economie', 'Healthcare', 'Buitenlands beleid'],
                'belangrijke_gebeurtenissen' => 'Obama\'s herverkiezing na de financiële crisis.',
                'opvallende_feiten' => 'Obamacare was een belangrijk campagne-onderwerp.',
                'beschrijving' => 'Obama won zijn herverkiezing ondanks economische uitdagingen na de crisis van 2008.'
            ]
        ];
    }

    private function getFallbackVerkiezingByJaar($jaar) {
        $fallbackVerkiezingen = $this->getFallbackVerkiezingen();
        
        foreach ($fallbackVerkiezingen as $verkiezing) {
            if ($verkiezing->jaar == $jaar) {
                return $verkiezing;
            }
        }
        
        return null;
    }

    private function getFallbackStatistieken() {
        return (object)[
            'totaal_verkiezingen' => 4,
            'eerste_verkiezing' => 2012,
            'laatste_verkiezing' => 2024,
            'gemiddelde_opkomst' => 60.2,
            'republican_overwinningen' => 2,
            'democratic_overwinningen' => 2
        ];
    }
    
    // ==========================================
    // PRESIDENTEN FALLBACK FUNCTIES
    // ==========================================
    
    private function getFallbackPresidenten() {
        return [
            (object)[
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
                'biografie' => 'George Washington was de eerste president van de Verenigde Staten en wordt beschouwd als een van de founding fathers.',
                'prestaties' => ['Eerste president van de Verenigde Staten', 'Leidde Continental Army', 'Presideerde Constitutional Convention'],
                'fun_facts' => ['Enige president unaniem gekozen', 'Nooit inwoner van Washington D.C.', 'Rijkste president ooit'],
                'echtgenote' => 'Martha Dandridge Custis Washington',
                'familie_connecties' => 'Verre neef van Robert E. Lee',
                'leeftijd_bij_aantreden' => 57,
                'jaren_in_functie' => 8
            ],
            (object)[
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
                'biografie' => 'John Adams was de tweede president en vader van John Quincy Adams.',
                'prestaties' => ['Eerste vice-president', 'Voorkwam oorlog met Frankrijk', 'Vader van Amerikaanse diplomatie'],
                'fun_facts' => ['Stierf op dezelfde dag als Jefferson', 'Eerste president in Witte Huis', 'Zoon werd ook president'],
                'echtgenote' => 'Abigail Smith Adams',
                'familie_connecties' => 'Vader van John Quincy Adams (6e president)',
                'leeftijd_bij_aantreden' => 61,
                'jaren_in_functie' => 4
            ],
            (object)[
                'president_nummer' => 46,
                'naam' => 'Joe Biden',
                'volledige_naam' => 'Joseph Robinette Biden Jr.',
                'bijnaam' => 'Amtrak Joe',
                'partij' => 'Democratic',
                'periode_start' => '2021-01-20',
                'periode_eind' => null,
                'geboren' => '1942-11-20',
                'overleden' => null,
                'geboorteplaats' => 'Scranton, Pennsylvania',
                'vice_president' => 'Kamala Harris',
                'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/68/Joe_Biden_presidential_portrait.jpg',
                'biografie' => 'Joe Biden is de 46e en huidige president van de Verenigde Staten.',
                'prestaties' => ['Oudste president ooit geïnaugureerd', '36 jaar Senator', 'Vice-president onder Obama'],
                'fun_facts' => ['Jongste senator ooit gekozen', 'Gebruikt trein dagelijks 36 jaar', 'Eerste katholieke president sinds JFK'],
                'echtgenote' => 'Jill Tracy Jacobs Biden',
                'familie_connecties' => 'Geen directe presidentiële familie',
                'leeftijd_bij_aantreden' => 78,
                'jaren_in_functie' => 3,
                'is_huidig' => true
            ]
        ];
    }
    
    private function groeperPresidentenPerPeriode($presidenten) {
        $periodes = [
            'Moderne Era (1993-heden)' => [],
            'Late 20e Eeuw (1945-1993)' => [],
            'Vroege 20e Eeuw (1901-1945)' => [],
            'Founding Era (1789-1900)' => []
        ];
        
        foreach ($presidenten as $president) {
            $startJaar = date('Y', strtotime($president->periode_start));
            
            if ($startJaar >= 1993) {
                $periodes['Moderne Era (1993-heden)'][] = $president;
            } elseif ($startJaar >= 1945) {
                $periodes['Late 20e Eeuw (1945-1993)'][] = $president;
            } elseif ($startJaar >= 1901) {
                $periodes['Vroege 20e Eeuw (1901-1945)'][] = $president;
            } else {
                $periodes['Founding Era (1789-1900)'][] = $president;
            }
        }
        
        return array_filter($periodes, function($presidenten) {
            return !empty($presidenten);
        });
    }
    
    private function getFallbackFamilieDynastieen() {
        return [
            'Adams Familie' => [
                (object)[
                    'naam' => 'John Adams',
                    'president_nummer' => 2,
                    'familie_connecties' => 'Vader van John Quincy Adams'
                ],
                (object)[
                    'naam' => 'John Quincy Adams', 
                    'president_nummer' => 6,
                    'familie_connecties' => 'Zoon van John Adams'
                ]
            ]
        ];
    }
    
    private function getFallbackPresidentenStatistieken() {
        return (object)[
            'totaal_presidenten' => 46,
            'gemiddelde_leeftijd' => 55.4,
            'jongste_leeftijd' => 42,
            'oudste_leeftijd' => 78,
            'gemiddelde_termijn_jaren' => 5.2,
            'nog_levend' => 5,
            'republican_presidenten' => 19,
            'democratic_presidenten' => 16
        ];
    }
    
    /**
     * Haal key figures presidenten op voor timeline sectie
     */
    private function getKeyFiguresPresidenten() {
        try {
            // Haal alle presidenten op
            $presidenten = $this->verkiezingenModel->getAllPresidenten();
            
            if (!empty($presidenten)) {
                // Zoek specifieke presidenten per periode
                $keyFigures = [
                    'vroege_republiek' => [],
                    'industriele_era' => [],
                    'digitale_revolutie' => []
                ];
                
                foreach ($presidenten as $president) {
                    // Vroege Republiek (1789-1860): Washington, Jefferson, Lincoln
                    if (in_array($president->president_nummer, [1, 3, 16])) {
                        $keyFigures['vroege_republiek'][] = $president;
                    }
                    // Industriële Era (1860-1960): Theodore Roosevelt, Franklin Roosevelt, Truman  
                    elseif (in_array($president->president_nummer, [26, 32, 33])) {
                        $keyFigures['industriele_era'][] = $president;
                    }
                    // Digitale Revolutie (1960-heden): Kennedy, Obama, Biden
                    elseif (in_array($president->president_nummer, [35, 44, 46])) {
                        $keyFigures['digitale_revolutie'][] = $president;
                    }
                }
                
                return $keyFigures;
            }
        } catch (Exception $e) {
            error_log("getKeyFiguresPresidenten fout: " . $e->getMessage());
        }
        
        // Fallback: gebruik fallback presidenten als database niet beschikbaar is
        return $this->getFallbackKeyFiguresPresidenten();
    }
    
    /**
     * Fallback key figures presidenten data
     */
    private function getFallbackKeyFiguresPresidenten() {
        return [
            'vroege_republiek' => [
                (object)[
                    'president_nummer' => 1,
                    'naam' => 'George Washington',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Gilbert_Stuart_Williamstown_Portrait_of_George_Washington.jpg/256px-Gilbert_Stuart_Williamstown_Portrait_of_George_Washington.jpg'
                ],
                (object)[
                    'president_nummer' => 3,
                    'naam' => 'Thomas Jefferson',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Thomas_Jefferson_by_Rembrandt_Peale%2C_1800.jpg/256px-Thomas_Jefferson_by_Rembrandt_Peale%2C_1800.jpg'
                ],
                (object)[
                    'president_nummer' => 16,
                    'naam' => 'Abraham Lincoln',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Abraham_Lincoln_O-77_matte_collodion_print.jpg/256px-Abraham_Lincoln_O-77_matte_collodion_print.jpg'
                ]
            ],
            'industriele_era' => [
                (object)[
                    'president_nummer' => 26,
                    'naam' => 'Theodore Roosevelt',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/President_Roosevelt_-_Pach_Bros.jpg/256px-President_Roosevelt_-_Pach_Bros.jpg'
                ],
                (object)[
                    'president_nummer' => 32,
                    'naam' => 'Franklin D. Roosevelt',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/FDR_1944_Color_Portrait.jpg/256px-FDR_1944_Color_Portrait.jpg'
                ],
                (object)[
                    'president_nummer' => 33,
                    'naam' => 'Harry S. Truman',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/TRUMAN_58-766-06_%28cropped%29.jpg/256px-TRUMAN_58-766-06_%28cropped%29.jpg'
                ]
            ],
            'digitale_revolutie' => [
                (object)[
                    'president_nummer' => 35,
                    'naam' => 'John F. Kennedy',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/John_F._Kennedy%2C_White_House_color_photo_portrait.jpg/256px-John_F._Kennedy%2C_White_House_color_photo_portrait.jpg'
                ],
                (object)[
                    'president_nummer' => 44,
                    'naam' => 'Barack Obama',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/President_Barack_Obama.jpg/256px-President_Barack_Obama.jpg'
                ],
                (object)[
                    'president_nummer' => 46,
                    'naam' => 'Joe Biden',
                    'foto_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Joe_Biden_presidential_portrait.jpg/256px-Joe_Biden_presidential_portrait.jpg'
                ]
            ]
        ];
    }
}

// Instantieer de controller
$verkiezingenController = new AmerikaanseVerkiezingenController();

// Bepaal de actie op basis van URL parameters
if (isset($_GET['actie']) && $_GET['actie'] === 'presidenten') {
    $verkiezingenController->presidenten();
} elseif (isset($_GET['jaar'])) {
    $verkiezingenController->detail($_GET['jaar']);
} else {
    $verkiezingenController->index();
} 