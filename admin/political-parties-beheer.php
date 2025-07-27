<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../models/PartyModel.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$partyModel = new PartyModel();
$message = '';
$messageType = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_party':
            $partyData = [
                'name' => $_POST['name'] ?? '',
                'leader' => $_POST['leader'] ?? '',
                'logo' => $_POST['logo'] ?? '',
                'leader_photo' => $_POST['leader_photo'] ?? '',
                'description' => $_POST['description'] ?? '',
                'leader_info' => $_POST['leader_info'] ?? '',
                'standpoints' => [
                    'Immigratie' => $_POST['standpoint_immigratie'] ?? '',
                    'Klimaat' => $_POST['standpoint_klimaat'] ?? '',
                    'Zorg' => $_POST['standpoint_zorg'] ?? '',
                    'Energie' => $_POST['standpoint_energie'] ?? ''
                ],
                'current_seats' => (int)($_POST['current_seats'] ?? 0),
                'polling' => [
                    'seats' => (int)($_POST['polling_seats'] ?? 0),
                    'percentage' => (float)($_POST['polling_percentage'] ?? 0),
                    'change' => (int)($_POST['polling_change'] ?? 0)
                ],
                'perspectives' => [
                    'left' => $_POST['perspective_left'] ?? '',
                    'right' => $_POST['perspective_right'] ?? ''
                ],
                'color' => $_POST['color'] ?? '#000000'
            ];
            
            $partyKey = strtoupper($_POST['party_key'] ?? '');
            
            if ($partyModel->addParty($partyKey, $partyData)) {
                $message = "Partij '{$partyData['name']}' succesvol toegevoegd!";
                $messageType = 'success';
            } else {
                $message = "Fout bij het toevoegen van de partij.";
                $messageType = 'error';
            }
            break;
            
        case 'update_party':
            $partyKey = $_POST['party_key'] ?? '';
            $partyData = [
                'name' => $_POST['name'] ?? '',
                'leader' => $_POST['leader'] ?? '',
                'logo' => $_POST['logo'] ?? '',
                'leader_photo' => $_POST['leader_photo'] ?? '',
                'description' => $_POST['description'] ?? '',
                'leader_info' => $_POST['leader_info'] ?? '',
                'standpoints' => [
                    'Immigratie' => $_POST['standpoint_immigratie'] ?? '',
                    'Klimaat' => $_POST['standpoint_klimaat'] ?? '',
                    'Zorg' => $_POST['standpoint_zorg'] ?? '',
                    'Energie' => $_POST['standpoint_energie'] ?? ''
                ],
                'current_seats' => (int)($_POST['current_seats'] ?? 0),
                'polling' => [
                    'seats' => (int)($_POST['polling_seats'] ?? 0),
                    'percentage' => (float)($_POST['polling_percentage'] ?? 0),
                    'change' => (int)($_POST['polling_change'] ?? 0)
                ],
                'perspectives' => [
                    'left' => $_POST['perspective_left'] ?? '',
                    'right' => $_POST['perspective_right'] ?? ''
                ],
                'color' => $_POST['color'] ?? '#000000'
            ];
            
            if ($partyModel->updateParty($partyKey, $partyData)) {
                $message = "Partij '{$partyData['name']}' succesvol bijgewerkt!";
                $messageType = 'success';
            } else {
                $message = "Fout bij het bijwerken van de partij.";
                $messageType = 'error';
            }
            break;
            
        case 'deactivate_party':
            $partyKey = $_POST['party_key'] ?? '';
            if ($partyModel->deactivateParty($partyKey)) {
                $message = "Partij succesvol gedeactiveerd!";
                $messageType = 'success';
            } else {
                $message = "Fout bij het deactiveren van de partij.";
                $messageType = 'error';
            }
            break;
    }
}

// Get all parties
$parties = $partyModel->getAllParties();
$stats = $partyModel->getPartiesStats();

// Get party for editing if requested
$editParty = null;
$editPartyKey = '';
if (isset($_GET['edit'])) {
    $editPartyKey = $_GET['edit'];
    $editParty = $partyModel->getParty($editPartyKey);
}

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal {
    backdrop-filter: blur(8px);
}

.party-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.search-input {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.stats-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    backdrop-filter: blur(10px);
}

.form-section {
    background: rgba(248, 250, 252, 0.8);
    backdrop-filter: blur(5px);
}

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.5);
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <a href="stemwijzer-dashboard.php" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <h1 class="text-3xl md:text-4xl font-bold text-white">Partijen Beheer</h1>
                    </div>
                    <p class="text-blue-100 text-base md:text-lg">Beheer alle politieke partijen en hun informatie</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="openAddModal()" 
                            class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 flex items-center justify-center space-x-2 text-sm md:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Nieuwe Partij</span>
                    </button>
                    <button onclick="exportParties()" 
                            class="bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold flex items-center justify-center space-x-2 text-sm md:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Display -->
    <?php if ($message): ?>
    <div class="container mx-auto px-4 -mt-6 relative z-20">
        <div class="<?= $messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' ?> px-6 py-4 border rounded-xl">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <?php if ($messageType === 'success'): ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    <?php else: ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    <?php endif; ?>
                </svg>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card rounded-2xl p-6 border border-white/50 shadow-xl">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Partijen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total_parties'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card rounded-2xl p-6 border border-white/50 shadow-xl">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.5 1.5 0 003 15.546V12c0-6.627 5.373-12 12-12s12 5.373 12 12v3.546z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Zetels</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total_seats'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card rounded-2xl p-6 border border-white/50 shadow-xl">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Grootste Partij</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['max_seats'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card rounded-2xl p-6 border border-white/50 shadow-xl">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Gemiddeld</p>
                        <p class="text-3xl font-bold text-gray-800"><?= round($stats['avg_seats'] ?? 0, 1) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="glass-effect rounded-2xl p-6 mb-8 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Zoek partijen..." 
                               class="search-input w-full px-4 py-3 pl-12 rounded-xl border border-white/30 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <select id="seatFilter" class="search-input px-4 py-3 rounded-xl border border-white/30 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Alle zetels</option>
                        <option value="0-5">0-5 zetels</option>
                        <option value="6-15">6-15 zetels</option>
                        <option value="16-30">16-30 zetels</option>
                        <option value="31+">31+ zetels</option>
                    </select>
                    <button onclick="resetFilters()" class="bg-white/50 text-gray-700 px-6 py-3 rounded-xl hover:bg-white/70 transition-all duration-300">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Parties Grid -->
        <div id="partiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($parties as $key => $party): ?>
            <div class="party-card rounded-2xl shadow-xl border border-white/50 overflow-hidden card-hover party-item" 
                 data-party-name="<?= strtolower($party['name']) ?>" 
                 data-party-key="<?= strtolower($key) ?>"
                 data-seats="<?= $party['current_seats'] ?>">
                
                <!-- Party Header -->
                <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, <?= $party['color'] ?>15 0%, <?= $party['color'] ?>08 100%);">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-xl bg-white shadow-md p-2">
                                <img src="<?= htmlspecialchars($party['logo']) ?>" 
                                     alt="<?= htmlspecialchars($party['name']) ?>" 
                                     class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($party['name']) ?></h3>
                                <p class="text-gray-600"><?= htmlspecialchars($party['leader']) ?></p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: <?= $party['color'] ?>20; color: <?= $party['color'] ?>;">
                                        <?= $key ?>
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                        <?= $party['current_seats'] ?> zetels
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('dropdown-<?= $key ?>')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            <div id="dropdown-<?= $key ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                <a href="?edit=<?= $key ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 first:rounded-t-xl">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Bewerken
                                </a>
                                <button onclick="duplicateParty('<?= $key ?>')" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Dupliceren
                                </button>
                                <button onclick="confirmDelete('<?= $key ?>', '<?= htmlspecialchars($party['name']) ?>')" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 last:rounded-b-xl">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Verwijderen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Party Content -->
                <div class="p-6">
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?= htmlspecialchars(substr($party['description'], 0, 120)) ?>...
                    </p>
                    
                    <!-- Polling Data -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Huidige Peiling</h4>
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-800"><?= $party['polling']['seats'] ?></p>
                                <p class="text-xs text-gray-600">Zetels</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-800"><?= $party['polling']['percentage'] ?>%</p>
                                <p class="text-xs text-gray-600">Percentage</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold <?= $party['polling']['change'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $party['polling']['change'] >= 0 ? '+' : '' ?><?= $party['polling']['change'] ?>
                                </p>
                                <p class="text-xs text-gray-600">Verandering</p>
                            </div>
                        </div>
                    </div>

                    <!-- Key Standpoints -->
                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-800 text-sm">Standpunten</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach (array_slice($party['standpoints'], 0, 4) as $topic => $stance): ?>
                            <div class="bg-white rounded-lg p-2 border border-gray-100">
                                <p class="text-xs font-medium text-gray-600"><?= $topic ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars(substr($stance, 0, 30)) ?>...</p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Geen partijen gevonden</h3>
            <p class="text-gray-500">Probeer je zoekcriteria aan te passen</p>
        </div>
    </div>
</main>

<!-- Add Party Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 modal z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Nieuwe Partij Toevoegen</h2>
            <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" class="custom-scrollbar overflow-y-auto max-h-[calc(90vh-120px)]">
            <input type="hidden" name="action" value="add_party">
            
            <div class="p-6 space-y-8">
                <!-- Basic Information -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basis Informatie</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Code *</label>
                            <input type="text" name="party_key" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="bijv. VVD">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Naam *</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Volledige partijnaam">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partijleider *</label>
                            <input type="text" name="leader" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Naam van de partijleider">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Kleur *</label>
                            <input type="color" name="color" required class="w-full h-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo URL</label>
                            <input type="url" name="logo" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leider Foto URL</label>
                            <input type="url" name="leader_photo" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="/partijleiders/...">
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Beschrijvingen</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Beschrijving *</label>
                            <textarea name="description" required rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Uitgebreide beschrijving van de partij"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leider Informatie *</label>
                            <textarea name="leader_info" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Informatie over de partijleider"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Zetels & Polling -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Zetels & Peilingen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Huidige Zetels *</label>
                            <input type="number" name="current_seats" required min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Peiling Zetels</label>
                            <input type="number" name="polling_seats" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Peiling Percentage</label>
                            <input type="number" name="polling_percentage" step="0.1" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verandering</label>
                            <input type="number" name="polling_change" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Standpunten -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Standpunten</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Immigratie</label>
                            <textarea name="standpoint_immigratie" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Standpunt over immigratie"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Klimaat</label>
                            <textarea name="standpoint_klimaat" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Standpunt over klimaat"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zorg</label>
                            <textarea name="standpoint_zorg" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Standpunt over zorg"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Energie</label>
                            <textarea name="standpoint_energie" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Standpunt over energie"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Perspectieven -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Perspectieven</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Links Perspectief</label>
                            <textarea name="perspective_left" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Hoe linkse kiezers deze partij kunnen zien"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rechts Perspectief</label>
                            <textarea name="perspective_right" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Hoe rechtse kiezers deze partij kunnen zien"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                <button type="button" onclick="closeAddModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Annuleren
                </button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                    Partij Toevoegen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Party Modal -->
<?php if ($editParty): ?>
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Partij Bewerken: <?= htmlspecialchars($editParty['name']) ?></h2>
            <a href="?" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        
        <form method="POST" class="custom-scrollbar overflow-y-auto max-h-[calc(90vh-120px)]">
            <input type="hidden" name="action" value="update_party">
            <input type="hidden" name="party_key" value="<?= htmlspecialchars($editPartyKey) ?>">
            
            <div class="p-6 space-y-8">
                <!-- Basic Information -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basis Informatie</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Code</label>
                            <input type="text" value="<?= htmlspecialchars($editPartyKey) ?>" disabled class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-500">
                            <p class="text-xs text-gray-500 mt-1">Partij code kan niet worden gewijzigd</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Naam *</label>
                            <input type="text" name="name" required value="<?= htmlspecialchars($editParty['name']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partijleider *</label>
                            <input type="text" name="leader" required value="<?= htmlspecialchars($editParty['leader']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Kleur *</label>
                            <input type="color" name="color" required value="<?= htmlspecialchars($editParty['color']) ?>" class="w-full h-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo URL</label>
                            <input type="url" name="logo" value="<?= htmlspecialchars($editParty['logo']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leider Foto URL</label>
                            <input type="url" name="leader_photo" value="<?= htmlspecialchars($editParty['leader_photo']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Beschrijvingen</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partij Beschrijving *</label>
                            <textarea name="description" required rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['description']) ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leider Informatie *</label>
                            <textarea name="leader_info" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['leader_info']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Zetels & Polling -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Zetels & Peilingen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Huidige Zetels *</label>
                            <input type="number" name="current_seats" required min="0" value="<?= $editParty['current_seats'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Peiling Zetels</label>
                            <input type="number" name="polling_seats" min="0" value="<?= $editParty['polling']['seats'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Peiling Percentage</label>
                            <input type="number" name="polling_percentage" step="0.1" min="0" max="100" value="<?= $editParty['polling']['percentage'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verandering</label>
                            <input type="number" name="polling_change" value="<?= $editParty['polling']['change'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Standpunten -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Standpunten</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Immigratie</label>
                            <textarea name="standpoint_immigratie" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['standpoints']['Immigratie'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Klimaat</label>
                            <textarea name="standpoint_klimaat" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['standpoints']['Klimaat'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zorg</label>
                            <textarea name="standpoint_zorg" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['standpoints']['Zorg'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Energie</label>
                            <textarea name="standpoint_energie" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['standpoints']['Energie'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Perspectieven -->
                <div class="form-section rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Perspectieven</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Links Perspectief</label>
                            <textarea name="perspective_left" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['perspectives']['left'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rechts Perspectief</label>
                            <textarea name="perspective_right" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($editParty['perspectives']['right'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                <a href="?" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Annuleren
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                    Wijzigingen Opslaan
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
// Modal functions
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Dropdown functions
function toggleDropdown(id) {
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        if (dropdown.id !== id) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Toggle target dropdown
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('button') || !event.target.closest('[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

// Search and filter functions
function filterParties() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const seatFilter = document.getElementById('seatFilter').value;
    const parties = document.querySelectorAll('.party-item');
    let visibleCount = 0;

    parties.forEach(party => {
        const name = party.dataset.partyName;
        const key = party.dataset.partyKey;
        const seats = parseInt(party.dataset.seats);
        
        let matchesSearch = name.includes(searchTerm) || key.includes(searchTerm);
        let matchesSeats = true;
        
        if (seatFilter) {
            switch(seatFilter) {
                case '0-5':
                    matchesSeats = seats >= 0 && seats <= 5;
                    break;
                case '6-15':
                    matchesSeats = seats >= 6 && seats <= 15;
                    break;
                case '16-30':
                    matchesSeats = seats >= 16 && seats <= 30;
                    break;
                case '31+':
                    matchesSeats = seats >= 31;
                    break;
            }
        }
        
        if (matchesSearch && matchesSeats) {
            party.style.display = 'block';
            visibleCount++;
        } else {
            party.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    const emptyState = document.getElementById('emptyState');
    if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('seatFilter').value = '';
    filterParties();
}

// Add event listeners
document.getElementById('searchInput').addEventListener('input', filterParties);
document.getElementById('seatFilter').addEventListener('change', filterParties);

// Delete confirmation
function confirmDelete(partyKey, partyName) {
    if (confirm(`Weet je zeker dat je "${partyName}" wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="deactivate_party">
            <input type="hidden" name="party_key" value="${partyKey}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Duplicate party
function duplicateParty(partyKey) {
    // This would open the add modal with pre-filled data
    // For now, just show an alert
    alert('Dupliceren functionaliteit wordt nog geïmplementeerd');
}

// Export parties
function exportParties() {
    alert('Export functionaliteit wordt nog geïmplementeerd');
}

// Auto-close messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('[class*="bg-green-100"], [class*="bg-red-100"]');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 