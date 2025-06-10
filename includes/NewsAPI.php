<?php

class NewsAPI {
    private $rss_feeds = [
        'De Volkskrant' => [
            'politiek' => 'https://www.volkskrant.nl/nieuws-achtergrond/politiek/rss.xml',
            'algemeen' => 'https://www.volkskrant.nl/voorpagina/rss.xml'
        ],
        'Trouw' => [
            'politiek' => 'https://www.trouw.nl/politiek/rss.xml',
            'algemeen' => 'https://www.trouw.nl/rss.xml'
        ],
        'NRC' => [
            'politiek' => 'https://www.nrc.nl/sectie/politiek/rss/',
            'algemeen' => 'https://www.nrc.nl/rss/'
        ],
        'WNL' => [
            'politiek' => 'https://wnl.tv/category/politiek/feed/',
            'algemeen' => 'https://wnl.tv/feed/'
        ],
        'Telegraaf' => [
            'politiek' => 'https://www.telegraaf.nl/nieuws/politiek/rss',
            'algemeen' => 'https://www.telegraaf.nl/rss'
        ],
        'AD' => [
            'politiek' => 'https://www.ad.nl/politiek/rss.xml',
            'algemeen' => 'https://www.ad.nl/rss.xml'
        ],
        'Reformatorisch Dagblad' => [
            'politiek' => 'https://www.rd.nl/rss',
            'algemeen' => 'https://www.rd.nl/rss'
        ],
        'NU.nl' => [
            'politiek' => 'https://www.nu.nl/rss/Politiek',
            'algemeen' => 'https://www.nu.nl/rss',
            'economie' => 'https://www.nu.nl/rss/Economie',
            'klimaat' => 'https://www.nu.nl/rss/Klimaat',
            'tech' => 'https://www.nu.nl/rss/Tech',
            'gezondheid' => 'https://www.nu.nl/rss/Gezondheid'
        ]
    ];

    private $political_keywords = [
        'politiek', 'kabinet', 'minister', 'staatssecretaris', 'tweede kamer', 'eerste kamer', 
        'partij', 'verkiezing', 'coalitie', 'oppositie', 'regering', 'regeerakkoord',
        'kamerlid', 'motie', 'debat', 'formatie', 'formateur', 'informateur', 'premier',
        'pvda', 'vvd', 'cda', 'd66', 'groenlinks', 'sp', 'pvv', 'forum voor democratie', 'fvd',
        'christenunie', 'sgp', 'denk', 'volt', 'bij1', 'nsc', 'bbb', 'nieuwe sociale contract',
        'schoof', 'wilders', 'omtzigt', 'timmermans', 'yesilgöz', 'yesilgoz', 'dijkgraaf',
        'klaver', 'marijnissen', 'kuzu', 'azarkan', 'ouwehand', 'asiel', 'asielbeleid',
        'migratie', 'immigratie', 'klimaat', 'stikstof', 'belasting', 'belastingplan',
        'staatsschuld', 'defensie', 'europa', 'europese unie', 'eu', 'tweede kamerfractie',
        'parlement', 'parlementair', 'begroting', 'miljoenennota', 'prinsjesdag', 'troonrede',
        'wetsvoorstel', 'wetgeving', 'stemmen', 'referendum', 'overheid', 'gemeenteraad',
        'burgemeester', 'gemeente', 'provincie', 'provinciale staten'
    ];

    // Voeg woorden toe die expliciet niet-politieke artikelen aanduiden
    private $exclusion_keywords = [
        'sport', 'voetbal', 'tennis', 'hockey', 'schaatsen', 'wielrennen', 'olympisch', 'olympische spelen',
        'formule 1', 'f1', 'eredivisie', 'champions league', 'knvb', 'fifa', 'uefa',
        'entertainment', 'film', 'bioscoop', 'muziek', 'concert', 'festival', 'tv', 'televisie',
        'hollywood', 'celebrity', 'beroemdheid', 'beroemdheden', 'acteur', 'actrice', 'zanger', 'zangeres'
    ];

    public function getLatestPoliticalNews() {
        // Haal nieuws op van NU.nl als standaard bron
        return $this->getNewsFromFeed($this->rss_feeds['NU.nl']['politiek']);
    }

    public function getLatestPoliticalNewsBySource($source) {
        // Controleer of de bron bestaat
        if (!isset($this->rss_feeds[$source])) {
            error_log("Nieuwsbron niet gevonden: $source");
            // Fallback naar test data als de bron niet bestaat
            return $this->getTestData($source);
        }

        // Probeer eerst politieke feed
        $news = $this->getNewsFromFeed($this->rss_feeds[$source]['politiek']);
        
        // Filter alleen politieke artikelen
        $news = array_filter($news, function($article) {
            return isset($article['isPolitical']) && $article['isPolitical'] === true;
        });
        
        // Als er niet genoeg politiek nieuws is, probeer dan algemene feed en filter strikt op politieke content
        if (count($news) < 3) {
            $general_news = $this->getNewsFromFeed($this->rss_feeds[$source]['algemeen'], true);
            
            // Filter de algemene feed streng op politieke content
            $general_news = array_filter($general_news, function($article) {
                return isset($article['isPolitical']) && $article['isPolitical'] === true;
            });
            
            // Voeg toe aan bestaand nieuws
            if (!empty($general_news)) {
                $news = array_merge($news, $general_news);
            }
        }
        
        // Voor NU.nl, als we nog steeds niet genoeg nieuws hebben, probeer dan ook economie en klimaat feeds maar strikt filteren
        if ($source === 'NU.nl' && count($news) < 3) {
            $more_news = [];
            
            // Voeg economie nieuws toe, maar alleen als het politiek relevant is
            $econ_news = $this->getNewsFromFeed($this->rss_feeds[$source]['economie'], true);
            $econ_news = array_filter($econ_news, function($article) {
                return isset($article['isPolitical']) && $article['isPolitical'] === true;
            });
            
            if (!empty($econ_news)) {
                $more_news = array_merge($more_news, $econ_news);
            }
            
            // Voeg klimaat nieuws toe, maar alleen als het politiek relevant is
            $climate_news = $this->getNewsFromFeed($this->rss_feeds[$source]['klimaat'], true);
            $climate_news = array_filter($climate_news, function($article) {
                return isset($article['isPolitical']) && $article['isPolitical'] === true;
            });
            
            if (!empty($climate_news)) {
                $more_news = array_merge($more_news, $climate_news);
            }
            
            // Combineer en limiteer
            if (!empty($more_news)) {
                // Sorteer op datum
                usort($more_news, function($a, $b) {
                    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
                });
                
                // Voeg toe aan bestaand nieuws
                $news = array_merge($news, array_slice($more_news, 0, 5 - count($news)));
            }
        }
        
        // Sorteer het gecombineerde nieuws op datum
        usort($news, function($a, $b) {
            return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
        });
        
        // Als er nog steeds geen nieuws is, gebruik test data maar alleen politieke items
        if (empty($news)) {
            error_log("Geen politiek nieuws gevonden voor $source, val terug op test data");
            $test_data = $this->getTestData($source);
            return array_filter($test_data, function($article) {
                return $article['isPolitical'] === true;
            });
        }
        
        return $news;
    }

    public function getThemaNews($thema) {
        // Map thema to RSS feed
        $feed_url = $this->mapThemaToFeed($thema);
        return $this->getNewsFromFeed($feed_url);
    }
    
    /**
     * Publieke methode om RSS feed te scrapen (voor NewsScraper)
     */
    public function scrapeRSSFeed($feed_url, $maxItems = 10) {
        // Tijdelijk wijzig de max_items in de private methode
        return $this->getNewsFromFeedWithLimit($feed_url, false, $maxItems);
    }

    private function getNewsFromFeed($feed_url, $filterPolitical = false) {
        return $this->getNewsFromFeedWithLimit($feed_url, $filterPolitical, 5);
    }
    
    private function getNewsFromFeedWithLimit($feed_url, $filterPolitical = false, $max_items = 5) {
        if (!filter_var($feed_url, FILTER_VALIDATE_URL)) {
            error_log("Ongeldige feed URL: $feed_url");
            return [];
        }

        try {
            // Initialiseer cURL
            $ch = curl_init();
            
            // Set cURL opties
            curl_setopt($ch, CURLOPT_URL, $feed_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Increase timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Voer het verzoek uit
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            // Sluit cURL
            curl_close($ch);
            
            // Controleer of we een geldige response hebben
            if ($httpCode !== 200 || !$response) {
                throw new Exception("Kon feed niet ophalen: HTTP code " . $httpCode . " Error: " . $error);
            }

            // Fix mogelijk slecht gevormde XML (specifiek voor NU.nl)
            if (strpos($feed_url, 'nu.nl') !== false) {
                $response = preg_replace('/(&(?!amp;|lt;|gt;|quot;|apos;|#\d+;))/i', '&amp;', $response);
            }

            // Converteer de response naar een SimpleXML object
            libxml_use_internal_errors(true);
            $rss = simplexml_load_string($response);
            
            if (!$rss) {
                $errors = libxml_get_errors();
                libxml_clear_errors();
                $errorMsg = "XML parse errors: ";
                foreach ($errors as $error) {
                    $errorMsg .= $error->message . " ";
                }
                throw new Exception($errorMsg);
            }

            $news = [];
            $counter = 0;
            // $max_items is now passed as parameter
            
            // Check if it's RSS 2.0 format
            if (isset($rss->channel) && isset($rss->channel->item)) {
                foreach ($rss->channel->item as $item) {
                    // Check if the article is political when filtering is needed
                    $title = strtolower((string)$item->title);
                    $description = strtolower((string)$item->description);
                    $isPolitical = $this->isPoliticalContent($title, $description);

                    // Skip if filtering political content and article is not political
                    if ($filterPolitical && !$isPolitical) {
                        continue;
                    }

                    if ($counter >= $max_items) break;

                    // Extract image if available
                    $image = $this->extractImageFromItem($item);
                    
                    // Special case for NU.nl - extract image from media:content
                    if (!$image && strpos($feed_url, 'nu.nl') !== false) {
                        $namespaces = $item->getNamespaces(true);
                        if (isset($namespaces['media'])) {
                            $media = $item->children($namespaces['media']);
                            if (isset($media->content) && isset($media->content->attributes()->url)) {
                                $image = (string)$media->content->attributes()->url;
                            }
                        }
                    }
                    
                    // Clean description
                    $clean_description = $this->cleanDescription((string)$item->description);
                    
                    $news[] = [
                        'title' => (string)$item->title,
                        'description' => $clean_description,
                        'url' => (string)$item->link,
                        'publishedAt' => date('Y-m-d H:i:s', strtotime((string)$item->pubDate)),
                        'source' => $this->getSourceFromFeedUrl($feed_url),
                        'image' => $image,
                        'isPolitical' => $isPolitical
                    ];
                    
                    $counter++;
                }
            } 
            // Check if it's Atom format
            else if (isset($rss->entry)) {
                foreach ($rss->entry as $entry) {
                    $namespaces = $entry->getNamespaces(true);
                    
                    // Check if the article is political when filtering is needed
                    $title = strtolower((string)$entry->title);
                    $description = strtolower((string)$entry->summary);
                    $isPolitical = $this->isPoliticalContent($title, $description);

                    // Skip if filtering political content and article is not political
                    if ($filterPolitical && !$isPolitical) {
                        continue;
                    }

                    if ($counter >= $max_items) break;

                    // Extract image if available
                    $image = null;
                    if (isset($entry->content)) {
                        $image = $this->extractImageFromContent((string)$entry->content);
                    }
                    
                    // Get link
                    $link = "";
                    if (isset($entry->link['href'])) {
                        $link = (string)$entry->link['href'];
                    } else if (isset($entry->link)) {
                        $link = (string)$entry->link;
                    }
                    
                    // Clean description
                    $description = isset($entry->summary) ? (string)$entry->summary : (isset($entry->content) ? (string)$entry->content : "");
                    $clean_description = $this->cleanDescription($description);
                    
                    $news[] = [
                        'title' => (string)$entry->title,
                        'description' => $clean_description,
                        'url' => $link,
                        'publishedAt' => date('Y-m-d H:i:s', strtotime((string)$entry->published)),
                        'source' => $this->getSourceFromFeedUrl($feed_url),
                        'image' => $image,
                        'isPolitical' => $isPolitical
                    ];
                    
                    $counter++;
                }
            }

            if (empty($news)) {
                error_log("Geen artikelen gevonden in de feed: $feed_url");
            }

            return $news;

        } catch (Exception $e) {
            error_log("NewsAPI Error: " . $e->getMessage() . " URL: " . $feed_url);
            return [];
        }
    }

    private function isPoliticalContent($title, $description) {
        // Combineer titel en beschrijving voor een volledige analyse
        $fullContent = strtolower($title . ' ' . $description);
        
        // Controleer eerst of er expliciete niet-politieke termen in de content staan
        foreach ($this->exclusion_keywords as $keyword) {
            if (strpos($fullContent, $keyword) !== false) {
                // Als het expliciet een sport of entertainment artikel lijkt, retourneer false
                return false;
            }
        }
        
        // Tel het aantal politieke keywords dat voorkomt
        $keywordCount = 0;
        $highPriorityKeywords = [
            'kabinet', 'minister', 'staatssecretaris', 'tweede kamer', 'eerste kamer', 
            'partij', 'verkiezing', 'coalitie', 'oppositie', 'regering', 'regeerakkoord',
            'kamerlid', 'motie', 'debat', 'formatie', 'formateur', 'wetsvoorstel', 'stemming',
            'premier', 'schoof', 'wilders', 'omtzigt', 'timmermans', 'asielbeleid'
        ];
        
        // Check eerst voor hoogprioritaire keywords die direct politiek inhoud aanduiden
        foreach ($highPriorityKeywords as $keyword) {
            if (strpos($fullContent, $keyword) !== false) {
                return true;
            }
        }
        
        // Check de volledige lijst van politieke keywords
        foreach ($this->political_keywords as $keyword) {
            if (strpos($fullContent, $keyword) !== false) {
                $keywordCount++;
            }
        }
        
        // Als er meerdere politieke termen voorkomen, is het waarschijnlijk een politiek artikel
        if ($keywordCount >= 2) {
            return true;
        }
        
        // Check of de titel zelf al een politieke term bevat (sterkere indicator)
        $title = strtolower($title);
        foreach ($this->political_keywords as $keyword) {
            if (strpos($title, $keyword) !== false) {
                return true;
            }
        }
        
        // Check of het expliciet over een politicus of partij gaat
        $politicians = ['rutte', 'schoof', 'wilders', 'omtzigt', 'timmermans', 'yesilgöz', 'yesilgoz', 
                       'klaver', 'marijnissen', 'kuzu', 'azarkan', 'ouwehand', 'van der plas', 'dijkgraaf'];
        
        foreach ($politicians as $politician) {
            if (strpos($fullContent, $politician) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function extractImageFromItem($item) {
        $image = null;
        
        // Method 1: Look for enclosure with image mime type
        if (isset($item->enclosure) && isset($item->enclosure['type']) && strpos((string)$item->enclosure['type'], 'image') !== false) {
            $image = (string)$item->enclosure['url'];
        }
        
        // Method 2: Check for media namespace
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media']) && isset($item->children($namespaces['media'])->content)) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content) && isset($media->content->attributes()->url)) {
                $image = (string)$media->content->attributes()->url;
            } else if (isset($media->thumbnail) && isset($media->thumbnail->attributes()->url)) {
                $image = (string)$media->thumbnail->attributes()->url;
            }
        }
        
        // Method 3: Try to find image in the description
        if (!$image && isset($item->description)) {
            if (preg_match('/<img[^>]+src="([^">]+)"/', (string)$item->description, $matches)) {
                $image = $matches[1];
            }
        }
        
        // Method 4: Look for image in content:encoded
        if (!$image && isset($item->children('content', true)->encoded)) {
            $content = (string)$item->children('content', true)->encoded;
            if (preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches)) {
                $image = $matches[1];
            }
        }
        
        return $image;
    }

    private function extractImageFromContent($content) {
        $image = null;
        if (preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches)) {
            $image = $matches[1];
        }
        return $image;
    }

    private function cleanDescription($html) {
        // Remove all HTML tags
        $text = strip_tags($html);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Limit length
        if (strlen($text) > 200) {
            $text = substr($text, 0, 197) . '...';
        }
        
        return $text;
    }

    private function getTestData($source = 'Test') {
        $now = date('Y-m-d H:i:s');
        $fiveMinAgo = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $tenMinAgo = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        
        $testData = [
            'De Volkskrant' => [
                [
                    'title' => 'Kabinet stelt 500 miljoen beschikbaar voor woningbouw',
                    'description' => 'Het kabinet stelt 500 miljoen euro extra beschikbaar om de woningbouw te stimuleren. Minister Hugo de Jonge (Volkshuisvesting) hoopt hiermee de bouw van 100.000 nieuwe woningen mogelijk te maken.',
                    'url' => 'https://www.volkskrant.nl/politiek',
                    'publishedAt' => $now,
                    'source' => 'De Volkskrant',
                    'isPolitical' => true,
                    'image' => 'https://static.volkskrant.nl/images/housing_crisis.jpg'
                ],
                [
                    'title' => 'Oppositie uiterst kritisch over uitvoering klimaatplannen',
                    'description' => 'De oppositiepartijen hebben forse kritiek op de manier waarop het kabinet de klimaatdoelen wil bereiken. "Te weinig, te laat en zonder concreet plan," aldus GroenLinks.',
                    'url' => 'https://www.volkskrant.nl/politiek/klimaat',
                    'publishedAt' => $fiveMinAgo,
                    'source' => 'De Volkskrant',
                    'isPolitical' => true,
                    'image' => 'https://static.volkskrant.nl/images/climate_debate.jpg'
                ]
            ],
            'NRC' => [
                [
                    'title' => 'Nieuwe coalitie onder druk door asielcrisis',
                    'description' => 'De pas gevormde coalitie staat onder druk door de aanhoudende discussies over het asielbeleid. PVV-leider Wilders dreigt met consequenties als er geen strengere maatregelen komen.',
                    'url' => 'https://www.nrc.nl/nieuws/politiek',
                    'publishedAt' => $now,
                    'source' => 'NRC',
                    'isPolitical' => true,
                    'image' => 'https://static.nrc.nl/images/coalition_tension.jpg'
                ]
            ],
            'Trouw' => [
                [
                    'title' => 'Staatssecretaris erkent problemen bij uitvoeringsinstanties',
                    'description' => 'Staatssecretaris Van Huffelen (Digitalisering) erkent dat er nog steeds grote problemen zijn bij belangrijke uitvoeringsinstanties zoals het UWV, de Belastingdienst en het CBR.',
                    'url' => 'https://www.trouw.nl/politiek',
                    'publishedAt' => $tenMinAgo,
                    'source' => 'Trouw',
                    'isPolitical' => true,
                    'image' => 'https://static.trouw.nl/images/government_agencies.jpg'
                ]
            ],
            'NU.nl' => [
                [
                    'title' => 'Kabinet presenteert plannen voor hervorming belastingstelsel',
                    'description' => 'Minister van Financiën Eelco Heinen heeft de plannen voor een grote hervorming van het Nederlandse belastingstelsel gepresenteerd. Vermogens en bedrijfswinsten worden zwaarder belast.',
                    'url' => 'https://www.nu.nl/politiek/belastingstelsel-hervorming.html',
                    'publishedAt' => $now,
                    'source' => 'NU.nl',
                    'isPolitical' => true,
                    'image' => 'https://media.nu.nl/m/hfwx8sjavvxy_sqr256.jpg/kabinet-presenteert-plannen-hervorming-belastingstelsel.jpg'
                ],
                [
                    'title' => 'Kamer stemt in met asielwet ondanks kritiek oppositie',
                    'description' => 'De Tweede Kamer heeft ingestemd met de nieuwe asielwet, ondanks felle kritiek van de oppositiepartijen. De wet moet de instroom van asielzoekers beperken.',
                    'url' => 'https://www.nu.nl/politiek/asielwet-aangenomen.html',
                    'publishedAt' => $fiveMinAgo,
                    'source' => 'NU.nl',
                    'isPolitical' => true,
                    'image' => 'https://media.nu.nl/m/qcux34ma09un_sqr256.jpg/kamer-stemt-in-met-asielwet.jpg'
                ]
            ],
            'Telegraaf' => [
                [
                    'title' => 'Kabinet overweegt lagere belastingen voor mkb',
                    'description' => 'Het kabinet onderzoekt mogelijkheden om de belastingdruk voor het midden- en kleinbedrijf te verlagen. Minister Dirk Beljaarts (Economische Zaken) spreekt van "noodzakelijke hervormingen".',
                    'url' => 'https://www.telegraaf.nl/politiek',
                    'publishedAt' => $fiveMinAgo,
                    'source' => 'Telegraaf',
                    'isPolitical' => true,
                    'image' => 'https://static.telegraaf.nl/images/tax_reform.jpg'
                ]
            ],
            'AD' => [
                [
                    'title' => 'Minister belooft aanpak wachttijden in de zorg',
                    'description' => 'Minister Fleur Agema (Volksgezondheid) heeft maatregelen aangekondigd om de oplopende wachttijden in de zorg tegen te gaan. Er komen extra middelen vrij voor ziekenhuizen.',
                    'url' => 'https://www.ad.nl/politiek',
                    'publishedAt' => $tenMinAgo,
                    'source' => 'AD',
                    'isPolitical' => true,
                    'image' => 'https://static.ad.nl/images/healthcare_waiting.jpg'
                ]
            ],
            'Test' => [
                [
                    'title' => 'Test artikel van ' . $source,
                    'description' => 'Dit is een test artikel omdat de RSS feed voor deze bron nog niet beschikbaar is.',
                    'url' => '#',
                    'publishedAt' => $now,
                    'source' => $source,
                    'isPolitical' => true,
                    'image' => null
                ]
            ]
        ];
        
        return isset($testData[$source]) ? $testData[$source] : $testData['Test'];
    }

    private function getSourceFromFeedUrl($feed_url) {
        foreach ($this->rss_feeds as $source => $feeds) {
            foreach ($feeds as $feed) {
                if ($feed === $feed_url) {
                    return $source;
                }
            }
        }
        
        // Try to extract domain name from URL
        $urlParts = parse_url($feed_url);
        if (isset($urlParts['host'])) {
            $domain = $urlParts['host'];
            // Remove www. if present
            $domain = preg_replace('/^www\./', '', $domain);
            // Get domain name without extension
            $parts = explode('.', $domain);
            if (count($parts) >= 2) {
                // Capitalize first letter
                return ucfirst($parts[0]);
            }
            return ucfirst($domain);
        }
        
        return 'Onbekende bron';
    }

    private function mapThemaToFeed($thema) {
        $thema = strtolower($thema);
        
        $themaMapping = [
            'klimaat' => $this->rss_feeds['NU.nl']['klimaat'],
            'economie' => $this->rss_feeds['NU.nl']['economie'],
            'tech' => $this->rss_feeds['NU.nl']['tech'],
            'gezondheid' => $this->rss_feeds['NU.nl']['gezondheid'],
            'default' => $this->rss_feeds['NU.nl']['algemeen']
        ];
        
        return isset($themaMapping[$thema]) ? $themaMapping[$thema] : $themaMapping['default'];
    }
} 