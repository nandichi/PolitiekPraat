<?php
/**
 * Controller voor de Midterms 2026 sectie (/midterms-2026).
 *
 * Verzorgt de negen pagina's en de JSON-feeds voor de interactieve kaart
 * en de Polymarket-odds. Dispatch onderaan dit bestand op basis van $_GET
 * (gezet door de routes in index.php).
 */

require_once 'includes/Database.php';
require_once 'models/MidtermsModel.php';
require_once 'includes/config.php';
require_once 'includes/midterms_helpers.php';

class MidtermsController
{
    /** @var Database|null */
    private $db;

    /** @var MidtermsModel */
    private $model;

    /** Geldige sub-secties => view-bestand (zonder extensie). */
    private const SECTIONS = [
        'hub'             => 'hub',
        'senaat'          => 'senaat',
        'huis'            => 'huis',
        'gouverneurs'     => 'gouverneurs',
        'voorverkiezingen' => 'voorverkiezingen',
        'nieuws'          => 'nieuws',
        'referenda'       => 'referenda',
        'races'           => 'races',
        'uitleg'          => 'uitleg',
    ];

    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (Throwable $e) {
            // Geen DB beschikbaar: model valt terug op seed-data.
            $this->db = null;
        }
        $this->model = new MidtermsModel($this->db);
    }

    // ------------------------------------------------------------------
    // JSON-feeds voor de frontend (D3-kaart + odds)
    // ------------------------------------------------------------------
    public function feed(string $feed): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
            header('Cache-Control: public, max-age=300');
        }

        if ($feed === 'map') {
            $chamber = $_GET['chamber'] ?? 'senate';
            $map = [
                'senaat' => 'senate', 'senate' => 'senate',
                'huis' => 'house', 'house' => 'house',
                'gouverneurs' => 'governor', 'governor' => 'governor',
            ];
            $chamber = $map[$chamber] ?? 'senate';

            echo json_encode([
                'chamber' => $chamber,
                'updated' => date('c'),
                'ratingMeta' => MidtermsModel::ratingMeta(),
                'ratings' => $this->model->getRatingsMap($chamber),
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($feed === 'odds') {
            echo json_encode([
                'updated' => date('c'),
                'odds' => array_values($this->model->getOdds()),
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!headers_sent()) {
            http_response_code(404);
        }
        echo json_encode(['error' => 'Onbekende feed']);
        exit;
    }

    // ------------------------------------------------------------------
    // HTML-pagina's
    // ------------------------------------------------------------------
    public function show(string $section): void
    {
        if (!isset(self::SECTIONS[$section])) {
            // Onbekende sub-sectie -> 404
            header('Location: ' . URLROOT . '/404');
            exit;
        }

        // Houd odds en nieuws vers via verkeer-gestuurde achtergrond-refresh.
        $this->maybeAutoRefresh();

        $model = $this->model;
        $ratingMeta = MidtermsModel::ratingMeta();
        $daysLeft = $model->getDaysUntilElection();
        $electionDate = MidtermsModel::ELECTION_DATE;

        // Per-sectie data + SEO meta
        $data = $this->metaForSection($section);

        switch ($section) {
            case 'senaat':
                $races = $model->getRacesByChamber('senate');
                $odds = $model->getOdds();
                break;

            case 'huis':
                $races = $model->getRacesByChamber('house');
                $competitive = $model->getCompetitiveRaces('house');
                $odds = $model->getOdds();
                break;

            case 'gouverneurs':
                $races = $model->getRacesByChamber('governor');
                $odds = $model->getOdds();
                break;

            case 'voorverkiezingen':
                $timeline = $model->getTimeline();
                break;

            case 'nieuws':
                $news = $model->getNews(24);
                break;

            case 'referenda':
                $referenda = $model->getReferenda();
                $odds = $model->getOdds();
                break;

            case 'races':
                $competitive = $model->getCompetitiveRaces();
                $odds = $model->getOdds();
                break;

            case 'uitleg':
                // statische uitleg in de view
                break;

            case 'hub':
            default:
                $odds = $model->getOdds();
                $news = $model->getNews(4);
                $timeline = $model->getTimeline(5);
                $topRaces = $model->getCompetitiveRaces(null, 6);
                break;
        }

        require_once BASE_PATH . '/views/midterms/' . self::SECTIONS[$section] . '.php';
    }

    /**
     * Ververst odds en nieuws op de achtergrond als de cache te oud is.
     *
     * Primair draaien er paneel-cronjobs (DirectAdmin) die de fetch-scripts elk
     * uur (odds) en elke 3 uur (nieuws) draaien. Die scripts raken na afloop het
     * lock-bestand aan (cache/mt_refresh_*.lock). Deze verkeer-gestuurde refresh
     * is daarom alleen een vangnet: de intervallen liggen bewust ruimer dan de
     * cron, zodat een paginabezoek alleen een fetch start als de cron is
     * uitgevallen. Zo verversen we dubbel noch verbruiken we onnodig API-quota.
     * De refresh blokkeert de pagina nooit en faalt stil.
     */
    private function maybeAutoRefresh(): void
    {
        if ($this->db === null) {
            return; // Geen DB -> geen live data om te verversen (bv. lokaal).
        }
        try {
            $base = defined('BASE_PATH') ? BASE_PATH : (__DIR__ . '/..');
            $php = getenv('POLITIEKPRAAT_PHP_BIN') ?: '/usr/local/bin/php';
            if (!is_file($php)) {
                $php = 'php';
            }
            // Vangnet-intervallen ruimer dan de cron (odds 1u, nieuws 3u), zodat
            // de cron normaal gesproken wint en verkeer alleen bijspringt als de
            // cron uitvalt.
            $this->autoRefreshTask('odds', 5400, $base, $php, $base . '/scripts/midterms_fetch_polymarket.php');
            $this->autoRefreshTask('news', 14400, $base, $php, $base . '/scripts/midterms_fetch_news.php');
        } catch (Throwable $e) {
            error_log('Midterms auto-refresh fout: ' . $e->getMessage());
        }
    }

    /**
     * Start losgekoppeld een fetch-script als het lock-bestand ouder is dan het
     * interval. Het lock wordt direct ge-touched om gelijktijdige starts te
     * beperken; ook bij een mislukte fetch wachten we tot het volgende interval.
     */
    private function autoRefreshTask(string $task, int $interval, string $base, string $php, string $script): void
    {
        $cacheDir = $base . '/cache';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }
        $lock = $cacheDir . '/mt_refresh_' . $task . '.lock';
        $age = is_file($lock) ? (time() - (int) @filemtime($lock)) : PHP_INT_MAX;
        if ($age < $interval) {
            return;
        }
        @touch($lock); // claim het interval meteen
        if (!is_file($script) || !function_exists('exec')) {
            return;
        }
        $logsDir = $base . '/logs';
        if (!is_dir($logsDir)) {
            @mkdir($logsDir, 0775, true);
        }
        $log = $logsDir . '/midterms_' . $task . '.log';
        $cmd = 'nohup ' . escapeshellarg($php) . ' ' . escapeshellarg($script)
            . ' >> ' . escapeshellarg($log) . ' 2>&1 &';
        @exec($cmd);
    }

    /**
     * SEO meta per sub-sectie. Wordt door views/templates/header.php gelezen.
     */
    private function metaForSection(string $section): array
    {
        $base = 'Midterms 2026';
        $map = [
            'hub' => [
                'title' => 'Amerikaanse midterms 2026: odds, kaarten en uitleg',
                'description' => 'Volg de Amerikaanse tussentijdse verkiezingen van 2026: live voorspellingen, interactieve kaarten van Senaat, Huis en gouverneursraces, en uitleg in het Nederlands.',
            ],
            'senaat' => [
                'title' => 'Senaat 2026: races, kaart en voorspellingen - ' . $base,
                'description' => 'Welke partij wint de Amerikaanse Senaat in 2026? Interactieve kaart van de 35 Senaatsraces met ratings en live odds.',
            ],
            'huis' => [
                'title' => 'Huis van Afgevaardigden 2026: districtenkaart - ' . $base,
                'description' => 'Alle 435 districten van het Huis van Afgevaardigden in 2026, met een interactieve kaart, competitieve races en voorspellingen.',
            ],
            'gouverneurs' => [
                'title' => 'Gouverneursraces 2026: kaart en ratings - ' . $base,
                'description' => 'De 36 gouverneursraces van 2026 op een interactieve kaart, met ratings en uitleg per staat.',
            ],
            'voorverkiezingen' => [
                'title' => 'Voorverkiezingen en tijdlijn - ' . $base,
                'description' => 'De belangrijkste gebeurtenissen op weg naar de midterms van 2026: voorverkiezingen, sleutelmomenten en deadlines.',
            ],
            'nieuws' => [
                'title' => 'Laatste nieuws over de midterms 2026 - ' . $base,
                'description' => 'Het laatste nieuws over de Amerikaanse tussentijdse verkiezingen van 2026, met korte Nederlandse toelichting en bronvermelding.',
            ],
            'referenda' => [
                'title' => 'Referenda en ballot measures 2026 - ' . $base,
                'description' => 'De belangrijkste referenda en ballot measures bij de midterms van 2026, met thema, uitleg en voorspellingen.',
            ],
            'races' => [
                'title' => 'Belangrijke races en swing states - ' . $base,
                'description' => 'De meest spannende en beslissende races van de midterms 2026, van Senaat tot gouverneurs.',
            ],
            'uitleg' => [
                'title' => 'Hoe werken de Amerikaanse midterms? Uitleg - ' . $base,
                'description' => 'Wat zijn de midterms, hoe werken de Senaat, het Huis en de gouverneursraces, en waarom zijn ze belangrijk? Uitleg in het Nederlands.',
            ],
        ];

        return $map[$section] ?? $map['hub'];
    }
}

// --------------------------------------------------------------------------
// Dispatch
// --------------------------------------------------------------------------
$midtermsController = new MidtermsController();

if (isset($_GET['feed']) && $_GET['feed'] !== '') {
    $midtermsController->feed((string) $_GET['feed']);
} else {
    $section = isset($_GET['sectie']) && $_GET['sectie'] !== '' ? (string) $_GET['sectie'] : 'hub';
    $midtermsController->show($section);
}
