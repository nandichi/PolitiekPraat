<?php
/**
 * Presidenten API Endpoint
 * Handles US presidents and Dutch prime ministers
 */

class PresidentenAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $country = $segments[1] ?? null; // 'usa' or 'nederland'
        $id = $segments[2] ?? null;
        
        if (!in_array($country, ['usa', 'nederland'])) {
            sendApiError('Gebruik /presidenten/usa of /presidenten/nederland', 400);
            return;
        }
        
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getPresident($country, $id);
                } else {
                    $this->getAllPresidenten($country);
                }
                break;
                
            case 'POST':
                $this->createPresident($country);
                break;
                
            case 'PUT':
                if ($id) {
                    $this->updatePresident($country, $id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $this->deletePresident($country, $id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllPresidenten($country) {
        try {
            $table = $country === 'usa' ? 'amerikaanse_presidenten' : 'nederlandse_ministers_presidenten';
            
            $this->db->query("SELECT * FROM {$table} ORDER BY id DESC");
            $presidenten = $this->db->resultSet();
            
            sendApiResponse([
                'presidenten' => $presidenten,
                'total' => count($presidenten),
                'country' => $country
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen presidenten: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPresident($country, $id) {
        try {
            $table = $country === 'usa' ? 'amerikaanse_presidenten' : 'nederlandse_ministers_presidenten';
            
            $this->db->query("SELECT * FROM {$table} WHERE id = :id");
            $this->db->bind(':id', $id);
            $president = $this->db->single();
            
            if (!$president) {
                sendApiError('President niet gevonden', 404);
                return;
            }
            
            sendApiResponse([
                'president' => $president,
                'country' => $country
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen president: ' . $e->getMessage(), 500);
        }
    }
    
    private function createPresident($country) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if ($country === 'usa') {
                $this->createUSAPresident($data);
            } else {
                $this->createNLPresident($data);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken president: ' . $e->getMessage(), 500);
        }
    }
    
    private function createUSAPresident($data) {
        $required = ['naam', 'partij', 'termijn_start', 'termijn_eind'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                sendApiError("Veld '{$field}' is verplicht", 400);
                return;
            }
        }
        
        $this->db->query("INSERT INTO amerikaanse_presidenten 
            (naam, partij, termijn_start, termijn_eind, foto_url, beschrijving) 
            VALUES (:naam, :partij, :termijn_start, :termijn_eind, :foto_url, :beschrijving)");
        
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':partij', $data['partij']);
        $this->db->bind(':termijn_start', $data['termijn_start']);
        $this->db->bind(':termijn_eind', $data['termijn_eind']);
        $this->db->bind(':foto_url', $data['foto_url'] ?? null);
        $this->db->bind(':beschrijving', $data['beschrijving'] ?? null);
        
        if ($this->db->execute()) {
            sendApiResponse([
                'message' => 'President succesvol aangemaakt',
                'president_id' => $this->db->lastInsertId()
            ], 201);
        } else {
            sendApiError('Fout bij aanmaken president', 500);
        }
    }
    
    private function createNLPresident($data) {
        $required = ['naam', 'partij', 'termijn_start', 'termijn_eind'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                sendApiError("Veld '{$field}' is verplicht", 400);
                return;
            }
        }
        
        $this->db->query("INSERT INTO nederlandse_ministers_presidenten 
            (naam, partij, termijn_start, termijn_eind, foto_url, kabinet, beschrijving) 
            VALUES (:naam, :partij, :termijn_start, :termijn_eind, :foto_url, :kabinet, :beschrijving)");
        
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':partij', $data['partij']);
        $this->db->bind(':termijn_start', $data['termijn_start']);
        $this->db->bind(':termijn_eind', $data['termijn_eind']);
        $this->db->bind(':foto_url', $data['foto_url'] ?? null);
        $this->db->bind(':kabinet', $data['kabinet'] ?? null);
        $this->db->bind(':beschrijving', $data['beschrijving'] ?? null);
        
        if ($this->db->execute()) {
            sendApiResponse([
                'message' => 'Minister-president succesvol aangemaakt',
                'president_id' => $this->db->lastInsertId()
            ], 201);
        } else {
            sendApiError('Fout bij aanmaken minister-president', 500);
        }
    }
    
    private function updatePresident($country, $id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $table = $country === 'usa' ? 'amerikaanse_presidenten' : 'nederlandse_ministers_presidenten';
            
            $fields = [];
            $allowed = ['naam', 'partij', 'termijn_start', 'termijn_eind', 'foto_url', 'beschrijving'];
            if ($country === 'nederland') {
                $allowed[] = 'kabinet';
            }
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($query);
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            }
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'President succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken president', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken president: ' . $e->getMessage(), 500);
        }
    }
    
    private function deletePresident($country, $id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $table = $country === 'usa' ? 'amerikaanse_presidenten' : 'nederlandse_ministers_presidenten';
            
            $this->db->query("DELETE FROM {$table} WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'President succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen president', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen president: ' . $e->getMessage(), 500);
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

