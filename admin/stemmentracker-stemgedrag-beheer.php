<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$db = new Database();
$message = '';
$messageType = '';

// Get motie_id from URL or default to first motie
$motie_id = $_GET['motie_id'] ?? null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_votes') {
        $current_motie_id = $_POST['motie_id'] ?? 0;
        $votes = $_POST['votes'] ?? [];
        $opmerkingen = $_POST['opmerkingen'] ?? [];
        
        try {
            $db->beginTransaction();
            
            // Verwijder bestaande stemmen voor deze motie
            $db->query("DELETE FROM stemmentracker_votes WHERE motie_id = :motie_id");
            $db->bind(':motie_id', $current_motie_id);
            $db->execute();
            
            // Voeg nieuwe stemmen toe
            foreach ($votes as $party_id => $vote) {
                if (!empty($vote)) {
                    $opmerking = $opmerkingen[$party_id] ?? '';
                    
                    $db->query("INSERT INTO stemmentracker_votes (motie_id, party_id, vote, opmerking) 
                               VALUES (:motie_id, :party_id, :vote, :opmerking)");
                    $db->bind(':motie_id', $current_motie_id);
                    $db->bind(':party_id', $party_id);
                    $db->bind(':vote', $vote);
                    $db->bind(':opmerking', $opmerking ?: null);
                    $db->execute();
                }
            }
            
            $db->commit();
            $message = 'Stemgedrag succesvol opgeslagen';
            $messageType = 'success';
            
        } catch (Exception $e) {
            $db->rollback();
            $message = 'Fout bij opslaan stemgedrag: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Haal alle moties op voor dropdown
$db->query("SELECT id, title, datum_stemming FROM stemmentracker_moties ORDER BY datum_stemming DESC");
$alle_moties = $db->resultSet();

// Als geen motie geselecteerd, selecteer de eerste
if (!$motie_id && !empty($alle_moties)) {
    $motie_id = $alle_moties[0]->id;
}

// Haal geselecteerde motie details op
$selected_motie = null;
if ($motie_id) {
    $db->query("SELECT * FROM stemmentracker_moties WHERE id = :id");
    $db->bind(':id', $motie_id);
    $selected_motie = $db->single();
}

// Haal partijen op met hun huidige stemgedrag voor deze motie
$parties_with_votes = [];
if ($motie_id) {
    $db->query("SELECT sp.*, sv.vote, sv.opmerking 
                FROM stemwijzer_parties sp
                LEFT JOIN stemmentracker_votes sv ON sp.id = sv.party_id AND sv.motie_id = :motie_id
                WHERE sp.is_active = 1
                ORDER BY sp.name");
    $db->bind(':motie_id', $motie_id);
    $parties_with_votes = $db->resultSet();
}

// Statistieken
$stats = null;
if ($motie_id) {
    $db->query("SELECT 
                    COUNT(*) as total_parties,
                    SUM(CASE WHEN vote = 'voor' THEN 1 ELSE 0 END) as voor_count,
                    SUM(CASE WHEN vote = 'tegen' THEN 1 ELSE 0 END) as tegen_count,
                    SUM(CASE WHEN vote = 'niet_gestemd' THEN 1 ELSE 0 END) as niet_gestemd_count,
                    SUM(CASE WHEN vote = 'afwezig' THEN 1 ELSE 0 END) as afwezig_count
                FROM stemmentracker_votes 
                WHERE motie_id = :motie_id");
    $db->bind(':motie_id', $motie_id);
    $stats = $db->single();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stemgedrag Beheer - StemmenTracker | PolitiekPraat Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white p-6">
            <h2 class="text-2xl font-bold mb-8">Admin Panel</h2>
            <nav class="space-y-4">
                <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-800">Dashboard</a>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-blue-200 uppercase tracking-wider">StemmenTracker</h3>
                    <a href="stemmentracker-motie-beheer.php" class="block py-2 px-4 rounded hover:bg-blue-800">Moties Beheer</a>
                    <a href="stemmentracker-motie-toevoegen.php" class="block py-2 px-4 rounded hover:bg-blue-800">Motie Toevoegen</a>
                    <a href="stemmentracker-stemgedrag-beheer.php" class="block py-2 px-4 rounded bg-blue-800">Stemgedrag Beheer</a>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-blue-200 uppercase tracking-wider">Stemwijzer</h3>
                    <a href="stemwijzer-dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-800">Stemwijzer Dashboard</a>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Stemgedrag Beheer</h1>
                    <a href="stemmentracker-motie-beheer.php" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Terug naar moties
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Motie selectie -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Selecteer Motie</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <select id="motie_select" onchange="changeMotie()" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecteer een motie...</option>
                                <?php foreach ($alle_moties as $motie): ?>
                                    <option value="<?php echo $motie->id; ?>" <?php echo $motie_id == $motie->id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($motie->title); ?> 
                                        (<?php echo date('d-m-Y', strtotime($motie->datum_stemming)); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <a href="stemmentracker-motie-toevoegen.php" 
                               class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-center block">
                                <i class="fas fa-plus mr-2"></i>Nieuwe Motie
                            </a>
                        </div>
                    </div>
                </div>

                <?php if ($selected_motie): ?>
                    <!-- Motie details -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Motie Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Titel</p>
                                <p class="font-medium"><?php echo htmlspecialchars($selected_motie->title); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Datum Stemming</p>
                                <p class="font-medium"><?php echo date('d-m-Y', strtotime($selected_motie->datum_stemming)); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Onderwerp</p>
                                <p class="font-medium"><?php echo htmlspecialchars($selected_motie->onderwerp); ?></p>
                            </div>
                            <?php if ($selected_motie->uitslag): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Uitslag</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        <?php 
                                        echo $selected_motie->uitslag === 'aangenomen' ? 'bg-green-100 text-green-800' : 
                                             ($selected_motie->uitslag === 'verworpen' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                                        ?>">
                                        <?php echo ucfirst($selected_motie->uitslag); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Beschrijving</p>
                                <p class="text-sm"><?php echo nl2br(htmlspecialchars($selected_motie->description)); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistieken -->
                    <?php if ($stats && $stats->total_parties > 0): ?>
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4">Stemming Overzicht</h3>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-700"><?php echo $stats->total_parties; ?></div>
                                    <div class="text-sm text-gray-500">Partijen</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600"><?php echo $stats->voor_count; ?></div>
                                    <div class="text-sm text-gray-500">Voor</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600"><?php echo $stats->tegen_count; ?></div>
                                    <div class="text-sm text-gray-500">Tegen</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600"><?php echo $stats->niet_gestemd_count; ?></div>
                                    <div class="text-sm text-gray-500">Niet gestemd</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-600"><?php echo $stats->afwezig_count; ?></div>
                                    <div class="text-sm text-gray-500">Afwezig</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Stemgedrag formulier -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Stemgedrag per Partij</h3>
                        
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="save_votes">
                            <input type="hidden" name="motie_id" value="<?php echo $motie_id; ?>">
                            
                            <?php if (empty($parties_with_votes)): ?>
                                <p class="text-gray-500 text-center py-8">Geen actieve partijen gevonden</p>
                            <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partij</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stemgedrag</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opmerking</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php foreach ($parties_with_votes as $party): ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <?php if ($party->logo_url): ?>
                                                                <img src="<?php echo htmlspecialchars($party->logo_url); ?>" 
                                                                     alt="<?php echo htmlspecialchars($party->name); ?>" 
                                                                     class="w-8 h-8 rounded mr-3">
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    <?php echo htmlspecialchars($party->name); ?>
                                                                </div>
                                                                <div class="text-sm text-gray-500">
                                                                    <?php echo htmlspecialchars($party->short_name); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <select name="votes[<?php echo $party->id; ?>]" 
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                            <option value="">Selecteer...</option>
                                                            <option value="voor" <?php echo $party->vote === 'voor' ? 'selected' : ''; ?>>Voor</option>
                                                            <option value="tegen" <?php echo $party->vote === 'tegen' ? 'selected' : ''; ?>>Tegen</option>
                                                            <option value="niet_gestemd" <?php echo $party->vote === 'niet_gestemd' ? 'selected' : ''; ?>>Niet gestemd</option>
                                                            <option value="afwezig" <?php echo $party->vote === 'afwezig' ? 'selected' : ''; ?>>Afwezig</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="text" name="opmerkingen[<?php echo $party->id; ?>]" 
                                                               value="<?php echo htmlspecialchars($party->opmerking ?? ''); ?>"
                                                               placeholder="Optionele opmerking..."
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="flex justify-end pt-6 border-t border-gray-200">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-save mr-2"></i>Stemgedrag Opslaan
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <i class="fas fa-vote-yea text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Geen motie geselecteerd</h3>
                        <p class="text-gray-500">Selecteer een motie om het stemgedrag te beheren</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function changeMotie() {
            const motieId = document.getElementById('motie_select').value;
            if (motieId) {
                window.location.href = 'stemmentracker-stemgedrag-beheer.php?motie_id=' + motieId;
            }
        }
    </script>
</body>
</html>