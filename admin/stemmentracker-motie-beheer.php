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

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'toggle_active') {
        $motieId = $_POST['motie_id'] ?? 0;
        $isActive = $_POST['is_active'] ?? 0;
        
        try {
            $db->query("UPDATE stemmentracker_moties SET is_active = :is_active WHERE id = :id");
            $db->bind(':is_active', $isActive);
            $db->bind(':id', $motieId);
            $db->execute();
            
            $message = 'Status succesvol bijgewerkt';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij bijwerken status: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'delete_motie') {
        $motieId = $_POST['motie_id'] ?? 0;
        
        try {
            // Verwijder eerst alle stemgedrag voor deze motie
            $db->query("DELETE FROM stemmentracker_votes WHERE motie_id = :motie_id");
            $db->bind(':motie_id', $motieId);
            $db->execute();
            
            // Verwijder de motie themas
            $db->query("DELETE FROM stemmentracker_motie_themas WHERE motie_id = :motie_id");
            $db->bind(':motie_id', $motieId);
            $db->execute();
            
            // Verwijder de motie zelf
            $db->query("DELETE FROM stemmentracker_moties WHERE id = :id");
            $db->bind(':id', $motieId);
            $db->execute();
            
            $message = 'Motie succesvol verwijderd';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij verwijderen motie: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Haal filters op
$onderwerp_filter = $_GET['onderwerp'] ?? '';
$thema_filter = $_GET['thema'] ?? '';
$uitslag_filter = $_GET['uitslag'] ?? '';
$search = $_GET['search'] ?? '';

// Bouw de WHERE clause op
$where_conditions = [];
$bind_params = [];

if (!empty($onderwerp_filter)) {
    $where_conditions[] = "sm.onderwerp LIKE :onderwerp";
    $bind_params[':onderwerp'] = '%' . $onderwerp_filter . '%';
}

if (!empty($thema_filter)) {
    $where_conditions[] = "EXISTS (SELECT 1 FROM stemmentracker_motie_themas smt 
                                  JOIN stemmentracker_themas st ON smt.thema_id = st.id 
                                  WHERE smt.motie_id = sm.id AND st.name = :thema)";
    $bind_params[':thema'] = $thema_filter;
}

if (!empty($uitslag_filter)) {
    $where_conditions[] = "sm.uitslag = :uitslag";
    $bind_params[':uitslag'] = $uitslag_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(sm.title LIKE :search OR sm.description LIKE :search OR sm.indiener LIKE :search)";
    $bind_params[':search'] = '%' . $search . '%';
}

$where_clause = !empty($where_conditions) ? ' WHERE ' . implode(' AND ', $where_conditions) : '';

// Haal moties op
$query = "SELECT sm.*, 
          GROUP_CONCAT(st.name SEPARATOR ', ') as themas,
          (SELECT COUNT(*) FROM stemmentracker_votes sv WHERE sv.motie_id = sm.id) as vote_count
          FROM stemmentracker_moties sm
          LEFT JOIN stemmentracker_motie_themas smt ON sm.id = smt.motie_id
          LEFT JOIN stemmentracker_themas st ON smt.thema_id = st.id
          $where_clause
          GROUP BY sm.id
          ORDER BY sm.datum_stemming DESC, sm.id DESC";

$db->query($query);
foreach ($bind_params as $param => $value) {
    $db->bind($param, $value);
}
$moties = $db->resultSet();

// Haal themas op voor filter
$db->query("SELECT DISTINCT name FROM stemmentracker_themas WHERE is_active = 1 ORDER BY name");
$themas = $db->resultSet();

// Haal onderwerpscategorieën op voor filter
$db->query("SELECT DISTINCT onderwerp FROM stemmentracker_moties ORDER BY onderwerp");
$onderwerpen = $db->resultSet();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StemmenTracker - Moties Beheer | PolitiekPraat Admin</title>
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
                    <a href="stemmentracker-motie-beheer.php" class="block py-2 px-4 rounded bg-blue-800">Moties Beheer</a>
                    <a href="stemmentracker-motie-toevoegen.php" class="block py-2 px-4 rounded hover:bg-blue-800">Motie Toevoegen</a>
                    <a href="stemmentracker-stemgedrag-beheer.php" class="block py-2 px-4 rounded hover:bg-blue-800">Stemgedrag Beheer</a>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-blue-200 uppercase tracking-wider">Stemwijzer</h3>
                    <a href="stemwijzer-dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-800">Stemwijzer Dashboard</a>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">StemmenTracker - Moties Beheer</h1>
                    <a href="stemmentracker-motie-toevoegen.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Nieuwe Motie
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zoeken</label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Zoek in titel, beschrijving, indiener..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Onderwerp</label>
                            <select name="onderwerp" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Alle onderwerpen</option>
                                <?php foreach ($onderwerpen as $onderwerp): ?>
                                    <option value="<?php echo htmlspecialchars($onderwerp->onderwerp); ?>" 
                                            <?php echo $onderwerp_filter === $onderwerp->onderwerp ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($onderwerp->onderwerp); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thema</label>
                            <select name="thema" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Alle themas</option>
                                <?php foreach ($themas as $thema): ?>
                                    <option value="<?php echo htmlspecialchars($thema->name); ?>" 
                                            <?php echo $thema_filter === $thema->name ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($thema->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uitslag</label>
                            <select name="uitslag" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Alle uitslagen</option>
                                <option value="aangenomen" <?php echo $uitslag_filter === 'aangenomen' ? 'selected' : ''; ?>>Aangenomen</option>
                                <option value="verworpen" <?php echo $uitslag_filter === 'verworpen' ? 'selected' : ''; ?>>Verworpen</option>
                                <option value="ingetrokken" <?php echo $uitslag_filter === 'ingetrokken' ? 'selected' : ''; ?>>Ingetrokken</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="stemmentracker-motie-beheer.php" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Moties lijst -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Moties (<?php echo count($moties); ?>)</h3>
                    </div>
                    
                    <?php if (empty($moties)): ?>
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>Geen moties gevonden</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Onderwerp</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Themas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uitslag</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stemmen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($moties as $motie): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($motie->title); ?>
                                                    </div>
                                                    <?php if ($motie->motie_nummer): ?>
                                                        <div class="text-sm text-gray-500">
                                                            Motie: <?php echo htmlspecialchars($motie->motie_nummer); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($motie->indiener): ?>
                                                        <div class="text-sm text-gray-500">
                                                            Indiener: <?php echo htmlspecialchars($motie->indiener); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo date('d-m-Y', strtotime($motie->datum_stemming)); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <?php echo htmlspecialchars($motie->onderwerp); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <?php echo $motie->themas ? htmlspecialchars($motie->themas) : '-'; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if ($motie->uitslag): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?php 
                                                        echo $motie->uitslag === 'aangenomen' ? 'bg-green-100 text-green-800' : 
                                                             ($motie->uitslag === 'verworpen' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                                                        ?>">
                                                        <?php echo ucfirst($motie->uitslag); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo $motie->vote_count; ?> partijen
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="action" value="toggle_active">
                                                    <input type="hidden" name="motie_id" value="<?php echo $motie->id; ?>">
                                                    <input type="hidden" name="is_active" value="<?php echo $motie->is_active ? 0 : 1; ?>">
                                                    <button type="submit" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?php echo $motie->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                        <?php echo $motie->is_active ? 'Actief' : 'Inactief'; ?>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="stemmentracker-motie-bewerken.php?id=<?php echo $motie->id; ?>" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="stemmentracker-stemgedrag-beheer.php?motie_id=<?php echo $motie->id; ?>" 
                                                       class="text-green-600 hover:text-green-900" title="Stemgedrag beheren">
                                                        <i class="fas fa-vote-yea"></i>
                                                    </a>
                                                    <form method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze motie wilt verwijderen?')">
                                                        <input type="hidden" name="action" value="delete_motie">
                                                        <input type="hidden" name="motie_id" value="<?php echo $motie->id; ?>">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>