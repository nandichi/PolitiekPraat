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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $motie_nummer = trim($_POST['motie_nummer'] ?? '');
    $kamerstuk_nummer = trim($_POST['kamerstuk_nummer'] ?? '');
    $datum_stemming = $_POST['datum_stemming'] ?? '';
    $onderwerp = trim($_POST['onderwerp'] ?? '');
    $indiener = trim($_POST['indiener'] ?? '');
    $uitslag = $_POST['uitslag'] ?? null;
    $stemming_type = $_POST['stemming_type'] ?? 'hoofdelijke';
    $kamer_stuk_url = trim($_POST['kamer_stuk_url'] ?? '');
    $selected_themas = $_POST['themas'] ?? [];
    
    $errors = [];
    
    // Validatie
    if (empty($title)) {
        $errors[] = 'Titel is verplicht';
    }
    if (empty($description)) {
        $errors[] = 'Beschrijving is verplicht';
    }
    if (empty($datum_stemming)) {
        $errors[] = 'Datum stemming is verplicht';
    }
    if (empty($onderwerp)) {
        $errors[] = 'Onderwerp is verplicht';
    }
    
    if (empty($errors)) {
        try {
            $db->beginTransaction();
            
            // Voeg motie toe
            $db->query("INSERT INTO stemmentracker_moties 
                       (title, description, motie_nummer, kamerstuk_nummer, datum_stemming, 
                        onderwerp, indiener, uitslag, stemming_type, kamer_stuk_url) 
                       VALUES (:title, :description, :motie_nummer, :kamerstuk_nummer, :datum_stemming, 
                               :onderwerp, :indiener, :uitslag, :stemming_type, :kamer_stuk_url)");
            
            $db->bind(':title', $title);
            $db->bind(':description', $description);
            $db->bind(':motie_nummer', $motie_nummer ?: null);
            $db->bind(':kamerstuk_nummer', $kamerstuk_nummer ?: null);
            $db->bind(':datum_stemming', $datum_stemming);
            $db->bind(':onderwerp', $onderwerp);
            $db->bind(':indiener', $indiener ?: null);
            $db->bind(':uitslag', $uitslag ?: null);
            $db->bind(':stemming_type', $stemming_type);
            $db->bind(':kamer_stuk_url', $kamer_stuk_url ?: null);
            
            $db->execute();
            $motie_id = $db->lastInsertId();
            
            // Voeg themas toe
            if (!empty($selected_themas)) {
                foreach ($selected_themas as $thema_id) {
                    $db->query("INSERT INTO stemmentracker_motie_themas (motie_id, thema_id) VALUES (:motie_id, :thema_id)");
                    $db->bind(':motie_id', $motie_id);
                    $db->bind(':thema_id', $thema_id);
                    $db->execute();
                }
            }
            
            $db->commit();
            
            $message = 'Motie succesvol toegevoegd';
            $messageType = 'success';
            
            // Reset form values
            $title = $description = $motie_nummer = $kamerstuk_nummer = $datum_stemming = '';
            $onderwerp = $indiener = $kamer_stuk_url = '';
            $uitslag = null;
            $stemming_type = 'hoofdelijke';
            $selected_themas = [];
            
        } catch (Exception $e) {
            $db->rollback();
            $message = 'Fout bij toevoegen motie: ' . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}

// Haal themas op
$db->query("SELECT * FROM stemmentracker_themas WHERE is_active = 1 ORDER BY name");
$themas = $db->resultSet();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motie Toevoegen - StemmenTracker | PolitiekPraat Admin</title>
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
                    <a href="stemmentracker-motie-toevoegen.php" class="block py-2 px-4 rounded bg-blue-800">Motie Toevoegen</a>
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
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Nieuwe Motie Toevoegen</h1>
                    <a href="stemmentracker-motie-beheer.php" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Terug naar overzicht
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-md p-8">
                    <form method="POST" class="space-y-6">
                        <!-- Basis informatie -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titel van de motie *
                                </label>
                                <input type="text" id="title" name="title" required
                                       value="<?php echo htmlspecialchars($title ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Bijv. Motie om het minimumloon te verhogen">
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Beschrijving *
                                </label>
                                <textarea id="description" name="description" rows="4" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Uitgebreide beschrijving van de motie"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                            </div>

                            <div>
                                <label for="motie_nummer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Motie nummer
                                </label>
                                <input type="text" id="motie_nummer" name="motie_nummer"
                                       value="<?php echo htmlspecialchars($motie_nummer ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Bijv. 36200-VIII-19">
                            </div>

                            <div>
                                <label for="kamerstuk_nummer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kamerstuk nummer
                                </label>
                                <input type="text" id="kamerstuk_nummer" name="kamerstuk_nummer"
                                       value="<?php echo htmlspecialchars($kamerstuk_nummer ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Bijv. 36200 VIII">
                            </div>

                            <div>
                                <label for="datum_stemming" class="block text-sm font-medium text-gray-700 mb-2">
                                    Datum stemming *
                                </label>
                                <input type="date" id="datum_stemming" name="datum_stemming" required
                                       value="<?php echo htmlspecialchars($datum_stemming ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="onderwerp" class="block text-sm font-medium text-gray-700 mb-2">
                                    Onderwerp/Categorie *
                                </label>
                                <input type="text" id="onderwerp" name="onderwerp" required
                                       value="<?php echo htmlspecialchars($onderwerp ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Bijv. Economie, Zorg, Onderwijs">
                            </div>

                            <div>
                                <label for="indiener" class="block text-sm font-medium text-gray-700 mb-2">
                                    Indiener
                                </label>
                                <input type="text" id="indiener" name="indiener"
                                       value="<?php echo htmlspecialchars($indiener ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Bijv. Kamerlid naam (VVD)">
                            </div>

                            <div>
                                <label for="uitslag" class="block text-sm font-medium text-gray-700 mb-2">
                                    Uitslag
                                </label>
                                <select id="uitslag" name="uitslag"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Nog niet bekend</option>
                                    <option value="aangenomen" <?php echo ($uitslag ?? '') === 'aangenomen' ? 'selected' : ''; ?>>Aangenomen</option>
                                    <option value="verworpen" <?php echo ($uitslag ?? '') === 'verworpen' ? 'selected' : ''; ?>>Verworpen</option>
                                    <option value="ingetrokken" <?php echo ($uitslag ?? '') === 'ingetrokken' ? 'selected' : ''; ?>>Ingetrokken</option>
                                </select>
                            </div>

                            <div>
                                <label for="stemming_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Type stemming
                                </label>
                                <select id="stemming_type" name="stemming_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="hoofdelijke" <?php echo ($stemming_type ?? 'hoofdelijke') === 'hoofdelijke' ? 'selected' : ''; ?>>Hoofdelijke stemming</option>
                                    <option value="handopsteken" <?php echo ($stemming_type ?? '') === 'handopsteken' ? 'selected' : ''; ?>>Handopsteken</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="kamer_stuk_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    Link naar kamerstuk
                                </label>
                                <input type="url" id="kamer_stuk_url" name="kamer_stuk_url"
                                       value="<?php echo htmlspecialchars($kamer_stuk_url ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="https://www.tweedekamer.nl/...">
                            </div>
                        </div>

                        <!-- Themas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Themas</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <?php foreach ($themas as $thema): ?>
                                    <label class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="themas[]" value="<?php echo $thema->id; ?>"
                                               <?php echo in_array($thema->id, $selected_themas ?? []) ? 'checked' : ''; ?>
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($thema->name); ?></span>
                                        <span class="w-3 h-3 rounded-full" style="background-color: <?php echo htmlspecialchars($thema->color); ?>"></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="stemmentracker-motie-beheer.php" 
                               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                Annuleren
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Motie Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>