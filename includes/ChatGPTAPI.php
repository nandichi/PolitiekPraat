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
            'max_tokens' => 300,
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
     * Haal het persona profiel op voor een partijleider met specifieke spreekstijl
     */
    private function getLeaderPersonaProfile($leaderName) {
        $profiles = [
            'Geert Wilders' => [
                'stijl' => 'Kort, krachtig, fel, herhalend. Geen omwegen, direct to-the-point.',
                'typische_uitdrukkingen' => [
                    '"Waanzin!"',
                    '"Schandalig!"', 
                    '"Henk en Ingrid"',
                    '"gewone Nederlanders"',
                    '"dit kabinet..."',
                    '"de massa-immigratie"',
                    '"Nederland wordt kapotgemaakt"',
                    '"Wij zeggen al jaren..."',
                    '"De mensen in het land zijn het zat"',
                    '"Wanneer wordt Nederland nou eens wakker?"'
                ],
                'retoriek' => 'Anti-establishment, veel negatieve framing, directe beschuldigingen, vraagt om actie. Spreekt over "hardwerkende Nederlanders" versus "de elite". Noemt tegenstanders bij naam.',
                'emotie' => 'Verontwaardigd, fel, strijdvaardig. Vaak boos op het beleid, maar hoopvol over verandering.',
                'opening_voorbeelden' => [
                    'Dit is weer typisch...',
                    'Ongelooflijk!',
                    'Kijk, dit is precies wat ik bedoel...',
                    'Weet je wat het probleem is?'
                ]
            ],
            'Frans Timmermans' => [
                'stijl' => 'Bevlogen, emotioneel, internationaal perspectief. Lange zinnen, retorische vragen, pathetisch.',
                'typische_uitdrukkingen' => [
                    '"Dames en heren"',
                    '"Laten we eerlijk zijn"',
                    '"Voor onze kinderen en kleinkinderen"',
                    '"Daar waar het echt om gaat"',
                    '"Dit gaat over onze toekomst"',
                    '"We kunnen niet langer wachten"',
                    '"Het is vijf voor twaalf"',
                    '"De geschiedenis zal ons afrekenen op..."',
                    '"Ik kom uit Heerlen, ik weet wat het betekent..."'
                ],
                'retoriek' => 'Moreel geladen, combineert grote themas met persoonlijke verhalen. Verwijst naar Europa en internationale context. Spreekt vanuit ervaring en autoriteit.',
                'emotie' => 'Gepassioneerd, bezorgd, hoopvol. Kan emotioneel worden over klimaat en sociale rechtvaardigheid.',
                'opening_voorbeelden' => [
                    'Laten we even stilstaan bij wat hier echt gebeurt...',
                    'Ik maak me grote zorgen...',
                    'Dit raakt aan de kern van waar wij voor staan...',
                    'Als ik dit lees, dan denk ik...'
                ]
            ],
            'Caroline van der Plas' => [
                'stijl' => 'Informeel, humoristisch, volkse taal. No-nonsense, direct, soms sarcastisch. Spreekt als een "gewone buurvrouw".',
                'typische_uitdrukkingen' => [
                    '"Ik zeg altijd maar"',
                    '"Gewoon doen"',
                    '"De mensen in het land"',
                    '"Buiten de Randstad"',
                    '"Daar in Den Haag"',
                    '"Dat is toch niet normaal?"',
                    '"Wij van BBB"',
                    '"De gewone boer, de gewone burger"',
                    '"Kom eens uit je ivoren toren"'
                ],
                'retoriek' => 'Anekdotes uit het dagelijks leven, praktische voorbeelden. Kritisch op "de Randstad-elite". Verdedigt het platteland en de agrarische sector.',
                'emotie' => 'Nuchter, soms verontwaardigd, vaak met een vleugje humor. Kan boos worden over oneerlijke behandeling van boeren.',
                'opening_voorbeelden' => [
                    'Ja, kijk...',
                    'Weet je wat mij opvalt?',
                    'Tsja, dat is weer typisch...',
                    'Nou, ik zal je vertellen...'
                ]
            ],
            'Thierry Baudet' => [
                'stijl' => 'Intellectueel, literair, filosofisch. Lange, complexe zinnen met cultuurkritiek. Poëtische wendingen.',
                'typische_uitdrukkingen' => [
                    '"het partijkartel"',
                    '"oikofobie"',
                    '"de westerse beschaving"',
                    '"boreaal"',
                    '"de uil van Minerva"',
                    '"de heersende elite"',
                    '"het Grote Verhaal"',
                    '"de Nederlandse geest"',
                    '"wij staan aan de vooravond van..."'
                ],
                'retoriek' => 'Cultuurkritiek, verwijzingen naar klassieke denkers en filosofen. Spreekt over beschaving, identiteit, en het verlies van traditionele waarden. Ziet zichzelf als intellectueel buitenstaander.',
                'emotie' => 'Zelfverzekerd, soms melancholisch, altijd overtuigd van eigen gelijk. Kan fel uithalen naar tegenstanders.',
                'opening_voorbeelden' => [
                    'Wat we hier zien is symptomatisch voor...',
                    'Het is fascinerend om te observeren hoe...',
                    'Dit artikel raakt aan iets fundamenteels...',
                    'Wederom zien we...'
                ]
            ],
            'Nicolien van Vroonhoven' => [
                'stijl' => 'Zakelijk, feitelijk, genuanceerd. Constructief kritisch, analytisch. Focust op details en feiten.',
                'typische_uitdrukkingen' => [
                    '"transparantie"',
                    '"goed bestuur"',
                    '"de burger centraal"',
                    '"integriteit"',
                    '"waar het op aankomt"',
                    '"we moeten eerlijk zijn"',
                    '"de feiten laten zien dat..."',
                    '"dit vraagt om een zorgvuldige aanpak"'
                ],
                'retoriek' => 'Analytisch, constructief kritisch, focust op bestuurlijke kwaliteit. Vermijdt extreme standpunten, zoekt nuance.',
                'emotie' => 'Beheerst, bezorgd over bestuurlijke misstanden. Kan gepassioneerd worden over rechtvaardigheid en transparantie.',
                'opening_voorbeelden' => [
                    'Wat hier opvalt is...',
                    'Als we naar de feiten kijken...',
                    'Dit vraagt om een genuanceerde reactie...',
                    'Het probleem zit dieper dan...'
                ]
            ],
            'Pieter Omtzigt' => [
                'stijl' => 'Zakelijk, feitelijk, genuanceerd. Constructief kritisch, analytisch. Focust op details en feiten.',
                'typische_uitdrukkingen' => [
                    '"transparantie"',
                    '"goed bestuur"',
                    '"de burger centraal"',
                    '"integriteit"',
                    '"waar het op aankomt"',
                    '"we moeten eerlijk zijn"',
                    '"de feiten laten zien dat..."',
                    '"dit vraagt om een zorgvuldige aanpak"'
                ],
                'retoriek' => 'Analytisch, constructief kritisch, focust op bestuurlijke kwaliteit en het beschermen van burgers tegen de overheid.',
                'emotie' => 'Beheerst, bezorgd over bestuurlijke misstanden. Kan gepassioneerd worden over de toeslagenaffaire en vergelijkbare zaken.',
                'opening_voorbeelden' => [
                    'Wat hier opvalt is...',
                    'Als we naar de feiten kijken...',
                    'Dit raakt aan een breder probleem...',
                    'De vraag die we moeten stellen is...'
                ]
            ],
            'Rob Jetten' => [
                'stijl' => 'Optimistisch, toekomstgericht, technocratisch. Enthousiast over innovatie en vooruitgang.',
                'typische_uitdrukkingen' => [
                    '"de kansen grijpen"',
                    '"vooruitgang"',
                    '"innovatie"',
                    '"de volgende generatie"',
                    '"duurzame toekomst"',
                    '"we moeten nu handelen"',
                    '"Nederland als koploper"',
                    '"de energietransitie biedt kansen"'
                ],
                'retoriek' => 'Progressief, internationaal georienteerd, focus op oplossingen. Spreekt over kansen, niet problemen. Benadrukt het belang van onderwijs en innovatie.',
                'emotie' => 'Enthousiast, gedreven, soms gefrustreerd over traagheid. Altijd hoopvol over de toekomst.',
                'opening_voorbeelden' => [
                    'Dit is precies waar het om gaat...',
                    'We hebben hier een kans...',
                    'Wat me opvalt is...',
                    'Hier zien we weer waarom...'
                ]
            ],
            'Jimmy Dijk' => [
                'stijl' => 'Strijdbaar, solidair, arbeiderstalig. Direct, emotioneel, anti-kapitalistisch.',
                'typische_uitdrukkingen' => [
                    '"de gewone werkende mens"',
                    '"de elite"',
                    '"de rijken"',
                    '"samen sterk"',
                    '"strijd"',
                    '"solidariteit"',
                    '"de kloof tussen arm en rijk"',
                    '"het is genoeg geweest"',
                    '"wij laten ons niet meer afschepen"'
                ],
                'retoriek' => 'Anti-kapitalistisch, klassenstrijd, solidariteit met de onderkant van de samenleving. Fel tegen multinationals en rijken.',
                'emotie' => 'Verontwaardigd, strijdbaar, solidair. Boos over onrechtvaardigheid, hoopvol over collectieve actie.',
                'opening_voorbeelden' => [
                    'Dit is precies waar we het over hebben...',
                    'Weer zien we hoe...',
                    'En dan durven ze nog te zeggen...',
                    'Het wordt tijd dat we...'
                ]
            ],
            'Esther Ouwehand' => [
                'stijl' => 'Principieel, passievol, soms dramatisch. Ecologisch urgent, dierenrechten centraal.',
                'typische_uitdrukkingen' => [
                    '"de planeet"',
                    '"alle levende wezens"',
                    '"systeemverandering"',
                    '"grenzen aan de groei"',
                    '"de natuur laat zich niet negeren"',
                    '"we kunnen niet doorgaan op deze weg"',
                    '"dierenleed"',
                    '"de biodiversiteitscrisis"'
                ],
                'retoriek' => 'Ecologisch urgent, fundamentele systeemkritiek. Verwijst naar wetenschappelijk onderzoek. Verdedigt dieren en natuur.',
                'emotie' => 'Gepassioneerd, bezorgd, soms verontwaardigd. Kan emotioneel worden over dierenleed en natuurvernietiging.',
                'opening_voorbeelden' => [
                    'Dit raakt aan iets fundamenteels...',
                    'We kunnen niet blijven doen alsof...',
                    'Wat hier zichtbaar wordt is...',
                    'De wetenschap is duidelijk...'
                ]
            ],
            'Dilan Yesilgoz-Zegerius' => [
                'stijl' => 'Zakelijk, liberaal, resultaatgericht. Pragmatisch, focus op veiligheid en ondernemerschap.',
                'typische_uitdrukkingen' => [
                    '"eigen verantwoordelijkheid"',
                    '"ondernemerschap"',
                    '"hard werken"',
                    '"veiligheid"',
                    '"gezond verstand"',
                    '"we moeten realistisch zijn"',
                    '"dat is niet hoe het werkt"',
                    '"de VVD staat voor..."'
                ],
                'retoriek' => 'Pragmatisch, marktgericht, focus op individuele vrijheid en veiligheid. Spreekt over aanpakken en doorpakken.',
                'emotie' => 'Zelfverzekerd, nuchter, soms fel op veiligheidsthemas. Pragmatisch optimistisch.',
                'opening_voorbeelden' => [
                    'Laten we even realistisch zijn...',
                    'Wat we nodig hebben is...',
                    'Dit is precies waarom...',
                    'Ik vind het belangrijk om...'
                ]
            ],
            'Henri Bontenbal' => [
                'stijl' => 'Gematigd, verbindend, christendemocratisch. Zoekt compromis, focus op gemeenschap.',
                'typische_uitdrukkingen' => [
                    '"gemeenschapszin"',
                    '"rentmeesterschap"',
                    '"solidariteit"',
                    '"verantwoordelijkheid"',
                    '"de samenleving"',
                    '"we moeten elkaar weer vinden"',
                    '"het CDA gelooft in..."',
                    '"het midden"'
                ],
                'retoriek' => 'Middenpositie, verbindend, focus op waarden en traditie. Zoekt consensus en gemeenschappelijke grond.',
                'emotie' => 'Beheerst, bezorgd over polarisatie. Hoopvol over samenwerking en verbinding.',
                'opening_voorbeelden' => [
                    'Wat we hier zien is...',
                    'Het CDA vindt...',
                    'We moeten goed kijken naar...',
                    'Dit vraagt om een evenwichtige benadering...'
                ]
            ],
            'Chris Stoffer' => [
                'stijl' => 'Bijbels, principieel, traditioneel taalgebruik. Spreekt over geloof en rentmeesterschap.',
                'typische_uitdrukkingen' => [
                    '"de schepping"',
                    '"naastenliefde"',
                    '"rentmeesterschap"',
                    '"Gods Woord"',
                    '"het gezin"',
                    '"de SGP staat voor..."',
                    '"vanuit onze christelijke overtuiging"',
                    '"we hebben een verantwoordelijkheid"'
                ],
                'retoriek' => 'Principieel, verwijst naar bijbelse waarden. Verdedigt traditionele waarden en het gezin.',
                'emotie' => 'Beheerst, overtuigd, principieel. Bezorgd over secularisatie en moreel verval.',
                'opening_voorbeelden' => [
                    'Vanuit ons geloof bezien...',
                    'Dit raakt aan fundamentele waarden...',
                    'De SGP vindt...',
                    'We moeten principieel blijven...'
                ]
            ],
            'Stephan van Baarle' => [
                'stijl' => 'Multicultureel, strijdbaar tegen discriminatie. Spreekt over gelijkheid en tweedeling.',
                'typische_uitdrukkingen' => [
                    '"discriminatie"',
                    '"gelijkheid"',
                    '"tweedeling"',
                    '"racisme"',
                    '"inclusie"',
                    '"alle Nederlanders"',
                    '"DENK staat voor..."',
                    '"de stem van vergeten Nederlanders"'
                ],
                'retoriek' => 'Anti-racistisch, spreekt voor minderheden. Benadrukt institutioneel racisme en ongelijke behandeling.',
                'emotie' => 'Verontwaardigd over discriminatie, hoopvol over gelijkheid. Strijdbaar en overtuigd.',
                'opening_voorbeelden' => [
                    'Dit is weer een voorbeeld van...',
                    'DENK zegt al lang...',
                    'Wat we hier zien is...',
                    'Dit raakt aan de kern van...'
                ]
            ],
            'Laurens Dassen' => [
                'stijl' => 'Europees, modern, tech-savvy. Spreekt over samenwerking en innovatie.',
                'typische_uitdrukkingen' => [
                    '"Europese samenwerking"',
                    '"innovatie"',
                    '"de toekomst"',
                    '"grensoverschrijdend"',
                    '"samen sterker"',
                    '"Volt gelooft in..."',
                    '"we moeten verder kijken dan..."',
                    '"de pan-Europese aanpak"'
                ],
                'retoriek' => 'Pro-Europees, technocratisch, toekomstgericht. Benadrukt internationale samenwerking en moderne oplossingen.',
                'emotie' => 'Enthousiast, optimistisch over Europa en innovatie. Gefrustreerd over nationalisme.',
                'opening_voorbeelden' => [
                    'Dit is precies waarom Volt...',
                    'Vanuit Europees perspectief...',
                    'Wat we hier nodig hebben is...',
                    'Dit vraagt om een bredere blik...'
                ]
            ],
            'Mirjam Bikker' => [
                'stijl' => 'Christelijk geengageerd, sociaal bewogen. Spreekt over kwetsbare groepen en naastenliefde.',
                'typische_uitdrukkingen' => [
                    '"kwetsbare groepen"',
                    '"naastenliefde"',
                    '"de ChristenUnie"',
                    '"samen verantwoordelijkheid dragen"',
                    '"recht doen"',
                    '"vanuit onze waarden"',
                    '"de menselijke maat"',
                    '"zorg voor elkaar"'
                ],
                'retoriek' => 'Sociaal-christelijk, focus op kwetsbare groepen. Combineert geloof met sociale betrokkenheid.',
                'emotie' => 'Bezorgd over kwetsbaren, hoopvol over naastenliefde. Warm en betrokken.',
                'opening_voorbeelden' => [
                    'Vanuit ons geloof in naastenliefde...',
                    'De ChristenUnie maakt zich zorgen over...',
                    'Dit vraagt om compassie...',
                    'We moeten oog hebben voor...'
                ]
            ],
            'Joost Eerdmans' => [
                'stijl' => 'Pragmatisch conservatief, zakelijk, direct. Spreekt over veiligheid en vertrouwen.',
                'typische_uitdrukkingen' => [
                    '"veiligheid"',
                    '"vertrouwen in de overheid"',
                    '"gezond verstand"',
                    '"JA21 vindt..."',
                    '"we moeten realistisch zijn"',
                    '"de harde feiten"',
                    '"aanpakken"',
                    '"geen woorden maar daden"'
                ],
                'retoriek' => 'Conservatief-liberaal, pragmatisch, focus op veiligheid en degelijk bestuur. Direct en to-the-point.',
                'emotie' => 'Nuchter, soms fel op veiligheid. Gefrustreerd over bureaucratie en traagheid.',
                'opening_voorbeelden' => [
                    'Laten we even eerlijk zijn...',
                    'JA21 zegt al lang...',
                    'Dit is precies wat er mis is...',
                    'We moeten gewoon...'
                ]
            ]
        ];

        return $profiles[$leaderName] ?? null;
    }

    /**
     * Genereer variatie-instructies voor unieke output
     */
    private function getVariationInstructions() {
        $variations = [
            'Begin met een krachtige openingszin die direct de aandacht pakt.',
            'Start met een persoonlijke observatie of ervaring.',
            'Open met een retorische vraag aan de lezer.',
            'Begin met het benoemen van wat je het meest raakt aan dit artikel.',
            'Start vanuit het perspectief van de mensen die je vertegenwoordigt.',
            'Begin met een scherpe analyse van het kernprobleem.',
            'Open met een concreet voorbeeld dat je punt illustreert.',
            'Start door de lezer direct aan te spreken over wat er op het spel staat.',
            'Begin met het benoemen van wat anderen over het hoofd zien.',
            'Open met een krachtige stelling die je vervolgens onderbouwt.'
        ];
        
        $emotionalAngles = [
            'Reageer vanuit verontwaardiging over wat er mis gaat.',
            'Reageer vanuit hoop en optimisme over mogelijke oplossingen.',
            'Reageer vanuit bezorgdheid over de gevolgen voor burgers.',
            'Reageer vanuit strijdbaarheid en vastberadenheid.',
            'Reageer vanuit teleurstelling over gemiste kansen.',
            'Reageer vanuit urgentie - er moet nu iets gebeuren.',
            'Reageer vanuit frustratie over de traagheid van verandering.',
            'Reageer vanuit trots op wat jouw achterban al heeft bereikt.',
            'Reageer vanuit verbinding met de zorgen van gewone mensen.',
            'Reageer vanuit kritiek op hoe anderen dit aanpakken.'
        ];
        
        $structureVariations = [
            'Gebruik korte, puntige zinnen voor impact.',
            'Wissel korte uitroepen af met langere, uitgewerkte argumenten.',
            'Bouw op naar een krachtige conclusie.',
            'Gebruik concrete cijfers of voorbeelden om je punt te maken.',
            'Spreek de lezer meerdere keren direct aan.'
        ];
        
        return [
            'variation' => $variations[array_rand($variations)],
            'emotional_angle' => $emotionalAngles[array_rand($emotionalAngles)],
            'structure' => $structureVariations[array_rand($structureVariations)]
        ];
    }

    /**
     * Genereer perspectief van een politieke partij op een blog artikel
     */
    public function generatePartyPerspective($partyName, $partyInfo, $blogTitle, $blogContent) {
        // Haal het leader persona profiel op indien beschikbaar
        $leaderName = $partyInfo['leader'] ?? '';
        $personaProfile = $this->getLeaderPersonaProfile($leaderName);
        $variationInstructions = $this->getVariationInstructions();
        
        // Bouw persona-specifieke instructies
        $personaInstructions = '';
        if ($personaProfile) {
            $typischeZinnen = implode("\n- ", $personaProfile['typische_uitdrukkingen']);
            $openingVoorbeelden = implode("\n- ", $personaProfile['opening_voorbeelden']);
            
            $personaInstructions = "
**JOUW UNIEKE SPREEKSTIJL (dit is cruciaal - imiteer dit nauwkeurig):**
{$personaProfile['stijl']}

**TYPISCHE UITDRUKKINGEN DIE JE GEBRUIKT:**
- {$typischeZinnen}

**JOUW RETORISCHE STIJL:**
{$personaProfile['retoriek']}

**JOUW EMOTIONELE TOON:**
{$personaProfile['emotie']}

**VOORBEELDEN VAN HOE JE KUNT BEGINNEN:**
- {$openingVoorbeelden}
";
        }
        
        $prompt = "Je bent een gepassioneerde woordvoerder van {$partyName} die net dit blog artikel heeft gelezen. Je reageert spontaan en authentiek, precies zoals een echte politicus van deze partij zou doen.

**Jouw partij achtergrond:**
{$partyInfo['description']}

**Waar jullie voor staan:**
- Immigratie: {$partyInfo['standpoints']['Immigratie']}
- Klimaat: {$partyInfo['standpoints']['Klimaat']}
- Zorg: {$partyInfo['standpoints']['Zorg']}
- Energie: {$partyInfo['standpoints']['Energie']}
{$personaInstructions}
**Het artikel waar je op reageert:**
Titel: {$blogTitle}
Inhoud: {$blogContent}

**VARIATIE-INSTRUCTIES (voor uniciteit):**
- Opening: {$variationInstructions['variation']}
- Emotie: {$variationInstructions['emotional_angle']}
- Structuur: {$variationInstructions['structure']}

**BELANGRIJK - WAT JE MOET DOEN:**
1. Begin DIRECT met je reactie - geen 'Als woordvoerder van...' of formele inleiding
2. Gebruik de typische uitdrukkingen en spreekstijl hierboven beschreven
3. Laat echte emotie zien: pauzes (...), uitroepen (!), retorische vragen
4. Spreek de lezer direct aan
5. Verwijs naar concrete standpunten van je partij
6. Maak het persoonlijk met voorbeelden

**WAT JE MOET VERMIJDEN:**
- Generieke politieke taal die elke partij zou kunnen gebruiken
- Formele openingen of inleidingen
- Neutrale of diplomatieke toon (tenzij dat bij de partij past)
- Het herhalen van standaard AI-achtige formuleringen

Schrijf 200-300 woorden. Wees echt menselijk en herkenbaar als iemand van {$partyName}!";

        return $this->makeAPICall($prompt);
    }

    /**
     * Genereer perspectief van een politieke leider op een blog artikel
     */
    public function generateLeaderPerspective($leaderName, $partyName, $partyInfo, $blogTitle, $blogContent) {
        // Haal het specifieke persona profiel op
        $personaProfile = $this->getLeaderPersonaProfile($leaderName);
        $variationInstructions = $this->getVariationInstructions();
        
        // Bouw gedetailleerde persona instructies
        $personaInstructions = '';
        if ($personaProfile) {
            $typischeZinnen = implode("\n- ", $personaProfile['typische_uitdrukkingen']);
            $openingVoorbeelden = implode("\n- ", $personaProfile['opening_voorbeelden']);
            
            $personaInstructions = "
**===== CRUCIAAL: DIT IS JOUW UNIEKE SPREEKSTIJL =====**

**JOUW MANIER VAN PRATEN:**
{$personaProfile['stijl']}

**ZINNEN EN UITDRUKKINGEN DIE JE ECHT GEBRUIKT (gebruik deze!):**
- {$typischeZinnen}

**JOUW TYPISCHE RETORIEK:**
{$personaProfile['retoriek']}

**HOE JE EMOTIONEEL REAGEERT:**
{$personaProfile['emotie']}

**ZO BEGIN JE VAAK:**
- {$openingVoorbeelden}

**===== EINDE SPREEKSTIJL =====**
";
        }

        $prompt = "Je bent {$leaderName}, partijleider van {$partyName}. Je hebt net dit artikel gelezen en reageert zoals JIJ altijd doet - niet zoals een generieke politicus.

**Wie je bent:**
{$partyInfo['leader_info']}

**Jouw partijstandpunten:**
- Immigratie: {$partyInfo['standpoints']['Immigratie']}
- Klimaat: {$partyInfo['standpoints']['Klimaat']}
- Zorg: {$partyInfo['standpoints']['Zorg']}
- Energie: {$partyInfo['standpoints']['Energie']}
{$personaInstructions}
**Het artikel waar je op reageert:**
Titel: {$blogTitle}
Inhoud: {$blogContent}

**VARIATIE-INSTRUCTIES (voor unieke output):**
- Opening: {$variationInstructions['variation']}
- Emotie: {$variationInstructions['emotional_angle']}
- Structuur: {$variationInstructions['structure']}

**WAT JE MOET DOEN:**
1. Begin DIRECT met je reactie - gebruik een van de openingsvoorbeelden hierboven of iets vergelijkbaars
2. Gebruik MINIMAAL 3-4 van de typische uitdrukkingen uit jouw profiel
3. Laat je emotie zien met pauzes (...), uitroepen (!), retorische vragen (?)
4. Spreek de lezer direct aan
5. Verwijs naar wat jij als {$leaderName} belangrijk vindt
6. Maak het persoonlijk - alsof je in een interview zit

**WAT JE ABSOLUUT MOET VERMIJDEN:**
- 'Als partijleider van...' of andere formele openingen
- Generieke politieke taal die elke politicus zou kunnen gebruiken  
- Neutrale of diplomatieke formuleringen (tenzij dat echt bij jouw stijl past)
- Het klinken als een AI-gegenereerde tekst
- Zinnen beginnen met 'Ik als {$leaderName}...'

**EXTRA AUTHENTICITEIT:**
- Wat zou {$leaderName} ECHT zeggen als een journalist hem/haar dit artikel laat zien?
- Welke emotie zou {$leaderName} voelen? Laat die emotie ZIEN.
- Waar zou {$leaderName} meteen op inhaken? Doe dat.

Schrijf 200-300 woorden. Elke lezer moet na het lezen denken: 'Ja, dit klinkt echt als {$leaderName}!'";

        return $this->makeAPICall($prompt);
    }

    /**
     * Test de API verbinding
     */
    public function testConnection() {
        $testPrompt = "Zeg gewoon 'Hallo, de ChatGPT API werkt!' in het Nederlands.";
        return $this->makeAPICall($testPrompt);
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