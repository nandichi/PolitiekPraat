<?php
require_once 'includes/Database.php';
require_once 'includes/config.php';

class StemmenTrackerController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        // Haal filters op uit URL parameters
        $filters = $this->getFilters();
        
        // Haal moties op met filters
        $moties = $this->getMoties($filters);
        
        // Haal themas op voor filter dropdown
        $themas = $this->getThemas();
        
        // Haal onderwerpen op voor filter dropdown
        $onderwerpen = $this->getOnderwerpen();
        
        // Haal uitslag opties op
        $uitslagen = ['aangenomen', 'verworpen', 'ingetrokken'];
        
        // Haal statistieken op
        $statistieken = $this->getStatistieken();
        
        require_once BASE_PATH . '/views/stemmentracker/index.php';
    }

    public function detail($motie_id) {
        // Haal motie details op
        $motie = $this->getMotieDetails($motie_id);
        
        if (!$motie) {
            header('Location: ' . URLROOT . '/404');
            exit();
        }
        
        // Haal stemgedrag op voor deze motie
        $stemgedrag = $this->getStemgedrag($motie_id);
        
        // Haal motie themas op
        $motie_themas = $this->getMotieThemas($motie_id);
        
        require_once BASE_PATH . '/views/stemmentracker/detail.php';
    }

    private function getFilters() {
        return [
            'search' => $_GET['search'] ?? '',
            'thema' => $_GET['thema'] ?? '',
            'onderwerp' => $_GET['onderwerp'] ?? '',
            'uitslag' => $_GET['uitslag'] ?? '',
            'jaar' => $_GET['jaar'] ?? '',
            'sort' => $_GET['sort'] ?? 'datum_desc'
        ];
    }

    private function getMoties($filters) {
        // Bouw WHERE clause op basis van filters
        $where_conditions = ['sm.is_active = 1'];
        $bind_params = [];

        if (!empty($filters['search'])) {
            $where_conditions[] = "(sm.title LIKE :search OR sm.description LIKE :search OR sm.indiener LIKE :search)";
            $bind_params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['thema'])) {
            $where_conditions[] = "EXISTS (SELECT 1 FROM stemmentracker_motie_themas smt 
                                          JOIN stemmentracker_themas st ON smt.thema_id = st.id 
                                          WHERE smt.motie_id = sm.id AND st.name = :thema)";
            $bind_params[':thema'] = $filters['thema'];
        }

        if (!empty($filters['onderwerp'])) {
            $where_conditions[] = "sm.onderwerp LIKE :onderwerp";
            $bind_params[':onderwerp'] = '%' . $filters['onderwerp'] . '%';
        }

        if (!empty($filters['uitslag'])) {
            $where_conditions[] = "sm.uitslag = :uitslag";
            $bind_params[':uitslag'] = $filters['uitslag'];
        }

        if (!empty($filters['jaar'])) {
            $where_conditions[] = "YEAR(sm.datum_stemming) = :jaar";
            $bind_params[':jaar'] = $filters['jaar'];
        }

        $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

        // Bepaal sorting
        $order_clause = match($filters['sort']) {
            'datum_asc' => 'ORDER BY sm.datum_stemming ASC',
            'titel' => 'ORDER BY sm.title ASC',
            'onderwerp' => 'ORDER BY sm.onderwerp ASC, sm.datum_stemming DESC',
            default => 'ORDER BY sm.datum_stemming DESC'
        };

        $query = "SELECT sm.*, 
                  GROUP_CONCAT(st.name SEPARATOR ', ') as themas,
                  GROUP_CONCAT(st.color SEPARATOR ',') as thema_colors,
                  (SELECT COUNT(*) FROM stemmentracker_votes sv WHERE sv.motie_id = sm.id) as vote_count,
                  (SELECT COUNT(*) FROM stemmentracker_votes sv WHERE sv.motie_id = sm.id AND sv.vote = 'voor') as voor_count,
                  (SELECT COUNT(*) FROM stemmentracker_votes sv WHERE sv.motie_id = sm.id AND sv.vote = 'tegen') as tegen_count
                  FROM stemmentracker_moties sm
                  LEFT JOIN stemmentracker_motie_themas smt ON sm.id = smt.motie_id
                  LEFT JOIN stemmentracker_themas st ON smt.thema_id = st.id
                  $where_clause
                  GROUP BY sm.id
                  $order_clause";

        $this->db->query($query);
        foreach ($bind_params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    private function getThemas() {
        $this->db->query("SELECT DISTINCT name FROM stemmentracker_themas WHERE is_active = 1 ORDER BY name");
        return $this->db->resultSet();
    }

    private function getOnderwerpen() {
        $this->db->query("SELECT DISTINCT onderwerp FROM stemmentracker_moties WHERE is_active = 1 ORDER BY onderwerp");
        return $this->db->resultSet();
    }

    private function getStatistieken() {
        // Totaal aantal moties
        $this->db->query("SELECT COUNT(*) as total FROM stemmentracker_moties WHERE is_active = 1");
        $total_moties = $this->db->single()->total;

        // Moties per uitslag
        $this->db->query("SELECT uitslag, COUNT(*) as count 
                         FROM stemmentracker_moties 
                         WHERE is_active = 1 AND uitslag IS NOT NULL 
                         GROUP BY uitslag");
        $uitslagen = $this->db->resultSet();

        // Recente activiteit (laatste 3 maanden)
        $this->db->query("SELECT COUNT(*) as count 
                         FROM stemmentracker_moties 
                         WHERE is_active = 1 AND datum_stemming >= DATE_SUB(NOW(), INTERVAL 3 MONTH)");
        $recent_count = $this->db->single()->count;

        // Meest actieve themas
        $this->db->query("SELECT st.name, st.color, COUNT(*) as count
                         FROM stemmentracker_themas st
                         JOIN stemmentracker_motie_themas smt ON st.id = smt.thema_id
                         JOIN stemmentracker_moties sm ON smt.motie_id = sm.id
                         WHERE st.is_active = 1 AND sm.is_active = 1
                         GROUP BY st.id
                         ORDER BY count DESC
                         LIMIT 5");
        $top_themas = $this->db->resultSet();

        return [
            'total_moties' => $total_moties,
            'uitslagen' => $uitslagen,
            'recent_count' => $recent_count,
            'top_themas' => $top_themas
        ];
    }

    private function getMotieDetails($motie_id) {
        $this->db->query("SELECT * FROM stemmentracker_moties WHERE id = :id AND is_active = 1");
        $this->db->bind(':id', $motie_id);
        return $this->db->single();
    }

    private function getStemgedrag($motie_id) {
        $this->db->query("SELECT sv.*, sp.name as party_name, sp.short_name, sp.logo_url
                         FROM stemmentracker_votes sv
                         JOIN stemwijzer_parties sp ON sv.party_id = sp.id
                         WHERE sv.motie_id = :motie_id AND sp.is_active = 1
                         ORDER BY sp.name");
        $this->db->bind(':motie_id', $motie_id);
        return $this->db->resultSet();
    }

    private function getMotieThemas($motie_id) {
        $this->db->query("SELECT st.* 
                         FROM stemmentracker_themas st
                         JOIN stemmentracker_motie_themas smt ON st.id = smt.thema_id
                         WHERE smt.motie_id = :motie_id AND st.is_active = 1");
        $this->db->bind(':motie_id', $motie_id);
        return $this->db->resultSet();
    }
}

// Initialize controller
$stemmenTrackerController = new StemmenTrackerController();

// Route handling
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'detail':
        $motie_id = $_GET['id'] ?? null;
        if ($motie_id) {
            $stemmenTrackerController->detail($motie_id);
        } else {
            $stemmenTrackerController->index();
        }
        break;
    
    default:
        $stemmenTrackerController->index();
        break;
}
?>