<?php
/**
 * Stemmentracker API Endpoint
 * Handles parliamentary votes and motions
 */

class StemmentrackerAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $resource = $segments[1] ?? null;
        $id = $segments[2] ?? null;
        $action = $segments[3] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($resource === 'moties' && $id && $action === 'votes') {
                    $this->getMotieVotes($id);
                } elseif ($resource === 'moties' && $id) {
                    $this->getMotie($id);
                } elseif ($resource === 'moties') {
                    $this->getAllMoties();
                } elseif ($resource === 'parties' && $id && $action === 'votes') {
                    $this->getPartyVotes($id);
                } else {
                    sendApiError('Ongeldige route', 404);
                }
                break;
                
            case 'POST':
                if ($resource === 'moties' && $id && $action === 'votes') {
                    $this->addVote($id);
                } elseif ($resource === 'moties') {
                    $this->createMotie();
                } else {
                    sendApiError('Ongeldige route', 404);
                }
                break;
                
            case 'PUT':
                if ($resource === 'moties' && $id) {
                    $this->updateMotie($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            case 'DELETE':
                if ($resource === 'moties' && $id) {
                    $this->deleteMotie($id);
                } else {
                    sendApiError('ID vereist', 400);
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan', 405);
        }
    }
    
    private function getAllMoties() {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 20;
            $offset = ($page - 1) * $limit;
            
            $where = [];
            $params = [];
            
            if (isset($_GET['thema'])) {
                $where[] = "EXISTS (SELECT 1 FROM stemmentracker_motie_themas mt 
                           JOIN stemmentracker_themas t ON mt.thema_id = t.id 
                           WHERE mt.motie_id = stemmentracker_moties.id AND t.name = :thema)";
                $params['thema'] = $_GET['thema'];
            }
            
            if (isset($_GET['uitslag'])) {
                $where[] = "uitslag = :uitslag";
                $params['uitslag'] = $_GET['uitslag'];
            }
            
            if (isset($_GET['datum_van'])) {
                $where[] = "datum_stemming >= :datum_van";
                $params['datum_van'] = $_GET['datum_van'];
            }
            
            if (isset($_GET['datum_tot'])) {
                $where[] = "datum_stemming <= :datum_tot";
                $params['datum_tot'] = $_GET['datum_tot'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";
            
            $this->db->query("SELECT COUNT(*) as total FROM stemmentracker_moties {$whereClause}");
            foreach ($params as $key => $value) {
                $this->db->bind(":{$key}", $value);
            }
            $total = $this->db->single()->total;
            
            $this->db->query("SELECT * FROM stemmentracker_moties {$whereClause} 
                ORDER BY datum_stemming DESC LIMIT :limit OFFSET :offset");
            foreach ($params as $key => $value) {
                $this->db->bind(":{$key}", $value);
            }
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            
            $moties = $this->db->resultSet();
            
            sendApiResponse([
                'moties' => $moties,
                'pagination' => [
                    'current_page' => $page,
                    'limit' => $limit,
                    'total' => intval($total),
                    'total_pages' => ceil($total / $limit),
                    'has_next' => $page < ceil($total / $limit),
                    'has_prev' => $page > 1
                ]
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen moties: ' . $e->getMessage(), 500);
        }
    }
    
    private function getMotie($id) {
        try {
            $this->db->query("SELECT * FROM stemmentracker_moties WHERE id = :id");
            $this->db->bind(':id', $id);
            $motie = $this->db->single();
            
            if (!$motie) {
                sendApiError('Motie niet gevonden', 404);
                return;
            }
            
            $this->db->query("SELECT 
                    stemmentracker_votes.*,
                    stemwijzer_parties.name as party_name,
                    stemwijzer_parties.short_name as party_key
                FROM stemmentracker_votes
                JOIN stemwijzer_parties ON stemmentracker_votes.party_id = stemwijzer_parties.id
                WHERE stemmentracker_votes.motie_id = :motie_id");
            $this->db->bind(':motie_id', $id);
            $votes = $this->db->resultSet();
            
            sendApiResponse([
                'motie' => $motie,
                'votes' => $votes
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen motie: ' . $e->getMessage(), 500);
        }
    }
    
    private function getMotieVotes($id) {
        try {
            $this->db->query("SELECT 
                    stemmentracker_votes.*,
                    stemwijzer_parties.name as party_name,
                    stemwijzer_parties.short_name as party_key
                FROM stemmentracker_votes
                JOIN stemwijzer_parties ON stemmentracker_votes.party_id = stemwijzer_parties.id
                WHERE stemmentracker_votes.motie_id = :motie_id");
            $this->db->bind(':motie_id', $id);
            $votes = $this->db->resultSet();
            
            sendApiResponse(['votes' => $votes]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen votes: ' . $e->getMessage(), 500);
        }
    }
    
    private function getPartyVotes($id) {
        try {
            $this->db->query("SELECT 
                    stemmentracker_votes.*,
                    stemmentracker_moties.title as motie_title,
                    stemmentracker_moties.datum_stemming
                FROM stemmentracker_votes
                JOIN stemmentracker_moties ON stemmentracker_votes.motie_id = stemmentracker_moties.id
                WHERE stemmentracker_votes.party_id = :party_id
                ORDER BY stemmentracker_moties.datum_stemming DESC");
            $this->db->bind(':party_id', $id);
            $votes = $this->db->resultSet();
            
            sendApiResponse(['votes' => $votes]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen party votes: ' . $e->getMessage(), 500);
        }
    }
    
    private function createMotie() {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $required = ['title', 'description', 'datum_stemming', 'onderwerp'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                    return;
                }
            }
            
            $this->db->query("INSERT INTO stemmentracker_moties 
                (title, description, motie_nummer, kamerstuk_nummer, datum_stemming, onderwerp, 
                 indiener, uitslag, stemming_type, kamer_stuk_url) 
                VALUES 
                (:title, :description, :motie_nummer, :kamerstuk_nummer, :datum_stemming, :onderwerp,
                 :indiener, :uitslag, :stemming_type, :kamer_stuk_url)");
            
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':motie_nummer', $data['motie_nummer'] ?? null);
            $this->db->bind(':kamerstuk_nummer', $data['kamerstuk_nummer'] ?? null);
            $this->db->bind(':datum_stemming', $data['datum_stemming']);
            $this->db->bind(':onderwerp', $data['onderwerp']);
            $this->db->bind(':indiener', $data['indiener'] ?? null);
            $this->db->bind(':uitslag', $data['uitslag'] ?? null);
            $this->db->bind(':stemming_type', $data['stemming_type'] ?? 'hoofdelijke');
            $this->db->bind(':kamer_stuk_url', $data['kamer_stuk_url'] ?? null);
            
            if ($this->db->execute()) {
                sendApiResponse([
                    'message' => 'Motie succesvol aangemaakt',
                    'motie_id' => $this->db->lastInsertId()
                ], 201);
            } else {
                sendApiError('Fout bij aanmaken motie', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij aanmaken motie: ' . $e->getMessage(), 500);
        }
    }
    
    private function updateMotie($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $fields = [];
            $allowed = ['title', 'description', 'motie_nummer', 'kamerstuk_nummer', 'datum_stemming',
                       'onderwerp', 'indiener', 'uitslag', 'stemming_type', 'kamer_stuk_url', 'is_active'];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                }
            }
            
            if (empty($fields)) {
                sendApiError('Geen velden om bij te werken', 400);
                return;
            }
            
            $query = "UPDATE stemmentracker_moties SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($query);
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $this->db->bind(":{$field}", $data[$field]);
                }
            }
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Motie succesvol bijgewerkt']);
            } else {
                sendApiError('Fout bij bijwerken motie', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij bijwerken motie: ' . $e->getMessage(), 500);
        }
    }
    
    private function deleteMotie($id) {
        if (!$this->requireAdmin()) return;
        
        try {
            $this->db->query("DELETE FROM stemmentracker_moties WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Motie succesvol verwijderd']);
            } else {
                sendApiError('Fout bij verwijderen motie', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij verwijderen motie: ' . $e->getMessage(), 500);
        }
    }
    
    private function addVote($motieId) {
        if (!$this->requireAdmin()) return;
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['party_id']) || empty($data['vote'])) {
                sendApiError('party_id en vote zijn verplicht', 400);
                return;
            }
            
            $this->db->query("INSERT INTO stemmentracker_votes (motie_id, party_id, vote, aantal_zetels, opmerking) 
                VALUES (:motie_id, :party_id, :vote, :aantal_zetels, :opmerking)
                ON DUPLICATE KEY UPDATE vote = :vote, aantal_zetels = :aantal_zetels, opmerking = :opmerking");
            
            $this->db->bind(':motie_id', $motieId);
            $this->db->bind(':party_id', $data['party_id']);
            $this->db->bind(':vote', $data['vote']);
            $this->db->bind(':aantal_zetels', $data['aantal_zetels'] ?? 1);
            $this->db->bind(':opmerking', $data['opmerking'] ?? null);
            
            if ($this->db->execute()) {
                sendApiResponse(['message' => 'Vote succesvol toegevoegd'], 201);
            } else {
                sendApiError('Fout bij toevoegen vote', 500);
            }
            
        } catch (Exception $e) {
            sendApiError('Fout bij toevoegen vote: ' . $e->getMessage(), 500);
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

