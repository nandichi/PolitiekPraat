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
    
    if ($action === 'delete_party') {
        $partyId = $_POST['party_id'] ?? 0;
        
        try {
            // Verwijder eerst alle standpunten voor deze partij
            $db->query("DELETE FROM stemwijzer_positions WHERE party_id = :party_id");
            $db->bind(':party_id', $partyId);
            $db->execute();
            
            // Verwijder de partij zelf
            $db->query("DELETE FROM stemwijzer_parties WHERE id = :id");
            $db->bind(':id', $partyId);
            $db->execute();
            
            $message = 'Partij succesvol verwijderd';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij verwijderen partij: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Haal alle partijen op
try {
    $db->query("SELECT p.id, p.name, p.short_name, p.logo_url, p.created_at, p.updated_at,
                       COUNT(pos.id) as total_positions,
                       COUNT(CASE WHEN pos.position = 'eens' THEN 1 END) as eens_count,
                       COUNT(CASE WHEN pos.position = 'neutraal' THEN 1 END) as neutraal_count,
                       COUNT(CASE WHEN pos.position = 'oneens' THEN 1 END) as oneens_count
                FROM stemwijzer_parties p 
                LEFT JOIN stemwijzer_positions pos ON p.id = pos.party_id 
                GROUP BY p.id 
                ORDER BY p.name ASC");
    $parties = $db->resultSet();
} catch (Exception $e) {
    $parties = [];
    $message = 'Fout bij ophalen partijen: ' . $e->getMessage();
    $messageType = 'error';
}

// Haal totaal aantal vragen op
try {
    $db->query("SELECT COUNT(*) as count FROM stemwijzer_questions WHERE is_active = 1");
    $totalActiveQuestions = $db->single()->count;
} catch (Exception $e) {
    $totalActiveQuestions = 0;
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

.party-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.party-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.logo-placeholder {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.completion-bar {
    transition: width 0.6s ease-in-out;
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-blue-100 text-sm mb-2">
                        <a href="stemwijzer-dashboard.php" class="hover:text-white">Stemwijzer Beheer</a> 
                        <span class="mx-2">›</span> 
                        <span>Partijen Beheer</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Partijen Beheer</h1>
                    <p class="text-blue-100 text-lg">Beheer alle politieke partijen en hun informatie</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-partij-toevoegen.php" 
                       class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Nieuwe Partij</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-xl <?= $messageType === 'success' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-3xl font-bold text-indigo-600"><?= count($parties) ?></p>
                    <p class="text-gray-600">Totaal Partijen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600"><?= $totalActiveQuestions ?></p>
                    <p class="text-gray-600">Actieve Vragen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600">
                        <?php 
                        $completeParties = 0;
                        foreach ($parties as $party) {
                            if ($party->total_positions >= $totalActiveQuestions) $completeParties++;
                        }
                        echo $completeParties;
                        ?>
                    </p>
                    <p class="text-gray-600">Volledig Ingevuld</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-orange-600">
                        <?= count($parties) - $completeParties ?>
                    </p>
                    <p class="text-gray-600">Nog In Te Vullen</p>
                </div>
            </div>
        </div>

        <!-- Parties Grid -->
        <?php if (empty($parties)): ?>
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nog geen partijen</h3>
                <p class="text-gray-500 mb-6">Begin met het toevoegen van je eerste politieke partij</p>
                <a href="stemwijzer-partij-toevoegen.php" 
                   class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Eerste Partij Toevoegen</span>
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($parties as $party): 
                    $completionPercentage = $totalActiveQuestions > 0 ? round(($party->total_positions / $totalActiveQuestions) * 100) : 0;
                    $isComplete = $party->total_positions >= $totalActiveQuestions;
                ?>
                    <div class="party-card bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                        <!-- Party Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 border-b border-gray-100">
                            <div class="flex items-start space-x-4">
                                <!-- Party Logo -->
                                <div class="w-16 h-16 rounded-xl border-2 border-white shadow-sm overflow-hidden flex-shrink-0">
                                    <?php if ($party->logo_url): ?>
                                        <img src="<?= htmlspecialchars($party->logo_url) ?>" 
                                             alt="<?= htmlspecialchars($party->name) ?>" 
                                             class="w-full h-full object-contain bg-white">
                                    <?php else: ?>
                                        <div class="logo-placeholder w-full h-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Party Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1 truncate"><?= htmlspecialchars($party->name) ?></h3>
                                    <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($party->short_name) ?></p>
                                    
                                    <!-- Completion Status -->
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="completion-bar h-2 rounded-full <?= $isComplete ? 'bg-green-500' : 'bg-blue-500' ?>" 
                                                 style="width: <?= $completionPercentage ?>%"></div>
                                        </div>
                                        <span class="text-xs font-medium <?= $isComplete ? 'text-green-600' : 'text-blue-600' ?>">
                                            <?= $completionPercentage ?>%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Party Stats -->
                        <div class="p-6">
                            <!-- Position Stats -->
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800"><?= $party->eens_count ?></p>
                                    <p class="text-xs text-gray-600">Eens</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800"><?= $party->neutraal_count ?></p>
                                    <p class="text-xs text-gray-600">Neutraal</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800"><?= $party->oneens_count ?></p>
                                    <p class="text-xs text-gray-600">Oneens</p>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="flex items-center justify-center mb-4">
                                <?php if ($isComplete): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        ✓ Volledig ingevuld
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                                        ⚠ <?= $totalActiveQuestions - $party->total_positions ?> vragen ontbreken
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Description (if exists) -->
                            <?php if (isset($party->description) && $party->description): ?>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?= htmlspecialchars($party->description) ?></p>
                            <?php endif; ?>
                            
                            <!-- Metadata -->
                            <div class="text-xs text-gray-500 mb-4 space-y-1">
                                <div>Aangemaakt: <?= formatDate($party->created_at) ?></div>
                                <div>ID: <?= $party->id ?></div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <div class="flex flex-col space-y-2">
                                <a href="stemwijzer-partij-bewerken.php?id=<?= $party->id ?>" 
                                   class="w-full px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm font-medium text-center">
                                    Partij Bewerken
                                </a>
                                
                                <a href="stemwijzer-standpunten-beheer.php?party_id=<?= $party->id ?>" 
                                   class="w-full px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm font-medium text-center">
                                    Standpunten (<?= $party->total_positions ?>/<?= $totalActiveQuestions ?>)
                                </a>
                                
                                <form method="POST" class="w-full" 
                                      onsubmit="return confirm('Ben je zeker dat je deze partij wilt verwijderen? Alle bijbehorende standpunten worden ook verwijderd.')">
                                    <input type="hidden" name="action" value="delete_party">
                                    <input type="hidden" name="party_id" value="<?= $party->id ?>">
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        Verwijderen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate completion bars
    const bars = document.querySelectorAll('.completion-bar');
    bars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 200);
    });
    
    // Add loading state to delete forms
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = form.querySelector('button[type="submit"]');
            button.innerHTML = 'Verwijderen...';
            button.disabled = true;
        });
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 