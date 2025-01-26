<?php

class NewsAPI {
    private $rss_feeds = [
        'algemeen' => 'https://www.nu.nl/rss/Algemeen',
        'politiek' => 'https://www.nu.nl/rss/Politiek',
        'economie' => 'https://www.nu.nl/rss/Economie',
        'klimaat' => 'https://www.nu.nl/rss/Klimaat',
        'tech' => 'https://www.nu.nl/rss/Tech',
        'gezondheid' => 'https://www.nu.nl/rss/Gezondheid'
    ];

    public function getLatestPoliticalNews() {
        return $this->getNewsFromFeed($this->rss_feeds['politiek']);
    }

    public function getThemaNews($thema) {
        // Map thema to RSS feed
        $feed_url = $this->mapThemaToFeed($thema);
        return $this->getNewsFromFeed($feed_url);
    }

    private function getNewsFromFeed($feed_url) {
        // Fetch RSS feed
        $rss = simplexml_load_file($feed_url);
        if (!$rss) {
            return [];
        }

        $news = [];
        $count = 0;
        
        foreach ($rss->channel->item as $item) {
            if ($count >= 6) break; // Limit to 6 items
            
            // Extract image if available
            $image = null;
            $description = (string)$item->description;
            if (preg_match('/<img[^>]+src="([^">]+)"/', $description, $matches)) {
                $image = $matches[1];
            }
            
            // Clean description
            $clean_description = strip_tags($description);
            
            $news[] = [
                'title' => (string)$item->title,
                'description' => $clean_description,
                'url' => (string)$item->link,
                'publishedAt' => date('Y-m-d H:i:s', strtotime((string)$item->pubDate)),
                'source' => 'NU.nl',
                'image' => $image
            ];
            
            $count++;
        }

        return $news;
    }

    private function mapThemaToFeed($thema) {
        $mapping = [
            'klimaatbeleid' => 'klimaat',
            'economie' => 'economie',
            'zorg' => 'gezondheid',
            'tech' => 'tech'
        ];

        return isset($mapping[$thema]) ? 
            $this->rss_feeds[$mapping[$thema]] : 
            $this->rss_feeds['politiek']; // Default to politiek feed
    }
} 