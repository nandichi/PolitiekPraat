<?php
require_once 'includes/Database.php';
require_once 'includes/NewsAPI.php';
require_once 'includes/PoliticalParties.php';
require_once 'includes/config.php';

class ThemaController {
    private $db;
    private $newsAPI;
    private $politicalParties;

    public function __construct() {
        $this->db = new Database();
        $this->newsAPI = new NewsAPI();
        $this->politicalParties = new PoliticalParties();
    }

    public function view($thema_slug) {
        // Haal thema informatie op
        $thema = $this->getThemaInfo($thema_slug);
        
        if (!$thema) {
            header('Location: ' . URLROOT . '/404');
            exit();
        }

        // Haal standpunten op van politieke partijen
        $linksePartijen = $this->politicalParties->getLinkseStandpunten($thema_slug);
        $rechtsePartijen = $this->politicalParties->getRechtseStandpunten($thema_slug);

        // Haal nieuws op over dit thema
        $themaNews = $this->newsAPI->getThemaNews($thema_slug);

        require_once 'views/thema/view.php';
    }

    private function getThemaInfo($slug) {
        $themas = [
            'klimaatbeleid' => [
                'title' => 'Klimaatbeleid',
                'icon' => '🌍',
                'description' => 'Actuele ontwikkelingen in klimaatbeleid',
                'long_description' => 'Het Nederlandse klimaatbeleid staat voor grote uitdagingen in de transitie naar een duurzame toekomst. Van energietransitie tot CO2-reductie, dit thema raakt aan vele aspecten van onze samenleving.',
                'key_points' => [
                    'CO2-reductie doelstellingen',
                    'Energietransitie',
                    'Duurzame energie',
                    'Klimaatadaptatie'
                ]
            ],
            'woningmarkt' => [
                'title' => 'Woningmarkt',
                'icon' => '🏠',
                'description' => 'Laatste updates woningmarkt',
                'long_description' => 'De Nederlandse woningmarkt staat onder druk met uitdagingen rond betaalbaarheid, beschikbaarheid en duurzaamheid van woningen.',
                'key_points' => [
                    'Woningtekort',
                    'Betaalbaarheid',
                    'Huurmarkt',
                    'Verduurzaming woningen'
                ]
            ],
            'economie' => [
                'title' => 'Economie',
                'icon' => '💶',
                'description' => 'Economische ontwikkelingen',
                'long_description' => 'De Nederlandse economie staat voor verschillende uitdagingen en kansen in een snel veranderende wereldeconomie.',
                'key_points' => [
                    'Economische groei',
                    'Inflatie',
                    'Arbeidsmarkt',
                    'Internationale handel'
                ]
            ],
            'zorg' => [
                'title' => 'Zorg',
                'icon' => '🏥',
                'description' => 'Actuele zorgthema\'s',
                'long_description' => 'De gezondheidszorg in Nederland staat voor uitdagingen op het gebied van toegankelijkheid, betaalbaarheid en kwaliteit.',
                'key_points' => [
                    'Zorgkosten',
                    'Personeelstekorten',
                    'Wachtlijsten',
                    'Digitalisering zorg'
                ]
            ],
            'onderwijs' => [
                'title' => 'Onderwijs',
                'icon' => '📚',
                'description' => 'Onderwijsontwikkelingen',
                'long_description' => 'Het Nederlandse onderwijs staat voor uitdagingen op het gebied van kwaliteit, toegankelijkheid en modernisering.',
                'key_points' => [
                    'Onderwijskwaliteit',
                    'Lerarentekort',
                    'Digitalisering',
                    'Kansenongelijkheid'
                ]
            ],
            'arbeidsmarkt' => [
                'title' => 'Arbeidsmarkt',
                'icon' => '💼',
                'description' => 'Arbeidsmarkt updates',
                'long_description' => 'De Nederlandse arbeidsmarkt ondergaat belangrijke veranderingen met impact op werkgevers en werknemers.',
                'key_points' => [
                    'Flexwerk',
                    'Arbeidsparticipatie',
                    'Scholing',
                    'Arbeidsvoorwaarden'
                ]
            ],
            'immigratie' => [
                'title' => 'Immigratie',
                'icon' => '🌐',
                'description' => 'Immigratie en integratie',
                'long_description' => 'Het Nederlandse immigratiebeleid en de integratie van nieuwkomers zijn belangrijke maatschappelijke thema\'s die veel discussie oproepen.',
                'key_points' => [
                    'Asielbeleid',
                    'Arbeidsmigratie',
                    'Integratie',
                    'Sociale cohesie'
                ]
            ],
            'veiligheid' => [
                'title' => 'Veiligheid',
                'icon' => '🛡️',
                'description' => 'Nationale veiligheid',
                'long_description' => 'De nationale veiligheid omvat verschillende aspecten, van criminaliteitsbestrijding tot cybersecurity en terrorismebestrijding.',
                'key_points' => [
                    'Criminaliteitsbestrijding',
                    'Cyberveiligheid',
                    'Terrorismebestrijding',
                    'Politiecapaciteit'
                ]
            ],
            'duurzaamheid' => [
                'title' => 'Duurzaamheid',
                'icon' => '♻️',
                'description' => 'Duurzame ontwikkeling',
                'long_description' => 'De transitie naar een duurzame samenleving vraagt om ingrijpende veranderingen in hoe we leven, werken en consumeren.',
                'key_points' => [
                    'Circulaire economie',
                    'Biodiversiteit',
                    'Duurzame landbouw',
                    'Afvalreductie'
                ]
            ]
        ];

        return isset($themas[$slug]) ? $themas[$slug] : null;
    }
}

// Instantieer de controller
$themaController = new ThemaController();

// Haal de slug uit de URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Roep de view methode aan
$themaController->view($slug); 