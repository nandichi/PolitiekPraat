<?php

class NewsAPI {
    private $rss_feeds = [
        'De Volkskrant' => [
            'politiek' => 'https://www.volkskrant.nl/voorpagina/rss.xml',
            'algemeen' => 'https://www.volkskrant.nl/voorpagina/rss.xml'
        ],
        'Trouw' => [
            'politiek' => 'https://www.trouw.nl/rss.xml',
            'algemeen' => 'https://www.trouw.nl/rss.xml'
        ],
        'NRC' => [
            'politiek' => 'https://www.nrc.nl/rss/',
            'algemeen' => 'https://www.nrc.nl/rss/'
        ],
        'WNL' => [
            'politiek' => 'https://wnl.tv/category/politiek/feed/',
            'algemeen' => 'https://wnl.tv/feed/'
        ],
        'Telegraaf' => [
            'politiek' => 'https://www.telegraaf.nl/rss',
            'algemeen' => 'https://www.telegraaf.nl/rss'
        ],
        'AD' => [
            'politiek' => 'https://www.ad.nl/rss.xml',
            'algemeen' => 'https://www.ad.nl/rss.xml'
        ],
        'NU.nl' => [
            'politiek' => 'https://www.nu.nl/rss',
            'algemeen' => 'https://www.nu.nl/rss',
            'economie' => 'https://www.nu.nl/rss',
            'klimaat' => 'https://www.nu.nl/rss',
            'tech' => 'https://www.nu.nl/rss',
            'gezondheid' => 'https://www.nu.nl/rss'
        ]
    ];

    public function getLatestPoliticalNews() {
        // Haal nieuws op van NU.nl als standaard bron
        return $this->getNewsFromFeed($this->rss_feeds['NU.nl']['politiek']);
    }

    public function getLatestPoliticalNewsBySource($source) {
        // Controleer of de bron bestaat
        if (!isset($this->rss_feeds[$source])) {
            // Als de bron niet bestaat, simuleer dan wat testdata
            return [[
                'title' => 'Test artikel van ' . $source,
                'description' => 'Dit is een test artikel omdat de RSS feed voor deze bron nog niet beschikbaar is.',
                'url' => '#',
                'publishedAt' => date('Y-m-d H:i:s'),
                'source' => $source
            ]];
        }

        // Haal het politieke nieuws op van de specifieke bron
        return $this->getNewsFromFeed($this->rss_feeds[$source]['politiek']);
    }

    public function getThemaNews($thema) {
        // Map thema to RSS feed
        $feed_url = $this->mapThemaToFeed($thema);
        return $this->getNewsFromFeed($feed_url);
    }

    private function getNewsFromFeed($feed_url) {
        if (!filter_var($feed_url, FILTER_VALIDATE_URL)) {
            return $this->getTestData();
        }

        try {
            // Initialiseer cURL
            $ch = curl_init();
            
            // Set cURL opties
            curl_setopt($ch, CURLOPT_URL, $feed_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Voer het verzoek uit
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            // Sluit cURL
            curl_close($ch);
            
            // Controleer of we een geldige response hebben
            if ($httpCode !== 200 || !$response) {
                throw new Exception("Kon feed niet ophalen: HTTP code " . $httpCode);
            }

            // Converteer de response naar een SimpleXML object
            libxml_use_internal_errors(true);
            $rss = simplexml_load_string($response);
            
            if (!$rss) {
                throw new Exception("Kon RSS feed niet parsen");
            }

            $news = [];
            $count = 0;
            
            // Parse de RSS items
            foreach ($rss->channel->item as $item) {
                if ($count >= 3) break; // Limit to 3 items per source
                
                // Extract image if available
                $image = null;
                $description = (string)$item->description;
                if (preg_match('/<img[^>]+src="([^">]+)"/', $description, $matches)) {
                    $image = $matches[1];
                }
                
                // Clean description
                $clean_description = strip_tags($description);
                if (strlen($clean_description) > 200) {
                    $clean_description = substr($clean_description, 0, 197) . '...';
                }
                
                $news[] = [
                    'title' => (string)$item->title,
                    'description' => $clean_description,
                    'url' => (string)$item->link,
                    'publishedAt' => date('Y-m-d H:i:s', strtotime((string)$item->pubDate)),
                    'source' => $this->getSourceFromFeedUrl($feed_url),
                    'image' => $image
                ];
                
                $count++;
            }

            return $news;
        } catch (Exception $e) {
            error_log("NewsAPI Error: " . $e->getMessage());
            return $this->getTestData($this->getSourceFromFeedUrl($feed_url));
        }
    }

    private function getTestData($source = 'Test') {
        $testData = [
            'De Volkskrant' => [
                [
                    'title' => 'Kabinet valt over migratiestandpunt',
                    'description' => 'Na maanden van onderhandelingen is het kabinet gevallen over het migratiebeleid. Premier Rutte kondigde zijn vertrek aan.',
                    'url' => 'https://www.volkskrant.nl/politiek',
                    'publishedAt' => date('Y-m-d H:i:s'),
                    'source' => 'De Volkskrant'
                ]
            ],
            'Trouw' => [
                [
                    'title' => 'Klimaatbeleid onder de loep',
                    'description' => 'Nieuwe maatregelen voor CO2-reductie leiden tot verhitte discussies in de Tweede Kamer.',
                    'url' => 'https://www.trouw.nl/politiek',
                    'publishedAt' => date('Y-m-d H:i:s'),
                    'source' => 'Trouw'
                ]
            ],
            'WNL' => [
                [
                    'title' => 'Debat over veiligheid en criminaliteit',
                    'description' => 'Politici discussiëren over nieuwe aanpak van ondermijnende criminaliteit en straatgeweld.',
                    'url' => 'https://wnl.tv/politiek',
                    'publishedAt' => date('Y-m-d H:i:s'),
                    'source' => 'WNL'
                ]
            ],
            'NRC' => [
                [
                    'title' => 'Nieuwe verkiezingen in aantocht',
                    'description' => 'Na de val van het kabinet worden er nieuwe verkiezingen uitgeschreven. Partijen bereiden zich voor op campagne.',
                    'url' => 'https://www.nrc.nl/politiek',
                    'publishedAt' => date('Y-m-d H:i:s'),
                    'source' => 'NRC'
                ]
            ],
            'Telegraaf' => [
                [
                    'title' => 'Belastingplan onder vuur',
                    'description' => 'Oppositie uit felle kritiek op nieuwe belastingmaatregelen van het demissionaire kabinet.',
                    'url' => 'https://www.telegraaf.nl/politiek',
                    'publishedAt' => date('Y-m-d H:i:s'),
                    'source' => 'Telegraaf'
                ]
            ]
        ];

        return isset($testData[$source]) ? $testData[$source] : [[
            'title' => 'Politiek nieuws van ' . $source,
            'description' => 'Dit is een tijdelijk artikel terwijl we proberen verbinding te maken met de nieuwsbron.',
            'url' => '#',
            'publishedAt' => date('Y-m-d H:i:s'),
            'source' => $source
        ]];
    }

    private function getSourceFromFeedUrl($feed_url) {
        foreach ($this->rss_feeds as $source => $feeds) {
            foreach ($feeds as $feed) {
                if ($feed === $feed_url) {
                    return $source;
                }
            }
        }
        return 'Onbekende bron';
    }

    private function mapThemaToFeed($thema) {
        $mapping = [
            'klimaatbeleid' => 'klimaat',
            'economie' => 'economie',
            'zorg' => 'gezondheid',
            'tech' => 'tech'
        ];

        return isset($mapping[$thema]) ? 
            $this->rss_feeds['NU.nl'][$mapping[$thema]] : 
            $this->rss_feeds['NU.nl']['politiek']; // Default to politiek feed
    }
} 