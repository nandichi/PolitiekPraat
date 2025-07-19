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
} 