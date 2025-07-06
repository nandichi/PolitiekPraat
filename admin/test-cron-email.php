<?php
// Test pagina voor cron job email functionaliteit
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../includes/mail_helper.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

// Verwerk test email verzending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testType = $_POST['test_type'] ?? '';
    $testSent = false;
    
    try {
        switch ($testType) {
            case 'basic':
                $testSent = sendTestCronEmail();
                $testMessage = "Basis test email verstuurd";
                break;
                
            case 'success':
                $testSent = sendCronJobEmail(
                    'Test Success Job',
                    'success',
                    'Dit is een test van een succesvolle cron job.',
                    "Test details:\n- Alles werkt correct\n- Geen fouten gevonden\n- Performance: OK"
                );
                $testMessage = "Success test email verstuurd";
                break;
                
            case 'warning':
                $testSent = sendCronJobEmail(
                    'Test Warning Job',
                    'warning',
                    'Dit is een test van een cron job met waarschuwingen.',
                    "Test details:\n- Job voltooid maar met waarschuwingen\n- Enkele bronnen niet beschikbaar\n- Performance: Traag"
                );
                $testMessage = "Warning test email verstuurd";
                break;
                
            case 'error':
                $testSent = sendCronJobEmail(
                    'Test Error Job',
                    'error',
                    'Dit is een test van een gefaalde cron job.',
                    "Test details:\n- FOUT: Database connectie mislukt\n- Stack trace: Test stack trace\n- Tijd: " . date('Y-m-d H:i:s')
                );
                $testMessage = "Error test email verstuurd";
                break;
                
            case 'all':
                $basicResult = sendTestCronEmail();
                $successResult = sendCronJobEmail('Test Success', 'success', 'Success test');
                $warningResult = sendCronJobEmail('Test Warning', 'warning', 'Warning test');
                $errorResult = sendCronJobEmail('Test Error', 'error', 'Error test');
                
                $testSent = $basicResult && $successResult && $warningResult && $errorResult;
                $testMessage = "Alle test emails verstuurd";
                break;
        }
        
        if ($testSent) {
            $successMessage = $testMessage . " naar naoufal.exe14@gmail.com";
        } else {
            $errorMessage = "Fout bij verzenden van test email";
        }
        
    } catch (Exception $e) {
        $errorMessage = "Fout bij verzenden van test email: " . $e->getMessage();
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
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.test-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Test Cron Job Emails</h1>
                    <p class="text-blue-100 text-lg">Test de email functionaliteit voor cron jobs</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        Dashboard
                    </a>
                    <a href="news-scraper-beheer.php" 
                       class="bg-white text-blue-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold">
                        News Scraper
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Succes/Error Messages -->
        <?php if (isset($successMessage)): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800"><?= $successMessage ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800"><?= $errorMessage ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Test Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Basic Test -->
            <div class="test-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Basis Test</h3>
                        <p class="text-sm text-gray-600">Eenvoudige test email</p>
                    </div>
                </div>
                
                <form method="POST" class="w-full">
                    <input type="hidden" name="test_type" value="basic">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Test Verzenden
                    </button>
                </form>
            </div>
            
            <!-- Success Test -->
            <div class="test-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Success Test</h3>
                        <p class="text-sm text-gray-600">Succesvolle job email</p>
                    </div>
                </div>
                
                <form method="POST" class="w-full">
                    <input type="hidden" name="test_type" value="success">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Test Verzenden
                    </button>
                </form>
            </div>
            
            <!-- Warning Test -->
            <div class="test-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Warning Test</h3>
                        <p class="text-sm text-gray-600">Waarschuwing email</p>
                    </div>
                </div>
                
                <form method="POST" class="w-full">
                    <input type="hidden" name="test_type" value="warning">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Test Verzenden
                    </button>
                </form>
            </div>
            
            <!-- Error Test -->
            <div class="test-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Error Test</h3>
                        <p class="text-sm text-gray-600">Fout melding email</p>
                    </div>
                </div>
                
                <form method="POST" class="w-full">
                    <input type="hidden" name="test_type" value="error">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Test Verzenden
                    </button>
                </form>
            </div>
            
            <!-- All Tests -->
            <div class="test-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover md:col-span-2">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Alle Tests</h3>
                        <p class="text-sm text-gray-600">Verzend alle test emails tegelijk</p>
                    </div>
                </div>
                
                <form method="POST" class="w-full">
                    <input type="hidden" name="test_type" value="all">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-500 to-violet-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-purple-600 hover:to-violet-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Alle Tests Verzenden
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Email Configuration Info -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Email Configuratie</h2>
                <p class="text-gray-600 text-sm">Huidige SMTP instellingen</p>
            </div>
            
            <div class="p-6">
                <?php
                $mailConfig = include '../includes/mail_config.php';
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">SMTP Instellingen</h3>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Host:</span> <?= $mailConfig['smtp_host'] ?></div>
                            <div><span class="font-medium">Port:</span> <?= $mailConfig['smtp_port'] ?></div>
                            <div><span class="font-medium">Security:</span> <?= $mailConfig['smtp_secure'] ?></div>
                            <div><span class="font-medium">Username:</span> <?= $mailConfig['smtp_username'] ?></div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Email Instellingen</h3>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">From:</span> <?= $mailConfig['from_email'] ?></div>
                            <div><span class="font-medium">Name:</span> <?= $mailConfig['from_name'] ?></div>
                            <div><span class="font-medium">Target:</span> naoufal.exe14@gmail.com</div>
                            <div><span class="font-medium">Status:</span> <span class="text-green-600">Actief</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../views/templates/footer.php'; ?> 