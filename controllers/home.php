<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalDataAPI.php';
require_once 'includes/PollAPI.php';
require_once 'includes/BlogController.php';
require_once 'models/PartyModel.php';
require_once 'models/NewsModel.php';

// Helper functie om Markdown syntax te strippen
function stripMarkdown($text) {
    if (empty($text)) {
        return $text;
    }
    
    // Strip verschillende Markdown elementen
    $text = preg_replace('/^#{1,6}\s+/m', '', $text); // Headers (# ## ###)
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Bold **text**
    $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Italic *text*
    $text = preg_replace('/`(.*?)`/', '$1', $text); // Inline code `code`
    $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text); // Links [text](url)
    $text = preg_replace('/^\s*[-*+]\s+/m', '', $text); // Unordered lists
    $text = preg_replace('/^\s*\d+\.\s+/m', '', $text); // Ordered lists
    $text = preg_replace('/^>\s+/m', '', $text); // Blockquotes
    $text = preg_replace('/^\s*```.*?```\s*$/ms', '', $text); // Code blocks
    $text = preg_replace('/^\s*---+\s*$/m', '', $text); // Horizontal rules
    
    // Verwijder extra witruimte
    $text = preg_replace('/\n\s*\n/', "\n", $text);
    $text = trim($text);
    
    return $text;
}

function normalize_party_key($name) {
    if (empty($name)) {
        return '';
    }

    return strtolower(preg_replace('/[^a-z0-9]+/i', '', $name));
}

function build_party_acronym($name) {
    if (empty($name)) {
        return null;
    }

    $clean = str_replace(['-', '/', '&'], ' ', $name);
    $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
    $stopWords = ['de', 'van', 'voor', 'het', 'een', 'en', 'der', 'tot', 'te', 'op', 'aan', 'met', 'over', 'door'];
    $letters = '';

    foreach ($words as $word) {
        $word = preg_replace('/[^a-zA-Z]/', '', $word);
        if ($word === '') {
            continue;
        }

        if (strlen($word) <= 2 && in_array(strtolower($word), $stopWords, true)) {
            continue;
        }

        $letters .= strtoupper($word[0]);
    }

    return $letters !== '' ? $letters : null;
}

function load_party_logo_lookup_from_cache() {
    $lookup = [];
    $cacheFile = dirname(__DIR__) . '/cache/politieke_partijen.json';

    if (!file_exists($cacheFile)) {
        return $lookup;
    }

    $payload = json_decode(file_get_contents($cacheFile), true);

    if (empty($payload['data']) || !is_array($payload['data'])) {
        return $lookup;
    }

    foreach ($payload['data'] as $entry) {
        $logo = $entry['logo'] ?? null;
        if (empty($logo)) {
            continue;
        }

        $name = $entry['naam'] ?? '';
        $primaryKey = normalize_party_key($name);

        if ($primaryKey !== '') {
            $lookup[$primaryKey] = $logo;
        }

        $acronym = build_party_acronym($name);
        if ($acronym !== null) {
            $lookup[strtolower($acronym)] = $logo;
        }
    }

    return $lookup;
}

function load_party_logo_lookup() {
    static $lookup;

    if ($lookup !== null) {
        return $lookup;
    }

    $lookup = [];
    try {
        $partyModel = new PartyModel();
        $dbParties = $partyModel->getAllParties();
        foreach ($dbParties as $partyKey => $party) {
            $logo = trim($party['logo'] ?? '');
            if ($logo === '') {
                continue;
            }

            $keysToNormalize = [];
            if (!empty($partyKey)) {
                $keysToNormalize[] = $partyKey;
            }
            if (!empty($party['name'])) {
                $keysToNormalize[] = $party['name'];
            }

            $acronym = build_party_acronym($party['name'] ?? '');
            if ($acronym !== null) {
                $keysToNormalize[] = $acronym;
            }

            foreach ($keysToNormalize as $key) {
                $normalized = normalize_party_key($key);
                if ($normalized === '') {
                    continue;
                }

                $lookup[$normalized] = $logo;
            }
        }
    } catch (Exception $e) {
        // Fallback naar cache wanneer database tijdelijk niet beschikbaar is
    }

    if (empty($lookup)) {
        $lookup = load_party_logo_lookup_from_cache();
    }

    return $lookup;
}

function get_local_party_logo_map() {
    static $map = null;
    if ($map !== null) {
        return $map;
    }

    $base = (defined('URLROOT') ? rtrim(URLROOT, '/') : '') . '/public/images/party-logos';

    $files = [
        'pvv'          => '/pvv.png',
        'vvd'          => '/vvd.png',
        'd66'          => '/d66.png',
        'cda'          => '/cda.png',
        'sp'           => '/sp.png',
        'pvdd'         => '/pvdd.png',
        'ja21'         => '/ja21.png',
        'sgp'          => '/sgp.png',
        'fvd'          => '/fvd.png',
        'denk'         => '/denk.png',
        'volt'         => '/volt.png',
        'bbb'          => '/bbb.png',
        'nsc'          => '/nsc.png',
        'glpvda'       => '/gl-pvda.png',
        'christenunie' => '/christenunie.svg',
    ];

    $alias = [
        'partijvoordevrijheid'                     => 'pvv',
        'volksparijvoorvrijheidendemocratie'       => 'vvd',
        'volkspartijvoorvrijheidendemocratie'      => 'vvd',
        'democraten66'                             => 'd66',
        'christendemocratischappel'                => 'cda',
        'christendemocratischappèl'                => 'cda',
        'socialistischepartij'                     => 'sp',
        'partijvoordedieren'                       => 'pvdd',
        'forumvoordemocratie'                      => 'fvd',
        'voltnederland'                            => 'volt',
        'boerburgerbeweging'                       => 'bbb',
        'nieuwsociaalcontract'                     => 'nsc',
        'groenlinkspvda'                           => 'glpvda',
        'glpvda'                                   => 'glpvda',
        'groenlinks'                               => 'glpvda',
        'pvda'                                     => 'glpvda',
        'staatkundiggereformeerdepartij'           => 'sgp',
    ];

    $map = [];
    foreach ($files as $key => $path) {
        $map[$key] = $base . $path;
    }
    foreach ($alias as $fromKey => $toKey) {
        if (isset($files[$toKey])) {
            $map[$fromKey] = $base . $files[$toKey];
        }
    }

    return $map;
}

function get_party_logo_url($party_name) {
    if (empty($party_name)) {
        return null;
    }

    $normalized = normalize_party_key($party_name);
    $localMap = get_local_party_logo_map();

    if ($normalized !== '' && isset($localMap[$normalized])) {
        return $localMap[$normalized];
    }

    $acronym = build_party_acronym($party_name);
    if ($acronym !== null) {
        $acronymKey = strtolower($acronym);
        if (isset($localMap[$acronymKey])) {
            return $localMap[$acronymKey];
        }
    }

    $lookup = load_party_logo_lookup();
    if (empty($lookup)) {
        return null;
    }

    if ($normalized !== '' && isset($lookup[$normalized])) {
        return $lookup[$normalized];
    }

    if ($normalized !== '') {
        foreach ($lookup as $key => $logo) {
            if (strpos($key, $normalized) !== false || strpos($normalized, $key) !== false) {
                return $logo;
            }
        }
    }

    if ($acronym !== null && isset($lookup[strtolower($acronym)])) {
        return $lookup[strtolower($acronym)];
    }

    return null;
}

function normalize_president_photo_url($url) {
    if (empty($url)) {
        return $url;
    }

    $modernTrumpPortrait = 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg/960px-Official_Presidential_Portrait_of_President_Donald_J._Trump_%282025%29.jpg';

    if (stripos($url, 'Donald_Trump_official_portrait.jpg') !== false) {
        return $modernTrumpPortrait;
    }

    return $url;
}

// Force canonical homepage URL: /home -> / (SEO duplicate content prevention)
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (rtrim($requestPath, '/') === '/home') {
    header('Location: ' . URLROOT . '/', true, 301);
    exit;
}

// PERFORMANCE TODO: Implement server-side caching (e.g., Redis, Memcached) for database queries and API responses to significantly improve TTFB (Time To First Byte).
$db = new Database();
$newsAPI = new NewsAPI();
$openDataAPI = new OpenDataAPI();
$politicalDataAPI = new PoliticalDataAPI();
$pollAPI = new PollAPI();
$blogController = new BlogController();
$newsModel = new NewsModel($db);

// Haal actuele politieke data op
$kamerStats = $politicalDataAPI->getKamerStatistieken();
$coalitieStatus = $politicalDataAPI->getCoalitieStatus();
$partijData = $politicalDataAPI->getPartijInformatie();

// Haal peilingen data op
$latestPolls = $pollAPI->getLatestPolls();
$historicalPolls = $pollAPI->getHistoricalPolls(3);

// Data voor zetelverdeling peiling 25-10-2025 (Ipsos I&O / Pauw & De Wit)
$peilingData = [
    [
        'partij' => 'PVV',
        'zetels' => [
            'peiling' => 26, 
            'vorige' => 29,
            'tkvorigepeiling' => 29,
            'tk2023' => 37
        ],
        'color' => '#0078D7'
    ],
    [
        'partij' => 'GL/PvdA',
        'zetels' => [
            'peiling' => 23, 
            'vorige' => 27,
            'tkvorigepeiling' => 22,
            'tk2023' => 25
        ],
        'color' => '#008800'
    ],
    [
        'partij' => 'D66',
        'zetels' => [
            'peiling' => 22, 
            'vorige' => 10,
            'tkvorigepeiling' => 18,
            'tk2023' => 9
        ],
        'color' => '#00B13C'
    ],
    [
        'partij' => 'CDA',
        'zetels' => [
            'peiling' => 20, 
            'vorige' => 21,
            'tkvorigepeiling' => 25,
            'tk2023' => 5
        ],
        'color' => '#1E8449'
    ],
    [
        'partij' => 'VVD',
        'zetels' => [
            'peiling' => 16, 
            'vorige' => 19,
            'tkvorigepeiling' => 14,
            'tk2023' => 24
        ],
        'color' => '#FF9900'
    ],
    [
        'partij' => 'JA21',
        'zetels' => [
            'peiling' => 12, 
            'vorige' => 8,
            'tkvorigepeiling' => 12,
            'tk2023' => 1
        ],
        'color' => '#4B0082'
    ],
    [
        'partij' => 'FVD',
        'zetels' => [
            'peiling' => 5, 
            'vorige' => 4,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#8B4513'
    ],
    [
        'partij' => 'PvdDieren',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 5,
            'tkvorigepeiling' => 4,
            'tk2023' => 3
        ],
        'color' => '#006400'
    ],
    [
        'partij' => 'BBB',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 5,
            'tkvorigepeiling' => 4,
            'tk2023' => 7
        ],
        'color' => '#7CFC00'
    ],
    [
        'partij' => 'SP',
        'zetels' => [
            'peiling' => 4, 
            'vorige' => 8,
            'tkvorigepeiling' => 4,
            'tk2023' => 5
        ],
        'color' => '#EE0000'
    ],
    [
        'partij' => 'DENK',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 4,
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#00BFFF'
    ],
    [
        'partij' => 'Volt',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 3,
            'tkvorigepeiling' => 3,
            'tk2023' => 2
        ],
        'color' => '#800080'
    ],
    [
        'partij' => 'SGP',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 4,
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#ff7f00'
    ],
    [
        'partij' => 'ChristenUnie',
        'zetels' => [
            'peiling' => 3, 
            'vorige' => 3,
            'tkvorigepeiling' => 3,
            'tk2023' => 3
        ],
        'color' => '#4682B4'
    ],
    [
        'partij' => '50PLUS',
        'zetels' => [
            'peiling' => 2, 
            'vorige' => 0,
            'tkvorigepeiling' => 2,
            'tk2023' => 0
        ],
        'color' => '#9C27B0'
    ],
    [
        'partij' => 'NSC',
        'zetels' => [
            'peiling' => 0, 
            'vorige' => 0,
            'tkvorigepeiling' => 0,
            'tk2023' => 20
        ],
        'color' => '#4D7F78'
    ]
];

// Mogelijke coalities berekenen op basis van de bijgewerkte peilingdata van 25-10-2025
$mogelijkeCoalities = [
    [
        'naam' => 'Links-progressief',
        'partijen' => ['GL/PvdA', 'D66', 'SP', 'PvdDieren', 'Volt'],
        'zetels' => 23 + 22 + 4 + 4 + 3 // 56 zetels
    ],
    [
        'naam' => 'Rechts-conservatief',
        'partijen' => ['PVV', 'VVD', 'BBB', 'JA21', 'SGP', 'FVD'],
        'zetels' => 26 + 16 + 4 + 12 + 3 + 5 // 66 zetels
    ],
    [
        'naam' => 'Centrum-breed',
        'partijen' => ['GL/PvdA', 'VVD', 'CDA', 'D66', 'ChristenUnie'],
        'zetels' => 23 + 16 + 20 + 22 + 3 // 84 zetels
    ],
    [
        'naam' => 'Huidige coalitie',
        'partijen' => ['PVV', 'VVD', 'BBB', 'NSC'],
        'zetels' => 26 + 16 + 4 + 0 // 46 zetels
    ]
];

// Sorteer coalities op aantal zetels (aflopend)
usort($mogelijkeCoalities, function($a, $b) {
    return $b['zetels'] - $a['zetels'];
});

// Haal de laatste 6 blogs op
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           WHERE blogs.status = 'published' AND blogs.published_at <= NOW()
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

// Haal Amerikaanse verkiezingen data op (laatste 4 verkiezingen)
$db->query("SELECT * FROM amerikaanse_verkiezingen ORDER BY jaar DESC LIMIT 4");
$amerikaanse_verkiezingen = $db->resultSet();

foreach ($amerikaanse_verkiezingen as $verkiezing) {
    if (!empty($verkiezing->winnaar_foto_url)) {
        $verkiezing->winnaar_foto_url = normalize_president_photo_url($verkiezing->winnaar_foto_url);
    }
    if (!empty($verkiezing->verliezer_foto_url)) {
        $verkiezing->verliezer_foto_url = normalize_president_photo_url($verkiezing->verliezer_foto_url);
    }
}

// Haal Nederlandse verkiezingen data op (laatste 4 verkiezingen)
$db->query("SELECT * FROM nederlandse_verkiezingen ORDER BY jaar DESC LIMIT 4");
$nederlandse_verkiezingen = $db->resultSet();

$fallback_data = require BASE_PATH . '/includes/data/nederlandse_verkiezingen_2025.php';
$fallback_latest_verkiezing_2025 = $fallback_data['fallback_latest_verkiezing_2025'] ?? null;
if ($fallback_latest_verkiezing_2025) {
    $has_latest = false;
    foreach ($nederlandse_verkiezingen as $record) {
        if (!empty($record->jaar) && (int) $record->jaar === (int) $fallback_latest_verkiezing_2025['jaar']) {
            $has_latest = true;
            break;
        }
    }

    if (!$has_latest) {
        array_unshift($nederlandse_verkiezingen, (object) $fallback_latest_verkiezing_2025);
    }
}

// Parse Nederlandse verkiezingen data
foreach ($nederlandse_verkiezingen as $verkiezing) {
    // Parse partij uitslagen voor grootste partij info
    if (!empty($verkiezing->partij_uitslagen)) {
        $partijUitslagen = json_decode($verkiezing->partij_uitslagen);
        if ($partijUitslagen && count($partijUitslagen) > 0) {
            $verkiezing->grootste_partij = $partijUitslagen[0]->partij ?? null;
            $verkiezing->grootste_partij_zetels = $partijUitslagen[0]->zetels ?? null;
            $verkiezing->grootste_partij_percentage = $partijUitslagen[0]->percentage ?? null;
        }
    }

    $verkiezing->grootste_partij_logo = get_party_logo_url($verkiezing->grootste_partij ?? null);
}

if (count($nederlandse_verkiezingen) > 4) {
    $nederlandse_verkiezingen = array_slice($nederlandse_verkiezingen, 0, 4);
}

$latest_election_year = date('Y');
if (!empty($nederlandse_verkiezingen)) {
    $years = array_map(function ($record) {
        return (int) ($record->jaar ?? 0);
    }, $nederlandse_verkiezingen);
    $latest_election_year = max($years);
}
$next_election_year = (int) $latest_election_year + 2;

// Haal de populairste blogs op voor de hero sectie
$db->query("SELECT blogs.*, users.username as author_name, users.profile_photo as author_photo 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           WHERE blogs.status = 'published' AND blogs.published_at <= NOW()
           ORDER BY views DESC, published_at DESC 
           LIMIT 4");
$featured_blogs = $db->resultSet();

// Berekenen van relatieve publicatiedatum voor blogs
foreach ($latest_blogs as $blog) {
    $blog->relative_date = getRelativeTime($blog->published_at);
    // Strip Markdown van titel en summary
    $blog->title = stripMarkdown($blog->title);
    $blog->summary = stripMarkdown($blog->summary);
}
foreach ($featured_blogs as $blog) {
    $blog->relative_date = getRelativeTime($blog->published_at);
    // Strip Markdown van titel en summary
    $blog->title = stripMarkdown($blog->title);
    $blog->summary = stripMarkdown($blog->summary);
}

// Haal het laatste nieuws op uit database (primair) met robuuste fallback
$latest_news = [];

try {
    $homepageNews = $newsModel->getHomepageNews(3, 48);
    $latest_news = array_merge($homepageNews['links'], $homepageNews['rechts']);

    // Filter op geldige externe links
    $latest_news = array_values(array_filter($latest_news, function($article) {
        if (empty($article['url']) || !filter_var($article['url'], FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($article['url'], PHP_URL_SCHEME);
        return in_array(strtolower((string)$scheme), ['http', 'https'], true);
    }));

    if (count($homepageNews['links']) < 3 || count($homepageNews['rechts']) < 3) {
        error_log("home.php: fallback geactiveerd, onvoldoende recente items per perspectief (links=" . count($homepageNews['links']) . ", rechts=" . count($homepageNews['rechts']) . ")");
    }
} catch (Exception $e) {
    error_log("home.php: ophalen homepage nieuws mislukt: " . $e->getMessage());
}

// Noodfallback zodat homepage nooit leeg is
if (empty($latest_news)) {
    error_log("home.php: noodfallback actief voor Laatste Politiek Nieuws");
    $latest_news = [
        [
            'orientation' => 'links',
            'source' => 'PolitiekPraat',
            'bias' => 'Centrum-links',
            'publishedAt' => date('Y-m-d H:i:s'),
            'title' => 'Progressief nieuws wordt momenteel opnieuw geladen',
            'description' => 'De live nieuwsfeed is tijdelijk niet beschikbaar. Probeer het over enkele minuten opnieuw.',
            'url' => URLROOT . '/nieuws?filter=progressief'
        ],
        [
            'orientation' => 'rechts',
            'source' => 'PolitiekPraat',
            'bias' => 'Centrum-rechts',
            'publishedAt' => date('Y-m-d H:i:s'),
            'title' => 'Conservatief nieuws wordt momenteel opnieuw geladen',
            'description' => 'De live nieuwsfeed is tijdelijk niet beschikbaar. Probeer het over enkele minuten opnieuw.',
            'url' => URLROOT . '/nieuws?filter=conservatief'
        ]
    ];
}

// Bronlogo mapping (kleine externe SVG/PNG logo's) + nette fallback
$newsSourceLogoMap = [
    'de volkskrant' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/De_Volkskrant_logo.svg/200px-De_Volkskrant_logo.svg.png',
    'nrc' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/NRC_logo.svg/200px-NRC_logo.svg.png',
    'trouw' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Trouw_logo.svg/200px-Trouw_logo.svg.png',
    'telegraaf' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/De_Telegraaf_logo.svg/200px-De_Telegraaf_logo.svg.png',
    'ad' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Algemeen_Dagblad_logo.svg/200px-Algemeen_Dagblad_logo.svg.png',
    'nu.nl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/61/Nu.nl_logo.svg/200px-Nu.nl_logo.svg.png',
    'ew magazine' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/EW_logo.svg/200px-EW_logo.svg.png',
    'wynia\'s week' => 'https://www.wyniasweek.nl/wp-content/uploads/2022/08/WW-logo.png',
    'het parool' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Het_Parool_logo.svg/200px-Het_Parool_logo.svg.png',
    'fd' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Het_Financieele_Dagblad_logo.svg/220px-Het_Financieele_Dagblad_logo.svg.png',
    'nos' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NOS_logo.svg/200px-NOS_logo.svg.png',
    'rtl nieuws' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/RTL_Nieuws_logo_2023.svg/220px-RTL_Nieuws_logo_2023.svg.png',
    'bnr' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/BNR_Nieuwsradio_logo.svg/220px-BNR_Nieuwsradio_logo.svg.png',
    'politiekpraat' => URLROOT . '/public/img/logo.png'
];

$resolveNewsLogo = static function(string $source) use ($newsSourceLogoMap): ?string {
    $normalized = mb_strtolower(trim($source));

    if (isset($newsSourceLogoMap[$normalized])) {
        return $newsSourceLogoMap[$normalized];
    }

    return null;
};

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

// Haal de nieuwste blog post op voor de hero sectie
$latestBlog = $blogController->getAll(1); // Haal alleen de nieuwste blog op
$latestBlog = !empty($latestBlog) ? $latestBlog[0] : null;

// Midterms 2026 data voor de homepage-band.
// Gebruikt MidtermsModel rechtstreeks (los van de midterms-views); valt bij een
// lege tabel of ontbrekende DB stil terug op de seed-data.
$midtermsOdds = [];
$midtermsTopRaces = [];
$midtermsRatingMeta = [];
$midtermsDaysLeft = null;
$midtermsElectionDate = null;
try {
    require_once 'models/MidtermsModel.php';
    $midtermsModel = new MidtermsModel($db);
    $midtermsOdds = $midtermsModel->getOdds();
    $midtermsTopRaces = $midtermsModel->getCompetitiveRaces(null, 5);
    $midtermsRatingMeta = MidtermsModel::ratingMeta();
    $midtermsDaysLeft = $midtermsModel->getDaysUntilElection();
    $midtermsElectionDate = MidtermsModel::ELECTION_DATE;
} catch (Throwable $e) {
    error_log('home.php: midterms-data laden mislukt: ' . $e->getMessage());
}

// Canonieke thema's voor de thema-sectie op de homepage
$home_themas = [];
$home_themas_path = BASE_PATH . '/includes/data/themas.php';
if (is_readable($home_themas_path)) {
    $loaded_themas = require $home_themas_path;
    if (is_array($loaded_themas)) {
        $home_themas = $loaded_themas;
    }
}

// Partijen (logo-wall) voor de homepage; gesorteerd op huidige zetels.
// Slug komt overeen met de detail-resolver in controllers/partijen-detail.php.
$home_parties = [];
try {
    $homePartyModel = new PartyModel();
    $homeAllParties = $homePartyModel->getAllParties();
    uasort($homeAllParties, static function ($a, $b) {
        return ((int) ($b['current_seats'] ?? 0)) <=> ((int) ($a['current_seats'] ?? 0));
    });
    foreach ($homeAllParties as $partyKey => $party) {
        $logo = get_party_logo_url($party['name'] ?? $partyKey);
        if (empty($logo)) {
            $logo = $party['logo'] ?? null;
        }
        if (empty($logo)) {
            continue;
        }
        $home_parties[] = [
            'name' => $party['name'] ?? $partyKey,
            'slug' => strtolower(str_replace(['/', ' '], '-', (string) $partyKey)),
            'logo' => $logo,
            'seats' => (int) ($party['current_seats'] ?? 0),
        ];
    }
} catch (Throwable $e) {
    error_log('home.php: partijen laden mislukt: ' . $e->getMessage());
}

require_once 'views/home/index.php';
