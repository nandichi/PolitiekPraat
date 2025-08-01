<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

$db = new Database();

echo "Creating StemmenTracker database tables...\n\n";

try {
    // 1. Create stemmentracker_moties table
    $db->query("CREATE TABLE IF NOT EXISTS stemmentracker_moties (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        motie_nummer VARCHAR(50),
        kamerstuk_nummer VARCHAR(50),
        datum_stemming DATE NOT NULL,
        onderwerp VARCHAR(255) NOT NULL,
        indiener VARCHAR(255),
        uitslag ENUM('aangenomen', 'verworpen', 'ingetrokken') DEFAULT NULL,
        stemming_type ENUM('hoofdelijke', 'handopsteken') DEFAULT 'hoofdelijke',
        kamer_stuk_url VARCHAR(500),
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_datum (datum_stemming),
        INDEX idx_onderwerp (onderwerp),
        INDEX idx_active (is_active),
        INDEX idx_uitslag (uitslag)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemmentracker_moties\n";
    } else {
        echo "✗ Failed to create table: stemmentracker_moties\n";
    }

    // 2. Create stemmentracker_votes table
    $db->query("CREATE TABLE IF NOT EXISTS stemmentracker_votes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        motie_id INT NOT NULL,
        party_id INT NOT NULL,
        vote ENUM('voor', 'tegen', 'niet_gestemd', 'afwezig') NOT NULL,
        aantal_zetels INT DEFAULT 1,
        opmerking TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (motie_id) REFERENCES stemmentracker_moties(id) ON DELETE CASCADE,
        FOREIGN KEY (party_id) REFERENCES stemwijzer_parties(id) ON DELETE CASCADE,
        UNIQUE KEY unique_motie_party (motie_id, party_id),
        INDEX idx_motie (motie_id),
        INDEX idx_party (party_id),
        INDEX idx_vote (vote)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemmentracker_votes\n";
    } else {
        echo "✗ Failed to create table: stemmentracker_votes\n";
    }

    // 3. Create stemmentracker_themas table
    $db->query("CREATE TABLE IF NOT EXISTS stemmentracker_themas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        color VARCHAR(7) DEFAULT '#3B82F6',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_active (is_active)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemmentracker_themas\n";
    } else {
        echo "✗ Failed to create table: stemmentracker_themas\n";
    }

    // 4. Create stemmentracker_motie_themas table
    $db->query("CREATE TABLE IF NOT EXISTS stemmentracker_motie_themas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        motie_id INT NOT NULL,
        thema_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (motie_id) REFERENCES stemmentracker_moties(id) ON DELETE CASCADE,
        FOREIGN KEY (thema_id) REFERENCES stemmentracker_themas(id) ON DELETE CASCADE,
        UNIQUE KEY unique_motie_thema (motie_id, thema_id),
        INDEX idx_motie (motie_id),
        INDEX idx_thema (thema_id)
    )");
    
    if ($db->execute()) {
        echo "✓ Created table: stemmentracker_motie_themas\n";
    } else {
        echo "✗ Failed to create table: stemmentracker_motie_themas\n";
    }

    // 5. Insert some default themas
    $default_themas = [
        ['name' => 'Economie', 'description' => 'Moties gerelateerd aan economische onderwerpen', 'color' => '#10B981'],
        ['name' => 'Zorg', 'description' => 'Moties gerelateerd aan gezondheidszorg', 'color' => '#EF4444'],
        ['name' => 'Onderwijs', 'description' => 'Moties gerelateerd aan onderwijs', 'color' => '#F59E0B'],
        ['name' => 'Klimaat', 'description' => 'Moties gerelateerd aan klimaat en milieu', 'color' => '#22C55E'],
        ['name' => 'Immigratie', 'description' => 'Moties gerelateerd aan immigratie en integratie', 'color' => '#8B5CF6'],
        ['name' => 'Sociale Zekerheid', 'description' => 'Moties gerelateerd aan sociale zekerheid', 'color' => '#06B6D4'],
        ['name' => 'Justitie', 'description' => 'Moties gerelateerd aan justitie en veiligheid', 'color' => '#DC2626'],
        ['name' => 'Europa', 'description' => 'Moties gerelateerd aan Europese samenwerking', 'color' => '#2563EB']
    ];

    echo "\nInserting default themas...\n";
    foreach ($default_themas as $thema) {
        $stmt = $db->getConnection()->prepare("INSERT IGNORE INTO stemmentracker_themas (name, description, color) VALUES (?, ?, ?)");
        if ($stmt->execute([$thema['name'], $thema['description'], $thema['color']])) {
            echo "✓ Inserted thema: " . $thema['name'] . "\n";
        }
    }

    echo "\nStemmenTracker database tables created successfully!\n";
    echo "You can now proceed with adding moties and votes through the admin interface.\n";

} catch (Exception $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
}
?>