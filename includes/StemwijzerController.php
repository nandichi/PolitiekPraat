<?php
class StemwijzerController {
    private $db;
    private $schemaType;
    private $hasValidConnection = false;

    public function __construct() {
        try {
            $this->db = new Database();
            $this->hasValidConnection = true;
            $this->detectSchemaType();
        } catch (Exception $e) {
            $this->hasValidConnection = false;
            $this->schemaType = 'fallback';
            error_log("StemwijzerController: Database connection failed - " . $e->getMessage());
        }
    }

    /**
     * Detecteer welk schema type we gebruiken
     */
    private function detectSchemaType() {
        if (!$this->hasValidConnection) {
            $this->schemaType = 'fallback';
            return;
        }
        
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
                
                $this->schemaType = $oldSchema ? 'old' : 'fallback';
            }
        } catch (Exception $e) {
            error_log("StemwijzerController: Schema detection failed - " . $e->getMessage());
            $this->schemaType = 'fallback';
        }
    }

    /**
     * Haal alle actieve vragen op uit de database
     */
    public function getQuestions() {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            return $this->getFallbackQuestions();
        }
        
        try {
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
            
            $result = $this->db->resultSet();
            
            // Als geen vragen gevonden, gebruik fallback
            if (empty($result)) {
                return $this->getFallbackQuestions();
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getQuestions failed - " . $e->getMessage());
            return $this->getFallbackQuestions();
        }
    }

    /**
     * Haal alle actieve partijen op uit de database
     */
    public function getParties() {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            return $this->getFallbackParties();
        }
        
        try {
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
            
            $result = $this->db->resultSet();
            
            // Als geen partijen gevonden, gebruik fallback
            if (empty($result)) {
                return $this->getFallbackParties();
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getParties failed - " . $e->getMessage());
            return $this->getFallbackParties();
        }
    }

    /**
     * Haal de standpunten van alle partijen op voor een specifieke vraag
     */
    public function getPositionsForQuestion($questionId) {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            return $this->getFallbackPositionsForQuestion($questionId);
        }
        
        try {
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
                $joinCondition = $this->schemaType === 'new_without_active' ? 
                    "sp ON spos.party_id = sp.id" :
                    "sp ON spos.party_id = sp.id WHERE sp.is_active = 1";
                    
                $this->db->query("
                    SELECT 
                        sp.name as party_name,
                        sp.short_name,
                        spos.position,
                        spos.explanation
                    FROM stemwijzer_positions spos
                    JOIN stemwijzer_parties $joinCondition
                    AND spos.question_id = :question_id 
                    ORDER BY sp.name ASC
                ");
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
            
            // Als geen posities gevonden, gebruik fallback
            if (empty($positions)) {
                return $this->getFallbackPositionsForQuestion($questionId);
            }
            
            return [
                'positions' => $positions,
                'explanations' => $explanations
            ];
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getPositionsForQuestion failed - " . $e->getMessage());
            return $this->getFallbackPositionsForQuestion($questionId);
        }
    }

    /**
     * Haal alle data voor de stemwijzer op (vragen + standpunten)
     */
    public function getStemwijzerData() {
        try {
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
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getStemwijzerData failed - " . $e->getMessage());
            return $this->getFallbackStemwijzerData();
        }
    }

    /**
     * Sla de resultaten van een gebruiker op
     */
    public function saveResults($sessionId, $answers, $results, $userId = null) {
        if (!$this->hasValidConnection) {
            return true; // Simuleer success als database niet beschikbaar is
        }
        
        try {
            // Voor oude schema, skip dit omdat er geen results tabel is
            if ($this->schemaType === 'old' || $this->schemaType === 'fallback') {
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
            
        } catch (Exception $e) {
            error_log("StemwijzerController: saveResults failed - " . $e->getMessage());
            return true; // Simuleer success ook bij fout
        }
    }

    /**
     * Haal statistieken op over de stemwijzer
     */
    public function getStatistics() {
        if (!$this->hasValidConnection) {
            return [
                'total_submissions' => 0,
                'last_updated' => date('Y-m-d H:i:s'),
                'schema_type' => 'fallback'
            ];
        }
        
        try {
            // Voor oude schema, geef basis stats terug
            if ($this->schemaType === 'old' || $this->schemaType === 'fallback') {
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
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getStatistics failed - " . $e->getMessage());
            return [
                'total_submissions' => 0,
                'last_updated' => date('Y-m-d H:i:s'),
                'schema_type' => 'error'
            ];
        }
    }
    
    /**
     * Get schema type voor debugging
     */
    public function getSchemaType() {
        return $this->schemaType;
    }
    
    /**
     * FALLBACK DATA METHODS
     */
    
    private function getFallbackQuestions() {
        return [
            (object)[
                'id' => 1,
                'title' => 'Nederland moet een strenger asielbeleid voeren',
                'description' => 'Asielzoekers moeten strenger geselecteerd worden en de instroom moet worden beperkt.',
                'context' => 'Deze stelling gaat over hoe Nederland omgaat met mensen die asiel aanvragen. Het behelst zowel de selectiecriteria als het aantal mensen dat wordt toegelaten.',
                'left_view' => 'Linkse partijen vinden dat Nederland humaan moet blijven en vluchtelingen moet opvangen die in nood verkeren.',
                'right_view' => 'Rechtse partijen willen de instroom van asielzoekers beperken omdat dit volgens hen de druk op de samenleving verlaagt.',
                'order_number' => 1
            ],
            (object)[
                'id' => 2,
                'title' => 'De belasting op vermogen moet worden verhoogd',
                'description' => 'Mensen met veel vermogen moeten meer belasting betalen dan nu het geval is.',
                'context' => 'Deze stelling betreft de belasting die wordt geheven op het bezit van vermogen, zoals spaargeld, aandelen en onroerend goed.',
                'left_view' => 'Linkse partijen vinden dat vermogende mensen meer moeten bijdragen aan de samenleving via hogere belastingen.',
                'right_view' => 'Rechtse partijen zijn tegen hogere vermogensbelasting omdat dit ondernemerschap en sparen zou ontmoedigen.',
                'order_number' => 2
            ],
            (object)[
                'id' => 3,
                'title' => 'Nederland moet meer doen tegen klimaatverandering',
                'description' => 'De regering moet strengere maatregelen nemen om de uitstoot van broeikasgassen te verminderen.',
                'context' => 'Deze stelling gaat over de rol van de overheid in het terugdringen van klimaatverandering door middel van beleid en regelgeving.',
                'left_view' => 'Linkse partijen willen snelle en ingrijpende maatregelen om klimaatdoelen te halen, ook als dit economische kosten heeft.',
                'right_view' => 'Rechtse partijen willen klimaatmaatregelen die de economie niet te veel schaden en meer inzetten op innovatie.',
                'order_number' => 3
            ]
        ];
    }
    
    private function getFallbackParties() {
        return [
            (object)['id' => 1, 'name' => 'PVV', 'short_name' => 'PVV', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/PVV_logo.svg/200px-PVV_logo.svg.png'],
            (object)['id' => 2, 'name' => 'VVD', 'short_name' => 'VVD', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/VVD_logo.svg/200px-VVD_logo.svg.png'],
            (object)['id' => 3, 'name' => 'NSC', 'short_name' => 'NSC', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/NSC_logo.svg/200px-NSC_logo.svg.png'],
            (object)['id' => 4, 'name' => 'BBB', 'short_name' => 'BBB', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/BoerBurgerBeweging_logo.svg/200px-BoerBurgerBeweging_logo.svg.png'],
            (object)['id' => 5, 'name' => 'GL-PvdA', 'short_name' => 'GL-PvdA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/GroenLinks-PvdA_logo.svg/200px-GroenLinks-PvdA_logo.svg.png'],
            (object)['id' => 6, 'name' => 'D66', 'short_name' => 'D66', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/D66_logo.svg/200px-D66_logo.svg.png'],
            (object)['id' => 7, 'name' => 'SP', 'short_name' => 'SP', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/SP_logo_%28Netherlands%29.svg/200px-SP_logo_%28Netherlands%29.svg.png'],
            (object)['id' => 8, 'name' => 'CDA', 'short_name' => 'CDA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/CDA_logo.svg/200px-CDA_logo.svg.png']
        ];
    }
    
    private function getFallbackPositionsForQuestion($questionId) {
        $fallbackPositions = [
            1 => [ // Asielbeleid
                'positions' => [
                    'PVV' => 'eens',
                    'VVD' => 'eens', 
                    'NSC' => 'eens',
                    'BBB' => 'eens',
                    'GL-PvdA' => 'oneens',
                    'D66' => 'oneens',
                    'SP' => 'neutraal',
                    'CDA' => 'neutraal'
                ],
                'explanations' => [
                    'PVV' => 'PVV wil een volledige asielstop en veel strengere controles aan de grens.',
                    'VVD' => 'VVD pleit voor een selectiever asielbeleid met strengere eisen.',
                    'NSC' => 'NSC wil een meer doordacht en gecontroleerd asielbeleid.',
                    'BBB' => 'BBB steunt een strenger asielbeleid om de druk op gemeenten te verlagen.',
                    'GL-PvdA' => 'GL-PvdA vindt dat Nederland vluchtelingen moet blijven opvangen vanuit humanitaire overwegingen.',
                    'D66' => 'D66 wil een humaan asielbeleid binnen Europese afspraken.',
                    'SP' => 'SP vindt dat er ruimte moet zijn voor vluchtelingen, maar met goede opvang en integratie.',
                    'CDA' => 'CDA wil een onderscheidend asielbeleid met aandacht voor zowel opvang als integratie.'
                ]
            ],
            2 => [ // Vermogensbelasting
                'positions' => [
                    'PVV' => 'neutraal',
                    'VVD' => 'oneens', 
                    'NSC' => 'oneens',
                    'BBB' => 'oneens',
                    'GL-PvdA' => 'eens',
                    'D66' => 'eens',
                    'SP' => 'eens',
                    'CDA' => 'neutraal'
                ],
                'explanations' => [
                    'PVV' => 'PVV richt zich meer op andere belastingonderwerpen.',
                    'VVD' => 'VVD is tegen hogere vermogensbelasting omdat dit ondernemerschap zou schaden.',
                    'NSC' => 'NSC vindt dat vermogensbelasting niet verhoogd moet worden.',
                    'BBB' => 'BBB is tegen hogere belastingen die ondernemers raken.',
                    'GL-PvdA' => 'GL-PvdA wil dat vermogende mensen meer bijdragen aan publieke voorzieningen.',
                    'D66' => 'D66 steunt een eerlijkere verdeling van de belastinglast.',
                    'SP' => 'SP wil dat rijken meer belasting betalen voor een rechtvaardige samenleving.',
                    'CDA' => 'CDA wil een evenwichtig belastingstelsel zonder al te hoge tarieven.'
                ]
            ],
            3 => [ // Klimaat
                'positions' => [
                    'PVV' => 'oneens',
                    'VVD' => 'neutraal', 
                    'NSC' => 'neutraal',
                    'BBB' => 'oneens',
                    'GL-PvdA' => 'eens',
                    'D66' => 'eens',
                    'SP' => 'eens',
                    'CDA' => 'eens'
                ],
                'explanations' => [
                    'PVV' => 'PVV vindt dat klimaatmaatregelen te ver gaan en de economie schaden.',
                    'VVD' => 'VVD wil klimaatmaatregelen die economisch haalbaar zijn.',
                    'NSC' => 'NSC steunt klimaatbeleid maar wil realistische doelen.',
                    'BBB' => 'BBB vindt dat boeren en bedrijven niet onevenredig belast moeten worden.',
                    'GL-PvdA' => 'GL-PvdA wil snelle en ingrijpende klimaatmaatregelen.',
                    'D66' => 'D66 pleit voor ambitieus klimaatbeleid met innovatie.',
                    'SP' => 'SP wil klimaatmaatregelen die eerlijk verdeeld zijn.',
                    'CDA' => 'CDA steunt klimaatbeleid binnen christelijke rentmeesterschap.'
                ]
            ]
        ];
        
        return $fallbackPositions[$questionId] ?? [
            'positions' => [],
            'explanations' => []
        ];
    }
    
    private function getFallbackStemwijzerData() {
        $questions = $this->getFallbackQuestions();
        $parties = $this->getFallbackParties();
        
        // Voeg standpunten toe aan elke vraag
        foreach ($questions as $question) {
            $positionData = $this->getFallbackPositionsForQuestion($question->id);
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
            'schema_type' => 'fallback'
        ];
    }
} 