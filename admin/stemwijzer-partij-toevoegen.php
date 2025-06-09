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
    $name = trim($_POST['name'] ?? '');
    $shortName = trim($_POST['short_name'] ?? '');
    $logoUrl = trim($_POST['logo_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validatie
    $errors = [];
    if (empty($name)) $errors[] = 'Partijnaam is verplicht';
    if (empty($shortName)) $errors[] = 'Afkorting is verplicht';
    
    // Check if name or short_name already exists
    if (!empty($name)) {
        try {
            $db->query("SELECT COUNT(*) as count FROM stemwijzer_parties WHERE name = :name");
            $db->bind(':name', $name);
            $existing = $db->single();
            if ($existing->count > 0) {
                $errors[] = 'Een partij met deze naam bestaat al';
            }
        } catch (Exception $e) {
            $errors[] = 'Fout bij controleren partijnaam: ' . $e->getMessage();
        }
    }
    
    if (!empty($shortName)) {
        try {
            $db->query("SELECT COUNT(*) as count FROM stemwijzer_parties WHERE short_name = :short_name");
            $db->bind(':short_name', $shortName);
            $existing = $db->single();
            if ($existing->count > 0) {
                $errors[] = 'Een partij met deze afkorting bestaat al';
            }
        } catch (Exception $e) {
            $errors[] = 'Fout bij controleren afkorting: ' . $e->getMessage();
        }
    }
    
    // Validate logo URL if provided
    if (!empty($logoUrl)) {
        if (!filter_var($logoUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Logo URL is niet geldig';
        }
    }
    
    if (empty($errors)) {
        try {
            // Check if description column exists, if not, insert without it
            $db->query("SHOW COLUMNS FROM stemwijzer_parties LIKE 'description'");
            $hasDescription = $db->single();
            
            if ($hasDescription) {
                // Partij toevoegen met description
                $db->query("INSERT INTO stemwijzer_parties (name, short_name, logo_url, description, created_at, updated_at) VALUES (:name, :short_name, :logo_url, :description, NOW(), NOW())");
                $db->bind(':name', $name);
                $db->bind(':short_name', $shortName);
                $db->bind(':logo_url', $logoUrl);
                $db->bind(':description', $description);
            } else {
                // Partij toevoegen zonder description
                $db->query("INSERT INTO stemwijzer_parties (name, short_name, logo_url, created_at, updated_at) VALUES (:name, :short_name, :logo_url, NOW(), NOW())");
                $db->bind(':name', $name);
                $db->bind(':short_name', $shortName);
                $db->bind(':logo_url', $logoUrl);
            }
            
            $db->execute();
            
            $message = 'Partij succesvol toegevoegd';
            $messageType = 'success';
            
            // Redirect naar partij beheer na 2 seconden
            header("refresh:2;url=stemwijzer-partij-beheer.php");
        } catch (Exception $e) {
            $errors[] = 'Fout bij toevoegen partij: ' . $e->getMessage();
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

.logo-preview {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

.logo-preview.show {
    max-height: 200px;
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
                        <a href="stemwijzer-partij-beheer.php" class="hover:text-white">Partijen Beheer</a>
                        <span class="mx-2">›</span> 
                        <span>Nieuwe Partij</span>
                    </nav>
                    <h1 class="text-4xl font-bold text-white mb-2">Nieuwe Partij Toevoegen</h1>
                    <p class="text-blue-100 text-lg">Voeg een nieuwe politieke partij toe aan de stemwijzer</p>
                </div>
                <div>
                    <a href="stemwijzer-partij-beheer.php" 
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
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Partij Details</h2>
                <p class="text-gray-600 text-sm">Vul alle velden in om een nieuwe partij toe te voegen</p>
            </div>
            
            <form method="POST" class="p-6 md:p-8 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Party Name -->
                    <div>
                        <div class="floating-label">
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   placeholder=" "
                                   maxlength="255"
                                   required
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <label for="name">Volledige Partijnaam</label>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-gray-500 text-sm">Officiële naam van de politieke partij</p>
                            <span class="character-counter text-xs text-gray-400" data-max="255" data-target="name">0/255</span>
                        </div>
                    </div>
                    
                    <!-- Short Name -->
                    <div>
                        <div class="floating-label">
                            <input type="text" 
                                   name="short_name" 
                                   id="short_name"
                                   placeholder=" "
                                   maxlength="20"
                                   required
                                   value="<?= htmlspecialchars($_POST['short_name'] ?? '') ?>"
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <label for="short_name">Afkorting</label>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-gray-500 text-sm">Korte afkorting (bijv. VVD, PvdA)</p>
                            <span class="character-counter text-xs text-gray-400" data-max="20" data-target="short_name">0/20</span>
                        </div>
                    </div>
                </div>
                
                <!-- Logo URL -->
                <div>
                    <div class="floating-label">
                        <input type="url" 
                               name="logo_url" 
                               id="logo_url"
                               placeholder=" "
                               value="<?= htmlspecialchars($_POST['logo_url'] ?? '') ?>"
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <label for="logo_url">Logo URL</label>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">Directe link naar het partijlogo (optioneel)</p>
                    
                    <!-- Logo Preview -->
                    <div id="logoPreview" class="logo-preview mt-4 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="flex items-center justify-center">
                            <div id="logoContainer" class="hidden">
                                <img id="logoImage" src="" alt="Logo preview" class="max-h-24 max-w-48 object-contain rounded">
                            </div>
                            <div id="logoPlaceholder" class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Logo preview verschijnt hier</p>
                            </div>
                        </div>
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
                                  class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <label for="description">Beschrijving</label>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-gray-500 text-sm">Korte beschrijving van de partij (optioneel)</p>
                        <span class="character-counter text-xs text-gray-400" data-max="1000" data-target="description">0/1000</span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 font-semibold shadow-lg">
                        Partij Toevoegen
                    </button>
                    
                    <a href="stemwijzer-partij-beheer.php" 
                       class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-center">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6">
            <h3 class="font-semibold text-green-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tips voor partij toevoegen
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-green-700 text-sm">
                <div>
                    <h4 class="font-medium mb-2">Partijnaam & Afkorting:</h4>
                    <ul class="space-y-1 text-xs">
                        <li>• Gebruik de officiële partijnaam</li>
                        <li>• Houd afkorting kort en herkenbaar</li>
                        <li>• Controleer op spelling fouten</li>
                        <li>• Zorg dat de naam uniek is</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium mb-2">Logo URL:</h4>
                    <ul class="space-y-1 text-xs">
                        <li>• Gebruik een directe link naar de afbeelding</li>
                        <li>• Formaten: JPG, PNG, SVG, GIF</li>
                        <li>• Aanbevolen: vierkant formaat</li>
                        <li>• Maximale grootte: 500KB</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Nederlandse Partijen Suggesties -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="font-semibold text-blue-800 mb-3">Nederlandse Politieke Partijen (2025)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>VVD</strong> - Volkspartij voor Vrijheid en Democratie
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>PVV</strong> - Partij voor de Vrijheid
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>GL-PvdA</strong> - GroenLinks-PvdA
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>NSC</strong> - Nieuw Sociaal Contract
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>D66</strong> - Democraten 66
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>BBB</strong> - BoerBurgerBeweging
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>CDA</strong> - Christen-Democratisch Appèl
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>SP</strong> - Socialistische Partij
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>PvdD</strong> - Partij voor de Dieren
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>FvD</strong> - Forum voor Democratie
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>SGP</strong> - Staatkundig Gereformeerde Partij
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>DENK</strong> - DENK
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>JA21</strong> - JA21
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <strong>Volt</strong> - Volt Nederland
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
    
    // Auto-generate short name from name
    const nameInput = document.getElementById('name');
    const shortNameInput = document.getElementById('short_name');
    
    nameInput.addEventListener('input', function() {
        if (shortNameInput.value === '' || shortNameInput.dataset.autoGenerated === 'true') {
            const name = this.value.trim();
            let shortName = '';
            
            // Generate short name from first letters of words
            const words = name.split(' ');
            if (words.length === 1) {
                shortName = name.substring(0, 8).toUpperCase();
            } else {
                words.forEach(word => {
                    if (word.length > 0) {
                        shortName += word.charAt(0).toUpperCase();
                    }
                });
            }
            
            shortNameInput.value = shortName.substring(0, 20);
            shortNameInput.dataset.autoGenerated = 'true';
        }
    });
    
    shortNameInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
    
    // Logo preview
    const logoUrlInput = document.getElementById('logo_url');
    const logoPreview = document.getElementById('logoPreview');
    const logoContainer = document.getElementById('logoContainer');
    const logoImage = document.getElementById('logoImage');
    const logoPlaceholder = document.getElementById('logoPlaceholder');
    
    function updateLogoPreview() {
        const url = logoUrlInput.value.trim();
        
        if (url && isValidUrl(url)) {
            logoImage.src = url;
            logoImage.onload = function() {
                logoContainer.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
                logoPreview.classList.add('show');
            };
            logoImage.onerror = function() {
                logoContainer.classList.add('hidden');
                logoPlaceholder.classList.remove('hidden');
                logoPreview.classList.remove('show');
            };
        } else {
            logoContainer.classList.add('hidden');
            logoPlaceholder.classList.remove('hidden');
            logoPreview.classList.remove('show');
        }
    }
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    logoUrlInput.addEventListener('input', updateLogoPreview);
    logoUrlInput.addEventListener('blur', updateLogoPreview);
    
    // Initial preview update
    updateLogoPreview();
    
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