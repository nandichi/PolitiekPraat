<?php
class AmerikaanseVerkiezingenModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Haal alle verkiezingen op gesorteerd op jaar (nieuwste eerst)
     */
    public function getAllVerkiezingen() {
        $sql = "SELECT * FROM amerikaanse_verkiezingen ORDER BY jaar DESC";
        
        try {
            $this->db->query($sql);
            $result = $this->db->resultSet();
            return $this->formatVerkiezingenResults($result);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getAllVerkiezingen fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal een specifieke verkiezing op per jaar
     */
    public function getVerkiezingByJaar($jaar) {
        $sql = "SELECT * FROM amerikaanse_verkiezingen WHERE jaar = ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $jaar);
            $this->db->execute();
            $result = $this->db->single();
            
            if ($result) {
                return $this->formatSingleVerkiezingResult($result);
            }
            return null;
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getVerkiezingByJaar fout: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Haal verkiezingen op binnen een bepaald datumbereik
     */
    public function getVerkiezingenByPeriod($startJaar, $eindJaar) {
        $sql = "SELECT * FROM amerikaanse_verkiezingen WHERE jaar BETWEEN ? AND ? ORDER BY jaar DESC";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $startJaar);
            $this->db->bind(2, $eindJaar);
            $this->db->execute();
            $result = $this->db->resultSet();
            return $this->formatVerkiezingenResults($result);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getVerkiezingenByPeriod fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal verkiezingen op per winnende partij
     */
    public function getVerkiezingenByPartij($partij) {
        $sql = "SELECT * FROM amerikaanse_verkiezingen WHERE winnaar_partij = ? ORDER BY jaar DESC";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $partij);
            $this->db->execute();
            $result = $this->db->resultSet();
            return $this->formatVerkiezingenResults($result);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getVerkiezingenByPartij fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Voeg een nieuwe verkiezing toe
     */
    public function addVerkiezing($data) {
        $sql = "INSERT INTO amerikaanse_verkiezingen (
            jaar, winnaar, winnaar_partij, winnaar_kiesmannen, verliezer, verliezer_partij, 
            verliezer_kiesmannen, winnaar_stemmen_populair, verliezer_stemmen_populair,
            winnaar_percentage_populair, verliezer_percentage_populair, opkomst_percentage,
            belangrijkste_themas, belangrijke_gebeurtenissen, opvallende_feiten,
            verkiezingsdata, inhuldiging_data, bronnen, extra_kandidaten, beschrijving
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $data['jaar']);
            $this->db->bind(2, $data['winnaar']);
            $this->db->bind(3, $data['winnaar_partij']);
            $this->db->bind(4, $data['winnaar_kiesmannen']);
            $this->db->bind(5, $data['verliezer']);
            $this->db->bind(6, $data['verliezer_partij']);
            $this->db->bind(7, $data['verliezer_kiesmannen']);
            $this->db->bind(8, $data['winnaar_stemmen_populair']);
            $this->db->bind(9, $data['verliezer_stemmen_populair']);
            $this->db->bind(10, $data['winnaar_percentage_populair']);
            $this->db->bind(11, $data['verliezer_percentage_populair']);
            $this->db->bind(12, $data['opkomst_percentage']);
            $this->db->bind(13, json_encode($data['belangrijkste_themas']));
            $this->db->bind(14, $data['belangrijke_gebeurtenissen']);
            $this->db->bind(15, $data['opvallende_feiten']);
            $this->db->bind(16, $data['verkiezingsdata']);
            $this->db->bind(17, $data['inhuldiging_data']);
            $this->db->bind(18, json_encode($data['bronnen']));
            $this->db->bind(19, json_encode($data['extra_kandidaten']));
            $this->db->bind(20, $data['beschrijving']);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::addVerkiezing fout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update een bestaande verkiezing
     */
    public function updateVerkiezing($jaar, $data) {
        $sql = "UPDATE amerikaanse_verkiezingen SET 
            winnaar = ?, winnaar_partij = ?, winnaar_kiesmannen = ?, verliezer = ?, verliezer_partij = ?,
            verliezer_kiesmannen = ?, winnaar_stemmen_populair = ?, verliezer_stemmen_populair = ?,
            winnaar_percentage_populair = ?, verliezer_percentage_populair = ?, opkomst_percentage = ?,
            belangrijkste_themas = ?, belangrijke_gebeurtenissen = ?, opvallende_feiten = ?,
            verkiezingsdata = ?, inhuldiging_data = ?, bronnen = ?, extra_kandidaten = ?, beschrijving = ?
            WHERE jaar = ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $data['winnaar']);
            $this->db->bind(2, $data['winnaar_partij']);
            $this->db->bind(3, $data['winnaar_kiesmannen']);
            $this->db->bind(4, $data['verliezer']);
            $this->db->bind(5, $data['verliezer_partij']);
            $this->db->bind(6, $data['verliezer_kiesmannen']);
            $this->db->bind(7, $data['winnaar_stemmen_populair']);
            $this->db->bind(8, $data['verliezer_stemmen_populair']);
            $this->db->bind(9, $data['winnaar_percentage_populair']);
            $this->db->bind(10, $data['verliezer_percentage_populair']);
            $this->db->bind(11, $data['opkomst_percentage']);
            $this->db->bind(12, json_encode($data['belangrijkste_themas']));
            $this->db->bind(13, $data['belangrijke_gebeurtenissen']);
            $this->db->bind(14, $data['opvallende_feiten']);
            $this->db->bind(15, $data['verkiezingsdata']);
            $this->db->bind(16, $data['inhuldiging_data']);
            $this->db->bind(17, json_encode($data['bronnen']));
            $this->db->bind(18, json_encode($data['extra_kandidaten']));
            $this->db->bind(19, $data['beschrijving']);
            $this->db->bind(20, $jaar);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::updateVerkiezing fout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verwijder een verkiezing
     */
    public function deleteVerkiezing($jaar) {
        $sql = "DELETE FROM amerikaanse_verkiezingen WHERE jaar = ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $jaar);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::deleteVerkiezing fout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Haal statistieken op
     */
    public function getStatistieken() {
        $sql = "SELECT 
            COUNT(*) as totaal_verkiezingen,
            MIN(jaar) as eerste_verkiezing,
            MAX(jaar) as laatste_verkiezing,
            AVG(opkomst_percentage) as gemiddelde_opkomst,
            SUM(CASE WHEN winnaar_partij = 'Republican' THEN 1 ELSE 0 END) as republican_overwinningen,
            SUM(CASE WHEN winnaar_partij = 'Democratic' THEN 1 ELSE 0 END) as democratic_overwinningen
            FROM amerikaanse_verkiezingen";
        
        try {
            $this->db->query($sql);
            $result = $this->db->single();
            return $result;
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getStatistieken fout: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Formatteer de verkiezingen resultaten
     */
    private function formatVerkiezingenResults($results) {
        if (!$results) return [];
        
        return array_map(function($verkiezing) {
            return $this->formatSingleVerkiezingResult($verkiezing);
        }, $results);
    }
    
    /**
     * Formatteer een enkele verkiezing
     */
    private function formatSingleVerkiezingResult($verkiezing) {
        if (!$verkiezing) return null;
        
        // Decode JSON fields
        if (isset($verkiezing->belangrijkste_themas)) {
            $verkiezing->belangrijkste_themas = json_decode($verkiezing->belangrijkste_themas, true);
        }
        if (isset($verkiezing->bronnen)) {
            $verkiezing->bronnen = json_decode($verkiezing->bronnen, true);
        }
        if (isset($verkiezing->extra_kandidaten)) {
            $verkiezing->extra_kandidaten = json_decode($verkiezing->extra_kandidaten, true);
        }
        
        // Voeg berekende velden toe
        $verkiezing->totaal_stemmen_populair = $verkiezing->winnaar_stemmen_populair + $verkiezing->verliezer_stemmen_populair;
        $verkiezing->kiesmannen_marge = $verkiezing->winnaar_kiesmannen - $verkiezing->verliezer_kiesmannen;
        $verkiezing->populaire_stemmen_marge = $verkiezing->winnaar_stemmen_populair - $verkiezing->verliezer_stemmen_populair;
        $verkiezing->percentage_marge = $verkiezing->winnaar_percentage_populair - $verkiezing->verliezer_percentage_populair;
        
        // Formatteer datums voor Nederlandse display
        if (isset($verkiezing->verkiezingsdata)) {
            $verkiezing->verkiezingsdata_formatted = $this->formatDutchDate($verkiezing->verkiezingsdata);
        }
        if (isset($verkiezing->inhuldiging_data)) {
            $verkiezing->inhuldiging_data_formatted = $this->formatDutchDate($verkiezing->inhuldiging_data);
        }
        
        return $verkiezing;
    }
    
    /**
     * Formatteer datum naar Nederlands formaat
     */
    private function formatDutchDate($date) {
        if (!$date) return null;
        
        $dutchMonths = [
            1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
            5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
            9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
        ];
        
        $timestamp = strtotime($date);
        $day = date('j', $timestamp);
        $month = $dutchMonths[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp);
        
        return "$day $month $year";
    }
    
    // ========================================
    // PRESIDENTEN FUNCTIES
    // ========================================
    
    /**
     * Haal alle presidenten op gesorteerd op president nummer
     */
    public function getAllPresidenten() {
        $sql = "SELECT * FROM amerikaanse_presidenten ORDER BY president_nummer ASC";
        
        try {
            $this->db->query($sql);
            $result = $this->db->resultSet();
            return $this->formatPresidentenResults($result);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getAllPresidenten fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal een specifieke president op per nummer
     */
    public function getPresidentByNummer($nummer) {
        $sql = "SELECT * FROM amerikaanse_presidenten WHERE president_nummer = ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $nummer);
            $this->db->execute();
            $result = $this->db->single();
            
            if ($result) {
                return $this->formatSinglePresidentResult($result);
            }
            return null;
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getPresidentByNummer fout: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Haal presidenten op per periode
     */
    public function getPresidentenPerPeriode() {
        $presidenten = $this->getAllPresidenten();
        
        $periodes = [
            'Moderne Era (1993-heden)' => [],
            'Late 20e Eeuw (1945-1993)' => [],
            'Vroege 20e Eeuw (1901-1945)' => [],
            'Progressieve Era (1861-1901)' => [],
            'Antebellum & Burgeroorlog (1841-1865)' => [],
            'Jacksoniaanse Democratie (1829-1841)' => [],
            'Era of Good Feelings (1817-1829)' => [],
            'Early Republic (1797-1817)' => [],
            'Founding Fathers (1789-1797)' => []
        ];
        
        foreach ($presidenten as $president) {
            $startJaar = date('Y', strtotime($president->periode_start));
            
            if ($startJaar >= 1993) {
                $periodes['Moderne Era (1993-heden)'][] = $president;
            } elseif ($startJaar >= 1945) {
                $periodes['Late 20e Eeuw (1945-1993)'][] = $president;
            } elseif ($startJaar >= 1901) {
                $periodes['Vroege 20e Eeuw (1901-1945)'][] = $president;
            } elseif ($startJaar >= 1861) {
                $periodes['Progressieve Era (1861-1901)'][] = $president;
            } elseif ($startJaar >= 1841) {
                $periodes['Antebellum & Burgeroorlog (1841-1865)'][] = $president;
            } elseif ($startJaar >= 1829) {
                $periodes['Jacksoniaanse Democratie (1829-1841)'][] = $president;
            } elseif ($startJaar >= 1817) {
                $periodes['Era of Good Feelings (1817-1829)'][] = $president;
            } elseif ($startJaar >= 1797) {
                $periodes['Early Republic (1797-1817)'][] = $president;
            } else {
                $periodes['Founding Fathers (1789-1797)'][] = $president;
            }
        }
        
        // Verwijder lege periodes
        return array_filter($periodes, function($presidenten) {
            return !empty($presidenten);
        });
    }
    
    /**
     * Haal presidenten op per partij
     */
    public function getPresidentenByPartij($partij = null) {
        if ($partij) {
            $sql = "SELECT * FROM amerikaanse_presidenten WHERE partij = ? ORDER BY president_nummer ASC";
            $this->db->query($sql);
            $this->db->bind(1, $partij);
        } else {
            $sql = "SELECT partij, COUNT(*) as aantal FROM amerikaanse_presidenten GROUP BY partij ORDER BY aantal DESC";
            $this->db->query($sql);
        }
        
        try {
            if ($partij) {
                $result = $this->db->resultSet();
                return $this->formatPresidentenResults($result);
            } else {
                return $this->db->resultSet();
            }
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getPresidentenByPartij fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Zoek presidenten op naam of bijnaam
     */
    public function searchPresidenten($zoekterm) {
        $sql = "SELECT * FROM amerikaanse_presidenten 
                WHERE naam LIKE ? OR volledige_naam LIKE ? OR bijnaam LIKE ? 
                ORDER BY president_nummer ASC";
        
        try {
            $searchPattern = '%' . $zoekterm . '%';
            $this->db->query($sql);
            $this->db->bind(1, $searchPattern);
            $this->db->bind(2, $searchPattern);
            $this->db->bind(3, $searchPattern);
            $result = $this->db->resultSet();
            return $this->formatPresidentenResults($result);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::searchPresidenten fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal presidenten familie dynastieën op
     */
    public function getFamilieDynastieen() {
        $sql = "SELECT * FROM amerikaanse_presidenten 
                WHERE familie_connecties IS NOT NULL AND familie_connecties != '' 
                ORDER BY president_nummer ASC";
        
        try {
            $this->db->query($sql);
            $result = $this->db->resultSet();
            $presidenten = $this->formatPresidentenResults($result);
            
            // Groepeer per familie
            $dynasties = [
                'Adams Familie' => [],
                'Bush Familie' => [],
                'Roosevelt Familie' => [],
                'Harrison Familie' => []
            ];
            
            foreach ($presidenten as $president) {
                if (strpos($president->familie_connecties, 'Adams') !== false) {
                    $dynasties['Adams Familie'][] = $president;
                }
                if (strpos($president->familie_connecties, 'Bush') !== false) {
                    $dynasties['Bush Familie'][] = $president;
                }
                if (strpos($president->familie_connecties, 'Roosevelt') !== false) {
                    $dynasties['Roosevelt Familie'][] = $president;
                }
                if (strpos($president->familie_connecties, 'Harrison') !== false) {
                    $dynasties['Harrison Familie'][] = $president;
                }
            }
            
            return array_filter($dynasties);
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getFamilieDynastieen fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal presidenten statistieken op
     */
    public function getPresidentenStatistieken() {
        $sql = "SELECT 
            COUNT(*) as totaal_presidenten,
            AVG(leeftijd_bij_aantreden) as gemiddelde_leeftijd,
            MIN(leeftijd_bij_aantreden) as jongste_leeftijd,
            MAX(leeftijd_bij_aantreden) as oudste_leeftijd,
            AVG(DATEDIFF(IFNULL(periode_eind, CURDATE()), periode_start) / 365.25) as gemiddelde_termijn_jaren,
            COUNT(CASE WHEN overleden IS NULL THEN 1 END) as nog_levend,
            COUNT(CASE WHEN partij = 'Republican' THEN 1 END) as republican_presidenten,
            COUNT(CASE WHEN partij = 'Democratic' THEN 1 END) as democratic_presidenten
            FROM amerikaanse_presidenten";
        
        try {
            $this->db->query($sql);
            return $this->db->single();
        } catch (Exception $e) {
            error_log("AmerikaanseVerkiezingenModel::getPresidentenStatistieken fout: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Formatteer presidenten resultaten
     */
    private function formatPresidentenResults($results) {
        if (!$results) return [];
        
        return array_map(function($president) {
            return $this->formatSinglePresidentResult($president);
        }, $results);
    }
    
    /**
     * Formatteer een enkele president
     */
    private function formatSinglePresidentResult($president) {
        if (!$president) return null;
        
        // Decode JSON fields
        if (isset($president->prestaties)) {
            $president->prestaties = json_decode($president->prestaties, true);
        }
        if (isset($president->fun_facts)) {
            $president->fun_facts = json_decode($president->fun_facts, true);
        }
        if (isset($president->kinderen)) {
            $president->kinderen = json_decode($president->kinderen, true);
        }
        if (isset($president->verkiezingsjaren)) {
            $president->verkiezingsjaren = json_decode($president->verkiezingsjaren, true);
        }
        if (isset($president->wetgeving)) {
            $president->wetgeving = json_decode($president->wetgeving, true);
        }
        if (isset($president->oorlogen)) {
            $president->oorlogen = json_decode($president->oorlogen, true);
        }
        if (isset($president->citaten)) {
            $president->citaten = json_decode($president->citaten, true);
        }
        
        // Voeg berekende velden toe
        if (isset($president->geboren) && isset($president->overleden)) {
            $geboren = new DateTime($president->geboren);
            $overleden = new DateTime($president->overleden);
            $president->leeftijd_bij_overlijden = $overleden->diff($geboren)->y;
        }
        
        if (isset($president->periode_start) && isset($president->periode_eind)) {
            $start = new DateTime($president->periode_start);
            $eind = new DateTime($president->periode_eind);
            $president->jaren_in_functie = $eind->diff($start)->y;
        } elseif (isset($president->periode_start) && $president->is_huidig) {
            $start = new DateTime($president->periode_start);
            $nu = new DateTime();
            $president->jaren_in_functie = $nu->diff($start)->y;
        }
        
        // Formatteer datums voor Nederlandse display
        if (isset($president->geboren)) {
            $president->geboren_formatted = $this->formatDutchDate($president->geboren);
        }
        if (isset($president->overleden)) {
            $president->overleden_formatted = $this->formatDutchDate($president->overleden);
        }
        if (isset($president->periode_start)) {
            $president->periode_start_formatted = $this->formatDutchDate($president->periode_start);
        }
        if (isset($president->periode_eind)) {
            $president->periode_eind_formatted = $this->formatDutchDate($president->periode_eind);
        }
        
        return $president;
    }
} 