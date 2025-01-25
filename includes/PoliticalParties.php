<?php

class PoliticalParties {
    private $parties = [
        'pvv' => [
            'name' => 'PVV',
            'color' => '#0a2896',
            'website' => 'pvv.nl',
            'seats' => 37,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#0a2896" text-anchor="middle" dominant-baseline="central">PVV</text></svg>'
        ],
        'gl-pvda' => [
            'name' => 'GroenLinks-PvdA',
            'color' => '#8cc63f',
            'website' => 'groenlinks-pvda.nl',
            'seats' => 25,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="40" font-family="Arial Black" font-size="25" fill="#8cc63f" text-anchor="middle">GL</text><text x="50" y="70" font-family="Arial Black" font-size="25" fill="#e31e24" text-anchor="middle">PvdA</text></svg>'
        ],
        'vvd' => [
            'name' => 'VVD',
            'color' => '#ff7404',
            'website' => 'vvd.nl',
            'seats' => 24,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#ff7404" text-anchor="middle" dominant-baseline="central">VVD</text></svg>'
        ],
        'nsc' => [
            'name' => 'NSC',
            'color' => '#123b6d',
            'website' => 'nieuwsociaalcontract.nl',
            'seats' => 20,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#123b6d" text-anchor="middle" dominant-baseline="central">NSC</text></svg>'
        ],
        'd66' => [
            'name' => 'D66',
            'color' => '#00b13c',
            'website' => 'd66.nl',
            'seats' => 9,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#00b13c" text-anchor="middle" dominant-baseline="central">D66</text></svg>'
        ],
        'bbb' => [
            'name' => 'BBB',
            'color' => '#6e9c3c',
            'website' => 'boerburgerbeweging.nl',
            'seats' => 7,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#6e9c3c" text-anchor="middle" dominant-baseline="central">BBB</text></svg>'
        ],
        'cda' => [
            'name' => 'CDA',
            'color' => '#007749',
            'website' => 'cda.nl',
            'seats' => 5,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#007749" text-anchor="middle" dominant-baseline="central">CDA</text></svg>'
        ],
        'sp' => [
            'name' => 'SP',
            'color' => '#ee1f27',
            'website' => 'sp.nl',
            'seats' => 5,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#ee1f27" text-anchor="middle" dominant-baseline="central">SP</text></svg>'
        ],
        'cu' => [
            'name' => 'ChristenUnie',
            'color' => '#00a0dc',
            'website' => 'christenunie.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#00a0dc" text-anchor="middle" dominant-baseline="central">CU</text></svg>'
        ],
        'denk' => [
            'name' => 'DENK',
            'color' => '#009f41',
            'website' => 'bewegingdenk.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#009f41" text-anchor="middle" dominant-baseline="central">DENK</text></svg>'
        ],
        'fvd' => [
            'name' => 'FVD',
            'color' => '#841818',
            'website' => 'fvd.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#841818" text-anchor="middle" dominant-baseline="central">FVD</text></svg>'
        ],
        'pvdd' => [
            'name' => 'Partij voor de Dieren',
            'color' => '#006c2e',
            'website' => 'partijvoordedieren.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="30" fill="#006c2e" text-anchor="middle" dominant-baseline="central">PvdD</text></svg>'
        ],
        'sgp' => [
            'name' => 'SGP',
            'color' => '#254399',
            'website' => 'sgp.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#254399" text-anchor="middle" dominant-baseline="central">SGP</text></svg>'
        ],
        'volt' => [
            'name' => 'Volt',
            'color' => '#502379',
            'website' => 'voltnederland.org',
            'seats' => 2,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="40" fill="#502379" text-anchor="middle" dominant-baseline="central">VOLT</text></svg>'
        ],
        'ja21' => [
            'name' => 'JA21',
            'color' => '#01557D',
            'website' => 'ja21.nl',
            'seats' => 1,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="35" fill="#01557D" text-anchor="middle" dominant-baseline="central">JA21</text></svg>'
        ]
    ];

    public function getParties() {
        return $this->parties;
    }

    public function getPartyLogos() {
        $logos = [];
        foreach ($this->parties as $slug => $party) {
            $logos[$slug] = 'data:image/svg+xml;base64,' . base64_encode($party['logo']);
        }
        return $logos;
    }
} 