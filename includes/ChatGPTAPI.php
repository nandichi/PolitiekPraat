<?php

class ChatGPTAPI {
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';
    private $model = 'gpt-4o-mini';
    
    public function __construct() {
        // Probeer API key uit verschillende bronnen te halen
        $this->apiKey = $this->getApiKey();
        
        if (!$this->apiKey) {
            throw new Exception('ChatGPT API key niet gevonden. Zet de key in een .env file of als environment variabele OPENAI_API_KEY.');
        }
    }
    
    /**
     * Haal API key op uit veilige bronnen
     */
    private function getApiKey() {
        // 1. Probeer uit environment variabele
        $envKey = getenv('OPENAI_API_KEY');
        if ($envKey) {
            return $envKey;
        }
        
        // 2. Probeer uit $_ENV superglobal
        if (isset($_ENV['OPENAI_API_KEY'])) {
            return $_ENV['OPENAI_API_KEY'];
        }
        
        // 3. Probeer uit .env file (als die bestaat)
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            if (preg_match('/OPENAI_API_KEY=(.+)/', $envContent, $matches)) {
                return trim($matches[1]);
            }
        }
        
        // 4. Probeer uit aparte config file (niet in git)
        $configFile = __DIR__ . '/../config/api_keys.php';
        if (file_exists($configFile)) {
            $config = include $configFile;
            if (isset($config['openai_api_key'])) {
                return $config['openai_api_key'];
            }
        }
        
        return null;
    }
    
    /**
     * Vraag ChatGPT om uitgebreide uitleg bij een stemwijzer vraag
     */
    public function explainQuestion($question, $context, $leftView, $rightView) {
        $prompt = "Je bent een Nederlandse politieke expert die neutrale uitleg geeft over politieke stellingen. 

Geef een uitgebreide, neutrale uitleg (ongeveer 150-200 woorden) over de volgende politieke stelling:

**Stelling:** {$question}

**Context:** {$context}

**Linkse visie:** {$leftView}

**Rechtse visie:** {$rightView}

Leg uit:
1. Waarom dit onderwerp belangrijk is in de Nederlandse politiek
2. Wat de hoofdargumenten zijn van beide kanten
3. Welke gevolgen verschillende standpunten kunnen hebben
4. Geef concrete voorbeelden waar relevant

Schrijf in toegankelijke taal voor gewone burgers. Blijf volledig neutraal en geef geen eigen mening.";

        return $this->makeAPICall($prompt);
    }
    
    /**
     * Vraag ChatGPT waarom een bepaalde partij bij iemand past
     */
    public function explainPartyMatch($partyName, $userAnswers, $questions, $matchPercentage) {
        // Maak een samenvatting van de user's antwoorden
        $answerSummary = $this->summarizeUserAnswers($userAnswers, $questions);
        
        $prompt = "Je bent een Nederlandse politieke expert die persoonlijk advies geeft over partij-matches.

Een gebruiker heeft {$matchPercentage}% overeenkomst met {$partyName}. 

**Gebruiker's politieke profiel:**
{$answerSummary}

Leg in ongeveer 150-200 woorden uit:
1. Waarom {$partyName} zo goed bij deze gebruiker past
2. Op welke specifieke onderwerpen ze het eens zijn
3. Wat de kernwaarden zijn die ze delen
4. Waar ze mogelijk zouden kunnen verschillen (als relevant)
5. Geef concrete voorbeelden van {$partyName}'s standpunten die aansluiten

Schrijf in een persoonlijke, toegankelijke toespraak alsof je direct tegen de gebruiker praat. Begin met iets zoals 'Op basis van jouw antwoorden...' Blijf feitelijk en objectief.";

        return $this->makeAPICall($prompt);
    }
    
    /**
     * Maak een samenvatting van gebruiker's antwoorden
     */
    private function summarizeUserAnswers($userAnswers, $questions) {
        $summary = "De gebruiker heeft de volgende standpunten ingenomen:\n\n";
        
        foreach ($userAnswers as $questionIndex => $answer) {
            if (isset($questions[$questionIndex])) {
                $question = $questions[$questionIndex];
                
                // Controleer of $question een object of array is
                if (is_object($question)) {
                    $title = $question->title ?? 'Onbekende vraag';
                } else {
                    $title = $question['title'] ?? 'Onbekende vraag';
                }
                
                $summary .= "- {$title}: {$answer}\n";
            }
        }
        
        return $summary;
    }
    
    /**
     * Geef algemeen politiek advies gebaseerd op alle antwoorden
     */
    public function generatePoliticalAdvice($personalityAnalysis, $topMatches) {
        $topThree = array_slice($topMatches, 0, 3);
        $topMatchesText = implode(', ', array_map(function($match) {
            return $match['name'] . ' (' . $match['agreement'] . '%)';
        }, $topThree));
        
        $prompt = "Je bent een Nederlandse politieke expert die persoonlijk stemadvies geeft.

Een gebruiker heeft de stemwijzer ingevuld met de volgende resultaten:

**Top 3 partij matches:** {$topMatchesText}

**Politiek profiel:** {$personalityAnalysis['political_profile']['type']} - {$personalityAnalysis['political_profile']['description']}

**Politieke kenmerken:**
- {$personalityAnalysis['left_right_percentage']}% rechts georiënteerd  
- {$personalityAnalysis['progressive_percentage']}% progressief
- {$personalityAnalysis['authoritarian_percentage']}% autoritair
- {$personalityAnalysis['eu_pro_percentage']}% pro-EU

Geef in ongeveer 200-250 woorden persoonlijk stemadvies:
1. Wat deze resultaten zeggen over hun politieke identiteit
2. Waarom de top matches goed bij hen passen
3. Op welke thema's ze extra moeten letten bij hun stemkeuze
4. Suggesties voor vervolgstappen (partijprogramma's lezen, debatten kijken, etc.)

Schrijf persoonlijk en bemoedigend. Begin met 'Op basis van jouw stemwijzer resultaten...' Eindigt met praktisch advies.";

        return $this->makeAPICall($prompt);
    }
    
    /**
     * Maak daadwerkelijke API call naar OpenAI
     */
    private function makeAPICall($prompt) {
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => 'Je bent een Nederlandse politieke expert die neutrale, informatieve uitleg geeft over politiek. Je schrijft in duidelijk Nederlands voor gewone burgers.'
                ],
                [
                    'role' => 'user', 
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 400,
            'temperature' => 0.7
        ];
        
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL error: ' . $error
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP error: ' . $httpCode,
                'response' => $response
            ];
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON decode error: ' . json_last_error_msg()
            ];
        }
        
        if (!isset($decodedResponse['choices'][0]['message']['content'])) {
            return [
                'success' => false,
                'error' => 'Unexpected API response format',
                'response' => $decodedResponse
            ];
        }
        
        return [
            'success' => true,
            'content' => trim($decodedResponse['choices'][0]['message']['content'])
        ];
    }
    
    /**
     * Analyseer politieke bias van een blog artikel
     */
    public function analyzePoliticalBias($title, $content) {
        // Check of content te lang is (meer dan 1500 karakters)
        if (mb_strlen($content) > 1500) {
            // Vat eerst samen
            $summaryResult = $this->summarizeContent($title, $content);
            if (!$summaryResult['success']) {
                return $summaryResult;
            }
            $content = $summaryResult['content'];
        }
        
        $prompt = "Je bent een Nederlandse politieke expert gespecialiseerd in bias detectie. 

Analyseer de volgende blog artikel op politieke orientatie:

**Titel:** {$title}

**Content:** {$content}

Geef een gedetailleerde analyse in exact dit JSON formaat (geen extra tekst):

{
    \"orientation\": \"links|rechts|centrum\",
    \"confidence\": 85,
    \"reasoning\": \"Korte uitleg waarom deze classificatie is gekozen\",
    \"indicators\": {
        \"economic\": \"links|rechts|centrum\",
        \"social\": \"links|rechts|centrum\",
        \"immigration\": \"links|rechts|centrum|neutraal\"
    },
    \"summary\": \"Een beknopte samenvatting van de politieke standpunten in dit artikel\"
}

Criteria:
- **Links**: Pro-sociale zekerheid, hogere belastingen voor rijken, milieubescherming, diversiteit, EU-integratie
- **Rechts**: Vrije markt, lagere belastingen, traditionelle waarden, strenge immigratie, nationale soevereiniteit  
- **Centrum**: Gematigde standpunten, compromissen, pragmatische oplossingen

Confidence score: 0-100 (hoe zeker ben je van de classificatie)
Wees objectief en gebaseerd op de daadwerkelijke inhoud.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Vat lange content samen voor bias analyse
     */
    private function summarizeContent($title, $content) {
        $prompt = "Je bent een Nederlandse politieke expert die artikelen samenvat voor bias analyse.

Vat de volgende blog artikel samen in ongeveer 300-400 woorden. Behoud alle politieke standpunten, argumenten en nuances die belangrijk zijn voor bias detectie:

**Titel:** {$title}

**Content:** {$content}

Schrijf een samenvatting die:
1. Alle politieke standpunten behoudt
2. De hoofdargumenten intact laat
3. Emotionele toon en woordkeuze behoudt waar relevant voor bias
4. Concrete beleidsvoorstellen of kritiek behoudt
5. Verwijzingen naar partijen of politici behoudt

Begin direct met de samenvatting - geen inleiding. Focus op politiek relevante inhoud.";

        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => 'Je bent een Nederlandse politieke expert die artikelen samenvat voor analyse. Je behoudt alle politiek relevante informatie.'
                ],
                [
                    'role' => 'user', 
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 600,
            'temperature' => 0.3
        ];
        
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL error bij samenvatting: ' . $error
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP error bij samenvatting: ' . $httpCode,
                'response' => $response
            ];
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON decode error bij samenvatting: ' . json_last_error_msg()
            ];
        }
        
        if (!isset($decodedResponse['choices'][0]['message']['content'])) {
            return [
                'success' => false,
                'error' => 'Unexpected API response format bij samenvatting',
                'response' => $decodedResponse
            ];
        }
        
        return [
            'success' => true,
            'content' => trim($decodedResponse['choices'][0]['message']['content'])
        ];
    }

    /**
     * Analyseer Nederlandse politieke peilingen en voorspel mogelijke kabinetten
     */
    public function analyzePollingData($partiesData) {
        // Sorteer partijen op peiling zetels
        uasort($partiesData, function($a, $b) {
            return $b['polling']['seats'] - $a['polling']['seats'];
        });

        // Maak een samenvatting van de peilingen
        $pollingSummary = "Huidige peilingen Nederlandse Tweede Kamer:\n\n";
        foreach($partiesData as $key => $party) {
            $change = $party['polling']['seats'] - $party['current_seats'];
            $changeText = $change > 0 ? " (+" . $change . ")" : ($change < 0 ? " (" . $change . ")" : " (=)");
            $pollingSummary .= "- {$key}: {$party['polling']['seats']} zetels{$changeText} (was {$party['current_seats']})\n";
        }

        $prompt = "Je bent een Nederlandse politieke expert en analist die diepgaande inzichten geeft over peilingen en kabinetsformatie.

**ACTUELE PEILINGEN:**
{$pollingSummary}

Geef een uitgebreide analyse (400-500 woorden) over:

## **Belangrijkste Trends**
- Welke partijen winnen/verliezen het meest?
- Wat betekenen deze verschuivingen voor het politieke landschap?

## **Mogelijke Kabinetten**
- Welke coalities zijn realistisch mogelijk? (minimaal 76 zetels)
- Wat zijn de kansen op verschillende scenario's?
- Welke combinaties zijn politiek haalbaar?

## **Politieke Gevolgen**
- Hoe beïnvloeden deze peilingen de machtsbalans?
- Welke thema's worden belangrijker/minder belangrijk?
- Wat betekent dit voor de komende periode?

## **Vooruitblik**
- Wat kunnen we verwachten de komende maanden?
- Welke ontwikkelingen zijn cruciaal om in de gaten te houden?

Schrijf in toegankelijke maar informatieve taal. Gebruik concrete voorbeelden en blijf objectief. Focus op politieke realiteiten en praktische gevolgen voor Nederland.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Genereer perspectief van een politieke partij op een blog artikel
     */
    public function generatePartyPerspective($partyName, $partyInfo, $blogTitle, $blogContent) {
        $prompt = "Je bent een gepassioneerde woordvoerder van {$partyName} die net dit blog artikel heeft gelezen. Je reageert spontaan en emotioneel, precies zoals een echte politicus zou doen.

**Jouw partij achtergrond:**
{$partyInfo['description']}

**Waar jullie voor staan:**
- Immigratie: {$partyInfo['standpoints']['Immigratie']}
- Klimaat: {$partyInfo['standpoints']['Klimaat']}
- Zorg: {$partyInfo['standpoints']['Zorg']}
- Energie: {$partyInfo['standpoints']['Energie']}

**Het artikel waar je op reageert:**
Titel: {$blogTitle}
Inhoud: {$blogContent}

Reageer als een echte Nederlandse politicus! Gebruik:
- Emotionele reacties (frustratie, enthousiasme, verontwaardiging)
- Typische Nederlandse politieke uitdrukkingen (\"Dat is toch onvoorstelbaar!\", \"Wij zeggen al jaren dat...\", \"De kiezer verdient beter\")
- Directe taal en sterke meningen
- Verwijzingen naar 'gewone Nederlanders', 'hardwerkende families'
- Kritiek op andere partijen waar relevant
- Persoonlijke anekdotes of voorbeelden uit de praktijk

Schrijf 200-300 woorden alsof je net uit een debat komt en emotioneel reageert op dit artikel. Begin direct met je reactie - geen inleiding. Wees echt menselijk, gebruik pauzes (...), uitroepen, en spreek de lezer direct aan.

Denk aan hoe {$partyName} werkelijk zou reageren - boos, teleurgesteld, hoopvol, vastberaden? Laat die emotie doorkomen!";

        return $this->makeAPICall($prompt);
    }

    /**
     * Genereer perspectief van een politieke leider op een blog artikel
     */
    public function generateLeaderPerspective($leaderName, $partyName, $partyInfo, $blogTitle, $blogContent) {
        $prompt = "Je bent {$leaderName}, partijleider van {$partyName}. Je leest dit blog artikel en reageert zoals je altijd doet - met passie, overtuiging en jouw eigen unieke stijl.

**Wie je bent:**
{$partyInfo['leader_info']}

**Waar jouw partij voor staat:**
- Immigratie: {$partyInfo['standpoints']['Immigratie']}
- Klimaat: {$partyInfo['standpoints']['Klimaat']}
- Zorg: {$partyInfo['standpoints']['Zorg']}
- Energie: {$partyInfo['standpoints']['Energie']}

**Het artikel:**
Titel: {$blogTitle}
Inhoud: {$blogContent}

Reageer als {$leaderName} zelf! Gebruik jouw eigen karakteristieke manier van spreken:
- Jouw persoonlijke stijl (direct, diplomatiek, fel, gemoedelijk?)
- Uitdrukkingen die jij vaak gebruikt
- Jouw manier van argumenteren
- Persoonlijke verhalen of ervaringen die je vaak deelt
- Jouw emoties - waar word je boos om? Wat geeft je energie?
- Hoe jij gewoonlijk andere partijen aanpakt

Schrijf 200-300 woorden alsof je net in een interview zit en de interviewer je dit artikel voorlegt. Begin direct met je reactie - geen beleefdheidsfrases. Laat zien wie je bent!

Denk aan:
- Hoe reageer je als {$leaderName} op kritiek?
- Wat zijn jouw vaste uitdrukkingen?
- Hoe verdedig je jouw standpunten?
- Wat is jouw unieke perspectief als leider?

Wees echt menselijk - toon frustratie, enthousiasme, vastberadenheid, teleurstelling... wat {$leaderName} ook zou voelen bij dit artikel.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Test de API verbinding
     */
    public function testConnection() {
        $testPrompt = "Zeg gewoon 'Hallo, de ChatGPT API werkt!' in het Nederlands.";
        return $this->makeAPICall($testPrompt);
    }
} 