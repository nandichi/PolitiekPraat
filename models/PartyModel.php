<?php
require_once __DIR__ . '/../includes/Database.php';

class PartyModel {
    private $db;

    private static $localLogoMap = [
        'PVV'     => '/public/images/party-logos/pvv.png',
        'VVD'     => '/public/images/party-logos/vvd.png',
        'D66'     => '/public/images/party-logos/d66.png',
        'CDA'     => '/public/images/party-logos/cda.png',
        'SP'      => '/public/images/party-logos/sp.png',
        'PvdD'    => '/public/images/party-logos/pvdd.png',
        'JA21'    => '/public/images/party-logos/ja21.png',
        'SGP'     => '/public/images/party-logos/sgp.png',
        'FvD'     => '/public/images/party-logos/fvd.png',
        'DENK'    => '/public/images/party-logos/denk.png',
        'Volt'    => '/public/images/party-logos/volt.png',
        'BBB'     => '/public/images/party-logos/bbb.png',
        'NSC'     => '/public/images/party-logos/nsc.png',
        'GL-PvdA' => '/public/images/party-logos/gl-pvda.png',
        'CU'      => '/public/images/party-logos/christenunie.svg',
    ];

    private static $localLeaderPhotoMap = [
        'PVV'     => '/partijleiders/geert.jpg',
        'VVD'     => '/partijleiders/dilan.jpg',
        'D66'     => '/partijleiders/rob.jpg',
        'CDA'     => '/partijleiders/Henri.jpg',
        'SP'      => '/partijleiders/jimmy.jpg',
        'PvdD'    => '/partijleiders/esther.jpg',
        'JA21'    => '/partijleiders/joost.jpg',
        'SGP'     => '/partijleiders/Chris.jpg',
        'FvD'     => '/partijleiders/thierry.jpg',
        'DENK'    => '/partijleiders/baarle.jpg',
        'Volt'    => '/partijleiders/dassen.jpg',
        'BBB'     => '/partijleiders/plas.jpg',
        'GL-PvdA' => '/partijleiders/frans.jpg',
        'NSC'     => '/partijleiders/omtzicht.jpg',
    ];

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Vervang externe of niet werkende afbeelding-URL's door lokale bestanden
     * wanneer die beschikbaar zijn. Externe bronnen zoals upload.wikimedia.org
     * en i.ibb.co/i.ibb.com leveren regelmatig HTTP 429/404 op door
     * hotlink-restricties; lokale assets zijn betrouwbaarder.
     */
    private static function preferLocalAsset($currentUrl, $localPath) {
        $localPath = (string) $localPath;
        if ($localPath === '') {
            return $currentUrl;
        }

        $absolutePath = dirname(__DIR__) . $localPath;
        if (!file_exists($absolutePath)) {
            return $currentUrl;
        }

        return $localPath;
    }

    private static function resolvePartyAssets($partyKey, array $party) {
        if (isset(self::$localLogoMap[$partyKey])) {
            $party['logo'] = self::preferLocalAsset($party['logo'] ?? '', self::$localLogoMap[$partyKey]);
        }

        if (isset(self::$localLeaderPhotoMap[$partyKey])) {
            $party['leader_photo'] = self::preferLocalAsset($party['leader_photo'] ?? '', self::$localLeaderPhotoMap[$partyKey]);
        }

        return $party;
    }

    /**
     * Haal alle actieve partijen op
     */
    public function getAllParties() {
        $stmt = $this->db->prepare("
            SELECT * FROM political_parties 
            WHERE is_active = 1 
            ORDER BY current_seats DESC
        ");
        $stmt->execute();

        $parties = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $party = [
                'name' => $row['name'],
                'leader' => $row['leader'],
                'logo' => $row['logo'],
                'leader_photo' => $row['leader_photo'],
                'description' => $row['description'],
                'leader_info' => $row['leader_info'],
                'standpoints' => json_decode($row['standpoints'], true),
                'current_seats' => $row['current_seats'],
                'polling' => json_decode($row['polling'], true),
                'perspectives' => json_decode($row['perspectives'], true),
                'color' => $row['color']
            ];

            $parties[$row['party_key']] = self::resolvePartyAssets($row['party_key'], $party);
        }

        return $parties;
    }

    /**
     * Haal één partij op
     */
    public function getParty($partyKey) {
        $stmt = $this->db->prepare("
            SELECT * FROM political_parties 
            WHERE party_key = :party_key AND is_active = 1
        ");
        $stmt->execute(['party_key' => $partyKey]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $party = [
            'name' => $row['name'],
            'leader' => $row['leader'],
            'logo' => $row['logo'],
            'leader_photo' => $row['leader_photo'],
            'description' => $row['description'],
            'leader_info' => $row['leader_info'],
            'standpoints' => json_decode($row['standpoints'], true),
            'current_seats' => $row['current_seats'],
            'polling' => json_decode($row['polling'], true),
            'perspectives' => json_decode($row['perspectives'], true),
            'color' => $row['color']
        ];

        return self::resolvePartyAssets($row['party_key'], $party);
    }
    
    /**
     * Update een partij
     */
    public function updateParty($partyKey, $data) {
        $stmt = $this->db->prepare("
            UPDATE political_parties 
            SET 
                name = :name,
                leader = :leader,
                logo = :logo,
                leader_photo = :leader_photo,
                description = :description,
                leader_info = :leader_info,
                standpoints = :standpoints,
                current_seats = :current_seats,
                polling = :polling,
                perspectives = :perspectives,
                color = :color,
                updated_at = NOW()
            WHERE party_key = :party_key
        ");
        
        return $stmt->execute([
            'party_key' => $partyKey,
            'name' => $data['name'],
            'leader' => $data['leader'],
            'logo' => $data['logo'],
            'leader_photo' => $data['leader_photo'],
            'description' => $data['description'],
            'leader_info' => $data['leader_info'],
            'standpoints' => json_encode($data['standpoints']),
            'current_seats' => $data['current_seats'],
            'polling' => json_encode($data['polling']),
            'perspectives' => json_encode($data['perspectives']),
            'color' => $data['color']
        ]);
    }
    
    /**
     * Voeg een nieuwe partij toe
     */
    public function addParty($partyKey, $data) {
        $stmt = $this->db->prepare("
            INSERT INTO political_parties (
                party_key, name, leader, logo, leader_photo, description, leader_info,
                standpoints, current_seats, polling, perspectives, color
            ) VALUES (
                :party_key, :name, :leader, :logo, :leader_photo, :description, :leader_info,
                :standpoints, :current_seats, :polling, :perspectives, :color
            )
        ");
        
        return $stmt->execute([
            'party_key' => $partyKey,
            'name' => $data['name'],
            'leader' => $data['leader'],
            'logo' => $data['logo'],
            'leader_photo' => $data['leader_photo'],
            'description' => $data['description'],
            'leader_info' => $data['leader_info'],
            'standpoints' => json_encode($data['standpoints']),
            'current_seats' => $data['current_seats'],
            'polling' => json_encode($data['polling']),
            'perspectives' => json_encode($data['perspectives']),
            'color' => $data['color']
        ]);
    }
    
    /**
     * Deactiveer een partij
     */
    public function deactivateParty($partyKey) {
        $stmt = $this->db->prepare("
            UPDATE political_parties 
            SET is_active = 0, updated_at = NOW() 
            WHERE party_key = :party_key
        ");
        
        return $stmt->execute(['party_key' => $partyKey]);
    }
    
    /**
     * Haal partijen statistieken op
     */
    public function getPartiesStats() {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_parties,
                SUM(current_seats) as total_seats,
                MAX(current_seats) as max_seats,
                MIN(current_seats) as min_seats,
                AVG(current_seats) as avg_seats
            FROM political_parties 
            WHERE is_active = 1
        ");
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?> 