<?php
/**
 * Haalt Nederlandstalig nieuws over de midterms 2026 op via de Brave Search
 * API en schrijft het naar midterms_news_cache. Bedoeld om een paar keer per
 * dag via cron te draaien.
 *
 * De Brave-sleutel komt uit de environment-variabele POLITIEKPRAAT_BRAVE_API_KEY.
 * Door op een Nederlandse locale te zoeken zijn titel en omschrijving al in het
 * Nederlands; de omschrijving wordt als korte NL-intro opgeslagen, met bronlink.
 *
 * Gebruik:
 *   php scripts/midterms_fetch_news.php
 *   php scripts/midterms_fetch_news.php --dry-run
 *   POLITIEKPRAAT_BRAVE_API_KEY=xxx php scripts/midterms_fetch_news.php --dry-run
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

$DRY_RUN = in_array('--dry-run', $argv ?? [], true);

echo "=== Midterms nieuws fetch: " . date('Y-m-d H:i:s') . " ===\n";

$apiKey = getenv('POLITIEKPRAAT_BRAVE_API_KEY') ?: '';
if ($apiKey === '') {
    echo "FOUT: POLITIEKPRAAT_BRAVE_API_KEY niet gezet in de environment.\n";
    echo "Zet de sleutel in includes/env.local.php (productie) of exporteer hem voor een test.\n";
    exit(1);
}

// Nederlandstalige zoekopdrachten voor brede dekking.
$queries = [
    'Amerikaanse midterms 2026',
    'midterms 2026 Senaat',
    'Amerikaanse Congresverkiezingen 2026',
    'midterms 2026 Huis van Afgevaardigden',
    'Trump midterms 2026',
];

/**
 * Brave news search voor een query.
 */
function mt_brave_news(string $query, string $apiKey): array
{
    $url = 'https://api.search.brave.com/res/v1/news/search?'
        . http_build_query([
            'q' => $query,
            'country' => 'nl',
            'search_lang' => 'nl',
            'ui_lang' => 'nl-NL',
            'count' => 20,
            'spellcheck' => 0,
        ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'X-Subscription-Token: ' . $apiKey,
        ],
        CURLOPT_ENCODING => '',
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    unset($ch);

    if ($body === false || $code >= 400) {
        echo "  Brave HTTP-fout ({$code}) voor \"{$query}\": {$err}\n";
        return [];
    }
    $data = json_decode((string) $body, true);
    return is_array($data) && isset($data['results']) ? $data['results'] : [];
}

/**
 * Bepaal publicatiedatum uit Brave-velden (page_age of relatieve age).
 */
function mt_news_published(array $item): ?string
{
    foreach (['page_age', 'age'] as $field) {
        $val = $item[$field] ?? '';
        if ($val === '') {
            continue;
        }
        $ts = strtotime($val);
        if ($ts !== false) {
            return date('Y-m-d H:i:s', $ts);
        }
    }
    return null;
}

// ---------------------------------------------------------------------------
$collected = [];
foreach ($queries as $q) {
    echo "- query: {$q}\n";
    $results = mt_brave_news($q, $apiKey);
    echo "  resultaten: " . count($results) . "\n";
    foreach ($results as $item) {
        $url = $item['url'] ?? '';
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            continue;
        }
        $hash = sha1($url);
        if (isset($collected[$hash])) {
            continue;
        }
        $title = trim(strip_tags($item['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $desc = trim(strip_tags($item['description'] ?? ''));
        $source = $item['meta_url']['hostname'] ?? ($item['profile']['name'] ?? '');
        $source = preg_replace('/^www\./', '', (string) $source);
        $image = $item['thumbnail']['src'] ?? null;

        $collected[$hash] = [
            'url_hash' => $hash,
            'title' => mb_substr($title, 0, 300),
            'url' => mb_substr($url, 0, 500),
            'source' => mb_substr((string) $source, 0, 120),
            'intro_nl' => $desc !== '' ? $desc : null,
            'description' => $desc !== '' ? $desc : null,
            'image_url' => $image ? mb_substr((string) $image, 0, 500) : null,
            'published_at' => mt_news_published($item),
        ];
    }
    usleep(1100000); // vriendelijk voor de Brave-rate-limit
}

echo "Totaal uniek verzameld: " . count($collected) . "\n";

if ($DRY_RUN) {
    $i = 0;
    foreach ($collected as $row) {
        if ($i++ >= 8) {
            break;
        }
        echo "  [" . ($row['source'] ?: '?') . "] " . $row['title'] . "\n";
        if (!empty($row['intro_nl'])) {
            echo "     " . mb_substr($row['intro_nl'], 0, 120) . "\n";
        }
    }
    echo "=== Klaar (dry-run): {$i} getoond ===\n";
    exit(0);
}

try {
    $db = new Database();
} catch (Throwable $e) {
    echo "DB niet beschikbaar: " . $e->getMessage() . "\n";
    exit(1);
}

$inserted = 0;
foreach ($collected as $row) {
    try {
        $db->query(
            "INSERT INTO midterms_news_cache
                (url_hash, title, url, source, intro_nl, description, image_url, published_at, is_published)
             VALUES (:h, :t, :u, :s, :i, :d, :img, :p, 1)
             ON DUPLICATE KEY UPDATE title=VALUES(title), source=VALUES(source),
                intro_nl=VALUES(intro_nl), description=VALUES(description),
                image_url=VALUES(image_url), published_at=VALUES(published_at)"
        );
        $db->bind(':h', $row['url_hash']);
        $db->bind(':t', $row['title']);
        $db->bind(':u', $row['url']);
        $db->bind(':s', $row['source']);
        $db->bind(':i', $row['intro_nl']);
        $db->bind(':d', $row['description']);
        $db->bind(':img', $row['image_url']);
        $db->bind(':p', $row['published_at']);
        $db->execute();
        $inserted++;
    } catch (Throwable $e) {
        echo "  DB-schrijffout: " . $e->getMessage() . "\n";
    }
}

echo "=== Klaar: {$inserted} opgeslagen/bijgewerkt ===\n";
exit(0);
