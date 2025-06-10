<?php
class NewsModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Haal alle nieuwsartikelen op
     */
    public function getAllNews($limit = null) {
        $sql = "SELECT * FROM news_articles ORDER BY published_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        try {
            $result = $this->db->directQuery($sql);
            return $this->formatNewsResults($result);
        } catch (Exception $e) {
            error_log("NewsModel::getAllNews fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal nieuwsartikelen op per oriëntatie
     */
    public function getNewsByOrientation($orientation, $limit = null) {
        $sql = "SELECT * FROM news_articles WHERE orientation = ? ORDER BY published_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $orientation);
            $this->db->execute();
            $result = $this->db->resultSet();
            return $this->formatNewsResultsFromObject($result);
        } catch (Exception $e) {
            error_log("NewsModel::getNewsByOrientation fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal gefilterd nieuws op
     */
    public function getFilteredNews($filter = 'alle', $limit = null) {
        switch($filter) {
            case 'progressief':
                return $this->getNewsByOrientation('links', $limit);
            case 'conservatief':
                return $this->getNewsByOrientation('rechts', $limit);
            default:
                return $this->getAllNews($limit);
        }
    }
    
    /**
     * Voeg een nieuw artikel toe
     */
    public function addNewsArticle($title, $description, $url, $source, $bias, $orientation, $publishedAt) {
        $sql = "INSERT INTO news_articles (title, description, url, source, bias, orientation, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $title);
            $this->db->bind(2, $description);
            $this->db->bind(3, $url);
            $this->db->bind(4, $source);
            $this->db->bind(5, $bias);
            $this->db->bind(6, $orientation);
            $this->db->bind(7, $publishedAt);
            $this->db->execute();
            return true;
        } catch (Exception $e) {
            error_log("NewsModel::addNewsArticle fout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Krijg statistieken over de nieuwsartikelen
     */
    public function getNewsStats() {
        try {
            // Totaal aantal artikelen
            $result = $this->db->directQuery("SELECT COUNT(*) as total FROM news_articles");
            $total = $result->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Progressieve artikelen
            $result = $this->db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE orientation = 'links'");
            $progressive = $result->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Conservatieve artikelen
            $result = $this->db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE orientation = 'rechts'");
            $conservative = $result->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Bronnen telling
            $result = $this->db->directQuery("SELECT COUNT(DISTINCT source) as count FROM news_articles");
            $sources = $result->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Nieuwste en oudste artikel
            $result = $this->db->directQuery("SELECT MAX(published_at) as newest, MIN(published_at) as oldest FROM news_articles");
            $dates = $result->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_articles' => $total,
                'progressive_count' => $progressive,
                'conservative_count' => $conservative,
                'source_count' => $sources,
                'newest_article' => $dates['newest'] ?: date('Y-m-d H:i:s'),
                'oldest_article' => $dates['oldest'] ?: date('Y-m-d H:i:s', strtotime('-1 hour'))
            ];
        } catch (Exception $e) {
            error_log("NewsModel::getNewsStats fout: " . $e->getMessage());
            return [
                'total_articles' => 0,
                'progressive_count' => 0,
                'conservative_count' => 0,
                'source_count' => 0,
                'newest_article' => date('Y-m-d H:i:s'),
                'oldest_article' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ];
        }
    }
    
    /**
     * Format results van directQuery (PDO)
     */
    private function formatNewsResults($result) {
        $articles = [];
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $articles[] = [
                    'orientation' => $row['orientation'],
                    'source' => $row['source'],
                    'bias' => $row['bias'],
                    'publishedAt' => $row['published_at'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'url' => $row['url']
                ];
            }
        }
        return $articles;
    }
    
    /**
     * Format results van prepared query (Objects)
     */
    private function formatNewsResultsFromObject($result) {
        $articles = [];
        if ($result) {
            foreach ($result as $row) {
                $articles[] = [
                    'orientation' => $row->orientation,
                    'source' => $row->source,
                    'bias' => $row->bias,
                    'publishedAt' => $row->published_at,
                    'title' => $row->title,
                    'description' => $row->description,
                    'url' => $row->url
                ];
            }
        }
        return $articles;
    }
    
    /**
     * Geef toegang tot database object voor NewsScraper
     */
    public function getDatabase() {
        return $this->db;
    }
}
?> 