<?php
require_once 'NewsAPI.php';

class NewsScraper {
    private $newsAPI;
    private $newsModel;
    private $lastScrapedFile;
    
    // Configuratie voor nieuwsbronnen met hun RSS feeds en oriëntatie
    private $newsSources = [
        'De Volkskrant' => [
            'rss_urls' => [
                'https://www.volkskrant.nl/voorpagina/rss.xml',
                'https://www.volkskrant.nl/nieuws-achtergrond/politiek/rss.xml'
            ],
            'orientation' => 'links',
            'bias' => 'Progressief'
        ],
        'NRC' => [
            'rss_urls' => [
                'https://www.nrc.nl/rss/',
                'https://www.nrc.nl/sectie/politiek/rss/'
            ],
            'orientation' => 'links',
            'bias' => 'Liberaal'
        ],
        'Trouw' => [
            'rss_urls' => [
                'https://www.trouw.nl/politiek/rss.xml',
                'https://www.trouw.nl/rss.xml'
            ],
            'orientation' => 'links',
            'bias' => 'Progressief'
        ],
        'Telegraaf' => [
            'rss_urls' => [
                'https://www.telegraaf.nl/rss',
                'https://www.telegraaf.nl/nieuws/politiek/rss'
            ],
            'orientation' => 'rechts',
            'bias' => 'Conservatief'
        ],
        'AD' => [
            'rss_urls' => [
                'https://www.ad.nl/politiek/rss.xml',
                'https://www.ad.nl/rss.xml'
            ],
            'orientation' => 'rechts',
            'bias' => 'Centrum-rechts'
        ],
        'NU.nl' => [
            'rss_urls' => [
                'https://www.nu.nl/rss/Politiek',
                'https://www.nu.nl/rss'
            ],
            'orientation' => 'rechts',
            'bias' => 'Conservatief'
        ]
    ];
    
    public function __construct($newsModel) {
        $this->newsAPI = new NewsAPI();
        $this->newsModel = $newsModel;
        $this->lastScrapedFile = __DIR__ . '/../cache/last_scraped.json';
        
        // Maak cache directory aan als deze niet bestaat
        if (!file_exists(dirname($this->lastScrapedFile))) {
            mkdir(dirname($this->lastScrapedFile), 0755, true);
        }
    }
    
    /**
     * Hoofdfunctie om alle nieuwsbronnen te scrapen
     */
    public function scrapeAllSources() {
        $scrapedCount = 0;
        $errors = [];
        $lastScrapedData = $this->loadLastScrapedData();
        
        // Alleen echo output als we in CLI mode zijn
        $isCLI = php_sapi_name() === 'cli';
        
        if ($isCLI) {
            echo "[" . date('Y-m-d H:i:s') . "] Start scraping alle nieuwsbronnen...\n";
        }
        
        foreach ($this->newsSources as $sourceName => $sourceConfig) {
            try {
                if ($isCLI) {
                    echo "Scraping $sourceName...\n";
                }
                $newArticles = $this->scrapeSource($sourceName, $sourceConfig, $lastScrapedData);
                
                if (!empty($newArticles)) {
                    if ($isCLI) {
                        echo "  Gevonden: " . count($newArticles) . " nieuwe artikelen\n";
                    }
                    
                    // Voeg artikelen toe aan database
                    foreach ($newArticles as $article) {
                        $success = $this->newsModel->addNewsArticle(
                            $article['title'],
                            $article['description'],
                            $article['url'],
                            $article['source'],
                            $article['bias'],
                            $article['orientation'],
                            $article['publishedAt']
                        );
                        
                        if ($success) {
                            $scrapedCount++;
                        } else if ($isCLI) {
                            echo "  Artikel overgeslagen (invalid/duplicate): " . ($article['url'] ?? 'onbekende-url') . "\n";
                        }
                    }
                    
                    // Update last scraped data
                    $lastScrapedData[$sourceName] = [
                        'last_article_url' => $newArticles[0]['url'],
                        'last_scraped_at' => date('Y-m-d H:i:s'),
                        'articles_found' => count($newArticles)
                    ];
                } else {
                    if ($isCLI) {
                        echo "  Geen nieuwe artikelen gevonden\n";
                    }
                }
                
            } catch (Exception $e) {
                $error = "Fout bij scrapen $sourceName: " . $e->getMessage();
                if ($isCLI) {
                    echo "  $error\n";
                }
                $errors[] = $error;
            }
        }
        
        // Sla laatste scrape data op
        $this->saveLastScrapedData($lastScrapedData);
        
        if ($isCLI) {
            echo "[" . date('Y-m-d H:i:s') . "] Scraping voltooid. $scrapedCount nieuwe artikelen toegevoegd.\n";
            
            if (!empty($errors)) {
                echo "Fouten tijdens scraping:\n";
                foreach ($errors as $error) {
                    echo "  - $error\n";
                }
            }
        }
        
        return [
            'scraped_count' => $scrapedCount,
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Scrape een specifieke nieuwsbron
     */
    private function scrapeSource($sourceName, $sourceConfig, $lastScrapedData) {
        $rssUrls = $sourceConfig['rss_urls'] ?? [];
        if (empty($rssUrls) && !empty($sourceConfig['rss_url'])) {
            $rssUrls = [$sourceConfig['rss_url']];
        }

        $articles = [];
        foreach ($rssUrls as $rssUrl) {
            $candidate = $this->newsAPI->scrapeRSSFeed($rssUrl, 12);
            if (!empty($candidate)) {
                $articles = $candidate;
                break;
            }
        }

        if (empty($articles)) {
            return [];
        }

        $lastArticleUrl = isset($lastScrapedData[$sourceName]) ? $lastScrapedData[$sourceName]['last_article_url'] : '';
        $newArticles = [];
        $seenKeys = [];

        foreach ($articles as $article) {
            $articleUrl = $this->normalizeArticleUrl($article['url'] ?? '');
            if (!$this->isValidHttpUrl($articleUrl)) {
                continue;
            }

            if (!empty($lastArticleUrl) && $articleUrl === $lastArticleUrl) {
                break;
            }

            $title = trim((string)($article['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $publishedAt = $this->normalizePublishedAt($article['publishedAt'] ?? '');
            if (!$this->isPlausiblePublishedAt($publishedAt)) {
                continue;
            }

            $dedupeKey = md5(mb_strtolower($title) . '|' . $articleUrl);
            if (isset($seenKeys[$dedupeKey])) {
                continue;
            }
            $seenKeys[$dedupeKey] = true;

            $article['title'] = $title;
            $article['url'] = $articleUrl;
            $article['source'] = $sourceName;
            $article['orientation'] = $sourceConfig['orientation'];
            $article['bias'] = $sourceConfig['bias'];
            $article['publishedAt'] = $publishedAt;

            $newArticles[] = $article;
        }

        return $newArticles;
    }
    
    /**
     * Controleer of artikel al in database staat
     */
    private function articleExistsInDatabase($url) {
        try {
            $db = $this->newsModel->getDatabase();
            $result = $db->directQuery("SELECT COUNT(*) as count FROM news_articles WHERE url = " . $db->getConnection()->quote($url));
            $count = $result->fetch(PDO::FETCH_ASSOC);
            return $count['count'] > 0;
        } catch (Exception $e) {
            error_log("Fout bij controleren artikel bestaat: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Laad laatste scrape data
     */
    private function loadLastScrapedData() {
        if (file_exists($this->lastScrapedFile)) {
            $data = file_get_contents($this->lastScrapedFile);
            return json_decode($data, true) ?: [];
        }
        return [];
    }
    
    /**
     * Sla laatste scrape data op
     */
    private function saveLastScrapedData($data) {
        file_put_contents($this->lastScrapedFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    private function normalizePublishedAt($publishedAt) {
        $timestamp = strtotime((string)$publishedAt);
        if ($timestamp === false || $timestamp < 946684800) { // voor 2000 als safeguard
            return date('Y-m-d H:i:s');
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function isPlausiblePublishedAt($publishedAt) {
        $timestamp = strtotime((string)$publishedAt);
        if ($timestamp === false) {
            return false;
        }

        $now = time();
        $maxFutureSkew = 2 * 3600;
        $maxPastAge = 14 * 24 * 3600;

        return $timestamp <= ($now + $maxFutureSkew) && $timestamp >= ($now - $maxPastAge);
    }

    private function isValidHttpUrl($url) {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        return in_array(strtolower((string)$scheme), ['http', 'https'], true);
    }

    private function normalizeArticleUrl($url) {
        if (!is_string($url)) {
            return '';
        }

        $url = trim($url);
        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return $url;
        }

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

        $path = $parts['path'] ?? '/';
        return strtolower($parts['scheme']) . '://' . strtolower($parts['host']) . $path . $query;
    }

    /**
     * Clean up oude artikelen (ouder dan X dagen)
     */
    public function cleanupOldArticles($daysOld = 30) {
        try {
            $db = $this->newsModel->getDatabase();
            $sql = "DELETE FROM news_articles WHERE published_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute([$daysOld]);
            $deletedCount = $stmt->rowCount();
            
            if (php_sapi_name() === 'cli') {
                echo "[" . date('Y-m-d H:i:s') . "] Cleanup: $deletedCount oude artikelen verwijderd (ouder dan $daysOld dagen)\n";
            }
            
            return $deletedCount;
        } catch (Exception $e) {
            error_log("Fout bij cleanup oude artikelen: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Krijg scraping statistieken
     */
    public function getScrapingStats() {
        $lastScrapedData = $this->loadLastScrapedData();
        $stats = [];
        
        foreach ($this->newsSources as $sourceName => $sourceConfig) {
            $stats[$sourceName] = [
                'last_scraped' => isset($lastScrapedData[$sourceName]) ? $lastScrapedData[$sourceName]['last_scraped_at'] : 'Nog nooit',
                'last_articles_found' => isset($lastScrapedData[$sourceName]) ? $lastScrapedData[$sourceName]['articles_found'] : 0,
                'rss_urls' => $sourceConfig['rss_urls'] ?? [($sourceConfig['rss_url'] ?? '')],
                'orientation' => $sourceConfig['orientation']
            ];
        }
        
        return $stats;
    }
}
?> 