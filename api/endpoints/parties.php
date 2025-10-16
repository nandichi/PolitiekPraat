<?php
/**
 * Parties API Endpoint
 * Handles all requests for political parties data
 */

class PartiesAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $action = $segments[1] ?? null;
        $id = $segments[2] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($action && !in_array($action, ['polling', 'standpoints'])) {
                    $this->getParty($action);
                } elseif ($id && ($action === 'polling' || $action === 'standpoints')) {
                    if ($action === 'polling') {
                        $this->getPartyPolling($id);
                    } else {
                        $this->getPartyStandpoints($id);
                    }
                } else {
                    $this->getAllParties();
                }
                break;
                
            case 'POST':
                $this->createParty();
                break;
                
            case 'PUT':
                if ($action) {
                    $this->updateParty($action);
                } else {
                    sendApiError('Party ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($action) {
                    $this->deleteParty($action);
                } else {
                    sendApiError('Party ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllParties() {
        try {
            $active_only = isset($_GET['active']) && $_GET['active'] === 'true';
            
            $query = "SELECT * FROM political_parties";
            if ($active_only) {
                $query .= " WHERE is_active = 1";
            }
            $query .= " ORDER BY current_seats DESC";
            
            $this->db->query($query);
            $parties = $this->db->resultSet();
            
            foreach ($parties as &$party) {
                $party->standpoints = json_decode($party->standpoints);
                $party->polling = json_decode($party->polling);
                $party->perspectives = json_decode($party->perspectives);
                $party->is_active = (bool)$party->is_active;
            }
            
            sendApiResponse([
                'parties' => $parties,
                'total' => count($parties)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen partijen: ' . $e->getMessage(), 500);
        }
    }
    
    private function getParty($identifier) {
        try {
            if (is_numeric($identifier)) {
                $this->db->query("SELECT * FROM political_parties WHERE id = :id");
                $this->db->bind(':id', $identifier);
            } else {
                $this->db->query("SELECT * FROM political_parties WHERE party_key = :party_key");
                $this->db->bind(':party_key', strtoupper($identifier));
            }
            
            $party = $this->db->single();
            
            if (!$party) {
                sendApiError('Partij niet gevonden', 404);
            }
            
            $party->standpoints = json_decode($party->standpoints);
            $party->polling = json_decode($party->polling);
            $party->perspectives = json_decode($party->perspectives);
            $party->is_active = (bool)$party->is_active;
            
            sendApiResponse(['party' => $party]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen partij: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPartyPolling($identifier) {
        try {
            if (is_numeric($identifier)) {
                $this->db->query("SELECT polling, name, party_key FROM political_parties WHERE id = :id");
                $this->db->bind(':id', $identifier);
            } else {
                $this->db->query("SELECT polling, name, party_key FROM political_parties WHERE party_key = :party_key");
                $this->db->bind(':party_key', strtoupper($identifier));
            }
            
            $party = $this->db->single();
            
            if (!$party) {
                sendApiError('Partij niet gevonden', 404);
            }
            
            sendApiResponse([
                'party_name' => $party->name,
                'party_key' => $party->party_key,
                'polling' => json_decode($party->polling)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen polling data: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPartyStandpoints($identifier) {
        try {
            if (is_numeric($identifier)) {
                $this->db->query("SELECT standpoints, name, party_key FROM political_parties WHERE id = :id");
                $this->db->bind(':id', $identifier);
            } else {
                $this->db->query("SELECT standpoints, name, party_key FROM political_parties WHERE party_key = :party_key");
                $this->db->bind(':party_key', strtoupper($identifier));
            }
            
            $party = $this->db->single();
            
            if (!$party) {
                sendApiError('Partij niet gevonden', 404);
            }
            
            sendApiResponse([
                'party_name' => $party->name,
                'party_key' => $party->party_key,
                'standpoints' => json_decode($party->standpoints)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen standpunten: ' . $e->getMessage(), 500);
        }
    }
    
    private function createParty() {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $required = ['party_key', 'name', 'leader', 'description', 'current_seats', 'color'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                }
            }
            
            $standpoints = isset($data['standpoints']) ? json_encode($data['standpoints']) : '{}';
            $polling = isset($data['polling']) ? json_encode($data['polling']) : '{}';
            $perspectives = isset($data['perspectives']) ? json_encode($data['perspectives']) : '{}';
            
            $this->db->query("INSERT INTO political_parties 
                (party_key, name, leader, logo, leader_photo, description, leader_info, 
                 standpoints, current_seats, polling, perspectives, color, is_active) 
                VALUES 
                (:party_key, :name, :leader, :logo, :leader_photo, :description, :leader_info,
                 :standpoints, :current_seats, :polling, :perspectives, :color, :is_active)");
            
            $this->db->bind(':party_key', strtoupper($data['party_key']));
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':leader', $data['leader']);
            $this->db->bind(':logo', $data['logo'] ?? '');
            $this->db->bind(':leader_photo', $data['leader_photo'] ?? '');
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':leader_info', $data['leader_info'] ?? '');
            $this->db->bind(':standpoints', $standpoints);
            $this->db->bind(':current_seats', $data['current_seats']);
            $this->db->bind(':polling', $polling);
            $this->db->bind(':perspectives', $perspectives);
            $this->db->bind(':color', $data['color']);
            $this->db->bind(':is_active', $data['is_active'] ?? 1);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Partij succesvol aangemaakt',
                    'party_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken partij', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken partij: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateParty($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $fields = [];
            $allowed_fields = ['party_key', 'name', 'leader', 'logo', 'leader_photo', 
                              'description', 'leader_info', 'current_seats', 'color', 'is_active'];
            
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (isset($data['standpoints'])) $fields[] = "standpoints = :standpoints";
            if (isset($data['polling'])) $fields[] = "polling = :polling";
            if (isset($data['perspectives'])) $fields[] = "perspectives = :perspectives";
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
            }
            
            $query = "UPDATE political_parties SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($query);
            
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            }
            
            if (isset($data['standpoints'])) $this->db->bind(':standpoints', json_encode($data['standpoints']));
            if (isset($data['polling'])) $this->db->bind(':polling', json_encode($data['polling']));
            if (isset($data['perspectives'])) $this->db->bind(':perspectives', json_encode($data['perspectives']));
            
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Partij succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken partij', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken partij: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteParty($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $this->db->query("DELETE FROM political_parties WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Partij succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen partij', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen partij: ' . $e->getMessage(), 500);
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

