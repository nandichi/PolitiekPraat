<?php

class NewsAPI {
    private $baseUrl = 'https://newsapi.org/v2';
    private $apiKey; // Deze moet later worden ingesteld via een configuratiebestand
    
    public function __construct($apiKey = null) {
        $this->apiKey = $apiKey;
    }
    
    /**
     * Haalt het laatste politieke nieuws op
     */
    public function getLatestPoliticalNews() {
        // Voorlopig statische data, later te vervangen door echte API calls
        return [
            [
                'title' => 'Kabinetsformatie: Onderhandelingen gaan nieuwe fase in',
                'description' => 'De onderhandelingen tussen PVV, VVD, NSC en BBB gaan een nieuwe fase in na intensieve gesprekken.',
                'source' => 'NOS',
                'publishedAt' => '2024-03-20',
                'url' => '#'
            ],
            [
                'title' => 'Tweede Kamer stemt in met nieuwe klimaatwet',
                'description' => 'Een meerderheid van de Tweede Kamer heeft ingestemd met aangescherpte klimaatdoelen voor 2030.',
                'source' => 'NU.nl',
                'publishedAt' => '2024-03-19',
                'url' => '#'
            ],
            [
                'title' => 'Gemeenteraadsverkiezingen: Opkomst hoger dan verwacht',
                'description' => 'Bij de gemeenteraadsverkiezingen in verschillende gemeenten is de opkomst hoger dan vier jaar geleden.',
                'source' => 'RTL Nieuws',
                'publishedAt' => '2024-03-18',
                'url' => '#'
            ]
        ];
    }
} 