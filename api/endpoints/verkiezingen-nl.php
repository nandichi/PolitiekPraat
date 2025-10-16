<?php
/**
 * Nederlandse Verkiezingen API Endpoint
 * Handles Dutch elections data
 */

class VerkiezingenNLAPI {
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
            $this->db->query("SELECT * FROM nederlandse_verkiezingen ORDER BY jaar DESC");
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
            $this->db->query("SELECT * FROM nederlandse_verkiezingen WHERE jaar = :jaar");
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
            
            $required = ['jaar', 'minister_president', 'minister_president_partij', 
                        'totaal_stemmen', 'opkomst_percentage', 'verkiezingsdata'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                    return;
                }
            }
            
            $jsonFields = ['partij_uitslagen', 'coalitie_partijen', 'oppositie_partijen', 
                          'belangrijkste_themas', 'nieuwe_partijen', 'verdwenen_partijen',
                          'kiesdrempel_gehaald', 'kiesdrempel_gemist', 'lijsttrekkers', 
                          'tv_debatten', 'bronnen'];
            
            $query = "INSERT INTO nederlandse_verkiezingen 
                (jaar, partij_uitslagen, coalitie_partijen, coalitie_zetels, coalitie_type, 
                 oppositie_partijen, minister_president, minister_president_partij, kabinet_naam,
                 kabinet_type, totaal_zetels, totaal_stemmen, opkomst_percentage, kiesdrempel_percentage,
                 verkiezingsdata, kabinet_start_datum, kabinet_eind_datum, formatie_duur_dagen,
                 verkiezings_aanleiding, belangrijkste_themas, belangrijke_gebeurtenissen,
                 opvallende_feiten, nieuwe_partijen, verdwenen_partijen, grootste_winnaar,
                 grootste_winnaar_aantal, grootste_verliezer, grootste_verliezer_aantal,
                 aantal_partijen_tk, kiesdrempel_gehaald, kiesdrempel_gemist, lijsttrekkers,
                 tv_debatten, verkiezingsuitslag_tijd, opkomst_verschil_vorige, beschrijving,
                 bronnen, foto_url)
                VALUES
                (:jaar, :partij_uitslagen, :coalitie_partijen, :coalitie_zetels, :coalitie_type,
                 :oppositie_partijen, :minister_president, :minister_president_partij, :kabinet_naam,
                 :kabinet_type, :totaal_zetels, :totaal_stemmen, :opkomst_percentage, :kiesdrempel_percentage,
                 :verkiezingsdata, :kabinet_start_datum, :kabinet_eind_datum, :formatie_duur_dagen,
                 :verkiezings_aanleiding, :belangrijkste_themas, :belangrijke_gebeurtenissen,
                 :opvallende_feiten, :nieuwe_partijen, :verdwenen_partijen, :grootste_winnaar,
                 :grootste_winnaar_aantal, :grootste_verliezer, :grootste_verliezer_aantal,
                 :aantal_partijen_tk, :kiesdrempel_gehaald, :kiesdrempel_gemist, :lijsttrekkers,
                 :tv_debatten, :verkiezingsuitslag_tijd, :opkomst_verschil_vorige, :beschrijving,
                 :bronnen, :foto_url)";
            
            $this->db->query($query);
            $this->bindVerkiezingData($data, $jsonFields);
            
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
            $allowed = ['partij_uitslagen', 'coalitie_partijen', 'coalitie_zetels', 'coalitie_type',
                       'oppositie_partijen', 'minister_president', 'minister_president_partij',
                       'kabinet_naam', 'kabinet_type', 'totaal_zetels', 'totaal_stemmen',
                       'opkomst_percentage', 'verkiezingsdata', 'beschrijving'];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE nederlandse_verkiezingen SET " . implode(', ', $fields) . " WHERE jaar = :jaar";
            $this->db->query($query);
            
            $jsonFields = ['partij_uitslagen', 'coalitie_partijen', 'oppositie_partijen'];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    if (in_array($field, $jsonFields)) {
                        $this->db->bind(":{$field}", json_encode($data[$field]));
                    } else {
                        $this->db->bind(":{$field}", $data[$field]);
                    }
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
            $this->db->query("DELETE FROM nederlandse_verkiezingen WHERE jaar = :jaar");
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
        $jsonFields = ['partij_uitslagen', 'coalitie_partijen', 'oppositie_partijen',
                      'belangrijkste_themas', 'nieuwe_partijen', 'verdwenen_partijen',
                      'kiesdrempel_gehaald', 'kiesdrempel_gemist', 'lijsttrekkers',
                      'tv_debatten', 'bronnen'];
        
        foreach ($jsonFields as $field) {
            if (isset($verkiezing->$field) && $verkiezing->$field) {
                $verkiezing->$field = json_decode($verkiezing->$field);
            }
        }
    }
    
    private function bindVerkiezingData($data, $jsonFields) {
        $allFields = ['jaar', 'partij_uitslagen', 'coalitie_partijen', 'coalitie_zetels',
                     'coalitie_type', 'oppositie_partijen', 'minister_president',
                     'minister_president_partij', 'kabinet_naam', 'kabinet_type', 'totaal_zetels',
                     'totaal_stemmen', 'opkomst_percentage', 'kiesdrempel_percentage',
                     'verkiezingsdata', 'kabinet_start_datum', 'kabinet_eind_datum',
                     'formatie_duur_dagen', 'verkiezings_aanleiding', 'belangrijkste_themas',
                     'belangrijke_gebeurtenissen', 'opvallende_feiten', 'nieuwe_partijen',
                     'verdwenen_partijen', 'grootste_winnaar', 'grootste_winnaar_aantal',
                     'grootste_verliezer', 'grootste_verliezer_aantal', 'aantal_partijen_tk',
                     'kiesdrempel_gehaald', 'kiesdrempel_gemist', 'lijsttrekkers', 'tv_debatten',
                     'verkiezingsuitslag_tijd', 'opkomst_verschil_vorige', 'beschrijving',
                     'bronnen', 'foto_url'];
        
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

