<?php
require_once '../../includes/config.php';
require_once '../../includes/Database.php';
require_once '../../includes/ChatGPTAPI.php';

// Maak een minimale BlogController klasse voor bias analyse (zonder Parsedown)
class SimpleBlogController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Haal blog op voor bias analyse (zonder Markdown parsing)
     */
    public function getBySlugForAnalysis($slug) {
        $this->db->query("SELECT blogs.*, users.username as author_name 
                         FROM blogs 
                         JOIN users ON blogs.author_id = users.id 
                         WHERE blogs.slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    /**
     * Analyseer politieke bias van een blog artikel
     */
    public function analyzeBias($slug) {
        try {
            // Haal blog op zonder Markdown parsing
            $blog = $this->getBySlugForAnalysis($slug);
            
            if (!$blog) {
                return [
                    'success' => false,
                    'error' => 'Blog niet gevonden'
                ];
            }

            // Initialiseer ChatGPT API
            $chatGPT = new ChatGPTAPI();
            
            // Analyseer de bias
            $result = $chatGPT->analyzePoliticalBias($blog->title, $blog->content);
            
            if ($result['success']) {
                // Parse JSON response
                $analysis = json_decode($result['content'], true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'success' => true,
                        'analysis' => $analysis
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Fout bij het verwerken van de AI analyse',
                        'raw_response' => $result['content']
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => $result['error'] ?? 'Onbekende fout bij AI analyse'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Fout bij bias analyse: ' . $e->getMessage()
            ];
        }
    }
}

/**
 * Valideer en standaardiseer de uitgebreide bias analyse response
 */
function validateBiasAnalysis($analysis) {
    // Definieer de verwachte structuur met defaults
    $defaults = [
        'spectrum' => [
            'economisch' => ['score' => 0, 'label' => 'Niet geanalyseerd', 'toelichting' => ''],
            'sociaal_cultureel' => ['score' => 0, 'label' => 'Niet geanalyseerd', 'toelichting' => ''],
            'eu_internationaal' => ['score' => 0, 'label' => 'Niet geanalyseerd', 'toelichting' => ''],
            'klimaat' => ['score' => 0, 'label' => 'Niet geanalyseerd', 'toelichting' => ''],
            'immigratie' => ['score' => 0, 'label' => 'Niet geanalyseerd', 'toelichting' => '']
        ],
        'overall' => [
            'orientatie' => 'Onbekend',
            'primaire_as' => 'economisch',
            'confidence' => 50,
            'samenvatting' => 'Geen samenvatting beschikbaar.'
        ],
        'retoriek' => [
            'toon' => 'neutraal',
            'framing' => 'neutraal',
            'stijl' => 'journalistiek',
            'objectiviteit' => 50,
            'toelichting' => ''
        ],
        'partij_match' => [
            'zou_onderschrijven' => [],
            'zou_afwijzen' => [],
            'best_match' => 'Onbekend',
            'toelichting' => ''
        ],
        'schrijfstijl' => [
            'feitelijk_vs_mening' => 50,
            'emotionele_lading' => 50,
            'bronverwijzingen' => 'beperkt',
            'argumentatie_balans' => 'evenwichtig',
            'toelichting' => ''
        ],
        'doelgroep' => [
            'primair' => 'Algemeen publiek',
            'demografisch' => '',
            'politiek_profiel' => ''
        ],
        'kernpunten' => []
    ];
    
    // Merge met defaults voor ontbrekende velden
    $result = [];
    
    foreach ($defaults as $key => $defaultValue) {
        if (isset($analysis[$key])) {
            if (is_array($defaultValue) && is_array($analysis[$key])) {
                // Recursief mergen voor geneste arrays
                $result[$key] = array_merge($defaultValue, $analysis[$key]);
                
                // Voor spectrum, controleer elk sub-item
                if ($key === 'spectrum') {
                    foreach ($defaultValue as $subKey => $subDefault) {
                        if (isset($analysis[$key][$subKey]) && is_array($analysis[$key][$subKey])) {
                            $result[$key][$subKey] = array_merge($subDefault, $analysis[$key][$subKey]);
                        } else {
                            $result[$key][$subKey] = $subDefault;
                        }
                    }
                }
            } else {
                $result[$key] = $analysis[$key];
            }
        } else {
            $result[$key] = $defaultValue;
        }
    }
    
    // Zorg ervoor dat scores numeriek zijn en binnen bereik
    foreach ($result['spectrum'] as $key => $data) {
        if (isset($data['score'])) {
            $result['spectrum'][$key]['score'] = max(-100, min(100, intval($data['score'])));
        }
    }
    
    // Zorg ervoor dat percentages numeriek zijn
    if (isset($result['overall']['confidence'])) {
        $result['overall']['confidence'] = max(0, min(100, intval($result['overall']['confidence'])));
    }
    if (isset($result['retoriek']['objectiviteit'])) {
        $result['retoriek']['objectiviteit'] = max(0, min(100, intval($result['retoriek']['objectiviteit'])));
    }
    if (isset($result['schrijfstijl']['feitelijk_vs_mening'])) {
        $result['schrijfstijl']['feitelijk_vs_mening'] = max(0, min(100, intval($result['schrijfstijl']['feitelijk_vs_mening'])));
    }
    if (isset($result['schrijfstijl']['emotionele_lading'])) {
        $result['schrijfstijl']['emotionele_lading'] = max(0, min(100, intval($result['schrijfstijl']['emotionele_lading'])));
    }
    
    return $result;
}

// Zorg ervoor dat dit alleen via AJAX wordt aangeroepen
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    http_response_code(405);
    exit('Method not allowed');
}

// Set content type naar JSON
header('Content-Type: application/json');

try {
    // Controleer of slug is meegegeven
    if (!isset($_POST['slug']) || empty($_POST['slug'])) {
        throw new Exception('Blog slug is vereist');
    }

    $slug = trim($_POST['slug']);
    
    // Haal blog gegevens direct op (zonder dependency op BlogController)
    $db = new Database();
    $db->query("SELECT blogs.*, users.username as author_name 
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                WHERE blogs.slug = :slug");
    $db->bind(':slug', $slug);
    $blog = $db->single();
    
    if (!$blog) {
        throw new Exception('Blog niet gevonden');
    }
    
    // Initialiseer ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    // Analyseer de bias met volledige content (API doet nu automatisch samenvatting indien nodig)
    $result = $chatGPT->analyzePoliticalBias($blog->title, $blog->content);
    
    if ($result['success']) {
        // Parse JSON response
        $analysis = json_decode($result['content'], true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            // Valideer de nieuwe uitgebreide structuur
            $validAnalysis = validateBiasAnalysis($analysis);
            
            echo json_encode([
                'success' => true,
                'analysis' => $validAnalysis
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // Log de raw response voor debugging
            error_log('Bias Analysis JSON parse error. Raw response: ' . substr($result['content'], 0, 500));
            throw new Exception('Fout bij het verwerken van de AI analyse. Probeer het opnieuw.');
        }
    } else {
        throw new Exception($result['error'] ?? 'Onbekende fout bij AI analyse');
    }
    
} catch (Exception $e) {
    // Error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 