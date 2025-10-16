<?php
/**
 * Themas API Endpoint
 * Handles political themes/topics
 */

class ThemasAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $id = $segments[1] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getThema($id);
                } else {
                    $this->getAllThemas();
                }
                break;
                
            case 'POST':
                $this->createThema();
                break;
                
            case 'PUT':
                if ($id) {
                    $this->updateThema($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $this->deleteThema($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllThemas() {
        try {
            $this->db->query("SELECT * FROM stemmentracker_themas WHERE is_active = 1 ORDER BY name");
            $themas = $this->db->resultSet();
            
            sendApiResponse([
                'themas' => $themas,
                'total' => count($themas)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen themas: ' . $e->getMessage(), 500);
        }
    }
    
    private function getThema($id) {
        try {
            $this->db->query("SELECT * FROM stemmentracker_themas WHERE id = :id");
            $this->db->bind(':id', $id);
            $thema = $this->db->single();
            
            if (!$thema) {
                sendApiError('Thema niet gevonden', 404);
                return;
            }
            
            $this->db->query("SELECT 
                    stemmentracker_moties.*
                FROM stemmentracker_moties
                JOIN stemmentracker_motie_themas ON stemmentracker_moties.id = stemmentracker_motie_themas.motie_id
                WHERE stemmentracker_motie_themas.thema_id = :thema_id
                ORDER BY datum_stemming DESC");
            $this->db->bind(':thema_id', $id);
            $moties = $this->db->resultSet();
            
            sendApiResponse([
                'thema' => $thema,
                'moties' => $moties,
                'motie_count' => count($moties)
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen thema: ' . $e->getMessage(), 500);
        }
    }
    
    private function createThema() {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['name'])) {
                sendApiError('Naam is verplicht', 400);
                return;
            }
            
            $this->db->query("INSERT INTO stemmentracker_themas (name, description, color) 
                VALUES (:name, :description, :color)");
            
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':description', $data['description'] ?? '');
            $this->db->bind(':color', $data['color'] ?? '#3B82F6');
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Thema succesvol aangemaakt',
                    'thema_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken thema', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken thema: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateThema($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $fields = [];
            $allowed = ['name', 'description', 'color', 'is_active'];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE stemmentracker_themas SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($query);
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            }
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Thema succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken thema', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken thema: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteThema($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $this->db->query("DELETE FROM stemmentracker_themas WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Thema succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen thema', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen thema: ' . $e->getMessage(), 500);
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

