<?php
declare(strict_types=1);

// Binaire output: geen enkele PHP-melding mag in de image-bytes belanden.
// Fouten worden nog wel gelogd, maar nooit naar de body geprint.
ini_set('display_errors', '0');

/**
 * Story image proxy.
 *
 * Externe blogafbeeldingen (image_path als http(s)-link) zijn cross-origin en
 * leveren geen CORS-headers. Daardoor kan de Instagram Story <canvas> ze niet
 * inlezen ('tainted canvas') en mislukt de export. Deze proxy haalt de externe
 * afbeelding server-side op en serveert hem vanaf onze eigen origin, zodat de
 * canvas de foto wel kan gebruiken voor download/delen.
 *
 * Beveiliging (anti-SSRF):
 *  - alleen http/https en poort 80/443
 *  - host moet naar een publiek IP resolven (geen private/reserved/loopback)
 *  - redirects worden niet door cURL gevolgd; elke hop wordt opnieuw gevalideerd
 *  - alleen image/* content (met byte-sniffing als fallback)
 *  - harde limiet op grootte en tijd
 */

/**
 * Stuur een foutstatus en stop, zonder body (voorkomt info-lekken).
 */
function story_proxy_fail(int $status): void
{
    http_response_code($status);
    exit;
}

/**
 * Valideer dat een URL veilig naar een publieke host wijst.
 */
function story_proxy_url_is_safe(string $url): bool
{
    $parts = parse_url($url);
    if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
        return false;
    }

    $scheme = strtolower((string) $parts['scheme']);
    if (!in_array($scheme, ['http', 'https'], true)) {
        return false;
    }

    if (!empty($parts['port']) && !in_array((int) $parts['port'], [80, 443], true)) {
        return false;
    }

    $host = (string) $parts['host'];
    $ips = [];

    if (filter_var($host, FILTER_VALIDATE_IP)) {
        $ips[] = $host;
    } else {
        $a = @gethostbynamel($host);
        if (is_array($a)) {
            $ips = array_merge($ips, $a);
        }
        $aaaa = @dns_get_record($host, DNS_AAAA);
        if (is_array($aaaa)) {
            foreach ($aaaa as $record) {
                if (!empty($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        }
    }

    if (empty($ips)) {
        return false;
    }

    foreach ($ips as $ip) {
        // Weiger private/gereserveerde/loopback-adressen (SSRF).
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return false;
        }
    }

    return true;
}

/**
 * Haal een URL op zonder redirects te volgen. Geeft status, content-type,
 * eventuele redirect-locatie en de body terug.
 *
 * @return array{0:int,1:string,2:string,3:string}
 */
function story_proxy_fetch(string $url, int $maxBytes, int $timeout): array
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
        CURLOPT_USERAGENT => 'PolitiekPraat-StoryProxy/1.0 (+https://politiekpraat.nl)',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_ACCEPT_ENCODING => '',
        CURLOPT_NOPROGRESS => false,
        CURLOPT_BUFFERSIZE => 16384,
        CURLOPT_PROGRESSFUNCTION => function ($resource, $downloadTotal, $downloadNow) use ($maxBytes): int {
            return ($downloadNow > $maxBytes) ? 1 : 0;
        },
    ]);

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $redirect = (string) curl_getinfo($ch, CURLINFO_REDIRECT_URL);

    return [$status, $contentType, $redirect, is_string($body) ? $body : ''];
}

$url = isset($_GET['url']) ? trim((string) $_GET['url']) : '';
if ($url === '' || strlen($url) > 2048) {
    story_proxy_fail(400);
}

if (!function_exists('curl_init')) {
    story_proxy_fail(500);
}

$maxBytes = 12 * 1024 * 1024; // 12 MB
$timeout = 8;
$current = $url;
$imageBytes = null;
$imageType = null;

for ($hop = 0; $hop < 3; $hop++) {
    if (!story_proxy_url_is_safe($current)) {
        story_proxy_fail(400);
    }

    [$status, $contentType, $redirect, $body] = story_proxy_fetch($current, $maxBytes, $timeout);

    if ($status >= 300 && $status < 400 && $redirect !== '') {
        $current = $redirect;
        continue;
    }

    if ($status !== 200 || $body === '') {
        story_proxy_fail(502);
    }

    if (stripos($contentType, 'image/') === 0) {
        $imageType = explode(';', $contentType)[0];
    } else {
        // Sommige hosts geven een generiek content-type: val terug op byte-sniffing.
        $info = @getimagesizefromstring($body);
        if ($info === false || empty($info['mime'])) {
            story_proxy_fail(415);
        }
        $imageType = (string) $info['mime'];
    }

    $imageBytes = $body;
    break;
}

if ($imageBytes === null || $imageType === null) {
    story_proxy_fail(502);
}

header('Content-Type: ' . $imageType);
header('Content-Length: ' . strlen($imageBytes));
header('Cache-Control: public, max-age=86400');
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: inline');
echo $imageBytes;
