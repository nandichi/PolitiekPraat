<?php
/**
 * MCP tools voor Nederlandse + Amerikaanse verkiezingen en historische
 * minister-presidenten / presidenten.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use Database;
use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;
use Throwable;

final class VerkiezingenTools
{
    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            // ---- Nederlandse Tweede-Kamer verkiezingen
            ToolBuilder::read(
                'list_nl_verkiezingen',
                "Lijst Nederlandse Tweede Kamer-verkiezingen (jaar, MP, kabinet, partij-uitslagen).",
                [
                    'type' => 'object',
                    'properties' => [
                        'since_year' => ['type' => 'integer'],
                        'until_year' => ['type' => 'integer'],
                        'limit'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 50],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_nl_verkiezingen']
            ),

            ToolBuilder::read(
                'get_nl_verkiezing',
                'Detail van één Nederlandse verkiezing op basis van jaar.',
                [
                    'type' => 'object',
                    'properties' => [
                        'jaar' => ['type' => 'integer', 'minimum' => 1800, 'maximum' => 2100],
                    ],
                    'required' => ['jaar'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_nl_verkiezing']
            ),

            // ---- Amerikaanse presidentsverkiezingen
            ToolBuilder::read(
                'list_us_verkiezingen',
                "Lijst Amerikaanse presidentsverkiezingen (winnaar, kiesmannen, partij).",
                [
                    'type' => 'object',
                    'properties' => [
                        'since_year' => ['type' => 'integer'],
                        'until_year' => ['type' => 'integer'],
                        'limit'      => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 50],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_us_verkiezingen']
            ),

            ToolBuilder::read(
                'get_us_verkiezing',
                'Detail van één Amerikaanse presidentsverkiezing op basis van jaar.',
                [
                    'type' => 'object',
                    'properties' => [
                        'jaar' => ['type' => 'integer'],
                    ],
                    'required' => ['jaar'],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_us_verkiezing']
            ),

            // ---- Minister-presidenten Nederland
            ToolBuilder::read(
                'list_nl_minister_presidenten',
                'Lijst van Nederlandse minister-presidenten (huidige + historisch).',
                [
                    'type' => 'object',
                    'properties' => [
                        'only_current' => ['type' => 'boolean', 'default' => false],
                        'partij'       => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_nl_mp']
            ),

            ToolBuilder::read(
                'get_nl_minister_president',
                'Detail van één minister-president (op naam of nummer).',
                [
                    'type' => 'object',
                    'properties' => [
                        'naam'   => ['type' => 'string'],
                        'nummer' => ['type' => 'integer'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_nl_mp']
            ),

            // ---- Amerikaanse presidenten
            ToolBuilder::read(
                'list_us_presidenten',
                'Lijst Amerikaanse presidenten (huidige + historisch).',
                [
                    'type' => 'object',
                    'properties' => [
                        'only_current' => ['type' => 'boolean', 'default' => false],
                        'partij'       => ['type' => 'string'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'list_us_presidenten']
            ),

            ToolBuilder::read(
                'get_us_president',
                'Detail van één Amerikaanse president (op naam of nummer).',
                [
                    'type' => 'object',
                    'properties' => [
                        'naam'   => ['type' => 'string'],
                        'nummer' => ['type' => 'integer'],
                    ],
                    'additionalProperties' => false,
                ],
                [self::class, 'get_us_president']
            ),
        ];
    }

    // ====== HANDLERS =======

    public static function list_nl_verkiezingen(array $args, ?array $ctx): array
    {
        $db = new Database();
        $where = [];
        $params = [];
        if (!empty($args['since_year'])) { $where[] = 'jaar >= :sy'; $params[':sy'] = (int) $args['since_year']; }
        if (!empty($args['until_year'])) { $where[] = 'jaar <= :uy'; $params[':uy'] = (int) $args['until_year']; }
        $limit = max(1, min(100, (int) ($args['limit'] ?? 50)));

        $sql = 'SELECT id, jaar, minister_president, minister_president_partij, kabinet_naam, kabinet_type,
                       coalitie_partijen, totaal_zetels, opkomst_percentage, verkiezingsdata,
                       grootste_winnaar, grootste_verliezer
                FROM nederlandse_verkiezingen';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= " ORDER BY jaar DESC LIMIT {$limit}";
        try {
            $db->query($sql);
            foreach ($params as $k => $v) $db->bind($k, $v);
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) { return ['verkiezingen' => [], 'error' => 'table_not_available']; }
        return ['verkiezingen' => array_map([self::class, 'hydrateJson'], $rows)];
    }

    public static function get_nl_verkiezing(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM nederlandse_verkiezingen WHERE jaar = :j LIMIT 1');
        $db->bind(':j', (int) $args['jaar']);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'verkiezing_not_found');
        return self::hydrateJson($row);
    }

    public static function list_us_verkiezingen(array $args, ?array $ctx): array
    {
        $db = new Database();
        $where = [];
        $params = [];
        if (!empty($args['since_year'])) { $where[] = 'jaar >= :sy'; $params[':sy'] = (int) $args['since_year']; }
        if (!empty($args['until_year'])) { $where[] = 'jaar <= :uy'; $params[':uy'] = (int) $args['until_year']; }
        $limit = max(1, min(100, (int) ($args['limit'] ?? 50)));

        $sql = 'SELECT id, jaar, winnaar, winnaar_partij, winnaar_kiesmannen,
                       verliezer, verliezer_partij, verliezer_kiesmannen,
                       opkomst_percentage, verkiezingsdata
                FROM amerikaanse_verkiezingen';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= " ORDER BY jaar DESC LIMIT {$limit}";
        try {
            $db->query($sql);
            foreach ($params as $k => $v) $db->bind($k, $v);
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) { return ['verkiezingen' => [], 'error' => 'table_not_available']; }
        return ['verkiezingen' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_us_verkiezing(array $args, ?array $ctx): array
    {
        $db = new Database();
        $db->query('SELECT * FROM amerikaanse_verkiezingen WHERE jaar = :j LIMIT 1');
        $db->bind(':j', (int) $args['jaar']);
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'verkiezing_not_found');
        return self::hydrateJson($row);
    }

    public static function list_nl_mp(array $args, ?array $ctx): array
    {
        $db = new Database();
        $where = []; $params = [];
        if (!empty($args['only_current'])) $where[] = 'is_huidig = 1';
        if (!empty($args['partij'])) { $where[] = 'partij = :p'; $params[':p'] = (string) $args['partij']; }
        $sql = 'SELECT id, minister_president_nummer, naam, partij, periode_start, periode_eind, is_huidig, foto_url
                FROM nederlandse_ministers_presidenten';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY periode_start DESC';
        try {
            $db->query($sql);
            foreach ($params as $k => $v) $db->bind($k, $v);
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) { return ['minister_presidenten' => [], 'error' => 'table_not_available']; }
        return ['minister_presidenten' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_nl_mp(array $args, ?array $ctx): array
    {
        $db = new Database();
        if (!empty($args['nummer'])) {
            $db->query('SELECT * FROM nederlandse_ministers_presidenten WHERE minister_president_nummer = :n LIMIT 1');
            $db->bind(':n', (int) $args['nummer']);
        } elseif (!empty($args['naam'])) {
            $db->query('SELECT * FROM nederlandse_ministers_presidenten WHERE naam LIKE :n OR volledige_naam LIKE :n LIMIT 1');
            $db->bind(':n', '%' . $args['naam'] . '%');
        } else {
            throw new McpException(-32602, 'Geef naam of nummer');
        }
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'mp_not_found');
        return self::hydrateJson($row);
    }

    public static function list_us_presidenten(array $args, ?array $ctx): array
    {
        $db = new Database();
        $where = []; $params = [];
        if (!empty($args['only_current'])) $where[] = 'is_huidig = 1';
        if (!empty($args['partij'])) { $where[] = 'partij = :p'; $params[':p'] = (string) $args['partij']; }
        $sql = 'SELECT id, president_nummer, naam, partij, periode_start, periode_eind, is_huidig, foto_url
                FROM amerikaanse_presidenten';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY president_nummer DESC';
        try {
            $db->query($sql);
            foreach ($params as $k => $v) $db->bind($k, $v);
            $rows = $db->resultSet() ?: [];
        } catch (Throwable $e) { return ['presidenten' => [], 'error' => 'table_not_available']; }
        return ['presidenten' => array_map(static fn($r) => (array) $r, $rows)];
    }

    public static function get_us_president(array $args, ?array $ctx): array
    {
        $db = new Database();
        if (!empty($args['nummer'])) {
            $db->query('SELECT * FROM amerikaanse_presidenten WHERE president_nummer = :n LIMIT 1');
            $db->bind(':n', (int) $args['nummer']);
        } elseif (!empty($args['naam'])) {
            $db->query('SELECT * FROM amerikaanse_presidenten WHERE naam LIKE :n OR volledige_naam LIKE :n LIMIT 1');
            $db->bind(':n', '%' . $args['naam'] . '%');
        } else {
            throw new McpException(-32602, 'Geef naam of nummer');
        }
        $row = $db->single();
        if (!$row) throw new McpException(-32001, 'president_not_found');
        return self::hydrateJson($row);
    }

    /** @param object|array $row */
    private static function hydrateJson($row): array
    {
        $out = (array) $row;
        foreach ($out as $k => $v) {
            if (is_string($v) && $v !== '' && (str_starts_with($v, '[') || str_starts_with($v, '{'))) {
                $dec = json_decode($v, true);
                if (is_array($dec)) $out[$k] = $dec;
            }
        }
        return $out;
    }
}
