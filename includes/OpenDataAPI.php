<?php
require_once 'config.php';

class OpenDataAPI {
    private $cache_dir = 'cache/';
    private $cache_time = 3600; // 1 uur

    public function __construct() {
        if (!file_exists('../cache')) {
            mkdir('../cache', 0777, true);
        }
    }

    private function fetchWithCache($url, $cache_key) {
        $cache_file = $this->cache_dir . $cache_key . '.json';

        // Check if cache exists and is still valid
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $this->cache_time)) {
            return json_decode(file_get_contents($cache_file), true);
        }

        $attempts = 0;
        $max_attempts = 3;
        $response = false;
        $last_errno = 0;
        $last_error = '';
        $request_ok = false;

        while ($attempts < $max_attempts) {
            $attempts++;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            $response = curl_exec($ch);
            $last_errno = curl_errno($ch);
            $last_error = curl_error($ch);
            $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response !== false && $http_code >= 200 && $http_code < 300) {
                $request_ok = true;
                break;
            }

            $is_tls_error = in_array($last_errno, [35, 51, 53, 58, 59, 60, 66, 77, 80, 82, 83, 90], true);
            if ($is_tls_error) {
                error_log(sprintf(
                    '[OpenDataAPI] TLS-verificatie mislukt voor URL %s (errno=%d, error=%s)',
                    $url,
                    $last_errno,
                    $last_error
                ));
                return null;
            }

            if ($attempts < $max_attempts) {
                usleep($attempts * 250000);
            }
        }

        if ($request_ok && $response !== false) {
            // Save to cache
            if (!is_dir($this->cache_dir)) {
                mkdir($this->cache_dir, 0777, true);
            }
            file_put_contents($cache_file, $response);
            return json_decode($response, true);
        }

        error_log(sprintf(
            '[OpenDataAPI] Request mislukt voor URL %s na %d pogingen (errno=%d, error=%s)',
            $url,
            $max_attempts,
            $last_errno,
            $last_error
        ));

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

    public function getGerelateerdePolitiekeDebatten($thema_slug) {
        // Simuleer API call voor gerelateerde debatten
        return [
            [
                'type' => 'Plenair debat',
                'titel' => 'Hoofdlijnen ' . ucfirst($thema_slug),
                'datum' => date('Y-m-d', strtotime('+2 weeks')),
                'beschrijving' => 'Plenair debat over de hoofdlijnen van het ' . $thema_slug . ' beleid.',
                'slug' => 'hoofdlijnen-' . $thema_slug
            ],
            [
                'type' => 'Commissiedebat',
                'titel' => 'Technische briefing ' . ucfirst($thema_slug),
                'datum' => date('Y-m-d', strtotime('+1 week')),
                'beschrijving' => 'Technische briefing over recente ontwikkelingen in het ' . $thema_slug . '.',
                'slug' => 'technische-briefing-' . $thema_slug
            ],
            [
                'type' => 'Rondetafelgesprek',
                'titel' => 'Expertmeeting ' . ucfirst($thema_slug),
                'datum' => date('Y-m-d', strtotime('+3 weeks')),
                'beschrijving' => 'Rondetafelgesprek met experts over de toekomst van ' . $thema_slug . '.',
                'slug' => 'expertmeeting-' . $thema_slug
            ]
        ];
    }

    public function getThemaNews($thema_slug) {
        // Simuleer API call voor thema-gerelateerd nieuws
        return [
            [
                'title' => 'Nieuwe ontwikkelingen in ' . ucfirst($thema_slug),
                'description' => 'Een analyse van de laatste ontwikkelingen op het gebied van ' . $thema_slug . '.',
                'source' => 'NOS',
                'publishedAt' => date('Y-m-d', strtotime('-1 day')),
                'url' => '#',
                'image' => 'https://via.placeholder.com/800x400'
            ],
            [
                'title' => 'Experts bezorgd over ' . ucfirst($thema_slug),
                'description' => 'Verschillende experts uiten hun zorgen over de huidige staat van ' . $thema_slug . ' in Nederland.',
                'source' => 'NU.nl',
                'publishedAt' => date('Y-m-d', strtotime('-2 days')),
                'url' => '#',
                'image' => 'https://via.placeholder.com/800x400'
            ],
            [
                'title' => 'Kamer debatteert over ' . ucfirst($thema_slug),
                'description' => 'Vandaag vindt er een belangrijk debat plaats in de Tweede Kamer over ' . $thema_slug . '.',
                'source' => 'RTL Nieuws',
                'publishedAt' => date('Y-m-d'),
                'url' => '#',
                'image' => 'https://via.placeholder.com/800x400'
            ]
        ];
    }

    private function getThemaIcon($thema) {
        $icons = [
            'klimaat' => '🌍',
            'wonen' => '🏠',
            'economie' => '💶',
            'zorg' => '🏥',
            'onderwijs' => '📚',
            'arbeid' => '💼',
            'default' => '📋'
        ];

        foreach ($icons as $keyword => $icon) {
            if (stripos($thema, $keyword) !== false) {
                return $icon;
            }
        }

        return $icons['default'];
    }
} 