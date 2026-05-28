<?php
require_once __DIR__ . '/_bootstrap.php';

$adminPageTitle = 'Presidenten';
$adminPageDescription = 'Presidenten beheren';
$adminActiveNav = 'presidenten';
require_once __DIR__ . '/partials/admin-header.php';

// Database verbinding
$db = new Database();
$message = '';
$messageType = '';
$logOutput = '';

// Verwerk formulier submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'run_migration':
            try {
                // Voer migratie uit
                $migrationPath = __DIR__ . '/../database/migrations/create_amerikaanse_presidenten_table.sql';
                $migrationContent = file_get_contents($migrationPath);
                
                if ($migrationContent === false) {
                    throw new Exception("Kan migratie bestand niet lezen");
                }
                
                $db->getConnection()->exec($migrationContent);
                $message = 'Amerikaanse presidenten tabel succesvol aangemaakt!';
                $messageType = 'success';
                $logOutput = "✅ Tabel migratie uitgevoerd\n✅ amerikaanse_presidenten tabel aangemaakt";
                
            } catch (Exception $e) {
                $message = 'Fout bij migratie: ' . $e->getMessage();
                $messageType = 'error';
                $logOutput = "❌ Migratie fout: " . $e->getMessage();
            }
            break;
            
        case 'populate_database':
            try {
                // Start output buffering to capture script output
                ob_start();
                
                // Include en run het populate script
                include __DIR__ . '/../scripts/populate_presidenten_database.php';
                
                $scriptOutput = ob_get_clean();
                
                // Parse de output voor succes/fout indicatie
                if (strpos($scriptOutput, '🎉 Amerikaanse Presidenten database populatie voltooid!') !== false) {
                    $message = 'Presidenten database succesvol gevuld!';
                    $messageType = 'success';
                } else {
                    $message = 'Database populatie uitgevoerd, controleer de output voor details';
                    $messageType = 'warning';
                }
                
                $logOutput = $scriptOutput;
                
            } catch (Exception $e) {
                $message = 'Fout bij database populatie: ' . $e->getMessage();
                $messageType = 'error';
                $logOutput = "❌ Populatie fout: " . $e->getMessage();
            }
            break;
            
        case 'fix_trump_data':
            try {
                // Start output buffering to capture script output
                ob_start();
                
                // Include en run het fix script
                include __DIR__ . '/../scripts/fix_trump_data.php';
                
                $scriptOutput = ob_get_clean();
                
                // Parse de output voor succes indicatie
                if (strpos($scriptOutput, '🎉 Alle 47 presidenten zijn succesvol toegevoegd!') !== false) {
                    $message = 'Alle 47 presidenten zijn nu in de database!';
                    $messageType = 'success';
                } else {
                    $message = 'Trump data fix uitgevoerd, controleer de output voor details';
                    $messageType = 'warning';
                }
                
                $logOutput = $scriptOutput;
                
            } catch (Exception $e) {
                $message = 'Fout bij Trump data fix: ' . $e->getMessage();
                $messageType = 'error';
                $logOutput = "❌ Fix fout: " . $e->getMessage();
            }
            break;
            
        case 'check_status':
            try {
                // Controleer database status
                $tableExists = false;
                $presidentCount = 0;
                
                try {
                    $db->query("SHOW TABLES LIKE 'amerikaanse_presidenten'");
                    $result = $db->single();
                    $tableExists = ($result !== false);
                    
                    if ($tableExists) {
                        $db->query("SELECT COUNT(*) as count FROM amerikaanse_presidenten");
                        $countResult = $db->single();
                        $presidentCount = $countResult->count;
                    }
                } catch (Exception $e) {
                    // Tabel bestaat waarschijnlijk niet
                }
                
                $logOutput = "=== DATABASE STATUS ===\n";
                $logOutput .= "Tabel amerikaanse_presidenten: " . ($tableExists ? "✅ Bestaat" : "❌ Bestaat niet") . "\n";
                $logOutput .= "Aantal presidenten: " . $presidentCount . "\n";
                
                if ($presidentCount == 47) {
                    $logOutput .= "✅ Alle 47 presidenten aanwezig (inclusief Trump's terugkeer)\n";
                } elseif ($presidentCount == 46) {
                    $logOutput .= "⚠️ 46 presidenten aanwezig, Trump's terugkeer (47) ontbreekt\n";
                } else {
                    $logOutput .= "⚠️ Onverwacht aantal presidenten\n";
                }
                
                $message = 'Database status gecontroleerd';
                $messageType = 'info';
                
            } catch (Exception $e) {
                $message = 'Fout bij status check: ' . $e->getMessage();
                $messageType = 'error';
                $logOutput = "❌ Status check fout: " . $e->getMessage();
            }
            break;
    }
}

// Haal huidige database statistieken op
$stats = [
    'table_exists' => false,
    'president_count' => 0,
    'current_president' => null
];

try {
    $db->query("SHOW TABLES LIKE 'amerikaanse_presidenten'");
    $result = $db->single();
    $stats['table_exists'] = ($result !== false);
    
    if ($stats['table_exists']) {
        $db->query("SELECT COUNT(*) as count FROM amerikaanse_presidenten");
        $countResult = $db->single();
        $stats['president_count'] = $countResult->count;
        
        // Haal huidige president op
        $db->query("SELECT naam, president_nummer FROM amerikaanse_presidenten WHERE is_huidig = 1 LIMIT 1");
        $currentResult = $db->single();
        $stats['current_president'] = $currentResult;
    }
} catch (Exception $e) {
    // Database errors negeren voor statistieken
}

?>

<main class="gradient-bg">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">🇺🇸 Amerikaanse Presidenten Beheer</h1>
                <p class="text-blue-200">Database scripts voor presidenten data</p>
            </div>
            <a href="dashboard.php" 
               class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                ← Terug naar Dashboard
            </a>
        </div>

        <!-- Status Messages -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg border <?= $messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                ($messageType === 'warning' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : 
                ($messageType === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-blue-50 border-blue-200 text-blue-800')) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Database Status Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Database Tabel</h3>
                        <p class="text-2xl font-bold mt-1 <?= $stats['table_exists'] ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $stats['table_exists'] ? 'Actief' : 'Niet aanwezig' ?>
                        </p>
                    </div>
                    <div class="text-3xl">
                        <?= $stats['table_exists'] ? '✅' : '❌' ?>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Aantal Presidenten</h3>
                        <p class="text-2xl font-bold mt-1 text-blue-600">
                            <?= $stats['president_count'] ?> / 47
                        </p>
                    </div>
                    <div class="text-3xl">
                        <?= $stats['president_count'] == 47 ? '🎉' : '📊' ?>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Huidige President</h3>
                        <p class="text-lg font-bold mt-1 text-purple-600">
                            <?= $stats['current_president'] ? "#{$stats['current_president']->president_nummer} {$stats['current_president']->naam}" : 'Geen' ?>
                        </p>
                    </div>
                    <div class="text-3xl">
                        🏛️
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Migratie Script -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden card-hover">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">1️⃣ Database Tabel Aanmaken</h2>
                    <p class="text-gray-600 text-sm">Maak de amerikaanse_presidenten tabel aan</p>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <span class="status-badge <?= $stats['table_exists'] ? 'status-success' : 'status-warning' ?>">
                            <?= $stats['table_exists'] ? '✅ Tabel bestaat' : '⚠️ Tabel ontbreekt' ?>
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4">
                        Dit script maakt de database tabel aan met alle benodigde velden voor presidentiële informatie.
                    </p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="run_migration">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            🚀 Migratie Uitvoeren
                        </button>
                    </form>
                </div>
            </div>

            <!-- Populate Script -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden card-hover">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">2️⃣ Database Vullen</h2>
                    <p class="text-gray-600 text-sm">Voeg alle 46 historische presidenten toe</p>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <span class="status-badge <?= $stats['president_count'] >= 46 ? 'status-success' : 'status-warning' ?>">
                            <?= $stats['president_count'] >= 46 ? '✅ Database gevuld' : '⚠️ Presidenten ontbreken' ?>
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4">
                        Vult de database met alle Amerikaanse presidenten van Washington tot Biden (1-46).
                    </p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="populate_database">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            📊 Database Vullen
                        </button>
                    </form>
                </div>
            </div>

            <!-- Fix Trump Data Script -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden card-hover">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">3️⃣ Trump Data Fix</h2>
                    <p class="text-gray-600 text-sm">Voeg Trump als 45e en 47e president toe</p>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <span class="status-badge <?= $stats['president_count'] == 47 ? 'status-success' : 'status-warning' ?>">
                            <?= $stats['president_count'] == 47 ? '✅ Trump toegevoegd' : '⚠️ Trump ontbreekt' ?>
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4">
                        Voegt Donald Trump toe als 45e president (2017-2021) en als 47e president (2025-2029).
                    </p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="fix_trump_data">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            🔧 Trump Data Fix
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Check -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden card-hover">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">📋 Status Check</h2>
                    <p class="text-gray-600 text-sm">Controleer database status</p>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Controleert de huidige status van de presidenten database.
                    </p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="check_status">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            🔍 Status Controleren
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script Output -->
        <?php if ($logOutput): ?>
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">📄 Script Output</h2>
                    <p class="text-gray-600 text-sm">Resultaat van de laatste uitgevoerde actie</p>
                </div>
                
                <div class="p-6">
                    <div class="log-output rounded-lg p-4">
                        <pre><?= htmlspecialchars($logOutput) ?></pre>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Add loading states to buttons
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<div class="loading-spinner mr-2"></div>Uitvoeren...';
            button.disabled = true;
            
            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 5000);
        });
    });
});
</script>

<?php require_once __DIR__ . '/partials/admin-footer.php';
?> 