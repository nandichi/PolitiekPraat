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
        $questionId = $_POST['question_id'] ?? 0;
        $isActive = $_POST['is_active'] ?? 0;
        
        try {
            $db->query("UPDATE stemwijzer_questions SET is_active = :is_active WHERE id = :id");
            $db->bind(':is_active', $isActive);
            $db->bind(':id', $questionId);
            $db->execute();
            
            $message = 'Status succesvol bijgewerkt';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij bijwerken status: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'delete_question') {
        $questionId = $_POST['question_id'] ?? 0;
        
        try {
            // Verwijder eerst alle standpunten voor deze vraag
            $db->query("DELETE FROM stemwijzer_positions WHERE question_id = :question_id");
            $db->bind(':question_id', $questionId);
            $db->execute();
            
            // Verwijder de vraag zelf
            $db->query("DELETE FROM stemwijzer_questions WHERE id = :id");
            $db->bind(':id', $questionId);
            $db->execute();
            
            $message = 'Vraag succesvol verwijderd';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Fout bij verwijderen vraag: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Haal alle vragen op
try {
    $db->query("SELECT id, title, description, context, left_view, right_view, order_number, is_active, created_at, updated_at FROM stemwijzer_questions ORDER BY order_number ASC, created_at DESC");
    $questions = $db->resultSet();
} catch (Exception $e) {
    $questions = [];
    $message = 'Fout bij ophalen vragen: ' . $e->getMessage();
    $messageType = 'error';
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

.question-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.question-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #10b981;
}

input:checked + .slider:before {
    transform: translateX(24px);
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
                        <span>Vragen Beheer</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Vragen Beheer</h1>
                    <p class="text-blue-100 text-lg">Beheer alle stemwijzer vragen en hun volgorde</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-vraag-toevoegen.php" 
                       class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Nieuwe Vraag</span>
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
                    <p class="text-gray-600">Totaal Vragen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600"><?= count(array_filter($questions, fn($q) => $q->is_active)) ?></p>
                    <p class="text-gray-600">Actieve Vragen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-red-600"><?= count(array_filter($questions, fn($q) => !$q->is_active)) ?></p>
                    <p class="text-gray-600">Inactieve Vragen</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600"><?= !empty($questions) ? max(array_column($questions, 'order_number')) : 0 ?></p>
                    <p class="text-gray-600">Laatste Volgorde</p>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Alle Vragen (<?= count($questions) ?>)</h2>
            </div>
            
            <?php if (empty($questions)): ?>
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Nog geen vragen</h3>
                    <p class="text-gray-500 mb-6">Begin met het toevoegen van je eerste stemwijzer vraag</p>
                    <a href="stemwijzer-vraag-toevoegen.php" 
                       class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Eerste Vraag Toevoegen</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($questions as $question): ?>
                        <div class="p-6 question-card hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Question Header -->
                                    <div class="flex items-center space-x-4 mb-3">
                                        <div class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                                            Volgorde <?= $question->order_number ?>
                                        </div>
                                        
                                        <!-- Active Toggle -->
                                        <form method="POST" class="inline-block">
                                            <input type="hidden" name="action" value="toggle_active">
                                            <input type="hidden" name="question_id" value="<?= $question->id ?>">
                                            <label class="toggle-switch">
                                                <input type="checkbox" 
                                                       name="is_active" 
                                                       value="<?= $question->is_active ? 0 : 1 ?>"
                                                       <?= $question->is_active ? 'checked' : '' ?>
                                                       onchange="this.form.submit()">
                                                <span class="slider"></span>
                                            </label>
                                        </form>
                                        
                                        <span class="text-sm text-gray-500">
                                            <?= $question->is_active ? '✓ Actief' : '○ Inactief' ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Question Title -->
                                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($question->title) ?></h3>
                                    
                                    <!-- Question Description -->
                                    <p class="text-gray-600 mb-4 leading-relaxed"><?= htmlspecialchars($question->description) ?></p>
                                    
                                    <!-- Context & Views (Collapsible) -->
                                    <div class="space-y-3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                <h4 class="font-semibold text-green-800 text-sm mb-1">Links Perspectief</h4>
                                                <p class="text-green-700 text-sm"><?= htmlspecialchars($question->left_view) ?></p>
                                            </div>
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                <h4 class="font-semibold text-red-800 text-sm mb-1">Rechts Perspectief</h4>
                                                <p class="text-red-700 text-sm"><?= htmlspecialchars($question->right_view) ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <h4 class="font-semibold text-blue-800 text-sm mb-1">Context</h4>
                                            <p class="text-blue-700 text-sm"><?= htmlspecialchars($question->context) ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Metadata -->
                                    <div class="flex items-center space-x-4 mt-4 text-sm text-gray-500">
                                        <span>Aangemaakt: <?= formatDate($question->created_at) ?></span>
                                        <?php if ($question->updated_at && $question->updated_at !== $question->created_at): ?>
                                            <span>Bijgewerkt: <?= formatDate($question->updated_at) ?></span>
                                        <?php endif; ?>
                                        <span>ID: <?= $question->id ?></span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-2 ml-6">
                                    <a href="stemwijzer-vraag-bewerken.php?id=<?= $question->id ?>" 
                                       class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm font-medium text-center">
                                        Bewerken
                                    </a>
                                    
                                    <a href="stemwijzer-standpunten-beheer.php?question_id=<?= $question->id ?>" 
                                       class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm font-medium text-center">
                                        Standpunten
                                    </a>
                                    
                                    <form method="POST" class="inline-block" 
                                          onsubmit="return confirm('Ben je zeker dat je deze vraag wilt verwijderen? Alle bijbehorende standpunten worden ook verwijderd.')">
                                        <input type="hidden" name="action" value="delete_question">
                                        <input type="hidden" name="question_id" value="<?= $question->id ?>">
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for status toggles
    const toggles = document.querySelectorAll('input[type="checkbox"][name="is_active"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function(e) {
            e.preventDefault();
            const isActive = this.checked;
            const action = isActive ? 'activeren' : 'deactiveren';
            
            if (confirm(`Ben je zeker dat je deze vraag wilt ${action}?`)) {
                this.form.submit();
            } else {
                this.checked = !this.checked;
            }
        });
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 