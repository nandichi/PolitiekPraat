<?php
/**
 * Genereert /.well-known/agent-skills/index.json met SHA256 digests voor
 * elke SKILL.md. Run dit script na elke wijziging aan een SKILL.md.
 *
 *   php scripts/build-agent-skills-index.php
 *
 * Schema: https://github.com/cloudflare/agent-skills-discovery-rfc
 */

declare(strict_types=1);

$basePath  = dirname(__DIR__);
$skillsDir = $basePath . '/.well-known/agent-skills';
$indexFile = $skillsDir . '/index.json';
$baseUrl   = 'https://politiekpraat.nl/.well-known/agent-skills';

if (!is_dir($skillsDir)) {
    fwrite(STDERR, "Skills dir niet gevonden: $skillsDir\n");
    exit(1);
}

$skills = [];
$entries = scandir($skillsDir) ?: [];

foreach ($entries as $entry) {
    if ($entry === '.' || $entry === '..' || $entry === 'index.json') {
        continue;
    }

    $entryPath = $skillsDir . '/' . $entry;
    if (!is_dir($entryPath)) {
        continue;
    }

    $skillFile = $entryPath . '/SKILL.md';
    if (!is_readable($skillFile)) {
        fwrite(STDERR, "Overslaan: geen SKILL.md in $entryPath\n");
        continue;
    }

    $contents = file_get_contents($skillFile);
    if ($contents === false) {
        fwrite(STDERR, "Kan niet lezen: $skillFile\n");
        continue;
    }

    $name = $entry;
    $description = '';
    $version = '1.0.0';
    $language = 'nl-NL';

    if (preg_match('/^---\s*\n(.*?)\n---/s', $contents, $m)) {
        $frontmatter = $m[1];
        foreach (preg_split('/\n(?=\S)/', $frontmatter) as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $key = trim($parts[0]);
            $value = trim(preg_replace("/^[>\s|]+/", '', $parts[1]));
            $value = trim(str_replace(["\n", "\r"], ' ', $value));
            $value = preg_replace('/\s+/', ' ', $value);

            switch ($key) {
                case 'name':        $name = $value; break;
                case 'description': $description = $value; break;
                case 'version':     $version = $value; break;
                case 'language':    $language = $value; break;
            }
        }
    }

    $digest = 'sha256-' . base64_encode(hash('sha256', $contents, true));

    $skills[] = [
        'name'        => $name,
        'type'        => 'markdown',
        'description' => $description,
        'url'         => $baseUrl . '/' . $entry . '/SKILL.md',
        'version'     => $version,
        'language'    => $language,
        'digest'      => $digest,
    ];
}

usort($skills, static fn($a, $b) => strcmp($a['name'], $b['name']));

$index = [
    '$schema'     => 'https://agentskills.io/schemas/index.v0.2.0.json',
    'publisher'   => [
        'name'    => 'PolitiekPraat',
        'url'     => 'https://politiekpraat.nl',
        'contact' => 'https://politiekpraat.nl/contact',
    ],
    'generatedAt' => date('c'),
    'skills'      => $skills,
];

$json = json_encode($index, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if ($json === false) {
    fwrite(STDERR, "JSON encoding fout\n");
    exit(1);
}

if (file_put_contents($indexFile, $json . "\n") === false) {
    fwrite(STDERR, "Kan niet schrijven: $indexFile\n");
    exit(1);
}

echo "Geschreven: $indexFile (" . count($skills) . " skills)\n";
