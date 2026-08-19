<?php
/**
 * Redactionele context en geverifieerde kerncijfers voor de Midterms 2026.
 *
 * Peildatum: 19 augustus 2026. Vluchtige marktprijzen en nieuws staan niet in
 * dit bestand: die komen uit de live caches. Bronlinks blijven zichtbaar in de
 * publieke interface zodat cijfers controleerbaar zijn.
 */
return [
    'verified_at' => '2026-08-19 19:00:00',
    'election_date' => '2026-11-03',
    'congress' => '120e Congres',
    'status' => 'upcoming',

    'house' => [
        'seats' => 435,
        'majority' => 218,
        'current' => [
            'R' => 218,
            'D' => 212,
            'I' => 1,
            'vacant' => 4,
        ],
        'rating_summary' => [
            'safe_d' => 184,
            'likely_d' => 11,
            'lean_d' => 10,
            'tossup' => 19,
            'lean_r' => 9,
            'likely_r' => 20,
            'safe_r' => 182,
        ],
        'boundary_note' => 'Voor de verkiezingen van 2026 wijzigen de districtsgrenzen in meerdere staten. De nieuwe landelijke kaart voor het 120e Congres wordt pas later in augustus en september volledig door het Census Bureau gepubliceerd.',
    ],

    'senate' => [
        'seats' => 100,
        'races' => 35,
        'regular_races' => 33,
        'special_races' => 2,
        'current' => [
            'R' => 53,
            'D' => 45,
            'I' => 2,
        ],
        'defending' => [
            'R' => 22,
            'D' => 13,
        ],
        'dem_net_needed' => 4,
        'rating_summary' => [
            'safe_d' => 9,
            'likely_d' => 1,
            'lean_d' => 3,
            'tossup' => 4,
            'lean_r' => 2,
            'likely_r' => 2,
            'safe_r' => 14,
        ],
    ],

    'governors' => [
        'races' => 36,
        'current' => [
            'R' => 26,
            'D' => 24,
        ],
    ],

    'sources' => [
        [
            'name' => 'Federal Election Commission',
            'short' => 'FEC',
            'url' => 'https://www.fec.gov/introduction-campaign-finance/election-results-and-voting-information/',
            'description' => 'Verkiezingskalender en federale verkiezingsinformatie',
        ],
        [
            'name' => 'U.S. Senate',
            'short' => 'Senate.gov',
            'url' => 'https://www.senate.gov/senators/Class_II.htm',
            'description' => 'Class II-zetels en huidige senatoren',
        ],
        [
            'name' => 'U.S. House Press Gallery',
            'short' => 'House',
            'url' => 'https://pressgallery.house.gov/member-data/party-breakdown',
            'description' => 'Actuele partijverdeling in het Huis',
        ],
        [
            'name' => 'Cook Political Report',
            'short' => 'Cook',
            'url' => 'https://www.cookpolitical.com/ratings/senate-race-ratings',
            'description' => 'Handmatig gecontroleerde race-ratings',
        ],
        [
            'name' => 'U.S. Census Bureau',
            'short' => 'Census',
            'url' => 'https://www.census.gov/programs-surveys/decennial-census/about/rdo/congressional-districts.120th_Congress.html',
            'description' => 'Officiële grenzen voor het 120e Congres',
        ],
        [
            'name' => 'Ballotpedia',
            'short' => 'Ballotpedia',
            'url' => 'https://ballotpedia.org/2026_ballot_measures',
            'description' => 'Gecertificeerde statewide ballot measures',
        ],
    ],

    'image' => [
        'path' => '/public/images/midterms/capitol-east-front.jpg',
        'alt' => 'Het Capitool in Washington bij zonsondergang',
        'credit' => 'Architect of the Capitol',
        'source_url' => 'https://commons.wikimedia.org/wiki/File:Flickr_-_USCapitol_-_East_Front_of_the_U.S._Capitol.jpg',
        'license' => 'Publiek domein',
    ],
];
