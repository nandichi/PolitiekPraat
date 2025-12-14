<?php
require_once 'NewsAPI.php';

class NewsScraper {
    private $newsAPI;
    private $newsModel;
    private $lastScrapedFile;
    
    // Configuratie voor nieuwsbronnen met hun RSS feeds en oriëntatie
    private $newsSources = [
        'De Volkskrant' => [
            'rss_url' => 'https://www.volkskrant.nl/nieuws/rss.xml',
            'orientation' => 'links',
            'bias' => 'Progressief'
        ],
        'NRC' => [
            'rss_url' => 'https://www.nrc.nl/rss/',
            'orientation' => 'links',
            'bias' => 'Progressief'
        ],
        'Trouw' => [
            'rss_url' => 'https://www.trouw.nl/politiek/rss.xml',
            'orientation' => 'links',
            'bias' => 'Progressief'
        ],
        'AD' => [
            'rss_url' => 'https://www.ad.nl/politiek/rss.xml',
            'orientation' => 'midden',
            'bias' => 'Conservatief'
        ],
        'NU.nl' => [
            'rss_url' => 'https://www.nu.nl/rss/Politiek',
            'orientation' => 'rechts',
            'bias' => 'Conservatief'
        ],
        'RTL Nieuws' => [
            'rss_url' => 'https://www.rtlnieuws.nl/rss.xml',
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
        $articles = $this->newsAPI->scrapeRSSFeed($sourceConfig['rss_url'], 10);
        
        if (empty($articles)) {
            return [];
        }
        
        // Check of er nieuwe artikelen zijn
        $lastArticleUrl = isset($lastScrapedData[$sourceName]) ? $lastScrapedData[$sourceName]['last_article_url'] : '';
        $newArticles = [];
        
        foreach ($articles as $article) {
            // Stop zodra we een artikel vinden dat we al eerder hebben gezien
            if ($article['url'] === $lastArticleUrl) {
                break;
            }
            
            // Voeg metadata toe
            $article['source'] = $sourceName;
            $article['orientation'] = $sourceConfig['orientation'];
            $article['bias'] = $sourceConfig['bias'];
            
            // Controleer of artikel al in database staat
            if (!$this->articleExistsInDatabase($article['url'])) {
                $newArticles[] = $article;
            }
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
                'rss_url' => $sourceConfig['rss_url'],
                'orientation' => $sourceConfig['orientation']
            ];
        }
        
        return $stats;
    }
}
?> 