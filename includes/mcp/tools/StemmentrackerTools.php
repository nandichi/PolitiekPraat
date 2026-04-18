<?php
/**
 * MCP tools voor de Stemmentracker (Tweede Kamer moties + votes per partij).
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

final class StemmentrackerTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::read(
                'list_moties',
                'Lijst Tweede-Kamer moties uit de Stemmentracker. Filter op onderwerp, thema, indiener, uitslag of periode.',
                [
                    'type' => 'object',
                    'properties' => [
                        'onderwerp' => ['type' => 'string'],
                        'thema_id'  => ['type' => 'integer'],
                        'indiener'  => ['type' => 'string'],
                        'uitslag'   => ['type' => 'string', 'enum' => ['aangenomen', 'verworpen', 'ingetrokken']],
                        'since'     => ['type' => 'string', 'description' => 'YYYY-MM-DD (>=)'],
                        'until'     => ['type' => 'string', 'description' => 'YYYY-MM-DD (<)'],
                        'limit'     => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset'    => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_moties']
            ),

            ToolBuilder::read(
                'get_motie',
                "Detail van één motie, inclusief stemmen per partij (voor/tegen/niet_gestemd/afwezig) en gekoppelde thema's.",
                [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'minimum' => 1],
                    ],
                    'required' => ['id'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_motie']
            ),

            ToolBuilder::read(
                'list_themas',
                "Lijst thema's die in de Stemmentracker aan moties gekoppeld worden.",
                [
                    'type' => 'object',
                    'properties' => [
                        'only_active' => ['type' => 'boolean', 'default' => true],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_themas']
            ),

            ToolBuilder::read(
                'get_thema',
                "Detail van één thema met bijbehorende moties (laatste N).",
                [
                    'type' => 'object',
                    'properties' => [
                        'id'    => ['type' => 'integer'],
                        'name'  => ['type' => 'string'],
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_thema']
            ),

            ToolBuilder::read(
                'get_partij_stemgedrag',
                'Hoe heeft een partij gestemd op moties? Filter op thema en periode.',
                [
                    'type' => 'object',
                    'properties' => [
                        'party_key' => ['type' => 'string'],
                        'party_id'  => ['type' => 'integer'],
                        'thema_id'  => ['type' => 'integer'],
                        'since'     => ['type' => 'string'],
                        'until'     => ['type' => 'string'],
                        'limit'     => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                        'offset'    => ['type' => 'integer', 'minimum' => 0, 'default' => 0],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_partij_stemgedrag']
            ),
        ];
    }

    // ---------- HANDLERS ----------

    public static function list_moties(array $args, ?array $ctx): array
    {
        $db = new Database();
        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));

        $where  = ['m.is_active = 1'];
        $params = [];
        $join   = '';

        if (!empty($args['onderwerp'])) {
            $where[] = '(m.onderwerp LIKE :q OR m.title LIKE :q OR m.description LIKE :q)';
            $params[':q'] = '%' . $args['onderwerp'] . '%';
        }
        if (!empty($args['indiener'])) {
            $where[] = 'm.indiener LIKE :ind';
            $params[':ind'] = '%' . $args['indiener'] . '%';
        }
        if (!empty($args['uitslag'])) {
            $where[] = 'm.uitslag = :uit';
            $params[':uit'] = $args['uitslag'];
        }
        if (!empty($args['since'])) {
            $where[] = 'm.datum_stemming >= :since';
            $params[':since'] = (string) $args['since'];
        }
        if (!empty($args['until'])) {
            $where[] = 'm.datum_stemming < :until';
            $params[':until'] = (string) $args['until'];
        }
        if (!empty($args['thema_id'])) {
            $join = ' JOIN stemmentracker_motie_themas mt ON mt.motie_id = m.id ';
            $where[] = 'mt.thema_id = :tid';
            $params[':tid'] = (int) $args['thema_id'];
        }

        $sql = "SELECT DISTINCT m.id, m.title, m.motie_nummer, m.kamerstuk_nummer, m.datum_stemming,
                       m.onderwerp, m.indiener, m.uitslag, m.stemming_type, m.kamer_stuk_url
                FROM stemmentracker_moties m" . $join . "
                WHERE " . implode(' AND ', $where) . "
                ORDER BY m.datum_stemming DESC, m.id DESC
                LIMIT {$limit} OFFSET {$offset}";
        $db->query($sql);
        foreach ($params as $k => $v) $db->bind($k, $v);
        $rows = $db->resultSet() ?: [];

        return ['moties' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_motie(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM stemmentracker_moties WHERE id = :id LIMIT 1');
        $db->bind(':id', (int) $args['id']);
        $motie = $db->single();
        if (!$motie) throw new McpException(-32001, 'motie_not_found');

        $db->query('SELECT v.party_id, pp.party_key, pp.name AS party_name, v.vote, v.aantal_zetels, v.opmerking
                    FROM stemmentracker_votes v
                    LEFT JOIN political_parties pp ON pp.id = v.party_id
                    WHERE v.motie_id = :m
                    ORDER BY pp.name');
        $db->bind(':m', (int) $motie->id);
        $votes = $db->resultSet() ?: [];

        $db->query('SELECT t.id, t.name, t.color
                    FROM stemmentracker_motie_themas mt
                    JOIN stemmentracker_themas t ON t.id = mt.thema_id
                    WHERE mt.motie_id = :m');
        $db->bind(':m', (int) $motie->id);
        $themas = $db->resultSet() ?: [];

        $out = (array) $motie;
        $out['votes']  = array_map(static fn($r) => (array) $r, $votes);
        $out['themas'] = array_map(static fn($r) => (array) $r, $themas);
        return $out;
    }

    public static function list_themas(array $args, ?array $ctx): array
    {
        $db = new Database();
        $sql = 'SELECT id, name, description, color, is_active FROM stemmentracker_themas';
        if (($args['only_active'] ?? true)) $sql .= ' WHERE is_active = 1';
        $sql .= ' ORDER BY name ASC';
        $db->query($sql);
        $rows = $db->resultSet() ?: [];
        return ['themas' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_thema(array $args, ?array $ctx): array
    {
        $db = new Database();
        if (!empty($args['id'])) {
            $db->query('SELECT * FROM stemmentracker_themas WHERE id = :i LIMIT 1');
            $db->bind(':i', (int) $args['id']);
        } elseif (!empty($args['name'])) {
            $db->query('SELECT * FROM stemmentracker_themas WHERE name = :n LIMIT 1');
            $db->bind(':n', (string) $args['name']);
        } else {
            throw new McpException(-32602, 'Geef id of name');
        }
        $t = $db->single();
        if (!$t) throw new McpException(-32001, 'thema_not_found');

        $limit = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $db->query("SELECT m.id, m.title, m.datum_stemming, m.uitslag
                    FROM stemmentracker_motie_themas mt
                    JOIN stemmentracker_moties m ON m.id = mt.motie_id
                    WHERE mt.thema_id = :t AND m.is_active = 1
                    ORDER BY m.datum_stemming DESC
                    LIMIT {$limit}");
        $db->bind(':t', (int) $t->id);
        $moties = $db->resultSet() ?: [];

        $out = (array) $t;
        $out['moties'] = array_map(static fn($r) => (array) $r, $moties);
        return $out;
    }

    public static function get_partij_stemgedrag(array $args, ?array $ctx): array
    {
        $db = new Database();
        $partyId = null;
        if (!empty($args['party_id'])) $partyId = (int) $args['party_id'];
        if ($partyId === null && !empty($args['party_key'])) {
            $db->query('SELECT id FROM political_parties WHERE party_key = :k LIMIT 1');
            $db->bind(':k', (string) $args['party_key']);
            $pp = $db->single();
            if ($pp) $partyId = (int) $pp->id;
        }
        if ($partyId === null) throw new McpException(-32602, 'Geef party_id of party_key');

        $limit  = max(1, min(100, (int) ($args['limit'] ?? 20)));
        $offset = max(0, (int) ($args['offset'] ?? 0));

        $where  = ['v.party_id = :pid', 'm.is_active = 1'];
        $params = [':pid' => $partyId];
        $join   = '';
        if (!empty($args['thema_id'])) {
            $join = ' JOIN stemmentracker_motie_themas mt ON mt.motie_id = m.id ';
            $where[] = 'mt.thema_id = :tid';
            $params[':tid'] = (int) $args['thema_id'];
        }
        if (!empty($args['since'])) {
            $where[] = 'm.datum_stemming >= :since';
            $params[':since'] = (string) $args['since'];
        }
        if (!empty($args['until'])) {
            $where[] = 'm.datum_stemming < :until';
            $params[':until'] = (string) $args['until'];
        }

        $sql = "SELECT DISTINCT m.id, m.title, m.datum_stemming, m.uitslag, v.vote, v.aantal_zetels
                FROM stemmentracker_votes v
                JOIN stemmentracker_moties m ON m.id = v.motie_id" . $join . "
                WHERE " . implode(' AND ', $where) . "
                ORDER BY m.datum_stemming DESC
                LIMIT {$limit} OFFSET {$offset}";
        $db->query($sql);
        foreach ($params as $k => $v) $db->bind($k, $v);
        $rows = $db->resultSet() ?: [];

        return ['stemgedrag' => array_map(static fn($r) => (array) $r, $rows)];
    }
}
