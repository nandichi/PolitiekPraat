<?php
/**
 * MCP tools voor de PartijMeter / Stemwijzer.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use Database;
use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;
use PolitiekPraat\OAuth\Scopes;
use Throwable;

final class StemwijzerTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'list_stellingen',
                'Lijst van PartijMeter-stellingen met titel, context, linkse en rechtse visie.',
                [
                    'type' => 'object',
                    'properties' => [
                        'only_active' => ['type' => 'boolean', 'default' => true],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_stellingen']
            ),

            ToolBuilder::read(
                'get_stelling',
                'Detail van één stelling, met alle partijposities (eens / neutraal / oneens) + uitleg.',
                [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'minimum' => 1],
                    ],
                    'required' => ['id'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_stelling']
            ),

            ToolBuilder::read(
                'list_stemwijzer_parties',
                'Lijst partijen die meedoen aan de PartijMeter (stemwijzer_parties tabel).',
                [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                    'additionalProperties' => false,
                ],
                [self::class, 'list_stemwijzer_parties']
            ),

            ToolBuilder::read(
                'calculate_partijmeter_match',
                'Bereken de match met alle partijen op basis van antwoorden zonder op te slaan. Retourneert percentage per partij.',
                [
                    'type' => 'object',
                    'properties' => [
                        'answers' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'question_id' => ['type' => 'integer'],
                                    'answer'      => ['type' => 'string', 'enum' => ['eens', 'oneens', 'neutraal']],
                                    'weight'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 3, 'default' => 1],
                                ],
                                'required' => ['question_id', 'answer'],
                                'additionalProperties' => false,
                            ],
                            'minItems' => 1,
                        ],
                    ],
                    'required' => ['answers'],
                    'additionalProperties' => false,
                ],
                [self::class, 'calculate_partijmeter_match']
            ),

            ToolBuilder::read(
                'get_partijmeter_result_by_share_id',
                'Haal een eerder opgeslagen PartijMeter-resultaat op via share_id.',
                [
                    'type' => 'object',
                    'properties' => [
                        'share_id' => ['type' => 'string', 'minLength' => 1],
                    ],
                    'required' => ['share_id'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_partijmeter_result_by_share_id']
            ),

            // Write (bestaand) - ondersteunt ook anoniem via share_id.
            ToolBuilder::write(
                'save_partijmeter_result',
                'Sla PartijMeter antwoorden op en retourneer share_id.',
                [
                    'type' => 'object',
                    'properties' => [
                        'answers' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'question_id' => ['type' => 'integer'],
                                    'answer'      => ['type' => 'string', 'enum' => ['eens', 'oneens', 'neutraal']],
                                    'weight'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 3],
                                ],
                                'required' => ['question_id', 'answer'],
                                'additionalProperties' => false,
                            ],
                        ],
                    ],
                    'required' => ['answers'],
                    'additionalProperties' => false,
                ],
                [Scopes::PARTIJMETER_WRITE],
                [self::class, 'save_partijmeter_result']
            ),
        ];
    }

    // ---------- HANDLERS ----------

    public static function list_stellingen(array $args, ?array $ctx): array
    {
        $db = new Database();
        $sql = 'SELECT id, title, description, context, left_view, right_view, order_number, is_active
                FROM stemwijzer_questions';
        if (($args['only_active'] ?? true)) $sql .= ' WHERE is_active = 1';
        $sql .= ' ORDER BY order_number ASC, id ASC';
        $db->query($sql);
        $rows = $db->resultSet() ?: [];
        return ['stellingen' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_stelling(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM stemwijzer_questions WHERE id = :id LIMIT 1');
        $db->bind(':id', (int) $args['id']);
        $q = $db->single();
        if (!$q) throw new McpException(-32001, 'stelling_not_found');

        $db->query('SELECT p.party_id, sp.name AS party_name, sp.short_name, p.position, p.explanation
                    FROM stemwijzer_positions p
                    JOIN stemwijzer_parties sp ON p.party_id = sp.id
                    WHERE p.question_id = :qid
                    ORDER BY sp.name');
        $db->bind(':qid', (int) $q->id);
        $positions = $db->resultSet() ?: [];

        $out = (array) $q;
        $out['positions'] = array_map(static fn($r) => (array) $r, $positions);
        return $out;
    }

    public static function list_stemwijzer_parties(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT id, name, short_name, logo_url, description FROM stemwijzer_parties ORDER BY name');
        $rows = $db->resultSet() ?: [];
        return ['parties' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function calculate_partijmeter_match(array $args, ?array $ctx): array
    {
        $answers = $args['answers'] ?? [];
        if (!is_array($answers) || count($answers) === 0) throw new McpException(-32602, 'Geen antwoorden');

        $db = new Database();
        $db->query('SELECT id, name, short_name FROM stemwijzer_parties ORDER BY name');
        $parties = $db->resultSet() ?: [];

        // Haal alle posities voor de gegeven vragen op in één query.
        $qids = array_values(array_unique(array_map(static fn($a) => (int) $a['question_id'], $answers)));
        if (empty($qids)) return ['matches' => []];

        $place = [];
        foreach ($qids as $i => $q) $place[] = ':q' . $i;
        $db->query('SELECT question_id, party_id, position FROM stemwijzer_positions WHERE question_id IN (' . implode(',', $place) . ')');
        foreach ($qids as $i => $q) $db->bind(':q' . $i, $q);
        $posRows = $db->resultSet() ?: [];

        // index: positions[question_id][party_id] = position
        $positions = [];
        foreach ($posRows as $r) {
            $positions[(int) $r->question_id][(int) $r->party_id] = (string) $r->position;
        }

        $matches = [];
        foreach ($parties as $p) {
            $score = 0;
            $max   = 0;
            foreach ($answers as $a) {
                $weight = max(1, (int) ($a['weight'] ?? 1));
                $qid    = (int) $a['question_id'];
                $user   = (string) $a['answer'];
                $max   += $weight * 2;
                $partyPos = $positions[$qid][(int) $p->id] ?? null;
                if ($partyPos === null) continue;
                if ($user === $partyPos) {
                    $score += $weight * 2;
                } elseif ($user === 'neutraal' || $partyPos === 'neutraal') {
                    $score += $weight; // deels overeen
                }
            }
            $pct = $max > 0 ? round(($score / $max) * 100, 1) : 0.0;
            $matches[] = [
                'party_id'   => (int) $p->id,
                'party_name' => $p->name,
                'short_name' => $p->short_name,
                'match_pct'  => $pct,
                'score'      => $score,
                'max_score'  => $max,
            ];
        }
        usort($matches, static fn($a, $b) => $b['match_pct'] <=> $a['match_pct']);
        return ['matches' => $matches];
    }

    public static function get_partijmeter_result_by_share_id(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT share_id, user_id, answers, results, created_at FROM stemwijzer_results WHERE share_id = :s LIMIT 1');
        $db->bind(':s', (string) $args['share_id']);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'result_not_found');

        $out = (array) $row;
        foreach (['answers', 'results'] as $col) {
            if (isset($out[$col]) && is_string($out[$col]) && $out[$col] !== '') {
                $dec = json_decode($out[$col], true);
                if ($dec !== null) $out[$col] = $dec;
            }
        }
        return $out;
    }

    public static function save_partijmeter_result(array $args, ?array $ctx): array
    {
        $db = new Database();
        $shareId = bin2hex(random_bytes(8));
        $userId = $ctx['user_id'] ?? null;

        try {
            // results alvast berekenen zodat de opgeslagen record bruikbaar is.
            $matches = self::calculate_partijmeter_match(['answers' => $args['answers']], $ctx)['matches'] ?? [];

            $db->query('INSERT INTO stemwijzer_results (share_id, user_id, answers, results, created_at)
                        VALUES (:s, :u, :a, :r, NOW())');
            $db->bind(':s', $shareId);
            $db->bind(':u', $userId);
            $db->bind(':a', json_encode($args['answers']));
            $db->bind(':r', json_encode($matches));
            $db->execute();
        } catch (Throwable $e) {
            throw new McpException(-32003, 'storage_error: ' . $e->getMessage());
        }

        return [
            'ok'       => true,
            'share_id' => $shareId,
            'url'      => (defined('URLROOT') ? rtrim(URLROOT, '/') : 'https://politiekpraat.nl') . '/resultaten/' . $shareId,
            'matches'  => $matches,
        ];
    }
}
