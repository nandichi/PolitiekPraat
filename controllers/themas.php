<?php
require_once 'includes/Database.php';
require_once 'includes/OpenDataAPI.php';
require_once 'includes/PoliticalParties.php';
require_once 'includes/config.php';

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

    public function index() {
        // Alle thema's ophalen
        $themas = [
            [
                'title' => 'Klimaatbeleid',
                'icon' => '🌍',
                'description' => 'Het Nederlandse klimaatbeleid staat voor grote uitdagingen in de transitie naar een duurzame toekomst.',
                'slug' => 'klimaatbeleid',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Woningmarkt',
                'icon' => '🏠',
                'description' => 'De Nederlandse woningmarkt staat onder druk met uitdagingen rond betaalbaarheid en beschikbaarheid.',
                'slug' => 'woningmarkt',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Economie',
                'icon' => '💶',
                'description' => 'De Nederlandse economie staat voor verschillende uitdagingen en kansen in een snel veranderende wereldeconomie.',
                'slug' => 'economie',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Zorg',
                'icon' => '🏥',
                'description' => 'De gezondheidszorg in Nederland staat voor uitdagingen op het gebied van toegankelijkheid en betaalbaarheid.',
                'slug' => 'zorg',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Onderwijs',
                'icon' => '📚',
                'description' => 'Het Nederlandse onderwijs staat voor uitdagingen op het gebied van kwaliteit en toegankelijkheid.',
                'slug' => 'onderwijs',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Arbeidsmarkt',
                'icon' => '💼',
                'description' => 'De Nederlandse arbeidsmarkt ondergaat belangrijke veranderingen met impact op werkgevers en werknemers.',
                'slug' => 'arbeidsmarkt',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Immigratie',
                'icon' => '🌐',
                'description' => 'Het immigratiebeleid en de integratie van nieuwkomers blijft een belangrijk thema in Nederland.',
                'slug' => 'immigratie',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Veiligheid',
                'icon' => '🛡️',
                'description' => 'Nationale veiligheid en criminaliteitsbestrijding zijn continue aandachtspunten.',
                'slug' => 'veiligheid',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ],
            [
                'title' => 'Duurzaamheid',
                'icon' => '♻️',
                'description' => 'De transitie naar een duurzame samenleving brengt uitdagingen en kansen met zich mee.',
                'slug' => 'duurzaamheid',
                'stats' => [
                    'discussions' => rand(50, 150),
                    'followers' => rand(500, 1500)
                ]
            ]
        ];

        // Actuele thema's ophalen
        $actueleThemas = $this->openDataAPI->getActueleThemas();

        require_once 'views/themas/index.php';
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
    $themasController->index();
} 