<?php
/**
 * MCP Prompts voor PolitiekPraat.
 *
 * Prompts zijn herbruikbare instructie-sjablonen die agents (of de host
 * IDE) kunnen invoegen. Elke prompt heeft een naam, beschrijving, lijst
 * van argumenten en een `messages`-array die wordt gegenereerd in
 * {@see Prompts::get()}.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

require_once __DIR__ . '/McpException.php';

final class Prompts
{
    /**
     * Lijst van beschikbare prompts (zoals in `prompts/list`).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function list(): array
    {
        $defs = self::definitions();
        $out = [];
        foreach ($defs as $name => $p) {
            $out[] = [
                'name'        => $name,
                'description' => $p['description'],
                'arguments'   => $p['arguments'],
            ];
        }
        return $out;
    }

    /**
     * Bouw een prompt uit zijn naam + arguments. Volgt MCP `prompts/get`
     * spec: retourneert een `description` + `messages` array.
     *
     * @return array{description:string, messages: array<int, array<string,mixed>>}
     */
    public static function get(string $name, array $args): array
    {
        $defs = self::definitions();
        if (!isset($defs[$name])) throw new McpException(-32601, 'unknown_prompt: ' . $name);

        $missing = [];
        foreach ($defs[$name]['arguments'] as $arg) {
            if (!empty($arg['required']) && !isset($args[$arg['name']])) {
                $missing[] = $arg['name'];
            }
        }
        if ($missing) throw new McpException(-32602, 'missing_arguments: ' . implode(', ', $missing));

        $messages = ($defs[$name]['build'])($args);
        return [
            'description' => $defs[$name]['description'],
            'messages'    => $messages,
        ];
    }

    /**
     * Volledige interne registry. `build` retourneert een MCP messages-array.
     *
     * @return array<string, array{description:string, arguments:array, build:callable}>
     */
    private static function definitions(): array
    {
        return [
            'write_political_blog' => [
                'description' => 'Schrijf een politieke blog over een specifiek onderwerp, met een bepaalde toon en doelpubliek.',
                'arguments' => [
                    ['name' => 'topic',      'description' => 'Het onderwerp / de invalshoek', 'required' => true],
                    ['name' => 'tone',       'description' => 'neutraal | analytisch | opinie', 'required' => false],
                    ['name' => 'audience',   'description' => 'bv. "geïnteresseerde burgers", "studenten", "stemmers"', 'required' => false],
                    ['name' => 'word_count', 'description' => 'Aantal woorden (default ~600)', 'required' => false],
                ],
                'build' => static fn(array $a) => [[
                    'role'    => 'user',
                    'content' => [
                        'type' => 'text',
                        'text' => "Je bent een politiek redacteur voor PolitiekPraat.nl. Schrijf een Nederlandstalige blog over: \"" . $a['topic'] . "\".\n\n" .
                                  "Toon: " . ($a['tone'] ?? 'neutraal en analytisch') . "\n" .
                                  "Doelgroep: " . ($a['audience'] ?? 'geïnteresseerde Nederlandse kiezers') . "\n" .
                                  "Lengte: ongeveer " . ($a['word_count'] ?? 600) . " woorden.\n\n" .
                                  "Volg deze stappen:\n" .
                                  "1. Gebruik `search_all` om actuele context op te halen.\n" .
                                  "2. Gebruik `get_partij` of `get_partij_stemgedrag` om standpunten te onderbouwen.\n" .
                                  "3. (Optioneel) Genereer een illustratie via `generate_blog_image`.\n" .
                                  "4. Maak een draft via `create_blog_draft` met passende `tags` en `category_slug`.\n" .
                                  "5. Lever de slug terug aan de gebruiker zodat deze kan reviewen voor publicatie.",
                    ],
                ]],
            ],

            'analyze_partij' => [
                'description' => 'Analyseer één Nederlandse politieke partij op standpunten, stemgedrag en geschiedenis.',
                'arguments' => [
                    ['name' => 'party_key', 'description' => 'party_key uit `political_parties` (bv. vvd, gl-pvda)', 'required' => true],
                    ['name' => 'focus',     'description' => 'Optioneel thema, bv. "klimaat" of "migratie"', 'required' => false],
                ],
                'build' => static fn(array $a) => [[
                    'role'    => 'user',
                    'content' => [
                        'type' => 'text',
                        'text' => "Maak een gestructureerde analyse van de partij `" . $a['party_key'] . "` op PolitiekPraat.\n" .
                                  "Stappen:\n" .
                                  "1. Roep `get_partij` aan voor basisinfo, peilingen en standpunten.\n" .
                                  "2. Roep `list_standpunten_voor_partij` aan om standpunten op stellingen op te halen.\n" .
                                  "3. Roep `get_partij_stemgedrag` aan voor recente stemmingen" .
                                  (!empty($a['focus']) ? " (filter op thema: " . $a['focus'] . ")" : '') . ".\n" .
                                  "4. Vat samen in: identiteit, kerncoalitie van standpunten, opvallend stemgedrag, sterktes/zwaktes.",
                    ],
                ]],
            ],

            'compare_standpunten' => [
                'description' => 'Vergelijk de standpunten van meerdere partijen over één thema.',
                'arguments' => [
                    ['name' => 'party_keys', 'description' => 'Comma-separated party_keys, bv. "vvd,d66,gl-pvda"', 'required' => true],
                    ['name' => 'thema',      'description' => 'Naam of id van het thema', 'required' => false],
                ],
                'build' => static fn(array $a) => [[
                    'role'    => 'user',
                    'content' => [
                        'type' => 'text',
                        'text' => "Vergelijk deze partijen: " . $a['party_keys'] . ".\n" .
                                  ($a['thema'] ?? '' ? "Focus op het thema: " . $a['thema'] . ".\n" : '') .
                                  "Stappen:\n" .
                                  "1. Roep `compare_partijen` aan met deze party_keys.\n" .
                                  "2. Voor elk: `list_standpunten_voor_partij`.\n" .
                                  "3. Maak een tabel/markdown-overzicht waar partijen het eens en oneens zijn.",
                    ],
                ]],
            ],

            'summarize_news_day' => [
                'description' => 'Vat de belangrijkste politieke nieuwsberichten van de afgelopen N dagen samen in een dagoverzicht.',
                'arguments' => [
                    ['name' => 'days',            'description' => 'Aantal dagen terugkijken (default 1)', 'required' => false],
                    ['name' => 'publish_as_blog', 'description' => 'Indien "true": publiceer ook als draft via `create_blog_draft`.', 'required' => false],
                ],
                'build' => static fn(array $a) => [[
                    'role'    => 'user',
                    'content' => [
                        'type' => 'text',
                        'text' => "Maak een politiek dagoverzicht van de afgelopen " . ($a['days'] ?? 1) . " dag(en).\n" .
                                  "1. Gebruik `list_nieuws` met `since` = vandaag minus " . ($a['days'] ?? 1) . " dagen.\n" .
                                  "2. Groepeer per thema en bron-bias.\n" .
                                  "3. Maak een neutrale samenvatting (~400 woorden), geen mening.\n" .
                                  (($a['publish_as_blog'] ?? false) ? "4. Maak hiervan een draft via `create_blog_draft` (tags: dagoverzicht, nieuws).\n" : ''),
                    ],
                ]],
            ],
        ];
    }
}
