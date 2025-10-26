<?php

class ChatGPTAPI {
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';
    private $model = 'gpt-4o'; // Upgraded for better reasoning and accuracy
    
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
    public function generatePoliticalAdvice($topMatches, $userAnswers = [], $questions = []) {
        $topThree = array_slice($topMatches, 0, 3);
        $topMatchesText = implode(', ', array_map(function($match) {
            return $match['name'] . ' (' . $match['agreement'] . '%)';
        }, $topThree));
        
        // Maak samenvatting van gebruiker's specifieke antwoorden
        $answerSummary = '';
        if (!empty($userAnswers) && !empty($questions)) {
            $answerSummary = $this->summarizeUserAnswers($userAnswers, $questions);
        }
        
        $prompt = "Je bent een Nederlandse politieke expert die persoonlijk stemadvies geeft.

Een gebruiker heeft de stemwijzer ingevuld met de volgende resultaten:

**Top 3 partij matches:** {$topMatchesText}";

        // Voeg specifieke antwoorden toe als deze beschikbaar zijn
        if (!empty($answerSummary)) {
            $prompt .= "\n\n**Specifieke standpunten van de gebruiker:**\n{$answerSummary}";
        }

        $prompt .= "\n\nGeef in ongeveer 200-250 woorden persoonlijk stemadvies gebaseerd uitsluitend op hun stemwijzer antwoorden:
1. Analyseer hun politieke voorkeuren op basis van hun concrete antwoorden
2. Waarom de top matches goed bij hen passen (verwijs naar specifieke antwoorden)
3. Op welke thema's ze extra moeten letten bij hun stemkeuze
4. Suggesties voor vervolgstappen (partijprogramma's lezen, debatten kijken, etc.)

Schrijf persoonlijk en bemoedigend. Begin met 'Op basis van jouw stemwijzer antwoorden...' Focus volledig op hun concrete standpunten en keuzes. Eindig met praktisch advies.";

        return $this->makeAPICall($prompt);
    }
    
    /**
     * Maak daadwerkelijke API call naar OpenAI
     */
    private function makeAPICall($prompt, $maxTokens = 300, $temperature = 0.7) {
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
            'max_tokens' => $maxTokens,
            'temperature' => $temperature
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
            'max_tokens' => 400,
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

    // ========================================
    // Politiek Gesprek Chatbot Methods
    // ========================================

    /**
     * Genereer een adaptieve vraag gebaseerd op eerdere antwoorden
     */
    public function generateChatQuestion($questionIndex, $previousAnswers, $userProfile) {
        $answersContext = $this->formatPreviousAnswers($previousAnswers);
        
        $prompt = "Je bent een Nederlandse politieke expert die een diepgaand stemwijzer gesprek voert.

**Context van het gesprek:**
De gebruiker heeft tot nu toe {$questionIndex} vragen beantwoord.

**Eerdere antwoorden:**
{$answersContext}

**Gebruikersprofiel tot nu toe:**
{$userProfile}

Genereer vraag nummer " . ($questionIndex + 1) . " (van 20) die:
1. Voortbouwt op hun eerdere antwoorden
2. Helpt om onduidelijkheden op te helderen
3. Dieper ingaat op controversiële onderwerpen
4. Relevant is voor de Nederlandse politiek van 2025
5. Helpt om keuze tussen partijen te verfijnen

Geef je antwoord in dit EXACTE JSON formaat:
{
    \"question\": \"De vraag die je wilt stellen (helder en direct)\",
    \"context\": \"Waarom deze vraag belangrijk is (2-3 zinnen)\",
    \"topic\": \"Onderwerp categorie (bijv. klimaat, immigratie, economie)\",
    \"follow_up_reason\": \"Waarom deze vraag relevant is op basis van eerdere antwoorden\"
}

Focus op actuele Nederlandse politieke thema's zoals:
- Stikstofcrisis en landbouw
- Asielbeleid en migratie
- Betaalbaarheid en inflatie
- Klimaatdoelen en energietransitie
- Zorg en vergrijzing
- Woningmarkt en betaalbaar wonen
- Onderwijs en studiefinanciering
- Defensie en NAVO
- EU-integratie vs nationale soevereiniteit

Wees specifiek en concreet. Geen algemene vragen.";

        return $this->makeAPICall($prompt, 400);
    }

    /**
     * Bepaal of een vraag multiple choice of open-ended moet zijn
     */
    public function determineQuestionMode($question, $topic, $previousModeCount) {
        $prompt = "Je bent een expert in het ontwerpen van politieke vragenlijsten.

**Vraag:** {$question}
**Onderwerp:** {$topic}

**Eerdere vraag types:**
- Multiple choice: {$previousModeCount['multiple_choice']}
- Open-ended: {$previousModeCount['open']}

Bepaal of deze vraag beter beantwoord kan worden met:
1. **Multiple choice** (eens/neutraal/oneens) - voor duidelijke standpunten, beleid, ja/nee situaties
2. **Open-ended** (vrije tekst) - voor complexe morele kwesties, genuanceerde meningen, persoonlijke overwegingen

Geef je antwoord in dit EXACTE JSON formaat:
{
    \"mode\": \"multiple_choice\" of \"open\",
    \"reasoning\": \"Korte uitleg waarom (1 zin)\",
    \"options\": [\"Eens\", \"Neutraal\", \"Oneens\"] (alleen bij multiple_choice, anders leeg array)
}

Richtlijnen:
- Gebruik multiple choice voor 60-70% van de vragen (voor betere vergelijkbaarheid)
- Gebruik open-ended voor complexe ethische vraagstukken
- Varieer tussen beide types voor een natuurlijk gesprek";

        return $this->makeAPICall($prompt, 200);
    }

    /**
     * Analyseer een open-ended antwoord en vertaal naar politieke positie
     */
    public function analyzeOpenAnswer($question, $answer, $topic) {
        $prompt = "Je bent een Nederlandse politieke expert die open antwoorden analyseert en vertaalt naar politieke standpunten.

**Vraag:** {$question}
**Onderwerp:** {$topic}
**Antwoord van gebruiker:** \"{$answer}\"

Analyseer dit antwoord en bepaal:
1. De politieke positie (links/centrum-links/centrum/centrum-rechts/rechts)
2. De stelligheid van het standpunt (0-100%)
3. Specifieke standpunten die blijken uit het antwoord
4. Welke Nederlandse partijen het meest aansluiten bij dit antwoord

Geef je antwoord in dit EXACTE JSON formaat:
{
    \"position\": \"eens|neutraal|oneens\",
    \"confidence\": 85,
    \"political_leaning\": \"links|centrum-links|centrum|centrum-rechts|rechts\",
    \"key_points\": [\"punt 1\", \"punt 2\", \"punt 3\"],
    \"party_alignment\": [\"Partij1\", \"Partij2\", \"Partij3\"],
    \"reasoning\": \"Waarom deze interpretatie (2-3 zinnen)\"
}

Wees objectief en baseer je alleen op wat de gebruiker daadwerkelijk zegt. Als het antwoord onduidelijk is, geef dat aan in de reasoning.";

        return $this->makeAPICall($prompt, 300);
    }

    /**
     * Genereer finale partij match analyse op basis van alle antwoorden
     */
    public function generateFinalMatch($allAnswers, $partyData, $userProfile) {
        $answersContext = $this->formatAllAnswersDetailed($allAnswers);
        
        // Limiteer party data voor context
        $partySummary = [];
        foreach ($partyData as $party) {
            $partySummary[] = $party['name'] . " (" . $party['short_name'] . ") - " . $party['current_seats'] . " zetels";
        }
        $partiesText = implode("\n", $partySummary);

        $prompt = "Je bent een Nederlandse politieke expert die uitgebreide stemadvies geeft na een diepgaand politiek gesprek.

**Alle 20 antwoorden van de gebruiker:**
{$answersContext}

**Beschikbare partijen:**
{$partiesText}

**Gebruikersprofiel:**
{$userProfile}

Geef een uitgebreide analyse (400-500 woorden) met:

## **Jouw Politieke Profiel**
[Beschrijf de gebruiker's politieke identiteit op basis van hun antwoorden]

## **Top 5 Partij Matches**
[Voor elke partij: waarom ze passen, sterke punten, mogelijke zorgen]

## **Diepgaande Analyse**
[Analyseer hun standpunten op belangrijkste thema's]

## **Stemadvies**
[Persoonlijk advies: welke partij het beste past en waarom]

## **Vervolgstappen**
[Praktische suggesties: programma's lezen, debatten kijken, etc.]

Schrijf persoonlijk en toegankelijk. Gebruik concrete voorbeelden uit hun antwoorden. Wees eerlijk over mogelijke verschillen met partijen.";

        return $this->makeAPICall($prompt, 1000);
    }

    /**
     * Format eerdere antwoorden voor context
     */
    private function formatPreviousAnswers($answers) {
        if (empty($answers)) {
            return "Nog geen eerdere antwoorden.";
        }

        $formatted = "";
        foreach ($answers as $index => $answerData) {
            $q = $answerData['question'] ?? 'Vraag ' . ($index + 1);
            $a = $answerData['answer'] ?? 'Geen antwoord';
            $formatted .= "Vraag " . ($index + 1) . ": {$q}\nAntwoord: {$a}\n\n";
        }

        return $formatted;
    }

    /**
     * Format alle antwoorden gedetailleerd voor finale analyse
     */
    private function formatAllAnswersDetailed($answers) {
        if (empty($answers)) {
            return "Geen antwoorden beschikbaar.";
        }

        $formatted = "";
        foreach ($answers as $index => $answerData) {
            $q = $answerData['question'] ?? 'Vraag ' . ($index + 1);
            $a = $answerData['answer'] ?? 'Geen antwoord';
            $topic = $answerData['topic'] ?? 'Algemeen';
            $mode = $answerData['mode'] ?? 'onbekend';
            
            $formatted .= "**Vraag " . ($index + 1) . " ({$topic}):**\n";
            $formatted .= "{$q}\n";
            $formatted .= "**Antwoord ({$mode}):** {$a}\n";
            
            if (isset($answerData['analysis'])) {
                $formatted .= "**Analyse:** {$answerData['analysis']}\n";
            }
            
            $formatted .= "\n";
        }

        return $formatted;
    }

    /**
     * Genereer conversationele introductie voor een vraag
     */
    public function generateQuestionIntro($questionNumber, $question, $context) {
        $prompt = "Je bent een vriendelijke Nederlandse politieke expert die een stemwijzer gesprek voert.

Dit is vraag {$questionNumber} van 20.

**De vraag:** {$question}
**Context:** {$context}

Schrijf een korte, natuurlijke inleiding (1-2 zinnen) voor deze vraag. Maak het conversationeel en betrokken, alsof je echt met iemand in gesprek bent.

Voorbeelden:
- \"Interessant! Laten we het nu over iets anders hebben...\"
- \"Dat is helder. Nu ben ik benieuwd naar jouw mening over...\"
- \"Goed om te weten. Het volgende onderwerp is misschien wat complexer...\"

Schrijf alleen de inleiding, geen verdere uitleg. Maximaal 2 zinnen.";

        return $this->makeAPICall($prompt, 100);
    }

    /**
     * Analyseer de voor- en nadelen van een politieke partij
     */
    public function analyzePartyProsAndCons($partyName, $partyData) {
        $standpoints = '';
        if (isset($partyData['standpoints']) && is_array($partyData['standpoints'])) {
            foreach ($partyData['standpoints'] as $topic => $standpoint) {
                $standpoints .= "- {$topic}: {$standpoint}\n";
            }
        }

        $prompt = "Je bent een objectieve Nederlandse politieke analist die kritisch maar eerlijk naar politieke partijen kijkt.

Analyseer {$partyName} en geef een genuanceerde beoordeling van hun voor- en nadelen.

**Partij informatie:**
Naam: {$partyName}
Beschrijving: {$partyData['description']}
Leider: {$partyData['leader']}
Huidige zetels: {$partyData['current_seats']}
Peilingen: {$partyData['polling']['seats']} zetels

**Standpunten:**
{$standpoints}

Geef een gebalanceerde analyse in dit formaat:

## **Voordelen van {$partyName}**
[3-4 sterke punten met uitleg waarom deze positief zijn]

## **Nadelen van {$partyName}**  
[3-4 zwakke punten of kritieken met uitleg]

## **Voor wie is deze partij geschikt?**
[Beschrijf welke kiezers het best bij deze partij passen]

## **Conclusie**
[Korte, eerlijke samenvatting van de partij]

Schrijf elke punt uit in 1-2 zinnen. Wees eerlijk over zowel sterke als zwakke punten. Gebruik concrete voorbeelden waar mogelijk. Blijf objectief en informatief - geen extreme kritiek of lofzang.

Totaal ongeveer 200-250 woorden.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Analyseer de voor- en nadelen van een partijleider
     */
    public function analyzeLeaderProsAndCons($leaderName, $partyName, $leaderInfo, $partyData) {
        $prompt = "Je bent een objectieve Nederlandse politieke analist die kritisch naar politieke leiders kijkt.

Analyseer {$leaderName} als partijleider van {$partyName} en geef een eerlijke beoordeling van zijn/haar sterke en zwakke punten.

**Leider informatie:**
Naam: {$leaderName}
Partij: {$partyName}
Achtergrond: {$leaderInfo}
Huidige positie: Partijleider van {$partyName} ({$partyData['current_seats']} zetels)

Geef een gebalanceerde analyse in dit formaat:

## ✅ **Sterke punten van {$leaderName}**
[3-4 positieve eigenschappen als leider met uitleg]

## ❌ **Zwakke punten van {$leaderName}**
[3-4 kritieken of verbeterpunten met uitleg]

## 🗣️ **Leiderschapsstijl**
[Beschrijf hoe hij/zij de partij leidt en communiceert]

## 📊 **Politieke Impact**
[Wat heeft hij/zij bereikt voor de partij en Nederland?]

Schrijf elke punt uit in 2-3 zinnen. Wees eerlijk over zowel positieve als negatieve aspecten. Gebruik concrete voorbeelden uit de Nederlandse politiek waar mogelijk. Blijf respectvol maar kritisch.

Focus op:
- Communicatievaardigheden
- Politieke ervaring
- Leiderschapskwaliteiten  
- Beleidsinhoudelijke kennis
- Electorale aantrekkingskracht
- Controverses (indien van toepassing)

Totaal ongeveer 200-250 woorden.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Genereer kiezer profiel voor een politieke partij
     */
    public function generateVoterProfile($partyName, $partyData) {
        $standpoints = '';
        if (isset($partyData['standpoints']) && is_array($partyData['standpoints'])) {
            foreach ($partyData['standpoints'] as $topic => $standpoint) {
                $standpoints .= "- {$topic}: {$standpoint}\n";
            }
        }

        $prompt = "Je bent een Nederlandse politieke socioloog en dataanalist die kiezersgedrag bestudeert.

Maak een gedetailleerd profiel van de typische {$partyName} kiezer op basis van demografische data, polls en verkiezingsresultaten.

**Partij informatie:**
Naam: {$partyName}
Beschrijving: {$partyData['description']}
Leider: {$partyData['leader']}
Huidige zetels: {$partyData['current_seats']}
Peilingen: {$partyData['polling']['seats']} zetels

**Standpunten:**
{$standpoints}

Creëer een uitgebreid kiezersprofiel in dit formaat:

## 👥 **Demografisch Profiel**
- Leeftijd en geslacht verdeling
- Opleidingsniveau en beroepen
- Inkomensniveau en woonsituatie
- Geografische spreiding in Nederland

## 🧠 **Psychografisch Profiel**
- Waarden en overtuigingen
- Levensstijl en prioriteiten
- Politieke attitudes en engagement
- Media consumptie en informatiebronnen

## 💭 **Motivaties & Zorgen**
- Hoofdredenen om op {$partyName} te stemmen
- Grootste politieke zorgen en frustraties
- Verwachtingen van de regering
- Hoop voor de toekomst van Nederland

## 🎯 **Typische Kiezer Persona**
[Beschrijf 2-3 concrete voorbeelden van typische kiezers met namen, leeftijden en achtergronden]

Baseer je analyse op Nederlandse verkiezingsdata, peilingen en sociologisch onderzoek. Wees specifiek en realistisch. Gebruik concrete percentages en trends waar mogelijk.

Totaal ongeveer 250-300 woorden.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Genereer politieke timeline voor een partij
     */
    public function generatePoliticalTimeline($partyName, $partyData) {
        $prompt = "Je bent een Nederlandse politieke historicus die partijgeschiedenissen documenteert.

Creëer een chronologische timeline van belangrijke momenten voor {$partyName} vanaf de oprichting tot nu.

**Partij informatie:**
Naam: {$partyName}
Beschrijving: {$partyData['description']}
Huidige leider: {$partyData['leader']}
Huidige zetels: {$partyData['current_seats']}

Maak een timeline in dit formaat:

## 📅 **Politieke Timeline van {$partyName}**

### **Oprichting & Beginjaren**
[Jaar van oprichting, oprichters, oorspronkelijke ideologie]

### **Belangrijke Mijlpalen**
[Chronologische lijst van 8-10 cruciale momenten, elk met jaartal en uitleg]

### **Leiderschapswisselingen**
[Overzicht van partijleiders door de jaren heen]

### **Electorale Hoogte- en Dieptepunten**
[Beste en slechtste verkiezingsresultaten met context]

### **Beleidsevolutie**
[Hoe zijn hun standpunten veranderd over tijd?]

### **Recente Ontwikkelingen**
[Laatste 5 jaar: wat is er gebeurd?]

Focus op:
- Verkiezingsresultaten en doorbraken
- Belangrijke beleidsstandpunten en -wijzigingen
- Coalities en regeringsdeelnames
- Controverses en crises
- Leiderschapswisselingen
- Fusies of splitsingen
- Invloedrijke speeches of momenten

Gebruik concrete jaartallen en wees historisch accuraat. Elke mijlpaal moet 2-3 zinnen uitleg krijgen.

Totaal ongeveer 400-500 woorden.";

        return $this->makeAPICall($prompt);
    }

    /**
     * Beantwoord specifieke vragen over een politieke partij
     */
    public function answerPartyQuestion($question, $partyName, $partyData) {
        $standpoints = '';
        if (isset($partyData['standpoints']) && is_array($partyData['standpoints'])) {
            foreach ($partyData['standpoints'] as $topic => $standpoint) {
                $standpoints .= "- {$topic}: {$standpoint}\n";
            }
        }

        $prompt = "Je bent een Nederlandse politieke expert die specialistische kennis heeft over alle Nederlandse politieke partijen.

Een gebruiker stelt de volgende vraag over {$partyName}:

**VRAAG:** {$question}

**Partij informatie om de vraag te beantwoorden:**
Naam: {$partyName}
Beschrijving: {$partyData['description']}
Leider: {$partyData['leader']}
Leider info: {$partyData['leader_info']}
Huidige zetels: {$partyData['current_seats']}
Peilingen: {$partyData['polling']['seats']} zetels

**Standpunten:**
{$standpoints}

Beantwoord de vraag uitgebreid en informatief in dit formaat:

## 📋 **Antwoord**
[Direct antwoord op de gestelde vraag in 2-3 alinea's]

## 🔍 **Achtergrond & Context**
[Relevante achtergrond informatie en context]

## 📊 **Vergelijking**
[Hoe verschilt {$partyName} hierop van andere grote Nederlandse partijen?]

## 💡 **Praktische Gevolgen**
[Wat zou dit betekenen als {$partyName} aan de macht komt?]

Richtlijnen:
- Geef concrete, feitelijke antwoorden
- Gebruik voorbeelden uit verkiezingsprogramma's
- Vergelijk met andere partijen waar relevant
- Wees objectief en evenwichtig
- Cite specifieke standpunten of uitspraken
- Leg complexe onderwerpen uit in begrijpelijke taal

Als de vraag niet beantwoord kan worden met de beschikbare informatie, zeg dit eerlijk.

Totaal ongeveer 300-400 woorden.";

        return $this->makeAPICall($prompt);
    }
} 