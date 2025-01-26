<?php
require_once 'includes/Database.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalParties.php';

class ThemasController {
    private $db;
    private $openDataAPI;
    private $politicalParties;

    public function __construct() {
        $this->db = new Database();
        $this->openDataAPI = new OpenDataAPI();
        $this->politicalParties = new PoliticalParties();
    }

    public function view($thema_slug) {
        // Haal thema informatie op
        $thema = $this->openDataAPI->getThemaBySlug($thema_slug);
        
        if (!$thema) {
            header('Location: ' . URLROOT . '/404');
            exit();
        }

        // Haal standpunten op van politieke partijen
        $linksePartijen = $this->politicalParties->getLinkseStandpunten($thema_slug);
        $rechtsePartijen = $this->politicalParties->getRechtseStandpunten($thema_slug);
        
        // Haal gerelateerde debatten op
        $gerelateerdeDebatten = $this->openDataAPI->getGerelateerdePolitiekeDebatten($thema_slug);

        require_once 'views/themas/view.php';
    }
}

// Instantieer de controller
$themasController = new ThemasController();

// Bepaal de actie op basis van URL parameters
$action = isset($params[0]) ? $params[0] : 'index';
$slug = isset($params[1]) ? $params[1] : null;

// Voer de juiste methode uit
if ($action === 'view' && $slug) {
    $themasController->view($slug);
} else {
    header('Location: ' . URLROOT . '/404');
    exit();
} 