<?php
/**
 * Amerikaanse Verkiezingen API Endpoint
 * Handles US elections data
 */

class VerkiezingenUSAAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $jaar = $segments[2] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($jaar) {
                    $this->getVerkiezing($jaar);
                } else {
                    $this->getAllVerkiezingen();
                }
                break;
                
            case 'POST':
                $this->createVerkiezing();
                break;
                
            case 'PUT':
                if ($jaar) {
                    $this->updateVerkiezing($jaar);
                } else {
                    sendApiError('Jaar vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($jaar) {
                    $this->deleteVerkiezing($jaar);
                } else {
                    sendApiError('Jaar vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllVerkiezingen() {
        try {
            $this->db->query("SELECT * FROM amerikaanse_verkiezingen ORDER BY jaar DESC");
            $verkiezingen = $this->db->resultSet();
            
            foreach ($verkiezingen as &$verkiezing) {
                $this->parseJsonFields($verkiezing);
            }
            
            sendApiResponse([
                'verkiezingen' => $verkiezingen,
                'total' => count($verkiezingen)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen verkiezingen: ' . $e->getMessage(), 500);
        }
    }
    
    private function getVerkiezing($jaar) {
        try {
            $this->db->query("SELECT * FROM amerikaanse_verkiezingen WHERE jaar = :jaar");
            $this->db->bind(':jaar', $jaar);
            $verkiezing = $this->db->single();
            
            if (!$verkiezing) {
                sendApiError('Verkiezing niet gevonden', 404);
                return;
            }
            
            $this->parseJsonFields($verkiezing);
            
            sendApiResponse(['verkiezing' => $verkiezing]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen verkiezing: ' . $e->getMessage(), 500);
        }
    }
    
    private function createVerkiezing() {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $required = ['jaar', 'winnaar', 'winnaar_partij', 'winnaar_kiesmannen',
                        'verliezer', 'verliezer_partij', 'verliezer_kiesmannen',
                        'winnaar_stemmen_populair', 'verliezer_stemmen_populair',
                        'winnaar_percentage_populair', 'verliezer_percentage_populair'];
            
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                    return;
                }
            }
            
            $this->db->query("INSERT INTO amerikaanse_verkiezingen 
                (jaar, winnaar, winnaar_partij, winnaar_kiesmannen, verliezer, verliezer_partij,
                 verliezer_kiesmannen, winnaar_stemmen_populair, verliezer_stemmen_populair,
                 winnaar_percentage_populair, verliezer_percentage_populair, totaal_kiesmannen,
                 opkomst_percentage, belangrijkste_themas, belangrijke_gebeurtenissen,
                 opvallende_feiten, verkiezingsdata, inhuldiging_data, bronnen, extra_kandidaten,
                 beschrijving, winnaar_foto_url, verliezer_foto_url)
                VALUES
                (:jaar, :winnaar, :winnaar_partij, :winnaar_kiesmannen, :verliezer, :verliezer_partij,
                 :verliezer_kiesmannen, :winnaar_stemmen_populair, :verliezer_stemmen_populair,
                 :winnaar_percentage_populair, :verliezer_percentage_populair, :totaal_kiesmannen,
                 :opkomst_percentage, :belangrijkste_themas, :belangrijke_gebeurtenissen,
                 :opvallende_feiten, :verkiezingsdata, :inhuldiging_data, :bronnen, :extra_kandidaten,
                 :beschrijving, :winnaar_foto_url, :verliezer_foto_url)");
            
            $this->bindVerkiezingData($data);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Verkiezing succesvol aangemaakt',
                    'jaar' => $data['jaar']
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken verkiezing', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken verkiezing: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateVerkiezing($jaar) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $fields = [];
            $allowed = ['winnaar', 'winnaar_partij', 'winnaar_kiesmannen', 'verliezer',
                       'verliezer_partij', 'verliezer_kiesmannen', 'winnaar_stemmen_populair',
                       'verliezer_stemmen_populair', 'winnaar_percentage_populair',
                       'verliezer_percentage_populair', 'opkomst_percentage', 'beschrijving'];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE amerikaanse_verkiezingen SET " . implode(', ', $fields) . " WHERE jaar = :jaar";
            $this->db->query($query);
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            }
            $this->db->bind(':jaar', $jaar);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Verkiezing succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken verkiezing', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken verkiezing: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteVerkiezing($jaar) {
        if (!$this->requireAdmin()) return;
        
        try {
            $this->db->query("DELETE FROM amerikaanse_verkiezingen WHERE jaar = :jaar");
            $this->db->bind(':jaar', $jaar);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Verkiezing succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen verkiezing', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen verkiezing: ' . $e->getMessage(), 500);
        }
    }
    
    private function parseJsonFields(&$verkiezing) {
        $jsonFields = ['belangrijkste_themas', 'bronnen', 'extra_kandidaten'];
        
        foreach ($jsonFields as $field) {
            if (isset($verkiezing->$field) && $verkiezing->$field) {
                $verkiezing->$field = json_decode($verkiezing->$field);
            }
        }
    }
    
    private function bindVerkiezingData($data) {
        $jsonFields = ['belangrijkste_themas', 'bronnen', 'extra_kandidaten'];
        $allFields = ['jaar', 'winnaar', 'winnaar_partij', 'winnaar_kiesmannen', 'verliezer',
                     'verliezer_partij', 'verliezer_kiesmannen', 'winnaar_stemmen_populair',
                     'verliezer_stemmen_populair', 'winnaar_percentage_populair',
                     'verliezer_percentage_populair', 'totaal_kiesmannen', 'opkomst_percentage',
                     'belangrijkste_themas', 'belangrijke_gebeurtenissen', 'opvallende_feiten',
                     'verkiezingsdata', 'inhuldiging_data', 'bronnen', 'extra_kandidaten',
                     'beschrijving', 'winnaar_foto_url', 'verliezer_foto_url'];
        
        foreach ($allFields as $field) {
            if (isset($data[$field])) {
                if (in_array($field, $jsonFields)) {
                    $this->db->bind(":{$field}", json_encode($data[$field]));
                } else {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            } else {
                $this->db->bind(":{$field}", null);
            }
        }
    }
    
    private function requireAdmin() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            sendApiError('Admin rechten vereist', 403);
            return false;
        }
        return true;
    }
}

