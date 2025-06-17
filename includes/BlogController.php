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
            return $this->db->lastInsertId();
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
} 