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

// Get question ID from URL
$questionId = $_GET['id'] ?? 0;

if (!$questionId) {
    redirect('stemwijzer-vraag-beheer.php');
}

// Haal vraag gegevens op
try {
    $db->query("SELECT * FROM stemwijzer_questions WHERE id = :id");
    $db->bind(':id', $questionId);
    $question = $db->single();
    
    if (!$question) {
        redirect('stemwijzer-vraag-beheer.php');
    }
} catch (Exception $e) {
    redirect('stemwijzer-vraag-beheer.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $context = trim($_POST['context'] ?? '');
    $leftView = trim($_POST['left_view'] ?? '');
    $rightView = trim($_POST['right_view'] ?? '');
    $orderNumber = intval($_POST['order_number'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Validatie
    $errors = [];
    if (empty($title)) $errors[] = 'Titel is verplicht';
    if (empty($description)) $errors[] = 'Beschrijving is verplicht';
    if (empty($context)) $errors[] = 'Context is verplicht';
    if (empty($leftView)) $errors[] = 'Links perspectief is verplicht';
    if (empty($rightView)) $errors[] = 'Rechts perspectief is verplicht';
    if ($orderNumber <= 0) $errors[] = 'Volgorde nummer moet groter zijn dan 0';
    
    if (empty($errors)) {
        try {
            // Controleer of volgorde nummer al bestaat (behalve voor deze vraag)
            $db->query("SELECT COUNT(*) as count FROM stemwijzer_questions WHERE order_number = :order_number AND id != :id");
            $db->bind(':order_number', $orderNumber);
            $db->bind(':id', $questionId);
            $existing = $db->single();
            
            if ($existing->count > 0) {
                $errors[] = 'Volgorde nummer ' . $orderNumber . ' is al in gebruik door een andere vraag';
            } else {
                // Vraag bijwerken
                $db->query("UPDATE stemwijzer_questions SET title = :title, description = :description, context = :context, left_view = :left_view, right_view = :right_view, order_number = :order_number, is_active = :is_active, updated_at = NOW() WHERE id = :id");
                
                $db->bind(':title', $title);
                $db->bind(':description', $description);
                $db->bind(':context', $context);
                $db->bind(':left_view', $leftView);
                $db->bind(':right_view', $rightView);
                $db->bind(':order_number', $orderNumber);
                $db->bind(':is_active', $isActive);
                $db->bind(':id', $questionId);
                
                $db->execute();
                
                // Update question object with new data
                $question->title = $title;
                $question->description = $description;
                $question->context = $context;
                $question->left_view = $leftView;
                $question->right_view = $rightView;
                $question->order_number = $orderNumber;
                $question->is_active = $isActive;
                
                $message = 'Vraag succesvol bijgewerkt';
                $messageType = 'success';
            }
        } catch (Exception $e) {
            $errors[] = 'Fout bij bijwerken vraag: ' . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
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

.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.floating-label {
    position: relative;
}

.floating-label input,
.floating-label textarea {
    transition: all 0.2s ease;
}

.floating-label label {
    position: absolute;
    left: 12px;
    top: 12px;
    color: #6b7280;
    transition: all 0.2s ease;
    pointer-events: none;
    background: white;
    padding: 0 4px;
}

.floating-label input:focus + label,
.floating-label textarea:focus + label,
.floating-label input:not(:placeholder-shown) + label,
.floating-label textarea:not(:placeholder-shown) + label {
    top: -8px;
    left: 8px;
    font-size: 0.75rem;
    color: #4f46e5;
}

.character-counter {
    transition: color 0.2s ease;
}

.status-badge {
    animation: pulse 2s infinite;
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
                        <a href="stemwijzer-vraag-beheer.php" class="hover:text-white">Vragen Beheer</a>
                        <span class="mx-2">›</span> 
                        <span>Vraag Bewerken</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Vraag Bewerken</h1>
                    <p class="text-blue-100 text-lg">Bewerk de gegevens van vraag #<?= $question->order_number ?></p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-standpunten-beheer.php?question_id=<?= $questionId ?>" 
                       class="bg-purple-500/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-white/30">
                        Standpunten Beheren
                    </a>
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        ← Terug naar Overzicht
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-xl <?= $messageType === 'success' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <!-- Question Info Card -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Vraag Informatie</h2>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <span>ID: <?= $question->id ?></span>
                        <span>Aangemaakt: <?= formatDate($question->created_at) ?></span>
                        <?php if ($question->updated_at && $question->updated_at !== $question->created_at): ?>
                            <span>Laatst bijgewerkt: <?= formatDate($question->updated_at) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <span class="status-badge px-4 py-2 rounded-full text-sm font-medium <?= $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $question->is_active ? 'Actief' : 'Inactief' ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Form Card -->
        <div class="form-card rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Vraag Bewerken</h2>
                <p class="text-gray-600 text-sm">Pas de gegevens van deze vraag aan</p>
            </div>
            
            <form method="POST" class="p-6 md:p-8 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Title -->
                    <div class="lg:col-span-2">
                        <div class="floating-label">
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   placeholder=" "
                                   maxlength="255"
                                   required
                                   value="<?= htmlspecialchars($question->title) ?>"
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <label for="title">Vraag Titel</label>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-gray-500 text-sm">De hoofdvraag/stelling</p>
                            <span class="character-counter text-xs text-gray-400" data-max="255" data-target="title">0/255</span>
                        </div>
                    </div>
                    
                    <!-- Order Number -->
                    <div>
                        <div class="floating-label">
                            <input type="number" 
                                   name="order_number" 
                                   id="order_number"
                                   placeholder=" "
                                   min="1"
                                   required
                                   value="<?= $question->order_number ?>"
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <label for="order_number">Volgorde Nummer</label>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Positie in de vragenlijst</p>
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <div class="floating-label">
                        <textarea name="description" 
                                  id="description"
                                  placeholder=" "
                                  rows="4"
                                  maxlength="1000"
                                  required
                                  class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"><?= htmlspecialchars($question->description) ?></textarea>
                        <label for="description">Beschrijving</label>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-gray-500 text-sm">Uitgebreide uitleg van de vraag/stelling</p>
                        <span class="character-counter text-xs text-gray-400" data-max="1000" data-target="description">0/1000</span>
                    </div>
                </div>
                
                <!-- Context -->
                <div>
                    <div class="floating-label">
                        <textarea name="context" 
                                  id="context"
                                  placeholder=" "
                                  rows="3"
                                  maxlength="1000"
                                  required
                                  class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"><?= htmlspecialchars($question->context) ?></textarea>
                        <label for="context">Context</label>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-gray-500 text-sm">Achtergrond informatie bij deze stelling</p>
                        <span class="character-counter text-xs text-gray-400" data-max="1000" data-target="context">0/1000</span>
                    </div>
                </div>
                
                <!-- Perspectives -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left View -->
                    <div>
                        <div class="floating-label">
                            <textarea name="left_view" 
                                      id="left_view"
                                      placeholder=" "
                                      rows="4"
                                      maxlength="500"
                                      required
                                      class="w-full px-3 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none bg-green-50/30"><?= htmlspecialchars($question->left_view) ?></textarea>
                            <label for="left_view" class="text-green-700">Links Perspectief</label>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-green-600 text-sm">Argument van linkse/progressieve partijen</p>
                            <span class="character-counter text-xs text-green-500" data-max="500" data-target="left_view">0/500</span>
                        </div>
                    </div>
                    
                    <!-- Right View -->
                    <div>
                        <div class="floating-label">
                            <textarea name="right_view" 
                                      id="right_view"
                                      placeholder=" "
                                      rows="4"
                                      maxlength="500"
                                      required
                                      class="w-full px-3 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none bg-red-50/30"><?= htmlspecialchars($question->right_view) ?></textarea>
                            <label for="right_view" class="text-red-700">Rechts Perspectief</label>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-red-600 text-sm">Argument van rechtse/conservatieve partijen</p>
                            <span class="character-counter text-xs text-red-500" data-max="500" data-target="right_view">0/500</span>
                        </div>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Instellingen</h3>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               <?= $question->is_active ? 'checked' : '' ?>
                               class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Vraag actief houden
                        </label>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Alleen actieve vragen worden getoond in de stemwijzer</p>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg">
                        Wijzigingen Opslaan
                    </button>
                    
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-center">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Related Actions -->
        <div class="mt-8 bg-purple-50 border border-purple-200 rounded-xl p-6">
            <h3 class="font-semibold text-purple-800 mb-4">Gerelateerde Acties</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="stemwijzer-standpunten-beheer.php?question_id=<?= $questionId ?>" 
                   class="flex items-center space-x-3 p-4 bg-white rounded-lg border border-purple-200 hover:bg-purple-50 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Standpunten Beheren</h4>
                        <p class="text-sm text-gray-600">Stel partij standpunten in voor deze vraag</p>
                    </div>
                </a>
                
                <a href="../controllers/stemwijzer.php" target="_blank"
                   class="flex items-center space-x-3 p-4 bg-white rounded-lg border border-purple-200 hover:bg-purple-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Preview Stemwijzer</h4>
                        <p class="text-sm text-gray-600">Bekijk hoe deze vraag eruit ziet</p>
                    </div>
                </a>
                
                <a href="stemwijzer-vraag-beheer.php" 
                   class="flex items-center space-x-3 p-4 bg-white rounded-lg border border-purple-200 hover:bg-purple-50 transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Alle Vragen</h4>
                        <p class="text-sm text-gray-600">Terug naar het vragen overzicht</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const counters = document.querySelectorAll('.character-counter');
    counters.forEach(counter => {
        const target = counter.dataset.target;
        const max = parseInt(counter.dataset.max);
        const input = document.getElementById(target);
        
        function updateCounter() {
            const length = input.value.length;
            counter.textContent = `${length}/${max}`;
            
            if (length > max * 0.9) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-gray-400');
            } else if (length > max * 0.7) {
                counter.classList.add('text-yellow-500');
                counter.classList.remove('text-gray-400', 'text-red-500');
            } else {
                counter.classList.remove('text-red-500', 'text-yellow-500');
                counter.classList.add('text-gray-400');
            }
        }
        
        input.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
    });
    
    // Form validation feedback
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-300');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-300');
                this.classList.add('border-gray-300');
            }
        });
    });
    
    // Auto-save draft (optional feature)
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Could implement auto-save functionality here
                console.log('Auto-save triggered (could save draft to localStorage)');
            }, 3000);
        });
    });
    
    // Warn user about unsaved changes
    let formChanged = false;
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            formChanged = true;
        });
    });
    
    form.addEventListener('submit', function() {
        formChanged = false;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'Je hebt onopgeslagen wijzigingen. Weet je zeker dat je de pagina wilt verlaten?';
        }
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 