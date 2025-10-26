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
                    'Economie' => $_POST['standpoint_economie'] ?? '',
                    'Onderwijs' => $_POST['standpoint_onderwijs'] ?? '',
                    'Veiligheid' => $_POST['standpoint_veiligheid'] ?? ''
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
                    'Economie' => $_POST['standpoint_economie'] ?? '',
                    'Onderwijs' => $_POST['standpoint_onderwijs'] ?? '',
                    'Veiligheid' => $_POST['standpoint_veiligheid'] ?? ''
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.hero-gradient {
    background: linear-gradient(135deg, #0f2a44 0%, #1a365d 50%, #2d4a6b 100%);
    position: relative;
    overflow: hidden;
}

.hero-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(196, 30, 58, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(196, 30, 58, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.gradient-text {
    background: linear-gradient(135deg, #d63856 0%, #c41e3a 50%, #9e1829 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.card-primary {
    background: white;
    border: 1px solid rgba(26, 54, 93, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -6px rgba(26, 54, 93, 0.12), 0 6px 12px -3px rgba(26, 54, 93, 0.08);
    border-color: rgba(26, 54, 93, 0.15);
}

.party-card {
    background: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.party-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--party-color, #c41e3a);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.party-card:hover::before {
    transform: scaleX(1);
}

.party-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #1a365d 0%, #2d4a6b 100%);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0f2a44 0%, #1a365d 100%);
    transform: translateY(-1px);
    box-shadow: 0 8px 16px -4px rgba(26, 54, 93, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #c41e3a 0%, #9e1829 100%);
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #9e1829 0%, #7d1320 100%);
    transform: translateY(-1px);
    box-shadow: 0 8px 16px -4px rgba(196, 30, 58, 0.4);
}

.stat-card {
    background: white;
    border-left: 4px solid;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateX(4px);
    box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
}

.modal-backdrop {
    backdrop-filter: blur(8px);
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tab-button {
    position: relative;
    transition: all 0.3s ease;
}

.tab-button::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #c41e3a 0%, #d63856 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.tab-button.active::after {
    transform: scaleX(1);
}

.preview-image {
    max-width: 120px;
    max-height: 120px;
    object-fit: contain;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px;
    background: white;
}

.color-preset {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.color-preset:hover {
    transform: scale(1.1);
    border-color: #1a365d;
}

.search-input {
    transition: all 0.3s ease;
}

.search-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px -4px rgba(26, 54, 93, 0.15);
}

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.badge-party {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .party-card:hover {
        transform: none;
    }
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/30">
    
    <!-- Hero Section -->
    <div class="hero-gradient relative">
        <div class="container mx-auto px-4 py-12 md:py-16 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <a href="stemwijzer-dashboard.php" class="text-white/70 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div class="px-3 py-1 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                            <span class="text-xs font-semibold text-white uppercase tracking-wider">Admin Beheer</span>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                        Politieke <span class="gradient-text">Partijen</span>
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 max-w-2xl leading-relaxed">
                        Beheer alle politieke partijen, hun standpunten, peilingen en informatie op één centrale plek.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                    <button onclick="openAddModal()" 
                            class="btn-secondary text-white px-6 py-4 rounded-xl font-semibold flex items-center justify-center space-x-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Nieuwe Partij</span>
                    </button>
                    <button onclick="exportParties()" 
                            class="bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        <span>Exporteer Data</span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12">
                <div class="stat-card rounded-xl p-6 shadow-lg border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Totaal Partijen</p>
                            <p class="text-4xl font-black text-primary"><?= $stats['total_parties'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-xl p-6 shadow-lg border-secondary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Totaal Zetels</p>
                            <p class="text-4xl font-black text-secondary"><?= $stats['total_seats'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 bg-secondary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-xl p-6 shadow-lg border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Grootste Partij</p>
                            <p class="text-4xl font-black text-green-600"><?= $stats['max_seats'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-xl p-6 shadow-lg border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Gemiddeld</p>
                            <p class="text-4xl font-black text-amber-600"><?= round($stats['avg_seats'] ?? 0, 1) ?></p>
                        </div>
                        <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Display -->
    <?php if ($message): ?>
    <div class="container mx-auto px-4 py-4 relative z-20">
        <div class="<?= $messageType === 'success' ? 'bg-green-50 border-l-4 border-green-500 text-green-800' : 'bg-red-50 border-l-4 border-red-500 text-red-800' ?> px-6 py-4 rounded-xl shadow-md">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <?php if ($messageType === 'success'): ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    <?php else: ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    <?php endif; ?>
                </svg>
                <span class="font-semibold"><?= htmlspecialchars($message) ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Search and Filter Section -->
        <div class="card-primary rounded-xl p-6 mb-8 shadow-md">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Zoek op partijnaam of afkorting..." 
                               class="search-input w-full px-5 py-3 pl-12 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select id="sortSelect" class="px-5 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                        <option value="seats-desc">Zetels (hoog-laag)</option>
                        <option value="seats-asc">Zetels (laag-hoog)</option>
                        <option value="name-asc">Naam (A-Z)</option>
                        <option value="name-desc">Naam (Z-A)</option>
                        <option value="polling-desc">Peiling (hoog-laag)</option>
                    </select>
                    <button onclick="resetFilters()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Parties Grid -->
        <div id="partiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($parties as $key => $party): ?>
            <div class="party-card rounded-2xl shadow-lg border border-gray-100 overflow-hidden party-item" 
                 style="--party-color: <?= $party['color'] ?>;"
                 data-party-name="<?= strtolower($party['name']) ?>" 
                 data-party-key="<?= strtolower($key) ?>"
                 data-seats="<?= $party['current_seats'] ?>"
                 data-polling="<?= $party['polling']['seats'] ?>">
                
                <!-- Party Header -->
                <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-20 h-20 rounded-xl bg-white shadow-md p-3 flex-shrink-0">
                                <img src="<?= htmlspecialchars($party['logo']) ?>" 
                                     alt="<?= htmlspecialchars($party['name']) ?>" 
                                     class="w-full h-full object-contain"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%22 y=%2255%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22%23fff%22%3E?%3C/text%3E%3C/svg%3E'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-black text-gray-900 mb-1 truncate"><?= htmlspecialchars($party['name']) ?></h3>
                                <p class="text-sm text-gray-600 font-medium truncate"><?= htmlspecialchars($party['leader']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 flex-wrap gap-2">
                        <span class="badge-party" style="background-color: <?= $party['color'] ?>20; color: <?= $party['color'] ?>;">
                            <?= $key ?>
                        </span>
                        <span class="badge-party bg-gray-100 text-gray-700">
                            <?= $party['current_seats'] ?> zetels
                        </span>
                        <span class="badge-party <?= $party['polling']['change'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <?= $party['polling']['change'] >= 0 ? '+' : '' ?><?= $party['polling']['change'] ?>
                        </span>
                    </div>
                </div>

                <!-- Party Content -->
                <div class="p-6">
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                        <?= htmlspecialchars(substr($party['description'], 0, 120)) ?>...
                    </p>
                    
                    <!-- Polling Data -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 mb-4">
                        <h4 class="font-bold text-gray-900 text-xs uppercase tracking-wide mb-3">Huidige Peiling</h4>
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <p class="text-2xl font-black text-primary"><?= $party['polling']['seats'] ?></p>
                                <p class="text-xs text-gray-600 font-medium mt-1">Zetels</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-primary"><?= $party['polling']['percentage'] ?>%</p>
                                <p class="text-xs text-gray-600 font-medium mt-1">Percentage</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black <?= $party['polling']['change'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $party['polling']['change'] >= 0 ? '+' : '' ?><?= $party['polling']['change'] ?>
                                </p>
                                <p class="text-xs text-gray-600 font-medium mt-1">Trend</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="?edit=<?= $key ?>" class="flex-1 btn-primary text-white text-center py-3 rounded-xl font-semibold text-sm">
                            Bewerken
                        </a>
                        <button onclick="confirmDelete('<?= $key ?>', '<?= htmlspecialchars($party['name']) ?>')" 
                                class="px-4 py-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Geen partijen gevonden</h3>
            <p class="text-gray-600">Probeer je zoekopdracht aan te passen of reset de filters</p>
        </div>
    </div>
</main>

<!-- Add Party Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/60 modal-backdrop z-50 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-primary to-primary-light">
            <h2 class="text-2xl font-black text-white">Nieuwe Partij Toevoegen</h2>
            <button onclick="closeAddModal()" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 bg-gray-50 px-6">
            <button onclick="switchTab('basic')" class="tab-button active px-6 py-4 font-semibold text-primary" data-tab="basic">
                Basis Info
            </button>
            <button onclick="switchTab('standpoints')" class="tab-button px-6 py-4 font-semibold text-gray-600 hover:text-primary" data-tab="standpoints">
                Standpunten
            </button>
            <button onclick="switchTab('polling')" class="tab-button px-6 py-4 font-semibold text-gray-600 hover:text-primary" data-tab="polling">
                Peilingen
            </button>
        </div>
        
        <form method="POST" class="custom-scrollbar overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <input type="hidden" name="action" value="add_party">
            
            <div class="p-6">
                <!-- Tab Content: Basic Info -->
                <div id="tab-basic" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Code *</label>
                            <input type="text" name="party_key" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                   placeholder="bijv. VVD">
                            <p class="text-xs text-gray-500 mt-1">Unieke code in hoofdletters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Naam *</label>
                            <input type="text" name="name" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                   placeholder="Volledige partijnaam">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partijleider *</label>
                            <input type="text" name="leader" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                   placeholder="Naam van de partijleider">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Huidige Zetels *</label>
                            <input type="number" name="current_seats" required min="0" value="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Logo URL *</label>
                            <input type="url" name="logo" id="logo_url" required
                                   oninput="previewImage('logo_url', 'logo_preview')"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                   placeholder="https://example.com/logo.png">
                            <div id="logo_preview" class="mt-3 hidden">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Preview:</p>
                                <img class="preview-image" alt="Logo preview">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Leider Foto URL *</label>
                            <input type="url" name="leader_photo" id="leader_photo_url" required
                                   oninput="previewImage('leader_photo_url', 'leader_photo_preview')"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                   placeholder="/partijleiders/naam.jpg">
                            <div id="leader_photo_preview" class="mt-3 hidden">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Preview:</p>
                                <img class="preview-image" alt="Leader photo preview">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Kleur *</label>
                            <div class="flex items-center gap-4">
                                <input type="color" name="color" id="color_picker" required value="#1a365d"
                                       class="h-12 w-20 border-2 border-gray-200 rounded-xl cursor-pointer">
                                <div class="flex gap-2">
                                    <button type="button" onclick="setColor('#1a365d')" class="color-preset" style="background: #1a365d;" title="Primary"></button>
                                    <button type="button" onclick="setColor('#c41e3a')" class="color-preset" style="background: #c41e3a;" title="Secondary"></button>
                                    <button type="button" onclick="setColor('#10b981')" class="color-preset" style="background: #10b981;" title="Groen"></button>
                                    <button type="button" onclick="setColor('#3b82f6')" class="color-preset" style="background: #3b82f6;" title="Blauw"></button>
                                    <button type="button" onclick="setColor('#f59e0b')" class="color-preset" style="background: #f59e0b;" title="Oranje"></button>
                                    <button type="button" onclick="setColor('#8b5cf6')" class="color-preset" style="background: #8b5cf6;" title="Paars"></button>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Beschrijving *</label>
                            <textarea name="description" required rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Uitgebreide beschrijving van de partij en haar ideologie"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Leider Informatie *</label>
                            <textarea name="leader_info" required rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Achtergrond en informatie over de partijleider"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Standpunten -->
                <div id="tab-standpoints" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Immigratie</label>
                            <textarea name="standpoint_immigratie" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over immigratie en asielbeleid"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Klimaat</label>
                            <textarea name="standpoint_klimaat" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over klimaat en duurzaamheid"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Zorg</label>
                            <textarea name="standpoint_zorg" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over gezondheidszorg"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Economie</label>
                            <textarea name="standpoint_economie" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over economie en belastingen"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Onderwijs</label>
                            <textarea name="standpoint_onderwijs" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over onderwijs"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Veiligheid</label>
                            <textarea name="standpoint_veiligheid" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Standpunt over veiligheid en justitie"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Links Perspectief</label>
                            <textarea name="perspective_left" rows="2" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Hoe linkse kiezers deze partij kunnen zien"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Rechts Perspectief</label>
                            <textarea name="perspective_right" rows="2" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" 
                                      placeholder="Hoe rechtse kiezers deze partij kunnen zien"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Polling -->
                <div id="tab-polling" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Peiling Zetels</label>
                            <input type="number" name="polling_seats" min="0" value="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <p class="text-xs text-gray-500 mt-1">Verwachte zetels volgens peilingen</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Peiling Percentage</label>
                            <input type="number" name="polling_percentage" step="0.1" min="0" max="100" value="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <p class="text-xs text-gray-500 mt-1">Percentage in peilingen</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Trend/Verandering</label>
                            <input type="number" name="polling_change" value="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <p class="text-xs text-gray-500 mt-1">Positief of negatief (bijv. +3 of -2)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                <button type="button" onclick="closeAddModal()" 
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition-colors font-semibold">
                    Annuleren
                </button>
                <button type="submit" class="btn-secondary px-8 py-3 text-white rounded-xl font-semibold shadow-lg">
                    Partij Toevoegen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Party Modal -->
<?php if ($editParty): ?>
<div id="editModal" class="fixed inset-0 bg-black/60 modal-backdrop z-50 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-primary to-primary-light">
            <h2 class="text-2xl font-black text-white">Partij Bewerken: <?= htmlspecialchars($editParty['name']) ?></h2>
            <a href="?" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 bg-gray-50 px-6">
            <button onclick="switchTabEdit('basic')" class="tab-button-edit active px-6 py-4 font-semibold text-primary" data-tab="basic">
                Basis Info
            </button>
            <button onclick="switchTabEdit('standpoints')" class="tab-button-edit px-6 py-4 font-semibold text-gray-600 hover:text-primary" data-tab="standpoints">
                Standpunten
            </button>
            <button onclick="switchTabEdit('polling')" class="tab-button-edit px-6 py-4 font-semibold text-gray-600 hover:text-primary" data-tab="polling">
                Peilingen
            </button>
        </div>
        
        <form method="POST" class="custom-scrollbar overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <input type="hidden" name="action" value="update_party">
            <input type="hidden" name="party_key" value="<?= htmlspecialchars($editPartyKey) ?>">
            
            <div class="p-6">
                <!-- Tab Content: Basic Info -->
                <div id="tab-edit-basic" class="tab-content-edit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Code</label>
                            <input type="text" value="<?= htmlspecialchars($editPartyKey) ?>" disabled 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 text-gray-500">
                            <p class="text-xs text-gray-500 mt-1">Partij code kan niet worden gewijzigd</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Naam *</label>
                            <input type="text" name="name" required value="<?= htmlspecialchars($editParty['name']) ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partijleider *</label>
                            <input type="text" name="leader" required value="<?= htmlspecialchars($editParty['leader']) ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Huidige Zetels *</label>
                            <input type="number" name="current_seats" required min="0" value="<?= $editParty['current_seats'] ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Logo URL *</label>
                            <input type="url" name="logo" id="edit_logo_url" required value="<?= htmlspecialchars($editParty['logo']) ?>"
                                   oninput="previewImage('edit_logo_url', 'edit_logo_preview')"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <div id="edit_logo_preview" class="mt-3">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Preview:</p>
                                <img src="<?= htmlspecialchars($editParty['logo']) ?>" class="preview-image" alt="Logo preview">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Leider Foto URL *</label>
                            <input type="url" name="leader_photo" id="edit_leader_photo_url" required value="<?= htmlspecialchars($editParty['leader_photo']) ?>"
                                   oninput="previewImage('edit_leader_photo_url', 'edit_leader_photo_preview')"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                            <div id="edit_leader_photo_preview" class="mt-3">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Preview:</p>
                                <img src="<?= htmlspecialchars($editParty['leader_photo']) ?>" class="preview-image" alt="Leader photo preview">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Kleur *</label>
                            <div class="flex items-center gap-4">
                                <input type="color" name="color" id="edit_color_picker" required value="<?= htmlspecialchars($editParty['color']) ?>"
                                       class="h-12 w-20 border-2 border-gray-200 rounded-xl cursor-pointer">
                                <div class="flex gap-2">
                                    <button type="button" onclick="setColorEdit('#1a365d')" class="color-preset" style="background: #1a365d;"></button>
                                    <button type="button" onclick="setColorEdit('#c41e3a')" class="color-preset" style="background: #c41e3a;"></button>
                                    <button type="button" onclick="setColorEdit('#10b981')" class="color-preset" style="background: #10b981;"></button>
                                    <button type="button" onclick="setColorEdit('#3b82f6')" class="color-preset" style="background: #3b82f6;"></button>
                                    <button type="button" onclick="setColorEdit('#f59e0b')" class="color-preset" style="background: #f59e0b;"></button>
                                    <button type="button" onclick="setColorEdit('#8b5cf6')" class="color-preset" style="background: #8b5cf6;"></button>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Partij Beschrijving *</label>
                            <textarea name="description" required rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['description']) ?></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Leider Informatie *</label>
                            <textarea name="leader_info" required rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['leader_info']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Standpunten -->
                <div id="tab-edit-standpoints" class="tab-content-edit hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Immigratie</label>
                            <textarea name="standpoint_immigratie" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Immigratie'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Klimaat</label>
                            <textarea name="standpoint_klimaat" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Klimaat'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Zorg</label>
                            <textarea name="standpoint_zorg" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Zorg'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Economie</label>
                            <textarea name="standpoint_economie" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Economie'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Onderwijs</label>
                            <textarea name="standpoint_onderwijs" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Onderwijs'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Veiligheid</label>
                            <textarea name="standpoint_veiligheid" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['standpoints']['Veiligheid'] ?? '') ?></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Links Perspectief</label>
                            <textarea name="perspective_left" rows="2" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['perspectives']['left'] ?? '') ?></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Rechts Perspectief</label>
                            <textarea name="perspective_right" rows="2" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10"><?= htmlspecialchars($editParty['perspectives']['right'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Polling -->
                <div id="tab-edit-polling" class="tab-content-edit hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Peiling Zetels</label>
                            <input type="number" name="polling_seats" min="0" value="<?= $editParty['polling']['seats'] ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Peiling Percentage</label>
                            <input type="number" name="polling_percentage" step="0.1" min="0" max="100" value="<?= $editParty['polling']['percentage'] ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Trend/Verandering</label>
                            <input type="number" name="polling_change" value="<?= $editParty['polling']['change'] ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                <a href="?" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition-colors font-semibold inline-block">
                    Annuleren
                </a>
                <button type="submit" class="btn-primary px-8 py-3 text-white rounded-xl font-semibold shadow-lg">
                    Wijzigingen Opslaan
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
// Modal Management
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Tab Management for Add Modal
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'text-primary');
        tab.classList.add('text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.querySelector('.tab-button[data-tab="' + tabName + '"]');
    activeTab.classList.add('active', 'text-primary');
    activeTab.classList.remove('text-gray-600');
}

// Tab Management for Edit Modal
function switchTabEdit(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content-edit').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button-edit').forEach(tab => {
        tab.classList.remove('active', 'text-primary');
        tab.classList.add('text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('tab-edit-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.querySelector('.tab-button-edit[data-tab="' + tabName + '"]');
    activeTab.classList.add('active', 'text-primary');
    activeTab.classList.remove('text-gray-600');
}

// Image Preview Function
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const img = preview.querySelector('img');
    
    if (input.value) {
        preview.classList.remove('hidden');
        img.src = input.value;
        img.onerror = function() {
            this.src = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%22 y=%2255%22 font-size=%2220%22 text-anchor=%22middle%22 fill=%22%23666%22%3EInvalid URL%3C/text%3E%3C/svg%3E';
        };
    } else {
        preview.classList.add('hidden');
    }
}

// Color Picker Functions
function setColor(color) {
    document.getElementById('color_picker').value = color;
}

function setColorEdit(color) {
    document.getElementById('edit_color_picker').value = color;
}

// Search and Filter
function filterParties() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const sortValue = document.getElementById('sortSelect').value;
    const parties = Array.from(document.querySelectorAll('.party-item'));
    let visibleCount = 0;

    // Filter
    parties.forEach(party => {
        const name = party.dataset.partyName;
        const key = party.dataset.partyKey;
        
        if (name.includes(searchTerm) || key.includes(searchTerm)) {
            party.style.display = 'block';
            visibleCount++;
        } else {
            party.style.display = 'none';
        }
    });

    // Sort visible parties
    const visibleParties = parties.filter(p => p.style.display !== 'none');
    visibleParties.sort((a, b) => {
        switch(sortValue) {
            case 'seats-desc':
                return parseInt(b.dataset.seats) - parseInt(a.dataset.seats);
            case 'seats-asc':
                return parseInt(a.dataset.seats) - parseInt(b.dataset.seats);
            case 'name-asc':
                return a.dataset.partyName.localeCompare(b.dataset.partyName);
            case 'name-desc':
                return b.dataset.partyName.localeCompare(a.dataset.partyName);
            case 'polling-desc':
                return parseInt(b.dataset.polling) - parseInt(a.dataset.polling);
            default:
                return 0;
        }
    });

    // Reorder in DOM
    const grid = document.getElementById('partiesGrid');
    visibleParties.forEach(party => grid.appendChild(party));

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
    document.getElementById('sortSelect').value = 'seats-desc';
    filterParties();
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterParties);
document.getElementById('sortSelect').addEventListener('change', filterParties);

// Delete confirmation
function confirmDelete(partyKey, partyName) {
    if (confirm(`Weet je zeker dat je "${partyName}" wilt deactiveren?\n\nDeze actie kan later ongedaan worden gemaakt door de partij opnieuw te activeren in de database.`)) {
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

// Export parties
function exportParties() {
    const parties = Array.from(document.querySelectorAll('.party-item:not([style*="display: none"])')).map(party => ({
        key: party.dataset.partyKey.toUpperCase(),
        name: party.querySelector('h3').textContent.trim(),
        seats: party.dataset.seats
    }));
    
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(parties, null, 2));
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "partijen_export_" + new Date().toISOString().split('T')[0] + ".json");
    document.body.appendChild(downloadAnchorNode);
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

// Auto-close success/error messages
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.3s ease';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });

    // Initialize image previews for edit modal
    <?php if ($editParty): ?>
    previewImage('edit_logo_url', 'edit_logo_preview');
    previewImage('edit_leader_photo_url', 'edit_leader_photo_preview');
    <?php endif; ?>
});

// Prevent closing modal when clicking inside
document.getElementById('addModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});

<?php if ($editParty): ?>
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        window.location.href = '?';
    }
});
<?php endif; ?>
</script>

<?php require_once '../views/templates/footer.php'; ?>
