<?php

/**
 * Politieke partijen in de Tweede Kamer en hun standpunten per thema.
 *
 * Zetelverdeling: Tweede Kamerverkiezingen 29 oktober 2025 (150 zetels).
 * Coalitie: Kabinet-Jetten (D66, VVD, CDA), minderheidskabinet.
 * Bron: includes/data/nederlandse_verkiezingen_2025.php (canonieke databron op de site).
 *
 * De standpunten per thema staan in includes/data/standpunten.php en worden
 * gekoppeld via de partij-slug.
 */
class PoliticalParties {
    /**
     * Partijen in volgorde van zetelaantal (grootste eerst). De volgorde wordt
     * gebruikt in de weergave van de standpunten.
     */
    private $parties = [
        'd66' => [
            'name' => 'D66', 'slug' => 'd66', 'color' => '#00AE41', 'website' => 'd66.nl',
            'seats' => 26, 'spectrum' => 'Sociaal-liberaal', 'blok' => 'links', 'coalitie' => true,
            'logo_file' => '/public/images/party-logos/d66.png',
        ],
        'pvv' => [
            'name' => 'PVV', 'slug' => 'pvv', 'color' => '#0a2896', 'website' => 'pvv.nl',
            'seats' => 26, 'spectrum' => 'Nationaal-conservatief', 'blok' => 'rechts', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/pvv.png',
        ],
        'vvd' => [
            'name' => 'VVD', 'slug' => 'vvd', 'color' => '#FF7404', 'website' => 'vvd.nl',
            'seats' => 22, 'spectrum' => 'Liberaal', 'blok' => 'rechts', 'coalitie' => true,
            'logo_file' => '/public/images/party-logos/vvd.png',
        ],
        'gl-pvda' => [
            'name' => 'GroenLinks-PvdA', 'slug' => 'gl-pvda', 'color' => '#DF1278', 'website' => 'groenlinks-pvda.nl',
            'seats' => 20, 'spectrum' => 'Progressief-links', 'blok' => 'links', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/gl-pvda.png',
        ],
        'cda' => [
            'name' => 'CDA', 'slug' => 'cda', 'color' => '#007749', 'website' => 'cda.nl',
            'seats' => 18, 'spectrum' => 'Christendemocratisch', 'blok' => 'midden', 'coalitie' => true,
            'logo_file' => '/public/images/party-logos/cda.png',
        ],
        'ja21' => [
            'name' => 'JA21', 'slug' => 'ja21', 'color' => '#01557D', 'website' => 'ja21.nl',
            'seats' => 9, 'spectrum' => 'Conservatief-liberaal', 'blok' => 'rechts', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/ja21.png',
        ],
        'fvd' => [
            'name' => 'Forum voor Democratie', 'slug' => 'fvd', 'color' => '#841818', 'website' => 'fvd.nl',
            'seats' => 7, 'spectrum' => 'Radicaal-rechts', 'blok' => 'rechts', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/fvd.png',
        ],
        'bbb' => [
            'name' => 'BBB', 'slug' => 'bbb', 'color' => '#769B00', 'website' => 'boerburgerbeweging.nl',
            'seats' => 4, 'spectrum' => 'Agrarisch', 'blok' => 'rechts', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/bbb.png',
        ],
        'denk' => [
            'name' => 'DENK', 'slug' => 'denk', 'color' => '#22A6B3', 'website' => 'bewegingdenk.nl',
            'seats' => 3, 'spectrum' => 'Links', 'blok' => 'links', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/denk.png',
        ],
        'cu' => [
            'name' => 'ChristenUnie', 'slug' => 'cu', 'color' => '#00a0dc', 'website' => 'christenunie.nl',
            'seats' => 3, 'spectrum' => 'Christelijk-sociaal', 'blok' => 'midden', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/christenunie.svg',
        ],
        'sp' => [
            'name' => 'SP', 'slug' => 'sp', 'color' => '#ee1f27', 'website' => 'sp.nl',
            'seats' => 3, 'spectrum' => 'Socialistisch', 'blok' => 'links', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/sp.png',
        ],
        'sgp' => [
            'name' => 'SGP', 'slug' => 'sgp', 'color' => '#E8650F', 'website' => 'sgp.nl',
            'seats' => 3, 'spectrum' => 'Reformatorisch', 'blok' => 'rechts', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/sgp.png',
        ],
        'pvdd' => [
            'name' => 'Partij voor de Dieren', 'slug' => 'pvdd', 'color' => '#006c2e', 'website' => 'partijvoordedieren.nl',
            'seats' => 3, 'spectrum' => 'Ecologisch-links', 'blok' => 'links', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/pvdd.png',
        ],
        '50plus' => [
            'name' => '50PLUS', 'slug' => '50plus', 'color' => '#92278F', 'website' => '50pluspartij.nl',
            'seats' => 2, 'spectrum' => 'Ouderenpartij', 'blok' => 'midden', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/50plus.svg',
        ],
        'volt' => [
            'name' => 'Volt', 'slug' => 'volt', 'color' => '#502379', 'website' => 'voltnederland.org',
            'seats' => 1, 'spectrum' => 'Pro-Europees', 'blok' => 'links', 'coalitie' => false,
            'logo_file' => '/public/images/party-logos/volt.png',
        ],
    ];

    /** @var array|null Cache van de standpunten-databron. */
    private $standpuntenData = null;

    /**
     * Laadt de standpunten-databron (thema-slug => [partij-slug => tekst]).
     */
    private function loadStandpunten(): array {
        if ($this->standpuntenData === null) {
            $file = __DIR__ . '/data/standpunten.php';
            $this->standpuntenData = is_file($file) ? (require $file) : [];
        }
        return $this->standpuntenData;
    }

    /**
     * Alle partijen (gesorteerd op zetels, grootste eerst).
     */
    public function getParties() {
        return $this->parties;
    }

    /**
     * Genereert eenvoudige tekstlogo's als data-URI (fallback voor plekken
     * zonder afbeeldingsbestand).
     */
    public function getPartyLogos() {
        $logos = [];
        foreach ($this->parties as $slug => $party) {
            $label = htmlspecialchars($party['name'], ENT_QUOTES);
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
                 . '<text x="50" y="50" font-family="Arial" font-size="22" fill="' . $party['color'] . '" '
                 . 'text-anchor="middle" dominant-baseline="central">' . $label . '</text></svg>';
            $logos[$slug] = 'data:image/svg+xml;base64,' . base64_encode($svg);
        }
        return $logos;
    }

    /**
     * Standpunten van alle partijen voor een thema, gesorteerd op zetels.
     * Geeft per partij de volledige metadata terug plus het standpunt.
     *
     * @return array<int,array<string,mixed>>
     */
    public function getStandpunten($themaKey) {
        $data = $this->loadStandpunten();
        $themaStandpunten = $data[$themaKey] ?? [];
        if (empty($themaStandpunten)) {
            return [];
        }

        $result = [];
        foreach ($this->parties as $slug => $party) {
            if (!isset($themaStandpunten[$slug])) {
                continue;
            }
            $result[] = array_merge($party, [
                'standpunt' => $themaStandpunten[$slug],
            ]);
        }
        return $result;
    }

    /**
     * Standpunten gefilterd op politiek blok (links/rechts/midden).
     * Geeft [partijnaam => standpunt] terug (compatibel met oudere weergaves).
     */
    private function getStandpuntenByBlok($themaKey, $blok) {
        $data = $this->loadStandpunten();
        $themaStandpunten = $data[$themaKey] ?? [];
        $result = [];
        foreach ($this->parties as $slug => $party) {
            if (isset($themaStandpunten[$slug]) && ($party['blok'] ?? '') === $blok) {
                $result[$party['name']] = $themaStandpunten[$slug];
            }
        }
        return $result;
    }

    public function getLinkseStandpunten($thema) {
        return $this->getStandpuntenByBlok($thema, 'links');
    }

    public function getRechtseStandpunten($thema) {
        return $this->getStandpuntenByBlok($thema, 'rechts');
    }
}
