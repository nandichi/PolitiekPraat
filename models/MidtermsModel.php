<?php
/**
 * MidtermsModel - data-toegang voor de Midterms 2026 sectie.
 *
 * Strategie: lees uit de database; als een tabel leeg is of de DB niet
 * beschikbaar is, val terug op de seed-data in
 * includes/data/midterms_2026_seed.php. Zo rendert de pagina altijd, ook
 * lokaal zonder database of vlak na een verse deploy (voor het seeden).
 */
class MidtermsModel
{
    /** @var Database|null */
    private $db;

    /** @var array|null Lazy-geladen seed/fallback data */
    private $seedCache = null;

    /** Datum van de verkiezingen (Election Day). */
    public const ELECTION_DATE = '2026-11-03';

    public function __construct($database = null)
    {
        $this->db = $database;
    }

    // ---------------------------------------------------------------------
    // Races
    // ---------------------------------------------------------------------

    /**
     * Alle races van een kamer (senate|house|governor), gesorteerd.
     */
    public function getRacesByChamber(string $chamber): array
    {
        $rows = $this->tryQuery(
            "SELECT * FROM midterms_races WHERE chamber = :chamber
             ORDER BY is_competitive DESC, sort_order ASC, state_name ASC, district ASC",
            [':chamber' => $chamber]
        );

        if ($rows === null || count($rows) === 0) {
            return $this->seedRaces($chamber);
        }

        return array_map([$this, 'formatRace'], $rows);
    }

    /**
     * Competitieve races (optioneel beperkt tot een kamer).
     */
    public function getCompetitiveRaces(?string $chamber = null, ?int $limit = null): array
    {
        $sql = "SELECT * FROM midterms_races WHERE is_competitive = 1";
        $params = [];
        if ($chamber !== null) {
            $sql .= " AND chamber = :chamber";
            $params[':chamber'] = $chamber;
        }
        $sql .= " ORDER BY FIELD(rating,'tossup','lean_d','lean_r','likely_d','likely_r','safe_d','safe_r'), state_name ASC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $rows = $this->tryQuery($sql, $params);

        if ($rows === null || count($rows) === 0) {
            $all = [];
            foreach (['senate', 'house', 'governor'] as $c) {
                if ($chamber !== null && $c !== $chamber) {
                    continue;
                }
                foreach ($this->seedRaces($c) as $race) {
                    if (!empty($race->is_competitive)) {
                        $all[] = $race;
                    }
                }
            }
            usort($all, static function ($a, $b) {
                $order = ['tossup' => 0, 'lean_d' => 1, 'lean_r' => 2, 'likely_d' => 3, 'likely_r' => 4, 'safe_d' => 5, 'safe_r' => 6];
                return ($order[$a->rating] ?? 9) <=> ($order[$b->rating] ?? 9);
            });
            if ($limit !== null) {
                $all = array_slice($all, 0, $limit);
            }
            return $all;
        }

        return array_map([$this, 'formatRace'], $rows);
    }

    /**
     * Bouw de ratings-map voor de interactieve kaart.
     *
     * Senaat/Gouverneur: keyed op state FIPS (matcht us-atlas state ids).
     * Huis: keyed op GEOID (statefips + districtnr, 4 tekens, bv. "0401").
     *
     * @return array<string,array>
     */
    public function getRatingsMap(string $chamber): array
    {
        $states = self::usStates();
        $byCodeFips = [];
        foreach ($states as $code => $info) {
            $byCodeFips[$code] = $info['fips'];
        }

        $map = [];

        if ($chamber === 'house') {
            // Begin met baseline (huidige partij per district), daarna overrides.
            foreach ($this->houseBaseline() as $key => $party) {
                // $key formaat: "AZ-01"
                [$sc, $dist] = array_pad(explode('-', $key, 2), 2, '');
                $fips = $byCodeFips[$sc] ?? null;
                if ($fips === null) {
                    continue;
                }
                $geoid = $fips . str_pad($dist === 'AL' ? '00' : $dist, 2, '0', STR_PAD_LEFT);
                $map[$geoid] = [
                    'state' => $sc,
                    'district' => $dist,
                    'rating' => $party === 'D' ? 'safe_d' : ($party === 'R' ? 'safe_r' : 'tossup'),
                    'party' => $party,
                    'competitive' => false,
                    'name' => $sc . '-' . $dist,
                ];
            }

            foreach ($this->getRacesByChamber('house') as $race) {
                $sc = $race->state_code;
                $dist = $race->district ?? '';
                $fips = $byCodeFips[$sc] ?? null;
                if ($fips === null) {
                    continue;
                }
                $geoid = $fips . str_pad($dist === 'AL' ? '00' : $dist, 2, '0', STR_PAD_LEFT);
                $map[$geoid] = [
                    'state' => $sc,
                    'district' => $dist,
                    'rating' => $race->rating,
                    'party' => $race->incumbent_party,
                    'competitive' => (bool) $race->is_competitive,
                    'name' => $race->state_name . ' ' . ($dist === 'AL' ? '(at-large)' : $dist),
                    'href' => '/midterms-2026/huis',
                ];
            }

            return $map;
        }

        // Senaat & Gouverneur: per staat
        foreach ($this->getRacesByChamber($chamber) as $race) {
            $fips = $byCodeFips[$race->state_code] ?? null;
            if ($fips === null) {
                continue;
            }
            $map[$fips] = [
                'state' => $race->state_code,
                'rating' => $race->rating,
                'party' => $race->incumbent_party,
                'competitive' => (bool) $race->is_competitive,
                'name' => $race->state_name,
                'incumbent' => $race->incumbent_name,
                'open' => (bool) $race->is_open,
            ];
        }

        return $map;
    }

    // ---------------------------------------------------------------------
    // Tijdlijn
    // ---------------------------------------------------------------------

    public function getTimeline(?int $limit = null, ?string $category = null): array
    {
        $sql = "SELECT * FROM midterms_timeline WHERE is_published = 1";
        $params = [];
        if ($category !== null) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }
        $sql .= " ORDER BY event_date DESC, sort_order ASC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $rows = $this->tryQuery($sql, $params);

        if ($rows === null || count($rows) === 0) {
            $items = $this->seedSection('timeline');
            $items = array_map(static fn ($i) => (object) $i, $items);
            if ($category !== null) {
                $items = array_values(array_filter($items, static fn ($i) => ($i->category ?? '') === $category));
            }
            usort($items, static fn ($a, $b) => strcmp((string) ($b->event_date ?? ''), (string) ($a->event_date ?? '')));
            if ($limit !== null) {
                $items = array_slice($items, 0, $limit);
            }
            return $items;
        }

        return $rows;
    }

    // ---------------------------------------------------------------------
    // Referenda
    // ---------------------------------------------------------------------

    public function getReferenda(): array
    {
        $rows = $this->tryQuery(
            "SELECT * FROM midterms_referenda WHERE is_published = 1 ORDER BY sort_order ASC, state_name ASC",
            []
        );

        if ($rows === null || count($rows) === 0) {
            return array_map(static fn ($i) => (object) $i, $this->seedSection('referenda'));
        }

        return $rows;
    }

    // ---------------------------------------------------------------------
    // Odds (Polymarket cache)
    // ---------------------------------------------------------------------

    /**
     * Alle odds, keyed op market_key. Outcomes worden gedecodeerd.
     */
    public function getOdds(): array
    {
        $rows = $this->tryQuery("SELECT * FROM midterms_odds_cache", []);

        $result = [];
        if ($rows !== null && count($rows) > 0) {
            foreach ($rows as $row) {
                $row->outcomes = json_decode((string) ($row->outcomes_json ?? '[]'), true) ?: [];
                unset($row->outcomes_json);
                $result[$row->market_key] = $row;
            }
            return $result;
        }

        // Fallback uit seed
        foreach ($this->seedSection('odds') as $key => $odd) {
            $odd['market_key'] = $key;
            $odd['is_fallback'] = true;
            $result[$key] = (object) $odd;
        }
        return $result;
    }

    public function getOdd(string $marketKey): ?object
    {
        $all = $this->getOdds();
        return $all[$marketKey] ?? null;
    }

    // ---------------------------------------------------------------------
    // Nieuws (Brave cache)
    // ---------------------------------------------------------------------

    public function getNews(int $limit = 12): array
    {
        $rows = $this->tryQuery(
            "SELECT * FROM midterms_news_cache WHERE is_published = 1
             ORDER BY published_at DESC, fetched_at DESC LIMIT " . (int) $limit,
            []
        );

        if ($rows === null || count($rows) === 0) {
            $items = array_map(static fn ($i) => (object) $i, $this->seedSection('news'));
            return array_slice($items, 0, $limit);
        }

        return $rows;
    }

    // ---------------------------------------------------------------------
    // Samenvattende statistieken voor de hub
    // ---------------------------------------------------------------------

    public function getDaysUntilElection(): int
    {
        $now = new DateTimeImmutable('now');
        $election = new DateTimeImmutable(self::ELECTION_DATE);
        $diff = (int) $now->diff($election)->format('%r%a');
        return $diff;
    }

    // ---------------------------------------------------------------------
    // Interne helpers
    // ---------------------------------------------------------------------

    /**
     * Voer een query uit met named params. Retourneert null bij een fout of
     * als er geen DB beschikbaar is, zodat de caller naar seed kan vallen.
     */
    private function tryQuery(string $sql, array $params): ?array
    {
        if (!$this->db) {
            return null;
        }
        try {
            $this->db->query($sql);
            foreach ($params as $k => $v) {
                $this->db->bind($k, $v);
            }
            return $this->db->resultSet();
        } catch (Throwable $e) {
            error_log('MidtermsModel::tryQuery fout: ' . $e->getMessage());
            return null;
        }
    }

    private function formatRace(object $race): object
    {
        if (isset($race->candidates_json)) {
            $race->candidates = json_decode((string) $race->candidates_json, true) ?: [];
        }
        $race->is_competitive = (int) ($race->is_competitive ?? 0);
        $race->is_open = (int) ($race->is_open ?? 0);
        return $race;
    }

    private function seedRaces(string $chamber): array
    {
        $races = $this->seedSection('races');
        $out = [];
        foreach ($races as $race) {
            if (($race['chamber'] ?? '') === $chamber) {
                $out[] = (object) $race;
            }
        }
        // Sorteer: competitief eerst, dan staat
        usort($out, static function ($a, $b) {
            $ca = (int) ($a->is_competitive ?? 0);
            $cb = (int) ($b->is_competitive ?? 0);
            if ($ca !== $cb) {
                return $cb <=> $ca;
            }
            return strcmp((string) ($a->state_name ?? ''), (string) ($b->state_name ?? ''));
        });
        return $out;
    }

    private function houseBaseline(): array
    {
        $seed = $this->seed();
        return $seed['house_baseline'] ?? [];
    }

    private function seedSection(string $key): array
    {
        $seed = $this->seed();
        return $seed[$key] ?? [];
    }

    private function seed(): array
    {
        if ($this->seedCache !== null) {
            return $this->seedCache;
        }
        $path = __DIR__ . '/../includes/data/midterms_2026_seed.php';
        if (is_readable($path)) {
            $data = require $path;
            $this->seedCache = is_array($data) ? $data : [];
        } else {
            $this->seedCache = [];
        }
        return $this->seedCache;
    }

    /**
     * Referentiedata voor de 50 staten + DC: FIPS-code en naam.
     * FIPS-codes matchen de ids in de us-atlas states TopoJSON.
     */
    public static function usStates(): array
    {
        return [
            'AL' => ['fips' => '01', 'name' => 'Alabama'],
            'AK' => ['fips' => '02', 'name' => 'Alaska'],
            'AZ' => ['fips' => '04', 'name' => 'Arizona'],
            'AR' => ['fips' => '05', 'name' => 'Arkansas'],
            'CA' => ['fips' => '06', 'name' => 'Californië'],
            'CO' => ['fips' => '08', 'name' => 'Colorado'],
            'CT' => ['fips' => '09', 'name' => 'Connecticut'],
            'DE' => ['fips' => '10', 'name' => 'Delaware'],
            'DC' => ['fips' => '11', 'name' => 'District of Columbia'],
            'FL' => ['fips' => '12', 'name' => 'Florida'],
            'GA' => ['fips' => '13', 'name' => 'Georgia'],
            'HI' => ['fips' => '15', 'name' => 'Hawaii'],
            'ID' => ['fips' => '16', 'name' => 'Idaho'],
            'IL' => ['fips' => '17', 'name' => 'Illinois'],
            'IN' => ['fips' => '18', 'name' => 'Indiana'],
            'IA' => ['fips' => '19', 'name' => 'Iowa'],
            'KS' => ['fips' => '20', 'name' => 'Kansas'],
            'KY' => ['fips' => '21', 'name' => 'Kentucky'],
            'LA' => ['fips' => '22', 'name' => 'Louisiana'],
            'ME' => ['fips' => '23', 'name' => 'Maine'],
            'MD' => ['fips' => '24', 'name' => 'Maryland'],
            'MA' => ['fips' => '25', 'name' => 'Massachusetts'],
            'MI' => ['fips' => '26', 'name' => 'Michigan'],
            'MN' => ['fips' => '27', 'name' => 'Minnesota'],
            'MS' => ['fips' => '28', 'name' => 'Mississippi'],
            'MO' => ['fips' => '29', 'name' => 'Missouri'],
            'MT' => ['fips' => '30', 'name' => 'Montana'],
            'NE' => ['fips' => '31', 'name' => 'Nebraska'],
            'NV' => ['fips' => '32', 'name' => 'Nevada'],
            'NH' => ['fips' => '33', 'name' => 'New Hampshire'],
            'NJ' => ['fips' => '34', 'name' => 'New Jersey'],
            'NM' => ['fips' => '35', 'name' => 'New Mexico'],
            'NY' => ['fips' => '36', 'name' => 'New York'],
            'NC' => ['fips' => '37', 'name' => 'North Carolina'],
            'ND' => ['fips' => '38', 'name' => 'North Dakota'],
            'OH' => ['fips' => '39', 'name' => 'Ohio'],
            'OK' => ['fips' => '40', 'name' => 'Oklahoma'],
            'OR' => ['fips' => '41', 'name' => 'Oregon'],
            'PA' => ['fips' => '42', 'name' => 'Pennsylvania'],
            'RI' => ['fips' => '44', 'name' => 'Rhode Island'],
            'SC' => ['fips' => '45', 'name' => 'South Carolina'],
            'SD' => ['fips' => '46', 'name' => 'South Dakota'],
            'TN' => ['fips' => '47', 'name' => 'Tennessee'],
            'TX' => ['fips' => '48', 'name' => 'Texas'],
            'UT' => ['fips' => '49', 'name' => 'Utah'],
            'VT' => ['fips' => '50', 'name' => 'Vermont'],
            'VA' => ['fips' => '51', 'name' => 'Virginia'],
            'WA' => ['fips' => '53', 'name' => 'Washington'],
            'WV' => ['fips' => '54', 'name' => 'West Virginia'],
            'WI' => ['fips' => '55', 'name' => 'Wisconsin'],
            'WY' => ['fips' => '56', 'name' => 'Wyoming'],
        ];
    }

    /**
     * Labels (NL) en kleurklasse per rating.
     */
    public static function ratingMeta(): array
    {
        return [
            'safe_d'   => ['label' => 'Zeker Democratisch', 'short' => 'Zeker D', 'party' => 'D'],
            'likely_d' => ['label' => 'Waarschijnlijk Democratisch', 'short' => 'Waarsch. D', 'party' => 'D'],
            'lean_d'   => ['label' => 'Neigt Democratisch', 'short' => 'Neigt D', 'party' => 'D'],
            'tossup'   => ['label' => 'Onbeslist', 'short' => 'Toss-up', 'party' => null],
            'lean_r'   => ['label' => 'Neigt Republikeins', 'short' => 'Neigt R', 'party' => 'R'],
            'likely_r' => ['label' => 'Waarschijnlijk Republikeins', 'short' => 'Waarsch. R', 'party' => 'R'],
            'safe_r'   => ['label' => 'Zeker Republikeins', 'short' => 'Zeker R', 'party' => 'R'],
        ];
    }
}
