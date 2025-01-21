<?php

class OpenDataAPI {
    private $cache_duration = 3600; // 1 uur cache

    public function __construct() {
        if (!file_exists('../cache')) {
            mkdir('../cache', 0777, true);
        }
    }

    private function fetchWithCache($url, $cache_key) {
        $cache_file = "../cache/{$cache_key}.json";
        
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $this->cache_duration)) {
            return json_decode(file_get_contents($cache_file), true);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            file_put_contents($cache_file, $response);
            return json_decode($response, true);
        }

        return null;
    }

    public function getActueleThemas() {
        // Open Kamer API voor actuele thema's
        $url = "https://opendata.tweedekamer.nl/v1/agenda/actueel";
        $data = $this->fetchWithCache($url, 'actuele_themas');
        
        $themas = [];
        if ($data && isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (count($themas) >= 6) break;
                
                $themas[] = [
                    'title' => $item['onderwerp'] ?? 'Onbekend thema',
                    'icon' => $this->getThemaIcon($item['onderwerp'] ?? ''),
                    'description' => $item['omschrijving'] ?? 'Geen beschrijving beschikbaar'
                ];
            }
        }

        // Fallback als de API geen resultaten geeft
        if (empty($themas)) {
            return [
                ['title' => 'Klimaatbeleid', 'icon' => '🌍', 'description' => 'Actuele ontwikkelingen in klimaatbeleid'],
                ['title' => 'Woningmarkt', 'icon' => '🏠', 'description' => 'Laatste updates woningmarkt'],
                ['title' => 'Economie', 'icon' => '💶', 'description' => 'Economische ontwikkelingen'],
                ['title' => 'Zorg', 'icon' => '🏥', 'description' => 'Actuele zorgthema\'s'],
                ['title' => 'Onderwijs', 'icon' => '📚', 'description' => 'Onderwijsontwikkelingen'],
                ['title' => 'Arbeidsmarkt', 'icon' => '💼', 'description' => 'Arbeidsmarkt updates']
            ];
        }

        return $themas;
    }

    public function getPolitiekeDebatten() {
        // Open Kamer API voor debatten
        $url = "https://opendata.tweedekamer.nl/v1/vergaderingen/komende";
        $data = $this->fetchWithCache($url, 'politieke_debatten');
        
        $debatten = [];
        if ($data && isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (count($debatten) >= 2) break;
                
                $debatten[] = [
                    'datum' => $item['datum'] ?? date('Y-m-d'),
                    'titel' => $item['onderwerp'] ?? 'Onbekend debat',
                    'status' => strtotime($item['datum']) > time() ? 'Aankomend' : 'Recent',
                    'beschrijving' => $item['omschrijving'] ?? 'Geen beschrijving beschikbaar'
                ];
            }
        }

        // Fallback als de API geen resultaten geeft
        if (empty($debatten)) {
            return [
                [
                    'datum' => date('Y-m-d', strtotime('+1 week')),
                    'titel' => 'Algemeen Overleg',
                    'status' => 'Aankomend',
                    'beschrijving' => 'Algemene bespreking actuele zaken'
                ],
                [
                    'datum' => date('Y-m-d', strtotime('-2 days')),
                    'titel' => 'Vragenuur',
                    'status' => 'Recent',
                    'beschrijving' => 'Wekelijks vragenuur Tweede Kamer'
                ]
            ];
        }

        return $debatten;
    }

    public function getPolitiekeAgenda() {
        // Combinatie van verschillende open data bronnen
        $url = "https://opendata.tweedekamer.nl/v1/agenda/komende";
        $data = $this->fetchWithCache($url, 'politieke_agenda');
        
        $agenda = [];
        if ($data && isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (count($agenda) >= 3) break;
                
                $agenda[] = [
                    'datum' => $item['datum'] ?? date('Y-m-d'),
                    'titel' => $item['onderwerp'] ?? 'Onbekende gebeurtenis',
                    'type' => $item['soort'] ?? 'Evenement',
                    'locatie' => $item['locatie'] ?? 'Den Haag'
                ];
            }
        }

        // Fallback als de API geen resultaten geeft
        if (empty($agenda)) {
            return [
                [
                    'datum' => date('Y-m-d', strtotime('+1 week')),
                    'titel' => 'Commissievergadering',
                    'type' => 'Vergadering',
                    'locatie' => 'Tweede Kamer'
                ],
                [
                    'datum' => date('Y-m-d', strtotime('+2 weeks')),
                    'titel' => 'Plenair Debat',
                    'type' => 'Debat',
                    'locatie' => 'Den Haag'
                ],
                [
                    'datum' => date('Y-m-d', strtotime('+1 month')),
                    'titel' => 'Begrotingsbehandeling',
                    'type' => 'Debat',
                    'locatie' => 'Tweede Kamer'
                ]
            ];
        }

        return $agenda;
    }

    private function getThemaIcon($onderwerp) {
        $icons = [
            'klimaat' => '🌍',
            'wonen' => '🏠',
            'economie' => '💶',
            'zorg' => '🏥',
            'onderwijs' => '📚',
            'arbeid' => '💼',
            'veiligheid' => '🛡️',
            'migratie' => '🌐',
            'landbouw' => '🌾',
            'verkeer' => '🚗'
        ];

        foreach ($icons as $keyword => $icon) {
            if (stripos($onderwerp, $keyword) !== false) {
                return $icon;
            }
        }

        return '📋'; // Default icon
    }
} 