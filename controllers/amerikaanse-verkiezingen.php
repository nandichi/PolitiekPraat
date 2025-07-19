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
}

// Instantieer de controller
$verkiezingenController = new AmerikaanseVerkiezingenController();

// Bepaal de actie op basis van URL parameters
if (isset($_GET['jaar'])) {
    $verkiezingenController->detail($_GET['jaar']);
} else {
    $verkiezingenController->index();
} 