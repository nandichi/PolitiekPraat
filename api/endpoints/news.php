<?php

class NewsAPI {
    private $db;
    private $newsModel;
    
    public function __construct() {
        $this->db = new Database();
        $this->newsModel = new NewsModel($this->db);
    }
    
    public function handle($method, $segments) {
        switch ($method) {
            case 'GET':
                if (isset($segments[1])) {
                    // GET /api/news/{action}
                    $this->handleNewsAction($segments[1]);
                } else {
                    // GET /api/news
                    $this->getAllNews();
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }
    
    private function getAllNews() {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 20;
            $filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'alle';
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            // Bepaal welke nieuws data op te halen
            switch ($filter) {
                case 'progressief':
                case 'links':
                    $news = $this->newsModel->getNewsByOrientationPaginated('links', $page, $limit);
                    break;
                case 'conservatief':
                case 'rechts':
                    $news = $this->newsModel->getNewsByOrientationPaginated('rechts', $page, $limit);
                    break;
                case 'midden':
                    $news = $this->newsModel->getNewsByOrientationPaginated('midden', $page, $limit);
                    break;
                default:
                    $news = $this->newsModel->getAllNewsPaginated($page, $limit);
                    break;
            }
            
            // Filter op search term indien opgegeven
            if (!empty($search)) {
                $news = array_filter($news, function($article) use ($search) {
                    return stripos($article['title'], $search) !== false || 
                           stripos($article['description'], $search) !== false;
                });
                $news = array_values($news); // Re-index array
            }
            
            // Haal total count voor paginering
            $totalCount = $this->getTotalNewsCount($filter, $search);
            
            // Format voor API
            $formattedNews = array_map([$this, 'formatNewsForAPI'], $news);
            
            $this->sendResponse([
                'news' => $formattedNews,
                'pagination' => [
                    'current_page' => $page,
                    'limit' => $limit,
                    'total' => $totalCount,
                    'total_pages' => ceil($totalCount / $limit),
                    'has_next' => ($page * $limit) < $totalCount,
                    'has_prev' => $page > 1
                ],
                'filter' => $filter
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function handleNewsAction($action) {
        switch ($action) {
            case 'stats':
                $this->getNewsStats();
                break;
            case 'sources':
                $this->getNewsSources();
                break;
            case 'recent':
                $this->getRecentNews();
                break;
            default:
                $this->sendError('Onbekende nieuws actie', 400);
        }
    }
    
    private function getNewsStats() {
        try {
            $stats = $this->newsModel->getNewsStatistics();
            
            $this->sendResponse([
                'statistics' => [
                    'total_articles' => (int)$stats['total_count'],
                    'articles_today' => (int)$stats['today_count'],
                    'articles_this_week' => (int)$stats['week_count'],
                    'by_orientation' => [
                        'links' => (int)$stats['links_count'],
                        'rechts' => (int)$stats['rechts_count'],
                        'midden' => (int)$stats['midden_count']
                    ],
                    'last_updated' => $stats['last_article_date'] ?: 'Nog geen artikelen'
                ]
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Fout bij ophalen statistieken: ' . $e->getMessage(), 500);
        }
    }
    
    private function getNewsSources() {
        $sources = [
            [
                'name' => 'De Volkskrant',
                'orientation' => 'links',
                'bias' => 'Progressief',
                'description' => 'Nederlandse kwaliteitskrant met linkse signatuur'
            ],
            [
                'name' => 'NRC',
                'orientation' => 'links',
                'bias' => 'Liberaal',
                'description' => 'Liberale kwaliteitskrant'
            ],
            [
                'name' => 'Trouw',
                'orientation' => 'links',
                'bias' => 'Progressief',
                'description' => 'Protestants-christelijke krant met progressieve inslag'
            ],
            [
                'name' => 'Telegraaf',
                'orientation' => 'rechts',
                'bias' => 'Conservatief',
                'description' => 'Populaire krant met rechtse signatuur'
            ],
            [
                'name' => 'AD',
                'orientation' => 'midden',
                'bias' => 'Conservatief',
                'description' => 'Algemeen dagblad voor breed publiek'
            ],
            [
                'name' => 'NU.nl',
                'orientation' => 'rechts',
                'bias' => 'Conservatief',
                'description' => 'Nieuwssite met brede berichtgeving'
            ]
        ];
        
        $this->sendResponse([
            'sources' => $sources,
            'total_sources' => count($sources)
        ]);
    }
    
    private function getRecentNews() {
        try {
            $limit = isset($_GET['limit']) ? max(1, min(20, intval($_GET['limit']))) : 10;
            $hours = isset($_GET['hours']) ? max(1, min(168, intval($_GET['hours']))) : 24; // Max 1 week
            
            // Haal nieuws van de laatste X uren
            $this->db->query("
                SELECT * FROM news_articles 
                WHERE published_at >= DATE_SUB(NOW(), INTERVAL :hours HOUR)
                ORDER BY published_at DESC 
                LIMIT :limit
            ");
            $this->db->bind(':hours', $hours, PDO::PARAM_INT);
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            
            $recentNews = $this->db->resultSet();
            
            // Format voor API
            $formattedNews = [];
            foreach ($recentNews as $article) {
                $formattedNews[] = $this->formatNewsForAPI((array)$article);
            }
            
            $this->sendResponse([
                'recent_news' => $formattedNews,
                'timeframe_hours' => $hours,
                'count' => count($formattedNews)
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Fout bij ophalen recent nieuws: ' . $e->getMessage(), 500);
        }
    }
    
    private function getTotalNewsCount($filter = 'alle', $search = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM news_articles";
            $whereConditions = [];
            $bindings = [];
            
            // Filter op oriëntatie
            if ($filter !== 'alle') {
                switch ($filter) {
                    case 'progressief':
                    case 'links':
                        $whereConditions[] = "orientation = 'links'";
                        break;
                    case 'conservatief':
                    case 'rechts':
                        $whereConditions[] = "orientation = 'rechts'";
                        break;
                    case 'midden':
                        $whereConditions[] = "orientation = 'midden'";
                        break;
                }
            }
            
            // Filter op zoekterm
            if (!empty($search)) {
                $whereConditions[] = "(title LIKE :search OR description LIKE :search)";
                $bindings[':search'] = "%$search%";
            }
            
            if (!empty($whereConditions)) {
                $sql .= " WHERE " . implode(' AND ', $whereConditions);
            }
            
            $this->db->query($sql);
            foreach ($bindings as $param => $value) {
                $this->db->bind($param, $value);
            }
            
            $result = $this->db->single();
            return (int)$result->total;
            
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function formatNewsForAPI($article) {
        return [
            'id' => (int)($article['id'] ?? 0),
            'title' => $article['title'] ?? '',
            'description' => $article['description'] ?? '',
            'url' => $article['url'] ?? '',
            'source' => [
                'name' => $article['source'] ?? '',
                'orientation' => $article['orientation'] ?? 'midden',
                'bias' => $article['bias'] ?? 'Neutraal'
            ],
            'published_at' => $article['published_at'] ?? '',
            'created_at' => $article['created_at'] ?? '',
            'reading_time' => $this->calculateReadingTime($article['description'] ?? ''),
            'orientation_color' => $this->getOrientationColor($article['orientation'] ?? 'midden')
        ];
    }
    
    private function calculateReadingTime($text) {
        $wordCount = str_word_count(strip_tags($text));
        $readingTime = ceil($wordCount / 200); // 200 woorden per minuut
        return max(1, $readingTime);
    }
    
    private function getOrientationColor($orientation) {
        switch ($orientation) {
            case 'links':
                return '#EF4444'; // Rood
            case 'rechts':
                return '#3B82F6'; // Blauw
            case 'midden':
                return '#10B981'; // Groen
            default:
                return '#6B7280'; // Grijs
        }
    }
    
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    private function sendError($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
} 