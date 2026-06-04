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
            // Alleen recent nieuws (afgelopen maand) zodat "laatste nieuws" ook echt recent is.
            'freshness' => 'pm',
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
 * Filtert ruis: houd alleen berichten die echt over de midterms 2026 gaan.
 * Vermijdt losse Trump-/binnenlandnieuws dat toevallig bij een query opduikt.
 */
function mt_news_relevant(string $title, string $desc): bool
{
    $h = mb_strtolower($title . ' ' . $desc);
    if (strpos($h, 'midterm') !== false) {
        return true;
    }
    // Anders: moet 2026 noemen én een verkiezingsterm bevatten.
    if (strpos($h, '2026') === false) {
        return false;
    }
    // Filter financiële/crypto-ruis die toevallig "Senaat" en "2026" noemt.
    foreach (['bitcoin', 'crypto', 'clarity act', 'beurskoers', 'aandelenkoers', 'koersdoel'] as $neg) {
        if (strpos($h, $neg) !== false) {
            return false;
        }
    }
    $terms = [
        'senaat', 'senate', 'huis van afgevaardigden', 'house of representatives',
        'afgevaardigde', 'congres', 'congress', 'gouverneur', 'governor',
        'verkiezing', 'election', 'voorverkiezing', 'primary', 'primaries',
        'tussentijdse', 'zetel', 'kandidaat', 'candidate', 'ballot',
    ];
    foreach ($terms as $kw) {
        if (strpos($h, $kw) !== false) {
            return true;
        }
    }
    return false;
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

/**
 * Heuristiek: is dit item vermoedelijk al Nederlandstalig?
 * Nederlandse bron (.nl/.be) of minstens twee Nederlandse stopwoorden.
 */
function mt_news_is_dutch(string $source, string $title, string $desc): bool
{
    if (preg_match('/\.(nl|be)$/', mb_strtolower(trim($source)))) {
        return true;
    }
    $h = ' ' . mb_strtolower($title . ' ' . $desc) . ' ';
    $stop = [' de ', ' het ', ' een ', ' en ', ' van ', ' wordt ', ' niet ', ' zijn ', ' voor ', ' naar ', ' met ', ' worden ', ' volgens '];
    $hits = 0;
    foreach ($stop as $w) {
        if (strpos($h, $w) !== false) {
            $hits++;
        }
    }
    return $hits >= 2;
}

/**
 * Vertaalt niet-Nederlandse koppen/intro's naar het Nederlands via de bestaande
 * OpenAI-integratie. De originele (Engelse) tekst blijft in 'description'.
 * Best-effort: zonder OpenAI-sleutel of bij een fout blijven de originele
 * teksten staan, zodat de pipeline nooit breekt.
 *
 * @param array<string,array> $collected
 * @return array<string,array>
 */
function mt_translate_to_dutch(array $collected): array
{
    $batch = [];
    $keys = [];
    foreach ($collected as $hash => $row) {
        $title = (string) ($row['title'] ?? '');
        $desc = (string) ($row['description'] ?? '');
        if (mt_news_is_dutch((string) ($row['source'] ?? ''), $title, $desc)) {
            continue;
        }
        $keys[] = $hash;
        $batch[] = ['title' => $title, 'intro' => $desc];
    }
    if (empty($batch)) {
        echo "Vertaling: niets te vertalen (alles al Nederlands).\n";
        return $collected;
    }

    $chatFile = __DIR__ . '/../includes/ChatGPTAPI.php';
    if (!is_readable($chatFile)) {
        echo "Vertaling overgeslagen: ChatGPTAPI.php niet gevonden.\n";
        return $collected;
    }
    require_once $chatFile;

    try {
        $chat = new ChatGPTAPI();
    } catch (Throwable $e) {
        echo "Vertaling overgeslagen (geen OpenAI-sleutel): " . $e->getMessage() . "\n";
        return $collected;
    }

    try {
        $translated = $chat->translateNewsToDutch($batch);
    } catch (Throwable $e) {
        echo "Vertaalfout, originelen behouden: " . $e->getMessage() . "\n";
        return $collected;
    }

    $count = 0;
    foreach ($keys as $i => $hash) {
        if (!isset($translated[$i], $collected[$hash])) {
            continue;
        }
        $t = trim((string) ($translated[$i]['title'] ?? ''));
        $intro = trim((string) ($translated[$i]['intro'] ?? ''));
        if ($t !== '') {
            $collected[$hash]['title'] = mb_substr($t, 0, 300);
            $count++;
        }
        if ($intro !== '') {
            $collected[$hash]['intro_nl'] = $intro;
        }
    }
    echo "Vertaling: {$count} item(s) naar het Nederlands vertaald.\n";
    return $collected;
}

/**
 * Raakt het gedeelde refresh-lock aan, zodat de verkeer-gestuurde fallback in
 * de controller weet dat er recent (via cron of verkeer) is opgehaald.
 */
function mt_touch_refresh_lock(string $task): void
{
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @touch($dir . '/mt_refresh_' . $task . '.lock');
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
        if (!mt_news_relevant($title, $desc)) {
            continue;
        }
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

// Sorteer op publicatiedatum, nieuwste eerst (null/onbekend achteraan).
uasort($collected, static function ($a, $b) {
    return strcmp((string) ($b['published_at'] ?? ''), (string) ($a['published_at'] ?? ''));
});

// Beperk tot de meest recente items (ruim genoeg voor nieuws- en hubweergave)
// en houd de vertaalkosten beperkt.
$collected = array_slice($collected, 0, 30, true);

echo "Totaal uniek verzameld: " . count($collected) . "\n";

// Vertaal Engelstalige koppen/intro's naar het Nederlands (best-effort).
$collected = mt_translate_to_dutch($collected);

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
mt_touch_refresh_lock('news');
exit(0);
