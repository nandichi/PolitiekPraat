<?php
class StemwijzerController {
    private $db;
    private $schemaType;
    private $hasValidConnection = false;
    private $questionsTableHasIsActive = false;

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
            // Probeer nieuwe schema tabel voor vragen
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_questions'");
            $newSchemaQuestions = $this->db->single();
            
            if ($newSchemaQuestions) {
                $this->db->query("SHOW COLUMNS FROM stemwijzer_questions LIKE 'is_active'");
                if ($this->db->single()) {
                    $this->questionsTableHasIsActive = true;
                    $this->schemaType = 'new'; // Vragen tabel heeft is_active
                } else {
                    $this->questionsTableHasIsActive = false;
                    $this->schemaType = 'new_without_active'; // Vragen tabel zonder is_active
                }
            } else {
                // Probeer oude schema tabel
                $this->db->query("SHOW TABLES LIKE 'questions'");
                if ($this->db->single()) {
                    $this->schemaType = 'old';
                } else {
                    $this->schemaType = 'fallback'; // Geen herkenbare vragen tabel
                }
            }
            error_log("StemwijzerController: Detected schemaType: " . $this->schemaType . ", questionsTableHasIsActive: " . ($this->questionsTableHasIsActive ? 'true' : 'false'));
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
            error_log("StemwijzerController: getQuestions using fallback (no connection or fallback schema)");
            return $this->getFallbackQuestions();
        }
        
        try {
            $sql = "";
            switch ($this->schemaType) {
                case 'new': // stemwijzer_questions MET is_active
                    $sql = "SELECT id, title, description, context, left_view, right_view, order_number FROM stemwijzer_questions WHERE is_active = 1 ORDER BY order_number ASC";
                    break;
                case 'new_without_active': // stemwijzer_questions ZONDER is_active
                    $sql = "SELECT id, title, description, context, left_view, right_view, order_number FROM stemwijzer_questions ORDER BY order_number ASC";
                    break;
                case 'old':
                    $sql = "SELECT question_id as id, title, description, context, left_view, right_view, question_id as order_number FROM questions ORDER BY question_id ASC";
                    break;
                default:
                    error_log("StemwijzerController: getQuestions using fallback (unknown schema)");
                    return $this->getFallbackQuestions();
            }
            
            $this->db->query($sql);
            $result = $this->db->resultSet();
            
            if (empty($result)) {
                error_log("StemwijzerController: getQuestions - No questions found in DB for schema " . $this->schemaType . ", using fallback.");
                return $this->getFallbackQuestions();
            }
            error_log("StemwijzerController: getQuestions - Found " . count($result) . " questions from DB for schema " . $this->schemaType);
            return $result;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getQuestions failed - " . $e->getMessage() . ", using fallback.");
            return $this->getFallbackQuestions();
        }
    }

    /**
     * Haal alle partijen op uit de database
     * BELANGRIJK: De stemwijzer_parties tabel heeft GEEN is_active kolom volgens de screenshots.
     */
    public function getParties() {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            error_log("StemwijzerController: getParties using fallback (no connection or fallback schema)");
            return $this->getFallbackParties();
        }
        
        try {
            $sql = "";
            // Voor partijen gebruiken we stemwijzer_parties als die bestaat, anders fallback naar oude 'parties' tabel
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_parties'");
            $hasNewPartiesTable = $this->db->single();

            if ($hasNewPartiesTable) {
                // Nieuwe schema voor partijen (ongeacht 'is_active' status van vragen tabel)
                // Er is GEEN is_active kolom in stemwijzer_parties
                $sql = "SELECT id, name, short_name, logo_url FROM stemwijzer_parties ORDER BY name ASC";
            } elseif ($this->schemaType === 'old') {
                // Oude schema voor partijen
                $sql = "SELECT party_id as id, party_name as name, party_name as short_name, party_logo as logo_url FROM parties ORDER BY party_name ASC";
            } else {
                error_log("StemwijzerController: getParties - No suitable parties table found, using fallback.");
                return $this->getFallbackParties();
            }
            
            $this->db->query($sql);
            $result = $this->db->resultSet();
            
            if (empty($result)) {
                error_log("StemwijzerController: getParties - No parties found in DB, using fallback.");
                return $this->getFallbackParties();
            }
            error_log("StemwijzerController: getParties - Found " . count($result) . " parties from DB.");
            return $result;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getParties failed - " . $e->getMessage() . ", using fallback.");
            return $this->getFallbackParties();
        }
    }

    /**
     * Haal de standpunten van alle partijen op voor een specifieke vraag
     */
    public function getPositionsForQuestion($questionId) {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            error_log("StemwijzerController: getPositionsForQuestion using fallback (no connection or fallback schema) for QID: " . $questionId);
            return $this->getFallbackPositionsForQuestion($questionId);
        }
        
        try {
            $sql = "";
            // Bepaal welke position en parties tabel te gebruiken
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_positions'");
            $hasNewPositionsTable = $this->db->single();
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_parties'");
            $hasNewPartiesTable = $this->db->single();

            if ($hasNewPositionsTable && $hasNewPartiesTable) {
                // Nieuwe schema: stemwijzer_positions en stemwijzer_parties
                // stemwijzer_parties.is_active wordt NIET gebruikt
                $sql = "SELECT sp.name as party_name, sp.short_name, spos.position, spos.explanation 
                        FROM stemwijzer_positions spos
                        JOIN stemwijzer_parties sp ON spos.party_id = sp.id
                        WHERE spos.question_id = :question_id 
                        ORDER BY sp.name ASC";
            } elseif ($this->schemaType === 'old') {
                // Oude schema: positions en parties
                $sql = "SELECT p.party_name as party_name, p.party_name as short_name, pos.stance as position, pos.explanation 
                        FROM positions pos
                        JOIN parties p ON pos.party_id = p.party_id
                        WHERE pos.question_id = :question_id 
                        ORDER BY p.party_name ASC";
            } else {
                error_log("StemwijzerController: getPositionsForQuestion - No suitable positions/parties tables found for QID: " . $questionId . ", using fallback.");
                return $this->getFallbackPositionsForQuestion($questionId);
            }
            
            $this->db->query($sql);
            $this->db->bind(':question_id', $questionId);
            $results = $this->db->resultSet();
            
            $positions = [];
            $explanations = [];
            foreach ($results as $result) {
                $shortName = $result->short_name ?: $result->party_name; // Fallback naar party_name als short_name leeg is
                if (empty($shortName)) continue; // Sla op als er geen naam is
                $positions[$shortName] = $result->position;
                $explanations[$shortName] = $result->explanation;
            }
            
            if (empty($positions)) {
                error_log("StemwijzerController: No positions found for question $questionId in DB, using fallback for positions.");
                return $this->getFallbackPositionsForQuestion($questionId); // Geeft fallback posities als DB leeg is.
            }
            error_log("StemwijzerController: getPositionsForQuestion - Found " . count($positions) . " positions for QID: " . $questionId);
            return ['positions' => $positions, 'explanations' => $explanations];
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getPositionsForQuestion failed for QID: " . $questionId . " - " . $e->getMessage() . ", using fallback.");
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
            
            error_log("StemwijzerController: getStemwijzerData - Initial questions: " . count($questions) . ", Initial parties: " . count($parties));
            
            $validQuestions = [];
            foreach ($questions as $question) {
                // Zorg ervoor dat question een object is met een 'id' property
                if (!is_object($question) || !property_exists($question, 'id')) {
                    error_log("StemwijzerController: Invalid question structure encountered in getStemwijzerData: " . print_r($question, true));
                    continue; // Sla deze vraag over
                }

                $positionData = $this->getPositionsForQuestion($question->id);
                
                // Alleen vragen toevoegen als er posities zijn (van DB of fallback)
                if (!empty($positionData['positions'])) {
                    $question->positions = $positionData['positions'];
                    $question->explanations = $positionData['explanations'];
                    $validQuestions[] = $question;
                    // error_log("StemwijzerController: Question {$question->id} has " . count($positionData['positions']) . " positions.");
                } else {
                    error_log("StemwijzerController: Question {$question->id} skipped, no positions found (DB or fallback).");
                }
            }
            $questions = $validQuestions; // Gebruik alleen vragen met posities

            $partyLogos = [];
            foreach ($parties as $party) {
                 // Zorg ervoor dat party een object is
                if (!is_object($party)) {
                    error_log("StemwijzerController: Invalid party structure encountered for logos: " . print_r($party, true));
                    continue; 
                }
                $shortName = property_exists($party, 'short_name') && !empty($party->short_name) ? $party->short_name : (property_exists($party, 'name') ? $party->name : null);
                $logoUrl = property_exists($party, 'logo_url') ? $party->logo_url : null;

                if (empty($shortName)) continue; // Sla op als geen naam

                if (empty($logoUrl) || $logoUrl === 'NULL') {
                    $logoUrl = $this->getFallbackLogoForParty($shortName);
                }
                $partyLogos[$shortName] = $logoUrl;
            }
            
            if (empty($partyLogos) && !empty($this->getFallbackParties())) {
                 error_log("StemwijzerController: No party logos mapped from DB parties, attempting full fallback logo mapping.");
                 $fallbackParties = $this->getFallbackParties();
                 foreach ($fallbackParties as $fbParty) {
                     if (is_object($fbParty) && property_exists($fbParty, 'short_name') && property_exists($fbParty, 'logo_url')) {
                        $partyLogos[$fbParty->short_name] = $fbParty->logo_url;
                     }
                 }
            }
            error_log("StemwijzerController: Final data - Questions: " . count($questions) . ", Parties: " . count($parties) . ", Logos: " . count($partyLogos));
            
            return [
                'questions' => $questions,
                'parties' => $parties, // Stuur alle partijen mee, de frontend kan filteren indien nodig
                'partyLogos' => $partyLogos,
                'schema_type' => $this->schemaType
            ];
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getStemwijzerData failed BIG CATCH - " . $e->getMessage() . ". Trace: " . $e->getTraceAsString());
            return $this->getFallbackStemwijzerData();
        }
    }

    /**
     * Sla de resultaten van een gebruiker op
     */
    public function saveResults($sessionId, $answers, $results, $userId = null) {
        if (!$this->hasValidConnection) {
            error_log("StemwijzerController: saveResults - Geen database connectie beschikbaar");
            return false; // Return false instead of true when no connection
        }
        
        if ($this->schemaType === 'fallback') {
            error_log("StemwijzerController: saveResults - Fallback schema, geen opslag mogelijk");
            return false;
        }
        
        try {
            // Voor oude schema's proberen we geen resultaten op te slaan
            if ($this->schemaType === 'old') {
                error_log("StemwijzerController: saveResults - Oude schema, geen stemwijzer_results tabel verwacht");
                return false;
            }
            
            // Controleer of stemwijzer_results tabel bestaat
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_results'");
            $tableExists = $this->db->single();
            
            if (!$tableExists) {
                error_log("StemwijzerController: saveResults - stemwijzer_results tabel bestaat niet, probeer aan te maken");
                
                // Probeer de tabel automatisch aan te maken
                if (!$this->createResultsTable()) {
                    error_log("StemwijzerController: saveResults - Kon stemwijzer_results tabel niet aanmaken");
                    return false;
                }
                
                error_log("StemwijzerController: saveResults - stemwijzer_results tabel succesvol aangemaakt");
            } else {
                // Tabel bestaat al, controleer of share_id kolom ook bestaat
                $this->addShareIdColumnIfMissing();
            }

            // Valideer input data
            if (empty($sessionId)) {
                error_log("StemwijzerController: saveResults - Session ID is leeg");
                return false;
            }
            
            if (empty($answers)) {
                error_log("StemwijzerController: saveResults - Geen antwoorden om op te slaan");
                return false;
            }
            
            if (empty($results)) {
                error_log("StemwijzerController: saveResults - Geen resultaten om op te slaan");
                return false;
            }

            // Genereer een unieke share_id voor de link functionaliteit
            $shareId = $this->generateShareId();

            // Sla de resultaten op
            $this->db->query("
                INSERT INTO stemwijzer_results 
                (session_id, share_id, user_id, answers, results, ip_address, user_agent, completed_at) 
                VALUES 
                (:session_id, :share_id, :user_id, :answers, :results, :ip_address, :user_agent, NOW())
            ");
            
            $this->db->bind(':session_id', $sessionId);
            $this->db->bind(':share_id', $shareId);
            $this->db->bind(':user_id', $userId); // Kan NULL zijn
            $this->db->bind(':answers', json_encode($answers));
            $this->db->bind(':results', json_encode($results));
            $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
            $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
            
            $success = $this->db->execute();
            
            if ($success) {
                $insertedId = $this->db->lastInsertId();
                error_log("StemwijzerController: saveResults - Resultaten succesvol opgeslagen met ID: " . $insertedId . ", Share ID: " . $shareId);
                error_log("StemwijzerController: saveResults - Session: $sessionId, Antwoorden: " . count($answers) . ", User: " . ($userId ?? 'anonymous'));
                return $shareId; // Return share_id instead of boolean for link generation
            } else {
                error_log("StemwijzerController: saveResults - Database execute() faalde");
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: saveResults - FOUT: " . $e->getMessage());
            error_log("StemwijzerController: saveResults - Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Genereer een unieke share ID voor een resultaat
     */
    private function generateShareId() {
        do {
            // Genereer een unieke, URL-veilige string
            $shareId = bin2hex(random_bytes(16)); // 32 character string
            
            // Controleer of deze share_id al bestaat
            $this->db->query("SELECT id FROM stemwijzer_results WHERE share_id = :share_id");
            $this->db->bind(':share_id', $shareId);
            $exists = $this->db->single();
        } while ($exists);
        
        return $shareId;
    }

    /**
     * Haal resultaten op via share_id
     */
    public function getResultsByShareId($shareId) {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            return null;
        }
        
        try {
            // Controleer of stemwijzer_results tabel bestaat
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_results'");
            $tableExists = $this->db->single();
            
            if (!$tableExists) {
                return null;
            }

            $this->db->query("
                SELECT id, session_id, share_id, answers, results, completed_at, ip_address 
                FROM stemwijzer_results 
                WHERE share_id = :share_id
            ");
            $this->db->bind(':share_id', $shareId);
            $result = $this->db->single();
            
            if (!$result) {
                return null;
            }
            
            // Decode JSON data
            $result->answers = json_decode($result->answers, true);
            $result->results = json_decode($result->results, true);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getResultsByShareId - FOUT: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Analyseer de politieke persoonlijkheid op basis van antwoorden
     */
    public function analyzePoliticalPersonality($answers, $questions) {
        $analysis = [
            'left_right_score' => 0,
            'progressive_conservative_score' => 0,
            'authoritarian_libertarian_score' => 0,
            'eu_skeptic_pro_score' => 0,
            'economic_left_right' => 0,
            'social_liberal_conservative' => 0,
            'total_answered' => count($answers)
        ];

        // Categorieën van vragen voor verschillende assen
        $economicQuestions = ['belasting', 'uitkering', 'economie', 'subsidie', 'markt', 'inkomen', 'pensioen'];
        $socialQuestions = ['asiel', 'immigratie', 'integratie', 'criminaliteit', 'veiligheid', 'identiteit'];
        $euQuestions = ['europa', 'eu', 'europese', 'brexit', 'soevereiniteit'];
        $progressiveQuestions = ['klimaat', 'milieu', 'duurzaam', 'innovatie', 'technologie'];
        $authoritarianQuestions = ['veiligheid', 'privacy', 'surveillance', 'politie', 'straf'];

        foreach ($answers as $questionIndex => $answer) {
            if (!isset($questions[$questionIndex])) continue;
            
            $question = $questions[$questionIndex];
            
            // Controleer of $question een object of array is en behandel dienovereenkomstig
            if (is_object($question)) {
                $questionText = strtolower($question->title . ' ' . $question->description);
            } else {
                $questionText = strtolower($question['title'] . ' ' . $question['description']);
            }
            
            // Bepaal de waarde van het antwoord (-1 = oneens, 0 = neutraal, 1 = eens)
            $answerValue = 0;
            if ($answer === 'eens') $answerValue = 1;
            elseif ($answer === 'oneens') $answerValue = -1;

            // Economische as (links-rechts)
            if ($this->containsKeywords($questionText, $economicQuestions)) {
                $analysis['economic_left_right'] += $answerValue;
            }

            // Sociale as (progressief-conservatief)
            if ($this->containsKeywords($questionText, $socialQuestions)) {
                $analysis['social_liberal_conservative'] += $answerValue;
            }

            // EU as (skeptisch-pro)
            if ($this->containsKeywords($questionText, $euQuestions)) {
                $analysis['eu_skeptic_pro_score'] += $answerValue;
            }

            // Progressief-conservatief
            if ($this->containsKeywords($questionText, $progressiveQuestions)) {
                $analysis['progressive_conservative_score'] += $answerValue;
            }

            // Autoritair-libertair
            if ($this->containsKeywords($questionText, $authoritarianQuestions)) {
                $analysis['authoritarian_libertarian_score'] += $answerValue;
            }

            // Algemene links-rechts score
            $analysis['left_right_score'] += $answerValue;
        }

        // Normaliseer scores naar percentages
        $totalQuestions = count($answers);
        if ($totalQuestions > 0) {
            $analysis['left_right_percentage'] = (($analysis['left_right_score'] / $totalQuestions) + 1) * 50;
            $analysis['progressive_percentage'] = (($analysis['progressive_conservative_score'] / $totalQuestions) + 1) * 50;
            $analysis['authoritarian_percentage'] = (($analysis['authoritarian_libertarian_score'] / $totalQuestions) + 1) * 50;
            $analysis['eu_pro_percentage'] = (($analysis['eu_skeptic_pro_score'] / $totalQuestions) + 1) * 50;
            $analysis['economic_right_percentage'] = (($analysis['economic_left_right'] / $totalQuestions) + 1) * 50;
            $analysis['social_conservative_percentage'] = (($analysis['social_liberal_conservative'] / $totalQuestions) + 1) * 50;
        }

        // Bepaal politiek profiel
        $analysis['political_profile'] = $this->determinePoliticalProfile($analysis);
        $analysis['personality_traits'] = $this->determinePoliticalTraits($analysis);
        $analysis['compass_position'] = $this->determineCompassPosition($analysis);

        return $analysis;
    }

    /**
     * Helper functie om te controleren of tekst bepaalde keywords bevat
     */
    private function containsKeywords($text, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Bepaal het politieke profiel
     */
    private function determinePoliticalProfile($analysis) {
        $leftRight = $analysis['left_right_percentage'] ?? 50;
        $progressive = $analysis['progressive_percentage'] ?? 50;
        
        if ($leftRight < 35 && $progressive > 65) {
            return [
                'type' => 'Progressief Links',
                'description' => 'Je hebt vooruitstrevende ideeën en gelooft in sociale gelijkheid en verandering.',
                'color' => 'from-green-500 to-blue-500'
            ];
        } elseif ($leftRight < 35 && $progressive < 35) {
            return [
                'type' => 'Traditioneel Links',
                'description' => 'Je combineert linkse economische ideeën met meer traditionele sociale waarden.',
                'color' => 'from-red-500 to-orange-500'
            ];
        } elseif ($leftRight > 65 && $progressive > 65) {
            return [
                'type' => 'Progressief Rechts',
                'description' => 'Je bent economisch liberaal maar sociaal vooruitstrevend.',
                'color' => 'from-blue-500 to-purple-500'
            ];
        } elseif ($leftRight > 65 && $progressive < 35) {
            return [
                'type' => 'Conservatief Rechts',
                'description' => 'Je hebt traditionele waarden en gelooft in vrije markteconomie.',
                'color' => 'from-blue-600 to-indigo-600'
            ];
        } else {
            return [
                'type' => 'Politiek Centraal',
                'description' => 'Je hebt een gematigde politieke houding met elementen van verschillende kanten.',
                'color' => 'from-gray-500 to-slate-600'
            ];
        }
    }

    /**
     * Bepaal politieke karaktereigenschappen
     */
    private function determinePoliticalTraits($analysis) {
        $traits = [];
        
        $leftRight = $analysis['left_right_percentage'] ?? 50;
        $progressive = $analysis['progressive_percentage'] ?? 50;
        $authoritarian = $analysis['authoritarian_percentage'] ?? 50;
        $euPro = $analysis['eu_pro_percentage'] ?? 50;

        // Links-rechts traits
        if ($leftRight < 30) {
            $traits[] = ['name' => 'Sterk Sociaal Bewust', 'icon' => '❤️', 'description' => 'Gelijkheid en solidariteit staan centraal'];
        } elseif ($leftRight > 70) {
            $traits[] = ['name' => 'Economisch Liberal', 'icon' => '💼', 'description' => 'Gelooft in vrije markt en ondernemerschap'];
        }

        // Progressief-conservatief traits
        if ($progressive > 70) {
            $traits[] = ['name' => 'Vooruitstrevend', 'icon' => '🚀', 'description' => 'Omarmt verandering en innovatie'];
        } elseif ($progressive < 30) {
            $traits[] = ['name' => 'Traditioneel', 'icon' => '🏛️', 'description' => 'Waardeert bewezen systemen en tradities'];
        }

        // Autoritair-libertair traits
        if ($authoritarian > 70) {
            $traits[] = ['name' => 'Veiligheid Georiënteerd', 'icon' => '🛡️', 'description' => 'Prioriteit aan orde en veiligheid'];
        } elseif ($authoritarian < 30) {
            $traits[] = ['name' => 'Vrijheidsliefhebber', 'icon' => '🕊️', 'description' => 'Individualiteit en persoonlijke vrijheid belangrijk'];
        }

        // EU traits
        if ($euPro > 70) {
            $traits[] = ['name' => 'Europees Minded', 'icon' => '🇪🇺', 'description' => 'Steunt Europese samenwerking'];
        } elseif ($euPro < 30) {
            $traits[] = ['name' => 'Soevereiniteitsvoorkeur', 'icon' => '🏴', 'description' => 'Nationale autonomie is belangrijk'];
        }

        // Voeg algemene traits toe
        if (abs($leftRight - 50) < 15 && abs($progressive - 50) < 15) {
            $traits[] = ['name' => 'Pragmatisch', 'icon' => '⚖️', 'description' => 'Zoekt balans tussen verschillende standpunten'];
        }

        return $traits;
    }

    /**
     * Bepaal positie op het politieke kompas
     */
    private function determineCompassPosition($analysis) {
        $economic = ($analysis['economic_right_percentage'] ?? 50) - 50; // -50 tot +50
        $social = ($analysis['social_conservative_percentage'] ?? 50) - 50; // -50 tot +50
        
        return [
            'x' => $economic, // Links (-) naar Rechts (+)
            'y' => $social,   // Liberaal (-) naar Autoritair (+)
            'quadrant' => $this->getQuadrant($economic, $social)
        ];
    }

    /**
     * Bepaal het kwadrant op het politieke kompas
     */
    private function getQuadrant($x, $y) {
        if ($x > 0 && $y < 0) {
            return ['name' => 'Rechts-Liberaal', 'color' => 'blue'];
        } elseif ($x > 0 && $y > 0) {
            return ['name' => 'Rechts-Autoritair', 'color' => 'indigo'];
        } elseif ($x < 0 && $y > 0) {
            return ['name' => 'Links-Autoritair', 'color' => 'red'];
        } else {
            return ['name' => 'Links-Liberaal', 'color' => 'green'];
        }
    }

    /**
     * Maak de stemwijzer_results tabel aan als deze niet bestaat
     */
    private function createResultsTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS stemwijzer_results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                session_id VARCHAR(255) NOT NULL,
                share_id VARCHAR(32) NOT NULL UNIQUE,
                user_id INT DEFAULT NULL,
                answers JSON NOT NULL,
                results JSON NOT NULL,
                completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                ip_address VARCHAR(45) DEFAULT NULL,
                user_agent TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_session (session_id),
                INDEX idx_share (share_id),
                INDEX idx_user (user_id),
                INDEX idx_completed (completed_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $this->db->query($sql);
            $success = $this->db->execute();
            
            if ($success) {
                error_log("StemwijzerController: createResultsTable - Tabel stemwijzer_results succesvol aangemaakt");
            } else {
                error_log("StemwijzerController: createResultsTable - Fout bij aanmaken van tabel");
            }
            
            // Controleer of share_id kolom al bestaat in bestaande tabel en voeg toe indien nodig
            $this->addShareIdColumnIfMissing();
            
            return $success;
            
        } catch (Exception $e) {
            error_log("StemwijzerController: createResultsTable - FOUT: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Voeg share_id kolom toe aan bestaande tabel indien deze ontbreekt
     */
    private function addShareIdColumnIfMissing() {
        try {
            // Controleer of share_id kolom al bestaat
            $this->db->query("SHOW COLUMNS FROM stemwijzer_results LIKE 'share_id'");
            $columnExists = $this->db->single();
            
            if (!$columnExists) {
                error_log("StemwijzerController: share_id kolom bestaat niet, voeg toe...");
                
                // Voeg share_id kolom toe
                $this->db->query("ALTER TABLE stemwijzer_results ADD COLUMN share_id VARCHAR(32) DEFAULT NULL AFTER session_id");
                $this->db->execute();
                
                // Maak index aan
                $this->db->query("ALTER TABLE stemwijzer_results ADD UNIQUE INDEX idx_share (share_id)");
                $this->db->execute();
                
                // Update bestaande records met een share_id
                $this->db->query("SELECT id FROM stemwijzer_results WHERE share_id IS NULL");
                $recordsToUpdate = $this->db->resultSet();
                
                foreach ($recordsToUpdate as $record) {
                    $shareId = bin2hex(random_bytes(16));
                    $this->db->query("UPDATE stemwijzer_results SET share_id = :share_id WHERE id = :id");
                    $this->db->bind(':share_id', $shareId);
                    $this->db->bind(':id', $record->id);
                    $this->db->execute();
                }
                
                error_log("StemwijzerController: share_id kolom succesvol toegevoegd en bestaande records bijgewerkt");
            }
            
        } catch (Exception $e) {
            error_log("StemwijzerController: addShareIdColumnIfMissing - FOUT: " . $e->getMessage());
        }
    }

    /**
     * Haal statistieken op over de stemwijzer
     */
    public function getStatistics() {
        if (!$this->hasValidConnection || $this->schemaType === 'fallback') {
            return [
                'total_submissions' => 0, 
                'last_updated' => date('Y-m-d H:i:s'), 
                'schema_type' => $this->schemaType,
                'database_status' => 'no_connection'
            ];
        }
        
        try {
            $this->db->query("SHOW TABLES LIKE 'stemwijzer_results'");
            $tableExists = $this->db->single();
            
            if (!$tableExists) {
                return [
                    'total_submissions' => 0, 
                    'last_updated' => date('Y-m-d H:i:s'), 
                    'schema_type' => $this->schemaType,
                    'database_status' => 'no_results_table'
                ];
            }
            
            // Basis statistieken
            $this->db->query("SELECT COUNT(*) as total FROM stemwijzer_results");
            $total = $this->db->single()->total ?? 0;
            
            // Laatste submission
            $this->db->query("SELECT MAX(completed_at) as last_submission FROM stemwijzer_results");
            $lastSubmission = $this->db->single()->last_submission ?? null;
            
            // Submissions vandaag
            $this->db->query("SELECT COUNT(*) as today_count FROM stemwijzer_results WHERE DATE(completed_at) = CURDATE()");
            $todayCount = $this->db->single()->today_count ?? 0;
            
            // Submissions deze week
            $this->db->query("SELECT COUNT(*) as week_count FROM stemwijzer_results WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $weekCount = $this->db->single()->week_count ?? 0;
            
            return [
                'total_submissions' => (int)$total,
                'submissions_today' => (int)$todayCount,
                'submissions_this_week' => (int)$weekCount,
                'last_submission' => $lastSubmission,
                'last_updated' => date('Y-m-d H:i:s'),
                'schema_type' => $this->schemaType,
                'database_status' => 'connected',
                'table_status' => 'exists'
            ];
            
        } catch (Exception $e) {
            error_log("StemwijzerController: getStatistics failed - " . $e->getMessage());
            return [
                'total_submissions' => 0, 
                'last_updated' => date('Y-m-d H:i:s'), 
                'schema_type' => $this->schemaType,
                'database_status' => 'error',
                'error_message' => $e->getMessage()
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
     * Krijg fallback logo URL voor een partij
     */
    private function getFallbackLogoForParty($partyName) {
        $fallbackLogos = [
            'PVV' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/PVV_logo.svg/200px-PVV_logo.svg.png',
            'VVD' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/VVD_logo.svg/200px-VVD_logo.svg.png',
            'NSC' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/NSC_logo.svg/200px-NSC_logo.svg.png',
            'BBB' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/BoerBurgerBeweging_logo.svg/200px-BoerBurgerBeweging_logo.svg.png',
            'GL-PvdA' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/GroenLinks-PvdA_logo.svg/200px-GroenLinks-PvdA_logo.svg.png',
            'D66' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/D66_logo.svg/200px-D66_logo.svg.png',
            'SP' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/SP_logo_%28Netherlands%29.svg/200px-SP_logo_%28Netherlands%29.svg.png',
            'PvdD' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Partij_voor_de_Dieren_logo.svg/200px-Partij_voor_de_Dieren_logo.svg.png',
            'CDA' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/CDA_logo.svg/200px-CDA_logo.svg.png',
            'JA21' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/JA21_logo.svg/200px-JA21_logo.svg.png',
            'SGP' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/SGP_logo.svg/200px-SGP_logo.svg.png',
            'FvD' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Forum_voor_Democratie_logo.svg/200px-Forum_voor_Democratie_logo.svg.png',
            'DENK' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/DENK_logo.svg/200px-DENK_logo.svg.png',
            'Volt' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Volt_Europa_logo.svg/200px-Volt_Europa_logo.svg.png'
        ];
        $defaultPlaceholder = 'https://via.placeholder.com/100x60/f0f0f0/666666?text=' . urlencode($partyName ?: "Onbekend");
        return $fallbackLogos[$partyName] ?? $defaultPlaceholder;
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
            (object)['id' => 8, 'name' => 'PvdD', 'short_name' => 'PvdD', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Partij_voor_de_Dieren_logo.svg/200px-Partij_voor_de_Dieren_logo.svg.png'],
            (object)['id' => 9, 'name' => 'CDA', 'short_name' => 'CDA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/CDA_logo.svg/200px-CDA_logo.svg.png'],
            (object)['id' => 10, 'name' => 'JA21', 'short_name' => 'JA21', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/JA21_logo.svg/200px-JA21_logo.svg.png'],
            (object)['id' => 11, 'name' => 'SGP', 'short_name' => 'SGP', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/SGP_logo.svg/200px-SGP_logo.svg.png'],
            (object)['id' => 12, 'name' => 'FvD', 'short_name' => 'FvD', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Forum_voor_Democratie_logo.svg/200px-Forum_voor_Democratie_logo.svg.png'],
            (object)['id' => 13, 'name' => 'DENK', 'short_name' => 'DENK', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/DENK_logo.svg/200px-DENK_logo.svg.png'],
            (object)['id' => 14, 'name' => 'Volt', 'short_name' => 'Volt', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Volt_Europa_logo.svg/200px-Volt_Europa_logo.svg.png']
        ];
    }
    
    private function getFallbackPositionsForQuestion($questionId) {
        $fallbackPositions = [
            1 => [ // Asielbeleid
                'positions' => [
                    'PVV' => 'eens', 'VVD' => 'eens', 'NSC' => 'eens', 'BBB' => 'eens',
                    'GL-PvdA' => 'oneens', 'D66' => 'oneens', 'SP' => 'neutraal', 'PvdD' => 'oneens',
                    'CDA' => 'neutraal', 'JA21' => 'eens', 'SGP' => 'eens', 'FvD' => 'eens',
                    'DENK' => 'oneens', 'Volt' => 'oneens'
                ],
                'explanations' => [
                    'PVV' => 'PVV wil een volledige asielstop en veel strengere controles aan de grens.',
                    'VVD' => 'VVD pleit voor een selectiever asielbeleid met strengere eisen.',
                    'NSC' => 'NSC wil een meer doordacht en gecontroleerd asielbeleid.',
                    'BBB' => 'BBB steunt een strenger asielbeleid om de druk op gemeenten te verlagen.',
                    'GL-PvdA' => 'GL-PvdA vindt dat Nederland vluchtelingen moet blijven opvangen vanuit humanitaire overwegingen.',
                    'D66' => 'D66 wil een humaan asielbeleid binnen Europese afspraken.',
                    'SP' => 'SP vindt dat er ruimte moet zijn voor vluchtelingen, maar met goede opvang en integratie.',
                    'PvdD' => 'PvdD pleit voor een humaan asielbeleid dat dierenrechten respecteert.',
                    'CDA' => 'CDA wil een onderscheidend asielbeleid met aandacht voor zowel opvang als integratie.',
                    'JA21' => 'JA21 wil een restrictief asielbeleid met strenge grenzen.',
                    'SGP' => 'SGP steunt een zeer restrictief asielbeleid vanuit christelijke waarden.',
                    'FvD' => 'FvD wil een volledig stop op immigratie en terugkeer naar nationale soevereiniteit.',
                    'DENK' => 'DENK pleit voor een meer inclusief en humaan asielbeleid.',
                    'Volt' => 'Volt staat voor een gemeenschappelijk Europees asielbeleid.'
                ]
            ],
            2 => [ // Vermogensbelasting
                'positions' => [
                    'PVV' => 'neutraal', 'VVD' => 'oneens', 'NSC' => 'oneens', 'BBB' => 'oneens',
                    'GL-PvdA' => 'eens', 'D66' => 'eens', 'SP' => 'eens', 'PvdD' => 'eens',
                    'CDA' => 'neutraal', 'JA21' => 'oneens', 'SGP' => 'neutraal', 'FvD' => 'oneens',
                    'DENK' => 'eens', 'Volt' => 'eens'
                ],
                'explanations' => [
                    'PVV' => 'PVV richt zich meer op andere belastingonderwerpen.',
                    'VVD' => 'VVD is tegen hogere vermogensbelasting omdat dit ondernemerschap zou schaden.',
                    'NSC' => 'NSC vindt dat vermogensbelasting niet verhoogd moet worden.',
                    'BBB' => 'BBB is tegen hogere belastingen die ondernemers raken.',
                    'GL-PvdA' => 'GL-PvdA wil dat vermogende mensen meer bijdragen aan publieke voorzieningen.',
                    'D66' => 'D66 steunt een eerlijkere verdeling van de belastinglast.',
                    'SP' => 'SP wil dat rijken meer belasting betalen voor een rechtvaardige samenleving.',
                    'PvdD' => 'PvdD steunt hogere belastingen op vermogen voor natuur en dierenwelzijn.',
                    'CDA' => 'CDA wil een evenwichtig belastingstelsel zonder al te hoge tarieven.',
                    'JA21' => 'JA21 is tegen hogere belastingen op vermogen en ondernemerschap.',
                    'SGP' => 'SGP wil een eerlijk belastingstelsel zonder overmatige belasting.',
                    'FvD' => 'FvD is tegen hogere belastingen en wil meer economische vrijheid.',
                    'DENK' => 'DENK steunt hogere belastingen op vermogen voor sociale rechtvaardigheid.',
                    'Volt' => 'Volt wil een progressief belastingstelsel met hogere vermogensbelasting.'
                ]
            ],
            3 => [ // Klimaat
                'positions' => [
                    'PVV' => 'oneens', 'VVD' => 'neutraal', 'NSC' => 'neutraal', 'BBB' => 'oneens',
                    'GL-PvdA' => 'eens', 'D66' => 'eens', 'SP' => 'eens', 'PvdD' => 'eens',
                    'CDA' => 'eens', 'JA21' => 'oneens', 'SGP' => 'neutraal', 'FvD' => 'oneens',
                    'DENK' => 'eens', 'Volt' => 'eens'
                ],
                'explanations' => [
                    'PVV' => 'PVV vindt dat klimaatmaatregelen te ver gaan en de economie schaden.',
                    'VVD' => 'VVD wil klimaatmaatregelen die economisch haalbaar zijn.',
                    'NSC' => 'NSC steunt klimaatbeleid maar wil realistische doelen.',
                    'BBB' => 'BBB vindt dat boeren en bedrijven niet onevenredig belast moeten worden.',
                    'GL-PvdA' => 'GL-PvdA wil snelle en ingrijpende klimaatmaatregelen.',
                    'D66' => 'D66 pleit voor ambitieus klimaatbeleid met innovatie.',
                    'SP' => 'SP wil klimaatmaatregelen die eerlijk verdeeld zijn.',
                    'PvdD' => 'PvdD staat voor radicale klimaatmaatregelen ter bescherming van natuur en dieren.',
                    'CDA' => 'CDA steunt klimaatbeleid binnen christelijke rentmeesterschap.',
                    'JA21' => 'JA21 is sceptisch over ingrijpende klimaatmaatregelen.',
                    'SGP' => 'SGP steunt klimaatmaatregelen binnen christelijke verantwoordelijkheid.',
                    'FvD' => 'FvD is kritisch op klimaatbeleid en wil meer economische vrijheid.',
                    'DENK' => 'DENK steunt klimaatmaatregelen die sociale rechtvaardigheid bevorderen.',
                    'Volt' => 'Volt wil ambitieus Europees klimaatbeleid met groene innovatie.'
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
        $partyLogos = [];

        foreach ($parties as $party) {
            if (is_object($party) && property_exists($party, 'short_name') && property_exists($party, 'logo_url')) {
                $partyLogos[$party->short_name] = $party->logo_url;
            }
        }
        
        $validQuestions = [];
        foreach ($questions as $question) {
            if (is_object($question) && property_exists($question, 'id')) {
                $positionData = $this->getFallbackPositionsForQuestion($question->id);
                if (!empty($positionData['positions'])) {
                    $question->positions = $positionData['positions'];
                    $question->explanations = $positionData['explanations'];
                    $validQuestions[] = $question;
                }
            }
        }
        
        return [
            'questions' => $validQuestions,
            'parties' => $parties,
            'partyLogos' => $partyLogos,
            'schema_type' => 'fallback'
        ];
    }
} 