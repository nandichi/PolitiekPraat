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

// Specifieke vraag of partij filter
$questionId = $_GET['question_id'] ?? null;
$partyId = $_GET['party_id'] ?? null;

// Handle form submission voor standpunten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_position') {
        $positionQuestionId = $_POST['question_id'] ?? 0;
        $positionPartyId = $_POST['party_id'] ?? 0;
        $position = $_POST['position'] ?? '';
        $explanation = trim($_POST['explanation'] ?? '');
        
        try {
            // Check if position already exists
            $db->query("SELECT id FROM stemwijzer_positions WHERE question_id = :question_id AND party_id = :party_id");
            $db->bind(':question_id', $positionQuestionId);
            $db->bind(':party_id', $positionPartyId);
            $existing = $db->single();
            
            if ($existing) {
                // Update existing
                $db->query("UPDATE stemwijzer_positions SET position = :position, explanation = :explanation, updated_at = NOW() WHERE id = :id");
                $db->bind(':position', $position);
                $db->bind(':explanation', $explanation);
                $db->bind(':id', $existing->id);
            } else {
                // Insert new
                $db->query("INSERT INTO stemwijzer_positions (question_id, party_id, position, explanation, created_at, updated_at) VALUES (:question_id, :party_id, :position, :explanation, NOW(), NOW())");
                $db->bind(':question_id', $positionQuestionId);
                $db->bind(':party_id', $positionPartyId);
                $db->bind(':position', $position);
                $db->bind(':explanation', $explanation);
            }
            
            $db->execute();
            $message = 'Standpunt succesvol opgeslagen';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij opslaan standpunt: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Haal vragen op
try {
    $questionFilter = $questionId ? "WHERE sq.id = :question_id" : "WHERE sq.is_active = 1";
    $db->query("SELECT sq.id, sq.title, sq.description, sq.order_number FROM stemwijzer_questions sq $questionFilter ORDER BY sq.order_number ASC");
    if ($questionId) {
        $db->bind(':question_id', $questionId);
    }
    $questions = $db->resultSet();
} catch (Exception $e) {
    $questions = [];
}

// Haal partijen op
try {
    $partyFilter = $partyId ? "WHERE sp.id = :party_id" : "";
    $db->query("SELECT sp.id, sp.name, sp.short_name, sp.logo_url FROM stemwijzer_parties sp $partyFilter ORDER BY sp.name ASC");
    if ($partyId) {
        $db->bind(':party_id', $partyId);
    }
    $parties = $db->resultSet();
} catch (Exception $e) {
    $parties = [];
}

// Haal alle bestaande standpunten op
$positions = [];
try {
    $db->query("SELECT question_id, party_id, position, explanation FROM stemwijzer_positions");
    $positionResults = $db->resultSet();
    
    foreach ($positionResults as $pos) {
        $positions[$pos->question_id][$pos->party_id] = [
            'position' => $pos->position,
            'explanation' => $pos->explanation
        ];
    }
} catch (Exception $e) {
    // Error handling
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

.position-cell {
    transition: all 0.2s ease;
    cursor: pointer;
}

.position-cell:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.position-eens {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-color: #10b981;
}

.position-neutraal {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-color: #3b82f6;
}

.position-oneens {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-color: #ef4444;
}

.position-empty {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-color: #d1d5db;
    border-style: dashed;
}

.sticky-header {
    position: sticky;
    top: 0;
    z-index: 10;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.modal-overlay {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
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
                        <span>Standpunten Beheer</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Standpunten Beheer</h1>
                    <p class="text-blue-100 text-lg">
                        <?php if ($questionId): ?>
                            Beheer standpunten voor specifieke vraag
                        <?php elseif ($partyId): ?>
                            Beheer standpunten voor specifieke partij
                        <?php else: ?>
                            Beheer alle partij standpunten per vraag
                        <?php endif; ?>
                    </p>
                </div>
                <div class="flex space-x-4">
                    <?php if ($questionId || $partyId): ?>
                        <a href="stemwijzer-standpunten-beheer.php" 
                           class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                            Alle Standpunten
                        </a>
                    <?php endif; ?>
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold">
                        Vragen Beheer
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
                    <p class="text-3xl font-bold text-indigo-600"><?= count($questions) ?></p>
                    <p class="text-gray-600">Vragen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600"><?= count($parties) ?></p>
                    <p class="text-gray-600">Partijen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600"><?= count($questions) * count($parties) ?></p>
                    <p class="text-gray-600">Totaal Posities</p>
                </div>
                <div class="text-center">
                    <?php 
                    $filledPositions = 0;
                    foreach ($questions as $q) {
                        foreach ($parties as $p) {
                            if (isset($positions[$q->id][$p->id])) {
                                $filledPositions++;
                            }
                        }
                    }
                    ?>
                    <p class="text-3xl font-bold text-orange-600"><?= $filledPositions ?></p>
                    <p class="text-gray-600">Ingevuld</p>
                </div>
            </div>
        </div>

        <!-- Standpunten Matrix -->
        <?php if (empty($questions) || empty($parties)): ?>
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nog geen data beschikbaar</h3>
                <p class="text-gray-500 mb-6">Voeg eerst vragen en partijen toe voordat je standpunten kunt beheren</p>
                <div class="flex justify-center space-x-4">
                    <a href="stemwijzer-vraag-toevoegen.php" 
                       class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition-colors">
                        <span>Vragen Toevoegen</span>
                    </a>
                    <a href="stemwijzer-partij-toevoegen.php" 
                       class="inline-flex items-center space-x-2 bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors">
                        <span>Partijen Toevoegen</span>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <!-- Matrix Header -->
                <div class="sticky-header bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Standpunten Matrix</h2>
                    <p class="text-gray-600 text-sm">Klik op een cel om een standpunt te bewerken</p>
                </div>
                
                <!-- Matrix Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <!-- Table Header -->
                        <thead class="sticky-header border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 min-w-[300px]">Vraag</th>
                                <?php foreach ($parties as $party): ?>
                                    <th class="px-2 py-3 text-center text-sm font-semibold text-gray-700 min-w-[120px]">
                                        <div class="flex flex-col items-center space-y-2">
                                            <?php if ($party->logo_url): ?>
                                                <img src="<?= htmlspecialchars($party->logo_url) ?>" 
                                                     alt="<?= htmlspecialchars($party->name) ?>" 
                                                     class="w-8 h-8 object-contain rounded">
                                            <?php endif; ?>
                                            <span class="text-xs"><?= htmlspecialchars($party->short_name) ?></span>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        
                        <!-- Table Body -->
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($questions as $question): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Question Cell -->
                                    <td class="px-4 py-4 border-r border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-indigo-600 font-bold text-sm"><?= $question->order_number ?></span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-semibold text-gray-800 text-sm leading-tight"><?= htmlspecialchars($question->title) ?></h4>
                                                <p class="text-gray-600 text-xs mt-1 line-clamp-2"><?= htmlspecialchars($question->description) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Position Cells -->
                                    <?php foreach ($parties as $party): ?>
                                        <?php 
                                        $hasPosition = isset($positions[$question->id][$party->id]);
                                        $position = $hasPosition ? $positions[$question->id][$party->id]['position'] : '';
                                        $explanation = $hasPosition ? $positions[$question->id][$party->id]['explanation'] : '';
                                        ?>
                                        <td class="px-2 py-4">
                                            <div class="position-cell p-3 rounded-lg border-2 text-center
                                                     <?= $position === 'eens' ? 'position-eens' : 
                                                         ($position === 'neutraal' ? 'position-neutraal' : 
                                                         ($position === 'oneens' ? 'position-oneens' : 'position-empty')) ?>"
                                                 onclick="openPositionModal(<?= $question->id ?>, <?= $party->id ?>, '<?= htmlspecialchars($position) ?>', '<?= htmlspecialchars($explanation) ?>', '<?= htmlspecialchars($question->title) ?>', '<?= htmlspecialchars($party->name) ?>')">
                                                
                                                <?php if ($position): ?>
                                                    <div class="font-medium text-sm text-gray-800 capitalize"><?= $position ?></div>
                                                    <?php if ($explanation): ?>
                                                        <div class="text-xs text-gray-600 mt-1 truncate" title="<?= htmlspecialchars($explanation) ?>">
                                                            <?= htmlspecialchars(substr($explanation, 0, 50)) ?><?= strlen($explanation) > 50 ? '...' : '' ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="text-gray-400 text-sm">Klik om in te vullen</div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Legend -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-6 text-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 rounded position-eens border-2"></div>
                            <span>Eens</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 rounded position-neutraal border-2"></div>
                            <span>Neutraal</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 rounded position-oneens border-2"></div>
                            <span>Oneens</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 rounded position-empty border-2"></div>
                            <span>Niet ingevuld</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Position Modal -->
    <div id="positionModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0" onclick="closePositionModal()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Standpunt Bewerken</h3>
                        <button onclick="closePositionModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-600" id="modalQuestionTitle"></p>
                        <p class="text-sm text-gray-500" id="modalPartyName"></p>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <form method="POST" class="p-6">
                    <input type="hidden" name="action" value="save_position">
                    <input type="hidden" name="question_id" id="modalQuestionId">
                    <input type="hidden" name="party_id" id="modalPartyId">
                    
                    <!-- Position Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Standpunt</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="position" value="eens" class="sr-only" id="radioEens">
                                <div class="position-select p-4 border-2 border-gray-200 rounded-lg text-center transition-all hover:border-green-300" data-position="eens">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium">Eens</span>
                                </div>
                            </label>
                            
                            <label class="cursor-pointer">
                                <input type="radio" name="position" value="neutraal" class="sr-only" id="radioNeutraal">
                                <div class="position-select p-4 border-2 border-gray-200 rounded-lg text-center transition-all hover:border-blue-300" data-position="neutraal">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                    </svg>
                                    <span class="font-medium">Neutraal</span>
                                </div>
                            </label>
                            
                            <label class="cursor-pointer">
                                <input type="radio" name="position" value="oneens" class="sr-only" id="radioOneens">
                                <div class="position-select p-4 border-2 border-gray-200 rounded-lg text-center transition-all hover:border-red-300" data-position="oneens">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="font-medium">Oneens</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Explanation -->
                    <div class="mb-6">
                        <label for="modalExplanation" class="block text-sm font-medium text-gray-700 mb-2">Toelichting</label>
                        <textarea name="explanation" 
                                  id="modalExplanation"
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Leg uit waarom de partij dit standpunt inneemt..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-gray-500 text-sm">Optionele uitleg bij het standpunt</p>
                            <span class="text-xs text-gray-400" id="explanationCounter">0/1000</span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold">
                            Standpunt Opslaan
                        </button>
                        <button type="button" 
                                onclick="closePositionModal()"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                            Annuleren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function openPositionModal(questionId, partyId, position, explanation, questionTitle, partyName) {
    document.getElementById('modalQuestionId').value = questionId;
    document.getElementById('modalPartyId').value = partyId;
    document.getElementById('modalQuestionTitle').textContent = questionTitle;
    document.getElementById('modalPartyName').textContent = 'Partij: ' + partyName;
    document.getElementById('modalExplanation').value = explanation;
    
    // Set position
    const positionSelects = document.querySelectorAll('.position-select');
    positionSelects.forEach(select => {
        const radio = select.parentElement.querySelector('input[type="radio"]');
        if (radio.value === position) {
            radio.checked = true;
            selectPosition(select, position);
        } else {
            radio.checked = false;
            select.classList.remove('position-eens', 'position-neutraal', 'position-oneens');
            select.classList.add('border-gray-200');
        }
    });
    
    // Show modal
    document.getElementById('positionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    updateExplanationCounter();
}

function closePositionModal() {
    document.getElementById('positionModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function selectPosition(element, position) {
    // Remove all position classes
    element.classList.remove('position-eens', 'position-neutraal', 'position-oneens', 'border-gray-200');
    
    // Add appropriate class
    switch(position) {
        case 'eens':
            element.classList.add('position-eens');
            break;
        case 'neutraal':
            element.classList.add('position-neutraal');
            break;
        case 'oneens':
            element.classList.add('position-oneens');
            break;
        default:
            element.classList.add('border-gray-200');
    }
}

function updateExplanationCounter() {
    const textarea = document.getElementById('modalExplanation');
    const counter = document.getElementById('explanationCounter');
    const length = textarea.value.length;
    counter.textContent = `${length}/1000`;
    
    if (length > 900) {
        counter.classList.add('text-red-500');
    } else if (length > 700) {
        counter.classList.add('text-yellow-500');
        counter.classList.remove('text-red-500');
    } else {
        counter.classList.remove('text-red-500', 'text-yellow-500');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Position selection handlers
    const positionSelects = document.querySelectorAll('.position-select');
    positionSelects.forEach(select => {
        select.addEventListener('click', function() {
            const radio = this.parentElement.querySelector('input[type="radio"]');
            const position = this.dataset.position;
            
            // Uncheck other radios and reset styles
            positionSelects.forEach(other => {
                other.parentElement.querySelector('input[type="radio"]').checked = false;
                other.classList.remove('position-eens', 'position-neutraal', 'position-oneens');
                other.classList.add('border-gray-200');
            });
            
            // Check this radio and apply style
            radio.checked = true;
            selectPosition(this, position);
        });
    });
    
    // Explanation counter
    const explanationTextarea = document.getElementById('modalExplanation');
    if (explanationTextarea) {
        explanationTextarea.addEventListener('input', updateExplanationCounter);
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('positionModal').classList.contains('hidden')) return;
        
        if (e.key === 'Escape') {
            closePositionModal();
        } else if (e.key === '1') {
            document.getElementById('radioEens').checked = true;
            selectPosition(document.querySelector('[data-position="eens"]'), 'eens');
        } else if (e.key === '2') {
            document.getElementById('radioNeutraal').checked = true;
            selectPosition(document.querySelector('[data-position="neutraal"]'), 'neutraal');
        } else if (e.key === '3') {
            document.getElementById('radioOneens').checked = true;
            selectPosition(document.querySelector('[data-position="oneens"]'), 'oneens');
        }
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 