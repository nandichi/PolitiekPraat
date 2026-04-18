<?php
/**
 * MCP tools voor Nederlandse politieke partijen (`political_parties`).
 * Alle tools zijn read-only (publiek).
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use Database;
use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;
use Throwable;

final class PartijenTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'list_partijen',
                'Lijst van Nederlandse politieke partijen (actief in Tweede Kamer). Gebruik `party_key` als slug-achtige identifier.',
                [
                    'type' => 'object',
                    'properties' => [
                        'only_active' => ['type' => 'boolean', 'default' => true],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_partijen']
            ),

            ToolBuilder::read(
                'get_partij',
                'Detail van een politieke partij (inclusief standpunten, peilingen, leader-info, perspectives) op basis van party_key.',
                [
                    'type' => 'object',
                    'properties' => [
                        'party_key' => ['type' => 'string', 'minLength' => 1],
                        'name'      => ['type' => 'string', 'minLength' => 1],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_partij']
            ),

            ToolBuilder::read(
                'compare_partijen',
                'Vergelijk twee of meer partijen op basis van standpunten, zetels en kleur. Geef party_keys of namen.',
                [
                    'type' => 'object',
                    'properties' => [
                        'party_keys' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 2, 'maxItems' => 10],
                    ],
                    'required' => ['party_keys'],
                    'additionalProperties' => false,
                ],
                [self::class, 'compare_partijen']
            ),

            ToolBuilder::read(
                'list_standpunten_voor_partij',
                'Lijst van alle standpunten van een partij (uit de stemwijzer). Matcht op `short_name` in stemwijzer_parties of op partij-naam.',
                [
                    'type' => 'object',
                    'properties' => [
                        'party_key' => ['type' => 'string'],
                        'name'      => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_standpunten_voor_partij']
            ),

            ToolBuilder::read(
                'get_leader_info',
                'Leider-informatie (naam, foto, bio) van een partij.',
                [
                    'type' => 'object',
                    'properties' => [
                        'party_key' => ['type' => 'string'],
                    ],
                    'required' => ['party_key'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_leader_info']
            ),
        ];
    }

    // ---------- HANDLERS ----------

    public static function list_partijen(array $args, ?array $ctx): array
    {
        $db = new Database();
        $onlyActive = $args['only_active'] ?? true;
        $sql = 'SELECT id, party_key, name, leader, logo, leader_photo, color, current_seats, tk2023_seats, is_active
                FROM political_parties';
        if ($onlyActive) $sql .= ' WHERE is_active = 1';
        $sql .= ' ORDER BY current_seats DESC, name ASC';
        $db->query($sql);
        $rows = $db->resultSet() ?: [];
        return ['partijen' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_partij(array $args, ?array $ctx): array
    {
        $db = new Database();
        if (!empty($args['party_key'])) {
            $db->query('SELECT * FROM political_parties WHERE party_key = :k LIMIT 1');
            $db->bind(':k', (string) $args['party_key']);
        } elseif (!empty($args['name'])) {
            $db->query('SELECT * FROM political_parties WHERE name = :n LIMIT 1');
            $db->bind(':n', (string) $args['name']);
        } else {
            throw new McpException(-32602, 'Geef party_key of name');
        }
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'partij_not_found');

        $out = (array) $row;
        foreach (['standpoints', 'polling', 'perspectives'] as $jsonCol) {
            if (isset($out[$jsonCol]) && is_string($out[$jsonCol]) && $out[$jsonCol] !== '') {
                $dec = json_decode($out[$jsonCol], true);
                if (is_array($dec)) $out[$jsonCol] = $dec;
            }
        }
        return $out;
    }

    public static function compare_partijen(array $args, ?array $ctx): array
    {
        $keys = array_values(array_filter(array_map('strval', $args['party_keys'] ?? [])));
        if (count($keys) < 2) throw new McpException(-32602, 'Geef minstens 2 party_keys');

        $db = new Database();
        $place = [];
        foreach ($keys as $i => $k) $place[] = ':k' . $i;
        $db->query('SELECT party_key, name, leader, current_seats, color, standpoints
                    FROM political_parties WHERE party_key IN (' . implode(',', $place) . ')');
        foreach ($keys as $i => $k) $db->bind(':k' . $i, $k);
        $rows = $db->resultSet() ?: [];

        $result = [];
        foreach ($rows as $r) {
            $a = (array) $r;
            $dec = is_string($a['standpoints'] ?? '') ? json_decode($a['standpoints'], true) : null;
            if (is_array($dec)) $a['standpoints'] = $dec;
            $result[] = $a;
        }
        return ['partijen' => $result, 'count' => count($result)];
    }

    public static function list_standpunten_voor_partij(array $args, ?array $ctx): array
    {
        $db = new Database();
        $partyId = null;

        if (!empty($args['party_key'])) {
            // Ga via political_parties → name → matchen op stemwijzer_parties.short_name of name.
            $db->query('SELECT name FROM political_parties WHERE party_key = :k LIMIT 1');
            $db->bind(':k', (string) $args['party_key']);
            $pp = $db->single();
            if ($pp) {
                $db->query('SELECT id FROM stemwijzer_parties WHERE name = :n OR short_name = :k LIMIT 1');
                $db->bind(':n', $pp->name);
                $db->bind(':k', (string) $args['party_key']);
                $sw = $db->single();
                if ($sw) $partyId = (int) $sw->id;
            }
        }
        if ($partyId === null && !empty($args['name'])) {
            $db->query('SELECT id FROM stemwijzer_parties WHERE name = :n OR short_name = :n LIMIT 1');
            $db->bind(':n', (string) $args['name']);
            $sw = $db->single();
            if ($sw) $partyId = (int) $sw->id;
        }

        if ($partyId === null) return ['standpunten' => [], 'note' => 'Partij niet gevonden in stemwijzer_parties.'];

        $db->query('SELECT q.id AS question_id, q.title AS stelling, p.position, p.explanation
                    FROM stemwijzer_positions p
                    JOIN stemwijzer_questions q ON p.question_id = q.id
                    WHERE p.party_id = :pid AND q.is_active = 1
                    ORDER BY q.order_number, q.id');
        $db->bind(':pid', $partyId);
        $rows = $db->resultSet() ?: [];
        return ['standpunten' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_leader_info(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT party_key, name AS party_name, leader, leader_photo, leader_info
                    FROM political_parties WHERE party_key = :k LIMIT 1');
        $db->bind(':k', (string) $args['party_key']);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'partij_not_found');
        return (array) $row;
    }
}
