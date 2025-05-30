<?php
class StemwijzerController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Haal alle actieve vragen op uit de database
     */
    public function getQuestions() {
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
        
        return $this->db->resultSet();
    }

    /**
     * Haal alle actieve partijen op uit de database
     */
    public function getParties() {
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
        
        return $this->db->resultSet();
    }

    /**
     * Haal de standpunten van alle partijen op voor een specifieke vraag
     */
    public function getPositionsForQuestion($questionId) {
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
            'partyLogos' => $partyLogos
        ];
    }

    /**
     * Sla de resultaten van een gebruiker op
     */
    public function saveResults($sessionId, $answers, $results, $userId = null) {
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
        // Totaal aantal ingevulde stemwijzers
        $this->db->query("SELECT COUNT(*) as total FROM stemwijzer_results");
        $total = $this->db->single()->total;
        
        // Meest populaire antwoorden per vraag
        $this->db->query("
            SELECT 
                sq.title,
                JSON_EXTRACT(sr.answers, '$.*') as answers
            FROM stemwijzer_results sr
            JOIN stemwijzer_questions sq ON JSON_VALID(sr.answers)
            ORDER BY sr.completed_at DESC
            LIMIT 100
        ");
        
        return [
            'total_submissions' => $total,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
} 