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

// Zorg ervoor dat dit alleen via AJAX wordt aangeroepen
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    http_response_code(405);
    exit('Method not allowed');
}

// Set content type naar JSON
header('Content-Type: application/json');

/**
 * Verkort blog content voor veilige API calls
 */
function truncateContentForAPI($content, $maxLength = 3000) {
    if (empty($content)) {
        return $content;
    }
    
    // Strip Markdown syntax eerst
    $content = stripMarkdownSyntax($content);
    
    // Als content al kort genoeg is, return direct
    if (mb_strlen($content) <= $maxLength) {
        return $content;
    }
    
    // Verkort tot maxLength, maar eindig op een volledige zin
    $truncated = mb_substr($content, 0, $maxLength);
    
    // Zoek de laatste punt, uitroepteken of vraagteken
    $lastSentenceEnd = max(
        mb_strrpos($truncated, '.'),
        mb_strrpos($truncated, '!'),
        mb_strrpos($truncated, '?')
    );
    
    if ($lastSentenceEnd !== false && $lastSentenceEnd > $maxLength * 0.7) {
        // Als we een goede zin-einde vinden binnen 70% van de lengte, gebruik die
        $truncated = mb_substr($truncated, 0, $lastSentenceEnd + 1);
    } else {
        // Anders, zoek de laatste spatie om midden in een woord te voorkomen
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLength * 0.8) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }
        $truncated .= '...';
    }
    
    return trim($truncated);
}

/**
 * Strip Markdown syntax voor betere content extractie
 */
function stripMarkdownSyntax($text) {
    if (empty($text)) {
        return $text;
    }
    
    // Strip verschillende Markdown elementen
    $text = preg_replace('/^#{1,6}\s+/m', '', $text); // Headers
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Bold
    $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Italic
    $text = preg_replace('/`(.*?)`/', '$1', $text); // Inline code
    $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text); // Links
    $text = preg_replace('/^\s*[-*+]\s+/m', '• ', $text); // Unordered lists
    $text = preg_replace('/^\s*\d+\.\s+/m', '', $text); // Ordered lists
    $text = preg_replace('/^>\s+/m', '', $text); // Blockquotes
    $text = preg_replace('/```.*?```/s', '', $text); // Code blocks
    $text = preg_replace('/^\s*---+\s*$/m', '', $text); // Horizontal rules
    
    // Vervang multiple whitespace met single space
    $text = preg_replace('/\s+/', ' ', $text);
    $text = preg_replace('/\n\s*\n/', "\n\n", $text);
    
    return trim($text);
}

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
    
    // Verkort blog content voor veilige API call
    $truncatedContent = truncateContentForAPI($blog->content, 2500);
    
    // Initialiseer ChatGPT API
    $chatGPT = new ChatGPTAPI();
    
    // Analyseer de bias met verkorte content
    $result = $chatGPT->analyzePoliticalBias($blog->title, $truncatedContent);
    
    if ($result['success']) {
        // Parse JSON response
        $analysis = json_decode($result['content'], true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            echo json_encode([
                'success' => true,
                'analysis' => $analysis
            ], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('Fout bij het verwerken van de AI analyse');
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