<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';

echo "=========================================\n";
echo "   PolitiekPraat Peiling Update Script   \n";
echo "=========================================\n\n";

// Nieuwe peiling data gebaseerd op de afbeelding van Peil.nl d.d. 26-07-2025
$new_polls = [
    'pvv' => 29,
    'gl-pvda' => 27,
    'cda' => 21,
    'vvd' => 19,
    'd66' => 10,
    'ja21' => 8,
    'sp' => 8,
    'bbb' => 5,
    'pvdd' => 5, // Was PvdDieren in de afbeelding
    'fvd' => 4,
    'sgp' => 4,
    'denk' => 4,
    'volt' => 3,
    'cu' => 3, // Was ChristenUnie in de afbeelding
    'nsc' => 0
];

try {
    $db = new Database();
    echo "✓ Database connectie succesvol\n\n";

    $total_parties = count($new_polls);
    $updated_count = 0;
    $not_found_count = 0;

    echo "Starten met het updaten van de peilingen voor " . $total_parties . " partijen...\n\n";

    foreach ($new_polls as $party_key => $new_seats) {
        // Haal de huidige partijgegevens op
        $db->query("SELECT id, party_key, name, current_seats FROM political_parties WHERE party_key = :party_key");
        $db->bind(':party_key', $party_key);
        $party = $db->single();

        if ($party) {
            // Bereken het nieuwe percentage (totaal 150 zetels in de Tweede Kamer)
            $new_percentage = round(($new_seats / 150) * 100, 1);

            // Bereken het verschil met de TK2023 uitslag (current_seats)
            $change = $new_seats - $party->current_seats;

            // Creëer de nieuwe JSON data voor de 'polling' kolom
            $new_polling_data = json_encode([
                'seats' => $new_seats,
                'percentage' => $new_percentage,
                'change' => $change
            ]);

            // Update de database
            $db->query("UPDATE political_parties SET polling = :polling WHERE id = :id");
            $db->bind(':polling', $new_polling_data);
            $db->bind(':id', $party->id);
            
            if ($db->execute()) {
                echo "✓ [" . str_pad($party->name, 15) . "] Peiling geüpdatet naar " . $new_seats . " zetels.\n";
                $updated_count++;
            } else {
                echo "✗ [" . str_pad($party->name, 15) . "] Fout bij het updaten van de peiling.\n";
            }
        } else {
            echo "⚠ Partij met key '" . $party_key . "' niet gevonden in de database. Overgeslagen.\n";
            $not_found_count++;
        }
    }

    echo "\n\n================= KLAAR =================\n";
    echo "Update voltooid.\n";
    echo "- " . $updated_count . " partijen succesvol geüpdatet.\n";
    echo "- " . $not_found_count . " partijen niet gevonden en overgeslagen.\n";
    echo "=========================================\n";

} catch (Exception $e) {
    echo "\n\n❌ FATALE FOUT: " . $e->getMessage() . "\n";
    echo "Script gestopt.\n";
    exit(1);
}
?>
