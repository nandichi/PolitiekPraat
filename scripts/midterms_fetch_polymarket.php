<?php
/**
 * Haalt Polymarket-odds op voor de midterms en schrijft ze naar
 * midterms_odds_cache. Bedoeld om per uur via cron te draaien.
 *
 * Gebruik:
 *   php scripts/midterms_fetch_polymarket.php            (schrijft naar DB)
 *   php scripts/midterms_fetch_polymarket.php --dry-run  (toont alleen output)
 *
 * Bron: Polymarket Gamma API (https://gamma-api.polymarket.com).
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

$DRY_RUN = in_array('--dry-run', $argv ?? [], true);

echo "=== Midterms Polymarket fetch: " . date('Y-m-d H:i:s') . " ===\n";

/**
 * Te volgen markten. market_key is intern; slug verwijst naar het Polymarket-event.
 * type:
 *   'binary'  - 1 markt met meerdere uitkomsten (pak hoogste volume),
 *   'grouped' - meerdere deelmarkten, elk een Yes/No (bijv. zetelaantallen),
 *   'party'   - deelmarkt per partij (Democratic/Republican Party), Yes-prijs per partij.
 */
$markets = [
    [
        // Event met een deelmarkt per partij ("Democratic Party"/"Republican Party"),
        // elk met een Yes/No-prijs. We nemen per partij de Yes-prijs -> party-type.
        'market_key' => 'senate_control', 'category' => 'control', 'type' => 'party',
        'title_nl' => 'Wie controleert de Senaat na 2026?',
        'slug' => 'which-party-will-win-the-senate-in-2026',
    ],
    [
        'market_key' => 'house_control', 'category' => 'control', 'type' => 'party',
        'title_nl' => 'Wie controleert het Huis na 2026?',
        'slug' => 'which-party-will-win-the-house-in-2026',
    ],
    [
        'market_key' => 'rep_senate_seats', 'category' => 'seats', 'type' => 'grouped',
        'title_nl' => 'Aantal Republikeinse Senaatszetels na 2026',
        'slug' => 'republican-senate-seats-after-the-2026-midterm-elections-927',
    ],
    [
        'market_key' => 'rep_house_seats', 'category' => 'seats', 'type' => 'grouped',
        'title_nl' => 'Aantal Republikeinse Huiszetels na 2026',
        'slug' => 'republican-house-seats-after-the-2026-midterm-elections',
    ],
    [
        'market_key' => 'rep_governors', 'category' => 'seats', 'type' => 'grouped',
        'title_nl' => 'Aantal Republikeinse gouverneurs na 2026',
        'slug' => 'how-many-republican-governors-after-the-2026-midterm-elections',
    ],
];

/**
 * HTTP GET met cURL.
 */
function mt_http_get(string $url): ?string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_USERAGENT => 'PolitiekPraat-Midterms/1.0 (+https://politiekpraat.nl)',
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    unset($ch);

    if ($body === false || $code >= 400) {
        echo "  HTTP-fout ({$code}) voor {$url}: {$err}\n";
        return null;
    }
    return (string) $body;
}

/**
 * Decodeer een veld dat een JSON-string of array kan zijn.
 */
function mt_decode_field($value): array
{
    if (is_array($value)) {
        return $value;
    }
    if (is_string($value) && $value !== '') {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}

/**
 * Vertaal Engelse uitkomstlabels naar NL waar mogelijk.
 */
function mt_translate_outcome(string $label): string
{
    $trim = trim($label);
    if (preg_match('/^below\s+(.+)$/i', $trim, $m)) {
        return 'Onder ' . $m[1];
    }
    if (preg_match('/^above\s+(.+)$/i', $trim, $m)) {
        return 'Boven ' . $m[1];
    }
    $l = strtolower($trim);
    $map = [
        'republican' => 'Republikeinen',
        'republicans' => 'Republikeinen',
        'republican party' => 'Republikeinen',
        'democratic' => 'Democraten',
        'democrat' => 'Democraten',
        'democrats' => 'Democraten',
        'democratic party' => 'Democraten',
        'other' => 'Andere partij',
        'yes' => 'Ja',
        'no' => 'Nee',
    ];
    return $map[$l] ?? $label;
}

/**
 * Bouw genormaliseerde outcomes uit een Polymarket-event.
 * @return array{outcomes: array, volume: float}
 */
function mt_extract_outcomes(array $event, string $type): array
{
    $marketsArr = $event['markets'] ?? [];
    $outcomes = [];
    $volume = (float) ($event['volume'] ?? 0);

    if ($type === 'binary' && count($marketsArr) >= 1) {
        // Pak de markt met het hoogste volume als hoofdmarkt.
        usort($marketsArr, static fn ($a, $b) => (float) ($b['volume'] ?? 0) <=> (float) ($a['volume'] ?? 0));
        $market = $marketsArr[0];
        $labels = mt_decode_field($market['outcomes'] ?? '');
        $prices = mt_decode_field($market['outcomePrices'] ?? '');
        foreach ($labels as $i => $label) {
            $price = isset($prices[$i]) ? (float) $prices[$i] : null;
            if ($price === null) {
                continue;
            }
            $outcomes[] = [
                'label' => (string) $label,
                'label_nl' => mt_translate_outcome((string) $label),
                'price' => $price,
            ];
        }
    } else {
        // Gegroepeerd: elke deelmarkt is een uitkomst (Yes-prijs).
        foreach ($marketsArr as $market) {
            $label = $market['groupItemTitle'] ?? ($market['question'] ?? '');
            if ($label === '') {
                continue;
            }
            $labels = mt_decode_field($market['outcomes'] ?? '');
            $prices = mt_decode_field($market['outcomePrices'] ?? '');
            $yesIndex = 0;
            foreach ($labels as $i => $l) {
                if (strtolower((string) $l) === 'yes') {
                    $yesIndex = $i;
                    break;
                }
            }
            $price = isset($prices[$yesIndex]) ? (float) $prices[$yesIndex] : null;
            if ($price === null) {
                continue;
            }
            $outcomes[] = [
                'label' => (string) $label,
                'label_nl' => mt_translate_outcome((string) $label),
                'price' => $price,
            ];
        }
        // Sorteer aflopend op kans, beperk tot top 12.
        usort($outcomes, static fn ($a, $b) => $b['price'] <=> $a['price']);
        $outcomes = array_slice($outcomes, 0, 12);
    }

    return ['outcomes' => $outcomes, 'volume' => $volume];
}

// ---------------------------------------------------------------------------
$db = null;
if (!$DRY_RUN) {
    try {
        $db = new Database();
        $db->query(
            "INSERT INTO midterms_odds_cache (market_key, event_slug, title_nl, category, outcomes_json, volume, source_url)
             VALUES (:k, :s, :t, :c, :o, :v, :u)
             ON DUPLICATE KEY UPDATE event_slug=VALUES(event_slug), title_nl=VALUES(title_nl),
                category=VALUES(category), outcomes_json=VALUES(outcomes_json), volume=VALUES(volume),
                source_url=VALUES(source_url), fetched_at=CURRENT_TIMESTAMP"
        );
        // We hergebruiken de prepared statement per iteratie via opnieuw query().
    } catch (Throwable $e) {
        echo "DB niet beschikbaar, schakel over naar dry-run: " . $e->getMessage() . "\n";
        $db = null;
        $DRY_RUN = true;
    }
}

$ok = 0;
$fail = 0;
foreach ($markets as $cfg) {
    echo "- {$cfg['market_key']} ({$cfg['slug']})\n";
    $url = 'https://gamma-api.polymarket.com/events?slug=' . rawurlencode($cfg['slug']);
    $raw = mt_http_get($url);
    if ($raw === null) {
        $fail++;
        continue;
    }
    $data = json_decode($raw, true);
    if (!is_array($data) || count($data) === 0) {
        echo "  Geen event gevonden voor slug.\n";
        $fail++;
        continue;
    }
    $event = $data[0];
    $extracted = mt_extract_outcomes($event, $cfg['type']);
    $outcomes = $extracted['outcomes'];

    if (empty($outcomes)) {
        echo "  Geen bruikbare uitkomsten.\n";
        $fail++;
        continue;
    }

    $sourceUrl = 'https://polymarket.com/event/' . $cfg['slug'];

    foreach (array_slice($outcomes, 0, 4) as $oc) {
        echo "    " . str_pad($oc['label_nl'], 22) . round($oc['price'] * 100, 1) . "%\n";
    }

    if (!$DRY_RUN && $db !== null) {
        try {
            $db->query(
                "INSERT INTO midterms_odds_cache (market_key, event_slug, title_nl, category, outcomes_json, volume, source_url)
                 VALUES (:k, :s, :t, :c, :o, :v, :u)
                 ON DUPLICATE KEY UPDATE event_slug=VALUES(event_slug), title_nl=VALUES(title_nl),
                    category=VALUES(category), outcomes_json=VALUES(outcomes_json), volume=VALUES(volume),
                    source_url=VALUES(source_url), fetched_at=CURRENT_TIMESTAMP"
            );
            $db->bind(':k', $cfg['market_key']);
            $db->bind(':s', $cfg['slug']);
            $db->bind(':t', $cfg['title_nl']);
            $db->bind(':c', $cfg['category']);
            $db->bind(':o', json_encode($outcomes, JSON_UNESCAPED_UNICODE));
            $db->bind(':v', $extracted['volume']);
            $db->bind(':u', $sourceUrl);
            $db->execute();
        } catch (Throwable $e) {
            echo "  DB-schrijffout: " . $e->getMessage() . "\n";
            $fail++;
            continue;
        }
    }
    $ok++;
    // Wees vriendelijk voor de API.
    usleep(250000);
}

echo "=== Klaar: {$ok} gelukt, {$fail} mislukt" . ($DRY_RUN ? ' (dry-run)' : '') . " ===\n";
if (!$DRY_RUN) {
    mt_touch_refresh_lock('odds');
}
exit($fail > 0 && $ok === 0 ? 1 : 0);

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
