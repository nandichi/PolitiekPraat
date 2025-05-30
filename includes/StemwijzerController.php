<?php
class StemwijzerController {
    private $db;
    private $schemaType;

    public function __construct() {
        $this->db = new Database();
        $this->detectSchemaType();
    }

    /**
     * Detecteer welk schema type we gebruiken
     */
    private function detectSchemaType() {
        try {
            // Probeer nieuwe schema tabel
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_questions'");
            $newSchema = $this->db->single();
            
            if ($newSchema) {
                // Controleer of de is_active kolom bestaat
                $this->db->query("SHOW COLUMNS FROM stemwijzer_questions LIKE 'is_active'");
                $hasIsActive = $this->db->single();
                
                $this->schemaType = $hasIsActive ? 'new' : 'new_without_active';
            } else {
                // Probeer oude schema tabel
                $this->db->query("SHOW TABLES LIKE 'questions'");
                $oldSchema = $this->db->single();
                
                $this->schemaType = $oldSchema ? 'old' : 'unknown';
            }
        } catch (Exception $e) {
            $this->schemaType = 'unknown';
        }
    }

    /**
     * Haal alle actieve vragen op uit de database
     */
    public function getQuestions() {
        if ($this->schemaType === 'old') {
            // Oude schema
            $this->db->query("
                SELECT 
                    question_id as id,
                    title,
                    description,
                    context,
                    left_view,
                    right_view,
                    question_id as order_number
                FROM questions 
                ORDER BY question_id ASC
            ");
        } else if ($this->schemaType === 'new_without_active') {
            // Nieuwe schema zonder is_active kolom
            $this->db->query("
                SELECT 
                    id,
                    title,
                    description,
                    context,
                    left_view,
                    right_view,
                    order_number
                FROM stemwijzer_questions 
                ORDER BY order_number ASC
            ");
        } else {
            // Nieuwe schema met is_active kolom
            $this->db->query("
                SELECT 
                    id,
                    title,
                    description,
                    context,
                    left_view,
                    right_view,
                    order_number
                FROM stemwijzer_questions 
                WHERE is_active = 1 
                ORDER BY order_number ASC
            ");
        }
        
        return $this->db->resultSet();
    }

    /**
     * Haal alle actieve partijen op uit de database
     */
    public function getParties() {
        if ($this->schemaType === 'old') {
            // Oude schema
            $this->db->query("
                SELECT 
                    party_id as id,
                    party_name as name,
                    party_name as short_name,
                    party_logo as logo_url
                FROM parties 
                ORDER BY party_name ASC
            ");
        } else if ($this->schemaType === 'new_without_active') {
            // Nieuwe schema zonder is_active kolom
            $this->db->query("
                SELECT 
                    id,
                    name,
                    short_name,
                    logo_url
                FROM stemwijzer_parties 
                ORDER BY name ASC
            ");
        } else {
            // Nieuwe schema met is_active kolom
            $this->db->query("
                SELECT 
                    id,
                    name,
                    short_name,
                    logo_url
                FROM stemwijzer_parties 
                WHERE is_active = 1 
                ORDER BY name ASC
            ");
        }
        
        return $this->db->resultSet();
    }

    /**
     * Haal de standpunten van alle partijen op voor een specifieke vraag
     */
    public function getPositionsForQuestion($questionId) {
        if ($this->schemaType === 'old') {
            // Oude schema
            $this->db->query("
                SELECT 
                    p.party_name as party_name,
                    p.party_name as short_name,
                    pos.stance as position,
                    pos.explanation
                FROM positions pos
                JOIN parties p ON pos.party_id = p.party_id
                WHERE pos.question_id = :question_id 
                ORDER BY p.party_name ASC
            ");
        } else {
            // Nieuwe schema (beide varianten)
            if ($this->schemaType === 'new_without_active') {
                $this->db->query("
                    SELECT 
                        sp.name as party_name,
                        sp.short_name,
                        spos.position,
                        spos.explanation
                    FROM stemwijzer_positions spos
                    JOIN stemwijzer_parties sp ON spos.party_id = sp.id
                    WHERE spos.question_id = :question_id 
                    ORDER BY sp.name ASC
                ");
            } else {
                $this->db->query("
                    SELECT 
                        sp.name as party_name,
                        sp.short_name,
                        spos.position,
                        spos.explanation
                    FROM stemwijzer_positions spos
                    JOIN stemwijzer_parties sp ON spos.party_id = sp.id
                    WHERE spos.question_id = :question_id 
                    AND sp.is_active = 1
                    ORDER BY sp.name ASC
                ");
            }
        }
        
        $this->db->bind(':question_id', $questionId);
        $results = $this->db->resultSet();
        
        // Converteer naar associatieve array voor gemakkelijke toegang
        $positions = [];
        $explanations = [];
        
        foreach ($results as $result) {
            $positions[$result->short_name] = $result->position;
            $explanations[$result->short_name] = $result->explanation;
        }
        
        return [
            'positions' => $positions,
            'explanations' => $explanations
        ];
    }

    /**
     * Haal alle data voor de stemwijzer op (vragen + standpunten)
     */
    public function getStemwijzerData() {
        $questions = $this->getQuestions();
        $parties = $this->getParties();
        
        // Voeg standpunten toe aan elke vraag
        foreach ($questions as $question) {
            $positionData = $this->getPositionsForQuestion($question->id);
            $question->positions = $positionData['positions'];
            $question->explanations = $positionData['explanations'];
        }
        
        // Maak partij logo mapping
        $partyLogos = [];
        foreach ($parties as $party) {
            $partyLogos[$party->short_name] = $party->logo_url;
        }
        
        return [
            'questions' => $questions,
            'parties' => $parties,
            'partyLogos' => $partyLogos,
            'schema_type' => $this->schemaType // Voor debugging
        ];
    }

    /**
     * Sla de resultaten van een gebruiker op
     */
    public function saveResults($sessionId, $answers, $results, $userId = null) {
        // Voor oude schema, skip dit omdat er geen results tabel is
        if ($this->schemaType === 'old') {
            return true; // Simuleer success voor nu
        }
        
        $this->db->query("
            INSERT INTO stemwijzer_results 
            (session_id, user_id, answers, results, ip_address, user_agent) 
            VALUES 
            (:session_id, :user_id, :answers, :results, :ip_address, :user_agent)
        ");
        
        $this->db->bind(':session_id', $sessionId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':answers', json_encode($answers));
        $this->db->bind(':results', json_encode($results));
        $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? '');
        $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? '');
        
        return $this->db->execute();
    }

    /**
     * Haal statistieken op over de stemwijzer
     */
    public function getStatistics() {
        // Voor oude schema, geef basis stats terug
        if ($this->schemaType === 'old') {
            return [
                'total_submissions' => 0,
                'last_updated' => date('Y-m-d H:i:s'),
                'schema_type' => $this->schemaType
            ];
        }
        
        // Totaal aantal ingevulde stemwijzers
        $this->db->query("SELECT COUNT(*) as total FROM stemwijzer_results");
        $total = $this->db->single()->total;
        
        return [
            'total_submissions' => $total,
            'last_updated' => date('Y-m-d H:i:s'),
            'schema_type' => $this->schemaType
        ];
    }
    
    /**
     * Get schema type voor debugging
     */
    public function getSchemaType() {
        return $this->schemaType;
    }
} 