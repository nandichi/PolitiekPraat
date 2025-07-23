<?php
// PDO is in the global namespace so no import is needed
class BlogController {
    private $db;
    private $parsedown;

    public function __construct() {
        $this->db = new Database();
        $this->parsedown = new Parsedown();
        // Configureer Parsedown voor optimale Markdown verwerking
        $this->parsedown->setSafeMode(false);
        $this->parsedown->setBreaksEnabled(true);
    }

    // Functie om leestijd te berekenen
    private function calculateReadingTime($content) {
        // Verwijder HTML tags
        $content = strip_tags($content);
        
        // Tel woorden (gescheiden door spaties)
        $wordCount = str_word_count($content);
        
        // Gemiddelde leessnelheid: 200 woorden per minuut
        $readingTime = ceil($wordCount / 200);
        
        // Minimum leestijd van 1 minuut
        return max(1, $readingTime);
    }

    public function getAll($limit = null) {
        $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo 
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                ORDER BY published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }
        
        $this->db->query($sql);
        $blogs = $this->db->resultSet();
        
        // Parse Markdown naar HTML voor elk blog
        foreach ($blogs as $blog) {
            // Bewaar de originele content voor de samenvatting
            $originalContent = $blog->content;
            // Converteer Markdown naar HTML voor de volledige content
            $blog->content = $this->parsedown->text($blog->content);
            // Maak een samenvatting van de originele content
            $blog->summary = substr(strip_tags($this->parsedown->text($originalContent)), 0, 200) . '...';
            // Bereken leestijd
            $blog->reading_time = $this->calculateReadingTime($originalContent);
        }
        
        return $blogs;
    }

    public function getBySlug($slug) {
        $this->db->query("SELECT blogs.*, users.username as author_name, users.profile_photo 
                         FROM blogs 
                         JOIN users ON blogs.author_id = users.id 
                         WHERE blogs.slug = :slug");
        $this->db->bind(':slug', $slug);
        $blog = $this->db->single();
        
        if ($blog) {
            // Converteer Markdown naar HTML
            $blog->content = $this->parsedown->text($blog->content);
            // Bereken leestijd
            $blog->reading_time = $this->calculateReadingTime($blog->content);
            
            // Haal poll op als deze bestaat
            $blog->poll = $this->getPollByBlogId($blog->id);
        }
        
        return $blog;
    }

    public function create($data) {
        // Use the provided summary or generate a new one
        $content = $data['content'];
        $summary = isset($data['summary']) ? $data['summary'] : substr(strip_tags($this->parsedown->text($content)), 0, 200) . '...';
        
        $this->db->query("INSERT INTO blogs (title, slug, content, summary, image_path, video_path, video_url, audio_path, audio_url, soundcloud_url, author_id, published_at) 
                         VALUES (:title, :slug, :content, :summary, :image_path, :video_path, :video_url, :audio_path, :audio_url, :soundcloud_url, :author_id, NOW())");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $this->generateSlug($data['title']));
        $this->db->bind(':content', $content);
        $this->db->bind(':summary', $summary);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':video_path', $data['video_path'] ?? null);
        $this->db->bind(':video_url', $data['video_url'] ?? null);
        $this->db->bind(':audio_path', $data['audio_path'] ?? null);
        $this->db->bind(':audio_url', $data['audio_url'] ?? null);
        $this->db->bind(':soundcloud_url', $data['soundcloud_url'] ?? null);
        $this->db->bind(':author_id', $_SESSION['user_id']);

        if ($this->db->execute()) {
            $blogId = $this->db->lastInsertId();
            
            // Voeg poll toe als deze is opgegeven
            if (isset($data['enable_poll']) && $data['enable_poll'] && 
                !empty($data['poll_question']) && !empty($data['poll_option_a']) && !empty($data['poll_option_b'])) {
                $this->createPoll($blogId, $data['poll_question'], $data['poll_option_a'], $data['poll_option_b']);
            }
            
            return $blogId;
        }
        
        return false;
    }

    public function update($data) {
        // Sla de originele Markdown content op
        $content = $data['content'];
        // Genereer een samenvatting van de content
        $summary = substr(strip_tags($this->parsedown->text($content)), 0, 200) . '...';
        
        $this->db->query("UPDATE blogs 
                         SET title = :title, content = :content, 
                             summary = :summary, image_path = :image_path,
                             video_path = :video_path, video_url = :video_url, 
                             audio_path = :audio_path, audio_url = :audio_url, soundcloud_url = :soundcloud_url
                         WHERE id = :id AND author_id = :author_id");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $content);
        $this->db->bind(':summary', $summary);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':video_path', $data['video_path'] ?? null);
        $this->db->bind(':video_url', $data['video_url'] ?? null);
        $this->db->bind(':audio_path', $data['audio_path'] ?? null);
        $this->db->bind(':audio_url', $data['audio_url'] ?? null);
        $this->db->bind(':soundcloud_url', $data['soundcloud_url'] ?? null);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':author_id', $_SESSION['user_id']);

        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM blogs WHERE id = :id AND author_id = :author_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':author_id', $_SESSION['user_id']);
        return $this->db->execute();
    }

    public function updateLikes($slug, $action) {
        // Haal het huidige aantal likes op
        $this->db->query("SELECT likes FROM blogs WHERE slug = :slug");
        $this->db->bind(':slug', $slug);
        $blog = $this->db->single();
        
        if (!$blog) {
            return false;
        }
        
        // Update het aantal likes
        $newLikes = $action === 'like' ? $blog->likes + 1 : $blog->likes - 1;
        $newLikes = max(0, $newLikes); // Voorkom negatieve likes
        
        $this->db->query("UPDATE blogs SET likes = :likes WHERE slug = :slug");
        $this->db->bind(':likes', $newLikes);
        $this->db->bind(':slug', $slug);
        
        if ($this->db->execute()) {
            return $newLikes;
        }
        
        return false;
    }

    public function updateLikesDirectly($id, $likes) {
        $likes = max(0, (int)$likes); // Voorkom negatieve likes
        
        $this->db->query("UPDATE blogs SET likes = :likes WHERE id = :id AND author_id = :author_id");
        $this->db->bind(':likes', $likes);
        $this->db->bind(':id', $id);
        $this->db->bind(':author_id', $_SESSION['user_id']);
        
        return $this->db->execute();
    }

    private function generateSlug($title) {
        // Convert to lowercase
        $slug = strtolower($title);
        // Replace non-alphanumeric characters with a dash
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        // Remove multiple consecutive dashes
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove leading and trailing dashes
        $slug = trim($slug, '-');
        return $slug;
    }

    public function getAllByUserId($userId, $page = 1, $perPage = 5) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo 
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                WHERE blogs.author_id = :author_id
                ORDER BY published_at DESC
                LIMIT :offset, :per_page";
        
        $this->db->query($sql);
        $this->db->bind(':author_id', $userId);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        $this->db->bind(':per_page', $perPage, PDO::PARAM_INT);
        $blogs = $this->db->resultSet();
        
        // Parse Markdown naar HTML voor elk blog
        foreach ($blogs as $blog) {
            // Bewaar de originele content voor de samenvatting
            $originalContent = $blog->content;
            // Converteer Markdown naar HTML voor de volledige content
            $blog->content = $this->parsedown->text($blog->content);
            // Maak een samenvatting van de originele content
            $blog->summary = substr(strip_tags($this->parsedown->text($originalContent)), 0, 200) . '...';
            // Bereken leestijd
            $blog->reading_time = $this->calculateReadingTime($originalContent);
        }
        
        return $blogs;
    }
    
    public function getTotalBlogCountByUserId($userId) {
        $this->db->query("SELECT COUNT(*) as total FROM blogs WHERE author_id = :author_id");
        $this->db->bind(':author_id', $userId);
        $result = $this->db->single();
        return $result->total;
    }
    
    public function getById($id) {
        $this->db->query("SELECT blogs.*, users.username as author_name, users.profile_photo 
                         FROM blogs 
                         JOIN users ON blogs.author_id = users.id 
                         WHERE blogs.id = :id");
        $this->db->bind(':id', $id);
        $blog = $this->db->single();
        
        // Don't convert Markdown to HTML here for editing purposes
        // We need the original Markdown content
        
        return $blog;
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

    // Poll functionaliteit
    public function createPoll($blogId, $question, $optionA, $optionB) {
        $this->db->query("INSERT INTO blog_polls (blog_id, question, option_a, option_b) 
                         VALUES (:blog_id, :question, :option_a, :option_b)");
        
        $this->db->bind(':blog_id', $blogId);
        $this->db->bind(':question', trim($question));
        $this->db->bind(':option_a', trim($optionA));
        $this->db->bind(':option_b', trim($optionB));
        
        return $this->db->execute();
    }

    public function getPollByBlogId($blogId) {
        $this->db->query("SELECT * FROM blog_polls WHERE blog_id = :blog_id");
        $this->db->bind(':blog_id', $blogId);
        $poll = $this->db->single();
        
        if ($poll) {
            // Voeg extra informatie toe over de stemmen
            $poll->total_votes = $poll->option_a_votes + $poll->option_b_votes;
            
            // Bereken percentages
            if ($poll->total_votes > 0) {
                $poll->option_a_percentage = round(($poll->option_a_votes / $poll->total_votes) * 100, 1);
                $poll->option_b_percentage = round(($poll->option_b_votes / $poll->total_votes) * 100, 1);
            } else {
                $poll->option_a_percentage = 0;
                $poll->option_b_percentage = 0;
            }
            
            // Check of huidige gebruiker al heeft gestemd
            $poll->user_has_voted = $this->checkUserVoted($poll->id);
            $poll->user_choice = $this->getUserChoice($poll->id);
        }
        
        return $poll;
    }

    public function checkUserVoted($pollId) {
        if (isset($_SESSION['user_id'])) {
            // Ingelogde gebruiker
            $this->db->query("SELECT id FROM blog_poll_votes WHERE poll_id = :poll_id AND user_id = :user_id");
            $this->db->bind(':poll_id', $pollId);
            $this->db->bind(':user_id', $_SESSION['user_id']);
        } else {
            // Anonieme gebruiker - check via IP en fingerprint
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $fingerprint = $this->generateAnonymousFingerprint();
            
            $this->db->query("SELECT id FROM blog_poll_votes WHERE poll_id = :poll_id AND anonymous_voter_ip = :ip AND anonymous_voter_fingerprint = :fingerprint");
            $this->db->bind(':poll_id', $pollId);
            $this->db->bind(':ip', $ip);
            $this->db->bind(':fingerprint', $fingerprint);
        }
        
        return $this->db->single() !== false;
    }

    public function getUserChoice($pollId) {
        if (isset($_SESSION['user_id'])) {
            // Ingelogde gebruiker
            $this->db->query("SELECT chosen_option FROM blog_poll_votes WHERE poll_id = :poll_id AND user_id = :user_id");
            $this->db->bind(':poll_id', $pollId);
            $this->db->bind(':user_id', $_SESSION['user_id']);
        } else {
            // Anonieme gebruiker
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $fingerprint = $this->generateAnonymousFingerprint();
            
            $this->db->query("SELECT chosen_option FROM blog_poll_votes WHERE poll_id = :poll_id AND anonymous_voter_ip = :ip AND anonymous_voter_fingerprint = :fingerprint");
            $this->db->bind(':poll_id', $pollId);
            $this->db->bind(':ip', $ip);
            $this->db->bind(':fingerprint', $fingerprint);
        }
        
        $result = $this->db->single();
        return $result ? $result->chosen_option : null;
    }

    public function votePoll($pollId, $choice) {
        // Controleer of gebruiker al heeft gestemd
        if ($this->checkUserVoted($pollId)) {
            return ['success' => false, 'message' => 'Je hebt al gestemd op deze poll'];
        }

        // Valideer keuze
        if (!in_array($choice, ['A', 'B'])) {
            return ['success' => false, 'message' => 'Ongeldige keuze'];
        }

        try {
            // Start transactie
            $this->db->beginTransaction();

            // Voeg stem toe
            if (isset($_SESSION['user_id'])) {
                // Ingelogde gebruiker
                $this->db->query("INSERT INTO blog_poll_votes (poll_id, user_id, chosen_option) 
                                 VALUES (:poll_id, :user_id, :choice)");
                $this->db->bind(':poll_id', $pollId);
                $this->db->bind(':user_id', $_SESSION['user_id']);
                $this->db->bind(':choice', $choice);
            } else {
                // Anonieme gebruiker
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                $fingerprint = $this->generateAnonymousFingerprint();
                
                $this->db->query("INSERT INTO blog_poll_votes (poll_id, anonymous_voter_ip, anonymous_voter_fingerprint, chosen_option) 
                                 VALUES (:poll_id, :ip, :fingerprint, :choice)");
                $this->db->bind(':poll_id', $pollId);
                $this->db->bind(':ip', $ip);
                $this->db->bind(':fingerprint', $fingerprint);
                $this->db->bind(':choice', $choice);
            }

            if (!$this->db->execute()) {
                throw new Exception('Kon stem niet opslaan');
            }

            // Update poll tellingen
            $column = $choice === 'A' ? 'option_a_votes' : 'option_b_votes';
            $this->db->query("UPDATE blog_polls SET {$column} = {$column} + 1, total_votes = total_votes + 1 WHERE id = :poll_id");
            $this->db->bind(':poll_id', $pollId);
            
            if (!$this->db->execute()) {
                throw new Exception('Kon poll tellingen niet updaten');
            }

            // Commit transactie
            $this->db->commit();

            // Haal bijgewerkte poll gegevens op
            $this->db->query("SELECT * FROM blog_polls WHERE id = :poll_id");
            $this->db->bind(':poll_id', $pollId);
            $poll = $this->db->single();

            if ($poll) {
                $poll->total_votes = $poll->option_a_votes + $poll->option_b_votes;
                $poll->option_a_percentage = $poll->total_votes > 0 ? round(($poll->option_a_votes / $poll->total_votes) * 100, 1) : 0;
                $poll->option_b_percentage = $poll->total_votes > 0 ? round(($poll->option_b_votes / $poll->total_votes) * 100, 1) : 0;
            }

            return [
                'success' => true, 
                'message' => 'Stem succesvol toegevoegd',
                'poll' => $poll
            ];

        } catch (Exception $e) {
            // Rollback bij fout
            $this->db->rollback();
            return ['success' => false, 'message' => 'Er is een fout opgetreden: ' . $e->getMessage()];
        }
    }

    private function generateAnonymousFingerprint() {
        // Simpele fingerprint gebaseerd op User-Agent en Accept headers
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $acceptEnc = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        
        return hash('sha256', $userAgent . $acceptLang . $acceptEnc);
    }

    public function updatePoll($pollId, $question, $optionA, $optionB) {
        try {
            $this->db->query("UPDATE blog_polls SET 
                             question = :question,
                             option_a = :option_a,
                             option_b = :option_b,
                             updated_at = NOW()
                             WHERE id = :poll_id");
            
            $this->db->bind(':poll_id', $pollId);
            $this->db->bind(':question', trim($question));
            $this->db->bind(':option_a', trim($optionA));
            $this->db->bind(':option_b', trim($optionB));
            
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function deletePoll($pollId) {
        try {
            $this->db->beginTransaction();
            
            // Delete poll votes first (foreign key constraint)
            $this->db->query("DELETE FROM blog_poll_votes WHERE poll_id = :poll_id");
            $this->db->bind(':poll_id', $pollId);
            $this->db->execute();
            
            // Delete poll
            $this->db->query("DELETE FROM blog_polls WHERE id = :poll_id");
            $this->db->bind(':poll_id', $pollId);
            $this->db->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

} 