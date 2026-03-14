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
     * Haal alle nieuwsartikelen op met paginering
     */
    public function getAllNewsPaginated($page = 1, $perPage = 9) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM news_articles ORDER BY published_at DESC LIMIT ? OFFSET ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, intval($perPage));
            $this->db->bind(2, intval($offset));
            $this->db->execute();
            $result = $this->db->resultSet();
            return $this->formatNewsResultsFromObject($result);
        } catch (Exception $e) {
            error_log("NewsModel::getAllNewsPaginated fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal nieuwsartikelen op per oriëntatie met paginering
     */
    public function getNewsByOrientationPaginated($orientation, $page = 1, $perPage = 9) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM news_articles WHERE orientation = ? ORDER BY published_at DESC LIMIT ? OFFSET ?";
        
        try {
            $this->db->query($sql);
            $this->db->bind(1, $orientation);
            $this->db->bind(2, intval($perPage));
            $this->db->bind(3, intval($offset));
            $this->db->execute();
            $result = $this->db->resultSet();
            return $this->formatNewsResultsFromObject($result);
        } catch (Exception $e) {
            error_log("NewsModel::getNewsByOrientationPaginated fout: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Haal gefilterd nieuws op met paginering
     */
    public function getFilteredNewsPaginated($filter = 'alle', $page = 1, $perPage = 9) {
        switch($filter) {
            case 'progressief':
                return $this->getNewsByOrientationPaginated('links', $page, $perPage);
            case 'conservatief':
                return $this->getNewsByOrientationPaginated('rechts', $page, $perPage);
            default:
                return $this->getAllNewsPaginated($page, $perPage);
        }
    }
    
    /**
     * Tel het totale aantal artikelen
     */
    public function getTotalCount($filter = 'alle') {
        try {
            switch($filter) {
                case 'progressief':
                    $result = $this->db->directQuery("SELECT COUNT(*) as total FROM news_articles WHERE orientation = 'links'");
                    break;
                case 'conservatief':
                    $result = $this->db->directQuery("SELECT COUNT(*) as total FROM news_articles WHERE orientation = 'rechts'");
                    break;
                default:
                    $result = $this->db->directQuery("SELECT COUNT(*) as total FROM news_articles");
            }
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) {
            error_log("NewsModel::getTotalCount fout: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Voeg een nieuw artikel toe
     */
    public function addNewsArticle($title, $description, $url, $source, $bias, $orientation, $publishedAt) {
        $normalizedUrl = $this->normalizeUrl($url);

        if (!$this->isValidHttpUrl($normalizedUrl)) {
            error_log("NewsModel::addNewsArticle overgeslagen, ongeldige URL: " . $url);
            return false;
        }

        try {
            // Idempotent: update bestaand artikel op dezelfde URL i.p.v. duplicaat inserten
            $this->db->query("SELECT id, published_at FROM news_articles WHERE url = ? LIMIT 1");
            $this->db->bind(1, $normalizedUrl);
            $this->db->execute();
            $existing = $this->db->single();

            if ($existing) {
                $existingPublished = strtotime($existing->published_at ?? '1970-01-01 00:00:00');
                $incomingPublished = strtotime($publishedAt ?: '1970-01-01 00:00:00');

                // Alleen upgraden als inkomende data recenter is of missende velden vult
                if ($incomingPublished >= $existingPublished || empty($existing->published_at)) {
                    $this->db->query("UPDATE news_articles SET title = ?, description = ?, source = ?, bias = ?, orientation = ?, published_at = ? WHERE id = ?");
                    $this->db->bind(1, $title);
                    $this->db->bind(2, $description);
                    $this->db->bind(3, $source);
                    $this->db->bind(4, $bias);
                    $this->db->bind(5, $orientation);
                    $this->db->bind(6, $publishedAt);
                    $this->db->bind(7, intval($existing->id));
                    $this->db->execute();
                }

                return true;
            }

            $sql = "INSERT INTO news_articles (title, description, url, source, bias, orientation, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql);
            $this->db->bind(1, $title);
            $this->db->bind(2, $description);
            $this->db->bind(3, $normalizedUrl);
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
     * Haal recente artikelen op per perspectief op voor homepage
     */
    public function getHomepageNews($perOrientation = 3, $maxAgeHours = 48) {
        $result = [
            'links' => [],
            'rechts' => []
        ];

        foreach (['links', 'rechts'] as $orientation) {
            $buckets = [
                $this->getRecentNewsByOrientation($orientation, $perOrientation, $maxAgeHours),
                $this->getRecentNewsByOrientation($orientation, $perOrientation, 72),
                $this->getRecentNewsByOrientation($orientation, $perOrientation, 168),
                $this->getNewsByOrientation($orientation, $perOrientation)
            ];

            $merged = [];
            foreach ($buckets as $bucket) {
                $merged = array_merge($merged, $bucket);
            }

            $unique = [];
            foreach ($merged as $article) {
                $url = $this->normalizeUrl($article['url'] ?? '');
                if (!$this->isValidHttpUrl($url)) {
                    continue;
                }

                $title = trim((string)($article['title'] ?? ''));
                if ($title === '') {
                    continue;
                }

                $key = md5(mb_strtolower($title) . '|' . $url);
                if (isset($unique[$key])) {
                    continue;
                }

                $article['url'] = $url;
                $unique[$key] = $article;
            }

            $result[$orientation] = array_slice(array_values($unique), 0, $perOrientation);
        }

        return $result;
    }

    /**
     * Recente artikelen per oriëntatie met tijdsfilter
     */
    public function getRecentNewsByOrientation($orientation, $limit = 3, $maxAgeHours = 48) {
        $sql = "SELECT * FROM news_articles WHERE orientation = ? AND published_at >= DATE_SUB(NOW(), INTERVAL ? HOUR) ORDER BY published_at DESC LIMIT ?";

        try {
            $this->db->query($sql);
            $this->db->bind(1, $orientation);
            $this->db->bind(2, intval($maxAgeHours));
            $this->db->bind(3, intval($limit));
            $this->db->execute();
            return $this->formatNewsResultsFromObject($this->db->resultSet());
        } catch (Exception $e) {
            error_log("NewsModel::getRecentNewsByOrientation fout: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Geef toegang tot database object voor NewsScraper
     */
    public function getDatabase() {
        return $this->db;
    }

    private function isValidHttpUrl($url) {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        return in_array(strtolower((string)$scheme), ['http', 'https'], true);
    }

    private function normalizeUrl($url) {
        if (!is_string($url)) {
            return '';
        }

        $url = trim($url);
        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['host']) || empty($parts['scheme'])) {
            return $url;
        }

        $scheme = strtolower($parts['scheme']);
        $host = strtolower($parts['host']);
        $path = $parts['path'] ?? '/';

        // Strip tracking query params maar behoud inhoudelijke params
        $query = '';
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $queryParams);
            foreach (array_keys($queryParams) as $key) {
                if (stripos($key, 'utm_') === 0 || in_array(strtolower($key), ['fbclid', 'gclid'], true)) {
                    unset($queryParams[$key]);
                }
            }
            if (!empty($queryParams)) {
                $query = '?' . http_build_query($queryParams);
            }
        }

        return $scheme . '://' . $host . $path . $query;
    }
}
?> 