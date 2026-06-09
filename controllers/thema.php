<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/PoliticalParties.php';
require_once 'includes/config.php';

class ThemaController {
    private $db;
    private $newsAPI;
    private $politicalParties;

    /**
     * Oude (legacy) slugs die historisch in gebruik waren, gekoppeld aan de
     * huidige canonieke slugs uit de sitemap. Zo blijven oude links/bookmarks
     * werken zonder dubbele content.
     */
    private $legacyAliases = [
        'klimaatbeleid' => 'klimaat-en-energie',
        'woningmarkt'   => 'wonen',
        'economie'      => 'economie-en-financien',
        'zorg'          => 'zorg-en-welzijn',
        'arbeidsmarkt'  => 'economie-en-financien',
        'immigratie'    => 'migratie-en-asiel',
        'veiligheid'    => 'veiligheid-en-justitie',
        'duurzaamheid'  => 'klimaat-en-energie',
    ];

    public function __construct() {
        $this->db = new Database();
        $this->newsAPI = new NewsAPI();
        $this->politicalParties = new PoliticalParties();
    }

    public function view($thema_slug) {
        $thema_slug = strtolower(trim((string) $thema_slug));

        // Normaliseer legacy-slugs naar de canonieke slug.
        if (isset($this->legacyAliases[$thema_slug])) {
            $thema_slug = $this->legacyAliases[$thema_slug];
        }

        // Haal thema informatie op
        $thema = $this->getThemaInfo($thema_slug);

        if (!$thema) {
            header('Location: ' . URLROOT . '/404');
            exit();
        }

        // Sleutel waarmee de partijstandpunten zijn opgeslagen (kan afwijken
        // van de slug). Bij ontbreken vallen we terug op de slug zelf.
        $standpuntenKey = $thema['standpunten_key'] ?? $thema_slug;

        // Haal standpunten op van politieke partijen
        $linksePartijen = $this->politicalParties->getLinkseStandpunten($standpuntenKey);
        $rechtsePartijen = $this->politicalParties->getRechtseStandpunten($standpuntenKey);

        // Haal nieuws op over dit thema
        $themaNews = $this->newsAPI->getThemaNews($thema['news_key'] ?? $thema_slug);

        require_once 'views/thema/view.php';
    }

    /**
     * Canonieke thema-definities uit de gedeelde databron. De sleutels komen
     * exact overeen met de slugs in generate-sitemap.php en op de
     * themas-overzichtspagina, zodat er geen 404's meer ontstaan.
     */
    public static function getThemas() {
        return require __DIR__ . '/../includes/data/themas.php';
    }

    private function getThemaInfo($slug) {
        $themas = self::getThemas();
        return $themas[$slug] ?? null;
    }
}

// Instantieer de controller
$themaController = new ThemaController();

// Haal de slug uit de URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Roep de view methode aan
$themaController->view($slug);
