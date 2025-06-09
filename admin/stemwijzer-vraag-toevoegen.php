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
            // Controleer of volgorde nummer al bestaat
            $db->query("SELECT COUNT(*) as count FROM stemwijzer_questions WHERE order_number = :order_number");
            $db->bind(':order_number', $orderNumber);
            $existing = $db->single();
            
            if ($existing->count > 0) {
                $errors[] = 'Volgorde nummer ' . $orderNumber . ' is al in gebruik';
            } else {
                // Vraag toevoegen
                $db->query("INSERT INTO stemwijzer_questions (title, description, context, left_view, right_view, order_number, is_active, created_at, updated_at) VALUES (:title, :description, :context, :left_view, :right_view, :order_number, :is_active, NOW(), NOW())");
                
                $db->bind(':title', $title);
                $db->bind(':description', $description);
                $db->bind(':context', $context);
                $db->bind(':left_view', $leftView);
                $db->bind(':right_view', $rightView);
                $db->bind(':order_number', $orderNumber);
                $db->bind(':is_active', $isActive);
                
                $db->execute();
                
                $message = 'Vraag succesvol toegevoegd';
                $messageType = 'success';
                
                // Redirect naar vraag beheer na 2 seconden
                header("refresh:2;url=stemwijzer-vraag-beheer.php");
            }
        } catch (Exception $e) {
            $errors[] = 'Fout bij toevoegen vraag: ' . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}

// Bepaal volgende volgorde nummer
try {
    $db->query("SELECT MAX(order_number) as max_order FROM stemwijzer_questions");
    $result = $db->single();
    $nextOrderNumber = ($result->max_order ?? 0) + 1;
} catch (Exception $e) {
    $nextOrderNumber = 1;
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
                        <span>Nieuwe Vraag</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Nieuwe Vraag Toevoegen</h1>
                    <p class="text-blue-100 text-lg">Voeg een nieuwe stemwijzer vraag toe</p>
                </div>
                <div>
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
        
        <!-- Form Card -->
        <div class="form-card rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Vraag Details</h2>
                <p class="text-gray-600 text-sm">Vul alle velden in om een nieuwe vraag toe te voegen</p>
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
                                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
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
                                   value="<?= htmlspecialchars($_POST['order_number'] ?? $nextOrderNumber) ?>"
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <label for="order_number">Volgorde Nummer</label>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Volgende: <?= $nextOrderNumber ?></p>
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
                                  class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
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
                                  class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"><?= htmlspecialchars($_POST['context'] ?? '') ?></textarea>
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
                                      class="w-full px-3 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none bg-green-50/30"><?= htmlspecialchars($_POST['left_view'] ?? '') ?></textarea>
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
                                      class="w-full px-3 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none bg-red-50/30"><?= htmlspecialchars($_POST['right_view'] ?? '') ?></textarea>
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
                               <?= isset($_POST['is_active']) || !isset($_POST['title']) ? 'checked' : '' ?>
                               class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Vraag direct activeren
                        </label>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Alleen actieve vragen worden getoond in de stemwijzer</p>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg">
                        Vraag Toevoegen
                    </button>
                    
                    <a href="stemwijzer-vraag-beheer.php" 
                       class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-center">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tips voor goede vragen
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-blue-700 text-sm">
                <div>
                    <h4 class="font-medium mb-2">Vraag Formulering:</h4>
                    <ul class="space-y-1 text-xs">
                        <li>• Houd de vraag kort en duidelijk</li>
                        <li>• Vermijd jargon en moeilijke woorden</li>
                        <li>• Formuleer als stellende zin</li>
                        <li>• Zorg dat er verschillende meningen mogelijk zijn</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium mb-2">Perspectief Beschrijvingen:</h4>
                    <ul class="space-y-1 text-xs">
                        <li>• Beschrijf beide kanten eerlijk</li>
                        <li>• Gebruik neutrale taal</li>
                        <li>• Leg de kernargumenten uit</li>
                        <li>• Vermijd extreme standpunten</li>
                    </ul>
                </div>
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
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 