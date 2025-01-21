<?php

class PoliticalPartyAPI {
    private $baseUrl = 'https://openstate.eu/api/v1';
    
    /**
     * Haalt de lijst van actieve politieke partijen op
     */
    public function getActiveParties() {
        // Voorlopig statische data, later kunnen we dit vervangen door echte API calls
        return [
            [
                'name' => 'VVD',
                'fullName' => 'Volkspartij voor Vrijheid en Democratie',
                'leader' => 'Dilan Yeşilgöz-Zegerius',
                'seats' => 24,
                'color' => '#041E42'
            ],
            [
                'name' => 'NSC',
                'fullName' => 'Nieuw Sociaal Contract',
                'leader' => 'Pieter Omtzigt',
                'seats' => 20,
                'color' => '#1E407C'
            ],
            [
                'name' => 'GroenLinks-PvdA',
                'fullName' => 'GroenLinks-PvdA',
                'leader' => 'Frans Timmermans',
                'seats' => 25,
                'color' => '#A0D233'
            ],
            [
                'name' => 'PVV',
                'fullName' => 'Partij voor de Vrijheid',
                'leader' => 'Geert Wilders',
                'seats' => 37,
                'color' => '#0E2D66'
            ],
            [
                'name' => 'D66',
                'fullName' => 'Democraten 66',
                'leader' => 'Rob Jetten',
                'seats' => 9,
                'color' => '#01B9E5'
            ],
            [
                'name' => 'CDA',
                'fullName' => 'Christen-Democratisch Appèl',
                'leader' => 'Henri Bontenbal',
                'seats' => 5,
                'color' => '#007B5F'
            ]
        ];
    }

    /**
     * Haalt de laatste peilingen op
     */
    public function getLatestPolls() {
        // Voorlopig statische data
        return [
            'datum' => date('Y-m-d'),
            'bron' => 'Peilingwijzer',
            'partijen' => $this->getActiveParties()
        ];
    }
} 