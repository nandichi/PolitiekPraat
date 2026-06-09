<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/PartyModel.php';
require_once __DIR__ . '/data/partijmeter_dataset.php';

/**
 * PartijMeterController
 *
 * Verzorgt de data voor de landelijke PartijMeter: stellingen + standpunten uit
 * de eigen partijmeter_* tabellen, verrijkt met partij-info (naam, logo, kleur,
 * leider, zetels) uit political_parties. Volledig losgekoppeld van de
 * (gemeentelijke) stemwijzer/Ede-dataset.
 *
 * Valt terug op de git-tracked dataset (includes/data/partijmeter_dataset.php)
 * wanneer de database (nog) leeg is, zodat de pagina altijd werkt.
 */
class PartijMeterController
{
    private Database $db;
    private PartyModel $partyModel;

    /** Geldige antwoord-/standpuntwaarden. */
    private array $validPositions = ['eens', 'neutraal', 'oneens'];

    public function __construct()
    {
        $this->db = new Database();
        $this->partyModel = new PartyModel();
    }

    /**
     * Volledige payload voor de frontend.
     *
     * @return array{meta:array,questions:array,parties:array}
     */
    public function getData(): array
    {
        $dataset = pp_partijmeter_dataset();

        // Actieve partijen uit de databron (gesorteerd op zetels).
        $dbParties = $this->partyModel->getAllParties();
        $compassCoords = $this->loadCompassCoords();

        $questions = $this->loadQuestions();

        // Beperk standpunten tot partijen die echt actief in de Kamer zitten.
        $activeKeys = array_keys($dbParties);
        foreach ($questions as &$q) {
            $q['positions'] = array_intersect_key($q['positions'], array_flip($activeKeys));
        }
        unset($q);

        // Bereken de kompas-coordinaten van elke partij op basis van dezelfde
        // as-getagde stellingen, zodat partijen en gebruiker op één meetlat staan.
        $partyCompass = $this->computePartyCompass($questions, $activeKeys);

        $parties = [];
        foreach ($dbParties as $key => $p) {
            $parties[] = [
                'key'         => $key,
                'name'        => $p['name'] ?? $key,
                'leader'      => $p['leader'] ?? null,
                'logo'        => $p['logo'] ?? null,
                'leaderPhoto' => $p['leader_photo'] ?? null,
                'color'       => $p['color'] ?? '#1F3A5F',
                'seats'       => (int) ($p['current_seats'] ?? 0),
                'compass'     => $partyCompass[$key] ?? ($compassCoords[$key] ?? ['x' => 0, 'y' => 0]),
            ];
        }

        return [
            'meta' => [
                'totalQuestions' => count($questions),
                'totalParties'   => count($parties),
                'disclaimer'     => $dataset['disclaimer'] ?? '',
                'peildatum'      => $dataset['peildatum'] ?? '',
            ],
            'questions' => array_values($questions),
            'parties'   => $parties,
        ];
    }

    /**
     * Laad de stellingen + standpunten. Eerst uit de DB; bij een lege/ontbrekende
     * tabel terugvallen op de git-tracked dataset.
     *
     * @return array<int,array>
     */
    private function loadQuestions(): array
    {
        try {
            $this->db->query('SELECT id, theme, title, explanation, axis_economic, axis_cultural, order_number FROM partijmeter_questions WHERE is_active = 1 ORDER BY order_number ASC, id ASC');
            $rows = $this->db->resultSet();
        } catch (Throwable $e) {
            $rows = [];
        }

        if (empty($rows)) {
            return $this->loadQuestionsFromDataset();
        }

        // Alle standpunten in één query ophalen en groeperen.
        try {
            $this->db->query('SELECT question_id, party_key, position, explanation, source_url FROM partijmeter_positions');
            $positionRows = $this->db->resultSet();
        } catch (Throwable $e) {
            $positionRows = [];
        }

        $byQuestion = [];
        foreach ($positionRows as $pr) {
            $byQuestion[(int) $pr->question_id][$pr->party_key] = [
                'p' => $pr->position,
                'e' => $pr->explanation,
                's' => $pr->source_url,
            ];
        }

        $questions = [];
        foreach ($rows as $row) {
            $qid = (int) $row->id;
            $positions = [];
            foreach (($byQuestion[$qid] ?? []) as $partyKey => $pos) {
                $positions[$partyKey] = $pos;
            }
            $questions[] = [
                'id'            => $qid,
                'theme'         => $row->theme,
                'title'         => $row->title,
                'explanation'   => $row->explanation,
                'axisEconomic'  => (int) $row->axis_economic,
                'axisCultural'  => (int) $row->axis_cultural,
                'positions'     => $positions,
            ];
        }

        return $questions;
    }

    /**
     * Fallback: bouw de stellingen op uit de centrale dataset.
     *
     * @return array<int,array>
     */
    private function loadQuestionsFromDataset(): array
    {
        $dataset = pp_partijmeter_dataset();
        $questions = [];
        $id = 1;
        foreach ($dataset['questions'] as $q) {
            $positions = [];
            foreach ($q['positions'] as $partyKey => $pos) {
                $positions[$partyKey] = [
                    'p' => $pos['p'],
                    'e' => $pos['e'] ?? null,
                    's' => $q['source'] ?? null,
                ];
            }
            $questions[] = [
                'id'           => $id++,
                'theme'        => $q['theme'],
                'title'        => $q['title'],
                'explanation'  => $q['explanation'] ?? null,
                'axisEconomic' => (int) ($q['axis_economic'] ?? 0),
                'axisCultural' => (int) ($q['axis_cultural'] ?? 0),
                'positions'    => $positions,
            ];
        }
        return $questions;
    }

    /**
     * Bereken per partij de kompas-positie (-100..100) op basis van de
     * as-getagde stellingen. economisch: +rechts/-links; cultureel: +conservatief/-progressief.
     *
     * @param array<int,array> $questions
     * @param string[] $partyKeys
     * @return array<string,array{x:int,y:int}>
     */
    private function computePartyCompass(array $questions, array $partyKeys): array
    {
        $valueMap = ['eens' => 1, 'neutraal' => 0, 'oneens' => -1];
        $result = [];

        foreach ($partyKeys as $key) {
            $sumE = 0.0; $cntE = 0;
            $sumC = 0.0; $cntC = 0;
            foreach ($questions as $q) {
                $pos = $q['positions'][$key]['p'] ?? null;
                if ($pos === null || !isset($valueMap[$pos])) {
                    continue;
                }
                $val = $valueMap[$pos];
                if ((int) $q['axisEconomic'] !== 0) {
                    $sumE += $val * (int) $q['axisEconomic'];
                    $cntE++;
                }
                if ((int) $q['axisCultural'] !== 0) {
                    $sumC += $val * (int) $q['axisCultural'];
                    $cntC++;
                }
            }
            $result[$key] = [
                'x' => $cntE > 0 ? (int) round(100 * $sumE / $cntE) : 0,
                'y' => $cntC > 0 ? (int) round(100 * $sumC / $cntC) : 0,
            ];
        }

        return $result;
    }

    /**
     * Optionele kompas-coordinaten uit de redactionele profielen (fallback).
     *
     * @return array<string,array{x:int,y:int}>
     */
    private function loadCompassCoords(): array
    {
        $file = __DIR__ . '/data/partijen_profiel.php';
        if (!is_readable($file)) {
            return [];
        }
        $profiles = require $file;
        $out = [];
        if (is_array($profiles)) {
            foreach ($profiles as $key => $profile) {
                if (!empty($profile['compass'])) {
                    $out[$key] = [
                        'x' => (int) ($profile['compass']['x'] ?? 0),
                        'y' => (int) ($profile['compass']['y'] ?? 0),
                    ];
                }
            }
        }
        return $out;
    }

    /**
     * Sla een resultaat anoniem op en geef het deel-id terug.
     *
     * @param array $answers   index => 'eens'|'neutraal'|'oneens'
     * @param array $weights   index => gewicht
     * @param array $results   berekende uitslag (ranking, kompas, etc.)
     * @return string|null     share_id of null bij falen
     */
    public function saveResult(array $answers, array $weights, array $results): ?string
    {
        $shareId = $this->generateShareId();
        $sessionId = session_id() ?: bin2hex(random_bytes(8));
        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        try {
            $this->db->query(
                'INSERT INTO partijmeter_results (session_id, share_id, user_id, answers, weights, results, ip_address, user_agent)
                 VALUES (:session_id, :share_id, :user_id, :answers, :weights, :results, :ip, :ua)'
            );
            $this->db->bind(':session_id', $sessionId);
            $this->db->bind(':share_id', $shareId);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':answers', json_encode($answers, JSON_UNESCAPED_UNICODE));
            $this->db->bind(':weights', json_encode($weights, JSON_UNESCAPED_UNICODE));
            $this->db->bind(':results', json_encode($results, JSON_UNESCAPED_UNICODE));
            $this->db->bind(':ip', $ip);
            $this->db->bind(':ua', $userAgent);
            $this->db->execute();
        } catch (Throwable $e) {
            error_log('[PartijMeter] saveResult faalt: ' . $e->getMessage());
            return null;
        }

        return $shareId;
    }

    /**
     * Haal een opgeslagen resultaat op via het deel-id.
     */
    public function getResultByShareId(string $shareId): ?array
    {
        try {
            $this->db->query('SELECT share_id, answers, weights, results, completed_at FROM partijmeter_results WHERE share_id = :share_id LIMIT 1');
            $this->db->bind(':share_id', $shareId);
            $row = $this->db->single();
        } catch (Throwable $e) {
            return null;
        }

        if (!$row) {
            return null;
        }

        return [
            'share_id'     => $row->share_id,
            'answers'      => json_decode($row->answers ?? '[]', true) ?: [],
            'weights'      => json_decode($row->weights ?? '[]', true) ?: [],
            'results'      => json_decode($row->results ?? '[]', true) ?: [],
            'completed_at' => $row->completed_at,
        ];
    }

    /**
     * Genereer een uniek, URL-veilig deel-id.
     */
    private function generateShareId(): string
    {
        for ($i = 0; $i < 6; $i++) {
            $candidate = substr(bin2hex(random_bytes(8)), 0, 12);
            try {
                $this->db->query('SELECT 1 FROM partijmeter_results WHERE share_id = :share_id LIMIT 1');
                $this->db->bind(':share_id', $candidate);
                if (!$this->db->single()) {
                    return $candidate;
                }
            } catch (Throwable $e) {
                return $candidate;
            }
        }
        return substr(bin2hex(random_bytes(12)), 0, 16);
    }

    /**
     * Eenvoudige statistieken voor de admin.
     *
     * @return array{questions:int,positions:int,results:int}
     */
    public function getStats(): array
    {
        $count = function (string $table): int {
            try {
                $this->db->query("SELECT COUNT(*) AS c FROM {$table}");
                return (int) ($this->db->single()->c ?? 0);
            } catch (Throwable $e) {
                return 0;
            }
        };

        return [
            'questions' => $count('partijmeter_questions'),
            'positions' => $count('partijmeter_positions'),
            'results'   => $count('partijmeter_results'),
        ];
    }
}
