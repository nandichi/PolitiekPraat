<?php

class NewsAPI {
    private $feeds = [
        [
            'name' => 'NOS',
            'url' => 'https://feeds.nos.nl/nosnieuwspolitiek',
            'type' => 'rss'
        ],
        [
            'name' => 'NU.nl',
            'url' => 'https://www.nu.nl/rss/Politiek',
            'type' => 'rss'
        ]
    ];
    
    /**
     * Haalt het laatste politieke nieuws op via RSS feeds
     * @return array Array met nieuws artikelen
     */
    public function getLatestPoliticalNews() {
        $news = [];
        
        foreach ($this->feeds as $feed) {
            try {
                $rss = simplexml_load_file($feed['url']);
                
                if ($rss === false) {
                    continue;
                }
                
                foreach ($rss->channel->item as $item) {
                    // Strip HTML tags en kort de beschrijving in
                    $description = strip_tags((string)$item->description);
                    if (strlen($description) > 150) {
                        $description = substr($description, 0, 147) . '...';
                    }

                    $news[] = [
                        'title' => (string)$item->title,
                        'description' => $description,
                        'source' => $feed['name'],
                        'publishedAt' => date('Y-m-d', strtotime((string)$item->pubDate)),
                        'url' => (string)$item->link
                    ];
                    
                    // Maximaal 5 artikelen per bron
                    if (count($news) >= 5) {
                        break;
                    }
                }
            } catch (Exception $e) {
                // Log de error maar ga door met de volgende feed
                error_log("Error loading RSS feed {$feed['name']}: " . $e->getMessage());
                continue;
            }
        }
        
        // Sorteer op datum (nieuwste eerst)
        usort($news, function($a, $b) {
            return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
        });
        
        // Return maximaal 6 artikelen in totaal
        return array_slice($news, 0, 6);
    }
} 